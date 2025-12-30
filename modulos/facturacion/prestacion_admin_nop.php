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
 		};
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

echo $html_header;
?>
<script>

function control_nuevos()
{
 if(document.all.prestacion.value=="-1"){
  alert('Debe Seleccionar una Prestacion');
  return false;
 }
 if(document.all.tema.value=="-1"){
  alert('Debe Seleccionar un Objeto de la Prestacion');
  return false;
 }
 if(document.all.patologia.value=="-1"){
  alert('Debe Seleccionar un Diagnostico');
  return false;
 }
 
 //Informe sanitario de población a cargo - ISI002
 if(document.all.tema.value=="11904" || document.all.tema.value=="12542"){
	if(isNaN(document.all.porc_geo.value)
  	||document.all.porc_geo.value<0||document.all.porc_geo.value>100){
  alert('Debe introducir un valor para el porcentaje (%) de personas a cargo georreferenciadas, rango 0-100');
	}

  if(isNaN(document.all.porc_dbt.value)
  	||document.all.porc_dbt.value<0 || document.all.porc_dbt.value>100){
  alert('Debe introducir un valor para el porcentaje (%) de personas con DBT 2 con una hemoglobina glicosilada, rango 0-100');
	}

  if(isNaN(document.all.porc_hta.value)
  	||document.all.porc_hta.value<0 || document.all.porc_hta.value>100){
  alert('Debe introducir un valor para el porcentaje (%) de personas con HTA con al menos un control por HTA, rango 0-100');
 	}
 }
 
 //Dispensa de medicamentos en efector - PRP053
 if(document.all.tema.value=="12226" || document.all.tema.value=="12864"){
	if(document.all.financiador.value=="-1"){
  alert('Debe introducir el financiador del medicamento dispensado');
  return false;
  }
 }

 //Mamografía (50 a 69 años, cada 2 años con mamografía negativa) - IGR014
 if(document.all.tema.value=="11867" || document.all.tema.value=="12505"){
  if(isNaN(document.all.birads.value)
  	||document.all.birads.value<0||document.all.birads.value>6){
  alert('Debe introducir un valor para resultado de BIRADS, rango 0-6');
  return false;
  }
 } 
 
 //Lectura de muestra de VPH (30 a 69 años) - APA004
 if(document.all.tema.value=="11865" || document.all.tema.value=="12503"){
  if(document.all.vph.value=="-1"){
  alert('Debe introducir un valor para resultado de VPH');
  return false;
  }
 } 
 
 //Consulta de detección y/o seguimiento de HTA - CTC074
 if(document.all.tema.value=="11790" || document.all.tema.value=="12428"){
  if(document.all.ta_d.value==""||isNaN(document.all.ta_d.value)
  	||document.all.ta_d.value<50||document.all.ta_d.value>300){
  alert('Debe introducir un valor entero valido para la tension sistólica, rango 50-300');
  return false;
  }
  if(document.all.ta_s.value==""||isNaN(document.all.ta_s.value)
  	||document.all.ta_s.value<40||document.all.ta_s.value>300){
  alert('Debe introducir un valor para la tension diastólica');
  return false;
  }
 } 

 //Hemoglobina glicosilada - LBL056
 if(document.all.tema.value=="12050" || document.all.tema.value=="12688"){
  if(document.all.hemo_glic.value==""||isNaN(document.all.hemo_glic.value)
  	||document.all.hemo_glic.value<3||document.all.hemo_glic.value>20){
  alert('Debe introducir un valor real valido del resultado de Hemoglobina glicosilada, rango 3-20');
  return false;
  }
 } 
 
 //Detección temprana de hipoacusia en RN (Otoemisiones acústicas) - PRP021
 if(document.all.tema.value=="11832"||document.all.tema.value=="12470"){
  if(document.all.est_oido_derecho.value==""){
  alert('Debe Seleccionar un Resultado del estudio de Oido Derecho');
  return false;
  }
  if(document.all.est_oido_izquierdo.value==""){
  alert('Debe Seleccionar un Resultado del estudio de Oido Izquierdo');
  return false;
  }
 }
 
//VDRL - LBL119
 if(document.all.tema.value=="12400"||document.all.tema.value=="13038"){
  if(document.all.res_vdrl.value=="-1"){
  alert('Debe Seleccionar un Resultado de Laboratorio para la prestacíon de VDRL');
  return false;
  }
}

//Test inmunoquímico de sangre oculta en materia fecal - TiSOMF (tamizaje 50 a 75 ) - LBL098
if(document.all.tema.value=="11866"||document.all.tema.value=="12504"){
  if(document.all.tisomf.value=="-1"){
  alert('Debe Seleccionar un Resultado de TiSOMF');
  return false;
  }
}

//Ecografía obstétrica - IGR031W78
if(document.all.tema.value=="11881"||document.all.tema.value=="12519"){
  if(document.all.r031_semges.value==""||isNaN(document.all.r031_semges.value)
  	||document.all.r031_semges.value<4||document.all.r031_semges.value>44){
  alert('Debe Seleccionar un Valor para la semana de gestacion, rango 4-43 (puede ir decimal con rango 0-6');
  return false;
  }
}

//Oftalmoscopía binocular indirecta (OBI) - PRP017
if(document.all.tema.value=="12413"||document.all.tema.value=="13051"){
  if(document.all.retinopatia.value=="-1"){
  alert('Debe Seleccionar un Grado de Retinopatía');
  return false;
  }
}
//programar solicitud de datos para Diagnostico biopsia - PRP017
if(document.all.tema.value=="12172"||document.all.tema.value=="12810"){
  if(document.all.diagnostico_pat_1.value=="-1"){
  alert('Debe Seleccionar el informe de transcripcion del resultado');
  return false;
  }
}

//Informe de biopsia de lesión de mama - APA002
if(document.all.tema.value=="12213"||document.all.tema.value=="12851"){
  if(document.all.diagnostico_pat_2.value=="-1"){
  alert('Debe Seleccionar el informe de transcripcion del resultado');
  return false;
  }
}

//Cáncer cérvicouterino - NTN003
if(document.all.tema.value=="12198"||document.all.tema.value=="12836"){
  if(document.all.tratamiento_instaurado.value=="-1"){
  alert('Debe Seleccionar el tratamiento Instaurado');
  return false;
  }
}

//Notificación de inicio de tratamiento de cáncer de mama - NTN002
if(document.all.tema.value=="12214"||document.all.tema.value=="12852"){
  if(document.all.tratamiento_instaurado_de_cm.value=="-1"){
  alert('Debe Seleccionar el tratamiento Instaurado');
  return false;
  }
}

//consultas Odontologicas
if ((document.all.tema.value=="12161" || document.all.tema.value=="12168"
	|| document.all.tema.value=="12799" || document.all.tema.value=="12806"
	) && document.all.edad_anios.value>=5){ 
  
  if(document.all.caries_cpod.value==""){
  alert('Debe Seleccionar Situacion de Caries');
  return false;
  }

  if(isNaN(document.all.caries_cpod.value)||document.all.caries_cpod.value<0
  	||document.all.caries_cpod.value>32){
  alert('El N\u00FAmero de Caries debe ser un N\u00FAmero Entero de 0 a 32');
  return false;
  }

  if(document.all.perdidos_cpod.value==""){
  alert('Debe Seleccionar Situacion de Perdidos');
  return false;
  }

  if(isNaN(document.all.perdidos_cpod.value)||document.all.perdidos_cpod.value<0
  	||document.all.perdidos_cpod.value>32){
  alert('El N\u00FAmero de Perdidos debe ser un N\u00FAmero Entero de 0 a 32');
  return false;
  }

  if(document.all.obturados_cpod.value==""){
  alert('Debe Seleccionar Situacion de Obturaciones');
  return false;
  }

  if(isNaN(document.all.obturados_cpod.value)||document.all.obturados_cpod.value<0
  	||document.all.obturados_cpod.value>32){
  alert('El N\u00FAmero de Obturados debe ser un N\u00FAmero Entero de 0 a 32');
  return false;
  }
}

if((document.all.tema.value=="12869"||document.all.tema.value=="12161"||document.all.tema.value=="12168"
	||document.all.tema.value=="12799"||document.all.tema.value=="12806"||document.all.tema.value=="12869"
	) 
	&& document.all.edad_anios.value<5){
  if(document.all.caries_ceod.value==""){
  alert('Debe Seleccionar Situacion de Caries');
  return false;
  }

  if(isNaN(document.all.caries_ceod.value)||document.all.caries_ceod.value<0
  	||document.all.caries_ceod.value>32){
  alert('El N\u00FAmero de Caries debe ser un N\u00FAmero Entero de 0 a 32');
  return false;
  }

  if(document.all.extracciones_ceod.value==""){
  alert('Debe Seleccionar Situacion de Extracciones indicadas');
  return false;
  }

  if(isNaN(document.all.extracciones_ceod.value)||document.all.extracciones_ceod.value<0
  	||document.all.extracciones_ceod.value>32){
  alert('El N\u00FAmero de Extracciones debe ser un N\u00FAmero Entero de 0 a 32');
  return false;
  }

  if(document.all.obturados_ceod.value==""){
  alert('Debe Seleccionar Situacion de Obturaciones');
  return false;
  }

  if(isNaN(document.all.obturados_ceod.value)
  	||document.all.obturados_ceod.value<0||document.all.obturados_ceod.value>32){
  alert('El N\u00FAmero de Obturados debe ser un N\u00FAmero Entero de 0 a 32');
  return false;
  }
}

}//de function control_nuevos()

function cambiar_nomenclador(){
	borrar_buffer(); 
	if (confirm ('Esta Accion Cambiara el Nomenclador Asociado al Efector: <?php echo $nombre;?>. ¿Esta Seguro?')){
		document.forms[0].submit()
	}
	else return false
}

/**********************************************************/
//funciones para busqueda abreviada utilizando teclas en la lista que muestra los clientes.
var digitos=10; //cantidad de digitos buscados
var puntero=0;
var buffer=new Array(digitos); //declaración del array Buffer
var cadena="";

function buscar_combo(obj)
{
   var letra = String.fromCharCode(event.keyCode)
   if(puntero >= digitos)
   {
       cadena="";
       puntero=0;
   }   
   //sino busco la cadena tipeada dentro del combo...
   else
   {
       buffer[puntero]=letra;
       //guardo en la posicion puntero la letra tipeada
       cadena=cadena+buffer[puntero]; //armo una cadena con los datos que van ingresando al array
       puntero++;

       //barro todas las opciones que contiene el combo y las comparo la cadena...
       //en el indice cero la opcion no es valida
       for (var opcombo=1;opcombo < obj.length;opcombo++){
          if(obj[opcombo].text.substr(0,puntero).toLowerCase()==cadena.toLowerCase()){
          obj.selectedIndex=opcombo;break;
          }
       }
    }//del else de if (event.keyCode == 13)
   event.returnValue = false; //invalida la acción de pulsado de tecla para evitar busqueda del primer caracter
}//de function buscar_op_submit(obj)

</script>

<form name='form1' action='prestacion_admin_nop.php' method='POST'>

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

<table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
     <?php
	     if ($accion=='') echo "<font size=+1><b>PRESTACIONES</b></font>";
	     elseif ($accion=='Comprobante Duplicado') echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
		 elseif ($accion=='Supera la Tasa de Uso') echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
		 else echo "<font size=+1 color=white><b>$accion $msg_precio</b></font>";
     ?>
    </td>
 </tr>

 <tr><td>
  <table width=100% align="center" class="bordes">
     <tr>
      <td id="mo" colspan="4">
       <b> Descripción del COMPROBANTE</b>
      </td>
     </tr>
     <tr>
       <td colspan="4">
        <table align="center">
		 <tr>
         	<td align="right">
         	  <b>Apellido:
         	</td>         	
            <td align='left'>
              <input type='text' name='afiapellido' value='<?php echo $afiapellido;?>' size=50 align='right' readonly></b>
            </td>         
            <td align="right" >
         	  <b> Nombre:
         	</td>   
           <td >
             <input type='text' name='afinombre' value='<?php echo $afinombre;?>' size=50 align='right' readonly></b>
           </td>
         </tr>
          
         <tr>
           <td align="right" >
         	  <b> Documento:
         	</td> 
           <td>
             <input type='text' name='afidni' value='<?php echo $afidni;?>' size=50 align='right' readonly></b>
           </td>          
           <td align="right" >
         	  <b> Fecha de Nacimiento:
         	</td> 
           <td >
             <input type='text' name='fecha_nacimeinto' value='<?php echo Fecha($fecha_nacimiento);?>' size=50 align='right' readonly></b>
           </td>
         </tr>
         
         <tr>
			<td align="right" title="A la fecha actual">
				<b>Edad a la Fecha Actual: </b>
			</td>
			<td align="left">					     	
				<?$edad_con_meses=edad_con_meses($fecha_nacimiento);
				$anio_edad=$edad_con_meses["anos"];
				$meses_edad=$edad_con_meses["meses"];
				$dias_edad=$edad_con_meses["dias"];?>
				<input type="text" value="<?echo $anio_edad." Año/s, ".$meses_edad." Mes/es y ".$dias_edad." dia/s"?>" name=edad_total size="50" readonly>			               
				<input type="hidden" value="<? echo $anio_edad;?>" name="edad_anios">
			</td>				
			<td align="right" id="mo">
				<b>Nomenclador en Uso:</b>
			</td>
			<td align="left" id="mo">	
				<?$cambia_nomenclador_prestaciones="disabled";?>	          			
				<select name=nomenclador_detalle Style="width=298px"
				onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="cambiar_nomenclador()" <?echo $cambia_nomenclador_prestaciones?>>
			    <?$sql="select * from facturacion.nomenclador_detalle";
			    $res=sql($sql) or fin_pagina();
			    while (!$res->EOF){ 
			    	$id_nomenclador_detalle_1=$res->fields['id_nomenclador_detalle'];
			        $descripcion=$res->fields['descripcion'];?>
			        <option value=<?php echo $id_nomenclador_detalle_1;if ($id_nomenclador_detalle==$id_nomenclador_detalle_1) echo " selected"?> >
			        	<?php echo $descripcion?>
			        </option>
			        <?$res->movenext();
			        }?>
			    </select>
			</td>
		</tr>
		
        <tr>
         	<td align="right">
         	  <b>Nombre del Efector:
         	</td>         	
            <td align='left'>
              <input type='text' name='afiapellido' value='<?php echo $nombre;?>' size=50 align='right' readonly></b>
            </td>
         
           <td align="right">
         	  <b> Fecha de la Prestacion:
         	</td> 
           <td colspan="2">
             <input type='text' name='afidni' value='<?php echo fecha($fecha_comprobante);?>' size=50 align='right' readonly></b>
           </td>
         </tr>
          
         <tr>
			<td align="right" title="A la fecha de la Practica">
				<b>Edad segun Fecha de Prestacion: </b>
			</td>
			<td align="left">					     	
				<?$codigo_edad=edad_con_meses_sin_fecha_actual($fecha_nacimiento,$fecha_comprobante);			     	
				$codigo_edad=$codigo_edad["anos"];
				if (strlen($codigo_edad)=='1'){
					$codigo_edad='0'.$codigo_edad;
				}
				else{
					$codigo_edad=$codigo_edad;
				}?>
				<input type="text" value="<?php echo $codigo_edad?>" name=edad size="50" readonly> 
			</td>			               
		      
			<td align="right" title="Grupo Etareo al la Fecha de la Practica">
				<b>Grupo Etario segun Fecha de la Prestacion: </b>
			</td>
			 <td align="left">
				<?$dias_de_vida=GetCountDaysBetweenTwoDates($fecha_nacimiento, $fecha_comprobante);
				if (($dias_de_vida>=0)&&($dias_de_vida<=28)) $grupo_etareo='Neonato';
				if (($dias_de_vida>28)&&($dias_de_vida<=2190)) $grupo_etareo='Cero a Cinco Años';
				if (($dias_de_vida>2190)&&($dias_de_vida<=3650)) $grupo_etareo='Seis a Nueve Años';
				if (($dias_de_vida>3650)&&($dias_de_vida<=7300)) $grupo_etareo='Adolecente';
				if (($dias_de_vida>7300)&&($dias_de_vida<=23725)) $grupo_etareo='Adulto';
				if ($dias_de_vida>23725) $grupo_etareo='Mayores de 65 años';?>					     		     		
				<input type="text" value="<?echo $grupo_etareo?>" name=grupo_etareo size="50" readonly>
				<input type="hidden" value="<?php echo $dias_de_vida?>" name="dias_de_vida">
			 </td>			               
			</tr> 
			                 
        </table>
      </td>      
     </tr>
   </table>     
	 <table class="bordes" align="center" width="90%">
		 <tr align="center" id="sub_tabla">		 	
		 	<td colspan="3">	
		 		Referencia para Diagnostico
		 	</td>
		 	<td colspan="2">	
		 		Nueva PRESTACION
		 	</td>
		 </tr>
		 <tr>
		 
		  <td colspan="3" align="center">
			 <table align='center' border=1 bordercolor='#000000' bgcolor='#FFFFFF' width='100%' cellspacing=0 cellpadding=0>
		     
		     <td width=30% bordercolor='#FFFFFF'>
		      <table border=1 bordercolor='#FFFFFF' cellspacing=0 cellpadding=0 width=100%>
		       <tr>
		        <td width=30 bgcolor='#BEF781' bordercolor='#000000' height=20>&nbsp;</td>
		        <td bordercolor='#FFFFFF'>Signos y Sintomas</td>
		        <td width=30 bgcolor='#F3F781' bordercolor='#000000' height=20>&nbsp;</td>
		        <td bordercolor='#FFFFFF'>Infecciones</td>
		       </tr>
		       <tr>
		       	<td>
		       	 &nbsp;
		       	</td>
		       </tr>
		       <tr>        
		        <td width=30 bgcolor='#46D7F4' bordercolor='#000000' height=20>&nbsp;</td>
		        <td bordercolor='#FFFFFF'>Neoplacias</td>
		        <td width=30 bgcolor='#F366D7' bordercolor='#000000' height=20>&nbsp;</td>
		        <td bordercolor='#FFFFFF'>Lesiones</td>
		       </tr>
		       <tr>
		       	<td>
		       	 &nbsp;
		       	</td>
		       </tr>
		       <tr>        
		        <td width=30 bgcolor='#81BEF7' bordercolor='#000000' height=20>&nbsp;</td>
		        <td bordercolor='#FFFFFF'>Anomalias Congenitas</td>
		        <td width=30 bgcolor='#D0A9F5' bordercolor='#000000' height=20>&nbsp;</td>
		        <td bordercolor='#FFFFFF'>Otros Diagnosticos</td>
		       </tr>
		      </table>
		     </td>
		    </table>
		 </td>
		 
		 <td class="bordes"><table>
			 <tr>
				 <td>	 	
					    <tr>      
			             <td align="right">
					    		<b>Sexo: </b>
					    	</td>
					     	<td align="left">
					    
					     		<?
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
					     		?>					     		
					     	    <input type="hidden" value="<?php echo $sexo_codigo?>" name="sexo_codigo">
					    		<input type="text" value="<?php echo $sexo_1?>" name=sexo Style="width=400px"size="8" readonly>
			               </td>			               
					 	</tr>				 	
					 	
					 	<tr>
					 		<td align="right">
					 			<b>Prestacion: </b>
					    	</td>
					    	<td align="left">
					     		<select name=prestacion Style="width=400px"
					 				onKeypress="buscar_combo(this);"
					 				onblur="borrar_buffer();"
					 				onchange="borrar_buffer(); document.forms[0].submit();">
				     				<option value=-1>Seleccione</option>
				                	<?
				                	if ($covid=='s') {
				                		$sql= "SELECT DISTINCT ON (grupo) grupo, subgrupo, grupo_descriptivo  FROM facturacion.nomenclador 
							     		WHERE tipo_nomenclador='COVID'";
				                	}
				                	 else {
					                	$sql= "SELECT DISTINCT ON (grupo) grupo, subgrupo, grupo_descriptivo FROM facturacion.nomenclador 
							     		WHERE (id_nomenclador_detalle='$id_nomenclador_detalle') and (subgrupo not like '%Reservado%')
							     		and grupo<>'PC'
							     		--group by grupo
							     		order by 1";}

						     		
				                 	$res_efectores=sql($sql) or fin_pagina();
				                 	while (!$res_efectores->EOF){ 
				                 		$categoria=$res_efectores->fields['grupo_descriptivo'];
				                 		$codigo=$res_efectores->fields['grupo'];?>		                 
				                   		<option value='<?php echo $codigo;?>' <?php if ($prestacion==$codigo) echo "selected"?>> 
				                  	 	<?php echo $codigo." - ".$categoria?></option>
				                    	<?$res_efectores->movenext();
				                 	}?>
			        			</select>
			      			</td>
			      			
					    	<td align="left">					    		
					    		<b><label style="color:green; font-size: 11pt"><?echo $prestacion;?></label></b>		     		
			                </td>
					 	</tr>
					 	
					 	<tr>
					 		<td align="right">
					 			<b>Objeto de la Prestación: </b>
					    	</td>
					    	<td align="left">
					     		<select name=tema Style="width=400px"
				 				onKeypress="buscar_combo(this);"
				 				onblur="borrar_buffer();"
				 				onchange="borrar_buffer(); document.forms[0].submit();">
			     				<option value=-1>Seleccione</option>
			                	<?
			                	switch ($grupo_etareo){
									case 'Neonato':
										$campo_sel='neo';
										break;
									case 'Cero a Cinco Años':
										$campo_sel='ceroacinco';
										break;
									case 'Seis a Nueve Años':
										$campo_sel='seisanueve';
										break;
									case 'Adolecente':
										$campo_sel='adol';
										break;
									case 'Adulto':
										$campo_sel='adulto';
										break;
									case 'Mayores de 65 años':
										$campo_sel='mayores_65';
										break;
                  }
								
								if (($sexo=='M')||($sexo=='Masculino')){
					     			$campo_sexo='m';
					     		}
					     		if (($sexo=='F')||($sexo=='Femenino')){
					     			$campo_sexo='f';
					     		}
							 
			            
                  if ($flap=='s') {
                    $sql= "SELECT * FROM 
                      facturacion.nomenclador 
                      WHERE ((descripcion ilike '%flap%' or descripcion ilike '%pie bot%' or descripcion ilike '%DISPLASIA DE CADERA%') and grupo = '$prestacion' and id_nomenclador_detalle='$id_nomenclador_detalle' and $campo_sel='1' and $campo_sexo='1') 
                      order by codigo";
                  }
               elseif ($covid=='s') {
              	     $sql= "SELECT * FROM 
                  facturacion.nomenclador 
                  WHERE tipo_nomenclador='COVID' and grupo='$prestacion'
                  and id_nomenclador_detalle='$id_nomenclador_detalle'
				  and codigo = 'E022'
                  order by codigo";
                  }
                  else {
                      
                  	//grupo de caps para poder facturar "extraccion de sangre"
                  	//if (es_hospital($cuie)) {	
					if (0) {	
                      $sql= "SELECT * FROM 
                        facturacion.nomenclador 
                        WHERE (grupo = '$prestacion' AND id_nomenclador_detalle='$id_nomenclador_detalle' AND $campo_sel='1' AND $campo_sexo='1') AND (descripcion NOT ilike '%flap%' AND descripcion NOT ilike '%pie bot%' AND descripcion NOT ilike '%DISPLASIA DE CADERA%')
                        	AND (codigo!='P008' 
							--or codigo !='P053' hablado con Guido el 11-05-2023
							)
                        order by codigo";
                    
                    } else {
                    	$sql= "SELECT * FROM 
                        facturacion.nomenclador 
                        WHERE (grupo = '$prestacion' AND id_nomenclador_detalle='$id_nomenclador_detalle' AND $campo_sel='1' AND $campo_sexo='1') AND (descripcion NOT ilike '%flap%' AND descripcion NOT ilike '%pie bot%' AND descripcion NOT ilike '%DISPLASIA DE CADERA%')
                        	--and codigo <> 'P053' habladon con Guido el 11-05-2023
							--and descripcion not ilike '%dispensa%'
                        	order by codigo";
                    	}

                  };

                  
			                 
			                 $res_efectores=sql($sql) or fin_pagina();
			                 while (!$res_efectores->EOF){ 
			                 	$categoria=$res_efectores->fields['descripcion'];
			                 	$codigo=$res_efectores->fields['id_nomenclador'];			                 	
			                 	$codigo1=$res_efectores->fields['codigo'];?>
			                   <option value=<?php echo $codigo;?> <?php if ($tema==$codigo) echo "selected"?>><?php echo $codigo1.' - '.$categoria?></option>
			                   
			                 <?
			                 $res_efectores->movenext();
			                 }
			                 ?>
			      			</select>
			      			</td>
			      			<?
			      			if ($tema!=''){
								$sql="select * from facturacion.nomenclador where id_nomenclador='$tema'";
								$result_fila_nomenclador=sql($sql,"no se puede ejecutar nomenclador");
			      			}?>
					    	<td align="left">
					    		<?if ($tema=='-1'){
					    			$color='red';
					    			$tema_cartel="*";
					    		}
					    		else{
					    			$color='green';
					    			$tema_cartel=$tema;
					    		}
					    		?>
					    		<b><label style="color:<?php echo $color?>; font-size: 11pt"><?echo $result_fila_nomenclador->fields['codigo'];?></label></b>	
			                </td>
					 	</tr>
					 	
					 	<!--<tr>
					 		<td align="right">					 			
					 			<b>Grupo de Diagnostico: </b>
					    	</td>
					    	<td align="left">	
					    		<?if ($tipo_diag_radio=='')$tipo_diag_radio='Frec'?>				    		
					     		<input type="radio" name="tipo_diag_radio" value="Frec" onclick="document.forms[0].submit();" <?if ($tipo_diag_radio=='Frec') echo "checked"?>> Frecuente
					 			<input type="radio" name="tipo_diag_radio" value="Comp" onclick="document.forms[0].submit();" <?if ($tipo_diag_radio=='Comp') echo "checked"?>> Completo
							</td>			      			
					 	</tr>-->
					 	
					 	<tr>
					 	<td align="right">					 			
					 	<b>Diagnostico: </b>
					  </td>
					  <td align="left">
					  <select name=patologia Style="width=400px"
					 			onKeypress="buscar_combo(this);"
					 			onblur="borrar_buffer();"
					 			onchange="borrar_buffer(); document.forms[0].submit();">				     				
            	 	<? 
            	 	if ($covid=='s') {
            	 		$sql= "SELECT * 
								   FROM nomenclador.patologias_frecuentes 
								   where codigo='R83'";		                	 			                 
						$res_efectores=sql($sql) or fin_pagina();
            	 	}
            	 	else {
            	 		if ($tipo_diag_radio=='Frec') $tabla_consulta_diag="nomenclador.patologias_frecuentes";
            	 		if ($tipo_diag_radio=='Comp') $tabla_consulta_diag="nomenclador.patologias";			                	 	
	            	 	switch ($grupo_etareo){
							case 'Neonato':
								$campo_sel='neo';
								break;
							case 'Cero a Cinco Años':
								$campo_sel='ceroacinco';
								break;
							case 'Seis a Nueve Años':
								$campo_sel='seisanueve';
								break;
							case 'Adolecente':
								$campo_sel='adol';
								break;
							case 'Adulto':
								$campo_sel='adulto';
								break;
							case 'Mayores de 65 años':
								$campo_sel='mayores_65';
								break;
						}								
						if (($sexo=='M')||($sexo=='Masculino')){
							$campo_sexo='m';
						}
						if (($sexo=='F')||($sexo=='Femenino')){
							$campo_sexo='f';
						}
					if ($tema){
						$sql= "SELECT * 
							   FROM facturacion.parametro_nomen 
							   where (id_nomenclador='$tema')";
						$count_param=sql($sql) or fin_pagina();
						
						if ($count_param->recordcount()==0){			
							$sql= "SELECT * 
								   FROM $tabla_consulta_diag 
								   where (id_nomenclador_detalle='6') and ($campo_sel='1') and ($campo_sexo='1')
									order by codigo";		                	 			                 
							$res_efectores=sql($sql) or fin_pagina();
						}
							else{
								$sql= "SELECT $tabla_consulta_diag.* 
									   FROM $tabla_consulta_diag
									   inner join facturacion.parametro_nomen ON ($tabla_consulta_diag.codigo = facturacion.parametro_nomen.codigo)
									   where (id_nomenclador_detalle='6') and ($campo_sel='1') and ($campo_sexo='1') and (id_nomenclador='$tema')
									   order by $tabla_consulta_diag.codigo";		                	 			                 
								$res_efectores=sql($sql) or fin_pagina();
							}
						}//if ($tema)
					}//del covid	
					if ($res_efectores->recordcount()!=1){?>
						<option value=-1>Seleccione</option>			                	 	
            	 	<?}
            	 	while (!$res_efectores->EOF){ 
             			$descripcion=$res_efectores->fields['descripcion'];
             			$codigo=$res_efectores->fields['codigo'];
             			$ceps_ap=$res_efectores->fields['ceps_ap'];
             			$color_diag=$res_efectores->fields['color'];?>
               			<option value=<?php echo $codigo;?> <?php if ($patologia==$codigo) echo "selected"?> <?php echo $color_diag?>> 
              	 		<?php echo $codigo." - ".$descripcion." - ".$ceps_ap?></option>
             			<?$res_efectores->movenext();
            	 	}?>
			      				</select>
			      			</td>
			      			<td align="left">
					    		<?if ($patologia=='-1'){
					    			$color='red';
					    			$patologia_cartel='*';
					    		}
					    		else{
					    			$color='green';
					    			$patologia_cartel=$patologia;
					    		}
					    		?>
					    		<b><label style="color:<?php echo $color?>; font-size: 11pt"><?echo $patologia_cartel;?></label></b>	
			                </td>
					 	</tr>
					 						 						 	
				<? if ($tema!='') {
					$sql_nomen="SELECT * from facturacion.nomenclador where id_nomenclador=$tema";
					$res_nomen=sql($sql_nomen) or fin_pagina();
					$codigo_nomen=$res_nomen->fields['codigo'];
					$grupo_nomen=$res_nomen->fields['grupo'];
					$descr_nomen=$res_nomen->fields['descripcion'];
					
					if ($grupo_nomen=='LB' and $codigo_nomen=='L119')  {?>
            
		                <tr>
		                <td align="right">
		                <b>Resultado VDRL: </b>
		                </td>
		                <td align="left">
		                <select name="res_vdrl" Style="width=400px">
		                      <option value="-1">Seleccione</option>
		                      <option value="+">Positivo</option>
		                      <option value="-">Negativo</option>
		                </select>
		                </td>
		                </tr>
		             <?}?> 

					 <?if ($grupo_nomen=='LB' and $codigo_nomen=='L098')  {?>            
						<tr>
						<td align="right">
						<b>Resultado TiSOMF: </b>
						</td>
						<td align="left">
						<select name="tisomf" Style="width=400px">
							<option value="-1">Seleccione</option>
							<option value="Positivo">Positivo</option>
							<option value="Negativo">Negativo</option>
						</select>
						</td>
						</tr>
					<?}?> 

					<?if ($grupo_nomen=='PR' and $codigo_nomen=='P053')  {?>            
						<tr>
						<td align="right">
						<b>Financiador del medicamento: </b>
						</td>
						<td align="left">
						<select name="financiador" Style="width=400px">
							<option value="-1">Seleccione</option>
							<option value="1">Provincial</option>
							<option value="2">Nacional</option>
							<option value="3">Otros</option>
						</select>
						</td>
						</tr>
					<?}?> 
             
             		<?if ($grupo_nomen=='PR' and $codigo_nomen=='P021') {?>
            
		                <tr>
		                <td align="right">
		                <b>Est.Oido Derecho: </b>
		                </td>
		                <td align="left">
		                <select name="est_oido_derecho" Style="width=400px">
		                      <option value="">Seleccione</option>
		                      <option value="pasa">Pasa</option>
		                      <option value="nopasa">No Pasa</option>
		                </select>
		                </td>
		                </tr>
		                
		                <tr>
		                <td align="right">
		                <b>Est.Oido Izquierdo: </b>
		                </td>
		                <td align="left">
		                <select name="est_oido_izquierdo" Style="width=400px">
		                      <option value="">Seleccione</option>
		                      <option value="pasa">Pasa</option>
		                      <option value="nopasa">No Pasa</option>
		                </select>
		                </td>
		                </tr>
		             <?}?>
             
             		<?if ($grupo_nomen=='IG' and $codigo_nomen=='R014') {?>
            
		                <tr>
		                <td align="right">
		                <b>Resultado mamograf&iacute;a (BIRADS): </b>
		                </td>
		                <td align="left">
		                <select name="birads" Style="width=400px">
		                      <option value="-1">Seleccione</option>
							  <option value="0">0</option> 
							  <option value="1">1</option>
		                      <option value="2">2</option>
		                      <option value="3">3</option>
		                      <option value="4">4</option>
		                      <option value="5">5</option>
							  <option value="6">6</option>
		                </select>
		                </td>
		                </tr>
		                <?}?> 		
						
						<?if ($grupo_nomen=='CT' and $codigo_nomen=='C074') {?>
            
							<tr>
			                <td align="right">
			                <b>MAXIMA: </b>
			                </td>
			                <td align="left">
			                <input type="text" name="ta_d" placeholder="Presion arterial rango:50-300">
			                </td>
			                </tr>
							
							<tr>
			                <td align="right">
			                <b>MINIMA: </b>
			                </td>
			                <td align="left">
			                <input type="text" name="ta_s" placeholder="Presion arterial rango:40-300">
			                </td>
			                </tr>
						<?}?> 

						<?if ($grupo_nomen=='LB' and $codigo_nomen=='L056') {?>
            
							<tr>
							<td align="right">
							<b>Resultado de Hemoglobina glicosilada: </b>
							</td>
							<td align="left">
							<input type="text" name="hemo_glic" placeholder="Res.Hemog.Glic. rango:3-20">
							</td>
							</tr>
						<?}?>

						<?if ($grupo_nomen=='IS' and $codigo_nomen=='I002') {?>
            
							<tr>
							<td align="right">
							<b>% de personas a cargo georreferenciadas: </b>
							</td>
							<td align="left">
							<input type="text" name="porc_geo" placeholder="Valor en porcentaje">
							</td>
							</tr>

							<tr>
							<td align="right">
							<b>% de personas con DBT 2 con una hemoglobina glicosilada: </b>
							</td>
							<td align="left">
							<input type="text" name="porc_dbt" placeholder="Valor en porcentaje">
							</td>
							</tr>

							<tr>
							<td align="right">
							<b>% de personas con HTA con al menos un control por HTA: </b>
							</td>
							<td align="left">
							<input type="text" name="porc_hta" placeholder="Valor en porcentaje">
							</td>
							</tr>
						<?}?>

						<?if ($grupo_nomen=='IG' and $codigo_nomen=='R031') {?>
            
							<tr>
							<td align="right">
							<b>Semana de Gestacion: </b>
							</td>
							<td align="left">
							<input type="text" name="r031_semges" placeholder="Edad Gestacional rango:3-20">
			                </td>
							</tr>
						<?}?>
						
						
						<?if ($grupo_nomen=='AP' and $codigo_nomen=='A004') {?>
            
							<tr>
							<td align="right">
							<b>Lectura de Test VPH: </b>
							</td>
							<td align="left">
							<select name="vph" Style="width=400px">
								<option value="-1">Seleccione</option>
								<option value="Negativo">Negativo</option> 
								<option value="Positivo">Positivo</option>								
							</select>
							</td>
							</tr>
						<?}?> 	
					
					
					 <?if ($grupo_nomen=='PR' and $codigo_nomen=='P017') {?>
            
		                <tr>
		                <td align="right">
		                <b>Grado de Retinopat&iacute;a: </b>
		                </td>
		                <td align="left">
		                <select name="retinopatia" Style="width=400px">
		                      <option value="-1">Seleccione</option>
							  <option value="0">0</option> 
							  <option value="1">1</option>
		                      <option value="2">2</option>
		                      <option value="3">3</option>
		                      <option value="4">4</option>
		                      <option value="5">5</option>
		                </select>
		                </td>
		                </tr>
		            <?}?> 

                	<?if ($grupo_nomen=='AP' and $codigo_nomen=='A002' and 
						$descr_nomen=='Informe de biopsia de cuello uterino ante PAP positivo (25 a 69 años)')			
                		 {?>
            
		                <tr>
		                <td align="right">
		                <b>Informe Resultado (Diag.Biopsia): </b>
		                </td>
		                <td align="left">
		                <!--ANTES
							1 - H-SIL
							2 - CIN 2
		                    3 - CIN 3
		                    4 - CIS-Carcinoma in situ
		                    5 - Cancer Cervicouterino
		                    6 - Lesion de Bajo grado (CIN1,L-SIL)
		                    7 - Sin Lesion
						 -->
						<select name="diagnostico_pat_1" Style="width=400px">
		                      <option value="-1">Seleccione</option>
		                      <option value=1>1 - No evaluable</option>
		                      <option value=2>2 - NILM-NEGATIVO para la lesión intraepitelial o malignidad</option>
		                      <option value=3>3 - ASC- células escamosas anormales / atípicas (sin otra especificación)</option>
		                      <option value=4>4 - ASC-US- células escamosas anormales /atípicas de significado a determinar</option>
		                      <option value=5>5 - ASC-H células escamosas anormales /atípicas en la que no es posible descartar HSIL</option>
		                      <option value=6>6 - SIL - Lesión escamosa intraepitelial (de grado indeterminado)</option>
		                      <option value=7>7 - LSIL - lesión escamosa intraepitelial de bajo grado (displasia leve- CIN 1- HPV)</option>
							  <option value=8>8 - HSIL - lesión escamosa intraepitelial de alto grado (displasia moderada, severa y CIS /CIN 2 Y 3)</option>
							  <option value=9>9 - HSIL lesión escamosa intraepitelial de alto grado con características sospechosas de invasión</option>
							  <option value=10>10 - CARCINOMA de células escamosas, no queratinizante (carcinoma epidermoide)</option>
							  <option value=11>11 - CARCINOMA de células escamosas, queratinizante (carcinoma epidermoide)</option>
							  <option value=12>12 - Otros Tumores malignos</option>
						</select>
		                </td>
		                </tr>
		             <?}?> 

             		<?if ($grupo_nomen=='AP' and $codigo_nomen=='A002' and 
                		$descr_nomen=='Informe de biopsia de lesión de mama'
            			) {?>
            
		                <tr>
		                <td align="right">
		                <b>Informe de Estudios Solicitados: </b>
		                </td>
		                <td align="left">
		                <!--ANTES
							  1 - Insitu
		                      2 - Invasor
		                      3 - Oculto
		                      4 - Otro CA
		                      5 - Preneoplasica
		                      6 - Benigno
		                      7 - Insatisfactorio
						-->
						<select name="diagnostico_pat_2" Style="width=400px">
		                      <option value="-1">Seleccione</option>
		                      <option value=1>1 - Insatisfactoria</option>
		                      <option value=2>2 - Epitelio anómalo no displásico</option>
		                      <option value=3>3 - Condiloma viral/ Displasia leve /CIN 1/ L- SIL</option>
		                      <option value=4>4 - Displasia moderada/ CIN 2 /H-SIL</option>
		                      <option value=5>5 - Displasia Severa/ CIN 3 /H-SIL</option>
		                      <option value=6>6 - Carcinoma in situ</option>
		                      <option value=7>7 - Carcinoma invasivo escamoso</option>
							  <option value=8>8 - Adenocarcinoma in situ</option>
							  <option value=9>9 - Adenocarcinoma invasivo</option>
							  <option value=10>10 - Otros tumores epiteliales malignos</option>
							  <option value=11>11 - Otros tumores mesenquimáticos malignos</option>
							  <option value=12>12 - Otros tumores</option>
							  <option value=13>13 - Otro</option>
							  <option value=14>14 - Cervicitis</option>
		                </select>
		                </td>
		                </tr>
		             <?}?>

             		<?if ($grupo_nomen=='AP' and $codigo_nomen=='A001' and 
					 $descr_nomen=='Lectura de PAP (25 a 69 años)')
                	 {?>

                	    <tr>
		                <td align="right">
		                <b>Informe Resultado (Diag.Anatomop.): </b>
		                </td>
		                <td align="left">
		                <!--ANTES
						    1 - H-SIL
		                    2 - CIN 2
		                    3 - CIN 3
		                    4 - CIS-Carcinoma in situ
		                    5 - Cancer Cervicouterino
		                    6 - Lesion de Bajo grado (ASC-US,CIN1,L-SIL)
		                    7 - Indeterminada (ASC-H)
		                    8 - Citologia Negativa
					 	-->
						<select name="diagnostico_pat_3" Style="width=400px">
		                      <option value="-1">Seleccione</option>
		                      <option value=1>1 - Benigno</option>
		                      <option value=2>2 - Carcinoma in situ</option>
		                      <option value=3>3 - Carcinoma invasor</option>
		                      <option value=4>4 - Otro maligno</option>
		                      <option value=5>5 - Desconocido</option>
		                      <option value=6>6 - Insatisfactorio</option>
		                      <option value=7>7 - Pre neoplásico</option>
		                      <option value=8>8 - Oculto</option>
		                </select>
		                </td>
		                </tr>
		            <?}?>

             <? if ($grupo_nomen=='NT' and $codigo_nomen=='N002' and 
                		$descr_nomen=='Notificación de inicio de tratamiento de cáncer cérvicouterino o lesión precancerosa'
                	) {?>
            
	                <tr>
	                <td align="right">
	                <b>Tratamiento Instaurado: </b>
	                </td>
	                <td align="left">
	                <!--ANTES
					      1 - Cono o Leep
	                      2 - Cirugia
	                      3 - Radioterapia
					-->
					<select name="tratamiento_instaurado" Style="width=400px">
	                      <option value="-1">Seleccione</option>
	                      <option value=1>1 - LEEP</option>
	                      <option value=2>2 - Cono quirúrgico</option>
	                      <option value=3>3 - Histerectomía simple</option>
						  <option value=4>4 - Histerectomía radical</option>
						  <option value=5>5 - Tratamiento oncológico con radioterapia externa</option>
						  <option value=6>6 - Tratamiento oncológico con braquiterapia</option>
						  <option value=7>7 - Tratamiento oncológico con quimioterapia</option>
	                </select>
	                </td>
	                </tr>
	             <?}?>

				 <? if ($grupo_nomen=='NT' and $codigo_nomen=='N002' and 
                		$descr_nomen=='Notificación de inicio de tratamiento de cáncer de mama'
                	) {?>
            
	                <tr>
	                <td align="right">
	                <b>Tratamiento Instaurado de cancer de mama: </b>
	                </td>
	                <td align="left">
	                <select name="tratamiento_instaurado_de_cm" Style="width=400px">
	                      <option value="-1">Seleccione</option>
	                      <option value=1>1 - Intervención quirúrgica</option>
						  <option value=2>2 - Radioterapia</option>
						  <option value=3>3 - Quimioterapia</option>
						  <option value=4>4 - Hormonoterapia</option>
	                </select>
	                </td>
	                </tr>
	             <?}?>

             <? 
             	  	$query_odont="SELECT * from facturacion.nomenclador where id_nomenclador=$tema
	             		and descripcion ilike '%odonto%'
	             		and codigo<> 'C138'";
	             	$res_adont=sql($query_odont) or fin_pagina();
	             	if ($res_adont->recordcount()>0)
	             	{
	             		if ($res_adont->fields['descripcion']=='Consulta  buco-dental en salud en niños menores de 6 años (Grupo niños  0 a 5 años)') {?>
	            		
		                	<tr>
			                <td align="right">
			                <b>Caries: </b>
			                </td>
			                <td align="left">
			                <input type="text" name="caries_ceod" placeholder="Numero de 0 a 32">
			                </td>
			                </tr>

			                <tr>
			                <td align="right">
			                <b>Extracciones: </b>
			                </td>
			                <td align="left">
			                <input type="text" name="extracciones_ceod" placeholder="Numero de 0 a 32">
			                </td>
			                </tr>

			                <tr>
			                <td align="right">
			                <b>Obturados: </b>
			                </td>
			                <td align="left">
			                <input type="text" name="obturados_ceod" placeholder="Numero de 0 a 32">
			                </td>
			                </tr>
			             <?}
		             	else {?>
							<tr>
			                <td align="right">
			                <b>Caries: </b>
			                </td>
			                <td align="left">
			                <input type="text" name="caries_cpod" placeholder="Numero de 0 a 32">
			                </td>
			                </tr>

			                <tr>
			                <td align="right">
			                <b>Perdidos: </b>
			                </td>
			                <td align="left">
			                <input type="text" name="perdidos_cpod" placeholder="Numero de 0 a 32">
			                </td>
			                </tr>

			                <tr>
			                <td align="right">
			                <b>Obturados: </b>
			                </td>
			                <td align="left">
			                <input type="text" name="obturados_cpod" placeholder="Numero de 0 a 32">
			                </td>
			                </tr>
			            <?}
		        	}
		        }?>

            	</td>
			 </tr>
		 </table>
		 </td>
		 
		 </tr>
		 	 
		 <tr> 
		  	<td align="center" colspan="4" class="bordes">
		  		<?php
		  		$codigo_trz=$result_fila_nomenclador->fields['codigo'];
		  		$desc_trz=$result_fila_nomenclador->fields['descripcion'];
		  		$query_trz="SELECT *
		    				FROM nomenclador.trz_pres
		    				WHERE obj_prestacion_vincula='$codigo_trz' AND descripcion_pres='$desc_trz'
		    				AND $campo_sel='1'";
		    	$result_trz=sql($query_trz,"no se puede ejecutar");		    	
		    	
		    	if (($result_trz->RecordCount()>0)&&($result_trz->fields['obliga_efector']=='1')){
		    		if ($usuario1)$hab_on_line="disabled";		    		
		    	}?>
		  		<input type="submit" name="guardar" id="guardar_prestacion" value="Guardar Prestacion" title="Guardar Prestacion" Style="width=300px;height=40px" onclick="return control_nuevos()" <?php echo $hab_on_line?>>
		    	<?php		    	
		    	if ($result_trz->RecordCount()>0){
		    		$texto_boton=$result_trz->fields['texto_boton'];
		    		$trz_vincula=$result_trz->fields['trz_vincula'];
		    		$pagina_destino=$result_trz->fields['pagina_destino'];
		    		$param_pagina_destino=$result_trz->fields['param_pagina_destino'];
		    		$practica_vincula=$result_trz->fields['practica_vincula'];
		    		$obj_prestacion_vincula=$result_trz->fields['obj_prestacion_vincula'];
		    		$diagnostico=$result_trz->fields['diagnostico'];
		    		$descripcion_pres=$result_trz->fields['descripcion_pres'];
		    		
		    		$ref=$ref = encode_link("../trazadorassps/$pagina_destino",array("fecha_comprobante"=>$fecha_comprobante,"id_smiafiliados"=>$id_smiafiliados,"cuie"=>$cuie,"pagina"=>"prestacion_admin.php","entidad_alta"=>$entidad_alta,"id_beneficiarios"=>$id));
  	    		?>	
  	    		&nbsp;&nbsp;&nbsp;&nbsp;	    		
				<input type=button name="carga_trazadora" value="<?php echo $texto_boton?>" 
  	    onclick="if(document.all.patologia.value=='-1'){
  			alert('Debe Seleccionar un Diagnostico');
  	    }
  	    else{  	    				
	  	  window.open('<?php echo $ref?>','Trazadoras','dependent:yes,width=900,height=700,top=1,left=60,scrollbars=yes');
  	    }" 
  	    title="<?php echo $trz_vincula?>" Style="width=300px;height=40px;background-color:#F781F3;">
		    <?php }?>		  		
		   </td>
    </tr> 
	 </table>	
 </td></tr>
 
   
 <tr><td><table width=100% align="center" class="bordes">
  <tr align="center">
   <td>
 	 <?if ($pagina_listado=='listado_beneficiarios_hist.php'){?>
   	 	<input type=button name="volver" value="Volver" onclick="document.location='listado_beneficiarios_hist.php'"title="Volver al Listado" style="width=150px">     
   	 <?}
   	 else if ($pagina_listado=='listado_beneficiarios_leche.php'){?>
   	 	<input type=button name="volver" value="Volver" onclick="document.location='../entrega_leche/listado_beneficiarios_leche.php'"title="Volver al Listado" style="width=150px">     
   	 <?}
   	 else{?>
     	<input type=button name="volver" value="Volver" onclick="document.location='listado_beneficiarios_fact.php'"title="Volver al Listado" style="width=150px">     
   	 <?}
     
     if ($pagina_viene=='comprobante_admin_total.php'){
	 	 $ref = encode_link("comprobante_admin_total.php",array("id"=>$id,"pagina_listado"=>$pagina_listado,"pagina_viene"=>"prestacion_admin.php","estado"=>$estado));?>
	     <input type=button name="volver" value="Volver al Beneficiario" onclick="document.location='<?php echo $ref?>'"title="Volver a los comprobantes" style="width=150px">  
	 <?}
	 else{
	 	 $ref = encode_link("comprobante_admin.php",array("id_smiafiliados"=>$id_smiafiliados,"clavebeneficiario"=>$clave_beneficiario,"pagina_listado"=>$pagina_listado,"pagina_viene"=>"prestacion_admin.php","estado"=>$estado));?>
	     <input type=button name="volver" value="Volver al Beneficiario" onclick="document.location='<?php echo $ref?>'"title="Volver a los comprobantes" style="width=150px"> 
	 <?}?>
	</td>   
  </tr>
 </table></td></tr>
 </table>
 
 </form>
 
 <?php echo fin_pagina();?>
