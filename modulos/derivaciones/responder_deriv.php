<? require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$id_user=$_ses_user['id'];


// Update 

if ($_POST['guardar']=="Guardar Planilla"){
	 
    $query_user="SELECT * from sistema.usu_efec where id_usuario=$id_user";
    $res_user=sql($query_user,"consulta 01")or die();
    $fecha_deriv=fecha_db($fecha_deriv);


//-----------------------carga derivacion de paciente -----------------------------------
           
        $query2="UPDATE  derivacion.efector_derivado SET
                   confirmacion=$confirmacion,
                   causa='$causa',
                   fecha=now(),
                   user_resp=$id_user
                  where id_deref=$id_deref";  
        sql($query2, "Error en consulta 04") or fin_pagina();      

        
        $accion2="Se guardo correctamente";      


       
} //FIN Insert Beneficiarios

       

if ($_POST['borrar']=="Borrar"){

	
} //FIN Borrado Beneficiarios

//Busqueda de Beneficiarios

if ($id_deref) {

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
     	where derivacion.efector_derivado.id_deref='$id_deref'";

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
$cuie_efe_sol=$res_factura->fields['cuie_efe_sol'];
$confirmacion=$res_factura->fields['confirmacion'];

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

</script>
<form name='form1' action='responder_deriv.php' method='POST'>
<input type="hidden" value="<?=$id_deref?>" name="id_deref">
<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
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
            <input type="text" size="30" value="<?=$apellido?>" name="apellido"  <?if ($id_deref) echo "disabled";?> >
        </td>
        <td align="right">
         	  <b>Nombre:</b>
        </td>         	
        <td align='left'>
            <input type="text" size="30" value="<?=$nombre?>" name="nombre" <?if ($id_deref) echo "disabled";?>>
        </td>
      </tr>    
		  <tr>
     	  <td align="right">
				    <b>Tipo de Documento:</b>
			  </td>
			  <td align="left">			 	
			       <select name=tipo_doc Style="width=200px" <?php if ($id_deref) echo "disabled"?>>
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
            <input type="text" size="30" value="<?=$num_doc?>" name="num_doc" <?if ($id_deref) echo "disabled";?>>
        </td>
      </tr>
  
</table>  
<tr><td><table width="100%" class="bordes" align="center">
  <tr align="center" id="ma">
    <td align="center">
     <b>Informacion del paciente al momento de solicitar la derivacion</td>
		 </tr>
</table></td></tr>	
<tr><td> <div><table width=100% align=center >
    <tr>  
     </td>
       <td align="right">
         <b>Grupo y factor:</b>
      </td> 
      <td align='left'>
            <input type='text' name='grp_factor' value='<?=$grp_factor?>' size=7 align='right' <?if ($id_deriv) echo "disabled";?>>
      </td>
      <td align="right">
          <b>Edad Gestacional:</b>
      </td>  
      <td align='left'>
          <input type='text' name='ed_gest' value='<?=$ed_gest?>' size=10 align='right'<?if ($id_derivf) echo "disabled";?>>
      </td> 
   
      <td align="right">
              <b>Gesta:</b>
      </td> 
      <td align='left'>
            <input type='text' name='gesta' value='<?=$gesta?>' size=10 align='right'<?if ($id_deriv) echo "disabled";?>>
      </td>
    </tr>
    <tr>
      <td align="right">
              <b>Paridad:</b>
      </td> 
      <td align='left'>
            <input type='text' name='paridad' value='<?=$paridad?>' size=10 align='right'<?if ($id_deriv) echo "disabled";?>>
      </td>
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
    </tr>
</table></div></td></tr>

<tr><td> <div><table width=100% align=center >
<td align="right">
      <b>Fecha Diagnostico:</b>
    </td>
    <td><input type=text id='fecha_diag' name='fecha_diag' value='<?=fecha($fecha_diag)?>' size=15 >
          <?=link_calendario("fecha_diag");?> 
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
      
  <tr>
    <td align="right">
      <b>Fecha programada:</b>
    </td>
    <td><input type=text id='fecha_deriv' name='fecha_deriv' value='<?=fecha($fecha_deriv)?>' size=15 <?if ($id_deriv) echo "disabled";?>>
          <?=link_calendario("fecha_deriv");?> 
    </td>
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


<tr><td> <div><table width=100%  >   
  <tr>
   <td align="right">
          <b>Estado:</b>
      </td>  
      <td>
      	<select name='confirmacion' Style="width=257px" <?if ($id_lote) echo 'disabled'?>>
							<option value=-1>Seleccione</option>
							<?$query10="SELECT *
										FROM 
										derivacion.est_deriv
										ORDER BY estado ASC";
								$res_10=sql($query10,"Error en consulta Nº 1");?>	
							 <? while (!$res_10->EOF){
									$id_tipo_envio_temp=$res_10->fields['id_est'];
									$tipo_envio=$res_10->fields['estado'];?>
									<option value='<?=$id_tipo_envio_temp?>' <? if(trim($id_tipo_envio_temp)==trim($confirmacion))echo "selected"?>><?=$tipo_envio?></option>
									<?$res_10->movenext();
								}?>
							</select>
		</td>
      <td align="right">
      <b>Fecha programada:</b>
    </td>
    <td><input type=text id='fecha_deriv' name='fecha_deriv' value='<?=fecha($fecha_deriv)?>' size=15 <?if ($id_deriv) echo "disabled";?>>
          <?=link_calendario("fecha_deriv");?> 
    </td>
      </tr>
</table></div></td></tr>
<tr><td> <div><table width=100% align=center >        
    <tr>
	
	   <td align="right">
          <b>Motivo de rechazo:</b>
      </td>  
      <td align='left'>
          <textarea cols='150' rows='3' name='causa'><?=$causa;?></textarea>
	  </td> 
	 </tr> 
 </table></div></td></tr>

  
<tr><td><table width=100% align="center" class="bordes">
   <tr align="center">
       <td>
          <input type='submit' name='guardar' value='Guardar Planilla' onclick="return control_nuevos()"
            title="Guardar" >
       </td>
      </tr>
 <tr>

     <td align="center">
        <input type=button name="volver" value="Volver" onclick="document.location='pacientes_der.php'"title="Volver al Listado" style="width=150px">     
     </td>
 </tr> 
</table></td></tr>
 </table>
</form>
 
<?=fin_pagina();// aca termino ?>
