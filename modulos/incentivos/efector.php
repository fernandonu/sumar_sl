<?
require_once ("../../config.php");

$op = $_POST['op'];

if (es_cuie($_ses_user['login'])) {
    $cuie=$_ses_user['login'];
    $sql_efe="SELECT * from nacer.efe_conv where cuie='$cuie'";
    $res_efe=sql($sql_efe,"No se pudieron traer los datos del efector") or fin_pagina();
    $id_efector=$res_efe->fields['id_efector'];
    $nombre=$res_efe->fields['nombre'];
    $domicilio=$res_efe->fields['domicilio'];
    $departamento=$res_efe->fields['dpto_nombre'];
    $localidad=$res_efe->fields['localidad'];
    $cod_pos=$res_efe->fields['cod_pos'];
    $cuidad=$res_efe->fields['cuidad'];
    $referente=$res_efe->fields['referente'];
    $tel=$res_efe->fields['tel'];
}
else $cuie=-1;

if ($op=="id_efector"){
  
  /*$id_efector=$_POST['id_efector'];
  $ref = encode_link("incentivos_personal.php",array("id_efector"=>$id_efector));
  echo "<SCRIPT>window.location='$ref';</SCRIPT>";*/
  
  
  $id_efector=$_POST['id_efector'];
  $sql_efe="SELECT * from nacer.efe_conv where id_efe_conv=$id_efector";
    $res_efe=sql($sql_efe,"No se pudieron traer los datos del efector") or fin_pagina();
    $id_efector=$res_efe->fields['id_efector'];
    $cuie=$res_efe->fields['cuie'];
    $nombre=$res_efe->fields['nombre'];
    $domicilio=$res_efe->fields['domicilio'];
    $departamento=$res_efe->fields['dpto_nombre'];
    $localidad=$res_efe->fields['localidad'];
    $cod_pos=$res_efe->fields['cod_pos'];
    $cuidad=$res_efe->fields['cuidad'];
    $referente=$res_efe->fields['referente'];
    $tel=$res_efe->fields['tel'];
    $link_excel = "$link=encode_link('incentivos_excel.php',array('id_efector'=>".$id_efector."));?>";
    $link_excel .="<button type='button' class='btn btn-primary btn-sm' name='reporte_excel'"; 
    $link_excel .="onclick='window.open('<?=$link?>')'>";
    $link_excel .="Reporte EXCEL</button>";
    
    $json_stock= array (
                  "id_efector"  =>  $id_efector,
                  "cuie"  =>  $cuie,
                  "nombre"  =>  $nombre,
                  "domicilio"  =>  $domicilio,
                  "departamento"  =>  $departamento,
                  "localidad"  =>  $localidad,
                  "cod_pos"  =>  $cod_pos,
                  "cuidad"  =>  $cuidad,
                  "referente"  =>  $referente,
                  "tel"  =>  $tel,
                  "link_excel" => $link_excel               
                   );
  echo json_encode($json_stock);
}
?>