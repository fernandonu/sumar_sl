<?
/*
$Author: seba $
$Revision: 2.5 $
$Date: 2022/02/11$
*/

require_once("../../config.php");


function calculo_talla () {

	$query="UPDATE fichero.fichero set talla=ccc.talla_fix from (
	select *,talla/100::numeric(32,2) as talla_fix from fichero.fichero where fecha_control >='2018-01-01' 
		and talla>3

		) as ccc
		where fichero.id_fichero=ccc.id_fichero
		";

	sql($query) or fin_pagina();
}



function calculo_imc() {

$query="UPDATE fichero.fichero set imc=ccc.imc_fix from (
select *,peso/(talla*talla) as imc_fix from fichero.fichero where fecha_control >='2018-01-01'  
		and talla<>0
		and peso<>0
		and imc>0 and imc<=1	

		) as ccc
		where fichero.id_fichero=ccc.id_fichero";

sql($query) or fin_pagina();

}

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

calculo_talla();
calculo_imc();


$sql="SELECT nacer.smiafiliados.*,
		fichero.fichero.*
		FROM
		fichero.fichero
		LEFT OUTER JOIN nacer.smiafiliados ON fichero.fichero.id_smiafiliados = nacer.smiafiliados.id_smiafiliados
		where embarazo='NO' and fecha_control > '2020-01-01'";
$res_sql=sql($sql) or fin_pagina();

while (!$res_sql->EOF){
	
	$id_fichero=$res_sql->fields['id_fichero'];
	
	if ($res_sql->fields['afifechanac']!='') {
		$fecha_nac=$res_sql->fields['afifechanac'];
		$sexo=trim($res_sql->fields['afisexo']);
		}
	else {
		$fecha_nac=$res_sql->fields['fecha_nac'];
		$sexo=trim($res_sql->fields['sexo']);
		}
	
	$fecha_control=$res_sql->fields['fecha_control'];
	$edad_con_meses=edad_con_meses($fecha_nac,$fecha_control);
  	$anio=$edad_con_meses['anos'];
	$meses=$edad_con_meses['meses'];
	$dias=$edad_con_meses['dias'];

	$peso=$res_sql->fields['peso'];
	$talla=$res_sql->fields['talla'];
	if ($res_sql->fields['imc']=='' or $res_sql->fields['imc']=='NaN' or $res_sql->fields['imc']==0){
		if ($talla<>'' and talla<>0) {$imc=$peso/($talla*$talla);}
	}
		else {$imc=$res_sql->fields['imc'];};
    
    
    if ($anio==0) {
		
		$dias=$dias+($meses*30);
		if ($peso!=0){
        $percen_peso_edad=calculo_percentilo_peso($dias,$sexo,$peso);
        $update="UPDATE fichero.fichero set 
				imc=$imc,
                percen_peso_edad='$percen_peso_edad'
                where id_fichero=$id_fichero";
        sql($update) or fin_pagina();
        };
    }

    elseif ($anio>=1 and $anio<=5) {
		  $meses=$meses+($anio*12);
		  $dias=$dias+($meses*30);
		  if ($peso!=0) $percen_peso_edad=calculo_percentilo_peso($dias,$sexo,$peso); 
		  else $percen_peso_edad='Sin Datos';
      if ($imc!=0) $percen_imc_edad=calculo_percentilo_imc($meses,$sexo,$imc);
		  else $percen_imc_edad='Sin Datos';
      if ($talla!=0) $percen_talla_edad=calculo_percentilo_talla($dias,$sexo,($talla*100));
      else $percen_talla_edad='Sin Datos';
    
      $update="UPDATE fichero.fichero set 
          imc=$imc,
		  percen_peso_edad='$percen_peso_edad',
          percen_talla_edad='$percen_talla_edad',
          percen_imc_edad='$percen_imc_edad' 
          where id_fichero=$id_fichero";
      sql($update) or fin_pagina();
		}

	elseif ($anio>5 and $anio<=19) {
		$meses=$meses+($anio*12);
		if ($imc!=0) $percen_imc_edad=calculo_percentilo_imc($meses,$sexo,$imc);
		else $percen_imc_edad='Sin Datos';
    if ($talla!=0) $percen_talla_edad=calculo_percentilo_talla($meses,$sexo,($talla*100));
    else $percen_talla_edad='Sin Datos';
    
    $update="UPDATE fichero.fichero set 
          imc=$imc,
		  percen_talla_edad='$percen_talla_edad',
          percen_imc_edad='$percen_imc_edad' 
          where id_fichero=$id_fichero";
    sql($update) or fin_pagina();
  	}  

$res_sql->MoveNext();
};
echo fin_pagina();// aca termino ?>