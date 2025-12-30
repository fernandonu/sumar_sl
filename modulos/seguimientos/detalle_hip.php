<?php

require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$sql_tmp="SELECT distinct on (afidni)
      afidni,afinombre,afiapellido,afifechanac,fecha_control,estado from (
      SELECT distinct on (nacer.smiafiliados.afidni,fichero.fichero.fecha_control)
      nacer.smiafiliados.afidni,
      nacer.smiafiliados.afinombre,
      nacer.smiafiliados.afiapellido,
      nacer.smiafiliados.afifechanac,
      fichero.fichero.fecha_control,
      'desde fichero (nacer)' as estado
      from fichero.fichero
      inner join nacer.smiafiliados on fichero.fichero.id_smiafiliados=nacer.smiafiliados.id_smiafiliados
      where cuie='$cuie' and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and hipertenso='SI'
      union
      select distinct on (uad.beneficiarios.numero_doc,fichero.fichero.fecha_control)
      uad.beneficiarios.numero_doc,
      uad.beneficiarios.nombre_benef,
      uad.beneficiarios.apellido_benef,
      uad.beneficiarios.fecha_nacimiento_benef,
      fichero.fichero.fecha_control,
      'desde fichero (emp.rapido)' as estado
      from fichero.fichero
      inner join uad.beneficiarios on fichero.fichero.id_beneficiarios=uad.beneficiarios.id_beneficiarios
      where cuie='$cuie' and 
      fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and hipertenso='SI'
      union

      SELECT distinct on (clasificacion_remediar2.num_doc,clasificacion_remediar2.fecha_control)
      clasificacion_remediar2.num_doc,
      clasificacion_remediar2.nombre,
      clasificacion_remediar2.apellido,
      clasificacion_remediar2.fecha_nac,
      clasificacion_remediar2.fecha_control,
      'desde clasificacion' as estado
      from trazadoras.clasificacion_remediar2,
      nacer.smiafiliados 
      where trazadoras.clasificacion_remediar2.clave_beneficiario = nacer.smiafiliados.clavebeneficiario 
      and cuie = '$cuie' and fecha_control between '$fecha_desde' and '$fecha_hasta' and hipertenso = 'SI'

      union
      --consulta desde seguimientos 

      select distinct on (nacer.smiafiliados.afidni,trazadoras.seguimiento_remediar.fecha_comprobante)
      --uad.beneficiarios.numero_doc,
      nacer.smiafiliados.afidni,
      --uad.beneficiarios.nombre_benef,
      nacer.smiafiliados.afinombre,
      --uad.beneficiarios.apellido_benef,
      nacer.smiafiliados.afiapellido,
      --uad.beneficiarios.fecha_nacimiento_benef,
      nacer.smiafiliados.afifechanac,
      trazadoras.seguimiento_remediar.fecha_comprobante as fecha_control,
      'desde seguimiento' as estado
      from trazadoras.seguimiento_remediar , nacer.smiafiliados 
      --inner join uad.beneficiarios on uad.beneficiarios.clave_beneficiario=trim (' ' from trazadoras.seguimiento_remediar.clave_beneficiario)
      where trazadoras.seguimiento_remediar.clave_beneficiario = nacer.smiafiliados.clavebeneficiario 
      and trazadoras.seguimiento_remediar.efector='$cuie' 
      and trazadoras.seguimiento_remediar.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
      and (trazadoras.seguimiento_remediar.hta='SI' or trazadoras.seguimiento_remediar.hta='on')

      ) as ccc order by 1,5";


$result=sql($sql_tmp) or fin_pagina();

echo $html_header;
?>
<form name=form1 method=post action="detalle_hip.php">
<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr><td align=left><b>Total de Beneficiarios : <?php echo $result->RecordCount();?></b></td></tr>   
     </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
  <tr bgcolor=#C0C0FF>
    <td align="right" id="mo">Inscripcion</td>      	
    <td align="right" id="mo">DNI</td>      	
    <td align="right" id="mo">Apellido</td>      	
    <td align="right" id="mo">Nombre</td>  
    <td align="right" id="mo">Fecha Nacimiento</td>    	
    <!--<td align="right" id="mo">Fecha Control</td>-->
  </tr>
  <?   
  $contador=0;
  $afidni_ant=$result->fields['afidni'];
  $cont_cont_validos=0;
  $cont_cont_invalidos=0;

  while (!$result->EOF) {
	
  if ($afidni_ant==$result->fields['afidni']) $contador++;
    else $contador=1;
  
  if ($contador<=6) {$color='#BFF1BF'; $cont_cont_validos++;}
    else {$color='#FAA3A3';$cont_cont_invalidos++;}
  
  switch ($result->fields['estado']) {
      case 'na':
        {?>  
      <tr style="background-color:<?php echo $color?>;">     
      <td>Inscripto SUMAR</td>
      <td><?php echo $result->fields['afidni']?></td>
      <td><?php echo $result->fields['afiapellido']?></td>
      <td><?php echo $result->fields['afinombre']?></td>
      <td><?php echo fecha($result->fields['afifechanac'])?></td> 
      <!--<td><?php //echo fecha($result->fields['fecha_control'])?></td> -->
    </tr>
  <? break;}
       
     case 'nu':
      {?>
      <tr style="background-color:<?php echo $color?>;">    
     <td>Inscripto por Fichero</td>
     <td><?php echo $result->fields['afidni']?></td>
     <td><?php echo $result->fields['afiapellido']?></td>
     <td><?php echo $result->fields['afinombre']?></td>
     <td><?php echo fecha($result->fields['afifechanac'])?></td> 
     <!--<td><?php //echo fecha($result->fields['fecha_control'])?></td> -->
    </tr>
  <? break;}   

      default: {?>
        <tr style="background-color:<?php echo $color?>;">     
        <td>Desde Seguimientos</td>
        <td><?php echo $result->fields['afidni']?></td>
        <td><?php echo $result->fields['afiapellido']?></td>
        <td><?php echo $result->fields['afinombre']?></td>
        <td><?php echo fecha($result->fields['afifechanac'])?></td> 
        <!--<td><?php //echo fecha($result->fields['fecha_control'])?></td> -->
    </tr>
     <? break; }

   }//del swith 
	$afidni_ant=$result->fields['afidni'];
	$result->MoveNext();
 }?>
		
 </table>
  &nbsp;&nbsp;
 <table border="1">
  <!--<tr style="background-color:#BFF1BF"><td align=left ><b>Total de Controles Validos : <?php echo $cont_cont_validos;?></b></td></tr>
  <tr style="background-color:#FAA3A3"><td align=left><b>Total de Controles >= 6 : <?php echo $cont_cont_invalidos;?></b></td></tr>
--> 
</table>
  &nbsp;&nbsp;
  &nbsp;&nbsp;
 </form>
 <?php echo fin_pagina();// aca termino ?>