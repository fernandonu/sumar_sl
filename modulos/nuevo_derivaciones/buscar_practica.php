<?php 

 require_once ("../../config.php");
      $keyword = '%'.$_POST['keyword'].'%';
       $sql = "SELECT *
                FROM 
                derivacion_general.practica
            where descripcion ilike '$keyword'
            order by id_practica";
      $res=sql($sql) or fin_pagina();

     foreach ($res as $rs) {
          echo '<li onclick="set_item_practica(\''.$rs['descripcion'].'\',\''.$rs['id_practica'].'\')">'.$rs['id_practica'].'-'.$rs['descripcion'].'</li>';
      }
       
?>