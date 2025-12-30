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
         values ($id_log_comprobante,$id_comprobante, '$fecha_carga','Nuevo Comprobante desde SIPWEB','Nro. Comprobante $id_comprobante', '$usuario')";
    sql($log) or fin_pagina(); 
  
  return $id_comprobante;
  
}

function insertar_prestacion ($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$fecha_nacimiento,$cuie,$clave_beneficiario,$comentario) {
  
  $usuario=$_ses_user['name'];
  $fecha_carga=date("Y-m-d H:i:s");
  $sexo_codigo=($sexo=='M') ? 'V' : 'M';

  $profesional="P99";
  $fecha_nacimiento_cod=str_replace('-','',$fecha_nacimiento);
  $fecha_comprobante_cod=substr(str_replace('-','',$fecha_control),0,8);
  
  //$talla=($talla>2.5)? $talla=round(($talla/100),2) : $talla;
  
  //saco el id_nomenclador_detalles segun el periodo
    $q="SELECT * from facturacion.nomenclador_detalle
        where '$fecha_control' between fecha_desde and fecha_hasta and modo_facturacion='4'";
    $res_efector=sql($q,"Error en traer el precio del nomenclador") or fin_pagina();
    $id_nomenclador_detalle=$res_efector->fields['id_nomenclador_detalle'];

    $q="SELECT * from facturacion.nomenclador
        where id_nomenclador_detalle=$id_nomenclador_detalle 
        and codigo='$codigo'
        and descripcion='$descripcion'
        and $grupoe='1'";
       
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

function insertar_trazadora($clavebeneficiario,$fecha_control,$cuie,$aficlasedoc,$afitipodoc
                            ,$afidni,$apellido,$nombre,$afifechanac,$peso,$talla,$imc,$tension_arterial,$comentario) {
  
  $usuario=$_ses_user['name'];
  $fecha_carga=date("Y-m-d H:i:s");
  //$talla=($talla>2.5)? $talla=round(($talla/100),2) : $talla;
  //Cargar datos a trazadoras Adulto
  $q="SELECT nextval('trazadoras.adultos_id_adulto_seq') as id_planilla";
  $id_planilla=sql($q) or fin_pagina();
  $id_planilla=$id_planilla->fields['id_planilla'];
  
  //reviso si ya esta ingresado el registro
  $query1="SELECT id_adulto from trazadoras.adultos
          where clave='$clavebeneficiario' and fecha_control='$fecha_control'";
  $res_duplicados = sql($query1) or fin_pagina();
        
  if ($res_duplicados->recordcount()==0){
          $query="INSERT into trazadoras.adultos
                 (id_adulto,cuie,clave,clase_doc,tipo_doc,num_doc,apellido,nombre,fecha_nac,fecha_control,
                 peso,talla,imc,observaciones,fecha_carga,usuario,ta)
                 values
                 ($id_planilla,'$cuie','$clavebeneficiario','$aficlasedoc','$afitipodoc','$afidni','$apellido','$nombre','$afifechanac','$fecha_control','$peso',$talla,'$imc','$comentario','$fecha_carga','$usuario','$tension_arterial')";
          sql($query, "Error al insertar la Planilla") or fin_pagina();
          $cont_trz_adulto=1;$cont_trz_adulto_r=0;
  }
   else{
        $cont_trz_adulto_r=1;$cont_trz_adulto=0;
    };
    $cont=array($cont_trz_adulto,$cont_trz_adulto_r);
    return $cont;
    
}

function insertar_fichero ($clavebeneficiario,$cuie,$nom_medico,$fecha_control,$peso,$talla,$imc,$tension_arterial,$tunner,$id_smiafiliados,$fecha_pcontrol,$diabetico,$hipertenso,$comentario) {
  
    $usuario=$_ses_user['name'];
    $fecha_carga=date("Y-m-d H:i:s");
    //$talla=($talla>2.5)? $talla=round(($talla/100),2) : $talla;

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

function facturar_clasificacion ($clave_beneficiario,$fecha_comprobante){
  
  $query = "SELECT clasificacion_remediar2.*,
    medicos.apellido_medico || ' ' ||medicos.nombre_medico as medico,
    smiafiliados.id_smiafiliados,
    smiafiliados.afinombre,
    smiafiliados.afiapellido,
    smiafiliados.afitipodoc,
    smiafiliados.aficlasedoc,
    smiafiliados.afifechanac,
    smiafiliados.afidni,
    smiafiliados.activo,
    smiafiliados.grupopoblacional,
    smiafiliados.afisexo
    from trazadoras.clasificacion_remediar2 
    inner join planillas.medicos using (id_medico)    
    INNER JOIN nacer.smiafiliados on (clasificacion_remediar2.clave_beneficiario=smiafiliados.clavebeneficiario)
    WHERE clasificacion_remediar2.clave_beneficiario='$clave_beneficiario'
    and clasificacion_remediar2.fecha_control::date = '$fecha_comprobante'";
  
  $result1=sql($query) or fin_pagina();
  if ($result1->recordcount()>0){
    $result1->Movefirst();
    $usuario=$_ses_user['name'];
    $fecha_carga=date("Y-m-d H:i:s");
    $clavebeneficiario=$clave_beneficiario;
    $fecha_control=$fecha_comprobante;
    $fecha_pcontrol=fecha_db($result1->fields['fecha_prox_seguimiento']);
    $cuie=trim($result1->fields['cuie']);
    $nom_medico=$result1->fields['medico'];

    $peso=($result1->fields['peso']) ? $result1->fields['peso']:0;
    //$peso=($peso>=1000) ? $peso/1000 : $peso;
    //$peso=($peso>300 and $peso<1000) ? $peso/100 : $peso;
    $talla=($result1->fields['talla']) ? $result1->fields['talla']:0;
    //$imc=($result1->fields['imc'])?(int)$result1->fields['imc']:0;
    //if ($imc==0 and $peso!=0 and $talla!=0) $imc=$peso/$talla*$talla;
    $imc = $result1->fields['imc'];
    
    $tension_arterial_M=trim($result1->fields['ta_sist']);
    $tension_arterial_m=trim($result1->fields['ta_diast']);
    $maxima=str_pad($tension_arterial_M,3,"0",STR_PAD_LEFT);
    $minima=str_pad($tension_arterial_m,3,"0",STR_PAD_LEFT);
    $tension_arterial="$maxima"."/"."$minima";
    
    $id_smiafiliados=$result1->fields['id_smiafiliados'];
    $activo=trim($result1->fields['activo']);
    $afidni=trim($result1->fields['afidni']);
    $grupopoblacional=$result1->fields['grupopoblacional'];
    $afitipodoc=trim($result1->fields['afitipodoc']);
    $aficlasedoc=$result1->fields['aficlasedoc'];
    $afifechanac=$result1->fields['afifechanac'];
    $apellido=$result1->fields['afiapellido'];
    $nombre=$result1->fields['afinombre'];
    $sexo=trim($result1->fields['afisexo']);

    $comentario = "Desde CLASIFICACION";

    $res_dia_mes_anio=dia_mes_anio($fecha_nacimiento,$fecha_control);
    $anios_nac=(int)$res_dia_mes_anio['anios'];  
    
    if ($fecha_comprobante) {
      $q1="SELECT * from facturacion.nomenclador_detalle 
          where '$fecha_comprobante'::date between fecha_desde and fecha_hasta 
          and modo_facturacion='4'";
      $res_q1=sql($q1) or fin_pagina();
      $id_nomenclador_detalle=$res_q1->fields['id_nomenclador_detalle'];
    };
    
    $grupoe = 'adulto';    
    $id_comprobante = insertar_comprobante ($cuie,$nom_medico,$fecha_control,$clavebeneficiario,$id_smiafiliados,$fecha_carga,$comentario,$grupopoblacional,$comentario);
    
    $codigo='C001';
    $descripcion='Examen periódico de salud';
    $diagnostico='A97';
    insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
    insertar_trazadora($clavebeneficiario,$fecha_control,$cuie,$aficlasedoc,$afitipodoc
              ,$afidni,$apellido,$nombre,$afifechanac,$peso,$talla,$imc,$tension_arterial,$comentario);
    

    if ($result1->fields['dmt2']<>'0' or $result1->fields['dmt']<>'0') {
    
      $codigo='C050';
      $descripcion='Consulta para diagnóstico de diabetes tipo 2 (a partir de 18 años)';
      $diagnostico='T90';
      $grupoe='adulto';
        
                    
    insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
    };

    if ($result1->fields['hta']=='1' or $result1->fields['hipertenso']=='S') {
    
      $codigo='C074';
      $descripcion='Consulta de detección y/o seguimiento de HTA (a partir de 18 años)';
      $diagnostico='K86';
      $grupoe='adulto';
        
                    
    insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
    };

    if ($id_nomenclador_detalle=='19') {
      $codigo='C048';
      $descripcion='Consulta para la evaluación de riesgo cardiovascular';
      $diagnostico='K22';
      $grupoe='adulto';
      } else {
        $codigo='C048';
        $descripcion='Consulta para evaluación de riesgo cardiovascular (a partir de 18 años)';
        //este codigo de nomenclador tiene errores de caracter
        $diagnostico='K212';
        $grupoe='adulto';
      }
    
    insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);

       
    if ($result1->fields['rcvg']<>null) {
      switch ($result1->fields['rcvg']) {
        case 'bajo':{$codigo='N007';
                  $descripcion = 'Notificación de riesgo cardiovascular < 10%  (a partir de 18 años)';
                  $diagnostico='K22';
                  $grupoe='adulto';
                  insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
                  break;}
        
        case 'mode':{
                $codigo='N008';
                $descripcion='Notificación de riesgo cardiovascular  10%-< 20%  (a partir de 18 años)';
                $diagnostico='K22';
                $grupoe='adulto';
                insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
                break;};
        
        case 'alto':{
                $codigo='N009';
                $descripcion='Notificación de riesgo cardiovascular 20%-< 30%  (a partir de 18 años)';
                $diagnostico='K22';
                $grupoe='adulto';
                insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
                break;};
                
        case 'malto':{
                $codigo='N010';
                $descripcion='Notificación de riesgo cardiovascular  ≥ 30%  (a partir de 18 años)';
                $diagnostico='K22';
                $grupoe='adulto';
                insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
                break;};
                
        default:break;
        }
    };//riego global
    
    if ($result1->fields['dmt2']<>'0' or $result1->fields['dmt']<>'0') 
      $diabetico='SI';
      else $diabetico='NO';

    if ($result1->fields['hta']<>'0') 
      $hipertenso='SI';
      else $hipertenso='NO';

    $tunner = null;
    insertar_fichero ($clavebeneficiario,$cuie,$nom_medico,$fecha_control,$peso,
    $talla,$imc,$tension_arterial,$tunner,$id_smiafiliados,$fecha_pcontrol,$diabetico,$hipertenso,$comentario);
    
  //eliminar_comprobante($id_comprobante);
  }

  return $id_comprobante;
}

function facturar_seguimiento ($clave_beneficiario,$fecha_comprobante,$fact_medic){
  
  $sql="SELECT a.*,
      b.id_smiafiliados,
      b.afinombre,
      b.afiapellido,
      b.afitipodoc,
      b.aficlasedoc,
      b.afifechanac,
      b.afidni,
      b.activo,
      b.grupopoblacional,
      b.afisexo,
      c.*,
      d.*
      from trazadoras.seguimiento_remediar a, 
      nacer.smiafiliados b, 
      trazadoras.seguimiento_tratamiento c,
      trazadoras.seguimiento_consejeria d
      WHERE b.clavebeneficiario = a.clave_beneficiario
      and a.id_seguimiento_remediar = c.id_seguimiento
      and a.id_seguimiento_remediar = d.id_seguimiento
      and a.clave_beneficiario = '$clave_beneficiario'
      and fecha_comprobante = '$fecha_comprobante'";

  $result1=sql($sql) or fin_pagina();
   
  if ($result1->recordcount()>0){
    $result1->Movefirst();
    $usuario=$_ses_user['name'];
    $fecha_carga=date("Y-m-d H:i:s");
    $clavebeneficiario=$clave_beneficiario;
    $fecha_control=$fecha_comprobante;
    $fecha_pcontrol=fecha_db($result1->fields['fecha_comprobante_proximo']);
    $cuie=trim($result1->fields['efector']);
    $nom_medico=$result1->fields['medico'];

    $peso=($result1->fields['peso'])?(int)$result1->fields['peso']:0;
    $peso=($peso>=1000) ? $peso/1000 : $peso;
    $peso=($peso>300 and $peso<1000) ? $peso/100 : $peso;
    //$talla=($result1->fields['talla'])?(int)$result1->fields['talla']:0;
    $talla=$result1->fields['talla'];
    $imc=($result1->fields['imc']) ? $result1->fields['imc'] : 0;

    if ($imc==0 and $peso!=0 and $talla!=0) $imc=$peso/$talla*$talla;
    
    $tension_arterial_M=trim($result1->fields['ta_sist']);
    $tension_arterial_m=trim($result1->fields['ta_diast']);
    $maxima=str_pad($tension_arterial_M,3,"0",STR_PAD_LEFT);
    $minima=str_pad($tension_arterial_m,3,"0",STR_PAD_LEFT);
    $tension_arterial="$maxima"."/"."$minima";
    
    $id_smiafiliados=$result1->fields['id_smiafiliados'];
    $activo=trim($result1->fields['activo']);
    $afidni=trim($result1->fields['afidni']);
    $grupopoblacional=$result1->fields['grupopoblacional'];
    $afitipodoc=trim($result1->fields['afitipodoc']);
    $aficlasedoc=$result1->fields['aficlasedoc'];
    $afifechanac=$result1->fields['afifechanac'];
    $apellido=$result1->fields['afiapellido'];
    $nombre=$result1->fields['afinombre'];
    $sexo=trim($result1->fields['afisexo']);


    $res_dia_mes_anio=dia_mes_anio($fecha_nacimiento,$fecha_control);
    $anios_nac=(int)$res_dia_mes_anio['anios'];

    $diabetico = ($result1->fields['dtm2']=='sin dmt'? 'NO':'SI');
    $hipertenso =trim($result1->fields['hta']);

    $comentario = "Desde SEGUIMIENTOS";
  
  
    //harcodeamos para rapido de
    $grupoe = 'adulto';
    $tunner = null;
    
    $id_comprobante = insertar_comprobante ($cuie,$nom_medico,$fecha_control,$clave_beneficiario,$id_smiafiliados,$fecha_carga,$comentario,$grupopoblacional,$comentario);
    
    $q1="SELECT * from facturacion.nomenclador_detalle 
        where '$fecha_control' between fecha_desde and fecha_hasta 
        and modo_facturacion='4'";
    $res_q1=sql($q1) or fin_pagina();
    $id_nomenclador_detalle=$res_q1->fields['id_nomenclador_detalle'];
    
    //Codigo de insersecion de las prestaciones
    
      $codigo='C001';
      $descripcion='Examen periódico de salud';
      $diagnostico='A97';
      $grupoe='adulto';
    
    
    insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
    insertar_trazadora($clavebeneficiario,$fecha_control,$cuie,$aficlasedoc,$afitipodoc
              ,$afidni,$apellido,$nombre,$afifechanac,$peso,$talla,$imc,$tension_arterial,$comentario);
    insertar_fichero ($clavebeneficiario,$cuie,$nom_medico,$fecha_control,$peso,
          $talla,$imc,$tension_arterial,$tunner,$id_smiafiliados,$fecha_pcontrol,$diabetico,$hipertenso,$comentario);
    
    if ($diabetico=='SI' ) {
        
      $codigo='C050';
      $descripcion='Consulta para diagnóstico de diabetes tipo 2 (a partir de 18 años)';
      $diagnostico='T90';
      $grupoe='adulto';
      
      insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
      
    };

    if ($hipertenso=='SI' ) {
        
      $codigo='C074';
      $descripcion='Consulta de detección y/o seguimiento de HTA (a partir de 18 años)';
      $diagnostico='K86';
      $grupoe='adulto';
      
      insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
      
    };

    if ($result1->fields['examendepie']=='SI' ) {
        
      $codigo='P060';
      $descripcion='Realización del Test Monofilamento en diabetes tipo 2';
      $diagnostico='T90';
      $grupoe='adulto';
      
      insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
      
    };

    if ($result1->fields['ifg']<>'' ) {
        
      $codigo='C047';
      $descripcion='Consulta preventiva o de diagnóstico precoz en personas con riesgo de ERC';
      $diagnostico='U89';
      $grupoe='adulto';
      
      insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
      
    };

    if ($result1->fields['ifg']<>'' ) {
        
      $codigo='C047';
      $descripcion='Consulta preventiva o de diagnóstico precoz en personas con riesgo de ERC';
      $diagnostico='U89';
      $grupoe='adulto';
      
      insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
      
    };

    if (trim($result1->fields['rastreo_tabaquismo'])=='si') {
        
      $codigo='T023';
      $descripcion='Consejo conductual breve de cese de tabaquismo';
      $diagnostico='P22';
      $grupoe='adulto';
      
      insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
      
    };

    //riesgo global actual
    /*if ($result1->fields['riesgo_globala']!='') {
        
      if ($id_nomenclador_detalle=='19') {
        $codigo='C048';
        $descripcion='Consulta para la evaluación de riesgo cardiovascular';
        $diagnostico='K22';
        } else {
          $codigo='C048';
          $descripcion='Consulta para evaluación de riesgo cardiovascular (a partir de 18 años)';
          $diagnostico='K22';
        }

      insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
      
    }*/
    //riesgo global actual
    if ($result1->fields['riesgo_globala']<>null) {
      switch ($result1->fields['riesgo_globala']) {
        case 'bajo':{$codigo='N007';
                  $descripcion = 'Notificación de riesgo cardiovascular < 10%  (a partir de 18 años)';
                  $diagnostico='K22';
                  $grupoe='adulto';
                  insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
                  break;}
        
        case 'mode':{
                $codigo='N008';
                $descripcion='Notificación de riesgo cardiovascular  10%-< 20%  (a partir de 18 años)';
                $diagnostico='K22';
                $grupoe='adulto';
                insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
                break;};
        
        case 'alto':{
                $codigo='N009';
                $descripcion='Notificación de riesgo cardiovascular 20%-< 30%  (a partir de 18 años)';
                $diagnostico='K22';
                $grupoe='adulto';
                insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
                break;};
                
        case 'malto':{
                $codigo='N010';
                $descripcion='Notificación de riesgo cardiovascular  ≥ 30%  (a partir de 18 años)';
                $diagnostico='K22';
                $grupoe='adulto';
                insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
                break;};
                
        default:break;
        }
    };//riego global actual

    //dispensacion de medicamentos
    if ($fact_medic) {
      $codigo='P053';
      $descripcion='Dispensa de medicamentos en efector';
      $diagnostico='K22';
      insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
             
    }
  }
  return $id_comprobante;
}
?>