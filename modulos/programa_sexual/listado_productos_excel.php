<?php
require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
$dia_hoy = date("Y-m-d");
excel_header("listado_completo_$dia_hoy.xls");

$sql_1="SELECT 
  stock_producto.id_remedio,
  remedio.codigo,
  remedio.descripcion,
  remedio.producto,
  stock_producto.id_efector,
  efectores.cuie,
  efectores.nombre,
  stock_producto.final
  from remedios.stock_producto
  inner join remedios.remedio on remedio.id_remedio=stock_producto.id_remedio
  inner join remedios.efectores on efectores.id_efector=stock_producto.id_efector
  order by cuie,descripcion";


$sql_tmp="SELECT * from remedios.stock
          inner join remedios.remedio on remedio.id_remedio=stock.id_remedio";
$result = sql($sql_tmp) or die;

?>

<form name="form1" id="form1" action="listado_productos_excel.php" method=POST>
<table cellspacing=3 cellpadding=3 border=0 width="100%" align="center">
</table>

<table border=0 width="100%" cellspacing=2 cellpadding=2 align="center">
  <tr>
  	<td colspan=10 align=left>
     <table  width=100%>
     <tr>
       <td width=10% align=left><b><font size=5 color=#1A34EC>STOCK</font></b></td> 
       <td width=30% align=left><b><font size=5 color=#1A34EC>COMPLETO PROVINCIA: </font></b></td>
       <td width=30% align=left><b><font size=5 color=#1A34EC>SAN LUIS</font></b></td> 
       <td width=10% align=left><b><font size=5 color=#1A34EC>Corte Realizado:</font></b></td>
       <td width=5% align=left><b><font size=5 color=#1A34EC><?=$dia_hoy?></font></b></td>
       </tr> 
    </table>
   </td>
  </tr>


    
  <table  class="table table-striped" width=80% border="1">
  <tr>
  	<td align="center" id="mo">Codigo</a></td>      	    
    <td align="center" id="mo">Descripcion</a></td>      	    
    <td align="center" id="mo">Producto</a></td>
    <? $sql_efectores="SELECT * from remedios.efectores order by cuie";
      $res_efectores=sql($sql_efectores) or die;
      while (!$res_efectores->EOF){
      $cuie=$res_efectores->fields['cuie'];
      $nombre=$res_efectores->fields['nombre'];?>
      
      <td align="right" id="mo" data-toggle="tooltip" data-placement="top" title="<?=$nombre?>"><?=$cuie?></a></td>
      <?$res_efectores->MoveNext();
      }?>
    </tr>
    <?    
    while (!$result->EOF) {?>

          
      <td align="center"><?=$result->fields['codigo']?></td>        
      <td><?=$result->fields['descripcion']?></td>     
      <td><?=$result->fields['producto']?></td>
      <?$res_efectores->movefirst();
        while (!$res_efectores->EOF){
        $cuie=strtolower($res_efectores->fields['cuie']);
        $nombre=$res_efectores->fields['nombre'];
        $stock=($result->fields[$cuie])?$result->fields[$cuie]:-1;
    /*if ($stock>150 and $stock<500) $bg_color="#80EE73"; bgcolor='<?=$bg_color?>'
    if ($stock>50 and $stock<=150) $bg_color="#EEEB73";
    if ($stock>=0 and $stock<=50) $bg_color="#EF9272";*/?>
       
    <td align="center" data-toggle="tooltip" data-placement="top" title="<?=$nombre?>"><?=$result->fields[$cuie]?></td>
    <?$res_efectores->MoveNext();
     }?>
     </tr>
	 <?$result->MoveNext();
    $res_efectores->movefirst();
   }?>
  </table>
    
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
