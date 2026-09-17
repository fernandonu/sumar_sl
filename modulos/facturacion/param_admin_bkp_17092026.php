<?php
require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
if ($_GET['id_nomenclador_detalle']) $id_nomenclador_detalle = $_GET['id_nomenclador_detalle'];

$extras = array("id_nomenclador_detalle" => $id_nomenclador_detalle);
variables_form_busqueda("param_admin", $extras);

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

$orden = array(
        "default" => "4",
        "1" => "id_nomenclador",
        "2" => "id_nomenclador_detalle",
        "3" => "descripcion",
        "4" => "codigo",
        "5" => "grupo",
        "6" => "subgrupo",
        "7" => "precio",
        "8" => "activo",
       );
$filtro = array(
        "codigo" => "Codigo",
        "id_nomenclador_detalle::text" => "Id Nom. Detalle",
        "descripcion" => "Descripcion",
        "grupo" => "Grupo",
        "subgrupo" => "Subgrupo",
        "activo::text" => "Activo",
       );

$sql_tmp="select * from facturacion.nomenclador";
$where_tmp = ($id_nomenclador_detalle != "") ? "id_nomenclador_detalle=$id_nomenclador_detalle" : "";
$link_tmp = array("id_nomenclador_detalle" => $id_nomenclador_detalle);

echo $html_header;
?>
<form name=form1 action="param_admin.php" method=POST>
<input type="hidden" name="id_nomenclador_detalle" value="<?php echo $id_nomenclador_detalle?>">
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
	  </td>
     </tr>
</table>

<?php $result = sql($sql) or die;?>

<table class="table table-striped" align=center>
<tr>
  	<td colspan=11 align=left id=ma>
     <table width=100%>
      <tr id=ma>
		  <?$total_muletos=$result->recordcount()?>
       <td width=30% align=left><b>Total:</b> <?php echo $total_muletos?></td>    
       <td width=40% align=right><?php echo $link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  

  <tr>
    <td align=right id=mo><a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"1","up"=>$up,"id_nomenclador_detalle"=>$id_nomenclador_detalle))?>'>ID</a></td>      	
    <td align=right id=mo><a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"2","up"=>$up,"id_nomenclador_detalle"=>$id_nomenclador_detalle))?>'>Id Nom Detalle</a></td>
    <td align=right id=mo><a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"3","up"=>$up,"id_nomenclador_detalle"=>$id_nomenclador_detalle))?>'>Descripcion</a></td>
    <td align=right id=mo><a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"4","up"=>$up,"id_nomenclador_detalle"=>$id_nomenclador_detalle))?>'>Codigo</a></td>
    <td align=right id=mo><a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"5","up"=>$up,"id_nomenclador_detalle"=>$id_nomenclador_detalle))?>'>Grupo</a></td>
    <td align=right id=mo><a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"6","up"=>$up,"id_nomenclador_detalle"=>$id_nomenclador_detalle))?>'>Subgrupo</a></td>
    <td align=right id=mo><a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"7","up"=>$up,"id_nomenclador_detalle"=>$id_nomenclador_detalle))?>'>Precio</a></td>
    <td align=right id=mo><a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"8","up"=>$up,"id_nomenclador_detalle"=>$id_nomenclador_detalle))?>'>Activo</a></td>
  </tr>
 <?
   while (!$result->EOF) {
   		$ref = encode_link("param_admin_fin.php",array("id_nomenclador"=>$result->fields['id_nomenclador']));
    	$onclick_elegir="location.href='$ref'";
   	?>
  
    <tr>     
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['id_nomenclador']?></td>
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['id_nomenclador_detalle']?></td>
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['descripcion']?></td>     
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['codigo']?></td>     
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['grupo']?></td>     
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['subgrupo']?></td>     
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo '$ '.number_format($result->fields['precio'],2,',','.')?></td>     
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo ($result->fields['activo']=='f')?'NO':'SI'?></td>     
    </tr>
	<?$result->MoveNext();
    }?>
    
</table>
<table width=100% align="center" class="bordes">
  <tr align="center">
   <td>
   <input type=button name="volver" value="Volver" onclick="document.location='param_listado.php'" title="Volver al Listado" style="width=150px">     
   </td>
  </tr>
</table>
</form>
</body>
</html>
<?php echo fin_pagina();// aca termino ?>


