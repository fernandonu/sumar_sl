<?php

define('FPDF_FONTPATH','font/');

require_once("../../config.php");
include_once("factura_clasepdf.php"); 

//generacion de pdf
$pdf=new orden_compra();
if ($parametros['id_factura']) $id_factura=$parametros['id_factura'];

$query="SELECT 
  sum(facturacion.prestacion.cantidad) AS cantidad,
  facturacion.nomenclador.codigo,
  facturacion.nomenclador.descripcion,
  facturacion.prestacion.precio_prestacion,
  facturacion.prestacion.precio_prestacion*sum(facturacion.prestacion.cantidad) as precio_total,
  nacer.efe_conv.nombre,
  nacer.efe_conv.cuie,
  facturacion.factura.periodo,
  facturacion.factura.periodo_actual,
  facturacion.factura.observaciones,
  facturacion.factura.fecha_carga,
  facturacion.factura.fecha_factura,
  facturacion.factura.alta_comp
FROM
  facturacion.nomenclador
  INNER JOIN facturacion.prestacion ON (facturacion.nomenclador.id_nomenclador = facturacion.prestacion.id_nomenclador)
  INNER JOIN facturacion.comprobante ON (facturacion.prestacion.id_comprobante = facturacion.comprobante.id_comprobante)
  INNER JOIN facturacion.factura ON (facturacion.comprobante.id_factura = facturacion.factura.id_factura)  
  INNER JOIN nacer.efe_conv ON (facturacion.comprobante.cuie = nacer.efe_conv.cuie)
WHERE  (factura.id_factura='$id_factura')
  GROUP BY
  facturacion.nomenclador.id_nomenclador,
  facturacion.nomenclador.codigo,
  facturacion.nomenclador.descripcion,
  facturacion.prestacion.precio_prestacion,
  nacer.efe_conv.nombre,
  nacer.efe_conv.cuie,
  facturacion.factura.periodo,
  facturacion.factura.periodo_actual,
  facturacion.factura.observaciones,
  facturacion.factura.fecha_carga,
  facturacion.factura.fecha_factura,
  facturacion.factura.alta_comp
  order by codigo";

$f_res=$db->Execute($query) or die($db->ErrorMsg());

$query1="SELECT 
		  b.categoria as obj_pres,
		  prestaciones_n_op.tema as codigo,
		  count (comprobante.id_comprobante) as cantidad_obj_pres,
		  sum (prestaciones_n_op.precio) precio,
		  nacer.efe_conv.nombre,
		  nacer.efe_conv.cuie,
		  facturacion.factura.periodo,
		  facturacion.factura.periodo_actual,
		  facturacion.factura.observaciones,
		  facturacion.factura.fecha_carga,
		  facturacion.factura.fecha_factura,
		  facturacion.factura.alta_comp
		FROM
		  facturacion.comprobante
		INNER JOIN nomenclador.prestaciones_n_op ON (facturacion.comprobante.id_comprobante = nomenclador.prestaciones_n_op.id_comprobante)
		INNER JOIN nomenclador.grupo_prestacion b ON (nomenclador.prestaciones_n_op.tema = b.codigo)
		INNER JOIN nacer.efe_conv ON (comprobante.cuie = nacer.efe_conv.cuie)
		INNER JOIN facturacion.factura ON (facturacion.comprobante.id_factura = facturacion.factura.id_factura)  
		WHERE  (factura.id_factura='$id_factura')
		GROUP BY
		  b.categoria,
		  prestaciones_n_op.tema,
		  nacer.efe_conv.nombre,
		  nacer.efe_conv.cuie,
		  facturacion.factura.periodo,
		  facturacion.factura.periodo_actual,
		  facturacion.factura.observaciones,
		  facturacion.factura.fecha_carga,
		  facturacion.factura.fecha_factura,
		  facturacion.factura.alta_comp
		ORDER BY 
		  obj_pres";
$f_res1=$db->Execute($query1) or die($db->ErrorMsg());

$pdf->dibujar_planilla();

if ($f_res->recordcount()>0){
	$pdf->nro_orden_compra($f_res->fields['periodo']);
	$pdf->nro_orden_compra1($f_res->fields['periodo_actual']);
	$pdf->proveedor($f_res->fields['nombre']);
	$pdf->fecha(Fecha($f_res->fields['fecha_factura']));
	$pdf->fecha_carga(Fecha($f_res->fields['fecha_carga']));
	$pdf->vendedor($f_res->fields['cuie']);
	$pdf->pasa_id_licitacion($id_factura);
	if ($f_res->fields['alta_comp']=='SI')$pdf->lugar_entrega($f_res->fields['observaciones']." FACTURA CORRESPONDIENTE AL NOMENCLADOR DE ALTA COMPLEJIDAD");
	else $pdf->lugar_entrega($f_res->fields['observaciones']);
}
if ($f_res1->recordcount()>0){
	$pdf->nro_orden_compra($f_res1->fields['periodo']);
	$pdf->nro_orden_compra1($f_res1->fields['periodo_actual']);
	$pdf->proveedor($f_res1->fields['nombre']);
	$pdf->fecha(Fecha($f_res1->fields['fecha_factura']));
	$pdf->fecha_carga(Fecha($f_res1->fields['fecha_carga']));
	$pdf->vendedor($f_res1->fields['cuie']);
	$pdf->pasa_id_licitacion($id_factura);
	$pdf->lugar_entrega($f_res1->fields['observaciones']);
}

//traemos los productos para agregar al pdf desde la tabla filas
$total=0;

if ($f_res->recordcount()>0){
	while(!$f_res->EOF){
		$pdf->producto($f_res->fields['codigo'].": ".$f_res->fields['descripcion'],$f_res->fields['cantidad'],$f_res->fields['precio_prestacion'],"$");
		$f_res->MoveNext();
	}
}

if ($f_res1->recordcount()>0){
	while(!$f_res1->EOF){
		$pdf->producto($f_res1->fields['codigo'].": ".$f_res1->fields['obj_pres'],$f_res1->fields['cantidad_obj_pres'],$f_res1->fields['precio'],"$");
		$f_res1->MoveNext();
	}
}

$query_t="SELECT sum 
			(facturacion.prestacion.cantidad*facturacion.prestacion.precio_prestacion) as total
			FROM
			  facturacion.factura
			  INNER JOIN facturacion.comprobante ON (facturacion.factura.id_factura = facturacion.comprobante.id_factura)
			  INNER JOIN facturacion.prestacion ON (facturacion.comprobante.id_comprobante = facturacion.prestacion.id_comprobante)
			  where factura.id_factura=$id_factura";
		$total=sql($query_t,"NO puedo calcular el total");
		$query_t1="SELECT sum 
			(nomenclador.prestaciones_n_op.precio) as total1
			FROM
			  facturacion.factura
			  INNER JOIN facturacion.comprobante ON (facturacion.factura.id_factura = facturacion.comprobante.id_factura)
			  INNER JOIN nomenclador.prestaciones_n_op using (id_comprobante)
			  where factura.id_factura=$id_factura";
$total1=sql($query_t1,"NO puedo calcular el total");
$total=$total->fields['total']+$total1->fields['total1'];

$sql_monto="SELECT factura.grupo_etareo as grupo, sum(neto) as subtotal from 
          (select prestacion.id_comprobante,
            prestacion.id_nomenclador,
            prestacion.cantidad,
            prestacion.precio_prestacion,
            nomenclador.normal,
            --smiafiliados.grupopoblacional,  
            comprobante.grupo_etareo,
            cantidad*prestacion.precio_prestacion as neto

           from facturacion.factura
          inner join facturacion.comprobante on comprobante.id_factura=factura.id_factura
          inner join facturacion.prestacion on prestacion.id_comprobante=comprobante.id_comprobante
          inner join facturacion.nomenclador on prestacion.id_nomenclador=nomenclador.id_nomenclador
          --inner join nacer.smiafiliados using (id_smiafiliados)
          where factura.id_factura=$id_factura) as factura
          group by grupo_etareo
          order by 1";
          
/*$sql_monto="SELECT factura.grupopoblacional as grupo, sum(neto) as subtotal from (
select prestacion.id_comprobante,
  prestacion.id_nomenclador,
  prestacion.cantidad,
  prestacion.precio_prestacion,
  nomenclador.normal,
  smiafiliados.afifechanac,
  comprobante.fecha_comprobante,
  trim (smiafiliados.afisexo) as sexo,
  extract (year from (age(comprobante.fecha_comprobante,smiafiliados.afifechanac))),
  case when extract (year from (age(comprobante.fecha_comprobante,smiafiliados.afifechanac)))<=5 then 'A' 
  when extract (year from (age(comprobante.fecha_comprobante,smiafiliados.afifechanac)))>5 
    and extract (year from (age(comprobante.fecha_comprobante,smiafiliados.afifechanac)))<=9 then 'B' 
  when extract (year from (age(comprobante.fecha_comprobante,smiafiliados.afifechanac)))>9 
    and extract (year from (age(comprobante.fecha_comprobante,smiafiliados.afifechanac)))<=19 then 'C'
  when extract (year from (age(comprobante.fecha_comprobante,smiafiliados.afifechanac)))>19
    and trim (smiafiliados.afisexo)='F' then 'D'
  when extract (year from (age(comprobante.fecha_comprobante,smiafiliados.afifechanac)))>19
    and trim (smiafiliados.afisexo)='M' then 'E' end as grupopoblacional,
  cantidad*prestacion.precio_prestacion as neto
  
  from facturacion.factura
inner join facturacion.comprobante on comprobante.id_factura=factura.id_factura
inner join facturacion.prestacion on prestacion.id_comprobante=comprobante.id_comprobante
inner join facturacion.nomenclador on prestacion.id_nomenclador=nomenclador.id_nomenclador
inner join nacer.smiafiliados using (id_smiafiliados)
where factura.id_factura=$id_factura) as factura
group by grupo
order by 1";*/

$rs_sql_monto=sql($sql_monto,"No se pudo ejecutar la consulta sobre los montos de la factura") or fin_pagina();

$rs_sql_monto->movefirst();
$monto_normal=0;
$monto_hombre=0;
while (!$rs_sql_monto->EOF) {
  $grupo=$rs_sql_monto->fields['grupo'];
  /*switch ($grupo) {
    case 'A':{$monto_normal=$monto_normal + $rs_sql_monto->fields['subtotal'];break;};
    case 'B':{$monto_normal=$monto_normal + $rs_sql_monto->fields['subtotal'];break;};
    case 'C':{$monto_normal=$monto_normal + $rs_sql_monto->fields['subtotal'];break;};
    case 'D':{$monto_normal=$monto_normal + $rs_sql_monto->fields['subtotal'];break;};
    case 'E':{$monto_hombre=$monto_hombre + $rs_sql_monto->fields['subtotal'];break;};
    default :/*{if ($rs_sql_monto->fields['edad_anio']>=20 and trim($rs_sql_monto->fields['afisexo'])=='M') 
              $monto_hombre=$monto_hombre+$rs_sql_monto->fields['subtotal'];
              else $monto_normal=$monto_normal+$rs_sql_monto->fields['subtotal'];};break; 
     };*/
    if ($grupo=='E') {$monto_hombre=$rs_sql_monto->fields['subtotal'];}
      else {$monto_normal=$monto_normal+$rs_sql_monto->fields['subtotal'];}
  $rs_sql_monto->movenext();
 
};


$pdf->_final($total,"$",$_ses_user['name'],$firma2,$firma3,$monto_normal,$monto_hombre);

$pdf->Footer();

$pdf->guardar_servidor("factura_$id_factura.pdf");
?>
