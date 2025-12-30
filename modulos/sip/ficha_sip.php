<?php
require_once("../../config.php");
require_once("clases/HCPerinatal.php");
require_once("clases/ConsultaPrenatal.php");
require_once("clases/ControlParto.php");
require_once("clases/ControlPuerperio.php");
require_once("clases/VariablesLibres.php");
require_once("sipclap_funciones.php");
require_once("../../clases/BeneficiariosUad.php");

$beneficiarioUad = new BeneficiarioUad();

$ficha = new HCPerinatal();
$ConsultaPrenatal = new ConsultaPrenatal();
$controlParto     = new ControlParto();
$puerperio = new ControlPuerperio();
$variablesLibres  = new VariablesLibres();
// Permiso de Hospital
// $permisoHospital = verificarPermisoFinalizar($_ses_user["id"]);
$permisoHospital = 1;
$db_error = "";

if (!empty($parametros)) {
    // acceso desde listado de inscriptos o cambio de ficha activa
    $clave = $parametros['clavebeneficiario'];
    $beneficiario = $beneficiarioUad->buscarPorClaveBeneficiario($clave);

    if (isset($parametros['idFicha'])) {
        // Buscar un ficha existente
        $ficha = $ficha->getHcPerinatalById($parametros['idFicha']);
    } else {
        // buscar ultima ficha creada
        $ficha = $ficha->buscarUltimaPorClaveBeneficiario($clave);

        if (is_null($ficha->getIdHcPerinatal())) {
            /* No existe Ficha creada, obtener datos del beneficiario para crear nueva  */
            $ficha->setDatosDesdeBeneficiario($beneficiario);
        }
    }    
    // obtiene consultas cargadas anteriormente y setea lugar de control
    //$consultas = $ConsultaPrenatal->getConsultasPrenatales($ficha, $beneficiario);
} else {
    $datosFaltantes = '';
    $formData = $_POST;
    $clave = $formData['claveBeneficiario'];
    $beneficiario = $beneficiarioUad->buscarPorClaveBeneficiario($clave);
    $idHc = $formData['idHcPerinatal'];

    if ($formData['nuevaFicha'] == 'S') {
        $ficha = new HCPerinatal();
        if ($formData['nuevaMultiple'] == 'S') {
            //CARGAR FICHA NUEVA PARA PARTO MULTIPLE
            $ficha = $ficha->crearSiguienteMultiple($idHc);
            //$consultas = $ConsultaPrenatal->getControlByHcPerinatal($idHc);
            $controlesParto = $controlParto->getControlesByHcPerinatal($idHc);
        } else {
            // ficha nueva
            $ficha->setDatosDesdeBeneficiario($beneficiario);
        }
    } else {
        // GRABAR DATOS DE LA FICHA
        try {
            sql("BEGIN");

            $ficha = $ficha->getHcPerinatalById($idHc);

            // guardar nivel_01
            $idHc = $ficha->saveFichaSip($formData);

            // guardar nivel_02 - consulta prenatal
            if( $formData['idHcPerinatal']=='' ) $formData['idHcPerinatal']=$idHc;

            $prestacion = guardarComprobantesConsultasPrenatales($formData,$_ses_user["id"]);

            // Omite comprobación de almenos 1 practica perinatal
            // if(!$prestacion) throw new Exception("Error procesando comprobante de consulta", 1);

            // guardar nivel_03 - control parto
            $result = $controlParto->saveControlesParto($idHc, $formData['control_parto']);
            // guardar nivel_04 - control post-parto o puerperio
            $result = $puerperio->saveControlesPuerperio($idHc, $formData['puerperio']);
            // guardar nivel_06 - variables libres
            $result = $variablesLibres->saveVariablesLibres($idHc, $formData['libres']);
            
            $datosFaltantes = validarDatosFicha($formData);

            if( $formData['finalizarFicha'] == 1 || true ){
                /*if(  $datosFaltantes == '' && $formData['var_0182']!=''
                        && $formData['var_0282']!='' && $formData['var_0287']!='' ){

                    if( $formData['var_0287']=='B'){
                        // cesarea 
                        $codigo ='IT Q002';
                        if( $formData['var_0282']=='A' ) $diagnostico = 'W88';
                        else                             $diagnostico = 'W89';                    
                    }else{
                        //vaginal
                        $codigo ='IT Q001';
                        if( $formData['var_0282']=='A' ) $diagnostico = 'W90';
                        else                             $diagnostico = 'W91';
                    }
                    $res = guardarComprobante($formData,$_ses_user["id"],$codigo,$diagnostico);
                    if(!$res)
                        throw new Exception("Error procesando comprobante de parto", 1);

                }*/
                /*if( $formData['var_0385'] =='B'){
                    //CONSERJERIA
                    $res = guardarComprobante($formData,$_ses_user["id"],'CO T017','W86');
                    if(!$res)
                        throw new Exception("Error procesando comprobante de Conserjeria", 1);
                }
                if( $formData['var_0367'] =='B'){
                    //ANTIRRUBEOLA POSTPARTO
                    $res = guardarComprobante($formData,$_ses_user["id"],'IM V011','A98');
                    if(!$res)
                        throw new Exception("Error procesando comprobante de Antirrubeola Postparto", 1);
                }*/
                
            }    
            
            // Actualizar datos de embarazada en beneficiarios
                
                // FPC
                $controles = $ConsultaPrenatal->getArrayControles($formData['consulta']);
                if( count($controles)>0 ){
                    //primer control
                    $control = $controles[0];
                    if ( strlen($control["facturado"]) > 0) {
                        $sqlComprobante = "select comprobante.* from facturacion.prestacion 
                                    left join facturacion.comprobante on comprobante.id_comprobante = prestacion.id_comprobante
                                where id_prestacion = ".$control["facturacion_id_prestacion"]."";
                        $resultComprobante = sql($sqlComprobante);

                        $sqlTrazadoraEmbarazada = "select * from trazadoras.embarazadas where id_emb = ".$control["trazadora_id_emb"]."";
                        $resultTrazadorasEmbarazada = sql($sqlTrazadoraEmbarazada);

                        $semanasEmbarazo = (int)$resultTrazadorasEmbarazada->fields["sem_gestacion"];
                        $fechaPrimerControl = $resultComprobante->fields["fecha_comprobante"];
                    }else{
                        $fechaPrimerControl = ConvFechaComoDB( $control['fecha_consulta'] );
                        $semanasEmbarazo = $control['var_0119'];
                    }
                    $fpc = $fechaPrimerControl;
                    
                }else{
                    $fpc = $beneficiario->getFechaProbableParto();
                    $semanasEmbarazo = $beneficiario->getSemanasEmbarazo();
                }

                if ( strlen($semanasEmbarazo)<1 ) {
                    $semanasEmbarazo = 0;
                }

                
                // FUM OPCIONAL EN UAD
                if ( strlen($ficha->getvar0057()) < 8 ) {
                    
                    if ( strlen($control["facturado"]) > 0 ) {
                        $sqlTrazadoraEmbarazada = "select * from trazadoras.embarazadas where id_emb = ".$control["trazadora_id_emb"]."";
                        $resultTrazadorasEmbarazada = sql($sqlTrazadoraEmbarazada);
                        $fechaFUM = $resultTrazadorasEmbarazada->fields["fum"];
                    }else{
                        $fechaFUM = $beneficiario->getFum();
                    }

                }else{
                    $fechaFUM = ConvFechaComoDB($ficha->getvar0057());
                }

                // Control de FUM
                if ( strlen( $fechaFUM )< 8 ) {
                    $fechaFUM = "NULL";
                }else{
                     $fechaFUM = "'". $fechaFUM."'";
                }


                // FPP
                if ( strlen($ficha->getvar0058()) < 8 ) {

                    if ( strlen($control["facturado"]) > 0 ) {
                        $sqlTrazadoraEmbarazada = "select * from trazadoras.embarazadas where id_emb = ".$control["trazadora_id_emb"]."";
                        $resultTrazadorasEmbarazada = sql($sqlTrazadoraEmbarazada);
                        $fechaFPP = $resultTrazadorasEmbarazada->fields["fpp"];
                    }else{
                        $fechaFPP = $beneficiario->getFechaProbableParto();
                    }

                }else{
                    $fechaFPP = ConvFechaComoDB($ficha->getvar0058());
                }
                
                // Control de fechaFPP
                if ( strlen( $fechaFPP )< 8 ) {
                    $fechaFPP = "NULL";
                }else{
                     $fechaFPP = "'". $fechaFPP."'";
                }

                // Control de fpc
                if ( strlen( $fpc )< 8 ) {
                    $fpc = "NULL";
                }else{
                     $fpc = "'". $fpc."'";
                }



                    
                // Estado Envio
                if ($beneficiario->getEstadoEnvio() == "e") {
                    $estadoEnvio = "n";
                }else{
                    $estadoEnvio = $beneficiario->getEstadoEnvio();
                }

                
                
                $sql = "UPDATE uad.beneficiarios SET 
                    estado_envio ='".$estadoEnvio."', 
                    fum=".$fechaFUM.",
                    fecha_probable_parto=".$fechaFPP.",
                    fecha_diagnostico_embarazo=". $fpc .",
                    semanas_embarazo = ".$semanasEmbarazo."
                    WHERE 
                        clave_beneficiario='" . $beneficiario->getClaveBeneficiario() . "'";
                // echo $sql;

                sql($sql);
            
            sql("COMMIT");

            $ficha->getHcPerinatalById($idHc);


        } catch (Exception $e) {
            sql("ROLLBACK", "Error en rollback", 0);
            // Setear los datos con lo recibido por POST
            // Volver a setear valores en el formulario
            $ficha->setFicha($formData);
            $consultas = array();
            foreach ($formData['consulta'] as $variable => $valores) {
                foreach ($valores as $key => $dato) {
                    $consultas[$key][$variable] = $dato;
                }
            }
            $controlesParto = array();
            foreach ($formData['control_parto'] as $variable => $valores) {
                foreach ($valores as $key => $dato) {
                    $controlesParto[$key][$variable] = $dato;
                }
            }
            $controlesPuerperio = array();
            foreach ($formData['puerperio'] as $variable => $valores) {
                foreach ($valores as $key => $dato) {
                    $controlesPuerperio[$key][$variable] = $dato;
                }
            }
            $varLibre = array();
            parse_str($formData['libres'], $varLibre);
            $variablesLibres->setHcVariablesLibres($varLibre);

            $db_error = '<div style="text-align:center">'. $e->getMessage() .'</div>';
        }
    }
}


// TABLaS ADICIONALES
// obtiene consultas cargadas anteriormente y setea lugar de control
$consultas = getConsultasPrenatales($ficha, $beneficiario);

if ($ficha->getIdHcPerinatal()) {
    // obtener datos de tablas adicionales
//    $consultas = $ConsultaPrenatal->getControlByHcPerinatal($ficha->getIdHcPerinatal());
    $controlesParto = $controlParto->getControlesByHcPerinatal($ficha->getIdHcPerinatal());
    $controlesPuerperio = $puerperio->getPuerperioByHcPerinatal($ficha->getIdHcPerinatal());
    $variablesLibres = $variablesLibres->getLibresByHcPerinatal($ficha->getIdHcPerinatal());
}
// Fichas para completar select
$fichasAll = $ficha->buscarFichasPorClave($clave);
$score_riesgo = 0;
$escala_riesgo = array(
  array(
    "min" => 0,
    "max" => 1,
    "desc" => "Bajo Riesgo",
    "accion" => "Control en CAPS",
    "style" => "background-color:green;color:white;"
  ),
  array(
    "min" => 2,
    "max" => 6,
    "desc" => "Medio Riesgo",
    "accion" => "Control con TG",
    "style" => "background-color:yellow;color:black;"
  ),
  array(
    "min" => 7,
    "max" => 99,
    "desc" => "Alto Riesgo",
    "accion" => "Derivaci&oacute;n con TG",
    "style" => "background-color:red;color:white;"
  )
);
// Verificar si la ficha se puede editar
$editable = $ficha->esEditable();
$permiteNuevaFicha = $ficha->permiteNuevaFicha();

// echo $html_header;
?>
<html>
<head>
  <title></title>
  <link rel=stylesheet type='text/css' href='<?php echo $html_root; ?>/lib/estilos.css'>
  <link rel='stylesheet' type='text/css' href='<?php echo $html_root; ?>/lib/jquery/ui/jquery-ui.css'/>
  <link rel="stylesheet" type='text/css' href="<?php echo $html_root; ?>/modulos/sip/css/normalize.css"/>
  <link rel="stylesheet" type='text/css' href="<?php echo $html_root; ?>/modulos/sip/css/icheck.css"/>
  <link rel="stylesheet" type='text/css' href="<?php echo $html_root; ?>/modulos/sip/css/sipclap.css"/>
  <link rel='stylesheet' type='text/css' href='<?php echo $html_root; ?>/lib/bootstrap-switch/bootstrap-switch.min.css'/>
  <link rel='stylesheet' type='text/css' href='<?php echo $html_root; ?>/lib/dataTables/jquery.dataTables.min.css'/>

  <script type='text/javascript' src='<?php echo $html_root; ?>/lib/jquery-1.7.2.min.js'></script>
  <script type='text/javascript' src='<?php echo $html_root; ?>/lib/jquery/jquery-ui.js'></script>
  <script type='text/javascript' src='<?php echo $html_root; ?>/lib/jquery/ui/jquery.ui.datepicker-es.js'></script>
  <script type='text/javascript' src='<?php echo $html_root; ?>/lib/bootstrap-switch/bootstrap-switch.min.js'></script>
  <script type='text/javascript' src='<?php echo $html_root; ?>/lib/dataTables/jquery.dataTables.min.js'></script>
  <script type='text/javascript' src='<?php echo $html_root; ?>/modulos/sip/js/icheck.min.js'></script>
  <script type='text/javascript' src="<?php echo $html_root; ?>/modulos/sip/js/sipclap.js" ></script>
</head>
<body>
<div class="divloading"><img src="css/loading.gif"></div>
<?php

//echo "id=".$ficha->getIdHcPerinatal()."<br/>";

$lnkVolver = $html_root."/modulos/turnos/pacientes.php";
$checked = 'checked="checked"';
$selected = 'selected="selected"';

if (!empty($db_error)) {
  echo $db_error;
}
?>

<!-- formularios modales -->
<div id="dialog_varlibres" style="display:none"><?php include 'variables_libres.php'; ?></div>            
<div id="dialog_efectores"></div>
<div id="dialog_auxiliar"></div>
<div id="dialog_faltantes"></div>

        <form id="sipclap" name="sipclap" method="POST" action="<?php echo $html_root; ?>/modulos/sip/ficha_sip.php">
            <div style="padding: 5px; height: 25px; background: powderblue;">
                <div class="listadoFichas">
                    <label>FICHAS:</label>
                    <select id="fichasList">
                        <?php 
                        // agregar item de ficha nueva a la lista
                        if( is_null($ficha->getIdHcPerinatal()) ){                            
                            $text = 'Fecha:'.  FechaView($ficha->getFechahoraCarga()); 
                            $text .= ' | Estado:'. $ficha->getEstadoTxt($ficha->getProcesado(),$ficha->getFinalizado());
                            echo '<option value="" selected="selected" >'. $text.'</option>';   
                        }
                        if( count($fichasAll)>0 ){
                        // cargar fichas existentes
                            while (!$fichasAll->EOF) {
                                $score_riesgo = intval($fichasAll->fields['score_riesgo']);
                                foreach ($escala_riesgo as $riesgo) {
                                  if ($score_riesgo >= $riesgo["min"] and $score_riesgo <= $riesgo["max"]) {
                                    $score_riesgo_txt_tmp = $score_riesgo.' - '.$riesgo["desc"];
                                    $score_riesgo_accion_tmp = $riesgo["accion"];
                                    $score_riesgo_style_tmp = $riesgo["style"];
                                    break;
                                  }
                                }
                                $activa = ( $fichasAll->fields['id_hcperinatal']==$ficha->getIdHcPerinatal() )? 'selected="selected"' : '';
                                if ($activa=='') {
                                  $urlLink = ' data-link="'. encode_link($html_root."/modulos/sip/ficha_sip.php", array("clavebeneficiario" => $clave,'idFicha' => $fichasAll->fields['id_hcperinatal'] )) .'"';
                                }
                                else {
                                  $score_riesgo_txt = $score_riesgo_txt_tmp;
                                  $score_riesgo_accion = $score_riesgo_accion_tmp;
                                  $score_riesgo_style = $score_riesgo_style_tmp;
                                  $estilos = ' style="'.$score_riesgo_style_tmp.'"';
                                }
                                $text = 'Carga:'.  FechaView($fichasAll->fields['fecha_hora_carga']); 
                                $text .= ' | Estado:'. $ficha->getEstadoTxt($fichasAll->fields['procesado'],$fichasAll->fields['finalizado']);
                               /* if($fichasAll->fields['procesado'])
                                    $text .= ' | Fecha proc.:'.FechaView($fichasAll->fields['fecha_hora_proceso']);*/
                                if ($fichasAll->fields['fecha_nacimiento']) {
                                    $text .= ' || Fecha Nac.:'.FechaView($fichasAll->fields['fecha_nacimiento']);
                                }
                                echo '<option value="'.$fichasAll->fields['id_hcperinatal'].'" '. $estilos . $activa . $urlLink .' >'. $text.'</option>';   
                                $fichasAll->MoveNext();
                            }  
                        }
                        ?>
                    </select>
                </div>    
                <div class="toolbar">
                    <?php if($editable && $ficha->getFinalizado() == 0) { ?>
                        <input type="submit" id="guardar" value="Guardar" style="color: black;" <?php ?>>
                    <?php } ?>
                    <?php 
                    if( $ficha->getFinalizado() == 0 && $permisoHospital && $editable ) {?>
                        <input type="button" id="finalizar" value="Finalizar" style="color: black;">
                    <?php }
                    if( $permiteNuevaFicha ) {
                        //$linkNuevaFicha = encode_link("../sip_clap/ficha_sip.php", array("clavebeneficiario" => $clave, 'nuevaficha' => 'S'));
                    ?>
                        &nbsp;&nbsp;    
                        <input type="button" value="Nueva Ficha" id="nuevaFichaBtn" style="color: black;">
                    <?php } ?>

                    &nbsp;&nbsp;
               <!--     <input type="button" onclick="history.go(-1)"  id="goback" value="Volver" style="color: black;">-->
                    <input type="button" onclick="location.href='<?= $lnkVolver ?>'"  id="goback" value="Volver" style="color: black;">
                </div>
            </div>
            <input type="hidden" value="N" name="nuevaFicha" id="nuevaFicha" />
            <input type="hidden" value="<?=$ficha->getIdHcPerinatal() ?>" name="idHcPerinatal" id="id_hcperinatal" />
            <input type="hidden" value="<?=$ficha->getClaveBeneficiario() ?>" name="claveBeneficiario" id="clave_beneficiario" />
            <input type="hidden" value="<?=$_ses_user["id"] ?>" id="user" name="usuario" />
            <input type="hidden" value="<?=$ficha->getFinalizado() ?>" name="finalizarFicha" id="finalizarFicha"/>
            <input type="hidden" value="N" name="nuevaMultiple" id="nuevaMultiple"/>
            <input type="hidden" value="<?= $permisoHospital ?>" id="permisoHospital"/>
            
            <div id="ficha">
            <!-- SECCION I - IDENTIFICACION -->
            <?php include '01_identificacion.php'; ?>                        
            <!-- SECCION II - ANTECEDENTES -->
            <?php include '02_antecedentes.php'; ?>
            <!-- SECCION III - GESTACION ACTUAL I -->
            <?php include '03_gestacion_actual_parte1.php'; ?>
            <!-- SECCION IV - GESTACION ACTUAL II -->
            <?php include '04_gestacion_actual_parte2.php'; ?>
            <!-- SECCION V - CONSULTAS ANTENATALES -->
            <?php include '05_consultas_antenatales.php'; ?>
            <!-- SECCION VI - PARTO - ABORTO -->
            <?php include '06_parto_aborto.php'; ?>
            <!-- SECCION VII - TABAJO DE PARTO -->
            <?php include '07_trabajo_parto.php'; ?>
            <!-- SECCION VIII - NACIMIENTO I -->
            <?php include '08_nacimiento_parte1.php'; ?>
            <!-- SECCION IX - NACIMIENTO II -->
            <?php include '09_nacimiento_parte2.php'; ?>
            <!-- SECCION X - RECIEN NACIDO -->
            <?php include '10_recien_nacido.php'; ?>
            <!-- SECCION XI - ENFERMEDADES -->
            <?php include '11_rn_enfermedades.php'; ?>
            <!-- SECCION XII - EGRESO RN, MATERNO - ANTICONCEPCION -->
            <?php include '12_egreso.php'; ?>
            
            <input type="hidden" name="libres" id="libres">
            </div>
        </form>    
<br><br><br>
<script type="text/javascript">
$(document).ready(function() {
    init("<?=$editable; ?>");
    var salida = "<?=$datosFaltantes; ?>";
    if( salida.length >0){
        $("#dialog_faltantes").dialog({
            title: "Resumen de datos faltantes",
            autoOpen: true,
            position: { my: "center top", at: "center top", of: window },
            modal: true,
            width: 500,
            open: function(event, ui) { $(this).html(salida); }
        });  
    }
});
</script>
<?php fin_pagina(); ?>