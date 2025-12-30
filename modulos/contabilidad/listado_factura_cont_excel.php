<?php

require_once ("../../config.php");

function ultimoDia($mes,$ano){ 
    if ($mes=='02')$ultimo_dia=28;
  else if ($mes=='04' or $mes=='06'or $mes=='09' or $mes=='11') $ultimo_dia=30;
  else $ultimo_dia=31;
    while (checkdate($mes,$ultimo_dia + 1,$ano)){ 
       $ultimo_dia++; 
    } 
    return $ultimo_dia; 
}
  $calend=array (
  1 => "Ene",
  2 => "Feb",
  3 => "Mar", 
  4 => "Abr", 
  5 => "May",  
  6 => "Jun", 
  7 => "Jul" ,
  8 => "Ago",
  9 => "Sep",
  10 => "Oct",
  11 => "Nov",  
  12 => "Dic"
  );

$periodo_actual=$parametros['periodo'];
$anio=substr($periodo_actual,0,4);
$mes=substr($periodo_actual,5,2); 
$fecha_desde=ereg_replace('/','-',$periodo_actual).'-01'; 
$fecha_hasta=ereg_replace('/','-',$periodo_actual).'-'.ultimoDia($mes,$anio);

$sql="SELECT
    nacer.efe_conv.nombre,
    facturacion.factura.cuie,
    facturacion.factura.id_factura,
    facturacion.factura.fecha_factura,
    facturacion.factura.monto_prefactura,
    facturacion.factura.periodo,
    facturacion.factura.alta_comp,
    contabilidad.ingreso.comentario,
    facturacion.factura.nro_exp_ext,
    facturacion.factura.fecha_exp_ext,
    facturacion.factura.periodo_contable,
    contabilidad.ingreso.fecha_deposito,
    expediente.expediente.nro_exp,
    expediente.expediente.fecha_ing,
    contabilidad.ingreso.fecha_notificacion
    FROM
    facturacion.factura
    LEFT JOIN nacer.efe_conv using (cuie)
      LEFT JOIN contabilidad.ingreso ON (facturacion.factura.id_factura = contabilidad.ingreso.numero_factura)
    LEFT JOIN expediente.expediente ON expediente.expediente.id_factura = facturacion.factura.id_factura
    WHERE
    ((facturacion.factura.estado = 'C') AND
    (contabilidad.ingreso.fecha_deposito between '$fecha_desde' and '$fecha_hasta')) AND
    (expediente.control=5)
    ORDER BY
    facturacion.factura.nro_exp_ext ASC,
    facturacion.factura.id_factura DESC";


//(contabilidad.ingreso.fecha_deposito between '$fecha_desde' and '$fecha_hasta'))
  

$result=sql($sql) or fin_pagina();

excel_header("listado de facturas cerradas.xls");

?>
<form name=form1 method=post action="listado_factura_cont_excel.php">
<table width="100%">
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
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">Efector</td>
    <td align=right id="mo">CUIE</td>
    <td align=right id="mo">Num. Fact</td>
    <td align=right id="mo">Fecha Factura</td>
    <td align=right id="mo">Monto Prefactura</td>
    <td align=right id="mo">Corresp. al Mes</td> 
    <td align=right id="mo">Subtotal Normal</td>
    <td align=right id="mo">Subtotal Hombres</td>   
    <td align=right id="mo">Total</td>    
    <td align=right id="mo">Num. Exp. Externo</td>    
    <td align=right id="mo">Fecha. Exp. Externo</td>    
    <td align=right id="mo">Fecha Deposito</td> 
    <td align=right id="mo">Nº Exp Interno</td>    
    <td align=right id="mo">Fecha de Ingreso a la UGSP</td>  
    <td align=right id="mo">Fecha de Notificacion</td>     
  </tr>
  <?   
  while (!$result->EOF) {
    $id_factura=$result->fields['id_factura'];

    if ($result->fields['alta_comp']=='SI'){
      $color_1='#A9BCF5'; 
      $title_1='FACTURA ALTA COMPLEJIDAD';
     }else{
      $color_1=''; 
      $title_1='FACTURA PDSPS';
    };
    

   $sql_monto="SELECT prestacion.id_comprobante,
          prestacion.id_nomenclador,
          prestacion.cantidad,
          prestacion.precio_prestacion,
          nomenclador.normal,
          smiafiliados.afinombre,
          smiafiliados.afiapellido,
          smiafiliados.afidni,
          smiafiliados.afifechanac,
          comprobante.fecha_comprobante,
          --extract(year from (age(comprobante.fecha_comprobante,smiafiliados.afifechanac))) as edad_anio,
          --smiafiliados.afisexo,
          --smiafiliados.grupopoblacional,
          comprobante.grupo_etareo,
          nomenclador.grupo,
          nomenclador.descripcion,  
          cantidad*prestacion.precio_prestacion as neto
          from facturacion.factura
          inner join facturacion.comprobante on comprobante.id_factura=factura.id_factura
          inner join facturacion.prestacion on prestacion.id_comprobante=comprobante.id_comprobante
          inner join facturacion.nomenclador on prestacion.id_nomenclador=nomenclador.id_nomenclador
          inner join nacer.smiafiliados using (id_smiafiliados)
          where factura.id_factura=$id_factura";

$rs_sql_monto=sql($sql_monto,"No se pudo ejecutar la consulta sobre los montos de la factura") or fin_pagina();

$rs_sql_monto->movefirst();
$monto_normal=0;
$monto_hombre=0;
while (!$rs_sql_monto->EOF) {
  $grupo=$rs_sql_monto->fields['grupo_etareo'];
  switch ($grupo) {
    case 'A':$monto_normal=$monto_normal+$rs_sql_monto->fields['neto'];break;
    case 'B':$monto_normal=$monto_normal+$rs_sql_monto->fields['neto'];break;
    case 'C':$monto_normal=$monto_normal+$rs_sql_monto->fields['neto'];break;
    case 'D':$monto_normal=$monto_normal+$rs_sql_monto->fields['neto'];break;
    case 'E': $monto_hombre=$monto_hombre+$rs_sql_monto->fields['neto'];break;
    default:  $monto_normal=$monto_normal+$rs_sql_monto->fields['neto'];break;
  };
  
$rs_sql_monto->movenext();
};

if ($monto_hombre){
      $color_1='#F1EE69'; 
      $title_1='FACTURA CON PRESTACIONES DE HOMBRES';
     };
?>


    <tr>
     <td bgcolor="<?=$color_1?>" title="<?=$title_1?>"><?=$result->fields['nombre']?></td>  
     <td bgcolor="<?=$color_1?>" title="<?=$title_1?>"><?=$result->fields['cuie']?></td>        
     <td bgcolor="<?=$color_1?>" title="<?=$title_1?>"><?=$result->fields['id_factura']?></td>     
     <td bgcolor="<?=$color_1?>" title="<?=$title_1?>"><?=fecha($result->fields['fecha_factura'])?></td>  
     <td bgcolor="<?=$color_1?>" title="<?=$title_1?>"><?=number_format($result->fields['monto_prefactura'],2,',','.')?></td>  
     
     <?$periodo_con=$result->fields['periodo_contable'];
     $periodo_con=explode("/",$periodo_con);
     $anio=$periodo_con[0]-2000;
     $mes=(int)$periodo_con[1];
     $mes_string=$calend[$mes];
     $fecha_string=$mes_string."-".$anio;
     ?>

     <td bgcolor="<?=$color_1?>" title="<?=$title_1?>"><?=$fecha_string?></td>
     <td align="center"><?=number_format($monto_normal,2,',','.');?></td>
     <td align="center"><?=number_format($monto_hombre,2,',','.');?></td>
 



 <? $id_factura=$result->fields['id_factura'];
      $query_t="SELECT sum 
      (facturacion.prestacion.cantidad*facturacion.prestacion.precio_prestacion) as total
      FROM
        facturacion.factura
        INNER JOIN facturacion.comprobante ON (facturacion.factura.id_factura = facturacion.comprobante.id_factura)
        INNER JOIN facturacion.prestacion ON (facturacion.comprobante.id_comprobante = facturacion.prestacion.id_comprobante)
        INNER JOIN facturacion.nomenclador ON (facturacion.prestacion.id_nomenclador = facturacion.nomenclador.id_nomenclador)
        INNER JOIN nacer.smiafiliados ON (facturacion.comprobante.id_smiafiliados = nacer.smiafiliados.id_smiafiliados)
        INNER JOIN facturacion.smiefectores ON (facturacion.comprobante.cuie = facturacion.smiefectores.cuie)
        where factura.id_factura=$id_factura";
    $total=sql($query_t,"NO puedo calcular el total");
    $total=$total->fields['total'];?>
       <td align="center"><?=number_format($total,2,',','.');?></td>
       <td ><?=$result->fields['nro_exp_ext']?></td>  
       <td ><?=$result->fields['fecha_exp_ext']?></td>         
       <td ><?=fecha($result->fields['fecha_deposito'])?></td>  
       <td ><?=$result->fields['nro_exp']?></td>  
       <td ><?=fecha($result->fields['fecha_ing'])?></td>     
       <td ><?=fecha($result->fields['fecha_notificacion'])?></td>     
      
      
    </tr>
  <?$result->MoveNext();
    }?>
 </table>
 </form>
