<?

require_once("../../config.php");
require_once("consultas_con_trimestral.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
$dia_hoy = date("Y-m-d");
$fechas = periodo($dia_hoy);
$fecha_desde = $fechas['fecha_desde'];
$fecha_hasta = $fechas['fecha_hasta'];

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

$nombre_archivo="Consolidado_trimestral_".$cuie."_".$dia_hoy.".xls";
excel_header($nombre_archivo);


$sql_stock="SELECT * 
            FROM programa_sexual.stock_producto a, programa_sexual.remedio b
            WHERE a.id_remedio = b.id_remedio
            AND a.id_efector=$id_efector 
            ORDER BY 3";
$result=sql($sql_stock,"No se pudo ejecutar la consulta sobre el stock") or fin_pagina();


$sql_efector="SELECT * 
          FROM nacer.efe_conv a, programa_sexual.proceso_archivo b
          WHERE a.id_efe_conv = b.id_efector
          AND a.id_efe_conv=$id_efector
          ORDER BY id_proceso DESC";
$res_efector=sql($sql_efector,"no se pudo ejecutar la consulta sobre los datos de efector") or fin_pagina();

//$consultas=$res_efector->fields['consultas'];
$fecha_corte=$res_efector->fields['fecha_corte'];


$sql_comprobantes="SELECT count (*) as total from programa_sexual.comprobantes where cuie='$cuie'";
$res_comprobante=sql($sql_comprobantes,"no se pudo ejecutar la consulta de comprobantes") or fin_pagina();
}

else {
  excel_header("listado de Productos_Completo_$dia_hoy.xls");
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

<form name=form1 method=post action="consolidado_trimestral.php">
<table width="95%" cellspacing=1 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
 <tr id="mo">
<td align="center">
<font size=+1><b>CONSOLIDADO TRIMESTREL</b></font> 
<BR><font color="red"><b>Fecha de corte: </b><?php echo $dia_hoy;?></font>
<BR><font color="red"><?php echo 'Trimestre: '.$fecha_desde.' hasta '.$fecha_hasta;?></font>
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
<td align="left"><font color="blue"><?php echo $nombre?></font></td>
</tr>

<tr>
<td align="right"><b>Domicilio:</b></td>
<td align="left"><font color="blue"><?php echo $domicilio?></font></td>
</tr>

<tr>
<td align="right"><b>Departamento:</b></td>
<td align="left"><font color="blue"><?php echo $departamento?></font></td>
</tr>

<tr>
<td align="right">
<b>Localidad:</b>
</td>
<td align="left"><font color="blue"><?php echo $localidad?></font></td>
</tr>
</table>
</td>      

<td>
<table align="center">        
<tr>
<td align="right"><b>Codigo Postal:</b></td>
<td align="left"><font color="blue"><?php echo $cod_pos?></font></td>
</tr>

<tr>
<td align="right"><b>Cuidad:</b></td>
<td align="left"><font color="blue"><?php echo $cuidad?></font></td>
</tr>

<tr>
<td align="right"><b>Referente:</b></td>
<td align="left"><font color="blue"><?php echo utf8_decode($referente)?></font></td>
</tr>

<tr>
<td align="right"><b>Telefono:</b></td>
<td align="left"><font color="blue"><?php echo $tel?></font></td>
</tr>          
</table>
</td>  
</tr> 
</table>

   
<br>
&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;   
  <table width=80% border="1">
  <tr>
               
    <td></td>            
    <td align="center" id="mo"><b>Personas NUEVAS</b></td>
    <td align="center" id="mo"><b>Total de prestaciones</b></td>
  </tr>

  <tr>
    <td></td>            
    <td align="center" id="mo"><b>en el Trimestre</b></td>
    <td align="center" id="mo"><b>en el Trimestre</b></td>
  </tr>
  <tr><td></td>
      <td align="center" id="mo"><b><?php echo benef_nuevos($cuie,$fecha_desde,$fecha_hasta);?></b></td>
      <td align="center" id="mo"><b><?php echo total_prest($cuie,$fecha_desde,$fecha_hasta)?></b></td></tr>
  <tr></tr>

  <tr><td align="left" id="mo"><b>Total de Personas</b></td>
      <td align="center" id="mo"><b>1</b></td></tr>
  <tr><td align="left" id="mo"><b>Total de Personas con Obra Social</b></td>
      <td align="center" id="mo"><b>1</b></td></tr>
      
  <tr><td align="left" id="mo"><b>Mujeres Menores de 20 años</b></td>
      <td align="center" id="mo"><b><?php echo mujeres_menor_20($cuie,$fecha_desde,$fecha_hasta)?></b></td></tr>
      
  <tr><td align="left" id="mo"><b>Mujeres Mayores de 20 años</b></td>
      <td align="center" id="mo"><b><?php echo mujeres_mayor_20($cuie,$fecha_desde,$fecha_hasta)?></b></td></tr>
      
  <tr><td align="left" id="mo"><b>Varones Menores de 20 años</b></td>
      <td align="center" id="mo"><b><?php echo varones_menor_20($cuie,$fecha_desde,$fecha_hasta)?></b></td></tr>
      
  <tr><td align="left" id="mo"><b>Varones Mayores de 20 años</b></td>
      <td align="center" id="mo"><b><?php echo varones_mayor_20($cuie,$fecha_desde,$fecha_hasta)?></b></td></tr>
      
<?php $paises_benef = benef_paises($cuie,$fecha_desde,$fecha_hasta);
  while (!$paises_benef->EOF)
    {
      $pais = $paises_benef->fields['pais'];
      switch ($pais) {
        case 'ARGENTINA': $value_ar=$paises_benef->fields['valor'];break;
        case 'BOLIVIA': $value_bo=$paises_benef->fields['valor'];break;
        case 'CHILE': $value_ch=$paises_benef->fields['valor'];break;
        case 'PARAGUAY': $value_pa=$paises_benef->fields['valor'];break;
        case 'PERU': $value_pe=$paises_benef->fields['valor'];break;       
        default: $value_otros = $paises_benef->fields['valor']; break;
      }
      $paises_benef->MoveNext();
    }

      ?>
      
  <tr><td align="left" id="mo"><b>Pais - Argentina</b></td>
      <td align="center" id="mo"><b><?php echo (($value_ar)?$value_ar:0);?></b></td></tr>
      
  <tr><td align="left" id="mo"><b>Pais - Bolivia</b></td>
      <td align="center" id="mo"><b><?php echo (($value_bo)?$value_bo:0);?></b></td></tr>
      
  <tr><td align="left" id="mo"><b>Pais - Chile</b></td>
      <td align="center" id="mo"><b><?php echo (($value_ch)?$value_ch:0);?></b></td></tr>
      
  <tr><td align="left" id="mo"><b>Pais - Paraguay</b></td>
      <td align="center" id="mo"><b><?php echo (($value_pa)?$value_pa:0);?></b></td></tr>
      
  <tr><td align="left" id="mo"><b>Pais - Peru</b></td>
      <td align="center" id="mo"><b><?php echo (($value_pe)?$value_pe:0);?></b></td></tr>
      
  <tr><td align="left" id="mo"><b>Pais - Otros</b></td>
      <td align="center" id="mo"><b><?php echo (($value_otros)?$value_otros:0);?></b></td></tr>

      <?php $prestaciones = metodo_antic($cuie,$fecha_desde,$fecha_hasta);
  while (!$prestaciones->EOF)
    {
      $prestacion = $prestaciones->fields['codigo'];
      switch ($prestacion) {
        case '009': $value_009=$prestaciones->fields['cantidad'];break;
        case '010': $value_010=$prestaciones->fields['cantidad'];break;
        case '011': $value_011=$prestaciones->fields['cantidad'];break;
        case '012': $value_012=$prestaciones->fields['cantidad'];break;
        case '013': $value_013=$prestaciones->fields['cantidad'];break;  
        case '001': $value_001=$prestaciones->fields['cantidad'];break;
        case '002': $value_002=$prestaciones->fields['cantidad'];break;
        case '003': $value_003=$prestaciones->fields['cantidad'];break;
        case '004': $value_004=$prestaciones->fields['cantidad'];break;
        case '005': $value_005=$prestaciones->fields['cantidad'];break;
        case '006': $value_006=$prestaciones->fields['cantidad'];break;
        case '008': $value_008=$prestaciones->fields['cantidad'];break;
        case '007': $value_007=$prestaciones->fields['cantidad'];break;

        default: break;
      }
      $prestaciones->MoveNext();
    }

      ?>
      

  <tr><td align="left" id="mo"><b>DIU (Disp.Intrauterino) T de cobre</b></td>
      <td align="center" id="mo"><b><?php echo (($value_009)?$value_009:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>DIU (Disp.Intrauterino) Tipo Multiload</b></td>
      <td align="center" id="mo"><b><?php echo (($value_010)?$value_010:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>DIU (Disp.Intrauterino) 7 mini</b></td>
      <td align="center" id="mo"><b><?php echo (($value_011)?$value_011:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>DIU (Disp.Intrauterino) H mini</b></td>
      <td align="center" id="mo"><b><?php echo (($value_012)?$value_012:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>SIU (Sist. Intrauterino)</b></td>
      <td align="center" id="mo"><b><?php echo (($value_013)?$value_013:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>ACO - Levonorgestrel 0,15 + EE 0,03mg x 21 Comp.</b></td>
      <td align="center" id="mo"><b><?php echo (($value_001)?$value_001:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>ACO - Gestodeno 0,75 + EE 0,03mg x 21 Comp.</b></td>
      <td align="center" id="mo"><b><?php echo (($value_002)?$value_002:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>ACO para LACT - Levonorgestrel 0,03mg x 35/28 Comp.</b></td>
      <td align="center" id="mo"><b><?php echo (($value_003)?$value_003:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>ACO para LACT - Desogestrel 0,075mg x 28 Comp.</b></td>
      <td align="center" id="mo"><b><?php echo (($value_004)?$value_004:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>ACI - Norestisterona 50mg - apolla mensual</b></td>
      <td align="center" id="mo"><b><?php echo (($value_005)?$value_005:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>ACI - Medroxiprogesterona 150mg - apolla mensual</b></td>
      <td align="center" id="mo"><b><?php echo (($value_006)?$value_006:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>AHE - Levonorgestrel 0,075mg Comp. x 2</b></td>
      <td align="center" id="mo"><b><?php echo (($value_007)?$value_007:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>AHE - Levonorgestrel 1,5mg Comp. x 2</b></td>
      <td align="center" id="mo"><b><?php echo (($value_008)?$value_008:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>Test</b></td>
      <td align="center" id="mo"><b><?php echo (($value_016)?$value_016:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>Implante</b></td>
      <td align="center" id="mo"><b><?php echo (($value_017)?$value_017:0)?></b></td></tr>
  <tr><td align="left" id="mo"><b>Ligadura tubaria</b></td>
      <td align="center" id="mo"><b>0</b></td></tr>
  <tr><td align="left" id="mo"><b>Vasectomia</b></td>
      <td align="center" id="mo"><b>0</b></td></tr>
  <tr><td align="left" id="mo"><b>Bajas del Programa - Mujeres</b></td>
      <td align="center" id="mo"><b>0</b></td></tr>
  <tr><td align="left" id="mo"><b>Bajas del Programa - Varones</b></td>
      <td align="center" id="mo"><b>0</b></td></tr>  
</table>
</form>