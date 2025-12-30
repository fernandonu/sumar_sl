<?

require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

if ($_POST['importar']){
  //...........................para subir el archivo c................
    $path = MOD_DIR."/remedios/remitos/";
    $name = $_FILES["archivo"]["name"];   
    $temp = $_FILES["archivo"]["tmp_name"];
    $size = $_FILES["archivo"]["size"];
    $type = $_FILES["archivo"]["type"];
    $extensiones = array("txt");
    if ($name) {
      $name = strtolower($name);
      $ext = substr($name,-3);
      if ($ext != "txt") {
        Error("El formato del archivo debe ser TXT");
      }
      $name = "$name";
      $ret = FileUpload($temp,$size,$name,$type,$max_file_size,$path,"",$extensiones,"",1,0);
      if ($ret["error"] != 0) {
        Error("No se pudo subir el archivo");
      }
    }
  
    $filename = MOD_DIR."/remedios/remitos/".$name; 

      if (!$handle = fopen($filename, 'r')) {
           echo "No se Puede abrir ($filename)";
          exit;
      }
      $fecha_mig=date("Y-m-d");
      
      
  
//reseteos
 $contador_registros=0;
 $contador_error=0;
 $sql_reset="DELETE from remedios.error_remito";
 sql($sql_reset) or fin_pagina();       
//

$buffer = fgets($handle, 170);//cabecera

while (!feof($handle)) {
    $contador_registros+=1;
    $buffer = fgets($handle, 200);//datos
    $datos = explode ("\t",$buffer);
       
    $nro_remito=$datos[1];
    $nro_remito=str_replace('"','',trim($nro_remito));
    $slq_remito="SELECT * from remedios.remito where numero_remito='$nro_remito'";
    $res_remito=sql($slq_remito) or fin_pagina();
    
    $sissa=str_replace('"','',$datos[0]);
    $sql_efector="SELECT * from remedios.efectores where siisa='$sissa'";
    $res_efectores=sql($sql_efector,"El efector no se encuentra en base de datos") or fin_pagina();
    
    if ($res_efectores->RecordCount()==0){
      $contador_error+=1;
      $sql_error="SELECT nextval('remedios.error_remito_id_error_seq') as id_error";
      $rs_error=sql($sql_error) or fin_pagina();
      $id_error=$rs_error->fields['id_error'];
        
      $sql_e="INSERT into remedios.error_remito (id_error,codigo,mensage) 
              values($id_error,'$sissa','Efector no encontrado')";
      $res_sql_e=sql($sql_e) or fin_pagina();
      }
    else {
      if ($res_remito->RecordCount()==0) {
        $q1="select nextval('remedios.remito_id_remito_seq') as id_remito";
        $id_remito_1=sql($q1) or fin_pagina();
        $id_remito=$id_remito_1->fields['id_remito'];
      
        $fecha_hoy=date("Y-m-d H:i:s");
               
        $id_efector=$res_efectores->fields['id_efector'];
      
        $fecha_remito=$datos[2];
        $fecha_remito=fecha_db(trim(substr($fecha_remito,0,9)));
            
        $botiquines=intval(str_replace('"','',$datos[3]));
         
        $sql_remito_1="INSERT into remedios.remito (id_remito,numero_remito,fecha_remito,id_efector,
                      estado,cantidad_botiquines,fecha_ingreso) values ($id_remito,'$nro_remito','$fecha_remito',
                      $id_efector,'p',$botiquines,'$fecha_hoy')";
        sql($sql_remito_1) or fin_pagina();
        }
        else {
          $sql1="SELECT * from remedios.remito where numero_remito='$nro_remito'";
          $res_sql1=sql($sql1) or fin_pagina();
          $id_remito=$res_sql1->fields['id_remito'];         
        };
    
      $codigo_medicamento=substr($datos[4],5,3);
      $sql_2="SELECT * from remedios.remedio where codigo='$codigo_medicamento'";
      $res_2=sql($sql_2,"Error: Busqueda del Medicamento") or fin_pagina();
      if ($res_2->RecordCount()!=0) {
    
        $id_remedio=$res_2->fields['id_remedio'];
    
        $cantidad=intval(str_replace('"','',$datos[6]));

        $lote=$datos[7];
        $lote=str_replace('"','',$lote);

        $fecha_vencimiento=$datos[8];
        $fecha_vencimiento=fecha_db(trim(substr($fecha_vencimiento,0,9)));

        $gtin=str_replace('"','',$datos[9]);
    
        $q1="select nextval('remedios.remito_remedio_id_remito_remedio_seq') as id_remito_remedio";
        $id_remedio_1=sql($q1) or fin_pagina();
        $id_remito_remedio=$id_remedio_1->fields['id_remito_remedio'];
    
        $sql_remedio="INSERT into remedios.remito_remedio (id_remito_remedio,id_remito,id_producto,
                    cantidad,fecha_vencimiento,condicion,lote,gtin) 
                    values ($id_remito_remedio,$id_remito,$id_remedio,$cantidad,'$fecha_vencimiento','p','$lote','$gtin')";
        sql($sql_remedio) or fin_pagina();
        }
        else {
          $contador_error+=1;
          $sql_error="SELECT nextval('remedios.error_remito_id_error_seq') as id_error";
          $rs_error=sql($sql_error) or fin_pagina();
          $id_error=$rs_error->fields['id_error'];
        
          $sql_e="INSERT into remedios.error_remito (id_error,codigo,mensage) 
                values($id_error,'$datos[4]','Medicamento no encontrado')";
          $res_sql_e=sql($sql_e) or fin_pagina();
        }
      }
    }
  fclose($handle);
  
  echo "Archivo ingresado Correctamente - "; 
  echo "Registros Procesados = ".$contador_registros." - ";
  echo "Registros con Errores= ".$contador_error;
      
}    



echo $html_header;
?>
<form name=form1 action="importar_remito.php" method=POST enctype="multipart/form-data">
<table width="80%" class="bordes" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
<tr><td>
  <table width=100% align="center" class="bordes">
  <tr id="mo" align="center">
  <td colspan="3" align="center">
  <font size=+1><b>Importar Archivo</b></font>              
  </td>
  </tr>
  <tr>            
  <td align="center" colspan="2" id="ma">
  <b> Importacion Archivos Sistema de Remediar</b>
  </td>
  </tr>
  <tr>
  <td align="center">   
  <input name="archivo" type="file" style="width=250px" id="archivo">
  </td>
  <td align="left">   
  <input type=submit name="importar" value='Importar' style="width=100px">
  </td>
  </tr>
  </table>
  </td>
  </tr>
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>