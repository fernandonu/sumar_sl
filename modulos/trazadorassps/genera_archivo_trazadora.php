<?php

require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();


function bisiesto_local($anio_actual){ 
    $bisiesto=false; 
    //probamos si el mes de febrero del año actual tiene 29 días 
      if (checkdate(2,29,$anio_actual)) 
      { 
        $bisiesto=true; 
    } 
    return $bisiesto; 
}

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

	if ($res_sql->RecordCount()!=0) {
		switch ($imc){
			case $imc<=$res_sql->fields['p3'] : return '1';breck;
			case $imc>$res_sql->fields['p3'] and $imc<=$res_sql->fields['p10'] : return '2';breck;
			case $imc>$res_sql->fields['p10'] and $imc<=$res_sql->fields['p85'] : return '3';breck;
			case $imc>$res_sql->fields['p85'] and $imc<=$res_sql->fields['p97'] : return '4';breck;
			case $imc>$res_sql->fields['p97'] :return '5';breck;
		}
	}
}



function calculo_percentilo_peso($dias,$sexo,$peso){

	$sql="SELECT * from nacer.percentilos_peso where dias=$dias and sexo='$sexo'";
	$res_sql=sql($sql) or fin_pagina();

	if ($res_sql->RecordCount()!=0) {
		switch ($peso){
			case $peso<=$res_sql->fields['p3'] : return '1';breck;
			case ($peso>$res_sql->fields['p3'] and $peso<=$res_sql->fields['p10']) : return '2';breck;
			case ($peso>$res_sql->fields['p10'] and $peso<=$res_sql->fields['p85']) : return '3';breck;
			case ($peso>$res_sql->fields['p85'] and $peso<=$res_sql->fields['p97']) : return '4';breck;
			case $peso>$res_sql->fields['p97'] :return '5';breck;
		}
	}
}

function calculo_percentilo_talla($dias,$sexo,$talla){

	$sql="SELECT * from nacer.percentilos_talla where dias=$dias and sexo='$sexo'";
	$res_sql=sql($sql) or fin_pagina();

	if ($res_sql->RecordCount()!=0) {
		switch ($talla){
			case $talla<=$res_sql->fields['p3'] : return '1';breck;
			case $talla>$res_sql->fields['p3'] and $talla<=$res_sql->fields['p10'] : return '2';breck;
			case $talla>$res_sql->fields['p10'] and $talla<=$res_sql->fields['p85'] : return '3';breck;
			case $talla>$res_sql->fields['p85'] and $talla<=$res_sql->fields['p97'] : return '4';breck;
			case $talla>$res_sql->fields['p97'] :return '5';breck;
		}
	}
}


function genera_trazadora_1($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente) {

//TRAZADORA 1 - SEGUIMIENTO DE SALUD DE LA MUJER EMBARAZADA
//IMPLEMENTAR CON LAS NUEVAS VERSIONES DE LAS TABLAS
}


function genera_trazadora_2($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente) {
//TRAZADORA 2 - SEGUIMIENTO DE SALUD DEL NIÑO MENOR DE 10 AÑOS
//IMPLEMENTAR CON LAS NUEVAS VERSIONES DE LAS TABLAS
//RESUELTO POR SCRIPT SOBRE BASE DE DATOS
   
}

function genera_trazadora_3($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente) {
//TRAZADORA 3 - SEGUIMIENTO DEL ADOLESCENTE DE 10 A 19 AÑOS
//IMPLEMENTAR CON LAS NUEVAS VERSIONES DE LAS TABLAS  	

}

function genera_trazadora_4 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente) {
//TRAZADORA 4 - SEGUIMIENTO DEL NIÑO CON SOBREPESO U OBESIDAD
//IMPLEMENTAR CON LAS NUEVAS VERSIONES DE LAS TABLAS 2025
}	
	
function genera_trazadora_5 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente) {
//TRAZADORA 5 - TAMNIZAJE DE CANCER COLORRECTAL
$sql="SELECT comprobante.cuie,
		smiafiliados.clavebeneficiario,
		trim(smiafiliados.aficlasedoc) as aficlasedoc,
		smiafiliados.afitipodoc,
		smiafiliados.afidni,
		smiafiliados.afiapellido,
		smiafiliados.afinombre,
		trim(smiafiliados.afisexo) as afisexo,
		smiafiliados.afifechanac,
		comprobante.fecha_comprobante,
		prestacion.tisomf
		from facturacion.prestacion 
		inner join facturacion.comprobante using (id_comprobante)
		inner join nacer.smiafiliados using (id_smiafiliados)
		inner join facturacion.nomenclador using (id_nomenclador)
		where nomenclador.codigo = 'L098'
		order by fecha_prestacion";

$res_sql_1=sql ($sql,"error al traer los registro de la trazadora 7 - Prev.Colorec.")  or fin_pagina();
	$filename = "$trz"."12"."$anio"."$cuatrim"."00001.txt";
	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}

    	$res_sql_1->movefirst();
    	while (!$res_sql_1->EOF) {
    		$contenido=$res_sql_1->fields['cuie']."\t";
    		$contenido.=$res_sql_1->fields['clavebeneficiario']."\t";
    		$contenido.=$res_sql_1->fields['afitipodoc']."\t";
			$contenido.=$res_sql_1->fields['afidni']."\t";
			$contenido.=$res_sql_1->fields['afiapellido']."\t";
			$contenido.=$res_sql_1->fields['afinombre']."\t";
			$contenido.=$res_sql_1->fields['afifechanac']."\t";
			$contenido.=trim($res_sql_1->fields['afisexo'])."\t";
			$contenido.=$res_sql_1->fields['fecha_comprobante']."\t";
			//$contenido.='Negativo'."\t";
			if ($res_sql_1->fields['tisomf'] and $res_sql_1->fields['tisomf']<>'') $contenido.=$res_sql_1->fields['tisomf']."\t";
				else $contenido.='negativo'."\t";
			$contenido.="1\t";
			$contenido.="\n\r";
    		if (fwrite($handle, $contenido) === FALSE) {
        		echo "No se Puede escribir  ($filename)";
        		exit;
    		}
    		$res_sql_1->MoveNext();
    	}
    echo "El Archivo ($filename) se genero con exito \n";
    
    fclose($handle);
	}

	
function genera_trazadora_6 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente) {

//--consulta para trazadora_VI (prevencion de Cancer Cervico-Uterino) sobre trazadorassps.trazadora_12
//--mathing con smiafiliados y leche.beneficiarios	

	$date=$anio.'-01-01';
	$sql_1="SELECT distinct on (afidni,fecha_diagnostico)
	nacer.smiafiliados.clavebeneficiario, 
	nacer.smiafiliados.afiapellido, 
	nacer.smiafiliados.afinombre, 
	nacer.smiafiliados.afitipodoc, 
	nacer.smiafiliados.afidni, 
	nacer.smiafiliados.afisexo::character(1),
	nacer.smiafiliados.afifechanac,
	trazadorassps.trazadora_12.fecha_diagnostico,
	trazadorassps.trazadora_12.diagnostico,
	case when (trazadorassps.trazadora_12.fecha_inic_tratamiento='1900-01-01')
		then trazadorassps.trazadora_12.fecha_diagnostico
		else trazadorassps.trazadora_12.fecha_inic_tratamiento end ::date as fecha_inic_tratamiento ,
	trazadorassps.trazadora_12.cuie
	from nacer.smiafiliados
	inner join trazadorassps.trazadora_12 on nacer.smiafiliados.id_smiafiliados=trazadorassps.trazadora_12.id_smiafiliados
	where  trazadorassps.trazadora_12.fecha_diagnostico >='$date' and trazadorassps.trazadora_12.diagnostico::integer>=4";
			
	$res_sql_1=sql ($sql_1,"error al traer los registro de la trazadora VI")  or fin_pagina();
	$filename = "$trz"."12"."$anio"."$cuatrim"."00001-TRZ6-diagnostico(trazadorassps.trazadora_12).txt";
	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    }

	$res_sql_1->movefirst();
	while (!$res_sql_1->EOF) {
		$contenido.=$res_sql_1->fields['cuie']."\t";
		$contenido.=$res_sql_1->fields['clavebeneficiario']."\t";
		$contenido.=$res_sql_1->fields['afitipodoc']."\t";
		$contenido.=$res_sql_1->fields['afidni']."\t";
		$contenido.=$res_sql_1->fields['afiapellido']."\t";
		$contenido.=$res_sql_1->fields['afinombre']."\t";
		$contenido.=$res_sql_1->fields['afifechanac']."\t";
		$contenido.=$res_sql_1->fields['afisexo']."\t";
		$contenido.='D'."\t";
		$contenido.=$res_sql_1->fields['fecha_diagnostico']."\t";
		$contenido.=$res_sql_1->fields['diagnostico']."\t";				
		$contenido.='10'."\n\r";
		if (fwrite($handle, $contenido) === FALSE) {
    		echo "No se Puede escribir  ($filename)";
    		exit;
		}
		$res_sql_1->MoveNext();
	}
    echo "El Archivo ($filename) se genero con exito".'<br>';
    
    fclose($handle);

    $filename = "$trz"."12"."$anio"."$cuatrim"."00001-TRZ6-Tratamiento(trazadorassps.trazadora_12).txt";
	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    }

	$res_sql_1->movefirst();
	while (!$res_sql_1->EOF) {
		$contenido.=$res_sql_1->fields['cuie']."\t";
		$contenido.=$res_sql_1->fields['clavebeneficiario']."\t";
		$contenido.=$res_sql_1->fields['afitipodoc']."\t";
		$contenido.=$res_sql_1->fields['afidni']."\t";
		$contenido.=$res_sql_1->fields['afiapellido']."\t";
		$contenido.=$res_sql_1->fields['afinombre']."\t";
		$contenido.=$res_sql_1->fields['afifechanac']."\t";
		$contenido.=$res_sql_1->fields['afisexo']."\t";
		$contenido.='T'."\t";
		$contenido.=$res_sql_1->fields['fecha_inic_tratamiento']."\t";
		$contenido.=''."\t";//diagnostico		
		$contenido.='10'."\n\r";
		if (fwrite($handle, $contenido) === FALSE) {
    		echo "No se Puede escribir  ($filename)";
    		exit;
		}
		$res_sql_1->MoveNext();
	}
    echo "El Archivo ($filename) se genero con exito".'<br>';
    
    fclose($handle);
}
	
	
function genera_trazadora_7 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente) {
//--consulta para trazadora_VII (Cancer de mama) sobre trazadorassps.trazadora_13
//--con mathing smiafiliados y leche.beneficiarios
	$date=$anio.'-01-01';
	$sql_1="SELECT distinct on (afidni,fecha_diagnostico)
			nacer.smiafiliados.clavebeneficiario, 
			nacer.smiafiliados.afiapellido, 
			nacer.smiafiliados.afinombre, 
			nacer.smiafiliados.afitipodoc, 
			nacer.smiafiliados.afidni, 
			nacer.smiafiliados.afifechanac,
			nacer.smiafiliados.afisexo::character(1),
			trazadorassps.trazadora_13.fecha_diagnostico,
			trazadorassps.trazadora_13.diagnostico,
			trazadorassps.trazadora_13.fecha_inic_tratamiento,
			trazadorassps.trazadora_13.cuie
			from nacer.smiafiliados
			inner join trazadorassps.trazadora_13 on nacer.smiafiliados.id_smiafiliados=trazadorassps.trazadora_13.id_smiafiliados
			where  trazadorassps.trazadora_13.fecha_diagnostico>='$date' and trazadorassps.trazadora_13.diagnostico<>''";
			
	$res_sql_1=sql ($sql_1,"error al traer los registro de la trazadora VII")  or fin_pagina();
	$filename = "$trz"."12"."$anio"."$cuatrim"."00001-TRZ7-diag(trazadorassps.trazadora_13).txt";
	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    }

	$res_sql_1->movefirst();
	while (!$res_sql_1->EOF) {
		$contenido.=$res_sql_1->fields['cuie']."\t";
		$contenido.=$res_sql_1->fields['clavebeneficiario']."\t";
		$contenido.=$res_sql_1->fields['afitipodoc']."\t";
		$contenido.=$res_sql_1->fields['afidni']."\t";
		$contenido.=$res_sql_1->fields['afiapellido']."\t";
		$contenido.=$res_sql_1->fields['afinombre']."\t";
		$contenido.=$res_sql_1->fields['afifechanac']."\t";
		$contenido.=$res_sql_1->fields['afisexo']."\t";
		$contenido.='D'."\t";
		$contenido.=$res_sql_1->fields['fecha_diagnostico']."\t";
		$contenido.=$res_sql_1->fields['diagnostico']."\t";
		$contenido.='10'."\n\r";
		if (fwrite($handle, $contenido) === FALSE) {
    		echo "No se Puede escribir  ($filename)";
    		exit;
		}
		$res_sql_1->MoveNext();
	}
    echo "El Archivo ($filename) se genero con exito\n ";
    
    fclose($handle);

    $filename = "$trz"."12"."$anio"."$cuatrim"."00001-TRZ7-trat(trazadorassps.trazadora_13).txt";
	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    }

	$res_sql_1->movefirst();
	while (!$res_sql_1->EOF) {
		$contenido.=$res_sql_1->fields['cuie']."\t";
		$contenido.=$res_sql_1->fields['clavebeneficiario']."\t";
		$contenido.=$res_sql_1->fields['afitipodoc']."\t";
		$contenido.=$res_sql_1->fields['afidni']."\t";
		$contenido.=$res_sql_1->fields['afiapellido']."\t";
		$contenido.=$res_sql_1->fields['afinombre']."\t";
		$contenido.=$res_sql_1->fields['afifechanac']."\t";
		$contenido.=$res_sql_1->fields['afisexo']."\t";
		$contenido.='T'."\t";
		$contenido.=$res_sql_1->fields['fecha_inic_tratamiento']."\t";
		$contenido.=$res_sql_1->fields['diagnostico']."\t";
		$contenido.='10'."\n\r";
		if (fwrite($handle, $contenido) === FALSE) {
    		echo "No se Puede escribir  ($filename)";
    		exit;
		}
		$res_sql_1->MoveNext();
	}
    echo "El Archivo ($filename) se genero con exito\n ";
    
    fclose($handle);

}
	
function genera_trazadora_8 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente) {

	//diabetes
	//IMPLEMENTAR
	//RESULTO POR SCRIPT SOBRE BASE DE DATOS

	}
		
function genera_trazadora_9 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente) {

//hipertension
//IMPLEMENTAR

}
	
function genera_trazadora_10 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente) {

//identificacion de personas a cargo
//IMPLEMENTAR LLENADO DE TABLAS CON MIGRACION DE DATOS QUE VIENEN DESDE AGENTES SANITARIOS
//LO PASA GUILLE
}
	

	
	if ($_POST['generar']){
	
	$anio=$_POST['anio'];
	$cuatrim=$_POST['cuatrimestre'];
	$fuente=$_POST['fuente'];
	
	if ($_POST['opcion_fecha']!='S') {
		switch ($cuatrim) {
		case 1 : $fecha_desde = $anio."-"."01-01"; $fecha_hasta=$anio."-"."04-30";break;
		case 2 : $fecha_desde = $anio."-"."05-01"; $fecha_hasta=$anio."-"."08-31";break;
		case 3 : $fecha_desde = $anio."-"."09-01"; $fecha_hasta=$anio."-"."12-31";break;
		};
	} else {
		$fecha_desde=fecha_db($_POST['fecha_desde']);
		$fecha_hasta=fecha_db($_POST['fecha_hasta']);
		};
		
	$trz=$_POST['trazadora'];
	switch ($trz) {
		case 'EB': genera_trazadora_1($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente); break;
		case 'NI': genera_trazadora_2 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente); break;
		case 'T3': genera_trazadora_3($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente); break;
 		case 'SP': genera_trazadora_4 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente); break;
		case 'RE': genera_trazadora_5 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente); break;
		case 'UT': genera_trazadora_6 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente); break;
		case 'MA': genera_trazadora_7 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente); break;
		case 'DI': genera_trazadora_8 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente); break;
		case 'HT': genera_trazadora_9 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente); break;
		case 'EP': genera_trazadora_10 ($trz,$fecha_desde,$fecha_hasta,$anio,$cuatrim,$fuente); break;
		 };
	
}

echo $html_header;
?>

<form name='form1' action='genera_archivo_trazadora.php' method='POST' enctype='multipart/form-data'>
<table width="80%" class="bordes" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
<tr><td>
  <table width=100% align="center" class="bordes">
  
	<tr id="mo" align="center">
    <td colspan="2" align="center">
    	<font size=+1><b>EXPORTACION DE ARCHIVOS PARA EL NUEVO SISTEMA DE TRAZADORAS</b></font> 
		 </td>
   </tr>
   		<tr>	           
           <td align="center" colspan="2" id="ma">
            <b> Exportacion de Archivos para Trazadoras </b>
           </td>
         </tr>
     <tr>
      <td align="right">
					    <b>Seleccione la Trazadora:</b>
					    </td>
					    <td align="left">
					    <select name=trazadora Style="width=200px">
				    	 <option value='EB'>Trazadora 1 - Cuidado del Embarazo</option>
						 <option value='NI'>Trazadora 2 - Seg.Salud 10 años</option>
						 <option value='T3'>Trazadora 3 - Seg.Salud Adolec.</option>
						 <option value='SP'>Trazadora 4 - Seg. Sobrepeso y Obesidad</option>
						 <option value='RE'>Trazadora 5 - Canc.Colonrrectal</option>
						 <option value='UT'>Trazadora 6 - Diag.Trat. Canc.Cervico-Uterino</option>
						 <option value='MA'>Trazadora 7 - Diag.Trat. Canc.Mama</option>
						 <option value='DI'>Trazadora 8 - Seg.Adulto con Diab.mellitus</option>
						 <option value='HT'>Trazadora 9 - Seg.Adulto con hipert.arter.</option>
						 <option value='EP'>Trazadora 10 - Ident.poblacion</option>
						 </select>
		</td>
		</tr>
		<tr>
      <td align="right">
					    <b>Seleccione el Cuatrimestre:</b>
					    </td>
					    <td align="left">
					    <select name=cuatrimestre Style="width=150px">
					    	 <option value='1'>Cuatrimestre I</option>
							 <option value='2'>Cuatrimestre II</option>
							 <option value='3'>Cuatrimestre III</option>
						</select>
		</td>
		</tr>
		<tr>
		<td align="right">
					    <b>Seleccione el Año:</b>
					    </td>
					    <td align="left">
					    <select name=anio Style="width=60px">					    	 
							 <option value='2023'>2023</option>
							 <option value='2024'>2024</option>
							 <option value='2025'>2025</option>
						</select>
		</td>
	  </tr>

	  <tr>
		<td align="right">
					    <b>Tabla(fuente):</b>
					    </td>
					    <td align="left">
					    <select name="fuente" Style="width=60px">
					    	<option value='trazadoras'>Trazadoras</option>
							<option value='fichero'>Fichero</option>
							<option value='sipweb'>SipWeb</option>
							<option value='prestaciones'>Prestaciones</option>		
							<option value='preg_prenatal'>Preg_Prenatal</option>	
							<option value='registros_medicos'>Registros Medicos</option>							 
						</select>
		</td>
	  </tr>
	  
	 </td></tr>
     <table width="80%" class="bordes" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
	 <td align="center">		
	 <input type=submit name="generar" value='generar' style="width=250px">
	 </td>
	 </table>
	 </form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>