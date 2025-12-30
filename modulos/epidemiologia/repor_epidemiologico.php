<?php

require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

$cuie=$_POST['cuie'];
$fecha_desde=Fecha_db($_POST['fecha_desde']);
$fecha_hasta=Fecha_db($_POST['fecha_hasta']);

if ($_POST['muestra']=="Muestra"){
	
		if($cuie=='Todos'){
			$sql_tmp="SELECT fichero.fecha_control,
              fichero.cuie,
              efe_conv.nombre as efector,
              beneficiarios.nombre,
              beneficiarios.apellido,
              beneficiarios.documento,
              beneficiarios.fecha_nac,
              extract (year from age(fichero.fecha_control,beneficiarios.fecha_nac)) as edad_anio,
              extract (month from age(fichero.fecha_control,beneficiarios.fecha_nac)) as edad_meses,
              beneficiarios.sexo,
              beneficiarios.domicilio,
              fichero.enfer_epidemeologica
              --esquema vacuna  

              from fichero.fichero 
              inner join nacer.efe_conv using (cuie)
              inner join leche.beneficiarios using (id_beneficiarios)

              where id_beneficiarios<>0 and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and fichero.enfer_epidemeologica is not null

              union

              SELECT fichero.fecha_control,
              fichero.cuie,
              efe_conv.nombre as efector,
              smiafiliados.afinombre,
              smiafiliados.afiapellido,
              smiafiliados.afidni,
              smiafiliados.afifechanac,
              extract (year from age(fichero.fecha_control,smiafiliados.afifechanac)) as edad_anio,
              extract (month from age(fichero.fecha_control,smiafiliados.afifechanac)) as edad_meses,
              smiafiliados.afisexo,
              smiafiliados.".'"afiDomCalle"'."||'N°'||smiafiliados.".'"afiDomNro"'." as direccion,
              fichero.enfer_epidemeologica
              --esquema vacuna  

              from fichero.fichero 
              inner join nacer.efe_conv using (cuie)
              inner join nacer.smiafiliados using (id_smiafiliados)

              where id_smiafiliados<>0 and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and fichero.enfer_epidemeologica is not null
              order by 1";
} else {
			$sql_tmp="SELECT fichero.fecha_control,
              fichero.cuie,
              efe_conv.nombre as efector,
              beneficiarios.nombre,
              beneficiarios.apellido,
              beneficiarios.documento,
              beneficiarios.fecha_nac,
              extract (year from age(fichero.fecha_control,beneficiarios.fecha_nac)) as edad_anio,
              extract (month from age(fichero.fecha_control,beneficiarios.fecha_nac)) as edad_meses,
              beneficiarios.sexo,
              beneficiarios.domicilio,
              fichero.enfer_epidemeologica
              --esquema vacuna  

              from fichero.fichero 
              inner join nacer.efe_conv using (cuie)
              inner join leche.beneficiarios using (id_beneficiarios)

              where id_beneficiarios<>0 and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and fichero.enfer_epidemeologica is not null and fichero.cuie='$cuie'

              union

              SELECT fichero.fecha_control,
              fichero.cuie,
              efe_conv.nombre as efector,
              smiafiliados.afinombre,
              smiafiliados.afiapellido,
              smiafiliados.afidni,
              smiafiliados.afifechanac,
              extract (year from age(fichero.fecha_control,smiafiliados.afifechanac)) as edad_anio,
              extract (month from age(fichero.fecha_control,smiafiliados.afifechanac)) as edad_meses,
              smiafiliados.afisexo,
              smiafiliados.".'"afiDomCalle"'."||'N°'||smiafiliados.".'"afiDomNro"'." as direccion,
              fichero.enfer_epidemeologica
              --esquema vacuna  

              from fichero.fichero 
              inner join nacer.efe_conv using (cuie)
              inner join nacer.smiafiliados using (id_smiafiliados)

              where id_smiafiliados<>0 and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and fichero.enfer_epidemeologica is not null and fichero.cuie='$cuie'
              order by 1";
							
			};
			
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

<form name=form1 action="repor_epidemiologico.php" method=POST>
<br>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align="center">
		<b>
	 <?if ($fecha_desde=='') $fecha_desde=date("Y-m-d");
	if ($fecha_hasta=='')$fecha_hasta=date("Y-m-d");?>
	Desde: <input type=text id="fecha_desde" name="fecha_desde" value='<?=fecha($fecha_desde)?>' size=15 readonly>
	<?=link_calendario("fecha_desde");?>
	
	Hasta: <input type=text id="fecha_hasta" name="fecha_hasta" value='<?=fecha($fecha_hasta)?>' size=15 readonly>
	<?=link_calendario("fecha_hasta");?> 
				
	Efector: 
	 <select name="cuie" Style="width=257px">
			 <?$user_login1=substr($_ses_user['login'],0,6);
								  if (es_cuie($_ses_user['login'])){
									$sql1="SELECT cuie, nombre, com_gestion from nacer.efe_conv where cuie='$user_login1' order by nombre";
								   }									
								  else{
									$usuario1=$_ses_user['id'];
									$sql1="SELECT nacer.efe_conv.nombre, nacer.efe_conv.cuie, com_gestion 
											from nacer.efe_conv 
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
			&nbsp;&nbsp;
      <? $link=encode_link("excel_epi.php",array("fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta,"cuie"=>$cuie));?>
      <img src="../../imagenes/excel.gif" style='cursor:hand;'  onclick="window.open('<?=$link?>')">
    	</td>
     </tr>
</table>

<?if ($_POST['muestra']){?>
<br>
<br>
<table border=1 width=90% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
  <tr>
  	<td colspan=12 align=left id="ma">
     <table width=90%>
      <tr id="ma">
       <td width=30% align="left"><b>Total:</b> <?=$res_comprobante->recordCount()?> </td>       
      </tr>
    </table>
   </td>
  </tr>
    	<td >Efec</td>
    	<td >Nombre Efector</td>
	 		<td >Fecha Control</td>
      <td >Apellido</td>
      <td >Nombre</td>
      <td >Nro. Doc</td>
      <td >Fecha Nac.</td>
      <td >Edad Años</td>
      <td >Edad Meses</td>
      <td >Sexo</td>
      <td >Direccion</td>
	 		<td >Patologia</td>	 			 		

  </tr>
 <?while (!$res_comprobante->EOF) {?>  	
      <tr <?=atrib_tr()?> >     
		 		<td><?=$res_comprobante->fields['cuie']?></td>	
		 		<td><?=$res_comprobante->fields['efector']?></td>
        <td><?=fecha($res_comprobante->fields['fecha_control'])?></td>
		 		<td><?=$res_comprobante->fields['apellido']?></td>		 		
		 		<td><?=$res_comprobante->fields['nombre']?></td>
        <td><?=$res_comprobante->fields['documento']?></td>
        <td><?=fecha($res_comprobante->fields['fecha_nac'])?></td>
        <td><?=$res_comprobante->fields['edad_anio']?></td>
        <td><?=$res_comprobante->fields['edad_meses']?></td>
        <td><?=trim($res_comprobante->fields['sexo'])?></td>
        <td><?=$res_comprobante->fields['domicilio']?></td>
        <td><?=$res_comprobante->fields['enfer_epidemeologica']?></td>			 				 		

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
