<?php

/**
 * 
 *
 * @version 1.107
 * @package entity
 */
class Nomenclador {

    private $id_nomenclador;
    private $codigo;
    private $grupo;
    private $subgrupo;
    private $descripcion;
    private $precio;
    private $tipoNomenclador;
    private $idNomencladorDetalle;
    private $categoria;
    private $diagnostico;
    private $neo;
    private $ceroAUno;
    private $unoASeis;
    private $seisADiez;
    private $veinteASesentaycuatro;
    private $f;
    private $m;
    private $embarazada;
    private $diezAVeinte;
    private $habilitado;
    private $pagoDiferenciado;

    public function construirResult($result) {
        $this->id_nomenclador = $result->fields['id_nomenclador'];
        $this->codigo = $result->fields['codigo'];
        $this->grupo = $result->fields['grupo'];
        $this->subgrupo = $result->fields['subgrupo'];
        $this->descripcion = $result->fields['descripcion'];
        $this->precio = $result->fields['precio'];
        $this->tipoNomenclador = $result->fields['tipo_nomenclador'];
        $this->idNomencladorDetalle = $result->fields['id_nomenclador_detalle'];
        $this->categoria = $result->fields['categoria'];
        $this->diagnostico = $result->fields['diagnostico'];
        $this->neo = $result->fields['neo'];
        $this->ceroAUno = $result->fields['cero_a_uno'];
        $this->unoASeis = $result->fields['uno_a_seis'];
        $this->seisADiez = $result->fields['seis_a_diez'];
        $this->veinteASesentaycuatro = $result->fields['veinte_a_sesentaycuatro'];
        $this->f = $result->fields['f'];
        $this->m = $result->fields['m'];
        $this->embarazada = $result->fields['embarazada'];
        $this->diezAVeinte = $result->fields['diez_a_veinte'];
        $this->habilitado = $result->fields['habilitado'];
        $this->pagoDiferenciado = $result->fields['pago_diferenciado'];
    }

    /**
     * set value for id_nomenclador 
     *
     * type:serial,size:10,default:nextval('facturacion.nomenclador_id_nomenclador_seq'::regclass),primary,unique,autoincrement
     *
     * @param mixed $idNomenclador
     */
    public function setIdNomenclador($idNomenclador) {
        $this->id_nomenclador = $idNomenclador;
    }

    /**
     * get value for id_nomenclador 
     *
     * type:serial,size:10,default:nextval('facturacion.nomenclador_id_nomenclador_seq'::regclass),primary,unique,autoincrement
     *
     * @return mixed
     */
    public function getIdNomenclador() {
        return $this->id_nomenclador;
    }

    /**
     * set value for codigo 
     *
     * type:text,size:2147483647,default:null,index,nullable
     *
     * @param mixed $codigo
     */
    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    /**
     * get value for codigo 
     *
     * type:text,size:2147483647,default:null,index,nullable
     *
     * @return mixed
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * set value for grupo 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $grupo
     */
    public function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    /**
     * get value for grupo 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getGrupo() {
        return $this->grupo;
    }

    /**
     * set value for subgrupo 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $subgrupo
     */
    public function setSubgrupo($subgrupo) {
        $this->subgrupo = $subgrupo;
    }

    /**
     * get value for subgrupo 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getSubgrupo() {
        return $this->subgrupo;
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
     * set value for precio 
     *
     * type:numeric,size:30,default:null,nullable
     *
     * @param mixed $precio
     */
    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    /**
     * get value for precio 
     *
     * type:numeric,size:30,default:null,nullable
     *
     * @return mixed
     */
    public function getPrecio() {
        return $this->precio;
    }

    /**
     * set value for tipo_nomenclador 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @param mixed $tipoNomenclador
     */
    public function setTipoNomenclador($tipoNomenclador) {
        $this->tipoNomenclador = $tipoNomenclador;
    }

    /**
     * get value for tipo_nomenclador 
     *
     * type:text,size:2147483647,default:null,nullable
     *
     * @return mixed
     */
    public function getTipoNomenclador() {
        return $this->tipoNomenclador;
    }

    /**
     * set value for id_nomenclador_detalle 
     *
     * type:int4,size:10,default:null,index,nullable
     *
     * @param mixed $idNomencladorDetalle
     */
    public function setIdNomencladorDetalle($idNomencladorDetalle) {
        $this->idNomencladorDetalle = $idNomencladorDetalle;
    }

    /**
     * get value for id_nomenclador_detalle 
     *
     * type:int4,size:10,default:null,index,nullable
     *
     * @return mixed
     */
    public function getIdNomencladorDetalle() {
        return $this->idNomencladorDetalle;
    }

    /**
     * set value for categoria 
     *
     * type:int2,size:5,default:null,nullable
     *
     * @param mixed $categoria
     */
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    /**
     * get value for categoria 
     *
     * type:int2,size:5,default:null,nullable
     *
     * @return mixed
     */
    public function getCategoria() {
        return $this->categoria;
    }

    /**
     * set value for diagnostico 
     *
     * type:text,size:2147483647,default:null,index,nullable
     *
     * @param mixed $diagnostico
     */
    public function setDiagnostico($diagnostico) {
        $this->diagnostico = $diagnostico;
    }

    public function getCodigoCategoria() {
        $categoria = split(" ", $this->codigo);
        return $categoria[0];
    }

    public function getCodigoTema() {
        $categoria = split(" ", $this->codigo);
        return $categoria[1];
    }

    public function getDiagnostico() {
        return $this->diagnostico;
    }

    public function getCodigoCompleto() {
        return $this->codigo . ' ' . $this->diagnostico;
    }

    /**
     * set value for neo 
     *
     * type:numeric,size:6,default:0
     *
     * @param mixed $neo
     */
    public function setNeo($neo) {
        $this->neo = $neo;
    }

    /**
     * get value for neo 
     *
     * type:numeric,size:6,default:0
     *
     * @return mixed
     */
    public function getNeo() {
        return $this->neo;
    }

    /**
     * set value for cero_a_uno 
     *
     * type:numeric,size:6,default:0
     *
     * @param mixed $ceroAUno
     */
    public function setCeroAUno($ceroAUno) {
        $this->ceroAUno = $ceroAUno;
    }

    /**
     * get value for cero_a_uno 
     *
     * type:numeric,size:6,default:0
     *
     * @return mixed
     */
    public function getCeroAUno() {
        return $this->ceroAUno;
    }

    /**
     * set value for uno_a_seis 
     *
     * type:numeric,size:6,default:0
     *
     * @param mixed $unoASeis
     */
    public function setUnoASeis($unoASeis) {
        $this->unoASeis = $unoASeis;
    }

    /**
     * get value for uno_a_seis 
     *
     * type:numeric,size:6,default:0
     *
     * @return mixed
     */
    public function getUnoASeis() {
        return $this->unoASeis;
    }

    /**
     * set value for seis_a_diez 
     *
     * type:numeric,size:6,default:0
     *
     * @param mixed $seisADiez
     */
    public function setSeisADiez($seisADiez) {
        $this->seisADiez = $seisADiez;
    }

    /**
     * get value for seis_a_diez 
     *
     * type:numeric,size:6,default:0
     *
     * @return mixed
     */
    public function getSeisADiez() {
        return $this->seisADiez;
    }

    /**
     * set value for veinte_a_sesentaycuatro 
     *
     * type:numeric,size:6,default:0
     *
     * @param mixed $veinteASesentaycuatro
     */
    public function setVeinteASesentaycuatro($veinteASesentaycuatro) {
        $this->veinteASesentaycuatro = $veinteASesentaycuatro;
    }

    /**
     * get value for veinte_a_sesentaycuatro 
     *
     * type:numeric,size:6,default:0
     *
     * @return mixed
     */
    public function getVeinteASesentaycuatro() {
        return $this->veinteASesentaycuatro;
    }

    /**
     * set value for f 
     *
     * type:bool,size:1,default:false
     *
     * @param mixed $f
     */
    public function setF($f) {
        $this->f = $f;
    }

    /**
     * get value for f 
     *
     * type:bool,size:1,default:false
     *
     * @return mixed
     */
    public function getF() {
        return $this->f;
    }

    /**
     * set value for m 
     *
     * type:bool,size:1,default:false
     *
     * @param mixed $m
     */
    public function setM($m) {
        $this->m = $m;
    }
    
    public function setPagoDiferenciado($pagoDiferenciado) {
        return $this->pagoDiferenciado = $pagoDiferenciado;
    }

    /**
     * get value for m 
     *
     * type:bool,size:1,default:false
     *
     * @return mixed
     */
    public function getM() {
        return $this->m;
    }

    /**
     * set value for embarazada 
     *
     * type:numeric,size:6,default:0
     *
     * @param mixed $embarazada
     */
    public function setEmbarazada($embarazada) {
        $this->embarazada = $embarazada;
    }

    /**
     * get value for embarazada 
     *
     * type:numeric,size:6,default:0
     *
     * @return mixed
     */
    public function getEmbarazada() {
        return $this->embarazada;
    }

    /**
     * set value for diez_a_veinte 
     *
     * type:numeric,size:6,default:0
     *
     * @param mixed $diezAVeinte
     */
    public function setDiezAVeinte($diezAVeinte) {
        $this->diezAVeinte = $diezAVeinte;
    }

    /**
     * get value for diez_a_veinte 
     *
     * type:numeric,size:6,default:0
     *
     * @return mixed
     */
    public function getDiezAVeinte() {
        return $this->diezAVeinte;
    }

    public function setHabilitado($habilitado) {
        $this->habilitado = $habilitado;
    }

    public function getHabilitado() {
        return $this->habilitado;
    }
    
    public function getPagoDiferenciado() {
        return $this->pagoDiferenciado;
    }

    public function logCambios($habilitacionNomenclador = true) {
        global $_ses_user;
        if ($habilitacionNomenclador) {
            $table = "nomenclador";
        } else {
            $table = "nomenclador_tmp";
        }
        $sql_log = "INSERT INTO facturacion.log_" . $table . "(id_nomenclador,codigo,grupo,subgrupo,descripcion,precio,tipo_nomenclador,id_nomenclador_detalle,categoria,diagnostico,neo,cero_a_uno,uno_a_seis,seis_a_diez,veinte_a_sesentaycuatro,f,m,embarazada,diez_a_veinte,habilitado,usuario,fecha,pago_diferenciado)
                    SELECT id_nomenclador,codigo,grupo,subgrupo,descripcion,precio,tipo_nomenclador,id_nomenclador_detalle,categoria,diagnostico,neo,cero_a_uno,uno_a_seis,seis_a_diez,veinte_a_sesentaycuatro,f,m,embarazada,diez_a_veinte,habilitado,$_ses_user[id],NOW(),pago_diferenciado
                    FROM facturacion." . $table . " 
                    WHERE id_nomenclador='" . $this->getIdNomenclador() . "'
                    ";
        sql($sql_log);
    }

    /* descripcion => devuelve un resultSet con el log de cambios de la practica 
     * parametros => $params: array con parametros para la query.
     *                        Prefijos de tabla: log => tabla log_nomenclador 
     *                                           u   => tabla usuarios
     *                  [select] => (csv o array) campos del select. 
     *                  [where] => (string o array) condicion del where
     *                  [limit] => (integer) cantidad de registros a devolver
     *               $habilitacionNomenclador: 0 o 1 para indicar si el tipo de nomenclador esta habilitado 
     *               o no. Define sobre que tabla se hara la consulta, nomenclador o nomenclador_tmp
     *               Por default trabaja sobre la tabla facturacion.nomenclador
     */

    public function getLogCambios($params, $habilitacionNomenclador = true) {
        if ($params != "") {
            if ($params[select] != "" || !is_null($params[select])) {
                if (is_array($params[select])) {
                    $select = "," . implode(",", $params[select]);
                } else {
                    $select = "," . $params[select];
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
        if ($habilitacionNomenclador) {
            $table = "log_nomenclador";
            $state = "'hab'";
            //$sortField = "id_log_nomenclador";
            //adhiero log de cambios cuando aun no se habia habilitado el nomenclador
            $union = " UNION
                            SELECT 'no_hab' AS estado " . $select . " 
                            FROM facturacion." . $table . "_tmp log 
                            JOIN sistema.usuarios u ON log.usuario=u.id_usuario 
                            WHERE log.codigo='" . $this->getCodigo() . "'
                              AND log.diagnostico='" . $this->getDiagnostico() . "' 
                              AND log.id_nomenclador_detalle='" . $this->getIdNomencladorDetalle() . "'
                     ";
        } else {
            $table = "log_nomenclador_tmp";
            $state = "'no_hab'";
            //$sortField = "id_log_nomenclador_tmp";
        }
        $sql_log = "SELECT " . $state . " AS estado " . $select . " 
                    FROM facturacion." . $table . " log 
                    JOIN sistema.usuarios u ON log.usuario=u.id_usuario 
                    WHERE log.id_nomenclador='" . $this->getIdNomenclador() . "' 
                    " . $where . "
                    " . $union . "
                    ORDER BY estado ASC, fecha DESC
                    " . $limit . "
                   ";
        return sql($sql_log);
    }

    /* descripcion => actualiza el campo 'habilitado' del nomenclador 
     * parametros => $habilitacionNomenclador: 0 o 1 para indicar si el tipo de nomenclador esta habilitado 
     *               o no. Define sobre que tabla se hara la actualizacion, nomenclador o nomenclador_tmp
     *               Por default trabaja sobre la tabla facturacion.nomenclador
     */

    public function actualizarHabilitado($habilitacionNomenclador = true) {
        if ($habilitacionNomenclador) {
            $table = "nomenclador";
        } else {
            $table = "nomenclador_tmp";
        }
        $this->logCambios($habilitacionNomenclador);
        $sql = "UPDATE facturacion." . $table . " SET habilitado='" . $this->getHabilitado() . "' 
                WHERE id_nomenclador='" . $this->getIdNomenclador() . "'";
        if (sql($sql)) {
            return true;
        } else {
            return false;
        }
    }
    
    /* actualiza los precios del codigo nomenclador para los grupos etarios 
     * $precios => array con los nuevos precios para los grupos etarios. El nombre de los 
     *             campos debe coincidir con el nombre de la columna en la tabla nomenclador.
     *             Se incluyen los campos f y m y pago_diferenciado con valores TRUE o FALSE como string
     * $response => array de retorno. Posicion 0 -> indica si hubo cambios en los precios
     *                                Posicion 1 -> contiene el sql de la query
     *                                Posicion 2 -> indica si la query fue exitosa o no
     */

    public function actualizarValorizacion($precios) {
        $habilitacionNomenclador = NomencladorColeccion::estaHabilitado($this->getTipoNomenclador(), $this->getIdNomencladorDetalle());
        $grupos_etarios = array('neo', 'cero_a_uno', 'uno_a_seis', 'seis_a_diez',
            'diez_a_veinte', 'veinte_a_sesentaycuatro', 'embarazada');
        if ($habilitacionNomenclador) {
            $table = "nomenclador";
        } else {
            $table = "nomenclador_tmp";
        }
        //cambios en la valorizacion
        foreach ($grupos_etarios as $grupo) {
            if ($this->permiteEdicionValorizacion($grupo, $habilitacionNomenclador)) {
                // corroboro que la valorizacion haya cambiado para agregar a la query
                $valorActual = $this->getPrecioSegunGrupo($grupo);
                if ($valorActual != $precios[$grupo]) {
                    // el precio debe ser actualizado, se agrega al query
                    $set[] = $grupo . "='" . $precios[$grupo] . "'";
                }
            }
        }
        //cambios en los campos de sexo
        $valorFem = $this->getF() == "t" ? "TRUE" : "FALSE";
        if ($valorFem != $precios[fem]) {
            $set[] = "f=$precios[fem]";
        }
        $valorMas = $this->getM() == "t" ? "TRUE" : "FALSE";
        if ($valorMas != $precios[mas]) {
            $set[] = "m=$precios[mas]";
        }
        $valorPD = $this->getPagoDiferenciado() == "t" ? "TRUE" : "FALSE";
        if ($valorPD != $precios[pdif]) {
            $set[] = "pago_diferenciado=$precios[pdif]";
        }

        if (count($set) > 0) {
            $this->logCambios($habilitacionNomenclador);
            $sql = "UPDATE facturacion." . $table . " 
                    SET " . implode(',', $set) . "
                    WHERE id_nomenclador='" . $this->getIdNomenclador() . "'";
            $response[0] = true;
            $response[1] = $sql;
            if (sql($sql)) {
                $response[2] = true;
            } else {
                $response[2] = false;
            }
        } else {
            $response[0] = false;
            $response[1] = false;
            $response[2] = false;
        }
        return $response;
    }

    /* devuelve true o false si se puede editar la valorizacion para un grupo etario
     * $campoTabla => es el grupo etario: neo, uno_a_seis, etc (coincide con el nombre del campo de la tabla)
     * $habilitacionNomenclador => true o false, referente al tipo de nomenclador (si esta habilitado o aun no)
     */

    public function permiteEdicionValorizacion($campoTabla, $habilitacionNomenclador = "") {
        if ($habilitacionNomenclador == "") {
            $habilitacionNomenclador = NomencladorColeccion::estaHabilitado($this->getTipoNomenclador(), $this->getIdNomencladorDetalle());
        }
        if ($habilitacionNomenclador) {
            $valor = $this->getPrecioSegunGrupo($campoTabla);
            if ($valor == 0) {
                $response = true;
            } else {
                $response = false;
            }
        } else {
            $response = true;
        }
        return $response;
    }

    /* actualiza los precios diferenciados del codigo nomenclador para los grupos etarios 
     * $params => array q contiene id_nomenclador, id_nom_dif y los precios
     *            El nombre de los campos debe coincidir con el nombre de la 
     *            columna en la tabla nomenclador_diferenciado.
     * $response => array de retorno. Posicion 0 -> indica si hubo cambios en los precios
     *                                Posicion 1 -> contiene el sql de la query
     *                                Posicion 2 -> indica si la query fue exitosa o no
     *                                Posicion 3 -> indica el id_nomenclador_dif generado
     */
    public static function actualizarValorizacionPagoDiferenciado($params){
        $grupos_etarios = array('neo', 'cero_a_uno', 'uno_a_seis', 'seis_a_diez','diez_a_veinte', 'veinte_a_sesentaycuatro', 'embarazada');
        foreach ($grupos_etarios as $grupo) {
            $set[] = $grupo . "='" . $params[$grupo] . "'";
        }
        if($params[funcion_pago]==1){
            $funcion_pago = "TRUE";
        }else{
            $funcion_pago = "FALSE";
        }
        $set[] = "funcion_pago=".$funcion_pago;
        $set[] = "comentario_funcion_pago='".$params[comentario_funcion_pago]."'";
        if($params[habilitado]==1){
            $habilitado = "TRUE";
        }else{
            $habilitado = "FALSE";
        }
        $set[] = "habilitado=".$habilitado;
        if($params[id_nom_dif]!=""){
            //update
            $sql = "UPDATE facturacion.nomenclador_diferenciado 
                    SET " . implode(',', $set) . "
                    WHERE id_nom_dif='$params[id_nom_dif]'";
        }else{
            //insert
            $sql = "INSERT INTO facturacion.nomenclador_diferenciado(id_nomenclador,neo,cero_a_uno,uno_a_seis,seis_a_diez,diez_a_veinte,veinte_a_sesentaycuatro,embarazada,habilitado,funcion_pago,comentario_funcion_pago)
                    VALUES('$params[id_nomenclador]','$params[neo]','$params[cero_a_uno]','$params[uno_a_seis]','$params[seis_a_diez]','$params[diez_a_veinte]','$params[veinte_a_sesentaycuatro]','$params[embarazada]',$habilitado,$funcion_pago,'$params[comentario_funcion_pago]')
                    RETURNING id_nom_dif
                   ";
        }
        $response[0] = true;
        $response[1] = $sql;
        
        $res = sql($sql);
        if ($res) {
            if($params[id_nom_dif]==""){
                $response[3] = $res->fields['id_nom_dif'];
            }
            $response[2] = true;
        } else {
            $response[2] = false;
        }
        
        
        return $response;
    }
    
    public static function setCondicionPackNomencladorDiferenciado($idPack,$params){
        $grupos = null;
        if(is_array($params[grupo_et]) && count($params[grupo_et])>0){
            $grupos = implode(',',$params[grupo_et]);
        }
        $sql = "UPDATE facturacion.nomenclador_diferenciado_pack 
                SET cond_pack='$params[cond_pack]',grupos_etarios='$grupos' 
                WHERE id_pack='$idPack'";
        $res = sql($sql);
        if ($res) {
            return true;
        }else{
            return false;
        }
    }
    
    public function savePackNomencladorDiferenciado($params){
        $grupos = '';
        if(is_array($params[grupo_et]) && count($params[grupo_et])>0){
            $grupos = implode(',',$params[grupo_et]);
        }
        $sql = "INSERT INTO facturacion.nomenclador_diferenciado_pack(id_nom_dif,cond_pack,grupos_etarios) 
                VALUES($params[id_nom_dif],'$params[cond_pack]','$grupos') 
                RETURNING id_pack
               ";
        $res = sql($sql);
        if($res){
            $response = $res->fields['id_pack'];
        }else{
            $response = false;
        }
        return $response;
    }
    
    /* guarda las practicas relacionadas a un pack de nomenclador diferenciado 
     * $idNomencDif => es el id del nomenclador diferenciado, al que le voy a relacionar las practicas
     * $arrPracticas => array q contiene los id de nomenclador asociados al nomenclador diferenciado
     */
    public static function savePracticasPackNomencladorDiferenciado($idPack,$arrPracticas){
        $sql = "INSERT INTO facturacion.nomenclador_diferenciado_practicas_pack(id_pack,id_nomenclador) VALUES";
        foreach($arrPracticas as $idNomenc){
            $arr[] = "('$idPack','$idNomenc')";
        }
        $res = sql($sql.implode(',',$arr));
        if ($res) {
            return true;
        }else{
            return false;
        }
    }
    
    /* borra las practicas relacionadas a un pack de nomenclador diferenciado 
     * $idPack => es el id del nomenclador diferenciado, al que le voy a quitar las practicas
     */
    public static function deletePracticasPackNomencladorDiferenciado($idPack){
        $sql = "DELETE FROM facturacion.nomenclador_diferenciado_practicas_pack WHERE id_pack='$idPack'";
        if(sql($sql)){
            return true;
        } else {
            return false;
        }
    }
    
    public static function getPracticasPackNomencladorDiferenciado($idPack){
        $sql = "SELECT n.codigo||' '||n.diagnostico AS cod_practica,
                       n.codigo,n.diagnostico,n.id_nomenclador,ndp.id_pack
                FROM facturacion.nomenclador_diferenciado_pack ndp 
                JOIN facturacion.nomenclador_diferenciado_practicas_pack pp ON ndp.id_pack=pp.id_pack 
                JOIN facturacion.nomenclador n ON pp.id_nomenclador=n.id_nomenclador 
                WHERE ndp.id_pack='$idPack' 
                ORDER BY cod_practica";
        $res = sql($sql);
        if($res) {
            return $res;
        }else{
            return false;
        }
    }
    
    public static function getPackNomencladorDiferenciado($idNomencDif,$order=""){
        if($order!=""){
            $order_fields = $order;
        }else{
            $order_fields = "cod_practica";
        }
        $sql = "SELECT n.codigo||' '||n.diagnostico AS cod_practica,n.id_nomenclador,ndp.id_pack,ndp.cond_pack
                FROM facturacion.nomenclador_diferenciado_pack ndp 
                JOIN facturacion.nomenclador_diferenciado_practicas_pack pp ON ndp.id_pack=pp.id_pack 
                JOIN facturacion.nomenclador n ON pp.id_nomenclador=n.id_nomenclador 
                WHERE ndp.id_nom_dif='$idNomencDif' 
                ORDER BY ".$order_fields;
        $res = sql($sql);
        if($res) {
            return $res;
        }else{
            return false;
        }
    }

    /* descripcion => devuelve un resultSet con los packs de un nomenclador diferenciado 
     * parametros => $params: array con parametros para la query.
     *                  [select] => (csv o array) campos del select. 
     *                  [where] => (string o array) condicion del where
     *                  [limit] => (integer) cantidad de registros a devolver
     */    
    public static function getPacksNomencladorDiferenciado($idNomencDif,$params){
        $select = "*";
        if($params != ""){
            if ($params[select] != "" || !is_null($params[select])) {
                if (is_array($params[select])) {
                    $select = "," . implode(",", $params[select]);
                } else {
                    $select = "," . $params[select];
                }
            }
            if ($params[where] != "") {
                if (is_array($params[where])) {
                    $where = " AND " . implode(" AND ", $params[where]);
                } else {
                    $where = " AND " . $params[where];
                }
            }
            if ($params[limit] != "") {
                $limit = " LIMIT " . $params[limit] . " ";
            }
        }
        $sql = "SELECT ".$select."
                FROM facturacion.nomenclador_diferenciado_pack  
                WHERE id_nom_dif='$idNomencDif' 
                ".$where."
               "; 
        $res = sql($sql);
        if($res) {
            return $res;
        }else{
            return false;
        }
    }    

    public function save() {
        $habilitacionNomenclador = NomencladorColeccion::estaHabilitado($this->getTipoNomenclador(), $this->getIdNomencladorDetalle());
        if ($habilitacionNomenclador) {
            $table = "nomenclador";
        } else {
            $table = "nomenclador_tmp";
        }
        if ($this->getIdNomenclador() == "") {
            //insert
            $valorFem = $this->getF() == "t" ? "TRUE" : "FALSE";
            $valorMas = $this->getM() == "t" ? "TRUE" : "FALSE";
            $sql = "INSERT INTO facturacion." . $table . "(codigo,grupo,subgrupo,descripcion,precio,tipo_nomenclador,
                                                        id_nomenclador_detalle,categoria,diagnostico,neo,cero_a_uno,
                                                        uno_a_seis,seis_a_diez,veinte_a_sesentaycuatro,f,m,
                                                        embarazada,diez_a_veinte,habilitado)
                    VALUES('" . $this->getCodigo() . "','" . $this->getGrupo() . "','" . $this->getSubgrupo() . "','" . $this->getDescripcion() . "'," . $this->getPrecio() . ",'" . $this->getTipoNomenclador() . "',
                           '" . $this->getIdNomencladorDetalle() . "'," . $this->getCategoria() . ",'" . $this->getDiagnostico() . "','" . $this->getNeo() . "','" . $this->getCeroAUno() . "',
                           '" . $this->getUnoASeis() . "','" . $this->getSeisADiez() . "','" . $this->getVeinteASesentaycuatro() . "',$valorFem,$valorMas,
                           '" . $this->getEmbarazada() . "','" . $this->getDiezAVeinte() . "',TRUE)
                    RETURNING id_nomenclador";
        } else {
            //update
            //query log
        }
        $res = sql($sql);
        if ($res) {
            if ($this->getIdNomenclador() == "") {
                $this->setIdNomenclador($res->fields['id_nomenclador']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function getArray() {
        return get_object_vars($this);
    }

    public function getPrecioSegunGrupo($grupo) {
        switch ($grupo) {
            case 'neo':
                $precio = $this->neo;
                break;
            case 'cero_a_uno':
                $precio = $this->ceroAUno;
                break;
            case 'uno_a_seis':
                $precio = $this->unoASeis;
                break;
            case 'seis_a_diez':
                $precio = $this->seisADiez;
                break;
            case 'diez_a_veinte':
                $precio = $this->diezAVeinte;
                break;
            case 'veinte_a_sesentaycuatro':
                $precio = $this->veinteASesentaycuatro;
                break;
            case 'embarazada':
                $precio = $this->embarazada;
                break;

            default:
                break;
        }
        return $precio;
    }

    /* $where => condiciones para el where de la consulta
     * $select => lista de campos a levantar de la tabla
     *            puede ser en formato csv o array
     * $habilitado => 0 o 1 para indicar si el nomenclador esta o no habilitado
     *                si es 0, se usa la tabla nomenclador_tmp, sino nomenclador
     */

    public static function getSQlSelectWhere($where, $select = "", $habilitado = "1") {
        if ($habilitado) {
            $table = "nomenclador";
        } else {
            $table = "nomenclador_tmp";
        }
        if ($select != "") {
            if (is_array($select)) {
                $fields = implode(",", $select);
            } else {
                $fields = $select;
            }
        } else {
            $fields = "*";
        }
        $sql = "SELECT $fields
                FROM facturacion." . $table . "
                WHERE " . $where . "";

        return($sql);
    }

    /* $select => lista de campos a levantar de la tabla
     *            puede ser en formato csv o array
     * $order =>  lista de campos para el ordenamiento
     *            puede ser en formato csv o array
     */

    public static function getSQlSelect($select = "*", $order = "") {
        if ($select != "") {
            if (is_array($select)) {
                $fields = implode(",", $select);
            } else {
                $fields = $select;
            }
        } else {
            $fields = "*";
        }
        if ($order != "") {
            $orderby = "ORDER BY ";
            if (is_array($order)) {
                $orderby .= implode(",", $order);
            } else {
                $orderby .= $order;
            }
        }
        $sql = "SELECT " . $fields . "
                FROM facturacion.nomenclador
                " . $orderby . "
               ";

        return($sql);
    }

    public static function buscarNomencladorPorId($id_nomenclador) {
        $where = "id_nomenclador='$id_nomenclador'";

        $result = sql(Nomenclador::getSQlSelectWhere($where));

        $nomenclador_aux = new Nomenclador();

        $nomenclador_aux->construirResult($result);

        return $nomenclador_aux;
    }

    public static function buscaPractica($categoria, $tema, $patologia, $id_nomenclador_detalle) {
        $codigo = $categoria . " " . $tema;
        $sql_diagnosticos = "SELECT *
                    FROM facturacion.nomenclador 
                    WHERE id_nomenclador_detalle='$id_nomenclador_detalle'               
                    AND codigo='$codigo'
                    AND diagnostico='$patologia'";
        $res_diagnosticos = sql($sql_diagnosticos) or fin_pagina();

        if (!$res_diagnosticos->EOF) {
            $nomenclador = new Nomenclador();
            $nomenclador->construirResult($res_diagnosticos);
        } else {
            $nomenclador = null;
        }
        return $nomenclador;
    }

    public static function practicaSoloParaEmbarazadas($id_nomenclador) {

        $sql = "SELECT * FROM facturacion.nomenclador where id_nomenclador='$id_nomenclador'
            and neo=0
            and cero_a_uno=0
            and uno_a_seis=0
            and seis_a_diez=0
            and diez_a_veinte=0
            and veinte_a_sesentaycuatro=0
            and embarazada > 0";
        $resultado = sql($sql);
        if (!$resultado->EOF) {
            return true;
        } else {
            return false;
        }
    }

    public static function practicaSoloParaUnGrupo($id_nomenclador) {

        $sql = "SELECT * FROM facturacion.nomenclador where id_nomenclador='$id_nomenclador'";
        $resultado = sql($sql);

        if (!$resultado->EOF) {

            $precios[1]['grupo'] = 'Grupo NeoNatal';
            $precios[1]['precio'] = $resultado->fields['neo'];
            $precios[2]['grupo'] = 'Grupo Menor de 1 año';
            $precios[2]['precio'] = $resultado->fields['cero_a_uno'];
            $precios[3]['grupo'] = 'Grupo de 1 a 5 años';
            $precios[3]['precio'] = $resultado->fields['uno_a_seis'];
            $precios[4]['grupo'] = 'Grupo de 6 a 9 años';
            $precios[4]['precio'] = $resultado->fields['seis_a_diez'];
            $precios[5]['grupo'] = 'Grupo de 10 a 19 años';
            $precios[5]['precio'] = $resultado->fields['diez_a_veinte'];
            $precios[6]['grupo'] = 'Grupo de 20 a 64 años';
            $precios[6]['precio'] = $resultado->fields['veinte_a_sesentaycuatro'];
            $precios[7]['grupo'] = 'Embarazadas';
            $precios[7]['precio'] = $resultado->fields['embarazada'];

            $preciounico = 0;
            $i = 1;

            while ($i < 8) {
                if ($precios[$i]['precio'] > 0) {
                    if ($preciounico == 0) {
                        $preciounico = $precios[$i];
                    } else {
                        $preciounico = false;
                        break;
                    }
                }
                $i++;
            }
        }
        return $preciounico;
    }

    public static function getNomencladoresNotIn($id) {
        $where = "SELECT DISTINCT on (codigo ||' '|| diagnostico) codigo ||' '|| diagnostico, * FROM facturacion.nomenclador
                order by codigo ||' '|| diagnostico where id_nomenclador_detalle = '$id'";

        $coleccion = Nomenclador::getNomencladores($where);
        return $coleccion;
    }

    public static function getNomencladores($where = '') {
        if ($where == '') {
            $sql = "SELECT DISTINCT on (codigo ||' '|| diagnostico) codigo ||' '|| diagnostico, * FROM facturacion.nomenclador
                order by codigo ||' '|| diagnostico";
            $result = sql($sql);
        } else {
            $sql = "SELECT DISTINCT on (codigo ||' '|| diagnostico) codigo ||' '|| diagnostico, * FROM facturacion.nomenclador
                    WHERE $where    
                    ORDER BY codigo ||' '|| diagnostico";
            $result = sql($sql);
        }

        $coleccionregistros = array();

        while (!$result->EOF) {

            $registro = new Nomenclador();
            $registro->construirResult($result);

            $coleccionregistros[] = $registro;
            $result->MoveNext();
        }

        return ($coleccionregistros);
    }
    static public function getNombreNomenclador($tipo_nomenclador) {
        $muestra_tipo_de_nomenclador = null;
        switch ($tipo_nomenclador) {
            case 'BASICO':
                $muestra_tipo_de_nomenclador = 'Basico';
                break;
            case 'BASICO_2':
                $muestra_tipo_de_nomenclador = 'Basico 2';
                break;
            case 'CARDIOPATIAS_CATASTROFICO':
                $muestra_tipo_de_nomenclador = 'Cardiopatias Catastrofico';
                break;
            case 'CARDIOPATIAS_NO_NOCATASTROFICO':
                $muestra_tipo_de_nomenclador = 'Cardiopatias No Catastrofico';
                break;
            case 'RONDAS':
                $muestra_tipo_de_nomenclador = 'Rondas';
                break;
            case 'PERINATAL_NO_CATASTROFICO':
                $muestra_tipo_de_nomenclador = 'Perinatal No Catastrofico';
                break;
            case 'REMEDIAR':
                $muestra_tipo_de_nomenclador = 'Remediar';
                break;
            case 'PERINATAL_CATASTROFICO':
                $muestra_tipo_de_nomenclador = 'Perinatal Catastrofico';
                break;
            case 'TALLERES':
                $muestra_tipo_de_nomenclador = 'Talleres';
                break;
            case 'INCLUIR':
                $muestra_tipo_de_nomenclador = 'Incluir';
                break;
            case 'ANOMALIAS_CONGENITAS':
                $muestra_tipo_de_nomenclador = 'Anomalias Congenitas';
                break;            
            default:
                break;
        }

        return $muestra_tipo_de_nomenclador;
    }

}

class NomencladorColeccion {
    /* descripcion => setea el estado de un tipo de nomenclador en la tabla nomenclador_estado
     * parametros => $tipoNomenclador: texto, 'BASICO','PERINATAL_CATASTROFICO',etc
     *               $vigencia: entero, es el id de nomenclador_detalle 
     *               $estado: TRUE o FALSE, es el estado a setear. Por default es TRUE
     */

    public static function actualizarNomencladorEstado($tipoNomenclador, $vigencia, $estado = "TRUE") {
        $sql = "UPDATE facturacion.nomenclador_estado SET habilitado=$estado
                WHERE tipo_nomenclador='$tipoNomenclador' 
                  AND id_nomenclador_detalle='$vigencia'";
        if (sql($sql)) {
            return true;
        } else {
            return false;
        }
    }
    
    /* Metodo que actualiza las practicas diferenciadas con sus respectivos packs
     * de acuerdo a los que ya poseia dicha practica en la vigencia de la que fue copiado
     * Se debe tener en cuenta que solo actualizara las practicas asociadas cuyo nomenclador 
     * se encuentre activo (ya que debe buscar los ids de las practicas habilitadas)
     */
    public static function actualizarNomencladorPracticasDiferenciadas($tipoNomenclador, $vigenciaFrom, $vigenciaNueva){
        
        $sql = "SELECT nnew.id_nomenclador AS id_nomenclador_new,ndif.*
                FROM facturacion.nomenclador_diferenciado ndif 
                JOIN facturacion.nomenclador n ON ndif.id_nomenclador=n.id_nomenclador
                JOIN facturacion.nomenclador nnew ON n.codigo=nnew.codigo AND n.diagnostico=nnew.diagnostico
                WHERE n.tipo_nomenclador='$tipoNomenclador' 
                  AND n.id_nomenclador_detalle='$vigenciaFrom'
                  AND nnew.tipo_nomenclador='$tipoNomenclador' 
                  AND nnew.id_nomenclador_detalle='$vigenciaNueva'
                  AND nnew.pago_diferenciado=TRUE
               ";
        $result = sql($sql);
        if($result && $result->RecordCount()>0){
            while(!$result->EOF){
                    $funcion_pago = $result->fields['funcion_pago']== "t" ? "1" : "";
                    $habilitado = $result->fields['habilitado']== "t" ? "1" : "";
                    $params = array("id_nomenclador"=>$result->fields['id_nomenclador_new'],
                                    "neo"=>$result->fields['neo'],
                                    "cero_a_uno"=>$result->fields['cero_a_uno'],
                                    "uno_a_seis"=>$result->fields['uno_a_seis'],	
                                    "seis_a_diez"=>$result->fields['seis_a_diez'],	
                                    "diez_a_veinte"=>$result->fields['diez_a_veinte'],	
                                    "veinte_a_sesentaycuatro"=>$result->fields['veinte_a_sesentaycuatro'],		
                                    "embarazada"=>$result->fields['embarazada'],	
                                    "cond_pack"=>$result->fields['cond_pack'],
                                    "habilitado"=>$habilitado,
                                    "funcion_pago"=>$funcion_pago,	
                                    "comentario_funcion_pago"=>$result->fields['comentario_funcion_pago']
                                   );
                    //realizo la insercion de las practicas diferenciadas
                    $resp = Nomenclador::actualizarValorizacionPagoDiferenciado($params);
                    unset($params);
                    if($resp[2]){
                        $id_nom_dif = $resp[3];
                        $res_pack = Nomenclador::getPacksNomencladorDiferenciado($result->fields['id_nom_dif'],''); 
                        if($res_pack && $res_pack->RecordCount()>0){
                            //realizo la insercion de los packs de cada practica con PD
                            while(!$res_pack->EOF){
                                $params_pack = array("id_nom_dif"=>$id_nom_dif,
                                                     "cond_pack"=>$res_pack->fields['cond_pack'],
                                                     "grupo_et"=>explode(',',$res_pack->fields['grupos_etarios'])
                                                    );
                                $id_pack = Nomenclador::savePackNomencladorDiferenciado($params_pack);
                                unset($params_pack);
                                
                                if($id_pack){
                                    //realizo la insercion de las practicas de cada pack
                                    $res_prac = Nomenclador::getPracticasPackNomencladorDiferenciado($res_pack->fields['id_pack']);
                                    if($res_prac && $res_prac->RecordCount()>0){
                                        $arr_codigos = array();
                                        while(!$res_prac->EOF){
                                            $arr_codigos[] = $res_prac->fields['cod_practica'];
                                            $res_prac->MoveNext();
                                        }
                                        if(count($arr_codigos)>0){
                                            //obtengo los ids nuevos de las practicas del nomenclador (pero codigo y diagnostico igual)
                                            $cnd_codigos  = " codigo||' '||diagnostico IN('".implode("','",$arr_codigos)."') ";
                                            $cnd_codigos .= " AND tipo_nomenclador='$tipoNomenclador' AND id_nomenclador_detalle='$vigenciaNueva' ";
                                            $sql_nom = Nomenclador::getSQlSelectWhere($cnd_codigos, "id_nomenclador");
                                            $res_nom = sql($sql_nom);
                                            if($res_nom && $res_nom->RecordCount()>0){
                                                //insertar las practicas al pack
                                                $arr_practicas = array();
                                                while(!$res_nom->EOF){
                                                    $arr_practicas[] = $res_nom->fields['id_nomenclador'];
                                                    $res_nom->MoveNext();
                                                }
                                                if(count($arr_practicas)>0){
                                                    Nomenclador::savePracticasPackNomencladorDiferenciado($id_pack,$arr_practicas);
                                                    unset($arr_practicas);
                                                }
                                            }
                                        }
                                        unset($arr_codigos,$cnd_codigos);
                                    }
                                }
                                $res_pack->MoveNext();
                            }
                        }
                    }
                $result->MoveNext();
            }
        }
        
    }
    //fin bloque experimental

    /* descripcion => elimina un codigo+practica de la tabla nomenclador_tmp
     * parametros => $idNomenclador: entero, primary key de la tabla
     * valor de retorno => boolean (verdadero si se elimino, falso en caso contrario)
     */

    public static function borrarCodigoPracticaNomenclador($idNomenclador) {
        $sql = "DELETE FROM facturacion.nomenclador_tmp WHERE id_nomenclador='$idNomenclador'";
        if (sql($sql)) {
            return true;
        } else {
            return false;
        }
    }

    /* descripcion => elimina todos los registros de un tipo de nomenclador de la tabla nomenclador_tmp
     * parametros => $tipoNomenclador: texto, 'BASICO','PERINATAL_CATASTROFICO',etc
     *               $vigencia: entero, es el id de nomenclador_detalle 
     */

    public static function borrarTipoNomencladorTemp($tipoNomenclador, $vigencia) {
        $sql = "DELETE FROM facturacion.nomenclador_tmp 
                WHERE tipo_nomenclador='" . trim($tipoNomenclador) . "'
                  AND id_nomenclador_detalle='$vigencia'
               ";
        sql($sql);
    }

    public static function obtenerGrupos($tipo_de_nomenclador, $efector, $fecha_comprobante,$categoria,$objetoPrestacion,$diagnostico,$sexo){


       
        if ($sexo == 'Femenino') {
            $condicion_sexo = "AND n.F=TRUE";
        } elseif ($sexo == 'Masculino') {
            $condicion_sexo = "AND n.M=TRUE";
        } else {
            $condicion_sexo = "AND n.F=TRUE
                           AND n.M=TRUE";
        }
        $sqltipo = "SELECT cn.* FROM nacer.efe_conv n
                            INNER JOIN nacer.conv_nom cn USING(id_efe_conv)
                            INNER JOIN facturacion.nomenclador_detalle nd on (cn.id_nomenclador_detalle=nd.id_nomenclador_detalle)
                            WHERE n.cuie='$efector'
                            AND '$fecha_comprobante' BETWEEN fecha_desde AND fecha_hasta";
        $res_tipos = sql($sqltipo);
        $id_nomenclador = $res_tipos->fields['id_nomenclador_detalle']; 
        $sql_categorias = "SELECT *
                                FROM facturacion.nomenclador n
                                LEFT JOIN nomenclador.patologias p ON n.diagnostico=p.codigo
                                WHERE n.id_nomenclador_detalle='$id_nomenclador'
                                AND n.habilitado=TRUE 
                                AND n.tipo_nomenclador='$tipo_de_nomenclador'
                                $condicion_sexo
                                AND n.codigo = '$categoria $objetoPrestacion'
                                AND n.diagnostico = '$diagnostico'";
        $result = sql($sql_categorias) or fin_pagina();
        $grupos = array();
        if ($result->fields['neo']>0){
            $grupos[] = 'neo';
            
        }
        if ($result->fields['cero_a_uno']>0){
            $grupos[] = 'cero_a_uno';
            
        }
        if ($result->fields['uno_a_seis']>0){
            $grupos[] = 'uno_a_seis';
            
        }
        if ($result->fields['seis_a_diez']>0){
            $grupos[] = 'seis_a_diez';

        }
        if ($result->fields['diez_a_veinte']>0){
            $grupos[] = 'diez_a_veinte';
        }   
        if ($result->fields['veinte_a_sesentaycuatro']>0){
            $grupos[] = 'veinte_a_sesentaycuatro';
        }        

        return $grupos;        
    }
    public static function codigosDePrestaciones($tipo_de_nomenclador, $efector, $fecha_comprobante, $sexo) {
       
        if ($sexo == 'F') {
            $condicion_sexo = "AND n.F=TRUE";
        } elseif ($sexo == 'M') {
            $condicion_sexo = "AND n.M=TRUE";
        } else {
            $condicion_sexo = "AND n.F=TRUE
               AND n.M=TRUE";
        }
        $sqltipo = "SELECT cn.* FROM nacer.efe_conv n
                            INNER JOIN nacer.conv_nom cn USING(id_efe_conv)
                            INNER JOIN facturacion.nomenclador_detalle nd on (cn.id_nomenclador_detalle=nd.id_nomenclador_detalle)
                            WHERE n.cuie='$efector'
                            AND '$fecha_comprobante' BETWEEN fecha_desde AND fecha_hasta";
        $res_tipos = sql($sqltipo);
        $id_nomenclador = $res_tipos->fields['id_nomenclador_detalle'];   
        $sql_categorias ="SELECT split_part(n.codigo,' ',1) categoria,
                                 split_part(n.codigo,' ',2) codigo,
                                  n.diagnostico,p.descripcion
                                FROM facturacion.nomenclador n
                                LEFT JOIN nomenclador.patologias p ON n.diagnostico=p.codigo
                                WHERE n.id_nomenclador_detalle=$id_nomenclador
                                AND n.habilitado=TRUE and  n.tipo_nomenclador='$tipo_de_nomenclador'
                                $condicion_sexo
                                order by n.categoria,n.codigo,n.diagnostico";
        $result = sql($sql_categorias);

        $data = array();
        if ($result && $result->RecordCount() > 0) {

            $keys = (array_keys($result->fields));
            while (!$result->EOF) {

                $temp = array();
                for ($i = 1; $i < count($keys); $i+=2) {
                    $temp[$keys[$i]] = htmlentities($result->fields[$keys[$i]]);
                    ;
                }
                $data[] = $temp;
                $result->MoveNext();
            }
        }
        return $data;
    }
    public static function labCodigos(){
         $sql_codlab = "SELECT codigo, categoria FROM nomenclador.grupo_prestacion";
        $result = sql($sql_codlab) or fin_pagina();
        $data = array();
        if ($result && $result->RecordCount() > 0) {
            $keys = (array_keys($result->fields));
            while (!$result->EOF) {
                $temp = array();
                for ($i = 1; $i < count($keys); $i+=2) {
                    $temp[$keys[$i]] = htmlentities($result->fields[$keys[$i]]);
                    ;
                }
                $data[] = $temp;
                $result->MoveNext();
            }
        }
        return $data;
    
        
    }
    public static function codigosPorSexoYGrupo($id_nomenclador_detalle, $tipo_nomenclador, $grupo_etareo, $sexo) {
        $categoria = $grupo_etareo['categoria'];

        if ($sexo == 'F') {
            $condicion_sexo = "AND n.F=TRUE";
        } elseif ($sexo == 'M') {
            $condicion_sexo = "AND n.M=TRUE";
        } else {
            $condicion_sexo = "AND n.F=TRUE
                           AND n.M=TRUE";
        }

        if ($grupo_etareo['estaembarazada']) {
            $cuandoesembarazada = "OR n.embarazada > 0";
        } else {
            $cuandoesembarazada = "";
        }

        $sql_categorias = "SELECT split_part(n.codigo,' ',1) categoria,
                                  split_part(n.codigo,' ',2) codigo,
                                  n.diagnostico,p.descripcion, p.color
                                FROM facturacion.nomenclador n
                                LEFT JOIN nomenclador.patologias p ON n.diagnostico=p.codigo
                                WHERE n.id_nomenclador_detalle='$id_nomenclador_detalle'
                                AND n.habilitado=TRUE 
                                AND (n.$categoria > 0 $cuandoesembarazada)
                                AND n.tipo_nomenclador='$tipo_nomenclador'
                                $condicion_sexo
                                order by n.categoria,n.codigo,n.diagnostico";
        $result = sql($sql_categorias) or fin_pagina();
        $data = array();
        if ($result && $result->RecordCount() > 0) {
            $keys = (array_keys($result->fields));
            while (!$result->EOF) {
                $temp = array();
                for ($i = 1; $i < count($keys); $i+=2) {
                    $temp[$keys[$i]] = htmlentities($result->fields[$keys[$i]]);
                    ;
                }
                $data[] = $temp;
                $result->MoveNext();
            }
        }
        return $data;
    }

    /* descripcion => devuelve true o false si el tipo de nomenclador esta habilitado o no
     * parametros => $tipoNomenclador: texto, 'BASICO','PERINATAL_CATASTROFICO',etc
     *               $vigencia: entero, es el id de nomenclador_detalle 
     */

    public static function estaHabilitado($tipoNomenclador, $vigencia) {
        $sql = "SELECT habilitado 
                FROM facturacion.nomenclador_estado 
                WHERE tipo_nomenclador='$tipoNomenclador' 
                  AND id_nomenclador_detalle='$vigencia' ";
        $result = sql($sql);
        if ($result && $result->RecordCount() > 0) {
            $result->MoveFirst();
            if ($result->fields['habilitado'] == "t") {
                $response = true;
            } else {
                $response = false;
            }
        } else {
            $response = false;
        }
        return $response;
    }

    /* descripcion => verifica la existencia de un codigo+practica en una cierta  
     *                vigencia consultando la tablas nomenclador y nomenclador_tmp
     * parametros => $codigo: texto, 'IT E013','CT C001',etc
     *               $diagnostico: texto, 'P07.0', 'A97', etc
     *               $vigencia: entero, es el id de nomenclador_detalle 
     * valor de retorno => boolean
     */

    public static function existeCodigoNomenclador($codigo, $diagnostico, $vigencia) {
        $sql = "SELECT COUNT(*) AS total 
                FROM ( 	SELECT id_nomenclador
                        FROM facturacion.nomenclador_tmp
                        WHERE codigo='$codigo'
                        AND diagnostico='$diagnostico'
                        AND id_nomenclador_detalle='$vigencia'
                      UNION 
                        SELECT id_nomenclador
                        FROM facturacion.nomenclador
                        WHERE codigo='$codigo'
                        AND diagnostico='$diagnostico'
                        AND id_nomenclador_detalle='$vigencia'
                     ) nomenc
                ";
        $result = sql($sql);
        if ($result->fields['total'] > 0) {
            $response = true;
        } else {
            $response = false;
        }
        return $response;
    }

    /* descripcion => verifica la existencia de un tipo de nomenclador en una cierta  
     *                vigencia consultando la tabla facturacion.nomenclador_estado
     * parametros => $tipoNomenclador: texto, 'BASICO','PERINATAL_CATASTROFICO',etc
     *               $vigencia: entero, es el id de nomenclador_detalle 
     * valor de retorno => boolean
     */

    public static function existeNomenclador($tipoNomenclador, $vigencia) {
        $sql = "SELECT habilitado 
                FROM facturacion.nomenclador_estado 
                WHERE tipo_nomenclador='$tipoNomenclador' 
                  AND id_nomenclador_detalle='$vigencia' ";
        $result = sql($sql);
        if ($result && $result->RecordCount() > 0) {
            $result->MoveFirst();
            $response = true;
        } else {
            $response = false;
        }
        return $response;
    }

    public static function getTiposNomenclador() {
        $sql = "SELECT DISTINCT(tipo_nomenclador) 
                FROM facturacion.nomenclador  
                ORDER BY tipo_nomenclador";
        $result = sql($sql);
        return($result);
    }

    public static function getCodigosNomenclador($tipoNomenclador, $vigencia) {
        $habilitado = self::estaHabilitado($tipoNomenclador, $vigencia);
        if ($habilitado) {
            $table = "nomenclador";
        } else {
            $table = "nomenclador_tmp";
        }
        $sql = "SELECT *
                FROM facturacion." . $table . " 
                WHERE tipo_nomenclador='$tipoNomenclador' 
                  AND id_nomenclador_detalle='$vigencia' 
                ORDER BY codigo,diagnostico
                ";
        $result = sql($sql);
        return $result;
    }

    public static function getCodigosNomencladorPagoDif($vigencia) {
        $sql = "SELECT n.id_nomenclador, nd.id_nom_dif,n.codigo,n.diagnostico, n.tipo_nomenclador,
                       CASE WHEN nd.id_nom_dif IS NULL THEN n.neo ELSE nd.neo END AS neo,
                       CASE WHEN nd.id_nom_dif IS NULL THEN n.cero_a_uno ELSE nd.cero_a_uno END AS cero_a_uno,
                       CASE WHEN nd.id_nom_dif IS NULL THEN n.uno_a_seis ELSE nd.uno_a_seis END AS uno_a_seis,
                       CASE WHEN nd.id_nom_dif IS NULL THEN n.seis_a_diez ELSE nd.seis_a_diez END AS seis_a_diez,
                       CASE WHEN nd.id_nom_dif IS NULL THEN n.diez_a_veinte ELSE nd.diez_a_veinte END AS diez_a_veinte,
                       CASE WHEN nd.id_nom_dif IS NULL THEN n.veinte_a_sesentaycuatro ELSE nd.veinte_a_sesentaycuatro END AS veinte_a_sesentaycuatro,
                       CASE WHEN nd.id_nom_dif IS NULL THEN n.embarazada ELSE nd.embarazada END AS embarazada,
                       CASE WHEN nd.id_nom_dif IS NULL THEN FALSE ELSE nd.funcion_pago END AS funcion_pago,
                       CASE WHEN nd.id_nom_dif IS NULL THEN FALSE ELSE nd.habilitado END AS habilitado,
                       CASE WHEN nd.id_nom_dif IS NULL THEN '' ELSE nd.comentario_funcion_pago END AS comentario_funcion_pago
                FROM facturacion.nomenclador n 
                LEFT JOIN facturacion.nomenclador_diferenciado nd ON n.id_nomenclador=nd.id_nomenclador
                WHERE n.id_nomenclador_detalle='$vigencia' 
                  AND n.pago_diferenciado=TRUE 
                ORDER BY n.codigo,n.diagnostico
                ";
        $result = sql($sql);
        return $result;
    }

    /* descripcion => devuelve la lista unica de codigos o diagnosticos de nomenclador
     * parametros => $parte: entero, valores posibles: 1 o 2 o 3 
     *                       caso de ejemplo: CT C001 A97 
     *                       si es 1 devuelve la primera parte del codigo (CT)
     *                       si es 2 devuelve la segunda parte del codigo (C001)
     *                       si es 3 devuelve el diagnostico (A97)
     */

    public static function getListaCodigosDiagnosticos($parte) {
        if ($parte == 1) {
            $sql = "SELECT DISTINCT(SPLIT_PART(codigo,' ',1)) codigo
                    FROM facturacion.nomenclador
                    WHERE LENGTH(SPLIT_PART(codigo,' ',1))=2 
                    ORDER BY codigo";
        }
        if ($parte == 2) {
            $sql = "SELECT DISTINCT(SPLIT_PART(codigo,' ',2)) codigo
                    FROM facturacion.nomenclador
                    WHERE LENGTH(SPLIT_PART(codigo,' ',2))=4 
                    ORDER BY codigo";
        }
        if ($parte == 3) {
            $sql = "SELECT DISTINCT(codigo) diagnostico
                    FROM nomenclador.patologias 
                    ORDER BY codigo";
        }

        $result = sql($sql);
        return $result;
    }
    
    public static function getVigenciaProcediente($tipoNomenclador, $vigencia){
        $sql = "SELECT id_nomenclador_detalle_procede 
                FROM facturacion.nomenclador_estado 
                WHERE tipo_nomenclador='$tipoNomenclador'
                  AND id_nomenclador_detalle='$vigencia'
                ";
        $result = sql($sql);
        if($result){
            return $result->fields['id_nomenclador_detalle_procede'];
        }else{
            return false;
        }
    }

    public static function nuevoNomencladorEstado($tipoNomenclador, $vigencia, $vigenciaProcede) {
        $sql = "INSERT INTO facturacion.nomenclador_estado(tipo_nomenclador,id_nomenclador_detalle,habilitado,id_nomenclador_detalle_procede) 
                VALUES('" . trim($tipoNomenclador) . "','$vigencia',FALSE,'$vigenciaProcede')";
        sql($sql);
    }

    public static function duplicarNomenclador($tipoNomenclador, $vigenciaFrom, $vigenciaNueva) {
        $sql = "INSERT INTO facturacion.nomenclador_tmp(codigo,grupo,subgrupo,descripcion,precio,tipo_nomenclador,id_nomenclador_detalle,categoria,diagnostico,neo,cero_a_uno,uno_a_seis,seis_a_diez,veinte_a_sesentaycuatro,f,m,embarazada,diez_a_veinte,habilitado,pago_diferenciado)
                SELECT codigo,grupo,subgrupo,descripcion,precio,tipo_nomenclador,$vigenciaNueva,categoria,diagnostico,neo,cero_a_uno,uno_a_seis,seis_a_diez,veinte_a_sesentaycuatro,f,m,embarazada,diez_a_veinte,habilitado,pago_diferenciado
                FROM facturacion.nomenclador 
                WHERE tipo_nomenclador='" . trim($tipoNomenclador) . "'
                  AND id_nomenclador_detalle='$vigenciaFrom'
               ";
        sql($sql);
    }

    /* descripcion => habilita un tipo de nomenclador, es decir, pasa todos los registros de los
     *                codigos+practicas desde la tabla nomenclador_tmp a nomenclador para una vigencia dada
     * parametros => $tipoNomenclador: texto, 'BASICO','PERINATAL_CATASTROFICO',etc
     *               $vigencia: entero, es el id de nomenclador_detalle 
     */

    public static function habilitarTipoNomenclador($tipoNomenclador, $vigencia) {
        $sql = "INSERT INTO facturacion.nomenclador(codigo,grupo,subgrupo,descripcion,precio,tipo_nomenclador,id_nomenclador_detalle,categoria,diagnostico,neo,cero_a_uno,uno_a_seis,seis_a_diez,veinte_a_sesentaycuatro,f,m,embarazada,diez_a_veinte,habilitado,pago_diferenciado)
                SELECT codigo,grupo,subgrupo,descripcion,precio,tipo_nomenclador,id_nomenclador_detalle,categoria,diagnostico,neo,cero_a_uno,uno_a_seis,seis_a_diez,veinte_a_sesentaycuatro,f,m,embarazada,diez_a_veinte,habilitado,pago_diferenciado 
                FROM facturacion.nomenclador_tmp 
                WHERE tipo_nomenclador='" . trim($tipoNomenclador) . "'
                  AND id_nomenclador_detalle='$vigencia'
               ";
        sql($sql);
    }

    public static function getConceptosInternacion() {
        $sql = "SELECT descripcion_abreviada, array_agg(descripcion) arr 
                FROM nomenclador.conceptos_internacion 
                WHERE descripcion_abreviada<>'' 
                GROUP BY descripcion_abreviada";
        $res = sql($sql);
        return $res;
    }

    public static function todosLosCodigos($id_nomenclador_detalle, $tipo_nomenclador) {


        $sql_categorias = "SELECT split_part(n.codigo,' ',1) categoria,
                                  split_part(n.codigo,' ',2) codigo,
                                  n.diagnostico,p.descripcion
                                FROM facturacion.nomenclador n
                                WHERE n.id_nomenclador_detalle='$id_nomenclador_detalle'
                                AND n.habilitado=TRUE
                                AND n.tipo_nomenclador='$tipo_nomenclador'
                                order by n.categoria,n.codigo,n.diagnostico";
        $result = sql($sql_categorias) or fin_pagina();
        $data = array();
        if ($result && $result->RecordCount() > 0) {
            $keys = (array_keys($result->fields));
            while (!$result->EOF) {
                $temp = array();
                for ($i = 1; $i < count($keys); $i+=2) {
                    $temp[$keys[$i]] = htmlentities($result->fields[$keys[$i]]);
                    ;
                }
                $data[] = $temp;
                $result->MoveNext();
            }
        }
        return $data;
    }

}

class CodigoNomenclador extends consultasDB {

    private $id_nomenclador;
    private $codigo;
    private $tipoNomenclador;
    private $idNomencladorDetalle;
    private $diagnostico;
    private $categoria;
    private $neo;
    private $ceroAUno;
    private $unoASeis;
    private $seisADiez;
    private $veinteASesentaycuatro;
    private $f;
    private $m;
    private $embarazada;
    private $diezAVeinte;
    private $habilitado;

    public static function Schema() {
        $esquema = array("schema" => "facturacion", "tabla" => "nomenclador", "alias" => "nomenclador");
        return $esquema;
    }

    public static function Propiedades() {
        $propiedades = array(
            'id_nomenclador' => array('field' => 'id_nomenclador', 'getter' => 'getIDNomenclador', 'setter' => 'setIDNomenclador'),
            'codigo' => array('field' => 'codigo', 'getter' => 'getCodigo', 'setter' => 'setCodigo'),
            'tipoNomenclador' => array('field' => 'tipo_nomenclador', 'getter' => 'getTipoNomenclador', 'setter' => 'setTipoNomenclador'),
            'idNomencladorDetalle' => array('field' => 'id_nomenclador_detalle', 'getter' => 'getIdNomencladorDetalle', 'setter' => 'setIdNomencladorDetalle'),
            'diagnostico' => array('field' => 'diagnostico', 'getter' => 'getDiagnostico', 'setter' => 'setDiagnostico'),
            'neo' => array('field' => 'neo', 'getter' => 'getNeo', 'setter' => 'setNeo'),
            'cero_a_uno' => array('field' => 'cero_a_uno', 'getter' => 'getCeroAUno', 'setter' => 'setCeroAUno'),
            'uno_a_seis' => array('field' => 'uno_a_seis', 'getter' => 'getUnoASeis', 'setter' => 'setUnoASeis'),
            'seis_a_diez' => array('field' => 'seis_a_diez', 'getter' => 'getSeisADiez', 'setter' => 'setSeisADiez'),
            'veinte_a_sesentaycuatro' => array('field' => 'veinte_a_sesentaycuatro', 'getter' => 'getVeinteASesentaycuatro', 'setter' => 'setVeinteASesentaycuatro'),
            'f' => array('field' => 'f', 'getter' => 'getF', 'setter' => 'setF'),
            'm' => array('field' => 'm', 'getter' => 'getM', 'setter' => 'setM'),
            'embarazada' => array('field' => 'embarazada', 'getter' => 'getEmbarazada', 'setter' => 'setEmbarazada'),
            'diez_a_veinte' => array('field' => 'diez_a_veinte', 'getter' => 'getDiezAVeinte', 'setter' => 'setDiezAVeinte'),
            'habilitado' => array('field' => 'habilitado', 'getter' => 'getHabilitado', 'setter' => 'setHabilitado'),
            'categoria' => array('getter' => 'getCategoria', 'setter' => 'setCategoria')
        );
        return $propiedades;
    }

    function getIDNomenclador() {
        return $this->id_nomenclador;
    }

    function getCodigo() {
        return $this->codigo;
    }

    function getTipoNomenclador() {
        return $this->tipoNomenclador;
    }

    function getIDNomencladorDetalle() {
        return $this->idNomencladorDetalle;
    }

    function getDiagnostico() {
        return $this->diagnostico;
    }

    function getNeo() {
        return $this->neo;
    }

    function getId_nomenclador() {
        return $this->id_nomenclador;
    }

    function getCategoria() {
        return $this->categoria;
    }

    function setId_nomenclador($id_nomenclador) {
        $this->id_nomenclador = $id_nomenclador;
    }

    function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    function getCeroAUno() {
        return $this->ceroAUno;
    }

    function getUnoASeis() {
        return $this->unoASeis;
    }

    function getSeisADiez() {
        return $this->seisADiez;
    }

    function getVeinteASesentaycuatro() {
        return $this->veinteASesentaycuatro;
    }

    function getF() {
        return $this->f;
    }

    function getM() {
        return $this->m;
    }

    function getEmbarazada() {
        return $this->embarazada;
    }

    function getDiezAVeinte() {
        return $this->diezAVeinte;
    }

    function getHabilitado() {
        return $this->habilitado;
    }

    function setIDNomenclador($id_nomenclador) {
        $this->id_nomenclador = $id_nomenclador;
    }

    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function setTipoNomenclador($tipoNomenclador) {
        $this->tipoNomenclador = $tipoNomenclador;
    }

    function setIDNomencladorDetalle($idNomencladorDetalle) {
        $this->idNomencladorDetalle = $idNomencladorDetalle;
    }

    function setDiagnostico($diagnostico) {
        $this->diagnostico = $diagnostico;
    }

    function setNeo($neo) {
        $this->neo = $neo;
    }

    function setCeroAUno($ceroAUno) {
        $this->ceroAUno = $ceroAUno;
    }

    function setUnoASeis($unoASeis) {
        $this->unoASeis = $unoASeis;
    }

    function setSeisADiez($seisADiez) {
        $this->seisADiez = $seisADiez;
    }

    function setVeinteASesentaycuatro($veinteASesentaycuatro) {
        $this->veinteASesentaycuatro = $veinteASesentaycuatro;
    }

    function setF($f) {
        $this->f = $f;
    }

    function setM($m) {
        $this->m = $m;
    }

    function setEmbarazada($embarazada) {
        $this->embarazada = $embarazada;
    }

    function setDiezAVeinte($diezAVeinte) {
        $this->diezAVeinte = $diezAVeinte;
    }

    function setHabilitado($habilitado) {
        $this->habilitado = $habilitado;
    }
    
    public function getCodigoCompleto() {
        return $this->codigo . ' ' . $this->diagnostico;
    }

}

class CodigosNomencladorColeccion {

    public static function buscar($where = array(), $orden = array(), $limit = null) {
        $sql = CodigoNomenclador::getSQlSelect(CodigoNomenclador::Schema(), CodigoNomenclador::Propiedades(), array("*"), $where, $orden, $limit);
        $result = sql($sql);
        $codigos = array();
        while (!$result->EOF) {
            $codigo = new CodigoNomenclador();
            $codigo->construirResult($result->fields);
            $codigos[] = $codigo;
            $result->MoveNext();
        }
        return $codigos;
    }

    public static function buscarNomencladorPorId($id_nomenclador) {
        $value = false;
        $where[] = "id_nomenclador='$id_nomenclador'";
        $result = CodigosNomencladorColeccion::buscar($where);
        if ($result) {
            $value = $result[0];
        }

        return $value;
    }

    public static function codigosDisponibles($id_nomenclador_detalle, $tipo_nomenclador, $grupo_etareo, $sexo) {

        $categoria = $grupo_etareo['categoria'];
        $where[] = "$categoria > 0";

        if (strtoupper($sexo) == 'F') {
            $where[] = "AND F=TRUE";
        } elseif (strtoupper($sexo) == 'M') {
            $where[] = "AND M=TRUE";
        }

        if ($grupo_etareo['estaembarazada']) {
            $where[] = "OR n.embarazada > 0";
        }

        $where[] = "AND id_nomenclador_detalle='$id_nomenclador_detalle'";

        $where[] = "AND habilitado=TRUE";

        $where[] = "AND tipo_nomenclador='$tipo_nomenclador'";

        $orden[] = array("campo" => "categoria,codigo,diagnostico", "criterio" => "ASC");

        $sql = CodigoNomenclador::getSQlSelect(CodigoNomenclador::Schema(), CodigoNomenclador::Propiedades(), array("split_part(codigo,' ',1) categoria,
                                  split_part(codigo,' ',2) codigo,
                                  diagnostico"), $where, $orden, $limit);
        $result = sql($sql);
        $codigos = array();
        while (!$result->EOF) {
            $codigo = new CodigoNomenclador();
            $codigo->construirResult($result->fields);
            $codigos[$codigo->getCategoria()][$codigo->getCodigo()][] = $codigo->getDiagnostico();
            $result->MoveNext();
        }

        return $codigos;
    }

    public static function buscarPractica($categoria, $tema, $patologia, $id_nomenclador_detalle, $grupo_etareo, $sexo, $tipo_nomenclador="") {

        if ($sexo == 'F') {
            $condicion_sexo = "AND F=TRUE";
        } elseif ($sexo == 'M') {
            $condicion_sexo = "AND M=TRUE";
        } else {
            $condicion_sexo = "";
        }
        if($tipo_nomenclador!=""){
            $where .= " AND tipo_nomenclador='".strtoupper($tipo_nomenclador)."' ";
        }

        $categoria_etaria = $grupo_etareo['categoria'];

        $codigo = $categoria . " " . $tema;
        $sql_diagnosticos = "SELECT *
                             FROM facturacion.nomenclador 
                             WHERE id_nomenclador_detalle='$id_nomenclador_detalle'
                               $condicion_sexo  
                               AND codigo='$codigo'
                               AND diagnostico='$patologia'
                               ".$where."
                            ";
        $res_diagnosticos = sql($sql_diagnosticos) or fin_pagina();

        if (!$res_diagnosticos->EOF) {
            if ($grupo_etareo['estaembarazada']) {
                if ($res_diagnosticos->fields['embarazada'] > 0) {
                    $datos['precio'] = $res_diagnosticos->fields['embarazada'];
                    $datos['grupo_precio'] = 'embarazada';
                } else {
                    $datos['precio'] = $res_diagnosticos->fields[$categoria_etaria];
                    $datos['grupo_precio'] = $categoria_etaria;
                }
            } else {
                $datos['precio'] = $res_diagnosticos->fields[$categoria_etaria];
                $datos['grupo_precio'] = $categoria_etaria;
            }
            $datos['id_nomenclador'] = $res_diagnosticos->fields['id_nomenclador'];
            $datos[1] = $datos['precio'];
            $datos[2] = 'nuevo';
            $datos[0] = $datos['id_nomenclador'];
            $datos[7] = $res_diagnosticos->fields['tipo_nomenclador'];
            $datos['codigo'] = $res_diagnosticos->fields['codigo'] . " " . $res_diagnosticos->fields['diagnostico'];
            $datos[3] = $datos['codigo'];
        } else {
            $datos['id_nomenclador'] = 0;
            $datos['precio'] = 0;
            $datos[1] = 0;
            $datos[0] = 0;
            $datos[7] = 0;
        }

        return $datos;
    }

}

class NomencladorDetalleCDB {

    private $idNomencladorDetalle;
    private $descripcion;
    private $fechaDesde;
    private $fechaHasta;
    private $modoFacturacion;
    private $usuario;
    private $fechaModificacion;

    public static function Schema() {
        $esquema = array("schema" => "facturacion", "tabla" => "nomenclador_detalle", "alias" => "nd");
        return $esquema;
    }

    public static function Propiedades() {
        $propiedades = array(
            'id_nomenclador_detalle' => array('field' => 'id_nomenclador_detalle', 'getter' => 'getIdNomencladorDetalle', 'setter' => 'setIdNomencladorDetalle'),
            'descripcion' => array('field' => 'descripcion', 'getter' => 'getDescripcion', 'setter' => 'setDescripcion'),
            'fechaDesde' => array('field' => 'fecha_desde', 'getter' => 'getFechaDesde', 'setter' => 'setFechaDesde'),
            'fechaHasta' => array('field' => 'fecha_hasta', 'getter' => 'getFechaHasta', 'setter' => 'setFechaHasta'),
            'modoFacturacion' => array('field' => 'modo_facturacion', 'getter' => 'getModoFacturacion', 'setter' => 'setModoFacturacion'),
            'usuario' => array('field' => 'usuario', 'getter' => 'getUsuario', 'setter' => 'setUsuario'),
            'fechaModificacion' => array('field' => 'fecha_modificacion', 'getter' => 'getFechaModificacion', 'setter' => 'setFechaModificacion')
        );
        return $propiedades;
    }

    function getIdNomencladorDetalle() {
        return $this->idNomencladorDetalle;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getFechaDesde() {
        return $this->fechaDesde;
    }

    function getFechaHasta() {
        return $this->fechaHasta;
    }

    function getModoFacturacion() {
        return $this->modoFacturacion;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    function setIdNomencladorDetalle($idNomencladorDetalle) {
        $this->idNomencladorDetalle = $idNomencladorDetalle;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setFechaDesde($fechaDesde) {
        $this->fechaDesde = $fechaDesde;
    }

    function setFechaHasta($fechaHasta) {
        $this->fechaHasta = $fechaHasta;
    }

    function setModoFacturacion($modoFacturacion) {
        $this->modoFacturacion = $modoFacturacion;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }

}

class NomencladorDetalleColeccion {

    public static function buscar($where = array(), $orden = array(), $limit = array()) {
        $sql = NomencladorDetalle::getSQlSelect(NomencladorDetalle::Schema(), NomencladorDetalle::Propiedades(), array("*"), $where, $orden, $limit);
        $result = sql($sql);
        $codigos = array();
        while (!$result->EOF) {
            $codigo = new NomencladorDetalle();
            $codigo->construirResult($result->fields);
            $codigos[] = $codigo;
            $result->MoveNext();
        }
        return $codigos;
    }

}

?>