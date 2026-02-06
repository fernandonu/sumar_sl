<?php

require_once("../../config.php");

variables_form_busqueda("listado_beneficiarios");
cargar_calendario();

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

if ($_POST['genera_excel']=="Genera Excel"){  
    $fecha_desde=$_POST['fecha_desde'];
	$fecha_hasta=$_POST['fecha_hasta'];

    $link=encode_link("listado_beneficiarios_excel.php",array("cmd"=>$cmd,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));?>
	<script>
	window.open('<?=$link?>')
	</script>	
        
<?}

if ($cmd == "")  $cmd="activos";

$orden = array(
        "default" => "1",
        "1" => "afiapellido",
        "2" => "afinombre",
        "3" => "afidni",        
       );
$filtro = array(
		"afidni" => "DNI",
        "nombre"=>"Nombre Efector",
        "cuie"=>"CUIE",           
       );
$datos_barra = array(
     array(
        "descripcion"=> "Activos",
        "cmd"        => "activos"
     ),
     array(
        "descripcion"=> "Activos con CEB",
        "cmd"        => "activos_con_ceb"
     ),
     array(
        "descripcion"=> "Activos sin CEB",
        "cmd"        => "activos_sin_ceb"
     ),
     array(
        "descripcion"=> "Inactivos",
        "cmd"        => "inactivos"
     ),
     array(
        "descripcion"=> "Todos",
        "cmd"        => "todos"
     )
);

generar_barra_nav($datos_barra);

$sql_tmp="select id_smiafiliados, afiapellido,afinombre,afidni,nombre,cuie,activo,motivobaja,mensajebaja,clavebeneficiario,fechainscripcion,afi_cuil
			from nacer.smiafiliados
			left join nacer.efe_conv on (cuieefectorasignado=cuie)";

if ($cmd=="activos")
    $where_tmp=" (smiafiliados.activo='S')";
    
if ($cmd=="activos_con_ceb")
    $where_tmp=" (smiafiliados.activo='S' and ceb='S')";
    
if ($cmd=="activos_sin_ceb")
    $where_tmp=" (smiafiliados.activo='S' and ceb='N')";    

if ($cmd=="inactivos")
    $where_tmp=" (smiafiliados.activo='N')";

echo $html_header;
?>

<script>
function control_excel()
{ 
 if(document.all.fecha_desde.value==""){
  alert('Debe Ingresar una Fecha DESDE');
  return false;
 } 
 if(document.all.fecha_hasta.value==""){
  alert('Debe Ingresar una Fecha HASTA');
  return false;
 } 
 if(document.all.fecha_hasta.value<document.all.fecha_desde.value){
  alert('La Fecha HASTA debe ser MAYOR 0 IGUAL a la Fecha DESDE');
  return false;
 }
 if(document.all.fecha_desde.value.indexOf("-")!=-1){
	  alert('Debe ingresar un fecha en el campo DESDE');
	  return false;
	 }
if(document.all.fecha_hasta.value.indexOf("-")!=-1){
	  alert('Debe ingresar una fecha en el campo HASTA');
	  return false;
	 }
return true;
}
</script>

<form name=form1 action="listado_beneficiarios.php" method=POST>

<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
	    &nbsp;&nbsp;&nbsp; || &nbsp;&nbsp;&nbsp;
	    Desde: <input type=text id="fecha_desde" name="fecha_desde" value='<?=$fecha_desde?>' size=15>
		<?=link_calendario("fecha_desde");?>
		
		Hasta: <input type=text id="fecha_hasta" name="fecha_hasta" value='<?=$fecha_hasta?>' size=15>
		<?=link_calendario("fecha_hasta");?>
	    &nbsp;&nbsp;&nbsp; 

        <input type="submit" class="btn btn-warning" name="genera_excel" value='Genera Excel' onclick="return control_excel()">
	    
	  </td>
     </tr>
</table>

<?$result = sql($sql) or die;?>

<table border=0 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
  <tr>
  	<td colspan=15 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  

  <tr>
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"1","up"=>$up))?>' >Apellido</a></td>      	
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"2","up"=>$up))?>'>Nombre</a></td>
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"3","up"=>$up))?>'>DNI</a></td>
    <td align=right id=mo>CUIL</td>
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"1","up"=>$up))?>'>Nombre Efector</a></td>
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"1","up"=>$up))?>'>CUIE</a></td>
    <?if (($cmd=="todos")||($cmd=="inactivos")){?>
    	<td align=right id=mo><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"1","up"=>$up))?>'>Activo</a></td>
    	<td align=right id=mo><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"1","up"=>$up))?>'>Cod Baja</td>
    	<td align=right id=mo><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"1","up"=>$up))?>'>Mensaje Baja</td>    
    <?}?>  
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"1","up"=>$up))?>'>Clave Beneficiario</a></td>
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"1","up"=>$up))?>'>F Ins</a></td>
    <td align=right id=mo>Certif.</td>

  </tr>
 <?
   while (!$result->EOF) {
		$ref = encode_link("beneficiarios_vista.php",array("id_smiafiliados"=>$result->fields['id_smiafiliados']));
    	$onclick_elegir="location.href='$ref'";?>
    <tr <?=atrib_tr()?>>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['afiapellido']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['afinombre']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['afidni']?></td>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['afi_cuil']?></td> 
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre']?></td> 
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['cuie']?></td> 
     <?if (($cmd=="todos")||($cmd=="inactivos")){?>    
     	<td onclick="<?=$onclick_elegir?>"><?=$result->fields['activo']?></td> 
     	<td onclick="<?=$onclick_elegir?>"><?=$result->fields['motivobaja']?></td> 
     	<td onclick="<?=$onclick_elegir?>"><?=$result->fields['mensajebaja']?></td> 
     <?}?>     
      <td onclick="<?=$onclick_elegir?>"><?=$result->fields['clavebeneficiario']?></td>  
      <td onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['fechainscripcion'])?></td>  
    
    	 <td align="center">
       	 <?$link=encode_link("certificado_pdf.php", array("id_smiafiliados"=>$result->fields['id_smiafiliados']));	
		   echo "<a target='_blank' href='".$link."' title='Imprime Factura'><IMG src='$html_root/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a>";?>
       </td>
     
    </tr>
	<?$result->MoveNext();
    }?>
    
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
