<?php

require_once ("../../config.php");

$fecha_hasta=$parametros["fecha_hasta"];


$sql="SELECT 
  nacer.efe_conv.cuie,
  nacer.efe_conv.nombre,
  sum(proteger.ingreso.monto_recurso) AS ingreso
FROM
  nacer.efe_conv
  INNER JOIN proteger.ingreso ON (nacer.efe_conv.cuie = proteger.ingreso.cuie)
WHERE
  (ingreso.fecha_deposito <= '$fecha_hasta' and efe_conv.proteger='S')
GROUP BY
  nacer.efe_conv.cuie,
  nacer.efe_conv.nombre
ORDER BY
  nacer.efe_conv.cuie";
$ing=sql($sql) or fin_pagina();

$sql="SELECT 
  nacer.efe_conv.cuie,
  nacer.efe_conv.nombre,
  proteger.inciso.ins_nombre,
  sum (proteger.egreso.monto_egreso) as monto
  
FROM
  nacer.efe_conv
  INNER JOIN proteger.egreso ON (nacer.efe_conv.cuie = proteger.egreso.cuie)
  INNER JOIN proteger.inciso ON (proteger.egreso.id_inciso = proteger.inciso.id_inciso)
WHERE
  proteger.egreso.fecha_egreso <= '$fecha_hasta' 
  --and egreso.monto_egre_comp <> 0
  and efe_conv.proteger='S'
GROUP BY
  proteger.inciso.ins_nombre,
  nacer.efe_conv.cuie,
  nacer.efe_conv.nombre
  
ORDER BY
  nacer.efe_conv.cuie,inciso.ins_nombre";
$egre=sql($sql) or fin_pagina();

excel_header("ingreso_egreso.xls");

?>
<form name=form1 method=post action="ingre_egre_excel2.php">
<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total Ingresos por CAPS: </b><?=$ing->RecordCount();?> 
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id=mo>CUIE</td>      	
    <td align=right id=mo>Efector</td>
    <td align=right id=mo>Total</td>      
  </tr>
  <?   
  while (!$ing->EOF) {?>  
    <tr>     
     <td ><?=$ing->fields['cuie']?></td>
     <td ><?=$ing->fields['nombre']?></td>
     <td ><?=number_format($ing->fields['ingreso'],'2',',','.')?></td>     
    </tr>
	<?$ing->MoveNext();
    }?>
 </table>
  <br>
  <br>
  <br>
 <table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total Egresos por Inciso: </b><?=$egre->RecordCount();?> 
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id=mo>CUIE</td>      	
    <td align=right id=mo>Efector</td>
    <td align=right id=mo>Inciso</td>
    <td align=right id=mo>Total</td>      
  </tr>
  <?   
  while (!$egre->EOF) {?>  
    <tr>     
     <td ><?=$egre->fields['cuie']?></td>
     <td ><?=$egre->fields['nombre']?></td>
     <td ><?=$egre->fields['ins_nombre']?></td>
     <td ><?=number_format($egre->fields['monto'],'2',',','.')?></td>     
    </tr>
	<?$egre->MoveNext();
    }?>
 </table>
 
 </form>
