<?php
require_once ("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
if (isset($_GET['id_smiafiliados']) && !$id_smiafiliados) $id_smiafiliados = $_GET['id_smiafiliados'];
if (isset($_GET['clavebeneficiario']) && !$clavebeneficiario) $clavebeneficiario = $_GET['clavebeneficiario'];
if (isset($_GET['pagina_listado']) && !$pagina_listado) $pagina_listado = $_GET['pagina_listado'];
if (isset($_GET['pagina_viene']) && !$pagina_viene) $pagina_viene = $_GET['pagina_viene'];
if (isset($_GET['entidad_alta']) && !$entidad_alta) $entidad_alta = $_GET['entidad_alta'];
if (isset($_GET['marcar']) && !$marcar) $marcar = $_GET['marcar'];
if (isset($_GET['id_comprobante']) && !$id_comprobante) $id_comprobante = $_GET['id_comprobante'];

$usuario1=$_ses_user['id'];
if ($entidad_alta=='nu'){//carga de prestacion a paciente NO PLAN NACER
	$link=encode_link("comprobante_admin_total.php", array("id"=>$id_smiafiliados,"pagina_viene"=>"comprobante_admin.php","pagina_listado"=>$pagina_listado,"entidad_alta"=>$entidad_alta));?>
	<script>location.href='<?=$link?>' </script>
<?exit();
}

$sql_parametro="select valor from nacer.parametros where parametro='control_historicos'";
$res_parametro=sql($sql_parametro, "Error") or fin_pagina();
$res_parametro=$res_parametro->fields['valor'];

if($marcar=="True"){
	$db->StartTrans();
	$query="update facturacion.comprobante set
             marca=1
             where id_comprobante=$id_comprobante";

    sql($query, "Error al marcar el comprobante") or fin_pagina();
    $accion="Se marcó el Comprobante Número: $id_comprobante como anulado";    
    /*cargo los log*/ 
    $usuario=$_ses_user['name'];
    $fecha_carga=date("Y-m-d H:i:s");
	$log="insert into facturacion.log_comprobante 
		   (id_comprobante, fecha, tipo, descripcion, usuario) 
	values ($id_comprobante, '$fecha_carga','Comprobante Anulado','Nro. Comprobante $id_comprobante', '$usuario')";
	sql($log) or fin_pagina();
	 
    $db->CompleteTrans();   
}
function suma_fechas($fecha,$ndias){
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
      	list($dia,$mes,$anio)=split("/", $fecha);
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
        list($dia,$mes,$anio)=split("-",$fecha);
      $nueva = mktime(0,0,0, $mes,$dia,$anio) + $ndias * 24 * 60 * 60;
      $nuevafecha=date("d-m-Y",$nueva);
      return ($nuevafecha);  
}

if (($_POST['guardar']=="Guardar Comprobante")||($_POST['guardar']=="Guardar Comprobante y Facturar")){
	if ($_POST['alta_comp'] == "alta_comp"){
		$alta_comp='SI';
	}
	else{
		$alta_comp='';
	}

  if ($_POST['flap'] == "flap"){
    $flap='s';
  }
  else{
    $flap='';
  }

  if ($_POST['covid'] == "covid"){
    $covid='s';
  }
  else{
    $covid='';
  }

	if ($pagina_listado=='listado_beneficiarios_hist.php') {
		$fecha_comprobante_hist=substr(eregi_replace('-','',Fecha_db($_POST['fecha_comprobante'])),0,6).'01';
		$periodo_comprobante_hist=eregi_replace('/','',$_POST['periodo']).'01';
		if ($res_parametro=='por_periodo')$sql_hist="select count(id_smiafiliados) as cant from nacer.historicotemp
											where clavebeneficiario = '$clavebeneficiario' and periodo = '$periodo_comprobante_hist' and activo='S'";
		if ($res_parametro=='por_fecha')$sql_hist="select count(id_smiafiliados) as cant from nacer.historicotemp
											where clavebeneficiario = '$clavebeneficiario' and periodo = '$fecha_comprobante_hist' and activo='S'";
		$result_hist=sql($sql_hist,'No puedo ejecutar la funcion');
		$result_hist=$result_hist->fields['cant'];
		if ($result_hist>'0') $comienza_validacion='S';
		else{
			($res_parametro=='por_periodo')?$accion="NO esta ACTIVO en el PERIODO de la Prestacion.":$accion="NO esta ACTIVO en la FECHA de la Prestacion.";
		}
	}
	else $comienza_validacion='S';
	
	if ($comienza_validacion=='S'){
		$fecha_carga=date("Y-m-d H:i:s");
		$cuie=$_POST['efector'];
		$nom_medico=$_POST['nom_medico'];
		$fecha_comprobante=$_POST['fecha_comprobante'];
		$comentario=$_POST['comentario'];
		$fecha_comprobante=Fecha_db($fecha_comprobante);
		
		$query="select * from facturacion.comprobante
				where id_smiafiliados=$id_smiafiliados and fecha_comprobante='$fecha_comprobante'";
	    $val=sql($query, "Error en consulta de validacion") or fin_pagina();
	    $val_id_comp=$val->fields['id_comprobante'];
	    if ($val->RecordCount()==0)$accion1="";
	    else $accion1="Ya se generó el comprobante Número $val_id_comp para esta persona el mismo día.";  
	
	    $query="select fechainscripcion,grupopoblacional from nacer.smiafiliados
				where id_smiafiliados=$id_smiafiliados";
	    $val=sql($query, "Error en consulta de validacion fecha Inscripción") or fin_pagina();
	    $fecha_inscripcion=$val->fields['fechainscripcion']; 
	    $grupopoblacional=$val->fields['grupopoblacional'];   
	        
	    $fecha_inscripcion_comp=Fecha_db(Fecha($fecha_inscripcion));
	    $fecha_comprobante_comp=$fecha_comprobante; 
	    
	    $query="select nomenclador_detalle.* from facturacion.nomenclador_detalle
	    		left join nacer.efe_conv using (id_nomenclador_detalle)
	    		where efe_conv.cuie='$cuie'";
	    $nomenclador_query=sql($query, "Error en consulta de trer nomenclador") or fin_pagina();
	    $fecha_desde_nom=$nomenclador_query->fields['fecha_desde'];
	    $fecha_hasta_nom=$nomenclador_query->fields['fecha_hasta'];
	            
		if ($fecha_comprobante_comp<$fecha_inscripcion_comp) $accion1="ERROR: la Fecha de la Prestación es MENOR a la fecha de Inscripción del Beneficiario.";	    	
	    else 
	    {
	      $db->StartTrans();
			$q="select nextval('comprobante_id_comprobante_seq') as id_comprobante";
		    $id_comprobante=sql($q) or fin_pagina();
		    $id_comprobante=$id_comprobante->fields['id_comprobante'];	
		    
		    if ($flag_inactivo=="S")$activo='S';
		    
		    $periodo= str_replace("-","/",substr($fecha_comprobante,0,7));
		    		    
		    $query="insert into facturacion.comprobante
		             (id_comprobante, cuie, nombre_medico, fecha_comprobante, clavebeneficiario, id_smiafiliados, fecha_carga,periodo,comentario,id_servicio,activo,alta_comp,flap,grupo_etareo,covid)
		             values
		             ($id_comprobante,'$cuie','$nom_medico','$fecha_comprobante','$clavebeneficiario', $id_smiafiliados,'$fecha_carga','$periodo','$comentario','$servicio','$activo','$alta_comp','$flap','$grupopoblacional','$covid')";	
		    sql($query, "Error al insertar el comprobante") or fin_pagina();	    
		    $accion="Se guardó el Comprobante Número: $id_comprobante.";	    /*cargo los log*/ 
		    $usuario=$_ses_user['name'];
			$log="insert into facturacion.log_comprobante 
				   (id_comprobante, fecha, tipo, descripcion, usuario) 
			values ($id_comprobante, '$fecha_carga','Nuevo Comprobante','Nro. Comprobante $id_comprobante', '$usuario')";
			sql($log) or fin_pagina();		 
		    $db->CompleteTrans(); 
		    if ($_POST['guardar']=="Guardar Comprobante y Facturar"){
		    	$ref = encode_link("prestacion_admin.php",array("id_smiafiliados"=>$id_smiafiliados,"id_comprobante"=>$id_comprobante,"estado"=>"","pagina_listado"=>$pagina_listado,"pagina_viene"=>"comprobante_admin.php","entidad_alta"=>$entidad_alta));
		    	echo "<SCRIPT>window.location='$ref';</SCRIPT>"; 
		    	exit();
		    }
	    }  
    }       
}

// Fallback de clavebeneficiario si no fue provista
if (empty($clavebeneficiario) && !empty($id_smiafiliados)) {
    $q_clave = "select clavebeneficiario from nacer.smiafiliados where id_smiafiliados = $id_smiafiliados";
    $res_c = sql($q_clave);
    if ($res_c && !$res_c->EOF) {
        $clavebeneficiario = $res_c->fields['clavebeneficiario'];
    }
}

// Consulta de beneficiario en San Luis por id_smiafiliados
$sql="select * from nacer.smiafiliados
	 left join nacer.smitiposcategorias on (afitipocategoria=codcategoria)
	 left join nacer.efe_conv on (cuieefectorasignado=cuie)
	 where id_smiafiliados=$id_smiafiliados";
$res_comprobante_afi=sql($sql, "Error al traer los Comprobantes") or fin_pagina();

$afiapellido=$res_comprobante_afi->fields['afiapellido'];
$afinombre=$res_comprobante_afi->fields['afinombre'];
$afidni=$res_comprobante_afi->fields['afidni'];
$descripcion=$res_comprobante_afi->fields['descripcion'];
$nombre=$res_comprobante_afi->fields['nombre'];
$afifechanac=$res_comprobante_afi->fields['afifechanac'];
$activo=$res_comprobante_afi->fields['activo'];
$afisexo=$res_comprobante_afi->fields['afisexo'];

// Pre-carga en memoria de descripciones de grupos de prestación y patologías (acelera drásticamente la carga de la página)
$map_grupo_prestacion = array();
$res_gp = sql("SELECT codigo, categoria FROM nomenclador.grupo_prestacion");
if ($res_gp) {
    while (!$res_gp->EOF) {
        $map_grupo_prestacion[$res_gp->fields['codigo']] = $res_gp->fields['categoria'];
        $res_gp->movenext();
    }
}
$map_patologias = array();
$res_pat = sql("SELECT distinct codigo, descripcion FROM nomenclador.patologias_frecuentes");
if ($res_pat) {
    while (!$res_pat->EOF) {
        $map_patologias[$res_pat->fields['codigo']] = $res_pat->fields['descripcion'];
        $res_pat->movenext();
    }
}

// Cálculo de edad
$edad_beneficiario = "";
if (!empty($afifechanac)) {
    $fn_ts = strtotime($afifechanac);
    if ($fn_ts !== false) {
        $edad_anos = date("Y") - date("Y", $fn_ts);
        if (date("md") < date("md", $fn_ts)) $edad_anos--;
        $edad_beneficiario = $edad_anos . " años";
    }
}

// Retención de campos ingresados durante recarga del formulario
$nom_medico_val = isset($_POST['nom_medico']) ? htmlspecialchars($_POST['nom_medico'], ENT_QUOTES) : '';
$comentario_val = isset($_POST['comentario']) ? htmlspecialchars($_POST['comentario'], ENT_QUOTES) : '';
$fecha_comprobante_val = isset($_POST['fecha_comprobante']) ? $_POST['fecha_comprobante'] : date("d/m/Y");

echo $html_header;
?>
<style>
/* Sistema de Diseño PAF para comprobante_admin.php (San Luis) */
.paf-wrapper {
    max-width: 1240px;
    margin: 12px auto 40px auto;
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
    color: #1e293b;
}

/* Cabecera Principal */
.paf-main-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: #ffffff;
    border-radius: 12px 12px 0 0;
    padding: 14px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}
.paf-main-title {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.paf-header-badge {
    background: rgba(255,255,255,0.2);
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* Ficha del Beneficiario en un solo renglón compacto */
.paf-patient-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 9px 16px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.paf-bar-item {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
}
.paf-bar-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
}
.paf-bar-val {
    font-weight: 600;
    color: #1e293b;
}
.paf-badge-dni {
    font-family: 'Consolas', monospace;
    background: #e0e7ff;
    color: #3730a3;
    padding: 2px 7px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 700;
}
.paf-badge-activo {
    background: #dcfce7;
    color: #15803d;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
}
.paf-badge-inactivo {
    background: #fee2e2;
    color: #991b1b;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
}

/* Tarjetas */
.paf-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 16px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}
.paf-card-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 10px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 700;
    font-size: 13.5px;
    color: #1e293b;
}
.paf-card-body {
    padding: 16px 20px;
}

/* Formulario Grid */
.paf-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 14px;
}
.paf-form-group {
    margin-bottom: 4px;
}
.paf-form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 4px;
}
.paf-form-control {
    width: 100%;
    padding: 7px 11px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 12px;
    color: #1e293b;
    background-color: #ffffff;
    outline: none;
    box-sizing: border-box;
    transition: all 0.15s ease;
}
.paf-form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
.paf-form-control option {
    font-size: 12px;
}
.paf-checkbox-group {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
}
.paf-checkbox-group input[type="checkbox"] {
    cursor: pointer;
    width: 16px;
    height: 16px;
}

/* Alertas */
.paf-alert {
    border-radius: 8px;
    padding: 12px 18px;
    margin-bottom: 14px;
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
.paf-alert-warning {
    background-color: #fffbeb;
    border: 1px solid #fde68a;
    color: #92400e;
}
.paf-alert-danger {
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}

/* Botones de acción */
.paf-action-buttons {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
    flex-wrap: wrap;
}
.paf-btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 20px;
    background: #ffffff;
    color: #1e293b;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
}
.paf-btn-secondary:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
}
.paf-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 24px;
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: #ffffff;
    border: none;
    border-radius: 6px;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(22,163,74,0.3);
    transition: all 0.15s ease;
}
.paf-btn-primary:hover {
    background: linear-gradient(135deg, #15803d 0%, #166534 100%);
    box-shadow: 0 4px 6px rgba(22,163,74,0.4);
    transform: translateY(-1px);
}
.paf-btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #ffffff;
    color: #334155;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s ease;
}
.paf-btn-back:hover {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #94a3b8;
}

/* Tabla de Comprobantes */
.paf-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
}
.paf-table th {
    background-color: #1e293b;
    color: #ffffff;
    font-weight: 600;
    padding: 10px 12px;
    text-align: left;
    border-right: 1px solid #334155;
    white-space: nowrap;
}
.paf-table td {
    padding: 9px 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    vertical-align: middle;
}
.paf-table tr.paf-row-clickable {
    cursor: pointer;
    transition: background-color 0.15s ease;
}
.paf-table tr.paf-row-clickable:hover {
    background-color: #f8fafc;
}

/* Badges de Estado */
.paf-badge-pending {
    background-color: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
    display: inline-block;
}
.paf-badge-billed {
    background-color: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
    display: inline-block;
}
.paf-badge-canceled {
    background-color: #f1f5f9;
    color: #64748b;
    border: 1px solid #cbd5e1;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
    display: inline-block;
}

/* Tags de Efector */
.paf-tag-alta-comp {
    background-color: #e0e7ff;
    color: #3730a3;
    padding: 1px 6px;
    border-radius: 4px;
    font-size: 10.5px;
    font-weight: 700;
    margin-left: 6px;
}
.paf-tag-flap {
    background-color: #dcfce7;
    color: #166534;
    padding: 1px 6px;
    border-radius: 4px;
    font-size: 10.5px;
    font-weight: 700;
    margin-left: 6px;
}
.paf-tag-covid {
    background-color: #fce7f3;
    color: #9d174d;
    padding: 1px 6px;
    border-radius: 4px;
    font-size: 10.5px;
    font-weight: 700;
    margin-left: 6px;
}

/* Acciones en tabla */
.paf-btn-action-view {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 5px;
    font-size: 11.5px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
}
.paf-btn-action-view:hover {
    background: #dbeafe;
    color: #1e40af;
    border-color: #93c5fd;
}
.paf-btn-action-cancel {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 3px 8px;
    background: #fff1f2;
    color: #be123c;
    border: 1px solid #fecdd3;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
}
.paf-btn-action-cancel:hover {
    background: #ffe4e6;
    border-color: #fda4af;
}

/* Subtabla de Prestaciones (Acordeón) */
.paf-subtable-container {
    background-color: #f8fafc;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    margin: 6px 0;
    padding: 10px;
}
.paf-subtable {
    width: 100%;
    border-collapse: collapse;
    font-size: 11.5px;
    background: #ffffff;
}
.paf-subtable th {
    background-color: #e2e8f0;
    color: #334155;
    font-weight: 600;
    padding: 6px 10px;
    text-align: left;
    border-bottom: 1px solid #cbd5e1;
}
.paf-subtable td {
    padding: 6px 10px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
}
.paf-code-pill {
    display: inline-block;
    padding: 1px 6px;
    background-color: #f1f5f9;
    border-radius: 4px;
    font-weight: 700;
    font-family: 'Consolas', monospace;
    font-size: 11.5px;
    color: #0f172a;
    border: 1px solid #cbd5e1;
}

/* Leyenda */
.paf-legend-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 16px;
    align-items: center;
    padding: 10px 16px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 12px;
}
.paf-legend-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #475569;
}

/* Logs */
.paf-log-container {
    max-height: 160px;
    overflow-y: auto;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    background: #ffffff;
}
</style>

<script>
function mayor_fecha(fecha, fecha2){
    var xMes=fecha.substring(3, 5);
    var xDia=fecha.substring(0, 2);
    var xAnio=fecha.substring(6,10);
    var yMes=fecha2.substring(3, 5);
    var yDia=fecha2.substring(0, 2);
    var yAnio=fecha2.substring(6,10);
    if (xAnio > yAnio){
        return(true);
    }else{
        if (xAnio == yAnio){
            if (xMes > yMes){
                return(true);
            }
            if (xMes == yMes){
                if (xDia > yDia){
                    return(true);
                }else{
                    return(false);
                }
            }else{
                return(false);
            }
        }else{
            return(false);
        }
    }
}

function control_nuevos() {
    var f_comp = document.getElementById('fecha_comprobante') ? document.getElementById('fecha_comprobante').value : (document.all.fecha_comprobante ? document.all.fecha_comprobante.value : '');
    var f_ahora = document.all.fecha_ahora_js ? document.all.fecha_ahora_js.value : '';
    if (mayor_fecha(f_comp, f_ahora)){
        alert('Debe Seleccionar un FECHA MENOR o IGUAL a la FECHA de HOY');
        return false;
    } 
 
    var ef = document.all.efector ? document.all.efector.value : '';
    if (ef == "-1" || ef == ""){
        alert('Debe Seleccionar un EFECTOR');
        return false;
    }
    <?if ($pagina_listado=='listado_beneficiarios_hist.php'){?>
    if (document.all.periodo && document.all.periodo.value == "-1"){
        alert('Debe Seleccionar un PERIODO');
        return false;
    }
    <?}?> 
    var srv = document.all.servicio ? document.all.servicio.value : '';
    if (srv == "-1" || srv == ""){
        alert('Debe Seleccionar un Servicio');
        return false;
    }
    if (confirm('¿Está Seguro que Desea Agregar Comprobante?')) return true;
    else return false;	
}

var img_ext='<?=$img_ext='../../imagenes/rigth2.gif' ?>';
var img_cont='<?=$img_cont='../../imagenes/down2.gif' ?>';
function muestra_tabla(obj_tabla,nro){
    var oimg = document.getElementById('imagen_' + nro) || (document.all ? eval("document.all.imagen_" + nro) : null);
    if (!obj_tabla) return;
    if (obj_tabla.style.display == 'none'){
        obj_tabla.style.display = '';
        if (oimg) { oimg.show = 0; oimg.src = img_ext; }
    } else {
        obj_tabla.style.display = 'none';
        if (oimg) { oimg.show = 1; oimg.src = img_cont; }
    }
}

function alternar_detalle(id_fila, btn_el) {
    var fila = document.getElementById(id_fila);
    if (!fila) return;
    if (fila.style.display === 'none' || fila.style.display === '') {
        fila.style.display = 'table-row';
        if (btn_el) btn_el.innerHTML = '▲ Ocultar';
    } else {
        fila.style.display = 'none';
        if (btn_el) btn_el.innerHTML = '▼ Detalle';
    }
}

var digitos=10;
var puntero=0;
var buffer=new Array(digitos);
var cadena="";

function borrar_buffer(){
    puntero=0;
    cadena="";
}

function buscar_combo(obj) {
    var letra = String.fromCharCode(event.keyCode);
    if(puntero >= digitos) {
        cadena="";
        puntero=0;
    } else {
        buffer[puntero]=letra;
        cadena=cadena+buffer[puntero];
        puntero++;
        for (var opcombo=1;opcombo < obj.length;opcombo++){
            if(obj[opcombo].text.substr(0,puntero).toLowerCase()==cadena.toLowerCase()){
                obj.selectedIndex=opcombo;
                break;
            }
        }
    }
    event.returnValue = false;
}
</script>

<div class="paf-wrapper">
    <form name='form1' action='comprobante_admin.php' method='POST'>
    <input type="hidden" value="<?=$usuario1?>" name="usuario1">
    <input type="hidden" name="id_smiafiliados" value="<?=$id_smiafiliados?>">
    <input type="hidden" name="clavebeneficiario" value="<?=$clavebeneficiario?>">
    <input type="hidden" name="pagina" value="<?=$pagina?>">
    <input type="hidden" name="pagina_viene" value="<?=$pagina_viene?>">
    <input type="hidden" name="pagina_listado" value="<?=$pagina_listado?>">
    <input type="hidden" name="activo" value="<?=$activo?>">
    <input type="hidden" name="flag_inactivo" value="<?=$flag_inactivo?>">
    <input type="hidden" name="entidad_alta" value="<?=$entidad_alta?>">
    <input type="hidden" name="fecha_ahora_js" value="<?=fecha(date("Y-m-d"));?>">

    <!-- Notificaciones y Alertas -->
    <?if (!empty($accion)){?>
        <div class="paf-alert paf-alert-success">
            <b>Información:</b> <?=$accion?>
        </div>
    <?}?>
    <?if (!empty($accion1)){?>
        <div class="paf-alert paf-alert-danger">
            <b>Atención:</b> <?=$accion1?>
        </div>
    <?}?>

    <!-- Cabecera Principal -->
    <div class="paf-main-header">
        <div class="paf-main-title">
            <span>📑</span> Gestión de Comprobantes de Beneficiario
        </div>
        <div class="paf-header-badge">
            <?if ($pagina_listado=='listado_beneficiarios_hist.php'){?>
                <span style="color:#fecaca;">Verificando HISTÓRICOS</span>
            <?}else{?>
                Sistema SUMAR
            <?}?>
        </div>
    </div>

    <!-- Barra compacta de datos del beneficiario (1 solo renglón) -->
    <div class="paf-patient-bar">
        <div class="paf-bar-item">
            <span class="paf-bar-label">Beneficiario:</span>
            <span class="paf-bar-val" style="font-size:13px; color:#1e3a8a;">
                <b><?=$afiapellido?>, <?=$afinombre?></b>
            </span>
        </div>
        <div class="paf-bar-item">
            <span class="paf-bar-label">DNI:</span>
            <span class="paf-badge-dni"><?=$afidni?></span>
        </div>
        <div class="paf-bar-item">
            <span class="paf-bar-label">Categoría:</span>
            <span class="paf-bar-val"><?=($descripcion ? $descripcion : 'Sin Categoría')?></span>
        </div>
        <div class="paf-bar-item">
            <span class="paf-bar-label">Nacimiento:</span>
            <span class="paf-bar-val"><?=fecha($afifechanac)?> <?=($edad_beneficiario ? "($edad_beneficiario)" : "")?></span>
        </div>
        <div class="paf-bar-item">
            <span class="paf-bar-label">Sexo:</span>
            <span class="paf-bar-val"><?=$afisexo?></span>
        </div>
        <div class="paf-bar-item">
            <span class="paf-bar-label">Efector Asignado:</span>
            <span class="paf-bar-val"><?=utf8_decode($nombre)?></span>
        </div>
        <div class="paf-bar-item">
            <span class="paf-bar-label">Estado:</span>
            <?if ($activo=='S'){?>
                <span class="paf-badge-activo">Activo</span>
            <?}else{?>
                <span class="paf-badge-inactivo">Inactivo</span>
            <?}?>
        </div>
    </div>

    <!-- Tarjeta: Nuevo Comprobante -->
    <div class="paf-card">
        <div class="paf-card-header">
            <span>➕ Cargar Nuevo Comprobante</span>
            <span style="font-size:11.5px; font-weight:normal; color:#64748b;">
                <?if (($pagina_listado=='listado_beneficiarios_hist.php') && ($res_parametro=='por_periodo')) echo "<font color=red>Validando Activo por PERÍODO</font>";?>
                <?if (($pagina_listado=='listado_beneficiarios_hist.php') && ($res_parametro=='por_fecha')) echo "<font color=red>Validando Activo por FECHA</font>";?>
            </span>
        </div>
        <div class="paf-card-body">
            <div class="paf-form-grid">
                <!-- Efector -->
                <div class="paf-form-group">
                    <label class="paf-form-label">Efector:*</label>
                    <select name="efector" class="paf-form-control" onkeypress="buscar_combo(this);" onblur="borrar_buffer();" onchange="borrar_buffer(); document.forms[0].submit();">
                        <option value="-1">Seleccione Efector</option>
                        <?
                        $user_login1=substr($_ses_user['login'],0,6);
                        if (es_cuie($_ses_user['login'])){
                            $sql= "select cuie, nombre, com_gestion from nacer.efe_conv where cuie='$user_login1' order by nombre";	
                        } else {
                            $sql= "select cuie, nombre, com_gestion, per_alta_com, covid from nacer.efe_conv where conv_sumar=true order by nombre";
                        }
                        $res_efectores=sql($sql) or fin_pagina();
                        while (!$res_efectores->EOF){ 
                            $cuie=$res_efectores->fields['cuie'];
                            $nombre_efector=$res_efectores->fields['nombre'];
                        ?>
                            <option value="<?=$cuie;?>" <?php if ($cuie==$efector) echo "selected"?>>
                                <?=$cuie." - ".$nombre_efector?>
                            </option>
                        <?
                            $res_efectores->movenext();
                        }?>
                    </select>
                </div>

                <!-- Servicio -->
                <div class="paf-form-group">
                    <label class="paf-form-label">Servicio:*</label>
                    <select name="servicio" class="paf-form-control" onkeypress="buscar_combo(this);" onblur="borrar_buffer();" onchange="borrar_buffer();">
                        <option value="-1">Seleccione Servicio</option>
                        <? 
                        switch ($efector) { 
                            case "D05035" : 
                                $sql= "select * from facturacion.servicio where id_servicio=42 or id_servicio=1";
                                $res_srv=sql($sql) or fin_pagina(); break;
                            case "D05113" : 
                                $sql= "select * from facturacion.servicio where id_servicio=51 or id_servicio=1";
                                $res_srv=sql($sql) or fin_pagina(); break;
                            default : 
                                $sql= "select * from facturacion.servicio except select * from facturacion.servicio where id_servicio=42 or id_servicio=51 order by descripcion";
                                $res_srv=sql($sql) or fin_pagina(); break;
                        };
                        while (!$res_srv->EOF){ 
                            $id_servicio=$res_srv->fields['id_servicio'];
                            $descripcion_srv=$res_srv->fields['descripcion'];
                        ?>
                            <option <?=($descripcion_srv=="No Corresponde")?"selected":""?> value="<?=$id_servicio;?>">
                                <?=$descripcion_srv?>
                            </option>
                        <?
                            $res_srv->movenext();
                        }?>
                    </select>
                </div>

                <!-- Médico -->
                <div class="paf-form-group">
                    <label class="paf-form-label">Nombre del Médico:</label>
                    <input type="text" name="nom_medico" value="<?=$nom_medico_val?>" class="paf-form-control" placeholder="Dr. / Dra. / Profesional tratante">
                </div>

                <!-- Fecha de Prestación -->
                <div class="paf-form-group">
                    <label class="paf-form-label">Fecha de Prestación:*</label>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <?=cargar_calendario();?>
                        <input type="text" id="fecha_comprobante" name="fecha_comprobante" value="<?=$fecha_comprobante_val;?>" class="paf-form-control" style="width:140px;" readonly>
                        <?=link_calendario("fecha_comprobante");?>
                    </div>
                </div>
            </div>

            <!-- Opciones Especiales (Alta Complejidad, FLAP, COVID) -->
            <?
            $sql="select per_alta_com,adenda_per,fecha_adenda_per,categoria_per 
                  from nacer.efe_conv
                  where cuie='$efector' and per_alta_com='SI'";
            $res_efec_1=sql($sql,"no se pudo ejecutar");

            $dias_de_vida=GetCountDaysBetweenTwoDates($afifechanac, date("Y-m-d"));
            $grupo_etareo_local = '';
            if (($dias_de_vida>=0)&&($dias_de_vida<=365)) $grupo_etareo_local='unAnio';	
            if (($dias_de_vida>=3600)&&($dias_de_vida<=18200)) $grupo_etareo_local='Embarazo';

            $has_alta_comp = ($res_efec_1->recordcount() == 1 && $grupo_etareo_local == 'unAnio');

            $sql_flap="SELECT * from nacer.efe_conv where cuie='$efector' and flap='s'";
            $res_efec_1_flap=sql($sql_flap,"no se pudo ejecutar");
            $has_flap = ($res_efec_1_flap->recordcount() != 0);

            $sql_covid="SELECT * from nacer.efe_conv where cuie='$efector' and covid='SI'";
            $res_covid=sql($sql_covid,"no se pudo ejecutar");
            $has_covid = ($res_covid->recordcount() != 0);

            if ($has_alta_comp || $has_flap || $has_covid) {
            ?>
            <div style="display:flex; gap:14px; flex-wrap:wrap; margin-top:10px;">
                <?if ($has_alta_comp){?>
                    <div class="paf-checkbox-group">
                        <input type="checkbox" id="alta_comp" name="alta_comp" value="alta_comp" <?=(isset($_POST['alta_comp']) && $_POST['alta_comp']=='alta_comp')?'checked':''?>>
                        <label for="alta_comp" style="cursor:pointer;">⚡ Comprobante de Alta Complejidad</label>
                    </div>
                <?}?>
                <?if ($has_flap){?>
                    <div class="paf-checkbox-group">
                        <input type="checkbox" id="flap" name="flap" value="flap" <?=(isset($_POST['flap']) && $_POST['flap']=='flap')?'checked':''?>>
                        <label for="flap" style="cursor:pointer;">🩺 Factura Anomalías Congénitas (FLAP)</label>
                    </div>
                <?}?>
                <?if ($has_covid){?>
                    <div class="paf-checkbox-group">
                        <input type="checkbox" id="covid" name="covid" value="covid" <?=(isset($_POST['covid']) && $_POST['covid']=='covid')?'checked':''?>>
                        <label for="covid" style="cursor:pointer;">🛡️ Factura COVID-19</label>
                    </div>
                <?}?>
            </div>
            <?}?>

            <!-- Comentario -->
            <div class="paf-form-group" style="margin-top:10px;">
                <label class="paf-form-label">Comentario u Observaciones:</label>
                <textarea name="comentario" rows="2" class="paf-form-control" placeholder="Observaciones pertinentes al comprobante..."><?=$comentario_val?></textarea>
            </div>

            <!-- Botones de Acción -->
            <div class="paf-action-buttons">
                <input type="submit" name="guardar" value="Guardar Comprobante" title="Guardar Comprobante y permanecer en esta pantalla" class="paf-btn-secondary" onclick="return control_nuevos()">
                <input type="submit" name="guardar" value="Guardar Comprobante y Facturar" title="Guardar Comprobante e ir inmediatamente a Cargar Prestaciones" class="paf-btn-primary" onclick="return control_nuevos()">
            </div>
        </div>
    </div>

    <!-- Historial de Comprobantes -->
    <?
    // Consulta de comprobantes en San Luis por id_smiafiliados
    $query="SELECT 
      facturacion.comprobante.id_comprobante,
      nacer.efe_conv.nombre,
      facturacion.comprobante.nombre_medico,
      facturacion.comprobante.comentario,
      facturacion.comprobante.fecha_comprobante,
      facturacion.comprobante.id_factura,
      facturacion.comprobante.marca,
      facturacion.comprobante.periodo,
      facturacion.comprobante.alta_comp,
      facturacion.comprobante.flap,
      facturacion.comprobante.covid
    FROM
      facturacion.comprobante
      INNER JOIN nacer.efe_conv ON (facturacion.comprobante.cuie = nacer.efe_conv.cuie)
      where facturacion.comprobante.id_smiafiliados=$id_smiafiliados
      order by comprobante.id_comprobante DESC";
    $res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();
    $total_comprobantes = $res_comprobante->RecordCount();
    ?>
    <div class="paf-card">
        <div class="paf-card-header">
            <div style="display:flex; align-items:center; gap:8px;">
                <span>📋 Comprobantes Registrados</span>
                <span class="paf-badge-dni"><?=$total_comprobantes?></span>
            </div>
            <button type="button" class="paf-btn-secondary" style="font-size:11.5px; padding:4px 10px;" onclick="
                var el = document.getElementById('contenedor_tabla_comprobantes');
                if (el.style.display=='none') { el.style.display=''; this.innerHTML='▲ Ocultar'; }
                else { el.style.display='none'; this.innerHTML='▼ Mostrar'; }
            ">▲ Ocultar</button>
        </div>

        <div id="contenedor_tabla_comprobantes" style="overflow-x:auto;">
            <?if ($total_comprobantes == 0){?>
                <div style="padding:24px; text-align:center; color:#64748b; font-size:13.5px;">
                    No existen comprobantes registrados para este beneficiario.
                </div>
            <?}else{?>
                <table class="paf-table">
                    <thead>
                        <tr>
                            <th style="width:40px; text-align:center;">Detalle</th>
                            <th style="width:130px;">Nro. Comprobante</th>
                            <th>Efector</th>
                            <th>Médico</th>
                            <th>Comentario</th>
                            <th style="width:130px;">Fecha / Edad</th>
                            <th style="width:90px; text-align:center;">Prestaciones</th>
                            <th style="width:80px; text-align:center;">Período</th>
                            <th style="width:150px; text-align:center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?
                    $res_comprobante->movefirst();
                    while (!$res_comprobante->EOF) {
                        $id_comp_actual = $res_comprobante->fields['id_comprobante'];
                        $id_factura_actual = $res_comprobante->fields['id_factura'];
                        $marca_actual = $res_comprobante->fields['marca'];
                        
                        $is_facturado = (!empty($id_factura_actual));
                        $is_anulado = ($marca_actual == 1);
                        $is_pendiente = (!$is_facturado && !$is_anulado);
                        
                        $ref_elegir = "";
                        $onclick_elegir = "";
                        $onclick_marcar = "";
                        
                        if ($is_pendiente) {
                            $ref_elegir = encode_link("prestacion_admin.php", array(
                                "id_smiafiliados" => $id_smiafiliados,
                                "id_comprobante" => $id_comp_actual,
                                "pagina_listado" => $pagina_listado,
                                "pagina_viene" => "comprobante_admin.php",
                                "estado" => $id_factura_actual,
                                "entidad_alta" => $entidad_alta
                            ));
                            $onclick_elegir = "location.href='$ref_elegir';";
                            
                            $ref_marcar = encode_link("comprobante_admin.php", array(
                                "id_comprobante" => $id_comp_actual,
                                "marcar" => "True",
                                "id_smiafiliados" => $id_smiafiliados,
                                "clavebeneficiario" => $clavebeneficiario,
                                "entidad_alta" => $entidad_alta
                            ));
                            $onclick_marcar = "if (confirm('¿Está seguro que desea ANULAR el Comprobante #$id_comp_actual?')) { location.href='$ref_marcar'; } return false;";
                        }

                        // Consulta de prestaciones del comprobante
                        $sql_items = "select prestacion.*, nomenclador.*, t1.codigo as cod_diag, t1.descripcion as desc_diag
                                      from facturacion.prestacion 
                                      left join facturacion.nomenclador using (id_nomenclador)	
                                      LEFT JOIN (select distinct codigo, descripcion from nomenclador.patologias_frecuentes) as t1 
                                      ON (prestacion.diagnostico=t1.codigo)
                                      where id_comprobante=$id_comp_actual order by id_prestacion DESC";
                        $result_items = sql($sql_items) or fin_pagina();

                        $sql_items1 = "select * from nomenclador.prestaciones_n_op where id_comprobante=$id_comp_actual order by id_prestaciones_n_op DESC";
                        $result_items1 = sql($sql_items1) or fin_pagina();

                        $cant_items = $result_items->RecordCount();
                        $cant_items1 = $result_items1->RecordCount();
                        $cant_total_pres = $cant_items + $cant_items1;
                        
                        $id_detalle_fila = "detalle_comp_" . $id_comp_actual;
                        
                        // Edad al momento del comprobante
                        $edad_comp = "";
                        if (!empty($res_comprobante->fields['fecha_comprobante']) && !empty($afifechanac)) {
                            $ts_comp = strtotime($res_comprobante->fields['fecha_comprobante']);
                            $ts_nac = strtotime($afifechanac);
                            if ($ts_comp && $ts_nac) {
                                $edad_comp = date("Y", $ts_comp) - date("Y", $ts_nac);
                                if (date("md", $ts_comp) < date("md", $ts_nac)) $edad_comp--;
                                $edad_comp = " (Edad: $edad_comp)";
                            }
                        }
                    ?>
                        <tr class="<?if ($is_pendiente) echo 'paf-row-clickable';?>">
                            <!-- Toggle acordeón -->
                            <td style="text-align:center;">
                                <?if ($cant_total_pres > 0){?>
                                    <button type="button" class="paf-btn-secondary" style="font-size:11px; padding:2px 6px;" onclick="alternar_detalle('<?=$id_detalle_fila?>', this);" title="Ver u ocultar prestaciones de este comprobante">
                                        ▼ Detalle
                                    </button>
                                <?}else{?>
                                    <span style="color:#94a3b8; font-size:11px;">-</span>
                                <?}?>
                            </td>

                            <!-- Nro Comprobante y Estado -->
                            <td onclick="<?=$onclick_elegir?>">
                                <div style="display:flex; flex-direction:column; gap:2px;">
                                    <span style="font-family:'Consolas',monospace; font-weight:700; font-size:13px; color:#1e3a8a;">
                                        #<?=$id_comp_actual?>
                                    </span>
                                    <?if ($is_facturado){?>
                                        <span class="paf-badge-billed">Fac #<?=$id_factura_actual?></span>
                                    <?}elseif ($is_anulado){?>
                                        <span class="paf-badge-canceled">Anulado</span>
                                    <?}else{?>
                                        <span class="paf-badge-pending">Sin Facturar</span>
                                    <?}?>
                                </div>
                            </td>

                            <!-- Efector -->
                            <td onclick="<?=$onclick_elegir?>">
                                <b><?=$res_comprobante->fields['nombre']?></b>
                                <?if ($res_comprobante->fields['alta_comp']=='SI'){?>
                                    <span class="paf-tag-alta-comp" title="Comprobante de Alta Complejidad">Alta Compl.</span>
                                <?}?>
                                <?if ($res_comprobante->fields['flap']=='s'){?>
                                    <span class="paf-tag-flap" title="Factura Anomalías Congénitas (FLAP)">FLAP</span>
                                <?}?>
                                <?if ($res_comprobante->fields['covid']=='s'){?>
                                    <span class="paf-tag-covid" title="Factura COVID-19">COVID-19</span>
                                <?}?>
                            </td>

                            <!-- Médico -->
                            <td onclick="<?=$onclick_elegir?>">
                                <?=($res_comprobante->fields['nombre_medico'] != '' ? $res_comprobante->fields['nombre_medico'] : '<span style="color:#94a3b8;">-</span>')?>
                            </td>

                            <!-- Comentario -->
                            <td onclick="<?=$onclick_elegir?>">
                                <?=($res_comprobante->fields['comentario'] != '' ? $res_comprobante->fields['comentario'] : '<span style="color:#94a3b8;">-</span>')?>
                            </td>

                            <!-- Fecha y Edad -->
                            <td onclick="<?=$onclick_elegir?>">
                                <?=fecha($res_comprobante->fields['fecha_comprobante'])?>
                                <span style="font-size:11px; color:#64748b;"><?=$edad_comp?></span>
                            </td>

                            <!-- Cantidad Prestaciones -->
                            <td onclick="<?=$onclick_elegir?>" style="text-align:center;">
                                <span class="paf-badge-dni" style="background:#f1f5f9; color:#1e293b;">
                                    <?=$cant_total_pres?>
                                </span>
                            </td>

                            <!-- Período -->
                            <td onclick="<?=$onclick_elegir?>" style="text-align:center;">
                                <span style="font-size:11.5px; font-weight:600; color:#475569;">
                                    <?=$res_comprobante->fields['periodo']?>
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td style="text-align:center;">
                                <div style="display:flex; align-items:center; justify-content:center; gap:6px;">
                                    <?if ($is_pendiente){?>
                                        <a href="<?=$ref_elegir?>" class="paf-btn-action-view" title="Cargar o Editar Prestaciones Médicas">
                                            ➕ Cargar
                                        </a>
                                        <button type="button" class="paf-btn-action-cancel" onclick="<?=$onclick_marcar?>" title="Anular este comprobante">
                                            ❌ Anular
                                        </button>
                                    <?}elseif ($is_facturado){?>
                                        <span class="paf-badge-billed">Facturado</span>
                                    <?}elseif ($is_anulado){?>
                                        <span class="paf-badge-canceled">Anulado</span>
                                    <?}?>
                                </div>
                            </td>
                        </tr>

                        <!-- Acordeón de Prestaciones asociadas -->
                        <tr id="<?=$id_detalle_fila?>" style="display:none; background-color:#f8fafc;">
                            <td colspan="9" style="padding:10px 18px;">
                                <div class="paf-subtable-container">
                                    <div style="font-weight:700; font-size:12px; color:#334155; margin-bottom:8px; display:flex; justify-content:space-between;">
                                        <span>Prestaciones facturadas en Comprobante #<?=$id_comp_actual?>:</span>
                                        <?if ($is_pendiente){?>
                                            <a href="<?=$ref_elegir?>" style="font-size:11.5px; color:#2563eb; font-weight:600; text-decoration:none;">
                                                ⚙️ Gestionar prestaciones de este comprobante &raquo;
                                            </a>
                                        <?}?>
                                    </div>

                                    <?if ($cant_total_pres == 0){?>
                                        <div style="padding:10px; text-align:center; color:#991b1b; font-weight:600; font-size:12px;">
                                            No hay prestaciones registradas para este comprobante.
                                        </div>
                                    <?}else{?>
                                        <table class="paf-subtable">
                                            <thead>
                                                <tr>
                                                    <th style="width:60px; text-align:center;">Cant.</th>
                                                    <th style="width:140px;">Código</th>
                                                    <th>Descripción y Diagnóstico</th>
                                                    <th style="width:100px; text-align:right;">Precio Unit.</th>
                                                    <th style="width:100px; text-align:right;">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?
                                            // Prestaciones Nomenclador no operables
                                            while (!$result_items1->EOF) {
                                                $cod_p = $result_items1->fields['prestacion'];
                                                $cod_t = $result_items1->fields['tema'];
                                                $cod_diag = $result_items1->fields['patologia'];
                                                $cod_prof = $result_items1->fields['profesional'];
                                                
                                                $cat_prest = isset($map_grupo_prestacion[$cod_p]) ? $map_grupo_prestacion[$cod_p] : $cod_p;
                                                $cat_tema = isset($map_grupo_prestacion[$cod_t]) ? $map_grupo_prestacion[$cod_t] : $cod_t;
                                                $desc_diag = isset($map_patologias[$cod_diag]) ? $map_patologias[$cod_diag] : $cod_diag;
                                                $cat_prof = isset($map_grupo_prestacion[$cod_prof]) ? $map_grupo_prestacion[$cod_prof] : $cod_prof;
                                                
                                                $desc_pres = "<b>Prestación:</b> $cat_prest | <b>Objeto:</b> $cat_tema | <b>Diagnóstico:</b> $desc_diag";
                                                $desc_amp = "Prestación: $cat_prest | Objeto: $cat_tema | Diagnóstico: $desc_diag | Profesional: $cat_prof";
                                                $precio_format = number_format($result_items1->fields["precio"],2,',','.');
                                                $codigo_formateado = substr($result_items1->fields["codigo"],41,2)."-".substr($result_items1->fields["codigo"],43,4)."-".substr($result_items1->fields["codigo"],47,3);
                                            ?>
                                                <tr>
                                                    <td style="text-align:center;">1</td>
                                                    <td><span class="paf-code-pill" title="<?=$result_items1->fields["codigo"]?>"><?=$codigo_formateado?></span></td>
                                                    <td title="<?=$desc_amp?>"><?=$desc_pres?></td>
                                                    <td style="text-align:right; font-family:'Consolas',monospace;">$ <?=$precio_format?></td>
                                                    <td style="text-align:right; font-family:'Consolas',monospace; font-weight:700;">$ <?=$precio_format?></td>
                                                </tr>
                                            <?
                                                $result_items1->movenext();
                                            }
                                            
                                            // Prestaciones tradicionales (con validación de confidencialidad para San Luis)
                                            while (!$result_items->EOF) {
                                                $cod_prestacion = $result_items->fields["codigo"];
                                                $desc_diag_trad = (!empty($result_items->fields["cod_diag"]) ? ' | Diagnóstico: ' . $result_items->fields["cod_diag"] . ' - ' . $result_items->fields["desc_diag"] : '');
                                                
                                                if (($cod_prestacion == "C073") || ($cod_prestacion == "C098")) {
                                                    if (permisos_check('inicio', 'ver_prestacion_confidencial')) {
                                                        $descripcion_final = $result_items->fields["descripcion"] . $desc_diag_trad;
                                                    } else {
                                                        $descripcion_final = "<span style='color:#dc2626; font-weight:600;'>CONFIDENCIAL</span>";
                                                    }
                                                } else {
                                                    $descripcion_final = $result_items->fields["descripcion"] . $desc_diag_trad;
                                                }
                                                
                                                $unit_price = $result_items->fields["precio_prestacion"];
                                                $cant_pres = $result_items->fields["cantidad"];
                                                $subtotal_pres = $cant_pres * $unit_price;
                                            ?>
                                                <tr>
                                                    <td style="text-align:center;"><?=$cant_pres?></td>
                                                    <td><span class="paf-code-pill"><?=$cod_prestacion?></span></td>
                                                    <td><?=$descripcion_final?></td>
                                                    <td style="text-align:right; font-family:'Consolas',monospace;">$ <?=number_format($unit_price,2,',','.')?></td>
                                                    <td style="text-align:right; font-family:'Consolas',monospace; font-weight:700;">$ <?=number_format($subtotal_pres,2,',','.')?></td>
                                                </tr>
                                            <?
                                                $result_items->movenext();
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    <?}?>
                                </div>
                            </td>
                        </tr>
                    <?
                        $res_comprobante->movenext();
                    }
                    ?>
                    </tbody>
                </table>
            <?}?>
        </div>
    </div>

    <!-- Barra de Referencias y Leyendas -->
    <div class="paf-legend-bar">
        <span style="font-weight:700; color:#1e293b;">Referencias:</span>
        <div class="paf-legend-item">
            <span class="paf-badge-pending">Sin Facturar</span>
            <span>Comprobante activo (pendiente de facturación)</span>
        </div>
        <div class="paf-legend-item">
            <span class="paf-badge-billed">Facturado</span>
            <span>Comprobante liquidado</span>
        </div>
        <div class="paf-legend-item">
            <span class="paf-badge-canceled">Anulado</span>
            <span>Comprobante dado de baja</span>
        </div>
        <div class="paf-legend-item">
            <span class="paf-tag-alta-comp">Alta Compl.</span>
            <span>Alta Complejidad</span>
        </div>
        <div class="paf-legend-item">
            <span class="paf-tag-flap">FLAP</span>
            <span>Anomalías Congénitas</span>
        </div>
        <div class="paf-legend-item">
            <span class="paf-tag-covid">COVID-19</span>
            <span>Prestación COVID-19</span>
        </div>
    </div>

    <!-- Registro de Actividad / Logs del Comprobante en San Luis -->
    <?
    $q_log="SELECT 
          facturacion.log_comprobante.id_log_comprobante,
          facturacion.comprobante.id_comprobante,
          facturacion.log_comprobante.fecha,
          facturacion.log_comprobante.tipo,
          facturacion.log_comprobante.descripcion,
          facturacion.log_comprobante.usuario
        FROM
          facturacion.comprobante
        LEFT JOIN facturacion.log_comprobante 
              ON (facturacion.comprobante.id_comprobante = facturacion.log_comprobante.id_comprobante)
        where facturacion.comprobante.id_smiafiliados=$id_smiafiliados
        order by id_log_comprobante DESC";
    $log_query=sql($q_log) or fin_pagina();
    $total_logs = $log_query->RecordCount();
    ?>
    <div class="paf-card">
        <div class="paf-card-header" style="cursor:pointer;" onclick="
            var el = document.getElementById('seccion_logs');
            if (el.style.display=='none') { el.style.display=''; this.querySelector('.paf-log-toggle').innerHTML='▲ Ocultar'; }
            else { el.style.display='none'; this.querySelector('.paf-log-toggle').innerHTML='▼ Desplegar'; }
        ">
            <div style="display:flex; align-items:center; gap:8px;">
                <span>📜 Registro de Auditoría de Comprobantes</span>
                <span class="paf-badge-dni" style="background:#f1f5f9; color:#475569;"><?=$total_logs?> registros</span>
            </div>
            <span class="paf-log-toggle" style="font-size:12px; color:#2563eb; font-weight:600;">▼ Desplegar</span>
        </div>
        <div id="seccion_logs" style="display:none; padding:12px;">
            <?if ($total_logs == 0){?>
                <div style="color:#64748b; font-size:12px; text-align:center; padding:10px;">
                    No hay registros de auditoría para este beneficiario.
                </div>
            <?}else{?>
                <div class="paf-log-container">
                    <table class="paf-subtable">
                        <thead>
                            <tr>
                                <th style="width:160px;">Fecha y Hora</th>
                                <th style="width:140px;">Usuario</th>
                                <th style="width:160px;">Tipo de Acción</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?while (!$log_query->EOF){?>
                            <tr>
                                <td><?=fecha($log_query->fields['fecha']) . " " . Hora($log_query->fields['fecha']);?></td>
                                <td><b><?=$log_query->fields['usuario']?></b></td>
                                <td><span class="paf-code-pill"><?=$log_query->fields['tipo']?></span></td>
                                <td><?=$log_query->fields['descripcion']?></td>
                            </tr>
                            <?$log_query->MoveNext();
                        }?>
                        </tbody>
                    </table>
                </div>
            <?}?>
        </div>
    </div>

    <!-- Botón Volver -->
    <div style="display:flex; justify-content:flex-start; margin-top:16px;">
        <?if ($pagina_listado=='listado_beneficiarios_hist.php'){?>
            <a href="listado_beneficiarios_hist.php" class="paf-btn-back">⬅ Volver al Listado Histórico</a>
        <?}else if ($pagina_listado=='listado_beneficiarios_leche.php'){?>
            <a href="../entrega_leche/listado_beneficiarios_leche.php" class="paf-btn-back">⬅ Volver al Listado de Leche</a>
        <?}else{?>
            <a href="listado_beneficiarios_fact.php" class="paf-btn-back">⬅ Volver al Listado de Facturación</a>
        <?}?>
    </div>

    </form>
</div>

<?=fin_pagina();?>
