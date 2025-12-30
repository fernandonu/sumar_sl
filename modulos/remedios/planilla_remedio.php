<?
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();


if ($_POST['guardar']=="Guardar Comprobante"){
	
		$db->StartTrans();
		$usuario=$_ses_user['name'];
    $fecha_comprobante=Fecha_db($fecha_comprobante);
		$id_beneficiarios=$_POST['id_beneficiarios'];

		$seleccionados = $_POST["chk_producto"] or Error("No se seleccionó ninguna remedio para relacionar");
		$cantidades = $_POST["cantidad_producto"] or Error("No hay cantidades para los productos seleccionados");
		
		$sql_efe="select cuie from remedios.efectores where id_efector='$id_efector'";
		$res_efe=sql($sql_efe) or fin_pagina();
		$cuie=$res_efe->fields['cuie'];

		$q1="select nextval('remedios.id_comprobante') as id_comprobante";
		$id_comprobante_1=sql($q1) or fin_pagina();
		$id_comp=$id_comprobante_1->fields['id_comprobante'];

		$id_cie10=$_POST['id_cie10'];
		$id_cepsap=($_POST['id_cepsap'])?$_POST['id_cepsap']:0;
		
		$dni_medico=$_POST['dni_medico'];
		$nombre_medico=$_POST['nombre_medico'];
		$apellido_medico=$_POST['apellido_medico'];

    $diag_1=$nomenclador." - ".$nomenclador_codigo;
    $diag_2=$nomenclador_1." - ".$nomenclador_codigo_1;
    
		$q_comprobante="INSERT INTO remedios.comprobantes (id_comprobante,id_beneficiarios,usuario,
			cuie,diag_1,diag_2,dni_medico,nombre_medico,apellido_medico,fecha_entrega)
			values ($id_comp,$id_beneficiarios,'$usuario',
			'$cuie','$diag_1','$diag_2','$dni_medico','$nombre_medico','$apellido_medico','$fecha_comprobante')";
		$res_comprobante=sql($q_comprobante,"No se pudieron ingresar los datos a la tabla comprobantes") or fin_pagina();

		$i=0;    
		foreach ($seleccionados as $remedio) {
		    
			   $q="select nextval('remedios.id_prestacion') as id_prestacion";
		   	 $id_comprobante=sql($q) or fin_pagina();
		   	 $id_prestacion=$id_comprobante->fields['id_prestacion'];

		   	$string_remedio = explode ("|",$remedio);
		   	$id_remedio = $string_remedio[0];

		   	//$i=$id_remedio-1;
        
		   	$cantidad_producto=$cantidades[$i];
		   	$stock = $string_remedio[1]-$cantidad_producto;
			  $sql_array[] = "INSERT INTO remedios.prestaciones (id_prestacion,id_remedio,cantidad,id_comprobante)
				values ($id_prestacion,$id_remedio,$cantidad_producto,$id_comp)";
   
        $sql_array2[] = "UPDATE remedios.stock_producto SET 
                       u_entregadas=u_entregadas + $cantidad_producto,
                       total_2=total_2+$cantidad_producto,
                       --final=total_1-(u_entregadas + $cantidad_producto)-total_2 
                       final=total_1-total_2-$cantidad_producto
                       where id_efector=$id_efector and id_remedio=$id_remedio";
        $i++;

			}

		   $result = sql($sql_array) or fin_pagina();
		   $result2 = sql($sql_array2) or fin_pagina();
			    
		    $accion="El/los remedio/s han sido asignados";
		    		    
		    $db->CompleteTrans();
		    echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";

		           
}//de if ($_POST['guardar']=="Guardar Comprobante")

 
$sql="select * from uad.beneficiarios where id_beneficiarios='$id_beneficiarios'";
$res_benef=sql($sql, "Error al traer los datos del beneficiario") or fin_pagina();

$apellido=$res_benef->fields['apellido_benef'];
$nombre=$res_benef->fields['nombre_benef'];
$dni=$res_benef->fields['numero_doc'];
$fechanac=$res_benef->fields['fecha_nacimiento_benef'];
$sexo=$res_benef->fields['sexo'];
$id_efector=$_POST['id_efector'];

echo $html_header;

$id_cie10 = "";
$codigo_cie10 = "";
if (!empty($parametros["nuevo_id_cie10"]) && intval($parametros["nuevo_id_cie10"]) > 0) {
  $id_cie10 = intval($parametros["nuevo_id_cie10"]);
  $_ses_nomenclador["id_cie10"] = $id_cie10;
  phpss_svars_set("_ses_nomenclador", $_ses_nomenclador);
}
elseif (!empty($_ses_nomenclador["id_cie10"])) {
  $id_cie10 = $_ses_nomenclador["id_cie10"];
}
// echo "id=$id_cie10";
if (!empty($id_cie10)) {
  $query = "SELECT 
              *
            FROM
              nacer.cie10
            WHERE
              id10 = $id_cie10";
  $res_cie10 = sql($query, "al obtener los datos del Nomenclador") or fin_pagina();
  $codigo_cie10 = $res_paciente->fields["dec10"];
}?>

<script type="text/javascript">
$(document).ready(function(){

//codigo nuevo
$("#nomenclador_cie10, #nomenclador_cepsap").on('change', function() {
$('#nomenclador_label').html($(this).val());
$('#nomenclador_codigo').prop('placeholder', 'Ingrese el código o descripción de '+$(this).val());
$('#nomenclador_id').val('');
$('#nomenclador_codigo').val('');

nomenclador_autocomplete();
});

$("#nomenclador_cie10_1, #nomenclador_cepsap_1").on('change', function() {
$('#nomenclador_label_1').html($(this).val());
$('#nomenclador_codigo_1').prop('placeholder', 'Ingrese el código o descripción de '+$(this).val());
$('#nomenclador_id_1').val('');
$('#nomenclador_codigo_1').val('');

nomenclador_autocomplete();
});

//fin codigo nuevo

 $("body").on("click", "#tabla_remedio input[type=checkbox]", function() {
    i = $(this).val().split ("|");
    if (i.length==2){
    id = i[0];
   $("#cantidad_producto_"+id).prop("disabled", !$(this).is(":checked"));
 }
 else alert("Error de datos");
    });

 
  // Parametros para el select con id_efector como id
  $("#id_efector").on("change", function () {
      
      $("#id_efector option:selected").each(function () {
      //alert($(this).val());
        elegido=$(this).val();
        $.ajax({
            data: { elegido: elegido },
            type: "POST",
            dataType: "text",
            url: "carga_diagnostico.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
               $("#tabla_remedio").html(data);
               $('[data-toggle="tooltip"]').tooltip();
               //console.log(data);
          }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado: " +  textStatus);
          }
      });//fail

        });//each
      })//change
  
    /*$( "#codigo_cie10" ).autocomplete({
      source: '<?php echo encode_link("carga_diagnostico.php", array("accion" => "cie10_autocomplete")); ?>',
      minLength: 2,
      select: function( event, ui ) {
        if (ui.item) {
          $('#id_cie10').val(ui.item.id);
        }
        else {
          $('#codigo_cie10').val("");
        }
      }
    });

    $( "#codigo_cepsap" ).autocomplete({
      source: '<?php echo encode_link("carga_diagnostico.php", array("accion" => "cepsap_autocomplete")); ?>',
      minLength: 2,
      select: function( event, ui ) {
        if (ui.item) {
          $('#id_cepsap').val(ui.item.id);
        }
        else {
          $('#codigo_cepsap').val("");
        }
      }
    });*/

    $("#form1").submit(function(event) {
      if ($("#nomenclador_codigo").val() == '' && $("#nomenclador_codigo_1").val() == '') {
        event.preventDefault();
        BootstrapDialog.alert("Falta seleccionar el c&oacute;digo del diagn&oacute;stico, complete alguno de los campos de nomenclador (CIE10 o CEPSAP)");
      }
    });

  $('[data-toggle="tooltip"]').tooltip();  
  });

var url_nomenclador_cie10 = '<?php echo encode_link("cargar_diagnostico_datos_1.php", array("accion" => "cie10_autocomplete")); ?>';
var url_nomenclador_cepsap = '<?php echo encode_link("cargar_diagnostico_datos_1.php", array("accion" => "cepsap_autocomplete")); ?>';

var prescripcion_count = 0;

function nomenclador_autocomplete() {
   var url = '';
   if ($("#nomenclador_cie10").is(":checked") || $("#nomenclador_cie10_1").is(":checked")) {
      url = url_nomenclador_cie10;
    }
    else if ($("#nomenclador_cepsap").is(":checked") || $("#nomenclador_cepsap_1").is(":checked")) {
      url = url_nomenclador_cepsap;
    }
    $( "#nomenclador_codigo" ).autocomplete({
      source: url,
      minLength: 2,
      select: function( event, ui ) {
        if (ui.item) {
          $('#nomenclador_id').val(ui.item.id);
                            
        }
        else {
          $('#nomenclador_codigo').val("");
        }
      }
    });

    $( "#nomenclador_codigo_1" ).autocomplete({
      source: url,
      minLength: 2,
      select: function( event, ui ) {
        if (ui.item) {
          $('#nomenclador_id_1').val(ui.item.id);
                            
        }
        else {
          $('#nomenclador_codigo_1').val("");
        }
      }
    });
  }

//controlan que ingresen todos los datos necesarios para la factura
function control_nuevos()
{
 if(document.all.id_efector.value=="-1"){
  alert('Debe Seleccionar un EFECTOR');
  document.all.id_efector.focus();
  return false;
 }

 if(document.all.nombre_medico.value==""){
  alert('Debe Seleccionar un Medico');
  document.all.nombre_medico.focus();
  return false;
 }

 if(document.all.apellido_medico.value==""){
  alert('Debe Seleccionar un Medico');
  document.all.apellido_medico.focus();
  return false;
 }

 if(document.all.dni_medico.value==""){
  alert('Debe Seleccionar un Medico');
  document.all.dni_medico.focus();
  return false;
 }

if(document.all.nomenclador_codigo.value=="" && document.all.nomenclador_codigo_1.value==""){
  alert('Debe Seleccionar al menos un Diagnostico');
  document.all.nomenclador_codigo.focus();
  return false;
 }

$("#tabla_remedio input:checked").each(function() {
  i = $(this).val().split ("|");
  if (i.length==2){
    id = i[0];
    stock = parseInt(i[1]);
    cantidad = parseInt($("#cantidad_producto_"+id).val());
    //alert(id+" - "+cantidad+" - "+stock);
    if(stock<=0 || cantidad>stock){
      alert("El valor de cantidad ingresado supera el stock actual"+" - "+cantidad+" - "+stock);
      $("#cantidad_producto_"+id).focus();
      j=1;
      return false;
      }else j=0;
  }
  else {alert("Error de datos");
        return false;}
 });

if ((j==0) && (confirm('Esta Seguro que Desea Agregar Comprobante?')))return true;
        else return false;
}//de function control_nuevos()


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

<form name='form1' action='planilla_remedio.php' method='POST' enctype='multipart/form-data'>

<!--<input type="hidden" name="id_efector" value="<?=$id_efector?>">-->
<input type="hidden" name="id_pagina" value="<?=$id_pagina?>">
<input type="hidden" name="id_beneficiarios" value="<?=$id_beneficiarios?>">



<?echo "<center><b><font size='+2' color='red'>$accion</font></b></center>";
?>
<hr>

<table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
     <font size=+1><b>Formulario de Entrega de Medicamento <?if ($pagina_listado=='planilla_remedio.php') echo "<font color=red>Verificando HISTORICOS </font>";?></b></font>    
    </td>
 </tr>
 <tr><td>
  <table class="bordes" align="center" width="70%" bordercolor=#E0E0E0 border="solid 1px ">
     <tr>
      <td id="mo" colspan="2">
       <b> Descripci&oacute;n del Beneficiario</b>
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
              <input type='text' name='apellido' value='<?=$apellido;?>' size=60 align='right' readonly></b>
            </td>
         </tr>
         <tr>
            <td align="right">
         	  <b> Nombre:
         	</td>   
           <td  colspan="2">
             <input type='text' name='nombre' value='<?=$nombre;?>' size=60 align='right' readonly></b>
           </td>
          </tr>
          <tr>
           <td align="right">
         	  <b> Documento:
         	</td> 
           <td colspan="2">
             <input type='text' name='dni' value='<?=$dni;?>' size=60 align='right' readonly></b>
           </td>
          </tr>
           <tr>
           <td align="right">
         	  <b> Fecha de Nacimiento:
         	</td> 
           <td colspan="2">
             <input type='text' name='fechanac' value='<?=fecha($fechanac);?>' size=60 align='right' readonly></b>
           </td>
          </tr>
                    
          </table>
      </td>      
     </tr>
   </table>
   &nbsp;&nbsp;&nbsp;

<table class="bordes" align="center" width="70%" bordercolor=#E0E0E0 border="solid 1px ">
		 <tr align="center" id="sub_tabla">
		 	<td colspan="2">	
		 		<b>Datos del Medico </b>
		 		<button style="font-size:9px;" onclick="window.open('busca_medico.php','Buscar','dependent:yes,width=900,height=700,top=1,left=60,scrollbars=yes');return false;">Buscar</button>

		 	</td>
		 </tr>
		 <tr><td class="bordes"><table>
			 <tr>
              </tr>
                    <tr>
                    	<input type="hidden" size="30" value="<?= $id_medico ?>" name="id_medico" id="id_medico" maxlength="50" >
                        <td align="left" style="padding-left:10px"><b>Apellido:</b> </td>
                        <td align="right"><input type="text" size="27" value="<?= $apellido_medico ?>" name="apellido_medico" id="apellido_medico" maxlength="50" ></td>        
                        <td align="left" style="padding-left:10px"><b>Nombre:</b> </td>    
                        <td align="right"><input type="text" size="27" value="<?= $nombre_medico ?>" name="nombre_medico" id="nombre_medico" maxlength="50" >
                        <td align="left" style="padding-left:10px"><b>DNI:</b></td>
                        <td align="right"><input type="text" size="16" value="<?= $dni_medico ?>" name="dni_medico" id="dni_medico" maxlength="12" > </td>          
                     </tr>
		 </table>
	</table>


   &nbsp;&nbsp;&nbsp;    
	<table class="bordes" align="center" width="70%" bordercolor=#E0E0E0 border="solid 1px ">
		 <tr align="center" id="sub_tabla">
		 	<td colspan="2">	
		 		<b>Datos del Efector </b>
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
				 		
            <? if (!$id_efector){?>
            <select name="id_efector" id="id_efector" Style="width=450px">
				
			 <?
			 $sql= "select * from remedios.efectores order by nombre";
			 echo "<option value=-1>Seleccione</option>";
			 		  		  		   
			 $res_efectores=sql($sql) or fin_pagina();
			 while (!$res_efectores->EOF){ 
			 	$id_efector=$res_efectores->fields['id_efector'];
			    $nombre_efector=$res_efectores->fields['nombre'];
			    $cuie=$res_efectores->fields['cuie'];
				?>
				<option value=<?=$id_efector;?> Style="background-color: <?=$color_style?>;"><?=$cuie." - ".$nombre_efector?></option>
			    <?
			    $res_efectores->movenext();
			    };
          $id_efector=0;?>
		  	</select>
        <?}
        else {
            $sql= "select * from remedios.efectores where id_efector=$id_efector";
            $res_efectores=sql($sql) or fin_pagina();
            $id_efector=$res_efectores->fields['id_efector'];
            $nombre_efector=$res_efectores->fields['nombre'];
            $cuie=$res_efectores->fields['cuie'];?>

          <b><input type='text' name='efector' value='<?=$cuie." - ".$nombre_efector?>' size=60 align='right' readonly></b>
          <input type='hidden' name='id_efector' value='<?=$id_efector?>'>


          <?}?>

			 </tr>
			 <tr>
					 	<td align="right">
					    	<b>Fecha de Entrega de Medicamento:</b>
					    </td>
					    <td align="left">
					    						    	
					    	<?$fecha_comprobante=date("d/m/Y");?>
					    	 <input type=text id=fecha_comprobante name=fecha_comprobante value='<?=$fecha_comprobante;?>' size=15 readonly>
					    	 <?=link_calendario("fecha_comprobante");?>					    	 
					    </td>		    
					 </tr> 	
				</td>
			 </tr>
		 </table>
	
&nbsp;&nbsp;&nbsp;
<br/>

            <div class="col-sm-12">
             <b>Diagnostico</b>
             <div class="row">
             <div class="col-sm-3">
             <div class="btn-group btn-group-justified btn-group-sm" role="group" data-toggle="buttons">
             <label class="btn btn-primary<?php echo ($nomenclador == "CEPSAP") ? ' active"' : ''; ?>">
             <input type="radio" id="nomenclador_cepsap" name="nomenclador" autocomplete="off" value="CEPSAP"<?php echo ($nomenclador == "CEPSAP") ? ' checked="checked"' : ''; ?> /> CEPSAP
             </label> 
             <label class="btn btn-primary<?php echo ($nomenclador == "CIE10") ? ' active"' : ''; ?>">
             <input type="radio" id="nomenclador_cie10" name="nomenclador" autocomplete="off" value="CIE10"<?php echo ($nomenclador == "CIE10") ? ' checked="checked"' : ''; ?> /> CIE10
           </label> 
        </div>
      </div>
      <div class="col-sm-9">
      <input type="hidden" name="nomenclador_id" id="nomenclador_id" value="<?php echo $nomenclador_id; ?>" />
      <input type="text" name="nomenclador_codigo" class="form-control" id="nomenclador_codigo" placeholder="Ingrese el c&oacute;digo o descripci&oacute;n" value="<?php echo $nomenclador_codigo; ?>">
  </div>

  &nbsp;&nbsp;&nbsp;
<br/>

 <div class="col-sm-12">
             <div class="row">
             <div class="col-sm-3">
             <div class="btn-group btn-group-justified btn-group-sm" role="group" data-toggle="buttons">
             <label class="btn btn-primary<?php echo ($nomenclador == "CEPSAP") ? ' active"' : ''; ?>">
             <input type="radio" id="nomenclador_cepsap_1" name="nomenclador_1" autocomplete="off" value="CEPSAP"<?php echo ($nomenclador_1 == "CEPSAP") ? ' checked="checked"' : ''; ?> /> CEPSAP
             </label> 
             <label class="btn btn-primary<?php echo ($nomenclador == "CIE10") ? ' active"' : ''; ?>">
             <input type="radio" id="nomenclador_cie10_1" name="nomenclador_1" autocomplete="off" value="CIE10"<?php echo ($nomenclador_1 == "CIE10") ? ' checked="checked"' : ''; ?> /> CIE10
           </label> 
        </div>
      </div>
      <div class="col-sm-9">
      <input type="hidden" name="nomenclador_id_1" id="nomenclador_id_1" value="<?php echo $nomenclador_id_1; ?>" />
      <input type="text" name="nomenclador_codigo_1" class="form-control" id="nomenclador_codigo_1" placeholder="Ingrese el c&oacute;digo o descripci&oacute;n" value="<?php echo $nomenclador_codigo_1; ?>">
  </div>

</table>
<br/>
&nbsp;&nbsp;&nbsp;
<table class="bordes" align="center" width="86%" bordercolor=#E0E0E0 border="solid 1px ">
		 <tr align="center" id="sub_tabla">
		 <td colspan="2">	
		 		Medicamentos 
		 </td>
		 </tr>
     <tr id="tabla_remedio">
     <td align="center"><h3>Seleccione un efector</h3></td>
</tr>	



</table>
<br/>
<table width=100% align="center" class="bordes">
  <tr align="center">
		 <td align="center" colspan="2" class="bordes">		      
		 <input type="submit" name="guardar" value="Guardar Comprobante" title="Guardar Comprobante" Style="width=250px;height=30px" onclick="return control_nuevos()">
		   	</td>
		 </tr> 
	 </table>
   <br/>	
<table width=100% align="center" class="bordes">
  <tr align="center">
   <td>
   	 <input type=button name="volver" value="Volver" onclick="window.close()"title="Volver al Listado" style="width=150px">     
   	 </td>
  </tr>
 </table>
	<br>
</form>
<?=fin_pagina();// aca termino ?>