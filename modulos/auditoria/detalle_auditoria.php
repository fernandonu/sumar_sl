<?php
/*
$Author: Seba $
$Revision: 3.0 $
$Date: 2016/12/28 $
*/

require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

if ($cuie=='todos') {
    $sql_tmp="SELECT * from facturacion.prestacion 
            inner join facturacion.comprobante using (id_comprobante) 
            full outer join nacer.smiafiliados using (id_smiafiliados)
            where id_nomenclador=$id_nomenclador
            and comprobante.marca=0
            and fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
            and id_smiafiliados is not null
            order by 1,fecha_comprobante";
    }
    else {
      $sql_tmp="SELECT * from facturacion.prestacion 
            inner join facturacion.comprobante using (id_comprobante) 
            full outer join nacer.smiafiliados using (id_smiafiliados)
            where id_nomenclador=$id_nomenclador
            and comprobante.marca=0
            and fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
            and cuie='$cuie'
            and id_smiafiliados is not null
            order by 1,fecha_comprobante";
      
    };
        
$result=sql($sql_tmp) or fin_pagina();


echo $html_header;
?>

<form name=form1 method=post action="detalle_auditoria.php">
<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align="left">
       <b>Total de Beneficiarios: <?=$result->RecordCount();?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="90%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
  <tr bgcolor=#C0C0FF>
    <td align="right" id="mo">DNI</td>        
    <td align="right" id="mo">Apellido</td>       
    <td align="right" id="mo">Nombre</td>       
    <td align="center" id="mo">Fecha Nac.</td>
    <td align="center" id="mo">Sexo</td>
    <td align="center" id="mo">Fec.Comproban.</td>
    </tr>
  <?   
  while (!$result->EOF) {
    $id_smiafiliados=$result->fields['id_smiafiliados'];?>   
  
  <tr>          
    <td><?=$result->fields['afidni']?></td>
    <td><?=$result->fields['afiapellido']?></td>
    <td><?=$result->fields['afinombre']?></td>
    <td align="center"><?=fecha($result->fields['afifechanac'])?></td> 
    <td align="center"><?=$result->fields['afisexo']?></td>
    <td align="center"><?=fecha($result->fields['fecha_comprobante'])?></td>
  </tr>
  
  <?$result->MoveNext();
    }?>
 </table>
 </form>
 <?=fin_pagina();// aca termino ?>