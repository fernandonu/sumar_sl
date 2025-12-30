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


$sql="SELECT * FROM proteger.egreso inner join proteger.inciso using (id_inciso) 
  where cuie='$cuie' order by id_egreso DESC";

    
$result=sql($sql) or fin_pagina();

$filename="Egreso_".$cuie.".xls";
excel_header($filename);

?>
<form name=form1 method=post action="egreso_excel.php">
<table width="100%">
  <tr>
   <td>
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
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> <tr id="sub_tabla" bgcolor=#C0C0FF>         
      <td align="center">ID</td>
      <td align="center">Servicio</td>
      <td align="center">Monto Prevent.</td>
      <td align="center">Fecha Prevent.</td>  
      <td align="center">Monto Comprom.</td>      
      <td align="center">Fecha Comprom.</td>
      <td align="center">Monto Egreso</td>     
      <td align="center">Fecha Egreso</td>
      <td align="center">Num. de Fact.</td>
      <td align="center">Num. de Exp.</td>
      <td align="center">Pagado</td>
      <td align="center">Comentario</td>
    </tr>  
  
  <?   
  while (!$result->EOF) {?>
    <tr>       
        <td ><?=$result->fields['id_egreso']?></td>
        <td ><?=$result->fields['ins_nombre']?></td>
        <td ><?=number_format($result->fields['monto_preventivo'],2,',','.')?></td>
        <td ><?=($result->fields['fecha_preventivo']=='1800-01-01')?'':fecha($result->fields['fecha_preventivo'])?></td>
        <td align="center"><?=number_format($result->fields['monto_comprometido'],2,',','.')?></td>
        <td ><?=($result->fields['fecha_comprometido']=='1800-01-01')?'':fecha($result->fields['fecha_comprometido'])?></td> 
        <td ><?=number_format($result->fields['monto_egreso'],2,',','.')?></td>
        <td ><?=($result->fields['fecha_egreso']=='1800-01-01')?'':fecha($result->fields['fecha_egreso'])?></td>
        <td ><?=$result->fields['numero_factura']?></td>
        <td ><?=$result->fields['numero_expediente']?></td>       
        <td ><?=$result->fields['pagado']?></td>
        <td ><?=$result->fields['comentario']?></td>
     </tr>
    
  <?$result->MoveNext();
    }?>
 </table>
 </form>