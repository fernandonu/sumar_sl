<?php

//extraer fecha desde y hasta desde el dia_hoy
function periodo($dia_hoy){
	
	$mes=intval(substr($dia_hoy,5,2));
	//$anio=substr($dia_hoy,0,4);
	$anio='2020';
	if ($mes>=1 and $mes<=3) {
		$fecha_desde = $anio.'-01-01';
		$fecha_hasta = $anio.'-03-31';
	}
	
	elseif ($mes>=4 and $mes <=6) {
		$fecha_desde = $anio.'-04-01';
		$fecha_hasta = $anio.'-06-30';
	}

	elseif ($mes>=7 and $mes<=9) {
		$fecha_desde = $anio.'-07-01';
		$fecha_hasta = $anio.'-09-30';
	}

	else {
		$fecha_desde = $anio.'-10-01';
		$fecha_hasta = $anio.'-12-31';
	}

	$fechas = array (
		"fecha_desde" => $fecha_desde,
		"fecha_hasta" => $fecha_hasta
	);

	return $fechas;
}

//Personas NUEVAS
function benef_nuevos($cuie,$fecha_desde,$fecha_hasta) {
	$sql_1 = "SELECT distinct on (a.id_beneficiarios,a.id_smiafiliados) *
	from programa_sexual.comprobantes a, nacer.efe_conv b
	where a.cuie = b.cuie
	and a.cuie = '$cuie'
	and a.fecha_entrega between '$fecha_desde' and '$fecha_hasta'
	and a.ingresa_programa = 'S'";
	$res=sql($sql_1);
	$count = $res->recordCount();
	return $count;
}

//total de Beneficiarios
function total_benef($cuie,$fecha_desde,$fecha_hasta) {
	$sql_1 = "SELECT distinct on (a.id_beneficiarios,a.id_smiafiliados) *
	from programa_sexual.comprobantes a, nacer.efe_conv b
	where a.cuie = b.cuie
	and a.cuie = '$cuie'
	and a.fecha_entrega between '$fecha_desde' and '$fecha_hasta'";
	
	$res=sql($sql_1);
	$count = $res->recordCount();
	return $count;
}

//Total de prestaciones
function total_prest($cuie,$fecha_desde,$fecha_hasta) {
	$sql_1 = "SELECT sum (c.cantidad) as cantidad_prestaciones
	from programa_sexual.comprobantes a, nacer.efe_conv b, programa_sexual.prestaciones c
	where a.cuie = b.cuie
	and a.id_comprobante = c.id_comprobante
	and a.cuie = '$cuie'
	and a.fecha_entrega between '$fecha_desde' and '$fecha_hasta'";

	$res=sql($sql_1);
	$count = ($res->field['cantidad_prestaciones']==NULL)?0:$res->field['cantidad_prestaciones'];
	return $count;
}

//Total de Personas con Obra Social --revisar
function total_benef_os($cuie,$fecha_desde,$fecha_hasta) {
	$sql_3 = "SELECT count (distinct (a.id_beneficiarios)) as cantidad
	from programa_sexual.comprobantes a 
	where a.cuie = '$cuie'
	and a.fecha_entrega between '$fecha_desde' and '$fecha_hasta'
	and a.id_beneficiarios is not null";
}

//Total de Beneficiarios SIN Obra Social (que esten en smiafiliados no significa q no tengan os--revisar)
function total_benef_sin_os($cuie,$fecha_desde,$fecha_hasta) {
	$sql_4 = "SELECT count (distinct (a.id_smiafiliados)) as cantidad
	from programa_sexual.comprobantes a 
	where a.cuie = '$cuie'
	and a.fecha_entrega between '$fecha_desde' and '$fecha_hasta'
	and a.id_smiafiliados is not null";
}

//Mujeres Menores de 20 años
function mujeres_menor_20($cuie,$fecha_desde,$fecha_hasta) {
	$sql_1 ="SELECT count (distinct (a.id_smiafiliados)) as cantidad
	--select *, extract ('year' from age (a.fecha_entrega, b.afifechanac))
	FROM programa_sexual.comprobantes a, nacer.smiafiliados b
	where a.id_smiafiliados = b.id_smiafiliados
	and a.cuie = '$cuie'
	and a.fecha_entrega between '$fecha_desde' and '$fecha_hasta'
	and a.id_smiafiliados is not null
	and trim(b.afisexo) = 'F'
	and extract ('year' from age (a.fecha_entrega, b.afifechanac)) < 20";

	$res=sql($sql_1);
	$count = $res->fields['cantidad'];
	return $count;
}


//Mujeres Mayores de 20 años
function mujeres_mayor_20($cuie,$fecha_desde,$fecha_hasta) {
	$sql_1 ="SELECT count (distinct (a.id_smiafiliados)) as cantidad
	--select *, extract ('year' from age (a.fecha_entrega, b.afifechanac))
	FROM programa_sexual.comprobantes a, nacer.smiafiliados b
	where a.id_smiafiliados = b.id_smiafiliados
	and a.cuie = '$cuie'
	and a.fecha_entrega between '$fecha_desde' and '$fecha_hasta'
	and a.id_smiafiliados is not null
	and trim(b.afisexo) = 'F'
	and extract ('year' from age (a.fecha_entrega, b.afifechanac)) >= 20";

	$res=sql($sql_1);
	$count = $res->fields['cantidad'];
	return $count;
}


//Varones Menores de 20 años
function varones_menor_20($cuie,$fecha_desde,$fecha_hasta) {
	$sql_1 ="SELECT count (distinct (a.id_smiafiliados)) as cantidad
	--select *, extract ('year' from age (a.fecha_entrega, b.afifechanac))
	FROM programa_sexual.comprobantes a, nacer.smiafiliados b
	where a.id_smiafiliados = b.id_smiafiliados
	and a.cuie = '$cuie'
	and a.fecha_entrega between '$fecha_desde' and '$fecha_hasta'
	and a.id_smiafiliados is not null
	and trim(b.afisexo) = 'M'
	and extract ('year' from age (a.fecha_entrega, b.afifechanac)) < 20";

	$res=sql($sql_1);
	$count = $res->fields['cantidad'];
	return $count;
}


//Varones Mayores de 20 años
function varones_mayor_20($cuie,$fecha_desde,$fecha_hasta) {
	$sql_1 ="SELECT count (distinct (a.id_smiafiliados)) as cantidad
	--select *, extract ('year' from age (a.fecha_entrega, b.afifechanac))
	FROM programa_sexual.comprobantes a, nacer.smiafiliados b
	where a.id_smiafiliados = b.id_smiafiliados
	and a.cuie = '$cuie'
	and a.fecha_entrega between '$fecha_desde' and '$fecha_hasta'
	and a.id_smiafiliados is not null
	and trim(b.afisexo) = 'M'
	and extract ('year' from age (a.fecha_entrega, b.afifechanac)) >= 20";

	$res=sql($sql_1);
	$count = $res->fields['cantidad'];
	return $count;
}

//paises beneficiarios
function benef_paises($cuie,$fecha_desde,$fecha_hasta) {
	$sql_1 = "SELECT upper(c.pais_nac), count (upper(c.pais_nac))
	FROM programa_sexual.comprobantes a, nacer.smiafiliados b, uad.beneficiarios c
	where a.id_smiafiliados = b.id_smiafiliados
	and b.clavebeneficiario = c.clave_beneficiario
	and a.cuie = '$cuie'
	and a.fecha_entrega between '$fecha_desde' and '$fecha_hasta'
	and a.id_smiafiliados is not null
	group by upper(c.pais_nac)";

	$res=sql($sql_1);
	return $res;
}



//metodo anticonceptivo
function metodo_antic($cuie,$fecha_desde,$fecha_hasta) {
	$sql_1 = "SELECT c.codigo, c.descripcion, c.producto, c.tipo, sum(b.cantidad) as cantidad
	FROM programa_sexual.comprobantes a, programa_sexual.prestaciones b, programa_sexual.remedio c
	where a.id_comprobante = b.id_comprobante
	and b.id_remedio = c.id_remedio
	and a.cuie = '$cuie'
	and a.fecha_entrega between '$fecha_desde' and '$fecha_hasta'
	group by c.codigo, c.descripcion, c.producto, c.tipo
	order by 5 DESC	";

	$res=sql($sql_1);
	return $res;
}
?>
