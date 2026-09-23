<?php
/**
 * Modelo para la persistencia y consulta del Log de Importaciones Web Service SIP-CLAP
 * y datos de consultas importadas.
 * Compatible con PHP 5.3+
 */
class WsConsultasLog {

    private static $dbConn = null;

    /**
     * Obtiene una conexión nativa pg_connect para consultas de alto rendimiento y JSONB
     * @return resource
     */
    public static function getConnection() {
        if (self::$dbConn === null) {
            global $db_host, $db_name, $db_user, $db_password;
            if (empty($db_host)) {
                // Si no están en el scope global, incluir db.php
                $dbConfigFile = dirname(__FILE__) . '/../../../db.php';
                if (file_exists($dbConfigFile)) {
                    require_once($dbConfigFile);
                }
            }
            self::$dbConn = pg_connect("host=$db_host dbname=$db_name user=$db_user password=$db_password");
        }
        return self::$dbConn;
    }

    /**
     * Registra el inicio de una transacción de importación
     * 
     * @param string $desde
     * @param string $hasta
     * @param string $usuario
     * @param string $parametrosJson
     * @return int|false ID del log insertado o false
     */
    public static function iniciarLog($desde, $hasta, $usuario, $parametrosJson = '') {
        $conn = self::getConnection();
        if (!$conn) {
            return false;
        }

        $sql = "INSERT INTO sip_clap.ws_consultas_log 
                (fecha_ejecucion, fecha_desde, fecha_hasta, registros_importados, registros_guardados, registros_errores, estado, usuario, parametros_json)
                VALUES (CURRENT_TIMESTAMP, $1, $2, 0, 0, 0, 'EN_PROCESO', $3, $4)
                RETURNING id_log";

        $res = pg_query_params($conn, $sql, array($desde, $hasta, $usuario, $parametrosJson));
        if ($res && $row = pg_fetch_assoc($res)) {
            return intval($row['id_log']);
        }
        return false;
    }

    /**
     * Actualiza el log al finalizar la importación
     * 
     * @param int $idLog
     * @param int $importados
     * @param int $guardados
     * @param int $errores
     * @param string $estado 'EXITO', 'PARCIAL', 'ERROR'
     * @param string $mensaje
     * @param float $duracionSeg
     * @return bool
     */
    public static function finalizarLog($idLog, $importados, $guardados, $errores, $estado, $mensaje, $duracionSeg = 0) {
        $conn = self::getConnection();
        if (!$conn) {
            return false;
        }

        $sql = "UPDATE sip_clap.ws_consultas_log SET
                registros_importados = $1,
                registros_guardados = $2,
                registros_errores = $3,
                estado = $4,
                mensaje = $5,
                duracion_seg = $6
                WHERE id_log = $7";

        $res = pg_query_params($conn, $sql, array(
            intval($importados),
            intval($guardados),
            intval($errores),
            $estado,
            $mensaje,
            round($duracionSeg, 2),
            intval($idLog)
        ));

        return (bool)$res;
    }

    /**
     * Guarda / actualiza una consulta devuelta por el Web Service
     * 
     * @param int $idLog
     * @param array $record Objeto de consulta decodificado de JSON
     * @return bool
     */
    public static function guardarConsulta($idLog, $record) {
        $conn = self::getConnection();
        if (!$conn || !is_array($record) || empty($record['id'])) {
            return false;
        }

        $idConsultaWs = trim($record['id']);
        $fechaConsultaRaw = isset($record['consultationDate']) ? trim($record['consultationDate']) : null;
        
        $efectorCodigo = isset($record['healthCenter']['code']) ? trim($record['healthCenter']['code']) : null;
        $efectorNombre = isset($record['healthCenter']['name']) ? trim($record['healthCenter']['name']) : null;
        $especialidad = isset($record['specialty']) ? trim($record['specialty']) : null;

        $pacDoc = isset($record['patient']['document']) ? trim($record['patient']['document']) : null;
        $pacTipoDoc = isset($record['patient']['documentType']) ? trim($record['patient']['documentType']) : null;
        $pacNombre = isset($record['patient']['fullName']) ? trim($record['patient']['fullName']) : (
            (isset($record['patient']['lastName']) ? $record['patient']['lastName'] . ' ' : '') . 
            (isset($record['patient']['firstName']) ? $record['patient']['firstName'] : '')
        );
        $pacSexo = isset($record['patient']['sex']) ? trim($record['patient']['sex']) : null;
        $pacFecNac = isset($record['patient']['birthDate']) ? trim($record['patient']['birthDate']) : null;
        $pacDom = isset($record['patient']['address']) ? trim($record['patient']['address']) : null;
        $pacLoc = isset($record['patient']['city']) ? trim($record['patient']['city']) : null;
        $pacProv = isset($record['patient']['state']) ? trim($record['patient']['state']) : null;

        $profMat = isset($record['professional']['matricula']) ? trim($record['professional']['matricula']) : null;
        $profNom = isset($record['professional']['nombre']) ? trim($record['professional']['nombre']) : null;

        $motivo = isset($record['reason']) ? trim($record['reason']) : null;

        $diagJson = isset($record['diagnoses']) ? json_encode($record['diagnoses']) : json_encode(array());
        $dataJson = isset($record['data']) ? json_encode($record['data']) : json_encode(array());
        $sipPlusJson = isset($record['sipPlusValues']) ? json_encode($record['sipPlusValues']) : json_encode(array());
        $fullJson = json_encode($record);

        $sql = "
        INSERT INTO sip_clap.ws_consultas_datos (
            id_log, id_consulta_ws, fecha_consulta, fecha_consulta_raw,
            efector_codigo, efector_nombre, especialidad,
            paciente_documento, paciente_tipo_doc, paciente_nombre, paciente_sexo,
            paciente_fecha_nac, paciente_domicilio, paciente_localidad, paciente_provincia,
            profesional_matricula, profesional_nombre, motivo_consulta,
            diagnosticos_json, datos_adicionales_json, sip_plus_values_json,
            datos_completos_json, fecha_actualizacion
        ) VALUES (
            $1, $2, 
            CASE 
                WHEN $3::text IS NOT NULL AND $3::text <> '' THEN TO_TIMESTAMP($3, 'DD-MM-YYYY HH24:MI:SS') 
                ELSE NULL 
            END, 
            $4,
            $5, $6, $7,
            $8, $9, $10, $11,
            $12, $13, $14, $15,
            $16, $17, $18,
            $19, $20, $21,
            $22, CURRENT_TIMESTAMP
        )
        ON CONFLICT (id_consulta_ws) DO UPDATE SET
            id_log = EXCLUDED.id_log,
            fecha_consulta = EXCLUDED.fecha_consulta,
            fecha_consulta_raw = EXCLUDED.fecha_consulta_raw,
            efector_codigo = EXCLUDED.efector_codigo,
            efector_nombre = EXCLUDED.efector_nombre,
            especialidad = EXCLUDED.especialidad,
            paciente_documento = EXCLUDED.paciente_documento,
            paciente_tipo_doc = EXCLUDED.paciente_tipo_doc,
            paciente_nombre = EXCLUDED.paciente_nombre,
            paciente_sexo = EXCLUDED.paciente_sexo,
            paciente_fecha_nac = EXCLUDED.paciente_fecha_nac,
            paciente_domicilio = EXCLUDED.paciente_domicilio,
            paciente_localidad = EXCLUDED.paciente_localidad,
            paciente_provincia = EXCLUDED.paciente_provincia,
            profesional_matricula = EXCLUDED.profesional_matricula,
            profesional_nombre = EXCLUDED.profesional_nombre,
            motivo_consulta = EXCLUDED.motivo_consulta,
            diagnosticos_json = EXCLUDED.diagnosticos_json,
            datos_adicionales_json = EXCLUDED.datos_adicionales_json,
            sip_plus_values_json = EXCLUDED.sip_plus_values_json,
            datos_completos_json = EXCLUDED.datos_completos_json,
            fecha_actualizacion = CURRENT_TIMESTAMP
        ";

        $params = array(
            $idLog,
            $idConsultaWs,
            $fechaConsultaRaw,
            $fechaConsultaRaw,
            $efectorCodigo,
            $efectorNombre,
            $especialidad,
            $pacDoc,
            $pacTipoDoc,
            $pacNombre,
            $pacSexo,
            $pacFecNac,
            $pacDom,
            $pacLoc,
            $pacProv,
            $profMat,
            $profNom,
            $motivo,
            $diagJson,
            $dataJson,
            $sipPlusJson,
            $fullJson
        );

        $res = pg_query_params($conn, $sql, $params);
        return (bool)$res;
    }

    /**
     * Obtiene el listado de logs de importaciones ordenados descendente
     * @param int $limit
     * @return array
     */
    public static function getLogs($limit = 50) {
        $conn = self::getConnection();
        $logs = array();
        if (!$conn) {
            return $logs;
        }

        $sql = "SELECT id_log, fecha_ejecucion, fecha_desde, fecha_hasta,
                       registros_importados, registros_guardados, registros_errores,
                       estado, usuario, mensaje, duracion_seg
                FROM sip_clap.ws_consultas_log
                ORDER BY id_log DESC
                LIMIT " . intval($limit);

        $res = pg_query($conn, $sql);
        if ($res) {
            while ($row = pg_fetch_assoc($res)) {
                $logs[] = $row;
            }
        }
        return $logs;
    }

    /**
     * Obtiene un log específico por su ID
     * @param int $idLog
     * @return array|null
     */
    public static function getLogById($idLog) {
        $conn = self::getConnection();
        if (!$conn) return null;

        $res = pg_query_params($conn, "SELECT * FROM sip_clap.ws_consultas_log WHERE id_log = $1", array(intval($idLog)));
        if ($res && $row = pg_fetch_assoc($res)) {
            return $row;
        }
        return null;
    }

    /**
     * Obtiene las consultas importadas correspondientes a una ejecución
     * @param int $idLog
     * @param int $limit
     * @return array
     */
    public static function getConsultasByLog($idLog, $limit = 200) {
        $conn = self::getConnection();
        $consultas = array();
        if (!$conn) return $consultas;

        $sql = "SELECT id_ws_consulta, id_log, id_consulta_ws, fecha_consulta, fecha_consulta_raw,
                       efector_codigo, efector_nombre, especialidad,
                       paciente_documento, paciente_tipo_doc, paciente_nombre, paciente_sexo,
                       profesional_matricula, profesional_nombre, motivo_consulta,
                       fecha_insercion, fecha_actualizacion
                FROM sip_clap.ws_consultas_datos
                WHERE id_log = $1
                ORDER BY fecha_consulta DESC NULLS LAST, id_ws_consulta DESC
                LIMIT " . intval($limit);

        $res = pg_query_params($conn, $sql, array(intval($idLog)));
        if ($res) {
            while ($row = pg_fetch_assoc($res)) {
                $consultas[] = $row;
            }
        }
        return $consultas;
    }

    /**
     * Obtiene el JSON completo de una consulta guardada
     * @param string $idWsConsulta (id de la tabla o id_consulta_ws)
     * @return array|null
     */
    public static function getConsultaJson($idWsConsulta) {
        $conn = self::getConnection();
        if (!$conn) return null;

        $sql = "SELECT id_ws_consulta, id_consulta_ws, paciente_documento, paciente_nombre, 
                       datos_completos_json
                FROM sip_clap.ws_consultas_datos
                WHERE id_ws_consulta = $1 OR id_consulta_ws = $2";

        $idInt = is_numeric($idWsConsulta) ? intval($idWsConsulta) : 0;
        $res = pg_query_params($conn, $sql, array($idInt, strval($idWsConsulta)));
        if ($res && $row = pg_fetch_assoc($res)) {
            return $row;
        }
        return null;
    }
}
