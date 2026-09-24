<?php
require_once("../../config.php");

// Atender peticiones AJAX de Web Service heredando los permisos de lotes_exportacion
if (isset($_POST['accion_ws']) || isset($_GET['accion_ws'])) {
    @set_time_limit(0);
    @ini_set('max_execution_time', 0);
    @ini_set('memory_limit', '1024M');
    if (function_exists('ignore_user_abort')) {
        @ignore_user_abort(true);
    }
    $_POST['accion'] = isset($_POST['accion_ws']) ? $_POST['accion_ws'] : $_GET['accion_ws'];
    require_once("procesar_importacion_ws.php");
    exit;
}

echo $html_header;

require_once("clases/LotesProceso.php"); 
require_once("clases/WsConsultasLog.php");
require_once("../../clases/Smiefectores.php");

// Lista de efectores tipo Hospital
$listEfectores = array();
$efectoresSmi = new SmiefectoresColeccion();
$efectores = $efectoresSmi->traeCuiesPorUsuario($_ses_user["id"]);
$codHosp = array("HOS", "HOS1", "HOS2", "HOS3");
if (is_array($efectores)) {
    foreach ($efectores as $key => $value) {
        $efector = $efectoresSmi->buscarPorCUIE($value);
        if ($efector && in_array($efector->getTipoEfector(), $codHosp)) {
            $listEfectores[] = array(
                "cuie" => $efector->getCuie(),
                "nombre" => utf8_encode($efector->getNombreefector()),
            );
        }
    }
}

// Lotes históricos de exportación MDB
$lotesProceso = new LotesProceso();
$lotes = $lotesProceso->getLotes();

// Logs históricos de importación Web Service
$logsWs = WsConsultasLog::getLogs(50);
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/sprites.css">
<link rel="stylesheet" type="text/css" href="../../lib/css/general.css">
<script type='text/javascript' src='../../lib/jquery/ui/jquery.ui.datepicker-es.js'></script>
<style>
    .nav-tabs > li > a {
        font-weight: bold;
        font-size: 13px;
    }
    .panel-sip {
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        border: 1px solid #dcdcdc;
        background: #fff;
        margin-bottom: 20px;
        padding: 15px;
    }
    td {
        text-align: center !important; 
        font-size: 11px !important; 
    }
    th {
        text-align: center !important;
        font-size: 11px !important;
    }
    #btnProcesar {
        border: 1px solid #c0c0c0;
        border-radius: 6px;
        cursor: pointer;
        margin: 10px;
        padding: 8px 16px;
        background: #f7f7f7;
        font-weight: bold;
        display: inline-block;
        text-decoration: none;
        color: #333;
    }
    #btnProcesar:hover {
        box-shadow: 0 0 5px powderblue;
        background: #eef5fb;
    }
    .btn-ws-importar {
        padding: 9px 24px;
        font-weight: bold;
        font-size: 13px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .datepicker-export {
        width: 115px;
        display: inline-block;
        text-align: center;
        font-size: 11px !important;
        height: 28px !important;
        padding: 2px 6px !important;
    }
    .datepicker-ws {
        font-size: 11px !important;
        height: 28px !important;
        padding: 2px 6px !important;
        text-align: center !important;
        letter-spacing: 0.5px;
    }
    .ui-datepicker-calendar.hide-calendar {
        display: none !important;
    }
    .tablagenerica th {
        background-color: #555577;
        color: #fff;
        padding: 6px;
    }
    .tablagenerica tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .tablagenerica tr:hover {
        background-color: #f0f4ff;
    }
    .ws-metric-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px;
        text-align: center;
    }
    .ws-metric-val {
        font-size: 20px;
        font-weight: bold;
        color: #2b6cb0;
    }
    .ws-metric-lbl {
        font-size: 11px;
        color: #718096;
        text-transform: uppercase;
    }
    pre.json-view {
        background-color: #1e1e1e;
        color: #d4d4d4;
        padding: 12px;
        border-radius: 6px;
        max-height: 480px;
        overflow-y: auto;
        font-size: 12px;
        font-family: Consolas, monospace;
    }
    /* Overlay bloqueante de pantalla completa durante la importación */
    #ws_block_overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.82);
        z-index: 9999999;
        display: none;
        cursor: wait;
        user-select: none;
        -webkit-user-select: none;
    }
    #ws_block_box {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%);
        background: #ffffff;
        padding: 35px 40px;
        border-radius: 12px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.6);
        max-width: 520px;
        width: 90%;
        text-align: center;
        cursor: default;
    }
    .ws-spinner-pulse {
        display: inline-block;
        width: 48px;
        height: 48px;
        border: 4px solid #e2e8f0;
        border-top-color: #3182ce;
        border-radius: 50%;
        animation: ws_spin 1s linear infinite;
        -webkit-animation: ws_spin 1s linear infinite;
        margin-bottom: 16px;
    }
    @keyframes ws_spin {
        to { transform: rotate(360deg); }
    }
    @-webkit-keyframes ws_spin {
        to { -webkit-transform: rotate(360deg); }
    }
</style>

<div class="container-fluid" style="padding: 15px 25px;">

    <!-- NAVEGACIÓN POR PESTAÑAS (TABS) -->
    <ul class="nav nav-tabs" id="sip_tabs" style="margin-bottom: 20px;">
        <li class="active">
            <a href="#tab_importacion" data-toggle="tab">
                <i class="glyphicon glyphicon-cloud-download"></i> Importación Web Service SIP-CLAP
            </a>
        </li>
        <li>
            <a href="#tab_exportacion" data-toggle="tab">
                <i class="glyphicon glyphicon-export"></i> Exportación de Fichas SIP (MDB)
            </a>
        </li>
    </ul>

    <div class="tab-content">

        <!-- =================================================================== -->
        <!-- PESTAÑA 1: IMPORTACIÓN WEB SERVICE SIP-CLAP (NUEVA FUNCIONALIDAD)   -->
        <!-- =================================================================== -->
        <div class="tab-pane active" id="tab_importacion">
            <div class="panel-sip">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3 class="titulo_pagina" style="margin-top:5px; margin-bottom: 15px;">
                            <i class="glyphicon glyphicon-cloud-download text-primary"></i> 
                            Importaci&oacute;n de Consultas Perinatales SIP vía Web Service
                        </h3>
                        <p class="text-muted" style="margin-bottom: 20px;">
                            Conectividad directa con el bus provincial SIP-CLAP. Los datos se persisten íntegramente en el esquema <code>sip_clap</code> con auditoría de transacciones.
                        </p>
                    </div>
                </div>

                <!-- Formulario de Importación -->
                <div class="row" style="background: #fbfbfd; padding: 18px 15px; border-radius: 6px; border: 1px solid #e9ecef; margin: 0 10px 20px 10px;">
                    <div class="col-md-4 col-sm-4 text-center">
                        <label for="ws_desde" style="font-weight:bold; font-size:11px; color:#444; display:block; margin-bottom:4px;">Fecha Desde:</label>
                        <div class="input-group" style="max-width: 155px; margin: 0 auto;">
                            <span class="input-group-addon" style="padding: 3px 8px; font-size: 11px;"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input type="text" class="form-control datepicker-ws" name="ws_desde" id="ws_desde" placeholder="DD/MM/AAAA" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 text-center">
                        <label for="ws_hasta" style="font-weight:bold; font-size:11px; color:#444; display:block; margin-bottom:4px;">Fecha Hasta:</label>
                        <div class="input-group" style="max-width: 155px; margin: 0 auto;">
                            <span class="input-group-addon" style="padding: 3px 8px; font-size: 11px;"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input type="text" class="form-control datepicker-ws" name="ws_hasta" id="ws_hasta" placeholder="DD/MM/AAAA" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 text-center" style="padding-top: 16px;">
                        <button type="button" id="btnImportarWs" class="btn btn-primary btn-ws-importar" style="font-size: 12px; padding: 6px 20px;">
                            <i class="glyphicon glyphicon-cloud-download"></i> EJECUTAR IMPORTACIÓN
                        </button>
                    </div>
                </div>

                <!-- Indicador de carga y estado -->
                <div id="ws_loading" style="display:none; margin: 20px 0; text-align: center;">
                    <div class="alert alert-info" style="display: inline-block; padding: 12px 25px; margin: 0 auto;">
                        <img src="imagenes/loading.gif" width="22px" height="22px" style="vertical-align: middle; margin-right: 10px;"/>
                        <span id="ws_loading_text" style="font-size: 13px; font-weight: bold; vertical-align: middle;">
                            Conectando con el Web Service y solicitando token de autenticación...
                        </span>
                    </div>
                </div>

                <!-- Alerta de resultados -->
                <div id="ws_alert_box" style="display:none; margin: 15px 10px;"></div>

                <!-- Historial de ejecuciones -->
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                            <h4 style="font-weight:bold; margin:0;">
                                <i class="glyphicon glyphicon-time text-info"></i> Registro Persistido de Ejecuciones
                            </h4>
                            <span class="text-muted" style="font-size:11px;">Últimas 50 transacciones registradas</span>
                        </div>
                        <div class="table-responsive">
                            <table id="lista_ws_logs" class="tablagenerica table table-bordered table-striped" style="width:100%;">
                                <thead>
                                    <tr style="background:#4a5568; color:#fff;">
                                        <th style="width: 50px;"># Log</th>
                                        <th style="width: 150px;">Fecha Ejecución</th>
                                        <th style="width: 180px;">Rango Solicitado</th>
                                        <th style="width: 100px;">Importados</th>
                                        <th style="width: 100px;">Guardados</th>
                                        <th style="width: 90px;">Errores</th>
                                        <th style="width: 100px;">Estado</th>
                                        <th style="width: 120px;">Usuario</th>
                                        <th style="width: 130px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($logsWs) > 0) {
                                        foreach ($logsWs as $l) {
                                            $estado = $l['estado'];
                                            $badgeClass = ($estado == 'EXITO') ? 'label-success' : (($estado == 'PARCIAL') ? 'label-warning' : 'label-danger');
                                            $badgeStyle = ($estado == 'EXITO') ? 'background-color:#28a745;color:#fff;' : (($estado == 'PARCIAL') ? 'background-color:#ffc107;color:#333;' : 'background-color:#dc3545;color:#fff;');
                                            $cantGuardados = intval($l['registros_guardados']);
                                            $cantErrores = intval($l['registros_errores']);
                                            $cantImportados = intval($l['registros_importados']);
                                    ?>
                                    <tr class="fila_ws" id="ws_log_<?=$l['id_log']?>">
                                        <td style="font-weight:bold;"><?=$l['id_log']?></td>
                                        <td><?=date('d/m/Y H:i:s', strtotime($l['fecha_ejecucion']))?></td>
                                        <td><span style="font-weight:600;"><?=$l['fecha_desde']?></span> al <span style="font-weight:600;"><?=$l['fecha_hasta']?></span></td>
                                        <td><span class="badge" style="background:#5bc0de;color:#fff;"><?=$cantImportados?></span></td>
                                        <td><span class="badge" style="background:#5cb85c;color:#fff;"><?=$cantGuardados?></span></td>
                                        <td><span class="badge" style="<?=($cantErrores > 0 ? 'background:#d9534f;color:#fff;' : 'background:#eee;color:#888;')?>"><?=$cantErrores?></span></td>
                                        <td><span class="label <?=$badgeClass?>" style="padding:4px 8px;border-radius:4px;<?=$badgeStyle?>"><?=$estado?></span></td>
                                        <td><?=htmlspecialchars($l['usuario'])?></td>
                                        <td>
                                            <?php if ($cantGuardados > 0) { ?>
                                                <button type="button" class="btn btn-xs btn-info btn-ver-consultas" data-id="<?=$l['id_log']?>" title="Ver <?=$cantGuardados?> consultas">
                                                    <i class="glyphicon glyphicon-list"></i> Ver Consultas
                                                </button>
                                            <?php } else { ?>
                                                <span class="text-muted">-</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php 
                                        } 
                                    } else { ?>
                                    <tr id="fila_sin_logs">
                                        <td colspan="9" style="padding: 20px; color:#888;">
                                            No se han registrado ejecuciones de importación web service todavía. Seleccione un rango de fechas y presione <b>EJECUTAR IMPORTACIÓN</b>.
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- =================================================================== -->
        <!-- PESTAÑA 2: EXPORTACIÓN DE FICHAS SIP (FUNCIONALIDAD ORIGINAL)       -->
        <!-- =================================================================== -->
        <div class="tab-pane" id="tab_exportacion">
            <div class="contenido" id="contenido0">
                <h3 align="center" class="titulo_pagina" style="margin-top:5px;">Exportaci&oacute;n de Fichas SIP</h3>
                <h4 align="center" style="color:#555;">Periodo de proceso:</h4>
                <div align="center">
                    <label style="font-size: 11px;">Desde:</label>
                    <input class="datepicker-export form-control" name="desde" id="desde" value="">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label style="font-size: 11px;">Hasta:</label>
                    <input class="datepicker-export form-control" name="hasta" id="hasta" value="">
                </div>
                <br>
                <div align="center">
                    <label>Efectores:</label>
                    <select name="efector" id="efector" class="form-control" style="width: auto; display: inline-block;">
                        <option val="">- Todos -</option>
                        <?php foreach ($listEfectores as $ef) {
                            echo '<option val="'.$ef['cuie'].'"> '.$ef['cuie'].' - '.$ef['nombre'].' </option> ';
                        } ?>
                    </select>
                </div>
                <div style="width: 100%; margin-bottom: 2%; margin-top: 2%" align="center">
                    <a id="btnProcesar">PROCESAR LOTE</a>
                </div>
                <div id="loading" style="width: 100%; margin-bottom: 2%; margin-top: 2%" align="center"></div>
                <h4 align="center" style="font-weight:bold; margin-top: 25px;">Lotes Procesados</h4>
                <div id="procesados" align="center">
                    <table id="lista_fichas" class="tablagenerica table-bordered table-striped" style="width:75%">
                        <thead>
                            <tr id="encabezados">
                                <th>#</th>
                                <th>Cuie</th>
                                <th>Periodo</th>
                                <th>Nuevas</th>
                                <th>Reprocesadas</th>
                                <th>Total</th>
                                <th>Fecha de Proceso</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (is_array($lotes) && count($lotes) > 0) {
                                $cantLotes = 1;
                                foreach ($lotes as $key => $lote) {
                                    ?>
                            <tr class="fila_con">
                                <td><?=$cantLotes?></td>
                                <td><b><?= $lote->getCuieTxt() ?></b></td>
                                <td><b><?=$lote->getPeriodoDesdeTxt().' &nbsp;-&nbsp; '.$lote->getPeriodoHastaTxt() ?></b></td>
                                <td><?=$lote->getFichasNuevas() ?></td>
                                <td><?=$lote->getFichasReprocesadas() ?></td>
                                <td><b><?=$lote->getFichasTotal() ?></b></td>
                                <td><?=Fecha($lote->getFechaProceso()) ?></td>
                                <td></td>
                            </tr>
                            <?php 
                                $cantLotes++;
                                } 
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- =================================================================== -->
<!-- MODAL: DETALLE DE CONSULTAS DE UN LOG                              -->
<!-- =================================================================== -->
<div class="modal fade" id="modalConsultasWs" tabindex="-1" role="dialog" aria-labelledby="modalConsultasWsLabel" style="z-index: 1050;">
    <div class="modal-dialog" style="width: 90%; max-width: 1200px;" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #2b6cb0; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalConsultasWsLabel">
                    <i class="glyphicon glyphicon-list-alt"></i> Consultas Perinatales Importadas (Log #<span id="modal_id_log"></span>)
                </h4>
            </div>
            <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
                <div id="modal_consultas_loading" style="text-align: center; padding: 25px;">
                    <img src="imagenes/loading.gif" width="30px" height="30px"/>
                    <p style="margin-top:10px;">Cargando registros asociados...</p>
                </div>
                <div id="modal_consultas_content" style="display:none;">
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-3">
                            <div class="ws-metric-box">
                                <div class="ws-metric-val" id="metric_total_consultas">0</div>
                                <div class="ws-metric-lbl">Total Registros</div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div style="background:#eef6fc; padding: 10px; border-radius:6px; border:1px solid #bee3f8; font-size:12px;">
                                <b>Periodo:</b> <span id="metric_periodo"></span> | 
                                <b>Ejecutado:</b> <span id="metric_fecha"></span> | 
                                <b>Usuario:</b> <span id="metric_usuario"></span> |
                                <b>Estado:</b> <span id="metric_estado"></span>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="tabla_modal_consultas" style="font-size:11px;">
                            <thead>
                                <tr style="background:#edf2f7;">
                                    <th>ID Consulta WS</th>
                                    <th>Fecha Atención</th>
                                    <th>Efector</th>
                                    <th>Paciente</th>
                                    <th>Documento</th>
                                    <th>EG (sem)</th>
                                    <th>PA Sist/Diast</th>
                                    <th>Tamizaje / Serología</th>
                                    <th>Profesional</th>
                                    <th>Motivo Consulta</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_modal_consultas"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- =================================================================== -->
<!-- MODAL: VISOR DE JSON COMPLETO                                      -->
<!-- =================================================================== -->
<div class="modal fade" id="modalJsonWs" tabindex="-1" role="dialog" aria-labelledby="modalJsonWsLabel" style="z-index: 1060;">
    <div class="modal-dialog" style="width: 75%; max-width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #1a202c; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalJsonWsLabel">
                    <i class="glyphicon glyphicon-file"></i> Datos Completos JSON (WS ID: <span id="modal_json_ws_id"></span>)
                </h4>
            </div>
            <div class="modal-body">
                <p id="modal_json_paciente_info" style="font-weight:bold; color:#4a5568; margin-bottom:10px;"></p>
                <pre class="json-view" id="modal_json_content">Cargando...</pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- =================================================================== -->
<!-- OVERLAY BLOQUEANTE DE PANTALLA COMPLETA DURANTE IMPORTACIÓN         -->
<!-- =================================================================== -->
<div id="ws_block_overlay">
    <div id="ws_block_box">
        <div class="ws-spinner-pulse"></div>
        <h4 style="font-weight:bold; color:#1a365d; margin-top:0; margin-bottom:8px; font-size:17px;">
            <i class="glyphicon glyphicon-cloud-download text-primary"></i> Importaci&oacute;n de Consultas SIP en Proceso
        </h4>
        <p style="font-size:12px; color:#4a5568; margin-bottom:16px; line-height:1.5;">
            Conectando con el Web Service provincial, descargando registros y persistiendo los datos &iacute;ntegros en la base de datos...
        </p>
        <div style="background:#ebf8ff; border:1px solid #bee3f8; border-radius:20px; padding:6px 20px; display:inline-block; font-size:13px; font-weight:bold; color:#2b6cb0; margin-bottom:18px;">
            <i class="glyphicon glyphicon-time"></i> Tiempo transcurrido: <span id="ws_overlay_timer">00:00</span>
        </div>
        <div class="alert alert-warning" style="margin-bottom:0; font-size:11px; padding:12px 14px; border-left:4px solid #dd6b20; text-align:left; line-height:1.45; background-color:#fffaf0; color:#7b341e;">
            <strong><i class="glyphicon glyphicon-lock"></i> Pantalla Bloqueada:</strong> Se han inhabilitado temporalmente todas las acciones en la p&aacute;gina para resguardar la consistencia de los datos. <b>No cierre ni recargue esta ventana</b> hasta que concluya el proceso.
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    // =========================================================================
    // 1. CONFIGURACIÓN DATEPICKER PARA EXPORTACIÓN ORIGINAL (MENSUAL: MM yy)
    // =========================================================================
    $("#desde, #hasta").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        maxDate: "+0m +0w", 
        onClose: function() {
            var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
        },
        beforeShow: function(input, inst) {
            $('#ui-datepicker-div').addClass('hide-calendar');
            if ((selDate = $(this).val()).length > 0) {
                iYear = selDate.substring(selDate.length - 4, selDate.length);   
                iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5),
                $(this).datepicker('option', 'monthNames'));
                $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
                $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
            }
        }
    });

    $("#desde").datepicker().datepicker("setDate", new Date(new Date().getFullYear(), 0, 1));
    $("#hasta").datepicker().datepicker("setDate", new Date());

    // Proceso de Exportación Original
    $('#btnProcesar').on('click', function() {
        if (confirm('Confirma el proceso del lote indicado?')) {
            var desde = $('#desde').val();
            var hasta = $('#hasta').val();
            var efector = $('#efector option:selected').attr('val');
            if (desde == '' || hasta == '') {
                alert('Debe ingresar el periodo de exportacion');
                return false;
            }
            $('#loading').empty().append('<img src="imagenes/loading.gif" width="25px" height="25px"/> Procesando...');
            var dataString = 'desde=' + desde + '&hasta=' + hasta + '&efector=' + efector;
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "procesar_lote.php",
                data: dataString,
                success: function(data) {
                    $('#loading').empty();
                    var dat = data.toString();
                    var cod = dat.slice(0, 1);
                    var id = dat.slice(1);
                    switch(cod) {
                        case '0':
                            alert('No hay fichas para procesar con el criterio seleccionado.');
                            break;
                        case '1':
                            alert('Error en el Proceso: INSERT INTO nivel_01 HCPerinatal ' + dat);
                            break;
                        case '2':
                            alert('Error en el Proceso: INSERT INTO nivel_05 HCPerinatal ' + dat);
                            break;
                        case '3':
                            alert('Error en el Proceso: INSERT INTO nivel_02 consultasPrenatales ' + dat);
                            break;
                        case '4':
                            alert('Error en el Proceso: INSERT INTO nivel_03 controlesParto ' + dat);
                            break;
                        case '5':
                            alert('Error en el Proceso: INSERT INTO nivel_04 controlesPuerperio ' + dat);
                            break;
                        case '6':
                            alert('Error en el Proceso: INSERT INTO nivel_06 variablesLibres ' + dat);
                            break;
                        default:
                            if (data.url) {
                                window.open(data.url, '_blank');
                            }
                            $("#lista_fichas tbody tr:first td:last").html('');
                            if (data.tr) {
                                $("#lista_fichas").prepend(data.tr);
                            }
                    }        
                },
                error: function(xhr, status, error) {
                    $('#loading').empty();
                    alert('Error en la comunicación con el servidor al procesar lote: ' + error);
                }
            });
        }
    });

    // =========================================================================
    // 2. CONFIGURACIÓN DATEPICKER PARA IMPORTACIÓN WEB SERVICE (DIARIO: dd/mm/yy)
    // =========================================================================
    $("#ws_desde, #ws_hasta").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        maxDate: "+0d",
        beforeShow: function() {
            $('#ui-datepicker-div').removeClass('hide-calendar');
        }
    });

    // Fechas por defecto: día actual
    var hoy = new Date();
    var diaHoy = ("0" + hoy.getDate()).slice(-2) + "/" + ("0" + (hoy.getMonth() + 1)).slice(-2) + "/" + hoy.getFullYear();
    $("#ws_desde").val(diaHoy);
    $("#ws_hasta").val(diaHoy);

    var wsTimerInterval = null;

    $('#btnImportarWs').on('click', function(e) {
        if (e && e.preventDefault) e.preventDefault();
        var desde = $.trim($('#ws_desde').val());
        var hasta = $.trim($('#ws_hasta').val());

        if (desde === '' || hasta === '') {
            alert('Por favor ingrese el rango completo de fechas (Desde y Hasta).');
            return false;
        }

        var confirmMsg = '¿Desea iniciar la importación desde el Web Service para el rango ' + desde + ' al ' + hasta + '?\n\n' +
                         'Nota: La pantalla se bloqueará temporalmente durante la ejecución para resguardar la consistencia de los datos.';
        if (!confirm(confirmMsg)) {
            return false;
        }

        // 1. Iniciar temporizador en vivo
        var segundosTranscurridos = 0;
        $('#ws_overlay_timer').text('00:00');
        if (wsTimerInterval) clearInterval(wsTimerInterval);
        wsTimerInterval = setInterval(function() {
            segundosTranscurridos++;
            var hrs = Math.floor(segundosTranscurridos / 3600);
            var mins = Math.floor((segundosTranscurridos % 3600) / 60);
            var secs = segundosTranscurridos % 60;
            var str = '';
            if (hrs > 0) {
                str += (hrs < 10 ? '0' : '') + hrs + ':';
            }
            str += (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
            $('#ws_overlay_timer').text(str);
        }, 1000);

        // 2. Bloquear totalmente la página para que no se pueda hacer otra acción
        $('#ws_alert_box').hide().empty();
        $('#ws_block_overlay').fadeIn(200);
        $('body').addClass('modal-open').css('overflow', 'hidden');
        $('#sip_tabs li').addClass('disabled');
        $('#sip_tabs a').css('pointer-events', 'none');
        $('button, input, select, textarea').prop('disabled', true);

        // 3. Seguro contra navegación o recarga accidental
        window.onbeforeunload = function(ev) {
            var msg = 'Hay una importación del Web Service SIP-CLAP en curso. Si abandona o recarga la página, la operación podría quedar incompleta.';
            ev = ev || window.event;
            if (ev) ev.returnValue = msg;
            return msg;
        };

        // 4. Ejecutar AJAX con tiempo ilimitado (timeout: 0 para no cortar la ejecución)
        $.ajax({
            type: "POST",
            url: "lotes_exportacion.php",
            data: {
                accion_ws: 'importar',
                desde: desde,
                hasta: hasta
            },
            dataType: "json",
            timeout: 0, // 0 = sin límite de tiempo (espera ilimitada a que termine el servidor)
            success: function(resp) {
                if (resp && resp.success) {
                    var alertClass = (resp.estado === 'EXITO') ? 'alert-success' : 'alert-warning';
                    var htmlAlert = '<div class="alert ' + alertClass + ' alert-dismissible" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>' +
                        '<strong><i class="glyphicon glyphicon-ok-sign"></i> ' + resp.estado + ':</strong> ' + resp.mensaje +
                        ' <span style="font-size:11px; margin-left:10px;">(Duración: ' + resp.duracion + ' seg - Log #' + resp.id_log + ')</span>' +
                        '</div>';
                    $('#ws_alert_box').html(htmlAlert).fadeIn();

                    // Quitar fila vacía si existe
                    $('#fila_sin_logs').remove();

                    // Insertar fila al inicio de la tabla
                    if (resp.tr) {
                        $('#lista_ws_logs tbody').prepend(resp.tr);
                    }
                } else {
                    var errDetalle = (resp && resp.error) ? resp.error : 'Ocurrió un error inesperado al procesar la importación.';
                    var htmlAlertErr = '<div class="alert alert-danger alert-dismissible" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>' +
                        '<strong><i class="glyphicon glyphicon-exclamation-sign"></i> Error:</strong> ' + errDetalle +
                        '</div>';
                    $('#ws_alert_box').html(htmlAlertErr).fadeIn();
                }
            },
            error: function(xhr, status, error) {
                var errMsg = 'Error en la comunicación con el servidor (' + status + '): ' + error;
                $('#ws_alert_box').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button><strong><i class="glyphicon glyphicon-exclamation-sign"></i> Error:</strong> ' + errMsg + '</div>').fadeIn();
            },
            complete: function() {
                // 5. Desbloquear pantalla y restaurar estado
                if (wsTimerInterval) {
                    clearInterval(wsTimerInterval);
                    wsTimerInterval = null;
                }
                $('#ws_block_overlay').fadeOut(250);
                $('body').removeClass('modal-open').css('overflow', '');
                $('#sip_tabs li').removeClass('disabled');
                $('#sip_tabs a').css('pointer-events', '');
                $('button, input, select, textarea').prop('disabled', false);
                window.onbeforeunload = null;
            }
        });
    });

    // =========================================================================
    // 4. VER CONSULTAS ASOCIADAS A UN LOG EN EL MODAL
    // =========================================================================
    $(document).on('click', '.btn-ver-consultas', function() {
        var idLog = $(this).data('id');
        $('#modal_id_log').text(idLog);
        $('#modal_consultas_loading').show();
        $('#modal_consultas_content').hide();
        $('#tbody_modal_consultas').empty();
        $('#modalConsultasWs').modal('show');

        $.ajax({
            type: "POST",
            url: "lotes_exportacion.php",
            data: {
                accion_ws: 'ver_consultas',
                id_log: idLog
            },
            dataType: "json",
            success: function(resp) {
                $('#modal_consultas_loading').hide();
                if (resp.success && resp.consultas) {
                    $('#metric_total_consultas').text(resp.total);
                    if (resp.log) {
                        $('#metric_periodo').text(resp.log.fecha_desde + ' al ' + resp.log.fecha_hasta);
                        $('#metric_fecha').text(resp.log.fecha_ejecucion);
                        $('#metric_usuario').text(resp.log.usuario);
                        $('#metric_estado').text(resp.log.estado);
                    }

                    var rowsHtml = '';
                    $.each(resp.consultas, function(i, c) {
                        var egVal = c.var_0119 || c.edad_gestacional;
                        var egHtml = egVal 
                            ? '<span class="label label-info" style="font-size:11px;">' + egVal + ' sem</span>' 
                            : '<span class="text-muted">-</span>';
                        
                        var sis = c.var_0121 || c.pa_sistolica || '';
                        var dia = c.var_0394 || c.pa_diastolica || '';
                        var paHtml = (sis || dia) 
                            ? '<b>' + (sis || '-') + '/' + (dia || '-') + '</b>' 
                            : '<span class="text-muted">-</span>';

                        var tags = [];
                        if (c.var_0101 !== null && c.var_0101 !== '') tags.push('<span class="label" style="background:#4a5568;color:#fff;" title="Chagas (VAR_0101)">Chag:' + c.var_0101 + '</span>');
                        if (c.var_0113 !== null && c.var_0113 !== '') tags.push('<span class="label" style="background:#2b6cb0;color:#fff;" title="Sífilis FTA (VAR_0113)">FTA:' + c.var_0113 + '</span>');
                        if (c.var_0115 !== null && c.var_0115 !== '') tags.push('<span class="label" style="background:#d69e2e;color:#fff;" title="Tratamiento Sífilis (VAR_0115)">TTO:' + c.var_0115 + '</span>');
                        if (c.var_0091 !== null && c.var_0091 !== '') tags.push('<span class="label" style="background:#805ad5;color:#fff;" title="VIH <20s Solicitado (VAR_0091)">VIH<20:' + c.var_0091 + '</span>');
                        if (c.var_0182 !== null && c.var_0182 !== '') tags.push('<span class="label" style="background:#38a169;color:#fff;" title="Parto/Aborto (VAR_0182)">P/A:' + c.var_0182 + '</span>');
                        var seroHtml = tags.length > 0 ? tags.join(' ') : '<span class="text-muted">-</span>';

                        rowsHtml += '<tr>' +
                            '<td><b>' + (c.id_consulta_ws || '-') + '</b></td>' +
                            '<td>' + (c.fecha_consulta_raw || '-') + '</td>' +
                            '<td><span title="' + (c.efector_nombre || '') + '">' + (c.efector_nombre || c.efector_codigo || '-') + '</span></td>' +
                            '<td><b>' + (c.paciente_nombre || '-') + '</b></td>' +
                            '<td>' + (c.paciente_tipo_doc ? c.paciente_tipo_doc + ' ' : '') + (c.paciente_documento || '-') + '</td>' +
                            '<td class="text-center">' + egHtml + '</td>' +
                            '<td class="text-center">' + paHtml + '</td>' +
                            '<td class="text-center">' + seroHtml + '</td>' +
                            '<td>' + (c.profesional_nombre ? c.profesional_nombre + ' (' + (c.profesional_matricula || '') + ')' : '-') + '</td>' +
                            '<td style="text-align:left !important; max-width:180px;" title="' + (c.motivo_consulta || '') + '">' + 
                                ((c.motivo_consulta && c.motivo_consulta.length > 60) ? c.motivo_consulta.substring(0, 60) + '...' : (c.motivo_consulta || '-')) + 
                            '</td>' +
                            '<td>' +
                                '<button type="button" class="btn btn-xs btn-default btn-ver-json" data-id="' + c.id_ws_consulta + '" title="Ver JSON completo">' +
                                    '<i class="glyphicon glyphicon-eye-open"></i> JSON' +
                                '</button>' +
                            '</td>' +
                        '</tr>';
                    });

                    $('#tbody_modal_consultas').html(rowsHtml);
                    $('#modal_consultas_content').show();
                } else {
                    $('#tbody_modal_consultas').html('<tr><td colspan="11" class="text-danger text-center">No se encontraron registros para este lote.</td></tr>');
                    $('#modal_consultas_content').show();
                }
            },
            error: function(xhr, status, error) {
                $('#modal_consultas_loading').hide();
                alert('Error al recuperar las consultas: ' + error);
            }
        });
    });

    // =========================================================================
    // 5. VER JSON COMPLETO DE UNA CONSULTA
    // =========================================================================
    $(document).on('click', '.btn-ver-json', function() {
        var idWsConsulta = $(this).data('id');
        $('#modal_json_ws_id').text(idWsConsulta);
        $('#modal_json_content').text('Cargando JSON...');
        $('#modal_json_paciente_info').text('');
        $('#modalJsonWs').modal('show');

        $.ajax({
            type: "POST",
            url: "lotes_exportacion.php",
            data: {
                accion_ws: 'ver_json',
                id_ws_consulta: idWsConsulta
            },
            dataType: "json",
            success: function(resp) {
                if (resp.success) {
                    $('#modal_json_ws_id').text(resp.id_consulta_ws);
                    $('#modal_json_paciente_info').text('Paciente: ' + resp.paciente_nombre + ' (DNI: ' + resp.paciente_documento + ')');
                    var formattedJson = JSON.stringify(resp.json_data, null, 2);
                    $('#modal_json_content').text(formattedJson);
                } else {
                    $('#modal_json_content').text('Error: ' + (resp.error || 'No se pudo cargar el JSON'));
                }
            },
            error: function(xhr, status, error) {
                $('#modal_json_content').text('Error de red: ' + error);
            }
        });
    });

});
</script>