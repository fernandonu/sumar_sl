<? require_once ("../../config.php"); 

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$id_user=$_ses_user['id'];


// Update Beneficiarios
if ($_POST['guardar_deriv']=="Notificar estado"){
		
  $query_user="SELECT * from sistema.usu_efec
                  where id_usuario=$id_user";
   $r_usuario=sql($query_user,"consulta usuario") or die();
//valido qel usario de edicion posea asignado el efector
   if($comp_hemod=='')$comp_hemod=0;
   if($via_endov_perm=='')$via_endov_perm=0;
   if($soprt_resp=='')$soprt_resp=0;
   if($intubada=='')$intubada=0;
   if($arm=='')$arm=0;
   if($bolseo=='')$bolseo=0;
   if($o2=='')$o2=0;
        $q3="select nextval('derivacion.est_general_id_estgral_seq') as id_estgral";
        $id_estgral=sql($q3, "Error al solicitar nextval 03") or fin_pagina();
        $id_estgral=$id_estgral->fields['id_estgral'];  

        $query2="insert into derivacion.est_general
                   (id_estgral,  id_deriv, est_gral, comp_hemod,fc, fr,sat_o2,via_endov_perm,perfusion,fcfetal,
                    t_act,dim_ut, tv, soprt_resp, intubada, arm, bolseo, o2, medicacion,sonda_drenaje,  t_condicion, user_carga, fecha_carga)
                 values
                  ( $id_estgral, $id_deriv, '$est_gral', $comp_hemod, '$fc', '$fr', '$sat_o2', $via_endov_perm, '$perfusion', '$fcfetal',
                    '$t_act', '$dim_ut', '$tv', $soprt_resp, $intubada, $arm, $bolseo, $o2, '$medicacion', '$sonda_drenaje',  1, $id_user, now())";  
        sql($query2, "Error en consulta 08") or fin_pagina();      
      
//log de seguridad de usuario
   //log de seguridad    
        $q_log2="select nextval('derivacion.log_estgral_id_log_estgral_seq') as id_log_estgral";
        $log2=sql($q_log2, "Error al solicitar nextval 05") or fin_pagina();
        $id_log_estgral=$log2->fields['id_log_estgral'];  
        $query_log2="insert into derivacion.log_estgral
                   (id_log_estgral,  usuario, fecha_log, id_estgral,detalle)
                 values
                  ( $id_log_estgral,  $id_user, 'now()', $id_estgral, 'Carga nuevo registro pre-derivacion')";  
        sql($query_log2, "Error en consulta 09") or fin_pagina();  
      

//edito los datos de la solicitud, con nuevo efector
		$accion='registro guardado correctamente';
} //FIN Update

//update mdificar los datos del paciente pre-derivacion
if ($_POST['guardar_ded']=="Guardar Editar"){
   
//valido qel usario de edicion posea asignado el efector
   if($comp_hemod=='')$comp_hemod=0;
   if($via_endov_perm=='')$via_endov_perm=0;
   if($soprt_resp=='')$soprt_resp=0;
   if($intubada=='')$intubada=0;
   if($arm=='')$arm=0;
   if($bolseo=='')$bolseo=0;
   if($o2=='')$o2=0;

        $query2="update  derivacion.est_general set
                  est_gral='$est_gral',
                  comp_hemod=$comp_hemod,
                  fc='$fc',
                  fr='$fr',
                  sat_o2='$sat_o2',
                  via_endov_perm=$via_endov_perm,
                  perfusion='$perfusion',
                  fcfetal='$fcfetal',
                  t_act='$t_act',
                  dim_ut='$dim_ut',
                  tv='$tv',
                  soprt_resp=$soprt_resp,
                  intubada=$intubada,
                  arm=$arm,
                  bolseo=$bolseo,
                  o2=$o2,
                  medicacion='$medicacion',
                  sonda_drenaje='$sonda_drenaje',
                  user_carga=$id_user, 
                  fecha_carga=now()
        where  id_estgral=$id_estgral1";  
        sql($query2, "Error en consulta 08") or fin_pagina();      
      
//log de seguridad de usuario
   //log de seguridad    
        $q_log2="select nextval('derivacion.log_estgral_id_log_estgral_seq') as id_log_estgral";
        $log2=sql($q_log2, "Error al solicitar nextval 05") or fin_pagina();
        $id_log_estgral=$log2->fields['id_log_estgral'];  
        $query_log2="insert into derivacion.log_estgral
                   (id_log_estgral,  usuario, fecha_log, id_estgral,detalle)
                 values
                  ( $id_log_estgral,  $id_user, 'now()', $id_estgral1, 'Modificacion de registro pre-derivacion')";  
        sql($query_log2, "Error en consulta 09") or fin_pagina();  
      

//edito los datos de la solicitud, con nuevo efector
    $accion='registro guardado correctamente';
} //FIN Update

if ($_POST['guardar_red']=="Guardar Editar"){
   
//valido qel usario de edicion posea asignado el efector
   if($comp_hemod2=='')$comp_hemod2=0;
   if($via_endov_perm2=='')$via_endov_perm2=0;
   if($soprt_resp2=='')$soprt_resp2=0;
   if($intubada2=='')$intubada2=0;
   if($arm2=='')$arm2=0;
   if($bolseo2=='')$bolseo2=0;
   if($o2=='')$o22=0;
    if($fecha_recep=='') $fecha_recep='1900-01-01'; else $fecha_recep=fecha_db($fecha_recep);
        $query6="update  derivacion.est_general set
                  est_gral='$est_gral2',
                  comp_hemod=$comp_hemod2,
                  fc='$fc2',
                  fr='$fr2',
                  sat_o2='$sat_o22',
                  via_endov_perm=$via_endov_perm2,
                  perfusion='$perfusion2',
                  fcfetal='$fcfetal2',
                  t_act='$t_act2',
                  dim_ut='$dim_ut2',
                  tv='$tv2',
                  soprt_resp=$soprt_resp2,
                  intubada=$intubada2,
                  arm=$arm2,
                  bolseo=$bolseo2,
                  o2=$o22,
                  medicacion='$medicacion2',
                  sonda_drenaje='$sonda_drenaje2',
                  obito='$obito',
                  fecha_recep= '$fecha_recep',
                  hs_recep='$hs_recep',
                  lugar='$lugar',
                  causa='$causa',
                  user_carga=$id_user, 
                  fecha_carga=now()
        where  id_estgral=$id_estgral2";  
        sql($query6, "Error en consulta 08") or fin_pagina();      
      
//log de seguridad de usuario
   //log de seguridad    
        $q_log2="select nextval('derivacion.log_estgral_id_log_estgral_seq') as id_log_estgral";
        $log2=sql($q_log2, "Error al solicitar nextval 05") or fin_pagina();
        $id_log_estgral=$log2->fields['id_log_estgral'];  
        $query_log2="insert into derivacion.log_estgral
                   (id_log_estgral,  usuario, fecha_log, id_estgral,detalle)
                 values
                  ( $id_log_estgral,  $id_user, 'now()', $id_estgral2, 'Modificacion de registro pos-derivacion')";  
        sql($query_log2, "Error en consulta 09") or fin_pagina();  
      

//edito los datos de la solicitud, con nuevo efector
    $accion='registro guardado correctamente';
} //FIN Update



if ($_POST['guardar_recep']=="Recepcionar"){
    
  $query_user="SELECT * from sistema.usu_efec
                  where id_usuario=$id_user";
   $r_usuario=sql($query_user,"consulta usuario") or die();
//valido qel usario de edicion posea asignado el efector
   if($comp_hemod2=='')$comp_hemod2=0;
   if($via_endov_perm2=='')$via_endov_perm2=0;
   if($soprt_resp2=='')$soprt_resp2=0;
   if($intubada2=='')$intubada2=0;
   if($arm2=='')$arm2=0;
   if($bolseo2=='')$bolseo2=0;
   if($o22=='')$o22=0;
   if($fecha_recep=='') $fecha_recep='1900-01-01'; else $fecha_recep=fecha_db($fecha_recep);
        $q32="select nextval('derivacion.est_general_id_estgral_seq') as id_estgral";
        $id_estgral2=sql($q32, "Error al solicitar nextval 03") or fin_pagina();
        $id_estgral2=$id_estgral2->fields['id_estgral'];  

        $query22="insert into derivacion.est_general
                   (id_estgral,  id_deriv, est_gral, comp_hemod,fc, fr,sat_o2,via_endov_perm,perfusion,fcfetal,
                    t_act,dim_ut, tv, soprt_resp, intubada, arm, bolseo, o2, medicacion,sonda_drenaje,  
                    obito, fecha_recep, hs_recep, lugar, causa,
                    t_condicion, user_carga, fecha_carga)
                 values
                  ( $id_estgral2, $id_deriv, '$est_gral2', $comp_hemod2, '$fc2', '$fr2', '$sat_o22', $via_endov_perm2, '$perfusion2', '$fcfetal2',
                    '$t_act2', '$dim_ut2', '$tv2', $soprt_resp2, $intubada2, $arm2, $bolseo2, $o22, '$medicacion2', '$sonda_drenaje2',  
                    '$obito', '$fecha_recep', '$hs_recep', '$lugar', '$causa',
                    2, $id_user, now())";  
        sql($query22, "Error en consulta 10") or fin_pagina();      
      
//log de seguridad de usuario
   //log de seguridad    
        $q_log22="select nextval('derivacion.log_estgral_id_log_estgral_seq') as id_log_estgral";
        $log22=sql($q_log22, "Error al solicitar nextval 05") or fin_pagina();
        $id_log_estgral2=$log22->fields['id_log_estgral'];  
        $query_log22="insert into derivacion.log_estgral
                   (id_log_estgral,  usuario, fecha_log, id_estgral,detalle)
                 values
                  ( $id_log_estgral2,  $id_user, 'now()', $id_estgral2, 'Carga nuevo registro post-derivacion')";  
        sql($query_log22, "Error en consulta 11") or fin_pagina();  
      

//edito los datos de la solicitud, con nuevo efector
    $accion='registro guardado correctamente';
} //FIN Update


//Insert 
if ($_POST['guardar_ed']=="Guardar Editar"){
	 
  $query_user="SELECT * from sistema.usu_efec where id_usuario=$id_user";

  $res_user=sql($query_user,"consulta 01")or die();
  //if($res_user->RecordCount!=0){ 
    $cuie_solic=$res_user->fields['cuie'];

  $fecha_diag=fecha_db($fecha_diag);
  $fecha_deriv=fecha_db($fecha_deriv);

//-----------------------carga derivacion de paciente -----------------------------------
   
        $query="update derivacion.depar set
                   gesta='$gesta',
                   paridad='$paridad',
                   abortos='$abortos',
                   ed_gest='$ed_gest',
                   csa='$csa',
                   diag_cie10='$id_cie',
                   fecha_diag='$fecha_diag',
                   obs_deriv='$obs_deriv',
                   grp_factor='$grp_factor'
                where id_deriv=$id_deriv";  
        sql($query, "Error en consulta 02") or fin_pagina();     



} //FIN Insert Beneficiarios

// Update Beneficiarios
if ($_POST['guardar_ef']=="Guardar Efector"){

   $q2="select nextval('derivacion.efector_derivado_id_deref_seq') as id_deref";
        $id_deref=sql($q2, "Error al solicitar nextval 03") or fin_pagina();
        $id_deref=$id_deref->fields['id_deref'];  
        
        $query2="insert into derivacion.efector_derivado
                   (id_deref,  cuie_efe_deriv, fecha_deriv, id_deriv,confirmacion, user_resp)
                 values
                  ( $id_deref,  '$cuie_efe_deriv', '$fecha_deriv', $id_deriv,1, $id_user)";  
        sql($query2, "Error en consulta 04") or fin_pagina();      
      
//log de seguridad    
        $q_log="select nextval('derivacion.log_efe_deriv_id_log_ef_seq') as id_log_ef";
        $log=sql($q_log, "Error al solicitar nextval 05") or fin_pagina();
        $id_log_ef=$log->fields['id_log_ef'];  
        $query_log="insert into derivacion.log_efe_deriv
                   (id_log_ef,  usuario, f_mod, id_deref,detalle)
                 values
                  ( $id_log_ef,  $id_user, 'now()', $id_deref, 'Se solicita nuevo permiso de derivacion')";  
        sql($query_log, "Error en consulta 06") or fin_pagina();  
 
 $accion2="Se envio Solicitud";
} //FIN Update
// Borrado de Beneficiarios
/*if ($_POST['borrar']=="Borrar"){
} //FIN Borrado Beneficiarios
*/
if ($_POST['guardar_ed']=="Guardar"){

   $q2="select nextval('derivacion.efector_derivado_id_deref_seq') as id_deref";
        $id_deref=sql($q2, "Error al solicitar nextval 03") or fin_pagina();
        $id_deref=$id_deref->fields['id_deref'];  
        
        $query2="insert into derivacion.efector_derivado
                   (id_deref,  cuie_efe_deriv, fecha_deriv, id_deriv,confirmacion, user_resp)
                 values
                  ( $id_deref,  '$cuie_efe_deriv', '$fecha_deriv', $id_deriv,1, $id_user)";  
        sql($query2, "Error en consulta 04") or fin_pagina();      
      
//log de seguridad    
        $q_log="select nextval('derivacion.log_efe_deriv_id_log_ef_seq') as id_log_ef";
        $log=sql($q_log, "Error al solicitar nextval 05") or fin_pagina();
        $id_log_ef=$log->fields['id_log_ef'];  
        $query_log="insert into derivacion.log_efe_deriv
                   (id_log_ef,  usuario, f_mod, id_deref,detalle)
                 values
                  ( $id_log_ef,  $id_user, 'now()', $id_deref, 'Se solicita nuevo permiso de derivacion')";  
        sql($query_log, "Error en consulta 06") or fin_pagina();  
 
 $accion2="Se envio Solicitud";
} //FIN Update


//Busqueda de Beneficiarios

if ($id_deriv) {

    $query="SELECT
    		cuie_dev.cuie AS cuie_efe_deriv,
    		cuie_solc.cuie AS cuie_efe_sol, *
    		FROM
    		derivacion.depar
    		LEFT JOIN derivacion.efector_derivado ON derivacion.efector_derivado.id_deriv = derivacion.depar.id_deriv
    		INNER JOIN nacer.efe_conv AS cuie_dev ON cuie_dev.cuie = derivacion.efector_derivado.cuie_efe_deriv
    		INNER JOIN nacer.efe_conv AS cuie_solc ON cuie_solc.cuie = derivacion.depar.cuie_solic
    		INNER JOIN derivacion.est_deriv ON derivacion.efector_derivado.confirmacion = derivacion.est_deriv.id_est
    		INNER JOIN nacer.smiafiliados ON derivacion.depar.id_beneficiario = nacer.smiafiliados.id_smiafiliados
    		LEFT JOIN derivacion.est_general ON derivacion.est_general.id_deriv = derivacion.depar.id_deriv
         	where derivacion.depar.id_deriv='$id_deriv' 
          order by derivacion.efector_derivado.id_deref DESC";

    $res_factura=sql($query, "Error al traer el Comprobantes") or fin_pagina();

    $nombre=$res_factura->fields['afinombre'];
    $apellido=$res_factura->fields['afiapellido'];
    $num_doc=$res_factura->fields['afidni'];
    $tipo_doc=$res_factura->fields['tipo_doc'];
    $gesta=$res_factura->fields['gesta'];
    $paridad=$res_factura->fields['paridad'];
    $abortos=$res_factura->fields['abortos'];
    $ed_gest=$res_factura->fields['ed_gest'];
    $fecha_diag=$res_factura->fields['fecha_diag'];
    $obs_deriv=$res_factura->fields['obs_deriv'];
    $grp_factor=$res_factura->fields['grp_factor'];
    $fecha_solicitud=$res_factura->fields['fecha_solicitud'];
    $csa=$res_factura->fields['csa'];
    $id_cie=$res_factura->fields['diag_cie10'];
    $fecha_deriv=$res_factura->fields['fecha_deriv'];
    $cuie_efe_deriv=$res_factura->fields['cuie_efe_deriv'];

     $query2="SELECT
        cuie_dev.cuie AS cuie_efe_deriv,
        cuie_solc.cuie AS cuie_efe_sol, *
        FROM
        derivacion.depar
        LEFT JOIN derivacion.efector_derivado ON derivacion.efector_derivado.id_deriv = derivacion.depar.id_deriv
        INNER JOIN nacer.efe_conv AS cuie_dev ON cuie_dev.cuie = derivacion.efector_derivado.cuie_efe_deriv
        INNER JOIN nacer.efe_conv AS cuie_solc ON cuie_solc.cuie = derivacion.depar.cuie_solic
        INNER JOIN derivacion.est_deriv ON derivacion.efector_derivado.confirmacion = derivacion.est_deriv.id_est
        INNER JOIN nacer.smiafiliados ON derivacion.depar.id_beneficiario = nacer.smiafiliados.id_smiafiliados
        INNER JOIN derivacion.est_general ON derivacion.est_general.id_deriv = derivacion.depar.id_deriv
          where derivacion.depar.id_deriv='$id_deriv' and derivacion.est_general.t_condicion=1
          order by derivacion.efector_derivado.id_deref DESC";
          $res_factura2=sql($query2, "Error al traer el Comprobantes") or fin_pagina();
    
    //-------------------------derivacion confirmada----------------------------------------------------------//
    // --------------T_condicion ==1 equivale a estado del paciente antes de su derivacion--------------------//
  $id_estgral1=$res_factura2->fields['id_estgral'];
    $est_gral=$res_factura2->fields['est_gral'];
    $comp_hemod=$res_factura2->fields['comp_hemod'];
    $fc=$res_factura2->fields['fc'];
    $fr=$res_factura2->fields['fr'];
    $sat_o2=$res_factura2->fields['sat_o2'];
    $via_endov_perm=$res_factura2->fields['via_endov_perm'];
    $perfusion=$res_factura2->fields['perfusion'];
    $fcfetal=$res_factura2->fields['fcfetal'];
    $t_act=$res_factura2->fields['t_act'];
    $dim_ut=$res_factura2->fields['dim_ut'];
    $tv=$res_factura2->fields['tv'];
    $soprt_resp=$res_factura2->fields['soprt_resp'];
    $intubada=$res_factura2->fields['intubada'];
    $arm=$res_factura2->fields['arm'];
    $bolseo=$res_factura2->fields['bolseo'];
    $o2=$res_factura2->fields['o2'];
    $medicacion=$res_factura2->fields['medicacion'];
    $sonda_drenaje=$res_factura2->fields['sonda_drenaje'];

    $query3="SELECT
        cuie_dev.cuie AS cuie_efe_deriv,
        cuie_solc.cuie AS cuie_efe_sol, *
        FROM
        derivacion.depar
        LEFT JOIN derivacion.efector_derivado ON derivacion.efector_derivado.id_deriv = derivacion.depar.id_deriv
        INNER JOIN nacer.efe_conv AS cuie_dev ON cuie_dev.cuie = derivacion.efector_derivado.cuie_efe_deriv
        INNER JOIN nacer.efe_conv AS cuie_solc ON cuie_solc.cuie = derivacion.depar.cuie_solic
        INNER JOIN derivacion.est_deriv ON derivacion.efector_derivado.confirmacion = derivacion.est_deriv.id_est
        INNER JOIN nacer.smiafiliados ON derivacion.depar.id_beneficiario = nacer.smiafiliados.id_smiafiliados
        INNER JOIN derivacion.est_general ON derivacion.est_general.id_deriv = derivacion.depar.id_deriv
          where derivacion.depar.id_deriv='$id_deriv' and derivacion.est_general.t_condicion=2
          order by derivacion.efector_derivado.id_deref DESC";
          $res_factura3=sql($query3, "Error al traer el Comprobantes") or fin_pagina();
  //-------------------------Recepcion del paciente----------------------------------------------------------//
  // --------------T_condicion ==2 equivale a estado del paciente una vez recibido luego de la derivacion--------------------//

    $id_estgral2=$res_factura3->fields['id_estgral'];
    $est_gral2=$res_factura3->fields['est_gral'];
    $comp_hemod2=$res_factura3->fields['comp_hemod'];
    $fc2=$res_factura3->fields['fc'];
    $fr2=$res_factura3->fields['fr'];
    $sat_o22=$res_factura3->fields['sat_o2'];
    $via_endov_perm2=$res_factura3->fields['via_endov_perm'];
    $perfusion2=$res_factura3->fields['perfusion'];
    $fcfetal2=$res_factura3->fields['fcfetal'];
    $t_act2=$res_factura3->fields['t_act'];
    $dim_ut2=$res_factura3->fields['dim_ut'];
    $tv2=$res_factura3->fields['tv'];
    $soprt_resp2=$res_factura3->fields['soprt_resp'];
    $intubada2=$res_factura3->fields['intubada'];
    $arm2=$res_factura3->fields['arm'];
    $bolseo2=$res_factura3->fields['bolseo'];
    $o22=$res_factura3->fields['o2'];
    $medicacion2=$res_factura3->fields['medicacion'];
    $sonda_drenaje2=$res_factura3->fields['sonda_drenaje'];
    $obito=$res_factura3->fields['obito'];
    $fecha_recep=$res_factura3->fields['fecha_recep'];
    $hs_recep=$res_factura3->fields['hs_recep'];
    $lugar=$res_factura3->fields['lugar'];
    $causa=$res_factura3->fields['causa'];

}
echo $html_header;
cargar_calendario();

?>
<script>
//empieza funcion mostrar tabla
var img_ext='<?=$img_ext='../../imagenes/rigth2.gif' ?>';//imagen extendido
var img_cont='<?=$img_cont='../../imagenes/down2.gif' ?>';//imagen contraido

function muestra_tabla(obj_tabla,nro){
 oimg=eval("document.all.imagen_"+nro);//objeto tipo IMG
 if (obj_tabla.style.display=='none'){
  obj_tabla.style.display='inline';
    oimg.show=0;
    oimg.src=img_ext;
 }
 else{
  obj_tabla.style.display='none';
    oimg.show=1;
  oimg.src=img_cont;
 }
}//termina muestra tabla

// Validar Fechas
function esFechaValida(fecha){
    if (fecha != undefined && fecha.value != "" ){
        if (!/^\d{2}\/\d{2}\/\d{4}$/.test(fecha.value)){
            alert("formato de fecha no válido (dd/mm/aaaa)");
            return false;
        }
        var dia  =  parseInt(fecha.value.substring(0,2),10);
        var mes  =  parseInt(fecha.value.substring(3,5),10);
        var anio =  parseInt(fecha.value.substring(6),10);
 
    switch(mes){
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            numDias=31;
            break;
        case 4: case 6: case 9: case 11:
            numDias=30;
            break;
        case 2:
            if (comprobarSiBisisesto(anio)){ numDias=29 }else{ numDias=28};
            break;
        default:
            alert("Fecha introducida errónea");
            return false;
    }
 
        if (dia>numDias || dia==0){
            alert("Fecha introducida errónea");
            return false;
        }
        return true;
    }
}
 
function comprobarSiBisisesto(anio){
if ( ( anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0))) {
    return true;
    }
else {
    return false;
    }
}

//controlan que ingresen todos los datos necesarios par el muleto
function control_nuevos(){
     if(document.all.num_doc.value==""){
    	 alert("Debe completar el campo numero de documento");
    	 document.all.num_doc.focus();
    	 return false;
    	 }else{
     		var num_doc=document.all.num_doc.value;
    		if(isNaN(num_doc)){
    			alert('El dato ingresado en numero de documento debe ser entero y no contener espacios');
    			document.all.num_doc.focus();
    			return false;
    	 	}
    	 }

    	
     if(document.all.apellido.value==""){
    	 alert("Debe completar el campo apellido");
    	 document.all.apellido.focus();
    	 return false;
     }else{
    	 var charpos = document.all.apellido.value.search("/[^A-Za-z\s]/"); 
    	   if( charpos >= 0) 
    	    { 
    	     alert( "El campo Apellido solo permite letras "); 
    	     document.all.apellido.focus();
    	     return false;
    	    }
    	 }	
     

     if(document.all.nombre.value==""){
    	 alert("Debe completar el campo nombre");
    	 document.all.nombre.focus();
    	 return false;
    	 }else{
    		 var charpos = document.all.nombre.value.search("/[^A-Za-z\s]/"); 
    		   if( charpos >= 0) 
    		    { 
    		     alert( "El campo Nombre solo permite letras "); 
    		     document.all.nombre.focus();
    		     return false;
    		    }
    		 }		
	
}
//de function control_nuevos()

function editar1(){ 
  document.form1.ed_gest.disabled=false;
  document.form1.gesta.disabled=false;
  document.form1.paridad.disabled=false; 
  document.form1.csa.disabled=false;
  document.form1.abortos.disabled=false;
  document.form1.grp_factor.disabled=false;
  document.form1.id_cie.disabled=false;
  document.form1.obs_deriv.disabled=false;

  document.form1.guardar_ed.disabled=false;
  document.form1.cancelar_ed.disabled=false;
  return true;
}
function editar2(){ 
  document.form1.est_gral.disabled=false;
  document.form1.fc.disabled=false;
  document.form1.fr.disabled=false; 
  document.form1.sat_o2.disabled=false;
  document.form1.perfusion.disabled=false;
  document.form1.fcfetal.disabled=false;
  document.form1.t_act.disabled=false;
  document.form1.dim_ut.disabled=false;
  document.form1.tv.disabled=false;
  document.form1.sonda_drenaje.disabled=false;
  document.form1.medicacion.disabled=false;


  document.form1.guardar_ded.disabled=false;
  document.form1.cancelar_ded.disabled=false;
  return true;
}
function editar3(){ 
  document.form1.est_gral2.disabled=false;
  document.form1.fc2.disabled=false;
  document.form1.fr2.disabled=false; 
  document.form1.sat_o22.disabled=false;
  document.form1.perfusion2.disabled=false;
  document.form1.fcfetal2.disabled=false;
  document.form1.t_act2.disabled=false;
  document.form1.dim_ut2.disabled=false;
  document.form1.tv2.disabled=false;
  document.form1.sonda_drenaje2.disabled=false;
  document.form1.medicacion2.disabled=false;
  document.form1.obito.disabled=false;
   document.form1.hs_recep.disabled=false;
  document.form1.lugar.disabled=false;
  document.form1.causa.disabled=false; 

  document.form1.guardar_red.disabled=false;
  document.form1.cancelar_red.disabled=false;
  return true;
}

</script>
<form name='form1' action='editar_derivacion.php' method='POST'>
<input type="hidden" value="<?=$id_deriv?>" name="id_deriv">
<input type="hidden" value="<?=$pagina?>" name="pagina"> 
<input type="hidden" value="<?=$id_estgral1?>" name="id_estgral1">
<input type="hidden" value="<?=$id_estgral2?>" name="id_estgral2">
<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<table width="97%" cellspacing=0 border="1" bordercolor='#E0E0E0' align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
    	
    	<font size=+1><b>RED PROVINCIAL DE ATENCION Y DERIVACION PERINATAL</b></font>   
    	
    </td>
 </tr>
 <tr><td>
  <table width=100% align="center" class="bordes">
      <tr>
       	<td align="right">
         	  <b>Apellido:</b>
       	</td>         	
        <td align='left'>
            <input type="text" size="30" value="<?=$apellido?>" name="apellido"  <?if ($id_deriv) echo "disabled";?> >
        </td>
        <td align="right">
         	  <b>Nombre:</b>
        </td>         	
        <td align='left'>
            <input type="text" size="30" value="<?=$nombre?>" name="nombre" <?if ($id_deriv) echo "disabled";?>>
        </td>
      </tr>    
		  <tr>
     	  <td align="right">
				    <b>Tipo de Documento:</b>
			  </td>
			  <td align="left">			 	
			       <select name=tipo_doc Style="width=200px" <?php if ($id_deriv) echo "disabled"?>>
      			  <option value=DNI <?if ($tipo_doc=='DNI') echo "selected"?>>Documento Nacional de Identidad</option>
      			  <option value=LE <?if ($tipo_doc=='LE') echo "selected"?>>Libreta de Enrolamiento</option>
      			  <option value=LC <?if ($tipo_doc=='LC') echo "selected"?>>Libreta C&iacute;vica</option>
      			  <option value=PA <?if ($tipo_doc=='PA') echo "selected"?>>Pasaporte Argentino</option>
      			  <option value=CM <?if ($tipo_doc=='CM') echo "selected"?>>Certificado Migratorio</option>
			       </select>
			  </td>
        <td align="right" width="20%">
            <b>N&uacute;mero de Documento:</b>
        </td>           
        <td align='left' width="30%">
            <input type="text" size="30" value="<?=$num_doc?>" name="num_doc" <?if ($id_deriv) echo "disabled";?>>
        </td>
      </tr>
  
</table>  
<tr><td><table width="100%" class="bordes" align="center">
  <tr align="center" id="ma">
    <td align="center">
     <b>Informacion del paciente al momento de solicitar la derivacion</td>
		 </tr>
</table></td></tr>	
<tr><td> <div><table width=80% align=center >
    <tr>  
        <td align="right">
            <b>Edad Gestacional:</b>
        </td>  
        <td align='left'>
            <input type='text' name='ed_gest' value='<?=$ed_gest?>' size=10 align='right'<?if ($id_deriv) echo "disabled";?>>semanas
        </td> 
     
        <td align="right">
                <b>Gesta:</b>
        </td> 
        <td align='left'>
              <input type='text' name='gesta' value='<?=$gesta?>' size=10 align='right'<?if ($id_deriv) echo "disabled";?>>
        </td>
        <td align="right">
                <b>Paridad:</b>
        </td> 
        <td align='left'>
              <input type='text' name='paridad' value='<?=$paridad?>' size=10 align='right'<?if ($id_deriv) echo "disabled";?>>
        </td>
    </tr>
    <tr>
        <td align="right">
           <b>Csa:</b>
        </td> 
        <td align='left'>
              <input type='text' name='csa' value='<?=$csa?>' size=10 align='right'<?if ($id_deriv) echo "disabled";?>>
        </td>
        <td align="right">
            <b>Abortos:</b>
        </td> 
        <td align='left'>
              <input type='text' name='abortos' value='<?=$abortos?>' size=10 align='right'<?if ($id_deriv) echo "disabled";?>>
        </td>  
             <td align="right">
           <b>Grupo y factor:</b>
        </td> 
        <td align='left'>
              <input type='text' name='grp_factor' value='<?=$grp_factor?>' size=10 align='right' <?if ($id_deriv) echo "disabled";?>>
        </td>    
    </tr>
</table></div></td></tr>
<tr><td> <div><table width=100% align=center >        
    <tr>
     <td align="right">
      <b>Fecha Diagnostico:</b>
    </td>
    <td><input type=text id='fecha_diag' name='fecha_diag' value='<?=fecha($fecha_diag)?>' size=15 >
          <?=link_calendario("fecha_diag");?> 
    </td>
      <td align="right" >
           <b>Diagnostico CIE 10:</b> 
      </td>
      <td>

<script type="text/javascript">
        function autocomplet() {
        var min_length = 0; // min caracters to display the autocomplete
        var keyword = $('#country_id').val();
        var dataString = 'keyword='+ keyword;

        if (keyword.length >= min_length) {
          $.ajax({
            url: 'buscar_cie10.php',
            type: 'POST',
            data: dataString,
            success:function(data){
              $('#country_list_id').show();
              $('#country_list_id').html(data);
            }
          });
        } else {
          $('#country_list_id').hide();
        }
        }
       
        // set_item : this function will be executed when we select an item
        function set_item(item,id) {
          // change input value
          $('#country_id').val(item);
          $('#id_cie').val(id);
          // hide proposition list
          $('#country_list_id').hide();
        }

      function autocomplet_fin() {
        var min_length = 0; // min caracters to display the autocomplete
        var keyword = $('#financiador_id').val();
        var dataString = 'keyword='+ keyword;

        if (keyword.length >= min_length) {
          $.ajax({
            url: 'buscar_efe.php',
            type: 'POST',
            data: dataString,
            success:function(data){
              $('#financiador_list_id').show();
              $('#financiador_list_id').html(data);
            }
          });
        } else {
          $('#financiador_list_id').hide();
        }
        }
       
        // set_item : this function will be executed when we select an item
        function set_item_fin(item,id) {
          // change input value
          $('#financiador_id').val(item);
          $('#cuie_efe_deriv').val(id);
          // hide proposition list
          $('#financiador_list_id').hide();
        }
      </script>
      <style type="text/css">
        * {
          margin: 2;
          padding: 0;
        }
        body {
          padding: 10px;
          background: #eaeaea;
          text-align: center;
          font-family: arial;
          font-size: 12px;
          color: #333333;
        }
        .container {
          width: 1000px;
          height: auto;
          background: #ffffff;
          border: 1px solid #cccccc;
          border-radius: 10px;
          margin: auto;
          text-align: left;
        }
        .header {
          padding: 10px;
        }
        .main_title {
          background: #cccccc;
          color: #ffffff;
          padding: 10px;
          font-size: 20px;
          line-height: 20px;
        }
        .content {
          padding: 10px;
          min-height: 100px;
        }
        .footer {
          padding: 10px;
          text-align: right;
        }
        .footer a {
          color: #999991;
          text-decoration: none;
        }
        .footer a:hover {
          text-decoration: underline;
        }
        .label_div {
          width: 120px;
          float: left;
          line-height: 28px;
        }
        .input_container {
          height: 30px;
          float: left;
        }
        .input_container input {
          height: 29px;
          width: 726px;
          padding: 3px;
          border: 1px solid #cccccc;
          border-radius: 0;
        }
        .input_container ul {
          width: 726px;
          border: 1px solid #eaeaea;
          position: absolute;
          z-index: 9;
          background: #f3f3f3;
          list-style: none;
        }
        .input_container ul li {
          padding: 2px;
        }
        .input_container ul li:hover {
          background: #eaeaea;
        }
        #country_list_id {
          display: none;
        }
        #financiador_list_id {
          display: none;
        }
      </style>
      
      <?
      $sql= "SELECT * FROM nacer.cie10 where id10='$id_cie'";
      $res_efectores=sql($sql) or fin_pagina();
      if($res_efectores->RecordCount()==1){
        $descripcion=$res_efectores->fields['dec10'];
        $id_cie=$res_efectores->fields['id10'];
      }?>

          <div class="input_container">
          <input type="text" id="country_id" onkeyup="autocomplet()" value="<?=$descripcion?>" autocomplete="off">
            <ul id="country_list_id"></ul>

            <input type="hidden" name="id_cie" value="<?=$id_cie?>" id="id_cie" >
        </div>       
    </td>


    </tr>
    <tr>
    </td>
  </tr>
</table></div></td></tr>
<tr><td> <div><table width=100%  >   
  <tr>
      <td align="right">
          <b>Observaciones:</b>
      </td> 
      <td align='left'>
          <textarea cols='170' rows='4' name='obs_deriv' <?if ($id_deriv) echo "disabled";?>><?=$obs_deriv; ?></textarea>
      </td>  
      <tr>
 </table></div></td></tr>
<tr><td> <div><table width=100% align='center' >   
 <?if ($id_deriv != ''){
     if($res_factura->fields['estado']!=1 or $res_factura->fields['estado']!=2) {?>
     <div>
            <input type=button name="editar" value="Editar" onclick="editar1()" title="Edita Campos" > &nbsp;&nbsp;
            <input type="submit" name="guardar_ed" value="Guardar Editar" title="Guardar" disabled onclick="return control_nuevos()">&nbsp;&nbsp;
            <input type=button name="cancelar_ed" value="Cancelar" title="Cancela Edicion" disabled onclick="document.location.reload()">         
   </div> 


  <?}
  }?>     
</table></div></td></tr>
 </tr> 
<br>
<td><table width=100% align="center" class="bordes">
  <? $q_deriv="SELECT
                cuie_dev.nombre AS nom_deriv,
                cuie_solc.nombre AS nom_solc, *
                FROM
                derivacion.depar
                INNER JOIN derivacion.efector_derivado ON derivacion.efector_derivado.id_deriv = derivacion.depar.id_deriv
                INNER JOIN nacer.efe_conv AS cuie_dev ON cuie_dev.cuie = derivacion.efector_derivado.cuie_efe_deriv
                INNER JOIN nacer.efe_conv AS cuie_solc ON cuie_solc.cuie = derivacion.depar.cuie_solic
                INNER JOIN derivacion.est_deriv ON derivacion.efector_derivado.confirmacion = derivacion.est_deriv.id_est
                INNER JOIN sistema.usuarios ON derivacion.efector_derivado.user_resp = sistema.usuarios.id_usuario
              where derivacion.depar.id_deriv='$id_deriv'
              order by derivacion.efector_derivado.id_deref DESC";
    $res_q_deriv=sql($q_deriv,"consulta 2")or die();
      if ($res_q_deriv->RecordCount()==0){
        ?>
          <tr>
             <td align="center">
                <font size="3" color="Red"><b>no hay registro de derivaciones</b></font>
             </td>
           </tr> 
        <?}else{ ?>
          <tr><td><table width="100%" class="bordes" align="center">
             <tr align="center" id="ma">
              <td align="center">
                <b>Derivacion</b>
             </td>
           </tr>
           </table></td></tr>
           <tr><td><table width="100%" class="bordes" align="center" zise='D0D8DE'>
               <tr>
                  <td align="center"><b>Fecha programada</b></td>
                  <td align="center" ><b>Efector Derivado</b></td>
                  <td align="center" ><b>Respuesta</b></td>
                  <td align="center" ><b>Responsable</b></td>
                  <td align="center" ><b>Fecha de respuesta</b></td>
              </tr>
              <? $verificador=0;
              while(!$res_q_deriv->EOF){      ?>
                      <tr>
                          <td align="center"><?=fecha($res_q_deriv->fields['fecha_deriv'])?></td>
                          <td align="center"><?=$res_q_deriv->fields['nom_deriv']?></td>
                          <td align="center"><?=$res_q_deriv->fields['estado'].'. '.$res_q_deriv->fields['causa'] ?></td>
                          <td align="center"><?=$res_q_deriv->fields['nombre'].', '.$res_q_deriv->fields['apellido']?></td>
                          <td align="center"><?=fecha($res_q_deriv->fields['fecha'])?></td>
                    </tr>
                <?  if($res_q_deriv->fields['confirmacion']==1 or $res_q_deriv->fields['confirmacion']==2) { $verificador=$verificador+1;}
                     //considero que al menos existe uno en proceso ok
                //echo $verificador;
                $res_q_deriv->MoveNext();
                }?>

          </table></td></tr>
          <? if($verificador==0){
          ?>
          <tr><td> <div><table width=100% align=center >        
              <tr>
               <td align="right" >
                  <b>Derivar a: </b>
                </td>
                <td>    
                <div class="input_container">
                    <input type="text" id="financiador_id" onkeyup="autocomplet_fin()" value="<?=$nombre_efe?>" autocomplete="off" >
                      <ul id="financiador_list_id"></ul>

                      <input type="hidden" name="cuie_efe_deriv" value="<?=$cuie_efe_deriv?>" id="cuie_efe_deriv" >
                </div>                
            </tr> 
            <tr>
              <td align="right">
                <b>Fecha programada:</b>
              </td>
              <td><input type=text id='fecha_deriv' name='fecha_deriv' size=15 >
                    <?=link_calendario("fecha_deriv");?> 
              </td>
            </tr>
          </table></div></td></tr>
          <tr><td> <div><table width=100% align=center >   
            <tr align="center">
             <td>
                <input type='submit' name='guardar_ef' value='Guardar Efector' title="nuevo efector de derivacion" >
             </td>
          </tr>
          </table></div></td></tr>


          <?} 
          }//fin del else?>

</table></td></tr>
<br>

 
<tr><td><table width="100%" class="bordes" align="center">
  <tr align="center" id="ma">
    <td align="center">
     <b>Informacion del paciente al momento del traslado</b>
     </td>
	</tr>
</table></td></tr>	


<tr><td> <div><table width=100%  >   
  <tr>
      <td align="right">
          <b>Estado General:</b>
      </td> 
      <td align='left'>
          <textarea cols='170' rows='4' name='est_gral' <?if ($id_estgral1) echo "disabled";?>><?=$est_gral;?></textarea>
      </td>  
      <tr>
 </table></div></td></tr>
<tr><td> <div><table width=70%  >   
    <tr> 
       
      <td align="right">
          <b>Compensacion Hemodinamica:</b>
      </td>  
      <td align='left'>
        <b>
          <input name="comp_hemod" type="radio" value="1" <?=(trim($comp_hemod)=='1')?'checked':'';?> >SI
          <input name='comp_hemod' type='radio' value="0" <?=(trim($comp_hemod)=='0')?'checked':'';?> >NO
        </b>
      </td>
      <td align="right">
            <b>FC:</b>
        </td>  
        <td align='left'>
            <input type='text' name='fc' value='<?=$fc?>' size=10 align='right' <?if ($id_estgral1) echo "disabled";?>>
        </td> 
     
        <td align="right">
            <b>FR:</b>
        </td> 
        <td align='left'>
              <input type='text' name='fr' value='<?=$fr?>' size=10 align='right' <?if ($id_estgral1) echo "disabled";?>>
        </td>
      </tr>
      <tr>
        <td align="right">
            <b>Via endovenosa permeable:</b>
        </td> 
        <td align='left'>
          <b>
            <input name="via_endov_perm" type="radio" value=1 <?=(trim($via_endov_perm)=='1')?'checked':'';?>>SI
            <input name='via_endov_perm' type='radio' value=0 <?=(trim($via_endov_perm)=='0')?'checked':'';?>>NO
          </b>
        </td>      
   
          <td align="right">
              <b>Saturacion de O2:</b>
          </td> 
          <td align='left'>
                <input type='text' name='sat_o2' value='<?=$sat_o2?>' size=10 align='right' <?if ($id_estgral1) echo "disabled";?>>
          </td>
          <td align="right">
             <b>Perfusion:</b>
          </td> 
          <td align='left'>
                <input type='text' name='perfusion' value='<?=$perfusion?>' size=10 align='right'<?if ($id_estgral1) echo "disabled";?>>
          </td>
      </tr>
   </tr>
    <tr>  

    <td align="right">
          
      </td>  
      <td align='left'>
      </td> 
      <td align="right">
          <b>Frecuencia Cardiaca Fetal:</b>
      </td>  
      <td align='left'>
          <input type='text' name='fcfetal' value='<?=$fcfetal?>' size=10 align='right'<?if ($id_estgral1) echo "disabled";?>>
      </td> 
      <td align="right">
          <b>Tension Arterial:</b>
      </td> 
      <td align='left'>
            <input type='text' name='t_act' value='<?=$t_act?>' size=10 align='right'<?if ($id_estgral1) echo "disabled";?>>
      </td>
 </tr>
</table></div></td></tr>

<tr><td> <div><table width=100%  >   
    <tr>  
      <td align="right">
          <b>Dinamica Uterina:</b>
      </td> 
      <td align='left'>
            <textarea cols='150' rows='3' name='dim_ut' <?if ($id_estgral1) echo "disabled";?>><?=$dim_ut;?></textarea>
      </td>
    </tr>
    <tr>
      <td align="right">
         <b>TV:</b>
      </td> 
      <td align='left'>
            <textarea cols='150' rows='3' name='tv' <?if ($id_estgral1) echo "disabled";?>><?=$tv;?></textarea>
      </td>
 </tr>
</table></div></td></tr>

<tr><td> <div><table width=80% >   
    <tr>  
      <td align="right">
          <b>Soporte Respiratorio:</b>
      <td align='left'>
        <b>
          <input name="soprt_resp" type="radio" value=1 <?=(trim($soprt_resp)=='1')?'checked':'';?>>SI
          <input name='soprt_resp' type='radio' value=0 <?=(trim($soprt_resp)=='0')?'checked':'';?>>NO
        </b>
      </td> 
      
    <td align="right">
          <b>Intubada:</b>
      </td> 
      <td align='left'>
        <b>
          <input name="intubada" type="radio" value=1 <?=(trim($intubada)=='1')?'checked':'';?>>SI
          <input name='intubada' type='radio' value=0 <?=(trim($intubada)=='0')?'checked':'';?>>NO
        </b>
      </td>
    </tr>
    <tr>
      <td align="right">
             <b>ARM:</b>
      </td> 
      <td align='left'>
        <b>
          <input name="arm" type="radio" value=1 <?=(trim($arm)=='1')?'checked':'';?>>SI
          <input name='arm' type='radio' value=0 <?=(trim($arm)=='0')?'checked':'';?>>NO
        </b>
      </td>
      
      <td align="right">
         <b>Bolseo:</b>
      </td> 
      <td align='left'>
        <b>
          <input name="bolseo" type="radio" value=1 <?=(trim($bolseo)=='1')?'checked':'';?>>SI
          <input name='bolseo' type='radio' value=0 <?=(trim($bolseo)=='0')?'checked':'';?>>NO
        </b>
      </td>
      </tr>
  <tr>
      <td align="right">
          <b>O2:</b>
      </td> 
      <td align='left'>
        <b>
          <input name="o2" type="radio" value=1 <?=(trim($o2)=='1')?'checked':'';?>>SI
          <input name='o2' type='radio' value=0 <?=(trim($o2)=='0')?'checked':'';?>>NO
        </b>
      </td>      
    </tr>
</table></div></td></tr>

<tr><td> <div><table width=100%  >   
    <tr>  
      <td align="right">
           <b>Sondas y drenajes:</b>
      </td> 
      <td align='left'>
            <textarea cols='170' rows='4' name='sonda_drenaje' <?if ($id_estgral1) echo "disabled";?> ><?=$sonda_drenaje;?></textarea> 
      </td> 
      </tr>
  <tr>
      <td align="right">
          <b>Medicacion suministrada:</b>
      </td>  
      <td align='left'>
			     <textarea cols='170' rows='4' name='medicacion' <?if ($id_estgral1) echo "disabled";?>><?=$medicacion;?></textarea>     
	    </td>
    
 	</tr>
 </table></div></td></tr>

 <tr><td> <div><table width=100%  > 
      
      <tr align="center">
       <div>
       <td>
       <? if(!$id_estgral1){?>
          <input type='submit' name='guardar_deriv' value='Notificar estado' title="Guardar datos del paciente al momento de la derivacion" >
      <? }else{?>
            <input type=button name="editar" value="Editar" onclick="editar2()" title="Edita Campos" > &nbsp;&nbsp;
            <input type="submit" name="guardar_ded" value="Guardar Editar" title="Guardar" disabled >&nbsp;&nbsp;
            <input type=button name="cancelar_ded" value="Cancelar" title="Cancela Edicion" disabled onclick="document.location.reload()">         
      <? }?>
        </div> 

       </td>
      </tr>
   </table></div></td></tr> 

   <tr><td><table width="100%" class="bordes" align="center">
  <tr align="center" id="ma">
    <td align="center">
     <b>Recepcion del paciente pos-traslado</b>
     </td>
  </tr>
</table></td></tr>  


<tr><td> <div><table width=100%  >   
  <tr>
      <td align="right">
          <b>Estado General:</b>
      </td> 
      <td align='left'>
          <textarea cols='170' rows='4' name='est_gral2' <?if ($id_estgral2) echo "disabled";?>><?=$est_gral2;?></textarea>
      </td>  
      <tr>
 </table></div></td></tr>
<tr><td> <div><table width=70%  >   
    <tr> 
       
      <td align="right">
          <b>Compensacion Hemodinamica:</b>
      </td>  
      <td align='left'>
        <b>
          <input name="comp_hemod2" type="radio" value=1 <?=(trim($comp_hemod2)=='1')?'checked':'';?>>SI
          <input name='comp_hemod2' type='radio' value=0 <?=(trim($comp_hemod2)=='0')?'checked':'';?>>NO
        </b>
      </td>
      <td align="right">
            <b>FC:</b>
        </td>  
        <td align='left'>
            <input type='text' name='fc2' value='<?=$fc2?>' size=10 align='right' <?if ($id_estgral2) echo "disabled";?>>
        </td> 
     
        <td align="right">
            <b>FR:</b>
        </td> 
        <td align='left'>
              <input type='text' name='fr2' value='<?=$fr2?>' size=10 align='right' <?if ($id_estgral2) echo "disabled";?>>
        </td>
      </tr>
      <tr>
        <td align="right">
            <b>Via endovenosa permeable:</b>
        </td> 
        <td align='left'>
          <b>
            <input name="via_endov_perm2" type="radio" value=1 <?=(trim($via_endov_perm2)=='1')?'checked':'';?>>SI
            <input name='via_endov_perm2' type='radio' value=0 <?=(trim($via_endov_perm2)=='0')?'checked':'';?>>NO
          </b>
        </td>      
   
          <td align="right">
              <b>Saturacion de O2:</b>
          </td> 
          <td align='left'>
                <input type='text' name='sat_o22' value='<?=$sat_o22?>' size=10 align='right' <?if ($id_estgral2) echo "disabled";?>>
          </td>
          <td align="right">
             <b>Perfusion:</b>
          </td> 
          <td align='left'>
                <input type='text' name='perfusion2' value='<?=$perfusion2?>' size=10 align='right'<?if ($id_estgral2) echo "disabled";?>>
          </td>
      </tr>
   </tr>
    <tr>  

    <td align="right">
          
      </td>  
      <td align='left'>
      </td> 
      <td align="right">
          <b>Frecuencia Cardiaca Fetal:</b>
      </td>  
      <td align='left'>
          <input type='text' name='fcfetal2' value='<?=$fcfetal2?>' size=10 align='right'<?if ($id_estgral2) echo "disabled";?>>
      </td> 
      <td align="right">
          <b>Tension Arterial:</b>
      </td> 
      <td align='left'>
            <input type='text' name='t_act2' value='<?=$t_act2?>' size=10 align='right'<?if ($id_estgral2) echo "disabled";?>>
      </td>
 </tr>
</table></div></td></tr>

<tr><td> <div><table width=100%  >   
    <tr>  
      <td align="right">
          <b>Dinamica Uterina:</b>
      </td> 
      <td align='left'>
            <textarea cols='150' rows='3' name='dim_ut2' <?if ($id_estgral2) echo "disabled";?>><?=$dim_ut2;?></textarea>
      </td>
    </tr>
    <tr>
      <td align="right">
         <b>TV:</b>
      </td> 
      <td align='left'>
            <textarea cols='150' rows='3' name='tv2' <?if ($id_estgral2) echo "disabled";?>><?=$tv2;?></textarea>
      </td>
 </tr>
</table></div></td></tr>

<tr><td> <div><table width=80% >   
    <tr>  
      <td align="right">
          <b>Soporte Respiratorio:</b>
      <td align='left'>
        <b>
          <input name="soprt_resp2" type="radio" value=1 <?=(trim($soprt_resp2)=='1')?'checked':'';?>>SI
          <input name='soprt_resp2' type='radio' value=0 <?=(trim($soprt_resp2)=='0')?'checked':'';?>>NO
        </b>
      </td> 
      
    <td align="right">
          <b>Intubada:</b>
      </td> 
      <td align='left'>
        <b>
          <input name="intubada2" type="radio" value=1 <?=(trim($intubada2)=='1')?'checked':'';?>>SI
          <input name='intubada2' type='radio' value=0 <?=(trim($intubada2)=='0')?'checked':'';?>>NO
        </b>
      </td>
    </tr>
    <tr>
      <td align="right">
             <b>ARM:</b>
      </td> 
      <td align='left'>
        <b>
          <input name="arm2" type="radio" value=1 <?=(trim($arm2)=='1')?'checked':'';?>>SI
          <input name='arm2' type='radio' value=0 <?=(trim($arm2)=='0')?'checked':'';?>>NO
        </b>
      </td>
      
      <td align="right">
         <b>Bolseo:</b>
      </td> 
      <td align='left'>
        <b>
          <input name="bolseo2" type="radio" value=1 <?=(trim($bolseo2)=='1')?'checked':'';?>>SI
          <input name='bolseo2' type='radio' value=0 <?=(trim($bolseo2)=='0')?'checked':'';?>>NO
        </b>
      </td>
      </tr>
  <tr>
      <td align="right">
          <b>O2:</b>
      </td> 
      <td align='left'>
        <b>
          <input name="o22" type="radio" value=1 <?=(trim($o22)=='1')?'checked':'';?>>SI
          <input name='o22' type='radio' value=0 <?=(trim($o22)=='0')?'checked':'';?>>NO
        </b>
      </td>      
    </tr>
</table></div></td></tr>

<tr><td> <div><table width=100%  >   
    <tr>  
      <td align="right">
           <b>Sondas y drenajes:</b>
      </td> 
      <td align='left'>
            <textarea cols='170' rows='4' name='sonda_drenaje2' <?if ($id_estgral2) echo "disabled";?> ><?=$sonda_drenaje2;?></textarea> 
      </td> 
      </tr>
  <tr>
      <td align="right">
          <b>Medicacion suministrada:</b>
      </td>  
      <td align='left'>
           <textarea cols='170' rows='4' name='medicacion2' <?if ($id_estgral2) echo "disabled";?>><?=$medicacion2;?></textarea>     
      </td>
    
  </tr>
 </table></div></td></tr>

 <tr><td> <div><table width=100%  >   
    <tr id="ma">  
      <td align="center">
          <b>En caso del fallecimiento al momento del traslado o a la llegada</b>
      </td> 
      
    </tr>
</table></div></td></tr>
<tr><td> <div><table width=100%  >   
	<tr>
    <td align="right">
         <b>Obito:</b>
      </td> 
      <td align='left'>
            <textarea cols='170' rows='4'  name='obito' <?if ($id_estgral2) echo "disabled";?> ><?=$obito;?></textarea> 
      </td>
</tr>
</table></div></td></tr>
<tr><td> <div><table width=100%  >   
	<tr>
    <td align="right">
     	 <b>Fecha Recepcion:</b>
	  </td>
	  <td><input type=text id='fecha_recep' name='fecha_recep' value='<?if ($fecha_recep!='1900-01-01') echo fecha($fecha_recep); else echo '';?>' size=10 >
	      <?=link_calendario("fecha_recep");?> 
	  </td> 
	   <td align="right">
          <b>Hora:</b>
      </td> 
      <td align='left'>
            <input type='text' name='hs_recep' value='<?=$hs_recep?>' size=10 align='right' <?if ($id_estgral2) echo "disabled";?>>
      </td> 
</tr>
</table></div></td></tr>
<tr><td> <div><table width=100%  >   
	<tr>   
      <td align="right">
          <b>Lugar:</b>
      </td>  
      <td align='left'>
			<textarea cols='170' rows='4' name='lugar' <?if ($id_estgral2) echo "disabled";?>><?=$lugar;?></textarea>      
	  </td> 
	</tr>
	<tr>
	   <td align="right">
          <b>Causa:</b>
      </td>  
      <td align='left'>
			<textarea cols='170' rows='4' name='causa' <?if ($id_estgral2) echo "disabled";?>><?=$causa;?></textarea>      
	  </td> 
	 </tr> 
 </table></div></td></tr>
 

 <tr><td> <div><table width=100%  > 
      
      <tr align="center">
       <div>
       <td>
       <? if(!$id_estgral2){?>
          <input type='submit' name='guardar_recep' value='Recepcionar' title="Guardar datos del paciente al momento de la recepcion" >
      <? }else{?>
            <input type=button name="editar_red" value="Editar" onclick="editar3()" title="Edita Campos" > &nbsp;&nbsp;
            <input type="submit" name="guardar_red" value="Guardar Editar" title="Guardar" disabled >&nbsp;&nbsp;
            <input type=button name="cancelar_red" value="Cancelar" title="Cancela Edicion" disabled onclick="document.location.reload()">         
      <? }?>
        </div> 

       </td>
      </tr>
   </table></div></td></tr> 



<tr><td><table>
</table>
<tr><td><table width=100% align="center" class="bordes">
 <tr>
     <td align="center">
        <input type=button name="volver" value="Volver" onclick="document.location='<?=$pagina; ?>'"title="Volver al Listado" style="width=150px">     
     </td>
 </tr> 
</table></td></tr>
 </table>
</form>
 
<?=fin_pagina();// aca termino ?>
