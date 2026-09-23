<?php
/**
 * Backend AJAX para procesar la importación de datos SIP vía Web Service
 * Compatible con PHP 5.3+
 */
require_once(dirname(__FILE__) . "/../../config.php");
require_once(dirname(__FILE__) . "/clases/SipWsClient.php");
require_once(dirname(__FILE__) . "/clases/WsConsultasLog.php");

// Evitar que el script se corte por tiempo límite o memoria en importaciones masivas
@set_time_limit(0);
@ini_set('max_execution_time', 0);
@ini_set('memory_limit', '1024M');
if (function_exists('ignore_user_abort')) {
    @ignore_user_abort(true);
}

// Configurar encabezado JSON
header('Content-Type: application/json; charset=utf-8');

// Desactivar buffer de salida no deseado
if (ob_get_length()) ob_clean();

$accion = isset($_POST['accion']) ? $_POST['accion'] : (isset($_GET['accion']) ? $_GET['accion'] : 'importar');

// Usuario en sesión
$usuarioActual = 'sistema';
if (isset($_ses_user['name']) && !empty($_ses_user['name'])) {
    $usuarioActual = $_ses_user['name'];
} elseif (isset($_ses_user['login']) && !empty($_ses_user['login'])) {
    $usuarioActual = $_ses_user['login'];
}

// --------------------------------------------------------------------------
// ACCIÓN: VER CONSULTAS ASOCIADAS A UN LOG
// --------------------------------------------------------------------------
if ($accion == 'ver_consultas') {
    $idLog = isset($_POST['id_log']) ? intval($_POST['id_log']) : (isset($_GET['id_log']) ? intval($_GET['id_log']) : 0);
    if ($idLog <= 0) {
        echo json_encode(array("success" => false, "error" => "ID de log inválido"));
        exit;
    }

    $log = WsConsultasLog::getLogById($idLog);
    $consultas = WsConsultasLog::getConsultasByLog($idLog, 300);

    echo json_encode(array(
        "success" => true,
        "log" => $log,
        "total" => count($consultas),
        "consultas" => $consultas
    ));
    exit;
}

// --------------------------------------------------------------------------
// ACCIÓN: VER JSON COMPLETO DE UNA CONSULTA
// --------------------------------------------------------------------------
if ($accion == 'ver_json') {
    $idConsulta = isset($_POST['id_ws_consulta']) ? $_POST['id_ws_consulta'] : (isset($_GET['id_ws_consulta']) ? $_GET['id_ws_consulta'] : '');
    if (empty($idConsulta)) {
        echo json_encode(array("success" => false, "error" => "ID de consulta no especificado"));
        exit;
    }

    $row = WsConsultasLog::getConsultaJson($idConsulta);
    if (!$row) {
        echo json_encode(array("success" => false, "error" => "No se encontró el registro solicitado"));
        exit;
    }

    $rawJson = $row['datos_completos_json'];
    // Decodificar y re-codificar con formato legible
    $parsed = json_decode($rawJson, true);
    
    echo json_encode(array(
        "success" => true,
        "id_consulta_ws" => $row['id_consulta_ws'],
        "paciente_documento" => $row['paciente_documento'],
        "paciente_nombre" => $row['paciente_nombre'],
        "json_data" => $parsed ? $parsed : $rawJson
    ));
    exit;
}

// --------------------------------------------------------------------------
// ACCIÓN: EJECUTAR IMPORTACIÓN WEB SERVICE POR RANGO DE FECHAS
// --------------------------------------------------------------------------
if ($accion == 'importar') {
    $desde = isset($_POST['desde']) ? trim($_POST['desde']) : '';
    $hasta = isset($_POST['hasta']) ? trim($_POST['hasta']) : '';
    $efector = isset($_POST['efector']) ? trim($_POST['efector']) : '';

    if (empty($desde) || empty($hasta)) {
        echo json_encode(array(
            "success" => false,
            "error" => "Debe ingresar tanto la fecha 'Desde' como la fecha 'Hasta'."
        ));
        exit;
    }

    $desdeWs = SipWsClient::formatToWsDate($desde);
    $hastaWs = SipWsClient::formatToWsDate($hasta);

    $opciones = array();
    if (!empty($efector) && $efector != '- Todos -') {
        $opciones['healthCenterCode'] = $efector;
    }

    $tiempoInicio = microtime(true);

    // 1. Iniciar registro de log en base de datos
    $parametrosLog = json_encode(array(
        "desde" => $desdeWs,
        "hasta" => $hastaWs,
        "efector" => !empty($efector) ? $efector : 'TODOS'
    ));
    $idLog = WsConsultasLog::iniciarLog($desdeWs, $hastaWs, $usuarioActual, $parametrosLog);

    if (!$idLog) {
        echo json_encode(array(
            "success" => false,
            "error" => "No se pudo inicializar la transacción de log en la base de datos (esquema sip_clap)."
        ));
        exit;
    }

    // 2. Ejecutar llamada al cliente Web Service
    $client = new SipWsClient();
    $consultas = $client->searchConsultations($desdeWs, $hastaWs, $opciones);

    if ($consultas === false) {
        $errorMsg = $client->getLastError();
        $tiempoTotal = microtime(true) - $tiempoInicio;
        WsConsultasLog::finalizarLog($idLog, 0, 0, 0, 'ERROR', $errorMsg, $tiempoTotal);

        echo json_encode(array(
            "success" => false,
            "id_log" => $idLog,
            "error" => $errorMsg,
            "duracion" => sprintf('%.2f', $tiempoTotal)
        ));
        exit;
    }

    // 3. Procesar y persistir registros recibidos
    $totalImportados = count($consultas);
    $guardados = 0;
    $errores = 0;
    $erroresDetalle = array();

    if ($totalImportados > 0) {
        foreach ($consultas as $idx => $record) {
            if ($idx % 50 === 0) {
                @set_time_limit(0);
            }
            $ok = WsConsultasLog::guardarConsulta($idLog, $record);
            if ($ok) {
                $guardados++;
            } else {
                $errores++;
                $recId = isset($record['id']) ? $record['id'] : "pos_$idx";
                $erroresDetalle[] = "Error al guardar consulta WS ID: $recId";
            }
        }
    }

    $tiempoTotal = microtime(true) - $tiempoInicio;
    
    // Determinar estado de la transacción
    if ($errores == 0) {
        $estado = 'EXITO';
        $mensajeFinal = ($totalImportados > 0) 
            ? "Importación completada con éxito. $guardados registros procesados y guardados."
            : "Consulta exitosa. No se encontraron registros en el rango de fechas seleccionado.";
    } elseif ($guardados > 0 && $errores > 0) {
        $estado = 'PARCIAL';
        $mensajeFinal = "Importación parcial: $guardados guardados, $errores errores. " . implode(", ", array_slice($erroresDetalle, 0, 3));
    } else {
        $estado = 'ERROR';
        $mensajeFinal = "Fallaron todos los registros al intentar guardar en base de datos. " . implode(", ", array_slice($erroresDetalle, 0, 3));
    }

    // 4. Actualizar log final
    WsConsultasLog::finalizarLog($idLog, $totalImportados, $guardados, $errores, $estado, $mensajeFinal, $tiempoTotal);

    // 5. Generar fila HTML para la grilla
    $fechaEjecucionTxt = date('d/m/Y H:i:s');
    $badgeClass = ($estado == 'EXITO') ? 'label-success' : (($estado == 'PARCIAL') ? 'label-warning' : 'label-danger');
    $badgeStyle = ($estado == 'EXITO') ? 'background-color:#28a745;color:#fff;' : (($estado == 'PARCIAL') ? 'background-color:#ffc107;color:#333;' : 'background-color:#dc3545;color:#fff;');

    $filaHtml = '<tr class="fila_ws" id="ws_log_' . $idLog . '">
        <td style="font-weight:bold;">' . $idLog . '</td>
        <td>' . $fechaEjecucionTxt . '</td>
        <td><span style="font-weight:600;">' . $desdeWs . '</span> al <span style="font-weight:600;">' . $hastaWs . '</span></td>
        <td><span class="badge" style="background:#5bc0de;color:#fff;">' . $totalImportados . '</span></td>
        <td><span class="badge" style="background:#5cb85c;color:#fff;">' . $guardados . '</span></td>
        <td><span class="badge" style="' . ($errores > 0 ? 'background:#d9534f;color:#fff;' : 'background:#eee;color:#888;') . '">' . $errores . '</span></td>
        <td><span class="label ' . $badgeClass . '" style="padding:4px 8px;border-radius:4px;' . $badgeStyle . '">' . $estado . '</span></td>
        <td>' . htmlspecialchars($usuarioActual) . '</td>
        <td>
            ' . ($guardados > 0 ? '<button type="button" class="btn btn-xs btn-info btn-ver-consultas" data-id="' . $idLog . '" title="Ver ' . $guardados . ' registros"><i class="glyphicon glyphicon-list"></i> Ver Consultas</button>' : '<span class="text-muted">-</span>') . '
        </td>
    </tr>';

    echo json_encode(array(
        "success" => true,
        "id_log" => $idLog,
        "fecha_ejecucion" => $fechaEjecucionTxt,
        "desde" => $desdeWs,
        "hasta" => $hastaWs,
        "importados" => $totalImportados,
        "guardados" => $guardados,
        "errores" => $errores,
        "estado" => $estado,
        "mensaje" => $mensajeFinal,
        "duracion" => sprintf('%.2f', $tiempoTotal),
        "tr" => $filaHtml
    ));
    exit;
}

echo json_encode(array("success" => false, "error" => "Acción desconocida"));
