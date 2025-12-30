 <?php
define('FPDF_FONTPATH','font/');
require_once("../../config.php");
include_once("planillat_clasepdf.php"); 

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$pdf=new orden_compra('L','pt','A4');

if ($parametros['id_agenda']) $id_agenda=$parametros['id_agenda'];
if ($parametros['inicio']) $id_agenda=$parametros['inicio'];

		$query="SELECT 
                    nacer.agendas_eventos.id as id_ag_evento,
                    nacer.agendas_eventos.titulo,
                    nacer.agendas_eventos.inicio,
                    nacer.agendas_eventos.fin,
                    nacer.agendas_eventos.url,
                    nacer.agendas_eventos.id_agenda,
                    nacer.agendas_eventos.id_paciente,
                    nacer.agendas_eventos.estado,
                    nacer.agendas_eventos.hora_presente,
                    nacer.agendas_eventos.motivo,
                    nacer.agendas_eventos.sobreturno,
                    uad.beneficiarios.apellido_benef AS paciente_apellido,
                    uad.beneficiarios.nombre_benef AS paciente_nombre,
                    uad.beneficiarios.numero_doc,
                    uad.beneficiarios.fecha_nacimiento_benef,
                    uad.beneficiarios.sexo,
                    uad.beneficiarios.tipo_documento,
                    nacer.agendas_eventos.id_efector,
                    nacer.efe_conv.nombre AS efector_nombre,
                    nacer.especialidades.id_especialidad,
                    nacer.especialidades.nom_titulo AS especialidad_nombre,
                    nacer.medicos.id_medico,
                    nacer.medicos.apellido AS medico_apellido,
                    nacer.medicos.nombre AS medico_nombre,
                    nacer.obras_sociales.id_obra_social,
                    nacer.obras_sociales.nom_obra_social AS obra_social_nombre,
                    nacer.zona_sani.nombre_zona
                  FROM
                    nacer.agendas_eventos
                    LEFT OUTER JOIN nacer.efe_conv ON (nacer.agendas_eventos.id_efector = nacer.efe_conv.id_efe_conv)
                    LEFT OUTER JOIN nacer.zona_sani ON (nacer.zona_sani.id_zona_sani = nacer.efe_conv.id_zona_sani)
                    LEFT OUTER JOIN uad.beneficiarios ON (nacer.agendas_eventos.id_paciente = uad.beneficiarios.id_beneficiarios)
                    LEFT OUTER JOIN nacer.agendas ON (nacer.agendas_eventos.id_agenda = nacer.agendas.id)
                    LEFT OUTER JOIN nacer.especialidades_medicos ON (nacer.agendas.id_especialidad_medico = nacer.especialidades_medicos.id)
                    LEFT OUTER JOIN nacer.medicos ON (nacer.especialidades_medicos.id_medico = nacer.medicos.id_medico)
                    LEFT OUTER JOIN nacer.obras_sociales ON (nacer.agendas_eventos.id_obra_social = nacer.obras_sociales.id_obra_social)
                    LEFT OUTER JOIN nacer.especialidades ON (nacer.especialidades.id_especialidad = nacer.especialidades_medicos.id_especialidad)
                  WHERE
                    date (nacer.agendas_eventos.inicio) = '$fecha_inicio_db' AND 
                    nacer.agendas_eventos.id_efector = {$id_efector} AND 
                    nacer.especialidades.id_especialidad = {$id_especialidad} AND 
                    nacer.medicos.id_medico = {$id_medico}";

	$f_res=$db->Execute($query) or die($db->ErrorMsg());//' 

	$base1_aux=5;
	$pdf->asignar_base1($base1_aux); 

	$efector_nombre=$f_res->fields['efector_nombre'];
	$direccion=$f_res->fields['direccion'].'-'.$f_res->fields['localidad'];
	$provincia=$f_res->fields['provincia'].'Codigo Postal: '.$f_res->fields['cp'];
	$fecha=$fecha_inicio_db;
  $zona=$f_res->fields['nombre_zona'];
	
	
	$pdf->dibujar_planilla();
//$base1_aux=145;
	while (!$f_res->EOF){
	//	$pdf->asignar_base2($base1_aux);
    $t_doc=$sql_pres->fields['tipo_documento'].'-'.$sql_pres->fields['numero_doc'];
    $ape_nom=$sql_pres->fields['paciente_apellido'].', '.$sql_pres->fields['paciente_nombre'];
    $domicilio=$sql_pres->fields['barrio'].', '.$sql_pres->fields['calle'].', '.$sql_pres->fields['numero_calle'];
    $inicio=$sql_pres->fields['inicio'];
		$sexo=$f_res->fields['sexo'];	
    $fecha_nacimiento=fecha($f_res->fields['fecha_nacimiento_benef']);

		$pdf->paciente($t_doc, $ape_nom, $domicilio, $inicio, $sexo, $fecha_nacimiento);
	//$base1_aux=$base1_aux + 6;
	$f_res->movenext();
}	
	

	//$pdf->_final($obs);
	$pdf->Footer();
	
$pdf->guardar_servidor("Planilla_$fecha_inicio_db.pdf");

?>