<?php
require_once ("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

  /*$sql_tmp=  "SELECT nacer.agendas_eventos.id AS id_ag_evento,
                      nacer.agendas_eventos.titulo,
                      nacer.agendas_eventos.inicio,
                      nacer.agendas_eventos.id_agenda,
                      nacer.agendas_eventos.id_paciente,
                      nacer.agendas_eventos.estado,
                      nacer.agendas_eventos.motivo,
                      nacer.agendas_eventos.sobreturno,
                      uad.beneficiarios.apellido_benef AS paciente_apellido,
                      uad.beneficiarios.nombre_benef AS paciente_nombre,
                      uad.beneficiarios.numero_doc,
                      uad.beneficiarios.sexo,
                      uad.beneficiarios.fecha_nacimiento_benef,
                      uad.beneficiarios.tipo_documento,
                      uad.beneficiarios.barrio,
                      uad.beneficiarios.numero_calle,
                      uad.beneficiarios.calle,
                      nacer.agendas_eventos.id_efector,
                      nacer.efe_conv.nombre AS efector_nombre,
                      nacer.especialidades.id_especialidad,
                      nacer.especialidades.nom_titulo AS especialidad_nombre,
                      nacer.medicos.id_medico,
                      nacer.medicos.apellido AS medico_apellido,
                      nacer.medicos.nombre AS medico_nombre,
                      nacer.obras_sociales.id_obra_social,
                      nacer.obras_sociales.nom_obra_social AS obra_social_nombre,
                      nacer.cie10.dec10,
                      nacer.cie10.grp10,
                      nacer.cepsap_items.codigo AS cesap1,
                      nacer.cepsap_items.descripcion as cesapdesc1,
                      nacer.cepsap_items_2016.codigo as cesapcod,
                      nacer.cepsap_items_2016.descripcion as cesapdesc,
                      nacer.diagnosticos.id_cesap2,
                      nacer.diagnosticos.prima_ves,
                      nacer.diagnosticos.ulterior
                  FROM
                    nacer.agendas_eventos
                    LEFT OUTER JOIN nacer.efe_conv ON (nacer.agendas_eventos.id_efector = nacer.efe_conv.id_efe_conv)
                    LEFT OUTER JOIN uad.beneficiarios ON (nacer.agendas_eventos.id_paciente = uad.beneficiarios.id_beneficiarios)
                    LEFT OUTER JOIN nacer.agendas ON (nacer.agendas_eventos.id_agenda = nacer.agendas.id)
                    LEFT OUTER JOIN nacer.especialidades_medicos ON (nacer.agendas.id_especialidad_medico = nacer.especialidades_medicos.id)
                    LEFT OUTER JOIN nacer.medicos ON (nacer.especialidades_medicos.id_medico = nacer.medicos.id_medico)
                    LEFT OUTER JOIN nacer.obras_sociales ON (nacer.agendas_eventos.id_obra_social = nacer.obras_sociales.id_obra_social)
                    LEFT OUTER JOIN nacer.especialidades ON (nacer.especialidades.id_especialidad = nacer.especialidades_medicos.id_especialidad)
                    LEFT OUTER JOIN nacer.diagnosticos ON nacer.agendas_eventos.id = nacer.diagnosticos.id_turno
                    LEFT OUTER JOIN nacer.cie10 ON nacer.cie10.id10 = nacer.diagnosticos.id_cie10
                    LEFT OUTER JOIN nacer.cepsap_items ON nacer.cepsap_items.id = nacer.diagnosticos.id_cepsap
                    LEFT OUTER JOIN nacer.cepsap_items_2016 ON nacer.cepsap_items_2016.id = nacer.diagnosticos.id_cesap2";
if (!empty($id_efector) and !empty($id_especialidad) and !empty($id_medico) and !empty($fecha_inicio) and !empty($fecha_hasta)){
                         //echo "entro 1 ";
                        $where="  WHERE
                          (date (nacer.agendas_eventos.inicio) BETWEEN  '{$fecha_inicio_db}' AND '{$fecha_hasta_db}') and
                          nacer.agendas_eventos.id_efector = {$id_efector} AND 
                          nacer.agendas_eventos.estado != 'cancelado' AND
                          nacer.especialidades.id_especialidad = {$id_especialidad} AND 
                          nacer.medicos.id_medico = {$id_medico}
                          order by nacer.agendas_eventos.inicio ASC";
                    }elseif(!empty($id_efector) and !empty($id_especialidad) and !empty($fecha_inicio) and !empty($fecha_hasta)){
                         //echo "entro 2 ";
                        $where="  WHERE
                          (date (nacer.agendas_eventos.inicio) BETWEEN  '{$fecha_inicio_db}' AND '{$fecha_hasta_db}') and
                          nacer.agendas_eventos.id_efector = {$id_efector} AND 
                          nacer.agendas_eventos.estado != 'cancelado' AND
                          nacer.especialidades.id_especialidad = {$id_especialidad}  
                          order by nacer.agendas_eventos.inicio ASC";
                      }elseif(!empty($id_efector) and !empty($fecha_inicio) and !empty($fecha_hasta)){
                      //echo "entro 3 ";
                      $where="  WHERE
                        (date (nacer.agendas_eventos.inicio) BETWEEN  '{$fecha_inicio_db}' AND '{$fecha_hasta_db}') and
                        nacer.agendas_eventos.id_efector = {$id_efector} AND 
                        nacer.agendas_eventos.estado != 'cancelado' 
                        order by nacer.agendas_eventos.inicio ASC";

                      }elseif(!empty($fecha_inicio) and !empty($fecha_hasta)) {
                              //echo "entro 4 ";
                              $where="  WHERE
                                    (date (nacer.agendas_eventos.inicio) BETWEEN  '{$fecha_inicio_db}' AND '{$fecha_hasta_db}') and
                                    nacer.agendas_eventos.estado != 'cancelado' 
                                    order by nacer.agendas_eventos.inicio ASC";
                      }
                       //  echo "entro a el codigo  ";
                 
                $sql_tmp1=$sql_tmp.$where;
                $sql_pres=sql($sql_tmp1,"No se pueden mostrar los registros");



$sql_pres=sql($sql_tmp) or fin_pagina();*/
excel_header("planilla.xls");

?>
<form name=form1 method=post action="excel_mat.php">
 <table width="100%" align=center> 
    <tr >    
      <td><b>Ministerio de Salud</b></td>
      <td><b>IPrograma Epidemiologi y Bioestadistica</b></td>
      <td><b>Area Estadistica Hospitalaria</b></td>
    </tr>
 </table>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
    <tr bgcolor=#C0C0FF> 
          <?while (!$sql_pres->EOF){ ?>
                        <td align="left"><?=$sql_pres->fields['efector_nombre'];?></td>
                        <td align="left"><?=$sql_pres->fields['especialidad_nombre'];?></td>
                        <td align="left"><?=$sql_pres->fields['medico_apellido'];?></td>
                        <td align="left"> <?=$sql_pres->fields['paciente_apellido'].', '.$sql_pres->fields['paciente_nombre'];?></td>
                        <td align="left" > <?=$sql_pres->fields['tipo_documento'].'-'.$sql_pres->fields['numero_doc']?></td>
                        <td align="left"> <?=$sql_pres->fields['sexo']?></td>
                        <td align="left"> <?=date("Y-m-d")-$sql_pres->fields['fecha_nacimiento_benef']?></td>
                        <td align="left"> <?=$sql_pres->fields['obra_social_nombre']?></td>
                     
                        <td align="left"> <?=$sql_pres->fields['estado']?></td>
                        <td align="left"> <?=fecha($sql_pres->fields['inicio']);?></td>
                        <td><a><?if($sql_pres->fields['prima_ves']==1)
                                    echo  "Primera vez";
                                  else
                                    echo  "Ulterrior";    
                        ?></a>
                         </td> 
                        <td><a><?if($sql_pres->fields['id_cepsap']!=0)
                            echo  $sql_pres->fields['id10'].'-'.$sql_pres->fields['grp10'].'-'.$sql_pres->fields['dec10'];// MUESTRA cepsap1?>
                            <?if($sql_pres->fields['id_cesap2']!=0)
                            echo  $sql_pres->fields['cesapcod'].'-'.$sql_pres->fields['cesapdesc'];// MUESTRA  CIE10
                            elseif($sql_pres->fields['id_cie10']!=0)
                        echo  $sql_pres->fields['cesap1'].'-'.$sql_pres->fields['cesapdesc1'];//muestra por CESAP
                        ?></a>
                         </td> 
       </tr>
  <?$sql_pres->MoveNext();
    }?>
  
 </table>
  </br>
 </form>