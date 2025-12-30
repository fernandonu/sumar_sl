<?php

require_once ("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$cuie=$parametros['cuie'];
$fecha_desde=$parametros['fecha_desde'];
$fecha_hasta=$parametros['fecha_hasta'];
    
    if($cuie=='Todos'){
      $sql_tmp="SELECT fichero.fecha_control,
              fichero.cuie,
              efe_conv.nombre as efector,
              beneficiarios.nombre,
              beneficiarios.apellido,
              beneficiarios.documento,
              beneficiarios.fecha_nac,
              extract (year from age(fichero.fecha_control,beneficiarios.fecha_nac)) as edad_anio,
              extract (month from age(fichero.fecha_control,beneficiarios.fecha_nac)) as edad_meses,
              beneficiarios.sexo,
              beneficiarios.domicilio,
              fichero.enfer_epidemeologica
              --esquema vacuna  

              from fichero.fichero 
              inner join nacer.efe_conv using (cuie)
              inner join leche.beneficiarios using (id_beneficiarios)

              where id_beneficiarios<>0 and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and fichero.enfer_epidemeologica is not null

              union

              SELECT fichero.fecha_control,
              fichero.cuie,
              efe_conv.nombre as efector,
              smiafiliados.afinombre,
              smiafiliados.afiapellido,
              smiafiliados.afidni,
              smiafiliados.afifechanac,
              extract (year from age(fichero.fecha_control,smiafiliados.afifechanac)) as edad_anio,
              extract (month from age(fichero.fecha_control,smiafiliados.afifechanac)) as edad_meses,
              smiafiliados.afisexo,
              smiafiliados.".'"afiDomCalle"'."||'N°'||smiafiliados.".'"afiDomNro"'." as direccion,
              fichero.enfer_epidemeologica
              --esquema vacuna  

              from fichero.fichero 
              inner join nacer.efe_conv using (cuie)
              inner join nacer.smiafiliados using (id_smiafiliados)

              where id_smiafiliados<>0 and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and fichero.enfer_epidemeologica is not null
              order by 1";
} else {
      $sql_tmp="SELECT fichero.fecha_control,
              fichero.cuie,
              efe_conv.nombre as efector,
              beneficiarios.nombre,
              beneficiarios.apellido,
              beneficiarios.documento,
              beneficiarios.fecha_nac,
              extract (year from age(fichero.fecha_control,beneficiarios.fecha_nac)) as edad_anio,
              extract (month from age(fichero.fecha_control,beneficiarios.fecha_nac)) as edad_meses,
              beneficiarios.sexo,
              beneficiarios.domicilio,
              fichero.enfer_epidemeologica
              --esquema vacuna  

              from fichero.fichero 
              inner join nacer.efe_conv using (cuie)
              inner join leche.beneficiarios using (id_beneficiarios)

              where id_beneficiarios<>0 and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and fichero.enfer_epidemeologica is not null and fichero.cuie='$cuie'

              union

              SELECT fichero.fecha_control,
              fichero.cuie,
              efe_conv.nombre as efector,
              smiafiliados.afinombre,
              smiafiliados.afiapellido,
              smiafiliados.afidni,
              smiafiliados.afifechanac,
              extract (year from age(fichero.fecha_control,smiafiliados.afifechanac)) as edad_anio,
              extract (month from age(fichero.fecha_control,smiafiliados.afifechanac)) as edad_meses,
              smiafiliados.afisexo,
              smiafiliados.".'"afiDomCalle"'."||'N°'||smiafiliados.".'"afiDomNro"'." as direccion,
              fichero.enfer_epidemeologica
              --esquema vacuna  

              from fichero.fichero 
              inner join nacer.efe_conv using (cuie)
              inner join nacer.smiafiliados using (id_smiafiliados)

              where id_smiafiliados<>0 and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and fichero.enfer_epidemeologica is not null and fichero.cuie='$cuie'
              order by 1";
              
      };
      
$res_comprobante=sql($sql_tmp) or fin_pagina();

excel_header("excel_epi.xls");

?>
<form name=form1 method=post action="excel_epi.php">
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
  <tr bgcolor=#C0C0FF>
    <td >Efec</td>
      <td >Nombre Efector</td>
      <td >Fecha Control</td>
      <td >Apellido</td>
      <td >Nombre</td>
      <td >Nro. Doc</td>
      <td >Fecha Nac.</td>
      <td >Edad Años</td>
      <td >Edad Meses</td>
      <td >Sexo</td>
      <td >Direccion</td>
      <td >Patologia</td>
  </tr>
  <?   
  while (!$res_comprobante->EOF) {?>  
    <tr>     
     <td><?=$res_comprobante->fields['cuie']?></td> 
        <td><?=$res_comprobante->fields['efector']?></td>
        <td><?=fecha($res_comprobante->fields['fecha_control'])?></td>
        <td><?=$res_comprobante->fields['apellido']?></td>        
        <td><?=$res_comprobante->fields['nombre']?></td>
        <td><?=$res_comprobante->fields['documento']?></td>
        <td><?=fecha($res_comprobante->fields['fecha_nac'])?></td>
        <td><?=$res_comprobante->fields['edad_anio']?></td>
        <td><?=$res_comprobante->fields['edad_meses']?></td>
        <td><?=trim($res_comprobante->fields['sexo'])?></td>
        <td><?=$res_comprobante->fields['domicilio']?></td>
        <td><?=$res_comprobante->fields['enfer_epidemeologica']?></td>      
    </tr>
  <?$res_comprobante->MoveNext();
}?>
  
 </table>
 </form>