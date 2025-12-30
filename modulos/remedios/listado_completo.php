<?php
require_once("../../config.php");


extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

variables_form_busqueda("listado_completo");
 
$orden = array(
        "default" => "1",
        "1" => "codigo",
		    "2" => "descripcion", 
        "3" => "producto",  
       );
$filtro = array(		
		"remedio.codigo" => "Codigo",		
		"descripcion" => "Descripcion",
		"producto" => "Producto",
		);
       
$sql_1="SELECT 
  stock_producto.id_remedio,
  remedio.codigo,
  remedio.descripcion,
  remedio.producto,
  stock_producto.id_efector,
  efectores.cuie,
  efectores.nombre,
  stock_producto.final
  from remedios.stock_producto
  inner join remedios.remedio on remedio.id_remedio=stock_producto.id_remedio
  inner join remedios.efectores on efectores.id_efector=stock_producto.id_efector
  order by cuie,descripcion";

$res_sql_1=sql($sql_1) or fin_pagina();
while (!$res_sql_1->EOF){
  $cuie=strtolower($res_sql_1->fields['cuie']);
  $id_remedio=$res_sql_1->fields['id_remedio'];
  $final=$res_sql_1->fields['final'];
  
  $sql_update="UPDATE remedios.stock set $cuie=$final where id_remedio=$id_remedio";
  $res_update=sql($sql_update) or fin_pagina();
  $res_sql_1->MoveNext();
};

$sql_tmp="SELECT * from remedios.stock
          inner join remedios.remedio on remedio.id_remedio=stock.id_remedio";


echo $html_header;?>

<form name="form1" id="form1" action="listado_completo.php" method=POST>
<table cellspacing=3 cellpadding=3 border=0 width="100%" align="center">
     <tr>
      <td align="center">
      <?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar' >	    
      &nbsp;&nbsp;<input type='button' name="nuevo" value='Nuevo' onclick="document.location='_.php';" disabled="true">
      
      <? $link=encode_link("listado_completo_excel.php",array());?>
      <img src="../../imagenes/excel.gif" style='cursor:hand;'  onclick="window.open('<?=$link?>')">     
     </td>
     </tr>
</table>
<?$result = sql($sql) or die;?>

<table border=0 width="100%" cellspacing=2 cellpadding=2 align="center">
  <tr>
  	<td colspan=10 align=left>
     <table  width=100%>
      <tr>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>


    
  <table  class="table table-striped" width=80% border="1">
  <tr>
  	<td align="right" id="mo"><a id="mo" href='<?=encode_link("listado_medicamentos.php",array("sort"=>"1","up"=>$up))?>'>Codigo</a></td>      	    
    <td align="right" id="mo"><a id="mo" href='<?=encode_link("listado_medicamentos.php",array("sort"=>"2","up"=>$up))?>'>Descripcion</a></td>      	    
    <td align="right" id="mo"><a id="mo" href='<?=encode_link("listado_medicamentos.php",array("sort"=>"3","up"=>$up))?>'>Producto</a></td>
    <? $sql_efectores="SELECT * from remedios.efectores order by cuie";
      $res_efectores=sql($sql_efectores) or die;
      while (!$res_efectores->EOF){
      $cuie=$res_efectores->fields['cuie'];
      $nombre=$res_efectores->fields['nombre'];?>
      
      <td align="right" id="mo" data-toggle="tooltip" data-placement="top" title="<?=$nombre?>"><?=$cuie?></a></td>
      <?$res_efectores->MoveNext();
      }?>
    </tr>
    <?    
    while (!$result->EOF) {?>

          
      <td align="center"><?=$result->fields['codigo']?></td>        
      <td><?=$result->fields['descripcion']?></td>     
      <td><?=$result->fields['producto']?></td>
      <?$res_efectores->movefirst();
        while (!$res_efectores->EOF){
        $cuie=strtolower($res_efectores->fields['cuie']);
        $nombre=$res_efectores->fields['nombre'];
        $stock=($result->fields[$cuie])?$result->fields[$cuie]:-1;
    /*if ($stock>150 and $stock<500) $bg_color="#80EE73";  //esto va en <td> bgcolor='<?=$bg_color?>' 
    if ($stock>50 and $stock<=150) $bg_color="#EEEB73";
    if ($stock>=0 and $stock<=50) $bg_color="#EF9272";*/?>
       
    <td align="center" data-toggle="tooltip" data-placement="top" title="<?=$nombre?>"><?=$result->fields[$cuie]?></td>
    <?$res_efectores->MoveNext();
     }?>
     </tr>
	 <?$result->MoveNext();
    $res_efectores->movefirst();
   }?>
  </table>
    
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
