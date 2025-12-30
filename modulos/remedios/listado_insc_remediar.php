<?php
require_once("../../config.php");


variables_form_busqueda("listado_insc_remediar");
$usuario1=$_ses_user['id'];
$user_name=$_ses_user['name'];

$orden = array(
        "default" => "1",
        "1" => "beneficiarios.numero_doc"
       );

$filtro = array(		
		"numero_doc" => "N&uacute;mero de Documento",		
		"apellido_benef" => "Apellido",
		"efectores.nombre" => "Efector",
		);
       
$sql_tmp="SELECT distinct on (beneficiarios.numero_doc)
			beneficiarios.id_beneficiarios, 
			beneficiarios.clave_beneficiario, 
			beneficiarios.apellido_benef, 
			beneficiarios.nombre_benef, 
			beneficiarios.fecha_nacimiento_benef, 
			beneficiarios.numero_doc, 
			efectores.nombre 
			FROM 
			uad.beneficiarios
			left join remedios.efectores on beneficiarios.cuie_ea=efectores.cuie";

echo $html_header;?>

<script type="text/javascript">

$(document).ready(function(){
  $("input[name='keyword']").focus();

  $("#buscar").click(function(){
      var keyword=$("input[name='keyword']").val();
      var n = keyword.search('"');
      if (n!=-1) {
        var key_array=keyword.split('"');
        //alert(key_array[4]);
        $("input[name='keyword']").val(key_array[4]);
        }

  })

})

</script>

<form name=form1 action="listado_insc_remediar.php" method=POST>
<table cellspacing=3 cellpadding=3 border=0 width=100% align="center">
     <tr>
      <td align="center">
		  <div id="campo_busqueda"> 
      <?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
      &nbsp;&nbsp;<input type='submit' name="buscar" value='Buscar' id="buscar">	    
      &nbsp;&nbsp;<input type='button' name="nuevo" value='Nuevo' onclick="document.location='planilla_insc.php';" disabled>
      </div>
      </td>
     </tr>
</table>

<?$result = sql($sql) or fin_pagina();?> 

<table border=1 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align="center">
  <tr>
  	<td colspan=10 align=left id="ma">
     <table width=100%>
      <tr id="ma">
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  

  <tr>
  	<td align="right" id="mo">Clave Beneficiario</td>    	    
    <td align="right" id="mo"><a id="mo" href='<?=encode_link("listado_insc_remediar.php",array("sort"=>"1","up"=>$up))?>'>Documento</a></td>      	    
    <td align="right" id="mo">Apellido</a></td>      	    
    <td align="right" id="mo">Nombre</a></td>
    <td align="right" id="mo">Efector</a></td>
    <td align="right" id="mo">F NAC</td>
    <td align="right" id="mo">tipo fichero cronol&oacute;gico</td>
  </tr>
 <?
   while (!$result->EOF) {?>
   
    <tr <?=atrib_tr()?>>     
     <td  align="center" onclick="<?=$onclick_elegir?>"><?=$result->fields['clave_beneficiario']?></td>
     <td  align="center" onclick="<?=$onclick_elegir?>"><?=$result->fields['numero_doc']?></td>        
     <td  onclick="<?=$onclick_elegir?>"><?=$result->fields['apellido_benef']?></td>     
     <td  onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre_benef']?></td>     
     <td  onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre']?></td>
     <td  align="center" onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['fecha_nacimiento_benef'])?></td>  
     <td align="center">
         <?$id_beneficiarios=$result->fields['id_beneficiarios'];
         $link=encode_link("planilla_remedio.php", array("id_beneficiarios"=>$id_beneficiarios));
         $ref="location.href='$link'"; // href='".$link."'
       echo "<a target='_blank'  href='".$link."' title='Cargar Medicamento'><IMG src='$html_root/imagenes/medicamento.jpg' height='25' width='60' border='0'></a>";?>
       </td>   
   </tr>
	<?$result->MoveNext();
    }?>
    
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
