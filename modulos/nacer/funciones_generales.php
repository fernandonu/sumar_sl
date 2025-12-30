<?
function datos_completos ($tipo_control,$edad,$peso,$talla,$imc,$perimetro_cefalico,$ta,$eg,$fum,$fpp) {

  switch ($tipo_control) {
     case 'Embarazadas': {
        if ($peso and $talla and $imc and $ta and $eg and $fum and $fpp) return true;
        else return false;
      };break;
     
     case 'Niños de 0 a 5': {
        if ($edad['anos']<1) {
          if ($peso and $talla and $imc and $perimetro_cefalico) return true;
            else return false;
        } else {
          if ($peso and $talla and $imc) return true;
            else return false;
        } 
      };break;

      case 'Niños de 6 a 9': {
        if ($peso and $talla and $imc and $ta) return true;
        else return false;
      };break;

      case 'Adolescentes': {
        if ($peso and $talla and $imc and $ta) return true;
        else return false;
      };break;

      case 'Adultos de 20 a 64': {
        if ($peso and $talla and $imc and $ta) return true;
        else return false;
      };break;

      default:break;
   } 
}


function carga_planilla($fichero)
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

function duplicado_fact_migracion($nomenclador,$id_smiafiliados,$fecha_control){

  $query="SELECT id_prestacion 
          from facturacion.prestacion
          inner join facturacion.comprobante using (id_comprobante)
          where 
          id_nomenclador=$nomenclador and fecha_comprobante='$fecha_control' and comprobante.id_smiafiliados='$id_smiafiliados'";
  $res_duplicados=sql($query, "Error") or fin_pagina();
  if ($res_duplicados->recordcount()==0){      
      return 1;
  }
  else{
    $accion = "Codigo duplicado en el fecha de la prestacion".$codigo. " - DNI: ".$afidni; 
    return 0;
    //echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
  }
}

function valida_prestacion_nuevo_nomenclador1($id_comprobante,$nomenclador,$afidni,$fecha_comprobante,$id_smiafiliados){
   
  //asigno variables para usar la validacion
  $query="SELECT codigo from facturacion.nomenclador 
      where id_nomenclador='$nomenclador'";              
  $res_codigo_nomenclador=sql($query, "Error 1") or fin_pagina(); 
  $codigo=$res_codigo_nomenclador->fields['codigo'];
  
  //traigo el codigo de nomenclador y si hay validaciones traigo los datos de la validacion
  $query="SELECT * from facturacion.validacion_prestacion
      where id_nomenclador='$nomenclador'";              
  $res=sql($query, "Error 1") or fin_pagina();
  
  if ($res->RecordCount()>0){//me fijo si hay que validar (si tiene regla)
    //recupero el id_smiafiliados para mas adelante
    /*$query="SELECT id_smiafiliados,fecha_comprobante
        FROM facturacion.comprobante
          INNER JOIN nacer.smiafiliados using (id_smiafiliados)
        where id_comprobante='$id_comprobante'";               
    $id_smiafiliados_res=sql($query, "Error 2") or fin_pagina();
    $id_smiafiliados=$id_smiafiliados_res->fields['id_smiafiliados'];
    $fecha_comprobante=$id_smiafiliados_res->fields['fecha_comprobante'];*/
    
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
        $accion = $msg_error." - Cantidad de Prestaciones: ".$cant_pres->RecordCount()." - Limite: ".$cant_pres_lim." en ".$per_pres_limite." dias" . " - Codigo: ".$codigo. " - DNI: ".$afidni; 
        //echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
        return 0;
      }
      else return 1;
  }
  else return 1;
}

function insertar_comprobante ($cuie,$nom_medico,$fecha_control,$clavebeneficiario,$id_smiafiliados,$fecha_carga,$comentario,$grupopoblacional,$comentario) {
  
  $q="SELECT nextval('comprobante_id_comprobante_seq') as id_comprobante";
    $id_comprobante=sql($q) or fin_pagina();
    $id_comprobante=$id_comprobante->fields['id_comprobante'];  
            
    $periodo= str_replace("-","/",substr($fecha_control,0,7));
                    
    $query="INSERT into facturacion.comprobante
        (id_comprobante, cuie, nombre_medico, fecha_comprobante, clavebeneficiario, id_smiafiliados, fecha_carga,periodo,comentario,id_servicio,activo,grupo_etareo)
        values
        ($id_comprobante,'$cuie','$nom_medico','$fecha_control','$clavebeneficiario', $id_smiafiliados,'$fecha_carga',
        '$periodo','$comentario','1','S','$grupopoblacional')"; 

    sql($query, "Error al insertar el comprobante") or fin_pagina();      
    $usuario=$_ses_user['name'];
    
    $q="SELECT nextval('facturacion.log_comprobante_id_log_comprobante_seq') as id_comprobante";
    $id_log_comprobante=sql($q) or fin_pagina();
    $id_log_comprobante=$id_log_comprobante->fields['id_comprobante'];

    $log="INSERT into facturacion.log_comprobante 
         (id_log_comprobante,id_comprobante, fecha, tipo, descripcion, usuario) 
         values ($id_log_comprobante,$id_comprobante, '$fecha_carga','$comentario','Nro. Comprobante $id_comprobante', '$usuario')";
    sql($log) or fin_pagina(); 
  
  return $id_comprobante;
  
}

function insertar_prestacion ($id_comprobante,$fecha_control,$codigo,$descripcion,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$fecha_nacimiento,$cuie,$clave_beneficiario,$comentario) {
  
  $usuario=$_ses_user['name'];
  $fecha_carga=date("Y-m-d H:i:s");
  $sexo_codigo=($sexo=='M') ? 'V' : 'M';

  $profesional="P99";
  $fecha_nacimiento_cod=str_replace('-','',$fecha_nacimiento);
  $fecha_comprobante_cod=substr(str_replace('-','',$fecha_control),0,8);
  
  $talla=($talla>2.5)? $talla=($talla/100) : $talla;
  
  //saco el id_nomenclador_detalles segun el periodo
    $q="SELECT * from facturacion.nomenclador_detalle
        where '$fecha_control' between fecha_desde and fecha_hasta and modo_facturacion='4'";
    $res_efector=sql($q,"Error en traer el precio del nomenclador") or fin_pagina();
    $id_nomenclador_detalle=$res_efector->fields['id_nomenclador_detalle'];

    $q="SELECT * from facturacion.nomenclador
        where id_nomenclador_detalle=$id_nomenclador_detalle 
        and codigo='$codigo'
        and descripcion='$descripcion'";
       
    $res_nom=sql($q,"Error en traer el id_nomenclador".$grupo_etareo) or fin_pagina();
    $nomenclador=$res_nom->fields['id_nomenclador'];
    $grupo=$res_nom->fields['grupo'];

    $res_dia_mes_anio=dia_mes_anio($fecha_nacimiento,$fecha_control);
    $anios_nac=$res_dia_mes_anio['anios'];
    $meses_nac=$res_dia_mes_anio['meses'];
    $dias_nac=$res_dia_mes_anio['dias'];

    $codigo=$cuie.$fecha_comprobante_cod.$clave_beneficiario.$fecha_nacimiento_cod.$sexo_codigo.$anios_nac.$grupo.$nomenclador.$diagnostico.$profesional;
    

    //tengo que sacar el id_anexo
    $q="SELECT * from facturacion.anexo
          where prueba='No Corresponde' and id_nomenclador_detalle=$id_nomenclador_detalle";
    $res_nom=sql($q,"Error en traer el id_anexo") or fin_pagina();
    $anexo=$res_nom->fields['id_anexo'];
          
    //saco id_prestacion
    $q="select nextval('facturacion.prestacion_id_prestacion_seq') as id_prestacion";
    $id_prestacion=sql($q) or fin_pagina();
    $id_prestacion=$id_prestacion->fields['id_prestacion'];
    
    $q="SELECT precio from facturacion.nomenclador where id_nomenclador=$nomenclador";
    $precio_prestacion=sql($q,"Error en traer el precio del nomenclador") or fin_pagina();
    $precio_prestacion=$precio_prestacion->fields['precio'];
        
    if (duplicado_fact_migracion($nomenclador,$id_smiafiliados,$fecha_control)  
        and ($nomenclador!='')  
        and ($anexo!='')  
        and ($precio_prestacion!='') 
        and valida_prestacion_nuevo_nomenclador1($id_comprobante,$nomenclador,$afidni,$fecha_control,$id_smiafiliados)
        )
    {    
        
    $query="INSERT into facturacion.prestacion
           (id_prestacion,id_comprobante, id_nomenclador,codigo_comp,fecha_nacimiento,edad,sexo,fecha_prestacion,anio,mes,dia,cantidad,precio_prestacion,id_anexo,peso,tension_arterial,talla,diagnostico,estado_envio)
           values
           ($id_prestacion,$id_comprobante,$nomenclador,'$codigo','$fecha_nacimiento','$anios_nac','$sexo_codigo','$fecha_control',$anios_nac,$meses_nac,$dias_nac,1,$precio_prestacion,$anexo,$peso,'$tension_arterial',$talla,'$diagnostico','n')";
               
        sql($query, "Error al insertar la prestacion") or fin_pagina();
          
      /*cargo los log*/ 
        
        $log="INSERT into facturacion.log_prestacion
                 (id_prestacion, fecha, tipo, descripcion, usuario) 
            values ($id_prestacion, '$fecha_carga','$comentario','Nro. prestacion $id_prestacion', '$usuario')";
        sql($log) or fin_pagina();
        $accion.=" Se Genero el Comprobante Nro  $id_comprobante.";    

        $cont_fac=1;$cont_fac_r=0;
    } // del if (valida_prestacion_nuevo_nomenc......
    else {
      $cont_fac_r=1;$cont_fac=0;
      }
  
$cont=array ($cont_fac,$cont_fac_r);
return $cont;
}

function insertar_trazadora($tipo_control,$tipo_benef,$id,$clavebeneficiario,$fecha_control,$cuie,$afifechanac,$peso,$talla,$imc,$ta,$eg,$fum,$fpp,$comentario,$edad,$sexo,$perimetro_cefalico) {
  
  $usuario=$_ses_user['name'];
  $fecha_carga=date("Y-m-d H:i:s");
  $talla=($talla>2.5)? $talla=round($talla/100) : $talla;

  if ($tipo_benef=='na') {$id_beneficiarios=0; $id_smiafiliados=$id;}
    else {$id_smiafiliados=0; $id_beneficiarios=$id;}
  
    switch ($tipo_control) {
      case 'Embarazadas': {
        //Cargar datos a trazadoras 1 o trazadora 2 (ulterior)
        $query1="SELECT id_trz1 from trazadorassps.trazadora_1
                where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios) 
                and fecha_control_prenatal='$fecha_control'";
        $res_duplicados_trz1 = sql($query1) or fin_pagina();

        if ($res_duplicados_trz1->recordcount()==0) {
          $query="SELECT * from trazadorassps.trazadora_1 
                  where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios)
                  AND fecha_control_prenatal between '$fecha_control'::date -($eg*7) and '$fecha_control'";
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
            $cont_trz=1;$cont_trz_r=0;
          } else {
            $query1="SELECT id_trz2 from trazadorassps.trazadora_2
                where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios)
                and fecha_control='$fecha_control'";
            $res_duplicados_trz2 = sql($query1) or fin_pagina();
            if ($res_duplicados_trz2->recordcount()==0) {
              $q="SELECT nextval('trazadorassps.seq_id_trz2') as id_planilla";
              $id_planilla=sql($q) or fin_pagina();
              $id_planilla=$id_planilla->fields['id_planilla'];

              $query="INSERT into trazadorassps.trazadora_2
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
            $cont_trz=1;$cont_trz_r=0;
            } else {
             $cont_trz_r=1;$cont_trz=0;
            }
          }
        } 
        else {
           $cont_trz_r=1;$cont_trz=0;
          }

      $cont=array($cont_trz,$cont_trz_r);
      return $cont;
      };break;
 
    case 'Niños de 0 a 5': {
        //Cargar datos a trazadoras 4 o 7 segun la edad
        if ($edad['anos'] < 1) {
          $q="SELECT nextval('trazadorassps.seq_id_trz4') as id_planilla";
          $id_planilla=sql($q) or fin_pagina();
          $id_planilla=$id_planilla->fields['id_planilla'];
          
          //reviso si ya esta ingresado el registro
          $query1="SELECT id_trz4 from trazadorassps.trazadora_4
                  where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios)
                  and fecha_control='$fecha_control'";
          $res_duplicados = sql($query1) or fin_pagina();
                
          if ($res_duplicados->recordcount()==0){
                        
            //tengo que pasar las valores de dia de nacimiento
            $dias=$edad['dias']+($edad['meses']*30);
            if ($peso!=0) $percen_peso_edad=calculo_percentilo_peso($dias,$sexo,$peso);

            $query="INSERT into trazadorassps.trazadora_4
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
            $cont_trz=1;$cont_trz_r=0;
          }
           else{
                $cont_trz_r=1;$cont_trz=0;
            };
            $cont=array($cont_trz,$cont_trz_r);
        
        } else {
          $q="SELECT nextval('trazadorassps.seq_id_trz7') as id_planilla";
          $id_planilla=sql($q) or fin_pagina();
          $id_planilla=$id_planilla->fields['id_planilla'];
          
          //reviso si ya esta ingresado el registro
          $query1="SELECT id_trz7 from trazadorassps.trazadora_7
                  where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios)
                  and fecha_control='$fecha_control'";
          $res_duplicados = sql($query1) or fin_pagina();
                
          if ($res_duplicados->recordcount()==0){
             
            if ($edad['anos']>=1 and $edad['anos']<=5) {
              $meses=$edad['meses']+($edad['anos']*12);
              $dias=$edad['dias']+($meses*30);
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


            $query="INSERT into trazadorassps.trazadora_7
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
            $cont_trz=1;$cont_trz_r=0;
          }
           else{
                $cont_trz_r=1;$cont_trz=0;
            };
            $cont=array($cont_trz,$cont_trz_r);
        }

          return $cont;
      };break;

    case 'Niños de 6 a 9': {
        //Cargar datos a trazadoras 7
        $q="SELECT nextval('trazadorassps.seq_id_trz7') as id_planilla";
        $id_planilla=sql($q) or fin_pagina();
        $id_planilla=$id_planilla->fields['id_planilla'];
          
        //reviso si ya esta ingresado el registro
        $query1="SELECT id_trz7 from trazadorassps.trazadora_7
                where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios)
                and fecha_control='$fecha_control'";
        $res_duplicados = sql($query1) or fin_pagina();
              
        if ($res_duplicados->recordcount()==0){
          
          if ($edad['anos']>5 and $edad['anos']<=19) {
            $meses=$edad['meses']+($edad['anos']*12);
            if ($imc!=0) $percen_imc_edad=calculo_percentilo_imc($meses,$sexo,$imc);
              else $percen_imc_edad='Sin Datos';
            if ($talla!=0) $percen_talla_edad=calculo_percentilo_talla($meses,$sexo,($talla*100));
              else $percen_talla_edad='Sin Datos';
          } else {
            $percen_imc_edad='Sin Datos';
            $percen_talla_edad='Sin Datos';
            }
          $query="INSERT into trazadorassps.trazadora_7
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
          $cont_trz=1;$cont_trz_r=0;
        }
         else{
              $cont_trz_r=1;$cont_trz=0;
          };
          $cont=array($cont_trz,$cont_trz_r);
          return $cont;
      };break;

    case 'Adolescentes': {
        //Cargar datos a trazadoras 10
        $q="SELECT nextval('trazadorassps.seq_id_trz10') as id_planilla";
        $id_planilla=sql($q) or fin_pagina();
        $id_planilla=$id_planilla->fields['id_planilla'];
        
        //reviso si ya esta ingresado el registro
        $query1="SELECT id_trz10 from trazadorassps.trazadora_10
                where (id_smiafiliados=$id_smiafiliados or id_beneficiarios=$id_beneficiarios)
                and fecha_control='$fecha_control'";
        $res_duplicados = sql($query1) or fin_pagina();
              
        if ($res_duplicados->recordcount()==0){
          if ($tipo_benef='na') {$id_beneficiarios=0; $id_smiafiliados=$id;}
            else {$id_smiafiliados=0; $id_beneficiarios=$id;}
          if ($edad['anos']>5 and $edad['anos']<=19) {
            $meses=$edad['meses']+($edad['anos']*12);
            if ($imc!=0) $percen_imc_edad=calculo_percentilo_imc($meses,$sexo,$imc);
              else $percen_imc_edad='Sin Datos';
            if ($talla!=0) $percen_talla_edad=calculo_percentilo_talla($meses,$sexo,($talla*100));
              else $percen_talla_edad='Sin Datos';
          } else {
            $percen_imc_edad='Sin Datos';
            $percen_talla_edad='Sin Datos';
          }

          $query="INSERT into trazadorassps.trazadora_10
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
          $cont_trz=1;$cont_trz_r=0;
        }
         else{
              $cont_trz_r=1;$cont_trz=0;
          };
          $cont=array($cont_trz,$cont_trz_r);
          return $cont;
      };break;

    case 'Adultos de 20 a 64': {
        //Cargar datos a trazadoras Adulto
        $q="SELECT nextval('trazadoras.adultos_id_adulto_seq') as id_planilla";
        $id_planilla=sql($q) or fin_pagina();
        $id_planilla=$id_planilla->fields['id_planilla'];
        
        //reviso si ya esta ingresado el registro
        $query1="SELECT id_adulto from trazadoras.adultos
                where clave='$clavebeneficiario' and fecha_control='$fecha_control'";
        $res_duplicados = sql($query1) or fin_pagina();
              
        if ($res_duplicados->recordcount()==0){
          if ($tipo_benef='na') {
            $query="SELECT * from nacer.smiafiliados where id_smiafiliados=$id";
            $res_query=sql($query) or fin_pagina();
            $clase_doc=$res_query->fields['aficlasedoc'];
            $tipo_doc=$res_query->fields['afitipodoc'];
            $dni=$res_query->fields['afidni'];
            $nombre=$res_query->fields['nombre'];
            $apellido=$res_query->fields['apellido'];
            $usuario=$_ses_user['name'];
            $id_beneficiarios=0; 
            $id_smiafiliados=$id;
            } else {
              $query="SELECT * from uad.beneficiarios where id_beneficiarios=$id";
              $res_query=sql($query) or fin_pagina();
              $clase_doc=$res_query->fields['clase_documento_benef'];
              $tipo_doc=$res_query->fields['tipo_documento'];
              $dni=$res_query->fields['numero_doc'];
              $nombre=$res_query->fields['nombre_benef'];
              $apellido=$res_query->fields['apellido_benef'];
              $usuario=$_ses_user['name'];
              $id_smiafiliados=0; 
              $id_beneficiarios=$id;}
          $query="INSERT into trazadoras.adultos
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
          $cont_trz=1;$cont_trz_r=0;
        }
         else{
              $cont_trz_r=1;$cont_trz=0;
          };
          $cont=array($cont_trz,$cont_trz_r);
          return $cont;
      };break;
    
    default:break;
  }
}

function insertar_fichero ($clavebeneficiario,$cuie,$nom_medico,$fecha_control,$peso,$talla,$imc,$tension_arterial,$tunner,$id_smiafiliados,$fecha_pcontrol,$diabetico,$hipertenso,$comentario) {
  
    $usuario=$_ses_user['name'];
    $fecha_carga=date("Y-m-d H:i:s");
    $talla=($talla>2.5)? $talla=round($talla/100) : $talla;

    $periodo= str_replace("-","/",substr($fecha_control,0,7));
    
    $update_f="UPDATE fichero.fichero set fecha_pcontrol_flag='0' where id_smiafiliados='$id_smiafiliados'";
    sql($update_f, "No se puede actualizar los registros") or fin_pagina(); 

    $q="select nextval('fichero.fichero_id_fichero_seq') as id_fichero";
    $id_fichero=sql($q) or fin_pagina();
    $id_fichero=$id_fichero->fields['id_fichero'];  
            
    $fecha_pcontrol=Fecha_db($fecha_pcontrol);
    $tunner=0; 
           
    //busco en clasificacion si es diabetico e hipertenso
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
        $cont_fich=1;$cont_fich_r=0;

        $log="insert into fichero.log_fichero 
             (id_fichero, fecha, tipo, descripcion, usuario) 
             values ($id_fichero, '$fecha_carga','$comentario','Nro. fichero $id_fichero', '$usuario')";
        sql($log) or fin_pagina();  
      }
      else{
      $cont_fich_r=1;$cont_fich=0;
     }
     $cont=array($cont_fich,$cont_fich_r);
     return $cont;
}

function eliminar_comprobante($id_comprobante) {

  $query="DELETE from facturacion.log_comprobante WHERE id_comprobante=$id_comprobante";
  sql($query, "Error al insertar el comprobante") or fin_pagina(); 
  $query="DELETE from facturacion.comprobante WHERE id_comprobante=$id_comprobante";
  sql($query, "Error al insertar el comprobante") or fin_pagina();
  return 'Comprobante Eliminado porque no hay prestaciones incluidas por superar tasa de uso';
}

function limpieza_tablas () {
  //limpiar la tabla de facturacion.comprobante con comp. sin facturacion.prestacion

  $q="DELETE from facturacion.log_comprobante where id_comprobante in (
        select id_comprobante from facturacion.comprobante
        where comentario='desde CLASIFICACION - CRONICOS' and id_factura is null
        except 
        select id_comprobante from facturacion.prestacion
        )";

  sql($q) or fin_pagina();

  $q="DELETE from facturacion.comprobante where id_comprobante in (
        select id_comprobante from facturacion.comprobante
        where comentario='desde CLASIFICACION - CRONICOS' and id_factura is null
        except 
        select id_comprobante from facturacion.prestacion
        )";

  sql($q) or fin_pagina();
}
?>