<?
require_once ("../../config.php");
require_once ("funciones_edad.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);


/*$sql="SELECT --expediente.transaccion.id_expediente, 
  expediente.expediente.id_factura,
  facturacion.comprobante.id_comprobante,
  --expediente.expediente.id_expediente,
  --expediente.transaccion.fecha_inf as fecha_cierre,
  nacer.efe_conv.cuie,
  nacer.efe_conv.nombre as nombre_efector,
  --expediente.transaccion.num_tranf,
  nacer.smiafiliados.id_smiafiliados,
  nacer.smiafiliados.afidni as dni,
  nacer.smiafiliados.afinombre as nombre,
  nacer.smiafiliados.afiapellido as apellido,
  nacer.smiafiliados.afifechanac,
  nacer.smiafiliados.afisexo,
  facturacion.prestacion.id_prestacion,
  --facturacion.prestacion.fecha_prestacion,
  facturacion.comprobante.fecha_comprobante,
  facturacion.comprobante.grupo_etareo,
  facturacion.prestacion.id_nomenclador,
  facturacion.nomenclador.grupo,
  facturacion.nomenclador.codigo,
  facturacion.nomenclador.descripcion,
  facturacion.prestacion.cantidad,
  facturacion.prestacion.precio_prestacion,
  facturacion.prestacion.diagnostico,
  contabilidad.ingreso.fecha_deposito
  
 --from expediente.transaccion 
from expediente.expediente

--inner join expediente.transaccion on expediente.transaccion.id_expediente=expediente.expediente.id_expediente
inner join facturacion.factura on facturacion.factura.id_factura=expediente.expediente.id_factura
inner join facturacion.comprobante on facturacion.comprobante.id_factura=facturacion.factura.id_factura
inner join facturacion.prestacion on facturacion.prestacion.id_comprobante=facturacion.comprobante.id_comprobante
inner join facturacion.nomenclador on facturacion.nomenclador.id_nomenclador=facturacion.prestacion.id_nomenclador
inner join nacer.smiafiliados on facturacion.comprobante.id_smiafiliados=nacer.smiafiliados.id_smiafiliados
inner join nacer.efe_conv on expediente.expediente.id_efe_conv=nacer.efe_conv.id_efe_conv
inner join contabilidad.ingreso on factura.id_factura=ingreso.numero_factura



 where expediente.expediente.estado='C' and contabilidad.ingreso.fecha_deposito between '$fecha_desde' and '$fecha_hasta' 
--and expediente.transaccion.fecha_inf between '2016-05-01' and '2016-06-30' 
order by 1,2";*/
$sql = "SELECT * from facturacion.resumen_prestacion".$periodo." ORDER BY 1,2";

$res_fact = sql($sql) or fin_pagina();
//$filename = "Detalle_comprobantes_desde_".$fecha_desde."_hasta_".$fecha_hasta.".xls";
$filename = "Detalle_comprobantes_periodo_".$periodo.".xls";
//$fecha_hoy = date("Y-m-d H:i:s");
excel_header($filename);?>
<form name=form1 method=post action="listado_comprobantes_auditoria_excel.php">
<table width="95%" cellspacing=1 border=1 bordercolor=#E0E0E0 align="center" class="bordes">
 <tr>

</tr>
<tr><td>
<table width=100% align="center" class="bordes">
<tr>
<td colspan="5" align="center">

</td>
</tr>
<tr>
<td>
    <table width="100%">
      <tr>
       <td>
        <table width="100%">
         <tr>
          <td align="left">
           <b>Total: </b><?php echo $res_fact->RecordCount();?> 
           </td> 
          </tr>      
        </table>  
       </td>
      </tr>  
     </table> 
     <br>
     <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
      <tr bgcolor=#C0C0FF>
        <th align="center">N.Factura</th>
        <th align="center">N.Comprob.</th>
        <!--<th align="center">N.Prestac.</th>-->
        <th align="center">CUIE</th>
        <th align="center">Efector</th>
        <th align="center">ClaveBeneficiario</th>
        <th align="center">DNI</th>
        <th align="center">Nombre</th>
        <th align="center">Apellido</th>
        <th align="center">Fecha Nac.</th>
        <th align="center">Sexo</th>
        <th align="center">Edad</th>
        <th align="center">Grupo Etareo</th>
        <th align="center">Fecha Comp.</th>
        <th align="center">Grupo</th>
        <th align="center">Codigo</th>
        <th align="center">Descripcion</th>
        <th align="center">Cantidad</th>
        <th align="center">Precio</th>
        <th align="center">Diagnostico</th>
        <th align="center">Peso</th>
        <th align="center">Talla</th>
        <th align="center">TA</th>
        <th align="center">Perim.Cefalico</th>
        <th align="center">Edad.Gestacional</th>
        <th align="center">Res.Oido.Der.</th>
        <th align="center">Res.Oido.Izq.</th>
        <th align="center">Retinopatia</th>
        <th align="center">Inf.Diag.Biop</th>
        <th align="center">Inf.Ant.Patol.</th>
        <th align="center">Inf.diag.Anatomo.</th>
        <th align="center">Inf.VDRL</th>
        <th align="center">Trat.Instaur.</th>
        <th align="center">CPOD-CEOD</th>
        <!--<th align="center">log</th>-->
        <th align="center">Fecha Deposito</th>  
      </tr>
      <?   
      while (!$res_fact->EOF) {
        $fecha_nac=$res_fact->fields['afifechanac'];
        $fecha_comp=$res_fact->fields['fecha_comprobante'];
        $edad=edad_con_meses($fecha_nac,$fecha_comp);
        $anios=$edad['anos'];
        
        ?>  
        <tr>     
        <td><?php echo $res_fact->fields['id_factura']?></td>
          <td><?php echo $res_fact->fields['id_comprobante']?></td>
          <!--<td><?php echo $res_fact->fields['id_prestacion']?></td>-->
          <td><?php echo $res_fact->fields['cuie']?></td>
          <td><?php echo utf8_decode($res_fact->fields['nombre_efector'])?></td>
          <td><?php echo $res_fact->fields['clavebeneficiario']?></td>
          <td><?php echo $res_fact->fields['dni']?></td>
          <td><?php echo utf8_decode($res_fact->fields['nombre'])?></td>
          <td><?php echo utf8_decode($res_fact->fields['apellido'])?></td>
          <td><?php echo fecha($res_fact->fields['afifechanac'])?></td>
          <td><?php echo $res_fact->fields['afisexo']?></td>
          <td><?php echo $res_fact->fields['edad']?></td>
          <!--<td><?php echo $anios?></td>-->
          <td><?php echo $res_fact->fields['grupo_etareo']?></td>
          <td><?php echo fecha($res_fact->fields['fecha_comprobante'])?></td>
          <td><?php echo $res_fact->fields['grupo']?></td>
          <td><?php echo $res_fact->fields['codigo']?></td>
          <td><?php echo utf8_decode($res_fact->fields['descripcion'])?></td>
          <td><?php echo $res_fact->fields['cantidad']?></td>
          <td><?php echo number_format($res_fact->fields['precio_prestacion'],2,',','.')?></td>
          <td><?php echo $res_fact->fields['diagnostico']?></td>
          <td><?php echo $res_fact->fields['peso']?></td>
          <td><?php echo $res_fact->fields['talla']*100?></td>
          <td><?php echo $res_fact->fields['ta']?></td>
          <td><?php echo $res_fact->fields['perim_cefalico']?></td>
          <td><?php echo $res_fact->fields['edad_gestacional']?></td>
          <td><?php echo $res_fact->fields['res_oido_derecho']?></td>
          <td><?php echo $res_fact->fields['res_oido_izquierdo']?></td>
          <td><?php echo $res_fact->fields['retinopatia']?></td>
          <td><?php echo $res_fact->fields['inf_diag_biopsia']?></td>
          <td><?php echo $res_fact->fields['inf_anat_patologica']?></td>
          <td><?php echo $res_fact->fields['inf_diag_anatomo']?></td>
          <td><?php echo $res_fact->fields['inf_vdrl']?></td>
          <td><?php echo $res_fact->fields['tratamiento_instaurado']?></td>
          <td><?php echo $res_fact->fields['cpod_ceod']?></td>
          <!--<td><?php echo $res_fact->fields['log_table']?></td>-->
          <td><?php echo fecha($res_fact->fields['fecha_deposito'])?></td>
       </tr>
      <?php $res_fact->MoveNext();
        }?>
     </table>
</form>
<?php echo fin_pagina();// aca termino ?>