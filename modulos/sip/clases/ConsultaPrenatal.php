<?php

class ConsultaPrenatal{
   private $idControlPrenatal;
   private $idHcPerinatal;
   private $idEfector;
   private $var0116;
   private $var0117;
   private $var0118;
   private $var0119;
   private $var0120;
   private $var0121;
   private $var0394;
   private $var0122;
   private $var0123;
   private $var0124;
   private $var0125;
   private $var0393;
   private $var0126;
   private $var0127;
   private $var0128;
   private $var0129;
   private $anioProxCita;
   private $fechaConsulta;
   private $idNomenclador;
   private $trazadoraIdEmb;
   private $facturacionIdPrestacion;
   

    public function construirResult($result) {
        $this->idControlPrenatal = $result['id_control_prenatal'];
        $this->idHcPerinatal = $result['id_hcperinatal'];
        $this->idEfector = $result['id_efector'];
        $this->var0116 = $result['var_0116'];
        $this->var0117 = $result['var_0117'];
        $this->var0118 = $result['var_0118'];
        $this->var0119 = $result['var_0119'];
        $this->var0120 = $result['var_0120'];
        $this->var0121 = $result['var_0121'];
        $this->var0394 = $result['var_0394'];
        $this->var0122 = $result['var_0122'];
        $this->var0123 = $result['var_0123'];
        $this->var0124 = $result['var_0124'];
        $this->var0125 = $result['var_0125'];
        $this->var0393 = $result['var_0393'];
        $this->var0126 = substr( trim(utf8_decode( $result['var_0126'] )), 0, 100); 
        $this->var0127 = $result['var_0127'];
        $this->var0128 = $result['var_0128'];
        $this->var0129 = $result['var_0129'];
        $this->anioProxCita = $result['anio_prox_cita'];    
        $this->fechaConsulta = $result['fecha_consulta'];
        $this->idNomenclador = $result['id_nomenclador'];
        $this->trazadoraIdEmb = $result['trazadora_id_emb'];
        $this->facturacionIdPrestacion = $result['facturacion_id_prestacion'];        
    }
    
	/*
     * integer $idControlPrenatal
     */
    public function setIdControlPrenatal($idControlPrenatal) {
        $this->idControlPrenatal = $idControlPrenatal;
    }    
    public function getIdControlPrenatal()
    {
        return $this->idControlPrenatal;
    }

    /*
     * integer $idHcPerinatal
     */
    public function setIdHcPerinatal($idHcPerinatal) {
        $this->idHcPerinatal = $idHcPerinatal;
    }    
    public function getIdHcPerinatal()
    {
        return $this->idHcPerinatal;
    }
    /*
     * integer $idEfector
     */
    public function setIdEfector($idEfector) {
        $this->idEfector = $idEfector;
    }    
    public function getIdEfector()
    {
        return $this->idEfector;
    }

    /*
 	* GETTERS
 	*/  
    /*
     * integer $var0116 -- cons_dia
     */
    public function getVar0116()
    {
        return $this->var0116;
    }
    /*
     * integer $var0117 -- cons_mes
     */
    public function getVar0117()
    {
        return $this->var0117;
    }
    /*
     * integer $var0118 -- cons_anio
     */
    public function getVar0118()
    {
        return $this->var0118;
    }
    /*
     * date $fechaConsulta
     */
    public function getFechaConsulta()
    {
      /*  $fecha = '';
        if($this->var0118){
            if ( $this->var0118 > date('y')) $anio = '19' . $this->var0118;
            else $anio = '20' . $this->var0118;
            $fecha =  $this->var0116.'-'.$this->var0117.'-'.$anio;
        }
    	return $fecha;*/
        return $this->fechaConsulta;
    }

    /*
     * integer $var0119 -- cons_edad_gestacional
     */
    public function getVar0119()
    {
        return $this->var0119;
    }
    /*
     * integer $var0120 -- cons_peso
     */
    public function getVar0120()
    {
        return $this->var0120;
    }
    /*
     * integer $var0121 -- cons_pa_sistolica
     */
    public function getVar0121()
    {
        return $this->var0121;
    }
    /*
     * integer $var0394 -- cons_pa_diastolica
     */
    public function getVar0394()
    {
        return $this->var0394;
    }
    /*
     * integer $var0122 -- cons_altura_uterina
     */
    public function getVar0122()
    {
        return $this->var0122;
    }
    /*
     * integer $var0123 -- cons_presentacion
     */
    public function getVar0123()
    {
        return $this->var0123;
    }
    /*
     * integer $var0124 -- cons_fcf
     */
    public function getVar0124()
    {
        return $this->var0124;
    }
    /*
     * integer $var0125 -- cons_movimientos_fetales
     */
    public function getVar0125()
    {
        return $this->var0125;
    }
    /*
     * integer $var0393 -- cons_proteinuria
     */
    public function getVar0393()
    {
        return $this->var0393;
    }
    /*
     * integer $var0126 -- cons_signos_examenes_tratamientos
     */
    public function getVar0126()
    {
        return $this->var0126;
    }
    /*
     * integer $var0127 -- cons_iniciales_tecnicos
     */
    public function getVar0127()
    {
        return $this->var0127;
    }
    /*
     * integer $var0128 -- cons_proxima_cita_dia
     */
    public function getVar0128()
    {
        return $this->var0128;
    }
    /*
     * integer $var0129 -- cons_proxima_cita_mes
     */
    public function getVar0129()
    {
        return $this->var0129;
    }
    /*
     * date $fechaConsulta
     */
    public function getFechaProximaConsulta()
    {
        return $this->var0128.'-'.$this->var0129.'-'.$this->anioProxCita;
    }
    /*
     * integer $idNomenclador -- identificador nomenclador_consultas_sip
     */
    public function getidNomenclador()
    {
        return $this->idNomenclador;
    }
    public function setidNomenclador($idNomenclador) {
        $this->idNomenclador = $idNomenclador;
    }
    /*
     * integer $trazadoraIdEmb -- identificador trazadoras.embarazadas.id_emb
     */
    public function getTrazadoraIdEmb()
    {
        return $this->trazadoraIdEmb;
    }
    public function setTrazadoraIdEmb($trazadoraIdEmb) {
        $this->trazadoraIdEmb = $trazadoraIdEmb;
    } 
    /*
     * integer $facturacionIdPrestacion -- identificador facturacion.prestacion.id_prestacion
     */
    public function getFacturacionIdPrestacion()
    {
        return $this->facturacionIdPrestacion;
    }
    public function setFacturacionIdPrestacion($facturacionIdPrestacion) {
        $this->facturacionIdPrestacion = $facturacionIdPrestacion;
    } 

/*
 * SETTERS
 */    
    public function setControlPrenatal($control) {
        $this->idControlPrenatal = $control['id_control_prenatal'];
        $this->idEfector = substr( trim( $control['id_efector'] ), 0, 6); 
        // fecha_consulta
        $fechaConsulta = explode('-',$control['fecha_consulta']);
      	$this->var0116 = $fechaConsulta[0];
      	$this->var0117 = $fechaConsulta[1];
      	$this->var0118 =  substr($fechaConsulta[2], 2) ;
        $this->fechaConsulta = $control['fecha_consulta'];
        $this->var0119 = ($control['var_0119']=='') ? 'NULL' : $control['var_0119'];
        $this->var0120 = ($control['var_0120']=='') ? 'NULL' : $control['var_0120'];
        $this->var0121 = ($control['var_0121']=='') ? 'NULL' : $control['var_0121'];
        $this->var0394 = ($control['var_0394']=='') ? 'NULL' : $control['var_0394'];
        $this->var0122 = ($control['var_0122']=='') ? 'NULL' : $control['var_0122'];
      	$this->var0123 = $control['var_0123'];
        $this->var0124 = ($control['var_0124']=='') ? 'NULL' : $control['var_0124'];
      	$this->var0125 = $control['var_0125'];
      	$this->var0393 = $control['var_0393'];
      	$this->var0126 = substr( trim(utf8_decode( $control['var_0126'] )), 0, 100); 
      	$this->var0127 = substr( trim( $control['var_0127'] ), 0, 6); 
		// fecha_proxima_consulta
        $fechaProximaConsulta = explode('-',$control['fecha_proxima']);
      	$this->var0128 = $fechaProximaConsulta[0];
      	$this->var0129 = $fechaProximaConsulta[1];
      	$this->anioProxCita = $fechaProximaConsulta[2];
        $this->idNomenclador = $control['id_nomenclador'];
        $this->trazadoraIdEmb = ($control['trazadora_id_emb']=='') ? 'NULL' : $control['trazadora_id_emb'];
        $this->facturacionIdPrestacion = ($control['facturacion_id_prestacion']=='') ? 'NULL' : $control['facturacion_id_prestacion'];
    }    

    /*
 * FUNCIONES ADICIONALES
 */    
    
    public function getControlByHcPerinatal($idHcPerinatal) {
        $sql = "SELECT * 
                FROM sip_clap.control_prenatal
                WHERE id_hcperinatal='$idHcPerinatal' order by var_0119";
        $result = sql($sql) or die($sql);
        while (!$result->EOF) {
            $controlesPrenatales[] = $result->fields;
            $result->MoveNext();
        }
        return $controlesPrenatales;
    }
    /*
     * Devuelve el id del control segun la prestacion y ficha
     */
    public function getControlByPrestacion($idHcPerinatal,$idPrestacion) {
        $idControl = null;
        $sql = "SELECT id_control_prenatal 
                FROM sip_clap.control_prenatal
                WHERE id_hcperinatal='$idHcPerinatal' AND facturacion_id_prestacion='$idPrestacion' LIMIT 1";
        $result = sql($sql);
        if($result){
          $idControl = $result->fields['id_control_prenatal']; 
        }
        return $idControl;
    }
    
    /*
     * Devuelve array de controles desde el recibido del formulario
     */
    public function getArrayControles($controles){
        $arrayControles = array();
        if($controles){
            foreach ($controles as $variable => $valores) {
                foreach ($valores as $key => $dato) {
                    $arrayControles[$key][$variable] = $dato;
                }
            }
        }
        return $arrayControles;
    }

    public function saveControlPrenatal(){
        if( strlen($this->idControlPrenatal) < 1 ){
            //cargar nuevo control
            $this->Insertar();
        }else{
            // actualizar control existente
            $this->Actualizar();
        }
    }

    public function saveControlesPrenatales($ficha, $controles) {
        /* Setear valores del formulario */
        if($controles){
            $arrayControles = array();
            foreach ($controles as $variable => $valores) {
                foreach ($valores as $key => $dato) {
                    $arrayControles[$key][$variable] = $dato;
                }
            }
            foreach ($arrayControles as $key => $control) {
                if(!empty($control['id_efector'])){
                    $this->setControlPrenatal($control);
                    $this->setIdHcPerinatal($ficha->getIdHcPerinatal());
                    
                    if($this->facturacionIdPrestacion=='NULL'){
                        // Nueva consulta ingresada en la ficha - crear comprobante y control
                        try{
                            // grabar comprobante
                            $datos =  $this->guardarComprobante($ficha, $control);
                            if($datos){
                                $this->facturacionIdPrestacion =$datos['prestacion'];

                                //grabar control
                                $this->Insertar();
                            }else{
                                throw new Exception;
                            }
                        } catch (Exception $ex) {

                        }
                    }else{
                        // Comprobante ya generado en sigep
                        if($this->idControlPrenatal==NULL){
                            //cargar nuevo control
                            $this->Insertar();
                        }else{
                            // actualizar control existente
                            $this->Actualizar();
                        }
                        //verificar si hubieron cambios en el comprobante para actualizar
                        
                        
                    }
                    
                }    
            }    
        }
    }


    public function Insertar() {
        // CONTROL PRENATAL
        if ( strlen( trim($this->anioProxCita) ) < 3 ) {
            $anioProxCita = "NULL";
        }else{
            $anioProxCita = "'".$this->anioProxCita."'";
        }

        if ( strlen( trim($this->fechaConsulta) ) < 3 ) {
            $fechaConsulta = "NULL";
        }else{
            $fechaConsulta = "'".$this->convertirFechaADb($this->fechaConsulta)."'";
        }
        $idNomenc = ( empty($this->idNomenclador)?0:$this->idNomenclador );
        $idEmb = ( empty($this->trazadoraIdEmb)?0:$this->trazadoraIdEmb );
        $idPrest = ( empty($this->facturacionIdPrestacion)?0:$this->facturacionIdPrestacion );
        $sql = "INSERT INTO sip_clap.control_prenatal(
                    id_hcperinatal, id_efector, var_0116, var_0117, var_0118, 
                    var_0119, var_0120, var_0121, var_0394, var_0122, var_0123, 
                    var_0124, var_0125, var_0393, var_0126, var_0127, var_0128, 
                    var_0129, anio_prox_cita, fecha_consulta, id_nomenclador, trazadora_id_emb, facturacion_id_prestacion)
            VALUES (
                    $this->idHcPerinatal, 
                    '$this->idEfector', 
                    '$this->var0116', 
                    '$this->var0117', 
                    '$this->var0118',
                    $this->var0119, 
                    $this->var0120, 
                    $this->var0121, 
                    $this->var0394, 
                    $this->var0122, 
                    '$this->var0123',
                    $this->var0124, 
                    '$this->var0125', 
                    '$this->var0393', 
                    '$this->var0126', 
                    '$this->var0127',
                    '$this->var0128', 
                    '$this->var0129', 
                    $anioProxCita, 
                    $fechaConsulta, 
                    $idNomenc, 
                    $idEmb,
                    $idPrest )
         RETURNING id_control_prenatal";
        //echo $sql;
        $result = sql($sql) or fin_pagina();
        return $result;
    }    
    public function Actualizar() {
        if ( strlen( trim($this->anioProxCita) ) < 3 ) {
            $anioProxCita = "NULL";
        }else{
            $anioProxCita = "'".$this->anioProxCita."'";
        }

        if ( strlen( trim($this->fechaConsulta) ) < 3 ) {
            $fechaConsulta = "NULL";
        }else{
            $fechaConsulta = "'".convertirFechaADb($this->fechaConsulta)."'";
        }

        $sql = "UPDATE sip_clap.control_prenatal
                SET id_efector='$this->idEfector', var_0116='$this->var0116', var_0117='$this->var0117', var_0118='$this->var0118', 
                    var_0119=$this->var0119, var_0120=$this->var0120, var_0121=$this->var0121, var_0394=$this->var0394, var_0122=$this->var0122,
                    var_0123='$this->var0123', var_0124=$this->var0124, var_0125='$this->var0125', var_0393='$this->var0393',
                    var_0126='$this->var0126', var_0127='$this->var0127', var_0128='$this->var0128', var_0129='$this->var0129',
                    anio_prox_cita=$anioProxCita, fecha_consulta = $fechaConsulta,id_nomenclador=$this->idNomenclador, 
                    trazadora_id_emb=$this->trazadoraIdEmb, facturacion_id_prestacion=$this->facturacionIdPrestacion
                WHERE id_control_prenatal=".$this->idControlPrenatal;
        return sql($sql);   
    }

    


public function guardarComprobante($ficha, $control){
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

	$transaccion = false;
	// Datos generados
	$FechaActual = date('Y-m-d', time());
        $fecha = $control['fecha_consulta'];
        $efector = $control['id_efector'];

	$fechaPeriodo_pre = explode("-", $fecha);
	$fechaPeriodo = $fechaPeriodo_pre[0]."/".$fechaPeriodo_pre[1];

	$nomenclador = new Nomenclador();
	$beneficiario = new BeneficiarioUad();
	$nomencladorDetalle = new NomencladorDetalle();

	// SQLS Adicionales
	// Nomenclador Detalle
	$sqlNomDetalle = $nomencladorDetalle->getSQlSelectWhere("'".$fecha."' BETWEEN fecha_desde AND fecha_hasta AND modo_facturacion='4'");
	$nomencladorDetalle->construirResult( sql($sqlNomDetalle) );
	// Nomenclador
	if ($nomencladorDetalle->getIdNomencladorDetalle() > 0) {
		$sqlWhereNomenclador = "codigo||(' '||diagnostico) = '".$control['cod_nomenclador']."' AND id_nomenclador_detalle = ".$nomencladorDetalle->getIdNomencladorDetalle()." AND tipo_nomenclador = 'BASICO' ";
		$sqlNomenclador = $nomenclador->getSQlSelectWhere($sqlWhereNomenclador);
    $result = sql($sqlNomenclador) or die($sqlNomenclador);
		$nomenclador->construirResult( $result );

		// Periodo
		$sqlIdPeriodo = "Select * from facturacion.periodo where periodo = '".$fechaPeriodo."'";
		$idPeriodo = sql($sqlIdPeriodo)->fields["id_periodo"];
	}

	// Comprobación de nomencladores
	if ( $nomenclador->getIdNomenclador() > 0 ) {
		// Comprobante
		$Comprobante = new Comprobante();
		$Comprobante->setCuie( $efector );
		$Comprobante->setIdFactura("NULL");
		$Comprobante->setNombreMedico("");
		$Comprobante->setFechaComprobante($fecha);
		$Comprobante->setClavebeneficiario($ficha->getClaveBeneficiario());
		$Comprobante->setFechaCarga($FechaActual);
		$Comprobante->setMarca(0);
		$Comprobante->setIdperiodo($idPeriodo);
		$Comprobante->setActivo(1);
		$Comprobante->setGrupoEtario( $beneficiario->getGrupoEtareo($FechaActual) );
		$Comprobante->setIdNomencladorDetalle( $nomencladorDetalle->getIdNomencladorDetalle() );
		$Comprobante->setTipoNomenclador( $nomenclador->getTipoNomenclador() );
		$Comprobante->setUsuario($_ses_user['id']);
		$idComprobante = $Comprobante->guardarComprobante();
		$Comprobante->setIdComprobante($idComprobante);		

		// Prestacion
		$Prestacion = new Prestacion();
		$Prestacion->setIdComprobante($idComprobante);
		$Prestacion->setIdNomenclador( $nomenclador->getIdNomenclador() );
		$Prestacion->setCantidad(1);
		// $Prestacion->setPrecioPrestacion( $nomenclador->getPrecioSegunGrupo( $beneficiario->getGrupoEtareo($FechaActual) ) );

		$idPrestacion = $Prestacion->guardarPrestacion();
		$Prestacion->setIdPrestacion($idPrestacion);
                
                // cargar trazadora
             /*   $q = "select nextval('trazadoras.embarazadas_id_emb_seq') as id_planilla";
                $id_planilla = sql($q) or fin_pagina();
                $id_planilla = $id_planilla->fields['id_planilla'];
                
                $sqlTrazadora = 'INSERT INTO trazadoras.embarazadas (id_emb,cuie,clave,tipo_doc,num_doc,apellido,nombre,fecha_control,
                    sem_gestacion,fum,fpp,fecha_carga,usuario,peso_embarazada,tension_arterial_minima,tension_arterial_maxima)
                    values ('$id_planilla','$efector','.$ficha->getClaveBeneficiario().','.$beneficiario->getTipoDocumento().','.$beneficiario->getNumeroDoc().','$apellido',
                        '$nombre','$fecha_control','$sem_gestacion','$fum',
                        '$fpp','$fpcp','$observaciones','$fecha_carga','$usuario','$peso','$talla','$tamin','$tamax')';*/
                
                return( array('trazadora'=>$idComprobante, 'prestacion' => $idPrestacion) );
		//$transaccion = true;
	} else {
            return false;
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


}
