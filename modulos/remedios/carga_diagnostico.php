<?php
require_once("../../config.php");
require_once("agenda_funciones.php");


 $id_efector=intval($_POST["elegido"]);
 if ($id_efector > 0) {
 /*       $sql= "SELECT cuie from remedios.efectores where id_efector=$id_efector";
        $res_efectores=sql($sql) or fin_pagina();
        $cuie=$res_efectores->fields['cuie'];
        $cuie_r=strtr($cuie,"D","d");

        $sql_remedio="SELECT remedios.remedio.*,
                  remedios.stock.$cuie_r as stock
                  from remedios.remedio 
                  inner join remedios.stock on remedio.codigo=stock.codigo
                  order by id_remedio";

        $res_remedio=sql($sql_remedio) or fin_pagina();

        while (!$res_remedio->EOF) {
        $res_json[] = array(
          "id_remedio"    => $res_remedio->fields['id_remedio'],
          "codigo"        => $res_remedio->fields['codigo'],
          "descripcion"     => $res_remedio->fields['descripcion'],
          "producto"          => $res_remedio->fields['producto'],
          "stock"           => $res_remedio->fields['stock'],
          );
        $res_remedio->MoveNext();
      };
 echo json_encode($res_json);
 */
?>
    <td class="bordes">
    <table class="table table-striped"  width="90%" cellspacing="0" cellpadding="0" >
    <tr>
    <td align="center" width="1%" id="mo"></td>
    <td align="right" width="5%" id="mo">Codigo</td>          
    <td align="right" width="20%" id="mo">Descripcion</td>            
    <td align="right" width="40%" id="mo">Producto</td>           
    <td align="right" width="5%" id="mo">Cantidad</td>
    </tr>
  
  <!--<input type="hidden" name="id_efector_real" id="id_efector_real" value=<?$id_efector_real?>>-->
       
    <? 
    /*$sql= "SELECT cuie from remedios.efectores where id_efector=$id_efector";
            $res_efectores=sql($sql) or fin_pagina();
            $cuie=$res_efectores->fields['cuie'];
            $cuie_r=strtr($cuie,"D","d");

            $sql_remedio="SELECT remedios.remedio.*,
                  remedios.stock.$cuie_r as stock
                  from remedios.remedio 
                  inner join remedios.stock on remedio.codigo=stock.codigo
                  order by id_remedio";*/

            $sql_remedio="SELECT * from ( select * from remedios.stock_producto 
              where id_efector=$id_efector) as tabla
              inner join remedios.remedio on remedio.id_remedio=tabla.id_remedio
              order by remedio.descripcion";
            $i=1;
    
    $result_remedio=sql($sql_remedio) or fin_pagina();
    $limite=round($result_remedio->recordcount()/2);
    
    while (!$result_remedio->EOF and $i<=$limite) {?>
      <?$id_remedio=$result_remedio->fields['id_remedio'];
      $stock=$result_remedio->fields['final'];
      $remedio=$id_remedio."|".$stock;
      $info="El Stock Actual es: ".$stock;
      
      //if ($stock==-1) $linea="";
      if ($stock>250 and $stock<500) $linea="class='success'";
      if ($stock>100 and $stock<=250) $linea="class='warning'";
      if ($stock<=100) $linea="class='danger'";
     ?>

    <tr <?echo $linea?> >
    <td width="1%" ><input type="checkbox"  name="chk_producto[]" id="chk_producto" value="<?=$remedio?>"></td>
     <td  align="center" width="5%" ><?=$result_remedio->fields['codigo']?></td>
     <td  align="left" width="20%" ><?=$result_remedio->fields['descripcion']?></td>
     <td  align="left" width="40%" ><?=$result_remedio->fields['producto']?></td>
     <td align="center" width="5%" ><input type="number" name="cantidad_producto[]" id="cantidad_producto_<?=$id_remedio?>" value=0 data-toggle="tooltip" data-placement="top" title="<?=$info?>" disabled="true"></td>
     </tr>
  <? 
    $i++;
    $result_remedio->MoveNext();
    }?>
    </table>
</td>
<td class="bordes">
    <table class="table table-striped"  width="90%" cellspacing="0" cellpadding="0">
    <tr>
    <td align="center" width="1%" id="mo"></td>
    <td align="right"  width="5%"id="mo">Codigo</td>          
    <td align="right" width="20%" id="mo">Descripcion</td>            
    <td align="right" width="40%" id="mo">Producto</td>           
    <td align="right" width="5%"id="mo">Cantidad</td>
    </tr>
    <? while (!$result_remedio->EOF) {?>
    <?  $id_remedio=$result_remedio->fields['id_remedio'];
      $stock=$result_remedio->fields['final'];  
      $remedio=$id_remedio."|".$stock;
      $info="El Stock Actual es: ".$stock;
      
      //if (!$stock) $linea="";
      if ($stock>250 and $stock<500) $linea="class='success'";
      if ($stock>100 and $stock<=250) $linea="class='warning'";
      if ($stock<=100) $linea="class='danger'";
      ?>
    <tr <?echo $linea?>>
     <td width="1%"><input type="checkbox"  name="chk_producto[]" value="<?=$remedio?>"></td>
     <td  align="center" width="5%"><?=$result_remedio->fields['codigo']?></td>
     <td  align="left" width="20%"><?=$result_remedio->fields['descripcion']?></td>
     <td  align="left" width="40%"><?=$result_remedio->fields['producto']?></td>
     <td align="center" width="5%"><input type="number" name="cantidad_producto[]" id="cantidad_producto_<?=$id_remedio?>" value=0  data-toggle="tooltip" data-placement="top" title="<?=$info?>" disabled="true"></td>
     </tr>
  <? 
    $result_remedio->MoveNext();
      };?>
  </table>
</td>
<?
 
}//del if




$extras = array(
            "id_efector"      => "",
            "id_especialidad" => ""
          );
variables_form_busqueda("planilla_remedio", $extras);
if (isset($parametros["accion"])) {
  switch ($parametros["accion"]) {
    case 'cie10_autocomplete':
      $buscar = $_GET["term"];
      $res_json = array();
      $query = "SELECT 
                  *
                FROM
                  nacer.cie10
                WHERE
                  id10 ILIKE ".$db->Quote($buscar)." OR
                  dec10 ILIKE ".$db->Quote("%".$buscar."%")."
                  ";
      // echo $query;
      $res_cie10 = sql($query, "al obtener los datos del nomenclador") or die();
      while (!$res_cie10->EOF) {
        $res_json[] = array(
          "id"            => $res_cie10->fields["id10"],
          "value"         => utf8_encode($res_cie10->fields["id10"]." ".$res_cie10->fields["dec10"]),
          "label"         => utf8_encode($res_cie10->fields["id10"]." ".$res_cie10->fields["dec10"])
        );
        $res_cie10->MoveNext();
      }
      echo json_encode($res_json);
      break;

    case 'cepsap_autocomplete':
      $buscar = $_GET["term"];
      $res_json = array();
      $query = "SELECT 
                  *
                FROM
                  nacer.cepsap_items_2016
                WHERE
                  codigo ILIKE ".$db->Quote($buscar)." OR
                  descripcion ILIKE ".$db->Quote("%".$buscar."%")."
                  ";
      // echo $query;
      $res_cepsap = sql($query, "al obtener los datos del nomenclador") or die();
      while (!$res_cepsap->EOF) {
        $res_json[] = array(
          "id"            => $res_cepsap->fields["id"],
          "value"         => utf8_encode($res_cepsap->fields["codigo"]." ".$res_cepsap->fields["descripcion"]),
          "label"         => utf8_encode($res_cepsap->fields["codigo"]." ".$res_cepsap->fields["descripcion"])
        );
        $res_cepsap->MoveNext();
      }
      echo json_encode($res_json);
      break;

    default:
      # code...
    break;
  }
}


?>