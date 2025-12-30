<?php
require_once("../../config.php");

 $id_efector=intval($_POST["elegido"]);
 $op=$_POST['op'];
 
 if ($op=='para_dispensar') {
   if ($id_efector > 0) {?>
      <td class="bordes">
      <table class="table table-striped"  width="90%" cellspacing="0" cellpadding="0" >
      <tr>
      <td align="center" width="1%" id="mo"></td>
      <td align="right" width="5%" id="mo">Codigo</td>          
      <td align="right" width="20%" id="mo">Descripcion</td>            
      <td align="right" width="40%" id="mo">Producto</td>           
      <td align="right" width="5%" id="mo">Cantidad</td>
      </tr>
    
    <?$sql_remedio="SELECT * from ( select * from programa_sexual.stock_producto 
                where id_efector=$id_efector) as tabla
                inner join programa_sexual.remedio on remedio.id_remedio=tabla.id_remedio
                order by remedio.codigo";
      $i=1;
      
      $result_remedio=sql($sql_remedio) or fin_pagina();
      $limite=round($result_remedio->recordcount()/2);
      
      while (!$result_remedio->EOF and $i<=$limite) {?>
        <?$id_remedio=$result_remedio->fields['id_remedio'];
        $stock=$result_remedio->fields['final'];
        $remedio=$id_remedio."|".$stock;
        $info="El Stock Actual es: ".$stock;
        
        //if ($stock==-1) $linea="";
        if ($stock>25 and $stock<50) $linea="class='success'";
        if ($stock>10 and $stock<=25) $linea="class='warning'";
        if ($stock<=10) $linea="class='danger'";
       ?>

      <tr>
      <td width="1%" ><input type="checkbox"  name="chk_producto[]" id="chk_producto" value="<?=$remedio?>"></td>
       <td  align="center" width="5%" ><?=$result_remedio->fields['codigo']?></td>
       <td  align="left" width="20%" ><?=$result_remedio->fields['descripcion']?></td>
       <td  align="left" width="40%" ><?=$result_remedio->fields['producto']?></td>
       <td align="center" width="5%" ><input type="number" name="cantidad_producto[]" id="cantidad_producto_<?=$id_remedio?>" placeholder='0' data-toggle="tooltip" data-placement="top" title="<?=$info?>" disabled="true"></td>
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
      <tr>
       <td width="1%"><input type="checkbox"  name="chk_producto[]" value="<?=$remedio?>"></td>
       <td  align="center" width="5%"><?=$result_remedio->fields['codigo']?></td>
       <td  align="left" width="20%"><?=$result_remedio->fields['descripcion']?></td>
       <td  align="left" width="40%"><?=$result_remedio->fields['producto']?></td>
       <?if ($id_remedio=='14' or $id_remedio=='15' or $id_remedio=='16') $placeholder="placeholder='No es nesesario Ingresar Cantidad'";
         else $placeholder="placeholder='0'";?>      
       <td align="center" width="5%"><input type="number" name="cantidad_producto[]" id="cantidad_producto_<?=$id_remedio?>" <?=$placeholder?> data-toggle="tooltip" data-placement="top" title="<?=$info?>" disabled="true"></td>
       </tr>
    <? 
      $result_remedio->MoveNext();
        };?>
    </table>
  </td>
  <? }//del if
}

if ($op=='control_stock') {?>
  <script>
    
    $(".btn_modal").click( function() {
    var efector_remedio=$(this).val();
    var clearing="#"+efector_remedio+"clearing";
    var inicial="#"+efector_remedio+"inicial";
    var remito="#"+efector_remedio+"remito";
    var total_1="#"+efector_remedio+"total_1";
    
    var u_entregadas="#"+efector_remedio+"u_entregadas";

    var salida_clearing="#"+efector_remedio+"salida_clearing";
    var salida_no_apto="#"+efector_remedio+"salida_no_apto";
    var observaciones="#"+efector_remedio+"observaciones";
    var salida_robo="#"+efector_remedio+"salida_robo";
    var total_2="#"+efector_remedio+"total_2";
    
    var final_1="#"+efector_remedio+"final";
    
    var codigo_ir="#"+efector_remedio+"codigo_ir";
    
    
    $(clearing).on("blur", function () {
        
        var total_1_p = parseInt($(inicial).val()) + parseInt($(remito).val()) + parseInt($(clearing).val());
        $(total_1).val(total_1_p);
        var total_2_p = parseInt($(u_entregadas).val()) + parseInt($(salida_clearing).val()) + parseInt($(salida_no_apto).val()) + parseInt($(salida_robo).val());
        $(total_2).val(total_2_p);
        var final_1_p = parseInt($(total_1).val()) - parseInt($(total_2).val());
        $(final_1).val(final_1_p);

   })//blur
  
    $(remito).on("blur", function () {
        
        var total_1_p = parseInt($(inicial).val()) + parseInt($(remito).val()) + parseInt($(clearing).val());
        $(total_1).val(total_1_p);
        var total_2_p = parseInt($(u_entregadas).val()) + parseInt($(salida_clearing).val()) + parseInt($(salida_no_apto).val()) + parseInt($(salida_robo).val());
        $(total_2).val(total_2_p);
        var final_1_p = parseInt($(total_1).val()) - parseInt($(total_2).val());
        $(final_1).val(final_1_p);
   })//blur

    $(salida_clearing).on("blur", function () {
        
        var total_1_p = parseInt($(inicial).val()) + parseInt($(remito).val()) + parseInt($(clearing).val());
        $(total_1).val(total_1_p);
        var total_2_p = parseInt($(u_entregadas).val()) + parseInt($(salida_clearing).val()) + parseInt($(salida_no_apto).val()) + parseInt($(salida_robo).val());
        $(total_2).val(total_2_p);
        var final_1_p = parseInt($(total_1).val()) - parseInt($(total_2).val());
        $(final_1).val(final_1_p);
   })//blur
  
    $(salida_no_apto).on("blur", function () {
        
        var total_1_p = parseInt($(inicial).val()) + parseInt($(remito).val()) + parseInt($(clearing).val());
        $(total_1).val(total_1_p);
        var total_2_p = parseInt($(u_entregadas).val()) + parseInt($(salida_clearing).val()) + parseInt($(salida_no_apto).val()) + parseInt($(salida_robo).val());
        $(total_2).val(total_2_p);
        var final_1_p = parseInt($(total_1).val()) - parseInt($(total_2).val());
        $(final_1).val(final_1_p);
   })//blur

    $(salida_robo).on("blur", function () {
        
        var total_1_p = parseInt($(inicial).val()) + parseInt($(remito).val()) + parseInt($(clearing).val());
        $(total_1).val(total_1_p);
        var total_2_p = parseInt($(u_entregadas).val()) + parseInt($(salida_clearing).val()) + parseInt($(salida_no_apto).val()) + parseInt($(salida_robo).val());
        $(total_2).val(total_2_p);
        var final_1_p = parseInt($(total_1).val()) - parseInt($(total_2).val());
        $(final_1).val(final_1_p);
   })//blur
  
    
  })//click   
    
    
    
    $("button[name='Guardar_Remedios']").click( function () {
      
      //alert($(this).val());
        elegido=$(this).val();
        var inicial=$("#"+elegido+"inicial").val();
        var remito=$("#"+elegido+"remito").val();
        var clearing=$("#"+elegido+"clearing").val();
        var total_1=$("#"+elegido+"total_1").val();
        var u_entregadas=$("#"+elegido+"u_entregadas").val();
        var salida_clearing=$("#"+elegido+"salida_clearing").val();
        var salida_no_apto=$("#"+elegido+"salida_no_apto").val();
        var campo_observaciones="#"+elegido+"observaciones";
        var observaciones=$(campo_observaciones).val();
        var salida_robo=$("#"+elegido+"salida_robo").val();
        var total_2=$("#"+elegido+"total_2").val();
        var final_1=$("#"+elegido+"final").val();
        var codigo_ir=$("#"+elegido+"codigo_ir").val();
        
        var minimo=$("#"+elegido+"minimo").val();
        var maximo=$("#"+elegido+"maximo").val();
        
        var op="carga_remedio";
        

        if (salida_no_apto==0) { 
        
          $.ajax({
            data: {elegido: elegido,
              inicial:inicial,
              remito:remito,
              clearing:clearing,
              total_1:total_1,
              u_entregadas:u_entregadas,
              salida_clearing:salida_clearing,
              salida_no_apto:salida_no_apto,
              observaciones:observaciones,
              salida_robo:salida_robo,
              total_2:total_2,
              final_1:final_1,
              codigo_ir:codigo_ir,
              minimo:minimo,
              maximo:maximo,
              op:op},
            type: "POST",
            dataType: "json",
            url: "carga_modal.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
               var id_remedio=data['id_remedio'];
               //console.log(data['id_remedio']);
               var myModal="#myModal"+id_remedio;
               $(myModal).modal('hide');
               location.reload();
               //$('[data-toggle="tooltip"]').tooltip();
               //console.log(html(data));
          }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado: " +  textStatus);
          }
      });//fail
    }//if
  else {if (observaciones=='') {alert("Debe ingresar un motivo para los medicamentos no apto"); 
        $(campo_observaciones).focus();
        return false;} 
        else {
          $.ajax({
            data: {elegido: elegido,
              inicial:inicial,
              remito:remito,
              clearing:clearing,
              total_1:total_1,
              u_entregadas:u_entregadas,
              salida_clearing:salida_clearing,
              salida_no_apto:salida_no_apto,
              observaciones:observaciones,
              salida_robo:salida_robo,
              total_2:total_2,
              final_1:final_1,
              codigo_ir:codigo_ir,
              minimo:minimo,
              maximo:maximo,
              op:op},
            type: "POST",
            dataType: "json",
            url: "carga_modal.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
               var id_remedio=data['id_remedio'];
               //console.log(data['id_remedio']);
               var myModal="#myModal"+id_remedio;
               $(myModal).modal('hide');
               location.reload();
               //$('[data-toggle="tooltip"]').tooltip();
               //console.log(html(data));
          }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado: " +  textStatus);
          }
      });//fail
     }//if
    }//else    
  })//click 
</script>
  
  <? if ($id_efector=='todos') {
  
        $sql_tmp="SELECT * from (SELECT * from (
                      select id_remedio,sum(inicial) as inicial,sum(remito) as remito,
                      sum(clearing) as clearing, sum(total_1) as total_1,
                      sum(u_entregadas) as u_entregadas,sum(salida_clearing) as salida_clearing,
                      sum(salida_no_apto) as salida_no_apto,sum (salida_robo) as salida_robo,
                      sum (total_2) as total_2, sum (final) as final
                      from programa_sexual.stock_producto group by 1 order by 1
                      ) as stock
                      inner join programa_sexual.remedio on stock.id_remedio=remedio.id_remedio) as ccc order by ccc.codigo";}

        else {$sql_tmp="SELECT * from (SELECT  stock_producto.id_stock_producto,
          stock_producto.id_efector,
          stock_producto.id_remedio,
          stock_producto.inicial,
          stock_producto.remito,
          stock_producto.clearing,
          stock_producto.total_1,
          stock_producto.u_entregadas,
          stock_producto.salida_clearing,
          stock_producto.salida_no_apto,
          stock_producto.salida_robo,
          stock_producto.total_2,
          stock_producto.final,
          stock_producto.observaciones_no_apto,
          stock_producto.codigo_ir,
          remedio.codigo,
          remedio.descripcion,
          remedio.producto,
          parametros.minimo,
          parametros.maximo
        from programa_sexual.stock_producto 
        inner join programa_sexual.remedio on remedio.id_remedio=stock_producto.id_remedio
        left join programa_sexual.parametros on parametros.id_remedio=remedio.id_remedio
        where stock_producto.id_efector=$id_efector) as ccc order by ccc.codigo";
                      }
        
        $result=sql($sql_tmp) or fin_pagina();

?>
  
  
<div class="col-md-12">  
<table border=0 width="100%" cellspacing=2 cellpadding=2 align="center">
  <table  class="table table-striped" width=100% border="1">
  <tr>
    <td align="right" id="mo"><a id="mo" href='<?=encode_link("listado_medicamentos.php",array("sort"=>"1","up"=>$up))?>'>Codigo</a></td>           
    <td align="right" id="mo"><a id="mo" href='<?=encode_link("listado_medicamentos.php",array("sort"=>"2","up"=>$up))?>'>Descripcion</a></td>            
    <td align="right" id="mo"><a id="mo" href='<?=encode_link("listado_medicamentos.php",array("sort"=>"3","up"=>$up))?>'>Producto</a></td>
    <td align="right" id="mo">Stock Inicial</td>
    <td align="right" id="mo" colspan="3">Unidades Recividas</td>
    <td align="right" id="mo">U. Entregadas</td>
    <td align="right" id="mo" colspan="4">Otras Salidas</td>
    <td align="right" id="mo">Total</td>
    <td align="right" id="mo">Stock Final</td>
    <td align="right" id="mo">Modificar Stock</td>
    </tr>

    <tr>
    <td align="right" id="mo"></td>           
    <td align="right" id="mo"></td>            
    <td align="right" id="mo"></td>
    <td align="right" id="mo"></td>
    <td align="right" id="mo">Remito</td>
    <td align="right" id="mo">Clearing</td>
    <td align="right" id="mo">Total</td>
    <td align="right" id="mo"></td>
    <td align="right" id="mo">Clearing</td>
    <td align="right" id="mo">No Apto</td>
    <td align="right" id="mo">Observ.</td>
    <td align="right" id="mo">Robo</td>
    <td align="right" id="mo"></td>
    <td align="right" id="mo"></td>
    <td align="right" id="mo"></td>
    </tr>

    <? while (!$result->EOF) {
          $id_remedio=$result->fields['id_remedio'];
          $stock=$stock=($result->fields['final'])?$result->fields['final']:-1;
          $efector_remedio=$id_efector."-".$id_remedio;
          
          $minimo=($result->fields['minimo'])?$result->fields['minimo']:5;
          $maximo=($result->fields['maximo'])?$result->fields['maximo']:10;
          if ($stock>=$maximo) $linea="class='success'";
          if ($stock>$minimo and $stock<$maximo) $linea="class='warning'";
          if ($stock<=$minimo) $linea="class='danger'";?>

<!--modal-->
<div class="modal fade" id="myModal<?=$id_remedio?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  

  <script type="text/javascript">
  </script>

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <? if ($id_efector) {
        $sql_modal="SELECT * from programa_sexual.remedio where id_remedio=$id_remedio";
        $res_modal=sql($sql_modal,"no se pudo ejecutar la consulta del producto") or fin_pagina();
        $codigo=$res_modal->fields['codigo'];
        $nombre_remedio=$res_modal->fields['descripcion'];
        $producto=$res_modal->fields['producto'];
        $titulo=$codigo." - ".$producto." - (".$nombre_remedio.")";

        

      }
          else {$titulo="Debe seleccionar un efector";}
        ?>
        <h4 class="modal-title"><?echo $titulo?></h4>
        </div>
        <div class="modal-body">
          <?
            $sql_stock="SELECT * from programa_sexual.stock_producto where id_efector=$id_efector and id_remedio=$id_remedio";
            $res_stock=sql($sql_stock,"no se pudo ejecutar la consulta sobre stock del remedio") or fin_pagina();
            $inicial=$res_stock->fields['inicial'];
            $remito=$res_stock->fields['remito'];
            $clearing=$res_stock->fields['clearing'];
            $total_1=$res_stock->fields['total_1'];
            $u_entregadas=$res_stock->fields['u_entregadas'];
            $salida_clearing=$res_stock->fields['salida_clearing'];
            $salida_no_apto=$res_stock->fields['salida_no_apto'];
            $observaciones=$res_stock->fields['observaciones_no_apto'];
            $salida_robo=$res_stock->fields['salida_robo'];
            $total_2=$res_stock->fields['total_2'];
            $final=$res_stock->fields['final'];
            ?>
            
          <form id="<?=$efector_remedio?>" class="form-horizontal" role="form">
          <input type="hidden" id="efector_remedio" name="efector_remedio" value="<?=$efector_remedio?>">
          <div class="row form-group">
          <label for="inicial" class="col-sm-3 control-label">Inicial:</label>
          <div class="col-sm-3">
            <input type="number" class="form-control" id="<?=$efector_remedio?>inicial" name="inicial" value="<?=$inicial?>" readonly>
          </div>
          <label for="remito" class="col-sm-3 control-label">Remito:</label>
          <div class="col-sm-3">
            <input type="number" class="form-control" id="<?=$efector_remedio?>remito" name="remito" value="<?=$remito?>">
          </div>
          </div>

          
          <div class="row form-group">
            <label for="clearing" class="col-sm-3 control-label">Clearing:</label>
            <div class="col-sm-3">
            <input type="number" class="form-control" id="<?=$efector_remedio?>clearing" name="clearing" value="<?=$clearing?>" >
            </div>
               <label for="total_1" class="col-sm-3 control-label">Total Parcial:</label>
            <div class="col-sm-3">
            <input type="number" class="form-control" id="<?=$efector_remedio?>total_1" name="total_1" value="<?=$total_1?>" readonly>
            </div>
            </div>

          <div class="row form-group">
            <label for="u_entregadas" class="col-sm-3 control-label">U. Entregadas:</label>
            <div class="col-sm-3">
            <input type="number" class="form-control" id="<?=$efector_remedio?>u_entregadas" name="u_entregadas" value="<?=$u_entregadas?>" readonly>
            </div>
            
            <label for="salida_clearing" class="col-sm-3 control-label">Salida Clearing:</label>
            <div class="col-sm-3">
            <input type="number" class="form-control" id="<?=$efector_remedio?>salida_clearing" name="salida_clearing" value="<?=$salida_clearing?>">
            </div>
            </div>

          <div class="row form-group">  
            <label for="salida_no_apto" class="col-sm-3 control-label">Salida No Apto</label>
            <div class="col-sm-3">
            <input type="number" class="form-control" id="<?=$efector_remedio?>salida_no_apto" name="salida_no_apto" value="<?=$salida_no_apto?>">
            </div>
            
            <label for="salida_robo" class="col-sm-3 control-label">Salida Robo:</label>
            <div class="col-sm-3">
            <input type="number" class="form-control" id="<?=$efector_remedio?>salida_robo" name="salida_robo" value="<?=$salida_robo?>">
            </div>
            </div>

          
          <div class="row form-group">
            <label for="observaciones" class="col-sm-3 control-label">Observaciones No Apto:</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" id="<?=$efector_remedio?>observaciones" name="observaciones" value="<?=$observaciones?>">
            </div>
          </div>
          
          
          <div class="row form-group">
            <label for="total_2" class="col-sm-3 control-label">Salida Total:</label>
            <div class="col-sm-3">
            <input type="number" class="form-control" id="<?=$efector_remedio?>total_2" name="total_2" value="<?=$total_2?>" readonly>
            </div>
            
            <label for="final" class="col-sm-3 control-label">Stock Final:</label>
            <div class="col-sm-3">
            <input type="number" class="form-control" id="<?=$efector_remedio?>final" name="final" value="<?=$final?>" readonly>
            </div>
          </div>
          
                
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" name="Guardar_Remedios" value="<?=$efector_remedio?>">
        Guardar Cambios</button>
      </div>
      
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
          
          
          
          
          
          <tr>    
           <td align="center"><?=$result->fields['codigo']?></td>        
           <td><?=$result->fields['descripcion']?></td>     
           <td><?=$result->fields['producto']?></td>     
           <td align="center"><?=$result->fields['inicial']?></td>
           <td align="center"><?=$result->fields['remito']?></td> 
           <td align="center"><?=$result->fields['clearing']?></td>
           <td align="center"><?=$result->fields['total_1']?></td>
           <td align="center"><?=$result->fields['u_entregadas']?></td>
           <td align="center"><?=$result->fields['salida_clearing']?></td>
           <td align="center"><?=$result->fields['salida_no_apto']?></td>
           <td align="center"><?=$result->fields['observaciones_no_apto']?></td>
           <td align="center"><?=$result->fields['salida_robo']?></td>
           <td align="center"><?=$result->fields['total_2']?></td>
           <td align="center"><b><?=$result->fields['final']?></b></td>
           <?if (!$id_efector) $disabled="disabled"; else $disabled="enabled"?>
           <td align="center"><button type="button" class="btn_modal" data-toggle="modal" data-target="#myModal<?=$id_remedio?>" name="boton_modal" value="<?=$efector_remedio?>" <?=$disabled?>> 
            Modf.
            </button>
            </td>
          </tr>
        <?$result->MoveNext();
        }?>
 
 
    </table>
    
    </table>
  </div>
   
   <?if ($id_efector<>'todos') {?> 
    <div class="col-md-12" id="botones" name="botones">  
      
      <?$link_1=encode_link("resumen_mensual.php",array("id_efector" => $id_efector));?>
      <td align="center"><button type="button" class="btn btn-primary btn-sm" <?=$disabled?> style='cursor:hand;'  onclick="window.open('<?=$link_1?>')">
      Resumen Mensual</button>
      
      <?$link_2=encode_link("registro_diario.php",array("id_efector" => $id_efector));?>
      <td align="center"><button type="button" class="btn btn-success btn-sm" style='cursor:hand;'  onclick="window.open('<?=$link_2?>')">
      Registro Diario</button>
      
      <?$link_3=encode_link("consolidado_trimestral.php",array("id_efector" => $id_efector));?>
      <td align="center"><button type="button" class="btn btn-warning btn-sm" style='cursor:hand;'  onclick="window.open('<?=$link_3?>')">
      Consolidado Trimestral</button>
      
        
      <!--<img src="../../imagenes/excel.gif" style='cursor:hand;'  onclick="window.open('<?=$link?>')" disabled> -->    
     <?}?>
     </td>
    </div>
     
<?}

?>