<?php

/**
 * 
 *
 * @version 1.107
 * @package entity
 */
class BeneficiarioUad {

    private $idBeneficiarios;
    private $estadoEnvio;
    private $claveBeneficiario;
    private $tipoTransaccion;
    private $apellidoBenef;
    private $nombreBenef;
    private $claseDocumentoBenef;
    private $tipoDocumento;
    private $numeroDoc;
    private $idCategoria;
    private $sexo;
    private $fechaNacimientoBenef;
    private $provinciaNac;
    private $localidadNac;
    private $paisNac;
    private $indigena;
    private $idTribu;
    private $idLengua;
    private $alfabeta;
    private $estudios;
    private $anioMayorNivel;
    private $tipoDocMadre;
    private $nroDocMadre;
    private $apellidoMadre;
    private $nombreMadre;
    private $alfabetaMadre;
    private $estudiosMadre;
    private $anioMayorNivelMadre;
    private $tipoDocPadre;
    private $nroDocPadre;
    private $apellidoPadre;
    private $nombrePadre;
    private $alfabetaPadre;
    private $estudiosPadre;
    private $anioMayorNivelPadre;
    private $tipoDocTutor;
    private $nroDocTutor;
    private $apellidoTutor;
    private $nombreTutor;
    private $alfabetaTutor;
    private $estudiosTutor;
    private $anioMayorNivelTutor;
    private $fechaDiagnosticoEmbarazo;
    private $semanasEmbarazo;
    private $fechaProbableParto;
    private $fechaEfectivaParto;
    private $cuieEa;
    private $cuieAh;
    private $menorConviveConAdulto;
    private $calle;
    private $numeroCalle;
    private $piso;
    private $dpto;
    private $manzana;
    private $entreCalle1;
    private $entreCalle2;
    private $telefono;
    private $departamento;
    private $localidad;
    private $municipio;
    private $barrio;
    private $codPos;
    private $observaciones;
    private $fechaInscripcion;
    private $fechaCarga;
    private $usuarioCarga;
    private $activo;
    private $scoreRiesgo;
    private $mail;
    private $celular;
    private $otrotel;
    private $estadoest;
    private $fum;
    private $obsgenerales;
    private $estadoestMadre;
    private $tipoFicha;
    private $responsable;
    private $discv;
    private $disca;
    private $discmo;
    private $discme;
    private $otradisc;
    private $estadoestPadre;
    private $estadoestTutor;
    private $menorEmbarazada;
    private $apellidoBenefOtro;
    private $nombreBenefOtro;
    private $fechaVerificado;
    private $usuarioVerificado;
    private $apellidoagente;
    private $nombreagente;
    private $centroInscriptor;
    private $dniAgente;
    private $edades;
    private $fallecido;
    //SMI
    private $estadoEnPadron;
    private $embarazado;

    public function construirResult($result) {
        $this->idBeneficiarios = $result->fields['id_beneficiarios'];
        $this->estadoEnvio = $result->fields['estado_envio'];
        $this->claveBeneficiario = $result->fields['clave_beneficiario'];
        $this->tipoTransaccion = $result->fields['tipo_transaccion'];
        $this->apellidoBenef = $result->fields['apellido_benef'];
        $this->nombreBenef = $result->fields['nombre_benef'];
        $this->claseDocumentoBenef = $result->fields['clase_documento_benef'];
        $this->tipoDocumento = $result->fields['tipo_documento'];
        $this->numeroDoc = $result->fields['numero_doc'];
        $this->idCategoria = $result->fields['id_categoria'];
        $this->sexo = $result->fields['sexo'];
        $this->fechaNacimientoBenef = $result->fields['fecha_nacimiento_benef'];
        $this->provinciaNac = $result->fields['provincia_nac'];
        $this->localidadNac = $result->fields['localidad_nac'];
        $this->paisNac = $result->fields['pais_nac'];
        $this->indigena = $result->fields['indigena'];
        $this->idTribu = $result->fields['id_tribu'];
        $this->idLengua = $result->fields['id_lengua'];
        $this->alfabeta = $result->fields['alfabeta'];
        $this->estudios = $result->fields['estudios'];
        $this->anioMayorNivel = $result->fields['anio_mayor_nivel'];
        $this->tipoDocMadre = $result->fields['tipo_doc_madre'];
        $this->nroDocMadre = $result->fields['nro_doc_madre'];
        $this->apellidoMadre = $result->fields['apellido_madre'];
        $this->nombreMadre = $result->fields['nombre_madre'];
        $this->alfabetaMadre = $result->fields['alfabeta_madre'];
        $this->estudiosMadre = $result->fields['estudios_madre'];
        $this->anioMayorNivelMadre = $result->fields['anio_mayor_nivel_madre'];
        $this->tipoDocPadre = $result->fields['tipo_doc_padre'];
        $this->nroDocPadre = $result->fields['nro_doc_padre'];
        $this->apellidoPadre = $result->fields['apellido_padre'];
        $this->nombrePadre = $result->fields['nombre_padre'];
        $this->alfabetaPadre = $result->fields['alfabeta_padre'];
        $this->estudiosPadre = $result->fields['estudios_padre'];
        $this->anioMayorNivelPadre = $result->fields['anio_mayor_nivel_padre'];
        $this->tipoDocTutor = $result->fields['tipo_doc_tutor'];
        $this->nroDocTutor = $result->fields['nro_doc_tutor'];
        $this->apellidoTutor = $result->fields['apellido_tutor'];
        $this->nombreTutor = $result->fields['nombre_tutor'];
        $this->alfabetaTutor = $result->fields['alfabeta_tutor'];
        $this->estudiosTutor = $result->fields['estudios_tutor'];
        $this->anioMayorNivelTutor = $result->fields['anio_mayor_nivel_tutor'];
        $this->fechaDiagnosticoEmbarazo = $result->fields['fecha_diagnostico_embarazo'];
        $this->semanasEmbarazo = $result->fields['semanas_embarazo'];
        $this->fechaProbableParto = $result->fields['fecha_probable_parto'];
        $this->fechaEfectivaParto = $result->fields['fecha_efectiva_parto'];
        $this->cuieEa = $result->fields['cuie_ea'];
        $this->cuieAh = $result->fields['cuie_ah'];
        $this->menorConviveConAdulto = $result->fields['menor_convive_con_adulto'];
        $this->calle = $result->fields['calle'];
        $this->numeroCalle = $result->fields['numero_calle'];
        $this->piso = $result->fields['piso'];
        $this->dpto = $result->fields['dpto'];
        $this->manzana = $result->fields['manzana'];
        $this->entreCalle1 = $result->fields['entre_calle_1'];
        $this->entreCalle2 = $result->fields['entre_calle_2'];
        $this->telefono = $result->fields['telefono'];
        $this->departamento = $result->fields['departamento'];
        $this->localidad = $result->fields['localidad'];
        $this->municipio = $result->fields['municipio'];
        $this->barrio = $result->fields['barrio'];
        $this->codPos = $result->fields['cod_pos'];
        $this->observaciones = $result->fields['observaciones'];
        $this->fechaInscripcion = $result->fields['fecha_inscripcion'];
        $this->fechaCarga = $result->fields['fecha_carga'];
        $this->usuarioCarga = $result->fields['usuario_carga'];
        $this->activo = $result->fields['activo'];
        $this->scoreRiesgo = $result->fields['score_riesgo'];
        $this->mail = $result->fields['mail'];
        $this->celular = $result->fields['celular'];
        $this->otrotel = $result->fields['otrotel'];
        $this->estadoest = $result->fields['estadoest'];
        $this->fum = $result->fields['fum'];
        $this->obsgenerales = $result->fields['obsgenerales'];
        $this->estadoestMadre = $result->fields['estadoest_madre'];
        $this->tipoFicha = $result->fields['tipo_ficha'];
        $this->responsable = $result->fields['responsable'];
        $this->discv = $result->fields['discv'];
        $this->disca = $result->fields['disca'];
        $this->discmo = $result->fields['discmo'];
        $this->discme = $result->fields['discme'];
        $this->otradisc = $result->fields['otradisc'];
        $this->estadoestPadre = $result->fields['estadoest_padre'];
        $this->estadoestTutor = $result->fields['estadoest_tutor'];
        $this->menorEmbarazada = $result->fields['menor_embarazada'];
        $this->apellidoBenefOtro = $result->fields['apellido_benef_otro'];
        $this->nombreBenefOtro = $result->fields['nombre_benef_otro'];
        $this->fechaVerificado = $result->fields['fecha_verificado'];
        $this->usuarioVerificado = $result->fields['usuario_verificado'];
        $this->apellidoagente = $result->fields['apellidoagente'];
        $this->nombreagente = $result->fields['nombreagente'];
        $this->centroInscriptor = $result->fields['centro_inscriptor'];
        $this->dniAgente = $result->fields['dni_agente'];
        $this->edades = $result->fields['edades'];
        $this->fallecido = $result->fields['fallecido'];
    }

    public function getApellidoFormal() {
        $nombreFormal = $this->apellidoBenef . " " . $this->apellidoBenefOtro;
        return($nombreFormal);
    }

    public function getNombreFormal() {
        $apellidoFormal = $this->nombreBenef . " " . $this->nombreBenefOtro;
        return($apellidoFormal);
    }

    public function getNombreCompletoFormal() {
        $nombreCompletoFormal = $this->getApellidoFormal() . ", " . $this->getNombreFormal();
        return($nombreCompletoFormal);
    }

    /**
     * set value for id_beneficiarios 
     *
     * type:serial,size:10,default:nextval('uad.beneficiarios_id_beneficiarios_seq'::regclass),primary,unique,autoincrement
     *
     * @param mixed $idBeneficiarios
     */
    public function setIdBeneficiarios($idBeneficiarios) {
        $this->idBeneficiarios = $idBeneficiarios;
    }

    /**
     * get value for id_beneficiarios 
     *
     * type:serial,size:10,default:nextval('uad.beneficiarios_id_beneficiarios_seq'::regclass),primary,unique,autoincrement
     *
     * @return mixed
     */
    public function getIdBeneficiarios() {
        return $this->idBeneficiarios;
    }

    /**
     * set value for estado_envio 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $estadoEnvio
     */
    public function setEstadoEnvio($estadoEnvio) {
        $this->estadoEnvio = $estadoEnvio;
    }

    /**
     * get value for estado_envio 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getEstadoEnvio() {
        return $this->estadoEnvio;
    }

    /**
     * set value for clave_beneficiario 
     *
     * type:varchar,size:16,default:null,nullable
     *
     * @param mixed $claveBeneficiario
     */
    public function setClaveBeneficiario($claveBeneficiario) {
        $this->claveBeneficiario = $claveBeneficiario;
    }

    /**
     * get value for clave_beneficiario 
     *
     * type:varchar,size:16,default:null,nullable
     *
     * @return mixed
     */
    public function getClaveBeneficiario() {
        return $this->claveBeneficiario;
    }

    /**
     * set value for tipo_transaccion 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $tipoTransaccion
     */
    public function setTipoTransaccion($tipoTransaccion) {
        $this->tipoTransaccion = $tipoTransaccion;
    }

    /**
     * get value for tipo_transaccion 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getTipoTransaccion() {
        return $this->tipoTransaccion;
    }

    /**
     * set value for apellido_benef 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $apellidoBenef
     */
    public function setApellidoBenef($apellidoBenef) {
        $this->apellidoBenef = $apellidoBenef;
    }

    /**
     * get value for apellido_benef 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getApellidoBenef() {
        return $this->apellidoBenef;
    }

    /**
     * set value for nombre_benef 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $nombreBenef
     */
    public function setNombreBenef($nombreBenef) {
        $this->nombreBenef = $nombreBenef;
    }

    /**
     * get value for nombre_benef 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getNombreBenef() {
        return $this->nombreBenef;
    }

    /**
     * set value for clase_documento_benef 
     *
     * type:varchar,size:1,default:null,index,nullable
     *
     * @param mixed $claseDocumentoBenef
     */
    public function setClaseDocumentoBenef($claseDocumentoBenef) {
        $this->claseDocumentoBenef = $claseDocumentoBenef;
    }

    /**
     * get value for clase_documento_benef 
     *
     * type:varchar,size:1,default:null,index,nullable
     *
     * @return mixed
     */
    public function getClaseDocumentoBenef() {
        return $this->claseDocumentoBenef;
    }

    /**
     * set value for tipo_documento 
     *
     * type:varchar,size:2147483647,default:null,index,nullable
     *
     * @param mixed $tipoDocumento
     */
    public function setTipoDocumento($tipoDocumento) {
        $this->tipoDocumento = $tipoDocumento;
    }

    /**
     * get value for tipo_documento 
     *
     * type:varchar,size:2147483647,default:null,index,nullable
     *
     * @return mixed
     */
    public function getTipoDocumento() {
        return $this->tipoDocumento;
    }

    /**
     * set value for numero_doc 
     *
     * type:varchar,size:12,default:null,index,nullable
     *
     * @param mixed $numeroDoc
     */
    public function setNumeroDoc($numeroDoc) {
        $this->numeroDoc = $numeroDoc;
    }

    /**
     * get value for numero_doc 
     *
     * type:varchar,size:12,default:null,index,nullable
     *
     * @return mixed
     */
    public function getNumeroDoc() {
        return $this->numeroDoc;
    }

    /**
     * set value for id_categoria 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @param mixed $idCategoria
     */
    public function setIdCategoria($idCategoria) {
        $this->idCategoria = $idCategoria;
    }

    /**
     * get value for id_categoria 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @return mixed
     */
    public function getIdCategoria() {
        return $this->idCategoria;
    }

    /**
     * set value for sexo 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @param mixed $sexo
     */
    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    /**
     * get value for sexo 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getSexo() {
        return $this->sexo;
    }

    public function getSexoFormal() {
        $rtnValue = "I";
        switch ($this->sexo) {
            case 'F':
                $rtnValue = "mujer";
                break;
            case 'M':
                $rtnValue = "hombre";
                break;

            default:
                # code...
                break;
        }
        return($rtnValue);
    }

    /**
     * set value for fecha_nacimiento_benef 
     *
     * type:date,size:13,default:null,nullable
     *
     * @param mixed $fechaNacimientoBenef
     */
    public function setFechaNacimientoBenef($fechaNacimientoBenef) {
        $this->fechaNacimientoBenef = $fechaNacimientoBenef;
    }

    /**
     * get value for fecha_nacimiento_benef 
     *
     * type:date,size:13,default:null,nullable
     *
     * @return mixed
     */
    public function getFechaNacimientoBenef() {
        return $this->fechaNacimientoBenef;
    }

    /**
     * set value for provincia_nac 
     *
     * type:varchar,size:20,default:null,nullable
     *
     * @param mixed $provinciaNac
     */
    public function setProvinciaNac($provinciaNac) {
        $this->provinciaNac = $provinciaNac;
    }

    /**
     * get value for provincia_nac 
     *
     * type:varchar,size:20,default:null,nullable
     *
     * @return mixed
     */
    public function getProvinciaNac() {
        return $this->provinciaNac;
    }

    /**
     * set value for localidad_nac 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $localidadNac
     */
    public function setLocalidadNac($localidadNac) {
        $this->localidadNac = $localidadNac;
    }

    /**
     * get value for localidad_nac 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getLocalidadNac() {
        return $this->localidadNac;
    }

    /**
     * set value for pais_nac 
     *
     * type:varchar,size:40,default:null,nullable
     *
     * @param mixed $paisNac
     */
    public function setPaisNac($paisNac) {
        $this->paisNac = $paisNac;
    }

    /**
     * get value for pais_nac 
     *
     * type:varchar,size:40,default:null,nullable
     *
     * @return mixed
     */
    public function getPaisNac() {
        return $this->paisNac;
    }

    /**
     * set value for indigena 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @param mixed $indigena
     */
    public function setIndigena($indigena) {
        $this->indigena = $indigena;
    }

    /**
     * get value for indigena 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getIndigena() {
        return $this->indigena;
    }

    /**
     * set value for id_tribu 
     *
     * type:bpchar,size:6,default:null,nullable
     *
     * @param mixed $idTribu
     */
    public function setIdTribu($idTribu) {
        $this->idTribu = $idTribu;
    }

    /**
     * get value for id_tribu 
     *
     * type:bpchar,size:6,default:null,nullable
     *
     * @return mixed
     */
    public function getIdTribu() {
        return $this->idTribu;
    }

    /**
     * set value for id_lengua 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @param mixed $idLengua
     */
    public function setIdLengua($idLengua) {
        $this->idLengua = $idLengua;
    }

    /**
     * get value for id_lengua 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @return mixed
     */
    public function getIdLengua() {
        return $this->idLengua;
    }

    /**
     * set value for alfabeta 
     *
     * type:varchar,size:5,default:null,nullable
     *
     * @param mixed $alfabeta
     */
    public function setAlfabeta($alfabeta) {
        $this->alfabeta = $alfabeta;
    }

    /**
     * get value for alfabeta 
     *
     * type:varchar,size:5,default:null,nullable
     *
     * @return mixed
     */
    public function getAlfabeta() {
        return $this->alfabeta;
    }

    /**
     * set value for estudios 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $estudios
     */
    public function setEstudios($estudios) {
        $this->estudios = $estudios;
    }

    /**
     * get value for estudios 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getEstudios() {
        return $this->estudios;
    }

    /**
     * set value for anio_mayor_nivel 
     *
     * type:int4,size:10,default:0,nullable
     *
     * @param mixed $anioMayorNivel
     */
    public function setAnioMayorNivel($anioMayorNivel) {
        $this->anioMayorNivel = $anioMayorNivel;
    }

    /**
     * get value for anio_mayor_nivel 
     *
     * type:int4,size:10,default:0,nullable
     *
     * @return mixed
     */
    public function getAnioMayorNivel() {
        return $this->anioMayorNivel;
    }

    /**
     * set value for tipo_doc_madre 
     *
     * type:varchar,size:5,default:null,nullable
     *
     * @param mixed $tipoDocMadre
     */
    public function setTipoDocMadre($tipoDocMadre) {
        $this->tipoDocMadre = $tipoDocMadre;
    }

    /**
     * get value for tipo_doc_madre 
     *
     * type:varchar,size:5,default:null,nullable
     *
     * @return mixed
     */
    public function getTipoDocMadre() {
        return $this->tipoDocMadre;
    }

    /**
     * set value for nro_doc_madre 
     *
     * type:varchar,size:12,default:null,nullable
     *
     * @param mixed $nroDocMadre
     */
    public function setNroDocMadre($nroDocMadre) {
        $this->nroDocMadre = $nroDocMadre;
    }

    /**
     * get value for nro_doc_madre 
     *
     * type:varchar,size:12,default:null,nullable
     *
     * @return mixed
     */
    public function getNroDocMadre() {
        return $this->nroDocMadre;
    }

    /**
     * set value for apellido_madre 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $apellidoMadre
     */
    public function setApellidoMadre($apellidoMadre) {
        $this->apellidoMadre = $apellidoMadre;
    }

    /**
     * get value for apellido_madre 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getApellidoMadre() {
        return $this->apellidoMadre;
    }

    /**
     * set value for nombre_madre 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $nombreMadre
     */
    public function setNombreMadre($nombreMadre) {
        $this->nombreMadre = $nombreMadre;
    }

    /**
     * get value for nombre_madre 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getNombreMadre() {
        return $this->nombreMadre;
    }

    /**
     * set value for alfabeta_madre 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $alfabetaMadre
     */
    public function setAlfabetaMadre($alfabetaMadre) {
        $this->alfabetaMadre = $alfabetaMadre;
    }

    /**
     * get value for alfabeta_madre 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getAlfabetaMadre() {
        return $this->alfabetaMadre;
    }

    /**
     * set value for estudios_madre 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $estudiosMadre
     */
    public function setEstudiosMadre($estudiosMadre) {
        $this->estudiosMadre = $estudiosMadre;
    }

    /**
     * get value for estudios_madre 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getEstudiosMadre() {
        return $this->estudiosMadre;
    }

    /**
     * set value for anio_mayor_nivel_madre 
     *
     * type:int4,size:10,default:0,nullable
     *
     * @param mixed $anioMayorNivelMadre
     */
    public function setAnioMayorNivelMadre($anioMayorNivelMadre) {
        $this->anioMayorNivelMadre = $anioMayorNivelMadre;
    }

    /**
     * get value for anio_mayor_nivel_madre 
     *
     * type:int4,size:10,default:0,nullable
     *
     * @return mixed
     */
    public function getAnioMayorNivelMadre() {
        return $this->anioMayorNivelMadre;
    }

    /**
     * set value for tipo_doc_padre 
     *
     * type:varchar,size:5,default:null,nullable
     *
     * @param mixed $tipoDocPadre
     */
    public function setTipoDocPadre($tipoDocPadre) {
        $this->tipoDocPadre = $tipoDocPadre;
    }

    /**
     * get value for tipo_doc_padre 
     *
     * type:varchar,size:5,default:null,nullable
     *
     * @return mixed
     */
    public function getTipoDocPadre() {
        return $this->tipoDocPadre;
    }

    /**
     * set value for nro_doc_padre 
     *
     * type:varchar,size:12,default:null,nullable
     *
     * @param mixed $nroDocPadre
     */
    public function setNroDocPadre($nroDocPadre) {
        $this->nroDocPadre = $nroDocPadre;
    }

    /**
     * get value for nro_doc_padre 
     *
     * type:varchar,size:12,default:null,nullable
     *
     * @return mixed
     */
    public function getNroDocPadre() {
        return $this->nroDocPadre;
    }

    /**
     * set value for apellido_padre 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $apellidoPadre
     */
    public function setApellidoPadre($apellidoPadre) {
        $this->apellidoPadre = $apellidoPadre;
    }

    /**
     * get value for apellido_padre 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getApellidoPadre() {
        return $this->apellidoPadre;
    }

    /**
     * set value for nombre_padre 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $nombrePadre
     */
    public function setNombrePadre($nombrePadre) {
        $this->nombrePadre = $nombrePadre;
    }

    /**
     * get value for nombre_padre 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getNombrePadre() {
        return $this->nombrePadre;
    }

    /**
     * set value for alfabeta_padre 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $alfabetaPadre
     */
    public function setAlfabetaPadre($alfabetaPadre) {
        $this->alfabetaPadre = $alfabetaPadre;
    }

    /**
     * get value for alfabeta_padre 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getAlfabetaPadre() {
        return $this->alfabetaPadre;
    }

    /**
     * set value for estudios_padre 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $estudiosPadre
     */
    public function setEstudiosPadre($estudiosPadre) {
        $this->estudiosPadre = $estudiosPadre;
    }

    /**
     * get value for estudios_padre 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getEstudiosPadre() {
        return $this->estudiosPadre;
    }

    /**
     * set value for anio_mayor_nivel_padre 
     *
     * type:int4,size:10,default:0,nullable
     *
     * @param mixed $anioMayorNivelPadre
     */
    public function setAnioMayorNivelPadre($anioMayorNivelPadre) {
        $this->anioMayorNivelPadre = $anioMayorNivelPadre;
    }

    /**
     * get value for anio_mayor_nivel_padre 
     *
     * type:int4,size:10,default:0,nullable
     *
     * @return mixed
     */
    public function getAnioMayorNivelPadre() {
        return $this->anioMayorNivelPadre;
    }

    /**
     * set value for tipo_doc_tutor 
     *
     * type:varchar,size:5,default:null,nullable
     *
     * @param mixed $tipoDocTutor
     */
    public function setTipoDocTutor($tipoDocTutor) {
        $this->tipoDocTutor = $tipoDocTutor;
    }

    /**
     * get value for tipo_doc_tutor 
     *
     * type:varchar,size:5,default:null,nullable
     *
     * @return mixed
     */
    public function getTipoDocTutor() {
        return $this->tipoDocTutor;
    }

    /**
     * set value for nro_doc_tutor 
     *
     * type:varchar,size:12,default:null,nullable
     *
     * @param mixed $nroDocTutor
     */
    public function setNroDocTutor($nroDocTutor) {
        $this->nroDocTutor = $nroDocTutor;
    }

    /**
     * get value for nro_doc_tutor 
     *
     * type:varchar,size:12,default:null,nullable
     *
     * @return mixed
     */
    public function getNroDocTutor() {
        return $this->nroDocTutor;
    }

    /**
     * set value for apellido_tutor 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $apellidoTutor
     */
    public function setApellidoTutor($apellidoTutor) {
        $this->apellidoTutor = $apellidoTutor;
    }

    /**
     * get value for apellido_tutor 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getApellidoTutor() {
        return $this->apellidoTutor;
    }

    /**
     * set value for nombre_tutor 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $nombreTutor
     */
    public function setNombreTutor($nombreTutor) {
        $this->nombreTutor = $nombreTutor;
    }

    /**
     * get value for nombre_tutor 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getNombreTutor() {
        return $this->nombreTutor;
    }

    /**
     * set value for alfabeta_tutor 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $alfabetaTutor
     */
    public function setAlfabetaTutor($alfabetaTutor) {
        $this->alfabetaTutor = $alfabetaTutor;
    }

    /**
     * get value for alfabeta_tutor 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getAlfabetaTutor() {
        return $this->alfabetaTutor;
    }

    /**
     * set value for estudios_tutor 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $estudiosTutor
     */
    public function setEstudiosTutor($estudiosTutor) {
        $this->estudiosTutor = $estudiosTutor;
    }

    /**
     * get value for estudios_tutor 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getEstudiosTutor() {
        return $this->estudiosTutor;
    }

    /**
     * set value for anio_mayor_nivel_tutor 
     *
     * type:int4,size:10,default:0,nullable
     *
     * @param mixed $anioMayorNivelTutor
     */
    public function setAnioMayorNivelTutor($anioMayorNivelTutor) {
        $this->anioMayorNivelTutor = $anioMayorNivelTutor;
    }

    /**
     * get value for anio_mayor_nivel_tutor 
     *
     * type:int4,size:10,default:0,nullable
     *
     * @return mixed
     */
    public function getAnioMayorNivelTutor() {
        return $this->anioMayorNivelTutor;
    }

    /**
     * set value for fecha_diagnostico_embarazo 
     *
     * type:date,size:13,default:null,nullable
     *
     * @param mixed $fechaDiagnosticoEmbarazo
     */
    public function setFechaDiagnosticoEmbarazo($fechaDiagnosticoEmbarazo) {
        $this->fechaDiagnosticoEmbarazo = $fechaDiagnosticoEmbarazo;
    }

    /**
     * get value for fecha_diagnostico_embarazo 
     *
     * type:date,size:13,default:null,nullable
     *
     * @return mixed
     */
    public function getFechaDiagnosticoEmbarazo() {
        return $this->fechaDiagnosticoEmbarazo;
    }

    /**
     * set value for semanas_embarazo 
     *
     * type:int4,size:10,default:0,nullable
     *
     * @param mixed $semanasEmbarazo
     */
    public function setSemanasEmbarazo($semanasEmbarazo) {
        $this->semanasEmbarazo = $semanasEmbarazo;
    }

    /**
     * get value for semanas_embarazo 
     *
     * type:int4,size:10,default:0,nullable
     *
     * @return mixed
     */
    public function getSemanasEmbarazo() {
        return $this->semanasEmbarazo;
    }

    /**
     * set value for fecha_probable_parto 
     *
     * type:date,size:13,default:null,nullable
     *
     * @param mixed $fechaProbableParto
     */
    public function setFechaProbableParto($fechaProbableParto) {
        $this->fechaProbableParto = $fechaProbableParto;
    }

    /**
     * get value for fecha_probable_parto 
     *
     * type:date,size:13,default:null,nullable
     *
     * @return mixed
     */
    public function getFechaProbableParto() {
        return $this->fechaProbableParto;
    }

    /**
     * set value for fecha_efectiva_parto 
     *
     * type:date,size:13,default:null,nullable
     *
     * @param mixed $fechaEfectivaParto
     */
    public function setFechaEfectivaParto($fechaEfectivaParto) {
        $this->fechaEfectivaParto = $fechaEfectivaParto;
    }

    /**
     * get value for fecha_efectiva_parto 
     *
     * type:date,size:13,default:null,nullable
     *
     * @return mixed
     */
    public function getFechaEfectivaParto() {
        return $this->fechaEfectivaParto;
    }

    /**
     * set value for cuie_ea 
     *
     * type:varchar,size:6,default:null,nullable
     *
     * @param mixed $cuieEa
     */
    public function setCuieEa($cuieEa) {
        $this->cuieEa = $cuieEa;
    }

    /**
     * get value for cuie_ea 
     *
     * type:varchar,size:6,default:null,nullable
     *
     * @return mixed
     */
    public function getCuieEa() {
        return $this->cuieEa;
    }

    /**
     * set value for cuie_ah 
     *
     * type:varchar,size:6,default:null,nullable
     *
     * @param mixed $cuieAh
     */
    public function setCuieAh($cuieAh) {
        $this->cuieAh = $cuieAh;
    }

    /**
     * get value for cuie_ah 
     *
     * type:varchar,size:6,default:null,nullable
     *
     * @return mixed
     */
    public function getCuieAh() {
        return $this->cuieAh;
    }

    /**
     * set value for menor_convive_con_adulto 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @param mixed $menorConviveConAdulto
     */
    public function setMenorConviveConAdulto($menorConviveConAdulto) {
        $this->menorConviveConAdulto = $menorConviveConAdulto;
    }

    /**
     * get value for menor_convive_con_adulto 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getMenorConviveConAdulto() {
        return $this->menorConviveConAdulto;
    }

    /**
     * set value for calle 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $calle
     */
    public function setCalle($calle) {
        $this->calle = $calle;
    }

    /**
     * get value for calle 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getCalle() {
        return $this->calle;
    }

    /**
     * set value for numero_calle 
     *
     * type:varchar,size:10,default:null,nullable
     *
     * @param mixed $numeroCalle
     */
    public function setNumeroCalle($numeroCalle) {
        $this->numeroCalle = $numeroCalle;
    }

    /**
     * get value for numero_calle 
     *
     * type:varchar,size:10,default:null,nullable
     *
     * @return mixed
     */
    public function getNumeroCalle() {
        return $this->numeroCalle;
    }

    /**
     * set value for piso 
     *
     * type:varchar,size:4,default:null,nullable
     *
     * @param mixed $piso
     */
    public function setPiso($piso) {
        $this->piso = $piso;
    }

    /**
     * get value for piso 
     *
     * type:varchar,size:4,default:null,nullable
     *
     * @return mixed
     */
    public function getPiso() {
        return $this->piso;
    }

    /**
     * set value for dpto 
     *
     * type:varchar,size:3,default:null,nullable
     *
     * @param mixed $dpto
     */
    public function setDpto($dpto) {
        $this->dpto = $dpto;
    }

    /**
     * get value for dpto 
     *
     * type:varchar,size:3,default:null,nullable
     *
     * @return mixed
     */
    public function getDpto() {
        return $this->dpto;
    }

    /**
     * set value for manzana 
     *
     * type:varchar,size:30,default:null,nullable
     *
     * @param mixed $manzana
     */
    public function setManzana($manzana) {
        $this->manzana = $manzana;
    }

    /**
     * get value for manzana 
     *
     * type:varchar,size:30,default:null,nullable
     *
     * @return mixed
     */
    public function getManzana() {
        return $this->manzana;
    }

    /**
     * set value for entre_calle_1 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $entreCalle1
     */
    public function setEntreCalle1($entreCalle1) {
        $this->entreCalle1 = $entreCalle1;
    }

    /**
     * get value for entre_calle_1 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getEntreCalle1() {
        return $this->entreCalle1;
    }

    /**
     * set value for entre_calle_2 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $entreCalle2
     */
    public function setEntreCalle2($entreCalle2) {
        $this->entreCalle2 = $entreCalle2;
    }

    /**
     * get value for entre_calle_2 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getEntreCalle2() {
        return $this->entreCalle2;
    }

    /**
     * set value for telefono 
     *
     * type:varchar,size:40,default:null,nullable
     *
     * @param mixed $telefono
     */
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    /**
     * get value for telefono 
     *
     * type:varchar,size:40,default:null,nullable
     *
     * @return mixed
     */
    public function getTelefono() {
        return $this->telefono;
    }

    /**
     * set value for departamento 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $departamento
     */
    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    /**
     * get value for departamento 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getDepartamento() {
        return $this->departamento;
    }

    /**
     * set value for localidad 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $localidad
     */
    public function setLocalidad($localidad) {
        $this->localidad = $localidad;
    }

    /**
     * get value for localidad 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getLocalidad() {
        return $this->localidad;
    }

    /**
     * set value for municipio 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $municipio
     */
    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

    /**
     * get value for municipio 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getMunicipio() {
        return $this->municipio;
    }

    /**
     * set value for barrio 
     *
     * type:varchar,size:2147483647,default:0,nullable
     *
     * @param mixed $barrio
     */
    public function setBarrio($barrio) {
        $this->barrio = $barrio;
    }

    /**
     * get value for barrio 
     *
     * type:varchar,size:2147483647,default:0,nullable
     *
     * @return mixed
     */
    public function getBarrio() {
        return $this->barrio;
    }

    /**
     * set value for cod_pos 
     *
     * type:varchar,size:11,default:null,nullable
     *
     * @param mixed $codPos
     */
    public function setCodPos($codPos) {
        $this->codPos = $codPos;
    }

    /**
     * get value for cod_pos 
     *
     * type:varchar,size:11,default:null,nullable
     *
     * @return mixed
     */
    public function getCodPos() {
        return $this->codPos;
    }

    /**
     * set value for observaciones 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $observaciones
     */
    public function setObservaciones($observaciones) {
        $this->observaciones = $observaciones;
    }

    /**
     * get value for observaciones 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getObservaciones() {
        return $this->observaciones;
    }

    /**
     * set value for fecha_inscripcion 
     *
     * type:timestamp,size:22,default:null,nullable
     *
     * @param mixed $fechaInscripcion
     */
    public function setFechaInscripcion($fechaInscripcion) {
        $this->fechaInscripcion = $fechaInscripcion;
    }

    /**
     * get value for fecha_inscripcion 
     *
     * type:timestamp,size:22,default:null,nullable
     *
     * @return mixed
     */
    public function getFechaInscripcion() {
        return $this->fechaInscripcion;
    }

    /**
     * set value for fecha_carga 
     *
     * type:timestamp,size:22,default:null,nullable
     *
     * @param mixed $fechaCarga
     */
    public function setFechaCarga($fechaCarga) {
        $this->fechaCarga = $fechaCarga;
    }

    /**
     * get value for fecha_carga 
     *
     * type:timestamp,size:22,default:null,nullable
     *
     * @return mixed
     */
    public function getFechaCarga() {
        return $this->fechaCarga;
    }

    /**
     * set value for usuario_carga 
     *
     * type:varchar,size:10,default:null,nullable
     *
     * @param mixed $usuarioCarga
     */
    public function setUsuarioCarga($usuarioCarga) {
        $this->usuarioCarga = $usuarioCarga;
    }

    /**
     * get value for usuario_carga 
     *
     * type:varchar,size:10,default:null,nullable
     *
     * @return mixed
     */
    public function getUsuarioCarga() {
        return $this->usuarioCarga;
    }

    /**
     * set value for activo 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @param mixed $activo
     */
    public function setActivo($activo) {
        $this->activo = $activo;
    }

    /**
     * get value for activo 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getActivo() {
        return $this->activo;
    }

    /**
     * set value for score_riesgo 
     *
     * type:varchar,size:2147483647,default:0,nullable
     *
     * @param mixed $scoreRiesgo
     */
    public function setScoreRiesgo($scoreRiesgo) {
        $this->scoreRiesgo = $scoreRiesgo;
    }

    /**
     * get value for score_riesgo 
     *
     * type:varchar,size:2147483647,default:0,nullable
     *
     * @return mixed
     */
    public function getScoreRiesgo() {
        return $this->scoreRiesgo;
    }

    /**
     * set value for mail 
     *
     * type:varchar,size:35,default:null,nullable
     *
     * @param mixed $mail
     */
    public function setMail($mail) {
        $this->mail = $mail;
    }

    /**
     * get value for mail 
     *
     * type:varchar,size:35,default:null,nullable
     *
     * @return mixed
     */
    public function getMail() {
        return $this->mail;
    }

    /**
     * set value for celular 
     *
     * type:varchar,size:40,default:null,nullable
     *
     * @param mixed $celular
     */
    public function setCelular($celular) {
        $this->celular = $celular;
    }

    /**
     * get value for celular 
     *
     * type:varchar,size:40,default:null,nullable
     *
     * @return mixed
     */
    public function getCelular() {
        return $this->celular;
    }

    /**
     * set value for otrotel 
     *
     * type:varchar,size:40,default:null,nullable
     *
     * @param mixed $otrotel
     */
    public function setOtrotel($otrotel) {
        $this->otrotel = $otrotel;
    }

    /**
     * get value for otrotel 
     *
     * type:varchar,size:40,default:null,nullable
     *
     * @return mixed
     */
    public function getOtrotel() {
        return $this->otrotel;
    }

    /**
     * set value for estadoest Estado de estudios del beneficiario (C = completo; I = Incompleto)
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @param mixed $estadoest
     */
    public function setEstadoest($estadoest) {
        $this->estadoest = $estadoest;
    }

    /**
     * get value for estadoest Estado de estudios del beneficiario (C = completo; I = Incompleto)
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getEstadoest() {
        return $this->estadoest;
    }

    /**
     * set value for fum Fecha Ultima Menstruacion (Para embarazadas)
     *
     * type:date,size:13,default:null,nullable
     *
     * @param mixed $fum
     */
    public function setFum($fum) {
        $this->fum = $fum;
    }

    /**
     * get value for fum Fecha Ultima Menstruacion (Para embarazadas)
     *
     * type:date,size:13,default:null,nullable
     *
     * @return mixed
     */
    public function getFum() {
        return $this->fum;
    }

    /**
     * set value for obsgenerales 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $obsgenerales
     */
    public function setObsgenerales($obsgenerales) {
        $this->obsgenerales = $obsgenerales;
    }

    /**
     * get value for obsgenerales 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getObsgenerales() {
        return $this->obsgenerales;
    }

    /**
     * set value for estadoest_madre 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @param mixed $estadoestMadre
     */
    public function setEstadoestMadre($estadoestMadre) {
        $this->estadoestMadre = $estadoestMadre;
    }

    /**
     * get value for estadoest_madre 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getEstadoestMadre() {
        return $this->estadoestMadre;
    }

    /**
     * set value for tipo_ficha Se refiere a quien da de Alta o Modifica el Registro:
     * 1 = R+R (en el Alta)
     * 2 = PN (en el Alta)
     * 3 = PN ó R+R (en Modificacion)
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @param mixed $tipoFicha
     */
    public function setTipoFicha($tipoFicha) {
        $this->tipoFicha = $tipoFicha;
    }

    /**
     * get value for tipo_ficha Se refiere a quien da de Alta o Modifica el Registro:
     * 1 = R+R (en el Alta)
     * 2 = PN (en el Alta)
     * 3 = PN ó R+R (en Modificacion)
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getTipoFicha() {
        return $this->tipoFicha;
    }

    /**
     * set value for responsable 
     *
     * type:varchar,size:15,default:null,nullable
     *
     * @param mixed $responsable
     */
    public function setResponsable($responsable) {
        $this->responsable = $responsable;
    }

    /**
     * get value for responsable 
     *
     * type:varchar,size:15,default:null,nullable
     *
     * @return mixed
     */
    public function getResponsable() {
        return $this->responsable;
    }

    /**
     * set value for discv 
     *
     * type:varchar,size:15,default:null,nullable
     *
     * @param mixed $discv
     */
    public function setDiscv($discv) {
        $this->discv = $discv;
    }

    /**
     * get value for discv 
     *
     * type:varchar,size:15,default:null,nullable
     *
     * @return mixed
     */
    public function getDiscv() {
        return $this->discv;
    }

    /**
     * set value for disca 
     *
     * type:varchar,size:15,default:null,nullable
     *
     * @param mixed $disca
     */
    public function setDisca($disca) {
        $this->disca = $disca;
    }

    /**
     * get value for disca 
     *
     * type:varchar,size:15,default:null,nullable
     *
     * @return mixed
     */
    public function getDisca() {
        return $this->disca;
    }

    /**
     * set value for discmo 
     *
     * type:varchar,size:15,default:null,nullable
     *
     * @param mixed $discmo
     */
    public function setDiscmo($discmo) {
        $this->discmo = $discmo;
    }

    /**
     * get value for discmo 
     *
     * type:varchar,size:15,default:null,nullable
     *
     * @return mixed
     */
    public function getDiscmo() {
        return $this->discmo;
    }

    /**
     * set value for discme 
     *
     * type:varchar,size:15,default:null,nullable
     *
     * @param mixed $discme
     */
    public function setDiscme($discme) {
        $this->discme = $discme;
    }

    /**
     * get value for discme 
     *
     * type:varchar,size:15,default:null,nullable
     *
     * @return mixed
     */
    public function getDiscme() {
        return $this->discme;
    }

    /**
     * set value for otradisc 
     *
     * type:varchar,size:20,default:null,nullable
     *
     * @param mixed $otradisc
     */
    public function setOtradisc($otradisc) {
        $this->otradisc = $otradisc;
    }

    /**
     * get value for otradisc 
     *
     * type:varchar,size:20,default:null,nullable
     *
     * @return mixed
     */
    public function getOtradisc() {
        return $this->otradisc;
    }

    /**
     * set value for estadoest_padre 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @param mixed $estadoestPadre
     */
    public function setEstadoestPadre($estadoestPadre) {
        $this->estadoestPadre = $estadoestPadre;
    }

    /**
     * get value for estadoest_padre 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getEstadoestPadre() {
        return $this->estadoestPadre;
    }

    /**
     * set value for estadoest_tutor 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @param mixed $estadoestTutor
     */
    public function setEstadoestTutor($estadoestTutor) {
        $this->estadoestTutor = $estadoestTutor;
    }

    /**
     * get value for estadoest_tutor 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getEstadoestTutor() {
        return $this->estadoestTutor;
    }

    /**
     * set value for menor_embarazada 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @param mixed $menorEmbarazada
     */
    public function setMenorEmbarazada($menorEmbarazada) {
        $this->menorEmbarazada = $menorEmbarazada;
    }

    /**
     * get value for menor_embarazada 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getMenorEmbarazada() {
        return $this->menorEmbarazada;
    }

    /**
     * set value for apellido_benef_otro 
     *
     * type:varchar,size:30,default:null,nullable
     *
     * @param mixed $apellidoBenefOtro
     */
    public function setApellidoBenefOtro($apellidoBenefOtro) {
        $this->apellidoBenefOtro = $apellidoBenefOtro;
    }

    /**
     * get value for apellido_benef_otro 
     *
     * type:varchar,size:30,default:null,nullable
     *
     * @return mixed
     */
    public function getApellidoBenefOtro() {
        return $this->apellidoBenefOtro;
    }

    /**
     * set value for nombre_benef_otro 
     *
     * type:varchar,size:30,default:null,nullable
     *
     * @param mixed $nombreBenefOtro
     */
    public function setNombreBenefOtro($nombreBenefOtro) {
        $this->nombreBenefOtro = $nombreBenefOtro;
    }

    /**
     * get value for nombre_benef_otro 
     *
     * type:varchar,size:30,default:null,nullable
     *
     * @return mixed
     */
    public function getNombreBenefOtro() {
        return $this->nombreBenefOtro;
    }

    /**
     * set value for fecha_verificado 
     *
     * type:timestamp,size:22,default:null,nullable
     *
     * @param mixed $fechaVerificado
     */
    public function setFechaVerificado($fechaVerificado) {
        $this->fechaVerificado = $fechaVerificado;
    }

    /**
     * get value for fecha_verificado 
     *
     * type:timestamp,size:22,default:null,nullable
     *
     * @return mixed
     */
    public function getFechaVerificado() {
        return $this->fechaVerificado;
    }

    /**
     * set value for usuario_verificado 
     *
     * type:varchar,size:10,default:null,nullable
     *
     * @param mixed $usuarioVerificado
     */
    public function setUsuarioVerificado($usuarioVerificado) {
        $this->usuarioVerificado = $usuarioVerificado;
    }

    /**
     * get value for usuario_verificado 
     *
     * type:varchar,size:10,default:null,nullable
     *
     * @return mixed
     */
    public function getUsuarioVerificado() {
        return $this->usuarioVerificado;
    }

    /**
     * set value for apellidoagente 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $apellidoagente
     */
    public function setApellidoagente($apellidoagente) {
        $this->apellidoagente = $apellidoagente;
    }

    /**
     * get value for apellidoagente 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getApellidoagente() {
        return $this->apellidoagente;
    }

    /**
     * set value for nombreagente 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @param mixed $nombreagente
     */
    public function setNombreagente($nombreagente) {
        $this->nombreagente = $nombreagente;
    }

    /**
     * get value for nombreagente 
     *
     * type:varchar,size:50,default:null,nullable
     *
     * @return mixed
     */
    public function getNombreagente() {
        return $this->nombreagente;
    }

    /**
     * set value for centro_inscriptor 
     *
     * type:bpchar,size:6,default:null,nullable
     *
     * @param mixed $centroInscriptor
     */
    public function setCentroInscriptor($centroInscriptor) {
        $this->centroInscriptor = $centroInscriptor;
    }

    /**
     * get value for centro_inscriptor 
     *
     * type:bpchar,size:6,default:null,nullable
     *
     * @return mixed
     */
    public function getCentroInscriptor() {
        return $this->centroInscriptor;
    }

    /**
     * set value for dni_agente 
     *
     * type:varchar,size:12,default:null,nullable
     *
     * @param mixed $dniAgente
     */
    public function setDniAgente($dniAgente) {
        $this->dniAgente = $dniAgente;
    }

    /**
     * get value for dni_agente 
     *
     * type:varchar,size:12,default:null,nullable
     *
     * @return mixed
     */
    public function getDniAgente() {
        return $this->dniAgente;
    }

    /**
     * set value for edades 
     *
     * type:int2,size:5,default:null,nullable
     *
     * @param mixed $edades
     */
    public function setEdades($edades) {
        $this->edades = $edades;
    }

    /**
     * get value for edades 
     *
     * type:int2,size:5,default:null,nullable
     *
     * @return mixed
     */
    public function getEdades() {
        return $this->edades;
    }

    /**
     * set value for fallecido 
     *
     * type:bpchar,size:1,default:'n'::bpchar,nullable
     *
     * @param mixed $fallecido
     */
    public function setFallecido($fallecido) {
        $this->fallecido = $fallecido;
    }

    /**
     * get value for fallecido 
     *
     * type:bpchar,size:1,default:'n'::bpchar,nullable
     *
     * @return mixed
     */
    public function getFallecido() {
        return $this->fallecido;
    }

    /* ! 
     *  \brief     Documentacion para metodo getDump.
     *  \details   Retorna todos los atributos del objeto en un array.
     *  \author    Pezzarini Pedro
     *  \date      28/11/2013
     *  \pre       --
     *  \bug       --
     *  \warning   --
     *  \copyright GNU Public License.
     */

    public function getDump() {
        $returnDump = array(
            'idBeneficiarios' => $this->idBeneficiarios,
            'estadoEnvio' => $this->estadoEnvio,
            'claveBeneficiario' => $this->claveBeneficiario,
            'tipoTransaccion' => $this->tipoTransaccion,
            'apellidoBenef' => $this->apellidoBenef,
            'nombreBenef' => $this->nombreBenef,
            'claseDocumentoBenef' => $this->claseDocumentoBenef,
            'tipoDocumento' => $this->tipoDocumento,
            'numeroDoc' => $this->numeroDoc,
            'idCategoria' => $this->idCategoria,
            'sexo' => $this->sexo,
            'fechaNacimientoBenef' => $this->fechaNacimientoBenef,
            'provinciaNac' => $this->provinciaNac,
            'localidadNac' => $this->localidadNac,
            'paisNac' => $this->paisNac,
            'indigena' => $this->indigena,
            'idTribu' => $this->idTribu,
            'idLengua' => $this->idLengua,
            'alfabeta' => $this->alfabeta,
            'estudios' => $this->estudios,
            'anioMayorNivel' => $this->anioMayorNivel,
            'tipoDocMadre' => $this->tipoDocMadre,
            'nroDocMadre' => $this->nroDocMadre,
            'apellidoMadre' => $this->apellidoMadre,
            'nombreMadre' => $this->nombreMadre,
            'alfabetaMadre' => $this->alfabetaMadre,
            'estudiosMadre' => $this->estudiosMadre,
            'anioMayorNivelMadre' => $this->anioMayorNivelMadre,
            'tipoDocPadre' => $this->tipoDocPadre,
            'nroDocPadre' => $this->nroDocPadre,
            'apellidoPadre' => $this->apellidoPadre,
            'nombrePadre' => $this->nombrePadre,
            'alfabetaPadre' => $this->alfabetaPadre,
            'estudiosPadre' => $this->estudiosPadre,
            'anioMayorNivelPadre' => $this->anioMayorNivelPadre,
            'tipoDocTutor' => $this->tipoDocTutor,
            'nroDocTutor' => $this->nroDocTutor,
            'apellidoTutor' => $this->apellidoTutor,
            'nombreTutor' => $this->nombreTutor,
            'alfabetaTutor' => $this->alfabetaTutor,
            'estudiosTutor' => $this->estudiosTutor,
            'anioMayorNivelTutor' => $this->anioMayorNivelTutor,
            'fechaDiagnosticoEmbarazo' => $this->fechaDiagnosticoEmbarazo,
            'semanasEmbarazo' => $this->semanasEmbarazo,
            'fechaProbableParto' => $this->fechaProbableParto,
            'fechaEfectivaParto' => $this->fechaEfectivaParto,
            'cuieEa' => $this->cuieEa,
            'cuieAh' => $this->cuieAh,
            'menorConviveConAdulto' => $this->menorConviveConAdulto,
            'calle' => $this->calle,
            'numeroCalle' => $this->numeroCalle,
            'piso' => $this->piso,
            'dpto' => $this->dpto,
            'manzana' => $this->manzana,
            'entreCalle1' => $this->entreCalle1,
            'entreCalle2' => $this->entreCalle2,
            'telefono' => $this->telefono,
            'departamento' => $this->departamento,
            'localidad' => $this->localidad,
            'municipio' => $this->municipio,
            'barrio' => $this->barrio,
            'codPos' => $this->codPos,
            'observaciones' => $this->observaciones,
            'fechaInscripcion' => $this->fechaInscripcion,
            'fechaCarga' => $this->fechaCarga,
            'usuarioCarga' => $this->usuarioCarga,
            'activo' => $this->activo,
            'scoreRiesgo' => $this->scoreRiesgo,
            'mail' => $this->mail,
            'celular' => $this->celular,
            'otrotel' => $this->otrotel,
            'estadoest' => $this->estadoest,
            'fum' => $this->fum,
            'obsgenerales' => $this->obsgenerales,
            'estadoestMadre' => $this->estadoestMadre,
            'tipoFicha' => $this->tipoFicha,
            'responsable' => $this->responsable,
            'discv' => $this->discv,
            'disca' => $this->disca,
            'discmo' => $this->discmo,
            'discme' => $this->discme,
            'otradisc' => $this->otradisc,
            'estadoestPadre' => $this->estadoestPadre,
            'estadoestTutor' => $this->estadoestTutor,
            'menorEmbarazada' => $this->menorEmbarazada,
            'apellidoBenefOtro' => $this->apellidoBenefOtro,
            'nombreBenefOtro' => $this->nombreBenefOtro,
            'fechaVerificado' => $this->fechaVerificado,
            'usuarioVerificado' => $this->usuarioVerificado,
            'apellidoagente' => $this->apellidoagente,
            'nombreagente' => $this->nombreagente,
            'centroInscriptor' => $this->centroInscriptor,
            'dniAgente' => $this->dniAgente,
            'edades' => $this->edades,
            'fallecido' => $this->fallecido,
        );

        return($returnDump);
    }

    public function getEstadoEnPadron() {
        if (!$this->estadoEnPadron) {
            $where = "clavebeneficiario='$this->claveBeneficiario'";
            $sql = "SELECT activo
			  FROM nacer.smiafiliados
			  WHERE $where";

            $result = sql($sql);

            $estado = $result->fields['activo'];
            if (is_null($estado)) {
                $this->estadoEnPadron = "No";
            } else {
                if ($estado == 'S') {
                    $this->estadoEnPadron = "Activo";
                } else {
                    $this->estadoEnPadron = "Inactivo";
                }
            }
        }
        return $this->estadoEnPadron;
    }

    public function getEmbarazado($fecha = '') {
        if ($fecha != '') {
            $this->embarazado = BeneficiariosUadColeccion::beneficiarioEmbarazadoUAD($this->claveBeneficiario, $fecha);
        }
        return $this->embarazado;
    }

    /* descripcion => devuelve un string sql
     * parametros => $where: condicion where de la consulta
     *               $fields => (csv o array) campos del select
     */

    public static function getSQlSelectWhere($where, $fields = "*") {
        if ($fields != "" || !is_null($fields)) {
            if (is_array($fields)) {
                $select = implode(",", $fields);
            } else {
                $select = $fields;
            }
        } else {
            $select = "*";
        }

        $sql = "SELECT " . $select . " 
                FROM uad.beneficiarios
                WHERE " . $where . "";

        return($sql);
    }

    public static function getSQlSelectNecesariosWhere($where) {

        $sql = "
			SELECT clave_beneficiario,apellido_benef,nombre_benef,numero_doc,fecha_nacimiento_benef,sexo  
			  FROM uad.beneficiarios
			  WHERE " . $where . "";

        return($sql);
    }

    public function Automata($where) {
        $sql = BeneficiarioUad::getSqlSelectWhere($where);
        $result = sql($sql);
        $this->construirResult($result);
    }

    public static function buscarPorClaveBeneficiario($clave) {
        $where = "clave_beneficiario='$clave'";
        $sql = BeneficiarioUad::getSqlSelectWhere($where);
        $result = sql($sql);
        if (!$result->EOF) {
            $beneficiario_aux = new BeneficiarioUad();
            $beneficiario_aux->construirResult($result);
        } else {
            $beneficiario_aux = null;
        }
        return $beneficiario_aux;
    }

    function getEdad($fecha_comprobante) {
        $dias_de_vida = GetCountDaysBetweenTwoDates($this->fechaNacimientoBenef, $fecha_comprobante);
        $edad = $dias_de_vida / 365;
        return $edad;
    }

    function getEdadDias($fecha_comprobante) {
        if (strtotime($this->fechaNacimientoBenef) > strtotime($fecha_comprobante)) {
            $dias_de_vida = GetCountDaysBetweenTwoDates($this->fechaNacimientoBenef, $fecha_comprobante) * -1;
        } else {
            $dias_de_vida = GetCountDaysBetweenTwoDates($this->fechaNacimientoBenef, $fecha_comprobante);
        }

        return $dias_de_vida;
    }

    private $grupoEtario;

    public function getGrupoEtareo($fecha_comprobante = '') {
        if ($fecha_comprobante != '') {
            $dias_de_vida = GetCountDaysBetweenTwoDates($this->fechaNacimientoBenef, $fecha_comprobante);
            $edad = $this->getEdad($fecha_comprobante);
            $grupo['edad'] = floor($edad);
            if (($dias_de_vida >= 0) && ($dias_de_vida <= 28)) {
                $grupo['categoria'] = 'neo';
                //  $grupo['descripcion'] = 'Grupo NeoNatal';
            } elseif (($dias_de_vida > 28) && ($dias_de_vida <= 364)) {
                $grupo['categoria'] = 'cero_a_uno';
                //  $grupo['descripcion'] = 'Grupo Menor de 1 a�o';
            } elseif (($dias_de_vida > 364) && ($dias_de_vida <= 2189 )) {
                $grupo['categoria'] = 'uno_a_seis';
                // $grupo['descripcion'] = 'Grupo de 1 a 5 a�os';
            } elseif (($dias_de_vida > 2189) && ($dias_de_vida <= 3649 )) {
                $grupo['categoria'] = 'seis_a_diez';
                // $grupo['descripcion'] = 'Grupo de 6 a 9 a�os';
            } elseif (($dias_de_vida > 3649) && ($dias_de_vida <= 7299 )) {
                $grupo['categoria'] = 'diez_a_veinte';
                // $grupo['descripcion'] = 'Grupo de 10 a 19 a�os';
            } elseif (($dias_de_vida > 7299) && ($dias_de_vida <= 23724 )) {
                $grupo['categoria'] = 'veinte_a_sesentaycuatro';
                //$grupo['descripcion'] = 'Grupo de 20 a 64 a�os';
            } else {
                $grupo['categoria'] = 'veinte_a_sesentaycuatro';
                //$grupo['descripcion'] = 'Grupo de 20 a 64 a�os';
            }
            $this->grupoEtario = $grupo['categoria'];
        }
        return $this->grupoEtario;
    }

    public function getArrayUTF8() {
        $datos['numeroDoc'] = utf8_encode($this->numeroDoc);
        $datos['apellidoBenef'] = utf8_encode($this->apellidoBenef);
        $datos['nombreBenef'] = utf8_encode($this->nombreBenef);
        $datos['claveBeneficiario'] = utf8_encode($this->claveBeneficiario);
        $datos['estadoEnPadron'] = utf8_encode($this->getEstadoEnPadron());
        $datos['embarazado'] = $this->getEmbarazado();
        $datos['grupoEtario'] = $this->getGrupoEtareo();
        $datos['sexo'] = utf8_encode($this->sexo);
        $datos['FechaNacimientoBenef'] = $this->getFechaNacimientoBenef();
        return $datos;
        // return get_object_vars(utf8_encode($this));
    }

    public function getDatosBasicosArrayUTF8() {
        $datos['numeroDoc'] = utf8_encode($this->numeroDoc);
        $datos['apellidoBenef'] = utf8_encode($this->apellidoBenef);
        $datos['nombreBenef'] = utf8_encode($this->nombreBenef);
        $datos['claveBeneficiario'] = utf8_encode($this->claveBeneficiario);
        $datos['sexo'] = utf8_encode($this->sexo);
        $datos['FechaNacimientoBenef'] = $this->getFechaNacimientoBenef();
        return $datos;
        // return get_object_vars(utf8_encode($this));
    }

    /*! 
     *  \brief     Documentacion para metodo log.
     *  \details   Persiste el estado actual del registro.
     *  \author    Pezzarini Pedro
     *  \date      09/10/2015
     *  \pre       Clase Inicializada con los valores de la tabla
     *  \bug       Normal, ejecución fallida php, SQL Exception
     *  \warning   Normal, php Warnings
     *  \copyright GNU Public License.
     */
    public function log(){
        global $_ses_user;
        
    }

    /*! 
     *  \brief     Documentacion para metodo __composelog.
     *  \details   Genera SQL para insertar log de cambios beneficiarios.
     *  \author    Pezzarini Pedro
     *  \date      13/10/2015
     *  \pre       Clase inicializada con valores
     *  \bug       Normal, ejecucion php
     *  \warning   Normal, php Warnings
     *  \copyright GNU Public License.
     */
    public function __composelog(){
        global $_ses_user;
        $sql = "INSERT INTO uad.log_beneficiarios(
            clave_beneficiario, 
            clase_documento_benef, 

            tipo_documento, 
            numero_doc, 
            fum, 
            fecha_diagnostico_embarazo, 

            fecha_probable_parto, 
            fecha_carga, 
            usuario_carga, 
            apellido_benef, 

            nombre_benef, 
            apellido_benef_otro, 
            nombre_benef_otro, 
            fecha_nacimiento_benef, 

            fecha_inscripcion, 
            estado_envio, 
            tipo_transaccion, 
            id_categoria, 

            sexo, 
            provincia_nac, 
            localidad_nac, 
            pais_nac, 
            indigena, 
            id_tribu, 

            id_lengua, 
            alfabeta, 
            estudios, 
            anio_mayor_nivel, 
            tipo_doc_madre, 

            nro_doc_madre, 
            apellido_madre, 
            nombre_madre, 
            alfabeta_madre, 

            estudios_madre, 
            anio_mayor_nivel_madre, 
            tipo_doc_padre, 
            nro_doc_padre, 

            apellido_padre, 
            nombre_padre, 
            alfabeta_padre, 
            estudios_padre, 

            anio_mayor_nivel_padre, 
            tipo_doc_tutor, 
            nro_doc_tutor, 
            apellido_tutor, 

            nombre_tutor, 
            alfabeta_tutor, 
            estudios_tutor, 
            anio_mayor_nivel_tutor, 

            semanas_embarazo, 
            fecha_efectiva_parto, 
            cuie_ea, 
            cuie_ah, 
            menor_convive_con_adulto, 

            calle, 
            numero_calle, 
            piso, 
            dpto, 
            manzana, 
            entre_calle_1, 
            entre_calle_2, 

            telefono, 
            departamento, 
            localidad, 
            municipio, 
            barrio, 
            cod_pos, 

            observaciones, 
            activo, 
            score_riesgo, 
            mail, 
            celular, 
            otrotel, 

            estadoest, 
            obsgenerales, 
            estadoest_madre, 
            tipo_ficha, 
            responsable, 

            discv, 
            disca, 
            discmo, 
            discme, 
            otradisc, 
            estadoest_padre, 
            estadoest_tutor, 

            menor_embarazada, 
            fecha_verificado, 
            usuario_verificado, 
            apellidoagente, 

            nombreagente, 
            centro_inscriptor, 
            dni_agente, 
            edades, 
            fallecido, 

            cod_ci, 
            cod_uad, 
            discha)
    
    VALUES (

        '".$this->claveBeneficiario."',
        '".$this->claseDocumentoBenef."',
        '".$this->tipoDocumento."',
        '".$this->numeroDoc."',
        '".$this->fum."',
        '".$this->fechaDiagnosticoEmbarazo."',
        $this->fechaProbableParto
        $this->apellidoBenef
        
        $this->estadoEnvio
        $this->tipoTransaccion
        $this->nombreBenef
        $this->idCategoria
        $this->sexo
        $this->fechaNacimientoBenef
        $this->provinciaNac
        $this->localidadNac
        $this->paisNac
        $this->indigena
        $this->idTribu
        $this->idLengua
        $this->alfabeta
        $this->estudios
        $this->anioMayorNivel
        $this->tipoDocMadre
        $this->nroDocMadre
        $this->apellidoMadre
        $this->nombreMadre
        $this->alfabetaMadre
        $this->estudiosMadre
        $this->anioMayorNivelMadre
        $this->tipoDocPadre
        $this->nroDocPadre
        $this->apellidoPadre
        $this->nombrePadre
        $this->alfabetaPadre
        $this->estudiosPadre
        $this->anioMayorNivelPadre
        $this->tipoDocTutor
        $this->nroDocTutor
        $this->apellidoTutor
        $this->nombreTutor
        $this->alfabetaTutor
        $this->estudiosTutor
        $this->anioMayorNivelTutor
        $this->semanasEmbarazo
        $this->fechaEfectivaParto
        $this->cuieEa
        $this->cuieAh
        $this->menorConviveConAdulto
        $this->calle
        $this->numeroCalle
        $this->piso
        $this->dpto
        $this->manzana
        $this->entreCalle1
        $this->entreCalle2
        $this->telefono
        $this->departamento
        $this->localidad
        $this->municipio
        $this->barrio
        $this->codPos
        $this->observaciones
        $this->fechaInscripcion
        $this->fechaCarga
        $this->usuarioCarga
        $this->activo
        $this->scoreRiesgo
        $this->mail
        $this->celular
        $this->otrotel
        $this->estadoest
        $this->obsgenerales
        $this->estadoestMadre
        $this->tipoFicha
        $this->responsable
        $this->discv
        $this->disca
        $this->discmo
        $this->discme
        $this->otradisc
        $this->estadoestPadre
        $this->estadoestTutor
        $this->menorEmbarazada
        $this->apellidoBenefOtro
        $this->nombreBenefOtro
        $this->fechaVerificado
        $this->usuarioVerificado
        $this->apellidoagente
        $this->nombreagente
        $this->centroInscriptor
        $this->dniAgente
        $this->edades
        $this->fallecido

            );";
    }

    public function logCambios($valores) {
        global $_ses_user;
        $flagLog = false;
        // comprobacion de cambio de valores en campos
        if ($this->getClaseDocumentoBenef() != strtoupper($valores[clase_documento_benef])) {
            $flagLog = true;
        }
        if ($this->getTipoDocumento() != strtoupper($valores[tipo_documento])) {
            $flagLog = true;
        }
        if ($this->getNumeroDoc() != $valores[numero_doc]) {
            $flagLog = true;
            if($this->getClaseDocumentoBenef()=='P'){
                //marcar registro para enviar msg Merge HL7
                $marcar_registro_hl7 = true;
            }
        }
        if ($this->getFum() != $valores[fum]) {
            if (is_null($this->getFum()) && $valores[fum] != "1899-12-30") {
                $flagLog = true;
            }
        }
        if ($this->getFechaDiagnosticoEmbarazo() != $valores[fecha_diagnostico_embarazo]) {
            if (is_null($this->getFechaDiagnosticoEmbarazo()) && $valores[fecha_diagnostico_embarazo] != "1899-12-30") {
                $flagLog = true;
            }
        }
        if ($this->getFechaProbableParto() != $valores[fecha_probable_parto]) {
            if (is_null($this->getFechaProbableParto()) && $valores[fecha_probable_parto] != "1899-12-30") {
                $flagLog = true;
            }
        }
        if ($this->getApellidoBenef() != $valores[apellido_benef]) {
            $flagLog = true;
        }
        if ($this->getNombreBenef() != $valores[nombre_benef]) {
            $flagLog = true;
        }
        if ($this->getApellidoBenefOtro() != $valores[apellido_benef_otro]) {
            $flagLog = true;
        }
        if ($this->getNombreBenefOtro() != $valores[nombre_benef_otro]) {
            $flagLog = true;
        }
        if ($this->getFechaNacimientoBenef() != $valores[fecha_nacimiento_benef]) {
            if (is_null($this->getFechaNacimientoBenef()) && $valores[fecha_nacimiento_benef] != "1899-12-30") {
                $flagLog = true;
            }
        }
        if ($this->getFechaInscripcion() != $valores[fecha_inscripcion]) {
            if (is_null($this->getFechaInscripcion()) && $valores[fecha_inscripcion] != "1899-12-30") {
                $flagLog = true;
            }
        }
        if ($flagLog) {
            // insercion en tabla de log
            // verificar si es primer cambio o ya existen otros
            $sql = "SELECT COUNT(*) AS total 
                    FROM uad.log_beneficiarios 
                    WHERE clave_beneficiario='" . $this->getClaveBeneficiario() . "'";
            $res = sql($sql);
            if ( (int)$res->fields['total'] < 1 ) {
                //inserto 1 registro adicional, el primer estado del benef.
                $sql_log = "INSERT INTO uad.log_beneficiarios(clave_beneficiario,clase_documento_benef,tipo_documento,numero_doc,fum,fecha_diagnostico_embarazo,fecha_probable_parto,fecha_carga,usuario_carga,apellido_benef,nombre_benef,apellido_benef_otro,nombre_benef_otro,fecha_nacimiento_benef,fecha_inscripcion)
                            SELECT clave_beneficiario,clase_documento_benef,tipo_documento,numero_doc,fum,fecha_diagnostico_embarazo,fecha_probable_parto,fecha_carga,CAST(usuario_carga AS INTEGER) AS usuario_carga,apellido_benef,nombre_benef,apellido_benef_otro,nombre_benef_otro,fecha_nacimiento_benef,fecha_inscripcion
                            FROM uad.beneficiarios
                            WHERE clave_beneficiario='" . $this->getClaveBeneficiario() . "'";
                sql($sql_log);
            }
            if($marcar_registro_hl7){
                $params[select] = array('log.id_log_beneficiarios AS id_log_beneficiarios,log.numero_doc');
                $params[limit] = '1';
                $res_log = $this->getLogCambios($params);
                $sql_marca = "UPDATE uad.log_beneficiarios SET mrg_hl7=TRUE WHERE id_log_beneficiarios='".$res_log->fields['id_log_beneficiarios']."'";
                sql($sql_marca);
            }
            $sql_log = "INSERT INTO uad.log_beneficiarios(clave_beneficiario,clase_documento_benef,tipo_documento,numero_doc,fum,fecha_diagnostico_embarazo,fecha_probable_parto,fecha_carga,usuario_carga,apellido_benef,nombre_benef,apellido_benef_otro,nombre_benef_otro,fecha_nacimiento_benef,fecha_inscripcion)
                        VALUES('" . $this->getClaveBeneficiario() . "','" . strtoupper($valores[clase_documento_benef]) . "','" . strtoupper($valores[tipo_documento]) . "','" . $valores[numero_doc] . "','" . $valores[fum] . "',
                               '" . $valores[fecha_diagnostico_embarazo] . "','" . $valores[fecha_probable_parto] . "',NOW(),'" . $_ses_user['id'] . "','" . strtoupper($valores[apellido_benef]) . "','" . strtoupper($valores[nombre_benef]) . "', 
                               '" . strtoupper($valores[apellido_benef_otro]) . "','" . strtoupper($valores[nombre_benef_otro]) . "','" . $valores[fecha_nacimiento_benef] . "','" . $valores[fecha_inscripcion] . "')";
            sql($sql_log);
        }
    }

    /* descripcion => devuelve un resultSet con el log de cambios del beneficiario (solo campos sensibles) 
     * parametros => $params: array con parametros para la query.
     *                        Prefijos de tabla: log => tabla log_beneficiarios 
     *                                           u   => tabla usuarios
     *                  [select] => (csv o array) campos del select. 
     *                  [where] => (string o array) condicion del where
     *                  [limit] => (integer) cantidad de registros a devolver
     */

    public function getLogCambios($params) {
        if ($params != "") {
            if ($params[select] != "" || !is_null($params[select])) {
                if (is_array($params[select])) {
                    $select = implode(",", $params[select]);
                } else {
                    $select = $params[select];
                }
            }
            if ($params[where] != "") {
                if (is_array($params[where])) {
                    $where = implode(" AND ", $params[where]);
                } else {
                    $where = " AND " . $params[where];
                }
            }
            if ($params[limit] != "") {
                $limit = " LIMIT " . $params[limit] . " ";
            }
        }

        $sql_log = "SELECT " . $select . " 
                    FROM uad.log_beneficiarios log 
                    JOIN sistema.usuarios u ON log.usuario_carga=u.id_usuario 
                    WHERE log.clave_beneficiario='" . $this->getClaveBeneficiario() . "' 
                    " . $where . "
                    ORDER BY log.id_log_beneficiarios DESC, log.fecha_carga DESC
                    " . $limit . "
                   ";
        return sql($sql_log);
    }

    public function getSQLUpdate() {
        $sql = "UPDATE uad.beneficiarios set ";
        !is_null($this->idBeneficiarios) ? $sql.="id_beneficiarios='" . $this->idBeneficiarios . "', " : false;
        !is_null($this->estadoEnvio) ? $sql.="estado_envio='" . $this->estadoEnvio . "', " : false;
        !is_null($this->claveBeneficiario) ? $sql.="clave_beneficiario='" . $this->claveBeneficiario . "', " : false;
        !is_null($this->tipoTransaccion) ? $sql.="tipo_transaccion='" . $this->tipoTransaccion . "', " : false;
        !is_null($this->apellidoBenef) ? $sql.="apellido_benef='" . $this->apellidoBenef . "', " : false;
        !is_null($this->nombreBenef) ? $sql.="nombre_benef='" . $this->nombreBenef . "', " : false;
        !is_null($this->claseDocumentoBenef) ? $sql.="clase_documento_benef='" . $this->claseDocumentoBenef . "', " : false;
        !is_null($this->tipoDocumento) ? $sql.="tipo_documento='" . $this->tipoDocumento . "', " : false;
        !is_null($this->numeroDoc) ? $sql.= "numero_doc='" . $this->numeroDoc . "', " : false;
        !is_null($this->idCategoria) ? $sql.="id_categoria='" . $this->idCategoria . "', " : false;
        !is_null($this->sexo) ? $sql.="sexo='" . $this->sexo . "', " : false;
        !is_null($this->fechaNacimientoBenef) ? $sql.= "fecha_nacimiento_benef='" . $this->fechaNacimientoBenef . "', " : false;
        !is_null($this->provinciaNac) ? $sql.="provincia_nac='" . $this->provinciaNac . "', " : false;
        !is_null($this->localidadNac) ? $sql.="localidad_nac='" . $this->localidadNac . "', " : false;
        !is_null($this->paisNac) ? $sql.="pais_nac='" . $this->paisNac . "', " : false;
        !is_null($this->indigena) ? $sql.="indigena='" . $this->indigena . "', " : false;
        !is_null($this->idTribu) ? $sql.="id_tribu='" . $this->idTribu . "', " : false;
        !is_null($this->idLengua) ? $sql.="id_lengua='" . $this->idLengua . "', " : false;
        !is_null($this->alfabeta) ? $sql.="alfabeta='" . $this->alfabeta . "', " : false;
        !is_null($this->estudios) ? $sql.="estudios='" . $this->estudios . "', " : false;
        !is_null($this->anioMayorNivel) ? $sql.="anio_mayor_nivel='" . $this->anioMayorNivel . "', " : false;
        !is_null($this->tipoDocMadre) ? $sql.="tipo_doc_madre='" . $this->tipoDocMadre . "', " : false;
        !is_null($this->nroDocMadre) ? $sql.="nro_doc_madre='" . $this->nroDocMadre . "', " : false;
        !is_null($this->apellidoMadre) ? $sql.="apellido_madre='" . $this->apellidoMadre . "', " : false;
        !is_null($this->nombreMadre) ? $sql.="nombre_madre='" . $this->nombreMadre . "', " : false;
        !is_null($this->alfabetaMadre) ? $sql.="alfabeta_madre='" . $this->alfabetaMadre . "', " : false;
        !is_null($this->estudiosMadre) ? $sql.="estudios_madre='" . $this->estudiosMadre . "', " : false;
        !is_null($this->anioMayorNivelMadre) ? $sql.="anio_mayor_nivel_madre='" . $this->anioMayorNivelMadre . "', " : false;
        !is_null($this->tipoDocPadre) ? $sql.="tipo_doc_padre='" . $this->tipoDocPadre . "', " : false;
        !is_null($this->nroDocPadre) ? $sql.="nro_doc_padre='" . $this->nroDocPadre . "', " : false;
        !is_null($this->apellidoPadre) ? $sql.="apellido_padre='" . $this->apellidoPadre . "', " : false;
        !is_null($this->nombrePadre) ? $sql.="nombre_padre='" . $this->nombrePadre . "', " : false;
        !is_null($this->alfabetaPadre) ? $sql.="alfabeta_padre='" . $this->alfabetaPadre . "', " : false;
        !is_null($this->estudiosPadre) ? $sql.="estudios_padre='" . $this->estudiosPadre . "', " : false;
        !is_null($this->anioMayorNivelPadre) ? $sql.="anio_mayor_nivel_padre='" . $this->anioMayorNivelPadre . "', " : false;
        !is_null($this->tipoDocTutor) ? $sql.="tipo_doc_tutor='" . $this->tipoDocTutor . "', " : false;
        !is_null($this->nroDocTutor) ? $sql.="nro_doc_tutor='" . $this->nroDocTutor . "', " : false;
        !is_null($this->apellidoTutor) ? $sql.="apellido_tutor='" . $this->apellidoTutor . "', " : false;
        !is_null($this->nombreTutor) ? $sql.="nombre_tutor='" . $this->nombreTutor . "', " : false;
        !is_null($this->alfabetaTutor) ? $sql.="alfabeta_tutor='" . $this->alfabetaTutor . "', " : false;
        !is_null($this->estudiosTutor) ? $sql.="estudios_tutor='" . $this->estudiosTutor . "', " : false;
        !is_null($this->anioMayorNivelTutor) ? $sql.="anio_mayor_nivel_tutor='" . $this->anioMayorNivelTutor . "', " : false;
        !is_null($this->fechaDiagnosticoEmbarazo) ? $sql.="fecha_diagnostico_embarazo='" . $this->fechaDiagnosticoEmbarazo . "', " : false;
        !is_null($this->semanasEmbarazo) ? $sql.="semanas_embarazo='" . $this->semanasEmbarazo . "', " : false;
        !is_null($this->fechaProbableParto) ? $sql.="fecha_probable_parto='" . $this->fechaProbableParto . "', " : false;
        !is_null($this->fechaEfectivaParto) ? $sql.="fecha_efectiva_parto='" . $this->fechaEfectivaParto . "', " : false;
        !is_null($this->cuieEa) ? $sql.="cuie_ea='" . $this->cuieEa . "', " : false;
        !is_null($this->cuieAh) ? $sql.="cuie_ah='" . $this->cuieAh . "', " : false;
        !is_null($this->menorConviveConAdulto) ? $sql.="menor_convive_con_adulto='" . $this->menorConviveConAdulto . "', " : false;
        !is_null($this->calle) ? $sql.="calle='" . $this->calle . "', " : false;
        !is_null($this->numeroCalle) ? $sql.="numero_calle='" . $this->numeroCalle . "', " : false;
        !is_null($this->piso) ? $sql.="piso='" . $this->piso . "', " : false;
        !is_null($this->dpto) ? $sql.="dpto='" . $this->dpto . "', " : false;
        !is_null($this->manzana) ? $sql.="manzana='" . $this->manzana . "', " : false;
        !is_null($this->entreCalle1) ? $sql.="entre_calle_1='" . $this->entreCalle1 . "', " : false;
        !is_null($this->entreCalle2) ? $sql.="entre_calle_2='" . $this->entreCalle2 . "', " : false;
        !is_null($this->telefono) ? $sql.="telefono='" . $this->telefono . "', " : false;
        !is_null($this->departamento) ? $sql.="departamento='" . $this->departamento . "', " : false;
        !is_null($this->localidad) ? $sql.="localidad='" . $this->localidad . "', " : false;
        !is_null($this->municipio) ? $sql.="municipio='" . $this->municipio . "', " : false;
        !is_null($this->barrio) ? $sql.="barrio='" . $this->barrio . "', " : false;
        !is_null($this->codPos) ? $sql.="cod_pos='" . $this->codPos . "', " : false;
        !is_null($this->observaciones) ? $sql.="observaciones='" . $this->observaciones . "', " : false;
        !is_null($this->fechaInscripcion) ? $sql.="fecha_inscripcion='" . $this->fechaInscripcion . "', " : false;
        !is_null($this->fechaCarga) ? $sql.="fecha_carga='" . $this->fechaCarga . "', " : false;
        !is_null($this->usuarioCarga) ? $sql.="usuario_carga='" . $this->usuarioCarga . "', " : false;
        !is_null($this->activo) ? $sql.="activo='" . $this->activo . "', " : false;
        !is_null($this->scoreRiesgo) ? $sql.="score_riesgo='" . $this->scoreRiesgo . "', " : false;
        !is_null($this->mail) ? $sql.="mail='" . $this->mail . "', " : false;
        !is_null($this->celular) ? $sql.="celular='" . $this->celular . "', " : false;
        !is_null($this->otrotel) ? $sql.="otrotel='" . $this->otrotel . "', " : false;
        !is_null($this->estadoest) ? $sql.="estadoest='" . $this->estadoest . "', " : false;
        !is_null($this->fum) ? $sql.="fum='" . $this->fum . "', " : false;
        !is_null($this->obsgenerales) ? $sql.="obsgenerales='" . $this->obsgenerales . "', " : false;
        !is_null($this->estadoestMadre) ? $sql.="estadoest_madre='" . $this->estadoestMadre . "', " : false;
        !is_null($this->tipoFicha) ? $sql.="tipo_ficha='" . $this->tipoFicha . "', " : false;
        !is_null($this->responsable) ? $sql.="responsable='" . $this->responsable . "', " : false;
        !is_null($this->discv) ? $sql.="discv='" . $this->discv . "', " : false;
        !is_null($this->disca) ? $sql.="disca='" . $this->disca . "', " : false;
        !is_null($this->discmo) ? $sql.="discmo='" . $this->discmo . "', " : false;
        !is_null($this->discme) ? $sql.="discme='" . $this->discme . "', " : false;
        !is_null($this->otradisc) ? $sql.="otradisc='" . $this->otradisc . "', " : false;
        !is_null($this->estadoestPadre) ? $sql.="estadoest_padre='" . $this->estadoestPadre . "', " : false;
        !is_null($this->estadoestTutor) ? $sql.="estadoest_tutor='" . $this->estadoestTutor . "', " : false;
        !is_null($this->menorEmbarazada) ? $sql.="menor_embarazada='" . $this->menorEmbarazada . "', " : false;
        !is_null($this->apellidoBenefOtro) ? $sql.="apellido_benef_otro='" . $this->apellidoBenefOtro . "', " : false;
        !is_null($this->nombreBenefOtro) ? $sql.="nombre_benef_otro='" . $this->nombreBenefOtro . "', " : false;
        !is_null($this->fechaVerificado) ? $sql.="fecha_verificado='" . $this->fechaVerificado . "', " : false;
        !is_null($this->usuarioVerificado) ? $sql.= "usuario_verificado='" . $this->usuarioVerificado . "', " : false;
        !is_null($this->apellidoagente) ? $sql.= "apellidoagente='" . $this->apellidoagente . "', " : false;
        !is_null($this->nombreagente) ? $sql.= "nombreagente='" . $this->nombreagente . "', " : false;
        !is_null($this->centroInscriptor) ? $sql.="centro_inscriptor='" . $this->centroInscriptor . "', " : false;
        !is_null($this->dniAgente) ? $sql.="dni_agente='" . $this->dniAgente . "', " : false;
        !is_null($this->edades) ? $sql.="edades='" . $this->edades . "', " : false;
        !is_null($this->fallecido) ? $sql.="fallecido='" . $this->fallecido . "' " : false;
        $sql.="where id_beneficiarios='$this->idBeneficiarios' ";
        $sql.="returning id_beneficiarios";
        return($sql);
    }

    public function actualizarBenefeficiarioUAD() {
        if ($this->idBeneficiarios) {
            $sql = $this->getSQLUpdate();
        }
        $result = sql($sql);
        return $result;
    }

    /* !
     * La funcion devuelve todos los datos de la persona necesarios 
     * para el envio de un mensaje de tipo ADT - A04 - A08 - A47
     */

    public static function getDatosHL7($clave_beneficiario, $numero_doc) {
        $sql = "SELECT b.apellido_benef,b.apellido_benef_otro,b.nombre_benef,b.nombre_benef_otro,b.tipo_documento,b.numero_doc,b.fecha_nacimiento_benef,
                       b.clave_beneficiario, CASE WHEN length(b.id_tribu) > 5 THEN b.id_tribu ELSE '' END AS id_tribu,
                       b.sexo,b.calle,b.numero_calle,b.localidad,b.cod_pos,b.pais_nac,b.celular,b.otrotel,b.num_hceu, 
                       b.tipo_doc_madre,b.nro_doc_madre,b.apellido_madre,b.nombre_madre,b.cuie_verifico,b.id_beneficiarios,
                       l.id_log_beneficiarios id_log, l.numero_doc as numero_doc_viejo, l.tipo_documento as tipo_documento_viejo, 
                       l.apellido_benef as apellido_benef_viejo, l.apellido_benef_otro as apellido_benef_otro_viejo,
                       l.nombre_benef as nombre_benef_viejo, l.nombre_benef_otro as nombre_benef_otro_viejo
                FROM uad.beneficiarios b
                    LEFT JOIN uad.log_beneficiarios l on b.clave_beneficiario=l.clave_beneficiario AND l.clase_documento_benef='P' AND l.mrg_hl7=TRUE 
                WHERE b.clave_beneficiario='$clave_beneficiario' and b.clase_documento_benef='P' and b.numero_doc='$numero_doc' 
                ORDER BY l.fecha_carga DESC
                LIMIT 1";
        return sql($sql);
    }

    public static function getSQLUpdateNumHCEU($id_beneficiarios, $num_hceu) {
        $sql = "Update uad.beneficiarios set num_hceu='$num_hceu' WHERE id_beneficiarios='$id_beneficiarios'";
        return $sql;
    }   
    
    public static function desmarcarIndicadorHL7Merge($id_registro){
        $sql = "UPDATE uad.log_beneficiarios SET mrg_hl7=NULL WHERE id_log_beneficiarios='$id_registro'";
        sql($sql);
    }

}

class BeneficiariosUadColeccion {
    /* ! 
     *  \brief     Documentacion para metodo Filtrar.
     *  \details   Filtra los beneficiarios por seleccion de campos y valores.
     *  \author    Pezzarini Pedro
     *  \date      23/01/2014
     *  \pre       --
     *  \bug       --
     *  \warning   --
     *  \copyright GNU Public License.
     */

    public function Filtrar($where) {
        $beneficiarios = array();
        $sql = BeneficiarioUad::getSqlSelectWhere($where);
        $result = sql($sql);
        while (!$result->EOF) {
            $beneficiarioTemp = new BeneficiarioUad();
            $beneficiarioTemp->construirResult($result);
            $beneficiarios[] = $beneficiarioTemp;
            unset($result->fields);
            $result->MoveNext();
        }

        unset($result);
        unset($beneficiarioTemp);

        return($beneficiarios);
    }

    public static function FiltrarToArray($where = '', $fecha_comprobante = '') {
        if (strlen($where) > 0) {
            $sql = BeneficiarioUad::getSQlSelectNecesariosWhere($where);
            $result = sql($sql);
        }

        $beneficiarios = array();

        while (!$result->EOF) {

            $registro = new BeneficiarioUad();
            $registro->construirResult($result);
            $registro->getEmbarazado($fecha_comprobante);
            $registro->getGrupoEtareo($fecha_comprobante);
            $beneficiarios[] = $registro->getArrayUTF8();

            $result->MoveNext();
        }

        return($beneficiarios);
    }

    public static function buscarLikeDNItoArray($dni, $limit = "5") {
        $where = "numero_doc::text ilike '%$dni%'";

        $sql = BeneficiarioUad::getSQlSelectNecesariosWhere($where);
        $sql.=" LIMIT $limit";

        $result = sql($sql);


        $beneficiarios = array();

        while (!$result->EOF) {

            $registro = new BeneficiarioUad();
            $registro->construirResult($result);
            //$registro->getEmbarazado($fecha_comprobante);
            //$registro->getGrupoEtareo($fecha_comprobante);
            $datosUTF8 = $registro->getDatosBasicosArrayUTF8();
            $datosUTF8['tieneDatosPatologia'] = BeneficiarioRusmiColeccion::tieneDatosPatologia($registro->getClaveBeneficiario());
            $habitaActualmente = HabitantesColeccion::buscarHabitantesPorClaveBeneficiario($registro->getClaveBeneficiario());
            if ($habitaActualmente) {
                $datosUTF8['viviendaActual'] = $habitaActualmente->getVivienda();
            }
            $beneficiarios[] = $datosUTF8;

            $result->MoveNext();
        }

        return($beneficiarios);
    }

    public static function buscarPorClaveBeneficiario($clave) {
        $where = "clave_beneficiario='$clave'";
        $sql = BeneficiarioUad::getSqlSelectWhere($where, "*");
        $result = sql($sql);
        if (!$result->EOF) {
            $beneficiario_aux = new BeneficiarioUad();
            $beneficiario_aux->construirResult($result);
        } else {
            $beneficiario_aux = null;
        }
        return $beneficiario_aux;
    }

    public static function existeClaveBeneficiario($clave) {
        if (!isset($clave))
            return false;
        $existe = false;
        $where = "clave_beneficiario='$clave'";
        $sql = BeneficiarioUad::getSqlSelectWhere($where);
        $result = sql($sql);
        if (!$result->EOF) {
            $existe = true;
        }
        return $existe;
    }

    public static function beneficiarioEmbarazadoUAD($clavebeneficiario, $fecha) {

        $embarazada = false;
        $sql = "SELECT fecha_probable_parto, fecha_diagnostico_embarazo from uad.beneficiarios where clave_beneficiario='$clavebeneficiario'";
        $result = sql($sql);

        $FPP_BEN = '';
        if ($result->fields['fecha_probable_parto'] != null) {
            $FPP_BEN = strtotime($result->fields['fecha_probable_parto']);
            $FDE_BEN = strtotime($result->fields['fecha_diagnostico_embarazo']);
        }

        $sql = "SELECT fechaprobableparto, fechadiagnosticoembarazo from nacer.smiafiliados where clavebeneficiario='$clavebeneficiario'";
        $result = sql($sql);

        $FPP_SMI = '';
        if ($result->fields['fechaprobableparto'] != null) {
            $FPP_SMI = strtotime($result->fields['fechaprobableparto']);
            $FDE_SMI = strtotime($result->fields['fechadiagnosticoembarazo']);
        }

        //si notiene dato de FPP en ninguna
        if ($FPP_BEN == '' and $FPP_SMI == '') {
            return $embarazada;
        }

        $FPP = '';
        if ($FPP_BEN == '' and $FPP_SMI != '') {
            $FPP = $FPP_SMI;
            $FDE = $FDE_SMI;
        }

        if ($FPP_BEN != '' and $FPP_SMI == '') {
            $FPP = $FPP_BEN;
            $FDE = $FDE_BEN;
        }

        //si tengo ambas fechas evaluo ambas

        if ($FPP_BEN != '' and $FPP_SMI != '') {
            if ($FPP_BEN > $FPP_SMI) {
                $FPP = $FPP_BEN;
                $FDE = $FDE_BEN;
            } else {
                if ($FPP_BEN < $FPP_SMI) {
                    $FPP = $FPP_SMI;
                    $FDE = $FDE_SMI;
                } else {
                    $FPP = $FPP_SMI;
                    $FDE = $FDE_SMI;
                }
            }
        }

        //if (($result->fields['fecha_probable_parto'] < $fecha) or ($result->fields['fecha_probable_parto'] == null)) {
        $fecha = strtotime($fecha);
        if ($FPP >= $fecha AND $FDE <= $fecha) {
            $embarazada = true;
        } else {
            if ($FPP < $fecha) {
                //se evalua el puerperio
                if ((($fecha - $FPP) / 60 / 60 / 24) <= 45) {
                    $embarazada = true;
                }
            }
        }

        return $embarazada;
    }

    public static function getSQLNivelesEstudio() {
        $sql = "SELECT DISTINCT(estudios) 
                FROM uad.beneficiarios
                WHERE estudios<>'' 
                ORDER BY estudios";
        return $sql;
    }

    // funciones estaticas para listados
    public static function getSQLCountListadoInscriptos($param, $filtro) {
        $sql = "SELECT COUNT(*) AS total 
                 FROM ( ";
        $sql .= BeneficiariosUadColeccion::getSQLListadoInscriptos($param, $filtro);
        $sql .= " ) ben ";
        return $sql;
    }

    /* Especificacion del Metodo
     * param es un array de parametros (los campos de $_REQUEST
     * filtro es un array con los campos para filtro en el where de la consulta
     * orden es el campo por el que se desea ordenar
     * dir es la direccion del order by en la consulta (asc o desc)
     */

    public static function getSQLListadoInscriptos($param, $filtro, $orden = "", $dir = "ASC", $limit = 99999, $offset = 0) {
        if ($param[keyword] != "") {
            if ($param[filter] == "b.apellido_benef") {
                $where .= " $param[filter] ILIKE '%$param[keyword]%' ";
            } else {
                $where .= " $param[filter]='$param[keyword]' ";
            }
        }
        if ($orden != "") {
            $sort = " ORDER BY $orden $dir ";
        }
        $limit = " LIMIT $limit OFFSET $offset ";
        $sql = "SELECT DISTINCT(b.id_beneficiarios), b.id_tribu ,b.indigena, b.municipio, b.clave_beneficiario, b.apellido_benef, 
                       b.apellido_benef_otro, b.numero_doc, b.nombre_benef, b.fecha_nacimiento_benef fecnac, b.estado_envio, 
                       b.activo, b.fecha_inscripcion fecins, b.clase_documento_benef clase_doc,
                       a.ceb, s.cuie || ' - ' || s.nombreefector nombre, a.activo AS activo_smi,
                       CASE WHEN a.id_smiafiliados IS NOT NULL THEN 1 ELSE 0 END AS existe_smi,
                       i.estado_beneficiario,i.clave_numero,m.descripcion as motivo_descripcion,b.celular, b.sexo
                FROM uad.beneficiarios b
                LEFT JOIN facturacion.smiefectores s ON b.cuie_ea=s.cuie 
                LEFT JOIN nacer.smiafiliados a ON b.clase_documento_benef=a.aficlasedoc
                                               AND b.tipo_documento=a.afitipodoc
                                               AND b.numero_doc=a.afidni 
                                               AND b.clave_beneficiario=a.clavebeneficiario
                LEFT JOIN incluir.beneficiario_incluir i ON (b.numero_doc=i.nro_documento and b.clase_documento_benef='P')
                LEFT JOIN incluir.motivo_baja m ON i.id_motivo_baja=m.id_motivo_baja
                WHERE 
                " . $where . "
                " . $sort . "
                " . $limit . "
               ";
//        echo $sql;
        return $sql;
    }

    public static function getSQLListadoInscriptosFiltrado($param) {

        if ($param['apellido'] != "") {
            $where.=" and b.apellido_benef like '%" . strtoupper($param['apellido']) . "%'";
        }

        if ($param['clave_benef'] != "") {
            $where.=" and b.clave_beneficiario='" . $param['clave_benef'] . "'";
        }
        if ($param['clave_is'] != "") {
            $where.=" and i.clave_numero='" . $param['clave_is'] . "'";
        }
        if ($param['departamento'] != "" and $param['departamento'] != "Seleccionar") {
            $where.=" and UPPER(b.departamento)='" . trim($param['departamento']) . "'";
        }
        if ($param['localidad'] != "" and $param['localidad'] != "Seleccionar") {
            $where.=" and UPPER(b.localidad)='" . trim($param['localidad']) . "'";
        }
        if ($param['nombre'] != "") {
            $where.=" and b.nombre_benef like '%" . strtoupper($param['nombre']) . "%'";
        }
        if ($param['num_doc'] != "") {
            $where.=" and b.numero_doc='" . trim($param['num_doc']) . "'";
        }
        if ($param['num_doc_madre'] != "") {
            $where.=" and b.nro_doc_madre='" . trim($param['num_doc_madre']) . "'";
        }
        if ($param['sexo'] != "Seleccionar") {
            $where.=" and b.sexo='" . $param['sexo'] . "'";
        }
        $cadena = substr(trim($where), 0, 3);
        if ($cadena == "and") {
            $where = substr(trim($where), 4);
        }
        if (isset($where)) {
            $sql = "SELECT DISTINCT(b.id_beneficiarios), b.id_tribu ,b.indigena, b.municipio, b.clave_beneficiario, b.apellido_benef, 
                       b.apellido_benef_otro, b.numero_doc, b.nombre_benef, b.fecha_nacimiento_benef fecnac, b.estado_envio, 
                       b.activo, b.fecha_inscripcion fecins, b.clase_documento_benef clase_doc,
                       a.ceb, s.cuie || ' - ' || s.nombreefector nombre, a.activo AS activo_smi,
                       CASE WHEN a.id_smiafiliados IS NOT NULL THEN 1 ELSE 0 END AS existe_smi,
                       i.estado_beneficiario,i.clave_numero,m.descripcion as motivo_descripcion,b.celular, b.sexo
                FROM uad.beneficiarios b
                LEFT JOIN facturacion.smiefectores s ON b.cuie_ea=s.cuie 
                LEFT JOIN nacer.smiafiliados a ON b.clase_documento_benef=a.aficlasedoc
                                               AND b.tipo_documento=a.afitipodoc
                                               AND b.numero_doc=a.afidni 
                                               AND b.clave_beneficiario=a.clavebeneficiario
                LEFT JOIN incluir.beneficiario_incluir i ON (b.numero_doc=i.nro_documento and b.clase_documento_benef='P')
                LEFT JOIN incluir.motivo_baja m ON i.id_motivo_baja=m.id_motivo_baja
                WHERE 
                " . $where;
//            echo $sql;
            return $sql;
        } else {
            return false;
        }
    }

    public static function getSQLCountListadoInscriptosAldea($param) {
        $sql = "SELECT COUNT(*) AS total 
                 FROM ( ";
        $sql .= BeneficiariosUadColeccion::getSQLListadoInscriptosAldea($param);
        $sql .= " ) ben ";
        return $sql;
    }

    /* Especificacion del Metodo
     * $filtro es un array de parametros (los campos de $_REQUEST)
     * $param es un array con los datos para armar la query
     *      |-> order =>  lista de campos para el ordenamiento. Puede ser en
     *                    formato csv o array (se le puede incluir el asc o desc)
     *      |-> limit =>  array con 2 posiciones para el LIMIT de la query
     *                    |-> limit => (integer)
     *                    |-> offset => (integer)
     */

    public static function getSQLListadoInscriptosAldea($param) {
        if ($param[select] != "") {
            if (is_array($param[select])) {
                $fields = implode(",", $param[select]);
            } else {
                $fields = $param[select];
            }
        } else {
            $fields = "DISTINCT(b.id_beneficiarios), b.id_tribu ,b.indigena, b.sexo, b.apellido_benef, 
                       b.apellido_benef_otro, b.numero_doc, b.nombre_benef, b.fecha_nacimiento_benef fecnac, 
                       b.estado_envio, b.activo, b.clase_documento_benef clase_doc, b.tipo_documento, 
                       b.semanas_embarazo, b.fecha_probable_parto, a.ceb, a.activo AS activo_smi, 
                       CASE WHEN a.id_smiafiliados IS NOT NULL THEN 1 ELSE 0 END AS existe_smi";
        }
        if ($param[where] != "") {
            if (is_array($param[where])) {
                $where = implode(" AND ", $param[where]);
            } else {
                $where = $param[where];
            }
        }
        if ($param[order] != "") {
            $orderby = "ORDER BY ";
            if (is_array($param[order])) {
                $orderby .= implode(",", $param[order]);
            } else {
                $orderby .= $param[order];
            }
        }
        if ($param[limit] != "") {
            $arr = $param[limit];
            $limit = $arr[limit] != "" ? $arr[limit] : 999999;
            $offset = $arr[offset] != "" ? $arr[offset] : 0;
            $limit = " LIMIT $limit OFFSET $offset ";
        }

        $sql = "SELECT " . $fields . "
                FROM uad.beneficiarios b
                LEFT JOIN nacer.smiafiliados a ON b.clase_documento_benef=a.aficlasedoc
                                               AND b.tipo_documento=a.afitipodoc
                                               AND b.numero_doc=a.afidni 
                                               AND b.clave_beneficiario=a.clavebeneficiario
                WHERE 
                " . $where . "
                " . $orderby . "
                " . $limit . "
               ";
        return $sql;
    }

}

?>