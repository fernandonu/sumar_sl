<?php
require_once("../../config.php");

if ($_POST['op']=="carga_remedio") {
  //if ($_POST['elegido']!="") {

  $efector_stock=$_POST['elegido'];
  $string_stock=explode("-",$efector_stock);
  $id_efector=$string_stock[0];
  $id_remedio=$string_stock[1];

  $inicial=$_POST['inicial'];
  $remito=$_POST['remito'];
  $clearing=$_POST['clearing'];
  $total_1=$_POST['total_1'];
  $u_entregadas=$_POST['u_entregadas'];
  $salida_clearing=$_POST['salida_clearing'];
  $salida_no_apto=$_POST['salida_no_apto'];
  $observaciones=$_POST['observaciones'];
  $salida_robo=$_POST['salida_robo'];
  $total_2=$_POST['total_2'];
  $final=$_POST['final_1'];
  $codigo_ir=$_POST['codigo_ir'];
  
  $minimo=$_POST['minimo'];
  $maximo=$_POST['maximo'];

  $sql_update="UPDATE programa_sexual.stock_producto SET 
      remito = $remito,
      clearing = $clearing,
      total_1 = $total_1,
      salida_clearing = $salida_clearing,
      salida_no_apto = $salida_no_apto,
      observaciones_no_apto = '$observaciones',
      salida_robo = $salida_robo,
      total_2 = $total_2,
      final = $final,
      codigo_ir = '$codigo_ir'
  WHERE id_efector=$id_efector and id_remedio=$id_remedio";

  sql($sql_update,"No se pudo actualizar la tabla de stock") or fin_pagina();
  
  $json_stock= array (
                  "id_efector"  =>  $id_efector,
                  "id_remedio"  =>  $id_remedio,
                   );
  echo json_encode($json_stock);
  } //del if

if ($_POST['op']=="cierra periodo")  {
  $id_efector=$_POST['elegido'];
  $consulta=$_POST['consulta'];
  $fecha_hoy=date("Y-m-d");
  
  $sql_insert="INSERT into programa_sexual.proceso_archivo (id_efector,fecha_corte,consultas) 
                values ($id_efector,'$fecha_hoy',$consulta)";
  sql($sql_insert) or fin_pagina();
  
  //actualizar los valores de la tabla stock_producto para cerrar el periodo y empezar un nuevo periodo
    
  $sql_remedios="SELECT * from programa_sexual.stock_producto where id_efector=$id_efector";
  $res_remedios=sql($sql_remedios) or fin_pagina();
  while (!$res_remedios->EOF){
  $id_remedio=$res_remedios->fields['id_remedio'];
  
  $sql_update1="UPDATE programa_sexual.stock_producto set inicial=final,
                remito=0,clearing=0,total_1=final,u_entregadas=0,salida_clearing=0,salida_robo=0,
                salida_no_apto=0,total_2=0,observaciones_no_apto='',codigo_ir=''
                where id_efector=$id_efector and id_remedio=$id_remedio";
  sql($sql_update1) or fin_pagina();
  $res_remedios->Movenext();
  }
    
  $json_stock= array (
                  "id_efector"  =>  $id_efector,
                  "consulta"  =>  $consulta,
                   );
  echo json_encode($json_stock);
  } 
?>
