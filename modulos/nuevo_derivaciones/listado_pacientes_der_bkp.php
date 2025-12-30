<?php
require_once ("../../config.php");

variables_form_busqueda("listado_beneficiarios");

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

if ($cmd == "")  $cmd="activos";

$orden = array(
        "default" => "1",
        "1" => "apellido_benef",
        "2" => "nombre_benef",
        "3" => "numero_doc",        
       );
$filtro = array(
    "numero_doc" => "DNI"           
       );
/*$sql_tmp="select id_smiafiliados, afiapellido,afinombre,afidni,nombre,cuie,activo,motivobaja,mensajebaja,clavebeneficiario,fechainscripcion
      from nacer.smiafiliados
      left join nacer.efe_conv on (cuieefectorasignado=cuie)";*/

$sql_tmp="SELECT id_beneficiarios as id_smiafiliados, 
        apellido_benef as afiapellido,
        nombre_benef as afinombre,
        numero_doc as afidni,
        clave_beneficiario as clavebeneficiario,
        fecha_inscripcion as fechainscripcion
      from uad.beneficiarios";

echo $html_header;
?>
<form name="form1" action="listado_pacientes_der.php" method="POST">

<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
          <?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
            &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'> 
    </td>
     </tr>
</table>

<?$result = sql($sql) or die;?>

<table border=0 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
  <tr>
    <td colspan=15 align=left id='ma'>
     <table width=100%>
      <tr id='ma'>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  <tr>
    <td align=right id='mo'><a href='<?=encode_link("listado_pacientes_der.php",array("sort"=>"1","up"=>$up))?>'>Clave Beneficiario</a></td>
    <td align=right id='mo'><a  href='<?=encode_link("listado_pacientes_der.php",array("sort"=>"1","up"=>$up))?>' >Apellido</a></td>       
    <td align=right id='mo'><a href='<?=encode_link("listado_pacientes_der.php",array("sort"=>"2","up"=>$up))?>'>Nombre</a></td>
    <td align=right id='mo'><a  href='<?=encode_link("listado_pacientes_der.php",array("sort"=>"3","up"=>$up))?>'>DNI</a></td>
    <td align=right id='mo'>Editar</td>
  </tr>
 <? while (!$result->EOF) {?>
                  <tr>
                  <td align="center"><?=$result->fields['clavebeneficiario'];?></td>
                  <td align="left"> <?=$result->fields['afiapellido']?></td>
                  <td align="left"> <?=$result->fields['afinombre']?></td>
                  <td align="left"> <?=$result->fields['afidni']?></td>
                  <? $ref = encode_link("admin_derivaciones.php",array("id_smiafiliados"=>$result->fields['id_smiafiliados'],"pagina"=>"listado_pacientes_der.php"));
                    $onclick_elegir="location.href='$ref'";?>
                  <td align="center">         
                    <span class="glyphicon glyphicon-list-alt" onclick="<?="location.href='$ref'"?>" style='cursor:hand;'> <p onclick="<?="location.href='$ref'"?>" style='cursor:hand;'> Editar </p></span>
                  </td>
                  </tr>
    <?$result->MoveNext();  }?> 
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>