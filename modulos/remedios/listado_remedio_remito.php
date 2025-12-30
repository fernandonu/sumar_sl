<?php
/*
Author: seba
$Revision: 1.0 $
$Date: 2015/04/30 10:52:40 $
*/
require_once("../../config.php");

variables_form_busqueda("listado_remedio_remito");

$orden = array(
        "default" => "1",
        "1" => "id_remito",
        "2" => "numero_remito",
        "3" => "fecha_remito"
        );

$filtro = array(
		"cuie" => "CUIE",
    "nombre"=> "Nombre de Centro",
    "numero_remito" => "Numero de Remito"
    );

if ($cmd=='')$cmd=1;     
$datos_barra = array(
     array(
        "descripcion"=> "Pendiente",
        "cmd"        => "1"
     ),
     array(
        "descripcion"=> "Autorizado",
        "cmd"        => "2"
     ),
     array(
        "descripcion"=> "Rechazado",
        "cmd"        => "3"
     ),
     array(
        "descripcion"=> "Todos",
        "cmd"        => "todos"
     )       
);

generar_barra_nav($datos_barra);
 
$sql_tmp="SELECT * from remedios.remito 
          inner join remedios.efectores on efectores.id_efector=remito.id_efector";

if ($cmd==1)
    $where_tmp=" (estado='p')";
if ($cmd==2)
    $where_tmp=" (estado='a')";  
if ($cmd==3)
    $where_tmp=" (estado='n')";
   
echo $html_header;
?>
<form name=form1 action="listado_remedio_remito.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_infosoc,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
	    </td>
	    <!--<td align=center>
	    &nbsp;&nbsp;<? $link=encode_link(".php",array("sql"=>$sql));?>
        <img src="../../imagenes/excel.gif" style='cursor:hand;'  onclick="window.open('<?=$link?>')">
	  </td>-->
    
     </tr>
</table>

<?$result = sql($sql,"No se ejecuto en la consulta principal") or die;?>

<table border=1 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
  <tr>
  	<td colspan=13 align="left" id="ma">
     <table width=100%>
      <tr id="ma">
       <td width=30% align="left"><b>Total:</b> <?=$total_infosoc?></td>       
       <td width=40% align="right"><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  <tr>

    <td align="right" id="mo">Estado</a></td> 
    <td align="right" id="mo">Numero de Remito</a></td> 
    <td align="right" id="mo">Fecha de Remito</a></td>    
    <td align="right" id="mo">Cuie</a></td>	
    <td align="right" id="mo">Nombre</a></td>
    <td align="right" id="mo">Fecha Entrega</a></td>
    <td align="right" id="mo">Fecha Aprobacion</a></td>
  </tr>
  <?
   while (!$result->EOF) {
	if($cmd==1 || $cmd==2 || $cmd==3){
   		$id_remito=$result->fields['id_remito'];
      $id_efector=$result->fields['id_efector'];
      $cuie=$result->fields['cuie'];
      $ref = encode_link("autorizacion_stock.php",array("cmd"=>$cmd,"pagina"=>"autorizacion_stock","id_remito"=>$id_remito, "id_efector"=>$id_efector,"cuie"=>$cuie));
    	$onclick_elegir="location.href='$ref'";
   	 }
   	?>
  
    <tr <?=atrib_tr()?>>     
     <td align=center onclick="<?=$onclick_elegir?>"><b><?if($result->fields['estado']=='p')echo "PENDIENTE"; elseif(trim($result->fields['estado'])=='a') echo "AUTORIZADO"; 
     elseif(trim($result->fields['estado'])=='n') echo "RECHAZADO"?></b></td>
	<td align=center onclick="<?=$onclick_elegir?>"><?=$result->fields['numero_remito']?></td>
  <td align="center" onclick="<?=$onclick_elegir?>"><?=$result->fields['fecha_remito']?></td>
  <td align="center" onclick="<?=$onclick_elegir?>"><?=$result->fields['cuie'];?></td>
  <td align="center" onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre']?></td>
  <td align="center" onclick="<?=$onclick_elegir?>"><?=$result->fields['fecha_entrega']?></td>
  <td align="center" onclick="<?=$onclick_elegir?>"><?=$result->fields['fecha_aprobacion']?></td>
  </tr>    
	<?$result->MoveNext();
    }?>
  	
</table>
</form>
</body>
</html>

<?echo fin_pagina();// aca termino ?>