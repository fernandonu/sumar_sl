<?php
require_once("../../config.php"); 

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$extras = array ("id_efector" => $id_efector,
                 "cuie" => $cuie
                 );

variables_form_busqueda("listado_medicamentos",$extras);

$usuario1=$_ses_user['id'];
$user_name=$_ses_user['name'];

if (es_cuie($_ses_user['login'])) {
    $cuie=$_ses_user['login'];
    $sql_efe="SELECT * from remedios.efectores where cuie='$cuie'";
    $res_efe=sql($sql_efe,"No se pudieron traer los datos del efector") or fin_pagina();
    $id_efector=$res_efe->fields['id_efector'];
  };
 
$orden = array(
        "default" => "1",
        "1" => "codigo",
		    "2" => "descripcion", 
        "3" => "producto",  
       );
$filtro = array(		
		"ccc.codigo" => "Codigo",		
		"ccc.descripcion" => "Descripcion",
		"ccc.producto" => "Producto",
		);
       
if (!$id_efector) {//$sql_tmp="SELECT * from remedios.remedio";
        $sql_tmp="SELECT * from (SELECT * from (
                select id_remedio,sum(inicial) as inicial,sum(remito) as remito,
                sum(clearing) as clearing, sum(total_1) as total_1,
                sum(u_entregadas) as u_entregadas,sum(salida_clearing) as salida_clearing,
                sum(salida_no_apto) as salida_no_apto,sum (salida_robo) as salida_robo,
                sum (total_2) as total_2, sum (final) as final
                from remedios.stock_producto group by 1 order by 1
                ) as stock
                inner join remedios.remedio on stock.id_remedio=remedio.id_remedio) as ccc";}

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
from remedios.stock_producto 
inner join remedios.remedio on remedio.id_remedio=stock_producto.id_remedio
left join remedios.parametros on parametros.id_remedio=remedio.id_remedio
where stock_producto.id_efector=$id_efector) as ccc";
              }


echo $html_header;?>

<style type="text/css"> 
.modal-body {max-height: 900px;}
</style>

<script type="text/javascript">

$(document).ready(function(){
 
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

$("button[name='guardar_consulta']").click( function () {
    elegido=$(this).val();
    var consulta=$('#consulta').val();
    var op="carga_consulta";
    
    if (consulta=='') {alert("Debe ingresar un Valor Positivo para la cantidad de Consultas"); 
        $(campo_observaciones).focus();
        return false;} 
    else {
    $.ajax({
            data: {elegido: elegido,
                  consulta: consulta,
                  op: op},
            type: "POST",
            dataType: "json",
            url: "carga_modal.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha acompletado correctamente.");
               $(myModal).modal('hide');
               location.reload();
               
          }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado: " +  textStatus);
          }
      });//fail
    }//del else 
  })//click 


$("button[name='imprimir']").click( function () {
    elegido=$(this).val();
    var consulta=$('#consulta').val();
        
    if (consulta=='') {alert("Debe ingresar un Valor Positivo para la cantidad de Consultas"); 
        $(campo_observaciones).focus();
        return false;} 
    else {
        var link = "<?php echo encode_link('stock_remedios.php',array('id_efector'=>$id_efector))?>";
        link += '&consultas='+consulta
        document.location = link;
      }//del else 
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


})//document.ready
  


function set_stock() {

  /*var a = document.all.precio.value();
  var b = document.all.cantidad.value();
  var c = a * b;

  document.all.total.value(c);*/

  
  /*var a = $("#stock").val();
  if (isNaN(a)) {alert ("El numero ingresado es Invalido"); document.all.stock.focus()}
  else {  
    if (a<=0) {alert ("El numero ingresado es Negativo"); document.all.stock.focus()}
    else {$("#stock").val(a);}
    };*/

//document.form1.submit();
}

</script>

<form name="form1" id="form1" action="listado_medicamentos.php" method=POST>
<table cellspacing=3 cellpadding=3 border=0 width="100%" align="center">
     <tr>
      <td align="center">
       <b>Efector:</b>
            <? if (!$id_efector){?>
            <select name="id_efector" id="id_efector" Style="width=450px"
            onKeypress="buscar_combo(this);"
            onblur="borrar_buffer();"
            onchange="borrar_buffer(); document.forms[0].submit()" >
        
      <?
      $sql= "select * from remedios.efectores order by cuie";
       
      echo "<option value=-1 Style='background-color: <?=$color_style?>'><b>Stock Total de Productos</b></option>";
                         
      $res_efectores=sql($sql) or fin_pagina();
      while (!$res_efectores->EOF){ 
      $id_efector=$res_efectores->fields['id_efector'];
      $nombre_efector=$res_efectores->fields['nombre'];
      $cuie=$res_efectores->fields['cuie'];
        ?>
        <option value=<?=$id_efector;?> Style="background-color: <?=$color_style?>;"><?=$cuie." - ".$nombre_efector?></option>
          <?
          $res_efectores->movenext();
          };
          $id_efector=0;?>
        </select>
        <?}
        else {
            $sql= "select * from remedios.efectores where id_efector=$id_efector";
            $res_efectores=sql($sql) or fin_pagina();
            $id_efector=$res_efectores->fields['id_efector'];
            $nombre_efector=$res_efectores->fields['nombre'];
            $cuie=$res_efectores->fields['cuie'];?>

          <b><input type='text' name='efector' value='<?=$cuie." - ".$nombre_efector?>' size=60 align='right' readonly></b>
          <input type='hidden' name='id_efector' value='<?=$id_efector?>'>


          <?}?>

      <?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>	    
      <?if (!$id_efector) $disabled="disabled"; else $disabled="enabled"?>
      <td align="center"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" <?=$disabled?>>
      Cierre Mensual</button>
      
      <? $link=encode_link("stock_remedios.php",array("id_efector" => $id_efector));?>
      <!--<img src="../../imagenes/excel.gif" style='cursor:hand;'  onclick="window.open('<?=$link?>')" disabled>     
     -->
     </td>
     </tr>
    </table>
  <?$result = sql($sql) or die;?>

<table border=0 width="100%" cellspacing=2 cellpadding=2 align="center">
  <tr>
  	<td colspan=10 align=left>
     <table  width=90%>
      <tr>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>

<!--modal del cierre de mes - ingresar consultas-->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Carga Manual de Consultas <?=$id_efector?></h4>
      </div>
      <div class="modal-body">
        <form id="<?=$efe?>" class="form-horizontal" role="form">
        <div class="row form-group">
        <label for="consulta" class="col-sm-6 control-label">Ingrese La cantidad de Consulta para Cerrar el Periodo:</label>
        <div class="col-sm-6">
        <input type="number" class="form-control" id="consulta" name="consulta" value="<?=$consultas?>">
        </div>
      </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" name="imprimir" class="btn btn-warning" value="<?=$id_efector?>">Imprimir Excel</button>
        <button type="button" name="guardar_consulta" class="btn btn-primary" value="<?=$id_efector?>">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>
</div>
   
  <table  class="table table-striped" width=80% border="1">
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

 <?
    while (!$result->EOF) {
    
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
        $sql_modal="SELECT * from remedios.remedio where id_remedio=$id_remedio";
        $res_modal=sql($sql_modal,"no se pudo ejecutar la consulta de remedio") or fin_pagina();
        $codigo=$res_modal->fields['codigo'];
        $nombre_remedio=$res_modal->fields['descripcion'];
        $producto=$res_modal->fields['producto'];
        $titulo=$codigo." - ".$nombre_remedio." - ".$producto;

        

      }
          else {$titulo="Debe seleccionar un efector";}
        ?>
        <h4 class="modal-title"><?echo $titulo?></h4>
        </div>
        <div class="modal-body">
          <?
            $sql_stock="SELECT * from remedios.stock_producto where id_efector=$id_efector and id_remedio=$id_remedio";
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
      <!--<div class="modal-footer">
            <label for="codigo_ir" class="col-sm-3 control-label">Codigo IR:</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" id="<?=$efector_remedio?>codigo_ir" name="codigo_ir" value="<?=$codigo_ir?>">
            </div>
      </div>-->
      
      <div class="modal-footer">
        <label for="minimo" class="col-sm-10 control-label">Ingrese los Valores para Manejar el SOTCK visualmente</label>
      </div>
      
        <div class="row form-group" align="center">
            <label for="minimo" class="col-sm-3 control-label">Minimo:</label>
            <div class="col-sm-3">
            <input type="number" class="form-control" id="<?=$efector_remedio?>minimo" name="minimo" value="<?=$minimo?>">
            </div>
            
            <label for="maximo" class="col-sm-3 control-label">Maximo:</label>
            <div class="col-sm-3">
            <input type="number" class="form-control" id="<?=$efector_remedio?>maximo" name="maximo" value="<?=$maximo?>">
          </div>
        </div>
      
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" name="Guardar_Remedios" value="<?=$efector_remedio?>">
        Guardar Cambios</button>
      </div>
      
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


    <tr <?echo $linea?>>    
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
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
