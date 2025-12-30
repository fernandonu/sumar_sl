<?
require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
$dia_hoy = date("Y-m-d");

if ($id_efector) {
$sql_efector="SELECT * from nacer.efe_conv where id_efe_conv=$id_efector";
$res_efector=sql($sql_efector) or fin_pagina();
$cuie=$res_efector->fields['cuie'];
$nombre=utf8_decode($res_efector->fields['nombre']);
$domicilio=utf8_decode($res_efector->fields['domicilio']);
$departamento=$res_efector->fields['dpto_nombre'];
$localidad=$res_efector->fields['localidad'];
$cod_pos=$res_efector->fields['cod_pos'];
$cuidad=$res_efector->fields['cuidad'];
$referente=$res_efector->fields['referente'];
$tel=$res_efector->fields['tel'];

$nombre_archivo="Resumen_Mensual_".$cuie."_".$dia_hoy.".xls";
excel_header($nombre_archivo);


$sql_stock="SELECT * from programa_sexual.stock_producto 
            inner join programa_sexual.remedio on stock_producto.id_remedio=remedio.id_remedio
            where stock_producto.id_efector=$id_efector order by 3";
$result=sql($sql_stock,"No se pudo ejecutar la consulta sobre el stock") or fin_pagina();

$sql_comprobantes1="SELECT * from programa_sexual.proceso_archivo 
                    where id_efector=$id_efector order by 1 DESC";
$res_comprobantes1=sql($sql_comprobantes1) or fin_pagina();

if ($res_comprobantes1->recordcount()>0) {

  $fecha_ultima_entrega=$res_comprobantes1->fields['fecha_corte'];
  $sql_consultas="SELECT count (*) as total from programa_sexual.comprobantes 
                    where cuie='$cuie' and fecha_entrega between '$fecha_ultima_entrega' and '$dia_hoy'";
  $res_consultas=sql($sql_consultas,"no se pudo ejecutar la consulta de comprobantes") or fin_pagina();
  $consulta=($res_consultas->fields['total'])?$res_consultas->fields['total']:0;
}
  else {
    $sql_consultas="SELECT count (*) as total from programa_sexual.comprobantes 
                    where cuie='$cuie'";
    $res_consultas=sql($sql_consultas,"no se pudo ejecutar la consulta de comprobantes") or fin_pagina();
    $consulta=($res_consultas->fields['total'])?$res_consultas->fields['total']:0;
  }

//codigo de insercion al proceso archivos del boton resumen mensual
$q1="select nextval('programa_sexual.proceso_archivo_id_proceso_seq') as id_proceso";
$res_proceso=sql($q1) or fin_pagina();
$id_proceso=$res_proceso->fields['id_proceso'];
$sql_insert="INSERT INTO programa_sexual.proceso_archivo (id_proceso,id_efector,fecha_corte,consultas) 
              VALUES ($id_proceso,$id_efector,'$dia_hoy',$consulta)";
sql($sql_insert) or fin_pagina();


}

else {
  excel_header("listado de Productos_Commpleto_$dia_hoy.xls");
  $sql_stock="SELECT * from (
          select id_remedio,sum(inicial) as inicial,sum(remito) as remito,
          sum(clearing) as clearing, sum(total_1) as total_1,
          sum(u_entregadas) as u_entregadas,sum(salida_clearing) as salida_clearing,
          sum(salida_no_apto) as salida_no_apto,sum (salida_robo) as salida_robo,
          sum (total_2) as total_2, sum (final) as final
          from programa_sexual.stock_producto group by 1 
          ) as stock
          inner join programa_sexual.remedio on stock.id_remedio=remedio.id_remedio
          order by descripcion";
  $result=sql($sql_stock,"No se pudo ejecutar la consulta sobre el stock") or fin_pagina(); 
  
  $sql_comprobantes="SELECT count (*) as total from programa_sexual.comprobantes";
  $res_comprobante=sql($sql_comprobantes,"no se pudo ejecutar la consulta de comprobantes") or fin_pagina();
}
 
?>

<form name=form1 method=post action="resumen_mensual.php">
<table width="95%" cellspacing=1 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
<td align="center">
<font size=+1><b>RESUMEN MENSUAL</b></font> 
<BR><font color="red"><b>Fecha de corte: </b><?=$dia_hoy;?></font>
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
<td align="right"><b>Efector:</b></td>
<td align="left"><font color="blue"><?=$nombre?></font></td>
</tr>

<tr>
<td align="right"><b>Domicilio:</b></td>
<td align="left"><font color="blue"><?=$domicilio?></font></td>
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

   
<br>
&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;   
  <table  class="table table-striped" width=80% border="1">
  <tr>
               
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
      
    <?
    $id_stock_producto=$result->fields['id_stock_producto'];
    $sql_update="UPDATE programa_sexual.stock_producto SET 
                  inicial=final, 
                  total_1=final,
                  remito=0,
                  clearing=0, 
                  u_entregadas=0,
                  salida_clearing=0,
                  salida_no_apto=0,
                  salida_robo=0,
                  total_2=0
                  where id_stock_producto=$id_stock_producto";
    sql($sql_update) or fin_pagina();              
    $result->MoveNext();
    }?>
</table>
</form>