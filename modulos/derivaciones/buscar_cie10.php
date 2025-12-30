<?php 

 require_once ("../../config.php");
      $keyword = '%'.$_POST['keyword'].'%';
       $sql = "SELECT id10,dec10
                FROM 
                nacer.cie10
            where id10 ilike '$keyword' or dec10 iLIKE  '$keyword'
            order by id10";
      $res=sql($sql) or fin_pagina();

     foreach ($res as $rs) {
          echo '<li onclick="set_item(\''.$rs['dec10'].'\',\''.$rs['id10'].'\')">'.$rs['id10'].'-'.$rs['dec10'].'</li>';
      }
       
?>