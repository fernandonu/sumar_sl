<?
/*
$Author: Seba $
$Revision: 3.0 $
$Date: 2016/12/28 $
*/
require_once ("../../config.php");
require_once ("add_libs.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

if ($_POST['genera_excel']) {
  
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);
  $cuie=$_POST['cuie'];
  
  $link=encode_link("auditoria_excel.php",array("cuie"=>$cuie, "fecha_desde"=>$fecha_desde, "fecha_hasta"=>$fecha_hasta));?>
  
  <script>
  window.open('<?=$link?>');
  </script>
  <?
}


if ($_POST['traer_datos']){
  
  
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);
  $cuie=$_POST['cuie'];
  $grupo=$_POST['grupo'];
  $id_nomenclador=$_POST['practica'];
  
  if ($grupo!='-1' or $grupo==NULL) $str_grupo=" and grupo='".$grupo."'";
    else $str_grupo='';
  
  if ($id_nomenclador=='-1' or $id_nomenclador==NULL) $str_codigo='';
    else $str_codigo=" and id_nomenclador=".$id_nomenclador;
  
  if ($cuie=='todos') $str_cuie='';
    else $str_cuie=" and comprobante.cuie='".$cuie."'";
    
  $sql="SELECT id_nomenclador,grupo,codigo,descripcion,count(id_nomenclador) as cantidad 
          from facturacion.prestacion 
          inner join facturacion.comprobante using (id_comprobante)
          inner join facturacion.nomenclador using (id_nomenclador)
          where fecha_comprobante between '$fecha_desde' and '$fecha_hasta'".
          $str_cuie.
          $str_grupo.
          $str_codigo.
          "and comprobante.marca=0
          and comprobante.id_smiafiliados is not null
          group by grupo,codigo,descripcion,id_nomenclador
          order by 5 DESC
          limit 10";
      
      
  
      
  $res_sql=sql($sql) or fin_pagina();
  $accion="Datos Obtenidos";
};

echo $html_header;
?>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

<div class="container">
<form name='form1' action='auditoria_fact.php' method='POST'>

<div class="row" style="border:0.5px solid; border-color:#F6F5F5">
  <div class="col-md-12">
    <h3 align="center"><b><font color="blue">Auditoria de Facturacion</font></b>
    <font size="1.5px" color="black">Ver.4.0</font>
    </h3>
  </div>
</div>

<div class="row">
   
  <div class="col-md-3">
  <label>Fecha Desde: </label>
  <input type="text" id="fecha_desde" name="fecha_desde" value='<?=fecha_db($fecha_desde)?>' size=14 placeholder="Ingrese Fecha Desde" readonly><?=link_calendario("fecha_desde");?>
  </div>

  <div class="col-md-3">
  <label>Fecha Hasta: </label>
  <input type="text" id="fecha_hasta" name="fecha_hasta" value='<?=fecha_db($fecha_hasta)?>' size=14 placeholder="Ingrese Fecha Hasta" readonly><?=link_calendario("fecha_hasta");?>
  </div>
  
  <div class="col-md-6">
  <label>Efector: </label>
  <select name="cuie" id="cuie" class="selectpicker" data-show-subtext="true" data-live-search="true" >
  <?if (!$cuie){?>
      <option value="Seleccione">Seleccione</option>
      <option data-divider="true"></option>
      <option value="todos">Todos</option>
      <option data-divider="true"></option>
   <?}
   else {
      if ($cuie=="todos"){?>
          <option data-divider="true"></option>
          <option value="todos">Todos</option>
          <option data-divider="true"></option>
          <?}
      else {?>
      <option data-divider="true"></option>
      <option value="todos">Todos</option>
      <option data-divider="true"></option>
      <?$sql= "SELECT * from nacer.efe_conv where cuie='$cuie'";
      $res_efectores=sql($sql) or fin_pagina();
      $id_efector=$res_efectores->fields['id_efe_conv'];
      $nombre_efector=$res_efectores->fields['nombre'];
      $cuie=$res_efectores->fields['cuie'];?>
      <option value="<?=$cuie?>"><?=$res_efectores->fields['cuie']." - ".$nombre_efector?></option>
      <?};
    };             
  $sql= "SELECT * from nacer.efe_conv order by nombre";
  $res_efectores=sql($sql) or fin_pagina();
  while (!$res_efectores->EOF){ 
    $id_efector=$res_efectores->fields['id_efe_conv'];
    $nombre_efector=$res_efectores->fields['nombre'];
    $cuie=$res_efectores->fields['cuie'];
    ?>
    <option value="<?=$cuie?>"><?=$res_efectores->fields['cuie']." - ".$nombre_efector?></option>
    <?
    $res_efectores->movenext();
    };?>
  </select>
  </div>
</div>
<div>&nbsp</div>
<div>&nbsp</div>


<div class="row">
   
  <div class="col-md-3" id="practicas-div"></div>
    
</div>
  

<div>&nbsp</div>
<div>&nbsp</div>

<div class="row">
   
  <div class="col-md-9" id="practicas_pres-div"></div>
  
</div>

<div>&nbsp</div>
<div>&nbsp</div>
<div class="col-md-12" align="center">
<input type="submit" name="traer_datos" value='Muestra' class="btn btn-success" onclick="return control_entrada()">

<input type="submit" class="btn btn-warning" name="genera_excel" value="Genera Excel" onclick="return control_entrada()">
  <div>
  </BR>
  </div>
</div>
</form>

<div class="row">
  <div class="col-md-12" id="grafico" name="grafico">
    <?if ($accion=='Datos Obtenidos'){
      $cuie=$_POST['cuie'];?>
     
    <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
            <table class="table table-striped">
              <tr>
                <!--<td align="center"><b>Id</b></td>-->
                <td align="center"><b>Grupo</b></td>
                <td align="center"><b>Codigo</b></td>
                <td align="center"><b>Descripcion</b></td>
                <td align="center"><b>Cantidad</b></b></td>
              </tr>
             
            <?$i=1;
              $codigo = "codigo".$i;
              $cantidad = "cantidad".$i;
              //$id_nomenclador = "id_nomenclador".$i;
              $descripcion = "descripcion".$i;
            while (!$res_sql->EOF) {
              $ref = encode_link("detalle_auditoria.php",array("cuie"=>$cuie,"id_nomenclador"=>$res_sql->fields['id_nomenclador'],"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
              $onclick="window.open('$ref' , '_blank');";?>
             <tr onclick="<?=$onclick?>">
             <!--<td><?=$res_sql->fields['id_nomenclador']?></td>-->
             <td><?=$res_sql->fields['grupo']?></td>
             <td><?=$res_sql->fields['codigo']?></td>
             <input type="hidden" id="<?=$codigo?>" value="<?=$res_sql->fields['codigo']?>">
             <td><?=$res_sql->fields['descripcion']?></td>
             <td align="center"><?=$res_sql->fields['cantidad']?></td>
             <input type="hidden" id="<?=$cantidad?>" value="<?=$res_sql->fields['cantidad']?>">
             <!--<input type="hidden" id="<?=$id_nomenclador?>" value="<?=$res_sql->fields['id_nomenclador']?>">-->
             <input type="hidden" id="<?=$descripcion?>" value="<?=$res_sql->fields['descripcion']?>">
             </tr>
            <?$i++;
            $codigo = "codigo".$i;
            $cantidad = "cantidad".$i;
            //$id_nomenclador = "id_nomenclador".$i;
            $descripcion = "descripcion".$i;
            $res_sql->MoveNext();  
            }?>
           <input type="hidden" id="i" value="<?=$i?>">
           
           </table>
      </div>   
  </div>
</div>
    
&nbsp

<div class="row">  
  <h1><font size=3 color= red><b>Grafico</b></font></h1>
  <div align="center">
    <div  class="col-md-12" id="grafica">
      <span id="spinner" class="fa fa-spin fa-3x"></span>
      <div id="chart"></div>
      <button id="bar" type="button" class="btn btn-success">
      <span class="fa fa-bar-chart"></span> Gráfica de barras
      </button>
      <button id="pie" class="btn btn-success">
      <span class="fa fa-pie-chart"></span> Gráfica circular
      </button>
      <button id="donut" class="btn btn-success">
      <span class="fa fa-circle-o-notch"></span> Gráfica de rosca
      </button>
      <script src="app_grafico.js" type="text/javascript" charset="utf-8"></script>
    </div>
  </div>
</div>
<?}?>
</div>
</div>
</div>
</body>
</html>
<?echo fin_pagina();// aca termino ?>