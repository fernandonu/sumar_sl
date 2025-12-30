<?
/*
$Author: Seba $
$Revision: 3.0 $
$Date: 2016/12/28 $
*/

require_once ("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

if ($cuie=='todos') {
      $cuie="Todos";
      $nombre=NULL;
      $domicilio=NULL;
      $departamento=NULL;
      $localidad=NULL;
      $cod_pos=NULL;
      $cuidad=NULL;
      $referente=NULL;
      $tel=NULL;
      
      $sql="SELECT grupo,codigo,descripcion,count(id_nomenclador) as cantidad,nomenclador.precio, sum(nomenclador.precio) as total 
            from facturacion.prestacion 
            inner join facturacion.comprobante using (id_comprobante)
            inner join facturacion.nomenclador using (id_nomenclador)
            where fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
            and comprobante.marca=0
            and comprobante.id_smiafiliados is not null
            group by grupo,codigo,descripcion,nomenclador.precio
            order by 4 DESC";
      
      $result=sql($sql) or fin_pagina();
    }
    else {
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
      
      
      $sql="SELECT grupo,codigo,descripcion,count(id_nomenclador) as cantidad,nomenclador.precio, sum(nomenclador.precio) as total 
            from facturacion.prestacion 
            inner join facturacion.comprobante using (id_comprobante)
            inner join facturacion.nomenclador using (id_nomenclador)
            where fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
            and comprobante.cuie='$cuie'
            and comprobante.marca=0
            and comprobante.id_smiafiliados is not null
            group by grupo,codigo,descripcion,nomenclador.precio
            order by 4 DESC";
      
      $result=sql($sql) or fin_pagina();
      
    }

    $filename = "Auditoria_".$cuie.".xls";
    $fecha_hoy = date("Y-m-d H:i:s");
    excel_header($filename);?>
<form name=form1 method=post action="auditoria_excel.php">
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
     <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
      <tr bgcolor=#C0C0FF>
        <td align="left" id=mo>Grupo</td>       
        <td align="left" id=mo>Codigo</td>       
        <td align="left" id=mo>Descripcion</td>
        <td align="center" id=mo>Precio Unit.</td>
        <td align="center" id=mo>Cantidad</td>
        <td align="center" id=mo>Total</td>
      </tr>
      <?   
      while (!$result->EOF) {?>  
        <tr>     
        <td><?=$result->fields['grupo']?></td>
         <td><?=$result->fields['codigo']?></td>
         <td><?=utf8_decode($result->fields['descripcion'])?></td>
         <td><?=number_format($result->fields['precio'],2,',','.')?></td>
         <td ><?=number_format($result->fields['cantidad'],0,',','.')?></td>
         <td ><?=number_format($result->fields['total'],2,',','.')?></td>
        </tr>
      <?$result->MoveNext();
        }?>
     </table>
</form>