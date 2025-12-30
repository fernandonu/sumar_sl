<?php
require_once("../../config.php");
require_once("clases/HCPerinatal.php");

$hcPerinatal = new HCPerinatal();

$ficha = $_POST;

//if( $ficha['op'] == 'SAVE' ){
    //Guardar la ficha 
require_once("clases/ConsultaPrenatal.php");
require_once("clases/ControlParto.php");
require_once("clases/ControlPuerperio.php");
require_once("clases/VariablesLibres.php");
require_once("../../clases/BeneficiariosUad.php");

$ConsultaPrenatal = new ConsultaPrenatal();
$controlParto     = new ControlParto();
$controlPuerperio = new ControlPuerperio();
$variablesLibres  = new VariablesLibres();
try {
    sql("BEGIN");
    
    validarDatosFicha($ficha); 
    
    
    // guardar nivel_01
    $result = $hcPerinatal->saveFichaSip($ficha);
    $idHc = $hcPerinatal->getIdHcPerinatal();
    // guardar nivel_02 - consulta prenatal
    $result = $ConsultaPrenatal->saveControlesPrenatales($idHc, $ficha['consulta']);
    // guardar nivel_03 - control parto
    $result = $controlParto->saveControlesParto($idHc, $ficha['control_parto']);
    // guardar nivel_04 - control post-parto o puerperio
    $result = $controlPuerperio->saveControlesPuerperio($idHc, $ficha['puerperio']);
    // guardar nivel_06 - variables libres
    $result = $variablesLibres->saveVariablesLibres($idHc, $ficha['libres']);

    // Marcar embarazada si corresponde
    $beneficiario = new BeneficiarioUad();
    $ben = $beneficiario->buscarPorClaveBeneficiario($clave);
    /*   echo '  Diag='.$ben->getFechaDiagnosticoEmbarazo();
      echo ' - FUM='.$ben->getFum();
      echo ' - FPP='.$ben->getFechaProbableParto(); */
    if ($hcPerinatal->getEstado() == 0 &&
            ( $ben->getFechaDiagnosticoEmbarazo() == '1899-12-30' ||
            $ben->getFechaDiagnosticoEmbarazo() == '' ||
            $ben->getFechaDiagnosticoEmbarazo() == '0' )) {
        $sql = "UPDATE uad.beneficiarios set estado_envio='n', fum='" . ConvFechaComoDB($hcPerinatal->getvar0057()) . "',
                        fecha_probable_parto='" . ConvFechaComoDB($hcPerinatal->getvar0058()) . "',fecha_diagnostico_embarazo=current_date  
                    where clave_beneficiario='" . $ben->getClaveBeneficiario() . "'";
        sql($sql);
        $valores = array('clase_documento_benef' => $ben->getClaseDocumentoBenef(), 'tipo_documento' => $ben->getTipoDocumento(), 'numero_doc' => $ben->getNumeroDoc(),
            'fum' => ConvFechaComoDB($hcPerinatal->getvar0057()), 'fecha_diagnostico_embarazo' => 'current_date', 'fecha_probable_parto' => ConvFechaComoDB($hcPerinatal->getvar0058()),
            'apellido_benef' => trim($ben->getApellidoBenef()), 'nombre_benef' => trim($ben->getNombreBenef()), 'apellido_benef_otro' => trim($ben->getApellidoBenefOtro()),
            'nombre_benef_otro' => trim($ben->getNombreBenefOtro()), 'fecha_nacimiento_benef' => $ben->getFechaNacimientoBenef(), 'fecha_inscripcion' => $ben->getFechaInscripcion(),
        );
        $ben->logCambios($valores);
    }


    //sql("ROLLBACK", "Error en rollback", 0);
    sql("COMMIT");
    //echo "La ficha ha sido guardada correctamente";
} catch (Exception $e) {
    sql("ROLLBACK", "Error en rollback", 0);
    echo "ERROR";
}

/*
if( $ficha['op'] == 'DELETE' ){
    try {
        sql("BEGIN");
        //Eliminar la ficha
        $result = $hcPerinatal->deleteFichaSip($ficha);
        sql("COMMIT");
        echo "La ficha ha sido eliminada correctamente";
    } catch (Exception $e) {
        sql("ROLLBACK", "Error en rollback", 0);
        echo "Hubo un error al eliminar la ficha.";
    }
    
}*/
    

function validarDatosFicha($ficha){
//    var_dump($ficha);die;
    
    return true;
    
}