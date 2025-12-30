<?php
require_once("../../config.php");
require_once("funciones_hc.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
$usuario1=$_ses_user['login'];
cargar_calendario();


if ($_POST['importar_hc']){

  //...........................para subir el JSON de Hc a SUMAR................
  //nuevo
  $path = MOD_DIR."/nacer/data/";
  $name = $_FILES["archivo"]["name"];		
  $temp = $_FILES["archivo"]["tmp_name"];
  $size = $_FILES["archivo"]["size"];
  $type = $_FILES["archivo"]["type"];
  $max_file_size = 20971520;
  $extensiones = array("json");
  if ($name) {
    $name = strtolower($name);
    $ext = substr($name,-4);
    if ($ext != "json") {
      Error("El formato del archivo debe ser JSON");
    }
    $name = "$name";
    $ret = FileUpload($temp,$size,$name,$type,$max_file_size,$path,"",$extensiones,"",1,0);
    if ($ret["error"] != 0) {
      Error("No se pudo subir el archivo");
    }
  }

  $filename = MOD_DIR."/nacer/data/".$name;	

  
  //$data = file_get_contents("data/migracion.json");
  $data = file_get_contents($filename);
  $products = json_decode($data,true);//convertir a array con TRUE como parametro
  $tipo_control=$_POST['tipo_control'];
  $periodo=$_POST['periodo'];
  
  $total_registros=0;
  $total_facturado=0;
  $sin_datos=0;
  $con_datos=0;
  $sin_codigo_facturable=0;
  $efector_no_encontrado=0;
  $benef_no_econtrado=0;
  $total_dup=0;
  $benef_inactivo=0;
  
  $comentario='Migracios desde el Sistema de HC'; 
  ?>
  <br>
  <br>
  <br>
  <table width="97%" class="bordes" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
  <tr><td>
  
  <tr id="mo" align="center">
  <td colspan="6" align="center">
  <font size=+1><b>Migracion de Historia Clinica --- Tipo Control :<?php echo '<b>'.$tipo_control.' --- </b>'.' ('.$name.')</b>'?></font>           
  </td>
  </tr>
  
  <table class="table table-striped" border=1 bordercolor=#E0E0E0>
  <thead>
    <tr>
      <th scope="col">DNI</th>
      <th scope="col">Nombre</th>
      <th scope="col">Apellido</th>
      <th scope="col">F.Nac.</th>
      <th scope="col">Tipo</th>
      <th scope="col">Act.</th>
      <th scope="col">Cuie</th>
      <th scope="col">Efector</th>
      <th scope="col">Fecha Control</th>
      <th scope="col">Peso</th>
      <th scope="col">Talla</th>
      <th scope="col">TA</th>
      <th scope="col">EG</th>
      <th scope="col">FUM</th>
      <th scope="col">FPP</th>
      <th scope="col">Per.Cef.</th>
      <th scope="col">IMC</th>
      <th scope="col">Cod.snomed</th>
      <th scope="col">Desc.API</th>
      <th scope="col">Cod.SUMAR</th>
      <th scope="col">Dat.Compl.</th>
      <th scope="col">Es Fact.</th>
      <th scope="col">Msg.</th>
      <th scope="col">Query</th>
    </tr>
  </thead>
    
  <?php  foreach ($products as $product) {
      
      $code_hc=($product{'healthCenter'}{'code'})?$product{'healthCenter'}{'code'}:'44444';
      $datos_efector=get_efector($code_hc);
      $cuie=$datos_efector['cuie'];

      
      $datos_info=get_info($product);
      $especialidad=$datos_info['especialidad'];
            
      $fecha_control=$datos_info['fecha_control'];
      $afidni=$product{'patient'}{'document'};
      $paciente=get_paciente($afidni,$fecha_control); 
      $edad_anios=$paciente['edad_anios'];     
      $product_data=$product{'data'};
      $datos_reportables=get_datos($product_data,$tipo_control,$fecha_control);
      $semana_gestacion=($datos_reportables['eg'])?$datos_reportables['eg']:0;
      $datos_nomenclador=get_nomenclador($datos_info,$tipo_control,$fecha_control,$edad_anios,$semana_gestacion);
      //echo $datos_nomenclador['query'].'<br>';
			
     
      if ($cuie) {//si ecuentra al efector y es activo
        if ($paciente) {
          if ($paciente['tipo_benef']=='na' and $paciente['activo']=='S'){//si encuentra al beneficiario y es activo
            $es_fact='OK';
            if ($datos_nomenclador['ok']) {//si encuentra un codigo facturable
              if(datos_completos ($tipo_control,$paciente,$datos_reportables)) {
                $datos_completos='OK';
                $con_datos+=1;
                
                $dato_comprobante = insertar_comprobante ($cuie,$datos_info,$paciente,$comentario,$datos_nomenclador);
                if ($dato_comprobante['ok']) {
                  insertar_prestacion($dato_comprobante['id_comprobante'],$cuie,$paciente,$datos_nomenclador,$datos_info,$datos_reportables,$comentario);
                  $trazadora=insertar_trazadora($tipo_control,$paciente,$datos_info,$cuie,$datos_reportables,$comentario);
                  $id_fichero=insertar_fichero ($cuie,$paciente,$datos_info,$datos_reportables,$comentario);
                  $total_facturado+=1;
                  $msg_rep = 'Compr.N° '.$dato_comprobante['id_comprobante'].' - '.'Trz. '.'<b>'.$trazadora['tabla'].'</b>'.' N° '.$trazadora['id_trazadora'].' - '.'Fich.N° '.$id_fichero;
              
                } else {
                    $msg_rep=$dato_comprobante['msg_duplicado'].'-'.$dato_comprobante['msg_uso_nomenclador'];
                    $total_dup+=1;
                  };              
              
              } else { 
                $datos_completos='NO'; 
                $msg_rep='Sin datos completos';             
                $sin_datos+=1;//reporte_faltan_datos_reportables($tipo_control,$especialidad,$afidni,$datos_reportables,$fecha_control);
                }
            
            } else {
                $sin_codigo_facturable+=1;
                //echo $datos_nomenclador['query'];
                $msg_rep = $datos_nomenclador['msg'];
                $r_query = $datos_nomenclador['query'];
              }
          }  
          
          if ($paciente['tipo_benef']=='na' and $paciente['activo']=='N') {
            $es_fact='NO';
            $benef_inactivo+=1;
            $msg_rep='Beneficiario NO activo';          
            if(datos_completos ($tipo_control,$paciente,$datos_reportables)) {
              $datos_completos='OK';
              $con_datos+=1;
            
              $trazadora=insertar_trazadora($tipo_control,$paciente,$datos_info,$cuie,$datos_reportables,$comentario);
              $id_fichero=insertar_fichero ($cuie,$paciente,$datos_info,$datos_reportables,$comentario);

              $msg_rep = 'Trz. '.'<b>'.$trazadora['tabla'].'</b>'.' N° '.$trazadora['id_trazadora'].' - '.'Fich.N° '.$id_fichero;
            
            } else {
              $sin_datos+=1;
              $datos_completos='NO';            
              }
          }
          
          if ($paciente['tipo_benef']=='nu' ) {
            $es_fact='NO';          
            
            if(datos_completos ($tipo_control,$paciente,$datos_reportables)) {
              $datos_completos='OK';            
              $con_datos+=1;
            
              $trazadora=insertar_trazadora($tipo_control,$paciente,$datos_info,$cuie,$datos_reportables,$comentario);
              $id_fichero=insertar_fichero ($cuie,$paciente,$datos_info,$datos_reportables,$comentario);

              $msg_rep = 'Trz. '.'<b>'.$trazadora['tabla'].'</b>'.' N° '.$trazadora['id_trazadora'].' - '.'Fich.N° '.$id_fichero;
            
            } else {
              $sin_datos+=1;
              $datos_completos='NO';            
            }
          }  
        
        } else {
            $benef_no_econtrado+=1;
            $msg_rep = 'Beneficiario NO encontrado';
            $es_fact='NO';
          }

      } else {
        $msg_rep = 'Efector NO conveniado';
        $efector_no_encontrado+=1; //reporte_efector_no_encontrado($product{'healthCenter'});
        $es_fact='NO';
        }
    
    ?>
    <tbody>
    <tr>
      <td><?php echo $afidni?></td>
      <td><?php echo $paciente['nombre'];?></td>
      <td><?php echo $paciente['apellido'];?></td>
      <td><?php echo $paciente['afifechanac'];?></td>
      <td><?php echo $paciente['tipo_benef'];?></td>
      <td><?php echo $paciente['activo'];?></td>
      <td><?php echo $cuie;?></td>
      <td><?php echo '<b>'.$datos_efector['nombre'].'</b>';?></td>
      <td><?php echo $fecha_control;?></td>
      <td><?php echo $datos_reportables['peso'];?></td>
      <td><?php echo $datos_reportables['talla'];?></td>
      <td><?php echo $datos_reportables['ta'];?></td>
      <td><?php echo $datos_reportables['eg'];?></td>
      <td><?php echo $datos_reportables['fum'];?></td>
      <td><?php echo $datos_reportables['fpp'];?></td>
      <td><?php echo $datos_reportables['perimetro_cefalico'];?></td>
      <td><?php echo $datos_reportables['imc'];?></td>
      <td><?php echo ($datos_info['snomed_code'])?$datos_info['snomed_code']:'Sin Codigo Snomed';?></td>
      <td><?php echo '<b>'.$datos_info['descripcion'].'</b>';?></td>
      <td><?php echo $datos_nomenclador['grupo'].$datos_nomenclador['codigo'].$datos_nomenclador['diagnostico'];?></td>
      <td <?php echo ($datos_completos=='OK')?"style='color:green'":"style='color:red'";?>><?php echo $datos_completos;?></td>
      <td <?php echo ($es_fact=='OK')?"style='color:green'":"style='color:red'";?>><?php echo $es_fact;?></td>
      <td><?php echo $msg_rep;?></td>
      <td><?php echo $r_query;?></td>
      </tr>
  
  <?php 
        
      $datos_aux = array(
        "cuie" => $cuie,
        "especialidad" => $especialidad,
        "fecha_control" => $fecha_control,
        "dni" => $afidni,
        "datos_completos" => $datos_completos,
        "es_fact" => $es_fact,
        "msg_rep" => $msg_rep,
        "r_query" =>$r_query
      );
     
      inserta_en_tabla_aux($name,$periodo,$datos_aux,$tipo_control,$paciente,$datos_reportables,$datos_nomenclador,$datos_info);
      
      $total_registros+=1;
      $msg_rep='';
      $es_fact='';} //foreach?>  
 </tbody>
 </table>   
  <br>
  <br>
  <table class="table table-bordered">
  <tr id="mo" align="center">
  <td colspan="6" align="center">
  <b>Resumen</b>           
  </td>
  </tr>

  <tr><td>Cantidad de Reg. Proc.</td><td><?php echo '<b>'.$total_registros.'</b>'?></td></tr>
  <tr><td>Total Facturados</td><td><?php echo '<b>'.$total_facturado.'</b>'?></td></tr>
  <tr><td>Total Duplicados y/o Uso Nomenclador</td><td><?php echo '<b>'.$total_dup.'</b>'?></td></tr>
  <tr><td>Efectores no Econtrados</td><td><?php echo '<b>'.$efector_no_encontrado.'</b>'?></td></tr>
  <tr><td>Beneficiarios no Activos</td><td><?php echo '<b>'.$benef_inactivo.'</b>'?></td></tr>
  <tr><td>Beneficiarios no Encontrados</td><td><?php echo '<b>'.$benef_no_econtrado.'</b>'?></td></tr>
  <tr><td>Total reg. C/datos Reportables</td><td><?php echo '<b>'.$con_datos.'</b>'?></td></tr>
  <tr><td>Total reg. S/datos Reportables</td><td><?php echo '<b>'.$sin_datos.'</b>'?></td></tr>
  <!--<h4><?php echo ('Sin codigo Facturable => '.$sin_codigo_facturable."<br>");?></h4>-->
  </table>
</table>

<?} //del POST 


echo $html_header;
?>

<form name=form1 action="migracion_hc.php" method=POST enctype="multipart/form-data">
<table width="80%" class="bordes" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
<tr><td>
  <?php if (!$_POST['importar_hc']){?>
  <table width=100% align="center" class="bordes">
  
	<tr id="mo" align="center">
    <td colspan="6" align="center">
    	<font size=+1><b>Migracion de Historia Clinica</b></font>      	      
    </td>
   </tr>
   	
	<?php if ($usuario1!='sebastian') {$disable='disabled';}
      else {$disable='';};?>
	 
	<tr>	           
  <td align="center" colspan="6" id="ma">
  <b> Migracion de HC a Sumar</b>
  </td>
  </tr>
  
  <tr>
  <td align="left">   
  <select name='tipo_control'>
      <option value="-1">Seleccione</option>
      <option value="Embarazadas">Embarazadas</option>
      <option value="Niños de 0 a 5">Niños de 0 a 5 años</option>
      <option value="Niños de 6 a 9">Niños de 6 a 9 años</option>
      <option value="Adolescentes">Adolescentes</option>
      <option value="Adultos de 20 a 64">Adultos de 20 a 64 años</option>
  </select></td>

  <td>
  <b>Periodo: </b>
  <input name="periodo" type="numeric" id='periodo' placeholder='AAAAMM'>
  </td>
  <td>
  <input name="archivo" type="file"  id="archivo">
  </td>
  <td>
  <input type=submit name="importar_hc" value='Importar HC' class="btn btn-primary"  <?php echo $disable;?>>
  </td>
  </table>
  <?php }?>    
</table>
</form>
</body>
</html>
<?php echo fin_pagina();// aca termino ?>
