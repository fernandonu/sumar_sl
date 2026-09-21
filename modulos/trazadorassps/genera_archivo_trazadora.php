<?php
require_once("../../config.php");
extract($_POST, EXTR_SKIP);
if ($parametros) extract($parametros, EXTR_OVERWRITE);
cargar_calendario();

// Manejador para descargar archivo generado directamente
if (isset($_GET['descargar_archivo'])) {
    $archivo_solicitado = basename($_GET['descargar_archivo']);
    $ruta_archivo = dirname(__FILE__) . "/" . $archivo_solicitado;
    $extension = strtolower(pathinfo($archivo_solicitado, PATHINFO_EXTENSION));

    if (in_array($extension, array('txt', 'csv', 'xls', 'xlsx')) && file_exists($ruta_archivo)) {
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $archivo_solicitado . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($ruta_archivo));
        readfile($ruta_archivo);
        exit;
    } else {
        $mensaje_alerta = "El archivo solicitado no existe o no tiene una extensión permitida.";
    }
}

function bisiesto_local($anio_actual){ 
    $bisiesto = false; 
    if (checkdate(2, 29, $anio_actual)) { 
        $bisiesto = true; 
    } 
    return $bisiesto; 
}

function edad_con_meses($fecha_de_nacimiento, $fecha_control){ 
    $array_nacimiento = explode("-", $fecha_de_nacimiento); 
    $array_actual = explode("-", $fecha_control); 

    $anos = $array_actual[0] - $array_nacimiento[0];
    $meses = $array_actual[1] - $array_nacimiento[1];
    $dias = $array_actual[2] - $array_nacimiento[2];

    if ($dias < 0) { 
        --$meses; 
        switch ($array_actual[1]) { 
            case 1:  $dias_mes_anterior = 31; break; 
            case 2:  $dias_mes_anterior = 31; break; 
            case 3:  
                if (bisiesto_local($array_actual[0])) { 
                    $dias_mes_anterior = 29; break; 
                } else { 
                    $dias_mes_anterior = 28; break; 
                } 
            case 4:  $dias_mes_anterior = 31; break; 
            case 5:  $dias_mes_anterior = 30; break; 
            case 6:  $dias_mes_anterior = 31; break; 
            case 7:  $dias_mes_anterior = 30; break; 
            case 8:  $dias_mes_anterior = 31; break; 
            case 9:  $dias_mes_anterior = 31; break; 
            case 10: $dias_mes_anterior = 30; break; 
            case 11: $dias_mes_anterior = 31; break; 
            case 12: $dias_mes_anterior = 30; break; 
        } 
        $dias = $dias + $dias_mes_anterior; 
    } 

    if ($meses < 0) { 
        --$anos; 
        $meses = $meses + 12; 
    } 
    $edad_con_meses_result = array("anos" => $anos, "meses" => $meses, "dias" => $dias);
    return $edad_con_meses_result;
}

function calculo_percentilo_imc($meses, $sexo, $imc){
    $sql = "SELECT * from nacer.percentilos_imc where meses=$meses and sexo='$sexo'";
    $res_sql = sql($sql) or fin_pagina();

    if ($res_sql->RecordCount() != 0) {
        switch ($imc){
            case $imc <= $res_sql->fields['p3'] : return '1'; break;
            case $imc > $res_sql->fields['p3'] and $imc <= $res_sql->fields['p10'] : return '2'; break;
            case $imc > $res_sql->fields['p10'] and $imc <= $res_sql->fields['p85'] : return '3'; break;
            case $imc > $res_sql->fields['p85'] and $imc <= $res_sql->fields['p97'] : return '4'; break;
            case $imc > $res_sql->fields['p97'] : return '5'; break;
        }
    }
}

function calculo_percentilo_peso($dias, $sexo, $peso){
    $sql = "SELECT * from nacer.percentilos_peso where dias=$dias and sexo='$sexo'";
    $res_sql = sql($sql) or fin_pagina();

    if ($res_sql->RecordCount() != 0) {
        switch ($peso){
            case $peso <= $res_sql->fields['p3'] : return '1'; break;
            case ($peso > $res_sql->fields['p3'] and $peso <= $res_sql->fields['p10']) : return '2'; break;
            case ($peso > $res_sql->fields['p10'] and $peso <= $res_sql->fields['p85']) : return '3'; break;
            case ($peso > $res_sql->fields['p85'] and $peso <= $res_sql->fields['p97']) : return '4'; break;
            case $peso > $res_sql->fields['p97'] : return '5'; break;
        }
    }
}

function calculo_percentilo_talla($dias, $sexo, $talla){
    $sql = "SELECT * from nacer.percentilos_talla where dias=$dias and sexo='$sexo'";
    $res_sql = sql($sql) or fin_pagina();

    if ($res_sql->RecordCount() != 0) {
        switch ($talla){
            case $talla <= $res_sql->fields['p3'] : return '1'; break;
            case $talla > $res_sql->fields['p3'] and $talla <= $res_sql->fields['p10'] : return '2'; break;
            case $talla > $res_sql->fields['p10'] and $talla <= $res_sql->fields['p85'] : return '3'; break;
            case $talla > $res_sql->fields['p85'] and $talla <= $res_sql->fields['p97'] : return '4'; break;
            case $talla > $res_sql->fields['p97'] : return '5'; break;
        }
    }
}

function genera_trazadora_1($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente) {
    // Trazadora 1 tradicional
}

function genera_trazadora_2($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente) {
    // Trazadora 2 tradicional
}

function genera_trazadora_3($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente) {
    // Trazadora 3 tradicional
}

function genera_trazadora_4($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente) {
    // Trazadora 4 tradicional
}

function genera_trazadora_5($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente) {
    global $mensaje_resultado_tradicional;
    $sql = "SELECT comprobante.cuie,
            smiafiliados.clavebeneficiario,
            trim(smiafiliados.aficlasedoc) as aficlasedoc,
            smiafiliados.afitipodoc,
            smiafiliados.afidni,
            smiafiliados.afiapellido,
            smiafiliados.afinombre,
            trim(smiafiliados.afisexo) as afisexo,
            smiafiliados.afifechanac,
            comprobante.fecha_comprobante,
            prestacion.tisomf
            from facturacion.prestacion 
            inner join facturacion.comprobante using (id_comprobante)
            inner join nacer.smiafiliados using (id_smiafiliados)
            inner join facturacion.nomenclador using (id_nomenclador)
            where nomenclador.codigo = 'L098'
            order by fecha_prestacion";

    $res_sql_1 = sql($sql, "error al traer los registro de la trazadora 5") or fin_pagina();
    $filename = "$trz" . "12" . "$anio" . "$cuatrim" . "00001.txt";
    if (!$handle = fopen($filename, 'w')) {
        $mensaje_resultado_tradicional = "No se puede abrir el archivo ($filename)";
        return;
    }

    $res_sql_1->movefirst();
    while (!$res_sql_1->EOF) {
        $contenido = $res_sql_1->fields['cuie'] . "\t";
        $contenido .= $res_sql_1->fields['clavebeneficiario'] . "\t";
        $contenido .= $res_sql_1->fields['afitipodoc'] . "\t";
        $contenido .= $res_sql_1->fields['afidni'] . "\t";
        $contenido .= $res_sql_1->fields['afiapellido'] . "\t";
        $contenido .= $res_sql_1->fields['afinombre'] . "\t";
        $contenido .= $res_sql_1->fields['afifechanac'] . "\t";
        $contenido .= trim($res_sql_1->fields['afisexo']) . "\t";
        $contenido .= $res_sql_1->fields['fecha_comprobante'] . "\t";
        if ($res_sql_1->fields['tisomf'] && $res_sql_1->fields['tisomf'] != '') {
            $contenido .= $res_sql_1->fields['tisomf'] . "\t";
        } else {
            $contenido .= 'negativo' . "\t";
        }
        $contenido .= "1\t\r\n";
        fwrite($handle, $contenido);
        $res_sql_1->MoveNext();
    }
    fclose($handle);
    $mensaje_resultado_tradicional = "El archivo ($filename) se generó con éxito.";
}

function genera_trazadora_6($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente) {
    global $mensaje_resultado_tradicional;
    $date = $anio . '-01-01';
    $sql_1 = "SELECT distinct on (afidni,fecha_diagnostico)
        nacer.smiafiliados.clavebeneficiario, 
        nacer.smiafiliados.afiapellido, 
        nacer.smiafiliados.afinombre, 
        nacer.smiafiliados.afitipodoc, 
        nacer.smiafiliados.afidni, 
        nacer.smiafiliados.afisexo::character(1),
        nacer.smiafiliados.afifechanac,
        trazadorassps.trazadora_12.fecha_diagnostico,
        trazadorassps.trazadora_12.diagnostico,
        case when (trazadorassps.trazadora_12.fecha_inic_tratamiento='1900-01-01')
            then trazadorassps.trazadora_12.fecha_diagnostico
            else trazadorassps.trazadora_12.fecha_inic_tratamiento end ::date as fecha_inic_tratamiento,
        trazadorassps.trazadora_12.cuie
        from nacer.smiafiliados
        inner join trazadorassps.trazadora_12 on nacer.smiafiliados.id_smiafiliados=trazadorassps.trazadora_12.id_smiafiliados
        where trazadorassps.trazadora_12.fecha_diagnostico >='$date' and trazadorassps.trazadora_12.diagnostico::integer>=4";
            
    $res_sql_1 = sql($sql_1, "error al traer los registro de la trazadora VI") or fin_pagina();
    $filename = "$trz" . "12" . "$anio" . "$cuatrim" . "00001-TRZ6-diagnostico(trazadorassps.trazadora_12).txt";
    if ($handle = fopen($filename, 'w')) {
        $res_sql_1->movefirst();
        while (!$res_sql_1->EOF) {
            $contenido = $res_sql_1->fields['cuie'] . "\t" .
                $res_sql_1->fields['clavebeneficiario'] . "\t" .
                $res_sql_1->fields['afitipodoc'] . "\t" .
                $res_sql_1->fields['afidni'] . "\t" .
                $res_sql_1->fields['afiapellido'] . "\t" .
                $res_sql_1->fields['afinombre'] . "\t" .
                $res_sql_1->fields['afifechanac'] . "\t" .
                $res_sql_1->fields['afisexo'] . "\t" .
                'D' . "\t" .
                $res_sql_1->fields['fecha_diagnostico'] . "\t" .
                $res_sql_1->fields['diagnostico'] . "\t10\r\n";
            fwrite($handle, $contenido);
            $res_sql_1->MoveNext();
        }
        fclose($handle);
    }

    $filename2 = "$trz" . "12" . "$anio" . "$cuatrim" . "00001-TRZ6-Tratamiento(trazadorassps.trazadora_12).txt";
    if ($handle2 = fopen($filename2, 'w')) {
        $res_sql_1->movefirst();
        while (!$res_sql_1->EOF) {
            $contenido = $res_sql_1->fields['cuie'] . "\t" .
                $res_sql_1->fields['clavebeneficiario'] . "\t" .
                $res_sql_1->fields['afitipodoc'] . "\t" .
                $res_sql_1->fields['afidni'] . "\t" .
                $res_sql_1->fields['afiapellido'] . "\t" .
                $res_sql_1->fields['afinombre'] . "\t" .
                $res_sql_1->fields['afifechanac'] . "\t" .
                $res_sql_1->fields['afisexo'] . "\t" .
                'T' . "\t" .
                $res_sql_1->fields['fecha_inic_tratamiento'] . "\t\t10\r\n";
            fwrite($handle2, $contenido);
            $res_sql_1->MoveNext();
        }
        fclose($handle2);
    }
    $mensaje_resultado_tradicional = "Archivos de Trazadora 6 generados con éxito.";
}

function genera_trazadora_7($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente) {
    global $mensaje_resultado_tradicional;
    $date = $anio . '-01-01';
    $sql_1 = "SELECT distinct on (afidni,fecha_diagnostico)
            nacer.smiafiliados.clavebeneficiario, 
            nacer.smiafiliados.afiapellido, 
            nacer.smiafiliados.afinombre, 
            nacer.smiafiliados.afitipodoc, 
            nacer.smiafiliados.afidni, 
            nacer.smiafiliados.afifechanac,
            nacer.smiafiliados.afisexo::character(1),
            trazadorassps.trazadora_13.fecha_diagnostico,
            trazadorassps.trazadora_13.diagnostico,
            trazadorassps.trazadora_13.fecha_inic_tratamiento,
            trazadorassps.trazadora_13.cuie
            from nacer.smiafiliados
            inner join trazadorassps.trazadora_13 on nacer.smiafiliados.id_smiafiliados=trazadorassps.trazadora_13.id_smiafiliados
            where trazadorassps.trazadora_13.fecha_diagnostico>='$date' and trazadorassps.trazadora_13.diagnostico<>''";
            
    $res_sql_1 = sql($sql_1, "error al traer los registro de la trazadora VII") or fin_pagina();
    $filename = "$trz" . "12" . "$anio" . "$cuatrim" . "00001-TRZ7-diag(trazadorassps.trazadora_13).txt";
    if ($handle = fopen($filename, 'w')) {
        $res_sql_1->movefirst();
        while (!$res_sql_1->EOF) {
            $contenido = $res_sql_1->fields['cuie'] . "\t" .
                $res_sql_1->fields['clavebeneficiario'] . "\t" .
                $res_sql_1->fields['afitipodoc'] . "\t" .
                $res_sql_1->fields['afidni'] . "\t" .
                $res_sql_1->fields['afiapellido'] . "\t" .
                $res_sql_1->fields['afinombre'] . "\t" .
                $res_sql_1->fields['afifechanac'] . "\t" .
                $res_sql_1->fields['afisexo'] . "\t" .
                'D' . "\t" .
                $res_sql_1->fields['fecha_diagnostico'] . "\t" .
                $res_sql_1->fields['diagnostico'] . "\t10\r\n";
            fwrite($handle, $contenido);
            $res_sql_1->MoveNext();
        }
        fclose($handle);
    }
    $mensaje_resultado_tradicional = "Archivos de Trazadora 7 generados con éxito.";
}

function genera_trazadora_8($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente) {}
function genera_trazadora_9($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente) {}
function genera_trazadora_10($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente) {}

// Procesamiento del bloque 1: Exportación tradicional
$mensaje_resultado_tradicional = "";
if (isset($_POST['generar'])) {
    $anio = $_POST['anio'];
    $cuatrim = $_POST['cuatrimestre'];
    $fuente = $_POST['fuente'];

    if ($_POST['opcion_fecha'] != 'S') {
        switch ($cuatrim) {
            case 1 : $fecha_desde = $anio . "-01-01"; $fecha_hasta = $anio . "-04-30"; break;
            case 2 : $fecha_desde = $anio . "-05-01"; $fecha_hasta = $anio . "-08-31"; break;
            case 3 : $fecha_desde = $anio . "-09-01"; $fecha_hasta = $anio . "-12-31"; break;
        }
    } else {
        $fecha_desde = fecha_db($_POST['fecha_desde']);
        $fecha_hasta = fecha_db($_POST['fecha_hasta']);
    }

    $trz = $_POST['trazadora'];
    switch ($trz) {
        case 'EB': genera_trazadora_1($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente); break;
        case 'NI': genera_trazadora_2($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente); break;
        case 'T3': genera_trazadora_3($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente); break;
        case 'SP': genera_trazadora_4($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente); break;
        case 'RE': genera_trazadora_5($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente); break;
        case 'UT': genera_trazadora_6($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente); break;
        case 'MA': genera_trazadora_7($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente); break;
        case 'DI': genera_trazadora_8($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente); break;
        case 'HT': genera_trazadora_9($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente); break;
        case 'EP': genera_trazadora_10($trz, $fecha_desde, $fecha_hasta, $anio, $cuatrim, $fuente); break;
    }
}

// Procesamiento del bloque 2: Nueva Trazadora 1 con delimitación por tabulador (\t)
$mensaje_exito_trz1 = "";
$mensaje_error_trz1 = "";
$archivo_generado_trz1 = "";
$cantidad_filas_trz1 = 0;
$preview_filas_trz1 = array();

// Valores por defecto para el formulario de Trazadora 1
$f_desde_trz1_val = isset($_POST['fecha_desde_trz1']) ? $_POST['fecha_desde_trz1'] : "01/01/2026";
$f_hasta_trz1_val = isset($_POST['fecha_hasta_trz1']) ? $_POST['fecha_hasta_trz1'] : "30/04/2026";

if (isset($_POST['generar_trz1'])) {
    $f_desde_db = Fecha_db($_POST['fecha_desde_trz1']);
    $f_hasta_db = Fecha_db($_POST['fecha_hasta_trz1']);

    if (empty($f_desde_db) || empty($f_hasta_db)) {
        $mensaje_error_trz1 = "Debe ingresar un rango válido de fechas (Desde y Hasta).";
    } else {
        $sql_trz1 = "SELECT
            facturacion.comprobante.cuie, 
            nacer.smiafiliados.clavebeneficiario, 
            nacer.smiafiliados.afitipodoc, 
            nacer.smiafiliados.afidni, 
            nacer.smiafiliados.afiapellido, 
            nacer.smiafiliados.afinombre, 
            nacer.smiafiliados.afifechanac, 
            trim(nacer.smiafiliados.afisexo) AS sexo, 
            CASE 
                WHEN (nomenclador.grupo || nomenclador.codigo || prestacion.diagnostico) IN ('CTC005W78', 'CTC006W78','CTC099W78','CTC100W78','MDM090W78','MDM089W78', 'MDM088W78','MDM087W78','CTC022O24.4','CTC022O10.0','CTC022O10.4','CTC022O16') THEN 'C' 
                WHEN (nomenclador.grupo || nomenclador.codigo || prestacion.diagnostico) IN ('ITQ002W88', 'ITQ002W89', 'ITQ001W90','ITQ001W91') THEN 'P'
                WHEN (nomenclador.codigo) IN ('L065','L080','L099','L128') THEN 'S2'
                WHEN (nomenclador.codigo) IN ('L119','L006','L142') THEN 'S1'
                WHEN (nomenclador.codigo) IN ('L121','L122','L127','L141','L145') THEN 'S3'
            END AS tipo_control,
            facturacion.comprobante.fecha_comprobante :: DATE AS fecha_control,
            CASE 
                WHEN edad_gestacional = -1 THEN 25 
                ELSE edad_gestacional 
            END AS edad_gestacional,
            CASE 
                WHEN tension_arterial = '/' THEN '120/080' 
                ELSE tension_arterial 
            END AS tension_arterial_corregida,
            '2' AS fuente_info
        FROM facturacion.prestacion
        JOIN facturacion.comprobante USING (id_comprobante)
        JOIN facturacion.nomenclador USING (id_nomenclador)
        JOIN nacer.smiafiliados USING (id_smiafiliados)
        WHERE
        ((nomenclador.grupo || nomenclador.codigo || prestacion.diagnostico) IN ('CTC005W78', 'CTC006W78','CTC099W78','CTC100W78','MDM090W78','MDM089W78', 'MDM088W78','MDM087W78','CTC022O24.4','CTC022O10.0','CTC022O10.4','CTC022O16','ITQ002W88', 'ITQ002W89', 'ITQ001W90','ITQ001W91','LBL065VMD','LBL080VMD','LBL099VMD','LBL128VMD','LBL119VMD','LBL006VMD','LBL142VMD','LBL121VMD','LBL122VMD','LBL127VMD','LBL141VMD','LBL145VMD') OR
        (nomenclador.codigo) IN ('L065','L080','L099','L128','L119','L006','L142','L121','L122','L127','L141','L145'))
        AND comprobante.fecha_comprobante >= '$f_desde_db' AND comprobante.fecha_comprobante <= '$f_hasta_db'

        UNION 

        SELECT
            trazadorassps.trazadora_2.cuie, 
            nacer.smiafiliados.clavebeneficiario, 
            nacer.smiafiliados.afitipodoc, 
            nacer.smiafiliados.afidni, 
            nacer.smiafiliados.afiapellido, 
            nacer.smiafiliados.afinombre, 
            nacer.smiafiliados.afifechanac, 
            trim(nacer.smiafiliados.afisexo) AS sexo, 
            'C' AS tipo_control, 
            trazadorassps.trazadora_2.fecha_control, 
            trazadorassps.trazadora_2.edad_gestacional, 
            trazadorassps.trazadora_2.tension_arterial,
            '2' AS fuente_info
        FROM
            trazadorassps.trazadora_2
            INNER JOIN
            nacer.smiafiliados
            ON 
                trazadorassps.trazadora_2.id_smiafiliados = nacer.smiafiliados.id_smiafiliados
        WHERE
            trazadorassps.trazadora_2.fecha_control >= '$f_desde_db' AND
            trazadorassps.trazadora_2.fecha_control <= '$f_hasta_db'
        ORDER BY fecha_control DESC";

        $res_trz1 = sql($sql_trz1, "Error al consultar registros de Trazadora 1") or fin_pagina();
        $cantidad_filas_trz1 = $res_trz1->RecordCount();

        $nombre_archivo = "TRZ1_Embarazadas_" . date("Ymd_His") . ".txt";
        $ruta_guardado = dirname(__FILE__) . "/" . $nombre_archivo;

        if (!$handle = fopen($ruta_guardado, 'w')) {
            $mensaje_error_trz1 = "No se pudo crear el archivo ($nombre_archivo) en el servidor. Verifique permisos de escritura.";
        } else {
            $res_trz1->movefirst();
            $contador = 0;
            while (!$res_trz1->EOF) {
                $fecha_ctrl = isset($res_trz1->fields['fecha_control']) ? $res_trz1->fields['fecha_control'] : (isset($res_trz1->fields['fecha_comprobante']) ? $res_trz1->fields['fecha_comprobante'] : $res_trz1->fields[9]);
                $ta = isset($res_trz1->fields['tension_arterial_corregida']) ? $res_trz1->fields['tension_arterial_corregida'] : (isset($res_trz1->fields['tension_arterial']) ? $res_trz1->fields['tension_arterial'] : $res_trz1->fields[11]);
                if ($ta == '/') $ta = '120/080';

                $campos = array(
                    trim(isset($res_trz1->fields['cuie']) ? $res_trz1->fields['cuie'] : $res_trz1->fields[0]),
                    trim(isset($res_trz1->fields['clavebeneficiario']) ? $res_trz1->fields['clavebeneficiario'] : $res_trz1->fields[1]),
                    trim(isset($res_trz1->fields['afitipodoc']) ? $res_trz1->fields['afitipodoc'] : $res_trz1->fields[2]),
                    trim(isset($res_trz1->fields['afidni']) ? $res_trz1->fields['afidni'] : $res_trz1->fields[3]),
                    trim(isset($res_trz1->fields['afiapellido']) ? $res_trz1->fields['afiapellido'] : $res_trz1->fields[4]),
                    trim(isset($res_trz1->fields['afinombre']) ? $res_trz1->fields['afinombre'] : $res_trz1->fields[5]),
                    trim(isset($res_trz1->fields['afifechanac']) ? $res_trz1->fields['afifechanac'] : $res_trz1->fields[6]),
                    trim(isset($res_trz1->fields['sexo']) ? $res_trz1->fields['sexo'] : $res_trz1->fields[7]),
                    trim(isset($res_trz1->fields['tipo_control']) ? $res_trz1->fields['tipo_control'] : $res_trz1->fields[8]),
                    trim($fecha_ctrl),
                    trim(isset($res_trz1->fields['edad_gestacional']) ? $res_trz1->fields['edad_gestacional'] : $res_trz1->fields[10]),
                    trim($ta),
                    trim(isset($res_trz1->fields['fuente_info']) ? $res_trz1->fields['fuente_info'] : $res_trz1->fields[12])
                );

                $linea = implode("\t", $campos) . "\r\n";
                fwrite($handle, $linea);

                if ($contador < 10) {
                    $preview_filas_trz1[] = $campos;
                }
                $contador++;
                $res_trz1->MoveNext();
            }
            fclose($handle);

            $archivo_generado_trz1 = $nombre_archivo;
            $mensaje_exito_trz1 = "El archivo <b>$nombre_archivo</b> se generó con éxito conteniendo <b>$contador</b> registros procesados.";
        }
    }
}

echo $html_header;
?>
<style>
/* Sistema de Diseño PAF para genera_archivo_trazadora.php */
.paf-wrapper {
    max-width: 1100px;
    margin: 15px auto 40px auto;
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
    color: #1e293b;
}

/* Cabecera Principal */
.paf-main-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: #ffffff;
    border-radius: 12px 12px 0 0;
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}
.paf-main-title {
    font-size: 19px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}
.paf-header-badge {
    background: rgba(255,255,255,0.2);
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* Tarjetas */
.paf-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    margin-bottom: 24px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}
.paf-card-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 700;
    font-size: 14px;
    color: #1e293b;
}
.paf-card-body {
    padding: 20px 24px;
}

/* Formulario Grid */
.paf-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}
.paf-form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.paf-form-label {
    font-size: 12.5px;
    font-weight: 600;
    color: #334155;
}
.paf-form-control {
    padding: 7px 11px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 12.5px;
    color: #1e293b;
    background-color: #ffffff;
    outline: none;
    transition: all 0.15s ease;
    width: 100%;
    box-sizing: border-box;
}
.paf-form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}

/* Alertas */
.paf-alert {
    border-radius: 8px;
    padding: 12px 18px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13.5px;
    font-weight: 500;
}
.paf-alert-success {
    background-color: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}
.paf-alert-danger {
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}

/* Botones */
.paf-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 9px 22px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    border: none;
    transition: all 0.15s ease;
    text-decoration: none;
}
.paf-btn-primary {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(37,99,235,0.3);
}
.paf-btn-primary:hover {
    background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
    box-shadow: 0 4px 6px rgba(37,99,235,0.4);
    transform: translateY(-1px);
}
.paf-btn-success {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(22,163,74,0.3);
}
.paf-btn-success:hover {
    background: linear-gradient(135deg, #15803d 0%, #166534 100%);
    box-shadow: 0 4px 6px rgba(22,163,74,0.4);
    transform: translateY(-1px);
}
.paf-btn-download {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: #ffffff;
    font-size: 14px;
    padding: 10px 24px;
    box-shadow: 0 2px 5px rgba(5,150,105,0.3);
}
.paf-btn-download:hover {
    background: linear-gradient(135deg, #047857 0%, #065f46 100%);
    color: #ffffff;
    transform: translateY(-1px);
}

/* Tabla de Vista Previa */
.paf-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11.5px;
}
.paf-table th {
    background-color: #1e293b;
    color: #ffffff;
    font-weight: 600;
    padding: 8px 10px;
    text-align: left;
    white-space: nowrap;
    border-right: 1px solid #334155;
}
.paf-table td {
    padding: 7px 10px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    font-family: 'Consolas', monospace;
    white-space: nowrap;
}
.paf-table tr:hover td {
    background-color: #f8fafc;
}

/* Divisor de Sección */
.paf-section-divider {
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 30px 0 20px 0;
    color: #64748b;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.paf-section-divider::before,
.paf-section-divider::after {
    content: "";
    flex: 1;
    border-bottom: 2px solid #e2e8f0;
}
</style>

<div class="paf-wrapper">

    <!-- CABECERA PRINCIPAL -->
    <div class="paf-main-header">
        <div class="paf-main-title">
            <span>📊</span> Módulo de Trazadoras SPS: Exportación de Archivos
        </div>
        <div class="paf-header-badge">
            San Luis - Programa SUMAR
        </div>
    </div>

    <!-- MENSAJES GENERALES -->
    <?if (!empty($mensaje_resultado_tradicional)):?>
        <div class="paf-alert paf-alert-success" style="margin-top: 14px;">
            <span>ℹ️</span> <?=$mensaje_resultado_tradicional?>
        </div>
    <?endif;?>

    <!-- BLOQUE 1: EXPORTACIÓN GENERAL TRADICIONAL -->
    <div class="paf-card" style="border-radius: 0 0 10px 10px; margin-bottom: 24px;">
        <div class="paf-card-header">
            <span>⚙️ Sistema Tradicional de Exportación de Trazadoras</span>
            <span style="font-size: 12px; color: #64748b; font-weight: normal;">Formatos preexistentes</span>
        </div>
        <div class="paf-card-body">
            <form name="form1" action="genera_archivo_trazadora.php" method="POST" enctype="multipart/form-data">
                <div class="paf-form-grid">
                    <div class="paf-form-group">
                        <label class="paf-form-label">Trazadora:</label>
                        <select name="trazadora" class="paf-form-control">
                            <option value="EB">Trazadora 1 - Cuidado del Embarazo</option>
                            <option value="NI">Trazadora 2 - Seg. Salud 10 años</option>
                            <option value="T3">Trazadora 3 - Seg. Salud Adolescentes</option>
                            <option value="SP">Trazadora 4 - Seg. Sobrepeso y Obesidad</option>
                            <option value="RE">Trazadora 5 - Cáncer Colorrectal</option>
                            <option value="UT">Trazadora 6 - Cáncer Cérvico-Uterino</option>
                            <option value="MA">Trazadora 7 - Cáncer de Mama</option>
                            <option value="DI">Trazadora 8 - Adulto con Diabetes</option>
                            <option value="HT">Trazadora 9 - Adulto con Hipertensión</option>
                            <option value="EP">Trazadora 10 - Identificación Población</option>
                        </select>
                    </div>

                    <div class="paf-form-group">
                        <label class="paf-form-label">Cuatrimestre:</label>
                        <select name="cuatrimestre" class="paf-form-control">
                            <option value="1">Cuatrimestre I (Ene - Abr)</option>
                            <option value="2">Cuatrimestre II (May - Ago)</option>
                            <option value="3">Cuatrimestre III (Sep - Dic)</option>
                        </select>
                    </div>

                    <div class="paf-form-group">
                        <label class="paf-form-label">Año:</label>
                        <select name="anio" class="paf-form-control">
                            <?for ($y = date("Y"); $y >= 2023; $y--):?>
                                <option value="<?=$y?>"><?=$y?></option>
                            <?endfor;?>
                        </select>
                    </div>

                    <div class="paf-form-group">
                        <label class="paf-form-label">Fuente de Datos:</label>
                        <select name="fuente" class="paf-form-control">
                            <option value="trazadoras">Trazadoras</option>
                            <option value="fichero">Fichero</option>
                            <option value="sipweb">SipWeb</option>
                            <option value="prestaciones">Prestaciones</option>
                            <option value="preg_prenatal">Preg_Prenatal</option>
                            <option value="registros_medicos">Registros Médicos</option>
                        </select>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; border-top: 1px solid #f1f5f9; padding-top: 14px;">
                    <input type="submit" name="generar" value="Generar Archivo Tradicional" class="paf-btn paf-btn-primary">
                </div>
            </form>
        </div>
    </div>

    <!-- SEPARADOR VISUAL -->
    <div class="paf-section-divider">
        <span>Nueva Funcionalidad Especial</span>
    </div>

    <!-- ALERTA DE RESULTADO TRAZADORA 1 -->
    <?if (!empty($mensaje_exito_trz1)):?>
        <div class="paf-alert paf-alert-success">
            <span style="font-size: 20px;">✅</span>
            <div style="flex:1;">
                <?=$mensaje_exito_trz1?>
            </div>
            <?if (!empty($archivo_generado_trz1)):?>
                <a href="genera_archivo_trazadora.php?descargar_archivo=<?=urlencode($archivo_generado_trz1)?>" class="paf-btn paf-btn-download">
                    ⬇ Descargar Archivo .txt
                </a>
            <?endif;?>
        </div>
    <?endif;?>

    <?if (!empty($mensaje_error_trz1)):?>
        <div class="paf-alert paf-alert-danger">
            <span style="font-size: 20px;">⚠️</span>
            <div><?=$mensaje_error_trz1?></div>
        </div>
    <?endif;?>

    <!-- BLOQUE 2: NUEVA GENERACIÓN DE ARCHIVO TRAZADORA 1 (SEPARADO POR DEBAJO) -->
    <div class="paf-card">
        <div class="paf-card-header" style="background: linear-gradient(to right, #eff6ff, #f8fafc); border-left: 4px solid #2563eb;">
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size: 18px;">🤰</span>
                <span>Trazadora 1: Seguimiento de Salud de la Mujer Embarazada (Archivo Tabulado .txt)</span>
            </div>
            <span style="font-size: 11.5px; background: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 10px; font-weight: 700;">
                Separado por &lt;TAB&gt;
            </span>
        </div>
        <div class="paf-card-body">
            <p style="font-size: 13px; color: #64748b; margin-top: 0; margin-bottom: 16px;">
                Genera el archivo consolidado de prestaciones de facturación y controles directos prenatales con 13 columnas delimitadas por tabulador (CUIE, Clave, Tipo Doc, DNI, Apellido, Nombre, Fecha Nac., Sexo, Tipo Control, Fecha Control, Edad Gest., Tensión Art., Fuente).
            </p>

            <form name="form_trz1" action="genera_archivo_trazadora.php" method="POST">
                <div class="paf-form-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
                    <div class="paf-form-group">
                        <label class="paf-form-label">Fecha Desde:*</label>
                        <div style="display:flex; align-items:center; gap:6px;">
                            <input type="text" id="fecha_desde_trz1" name="fecha_desde_trz1" value="<?=$f_desde_trz1_val?>" class="paf-form-control" style="width:140px;" readonly>
                            <?=link_calendario("fecha_desde_trz1");?>
                        </div>
                    </div>

                    <div class="paf-form-group">
                        <label class="paf-form-label">Fecha Hasta:*</label>
                        <div style="display:flex; align-items:center; gap:6px;">
                            <input type="text" id="fecha_hasta_trz1" name="fecha_hasta_trz1" value="<?=$f_hasta_trz1_val?>" class="paf-form-control" style="width:140px;" readonly>
                            <?=link_calendario("fecha_hasta_trz1");?>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; border-top: 1px solid #f1f5f9; padding-top: 16px; margin-top: 10px;">
                    <div style="font-size: 12px; color: #64748b;">
                        <b>Rango uniforme:</b> El mismo rango de fechas se aplica automáticamente a ambas fuentes (Comprobantes y Controles prenatales).
                    </div>
                    <input type="submit" name="generar_trz1" value="Generar Archivo Trazadora 1 (.txt)" class="paf-btn paf-btn-success">
                </div>
            </form>
        </div>

        <!-- VISTA PREVIA DE LOS REGISTROS GENERADOS -->
        <?if (!empty($preview_filas_trz1)):?>
            <div style="border-top: 1px solid #e2e8f0; background: #f8fafc; padding: 14px 20px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 8px;">
                    <span style="font-weight: 700; font-size: 12.5px; color: #1e293b;">
                        🔍 Vista previa de los primeros <?=count($preview_filas_trz1)?> registros generados en <span style="color:#1d4ed8; font-family:'Consolas',monospace;"><?=$archivo_generado_trz1?></span>:
                    </span>
                    <a href="genera_archivo_trazadora.php?descargar_archivo=<?=urlencode($archivo_generado_trz1)?>" style="font-size: 12px; font-weight:700; color:#047857; text-decoration:none;">
                        ⬇ Descargar archivo completo &raquo;
                    </a>
                </div>
                <div style="overflow-x:auto; background:#ffffff; border:1px solid #cbd5e1; border-radius:6px;">
                    <table class="paf-table">
                        <thead>
                            <tr>
                                <th>CUIE</th>
                                <th>Clave Beneficiario</th>
                                <th>Tipo Doc</th>
                                <th>DNI</th>
                                <th>Apellido</th>
                                <th>Nombre</th>
                                <th>F. Nac</th>
                                <th>Sexo</th>
                                <th>Tipo Control</th>
                                <th>F. Control</th>
                                <th>Edad Gest.</th>
                                <th>Tensión Art.</th>
                                <th>Fuente</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?foreach ($preview_filas_trz1 as $fila):?>
                            <tr>
                                <td><?=$fila[0]?></td>
                                <td><?=$fila[1]?></td>
                                <td><?=$fila[2]?></td>
                                <td><?=$fila[3]?></td>
                                <td><?=$fila[4]?></td>
                                <td><?=$fila[5]?></td>
                                <td><?=$fila[6]?></td>
                                <td><?=$fila[7]?></td>
                                <td><b><?=$fila[8]?></b></td>
                                <td><?=$fila[9]?></td>
                                <td><?=$fila[10]?></td>
                                <td><?=$fila[11]?></td>
                                <td><?=$fila[12]?></td>
                            </tr>
                        <?endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?endif;?>
    </div>

</div>

<?=fin_pagina();?>