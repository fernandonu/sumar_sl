<?php

require_once("../../config.php");
require_once("funciones_seguimientos.php");

variables_form_busqueda("seguimiento_global");
cargar_calendario();

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);
  	 	
echo $html_header;
?>
<form name=form1 action="seguimiento_global.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<b>	
			
		Desde: <input type=text id=fecha_desde name=fecha_desde value='<?=$fecha_desde?>' size=15 readonly>
		<?=link_calendario("fecha_desde");?>
		
		Hasta: <input type=text id=fecha_hasta name=fecha_hasta value='<?=$fecha_hasta?>' size=15 readonly>
		<?=link_calendario("fecha_hasta");?> 
		
		   
	    
	    &nbsp;&nbsp;&nbsp;
		<input type="submit" name="muestra" value='Muestra' >
	    </b>
	    </td>
       
     </tr>
</table>

<table border=1 width=150% cellspacing=5 bgcolor='<?=$bgcolor3?>' align=center>
  <tr>
  	<td colspan=47 align=left id="ma">
     <table width=150%>
      <tr id="ma">
       <!-- <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td> -->
      </tr>
    </table>
   </td>
  </tr>
  

  <tr>
    <td align=right id="mo"><a id="mo">CUIE</a></td>      	
    <td align=right id="mo"><a id="mo">Nombre</a></td>
    <td align=right id="mo"><a id="mo">Cuidad</a></td>        
    <td align=right colspan=2 id="mo"><a id="mo">Total de Controles de Ni&ntilde;os menor de 1 a&ntilde;o </a></td>
    <td align=right colspan=2 id="mo"><a id="mo">Total de Controles de Ni&ntilde;os de 1 a 9 a&ntilde;os</a></td>
	<td align=right colspan=2 id="mo"><a id="mo">Total de Inscriptos que Marca Cuidado Sexual y Reproductivo </a></td>	
	<td align=right colspan=2 id="mo"><a id="mo">Total de Embarazadas antes de las 12 semanas </a></td>	
    <td align=right colspan=2 id="mo"><a id="mo">Total de Controles de Embarazo segun periodo </a></td>
    <td align=right colspan=2 id="mo"><a id="mo">Total de Adolescentes de 10 a 19 a&ntilde;os </a></td>
	<td align=right colspan=2 id="mo"><a id="mo">Total de Controles que Marca Hipertenso</a></td>       
    <td align=right colspan=2 id="mo"><a id="mo">Total de Controles que Marca Diabetico</a></td>
    <td align=right colspan=2 id="mo"><a id="mo">Notificacion de Ebarazadas con F.de Riesgo</a></td>
    <td align=right colspan=2 id="mo"><a id="mo">Talleres</a></td>
    <td align=right colspan=2 id="mo"><a id="mo">Total de Vacunas Pentavalentes</a></td>  
    <td align=right colspan=2 id="mo"><a id="mo">Total de Vacunas Doble Viral</a></td>
	<td align=right colspan=2 id="mo"><a id="mo">Total de Vacunas Hepatitis B</a></td>        
    <td align=right colspan=2 id="mo"><a id="mo">Total de Vacunas Neumococo</a></td>
    <td align=right colspan=2 id="mo"><a id="mo">Total de Vacunas Sabin </a></td>
	<td align=right colspan=2 id="mo"><a id="mo">Total de Vacunas Triple Viral</a></td>  
    <td align=right colspan=2 id="mo"><a id="mo">Total de Vacunas Anti-Gripales</a></td>        
    <td align=right colspan=2 id="mo"><a id="mo">Total de Vacunas Hepatitis A</a></td>
    <td align=right colspan=2 id="mo"><a id="mo">Total de Vacunas Tri.Bac.Celular</a></td>
	<td align=right colspan=2 id="mo"><a id="mo">Total de Vacunas Tri.Bac.Acelular</a></td>        
    <td align=right colspan=2 id="mo"><a id="mo">Total de Vacunas Doble Bacteriana</a></td>
    <td align=right colspan=2 id="mo"><a id="mo">Total de Vacunas VPH</a></td>  
    </tr>
	
	<tr>
    <td></td>      	
    <td></td>
    <td></td>        
    <td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td>
	<td align=right id="mo"><a id="mo">Dato</a></td>
	<td align=right id="mo"><a id="mo">Meta</a></td> 
    </tr>
 <? if ($_POST['muestra']=="Muestra"){
   
   
	$sql="select id_efe_conv,cuie,nombre,cuidad from nacer.efe_conv where conv_sumar='t'";
	$result = sql($sql) or die;
	
	$fecha_desde=fecha_db($_POST['fecha_desde']);
	$fecha_hasta=fecha_db($_POST['fecha_hasta']);
   
	
   
   while (!$result->EOF) {
  	
    $cuie=$result->fields['cuie'];
  
    //consultas para traer los datos de carga
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
$meta_emb_riesgo=$res_query_meta->fields['emb_riesgo'];
$meta_talleres=$res_query_meta->fields['talleres'];


$sql_sitam="select cantidad from nacer.sitam where cuie='$cuie'";
$res_sitam=sql($sql_sitam,"Error al traer los datos del sitam") or fin_pagina();
$paps=$res_sitam->fields['cantidad'];
	
		
	?>
	<tr <?=atrib_tr()?>>        
				<td align=center><?=$result->fields['cuie']?></td>
				<td align=center><?=$result->fields['nombre']?></td>
				<td align=center><?=$result->fields['cuidad']?></td>       
				<td align=center><?=($ninios_new_1)?$ninios_new_1:0?></td>
				<td align=center><?=($cns_menor_1_año)?$cns_menor_1_año:0?></td>
				<td align=center><?=($ninios_1_a_9)?$ninios_1_a_9:0?></td>
				<td align=center><?=($cns_entre_1_y_9)?$cns_entre_1_y_9:0?></td>
				<td align=center><?=($cuidado_sexual)?$cuidado_sexual:0?></td> 
				<td align=center><?=($mujeres_edad_fertil)?$mujeres_edad_fertil:0?></td>
				<td align=center><?=($embarazadas_12_pers)?$embarazadas_12_pers:0?></td>
				<td align=center><?=($captacion_temprana)?$captacion_temprana:0?></td>	
				<td align=center><?=($embarazadas)?$embarazadas:0?></td> 
				<td align=center><?=($promedio_controles_x_emb)?$promedio_controles_x_emb:0?></td>
				<td align=center><?=($adol_new_pers_3)?$adol_new_pers_3:0?></td> 
				<td align=center><?=($adolecentes)?$adolecentes:0?></td>	
				<td align=center><?=($hip)?$hip:0?></td>
				<td align=center><?=($enfermedades_cronicas_HTA)?$enfermedades_cronicas_HTA:0?></td>
				<td align=center><?=($dia)?$dia:0?></td>
				<td align=center><?=($enfermedades_cronicas_DBT)?$enfermedades_cronicas_DBT:0?></td>	
				<td align=center><?=($emb_riesgo)?$emb_riesgo:0?></td>
				<td align=center><?=($meta_emb_riesgo)?$meta_emb_riesgo:0?></td>
				<td align=center><?=($talleres)?$talleres:0?></td>
				<td align=center><?=($meta_talleres)?$meta_talleres:0?></td>
				<td align=center><?=($efe_pentavalente)?$efe_pentavalente:0?></td>
				<td align=center><?=($vacuna_pentavalente)?$vacuna_pentavalente:0?></td>
				<td align=center><?=($efe_doble_viral)?$efe_doble_viral:0?></td>	
				<td align=center><?=($vacuna_doble_viral)?$vacuna_doble_viral:0?></td>
				<td align=center><?=($efe_hep_b)?$efe_hep_b:0?></td> 
				<td align=center><?=($vacuna_hep_b)?$vacuna_hep_b:0?></td>	
				<td align=center><?=($efe_neumococo)?$efe_neumococo:0?></td> 
				<td align=center><?=($vacuna_neumococo)?$vacuna_neumococo:0?></td>
				<td align=center><?=($efe_sabin)?$efe_sabin:0?></td> 
				<td align=center><?=($vacuna_sabin)?$vacuna_sabin:0?></td>	
				<td align=center><?=($efe_triple_viral)?$efe_triple_viral:0?></td> 
				<td align=center><?=($vacuna_triple_viral)?></td>
				<td align=center><?=($efe_gripe)?$efe_gripe:0?></td> 
				<td align=center><?=($vacuna_gripe)?></td>
				<td align=center><?=($efe_hep_a)?$efe_hep_a:0?></td>
				<td align=center><?=($vacuna_hep_a)?$vacuna_hep_a:0?></td>
				<td align=center><?=($efe_triple_bacteriana_celular)?$efe_triple_bacteriana_celular:0?></td> 
				<td align=center><?=($vacuna_triple_bacteriana_celular)?$vacuna_triple_bacteriana_celular:0?></td>
				<td align=center><?=($efe_triple_bacteriana_acelular)?$efe_triple_bacteriana_acelular:0?></td>  
				<td align=center><?=($vacuna_triple_bacteriana_acelular)?$vacuna_triple_bacteriana_acelular:0?></td>
				<td align=center><?=($efe_doble_bacteriana)?$efe_doble_bacteriana:0?></td> 
				<td align=center><?=($vacuna_doble_bacteriana)?$vacuna_doble_bacteriana:0?></td>
				<td align=center><?=($efe_hpv)?$efe_hpv:0?></td> 
				<td align=center><?=($vacuna_vph)?$vacuna_vph:0?></td>
	  </tr>
	<?$result->MoveNext(); 
	}    
  }?>    
    <tr>
  	<td colspan=15 align=left id=ma>
     <table width=100%>
      
    </table>
   </td>
  </tr>
 </table>  
   <tr>
  	<td colspan=15 align=left id=ma>
     <table width=100%>
      	
    </table>
   </td>
  </tr>
  
  <table>
  <tr align="center">
   <td>
     <input type=button name="volver" value="Volver" onclick="document.location='seguimiento.php'"title="Volver al Listado" style="width=150px">     
   </td>
  </tr>
  </table>

</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>