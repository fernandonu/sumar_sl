<?php
require_once ("../../config.php");
    $keyword = '%'.$_POST['keyword'].'%';
    $id = $_POST['id'];

    $sql = "SELECT * 
                    FROM nacer.efe_conv 
                    INNER JOIN sistema.usu_efec ON sistema.usu_efec.cuie = nacer.efe_conv.cuie
                    where  sistema.usu_efec.id_usuario=$id and upper(nombre) like upper('$keyword') 
                order by nombre ASC";
      //$res=sql($sql) or fin_pagina();

      $res=$db->Execute ($sql) or die ("Error: no puedo ejecutar 01");
     
      foreach ($res as $rs) {
            // put in bold the written text
        $country_name = $rs['nombre'];
            // add new option
  echo '<li onclick="set_item(\''.str_replace("'", "\'", $rs['nombre']).'\',\''.$rs['id_efe_conv'].'\')">'.$country_name.'</li>';      }
      
?>