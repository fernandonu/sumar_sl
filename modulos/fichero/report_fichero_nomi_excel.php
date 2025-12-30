<?php
/*
$Author: Seba $
$Revision: 4.0 $
$Date: 2025/10/18 $
*/

require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$fecha_desde=Fecha_db($fecha_desde);
$fecha_hasta=Fecha_db($fecha_hasta);

function edad_con_meses($fecha_de_nacimiento,$fecha_control){ 
	//$fecha_actual = date ("Y-m-d"); 

	// separamos en partes las fechas 
	$array_nacimiento = explode ( "-", $fecha_de_nacimiento ); 
	$array_actual = explode ( "-", $fecha_control ); 

	$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
	$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
	$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días 

	//ajuste de posible negativo en $días 
	if ($dias < 0) 
	{ 
		--$meses; 

		//ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
		switch ($array_actual[1]) { 
			   case 1:     $dias_mes_anterior=31; break; 
			   case 2:     $dias_mes_anterior=31; break; 
			   case 3:  
					if (bisiesto_local($array_actual[0])) 
					{ 
						$dias_mes_anterior=29; break; 
					} else { 
						$dias_mes_anterior=28; break; 
					} 
			   case 4:     $dias_mes_anterior=31; break; 
			   case 5:     $dias_mes_anterior=30; break; 
			   case 6:     $dias_mes_anterior=31; break; 
			   case 7:     $dias_mes_anterior=30; break; 
			   case 8:     $dias_mes_anterior=31; break; 
			   case 9:     $dias_mes_anterior=31; break; 
			   case 10:     $dias_mes_anterior=30; break; 
			   case 11:     $dias_mes_anterior=31; break; 
			   case 12:     $dias_mes_anterior=30; break; 
		} 

		$dias=$dias + $dias_mes_anterior; 
	} 

	//ajuste de posible negativo en $meses 
	if ($meses < 0) 
	{ 
		--$anos; 
		$meses=$meses + 12; 
	} 
	$edad_con_meses_result= array("anos"=>$anos,"meses"=>$meses,"dias"=>$dias);
	return  $edad_con_meses_result;
}


function calculo_percentilo_imc($meses,$sexo,$imc){

	$sql="SELECT * from nacer.percentilos_imc where meses=$meses and sexo='$sexo'";
	$res_sql=sql($sql) or fin_pagina();

	if ($res_sql->RecordCount()!=0 && $imc>0) {
		switch ($imc){
			case $imc<=$res_sql->fields['p3'] : return 1;break;
			case $imc>$res_sql->fields['p3'] and $imc<=$res_sql->fields['p10'] : return 2;break;
			case $imc>$res_sql->fields['p10'] and $imc<=$res_sql->fields['p85'] : return 3;break;
			case $imc>$res_sql->fields['p85'] and $imc<=$res_sql->fields['p97'] : return 4;break;
			case $imc>$res_sql->fields['p97'] :return 5;break;
			default: return 0;break;
		}
	} else return 0;
}



function calculo_percentilo_peso($dias,$sexo,$peso){

	$sql="SELECT * from nacer.percentilos_peso where dias=$dias and sexo='$sexo'";
	$res_sql=sql($sql) or fin_pagina();

	if ($res_sql->RecordCount()!=0 && $peso>0) {
		switch ($peso){
			case $peso<=$res_sql->fields['p3'] : return 1;break;
			case ($peso>$res_sql->fields['p3'] and $peso<=$res_sql->fields['p10']) : return 2;break;
			case ($peso>$res_sql->fields['p10'] and $peso<=$res_sql->fields['p85']) : return 3;break;
			case ($peso>$res_sql->fields['p85'] and $peso<=$res_sql->fields['p97']) : return 4;break;
			case $peso>$res_sql->fields['p97'] :return 5;break;
			default: return 0;break;
		}
	} else return 0;
}

function calculo_percentilo_talla($dias,$sexo,$talla){

	$sql="SELECT * from nacer.percentilos_talla where dias=$dias and sexo='$sexo'";
	$res_sql=sql($sql) or fin_pagina();

	if ($res_sql->RecordCount()!=0 && $talla>0) {
		switch ($talla){
			case $talla<=$res_sql->fields['p3'] : return 1;break;
			case $talla>$res_sql->fields['p3'] and $talla<=$res_sql->fields['p10'] : return 2;break;
			case $talla>$res_sql->fields['p10'] and $talla<=$res_sql->fields['p85'] : return 3;break;
			case $talla>$res_sql->fields['p85'] and $talla<=$res_sql->fields['p97'] : return 4;break;
			case $talla>$res_sql->fields['p97'] :return 5;break;
			default: return 0;break;
		}
	} else return 0;
}

function get_datos_fichero ($cuie,$fecha_desde,$fecha_hasta) {

	if($cuie!='Todos'){
		$sql_tmp="SELECT 
						--leche.beneficiarios.*,
						uad.beneficiarios.*,
						nacer.smiafiliados.*,
						fichero.fichero.*,
						nacer.efe_conv.nombre as nom_efe
						FROM
						fichero.fichero
						LEFT OUTER JOIN nacer.smiafiliados ON fichero.fichero.id_smiafiliados = nacer.smiafiliados.id_smiafiliados
						--LEFT OUTER JOIN leche.beneficiarios ON leche.beneficiarios.id_beneficiarios = fichero.fichero.id_beneficiarios
						LEFT OUTER JOIN uad.beneficiarios ON uad.beneficiarios.id_beneficiarios = fichero.fichero.id_beneficiarios
						INNER JOIN nacer.efe_conv ON nacer.efe_conv.cuie = fichero.fichero.cuie
					where (fichero.fichero.fecha_control BETWEEN '$fecha_desde' and '$fecha_hasta') 
					and (nacer.efe_conv.cuie='$cuie')
					and (embarazo='NO') 
					and (taller='NO')
					and (embarazo_riesgo='NO' or embarazo_riesgo='')
					and (anular='' or anular IS NULL)
					ORDER BY fecha_control";
}else {
			$sql_tmp="SELECT 
						--leche.beneficiarios.*,
						uad.beneficiarios.*,
						nacer.smiafiliados.*,
						fichero.fichero.*,
						nacer.efe_conv.nombre as nom_efe
							FROM
							fichero.fichero
							LEFT OUTER JOIN nacer.smiafiliados ON fichero.fichero.id_smiafiliados = nacer.smiafiliados.id_smiafiliados
							--LEFT OUTER JOIN leche.beneficiarios ON leche.beneficiarios.id_beneficiarios = fichero.fichero.id_beneficiarios
							LEFT OUTER JOIN uad.beneficiarios ON uad.beneficiarios.id_beneficiarios = fichero.fichero.id_beneficiarios
							INNER JOIN nacer.efe_conv ON nacer.efe_conv.cuie = fichero.fichero.cuie
						where (fichero.fichero.fecha_control BETWEEN '$fecha_desde' and '$fecha_hasta') 
						and (embarazo='NO')
						and (taller='NO')
						and (embarazo_riesgo='NO' or embarazo_riesgo='')
						and (anular='' or anular IS NULL)
						ORDER BY fecha_control";
						
		}
	return $sql_tmp;

}


function get_datos_prestaciones ($cuie, $fecha_desde, $fecha_hasta){

	if($cuie!='Todos'){
		$sql_tmp="SELECT 
			e.*,
			a.*,
			b.*,
			f.nombre as nom_efe,
			case when (a.talla is not null and a.talla<>0 and a.peso is not null and a.peso<>0) 
				then  round(a.peso/((a.talla/100)*(a.talla/100)),3)
				else 0 end as imc,
			extract ('YEAR' from age(b.fecha_comprobante, e.afifechanac)) as edad_control_anios,
			extract ('MONTH' from age(b.fecha_comprobante, e.afifechanac)) as edad_control_meses,
			extract ('DAY' from age(b.fecha_comprobante, e.afifechanac)) as edad_control_dias
			FROM
			facturacion.prestacion a,
			facturacion.comprobante b,
			facturacion.nomenclador c,
			nacer.smiafiliados e,
			nacer.efe_conv f
			where 
			a.id_comprobante = b.id_comprobante
			and a.id_nomenclador = c.id_nomenclador
			and b.id_smiafiliados = e.id_smiafiliados
			and b.cuie = f.cuie			
			and b.fecha_comprobante BETWEEN '$fecha_desde' and '$fecha_hasta' 
			and f.cuie='$cuie'
			and c.grupo||c.codigo = 'CTC001'
			ORDER BY b.fecha_comprobante";
}else {
	$sql_tmp="SELECT 
		e.*,
		a.*,
		b.*,
		f.nombre as nom_efe,
		case when (a.talla is not null and a.talla<>0 and a.peso is not null and a.peso<>0) 
				then  round(a.peso/((a.talla/100)*(a.talla/100)),3)
				else 0 end as imc,
		extract ('YEAR' from age(b.fecha_comprobante, e.afifechanac)) as edad_control_anios,
		extract ('MONTH' from age(b.fecha_comprobante, e.afifechanac)) as edad_control_meses,
		extract ('DAY' from age(b.fecha_comprobante, e.afifechanac)) as edad_control_dias
		FROM
		facturacion.prestacion a,
		facturacion.comprobante b,
		facturacion.nomenclador c,
		nacer.smiafiliados e,
		nacer.efe_conv f
		where 
		a.id_comprobante = b.id_comprobante
		and a.id_nomenclador = c.id_nomenclador
		and b.id_smiafiliados = e.id_smiafiliados
		and b.cuie = f.cuie			
		and b.fecha_comprobante BETWEEN '$fecha_desde' and '$fecha_hasta' 
		and c.grupo||c.codigo = 'CTC001'
		ORDER BY b.fecha_comprobante";
						
		}
	return $sql_tmp;
	
}
			
$sql_tmp = get_datos_prestaciones ($cuie,$fecha_desde,$fecha_hasta);
$res_comprobante=sql($sql_tmp,"<br>Error al traer los datos<br>") or fin_pagina();
excel_header("report_fichero_nomi_excel.xls");?>

<form name=form1 action="report_fichero_nomi_excel.php" method=POST>
<table border=1 width=100% cellspacing=2 cellpadding=2 align=center>
  <tr>
  	<td colspan=17 align=left>
     <table width=100%>
      <tr id="ma">
      <td width=30% align=left><b>Total:</b> <?php echo $res_comprobante->recordCount()?></td>       
      </tr>
      <tr>
      <td width=30% align=left><b>Fecha Desde</b> <?php echo $fecha_desde;?> <b> Fecha Hasta</b> <?php echo $fecha_hasta;?></td>       
      </tr>
    </table>
   </td>
  </tr>
    <td align="center">Efector</td>
    <td align="center">DNI</td>
    <td align="center">Nombre</td>
    <td align="center">Apellido</td>
    <td align="center">Fecha Nac</td>
    <td align="center">Sexo</td>
		<td align="center">Edad Años</td>
		<td align="center">Edad Meses</td>
		<td align="center">Edad Dias</td>
    <td align="center">Domicilio</td>
    <td align="center">Fecha Control</td>
    <td align="center">Peso</td>
    <td align="center">Talla</td>
    <td align="center">IMC</td>
    <td align="center">Per. Cef.</td>
    <td align="center">Perc Peso/Edad</td>
    <td align="center">Perc Talla/Edad</td>
    <!--<td align="center">Perc Perim. Cefalico/Edad</td>-->
    <td align="center">Perc IMC/Edad</td>
    <td align="center">Perc Peso/Talla</td>
	<!--<td align="center">Tipo de Lactancia</td>-->
  </tr>
 <? while (!$res_comprobante->EOF) {?>  	
  
    <tr>     
    <td ><?php echo $res_comprobante->fields['nom_efe']?></td>    
	<td ><?php echo ($res_comprobante->fields['afidni']!='')?$res_comprobante->fields['afidni']:$res_comprobante->fields['documento'];?></td>    
	<td ><?php echo ($res_comprobante->fields['afinombre']!='')?$res_comprobante->fields['afinombre']:$res_comprobante->fields['nombre'];?></td>    
	<td ><?php echo ($res_comprobante->fields['afiapellido']!='')?$res_comprobante->fields['afiapellido']:$res_comprobante->fields['apellido'];?></td>  
	<td ><?php echo ($res_comprobante->fields['afifechanac']!='')?fecha($res_comprobante->fields['afifechanac']):fecha($res_comprobante->fields['fecha_nac']);?></td>
	<? if ($res_comprobante->fields['afisexo']!='') $sexo = trim($res_comprobante->fields['afisexo']);
		 	else $sexo = trim($res_comprobante->fields['sexo']);?>
	<td ><?php echo $sexo;?></td>  
	<?php /*$edad = edad_con_meses($res_comprobante->fields['afifechanac'],$res_comprobante->fields['fecha_comprobante']);*/?>
	<td ><?php echo $res_comprobante->fields['edad_control_anios']?></td>
	<td ><?php echo $res_comprobante->fields['edad_control_meses']?></td>
	<td ><?php echo $res_comprobante->fields['edad_control_dias']?></td> 
	<td ><?php echo ($res_comprobante->fields['afidomlocalidad']!='')?$res_comprobante->fields['afidomlocalidad']:$res_comprobante->fields['domicilio'];?></td>  
  <td ><?php echo fecha($res_comprobante->fields['fecha_comprobante'])?></td>  
  <td ><?php echo number_format($res_comprobante->fields['peso'],2,',',0)?></td>    
  <td ><?php echo number_format($res_comprobante->fields['talla'],2,',',0)?></td>    
  <td ><?if ($res_comprobante->fields['imc']!='NaN') echo number_format($res_comprobante->fields['imc'],3,',',0);
  else echo '0,00';?></td>    
 	<td ><?php echo number_format($res_comprobante->fields['perim_cefalico'],1,',',0)?></td>
		 
		 <?
		 $edad_dias = $res_comprobante->fields['edad_control_anios']*365 + $res_comprobante->fields['edad_control_meses']*30 + $res_comprobante->fields['edad_control_dias'];
		 $percen_peso_edad = calculo_percentilo_peso($edad_dias,$sexo,$res_comprobante->fields['peso']);
		 //$percen_peso_edad = $res_comprobante->fields['percen_peso_edad'];
		 switch ($percen_peso_edad) {
      				case 1: $percentilo_peso_edad="<3";break;
      				case 2: $percentilo_peso_edad="3-10";break;
      				case 3: $percentilo_peso_edad=">10-85";break;
      				case 4: $percentilo_peso_edad=">85-97";break;
      				case 5: $percentilo_peso_edad=">97";break;
      				case 0: $percentilo_peso_edad="Datos sin Ingresar";break;
      			};
      	
      	$percen_talla_edad = calculo_percentilo_talla($edad_dias,$sexo,$res_comprobante->fields['talla']);
		//$percen_talla_edad = $res_comprobante->fields['percen_talla_edad'];
		switch ($percen_talla_edad) {
			case 1:$percentilo_talla_edad=">-3";break;
			case 2:$percentilo_talla_edad=">3-97";break;
			case 3:$percentilo_talla_edad=">97";break;
			case 0: $percentilo_talla_edad="Datos sin Ingresar";break;
			default: $percentilo_talla_edad="Datos sin Ingresar";break;
   			};

   		
		//$percen_perim_cefali_edad = ;
		/*$percen_perim_cefali_edad = $res_comprobante->fields['percen_perim_cefali_edad'];
		switch ($percen_perim_cefali_edad) {
			case 1:$percentilo_perim_cefalico_edad=">-3";break;
			case 2:$percentilo_perim_cefalico_edad=">3-97";break;
			case 3:$percentilo_perim_cefalico_edad=">+97";break;
			case 0: $percentilo_perim_cefalico_edad="Datos sin Ingresar";break;
    	};*/

    	
		//$percen_peso_talla = ;
		/*$percen_peso_talla = $res_comprobante->fields['percen_peso_talla'];
		switch ($percen_peso_talla) {
			case 1:$percentilo_peso_talla="<3";break;
			case 2:$percentilo_peso_talla="(3-10)";break;
			case 3:$percentilo_peso_talla=">10-85";break;
			case 4:$percentilo_peso_talla=">85-97";break;
			case 5:$percentilo_peso_talla=">97";break;
			case 0: $percentilo_peso_talla="Datos sin Ingresar";break;
   		};*/

   	$edad_meses = $res_comprobante->fields['edad_control_anios']*12 + $res_comprobante->fields['edad_control_meses'];
		$percen_imc_edad = calculo_percentilo_imc($edad_meses,$sexo,$res_comprobante->fields['imc']);
		//$percen_imc_edad = ($res_comprobante->fields['percen_imc_edad']);
		switch  ($percen_imc_edad) {
   			case 1:$percentilo_imc_edad="<3";break;
   			case 2:$percentilo_imc_edad="(3-10)";break;
   			case 3:$percentilo_imc_edad=" >10-85";break;
   			case 4:$percentilo_imc_edad=">85-97";break;
   			case 5:$percentilo_imc_edad=" >97";break;
   			case 0: $percentilo_imc_edad="Dato Sin Ingresar";break;
   		}

   		?> 

		 <td><?php echo $percentilo_peso_edad;?></td>
	   <td><?php echo $percentilo_talla_edad;?></td>	
	   <!--<td><?php echo $percentilo_perim_cefalico_edad;?></td>		   -->
   	 <td ><?php echo $percentilo_imc_edad?></td>
		 <? /*switch ($res_comprobante->fields['tipo_lactancia']) {
				case 'LME': $tipo_lactancia='Exclusiva';break;
				case 'LMM': $tipo_lactancia='Predominante';break;
				case 'NLM': $tipo_lactancia='Artificial';break;
				default: $tipo_lactancia='Dato sin Ingresar';break;
		 }*/?>
		 <!--<td ><?php echo $tipo_lactancia?></td>-->
    </tr>
	<?php $res_comprobante->MoveNext();
   }?>
    
    
</table>
<br>
	
</td>
</table>

</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
