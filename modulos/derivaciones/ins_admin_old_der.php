<?
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$id_user=$_ses_user['id'];


// Update Beneficiarios
if ($_POST['guardar_editar']=="Guardar"){
		
  $query_user="SELECT * from sistema.usu_efec
                  where id_usuario=$id_user";
   $r_usuario=sql($query_user,"consulta usuario") or die();

		
} //FIN Update

//Insert 
if ($_POST['guardar']=="Guardar Planilla"){
	 

$fecha_diag=fecha_db($fecha_diag);
$fecha_deriv=fecha_db($fecha_deriv);

//-----------------------carga derivacion de paciente -----------------------------------
   $q="select nextval('derivacion.depar_id_deriv_seq') as id_deriv";
        $id_deriv=sql($q, "Error al solicitar nextval 01") or fin_pagina();
        $id_deriv=$id_deriv->fields['id_deriv'];  
  
        $query="insert into derivacion.depar
                   (id_deriv,  id_beneficiario, cuie_solic, gesta, paridad, abortos, ed_gest,csa,diag_cie10, fecha_diag, obs_deriv, grp_factor, fecha_solicitud, usuario)
                 values
                  ( $id_deriv,  $id_smiafiliados, '$cuie_solic', '$gesta', '$paridad', '$abortos', '$ed_gest','$csa','$id_cie','$fecha_diag','$obs_deriv', '$grp_factor', now(), $id_user)";  
        sql($query, "Error en consulta 02") or fin_pagina();     

  //----------cargamos la primer solicitud de recepcion------------------------------------
        $q2="select nextval('derivacion.efector_derivado_id_deref_seq') as id_deref";
        $id_deref=sql($q2, "Error al solicitar nextval 03") or fin_pagina();
        $id_deref=$id_deref->fields['id_deref'];  
        
        $query2="insert into derivacion.efector_derivado
                   (id_deref,  cuie_efe_deriv, fecha_deriv, id_deriv, confirmacion,user_resp)
                 values
                  ( $id_deref,  '$cuie_efe_deriv', '$fecha_deriv', $id_deriv, 1, $id_user)";  
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
        $accion="Se envio Solicitud";      
        

       
} //FIN Insert Beneficiarios

// Borrado de Beneficiarios
if ($_POST['borrar']=="Borrar"){

	
} //FIN Borrado Beneficiarios

//Busqueda de Beneficiarios

if ($id_smiafiliados) {

$query="SELECT id_smiafiliados, afiapellido,afinombre,afidni,nombre,cuie,clavebeneficiario
      from nacer.smiafiliados
      left join nacer.efe_conv on (cuieefectorasignado=cuie)
      where id_smiafiliados='$id_smiafiliados'";

$res_factura=sql($query, "Error al traer el Comprobantes") or fin_pagina();

$nombre=$res_factura->fields['afinombre'];
$apellido=$res_factura->fields['afiapellido'];
$num_doc=$res_factura->fields['afidni'];
$tipo_doc=$res_factura->fields['tipo_doc'];
$clave_beneficiario=$res_factura->fields['clavebeneficiario'];
$id_smiafiliados=$res_factura->fields['id_smiafiliados'];
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
    if(document.all.fecha_diag.value==""){
    	 alert("Debe completar la Fecha del diagnostico ");
    	 document.all.fecha_diag.focus();
    	 return false;
    	 }
    if(document.all.grp_factor.value==""){
       alert("Debe completar el grupo y factor sanguineo");
       document.all.grp_factor.focus();
       return false;
       }    
    if(document.all.ed_gest.value==""){
       alert("Debe completar la campo edad gestacional");
       document.all.ed_gest.focus();
       return false;
       }else{
        var num_gest=document.all.ed_gest.value;
        if(isNaN(num_gest)){
          alert('El dato ingresado en edad gestacional debe ser entero y no contener espacios');
          document.all.ed_gest.focus();
          return false;
        }
    }   
    if(document.all.gesta.value==""){
    	 alert("Debe completar la gesta");
    	 document.all.gesta.focus();
    	 return false;
    }	
    if(document.all.paridad.value==""){
       alert("Debe completar la paridad");
       document.all.paridad.focus();
       return false;
    } 
    if(document.all.id_cie.value==""){
       alert("Debe seleccionar un diagnostico");
       document.all.id_cie.focus();
       return false;
    }
    if(document.all.cuie_efe_deriv.value==""){
       alert("Debe seleccionar un Efector destino");
       document.all.cuie_efe_deriv.focus();
       return false;
    }  
   if(document.all.fecha_deriv.value==""){
       alert("Debe completar la Fecha de derivacion ");
       document.all.fecha_deriv.focus();
       return false;
       }

	
}
//de function control_nuevos()

</script>
<form name='form1' action='ins_admin_old_der.php' method='POST'>
<input type="hidden" value="<?=$id_smiafiliados?>" name="id_smiafiliados">
<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<?//echo "<center><b><font size='+1' color='Blue'>$accion2</font></b></center>";?>
<table width="97%" cellspacing=0 border="1" bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
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
            <input type="text" size="30" value="<?=$apellido?>" name="apellido"  <?if ($id_smiafiliados) echo "disabled";?> >
        </td>
        <td align="right">
         	  <b>Nombre:</b>
        </td>         	
        <td align='left'>
            <input type="text" size="30" value="<?=$nombre?>" name="nombre" <?if ($id_smiafiliados) echo "disabled";?>>
        </td>
      </tr>    
		  <tr>
     	  <td align="right">
				    <b>Tipo de Documento:</b>
			  </td>
			  <td align="left">			 	
			       <select name=tipo_doc Style="width=200px" <?php if (($id_smiafiliados) and ($tipo_transaccion != "M"))echo "disabled"?>>
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
            <input type="text" size="30" value="<?=$num_doc?>" name="num_doc" <?if ($id_smiafiliados) echo "disabled";?>>
        </td>
      </tr>
  
</table>  
<tr><td><table width="100%" class="bordes" align="center">
  <tr align="center" id="mo">
    <td align="center" width="3%">
     <img id="imagen_2" src="<?=$img_ext?>" border=0 title="Solicitu de traslado" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida1,2);" >
    </td>
    <td align="center">
     <b>Alta de traslado de Embarazada</b>
    </td>
  </tr>
</table></td></tr>

<tr><td><table id="prueba_vida1" border="1" width="100%" style="display:none;border:thin groove">
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
            <input type='text' name='ed_gest' value='<?=$ed_gest?>' size=10 align='right'>semanas
        </td> 
     
        <td align="right">
                <b>Gesta:</b>
        </td> 
        <td align='left'>
              <input type='text' name='gesta' value='<?=$gesta?>' size=10 align='right'>
        </td>
        <td align="right">
                <b>Paridad:</b>
        </td> 
        <td align='left'>
              <input type='text' name='paridad' value='<?=$paridad?>' size=10 align='right'>
        </td>
    </tr>
    <tr>
        <td align="right">
           <b>Csa:</b>
        </td> 
        <td align='left'>
              <input type='text' name='csa' value='<?=$csa?>' size=10 align='right'>
        </td>
        <td align="right">
            <b>Abortos:</b>
        </td> 
        <td align='left'>
              <input type='text' name='abortos' value='<?=$abortos?>' size=10 align='right'>
        </td>  
             <td align="right">
           <b>Grupo y factor:</b>
        </td> 
        <td align='left'>
              <select name=grp_factor Style="width=50px" >
              <option value='A+' <?if ($grp_factor=='A+') echo "selected"?>>A+</option>
              <option value='A-' <?if ($grp_factor=='A-') echo "selected"?>>A-</option>
              <option value='B+' <?if ($grp_factor=='B+') echo "selected"?>>B+</option>
              <option value='B-' <?if ($grp_factor=='B-') echo "selected"?>>B-</option>
              <option value='AB+' <?if ($grp_factor=='AB+') echo "selected"?>>AB+</option>
              <option value='AB-' <?if ($grp_factor=='AB-') echo "selected"?>>AB-</option>
              <option value='O+' <?if ($grp_factor=='O+') echo "selected"?>>O+</option>
              <option value='O-' <?if ($grp_factor=='O-') echo "selected"?>>O-</option>

             </select>
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
        var min_length = 5; // min caracters to display the autocomplete
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
          var itemcomp=id+'-'+item;
          // change input value
          $('#country_id').val(itemcomp);
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
		//-------------------------------------------------------------------------------***
		function autocomplet_efec() {
        var min_length = 0; // min caracters to display the autocomplete
        var keyword = $('#financiador_id1').val();
        var dataString = 'keyword='+ keyword;

        if (keyword.length >= min_length) {
          $.ajax({
            url: 'buscar_efe1.php',
            type: 'POST',
            data: dataString,
            success:function(data){
              $('#financiador_list_id1').show();
              $('#financiador_list_id1').html(data);
            }
          });
        } else {
          $('#financiador_list_id1').hide();
        }
        }
       
        // set_item : this function will be executed when we select an item
        function set_item_efec(item,id) {
          // change input value
          $('#financiador_id1').val(item);
          $('#cuie_solic').val(id);
          // hide proposition list
          $('#financiador_list_id1').hide();
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
      $sql= "SELECT * FROM nacer.cie10";
      $res_efectores=sql($sql) or fin_pagina();
      if($res_efectores->RecordCount()==1){
        $descripcion=$res_efectores->fields['dec10'];
        $id_cie=$res_efectores->fields['id10'];
      }?>

          <div class="input_container">
          <input type="text" id="country_id" onkeyup="autocomplet()" value="<?=$descripcion?>" autocomplete="off" >
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
          <textarea cols='170' rows='4' name='obs_deriv'><?=$obs_deriv;?></textarea>
      </td>  
      <tr>
 </table></div></td></tr>
 
 <tr><td> <div><table width=100%  >   
  <tr id="ma">
      <td align="center">
          <b>Solicitante</b>
      
      </td>  
      <tr>
 </table></div></td></tr>
<tr><td> <div><table width=100% align=center >        
    <tr>
     <td align="right" >
        <b>Efector: </b>
      </td>
      <?if($cuie_solic){
			$query_user=  "SELECT * FROM nacer.efe_conv where cuie='$cuie_solic'";
			$res_user=sql($query_user,"consulta 01")or die();
			$cuie_solic=$res_user->fields['cuie'];
			$nombre_sol=$res_user->fields['nombre'];			
		}	?>

      <td>    
      <div class="input_container">
          <input type="text" id="financiador_id1" onkeyup="autocomplet_efec()" value="<?=$nombre_sol?>" autocomplete="off" >
            <ul id="financiador_list_id1"></ul>

            <input type="hidden" name="cuie_solic" value="<?=$cuie_solic?>" id="cuie_solic" >
      </div>
      
  </tr> 
 </table></div></td></tr>
 
 
 <tr><td> <div><table width=100%  >   
  <tr id="ma">
      <td align="center">
          <b>Efector a derivar</b>
      
      </td>  
      <tr>
 </table></div></td></tr>
<tr><td> <div><table width=100% align=center >        
    <tr>
     <td align="right" >
        <b>Derivar a: </b>
      </td>
      <?if($cuie_efe_deriv){
          $sql=  "SELECT * FROM nacer.efe_conv where cuie='$cuie_efe_deriv'";
          $res_descripcion=sql($sql) or fin_pagina();
          $cuie_efe_deriv=$res_descripcion->fields['cuie'];
          $nombre_efe=$res_descripcion->fields['nombre'];
        } ?>

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
    <td><input type=text id='fecha_deriv' name='fecha_deriv' value='<?=fecha($fecha_deriv)?>' size=15 >
          <?=link_calendario("fecha_deriv");?> 
    </td>
  </tr>
</table></div></td></tr>

            
            
<tr><td> <div><table width=100% align=center >   
 <?if ($id_smiafiliados != ''){?>
      
      <tr align="center">
       <td>
          <input type='submit' name='guardar' value='Guardar Planilla' onclick="return control_nuevos()"
            title="Guardar datos de la Planilla" >
       </td>
      </tr>
  <?}?>     
</table></div></td></tr>
 </tr> 
</table>        
<br>
<?if  ($id_smiafiliados != '') {?>
<tr><td><table width="100%" class="bordes" align="center">
  <tr align="center" id="mo">
    <td align="center" width="3%">
     <img id="imagen_2" src="<?=$img_ext?>" border=0 title="Derivaciones solicitadas" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida2,2);" >
    </td>
    <td align="center">
     <b>DERIVACIONES</td>
		 </tr>
</table></td></tr>	
<tr><td><table id="prueba_vida2" border="1" width="100%" style="display:inline;border:thin groove">
  <tr><td><table width=100% align="center" class="bordes">
  <? $q_deriv="SELECT
              cuie_dev.nombre AS nom_deriv,
              cuie_solc.nombre AS nom_solc,
              sistema.usuarios.nombre as user_name,
              sistema.usuarios.apellido as user_ap, *
              FROM
              derivacion.depar
              LEFT JOIN derivacion.efector_derivado ON derivacion.efector_derivado.id_deriv = derivacion.depar.id_deriv
              INNER JOIN nacer.efe_conv AS cuie_dev ON cuie_dev.cuie = derivacion.efector_derivado.cuie_efe_deriv
              INNER JOIN nacer.efe_conv AS cuie_solc ON cuie_solc.cuie = derivacion.depar.cuie_solic
              INNER JOIN derivacion.est_deriv ON derivacion.efector_derivado.confirmacion = derivacion.est_deriv.id_est
              INNER JOIN sistema.usuarios ON sistema.usuarios.id_usuario = derivacion.depar.usuario  where id_beneficiario=$id_smiafiliados";
    $res_q_deriv=sql($q_deriv,"consulta 2")or die();
      if ($res_q_deriv->RecordCount()==0){
        ?>
          <tr>
             <td align="center">
                <font size="3" color="Red"><b>No hay solicitud de derivaciones cargadas</b></font>
             </td>
           </tr>
        <?}else{  ?>
 <tr id='ma'>
                  <td align="left"> Fecha de Solicitud</td>
                  <td align="left">Efector de Origen</td>
                  <td align="left"> Derivado</td>
                  <td align="left"> Estado de derivacion</td>
                  <td align="left"> Usuario de carga</td>
                  <td align="center">editar</td>

 </tr>
<?while (!$res_q_deriv->EOF){?>
                  <tr>
                  <td align="center"><?=fecha($res_q_deriv->fields['fecha_solicitud']);?></td>
                  <td align="left"> <?=$res_q_deriv->fields['nom_solc']?></td>
                  <td align="left"> <?=$res_q_deriv->fields['nom_deriv']?></td>
                  <td align="left"> <?=$res_q_deriv->fields['estado']?></td>
                  <td align="left"> <?=$res_q_deriv->fields['user_name'].', '.$res_q_deriv->fields['user_ap']?></td>
                  <? $ref = encode_link("editar_derivacion.php",array("id_deriv"=>$res_q_deriv->fields['id_deriv'],"id_smiafiliados"=>$id_smiafiliados,"pagina"=>"pacientes_der.php"));// ins_admin_old_der
                    $onclick_elegir="location.href='$ref'";?>
                  <td align="center">         
                    <span class="glyphicon glyphicon-list-alt" onclick="<?="location.href='$ref'"?>" style='cursor:hand;'> <p onclick="<?="location.href='$ref'"?>" style='cursor:hand;'> Editar </p></span>
                  </td>
                  </tr>
              <?$res_q_deriv->MoveNext();
          }
}?>
            
 </tr> 
</table></td></tr>
<br>
<?}?>
 </tr> 
</table>    
<tr><td><table width=100% align="center" class="bordes">
 <tr>
     <td align="center">
        <input type=button name="volver" value="Volver" onclick="document.location='pacientes_der.php'"title="Volver al Listado" style="width=150px">     
     </td>
 </tr> 
</table></td></tr>
 </table>
</form>
 
<?=fin_pagina();// aca termino ?>
