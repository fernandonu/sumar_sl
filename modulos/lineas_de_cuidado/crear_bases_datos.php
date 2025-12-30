<?
require_once ("../../config.php");
require_once ("funciones_csi.php");
require_once ("add_libs.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();


if ($_POST['crea_bases']){
  
  
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);
  $grupoetareo=$_POST['grupoetareo'];
  $linea_cuidado=$_POST['linea_cuidado'];
  $cuie=$_POST['cuie'];
  crear_bases_de_datos($grupoetareo,$linea_cuidado,$fecha_desde,$fecha_hasta,$cuie);
  };

echo $html_header;
?>

<div class="container">
<form name='form1' action='crear_bases_datos.php' method='POST'>

<div class="row" style="border:0.5px solid; border-color:#EAEAEA">
  <div class="col-md-12">
    <h3 align="center"><b><font color="blue"> Modulo Lineas de Cuidado</font></b>
    <font size="1.5px" color="black">Ver.1.0</font>
    </h3>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
  <label>Grupo Etareo: </label>
  <select name="grupoetareo" id="grupoetareo">
    <option value="-1">Seleccione</option>
    <option value="ninios_0_1_anio">Niños de 0 a 1 Años</option>
    <option value="ninios_1_2_anio">Niños de 1 a 2 Años</option>
    <option value="ninios_2_5_anio">Niños de 2 a 5 Años</option>
    <option value="ninios_6_9_anio">Niños de 6 a 9 Años</option>
    <option value="ninios_6_anio">Niños de 6 Años</option>
    <option value="adolescente_11_anio">Niños de 11 Años</option>
    <option value="adolescente_10_19_anio">Niños de 10 a 19 Años</option>
    <option value="mujeres_20_64_anio">Mujeres de 20 a 64 Años</option>
    <option value="mujeres_25_64_anio">Mujeres de 25 a 64 Años</option>
    <option value="mujeres_49_64_anio">Mujeres de 49 a 64 Años</option>
    <option value="hombres_20_64_anio">Hombres de 20 a 64 Años</option>
    <option value="hombres_40_64_anio">Hombres de 40 a 64 Años</option>
    <option value="hombres_50_64_anio">Hombres de 50 a 64 Años</option>
  </select>
  </div>
  
  <div class="col-md-4">
  <label>Fecha Desde: </label>
  <input type="text" id="fecha_desde" name="fecha_desde" value='<?=$fecha_desde?>' size=15 placeholder="Ingrese Fecha Desde"><?=link_calendario("fecha_desde");?>
  </div>

  <div class="col-md-4">
  <label>Fecha Hasta: </label>
  <input type="text" id="fecha_hasta" name="fecha_hasta" value='<?=$fecha_hasta?>' size=15 placeholder="Ingrese Fecha Hasta"><?=link_calendario("fecha_hasta");?>
  </div>
</div>
<div>&nbsp</div>
<div>&nbsp</div>

<div class="row">
  <div class="col-md-6">
  <label>Efector: </label>
  <select name="cuie">
  <option value="todos">Todos</option>
                
  <?$sql= "SELECT * from nacer.efe_conv order by cuie";
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
  <div class="col-md-6" id="linea_cuidado-div"></div>
</div>

<div>&nbsp</div>
<div>&nbsp</div>
<div class="col-md-12" align="center">
<input type="submit" name="crea_bases" value='Muestra' class="btn btn-success" onclick="return control_entrada()">
  <div>
  </BR>
  </div>
</div>
</form>

<div class="row">
<div class="col-md-12" id="grafico" name="grafico">
  <?if ($accion=='Se Creo la tabla cobertura_efectiva'){
    
    $tabla_historial_1="hist_".$tabla_base_1;
    $ref = encode_link("detalle_linea_de_cuidado.php",array("tipo_linea"=>$linea_cuidado_1,"tabla_base"=>$tabla_base_1,"tabla_historial"=>$tabla_historial_1));
    $onclick="window.open('$ref' , '_blank');";?>
   
  <div align="center" onclick="<?=$onclick?>" style="border:1px solid; border-color:#EF9696; background-color:#F5D0A9; cursor: pointer">
          <? 
          $valores=valores_csi($tabla_base_1,$linea_cuidado_1);
          $madre=$valores['madre'];
          $minima=$valores['minima'];
          $adecuada=$valores['adecuada'];
          
          ?>
          <font size=2 color= red>
          <b>Linea de Cuidado: </b> <input value="<?=$linea_cuidado_1?>" readonly>
          <b>Grupo Etareo: </b> <input value="<?=$tabla_base_1?>" readonly>
          -- Madres = <b><input size="1px" id="madre" value="<?=($madre)?$madre:0?>" readonly></input></b> 
          -- Minima = <b><input id="minima" size="1px" value="<?=($minima)?$minima:0?>" readonly></input></b>
          -- Adecuada = <b><input id="adecuada"  size="1px" value="<?=($adecuada)?$adecuada:0?>" readonly></input></b> 
          </font>
  </div>   
  <div>
    
    &nbsp
  </div>
  <div class="col-md-12">
    <div class="col-md-6" align="center" style="border:0.5px solid; border-color:#EAEAEA">
    <?$q_parametros="SELECT * from cobertura_efectiva.parametros 
      where poblacion='$tabla_base_1' 
      and linea_de_cuidado='$linea_cuidado_1'
      order by 1";
      $res_parametros=sql($q_parametros) or fin_pagina();?>
      <div>
      <label>Parametros de Medicion</label>
      </div>
      
      <? $i=0;
         while (!$res_parametros->EOF) {
          $id_parametro=$res_parametros->fields['id_parametro'];
          $prestacion=$res_parametros->fields['prestacion'];
          $minima_p=$res_parametros->fields['minima'];
          $adecuada_p=$res_parametros->fields['adecuada'];
          $tipo=$res_parametros->fields['tipo_prestacion'];
          if ($tipo=='MADRE') $color='#6B75F5';
            else $color='#5CD349';
          
        ?>
      <form>
      <input type="hidden" id="id_parametro<?=$i?>" value="<?=$id_parametro?>"></>
      <label><font color="<?=$color?>"><?=$prestacion?></font></label>
      <input type="numeric" id="minima<?=$i?>" value="<?=$minima_p?>"></>
      <input type="numeric" id="adecuada<?=$i?>" value="<?=$adecuada_p?>"></>
      </form>
      <?$res_parametros->Movenext();
      $i++;
      }?>
      <div>&nbsp</div>
      <input type="hidden" id="indice" value="<?=$i?>"></>
      <input type="button" id="guardar" value="Cambiar Parametros" class="btn btn-primary">
      </div>
    <div class="col-md-4" align="center">
      <div class="grafica">
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
    
    <!--graficos viejos-->
    <!--<? $link_s=encode_link("lineas_grafico.php",array("madre"=>$madre,"minima"=>$minima,"adecuada"=>$adecuada,"tamaño"=>"small","nombre"=>"control_salud"));
    echo "<a target='_blank'><img src='$link_s'  border=0 align=top></a>\n";?>-->
  </div>
  
</div>
  
<?}?>
</div>
</div>



</div>
</body>
</html>
<?echo fin_pagina();// aca termino ?>