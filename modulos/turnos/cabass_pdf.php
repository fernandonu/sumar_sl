<?php

define('FPDF_FONTPATH','font/');

require_once("../../config.php");
include_once("cabass_clasepdf.php"); 

$pdf=new orden_compra(P,cm,array(29,21 ));
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

//if ($parametros['id_agevent']) $idagen_ev=$parametros['id_agevent'];

$query_pac="SELECT 
                    nacer.agendas_eventos.id,
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
                    uad.beneficiarios.tipo_documento,
                    uad.beneficiarios.sexo,
                    uad.beneficiarios.numero_doc,
                     uad.beneficiarios.fecha_nacimiento_benef,
                    nacer.agendas_eventos.id_efector,
                    nacer.efe_conv.nombre AS efector_nombre,
                    nacer.efe_conv.codigo_hpgd,
                    nacer.especialidades.id_especialidad,
                    nacer.especialidades.nom_titulo AS especialidad_nombre,
                    nacer.medicos.id_medico,
                    nacer.medicos.apellido AS medico_apellido,
                    nacer.medicos.nombre AS medico_nombre,
                    nacer.obras_sociales.id_obra_social,
                    nacer.obras_sociales.nom_obra_social AS obra_social_nombre
                  FROM
                    nacer.agendas_eventos
                    LEFT OUTER JOIN nacer.efe_conv ON (nacer.agendas_eventos.id_efector = nacer.efe_conv.id_efe_conv)
                    LEFT OUTER JOIN uad.beneficiarios ON (nacer.agendas_eventos.id_paciente = uad.beneficiarios.id_beneficiarios)
                    LEFT OUTER JOIN nacer.agendas ON (nacer.agendas_eventos.id_agenda = nacer.agendas.id)
                    LEFT OUTER JOIN nacer.especialidades_medicos ON (nacer.agendas.id_especialidad_medico = nacer.especialidades_medicos.id)
                    LEFT OUTER JOIN nacer.medicos ON (nacer.especialidades_medicos.id_medico = nacer.medicos.id_medico)
                    LEFT OUTER JOIN nacer.obras_sociales ON (nacer.agendas_eventos.id_obra_social = nacer.obras_sociales.id_obra_social)
                    LEFT OUTER JOIN nacer.especialidades ON (nacer.especialidades.id_especialidad = nacer.especialidades_medicos.id_especialidad)
                  WHERE
                    nacer.agendas_eventos.id= $id_agevent";

   echo  $res_pac=sql($query_pac,'al traer informacion del paciente') or die();


  $nom_ape=$res_pac->fields['paciente_apellido'].', '. $res_pac->fields['paciente_nombre'];
  $edad=date("Y-m-d")-$res_pac->fields['f_nacimiento'];
  $tipo_dni=$res_pac->fields['id_tdni']; 
  $dni=$res_pac->fields['numero_doc'];
  $fecha_nac=fecha($res_pac->fields['fecha_nacimiento_benef']);
  $sexo=$res_pac->fields['sexo'];
  if($sexo=='1')$sexo='FEMENINO';elseif($sexo=='2')$sexo='MASCULINO';else$sexo='';
  $f_atencion=$res_pac->fields['inicio'];
  $nro_cabos='';
  $t_atencion='Consulta';
  $n_servico=$res_pac->fields['especialidad_nombre'];
  $nom_financer=$res_pac->fields['obra_social_nombre'];
  $code=$res_pac->fields['code'];
  $nom_hopital=$res_pac->fields['efector_nombre'];
  $codigo_hpgd=$res_pac->fields['codigo_hpgd'];
  $nro_benef='';
  $nro_titular='';
  $furs='';
  $cuit_trabajo='';
  $parentesco='';
  $t_benef='';
  $d_trabajo='';
  $l_trabajo='';
  $diag='';

	$base1_aux=-2.5;
	$pdf->asignar_base1($base1_aux);
	$pdf->dibujar_planilla();
	$pdf->envia_declaracion($nro_cabos, $f_atencion, $nom_hopital, $codigo_hpgd, $nom_ape, $tipo_dni, $dni, $t_benef,  $parentesco, $sexo, $edad, $t_atencion, $n_servico,$f_atencion, $nom_financer, $code,$nro_titular, $nro_benef , $l_trabajo, $furs, $d_trabajo, $cuit_trabajo, $diag );

	$pdf->final_pagina($base1_aux);
	$pdf->Footer();
	
$pdf->guardar_servidor("CABASS_$idagen_ev.pdf");
//}
?>