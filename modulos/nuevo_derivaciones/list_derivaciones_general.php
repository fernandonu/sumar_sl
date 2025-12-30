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
    "numero_doc" => "DNI",
    "apellido_benef"=>"Apellido",
    "nombre_benef"=>"Nombre"
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

if (es_cuie($_ses_user['login'])) {
  
  $cuie_der=$_ses_user['login'];
  $sql_tmp="SELECT
        cuie_dev.cuie AS cuie_efe_deriv,
        cuie_dev.nombre AS nom_efector_derivado,
        cuie_solc.nombre AS cuie_efe_sol,
        sistema.usuarios.nombre as user_nam,
        sistema.usuarios.apellido as user_ap, 
        derivacion_general.depar.profesional as profesional_sol,
        derivacion_general.efector_derivado.profesional as profesional_der,
        cie10.dec10,
        derivacion_general.depar.id_deriv as der,*,
        practica.descripcion as practica_descrip
        FROM
        derivacion_general.depar
        LEFT JOIN derivacion_general.efector_derivado ON derivacion_general.efector_derivado.id_deriv = derivacion_general.depar.id_deriv
        INNER JOIN (SELECT * from nacer.efe_conv WHERE cuie='$cuie_der') AS cuie_dev ON cuie_dev.cuie = derivacion_general.efector_derivado.cuie_efe_deriv
        INNER JOIN nacer.efe_conv AS cuie_solc ON cuie_solc.cuie = derivacion_general.depar.cuie_solic
        INNER JOIN derivacion_general.est_deriv ON derivacion_general.efector_derivado.confirmacion = derivacion_general.est_deriv.id_est            
        INNER JOIN uad.beneficiarios ON derivacion_general.depar.id_beneficiario = uad.beneficiarios.id_beneficiarios
        INNER JOIN sistema.usuarios ON derivacion_general.depar.usuario = sistema.usuarios.id_usuario
        INNER JOIN nacer.cie10 on cie10.id10=depar.diag_cie10
        INNER JOIN derivacion_general.practica ON derivacion_general.practica.id_practica=derivacion_general.depar.practica";
  
  
  }
   else {
  $sql_tmp="SELECT
            cuie_dev.cuie AS cuie_efe_deriv,
            cuie_dev.nombre AS nom_efector_derivado,
            cuie_solc.nombre AS cuie_efe_sol,
            sistema.usuarios.nombre as user_nam,
            sistema.usuarios.apellido as user_ap, 
            derivacion_general.depar.profesional as profesional_sol,
            derivacion_general.efector_derivado.profesional as profesional_der,
            cie10.dec10,
            derivacion_general.depar.id_deriv as der,*,
            practica.descripcion as practica_descrip
            FROM
            derivacion_general.depar
            LEFT JOIN derivacion_general.efector_derivado ON derivacion_general.efector_derivado.id_deriv = derivacion_general.depar.id_deriv
            INNER JOIN nacer.efe_conv AS cuie_dev ON cuie_dev.cuie = derivacion_general.efector_derivado.cuie_efe_deriv
            INNER JOIN nacer.efe_conv AS cuie_solc ON cuie_solc.cuie = derivacion_general.depar.cuie_solic
            INNER JOIN derivacion_general.est_deriv ON derivacion_general.efector_derivado.confirmacion = derivacion_general.est_deriv.id_est
            
            INNER JOIN uad.beneficiarios ON derivacion_general.depar.id_beneficiario = uad.beneficiarios.id_beneficiarios
            INNER JOIN sistema.usuarios ON derivacion_general.depar.usuario = sistema.usuarios.id_usuario
            INNER JOIN nacer.cie10 on cie10.id10=depar.diag_cie10
            INNER JOIN derivacion_general.practica ON derivacion_general.practica.id_practica=derivacion_general.depar.practica";
  }
    
if ($cmd=='1'){ 
    //if (es_cuie($_ses_user['login']))
    $where_tmp=" (derivacion_general.efector_derivado.confirmacion=1) ";
    
}
if ($cmd=='2'){ 
   // if (es_cuie($_ses_user['login']))
    $where_tmp=" (derivacion_general.efector_derivado.confirmacion=2) "; 
   
}
if ($cmd=='3'){ 
    //if (es_cuie($_ses_user['login']))
    $where_tmp=" (derivacion_general.efector_derivado.confirmacion=3) "; 
   
}
if ($cmd=='4'){ 
    //if (es_cuie($_ses_user['login'])) and cuie='$user_login'
    $where_tmp=" (derivacion_general.efector_derivado.confirmacion=4) "; 
    
}
if ($where_tmp=="")  $where_tmp=" (derivacion_general.efector_derivado.confirmacion=1) ";
echo $html_header;
?>
<form name="form1" action="list_derivaciones_general.php" method="POST">

<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
          <?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
            &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'> 
    </td>
     </tr>
</table>

<?$result = sql($sql) or die;?>

<table class="table table-striped">
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
      <td align=right id='mo'><a href='<?=encode_link("list_derivaciones_general.php",array("sort"=>"1","up"=>$up))?>'>Prioridad</a></td>

    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones_general.php",array("sort"=>"1","up"=>$up))?>'>Fecha de derivacion</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones_general.php",array("sort"=>"1","up"=>$up))?>'>Efector Solicitante</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones_general.php",array("sort"=>"2","up"=>$up))?>' >Apellido</a></td>       
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones_general.php",array("sort"=>"2","up"=>$up))?>'>Nombre</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones_general.php",array("sort"=>"3","up"=>$up))?>'>DNI</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones_general.php",array("sort"=>"3","up"=>$up))?>'>Solicitante</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones_general.php",array("sort"=>"3","up"=>$up))?>'>Fecha de carga</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones_general.php",array("sort"=>"3","up"=>$up))?>'>Diag.</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones_general.php",array("sort"=>"3","up"=>$up))?>'>Efect.Deriv.</a></td>
    <td align=right id='mo'><a href='<?=encode_link("list_derivaciones_general.php",array("sort"=>"3","up"=>$up))?>'>Pract.Solic.</a></td>
    <td align=right id='mo'>Editar respuesta</td>

  </tr>
 <? while (!$result->EOF) {?>
              <?php switch ($result->fields['prioridad']) {
                case 'Alta': $estilo="style='background-color:#FB6C6C'";break;
                  
                  case 'Media':$estilo="style='background-color:#EFFB6C'";break;
                  
                  case 'Baja':$estilo="style='background-color:#80FB6C'";break;
                
                default:break;
              } ?>
                  <tr>
                    <!--<td align="center"><?=$result->fields['id_deriv'];?></td>-->
                      <td align="center" <?php echo $estilo;?>                     
                      ><b><?=$result->fields['prioridad'];?></b></td>
                      <td align="center"><?=fecha($result->fields['fecha_deriv']);?></td>
                      <td align="center"><?=$result->fields['cuie_efe_sol'];?></td>
                      <td align="center"> <?=$result->fields['apellido_benef']?></td>
                      <td align="center"> <?=$result->fields['nombre_benef']?></td>
                      <td align="center"> <?=$result->fields['numero_doc']?></td>
                      <td align="center"> <?=$result->fields['profesional_sol']?></td>
                      <td align="center"> <?=fecha($result->fields['fecha_solicitud'])?></td>
                      <td align="center"> <?=$result->fields['dec10']?></td>
                      <td align="center"> <?=$result->fields['nom_efector_derivado']?></td>
                      <td align="center"> <?=$result->fields['practica_descrip']?></td>
                      <? $ref = encode_link("res_derivacion_general.php",array("id_deriv"=>$result->fields['der'],"id_deref"=>$result->fields['id_deref'],"confirmacion"=>$result->fields['confirmacion'],"pagina"=>"list_derivaciones_general.php"));
                        $onclick_elegir="location.href='$ref'";?>
                      <td align="center">         
                        <span class="glyphicon glyphicon-list-alt" onclick="<?php echo "location.href='$ref'"?>" style='cursor:hand;'> <p onclick="<?="location.href='$ref'"?>" style='cursor:hand;'> Editar </p></span>
                      </td>                        
                  </tr>
    <?$result->MoveNext();  }?> 
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>