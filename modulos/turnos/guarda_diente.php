<?php
require_once ("../../config.php");

$id1=$_POST['id1'];
$id2=$_POST['id2'];
$id3=$_POST['id3'];
$id4=$_POST['id4'];
$id5=$_POST['id5'];

if ($id5=='guardadiente'){

	$db->StartTrans();	    
		/*cargo los log*/ 
		$q_1="select nextval('ficha_consumo.odontograma_id_odontograma_seq') as id_log";
		$id_log=sql($q_1) or fin_pagina();
		$id_log=$id_log->fields['id_log'];	    
		$log="insert into ficha_consumo.odontograma
			   (id_odontograma,id_paciente,pieza,cara,tratamiento,fecha_pres) 
				values ($id_log, '$id1','$id2','$id3','$id4',CURRENT_DATE)";
		sql($log) or fin_pagina();
	$db->CompleteTrans(); 

	$resultado = "Se Grabo el Registo. ";

echo $resultado;
}

if ($id5=='buscadiente'){

    $res="select * FROM ficha_consumo.odontograma where id_paciente='$id1' and pieza='$id2'";
    $res=sql($res) or fin_pagina();
  
  if ($res->recordCount()!=0){
    $cara="";
    while (!$res->EOF){
      $cara.=trim($res->fields['cara']);
      $res->movenext();
    }
    echo $cara;
  }
  else {
    echo $res->recordCount();
  }
}

?>
