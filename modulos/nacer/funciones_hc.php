<?php

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
 
  $array_nacimiento = explode ( "-", $fecha_de_nacimiento ); 
  $array_actual = explode ( "-", $fecha_control);

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


function duplicado_fact_migracion($nomenclador,$id_smiafiliados,$fecha_control) {

  $query="SELECT id_prestacion, id_comprobante 
          from facturacion.prestacion
          inner join facturacion.comprobante using (id_comprobante)
          where 
          id_nomenclador=$nomenclador and fecha_comprobante='$fecha_control' and comprobante.id_smiafiliados='$id_smiafiliados'";
  $res_duplicados=sql($query, "Error") or fin_pagina();
  if ($res_duplicados->recordcount()==0){      
      return 1;
  }
  else{
    return 'Duplicado comp.n° '.$res_duplicados->fields['id_comprobante'].' prest.n° '.$res_duplicados->fields['id_prestacion']; 
    //return 0;
    }
}

function valida_prestacion_nuevo_nomenclador1($id_comprobante,$datos_nomenclador,$paciente,$fecha_comprobante){
   
  $codigo=$datos_nomenclador->fields['codigo'];
  $nomenclador=$datos_nomenclador['id_nomenclador'];
  $id_smiafiliados=$paciente['id_smiafiliados'];

  $query="SELECT * from facturacion.validacion_prestacion
      where id_nomenclador='$nomenclador'";              
  $res=sql($query, "Error 1") or fin_pagina();
  
  if ($res->RecordCount()>0){//me fijo si hay que validar (si tiene regla)
    //cantidad de prestaciones limites
    $cant_pres_lim=$res->fields['cant_pres_lim'];
    $per_pres_limite=$res->fields['per_pres_limite'];
    
    //cuenta la cantidad de prestaciones de un determinado afiliado, de un determinado codigo y 
    //en un periodo de tiempo parametrizado.
  $query="SELECT id_prestacion, codigo, fecha_comprobante
        FROM nacer.smiafiliados
          INNER JOIN facturacion.comprobante ON (nacer.smiafiliados.id_smiafiliados = facturacion.comprobante.id_smiafiliados)
          INNER JOIN facturacion.prestacion ON (facturacion.comprobante.id_comprobante = facturacion.prestacion.id_comprobante)
          INNER JOIN facturacion.nomenclador ON (facturacion.prestacion.id_nomenclador = facturacion.nomenclador.id_nomenclador)
          where smiafiliados.id_smiafiliados=$id_smiafiliados and 
              nomenclador.id_nomenclador='$nomenclador' and
               facturacion.comprobante.marca !=1 and
             fecha_comprobante between ('$fecha_comprobante'::date - $per_pres_limite) and '$fecha_comprobante'::date";
   $cant_pres=sql($query, "Error 3") or fin_pagina();
  
   if ($cant_pres->RecordCount()>=$cant_pres_lim){
        $msg_error=$res->fields['msg_error'];
        return $msg_error." - Cantidad de Prestaciones: ".$cant_pres->RecordCount()." - Limite: ".$cant_pres_lim." en ".$per_pres_limite." dias" . " - Codigo: ".$codigo. " - DNI: ".$paciente['afidni']; 
        
        //return 0;
      }
      else return 1;
  }
  else return 1;
}


function calculo_percentilo_peso($dias,$sexo,$peso){

  $sql="SELECT * from nacer.percentilos_peso where dias=$dias and sexo='$sexo'";
  $res_sql=sql($sql) or fin_pagina();

  if ($res_sql->RecordCount()!=0) {
    switch ($peso){
      case $peso<=$res_sql->fields['p3'] : return '1';break;
      case $peso>$res_sql->fields['p3'] and $peso<=$res_sql->fields['p10'] : return '2';break;
      case $peso>$res_sql->fields['p10'] and $peso<=$res_sql->fields['p85'] : return '3';break;
      case $peso>$res_sql->fields['p85'] and $peso<=$res_sql->fields['p97'] : return '4';break;
      case $peso>$res_sql->fields['p97'] :return '5';break;
    }
  }
}

function calculo_percentilo_imc($meses,$sexo,$imc){

  $sql="SELECT * from nacer.percentilos_imc where meses=$meses and sexo='$sexo'";
  $res_sql=sql($sql) or fin_pagina();

  if ($res_sql->RecordCount()!=0) {
    switch ($imc){
      case $imc<=$res_sql->fields['p3'] : return '1';break;
      case $imc>$res_sql->fields['p3'] and $imc<=$res_sql->fields['p10'] : return '2';break;
      case $imc>$res_sql->fields['p10'] and $imc<=$res_sql->fields['p85'] : return '3';break;
      case $imc>$res_sql->fields['p85'] and $imc<=$res_sql->fields['p97'] : return '4';break;
      case $imc>$res_sql->fields['p97'] :return '5';break;
    }
  }
}

function calculo_percentilo_talla($dias,$sexo,$talla){

  $sql="SELECT * from nacer.percentilos_talla where dias=$dias and sexo='$sexo'";
  $res_sql=sql($sql) or fin_pagina();

  if ($res_sql->RecordCount()!=0) {
    switch ($talla){
      case $talla<=$res_sql->fields['p3'] : return '1';break;
      case $talla>$res_sql->fields['p3'] and $talla<=$res_sql->fields['p10'] : return '2';break;
      case $talla>$res_sql->fields['p10'] and $talla<=$res_sql->fields['p85'] : return '3';break;
      case $talla>$res_sql->fields['p85'] and $talla<=$res_sql->fields['p97'] : return '4';break;
      case $talla>$res_sql->fields['p97'] :return '5';break;
    }
  }
}

function get_efector ($code_hc) {

	$query_efec="SELECT * from nacer.efe_conv where code_hc='".$code_hc."' and conv_sumar=TRUE";
	$res_efec=sql($query_efec) or fin_pagina();
	      if ($res_efec->Recordcount()>0) {
	        $efe = array (
              "cuie" => $res_efec->fields['cuie'],
              "nombre" => $res_efec->fields['nombre']
              );
          return $efe;
	        } else return false;
}

function get_paciente($afidni,$fecha_control) {
	$query_afi="SELECT * from nacer.smiafiliados where afidni='$afidni'";
  $res_afi=sql($query_afi) or fin_pagina();
        
  if ($res_afi->recordcount()>0) {
    $sexo=trim($res_afi->fields['afisexo']);
    $edad=edad_con_meses($res_afi->fields['afifechanac'],$fecha_control);
    $edad_anios=$edad['anos'];
    $edad_meses=$edad['meses'];
    $edad_dias=$edad['dias'];
    $activo=trim($res_afi->fields['activo']);

    $clase_doc=$res_afi->fields['aficlasedoc'];
	  $tipo_doc=$res_afi->fields['afitipodoc'];
    $nombre=$res_afi->fields['afinombre'];
    $apellido=$res_afi->fields['afiapellido'];
    
    $beneficiario = array (
    	'id_smiafiliados' => $res_afi->fields['id_smiafiliados'],
      'clavebeneficiario' => $res_afi->fields['clavebeneficiario'],
      'afidni' => $res_afi->fields['afidni'],
      'afifechanac' => $res_afi->fields['afifechanac'],
      'sexo' => $sexo,
      'grupopoblacional' => $res_afi->fields['grupopoblacional'],
      'edad' => edad_con_meses($res_afi->fields['afifechanac'],$fecha_control),
      'edad_anios' => $edad_anios,
      'edad_meses' => $edad_meses,
      'edad_dias' => $edad_dias,
      'activo' => $activo,
      'clase_doc' => $clase_doc,
      'tipo_doc' => $tipo_doc,
      'nombre' => $nombre,
      'apellido' => $apellido,
      'tipo_benef' => 'na'
  		);
 
    return $beneficiario;

  } else {
    	//buscar en uad.beneficiarios
    	$query_afi="SELECT * from uad.beneficiarios where numero_doc='$afidni'";
		  $res_afi=sql($query_afi) or fin_pagina();
		        
		  if ($res_afi->recordcount()>0) {
	      $sexo=trim($res_afi->fields['sexo']);
	      $edad=edad_con_meses($res_afi->fields['fecha_nacimiento_benef'],$fecha_control);
	      $edad_anios=$edad['anos'];
	      $edad_meses=$edad['meses'];
	      $edad_dias=$edad['dias'];
	      $activo='N';
	      	      
	      $beneficiario = array (
	      	'id_smiafiliados' => $res_afi->fields['id_beneficiarios'],
		      'clavebeneficiario' => $res_afi->fields['clave_beneficiario'],
		      'afidni' => $res_afi->fields['numero_doc'],
		      'afifechanac' => $res_afi->fields['fecha_nacimiento_benef'],
		      'sexo' => $sexo,
		      'grupopoblacional' => $res_afi->fields['grupopoblacional'],//revisar
		      'edad_anios' => $edad_anios,
		      'edad_meses' => $edad_meses,
		      'edad_dias' => $edad_dias,
		      'activo' => $activo,
		      'clase_doc' => $res_afi->fields['clase_documento_benef'],
		      'tipo_doc' => $res_afi->fields['tipo_documento'],
		      'nombre' => $res_afi->fields['nombre_benef'],
		      'apellido' => $res_afi->fields['apellido_benef'],
		      'tipo_benef' => 'nu'
		  		);
		 
		      return $beneficiario;
        }
      else return false;  
    	}
}

function get_info($product) {

  $reason=$product{'reason'};
  	
  //if (isset($reason)) {
  $fecha_control=substr($product{'consultationDate'},0,10);
  $fecha_control=Fecha_db($fecha_control);
  //echo $fecha_control;
  $nom_medico=$product{'professional'}{'nombre'};
  $especialidad=$product{'specialty'};
  
  $datos=$product{'diagnoses'};
  $descripcion=$datos[0]['description'];
  //$snomed_code=($datos[0]['procedureCode']==null)?$datos[0]['reportableDiagnosis']['procedureCode']:$datos[0]['procedureCode'];
  
  //2023-05-25
  if ($datos[0]['procedureCode']==null and $datos[0]['reportableDiagnosis']['procedureCode']<>NULL)
    $snomed_code=$datos[0]['reportableDiagnosis']['procedureCode'];
  elseif ($datos[0]['procedureCode']==null and $descripcion=='CONTROL DE SALUD')
    $snomed_code=185349003;//forzo el codigo en los .json de adolescentes
  else $snomed_code=$datos[0]['procedureCode'];
  //2023-05-25
  
  if ($nom_medico and $fecha_control) {
    $info_datos = array (
      'reason' => $reason,
      'fecha_control' => $fecha_control,
      'nom_medico' => $nom_medico,
      'especialidad' => $especialidad,
      'descripcion' => $descripcion,
      'snomed_code' => $snomed_code
    );
    return $info_datos;
  } else return false;
}

function get_fpp_fum ($eg,$fecha_control) {

    if ($eg<>0) {
    //calculo la fpp
    $dias=282;
    $dias_eg=$eg * 7;
    $dif_dias=$dias - $dias_eg;

    //$fpp = strtotime ( '+'.$dif_dias.' day' , strtotime ( $fecha_control ) ) ;
    //$fpp = date ( 'Y-m-j' , $fpp );
    $query_fpp="SELECT ('$fecha_control'::date + round($dif_dias)::integer)::date as fpp";
    $res_fpp=sql($query_fpp) or fin_pagina();
    $fpp=$res_fpp->fields['fpp'];
  
    //calculo la fum
    $dias_eg=$eg * 7;
    $query_fum="SELECT ('$fecha_control'::date - round($dias_eg)::integer)::date as fum";
    $res_fum=sql($query_fum) or fin_pagina();
    $fum=$res_fum->fields['fum'];
    //$fum = strtotime ( '-'.$dias_eg.' day' , strtotime ( $fecha_control ) ) ;
    //$fum = date ( 'Y-m-j' , $fum );
    
    $fechas_emb = array (
      'fum' => $fum,
      'fpp' => $fpp
      );
    return $fechas_emb;
    
    } else FALSE;
    
}

function get_datos ($product_data,$tipo_control,$fecha_control) {

	if (!(empty($product_data))) {
          foreach ($product_data as $dato) {
            //echo $dato['description'].'<br>';
            if ($dato['id']=='3') $ta = $dato['value'];
            
            switch (strtolower($dato['description'])) {
              case 'weight': {$peso=is_numeric(strtr($dato['value'],',','.'))?strtr($dato['value'],',','.'):NULL;};break;
              case 'height': {$talla=is_numeric(strtr($dato['value'],',','.'))?strtr($dato['value'],',','.'):NULL;};break;
              case 'bloodpressure': $ta=strtr($dato['value'],',','.');break;//no viene mas con ese definicion
              case 'gestationalage': $eg=is_numeric (strtr($dato['value'],',','.'))?strtr($dato['value'],',','.'):NULL;break;
              case 'fum': $fum=$dato['value'];break;
              case 'fpp': $fpp=$dato['value'];break;
              case 'cephalicperimeter': $perimetro_cefalico=strtr($dato['value'],',','.');break; 
              //case 'Percentile IMC': {$imc=(is_numeric (strtr($dato['value'],',','.')))?strtr($dato['value'],',','.'):NULL;};break;          
              default:break;
            }        
          }
        $peso=($peso>1000)? $peso=($peso/100) : $peso;

        //$talla=($talla>2.5)? $talla=($talla/100) : $talla;
        
        if ($talla<>0) $imc=$peso/(($talla/100)*($talla/100));

        if (is_numeric($perimetro_cefalico) and  $perimetro_cefalico>=100) $perimetro_cefalico=round ($perimetro_cefalico/10,1);
              
        if ($tipo_control=='Embarazadas') {
          if ($fpp=='' or $fum=='') {
            if ($eg!='' and $fecha_control!='') {
              $fechas_emb=get_fpp_fum ($eg,$fecha_control);
              $fum=$fechas_emb['fum'];
              $fpp=$fechas_emb['fpp'];
            }
          }
        } 

        $datos = array (
        'peso' => $peso,
        'talla' => $talla,
        'ta' => $ta,
        'eg' => $eg,
        'fum' => $fum,
        'fpp' => $fpp,
        'perimetro_cefalico' => $perimetro_cefalico,
        'imc' => $imc
          );               

        return $datos;
    
    } else return FALSE;    

}



function get_id_nomenclador_detalle ($fecha_control) {
	$nomen="SELECT * from facturacion.nomenclador_detalle 
        where '$fecha_control'::date between fecha_desde and fecha_hasta and modo_facturacion='4'";
    $res_nomen=sql($nomen) or fin_pagina();
    if ($res_nomen->recordcount()>0) return $res_nomen->fields['id_nomenclador_detalle'];
    	else return FALSE;
}

function get_nomenclador ($datos_info,$tipo_control,$fecha_control,$edad_anios,$semana_gestacion) {

  if ($fecha_control<='2023-05-31') $id_nomenclador_actual = 20;
    else $id_nomenclador_actual = 21; 
    
  $code_snomed=intval($datos_info['snomed_code']);
  $descripcion=$datos_info['descripcion'];

  $codigos_embarazadas = array (185349003);//SnomedCodeProced
  $codigos_controles =  array (185349003);//SnomedCodeProced

  //$codigos_embarazadas = array (77386006,271887000,87527008,57630001,127365008,59466002,41587001,42814007);
  //$codigos_sobrepeso_obsedidad = array (238131007,414916001,248342006,262285001,249533007,83911000119104);
  //$codigos_controles = array (102500002,162651007,305830005,102506008,305827003,102509001,243788004,43664005);
  
  if ($fecha_control!='') {
    $id_nomenclador_detalle=get_id_nomenclador_detalle($fecha_control);
  };
  
	
  if ($tipo_control=='Embarazadas' or $descripcion=='EMBARAZO') {
    if (in_array($code_snomed, $codigos_embarazadas)) {
      if ($semana_gestacion<13) {
        $codigo_nomen='C005';        
        $descripcion_nomen='Control de embarazo < a 13 semanas';
        } else {
          $codigo_nomen='C006';        
          $descripcion_nomen='Control de embarazo (desde semana 13)';
        }
      $diagnostico_nomen='W78';       
    }
  }

  if ($tipo_control=='Niños de 0 a 5' and ($edad_anios<1)) {
    if (in_array($code_snomed, $codigos_controles)) {
      $grupopoblacional='A';
      $codigo_nomen='C001';
      $diagnostico_nomen='A97';
      $descripcion_nomen='Examen periódico de salud';
    }
  }

  if ($tipo_control=='Niños de 0 a 5' and ($edad_anios>=1)) {
    if (in_array($code_snomed, $codigos_controles)) {
      $grupopoblacional='A';
      $codigo_nomen='C001';
      $diagnostico_nomen='A97';
      $descripcion_nomen='Examen periódico de salud';
    }                                     
  }

  if ($tipo_control=='Niños de 6 a 9') {
    if (in_array($code_snomed, $codigos_controles)) {
      $grupopoblacional='B';
      $codigo_nomen='C001';
      $diagnostico_nomen='A97'; 
      $descripcion_nomen='Examen periódico de salud';
    }       
  }

  if ($tipo_control=='Adolescentes') {
    if (in_array($code_snomed, $codigos_controles)) {
      $grupopoblacional='C';
      $codigo_nomen='C001';
      $diagnostico_nomen='A97';
      $descripcion_nomen='Examen periódico de salud';
    };    
  }
      
  if ($tipo_control=='Adultos de 20 a 64' and $descripcion<>'EMBARAZO') {
    if (in_array($code_snomed, $codigos_controles)) {
      $grupopoblacional='D';
      $codigo_nomen='C001';
      $diagnostico_nomen='A97';
      $descripcion_nomen='Examen periódico de salud';
    }
  }

  /*switch ($descripcion) {
    case 'HIPERTENSION ARTERIAL': {
      $codigo_nomen='C074';
      $diagnostico_nomen='K86';
      $descripcion_nomen='Consulta de detección y seguimiento de HTA';
      };break;        
    
    case 'DIABETES GESTACIONAL': {
          $codigo_nomen=$res_query->fields['codigo_sumar'];
          $diagnostico_nomen=$res_query->fields['diagnostico_sumar'];
          $descripcion_nomen=$res_query->fields['descripcion'];
        };break;

    default:break;
  }*/


  $q="SELECT * from facturacion.anexo
        where prueba='No Corresponde' and id_nomenclador_detalle=$id_nomenclador_detalle";
  $res_nom=sql($q,"Error en traer el id_anexo") or fin_pagina();
  $anexo=$res_nom->fields['id_anexo'];

	$query="SELECT * from facturacion.nomenclador 
      where id_nomenclador_detalle=$id_nomenclador_detalle 
			and codigo='$codigo_nomen' 
			and descripcion='$descripcion_nomen'";

	$res_query=sql($query) or fin_pagina();
	if ($res_query->recordcount()>0) {
		$nomenclador=array ( 
			'id_nomenclador' => $res_query->fields['id_nomenclador'],
			'grupo' => $res_query->fields['grupo'],
			'codigo' => $res_query->fields['codigo'],
			'desc' => $res_query->fields['descripcion'],
			'diagnostico' => $diagnostico_nomen,
			'grupopoblacional' => $grupopoblacional,
			'id_nomenclador_detalle' => $id_nomenclador_detalle,
			'id_anexo' => $anexo,
      'precio' => $res_query->fields['precio'],
      'ok' => true,
      'query' => $query,
		);
		return $nomenclador;
	} else return $nomenclador=array (
                'ok' => false,
                'msg' => 'Codigo nomenclador no encontrado',
                'query' => $query
                );
}

function datos_completos ($tipo_control,$paciente,$datos_reportables) {

  switch ($tipo_control) {
     case 'Embarazadas': {
        /*echo 'dni=>'.$paciente['afidni'].'</br>';
        echo 'peso=>'.$datos_reportables['peso'] .'</br>';
        echo 'ta=>'.$datos_reportables['ta'] .'</br>';
        echo 'eg=>'.$datos_reportables['eg'] .'</br>';
        echo 'fum=>'.$datos_reportables['fum'] .'</br>';
        echo 'fpp=>'.$datos_reportables['fpp'].'</br></br>';*/
      
        if ($datos_reportables['peso'] <> NULL
        	and $datos_reportables['ta'] <> NULL
        	and $datos_reportables['eg'] <> NULL
        	and $datos_reportables['fum'] <> NULL
        	and $datos_reportables['fpp'] <> NULL) return true;
        else return false;
      };break;
     
     case 'Niños de 0 a 5': {
        if ($paciente['edad_anios']<1) {
          if ($datos_reportables['peso'] 
          	and $datos_reportables['talla'] 
          	and $datos_reportables['imc'] 
          	and $datos_reportables['perimetro_cefalico']) return true;
            else return false;
        } else {
          if ($datos_reportables['peso'] 
          	and $datos_reportables['talla'] 
          	and $datos_reportables['imc']) return true;
            else return false;
        } 
      };break;

      case 'Niños de 6 a 9': {
        if ($datos_reportables['peso'] 
        	and $datos_reportables['talla'] 
        	and $datos_reportables['imc'] 
        	and $datos_reportables['ta']) return true;
        else return false;
      };break;

      case 'Adolescentes': {
        if ($datos_reportables['peso'] 
        	and $datos_reportables['talla'] 
        	and $datos_reportables['imc'] 
        	and $datos_reportables['ta']) return true;
        else return false;
      };break;

      case 'Adultos de 20 a 64': {
        if ($datos_reportables['peso'] 
        	and $datos_reportables['talla'] 
        	and $datos_reportables['imc'] 
        	and $datos_reportables['ta']) return true;
        else return false;
      };break;

      default: return false; break;
   } 
}

function insertar_comprobante ($cuie,$datos_info,$paciente,$comentario,$datos_nomenclador) {
  
  $fecha_carga=date('Y-m-d');
  $fecha_pcontrol='01-01-1800';
  $usuario=$_ses_user['name'];

  $q="SELECT nextval('comprobante_id_comprobante_seq') as id_comprobante";
  $id_comprobante=sql($q) or fin_pagina();
  $id_comprobante=$id_comprobante->fields['id_comprobante'];  

  $nom_medico=$datos_info['nom_medico'];
  $fecha_control=$datos_info['fecha_control'];
  $clavebeneficiario=$paciente['clave_beneficiarios'];//revisar
  $id_smiafiliados=$paciente['id_smiafiliados'];
  $grupopoblacional=$paciente['grupopoblacional'];            
  $periodo= str_replace("-","/",substr($fecha_control,0,7));
  $msg_duplicados=duplicado_fact_migracion($datos_nomenclador['id_nomenclador'],$paciente['id_smiafiliados'],$fecha_control);
  $msg_uso_nomenclador=valida_prestacion_nuevo_nomenclador1($id_comprobante,$datos_nomenclador,$paciente,$fecha_control);
                    
  if ($msg_duplicados===1 and $msg_uso_nomenclador===1) {

	  $query="INSERT into facturacion.comprobante
	        (id_comprobante, cuie, nombre_medico, fecha_comprobante, clavebeneficiario, id_smiafiliados, fecha_carga,periodo,comentario,id_servicio,activo,grupo_etareo)
	        values
	        ($id_comprobante,'$cuie','$nom_medico','$fecha_control','$clavebeneficiario', $id_smiafiliados,'$fecha_carga',
	        '$periodo','$comentario','1','S','$grupopoblacional')"; 

	  sql($query, "Error al insertar el comprobante") or fin_pagina();      
	     
	  $q="SELECT nextval('facturacion.log_comprobante_id_log_comprobante_seq') as id_comprobante";
	  $id_log_comprobante=sql($q) or fin_pagina();
	  $id_log_comprobante=$id_log_comprobante->fields['id_comprobante'];

	  $log="INSERT into facturacion.log_comprobante 
	         (id_log_comprobante,id_comprobante, fecha, tipo, descripcion, usuario) 
	         values ($id_log_comprobante,$id_comprobante, '$fecha_carga','$comentario','Nro. Comprobante $id_comprobante', '$usuario')";
	  sql($log) or fin_pagina(); 
	  
	  $dato_comprobante = array (
            'id_comprobante' => $id_comprobante,
            'ok' => true
            );
    return $dato_comprobante;
  	
    } else {
            $dato_comprobante = array (
              'msg_duplicado' => $msg_duplicados,
              'msg_uso_nomenclador' => $msg_uso_nomenclador,
              'ok' => false
              );
            return $dato_comprobante;}
}

function insertar_prestacion ($id_comprobante,$cuie,$paciente,$datos_nomenclador,$datos_info,$datos_reportables,$comentario) {
  
  $usuario=$_ses_user['name'];
  $fecha_carga=date("Y-m-d H:i:s");
  $sexo_codigo=($paciente['sexo']=='M') ? 'V' : 'M';

  $nomenclador=$datos_nomenclador['id_nomenclador'];
  $anexo=$datos_nomenclador['id_anexo'];
  $diagnostico=$datos_nomenclador['diagnostico'];

  $profesional="P99";
  $fecha_nacimiento_cod=str_replace('-','',$paciente['afifechanac']);
  

  $fecha_nacimiento=$paciente['afifechanac'];
  $anios_nac=$paciente['edad_anios'];
  $meses_nac=$paciente['edad_meses'];
  $dias_nac=$paciente['edad_dias'];
  $fecha_control=$datos_info['fecha_control'];

  $fecha_comprobante_cod=substr(str_replace('-','',$fecha_control),0,8);

  $peso=$datos_reportables['peso'];
  $tension_arterial=$datos_reportables['ta'];
  $talla=($datos_reportables['talla'])?$datos_reportables['talla']:0;
  $talla=($talla>2.5)? $talla=($talla/100) : $talla;
  $edad_gestacional = ($datos_reportables['eg'])?$datos_reportables['eg']:-1;
  
  $codigo=$cuie.$fecha_comprobante_cod.$paciente['clave_beneficiario'].$fecha_nacimiento_cod.$sexo_codigo.$paciente['edad_anios'].$paciente['grupopoblacional'].$datos_nomenclador['id_nomenclador'].$datos_nomenclador['diagnostico'];//.$datos_info['nom_medico'];
    
	$q="select nextval('facturacion.prestacion_id_prestacion_seq') as id_prestacion";
	$id_prestacion=sql($q) or fin_pagina();
	$id_prestacion=$id_prestacion->fields['id_prestacion'];

	$precio_prestacion=$datos_nomenclador['precio'];
	    
	$query="INSERT into facturacion.prestacion
	       (id_prestacion,id_comprobante, id_nomenclador,codigo_comp,fecha_nacimiento,edad,sexo,fecha_prestacion,anio,mes,dia,cantidad,precio_prestacion,id_anexo,peso,tension_arterial,talla,diagnostico,edad_gestacional,estado_envio)
	       values
	       ($id_prestacion,$id_comprobante,$nomenclador,'$codigo','$fecha_nacimiento','$anios_nac','$sexo_codigo','$fecha_control',$anios_nac,$meses_nac,$dias_nac,1,$precio_prestacion,$anexo,$peso,'$tension_arterial',$talla,'$diagnostico',$edad_gestacional,'n')";
	           
	sql($query, "Error al insertar la prestacion") or fin_pagina();
	      
	$log="INSERT into facturacion.log_prestacion
	             (id_prestacion, fecha, tipo, descripcion, usuario) 
	        values ($id_prestacion, '$fecha_carga','$comentario','Nro. prestacion $id_prestacion', '$usuario')";
	sql($log) or fin_pagina();
  
  return $id_prestacion;
}

function insertar_trazadora($tipo_control,$paciente,$datos_info,$cuie,$datos_reportables,$comentario) {
  
  $usuario=$_ses_user['name'];
  $fecha_carga=date("Y-m-d H:i:s");
  $fecha_control=$datos_info['fecha_control'];
 
  $sexo=$paciente['sexo'];
  $clavebeneficiario=$paciente['clavebeneficiario'];
  $afifechanac=$paciente['afifechanac'];
  $clase_doc=$paciente['clase_doc'];
  $tipo_doc=$paciente['tipo_doc'];
  $dni=$paciente['afidni'];
  $nombre=$paciente['nombre'];
  $apellido=$paciente['apellido'];
  $edad_anios=$paciente['edad_anios'];
  
  $peso=$datos_reportables['peso'];
  $talla=$datos_reportables['talla'];
  $talla=($talla>2.5)? $talla=round($talla/100) : $talla;
  $eg=$datos_reportables['eg'];
  $fum=$datos_reportables['fum'];
  $fpp=$datos_reportables['fpp'];
  $ta=$datos_reportables['ta'];
  $perimetro_cefalico=$datos_reportables['perimetro_cefalico'];
  $imc=$datos_reportables['imc'];

 if ($paciente['tipo_benef']=='na') {$id_beneficiarios=0; $id_smiafiliados=$paciente['id_smiafiliados'];}
    else {$id_smiafiliados=0; $id_beneficiarios=$paciente['id_smiafiliados'];}
  
  switch ($tipo_control) {
    
    case 'Embarazadas': {
      //Cargar datos a trazadoras 1 o trazadora 2 (ulterior)
      $tabla='trazadorassps.trazadora_1';
      $query1="SELECT id_trz1 from $tabla
              where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios) 
              and fecha_control_prenatal='$fecha_control'";
      $res_duplicados_trz1 = sql($query1) or fin_pagina();

      if ($res_duplicados_trz1->recordcount()==0) {
        $query="SELECT * from $tabla 
                where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios)
                AND fecha_control_prenatal between '$fecha_control'::date -(round($eg)::integer *7) and '$fecha_control'";
        
        $res_query=sql($query) or fin_pagina();
        
        if ($res_query->recordcount()==0) {
          $q="SELECT nextval('trazadorassps.seq_id_trz1') as id_planilla";
          $id_planilla=sql($q) or fin_pagina();
          $id_planilla=$id_planilla->fields['id_planilla'];
          $query="INSERT into trazadorassps.trazadora_1
                       (id_trz1,
                        cuie,
                        fecha_control_prenatal ,
                        edad_gestacional,
                        fecha_carga,
                        usuario,
                        comentario,
                        id_smiafiliados,
                        id_beneficiarios,
                        fum,
                        fpp,
                        peso,
                        ta
                       )
                       values
                       ($id_planilla,
                        '$cuie',
                        '$fecha_control',
                        $eg,
                        '$fecha_carga',
                        '$usuario',
                        '$comentario',
                        $id_smiafiliados,
                        $id_beneficiarios,
                        '$fum',
                        '$fpp',
                        $peso,
                        '$ta')";
          sql($query, "Error al insertar la Planilla") or fin_pagina();
          $trazadora=array (
            'tabla' => $tabla,
            'id_trazadora' => $id_planilla
            );
          return $trazadora;
          
        } else {
          $tabla='trazadorassps.trazadora_2';
          
          $query1="SELECT id_trz2 from $tabla
              where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios)
              and fecha_control='$fecha_control'";
          $res_duplicados_trz2 = sql($query1) or fin_pagina();
          if ($res_duplicados_trz2->recordcount()==0) {
            $q="SELECT nextval('trazadorassps.seq_id_trz2') as id_planilla";
            $id_planilla=sql($q) or fin_pagina();
            $id_planilla=$id_planilla->fields['id_planilla'];

            $query="INSERT into $tabla
                   (id_trz2,
                   cuie,
                   fecha_control,
                   edad_gestacional,
                   peso,
                   fecha_carga,
                   usuario,
                   comentario,
                   id_smiafiliados,
                   id_beneficiarios,
                   tension_arterial)
                   values
                   ($id_planilla,
                    '$cuie',
                    '$fecha_control',
                    $eg,
                    $peso,
                    '$fecha_carga',
                    '$usuario',
                    '$comentario',
                    $id_smiafiliados,
                    $id_beneficiarios,
                    '$ta')";
            sql($query, "Error al insertar la Planilla") or fin_pagina();
            $trazadora=array (
              'tabla' => $tabla,
              'id_trazadora' => $id_planilla
            );
            return $trazadora;
          } else {
              $trazadora=array (
              'tabla' => $tabla,
              'id_trazadora' => $res_duplicados_trz2->fields['id_trz2']
              );
              return $trazadora;
            } 
        }
      } else {
          $trazadora=array (
            'tabla' => $tabla,
            'id_trazadora' => $res_duplicados_trz1->fields['id_trz1']
            );
          return $trazadora;
        }

    };break;

  case 'Niños de 0 a 5': {
      //Cargar datos a trazadoras 4 o 7 segun la edad
      if ($edad_anios < 1) {
        $q="SELECT nextval('trazadorassps.seq_id_trz4') as id_planilla";
        $id_planilla=sql($q) or fin_pagina();
        $id_planilla=$id_planilla->fields['id_planilla'];
        $tabla='trazadorassps.trazadora_4';
        
        //reviso si ya esta ingresado el registro
        $query1="SELECT id_trz4 from $tabla
                where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios)
                and fecha_control='$fecha_control'";
        $res_duplicados = sql($query1) or fin_pagina();
              
        if ($res_duplicados->recordcount()==0){
          
          $dias=$paciente['edad_dias']+($paciente['edad_meses']*30);
          if ($peso!=0) $percen_peso_edad=calculo_percentilo_peso($dias,$sexo,$peso);

          $query="INSERT into $tabla
                 (id_trz4,
                  cuie,
                  fecha_nac,
                  fecha_control,
                  peso,
                  talla,
                  perimetro_cefalico,
                  percentilo_peso_edad,
                  percentilo_talla_edad,
                  percentilo_perim_cefalico_edad,
                  percentilo_peso_talla,
                  fecha_carga,
                  usuario,
                  comentario,
                  id_smiafiliados,
                  id_beneficiarios
                 )
                 values
                 ($id_planilla,
                 '$cuie',
                 '$afifechanac',
                 '$fecha_control',
                  $peso,
                  $talla,
                  $perimetro_cefalico,
                  $percen_peso_edad,
                  's/d',
                  's/d',
                  's/d',      
                  '$fecha_carga',
                  '$usuario',
                  '$comentario',
                  $id_smiafiliados,
                  $id_beneficiarios
                  )";
          sql($query, "Error al insertar la Planilla") or fin_pagina();
        }
            
      } else {
        $q="SELECT nextval('trazadorassps.seq_id_trz7') as id_planilla";
        $id_planilla=sql($q) or fin_pagina();
        $id_planilla=$id_planilla->fields['id_planilla'];

        $tabla='trazadorassps.trazadora_7';

        //reviso si ya esta ingresado el registro
        $query1="SELECT id_trz7 from $tabla
                where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios)
                and fecha_control='$fecha_control'";
        $res_duplicados = sql($query1) or fin_pagina();
              
        if ($res_duplicados->recordcount()==0){
           
          if ($paciente['edad_anios']>=1 and $paciente['edad_anios']<=5) {
            $meses=$paciente['edad_meses']+($paciente['edad_anios']*12);
            $dias=$paciente['edad_dias']+($meses*30);
            if ($peso!=0) $percen_peso_edad=calculo_percentilo_peso($dias,$sexo,$peso); 
              else $percen_peso_edad='Sin Datos';
            if ($imc!=0) $percen_imc_edad=calculo_percentilo_imc($meses,$sexo,$imc);
              else $percen_imc_edad='Sin Datos';
            if ($talla!=0) $percen_talla_edad=calculo_percentilo_talla($dias,$sexo,($talla*100));
              else $percen_talla_edad='Sin Datos';

          } else {
            $percen_peso_edad='Sin Datos';
            $percen_talla_edad='Sin Datos';
            $percen_peso_edad='Sin Datos';
            $percen_imc_edad='Sin Datos';
          }


          $query="INSERT into $tabla
                 (id_trz7,
                  cuie,
                  fecha_nac,
                  fecha_control,
                  peso,
                  talla,
                  percentilo_peso_edad,
                  percentilo_talla_edad,
                  percentilo_peso_talla,
                  fecha_carga,
                  usuario,
                  comentario,
                  id_smiafiliados,
                  id_beneficiarios,
                  tension_arterial,
                  percen_imc_edad,
                  imc
                 )
                 values
                 ($id_planilla,
                 '$cuie',
                 '$afifechanac',
                 '$fecha_control',
                 $peso,
                 $talla,
                 '$percen_peso_edad',
                 '$percen_talla_edad',
                 '$percen_peso_edad',
                 '$fecha_carga',
                 '$usuario',
                 '$comentario',
                 $id_smiafiliados,
                 $id_beneficiarios,
                 '$ta',
                 '$percen_imc_edad',
                 $imc
                  )";
          sql($query, "Error al insertar la Planilla") or fin_pagina();
        }
      }
    $trazadora=array (
      'tabla' => $tabla,
      'id_trazadora' => $id_planilla
    );
    return $trazadora;
    };break;

  case 'Niños de 6 a 9': {
      //Cargar datos a trazadoras 7
      $q="SELECT nextval('trazadorassps.seq_id_trz7') as id_planilla";
      $id_planilla=sql($q) or fin_pagina();
      $id_planilla=$id_planilla->fields['id_planilla'];

      $tabla='trazadorassps.trazadora_7';
        
      //reviso si ya esta ingresado el registro
      $query1="SELECT id_trz7 from $tabla
              where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios)
              and fecha_control='$fecha_control'";
      $res_duplicados = sql($query1) or fin_pagina();
            
      if ($res_duplicados->recordcount()==0){
        
        if ($paciente['edad_anios']>5 and $paciente['edad_anios']<=19) {
          $meses=$paciente['edad_meses']+($paciente['edad_aniosedad_']*12);
          if ($imc!=0) $percen_imc_edad=calculo_percentilo_imc($meses,$sexo,$imc);
            else $percen_imc_edad='Sin Datos';
          if ($talla!=0) $percen_talla_edad=calculo_percentilo_talla($meses,$sexo,($talla*100));
            else $percen_talla_edad='Sin Datos';
        } else {
          $percen_imc_edad='Sin Datos';
          $percen_talla_edad='Sin Datos';
          }
        $query="INSERT into $tabla
              (id_trz7,
                cuie,
                fecha_nac,
                fecha_control,
                peso,
                talla,
                percentilo_peso_edad,
                percentilo_talla_edad,
                percentilo_peso_talla,
                fecha_carga,
                usuario,
                comentario,
                id_smiafiliados,
                id_beneficiarios,
                tension_arterial,
                percen_imc_edad,
                imc
               )
               values
               ($id_planilla,
               '$cuie',
               '$afifechanac',
               '$fecha_control',
               $peso,
               $talla,
               '$percen_peso_edad',
               '$percen_talla_edad',
               '$percen_peso_edad',
               '$fecha_carga',
               '$usuario',
               '$comentario',
               $id_smiafiliados,
               $id_beneficiarios,
               '$ta',
               '$percen_imc_edad',
               $imc
                )";
        sql($query, "Error al insertar la Planilla") or fin_pagina();
      }
       
      $trazadora=array (
        'tabla' => $tabla,
        'id_trazadora' => $id_planilla
      );
      return $trazadora;
    };break;

  case 'Adolescentes': {
      //Cargar datos a trazadoras 10
      $q="SELECT nextval('trazadorassps.seq_id_trz10') as id_planilla";
      $id_planilla=sql($q) or fin_pagina();
      $id_planilla=$id_planilla->fields['id_planilla'];

      $tabla='trazadorassps.trazadora_10';
      
      //reviso si ya esta ingresado el registro
      $query1="SELECT id_trz10 from $tabla
              where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios)
              and fecha_control='$fecha_control'";
      $res_duplicados = sql($query1) or fin_pagina();
            
      if ($res_duplicados->recordcount()==0){
        if ($tipo_benef='na') {$id_beneficiarios=0;}
          else {$id_smiafiliados=0;}
        if ($paciente['edad_anios']>5 and $paciente['edad_anios']<=19) {
          $meses=$paciente['edad_meses']+($paciente['edad_anios']*12);
          if ($imc!=0) $percen_imc_edad=calculo_percentilo_imc($meses,$sexo,$imc);
            else $percen_imc_edad='Sin Datos';
          if ($talla!=0) $percen_talla_edad=calculo_percentilo_talla($meses,$sexo,($talla*100));
            else $percen_talla_edad='Sin Datos';
        } else {
          $percen_imc_edad='Sin Datos';
          $percen_talla_edad='Sin Datos';
        }

        $query="INSERT into $tabla
               (id_trz10,
                cuie, 
                fecha_nac,
                fecha_control,
                talla,
                peso,
                fecha_carga,
                usuario,
                comentario,
                id_smiafiliados,
                id_beneficiarios,
                tension_arterial,
                percen_imc_edad,
                percentilo_talla_edad,
                imc)
               values
               ($id_planilla,
               '$cuie',
               '$afifechanac',
               '$fecha_control',
               $talla,
               $peso,
               '$fecha_carga',
               '$usuario',
               '$comentario',
               $id_smiafiliados,
               $id_beneficiarios,
               '$ta',
               '$percen_imc_edad',
               '$percen_talla_edad',
               $imc
                )";
        sql($query, "Error al insertar la Planilla") or fin_pagina();
      }
      $trazadora=array (
        'tabla' => $tabla,
        'id_trazadora' => $id_planilla
      );
      return $trazadora;
    };break;

  case 'Adultos de 20 a 64': {
      //Cargar datos a trazadoras Adulto
      $q="SELECT nextval('trazadoras.adultos_id_adulto_seq') as id_planilla";
      $id_planilla=sql($q) or fin_pagina();
      $id_planilla=$id_planilla->fields['id_planilla'];

      $tabla='trazadoras.adultos';
      
      //reviso si ya esta ingresado el registro
      $query1="SELECT id_adulto from $tabla
              where clave='$clavebeneficiario' and fecha_control='$fecha_control'";
      $res_duplicados = sql($query1) or fin_pagina();
            
      if ($res_duplicados->recordcount()==0){
        
        $query="INSERT into $tabla
               (id_adulto,
               cuie,
               clave,
               clase_doc,
               tipo_doc,
               num_doc,
               apellido,
               nombre,
               fecha_nac,
               fecha_control,
               peso,
               talla,
               imc,
               observaciones,
               fecha_carga,
               usuario,
               ta)
               values
               ($id_planilla,
               '$cuie',
               '$clavebeneficiario',
               '$clase_doc',
               '$tipo_doc',
               '$dni',
               '$apellido',
               '$nombre',
               '$afifechanac',
               '$fecha_control',
               '$peso',
                $talla,
               '$imc',
               '$comentario',
               '$fecha_carga',
               '$usuario',
               '$ta')";
        sql($query, "Error al insertar la Planilla") or fin_pagina();
       }
       $trazadora=array (
        'tabla' => $tabla,
        'id_trazadora' => $id_planilla
      );
      return $trazadora;
    };break;
  
  default:break;
}
}


function insertar_fichero ($cuie,$paciente,$datos_info,$datos_reportables,$comentario) {
  
    $usuario=$_ses_user['name'];
    $fecha_carga=date("Y-m-d H:i:s");
    $fecha_pcontrol='1800-01-01';

   	$id_smiafiliados=$paciente['id_smiafiliados'];
   	$clavebeneficiario=$paciente['clavebeneficiario'];

    $fecha_control=$datos_info['fecha_control'];
    $nom_medico=$datos_info['nom_medico'];

    $peso=$datos_reportables['peso'];
    $talla=($datos_reportables['talla'])?$datos_reportables['talla']:0;
    $talla=($talla>2.5)? $talla=round($talla/100) : $talla;
    $imc=($datos_reportables['imc'])?$datos_reportables['imc']:0;
    $tension_arterial=$datos_reportables['ta'];

    $periodo= str_replace("-","/",substr($fecha_control,0,7));
    
    $update_f="UPDATE fichero.fichero set fecha_pcontrol_flag='0' where id_smiafiliados='$id_smiafiliados'";
    sql($update_f, "No se puede actualizar los registros") or fin_pagina(); 

    $q="select nextval('fichero.fichero_id_fichero_seq') as id_fichero";
    $id_fichero=sql($q) or fin_pagina();
    $id_fichero=$id_fichero->fields['id_fichero'];  
            
    //$fecha_pcontrol=Fecha_db($fecha_pcontrol);
    $tunner=0; 
           
    $q="SELECT * from trazadoras.clasificacion_remediar2 where clave_beneficiario='$clavebeneficiario'";
    $res_q=sql($q) or fin_pagina();
    $diabetico=trim($res_q->fields['diabetico']);
    $hipertenso=trim($res_q->fields['hipertenso']);


    $query="INSERT into fichero.fichero
                   (id_fichero,cuie,nom_medico,fecha_control,comentario,periodo,peso,talla, 
                   imc,ta,tunner,id_smiafiliados,id_beneficiarios,fecha_pcontrol,diabetico,hipertenso)
            values
            ($id_fichero, '$cuie', '$nom_medico', '$fecha_control','$comentario','$periodo','$peso','$talla','$imc', '$tension_arterial', '$tunner','$id_smiafiliados',0,'$fecha_pcontrol','$diabetico','$hipertenso')";  

    $query1="SELECT id_fichero from fichero.fichero 
              where id_smiafiliados='$id_smiafiliados' and fecha_control='$fecha_control'";
    $res_duplicados = sql($query1, "Error al insertar la Planilla") or fin_pagina();

    if ($res_duplicados->recordcount()==0){
        sql($query, "Error al insertar el comprobante") or fin_pagina();
        
        $log="INSERT into fichero.log_fichero 
             (id_fichero, fecha, tipo, descripcion, usuario) 
             values ($id_fichero, '$fecha_carga','$comentario','Nro. fichero $id_fichero', '$usuario')";
        sql($log) or fin_pagina();  
        
        return $id_fichero;  
      } else return $res_duplicados->fields['id_fichero'];//return false;
}

function reporte_faltan_datos_reportables($consulta,$especialidad,$dni,$datos_reportables,$fecha_control) {
	$filename = "reporte_faltan_datos_reportables-00001.txt";
			
	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
    
  $contenido ='-----------DATOS REp.------------------'."\n\r";
	$contenido .= 'DNI => '.$dni."\n\r";
	$contenido .='Consulta =>'.$consulta."\n\r";
	$contenido .='Especialidad =>'.$especialidad."\n\r";
	$contenido .='Fecha Control => '.$fecha_control."\n\r";
	$contenido .='peso => '.$datos_reportables['peso']."\n\r";
	$contenido .='talla => '.$datos_reportables['talla']."\n\r";
	$contenido .='IMC => '.$datos_reportables['imc']."\n\r";
	$contenido .='Perimetro Cefal. => '.$datos_reportables['perimetro_cefalico']."\n\r";
	$contenido .='ta => '.$datos_reportables['ta']."\n\r";
	$contenido .='EG => '.$datos_reportables['eg']."\n\r";
	$contenido .='FUM => '.$$datos_reportables['fum']."\n\r";
	$contenido .='FPP => '.$datos_reportables['fpp']."\n\r";
	$contenido .='-----------END.------------------'."\n\r";
    $contenido .="\n\r";
	if (fwrite($handle, $contenido) === FALSE) {
		echo "No se Puede escribir  ($filename)";
		exit;
	}
	echo "El Archivo ($filename) se genero con exito";
	fclose($handle);
}

function reporte_no_encuentra_codigo_facturable($codigo_snomed) {
	$filename = "reporte_no_encuentra_codigo_facturable-00001.txt";
			
	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
    $contenido=$codigo_snomed."\n\r";
	if (fwrite($handle, $contenido) === FALSE) {
		echo "No se Puede escribir  ($filename)";
		exit;
	}
	echo "El Archivo ($filename) se genero con exito";
	fclose($handle);
}

function reporte_paciente_no_encontrado_o_inactivo($afi) {
	$filename = "reporte_paciente_no_encontrado_o_inactivo-00001.txt";
			
	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
  $contenido ='-----------Beneficiario------------------'."\n\r";
  $contenido.='DNI => '.$afi{'document'}."\n\r";
  $contenido.='Nombre => '.$afi{'firstName'}."\n\r";
	$contenido.='Apellido => '.$afi{'lastName'}."\n\r";
	$contenido.='Fecha Nac. => '.$afi{'birthDate'}."\n\r";
	$contenido.='sexo '.$afi{'sex'}."\n\r";
	$contenido.='Direccion => '.$afi{'address'}."\n\r";
	$contenido.='Ciudad => '.$afi{'city'}."\n\r";
	$contenido.='Celular => '.$afi{'phone'}."\n\r"; 
	$contenido ='---------------END.----------------------'."\n\r";   
    $contenido.="\n\r";
	if (fwrite($handle, $contenido) === FALSE) {
		echo "No se Puede escribir  ($filename)";
		exit;
	}
	echo "El Archivo ($filename) se genero con exito";
	fclose($handle);
}

function reporte_efector_no_encontrado($efe) {
	$filename = "reporte_efector_no_encontrado-00001.txt";
			
	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
    $contenido='Codigo => '.$efe{'code'}."\n\r";
    $contenido.='Nombre => '.$efe{'name'}."\n\r";
    $contenido.='------------------------'."\n\r";
	$contenido.="\n\r";
	if (fwrite($handle, $contenido) === FALSE) {
		echo "No se Puede escribir  ($filename)";
		exit;
	}
	echo "El Archivo ($filename) se genero con exito";
	fclose($handle);
}

function facturar($cuie,$datos_info,$paciente,$comentario,$datos_nomenclador,$tipo_control,$datos_reportables) {

  $id_comprobante = insertar_comprobante ($cuie,$datos_info,$paciente,$comentario,$datos_nomenclador);
  if (is_int($id_comprobante)) {
      $id_prestacion = insertar_prestacion($id_comprobante,$cuie,$paciente,$datos_nomenclador,$datos_info,$datos_reportables,$comentario);
      $id_trazadora = insertar_trazadora($tipo_control,$paciente,$datos_info,$cuie,$datos_reportables,$comentario);
      $id_fichero = insertar_fichero ($cuie,$paciente,$datos_info,$datos_reportables,$comentario);
      $id = array (
        'id_comprobante' => $id_comprobante,
        'id_prestacion' => $id_prestacion,
        'id_trazadora' => $id_trazadora,
        'id_fichero' => $id_fichero,
        'msg' => 'OK'
      );
      return $id;
  } else { 
    $id = array (
      'id_comprobante' => 0,
      'id_prestacion' => 0,
      'id_trazadora' => 0,
      'id_fichero' => 0,
      'msg' =>  $id_comprobante
    );
    return $id;
    }
}

function inserta_en_tabla_aux($nombre_archivo,$periodo,$datos_aux,$tipo_control,$paciente,$datos_reportables,$datos_nomenclador,$datos_info) {

  $fecha_carga = date("Y-m-d H:i:s");
  $afidni=$datos_aux['dni'];
  $nombre=str_replace("'","",$paciente['nombre']);
  $apellido=str_replace("'","",$paciente['apellido']);
  $afifechanac=($paciente['afifechanac']<>'' or $paciente['afifechanac']<>null )?$paciente['afifechanac']:'1800-01-01';
  $tipo_benef=($paciente['tipo_benef']=='na' or $paciente['tipo_benef']=='nu')?$paciente['tipo_benef']:'Benef.Inexistente';
  $activo=$paciente['activo'];
  $cuie=$datos_aux['cuie'];
  $fecha_control=($datos_aux['fecha_control']<>'')?$datos_aux['fecha_control']:'1800-01-01' ;
  $peso=($datos_reportables['peso'])?$datos_reportables['peso']:0;
  $talla=($datos_reportables['talla'])?$datos_reportables['talla']:0;
  $ta=$datos_reportables['ta'];
  $semana_gestacion=($datos_reportables['eg'])?$datos_reportables['eg']:0;
  $fum=($datos_reportables['fum']<>'')?$datos_reportables['fum']:'1800-01-01';
  $fpp=($datos_reportables['fpp']<>'')?$datos_reportables['fpp']:'1800-01-01';
  $perim_cefalico=($datos_reportables['perimetro_cefalico'])?$datos_reportables['perimetro_cefalico']:0;
  $imc=($datos_reportables['imc'])?$datos_reportables['imc']:0;
  $code_snomed=($datos_info['snomed_code'])?$datos_info['snomed_code']:'Sin Codigo Snomed';
  $descripcion=$datos_info['descripcion'];
  $codigo_fact=$datos_nomenclador['grupo'].$datos_nomenclador['codigo'].$datos_nomenclador['diagnostico'];
  $datos_completos=$datos_aux['datos_completos'];
  $es_fact=$datos_aux['es_fact'];
  $msg_rep=$datos_aux['msg_rep'];
  $r_query=$datos_aux['r_query'];

  $id="SELECT nextval('facturacion.resumen_hc_id_resumen_hc_seq') as id_resumen_hc";
	$id_res=sql($id) or fin_pagina();
  $id_resumen_hc = $id_res->fields['id_resumen_hc'];
  //da errores $r_query (reviar)
  $query = "INSERT INTO 
        facturacion.resumen_hc
        (id_resumen_hc,tipo_control,dni,nombre,apellido,fecha_nac,tipo_benef,activo,cuie,fecha_control,
          peso,talla,ta,semana_gestacion,fum,fpp,perim_cefalico,imc,
          code_snomed,descripcion,codigo_fact,datos_comp,es_fact,msg_rep,log,fecha_carga,periodo,nombre_archivo)
        VALUES ($id_resumen_hc,
          '$tipo_control',
          '$afidni',
          '$nombre',
          '$apellido',
          '$afifechanac',
          '$tipo_benef',
          '$activo',
          '$cuie',
          '$fecha_control',
          $peso,
          $talla,
          '$ta',
          $semana_gestacion,
          '$fum',
          '$fpp',
          $perim_cefalico,
          $imc,
          '$code_snomed',
          '$descripcion',
          '$codigo_fact',
          '$datos_completos',
          '$es_fact',
          '$msg_rep',
          '$r_query',
          '$fecha_carga',
          $periodo,
          '$nombre_archivo')";

    sql($query,"Error al tratar de ingresar el registro") or fin_pagina();

}
?>