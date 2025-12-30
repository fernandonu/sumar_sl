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
	window.open('<?php echo $link?>')
	</script>	
<?}*/

if ($_POST['muestra']=="Muestra"){
	$cuie=$_POST['cuie'];
	$fecha_desde=Fecha_db($_POST['fecha_desde']);
	$fecha_hasta=Fecha_db($_POST['fecha_hasta']);
		if($cuie!='Todos'){
			$sql_tmp="SELECT
						nacer.efe_conv.cuie,
						nacer.efe_conv.nombre as nombre_efector,
						trazadoras.clasificacion_remediar2.*,
						trazadoras.clasificacion_remediar2.nombre AS nom_per,
						uad.beneficiarios.sexo
						FROM
						trazadoras.clasificacion_remediar2
						INNER JOIN uad.beneficiarios using (clave_beneficiario)
            INNER JOIN nacer.efe_conv ON trazadoras.clasificacion_remediar2.cuie = nacer.efe_conv.cuie
						where (fecha_control BETWEEN '$fecha_desde' and '$fecha_hasta') and (efe_conv.cuie='$cuie')";
}else {
			$sql_tmp="SELECT 
						nacer.efe_conv.cuie,
						nacer.efe_conv.nombre as nombre_efector,
						trazadoras.clasificacion_remediar2.*,
						trazadoras.clasificacion_remediar2.nombre AS nom_per,
						uad.beneficiarios.sexo
						FROM
						trazadoras.clasificacion_remediar2
            INNER JOIN uad.beneficiarios using (clave_beneficiario)
						INNER JOIN nacer.efe_conv ON trazadoras.clasificacion_remediar2.cuie = nacer.efe_conv.cuie
						where (fecha_control BETWEEN '$fecha_desde' and '$fecha_hasta')";
							
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
<form name=form1 action="report_clasificacion_crono.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<b>
	    	  <?if ($fecha_desde=='')$fecha_desde=date("Y-m-d",time()-(30*24*60*60));
			  if ($fecha_hasta=='')$fecha_hasta=date("Y-m-d");?>
			  Desde: <input type=text id=fecha_desde name=fecha_desde value='<?php echo fecha($fecha_desde)?>' size=15 readonly>
				<?php echo link_calendario("fecha_desde");?>
		
			  Hasta: <input type=text id=fecha_hasta name=fecha_hasta value='<?php echo fecha($fecha_hasta)?>' size=15 readonly>
				<?php echo link_calendario("fecha_hasta");?> 
				
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
								<option value='<?php echo $cuie1;?>' Style="background-color: <?php echo $color_style;?>" <?if ($cuie1==$cuie)echo "selected"?>><?php echo $cuie1." - ".$nombre_efector?></option>
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
<table border=0 width=90%  class="table table-striped" cellspacing=2 cellpadding=2 bgcolor='<?php echo $bgcolor3?>' align=center>
  <tr>
  	<td colspan=38 align=left>
     <table width=90%>
      <tr>
       <td width=30% align=left><b>Total:</b> <?php echo $res_comprobante->recordCount()?> </td>       
      </tr>
    </table>
   </td>
  </tr>
    	<td>Efec</td>
    	<td>Nombre Efector</td>
 		<td>Nro. Doc</td>
 		<td>Apellido</td>
 		<td>Nombre</td>
        <td>Fecha Nac.</td>
        <td>Sexo</td>
 		<td>Fecha Clasificacion</td>
 		<td>Fecha Seguimiento</td>
		<td>Peso</td>
		<td>Talla</td>
		<td>IMC</td>
		<td>P.Abd.</td>
 		<td>dmt</td>
 		<td>ta</td>
 		<td>colest_total</td>
 		<td>acv</td>	 			 		
 		<td>vas_per</td>
 		<td>car_isq</td>
	  	<td>col310</td>
	  	<td>col_ldl</td>
		<td>ct_hdl </td>
		<td>pres_art</td>
		<td>dmt2</td>
		<td>insu_renal </td>
		<td>dmt_menor </td>
		<td>hta_menor </td>
		<td>menopausia </td>
		<td>antihiper </td>
		<td>obesi </td>
		<td>acv_prema </td>
		<td>trigli </td>
		<td>hdl_col </td>
		<td>hiperglu </td>
		<td>microalbu </td>
		<td>tabaquismo </td>
		<td>hta </td>
		<td>rcvg </td>
		<td>diabetico</td>,
		<td>hipertenso</td>
		<td>clasif_rapida </td>
		<td>bajo_prog</td>
		<td>sedentarismo</td>

  </tr>
 <?while (!$res_comprobante->EOF) {?>  	
    <tr >     
	<td><?php echo $res_comprobante->fields['cuie']?></td>	
	<td><?php echo $res_comprobante->fields['nombre_efector']?></td>
	<td><?php echo $res_comprobante->fields['num_doc']?></td>		 				 	 		
	<td><?php echo $res_comprobante->fields['apellido']?></td>
    <td><?php echo $res_comprobante->fields['nom_per']?></td>	
    <td><?php echo $res_comprobante->fields['fecha_nac']?></td>
    <td><?php echo $res_comprobante->fields['sexo']?></td>	 		
	<td><?php echo fecha($res_comprobante->fields['fecha_control'])?></td>		 		
	<td><?php echo fecha($res_comprobante->fields['fecha_prox_seguimiento'])?></td>
	<td><?php echo $res_comprobante->fields['peso']?></td>
	<td><?php echo $res_comprobante->fields['talla']?></td>
	<td><?php echo $res_comprobante->fields['imc']?></td>
	<td><?php echo $res_comprobante->fields['p_abd']?></td>
	<td><?php echo $res_comprobante->fields['dmt']?></td>
	<td><?echo $res_comprobante->fields['ta_sist'].'/'.$res_comprobante->fields['ta_diast']?></td>
	<td><?php echo $res_comprobante->fields['col_tot']?></td>
	<td><?php echo ($res_comprobante->fields['acv']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['vas_per']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['car_isq']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['col310']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['col_ldl']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['ct_hdl']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['pres_art']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['dmt2']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['insu_renal']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['dmt_menor']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['hta_menor']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['menopausia']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['antihiper']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['obesi']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['acv_prema']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['trigli']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['hdl_col']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['hiperglu']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['microalbu']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['tabaquismo']=='1')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['hta']=='1')?'SI':'NO'?></td>
	<td><?php echo $res_comprobante->fields['rcvg']?></td>
	<td><?php echo $res_comprobante->fields['diabetico']?></td>
	<td><?php echo $res_comprobante->fields['hipertenso']?></td>
	<td><?php echo ($res_comprobante->fields['clasif_rapida']=='s')?'SI':'NO'?></td>
	<td><?php echo ($res_comprobante->fields['bajo_prog']=='1')?'SI':'NO'?></td>
	<td><?php echo $res_comprobante->fields['sedentarismo']?></td>

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
<?php echo fin_pagina();// aca termino ?>
