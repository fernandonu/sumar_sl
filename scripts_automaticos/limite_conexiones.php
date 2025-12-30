<?

include("funciones_generales.php");

$sql = "select * from pg_stat_activity";
$result = $db->Execute($sql) or die("Error borrando las sesiones\n");

if($result->RecordCount()>95){
	$ret='ALERTA DE CONEXIONES!!!! ----';
	While (!$result->EOF){
		$ret.=$result->fields[0].' '.$result->fields[1].' '.$result->fields[2].' '.$result->fields[3].' '.$result->fields[4].' '.$result->fields[5].' ';
		$ret.=$result->fields[6].' '.$result->fields[7].' '.$result->fields[8].' '.$result->fields[9].' '.$result->fields[10].' ';
		$ret.=$result->fields[11].' '.$result->fields[12].' ';
		$ret.='--FIN REGISTRO.--';
		$result->MoveNext();
	}	
	$para = "fernandonu@gmail.com";
	$asunto = "Limite de conexiones";
	enviar_mail_html($para,$asunto,$ret,'','','',0); 
	$para = "seba_cyb1202@hotmail.com";
	enviar_mail_html($para,$asunto,$ret,'','','',0);
	$para = "gantonacci@gmail.com";
	enviar_mail_html($para,$asunto,$ret,'','','',0);
	$sql = "select pg_terminate_backend(procpid) from pg_stat_activity 
			where  current_query = '<IDLE>' and query_start < current_timestamp - interval '5 minutes'";
	$result = $db->Execute($sql) or die("Error borrando las sesiones\n");
}
?>