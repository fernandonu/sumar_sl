<?php

require_once ("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);


$query ="SELECT * from nacer.efe_conv where cuie='$cuie'";
$res_factura=sql($query, "Error al traer el Efector") or fin_pagina();

$cuie=$res_factura->fields['cuie'];
$nombre=$res_factura->fields['nombre'];
$domicilio=$res_factura->fields['domicilio'];
$departamento=$res_factura->fields['dpto_nombre'];
$localidad=$res_factura->fields['localidad'];
$cod_pos=$res_factura->fields['cod_pos'];
$cuidad=$res_factura->fields['cuidad'];
$referente=$res_factura->fields['referente'];
$tel=$res_factura->fields['tel'];



$sql="SELECT 
  *
FROM
  proteger.ingreso  
  where ingreso.cuie='$cuie' 
  order by id_ingreso DESC";
    
$result=sql($sql) or fin_pagina();
$filename="Ingreso_".$cuie.".xls";
excel_header($filename);

?>
<form name=form1 method=post action="ingreso_excel.php">
<table width="100%">
  <table width=100% align="center" class="bordes">
<tr>
<td id=mo colspan="5" align="center">

</td>
</tr>
<tr>
<td>
<table align="center">
<td align="right"><b>Nombre:</b></td>
<td align="left"><font color="blue"><?=utf8_decode($nombre)?></font></td>
</tr>

<tr>
<td align="right"><b>Domicilio:</b></td>
<td align="left"><font color="blue"><?=utf8_decode($domicilio)?></font></td>
</tr>

<tr>
<td align="right"><b>Departamento:</b></td>
<td align="left"><font color="blue"><?=$departamento?></font></td>
</tr>

<tr>
<td align="right">
<b>Localidad:</b>
</td>
<td align="left"><font color="blue"><?=$localidad?></font></td>
</tr>
</table>
</td>      

<td>
<table align="center">        
<tr>
<td align="right"><b>Codigo Postal:</b></td>
<td align="left"><font color="blue"><?=$cod_pos?></font></td>
</tr>

<tr>
<td align="right"><b>Cuidad:</b></td>
<td align="left"><font color="blue"><?=$cuidad?></font></td>
</tr>

<tr>
<td align="right"><b>Referente:</b></td>
<td align="left"><font color="blue"><?=utf8_decode($referente)?></font></td>
</tr>

<tr>
<td align="right"><b>Telefono:</b></td>
<td align="left"><font color="blue"><?=$tel?></font></td>
</tr>          
</table>
</td>  
</tr> 
</table>
  
  
  
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total: </b><?=$result->RecordCount();?> 
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5">   
  <tr id="sub_tabla" bgcolor=#C0C0FF>         
      <td width="10%" align="center">ID</td>
      <td width="15%" align="center">Numero Exp.</td>
      <td width="15%" align="center">Periodo</td>
      <td width="15%" align="center">Monto Recurso</td>
      <td width="15%" align="center">Fecha Deposito</td>
      <td width="15%" align="center">Fecha Notificacion</td>
      <td width="15%" align="center">Comentario</td>
    </tr>
  <?   
  while (!$result->EOF) {?>
    <tr>        
        <td><?=$result->fields['id_ingreso']?></td>
        <td><?=$result->fields['numero_expediente']?></td>
        <td><?=$result->fields['periodo']?></td>
        <td align="center"><?=number_format($result->fields['monto_recurso'],2,',','.')?></td>
        <td><?=fecha($result->fields['fecha_deposito'])?></td>
        <td><?=fecha($result->fields['fecha_notificacion'])?></td>
        <td><?=$result->fields['comentario']?></td>
      </tr>
  <?$result->MoveNext();
  }?>
 </table>
 </form>