<?php
require_once("../../config.php");
require_once("../../lib/bibliotecaTraeme.php");

error_reporting((E_ALL) & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);
ini_set('display_errors',1);


// Para uso Futuro, agregar validaciones acá
// Mejor acá que en otros archivos, 
switch ($_POST['type']) {
    case 'validarNuevaFicha':
        $validacion = array("valid"=>false);
        if ( validarNuevaFicha() ) {
            $validacion["valid"] = true;
        }
        echo json_encode( $validacion );
        break;
    
    default:
        break;
}


if( isset($_POST['fum']) ){
    // calculo de fpp por fum
    $fum = $_POST['fum'];
    $aux = strtotime ('7 day' , strtotime ( $fum ) ) ;
    $anio = date ( 'Y' , strtotime ( $fum ) );
    $dfpp = date ( 'd' , $aux );
    $aux = strtotime ('-3 month' , strtotime ( $fum ) ) ;
    $mfpp = date ( 'm' , $aux );
    if($mfpp < date( 'm' , strtotime ( $fum )) ){
        $anio += 1;
    }
    echo $dfpp.'-'.$mfpp.'-'.$anio;
    //echo date ( 'd-m-Y' , strtotime ('280 day' , strtotime ( $fum ) ) ) ;
    return false;
}

if( isset($_POST['edadgest']) ){
    // validaciones
    $edadgest = $_POST['edadgest'];
    $valor = $_POST['valor'];
    if($edadgest && $valor>0){
        $sql = "SELECT min,max
            FROM sip_clap.validacion 
            WHERE tabla = '".$_POST['tabla']."' 
            and edadg=".$edadgest;
        $result = sql($sql);
        // controlar si el valor cumple el rango
        if($result->recordCount() > 0){
            if( $valor < $result->fields['min']  || $valor > $result->fields['max']  )
                echo json_encode('error');
               // echo json_encode ( array('min'=>$result->fields['min'], 'max'=>$result->fields['max'] ) );
//                echo 'Para esta edad gestacional el valor debe estar entre '.$result->fields['min'] . ' y '.$result->fields['max'] ;
        }
    }
    return false;
}



/*
 * Validar cuie segun usuario
 */

if( isset($_POST['cuie']) ){
    echo validarCuie($_POST['tipo_cuie'],$_POST['cuie'],$_POST['user']);
}
function validarCuie($tipo,$cuie,$user){
    require_once("../../clases/Smiefectores.php");
    $efectoresSmi = new SmiefectoresColeccion();
    $efectores = $efectoresSmi->traeCuiesPorUsuario($user);
    if( in_array( $cuie,$efectores) ){
        if($tipo=='H'){
            $codHosp = array("HOS","HOS1","HOS2","HOS3");
            $efector = $efectoresSmi->buscarPorCUIE($cuie);
            if( !in_array($efector->getTipoEfector(), $codHosp) ){
                return 'NO';
            }            
        }
    }else{
        return 'NO';
    }    
}

/*
 * Verificar si usuario posee permiso para finalizar ficha.
 */
function verificarPermisoFinalizar($user) {
    require_once("../../clases/Smiefectores.php");
    $efectoresSmi = new SmiefectoresColeccion();
    $efectores = $efectoresSmi->traeCuiesPorUsuario($user);
    $efector = Null;
    $permiso = 0;
    $codHosp = array("HOS", "HOS1", "HOS2", "HOS3");
    foreach ($efectores as $key => $value) {
        $efector = $efectoresSmi->buscarPorCUIE($value);
        if ($efector != false) {
            if (in_array($efector->getTipoEfector(), $codHosp)) {
                $permiso = 1;
                break;
            }
        }
    }
    return $permiso;
}

/*
 * Validacion de campos para grabar
 */

function validarDatosFicha($formData) {
    $adicionales = array();
    $salida = '';
  if ($formData['finalizarFicha'] == 1) {
        // FINALIZACION DE FICHA
        $requeridas = getVariablesRequeridas(6);
        // controles adicionales
        // sifilis
        if ( $formData['var_0112'] == '' && $formData['var_0114'] == '' && $formData['var_0437'] == '' ) {
            array_push($adicionales, 'SIFILIS - PRUEBA NO TREPONEMICA NO REALIZADA' );
        }
        // sulfatos
        if ( ( $formData['var_0308'] == '18' || $formData['var_0309'] == '18' ) && $formData['var_0260'] == 'B' && $formData['var_0261'] == 'B'  ) {
            array_push($adicionales, 'SULFATO EN ECLAMPSIA O PREECLAMPSIA NO INDICADA' );
        }
        // HIV
        if ( $formData['var_0278'] == '76'  && $formData['var_0433'] == '' && $formData['var_0435'] == ''  && $formData['var_0438'] == '' ) {
            array_push($adicionales, 'HIV NO INDICADA' );
        }

    /*} else {
        // otra etapa anterior
        if($formData['var_0315']!=''){
            $edadgest = $formData['var_0315'];
        }elseif( $formData['var_0198']!='' ){
            $edadgest = $formData['var_0198'];
        }else{
            $consultas = array();
            foreach ($formData['consulta'] as $variable => $valores) {
                foreach ($valores as $key => $dato) {
                    $consultas[$key][$variable] = $dato;
                }
            }
            $ultcons = array_pop($consultas);
            $edadgest = $ultcons['var_0119'];
        }
        if( $edadgest>28 ){
            $requeridas = getVariablesRequeridas(3);
        }elseif( count($consultas)==1 ){
            $requeridas = getVariablesRequeridas(1);
        }else{
            $requeridas = getVariablesRequeridas(2);
        }
        
    }*/

    $i = 1;
    foreach ($requeridas as $key => $value) {
        if (($formData[$key]) == '') {
            $salida .= '<div>' . $i++ . ') ' . $key . ' - ' . $value . '</div>';
        }
    }
    // salida de la validacion
    if ($salida != '' || count($adicionales) > 0) {
        $header = "<h6 style='font-size:14px;text-align:center;'>&nbsp;&nbsp;Nombre y Apellido: " . $formData['var_0001'] . " " . $formData['var_0002'] . " <br> DNI:" . $formData['var_0019'] . "</h6>".
                '<br><div>Atendi&oacute; el parto: <strong>' . strtoupper($formData['var_0332']) . '</strong></div>' .
                '<div>Responsable egreso materno: <strong>' . strtoupper($formData['var_0391']) . '</strong></div>' .
                "<button style='float:right;' onclick='javascript:imprFaltantes();' type='button' title='Imprimir resumen' ><img src='../../imagenes/imp.gif' ></button>" .
                '<div>Responsable egreso reci&eacute;n nacido: <strong>' . strtoupper($formData['var_0390']) . '</strong></div>';

        if ($formData['finalizarFicha'] == 1) {
            $salida = $header . '<h3>No se ha generado el comprobante de Parto/Aborto por el faltante de los siguientes datos requeridos:</h3>' . $salida;
            foreach ($adicionales as $value) {
                $salida .= '<div>' . $i++ . ') ' . $value . '</div>';
            }
        } else {
            $salida = $header . '<h3>La ficha se ha guardado sin los siguientes datos:</h3>' . $salida;
        }
    }
    
  }
    return $salida;
}

/*
 * Listado de variables requeridas para las distintas instancias de guardado de la ficha
 */
function getVariablesRequeridas($etapa){
    $var_etapa1 = array( 
        'var_0053' => "EMBARAZO PLANEADO",
        'var_0054' => "FRACASO METODO ANTICONCEPTIVO", 
        'var_0057' => "FUM",
        'var_0058' => "FPP", 
        'var_0059' => "EG CONFIABLE POR FUM",
        'var_0061' => "FUMADORA ACTIVA 1º TRIM", 
        'var_0062' => "FUMADORA PASIVA 1º TRIM", 
        'var_0081' => "EXAMEN MAMAS NORMAL"  );
    $var_etapa2 = array(
        'var_0060' => "EG CONFIABLE POR ECO",
        "var_0077" => "ANTITETANICA",
        "var_0078" => "ANTITETANICA DOSIS 1",
        "var_0079" => "ANTITETANICA DOSIS 2",
        'var_0097' => "HIERRO",
        'var_0098' => "FOLATOS",
        'var_0101' => "CHAGAS",
        'var_0091' => "TAMIZAJE ANTENATAL - VIH < 20 SEM - SOLICITADO",
        'var_0433' => "TAMIZAJE ANTENATAL - PRUEBA VIH < 20 SEM",
        'var_0434' => "TAMIZAJE ANTENATAL - TARV VIH < 20 SEM",
        'var_0112' => "PRUEBA SIFILIS NO TREPONEMICA < 20 SEM",
        'var_0413' => "SEMANA PRUEBA SIFILIS NO TREPONEMICA < 20 SEM",
        'var_0414' => "SEMANA PRUEBA SIFILIS TREPONEMICA < 20 SEM",
        'var_0415' => "PRUEBA SIFILIS TREPONEMICA < 20 SEM",
        'var_0416' => "SEMANA TRATAMIENTO SIFILIS < 20 SEM",
        'var_0115' => "TRATAMIENTO SIFILIS < 20 SEM",
        'var_0418' => "TRATAMIENTO SIFILIS PAREJA < 20 SEM");
    $var_etapa3 = array(
        'var_0083' => "CERVIX PAP",        
        'var_0110' => "PREPARACION PARTO" ,
        'var_0111' => "CONSERJERIA LACTANCIA" ,
        'var_0093' => "TAMIZAJE ANTENATAL - VIH >= 20 SEM - SOLICITADO" ,
        'var_0435' => "TAMIZAJE ANTENATAL - PRUEBA VIH >= 20 SEM" ,
        'var_0436' => "TAMIZAJE ANTENATAL - TARV VIH >= 20 SEM" ,
        'var_0114' => "PRUEBA SIFILIS NO TREPONEMICA >= 20 SEM" ,
        'var_0419' => "SEMANA PRUEBA SIFILIS NO TREPONEMICA >= 20 SEM" ,
        'var_0420' => "SEMANA PRUEBA SIFILIS TREPONEMICA >= 20 SEM" ,
        'var_0421' => "PRUEBA SIFILIS TREPONEMICA >= 20 SEM" ,
        'var_0422' => "SEMANA TRATAMIENTO SIFILIS >= 20 SEM" ,
        'var_0423' => "TRATAMIENTO SIFILIS >= 20 SEM" ,
        'var_0424' => "TRATAMIENTO SIFILIS PAREJA >= 20 SEM" 
        );
    
    $finalizacion = array(
        "var_0017" =>"LUGAR CONTROL PRENATAL",
        "var_0018" =>"LUGAR PARTO/ABORTO",
        "var_0019" => "Nº IDENTIDAD",
        "var_0009" => "EDAD MATERNA",
        "var_0040" => "GESTAS PREVIAS",
        "var_0053" => "EMBARAZO PLANEADO",
        "var_0054" => "FRACASO METODO ANTICONCEPTIVO",
        "var_0061" => "FUMADORA ACTIVA 1º TRIM",
        "var_0066" => "FUMADORA ACTIVA 2º TRIM",
        "var_0071" => "FUMADORA ACTIVA 3º TRIM",
        "var_0083" => "CERVIX PAP", 
        "var_0097" => "HIERRO",
        "var_0098" => "FOLATOS",
     //   "var_0112" => "PRUEBA SIFILIS < 20 SEM - NO TREPONEMICA",
     //   "var_0114" => "PRUEBA SIFILIS >= 20 SEM - NO TREPONEMICA",
        "var_0101" => "CHAGAS",
        "var_0185" => "CONSULTA PRENATALES TOTAL",
        "var_0188" => "CORTICOIDES ANTENATALES",
        "var_0198" => "EDAD GESTACIONAL - SEMANAS",
        "var_0205" => "ACOMPAÑANTE PARTO",
        "var_0260" => "PREECLAMPSIA",
        "var_0261" => "ECLAMPSIA",
        "var_0282" => "NACIMIENTO",
        "var_0287" => "TERMINACION PARTO / ABORTO",
        "var_0292" => "EPISIOTOMIA",
        "var_0295" => "OCITOCICOS PREALUMBRAMIENTO",
        "var_0311" => "PESO AL NACER",
        "var_0335" => "DEFECTOS CONGENITOS",
        "var_0371" => "EGRESO",
        "var_0374" => "EDAD EGRESO",
        "var_0385" => "CONSERJERIA",
        "var_0386" => "METODO ANTICOCEP. ELEGIDO",
        "var_0077" => "ANTITETANICA",
        "var_0078" => "ANTITETANICA DOSIS 1",
        "var_0079" => "ANTITETANICA DOSIS 2",
        "var_0013" => "ESTUDIOS",
        "var_0322" => "APGAR 5º MINUTO",
        //"var_0432" => "VIH+",
        //"var_0433" => "PRUEBA VIH <20 SEM",
        //"var_0435" => "PRUEBA VIH >=20 SEM"
        );

    switch ($etapa) {
        case 1:
            $variables = $var_etapa1;
            break;
        case 2:
            $variables = $var_etapa1 + $var_etapa2;
            break;
        case 3:
            $variables = $var_etapa1 + $var_etapa2 + $var_etapa3;
            break;      
        case 6:
            $variables = $finalizacion;
        default:
            break;
    }
    return $variables;
}

     
/**
 * Busca consultas prenatales y setea lugar de consulta
 */
function getConsultasPrenatales($ficha, $beneficiario){
    require_once("../../clases/Prestacion.php");
    require_once("../../modulos/facturacion/funciones.php");
    require_once("clases/ConsultaPrenatal.php");
    $consultaPrenatal = new ConsultaPrenatal();

    // Consultas antenatales desde fecha de diagnostico de embarazo
    if($beneficiario->getFechaDiagnosticoEmbarazo()!='1899-12-30' && $beneficiario->getFechaDiagnosticoEmbarazo()!='' && $beneficiario->getFechaDiagnosticoEmbarazo()!='0' ){
        $diagnosticoEmbarazo = $beneficiario->getFechaDiagnosticoEmbarazo();
    }else{
        $diagnosticoEmbarazo = date('d/m/Y', strtotime ('-8 month' , strtotime ( $fecha ) ) ); 
    }
    
    $sql_total = getSQLCountPrestacionesBeneficiario(TRUE,$beneficiario->getNumeroDoc(),Fecha($diagnosticoEmbarazo),date('d/m/Y'));
    $registros = sql($sql_total) or fin_pagina();
    $totalConsultasFacturadas = $registros->fields['total'];
    
    //Consultas SIP
    if($ficha->getIdHcPerinatal()){
        $consultasSIP = array();
        $consultasSIP2 = $consultaPrenatal->getControlByHcPerinatal($ficha->getIdHcPerinatal());
        $totalConsultasSIP = count($consultasSIP2);
        $codigo = $diagnostico = '';
        if($totalConsultasSIP>0){
            foreach ($consultasSIP2 as $csip){
                if($csip['id_nomenclador']!=''){
                    $nomSip = getNomencladorSipById($csip['id_nomenclador']);
                    $codigo = $nomSip['codigo'];
                    $diagnostico = $nomSip['diagnostico'] ;
                }
                $control = array(
                              'id_control_prenatal' => $csip['id_control_prenatal'],
                              'id_hcperinatal' => $csip['id_hcperinatal'],
                              'id_efector' => $csip['id_efector'],
                              'var_0116' => $csip['var_0116'],
                              'var_0117' => $csip['var_0117'],
                              'var_0118' => $csip['var_0118'],
                              'var_0119' => $csip['var_0119'],
                              'var_0120' => $csip['var_0120'],
                              'var_0121' => $csip['var_0121'],
                              'var_0394' => $csip['var_0394'],
                              'var_0122' => $csip['var_0122'],
                              'var_0123'=>$csip['var_0123'],
                              'var_0124'=>$csip['var_0124'],
                              'var_0125'=>$csip['var_0125'],
                              'var_0393'=>$csip['var_0393'],
                              'var_0126'=>$csip['var_0126'],
                              'var_0127'=>$csip['var_0127'], 
                              'var_0128'=>$csip['var_0128'],
                              'var_0129'=>$csip['var_0129'],
                              'anio_prox_cita'=>$csip['anio_prox_cita'],
                              'trazadora_id_emb' => $csip['trazadora_id_emb'],
                              'facturacion_id_prestacion' => $csip['facturacion_id_prestacion'],
                              'id_nomenclador' => $csip['id_nomenclador'],
                              'facturado' => '',
                              'fecha_consulta' => $csip['fecha_consulta'],
                              'codigo' =>$codigo,
                              'diagnostico'=>$diagnostico
                          );
                      array_push($consultasSIP, $control);
            }
        }
    }
    if( $totalConsultasFacturadas>0 || $totalConsultasSIP>0 ){
        // obtener consultas SIGEP
        $sql = getSQLPrestacionesBeneficiario(TRUE,$beneficiario->getNumeroDoc(),Fecha($beneficiario->getFechaDiagnosticoEmbarazo()),date('d/m/Y'));
        $consultasFacturadas = sql($sql) or fin_pagina();
        // obtener trazadora y buscar moda para lugar de atencion
        $cuies = array();
        $controles = $consultasSIGEP = array();
        $i = 0;
        while(!$consultasFacturadas->EOF){            
            if($consultasFacturadas->fields['id_prestacion']){
                $id_nomenclador = getIdNomencladorSip($consultasFacturadas->fields['id_nomenclador_detalle'],$consultasFacturadas->fields['codigo'],$consultasFacturadas->fields['diagnostico']);  
                
                if( $id_nomenclador>0 ){  
                  // controlar si corresponde segun nomenclador
                  $datos = array( 'trazadora' => 'EMB',
                                  'fecha_prestacion' => $consultasFacturadas->fields['fecha_comprobante'],
                                  'cod_nomenclador' =>  $consultasFacturadas->fields['cod_nomenclador'],
                                  'id_prestacion' => $consultasFacturadas->fields['id_prestacion'],
                                  'cuie' => $consultasFacturadas->fields['cuie'],
                                  'nro_doc' => $consultasFacturadas->fields['numero_doc'],
                                  'clave_benef' => $clave  );
                  $datos = (object)$datos;
                  $sql =  PrestacionColeccion::getSQLDatosTrazadora($datos);
                  $prestacion = sql($sql);

                  if($prestacion->fields){
                      $i += 1;
                      // armar fecha consulta
                      $fechaConsulta = explode('-', FechaView($prestacion->fields['fecha_control']));
                      $var0116 = $fechaConsulta[0];
                      $var0117 = $fechaConsulta[1];
                      $var0118 =  substr($fechaConsulta[2], 2) ;
                      $idControl = '';
                      if($ficha->getIdHcPerinatal()){
                          $idControl = $consultaPrenatal->getControlByPrestacion($ficha->getIdHcPerinatal(), $consultasFacturadas->fields['id_prestacion']);
                      }
                      //construir array de controles encontrados     
                      $control = array(
                              'id_control_prenatal' => $idControl,
                              'id_hcperinatal' => $ficha->getIdHcPerinatal(),
                              'id_efector' => $prestacion->fields['cuie'],
                              'var_0116' => $var0116,
                              'var_0117' => $var0117,
                              'var_0118' => $var0118,
                              'var_0119' => intval($prestacion->fields['sem_gestacion']),
                              'var_0120' => intval($prestacion->fields['peso_embarazada'])  ,
                              'var_0121' => intval($prestacion->fields['tension_arterial_maxima']),
                              'var_0394' => intval($prestacion->fields['tension_arterial_minima']),
                              'var_0122' => intval($prestacion->fields['altura_uterina']),
                              'var_0123'=>'','var_0124'=>'','var_0125'=>'','var_0393'=>'',
                              'var_0126'=>'','var_0127'=>'','var_0128'=>'','var_0129'=>'','anio_prox_cita'=>'',
                              'trazadora_id_emb' => intval($prestacion->fields['id_emb']),
                              'facturacion_id_prestacion' => intval($consultasFacturadas->fields['id_prestacion']),
                              'id_nomenclador' => $id_nomenclador,
                              'facturado' => $consultasFacturadas->fields['nro_fact_offline'],
                              'fecha_consulta' => $prestacion->fields['fecha_control'],
                              'codigo' =>$consultasFacturadas->fields['codigo'],
                              'diagnostico'=>$consultasFacturadas->fields['diagnostico']
                          );
                    if( !((int)$idControl > 0) ){
                      array_push($consultasSIGEP, $control);

                      // array de cuie
                      array_push($cuies, $consultasFacturadas->fields['cuie']);
                      if( $i>=12)     break;
                  }
                      


                  }
                }  
            }
          $consultasFacturadas->MoveNext();
        }
        // efector con mas consultas para lugar de control
        if($ficha->getVar0017()==''){
            $cuenta = array_count_values($cuies);
            arsort($cuenta);
            $ficha->setVar0017( key($cuenta) );            
        }

        if( $totalConsultasSIP>0 && count($consultasSIGEP)>0 ){
            // Merge consultas
            $controles = array_merge($consultasSIGEP,$consultasSIP);
        }elseif( $totalConsultasSIP>0 ){
            $controles = $consultasSIP;
        }else{
            $controles = $consultasSIGEP; 
        }
        // ordenar las consultas 
        $ord = usort( $controles, 
                create_function( '$x, $y', 
                    'return strtotime($x["fecha_consulta"]) - strtotime($y["fecha_consulta"]);'
                )
            );
        return $controles;
    }
}

/*
 * codigos de nomenclador segun efector y nomenclador_consultas_sip 
 */
if( isset($_POST['efectorNomenclador']) ){
    $codigos = getCodigosByEfector($_POST['efectorNomenclador']);
    echo json_encode( $codigos);  
}

function getCodigosByEfector($cuie){
    require_once ("../../clases/consultasDB.php");
    require_once ("../../clases/NomencladorDetalle.php");    
    require_once("../../clases/Smiefectores.php");
    // tipos de nomenclador para el efector
    $efectoresSmi = new Smiefectores();
    $efectoresSmi->setCuie($cuie);
    $tipos = $efectoresSmi->tiposDeNomenclador();
    if($tipos){
        $nomencladores="(";
        foreach ($tipos as $key => $value) {
            $nomencladores .= "'".$key."',";
        }
        $nomencladores = substr($nomencladores,0,-1);
        $nomencladores .= ")";

        // vigencia
        $nomencladorDetalle = new NomencladorDetalle();
        $sqlNomDetalle = $nomencladorDetalle->getSQlSelectWhere("'".date('Y/m/d', time())."' BETWEEN fecha_desde AND fecha_hasta");
        
        echo $sqlNomDetalle;

        $nomencladorDetalle->construirResult( sql($sqlNomDetalle) );

        // codigos
        $sql = "SELECT distinct codigo
                FROM sip_clap.nomenclador_consultas_sip 
                WHERE id_nomenclador_detalle=".$nomencladorDetalle->getIdNomencladorDetalle().
                " AND tipo_nomenclador IN ".$nomencladores ." ORDER BY codigo" ;

        // echo $sql;

        $result = sql($sql);  
        
        while (!$result->EOF) {
            $sqlDescripcion = "SELECT descripcion FROM nomenclador.descripciones WHERE codigo= '".$result->fields['codigo']."' LIMIT 1";
            $descripcion = sql($sqlDescripcion);
            $txtDescripcion = ( is_null($descripcion->fields['descripcion']) )?'':$descripcion->fields['descripcion'];
            $codigos[] = array( 'codigo'=>$result->fields['codigo'], 'descripcion'=>$txtDescripcion );
            $result->MoveNext();
        }
        return $codigos;

    }else return '';
   
}





/*
 * Diagnosticos
 */
if( isset($_POST['codigoNomenclador']) ){
    echo getDiagnosticosByCodigo($_POST['codigoNomenclador'],$_POST['cuieEfector']);
}
function getDiagnosticosByCodigo($codigo,$cuie){
    require_once("../../clases/Smiefectores.php");
    // tipos de nomenclador para el efector
    $efectoresSmi = new Smiefectores();
    $efectoresSmi->setCuie($cuie);
    $tipos = $efectoresSmi->tiposDeNomenclador();
    
    $nomencladores="(";
    foreach ($tipos as $key => $value) {
        $nomencladores .= "'".$key."',";
    }
    $nomencladores = substr($nomencladores,0,-1);
    $nomencladores .= ")";
    $sql = "SELECT  distinct nomenc.diagnostico, patolog.descripcion 
            FROM sip_clap.nomenclador_consultas_sip as nomenc 
            LEFT JOIN nomenclador.patologias as patolog ON nomenc.diagnostico=patolog.codigo".
            " WHERE nomenc.codigo='".$codigo."' AND nomenc.tipo_nomenclador IN ".$nomencladores ." ORDER BY nomenc.diagnostico";
    $result = sql($sql);  
    
    $diagnosticos = [];
    if ($result) { 
    while (!$result->EOF) {
        $descripcion = ( is_null($result->fields['descripcion']) )?'':$result->fields['descripcion'];
        $diagnosticos[] = array( 'diagnostico'=>$result->fields['diagnostico'], 'descripcion'=>$descripcion);
        $result->MoveNext();
    }
    }
    return json_encode( $diagnosticos);   
}


function getCodigosNomencladorConsultaSip(){
# Clase ConsultaDB
require_once ("../../clases/consultasDB.php");
# Clase NomencladorDetalle.php
require_once ("../../clases/NomencladorDetalle.php");    
require_once("../../lib/bibliotecaTraeme.php");
    $nomencladorDetalle = new NomencladorDetalle();
    // Nomenclador Detalle
    $sqlNomDetalle = $nomencladorDetalle->getSQlSelectWhere("'".date('Y/m/d', time())."' BETWEEN fecha_desde AND fecha_hasta");

    $nomencladorDetalle->construirResult( sql($sqlNomDetalle) );
    
   
    $sql = "SELECT * 
            FROM sip_clap.nomenclador_consultas_sip
            WHERE id_nomenclador_detalle=".$nomencladorDetalle->getIdNomencladorDetalle();
    $result = sql($sql);  
    while (!$result->EOF) {
        $nomenclador[] = $result->fields;
        $result->MoveNext();
    }
    return $nomenclador;
}


function getIdNomencladorSip($id_nomenclador_detalle,$codigo,$diagnostico){
    // devuelve id_nomenclador de una practica
    $sql = "SELECT id_nomenclador 
            FROM sip_clap.nomenclador_consultas_sip
            WHERE id_nomenclador_detalle=".$id_nomenclador_detalle.
            " AND codigo ='".$codigo."' AND diagnostico='".$diagnostico."' LIMIT 1";
    $result = sql($sql); 
    if( $result->fields ) return $result->fields['id_nomenclador'];
    else  return 0;
}
function getNomencladorSipById($id_nomenclador){
    // devuelve 
    $sql = "SELECT *
            FROM sip_clap.nomenclador_consultas_sip
            WHERE id_nomenclador=".$id_nomenclador." LIMIT 1";
    $result = sql($sql); 
    return $result->fields;
}



/*
 * GUARDAR COMPROBANTES
 */
function guardarComprobantesConsultasPrenatales($ficha, $user) {
# Clase Comprobante.php
    require_once ("../../clases/Comprobante.php");
# Clase Prestacion.php
    require_once ("../../clases/Prestacion.php");
# Clase ConsultaDB
    require_once ("../../clases/consultasDB.php");
# Clase Nomenclador.php
    require_once ("../../clases/Nomenclador.php");
# Clase NomencladorDetalle.php
    require_once ("../../clases/NomencladorDetalle.php");
# Clase ConsultaPrenatal.php
    require_once("clases/ConsultaPrenatal.php");

    // Datos generados
    $transaccion = false;
    $FechaActual = date('Y/m/d', time());
    $nomenclador = new Nomenclador();
    $beneficiarioUad = new BeneficiarioUad();
    $beneficiario = $beneficiarioUad->buscarPorClaveBeneficiario($ficha['claveBeneficiario']);
    $nomencladorDetalle = new NomencladorDetalle();
    $prenatal = new ConsultaPrenatal();
    // consultas antenatales de la ficha
    $controles = $prenatal->getArrayControles($ficha['consulta']);

    
    // var_dump($controles);

    foreach ($controles as $key => $control) {
        $prenatal->setControlPrenatal($control);
        $prenatal->setIdHcPerinatal($ficha['idHcPerinatal']);
        $fecha = $prenatal->getFechaConsulta();
        $efector = $prenatal->getIdEfector();
        $codigo = $control['codigo'];
        $diagnostico = $control['diagnostico'];
        $validacionComprobante = (strlen($control["var_0119"]) < 1) || (strlen($control["var_0120"]) < 1) || (strlen($control["var_0121"]) < 1) || (strlen($control["var_0394"]) < 1);
        // var_dump($control);
        // Viene si es un comprobante sumar
        if (  $validacionComprobante && ((int)$control["nuevo"] != "1") ) {
            // echo "COMPROBANTE SUMAR";
            // echo "<br>";
            //solo consulta SIP
            // echo "<br>CONSULTA SIP";
            // echo "<br>";
            // echo $key;
            //var_dump($control);

            if (!$control["facturado"]) {
                // print_r($ficha['consulta']);
                // echo "<br>";
                // echo "No Facturado";
                // echo "<br>";
                // $res = $prenatal->saveControlPrenatal();
            }

            // if ($res) $transaccion = true;
        } else {
            // echo "<br>NUEVO";

            $fechaPeriodo_pre = explode("-", $fecha);
            $fechaPeriodo = $fechaPeriodo_pre[0] . "/" . $fechaPeriodo_pre[1];
            
            // SQLS Adicionales
            // Nomenclador Detalle

            $fecha = convertirFechaADb($fecha);


            $sqlNomDetalle = $nomencladorDetalle->getSQlSelectWhere("'" . $fecha . "' BETWEEN fecha_desde AND fecha_hasta");
            $result = sql($sqlNomDetalle) or fin_pagina();
            $nomencladorDetalle->construirResult($result);
            // Nomenclador
                // echo "id: ".$nomencladorDetalle->getIdNomencladorDetalle()."<br>";
            if ($nomencladorDetalle->getIdNomencladorDetalle() > 0) {
                // $sqlWhereNomenclador = "codigo = '" . $codigo . "' AND id_nomenclador_detalle = " . $nomencladorDetalle->getIdNomencladorDetalle() . " AND diagnostico = '" . $diagnostico . "' AND tipo_nomenclador = 'BASICO' ";
                $sqlWhereNomenclador = "id_nomenclador_detalle = " . $nomencladorDetalle->getIdNomencladorDetalle()
										. "AND codigo='C005' and descripcion ilike '%control%'";
                $sqlNomenclador = $nomenclador->getSQlSelectWhere($sqlWhereNomenclador);
                $result = sql($sqlNomenclador) or fin_pagina();
                $nomenclador->construirResult($result);

                // Periodo
                $sqlIdPeriodo = "Select * from facturacion.periodo where periodo = '" . $fechaPeriodo . "'";
                $idPeriodo = sql($sqlIdPeriodo)->fields["id_periodo"];
            }

            
            // Nuevo Control
            if ( ((int)$control["nuevo"] == "1") ) {
                // echo "<br>NUEVO COMPROBANTE y CONSULTA SIP";
                // NUEVO COMPROBANTE y CONSULTA SIP
                // generar nuevo comprobante y grabar control
                if ( $nomenclador->getIdNomenclador() > 0 ) {
                    // Comprobante
                    $Comprobante = new Comprobante();
                    $Comprobante->setCuie($efector);
                    $Comprobante->setIdFactura("NULL");
                    $Comprobante->setNombreMedico("");
                    $Comprobante->setFechaComprobante($fecha);
                    $Comprobante->setClavebeneficiario($ficha['claveBeneficiario']);
                    $Comprobante->setFechaCarga($FechaActual);
                    $Comprobante->setMarca(0);
                    // $Comprobante->setIdperiodo($idPeriodo);
                    $Comprobante->setActivo(1);
                    // $Comprobante->setGrupoEtario($beneficiario->getGrupoEtareo($FechaActual));
                    // $Comprobante->setIdNomencladorDetalle($nomencladorDetalle->getIdNomencladorDetalle());
                    // $Comprobante->setTipoNomenclador($nomenclador->getTipoNomenclador());
                    // $Comprobante->setUsuario($user);
                    //$idComprobante = $Comprobante->guardarComprobante(); actualizacion Sept.2019
                    $Comprobante->setIdComprobante($idComprobante);
                    // FIX COMPROBANTE 0
                    
					//actualizacion Sept.2019
					//$sqlFIXCOMP = "update facturacion.comprobante set marca = 0, periodo = (select to_char(fecha_comprobante, 'YYYY/MM') from facturacion.comprobante where id_comprobante = ".$idComprobante.") where id_comprobante = ".$idComprobante.";";
                    //sql($sqlFIXCOMP) or fin_pagina();


                    // Prestacion
                    $Prestacion = new Prestacion();
                    $Prestacion->setIdComprobante($idComprobante);
                    $Prestacion->setIdNomenclador($nomenclador->getIdNomenclador());
                    $Prestacion->setCantidad(1);

                    if ( beneficiarioEmbarazadoUAD( $beneficiario->getClaveBeneficiario(), $fecha) ) {
                        $precio = $nomenclador->getPrecioSegunGrupo("embarazada");
                    }else{
                        $precio = $nomenclador->getPrecioSegunGrupo( $beneficiario->getGrupoEtareo($FechaActual) );
                    }
                    
                    // $Prestacion->setPrecioPrestacion( $precio );

                    //$idPrestacion = $Prestacion->guardarPrestacion(); actualizacion Sept.2019
                    $Prestacion->setIdPrestacion($idPrestacion);
                    $prenatal->setFacturacionIdPrestacion($idPrestacion);

                    // cargar trazadora
                    $q = "select nextval('trazadoras.embarazadas_id_emb_seq') as id_planilla";
                    $id_planilla = sql($q) or fin_pagina();
                    $id_planilla = $id_planilla->fields['id_planilla'];
                    $fechaFUM = (($ficha['var_0057'] == '') ? 'NULL' : "'" . convertirFechaADb($ficha['var_0057']) . "'" );
                    $fechaFPP = (($ficha['var_0058'] == '') ? 'NULL' : "'" . convertirFechaADb($ficha['var_0058']) . "'" );

                    if ($prenatal->getVar0121() == 'NULL' || $prenatal->getVar0394() == 'NULL') {
                      $ta = 'NULL';
                    }
                    else {
                      $ta = "'" . $prenatal->getVar0121() . "/" . $prenatal->getVar0394() . "'";
                    }
                    $sqlTrazadora = "INSERT INTO trazadoras.embarazadas (id_emb,cuie,clave,tipo_doc,num_doc,apellido,nombre,fecha_control,
                                sem_gestacion,fum,fpp,fecha_carga,usuario,ta)
                                values (" . $id_planilla . ",'" . $efector . "','" . $ficha['claveBeneficiario'] . "','" . $beneficiario->getTipoDocumento() . "','" .
                            $beneficiario->getNumeroDoc() . "','" . $beneficiario->getApellidoBenef() . "','" . $beneficiario->getNombreBenef() . "','" .
                            convertirFechaADb($fecha) . "','" . $prenatal->getVar0119() . "'," . $fechaFUM . "," . $fechaFPP . ",current_timestamp," .
                            $user . ", " . $ta . ")";
    
                    // $sqlTrazadora = "INSERT INTO trazadoras.embarazadas (id_emb,cuie,clave,tipo_doc,num_doc,apellido,nombre,fecha_control,
                    //             sem_gestacion,fum,fpp,fecha_carga,usuario,peso_embarazada,tension_arterial_maxima,tension_arterial_minima, id_prestacion_sigep)
                    //             values (" . $id_planilla . ",'" . $efector . "','" . $ficha['claveBeneficiario'] . "','" . $beneficiario->getTipoDocumento() . "','" .
                    //         $beneficiario->getNumeroDoc() . "','" . $beneficiario->getApellidoBenef() . "','" . $beneficiario->getNombreBenef() . "','" .
                    //         convertirFechaADb($fecha) . "','" . $prenatal->getVar0119() . "'," . $fechaFUM . "," . $fechaFPP . ",current_timestamp," .
                    //         $user . "," . $prenatal->getVar0120() . "," . $prenatal->getVar0121() . "," . $prenatal->getVar0394() . ", ".$idPrestacion.")";
    

                    //sql($sqlTrazadora) or fin_pagina();

                    $prenatal->setTrazadoraIdEmb($id_planilla);
            
                    $prenatal->setidNomenclador($nomenclador->getIdNomenclador());
                    $prenatal->Insertar();
                    $transaccion = true;
                }
            } else {
                // ACTUALIZAR COMPROBANTE SUMAR
                // echo "<br>ACTUALIZAR COMPROBANTE SUMAR";
                if($control['facturado']==''){
                    // no esta facturado

                }

                // INSERT OR UPDATE CONSULTA SIP
                // echo "<br>INSERT OR UPDATE CONSULTA SIP";
                // $res = $prenatal->saveControlPrenatal();
                // echo "NO NUEVO";
                // print_r($prenatal);
                $transaccion = true;
            }
        }
    }

    return $transaccion;
}


function guardarComprobante($ficha,$user,$codigo,$diagnostico){
# Clase Comprobante.php
require_once ("../../clases/Comprobante.php");
# Clase Prestacion.php
require_once ("../../clases/Prestacion.php");
# Clase ConsultaDB
require_once ("../../clases/consultasDB.php");
# Clase Nomenclador.php
require_once ("../../clases/Nomenclador.php");
# Clase NomencladorDetalle.php
require_once ("../../clases/NomencladorDetalle.php");

    // Datos generados
    $transaccion = false;
    $FechaActual = date('Y/m/d', time());
    $nomenclador = new Nomenclador();
    $beneficiarioUad = new BeneficiarioUad();
    $beneficiario = $beneficiarioUad->buscarPorClaveBeneficiario($ficha['claveBeneficiario']);
    $nomencladorDetalle = new NomencladorDetalle();

    $medico = ($codigo=='IT Q002'||$codigo=='IT Q001')?$ficha['var_0332']:'';
    $fecha = Fecha_db($ficha['var_0284']);
    $efector = $ficha['var_0018'];
    $fechaPeriodo_pre = explode("-", $fecha);
    $fechaPeriodo = $fechaPeriodo_pre[0]."/".$fechaPeriodo_pre[1];
    $fechaPeriodo = "2015/07"; // REVISAR (NN)
    $fecha = '2015-07-01'; // REVISAR (NN)

    // SQLS Adicionales
    // Nomenclador Detalle
    $sqlNomDetalle = $nomencladorDetalle->getSQlSelectWhere("'".$fecha."' BETWEEN fecha_desde AND fecha_hasta");
    $result = sql($sqlNomDetalle) or fin_pagina();
    $nomencladorDetalle->construirResult( $result );
    var_dump($result->fields);

    // Nomenclador
    /*if ($nomencladorDetalle->getIdNomencladorDetalle() > 0) {
            $sqlWhereNomenclador = "codigo = '".$codigo."' AND id_nomenclador_detalle = ".$nomencladorDetalle->getIdNomencladorDetalle()." AND diagnostico = '".$diagnostico."' AND tipo_nomenclador = 'BASICO' ";
            $sqlNomenclador = $nomenclador->getSQlSelectWhere($sqlWhereNomenclador);
            $result = sql($sqlWhereNomenclador) or fin_pagina();
            $nomenclador->construirResult( $result );

            // Periodo
            $sqlIdPeriodo = "Select * from facturacion.periodo where periodo = '".$fechaPeriodo."'";
            $idPeriodo = sql($sqlIdPeriodo)->fields["id_periodo"];
    }*/
    
    //Verificar que no exista el comprobante
    $desde = date('Y-m-d', strtotime ('-30 day' , strtotime ( $fecha ) ) );
    $hasta = date('Y-m-d', strtotime ('+30 day' , strtotime ( $fecha ) ) );
    $sqlComp = sql("SELECT count(*) as cant FROM facturacion.comprobante c INNER JOIN facturacion.prestacion p ON p.id_comprobante=c.id_comprobante"
        . " WHERE p.id_nomenclador=".$nomenclador->getIdNomenclador()." AND c.id_nomenclador_detalle=  ".$nomencladorDetalle->getIdNomencladorDetalle()
        . " AND c.cuie='".$efector."' AND c.clavebeneficiario='".$ficha['claveBeneficiario']."' AND c.fecha_comprobante BETWEEN '".$desde."' AND '".$hasta."' " );

    if($sqlComp->fields['cant']==3 || True){
        if ( $nomenclador->getIdNomenclador() > 0 ) {
            // Comprobante
            $Comprobante = new Comprobante();
            $Comprobante->setCuie( $efector );
            $Comprobante->setIdFactura("NULL");
            $Comprobante->setNombreMedico($medico);
            $Comprobante->setFechaComprobante($fecha);
            $Comprobante->setClavebeneficiario($ficha['claveBeneficiario']);
            $Comprobante->setFechaCarga($FechaActual);
            $Comprobante->setMarca(0);
            $Comprobante->setIdperiodo($idPeriodo);
            $Comprobante->setActivo(1);
            $Comprobante->setGrupoEtario( $beneficiario->getGrupoEtareo($FechaActual) );
            $Comprobante->setIdNomencladorDetalle( $nomencladorDetalle->getIdNomencladorDetalle() );
            $Comprobante->setTipoNomenclador( $nomenclador->getTipoNomenclador() );
            $Comprobante->setUsuario($user);
            $idComprobante = $Comprobante->guardarComprobante();
            $Comprobante->setIdComprobante($idComprobante);

            #!!!!!!!!!!!!!!!!
            $idComprobante == -1; // REVISAR (NN)
            if ($idComprobante == 0 || $idComprobante == "0") {
                $sqlDeleteComprobante = "DELETE from facturacion.comprobante where id_comprobante = ".$idComprobante."";
                $resultDeleteComprobante = sql($sqlDeleteComprobante);
                $transaccion = true;
            }else{

                // FIX COMPROBANTE MARCA 0
                $sqlFIXCOMP = "update facturacion.comprobante set marca = 0, periodo = (select to_char(fecha_comprobante, 'YYYY/MM') from facturacion.comprobante where id_comprobante = ".$idComprobante.") where id_comprobante = ".$idComprobante.";";
                sql($sqlFIXCOMP);

                // Prestacion
                $Prestacion = new Prestacion();
                $Prestacion->setIdComprobante($idComprobante);
                $Prestacion->setIdNomenclador( $nomenclador->getIdNomenclador() );
                $Prestacion->setCantidad(1);

                $ge = $beneficiario->getGrupoEtareo($FechaActual);
                if ( $beneficiario->getEmbarazado($FechaActual) ) {
                    $ge = "embarazada";
                }
                // $Prestacion->setPrecioPrestacion( $nomenclador->getPrecioSegunGrupo( $ge ) );

                echo $ge;
                echo $nomenclador->getPrecioSegunGrupo( $ge );

                $idPrestacion = $Prestacion->guardarPrestacion();
                $Prestacion->setIdPrestacion($idPrestacion);

                // SI CODIGO ES IT VER GUARDADO EN TRAZADORA PARTO
                if($codigo=='IT Q002'||$codigo=='IT Q001'){
                    $q = "select nextval('trazadoras.partos_id_par_seq') as id_planilla";
                    $id_planilla = sql($q) or fin_pagina();
                    $id_planilla = $id_planilla->fields['id_planilla'];
                    $fecha_conserjeria = "1980-01-01";
                    $vdrl =( $ficha['var_0343'] =='B')?'S':'N';
                    $antitetanica =( $ficha['var_0077'] =='B')?'S':'N';
                    $query = "insert into trazadoras.partos
                             (id_par,cuie,clave,tipo_doc,num_doc,apellido,nombre,fecha_parto,apgar,peso,vdrl,antitetanica,fecha_conserjeria,observaciones,
                                fecha_carga,usuario, fecha_nacimiento)
                             values
                                (" . $id_planilla . ",'" . $efector . "','" . $ficha['claveBeneficiario'] . "','" . $beneficiario->getTipoDocumento() . "','" .
                                $beneficiario->getNumeroDoc() . "','" . $beneficiario->getApellidoBenef() . "','" . $beneficiario->getNombreBenef() . "','" .
                                $fecha . "','" . $ficha['var_0321'] . "','" . $ficha['var_0311'] . "','".$vdrl."','".$antitetanica."','".$fecha_conserjeria."','',current_timestamp, " .
                                $user . ", '".$fecha."' )";
                    

                    sql($query, "Error al generar trazadora de parto");
                }

                $transaccion = true;
            }
            
            
        }
    }else $transaccion = true;
    
    return $transaccion;
}

// Funciones Externas
/**
 * @return string
 * @param fecha_db string
 * @desc Convierte una fecha de la forma AAAA-MM-DD
 *       a la forma DD/MM/AAAA
 */
function FechaView($fecha_db) {
	$m = substr($fecha_db,5,2);
	$d = substr($fecha_db,8,2);
	$a = substr($fecha_db,0,4);
	if (is_numeric($d) && is_numeric($m) && is_numeric($a)) {
		return "$d-$m-$a";
	}
	else {
		return $fecha_db;
	}
}

// Cambio de fecha
function convertirFechaADb($fecha){
    $data = explode("-", $fecha);

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

function cargar_beneficiario($clave) {
  $sql = "SELECT * FROM uad.beneficiarios WHERE clave_beneficiario = '".$clave."'";
  $result = sql($sql);
  if ($result->recordCount() > 0) {
    return $result->fields;
  }
  else {
    return NULL;
  }
}
