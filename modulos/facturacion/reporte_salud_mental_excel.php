<?php

require_once ("../../config.php");


$fecha_desde=fecha_db($parametros["fecha_desde"]);
$fecha_hasta=fecha_db($parametros["fecha_hasta"]);

$sql_tmp="SELECT 
                  prestacion.*,nomenclador.*, t1.codigo as cod_diag, t1.descripcion as desc_diag, comprobante.cuie, smiafiliados.*
                FROM facturacion.prestacion 
                LEFT JOIN facturacion.comprobante USING (id_comprobante)
                LEFT JOIN nacer.smiafiliados USING (id_smiafiliados)
                LEFT JOIN facturacion.nomenclador using (id_nomenclador) 
                LEFT JOIN (select distinct codigo,descripcion from nomenclador.patologias_frecuentes) as t1 ON (prestacion.diagnostico=t1.codigo)
                WHERE
                  (prestacion.fecha_prestacion BETWEEN '$fecha_desde' and '$fecha_hasta') AND
                  (nomenclador.codigo = 'C073' OR nomenclador.codigo = 'C098')
                ORDER BY fecha_prestacion DESC";
$res_comprobante=sql($sql_tmp,"<br>Error al traer los datos<br>") or fin_pagina();

excel_header("reporte_salud_mental_excel.xls");

?>
<form name=form1 method=post action="resumen_prestaciones_excel.php">
 <br>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
    <tr>
    <th align="center">CUIE</th>
    <th align="center">DNI</th>
    <th align="center">Nombre</th>
    <th align="center">Apellido</th>
    <th align="center">Fecha Nac</th>
    <th align="center">Sexo</th>
    <th align="center">Fecha Prestacion</th>
    <th align="center">Cod Prestacion</th>
    <th align="center">Prestacion</th>    
    <th align="center">Cod Diagnostico</th>
    <th align="center">Diagnostino</th>
    <th align="center">Grupo</th>
    <th align="center">Subgrupo</th>
    </tr>
  <?   
  while (!$res_comprobante->EOF) {?>  
    <tr>     
     <td ><?=$res_comprobante->fields['cuie']?></td>    
      <td ><?=$res_comprobante->fields['afidni']?></td>    
      <td ><?=$res_comprobante->fields['afinombre']?></td>    
      <td ><?=$res_comprobante->fields['afiapellido']?></td>  
      <td ><?=fecha($res_comprobante->fields['afifechanac'])?></td>  
      <td><?=$res_comprobante->fields['afisexo']?></td>
      <td ><?=fecha($res_comprobante->fields['fecha_prestacion'])?></td>  
      <td><?=$res_comprobante->fields['codigo']?></td>
      <td><?=$res_comprobante->fields['descripcion']?></td>
      <td><?=$res_comprobante->fields['cod_diag']?></td>
      <td><?=$res_comprobante->fields['desc_diag']?></td>
      <td><?=$res_comprobante->fields['grupo_descriptivo']?></td>
      <td><?=$res_comprobante->fields['subgrupo']?></td>           
    </tr>
	<?$res_comprobante->MoveNext();
    }?>
 </table>
 </form>