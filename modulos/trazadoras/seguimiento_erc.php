<?
require_once ("../../config.php");


extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

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

if ($_POST['guardar']=="Guardar"){		
	$db->StartTrans();
	$fecha_carga=date("Y-m-d H:m:s");
  $usuario=$_ses_user['name'];
	$fecha_comprobante=fecha_db($fecha_comprobante);
	$fecha_comprobante_proximo=fecha_db($fecha_comprobante_proximo);

  $derivacion=$_POST['derivacion'];
  $diagnostico_ingreso=$_POST['diagnostico_ingreso'];
  $a_patologicos=$_POST['a_patologicos'];
  $m_respiratoria=$_POST['m_respiratoria'];
  $fuma=$_POST['fuma']; 
  $fumo=$_POST['fumo'];  
  $anios_fumador=$_POST['anios_f'];  
  $tiempo_dejo_fumar=$_POST['tiempo_dejo'];  
  $cantidad_cig=$_POST['n_cigarrillos'];  
// $dias_x_anios_consumo=$_POST['dias_x_anios_consumo'];  
  $carga_tabaquica=$_POST['carga'];  
  $vacunas_completas=$_POST['vacunas'];  
  $falta_vacunas=$_POST['falta'];  
  $disnea_grado0=($_POST['g0']=='on')?'s':'n';  
  $disnea_grado1=($_POST['g1']=='on')?'s':'n';  
  $disnea_grado2=($_POST['g2']=='on')?'s':'n';  
  $disnea_grado3=($_POST['g3']=='on')?'s':'n';  
  $disnea_grado4=($_POST['g4']=='on')?'s':'n';  
  $espirometria=$_POST['solic_espirometria'];  
  $fecha_espirometria=($_POST['fecha_espirometria']=='')?'1800-01-01':fecha_db($_POST['fecha_espirometria']); 
  $informe_previo=$_POST['informe_previo'];  
  $resultado_esp=$_POST['resultado_espi'];  
  $solicitud_rehab=$_POST['rehabilitacion_respiratoria'];  
  $rehab_kinesiologia=$_POST['rehabilitacion_kinesiologia'];  
  $manejo_tratamiento=$_POST['manejor_tratamiento'];  
  $seguimiento=$_POST['seguimiento'];  
  $ta=$_POST['ta']; 
  $fc=$_POST['fc'];  
  $fr=$_POST['fr'];  
  $sat=$_POST['sat'];  
  $temperatura=$_POST['temp'];  
  $peso=$_POST['peso']; 
  $beba=$_POST['beba'];  
  $roncus=$_POST['roncus'];  
  $tos=$_POST['tos'];  
  $sibilansia=$_POST['sibilansia'];  
  $crepitantes_subcre=$_POST['crepitantes'];  
  $tiraje=$_POST['tiraje'];  
  $edemas=$_POST['edemas'];  
  $secreciones=$_POST['secreciones'];  
  $diag_presun=$_POST['diagnostico'];  
  $tratamiento=$_POST['tratamiento'];  
  $rx_toraz=$_POST['rx_torax'];  
  $tac_torax=$_POST['tac_torax'];  
  $otro_estudio=$_POST['otros'];  
  $reentren_respir=$_POST['reentrenamiento'];  
  $higiene_bronquial=$_POST['higiene_bronquial'];  
  $automanejo=$_POST['automanejo'];  
  $code=$_POST['cuestinario_code'];  
  $code_text=$_POST['cuestionario_code_text'];
  $reali_espiro=$_POST['realizacion_esp'];  
  $pico_flujo=$_POST['pico_flujo']; 
  $comentario=$_POST['comentario']; 

	
	$q="select nextval('trazadoras.seguimiento_erc_id_erc_seq') as id_planilla";
  $id_planilla=sql($q) or fin_pagina();
  $id_planilla=$id_planilla->fields['id_planilla'];
    
	$query = "insert into trazadoras.seguimiento_erc
				(id_erc,
        derivacion,
        diag_ingreso ,
        pat_respiratoria ,
        medicacion_respiratoria ,
        fuma,
        fumo,
        anios_fumador,
        tiempo_dejo_fumar,
        cantidad_cig,
        carga_tabaquica,
        vacunas_completas,
        falta_vacunas,
        disnea_grado0,
        disnea_grado1,
        disnea_grado2,
        disnea_grado3,
        disnea_grado4,
        espirometria,
        fecha_espirometria,
        informe_previo,
        resultado_esp,
        solicitud_rehab,
        rehab_kinesiologia,
        manejo_tratamiento,
        seguimiento,
        ta,
        fc,
        fr,
        sat,
        temperatura,
        peso,
        beba,
        roncus,
        tos,
        sibilansia,
        crepitantes_subcre,
        tiraje,
        edemas,
        secreciones,
        diag_presun,
        tratamiento,
        rx_toraz,
        tac_torax,
        otro_estudio,
        reentren_respir,
        higiene_bronquial,
        automanejo,
        code,
        reali_espiro,
        pico_flujo,
        comentario,
        usuario,
        fecha_carga,
        fecha_comprobante,
        clave_beneficiario,
        cuie)
        
        values 
				
        ($id_planilla,
        '$derivacion',
        '$diagnostico_ingreso',
        '$a_patologicos',
        '$m_respiratoria',
        '$fuma', 
        '$fumo',  
        $anios_fumador,  
        $tiempo_dejo_fumar,  
        $cantidad_cig,
        $carga_tabaquica,  
        '$vacunas_completas',  
        '$falta_vacunas',  
        '$disnea_grado0',  
        '$disnea_grado1',  
        '$disnea_grado2',  
        '$disnea_grado3',  
        '$disnea_grado4',  
        '$espirometria',  
        '$fecha_espirometria', 
        '$informe_previo',  
        '$resultado_esp',  
        '$solicitud_rehab',  
        '$rehab_kinesiologia',  
        '$manejo_tratamiento',  
        '$seguimiento',  
        '$ta', 
        '$fc',  
        '$fr',  
        '$sat',  
        '$temperatura',  
        $peso, 
        '$beba',  
        '$roncus',  
        '$tos',  
        '$sibilansia',  
        '$crepitantes_subcre',  
        '$tiraje',  
        '$edemas',  
        '$secreciones',  
        '$diag_presun',  
        '$tratamiento',  
        '$rx_toraz',  
        '$tac_torax',  
        '$otro_estudio',  
        '$reentren_respir',  
        '$higiene_bronquial',  
        '$automanejo',  
        '$code',  
        '$reali_espiro',  
        '$pico_flujo',
        '$comentario',
        '$usuario',
        '$fecha_carga',
        '$fecha_comprobante',
        '$clave_beneficiario',
        '$efector')";

  $res_extras = sql($query, "Error al insertar la Planilla") or fin_pagina();
  $accion = "Se guardo el Seguiemiento Nro. " . $id_planilla;
	$db->CompleteTrans();  
}

if ($marcar=="True"){
$query = "DELETE from trazadoras.seguimiento_erc where id_seguimiento_erc=$id_seguimiento_erc";
$res_extras = sql($query, "Error al insertar la Planilla") or fin_pagina();
$accion = "Se Elimino el Seguiemiento Nro. " . $id_seguimiento_erc;				
}

if ($clave_beneficiario){
	$sql="SELECT *
	     from uad.beneficiarios	 
		   where clave_beneficiario='$clave_beneficiario'";
	$res_comprobante=sql($sql, "Error al traer los Comprobantes") or fin_pagina();
  $id_beneficiarios=$res_comprobante->fields['id_beneficiarios'];

  /*$sql_smi="SELECT *
        from nacer.smiafiliados  
        where clavebeneficiario='$clave_beneficiario'";
  $res_smi=sql($sql_smi) or fin_pagina();
  $id_smiafiliados1=$res_smi->fields['id_smiafiliados'];*/
}


$clave_beneficiario=$res_comprobante->fields['clave_beneficiario'];
$apellido_benef=$res_comprobante->fields['apellido_benef'];
$nombre_benef=$res_comprobante->fields['nombre_benef'];
$numero_doc=$res_comprobante->fields['numero_doc'];
$fecha_nacimiento_benef=$res_comprobante->fields['fecha_nacimiento_benef'];
$sexo=$res_comprobante->fields['sexo'];
$calle=$res_comprobante->fields['calle'];
$id_beneficiarios=$res_comprobante->fields['id_beneficiarios'];

/*por si nesecito traer datos
$sql_dh="SELECT * from trazadoras.clasificacion_erc where clave_beneficiario='$clave_beneficiario'";
$res_dh=sql ($sql_dh) or fin_pagina();
*/


$fecha_hoy=date("Y-m-d");
 
  

echo $html_header;
?>
<script>
//controlan que ingresen todos los datos necesarios par el muleto

$(document).ready(function() {
	
  $('#efector_derivacion').hide();
	
  $('#derivacion').change(function(){
    str=$('select#derivacion option:checked').val();
    //alert(str);
    if (str=='-1') {
    $('#efector_derivacion').hide();
      
  };

    if (str=='si') {
      $('#efector_derivacion').show();
    };

    if (str=='no') {
      $('#efector_derivacion').hide();
  };

  });

  $('#fuma_no').hide();
  
  $('#fuma').change(function(){
    str=$('#fuma').val();
    if (str=='n') {
      $('#fuma_no').show();
      }
      else {$('#fuma_no').hide();
      }
  
  
  })  

  $('#titulo_valor_p_flujo').hide();
  $('#valor_pico_flujo').hide();
  
  $('#pico_flujo').change(function(){
    str=$('#pico_flujo').val();
    if (str=='s') {
      $('#titulo_valor_p_flujo').show();
      $('#valor_pico_flujo').show();
      }
      else {
        $('#titulo_valor_p_flujo').hide();
        $('#valor_pico_flujo').hide();
      }
  
  
  })  



  $('#resultado').hide();
  $('#resultado_espi').hide();
  $('#informe_previo').change(function(){
    str1=$('select#informe_previo option:checked').val();
    //alert(str);
    if (str1=='-1') {
    $('#resultado_espi').hide();
    $('#resultado').hide();
      
  };

    if (str1=='si') {
      $('#resultado').show();
      $('#resultado_espi').show();
    };

    if (str1=='no') {
      $('#resultado').hide();
      $('#resultado_espi').hide();
  };

  });

  $('#cvf').hide();
  $('#cvf_in').hide();
  $('#vef1').hide();
  $('#vef1_in').hide();
  $('#vefcvf').hide();
  $('#vefcvf_in').hide();
  $('#edemas').change(function(){
    str2=$('select#edemas option:checked').val();
    //alert(str);
    if (str2=='-1') {
      $('#cvf').hide();
      $('#cvf_in').hide();
      $('#vef1').hide();
      $('#vef1_in').hide();
      $('#vefcvf').hide();
      $('#vefcvf_in').hide();
      
  };

    if (str2=='Obstructiva'||str2=='Restrictiva'||str2=='Mixta') {
      $('#cvf').show();
      $('#cvf_in').show();
      $('#vef1').show();
      $('#vef1_in').show();
      $('#vefcvf').show();
      $('#vefcvf_in').show();
    };

    if (str2=='No Solicitada'||str2=='Solicitada'||str2=='Normal') {
      $('#cvf').hide();
      $('#cvf_in').hide();
      $('#vef1').hide();
      $('#vef1_in').hide();
      $('#vefcvf').hide();
      $('#vefcvf_in').hide();
  };

  });
   
 	
})

function cont() {
  var numero_cigarrillo=$('#n_cigarrillos').val();
  var anios_consumo=$('#anios_f').val();
//  console.log(numero_cigarrillo);
//  console.log(anios_consumo);
  if (numero_cigarrillo && anios_consumo) {
    carga_tabaquica=(numero_cigarrillo*anios_consumo)/20;
    console.log(carga_tabaquica);
    $('#carga').val(carga_tabaquica);
  }
}


function control_nuevos()
{
 
 if(document.all.efector.value=="-1"){
            alert("Debe elegir un efector");
            document.all.efector.focus();
             return false;
           }
 if(document.all.profesional.value==""){
  alert('Debe Ingresar un Profesional');
  return false;
 }

 if (confirm('Esta Seguro que Desea Guardar Seguimiento?'))return true;
 else return false;	
}//de function control_nuevos()

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
}

/**********************************************************/
//funciones para busqueda abreviada utilizando teclas en la lista que muestra los clientes.
var digitos=10; //cantidad de digitos buscados
var puntero=0;
var buffer=new Array(digitos); //declaración del array Buffer
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
   event.returnValue = false; //invalida la acción de pulsado de tecla para evitar busqueda del primer caracter
}//de function buscar_op_submit(obj)

</script>

<form name='form1' action='seguimiento_erc.php' method='POST' enctype='multipart/form-data'>
<input type="hidden" name="clave_beneficiario" value="<?=$clave_beneficiario?>">
<?	$fecha_carga=date("Y-m-d");
	$edad_completo = edad_con_meses($fecha_nacimiento_benef,$fecha_carga);?>

<input type="hidden" name="edad" value="<?=$edad_completo['anos'];?>">

<?echo "<center><b><font size='+1' color='blue'>$accion</font></b></center>";?>
<table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
     <font size=+1><b>Beneficiario <?=$accion1?></b></font>    
    </td>
 </tr>
  
<tr><td>
  <br>
  <br>
  <table width=80% align="center" class="bordes">
     <tr>
      <td id=mo colspan="2">
       <b> Descripcion del Beneficiario</b>
      </td>
     </tr>
     <tr>
       <td>
        <table>
         
         <tr>
         	<td align="right">
         	  <b>Apellido:
         	</td>         	
            <td align='left'>
              <input type='text' name='apellido_benef' value='<?=$apellido_benef;?>' size=50 align='right' readonly></b>
            </td>
         
            <td align="right">
         	  <b> Nombre:
         	</td>   
           <td  colspan="2">
             <input type='text' name='nombre_benef' value='<?=$nombre_benef;?>' size=50 align='right' readonly></b>
           </td>
          </tr>
          
          <tr>
           <td align="right">
         	  <b> Documento:
         	</td> 
           <td >
             <input type='text' name='numero_doc' value='<?=$numero_doc;?>' size=20 align='right' readonly></b>
           </td>
           <td align="right">
         	  <b> Fecha de Nacimiento:
         	</td> 
           <td>
             <input type='text' name='fecha_nacimiento_benef' value='<?=Fecha($fecha_nacimiento_benef);?>' size=20 align='right' readonly></b>
           </td>
          </tr>
          
          <tr>
           <td align="right">
         	  <b> Domicilio:
         	</td> 
           <td >
             <input type='text' name='calle' value='<?=$calle;?>' size=50 align='right' readonly></b>
           </td>        
          <td align="right">
         	  <b> Clave Beneficiario:
         	</td>   
           <td>
             <input type='text' name='clave_beneficiario' value='<?=$clave_beneficiario;?>' size=50 align='right' readonly></b>
           </td>
           <td>
             <input type='hidden' name='sexo' value='<?=$sexo;?>' size=50 align='right' readonly></b>
           </td>
         </tr>
         
        </table>
      </td>      
     </tr>
   </table>     
    <br>
    <br>

	 <table class="bordes" align="center" width="90%">
		 <tr align="center" id="sub_tabla">
		 	<td colspan="2">	
		 		Nuevo Seguimiento
		 	</td>
		 </tr>
		 <tr><td class="bordes"><table>
			 <tr>
				 <td>
					 <tr>
              &nbsp;
              &nbsp;
					    <td align="right">
					    	<b>Efector:</b>
					    </td>
					    <td align="left">		          			
				 			<select name=efector Style="width=450px"
				 			onKeypress="buscar_combo(this);"
				 			onblur="borrar_buffer();"
				 			onchange="borrar_buffer();">
							<option value=-1>Seleccione</option>
			                 <?$user_login=substr($_ses_user['login'],0,6);
								  if (es_cuie($_ses_user['login'])){
									$sql1= "select cuie, nombre, com_gestion from nacer.efe_conv where cuie='$user_login' order by nombre";
								   }									
								  else{
									$usuario1=$_ses_user['id'];
									$sql1= "select nacer.efe_conv.nombre, nacer.efe_conv.cuie, com_gestion 
											from nacer.efe_conv 
											join sistema.usu_efec on (nacer.efe_conv.cuie = sistema.usu_efec.cuie) 
											join sistema.usuarios on (sistema.usu_efec.id_usuario = sistema.usuarios.id_usuario) 
											where sistema.usuarios.id_usuario = '$usuario1'
										 order by nombre";
								   }			 			   
							 $sql1= "select cuie, nombre, com_gestion from nacer.efe_conv order by nombre";
							 $res_efectores=sql($sql1) or fin_pagina();
							 
							 while (!$res_efectores->EOF){ 
								$cuie=$res_efectores->fields['cuie'];
								$nombre_efector=$res_efectores->fields['nombre'];
								?>
								<option value='<?=$cuie;?>'><?=$cuie." - ".$nombre_efector?></option>
								<?
								$res_efectores->movenext();
								}?>
			      			</select>
					    </td>
					 </tr>					 
					 
					 <tr>
					 	<td align="right">
					    	<b>Fecha Control:</b>	
					    </td>	
					    <td align="left">		    						    	
					    	<?$fecha_comprobante=date("d/m/Y");?>
					    	 <input type=text id=fecha_comprobante name=fecha_comprobante value='<?=$fecha_comprobante;?>' size=15 readonly>
					    	 <?=link_calendario("fecha_comprobante");?>					    	 
					    </td>
					  </tr>
					  <tr>
					    <td align="right">
					    	<b>Fecha Proximo Control:</b>	
					    </td>	
					    <td align="left">				    						    	
					    	<?$fecha_comprobante_proximo=date("d/m/Y");?>
					    	 <input type=text id=fecha_comprobante_proximo name=fecha_comprobante_proximo value='<?=$fecha_comprobante_proximo;?>' size=15 readonly>
					    	 <?=link_calendario("fecha_comprobante_proximo");?>					    	 
					    </td>			    
					 </tr>

					 <tr>
					    <td align="right">
					    	<b>Profesional:</b>	
					    </td>	
					    <td align="left">				    						    	
					    	<input type="text" value="<?=$profesional;?>" name="profesional" size="35" title="Nombre Profesional"/>				    	 
					    </td>			    
					 </tr>
		
		  
		<tr>
    <td colspan="5" align="center">                       
        <table width=100% align="center" class="bordes" style="margin-top:5px">
           <td align="center" id='mo' colspan="4"><b>Datos Epediomiologicos</b></td>
	   <tr>
      <td align="left" style="padding:10px">
        <div style="display:outline;
        border: 2px solid #5D5DF9;
        padding: 5px;
        border-radius: 25px;
        border-width: thin;
        background-color: #F7F7CF">

          
          <b title="derivac">Derivacion:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <select id='derivacion'>
            <option value='-1'>Seleccione</option>
            <option value="si">SI</option>
            <option value="no">NO</option>
          </select>
          &nbsp;&nbsp;&nbsp;
          <!--<input type="text" value="" name="derivacion" size="50" style="font-size:15px;" maxlength="35" title="Derivacion"/>-->
          <select id='efector_derivacion' name='efector_derivacion' Style="width=450px"
              onKeypress="buscar_combo(this);"
              onblur="borrar_buffer();"
              onchange="borrar_buffer();">
              <option value=-1>Seleccione</option>
              <option value='Privado' style='color:red'>PRIVADO</option>
                <?$sql1= "SELECT cuie, nombre, com_gestion from nacer.efe_conv order by nombre";
                $res_efectores=sql($sql1) or fin_pagina();
               
               while (!$res_efectores->EOF){ 
                $cuie=$res_efectores->fields['cuie'];
                $nombre_efector=$res_efectores->fields['nombre'];
                ?>
                <option value='<?=$cuie;?>'><?=$cuie." - ".$nombre_efector?></option>
                <?
                $res_efectores->movenext();
                }?>
                </select>
                <br>
          <br>
          <b title="diag_ingreso">Diagnostico de Ingreso:</b>
          &nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;
                   
          <select name="diagnostico_ingreso">
            <option value='-1'>Seleccione</option>
            <option value='ASMATICO'>ASMA</option>
            <option value='BRONCOESPASMO'>BRONCOESPASMO</option>
            <option value='DISNEA EN ESTUDIO'>DISNEA EN ESTUDIO</option>
            <option value='EPOC'>EPOC</option>
            <option value='ENFISEMA'>ENFISEMA</option>
            <option value='IRA'>IRA</option>
            <option value='OTROS'>OTROS</option>
          </select><br>
          <!--<input type="text" value="" name="diagnostico_ingreso" size="41" style="font-size:15px;" maxlength="35" title="Diagnostico de Ingreso" /><br>-->
          <br>
          &nbsp;
          <b title="a_patologico">A.Patologicos Respiratorios y otros:</b>
          <input type="text" value="" name="a_patologicos" size="32.5" style="font-size:15px;" maxlength="35" title="A.Patologicos"/><br>
          <br>
          &nbsp;
        </div>
      </td>
    </tr>

          <tr>
          <td align="left" style="padding:10px">
          <div style="display:outline;
          border: 2px solid #5D5DF9;
          padding: 5px;
          border-radius: 25px;
          border-width: thin;">
          <br>
          <h5 align="center" style="background-color: #D3E4FF"><b>Medicacion Prescripta</b></h5>
          
          <b title="Broncodilatadores de acción corta B2: salbutamol">Broncodilatadores de acción corta B2: salbutamol</b>&nbsp;&nbsp;&nbsp;
          <select name="md1">
            <option value='-1'>Seleccione</option>
            <option value='SI'>SI</option>
            <option value='NO'>NO</option>            
          </select><br>

          <br>

          <b title="Broncodilatadores de acción corta M: ipratropio">Broncodilatadores de acción corta M: ipratropio</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <select name="md2">
            <option value='-1'>Seleccione</option>
            <option value='SI'>SI</option>
            <option value='NO'>NO</option>            
          </select><br>

          <br>
          <b title="BrB2 + Br BM:">BrB2 + Br BM:</b>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <select name="md3">
            <option value='-1'>Seleccione</option>
            <option value='SI'>SI</option>
            <option value='NO'>NO</option>            
          </select>
          <br>

          <br>
          <b title="Corticoides oral:">Corticoides:</b>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <select name="tr4">
            <option value='-1'>Seleccione</option>
            <optgroup label='Tratamientos  Orales'>
              <option value='Metilprednisona'>Metilprednisona</option>
              <option value='Betametasona'>Betametasona</option>
              <option value='Betilxantinas'>Betilxantinas</option>
              <option value='Inhibidores'>Inhibidores de la Fosfodiesterasa 4 (roflumilast)</option>
              <option value='Mucolíticos'>Mucolíticos (N-acetil cisteína)</option>
            </optgroup>
            <optgroup label='Corticoides inhalatorios'>
              <option value='Budesonide'>Budesonide</option>
              <option value='Fluticasona'>Fluticasona</option>
            </optgroup>
            <optgroup label='Acción larga (LABA)'>
              <option value='Salmeterol'>Salmeterol</option>
              <option value='Formoterol'>Formoterol</option>
            </optgroup>
            <optgroup label='Accion ultralarga (ultra-LABA)'>
              <option value='Indacaterol'>Indacaterol</option> 
              <option value='Vilanterol'>Vilanterol</option>
              <option value='Olodaterol'>Olodaterol</option> 
            </optgroup>
            <optgroup label='Asociaciones'>
              <option value='Salmeterol'>Salmeterol</option>
              <option value='Fluticasona'>Fluticasona</option>
              <option value='Formoterol'>Formoterol</option>
              <option value='Budesonida'>Budesonida</option>              
            </optgroup>            
          </select><br>
          <!--<input type="text" name="tratamiento" size="124px"/>-->
          <br>

          <!--<input type="text" value="" name="m_respiratoria" size="40.5" style="font-size:15px;" maxlength="35" title="Medicacion Respiratoria"/><br>-->
          <br>
          
        &nbsp;
          </div>
        </td>
      </tr>
      </tr>
      &nbsp;
    <tr>
			 <td align="left" style="padding:10px">
				<div style="display:outline;
				border: 2px solid #5D5DF9;
				padding: 5px;
				border-radius: 25px;
				border-width: thin;">
        <br>
        <h5 align="center" style="background-color: #D3E4FF"><b>Tabaquismo</b></h5>
				<b title="fuma">Fuma:</b>
				<select name="fuma" id="fuma" title="fuma">
          <option value='-1'>Seleccione</option>
          <option value='s'>SI</option>
          <option value='n'>NO</option>
				</select>
        
        <br><br>   
        <div id="fuma_no">    
				<b title="Fumo">Fumo:</b>
				<select name="fumo" title="Fumo">
				  <option value='-1'>Seleccione</option>
          <option value='s'>SI</option>
          <option value='n'>NO</option>               
        </select> 
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;
			
        <b title="anios_prochaska">Años</b>
        <select name="prochaska_in" title="prochaska_in">
          <option value='-1'>Seleccione</option>
          <option value='menor_5'>< 5 Años</option>
          <option value='5-10'>Entre 5 - 10 años</option>
          <option value='10-20'>Entre 10 - 20 años</option>
          <option value='20-30'>Entre 20 - 30 años</option>  
          <option value='mayor_30'>> 30 Años</option>             
        </select>&nbsp;&nbsp;

        <b title="tiempo_dejo_prochaska">Tiempo que dejo</b>
        <select name="tiempo_dejo_prochaska" title="tiempo_dejo">
          <option value='-1'>Seleccione</option>
          <option value='menor_5'>< 5 Años</option>
          <option value='5-10'>Entre 5 - 10 años</option>
          <option value='10-20'>Entre 10 - 20 años</option>
          <option value='20-30'>Entre 20 - 30 años</option>  
          <option value='mayor_30'>> 30 Años</option>             
        </select>&nbsp;&nbsp;
        <b title="prochaska">Etapa Cambios Prochaska</b>
        <select name="prochaska_in" title="prochaska_in">
          <option value='-1'>Seleccione</option>
          <option value='pre'>PRECONTEMPLATIVA</option>
          <option value='con'>CONTEMPLATIVA</option>
          <option value='acc'>ACCION</option>
          <option value='man'>MANTENIMIEMTO</option>  
          <option value='rec'>RECAIDA</option>             
        </select>&nbsp;&nbsp;
        </div>
        <br>
        <br>												
				</div>
				</td>
				</tr>
				
        <tr>
				<td align="left" style="padding:10px">
        <div style="display:outline;
				border: 2px solid #5D5DF9;
				padding: 5px;
				border-radius: 25px;
				border-width: thin;">
				<br>
        <b title="numero_cigarrillo">Nº de Cigarrillo X dia:</b>
        <input type="numeric" id="n_cigarrillos" name="n_cigarrillos" onblur="cont()"/>&nbsp;
				<!--<b title="anios_consumo">Años de Consumos:</b>
        <input type="numeric" id="anios_consumo" name="dias_x_anios_consumo" onblur="cont()"/>-->
        &nbsp;
				&nbsp;
        &nbsp;
        &nbsp;
        &nbsp;
        <input type="numeric" id="carga" name="carga" size="9" style="font-size:15px;" readonly/>&nbsp;
        <b><16: Leve  -  </b>&nbsp;&nbsp;	
        <b>16 a 25: Moderado  -  </b>&nbsp;&nbsp;
        <b>>25: Severo</b>&nbsp;	
        <br>
        <br>																		
				</div>
			</td>
			</tr>
							
			<td align="left" style="padding:10px">
				<div style="display:outline;
				border: 2px solid #5D5DF9;
				padding: 5px;
				border-radius: 25px;
				border-width: thin;">
				<br>
        <h5 align="center" style="background-color: #D3E4FF"><b>Inmunizaciones</b></h5>	
				&nbsp;
        &nbsp;
				<input type='checkbox' name='gripe_a'>&nbsp;&nbsp;<b title="gripe_a">Gripe A</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type='checkbox' name='neumo_13'>&nbsp;&nbsp;<b title="neumo_13">Neumo 13</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type='checkbox' name='neumo_23'>&nbsp;&nbsp;<b title="neumo_23">Neumo 23</b>
				&nbsp;
        &nbsp;
        &nbsp;
        &nbsp;
				<!--<b title="falta">Cual Falta</b>
				<input type="text" name="falta" size="35" style="font-size:15px;" maxlength="35" title="Que vacuna/s falta/n?"/>
				<br>
        <br>-->
									
				</div>
				</td>
				</tr>
				<tr>
				<td align="left" style="padding:10px">
					<div style="display:outline;
					border: 2px solid #5D5DF9;
					padding: 5px;
					border-radius: 25px;
					border-width: thin;">
          <br>
          <h5 align="center" style="background-color: #D3E4FF"><b>Escala de DISNEA MMRC</b></h5>

          &nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="radio" name="radios_escala" id="radios_escala" value="g0" checked>
          &nbsp;&nbsp;&nbsp;
          <b title="Grado 0">Grado 0: Solo falta de aire en ejercicios intensos.</b>
                 
          <br><br>
          &nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="radio" name="radios_escala" id="radios_escala" value="g1">
          &nbsp;&nbsp;&nbsp;
          <b title="Grado 1">Grado 1: Falta aire al caminar rapido.</b>
                  
          <br><br>  
          &nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="radio" name="radios_escala" id="radios_escala" value="g2"> 
          &nbsp;&nbsp;&nbsp; 
          <b title="Grado 2">Grado 2: Camino mas despacio que otras personas de mi edad y tengo que detenerme a recuperar aliento cuando camino en llano a mi propio paso.</b>
                   
          <br><br>  
          &nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="radio" name="radios_escala" id="radios_escala" value="g3">
          &nbsp;&nbsp;&nbsp; 
          <b title="Grado 3">Grado 3: Tengo que detenerme a recuperar aliento despues de caminar 100 m o despues de unos minutos de caminar llano.</b>
                    
          <br><br> 
          &nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="radio" name="radios_escala" id="radios_escala" value="g4">  
          &nbsp;&nbsp;&nbsp;  
          <b title="Grado 4">Grado 4: Me falta demasiado el aire como para salir de mi casa o para vestirme.</b>
                 
          <br><br>
					  </div>
					</div>
					</td>
					</tr>

          <tr>
          <td align="left" style="padding:10px">	
          <div style="display:outline;
          border: 2px solid #5D5DF9;
          padding: 5px;
          border-radius: 25px;
          border-width: thin;">
            &nbsp;
            <b title="solic_espirometria">Alguna vez le solicitaron una Espirometria?: </b>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;
            <select name="solic_espirometria" title="solic_espirometria">
              <option value='-1'>Seleccione</option>
              <option value='s'>SI</option>
              <option value='n'>NO</option>               
            </select>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            
            <b title="resultado" id="resultado">Resultado: </b>
            <!--<input type="text" name="resultado_espi" size="100px"/>-->
            <select name="resultado_espi" id="resultado_espi" title="resultado_espi">
              <option value='-1'>Seleccione</option>
              <option value='obs'>OBSTRUCTIVO</option>
              <option value='res'>RESTRICTIVO</option> 
              <option value='mix'>MIXTO</option>              
            </select><br><br>
            &nbsp;
            <b title="Rehabilitacion Respiratoria">Alguna vez le solicitaron rehabilitacion respiratoria?</b>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <select name="rehabilitacion_respiratoria" title="rehabilitacion_respiratoria">
              <option value='-1'>Seleccione</option>
              <option value='s'>SI</option>
              <option value='n'>NO</option>               
            </select><br><br>
            &nbsp;

            <b title="Rehabilitacion Serv. Kinesiologia">Realizo rehabilitacion con el ser. de kinesiologia?</b>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;
            <select name="rehabilitacion_kinesiologia" title="rehabilitacion_kinesiologia">
              <option value='-1'>Seleccione</option>
              <option value='s'>SI</option>
              <option value='n'>NO</option>               
            </select><br><br>
            &nbsp;

            <b title="manejo de tratamiento">Alguna vez evaluaron el manejo de su tratamiento (autom)?</b>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <select name="manejor_tratamiento" title="manejor_tratamiento">
              <option value='-1'>Seleccione</option>
              <option value='s'>SI</option>
              <option value='n'>NO</option>               
            </select><br><br>
            &nbsp;

            <b title="seguimiento">Tiene seguimiento de su enfermedad respiratoria?</b>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <select name="seguimiento" title="seguimiento">
              <option value='-1'>Seleccione</option>
              <option value='s'>SI</option>
              <option value='n'>NO</option>               
            </select><br><br>
            &nbsp; 
          </div>
					</td>
					</tr>

						

						<tr>
						<td align="left" style="padding:10px">	
							<div style="display:outline;
							border: 2px solid #5D5DF9;
							padding: 5px;
							border-radius: 25px;
							border-width: thin;
              background-color: #F7F7CF">
							&nbsp;
							<b title="ta">TA </b>&nbsp;&nbsp;
							<!--<input type="text" name="ta" size="10px" /> &nbsp;-->
              <font color="Red">MAXIMA: </b></font>
              <input type='text' name='tension_arterial_M' value='<?=$tension_arterial_M;?>' size=5 align='right'></b>
              <font color="Red">MINIMA: </b></font>
              <input type='text' name='tension_arterial_m' value='<?=$tension_arterial_m;?>' size=5 align='right'></b>&nbsp;&nbsp;
							<b title="fc">FC: </b>&nbsp;&nbsp;
              <input type="text" name="fc" size="10px"/> &nbsp;
              <b title="fr">FR: </b>&nbsp;&nbsp;
              <input type="text" name="fr" size="10px"/> &nbsp;
              <b title="sat">SAT: </b>&nbsp;&nbsp;
              <input type="text" name="sat" size="10px"/> % &nbsp;
              <b title="temp">Temperatura: </b> &nbsp;&nbsp;
              <input type="text" name="temp" size="10px"/> Cº &nbsp;
              <b title="peso">Peso: </b>&nbsp;&nbsp;
              <input type="text" name="peso" size="10px"/> &nbsp;
							<br><br>
              <b title="beba">BEBA</b>
              &nbsp;
              &nbsp;
              <select name="beba" title="beba">
                <option value='-1'>Seleccione</option>
                <option value='s'>SI</option>
                <option value='n'>NO</option>               
              </select>&nbsp;
              <b title="roncus">RONCUS</b>
              &nbsp;
              &nbsp;
              <select name="roncus" title="roncus">
                <option value='-1'>Seleccione</option>
                <option value='s'>SI</option>
                <option value='n'>NO</option>               
              </select>&nbsp;
              <b title="tos">TOS</b>
              &nbsp;
              &nbsp;
              <select name="tos" title="tos">
                <option value='-1'>Seleccione</option>
                <option value='s'>SI</option>
                <option value='n'>NO</option>               
              </select>&nbsp;
              <b title="sibilansia">SIBILANCIA</b>
              &nbsp;
              &nbsp;
              <select name="sibilansia" title="sibilansia">
                <option value='-1'>Seleccione</option>
                <option value='s'>SI</option>
                <option value='n'>NO</option>               
              </select>&nbsp;
              <b title="crepitantes">CREPITANTES/SUBCREPITANTES</b>
              &nbsp;
              &nbsp;
              <select name="crepitantes" title="crepitantes">
                <option value='-1'>Seleccione</option>
                <option value='s'>SI</option>
                <option value='n'>NO</option>               
              </select>&nbsp;
              <br><br>
              <b title="tiraje">TIRAJE</b>
              &nbsp;
              &nbsp;
              <select name="tiraje" title="tiraje">
                <option value='-1'>Seleccione</option>
                <option value='s'>SI</option>
                <option value='n'>NO</option>               
              </select>&nbsp;
              <b title="edemas">EDEMAS M.INF Y OTROS</b>
              &nbsp;
              &nbsp;
              <select name="edemas" title="edemas">
                <option value='-1'>Seleccione</option>
                <option value='s'>SI</option>
                <option value='n'>NO</option>               
              </select>&nbsp;
              <br><br>
              <b title="secreciones">SECRECIONES:</b>
              <select name='secreciones'>
                <option value='-1'>Seleccione</option>
                <option value='SI'>SI</option>
                <option value='NO'>NO</option>
              </select>
              &nbsp;
              &nbsp;
              <!--<input type="text" name="secreciones" size="124px"/>-->
              <br><br>
              <b title="diagnostico">DIAGNOSTICO PRESUNTIVO:</b>
              &nbsp;
              <select name="diagnostico">
                <option value='-1'>Seleccione</option>
                <optgroup label='ASMA'>
                  <option value='asma_intermitente'>INTERMITENTE</option>
                  <option value='asma_per_leve'>PERSISTENTE LEVE</option>
                  <option value='asma_per_moderado'>PERSISTENTE MODERADO</option>
                  <option value='asma_per_gave'>PERSISTENTE GRAVE</option>
                  <option value='asma_no'>Asma NO</option>
                </optgroup>
                <optgroup label='EPOC'>
                  <option value='epoc_obst_leve'>OBSTRUCTIVO LEVE</option>
                  <option value='epoc_obst_moderado'>OBSTRUCTIVO MODERADO</option>
                  <option value='epoc_obst_severo'>OBSTRUCTIVO SEVERO</option>
                  <option value='epoc_restrictivo'>RESTRICTIVO</option>
                  <option value='epoc_mixto'>MIXTO</option>
                  <option value='epoc_no'>Epoc NO</option>                  
                </optgroup>
                <optgroup label='IRA'>
                  <option value='ira_nac'>NAC</option>
                  <option value='ira_bronquitis'>BRONQUITIS</option>
                </optgroup>
                <optgroup label='OTROS'>
                  <option value='otros_broncoespasmo'>BRONCOESPASMO</option>           
                  <option value='otros_disnea'>DISNEA EN ESTUDIO</option>
                  <option value='asma_epoc'>ASMA-EPOC</option>
                </optgroup>
              </select>
              &nbsp;
              <!--<input type="text" name="diagnostico" size="113px"/>-->
              <br><br>
              </div>
            </td>
          </tr>
              
            <tr>
            <td align="left" style="padding:10px">
            <div style="display:outline;
            border: 2px solid #5D5DF9;
            padding: 5px;
            border-radius: 25px;
            border-width: thin;">
            <br>
            <h5 align="center" style="background-color: #D3E4FF"><b>Tratamiento</b></h5>
              <br>           
              <b title="Broncodilatadores de acción corta B2: Salbutamol">Broncodilatadores de acción corta B2: Salbutamol</b>&nbsp;&nbsp;
              <select name="tr1">
                <option value='-1'>Seleccione</option>
                <option value='SI'>SI</option>
                <option value='NO'>NO</option>            
              </select><br>
              <br>

              <b title="Broncodilatadores de acción corta M: Ipratropio">Broncodilatadores de acción corta M: Ipratropio</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <select name="tr2">
                <option value='-1'>Seleccione</option>
                <option value='SI'>SI</option>
                <option value='NO'>NO</option>            
              </select><br>
              <br>

              <b title="BrB2 + Br BM:">BrB2 + Br BM:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              
              <select name="tr3">
                <option velue='-1'>Seleccione</option>
                <option value='SI'>SI</option>
                <option value='NO'>NO</option>            
              </select><br>
              
              <br>
              <b title="tratamientos_orales">Tratamientos  Orales:</b>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              
              <select name="tratamientos_orales">
              <option value='-1'>Seleccione</option>
              <option value='NO'>NO</option>
              <option value='Metilprednisona'>Metilprednisona</option>
              <option value='Betametasona'>Betametasona</option>
              <option value='Betilxantinas'>Betilxantinas</option>
              <option value='Inhibidores'>Inhibidores de la Fosfodiesterasa 4 (roflumilast)</option>
              <option value='Mucolíticos'>Mucolíticos (N-acetil cisteína)</option>
              </select>
              <br>
              
              <br>
              <b title="Corticoides_inhalatorios">Corticoides Inhalatorios:</b>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;          
              <select name="corticoides_inhalatorios">
              <option value='-1'>Seleccione</option>
              <option value='NO'>NO</option>
              <option value='Budesonide'>Budesonide</option>
              <option value='Fluticasona'>Fluticasona</option>
              </select>
              <br>

              <br>
              <b title="accion_larga">Acción larga (LABA):</b>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;
              <select name="accion_larga">
              <option value='-1'>Seleccione</option>
              <option value='NO'>NO</option>  
              <option value='Salmeterol'>Salmeterol</option>
              <option value='Formoterol'>Formoterol</option>
              </select>  
              <br>
                
              <br>
              <b title="accion_ultralarga">Accion ultralarga (ultra-LABA):</b>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;
              <select name="accion_ultralarga">
              <option value='-1'>Seleccione</option>
              <option value='NO'>NO</option>   
              <option value='Indacaterol'>Indacaterol</option> 
              <option value='Vilanterol'>Vilanterol</option>
              <option value='Olodaterol'>Olodaterol</option> 
              </select>
              <br>

              <br>
              <b title="asociaciones">Asociaciones:</b>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <select name="asociaciones">
              <option value='-1'>Seleccione</option>
              <option value='NO'>NO</option>
              <option value='Salmeterol'>Salmeterol</option>
              <option value='Fluticasona'>Fluticasona</option>
              <option value='Formoterol'>Formoterol</option>
              <option value='Budesonida'>Budesonida</option>              
              </select>
              <br>

              <br>
              <b title="ocd">Oxigenoterapia Cronica Domiciliaria:</b>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;
              <select name="ocd">
              <option value='-1'>Seleccione</option>
              <option value='NO'>NO</option>
              <option value='SI'>SI</option>
              </select>
              <br>
              <br>
              </div> 
						</td>
						</tr>

            <tr>
            <td align="left" style="padding:10px">
            <div style="display:outline;
            border: 2px solid #5D5DF9;
            padding: 5px;
            border-radius: 25px;
            border-width: thin;">
            <br>
            <h5 align="center" style="background-color: #D3E4FF"><b>Estudios Complementarios</b></h5>
              <br> 
              
              <b title="espirometria">ESPIROMETRIA: </b>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;
              <select name="edemas" title="edemas" id="edemas">
                <option value='-1'>Seleccione</option>
                <option value='No Solicitada'>No Solicitada</option>
                <option value='Solicitada'>Solicitada</option>
                <option value='Normal'>Realizada: Normal</option>
                <optgroup label="Realizada Patológica">
                  <option value='Obstructiva'>Obstructiva</option>
                  <option value='Restrictiva'>Restrictiva</option>
                  <option value='Mixta'>Mixta</option>
                </optgroup>
              </select> 
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <b title="cvf" id="cvf">CVF % : </b>
              <input type="text" name="cvf" id="cvf_in" size="10px"/> &nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <b title="vef1" id="vef1">VEF1 %: </b>
              <input type="text" name="vef1" id="vef1_in" size="10px"/> &nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <b title="vefcvf" id="vefcvf">VEF1/CVF  %: </b>
              <input type="text" name="vefcvf" id="vefcvf_in" size="10px"/> &nbsp;
              <br>
              <br>
              <b title="rx_torax">RX TORAX: </b>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;
              <select name="rx_torax" title="rx_torax">
                <option value='-1'>Seleccione</option>
                <option value='No Solicitada'>No Solicitada</option>
                <option value='Solicitada'>Solicitada</option>
                <option value='Normal'>Realizada: Normal</option>
                <option value='Patologico'>Patol&oacute;gica</option>
              </select>
             <br>
             <br>

              <b title="tac_torax">TAC DE TORAX: </b>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;
              <select name="tac_torax" title="tac_torax">
                <option value='-1'>Seleccione</option>
                <option value='No Solicitada'>No Solicitada</option>
                <option value='Solicitada'>Solicitada</option>
                <option value='Normal'>Realizada: Normal</option>
                <option value='Patologico'>Patol&oacute;gica</option>
              </select>
              <br><br>

              <b title="derivaciones_interc">DERIVACIONES O INTERCONSULTAS: </b>
              &nbsp;&nbsp;
              
              <select name="derivaciones" title="derivaciones interconsutas">
                <option value='-1'>Seleccione</option>
                <option value='neumologia'>NEUMOLOGIA</option>
                <option value='cardiologia'>CARDIOLOGIA</option>
                <option value='psicologia'>PSICOLOGIA</option>
                <option value='cirugia_toraxica'>CIRUGIA TORAXICA</option>
                <option value='neumologia'>NEUMOLOGIA</option>
              </select>
              <br><br>

              <b title="laboratorio">LABORATORIO: </b>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;
              <select name="laboratorio" title="laboratorio">
                <option value='-1'>Seleccione</option>
                <option value='solicitado'>SOLICITADO</option>
                <option value='no solicitado'>NO SOLICITADO</option>
              </select>
              <br><br>

              <b title="otros">OTROS:</b>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;
              <input type="text" name="otros" size="110px"/> 
              <br><br>

             </div> 
						</td>
						</tr>
          
          <tr>
            <td align="left" style="padding:10px">  
              <div style="display:outline;
              border: 2px solid #5D5DF9;
              padding: 5px;
              border-radius: 25px;
              border-width: thin;">
              <h5 align="center" style="background-color: #D3E4FF"><b>Kinesiologia</b></h5>
              <br> 
              &nbsp;
              <b title="reentrenamiento">Reentrenamiento Respiratorio</b>
              <select name="reentrenamiento" title="reentrenamiento">
                <option value='-1'>Seleccione</option>
                <option value='s'>SI</option>
                <option value='n'>NO</option>               
              </select>&nbsp;
              <b title="higiene_bronquial">Higiene Bronquial</b>
              <select name="higiene_bronquial" title="higiene_bronquial">
                <option value='-1'>Seleccione</option>
                <option value='s'>SI</option>
                <option value='n'>NO</option>               
              </select>&nbsp;
              <b title="automanejo">AUTOMANEJO</b>
              <select name="automanejo" title="automanejo">
                <option value='-1'>Seleccione</option>
                <option value='bueno'>BUENO</option>
                <option value='regular'>REGULAR</option> 
                <option value='malo'>MALO</option>              
              </select>&nbsp;
              <b title="cuestinario_code">CUESTIONARIO CODE</b>
              <select name="cuestinario_code" title="cuestinario_code">
                <option value='-1'>Seleccione</option>
                <option value='positivo'>POSITIVO</option>
                <option value='negativo'>NEGATIVO</option>               
              </select>&nbsp;
              <input type="text" name="cuestionario_code_text" size="20px"/>
              <br><br>
              &nbsp;
              
              <b title="pico_flujo">Pico Flujo</b>
              <select name="pico_flujo" id="pico_flujo" title="pico_flujo">
                <option value='-1'>Seleccione</option>
                <option value='s'>SI</option>
                <option value='n'>NO</option>               
              </select>&nbsp;&nbsp;
              
              <b title="valor_p_flujo" id="titulo_valor_p_flujo">Valor Pico Flujo</b>
              <input type="text"  id="valor_pico_flujo" name="valor_pico_flujo" placeholder="Valor en ml" size="20px"/>
              

            </div> 
            </td>
            </tr>


          </td>
			 </table>
			</td>
		</tr>
	  
		 <tr>
         	 <td align="right">
         	  	<b>Comentario:</b>
         	 </td>         	
	         <td align='left'>
	            <textarea cols='70' rows='3' name='comentario'></textarea>
              <br><br>
	         </td>
         </tr>   					 
		</td>
	</tr>
</table>
</td></tr>	  

		  
			 <tr>
			  	<td align="center" colspan="2" class="bordes">		      
			    	<input type="submit" class="btn btn-success" name="guardar" value="Guardar" title="Guardar" Style="width=230" onclick="return control_nuevos()" disabled><br><br>
			    </td>
			 </tr> 
			 
	</table>
 </td></tr>
</table>
<?

//tabla de comprobantes
$query="SELECT * FROM trazadoras.seguimiento_erc
        INNER JOIN nacer.efe_conv using (cuie)
        WHERE clave_beneficiario='$clave_beneficiario' ORDER BY id_erc DESC";

$res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();
?>
<tr><td><table width="100%" class="bordes" align="center">
	<tr align="center" id="mo">
	  <td align="center" width="3%">
	   <img id="imagen_2" src="<?=$img_ext?>" border=0 title="Mostrar Seguimientos" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida,2);" >
	  </td>
	  <td align="center">
	   <b>Seguimientos</b>
	  </td>
	</tr>
</table></td></tr>
<tr><td><table id="prueba_vida" border="1"  style="display:none;border:thin groove">
	<?if ($res_comprobante->RecordCount()==0){?>
	 <tr>
	  <td align="center">
	   <font size="3" color="Red"><b>No existen Seguimientos para este beneficiario</b></font>
	  </td>
	 </tr>
	 <?}
	 else{	 	
	 	?>
	 	<tr id="sub_tabla">	
	 		<td width="10%">Efec</td>
	 		<td width="10%">Fecha</td>
	 		<!--<td>Edita Fecha</td>-->
      <td width="5%">Borrar</td> 
	 	</tr>
	 	<?
	 	$res_comprobante->movefirst();
	 		while (!$res_comprobante->EOF){
	 		$ref1 = encode_link("seguimiento_admin.php",array("id_erc"=>$res_comprobante->fields['id_erc'],"clave_beneficiario"=>$clave_beneficiario,"marcar"=>"True"));
            $onclick_marcar="if (confirm('Esta Seguro que Desea Eliminar?')) location.href='$ref1'
            				else return false; ";
							
			$ref_editar = encode_link("modifica_seguimiento.php",array("id_erc"=>$res_comprobante->fields['id_erc']));
			
      $onclick_editar="if (confirm('Esta Seguro que Desea Editar la Fecha del Seguimiento?')) location.href='$ref_editar' else return false;	";

      $ref_ver = encode_link("seguimiento_muestra.php",array("id_erc"=>$res_comprobante->fields['id_erc']));

      $onclick_ver="location.href='$ref_ver'";
            ?>

	 		<tr <?=atrib_tr()?>>	
		 		<td onclick="<?=$onclick_ver?>"><?=$res_comprobante->fields['nombre']?></td>		 		
		 		<td onclick="<?=$onclick_ver?>"><?=fecha($res_comprobante->fields['fecha_comprobante'])?></td><!--<td align="center"><img src='../../imagenes/editar1.png' style='cursor:hand;' height="32" width="32" onclick="<?=$onclick_editar?>"></td>-->
  		 	<td align="center"><img src='../../imagenes/salir.gif' style='cursor:pointer;' height="32" width="32" onclick="<?=$onclick_marcar?>"></td>		 				
		 	</tr>	
		 	
	 		<?$res_comprobante->movenext();
	 	 }
	 	}
	 ?>
</table></td></tr>
  <br><br>
  <tr><td><table width=100% align="center" class="bordes">
	  <tr align="center">
	   <td>
	     <input type=button name="cerrar" class="btn btn-primary" value="Cerrar" onclick="window.close()">     
	   </td>
	  </tr>
  </table></td></tr>   
	 	 
</form>
<?=fin_pagina();// aca termino ?>
