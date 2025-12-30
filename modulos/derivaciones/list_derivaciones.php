<?php
require_once ("../../config.php");

variables_form_busqueda("listado_beneficiarios");

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

if ($cmd == "")  $cmd="activos";

$orden = array(
        "default" => "1",
        "1" => "afiapellido",
        "2" => "afinombre",
        "3" => "afidni", 
            
       );
$filtro = array(
    "afidni" => "DNI",
        "afiapellido"=>"Apellido",
        "afinombre"=>"Nombre",  
        "afidni"=> "DNI",

       );

 
$datos_barra = array(
  
     array(
        "descripcion"=> 'Pendiente',
        "cmd"        => '1',
     ),
     array(
        "descripcion"=> 'Confirmados',
        "cmd"        => '2',
     ),
     array(
        "descripcion"=> 'Rechazados',
        "cmd"        => '3',
     ),
     array(
        "descripcion"=> 'Anulados',
        "cmd"        => '4',
     )
 );


generar_barra_nav($datos_barra);

$sql_tmp="SELECT
          cuie_dev.cuie AS cuie_efe_deriv,
          cuie_solc.nombre AS cuie_efe_sol,
          sistema.usuarios.nombre as user_nam,
          sistema.usuarios.apellido as user_ap, 
          derivacion.depar.id_deriv as der,*
          FROM
          derivacion.depar
          LEFT JOIN derivacion.efector_derivado ON derivacion.efector_derivado.id_deriv = derivacion.depar.id_deriv
          INNER JOIN nacer.efe_conv AS cuie_dev ON cuie_dev.cuie = derivacion.efector_derivado.cuie_efe_deriv
          INNER JOIN nacer.efe_conv AS cuie_solc ON cuie_solc.cuie = derivacion.depar.cuie_solic
          INNER JOIN derivacion.est_deriv ON derivacion.efector_derivado.confirmacion = derivacion.est_deriv.id_est
          INNER JOIN nacer.smiafiliados ON derivacion.depar.id_beneficiario = nacer.smiafiliados.id_smiafiliados
          INNER JOIN sistema.usuarios ON derivacion.depar.usuario = sistema.usuarios.id_usuario";
    
if ($cmd=='1'){ 
    //if (es_cuie($_ses_user['login']))
    $where_tmp=" (derivacion.efector_derivado.confirmacion=1) ";
    
}
if ($cmd=='2'){ 
   // if (es_cuie($_ses_user['login']))
    $where_tmp=" (derivacion.efector_derivado.confirmacion=2) "; 
   
}
if ($cmd=='3'){ 
    //if (es_cuie($_ses_user['login']))
    $where_tmp=" (derivacion.efector_derivado.confirmacion=3) "; 
   
}
if ($cmd=='4'){ 
    //if (es_cuie($_ses_user['login'])) and cuie='$user_login'
    $where_tmp=" (derivacion.efector_derivado.confirmacion=4) "; 
    
}
if ($where_tmp=="")  $where_tmp=" (derivacion.efector_derivado.confirmacion=1) ";
echo $html_header;
?>
<form name="form1" action="list_derivaciones.php" method="POST">

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
      <td align=right id='mo'><a href='<?=encode_link("list_derivaciones.php",array("sort"=>"1","up"=>$up))?>'>Registro Nro</a></td>

    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones.php",array("sort"=>"1","up"=>$up))?>'>Fecha de derivacion</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones.php",array("sort"=>"1","up"=>$up))?>'>Efector Solicitante</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones.php",array("sort"=>"2","up"=>$up))?>' >Apellido</a></td>       
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones.php",array("sort"=>"2","up"=>$up))?>'>Nombre</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones.php",array("sort"=>"3","up"=>$up))?>'>DNI</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones.php",array("sort"=>"3","up"=>$up))?>'>Solicitante</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones.php",array("sort"=>"3","up"=>$up))?>'>Fecha de carga</a></td>
    <td align=right id='mo'>Editar respuesta</td>

  </tr>
 <? while (!$result->EOF) {?>
                  <tr><td align="center"><?=$result->fields['id_deriv'];?></td>
                      <td align="center"><?=fecha($result->fields['fecha_deriv']);?></td>
                      <td align="center"><?=$result->fields['cuie_efe_sol'];?></td>
                      <td align="center"> <?=$result->fields['afiapellido']?></td>
                      <td align="center"> <?=$result->fields['afinombre']?></td>
                      <td align="center"> <?=$result->fields['afidni']?></td>
                      <td align="center"> <?=$result->fields['user_nam'].'-'.$result->fields['user_ap']?></td>
                      <td align="center"> <?=fecha($result->fields['fecha_solicitud'])?></td>
                      <? $ref = encode_link("responder_deriv.php",array("id_deref"=>$result->fields['id_deref'],"confirmacion"=>$result->fields['confirmacion'],"pagina"=>"list_derivaciones.php"));
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