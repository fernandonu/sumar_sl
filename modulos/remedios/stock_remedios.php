<?


require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
$dia_hoy = date("Y-m-d");

$consultas=$_GET["consultas"];


if ($id_efector) {
$sql_efector="SELECT * from remedios.efectores where id_efector=$id_efector";
$res_efector=sql($sql_efector) or fin_pagina();
$cuie=$res_efector->fields['cuie'];

$nombre_archivo="listado de medicamentos_".$cuie."_".$dia_hoy.".xls";
excel_header($nombre_archivo);


$sql_stock="SELECT * from remedios.stock_producto 
            inner join remedios.remedio on stock_producto.id_remedio=remedio.id_remedio
            where stock_producto.id_efector=$id_efector order by 3";
$result=sql($sql_stock,"No se pudo ejecutar la consulta sobre el stock") or fin_pagina();


$sql_efector="SELECT * from remedios.efectores
            inner join remedios.proceso_archivo on proceso_archivo.id_efector=efectores.id_efector
            where efectores.id_efector=$id_efector
            order by id_proceso DESC";
$res_efector=sql($sql_efector,"no se pudo ejecutar la consulta sobre los datos de efector") or fin_pagina();
$cuie=$res_efector->fields['cuie'];
//$consultas=$res_efector->fields['consultas'];
$fecha_corte=$res_efector->fields['fecha_corte'];


$sql_comprobantes="SELECT count (*) as total from remedios.comprobantes where cuie='$cuie'";
$res_comprobante=sql($sql_comprobantes,"no se pudo ejecutar la consulta de comprobantes") or fin_pagina();
}

else {
  excel_header("listado de remedios_Completo_$dia_hoy.xls");
  $sql_stock="SELECT * from (
          select id_remedio,sum(inicial) as inicial,sum(remito) as remito,
          sum(clearing) as clearing, sum(total_1) as total_1,
          sum(u_entregadas) as u_entregadas,sum(salida_clearing) as salida_clearing,
          sum(salida_no_apto) as salida_no_apto,sum (salida_robo) as salida_robo,
          sum (total_2) as total_2, sum (final) as final
          from remedios.stock_producto group by 1 
          ) as stock
          inner join remedios.remedio on stock.id_remedio=remedio.id_remedio
          order by descripcion";
  $result=sql($sql_stock,"No se pudo ejecutar la consulta sobre el stock") or fin_pagina(); 
  
  $sql_comprobantes="SELECT count (*) as total from remedios.comprobantes";
  $res_comprobante=sql($sql_comprobantes,"no se pudo ejecutar la consulta de comprobantes") or fin_pagina();
}
 
?>

<form name=form1 method=post action="stock_remedios.php">
<table border=1 width=100% cellspacing=2 cellpadding=2 align=center>
  <tr>
    <td colspan=10 align=left>
    &nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;
     <table  width=100%>
       <?if ($id_efector) {?>
       <tr>
       <td width=10% align=left><b><font size=5 color=#1A34EC> <?=$res_efector->fields['cuie']?></font></b></td> 
       <td width=30% align=left><b><font size=5 color=#1A34EC> <?=$res_efector->fields['nombre']?></font></b></td> 
       <td width=30% align=left><b><font size=5 color=#1A34EC> <?=$res_efector->fields['localidad']?></font></b></td>
       <td width=30% align=left><b><font size=5 color=#1A34EC> <?=$res_efector->fields['departamento']?></font></b></td>     
       </tr>
       <tr>
       <td width=10% align=left><b><font size=5 color=#F33854>Corte Realizado Desde: </font></b></td>
       <td width=10% align=left><b><font size=5 color=#F33854><?=Fecha($fecha_corte)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a</font></b></td>
       <td width=5% align=left><b><font size=5 color=#F33854><?=$dia_hoy?></font></b></td>
       </tr>
       <?}
       else {?>
       <tr>
       <td width=10% align=left><b><font size=5 color=#1A34EC>STOCK</font></b></td> 
       <td width=30% align=left><b><font size=5 color=#1A34EC>COMPLETO PROVINCIA: </font></b></td>
       <td width=30% align=left><b><font size=5 color=#1A34EC>SAN LUIS</font></b></td> 
       </tr>
       <tr>
       <td width=10% align=left><b><font size=5 color=#F33854>Corte Realizado Desde: </font></b></td>
       <td width=10% align=left><b><font size=5 color=#F33854><?=Fecha($fecha_corte)?>&nbsp;&nbsp;&nbsp;a</font></b></td>
       <td width=5% align=left><b><font size=5 color=#F33854><?=$dia_hoy?></font></b></td>
       </tr>
       <?}?>
    </table>
   </td>
  </tr>

<table border=1 width=100% cellspacing=2 cellpadding=2 align=center>
&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;
<tr>
       <td width=30% align=left><b> Cantidad de Consultas:</b></td> 
       <td width=30% align=left><i> <?=$consultas?></i></td>
       <td></td>
       <td width=30% align=left><b> Cantidad de Recetas:</td> 
       <td width=30% align=left><b> <?=$res_comprobante->fields['total']?></td>      
</tr>

</table>
&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;   
  <table  class="table table-striped" width=80% border="1">
  <tr>
    <td align="center" id="mo"><b>Codigo</b></td>           
    <td align="center" id="mo"><b>Descripcion</b></td>            
    <td align="center" id="mo"><b>Producto</b></td>
    <td align="center" id="mo"><b>Stock Inicial</b></td>
    <td align="center" id="mo" colspan="3"><b>Unidades Recividas</b></td>
    <td align="right" id="mo"><b>U. Entregadas</b></td>
    <td align="center" id="mo" colspan="4"><b>Otras Salidas</b></td>
    <td align="right" id="mo"><b>Total</b></td>
    <td align="right" id="mo"><b>Stock Final</b></td>
    </tr>

    <tr>
    <td align="right" id="mo"></td>           
    <td align="right" id="mo"></td>            
    <td align="right" id="mo"></td>
    <td align="right" id="mo"></td>
    <td align="right" id="mo"><b>Remito</b></td>
    <td align="right" id="mo"><b>Cleaning</b></td>
    <td align="right" id="mo"><b>Total</b></td>
    <td align="right" id="mo"></td>
    <td align="right" id="mo"><b>Cleaning</b></td>
    <td align="right" id="mo"><b>No Apto</b></td>
    <td align="center" id="mo"><b>Observ.</b></td>
    <td align="right" id="mo"><b>Robo</b></td>
    <td align="right" id="mo"></td>
    <td align="right" id="mo"></td>
    </tr>

<?   while (!$result->EOF) {?>
    <tr>
    <td align="center"><?=$result->fields['codigo']?></td>        
     <td><?=$result->fields['descripcion']?></td>     
     <td><?=$result->fields['producto']?></td>     
     <td align="center"><?=$result->fields['inicial']?></td>
     <td align="center"><?=$result->fields['remito']?></td> 
     <td align="center"><?=$result->fields['clearing']?></td>
     <td align="center"><?=$result->fields['total_1']?></td>
     <td align="center"><?=$result->fields['u_entregadas']?></td>
     <td align="center"><?=$result->fields['salida_clearing']?></td>
     <td align="center"><?=$result->fields['salida_no_apto']?></td>
     <td align="center"><?=$result->fields['observaciones_no_apto']?></td>
     <td align="center"><?=$result->fields['salida_robo']?></td>
     <td align="center"><?=$result->fields['total_2']?></td>
     <td align="center"><b><?=$result->fields['final']?></b></td>
      </tr>

    <?$result->MoveNext();
    }?>
</table>
</form>