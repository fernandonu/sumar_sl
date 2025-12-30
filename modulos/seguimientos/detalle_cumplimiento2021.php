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
	
	$emb_riesgo=embarazo_riesgo($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$talleres=talleres($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$embarazadas=embarazadas($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$embarazadas_12_pers=emb_sem_12($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$ninios_new_1=ninios_menores_1_anio($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$ninios_1_a_9=ninios_entre_1_y_9_anios($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$adol_new_pers_3=adolescentes($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$cuidado_sexual=cuidado_sexual($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$dia=diabeticos($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
	$hip=hipertensos($fecha_desde,$fecha_hasta,$cuie)->RecordCount();
			
	
//aqi antes estaba las vacunas
	$vacunas=vacunas($fecha_desde,$fecha_hasta,$cuie);
	$efe_hep_b=$vacunas['efe_hep_b'];
    $efe_neumococo=$vacunas['efe_neumococo'];
    $efe_pentavalente=$vacunas['efe_pentavalente'];
    $efe_sabin=$vacunas['efe_sabin'];
    $efe_triple_viral=$vacunas['efe_triple_viral'];
    $efe_gripe=$vacunas['efe_gripe'];
    $efe_hep_a=$vacunas['efe_hep_a'];
    $efe_triple_bacteriana_celular=$vacunas['efe_triple_bacteriana_celular'];
    $efe_triple_bacteriana_acelular=$vacunas['efe_triple_bacteriana_acelular'];
    $efe_doble_bacteriana=$vacunas['efe_doble_bacteriana'];
    $efe_hpv=$vacunas['efe_hpv'];
    $efe_doble_viral=$vacunas['efe_doble_viral'];
    $efe_fiebre_amarilla=$vacunas['efe_fiebre_amarilla'];

//llenar con las consultas
}

$param=array("ninios_new_1"=>$ninios_new_1,
             "ninios_1_a_9"=>$ninios_1_a_9,
             "embarazadas_12_pers"=>$embarazadas_12_pers,
             "embarazadas"=>$embarazadas,
             "adol_new_pers_3"=>$adol_new_pers_3,
             "cuidado_sexual"=>$cuidado_sexual,
             "dia"=>$dia,
             "hip"=>$hip,
             "talleres"=>$talleres,
             "embarazo_riesgo"=>$embarazo_riesgo,
             "efe_hep_b"=>$efe_hep_b,
    		"efe_neumococo"=>$efe_neumococo,
    		"efe_pentavalente"=>$efe_pentavalente,
    		"efe_sabin"=>$efe_sabin,
    		"efe_triple_viral"=>$efe_triple_viral,
    		"efe_gripe"=>$efe_gripe,
    		"efe_hep_a"=>$efe_hep_a,
    		"efe_triple_bacteriana_celular"=>$efe_triple_bacteriana_celular,
    		"efe_triple_bacteriana_acelular"=>$efe_triple_bacteriana_acelular,
    		"efe_doble_bacteriana"=>$efe_doble_bacteriana,
    		"efe_hpv"=>$efe_hpv,
    		"efe_doble_viral"=>$efe_doble_viral,
    		"efe_fiebre_amarilla"=>$efe_fiebre_amarilla
             );


//devolucion de metas anuales
$query_meta="select *  from nacer.metas where cuie='$cuie'";
$res_query_meta=sql($query_meta, "Error al traer el Efector") or fin_pagina();

$captacion_temprana=$res_query_meta->fields['captacion_temprana'];
$promedio_controles_x_emb=$res_query_meta->fields['promedio_controles_x_emb'];
$mujeres_edad_fertil=$res_query_meta->fields['mujeres_edad_fertil'];
$cns_menor_1_año=$res_query_meta->fields['cns_menor_1_anio'];
$cns_entre_1_y_9=$res_query_meta->fields['cns_entre_1_y_9'];
$adolecentes=$res_query_meta->fields['adolecentes'];
$enfermedades_cronicas_HTA=$res_query_meta->fields['hta'];
$enfermedades_cronicas_DBT=$res_query_meta->fields['dbt'];
$vacuna_hep_b=$res_query_meta->fields['hep_b'];
$vacuna_neumococo=$res_query_meta->fields['neumococo'];
$vacuna_pentavalente=$res_query_meta->fields['pentavalente'];
$vacuna_sabin=$res_query_meta->fields['sabin'];
$vacuna_triple_viral=$res_query_meta->fields['triple_viral'];
$vacuna_gripe=$res_query_meta->fields['gripe'];
$vacuna_hep_a=$res_query_meta->fields['hep_a'];
$vacuna_triple_bacteriana_celular=$res_query_meta->fields['triple_bacteriana_celular'];
$vacuna_triple_bacteriana_acelular=$res_query_meta->fields['triple_bacteriana_acelular'];
$vacuna_doble_bacteriana=$res_query_meta->fields['doble_bacteriana'];
$vacuna_vph=$res_query_meta->fields['vph'];
$vacuna_doble_viral=$res_query_meta->fields['doble_viral'];
$vacuna_fiebre_amarilla=$res_query_meta->fields['fiebre_amarilla'];
$meta_emb_riesgo=$res_query_meta->fields['emb_riesgo'];
$meta_talleres=$res_query_meta->fields['talleres'];


/*$sql_sitam="select cantidad from nacer.sitam where cuie='$cuie'";
$res_sitam=sql($sql_sitam,"Error al traer los datos del sitam") or fin_pagina();
$paps=$res_sitam->fields['cantidad'];*/
  
$metas=array("captacion_temprana"=>$captacion_temprana,
            "promedio_controles_x_emb"=>$promedio_controles_x_emb,
            "mujeres_edad_fertil"=>$mujeres_edad_fertil,
            "cns_menor_1_año"=>$cns_menor_1_año,
            "cns_entre_1_y_9"=>$cns_entre_1_y_9,
            "adolecentes"=>$adolecentes,
            "enfermedades_cronicas_HTA"=>$enfermedades_cronicas_HTA,
            "enfermedades_cronicas_DBT"=>$enfermedades_cronicas_DBT,
            "vacuna_hep_b"=>$vacuna_hep_b,
            "vacuna_neumococo"=>$vacuna_neumococo,
            "vacuna_pentavalente"=>$vacuna_pentavalente,
            "vacuna_sabin"=>$vacuna_sabin,
            "vacuna_triple_viral"=>$vacuna_triple_viral,
            "vacuna_gripe"=>$vacuna_gripe,
            "vacuna_hep_a"=>$vacuna_hep_a,
            "vacuna_triple_bacteriana_celular"=>$vacuna_triple_bacteriana_celular,
            "vacuna_triple_bacteriana_acelular"=>$vacuna_triple_bacteriana_acelular,
            "vacuna_doble_bacteriana"=>$vacuna_doble_bacteriana,
            "vacuna_vph"=>$vacuna_vph,
            "vacuna_doble_viral"=>$vacuna_doble_viral,
            "vacuna_fiebre_amarilla"=>$vacuna_fiebre_amarilla,
            "emb_riesgo"=>$meta_emb_riesgo,
            "meta_talleres"=>$meta_talleres);


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
<input type="hidden" value="<?=$id_efe_conv?>" name="id_efe_conv">
<input type="hidden" value="<?=$cuie?>" name="cuie">

<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<b>	
		<?if ($fecha_desde=='') $fecha_desde=DATE ('01/01/2021');
		if ($fecha_hasta=='') $fecha_hasta=DATE ('31/12/2021');?>		
		Desde: <input type=text id="fecha_desde" name="fecha_desde" value='<?=$fecha_desde?>' size=15 readonly>
		<?=link_calendario("fecha_desde");?>
		
		Hasta: <input type=text id="fecha_hasta" name="fecha_hasta" value='<?=$fecha_hasta?>' size=15 readonly>
		<?=link_calendario("fecha_hasta");?> 
		
		   
	    
	    &nbsp;&nbsp;&nbsp;
	    <input type="submit" class="btn btn-success" name="muestra" value='Muestra' onclick="return control_muestra()" >
	    </b>
	    
	    &nbsp;&nbsp;&nbsp;	    
        <?if ($_POST['muestra']){
         	
        $link=encode_link("efec_cumplimiento_pdf.php",array("id_efe_conv"=>$id_efe_conv,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta,"param"=>$param,"metas"=>$metas));?> 
        <img src="../../imagenes/pdf_logo.gif" style='cursor:hand;'  onclick="window.open('<?=$link?>')"> 
        <?}?>
	  </td>
       
     </tr>
     
    
     
</table>
<table width="98%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
    	<font size=+1><b>Efector: <?echo $cuie.". Desde: ".fecha($fecha_desde)." Hasta: ".fecha($fecha_hasta)?> </b></font>        
    </td>
 </tr>
 <tr><td>
  <table width=100% align="center" class="bordes">
     <tr>
      <td id=mo colspan="5">
       <b> Descripcion del Efector</b>
      </td>
     </tr>
     <tr>
       <td>
        <table align="center">
                
         <td align="right">
				<b>Nombre:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$nombre?>" name="nombre" readonly>
            </td>
         </tr>
         
         <tr>	           
           
         <tr>
         <td align="right">
				<b>Domicilio:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$domicilio?>" name="domicilio" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Departamento:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$departamento?>" name="departamento" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Localidad:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$localidad?>" name="localidad" readonly>
            </td>
         </tr>
        </table>
      </td>      
      <td>
        <table align="center">        
         <tr>
         <td align="right">
				<b>Codigo Postal:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$cod_pos?>" name="cod_pos" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Cuidad:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$cuidad?>" name="cuidad" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Referente:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$referente?>" name="referente" readonly>
            </td>
         </tr>
         
         <tr>
	     <td align="right">
				<b>Telefono:</b>
			</td>
			<td align="left">		 
	          <input type="text" size="40" value="<?=$tel?>" name="tel" readonly>
	        </td>
         </tr>          
        </table>
      </td>  
       
     </tr> 
           
 </table>           

<?if ($_POST['muestra']){?>
<table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
		<tr align="center" id="sub_tabla">
		 	
</table>

<table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">		 
		 <tr align="center" id="sub_tabla">
		 	<td colspan=10>	
		 	<font size=3 >Detalle sobre cumplimientos de metas <BR> </font>
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

		<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_cns_1?>" <?=atrib_tr7()?>>
			<?$porcentaje=($ninios_new_1/$cns_menor_1_año)*100;
			$porcentaje=number_format ($porcentaje,2,',','.')?>
			Total de Controles de Ninos menor de 1 año segun periodo (por fecha de control): <b><?=($ninios_new_1)?$ninios_new_1:0?> / </b><font size=2 color= red> <b>Meta Anual x RRHH: <?=$cns_menor_1_año?> </b></font>
			<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
		</td>   
		
		<?$ref_cns_1_9 = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"controles_1_a_9","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
		$onclick_cns_1_9="window.open('$ref_cns_1_9' , '_blank');";?>
		
		<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_cns_1_9?>" <?=atrib_tr7()?>>
		<? $porcentaje=($ninios_1_a_9/$cns_entre_1_y_9)*100;
			$porcentaje=number_format ($porcentaje,2,',','.')?>
			Total de Controles de Niños de 1 a 9 años segun periodo (por fecha de control): <b><?=($ninios_1_a_9)?$ninios_1_a_9:0?> / </b> <!--<font size=2 color= red> <b>Meta anual: <?=$cns_entre_1_y_9?> / </b></font>--> <font size=2 color=red> <b>Meta Anual x RRHH: <?=$cns_entre_1_y_9?> </b></font>
			<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
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
		<?$ref_ssr = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"cuidado_sexual","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
			$onclick_ssr="window.open('$ref_ssr' , '_blank');";?>
		
			<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_ssr?>" <?=atrib_tr7()?>>
				<?$porcentaje=($cuidado_sexual/$mujeres_edad_fertil)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Inscriptos que Marca Cuidado Sexual y Reproductivo: <b><?=($cuidado_sexual)?$cuidado_sexual:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$mujeres_edad_fertil?> / </b></font>--> <font size=2 color= red> <b>Meta Anual x RRHH: <?=$mujeres_edad_fertil?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
			</td>
		
		<?$ref_emb_12 = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"embar_antes_sem_12","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
		$onclick_emb_12="window.open('$ref_emb_12' , '_blank');";?>
		
		<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_emb_12?>" <?=atrib_tr7()?>>
		<?$porcentaje=($embarazadas_12_pers/$captacion_temprana)*100;
		$porcentaje=number_format ($porcentaje,2,',','.')?>
		Total de Embarazadas antes de las 12 semanas: <b><?=($embarazadas_12_pers)?$embarazadas_12_pers:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$captacion_temprana?> / </b></font>--> <font size=2 color= red> <b>Meta Anual : <?=$captacion_temprana?> </b></font>
		<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
		</td>    	
		     
		</tr>
		<tr>
		<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$cuidado_sexual,"metarrhh_s"=>$mujeres_edad_fertil,"tamaño"=>"small","nombre"=>"cuidado_sexual","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
		</td>

		<td align="center" border=1 bordercolor=#2C1701>
		<? $link_s=encode_link("metas_grafico.php",array("dato"=>$embarazadas_12_pers,"metarrhh_s"=>$captacion_temprana,"tamaño"=>"small","nombre"=>"embarazadas_12_pers","cuie"=>$cuie));
		echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
		</td>
		</tr>
		
		<tr>
		<?$ref_cont_emb = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"total_controles_embar","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
		$onclick_cont_emb="window.open('$ref_cont_emb' , '_blank');";?>
		
		<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_cont_emb?>" <?=atrib_tr7()?>>
			<?$porcentaje=($embarazadas/$promedio_controles_x_emb)*100;
			$porcentaje=number_format ($porcentaje,2,',','.')?>
			Total de Controles de Embarazo segun periodo (por fecha de control): <b><?=($embarazadas)?$embarazadas:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$promedio_controles_x_emb?> / </b></font> --><font size=2 color= red> <b>Meta Anual: <?=$promedio_controles_x_emb?> </b></font>
			<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
			</td>	
		 	

		<?$ref_adol = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"adolescentes","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
		$onclick_adol="window.open('$ref_adol' , '_blank');";?>
		
		<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_adol?>" <?=atrib_tr7()?>>
			<?$porcentaje=($adol_new_pers_3/$adolecentes)*100;
			$porcentaje=number_format ($porcentaje,2,',','.')?>
			Total de Controles Adolesc. de 10 a 19 años segun periodo (por fecha de control): <b><?=($adol_new_pers_3)?$adol_new_pers_3:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$adolecentes?> / </b></font>--> <font size=2 color= red> <b>Meta Anual x RRHH: <?=$adolecentes?> </b></font>
			<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
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
			<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_elegir?>" <?=atrib_tr7()?>>
				<?if ($enfermedades_cronicas_HTA!=0) $porcentaje=($hip/$enfermedades_cronicas_HTA)*100;
					else $porcentaje=0;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Controles que Marca Hipertenso: <b><?=($hip)?$hip:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$enfermedades_cronicas_HTA?> / </b></font> --><font size=2 color= red> <b>Meta Anual x RRHH: <?=$enfermedades_cronicas_HTA?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
			</td>

            <?$ref_diab = encode_link("detalle_diab.php",array("cuie"=>$cuie,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
					$onclick_elegir="location.href='$ref_diab' target='_blank'";
					$onclick_elegir="window.open('$ref_diab' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_elegir?>" <?=atrib_tr7()?>>
				<?if ($enfermedades_cronicas_DBT!=0) $porcentaje=($dia/$enfermedades_cronicas_DBT)*100;
				else $porcentaje=0;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Controles que Marca Diabetico: <b><?=($dia)?$dia:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$enfermedades_cronicas_DBT?> / </b></font>--> <font size=2 color= red> <b>Meta Anual x RRHH: <?=$enfermedades_cronicas_DBT?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
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
			
			
			<tr>
			<?$ref_emb_riesgo = encode_link("detalle_emb_riesgos.php",array("cuie"=>$cuie,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_elegir="location.href='$ref_emb_riesgo' target='_blank'";
				$onclick_elegir="window.open('$ref_emb_riesgo' , '_blank');";?>
			<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_elegir?>" <?=atrib_tr7()?>>
				<?$porcentaje=($emb_riesgo/$meta_emb_riesgo)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Notificaciones de Embarazadas con Factores de Riesgo: <b><?=($emb_riesgo)?$emb_riesgo:0?> / </b><font size=2 color= red> <b>Meta Anual x RRHH: <?=$meta_emb_riesgo?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
			</td>

            <?$ref_talleres = encode_link("detalle_talleres.php",array("cuie"=>$cuie,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
					$onclick_elegir="location.href='$ref_talleres' target='_blank'";
					$onclick_elegir="window.open('$ref_talleres' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_elegir?>" <?=atrib_tr7()?>>
				<?$porcentaje=($talleres/$meta_talleres)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Talleres: <b><?=($talleres)?$talleres:0?> / </b><font size=2 color= red> <b>Meta Anual x RRHH: <?=$meta_talleres?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
				</td> 
			</tr>
            
			<tr>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$emb_riesgo,"metarrhh_s"=>$meta_emb_riesgo,"tamaño"=>"small","nombre"=>"emb_riesgo","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>

			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$talleres,"metarrhh_s"=>$meta_talleres,"tamaño"=>"small","nombre"=>"_talleres","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			</tr>

			
			<tr>
			<?$ref_vacunas = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"pentavalente","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
			$onclick_vacunas="window.open('$ref_vacunas' , '_blank');";?>
			<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_vacunas?>" <?=atrib_tr7()?>>
			<?$porcentaje=($efe_pentavalente/$vacuna_pentavalente)*100;
			$porcentaje=number_format ($porcentaje,2,',','.')?>
			Total de Vacunas Pentavalentes: <b><?=($efe_pentavalente)?$efe_pentavalente:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$vacuna_pentavalente?> / </b></font> --><font size=2 color= red> <b>Meta Anual: <?=$vacuna_pentavalente?> </b></font>
			<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
			</td> 	
				
			<?$ref_vacunas = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"doble_viral","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
			$onclick_vacunas="window.open('$ref_vacunas' , '_blank');";?>
			<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_vacunas?>" <?=atrib_tr7()?>>
				<?$porcentaje=($efe_doble_viral/$vacuna_doble_viral)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Vacunas Doble Viral: <b><?=($efe_doble_viral)?$efe_doble_viral:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$vacuna_doble_viral?> / </b></font>--> <font size=2 color= red> <b>Meta Anual: <?=$vacuna_doble_viral?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
			</td>
			</tr>
            
            <tr>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$efe_pentavalente,"metarrhh_s"=>$vacuna_pentavalente,"tamaño"=>"small","nombre"=>"_vacuna_pentavalente","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>			
			
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$efe_doble_viral,"metarrhh_s"=>$vacuna_doble_viral,"tamaño"=>"small","nombre"=>"_vacuna_doble_viral","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			</tr> 
			 
			 <!-- graficos para vacunas -->
			 
			<tr>
				<?$ref_vacunas = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"hep_b","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_vacunas="window.open('$ref_vacunas' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_vacunas?>" <?=atrib_tr7()?>>
				<?$porcentaje=($efe_hep_b/$vacuna_hep_b)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Vacunas Hepatitis B: <b><?=($efe_hep_b)?$efe_hep_b:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$vacuna_hep_b?> / </b></font>--> <font size=2 color= red> <b>Meta Anual: <?=$vacuna_hep_b?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
				</td> 
				
				<?$ref_vacunas = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"neumococo","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_vacunas="window.open('$ref_vacunas' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_vacunas?>" <?=atrib_tr7()?>>
				<?$porcentaje=($efe_neumococo/$vacuna_neumococo)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Vacunas Neumococo: <b><?=($efe_neumococo)?$efe_neumococo:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$vacuna_neumococo?> / </b></font>--> <font size=2 color= red> <b>Meta Anual: <?=$vacuna_neumococo?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
				</td>            
            </tr>
			<tr>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$efe_hep_b,"metarrhh_s"=>$vacuna_hep_b,"tamaño"=>"small","nombre"=>"_vacuna_hep_b","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$efe_neumococo,"metarrhh_s"=>$vacuna_neumococo,"tamaño"=>"small","nombre"=>"_vacuna_neumococo","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			</tr>
							
			<tr>
				<?$ref_vacunas = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"sabin","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_vacunas="window.open('$ref_vacunas' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_vacunas?>" <?=atrib_tr7()?>>
				<?$porcentaje=($efe_sabin/$vacuna_sabin)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Vacunas Sabin/Salk: <b><?=($efe_sabin)?$efe_sabin:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$vacuna_sabin?> / </b></font>--> <font size=2 color= red> <b>Meta Anual: <?=$vacuna_sabin?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
				</td> 
				
				<?$ref_vacunas = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"triple_viral","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_vacunas="window.open('$ref_vacunas' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_vacunas?>" <?=atrib_tr7()?>>
				<?$porcentaje=($efe_triple_viral/$vacuna_triple_viral)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Vacunas Triple Viral: <b><?=($efe_triple_viral)?$efe_triple_viral:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$vacuna_triple_viral?> / </b></font>--> <font size=2 color= red> <b>Meta Anual: <?=$vacuna_triple_viral?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
				</td>            
            </tr>
			<tr>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$efe_sabin,"metarrhh_s"=>$vacuna_sabin,"tamaño"=>"small","nombre"=>"_vacuna_sabin","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$efe_triple_viral,"metarrhh_s"=>$vacuna_triple_viral,"tamaño"=>"small","nombre"=>"_vacuna_triple_viral","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			</tr>
			
			<tr>
				<?
				$ref_vacunas = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"gripe","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_vacunas="window.open('$ref_vacunas' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_vacunas?>" <?=atrib_tr7()?>>
				<?$porcentaje=($efe_gripe/$vacuna_gripe)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Vacunas Anti-Gripales: <b><?=($efe_gripe)?$efe_gripe:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$vacuna_gripe?> / </b></font>--> <font size=2 color= red> <b>Meta Anual: <?=$vacuna_gripe?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
				</td> 
				
				<?$ref_vacunas = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"hep_a","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_vacunas="window.open('$ref_vacunas' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_vacunas?>" <?=atrib_tr7()?>>
				<?$porcentaje=($efe_hep_a/$vacuna_hep_a)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Vacunas Hepatitis A: <b><?=($efe_hep_a)?$efe_hep_a:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$vacuna_hep_a?> / </b></font> --><font size=2 color= red> <b>Meta Anual: <?=$vacuna_hep_a?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
				</td>            
            </tr>
			<tr>
			<td align="center" border=1 bordercolor=#2C1701>
			<?
			$link_s=encode_link("metas_grafico.php",array("dato"=>$efe_gripe,"meta"=>$vacuna_gripe,"metarrhh"=>$vacuna_griperrhh,"metarrhh_s"=>$vacuna_gripe,"tamaño"=>"small","nombre"=>"_vacuna_gripe","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$efe_hep_a,"meta"=>$vacuna_hep_a,"metarrhh"=>$vacuna_hep_arrhh,"metarrhh_s"=>$vacuna_hep_a,"tamaño"=>"small","nombre"=>"_vacuna_hep_a","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			</tr>
			
			<tr>
				<?$ref_vacunas = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"trip_celular","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_vacunas="window.open('$ref_vacunas' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_vacunas?>" <?=atrib_tr7()?>>
				<?$porcentaje=($efe_triple_bacteriana_celular/$vacuna_triple_bacteriana_celular)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Vacunas Tri.Bac.Celular: <b><?=($efe_triple_bacteriana_celular)?$efe_triple_bacteriana_celular:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$vacuna_triple_bacteriana_celular?> / </b></font>--> <font size=2 color= red> <b>Meta Anual: <?=$vacuna_triple_bacteriana_celular?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
				</td> 
				
				<?$ref_vacunas = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"trip_acelular","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_vacunas="window.open('$ref_vacunas' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_vacunas?>" <?=atrib_tr7()?>>
				<?$porcentaje=($efe_triple_bacteriana_acelular/$vacuna_triple_bacteriana_acelular)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Vacunas Tri.Bac.Acelular: <b><?=($efe_triple_bacteriana_acelular)?$efe_triple_bacteriana_acelular:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$vacuna_triple_bacteriana_acelular?> / </b></font>--> <font size=2 color= red> <b>Meta Anual: <?=$vacuna_triple_bacteriana_acelular?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
				</td>            
            </tr>
			<tr>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$efe_triple_bacteriana_celular,"metarrhh_s"=>$vacuna_triple_bacteriana_celular,"tamaño"=>"small","nombre"=>"_vacuna_triple_bacteriana_celular","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$efe_triple_bacteriana_acelular,"metarrhh_s"=>$vacuna_triple_bacteriana_acelular,"tamaño"=>"small","nombre"=>"_vacuna_triple_bacteriana_acelular","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			</tr>
			
			<tr>
				<?$ref_vacunas = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"doble_bacteriana","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_vacunas="window.open('$ref_vacunas' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_vacunas?>" <?=atrib_tr7()?>>
				<?$porcentaje=($efe_doble_bacteriana/$vacuna_doble_bacteriana)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Vacunas Doble Bacteriana: <b><?=($efe_doble_bacteriana)?$efe_doble_bacteriana:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$vacuna_doble_bacteriana?> / </b></font>--> <font size=2 color= red> <b>Meta Anual: <?=$vacuna_doble_bacteriana?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
				</td> 
				
				<?$ref_vacunas = encode_link("datos_detalle_cumplimiento.php",array("cuie"=>$cuie,"solicita_datos"=>"vph","fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
				$onclick_vacunas="window.open('$ref_vacunas' , '_blank');";?>
				<td align="center" border=1 bordercolor=#2C1701 onclick="<?=$onclick_vacunas?>" <?=atrib_tr7()?>>
				<?$porcentaje=($efe_hpv/$vacuna_vph)*100;
				$porcentaje=number_format ($porcentaje,2,',','.')?>
				Total de Vacunas VPH: <b><?=($efe_hpv)?$efe_hpv:0?> / </b> <!--<font size=2 color= red> <b>Meta Anual: <?=$vacuna_vph?> / </b></font> --><font size=2 color= red> <b>Meta Anual: <?=$vacuna_vph?> </b></font>
				<font size=2 color=green><b>(<?=$porcentaje?> %) </b></font>
				</td>            
            </tr>
			<tr>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$efe_doble_bacteriana,"metarrhh_s"=>$vacuna_doble_bacteriana,"tamaño"=>"small","nombre"=>"_vacuna_doble_bacteriana","cuie"=>$cuie));
			echo "<a href='$link_l' target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>
			</td>
			<td align="center" border=1 bordercolor=#2C1701>
			<? $link_s=encode_link("metas_grafico.php",array("dato"=>$efe_hpv,"metarrhh_s"=>$vacuna_vph,"tamaño"=>"small","nombre"=>"_vacuna_vph","cuie"=>$cuie));
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
 
 <?=fin_pagina();// aca termino ?>
