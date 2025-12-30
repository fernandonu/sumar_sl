<?php
require_once("../../config.php");
require_once ("clases/HCPerinatal.php"); 
require_once ("clases/ConsultaPrenatal.php"); 
require_once ("clases/ControlParto.php"); 
require_once ("clases/ControlPuerperio.php");
require_once ("clases/VariablesLibres.php");

require_once ("clases/LotesProceso.php");
$lotesProceso = new LotesProceso();

// texto periodo separando mes y año
$desde = explode(' ',$_POST['desde']);
$hasta = explode(' ',$_POST['hasta']);
$efector = $_POST['efector'];
// convertir mes a nro
$mesDesde = array_search($desde[0], $lotesProceso->meses);
$mesHasta = array_search($hasta[0], $lotesProceso->meses);

// calcular ultimo dia del mes para el periodo hasta
$mes = mktime( 0, 0, 0, $mesHasta+1, 1, $hasta[1] ); 
$ultdia = intval(date("t",$mes));
// periodo para guardar el lote y fecha para buscar fichas
$periodoDesde = $desde[1] . str_pad($mesDesde+1, 2 , "0",'STR_PAD_LEFT');
$periodoHasta = $hasta[1] . str_pad($mesHasta+1, 2 , "0",'STR_PAD_LEFT') ;
$fechaDesde = $desde[1] .'-'. str_pad($mesDesde+1, 2 , "0",'STR_PAD_LEFT') .'-'. '01';
$fechaHasta = $hasta[1] .'-'. str_pad($mesHasta+1, 2 , "0",'STR_PAD_LEFT') .'-'. str_pad($ultdia, 2 , "0",'STR_PAD_LEFT');

// FICHAS PARA PROCESAR
$HCPerinatal = new HCPerinatal();
$fichas = $HCPerinatal->getFichasByPeriodo($fechaDesde, $fechaHasta, $efector);
if($fichas->fields){
    // creacion de nueva base para procesar los datos
    $folder_path = MOD_DIR . "/sip_clap/sip_base/";
    $base_sip = $folder_path . 'sip_clap_base.mdb';
    $sip_envio = 'sip_clap_envio.mdb';
    $envio_path = $folder_path . $sip_envio;
    if (file_exists($base_sip)) {
        // crear base nueva para exportar
        copy($base_sip, $envio_path);
        //crear conexion 
        $access = ADONewConnection("ado_access");
        //$access->debug = 1;
        $myDSN = 'PROVIDER=Microsoft.Jet.OLEDB.4.0;'
                . 'DATA SOURCE=' . $envio_path . ';';

        $cnx = $access->Connect($myDSN) || die('fallo al conectar');
        if ($cnx) {
            // variables para proceso
            $version = '4.0.2';
            $fecha = date("Y-m-d");
            $hora = date("H:i:s");
            $user = $_ses_user['id'];
            $rllbck = 0;
            $nuevas = 0;
            $reproc = 0;

            // comenzar tansaccion
            try {
                sql("BEGIN");
                $total=$fichas->numRows();
                $j=0;
                foreach ($fichas as $ficha) {
                    $hc = $HCPerinatal->getHcPerinatalById($ficha['id_hcperinatal']);
                    if( $hc->getProcesado() )  $reproc += 1;
                    else   $nuevas+=1;
                    // id para nivel_01, nivel_05 y nivel_06
                    $id = $HCPerinatal->getPadL($hc->getVar0018(),20);
                    $id .= $HCPerinatal->getPadL($hc->getVar0019(),19);
                    $id .= $HCPerinatal->getPadL($hc->getVar0040(),2);
                    $id1 = $id.$hc->getVar0286();
                    if($j==43){
                        $l=10;
                    }
                    // TABLA nivel_01 
                    $var0006 = trim($hc->getVar0006())=='' ? 'NULL' : "'".$hc->getVar0006()."'";
                    $var0051 = trim($hc->getVar0051())=='' ? 'NULL' : "'".$hc->getVar0051()."'";
                    $var0057 = trim($hc->getVar0057())=='' ? 'NULL' : "'".$hc->getVar0057()."'";
                    $var0058 = trim($hc->getVar0058())=='' ? 'NULL' : "'".$hc->getVar0058()."'";
                    $var0183 = trim($hc->getVar0183())=='' ? 'NULL' : "'".$hc->getVar0183()."'";
                    $var0192 = trim($hc->getVar0192())=='' ? 'NULL' : "'".$hc->getVar0192()."'";
                    $var0284 = trim($hc->getVar0284())=='' ? 'NULL' : "'".$hc->getVar0284()."'";
                    $var0425 = trim($hc->getVar0425())=='' ? 'NULL' : "'".$hc->getVar0425()."'";
                    $var0379 = trim($hc->getVar0379())=='' ? 'NULL' : "'".$hc->getVar0379()."'";
                    
                    $variables=array(
                        //Fechas
                        "var_0006"  => strlen($hc->getVar0006()) != 10 || trim($hc->getVar0006())=='' ? 'NULL' : "'".$hc->getVar0006()."'",
                        "var_0051"  => strlen($hc->getVar0051()) != 10 || trim($hc->getVar0051())=='' ? 'NULL' : "'".$hc->getVar0051()."'",
                        "var_0057"  => strlen($hc->getVar0057()) != 10 || trim($hc->getVar0057())=='' ? 'NULL' : "'".$hc->getVar0057()."'",
                        "var_0058"  => strlen($hc->getVar0058()) != 10 || trim($hc->getVar0058())=='' ? 'NULL' : "'".$hc->getVar0058()."'",
                        "var_0183"  => strlen($hc->getVar0183()) != 10 || trim($hc->getVar0183())=='' ? 'NULL' : "'".$hc->getVar0183()."'",
                        "var_0192"  => strlen($hc->getVar0192()) != 10 || trim($hc->getVar0192())=='' ? 'NULL' : "'".$hc->getVar0192()."'",
                        "var_0284"  => strlen($hc->getVar0284()) != 10 || trim($hc->getVar0284())=='' ? 'NULL' : "'".$hc->getVar0284()."'",
                        "var_0425"  => strlen($hc->getVar0425()) != 10 || trim($hc->getVar0425())=='' ? 'NULL' : "'".$hc->getVar0425()."'",
                        "var_0379"  => strlen($hc->getVar0379()) != 10 || trim($hc->getVar0379())=='' ? 'NULL' : "'".$hc->getVar0379()."'",
                        // Texto 
                        "ID01"     => "'".$id1."'",                                                        // Texto:  43                       
                        "ID02"     => "'".$id1."'",                                                        // Texto:  43                     
                        "var_0001" => strlen($hc->getVar0001()) > 20 ? 'NULL' : "'".$hc->getVar0001()."'", // Texto:  20  
                        "var_0002" => strlen($hc->getVar0002()) > 20 ? 'NULL' : "'".$hc->getVar0002()."'", // Texto:  20
                        "var_0003" => strlen($hc->getVar0003()) > 50 ? 'NULL' : "'".$hc->getVar0003()."'", // Texto:  50
                        "var_0004" => strlen($hc->getVar0004()) > 30 ? 'NULL' : "'".$hc->getVar0004()."'", // Texto:  30
                        "var_0005" => strlen($hc->getVar0005()) > 20 || trim($hc->getVar0005()) == ''  ? 'NULL' : "'".$hc->getVar0005()."'", // Texto:  20
                        "var_0009" => strlen($hc->getVar0009()) >  2 || trim($hc->getVar0009()) == ''  ? 'NULL' : "'".$hc->getVar0009()."'", // Texto:   2
                        "var_0010" => strlen($hc->getVar0010()) >  1 || trim($hc->getVar0010()) == ''  ? 'NULL' : "'".$hc->getVar0010()."'", // Texto:   1
                        "var_0011" => strlen($hc->getVar0011()) >  1 || trim($hc->getVar0011()) == ''  ? 'NULL' : "'".$hc->getVar0011()."'", // Texto:   1 
                        "var_0012" => strlen($hc->getVar0012()) >  1 || trim($hc->getVar0012()) == ''  ? 'NULL' : "'".$hc->getVar0012()."'", // Texto:   1
                        "var_0013" => strlen($hc->getVar0013()) >  1 || trim($hc->getVar0013()) == ''  ? 'NULL' : "'".$hc->getVar0013()."'", // Texto:   1
                        "var_0014" => strlen($hc->getVar0014()) >  1 || trim($hc->getVar0014()) == ''  ? 'NULL' : "'".$hc->getVar0014()."'", // Texto:   1
                        "var_0015" => strlen($hc->getVar0015()) >  1 || trim($hc->getVar0015()) == ''  ? 'NULL' : "'".$hc->getVar0015()."'", // Texto:   1 
                        "var_0016" => strlen($hc->getVar0016()) >  1 || trim($hc->getVar0016()) == ''  ? 'NULL' : "'".$hc->getVar0016()."'", // Texto:   1
                        "var_0017" => strlen($hc->getVar0017()) > 20 || trim($hc->getVar0017()) == ''  ? 'NULL' : "'".$hc->getVar0017()."'", // Texto:  20
                        "var_0018" => strlen($hc->getVar0018()) > 20 || trim($hc->getVar0018()) == ''  ? 'NULL' : "'".$hc->getVar0018()."'", // Texto:  20
                        "var_0019" => strlen($hc->getVar0019()) > 20 || trim($hc->getVar0019()) == ''  ? 'NULL' : "'".$hc->getVar0019()."'", // Texto:  20
                        "var_0020" => strlen($hc->getVar0020()) >  1 || trim($hc->getVar0020()) == ''  ? 'NULL' : "'".$hc->getVar0020()."'", // Texto:   1
                        "var_0022" => strlen($hc->getVar0022()) >  1 || trim($hc->getVar0022()) == ''  ? 'NULL' : "'".$hc->getVar0022()."'", // Texto:   1
                        "var_0024" => strlen($hc->getVar0024()) >  1 || trim($hc->getVar0024()) == ''  ? 'NULL' : "'".$hc->getVar0024()."'", // Texto:   1 
                        "var_0026" => strlen($hc->getVar0026()) >  1 || trim($hc->getVar0026()) == ''  ? 'NULL' : "'".$hc->getVar0026()."'", // Texto:   1
                        "var_0028" => strlen($hc->getVar0028()) >  1 || trim($hc->getVar0028()) == ''  ? 'NULL' : "'".$hc->getVar0028()."'", // Texto:   1
                        "var_0030" => strlen($hc->getVar0030()) >  1 || trim($hc->getVar0030()) == ''  ? 'NULL' : "'".$hc->getVar0030()."'", // Texto:   1               
                        "var_0021" => strlen($hc->getVar0021()) >  1 || trim($hc->getVar0021()) == ''  ? 'NULL' : "'".$hc->getVar0021()."'", // Texto:   1
                        "var_0023" => strlen($hc->getVar0023()) >  1 || trim($hc->getVar0023()) == ''  ? 'NULL' : "'".$hc->getVar0023()."'", // Texto:   1
                        "var_0025" => strlen($hc->getVar0025()) >  1 || trim($hc->getVar0025()) == ''  ? 'NULL' : "'".$hc->getVar0025()."'", // Texto:   1
                        "var_0027" => strlen($hc->getVar0027()) >  1 || trim($hc->getVar0027()) == ''  ? 'NULL' : "'".$hc->getVar0027()."'", // Texto:   1
                        "var_0029" => strlen($hc->getVar0029()) >  1 || trim($hc->getVar0029()) == ''  ? 'NULL' : "'".$hc->getVar0029()."'", // Texto:   1
                        "var_0031" => strlen($hc->getVar0031()) >  1 || trim($hc->getVar0031()) == ''  ? 'NULL' : "'".$hc->getVar0031()."'", // Texto:   1
                        "var_0032" => strlen($hc->getVar0032()) >  1 || trim($hc->getVar0032()) == ''  ? 'NULL' : "'".$hc->getVar0032()."'", // Texto:   1
                        "var_0033" => strlen($hc->getVar0033()) >  1 || trim($hc->getVar0033()) == ''  ? 'NULL' : "'".$hc->getVar0033()."'", // Texto:   1
                        "var_0034" => strlen($hc->getVar0034()) >  1 || trim($hc->getVar0034()) == ''  ? 'NULL' : "'".$hc->getVar0034()."'", // Texto:   1
                        "var_0035" => strlen($hc->getVar0035()) >  1 || trim($hc->getVar0035()) == ''  ? 'NULL' : "'".$hc->getVar0035()."'", // Texto:   1
                        "var_0036" => strlen($hc->getVar0036()) >  1 || trim($hc->getVar0036()) == ''  ? 'NULL' : "'".$hc->getVar0036()."'", // Texto:   1
                        "var_0037" => strlen($hc->getVar0037()) > 20 || trim($hc->getVar0037()) == ''  ? 'NULL' : "'".$hc->getVar0037()."'", // Texto:  20
                        "var_0040" => strlen($hc->getVar0040()) >  2 || trim($hc->getVar0040()) == ''  ? 'NULL' : "'".$hc->getVar0040()."'", // Texto:   2
                        "var_0041" => strlen($hc->getVar0041()) >  2 || trim($hc->getVar0041()) == ''  ? 'NULL' : "'".$hc->getVar0041()."'", // Texto:   2
                        "var_0045" => strlen($hc->getVar0045()) >  1 || trim($hc->getVar0045()) == ''  ? 'NULL' : "'".$hc->getVar0045()."'", // Texto:   1
                        "var_0046" => strlen($hc->getVar0046()) >  2 || trim($hc->getVar0046()) == ''  ? 'NULL' : "'".$hc->getVar0046()."'", // Texto:   2
                        "var_0038" => strlen($hc->getVar0038()) >  1 || trim($hc->getVar0038()) == ''  ? 'NULL' : "'".$hc->getVar0038()."'", // Texto:   1
                        "var_0039" => strlen($hc->getVar0039()) >  1 || trim($hc->getVar0039()) == ''  ? 'NULL' : "'".$hc->getVar0039()."'", // Texto:   1
                        "var_0042" => strlen($hc->getVar0042()) >  2 || trim($hc->getVar0042()) == ''  ? 'NULL' : "'".$hc->getVar0042()."'", // Texto:   2
                        "var_0047" => strlen($hc->getVar0047()) >  1 || trim($hc->getVar0047()) == ''  ? 'NULL' : "'".$hc->getVar0047()."'", // Texto:   1
                        "var_0043" => strlen($hc->getVar0043()) >  2 || trim($hc->getVar0043()) == ''  ? 'NULL' : "'".$hc->getVar0043()."'", // Texto:   2
                        "var_0044" => strlen($hc->getVar0044()) >  2 || trim($hc->getVar0044()) == ''  ? 'NULL' : "'".$hc->getVar0044()."'", // Texto:   2
                        "var_0048" => strlen($hc->getVar0048()) >  1 || trim($hc->getVar0048()) == ''  ? 'NULL' : "'".$hc->getVar0048()."'", // Texto:   1
                        "var_0049" => strlen($hc->getVar0049()) >  1 || trim($hc->getVar0049()) == ''  ? 'NULL' : "'".$hc->getVar0049()."'", // Texto:   1
                        "var_0050" => strlen($hc->getVar0050()) >  1 || trim($hc->getVar0050()) == ''  ? 'NULL' : "'".$hc->getVar0050()."'", // Texto:   1
                        "var_0052" => strlen($hc->getVar0052()) >  1 || trim($hc->getVar0052()) == ''  ? 'NULL' : "'".$hc->getVar0052()."'", // Texto:   1
                        "var_0053" => strlen($hc->getVar0053()) >  1 || trim($hc->getVar0053()) == ''  ? 'NULL' : "'".$hc->getVar0053()."'", // Texto:   1
                        "var_0054" => strlen($hc->getVar0054()) >  1 || trim($hc->getVar0054()) == ''  ? 'NULL' : "'".$hc->getVar0054()."'", // Texto:   1
                        "var_0055" => strlen($hc->getVar0055()) >  3 || trim($hc->getVar0055()) == ''  ? 'NULL' : "'".$hc->getVar0055()."'", // Texto:   3
                        "var_0056" => strlen($hc->getVar0056()) >  2 || trim($hc->getVar0056()) == ''  ? 'NULL' : "'".$hc->getVar0056()."'", // Texto:   2
                        "var_0059" => strlen($hc->getVar0059()) >  1 || trim($hc->getVar0059()) == ''  ? 'NULL' : "'".$hc->getVar0059()."'", // Texto:   1
                        "var_0060" => strlen($hc->getVar0060()) >  1 || trim($hc->getVar0060()) == ''  ? 'NULL' : "'".$hc->getVar0060()."'", // Texto:   1
                        "var_0076" => strlen($hc->getVar0076()) >  1 || trim($hc->getVar0076()) == ''  ? 'NULL' : "'".$hc->getVar0076()."'", // Texto:   1
                        "var_0077" => strlen($hc->getVar0077()) >  1 || trim($hc->getVar0077()) == ''  ? 'NULL' : "'".$hc->getVar0077()."'", // Texto:   1
                        "var_0078" => strlen($hc->getVar0078()) >  1 || trim($hc->getVar0078()) == ''  ? 'NULL' : "'".$hc->getVar0078()."'", // Texto:   1
                        "var_0079" => strlen($hc->getVar0079()) >  1 || trim($hc->getVar0079()) == ''  ? 'NULL' : "'".$hc->getVar0079()."'", // Texto:   1
                        "var_0080" => strlen($hc->getVar0080()) >  1 || trim($hc->getVar0080()) == ''  ? 'NULL' : "'".$hc->getVar0080()."'", // Texto:   1
                        "var_0081" => strlen($hc->getVar0081()) >  1 || trim($hc->getVar0081()) == ''  ? 'NULL' : "'".$hc->getVar0081()."'", // Texto:   1
                        "var_0082" => strlen($hc->getVar0082()) >  1 || trim($hc->getVar0082()) == ''  ? 'NULL' : "'".$hc->getVar0082()."'", // Texto:   1
                        "var_0083" => strlen($hc->getVar0083()) >  1 || trim($hc->getVar0083()) == ''  ? 'NULL' : "'".$hc->getVar0083()."'", // Texto:   1
                        "var_0084" => strlen($hc->getVar0084()) >  1 || trim($hc->getVar0084()) == ''  ? 'NULL' : "'".$hc->getVar0084()."'", // Texto:   1
                        "var_0085" => strlen($hc->getVar0085()) >  2 || trim($hc->getVar0085()) == ''  ? 'NULL' : "'".$hc->getVar0085()."'", // Texto:   2
                        "var_0086" => strlen($hc->getVar0086()) >  1 || trim($hc->getVar0086()) == ''  ? 'NULL' : "'".$hc->getVar0086()."'", // Texto:   1
                        "var_0087" => strlen($hc->getVar0087()) >  1 || trim($hc->getVar0087()) == ''  ? 'NULL' : "'".$hc->getVar0087()."'", // Texto:   1
                        "var_0088" => strlen($hc->getVar0088()) >  1 || trim($hc->getVar0088()) == ''  ? 'NULL' : "'".$hc->getVar0088()."'", // Texto:   1
                        "var_0089" => strlen($hc->getVar0089()) >  1 || trim($hc->getVar0089()) == ''  ? 'NULL' : "'".$hc->getVar0089()."'", // Texto:   1
                        "var_0090" => strlen($hc->getVar0090()) >  1 || trim($hc->getVar0090()) == ''  ? 'NULL' : "'".$hc->getVar0090()."'", // Texto:   1
                        "var_0101" => strlen($hc->getVar0101()) >  1 || trim($hc->getVar0101()) == ''  ? 'NULL' : "'".$hc->getVar0101()."'", // Texto:   1
                        "var_0102" => strlen($hc->getVar0102()) >  1 || trim($hc->getVar0102()) == ''  ? 'NULL' : "'".$hc->getVar0102()."'", // Texto:   1
                        "var_0103" => strlen($hc->getVar0103()) >  1 || trim($hc->getVar0103()) == ''  ? 'NULL' : "'".$hc->getVar0103()."'", // Texto:   1
                        "var_0104" => strlen($hc->getVar0104()) >  1 || trim($hc->getVar0104()) == ''  ? 'NULL' : "'".$hc->getVar0104()."'", // Texto:   1
                        "var_0105" => strlen($hc->getVar0105()) >  3 || trim($hc->getVar0105()) == ''  ? 'NULL' : "'".$hc->getVar0105()."'", // Texto:   3
                        "var_0106" => strlen($hc->getVar0106()) >  1 || trim($hc->getVar0106()) == ''  ? 'NULL' : "'".$hc->getVar0106()."'", // Texto:   1
                        "var_0108" => strlen($hc->getVar0108()) >  3 || trim($hc->getVar0108()) == ''  ? 'NULL' : "'".$hc->getVar0108()."'", // Texto:   3
                        "var_0107" => strlen($hc->getVar0107()) >  1 || trim($hc->getVar0107()) == ''  ? 'NULL' : "'".$hc->getVar0107()."'", // Texto:   1
                        "var_0091" => strlen($hc->getVar0091()) >  1 || trim($hc->getVar0091()) == ''  ? 'NULL' : "'".$hc->getVar0091()."'", // Texto:   1
                        "var_0093" => strlen($hc->getVar0093()) >  1 || trim($hc->getVar0093()) == ''  ? 'NULL' : "'".$hc->getVar0093()."'", // Texto:   1
                        "var_0095" => strlen($hc->getVar0095()) >  3 || trim($hc->getVar0095()) == ''  ? 'NULL' : "'".$hc->getVar0095()."'", // Texto:   3
                        "var_0096" => strlen($hc->getVar0096()) >  1 || trim($hc->getVar0096()) == ''  ? 'NULL' : "'".$hc->getVar0096()."'", // Texto:   1
                        "var_0097" => strlen($hc->getVar0097()) >  1 || trim($hc->getVar0097()) == ''  ? 'NULL' : "'".$hc->getVar0097()."'", // Texto:   1
                        "var_0098" => strlen($hc->getVar0098()) >  1 || trim($hc->getVar0098()) == ''  ? 'NULL' : "'".$hc->getVar0098()."'", // Texto:   1 
                        "var_0099" => strlen($hc->getVar0099()) >  3 || trim($hc->getVar0099()) == ''  ? 'NULL' : "'".$hc->getVar0099()."'", // Texto:   3
                        "var_0100" => strlen($hc->getVar0100()) >  1 || trim($hc->getVar0100()) == ''  ? 'NULL' : "'".$hc->getVar0100()."'", // Texto:   1
                        "var_0109" => strlen($hc->getVar0109()) >  1 || trim($hc->getVar0109()) == ''  ? 'NULL' : "'".$hc->getVar0109()."'", // Texto:   1
                        "var_0110" => strlen($hc->getVar0110()) >  1 || trim($hc->getVar0110()) == ''  ? 'NULL' : "'".$hc->getVar0110()."'", // Texto:   1
                        "var_0111" => strlen($hc->getVar0111()) >  1 || trim($hc->getVar0111()) == ''  ? 'NULL' : "'".$hc->getVar0111()."'", // Texto:   1
                        "var_0112" => strlen($hc->getVar0112()) >  1 || trim($hc->getVar0112()) == ''  ? 'NULL' : "'".$hc->getVar0112()."'", // Texto:   1
                        "var_0114" => strlen($hc->getVar0114()) >  1 || trim($hc->getVar0114()) == ''  ? 'NULL' : "'".$hc->getVar0114()."'", // Texto:   1
                        "var_0115" => strlen($hc->getVar0115()) >  1 || trim($hc->getVar0115()) == ''  ? 'NULL' : "'".$hc->getVar0115()."'", // Texto:   1
                        "var_0182" => strlen($hc->getVar0182()) >  1 || trim($hc->getVar0182()) == ''  ? 'NULL' : "'".$hc->getVar0182()."'", // Texto:   1
                        "var_0184" => strlen($hc->getVar0184()) >  1 || trim($hc->getVar0184()) == ''  ? 'NULL' : "'".$hc->getVar0184()."'", // Texto:   1
                        "var_0185" => strlen($hc->getVar0185()) >  2 || trim($hc->getVar0185()) == ''  ? 'NULL' : "'".$hc->getVar0185()."'", // Texto:   2
                        "var_0186" => strlen($hc->getVar0186()) >  1 || trim($hc->getVar0186()) == ''  ? 'NULL' : "'".$hc->getVar0186()."'", // Texto:   1
                        "var_0187" => strlen($hc->getVar0187()) >  2 || trim($hc->getVar0187()) == ''  ? 'NULL' : "'".$hc->getVar0187()."'", // Texto:   2
                        "var_0188" => strlen($hc->getVar0188()) >  1 || trim($hc->getVar0188()) == ''  ? 'NULL' : "'".$hc->getVar0188()."'", // Texto:   1
                        "var_0189" => strlen($hc->getVar0189()) >  2 || trim($hc->getVar0189()) == ''  ? 'NULL' : "'".$hc->getVar0189()."'", // Texto:   2
                        "var_0190" => strlen($hc->getVar0190()) >  1 || trim($hc->getVar0190()) == ''  ? 'NULL' : "'".$hc->getVar0190()."'", // Texto:   1
                        "var_0191" => strlen($hc->getVar0191()) >  1 || trim($hc->getVar0191()) == ''  ? 'NULL' : "'".$hc->getVar0191()."'", // Texto:   1               
                        "var_0193" => strlen($hc->getVar0193()) >  4 || trim($hc->getVar0193()) == ''  ? 'NULL' : "'".$hc->getVar0193()."'", // Texto:   4
                        "var_0194" => strlen($hc->getVar0194()) >  1 || trim($hc->getVar0194()) == ''  ? 'NULL' : "'".$hc->getVar0194()."'", // Texto:   1
                        "var_0195" => strlen($hc->getVar0195()) >  1 || trim($hc->getVar0195()) == ''  ? 'NULL' : "'".$hc->getVar0195()."'", // Texto:   1
                        "var_0196" => strlen($hc->getVar0196()) >  1 || trim($hc->getVar0196()) == ''  ? 'NULL' : "'".$hc->getVar0196()."'", // Texto:   1
                        "var_0197" => strlen($hc->getVar0197()) >  3 || trim($hc->getVar0197()) == ''  ? 'NULL' : "'".$hc->getVar0197()."'", // Texto:   3
                        "var_0198" => strlen($hc->getVar0198()) >  2 || trim($hc->getVar0198()) == ''  ? 'NULL' : "'".$hc->getVar0198()."'", // Texto:   2
                        "var_0199" => strlen($hc->getVar0199()) >  1 || trim($hc->getVar0199()) == ''  ? 'NULL' : "'".$hc->getVar0199()."'", // Texto:   1
                        "var_0200" => strlen($hc->getVar0200()) >  1 || trim($hc->getVar0200()) == ''  ? 'NULL' : "'".$hc->getVar0200()."'", // Texto:   1
                        "var_0201" => strlen($hc->getVar0201()) >  1 || trim($hc->getVar0201()) == ''  ? 'NULL' : "'".$hc->getVar0201()."'", // Texto:   1
                        "var_0202" => strlen($hc->getVar0202()) >  1 || trim($hc->getVar0202()) == ''  ? 'NULL' : "'".$hc->getVar0202()."'", // Texto:   1
                        "var_0203" => strlen($hc->getVar0203()) >  1 || trim($hc->getVar0203()) == ''  ? 'NULL' : "'".$hc->getVar0203()."'", // Texto:   1
                        "var_0204" => strlen($hc->getVar0204()) >  1 || trim($hc->getVar0204()) == ''  ? 'NULL' : "'".$hc->getVar0204()."'", // Texto:   1
                        "var_0205" => strlen($hc->getVar0205()) >  1 || trim($hc->getVar0205()) == ''  ? 'NULL' : "'".$hc->getVar0205()."'", // Texto:   1
                        "var_0206" => strlen($hc->getVar0206()) >  1 || trim($hc->getVar0206()) == ''  ? 'NULL' : "'".$hc->getVar0206()."'", // Texto:   1
                        "var_0257" => strlen($hc->getVar0257()) >  1 || trim($hc->getVar0257()) == ''  ? 'NULL' : "'".$hc->getVar0257()."'", // Texto:   1
                        "var_0258" => strlen($hc->getVar0258()) >  1 || trim($hc->getVar0258()) == ''  ? 'NULL' : "'".$hc->getVar0258()."'", // Texto:   1
                        "var_0259" => strlen($hc->getVar0259()) >  1 || trim($hc->getVar0259()) == ''  ? 'NULL' : "'".$hc->getVar0259()."'", // Texto:   1
                        "var_0260" => strlen($hc->getVar0260()) >  1 || trim($hc->getVar0260()) == ''  ? 'NULL' : "'".$hc->getVar0260()."'", // Texto:   1
                        "var_0261" => strlen($hc->getVar0261()) >  1 || trim($hc->getVar0261()) == ''  ? 'NULL' : "'".$hc->getVar0261()."'", // Texto:   1
                        "var_0262" => strlen($hc->getVar0262()) >  1 || trim($hc->getVar0262()) == ''  ? 'NULL' : "'".$hc->getVar0262()."'", // Texto:   1
                        "var_0263" => strlen($hc->getVar0263()) >  1 || trim($hc->getVar0263()) == ''  ? 'NULL' : "'".$hc->getVar0263()."'", // Texto:   1
                        "var_0264" => strlen($hc->getVar0264()) >  1 || trim($hc->getVar0264()) == ''  ? 'NULL' : "'".$hc->getVar0264()."'", // Texto:   1
                        "var_0266" => strlen($hc->getVar0266()) >  1 || trim($hc->getVar0266()) == ''  ? 'NULL' : "'".$hc->getVar0266()."'", // Texto:   1
                        "var_0267" => strlen($hc->getVar0267()) >  1 || trim($hc->getVar0267()) == ''  ? 'NULL' : "'".$hc->getVar0267()."'", // Texto:   1
                        "var_0268" => strlen($hc->getVar0268()) >  1 || trim($hc->getVar0268()) == ''  ? 'NULL' : "'".$hc->getVar0268()."'", // Texto:   1
                        "var_0269" => strlen($hc->getVar0269()) >  1 || trim($hc->getVar0269()) == ''  ? 'NULL' : "'".$hc->getVar0269()."'", // Texto:   1
                        "var_0270" => strlen($hc->getVar0270()) >  1 || trim($hc->getVar0270()) == ''  ? 'NULL' : "'".$hc->getVar0270()."'", // Texto:   1
                        "var_0271" => strlen($hc->getVar0271()) >  1 || trim($hc->getVar0271()) == ''  ? 'NULL' : "'".$hc->getVar0271()."'", // Texto:   1
                        "var_0272" => strlen($hc->getVar0272()) >  1 || trim($hc->getVar0272()) == ''  ? 'NULL' : "'".$hc->getVar0272()."'", // Texto:   1
                        "var_0273" => strlen($hc->getVar0273()) >  1 || trim($hc->getVar0273()) == ''  ? 'NULL' : "'".$hc->getVar0273()."'", // Texto:   1
                        "var_0275" => strlen($hc->getVar0275()) >  1 || trim($hc->getVar0275()) == ''  ? 'NULL' : "'".$hc->getVar0275()."'", // Texto:   1
                        "var_0276" => strlen($hc->getVar0276()) >  1 || trim($hc->getVar0276()) == ''  ? 'NULL' : "'".$hc->getVar0276()."'", // Texto:   1
                        "var_0277" => strlen($hc->getVar0277()) >  1 || trim($hc->getVar0277()) == ''  ? 'NULL' : "'".$hc->getVar0277()."'", // Texto:   1
                        "var_0278" => strlen($hc->getVar0278()) >  2 || trim($hc->getVar0278()) == ''  ? 'NULL' : "'".$hc->getVar0278()."'", // Texto:   2
                        "var_0279" => strlen($hc->getVar0279()) >  2 || trim($hc->getVar0279()) == ''  ? 'NULL' : "'".$hc->getVar0279()."'", // Texto:   2
                        "var_0280" => strlen($hc->getVar0280()) >  2 || trim($hc->getVar0280()) == ''  ? 'NULL' : "'".$hc->getVar0280()."'", // Texto:   2
                        "var_0282" => strlen($hc->getVar0282()) > 01 || trim($hc->getVar0282()) == ''  ? 'NULL' : "'".$hc->getVar0282()."'", // Texto:  01
                        "var_0283" => strlen($hc->getVar0283()) > 04 || trim($hc->getVar0283()) == ''  ? 'NULL' : "'".$hc->getVar0283()."'", // Texto:  04
                        "var_0285" => strlen($hc->getVar0285()) > 01 || trim($hc->getVar0285()) == ''  ? 'NULL' : "'".$hc->getVar0285()."'", // Texto:  01
                        "var_0286" => strlen($hc->getVar0286()) > 01 || trim($hc->getVar0286()) == ''  ? 'NULL' : "'".$hc->getVar0286()."'", // Texto:  01
                        "var_0287" => strlen($hc->getVar0287()) > 01 || trim($hc->getVar0287()) == ''  ? 'NULL' : "'".$hc->getVar0287()."'", // Texto:  01
                        "var_0288" => strlen($hc->getVar0288()) >100 || trim($hc->getVar0288()) == ''  ? 'NULL' : "'".$hc->getVar0288()."'", // Texto: 100
                        "var_0289" => strlen($hc->getVar0289()) >  2 || trim($hc->getVar0289()) == ''  ? 'NULL' : "'".$hc->getVar0289()."'", // Texto:   2
                        "var_0290" => strlen($hc->getVar0290()) >  2 || trim($hc->getVar0290()) == ''  ? 'NULL' : "'".$hc->getVar0290()."'", // Texto:   2
                        "var_0291" => strlen($hc->getVar0291()) >  1 || trim($hc->getVar0291()) == ''  ? 'NULL' : "'".$hc->getVar0291()."'", // Texto:   1
                        "var_0292" => strlen($hc->getVar0292()) >  1 || trim($hc->getVar0292()) == ''  ? 'NULL' : "'".$hc->getVar0292()."'", // Texto:   1
                        "var_0293" => strlen($hc->getVar0293()) >  1 || trim($hc->getVar0293()) == ''  ? 'NULL' : "'".$hc->getVar0293()."'", // Texto:   1
                        "var_0294" => strlen($hc->getVar0294()) >  1 || trim($hc->getVar0294()) == ''  ? 'NULL' : "'".$hc->getVar0294()."'", // Texto:   1
                        "var_0295" => strlen($hc->getVar0295()) >  1 || trim($hc->getVar0295()) == ''  ? 'NULL' : "'".$hc->getVar0295()."'", // Texto:   1
                        "var_0296" => strlen($hc->getVar0296()) >  1 || trim($hc->getVar0296()) == ''  ? 'NULL' : "'".$hc->getVar0296()."'", // Texto:   1
                        "var_0297" => strlen($hc->getVar0297()) >  1 || trim($hc->getVar0297()) == ''  ? 'NULL' : "'".$hc->getVar0297()."'", // Texto:   1
                        "var_0298" => strlen($hc->getVar0298()) >  1 || trim($hc->getVar0298()) == ''  ? 'NULL' : "'".$hc->getVar0298()."'", // Texto:   1
                        "var_0299" => strlen($hc->getVar0299()) >  1 || trim($hc->getVar0299()) == ''  ? 'NULL' : "'".$hc->getVar0299()."'", // Texto:   1
                        "var_0300" => strlen($hc->getVar0300()) >  1 || trim($hc->getVar0300()) == ''  ? 'NULL' : "'".$hc->getVar0300()."'", // Texto:   1
                        "var_0301" => strlen($hc->getVar0301()) >  1 || trim($hc->getVar0301()) == ''  ? 'NULL' : "'".$hc->getVar0301()."'", // Texto:   1
                        "var_0302" => strlen($hc->getVar0302()) >  1 || trim($hc->getVar0302()) == ''  ? 'NULL' : "'".$hc->getVar0302()."'", // Texto:   1
                        "var_0303" => strlen($hc->getVar0303()) >  1 || trim($hc->getVar0303()) == ''  ? 'NULL' : "'".$hc->getVar0303()."'", // Texto:   1
                        "var_0304" => strlen($hc->getVar0304()) >  1 || trim($hc->getVar0304()) == ''  ? 'NULL' : "'".$hc->getVar0304()."'", // Texto:   1
                        "var_0305" => strlen($hc->getVar0305()) >  1 || trim($hc->getVar0305()) == ''  ? 'NULL' : "'".$hc->getVar0305()."'", // Texto:   1
                        "var_0306" => strlen($hc->getVar0306()) >  1 || trim($hc->getVar0306()) == ''  ? 'NULL' : "'".$hc->getVar0306()."'", // Texto:   1
                        "var_0307" => strlen($hc->getVar0307()) >  1 || trim($hc->getVar0307()) == ''  ? 'NULL' : "'".$hc->getVar0307()."'", // Texto:   1
                        "var_0308" => strlen($hc->getVar0308()) >  2 || trim($hc->getVar0308()) == ''  ? 'NULL' : "'".$hc->getVar0308()."'", // Texto:   2
                        "var_0309" => strlen($hc->getVar0309()) >  2 || trim($hc->getVar0309()) == ''  ? 'NULL' : "'".$hc->getVar0309()."'", // Texto:   2
                        "var_0310" => strlen($hc->getVar0310()) >  1 || trim($hc->getVar0310()) == ''  ? 'NULL' : "'".$hc->getVar0310()."'", // Texto:   1
                        "var_0311" => strlen($hc->getVar0311()) >  4 || trim($hc->getVar0311()) == ''  ? 'NULL' : "'".$hc->getVar0311()."'", // Texto:   4
                        "var_0312" => strlen($hc->getVar0312()) >  1 || trim($hc->getVar0312()) == ''  ? 'NULL' : "'".$hc->getVar0312()."'", // Texto:   1
                        "var_0313" => strlen($hc->getVar0313()) >  3 || trim($hc->getVar0313()) == ''  ? 'NULL' : "'".$hc->getVar0313()."'", // Texto:   3
                        "var_0314" => strlen($hc->getVar0314()) >  3 || trim($hc->getVar0314()) == ''  ? 'NULL' : "'".$hc->getVar0314()."'", // Texto:   3
                        "var_0315" => strlen($hc->getVar0315()) >  2 || trim($hc->getVar0315()) == ''  ? 'NULL' : "'".$hc->getVar0315()."'", // Texto:   2
                        "var_0316" => strlen($hc->getVar0316()) >  1 || trim($hc->getVar0316()) == ''  ? 'NULL' : "'".$hc->getVar0316()."'", // Texto:   1
                        "var_0319" => strlen($hc->getVar0319()) >  1 || trim($hc->getVar0319()) == ''  ? 'NULL' : "'".$hc->getVar0319()."'", // Texto:   1
                        "var_0317" => strlen($hc->getVar0317()) >  1 || trim($hc->getVar0317()) == ''  ? 'NULL' : "'".$hc->getVar0317()."'", // Texto:   1
                        "var_0318" => strlen($hc->getVar0318()) >  1 || trim($hc->getVar0318()) == ''  ? 'NULL' : "'".$hc->getVar0318()."'", // Texto:   1
                        "var_0320" => strlen($hc->getVar0320()) >  1 || trim($hc->getVar0320()) == ''  ? 'NULL' : "'".$hc->getVar0320()."'", // Texto:   1
                        "var_0321" => strlen($hc->getVar0321()) >  2 || trim($hc->getVar0321()) == ''  ? 'NULL' : "'".$hc->getVar0321()."'", // Texto:   2
                        "var_0322" => strlen($hc->getVar0322()) >  2 || trim($hc->getVar0322()) == ''  ? 'NULL' : "'".$hc->getVar0322()."'", // Texto:   2
                        "var_0323" => strlen($hc->getVar0323()) >  1 || trim($hc->getVar0323()) == ''  ? 'NULL' : "'".$hc->getVar0323()."'", // Texto:   1
                        "var_0324" => strlen($hc->getVar0324()) >  1 || trim($hc->getVar0324()) == ''  ? 'NULL' : "'".$hc->getVar0324()."'", // Texto:   1
                        "var_0325" => strlen($hc->getVar0325()) >  1 || trim($hc->getVar0325()) == ''  ? 'NULL' : "'".$hc->getVar0325()."'", // Texto:   1
                        "var_0326" => strlen($hc->getVar0326()) >  1 || trim($hc->getVar0326()) == ''  ? 'NULL' : "'".$hc->getVar0326()."'", // Texto:   1
                        "var_0327" => strlen($hc->getVar0327()) >  1 || trim($hc->getVar0327()) == ''  ? 'NULL' : "'".$hc->getVar0327()."'", // Texto:   1
                        "var_0328" => strlen($hc->getVar0328()) >  1 || trim($hc->getVar0328()) == ''  ? 'NULL' : "'".$hc->getVar0328()."'", // Texto:   1
                        "var_0329" => strlen($hc->getVar0329()) >  1 || trim($hc->getVar0329()) == ''  ? 'NULL' : "'".$hc->getVar0329()."'", // Texto:   1
                        "var_0330" => strlen($hc->getVar0330()) >  1 || trim($hc->getVar0330()) == ''  ? 'NULL' : "'".$hc->getVar0330()."'", // Texto:   1
                        "var_0331" => strlen($hc->getVar0331()) >  1 || trim($hc->getVar0331()) == ''  ? 'NULL' : "'".$hc->getVar0331()."'", // Texto:   1
                        "var_0333" => strlen($hc->getVar0333()) >  1 || trim($hc->getVar0333()) == ''  ? 'NULL' : "'".$hc->getVar0333()."'", // Texto:   1
                        "var_0332" => strlen($hc->getVar0332()) > 50 || trim($hc->getVar0332()) == ''  ? 'NULL' : "'".$hc->getVar0332()."'", // Texto:  50
                        "var_0334" => strlen($hc->getVar0334()) > 50 || trim($hc->getVar0334()) == ''  ? 'NULL' : "'".$hc->getVar0334()."'", // Texto:  50
                        "var_0335" => strlen($hc->getVar0335()) >  1 || trim($hc->getVar0335()) == ''  ? 'NULL' : "'".$hc->getVar0335()."'", // Texto:   1
                        "var_0368" => strlen($hc->getVar0368()) >  3 || trim($hc->getVar0368()) == ''  ? 'NULL' : "'".$hc->getVar0368()."'", // Texto:   3
                        "var_0336" => strlen($hc->getVar0336()) >  1 || trim($hc->getVar0336()) == ''  ? 'NULL' : "'".$hc->getVar0336()."'", // Texto:   1
                        "var_0337" => strlen($hc->getVar0337()) >  2 || trim($hc->getVar0337()) == ''  ? 'NULL' : "'".$hc->getVar0337()."'", // Texto:   2
                        "var_0338" => strlen($hc->getVar0338()) > 50 || trim($hc->getVar0338()) == ''  ? 'NULL' : "'".$hc->getVar0338()."'", // Texto:  50
                        "var_0339" => strlen($hc->getVar0339()) >  2 || trim($hc->getVar0339()) == ''  ? 'NULL' : "'".$hc->getVar0339()."'", // Texto:   2
                        "var_0340" => strlen($hc->getVar0340()) > 50 || trim($hc->getVar0340()) == ''  ? 'NULL' : "'".$hc->getVar0340()."'", // Texto:  50
                        "var_0341" => strlen($hc->getVar0341()) >  2 || trim($hc->getVar0341()) == ''  ? 'NULL' : "'".$hc->getVar0341()."'", // Texto:   2
                        "var_0342" => strlen($hc->getVar0342()) > 50 || trim($hc->getVar0342()) == ''  ? 'NULL' : "'".$hc->getVar0342()."'", // Texto:  50
                        "var_0343" => strlen($hc->getVar0343()) >  1 || trim($hc->getVar0343()) == ''  ? 'NULL' : "'".$hc->getVar0343()."'", // Texto:   1
                        "var_0344" => strlen($hc->getVar0344()) >  1 || trim($hc->getVar0344()) == ''  ? 'NULL' : "'".$hc->getVar0344()."'", // Texto:   1
                        "var_0345" => strlen($hc->getVar0345()) >  1 || trim($hc->getVar0345()) == ''  ? 'NULL' : "'".$hc->getVar0345()."'", // Texto:   1
                        "var_0346" => strlen($hc->getVar0346()) >  1 || trim($hc->getVar0346()) == ''  ? 'NULL' : "'".$hc->getVar0346()."'", // Texto:   1
                        "var_0347" => strlen($hc->getVar0347()) >  1 || trim($hc->getVar0347()) == ''  ? 'NULL' : "'".$hc->getVar0347()."'", // Texto:   1
                        "var_0348" => strlen($hc->getVar0348()) >  1 || trim($hc->getVar0348()) == ''  ? 'NULL' : "'".$hc->getVar0348()."'", // Texto:   1
                        "var_0367" => strlen($hc->getVar0367()) >  1 || trim($hc->getVar0367()) == ''  ? 'NULL' : "'".$hc->getVar0367()."'", // Texto:   1
                        "var_0370" => strlen($hc->getVar0370()) >  4 || trim($hc->getVar0370()) == ''  ? 'NULL' : "'".$hc->getVar0370()."'", // Texto:   4
                        "var_0371" => strlen($hc->getVar0371()) >  1 || trim($hc->getVar0371()) == ''  ? 'NULL' : "'".$hc->getVar0371()."'", // Texto:   1
                        "var_0372" => strlen($hc->getVar0372()) > 10 || trim($hc->getVar0372()) == ''  ? 'NULL' : "'".$hc->getVar0372()."'", // Texto:  10
                        "var_0373" => strlen($hc->getVar0373()) >  1 || trim($hc->getVar0373()) == ''  ? 'NULL' : "'".$hc->getVar0373()."'", // Texto:   1 
                        "var_0374" => strlen($hc->getVar0374()) >  2 || trim($hc->getVar0374()) == ''  ? 'NULL' : "'".$hc->getVar0374()."'", // Texto:   2
                        "var_0375" => strlen($hc->getVar0375()) >  1 || trim($hc->getVar0375()) == ''  ? 'NULL' : "'".$hc->getVar0375()."'", // Texto:   1
                        "var_0376" => strlen($hc->getVar0376()) >  1 || trim($hc->getVar0376()) == ''  ? 'NULL' : "'".$hc->getVar0376()."'", // Texto:   1
                        "var_0377" => strlen($hc->getVar0377()) >  1 || trim($hc->getVar0377()) == ''  ? 'NULL' : "'".$hc->getVar0377()."'", // Texto:   1
                        "var_0378" => strlen($hc->getVar0378()) >  1 || trim($hc->getVar0378()) == ''  ? 'NULL' : "'".$hc->getVar0378()."'", // Texto:   1
                        "var_0395" => strlen($hc->getVar0395()) >  4 || trim($hc->getVar0395()) == ''  ? 'NULL' : "'".$hc->getVar0395()."'", // Texto:   4
                        "var_0381" => strlen($hc->getVar0381()) > 10 || trim($hc->getVar0381()) == ''  ? 'NULL' : "'".$hc->getVar0381()."'", // Texto:  10
                        "var_0382" => strlen($hc->getVar0382()) >  1 || trim($hc->getVar0382()) == ''  ? 'NULL' : "'".$hc->getVar0382()."'", // Texto:   1
                        "var_0383" => strlen($hc->getVar0383()) >  1 || trim($hc->getVar0383()) == ''  ? 'NULL' : "'".$hc->getVar0383()."'", // Texto:   1
                        "var_0384" => strlen($hc->getVar0384()) >  3 || trim($hc->getVar0384()) == ''  ? 'NULL' : "'".$hc->getVar0384()."'", // Texto:   3
                        "var_0385" => strlen($hc->getVar0385()) >  1 || trim($hc->getVar0385()) == ''  ? 'NULL' : "'".$hc->getVar0385()."'", // Texto:   1
                        "var_0386" => strlen($hc->getVar0386()) >  1 || trim($hc->getVar0386()) == ''  ? 'NULL' : "'".$hc->getVar0386()."'", // Texto:   1
                        "var_0388" => strlen($hc->getVar0388()) > 30 || trim($hc->getVar0388()) == ''  ? 'NULL' : "'".$hc->getVar0388()."'", // Texto:  30
                        "var_0389" => strlen($hc->getVar0389()) > 50 || trim($hc->getVar0389()) == ''  ? 'NULL' : "'".$hc->getVar0389()."'", // Texto:  50
                        "var_0390" => strlen($hc->getVar0390()) > 50 || trim($hc->getVar0390()) == ''  ? 'NULL' : "'".$hc->getVar0390()."'", // Texto:  50
                        "var_0391" => strlen($hc->getVar0391()) > 50 || trim($hc->getVar0391()) == ''  ? 'NULL' : "'".$hc->getVar0391()."'", // Texto:  50
                        "version"  => "'".$version."'",                                                                                      // Texto:   5
                        "fecha"    => "'".$fecha."'",                                                                                        // Fecha:  
                        "hora"     => "'".$hora."'",                                                                                         // Texto:   8
                      //"var_0281" => strlen($hc->getVar0281()) >100 || trim($hc->getVar0281()) == ''  ? 'NULL' : "'".$hc->getVar0281()."'", // Texto: 100
                      //"var_0092" => strlen($hc->getVar0092()) >  1 || trim($hc->getVar0092()) == ''  ? 'NULL' : "'".$hc->getVar0092()."'", // Texto:   1
                      //"var_0094" => strlen($hc->getVar0094()) >  1 || trim($hc->getVar0094()) == ''  ? 'NULL' : "'".$hc->getVar0094()."'", // Texto:   1
                        );
                    $claves=Null;
                    $valores=Null;
                    foreach ( $variables as $clave => $valor) {
                        if($valor !='NULL'){
                            $claves[]=$clave;
                            $valores[]=$valor;
                        }
                    }
                    $sql='INSERT INTO nivel_01 ('.implode(',',$claves).') VALUES ('.implode(',',$valores).')';

                    $sql_l="INSERT INTO nivel_01 (ID01, ID02, var_0001, var_0002, var_0003 ,var_0004, var_0005, 
                        var_0006, var_0009, var_0010, var_0011, var_0012, var_0013, 
                        var_0014, var_0015, var_0016, var_0017, var_0018, var_0019,
                        var_0020, var_0022, var_0024, var_0026, var_0028, var_0030,                
                        var_0021, var_0023, var_0025, var_0027, var_0029, var_0031,
                        var_0032, var_0033, var_0034, var_0035, var_0036, var_0037, 
                        var_0040, var_0041, var_0045, var_0046, var_0038, var_0039, 
                        var_0042, var_0047, var_0043, var_0044, var_0048, var_0049, 
                        var_0050, var_0051, var_0052, var_0053, var_0054, var_0055,
                        var_0056, var_0057, var_0058, var_0059, var_0060, var_0076,
                        var_0077, var_0078, var_0079, var_0080, var_0081, var_0082, 
                        var_0083, var_0084, var_0085, var_0086, var_0087, var_0088, 
                        var_0089, var_0090, var_0101, var_0102, var_0103, var_0104,
                        var_0105, var_0106, var_0108, var_0107, var_0091, 
                        var_0093, var_0095, var_0096, var_0097, var_0098,
                        var_0099, var_0100, var_0109, var_0110, var_0111, var_0112,
                        var_0114, var_0115, var_0182, var_0183, var_0184, var_0185,
                        var_0186, var_0187, var_0188, var_0189, var_0190, var_0191,                
                        var_0192, var_0193, var_0194, var_0195, var_0196, var_0197,
                        var_0198, var_0199, var_0200, var_0201, var_0202, var_0203,
                        var_0204, var_0205, var_0206, var_0257, var_0258, var_0259,
                        var_0260, var_0261, var_0262, var_0263, var_0264, var_0266,
                        var_0267, var_0268, var_0269, var_0270, var_0271, var_0272,
                        var_0273, var_0274, var_0275, var_0276, var_0277, var_0278,
                        var_0279, var_0280, var_0282, var_0283, var_0284,
                        var_0285, var_0286, var_0287, var_0288, var_0289, var_0290,
                        var_0291, var_0292, var_0293, var_0294, var_0295, var_0296,
                        var_0297, var_0298, var_0299, var_0300, var_0301, var_0302,
                        var_0303, var_0304, var_0305, var_0306, var_0307, var_0308,
                        var_0309, var_0310, var_0311, var_0312, var_0313, var_0314,
                        var_0315, var_0316, var_0319, var_0317, var_0318, var_0320,
                        var_0321, var_0322, var_0323, var_0324, var_0325, var_0326,
                        var_0327, var_0328, var_0329, var_0330, var_0331, var_0333,
                        var_0332, var_0334, var_0335, var_0368, var_0336, var_0337,
                        var_0338, var_0339, var_0340, var_0341, var_0342, var_0343,
                        var_0344, var_0345, var_0346, var_0347, var_0348, var_0367,
                        var_0425, var_0370, var_0371, var_0372, var_0373, var_0374,
                        var_0375, var_0376, var_0377, var_0378, var_0395, var_0379,
                        var_0381, var_0382, var_0383, var_0384, var_0385, var_0386,
                        var_0388, var_0389, var_0390, var_0391, version, fecha, hora 
                    ) 
                    values ( '$id1','$id1', '".$hc->getVar0001()."', '".trim($hc->getVar0002())."','".trim($hc->getVar0003())."','".trim($hc->getVar0004())."','".trim($hc->getVar0005())."',
                     ".$var0006.",'".$hc->getVar0009()."','".$hc->getVar0010()."','".$hc->getVar0011()."','".$hc->getVar0012()."','".$hc->getVar0013()."',
                    '".$hc->getVar0014()."','".$hc->getVar0015()."','".$hc->getVar0016()."','".trim($hc->getVar0017())."','".trim($hc->getVar0018())."','".trim($hc->getVar0019())."',
                    '".$hc->getVar0020()."','".$hc->getVar0022()."','".$hc->getVar0024()."','".$hc->getVar0026()."','".$hc->getVar0028()."','".$hc->getVar0030()."',
                    '".$hc->getVar0021()."','".$hc->getVar0023()."','".$hc->getVar0025()."','".$hc->getVar0027()."','".$hc->getVar0029()."','".$hc->getVar0031()."',
                    '".$hc->getVar0032()."','".$hc->getVar0033()."','".$hc->getVar0034()."','".$hc->getVar0035()."','".$hc->getVar0036()."','".trim($hc->getVar0037())."',    
                    '".$hc->getVar0040()."','".$hc->getVar0041()."','".$hc->getVar0045()."','".$hc->getVar0046()."','".$hc->getVar0038()."','".$hc->getVar0039()."',
                    '".$hc->getVar0042()."','".$hc->getVar0047()."','".$hc->getVar0043()."','".$hc->getVar0044()."','".$hc->getVar0048()."','".$hc->getVar0049()."',
                    '".$hc->getVar0050()."', ".$var0051.",'".$hc->getVar0052()."','".$hc->getVar0053()."','".$hc->getVar0054()."','".$hc->getVar0055()."',    
                    '".$hc->getVar0056()."', ".$var0057.", ".$var0058.",'".$hc->getVar0059()."','".$hc->getVar0060()."','".$hc->getVar0076()."', 
                    '".$hc->getVar0077()."','".$hc->getVar0078()."','".$hc->getVar0079()."','".$hc->getVar0080()."','".$hc->getVar0081()."','".$hc->getVar0082()."', 
                    '".$hc->getVar0083()."','".$hc->getVar0084()."','".$hc->getVar0085()."','".$hc->getVar0086()."','".$hc->getVar0087()."','".$hc->getVar0088()."', 
                    '".$hc->getVar0089()."','".$hc->getVar0090()."','".$hc->getVar0101()."','".$hc->getVar0102()."','".$hc->getVar0103()."','".$hc->getVar0104()."', 
                    '".$hc->getVar0105()."','".$hc->getVar0106()."','".$hc->getVar0108()."','".$hc->getVar0107()."','".$hc->getVar0091()."','', 
                    '".$hc->getVar0093()."','','".$hc->getVar0095()."','".$hc->getVar0096()."','".$hc->getVar0097()."','".$hc->getVar0098()."', 
                    '".$hc->getVar0099()."','".$hc->getVar0100()."','".$hc->getVar0109()."','".$hc->getVar0110()."','".$hc->getVar0111()."','".$hc->getVar0112()."', 
                    '".$hc->getVar0114()."','".$hc->getVar0115()."','".$hc->getVar0182()."', ".$var0183.",'".$hc->getVar0184()."','".$hc->getVar0185()."', 
                    '".$hc->getVar0186()."','".$hc->getVar0187()."','".$hc->getVar0188()."','".$hc->getVar0189()."','".$hc->getVar0190()."','".$hc->getVar0191()."', 
                    ".$var0192.",'".$hc->getVar0193()."','".$hc->getVar0194()."','".$hc->getVar0195()."','".$hc->getVar0196()."','".$hc->getVar0197()."',  
                    '".$hc->getVar0198()."','".$hc->getVar0199()."','".$hc->getVar0200()."','".$hc->getVar0201()."','".$hc->getVar0202()."','".$hc->getVar0203()."', 
                    '".$hc->getVar0204()."','".$hc->getVar0205()."','".$hc->getVar0206()."','".$hc->getVar0257()."','".$hc->getVar0258()."','".$hc->getVar0259()."',
                    '".$hc->getVar0260()."','".$hc->getVar0261()."','".$hc->getVar0262()."','".$hc->getVar0263()."','".$hc->getVar0264()."','".$hc->getVar0266()."',
                    '".$hc->getVar0267()."','".$hc->getVar0268()."','".$hc->getVar0269()."','".$hc->getVar0270()."','".$hc->getVar0271()."','".$hc->getVar0272()."',
                    '".$hc->getVar0273()."','".$hc->getVar0274()."','".$hc->getVar0275()."','".$hc->getVar0276()."','".$hc->getVar0277()."','".$hc->getVar0278()."',
                    '".$hc->getVar0279()."','".$hc->getVar0280()."','','".$hc->getVar0282()."','".$hc->getVar0283()."', ".$var0284.",
                    '".$hc->getVar0285()."','".$hc->getVar0286()."','".$hc->getVar0287()."','".trim($hc->getVar0288())."','".$hc->getVar0289()."','".$hc->getVar0290()."',
                    '".$hc->getVar0291()."','".$hc->getVar0292()."','".$hc->getVar0293()."','".$hc->getVar0294()."','".$hc->getVar0295()."','".$hc->getVar0296()."',
                    '".$hc->getVar0297()."','".$hc->getVar0298()."','".$hc->getVar0299()."','".$hc->getVar0300()."','".$hc->getVar0301()."','".$hc->getVar0302()."',
                    '".$hc->getVar0303()."','".$hc->getVar0304()."','".$hc->getVar0305()."','".$hc->getVar0306()."','".$hc->getVar0307()."','".$hc->getVar0308()."',
                    '".$hc->getVar0309()."','".$hc->getVar0310()."','".$hc->getVar0311()."','".$hc->getVar0312()."','".$hc->getVar0313()."','".$hc->getVar0314()."',
                    '".$hc->getVar0315()."','".$hc->getVar0316()."','".$hc->getVar0319()."','".$hc->getVar0317()."','".$hc->getVar0318()."','".$hc->getVar0320()."',
                    '".$hc->getVar0321()."','".$hc->getVar0322()."','".$hc->getVar0323()."','".$hc->getVar0324()."','".$hc->getVar0325()."','".$hc->getVar0326()."',
                    '".$hc->getVar0327()."','".$hc->getVar0328()."','".$hc->getVar0329()."','".$hc->getVar0330()."','".$hc->getVar0331()."','".$hc->getVar0333()."',
                    '".trim($hc->getVar0332())."','".trim($hc->getVar0334())."','".$hc->getVar0335()."','".$hc->getVar0368()."','".$hc->getVar0336()."','".$hc->getVar0337()."',
                    '".trim($hc->getVar0338())."','".$hc->getVar0339()."','".trim($hc->getVar0340())."','".$hc->getVar0341()."','".trim($hc->getVar0342())."','".$hc->getVar0343()."',
                    '".$hc->getVar0344()."','".$hc->getVar0345()."','".$hc->getVar0346()."','".$hc->getVar0347()."','".$hc->getVar0348()."','".$hc->getVar0367()."',
                     ".$var0425.",'".$hc->getVar0370()."','".$hc->getVar0371()."','".trim($hc->getVar0372())."','".$hc->getVar0373()."','".$hc->getVar0374()."',
                    '".$hc->getVar0375()."','".$hc->getVar0376()."','".$hc->getVar0377()."','".$hc->getVar0378()."','".$hc->getVar0395()."',".$var0379.",
                    '".trim($hc->getVar0381())."','".$hc->getVar0382()."','".$hc->getVar0383()."','".$hc->getVar0384()."','".$hc->getVar0385()."','".$hc->getVar0386()."',
                    '".$hc->getVar0388()."','".trim($hc->getVar0389())."','".trim($hc->getVar0390())."','".trim($hc->getVar0391())."', '".$version."', '".$fecha."', '".$hora."'
                        )";
                    $result =  $access->query($sql);
                    if(!$result){ 
                        $rllbck='1'.$hc->getIdHcPerinatal();  break; 
                    }

                    // TABLA nivel_05 - Especiales
                    $sql="INSERT INTO nivel_05 (ID01, ID02,  var_0409, var_0061 ,var_0066, var_0071, 
                        var_0062, var_0067, var_0072, var_0063, var_0068, var_0073,
                        var_0064, var_0069, var_0074, var_0065, var_0070, var_0075,                    
                        var_0410, var_0413, var_0419, var_0415, var_0414, var_0420,                    
                        var_0421, var_0416, var_0422, var_0423, var_0418, var_0424,                    
                        var_0412, var_0411, version, fecha, hora,                    
                        var_0432, var_0433, var_0434, var_0435, var_0436, var_0437, 
                        var_0438, var_0439, var_0440, var_0441
                    ) 
                    values ( '$id1','$id1', '".$hc->getVar0409()."','".$hc->getVar0061()."','".$hc->getVar0066()."','".$hc->getVar0071()."',
                       '".$hc->getVar0062()."','".$hc->getVar0067()."','".$hc->getVar0072()."','".$hc->getVar0063()."','".$hc->getVar0068()."','".$hc->getVar0073()."',  
                       '".$hc->getVar0064()."','".$hc->getVar0069()."','".$hc->getVar0074()."','".$hc->getVar0065()."','".$hc->getVar0070()."','".$hc->getVar0075()."',                        
                       '".$hc->getVar0410()."','".$hc->getVar0413()."','".$hc->getVar0419()."','".$hc->getVar0415()."','".$hc->getVar0414()."','".$hc->getVar0420()."',                        
                       '".$hc->getVar0421()."','".$hc->getVar0416()."','".$hc->getVar0422()."','".$hc->getVar0423()."','".$hc->getVar0418()."','".$hc->getVar0424()."',                        
                       '".$hc->getVar0412()."','".$hc->getVar0411()."','".$version."', '".$fecha."', '".$hora."',
                       '".$hc->getVar0432()."','".$hc->getVar0433()."','".$hc->getVar0434()."','".$hc->getVar0435()."','".$hc->getVar0436()."','".$hc->getVar0437()."',                        
                       '".$hc->getVar0438()."','".$hc->getVar0439()."','".$hc->getVar0440()."','".$hc->getVar0441()."'
                        )";

                    $result05 =  $access->query($sql);
                    if(!$result05){ 
                        $rllbck='2'.$hc->getIdHcPerinatal();  
                        break;  
                    }

                    // TABLA nivel_02 - Consultas Prenatales
                    $letra = 65;
                    $consultasPrenatales= new ConsultaPrenatal();
                    $controles = $consultasPrenatales->getControlByHcPerinatal($hc->getIdHcPerinatal());
                    if($controles){
                      foreach ( $controles as $control) {
                        $cpn = new ConsultaPrenatal();
                        $cpn->construirResult($control);
                        $id2 = $id1.chr($letra++);
                        $sql="INSERT INTO nivel_02 (ID01, ID02, var_0116, var_0117, var_0118 ,var_0119, 
                                var_0120, var_0121, var_0394, var_0122, var_0123, 
                                var_0124, var_0125, var_0393, var_0126, var_0127, 
                                var_0128, var_0129, version, fecha, hora
                            ) 
                            values ( '$id2','$id2', '".$cpn->getVar0116()."', '".$cpn->getVar0117()."', '".$cpn->getVar0118()."', '".$cpn->getVar0119()."',
                                '".$cpn->getVar0120()."', '".$cpn->getVar0121()."', '".$cpn->getVar0394()."', '".$cpn->getVar0122()."', '".$cpn->getVar0123()."',
                                '".$cpn->getVar0124()."', '".$cpn->getVar0125()."', '".$cpn->getVar0393()."', '".$cpn->getVar0126()."', '".$cpn->getVar0127()."',
                                '".$cpn->getVar0128()."', '".$cpn->getVar0129()."', '".$version."', '".$fecha."', '".$hora."'
                             )";
                        $result02 =  $access->query($sql);
                        if(!$result02){
                            $rllbck='3'.$hc->getIdHcPerinatal();  break;  
                        }
                      }
                    }  
                    if($rllbck>0){ break; }

                    // TABLA nivel_03 - Controles de Parto
                    $letra = 65;
                    $controlesParto = new ControlParto();
                    $controles = $controlesParto->getControlesByHcPerinatal($hc->getIdHcPerinatal());
                    if($controles){
                      foreach ( $controles as $control) {
                        $cpa = new ControlParto();
                        $cpa->construirResult($control);
                        $id2 = $id.chr($letra++);
                        //nivel_03
                        $sql="INSERT INTO nivel_03 (ID01, ID02, var_0207, var_0208, var_0209, var_0210, 
                            var_0407, var_0392, var_0211, var_0212, var_0213, 
                            var_0214, var_0215, var_0216, version, fecha, hora
                          ) 
                            values ( '$id2','$id2', '".$cpa->getVar0207()."', '".$cpa->getVar0208()."', '".$cpa->getVar0209()."', '".$cpa->getVar0210()."',
                              '".$cpa->getVar0407()."', '".$cpa->getVar0392()."', '".$cpa->getVar0211()."', '".$cpa->getVar0212()."', '".$cpa->getVar0213()."',
                              '".$cpa->getVar0214()."', '".$cpa->getVar0215()."', '".$cpa->getVar0216()."', '".$version."', '".$fecha."', '".$hora."'
                        )";
                        $result03 =  $access->query($sql);
                        if(!$result03){ $rllbck='4'.$controlesParto->getIdControlParto();  break;   }
                      }                    
                    }

                    if($rllbck>0){ break; }

                    // TABLA nivel_04 - Controles Puerperio
                    $letra = 65;
                    $controlesPuerperio = new ControlPuerperio;
                    $controles = $controlesPuerperio->getPuerperioByHcPerinatal($hc->getIdHcPerinatal());
                    if($controles){
                      foreach ( $controles as $control) {
                        $cpp = new ControlPuerperio();
                        $cpp->construirResult($control);
                        $id2 = $id.chr($letra++);
                        //nivel_04
                        $sql="INSERT INTO nivel_04 (ID01, ID02, var_0349, var_0350, var_0351, var_0352, 
                                var_0406, var_0353, var_0354, var_0355, version, fecha, hora
                              ) 
                              values ( '$id2','$id2', '".$cpp->getVar0349()."', '".$cpp->getVar0350()."', '".$cpp->getVar0351()."', '".$cpp->getVar0352()."',
                                '".$cpp->getVar0406()."', '".$cpp->getVar0353()."', '".$cpp->getVar0354()."', '".$cpp->getVar0355()."', '".$version."', '".$fecha."', '".$hora."'
                            )";
                        $result04 =  $access->query($sql);
                        if(!$result04){ $rllbck='5'.$controlesPuerperio->getIdControlPuerperio(); break; }
                      }
                    }
                    if($rllbck>0){ break; }

                    // TABLA nivel_06 - Variables Libres
                    $variablesLibres = new VariablesLibres();
                    $vl = $variablesLibres->getLibresByHcPerinatal($hc->getIdHcPerinatal());
                    // nivel_06
                    $sql = "INSERT INTO nivel_06 (ID01, ID02, var_0396, var_0397, var_0398, var_0399, 
                                var_0400, var_0401, var_0402, var_0403, var_0404, 
                                var_0405, var_0426, var_0427, var_0428, var_0429, 
                                var_0430, var_0431, version, fecha, hora
                            ) 
                            values ( '$id1','$id1', '".$vl->getVar0396()."', '".$vl->getVar0397()."', '".$vl->getVar0398()."', '".$vl->getVar0399()."',
                                '".$vl->getVar0400()."', '".$vl->getVar0401()."', '".$vl->getVar0402()."', '".$vl->getVar0403()."', '".$vl->getVar0404()."',
                                '".$vl->getVar0405()."', '".$vl->getVar0426()."', '".$vl->getVar0427()."', '".$vl->getVar0428()."', '".$vl->getVar0429()."',
                                '".$vl->getVar0430()."', '".$vl->getVar0431()."', '".$version."', '".$fecha."', '".$hora."'
                            )";
                    $result06 =  $access->query($sql);

                    if(!$result06){ $rllbck='6'.$variablesLibres->getIdHcLibres(); break; }

                    // marcar proceso de ficha
                    $proc = "UPDATE sip_clap.hcperinatal SET procesado=1, 
                                fecha_hora_proceso=current_timestamp WHERE id_hcperinatal= ".$hc->getIdHcPerinatal();
                    sql($proc);
                    $j++;
                }
                if($rllbck==0 && file_exists($envio_path) ){
                    // guardar proceso de lote.
                    $sql = "INSERT INTO sip_clap.lotes_proceso(
                        cuie, periodo_desde, periodo_hasta, fichas_nuevas, fichas_reprocesadas, fecha_proceso, usuario_proceso)
                        VALUES ( '$efector', '$periodoDesde', '$periodoHasta', $nuevas, $reproc, '$fecha $hora', $user)  
                        RETURNING id_lote;";
                    $result = sql($sql);
                    $idLote = $result->fields;
                }else{
                    throw new Exception($rllbck, 1);
                }    
                sql("COMMIT"); 

                // generar zip 
              /* if (extension_loaded('zip')) {
                    $file = 'sip_clap_envio' .date('YmdHis') . ".zip";
                    $zip_name = $folder_path . $file; // Zip name

                    $zip = new ZipArchive();
                    if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
                        // Opening zip file to load files
                        $error .= "La generacion del ZIP fallo";
                    }
                    $zip->addFile($envio_path,$sip_envio); // Adding files into zip
                    $zip->close();
                }
                if (file_exists($zip_name))  $envio_name = $file;  
                else  */    
                $envio_name = $sip_envio;

                $path_envio_zip = array("path"=>$folder_path,
                        "nombre_archivo"=>$envio_name);
                
                $linkMdb = encode_link("../../lib/ver_archivo.php", $path_envio_zip);
                
               
                $lote = $lotesProceso->getLoteById($idLote['id_lote']);
                $salida = '';
                //foreach ($lotes as $key => $lote) {

                    $salida .= '<tr class="fila_con">';
                    $salida .= '<td>*</td>';
                    $salida .= '<td><b>'.$lote->getCuieTxt().'</b></td>';
                    $salida .= '<td><b>'.$lote->getPeriodoDesdeTxt().' &nbsp;-&nbsp; '.$lote->getPeriodoHastaTxt().'</b></td>';
                    $salida .= '<td>'.$lote->getFichasNuevas().'</td>';
                    $salida .= '<td>'.$lote->getFichasReprocesadas().'</td>';
                    $salida .= '<td><b>'.$lote->getFichasTotal().'</b></td>';
                    $salida .= '<td><b>'. Fecha($lote->getFechaProceso()).'</b></td>';        
                    $salida .= '<td>';
                    if($lote->getIdLote()==$idLote['id_lote']){
                        $salida .= '<a class="sprite-gral icon-download" href="'.encode_link("../../lib/ver_archivo.php", $path_envio_zip).'" target="_blank" title="Descargar"></a>';
                    }
                    $salida .= '</td>  </tr>';
                //}
                echo json_encode( array('url'=>$linkMdb, 'tr' => $salida) ); 
            } catch (Exception $e) {
                sql("ROLLBACK", "ERROR", 0);
                echo $e->getMessage();
            }
        }else{ echo 'No se puede conectar a Access';}
    }else{ echo 'No se puede encontrar Base origen';}
}else{ echo '0'; }