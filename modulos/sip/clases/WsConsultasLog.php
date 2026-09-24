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
     * Normaliza una fecha en formato string a YYYY-MM-DD
     * @param string $f
     * @return string|null
     */
    public static function normalizarFecha($f) {
        $f = trim($f);
        if (empty($f)) return null;
        if (preg_match('/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{2,4})/', $f, $m)) {
            $d = intval($m[1]);
            $mo = intval($m[2]);
            $y = intval($m[3]);
            if ($y < 100) $y += 2000;
            return sprintf('%04d-%02d-%02d', $y, $mo, $d);
        }
        return null;
    }

    /**
     * Extrae los atributos clínicos y perinatales del JSON SIP Plus
     * buscando coincidencias con la fecha de la consulta o el último control prenatal.
     * 
     * @param array $record
     * @return array
     */
    public static function extraerAtributosSip($record) {
        $attrs = array(
            'var_0119' => null, // edad gestacional
            'var_0121' => null, // PA sistolica
            'var_0394' => null, // PA diastolica
            'var_0182' => null, // parto / aborto
            'var_0113' => null, // sifilis FTA
            'var_0115' => null, // tto sifilis
            'var_0101' => null, // chagas
            'var_0092' => null, // vih <20 real
            'var_0094' => null, // vih >=20 real
            'var_0091' => null, // vih <20 sol
            'var_0093' => null, // vih >=20 sol
            'var_0116' => null, // fecha control prenatal
            'var_0126' => null, // observaciones control
            'var_0200' => null, // FUM
            'var_0201' => null, // FPP
        );

        $sip = isset($record['sipPlusValues']) && is_array($record['sipPlusValues']) ? $record['sipPlusValues'] : array();

        // 1. FUM y FPP del nivel raíz de sipPlusValues
        if (isset($sip['F200']) && $sip['F200'] !== '') $attrs['var_0200'] = strval($sip['F200']);
        if (isset($sip['F201']) && $sip['F201'] !== '') $attrs['var_0201'] = strval($sip['F201']);

        // 2. Extraer de pregnancies
        $pregnancies = isset($sip['pregnancies']) && is_array($sip['pregnancies']) ? $sip['pregnancies'] : array();
        
        $pregVars = array(
            '0101' => 'var_0101',
            '0091' => 'var_0091',
            '0092' => 'var_0092',
            '0093' => 'var_0093',
            '0094' => 'var_0094',
            '0113' => 'var_0113',
            '0115' => 'var_0115',
            '0182' => 'var_0182',
        );

        $fechaConsNorm = isset($record['consultationDate']) ? self::normalizarFecha($record['consultationDate']) : null;

        $bestPrenatal = null;
        $lastPrenatal = null;

        foreach ($pregnancies as $pregIdx => $preg) {
            if (!is_array($preg)) continue;

            foreach ($pregVars as $code => $col) {
                if ($attrs[$col] === null && isset($preg[$code]) && $preg[$code] !== '') {
                    $attrs[$col] = strval($preg[$code]);
                }
            }

            // Revisar controles prenatales para asociar a esta consulta
            if (isset($preg['prenatal']) && is_array($preg['prenatal'])) {
                foreach ($preg['prenatal'] as $pIdx => $ctrl) {
                    if (!is_array($ctrl)) continue;
                    $lastPrenatal = $ctrl;

                    if ($fechaConsNorm && isset($ctrl['0116'])) {
                        $ctrlFecNorm = self::normalizarFecha($ctrl['0116']);
                        if ($ctrlFecNorm === $fechaConsNorm) {
                            $bestPrenatal = $ctrl;
                        }
                    }
                }
            }
        }

        // Revisar también nivel raíz de sipPlusValues por si vinieron allí
        foreach ($pregVars as $code => $col) {
            if ($attrs[$col] === null && isset($sip[$code]) && $sip[$code] !== '') {
                $attrs[$col] = strval($sip[$code]);
            }
        }

        // Asignar prenatal seleccionado (el que coincide por fecha o el último registrado)
        $ctrlElegido = $bestPrenatal ? $bestPrenatal : $lastPrenatal;
        if ($ctrlElegido) {
            if (isset($ctrlElegido['0116']) && $ctrlElegido['0116'] !== '') $attrs['var_0116'] = strval($ctrlElegido['0116']);
            if (isset($ctrlElegido['0119']) && $ctrlElegido['0119'] !== '') $attrs['var_0119'] = strval($ctrlElegido['0119']);
            if (isset($ctrlElegido['0121']) && $ctrlElegido['0121'] !== '') $attrs['var_0121'] = strval($ctrlElegido['0121']);
            if (isset($ctrlElegido['0394']) && $ctrlElegido['0394'] !== '') $attrs['var_0394'] = strval($ctrlElegido['0394']);
            if (isset($ctrlElegido['0126']) && $ctrlElegido['0126'] !== '') $attrs['var_0126'] = strval($ctrlElegido['0126']);
        }

        // Fallback para fecha de control
        if ($attrs['var_0116'] === null && isset($record['consultationDate'])) {
            $attrs['var_0116'] = substr(trim($record['consultationDate']), 0, 10);
        }

        // Fallback de EG en texto de motivo de consulta
        if ($attrs['var_0119'] === null && isset($record['reason'])) {
            if (preg_match('/EG:\s*(\d+)/i', $record['reason'], $m)) {
                $attrs['var_0119'] = strval($m[1]);
            }
        }

        return $attrs;
    }

    /**
     * Guarda / actualiza una consulta devuelta por el Web Service
     * persistiendo tanto el JSON completo como las columnas de variables extraídas.
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

        // Extraer atributos específicos de SIP Plus
        $sipAttrs = self::extraerAtributosSip($record);

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
            var_0119, var_0121, var_0394, var_0182, var_0113, var_0115, var_0101,
            var_0092, var_0094, var_0091, var_0093, var_0116, var_0126, var_0200, var_0201,
            edad_gestacional, pa_sistolica, pa_diastolica, parto, sifilis_fta, tto_sifilis, chagas,
            vih_menor20_realizado, vih_mayor20_realizado, vih_menor20_solicitado, vih_mayor20_solicitado,
            fecha_control_prenatal, fum, fpp,
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
            $19, $20, $21, $22, $23, $24, $25,
            $26, $27, $28, $29, $30, $31, $32, $33,
            $19, $20, $21, $22, $23, $24, $25,
            $26, $27, $28, $29, $30, $32, $33,
            $34, $35, $36,
            $37, CURRENT_TIMESTAMP
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
            var_0119 = EXCLUDED.var_0119,
            var_0121 = EXCLUDED.var_0121,
            var_0394 = EXCLUDED.var_0394,
            var_0182 = EXCLUDED.var_0182,
            var_0113 = EXCLUDED.var_0113,
            var_0115 = EXCLUDED.var_0115,
            var_0101 = EXCLUDED.var_0101,
            var_0092 = EXCLUDED.var_0092,
            var_0094 = EXCLUDED.var_0094,
            var_0091 = EXCLUDED.var_0091,
            var_0093 = EXCLUDED.var_0093,
            var_0116 = EXCLUDED.var_0116,
            var_0126 = EXCLUDED.var_0126,
            var_0200 = EXCLUDED.var_0200,
            var_0201 = EXCLUDED.var_0201,
            edad_gestacional = EXCLUDED.edad_gestacional,
            pa_sistolica = EXCLUDED.pa_sistolica,
            pa_diastolica = EXCLUDED.pa_diastolica,
            parto = EXCLUDED.parto,
            sifilis_fta = EXCLUDED.sifilis_fta,
            tto_sifilis = EXCLUDED.tto_sifilis,
            chagas = EXCLUDED.chagas,
            vih_menor20_realizado = EXCLUDED.vih_menor20_realizado,
            vih_mayor20_realizado = EXCLUDED.vih_mayor20_realizado,
            vih_menor20_solicitado = EXCLUDED.vih_menor20_solicitado,
            vih_mayor20_solicitado = EXCLUDED.vih_mayor20_solicitado,
            fecha_control_prenatal = EXCLUDED.fecha_control_prenatal,
            fum = EXCLUDED.fum,
            fpp = EXCLUDED.fpp,
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
            $sipAttrs['var_0119'],
            $sipAttrs['var_0121'],
            $sipAttrs['var_0394'],
            $sipAttrs['var_0182'],
            $sipAttrs['var_0113'],
            $sipAttrs['var_0115'],
            $sipAttrs['var_0101'],
            $sipAttrs['var_0092'],
            $sipAttrs['var_0094'],
            $sipAttrs['var_0091'],
            $sipAttrs['var_0093'],
            $sipAttrs['var_0116'],
            $sipAttrs['var_0126'],
            $sipAttrs['var_0200'],
            $sipAttrs['var_0201'],
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
     * incluyendo los atributos para indicadores y facturación
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
                       var_0119, var_0121, var_0394, var_0182, var_0113, var_0115, var_0101,
                       var_0092, var_0094, var_0091, var_0093, var_0116, var_0126, var_0200, var_0201,
                       edad_gestacional, pa_sistolica, pa_diastolica,
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
