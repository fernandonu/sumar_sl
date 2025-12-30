<?php

require_once("../../config.php");


	




variables_form_busqueda("efectores_unif");

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

if ($cmd == "")  $cmd="VERDADERO";

$orden = array(
        "default" => "1",
        "1" => "cuie",
        "2" => "efe_conv.nombre",        
        "3" => "cuidad",    
        "9" => "nombre_dpto"    
       );
       
$filtro = array(
		"cuie" => "CUIE",
        "efe_conv.nombre" => "Nombre",
        "referente" => "Referente"
       );
       
$datos_barra = array(
     array(
        "descripcion"=> "Convenio",
        "cmd"        => "VERDADERO"
     ),
     array(
        "descripcion"=> "Sin Convenio",
        "cmd"        => "FALSO"
     ),
     array(
        "descripcion"=> "Red Salud",
        "cmd"        => "REDSALUD"
     ),
     array(
        "descripcion"=> "Todos",
        "cmd"        => "TODOS"
     )
);

generar_barra_nav($datos_barra);

$sql_tmp="SELECT 
  efe_conv.*,zona_sani.*,dpto.nombre as nombre_dpto
FROM
  nacer.efe_conv
  left join facturacion.nomenclador_detalle using (id_nomenclador_detalle)
  left join nacer.zona_sani using (id_zona_sani)
  left join nacer.dpto on dpto.codigo=efe_conv.departamento";

$user_login1=substr($_ses_user['login'],0,6);

if ($cmd=="VERDADERO")
	if (es_cuie($_ses_user['login']))
	    $where_tmp=" (efe_conv.conv_sumar='t' and efe_conv.cuie='$user_login1')";
    else 
    	$where_tmp=" (efe_conv.conv_sumar='t')"; 

if ($cmd=="FALSO")
	if (es_cuie($_ses_user['login']))
   	 	$where_tmp=" (efe_conv.conv_sumar='f' and efe_conv.cuie='$user_login1')";
   	 else 
   	 $where_tmp=" (efe_conv.conv_sumar='f')";
   	 
if ($cmd=="REDSALUD")
	if (es_cuie($_ses_user['login']))
   	 	$where_tmp=" (efe_conv.com_gestion='REDSALUD' and efe_conv.cuie='$user_login1')";
   	 else 
   	 $where_tmp=" (efe_conv.com_gestion='REDSALUD')";  	
   	  
if ($cmd=="TODOS")
	if (es_cuie($_ses_user['login']))
   	 	$where_tmp=" (efe_conv.cuie='$user_login1')";
   	 	
echo $html_header;
?>
<form name=form1 action="efectores_unif.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
	     &nbsp;&nbsp;
	    <? $link=encode_link("efectores_unif_excel.php",array("cmd"=>$cmd));?>
        <img src="../../imagenes/excel.gif" style='cursor:hand;'  onclick="window.open('<?=$link?>')">
        &nbsp;&nbsp;
        <b><a href=mail.txt target="_blank">Mail con Descripcion</a></b>
        &nbsp;&nbsp;
        <b><a href=mail_solos.txt target="_blank">Mail</a></b>
	  </td>
     </tr>
</table>

<?$result = sql($sql) or die;?>

<table border=1 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
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
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"1","up"=>$up))?>'>CUIE</a></td>      	
    <td align=right id=mo>Cod Siisa</td>
    <td align=right id=mo>Cod Remediar</td>
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"2","up"=>$up))?>'>Nombre</a></td>
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"3","up"=>$up))?>'>Cuidad</a></td>        
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"5","up"=>$up))?>'>Referente</a></td>        
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"6","up"=>$up))?>'>Telefono</a></td>
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"7","up"=>$up))?>'>Mail</a></td>
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"8","up"=>$up))?>'>Comp Gestion</a></td>    
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"9","up"=>$up))?>'>Departamento</a></td>    
   </tr>
 <?
   while (!$result->EOF) {
  	$ref = encode_link("efectores_unif_admin.php",array("id_efe_conv"=>$result->fields['id_efe_conv']));
    $onclick_elegir="location.href='$ref'";?>
    
    <tr <?=atrib_tr()?>>        
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['cuie']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['cod_siisa']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['cod_remediar']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['cuidad']?></td>       
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['referente']?></td>  
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['tel']?></td> 
     <?$cuie=$result->fields['cuie'];
     $sql="select * from nacer.mail_efe_conv where cuie = '$cuie'";
     $result_mail=sql($sql,'Error');
     $result_mail->movefirst();
     $contenido_mail='';
     while (!$result_mail->EOF) {
     	$contenido_mail.=$result_mail->fields['mail'].', ';
     	$result_mail->MoveNext();
     }
     ?>  
     <td onclick="<?=$onclick_elegir?>"><?=$contenido_mail?></td>  
     <td onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['fecha_comp_ges'])?></td>  
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre_dpto']?></td>  
    </tr>
	<?$result->MoveNext();
    }

    if (permisos_check('inicio','mail_masivos')){	
    ?>    
    <tr>
  	<td colspan=15 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left>
        <b>Envia Mail a TODOS los CAPS con Convenio, de todos los NIÑOS con Percentilos de Peso Bajo:</b>
        <input type="submit" value="Enviar Mail" name="mail_percentilo_nino_bajo" disabled="disabled">
       </td>       
      </tr>	
    </table>
   </td>
  </tr>
   <tr>
  	<td colspan=15 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left>
        <b>Envia Mail a TODOS los CAPS con Convenio, de Mujeres con embarazo Adolecente:</b>
        <input type="submit" value="Enviar Mail" name="mail_embarazo_adol" disabled="disabled">
       </td>       
      </tr>	
    </table>
   </td>
  </tr>
  <?}?>
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
