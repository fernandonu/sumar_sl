<?php

require_once ("../../config.php");

$id_factura=$parametros["id_factura"];


	$query="SELECT * from facturacion.comprobante
            inner join facturacion.prestacion using (id_comprobante)
            inner join nacer.smiafiliados using (id_smiafiliados)
            inner join facturacion.nomenclador using (id_nomenclador)
            where id_factura=$id_factura";

$result=$db->Execute($query) or die($db->ErrorMsg());

excel_header("detalle_factura_".$id_factura.".xls");

?>
<form name=form1 method=post action="detalle_factura.php">
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
  <tr bgcolor=#C0C0FF>
  	<td align=right >Num Factura</td>
    <td align=right >Comprobante</td>      	
    <td align=right >Prestacion</td>
    <td align="center">Id_afiliado</td>  
    <td align="center">Nombre</td>
    <td align="center">Apellido</td>
    <td align="center">DNI</td>
    <td align="center">fecha_NAC</td>
    <td align="center">Sexo</td>  
    <td align=right >fecha comprobante</td>
    <td align=right >fecha carga</td>
    <td align=right >Id.Nomen.</td>
    <td align=right >Codigo</td>
    <td align=right >Descripcion</td>
    <td align=right >Precio Prest.</td>
    </tr>
  <?   
  while (!$result->EOF) {?>  
    <tr>     
     <td><?=$result->fields['id_factura']?></td>  
     <td><?=$result->fields['id_comprobante']?></td>
     <td><?=$result->fields['id_prestacion']?></td> 
     <td><?=$result->fields['id_smiafiliados']?></td>
     <td><?=$result->fields['afinombre']?></td>
     <td><?=$result->fields['afiapellido']?></td>
     <td><?=$result->fields['afidni']?></td>
     <td><?=$result->fields['afifechanac']?></td>
     <td><?=$result->fields['afisexo']?></td>
     <td><?=$result->fields['fecha_comprobante']?></td> 
     <td><?=$result->fields['fecha_carga']?></td> 
     <td><?=$result->fields['id_nomenclador']?></td>           
     <td><?=$result->fields['codigo']?></td>           
     <td><?=$result->fields['descripcion']?></td>           
     <td><?=$result->fields['precio_prestacion']?></td>
    </tr>
	<?$result->MoveNext();
    }?>
 </table>
 </form>
