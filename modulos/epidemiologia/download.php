<?php
require_once ("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

header("Content-Length: " . filesize ( $fichero ) ); 
header("Content-type: application/pdf"); 
header("Content-disposition: attachment; filename=".basename($fichero));
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
ob_clean();
flush();
readfile($fichero);
$link=encode_link("../fichero/comprobante_fichero.php",array("id"=>$id,"entidad_alta"=> $entidad_alta, "accion"=>"Se grabaron los registros"));
$ref="location.href='$link'";
$ref_1="window.location='$link'";
echo "<script language='javascript'>$ref</script>";
fin_pagina();// aca termino?>