<?php
require_once("../../config.php");



$datos_barra = array( 
          array(
              "descripcion" => "Nomenclador 2024",
              "cmd"     => 22
              ),
          array(
                "descripcion" => "Nomenclador 2025",
                "cmd"     => 23
                ),
          array(
          "descripcion" => "PPAC 2021",
          "cmd"     => 112
          )    
           );
echo "<br>";

$orden = array(
        "default" => "1",
        "1" => "nomenclador.codigo",
        "2" => "cant_pres_lim",
        "3" => "per_pres_limite",
        "4" => "msg_error",
        "5"=> "nomenclador.descripcion",
        "6"=>"nomenclador_detalle.descripcion"
       );
$filtro = array(
		"nomenclador.codigo" => "Codigo",
    "nomenclador.descripcion"=> "Descripcion",
    "nomenclador_detalle.descripcion"=>"Descripcion Nomenclador"
           );




echo $html_header;
variables_form_busqueda("listado_validar_prest");
?>
<form name=form1 action="listado_validar_prest.php" method=POST>
<?php generar_barra_nav($datos_barra);?>

<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?
    if (!$cmd or $cmd=='') $cmd=23;
    $sql_tmp="SELECT nomenclador.*,
  nomenclador_detalle.descripcion as descripcion_nomen,
  validacion_prestacion.codigo as siglas,
  validacion_prestacion.cant_pres_lim,
  validacion_prestacion.per_pres_limite,
  validacion_prestacion.msg_error,
  validacion_prestacion.id_val_pres
  from facturacion.nomenclador
  inner join facturacion.nomenclador_detalle on nomenclador.id_nomenclador_detalle=nomenclador_detalle.id_nomenclador_detalle
  left join facturacion.validacion_prestacion on validacion_prestacion.id_nomenclador=nomenclador.id_nomenclador";

$where_tmp="nomenclador.id_nomenclador_detalle='$cmd'";
    list($sql,$total_validacion,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
	    &nbsp;&nbsp;<input type='button' name="nuevo" value='Nuevo' onclick="document.location='validar_prest_admin.php'">
	  </td>
     </tr>
</table>

<?php 


$result = sql($sql,"No se ejecuto en la consulta principal") or die;?>

<!--<table border=0 width=85% cellspacing=2 cellpadding=2 bgcolor='<?php echo $bgcolor3?>' align=center>-->
<table width=85% class="table table-striped"  align=center>  
  <tr>
  	<td colspan=12 align=left id="ma">
     <table width=85%>
      <tr id="ma">
       <td width=30% align=left><b>Total:</b> <?php echo $total_validacion?></td>       
       <td width=40% align=right><?php echo $link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  <tr>
  <td align=right id=mo><a id=mo href='<?php echo encode_link("listado_validar_prest.php",array("sort"=>"1","up"=>$up))?>' >Codigo</a></td>      	
  <td align=right id=mo><a id=mo href='<?php echo encode_link("listado_validar_prest.php",array("sort"=>"5","up"=>$up))?>' >Descripcion</a></td>      	
	<td align=right id=mo><a id=mo href='<?php echo encode_link("listado_validar_prest.php",array("sort"=>"5","up"=>$up))?>' >Nomenclador</a></td>   
	<td align=right id=mo><a id=mo href='<?php echo encode_link("listado_validar_prest.php",array("sort"=>"2","up"=>$up))?>' >Cant. Prestaciones</a></td>   
	<td align=right id=mo><a id=mo href='<?php echo encode_link("listado_validar_prest.php",array("sort"=>"3","up"=>$up))?>' >Periodo Limite</a></td>   
	<td align=right id=mo><a id=mo href='<?php echo encode_link("listado_validar_prest.php",array("sort"=>"4","up"=>$up))?>' >Mensaje de error</a></td>              
  </tr>
  <?
   while (!$result->EOF) {
   		$ref = encode_link("validar_prest_admin.php",array(
        "id_val_pres"=>$result->fields['id_val_pres'],"id_nomenclador"=>$result->fields['id_nomenclador'],"pagina"=>"listado_validar_prest"));
   		$onclick_elegir="location.href='$ref'";
   	?>
  
    <tr>     
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['codigo']?></td>
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['descripcion']?></td>
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['descripcion_nomen']?></td>
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['cant_pres_lim']?></td>
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['per_pres_limite']?></td>
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['msg_error']?></td>
    </tr>    
	<?php $result->MoveNext();
    }?>
  	
</table>
</form>
</body>
</html>

<?php echo fin_pagina();// aca termino ?>
