<?php
/*
Author: sebastian lohaiza

modificada por
$Author: seba $
$Revision: 2.0 $
$Date: 2024/04/25 $
*/
require_once("../../config.php");
require (dirname(__FILE__).'/html2pdf/html2pdf.class.php');

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);


if ($id_efe_conv) {
$query="SELECT 
  efe_conv.*,dpto.nombre as dpto_nombre
FROM
  nacer.efe_conv 
  left join nacer.dpto on dpto.codigo=efe_conv.departamento   
  where id_efe_conv=$id_efe_conv";

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
}	    

if ($param){
  $ninios_new_1=$param['ninios_new_1'];
  $ninios_1_a_9=$param['ninios_1_a_9'];
  //$embarazadas_12_pers=$param['embarazadas_12_pers'];
  $embarazadas=$param['embarazadas'];
  $adol_new_pers_3=$param['adol_new_pers_3'];
  //$cuidado_sexual=$param['cuidado_sexual'];
  $dia=$param['dia'];
  $hip=$param['hip'];
  
 /*$efe_doble_viral=($param['efe_doble_viral'])?$param['efe_doble_viral']:0;
  $efe_hep_b=($param['efe_hep_b'])?$param['efe_hep_b']:0;
  $efe_neumococo=($param['efe_neumococo'])?$param['efe_neumococo']:0;
  $efe_cuadruple=($param['efe_cuadruple'])?$param['efe_cuadruple']:0;
  $efe_sabin=($param['efe_sabin'])?$param['efe_sabin']:0;
  $efe_triple_viral=($param['efe_triple_viral'])?$param['efe_triple_viral']:0;
  $efe_gripe=($param['efe_gripe'])?$param['efe_gripe']:0;
  $efe_hep_a=($param['efe_hep_a'])?$param['efe_hep_a']:0;
  $efe_triple_bacteriana_celular=($param['efe_triple_bacteriana_celular'])?$param['efe_triple_bacteriana_celular']:0;
  $efe_triple_bacteriana_acelular=($param['efe_triple_bacteriana_acelular'])?$param['efe_triple_bacteriana_acelular']:0;
  $efe_doble_bacteriana=($param['efe_doble_bacteriana'])?$param['efe_doble_bacteriana']:0;
  $efe_hpv=($param['efe_hpv'])?$param['efe_hpv']:0; 
  $efe_pentavalente=($param['efe_pentavalente'])?$param['efe_pentavalente']:0;

  $talleres=($param['talleres'])?$param['talleres']:0;
  $emb_riesgo=($param['embarazo_riesgo'])?$param['embarazo_riesgo']:0; */
}

if ($metas) {
  //$pap_sitam=$metas['pap_sitam'];
  $cant_embarazadas=$metas['cant_embarazadas'];
  //$captacion_temprana=$metas['captacion_temprana'];
  //$promedio_controles_x_emb=$metas['promedio_controles_x_emb'];
  //$mujeres_edad_fertil=$metas['mujeres_edad_fertil'];
  $cns_menor_1_año=$metas['cns_menor_1_año'];
  $cns_entre_1_y_9=$metas['cns_entre_1_y_9'];
  $adolecentes=$metas['adolecentes'];
  $enfermedades_cronicas_HTA=$metas['enfermedades_cronicas_HTA'];
  $enfermedades_cronicas_DBT=$metas['enfermedades_cronicas_DBT'];
  /*$vacuna_hep_b=$metas['vacuna_hep_b'];
  $vacuna_neumococo=$metas['vacuna_neumococo'];
  $vacuna_pentavalente=$metas['vacuna_pentavalente'];
  $vacuna_sabin=$metas['vacuna_sabin'];
  $vacuna_triple_viral=$metas['vacuna_triple_viral'];
  $vacuna_gripe=$metas['vacuna_gripe'];
  $vacuna_hep_a=$metas['vacuna_hep_a'];
  $vacuna_triple_bacteriana_celular=$metas['vacuna_triple_bacteriana_celular'];
  $vacuna_triple_bacteriana_acelular=$metas['vacuna_triple_bacteriana_acelular'];
  $vacuna_doble_bacteriana=$metas['vacuna_doble_bacteriana'];
  $vacuna_vph=$metas['vacuna_vph'];
  $vacuna_doble_viral=$metas['vacuna_doble_viral'];
  $meta_emb_riesgo=$metas['emb_riesgo'];
  $meta_talleres=$metas['meta_talleres'];*/
  
}
ob_start(); 
?> 
<page footer="page" format=A4 backtop="5mm" backbottom="5mm" backleft="2mm" backright="2mm"  style="font-size: 8pt">

<!-- <page_header> --> 
<table class="page_header" width=210 height=297 cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor="#CFE8DD" class="bordes">
<!-- width=210 height=297 -->
<tr>
<td align="center" border=1 bordercolor=#E0E0E0 bgcolor="#F5BCA9">
<?$hoy = date("d/m/Y H:i"); ?>
<font size=+2><b>Fecha y Hora de Corte del Informe: <?echo $hoy?> </b></font>        
</td>
</tr>
</table>

<table class="page_header" width=210 height=297 cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor="#CFE8DD" class="bordes">
	<!-- width=210 height=297 -->
<tr>
<td align="center" border=1 bordercolor=#E0E0E0 bgcolor="#CFE8DD">
<font size=+2><b>Efector: <?echo $cuie.". Periodo Desde: ".fecha($fecha_desde)." Hasta: ".fecha($fecha_hasta)?> </b></font>        
</td>
</tr>
</table>
<!-- </page_header> -->

<table border=2 width=210 height=297 align="center" class="bordes" >
<tr>
<td >
<b>Descripcion del Efector</b>
</td>
</tr>
<tr>
<td>
      
<table align="center">
<tr>
<td align="right">
<b>Nombre:</b>
</td>
<td align="left" border=1 bordercolor=#E0E0E0 bgcolor="#CFE8DD">		 
<?echo $nombre?>"
</td>
<td align="right">
<b>Codigo Postal:</b>
</td>
<td align="left" border=1 bordercolor=#E0E0E0 bgcolor="#CFE8DD">		 	 
<?echo $cod_pos?>
</td>
</tr>
<tr>
<td align="right">
<b>Domicilio:</b>
</td>
<td align="left" bordercolor=#E0E0E0 bgcolor="#CFE8DD">	 
<?echo $domicilio?>
</td>
<td align="right">
<b>Cuidad:</b>
</td>
<td align="left" bordercolor=#E0E0E0 bgcolor="#CFE8DD">	 
<?echo $cuidad?>
</td>
</tr> 
<tr>
<td align="right">
<b>Departamento:</b>
</td>
<td align="left" bordercolor=#E0E0E0 bgcolor="#CFE8DD">			 
<?echo $departamento?>
</td>
<td align="right">
<b>Referente:</b>
</td>
<td align="left" bordercolor=#E0E0E0 bgcolor="#CFE8DD">
<?echo $referente?>
</td>
</tr>
    
<tr>
<td align="right">
<b>Localidad:</b>
</td>
<td align="left" bordercolor=#E0E0E0 bgcolor="#CFE8DD">			 
<?echo $localidad?>
</td>
<td align="right">
<b>Telefono:</b>
</td>
<td align="left" bordercolor=#E0E0E0 bgcolor="#CFE8DD">		 
<?echo $tel?>
</td>
</tr>
</table>
</td>
</tr>
  
</table>
<table><tr><td>&nbsp;</td></tr></table>
<table><tr><td>&nbsp;</td></tr></table>

<table border=2 width=210 height=297 align="center" class="bordes" >		 
	<tr align="center" id="sub_tabla">
	<td colspan=10>	
	<font size=3 >Detalle sobre cumplimientos de metas <BR> </font>
	</td>
	</tr>
  
  <tr align="center" id="sub_tabla">
  <td colspan=10> 
  <!--<font size=4><b>Nota Importante: las metas anuales estan fijadas con periodo desde 01/01/2019 al 31/12/2019 <BR></b> </font>
  <font size=4><b>Las mismas son evaluadas al 50% de la meta anual para el cumplimiento del primer semestre <BR></b> </font>
  -->  
</td>
  </tr>
    <tr>
    <td align="center" border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
    Total de Controles de Niños menores 1 año: <b><?php echo $ninios_new_1?> / Meta Anual : <?php echo $cns_menor_1_año?></b>
    </td>   
    <td align="center" border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
    Total de Controles de Niños de 1 a 9 años: <b><?php echo $ninios_1_a_9?> / Meta Anual: <?php echo $cns_entre_1_y_9?> </b>
    </td>     
    </tr> 
    
    <tr>
    <td align="center" border=1 bordercolor=#2C1701>
    <? echo "<a target='_blank'><img src='graficos\cns_menor_1_a__o".$cuie.".png'  border=0 align=top></a>\n";?>
    </td>
    <td align="center" border=1 bordercolor=#2C1701>
    <?echo "<a target='_blank'><img src='graficos\_ninios_total".$cuie.".png'  border=0 align=top></a>\n";?>
    </td>
    </tr>
    
    <!--
    <tr>
    <td align="center" border=1 bordercolor=#2C1701 onclick="<?php //echo $onclick_elegir?>" <?php //echo atrib_tr7()?>>
    Total de Inscriptos bajo Cuidado Sexual y Reproductivo: <b><?php //echo $cuidado_sexual?> / Meta Anual: <?php //echo $mujeres_edad_fertil?> </b>
    </td> 
    <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
    Total de Embarazadas antes de las 12 sem.: <b><?php //echo $embarazadas_12_pers?> / Meta Anual: <?php //echo $captacion_temprana?></b>
    </td>
    </tr>
         
    <tr>
    <td align="center" border=1 bordercolor=#2C1701>
    <?//echo "<a href='$link_l' target='_blank'><img src='graficos\cuidado_sexual".$cuie.".png'  border=0 align=top></a>\n";?>
    </td>
    <td align="center" border=1 bordercolor=#2C1701>
    <?//echo "<a href='$link_l' target='_blank'><img src='graficos\embarazadas_12_pers".$cuie.".png'  border=0 align=top></a>\n";?>
    </td>
    </tr>
    -->

    <tr>
    <td align="center" border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
    Total de Controles de Embarazo: <b><?php echo $embarazadas?> / Meta Anual: <?php echo $promedio_controles_x_emb?> </b>
    </td> 
    <td align="center" border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
    Total de Controles Adolesc. de 10 a 19 años: <b><?php echo $adol_new_pers_3?> / Meta Anual: <?php echo $adolecentes?> </b>
    </td>   
    </tr>
    
    <tr>
      <td align="center" border=1 bordercolor=#2C1701>
      <?echo "<a href='$link_l' target='_blank'><img src='graficos\embarazadas".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      <td align="center" border=1 bordercolor=#2C1701>
      <?echo "<a href='$link_l' target='_blank'><img src='graficos\adol_new_pers_3".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
    </tr>

  </table>
</page>

<page footer="page" format=A4 backtop="5mm" backbottom="5mm" backleft="2mm" backright="2mm"  style="font-size: 8pt">
  <table border=2 width=210 height=297 align="center" class="bordes" >     
  <tr align="center" id="sub_tabla">
  <td colspan=10> 
  <font size=3 >Detalle sobre cumplimientos de metas <BR> </font>
  </td>
  </tr>  
    
    <tr>
    <td align="center" border=1 bordercolor=#2C1701 onclick="<?php echo $onclick_elegir?>" <?php echo atrib_tr7()?>>
      Total de Controles de Hipertensos: <b><?php echo $hip?> / Meta Anual: <?php echo $enfermedades_cronicas_HTA?> </b>
      </td>            
    <td align="center" border=1 bordercolor=#2C1701 onclick="<?php echo $onclick_elegir?>" <?php echo atrib_tr7()?>>
    Total de Controles de Diabeticos: <b><?php echo $dia?> / Meta Anual: <?php echo $enfermedades_cronicas_DBT?> </b>
    </td>          
    </tr>
            
    <tr>
    <td align="center" border=1 bordercolor=#2C1701>
      <?echo "<a href='$link_l' target='_blank'><img src='graficos\hip".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
    <td align="center" border=1 bordercolor=#2C1701>
    <?echo "<a href='$link_l' target='_blank'><img src='graficos\dia".$cuie.".png'  border=0 align=top></a>\n";?>
    </td>
    </tr>
    
    <!--
    <tr>
    <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
    Notificaciones de Embarazadas con Factores de Riesgo: <b><?php //echo ($emb_riesgo)?$emb_riesgo:0?> /  Meta Anual x RRHH: <?php //echo $meta_emb_riesgo?> </b>
    </td>
    <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
      Total de Talleres: <b><?php //echo ($talleres)?$talleres:0?> / Meta Anual x RRHH: <?php //echo $meta_talleres?> </b>
      </td>            
    </tr>
            
    <tr>
    <td align="center" border=1 bordercolor=#2C1701>
    <?echo "<a href='$link_l' target='_blank'><img src='graficos\emb_riesgo".$cuie.".png'  border=0 align=top></a>\n";?>
    </td>
    <td align="center" border=1 bordercolor=#2C1701>
      <?echo "<a href='$link_l' target='_blank'><img src='graficos\_talleres".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
    </tr>
    -->
    </table>
</page>  
    
<!--
<page footer="page" format=A4 backtop="5mm" backbottom="5mm" backleft="2mm" backright="2mm"  style="font-size: 8pt">
<table border=2 width=210 height=297 align="center" class="bordes" >     
<tr align="center" id="sub_tabla">
<td colspan=10> 
<font size=3 >Detalle sobre cumplimientos de metas <BR> </font>
</td>
</tr>
           
    <tr>
      <td align="center" border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
      Total de Vacunas Pentavalentes: <b><?php //echo $efe_pentavalente?> / Meta Anual: <?php //echo $vacuna_pentavalente?> </b>
      </td>
      <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
      Total de Vacunas Doble Viral: <b><?php //echo $efe_doble_viral?> / Meta Anual: <?php //echo $vacuna_doble_viral?> </b>
      </td>           
      </tr>
            
      <tr>
      <td align="center" border=1 bordercolor=#2C1701>
      <?//echo "<a href='$link_l' target='_blank'><img src='graficos\_vacuna_pentavalente".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      <td align="center" border=1 bordercolor=#2C1701>
      <?//echo "<a href='$link_l' target='_blank'><img src='graficos\_vacuna_doble_viral".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      </tr>
      
      <tr>
      <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
      Total de Vacunas Hepatitis B: <b><?php //echo $efe_hep_b?> / Meta Anual: <?php //echo $vacuna_hep_b?> </b>
      </td> 
      <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
      Total de Vacunas Neumococo: <b><?php //echo $efe_neumococo?> / Meta Anual: <?php //echo $vacuna_neumococo?> </b>
      </td>               
      </tr>
      
      <tr>
      <td align="center" border=1 bordercolor=#2C1701>
      <?//echo "<a href='$link_l' target='_blank'><img src='graficos\_vacuna_hep_b".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      <td align="center" border=1 bordercolor=#2C1701>
      <?//echo "<a href='$link_l' target='_blank'><img src='graficos\_vacuna_neumococo".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      </tr>
      
      <tr>
      <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
      Total de Vacunas Sabin/Salk: <b><?php //echo $efe_sabin?> / Meta Anual: <?php //echo $vacuna_sabin?> </b>
      </td> 
      <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
      Total de Vacunas Triple Viral: <b><?php //echo $efe_triple_viral?> / Meta Anual: <?php //echo $vacuna_triple_viral?> </b>
      </td>             
      </tr>
      
      <tr>
      <td align="center" border=1 bordercolor=#2C1701>
      <?//echo "<a href='$link_l' target='_blank'><img src='graficos\_vacuna_sabin".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      <td align="center" border=1 bordercolor=#2C1701>
      <?//echo "<a href='$link_l' target='_blank'><img src='graficos\_vacuna_triple_viral".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      </tr>

  </table>
</page>  
    
<page footer="page" format=A4 backtop="5mm" backbottom="5mm" backleft="2mm" backright="2mm"  style="font-size: 8pt">
<table border=2 width=210 height=297 align="center" class="bordes" >     
<tr align="center" id="sub_tabla">
<td colspan=10> 
<font size=3 >Detalle sobre cumplimientos de metas <BR> </font>
</td>
</tr>      
      <tr>
      <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
      Total de Vacunas Anti-Gripales: <b><?php //echo $efe_gripe?> / Meta Anual: <?php //echo $vacuna_gripe?> </b>
      </td> 
      <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
      Total de Vacunas Hepatitis A: <b><?php //echo $efe_hep_a?> / Meta Anual: <?php //echo $vacuna_hep_a?> </b>
      </td>            
      </tr>
      
      <tr>
      <td align="center" border=1 bordercolor=#2C1701>
      <?//echo "<a href='$link_l' target='_blank'><img src='graficos\_vacuna_gripe".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      <td align="center" border=1 bordercolor=#2C1701>
      <?//echo "<a href='$link_l' target='_blank'><img src='graficos\_vacuna_hep_a".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      </tr>
      
      <tr>
      <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
      Total de Vacunas Tri.Bac.Celular: <b><?php //echo $efe_triple_bacteriana_celular?> / Meta Anual: <?php //echo $vacuna_triple_bacteriana_celular?> </b>
      </td> 
      <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
      Total de Vacunas Tri.Bac.Acelular: <b><?php //echo $efe_triple_bacteriana_acelular?> / Meta Anual: <?php //echo $vacuna_triple_bacteriana_acelular?> </b>
      </td>            
            </tr>
      
      <tr>
      <td align="center" border=1 bordercolor=#2C1701>
      <?//echo "<a href='$link_l' target='_blank'><img src='graficos\_vacuna_triple_bacteriana_celular".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      <td align="center" border=1 bordercolor=#2C1701>
      <?//echo "<a href='$link_l' target='_blank'><img src='graficos\_vacuna_triple_bacteriana_acelular".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      </tr>
      
      <tr>
      <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
      Total de Vacunas Doble Bacteriana: <b><?php //echo $efe_doble_bacteriana?> / Meta Anual: <?php //echo $vacuna_doble_bacteriana?> </b>
      </td> 
      <td align="center" border=1 bordercolor=#2C1701 <?php //echo atrib_tr7()?>>
      Total de Vacunas VPH: <b><?php //echo $efe_hpv?> / Meta Anual: <?php //echo $vacuna_vph?> </b>
      </td>            
      </tr>
      
      <tr>
      <td align="center" border=1 bordercolor=#2C1701>
      <?//echo "<a href='$link_l' target='_blank'><img src='graficos\_vacuna_doble_bacteriana".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      <td align="center" border=1 bordercolor=#2C1701>
      <?//echo "<a href='$link_l' target='_blank'><img src='graficos\_vacuna_vph".$cuie.".png'  border=0 align=top></a>\n";?>
      </td>
      </tr>
      
</table>
</page>  
-->   

<?$content = ob_get_clean(); 
   $html2pdf = new HTML2PDF('P','A4','es',array(mL, mT, mR, mB));
   $html2pdf->WriteHTML($content);
   $file="cumplimiento_".$cuie."_".$fecha_desde."_".$fecha_hasta;
   $html2pdf->Output("$file.pdf",'D');?> 
   		    
