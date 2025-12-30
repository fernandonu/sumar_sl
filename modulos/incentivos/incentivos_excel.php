<?
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


$sql="SELECT * from personal.incentivos where cuie='$cuie'";
    $result = sql($sql) or fin_pagina();
    $filename = "Incentivos_".$cuie.".xls";
    $fecha_hoy = date("Y-m-d H:i:s");
    excel_header($filename);?>
<form name=form1 method=post action="incentivos_excel.php">
<table width="95%" cellspacing=1 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
<td>
<font size=+1><b>Efector: <?echo $cuie?> </b></font> 
<BR><font color="red"><b>Fecha y hora: </b><?=$fecha_hoy;?></font>
</td>
</tr>
<tr><td>
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
       <td>
        <table width="100%">
         <tr>
          <td align="left">
           <b>Total: </b><?=$result->RecordCount();?> 
           </td> 
          </tr>      
        </table>  
       </td>
      </tr>  
     </table> 
     <br>
     <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
      <tr bgcolor=#C0C0FF>
        <td align="center" id=mo>Nombre y Apellido</td>       
        <td align="center" id=mo>CUIL</td>
        <td align="center" id=mo>Activo</td>
        <td align="center" id=mo>Regimen Laboral</td>
        <td align="center" id=mo>Funcion</td> 
        <td align="center" id=mo>Especialidad</td>
        <td align="center" id=mo>Funcion No-Profesional</td>  
      </tr>
      <?   
      while (!$result->EOF) {?>  
        <tr>     
        <td><?=$result->fields['nombre'].' '.$result->fields['apellido']?></td>
         <td align="center"><?=$result->fields['dni']?></td>
         <td align="center"><?=$result->fields['activo']?></td>
         <td ><?=$result->fields['regimen_laboral']?></td>
         <td ><?=$result->fields['funcion']?></td>
         <td ><?=$result->fields['especialidad']?></td>
         <td ><?=$result->fields['mantenimiento']?></td>
       </tr>
      <?$result->MoveNext();
        }?>
     </table>
</form>