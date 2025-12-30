<? 
require_once ("../../config.php");
$op=$_POST['op'];
if ($op=="cambio_parametros"){
  $minima=$_POST['minima'];
  $adecuada=$_POST['adecuada'];
  $id_parametro=$_POST['id_parametro'];
  
  $q="UPDATE cobertura_efectiva.parametros SET minima=$minima, adecuada=$adecuada
      WHERE id_parametro=$id_parametro";
  sql($q) or fin_pagina();
   
}

$grupoetareo=$_POST["grupoetareo"];
 if ($grupoetareo!="-1") {
        $query = "SELECT distinct linea_de_cuidado FROM cobertura_efectiva.parametros where poblacion='$grupoetareo'";
        $res_query = sql($query) or die();?>
        <label for="linea_cuidado">Lineas de Cuidado: </label>
        <select name="linea_cuidado" id="linea_cuidado">
          <option value="-1">seleccione</option>
          <?while (!$res_query->EOF){?>
            <option value="<?=$res_query->fields['linea_de_cuidado']?>"><?=$res_query->fields['linea_de_cuidado']?></option>
            <?$res_query->Movenext();
          }?>
        </select>
<?};//del if

?>