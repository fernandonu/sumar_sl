<?

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

function nomenclador_vacuna($id_vac_apli,$grupopoblacional,$afitipocategoria,$id_nomenclador_detalle,$afisexo)

{
  $param_nomen=array ('id' => -1,'anexo' => -1);
  
  //----------------------------------------|
      //   GRUPOS                               |
      //  A --------> NIÑOS 0 A 5 AÑOS          |
      //  B --------> NIÑOS 6 A 9 AÑOS          |
      //  C --------> ADOLESCENTES 10 A 19 AÑOS |
      //  D --------> MUJERES 20 A 64 AÑOS      |
      //  E --------> HOMBRES 20 A 64 AÑOS      | 
      // ---------------------------------------|

    switch ($id_vac_apli) {
      case '6' : $codigo="V001";break;
      case '5' : $codigo="V002";break;
      case '3' : $codigo= "V003";break;
      case '4' : $codigo= "V004";break;
      case '7' : $codigo= "V005";break;
      case '8' : $codigo= "V006";break;
      case '12': $codigo= "V007";break;
      case '9' : $codigo= "V008";break;
      case '2' : $codigo= "V009";break;
      case '10' : $codigo= "V010";break;
      case '11' : $codigo= "V011";break;
      case '1' : $codigo= "V012";break;
      case '18': $codigo= "V013";break;
      case '52':$codigo= "V013";break;
      case '53':$codigo= "V013";break;
      case '54': $codigo= "V013";break;
      case '14' : $codigo= "V014";break;
      case '16':$codigo= "V015";break;
      case '17' : $codigo= "V015";break;
      case '37' : $codigo= "V016";break;
      case '21' : $codigo= "V017";break;
      case '22' : $codigo= "V018";break;
      case '47' : $codigo= "V019";break;
    }    
 
if ($codigo!="") {
  
  $q1="SELECT * from facturacion.nomenclador
        where id_nomenclador_detalle='$id_nomenclador_detalle'
        and codigo='$codigo' 
        --and lower(descripcion)=lower('$descripcion')
        ";
  $res_nom=sql($q1,"Error en traer el id_nomenclador") or fin_pagina();
  $nomenclador=$res_nom->fields['id_nomenclador'];
      
  // el id_anexo
  $q="SELECT * from facturacion.anexo
      where id_nomenclador_detalle='$id_nomenclador_detalle' and id_nomenclador='$nomenclador'";
  $res_nom=sql($q,"Error en traer el id_anexo") or fin_pagina();
  $anexo=$res_nom->fields['id_anexo'];
      
  if ($anexo==''){
    $q="SELECT * from facturacion.anexo
        where prueba='No Corresponde' and id_nomenclador_detalle=$id_nomenclador_detalle";
    $res_nom=sql($q,"Error en traer el id_anexo") or fin_pagina();
    $anexo=$res_nom->fields['id_anexo'];
    }
  $param_nomen['id']=$nomenclador;
  $param_nomen['anexo']=$anexo;
  }

return $param_nomen;
}//del function
?>