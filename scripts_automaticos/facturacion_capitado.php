<?php 

include("funciones_generales.php");


function bisiesto_local($anio_actual){ 
    $bisiesto=false; 
    //probamos si el mes de febrero del año actual tiene 29 días 
      if (checkdate(2,29,$anio_actual)) 
      { 
        $bisiesto=true; 
    } 
    return $bisiesto; 
} 
function edad_con_meses($fecha_de_nacimiento){ 
	$fecha_actual = date ("Y-m-d"); 

	// separamos en partes las fechas 
	$array_nacimiento = explode ( "-", $fecha_de_nacimiento ); 
	$array_actual = explode ( "-", $fecha_actual ); 

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


//SETEAR facturacion capitada
$cuie='D05030';
$fecha_comprobante='2020-09-01';
$mes_fact_d_c='Septiembre 2020';

$fecha_factura=date("Y-m-d");
$fecha_carga=date("Y-m-d");
$periodo= str_replace("-","/",substr($fecha_comprobante,0,7));
$comentario='Carga pretacion capitada';
$usuario='Sebastian Lohaiza';
$tema=6866;
$patologia='A97';
$prestacion='PC'; //=>grupo en el nomenclador - Prestacion Capitada
$id_nomenclador=6866;
$precio=125;
$profesional="P99";
$id_nomenclador_detalle=17;
$observaciones='Factura con monto capitado';
$estado_envio='s'; //=> para no enviar por SIrge ni por sistema de padrones




$benef="SELECT * from nacer.smiafiliados where 
			activo='S' and 
			cuieefectorasignado='$cuie'";
//$res_benef=sql($benef);
$res_b=$db->Execute($benef) or die("Error Consulta de Comprobantes\n");

//creamos factura

$q="SELECT nextval('facturacion.factura_id_factura_seq') as id_factura";
$id_factura=$db->Execute($q) or die("Error al traer Id Factura\n");
$id_factura=$id_factura->fetchRow();
$id_factura=$id_factura['id_factura'];
$factura_online='NO';
			   
$query="INSERT into facturacion.factura
	(id_factura,cuie,fecha_carga,fecha_factura,periodo,estado,observaciones,online,periodo_actual,estado_envio,traba)
	values
	($id_factura,'$cuie','$fecha_carga','$fecha_factura','$periodo','A','$observaciones','$factura_online','$periodo','$estado_envio','1')";

$db->Execute($query) or die("Error al insertar la factura\n");

$log="INSERT into facturacion.log_factura
			(id_factura, fecha, tipo, descripcion, usuario) 
			values
			($id_factura, '$fecha_carga','ALTA','Factura para pago CAPITADO', '$usuario')";
$db->Execute($log) or die("Error entrada LOG de factu.\n");

//busqueda de Anexo

$query_a="SELECT id_anexo from facturacion.anexo 
				where prueba = 'No Corresponde' and id_nomenclador_detalle='$id_nomenclador_detalle'";
$query_anexo=$db->Execute($query_a) or die("Error Consulta de Anexo\n");
//$query_anexo=sql($query_anexo) or fin_pagina();
$query_anexo=$query_anexo->fetchRow();
$id_anexo=$query_anexo['id_anexo'];

while ($res_benef=$res_b->fetchRow()) {	//while (!$res_benef->EOF()) {

$clavebeneficiario=$res_benef['clavebeneficiario'];
$id_smiafiliados=$res_benef['id_smiafiliados'];
$sexo=trim($res_benef['afisexo']);
$grupopoblacional=$res_benef['grupopoblacional'];
$fecha_nacimiento=$res_benef['afifechanac'];

$q="SELECT nextval('comprobante_id_comprobante_seq') as id_comprobante";
$id_comprobante=$db->Execute($q) or die("Error ID Comprobante\n");
$id_comprobante=$id_comprobante->fetchRow();
$id_comprobante=$id_comprobante['id_comprobante'];

$query="INSERT into facturacion.comprobante
		             (id_comprobante, cuie,id_factura,fecha_comprobante, clavebeneficiario, id_smiafiliados, 
					 fecha_carga,periodo,comentario,id_servicio,activo,grupo_etareo)
		             values
		             ($id_comprobante,'$cuie',$id_factura,'$fecha_comprobante','$clavebeneficiario', $id_smiafiliados,
					 '$fecha_carga','$periodo','$comentario',1,'S','$grupopoblacional')";	
$db->Execute($query) or die ("Error al insertar el comprobante");	    


$log="INSERT into facturacion.log_comprobante 
	   (id_comprobante, fecha, tipo, descripcion, usuario) 
values ($id_comprobante, '$fecha_carga','Nuevo Comprobante - Pago Capitado','Nro. Comprobante $id_comprobante', '$usuario')";	

 		
$fecha_nacimiento_cod=str_replace('-','',$fecha_nacimiento);
$fecha_comprobante_cod=substr(str_replace('-','',$fecha_comprobante),0,8);

$res_dia_mes_anio=edad_con_meses($fecha_nacimiento,$fecha_comprobante);
$anios_desde_nac=$res_dia_mes_anio['anos'];
$meses_desde_nac=$res_dia_mes_anio['meses'];
$dias_desde_nac=$res_dia_mes_anio['dias'];

if (($sexo=='M')||($sexo=='Masculino')){
	     			$sexo_codigo='V';
	     			$sexo_1='Masculino';
	     			$sexo='M';
	     		}
if (($sexo=='F')||($sexo=='Femenino')){
	     			$sexo_codigo='M';
	     			$sexo_1='Femenino';
	     			$sexo='F';
	     		}			     		     		
		 		

$codigo=$cuie.$fecha_comprobante_cod.$clavebeneficiario.$fecha_nacimiento_cod.$sexo_codigo.$anios_desde_nac.$prestacion.$tema.$patologia.$profesional; 		




$q="SELECT nextval('facturacion.prestacion_id_prestacion_seq') as id_prestacion";
$id_prestacion=$db->Execute($q) or die("Error al buscar Id Prestacion\n");
$id_prestacion=$id_prestacion->fetchRow();
$id_prestacion=$id_prestacion['id_prestacion'];

$consulta= "INSERT into facturacion.prestacion
							(id_prestacion,id_comprobante,id_nomenclador,cantidad,precio_prestacion,id_anexo,
							diagnostico,edad,sexo,codigo_comp,fecha_nacimiento,fecha_prestacion,
							anio,mes,dia,estado_envio)
						values 
						    ('$id_prestacion','$id_comprobante','$id_nomenclador','1','$precio','$id_anexo',
						    '$patologia','$anios_desde_nac','$sexo_codigo','$codigo','$fecha_nacimiento','$fecha_comprobante',
						    '$anios_desde_nac','$meses_desde_nac','$dias_desde_nac','$estado_envio')";
$db->Execute($consulta) or die ("Error al insertar la prestacion\n");

}

$monto_pre="SELECT sum(precio_prestacion) as total from facturacion.prestacion
			inner join facturacion.comprobante using (id_comprobante)
			where id_factura=$id_factura";
$res_monto=$db->Execute($monto_pre) or die("Error al traer el monto facturado\n");
$monto_prefacturacion=$res_monto->fetchRow();
$monto_prefacturacion=$monto_prefacturacion['total'];

$update_fact="UPDATE facturacion.factura set 
			monto_prefactura=$monto_prefacturacion,
			estado='C', 
			estado_exp=1,
			mes_fact_d_c='$mes_fact_d_c'
			where id_factura=$id_factura";
$db->Execute($update_fact) or die ("Error al cargar el monto de facturacion\n");

$log="INSERT into facturacion.log_factura
	(id_factura, fecha, tipo, descripcion, usuario) 
	values 
	($id_factura, '$fecha_carga','Cerrar Factura','Cierra la Factura', '$usuario')";
	
$db->Execute($log) or die("Error al insertar el Log de Facturacion\n");


//codigo para la insercion de la factura al sistema de expediente


$q="SELECT nextval('expediente.expediente_id_expediente_seq') as id_expediente";
$id_expediente=$db->Execute($q) or die("Eror al traer el ID de Expekdientes\n");
$id_expediente=$id_expediente->fetchRow();
$id_expediente=$id_expediente['id_expediente'];

$q_trans="SELECT nextval('expediente.transaccion_id_transac_seq') as id_transac";
$id_transac=$db->Execute($q_trans) or die("Error al traer Id de Transacciones\n");
$id_transac=$id_transac->fetchRow();
$id_transac=$id_transac['id_transac'];

$id_area=3;
$estado='V';
$comentario="Expediente en fase de revision - Generado desde cierre de Factura";

$mes=date("m");
$anio=date("Y");

$dias=50;
$plazo_para_pago=date("Y-m-d", strtotime ("$fecha_carga +$dias days"));


$sql_efector="SELECT id_efe_conv,cuie from nacer.efe_conv where cuie='$cuie'";
$res_efector=$db->Execute($sql_efector) or die ("no se pudo traer los datos del efector");
$res_efector=$res_efector->fetchRow();
$id_efector_real=$res_efector['id_efe_conv'];
 
$periodo=explode ("/",$periodo);


$nro_exp= "$periodo[1]$periodo[0]$id_expediente$cuie";

$calend[1]="Enero";
$calend[2]="Febrero";
$calend[3]="Marzo";
$calend[4]="Abril";
$calend[5]="Mayo";
$calend[6]="Junio";
$calend[7]="Julio";
$calend[8]="Agosto";
$calend[9]="Septiembre";
$calend[10]="Octubre";
$calend[11]="Noviembre";
$calend[12]="Diciembre";

$mes=(int)$periodo[1];
$fecha_coment=$calend[$mes]." ".$periodo[0];

$query="INSERT into expediente.expediente
		   (id_expediente,
			  id_efe_conv,
			  fecha_ing,
			  monto,
			  nro_exp,
			  plazo_para_pago,
			  comentario1,
			  id_factura,
			  periodo,
			  estado)
		 values
		  ('$id_expediente',
			  '$id_efector_real',
			  '$fecha_carga',
			  $monto_prefacturacion,
			  '$nro_exp',
			  '$plazo_para_pago',
			  '$comentario',
			  '$id_factura',
			  '$fecha_coment',
			  '$estado')";


$query_trans="INSERT into expediente.transaccion
		   (id_transac,
			  id_expediente,
			   id_area,
			  fecha_mov,
			  estado,
			  comentario,
			  total_pagar,
			  id_factura,
			  usuario)
	  values
		  ('$id_transac',
			  '$id_expediente',
			  '$id_area',
			  '$fecha_carga',
			  '$estado',
			  '$comentario',
			  $monto_prefacturacion,
			  '$id_factura',
			  '$usuario')";


$db->Execute($query) or die("Error al insertar el Expediente");
$db->Execute($query_trans) or die("Error al insertar la transaccion");    

$update_fact="UPDATE facturacion.factura set 
			nro_exp='$nro_exp'
			where id_factura=$id_factura";
$db->Execute($update_fact) or die ("Error al cargar el monto de facturacion\n");

echo "Se guardo la Factura Numero: ".$id_factura."\n";