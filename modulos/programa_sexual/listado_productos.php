<?php
require_once("../../config.php"); 
require_once ("add_libs.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

echo $html_header;?>

<style type="text/css"> 
.modal-body {max-height: 900px;}
</style>

<?$html_option='<option data-divider="true"></option>
                <option value="todos">Todos</option>
                <option data-divider="true"></option>';?>

<form name='form1' action='listado_productos.php' method='POST'>
  
<div class="container">
  <div class="col-md-12">
    
    <?if (!es_cuie($_ses_user['login'])) {?>
        <select name="id_efector" id="id_efector">
        <option value="-1" disabled selected>Seleccione el Efector</option>
        <?if (!$id_efector){echo $html_option;}
         else {
            if ($id_efector=="todos"){echo $html_option;}
            else {echo $html_option;
            $sql= "SELECT * from nacer.efe_conv where cuie='$cuie'";
            $res_efectores=sql($sql) or fin_pagina();
            $id_efector=$res_efectores->fields['id_efe_conv'];
            $nombre_efector=$res_efectores->fields['nombre'];
            $cuie=$res_efectores->fields['cuie'];?>
            <option value="<?=$id_efector?>"><?=$res_efectores->fields['cuie']." - ".$nombre_efector?></option>
            <?};
          };             
        $sql= "SELECT * from nacer.efe_conv order by nombre";
        $res_efectores=sql($sql) or fin_pagina();
        while (!$res_efectores->EOF){ 
          $id_efector=$res_efectores->fields['id_efe_conv'];
          $nombre_efector=$res_efectores->fields['nombre'];
          $cuie=$res_efectores->fields['cuie'];
          ?>
          <option value="<?=$id_efector?>"><?=$res_efectores->fields['cuie']." - ".$nombre_efector?></option>
          <?
          $res_efectores->movenext();
          };?>
        </select>
      <?}
       else {?>
          <input type="text" placeholder="Nombre del Efector" style="width:500px" disabled>
      
     <?}?>
    </div>  	    
    
    
  </div>
  

<div class="container">
  
  <div id="tabla_remedio"></div>
  
</div>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
