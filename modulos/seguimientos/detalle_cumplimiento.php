<?

require_once ("../../config.php");
require_once("funciones_seguimientos.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

function extrae_anio($fecha) {
        list($d,$m,$a) = explode("/",$fecha);
        //$a=$a+2000;
        return $a;
		}


$user=$_ses_user['login'];

if ($id_efe_conv) {
	$query ="SELECT 
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
else {

	$cuie=$_ses_user['login'];
	$sql_cuie="select * from nacer.efe_conv where cuie='$cuie'";
	$res_cuie= sql($sql_cuie, "Error al traer el Efector") or fin_pagina();
	$id_efe_conv=$res_cuie->fields['id_efe_conv'];
	}

if ($_POST['muestra']=="Muestra"){	
	
	$fecha_desde=fecha_db($_POST['fecha_desde']);
	$fecha_hasta=fecha_db($_POST['fecha_hasta']);
	
	$embarazadas=embarazadas($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$ninios_new_1=ninios_menores_1_anio($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$ninios_1_a_9=ninios_entre_1_y_9_anios($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$adol_new_pers_3=adolescentes($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$adultos=control_adultos($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$dia=diabeticos($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$hip=hipertensos($fecha_desde,$fecha_hasta,$cuie)->RecordCount();

}

$param=array("ninios_new_1"=>$ninios_new_1,
            "ninios_1_a_9"=>$ninios_1_a_9,
            "embarazadas"=>$embarazadas,
            "adol_new_pers_3"=>$adol_new_pers_3,
			"adultos"=>$adultos,
            "dia"=>$dia,
            "hip"=>$hip
            );


//devolucion de metas anuales
$query_meta="select *  from nacer.metas_2024 where cuie='$cuie'";
$res_query_meta=sql($query_meta, "Error al traer el Efector") or fin_pagina();

$promedio_controles_x_emb=$res_query_meta->fields['embarazadas'];
$cns_menor_1_año=$res_query_meta->fields['ninio_1'];
$cns_entre_1_y_9=$res_query_meta->fields['ninio_1_9'];
$adolecentes=$res_query_meta->fields['adolecentes'];
$adultos_metas=$res_query_meta->fields['adultos'];
$enfermedades_cronicas_HTA=$res_query_meta->fields['hta'];
$enfermedades_cronicas_DBT=$res_query_meta->fields['dbt'];

  
$metas=array("promedio_controles_x_emb"=>$promedio_controles_x_emb,
            "cns_menor_1_año"=>$cns_menor_1_año,
            "cns_entre_1_y_9"=>$cns_entre_1_y_9,
            "adolecentes"=>$adolecentes,
			"adultos"=>$adultos_metas,
            "enfermedades_cronicas_HTA"=>$enfermedades_cronicas_HTA,
            "enfermedades_cronicas_DBT"=>$enfermedades_cronicas_DBT,
            );


echo $html_header;
?>
<script>
function control_muestra()
{ 
 if(document.all.fecha_desde.value==""){
  alert('Debe Ingresar una Fecha DESDE');
  return false;
 } 
 if(document.all.fecha_hasta.value==""){
  alert('Debe Ingresar una Fecha HASTA');
  return false;
 } 
 if(document.all.fecha_hasta.value<document.all.fecha_desde.value){
  alert('La Fecha HASTA debe ser MAYOR 0 IGUAL a la Fecha DESDE');
  return false;
 }
 if(document.all.fecha_desde.value.indexOf("-")!=-1){
	  alert('Debe ingresar un fecha en el campo DESDE');
	  return false;
	 }
if(document.all.fecha_hasta.value.indexOf("-")!=-1){
	  alert('Debe ingresar una fecha en el campo HASTA');
	  return false;
	 }
return true;
}
</script>

<form name='form1' action='detalle_cumplimiento.php' method='POST'>
<input type="hidden" value="<?php echo $id_efe_conv?>" name="id_efe_conv">
<input type="hidden" value="<?php echo $cuie?>" name="cuie">

<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<b>	
		<?if ($fecha_desde=='') $fecha_desde=DATE ('01/01/2023');
		if ($fecha_hasta=='') $fecha_hasta=DATE ('31/12/2023');?>		
		Desde: <input type=text id="fecha_desde" name="fecha_desde" value='<?php echo $fecha_desde?>' size=15 readonly>
		<?php echo link_calendario("fecha_desde");?>
		
		Hasta: <input type=text id="fecha_hasta" name="fecha_hasta" value='<?php echo $fecha_hasta?>' size=15 readonly>
		<?php echo link_calendario("fecha_hasta");?> 
		
		   
	    
	    &nbsp;&nbsp;&nbsp;
	    <input type="submit" class="btn btn-success" name="muestra" value='Muestra' onclick="return control_muestra()" >
	    </b>
	    
	    &nbsp;&nbsp;&nbsp;	    
        <?if ($_POST['muestra']){
         	
        $link=encode_link("efec_cumplimiento_pdf.php",array("id_efe_conv"=>$id_efe_conv,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta,"param"=>$param,"metas"=>$metas));?> 
        <!--<img src="../../imagenes/pdf_logo.gif" style='cursor:hand;'  onclick="window.open('<?php echo $link?>')"> -->
        <?}?>
	  </td>
       
     </tr>
     
    
     
</table>
<table width="98%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
    	<font size=+1><b>Efector: <?echo $cuie.". Desde: ".fecha($fecha_desde)." Hasta: ".fecha($fecha_hasta)?> </b></font>        
    </td>
 </tr>
 <tr><td>
  <table width=100% align="center" class="bordes">
     <tr><td id=mo colspan="5"><b> Descripcion del Efector</b></td></tr>
		<tr>
		<td><table align="center">
		<td align="right"><b>Nombre:</b></td>
		<td align="left"><input type="text" size="40" value="<?php echo $nombre?>" name="nombre" readonly></td>
    </tr>
         
    <tr>	           
    <tr><td align="right"><b>Domicilio:</b></td>
		<td align="left"><input type="text" size="40" value="<?php echo $domicilio?>" name="domicilio" readonly></td>
    </tr>
         
    <tr>
    <td align="right"><b>Departamento:</b></td>
	<td align="left"><input type="text" size="40" value="<?php echo $departamento?>" name="departamento" readonly></td>
    </tr>
         
    <tr>
    <td align="right"><b>Localidad:</b></td>
	<td align="left"><input type="text" size="40" value="<?php echo $localidad?>" name="localidad" readonly></td>
    </tr>
    
</table></td>      
    <td><table align="center">        
        <tr>
		<td align="right"><b>Codigo Postal:</b></td>
		<td align="left"><input type="text" size="40" value="<?php echo $cod_pos?>" name="cod_pos" readonly></td>
        </tr>
         
        <tr>
        <td align="right"><b>Cuidad:</b></td>
		<td align="left"><input type="text" size="40" value="<?php echo $cuidad?>" name="cuidad" readonly></td>
         </tr>
         
        <tr>
        <td align="right"><b>Referente:</b></td>
		<td align="left"><input type="text" size="40" value="<?php echo $referente?>" name="referente" readonly></td>
        </tr>
         
        <tr>
	    <td align="right"><b>Telefono:</b></td>
		<td align="left"><input type="text" size="40" value="<?php echo $tel?>" name="tel" readonly></td>
        </tr>          
    </table></td></tr> 
           
 </table>           

<?if ($_POST['muestra']){?>
<table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
		<tr align="center" id="sub_tabla">
		 	
</table>

<table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">		 
		 <tr align="center" id="sub_tabla">
		 	<td colspan=10>	
		 	<font size=3 >Detalle sobre cumplimientos de Poblacion a Cargo <BR> </font>
		 	</td>
		 </tr>

		<tr align="center" id="sub_tabla">
		 	<td colspan=10>	
		 	<!--<font size=4 color="red" ><b>Nota Importante: las metas anuales estan fijadas con periodo desde <?echo Fecha($fecha_desde)?> al <?echo Fecha($fecha_hasta)?> <BR></b> </font>
		 	<font size=4 color="red" ><b>Las mismas son evaluadas al 50% de la meta anual para el cumplimiento del primer semestre <BR></b> </font>
			-->		 	
			</td>
		 </tr>
				
		<tr>
		<?$ref_cns_1 = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"controles de ninos menor de 1","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
		$onclick_cns_1="window.open('$ref_cns_1' , '_blank');";?>

		<td align="center" border=1 bordercolor=#2C1701 onclick="<?php echo $onclick_cns_1?>" <?php echo atrib_tr7()?>>
			<?$porcentaje=($ninios_new_1/$cns_menor_1_año)*100;
			$porcentaje=number_format ($porcentaje,2,',','.')?>
			Cantidad de niños controlados <1 año según periodo: <b><?php echo($ninios_new_1)?$ninios_new_1:0?> / </b><font size=2 color= red> <b>Población a cargo: <?php echo $cns_menor_1_año?> </b></font>
			<font size=2 color=green><b>(<?php echo $porcentaje?> %) </b></font>
		</td>   
		
		<?$ref_cns_1_9 = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"controles_1_a_9","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
		$onclick_cns_1_9="window.open('$ref_cns_1_9' , '_blank');";?>
		
		<td align="center" border=1 bordercolor=#2C1701 onclick="<?php echo $onclick_cns_1_9?>" <?php echo atrib_tr7()?>>
		<? $porcentaje=($ninios_1_a_9/$cns_entre_1_y_9)*100;
			$porcentaje=number_format ($porcentaje,2,',','.')?>
			Cantidad de niños controlados de 1 a 9 años según periodo: <b><?php echo($ninios_1_a_9)?$ninios_1_a_9:0?> / </b> <!--<font size=2 color= red> <b>Meta anual: <?php echo $cns_entre_1_y_9?> / </b></font>--> <font size=2 color=red> <b>Población a cargo: <?php echo $cns_entre_1_y_9?> </b></font>
			<font size=2 color=green><b>(<?php echo $porcentaje?> %) </b></font>
		</td>	   	
		</tr> 
		<tr>
			
		<td align="center" border=1 bordercolor=#2C1701>
		<? $link_s=encode_link("metas_grafico.php",array("dato"=>$ninios_new_1,"metarrhh_s"=>$cns_menor_1_año,"tamaño"=>"small","nombre"=>"cns_menor_1_año","cuie"=>$cuie));
		echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
		</td>
		<td align="center" border=1 bordercolor=#2C1701>
		<? $link_s=encode_link("metas_grafico.php",array("dato"=>$ninios_1_a_9,"metarrhh_s"=>$cns_entre_1_y_9,"tamaño"=>"small","nombre"=>"_ninios_total","cuie"=>$cuie));
		echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
		</td>
		</tr>		
			
		<tr>
		<?$ref_cont_emb = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"total_controles_embar","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
		$onclick_cont_emb="window.open('$ref_cont_emb' , '_blank');";?>
		
		<td align="center" border=1 bordercolor=#2C1701 onclick="<?php echo $onclick_cont_emb?>" <?php echo atrib_tr7()?>>
			<?$porcentaje=($embarazadas/$promedio_controles_x_emb)*100;
			$porcentaje=number_format ($porcentaje,2,',','.')?>
			Cantidad de Mujeres y personas embarazadas controladas según periodo: <b><?php echo ($embarazadas)?$embarazadas:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?php echo $promedio_controles_x_emb?> / </b></font> --><font size=2 color= red> <b>Población a cargo: <?php echo $promedio_controles_x_emb?> </b></font>
			<font size=2 color=green><b>(<?php echo $porcentaje?> %) </b></font>
			</td>	
		 	

		<?$ref_adol = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"adolescentes","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
		$onclick_adol="window.open('$ref_adol' , '_blank');";?>
		
		<td align="center" border=1 bordercolor=#2C1701 onclick="<?php echo $onclick_adol?>" <?php echo atrib_tr7()?>>
			<?$porcentaje=($adol_new_pers_3/$adolecentes)*100;
			$porcentaje=number_format ($porcentaje,2,',','.')?>
			Cantidad de niños controlados de 10 a 19 años según periodo: <b><?php echo ($adol_new_pers_3)?$adol_new_pers_3:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?php echo $adolecentes?> / </b></font>--> <font size=2 color= red> <b>Población a cargo: <?php echo $adolecentes?> </b></font>
			<font size=2 color=green><b>(<?php echo $porcentaje?> %) </b></font>
			</td> 
			</tr>
		 	<tr>
			
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$embarazadas,"metarrhh_s"=>$promedio_controles_x_emb,"tamaño"=>"small","nombre"=>"embarazadas","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$adol_new_pers_3,"metarrhh_s"=>$adolecentes,"tamaño"=>"small","nombre"=>"adol_new_pers_3","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			</tr>
		 	
			<tr>
			<?$ref_hip = encode_link("detalle_hip.php",array("cuie"=>$cuie,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_elegir="location.href='$ref_hip' target='_blank'";
				$onclick_elegir="window.open('$ref_hip' , '_blank');";?>
			<td align="center" border=1 bordercolor=#2C1701 onclick="<?php echo $onclick_elegir?>" <?php echo atrib_tr7()?>>
				<?if ($enfermedades_cronicas_HTA!=0) $porcentaje=($hip/$enfermedades_cronicas_HTA)*100;
					else $porcentaje=0;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Población a cargo de >=18 años empadronados con Hipertensión según periodo: <b><?php echo ($hip)?$hip:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?php echo $enfermedades_cronicas_HTA?> / </b></font> --><font size=2 color= red> <b>Población a cargo: <?php echo $enfermedades_cronicas_HTA?> </b></font>
				<font size=2 color=green><b>(<?php echo $porcentaje?> %) </b></font>
			</td>

            <?$ref_diab = encode_link("detalle_diab.php",array("cuie"=>$cuie,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
					$onclick_elegir="location.href='$ref_diab' target='_blank'";
					$onclick_elegir="window.open('$ref_diab' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?php echo $onclick_elegir?>" <?php echo atrib_tr7()?>>
				<?if ($enfermedades_cronicas_DBT!=0) $porcentaje=($dia/$enfermedades_cronicas_DBT)*100;
				else $porcentaje=0;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Población a cargo de >=18 años empadronados por diabetes mellitus tipo 2 según periodo: <b><?php echo ($dia)?$dia:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?php echo $enfermedades_cronicas_DBT?> / </b></font>--> <font size=2 color= red> <b>Población a cargo: <?php echo $enfermedades_cronicas_DBT?> </b></font>
				<font size=2 color=green><b>(<?php echo $porcentaje?> %) </b></font>
				</td> 
			</tr>
            
			<tr>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$hip,"metarrhh_s"=>$enfermedades_cronicas_HTA,"tamaño"=>"small","nombre"=>"hip","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>

			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$dia,"metarrhh_s"=>$enfermedades_cronicas_DBT,"tamaño"=>"small","nombre"=>"dia","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			</tr>

			<!--controles adultos-->
			<tr>
			<?$ref_adul = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"control de adultos","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_elegir="location.href='$ref_adul' target='_blank'";
				$onclick_elegir="window.open('$ref_adul' , '_blank');";?>
			<td align="center" border=1 bordercolor=#2C1701 onclick="<?php echo $onclick_elegir?>" <?php echo atrib_tr7()?>>
				<?if ($adultos_metas!=0) $porcentaje=($adultos/$adultos_metas)*100;
					else $porcentaje=0;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Población a cargo de adultos (mayores de 19 años) empadronados según periodo: <b><?php echo ($adultos)?$adultos:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?php echo $adultos?> / </b></font> --><font size=2 color= red> <b>Población a cargo: <?php echo $adultos_metas?> </b></font>
				<font size=2 color=green><b>(<?php echo $porcentaje?> %) </b></font>
			</td>
			</tr>
            
			<tr>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$adultos,"metarrhh_s"=>$adultos_metas,"tamaño"=>"small","nombre"=>"adultos","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			</tr>
						 
</table>



<?}?>
<BR>
 <tr><td><table width=90% align="center" class="bordes">
  <tr align="center">
  <?
   if (!es_cuie($user)){ ?>
		<td>
     	<input type="button" class="btn btn-primary" name="volver" value="Volver" onclick="document.location='seguimiento.php'"title="Volver al Listado">     
   		</td>
   <?} 
   else {?>

   		<td>
     	<input type=button name="volver" value="Volver" onclick="document.location='efectores_detalle.php'"title="Volver al Listado" style="width=150px">     
   		</td>
  <?}?>
  </tr>
 </table></td></tr>
 
 
 </table>
 </form>
 
 <?php echo fin_pagina();// aca termino ?>
