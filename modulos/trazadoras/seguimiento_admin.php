<?
require_once ("../../config.php");
require_once ("../nacer/funciones_cronicos.php");


extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();


if ($_POST['guardar']=="Guardar"){		
	$db->StartTrans();
	$fecha_carga=date("Y-m-d H:m:s");
    $usuario=$_ses_user['name'];
	$fecha_comprobante=fecha_db($fecha_comprobante);
	$fecha_comprobante_proximo=fecha_db($fecha_comprobante_proximo);
	
	$q="select nextval('trazadoras.seguimiento_remediar_id_seguimiento_remediar_seq') as id_planilla";
    $id_planilla=sql($q) or fin_pagina();
    $id_planilla=$id_planilla->fields['id_planilla'];


    $q="select nextval('trazadoras.seguimiento_inmunizacion_id_inmunizacion_seq') as id_planilla";
    $id_planilla_1=sql($q) or fin_pagina();
    $id_inmunizacion=$id_planilla_1->fields['id_planilla'];

    $q="select nextval('trazadoras.seguimiento_interconsulta_id_interconsulta_seq') as id_planilla";
    $id_planilla_2=sql($q) or fin_pagina();
    $id_interconsulta=$id_planilla_2->fields['id_planilla'];

    $q="select nextval('trazadoras.seguimiento_tratamiento_id_tratamiento_seq') as id_planilla";
    $id_planilla_3=sql($q) or fin_pagina();
    $id_tratamiento=$id_planilla_3->fields['id_planilla'];

    $q="select nextval('trazadoras.seguimiento_consejeria_id_consejeria_seq') as id_planilla";
    $id_planilla_4=sql($q) or fin_pagina();
    $id_consejeria=$id_planilla_4->fields['id_planilla'];

    $ieca_ara=($ieca_ara=='on')? 'si':'no';//en desuso 2019-08-09
    $estatina=($estatina=='on')? 'si':'no';
    $aas=($aas=='on')? 'si':'no';
    $beta_bloqueantes=($beta_bloqueantes=='on')? 'si':'no'; //en desuso 2019-08-09
    $hipoglusemiante_oral=($hipoglusemiante_oral=='on')? 'si':'no';
    $insulina=($insulina=='on')? 'si':'no';
    $metformina=($metformina=='on')? 'si':'no';
    $enalapril=($enalapril=='on')?'si':'no';
    $losartan=($losartan=='on') ? 'si':'no';
    $amlodipina=($amlodipina=='on')? 'si':'no';
    $atenolol=($atenolol=='on')?'si':'no';
    $hidroclorotiazida=($hidroclorotiazida=='on')?'si':'no';
    
    $antigripal=($antigripal=='on')? 'si':'no';
    $neumococo13=($neumococo13=='on')? 'si':'no';
    $neumococo23=($neumococo23=='on')? 'si':'no';
    $doble_adulto=($doble_adulto=='on')? 'si':'no';
    $hepatitis_b=($hepatitis_b=='on')? 'si':'no';
	$covid=($covid=='on')? 'si':'no';

    $oftalmologia=($oftalmologia=='on')? 'si':'no';
    $cardiologia=($cardiologia=='on')? 'si':'no';
    $nefrologia=($nefrologia=='on')? 'si':'no';
    $laboratorio=($laboratorio=='on')? 'si':'no';
    $fonoaudiologia=($fonoaudiologia=='on')? 'si':'no';
    $psicologia=($psicologia=='on')? 'si':'no';
    $kinesiologia=($kinesiologia=='on')? 'si':'no';
    $activ_fis_adapt=($activ_fis_adapt=='on')? 'si':'no';
    $nutricion=($nutricion=='on')? 'si':'no';
    $odontologia=($odontologia=='on')? 'si':'no';
    $psiquiatria=($psiquiatria=='on')? 'si':'no';
    $farmacia=($farmacia=='on')? 'si':'no';

    $alimentacion_saludable=($alimentacion_saludable=='on')? 'si':'no';
    $actividad_fisica=($actividad_fisica=='on')? 'si':'no';
    $rastreo_tabaquismo=($rastreo_tabaquismo=='on')? 'si':'no';

    
	$query = "insert into trazadoras.seguimiento_remediar
				(id_seguimiento_remediar,dtm2,hta,ta_sist,ta_diast,tabaquismo,col_tot,gluc,peso,talla,imc,hba1c,ecg,fondodeojo,
				examendepie,microalbuminuria,hdl,ldl,tags,creatininemia,riesgo_global,riesgo_globala,comentario,fecha_carga,usuario,
				efector,fecha_comprobante,fecha_comprobante_proximo,clave_beneficiario,profesional,p_abd,ifg,ecocardiograma,estudiourinario,albuminuria_orina_aislada,proteinuria_orina_aislada,albuminuria_orina_24h,proteinuria_orina_24h,indice_ac,indice_pc,sedentarismo)
            values 
				($id_planilla,'$dtm2','$hta','$ta_sist','$ta_diast','$tabaquismo','$col_tot','$gluc','$peso','$talla','$imc','$hba1c','$ecg','$fondodeojo',
				'$examendepie','$microalbuminuria','$hdl','$ldl','$tags','$creatininemia','$riesgo_global','$riesgo_globala','$comentario','$fecha_carga','$usuario',
				'$efector','$fecha_comprobante','$fecha_comprobante_proximo','$clave_beneficiario','$profesional','$p_abd','$ifg','$ecocardiograma','$estudiourinario','$albuminuria_orina_aislada','$proteinuria_orina_aislada','$albuminuria_orina_24h','$proteinuria_orina_24h','$indice_ac','$indice_pc','$sedentarismo')";

    $res_extras = sql($query, "Error al insertar la Planilla") or fin_pagina();
    

    $query = "INSERT into trazadoras.seguimiento_inmunizacion
    			(id_inmunizacion,
				  id_seguimiento,
				  antigripal,
				  neumococo13,
				  neumococo23,
				  doble_adulto,
          		  hepatitis_b,
				  covid)
				  values
				  (
					$id_inmunizacion,
					$id_planilla,
					'$antigripal',
					'$neumococo13',
					'$neumococo23',
					'$doble_adulto',
          			'$hepatitis_b',
					'$covid')";

	$res_extras = sql($query, "Error al insertar Inmunizaciones") or fin_pagina();

	$query = "INSERT into trazadoras.seguimiento_interconsulta
				(id_interconsulta,
				  id_seguimiento,
				  oftalmologia,
				  cardiologia,
				  nefrologia,
				  laboratorio,
				  fonoaudiologia,
				  psicologia,
				  kinesiologia,
				  activ_fis_adapt,
				  nutricion,
				  odontologia,
				  psiquiatria,
				  farmacia)
				values
				($id_interconsulta,
					$id_planilla,
					'$oftalmologia',
					'$cardiologia',
					'$nefrologia',
					'$laboratorio',
					'$fonoaudiologia',
					'$psicologia',
					'$kinesiologia',
					'$activ_fis_adapt',
					'$nutricion',
					'$odontologia',
					'$psiquiatria',
					'$farmacia')";

	$res_extras = sql($query, "Error al insertar Interconsulta") or fin_pagina();

	$query = "INSERT into trazadoras.seguimiento_tratamiento
				( id_tratamiento,
				  id_seguimiento,
				  ieca_ara,
				  estatina,
				  aas,
				  beta_bloqueantes,
				  hipoglusemiante_oral,
				  insulina,
          metformina,
          enalapril,
          losartan,
          amlodipina,
          atenolol,
          hidroclorotiazida)
				values
				($id_tratamiento,
				$id_planilla,
				'$ieca_ara',
				'$estatina',
				'$aas',
				'$beta_bloqueantes',
				'$hipoglusemiante_oral',
				'$insulina',
        '$metformina',
        '$enalapril',
        '$losartan',
        '$amlodipina',
        '$atenolol',
        '$hidroclorotiazida')";

	$res_extras = sql($query, "Error al insertar Tratamiento") or fin_pagina();

	$query = "INSERT into trazadoras.seguimiento_consejeria
				( id_consejeria,
				  	id_seguimiento,
				  	alimentacion_saludable,
					actividad_fisica,
					rastreo_tabaquismo)
				values
				($id_consejeria,
				$id_planilla,
				'$alimentacion_saludable',
				'$actividad_fisica',
				'$rastreo_tabaquismo')";

	$res_extras = sql($query, "Error al insertar Consejeria") or fin_pagina();


    $accion = "Se guardo el Seguimiento Nro. " . $id_planilla . '</br>';
	
	//colocar codigo para facturacion de la
	if ($estatina=='si' or
		$aas=='si' or
		$beta_bloqueantes=='si' or
		$hipoglusemiante_oral=='si' or
		$insulina=='si' or 
		$metformina=='si' or
		$enalapril=='si' or 
		$losartan=='si' or 
		$amlodipina=='si' or
		$atenolol=='si' or
		$hidroclorotiazida=='si') $fact_medic = true;
					else $fact_medic = false;
	 
	$sql_smi="SELECT *
			from nacer.smiafiliados  
			where clavebeneficiario='$clave_beneficiario'";
	$res_smi=sql($sql_smi) or fin_pagina();
	$id_smiafiliados=$res_smi->fields['id_smiafiliados'];
	if (trim($res_smi->fields['activo']) == 'S') {
			$id_comprobante = facturar_seguimiento ($clave_beneficiario,$fecha_comprobante,$fact_medic);
			$accion .= "Se Genero el  comprobantes N° ".$id_comprobante." para facturacion."."</br>";
		} else $accion .= "Beneficiarios no Activo al momento de facturar."."</br>";
	//fin codigo de facturacion

	$db->CompleteTrans();  
}

if ($marcar=="True"){
	$query = "delete from trazadoras.seguimiento_remediar where id_seguimiento_remediar=$id_seguimiento_remediar";
    $res_extras = sql($query, "Error al insertar la Planilla") or fin_pagina();
    $accion = "Se Elimino el Seguiemiento Nro. " . $id_seguimiento_remediar;				
}

//inicial los datos para cargar el beneficiarios
if ($pagina=='listado_beneficiarios_leche.php'){
	$sql="select clavebeneficiario from nacer.smiafiliados where id_smiafiliados='$id_smiafiliados'";
	$res_clave = sql($sql, "Error al traer la clave del beneficiario") or fin_pagina();
	$clave_beneficiario=$res_clave->fields['clavebeneficiario'];
}
if ($clave_beneficiario){
	$sql="SELECT *
	     from uad.beneficiarios	 
		   where clave_beneficiario='$clave_beneficiario'";
	$res_comprobante=sql($sql, "Error al traer los Comprobantes") or fin_pagina();
  $id_beneficiario1=$res_comprobante->fields['id_beneficiarios'];

  $sql_smi="SELECT *
        from nacer.smiafiliados  
        where clavebeneficiario='$clave_beneficiario'";
  $res_smi=sql($sql_smi) or fin_pagina();
  $id_smiafiliados1=$res_smi->fields['id_smiafiliados'];
}


$clave_beneficiario=$res_comprobante->fields['clave_beneficiario'];
$apellido_benef=$res_comprobante->fields['apellido_benef'];
$nombre_benef=$res_comprobante->fields['nombre_benef'];
$numero_doc=$res_comprobante->fields['numero_doc'];
$fecha_nacimiento_benef=$res_comprobante->fields['fecha_nacimiento_benef'];
$sexo=$res_comprobante->fields['sexo'];
$calle=$res_comprobante->fields['calle'];

$sql_dh="SELECT * from trazadoras.clasificacion_remediar2 where clave_beneficiario='$clave_beneficiario'";
$res_dh=sql ($sql_dh) or fin_pagina();
$diabetico=trim($res_dh->fields['dmt']);
$hipertenso=trim($res_dh->fields['hipertenso']);
$rcvg=trim($res_dh->fields['rcvg']);
$fecha_clasificacion = $res_dh->fields['fecha_control'];


$fecha_hoy=date("Y-m-d");
$id_beneficiario1=($id_beneficiario1) ? $id_beneficiario1 : 0;
$id_smiafiliados1=($id_smiafiliados1) ? $id_smiafiliados1 : 0;
$sql_vacunas="SELECT * from trazadoras.vacunas 
              where (id_beneficiarios=$id_beneficiario1 or id_smiafiliados=$id_smiafiliados1)
              and (id_vac_apli=19 or id_vac_apli=45 or id_vac_apli=46
              or id_vac_apli=16 or id_vac_apli=17 or id_vac_apli=10 or id_vac_apli=2)
              and fecha_vac between '$fecha_hoy'::date - 365 and '$fecha_hoy'";
$res_vacunas=sql($sql_vacunas) or fin_pagina();

$vac_antigripal=0;
$vac_neumo23=0;
$vac_neumo13=0;
$vac_dobleadulto=0;
$vac_heptb=0;
$covid=0;

while (!$res_vacunas->EOF) {
    switch ($res_vacunas->fields['id_vac_apli']) {
       case '19':$vac_antigripal=1;break;

       case '45':$vac_antigripal=1;break;

       case '46':$vac_antigripal=1;break;

       case '16':$vac_neumo23=1;break;

       case '17':$vac_neumo13=1;break;

       case '10':$vac_dobleadulto=1;break;

       case '2':$vac_heptb=1;break;

       default: $res_vacunas->movenext();break;
     } 
  $res_vacunas->movenext(); 
}

echo $html_header;
?>
<script>
//controlan que ingresen todos los datos necesarios par el muleto

$(document).ready(function() {
	
	$('#orina_24hs').hide();
    $('#orina_aislada').hide();
	
	$('#estudiourinario').change(function(){
    str=$('select#estudiourinario option:checked').val();
    //alert(str);
    if (str=='-1') {
		$('#orina_24hs').hide();
	    $('#orina_aislada').hide();
	};

    if (str=='orina_aislada') {
    	$('#orina_24hs').hide();
     	$('#orina_aislada').show();
    };

    if (str=='orina_24hs') {
     	$('#orina_24hs').show();
     	$('#orina_aislada').hide();
 	};

 	});
})


function calculo_imc(){
	var t=document.all.talla.value;
	var p=document.all.peso.value;
	var i=0;
	var peso=document.all.peso.value;
	var talla=document.all.talla.value;
	document.all.peso.value = peso.replace(',','.');
	document.all.talla.value = talla.replace(',','.');
	
   if(t!=0) i=(p/(t * t));
  	 var original=i;
	 var result=Math.round(i*100)/100 ;
	 document.all.imc.readonly = true;
	 document.all.imc.value=result; 
	 document.all.imc.readonly = false;	 
}

function calculo_ifg(){

	//escribir la formula para el filtrado glomedular
	var creatininemia=Math.pow (document.all.creatininemia.value,-1.154);
	//var creatininemia=creatininemia.replace(',','.');
	var edad=Math.pow (document.all.edad.value,-0.203);
	var sexo=document.all.sexo.value;
		
   	 if (sexo=='F') var result=186*creatininemia*edad*0.742;
   	 	else var result=186*creatininemia*edad;
	 
	 result=result.toFixed(2);
	 document.all.ifg.readonly = true;
	 document.all.ifg.value=result; 
	 document.all.ifg.readonly = false;

	
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

 if (document.all.peso.value=='') {
        alert("Debe Colocar un Valor PESO");
        document.all.peso.focus();
        return false;
    } 
    
    if (isNaN(document.all.peso.value)) {
        alert("El Valor de PESO debe ser un número real");
        document.all.peso.focus();
        return false;
    } 
    
    if (document.all.peso.value<=10 || document.all.peso.value>=250) {
        alert("El Valor de PESO debe estar entre 10 y 250");
        document.all.peso.focus();
        return false;
    }   

    if (document.all.talla.value=='') {
        alert("Debe Colocar un Valor TALLA");
        document.all.talla.focus();
        return false;
    }    
    
    if (isNaN(document.all.talla.value)) {
        alert("El valor de TALLA debe ser un número entero");
        document.all.talla.focus();
        return false;
    }
            
    if (document.all.talla.value<=0.30 || document.all.talla.value>=2.50) {
        alert("El valor de TALLA debe estar comprendido entre 0.30 y 2.50");
        document.all.talla.focus();
        return false;
    }

	if (document.all.p_abd.value<50) {
        alert("El Valor de Perimetro Abdominal debe estar comprendido entre 50 y 200 cm");
        document.all.p_abd.focus();
        return false;
    } 

	if (document.all.p_abd.value>200) {
        alert("El Valor de Perimetro Abdominal debe estar comprendido entre 50 y 200 cm");
        document.all.p_abd.focus();
        return false;
    } 

	

	//tabaquismo
	if(document.all.tabaquismo.value=="-1"){
	alert('Debe Ingresar el Campo Tabaquismo');
	return false;
	}

	//examen de pie
	if(document.all.examendepie.value=="-1"){
	alert('Debe Ingresar el Campo Examen de Pie');
	return false;
	}

	//fondo de ojos
	if(document.all.fondodeojo.value=="-1"){
	alert('Debe Ingresar el Campo Fondo de Ojo');
	return false;
	}
 
	//la fecha de clasificacion no tiene que ser igual a la fecha de seguimiento
	if(document.all.fecha_comprobante.value==document.all.fecha_clasificacion.value){
	alert('La fecha de clasificion no puede ser igual a la fecha de seguimiento, minimo una semana de diferencia');
	return false;
	}

 if (confirm('Esta Seguro que Desea Guardar Seguimiento?'))return true;
 else return false;	
}//de function control_nuevos()

var img_ext='<?php echo $img_ext='../../imagenes/rigth2.gif' ?>';//imagen extendido
var img_cont='<?php echo $img_cont='../../imagenes/down2.gif' ?>';//imagen contraido
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

<form name='form1' action='seguimiento_admin.php' method='POST' enctype='multipart/form-data'>
<input type="hidden" name="clave_beneficiario" value="<?php echo $clave_beneficiario?>">
<?	$fecha_carga=date("Y-m-d");
	$edad_completo = edad_con_meses($fecha_nacimiento_benef,$fecha_carga);?>

<input type="hidden" name="edad" value="<?php echo $edad_completo['anos'];?>">

<?echo "<center><b><font size='+1' color='blue'>$accion</font></b></center>";?>
<table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
     <font size=+1><b>Beneficiario <?php echo $accion1?></b></font>    
    </td>
 </tr>
  
<tr><td>
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
              <input type='text' name='apellido_benef' value='<?php echo $apellido_benef;?>' size=50 align='right' readonly></b>
            </td>
         
            <td align="right">
         	  <b> Nombre:
         	</td>   
           <td  colspan="2">
             <input type='text' name='nombre_benef' value='<?php echo $nombre_benef;?>' size=50 align='right' readonly></b>
           </td>
          </tr>
          
          <tr>
           <td align="right">
         	  <b> Documento:
         	</td> 
           <td >
             <input type='text' name='numero_doc' value='<?php echo $numero_doc;?>' size=20 align='right' readonly></b>
           </td>
           <td align="right">
         	  <b> Fecha de Nacimiento:
         	</td> 
           <td>
             <input type='text' name='fecha_nacimiento_benef' value='<?php echo Fecha($fecha_nacimiento_benef);?>' size=20 align='right' readonly></b>
           </td>
          </tr>
          
          <tr>
           <td align="right">
         	  <b> Domicilio:
         	</td> 
           <td >
             <input type='text' name='calle' value='<?php echo $calle;?>' size=50 align='right' readonly></b>
           </td>        
          <td align="right">
         	  <b> Clave Beneficiario:
         	</td>   
           <td>
             <input type='text' name='clave_beneficiario' value='<?php echo $clave_beneficiario;?>' size=50 align='right' readonly></b>
           </td>
           <td>
             <input type='hidden' name='sexo' value='<?php echo $sexo;?>' size=50 align='right' readonly></b>
           </td>
         </tr>
         
        </table>
      </td>      
     </tr>
   </table>     

	
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
								<option value='<?php echo $cuie;?>'><?php echo $cuie." - ".$nombre_efector?></option>
								<?
								$res_efectores->movenext();
								}?>
			      			</select>
					    </td>
					 </tr>					 
					
					 <tr>
					 	<td align="right">
					    	<b>Fecha clasificacion:</b>	
					    </td>	
					    <td align="left">		    						    	
					    	<input type=text id=fecha_clasificacion name=fecha_clasificacion value='<?php echo fecha($fecha_clasificacion);?>' size=15 readonly>
					    	 				    	 
					    </td>
					  </tr>

					 <tr>
					 	<td align="right">
					    	<b>Fecha Seguimiento:</b>	
					    </td>	
					    <td align="left">		    						    	
					    	<?$fecha_comprobante=date("d/m/Y");?>
					    	 <input type=text id=fecha_comprobante name=fecha_comprobante value='<?php echo $fecha_comprobante;?>' size=15 readonly>
					    	 <?php echo link_calendario("fecha_comprobante");?>					    	 
					    </td>
					  </tr>
					  <tr>
					    <td align="right">
					    	<b>Fecha Proximo Seguimiento:</b>	
					    </td>	
					    <td align="left">				    						    	
					    	<?$fecha_comprobante_proximo=date("d/m/Y");?>
					    	 <input type=text id=fecha_comprobante_proximo name=fecha_comprobante_proximo value='<?php echo $fecha_comprobante_proximo;?>' size=15 readonly>
					    	 <?php echo link_calendario("fecha_comprobante_proximo");?>					    	 
					    </td>			    
					 </tr>

					 <tr>
					    <td align="right">
					    	<b>Profesional:</b>	
					    </td>	
					    <td align="left">				    						    	
					    	<input type="text" value="<?php echo $profesional;?>" name="profesional" size="20" title="Nombre Profesional"/>				    	 
					    </td>			    
					 </tr>
		
		  
		<tr>
            <td colspan="5" align="center">                       
                <table width=100% align="center" class="bordes" style="margin-top:5px">
                   <td align="center" id='mo' colspan="4"><b>Datos Seguimiento Cuatrimestral</b></td>
						<tr>
							<td align="left" style="padding:10px">
								<div style="display:outline;
								border: 2px solid #5D5DF9;
								padding: 5px;
								border-radius: 25px;
								border-width: thin;">
								<b title="DMT">DTM:</b>
								<select name="dtm2" title="dtm2">
                  <option value='-1' <?php echo ($diabetico=='')?'selected':'';?>>Seleccione</option>
									<option value='sin dmt' <?php echo ($diabetico=='0')?'selected':'';?>>SIN DMT</option>
                  <option value='dmt1' <?php echo ($diabetico=='1')?'selected':'';?>>DMT 1</option>
                  <option value='dmt2' <?php echo ($diabetico=='2')?'selected':'';?>>DMT 2</option>                  
								</select>
                
								<b title="Hipertension Arterial">HTA</b>
								<select name="hta" title="Hipertension Arterial">
								  <option value='-1' <?php echo ($hipertenso=='')?'selected':'';?>>Seleccione</option>
                                  <option value='SI' <?php echo ($hipertenso=='SI')?'selected':'';?>>SI</option>
                                  <option value='NO' <?php echo ($hipertenso!='SI')?'selected':'';?>>NO</option>
                                </select> 
								&nbsp;
								<b>Tabaquismo</b>
								<!--<input type="checkbox" name="tabaquismo"/>-->
								<select name="tabaquismo">
                                  <option value='-1' selected disabled>Seleccione</option>
                                  <option value='SI'>SI</option>
                                  <option value='NO'>NO</option>
                                </select>
								&nbsp;
								<b title="Sedentarismo">Sedentarismo</b>
								<select name="sedentarismo" title="sedentarismo">
                                  <option value='-1' selected disabled>Seleccione</option>
                                  <option value='SI'>SI</option>
                                  <option value='NO'>NO</option>
                                 </select>									
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

									
									<b title="Control del Peso">Peso</b>
									<input type="text" value="" name="peso" size="6" style="font-size:9px;" maxlength="5" title="Control del Peso" placeholder="Kg"/>
									<b title="Talla">Talla</b>
									<input type="text" value="" name="talla" size="6" style="font-size:9px;" maxlength="5" title="Talla" placeholder="Mts" onchange="calculo_imc()"/>
									&nbsp;
									<b title="IMC">IMC</b>
									<input type="text" value="" name="imc" size="6" style="font-size:9px;" maxlength="5" title="IMC" readonly/>
									&nbsp;
									<b title="Perimetro Abdominal">P. ABD</b>
									<input type="text" value="" name="p_abd" size="6" style="font-size:9px;" maxlength="5" title="Perimetro Abdominal" placeholder="Cm"/>
									&nbsp;
									<b title="Control de la Presion Arterial Sistolica">TA Sist</b>
									<input type="text" value="" name="ta_sist" size="3" style="font-size:9px;" maxlength="3" title="Control de la Presion Arterial Sistolica"/>
									&nbsp;
									<b title="Control de la Presion Arterial Diastolica">TA Diast</b>
									<input type="text" value="" name="ta_diast" size="3" style="font-size:9px;" maxlength="3" title="Control de la Presion Arterial Diastolica"/>
									&nbsp;
									<b title="Examen de Pie">Examen de Pie</b>
									<!--<input type="checkbox" name="examendepie" title="Examen de Pie" />-->
									<select name="examendepie" title="Examen de Pie">
	                                  <option value='-1' selected disabled>Seleccione</option>
	                                  <option value='NO'>NO</option>
	                                  <option value='SIN RIESGO'>SIN RIESGO</option>
	                                  <option value='CON RIESGO'>CON RIESGO</option>
	                                </select>
									<!--<b title="Microalbuminuria">Microalbuminuria</b>
									<input type="text" value="" name="microalbuminuria" size="7" style="font-size:9px;" maxlength="10" title="Control de la Microalbuminuria"/>-->
								&nbsp;
									</div>
							</td>
						</tr>
				<tr>
					&nbsp;

				<tr>
				<td align="left" style="padding:10px">	
				<div style="display:outline;
				border: 2px solid #5D5DF9;
				padding: 5px;
				border-radius: 25px;
				border-width: thin;">
				&nbsp;
				<b title="Inmunizaciones">Inmunizaciones:</b>&nbsp;&nbsp;
				<input type="checkbox" name="antigripal" <?php echo ($vac_antigripal)?'checked':''?>/>ANTIGRIPAL &nbsp;
				<input type="checkbox" name="neumococo13" <?php echo ($vac_neumo13)?'checked':''?>/>NEUMOCOCO 13 &nbsp;
				<input type="checkbox" name="neumococo23" <?php echo ($vac_neumo23)?'checked':''?>/>NEUMOCOCO 23 &nbsp;
				<input type="checkbox" name="doble_adulto" <?php echo ($vac_dobleadulto)?'checked':''?>/>DOBLE ADULTO &nbsp;
				<input type="checkbox" name="hepatitis_b" <?php echo ($vac_heptb)?'checked':''?>/>HEPATITS B &nbsp;&nbsp; 
				<input type="checkbox" name="covid" <?php echo ($covid)?'checked':''?>/>COVID-19 &nbsp;&nbsp; 																						
				</div>
			</td>
			</tr>
							
							<td align="left" style="padding:10px">
								<div style="display:outline;
								border: 2px solid #5D5DF9;
								padding: 5px;
								border-radius: 25px;
								border-width: thin;">
									
									<b title="Control de la Glucosa">Gluc</b>
									<input type="text" value="" name="gluc" size="6" style="font-size:9px;" maxlength="5" title="Control de la Glucosa" placeholder="mg/dl" />
									&nbsp;
									<b title="Control del colesterol Total">Col. Tot.</b>
									<input type="text" value="" name="col_tot" size="6" style="font-size:9px;" maxlength="5" title="Control del colesterol Total"
									placeholder="mg/dl"/>
									&nbsp;
									<b title="HDL">HDL</b>
									<input type="text" value="" name="hdl" size="6" style="font-size:9px;" maxlength="5" title="Control del HDL"/>
									&nbsp;
									<b title="Control del LDL">LDL</b>
									<input type="text" value="" name="ldl" size="6" style="font-size:9px;" maxlength="5" title="Control del LDL"/>
									&nbsp;
									<b title="Control del TAGs">TAGs</b>
									<input type="text" value="" name="tags" size="6" style="font-size:9px;" maxlength="5" title="Control del TAGs"/>
									&nbsp;
									<b title="HbA1c">HbA1c</b>
									<input type="text" value="" name="hba1c" size="6" style="font-size:9px;" maxlength="5" title="HbA1c"/>
									</br>
									</br>
									<b title="Control de Creatininemia">Crt</b>
									<input type="text" value="" name="creatininemia" size="5" style="font-size:9px;" maxlength="5" title="Control de Creatininemia"
										 placeholder="mg/dl" onchange="calculo_ifg()" />
									&nbsp;
									<b title="Indice de Filtrado Glomerular">IFG</b>
									<input type="text" value="" name="ifg" size="6" style="font-size:9px;" maxlength="5" />
									&nbsp;
									<b title="Indice A/C">Ind.A/C</b>
									<input type="text" value="" name="indice_ac" size="6" style="font-size:9px;" maxlength="5" placeholder="mg/g" />
								
									<b title="Indice A/C">Ind.P/C</b>
									<input type="text" value="" name="indice_pc" size="6" style="font-size:9px;" maxlength="5" placeholder="mg/g" />

									<b title="Estudio Urinario">Estudio Urinario</b>
									<!--<input type="checkbox" name="examendepie" title="Examen de Pie" />-->
									<select name="estudiourinario" id='estudiourinario' title="Estudio Urinario">
                                      <option value='-1' selected>Seleccione</option>
                                      <option value='orina_aislada'>ORINA AISLADA</option>
                                      <option value='orina_24hs'>ORINA 24 HS</option>
                                    </select>
									&nbsp;
									<div id='orina_aislada'>
									<br>
									<b title="Albuminuria">Albuminuria</b>
									<input type="numeric" value="" name="albuminuria_orina_aislada" size="7" style="font-size:9px;" maxlength="10" title="Control de la Microalbuminuria"/>mg/L
									&nbsp;
									<b title="Proteinuria">Proteinuria</b>
									<input type="text" value="" name="proteinuria_orina_aislada" size="7" style="font-size:9px;" maxlength="10" title="Control de Proteinuria"/>mg/L
									</div>
									
									<div id='orina_24hs'>
									<br>
									<b title="Albuminuria">Albuminuria</b>
									<input type="text" value="" name="albuminuria_orina_24h" size="7" style="font-size:9px;" maxlength="10" title="Control de la Microalbuminuria"/>mg/Dia
									&nbsp;
									<b title="Proteinuria">Proteinuria</b>
									<input type="text" value="" name="proteinuria_orina_24h" size="7" style="font-size:9px;" maxlength="10" title="Control de Proteinuria"/>mg/Dia
									&nbsp;
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
									<b title="Ecocardiograma">Ecocardiograma</b>
									<!--<input type="checkbox" name="fondodeojo"/>-->
									<select name="ecocardiograma">
                                      <option value='-1' selected disabled>Seleccione</option>
                                      <option value='no solicitado'>NO SOLICITADO</option>
                                      <option value='solicitado'>SOLICITADO</option>
                                      <option value='normal'>REALIZADO - NORMAL</option>
                                      <option value='patologico'>REALIZADO - PATOLOGICO</option>
                                    </select>
                                    &nbsp;
									<b title="ECG">ECG</b>
									<!--<input type="checkbox" name="fondodeojo"/>-->
									<select name="ecg">
                                      <option value='-1' selected disabled>Seleccione</option>
                                     <option value='no solicitado'>NO SOLICITADO</option>
                                      <option value='solicitado'>SOLICITADO</option>
                                      <option value='normal'>REALIZADO - NORMAL</option>
                                      <option value='patologico'>REALIZADO - PATOLOGICO</option>
                                    </select>
									&nbsp;
									<b title="Fondo de Ojo">Fondo de Ojo</b>
									<select name="fondodeojo">
                                      <option value='-1' selected disabled>Seleccione</option>
                                      <option value='no solicitado'>NO SOLICITADO</option>
                                      <option value='solicitado'>SOLICITADO</option>
                                      <option value='normal'>REALIZADO - NORMAL</option>
                                      <option value='patologico'>REALIZADO - PATOLOGICO</option>
                                    </select>
							</br>
							</br>
                                    
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
									<b title="Interconsulta Especialidad">Interconsultas: </b>
									<input type="checkbox" name="oftalmologia"/> Oftalmologia &nbsp;
									<input type="checkbox" name="cardiologia"/> Cardiologia &nbsp;
									<input type="checkbox" name="nefrologia"/> Nefrologia &nbsp;
									<input type="checkbox" name="laboratorio"/> Laboratorio &nbsp;
									<input type="checkbox" name="fonoaudiologia"/> Fonoaudiologia &nbsp;
									<input type="checkbox" name="psicologia"/> Psicologia &nbsp;
									<input type="checkbox" name="kinesiologia"/> Kinesiologia &nbsp;
									<input type="checkbox" name="activ_fis_adapt"/> Activ.Fis.Adapt. &nbsp;
									<input type="checkbox" name="nutricion"/> Nutricion &nbsp;
									<input type="checkbox" name="odontologia"/> Odontologia &nbsp;
									<input type="checkbox" name="psiquiatria"/> Psiquiatria &nbsp;
									<input type="checkbox" name="farmacia"/> Farmacia &nbsp;
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
							<b title="Tratamiento">Tratamiento: </b>&nbsp;&nbsp;
							<!--<input type="checkbox" name="ieca_ara"/> IECA/ARA &nbsp;-->
              <input type="checkbox" name="enalapril"/> Enalapril  &nbsp;
              <input type="checkbox" name="losartan"/> Losartán  &nbsp;
              <input type="checkbox" name="amlodipina"/> Amlodipina &nbsp;
              <input type="checkbox" name="atenolol"/> Atenolol &nbsp;
              <input type="checkbox" name="hidroclorotiazida"/> Hidroclorotiazida  &nbsp;
							<input type="checkbox" name="estatina"/> Estatina &nbsp;
							<input type="checkbox" name="aas"/> AAS &nbsp;
							<!--<input type="checkbox" name="beta_bloqueantes"/> BETA BLOQUEANTES &nbsp;-->
							<input type="checkbox" name="hipoglusemiante_oral"/> Hipoglucemiante Oral &nbsp;
							<input type="checkbox" name="insulina"/>Insulina &nbsp;
							&nbsp;
              <input type="checkbox" name="metformina"/>Metfomina &nbsp;
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
							&nbsp;
							<b title="Consejeria">Consejeria: </b>&nbsp;&nbsp;
							<input type="checkbox" name="alimentacion_saludable"/>Alimentación Saludable &nbsp;
							<input type="checkbox" name="actividad_fisica"/>Práctica Regular de Actividad Física &nbsp;
							<input type="checkbox" name="rastreo_tabaquismo"/>rastreo de tabaquismo y consejo breve  para dejar de fumar &nbsp;
							&nbsp;
							</div>
						</td>
						</tr>
                   </td>
			    </table>
			</td>
		</tr>
				
		<tr>
            <td colspan="4" align="center">                       
                <table width=95% align="center" class="bordes" style="margin-top:5px">
                   <td align="center" id='mo' colspan="4"><b>RCGV INICIAL</b></td>
						<tr>
							<td align="left" style="padding-left:10px">
								<div style="display:inline;">
									<input style="margin-left:10px" type="radio" value="bajo" name="riesgo_global" title="Riesgo Bajo" <?php echo ($rcvg=='bajo') ? 'checked': ''?>/> Bajo < 10%
									<input style="margin-left:10px" type="radio" value="mode" name="riesgo_global" title="Riesgo Moderado" <?php echo ($rcvg=='mode')? 'checked': ''?>/> Moderado 10% a < 20% 
									<input style="margin-left:10px" type="radio" value="alto" name="riesgo_global" title="Riesgo Alto" <?php echo ($rcvg=='alto')? 'checked': ''?>/> Alto 20% a < 30% 
									<input style="margin-left:10px" type="radio" value="malto" name="riesgo_global" title="Riesgo Muy Alto" <?php echo ($rcvg=='malto')? 'checked': ''?>/> Muy Alto > 30%
									<input style="margin-left:10px" type="radio" value="perse" name="riesgo_global" title="Riesgo PER SE" <?php echo ($rcvg=='per se')? 'checked': ''?>/> PER SE  
								</div>
							</td>
						</tr>								
                   </td>
			    </table>
			</td>
		</tr>
        	        
        	    <tr>
                    <td colspan="4" align="center">                       
                        <table width=95% align="center" class="bordes" style="margin-top:5px">
                           <td align="center" id='mo' colspan="4"><b>RCGV ACTUAL</b></td>
								<tr>
									<td align="left" style="padding-left:10px">
										<div style="display:inline;">
											<input style="margin-left:10px" type="radio" value="bajoa" name="riesgo_globala" title="Riesgo Bajo" /> Bajo < 10%
											<input style="margin-left:10px" type="radio" value="modea" name="riesgo_globala" title="Riesgo Moderado" /> Moderado 10% a < 20% 
											<input style="margin-left:10px" type="radio" value="altoa" name="riesgo_globala" title="Riesgo Alto" /> Alto 20% a < 30% 
											<input style="margin-left:10px" type="radio" value="maltoa" name="riesgo_globala" title="Riesgo Muy Alto" /> Muy Alto > 30% 
											<input style="margin-left:10px" type="radio" value="perse" name="riesgo_globala" title="Riesgo PER SE" /> PER SE
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
	         </td>
         </tr>   					 
		</td>
	</tr>
</table>
</td></tr>	  

		
			 <tr>
			  	<td align="center" colspan="2" class="bordes">		      
			    	<input type="submit" name="guardar" value="Guardar" title="Guardar" Style="width=230" onclick="return control_nuevos()">
			    </td>
			 </tr> 
			 
	</table>
 </td></tr>
</table>
<?

//tabla de comprobantes
$query="SELECT * FROM trazadoras.seguimiento_remediar
        WHERE clave_beneficiario='$clave_beneficiario' ORDER BY id_seguimiento_remediar DESC";

$res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();
?>
<tr><td><table width="100%" class="bordes" align="center">
	<tr align="center" id="mo">
	  <td align="center" width="3%">
	   <img id="imagen_2" src="<?php echo $img_ext?>" border=0 title="Mostrar Seguimientos" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida,2);" >
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
	 		<td width="10%">Fecha Control</td>
	 		<!--<td>Edita Fecha</td>-->
      <td width="10%">DBT</td>
      <td width="10%">HTA</td>
      <td width="7%">TAS</td>
      <td width="7%">DAS</td>
      <td width="10">IMC</td>
      <td width="10%">P.Abd</td>
      <td width="5%">Tabaq.</td>
      <td width="5%">Col.Tot</td>
      <td width="5%">HbA1c</td>
      <td width="10%">Ex.Pie</td>
      <td width="10%">fondo de Ojo</td>
      <td width="10%">IFG</td>
      <td width="10%">Rel. A/C</td>
      <td width="5%">Borrar</td> 
	 	</tr>
	 	<?
	 	$res_comprobante->movefirst();
	 		while (!$res_comprobante->EOF){
	 		$ref1 = encode_link("seguimiento_admin.php",array("id_seguimiento_remediar"=>$res_comprobante->fields['id_seguimiento_remediar'],"clave_beneficiario"=>$clave_beneficiario,"marcar"=>"True"));
            $onclick_marcar="if (confirm('Esta Seguro que Desea Eliminar?')) location.href='$ref1'
            				else return false; ";
							
			$ref_editar = encode_link("modifica_seguimiento.php",array("id_seguimiento_remediar"=>$res_comprobante->fields['id_seguimiento_remediar']));
			
      $onclick_editar="if (confirm('Esta Seguro que Desea Editar la Fecha del Seguimiento?')) location.href='$ref_editar' else return false;	";

      $ref_ver = encode_link("seguimiento_muestra.php",array("id_seguimiento_remediar"=>$res_comprobante->fields['id_seguimiento_remediar']));

      $onclick_ver="location.href='$ref_ver'";
            ?>

	 		<tr <?php echo atrib_tr()?>>	
		 	<td onclick="<?php echo $onclick_ver?>"><?php echo fecha($res_comprobante->fields['fecha_comprobante'])?></td>
        <!--<td align="center"><img src='../../imagenes/editar1.png' style='cursor:hand;' height="32" width="32" onclick="<?php echo $onclick_editar?>"></td>-->
	 		<td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['dtm2']?></td>
      <td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['hta']?></td>
      <td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['ta_sist']?></td>
      <td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['ta_diast']?></td>
      <td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['imc']?></td>
      <td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['p_abd']?></td>
      <td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['tabaquismo']?></td>
      <td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['col_tot']?></td>
      <td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['hba1c']?></td>
      <td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['examendepie']?></td>
      <td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['fondodeojo']?></td>
      <td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['ifg']?></td>
      <td align="center" onclick="<?php echo $onclick_ver?>"><?php echo $res_comprobante->fields['indice_ac']?></td>
      <td align="center"><img src='../../imagenes/trash.png' style='cursor:pointer;' height="32" width="32" onclick="<?php echo $onclick_marcar?>"></td>		 				
		 	</tr>	
		 	
	 		<?$res_comprobante->movenext();
	 	 }
	 	}
	 ?>
</table></td></tr>
 
  <tr><td><table width=100% align="center" class="bordes">
	  <tr align="center">
	   <td>
	     <input type=button name="cerrar" value="Cerrar" onclick="window.close()">     
	   </td>
	  </tr>
  </table></td></tr>   
	 	 
</form>
<?php echo fin_pagina();// aca termino ?>
