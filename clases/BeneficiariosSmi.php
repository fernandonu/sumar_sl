<?php

/**
 * 
 *
 * @version 1.107
 * @package entity
 */
class BeneficiarioSmi {

    private $idSmiafiliados;
    private $clavebeneficiario;
    private $afiapellido;
    private $afinombre;
    private $afitipodoc;
    private $aficlasedoc;
    private $afidni;
    private $afisexo;
    private $afitipocategoria;
    private $afifechanac;
    private $fechainscripcion;
    private $fechadiagnosticoembarazo;
    private $semanasembarazo;
    private $fechaprobableparto;
    private $fechaefectivaparto;
    private $activo;
    private $motivobaja;
    private $mensajebaja;
    private $idProcesobajaautomatica;
    private $fechacarga;
    private $clavebenefprovocobaja;
    private $idpersona;
    private $periodo;
    private $embarazoactual;
    private $fum;
    // Agregados

    private $afiprovincia;
    private $afilocalidad;
    private $afideclaraindigena;
    private $afiidLengua;
    private $afiidTribu;
    private $matipodocumento;
    private $manrodocumento;
    private $maapellido;
    private $manombre;
    private $patipodocumento;
    private $panrodocumento;
    private $paapellido;
    private $panombre;
    private $otrotipodocumento;
    private $otronrodocumento;
    private $otroapellido;
    private $otronombre;
    private $otrotiporelacion;
    private $fechaaltaefectiva;
    private $accionpendienteconfirmar;
    private $afidomcalle;
    private $afidomnro;
    private $afidommanzana;
    private $afidompiso;
    private $afidomdepto;
    private $afidomentrecalle1;
    private $afidomentrecalle2;
    private $afidombarrioparaje;
    private $afidommunicipio;
    private $afidomdepartamento;
    private $afidomlocalidad;
    private $afidomprovincia;
    private $afidomcp;
    private $afitelefono;
    private $lugaratencionhabitual;
    private $datosfechaenvio;
    private $fechaalta;
    private $pendienteenviar;
    private $codigoprovinciaaltadatos;
    private $codigouadaltadatos;
    private $codigocialtadatos;
    private $pendienteenviaranacion;
    private $usuariocarga;
    private $menorconvivecontutor;
    private $cuieefectorasignado;
    private $cuielugaratencionhabitual;
    private $fechabajaefectiva;
    private $fechaaltauec;
    private $auditoria;
    private $usuariocreacion;
    private $fechacreacion;
    private $confirmacionNroDocumento;
    private $scorederiesgo;
    private $benefalfabetizacion;
    private $benefalfabetaniosultimonivel;
    private $madrealfabetizacion;
    private $madrealfabetaniosultimonivel;
    private $padrealfabetizacion;
    private $padrealfabetaniosultimonivel;
    private $tutoralfabetizacion;
    private $tutoralfabetaniosultimonivel;
    private $activor;
    private $motivobajar;
    private $mensajebajar;
    private $email;
    private $numerocelular;
    private $observacionesgenerales;
    private $discapacidad;
    private $afipais;
    private $ceb;
    private $cuie;
    private $fechaultimaprestacion;
    private $codigoprestacion;
    private $devengacapita;
    private $devengacantidadcapita;
    private $grupopoblacional;

    public function construirResult($result) {
        $this->idSmiafiliados = $result->fields['id_smiafiliados'];
        $this->clavebeneficiario = $result->fields['clavebeneficiario'];
        $this->afiapellido = $result->fields['afiapellido'];
        $this->afinombre = $result->fields['afinombre'];
        $this->afitipodoc = $result->fields['afitipodoc'];
        $this->aficlasedoc = $result->fields['aficlasedoc'];
        $this->afidni = $result->fields['afidni'];
        $this->afisexo = $result->fields['afisexo'];
        $this->afitipocategoria = $result->fields['afitipocategoria'];
        $this->afifechanac = $result->fields['afifechanac'];
        $this->fechainscripcion = $result->fields['fechainscripcion'];
        $this->fechadiagnosticoembarazo = $result->fields['fechadiagnosticoembarazo'];
        $this->semanasembarazo = $result->fields['semanasembarazo'];
        $this->fechaprobableparto = $result->fields['fechaprobableparto'];
        $this->fechaefectivaparto = $result->fields['fechaefectivaparto'];
        $this->activo = $result->fields['activo'];
        $this->motivobaja = $result->fields['motivobaja'];
        $this->mensajebaja = $result->fields['mensajebaja'];
        $this->idProcesobajaautomatica = $result->fields['id_procesobajaautomatica'];
        $this->fechacarga = $result->fields['fechacarga'];
        $this->clavebenefprovocobaja = $result->fields['clavebenefprovocobaja'];
        $this->idpersona = $result->fields['idpersona'];
        $this->embarazoactual = $result->fields['embarazoactual'];
        $this->fum = $result->fields['fum'];


        // Agregados

        $this->afiprovincia = $result->fields['afiprovincia'];
        $this->afilocalidad = $result->fields['afilocalidad'];
        $this->afideclaraindigena = $result->fields['afideclaraindigena'];
        $this->afiidLengua = $result->fields['afiid_lengua'];
        $this->afiidTribu = $result->fields['afiid_tribu'];
        $this->matipodocumento = $result->fields['matipodocumento'];
        $this->manrodocumento = $result->fields['manrodocumento'];
        $this->maapellido = $result->fields['maapellido'];
        $this->manombre = $result->fields['manombre'];
        $this->patipodocumento = $result->fields['patipodocumento'];
        $this->panrodocumento = $result->fields['panrodocumento'];
        $this->paapellido = $result->fields['paapellido'];
        $this->panombre = $result->fields['panombre'];
        $this->otrotipodocumento = $result->fields['otrotipodocumento'];
        $this->otronrodocumento = $result->fields['otronrodocumento'];
        $this->otroapellido = $result->fields['otroapellido'];
        $this->otronombre = $result->fields['otronombre'];
        $this->otrotiporelacion = $result->fields['otrotiporelacion'];
        $this->fechaaltaefectiva = $result->fields['fechaaltaefectiva'];
        $this->accionpendienteconfirmar = $result->fields['accionpendienteconfirmar'];
        $this->afidomcalle = $result->fields['afidomcalle'];
        $this->afidomnro = $result->fields['afidomnro'];
        $this->afidommanzana = $result->fields['afidommanzana'];
        $this->afidompiso = $result->fields['afidompiso'];
        $this->afidomdepto = $result->fields['afidomdepto'];
        $this->afidomentrecalle1 = $result->fields['afidomentrecalle1'];
        $this->afidomentrecalle2 = $result->fields['afidomentrecalle2'];
        $this->afidombarrioparaje = $result->fields['afidombarrioparaje'];
        $this->afidommunicipio = $result->fields['afidommunicipio'];
        $this->afidomdepartamento = $result->fields['afidomdepartamento'];
        $this->afidomlocalidad = $result->fields['afidomlocalidad'];
        $this->afidomprovincia = $result->fields['afidomprovincia'];
        $this->afidomcp = $result->fields['afidomcp'];
        $this->afitelefono = $result->fields['afitelefono'];
        $this->lugaratencionhabitual = $result->fields['lugaratencionhabitual'];
        $this->datosfechaenvio = $result->fields['datosfechaenvio'];
        $this->fechaalta = $result->fields['fechaalta'];
        $this->pendienteenviar = $result->fields['pendienteenviar'];
        $this->codigoprovinciaaltadatos = $result->fields['codigoprovinciaaltadatos'];
        $this->codigouadaltadatos = $result->fields['codigouadaltadatos'];
        $this->codigocialtadatos = $result->fields['codigocialtadatos'];
        $this->pendienteenviaranacion = $result->fields['pendienteenviaranacion'];
        $this->usuariocarga = $result->fields['usuariocarga'];
        $this->menorconvivecontutor = $result->fields['menorconvivecontutor'];
        $this->cuieefectorasignado = $result->fields['cuieefectorasignado'];
        $this->cuielugaratencionhabitual = $result->fields['cuielugaratencionhabitual'];
        $this->fechabajaefectiva = $result->fields['fechabajaefectiva'];
        $this->fechaaltauec = $result->fields['fechaaltauec'];
        $this->auditoria = $result->fields['auditoria'];
        $this->usuariocreacion = $result->fields['usuariocreacion'];
        $this->fechacreacion = $result->fields['fechacreacion'];
        $this->confirmacionNroDocumento = $result->fields['confirmacion_nro_documento'];
        $this->scorederiesgo = $result->fields['scorederiesgo'];
        $this->benefalfabetizacion = $result->fields['benefalfabetizacion'];
        $this->benefalfabetaniosultimonivel = $result->fields['benefalfabetaniosultimonivel'];
        $this->madrealfabetizacion = $result->fields['madrealfabetizacion'];
        $this->madrealfabetaniosultimonivel = $result->fields['madrealfabetaniosultimonivel'];
        $this->padrealfabetizacion = $result->fields['padrealfabetizacion'];
        $this->padrealfabetaniosultimonivel = $result->fields['padrealfabetaniosultimonivel'];
        $this->tutoralfabetizacion = $result->fields['tutoralfabetizacion'];
        $this->tutoralfabetaniosultimonivel = $result->fields['tutoralfabetaniosultimonivel'];
        $this->activor = $result->fields['activor'];
        $this->motivobajar = $result->fields['motivobajar'];
        $this->mensajebajar = $result->fields['mensajebajar'];
        $this->email = $result->fields['email'];
        $this->numerocelular = $result->fields['numerocelular'];
        $this->observacionesgenerales = $result->fields['observacionesgenerales'];
        $this->discapacidad = $result->fields['discapacidad'];
        $this->afipais = $result->fields['afipais'];
        $this->ceb = $result->fields['ceb'];
        $this->cuie = $result->fields['cuie'];
        $this->fechaultimaprestacion = $result->fields['fechaultimaprestacion'];
        $this->codigoprestacion = $result->fields['codigoprestacion'];
        $this->devengacapita = $result->fields['devengacapita'];
        $this->devengacantidadcapita = $result->fields['devengacantidadcapita'];
        $this->grupopoblacional = $result->fields['grupopoblacional'];
    }

    /**
     * set value for id_smiafiliados 
     *
     * type:int4,size:10,default:null
     *
     * @param mixed $idSmiafiliados
     */
    public function setIdSmiafiliados($idSmiafiliados) {
        $this->idSmiafiliados = $idSmiafiliados;
    }

    /**
     * get value for id_smiafiliados 
     *
     * type:int4,size:10,default:null
     *
     * @return mixed
     */
    public function getIdSmiafiliados() {
        return $this->idSmiafiliados;
    }

    /**
     * set value for clavebeneficiario 
     *
     * type:varchar,size:16,default:null,primary,unique
     *
     * @param mixed $clavebeneficiario
     */
    public function setClavebeneficiario($clavebeneficiario) {
        $this->clavebeneficiario = $clavebeneficiario;
    }

    /**
     * get value for clavebeneficiario 
     *
     * type:varchar,size:16,default:null,primary,unique
     *
     * @return mixed
     */
    public function getClavebeneficiario() {
        return $this->clavebeneficiario;
    }

    /**
     * set value for afiapellido 
     *
     * type:varchar,size:40,default:null,nullable
     *
     * @param mixed $afiapellido
     */
    public function setAfiapellido($afiapellido) {
        $this->afiapellido = $afiapellido;
    }

    /**
     * get value for afiapellido 
     *
     * type:varchar,size:40,default:null,nullable
     *
     * @return mixed
     */
    public function getAfiapellido() {
        return $this->afiapellido;
    }

    /**
     * set value for afinombre 
     *
     * type:varchar,size:40,default:null,nullable
     *
     * @param mixed $afinombre
     */
    public function setAfinombre($afinombre) {
        $this->afinombre = $afinombre;
    }

    /**
     * get value for afinombre 
     *
     * type:varchar,size:40,default:null,nullable
     *
     * @return mixed
     */
    public function getAfinombre() {
        return $this->afinombre;
    }

    /**
     * set value for afitipodoc 
     *
     * type:varchar,size:5,default:null,nullable
     *
     * @param mixed $afitipodoc
     */
    public function setAfitipodoc($afitipodoc) {
        $this->afitipodoc = $afitipodoc;
    }

    /**
     * get value for afitipodoc 
     *
     * type:varchar,size:5,default:null,nullable
     *
     * @return mixed
     */
    public function getAfitipodoc() {
        return $this->afitipodoc;
    }

    /**
     * set value for aficlasedoc 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @param mixed $aficlasedoc
     */
    public function setAficlasedoc($aficlasedoc) {
        $this->aficlasedoc = $aficlasedoc;
    }

    /**
     * get value for aficlasedoc 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getAficlasedoc() {
        return $this->aficlasedoc;
    }

    /**
     * set value for afidni 
     *
     * type:varchar,size:12,default:null,nullable
     *
     * @param mixed $afidni
     */
    public function setAfidni($afidni) {
        $this->afidni = $afidni;
    }

    /**
     * get value for afidni 
     *
     * type:varchar,size:12,default:null,nullable
     *
     * @return mixed
     */
    public function getAfidni() {
        return $this->afidni;
    }

    /**
     * set value for afisexo 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @param mixed $afisexo
     */
    public function setAfisexo($afisexo) {
        $this->afisexo = $afisexo;
    }

    /**
     * get value for afisexo 
     *
     * type:varchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getAfisexo() {
        return $this->afisexo;
    }

    /**
     * set value for afitipocategoria 
     *
     * type:int2,size:5,default:null,nullable
     *
     * @param mixed $afitipocategoria
     */
    public function setAfitipocategoria($afitipocategoria) {
        $this->afitipocategoria = $afitipocategoria;
    }

    /**
     * get value for afitipocategoria 
     *
     * type:int2,size:5,default:null,nullable
     *
     * @return mixed
     */
    public function getAfitipocategoria() {
        return $this->afitipocategoria;
    }

    /**
     * set value for afifechanac 
     *
     * type:date,size:13,default:null,nullable
     *
     * @param mixed $afifechanac
     */
    public function setAfifechanac($afifechanac) {
        $this->afifechanac = $afifechanac;
    }

    /**
     * get value for afifechanac 
     *
     * type:date,size:13,default:null,nullable
     *
     * @return mixed
     */
    public function getAfifechanac() {
        return $this->afifechanac;
    }

    /**
     * set value for fechainscripcion 
     *
     * type:date,size:13,default:null,nullable
     *
     * @param mixed $fechainscripcion
     */
    public function setFechainscripcion($fechainscripcion) {
        $this->fechainscripcion = $fechainscripcion;
    }

    /**
     * get value for fechainscripcion 
     *
     * type:date,size:13,default:null,nullable
     *
     * @return mixed
     */
    public function getFechainscripcion() {
        return $this->fechainscripcion;
    }

    /**
     * set value for fechadiagnosticoembarazo 
     *
     * type:date,size:13,default:null,nullable
     *
     * @param mixed $fechadiagnosticoembarazo
     */
    public function setFechadiagnosticoembarazo($fechadiagnosticoembarazo) {
        $this->fechadiagnosticoembarazo = $fechadiagnosticoembarazo;
    }

    /**
     * get value for fechadiagnosticoembarazo 
     *
     * type:date,size:13,default:null,nullable
     *
     * @return mixed
     */
    public function getFechadiagnosticoembarazo() {
        return $this->fechadiagnosticoembarazo;
    }

    /**
     * set value for semanasembarazo 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @param mixed $semanasembarazo
     */
    public function setSemanasembarazo($semanasembarazo) {
        $this->semanasembarazo = $semanasembarazo;
    }

    /**
     * get value for semanasembarazo 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @return mixed
     */
    public function getSemanasembarazo() {
        return $this->semanasembarazo;
    }

    /**
     * set value for fechaprobableparto 
     *
     * type:date,size:13,default:null,nullable
     *
     * @param mixed $fechaprobableparto
     */
    public function setFechaprobableparto($fechaprobableparto) {
        $this->fechaprobableparto = $fechaprobableparto;
    }

    /**
     * get value for fechaprobableparto 
     *
     * type:date,size:13,default:null,nullable
     *
     * @return mixed
     */
    public function getFechaprobableparto() {
        return $this->fechaprobableparto;
    }

    /**
     * set value for fechaefectivaparto 
     *
     * type:date,size:13,default:null,nullable
     *
     * @param mixed $fechaefectivaparto
     */
    public function setFechaefectivaparto($fechaefectivaparto) {
        $this->fechaefectivaparto = $fechaefectivaparto;
    }

    /**
     * get value for fechaefectivaparto 
     *
     * type:date,size:13,default:null,nullable
     *
     * @return mixed
     */
    public function getFechaefectivaparto() {
        return $this->fechaefectivaparto;
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
     * set value for motivobaja 
     *
     * type:int2,size:5,default:null,nullable
     *
     * @param mixed $motivobaja
     */
    public function setMotivobaja($motivobaja) {
        $this->motivobaja = $motivobaja;
    }

    /**
     * get value for motivobaja 
     *
     * type:int2,size:5,default:null,nullable
     *
     * @return mixed
     */
    public function getMotivobaja() {
        return $this->motivobaja;
    }

    /**
     * set value for mensajebaja 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $mensajebaja
     */
    public function setMensajebaja($mensajebaja) {
        $this->mensajebaja = $mensajebaja;
    }

    /**
     * get value for mensajebaja 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getMensajebaja() {
        return $this->mensajebaja;
    }

    /**
     * set value for id_procesobajaautomatica 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @param mixed $idProcesobajaautomatica
     */
    public function setIdProcesobajaautomatica($idProcesobajaautomatica) {
        $this->idProcesobajaautomatica = $idProcesobajaautomatica;
    }

    /**
     * get value for id_procesobajaautomatica 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @return mixed
     */
    public function getIdProcesobajaautomatica() {
        return $this->idProcesobajaautomatica;
    }

    /**
     * set value for fechacarga 
     *
     * type:date,size:13,default:null,nullable
     *
     * @param mixed $fechacarga
     */
    public function setFechacarga($fechacarga) {
        $this->fechacarga = $fechacarga;
    }

    /**
     * get value for fechacarga 
     *
     * type:date,size:13,default:null,nullable
     *
     * @return mixed
     */
    public function getFechacarga() {
        return $this->fechacarga;
    }

    /**
     * set value for clavebenefprovocobaja 
     *
     * type:varchar,size:16,default:null,nullable
     *
     * @param mixed $clavebenefprovocobaja
     */
    public function setClavebenefprovocobaja($clavebenefprovocobaja) {
        $this->clavebenefprovocobaja = $clavebenefprovocobaja;
    }

    /**
     * get value for clavebenefprovocobaja 
     *
     * type:varchar,size:16,default:null,nullable
     *
     * @return mixed
     */
    public function getClavebenefprovocobaja() {
        return $this->clavebenefprovocobaja;
    }

    /**
     * set value for idpersona 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @param mixed $idpersona
     */
    public function setIdpersona($idpersona) {
        $this->idpersona = $idpersona;
    }

    /**
     * get value for idpersona 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @return mixed
     */
    public function getIdpersona() {
        return $this->idpersona;
    }

    /**
     * set value for periodo 
     *
     * type:varchar,size:6,default:null,primary,unique
     *
     * @param mixed $periodo
     */
    public function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }

    /**
     * get value for periodo 
     *
     * type:varchar,size:6,default:null,primary,unique
     *
     * @return mixed
     */
    public function getPeriodo() {
        return $this->periodo;
    }

    /**
     * set value for embarazoactual 
     *
     * type:bpchar,size:1,default:null,nullable
     *
     * @param mixed $embarazoactual
     */
    public function setEmbarazoactual($embarazoactual) {
        $this->embarazoactual = $embarazoactual;
    }

    /**
     * get value for embarazoactual 
     *
     * type:bpchar,size:1,default:null,nullable
     *
     * @return mixed
     */
    public function getEmbarazoactual() {
        return $this->embarazoactual;
    }

    /**
     * set value for fum 
     *
     * type:date,size:13,default:null,nullable
     *
     * @param mixed $fum
     */
    public function setFum($fum) {
        $this->fum = $fum;
    }

    /**
     * get value for fum 
     *
     * type:date,size:13,default:null,nullable
     *
     * @return mixed
     */
    public function getFum() {
        return $this->fum;
    }

    // Agregados

    /**
     * set value for afiprovincia
     *
     * type:
     *
     * @param mixed $afiprovincia
     */
    public function setAfiprovincia($afiprovincia) {
        $this->afiprovincia = $afiprovincia;
    }

    /**
     * get value for afiprovincia
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfiprovincia() {
        return($this->afiprovincia);
    }

    /**
     * set value for afilocalidad
     *
     * type:
     *
     * @param mixed $afilocalidad
     */
    public function setAfilocalidad($afilocalidad) {
        $this->afilocalidad = $afilocalidad;
    }

    /**
     * get value for afilocalidad
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfilocalidad() {
        return($this->afilocalidad);
    }

    /**
     * set value for afideclaraindigena
     *
     * type:
     *
     * @param mixed $afideclaraindigena
     */
    public function setAfideclaraindigena($afideclaraindigena) {
        $this->afideclaraindigena = $afideclaraindigena;
    }

    /**
     * get value for afideclaraindigena
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfideclaraindigena() {
        return($this->afideclaraindigena);
    }

    /**
     * set value for afiidLengua
     *
     * type:
     *
     * @param mixed $afiidLengua
     */
    public function setAfiidLengua($afiidLengua) {
        $this->afiidLengua = $afiidLengua;
    }

    /**
     * get value for afiidLengua
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfiidLengua() {
        return($this->afiidLengua);
    }

    /**
     * set value for afiidTribu
     *
     * type:
     *
     * @param mixed $afiidTribu
     */
    public function setAfiidTribu($afiidTribu) {
        $this->afiidTribu = $afiidTribu;
    }

    /**
     * get value for afiidTribu
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfiidTribu() {
        return($this->afiidTribu);
    }

    /**
     * set value for matipodocumento
     *
     * type:
     *
     * @param mixed $matipodocumento
     */
    public function setMatipodocumento($matipodocumento) {
        $this->matipodocumento = $matipodocumento;
    }

    /**
     * get value for matipodocumento
     *
     * type:
     * 
     * @return mixed
     */
    public function getMatipodocumento() {
        return($this->matipodocumento);
    }

    /**
     * set value for manrodocumento
     *
     * type:
     *
     * @param mixed $manrodocumento
     */
    public function setManrodocumento($manrodocumento) {
        $this->manrodocumento = $manrodocumento;
    }

    /**
     * get value for manrodocumento
     *
     * type:
     * 
     * @return mixed
     */
    public function getManrodocumento() {
        return($this->manrodocumento);
    }

    /**
     * set value for maapellido
     *
     * type:
     *
     * @param mixed $maapellido
     */
    public function setMaapellido($maapellido) {
        $this->maapellido = $maapellido;
    }

    /**
     * get value for maapellido
     *
     * type:
     * 
     * @return mixed
     */
    public function getMaapellido() {
        return($this->maapellido);
    }

    /**
     * set value for manombre
     *
     * type:
     *
     * @param mixed $manombre
     */
    public function setManombre($manombre) {
        $this->manombre = $manombre;
    }

    /**
     * get value for manombre
     *
     * type:
     * 
     * @return mixed
     */
    public function getManombre() {
        return($this->manombre);
    }

    /**
     * set value for patipodocumento
     *
     * type:
     *
     * @param mixed $patipodocumento
     */
    public function setPatipodocumento($patipodocumento) {
        $this->patipodocumento = $patipodocumento;
    }

    /**
     * get value for patipodocumento
     *
     * type:
     * 
     * @return mixed
     */
    public function getPatipodocumento() {
        return($this->patipodocumento);
    }

    /**
     * set value for panrodocumento
     *
     * type:
     *
     * @param mixed $panrodocumento
     */
    public function setPanrodocumento($panrodocumento) {
        $this->panrodocumento = $panrodocumento;
    }

    /**
     * get value for panrodocumento
     *
     * type:
     * 
     * @return mixed
     */
    public function getPanrodocumento() {
        return($this->panrodocumento);
    }

    /**
     * set value for paapellido
     *
     * type:
     *
     * @param mixed $paapellido
     */
    public function setPaapellido($paapellido) {
        $this->paapellido = $paapellido;
    }

    /**
     * get value for paapellido
     *
     * type:
     * 
     * @return mixed
     */
    public function getPaapellido() {
        return($this->paapellido);
    }

    /**
     * set value for panombre
     *
     * type:
     *
     * @param mixed $panombre
     */
    public function setPanombre($panombre) {
        $this->panombre = $panombre;
    }

    /**
     * get value for panombre
     *
     * type:
     * 
     * @return mixed
     */
    public function getPanombre() {
        return($this->panombre);
    }

    /**
     * set value for otrotipodocumento
     *
     * type:
     *
     * @param mixed $otrotipodocumento
     */
    public function setOtrotipodocumento($otrotipodocumento) {
        $this->otrotipodocumento = $otrotipodocumento;
    }

    /**
     * get value for otrotipodocumento
     *
     * type:
     * 
     * @return mixed
     */
    public function getOtrotipodocumento() {
        return($this->otrotipodocumento);
    }

    /**
     * set value for otronrodocumento
     *
     * type:
     *
     * @param mixed $otronrodocumento
     */
    public function setOtronrodocumento($otronrodocumento) {
        $this->otronrodocumento = $otronrodocumento;
    }

    /**
     * get value for otronrodocumento
     *
     * type:
     * 
     * @return mixed
     */
    public function getOtronrodocumento() {
        return($this->otronrodocumento);
    }

    /**
     * set value for otroapellido
     *
     * type:
     *
     * @param mixed $otroapellido
     */
    public function setOtroapellido($otroapellido) {
        $this->otroapellido = $otroapellido;
    }

    /**
     * get value for otroapellido
     *
     * type:
     * 
     * @return mixed
     */
    public function getOtroapellido() {
        return($this->otroapellido);
    }

    /**
     * set value for otronombre
     *
     * type:
     *
     * @param mixed $otronombre
     */
    public function setOtronombre($otronombre) {
        $this->otronombre = $otronombre;
    }

    /**
     * get value for otronombre
     *
     * type:
     * 
     * @return mixed
     */
    public function getOtronombre() {
        return($this->otronombre);
    }

    /**
     * set value for otrotiporelacion
     *
     * type:
     *
     * @param mixed $otrotiporelacion
     */
    public function setOtrotiporelacion($otrotiporelacion) {
        $this->otrotiporelacion = $otrotiporelacion;
    }

    /**
     * get value for otrotiporelacion
     *
     * type:
     * 
     * @return mixed
     */
    public function getOtrotiporelacion() {
        return($this->otrotiporelacion);
    }

    /**
     * set value for fechaaltaefectiva
     *
     * type:
     *
     * @param mixed $fechaaltaefectiva
     */
    public function setFechaaltaefectiva($fechaaltaefectiva) {
        $this->fechaaltaefectiva = $fechaaltaefectiva;
    }

    /**
     * get value for fechaaltaefectiva
     *
     * type:
     * 
     * @return mixed
     */
    public function getFechaaltaefectiva() {
        return($this->fechaaltaefectiva);
    }

    /**
     * set value for accionpendienteconfirmar
     *
     * type:
     *
     * @param mixed $accionpendienteconfirmar
     */
    public function setAccionpendienteconfirmar($accionpendienteconfirmar) {
        $this->accionpendienteconfirmar = $accionpendienteconfirmar;
    }

    /**
     * get value for accionpendienteconfirmar
     *
     * type:
     * 
     * @return mixed
     */
    public function getAccionpendienteconfirmar() {
        return($this->accionpendienteconfirmar);
    }

    /**
     * set value for afidomcalle
     *
     * type:
     *
     * @param mixed $afidomcalle
     */
    public function setAfidomcalle($afidomcalle) {
        $this->afidomcalle = $afidomcalle;
    }

    /**
     * get value for afidomcalle
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidomcalle() {
        return($this->afidomcalle);
    }

    /**
     * set value for afidomnro
     *
     * type:
     *
     * @param mixed $afidomnro
     */
    public function setAfidomnro($afidomnro) {
        $this->afidomnro = $afidomnro;
    }

    /**
     * get value for afidomnro
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidomnro() {
        return($this->afidomnro);
    }

    /**
     * set value for afidommanzana
     *
     * type:
     *
     * @param mixed $afidommanzana
     */
    public function setAfidommanzana($afidommanzana) {
        $this->afidommanzana = $afidommanzana;
    }

    /**
     * get value for afidommanzana
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidommanzana() {
        return($this->afidommanzana);
    }

    /**
     * set value for afidompiso
     *
     * type:
     *
     * @param mixed $afidompiso
     */
    public function setAfidompiso($afidompiso) {
        $this->afidompiso = $afidompiso;
    }

    /**
     * get value for afidompiso
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidompiso() {
        return($this->afidompiso);
    }

    /**
     * set value for afidomdepto
     *
     * type:
     *
     * @param mixed $afidomdepto
     */
    public function setAfidomdepto($afidomdepto) {
        $this->afidomdepto = $afidomdepto;
    }

    /**
     * get value for afidomdepto
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidomdepto() {
        return($this->afidomdepto);
    }

    /**
     * set value for afidomentrecalle1
     *
     * type:
     *
     * @param mixed $afidomentrecalle1
     */
    public function setAfidomentrecalle1($afidomentrecalle1) {
        $this->afidomentrecalle1 = $afidomentrecalle1;
    }

    /**
     * get value for afidomentrecalle1
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidomentrecalle1() {
        return($this->afidomentrecalle1);
    }

    /**
     * set value for afidomentrecalle2
     *
     * type:
     *
     * @param mixed $afidomentrecalle2
     */
    public function setAfidomentrecalle2($afidomentrecalle2) {
        $this->afidomentrecalle2 = $afidomentrecalle2;
    }

    /**
     * get value for afidomentrecalle2
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidomentrecalle2() {
        return($this->afidomentrecalle2);
    }

    /**
     * set value for afidombarrioparaje
     *
     * type:
     *
     * @param mixed $afidombarrioparaje
     */
    public function setAfidombarrioparaje($afidombarrioparaje) {
        $this->afidombarrioparaje = $afidombarrioparaje;
    }

    /**
     * get value for afidombarrioparaje
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidombarrioparaje() {
        return($this->afidombarrioparaje);
    }

    /**
     * set value for afidommunicipio
     *
     * type:
     *
     * @param mixed $afidommunicipio
     */
    public function setAfidommunicipio($afidommunicipio) {
        $this->afidommunicipio = $afidommunicipio;
    }

    /**
     * get value for afidommunicipio
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidommunicipio() {
        return($this->afidommunicipio);
    }

    /**
     * set value for afidomdepartamento
     *
     * type:
     *
     * @param mixed $afidomdepartamento
     */
    public function setAfidomdepartamento($afidomdepartamento) {
        $this->afidomdepartamento = $afidomdepartamento;
    }

    /**
     * get value for afidomdepartamento
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidomdepartamento() {
        return($this->afidomdepartamento);
    }

    /**
     * set value for afidomlocalidad
     *
     * type:
     *
     * @param mixed $afidomlocalidad
     */
    public function setAfidomlocalidad($afidomlocalidad) {
        $this->afidomlocalidad = $afidomlocalidad;
    }

    /**
     * get value for afidomlocalidad
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidomlocalidad() {
        return($this->afidomlocalidad);
    }

    /**
     * set value for afidomprovincia
     *
     * type:
     *
     * @param mixed $afidomprovincia
     */
    public function setAfidomprovincia($afidomprovincia) {
        $this->afidomprovincia = $afidomprovincia;
    }

    /**
     * get value for afidomprovincia
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidomprovincia() {
        return($this->afidomprovincia);
    }

    /**
     * set value for afidomcp
     *
     * type:
     *
     * @param mixed $afidomcp
     */
    public function setAfidomcp($afidomcp) {
        $this->afidomcp = $afidomcp;
    }

    /**
     * get value for afidomcp
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfidomcp() {
        return($this->afidomcp);
    }

    /**
     * set value for afitelefono
     *
     * type:
     *
     * @param mixed $afitelefono
     */
    public function setAfitelefono($afitelefono) {
        $this->afitelefono = $afitelefono;
    }

    /**
     * get value for afitelefono
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfitelefono() {
        return($this->afitelefono);
    }

    /**
     * set value for lugaratencionhabitual
     *
     * type:
     *
     * @param mixed $lugaratencionhabitual
     */
    public function setLugaratencionhabitual($lugaratencionhabitual) {
        $this->lugaratencionhabitual = $lugaratencionhabitual;
    }

    /**
     * get value for lugaratencionhabitual
     *
     * type:
     * 
     * @return mixed
     */
    public function getLugaratencionhabitual() {
        return($this->lugaratencionhabitual);
    }

    /**
     * set value for datosfechaenvio
     *
     * type:
     *
     * @param mixed $datosfechaenvio
     */
    public function setDatosfechaenvio($datosfechaenvio) {
        $this->datosfechaenvio = $datosfechaenvio;
    }

    /**
     * get value for datosfechaenvio
     *
     * type:
     * 
     * @return mixed
     */
    public function getDatosfechaenvio() {
        return($this->datosfechaenvio);
    }

    /**
     * set value for fechaalta
     *
     * type:
     *
     * @param mixed $fechaalta
     */
    public function setFechaalta($fechaalta) {
        $this->fechaalta = $fechaalta;
    }

    /**
     * get value for fechaalta
     *
     * type:
     * 
     * @return mixed
     */
    public function getFechaalta() {
        return($this->fechaalta);
    }

    /**
     * set value for pendienteenviar
     *
     * type:
     *
     * @param mixed $pendienteenviar
     */
    public function setPendienteenviar($pendienteenviar) {
        $this->pendienteenviar = $pendienteenviar;
    }

    /**
     * get value for pendienteenviar
     *
     * type:
     * 
     * @return mixed
     */
    public function getPendienteenviar() {
        return($this->pendienteenviar);
    }

    /**
     * set value for codigoprovinciaaltadatos
     *
     * type:
     *
     * @param mixed $codigoprovinciaaltadatos
     */
    public function setCodigoprovinciaaltadatos($codigoprovinciaaltadatos) {
        $this->codigoprovinciaaltadatos = $codigoprovinciaaltadatos;
    }

    /**
     * get value for codigoprovinciaaltadatos
     *
     * type:
     * 
     * @return mixed
     */
    public function getCodigoprovinciaaltadatos() {
        return($this->codigoprovinciaaltadatos);
    }

    /**
     * set value for codigouadaltadatos
     *
     * type:
     *
     * @param mixed $codigouadaltadatos
     */
    public function setCodigouadaltadatos($codigouadaltadatos) {
        $this->codigouadaltadatos = $codigouadaltadatos;
    }

    /**
     * get value for codigouadaltadatos
     *
     * type:
     * 
     * @return mixed
     */
    public function getCodigouadaltadatos() {
        return($this->codigouadaltadatos);
    }

    /**
     * set value for codigocialtadatos
     *
     * type:
     *
     * @param mixed $codigocialtadatos
     */
    public function setCodigocialtadatos($codigocialtadatos) {
        $this->codigocialtadatos = $codigocialtadatos;
    }

    /**
     * get value for codigocialtadatos
     *
     * type:
     * 
     * @return mixed
     */
    public function getCodigocialtadatos() {
        return($this->codigocialtadatos);
    }

    /**
     * set value for pendienteenviaranacion
     *
     * type:
     *
     * @param mixed $pendienteenviaranacion
     */
    public function setPendienteenviaranacion($pendienteenviaranacion) {
        $this->pendienteenviaranacion = $pendienteenviaranacion;
    }

    /**
     * get value for pendienteenviaranacion
     *
     * type:
     * 
     * @return mixed
     */
    public function getPendienteenviaranacion() {
        return($this->pendienteenviaranacion);
    }

    /**
     * set value for usuariocarga
     *
     * type:
     *
     * @param mixed $usuariocarga
     */
    public function setUsuariocarga($usuariocarga) {
        $this->usuariocarga = $usuariocarga;
    }

    /**
     * get value for usuariocarga
     *
     * type:
     * 
     * @return mixed
     */
    public function getUsuariocarga() {
        return($this->usuariocarga);
    }

    /**
     * set value for menorconvivecontutor
     *
     * type:
     *
     * @param mixed $menorconvivecontutor
     */
    public function setMenorconvivecontutor($menorconvivecontutor) {
        $this->menorconvivecontutor = $menorconvivecontutor;
    }

    /**
     * get value for menorconvivecontutor
     *
     * type:
     * 
     * @return mixed
     */
    public function getMenorconvivecontutor() {
        return($this->menorconvivecontutor);
    }

    /**
     * set value for cuieefectorasignado
     *
     * type:
     *
     * @param mixed $cuieefectorasignado
     */
    public function setCuieefectorasignado($cuieefectorasignado) {
        $this->cuieefectorasignado = $cuieefectorasignado;
    }

    /**
     * get value for cuieefectorasignado
     *
     * type:
     * 
     * @return mixed
     */
    public function getCuieefectorasignado() {
        return($this->cuieefectorasignado);
    }

    /**
     * set value for cuielugaratencionhabitual
     *
     * type:
     *
     * @param mixed $cuielugaratencionhabitual
     */
    public function setCuielugaratencionhabitual($cuielugaratencionhabitual) {
        $this->cuielugaratencionhabitual = $cuielugaratencionhabitual;
    }

    /**
     * get value for cuielugaratencionhabitual
     *
     * type:
     * 
     * @return mixed
     */
    public function getCuielugaratencionhabitual() {
        return($this->cuielugaratencionhabitual);
    }

    /**
     * set value for fechabajaefectiva
     *
     * type:
     *
     * @param mixed $fechabajaefectiva
     */
    public function setFechabajaefectiva($fechabajaefectiva) {
        $this->fechabajaefectiva = $fechabajaefectiva;
    }

    /**
     * get value for fechabajaefectiva
     *
     * type:
     * 
     * @return mixed
     */
    public function getFechabajaefectiva() {
        return($this->fechabajaefectiva);
    }

    /**
     * set value for fechaaltauec
     *
     * type:
     *
     * @param mixed $fechaaltauec
     */
    public function setFechaaltauec($fechaaltauec) {
        $this->fechaaltauec = $fechaaltauec;
    }

    /**
     * get value for fechaaltauec
     *
     * type:
     * 
     * @return mixed
     */
    public function getFechaaltauec() {
        return($this->fechaaltauec);
    }

    /**
     * set value for auditoria
     *
     * type:
     *
     * @param mixed $auditoria
     */
    public function setAuditoria($auditoria) {
        $this->auditoria = $auditoria;
    }

    /**
     * get value for auditoria
     *
     * type:
     * 
     * @return mixed
     */
    public function getAuditoria() {
        return($this->auditoria);
    }

    /**
     * set value for usuariocreacion
     *
     * type:
     *
     * @param mixed $usuariocreacion
     */
    public function setUsuariocreacion($usuariocreacion) {
        $this->usuariocreacion = $usuariocreacion;
    }

    /**
     * get value for usuariocreacion
     *
     * type:
     * 
     * @return mixed
     */
    public function getUsuariocreacion() {
        return($this->usuariocreacion);
    }

    /**
     * set value for fechacreacion
     *
     * type:
     *
     * @param mixed $fechacreacion
     */
    public function setFechacreacion($fechacreacion) {
        $this->fechacreacion = $fechacreacion;
    }

    /**
     * get value for fechacreacion
     *
     * type:
     * 
     * @return mixed
     */
    public function getFechacreacion() {
        return($this->fechacreacion);
    }

    /**
     * set value for confirmacionNroDocumento
     *
     * type:
     *
     * @param mixed $confirmacionNroDocumento
     */
    public function setConfirmacionNroDocumento($confirmacionNroDocumento) {
        $this->confirmacionNroDocumento = $confirmacionNroDocumento;
    }

    /**
     * get value for confirmacionNroDocumento
     *
     * type:
     * 
     * @return mixed
     */
    public function getConfirmacionNroDocumento() {
        return($this->confirmacionNroDocumento);
    }

    /**
     * set value for scorederiesgo
     *
     * type:
     *
     * @param mixed $scorederiesgo
     */
    public function setScorederiesgo($scorederiesgo) {
        $this->scorederiesgo = $scorederiesgo;
    }

    /**
     * get value for scorederiesgo
     *
     * type:
     * 
     * @return mixed
     */
    public function getScorederiesgo() {
        return($this->scorederiesgo);
    }

    /**
     * set value for benefalfabetizacion
     *
     * type:
     *
     * @param mixed $benefalfabetizacion
     */
    public function setBenefalfabetizacion($benefalfabetizacion) {
        $this->benefalfabetizacion = $benefalfabetizacion;
    }

    /**
     * get value for benefalfabetizacion
     *
     * type:
     * 
     * @return mixed
     */
    public function getBenefalfabetizacion() {
        return($this->benefalfabetizacion);
    }

    /**
     * set value for benefalfabetaniosultimonivel
     *
     * type:
     *
     * @param mixed $benefalfabetaniosultimonivel
     */
    public function setBenefalfabetaniosultimonivel($benefalfabetaniosultimonivel) {
        $this->benefalfabetaniosultimonivel = $benefalfabetaniosultimonivel;
    }

    /**
     * get value for benefalfabetaniosultimonivel
     *
     * type:
     * 
     * @return mixed
     */
    public function getBenefalfabetaniosultimonivel() {
        return($this->benefalfabetaniosultimonivel);
    }

    /**
     * set value for madrealfabetizacion
     *
     * type:
     *
     * @param mixed $madrealfabetizacion
     */
    public function setMadrealfabetizacion($madrealfabetizacion) {
        $this->madrealfabetizacion = $madrealfabetizacion;
    }

    /**
     * get value for madrealfabetizacion
     *
     * type:
     * 
     * @return mixed
     */
    public function getMadrealfabetizacion() {
        return($this->madrealfabetizacion);
    }

    /**
     * set value for madrealfabetaniosultimonivel
     *
     * type:
     *
     * @param mixed $madrealfabetaniosultimonivel
     */
    public function setMadrealfabetaniosultimonivel($madrealfabetaniosultimonivel) {
        $this->madrealfabetaniosultimonivel = $madrealfabetaniosultimonivel;
    }

    /**
     * get value for madrealfabetaniosultimonivel
     *
     * type:
     * 
     * @return mixed
     */
    public function getMadrealfabetaniosultimonivel() {
        return($this->madrealfabetaniosultimonivel);
    }

    /**
     * set value for padrealfabetizacion
     *
     * type:
     *
     * @param mixed $padrealfabetizacion
     */
    public function setPadrealfabetizacion($padrealfabetizacion) {
        $this->padrealfabetizacion = $padrealfabetizacion;
    }

    /**
     * get value for padrealfabetizacion
     *
     * type:
     * 
     * @return mixed
     */
    public function getPadrealfabetizacion() {
        return($this->padrealfabetizacion);
    }

    /**
     * set value for padrealfabetaniosultimonivel
     *
     * type:
     *
     * @param mixed $padrealfabetaniosultimonivel
     */
    public function setPadrealfabetaniosultimonivel($padrealfabetaniosultimonivel) {
        $this->padrealfabetaniosultimonivel = $padrealfabetaniosultimonivel;
    }

    /**
     * get value for padrealfabetaniosultimonivel
     *
     * type:
     * 
     * @return mixed
     */
    public function getPadrealfabetaniosultimonivel() {
        return($this->padrealfabetaniosultimonivel);
    }

    /**
     * set value for tutoralfabetizacion
     *
     * type:
     *
     * @param mixed $tutoralfabetizacion
     */
    public function setTutoralfabetizacion($tutoralfabetizacion) {
        $this->tutoralfabetizacion = $tutoralfabetizacion;
    }

    /**
     * get value for tutoralfabetizacion
     *
     * type:
     * 
     * @return mixed
     */
    public function getTutoralfabetizacion() {
        return($this->tutoralfabetizacion);
    }

    /**
     * set value for tutoralfabetaniosultimonivel
     *
     * type:
     *
     * @param mixed $tutoralfabetaniosultimonivel
     */
    public function setTutoralfabetaniosultimonivel($tutoralfabetaniosultimonivel) {
        $this->tutoralfabetaniosultimonivel = $tutoralfabetaniosultimonivel;
    }

    /**
     * get value for tutoralfabetaniosultimonivel
     *
     * type:
     * 
     * @return mixed
     */
    public function getTutoralfabetaniosultimonivel() {
        return($this->tutoralfabetaniosultimonivel);
    }

    /**
     * set value for activor
     *
     * type:
     *
     * @param mixed $activor
     */
    public function setActivor($activor) {
        $this->activor = $activor;
    }

    /**
     * get value for activor
     *
     * type:
     * 
     * @return mixed
     */
    public function getActivor() {
        return($this->activor);
    }

    /**
     * set value for motivobajar
     *
     * type:
     *
     * @param mixed $motivobajar
     */
    public function setMotivobajar($motivobajar) {
        $this->motivobajar = $motivobajar;
    }

    /**
     * get value for motivobajar
     *
     * type:
     * 
     * @return mixed
     */
    public function getMotivobajar() {
        return($this->motivobajar);
    }

    /**
     * set value for mensajebajar
     *
     * type:
     *
     * @param mixed $mensajebajar
     */
    public function setMensajebajar($mensajebajar) {
        $this->mensajebajar = $mensajebajar;
    }

    /**
     * get value for mensajebajar
     *
     * type:
     * 
     * @return mixed
     */
    public function getMensajebajar() {
        return($this->mensajebajar);
    }

    /**
     * set value for email
     *
     * type:
     *
     * @param mixed $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * get value for email
     *
     * type:
     * 
     * @return mixed
     */
    public function getEmail() {
        return($this->email);
    }

    /**
     * set value for numerocelular
     *
     * type:
     *
     * @param mixed $numerocelular
     */
    public function setNumerocelular($numerocelular) {
        $this->numerocelular = $numerocelular;
    }

    /**
     * get value for numerocelular
     *
     * type:
     * 
     * @return mixed
     */
    public function getNumerocelular() {
        return($this->numerocelular);
    }

    /**
     * set value for observacionesgenerales
     *
     * type:
     *
     * @param mixed $observacionesgenerales
     */
    public function setObservacionesgenerales($observacionesgenerales) {
        $this->observacionesgenerales = $observacionesgenerales;
    }

    /**
     * get value for observacionesgenerales
     *
     * type:
     * 
     * @return mixed
     */
    public function getObservacionesgenerales() {
        return($this->observacionesgenerales);
    }

    /**
     * set value for discapacidad
     *
     * type:
     *
     * @param mixed $discapacidad
     */
    public function setDiscapacidad($discapacidad) {
        $this->discapacidad = $discapacidad;
    }

    /**
     * get value for discapacidad
     *
     * type:
     * 
     * @return mixed
     */
    public function getDiscapacidad() {
        return($this->discapacidad);
    }

    /**
     * set value for afipais
     *
     * type:
     *
     * @param mixed $afipais
     */
    public function setAfipais($afipais) {
        $this->afipais = $afipais;
    }

    /**
     * get value for afipais
     *
     * type:
     * 
     * @return mixed
     */
    public function getAfipais() {
        return($this->afipais);
    }

    /**
     * set value for ceb
     *
     * type:
     *
     * @param mixed $ceb
     */
    public function setCeb($ceb) {
        $this->ceb = $ceb;
    }

    /**
     * get value for ceb
     *
     * type:
     * 
     * @return mixed
     */
    public function getCeb() {
        return($this->ceb);
    }

    /**
     * set value for cuie
     *
     * type:
     *
     * @param mixed $cuie
     */
    public function setCuie($cuie) {
        $this->cuie = $cuie;
    }

    /**
     * get value for cuie
     *
     * type:
     * 
     * @return mixed
     */
    public function getCuie() {
        return($this->cuie);
    }

    /**
     * set value for fechaultimaprestacion
     *
     * type:
     *
     * @param mixed $fechaultimaprestacion
     */
    public function setFechaultimaprestacion($fechaultimaprestacion) {
        $this->fechaultimaprestacion = $fechaultimaprestacion;
    }

    /**
     * get value for fechaultimaprestacion
     *
     * type:
     * 
     * @return mixed
     */
    public function getFechaultimaprestacion() {
        return($this->fechaultimaprestacion);
    }

    /**
     * set value for codigoprestacion
     *
     * type:
     *
     * @param mixed $codigoprestacion
     */
    public function setCodigoprestacion($codigoprestacion) {
        $this->codigoprestacion = $codigoprestacion;
    }

    /**
     * get value for codigoprestacion
     *
     * type:
     * 
     * @return mixed
     */
    public function getCodigoprestacion() {
        return($this->codigoprestacion);
    }

    /**
     * set value for devengacapita
     *
     * type:
     *
     * @param mixed $devengacapita
     */
    public function setDevengacapita($devengacapita) {
        $this->devengacapita = $devengacapita;
    }

    /**
     * get value for devengacapita
     *
     * type:
     * 
     * @return mixed
     */
    public function getDevengacapita() {
        return($this->devengacapita);
    }

    /**
     * set value for devengacantidadcapita
     *
     * type:
     *
     * @param mixed $devengacantidadcapita
     */
    public function setDevengacantidadcapita($devengacantidadcapita) {
        $this->devengacantidadcapita = $devengacantidadcapita;
    }

    /**
     * get value for devengacantidadcapita
     *
     * type:
     * 
     * @return mixed
     */
    public function getDevengacantidadcapita() {
        return($this->devengacantidadcapita);
    }

    /**
     * set value for grupopoblacional
     *
     * type:
     *
     * @param mixed $grupopoblacional
     */
    public function setGrupopoblacional($grupopoblacional) {
        $this->grupopoblacional = $grupopoblacional;
    }

    /**
     * get value for grupopoblacional
     *
     * type:
     * 
     * @return mixed
     */
    public function getGrupopoblacional() {
        return($this->grupopoblacional);
    }

    public static function getSQlSelectWhere($where) {

        $sql = "
			SELECT *
			  FROM nacer.smiafiliados
			  WHERE " . $where . "";

        return($sql);
    }

    public function Automata($where) {
        $sql = BeneficiarioSmi::getSqlSelectWhere($where);
        $result = sql($sql);
        $this->construirResult($result);
    }

    //deprecating hacia la coleccion
    public static function buscarPorClaveBeneficiario($clave) {
        $where = "clavebeneficiario='$clave'";
        $sql = BeneficiarioSmi::getSqlSelectWhere($where);
        $result = sql($sql);
        if (!$result->EOF) {
            $beneficiario_aux = new BeneficiarioSmi();
            $beneficiario_aux->construirResult($result);
        } else {
            $beneficiario_aux = null;
        }
        return $beneficiario_aux;
    }

    private $grupo_etario;

    public function calcularGrupoEtareo($fecha_comprobante = null) {

        if (!$fecha_comprobante) {
            $fecha_comprobante = date('d-m-Y');
        }
        $dias_de_vida = GetCountDaysBetweenTwoDates($this->afifechanac, $fecha_comprobante);
        $grupo['dias_de_vida'] = $dias_de_vida;
        $edad = calcularEdad($this->afifechanac, $fecha_comprobante);
        $grupo['edad'] = floor($edad);
        if (($dias_de_vida >= 0) && ($dias_de_vida <= 28)) {
            $grupo['categoria'] = 'neo';
            $grupo['descripcion'] = 'Grupo NeoNatal';
        } elseif (($dias_de_vida > 28) && ($dias_de_vida <= 364)) {
            $grupo['categoria'] = 'cero_a_uno';
            $grupo['descripcion'] = 'Grupo Menor de 1 año';
        } elseif (($dias_de_vida > 364) && ($dias_de_vida <= 2189 )) {
            $grupo['categoria'] = 'uno_a_seis';
            $grupo['descripcion'] = 'Grupo de 1 a 5 años';
        } elseif (($dias_de_vida > 2189) && ($dias_de_vida <= 3649 )) {
            $grupo['categoria'] = 'seis_a_diez';
            $grupo['descripcion'] = 'Grupo de 6 a 9 años';
        } elseif (($dias_de_vida > 3649) && ($dias_de_vida <= 7299 )) {
            $grupo['categoria'] = 'diez_a_veinte';
            $grupo['descripcion'] = 'Grupo de 10 a 19 años';
        } elseif (($dias_de_vida > 7299) && ($dias_de_vida <= 23724 )) {
            $grupo['categoria'] = 'veinte_a_sesentaycuatro';
            $grupo['descripcion'] = 'Grupo de 20 a 64 años';
        } else {
            $grupo['categoria'] = 'veinte_a_sesentaycuatro';
            $grupo['descripcion'] = 'Grupo de 20 a 64 años';
        }
        $this->grupo_etario = $grupo;

        return $this->grupo_etario;
    }

    // \brief Determina el estado del embarazo de una beneficiaria segun una fecha de practica
    // afecta corrercontroles/simular y sepuedeagregarcomprobante

    public static function comprobarEmbarazo($clavebeneficiario, $fechapractica, &$dato = '') {

        $periododelcomprobante = buscarPeriodo(str_replace("/", "-", $fechapractica));
        $embarazada = false;
        $fecha_comprobante_time = strtotime(ConvFechaComoDB($fechapractica));

        $periodo_buscado = $periododelcomprobante['id'];

        do {
            $sqlperiodo = "SELECT * from facturacion.periodo where id_periodo='$periodo_buscado'";
            $resultp = sql($sqlperiodo);
            if ($resultp->fields['tipo'] == 'H') {
                $fechaperiodo = split('/', $resultp->fields['periodo']);
                $fechaperiodo = $fechaperiodo[1] . $fechaperiodo[0];

                $sql = "SELECT activo,embarazoactual,fechadiagnosticoembarazo,fechaprobableparto,(fechaprobableparto - interval '9 month') as fechacomienzoembarazo from nacer.smiafiliadoshst where clavebeneficiario='$clavebeneficiario' and periodo='$fechaperiodo'";
                $result = sql($sql);
            } else {
                $sql = "SELECT activo, embarazoactual,fechadiagnosticoembarazo,fechaprobableparto, (fechaprobableparto - interval '9 month') as fechacomienzoembarazo from nacer.smiafiliados where clavebeneficiario='$clavebeneficiario'";
                $result = sql($sql);
            }

            if ((!$result->EOF) && (trim($result->fields['activo']) != 'S')) {
                break;
            }

            $fecha_comprobante_time = strtotime(ConvFechaComoDB($fechapractica));

            $fechadiagnosticoembarazo_time = strtotime($result->fields['fechadiagnosticoembarazo']);
            $fechaprobableparto_time = strtotime($result->fields['fechaprobableparto']);

            if (($result->fields['embarazoactual'] == 'S') && ( $fecha_comprobante_time >= $fechadiagnosticoembarazo_time)) { // && ( $fecha_comprobante_time <= $fechaprobableparto_time)) {
                $embarazada = TRUE;
                $dato['fechacomienzoembarazo'] = $result->fields['fechacomienzoembarazo'];
                $dato['fechaprobableparto'] = $result->fields['fechaprobableparto'];
                break;
            }

            $periodo_buscado++;
        } while ($resultp->fields['tipo'] == 'H' && !pasaron2meses($periodo_buscado, $periododelcomprobante['id']));

        return $embarazada;
    }

}

class BeneficiariosSMIColeccion {
    /* ! 
     *  \brief     Documentacion para metodo buscarPorClaveBeneficiario.
     *  \details   Busca el beneficiario correspondiente a la clave en el Padron Vigente de SMI.
     *  \author    Gustavo Fernandez
     *  \date      18/06/2014
     *  \pre       --
     *  \bug       --
     *  \warning   --
     *  \copyright GNU Public License.
     */

    public static function buscarPorClaveBeneficiario($clave) {
        $where = "clavebeneficiario='$clave'";
        $sql = BeneficiarioSmi::getSqlSelectWhere($where);
        $result = sql($sql);
        if (!$result->EOF) {
            $beneficiario_aux = new BeneficiarioSmi();
            $beneficiario_aux->construirResult($result);
        } else {
            $beneficiario_aux = null;
        }
        return $beneficiario_aux;
    }

    public static function calcularGrupoEtareo($fecha_nacimiento, $fecha_comprobante, $arr_dias_anios = null) {
        if ($arr_dias_anios['dias'] == '') {
            $dias_de_vida = GetCountDaysBetweenTwoDates($fecha_nacimiento, $fecha_comprobante);
        } else {
            $dias_de_vida = $arr_dias_anios['dias'];
        }
        if ($arr_dias_anios['anios'] == '') {
            $edad = calcularEdad($fecha_nacimiento, $fecha_comprobante);
        } else {
            $edad = $arr_dias_anios['anios'];
        }
        $grupo['edad'] = $edad;
        $grupo['dias_de_vida'] = $dias_de_vida;
        if (($dias_de_vida >= 0) && ($dias_de_vida <= 28)) {
            $grupo['categoria'] = 'neo';
            $grupo['descripcion'] = 'Grupo NeoNatal';
        } elseif (($dias_de_vida > 28) && ($dias_de_vida <= 364)) {
            $grupo['categoria'] = 'cero_a_uno';
            $grupo['descripcion'] = 'Grupo Menor de 1 año';
        } elseif (($dias_de_vida > 364) && ($dias_de_vida <= 2189 )) {
            $grupo['categoria'] = 'uno_a_seis';
            $grupo['descripcion'] = 'Grupo de 1 a 5 años';
        } elseif (($dias_de_vida > 2189) && ($dias_de_vida <= 3649 )) {
            $grupo['categoria'] = 'seis_a_diez';
            $grupo['descripcion'] = 'Grupo de 6 a 9 años';
        } elseif (($dias_de_vida > 3649) && ($dias_de_vida <= 7299 )) {
            $grupo['categoria'] = 'diez_a_veinte';
            $grupo['descripcion'] = 'Grupo de 10 a 19 años';
        } elseif (($dias_de_vida > 7299) && ($dias_de_vida <= 23724 )) {
            $grupo['categoria'] = 'veinte_a_sesentaycuatro';
            $grupo['descripcion'] = 'Grupo de 20 a 64 años';
        } else {
            $grupo['categoria'] = 'veinte_a_sesentaycuatro';
            $grupo['descripcion'] = 'Grupo de 20 a 64 años';
        }
        return $grupo;
    }

}

?>
