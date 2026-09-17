<?php
require_once ("../../config.php");

extract($_POST, EXTR_SKIP);
if ($parametros) extract($parametros, EXTR_OVERWRITE);
cargar_calendario();

// Obtener parámetros por POST o GET de forma robusta
if (isset($_POST['id_nomenclador']) && trim($_POST['id_nomenclador']) !== '') {
    $id_nomenclador = trim($_POST['id_nomenclador']);
} elseif (isset($_GET['id_nomenclador']) && trim($_GET['id_nomenclador']) !== '') {
    $id_nomenclador = trim($_GET['id_nomenclador']);
}

if (isset($_POST['id_nomenclador_detalle']) && trim($_POST['id_nomenclador_detalle']) !== '') {
    $id_nomenclador_detalle = trim($_POST['id_nomenclador_detalle']);
} elseif (isset($_GET['id_nomenclador_detalle']) && trim($_GET['id_nomenclador_detalle']) !== '') {
    $id_nomenclador_detalle = trim($_GET['id_nomenclador_detalle']);
}

if (isset($_GET['nuevo']) && $_GET['nuevo'] == '1') {
    $nuevo = 1;
    $id_nomenclador = null;
}

if (!$id_nomenclador_detalle && $id_nomenclador) {
    $q_det = "SELECT id_nomenclador_detalle FROM facturacion.nomenclador WHERE id_nomenclador = '$id_nomenclador'";
    $r_det = sql($q_det);
    if ($r_det && $r_det->RecordCount() > 0) {
        $id_nomenclador_detalle = $r_det->fields['id_nomenclador_detalle'];
    }
}
if (!$id_nomenclador_detalle) {
    $q_det = "SELECT max(id_nomenclador_detalle) as id_det FROM facturacion.nomenclador_detalle WHERE activonomen = 's'";
    $r_det = sql($q_det);
    if ($r_det && $r_det->RecordCount() > 0) {
        $id_nomenclador_detalle = $r_det->fields['id_det'];
    }
}

// Detección tolerante de acciones
$accion_form = isset($_POST['accion_form']) ? trim($_POST['accion_form']) : '';
$guardar_val = isset($_POST['guardar']) ? trim($_POST['guardar']) : '';

$is_guardar_nuevo = (
    $accion_form === 'guardar_nueva_prestacion' ||
    stripos($guardar_val, 'Nueva') !== false
);

$is_guardar_existente = (
    $accion_form === 'guardar_parametros' ||
    stripos($guardar_val, 'Param') !== false ||
    stripos($guardar_val, 'Parám') !== false
);

$is_guardar_diag = (
    $accion_form === 'guardar_diagnostico' ||
    stripos($guardar_val, 'Diag') !== false ||
    $_POST['guardar'] == "Guardar Diagnostico"
);

// 1. Guardar NUEVA prestación
if ($is_guardar_nuevo) {
    $codigo = trim(strtoupper($_POST['codigo']));
    $grupo = trim(strtoupper($_POST['grupo']));
    $subgrupo = trim($_POST['subgrupo']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval(str_replace(',', '.', str_replace('$', '', trim($_POST['precio']))));
    
    // Switches SI / NO: con hidden inputs siempre llega "1" o "0"
    $neo = (isset($_POST['neo']) && $_POST['neo'] == '1') ? '1' : '0';
    $ceroacinco = (isset($_POST['ceroacinco']) && $_POST['ceroacinco'] == '1') ? '1' : '0';
    $seisanueve = (isset($_POST['seisanueve']) && $_POST['seisanueve'] == '1') ? '1' : '0';
    $adol = (isset($_POST['adol']) && $_POST['adol'] == '1') ? '1' : '0';
    $adulto = (isset($_POST['adulto']) && $_POST['adulto'] == '1') ? '1' : '0';
    $f = (isset($_POST['f']) && $_POST['f'] == '1') ? '1' : '0';
    $m = (isset($_POST['m']) && $_POST['m'] == '1') ? '1' : '0';
    $catas = (isset($_POST['catas']) && $_POST['catas'] == '1') ? '1' : '0';
    $priori = (isset($_POST['priori']) && $_POST['priori'] == '1') ? '1' : '0';
    $ceb = (isset($_POST['ceb']) && ($_POST['ceb'] == '1' || strtoupper($_POST['ceb']) == 'S')) ? 'S' : 'N';
    $activo = (isset($_POST['activo']) && ($_POST['activo'] == '1' || strtolower($_POST['activo']) == 't')) ? 't' : 'f';

    if ($codigo == "" || $grupo == "" || $descripcion == "") {
        $accion = "Error: Debe ingresar Código, Grupo y Descripción para la nueva prestación.";
    } else {
        $q_dup = "SELECT id_nomenclador FROM facturacion.nomenclador 
                  WHERE codigo = '$codigo' AND grupo = '$grupo' AND id_nomenclador_detalle = $id_nomenclador_detalle";
        $r_dup = sql($q_dup);
        if ($r_dup && $r_dup->RecordCount() > 0) {
            $accion = "Error: Ya existe una prestación con Código '$codigo' y Grupo '$grupo' para este nomenclador.";
        } else {
            $db->StartTrans();
            $q_seq = "SELECT nextval('facturacion.nomenclador_id_nomenclador_seq') as id_nom";
            $r_seq = sql($q_seq) or fin_pagina();
            $id_nomenclador = $r_seq->fields['id_nom'];

            // Auto-sincronización de secuencia en caso de IDs manuales previos
            $q_chk = "SELECT id_nomenclador FROM facturacion.nomenclador WHERE id_nomenclador = $id_nomenclador";
            $r_chk = sql($q_chk);
            if ($r_chk && $r_chk->RecordCount() > 0) {
                sql("SELECT setval('facturacion.nomenclador_id_nomenclador_seq', (SELECT max(id_nomenclador) FROM facturacion.nomenclador))");
                $r_seq = sql("SELECT nextval('facturacion.nomenclador_id_nomenclador_seq') as id_nom") or fin_pagina();
                $id_nomenclador = $r_seq->fields['id_nom'];
            }

            $query = "INSERT INTO facturacion.nomenclador (
                        id_nomenclador,
                        codigo,
                        grupo,
                        subgrupo,
                        descripcion,
                        precio,
                        id_nomenclador_detalle,
                        tipo_nomenclador,
                        neo,
                        ceroacinco,
                        seisanueve,
                        adol,
                        adulto,
                        f,
                        m,
                        catas,
                        priori,
                        ceb,
                        activo
                      ) VALUES (
                        $id_nomenclador,
                        '$codigo',
                        '$grupo',
                        '$subgrupo',
                        '$descripcion',
                        $precio,
                        $id_nomenclador_detalle,
                        'NORMAL',
                        '$neo',
                        '$ceroacinco',
                        '$seisanueve',
                        '$adol',
                        '$adulto',
                        '$f',
                        '$m',
                        '$catas',
                        '$priori',
                        '$ceb',
                        '$activo'
                      )";
            sql($query, "Error al insertar la prestación") or fin_pagina();
            $db->CompleteTrans();
            $accion = "Se Creó la Prestación exitosamente (#$id_nomenclador). Ahora puede vincular sus Diagnósticos.";
        }
    }
}

// 2. Actualizar prestación EXISTENTE
if ($is_guardar_existente && $id_nomenclador) {
    $precio = floatval(str_replace(',', '.', str_replace('$', '', trim($_POST['precio']))));
    $codigo = trim(strtoupper($_POST['codigo']));
    $grupo = trim(strtoupper($_POST['grupo']));
    $subgrupo = trim($_POST['subgrupo']);
    $descripcion = trim($_POST['descripcion']);
    
    // Switches SI / NO: con hidden inputs siempre llega "1" o "0"
    $neo = (isset($_POST['neo']) && $_POST['neo'] == '1') ? '1' : '0';
    $ceroacinco = (isset($_POST['ceroacinco']) && $_POST['ceroacinco'] == '1') ? '1' : '0';
    $seisanueve = (isset($_POST['seisanueve']) && $_POST['seisanueve'] == '1') ? '1' : '0';
    $adol = (isset($_POST['adol']) && $_POST['adol'] == '1') ? '1' : '0';
    $adulto = (isset($_POST['adulto']) && $_POST['adulto'] == '1') ? '1' : '0';
    $f = (isset($_POST['f']) && $_POST['f'] == '1') ? '1' : '0';
    $m = (isset($_POST['m']) && $_POST['m'] == '1') ? '1' : '0';
    $catas = (isset($_POST['catas']) && $_POST['catas'] == '1') ? '1' : '0';
    $priori = (isset($_POST['priori']) && $_POST['priori'] == '1') ? '1' : '0';
    $ceb = (isset($_POST['ceb']) && ($_POST['ceb'] == '1' || strtoupper($_POST['ceb']) == 'S')) ? 'S' : 'N';
    $activo = (isset($_POST['activo']) && ($_POST['activo'] == '1' || strtolower($_POST['activo']) == 't')) ? 't' : 'f';

    $query = "UPDATE facturacion.nomenclador
              SET
                codigo = '$codigo',
                grupo = '$grupo',
                subgrupo = '$subgrupo',
                neo = '$neo',
                ceroacinco = '$ceroacinco',
                seisanueve = '$seisanueve',
                adol = '$adol',
                adulto = '$adulto',
                f = '$f',
                m = '$m',
                precio = $precio,
                priori = '$priori',
                catas = '$catas',
                ceb = '$ceb',
                activo = '$activo',
                descripcion = '$descripcion'
              WHERE id_nomenclador = '$id_nomenclador'";
    $val = sql($query, "Error en consulta de parametros") or fin_pagina(); 
    $accion = "Se Actualizaron los Parámetros con Éxito.";
}

// 3. Eliminar diagnóstico vinculado (Lógica Nativa SL)
if ($borra_efec == 'borra_efec') {	
    $query = "DELETE FROM facturacion.parametro_nomen  
              WHERE id_parametro_nomen = '$id_parametro_nomen'";
    sql($query, "Error al eliminar") or fin_pagina();
    $accion = "Diagnóstico Eliminado con Éxito.";
}

// 4. Guardar diagnóstico vinculado a la prestación (Lógica Nativa SL)
if ($is_guardar_diag && $id_nomenclador) {
    $fecha_carga = date("Y-m-d H:i:s");
    $usuario = $_ses_user['name'];
    $diag = isset($_POST['diag']) ? trim($_POST['diag']) : '';

    if ($diag == "-1" || empty($diag)) {
        $accion = "Error: Debe seleccionar un diagnóstico válido.";
    } else {
        $query = "SELECT * FROM facturacion.parametro_nomen
                  WHERE trim(codigo) = '$diag' AND id_nomenclador = $id_nomenclador";
        $val = sql($query, "Error en consulta de validacion") or fin_pagina();    
        if ($val->RecordCount() == 0) {
            $db->StartTrans();
            $q = "SELECT nextval('facturacion.parametro_nomen_id_parametro_nomen_seq') as id_comprobante";
            $id_comprobante = sql($q) or fin_pagina();
            $id_comprobante = $id_comprobante->fields['id_comprobante'];
                        
            $query = "INSERT INTO facturacion.parametro_nomen
                     (id_parametro_nomen, codigo, id_nomenclador, usuario)
                     VALUES
                     ($id_comprobante, '$diag', '$id_nomenclador', '$usuario'||'-'||'$fecha_carga')";	
            sql($query, "Error al insertar el comprobante") or fin_pagina();	    
            $accion = "Se guardó el Diagnóstico con Éxito.";
            
            $db->CompleteTrans(); 
        } else {
            $accion = "El Diagnóstico ya se encuentra vinculado a esta Prestación.";
        }	 
    }
}

// Obtener datos actuales de la prestación
if ($id_nomenclador) {
    $sql = "SELECT * FROM facturacion.nomenclador WHERE id_nomenclador = $id_nomenclador";
    $res_comprobante = sql($sql, "Error al traer los Comprobantes") or fin_pagina();
    if ($res_comprobante && !$res_comprobante->EOF) {
        $codigo = $res_comprobante->fields['codigo'];
        $grupo = $res_comprobante->fields['grupo'];
        $subgrupo = $res_comprobante->fields['subgrupo'];
        $descripcion = $res_comprobante->fields['descripcion'];
        $precio = trim($res_comprobante->fields['precio']);
        $neo = trim($res_comprobante->fields['neo']);
        $ceroacinco = trim($res_comprobante->fields['ceroacinco']);
        $seisanueve = trim($res_comprobante->fields['seisanueve']);
        $adol = trim($res_comprobante->fields['adol']);
        $adulto = trim($res_comprobante->fields['adulto']);
        $f = trim($res_comprobante->fields['f']);
        $m = trim($res_comprobante->fields['m']);
        $priori = trim($res_comprobante->fields['priori']);
        $catas = trim($res_comprobante->fields['catas']);
        $ceb = trim($res_comprobante->fields['ceb']);
        $activo = trim($res_comprobante->fields['activo']);
        $id_nomenclador_detalle = $res_comprobante->fields['id_nomenclador_detalle'];
        $modo_facturacion = ($res_comprobante->fields['tipo_nomenclador'] == 'NORMAL') ? 4 : 3;
    }
} else {
    // Valores predeterminados para NUEVA prestación
    $codigo = "";
    $grupo = "";
    $subgrupo = "";
    $descripcion = "";
    $precio = "0";
    $neo = "1";
    $ceroacinco = "1";
    $seisanueve = "1";
    $adol = "1";
    $adulto = "1";
    $f = "1";
    $m = "1";
    $priori = "0";
    $catas = "0";
    $ceb = "N";
    $activo = "t";
    $modo_facturacion = 4;
}

// Datos de contexto del Nomenclador Detalle
$info_nd = null;
if (!empty($id_nomenclador_detalle)) {
    $res_nd = sql("SELECT descripcion, modo_facturacion, fecha_desde, fecha_hasta, activonomen FROM facturacion.nomenclador_detalle WHERE id_nomenclador_detalle = " . intval($id_nomenclador_detalle));
    if ($res_nd && !$res_nd->EOF) {
        $info_nd = $res_nd->fields;
    }
}

// Presets de Grupos comunes en el Programa SUMAR
$grupos_presets = array(
    'AP' => array('nombre' => 'Atención Primaria / Promoción', 'subgrupo' => 'CONSULTA'),
    'CT' => array('nombre' => 'Consulta Médica Especializada', 'subgrupo' => 'CONSULTA'),
    'IG' => array('nombre' => 'Intervención Quirúrgica', 'subgrupo' => 'INTERVENCION'),
    'IM' => array('nombre' => 'Diagnóstico por Imágenes', 'subgrupo' => 'IMAGENES'),
    'LB' => array('nombre' => 'Laboratorio / Análisis Clínicos', 'subgrupo' => 'LABORATORIO'),
    'PR' => array('nombre' => 'Práctica Médica / Procedimiento', 'subgrupo' => 'PRACTICA'),
    'TC' => array('nombre' => 'Teleconsulta / Consejería', 'subgrupo' => 'TELECONSULTA'),
    'TA' => array('nombre' => 'Taller / Capacitación Grupal', 'subgrupo' => 'TALLER'),
    'TL' => array('nombre' => 'Traslado Sanitario', 'subgrupo' => 'TRASLADO'),
    'NT' => array('nombre' => 'Notificación de Caso / Ficha', 'subgrupo' => 'NOTIFICACION'),
    'RO' => array('nombre' => 'Ronda Sanitaria', 'subgrupo' => 'RONDA'),
    'XM' => array('nombre' => 'Medicamentos / Prótesis / Insumos', 'subgrupo' => 'MEDICAMENTOS'),
);

echo $html_header;
?>
<style>
/* Estilos modernos y prolijos para param_admin_fin - SUMAR San Luis */
.paf-wrapper {
    max-width: 1180px;
    margin: 15px auto 40px auto;
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
    color: #1e293b;
}
.paf-header-bar {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: #ffffff;
    border-radius: 12px;
    padding: 16px 24px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    flex-wrap: wrap;
    gap: 12px;
}
.paf-title {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}
.paf-subtitle {
    font-size: 13px;
    font-weight: 400;
    opacity: 0.9;
    margin-top: 3px;
}
.paf-alert {
    padding: 12px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.paf-alert-info {
    background-color: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}
.paf-alert-success {
    background-color: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
}
.paf-alert-danger {
    background-color: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}
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
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.paf-card-header h3 {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.paf-card-body {
    padding: 20px;
}
.paf-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 18px;
}
.paf-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.paf-field label {
    font-size: 12.5px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.paf-field input[type="text"], 
.paf-field textarea {
    padding: 9px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13.5px;
    color: #1e293b;
    background-color: #ffffff;
    outline: none;
    transition: all 0.2s ease;
    font-family: inherit;
}
.paf-field select {
    padding: 7px 10px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 12px;
    color: #1e293b;
    background-color: #ffffff;
    outline: none;
    transition: all 0.2s ease;
    font-family: inherit;
}
.paf-field select option {
    font-size: 12px;
    padding: 3px 6px;
}
.paf-field input[type="text"]:focus, 
.paf-field select:focus, 
.paf-field textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
.paf-select-diag {
    flex: 1;
    min-width: 320px;
    max-width: 780px;
    padding: 7px 10px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 12px;
    color: #1e293b;
    background-color: #ffffff;
    outline: none;
    font-family: inherit;
}
.paf-select-diag option {
    font-size: 12px;
    padding: 3px 6px;
}
.paf-field-full {
    grid-column: 1 / -1;
}
.paf-switches-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}
@media (max-width: 768px) {
    .paf-switches-layout {
        grid-template-columns: 1fr;
    }
}
.paf-switch-card {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
}
.paf-switch-card-title {
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
    border-bottom: 1px solid #cbd5e1;
    padding-bottom: 8px;
}
.paf-switch-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 10px;
    border-radius: 6px;
    margin-bottom: 4px;
    transition: background 0.15s ease;
    cursor: pointer;
}
.paf-switch-row:hover {
    background-color: #f1f5f9;
}
.paf-switch-row span.label-text {
    font-size: 13.5px;
    font-weight: 500;
    color: #334155;
}
.paf-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}
.paf-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.paf-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 24px;
}
.paf-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
input:checked + .paf-slider {
    background-color: #10b981;
}
input:checked + .paf-slider:before {
    transform: translateX(20px);
}
.paf-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 6px;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
    text-decoration: none !important;
}
.paf-btn-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff !important;
}
.paf-btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    box-shadow: 0 4px 6px -1px rgba(37,99,235,0.3);
}
.paf-btn-create {
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    color: #ffffff !important;
}
.paf-btn-create:hover {
    background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
    box-shadow: 0 4px 6px -1px rgba(109,40,217,0.3);
}
.paf-btn-secondary {
    background: #f1f5f9;
    color: #334155 !important;
    border: 1px solid #cbd5e1;
}
.paf-btn-secondary:hover {
    background: #e2e8f0;
    color: #0f172a !important;
}
.paf-btn-danger {
    background-color: #fee2e2;
    color: #b91c1c !important;
    border: 1px solid #fecaca;
    padding: 4px 10px;
    font-size: 12px;
}
.paf-btn-danger:hover {
    background-color: #dc2626;
    color: #ffffff !important;
    border-color: #dc2626;
}
.paf-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.paf-table th {
    background-color: #1e293b;
    color: #ffffff;
    font-weight: 600;
    padding: 10px 14px;
    text-align: left;
    border-right: 1px solid #334155;
    white-space: nowrap;
}
.paf-table td {
    padding: 10px 14px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
}
.paf-table tr:hover td {
    background-color: #f8fafc;
}
.paf-badge-code {
    display: inline-block;
    padding: 3px 8px;
    background-color: #e0e7ff;
    color: #3730a3;
    border-radius: 4px;
    font-weight: 700;
    font-family: 'Consolas', monospace;
    font-size: 12.5px;
}
.paf-badge-id {
    display: inline-block;
    padding: 2px 7px;
    background-color: #f1f5f9;
    color: #475569;
    border-radius: 4px;
    font-weight: 600;
    font-size: 11.5px;
}
.paf-diag-search-box {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
    background-color: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    margin-bottom: 20px;
}
</style>

<script>
// Manejo dinámico e interactivo de switches
function pafUpdateSwitch(fieldName, isChecked) {
    var hiddenInput = document.getElementById('input_' + fieldName);
    if (!hiddenInput) return;
    
    if (fieldName === 'activo') {
        hiddenInput.value = isChecked ? 't' : 'f';
    } else if (fieldName === 'ceb') {
        hiddenInput.value = isChecked ? 'S' : 'N';
    } else {
        hiddenInput.value = isChecked ? '1' : '0';
    }
}

function pafToggleFromRow(fieldName) {
    var checkbox = document.getElementById('switch_' + fieldName);
    if (!checkbox) return;
    checkbox.checked = !checkbox.checked;
    pafUpdateSwitch(fieldName, checkbox.checked);
}

// Autocompletado de Grupo y Subgrupo
function pafSelectGrupo(selectEl) {
    var val = selectEl.value;
    var customInput = document.getElementById('field_grupo_custom');
    var subgrupoInput = document.getElementById('field_subgrupo');
    
    if (val === 'OTRO') {
        customInput.style.display = 'block';
        customInput.value = '';
        customInput.focus();
    } else {
        customInput.style.display = 'none';
        customInput.value = val;
        
        var presets = {
            'AP': 'CONSULTA',
            'CT': 'CONSULTA',
            'IG': 'INTERVENCION',
            'IM': 'IMAGENES',
            'LB': 'LABORATORIO',
            'PR': 'PRACTICA',
            'TC': 'TELECONSULTA',
            'TA': 'TALLER',
            'TL': 'TRASLADO',
            'NT': 'NOTIFICACION',
            'RO': 'RONDA',
            'XM': 'MEDICAMENTOS'
        };
        if (presets[val] && (!subgrupoInput.value || subgrupoInput.value.trim() === '')) {
            subgrupoInput.value = presets[val];
        }
    }
}

function pafValidarFormulario() {
    var codigo = document.getElementById('field_codigo').value.trim();
    var desc = document.getElementById('field_descripcion').value.trim();
    var grupo = document.getElementById('field_grupo_custom').value.trim();
    
    if (!codigo) {
        alert('Debe especificar un Código para la prestación (ej. C001, A002).');
        document.getElementById('field_codigo').focus();
        return false;
    }
    if (!grupo) {
        alert('Debe seleccionar o especificar un Grupo para la prestación.');
        document.getElementById('select_grupo').focus();
        return false;
    }
    if (!desc) {
        alert('Debe ingresar la Descripción de la prestación.');
        document.getElementById('field_descripcion').focus();
        return false;
    }
    return true;
}

function pafValidarDiagnostico() {
    var diag = document.getElementById('select_diag').value;
    if (diag === '-1' || diag === '') {
        alert('Debe seleccionar un diagnóstico de la lista.');
        return false;
    }
    return confirm('¿Está seguro de vincular este diagnóstico a la prestación?');
}

// Búsqueda rápida por teclado en el desplegable de diagnósticos
var digitos = 10;
var puntero = 0;
var buffer = new Array(digitos);
var cadena = "";

function buscar_combo(obj) {
    var letra = String.fromCharCode(event.keyCode);
    if (puntero >= digitos) {
        cadena = "";
        puntero = 0;
    } else {
        buffer[puntero] = letra;
        cadena = cadena + buffer[puntero];
        puntero++;

        for (var opcombo = 1; opcombo < obj.length; opcombo++) {
            if (obj[opcombo].text.substr(0, puntero).toLowerCase() == cadena.toLowerCase()) {
                obj.selectedIndex = opcombo;
                break;
            }
        }
    }
    event.returnValue = false;
}
function borrar_buffer() {
    cadena = "";
    puntero = 0;
}
</script>

<div class="paf-wrapper">

  <!-- ENCABEZADO SUPERIOR -->
  <div class="paf-header-bar">
    <div>
      <div class="paf-title">
        <?php if ($id_nomenclador): ?>
          <span>✏️</span> Parámetros de Prestación #<?=$id_nomenclador?>
        <?php else: ?>
          <span>✨</span> Nueva Prestación Médica
        <?php endif; ?>
      </div>
      <div class="paf-subtitle">
        <?php if ($info_nd): ?>
          Nomenclador: <b><?=$info_nd['descripcion']?></b> (#<?=$id_nomenclador_detalle?>) &bull; Modo <?=$info_nd['modo_facturacion']?> &bull; Vigencia: <?=fecha($info_nd['fecha_desde'])?> al <?=fecha($info_nd['fecha_hasta'])?>
        <?php else: ?>
          Configuración de Nomenclador de Facturación - SUMAR San Luis
        <?php endif; ?>
      </div>
    </div>
    <div>
      <?php
      $ref_volver = encode_link("param_admin.php", array(
          "id_nomenclador_detalle" => $id_nomenclador_detalle,
          "modo_facturacion" => $modo_facturacion
      ));
      ?>
      <button type="button" class="paf-btn paf-btn-secondary" onclick="location.href='<?=$ref_volver?>'">
        <span>↩️</span> Volver al Listado
      </button>
    </div>
  </div>

  <!-- MENSAJES DE ESTADO / ACCIÓN -->
  <?php if (!empty($accion)): ?>
    <?php
    $alert_class = 'paf-alert-info';
    if (stripos($accion, 'Error') !== false || stripos($accion, 'no') !== false && stripos($accion, 'existe') !== false) {
        $alert_class = 'paf-alert-danger';
        $icon = '⚠️';
    } elseif (stripos($accion, 'Éxito') !== false || stripos($accion, 'exitosamente') !== false || stripos($accion, 'Actualizaron') !== false || stripos($accion, 'guardó') !== false || stripos($accion, 'guardo') !== false) {
        $alert_class = 'paf-alert-success';
        $icon = '✅';
    } else {
        $icon = 'ℹ️';
    }
    ?>
    <div class="paf-alert <?=$alert_class?>">
      <span style="font-size: 18px;"><?=$icon?></span>
      <span><?=$accion?></span>
    </div>
  <?php endif; ?>

  <!-- FORMULARIO DE PARÁMETROS -->
  <form name="form_prestacion" id="form_prestacion" action="param_admin_fin.php" method="POST" onsubmit="return pafValidarFormulario()">
    <input type="hidden" name="id_nomenclador" value="<?=$id_nomenclador?>">
    <input type="hidden" name="id_nomenclador_detalle" value="<?=$id_nomenclador_detalle?>">
    <input type="hidden" name="accion_form" id="accion_form" value="<?=($id_nomenclador ? 'guardar_parametros' : 'guardar_nueva_prestacion')?>">

    <!-- TARJETA 1: DATOS PRINCIPALES DE LA PRESTACIÓN -->
    <div class="paf-card">
      <div class="paf-card-header">
        <h3><span>📝</span> Datos Principales de la Prestación</h3>
        <?php if ($id_nomenclador): ?>
          <span class="paf-badge-id">ID Nomenclador: #<?=$id_nomenclador?></span>
        <?php endif; ?>
      </div>
      <div class="paf-card-body">
        <div class="paf-form-grid">
          
          <!-- SELECCIÓN DE GRUPO -->
          <div class="paf-field">
            <label for="select_grupo">Grupo (Objeto de la Prestación)</label>
            <?php
            $grupo_upper = strtoupper(trim($grupo));
            $is_in_presets = array_key_exists($grupo_upper, $grupos_presets);
            ?>
            <select id="select_grupo" onchange="pafSelectGrupo(this)">
              <option value="">-- Seleccionar Grupo --</option>
              <?php foreach ($grupos_presets as $g_code => $g_data): ?>
                <option value="<?=$g_code?>" <?=($grupo_upper === $g_code ? 'selected' : '')?>>
                  <?=$g_code?> - <?=$g_data['nombre']?>
                </option>
              <?php endforeach; ?>
              <option value="OTRO" <?=(!$is_in_presets && !empty($grupo_upper) ? 'selected' : '')?>>
                [Otro / Código Personalizado]
              </option>
            </select>
            <input type="text" name="grupo" id="field_grupo_custom" value="<?=$grupo?>" 
                   placeholder="Código de grupo (ej: AP, CT, TC)" 
                   style="margin-top: 6px; <?=($is_in_presets ? 'display:none;' : '')?>"
                   onblur="this.value = this.value.toUpperCase()">
          </div>

          <!-- CÓDIGO -->
          <div class="paf-field">
            <label for="field_codigo">Código de Práctica</label>
            <input type="text" name="codigo" id="field_codigo" value="<?=$codigo?>" 
                   placeholder="Ej: C001, A002, P046" required
                   style="font-family: 'Consolas', monospace; font-weight: 700; text-transform: uppercase;"
                   onblur="this.value = this.value.toUpperCase()">
          </div>

          <!-- SUBGRUPO -->
          <div class="paf-field">
            <label for="field_subgrupo">Subgrupo / Categoría</label>
            <input type="text" name="subgrupo" id="field_subgrupo" value="<?=$subgrupo?>" 
                   placeholder="Ej: CONSULTA, PRACTICA, TALLER">
          </div>

          <!-- PRECIO -->
          <div class="paf-field">
            <label for="field_precio">Precio / Arancel ($)</label>
            <input type="text" name="precio" id="field_precio" value="<?=$precio?>" 
                   placeholder="0.00" style="font-weight: 700; color: #0f766e;">
          </div>

          <!-- DESCRIPCIÓN COMPLETA -->
          <div class="paf-field paf-field-full">
            <label for="field_descripcion">Descripción Detallada de la Prestación</label>
            <textarea name="descripcion" id="field_descripcion" rows="3" 
                      placeholder="Ingrese el texto explicativo completo de la práctica médica..." required><?=$descripcion?></textarea>
          </div>

        </div>
      </div>
    </div>

    <!-- TARJETA 2: REGLAS DE HABILITACIÓN Y FACTURACIÓN -->
    <div class="paf-card">
      <div class="paf-card-header">
        <h3><span>⚙️</span> Reglas de Habilitación y Facturación</h3>
        <span style="font-size: 12px; color: #64748b;">Haga clic sobre cualquier fila para alternar SI / NO</span>
      </div>
      <div class="paf-card-body">
        
        <div class="paf-switches-layout">
          
          <!-- COLUMNA IZQUIERDA: GRUPOS ETARIOS -->
          <div class="paf-switch-card">
            <div class="paf-switch-card-title">
              <span>👶</span> Habilitación por Grupo Etario
            </div>

            <!-- Neonato -->
            <div class="paf-switch-row" onclick="pafToggleFromRow('neo')">
              <span class="label-text">Neonato (0 a 28 días)</span>
              <label class="paf-switch" onclick="if (event && event.stopPropagation) { event.stopPropagation(); }">
                <input type="checkbox" id="switch_neo" <?=($neo == '1' ? 'checked' : '')?> onchange="pafUpdateSwitch('neo', this.checked)">
                <span class="paf-slider"></span>
              </label>
              <input type="hidden" name="neo" id="input_neo" value="<?=($neo == '1' ? '1' : '0')?>">
            </div>

            <!-- Cero a Cinco -->
            <div class="paf-switch-row" onclick="pafToggleFromRow('ceroacinco')">
              <span class="label-text">Cero a Cinco años (1 a 5 años)</span>
              <label class="paf-switch" onclick="if (event && event.stopPropagation) { event.stopPropagation(); }">
                <input type="checkbox" id="switch_ceroacinco" <?=($ceroacinco == '1' ? 'checked' : '')?> onchange="pafUpdateSwitch('ceroacinco', this.checked)">
                <span class="paf-slider"></span>
              </label>
              <input type="hidden" name="ceroacinco" id="input_ceroacinco" value="<?=($ceroacinco == '1' ? '1' : '0')?>">
            </div>

            <!-- Seis a Nueve -->
            <div class="paf-switch-row" onclick="pafToggleFromRow('seisanueve')">
              <span class="label-text">Seis a Nueve años (6 a 9 años)</span>
              <label class="paf-switch" onclick="if (event && event.stopPropagation) { event.stopPropagation(); }">
                <input type="checkbox" id="switch_seisanueve" <?=($seisanueve == '1' ? 'checked' : '')?> onchange="pafUpdateSwitch('seisanueve', this.checked)">
                <span class="paf-slider"></span>
              </label>
              <input type="hidden" name="seisanueve" id="input_seisanueve" value="<?=($seisanueve == '1' ? '1' : '0')?>">
            </div>

            <!-- Adolescente -->
            <div class="paf-switch-row" onclick="pafToggleFromRow('adol')">
              <span class="label-text">Adolescente (10 a 19 años)</span>
              <label class="paf-switch" onclick="if (event && event.stopPropagation) { event.stopPropagation(); }">
                <input type="checkbox" id="switch_adol" <?=($adol == '1' ? 'checked' : '')?> onchange="pafUpdateSwitch('adol', this.checked)">
                <span class="paf-slider"></span>
              </label>
              <input type="hidden" name="adol" id="input_adol" value="<?=($adol == '1' ? '1' : '0')?>">
            </div>

            <!-- Adulto -->
            <div class="paf-switch-row" onclick="pafToggleFromRow('adulto')">
              <span class="label-text">Adulto (&ge; 20 años)</span>
              <label class="paf-switch" onclick="if (event && event.stopPropagation) { event.stopPropagation(); }">
                <input type="checkbox" id="switch_adulto" <?=($adulto == '1' ? 'checked' : '')?> onchange="pafUpdateSwitch('adulto', this.checked)">
                <span class="paf-slider"></span>
              </label>
              <input type="hidden" name="adulto" id="input_adulto" value="<?=($adulto == '1' ? '1' : '0')?>">
            </div>

          </div>

          <!-- COLUMNA DERECHA: SEXO Y REGLAS DE FACTURACIÓN -->
          <div class="paf-switch-card">
            <div class="paf-switch-card-title">
              <span>🩺</span> Sexo y Reglas de Facturación
            </div>

            <!-- Femenino -->
            <div class="paf-switch-row" onclick="pafToggleFromRow('f')">
              <span class="label-text">Femenino</span>
              <label class="paf-switch" onclick="if (event && event.stopPropagation) { event.stopPropagation(); }">
                <input type="checkbox" id="switch_f" <?=($f == '1' ? 'checked' : '')?> onchange="pafUpdateSwitch('f', this.checked)">
                <span class="paf-slider"></span>
              </label>
              <input type="hidden" name="f" id="input_f" value="<?=($f == '1' ? '1' : '0')?>">
            </div>

            <!-- Masculino -->
            <div class="paf-switch-row" onclick="pafToggleFromRow('m')">
              <span class="label-text">Masculino</span>
              <label class="paf-switch" onclick="if (event && event.stopPropagation) { event.stopPropagation(); }">
                <input type="checkbox" id="switch_m" <?=($m == '1' ? 'checked' : '')?> onchange="pafUpdateSwitch('m', this.checked)">
                <span class="paf-slider"></span>
              </label>
              <input type="hidden" name="m" id="input_m" value="<?=($m == '1' ? '1' : '0')?>">
            </div>

            <!-- Activo en el sistema -->
            <div class="paf-switch-row" onclick="pafToggleFromRow('activo')">
              <span class="label-text"><b>Activo en el Sistema</b></span>
              <label class="paf-switch" onclick="if (event && event.stopPropagation) { event.stopPropagation(); }">
                <input type="checkbox" id="switch_activo" <?=(strtolower($activo) != 'f' && $activo != '0' ? 'checked' : '')?> onchange="pafUpdateSwitch('activo', this.checked)">
                <span class="paf-slider"></span>
              </label>
              <input type="hidden" name="activo" id="input_activo" value="<?=(strtolower($activo) != 'f' && $activo != '0' ? 't' : 'f')?>">
            </div>

            <!-- Priorizada -->
            <div class="paf-switch-row" onclick="pafToggleFromRow('priori')">
              <span class="label-text">Alta Complejidad / Priorizada</span>
              <label class="paf-switch" onclick="if (event && event.stopPropagation) { event.stopPropagation(); }">
                <input type="checkbox" id="switch_priori" <?=($priori == '1' ? 'checked' : '')?> onchange="pafUpdateSwitch('priori', this.checked)">
                <span class="paf-slider"></span>
              </label>
              <input type="hidden" name="priori" id="input_priori" value="<?=($priori == '1' ? '1' : '0')?>">
            </div>

            <!-- Catastrófica -->
            <div class="paf-switch-row" onclick="pafToggleFromRow('catas')">
              <span class="label-text">Práctica Catastrófica</span>
              <label class="paf-switch" onclick="if (event && event.stopPropagation) { event.stopPropagation(); }">
                <input type="checkbox" id="switch_catas" <?=($catas == '1' ? 'checked' : '')?> onchange="pafUpdateSwitch('catas', this.checked)">
                <span class="paf-slider"></span>
              </label>
              <input type="hidden" name="catas" id="input_catas" value="<?=($catas == '1' ? '1' : '0')?>">
            </div>

            <!-- Cobertura Efectiva Básica (CEB) -->
            <div class="paf-switch-row" onclick="pafToggleFromRow('ceb')">
              <span class="label-text">Cobertura Efectiva Básica (CEB)</span>
              <label class="paf-switch" onclick="if (event && event.stopPropagation) { event.stopPropagation(); }">
                <input type="checkbox" id="switch_ceb" <?=($ceb == '1' || strtoupper($ceb) == 'S' ? 'checked' : '')?> onchange="pafUpdateSwitch('ceb', this.checked)">
                <span class="paf-slider"></span>
              </label>
              <input type="hidden" name="ceb" id="input_ceb" value="<?=($ceb == '1' || strtoupper($ceb) == 'S' ? 'S' : 'N')?>">
            </div>

          </div>

        </div>

        <!-- BOTÓN DE GUARDADO PRINCIPAL -->
        <div style="margin-top: 24px; text-align: center;">
          <?php if ($id_nomenclador): ?>
            <button type="submit" name="guardar" value="Guardar Parametros" class="paf-btn paf-btn-primary" style="padding: 10px 28px; font-size: 15px;">
              <span>💾</span> Guardar Parámetros de la Prestación
            </button>
          <?php else: ?>
            <button type="submit" name="guardar" value="Guardar Nueva Prestacion" class="paf-btn paf-btn-create" style="padding: 10px 28px; font-size: 15px;">
              <span>✨</span> Crear Nueva Prestación
            </button>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </form>

  <!-- TARJETA 3: VINCULAR DIAGNÓSTICOS (SÓLO SI EXISTE LA PRESTACIÓN - LÓGICA NATIVA SL) -->
  <?php if ($id_nomenclador): ?>
    <div class="paf-card">
      <div class="paf-card-header">
        <h3><span>🩺</span> Vincular Diagnósticos a la Prestación</h3>
        <span style="font-size: 12.5px; color: #64748b;">Prestación: <b><?=$codigo?></b> - <?=$grupo?></span>
      </div>
      <div class="paf-card-body">
        
        <!-- FORMULARIO DE AGREGAR DIAGNÓSTICO -->
        <form name="form_diag" id="form_diag" action="param_admin_fin.php" method="POST" onsubmit="return pafValidarDiagnostico()">
          <input type="hidden" name="id_nomenclador" value="<?=$id_nomenclador?>">
          <input type="hidden" name="id_nomenclador_detalle" value="<?=$id_nomenclador_detalle?>">
          <input type="hidden" name="accion_form" value="guardar_diagnostico">

          <div class="paf-diag-search-box">
            <span style="font-size: 14px; font-weight: 600; color: #334155;">Seleccionar Diagnóstico:</span>
            <select name="diag" id="select_diag" class="paf-select-diag"
                    onkeypress="buscar_combo(this);"
                    onblur="borrar_buffer();"
                    onchange="borrar_buffer();">
              <option value="-1">-- Seleccione un Diagnóstico del Catálogo --</option>
              <?php
              $sql_pat = "SELECT codigo, descripcion FROM nomenclador.patologias_frecuentes ORDER BY codigo";
              $res_pat = sql($sql_pat) or fin_pagina();
              while ($res_pat && !$res_pat->EOF) {
                  $c_diag = trim($res_pat->fields['codigo']);
                  $d_diag = $res_pat->fields['descripcion'];
              ?>
                <option value="<?=$c_diag?>"><?=$c_diag?> - <?=$d_diag?></option>
              <?php
                  $res_pat->MoveNext();
              }
              ?>
            </select>
            <button type="submit" name="guardar" value="Guardar Diagnostico" class="paf-btn paf-btn-primary">
              <span>➕</span> Guardar Diagnóstico
            </button>
          </div>
        </form>

        <!-- TABLA DE DIAGNÓSTICOS VINCULADOS -->
        <?php
        $query_comp = "SELECT facturacion.parametro_nomen.codigo, id_parametro_nomen, usuario, patologias_frecuentes.descripcion 
                       FROM facturacion.parametro_nomen
                       LEFT JOIN nomenclador.patologias ON trim(facturacion.parametro_nomen.codigo) = trim(nomenclador.patologias.codigo)
                       INNER JOIN nomenclador.patologias_frecuentes ON trim(facturacion.parametro_nomen.codigo) = trim(nomenclador.patologias_frecuentes.codigo)
                       WHERE id_nomenclador = $id_nomenclador
                       ORDER BY facturacion.parametro_nomen.codigo";
        $res_comp = sql($query_comp, "Error al traer los comprobantes") or fin_pagina();
        $total_diag = $res_comp ? $res_comp->RecordCount() : 0;
        ?>

        <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
          <b style="font-size: 14px; color: #1e293b;">Diagnósticos Vinculados (<?=$total_diag?>)</b>
          <span style="font-size: 12.5px; color: #64748b;">Haga clic en el botón de eliminar para desvincular un diagnóstico</span>
        </div>

        <table class="paf-table">
          <thead>
            <tr>
              <th width="8%">ID</th>
              <th width="15%">Código</th>
              <th width="47%">Descripción de la Patología</th>
              <th width="20%">Usuario de Carga</th>
              <th width="10%" style="text-align: center;">Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($total_diag == 0): ?>
              <tr>
                <td colspan="5" style="text-align: center; padding: 24px; color: #64748b;">
                  <span>ℹ️ No existen diagnósticos vinculados a esta prestación. Utilice el selector superior para asociar uno.</span>
                </td>
              </tr>
            <?php else: ?>
              <?php
              $res_comp->MoveFirst();
              while (!$res_comp->EOF) {
                  $id_pn = $res_comp->fields['id_parametro_nomen'];
                  $cod_diag = trim($res_comp->fields['codigo']);
                  $desc_diag = $res_comp->fields['descripcion'];
                  $usr = $res_comp->fields['usuario'];

                  $ref_del = encode_link("param_admin_fin.php", array(
                      "id_nomenclador" => $id_nomenclador,
                      "id_nomenclador_detalle" => $id_nomenclador_detalle,
                      "id_parametro_nomen" => $id_pn,
                      "borra_efec" => "borra_efec"
                  ));
              ?>
                <tr>
                  <td><span class="paf-badge-id">#<?=$id_pn?></span></td>
                  <td><span class="paf-badge-code"><?=$cod_diag?></span></td>
                  <td style="font-weight: 500;"><?=$desc_diag?></td>
                  <td style="font-size: 12px; color: #64748b;"><?=$usr?></td>
                  <td style="text-align: center;">
                    <a href="<?=$ref_del?>" onclick="return confirm('¿Seguro que desea eliminar el Diagnóstico <?=$cod_diag?> de esta prestación?')" class="paf-btn paf-btn-danger">
                      <span>🗑️</span> Borrar
                    </a>
                  </td>
                </tr>
              <?php
                  $res_comp->MoveNext();
              }
              ?>
            <?php endif; ?>
          </tbody>
        </table>

      </div>
    </div>
  <?php endif; ?>

  <!-- BOTÓN VOLVER INFERIOR -->
  <div style="text-align: center; margin-top: 20px;">
    <button type="button" class="paf-btn paf-btn-secondary" onclick="location.href='<?=$ref_volver?>'" style="padding: 9px 24px;">
      <span>↩️</span> Volver al Listado de Prestaciones
    </button>
  </div>

</div>

<?php echo fin_pagina(); ?>
