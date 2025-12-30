<?php

require_once ("../../config.php");

$id_factura=$parametros["id_factura"];


	$query="SELECT id_smiafiliados,afidni,fecha_comprobante,codigo,descripcion, count(*) as cantidad from facturacion.comprobante
        inner join facturacion.prestacion using (id_comprobante)
        inner join facturacion.nomenclador using (id_nomenclador)
        inner join nacer.smiafiliados using (id_smiafiliados)
        where comprobante.id_factura=$id_factura
        group by 1,2,3,4,5
        having count(*) >=2";


  
$result=$db->Execute($query) or die($db->ErrorMsg());

excel_header("inconsistencias.xls");

?>
<form name=form1 method=post action="analisis_inconsistencia.php">
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
  <tr bgcolor=#C0C0FF>
  	<td align=right >Num.Factura</td>
    <td align="center">Id_afiliado</td>    
    <td align="center">AFIdni</td>
    <td align=right >fecha comprobante</td>
    <td align=right >Codigo</td>
    <td align=right >Descripcion</td>
    <td align=right >Cantidad</td>
    </tr>
  <?   
  while (!$result->EOF) {?>  
    <tr>     
     <td><?=$id_factura?></td>  
     <td><?=$result->fields['id_smiafiliados']?></td>
     <td><?=$result->fields['afidni']?></td>
     <td><?=$result->fields['fecha_comprobante']?></td> 
     <td><?=$result->fields['codigo']?></td>           
     <td><?=$result->fields['descripcion']?></td>           
     <td><?=$result->fields['cantidad']?></td>
    </tr>
	<?$result->MoveNext();
    }?>
 </table>
 </form>
