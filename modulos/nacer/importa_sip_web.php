<?php

require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);


function cantidad_controles_parto($clavebeneficiario,$fecha_control){
      $consulta="SELECT * from facturacion.comprobante
                inner join facturacion.prestacion using (id_comprobante)
                inner join facturacion.nomenclador using (id_nomenclador)
                where comprobante.clavebeneficiario='$clavebeneficiario'
                and fecha_comprobante between '$fecha_control'::date - 270  and '$fecha_control'
                and (nomenclador.codigo='Q001' or nomenclador.codigo='Q002')";

     $res_analisis=sql($consulta,"Error al consultar los controles anteriores") or fin_pagina(); 

     if ($res_analisis->RecordCount()>0) return 1;
      else return 0;
}


function cantidad_controles($clavebeneficiario,$edad_ges_var_0119,$fecha_control){
  //funcion que revisa los controles para atras para saber si es "inicial" o "ulterior"
    $dias_gest=280-($edad_ges_var_0119*7);
    $dias_gest=($dias_gest<0)?1:$dias_gest;
    $var_intervalo='P'.$dias_gest.'D';

    $fecha_control_new=new DateTime($fecha_control);
    $fpp=$fecha_control_new->add(new DateInterval($var_intervalo));
    $fecha_inicial=$fpp->sub(new DateInterval('P9M'));
    $fecha_completa=$fecha_inicial->format('Y-m-d');

    $consulta="SELECT * from facturacion.comprobante
                inner join facturacion.prestacion using (id_comprobante)
                inner join facturacion.nomenclador using (id_nomenclador)
                where comprobante.clavebeneficiario='$clavebeneficiario'
                and fecha_comprobante between '$fecha_completa' and '$fecha_control'
                and ((nomenclador.codigo='C005' and nomenclador.descripcion ilike '%control%embarazo%')
                  or (nomenclador.codigo='C006' and nomenclador.descripcion ilike '%control%embarazo%'))";

    $res_analisis=sql($consulta,"Error al consultar los controles anteriores") or fin_pagina(); 

     if ($res_analisis->RecordCount()>0) return 1;
      else return 0;
}

function duplicado_fact_migracion($nomenclador,$id_smiafiliados,$fecha_control){

  $query="SELECT id_prestacion 
          from facturacion.prestacion
          inner join facturacion.comprobante using (id_comprobante)
          where 
          fecha_comprobante='$fecha_control' 
          and comprobante.id_smiafiliados='$id_smiafiliados'
          and prestacion.id_nomenclador=$nomenclador";
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

function valida_prestacion_nuevo_nomenclador1($id_comprobante,$nomenclador,$afidni){
   
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
    $query="SELECT id_smiafiliados,fecha_comprobante
        FROM facturacion.comprobante
          INNER JOIN nacer.smiafiliados using (id_smiafiliados)
        where id_comprobante='$id_comprobante'";               
    $id_smiafiliados_res=sql($query, "Error 2") or fin_pagina();
    $id_smiafiliados=$id_smiafiliados_res->fields['id_smiafiliados'];
    $fecha_comprobante=$id_smiafiliados_res->fields['fecha_comprobante'];
    
    //cantidad de prestaciones limites
    $cant_pres_lim=$res->fields['cant_pres_lim'];
    $per_pres_limite=$res->fields['per_pres_limite'];
    
    //cuenta la cantidad de prestaciones de un determinado filiado, de un determinado codigo y 
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

$update_cuie="UPDATE sip_clap.control_prenatal set id_efector=upper(id_efector) where id_efector like '%d%'";
sql($update_cuie) or fin_pagina();


$sql="SELECT
uad.beneficiarios.clave_beneficiario,
uad.beneficiarios.apellido_benef,
uad.beneficiarios.nombre_benef,
uad.beneficiarios.tipo_documento,
uad.beneficiarios.numero_doc,
sip_clap.control_prenatal.id_efector,
sip_clap.control_prenatal.var_0116 AS dia_var_0116,
sip_clap.control_prenatal.var_0117 AS mes_var_0117,
sip_clap.control_prenatal.var_0118 AS anio_var_0118,
sip_clap.control_prenatal.var_0119 AS edad_ges_var_0119,
sip_clap.control_prenatal.var_0120 AS peso_var_0120,
sip_clap.control_prenatal.var_0121 AS ta_siast_var_0121,
sip_clap.control_prenatal.var_0394 AS ta_diast_var_0394,
sip_clap.control_prenatal.var_0122 AS altura_uter_var_0122,
sip_clap.control_prenatal.var_0126 AS signos_y_tratam_var_0126,
sip_clap.hcgestacion_actual.var_0056 AS talla_madre_var_0056,
sip_clap.hcgestacion_actual.var_0057 AS fum_var_0057,
sip_clap.hcgestacion_actual.var_0058 AS fpp_var_0058,
date ('20'||(sip_clap.control_prenatal.var_0118::character(2)) ||'-'|| sip_clap.control_prenatal.var_0117 ||'-'|| sip_clap.control_prenatal.var_0116) AS fecha_control
FROM
sip_clap.hcperinatal
INNER JOIN sip_clap.hcgestacion_actual ON sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcgestacion_actual.id_hcperinatal
INNER JOIN sip_clap.control_prenatal ON sip_clap.hcperinatal.id_hcperinatal = sip_clap.control_prenatal.id_hcperinatal
INNER JOIN uad.beneficiarios ON uad.beneficiarios.clave_beneficiario = sip_clap.hcperinatal.clave_beneficiario
where date ('20'||(sip_clap.control_prenatal.var_0118::character(2)) ||'-'|| sip_clap.control_prenatal.var_0117 ||'-'|| sip_clap.control_prenatal.var_0116) between current_date-90 and current_date
order by 5,9,8,7";

$result1=sql($sql) or fin_pagina();


$parto="SELECT *,
          hcparto_aborto.var_0284 as fecha_control from sip_clap.hcperinatal
          inner join sip_clap.hcparto_aborto using (id_hcperinatal)
          where sip_clap.hcparto_aborto.var_0284 is not null
          and char_length(sip_clap.hcparto_aborto.var_0284)=10
          and sip_clap.hcparto_aborto.var_0284::date between current_date-120 and current_date
          and sip_clap.hcparto_aborto.var_0182='A'";

$res_parto=sql($parto) or fin_pagina();

$cont_fac=0;
$cont_trz_1=0;
$cont_trz_2=0;
$cont_fich=0;
$cont_fac_r=0;
$cont_trz_r_1=0;
$cont_trz_r_2=0;
$cont_fich_r=0;
$partos_fat=0;
$partos_r=0;

$usuario=$_ses_user['name'];
$fecha_carga=date("Y-m-d H:i:s");

//facturacion de partos

$res_parto->movefirst();

while (!$res_parto->EOF) {
  $clavebeneficiario=$res_parto->fields['clave_beneficiario'];
  $fecha_control=$res_parto->fields['fecha_control'];
  $comentario = 'desde SIPWEB';
  $tipo_parto=$res_parto->fields['var_0190'];
  $edad_gestacional_parto=($res_parto->fields['var_0198']=='') ? 0 : $res_parto->fields['var_0198'];
  $localidad=$res_parto->fields['var_0004'];


  //consulto el id_smiafiliados
  $afiliado="SELECT id_smiafiliados,afidni,activo,grupopoblacional from nacer.smiafiliados where clavebeneficiario='$clavebeneficiario'";
  $result2=sql($afiliado) or fin_pagina();
  $activo=trim($result2->fields['activo']);
  $id_smiafiliados=$result2->fields['id_smiafiliados'];
  $grupopoblacional=$result2->fields['grupopoblacional'];

  if ($result2->recordcount()>0 and $activo=='S'){
    //COMPROBANTE inserto!!!
    $q="SELECT nextval('comprobante_id_comprobante_seq') as id_comprobante";
    $id_comprobante=sql($q) or fin_pagina();
    $id_comprobante=$id_comprobante->fields['id_comprobante'];  
            
    $periodo= str_replace("-","/",substr($fecha_control,0,7));

    /*if ($localidad=='VILLA MERCEDES') $cuie_parto='D05148';
      else $cuie_parto='D12009';*/
    $cuie_parto=$res_parto->fields['var_0018'];
                    
    $query="INSERT into facturacion.comprobante
        (id_comprobante, cuie, nombre_medico, fecha_comprobante, clavebeneficiario, id_smiafiliados, fecha_carga,periodo,comentario,id_servicio,activo,grupo_etareo)
        values
        ($id_comprobante,'$cuie_parto','','$fecha_control','$clavebeneficiario', $id_smiafiliados,'$fecha_carga',
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

    
    //saco el id_nomenclador_detalles segun el periodo
    $q="SELECT * from facturacion.nomenclador_detalle
        where '$fecha_control' between fecha_desde and fecha_hasta and modo_facturacion='4'";
    $res_efector=sql($q,"Error en traer el precio del nomenclador") or fin_pagina();
    $id_nomenclador_detalle=$res_efector->fields['id_nomenclador_detalle'];

    if (!(cantidad_controles_parto($clavebeneficiario,$fecha_control))) {
      
      if($tipo_parto=='C') {
            $q="SELECT * 
              from facturacion.nomenclador
              where id_nomenclador_detalle=$id_nomenclador_detalle and 
              codigo='Q002'";
              $diagnostico_parto='W88';
            }
            else {
              $q="SELECT * 
              from facturacion.nomenclador
              where id_nomenclador_detalle=$id_nomenclador_detalle and 
              codigo='Q001'";
              $diagnostico_parto='W90';
            }
            
    $res_nom=sql($q,"Error en traer el id_nomenclador".$grupo_etareo) or fin_pagina();
    $nomenclador=$res_nom->fields['id_nomenclador'];
    $codigo_p_trz=$res_nom->fields['codigo'];
    

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

    if ($edad_gestacional_parto<>0){
      $query="INSERT into facturacion.prestacion
                (id_prestacion,id_comprobante, id_nomenclador,cantidad,precio_prestacion,id_anexo,diagnostico,estado_envio,edad_gestacional)
                values
                ($id_prestacion,$id_comprobante,$nomenclador,1,$precio_prestacion,$anexo,'$diagnostico_parto','n',$edad_gestacional_parto)";
                
      sql($query, "Error al insertar la prestacion") or fin_pagina();
            
          /*cargo los log*/ 
      $log="INSERT into facturacion.log_prestacion
                  (id_prestacion, fecha, tipo, descripcion, usuario) 
              values ($id_prestacion, '$fecha_carga','Nueva PRESTACION desde SIPWEB','Nro. prestacion $id_prestacion', '$usuario')";
      sql($log) or fin_pagina();
      $accion.=" Se Genero el Comprobante Nro  $id_comprobante.";    

      $partos_fat ++;
    }
  }

    else {
      $query="DELETE from facturacion.log_comprobante WHERE id_log_comprobante='$id_log_comprobante'";
      sql($query, "Error al insertar el comprobante") or fin_pagina(); 
      $query="DELETE from facturacion.comprobante WHERE id_comprobante='$id_comprobante'";
      sql($query, "Error al insertar el comprobante") or fin_pagina();
      $accion=" No se pudo facturar el parto";
      //echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
      $partos_r ++;
    }
  }
  $res_parto->movenext();
}//fin facturacion parto


$result1->movefirst();
while (!$result1->EOF) {
  //saco los datos que necesito para cargar
  $clavebeneficiario=$result1->fields['clave_beneficiario'];
  $fecha_control=$result1->fields['fecha_control'];
  $cuie=trim($result1->fields['id_efector']);
  $edad_ges_var_0119=$result1->fields['edad_ges_var_0119'];
  $peso_var_0120=($result1->fields['peso_var_0120']/10);
  $tension_arterial_M=$result1->fields['ta_siast_var_0121'];
  $tension_arterial_m=$result1->fields['ta_diast_var_0394'];
  $talla_madre_var_0056=1+((int)$result1->fields['talla_madre_var_0056']/100);
  $fum_var_0057=$result1->fields['fum_var_0057'];
  $fpp_var_0058=$result1->fields['fpp_var_0058'];
  $altura_uter_var_0122=$result1->fields['altura_uter_var_0122'];

  if($fpp_var_0058=='')$fpp_var_0058='1000-01-01';
  if($fum_var_0057=='')$fum_var_0057='1000-01-01';
  $maxima=str_pad($tension_arterial_M,3,"0",STR_PAD_LEFT);
  $minima=str_pad($tension_arterial_m,3,"0",STR_PAD_LEFT);
  $tension_arterial="$maxima"."/"."$minima";
  $comentario = 'desde SIPWEB';

  //consulto el id_smiafiliados
  $sql="SELECT id_smiafiliados,afidni,activo,grupopoblacional from nacer.smiafiliados where clavebeneficiario='$clavebeneficiario'";
  $result2=sql($sql) or fin_pagina();

  //traigo lo que necesito de la smiafiliados
  $id_smiafiliados=$result2->fields['id_smiafiliados'];
  $activo=trim($result2->fields['activo']);
  $afidni=trim($result2->fields['afidni']);
  $grupopoblacional=$result2->fields['grupopoblacional'];


  //////// intento facturar si encuentra un id_smiafiliados y esta activo y tiene semana de gestacion y TA/////
  if ($result2->recordcount()>0 and $activo=='S' and $edad_ges_var_0119 
      and $tension_arterial<>'000/000'){
    
    $q="SELECT nextval('comprobante_id_comprobante_seq') as id_comprobante";
    $id_comprobante=sql($q) or fin_pagina();
    $id_comprobante=$id_comprobante->fields['id_comprobante'];  
            
    $periodo= str_replace("-","/",substr($fecha_control,0,7));
                    
    $query="INSERT into facturacion.comprobante
        (id_comprobante, cuie, nombre_medico, fecha_comprobante, clavebeneficiario, id_smiafiliados, fecha_carga,periodo,comentario,id_servicio,activo,grupo_etareo)
        values
        ($id_comprobante,'$cuie','','$fecha_control','$clavebeneficiario', $id_smiafiliados,'$fecha_carga',
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

    
    //saco el id_nomenclador_detalles segun el periodo
    $q="SELECT * from facturacion.nomenclador_detalle
        where '$fecha_control' between fecha_desde and fecha_hasta and modo_facturacion='4'";
    $res_efector=sql($q,"Error en traer el precio del nomenclador") or fin_pagina();
    $id_nomenclador_detalle=$res_efector->fields['id_nomenclador_detalle'];

    
    /*if (cantidad_controles($clavebeneficiario,$edad_ges_var_0119,$fecha_control)) {
        $q="SELECT * 
            from facturacion.nomenclador
            where id_nomenclador_detalle=$id_nomenclador_detalle and 
                  codigo='C006' and 
                  descripcion ilike '%control%embarazo%'";
        }

    else {
      $q="SELECT * 
          from facturacion.nomenclador
          where id_nomenclador_detalle=$id_nomenclador_detalle and 
                codigo='C005' and 
                descripcion ilike '%control%embarazo%'";
      };*/

    if ($edad_ges_var_0119>13) {
        $q="SELECT * 
            from facturacion.nomenclador
            where id_nomenclador_detalle=$id_nomenclador_detalle and 
                  codigo='C006' and 
                  descripcion ilike '%control%embarazo%'";
        }

    else {
      $q="SELECT * 
          from facturacion.nomenclador
          where id_nomenclador_detalle=$id_nomenclador_detalle and 
                codigo='C005' and 
                descripcion ilike '%control%embarazo%'";
      };
    $res_nom=sql($q,"Error en traer el id_nomenclador".$grupo_etareo) or fin_pagina();
    $nomenclador=$res_nom->fields['id_nomenclador'];
    $codigo_p_trz=$res_nom->fields['codigo'];
    

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
        and valida_prestacion_nuevo_nomenclador1($id_comprobante,$nomenclador,$afidni)
		    )
    {    
        
    $talla_madre_var_0056=($talla_madre_var_0056)?$talla_madre_var_0056:0;


        $query="INSERT into facturacion.prestacion
               (id_prestacion,id_comprobante, id_nomenclador,cantidad,precio_prestacion,id_anexo,peso,tension_arterial,talla,diagnostico,estado_envio,edad_gestacional)
               values
               ($id_prestacion,$id_comprobante,$nomenclador,1,$precio_prestacion,$anexo,$peso_var_0120,'$tension_arterial',$talla_madre_var_0056,'W78','n','$edad_ges_var_0119')";
               
        sql($query, "Error al insertar la prestacion") or fin_pagina();
          
        //cargo los log 
        $usuario=$_ses_user['name'];
        $log="INSERT into facturacion.log_prestacion
                 (id_prestacion, fecha, tipo, descripcion, usuario) 
            values ($id_prestacion, '$fecha_carga','Nueva PRESTACION desde SIPWEB','Nro. prestacion $id_prestacion', '$usuario')";
        sql($log) or fin_pagina();
        $accion.=" Se Genero el Comprobante Nro  $id_comprobante.";    

        $cont_fac ++;
    } // del if (valida_prestacion_nuevo_nomenc......
    else {
      $query="DELETE from facturacion.log_comprobante WHERE id_log_comprobante='$id_log_comprobante'";
      sql($query, "Error al insertar el comprobante") or fin_pagina(); 
      $query="DELETE from facturacion.comprobante WHERE id_comprobante='$id_comprobante'";
      sql($query, "Error al insertar el comprobante") or fin_pagina();
      $accion=" Supero tasa de Uso o Dulicado o cuie invalido en sip web: ".$afidni;
      //echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
      $cont_fac_r ++;
    }
  }//del if de facturacion

  //Cargar datos a trazadoras segun corresponda, trz. I o trz. II
   
  if ($codigo_p_trz=='C006'){
    
    $cons_benef="SELECT * from nacer.smiafiliados where clavebeneficiario='$clavebeneficiario'";
    $res_benef=sql($cons_benef) or fin_pagina();

    if ($res_benef->RecordCount()>0) {

        $q="select nextval('trazadorassps.seq_id_trz2') as id_planilla";
        $id_planilla=sql($q) or fin_pagina();
        $id_planilla=$id_planilla->fields['id_planilla'];
        $id_beneficiarios=0;
        $id_smiafiliados=$res_benef->fields['id_smiafiliados'];

        //reviso si ya esta ingresado el registro
        $query1="select id_trz2 from trazadorassps.trazadora_2 
                  where id_smiafiliados='$id_smiafiliados' and fecha_control='$fecha_control'";
        $res_duplicados = sql($query1) or fin_pagina();
        if ($res_duplicados->recordcount()==0){
          $query="INSERT into trazadorassps.trazadora_2 
                 (id_trz2,cuie,id_smiafiliados,fecha_control,edad_gestacional
                 ,peso,tension_arterial,fecha_carga,usuario,comentario,id_beneficiarios)
                 values
                 ('$id_planilla','$cuie','$id_smiafiliados','$fecha_control','$edad_ges_var_0119',
                 '$peso_var_0120','$tension_arterial','$fecha_carga','$usuario','$comentario',$id_beneficiarios)";
          sql($query, "Error al insertar la Planilla") or fin_pagina();
          $cont_trz_2 ++;
       }
       else{
        $cont_trz_r_2 ++;
       };
    };

    
  }//del if ($codigo_p_trz=='C006'){ DE TRAZADORA ++++++++++++
  else {
    $cons_benef="SELECT * from nacer.smiafiliados where clavebeneficiario='$clavebeneficiario'";
    $res_benef=sql($cons_benef) or fin_pagina();

    if ($res_benef->RecordCount()>0) {

     $q="select nextval('trazadorassps.seq_id_trz1') as id_planilla";
     $id_planilla=sql($q) or fin_pagina();
     $id_planilla=$id_planilla->fields['id_planilla'];

     $query1="SELECT id_trz1 from trazadorassps.trazadora_1 
              where id_smiafiliados='$id_smiafiliados' and fecha_control_prenatal='$fecha_control'";
     $res_duplicados = sql($query1) or fin_pagina();
     
     if ($res_duplicados->recordcount()==0){
        $query="INSERT into trazadorassps.trazadora_1 
               (id_trz1,cuie,id_smiafiliados,fecha_control_prenatal,fum,fpp,
                edad_gestacional,fecha_carga,usuario,comentario,id_beneficiarios,
                peso,ta)
               values
               ('$id_planilla','$cuie','$id_smiafiliados','$fecha_control','$fum_var_0057','$fpp_var_0058',
               '$edad_ges_var_0119','$fecha_carga','$usuario','$comentario',0,
               $peso_var_0120,'$tension_arterial')";
        sql($query, "Error al insertar la Planilla") or fin_pagina();
        $cont_trz_1 ++;
       }
       else {
        $cont_trz_r_1 ++;
       }
   };
};
  

  //cargo fichero 
  if ($result2->recordcount()>0){

    $id_beneficiarios=0;
    $update_f="update fichero.fichero set fecha_pcontrol_flag='0' where id_smiafiliados='$id_smiafiliados'";
    sql($update_f, "No se puede actualizar los registros") or fin_pagina(); 

    $q="select nextval('fichero.fichero_id_fichero_seq') as id_fichero";
    $id_fichero=sql($q) or fin_pagina();
    $id_fichero=$id_fichero->fields['id_fichero'];  
            
    $periodo= str_replace("-","/",substr($fecha_control,0,7));
        
    if($fecha_pcontrol=='')$fecha_pcontrol='1000-01-01';
    else $fecha_pcontrol=Fecha_db($fecha_pcontrol);
        
    if($tunner=='')$tunner=0; 

    
    if($tasa_materna=='' or $tasa_materna==-1){
          $tasa_materna=0;
    } 

    
    if($imc=='')$imc=0; 
    if($talla_madre_var_0056=='')$talla_madre_var_0056=0; 
    if($peso_var_0120=='')$peso_var_0120=0;
    if($altura_uter_var_0122=='')$altura_uter_var_0122=0;
    if($imc_uterina=='')$imc_uterina=0;
    if($edad_ges_var_0119=='')$edad_ges_var_0119=0;
    if($perim_cefalico=='')$perim_cefalico=0;
    if($fpp_var_0058=='')$fpp_var_0058='1000-01-01';
    if($fum_var_0057=='')$fum_var_0057='1000-01-01';
    if($diabetico=='')$diabetico='NO';
    if($hipertenso=='')$hipertenso='NO';
    $embarazo='SI';
    $f_diagnostico='1000-01-01';
    $taller='NO';
    $codigo_taller='';

    $maxima=str_pad($tension_arterial_M,3,"0",STR_PAD_LEFT);
    $minima=str_pad($tension_arterial_m,3,"0",STR_PAD_LEFT);
    $tension_arterial="$maxima"."/"."$minima";
   
    $query="INSERT into fichero.fichero
                   (id_fichero,  cuie, nom_medico, fecha_control, comentario, periodo, peso, talla, 
                   imc ,ta, tunner, c_vacuna,  ex_clinico_gral, ex_trauma, ex_cardio, ex_odontologico, ex_ecg, hemograma, vsg, 
                   glucemia, uremia, ca_total, orina_cto, chagas ,obs_laboratorio, ergometria, obs_adolesc, id_smiafiliados, id_beneficiarios, 
                   conclusion,tasa_materna,salud_rep,metodo_anti,fecha_pcontrol,fpp, fum, f_diagnostico, peso_embarazada,altura_uterina,imc_uterina, semana_gestacional, 
                   rx_torax,rx_col_vertebral,otros,rx_observaciones,otros_obs,fecha_pcontrol_flag,percen_peso_edad,percen_talla_edad,perim_cefalico,
                   percen_perim_cefali_edad,percen_peso_talla,percen_imc_edad,diabetico,hipertenso,embarazo,publico,ag_visual, obs_ecg,tipo_lactancia,
                   embarazo_riesgo,codigo_riesgo,taller,codigo_taller)
            values
            ($id_fichero, '$cuie', '', '$fecha_control', 'Desde SIPWEB', '$periodo', '$peso_var_0120','$talla_madre_var_0056', '$imc', '$tension_arterial', '$tunner', '$c_vacuna',  '$ex_clinico_gral',
            '$ex_trauma', '$ex_cardio', '$ex_odontologico', '$ex_ecg', '$hemograma','$vsg', '$glucemia', '$uremia', '$ca_total', '$orina_cto', '$chagas', '$obs_laboratorio', '$ergometria',
            '$obs_adolesc', '$id_smiafiliados', '$id_beneficiarios', 
            '$conclusion','$tasa_materna','$salud_rep','$metodo_anti' ,'$fecha_pcontrol', '$fpp_var_0058', '$fum_var_0057',
            '$f_diagnostico', '$peso_var_0120', '$altura_uter_var_0122','$imc_uterina','$edad_ges_var_0119',
            '$rx_torax','$rx_col_vertebral','$otros','$rx_observaciones','$otros_obs','1','$percen_peso_edad',
            '$percen_talla_edad','$perim_cefalico','$percen_perim_cefali_edad','$percen_peso_talla', '$percen_imc_edad','$diabetico','$hipertenso','$embarazo','$publico', '$ag_visual', '$obs_ecg','$tipo_lactancia','$emb_riesgo','$codigo_riesgo','$taller','$codigo_taller')";  

      $query1="SELECT id_fichero from fichero.fichero 
              where id_smiafiliados='$id_smiafiliados' and fecha_control='$fecha_control'";
      $res_duplicados = sql($query1, "Error al insertar la Planilla") or fin_pagina();

      if ($res_duplicados->recordcount()==0){
        sql($query, "Error al insertar el comprobante") or fin_pagina();
        $cont_fich ++;

        $log="insert into fichero.log_fichero 
             (id_fichero, fecha, tipo, descripcion, usuario) 
             values ($id_fichero, '$fecha_carga','Nuevo Registro desde SIPWEB','Nro. fichero $id_fichero', '$usuario')";
        sql($log) or fin_pagina();  
      }
      else{
      $cont_fich_r ++;
     }

  }//fin cargo fichero

  $result1->movenext();
}//fin facturacion del control


Echo "<b><font size='+1' color='black'>";
echo "Cantidad Facturado: ".$cont_fac."<br>"; 
echo "Cantidad Facturado Rechazado: ".$cont_fac_r."<br>"; 
echo "Cantidad Trazadoras I: ".$cont_trz_1."<br>"; 
echo "Cantidad Trazadoras I Rechazado: ".$cont_trz_r_1."<br>";
echo "Cantidad Trazadoras II: ".$cont_trz_2."<br>";
echo "Cantidad Trazadoras II Rechazado: ".$cont_trz_r_2."<br>"; 
echo "Cantidad Fichero: ".$cont_fich."<br>"; 
echo "Cantidad Fichero Rechazado: ".$cont_fich_r."<br>"; 
echo "Cantidad Partos Facturados: ".$partos_fat."<br>";
echo "Cantidad Partos Rechazado: ".$partos_r."<br>";
echo "Procesados: ".$result1->recordcount()."<br>"; 
echo "</font></b>";

?>

<?echo fin_pagina();// aca termino ?>