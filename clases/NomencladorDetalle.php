<?php

/**
 * 
 *
 * @version 1.107
 * @package entity
 */
class NomencladorDetalle {

    private $idNomencladorDetalle;
    private $descripcion;
    private $fechaDesde;
    private $fechaHasta;
    private $modoFacturacion;
    private $usuario;
    private $fechaModificacion;

    public function construirResult($result) {
        $this->idNomencladorDetalle = $result->fields['id_nomenclador_detalle'];
        $this->descripcion = $result->fields['descripcion'];
        $this->fechaDesde = $result->fields['fecha_desde'];
        $this->fechaHasta = $result->fields['fecha_hasta'];
        $this->modoFacturacion = $result->fields['modo_facturacion'];
        $this->usuario = $result->fields['usuario'];
        $this->fechaModificacion = $result->fields['fecha_modificacion'];
    }
    
    public function construirArray($result){
        $ret = array();
        $ret['idNomencladorDetalle'] = $result->fields['id_nomenclador_detalle'];
        $ret['descripcion'] = $result->fields['descripcion'];
        $ret['fechaDesde'] = $result->fields['fecha_desde'];
        $ret['fechaHasta'] = $result->fields['fecha_hasta'];
        $ret['modoFacturacion'] = $result->fields['modo_facturacion'];
        $ret['usuario'] = $result->fields['usuario'];
        $ret['fechaModificacion'] = $result->fields['fecha_modificacion'];
        return $ret;
    }

    /**
     * set value for id_nomenclador_detalle 
     *
     * type:serial,size:10,default:nextval('facturacion.nomenclador_detalle_id_nomenclador_detalle_seq'::regclass),primary,unique,autoincrement
     *
     * @param mixed $idNomencladorDetalle
     */
    public function setIdNomencladorDetalle($idNomencladorDetalle) {
        $this->idNomencladorDetalle = $idNomencladorDetalle;
    }

    /**
     * get value for id_nomenclador_detalle 
     *
     * type:serial,size:10,default:nextval('facturacion.nomenclador_detalle_id_nomenclador_detalle_seq'::regclass),primary,unique,autoincrement
     *
     * @return mixed
     */
    public function getIdNomencladorDetalle() {
        return $this->idNomencladorDetalle;
    }

    /**
     * set value for descripcion 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    /**
     * get value for descripcion 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * set value for fecha_desde 
     *
     * type:date,size:13,default:null
     *
     * @param mixed $fechaDesde
     */
    public function setFechaDesde($fechaDesde) {
        $this->fechaDesde = $fechaDesde;
    }

    /**
     * get value for fecha_desde 
     *
     * type:date,size:13,default:null
     *
     * @return mixed
     */
    public function getFechaDesde() {
        return $this->fechaDesde;
    }

    /**
     * set value for fecha_hasta 
     *
     * type:date,size:13,default:null
     *
     * @param mixed $fechaHasta
     */
    public function setFechaHasta($fechaHasta) {
        $this->fechaHasta = $fechaHasta;
    }

    /**
     * get value for fecha_hasta 
     *
     * type:date,size:13,default:null
     *
     * @return mixed
     */
    public function getFechaHasta() {
        return $this->fechaHasta;
    }

    /**
     * set value for modo_facturacion 
     *
     * type:bpchar,size:1,default:1,nullable
     *
     * @param mixed $modoFacturacion
     */
    public function setModoFacturacion($modoFacturacion) {
        $this->modoFacturacion = $modoFacturacion;
    }

    /**
     * get value for modo_facturacion 
     *
     * type:bpchar,size:1,default:1,nullable
     *
     * @return mixed
     */
    public function getModoFacturacion() {
        return $this->modoFacturacion;
    }

    /**
     * set value for usuario 
     *
     * type:varchar,size:20,default:null,nullable
     *
     * @param mixed $usuario
     */
    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    /**
     * get value for usuario 
     *
     * type:varchar,size:20,default:null,nullable
     *
     * @return mixed
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * set value for fecha_modificacion 
     *
     * type:timestamp,size:29,default:null,nullable
     *
     * @param mixed $fechaModificacion
     */
    public function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }

    /**
     * get value for fecha_modificacion 
     *
     * type:timestamp,size:29,default:null,nullable
     *
     * @return mixed
     */
    public function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    public static function getSQlSelectWhere($where) {

        $sql = "
			SELECT *
			  FROM facturacion.nomenclador_detalle
			  WHERE " . $where . "";

        return($sql);
    }
    
    public function save(){
        if($this->getIdNomencladorDetalle()==""){
            //insert
            $sql = "INSERT INTO facturacion.nomenclador_detalle(descripcion,fecha_desde,fecha_hasta,modo_facturacion,usuario)
                    VALUES('".$this->getDescripcion()."','".$this->getFechaDesde()."','".$this->getFechaHasta()."',
                           '".$this->getModoFacturacion()."','".$this->getUsuario()."') 
                    RETURNING id_nomenclador_detalle";
        }else{
            //update
            $sql = "UPDATE facturacion.nomenclador_detalle 
                    SET descripcion='".$this->getDescripcion()."',
                        fecha_desde='".$this->getFechaDesde()."',
                        fecha_hasta='".$this->getFechaHasta()."',
                        modo_facturacion='".$this->getModoFacturacion()."',
                        usuario='".$this->getUsuario()."',
                        fecha_modificacion='".$this->getFechaModificacion()."'
                    WHERE id_nomenclador_detalle='".$this->getIdNomencladorDetalle()."'";
            //query log
            $sql_log = "INSERT INTO facturacion.log_nomenclador_detalle(id_nomenclador_detalle,descripcion,fecha_desde,fecha_hasta,modo_facturacion,usuario,fecha_modificacion)
                        SELECT id_nomenclador_detalle,descripcion,fecha_desde,fecha_hasta,modo_facturacion,usuario,fecha_modificacion 
                        FROM facturacion.nomenclador_detalle 
                        WHERE id_nomenclador_detalle='".$this->getIdNomencladorDetalle()."'";
            sql($sql_log);
        }
        $res = sql($sql);
        if($res){
            if($this->getIdNomencladorDetalle()==""){
                $this->setIdNomencladorDetalle($res->fields['id_nomenclador_detalle']);
            }
            return true;
        }else{
            return false;
        }
    }
    
    /* descripcion => devuelve un resultSet de los tipos de nomenclador
     *                asociados a una vigencia en particular
     * parametros => $vigencia: integer, es el id_nomenclador_detalle
     */
    public static function getTiposNomencladorAsociados($vigencia){
        $sql = "SELECT * 
                FROM facturacion.nomenclador_estado 
                WHERE id_nomenclador_detalle=$vigencia 
                ORDER BY tipo_nomenclador";
        $result = sql($sql);
        return($result);
    }
    
    /* descripcion => devuelve un resultSet de las vigencias
     * parametros => $params: array con parametros para la query
     *                  [select] => (csv o array) campos del select
     *                  [where] => (string) condicion del where
     *                  [limit] => (integer) cantidad de registros a devolver
     */
    public static function getVigencias($params=""){
        $select = " * ";
        if($params!=""){
            if($params[select]!="" || !is_null($params[select])){
                if(is_array($params[select])){
                    $select = implode(",",$params[select]);
                }else{
                    $select = $params[select];
                }
            }
            if($params[where]!=""){
                $where = " WHERE ".$params[where]." ";
            }
            if($params[limit]!=""){
                $limit = " LIMIT ".$params[limit]." ";  
            }
        }
        $sql = "SELECT ".$select." 
                FROM facturacion.nomenclador_detalle nd 
                ".$where."
                ORDER BY nd.id_nomenclador_detalle DESC 
                ".$limit."
                ";
        $result = sql($sql);
        return($result);
    }
    
    /* verifica la existencia de una vigencia en un rango de fechas dado
     * valor de retorno: true o false
     */
    public static function existeVigenciaSolapada($fechaDesde,$fechaHasta,$idNomencladorDetalle=""){
        if($idNomencladorDetalle!=""){
            $where = " AND id_nomenclador_detalle<>$idNomencladorDetalle ";
        }
        $sql = "SELECT COUNT(*) AS total  
                FROM facturacion.nomenclador_detalle
                WHERE (fecha_desde,fecha_hasta) 
                       OVERLAPS
                      (DATE '$fechaDesde', DATE '$fechaHasta') 
                  ".$where."
                ";
        $result = sql($sql);
        if($result->fields['total']>0){
            return true;
        }else{
            return false;
        }
    }

}

?>