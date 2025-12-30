<?php
require_once ("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

  $sql_tmp=  "SELECT nacer.agendas_eventos.id AS id_ag_evento,
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
                      nacer.cepsap_items.codigo,
                      nacer.cepsap_items.descripcion 
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
                      WHERE (date (nacer.agendas_eventos.inicio) = '{$fecha_inicio_db}' AND 
                          nacer.agendas_eventos.id_efector = {$id_efector} AND 
                          nacer.agendas_eventos.estado != 'cancelado' AND
                          nacer.especialidades.id_especialidad = {$id_especialidad} AND 
                          nacer.medicos.id_medico = {$id_medico})
                  ORDER BY nacer.agendas_eventos.inicio ASC";
$result=sql($sql_tmp) or fin_pagina();
excel_header("planilla.xls");

?>
<form name=form1 method=post action="excel_mat.php">
 <table width="100%" align=center> 
    <tr >    
      <td><b>PROGRAMA ATENCION PRIMARIA</b></td>
      <td><b>INFORME DIARIO DE CONSULTAS AMBULATORIAS</b></td>
      <td><b>MEDICA GENERAL</b></td>
    </tr>
 </table>
<table width="100%" align=center> 
    <tr>    
      <td><b>Establecimiento:</b></td>
      <td><?=$result->fields['efector_nombre']?></td>
    </tr>
    <tr>
      <td><b>ZONA SANITARIA:</b></td>
      <td><??></td>
      <td><b>Nombre y Apellido del Medico:</b></td>
      <td><?=$result->fields['medico_nombre'].', '.$result->fields['medico_apellido']?></td>
    </tr>
 </table>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
    <tr bgcolor=#C0C0FF> 
          <td align="center" id=mo>Tipo y Nro.</td>  
            <td align="center" id=mo>Apellido</td>        
            <td align="center" id=mo>Nombre</td>    
            <td align="center" id=mo>Edad</td>  
            <td align="center" id=mo>Sexo</td>   
            <td align="center" id=mo>Residencia Habitual</td>
            <td align="center" id=mo>OS</td>   
            <td align="center" id=mo>P.Soc.</td>        
            <td align="center" id=mo>Plan de Seg. Publico</td>    
            <td align="center" id=mo>Mas de uno</td>       
            <td align="center" id=mo>Ninguno</td>  
            <td align="center" id=mo>1ra Vez</td>   
            <td align="center" id=mo>Ulterior</td>     
            <td align="center" id=mo>Diagnostico</td>   
       
    </tr>
  <?   
  while (!$result->EOF) {?>  
       <tr>    
          <td ><?=$result->fields['tipo_documento'].', '.$result->fields['numero_doc']?></td> 
          <td ><?=$result->fields['paciente_apellido']?></td>
          <td ><?=$result->fields['paciente_nombre']?></td> 
          <td ><?=date("Y-m-d")-$result->fields['fecha_nacimiento_benef'];?></td>
          <td ><?=$result->fields['sexo']?></td>
          <td ><?=$result->fields['calle'].', '.$result->fields['numero_calle'].', '.$result->fields['barrio'];?></td>
          <td ><?if($result->fields['sexo']!='')echo "X"; else echo " ";?></td>
          <td ></td>
            <td ></td>    
            <td ></td>       
            <td ></td>  
            <td ></td>   
            <td ></td>     
            <td ></td>  
       </tr>
  <?$result->MoveNext();
    }?>
  
 </table>
  </br>
 </form>