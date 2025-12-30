<?php
require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
variables_form_busqueda("param_admin");

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

$orden = array(
        "default" => "2",
        "1" => "id_nomenclador",
        "2" => "descripcion",
        "3" => "codigo",
        "4" => "grupo",
        "5" => "subgrupo",
        "6" => "precio",
       );
$filtro = array(
		"descripcion" => "Descripcion",
       );

$sql_tmp="select * from facturacion.nomenclador where id_nomenclador_detalle=$id_nomenclador_detalle order by grupo,codigo,descripcion";
$where_tmp= "";

echo $html_header;
?>
<form name=form1 action="param_admin.php" method=POST>
<input type="hidden" name="id_nomenclador_detalle" value="<?php echo $id_nomenclador_detalle?>">
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?//list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    <!--</->&nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>-->
	  </td>
     </tr>
</table>

<?php $result = sql($sql_tmp) or die;?>

<!--<table border=0 width=80% cellspacing=2 cellpadding=2 bgcolor='<?php echo $bgcolor3?>' align=center>-->
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
    <td align=right id=mo><!--<a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"1","up"=>$up))?>'>-->ID</a></td>      	
    <td align=right id=mo><!--<a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"2","up"=>$up))?>'>-->Descripcion</a></td>
    <td align=right id=mo><!--<a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"3","up"=>$up))?>'>-->Codigo</a></td>
    <td align=right id=mo><!--<a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"4","up"=>$up))?>'>-->Grupo</a></td>
    <td align=right id=mo><!--<a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"5","up"=>$up))?>'>-->Subgrupo</a></td>
    <td align=right id=mo><!--<a id=mo href='<?php echo encode_link("param_admin.php",array("sort"=>"6","up"=>$up))?>'>-->Precio</a></td>
  </tr>
 <?
   while (!$result->EOF) {
   		$ref = encode_link("param_admin_fin.php",array("id_nomenclador"=>$result->fields['id_nomenclador']));
    	$onclick_elegir="location.href='$ref'";
   	?>
  
    <tr>     
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['id_nomenclador']?></td>
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['descripcion']?></td>     
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['codigo']?></td>     
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['grupo']?></td>     
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['subgrupo']?></td>     
     <td style='cursor: hand; height:35px;' onclick="<?php echo $onclick_elegir?>"><?php echo '$ '.number_format($result->fields['precio'],2,',','.')?></td>     
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
