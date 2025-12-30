<?php
//include_once('consultasDB.php');
/**
 * 
 *
 * @version 1.107
 * @package entity
 */
class Smiefectores {

    private $cuie;
    private $nombreefector;
    private $sistema;
    private $domicilio;
    private $departamento;
    private $localidad;
    private $codPos;
    private $ciudad;
    private $referente;
    private $tel;
    private $tipoefector;
    private $codOrg;
    private $nivel;
    private $banco;
    private $nrocta;

    public function __construct($cuie = '') {
        if ($cuie != '') {
            $where = "cuie='$cuie'";
            $sql = Smiefectores::getSQlSelectWhere($where);
            $result = sql($sql);
            if (!$result->EOF) {
                $this->construirResult($result);
            }
        }
    }

    public function construirResult($result) {
        $this->cuie = $result->fields['cuie'];
        $this->nombreefector = $result->fields['nombreefector'];
        $this->sistema = $result->fields['sistema'];
        $this->domicilio = $result->fields['domicilio'];
        $this->departamento = $result->fields['departamento'];
        $this->localidad = $result->fields['localidad'];
        $this->codPos = $result->fields['cod_pos'];
        $this->ciudad = $result->fields['ciudad'];
        $this->referente = $result->fields['referente'];
        $this->tel = $result->fields['tel'];
        $this->tipoefector = $result->fields['tipoefector'];
        $this->codOrg = $result->fields['cod_org'];
        $this->nivel = $result->fields['nivel'];
        $this->banco = $result->fields['banco'];
        $this->nrocta = $result->fields['nrocta'];
    }

    /**
     * set value for cuie 
     *
     * type:text,size:2147483647,default:null,primary,unique
     *
     * @param mixed $cuie
     */
    public function setCuie($cuie) {
        $this->cuie = $cuie;
    }

    /**
     * get value for cuie 
     *
     * type:text,size:2147483647,default:null,primary,unique
     *
     * @return mixed
     */
    public function getCuie() {
        return $this->cuie;
    }

    /**
     * set value for nombreefector 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $nombreefector
     */
    public function setNombreefector($nombreefector) {
        $this->nombreefector = $nombreefector;
    }

    /**
     * get value for nombreefector 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getNombreefector() {
        return $this->nombreefector;
    }

    /**
     * set value for sistema 
     *
     * type:int4,size:10,default:3
     *
     * @param mixed $sistema
     */
    public function setSistema($sistema) {
        $this->sistema = $sistema;
    }

    /**
     * get value for sistema 
     *
     * type:int4,size:10,default:3
     *
     * @return mixed
     */
    public function getSistema() {
        return $this->sistema;
    }

    /**
     * set value for domicilio 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $domicilio
     */
    public function setDomicilio($domicilio) {
        $this->domicilio = $domicilio;
    }

    /**
     * get value for domicilio 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getDomicilio() {
        return $this->domicilio;
    }

    /**
     * set value for departamento 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $departamento
     */
    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    /**
     * get value for departamento 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getDepartamento() {
        return $this->departamento;
    }

    /**
     * set value for localidad 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $localidad
     */
    public function setLocalidad($localidad) {
        $this->localidad = $localidad;
    }

    /**
     * get value for localidad 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getLocalidad() {
        return $this->localidad;
    }

    /**
     * set value for cod_pos 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $codPos
     */
    public function setCodPos($codPos) {
        $this->codPos = $codPos;
    }

    /**
     * get value for cod_pos 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getCodPos() {
        return $this->codPos;
    }

    /**
     * set value for ciudad 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $ciudad
     */
    public function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
    }

    /**
     * get value for ciudad 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getCiudad() {
        return $this->ciudad;
    }

    /**
     * set value for referente 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $referente
     */
    public function setReferente($referente) {
        $this->referente = $referente;
    }

    /**
     * get value for referente 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getReferente() {
        return $this->referente;
    }

    /**
     * set value for tel 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $tel
     */
    public function setTel($tel) {
        $this->tel = $tel;
    }

    /**
     * get value for tel 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getTel() {
        return $this->tel;
    }

    /**
     * set value for tipoefector 
     *
     * type:varchar,size:4,default:null,nullable
     *
     * @param mixed $tipoefector
     */
    public function setTipoefector($tipoefector) {
        $this->tipoefector = $tipoefector;
    }

    /**
     * get value for tipoefector 
     *
     * type:varchar,size:4,default:null,nullable
     *
     * @return mixed
     */
    public function getTipoefector() {
        return $this->tipoefector;
    }

    /**
     * set value for cod_org 
     *
     * type:varchar,size:5,default:null,nullable
     *
     * @param mixed $codOrg
     */
    public function setCodOrg($codOrg) {
        $this->codOrg = $codOrg;
    }

    /**
     * get value for cod_org 
     *
     * type:varchar,size:5,default:null,nullable
     *
     * @return mixed
     */
    public function getCodOrg() {
        return $this->codOrg;
    }

    /**
     * set value for nivel 
     *
     * type:numeric,size:131089,default:null,nullable
     *
     * @param mixed $nivel
     */
    public function setNivel($nivel) {
        $this->nivel = $nivel;
    }

    /**
     * get value for nivel 
     *
     * type:numeric,size:131089,default:null,nullable
     *
     * @return mixed
     */
    public function getNivel() {
        return $this->nivel;
    }

    /**
     * set value for banco 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @param mixed $banco
     */
    public function setBanco($banco) {
        $this->banco = $banco;
    }

    /**
     * get value for banco 
     *
     * type:int4,size:10,default:null,nullable
     *
     * @return mixed
     */
    public function getBanco() {
        return $this->banco;
    }

    /**
     * set value for nrocta 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @param mixed $nrocta
     */
    public function setNrocta($nrocta) {
        $this->nrocta = $nrocta;
    }

    /**
     * get value for nrocta 
     *
     * type:varchar,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getNrocta() {
        return $this->nrocta;
    }

    /* descripcion => devuelve un string sql de la tabla factura 
     * parametros => $where: (string) condicion del where
     *               $select => (csv o array) campos del select. Opcional
     *               $order => (string) campo/s por los cuales ordenar el resultado. Opcional
     */

    public static function getSQlSelectWhere($where, $select = "", $order = "") {
        if ($select != "" && !is_null($select)) {
            if (is_array($select)) {
                $fields = "," . implode(",", $select);
            } else {
                $fields = $select;
            }
        } else {
            $fields = "*";
        }
        $sql = "SELECT " . $fields . "
                FROM facturacion.smiefectores
                WHERE " . $where . "";
        if ($order != "") {
            $sql .= " ORDER BY " . $order . " ";
        }

        return($sql);
    }

    public static function getSQlSelect() {
        $sql = "SELECT *
              FROM facturacion.smiefectores";

        return($sql);
    }

    public function tiposDeNomenclador() {
        $nomencladores = false;
        if (false && $this->cuie) {
            $sql_tipos = "select nom_basico,nom_cc_catastrofico,nom_perinatal_catastrofico,nom_perinatal_nocatastrofico,
                                 nom_remediar,nom_cc_nocatastrofico,nom_basico_2,nom_rondas,nom_talleres,nom_incluir,
                                 nom_ac,nom_hombres_20_a_64
                          from nacer.conv_nom cn
                          inner join nacer.efe_conv ec using (id_efe_conv)
                          where cuie='$this->cuie'
                            AND cn.activo='t'
                            AND ec.activo='t'";
            $res_tipos = sql($sql_tipos) or fin_pagina();
            if (!$res_tipos->EOF) {

                if ($res_tipos->fields['nom_basico'] == 't') {
                    $tipos_de_nomenclador['BASICO'] = 'Basico';
                }
                if ($res_tipos->fields['nom_basico_2'] == 't') {
                    $tipos_de_nomenclador['BASICO_2'] = 'Basico 2';
                }
                if ($res_tipos->fields['nom_cc_catastrofico'] == 't') {
                    //$tipos_de_nomenclador['CC_CATASTROFICO'] = 'CC Catastrofico';
                    $tipos_de_nomenclador['CARDIOPATIAS_CATASTROFICO'] = 'Cardiopatias Catastrofico';
                }
                if ($res_tipos->fields['nom_perinatal_catastrofico'] == 't') {
                    $tipos_de_nomenclador['PERINATAL_CATASTROFICO'] = 'Perinatal Catastrofico';
                }
                if ($res_tipos->fields['nom_cc_nocatastrofico'] == 't') {
                    //$tipos_de_nomenclador['CC_NOCATASTROFICO'] = 'CC No Catastrofico';
                    $tipos_de_nomenclador['CARDIOPATIAS_NO_CATASTROFICO'] = 'Cardiopatias No Catastrofico';
                }
                if ($res_tipos->fields['nom_perinatal_nocatastrofico'] == 't') {
                    $tipos_de_nomenclador['PERINATAL_NO_CATASTROFICO'] = 'Perinatal No Catastrofico';
                }
                if ($res_tipos->fields['nom_remediar'] == 't') {
                    $tipos_de_nomenclador['REMEDIAR'] = 'Remediar';
                }
                if ($res_tipos->fields['nom_rondas'] == 't') {
                    $tipos_de_nomenclador['RONDAS'] = 'Rondas';
                }
                if ($res_tipos->fields['nom_talleres'] == 't') {
                    $tipos_de_nomenclador['TALLERES'] = 'Talleres';
                }
                if ($res_tipos->fields['nom_incluir'] == 't') {
                    $tipos_de_nomenclador['INCLUIR'] = 'Incluir';
                }
                if ($res_tipos->fields['nom_ac'] == 't') {
                    $tipos_de_nomenclador['ANOMALIAS_CONGENITAS'] = 'Anomalias Congenitas';
                }
                if ($res_tipos->fields['nom_hombres_20_a_64'] == 't') {
                    $tipos_de_nomenclador['HOMBRES_20_A_64'] = 'Hombres 20 a 64';
                }
                $nomencladores = $tipos_de_nomenclador;
            }
        }
        return $nomencladores;
    }

    public function getArrayUTF8() {
        $datos['cuie'] = utf8_encode($this->cuie);
        $datos['nombreefector'] = utf8_encode($this->nombreefector);
        return $datos;
    }

    public function tiposDeNomencladorPorFecha($fechacomprobante) {
        $nomencladores = false;
        if (false && $this->cuie) {

            $sql_tipos = "SELECT cn.* 
                          FROM nacer.efe_conv n
                          INNER JOIN nacer.conv_nom cn USING(id_efe_conv)
                          INNER JOIN facturacion.nomenclador_detalle nd on (cn.id_nomenclador_detalle=nd.id_nomenclador_detalle)
                          WHERE n.cuie='$this->cuie'
                            AND '$fechacomprobante' BETWEEN fecha_desde AND fecha_hasta";
            $res_tipos = sql($sql_tipos);
            if (!$res_tipos->EOF) {

                if ($res_tipos->fields['nom_basico'] == 't') {
                    $tipos_de_nomenclador['BASICO'] = 'Basico';
                }
                if ($res_tipos->fields['nom_basico_2'] == 't') {
                    $tipos_de_nomenclador['BASICO_2'] = 'Basico 2';
                }
                if ($res_tipos->fields['nom_cc_catastrofico'] == 't') {
                    //$tipos_de_nomenclador['CC_CATASTROFICO'] = 'CC Catastrofico';
                    $tipos_de_nomenclador['CARDIOPATIAS_CATASTROFICO'] = 'Cardiopatias Catastrofico';
                }
                if ($res_tipos->fields['nom_perinatal_catastrofico'] == 't') {
                    $tipos_de_nomenclador['PERINATAL_CATASTROFICO'] = 'Perinatal Catastrofico';
                }
                if ($res_tipos->fields['nom_cc_nocatastrofico'] == 't') {
                    //$tipos_de_nomenclador['CC_NO_CATASTROFICO'] = 'CC No Catastrofico';
                    $tipos_de_nomenclador['CARDIOPATIAS_NO_CATASTROFICO'] = 'Cardiopatias No Catastrofico';
                }
                if ($res_tipos->fields['nom_perinatal_nocatastrofico'] == 't') {
                    $tipos_de_nomenclador['PERINATAL_NO_CATASTROFICO'] = 'Perinatal No Catastrofico';
                }
                if ($res_tipos->fields['nom_remediar'] == 't') {
                    $tipos_de_nomenclador['REMEDIAR'] = 'Remediar';
                }
                if ($res_tipos->fields['nom_rondas'] == 't') {
                    $tipos_de_nomenclador['RONDAS'] = 'Rondas';
                }
                if ($res_tipos->fields['nom_talleres'] == 't') {
                    $tipos_de_nomenclador['TALLERES'] = 'Talleres';
                }
                if ($res_tipos->fields['nom_incluir'] == 't') {
                    $tipos_de_nomenclador['INCLUIR'] = 'Incluir';
                }    
                if ($res_tipos->fields['nom_ac'] == 't') {
                    $tipos_de_nomenclador['ANOMALIAS_CONGENITAS'] = 'Anomalias Congenitas';                    
                }
                if ($res_tipos->fields['nom_hombres_20_a_64'] == 't') {
                    $tipos_de_nomenclador['HOMBRES_20_A_64'] = 'Hombres 20 a 64';
                }
                $nomencladores = $tipos_de_nomenclador;
            }
        }
        return $nomencladores;
    }

}
class SmiefectoresColeccion {

    public static function getSQlSelectLimit($where, $limit = 5) {
        $sql = "SELECT *  FROM facturacion.smiefectores";
        if (strlen($where) > 5) {
            $sql .=" WHERE $where";
        }
        $sql .=" LIMIT $limit";
        return $sql;
    }

    public static function getSQLEfectoresMunicipio($nroZona = "", $idMunicipio = "") {
        $where = "";
        if ($nroZona != "") {
            $where .= " AND efec.id_zona_sani='$nroZona' ";
        }
        if ($idMunicipio != "") {
            $where .= " AND mun.idmuni_provincial=$idMunicipio ";
        }
        $sql = "SELECT DISTINCT(efec.cuie), efec.nombreefector 
                FROM uad.municipios mun 
                JOIN facturacion.smiefectores efec ON mun.id_depto_nac=efec.departamento 
                                                   AND mun.id_municipio=efec.municipio
                WHERE 1=1 
                " . $where . "
                ORDER BY efec.nombreefector
                ";
        return $sql;
    }

    public static function getSQLZonasSanitarias($nroZonaSanitaria = "") {
        if ($nroZonaSanitaria != "") {
            $where = " WHERE id_zona_sani=" . $nroZonaSanitaria . " ";
        }
        $sql = "SELECT * 
                FROM nacer.zona_sani 
                " . $where . "
                ORDER BY nombre_zona";
        return $sql;
    }

    public static function getSQLEfectoresHabitualesInsc() {
        $sql = "SELECT smi.cuie,smi.nombreefector 
                FROM facturacion.smiefectores smi
                JOIN uad.efectores ef ON smi.cuie=ef.cuie
                WHERE smi.tipoefector NOT IN('EXT','ALD','TRN','LAB','BCO','ADM') 
                ORDER BY smi.nombreefector";
        return $sql;
    }

    /* $parametros => array con los campos para customizar la query
     *                |-> select: (array) campos a agregar en el select
     *                |-> where: (array) campos a agregar en el where
     */
    public static function getSQLEfectoresReporteCodigoPrestacional($parametros=null) {
        if ($parametros[where] != "" && !is_null($parametros[where])) {
            if (is_array($parametros[where])) {
                $where = " AND " . implode(" AND ", $parametros[where]);
            } else {
                $where = $parametros[where];
            }
        }
        $sql = "SELECT smi.cuie,smi.nombreefector 
                FROM facturacion.smiefectores smi
                LEFT JOIN uad.efectores ef ON smi.cuie=ef.cuie
                WHERE smi.tipoefector <> 'EXT' 
                ".$where."
                ORDER BY smi.nombreefector";
        return $sql;
    }

    #	Metodo Filtrar 		

    public static function Filtrar($where = '') {
        if (strlen($where) > 0) {
            $sql = Smiefectores::getSQlSelectWhere($where);
        }// else {
        //$sql = Expediente::getSQlSelect();
        //}

        $result = sql($sql);

        if (!$result->EOF) {
            $registro = new Smiefectores();
            $registro->construirResult($result);
        }

        return($registro);
    }

    /* ! 
     *  \brief     Documentacion para metodo FiltrarGrupo.
     *  \details   Filtra los efectores por grupos en arrays.
     *  \author    Pezzarini Pedro
     *  \date      28/04/2014
     *  \pre       --
     *  \bug       --
     *  \warning   --
     *  \copyright GNU Public License.
     */

    public function FiltrarGrupo($where = "True") {
        $sql = Smiefectores::getSQlSelectWhere($where);

        $result = sql($sql);
        $datos = array();

        if (!$result->EOF) {
            $registro = new Smiefectores();
            $registro->construirResult($result);
            $datos[] = $registro;
            $result->MoveNext();
        }

        return($datos);
    }

    public static function buscarPorNombreLike($nombreEfector) {
        $where = "nombreEfector ILIKE '%$nombreEfector%'";
        $sql = SmiefectoresColeccion::getSQlSelectLimit($where);
        $result = sql($sql);

        $efectores = array();

        while (!$result->EOF) {

            $registro = new Smiefectores();
            $registro->construirResult($result);
            $efectores[] = $registro->getArrayUTF8();

            $result->MoveNext();
        }
        return $efectores;
    }

    public static function buscarPorCuieLike($cuie) {
        $where = "cuie ILIKE '$cuie%'";
        $sql = SmiefectoresColeccion::getSQlSelectLimit($where);
        $result = sql($sql);

        $efectores = array();

        while (!$result->EOF) {

            $registro = new Smiefectores();
            $registro->construirResult($result);
            $efectores[] = $registro->getArrayUTF8();

            $result->MoveNext();
        }
        return $efectores;
    }

    public static function buscarPorCUIE($cuie) {
        $where = "cuie = '$cuie'";
        $sql = Smiefectores::getSQlSelectWhere($where);
        $result = sql($sql);
        if (!$result->EOF) {
            $efector = new Smiefectores();
            $efector->construirResult($result);
        } else {
            $efector = false;
        }
        return $efector;
    }

    public static function traeCuiesPorUsuario($idusuario) {
        $cuieses = false;
        $sql = "select distinct(s.cuie), nombreefector
            from  facturacion.smiefectores s
            inner join sistema.usu_efec ue on ue.cuie=s.cuie
            where id_usuario='$idusuario'";
        $result = sql($sql);
        if (!$result->EOF) {
            $cuieses = array();
            while (!$result->EOF) {
                array_push($cuieses, $result->fields['cuie']);
                $result->movenext();
            }
        }

        return $cuieses;
    }

    public static function buscarTodos() {
        $sql = Smiefectores::getSQlSelect();
        $result = sql($sql);

        $efectores = array();

        while (!$result->EOF) {

            $registro = new Smiefectores();
            $registro->construirResult($result);
            $efectores[] = $registro->getArrayUTF8();

            $result->MoveNext();
        }
        return $efectores;
    }
    
    public static function calcularVigencia($cuie, $fecha, $activa=false) {
        if($activa){
            $where .= " AND n.activo='t' ";
        }
        $sql_conv = "SELECT nd.id_nomenclador_detalle 
                     FROM nacer.efe_conv n
                     INNER JOIN nacer.conv_nom cn USING(id_efe_conv)
                     INNER JOIN facturacion.nomenclador_detalle nd on (cn.id_nomenclador_detalle=nd.id_nomenclador_detalle)
                     WHERE n.cuie='$cuie'
                       AND '$fecha' BETWEEN fecha_desde AND fecha_hasta
                       ".$where."
                    ";
        $id_nomenclador_detalle = sql($sql_conv) or die;

        return $id_nomenclador_detalle->fields['id_nomenclador_detalle'];
    }
    
    /* descripcion => devuelve el tipo de nomenclador que se debe usar para 
     *                el codigo prestacional elegido, con el beneficiario y 
     *                efector dado, en una vigencia en particular
     * parametros => array de parametros 
     *               |-> cuie : (string) efector sobre el cual estamos consultando el nomenclador
     *               |-> id_vigencia: el id de la vigencia correspondiente 
     *                                (de acuerdo a la fecha de la prestacion)
     *               |-> codigo: (string) codigo de la practica
     *               |-> diagnostico: (string) diagnostico de la practica
     *               |-> grupo_etario: (string) slug del grupo etario del beneficiario
     *               |-> sexo: (string) sexo del beneficiario
     */
    public static function getNomencladorCorrespondiente($parametros){
        $nomenclador = null;
        if(!class_exists('Nomenclador')){
            include_once(ROOT_DIR."/clases/Nomenclador.php");
        }
        $where = "id_nomenclador_detalle='$parametros[id_vigencia]' AND codigo='$parametros[codigo]' AND diagnostico='$parametros[diagnostico]'";
        $sql_tipos_nom = Nomenclador::getSQlSelectWhere($where,"DISTINCT(tipo_nomenclador)",1);
        $tipos_nom = sql($sql_tipos_nom);
        if($tipos_nom){
            $tipos_nomenclador = array();
            while(!$tipos_nom->EOF){
                $tipos_nomenclador[] = $tipos_nom->fields['tipo_nomenclador'];
                $tipos_nom->MoveNext();
            }
        }    
        if(count($tipos_nomenclador)>1){
            // en caso de que existan mas de un nomenclador con la misma practica
            if(in_array('BASICO',$tipos_nomenclador) && in_array('HOMBRES_20_A_64',$tipos_nomenclador)){
                //evaluar la edad y el sexo del beneficiario
                if($parametros[grupo_etario]=='veinte_a_sesentaycuatro' && $parametros[sexo]=='M'){
                    $nomenclador = 'HOMBRES_20_A_64';
                }else{
                    $nomenclador = 'BASICO';
                } 
            }            
        }else{
            // en caso de que exista solo un nomenclador con la practica
            $nomenclador = $tipos_nomenclador[0];
        }
        
        return $nomenclador;
        
    }

}

?>
