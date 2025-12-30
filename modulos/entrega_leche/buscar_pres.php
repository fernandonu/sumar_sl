<?php
 require_once ("../../config.php");
      $keyword = '%'.$_POST['keyword'].'%';

$login=new user($_ses_user['login']);
$id=$usuario->get_id_usuario();

      if (es_cuie($_ses_user['login'])){
                  $sql= "SELECT cuie, nombre, com_gestion from nacer.efe_conv where cuie='$login'";
                   }                  
                  else{
                  $usuario1=$_ses_user['id'];
                  $sql= "SELECT nacer.efe_conv.nombre, nacer.efe_conv.cuie, com_gestion 
                      from nacer.efe_conv 
                      join sistema.usu_efec on (nacer.efe_conv.cuie = sistema.usu_efec.cuie) 
                      join sistema.usuarios on (sistema.usu_efec.id_usuario = sistema.usuarios.id_usuario) 
                      where sistema.usuarios.id_usuario = '$id' and upper(nombre) like upper('$keyword') OR upper(cuie) like upper('$keyword')
                     order by nombre";
                   }               
                    
      $res=sql($sql) or fin_pagina();


     foreach ($res as $rs) {
            // put in bold the written text
            $country_name = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['nombre']);
            // add new option
          echo '<li onclick="set_item(\''.str_replace("'", "\'", $rs['nombre']).'\',\''.$rs['cuie'].'\')">'.$country_name.'</li>';
      }
       
?>