<?
require_once ("../../config.php");
include_once("./funciones.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
$usuario1=$_ses_user['id'];

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

function edad_con_meses_sin_fecha_actual($fecha_de_nacimiento,$fecha_actual){ 
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

if ($_POST['nomenclador_detalle']){
	$query="UPDATE nacer.efe_conv
			set id_nomenclador_detalle='$nomenclador_detalle'
			where cuie='$cuie'";	
	sql($query, "Error al insertar la prestacion") or fin_pagina();
}

if ($_POST['guardar']=="Guardar Prestacion"){
		$fecha_carga=date("Y-m-d H:i:s");
		
		$query_precio="SELECT id_nomenclador, precio from facturacion.nomenclador 
	 						where id_nomenclador = $tema and id_nomenclador_detalle=$id_nomenclador_detalle";
	 	$query_precio=sql($query_precio) or fin_pagina();
	 	$precio=$query_precio->fields['precio'];
	 	$id_nomenclador=$query_precio->fields['id_nomenclador'];
	 	$descripcion_nomenclador=$query_precio->fields['descripcion'];
	 	$grupo_ceroacinco=$query_precio->fields['ceroacinco'];
	 	$grupo_seisanueve=$query_precio->fields['seisanueve'];
	 	$grupo_adol=$query_precio->fields['adol'];
	 	$grupo_adulto=$query_precio->fields['adulto'];

		
		if (($pagina_viene=='comprobante_admin_total.php')||(valida_prestacion_nuevo_nomenclador($id_comprobante,$id_nomenclador,$accion))){
 		
	 		$db->StartTrans();
	 		
	 		$profesional="P99";
	 		
	 		$fecha_nacimiento_cod=str_replace('-','',$fecha_nacimiento);
	 		$fecha_comprobante_cod=substr(str_replace('-','',$fecha_comprobante),0,8);
	 		
	 		$codigo=$cuie.$fecha_comprobante_cod.$clave_beneficiario.$fecha_nacimiento_cod.$sexo_codigo.$edad.$prestacion.$tema.$patologia.$profesional; 		
	 		
	 		$res_dia_mes_anio=dia_mes_anio($fecha_nacimiento,$fecha_comprobante);
	 		$anios_desde_nac=$res_dia_mes_anio['anios'];
	 		$meses_desde_nac=$res_dia_mes_anio['meses'];
	 		$dias_desde_nac=$res_dia_mes_anio['dias'];

	 		$dias_de_vida=GetCountDaysBetweenTwoDates($fecha_nacimiento, $fecha_comprobante);
				if (($dias_de_vida>=0)&&($dias_de_vida<=28)) $grupo_etareo='Neonato';
				if (($dias_de_vida>28)&&($dias_de_vida<=2190)) $grupo_etareo='Cero a Cinco Años';
				if (($dias_de_vida>2190)&&($dias_de_vida<=3650)) $grupo_etareo='Seis a Nueve Años';
				if (($dias_de_vida>3650)&&($dias_de_vida<=7300)) $grupo_etareo='Adolecente';
				if (($dias_de_vida>7300)&&($dias_de_vida<=23725)) $grupo_etareo='Adulto';	
				if ($dias_de_vida>23725) $grupo_etareo='Mayor de 65 años';
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
						 		
	 		$query_anexo="SELECT id_anexo from facturacion.anexo 
	 						where prueba = 'No Corresponde' and id_nomenclador_detalle='$id_nomenclador_detalle'";
			$query_anexo=sql($query_anexo) or fin_pagina();
			$id_anexo=$query_anexo->fields['id_anexo'];
	 	
	 		$q="SELECT nextval('facturacion.prestacion_id_prestacion_seq') as id_prestacion";
		  $id_prestacion=sql($q) or fin_pagina();
		  $id_prestacion=$id_prestacion->fields['id_prestacion'];
			    
			//enriquesimientos para tabla facturacion.prestacion para datos reportables
			//Prestaciones desde ambos nomencladores SUMAR2019 y PACES  
			if ($descripcion_nomenclador=='Control prenatal  de 1ra.vez  (Grupo Embarazo/parto/puerperio de Bajo Riesgo)' 
				or
				$descripcion_nomenclador=='Control prenatal  de 1ra.vez (< a 13 semanas de Edad Gestacional)'
				or 
				$descripcion_nomenclador=='Ulterior de control  prenatal.   (Grupo Embarazo/parto/puerperio de Bajo Riesgo)'
				or 
				$descripcion_nomenclador=='Control  prenatal. '
				or
				$descripcion_nomenclador=='Consulta inicial de diabetes gestacional' 
				or
				$descripcion_nomenclador=='Diabetes gestacional: Consulta de seguimiento '
				or
				$descripcion_nomenclador=='Consulta de seguimiento de diabetes gestacional' 
				or
				$descripcion_nomenclador=='Consulta inicial de hipertensión gestacional' 
				or
				$descripcion_nomenclador=='Embarazada con hipertensión arterial: Consulta de seguimiento '
				or 
				$descripcion_nomenclador=='Consulta de seguimiento de la hipertensión gestacional'

				or 
				$descripcion_nomenclador=='Control de embarazo < a 13 semanas'
			)			
			{
				$sql_datos="SELECT * from trazadorassps.trazadora_1
							where id_smiafiliados=$id_smiafiliados and fecha_carga='$fecha_carga'
							order by 1 DESC";
				$res_datos=sql($sql_datos,"No se pudieron traer los datos reportables") or fin_pagina();
				$hay_datos=1;
				
				if ($res_datos->RecordCount()==0){
					$sql_datos="SELECT * from trazadorassps.trazadora_2
							where id_smiafiliados=$id_smiafiliados and fecha_carga='$fecha_carga'
							order by 1 DESC";
					$res_datos=sql($sql_datos,"No se pudieron traer los datos reportables") or fin_pagina();
					$hay_datos=1;
					};
			}
		
			elseif ($descripcion_nomenclador=='Pediátrica en menores de 1 año (Grupo Niño 0 a 5 años  Cuidado de la Salud)' 
				or 
				$descripcion_nomenclador=='Examen periódico de salud de niño menor de 1 año'
				or 
				($descripcion_nomenclador=='Examen periódico de salud' and $grupo_ceroacinco=='1')
			){
				$sql_datos="SELECT * from trazadorassps.trazadora_4
							where id_smiafiliados=$id_smiafiliados and fecha_carga='$fecha_carga'
							order by 1 DESC";
					$res_datos=sql($sql_datos,"No se pudieron traer los datos reportables") or fin_pagina();
					$hay_datos=1;
			}
			
			elseif ($descripcion_nomenclador=='Pediátrica de 1 a 5 años (Grupo Niño 0 a 5 años  Cuidado de la Salud)'
				or $descripcion_nomenclador=='Examen periódico de salud de niño de 1 a 5 años' 
				or $descripcion_nomenclador=='Control de salud individual para población indígena en terreno (Grupo Niños de 6 a 9 Años)' 
				or $descripcion_nomenclador=='Control en Niños de 6 a 9 años (Grupo Niños de 6 a 9 Años)'
				or $descripcion_nomenclador=='Examen periódico de salud del niño de 6 a 9 años'
				or 
				($descripcion_nomenclador=='Examen periódico de salud' and $grupo_seisanueve=='1')
				)
				{
				$sql_datos="SELECT * from trazadorassps.trazadora_7
							where id_smiafiliados=$id_smiafiliados and fecha_carga='$fecha_carga'
							order by 1 DESC";
					$res_datos=sql($sql_datos,"No se pudieron traer los datos reportables") or fin_pagina();
					$hay_datos=1;
				}
			
			elseif ($descripcion_nomenclador=='Examen Periódico de Salud del adolescente (Grupo Adolecentes 10 a 19 años)' 
				or $descripcion_nomenclador=='Control de salud individual para población indígena en terreno (Grupo Adolecentes 10 a 19 años)'
				or $descripcion_nomenclador=='Examen Periódico de Salud del adolescente'
				or 
				($descripcion_nomenclador=='Examen periódico de salud' and $grupo_adol='1')
				)
				{
				$sql_datos="SELECT * from trazadorassps.trazadora_10
							where id_smiafiliados=$id_smiafiliados and fecha_carga='$fecha_carga'
							order by 1 DESC";
					$res_datos=sql($sql_datos,"No se pudieron traer los datos reportables") or fin_pagina();
					$hay_datos=1;
				}

			elseif ($descripcion_nomenclador=='Examen periódico de salud' and $grupo_adulto='1')
				
				{
				$sql_datos="SELECT * from trazadoras.adultos
							where round(num_doc)=$afidni and fecha_control='$fecha_carga'
							order by 1 DESC";
					$res_datos=sql($sql_datos,"No se pudieron traer los datos reportables") or fin_pagina();
					$hay_datos=1;
				}
			
			elseif ($descripcion_nomenclador=='Diagnóstico por biopsia en laboratorio de anatomía patológica, para aquellas mujeres con citología ASC-H, H-SIL,Cáncer (CA cervicouterino) (Grupo Adultos 20 a 64 Años)' 
				or $descripcion_nomenclador=='Anatomía patológica de biopsia en mujeres (CA mama)  (Grupo Adultos 20 a 64 Años)'
				or $descripcion_nomenclador=='Informe de biopsia de lesión de mama ')
				{
				$sql_datos="SELECT * from trazadorassps.trazadora_12
							where id_smiafiliados=$id_smiafiliados and fecha_carga='$fecha_carga'
							order by 1 DESC";
					$res_datos=sql($sql_datos,"No se pudieron traer los datos reportables") or fin_pagina();
					$hay_datos=1;
				}
			
			else $hay_datos=0;
		
		if ($hay_datos) {
		
		$peso=($res_datos->fields['peso'])?$res_datos->fields['peso']:0;
		$talla=($res_datos->fields['talla'])?$res_datos->fields['talla']:0;
		$eg=($res_datos->fields['edad_gestacional'])?$res_datos->fields['edad_gestacional']:0;
		$ta=($res_datos->fields['tension_arterial'])?$res_datos->fields['tension_arterial']:0;
		$perimetro_cefalico=($res_datos->fields['perimetro_cefalico'])?$res_datos->fields['perimetro_cefalico']:0;
		$inf_anat_patologica=($res_datos->fields['inf_anat_patologica'])?$res_datos->fields['inf_anat_patologica']:0;
		$inf_diag_biopsia=($res_datos->fields['inf_diag_biopsia'])?$res_datos->fields['inf_diag_biopsia']:0;
		}
		else {
		$peso=0;
		$talla=0;
		$eg=0;
		$ta=null;
		$perimetro_cefalico=0;
		$inf_anat_patologica=0;
		$inf_diag_biopsia=0;  
			
		} 
			
		$res_vdrl=$_POST['res_vdrl'];
		$res_est_oido_derecho=($_POST['est_oido_derecho'])?$_POST['est_oido_derecho']:0;
		$res_est_oido_izquierdo=($_POST['est_oido_izquierdo'])?$_POST['est_oido_izquierdo']:0;
		$retinopatia=($_POST['retinopatia'])?$_POST['retinopatia']:0;
		$inf_anat_patologica=($_POST['diagnostico_pat_2'])?$_POST['diagnostico_pat_2']:0;
		$inf_diag_biopsia=($_POST['diagnostico_pat_1'])?$_POST['diagnostico_pat_1']:0;
		$inf_diag_anatomo=($_POST['diagnostico_pat_3'])?$_POST['diagnostico_pat_3']:0;
		$tratamiento_instaurado=($_POST['tratamiento_instaurado'])?$_POST['tratamiento_instaurado']:0;
		
		//2023
		$birads = ($_POST['birads'])?$_POST['birads']:-1;
		$tisomf = ($_POST['tisomf'])?$_POST['tisomf']:null;
		$hemo_glic = ($_POST['hemo_glic'])?$_POST['hemo_glic']:-1;
		$vph = ($_POST['vph'])?$_POST['vph']:null;
		$tratamiento_instaurado_de_cm=($_POST['tratamiento_instaurado_de_cm'])?$_POST['tratamiento_instaurado_de_cm']:-1;
		$ta_d = ($_POST['ta_d'])?$_POST['ta_d']:null;
		$ta_s = ($_POST['ta_s'])?$_POST['ta_s']:null;
		$ta = $ta_d.'/'.$ta_s;  
		$r031_semges = ($_POST['r031_semges'])?$_POST['r031_semges']:-1;
		$financiador = ($_POST['financiador'])?$_POST['financiador']:-1;
		$porc_geo = ($_POST['porc_geo'])?$_POST['porc_geo']:-1;
		$porc_dbt = ($_POST['porc_dbt'])?$_POST['porc_dbt']:-1;
		$porc_hta = ($_POST['porc_hta'])?$_POST['porc_hta']:-1;

		$caries1=($_POST['caries_cpod']<10)?'0'.$_POST['caries_cpod']:$_POST['caries_cpod'];
		$perdidos1=($_POST['perdidos_cpod']<10)?'0'.$_POST['perdidos_cpod']:$_POST['perdidos_cpod'];
		$obturados1=($_POST['obturados_cpod']<10)?'0'.$_POST['obturados_cpod']:$_POST['obturados_cpod'];

		$cpod="C:".$caries1."/P:".$perdidos1."/O:".$obturados1;

		$caries2=($_POST['caries_ceod']<10)?'0'.$_POST['caries_ceod']:$_POST['caries_ceod'];
		$perdidos2=($_POST['extracciones_ceod']<10)?'0'.$_POST['extracciones_ceod']:$_POST['extracciones_ceod'];
		$obturados2=($_POST['obturados_ceod']<10)?'0'.$_POST['obturados_ceod']:$_POST['obturados_ceod'];
		

		$ceod="c:".$caries2."/e:".$perdidos2."/o:".$obturados2;

		
		/*$consulta= "INSERT into facturacion.prestacion
								(id_prestacion,id_comprobante,id_nomenclador,cantidad,precio_prestacion,id_anexo,
								peso,tension_arterial,diagnostico,edad,sexo,codigo_comp,fecha_nacimiento,fecha_prestacion,
								anio,mes,dia,estado_envio,
				edad_gestacional,talla,perim_cefalico,inf_anat_patologica,inf_diag_biopsia,
				inf_vdrl,res_oido_derecho,res_oido_izquierdo,retinopatia,inf_diag_anatomo,tratamiento_instaurado,cpod,ceod,
				birads,tisomf,hemo_glic,vph,tratamiento_instaurado_de_cm)
							values 
								('$id_prestacion','$id_comprobante','$id_nomenclador','1','$precio','$id_anexo',
								$peso,'$ta','$patologia','$edad','$sexo_codigo','$codigo','$fecha_nacimiento','$fecha_comprobante',
								'$anios_desde_nac','$meses_desde_nac','$dias_desde_nac','n',
					'$eg','$talla',$perimetro_cefalico,$inf_anat_patologica,$inf_diag_biopsia,
					'$res_vdrl','$res_est_oido_derecho','$res_est_oido_izquierdo','$retinopatia',$inf_diag_anatomo,$tratamiento_instaurado,'$cpod','$ceod',
					$birads,'$tisomf',$hemo_glic,'$vph',$tratamiento_instaurado_de_cm)";
				*/
			$consulta= "INSERT into facturacion.prestacion
				(id_prestacion,id_comprobante,id_nomenclador,cantidad,precio_prestacion,id_anexo,
				peso,tension_arterial,diagnostico,edad,sexo,codigo_comp,fecha_nacimiento,fecha_prestacion,
				anio,mes,dia,estado_envio,
				edad_gestacional,talla,perim_cefalico,inf_anat_patologica,inf_diag_biopsia,
				inf_vdrl,res_oido_derecho,res_oido_izquierdo,retinopatia,inf_diag_anatomo,tratamiento_instaurado,cpod,ceod,
				birads,tisomf,hemo_glic,vph,tratamiento_instaurado_de_cm,financiador,porc_geo,porc_dbt,porc_hta)
				values 
				('$id_prestacion','$id_comprobante','$id_nomenclador','1','$precio','$id_anexo',
				$peso,'$ta','$patologia','$edad','$sexo_codigo','$codigo','$fecha_nacimiento','$fecha_comprobante',
				'$anios_desde_nac','$meses_desde_nac','$dias_desde_nac','n',
				$r031_semges,'$talla',$perimetro_cefalico,$inf_anat_patologica,$inf_diag_biopsia,
				'$res_vdrl','$res_est_oido_derecho','$res_est_oido_izquierdo','$retinopatia',$inf_diag_anatomo,$tratamiento_instaurado,'$cpod','$ceod',
				$birads,'$tisomf',$hemo_glic,'$vph',$tratamiento_instaurado_de_cm,$financiador,$porc_geo,$porc_dbt,$porc_hta)";
			
				sql($consulta) or fin_pagina();
							
				$db->CompleteTrans();   
				$accion="Se Grabo la Prestacion.";
 		}
		else{
			$accion="Comprobante Duplicado";
		}			
   }

$query="SELECT 
  facturacion.comprobante.id_comprobante,
  facturacion.comprobante.flap,
  nacer.efe_conv.nombre,
  nacer.efe_conv.cuie,
  facturacion.comprobante.nombre_medico,
  facturacion.comprobante.fecha_comprobante,
  facturacion.comprobante.alta_comp,
  facturacion.comprobante.covid
FROM
  facturacion.comprobante
  INNER JOIN nacer.efe_conv ON (facturacion.comprobante.cuie = nacer.efe_conv.cuie)
  where id_comprobante=$id_comprobante";
$res_comprobante=sql($query, "Error al traer el Comprobantes") or fin_pagina();
$nombre=$res_comprobante->fields['nombre'];
$cuie=$res_comprobante->fields['cuie'];
$nombre_medico=$res_comprobante->fields['nombre_medico'];
$fecha_comprobante=$res_comprobante->fields['fecha_comprobante'];
$flap=$res_comprobante->fields['flap'];
$covid=$res_comprobante->fields['covid'];

/*$sql=" SELECT  *
FROM
  nacer.efe_conv
  left join facturacion.nomenclador_detalle using (id_nomenclador_detalle)
  where cuie='$cuie'";*/

if ($res_comprobante->fields['alta_comp']=='SI' or $res_comprobante->fields['covid']=='s') 
$sql="SELECT * FROM facturacion.nomenclador_detalle where modo_facturacion='3' and '$fecha_comprobante' between fecha_desde and fecha_hasta";

else $sql="SELECT * FROM facturacion.nomenclador_detalle where modo_facturacion='4' and '$fecha_comprobante' between fecha_desde and fecha_hasta";

$res_nom=sql($sql, "Error al traer el nomenclador detalle") or fin_pagina();
$descripcion=$res_nom->fields['descripcion'];
$id_nomenclador_detalle=$res_nom->fields['id_nomenclador_detalle'];

if ($nomenclador<>"") {
  		$sql= "select * from facturacion.nomenclador 
				where id_nomenclador='$nomenclador'";
     	$res_codigo=sql($sql) or fin_pagina();
     	$res_codigo=$res_codigo->fields['codigo'];     
}
if ($pagina_viene=='comprobante_admin.php'){
	$query_b="SELECT nacer.smiafiliados.*,smitiposcategorias.*
		   FROM nacer.smiafiliados
	 	   left join nacer.smitiposcategorias on (afitipocategoria=codcategoria)
		   left join facturacion.comprobante using (id_smiafiliados)
	  	   where comprobante.id_comprobante=$id_comprobante";
	$res_comprobante_b=sql($query_b, "Error al traer el Comprobantes") or fin_pagina();
	
	$afiapellido=$res_comprobante_b->fields['afiapellido'];
	$afinombre=$res_comprobante_b->fields['afinombre'];
	$afidni=$res_comprobante_b->fields['afidni'];
	$descripcion_b=$res_comprobante_b->fields['descripcion'];
	$codcategoria=$res_comprobante_b->fields['codcategoria'];
	$fecha_nacimiento=$res_comprobante_b->fields['afifechanac'];
	$activo=$res_comprobante_b->fields['activo'];
	$sexo=trim($res_comprobante_b->fields['afisexo']);
	$clave_beneficiario=trim($res_comprobante_b->fields['clavebeneficiario']);
	$entidad_alta="na";
}

if ($pagina_viene=='comprobante_admin_total.php'){
	$sql="select 
			  leche.beneficiarios.id_beneficiarios as id,
			  leche.beneficiarios.apellido as a,
			  leche.beneficiarios.nombre as b,
			  leche.beneficiarios.documento as c,
			  leche.beneficiarios.fecha_nac as d,
			  leche.beneficiarios.domicilio as e,
			  leche.beneficiarios.sexo as f
		 from leche.beneficiarios	 
		 where id_beneficiarios=$id";
	$res_comprobante=sql($sql, "Error al traer los Comprobantes") or fin_pagina();
	
	
	$afiapellido=$res_comprobante->fields['a'];
	$afinombre=$res_comprobante->fields['b'];
	$afidni=$res_comprobante->fields['c'];
	$fecha_nacimiento=$res_comprobante->fields['d'];
	$sexo=trim($res_comprobante->fields['f']);
	$entidad_alta="nu";
}

// Cálculos de edad y grupo etario
$edad_con_meses = edad_con_meses($fecha_nacimiento);
$anio_edad = $edad_con_meses["anos"];
$meses_edad = $edad_con_meses["meses"];
$dias_edad = $edad_con_meses["dias"];

$codigo_edad_arr = edad_con_meses_sin_fecha_actual($fecha_nacimiento, $fecha_comprobante);
$codigo_edad = $codigo_edad_arr["anos"];
if (strlen($codigo_edad) == '1') {
    $codigo_edad = '0' . $codigo_edad;
}

$dias_de_vida = GetCountDaysBetweenTwoDates($fecha_nacimiento, $fecha_comprobante);
if (($dias_de_vida >= 0) && ($dias_de_vida <= 28)) $grupo_etareo = 'Neonato';
if (($dias_de_vida > 28) && ($dias_de_vida <= 2190)) $grupo_etareo = 'Cero a Cinco Años';
if (($dias_de_vida > 2190) && ($dias_de_vida <= 3650)) $grupo_etareo = 'Seis a Nueve Años';
if (($dias_de_vida > 3650) && ($dias_de_vida <= 7300)) $grupo_etareo = 'Adolecente';
if (($dias_de_vida > 7300) && ($dias_de_vida <= 23725)) $grupo_etareo = 'Adulto';
if ($dias_de_vida > 23725) $grupo_etareo = 'Mayores de 65 años';

if (($sexo == 'M') || ($sexo == 'Masculino')) {
    $sexo_codigo = 'V';
    $sexo_1 = 'Masculino';
    $sexo = 'M';
}
if (($sexo == 'F') || ($sexo == 'Femenino')) {
    $sexo_codigo = 'M';
    $sexo_1 = 'Femenino';
    $sexo = 'F';
}

echo $html_header;
?>
<style>
/* Estilos modernos y de alta ergonomía para prestacion_admin_nop.php (San Luis) */
.paf-wrapper {
    max-width: 1260px;
    margin: 15px auto 40px auto;
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
    color: #1e293b;
}
.paf-main-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    color: #ffffff;
    border-radius: 12px 12px 0 0;
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    flex-wrap: wrap;
    gap: 12px;
}
.paf-main-title {
    font-size: 19px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}
.paf-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    overflow: hidden;
}
.paf-card-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 10px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    font-size: 13.5px;
    color: #1e293b;
}
.paf-card-body {
    padding: 14px 18px;
}

/* Ficha del Beneficiario y Práctica en un solo renglón compacto */
.paf-patient-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 16px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.paf-bar-item {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
}
.paf-bar-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
}
.paf-bar-val {
    font-weight: 600;
    color: #1e293b;
}

/* Badges */
.paf-badge-dni {
    font-family: 'Consolas', monospace;
    background: #e0e7ff;
    color: #3730a3;
    padding: 1px 6px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 700;
}
.paf-badge-group-etario {
    display: inline-block;
    padding: 1px 8px;
    border-radius: 12px;
    font-size: 11.5px;
    font-weight: 700;
}
.paf-etario-neonato { background: #fee2e2; color: #991b1b; }
.paf-etario-ceroacinco { background: #fef3c7; color: #92400e; }
.paf-etario-seisanueve { background: #e0f2fe; color: #0369a1; }
.paf-etario-adolecente { background: #f3e8ff; color: #6b21a8; }
.paf-etario-adulto { background: #dcfce7; color: #15803d; }
.paf-etario-mayores { background: #f1f5f9; color: #0f172a; border: 1px solid #cbd5e1; }

/* Controles de Formulario */
.paf-form-group {
    margin-bottom: 12px;
}
.paf-form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 4px;
}
.paf-form-control {
    width: 100%;
    padding: 6px 10px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 12px;
    color: #1e293b;
    background-color: #ffffff;
    outline: none;
    transition: all 0.15s ease;
    box-sizing: border-box;
}
.paf-form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
.paf-form-control option, .paf-form-control optgroup {
    font-size: 12px;
}

/* Alertas de Notificación */
.paf-alert {
    border-radius: 8px;
    padding: 14px 18px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
}
.paf-alert-success {
    background-color: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}
.paf-alert-warning {
    background-color: #fffbeb;
    border: 1px solid #fde68a;
    color: #92400e;
}
.paf-alert-danger {
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}

/* Paleta de colores de referencia */
.paf-palette-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 5px;
}
.paf-palette-item {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 3px 6px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    color: #334155;
}
.paf-color-dot {
    width: 12px;
    height: 12px;
    border-radius: 3px;
    border: 1px solid rgba(0,0,0,0.15);
    flex-shrink: 0;
}

/* Barra de acciones */
.paf-action-bar {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    border-radius: 0 0 8px 8px;
}
.paf-btn-save {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 28px;
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(22,163,74,0.3);
    transition: all 0.15s ease;
}
.paf-btn-save:hover {
    background: linear-gradient(135deg, #15803d 0%, #166534 100%);
    box-shadow: 0 4px 6px rgba(22,163,74,0.4);
    transform: translateY(-1px);
}
.paf-btn-trazadora {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #c026d3 0%, #a21caf 100%);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(192,38,211,0.3);
    transition: all 0.15s ease;
}
.paf-btn-trazadora:hover {
    background: linear-gradient(135deg, #a21caf 0%, #86198f 100%);
    transform: translateY(-1px);
}
.paf-btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 16px;
    background: #ffffff;
    color: #334155;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s ease;
}
.paf-btn-back:hover {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #94a3b8;
}
.paf-code-pill {
    display: inline-block;
    padding: 2px 8px;
    background-color: #f1f5f9;
    border-radius: 4px;
    font-weight: 700;
    font-family: 'Consolas', monospace;
    font-size: 12px;
    color: #0f172a;
    border: 1px solid #cbd5e1;
}
.paf-grid-2col {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.paf-col-left {
    flex: 1 1 58%;
    min-width: 320px;
}
.paf-col-right {
    flex: 1 1 38%;
    min-width: 280px;
}
.paf-clinical-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
}
</style>

<script>
// Manejo de scroll para mantener la posición en pantalla durante recargas
function guardar_scroll() {
    try {
        sessionStorage.setItem('pan_scroll_y', window.scrollY || window.pageYOffset || 0);
    } catch(e) {}
}

window.addEventListener('load', function() {
    try {
        var y = sessionStorage.getItem('pan_scroll_y');
        if (y !== null) {
            window.scrollTo(0, parseInt(y));
            sessionStorage.removeItem('pan_scroll_y');
        }
    } catch(e) {}
});

function getVal(name) {
    var f = document.form1 || document.forms[0];
    if (f && f[name]) {
        return f[name].value;
    }
    if (document.all && document.all[name]) {
        return document.all[name].value;
    }
    var el = document.getElementById(name);
    if (el) return el.value;
    return '';
}

function control_nuevos() {
    var prestacion = getVal('prestacion');
    if (prestacion == "-1" || prestacion == "") {
        alert('Debe Seleccionar una Prestacion');
        return false;
    }
    
    var tema = getVal('tema');
    if (tema == "-1" || tema == "") {
        alert('Debe Seleccionar un Objeto de la Prestacion');
        return false;
    }
    
    var patologia = getVal('patologia');
    if (patologia == "-1" || patologia == "") {
        alert('Debe Seleccionar un Diagnostico');
        return false;
    }
  
    // Informe sanitario de población a cargo - ISI002
    if (tema == "11904" || tema == "12542") {
        var porc_geo = parseFloat(getVal('porc_geo'));
        if (isNaN(porc_geo) || porc_geo < 0 || porc_geo > 100) {
            alert('Debe introducir un valor para el porcentaje (%) de personas a cargo georreferenciadas, rango 0-100');
            return false;
        }
        var porc_dbt = parseFloat(getVal('porc_dbt'));
        if (isNaN(porc_dbt) || porc_dbt < 0 || porc_dbt > 100) {
            alert('Debe introducir un valor para el porcentaje (%) de personas con DBT 2 con una hemoglobina glicosilada, rango 0-100');
            return false;
        }
        var porc_hta = parseFloat(getVal('porc_hta'));
        if (isNaN(porc_hta) || porc_hta < 0 || porc_hta > 100) {
            alert('Debe introducir un valor para el porcentaje (%) de personas con HTA con al menos un control por HTA, rango 0-100');
            return false;
        }
    }
  
    // Dispensa de medicamentos en efector - PRP053
    if (tema == "12226" || tema == "12864") {
        if (getVal('financiador') == "-1") {
            alert('Debe introducir el financiador del medicamento dispensado');
            return false;
        }
    }

    // Mamografía (50 a 69 años, cada 2 años con mamografía negativa) - IGR014
    if (tema == "11867" || tema == "12505") {
        var birads = parseFloat(getVal('birads'));
        if (isNaN(birads) || birads < 0 || birads > 6) {
            alert('Debe introducir un valor para resultado de BIRADS, rango 0-6');
            return false;
        }
    } 
  
    // Lectura de muestra de VPH (30 a 69 años) - APA004
    if (tema == "11865" || tema == "12503") {
        if (getVal('vph') == "-1") {
            alert('Debe introducir un valor para resultado de VPH');
            return false;
        }
    } 
  
    // Consulta de detección y/o seguimiento de HTA - CTC074
    if (tema == "11790" || tema == "12428") {
        var ta_d = parseFloat(getVal('ta_d'));
        if (getVal('ta_d') == "" || isNaN(ta_d) || ta_d < 50 || ta_d > 300) {
            alert('Debe introducir un valor entero valido para la tension sistólica, rango 50-300');
            return false;
        }
        var ta_s = parseFloat(getVal('ta_s'));
        if (getVal('ta_s') == "" || isNaN(ta_s) || ta_s < 40 || ta_s > 300) {
            alert('Debe introducir un valor para la tension diastólica');
            return false;
        }
    } 

    // Hemoglobina glicosilada - LBL056
    if (tema == "12050" || tema == "12688") {
        var hemo_glic = parseFloat(getVal('hemo_glic'));
        if (getVal('hemo_glic') == "" || isNaN(hemo_glic) || hemo_glic < 3 || hemo_glic > 20) {
            alert('Debe introducir un valor real valido del resultado de Hemoglobina glicosilada, rango 3-20');
            return false;
        }
    } 
  
    // Detección temprana de hipoacusia en RN (Otoemisiones acústicas) - PRP021
    if (tema == "11832" || tema == "12470") {
        if (getVal('est_oido_derecho') == "") {
            alert('Debe Seleccionar un Resultado del estudio de Oido Derecho');
            return false;
        }
        if (getVal('est_oido_izquierdo') == "") {
            alert('Debe Seleccionar un Resultado del estudio de Oido Izquierdo');
            return false;
        }
    }
  
    // VDRL - LBL119
    if (tema == "12400" || tema == "13038") {
        if (getVal('res_vdrl') == "-1" || getVal('res_vdrl') == "") {
            alert('Debe Seleccionar un Resultado de Laboratorio para la prestacíon de VDRL');
            return false;
        }
    }

    // Test inmunoquímico de sangre oculta en materia fecal - TiSOMF - LBL098
    if (tema == "11866" || tema == "12504") {
        if (getVal('tisomf') == "-1" || getVal('tisomf') == "") {
            alert('Debe Seleccionar un Resultado de TiSOMF');
            return false;
        }
    }

    // Ecografía obstétrica - IGR031W78
    if (tema == "11881" || tema == "12519") {
        var r031_semges = parseFloat(getVal('r031_semges'));
        if (getVal('r031_semges') == "" || isNaN(r031_semges) || r031_semges < 4 || r031_semges > 44) {
            alert('Debe Seleccionar un Valor para la semana de gestacion, rango 4-43 (puede ir decimal con rango 0-6');
            return false;
        }
    }

    // Oftalmoscopía binocular indirecta (OBI) - PRP017
    if (tema == "12413" || tema == "13051") {
        if (getVal('retinopatia') == "-1") {
            alert('Debe Seleccionar un Grado de Retinopatía');
            return false;
        }
    }

    // Diagnostico biopsia - PRP017
    if (tema == "12172" || tema == "12810") {
        if (getVal('diagnostico_pat_1') == "-1") {
            alert('Debe Seleccionar el informe de transcripcion del resultado');
            return false;
        }
    }

    // Informe de biopsia de lesión de mama - APA002
    if (tema == "12213" || tema == "12851") {
        if (getVal('diagnostico_pat_2') == "-1") {
            alert('Debe Seleccionar el informe de transcripcion del resultado');
            return false;
        }
    }

    // Cáncer cérvicouterino - NTN003
    if (tema == "12198" || tema == "12836") {
        if (getVal('tratamiento_instaurado') == "-1") {
            alert('Debe Seleccionar el tratamiento Instaurado');
            return false;
        }
    }

    // Notificación de inicio de tratamiento de cáncer de mama - NTN002
    if (tema == "12214" || tema == "12852") {
        if (getVal('tratamiento_instaurado_de_cm') == "-1") {
            alert('Debe Seleccionar el tratamiento Instaurado');
            return false;
        }
    }

    var edad_anios = parseInt(getVal('edad_anios') || '0', 10);

    // Consultas Odontologicas CPOD (si los campos están presentes en pantalla)
    if (document.getElementById('caries_cpod')) {
        var caries_cpod = parseFloat(getVal('caries_cpod'));
        if (getVal('caries_cpod') == "" || isNaN(caries_cpod) || caries_cpod < 0 || caries_cpod > 32) {
            alert('El Número de Caries debe ser un Número Entero de 0 a 32');
            return false;
        }
        var perdidos_cpod = parseFloat(getVal('perdidos_cpod'));
        if (getVal('perdidos_cpod') == "" || isNaN(perdidos_cpod) || perdidos_cpod < 0 || perdidos_cpod > 32) {
            alert('El Número de Perdidos debe ser un Número Entero de 0 a 32');
            return false;
        }
        var obturados_cpod = parseFloat(getVal('obturados_cpod'));
        if (getVal('obturados_cpod') == "" || isNaN(obturados_cpod) || obturados_cpod < 0 || obturados_cpod > 32) {
            alert('El Número de Obturados debe ser un Número Entero de 0 a 32');
            return false;
        }
    }

    // Consultas Odontologicas CEOD (si los campos están presentes en pantalla)
    if (document.getElementById('caries_ceod')) {
        var caries_ceod = parseFloat(getVal('caries_ceod'));
        if (getVal('caries_ceod') == "" || isNaN(caries_ceod) || caries_ceod < 0 || caries_ceod > 32) {
            alert('El Número de Caries debe ser un Número Entero de 0 a 32');
            return false;
        }
        var extracciones_ceod = parseFloat(getVal('extracciones_ceod'));
        if (getVal('extracciones_ceod') == "" || isNaN(extracciones_ceod) || extracciones_ceod < 0 || extracciones_ceod > 32) {
            alert('El Número de Extracciones debe ser un Número Entero de 0 a 32');
            return false;
        }
        var obturados_ceod = parseFloat(getVal('obturados_ceod'));
        if (getVal('obturados_ceod') == "" || isNaN(obturados_ceod) || obturados_ceod < 0 || obturados_ceod > 32) {
            alert('El Número de Obturados debe ser un Número Entero de 0 a 32');
            return false;
        }
    }

    return true;
}

function cambiar_nomenclador() {
    borrar_buffer(); 
    if (confirm('Esta Accion Cambiara el Nomenclador Asociado al Efector: <?php echo addslashes($nombre);?>. ¿Esta Seguro?')) {
        guardar_scroll();
        document.forms[0].submit();
    } else {
        return false;
    }
}

var digitos = 10;
var puntero = 0;
var buffer = new Array(digitos);
var cadena = "";

function borrar_buffer() {
    puntero = 0;
    cadena = "";
    buffer = new Array(digitos);
}

function buscar_combo(obj) {
    var e = window.event || event;
    if (!e) return;
    var letra = String.fromCharCode(e.keyCode || e.which);
    if (puntero >= digitos) {
        cadena = "";
        puntero = 0;
    } else {
        buffer[puntero] = letra;
        cadena = cadena + buffer[puntero];
        puntero++;

        for (var opcombo = 1; opcombo < obj.length; opcombo++) {
            if (obj[opcombo].text.substr(0, puntero).toLowerCase() == cadena.toLowerCase()) {
                obj.selectedIndex = opcombo;
                break;
            }
        }
    }
    if (e.preventDefault) e.preventDefault();
    e.returnValue = false;
}
</script>

<div class="paf-wrapper">

  <form name="form1" id="form1" action="prestacion_admin_nop.php" method="POST">

    <!-- CAMPOS OCULTOS DE CONTROL Y CONTEXTO -->
    <input type="hidden" name="id" value="<?php echo $id?>">
    <input type="hidden" name="id_comprobante" value="<?php echo $id_comprobante?>">
    <input type="hidden" name="id_prestacion_extra" value="<?php echo $id_prestacion?>">
    <input type="hidden" name="id_smiafiliados" value="<?php echo $id_smiafiliados?>">
    <input type="hidden" name="id_nomenclador_detalle" value="<?php echo $id_nomenclador_detalle?>">
    <input type="hidden" name="cuie" value="<?php echo $cuie?>">
    <input type="hidden" name="clave_beneficiario" value="<?php echo $clave_beneficiario?>">
    <input type="hidden" name="fecha_nacimiento" value="<?php echo $fecha_nacimiento?>">
    <input type="hidden" name="fecha_comprobante" value="<?php echo $fecha_comprobante?>">
    <input type="hidden" name="pagina_viene" value="<?php echo $pagina_viene?>">
    <input type="hidden" name="pagina_listado" value="<?php echo $pagina_listado?>">
    <input type="hidden" name="flap" value="<?php echo $flap?>">
    <input type="hidden" name="accion" value="<?php echo $accion?>">

    <!-- DATOS DE BENEFICIARIO PARA POSTBACK -->
    <input type="hidden" name="afiapellido" value="<?php echo $afiapellido?>">
    <input type="hidden" name="afinombre" value="<?php echo $afinombre?>">
    <input type="hidden" name="afidni" value="<?php echo $afidni?>">
    <input type="hidden" name="fecha_nacimeinto" value="<?php echo fecha($fecha_nacimiento)?>">
    <input type="hidden" name="edad_total" value="<?php echo $anio_edad.' Año/s, '.$meses_edad.' Mes/es y '.$dias_edad.' dia/s'?>">
    <input type="hidden" name="edad_anios" id="edad_anios" value="<?php echo $anio_edad?>">
    <input type="hidden" name="edad" value="<?php echo $codigo_edad?>">
    <input type="hidden" name="grupo_etareo" value="<?php echo $grupo_etareo?>">
    <input type="hidden" name="dias_de_vida" value="<?php echo $dias_de_vida?>">
    <input type="hidden" name="sexo_codigo" value="<?php echo $sexo_codigo?>">
    <input type="hidden" name="sexo" value="<?php echo $sexo_1?>">

    <!-- CABECERA INSTITUCIONAL -->
    <div class="paf-main-header">
      <div class="paf-main-title">
        <span style="font-size: 24px;">👤</span>
        <div>
          <div>Facturación de Prestaciones</div>
          <div style="font-size: 13px; font-weight: 500; opacity: 0.95; margin-top: 2px;">
            Efector: <b><?php echo $nombre?></b> (<?php echo $cuie?>) &bull; Fecha Práctica: <b><?php echo fecha($fecha_comprobante)?></b>
          </div>
        </div>
      </div>
      <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
        <span style="font-size: 12px; font-weight: 600; background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.3);">
          Comprobante: <b>#<?php echo $id_comprobante?></b>
        </span>
        <span style="font-size: 12px; font-weight: 600; background: #fef08a; color: #854d0e; padding: 5px 12px; border-radius: 12px;">
          Nomenclador: <b><?php echo $descripcion?></b>
        </span>
      </div>
    </div>

    <!-- TARJETA DE ALERTAS Y MENSAJES -->
    <?php if (!empty($accion)): ?>
      <?php if (strpos(strtolower($accion), "grabo") !== false || strpos(strtolower($accion), "éxito") !== false): ?>
        <div class="paf-alert paf-alert-success">
          <span style="font-size: 22px;">✅</span>
          <div>
            <b>¡Prestación registrada con éxito!</b>
            <div style="font-size: 12.5px; margin-top: 2px;">Los datos fueron guardados correctamente en el comprobante. Puede ingresar una nueva prestación.</div>
          </div>
        </div>
      <?php elseif ($accion == 'Comprobante Duplicado' || $accion == 'Supera la Tasa de Uso'): ?>
        <div class="paf-alert paf-alert-danger">
          <span style="font-size: 22px;">❌</span>
          <div>
            <b>Advertencia de Registro</b>
            <div style="font-size: 12.5px; margin-top: 2px;"><?php echo $accion . " " . $msg_precio?></div>
          </div>
        </div>
      <?php else: ?>
        <div class="paf-alert paf-alert-warning">
          <span style="font-size: 22px;">⚠️</span>
          <div>
            <b>Información del Sistema</b>
            <div style="font-size: 12.5px; margin-top: 2px;"><?php echo $accion . " " . $msg_precio?></div>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <!-- FICHA DEL BENEFICIARIO Y PRÁCTICA EN UN SOLO RENGLÓN COMPACTO -->
    <?php
    $css_etario = 'paf-etario-adulto';
    $ge_lower = strtolower($grupo_etareo);
    if (strpos($ge_lower, 'neonato') !== false) $css_etario = 'paf-etario-neonato';
    elseif (strpos($ge_lower, 'cero') !== false) $css_etario = 'paf-etario-ceroacinco';
    elseif (strpos($ge_lower, 'seis') !== false) $css_etario = 'paf-etario-seisanueve';
    elseif (strpos($ge_lower, 'adolecente') !== false) $css_etario = 'paf-etario-adolecente';
    elseif (strpos($ge_lower, 'mayores') !== false) $css_etario = 'paf-etario-mayores';
    ?>
    <div class="paf-patient-bar">
      <div class="paf-bar-item">
        <span class="paf-bar-label">📋 Paciente:</span>
        <span class="paf-bar-val" style="color: #1e3a8a; font-weight: 700;">
          <?php echo $afiapellido?>, <?php echo $afinombre?>
        </span>
      </div>

      <div class="paf-bar-item">
        <span class="paf-bar-label">DNI:</span>
        <span class="paf-badge-dni"><?php echo $afidni?></span>
      </div>

      <div class="paf-bar-item">
        <span class="paf-bar-label">Nac:</span>
        <span class="paf-bar-val"><?php echo fecha($fecha_nacimiento)?></span>
      </div>

      <div class="paf-bar-item">
        <span class="paf-bar-label">Edad Práctica:</span>
        <span class="paf-bar-val" style="color: #0f766e; font-weight: 700;"><?php echo $codigo_edad?> Años</span>
      </div>

      <div class="paf-bar-item">
        <span class="paf-bar-label">Etario:</span>
        <span class="paf-badge-group-etario <?php echo $css_etario?>">● <?php echo $grupo_etareo?></span>
      </div>

      <div class="paf-bar-item">
        <span class="paf-bar-label">Sexo:</span>
        <span class="paf-bar-val"><?php echo $sexo_1?></span>
      </div>

      <?php if (!empty($nombre_medico)): ?>
        <div class="paf-bar-item" style="border-left: 1px solid #cbd5e1; padding-left: 10px;">
          <span class="paf-bar-label">Médico:</span>
          <span class="paf-bar-val" style="color: #475569;"><?php echo $nombre_medico?></span>
        </div>
      <?php endif; ?>
    </div>

    <!-- SELECCIÓN DE PRESTACIÓN, OBJETO Y DIAGNÓSTICO -->
    <div class="paf-card">
      <div class="paf-card-header">
        <div style="display: flex; align-items: center; gap: 8px;">
          <span>🩺</span>
          <span>Carga de la Prestación Médica</span>
        </div>
        <div style="font-size: 12px; color: #64748b;">
          💡 Los campos avanzan secuencialmente de arriba hacia abajo
        </div>
      </div>
      <div class="paf-card-body">

        <div class="paf-grid-2col">
          
          <!-- COLUMNA IZQUIERDA: COMBOS DE FACTURACIÓN -->
          <div class="paf-col-left">
            
            <!-- 1. GRUPO / PRESTACIÓN -->
            <div class="paf-form-group">
              <label class="paf-form-label">
                <b>1. Prestación (Grupo)*:</b>
                <?php if (!empty($prestacion)): ?>
                  <span class="paf-code-pill" style="color:#15803d; border-color:#86efac;"><?php echo $prestacion?></span>
                <?php endif; ?>
              </label>
              <select name="prestacion" id="prestacion" class="paf-form-control"
                      onkeypress="buscar_combo(this);"
                      onblur="borrar_buffer();"
                      onchange="borrar_buffer(); guardar_scroll(); document.forms[0].submit();">
                <option value="-1">-- Seleccione una Prestación (Grupo) --</option>
                <?php
                if ($covid == 's') {
                    $sql = "SELECT DISTINCT ON (grupo) grupo, subgrupo, grupo_descriptivo FROM facturacion.nomenclador 
                            WHERE tipo_nomenclador='COVID' and (activo is null or (activo::text <> 'f' and activo::text <> 'false' and activo::text <> '0'))";
                } else {
                    $sql = "SELECT DISTINCT ON (grupo) grupo, subgrupo, grupo_descriptivo FROM facturacion.nomenclador 
                            WHERE (id_nomenclador_detalle='$id_nomenclador_detalle') and (subgrupo not like '%Reservado%')
                              and grupo<>'PC' and (activo is null or (activo::text <> 'f' and activo::text <> 'false' and activo::text <> '0'))
                            ORDER BY 1";
                }
                $res_efectores = sql($sql) or fin_pagina();
                while (!$res_efectores->EOF) {
                    $categoria = $res_efectores->fields['grupo_descriptivo'];
                    $codigo = $res_efectores->fields['grupo'];
                ?>
                  <option value="<?php echo $codigo?>" <?php if ($prestacion == $codigo) echo "selected"?>>
                    <?php echo $codigo . " - " . $categoria?>
                  </option>
                <?php
                    $res_efectores->movenext();
                }
                ?>
              </select>
            </div>

            <!-- 2. OBJETO DE LA PRESTACIÓN (TEMA) -->
            <?php
            switch ($grupo_etareo) {
                case 'Neonato': $campo_sel = 'neo'; break;
                case 'Cero a Cinco Años': $campo_sel = 'ceroacinco'; break;
                case 'Seis a Nueve Años': $campo_sel = 'seisanueve'; break;
                case 'Adolecente': $campo_sel = 'adol'; break;
                case 'Adulto': $campo_sel = 'adulto'; break;
                case 'Mayores de 65 años': $campo_sel = 'mayores_65'; break;
            }
            if (($sexo == 'M') || ($sexo == 'Masculino')) { $campo_sexo = 'm'; }
            if (($sexo == 'F') || ($sexo == 'Femenino')) { $campo_sexo = 'f'; }

            if ($flap == 's') {
                $sql = "SELECT * FROM facturacion.nomenclador 
                        WHERE ((descripcion ilike '%flap%' or descripcion ilike '%pie bot%' or descripcion ilike '%DISPLASIA DE CADERA%') 
                               and grupo = '$prestacion' and id_nomenclador_detalle='$id_nomenclador_detalle' and $campo_sel='1' and $campo_sexo='1' 
                               and (activo is null or (activo::text <> 'f' and activo::text <> 'false' and activo::text <> '0'))) 
                        ORDER BY codigo";
            } elseif ($covid == 's') {
                $sql = "SELECT * FROM facturacion.nomenclador 
                        WHERE tipo_nomenclador='COVID' and grupo='$prestacion'
                          and id_nomenclador_detalle='$id_nomenclador_detalle'
                          and codigo = 'E022' and (activo is null or (activo::text <> 'f' and activo::text <> 'false' and activo::text <> '0'))
                        ORDER BY codigo";
            } else {
                $sql = "SELECT * FROM facturacion.nomenclador 
                        WHERE (grupo = '$prestacion' AND id_nomenclador_detalle='$id_nomenclador_detalle' AND $campo_sel='1' AND $campo_sexo='1') 
                          AND (descripcion NOT ilike '%flap%' AND descripcion NOT ilike '%pie bot%' AND descripcion NOT ilike '%DISPLASIA DE CADERA%')
                          AND (activo is null or (activo::text <> 'f' and activo::text <> 'false' and activo::text <> '0'))
                        ORDER BY codigo";
            }
            $res_efectores = sql($sql) or fin_pagina();

            $result_fila_nomenclador = null;
            $codigo_nomen = '';
            $grupo_nomen = '';
            $descr_nomen = '';
            $codentero = '';
            if (!empty($tema) && $tema != '-1') {
                $sql_nomen = "SELECT * FROM facturacion.nomenclador WHERE id_nomenclador='$tema'";
                $result_fila_nomenclador = sql($sql_nomen, "no se puede ejecutar nomenclador");
                if ($result_fila_nomenclador && !$result_fila_nomenclador->EOF) {
                    $codigo_nomen = $result_fila_nomenclador->fields['codigo'];
                    $grupo_nomen = $result_fila_nomenclador->fields['grupo'];
                    $descr_nomen = $result_fila_nomenclador->fields['descripcion'];
                    $codentero = $grupo_nomen . $codigo_nomen;
                }
            }
            ?>

            <div class="paf-form-group">
              <label class="paf-form-label">
                <b>2. Objeto de la Prestación*:</b>
                <?php if (!empty($codigo_nomen)): ?>
                  <span class="paf-code-pill" style="color:#1d4ed8; border-color:#93c5fd;"><?php echo $codigo_nomen?></span>
                <?php endif; ?>
              </label>
              <select name="tema" id="tema" class="paf-form-control"
                      onkeypress="buscar_combo(this);"
                      onblur="borrar_buffer();"
                      onchange="borrar_buffer(); guardar_scroll(); document.forms[0].submit();">
                <option value="-1">-- Seleccione Objeto de la Prestación --</option>
                <?php
                while (!$res_efectores->EOF) {
                    $categoria_nom = $res_efectores->fields['descripcion'];
                    $codigo_nom_id = $res_efectores->fields['id_nomenclador'];
                    $codigo_nom_cod = $res_efectores->fields['codigo'];
                ?>
                  <option value="<?php echo $codigo_nom_id?>" <?php if ($tema == $codigo_nom_id) echo "selected"?>>
                    <?php echo $codigo_nom_cod . ' - ' . $categoria_nom?>
                  </option>
                <?php
                    $res_efectores->movenext();
                }
                ?>
              </select>
            </div>

            <!-- 3. DIAGNÓSTICO -->
            <?php
            if ($tipo_diag_radio == '') $tipo_diag_radio = 'Frec';
            $tabla_consulta_diag = "nomenclador.patologias_frecuentes";
            if ($tipo_diag_radio == 'Comp') $tabla_consulta_diag = "nomenclador.patologias";

            $res_diag = null;
            if ($covid == 's') {
                $sql = "SELECT * FROM nomenclador.patologias_frecuentes WHERE codigo='R83'";
                $res_diag = sql($sql) or fin_pagina();
            } else {
                if (!empty($tema) && $tema != '-1') {
                    $sql = "SELECT * FROM facturacion.parametro_nomen WHERE (id_nomenclador='$tema')";
                    $count_param = sql($sql) or fin_pagina();

                    if ($count_param->recordcount() == 0) {
                        $sql = "SELECT * FROM $tabla_consulta_diag 
                                WHERE (id_nomenclador_detalle='6') AND ($campo_sel='1') AND ($campo_sexo='1')
                                ORDER BY codigo";
                        $res_diag = sql($sql) or fin_pagina();
                    } else {
                        $sql = "SELECT $tabla_consulta_diag.* 
                                FROM $tabla_consulta_diag
                                INNER JOIN facturacion.parametro_nomen ON ($tabla_consulta_diag.codigo = facturacion.parametro_nomen.codigo)
                                WHERE (id_nomenclador_detalle='6') AND ($campo_sel='1') AND ($campo_sexo='1') AND (id_nomenclador='$tema')
                                ORDER BY $tabla_consulta_diag.codigo";
                        $res_diag = sql($sql) or fin_pagina();
                    }
                }
            }
            ?>

            <div class="paf-form-group">
              <label class="paf-form-label">
                <b>3. Diagnóstico Asociado*:</b>
                <?php if (!empty($patologia) && $patologia != '-1'): ?>
                  <span class="paf-code-pill" style="color:#7c3aed; border-color:#c4b5fd;"><?php echo $patologia?></span>
                <?php endif; ?>
              </label>
              <select name="patologia" id="patologia" class="paf-form-control"
                      onkeypress="buscar_combo(this);"
                      onblur="borrar_buffer();"
                      onchange="borrar_buffer(); guardar_scroll(); document.forms[0].submit();">
                <?php if (!$res_diag || $res_diag->recordcount() != 1): ?>
                  <option value="-1">-- Seleccione Diagnóstico --</option>
                <?php endif; ?>
                <?php
                if ($res_diag) {
                    while (!$res_diag->EOF) {
                        $descripcion_diag = $res_diag->fields['descripcion'];
                        $codigo_diag = trim($res_diag->fields['codigo']);
                        $ceps_ap = isset($res_diag->fields['ceps_ap']) ? $res_diag->fields['ceps_ap'] : '';
                        $color_diag = $res_diag->fields['color'];
                        $sel = ($patologia == $codigo_diag) ? "selected" : "";
                ?>
                      <option value="<?php echo $codigo_diag?>" <?php echo $sel?> <?php echo $color_diag?>>
                        <?php echo $codigo_diag . " - " . $descripcion_diag . ($ceps_ap ? " - " . $ceps_ap : "")?>
                      </option>
                <?php
                        $res_diag->movenext();
                    }
                }
                ?>
              </select>
            </div>

          </div>

          <!-- COLUMNA DERECHA: REFERENCIA DE DIAGNÓSTICOS Y RESUMEN -->
          <div class="paf-col-right">
            
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 7px; padding: 10px; margin-bottom: 12px;">
              <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px; display: flex; align-items: center; gap: 5px;">
                <span>🎨</span>
                <span>Referencia de Diagnósticos</span>
              </div>
              <div class="paf-palette-grid">
                <div class="paf-palette-item">
                  <div class="paf-color-dot" style="background-color: #BEF781;"></div>
                  <span>Signos y Síntomas</span>
                </div>
                <div class="paf-palette-item">
                  <div class="paf-color-dot" style="background-color: #F3F781;"></div>
                  <span>Infecciones</span>
                </div>
                <div class="paf-palette-item">
                  <div class="paf-color-dot" style="background-color: #46D7F4;"></div>
                  <span>Neoplasias</span>
                </div>
                <div class="paf-palette-item">
                  <div class="paf-color-dot" style="background-color: #F366D7;"></div>
                  <span>Lesiones</span>
                </div>
                <div class="paf-palette-item">
                  <div class="paf-color-dot" style="background-color: #81BEF7;"></div>
                  <span>Anomalías Congénitas</span>
                </div>
                <div class="paf-palette-item">
                  <div class="paf-color-dot" style="background-color: #D0A9F5;"></div>
                  <span>Otros Diagnósticos</span>
                </div>
              </div>
            </div>

            <?php if (!empty($tema) && $tema != '-1' && !empty($descr_nomen)): ?>
              <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px 14px;">
                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #1e40af; margin-bottom: 4px;">
                  Prestación Seleccionada
                </div>
                <div style="font-size: 13px; font-weight: 600; color: #1e293b; line-height: 1.4;">
                  <span class="paf-code-pill" style="color: #1e40af; margin-right: 4px;"><?php echo $codentero?></span> <?php echo $descr_nomen?>
                </div>
                <?php if (isset($result_fila_nomenclador->fields['precio']) && $result_fila_nomenclador->fields['precio'] > 0): ?>
                  <div style="margin-top: 6px; font-size: 12px; color: #059669; font-weight: 700;">
                    Precio: $ <?php echo number_format($result_fila_nomenclador->fields['precio'], 2, ',', '.')?>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>

          </div>

        </div>

      </div>
    </div>

    <!-- DATOS CLÍNICOS Y CAMPOS CONDICIONALES -->
    <?php if (!empty($tema) && $tema != '-1'): ?>
      
      <?php
      // Verificamos si existe al menos un campo condicional aplicable
      $query_odont = "SELECT * FROM facturacion.nomenclador WHERE id_nomenclador=$tema AND descripcion ilike '%odonto%' AND codigo <> 'C138'";
      $res_adont = sql($query_odont) or fin_pagina();
      $tiene_odont = ($res_adont->recordcount() > 0);

      $tiene_condicionales = (
          ($grupo_nomen == 'LB' && ($codigo_nomen == 'L119' || $codigo_nomen == 'L098' || $codigo_nomen == 'L056')) ||
          ($grupo_nomen == 'PR' && ($codigo_nomen == 'P053' || $codigo_nomen == 'P021' || $codigo_nomen == 'P017')) ||
          ($grupo_nomen == 'IG' && ($codigo_nomen == 'R014' || $codigo_nomen == 'R031')) ||
          ($grupo_nomen == 'CT' && $codigo_nomen == 'C074') ||
          ($grupo_nomen == 'IS' && $codigo_nomen == 'I002') ||
          ($grupo_nomen == 'AP' && ($codigo_nomen == 'A004' || $codigo_nomen == 'A002' || $codigo_nomen == 'A001')) ||
          ($grupo_nomen == 'NT' && $codigo_nomen == 'N002') ||
          $tiene_odont
      );
      ?>

      <?php if ($tiene_condicionales): ?>
        <div class="paf-card" style="border-top: 3px solid #3b82f6;">
          <div class="paf-card-header" style="background-color: #f0fdfa;">
            <div style="display: flex; align-items: center; gap: 8px; color: #0f766e;">
              <span>🔬</span>
              <span>Datos Clínicos y Parámetros de la Prestación</span>
            </div>
            <div style="font-size: 11.5px; color: #0d9488;">
              Complete los valores requeridos para la práctica
            </div>
          </div>
          <div class="paf-card-body">
            <div class="paf-clinical-grid">

              <!-- VDRL (L119) -->
              <?php if ($grupo_nomen == 'LB' && $codigo_nomen == 'L119'): ?>
                <div class="paf-form-group">
                  <label class="paf-form-label">Resultado VDRL*</label>
                  <select name="res_vdrl" id="res_vdrl" class="paf-form-control">
                    <option value="-1">-- Seleccione --</option>
                    <option value="+">Positivo</option>
                    <option value="-">Negativo</option>
                  </select>
                </div>
              <?php endif; ?>

              <!-- TiSOMF (L098) -->
              <?php if ($grupo_nomen == 'LB' && $codigo_nomen == 'L098'): ?>
                <div class="paf-form-group">
                  <label class="paf-form-label">Resultado TiSOMF*</label>
                  <select name="tisomf" id="tisomf" class="paf-form-control">
                    <option value="-1">-- Seleccione --</option>
                    <option value="Positivo">Positivo</option>
                    <option value="Negativo">Negativo</option>
                  </select>
                </div>
              <?php endif; ?>

              <!-- FINANCIADOR (P053) -->
              <?php if ($grupo_nomen == 'PR' && $codigo_nomen == 'P053'): ?>
                <div class="paf-form-group">
                  <label class="paf-form-label">Financiador del medicamento*</label>
                  <select name="financiador" id="financiador" class="paf-form-control">
                    <option value="-1">-- Seleccione --</option>
                    <option value="1">Provincial</option>
                    <option value="2">Nacional</option>
                    <option value="3">Otros</option>
                  </select>
                </div>
              <?php endif; ?>

              <!-- OÍDOS (P021) -->
              <?php if ($grupo_nomen == 'PR' && $codigo_nomen == 'P021'): ?>
                <div class="paf-form-group">
                  <label class="paf-form-label">Estudio Oído Derecho*</label>
                  <select name="est_oido_derecho" id="est_oido_derecho" class="paf-form-control">
                    <option value="">-- Seleccione --</option>
                    <option value="pasa">Pasa</option>
                    <option value="nopasa">No Pasa</option>
                  </select>
                </div>
                <div class="paf-form-group">
                  <label class="paf-form-label">Estudio Oído Izquierdo*</label>
                  <select name="est_oido_izquierdo" id="est_oido_izquierdo" class="paf-form-control">
                    <option value="">-- Seleccione --</option>
                    <option value="pasa">Pasa</option>
                    <option value="nopasa">No Pasa</option>
                  </select>
                </div>
              <?php endif; ?>

              <!-- MAMOGRAFÍA BIRADS (R014) -->
              <?php if ($grupo_nomen == 'IG' && $codigo_nomen == 'R014'): ?>
                <div class="paf-form-group">
                  <label class="paf-form-label">Resultado mamografía (BIRADS)*</label>
                  <select name="birads" id="birads" class="paf-form-control">
                    <option value="-1">-- Seleccione --</option>
                    <option value="0">0</option> 
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                  </select>
                </div>
              <?php endif; ?>

              <!-- TENSIÓN ARTERIAL (C074) -->
              <?php if ($grupo_nomen == 'CT' && $codigo_nomen == 'C074'): ?>
                <div class="paf-form-group">
                  <label class="paf-form-label">Tensión Sistólica (MÁXIMA)*</label>
                  <input type="text" name="ta_d" id="ta_d" class="paf-form-control" placeholder="Rango: 50-300">
                </div>
                <div class="paf-form-group">
                  <label class="paf-form-label">Tensión Diastólica (MÍNIMA)*</label>
                  <input type="text" name="ta_s" id="ta_s" class="paf-form-control" placeholder="Rango: 40-300">
                </div>
              <?php endif; ?>

              <!-- HEMOGLOBINA GLICOSILADA (L056) -->
              <?php if ($grupo_nomen == 'LB' && $codigo_nomen == 'L056'): ?>
                <div class="paf-form-group">
                  <label class="paf-form-label">Resultado Hemoglobina Glicosilada*</label>
                  <input type="text" name="hemo_glic" id="hemo_glic" class="paf-form-control" placeholder="Rango: 3-20">
                </div>
              <?php endif; ?>

              <!-- INDICADORES SANITARIOS (I002) -->
              <?php if ($grupo_nomen == 'IS' && $codigo_nomen == 'I002'): ?>
                <div class="paf-form-group">
                  <label class="paf-form-label">% personas georreferenciadas*</label>
                  <input type="text" name="porc_geo" id="porc_geo" class="paf-form-control" placeholder="Valor % (0-100)">
                </div>
                <div class="paf-form-group">
                  <label class="paf-form-label">% DBT 2 con hemo. glicosilada*</label>
                  <input type="text" name="porc_dbt" id="porc_dbt" class="paf-form-control" placeholder="Valor % (0-100)">
                </div>
                <div class="paf-form-group">
                  <label class="paf-form-label">% HTA con al menos un control*</label>
                  <input type="text" name="porc_hta" id="porc_hta" class="paf-form-control" placeholder="Valor % (0-100)">
                </div>
              <?php endif; ?>

              <!-- SEMANA GESTACIONAL ECOGRAFÍA (R031) -->
              <?php if ($grupo_nomen == 'IG' && $codigo_nomen == 'R031'): ?>
                <div class="paf-form-group">
                  <label class="paf-form-label">Semana de Gestación*</label>
                  <input type="text" name="r031_semges" id="r031_semges" class="paf-form-control" placeholder="Rango: 4-43">
                </div>
              <?php endif; ?>

              <!-- TEST VPH (A004) -->
              <?php if ($grupo_nomen == 'AP' && $codigo_nomen == 'A004'): ?>
                <div class="paf-form-group">
                  <label class="paf-form-label">Lectura de Test VPH*</label>
                  <select name="vph" id="vph" class="paf-form-control">
                    <option value="-1">-- Seleccione --</option>
                    <option value="Negativo">Negativo</option> 
                    <option value="Positivo">Positivo</option>
                  </select>
                </div>
              <?php endif; ?>

              <!-- RETINOPATÍA (P017) -->
              <?php if ($grupo_nomen == 'PR' && $codigo_nomen == 'P017'): ?>
                <div class="paf-form-group">
                  <label class="paf-form-label">Grado de Retinopatía*</label>
                  <select name="retinopatia" id="retinopatia" class="paf-form-control">
                    <option value="-1">-- Seleccione --</option>
                    <option value="0">0</option> 
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                </div>
              <?php endif; ?>

              <!-- BIOPSIA CUELLO UTERINO (A002) -->
              <?php if ($grupo_nomen == 'AP' && $codigo_nomen == 'A002' && $descr_nomen == 'Informe de biopsia de cuello uterino ante PAP positivo (25 a 69 años)'): ?>
                <div class="paf-form-group" style="grid-column: 1 / -1;">
                  <label class="paf-form-label">Informe Resultado (Diag. Biopsia)*</label>
                  <select name="diagnostico_pat_1" id="diagnostico_pat_1" class="paf-form-control">
                    <option value="-1">-- Seleccione --</option>
                    <option value="1">1 - No evaluable</option>
                    <option value="2">2 - NILM-NEGATIVO para la lesión intraepitelial o malignidad</option>
                    <option value="3">3 - ASC- células escamosas anormales / atípicas (sin otra especificación)</option>
                    <option value="4">4 - ASC-US- células escamosas anormales /atípicas de significado a determinar</option>
                    <option value="5">5 - ASC-H células escamosas anormales /atípicas en la que no es posible descartar HSIL</option>
                    <option value="6">6 - SIL - Lesión escamosa intraepitelial (de grado indeterminado)</option>
                    <option value="7">7 - LSIL - lesión escamosa intraepitelial de bajo grado (displasia leve- CIN 1- HPV)</option>
                    <option value="8">8 - HSIL - lesión escamosa intraepitelial de alto grado (displasia moderada, severa y CIS /CIN 2 Y 3)</option>
                    <option value="9">9 - HSIL lesión escamosa intraepitelial de alto grado con características sospechosas de invasión</option>
                    <option value="10">10 - CARCINOMA de células escamosas, no queratinizante (carcinoma epidermoide)</option>
                    <option value="11">11 - CARCINOMA de células escamosas, queratinizante (carcinoma epidermoide)</option>
                    <option value="12">12 - Otros Tumores malignos</option>
                  </select>
                </div>
              <?php endif; ?>

              <!-- BIOPSIA LESIÓN MAMA (A002) -->
              <?php if ($grupo_nomen == 'AP' && $codigo_nomen == 'A002' && $descr_nomen == 'Informe de biopsia de lesión de mama'): ?>
                <div class="paf-form-group" style="grid-column: 1 / -1;">
                  <label class="paf-form-label">Informe de Estudios Solicitados (Biopsia Mama)*</label>
                  <select name="diagnostico_pat_2" id="diagnostico_pat_2" class="paf-form-control">
                    <option value="-1">-- Seleccione --</option>
                    <option value="1">1 - Insatisfactoria</option>
                    <option value="2">2 - Epitelio anómalo no displásico</option>
                    <option value="3">3 - Condiloma viral/ Displasia leve /CIN 1/ L- SIL</option>
                    <option value="4">4 - Displasia moderada/ CIN 2 /H-SIL</option>
                    <option value="5">5 - Displasia Severa/ CIN 3 /H-SIL</option>
                    <option value="6">6 - Carcinoma in situ</option>
                    <option value="7">7 - Carcinoma invasivo escamoso</option>
                    <option value="8">8 - Adenocarcinoma in situ</option>
                    <option value="9">9 - Adenocarcinoma invasivo</option>
                    <option value="10">10 - Otros tumores epiteliales malignos</option>
                    <option value="11">11 - Otros tumores mesenquimáticos malignos</option>
                    <option value="12">12 - Otros tumores</option>
                    <option value="13">13 - Otro</option>
                    <option value="14">14 - Cervicitis</option>
                  </select>
                </div>
              <?php endif; ?>

              <!-- LECTURA DE PAP (A001) -->
              <?php if ($grupo_nomen == 'AP' && $codigo_nomen == 'A001' && $descr_nomen == 'Lectura de PAP (25 a 69 años)'): ?>
                <div class="paf-form-group" style="grid-column: 1 / -1;">
                  <label class="paf-form-label">Informe Resultado (Diag. Anatomopatológico)*</label>
                  <select name="diagnostico_pat_3" id="diagnostico_pat_3" class="paf-form-control">
                    <option value="-1">-- Seleccione --</option>
                    <option value="1">1 - Benigno</option>
                    <option value="2">2 - Carcinoma in situ</option>
                    <option value="3">3 - Carcinoma invasor</option>
                    <option value="4">4 - Otro maligno</option>
                    <option value="5">5 - Desconocido</option>
                    <option value="6">6 - Insatisfactorio</option>
                    <option value="7">7 - Pre neoplásico</option>
                    <option value="8">8 - Oculto</option>
                  </select>
                </div>
              <?php endif; ?>

              <!-- TRATAMIENTO CÁNCER CERVICOUTERINO (N002) -->
              <?php if ($grupo_nomen == 'NT' && $codigo_nomen == 'N002' && $descr_nomen == 'Notificación de inicio de tratamiento de cáncer cérvicouterino o lesión precancerosa'): ?>
                <div class="paf-form-group" style="grid-column: 1 / -1;">
                  <label class="paf-form-label">Tratamiento Instaurado (Cérvicouterino)*</label>
                  <select name="tratamiento_instaurado" id="tratamiento_instaurado" class="paf-form-control">
                    <option value="-1">-- Seleccione --</option>
                    <option value="1">1 - LEEP</option>
                    <option value="2">2 - Cono quirúrgico</option>
                    <option value="3">3 - Histerectomía simple</option>
                    <option value="4">4 - Histerectomía radical</option>
                    <option value="5">5 - Tratamiento oncológico con radioterapia externa</option>
                    <option value="6">6 - Tratamiento oncológico con braquiterapia</option>
                    <option value="7">7 - Tratamiento oncológico con quimioterapia</option>
                  </select>
                </div>
              <?php endif; ?>

              <!-- TRATAMIENTO CÁNCER DE MAMA (N002) -->
              <?php if ($grupo_nomen == 'NT' && $codigo_nomen == 'N002' && $descr_nomen == 'Notificación de inicio de tratamiento de cáncer de mama'): ?>
                <div class="paf-form-group" style="grid-column: 1 / -1;">
                  <label class="paf-form-label">Tratamiento Instaurado (Cáncer de Mama)*</label>
                  <select name="tratamiento_instaurado_de_cm" id="tratamiento_instaurado_de_cm" class="paf-form-control">
                    <option value="-1">-- Seleccione --</option>
                    <option value="1">1 - Intervención quirúrgica</option>
                    <option value="2">2 - Radioterapia</option>
                    <option value="3">3 - Quimioterapia</option>
                    <option value="4">4 - Hormonoterapia</option>
                  </select>
                </div>
              <?php endif; ?>

              <!-- ODONTOLOGÍA (CEOD / CPOD) -->
              <?php if ($tiene_odont): ?>
                <?php if ($res_adont->fields['descripcion'] == 'Consulta  buco-dental en salud en niños menores de 6 años (Grupo niños  0 a 5 años)'): ?>
                  <div class="paf-form-group">
                    <label class="paf-form-label">Caries (CEOD)*</label>
                    <input type="text" name="caries_ceod" id="caries_ceod" class="paf-form-control" placeholder="Número 0 a 32">
                  </div>
                  <div class="paf-form-group">
                    <label class="paf-form-label">Extracciones (CEOD)*</label>
                    <input type="text" name="extracciones_ceod" id="extracciones_ceod" class="paf-form-control" placeholder="Número 0 a 32">
                  </div>
                  <div class="paf-form-group">
                    <label class="paf-form-label">Obturados (CEOD)*</label>
                    <input type="text" name="obturados_ceod" id="obturados_ceod" class="paf-form-control" placeholder="Número 0 a 32">
                  </div>
                <?php else: ?>
                  <div class="paf-form-group">
                    <label class="paf-form-label">Caries (CPOD)*</label>
                    <input type="text" name="caries_cpod" id="caries_cpod" class="paf-form-control" placeholder="Número 0 a 32">
                  </div>
                  <div class="paf-form-group">
                    <label class="paf-form-label">Perdidos (CPOD)*</label>
                    <input type="text" name="perdidos_cpod" id="perdidos_cpod" class="paf-form-control" placeholder="Número 0 a 32">
                  </div>
                  <div class="paf-form-group">
                    <label class="paf-form-label">Obturados (CPOD)*</label>
                    <input type="text" name="obturados_cpod" id="obturados_cpod" class="paf-form-control" placeholder="Número 0 a 32">
                  </div>
                <?php endif; ?>
              <?php endif; ?>

            </div>
          </div>
        </div>
      <?php endif; ?>

    <?php endif; ?>

    <!-- BARRA DE ACCIONES PRINCIPAL -->
    <?php
    $codigo_trz = isset($result_fila_nomenclador->fields['codigo']) ? $result_fila_nomenclador->fields['codigo'] : '';
    $desc_trz = isset($result_fila_nomenclador->fields['descripcion']) ? $result_fila_nomenclador->fields['descripcion'] : '';
    $result_trz = null;
    $hab_on_line = '';

    if (!empty($codigo_trz) && !empty($desc_trz)) {
        $query_trz = "SELECT * FROM nomenclador.trz_pres 
                      WHERE obj_prestacion_vincula='$codigo_trz' AND descripcion_pres='$desc_trz' 
                        AND $campo_sel='1'";
        $result_trz = sql($query_trz, "no se puede ejecutar");
        if (($result_trz->RecordCount() > 0) && ($result_trz->fields['obliga_efector'] == '1')) {
            if ($usuario1) $hab_on_line = "disabled";
        }
    }
    ?>

    <div class="paf-card">
      <div class="paf-action-bar">
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
          <button type="submit" name="guardar" id="guardar_prestacion" value="Guardar Prestacion" 
                  class="paf-btn-save" onclick="return control_nuevos();" <?php echo $hab_on_line?>>
            <span>💾</span>
            <span>Guardar Prestación</span>
          </button>

          <?php if ($result_trz && $result_trz->RecordCount() > 0): ?>
            <?php
            $texto_boton = $result_trz->fields['texto_boton'];
            $trz_vincula = $result_trz->fields['trz_vincula'];
            $pagina_destino = $result_trz->fields['pagina_destino'];
            $param_pagina_destino = $result_trz->fields['param_pagina_destino'];
            $practica_vincula = $result_trz->fields['practica_vincula'];
            $obj_prestacion_vincula = $result_trz->fields['obj_prestacion_vincula'];
            $diagnostico = $result_trz->fields['diagnostico'];
            $descripcion_pres = $result_trz->fields['descripcion_pres'];

            $ref_trz = encode_link("../trazadorassps/$pagina_destino", array(
                "fecha_comprobante" => $fecha_comprobante,
                "id_smiafiliados" => $id_smiafiliados,
                "cuie" => $cuie,
                "pagina" => "prestacion_admin.php",
                "entidad_alta" => $entidad_alta,
                "id_beneficiarios" => $id
            ));
            ?>
            <button type="button" name="carga_trazadora" class="paf-btn-trazadora"
                    onclick="if (getVal('patologia') == '-1' || getVal('patologia') == '') { alert('Debe Seleccionar un Diagnostico'); } else { window.open('<?php echo $ref_trz?>','Trazadoras','dependent:yes,width=900,height=700,top=1,left=60,scrollbars=yes'); }"
                    title="<?php echo $trz_vincula?>">
              <span>⚡</span>
              <span><?php echo $texto_boton?></span>
            </button>
          <?php endif; ?>
        </div>

        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
          <?php
          if ($pagina_viene == 'comprobante_admin_total.php') {
              $ref = encode_link("comprobante_admin_total.php", array(
                  "id" => $id,
                  "pagina_listado" => $pagina_listado,
                  "pagina_viene" => "prestacion_admin.php",
                  "estado" => $estado
              ));
          ?>
            <a href="<?php echo $ref?>" class="paf-btn-back" title="Volver al Comprobante">
              <span>↩️</span> Volver al Comprobante
            </a>
          <?php
          } else {
              $ref = encode_link("comprobante_admin.php", array(
                  "id_smiafiliados" => $id_smiafiliados,
                  "clavebeneficiario" => $clave_beneficiario,
                  "pagina_listado" => $pagina_listado,
                  "pagina_viene" => "prestacion_admin.php",
                  "estado" => $estado
              ));
          ?>
            <a href="<?php echo $ref?>" class="paf-btn-back" title="Volver al Comprobante">
              <span>↩️</span> Volver al Comprobante
            </a>
          <?php
          }
          ?>

          <?php if ($pagina_listado == 'listado_beneficiarios_hist.php'): ?>
            <a href="listado_beneficiarios_hist.php" class="paf-btn-back" title="Volver al Listado">
              <span>📋</span> Volver al Listado
            </a>
          <?php elseif ($pagina_listado == 'listado_beneficiarios_leche.php'): ?>
            <a href="../entrega_leche/listado_beneficiarios_leche.php" class="paf-btn-back" title="Volver al Listado">
              <span>📋</span> Volver al Listado
            </a>
          <?php else: ?>
            <a href="listado_beneficiarios_fact.php" class="paf-btn-back" title="Volver al Listado">
              <span>📋</span> Volver al Listado
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </form>

</div>

<?php echo fin_pagina(); ?>