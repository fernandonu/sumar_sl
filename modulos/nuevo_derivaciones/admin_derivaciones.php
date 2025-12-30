<?
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$id_user=$_ses_user['id'];

if ($_ses_user['login']=='sebastian' or $_ses_user['login']=='gdagata') $hidden='';
  else $hidden='hidden';

//borrar archivos
if ($modo=="borrar_archivo") {
  $id=$parametros["id_archivo"];
  $filename=$parametros["filename"];
  $query="delete from derivacion_general.archivos where id=$id and clave_beneficiario='$clave_beneficiario'";
  sql($query, "Error al eliminar el Archivo ".$filename) or fin_pagina();
  if ((!$error)&&(unlink("./archivos/$filename"))) $msg="Se elimino el Archivo";
  else{
    echo "<script>alert('No se pudo borrar el archivo')</script>";
  }
  $new="";
  $modo="modif";
}


  //borrar registro
if ($borrar=="True"){
  
  $query = "DELETE from derivacion_general.depar 
            WHERE id_deriv=$id_deriv";
  $res_extras = sql($query, "Error al eliminar el registro") or fin_pagina();
  
  $query = "DELETE from derivacion_general.efector_derivado 
            WHERE id_deriv=$id_deriv";
  $res_extras = sql($query, "Error al eliminar el registro") or fin_pagina();
  
  $accion = "Se Elimino la derivacion Nro. " . $id_deriv;        
}

// Update Beneficiarios
if ($_POST['guardar_editar']=="Guardar"){
		
  $query_user="SELECT * from sistema.usu_efec
                  where id_usuario=$id_user";
   $r_usuario=sql($query_user,"consulta usuario") or die();

		
} //FIN Update

//Insert 
if ($_POST['guardar']=="Guardar Planilla"){
	 

$fecha_diag=fecha_db($fecha_diag);
$fecha_deriv=fecha_db($fecha_der);
$practica=$_POST['id_practica'];

//-----------------------carga derivacion de paciente -----------------------------------
   $q="select nextval('derivacion_general.depar_id_deriv_seq') as id_deriv";
        $id_deriv=sql($q, "Error al solicitar nextval 01") or fin_pagina();
        $id_deriv=$id_deriv->fields['id_deriv'];  
  
        $query="insert into derivacion_general.depar
            (id_deriv,
            id_beneficiario,
            cuie_solic,
            obs_deriv,
            fecha_solicitud,
            diag_cie10,
            usuario,
            profesional,
            fecha_diag,
            prioridad,
            practica,
            telefono,
            mail)
          values
          ( $id_deriv,
            $id_smiafiliados,
            '$cuie_solic',
            '$obs_deriv',
            '$fecha_deriv',                    
            '$id_cie',
            $id_user,
            '$profesional',
            '$fecha_diag',
            '$prioridad',
            $practica,
            '$telefono',
            '$mail')";  
        sql($query, "Error en consulta 02") or fin_pagina();  
        
        /*if(isset($_POST['telefono'])) {
          $telefono=$_POST['telefono'];
          $sql_update_benef="UPDATE uad.beneficiarios set 
                              telefono='$telefono',
                              tipo_transaccion='M',
                              estado_envio='n'
                              WHERE id_beneficiarios=$id_smiafiliados";
          sql($sql_update_benef, "Error en update beneficiarios") or fin_pagina();
        } */  

  //----------cargamos la primer solicitud de recepcion------------------------------------
        $q2="select nextval('derivacion_general.efector_derivado_id_deref_seq') as id_deref";
        $id_deref=sql($q2, "Error al solicitar nextval 03") or fin_pagina();
        $id_deref=$id_deref->fields['id_deref'];  
        
        $query2="insert into derivacion_general.efector_derivado
                   (id_deref,  cuie_efe_deriv, fecha_deriv, id_deriv, confirmacion,user_resp)
                 values
                  ( $id_deref,  '$cuie_efe_deriv', '$fecha_deriv', $id_deriv, 1, $id_user)";  
        sql($query2, "Error en consulta 04") or fin_pagina();      
        //log de seguridad    
        $q_log="select nextval('derivacion_general.log_efe_deriv_id_log_ef_seq') as id_log_ef";
        $log=sql($q_log, "Error al solicitar nextval 05") or fin_pagina();
        $id_log_ef=$log->fields['id_log_ef'];  
        $query_log="insert into derivacion_general.log_efe_deriv
                   (id_log_ef,  usuario, f_mod, id_deref,detalle)
                 values
                  ( $id_log_ef,  $id_user, 'now()', $id_deref, 'Se solicita nuevo permiso de derivacion')";  
        sql($query_log, "Error en consulta 06") or fin_pagina();  
        $accion="Se envio Solicitud";      
        

       
} //FIN Insert Beneficiarios


//Busqueda de Beneficiarios

if ($id_smiafiliados) {

/*$query="SELECT id_smiafiliados, afiapellido,afinombre,afidni,nombre,cuie,clavebeneficiario
      from nacer.smiafiliados
      left join nacer.efe_conv on (cuieefectorasignado=cuie)
      where id_smiafiliados='$id_smiafiliados'";*/
      
$query="SELECT id_beneficiarios as id_smiafiliados, 
              apellido_benef as afiapellido,
              nombre_benef as afinombre,
              numero_doc as afidni,
              fecha_nacimiento_benef as fecha_nac,
              calle,numero_calle,
              clave_beneficiario as clavebeneficiario,
              telefono
      from uad.beneficiarios
      where id_beneficiarios='$id_smiafiliados'";

$res_factura=sql($query, "Error al traer el Comprobantes") or fin_pagina();

$nombre=$res_factura->fields['afinombre'];
$apellido=$res_factura->fields['afiapellido'];
$num_doc=$res_factura->fields['afidni'];
$tipo_doc=$res_factura->fields['tipo_doc'];
$clave_beneficiario=$res_factura->fields['clavebeneficiario'];
$id_smiafiliados=$res_factura->fields['id_smiafiliados'];
$fecha_nac=$res_factura->fields['fecha_nac'];
$direccion_benf=$res_factura->fields['calle'].' '.$res_factura->fields['numero_calle'];
$telefono=$res_factura->fields['telefono'];
}
echo $html_header;
cargar_calendario();

?>
<script src="app_derivaciones.js"></script>
<script src="funciones.js"></script>
<script>
warchivos=0;
function moveOver() {
	var boxLength;// = document.form1.compatibles.length;
  var prodLength = document.form1.sel_disponible.length;
  var selectedText;  // = document.choiceForm.available.options[selectedItem].text;
  var selectedValue; // = document.form1.productos.options[selectedItem].value;
  var i;
  var isNew = true;

  arrText = new Array();
  arrValue = new Array();
  var count = 0;

  for (i = 0; i < prodLength; i++) {
    if (document.form1.sel_disponible.options[i].selected) {
      arrValue[count] = document.form1.sel_disponible.options[i].value;
      arrText[count] = document.form1.sel_disponible.options[i].text;
      count++;
    }
	}
  for(j = 0; j < count; j++){
	  isNew = true;
		boxLength = document.form1.sel_afectado.length;
		selectedText=arrText[j];
 		selectedValue=arrValue[j];
		if (boxLength != 0) {
  	  for (i = 0; i < boxLength; i++) {
  		  thisitem = document.form1.sel_afectado.options[i].text;
      	if (thisitem == selectedText) {
        	isNew = false;
	      }
  	  }
	  }
  	if (isNew) {
  		newoption = new Option(selectedText, selectedValue, false, false);
	    document.form1.sel_afectado.options[boxLength] = newoption;
  	}
	  document.form1.sel_disponible.selectedIndex=-1;
  } 
}

function removeMe() {
  var boxLength = document.form1.sel_afectado.length;
  arrSelected = new Array();
  var count = 0;
  for (i = 0; i < boxLength; i++) {
    if (document.form1.sel_afectado.options[i].selected) {
      arrSelected[count] = document.form1.sel_afectado.options[i].value;
    }
    count++;
  }
  var x;
  for (i = 0; i < boxLength; i++) {
    for (x = 0; x < arrSelected.length; x++) {
      if (document.form1.sel_afectado.options[i].value == arrSelected[x]) {
        document.form1.sel_afectado.options[i] = null;
      }
    }
    boxLength = document.form1.sel_afectado.length;
  }
}
function val_text(){
	var a=new Array();
  var largo=document.form1.sel_afectado.length;
  var i=0;
  
  for(i;i<largo;i++){
  	a[i]=document.form1.sel_afectado.options[i].value;
  }
	document.form1.afectadosValues.value=a;
	document.form1.hguardar.value='sip';
	if ((typeof(document.form1.tcomentarios.value)!="undefined")&&(document.form1.tcomentarios.value!="")) document.form1.hcomentarios.value=document.form1.tcomentarios.value;
	else document.form1.hcomentarios.value=" ";
}
function update_disponibles(sel){
	var backup_value=new Array();
	var backup_text=new Array();
	var obj=document.form1.sel_disponible;
	
	document.form1.hlocacion.value=sel.options[sel.selectedIndex].text;

	if (document.form1.hbck_text.value==''){
		for(i=obj.length-1; i>=0; i--){
			backup_value[i]=obj.options[i].value+"|";
			backup_text[i]=obj.options[i].text+"|";
		}
		document.form1.hbck_value.value=backup_value;
		document.form1.hbck_text.value=backup_text;
	}
	
	var str_value= new String(document.form1.hbck_value.value);
	var str_text= new String(document.form1.hbck_text.value);
	backup_value=str_value.split("|");
	backup_text=str_text.split("|");
	for (i=obj.length-1; i>=0; i--) obj.options[i]=null;
	for (i=0, j=0; i<backup_text.length; i++){
		if ((backup_text[i].indexOf(document.form1.hlocacion.value)!=-1)||(document.form1.hlocacion.value==" ")){
			if (i!=0){
				var strt=backup_text[i].substring(1, backup_text[i].length);
				var strv=backup_value[i].substring(1, backup_value[i].length);
			}else{
				var strt=backup_text[i];
				var strv=backup_value[i];
			}
			newoption = new Option(strt, strv, false, false);
		  obj.options[j++] = newoption;
		}
	}	
}
function clearfields(){
	document.form1.afectadosValues.value="";
	document.form1.hlocacion.value="";
	document.form1.hbck_value.value="";
	document.form1.hbck_text.value="";
	document.form1.hguardar.value="";
}
</script>

<link rel = "stylesheet" type = "text/css" href = "style.css" />

<form name='form1' action='admin_derivaciones.php' method='POST'>
<input type="hidden" value="<?=$id_smiafiliados?>" name="id_smiafiliados">
<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<?//echo "<center><b><font size='+1' color='Blue'>$accion2</font></b></center>";?>
<table width="97%" cellspacing=0 border="1" bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
    	
    	<font size=+1><b>RED PROVINCIAL DE ATENCION Y DERIVACION</b></font>   
    	
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
			       <select name=tipo_doc Style="width=180px" <?php if (($id_smiafiliados) and ($tipo_transaccion != "M"))echo "disabled"?>>
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
      
      <tr>
        <td align="right">
            <b>Fecha Nacimiento:</b>
        </td>
        <td align='left' width="30%">
            <input type="text" size="30" value="<?=fecha($fecha_nac)?>" name="fecha_nac" <?if ($id_smiafiliados) echo "disabled";?>>
        </td>
        <td align="right">
            <b>Numero Telefono:</b>
        </td>
        <td align='left' width="30%">
            <input type="text" size="30" value="<?=$telefono?>" name="telefono">
        </td>
      </tr>
      <tr>
        <td align="right">
            <b>Direccion:</b>
        </td>
        <td align='left' width="30%">
            <input type="text" size="30" value="<?=$direccion_benf?>" name="direccion">
        </td>
        <td align="right">
            <b>Mail:</b>
        </td>
        <td align='left' width="30%">
            <input type="text" size="30" value="<?=$mail?>" name="mail">
        </td>
      </tr>
</table>  

<script type="text/javascript">
  var img_ext="<?=$img_ext='../../imagenes/rigth2.gif' ?>";//imagen extendido
  var img_cont="<?=$img_cont='../../imagenes/down2.gif' ?>";//imagen contraido
</script>

<tr><td><table width="100%" class="bordes" align="center">
  <tr align="center" id="mo">
    <td align="center" width="3%">
     <img id="imagen_2" src="<?=$img_ext?>" border=0 title="Solicitu de traslado" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida1,2);" >
    </td>
    <td align="center">
     <b>SOLICITUD DE TURNO</b>
    </td>
  </tr>
</table></td></tr>


<tr><td><table id="prueba_vida1" border="0" width="100%" style="display:inline;">
<tr><td><table width="140%" align="center">
  <tr align="center" id="ma">
    <td align="center">
     <b>Informacion del paciente al momento de solicitar la derivacion</td>
     </tr>
</table></td></tr>  
<tr><td> <div><table width=140% align="center" >
    
    <tr>
      <td align="right">
      <b>Fecha Solicitud del Turno:</b>
    </td>
    <td><input type=text id='fecha_der' name='fecha_der' value='<?=fecha($fecha_der)?>' size=15 ><?=link_calendario("fecha_der");?> 
    </td>
        <td align="right">
            <b>Profesional:</b>
        </td> 
        <td align='left'>
              <input type='text' name='profesional' value='<?=$profesional?>' size=30 align='right'>
        </td>  
          
    </tr>
    
    <tr>
     <td align="right">
      <b>Fecha Diagnostico:</b>
      </td>
      <td><input type=text id='fecha_diag' name='fecha_diag' value='<?=fecha($fecha_diag)?>' size=15><?=link_calendario("fecha_diag");?> 
      </td>
      
      
        <tr>
          <td align="right">
            <b>Nivel de Prioridad:</b>
          </td>
          <td align='left' width="30%">
              <select name="prioridad" >
                <option value="-1">Seleccione</option>}
                <option value="Alta">ALTA (menos de 7 dias)</option>
                <option value="Media">MEDIA (entre 7 y 15 dias)</option>
                <option value="Baja">BAJA (mas de 15 dias)</option>
              </select>
          </td>
        </tr>
    </tr>   
</table></div></td></tr>

<tr><td> <div><table width=150% align=center >        
      <tr>
      <td align="right" >
           <b>Diagnostico CIE 10:</b> 
      </td>
      <td>      
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
      <td align="right" >
           <b>Practica Solicitada:</b> 
      </td>
      <td>      
      <?
      $sql_practica= "SELECT * FROM derivacion_general.practica";
      $res_practica=sql($sql_practica) or fin_pagina();
      if($res_practica->RecordCount()==1){
        $descripcion=$res_efectores->fields['descripcion'];
        $id_practica=$res_efectores->fields['id_practica'];
      }?>

      <div class="input_container">
      <input type="text" id="practica" onkeyup="autocomplet_practica()" value="<?=$descripcion?>" autocomplete="off" >
      <ul id="practicas_list"></ul>
      <input type="hidden" name="id_practica" value="<?php echo $id_practica?>" id="id_practica" >
      </div>       
    </td>
    </tr>
    <tr>
      <td align="right">
          <b>Referencia:</b>
      </td> 
      <td align='left'>
          <textarea cols='100' rows='4' name='obs_deriv'><?php echo $obs_deriv;?></textarea>
      </td>  
      <tr>
</table></div></td></tr>
</table></div></td></tr>

 
 <tr><td> <div><table width=100%  >   
  <tr id="ma">
      <td align="center">
          <b>Efector Solicitante</b>
      
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

            <input type="hidden" name="cuie_solic" value="<?php echo $cuie_solic?>" id="cuie_solic" >
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

            <input type="hidden" name="cuie_efe_deriv" value="<?php echo $cuie_efe_deriv?>" id="cuie_efe_deriv" >
      </div>
      
  </tr> 
</table></div></td></tr>
<tr align="center">
<td>
  <input type='submit' class='btn btn-warning' name='guardar' value='Guardar Planilla' onclick="return control_nuevos()"
    title="Guardar datos de la Planilla" >
</td>
</tr>
</table></div></td></tr>

<?php //codigo para archivos?>
<tr><td><table width="100%" class="bordes" align="center">
<tr align="center" id="mo">
  <td align="center" width="3%">
  <img id="imagen_2" src="<?php echo $img_ext?>" border=0 title="Derivaciones solicitadas" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida2,2);" >
  </td>
  <td align="center"><b>ARCHIVOS ADJUNTOS</td>
</tr>
</table></td></tr>	

<tr><td><table id="prueba_vida2" border="0" width="100%" style="display:inline;">
  <tr><td><table class="table table-striped" width=150% align="center">

<table align = 'center' class="table table-striped table-advance table-hover">
	<?php if ($clave_beneficiario){?>
	<table border="1" class="table table-striped table-advance table-hover" align="center">
  <?$sql_archivos="select * from derivacion_general.archivos where clave_beneficiario='$clave_beneficiario'";
  $rta_archivos=sql($sql_archivos) or fin_pagina();?>
  Archivos (<?php echo $rta_archivos->recordcount()?> en total)
  <input class="btn btn-primary" type="button" name="bagregar" 
  value="Agregar" 
  onclick="if (typeof(warchivos)=='object' && warchivos.closed || warchivos==false) 
          warchivos=window.open('<?php echo encode_link('archivos_subir_derivaciones.php',
                array("id_registro"=>$clave_beneficiario, 
                      "user"=>$_ses_user["name"], 
                      "onclickaceptar"=>"window.self.focus();", 
                      "proc_file"=>"./orden_file_proc.php")) ?>',
                      '','toolbar=0,location=0,directories=0,status=0, menubar=0,scrollbars=1'); 
                        else warchivos.focus()" <?php echo $new?>>
				<?php if ($rta_archivos->recordcount()>0){?>
				<thead>
				<tr>
					<td align="right" >Archivo</td>
					<td align="right" >Fecha</td>
					<td align="right" >Subido por</td>
					<td align="right" >Tamaño</td>
					<td align="right" >&nbsp;</td>
				</tr>
				</thead>
	<?php	while (!$rta_archivos->EOF){?>
    <tr style='font-size: 9pt'>
      <td align=center>
        <?if (is_file("./archivos/".$rta_archivos->fields["nombre"])) 
        echo "<a target=_blank href='".encode_link("../archivos/archivos_lista.php", 
        array ("file" =>$rta_archivos->fields["nombre"],
              "size" => $rta_archivos->fields["size"],
              "cmd" => "download_derivacion"))."'>";
          echo $rta_archivos->fields["nombre"]."</a>";?>
          
        </td>
          <td align=center>&nbsp;<?php echo Fecha($rta_archivos->fields["fecha"])?></td>
        <td align=center>&nbsp;<?php echo  $rta_archivos->fields["creadopor"] ?></td>
        <td align=center>&nbsp;<?php echo $size=number_format($rta_archivos->fields["size"] / 1024); ?> Kb</td>	    			
<?php $lnk=encode_link("$_SERVER[PHP_SELF]",Array(
                      "clave_beneficiario"=>$clave_beneficiario,
                      "id_deriv"=>$result->fields['der'],
                      "id_deref"=>$result->fields['id_deref'],
                      "confirmacion"=>$result->fields['confirmacion'],
                      "pagina"=>"list_derivaciones_general.php",
                      "id_archivo"=>$rta_archivos->fields["id"],
                      "filename"=>$rta_archivos->fields["nombre"],
                      "modo"=>"borrar_archivo"));
  $onclick_eliminar="if (confirm('Esta Seguro que Desea Eliminar el Archivo ".$rta_archivos->fields["nombre"]."?')) location.href='$lnk'
  else return false;	";?>		      		
  <td onclick="<?php echo $onclick_eliminar?>" align="center">
  <i class='glyphicon glyphicon-remove-circle'></i> Eliminar
  </td>
  </tr>
<?php $rta_archivos->movenext();
    }
  }?>

  </table>
<?}?>
</table>
  </td></tr></table>
</td></tr></table>
<?php //end codigo para archivos ?>

</tr> 
</table>        
<br>
<?if  ($id_smiafiliados != '') {?>
<tr><td><table width="100%" class="bordes" align="center">
  <tr align="center" id="mo">
    <td align="center" width="3%">
     <img id="imagen_2" src="<?php echo $img_ext?>" border=0 title="Derivaciones solicitadas" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida3,3);" >
    </td>
    <td align="center">
     <b>TURNOS SOLICITADOS</td>
		 </tr>
</table></td></tr>	
<tr><td><table id="prueba_vida3" border="0" width="100%" style="display:inline;">
  <tr><td><table class="table table-striped" width=150% align="center">
  <? $q_deriv="SELECT
              cuie_dev.nombre AS nom_deriv,
              cuie_solc.nombre AS nom_solc,
              sistema.usuarios.nombre as user_name,
              sistema.usuarios.apellido as user_ap, *
              FROM
              derivacion_general.depar
              LEFT JOIN derivacion_general.efector_derivado ON derivacion_general.efector_derivado.id_deriv = derivacion_general.depar.id_deriv
              INNER JOIN nacer.efe_conv AS cuie_dev ON cuie_dev.cuie = derivacion_general.efector_derivado.cuie_efe_deriv
              INNER JOIN nacer.efe_conv AS cuie_solc ON cuie_solc.cuie = derivacion_general.depar.cuie_solic
              INNER JOIN derivacion_general.est_deriv ON derivacion_general.efector_derivado.confirmacion = derivacion_general.est_deriv.id_est
              INNER JOIN sistema.usuarios ON sistema.usuarios.id_usuario = derivacion_general.depar.usuario  where id_beneficiario=$id_smiafiliados";
              
    $res_q_deriv=sql($q_deriv,"consulta 2") or fin_pagina();
      if ($res_q_deriv->RecordCount()==0){
        ?>
          <tr>
             <td align="center">
                <font size="3" color="Red"><b>No hay solicitud de derivaciones cargadas</b></font>
             </td>
           </tr>
        <?}else{  ?>
 <tr id='ma'>
                  <td align="center" width=10%> Fecha de Solicitud</td>
                  <td align="center" width=20%> Efector de Origen</td>
                  <td align="center" width=20%> Derivado</td>
                  <td align="center" width=10%> Estado de derivacion</td>
                  <td align="center" width=10%> Prioridad</td>
                  <td align="center" width=20%> Usuario de carga</td>
                  <td align="center" width=10%> Ver Reg.</td>
                  <td <?php echo $hidden;?> align="center" width=10%> Borrar Reg.</td>

 </tr>
<?php 
  //$id_deref=$res_q_deriv->fields['id_deref'];
  

while (!$res_q_deriv->EOF){
  $id_deriv=$res_q_deriv->fields['id_deriv'];
  $ref1 = encode_link("admin_derivaciones.php",array("id_deriv"=>$res_q_deriv->fields['id_deriv'],"id_smiafiliados"=>$id_smiafiliados,"borrar"=>"True"));
  $onclick_borrar="if (confirm('Esta Seguro que Desea Eliminar?')) location.href='$ref1'
                    else return false; ";
      switch ($res_q_deriv->fields['prioridad']) {
        case 'Alta': $estilo="style='background-color:#FB6C6C'";break;
          
          case 'Media':$estilo="style='background-color:#EFFB6C'";break;
          
          case 'Baja':$estilo="style='background-color:#80FB6C'";break;
        
        default:$estilo="";break;
      } ?>
        <tr>
        <td align="center" width=10%><?=fecha($res_q_deriv->fields['fecha_solicitud']);?></td>
        <td align="center" width=20%> <?=$res_q_deriv->fields['nom_solc']?></td>
        <td align="center" width=20%> <?=$res_q_deriv->fields['nom_deriv']?></td>
        <td align="center" width=10%> <?=$res_q_deriv->fields['estado']?></td>
        <td align="center" width=10% <?php echo $estilo;?>> <?=$res_q_deriv->fields['prioridad']?></td>
        <td align="center" width=20%> <?=$res_q_deriv->fields['user_name'].', '.$res_q_deriv->fields['user_ap']?></td>
        <? $ref = encode_link("res_derivacion_general.php",array("id_deriv"=>$id_deriv,"pagina"=>"admin_derivaciones"));// ins_admin_old_der
          $onclick_elegir="location.href='$ref'";?>
        <td align="center" width=10%>         
          <span class="glyphicon glyphicon-list-alt" onclick="<?="location.href='$ref'"?>" style='cursor:hand;'> <p onclick="<?="location.href='$ref'"?>" style='cursor:hand;'>Ver</p></span>
        </td>
        
        <td <?echo $hidden;?> align="center" width=10%>         
          <span class="glyphicon glyphicon-trash" style='cursor:hand;'> <p onclick="<?php echo $onclick_borrar;?>">Borrar</p></span>
        </td>
        </tr>
              <?$res_q_deriv->MoveNext();
          }
}?>
            
  
</table></td></tr>
<br>
<?}?>
 </tr> 
</table>    
<tr><td><table width=100% align="center">
 <tr>
     <td align="center">
        <input type=button class='btn btn-info' name="volver" value="Volver" onclick="document.location='listado_pacientes_der.php'"title="Volver al Listado" style="width=150px">     
     </td>
 </tr> 
</table></td></tr>
 </table>
</form>
 
<?php echo fin_pagina();// aca termino ?>
