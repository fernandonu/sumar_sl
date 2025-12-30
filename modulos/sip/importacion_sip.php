<?php
require_once ("../../config.php");
require_once ("../inmunizacion/Clases/clases.php");
?>

<script src='../../lib/jquery.min.js' type='text/javascript'></script>
<link rel="stylesheet" type="text/css" href="../../lib/css/sprites.css">
<link rel="stylesheet" type="text/css" href="../../lib/css/general.css">

<script language="javascript">
//Creo una funci�n que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
    function callprogress(vValor) {
        document.getElementById("getprogress").innerHTML = vValor;
        document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: ' + vValor + '%;"></div>';
    }
</script>

<style type="text/css">
    /* Ahora creo el estilo que hara que aparesca el porcentanje y relleno del mismoo*/
    .ProgressBar     { width: 16em; border: 1px solid black; background: #eef; height: 1.25em; display: block; }
    .ProgressBarText { position: absolute; font-size: 1em; width: 16em; text-align: center; font-weight: normal }
    .ProgressBarFill { height: 100%; background: #aae; display: block; overflow: visible }
    .progreessBarConteiner {
        text-align: -moz-center;
        margin: 2%;
    }
    #getProgressBarFill {
        text-align: left;
    }
    .progreessBarTextBox {
        text-align: center;
        margin: 2%;
    }
    .contenido{
        border: 1px solid #ebebeb;
    }
</style>
<div class="contenido" id="contenido0" >
    <h3 align="center" class="titulo_pagina">Importar Base Sip-Clap</h3>
    <?
    // Cambio de fecha
    function convertirFechaADb($fecha){
        $data = explode("/", $fecha);

        if (count($data) == 3) {
            if (strlen($data[2]) == 4) {
                $data = $data[2]."-".$data[1]."-".$data[0];
            }else{
                $data = $fecha;
            }
        }else{
            $data = $fecha;
        }

        return($data);
    }
    ?>
        <div class = "progreessBarConteiner">
            <div class = "ProgressBar">
                <div class = "ProgressBarText"><span id = "getprogress"></span>&nbsp;
                    % completado</div>
                <div id = "getProgressBarFill"></div>
            </div>
        </div>
    <?
    $nombre_archivo = $_FILES["archivo"]["name"];
    
    
    try{ 
    if ($nombre_archivo != "") {

    $folder_path = MOD_DIR . "/sip/importacion/";
    $upload_dir = $folder_path . $nombre_archivo;
    $tmp = $_FILES['archivo']['tmp_name'];
    
    if (!file_exists($upload_dir)) {
       
        if (move_uploaded_file($tmp, $upload_dir)) {
        
            sql("BEGIN");   
            $access = ADONewConnection("ado_access");
            $myDSN = 'PROVIDER=Microsoft.Jet.OLEDB.4.0;'
                . 'DATA SOURCE=' . $upload_dir . ';';
            $cnx = $access->Connect($myDSN) || die('fallo al conectar');
            $sql="select * from nivel_01";
            $result =  $access->query($sql);
            
            $j=1;
            $total=$result->numRows();
            while(!$result->EOF) {
                
                $hcperinatal = new HCPerinatal();
                
                $hcperinatal->setAtributo('id_importacion', $nombre_archivo);
                $hcperinatal->setAtributo('clave_beneficiario', $result->fields("clave_beneficiario"));
                $hcperinatal->setAtributo('var_0001', $result->fields("var_0001"));
                $hcperinatal->setAtributo('var_0002', $result->fields("var_0002"));
                $hcperinatal->setAtributo('var_0003', $result->fields("var_0003"));
                $hcperinatal->setAtributo('var_0004', $result->fields("var_0004"));
                $hcperinatal->setAtributo('var_0005', $result->fields("var_0005"));
                
                $hcperinatal->setAtributo('var_0006', $result->fields("var_0006"));
                $hcperinatal->setAtributo('var_0009', 9999); //$result->fields("var_0009") No acepta Null
                $hcperinatal->setAtributo('var_0010', $result->fields("var_0010"));
                $hcperinatal->setAtributo('var_0011', $result->fields("var_0011"));
                $hcperinatal->setAtributo('var_0012', $result->fields("var_0012"));
                $hcperinatal->setAtributo('var_0013', $result->fields("var_0013"));
                
                $hcperinatal->setAtributo('var_0014', $result->fields("var_0014"));
                $hcperinatal->setAtributo('var_0015', $result->fields("var_0015"));
                $hcperinatal->setAtributo('var_0016', $result->fields("var_0016"));
                $hcperinatal->setAtributo('var_0017', substr($result->fields("var_0017"),0,6)); // Aceptan solo 6 caracteres
                $hcperinatal->setAtributo('var_0018', substr($result->fields("var_0018"),0,6)); // Aceptan solo 6 caracteres
                
                
                if($result->fields("var_0019")!=Null && trim($result->fields("var_0019"))!="" && trim($result->fields("var_0019"))!="0"){
                    $hcperinatal->setAtributo('var_0019',$result->fields("var_0019"));// No acepta null $result->fields("var_0021")
                }else{
                    $hcperinatal->setAtributo('var_0019',9999);
                }
                
                
                $hcperinatal->setAtributo('usuario_carga', $_ses_user[id]);
                $hcperinatal->setAtributo('fecha_hora_carga', date("Y-m-d H:i:s"));
                $hcperinatal->setAtributo('fecha_hora_proceso', date("Y-m-d H:i:s"));
                $hcperinatal->setAtributo('usuario_ultact', $_ses_user[id]);
                $hcperinatal->setAtributo('fecha_hora_ultact', date("Y-m-d H:i:s"));
                
                $hcperinatal->guardar();
                
                //--hcantecedentes
                
                $hcantecedentes = new HcAntecedentes();
                $hcantecedentes->setAtributo('id_importacion', $nombre_archivo);
                $hcantecedentes->setAtributo('id_hcperinatal', $hcperinatal->getAtributo('id_hcperinatal'));
                $hcantecedentes->setAtributo('var_0020', $result->fields("var_0020"));
                $hcantecedentes->setAtributo('var_0022', $result->fields("var_0022"));
                $hcantecedentes->setAtributo('var_0024', $result->fields("var_0024"));
                $hcantecedentes->setAtributo('var_0026', $result->fields("var_0026"));
                $hcantecedentes->setAtributo('var_0028', $result->fields("var_0028"));
                $hcantecedentes->setAtributo('var_0030', $result->fields("var_0030"));
                
                $hcantecedentes->setAtributo('var_0021', $result->fields("var_0021"));
                $hcantecedentes->setAtributo('var_0023', $result->fields("var_0023"));
                $hcantecedentes->setAtributo('var_0025', $result->fields("var_0025"));
                $hcantecedentes->setAtributo('var_0027', $result->fields("var_0027"));
                $hcantecedentes->setAtributo('var_0029', $result->fields("var_0029"));
                $hcantecedentes->setAtributo('var_0031', $result->fields("var_0031"));
                
                $hcantecedentes->setAtributo('var_0032', $result->fields("var_0032"));
                $hcantecedentes->setAtributo('var_0033', $result->fields("var_0033"));
                $hcantecedentes->setAtributo('var_0034', $result->fields("var_0034"));
                $hcantecedentes->setAtributo('var_0035', $result->fields("var_0035"));
                $hcantecedentes->setAtributo('var_0036', $result->fields("var_0036"));
                $hcantecedentes->setAtributo('var_0037', $result->fields("var_0037"));
                
                $hcantecedentes->setAtributo('var_0040', 9999); // Not Null $result->fields("var_0040")
                $hcantecedentes->setAtributo('var_0041', $result->fields("var_0041"));
                $hcantecedentes->setAtributo('var_0045', $result->fields("var_0045"));
                $hcantecedentes->setAtributo('var_0046', $result->fields("var_0046"));
                $hcantecedentes->setAtributo('var_0038', $result->fields("var_0038"));
                $hcantecedentes->setAtributo('var_0039', $result->fields("var_0039"));
                
                $hcantecedentes->setAtributo('var_0042', $result->fields("var_0042"));
                $hcantecedentes->setAtributo('var_0047', $result->fields("var_0047"));
                $hcantecedentes->setAtributo('var_0043', $result->fields("var_0043"));
                $hcantecedentes->setAtributo('var_0044', $result->fields("var_0044"));
                $hcantecedentes->setAtributo('var_0048', $result->fields("var_0048"));
                $hcantecedentes->setAtributo('var_0049', $result->fields("var_0049"));
                
                $hcantecedentes->setAtributo('var_0050', $result->fields("var_0050"));
                $hcantecedentes->setAtributo('var_0051', $result->fields("var_0051"));
                $hcantecedentes->setAtributo('var_0052', $result->fields("var_0052"));
                $hcantecedentes->setAtributo('var_0053', $result->fields("var_0053"));
                $hcantecedentes->setAtributo('var_0054', $result->fields("var_0054"));
                
                $hcantecedentes->guardar(1);
                
                
//                //-- hcgestacion_actual

                $hcgestacion_actual= new HcGestacionActual();
                $hcgestacion_actual->setAtributo('id_importacion', $nombre_archivo);
                $hcgestacion_actual->setAtributo('id_hcperinatal', $hcperinatal->getAtributo('id_hcperinatal'));
                $hcgestacion_actual->setAtributo('var_0055', $result->fields("var_0055"));
                
                $hcgestacion_actual->setAtributo('var_0056', $result->fields("var_0056"));
                $hcgestacion_actual->setAtributo('var_0057', $result->fields("var_0057"));
                $hcgestacion_actual->setAtributo('var_0058', $result->fields("var_0058"));
                $hcgestacion_actual->setAtributo('var_0059', $result->fields("var_0059"));
                $hcgestacion_actual->setAtributo('var_0060', $result->fields("var_0060"));
                $hcgestacion_actual->setAtributo('var_0076', $result->fields("var_0076"));
                
                $hcgestacion_actual->setAtributo('var_0077', $result->fields("var_0077"));
                $hcgestacion_actual->setAtributo('var_0078', $result->fields("var_0078"));
                $hcgestacion_actual->setAtributo('var_0079', $result->fields("var_0079"));
                $hcgestacion_actual->setAtributo('var_0080', $result->fields("var_0080"));
                $hcgestacion_actual->setAtributo('var_0081', $result->fields("var_0081"));
                $hcgestacion_actual->setAtributo('var_0082', $result->fields("var_0082"));
                
                $hcgestacion_actual->setAtributo('var_0083', $result->fields("var_0083"));
                $hcgestacion_actual->setAtributo('var_0084', $result->fields("var_0084"));
                $hcgestacion_actual->setAtributo('var_0085', $result->fields("var_0085"));
                $hcgestacion_actual->setAtributo('var_0086', $result->fields("var_0086"));
                $hcgestacion_actual->setAtributo('var_0087', $result->fields("var_0087"));
                $hcgestacion_actual->setAtributo('var_0088', $result->fields("var_0088"));
                
                $hcgestacion_actual->setAtributo('var_0089', $result->fields("var_0089"));
                $hcgestacion_actual->setAtributo('var_0090', $result->fields("var_0090"));
                $hcgestacion_actual->setAtributo('var_0101', $result->fields("var_0101"));
                $hcgestacion_actual->setAtributo('var_0102', $result->fields("var_0102"));
                $hcgestacion_actual->setAtributo('var_0103', $result->fields("var_0103"));
                $hcgestacion_actual->setAtributo('var_0104', $result->fields("var_0104"));  
                
                $hcgestacion_actual->setAtributo('var_0105', $result->fields("var_0105"));
                $hcgestacion_actual->setAtributo('var_0106', $result->fields("var_0106"));
                $hcgestacion_actual->setAtributo('var_0108', $result->fields("var_0108"));
                $hcgestacion_actual->setAtributo('var_0107', $result->fields("var_0107"));
                $hcgestacion_actual->setAtributo('var_0091', $result->fields("var_0091"));
//                $hcgestacion_actual->setAtributo('var_0092', $result->fields("var_0092"));
                
                $hcgestacion_actual->setAtributo('var_0093', $result->fields("var_0093"));
//                $hcgestacion_actual->setAtributo('var_0094', $result->fields("var_0094"));
                $hcgestacion_actual->setAtributo('var_0095', $result->fields("var_0095"));
                $hcgestacion_actual->setAtributo('var_0096', $result->fields("var_0096"));
                $hcgestacion_actual->setAtributo('var_0097', $result->fields("var_0097"));
                $hcgestacion_actual->setAtributo('var_0098', $result->fields("var_0098"));
                
                $hcgestacion_actual->setAtributo('var_0099', $result->fields("var_0099"));
                $hcgestacion_actual->setAtributo('var_0100', $result->fields("var_0100"));
                $hcgestacion_actual->setAtributo('var_0109', $result->fields("var_0109"));
                $hcgestacion_actual->setAtributo('var_0110', $result->fields("var_0110"));
                $hcgestacion_actual->setAtributo('var_0111', $result->fields("var_0111"));
                $hcgestacion_actual->setAtributo('var_0112', $result->fields("var_0112"));
                
                $hcgestacion_actual->setAtributo('var_0114', $result->fields("var_0114"));
                $hcgestacion_actual->setAtributo('var_0115', $result->fields("var_0115"));
                
                $hcgestacion_actual->guardar(1);
                
//                //-- hcparto_aborto                
                
		$hcparto_aborto= new HcPartoAborto();
                $hcparto_aborto->setAtributo('id_importacion', $nombre_archivo);
                $hcparto_aborto->setAtributo('id_hcperinatal', $hcperinatal->getAtributo('id_hcperinatal'));
				
                $hcparto_aborto->setAtributo('var_0182', $result->fields("var_0182"));
                $hcparto_aborto->setAtributo('var_0183', $result->fields("var_0183"));
                $hcparto_aborto->setAtributo('var_0184', $result->fields("var_0184"));
                $hcparto_aborto->setAtributo('var_0185', $result->fields("var_0185"));
                
                $hcparto_aborto->setAtributo('var_0186', $result->fields("var_0186"));
                $hcparto_aborto->setAtributo('var_0187', $result->fields("var_0187"));
                $hcparto_aborto->setAtributo('var_0188', $result->fields("var_0188"));
                $hcparto_aborto->setAtributo('var_0189', $result->fields("var_0189"));
                $hcparto_aborto->setAtributo('var_0190', $result->fields("var_0190"));
                $hcparto_aborto->setAtributo('var_0191', $result->fields("var_0191"));
                
                $hcparto_aborto->setAtributo('var_0192', $result->fields("var_0192"));
                $hcparto_aborto->setAtributo('var_0193', $result->fields("var_0193"));
                $hcparto_aborto->setAtributo('var_0194', $result->fields("var_0194"));
                $hcparto_aborto->setAtributo('var_0195', $result->fields("var_0195"));
                $hcparto_aborto->setAtributo('var_0196', $result->fields("var_0196"));
                $hcparto_aborto->setAtributo('var_0197', $result->fields("var_0197"));
                
                $hcparto_aborto->setAtributo('var_0198', $result->fields("var_0198"));
                $hcparto_aborto->setAtributo('var_0199', $result->fields("var_0199"));
                $hcparto_aborto->setAtributo('var_0200', $result->fields("var_0200"));
                $hcparto_aborto->setAtributo('var_0201', $result->fields("var_0201"));
                $hcparto_aborto->setAtributo('var_0202', $result->fields("var_0202"));
                $hcparto_aborto->setAtributo('var_0203', $result->fields("var_0203"));
                
                $hcparto_aborto->setAtributo('var_0204', $result->fields("var_0204"));
                $hcparto_aborto->setAtributo('var_0205', $result->fields("var_0205"));
                $hcparto_aborto->setAtributo('var_0206', $result->fields("var_0206"));
                $hcparto_aborto->setAtributo('var_0257', $result->fields("var_0257"));
                $hcparto_aborto->setAtributo('var_0258', $result->fields("var_0258"));
                $hcparto_aborto->setAtributo('var_0259', $result->fields("var_0259"));
                
                $hcparto_aborto->setAtributo('var_0260', $result->fields("var_0260"));
                $hcparto_aborto->setAtributo('var_0261', $result->fields("var_0261"));
                $hcparto_aborto->setAtributo('var_0262', $result->fields("var_0262"));
                $hcparto_aborto->setAtributo('var_0263', $result->fields("var_0263"));
                $hcparto_aborto->setAtributo('var_0264', $result->fields("var_0264"));
                $hcparto_aborto->setAtributo('var_0266', $result->fields("var_0266"));
                
                $hcparto_aborto->setAtributo('var_0267', $result->fields("var_0267"));
                $hcparto_aborto->setAtributo('var_0268', $result->fields("var_0268"));
                $hcparto_aborto->setAtributo('var_0269', $result->fields("var_0269"));
                $hcparto_aborto->setAtributo('var_0270', $result->fields("var_0270"));
                $hcparto_aborto->setAtributo('var_0271', $result->fields("var_0271"));
                $hcparto_aborto->setAtributo('var_0272', $result->fields("var_0272"));
                
                $hcparto_aborto->setAtributo('var_0273', $result->fields("var_0273"));
                $hcparto_aborto->setAtributo('var_0274', $result->fields("var_0274"));
                $hcparto_aborto->setAtributo('var_0275', $result->fields("var_0275"));
                $hcparto_aborto->setAtributo('var_0276', $result->fields("var_0276"));
                $hcparto_aborto->setAtributo('var_0277', $result->fields("var_0277"));
                $hcparto_aborto->setAtributo('var_0278', $result->fields("var_0278"));
                
                $hcparto_aborto->setAtributo('var_0279', $result->fields("var_0279"));
                $hcparto_aborto->setAtributo('var_0280', $result->fields("var_0280"));
//                $hcparto_aborto->setAtributo('var_0281', $result->fields("var_0281"));
                $hcparto_aborto->setAtributo('var_0282', $result->fields("var_0282"));
                $hcparto_aborto->setAtributo('var_0283', $result->fields("var_0283"));
                $hcparto_aborto->setAtributo('var_0284', $result->fields("var_0284"));
                
                $hcparto_aborto->setAtributo('var_0285', $result->fields("var_0285"));
                $hcparto_aborto->setAtributo('var_0286', $result->fields("var_0286"));
                $hcparto_aborto->setAtributo('var_0287', $result->fields("var_0287"));
                $hcparto_aborto->setAtributo('var_0288', $result->fields("var_0288"));
                $hcparto_aborto->setAtributo('var_0289', $result->fields("var_0289"));
                $hcparto_aborto->setAtributo('var_0290', $result->fields("var_0290"));
                
                $hcparto_aborto->setAtributo('var_0291', $result->fields("var_0291"));
                $hcparto_aborto->setAtributo('var_0292', $result->fields("var_0292"));
                $hcparto_aborto->setAtributo('var_0293', $result->fields("var_0293"));
                $hcparto_aborto->setAtributo('var_0294', $result->fields("var_0294"));
                $hcparto_aborto->setAtributo('var_0295', $result->fields("var_0295"));
                $hcparto_aborto->setAtributo('var_0296', $result->fields("var_0296"));
                
                $hcparto_aborto->setAtributo('var_0297', $result->fields("var_0297"));
                $hcparto_aborto->setAtributo('var_0298', $result->fields("var_0298"));
                $hcparto_aborto->setAtributo('var_0299', $result->fields("var_0299"));
                $hcparto_aborto->setAtributo('var_0300', $result->fields("var_0300"));
                $hcparto_aborto->setAtributo('var_0301', $result->fields("var_0301"));
                $hcparto_aborto->setAtributo('var_0302', $result->fields("var_0302"));
                
                $hcparto_aborto->setAtributo('var_0303', $result->fields("var_0303"));
                $hcparto_aborto->setAtributo('var_0304', $result->fields("var_0304"));
                $hcparto_aborto->setAtributo('var_0305', $result->fields("var_0305"));
                $hcparto_aborto->setAtributo('var_0306', $result->fields("var_0306"));
                $hcparto_aborto->setAtributo('var_0307', $result->fields("var_0307"));
                $hcparto_aborto->setAtributo('var_0308', $result->fields("var_0308"));
                
                $hcparto_aborto->setAtributo('var_0309', $result->fields("var_0309"));
                
                $hcparto_aborto->guardar(1);
                
//              --hcrecien_nacido  
                
                $hcrecien_nacido=new HcRecienNacido();
                $hcrecien_nacido->setAtributo('id_importacion', $nombre_archivo);
                $hcrecien_nacido->setAtributo('id_hcperinatal', $hcperinatal->getAtributo('id_hcperinatal'));
                
                $hcrecien_nacido->setAtributo('var_0310', $result->fields("var_0310"));
                $hcrecien_nacido->setAtributo('var_0311', $result->fields("var_0311"));
                $hcrecien_nacido->setAtributo('var_0312', $result->fields("var_0312"));
                $hcrecien_nacido->setAtributo('var_0313', $result->fields("var_0313"));
                $hcrecien_nacido->setAtributo('var_0314', $result->fields("var_0314"));
                
                $hcrecien_nacido->setAtributo('var_0315', $result->fields("var_0315"));
                $hcrecien_nacido->setAtributo('var_0316', $result->fields("var_0316"));
                $hcrecien_nacido->setAtributo('var_0319', $result->fields("var_0319"));
                $hcrecien_nacido->setAtributo('var_0317', $result->fields("var_0317"));
                $hcrecien_nacido->setAtributo('var_0318', $result->fields("var_0318"));
                $hcrecien_nacido->setAtributo('var_0320', $result->fields("var_0320"));
                
                $hcrecien_nacido->setAtributo('var_0321', $result->fields("var_0321"));
                $hcrecien_nacido->setAtributo('var_0322', $result->fields("var_0322"));
                $hcrecien_nacido->setAtributo('var_0323', $result->fields("var_0323"));
                $hcrecien_nacido->setAtributo('var_0324', $result->fields("var_0324"));
                $hcrecien_nacido->setAtributo('var_0325', $result->fields("var_0325"));
                $hcrecien_nacido->setAtributo('var_0326', $result->fields("var_0326"));
                
                $hcrecien_nacido->setAtributo('var_0327', $result->fields("var_0327"));
                $hcrecien_nacido->setAtributo('var_0328', $result->fields("var_0328"));
                $hcrecien_nacido->setAtributo('var_0329', $result->fields("var_0329"));
                $hcrecien_nacido->setAtributo('var_0330', $result->fields("var_0330"));
                $hcrecien_nacido->setAtributo('var_0331', $result->fields("var_0331"));
                $hcrecien_nacido->setAtributo('var_0333', $result->fields("var_0333"));
                
                $hcrecien_nacido->setAtributo('var_0332', $result->fields("var_0332"));
                $hcrecien_nacido->setAtributo('var_0334', $result->fields("var_0334"));
                $hcrecien_nacido->setAtributo('var_0335', $result->fields("var_0335"));
                $hcrecien_nacido->setAtributo('var_0368', $result->fields("var_0368"));
                $hcrecien_nacido->setAtributo('var_0336', $result->fields("var_0336"));
                $hcrecien_nacido->setAtributo('var_0337', $result->fields("var_0337"));
                
                $hcrecien_nacido->setAtributo('var_0338', $result->fields("var_0338"));
                $hcrecien_nacido->setAtributo('var_0339', $result->fields("var_0339"));
                $hcrecien_nacido->setAtributo('var_0340', $result->fields("var_0340"));
                $hcrecien_nacido->setAtributo('var_0341', $result->fields("var_03410"));
                $hcrecien_nacido->setAtributo('var_0342', $result->fields("var_0342"));
                $hcrecien_nacido->setAtributo('var_0343', $result->fields("var_0343"));
                
                $hcrecien_nacido->setAtributo('var_0344', $result->fields("var_0000"));
                $hcrecien_nacido->setAtributo('var_0345', $result->fields("var_0000"));
                $hcrecien_nacido->setAtributo('var_0346', $result->fields("var_0000"));
                $hcrecien_nacido->setAtributo('var_0347', $result->fields("var_0000"));
                $hcrecien_nacido->setAtributo('var_0348', $result->fields("var_0000"));
                $hcrecien_nacido->setAtributo('var_0367', $result->fields("var_0000"));
                
                $hcrecien_nacido->setAtributo('var_0425', $result->fields("var_0425"));
                $hcrecien_nacido->setAtributo('var_0370', $result->fields("var_0370"));
                $hcrecien_nacido->setAtributo('var_0371', $result->fields("var_0371"));
                $hcrecien_nacido->setAtributo('var_0372', $result->fields("var_0372"));
                $hcrecien_nacido->setAtributo('var_0373', $result->fields("var_0373"));
                $hcrecien_nacido->setAtributo('var_0374', $result->fields("var_0374"));
                
                $hcrecien_nacido->setAtributo('var_0375', $result->fields("var_0375"));
                $hcrecien_nacido->setAtributo('var_0376', $result->fields("var_0376"));
                $hcrecien_nacido->setAtributo('var_0377', $result->fields("var_0377"));
                $hcrecien_nacido->setAtributo('var_0378', $result->fields("var_0378"));
                $hcrecien_nacido->setAtributo('var_0395', $result->fields("var_0395"));
                $hcrecien_nacido->setAtributo('var_0379', $result->fields("var_0379"));
                
                $hcrecien_nacido->setAtributo('var_0381', $result->fields("var_0381"));
                $hcrecien_nacido->setAtributo('var_0382', $result->fields("var_0382"));
                $hcrecien_nacido->setAtributo('var_0383', $result->fields("var_0383"));
                $hcrecien_nacido->setAtributo('var_0384', $result->fields("var_0384"));
                $hcrecien_nacido->setAtributo('var_0385', $result->fields("var_0385"));
                $hcrecien_nacido->setAtributo('var_0386', $result->fields("var_0386"));
                
                $hcrecien_nacido->setAtributo('var_0388', $result->fields("var_0388"));
                $hcrecien_nacido->setAtributo('var_0389', $result->fields("var_0389"));
                $hcrecien_nacido->setAtributo('var_0390', $result->fields("var_0390"));
                $hcrecien_nacido->setAtributo('var_0391', $result->fields("var_0391"));
                
                $hcrecien_nacido->guardar(1);
                
                // TABLA nivel_05 - Especiales
                $id01=$result->fields("id01");
                $sql="select * from nivel_05 where id01='$id01'";
                $result2 =  $access->query($sql);
                $algo=$result2->numRows();
                if($algo > 0){
                    while(!$result2->EOF) {
                        $hcespeciales= new HcEspeciales();
                        $hcespeciales->setAtributo('id_importacion', $nombre_archivo);
                        $hcespeciales->setAtributo('id_hcperinatal', $hcperinatal->getAtributo('id_hcperinatal'));
                        $hcespeciales->setAtributo('var_0409', $result2->fields("var_0409"));
                        $hcespeciales->setAtributo('var_0061', $result2->fields("var_0061"));
                        $hcespeciales->setAtributo('var_0066', $result2->fields("var_0066"));
                        $hcespeciales->setAtributo('var_0071', $result2->fields("var_0071"));
                        $hcespeciales->setAtributo('var_0062', $result2->fields("var_0062"));
                        $hcespeciales->setAtributo('var_0067', $result2->fields("var_0067"));
                        $hcespeciales->setAtributo('var_0072', $result2->fields("var_0072"));
                        $hcespeciales->setAtributo('var_0063', $result2->fields("var_0063"));
                        $hcespeciales->setAtributo('var_0068', $result2->fields("var_0068"));
                        $hcespeciales->setAtributo('var_0073', $result2->fields("var_0073"));
                        $hcespeciales->setAtributo('var_0064', $result2->fields("var_0064"));
                        $hcespeciales->setAtributo('var_0069', $result2->fields("var_0069"));
                        $hcespeciales->setAtributo('var_0074', $result2->fields("var_0074"));
                        $hcespeciales->setAtributo('var_0065', $result2->fields("var_0065"));
                        $hcespeciales->setAtributo('var_0070', $result2->fields("var_0070"));
                        $hcespeciales->setAtributo('var_0075', $result2->fields("var_0075"));
                        $hcespeciales->setAtributo('var_0410', $result2->fields("var_0410"));
                        $hcespeciales->setAtributo('var_0413', $result2->fields("var_0413"));
                        $hcespeciales->setAtributo('var_0419', $result2->fields("var_0419"));
                        $hcespeciales->setAtributo('var_0415', $result2->fields("var_0415"));
                        $hcespeciales->setAtributo('var_0414', $result2->fields("var_0414"));
                        $hcespeciales->setAtributo('var_0420', $result2->fields("var_0420"));
                        $hcespeciales->setAtributo('var_0421', $result2->fields("var_0421"));
                        $hcespeciales->setAtributo('var_0422', $result2->fields("var_0422"));
                        $hcespeciales->setAtributo('var_0423', $result2->fields("var_0423"));
                        $hcespeciales->setAtributo('var_0416', $result2->fields("var_0416"));
                        $hcespeciales->setAtributo('var_0418', $result2->fields("var_0418"));
                        $hcespeciales->setAtributo('var_0412', $result2->fields("var_0412"));
                        $hcespeciales->setAtributo('var_0411', $result2->fields("var_0411"));
                        $hcespeciales->setAtributo('var_0432', $result2->fields("var_0432"));
                        $hcespeciales->setAtributo('var_0433', $result2->fields("var_0433"));
                        $hcespeciales->setAtributo('var_0434', $result2->fields("var_0434"));
                        $hcespeciales->setAtributo('var_0435', $result2->fields("var_0435"));
                        $hcespeciales->setAtributo('var_0436', $result2->fields("var_0436"));
                        $hcespeciales->setAtributo('var_0437', $result2->fields("var_0437"));
                        $hcespeciales->setAtributo('var_0438', $result2->fields("var_0438"));
                        $hcespeciales->setAtributo('var_0439', $result2->fields("var_0439"));
                        $hcespeciales->setAtributo('var_0440', $result2->fields("var_0440"));
                        $hcespeciales->setAtributo('var_0441', $result2->fields("var_0441"));

                        $hcespeciales->guardar(1);

                        $result2->MoveNext();
                    }
                }
                
                $cuie=$hcperinatal->getAtributo('var_0018');
                $dni=$hcperinatal->getAtributo('var_0019');
                
                // TABLA nivel_02 - Consultas Prenatales
                $sql="select * from nivel_02 where ID01 like '%".$cuie."000000000000".$dni."%'"; // aca hay q recortar id01 
                $result3 =$access->query($sql);
                $algo=$result3->numRows();
                
                if($algo > 0){
                    while(!$result3->EOF) {
                        $hccontrolprenatal= new HcControlPrenatal();
                        $hccontrolprenatal->setAtributo('id_importacion', $nombre_archivo);
                        $hccontrolprenatal->setAtributo('id_hcperinatal', $hcperinatal->getAtributo('id_hcperinatal'));
                        
                        $hccontrolprenatal->setAtributo('var_0116', $result3->fields("var_0116"));
                        $hccontrolprenatal->setAtributo('var_0117', $result3->fields("var_0117"));
                        $hccontrolprenatal->setAtributo('var_0118', $result3->fields("var_0118"));
                        $hccontrolprenatal->setAtributo('var_0119', $result3->fields("var_0119"));
                        $hccontrolprenatal->setAtributo('var_0120', $result3->fields("var_0120"));
                        $hccontrolprenatal->setAtributo('var_0121', $result3->fields("var_0121"));
                        $hccontrolprenatal->setAtributo('var_0394', $result3->fields("var_0394"));
                        $hccontrolprenatal->setAtributo('var_0122', $result3->fields("var_0122"));
                        $hccontrolprenatal->setAtributo('var_0123', substr($result3->fields("var_0123"),0,3)); // character(3)
                        $hccontrolprenatal->setAtributo('var_0124', $result3->fields("var_0124"));
                        $hccontrolprenatal->setAtributo('var_0125', $result3->fields("var_0125"));
                        $hccontrolprenatal->setAtributo('var_0393', $result3->fields("var_0393"));
                        $hccontrolprenatal->setAtributo('var_0126', $result3->fields("var_0126"));
                        $hccontrolprenatal->setAtributo('var_0127', $result3->fields("var_0127"));
                        $hccontrolprenatal->setAtributo('var_0128', $result3->fields("var_0128"));
                        $hccontrolprenatal->setAtributo('var_0129', $result3->fields("var_0129"));
                        
                        $hccontrolprenatal->guardar(); //Tiene id propio
                        
                        $result3->MoveNext();
                    }
                }
                
                // TABLA nivel_03 - Controles de Parto
                $sql="select * from nivel_03 where ID01 like('%".$cuie."000000000000".$dni."%')"; // aca hay q recortar id01
                $result4 =  $access->query($sql);
                $algo=$result4->numRows();
                
                if($algo > 0){
                    while(!$result4->EOF) {
                        $hccontrolparto= new HcControlParto();
                        $hccontrolparto->setAtributo('id_importacion', $nombre_archivo);
                        $hccontrolparto->setAtributo('id_hcperinatal', $hcperinatal->getAtributo('id_hcperinatal'));
                        
                        $hccontrolparto->setAtributo('var_0207', $result4->fields("var_0207"));
                        $hccontrolparto->setAtributo('var_0208', $result4->fields("var_0208"));
                        $hccontrolparto->setAtributo('var_0209', $result4->fields("var_0209"));
                        $hccontrolparto->setAtributo('var_0210', $result4->fields("var_0210"));
                        $hccontrolparto->setAtributo('var_0407', $result4->fields("var_0407"));
                        $hccontrolparto->setAtributo('var_0392', $result4->fields("var_0392"));
                        $hccontrolparto->setAtributo('var_0211', $result4->fields("var_0211"));
                        $hccontrolparto->setAtributo('var_0212', $result4->fields("var_0212"));
                        $hccontrolparto->setAtributo('var_0213', $result4->fields("var_0213"));
                        $hccontrolparto->setAtributo('var_0214', $result4->fields("var_0214"));
                        $hccontrolparto->setAtributo('var_0215', $result4->fields("var_0215"));
                        $hccontrolparto->setAtributo('var_0216', $result4->fields("var_0216"));
                        
                        $hccontrolparto->guardar(); //Tiene id propio
                        
                        $result4->MoveNext();
                    }
                }
                
//                 TABLA nivel_04 - Controles Puerperio
                
                $sql="select * from nivel_04 where ID01 like('%".$cuie."000000000000".$dni."%')"; // aca hay q recortar id01
                $result5 =  $access->query($sql);
                $algo=$result5->numRows();
                
                if($algo > 0){
                    while(!$result5->EOF) {
                        $hcpuerperio= new HcPuerperio();
                        $hcpuerperio->setAtributo('id_importacion', $nombre_archivo);
                        $hcpuerperio->setAtributo('id_hcperinatal', $hcperinatal->getAtributo('id_hcperinatal'));
                        
                        $hcpuerperio->setAtributo('var_0349', $result4->fields("var_0349"));
                        $hcpuerperio->setAtributo('var_0350', $result4->fields("var_0350"));
                        $hcpuerperio->setAtributo('var_0351', $result4->fields("var_0351"));
                        $hcpuerperio->setAtributo('var_0352', $result4->fields("var_0352"));
                        $hcpuerperio->setAtributo('var_0406', $result4->fields("var_0406"));
                        $hcpuerperio->setAtributo('var_0353', $result4->fields("var_0353"));
                        $hcpuerperio->setAtributo('var_0354', $result4->fields("var_0354"));
                        $hcpuerperio->setAtributo('var_0355', $result4->fields("var_0355"));
                        
                        $hcpuerperio->guardar(); //Tiene id propio
                        
                        $result5->MoveNext();
                    }
                }
                
                  // TABLA nivel_06 - Variables Libres
                
                $sql="select * from nivel_06 where id01='$id01'"; // Revisar porque guarda todo vacio
                $result6 =  $access->query($sql);
                $algo=$result6->numRows();
                
                if($algo > 0){
                    while(!$result6->EOF) {
                        $variablesLibres= new HcLibres();
                        $variablesLibres->setAtributo('id_importacion', $nombre_archivo);
                        $variablesLibres->setAtributo('id_hcperinatal', $hcperinatal->getAtributo('id_hcperinatal'));
                        
                        $variablesLibres->setAtributo('var_0396', $result4->fields("var_0396"));
                        $variablesLibres->setAtributo('var_0397', $result4->fields("var_0397"));
                        $variablesLibres->setAtributo('var_0398', $result4->fields("var_0398"));
                        $variablesLibres->setAtributo('var_0399', $result4->fields("var_0399"));
                        $variablesLibres->setAtributo('var_0400', $result4->fields("var_0400"));
                        $variablesLibres->setAtributo('var_0401', $result4->fields("var_0401"));
                        $variablesLibres->setAtributo('var_0402', $result4->fields("var_0402"));
                        $variablesLibres->setAtributo('var_0403', $result4->fields("var_0403"));
                        $variablesLibres->setAtributo('var_0404', $result4->fields("var_0404"));
                        $variablesLibres->setAtributo('var_0405', $result4->fields("var_0405"));
                        $variablesLibres->setAtributo('var_0426', $result4->fields("var_0426"));
                        $variablesLibres->setAtributo('var_0427', $result4->fields("var_0427"));
                        $variablesLibres->setAtributo('var_0428', $result4->fields("var_0428"));
                        $variablesLibres->setAtributo('var_0429', $result4->fields("var_0429"));
                        $variablesLibres->setAtributo('var_0430', $result4->fields("var_0430"));
                        $variablesLibres->setAtributo('var_0431', $result4->fields("var_0431"));
                        
                        $variablesLibres->guardar(); //Tiene id propio
                        
                        $result6->MoveNext();
                    }
                }
                
                $porcentaje = $j * 100 / $total; //saco mi valor en porcentaje
                $j+=1;
                echo "<script>callprogress(" . round($porcentaje) . ")</script>"; //llamo a la funci�n JS(JavaScript) para actualizar el progreso
                flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
                ob_flush();
                $result->MoveNext();        
            }
                       
        }else{    
            $error = 'Posible ataque de carga de archivo!';
        }
        sql("COMMIT");
        ?>
        <div class="progreessBarTextBox">
            <b><?="El archivo ".$nombre_archivo." ha sido importado!!";?></b>
        </div>    
        <?
        
    }else{
        echo "Archivo ya Ingresado !";
    }

    }else{
        throw new Exception('Desaparecio!!!.');
        echo "Seleccione un archivo !";
    }
    }catch(Exception $e){
            sql("ROLLBACK", "Error en Importacion Sip-Clap", 0);
            echo $e->getTrace();
        }
    ?>   
</div>    