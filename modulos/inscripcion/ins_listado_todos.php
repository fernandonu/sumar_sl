<?php
require_once("../../config.php");

variables_form_busqueda("ins_listado_old");
$usuario1=$_ses_user['id'];
$user_name=$_ses_user['name'];

       
$sql_tmp="SELECT distinct on (ccc.numero_doc) * from (SELECT 
      beneficiarios.id_beneficiarios, 
      beneficiarios.clave_beneficiario, 
      beneficiarios.apellido_benef, 
      beneficiarios.nombre_benef, 
      beneficiarios.fecha_nacimiento_benef, 
      beneficiarios.numero_doc,
      beneficiarios.calle,
      beneficiarios.numero_calle,
      beneficiarios.fecha_inscripcion, 
      efe_conv.nombre 
      FROM 
      uad.beneficiarios
      left join nacer.efe_conv on beneficiarios.cuie_ea=efe_conv.cuie
      order by beneficiarios.fecha_inscripcion DESC) as ccc";



echo $html_header;?>

<form name=form1 action="ins_listado_todos.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		  &nbsp;&nbsp;<b>Buscar: </b><input type=text name="tex_buscar">
      &nbsp;&nbsp;<input type=submit class='btn btn-primary' name="buscar" value='Buscar'>    
     </tr>
</table>


<table border=0 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
  <tr>
  	<td colspan=10 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left><b>Resultado:</b></td>       
       </tr>
    </table>
   </td>
  </tr>
  

  <tr>
  	<td align=right id=mo>Clave Beneficiario</td>    	    
    <td align=right id=mo><a id=mo href='<?=encode_link("ins_listado_todos.php",array("sort"=>"1","up"=>$up))?>'>Documento</a></td>      	    
    <td align=right id=mo>Apellido</a></td>      	    
    <td align=right id=mo>Nombre</a></td>
    <td align=right id=mo>Direccion</a></td>
    <td align=right id=mo><a id=mo href='<?=encode_link("ins_listado_todos.php",array("sort"=>"3","up"=>$up))?>'>Efector</a></td>
    <td align=right id=mo>F NAC</td>
    <td align=right id=mo>F Insc.</td>
  </tr>
 <?
   if ($_POST['buscar']) {
    $where_var = $_POST['tex_buscar'];
    if ($where_var=='') $where = " ORDER BY id_r_x_b DESC LIMIT 50 ";
    else 
    $where = " WHERE ccc.numero_doc ILIKE '%".$where_var."%'"." OR ccc.apellido_benef ILIKE '%".$where_var."%'";
    
    $sql1=$sql_tmp.$where;
    $result = sql($sql1) or fin_pagina();
     while (!$result->EOF) {
     	$ref = encode_link("../trazadoras/seguimiento_erc.php",array("clave_beneficiario"=>$result->fields['clave_beneficiario']));   	
      $onclick_elegir="location.href='$ref'";?>
    
      <tr <?=atrib_tr()?>>     
       <td  onclick="<?=$onclick_elegir?>"><?=$result->fields['clave_beneficiario']?></td>
       <td  onclick="<?=$onclick_elegir?>"><?=$result->fields['numero_doc']?></td>        
       <td  onclick="<?=$onclick_elegir?>"><?=$result->fields['apellido_benef']?></td>     
       <td  onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre_benef']?></td> 
       <td  onclick="<?=$onclick_elegir?>"><?=$result->fields['calle']?></td>    
       <td  onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre']?></td>
       <td  onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['fecha_nacimiento_benef'])?></td>  
       <td  onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['fecha_inscripcion'])?></td>   
     </tr>
  	<?$result->MoveNext();
      }
    }?>
    
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
