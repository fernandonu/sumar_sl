<?
require_once ("../../config.php");
require_once ("funciones_prog_sexual.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();



if ($_POST['guardar']=="Guardar Comprobante"){
	
		$db->StartTrans();
		  
    $usuario=$_ses_user['name'];
    $fecha_comprobante=Fecha_db($fecha_comprobante);
		$id=$_POST['id'];
    $ingresa_programa=($_POST['ingresa_programa'])?'S':'N';
    
    
		$seleccionados = $_POST["chk_producto"] or Error("No se seleccionó ninguna remedio para relacionar");
		$cantidades = $_POST["cantidad_producto"] or Error("No hay cantidades para los productos seleccionados");
		
		$sql_efe="select cuie from nacer.efe_conv where id_efe_conv='$id_efector'";
		$res_efe=sql($sql_efe) or fin_pagina();
		$cuie=$res_efe->fields['cuie'];

		$q1="select nextval('programa_sexual.comprobantes_id_comprobante_seq') as id_comprobante";
		$id_comprobante_1=sql($q1) or fin_pagina();
		$id_comp=$id_comprobante_1->fields['id_comprobante'];

    
    if ($entidad_alta=='na'){
        $q_comprobante="INSERT INTO programa_sexual.comprobantes (id_comprobante,id_smiafiliados,usuario,
    			cuie,fecha_entrega,ingresa_programa)
    			values ($id_comp,$id,'$usuario',
    			'$cuie','$fecha_comprobante','$ingresa_programa')";
    	}
    else {
      $q_comprobante="INSERT INTO programa_sexual.comprobantes (id_comprobante,id_beneficiarios,usuario,
          cuie,fecha_entrega,ingresa_programa)
          values ($id_comp,$id,'$usuario',
          '$cuie','$fecha_comprobante','$ingresa_programa')";
    };    
    
    $res_comprobante=sql($q_comprobante,"No se pudieron ingresar los datos a la tabla comprobantes") or fin_pagina();

		$i=0;    
		foreach ($seleccionados as $remedio) {
		    
			   $q="select nextval('programa_sexual.prestaciones_id_prestacion_seq') as id_prestacion";
		   	 $id_comprobante=sql($q) or fin_pagina();
		   	 $id_prestacion=$id_comprobante->fields['id_prestacion'];

		   	$string_remedio = explode ("|",$remedio);
		   	$id_remedio = $string_remedio[0];

		   	//$i=$id_remedio-1;
        
		   	$cantidad_producto=$cantidades[$i];
		   	$stock = $string_remedio[1]-$cantidad_producto;
			  $sql_array = "INSERT INTO programa_sexual.prestaciones (id_prestacion,id_remedio,cantidad,id_comprobante)
				values ($id_prestacion,$id_remedio,$cantidad_producto,$id_comp)";
   
        $sql_array2 = "UPDATE programa_sexual.stock_producto SET 
                       u_entregadas=u_entregadas + $cantidad_producto,
                       total_2=total_2+$cantidad_producto,
                       --final=total_1-(u_entregadas + $cantidad_producto)-total_2 
                       final=total_1-total_2-$cantidad_producto
                       where id_efector=$id_efector and id_remedio=$id_remedio";
        
        $datos = array (
        "entidad_alta"=>$entidad_alta,
        "ID"=>$id,
        "fecha_control"=>$fecha_comprobante,
        "id_remedio"=>$id_remedio
        );
        
       $q="SELECT * from programa_sexual.remedio where id_remedio=$id_remedio";
             $res_q=sql($q) or fin_pagina();
             $codigo=$res_q->fields['codigo'];
       
       if (valida_producto($datos)) {
             $result = sql($sql_array) or fin_pagina();
             $result2 = sql($sql_array2) or fin_pagina();
             $accion.="El producto: ".$codigo." ha sido asignado al comprobante Nº ".$id_comp."<br>";
          }
        else $accion.="El producto: ".$codigo." NO han sido registrado<br>";
            $i++;

			}
        
        
      $db->CompleteTrans();
		  echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";

		           
}//de if ($_POST['guardar']=="Guardar Comprobante")

 
if ($entidad_alta=='na'){
  $sql="select *,
        nacer.smiafiliados.id_smiafiliados as id,
        nacer.smiafiliados.afiapellido as a,
        nacer.smiafiliados.afinombre as b,
        nacer.smiafiliados.afidni as c,
        nacer.smiafiliados.afifechanac as d,
        nacer.smiafiliados.afidomlocalidad as e
       from nacer.smiafiliados   
     where id_smiafiliados=$id";

}
else{
  $sql="select *,
        leche.beneficiarios.id_beneficiarios as id,
        leche.beneficiarios.apellido as a,
        leche.beneficiarios.nombre as b,
        leche.beneficiarios.documento as c,
        leche.beneficiarios.fecha_nac as d,
        leche.beneficiarios.domicilio as e
     from leche.beneficiarios  
     where id_beneficiarios=$id";
}

$res_benef=sql($sql, "Error al traer los datos del beneficiario") or fin_pagina();

$apellido=$res_benef->fields['a'];
$nombre=$res_benef->fields['b'];
$dni=$res_benef->fields['c'];
$fechanac=$res_benef->fields['d'];
$sexo=$res_benef->fields['sexo'];
$id_efector=$_POST['id_efector'];

echo $html_header;


?>

<script type="text/javascript">
$(document).ready(function(){


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
        console.log(elegido);
        op="para_dispensar";
        $.ajax({
            data: { elegido: elegido ,op:op},
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
  
    
  $('[data-toggle="tooltip"]').tooltip();  
  });


//controlan que ingresen todos los datos necesarios para la factura
function control_nuevos()
{
 if(document.all.id_efector.value=="-1"){
  alert('Debe Seleccionar un EFECTOR');
  document.all.id_efector.focus();
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

<!--<link href="style.css" rel="stylesheet" type="text/css">-->

<form name='form1' action='planilla_prog_sexual.php' method='POST' enctype='multipart/form-data'>

<!--<input type="hidden" name="id_efector" value="<?=$id_efector?>">-->
<input type="hidden" name="id_pagina" value="<?=$id_pagina?>">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="entidad_alta" value="<?=$entidad_alta?>">
<?
echo "<center><b><font size='+2' color='red'>$accion</font></b></center>";
?>
<hr>

<table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
     <font size=+1><b>Formulario de Entrega de Productos <?if ($pagina_listado=='planilla_remedio.php') echo "<font color=red>Verificando HISTORICOS </font>";?></b></font>    
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
		 
<tr><td class="bordes">
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
			 $sql= "SELECT * from nacer.efe_conv order by nombre";
			 echo "<option value=-1>Seleccione</option>";
			 		  		  		   
			 $res_efectores=sql($sql) or fin_pagina();
			 while (!$res_efectores->EOF){ 
			 	$id_efector=$res_efectores->fields['id_efe_conv'];
			    $nombre_efector=$res_efectores->fields['nombre'];
			    $cuie=$res_efectores->fields['cuie'];
				?>
				<option value=<?=$id_efector;?> Style="background-color: <?=$color_style?>;"><?=$cuie." - ".$nombre_efector?></option>
			    <?
			    $res_efectores->movenext();
			    };
          $id_efector=0;
          ?>
		  	</select>
        <?}
        else {
            $sql= "select * from nacer.efe_conv where id_efe_conv=$id_efector";
            $res_efectores=sql($sql) or fin_pagina();
            $id_efector=$res_efectores->fields['id_efe_conv'];
            $nombre_efector=$res_efectores->fields['nombre'];
            $cuie=$res_efectores->fields['cuie'];?>

          <b><input type='text' name='efector' value='<?=$cuie." - ".$nombre_efector?>' size=60 align='right' readonly></b>
          <input type='hidden' name='id_efector' value='<?=$id_efector?>'>


          <?}?>

			 </tr>
			  <tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
       <tr>
					 	<td align="right">
					    	<b>Fecha de Comprobante:</b>
					    </td>
					    <td align="left">
					    						    	
					    	<?$fecha_comprobante=date("d/m/Y");?>
					    	 <input type=text id=fecha_comprobante name=fecha_comprobante value='<?=$fecha_comprobante;?>' size=15 readonly>
					    	 <?=link_calendario("fecha_comprobante");?>					    	 
					    </td>		    
					 </tr> 	

           <tr>
            <td align="right">
                <b>Ingresa al Programa (es nueva?):</b>
              </td>
              <td align="left">
              <input type=checkbox id='ingresa_programa' name='ingresa_programa'>
              </td>       
           </tr>  

				</td>
			 </tr>
		 </table>
	
&nbsp;&nbsp;&nbsp;
<br/>

            

  &nbsp;&nbsp;&nbsp;
<br/>

 

</table>
<br/>
&nbsp;&nbsp;&nbsp;
<table class="bordes" align="center" width="86%" bordercolor=#E0E0E0 border="solid 1px ">
		 <tr align="center" id="sub_tabla">
		 <td colspan="2">	
		 		Productos - Prog. Salud Sexual y Reproductiva 
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
		 
     <?$dato=$id.'/'.$entidad_alta;?>
      <button type="button" name="ver_prestaciones" class="btn btn-info" data-toggle="modal" data-target="#myModal_timeline" value="<?=$dato?>">Ver Prestaciones</button>
      
     <button type="submit" class="btn btn-warning" name="guardar" value="Guardar Comprobante" title="Guardar Comprobante" onclick="return control_nuevos()">Guardar Comprobante</button>
		   	
      
        
      </td>
		 </tr> 
	 </table>
   <br/>	
<table width=100% align="center" class="bordes">
  <tr align="center">
   <td>
   	 <butotn type=button  class="btn btn-primary" name="volver" value="Volver" onclick="document.location='../entrega_leche/listado_beneficiarios_leche.php'" title="Volver al Listado" style="width=150px">Volver</butotn>     
   	 </td>
  </tr>
 </table>
	<br>
</form>
<?include("modal_prog_sexual.php");?>
<?=fin_pagina();// aca termino ?>