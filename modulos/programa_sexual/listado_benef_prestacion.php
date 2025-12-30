<?php
require_once("../../config.php");

Header('Content-Type: text/html; charset=LATIN1');

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

variables_form_busqueda("listado_medicamentos");
$usuario1=$_ses_user['id'];
$user_name=$_ses_user['name'];
$orden = array(
        "default" => "1",
        "1" => "numero_doc",
		    "2" => "apellido_benef", 
        "3" => "nombre_benef",  
       );
$filtro = array(		
		"numero_doc" => "DNI",		
		"apellido_benef" => "Apellido",
		"nombre_benef" => "Nombre",
		);
       
$sql_tmp="SELECT distinct on (beneficiarios.numero_doc)
  beneficiarios.id_beneficiarios,
  beneficiarios.numero_doc,
  beneficiarios.apellido_benef,
  beneficiarios.nombre_benef,
  beneficiarios.sexo,
  beneficiarios.fecha_nacimiento_benef,
  comprobantes.fecha_entrega,
  comprobantes.usuario,
  comprobantes.cuie,
  efectores.nombre as efector
  
 from remedios.comprobantes
inner join uad.beneficiarios on comprobantes.id_beneficiarios=beneficiarios.id_beneficiarios
inner join remedios.efectores on efectores.cuie=comprobantes.cuie";

$fecha_desde=fecha_db($_POST['fecha_desde']);
$fecha_hasta=fecha_db($_POST['fecha_hasta']);

if ($fecha_desde!="" && $fecha_hasta!=""){
    $where_tmp="comprobantes.fecha_entrega between '$fecha_desde' and '$fecha_hasta'";  
  }


echo $html_header;?>

<script>

</script>

<form name=form1 action="listado_benef_prestacion.php" method=POST>
<table cellspacing=3 cellpadding=3 border=0 width=100% align="center">
    <tr>
    <td align="center">
		<b>Desde: </b><input type=text id="fecha_desde" name="fecha_desde" value='<?=fecha($fecha_desde)?>' size=15 readonly>
    <?=link_calendario("fecha_desde");?>
    
    <b>Hasta: </b><input type=text id="fecha_hasta" name="fecha_hasta" value='<?=fecha($fecha_hasta)?>' size=15 readonly>
    <?=link_calendario("fecha_hasta");?> 
    
    <?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	   &nbsp;&nbsp;
     <input type=submit name="buscar" value='Buscar'>	    
    </td>
    </tr>
</table>

<?$result = sql($sql) or die;?>

<table border=0 width=100% cellspacing=2 cellpadding=2 align="center">
  <tr>
  	<td colspan=10 align="left">
     <table width=100%>
      <tr>
       <td width=30% align="left"><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align="right"><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  
  <table  width=80% class="table table-striped"  border="1">
  <tr>
  	<td align="right" id="mo">DNI</td>    	    
    <td align="right" id="mo">Apellido</a></td>      	    
    <td align="right" id="mo">Nombre</a></td>      	    
    <td align="right" id="mo">Sexo</a></td>
    <td align="right" id="mo">Fecha Nacimiento</td>
    <td align="right" id="mo">Fecha Entrega</td>
    <td align="right" id="mo">Usuario</td>
    <td align="right" id="mo">Cuie</td>
    <td align="right" id="mo">Efector</td>

    </tr>
 <?
   while (!$result->EOF) {?>
   
    <tr <?=atrib_tr()?>> 
    <?$ref = encode_link("listado_benef_prestacion_detalle.php",array("id_beneficiarios"=>$result->fields['id_beneficiarios']));
    $onclick_elegir="location.href='$ref'";?>    
     <td onclick="<?=$onclick_elegir?>" align="center"><?=$result->fields['numero_doc']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['apellido_benef']?></td>        
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre_benef']?></td>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['sexo']?></td>  
     <td onclick="<?=$onclick_elegir?>"><?=Fecha($result->fields['fecha_nacimiento_benef'])?></td>   
     <td onclick="<?=$onclick_elegir?>"><?=Fecha($result->fields['fecha_entrega'])?></td> 
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['usuario']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['cuie']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['efector']?></td>
   </tr>
	<?$result->MoveNext();
    }?>
    </table>
    
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
