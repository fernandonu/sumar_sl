<?
require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
excel_header("informe_no_conforme.xls");

//$id=$_POST['id'];
$sql="SELECT * from calidad.noconformes 
      inner join calidad.tipo_producto on tipo_producto.id_tipo_producto=noconformes.id_tipo_producto
      where id_noconforme=$id";
$res_sql=sql($sql) or fin_pagina();
?>         

<form name=form1 method=post action="detalle_noconf_excel.php">
<input type="hidden" name="id" value="<?=$id?>">
<table width="40%"  border="1" align="center">
<tr>
<td align="center" id="mo" colspan="4"><b>Detalle de Producto No Conforme</b></td>
</tr>
<tr>  
<td colspan="4"><b>ID:<? echo $id; ?></b></td> 
</tr>
<tr>
<td><strong>Producto</strong></td>
<td><?=$res_sql->fields['descripcion'];?></td>
<td>
<strong>Nro de Serie</strong>
</td>
<td align="left"><?=$res_sql->fields['nro_serie'];?></td>
</tr>

<tr>
    <td><strong>Fecha Evento</strong></td>
    <td><?=$res_sql->fields['fecha_evento']?>&nbsp;</td>
    <td><strong>Usuario</strong></td>
    <td><?=$res_sql->fields['usuario'];?></td>
</tr>

<tr>
     <td><strong>Área</strong></td>
     <td colspan="3"><?=$res_sql->fields['area']?></td>
</tr>
   
<tr>
  <td colspan="4" align="center">
  <br>
  <strong>Como se detecto</strong><br>
  <?=$res_sql->fields['texto_deteccion']?>
  </td>
  </tr>
  <tr>
  <td colspan="3">Control Interno en Area</td>
  </td>
  </tr>   
  <tr>
  <td colspan="4" align="center">
  <br>
  <strong>Causa</strong><br>
  <?=$res_sql->fields['descripcion_inconformidad']?>
  </td>
  </tr>
</table> 
</form>