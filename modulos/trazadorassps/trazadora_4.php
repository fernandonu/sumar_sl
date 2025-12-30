<?
/*
$Author: seba $
$Revision: 1.00 $
$Date: 2016/05/13 19:12:40 $
*/

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



function edad_con_meses($fecha_de_nacimiento){ 
  $fecha_actual = date ("Y-m-d"); 

  // separamos en partes las fechas 
  $array_nacimiento = explode ( "-", $fecha_de_nacimiento ); 
  $array_actual = explode ( "-", $fecha_actual ); 

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


function calculo_percentilo_imc($meses,$sexo,$imc){

  $sql="SELECT * from nacer.percentilos_imc where meses=$meses and sexo='$sexo'";
  $res_sql=sql($sql) or fin_pagina();

  if ($res_sql->RecordCount()!=0) {
    switch ($imc){
      case $imc<=$res_sql->fields['p3'] : return '1';breck;
      case $imc>$res_sql->fields['p3'] and $imc<=$res_sql->fields['p10'] : return '2';breck;
      case $imc>$res_sql->fields['p10'] and $imc<=$res_sql->fields['p90'] : return '3';breck;
      case $imc>$res_sql->fields['p90'] and $imc<=$res_sql->fields['p97'] : return '4';breck;
      case $imc>$res_sql->fields['p97'] :return '5';breck;
    }
  }
}

function calculo_percentilo_peso($dias,$sexo,$peso){

  $sql="SELECT * from nacer.percentilos_peso where dias=$dias and sexo='$sexo'";
  $res_sql=sql($sql) or fin_pagina();

  if ($res_sql->RecordCount()!=0) {
    switch ($peso){
      case $peso<=$res_sql->fields['p3'] : return '1';breck;
      case $peso>$res_sql->fields['p3'] and $peso<=$res_sql->fields['p10'] : return '2';breck;
      case $peso>$res_sql->fields['p10'] and $peso<=$res_sql->fields['p90'] : return '3';breck;
      case $peso>$res_sql->fields['p90'] and $peso<=$res_sql->fields['p97'] : return '4';breck;
      case $peso>$res_sql->fields['p97'] :return '5';breck;
    }
  }
}

function calculo_percentilo_talla($dias,$sexo,$talla){

  $sql="SELECT * from nacer.percentilos_talla where dias=$dias and sexo='$sexo'";
  $res_sql=sql($sql) or fin_pagina();

  if ($res_sql->RecordCount()!=0) {
    switch ($talla){
      case $talla<=$res_sql->fields['p3'] : return '1';breck;
      case $talla>$res_sql->fields['p3'] and $talla<=$res_sql->fields['p10'] : return '2';breck;
      case $talla>$res_sql->fields['p10'] and $talla<=$res_sql->fields['p90'] : return '3';breck;
      case $talla>$res_sql->fields['p90'] and $talla<=$res_sql->fields['p97'] : return '4';breck;
      case $talla>$res_sql->fields['p97'] :return '5';breck;
    }
  }
}



if ($_POST['guardar']=="Guardar Planilla"){
   $fecha_carga=date("Y-m-d");
   $usuario=$_ses_user['name'];
   $db->StartTrans();   
   $fecha_ctrl_trz4=Fecha_db($_POST['fecha_control_trz4']); 
   $peso_trz4=$_POST['peso_trz4'];
   $talla_trz4=$_POST ['talla_trz4'];
   $perimetro_cefalico_trz4=$_POST['perimetro_cefalico_trz4'];
   /*$percentilo_peso_edad_trz4=$_POST['percentilo_peso_edad_trz4'];
   $percentilo_talla_edad_trz4=$_POST['percentilo_talla_edad_trz4'];
   $percentilo_peso_talla_trz4=$_POST['percentilo_peso_talla_trz4'];*/
   $percentilo_perim_cefalico_edad_trz4=$_POST['percentilo_per_cefalico_edad_trz4'];
   $comentario_trz4=$_POST['observaciones_trz4'];
   
   if ($entidad_alta=='na') {
   $sql_benef="select * from nacer.smiafiliados where id_smiafiliados=$id_smiafiliados";
   $res_benef=sql($sql_benef,"no se pudieron traer los datos de smiafiliados");
   $fecha_nac_trz4=$res_benef->fields['afifechanac'];
   $sexo=trim($res_benef->fields['afisexo']);
   $id_beneficiarios=0;
   }
   else {
      if ($id_smiafiliados) $id_beneficiarios=$id_smiafiliados;
      $sql_benef="select * from leche.beneficiarios where id_beneficiarios='$id_beneficiarios'";
      $res_benef=sql($sql_benef,"no se pudieron traer los datos de smiafiliados");
      $fecha_nac_trz4=$res_benef->fields['fecha_nac'];
      $sexo=$res_benef->fields['sexo'];
      $id_smiafiliados=0;
   }
   
   
  $edad_con_meses=edad_con_meses($fecha_nac_trz4);
  $anio=$edad_con_meses['anos'];
  $meses=$edad_con_meses['meses'];
  $dias=$edad_con_meses['dias'];
    
    if ($anio==0) {
    $dias=$dias+($meses*30);
    $percentilo_peso_edad_trz4=calculo_percentilo_peso($dias,$sexo,$peso);
    }

    if ($anio>=1 and $anio<=5) {
    $meses=$meses+($anio*12);
    $dias=$dias+($meses*30);
    $percen_imc_edad=calculo_percentilo_imc($meses,$sexo,$imc);
    $percentilo_peso_edad_trz4=calculo_percentilo_peso($dias,$sexo,$peso);
    $percentilo_talla_edad_trz4=calculo_percentilo_talla($dias,$sexo,($talla*100));

    };
    
    $percentilo_peso_talla_trz4="Datos sin Ingresar";

   
   $q="select nextval('trazadorassps.seq_id_trz4') as id_planilla";
   $id_planilla=sql($q) or fin_pagina();
   $id_planilla=$id_planilla->fields['id_planilla'];
   
   $query="insert into trazadorassps.trazadora_4	
             (id_trz4,cuie,id_smiafiliados,id_beneficiarios,fecha_nac,
  				fecha_control ,
  				peso ,
  				talla,
      		perimetro_cefalico,
      		percentilo_peso_edad,
  				percentilo_talla_edad,
  				percentilo_perim_cefalico_edad,
  				percentilo_peso_talla,
  				fecha_carga,
  				usuario,
  				comentario)
             values
             ('$id_planilla','$cuie',$id_smiafiliados,'$id_beneficiarios','$fecha_nac_trz4','$fecha_ctrl_trz4',
             '$peso_trz4','$talla_trz4','$perimetro_cefalico_trz4',
             '$percentilo_peso_edad_trz4',
             '$percentilo_talla_edad_trz4','$percentilo_perim_cefalico_edad_trz4',
             '$percentilo_peso_talla_trz4','$fecha_carga','$usuario','$comentario_trz4')";

    sql($query, "Error al insertar la Planilla") or fin_pagina();
    
    $accion="Se guardo la Planilla";    
	 
    $db->CompleteTrans();
    
    echo "<script>window.opener.$('#guardar_prestacion').prop('disabled',false);window.close()</script>";  
}//de if ($_POST['guardar']=="Guardar nuevo Muleto")

if (($pagina=='prestacion_admin.php')&&($pagina_viene!="comprobante_admin_total.php")){
	
	if ($entidad_alta=='na') {
  $sql="select * from nacer.smiafiliados where id_smiafiliados=$id_smiafiliados";
	$res_extra=sql($sql, "Error al traer el beneficiario") or fin_pagina();
	
	$clave=$res_extra->fields['clavebeneficiario'];
	$tipo_doc=$res_extra->fields['afitipodoc'];
	$num_doc=number_format($res_extra->fields['afidni'],0,'.','');
	$apellido=$res_extra->fields['afiapellido'];
	$nombre=$res_extra->fields['afinombre'];
  $fecha_nac_trz4=$res_extra->fields['afifechanac'];
}
else {
  if ($id_smiafiliados) $id_beneficiarios=$id_smiafiliados;
  $sql="select * from leche.beneficiarios where id_beneficiarios='$id_beneficiarios'";
  $res_extra=sql($sql, "Error al traer los datos de leche.beneficiarios") or fin_pagina();

  $num_doc=$res_extra->fields['documento'];
  $apellido=$res_extra->fields['apellido'];
  $nombre=$res_extra->fields['nombre'];
  $fecha_nac_trz4=$res_extra->fields['fecha_nac'];
  }
	
	$fecha_control=$fecha_comprobante;
	$fpcp=$fecha_comprobante;
}

if ($pagina_viene=="comprobante_admin_leche.php") {
  
  if ($entidad_alta=='na'){
  $sql="select * from nacer.smiafiliados where id_smiafiliados=$id_smiafiliados";
  $res_extra=sql($sql, "Error al traer el beneficiario") or fin_pagina();
  
  $clave=$res_extra->fields['clavebeneficiario'];
  $tipo_doc=$res_extra->fields['afitipodoc'];
  $num_doc=number_format($res_extra->fields['afidni'],0,'.','');
  $apellido=$res_extra->fields['afiapellido'];
  $nombre=$res_extra->fields['afinombre'];
  $fecha_nac_trz4=$res_extra->fields['afifechanac'];
  
  $fecha_control=$fecha_comprobante;
  $fpcp=$fecha_comprobante;
  }
  else {
      if ($id_smiafiliados) $id_beneficiarios=$id_smiafiliados;
      $sql="select * from leche.beneficiarios where id_beneficiarios='$id_beneficiarios'";
      $res_extra=sql($sql, "Error al traer el beneficiario") or fin_pagina();
  
       $tipo_doc="DNI";
       $num_doc=$res_extra->fields['documento'];
       $apellido=$res_extra->fields['apellido'];
       $nombre=$res_extra->fields['nombre'];
       $fecha_nac_trz4=$res_extra->fields['fecha_nac'];
  
      $fecha_control=$fecha_comprobante;
      $fpcp=$fecha_comprobante;
       }
}

if ($id_planilla) {

	if ($entidad_alta=='na') {
  $sql="select * from nacer.smiafiliados where id_smiafiliados=$id_smiafiliados";
  $res_comprobante=sql($sql, "Error al traer los Comprobantes") or fin_pagina();
    
  $apellido=trim($res_comprobante->fields['afiapellido']);
	$nombre=trim($res_comprobante->fields['afinombre']);
	$num_doc=number_format($res_comprobante->fields['afidni'],0,'.','');
	$localidad=$res_comprobante->fields['afidomlocalidad'];
	$fecha_nac=$res_comprobante->fields['afifechanac'];
	$sexo=$res_comprobante->fields['afisexo'];
  }
  else {
    if ($id_smiafiliados) $id_beneficiarios=$id_smiafiliados;
    $sql="select * from leche.beneficiarios where id_beneficiarios=$id_beneficiarios";
    $res_comprobante=sql($sql, "Error al traer los datos leche.beneficiarios") or fin_pagina();
    
  $apellido=trim($res_comprobante->fields['apellido']);
  $nombre=trim($res_comprobante->fields['nombre']);
  $num_doc=$res_comprobante->fields['documento'];
  $fecha_nac=$res_comprobante->fields['fecha_nac'];
  $sexo=$res_comprobante->fields['sexo'];
   }
}
echo $html_header;
?>
<script>
//controlan que ingresen todos los datos necesarios par el muleto
function control_nuevos()
{ 
 if(document.all.cuie.value=="-1"){
  alert('Debe Seleccionar un Efector');
  return false;
 } 
 if(document.all.tipo_doc.value=="-1"){
  alert('Debe Seleccionar un Tipo de Documento');
  return false; 
 }   
 if(document.all.num_doc.value==""){
  alert('Debe Ingresar un Documento');
  return false;
 }
 if(document.all.apellido.value==""){
  alert('Debe Ingresar un apellido');
  return false;
 }
 if(document.all.nombre.value==""){
  alert('Debe Ingresar un nombre');
  return false;
 } 
 if(document.all.fecha_control_trz4.value==""){
	  alert('Debe Ingresar Fecha de Control');
	  return false;
	 }
if (document.all.peso_trz4.value==""){ 
		alert('Debe ingresar el Peso');
		document.all.peso_trz4.focus();
		return false;
		}
	
	if (isNaN(document.all.peso_trz4.value)) {
		alert('El dato del Peso debe ser un Numero Real');
		document.all.peso_trz4.focus();
		return false;
		}
	
	if (document.all.peso_trz4.value>250) {
		alert ('El Peso debe ser menor de 250 Kg');
		document.all.peso_trz4.focus();
		return false;
		}
		
	if (document.all.peso_trz4.value<1.5) {
		alert ('El Peso debe ser mayor de 1.5 Kg');
		document.all.peso_trz4.focus();
		return false;
		}
   
if(document.all.talla_trz4.value==""){
    alert('Debe ingresar la talla');
      document.all.talla_trz4.focus();
    return false;
   
   } else   if(document.all.talla_trz4.value >= 180  ){
    alert('la talla no puede superar los 180 Cm');
    document.all.talla_trz4.focus();
    return false;
   
   } else  if(document.all.talla_trz4.value<=40  ){
    alert('la talla no puede menor a 40 Cm');
    document.all.talla_trz4.focus();
    return false;
   }
   
if(document.all.perimetro_cefalico_trz4.value==""){
	  alert('Debe Ingresar el Perimetro Cefalico');
	  return false;
	 }
   else   if(document.all.perimetro_cefalico_trz4.value <29  ){
    alert('El perimetro Cefalico no puede ser menor que 29 Cm');
    document.all.talla_trz4.focus();
    return false;
   
   } else  if(document.all.perimetro_cefalico_trz4.value>54  ){
    alert('El Perimetro Cefalico no puede superar los 54 Cm');
    document.all.talla_trz4.focus();
    return false;
   }
 
}//de function control_nuevos()


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

<form name='form1' action='trazadora_4.php' method='POST'>
<input type="hidden" value="<?=$id_planilla?>" name="id_planilla">
<input type="hidden" value="<?=$pagina?>" name="pagina">
<input type="hidden" value="<?=$id_smiafiliados?>" name="id_smiafiliados">
<input type="hidden" value="<?=$id_beneficiarios?>" name="id_beneficiarios">
<input type="hidden" value="<?=$entidad_alta?>" name="entidad_alta">

<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<?echo "<center><b><font size='+1' color='Blue'>$accion2</font></b></center>";?>
<?echo $parametros['id_smiafiliados'];?>

<table width="85%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
    	<?
    	if (!$id_planilla) {
    	?>  
    	<font size=+1><b>Nuevo Dato</b></font>   
    	<? }
        else {
        ?>
        <font size=+1><b>Dato</b></font>   
        <? } ?>
       
    v3.3</td>
 </tr>
 <tr><td>
  <table width=90% align="center" class="bordes">
     <tr>
      <td id=mo colspan="2">
       <b> Descripción de la PLANILLA</b></td>
     </tr>
     <tr>
       <td>
        <table>
         
        <tr>	           
           <td align="center" colspan="2">
            <b> Número del Dato: <font size="+1" color="Red"><?=($id_planilla)? $id_planilla : "Nuevo Dato"?></font> </b>
           </td>
         </tr>
         
         <tr>	           
           <td align="center" colspan="2">
             <b><font size="2" color="Red">Nota: Los valores numericos se ingresan SIN separadores de miles, y con "." como separador DECIMAL</font> </b>
           </td>
         </tr>
         
         <tr>
         	<td align="right">
         	  <b>Número de Documento:</b>
         	</td>         	
            <td align='left'>
              <input type="text" size="40" value="<?=$num_doc?>" name="num_doc" <? if ($id_planilla) echo "readonly"?>>
              </td>
         </tr> 
         
         <tr>
         	<td align="right">
				<b>Efector:</b>
			</td>
			<td align="left">			 	
			 <select name=cuie Style="width=257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_planilla) echo "disabled"?> >
			 <option value=-1>Seleccione</option>
			 <?
			 $sql= "select * from nacer.efe_conv order by nombre";
			 $res_efectores=sql($sql) or fin_pagina();
			 while (!$res_efectores->EOF){ 
			 	$cuiel=$res_efectores->fields['cuie'];
			    $nombre_efector=$res_efectores->fields['nombre'];
			    
			    ?>
				<option value='<?=$cuiel?>' <?if ($cuie==$cuiel) echo "selected"?> ><?=$cuiel." - ".$nombre_efector?></option>
			    <?
			    $res_efectores->movenext();
			    }?>
			</select>
			</td>
         </tr>
         
         <tr>
         	<td align="right">
         	  <b>Clave Beneficiario:</b>
         	</td>         	
            <td align='left'>
              <input type="text" size="40" value="<?=$clave?>" name="clave" <? if ($id_planilla) echo "readonly"?>> 
            </td>
         </tr> 
                  
         <td align="right">
				<b>Tipo de Documento:</b>
			</td>
			<td align="left">			 	
			 <select name=tipo_doc Style="width=257px" <?if ($id_planilla) echo "disabled"?>>
			  <option value=-1>Seleccione</option>
			  <option value=DNI <?if ($tipo_doc=='DNI') echo "selected"?>>Documento Nacional de Identidad</option>
			  <option value=LE <?if ($tipo_doc=='LE') echo "selected"?>>Libreta de Enrolamiento</option>
			  <option value=LC <?if ($tipo_doc=='LC') echo "selected"?>>Libreta Civica</option>
			  <option value=PA <?if ($tipo_doc=='PA') echo "selected"?>>Pasaporte Argentino</option>
			  <option value=CM <?if ($tipo_doc=='CM') echo "selected"?>>Certificado Migratorio</option>
			 </select>
			</td>
         </tr>
         
         <tr>
         	<td align="right">
         	  <b>Apellido:</b>
         	</td>         	
            <td align='left'>
              <input type="text" size="40" value="<?=$apellido?>" name="apellido" <? if ($id_planilla) echo "readonly"?>>
            </td>
         </tr> 
         
         <tr>
         	<td align="right">
         	  <b>Nombre:</b>
         	</td>         	
            <td align='left'>
              <input type="text" size="40" value="<?=$nombre?>" name="nombre" <? if ($id_planilla) echo "readonly"?>>
            </td>
         </tr>          
        <tr>
			<td align="right">
				<b>Fecha de nacimiento:</b>
			</td>
		    <td align="left">
		    	 <? /*$q="select afifechanac from nacer.smiafiliados where id_smiafiliados='$id_smiafiliados'";
		    	 	$res_q=sql($q,"no se pudieron traer los datos");
		    	 	$fecha_nac=$res_q->fields['afifechanac'];*/?>
		    	 <input type=text id="fecha_nac" name="fecha_nac" value='<?=fecha($fecha_nac_trz4);?>' size=15 <? if ($id_planilla) echo "readonly"?>>
		    	 <?=link_calendario("fecha_nac");?>					    	 
		    </td>		    
		</tr>          
		
		
		<tr>
			<td align="right">
				<b>Fecha del Control:</b>
			</td>
		    <td align="left">
		    	<?$fecha_control_trz4=date("d/m/Y");?>
		    	 <input type=text id=fecha_control_trz4 name=fecha_control_trz4 value='<?=$fecha_control_trz4;?>' size=15>
		    	 <?=link_calendario("fecha_control_trz4");?>					    	 
		    </td>		    
		</tr>		
		<tr>
           <td align="right">
         	  <b> Peso:
         	</td> 
           <td >
             <input type='text' name='peso_trz4' value='<?=$peso_trz4;?>' size=60 align='right'></b><font color="Red">Kg (Kilogramos)</font>
           </td>
          </tr>	
          <tr>
           <td align="right">
         	  <b> Talla:
         	</td> 
           <td >
             <input type='text' name='talla_trz4' value='<?=$talla_trz4;?>' size=60 align='right'></b><font color="Red">Centimetros (Cm)</font>
           </td>
          </tr>	
          <tr>
           <td align="right">
         	  <b> Perímetro cefálico:
         	</td> 
           <td >
             <input type='text' name='perimetro_cefalico_trz4' value='<?=$perimetro_cefalico_trz4;?>' size=60 align='right'></b><font color="Red">Cm (Centimentros)</font>
           </td>
          </tr>	
          
          <tr>
           <td align="right">
         	  <b> Percentilo Perímetro Cefálico/Edad:
         	</td> 
            <td align="left">			 	
									 <select name=percentilo_per_cefalico_edad_trz4 Style="width=170px" <?if ($id_planilla) echo "disabled"?>>
									  <option value=-1>Seleccione</option>
									  <option value=1 <?if ($percentilo_per_cefalico_edad_trz4=='1') echo "selected"?>>-3</option>
									  <option value=2 <?if ($percentilo_per_cefalico_edad_trz4=='2') echo "selected"?>>3-97</option>
									  <option value=3 <?if ($percentilo_per_cefalico_edad_trz4=='3') echo "selected"?>>+97</option>
									  <option value='' <?if ($percentilo_per_cefalico_edad_trz4=='') echo "selected"?>>Dato Sin Ingresar</option>			  
									 </select><font color="Red">No Obligatorio</font>
					</td>
          </tr>	
          
		<tr>
    <td align="right">
    <b>Observaciones:</b>
    </td>         	
    <td align='left'>
    <textarea cols='40' rows='4' name='observaciones_trz4' <? if ($id_planilla) echo "readonly"?>><?=$observaciones_trz4;?></textarea>
    </td>
    </tr>              
    </table>
    </td>      
    </tr> 
   

   <?if (!($id_planilla)){?>
	 
	 <tr id="mo">
  		<td align=center colspan="2">
  			<b>Guarda Planilla</b>
  		</td>
  	</tr>  
      <tr align="center">
       <td>
        <input type='submit' name='guardar' value='Guardar Planilla' onclick="return control_nuevos()"
         title="Guardar datos de la Planilla">
       </td>
      </tr>
     
     <?}?>
     
 </table>           
<br>
<?if ($id_planilla){?>
<table class="bordes" align="center" width="100%">
	<tr align="center" id="sub_tabla">
	<td>	
	Guardar DATOS
	</td>
	</tr>
	 
	<tr>
	<td align="center">
	<input type="submit" name="guardar_editar" value="Guardar" title="Guarda Muleto" disabled style="width=130px" onclick="return control_nuevos()">&nbsp;&nbsp;
	<input type="button" name="cancelar_editar" value="Cancelar" title="Cancela Edicion de Muletos" disabled style="width=130px" onclick="document.location.reload()">		      
	</td>
	</tr> 
	</table>	
	<br>
	<?}?>
  
  <tr><td><table width=100% align="center" class="bordes">
  <tr align="center">
  <td>
  <font color="Black" size="3"> <b>En esta pantalla se miden 1 (UNA) TRAZADORA y los datos minimos a cargar por Trazadora son:</b></font>
  </td>
  </tr>
  <tr align="left">
  <td>
  <font size="2">Trazadora IV (SEGUIMIENTO DE SALUD DEL NIÑO MENOR DE 1 AÑO): Campos son todos menos los Percentilos y Observaciones.</font>
  </td>
  </tr>
  
 </table></td></tr>
 
 </table>
 </form>
 
 <?=fin_pagina();// aca termino ?>