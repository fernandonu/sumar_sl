<?php
require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

/*if ($_POST['generar_excel']=="Generar Excel"){
	$periodo=$_POST['periodo'];
	$link=encode_link("fichero_ind_excel.php",array("fecha_desde"=>$_POST['fecha_desde'],"fecha_hasta"=>$_POST['fecha_hasta'],"cuie"=>$_POST['cuie']));
	?>
	<script>
	window.open('<?=$link?>')
	</script>	
<?}*/

if ($_POST['muestra']=="Muestra"){
	$cuie=$_POST['cuie'];
	$fecha_desde=Fecha_db($_POST['fecha_desde']);
	$fecha_hasta=Fecha_db($_POST['fecha_hasta']);
		if($cuie!='Todos'){
			$sql_tmp="SELECT seguimiento_remediar.*, 
					nacer.smiafiliados.afiapellido, 
					nacer.smiafiliados.afinombre, 
					nacer.smiafiliados.afidni,
					nacer.smiafiliados.afisexo,
					nacer.smiafiliados.afifechanac,
					trazadoras.seguimiento_consejeria.*,
					trazadoras.seguimiento_inmunizacion.*,
					trazadoras.seguimiento_interconsulta.*,
					trazadoras.seguimiento_tratamiento.* 
						FROM trazadoras.seguimiento_remediar
						INNER JOIN nacer.smiafiliados ON nacer.smiafiliados.clavebeneficiario = trazadoras.seguimiento_remediar.clave_beneficiario
						LEFT JOIN trazadoras.seguimiento_consejeria on seguimiento_consejeria.id_seguimiento=seguimiento_remediar.id_seguimiento_remediar
						LEFT JOIN trazadoras.seguimiento_inmunizacion on seguimiento_inmunizacion.id_seguimiento=seguimiento_remediar.id_seguimiento_remediar
						LEFT JOIN trazadoras.seguimiento_interconsulta on seguimiento_interconsulta.id_seguimiento=seguimiento_remediar.id_seguimiento_remediar
						LEFT JOIN trazadoras.seguimiento_tratamiento on seguimiento_tratamiento.id_seguimiento=seguimiento_remediar.id_seguimiento_remediar
						where (fecha_comprobante BETWEEN '$fecha_desde' and '$fecha_hasta') and (efector='$cuie')";
}else {
				$sql_tmp="SELECT 
					seguimiento_remediar.*, 
					nacer.smiafiliados.afiapellido, 
					nacer.smiafiliados.afinombre, 
					nacer.smiafiliados.afidni,
					nacer.smiafiliados.afisexo,
					nacer.smiafiliados.afifechanac,
					trazadoras.seguimiento_consejeria.*,
					trazadoras.seguimiento_inmunizacion.*,
					trazadoras.seguimiento_interconsulta.*,
					trazadoras.seguimiento_tratamiento.*
						FROM trazadoras.seguimiento_remediar
						INNER JOIN nacer.smiafiliados ON nacer.smiafiliados.clavebeneficiario = trazadoras.seguimiento_remediar.clave_beneficiario
						LEFT JOIN trazadoras.seguimiento_consejeria on seguimiento_consejeria.id_seguimiento=seguimiento_remediar.id_seguimiento_remediar
						LEFT JOIN trazadoras.seguimiento_inmunizacion on seguimiento_inmunizacion.id_seguimiento=seguimiento_remediar.id_seguimiento_remediar
						LEFT JOIN trazadoras.seguimiento_interconsulta on seguimiento_interconsulta.id_seguimiento=seguimiento_remediar.id_seguimiento_remediar
						LEFT JOIN trazadoras.seguimiento_tratamiento on seguimiento_tratamiento.id_seguimiento=seguimiento_remediar.id_seguimiento_remediar
						where (fecha_comprobante BETWEEN '$fecha_desde' and '$fecha_hasta')";

			}
			
			$res_comprobante=sql($sql_tmp,"<br>Error al traer los datos<br>") or fin_pagina();
}

echo $html_header;?>

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
return true;
}
</script>
<form name=form1 action="report_seguimiento_crono.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center >
     <tr>
      <td align=center>
		<b>
	    	  <?if ($fecha_desde=='')$fecha_desde=date("Y-m-d",time()-(30*24*60*60));
			  if ($fecha_hasta=='')$fecha_hasta=date("Y-m-d");?>
			  Desde: <input type=text id=fecha_desde name=fecha_desde value='<?=fecha($fecha_desde)?>' size=15 readonly>
				<?=link_calendario("fecha_desde");?>
		
			  Hasta: <input type=text id=fecha_hasta name=fecha_hasta value='<?=fecha($fecha_hasta)?>' size=15 readonly>
				<?=link_calendario("fecha_hasta");?> 
				
			  Efector: 
			 <select name=cuie Style="width=257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();">
			 <?$user_login1=substr($_ses_user['login'],0,6);
								  if (es_cuie($_ses_user['login'])){
									$sql1= "select cuie, nombre, com_gestion from nacer.efe_conv where cuie='$user_login1' order by nombre";
								   }									
								  else{
									$usuario1=$_ses_user['id'];
									$sql1= "select nacer.efe_conv.nombre, nacer.efe_conv.cuie, com_gestion 
											from nacer.efe_conv 
											join sistema.usu_efec on (nacer.efe_conv.cuie = sistema.usu_efec.cuie) 
											join sistema.usuarios on (sistema.usu_efec.id_usuario = sistema.usuarios.id_usuario) 
											where sistema.usuarios.id_usuario = '$usuario1'
										 order by nombre";
								   }			 			   
								 $res_efectores=sql($sql1) or fin_pagina();
							 
							 while (!$res_efectores->EOF){ 
								$com_gestion=$res_efectores->fields['com_gestion'];
								$cuie1=$res_efectores->fields['cuie'];
								$nombre_efector=$res_efectores->fields['nombre'];
								if($com_gestion=='FALSO')$color_style='#F78181'; else $color_style='';
								?>
								<option value='<?=$cuie1;?>' Style="background-color: <?=$color_style;?>" <?if ($cuie1==$cuie)echo "selected"?>><?=$cuie1." - ".$nombre_efector?></option>
								<?
								$res_efectores->movenext();
								}?>
			  <option value='Todos' <?if ("Todos"==$cuie)echo "selected"?>>Todos</option>
			</select>
			
			<input type="submit" name="muestra" value='Muestra' onclick="return control_muestra()" >
			<!--<input type="submit" value="Generar Excel" name="generar_excel">-->
    	  </b>     
	  </td>
     </tr>
</table>

<?if ($_POST['muestra']){?>
<table border=0 width=90% cellspacing=2 cellpadding=2 class="table table-striped" align=center>
  <tr>
  	<td colspan=10 align=left>
     <table width=90%>
      <tr>
       <td width=30% align=left><b>Total:</b> <?=$res_comprobante->recordCount()?> </td>       
      </tr>
    </table>
   </td>
  </tr>
      	   	<!--DATOS AFILIATORIOS-->
      	   	<th >Efec</th>	 		
	 		<th >DNI</th>
	 		<th >Apellido</th>
	 		<th >Nombre</th>
	 		<th >Fecha Nac.</th>
	 		<th >Sexo</th>
			<!--DIAGNOSTICOS-->
	 		<th >Fecha</th>
	 		<th >Fecha Prox</th>
	 		<th >dtm2</th>
	 		<th >hta</th>
	 		<th >ta_sist</th>
	 		<th >ta_diast</th>
	 		<th >tabaquismo</th>
	 		<th >Sedentarismo</th>
	 		<th >peso</th>
	 		<th >talla</th>
		    <th >imc </th>
		    <th>Perim.Abdom.</th>
		    <th >examendepie </th>
		    <!--inmunizacion-->
			<th>Antigripal</th>
			<th>Neumococo 13</th>
			<th>Neumococo 23</th>
			<th>Doble Adulto</th>
			<th>Hepat.B</th>
			<!--diabetes /metab-->
	 		<th >gluc</th>
	 		<th >hba1c </th>
	 		<!--perfil lipido-->
	 		<th >col_tot</th>
	 		<th >hdl </th>
		    <th >ldl </th>
		    <th >tags </th>
		    <!--funcion renal-->
		    <th >creatininemia </th>
		    <th>IFG</th>
		    <th>Indice.AC</th>
            <th>Indice.PC</th>
            <!--orina aislada-->
            <th>Album.Orina.Aisl</th>	
            <th>Protein.Orina.Aisl</th>
            <!--orina de 24hs-->
			<th>Album.Orina.24hs</th>	
            <th>Protein.Orina.24hs</th>
            <!--estudios-->
            <th >fondodeojo </th>
            <th >ecg </th>
            <th>Ecocardiograma</th>
            <!--Consejeria-->
			<th>Alimnt.Salud.</th>
			<th>Activ.Fisica</th>
			<th>Rast.Tabaq.</th>
			<!--Interconsultas-->
			<th>Oftalmologia</th>
			<th>Cardiologia</th>
			<th>Nefrologia</th>
			<th>Laboratorio</th>
			<th>Fonoaudiologia</th>
			<th>Psicologia</th>
			<th>Kinesiologia</th>
			<th>Activ.Fis.Adapt</th>
			<th>Nutricion</th>
			<th>Odontologia</th>
			<th>Psiquiatria</th>
			<th>Farmacia</th>
			<!--Tratamiento-->
			<th>Ieca.Ara</th>
			<th>Estatina</th>
			<th>AAS</th>
			<th>Beta_Bloqueantes</th>
			<th>Hipoglucemiante_Oral</th>
			<th>Insulina</th>
			<th>Metformina</th>
			<th>Enalapril</th>
			<th>Losartan</th>
			<th>Amlodipina</th>
			<th>Atenolol </th>
			<th>Hidroclorotiazida</th>
			<!--estimar riesgo-->
			<th >riesgo_global </th>
            <th >riesgo_globala </th>
            <!--quien registra-->
            <th >fecha_carga </th>
            <th >usuario</th>
            <th >Comentario</th>               
  </tr>
 <?while (!$res_comprobante->EOF) {?>  	
      <tr>     
		 		<!--datos afiliatorios-->
		 		<td><?=$res_comprobante->fields['efector']?></td>	
		 		<td><?=$res_comprobante->fields['afidni']?></td>	
		 		<td><?=$res_comprobante->fields['afiapellido']?></td>	
		 		<td><?=$res_comprobante->fields['afinombre']?></td>	
		 		<td><?=$res_comprobante->fields['afifechanac']?></td>
		 		<td><?=$res_comprobante->fields['afisexo']?></td>
		 		<!--diagnostico-->
		 		<td><?=fecha($res_comprobante->fields['fecha_comprobante'])?></td>		 		
		 		<td><?=fecha($res_comprobante->fields['fecha_comprobante_proximo'])?></td>		 	
		 		<td><?=$res_comprobante->fields['dtm2']?></td>		 		
		 		<td><?=$res_comprobante->fields['hta']?></td>		 		
		 		<td><?=$res_comprobante->fields['ta_sist']?></td>		 		
		 		<td><?=$res_comprobante->fields['ta_diast']?></td>		 		
		 		<td><?=$res_comprobante->fields['tabaquismo']?></td>
		 		<td><?=$res_comprobante->fields['sedentarismo']?></td>
		 		<td><?=$res_comprobante->fields['peso']?></td>		 	 		
		 		<td><?=$res_comprobante->fields['talla']?></td>		 	 		
		 		<td><?=$res_comprobante->fields['imc']?></td>	
		 		<td><?=$res_comprobante->fields['p_abd']?></td>
		 		<td><?=$res_comprobante->fields['examendepie']?></td>	
				<!--inmunizaciones-->
		 		<td><?=$res_comprobante->fields['antigripal']?></td>
				<td><?=$res_comprobante->fields['neumococo13']?></td>
				<td><?=$res_comprobante->fields['neumococo23']?></td>
				<td><?=$res_comprobante->fields['doble_adulto']?></td>
				<td><?=$res_comprobante->fields['hepatitis_b']?></td>
		 		<!--diabetes /metab-->
		 		<td><?=$res_comprobante->fields['gluc']?></td>
		 		<td><?=$res_comprobante->fields['hba1c']?></td>
		 		<!--perfil lipido-->
		 		<td><?=$res_comprobante->fields['col_tot']?></td>
		 		<td><?=$res_comprobante->fields['hdl']?></td>	
		 		<td><?=$res_comprobante->fields['ldl']?></td>
		 		<td><?=$res_comprobante->fields['tags']?></td>	 		
		 		<!--funcion renal-->	
		 		<td><?=$res_comprobante->fields['creatininemia']?></td>	
		 		<td><?=$res_comprobante->fields['ifg']?></td> 
		 		<td><?=$res_comprobante->fields['indice_ac']?></td>
				<td><?=$res_comprobante->fields['indice_pc']?></td>
				<!--orina aislada-->
				<td><?=$res_comprobante->fields['albuminuria_orina_aislada']?></td>
				<td><?=$res_comprobante->fields['proteinuria_orina_aislada']?></td>	 		
		 		<!--orina de 24hs-->
		 		<td><?=$res_comprobante->fields['albuminuria_orina_24h']?></td>
				<td><?=$res_comprobante->fields['proteinuria_orina_24h']?></td>
				<!--estudios-->	
				<td><?=$res_comprobante->fields['fondodeojo']?></td>	 	 		
		 		<td><?=$res_comprobante->fields['ecg']?></td>	
		 		<td><?=$res_comprobante->fields['ecocardiograma']?></td>
		 		<!--Consejeria-->
		 		<td><?=$res_comprobante->fields['alimentacion_saludable']?></td>
				<td><?=$res_comprobante->fields['actividad_fisica']?></td>
				<td><?=$res_comprobante->fields['rastreo_tabaquismo']?></td>	
				<!--Interconsultas-->
				<td><?=$res_comprobante->fields['oftalmologia']?></td>
				<td><?=$res_comprobante->fields['cardiologia']?></td>
				<td><?=$res_comprobante->fields['nefrologia']?></td>
				<td><?=$res_comprobante->fields['laboratorio']?></td>
				<td><?=$res_comprobante->fields['fonoaudiologia']?></td>
				<td><?=$res_comprobante->fields['psicologia']?></td>
				<td><?=$res_comprobante->fields['kinesiologia']?></td>
				<td><?=$res_comprobante->fields['activ_fis_adapt']?></td>
				<td><?=$res_comprobante->fields['nutricion']?></td>
				<td><?=$res_comprobante->fields['odontologia']?></td>
				<td><?=$res_comprobante->fields['psiquiatria']?></td>
				<td><?=$res_comprobante->fields['farmacia']?></td> 	
				<!--Tratamiento-->
				<td><?=$res_comprobante->fields['ieca_ara']?></td>
				<td><?=$res_comprobante->fields['estatina']?></td>
				<td><?=$res_comprobante->fields['aas']?></td>
				<td><?=$res_comprobante->fields['beta_bloqueantes']?></td>
				<td><?=$res_comprobante->fields['hipoglusemiante_oral']?></td>
				<td><?=$res_comprobante->fields['insulina']?></td>
				<td><?=$res_comprobante->fields['metformina']?></td>
				<td><?=$res_comprobante->fields['enalapril']?></td>
				<td><?=$res_comprobante->fields['losartan']?></td>
				<td><?=$res_comprobante->fields['amlodipina']?></td>
				<td><?=$res_comprobante->fields['atenolol']?></td>
				<td><?=$res_comprobante->fields['hidroclorotiazida']?></td>
				<!--estimar riesgo-->
				<td><?=$res_comprobante->fields['riesgo_global']?></td>	
		 		<td><?=$res_comprobante->fields['riesgo_globala']?></td>
		 		<!--quien registra-->
		 		<td><?=fecha($res_comprobante->fields['fecha_carga'])?></td>		 		
		 		<td><?=$res_comprobante->fields['usuario']?></td>
		 		<td><?=$res_comprobante->fields['comentario']?></td> 
		 		</tr>
	<?$res_comprobante->MoveNext();
   }?>
    
    
</table>
<?}?>
<br>
	
</td>
</table>

</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
