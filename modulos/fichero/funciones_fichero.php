<?function carga_planilla($fichero)
{
      $fichero='../epidemiologia/pdf/'.$fichero.'.pdf';
      $link=encode_link("../epidemiologia/download.php",array("fichero"=>$fichero));
      $ref="location.href='$link'";
      $ref_1="window.location='$link'";  
      return $link;
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
function calculo_dias($fecha_hast,$fecha_eq){ // calculamos la diferencia de dias en entero 
    //defino fecha 1
    $anio1 = substr($fecha_hast,6,9);
    $mes1 = substr($fecha_hast,3,-5);
    $dia1 = substr($fecha_hast,0,2);
    //defino fecha 2      
      
     $dia2 = substr($fecha_eq,0,2);
     $mes2 = substr($fecha_eq,3,-5);
     $anio2 = substr($fecha_eq,6,9);
    //calculo timestam de las dos fechas
    $timestamp1 = mktime(0,0,0,$mes1,$dia1,$anio1);
    $timestamp2 = mktime(0,0,0,$mes2,$dia2,$anio2); 
    //resto a una fecha la otra
    $segundos_diferencia = $timestamp1 - $timestamp2;
    //echo $segundos_diferencia;
    
    //convierto segundos en días
    $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
    //obtengo el valor absoulto de los días (quito el posible signo negativo)
    $dias_diferencia = abs($dias_diferencia);
    $meses_trans=$dias_diferencia/30;
    //quito los decimales a los días de diferencia
    //$dias_diferencia = floor($dias_diferencia); 
    $meses_trans = floor($meses_trans); 
     return ($meses_trans); 
}

function edad_con_meses($fecha_de_nacimiento,$fecha_control){ 
  //$fecha_actual = date ("Y-m-d"); 

  // separamos en partes las fechas 
  $array_nacimiento = explode ( "-", $fecha_de_nacimiento ); 
  //$array_actual = explode ( "-", $fecha_actual ); 
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



function suma_fechas($fecha,$ndias){
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
        list($dia,$mes,$anio)=split("/", $fecha);
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
        list($dia,$mes,$anio)=split("-",$fecha);
      $nueva = mktime(0,0,0, $mes,$dia,$anio) + $ndias * 24 * 60 * 60;
      $nuevafecha=date("d-m-Y",$nueva);
      return ($nuevafecha);  
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
      case $peso>$res_sql->fields['p3'] and $peso<=$res_sql->fields['p10'] : return '2';breck;
      case $peso>$res_sql->fields['p10'] and $peso<=$res_sql->fields['p85'] : return '3';breck;
      case $peso>$res_sql->fields['p85'] and $peso<=$res_sql->fields['p97'] : return '4';breck;
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
?>