<? require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$id_user=$_ses_user['id'];

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



// Update 

if ($_POST['guardar']=="Guardar Planilla"){
	 
  $query_user="SELECT * from sistema.usu_efec where id_usuario=$id_user";
  $res_user=sql($query_user,"consulta 01")or die();
  
  $fecha_deriv=fecha_db($fecha_deriv);


//-----------------------carga derivacion de paciente -----------------------------------
  if (stripos($fecha_deriv_1,"/")<3) {
      $fecha_deriv_1=fecha_db($fecha_deriv_1);
    };
  

  if ($confirmacion=='CONFIRMADO') {
    $query2="UPDATE  derivacion_general.efector_derivado SET
             causa='$causa'
            where id_deref=$id_deref";  
            
    sql($query2, "Error en consulta 04") or fin_pagina();
   
  }else {
  $fecha_deriv_1=($fecha_deriv_1)?$fecha_deriv_1:'1800-01-01';
  $query2="UPDATE  derivacion_general.efector_derivado SET
             confirmacion=$confirmacion,
             --causa='$causa',
             fecha_programada='$fecha_deriv_1',
             hora_programada='$hora',
             user_resp=$id_user,
             profesional='$profesional_derivado'
            where id_deref=$id_deref";  
  sql($query2, "Error en consulta 04") or fin_pagina();      
   }     
  $accion="Se guardo correctamente";      

} //FIN Insert Beneficiarios

       

if ($_POST['borrar']=="Borrar"){

	
} //FIN Borrado Beneficiarios

//Busqueda de Beneficiarios

if ($id_deriv) {

$query="SELECT
    cuie_dev.cuie AS cuie_efe_deriv,
    cuie_solc.cuie AS cuie_efe_sol,
    cuie_solc.nombre as nombre_sol, *,
    derivacion_general.practica.descripcion as dec_practica,
    derivacion_general.depar.profesional as profesional_sol,
    derivacion_general.efector_derivado.profesional as profesional_der,
    derivacion_general.depar.telefono as telefono_der,
    derivacion_general.depar.mail as mail_der,
    derivacion_general.efector_derivado.causa as contrareferencia
    FROM
    derivacion_general.depar
    LEFT JOIN derivacion_general.efector_derivado ON derivacion_general.efector_derivado.id_deriv = derivacion_general.depar.id_deriv
    INNER JOIN nacer.efe_conv AS cuie_dev ON cuie_dev.cuie = derivacion_general.efector_derivado.cuie_efe_deriv
    INNER JOIN nacer.efe_conv AS cuie_solc ON cuie_solc.cuie = derivacion_general.depar.cuie_solic
    INNER JOIN derivacion_general.est_deriv ON derivacion_general.efector_derivado.confirmacion = derivacion_general.est_deriv.id_est
    INNER JOIN uad.beneficiarios ON derivacion_general.depar.id_beneficiario = uad.beneficiarios.id_beneficiarios
    LEFT JOIN derivacion_general.est_general ON derivacion_general.est_general.id_deriv = derivacion_general.depar.id_deriv
    INNER JOIN nacer.cie10 ON nacer.cie10.id10=derivacion_general.depar.diag_cie10
    INNER JOIN derivacion_general.practica ON derivacion_general.practica.id_practica=derivacion_general.depar.practica
      --where derivacion_general.efector_derivado.id_deref='$id_deref'
      WHERE derivacion_general.depar.id_deriv=$id_deriv";

$res_factura=sql($query, "Error al traer el Comprobantes") or fin_pagina();

$id_beneficiario=$res_factura->fields['id_beneficiario'];
$nombre=$res_factura->fields['nombre_benef'];
$apellido=$res_factura->fields['apellido_benef'];
$num_doc=$res_factura->fields['numero_doc'];
$tipo_doc=$res_factura->fields['tipo_doc'];
$clave_beneficiario=$res_factura->fields['clave_beneficiario'];
$fecha_nac=$res_factura->fields['fecha_nacimiento_benef'];
$direccion_benef=$res_factura->fields['calle'].' '.$res_factura->fields['numero_calle'];
$fecha_diag=$res_factura->fields['fecha_diag'];
$obs_deriv=$res_factura->fields['obs_deriv'];
$fecha_solicitud=$res_factura->fields['fecha_solicitud'];
$dec10=$res_factura->fields['dec10'];
$dec_practica=$res_factura->fields['dec_practica'];
$profesional=$res_factura->fields['profesional'];
$fecha_der=$res_factura->fields['fecha_solicitud'];
$cuie_efe_deriv=$res_factura->fields['cuie_efe_deriv'];
$cuie_efe_sol=$res_factura->fields['cuie_efe_sol'];
$nombre_solic=$res_factura->fields['nombre_sol'];
$confirmacion=$res_factura->fields['confirmacion'];
$prioridad=$res_factura->fields['prioridad'];

$fecha_programada=$res_factura->fields['fecha_programada'];
$hora_programada=$res_factura->fields['hora_programada'];
$profesional_derivado=$res_factura->fields['profesional_der'];
$profesional_solicitante=$res_factura->fields['profesional_sol'];

$telefono=$res_factura->fields['telefono_der'];
$mail=$res_factura->fields['mail_der'];

$estado_der=$res_factura->fields['estado'];
$causa=$res_factura->fields['contrareferencia'];

}

echo $html_header;
cargar_calendario();

?>
<script>
//empieza funcion mostrar tabla
var img_ext='<?php echo $img_ext='../../imagenes/rigth2.gif' ?>';//imagen extendido
var img_cont='<?php echo $img_cont='../../imagenes/down2.gif' ?>';//imagen contraido
</script>
<script src="app_res_derivacion_general.js"></script>

<script>    
    $(function () {

        /* setting time */
        $("#timepicker").datetimepicker({
            format : "HH:mm:ss"
        });

    }    
</script>

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


<form name='form1' action='res_derivacion_general.php' method='POST'>
<input type="hidden" value="<?php echo $id_deref?>" name="id_deref">
<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<table width="97%" cellspacing=0 border="1" bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
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
            <input type="text" size="30" value="<?php echo $apellido?>" name="apellido"  <?if ($id_deref) echo "disabled";?> >
        </td>
        <td align="right">
         	  <b>Nombre:</b>
        </td>         	
        <td align='left'>
            <input type="text" size="30" value="<?php echo $nombre?>" name="nombre" <?if ($id_deref) echo "disabled";?>>
        </td>
      </tr>    
		  <tr>
     	  <td align="right">
				    <b>Tipo de Documento:</b>
			  </td>
			  <td align="left">			 	
			     <input type="text" size="30" value="<?php echo $tipo_doc?>" name="tipo_doc" disabled>  
			  </td>
        <td align="right" width="20%">
            <b>N&uacute;mero de Documento:</b>
        </td>           
        <td align='left' width="30%">
            <input type="text" size="30" value="<?php echo $num_doc?>" name="num_doc" <?if ($id_deref) echo "disabled";?>>
        </td>
      </tr>
      
      <tr>
        <td align="right">
            <b>Fecha Nacimiento:</b>
        </td>
        <td align='left' width="30%">
            <input type="text" size="30" value="<?php echo fecha($fecha_nac)?>" name="fecha_nac" <?if ($id_smiafiliados) echo "disabled";?>>
        </td>
        <td align="right">
            <b>Numero Telefono:</b>
        </td>
        <td align='left' width="30%">
            <input type="text" size="30" value="<?php echo $telefono?>" name="telefono" <?if ($id_deref) echo "readonly";?>>
        </td>
      </tr>
      <tr>
        <td align="right">
            <b>Direccion:</b>
        </td>
        <td align='left' width="30%">
            <input type="text" size="30" value="<?php echo $direccion_benef?>" name="direccion" <?if ($id_deref) echo "readonly";?>>
        </td>
        <td align="right">
            <b>Mail:</b>
        </td>
        <td align='left' width="30%">
            <input type="text" size="30" value="<?php echo $mail?>" name="mail" <?if ($id_deref) echo "readonly";?>>
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
      <b>Fecha Solicitud de Derivacion:</b>
    </td>
    <td><input type=text name='fecha_der' value='<?php echo fecha($fecha_der)?>' size=15 readonly >
    </td>
        <td align="right">
            <b>Profesional:</b>
        </td> 
        <td align='left'>
              <input type='text' name='profesional' value='<?php echo $profesional_solicitante?>' size=30 align='right' readonly>
        </td>  
          
    </tr>
    
    <tr>
     <td align="right">
      <b>Fecha Diagnostico:</b>
      </td>
      <td><input type=text id='fecha_diag' name='fecha_diag' value='<?php echo fecha($fecha_diag)?>' size=15 readonly> 
      </td>
      
        
        <tr>
          <td align="right">
            <b>Nivel de Prioridad:</b>
          </td>
          <td align='left' width="30%">
           <input type=text name='grupo' value='<?php echo $prioridad?>' size=15 disabled>    
          </td>
        </tr>
    </tr>   
</table></div></td></tr>

<tr><td> <div><table width=100% align=center >        
      <tr>
      <td align="right" >
           <b>Diagnostico CIE 10:</b> 
      </td>
      <td>
        <input type="text" syle="color:'grey'"name="id_cie" value="<?php echo $dec10?>" size=50 disabled>
      </td>
    </tr>
        
    <tr>
      <td align="right" >
           <b>Practica Solicitada:</b> 
      </td>
      <td>     
      <input type="text" name="dec_practica" value="<?php echo $dec_practica?>" size=50 disabled>
      </div>       
    </td>
    </tr>
</table></div></td></tr>
<tr><td> <div><table width=100%  >   
  <tr>
      <td align="right">
          <b>Referencia:</b>
      </td> 
      <td align='left'>
          <textarea cols='150' rows='4' name='obs_deriv' <?if ($id_deref) echo "disabled";?>><?php echo $obs_deriv;?></textarea>
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
     <?php if (isset($cuie_efe_sol)) $rd='readonly';
        else $rd='';     
     ?>
     <td align="right" >
        <b>CUIE: </b>
      </td>
      <td>    
      <input type="text" name="cuie_solic" value="<?php echo $cuie_efe_sol?>" size=15 <?echo $rd;?>>
      
      <td align="right" >
        <b>Nombre: </b>
      </td>
      <td>    
      <input type="text" name="nombre_solic" value="<?php echo $nombre_solic?>" size=50 <?echo $rd;?>>
      </tr>
      
      
 </table></div></td></tr>


            
<tr><td> <div><table width=100% align=center >   
 <?if ($id_deriv != ''){?>
      
      <tr align="center">
       <td>
          <input type='submit' name='guardar' value='Guardar Planilla' onclick="return control_nuevos()"
            title="Guardar datos de la Planilla" >
       </td>
      </tr>
  <?}?>     
</table></div></td></tr>
 </tr> 
<br>

<tr><td><table width="100%" class="bordes" align="center">
  <tr align="center" id="ma">
    <td align="center">
     <b>Respuesta de derivacion</b>
     </td>
	</tr>
</table></td></tr>	
<?php
if ($pagina=='desde_res_de_gral' 
  or $pagina=='admin_derivaciones'
  or $confirmacion=='2') $readonly='disabled';
        else $readonly='';
?>
<tr><td> <div><table width=100%  >   
  <tr>
   <td align="right">
          <b>Estado:</b>
      </td>  
      <td>
      	<? if ($res_factura->fields['estado']=='PENDIENTE') {?>
          <select name='confirmacion' Style="width=257px" <?if ($id_lote) echo 'disabled'?> <?php echo $readonly;?>>
  							<option value=-1>Seleccione</option>
  							<?$query10="SELECT *
  										FROM 
  										derivacion_general.est_deriv
  										ORDER BY estado ASC";
  								$res_10=sql($query10,"Error en consulta Nº 1");?>	
  							 <? while (!$res_10->EOF){
  									$id_tipo_envio_temp=$res_10->fields['id_est'];
  									$tipo_envio=$res_10->fields['estado'];?>
  									<option value='<?php echo $id_tipo_envio_temp?>' <? if(trim($id_tipo_envio_temp)==trim($confirmacion))echo "selected"?>><?php echo $tipo_envio?></option>
  									<?$res_10->movenext();
  								}?>
  							</select>
          <?}
            else {?>
		        <input type="text" name="confirmacion" id="confirmacion" size=30 value='<?php echo $res_factura->fields['estado'];?>' readonly></td>
            <?}?>
    </td>
    
    <td align="right">
      <b>Profesional:</b>
    </td>
    <td><input type="text" name="profesional_derivado" id="profesional_derivado" size=30 value='<?php echo $profesional_derivado;?>' <?php echo $readonly;?>></td>
      
      </tr>
      <tr>
    <td align="right">
      <b>Fecha programada:</b>
    </td>
    <td>
      <input type=text id='fecha_deriv_1' name='fecha_deriv_1' value='<?php echo fecha($fecha_programada)?>' size=15 <?echo $readonly;?>>
        <?php echo ($pagina=='desde_res_de_gral' 
          or $pagina=='admin_derivaciones'
          or $confirmacion=='2')?'':link_calendario("fecha_deriv_1");?> 
    </td>
    
    <td align="right">
      <b>Hora Programada:</b>
    </td>
    <td>
      <input type=time id='hora' name='hora' value='<?php echo $hora_programada;?>' size=15 <?echo $readonly;?>>
    </td>
    </tr>
    <tr>
    
  </tr> 
</table></div></td></tr>
<tr><td> <div><table width=100% align=center >        
    <tr>
	   
	   <td align="right">
          <b>Contrareferencia:</b>
      </td>  
      <td align='left'>
          <textarea cols='150' rows='3' name='causa'
          <?php echo ($pagina=='desde_res_de_gral' or $pagina=='admin_derivaciones')?'readonly':'';?>><?php echo $causa;?></textarea>
	  </td> 
	 </tr> 
 </table></div></td></tr>
 
 <?php //codigo para archivos?>

 <tr><td>
<table class="table table-striped table-advance table-hover">
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
					<td align="center" >Archivo</td>
					<td align="center" >Fecha</td>
					<td align="center" >Subido por</td>
					<td align="center" >Tamaño</td>
					<td align="center" >&nbsp;</td>
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
</td>
</tr>
<?php //end codigo para archivos ?>
  
<tr><td><table width=100% align="center" class="bordes">
   <tr align="center">
   <td>
   <? if ($pagina=='admin_derivaciones') $readonly='disabled';
        else $readonly='';
        ?>
   <input type='submit' name='guardar' value='Guardar Planilla' class="btn btn-warning" <?php echo $readonly;?> onclick="return control_nuevos()"
        title="Guardar" >
   </td>
     </tr>
 <tr>

     <td align="center">
        <? ?>

        <?php if ($pagina=='desde_res_de_gral') {
                $ref = encode_link("list_der_solicitante.php",array());
                $onclick_elegir="location.href='$ref'";
                }
              
              elseif ($pagina=='admin_derivaciones') {
                $ref = encode_link("admin_derivaciones.php",array("id_smiafiliados"=>$id_beneficiario));
                $onclick_elegir="location.href='$ref'";
                }
              
              else {$ref = encode_link("list_derivaciones_general.php",array());
                $onclick_elegir="location.href='$ref'";
              }
          ?>
        <!--<input type=button name="volver" value="Volver" class="btn btn-primary" onclick="document.location='<?echo $link_pagina.'.php';?>'"title="Volver al Listado" style="width=150px">--> 
        <input type=button name="volver" value="Volver" class="btn btn-primary" onclick="<?php echo $onclick_elegir;?>" title="Volver al Listado" style="width=150px">    
     </td>
 </tr> 
</table></td></tr>
 </table>
</form>
 
<?php echo fin_pagina();// aca termino ?>
