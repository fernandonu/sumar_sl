<?php
 require_once ("../../config.php");
      $keyword = '%'.$_POST['keyword'].'%';
	  $id = $_POST['id'];

			$sql = "SELECT * FROM nacer.efe_conv
            where (upper(nombre) like upper('$keyword') or 
                  upper(cuie) LIKE  upper('$keyword') )
            order by nombre";
      $res=sql($sql) or fin_pagina();

     foreach ($res as $rs) {
            // put in bold the written text
            $country_name = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['nombre']);
            // add new option
          echo '<li onclick="set_item_efec(\''.str_replace("'", "\'", $rs['nombre']).'\',\''.$rs['cuie'].'\')">'.$country_name.'</li>';
      }

       
?>