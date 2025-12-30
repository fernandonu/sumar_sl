<?php
require_once ("../../config.php");

variables_form_busqueda("listado_beneficiarios");

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

$orden = array(
        "default" => "1",
        "1" => "apellido_benef",
        "2" => "nombre_benef",
        "3" => "numero_doc", 
            
       );
$filtro = array(
    "numero_doc" => "DNI",
    "apellido_benef"=>"Apellido",
    "nombre_benef"=>"Nombre",
    "estado"=>"Estado Deriv."
       );

 
$datos_barra = array(
  
     array(
        "descripcion"=> 'Alta',
        "cmd"        => '1',
     ),
     array(
        "descripcion"=> 'Media',
        "cmd"        => '2',
     ),
     array(
        "descripcion"=> 'Baja',
        "cmd"        => '3',
     )
 );


generar_barra_nav($datos_barra);

if (es_cuie($_ses_user['login'])) {
  
  $cuie_der=$_ses_user['login'];
  $sql_tmp="SELECT *,
      sistema.usuarios.nombre as user_nam,
      sistema.usuarios.apellido as user_ap,
      derivacion_general.est_deriv.estado as estado_derivacion,
      efector_der.profesional as profesional_sol,
      cuie_deriv.nombre as efector_derivado,  
      derivacion_general.efector_derivado.profesional as profesional_der,
      derivacion_general.practica.descripcion as des_practica
      from uad.beneficiarios 
      INNER JOIN (select * from derivacion_general.depar where cuie_solic='$cuie_der') as efector_der 
        ON uad.beneficiarios.id_beneficiarios = efector_der.id_beneficiario
      INNER JOIN derivacion_general.practica ON efector_der.practica=practica.id_practica 
      INNER JOIN (SELECT cuie,nombre from nacer.efe_conv) AS cuie_solc ON cuie_solc.cuie = efector_der.cuie_solic 
      INNER JOIN derivacion_general.efector_derivado using (id_deriv)
      INNER JOIN (SELECT cuie,nombre from nacer.efe_conv) AS cuie_deriv ON cuie_deriv.cuie = efector_derivado.cuie_efe_deriv 
      INNER JOIN derivacion_general.est_deriv ON efector_derivado.confirmacion= derivacion_general.est_deriv.id_est
      INNER JOIN sistema.usuarios ON efector_der.usuario = sistema.usuarios.id_usuario";
      
      switch ($cmd) {
        case '1':$where_tmp=" (efector_der.prioridad='Alta') ";break;
        case '2':$where_tmp=" (efector_der.prioridad='Media') "; break;
        case '3':$where_tmp=" (efector_der.prioridad='Baja') "; break;
        default:$where_tmp=" (efector_der.prioridad!='') ";break;
      }
  
  } else {
          
  $sql_tmp="SELECT *,
      sistema.usuarios.nombre as user_nam,
      sistema.usuarios.apellido as user_ap,
      derivacion_general.est_deriv.estado as estado_derivacion,
      derivacion_general.depar.profesional as profesional_sol,
      cuie_deriv.nombre as efector_derivado,  
      derivacion_general.efector_derivado.profesional as profesional_der,
      derivacion_general.practica.descripcion as des_practica
      from uad.beneficiarios 
      INNER JOIN derivacion_general.depar ON uad.beneficiarios.id_beneficiarios = derivacion_general.depar.id_beneficiario
      INNER JOIN derivacion_general.practica ON depar.practica=practica.id_practica 
      INNER JOIN (SELECT cuie,nombre from nacer.efe_conv) AS cuie_solc ON cuie_solc.cuie = derivacion_general.depar.cuie_solic 
      INNER JOIN derivacion_general.efector_derivado using (id_deriv)
      INNER JOIN (SELECT cuie,nombre from nacer.efe_conv) AS cuie_deriv ON cuie_deriv.cuie = efector_derivado.cuie_efe_deriv 
      INNER JOIN derivacion_general.est_deriv ON efector_derivado.confirmacion= derivacion_general.est_deriv.id_est
      INNER JOIN sistema.usuarios ON derivacion_general.depar.usuario = sistema.usuarios.id_usuario";
      
      switch ($cmd) {
        case '1':$where_tmp=" (derivacion_general.depar.prioridad='Alta') ";break;
        case '2':$where_tmp=" (derivacion_general.depar.prioridad='Media') "; break;
        case '3':$where_tmp=" (derivacion_general.depar.prioridad='Baja') "; break;
        default:$where_tmp=" (derivacion_general.depar.prioridad!='') ";break;
      }
  };
    
echo $html_header;
?>
<form name="form1" action="list_der_solicitante.php" method="POST">

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
    <td align="center">Fecha de derivacion</td>
    <td align="center">Estado</td>
    <td align="center">Apellido</td>       
    <td align="center">Nombre</td>
    <td align="center">DNI</td>
    <td align="center">Practica</td>
    <td align="center">Solicitante</td>
    <td align="center">Efector Deriv.</td>
    <td align="center">Fecha de carga</td>
    
  </tr>
 <? while (!$result->EOF) {?>
                  <?php switch ($result->fields['prioridad']) {
                    case 'Alta': $estilo="style='background-color:#FB6C6C'";break;
                      
                    case 'Media':$estilo="style='background-color:#EFFB6C'";break;
                    
                    case 'Baja':$estilo="style='background-color:#80FB6C'";break;
                    
                    default:break;
                  } ?>
                  <tr <?php 
                        $id_deref=$result->fields['id_deref'];?>>
                  <td align="center" width="10%"><?=fecha($result->fields['fecha_deriv']);?></td>
                  <!--<td align="center"><?=$result->fields['cuie_efe_sol'];?></td>-->
                  <td align="center" width="10%" <?echo $estilo;?>>
                    <?php if ($result->fields['estado_derivacion']=='CONFIRMADO') {?>
                    <a href='<?=encode_link("res_derivacion_general.php",array("id_deref"=>$id_deref,"id_deriv"=>$result->fields['id_deriv'],"pagina"=>"desde_res_de_gral"))?>'><b><?=$result->fields['estado_derivacion'];?></b></a>
                    <?}
                      else {?>
                        <b><?=$result->fields['estado_derivacion'];?></b>
                      <?}?>
                  </td>
                  <td align="center" width="10%"><?=$result->fields['apellido_benef']?></td>
                  <td align="center" width="10%"><?=$result->fields['nombre_benef']?></td>
                  <td align="center" width="10%"><?=$result->fields['numero_doc']?></td>
                  <td align="center" width="10%"><?=$result->fields['des_practica']?></td>
                  <td align="center" width="10%"><?=$result->fields['profesional_sol']?></td>
                  <td align="center" width="10%"><?=$result->fields['efector_derivado']?></td>
                  <td align="center" width="10%"><?=fecha($result->fields['fecha_solicitud'])?></td>
                  </tr>
    <?$result->MoveNext();  }?> 
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>