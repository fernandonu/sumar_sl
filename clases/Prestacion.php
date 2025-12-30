<?php

class Prestacion {

    private $idPrestacion;
    private $idComprobante;
    private $idNomenclador;
    private $cantidad;
    private $precioPrestacion;
    private $idAnexo;
    private $peso;
    private $tensionArterial;
    private $prestacionid;
    private $comprobante;
    private $debito;
    private $nomenclador;
    private $pagoDiferenciado;

    public function construirResult($result) {
        $this->idComprobante = $result->fields['id_comprobante'];
        $this->idPrestacion = $result->fields['id_prestacion'];
        $this->precioPrestacion = empty($result->fields['precio_prestacion']) ? 0 : $result->fields['precio_prestacion'];
        $this->idNomenclador = $result->fields['id_nomenclador'];
        $this->cantidad = $result->fields['cantidad'];
        $this->idAnexo = $result->fields['id_anexo'];
        $this->peso = $result->fields['peso'];
        $this->tensionArterial = $result->fields['tension_arterial'];
        $this->prestacionid = $result->fields['prestacionid'];
        $this->pagoDiferenciado = $result->fields['pago_diferenciado'];
    }

    /**
     * set value for id_prestacion 
     *
     * type:serial,size:10,default:nextval('facturacion.prestacion_id_prestacion_seq'::regclass),primary,unique,autoincrement
     *
     * @param mixed $idPrestacion
     */
    public function setIdPrestacion($idPrestacion) {
        $this->idPrestacion = $idPrestacion;
    }

    /**
     * get value for id_prestacion 
     *
     * type:serial,size:10,default:nextval('facturacion.prestacion_id_prestacion_seq'::regclass),primary,unique,autoincrement
     *
     * @return mixed
     */
    public function getIdPrestacion() {
        return $this->idPrestacion;
    }

    /**
     * set value for id_comprobante 
     *
     * type:int4,size:10,default:null,index
     *
     * @param mixed $idComprobante
     */
    public function setIdComprobante($idComprobante) {
        $this->idComprobante = $idComprobante;
    }

    /**
     * get value for id_comprobante 
     *
     * type:int4,size:10,default:null,index
     *
     * @return mixed
     */
    public function getIdComprobante() {
        return $this->idComprobante;
    }

    /**
     * set value for id_nomenclador 
     *
     * type:int4,size:10,default:null,index
     *
     * @param mixed $idNomenclador
     */
    public function setIdNomenclador($idNomenclador) {
        $this->idNomenclador = $idNomenclador;
    }

    /**
     * get value for id_nomenclador 
     *
     * type:int4,size:10,default:null,index
     *
     * @return mixed
     */
    public function getIdNomenclador() {
        return $this->idNomenclador;
    }

    public function getNomenclador() {
        if (!$this->nomenclador) {
            $nomenclador = Nomenclador::buscarNomencladorPorId($this->idNomenclador);
            $this->nomenclador = $nomenclador;
        }
        return $this->nomenclador;
    }

    /**
     * set value for cantidad 
     *
     * type:int4,size:10,default:1,nullable
     *
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    /**
     * get value for cantidad 
     *
     * type:int4,size:10,default:1,nullable
     *
     * @return mixed
     */
    public function getCantidad() {
        return $this->cantidad;
    }

    /**
     * set value for precio_prestacion 
     *
     * type:numeric,size:30,default:0,nullable
     *
     * @param mixed $precioPrestacion
     */
    public function setPrecioPrestacion($precioPrestacion) {
        $this->precioPrestacion = $precioPrestacion;
    }

    /**
     * get value for precio_prestacion 
     *
     * type:numeric,size:30,default:0,nullable
     *
     * @return mixed
     */
    public function getPrecioPrestacion() {
        return $this->precioPrestacion;
    }

    /**
     * set value for id_anexo 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @param mixed $idAnexo
     */
    public function setIdAnexo($idAnexo) {
        $this->idAnexo = $idAnexo;
    }

    /**
     * get value for id_anexo 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @return mixed
     */
    public function getIdAnexo() {
        return $this->idAnexo;
    }

    /**
     * set value for peso 
     *
     * type:numeric,size:7,default:null,nullable
     *
     * @param mixed $peso
     */
    public function setPeso($peso) {
        $this->peso = $peso;
    }

    /**
     * get value for peso 
     *
     * type:numeric,size:7,default:null,nullable
     *
     * @return mixed
     */
    public function getPeso() {
        return $this->peso;
    }

    /**
     * set value for tension_arterial 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $tensionArterial
     */
    public function setTensionArterial($tensionArterial) {
        $this->tensionArterial = $tensionArterial;
    }

    /**
     * get value for tension_arterial 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getTensionArterial() {
        return $this->tensionArterial;
    }

    /**
     * set value for prestacionid 
     *
     * type:int8,size:19,default:null,nullable
     *
     * @param mixed $prestacionid
     */
    public function setPrestacionid($prestacionid) {
        $this->prestacionid = $prestacionid;
    }

    /**
     * get value for prestacionid 
     *
     * type:int8,size:19,default:null,nullable
     *
     * @return mixed
     */
    public function getPrestacionid() {
        return $this->prestacionid;
    }
    
    public function setPagoDiferenciado($pagoDiferenciado){
        $this->pagoDiferenciado = $pagoDiferenciado;
    }
    
    public function getPagoDiferenciado(){
        return $this->pagoDiferenciado;
    }

    public function getSQLInsert() {
        $sql = "INSERT INTO facturacion.prestacion (id_comprobante, id_nomenclador,cantidad,precio_prestacion)
                    VALUES ('$this->idComprobante',
                            '$this->idNomenclador',
                            '$this->cantidad',
                            '".(empty($this->precioPrestacion) ? 0 : $this->precioPrestacion)."')
                    RETURNING id_prestacion";
        return($sql);
    }

    # Documentacion para metodo getSQlUpdate

    public function getSQlUpdate() {
        $set = array();
        $sql = "UPDATE facturacion.prestacion set ";
        !is_null($this->cantidad) ? $set[] = " cantidad='".$this->cantidad."' " : false;
        !is_null($this->idNomenclador) ? $set[] = " id_nomenclador='".$this->idNomenclador . "' " : false;
        !is_null($this->precioPrestacion) ? $set[] =  "precio_prestacion='".$this->precioPrestacion."' " : false;
        !is_null($this->idComprobante) ? $set[] = " id_comprobante='".$this->idComprobante."' " : false;
        !is_null($this->pagoDiferenciado) ? $set[] = " pago_diferenciado='".$this->pagoDiferenciado."' " : false;
        $sql.= implode(',',$set);
        $sql.="WHERE id_prestacion='$this->idPrestacion' ";
        $sql.="RETURNING id_prestacion";
        return($sql);
    }

    # Documentacion para metodo getSQlDelete

    public function getSQlDelete() {
        $sql = '';
        return($sql);
    }

    public function guardarPrestacion() {
        if ($this->idPrestacion) {
            $sql = $this->getSQLUpdate();
        } else {
            $sql = $this->getSQlInsert();
        }        
        $result = sql($sql);
        return $result->fields['id_prestacion'];
    }
    
    /* Crea una nueva prestacion con los mismos
    datos que la original, a excepcion del id */
    public function clonarPrestacion(){
        $sql = "INSERT INTO facturacion.prestacion(id_comprobante,id_nomenclador,cantidad,precio_prestacion,id_anexo,peso,tension_arterial,prestacionid,archivo,fecha_proceso) 
                SELECT id_comprobante,id_nomenclador,cantidad,precio_prestacion,id_anexo,peso,tension_arterial,prestacionid,archivo,fecha_proceso 
                FROM facturacion.prestacion WHERE id_prestacion=".$this->getIdPrestacion()." 
                RETURNING id_prestacion";
        $result = sql($sql);
        return $result->fields['id_prestacion'];
    }

    public function getGrupoEtario() {
        $nomenclador = $this->getNomenclador();
        $comprobante = $this->getComprobante();
        $beneficiario = $comprobante->getBeneficiarioUAD();
        $grupo_etareo = $beneficiario->getGrupoEtareo($comprobante->getFechaComprobante());
        $embarazo = $beneficiario->getEmbarazado($comprobante->getFechaComprobante());
        if ($embarazo) {
            if ($nomenclador->getPrecioSegunGrupo($embarazada) > 0) {
                //$datos['precio'] = $nomenclador->getPrecioSegunGrupo($embarazada);
                //  $datos['grupo_precio'] = 'embarazada';
                $datos = 'embarazada';
            } else {
                // $datos['precio'] = $nomenclador->getPrecioSegunGrupo($grupo_etareo);
                // $datos['grupo_precio'] = $grupo_etareo;
                $datos = $grupo_etareo;
            }
        } else {
            //$datos['precio'] = $nomenclador->getPrecioSegunGrupo($grupo_etareo);
            //$datos['grupo_precio'] = $grupo_etareo;
            $datos = $grupo_etareo;
        }
        return $datos;
    }

    #	Metodo Automata 		

    public function Automata($where) {
        $sql = Prestacion::getSqlSelectWhere($where);
        $result = sql($sql);
        $this->construirResult($result);
    }

    #	Metodo getSQlSelectWhere 		

    public static function getSQlSelectWhere($where, $fields = "*") {

        $sql = "SELECT $fields
                FROM facturacion.prestacion
                WHERE " . $where . "";

        return($sql);
    }

    public static function getSQlSelect() {
        $sql = "
			SELECT *
			  FROM facturacion.prestacion";

        return($sql);
    }

    public function getComprobante() {
        if (!$this->comprobante) {
            $comprobante = Comprobante::getComprobantePorId($this->idComprobante);
            $this->comprobante = $comprobante;
        }
        return $this->comprobante;
    }

    public function getTotal() {
        return ($this->getCantidad() * $this->getPrecioPrestacion());
    }

    public function getDebito() {
        if (is_null($this->debito)) {
            $debito = Debito::getDebitoPorPrestacion($this->idPrestacion);
            $this->debito = $debito;
        }
        return $this->debito;
    }

    public function estaDebitada() {
        return DebitoColeccion::practicaDebitada($this->idPrestacion);
    }

    public function estaDebitadaVieja() {
        return DebitoColeccion::practicaDebitadaVieja($this->idComprobante);
    }

    public static function getSQlSelectWhereConNomenclador($where, $fields = "*") {

        $sql = "SELECT $fields
                FROM facturacion.prestacion
                INNER JOIN facturacion.nomenclador using (id_nomenclador)
                WHERE " . $where . "";

        return($sql);
    }

}

/**
 * 
 */
class PrestacionColeccion {

    var $registro = array();
    var $coleccionregistros = '';

    ### SETTERS
    # Documentacion para el metodo getBeneficiarios 		

    public function getPrestaciones() {
        return($this->coleccionregistros);
    }

    #	Metodo Filtrar 		

    public static function Filtrar($where = '') {
        if (strlen($where) > 0) {
            $sql = Prestacion::getSqlSelectWhere($where);
        } else {
            $sql = Prestacion::getSQlSelect();
        }

        $result = sql($sql);

        $coleccionregistros = array();

        while (!$result->EOF) {

            $registro = new Prestacion();
            $registro->construirResult($result);

            $coleccionregistros[] = $registro;
            $result->MoveNext();
        }

        return($coleccionregistros);
    }

    public static function buscarPrestacionPorId($id_prestacion) {
        $where = "id_prestacion='$id_prestacion'";
        $sql = Prestacion::getSqlSelectWhere($where);
        $result = sql($sql);
        if (!$result->EOF) {
            $registro = new Prestacion();
            $registro->construirResult($result);
        }
        return $registro;
    }

    public static function buscarPrestacionPorComprobante($id_comprobante) {
        $prestacion_aux = false;
        $where = "id_comprobante='$id_comprobante'";
        $sql = Prestacion::getSQlSelectWhere($where);
        $result = sql($sql);
        if (!$result->EOF) {
            $prestacion_aux = new Prestacion();
            $prestacion_aux->construirResult($result);
        }
        return $prestacion_aux;
    }

    public static function practicasSinDebitoEnExpediente($cuie, $expediente, $filtro = '') {
        if ($filtro != '') {
            $where = " AND clavebeneficiario ='$filtro'";
        }
        $sql = "SELECT p.* FROM facturacion.prestacion p                
                INNER JOIN facturacion.comprobante c ON(p.id_comprobante=c.id_comprobante)
                INNER JOIN facturacion.factura f ON (c.id_factura=f.id_factura)
                INNER JOIN facturacion.expediente e using (nro_exp)
                LEFT JOIN facturacion.debito d ON(p.id_comprobante=d.id_comprobante AND p.id_prestacion=d.id_prestacion)
                WHERE f.cuie='$cuie' AND f.nro_exp='$expediente' AND c.id_factura is not null AND id_debito is NULL
                AND f.estado='C' AND f.ctrl='S' AND e.estado<>'C'" . $where;

        $result = sql($sql);

        $coleccionregistros = array();

        while (!$result->EOF) {

            $registro = new Prestacion();
            $registro->construirResult($result);

            $coleccionregistros[] = $registro;
            $result->MoveNext();
        }

        return $coleccionregistros;
    }

    public static function practicasConDebitoEnExpediente($cuie, $expediente, $filtro = '') {
        if ($filtro != '') {
            $where = " AND clavebeneficiario ='$filtro'";
        }
        $sql = "SELECT p.* FROM facturacion.prestacion p                
                INNER JOIN facturacion.comprobante c ON(p.id_comprobante=c.id_comprobante)
                INNER JOIN facturacion.factura f ON (c.id_factura=f.id_factura)
                INNER JOIN facturacion.expediente e using (nro_exp)
                LEFT JOIN facturacion.debito d ON(p.id_comprobante=d.id_comprobante AND p.id_prestacion=d.id_prestacion)
                WHERE f.cuie='$cuie' AND f.nro_exp='$expediente' AND c.id_factura is not null AND id_debito IS NOT NULL
                AND d.id_motivo_d='82'
                AND f.estado='C' AND f.ctrl='S' AND e.estado<>'C'" . $where;

        $result = sql($sql);

        $coleccionregistros = array();

        while (!$result->EOF) {

            $registro = new Prestacion();
            $registro->construirResult($result);

            $coleccionregistros[] = $registro;
            $result->MoveNext();
        }

        return $coleccionregistros;
    }

    /* devuelve las prestaciones que estan en condiciones de ser debitadas
     * El expte que se pasa como parametro debe ser uno cerrado
     */

    public static function getPrestacionesParaDebitoRetroactivo($cuie, $expte, $nroFac = "", $claveDoc = "") {
        if ($expte != "") {
            $where .= " AND fac.nro_exp='$expte' ";
        }
        if ($nroFac != "") {
            $where .= " AND fac.nro_fact_offline='$nroFac' ";
        }
        if ($claveDoc != "") {
            $where .= " AND ( com.clavebeneficiario='$claveDoc' 
                            OR 
                              (ben.numero_doc='$claveDoc' AND ben.clase_documento_benef='P') 
                            ) ";
        }
        $sql = "SELECT DISTINCT(pre.id_prestacion), fac.nro_exp, fac.nro_fact_offline, 
                       com.fecha_comprobante, pre.cantidad, pre.precio_prestacion, 
                       nom.codigo, nom.diagnostico, ben.apellido_benef, ben.nombre_benef,
                       ben.apellido_benef_otro, ben.nombre_benef_otro
                FROM facturacion.factura fac 
                JOIN facturacion.comprobante com ON fac.id_factura=com.id_factura
                JOIN facturacion.prestacion pre ON com.id_comprobante=pre.id_comprobante
                JOIN facturacion.nomenclador nom ON pre.id_nomenclador=nom.id_nomenclador 
                JOIN uad.beneficiarios ben ON com.clavebeneficiario=ben.clave_beneficiario 
                LEFT JOIN facturacion.debito deb ON pre.id_prestacion=deb.id_prestacion 
                LEFT JOIN facturacion.debito_retroactivo deb_ret ON pre.id_prestacion=deb_ret.id_prestacion 
                WHERE deb.id_debito IS NULL 
                  AND deb_ret.id IS NULL 
                  AND fac.cuie='$cuie' 
                  " . $where . "
                ORDER BY fecha_comprobante DESC
                ";
        //echo $sql;
        $res = sql($sql);
        return $res;
    }

    /* actualiza el id de comprobante (con $idCompNuevo) 
     * de las prestaciones que pertenecen a un comprobante 
     * con id $idCompViejo. Se puede especificar las prestaciones
     * que no se desea actualizar en el array $prestExluidas
     */

    public static function actualizarComprobante($idCompNuevo, $idCompViejo, $prestExcluidas = "") {
        if ($prestExcluidas != "") {
            $where .= " AND id_prestacion NOT IN(" . implode(",", $prestExcluidas) . ") ";
        }
        $sql = "UPDATE facturacion.prestacion SET id_comprobante=" . $idCompNuevo . " 
                WHERE id_comprobante=" . $idCompViejo . " 
                 " . $where . "
               ";
        sql($sql);
    }
    
    public static function armarMatrizTrazadoraPPAC($conceptos,$result){
        $m = array();
        if($result){ 
            while(!$result->EOF){
                $m[0][$result->fields['descripcion_abreviada']] = $result->fields['cantidad'];
                $result->MoveNext();
            }
        }
        return $m;
    }
    
    /* devuelve un slug para cada trazadora. Se utiliza internamente para
     * el reporte de prestaciones y doms. Facilita la agrupacion de diver-
     * sas trazadoras en una sola generica (PPAC por ejemplo)     * 
     */
    public static function getSlugGenericoTrazadora($trazadora){
        switch($trazadora){
            case "EMBARAZO-ALTO-RIESGO":
            case "PRE-QUIRURGICO":
            case "PRE-QUIRURGICO-NC":
            case "PREMATUREZ":
                $trazadora = "PPAC";
                break;
            default:
                break;
        }
        return $trazadora;
    }
    
    public static function getSQLDatosTrazadora($datos_prestacion){
        $a = (object)$datos_prestacion; // convierto el array a obj para su tratamiento
        $join = " JOIN facturacion.smiefectores ef ON trz.cuie=ef.cuie";
        $where ;
        $limit = "  ";
        $cod_nomenclador = trim($a->cod_nomenclador);
        switch($a->trazadora){
           
            
            case "ADOLESCENTE":
                $campo_tabla = "codnomenclador";
                if ($cod_nomenclador != "") {
                    $where .= " AND (replace(trz." . $campo_tabla . ",' ','')='" . str_replace(' ', '', $a->cod_nomenclador) . "' OR trz." . $campo_tabla . " IS NULL OR trz." . $campo_tabla . "='')";
                } else {
                    $where .= " AND (trz." . $campo_tabla . " IS NULL OR trz." . $campo_tabla . "='') ";
                }
                $where .= " AND trz.num_doc='$a->nro_doc' ";
                $where .= " AND trz.fecha_control='$a->fecha_prestacion' ";
                $where .= " AND trz.cuie='$a->cuie' ";
                break;
            case "ADULTO":
                $campo_tabla = "codnomenclador";
                if ($cod_nomenclador != "") {
                    $where .= " AND (replace(trz." . $campo_tabla . ",' ','')='" . str_replace(' ', '', $a->cod_nomenclador) . "' OR trz." . $campo_tabla . " IS NULL OR trz." . $campo_tabla . "='')";
                } else {
                    $where .= " AND (trz." . $campo_tabla . " IS NULL OR trz." . $campo_tabla . "='') ";
                }
                $where .= " AND trz.num_doc='$a->nro_doc' ";
                $where .= " AND trz.fecha_control='$a->fecha_prestacion' ";
                $where .= " AND trz.cuie='$a->cuie' ";
                break;
            case "CLASIFICACION":
                $where .= " AND trz.num_doc='$a->nro_doc' ";
                $where .= " AND trz.fecha_control='$a->fecha_prestacion' ";
                $where .= " AND trz.cuie='$a->cuie' ";
                break;
            case "EMB":
                $where .= " AND trz.num_doc='$a->nro_doc' ";
                $where .= " AND trz.fecha_control='$a->fecha_prestacion' ";
                $where .= " AND trz.cuie='$a->cuie' ";
                break;

            case "HOMBRES_OPCION1":
                $select .=" ,fn.codigo, fn.diagnostico ";
                 $join .=" JOIN facturacion.nomenclador fn on fn.id_nomenclador= trz.id_nomenclador";
                $where .= " AND trz.id_prestacion=$a->id_prestacion";
                $limit = "";
                break;
            case "HOMBRES_OPCION2":
                 $select .=" ,fn.codigo, fn.diagnostico ";
                 $join .=" JOIN facturacion.nomenclador fn on fn.id_nomenclador= trz.id_nomenclador";
                $where .= " AND trz.id_prestacion=$a->id_prestacion";
                $limit = "";
                break;
            case "HOMBRES_OPCION3":
                 $select .=" ,fn.codigo, fn.diagnostico ";
                 $join .=" JOIN facturacion.nomenclador fn on fn.id_nomenclador= trz.id_nomenclador";
                $where .= " AND trz.id_prestacion=$a->id_prestacion";
                $limit = "";
                break;
            case "HOMBRES_OPCION4":
                 $select .=" ,fn.codigo, fn.diagnostico ";
                 $join .=" JOIN facturacion.nomenclador fn on fn.id_nomenclador= trz.id_nomenclador";
                $where .= " AND trz.id_prestacion=$a->id_prestacion";
                $limit = "";
                break;
             case "HIPOACUSIA":
                $select .=" ,ef.cuie ,fn.codigo, fn.diagnostico ";
                        $join = " JOIN facturacion.prestacion fp on fp.id_prestacion= trz.id_prestacion";
                        $join .=" JOIN facturacion.nomenclador fn on fn.id_nomenclador= trz.id_nomenclador";
                        $join .=" JOIN facturacion.comprobante fc on fc.id_comprobante = fp.id_comprobante";
                        $join .=" JOIN  facturacion.smiefectores ef on ef.cuie= fc.cuie";
                        $where .=" AND trz.id_prestacion=$a->id_prestacion";
                        $where .=" AND fc.clavebeneficiario='$a->clave_benef'";
                        $limit = "";
                break;

            case "INMU":
                $select .= " ,vd.descripcion AS desc_dosis, vd.*, pr.descripcion AS desc_presentacion, 
                             CASE trz.id_grupo_riesgo WHEN -1 THEN '' ELSE gr.descripcion END AS desc_grupo_riesgo ";
                $join .= " JOIN inmunizacion.vacunas_dosis vd ON trz.id_vacuna_dosis=vd.id_vacuna_dosis 
                             JOIN inmunizacion.presentaciones pr ON trz.id_presentacion=pr.id_presentacion 
                             LEFT JOIN inmunizacion.grupos_riesgos gr ON trz.id_grupo_riesgo=gr.id_grupo_riesgo ";
                $where .= " AND trz.id_prestacion='$a->id_prestacion' ";
                $where .= " AND trz.cuie='$a->cuie' ";
                break;

            case "LABORATORIOREDES":
                // $select.=", pm.apellido_medico "; 
                //$join =" JOIN facturacion.prestacion pres on  pres.prestacionid=trz.id_prestacion ";
                $join = " JOIN facturacion.smiefectores ef ON trz.efectorlab=ef.cuie";
                //$join .= " left join planillas.medicos pm on pm.dni_medico = CAST(trz.idbioquimico AS character varying)";
                $where .= " AND trz.id_prestacion=$a->id_prestacion";
                // $where .=   " AND pres.prestacionid= (select pres.prestacionid from facturacion.prestacion  pres where pres.id_prestacion=$a->id_prestacion)";
                $limit = "";

                break;
            case "SANGRE-OCULTA":
            case "LABORATORIOS":
                $select .=" ,ef.cuie ,fn.codigo, fn.diagnostico ";
                $join = " JOIN facturacion.prestacion fp on fp.id_prestacion= trz.id_prestacion";
                $join .=" JOIN facturacion.nomenclador fn on fn.id_nomenclador= trz.id_nomenclador";
                $join .=" JOIN facturacion.comprobante fc on fc.id_comprobante = fp.id_comprobante";
                $join .=" JOIN  facturacion.smiefectores ef on ef.cuie= fc.cuie";
                $where .=" AND trz.id_prestacion=$a->id_prestacion";
                $where .=" AND fc.clavebeneficiario='$a->clave_benef'";
                $limit = "";

                break;

            case "NINO":
            case "NINO_PESO":
                $campo_tabla = "cod_nomenclador";
                $select .=" ,esn.zp_e, esn.zt_e, esn.zimc_e";
                $join .=" LEFT JOIN estado_nutricional.anthros esn ON trz.id_anthro= esn.id_anthro";
                if ($cod_nomenclador != "") {
                    $where .= " AND (replace(trz." . $campo_tabla . ",' ','')='" . str_replace(' ', '', $a->cod_nomenclador) . "' OR trz." . $campo_tabla . " IS NULL OR trz." . $campo_tabla . "='')";
                } else {
                    $where .= " AND (trz." . $campo_tabla . " IS NULL OR trz." . $campo_tabla . "='') ";
                }
                $where .= " AND trz.num_doc='$a->nro_doc' ";
                $where .= " AND trz.fecha_control='$a->fecha_prestacion' ";
                $where .= " AND trz.cuie='$a->cuie' ";
                $where .= " AND trz.id_prestacion_sigep='$a->id_prestacion' ";
                break;
            case "PARTO":
                $where .= " AND trz.num_doc='$a->nro_doc' ";
                $where .= " AND trz.fecha_parto='$a->fecha_prestacion' ";
                $where .= " AND trz.cuie='$a->cuie' ";
                break;
            case "EMBARAZO-ALTO-RIESGO":
            case "PRE-QUIRURGICO":
            case "PRE-QUIRURGICO-NC":
            case "PREMATUREZ":
            case "PPAC":
                unset($join, $limit);
                $select = " ,c.fecha_comprobante, ci.descripcion_abreviada, fa.fecha_alta ";
                $join = " JOIN facturacion.comprobante c ON trz.id_comprobante=c.id_comprobante 
                          JOIN facturacion.smiefectores ef ON c.cuie=ef.cuie 
                          JOIN nomenclador.conceptos_internacion ci ON trz.id_concepto=ci.id_concepto 
                          LEFT JOIN facturacion.fecha_de_alta fa ON trz.id_comprobante=fa.id_comprobante 
                                                                 AND trz.id_prestacion=fa.id_prestacion
                        ";
                $where .= " AND trz.id_prestacion='$a->id_prestacion' AND c.clavebeneficiario='$a->clave_benef' ";
                break;
            case "SEGUIMIENTO":
                $where .= " AND trz.num_doc='$a->nro_doc' ";
                $where .= " AND trz.fecha_control='$a->fecha_prestacion' ";
                break;
            case "TAL":
                $campo_tabla = "codnomenclador";
                if ($cod_nomenclador != "") {
                    $where .= " AND (replace(trz." . $campo_tabla . ",' ','')='" . str_replace(' ', '', $a->cod_nomenclador) . "' OR trz." . $campo_tabla . " IS NULL OR trz." . $campo_tabla . "='')";
                } else {
                    $where .= " AND (trz." . $campo_tabla . " IS NULL OR trz." . $campo_tabla . "='') ";
                }
                $where .= " AND trz.num_doc='$a->nro_doc' ";
                $where .= " AND trz.fecha_control='$a->fecha_prestacion' ";
                $where .= " AND trz.cuie='$a->cuie' ";
                break;
            case "HOTEL":
            case "PASAJE":
                $select .=", nom.codigo as codigo, nom.diagnostico as diagn ";
                $join.="JOIN facturacion.prestacion prestacion ON trz.id_prestacion=prestacion.id_prestacion
                       JOIN facturacion.nomenclador nom ON prestacion.id_nomenclador=nom.id_nomenclador ";
                $where .= " AND trz.id_prestacion='$a->id_prestacion' ";

                break;
            case "MEDICAMENTO":
                $select = " , medicamento.nombre as medicamento,
                        pedidos.fecha_prescripcion,pedidos.matricula_medico_deriva||'-'||pedidos.medico_deriva as medico,
                        pedidos.motivo_derivacion,pedidos.cuie_efector,pedidos.id_pedido,prest.fecha_proceso
                        ";
                $join = "JOIN incluir.item_medicamento item_medi on item_medi.id_item=trz.id_item
                        JOIN incluir.pedidos pedidos on pedidos.id_pedido=trz.id_pedido
                        JOIN facturacion.smiefectores ef ON pedidos.cuie_efector=ef.cuie
                        JOIN incluir.medicamento medicamento on medicamento.id_medicamento=item_medi.id_medicamento
                        JOIN facturacion.prestacion prest on prest.id_prestacion=trz.id_prestacion";
                $where .= " AND trz.id_prestacion='$a->id_prestacion' ";
                break;
            case "PANAL":
                $select = " ,tipo.nombre||'-'||tamano.nombre as panal,                        
                        pedidos.fecha_prescripcion,pedidos.matricula_medico_deriva||'-'||pedidos.medico_deriva as medico,
                        pedidos.motivo_derivacion,pedidos.cuie_efector,pedidos.id_pedido,prest.fecha_proceso
                        ";
                $join = "JOIN incluir.item_panal item_panal on item_panal.id_item=trz.id_item
                        JOIN incluir.panal panales on panales.id_panal=item_panal.id_panal
                        JOIN incluir.tamano_panal tamano on tamano.id_tamano_panal=panales.id_tamano_panal
                        JOIN incluir.tipo_panal tipo on tipo.id_tipo_panal=panales.id_tipo_panal
                        JOIN incluir.pedidos pedidos on pedidos.id_pedido=trz.id_pedido
                        JOIN facturacion.smiefectores ef ON pedidos.cuie_efector=ef.cuie
                        JOIN facturacion.prestacion prest on prest.id_prestacion=trz.id_prestacion";
                $where .= " AND trz.id_prestacion='$a->id_prestacion'";
                break;
            
            case "DATO_REPORTABLE_14"://mamografia
            case "DATO_REPORTABLE_11"://mamografia
            case "DATO_REPORTABLE_10"://mamografia
            case "DATO_REPORTABLE_9"://mamografia
            case "ODONTOLOGIA":
            case"MAMOGRAFIA_12":
            case "VDRL":
            case "RETINOPATIA":
                $select .=" ,ef.cuie ,fn.codigo, fn.diagnostico ";
                $join = " JOIN facturacion.prestacion fp on fp.id_prestacion= trz.id_prestacion";
                $join .=" JOIN facturacion.nomenclador fn on fn.id_nomenclador= trz.id_nomenclador";
                $join .=" JOIN facturacion.comprobante fc on fc.id_comprobante = fp.id_comprobante";
                $join .=" JOIN  facturacion.smiefectores ef on ef.cuie= fc.cuie";
                $where .=" AND trz.id_prestacion=$a->id_prestacion";
                $where .=" AND fc.clavebeneficiario='$a->clave_benef'";
                $limit = "";

                break;
            
            default:
                break;
        }
        
     $tabla = getNombreTablaTrazadora($a->trazadora);    
 
      $sql = "SELECT trz.*, ef.nombreefector AS efector".$select." 
      FROM ".$tabla." trz 
                ".$join."
                WHERE 1=1 ".$where." 
                ORDER BY 1 DESC
                ".$limit." 
               "; 
      
        return $sql; 
    }

    /* $parametros => array con los campos del $_REQUEST
     *                |-> fecha_desde: fecha de inicio, con formato dd/mm/yyyy
     *                |-> fecha_hasta: fecha de fin, con formato dd/mm/yyyy
     *                |-> efector: cuie del efector
     *                |-> cuie: cuie del efector cuando debe traer todos los efectores por practica
     *                |-> cod_practica: codigo+diagnostico de la prestacion
     */
    public static function getConteoPrestacionesAgrupadas($parametros) {
        if ($parametros['fecha_desde'] != "" && $parametros['fecha_hasta']) {
            $where .= " AND c.fecha_comprobante BETWEEN '" . ConvFechaComoDB($parametros['fecha_desde']) . "' AND '" . ConvFechaComoDB($parametros['fecha_hasta']) . "' ";
        }
        if ($parametros['tipo'] == "efector") {
            if ($parametros['efector'] != "") {
                $select = " e.cuie, ";
                $where .= " AND e.cuie='" . $parametros['efector'] . "' ";
                $join=" JOIN facturacion.smiefectores e ON e.cuie=c.cuie";
                $group = " e.cuie, ";
            }
            if ($parametros['efector'] == "") {
                //$select = " c.cuie, e.efector,  ";
                $select = " e.cuie, e.nombreefector,  ";
                //$join=" JOIN uad.efectores e ON e.cuie=c.cuie";
                $join=" JOIN facturacion.smiefectores e ON e.cuie=c.cuie";
              //  $group = " c.cuie ,e.efector , ";
                $group = " e.cuie ,e.nombreefector , ";
            }
        }elseif($parametros['tipo'] == "municipio"){
            if ($parametros['sug_municipios'] != "") {
                $where .= " AND b.municipio='" . $parametros['sug_municipios'] . "' ";
                $group = " b.municipio, ";
                $join=" JOIN uad.beneficiarios b ON c.clavebeneficiario=b.clave_beneficiario";
            }            
        }

        if ($parametros['cod_practica'] != "") {
            $where .= " AND n.codigo||' '||n.diagnostico='" . $parametros['cod_practica'] . "' ";
        }
        $query = "SELECT " . $select . " n.codigo,n.diagnostico,count(distinct(p.id_prestacion)) as  cnt_prest, 
                         count(distinct(c.clavebeneficiario)) as cnt_benef,	
                         count(distinct(case when c.id_factura is null then p.id_prestacion else null end)) as cnt_prest_no_fact,
                         count(distinct(case when c.id_factura is not null then p.id_prestacion else null end)) as cnt_prest_fact,
                         count(distinct(case when c.id_factura is null then c.clavebeneficiario else null end)) as ben_dist_no_fact,	
                         count(distinct(case when c.id_factura is not null then c.clavebeneficiario else null end)) as ben_dist_fact,
                         count(distinct(case when d.id_debito is not null then d.id_debito else null end)) as cnt_prest_fact_dbt,
                         count(distinct(case when d.id_debito is not null then c.clavebeneficiario else null end)) as ben_dist_fact_dbt
                  FROM facturacion.comprobante c
                  JOIN facturacion.prestacion p ON c.id_comprobante=p.id_comprobante 
                  JOIN facturacion.nomenclador n ON p.id_nomenclador=n.id_nomenclador 
                  LEFT JOIN facturacion.debito d ON p.id_prestacion=d.id_prestacion"
                  .$join."
                  WHERE c.marca=0 
                    " . $where . "
                  GROUP BY " . $group . " n.codigo,n.diagnostico
                  ORDER BY n.codigo,n.diagnostico,count(p.id_prestacion) DESC
                 ";   
        $result = sql($query);
        return $result;
    }

    /* $parametros => array con los campos del $_REQUEST
     *                |-> fecha_desde: fecha de inicio, con formato dd/mm/yyyy
     *                |-> fecha_hasta: fecha de fin, con formato dd/mm/yyyy
     *                |-> efector: cuie del efector
     *                |-> cod_practica: codigo+diagnostico de la prestacion
     */
    public static function getNominalPrestacionesAgrupadas($parametros) {
        if ($parametros['fecha_desde'] != "" && $parametros['fecha_hasta']) {
            $where .= " AND c.fecha_comprobante BETWEEN '" . ConvFechaComoDB($parametros['fecha_desde']) . "' AND '" . ConvFechaComoDB($parametros['fecha_hasta']) . "' ";
        }
        if($parametros['zona_sanitaria'] != ""){
            $where .= " AND e.id_zona_sani='" . $parametros['zona_sanitaria'] . "' ";
        }
        //if ($parametros['tipo'] == "efector") {
            if ($parametros['efector'] != "") {
                $select = " c.cuie, ";
                $where .= " AND c.cuie='" . $parametros['efector'] . "' ";
                //$group = " c.cuie, ";
            }
        //}
        if (!is_null($parametros['cod_practica']) && trim($parametros['cod_practica']) != "") {
            $where .= " AND n.codigo||' '||n.diagnostico='" . $parametros['cod_practica'] . "' ";
        }
        if ($parametros['condicion_prestacion'] != "") {
            if($parametros['condicion_prestacion']=="F"){
                $where .= " AND c.id_factura is not null ";
            }
            if($parametros['condicion_prestacion']=="FD"){
                $join = " LEFT JOIN facturacion.debito d ON p.id_prestacion=d.id_prestacion ";
                $where .= " AND d.id_debito is not null ";
            }
            if($parametros['condicion_prestacion']=="NF"){
                $where .= " AND c.id_factura is null ";
            }
            $where .= " AND n.codigo||' '||n.diagnostico='" . $parametros['cod_practica'] . "' ";
        }
        $query = "SELECT n.codigo,n.diagnostico, c.fecha_comprobante, c.cuie, e.nombreefector, 
                         b.apellido_benef, b.apellido_benef_otro, b.nombre_benef, b.fecha_nacimiento_benef, numero_doc, 
                         age(c.fecha_comprobante,b.fecha_nacimiento_benef) edad, 
                         date_part('year',age(c.fecha_comprobante,b.fecha_nacimiento_benef))*365 + date_part('month',age(c.fecha_comprobante,b.fecha_nacimiento_benef))*30 + date_part('day',age(c.fecha_comprobante,b.fecha_nacimiento_benef))dias,
                         date_part('year',age(c.fecha_comprobante,b.fecha_nacimiento_benef)) anios                         
                  FROM facturacion.comprobante c
                  JOIN facturacion.prestacion p ON c.id_comprobante=p.id_comprobante 
                  JOIN facturacion.nomenclador n ON p.id_nomenclador=n.id_nomenclador 
                  JOIN uad.beneficiarios b ON c.clavebeneficiario=b.clave_beneficiario 
                  JOIN facturacion.smiefectores e ON c.cuie=e.cuie 
                  ".$join."
                  WHERE c.marca=0 
                  ".$where."
                  ORDER BY e.nombreefector ASC, b.apellido_benef ASC, b.nombre_benef ASC
                 ";  
        $result = sql($query);
        return $result;
    }    
    
    public static function getPrestacionesAgrupadasXProgramas($parametros,$limit=999999,$offset=0) {
        
        if ($parametros['fecha_desde'] != "" && $parametros['fecha_hasta']) {
            $where .= " AND c.fecha_comprobante BETWEEN '" . ConvFechaComoDB($parametros['fecha_desde']) . "' AND '" . ConvFechaComoDB($parametros['fecha_hasta']) . "' ";
        }
         if ($parametros['sug_programa']!=''){
             $where .= " AND c.tipo_nomenclador='".$parametros['sug_programa']."'";
         }

        if ($parametros['efector'] != "") {
            $select = " c.cuie, ";
            $where .= " AND c.cuie='" . $parametros['efector'] . "' ";
        }
        if ($parametros['cod_practica'] != "") {
            $where .= " AND n.codigo||' '||n.diagnostico='" . $parametros['cod_practica'] . "' ";
        }
        $limit = " LIMIT $limit OFFSET $offset ";
        $query = "SELECT  distinct(p.id_prestacion), " . $select . "u.apellido_benef,u.nombre_benef,u.fecha_nacimiento_benef,u.tipo_documento,u.numero_doc,
                                          u.clave_beneficiario,u.semanas_embarazo,i.clave_numero,c.fecha_comprobante,t.fecha_ingreso,t.fecha_salida,
                                          t.hora_salida,trunc(p.precio_prestacion,2) as precio_prestacion,n.codigo,n.diagnostico,
                                          c.id_factura,f.nro_exp,f.nro_fact_offline
                  FROM facturacion.comprobante c
                  JOIN facturacion.prestacion p ON c.id_comprobante=p.id_comprobante 
                  JOIN facturacion.nomenclador n ON p.id_nomenclador=n.id_nomenclador  
                  LEFT JOIN uad.beneficiarios u ON c.clavebeneficiario=u.clave_beneficiario
                  LEFT JOIN trazadoras.trazadora_h_p t ON p.id_prestacion=t.id_prestacion
                  LEFT JOIN incluir.beneficiario_incluir i ON u.numero_doc=i.nro_documento
                  LEFT JOIN facturacion.factura f ON c.id_factura=f.id_factura
                  WHERE c.id_comprobante<>0
                    " . $where . "
                    ORDER BY c.fecha_comprobante   
                    ". $limit;    
                  
        $result = sql($query);
        return $result;
    }
    

    /* $parametros => array con los campos del $_REQUEST
     *                |-> fecha_desde: fecha de inicio, con formato dd/mm/yyyy
     *                |-> fecha_hasta: fecha de fin, con formato dd/mm/yyyy
     *                |-> efector: cuie del efector
     *                |-> cuie: cuie del efector en el caso de listado completo(por codigo) y seleccionas un efecor
     *                |-> cod_practica: codigo+diagnostico de la prestacion
     *                |-> fact: permite indicar sobre qué se hace la consulta de acuerdo a valores:
     *                          0 indica 'no facturados'
                                1 indica 'facturados'
                                2 indica 'facturados debitados'
     *                |-> focus: permite indicar sobre qué se hace la consulta de acuerdo a valores:
     *                          0 (o vacio) indica prestaciones
                                1 indica personas
     */
    public static function getDetallePrestacionesAgrupadas($parametros){
        if($parametros['fecha_desde']!="" && $parametros['fecha_hasta']){
            $where .= " AND c.fecha_comprobante BETWEEN '".ConvFechaComoDB($parametros['fecha_desde'])."' AND '".ConvFechaComoDB($parametros['fecha_hasta'])."' ";
        }
        if($parametros['efector']!=""){
            $where .= " AND c.cuie='".$parametros['efector']."' ";
        }
        if($parametros['cuie']!=""){
             $where .= " AND c.cuie='".$parametros['cuie']."' ";
        }
        if($parametros['cod_practica']!=""){
            $where .= " AND n.codigo||' '||n.diagnostico='".$parametros['cod_practica']."' ";
        }
        if($parametros['fact']==0){
            $where .= " AND c.id_factura is null ";
        }
        if($parametros['fact']==1){
            $where .= " AND c.id_factura is not null ";
        }
        if($parametros['fact']==2){
            $where .= " AND c.id_factura is not null ";
            $join .= " JOIN facturacion.debito d ON p.id_prestacion=d.id_prestacion ";
        }
        $campo = "p.id_prestacion";
        if($parametros['focus']==0){
            $campo = "p.id_prestacion";
        }
        if($parametros['focus']==1){
            $campo = "c.clavebeneficiario";
        }
        $query = "SELECT distinct(".$campo."),
                         array_agg(distinct(c.fecha_comprobante)) fecha_comprobante, 
                         array_agg(distinct(b.fecha_nacimiento_benef)) fecha_nacimiento_benef, 
                         array_agg(age(c.fecha_comprobante,b.fecha_nacimiento_benef)) edad, 
                         array_agg(date_part('year',age(c.fecha_comprobante,b.fecha_nacimiento_benef))*365 + date_part('day',age(c.fecha_comprobante,b.fecha_nacimiento_benef))) dias,
                         array_agg(date_part('year',age(c.fecha_comprobante,b.fecha_nacimiento_benef))) anios
                  FROM facturacion.comprobante c
                  JOIN facturacion.prestacion p ON c.id_comprobante=p.id_comprobante 
                  JOIN facturacion.nomenclador n ON p.id_nomenclador=n.id_nomenclador                   
                  LEFT JOIN uad.beneficiarios b ON c.clavebeneficiario=b.clave_beneficiario 
                    ".$join."
                  WHERE c.marca=0 
                    ".$where."
                  GROUP BY ".$campo."      
                  ORDER BY edad ASC
                 ";
        $result = sql($query);
        return $result;
    }
    
    public static function BuscarPracticasDelBeneficiario($clavebeneficiario, $codigo) {
        $registros = false;
        $codigodesarmado = desarmarCodigoPrestacion($codigo);

        $query = "SELECT id_prestacion,prestacionid,cantidad,id_nomenclador,precio_prestacion,id_comprobante, fecha_comprobante::date
                        FROM facturacion.prestacion p
  			INNER JOIN facturacion.comprobante using (id_comprobante)
  			INNER JOIN facturacion.nomenclador using (id_nomenclador)
                        WHERE clavebeneficiario = '$clavebeneficiario'
                        AND codigo = '" . $codigodesarmado['categoria_padre'] . " " . $codigodesarmado['profesional'] . "'
                        AND diagnostico = '" . $codigodesarmado['codigo'] . "'
                        ORDER BY fecha_comprobante ASC";

        $res_rel = sql($query);

        while (!$res_rel->EOF) {
            $registro = new Prestacion();
            $registro->construirResult($res_rel);
            $registros[] = $registro;
            $res_rel->MoveNext();
        }

        return $registros;
    }
    
    
    public static function procesarPagosDiferenciados($params){
        $id_factura = $params['id_factura'];
        
        $query = "SELECT c.clavebeneficiario, c.fecha_comprobante, c.cuie, c.grupo_etario, 
                         p.id_prestacion, p.precio_prestacion, p.id_nomenclador,
                         n.codigo||' '||n.diagnostico AS cod_practica, b.fecha_nacimiento_benef, b.sexo, 
                         age(c.fecha_comprobante,b.fecha_nacimiento_benef) edad, 
                         date_part('year',age(c.fecha_comprobante,b.fecha_nacimiento_benef))*365 + date_part('month',age(c.fecha_comprobante,b.fecha_nacimiento_benef))*30 + date_part('day',age(c.fecha_comprobante,b.fecha_nacimiento_benef))dias,
                         date_part('year',age(c.fecha_comprobante,b.fecha_nacimiento_benef)) anios,  
                         nd.neo, nd.cero_a_uno, nd.uno_a_seis, nd.seis_a_diez, nd.diez_a_veinte, 
                         nd.veinte_a_sesentaycuatro, nd.embarazada, nd.cond_pack, nd.funcion_pago, nd.id_nom_dif
                  FROM facturacion.comprobante c
                  JOIN facturacion.prestacion p ON c.id_comprobante=p.id_comprobante
                  JOIN facturacion.nomenclador_diferenciado nd ON p.id_nomenclador=nd.id_nomenclador   
                  JOIN facturacion.nomenclador n ON p.id_nomenclador=n.id_nomenclador 
                  JOIN uad.beneficiarios b ON c.clavebeneficiario=b.clave_beneficiario

                  WHERE c.id_factura='$id_factura' 
                    AND nd.habilitado=TRUE
                  ";
        $result = sql($query);
        if($result && $result->RecordCount()>0){
            while(!$result->EOF){
                //defino valores q van a usarse
                $arr_fecha = explode("-",$result->fields['fecha_comprobante']);
                $update_precio = false;
                //encontrar el grupo etario
                $fecha_nac = $result->fields['fecha_nacimiento_benef'];
                $fecha_comp = $result->fields['fecha_comprobante'];
                $arr_edad = array('dias'=>$result->fields['dias'],'anios'=>$result->fields['anios']);
                $arr_etareo = calcularGrupoEtareo($fecha_nac,$fecha_comp,$arr_edad);
                $grupo_etario = $arr_etareo['categoria'];
                if($result->fields['sexo']=='F'){
                    //verificar si esta embarazada
                    if(beneficiarioEmbarazadoUAD($result->fields['clavebeneficiario'],$fecha_comp)){
                        $grupo_etario = 'embarazada';
                    }
                }
                
                // 1- obtener los packs
                $res_pack = Nomenclador::getPacksNomencladorDiferenciado($result->fields['id_nom_dif'],''); //array("where"=>array("habilitado=TRUE")));
                if($res_pack && $res_pack->RecordCount()>0){
                    while(!$res_pack->EOF){
                        $cond = $res_pack->fields['cond_pack'];
                        $grupos_pack = explode(',',$res_pack->fields['grupos_etarios']);
                        // 2- ver si corresponde aplicar el pack por grupo etario
                        if(in_array($grupo_etario,$grupos_pack)){
                            // 3- obtener las prestaciones del pack
                            $res_prac = Nomenclador::getPracticasPackNomencladorDiferenciado($res_pack->fields['id_pack']);
                            $arr_cond = array();
                            // 4- verificar que tenga alguna de esas prest en el periodo q corresponda
                            while(!$res_prac->EOF){
                                $arr_cond[] = " n.codigo||' '||n.diagnostico='".$res_prac->fields['cod_practica']."' ";
                                $res_prac->MoveNext();
                            }
                            $arr_fecha = explode("-",$result->fields['fecha_comprobante']);
                            $dia_inicio = "01";
                            $dia_fin = ultimoDia($arr_fecha[1],$arr_fecha[0]); //ultimoDia($mes, $ano) <- esta funciones_misiones.php
                            $desde = $arr_fecha[0]."-".$arr_fecha[1]."-".$dia_inicio;
                            $hasta = $arr_fecha[0]."-".$arr_fecha[1]."-".$dia_fin;

                            $where  = " c.id_factura='$id_factura' ";
                            $where .= " AND c.cuie='".$result->fields['cuie']."' ";
                            $where .= " AND c.marca='0' ";
                            $where .= " AND c.clavebeneficiario='".$result->fields['clavebeneficiario']."' ";
                            $where .= " AND c.fecha_comprobante BETWEEN '$desde' AND '$hasta' ";
                            $where .= " AND (".implode(" OR ",$arr_cond).") ";

                            $q_cond = array("select"=>"DISTINCT(n.codigo||' '||n.diagnostico)","where"=>$where);
                            $q_comp = Comprobante::getSQlSelectWherePracticaConNomenclador($q_cond);

                            $r_comp = sql($q_comp);
                            if($r_comp && $r_comp->RecordCount()>0){
                                if($cond=='OR'){
                                    $update_precio = true;
                                }elseif($cond=='AND' && $r_comp->RecordCount()==count($arr_cond)){
                                    $update_precio = true;
                                }
                            }
                        }
                        $res_pack->MoveNext();
                    } 
                    
                }
                // verificar si el nomenclador tiene fn asociada y correrla/s
                if($result->fields['funcion_pago']=="t" && $update_precio==false){
                    // correr funciones
                    if( $result->fields['cod_practica']=='CT C005 W78'){
                        $flag = Controles::menosDe13DiasDeGestacion($result->fields['id_prestacion'],$result->fields['clavebeneficiario']);
                        if($flag)
                            $update_precio = true;
                    }
                    
                    //prox etapa
                    
                    $arr_fecha = explode("-",$result->fields['fecha_comprobante']);
                    if($arr_fecha[1]!='01'){
                        $mes_p = (int)$arr_fecha[1] - 1;
                        $ano_p = $arr_fecha[0]; 
                    }else{
                        $mes_p = "12";
                        $ano_p = (int)$arr_fecha[0] - 1;
                    }
                    $dia_inicio = "01";
                    $dia_fin = ultimoDia($mes_p,$ano_p); 
                    $desde = $ano_p."-".$mes_p."-".$dia_inicio;
                    $hasta = $ano_p."-".$mes_p."-".$dia_fin;
                    $where  = " c.id_factura IS NOT NULL ";
                    $where .= " AND c.cuie='".$result->fields['cuie']."' ";
                    $where .= " AND c.marca='0' ";
                    $where .= " AND c.clavebeneficiario='".$result->fields['clavebeneficiario']."' ";
                    //$where .= " AND c.fecha_comprobante BETWEEN '$desde' AND '$hasta' ";
                    //select NOW(), NOW() - (3 || ' month')::INTERVAL, 
                    //date_trunc('month',(NOW() - (3 || ' month')::INTERVAL))
                    //(date_trunc('MONTH', NOW() - (3 || ' month')::INTERVAL) + INTERVAL '1 MONTH - 1 day')
                    
                    if( $result->fields['cod_practica']=='CT C006 W78'){
                        $where_comp = $where;
                        $where_comp .= " AND c.fecha_comprobante BETWEEN '$desde' AND '$hasta' ";
                        $where_comp .= " AND n.codigo||' '||n.diagnostico in('CT C006 W78','CT C005 W78') ";
                        $q_comp = Comprobante::getSQlSelectWherePracticaConNomenclador(array("where"=>$where_comp));
                        $r_comp = sql($q_comp);
                        if($r_comp && $r_comp->RecordCount()>0){
                            $update_precio = true;
                        }
                    }
                    
                    if( $result->fields['cod_practica']=='CT C001 A97' && ($grupo_etario=='cero_a_uno' || $grupo_etario=='uno_a_seis')){
                        $where_comp = $where;
                        $where_comp .= " AND n.codigo||' '||n.diagnostico in('CT C001 A97') ";
                        
                        if($grupo_etario=='cero_a_uno'){
                            $where_comp .= " AND c.fecha_comprobante BETWEEN '$desde' AND '$hasta' ";
                        }
                        if($grupo_etario=='uno_a_seis'){
                            //armo el rango de fecha para el trimestre anterior
                            if(!in_array($arr_fecha[1], array('01','02','03'))){
                                //si la prestacion no es en los meses 01 o 02 o 03 se trabaja en el mismo año
                                $mes_inicio_t = "0".(int)$arr_fecha[1] - 3;
                                $m = (int)$arr_fecha[1] - 1;
                                $mes_fin_t = strlen($m)==1 ? "0".$m : $m;
                                $ano_inicio_t = $ano_fin_t = $arr_fecha[0]; 
                            }else{
                                //se arma el rango de fecha considerando el año anterior
                                $ano_inicio_t = $ano_fin_t = (int)$arr_fecha[0] - 1;
                                if($arr_fecha[1]=='01'){
                                    $mes_inicio_t = "10";
                                    $mes_fin_t = "12";
                                }
                                if($arr_fecha[1]=='02'){
                                    $mes_inicio_t = "11";
                                    $mes_fin_t = "01";
                                    $ano_fin_t = $arr_fecha[0];
                                }
                                if($arr_fecha[1]=='03'){
                                    $mes_inicio_t = "12";
                                    $mes_fin_t = "02";
                                    $ano_fin_t = $arr_fecha[0];
                                }
                            }
                            $dia_inicio_t = "01";
                            $dia_fin_t = ultimoDia($mes_fin_t,$ano_fin_t); 
                            $desde_t = $ano_inicio_t."-".$mes_inicio_t."-".$dia_inicio_t;
                            $hasta_t = $ano_fin_t."-".$mes_fin_t."-".$dia_fin_t;
                    
                            $where_comp .= " AND c.fecha_comprobante BETWEEN '$desde_t' AND '$hasta_t' ";
                        }
                        
                        $q_comp = Comprobante::getSQlSelectWherePracticaConNomenclador(array("where"=>$where_comp));
                        $r_comp = sql($q_comp);
                        if($r_comp && $r_comp->RecordCount()>0){
                            $update_precio = true;
                        }
                    }                    
                    
                }
                // 4- si la bandera esta en true, actualizar el precio segun grupo etareo correspondiente
                if($update_precio){
                    $precio_new = $result->fields[$grupo_etario];
                    $prest = new Prestacion();
                    $prest->setIdPrestacion($result->fields['id_prestacion']);
                    $prest->setPrecioPrestacion($precio_new);
                    $prest->setPagoDiferenciado('t');
                    $prest->guardarPrestacion();
                }
                
                $result->MoveNext();
            }
        }
        
    }
    

}

?>
