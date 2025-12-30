<?
require_once ("../../config.php"); 
require_once("funciones_test_findrisk.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();
$user_cuie=substr($_ses_user['login'],0,6);


if ($_POST['guardar_editar']=="Guardar"){	
    $fecha_carga= date("Y-m-d");
    $usuario=$_ses_user['id'];
    $usuario = substr($usuario,0,9);
    $clave_beneficiario=$_POST['clavebeneficiario'];
    $edad=$_POST['edad'];
    $sexo=$_POST['sexo'];
    $fecha_nac=$_POST['fecha_nac'];
    if ($_POST['num_form_remediar']=='') $num_form_remediar=$clave_beneficiario;
    else $num_form_remediar=$_POST['num_form_remediar'];
    $puntaje_final=$_POST['puntaje_final'];
    $fechaempadronamiento=Fecha_db($_POST['fechaempadronamiento']);
    $cuie=$_POST['cuie'];
    
    $puntaje_final=$_POST['puntaje_final'];
    $db->StartTrans();

    $q="select * from uad.remediar_x_beneficiario where clavebeneficiario='$clave_beneficiario'";
    $val=sql($q, "Error en consulta de validacion") or fin_pagina();
    if ($val->RecordCount()!=0){
        $Id_formulario=$val->fields['id_r_x_b'];
        $fecha_carga=date("Y-m-d");
		    $usuario=$_ses_user['name'];
		
		    $query2="update uad.remediar_x_beneficiario set 
          				test_findrisk=$puntaje_final,
          				usuario_test='$usuario',
          				fecha_test_findrisk='$fecha_carga',
                          cuie_test='$cuie'
                          where id_r_x_b=$Id_formulario";

        sql($query2, "Error al verificar datos") or fin_pagina();
        $accion="Se actualizo la planilla Remediar con el test de Findrisk";
        } else {  
                $q="select nextval('uad.remediar_x_beneficiario_id_r_x_b_seq') as id";
                $id_Idformulario=sql($q) or fin_pagina();
                $Id_formulario=$id_Idformulario->fields['id'];
				
				$fecha_carga=date("Y-m-d");
				$usuario=$_ses_user['name'];
				
                $query2="insert into uad.remediar_x_beneficiario (
                          id_r_x_b,
                          nroformulario,
                          clavebeneficiario,
                          fecha_test_findrisk,
                          test_findrisk,
                          usuario_test)
                        values(
                            $Id_formulario,
                            '$clave_beneficiario',
                            '$clave_beneficiario',
                            '$fecha_carga',
                            $puntaje_final,
                            '$usuario')";

                sql($query2, "Error al insertar remediar_x_beneficiario") or fin_pagina();
		        $accion="Se guardo Remediar";
                }
        
    
    //codigo para insertar en las tablas de prestacion y comprobante para facturar

    $query_benf="SELECT * from nacer.smiafiliados where clavebeneficiario='$clave_beneficiario'
                and activo='S'";
    $res_benef=sql($query_benf) or fin_pagina();
    if ($res_benef->RecordCount()>0)
      {
      $id_smiafiliados=$res_benef->fields['id_smiafiliados'];
      $afidni=$res_benef->fields['afidni'];
      $sexo=trim($res_benef->fields['afisexo']);
      $afifechanac=$res_benef->fields['afifechanac'];
      $nom_medico='';
      $comentario='Desde el Form. Test de Findrisc';
      $id_comprobante = insertar_comprobante ($cuie,$nom_medico,$fecha_carga,$clavebeneficiario,$id_smiafiliados,$fecha_carga,$comentario,$grupopoblacional);

    if ($fecha_carga) {
    $q1="SELECT * from facturacion.nomenclador_detalle 
        where '$fecha_carga' between fecha_desde and fecha_hasta 
        and modo_facturacion='4'";
    $res_q1=sql($q1) or fin_pagina();
    $id_nomenclador_detalle=$res_q1->fields['id_nomenclador_detalle'];
    $codigo='N031';
    $descripcion='Uso de la herramienta FINDRISC para identificación de riesgo de desarrollar diabetes';
    $diagnostico='A97';
    }

    $cont=insertar_prestacion($id_comprobante,$fecha_carga,$codigo,$descripcion,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
   }
   //end facturacion

    $db->CompleteTrans();

}

if($clave_beneficiario){ 

     $queryrmediar="SELECT * from uad.remediar_x_beneficiario 
                    inner join uad.beneficiarios on (clave_beneficiario=clavebeneficiario)
                    where clavebeneficiario='$clave_beneficiario'";

    $res_remediar=sql($queryrmediar, "Error al traer el Comprobantes") or fin_pagina();
    if ($res_remediar->RecordCount()>0){
    	$num_form_remediar=$res_remediar->fields['nroformulario'];
        $fecha_test=$res_remediar->fields['fecha_test_findrisk'];
    	$puntaje_final=$res_remediar->fields['test_findrisk'];
    	$fechaempadronamiento=fecha($res_remediar->fields['fechaempadronamiento']);
        $fecha_nac=fecha($res_remediar->fields['fecha_nacimiento_benef']);
        $cuie=$res_remediar->fields['cuie_test'];
        $sexo=trim($res_remediar->fields['sexo']);
        
    	}

}

echo $html_header;
?>
<script>
 //controlan que ingresen todos los datos necesarios par el muleto
function control_nuevos()
{
    if(document.all.num_form_remediar.value==""){
                 //alert("Debe completar el campo numero de formulario");
                // document.all.num_form_remediar.focus();
                // return false;
                 }else{
                        var num_form_remediar=document.all.num_form_remediar.value;
                        if(isNaN(num_form_remediar)){
                                alert('El dato ingresado en numero de formulario debe ser entero');
                                document.all.num_form_remediar.focus();
                                return false;
                        }
                 }
              
         
		 
           if(document.all.pregunta2.value=="-1"){
            alert("Debe completar el campo de la pregunta 2)");
            document.all.pregunta2.focus();
             return false;
           }

           if(document.all.pregunta3.value=="-1"){
            alert("Debe completar el campo de la pregunta 3)");
            document.all.pregunta3.focus();
             return false;
           }

           if(document.all.pregunta4.value=="-1"){
            alert("Debe completar el campo de la pregunta 4)");
            document.all.pregunta4.focus();
             return false;
           }

           if(document.all.pregunta5.value=="-1"){
            alert("Debe completar el campo de la pregunta 5)");
            document.all.pregunta5.focus();
             return false;
           }

           if(document.all.pregunta6.value=="-1"){
            alert("Debe completar el campo de la pregunta 6)");
            document.all.pregunta6.focus();
             return false;
           }

           if(document.all.pregunta7.value=="-1"){
            alert("Debe completar el campo de la pregunta 7)");
            document.all.pregunta7.focus();
             return false;
           }

           if(document.all.pregunta8.value=="-1"){
            alert("Debe completar el campo de la pregunta 8)");
            document.all.pregunta8.focus();
             return false;
           }
           
           
		   if(document.all.cuie.value=="-1"){
            alert("Debe elegir un centro inscriptor");
            document.all.cuie.focus();
             return false;
           }
		   
	
}//de function control_nuevos()


/**********************************************************/
//funciones para busqueda abreviada utilizando teclas en la lista que muestra los clientes.
var digitos=10; //cantidad de digitos buscados
var puntero=0;
var buffer=new Array(digitos); //declaraci?n del array Buffer
var cadena="";

function buscar_combo(obj)
{
   var letra = String.fromCharCode(event.keyCode)
   if(puntero >= digitos)
   {
       cadena="";
       puntero=0;
   }
   //sino busco la cadena tipeada dentro del combo...
   else
   {
       buffer[puntero]=letra;
       //guardo en la posicion puntero la letra tipeada
       cadena=cadena+buffer[puntero]; //armo una cadena con los datos que van ingresando al array
       puntero++;

       //barro todas las opciones que contiene el combo y las comparo la cadena...
       //en el indice cero la opcion no es valida
       for (var opcombo=1;opcombo < obj.length;opcombo++){
          if(obj[opcombo].text.substr(0,puntero).toLowerCase()==cadena.toLowerCase()){
          obj.selectedIndex=opcombo;break;
          }
       }
    }//del else de if (event.keyCode == 13)
   event.returnValue = false; //invalida la acci?n de pulsado de tecla para evitar busqueda del primer caracter
}//de function buscar_op_submit(obj)

//Validar Fechas
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

</script>

<form name='form1' action='test_findrisk.php' method='POST'>
<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<?echo "<center><b><font size='+1' color='Blue'>$accion2</font></b></center>";?>
<table width="97%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
        <font size=+1><b>Test de Findrisk</b></font>
    </td>
 </tr>
 <tr><td>
  <table width=100% align="center" class="bordes">
      <tr>
       <td>
        <table class="bordes" align="center">
         <tr>
           <td align="center" colspan="4" id="ma">
               <b> N&uacute;mero de Formulario: <font size="+1" color="Blue"><?=($clave_beneficiario)? $clave_beneficiario : $clave_beneficiario=$clavebeneficiario?></font> </b>
            <input type="hidden" value="<?=$clave_beneficiario?>" name="clavebeneficiario">
            <input type="hidden" value="<?=$fecha_nac?>" name="fecha_nac">
            <input type="hidden" value="<?=$sexo?>" name="sexo">
            <input type="hidden" value="<?=$vremediar?>" name="vremediar">
            <input type="hidden" value="<?=$campo_actual?>" name="campo_actual">
            <input type="hidden"  value="<?=$edad?>" name="edad">
            <input type="hidden"  value="<?=$pagina_viene_1?>" name="pagina_viene_1">
			<input type="hidden"  value="<?=$estado_envio?>" name="estado_envio">
           </td>
         </tr>

         </td>
       </tr>
       <tr>
       <tr id="mo">
       <td align="center" colspan="4" >
       
        </td>
        </tr>
        <tr id="ma">
     	<td align="left" colspan="4">
    	<b>Fecha de Empadronamiento:</b>
	   	 <input type=text name=fechaempadronamiento value='<?=$fechaempadronamiento;?>' size=15 <?php if ($num_form_remediar && $accion2!="No se encuentra formulario")echo "readOnly"; ?> onblur="esFechaValida(this); sumatoria();" onKeyUp="mascara(this,'/',patron,true);">
		    	 <?//if (!$num_form_remediar){ echo link_calendario("fechaempadronamiento");}?>
	    </td>
	    </tr>
        <tr id="mo">
        </tr>
        <tr id="ma">
        <td align="left" colspan="4">
        <b>Test de Findrisk</b>
        </td>
        </tr>
        <tr align="center">
        <td  colspan="4">
        <table width="100%" border="1" cellspacing="0" bordercolor="#006699" style="border:thin groove;">
        <tr >
        <td style=" padding-left: 40px"><b> 1) Sexo y edad </b></td>
        <td align="center"><?if($sexo=='F'){ echo 'Femenino';}else{ echo 'Masculino';}?></td>
        <td align="center"><?$edad_completo=edad_con_meses(fecha_db($fecha_nac),date("Y-m-d"));
                             echo $edad_completo['anos'];?> Años
        <input type="hidden"  value="<?=$id_factorriesgo?>" name="factorriesgo">
        <input type="hidden"  value="<?=$puntos_1?>" name="puntos_1">
				
	    
		</td>
        </tr>
        </table>
     	</td>
        </tr>
        <tr id="ma">
        <td align="left" colspan="4">
        <b>INDICE DE MASA CORPORAL</b>
        </td>
        </tr>
        <tr align="center">
        <td  colspan="4">
        <table width="100%" border="1" cellspacing="0" bordercolor="#006699" style="border:thin groove;">
        <tr>
        <td style=" padding-left: 40px" width="72%"><b>2) Indice de Masa Corporal
        &nbsp;Peso:Kilogramos / Talla:Metros * 2</b></td>
        <td align="center">
		<select name="pregunta2" Style="width:200px"
   		onKeypress="buscar_combo(this);"
		onblur="borrar_buffer(); sumatoria();"
		onchange="sumatoria();">
   	    <option value='-1'>Seleccione</option>
		<option value='0'>Menor de 25 Kg/m2 </option>
        <option value='1'>Entre de 25-30 Kg/m2 </option>
        <option value='3'>Mayor de 30 Kg/m2 </option>
		</select></td></tr>
                  
        </table>
        </td>
        </tr>
        <tr id="ma">
        <td align="left" colspan="4">
        <b>PERIMETRO DE CINTURA</b>
        </td>
        </tr>
        <tr align="center">
        <td  colspan="4">
        <table width="100%" border="1" cellspacing="0" bordercolor="#006699" style="border:thin groove;">
        <tr>
        <td style=" padding-left: 40px" width="72%"><b> 3) Per&iacute;metro de cintura medido por debajo de las costillas <br> &nbsp;&nbsp;&nbsp;
        &nbsp;(normalmente a nivel del ombligo)</b></td>
        <td align="center">
		<select name="pregunta3" Style="width:200px"
    		onKeypress="buscar_combo(this);"
			onblur="borrar_buffer(); sumatoria();"
			onchange="sumatoria();">
       		<? if ($sexo=='M') {?>
                <option value='-1'>Seleccione</option>
    			<option value='0'>Menos de 94 Cm</option>
                <option value='3'>Entre 94-102 Cm</option>
                <option value='4'>Mayor de 102 Cm</option>
		    <?} else {?>
                <option value='-1'>Seleccione</option>
                <option value='0'>Menos de 80 Cm</option>
                <option value='3'>Entre de 80-88 Cm</option>
                <option value='4'>Mayor de 88 Cm</option>
            <?}?>
            </select></td></tr>
                   
        <tr>
        <td style=" padding-left: 40px" width="72%"><b> 4) &iquest;Realiza habitualmente 
          al menos 30 minutos de actividad fisica, en el trabajo y/o en el tiempo libre?:</b></td>
        <td align="center">
    	<select name="pregunta4" Style="width:200px"
        		onKeypress="buscar_combo(this);"
    			onblur="borrar_buffer(); sumatoria();"
    			onchange="sumatoria();">
			 <option value='-1'>Seleccione</option>
 			 <option value='0' >SI</option>
             <option value='2' >NO</option>
			 </select></td></tr>
        </table></td></tr>

        <tr id="ma">
        <td align="left" colspan="4">
        <b>FRUTAS Y VERDURAS</b>
        </td>
        </tr>
        <tr align="center">
     	<td  colspan="4">
     	<table width="100%" border="1" cellspacing="0" bordercolor="#006699" style="border:thin groove;">
        <tr>
        <td style=" padding-left: 40px" width="72%"><b> 6) &quest;Con que frecuencia come verduras
        o frutas?:</b></td>
        <td align="center">
		<select name="pregunta5" Style="width:200px"
		onKeypress="buscar_combo(this);"
		onblur="borrar_buffer(); sumatoria();"
		onchange="sumatoria();">
		<option value='-1'>Seleccione</option>
		<option value='0'>Todos los d&iacute;as</option>
        <option value='1'>No todos los d&iacute;as</option>
		</select></td></tr>
        </table></td></tr>
        <tr id="ma">
        <td align="left" colspan="4">
        <b>MEDICACION</b>
        </td>
        </tr>
        <tr align="center">
        <td  colspan="4">
     	<table width="100%" border="1" cellspacing="0" bordercolor="#006699" style="border:thin groove;">
        <tr>
        <td style=" padding-left: 40px" width="72%"><b> 6) &iquest;Toma med&iacute;cacion para la
        hipertens&iacute;on regularmente?:</b></td>
        <td align="center">
		<select name="pregunta6" Style="width:200px"
		onKeypress="buscar_combo(this);"
		onblur="borrar_buffer(); sumatoria();"
		onchange="sumatoria();">
		<option value='-1'>Seleccione</option>
		<option value='0'>NO</option>
        <option value='2'>SI</option>
		</select></td></tr>
        </table></td></tr>
        
        <tr id="ma">
        <td align="left" colspan="4">
        <b>GLUCOSA</b>
        </td>
        </tr>
        <tr align="center">
     	<td  colspan="4">
     	<table width="100%" border="1" cellspacing="0" bordercolor="#006699" style="border:thin groove;">
        <tr>
        <td style=" padding-left: 40px" width="72%"><b> 7) &iquest;Le han encontrado alguna vez
        valores de glucosa altos (Ej. en un control m&eacute;dico, durante una &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;enfermedad o embarazo)?:</b></td>
        <td align="center">
		<select name="pregunta7" Style="width:200px"
		onKeypress="buscar_combo(this);"
		onblur="borrar_buffer(); sumatoria();"
		onchange="sumatoria();">
		<option value='-1'>Seleccione</option>
		<option value='0' >NO</option>
        <option value='5' >SI</option>
		</select></td></tr>
        </table></td></tr>

        <tr id="ma">
        <td align="left" colspan="4">
        <b>DIABETES</b>
        </td>
        </tr>
        <tr align="center">
        <td  colspan="4">
        <table width="100%" border="1" cellspacing="0" bordercolor="#006699" style="border:thin groove;">
        <tr>
        <td style=" padding-left: 40px" width="72%"><b> 8) &iquest;Se le ha diagnosticado daibetes (tipo 1 o tipo 2) a alguno de sus familiares allegados u otros &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;parientes?: <br>
        &nbsp;&nbsp;&nbsp;&nbsp;(marcar solo una opci&oacute;n)</b></td>
        <td align="center">
        <select name="pregunta8" Style="width:200px"
        onKeypress="buscar_combo(this);"
        onblur="borrar_buffer(); sumatoria();"
        onchange="sumatoria();">
        <option value='-1'>Seleccione</option>
        <option value='0' >NO</option>
        <option value='3' >SI: Abuelos, t&iacute;a, t&iacute;o, primo hermano</option>
        <option value='5' >SI: Padres, hermanos, hijos</option>
        </select></td></tr>
        </table></td></tr>
            
        <tr id="ma">
        <td  colspan="4" >
        <b style=" margin-left: 200px">SUMATORIA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>

        <input type="text" 
        onchange="cambia_color();"        
        value="<?=$puntaje_final?>" id="puntaje_final" name="puntaje_final" readonly size="20">
        &nbsp;<input type="text" name="msg_score" id="msg_score" size="40" readonly>
       </td>
        </tr>
        <tr>
        <td align="center" colspan="4" id="ma">
        <b> Centro Inscriptor </b>
        </td>
        </tr>

        <tr>
     	<td align="right" width="20%" colspan="2">
			<b>Lugar:</b>
		</td>
		<td align="left" width="30%" colspan="2">
		<select name=cuie Style="width:300px"
    		onKeypress="buscar_combo(this);"
			onblur="borrar_buffer();"
			onchange="borrar_buffer();">
		<option value=-1>Seleccione</option>
			 <?
			 $sql= "select * from nacer.efe_conv order by nombre";
			 $res_efectores=sql($sql) or fin_pagina();
			 while (!$res_efectores->EOF){
			 	$cuiec=$res_efectores->fields['cuie'];
			    $nombre_efector=$res_efectores->fields['nombre'];
			    ?>
				<option value='<?=$cuiec?>' <?if ($cuie==$cuiec) echo "selected"?> ><?=$cuiec." - ".$nombre_efector?></option>
			    <?
			    $res_efectores->movenext();
			    }?>
			
		</td>
		</tr>
        </table>
      </td>
     </tr>

    
	<tr id="mo">
  		<td align=center colspan="2">
  			<b>Guardar Planilla</b>
  		</td>
  	</tr>
  	
  	 <tr align="center">
	 	<td>
	 		<b><font size="0" color="Red">Nota: Verifique todos los datos antes de guardar</font> </b>
	 	</td>
	</tr>
	
    <tr align="center">
       <td>
        <input type='submit' name='guardar_editar' value='Guardar' onclick="return control_nuevos()"title="Guardar datos de la Planilla" <?=$desabil_guardar?>>
       </td>
    </tr>
    
    <?if($pagina_viene_1=="ins_listado_remediar.php"){?>
    	<tr align="center">
	       <td>
	       <input type=button name="volver" value="Volver" onclick="document.location='../inscripcion/ins_listado_remediar.php'"title="Volver al Listado" style="width=150px"> 
	       </td>
    	</tr>    
    <?} ?> 
 </table>
</form>

<script>
var campo_focus=document.all.campo_actual.value;
if(campo_focus==''){
    document.getElementById('campo_actual').value='num_form_remediar';
    campo_focus='num_form_remediar';
}else{
	if(campo_focus=='num_form_remediar'){
		campo_focus='fechaempadronamiento';
		document.getElementById('campo_actual').value='fechaempadronamiento';
	}else{
          campo_focus='os';
		  }
}
document.getElementById(campo_focus).focus();

if(form1.num_form_remediar.value=='' && form1.edad.value>0){
                form1.puntaje_final.value=form1.puntos_1.value;
              }
              
              function sumatoria(){
                  var edad=form1.edad.value; 
                  if(edad<=45){
                    p_preg1=0;
                  }
                  if(edad>45 && edad<=54){
                    p_preg1=2;
                  }
                  if(edad>=55 && edad <=64 ){
                    p_preg1=3;
                  }
                  if(edad>64){
                    p_preg1=4;
                  }
                                    
              var p_preg2=0;
              var p_preg3=0;
              var p_preg4=0;
              var p_preg5=0;
              var p_preg6=0;
              var p_preg7=0;
              var p_preg8=0;

              if (form1.pregunta2.value!='-1') 
                p_preg2=parseInt(form1.pregunta2.value);

            if (form1.pregunta3.value!='-1') 
                p_preg3=parseInt(form1.pregunta3.value);

            if (form1.pregunta4.value!='-1') 
                p_preg4=parseInt(form1.pregunta4.value);

            if (form1.pregunta5.value!='-1')
                p_preg5=parseInt(form1.pregunta5.value);

            if (form1.pregunta6.value!='-1') 
                p_preg6=parseInt(form1.pregunta6.value);

            if (form1.pregunta7.value!='-1') 
                p_preg7=parseInt(form1.pregunta7.value);

            if (form1.pregunta8.value!='-1') 
                p_preg8=parseInt(form1.pregunta8.value);
                
              /*var preg2=form1.pregunta2.value;
              if(preg2!='-1'){
                p_preg2=preg2.split('_');
                p_preg2=p_preg2[1]
              } ;*/
            form1.puntaje_final.value=p_preg1+p_preg2+p_preg3+p_preg4+p_preg5+p_preg6+p_preg7+p_preg8;

            var color='';
            if (form1.puntaje_final.value>=0 && form1.puntaje_final.value<=7) {
                document.getElementById('puntaje_final').style.backgroundColor='#45FC27';
                document.getElementById('msg_score').style.backgroundColor='#45FC27';
                document.getElementById('msg_score').value='Valor Bajo';
            }
            if (form1.puntaje_final.value>7 && form1.puntaje_final.value<=14) { 
                document.getElementById('puntaje_final').style.backgroundColor='#F9FC2F';
                if (form1.puntaje_final.value>7 && form1.puntaje_final.value<=11) var str='Ligeramente alto - ';
                    else var str='Moderado - ';
                document.getElementById('msg_score').style.backgroundColor='#F9FC2F';
                document.getElementById('msg_score').value=str+'Cambios en su estilo de Vida';
            }
            if (form1.puntaje_final.value>15 && form1.puntaje_final.value<=21) { 
                document.getElementById('puntaje_final').style.backgroundColor='#FE3B2C';
                document.getElementById('puntaje_final').style.color='white';
                if (form1.puntaje_final.value>15 && form1.puntaje_final.value<=20) var str='Alto - ';
                    else var str='Muy alto - ';
                document.getElementById('msg_score').style.backgroundColor='#FE3B2C';
                document.getElementById('msg_score').style.color='white';
                document.getElementById('msg_score').value=str+'Derivacion al medico';
                }
             }
</script>

<?if(($_POST['guardar_editar']=="Guardar")&&($pagina_viene_1=="ins_admin_old.php")){
    sleep(2);
    echo('<script>window.close();</script>');
    } ?> 
</td></tr></table></td></tr></table></form>
<?=fin_pagina();// aca termino ?>
