<?php

abstract class consultasDB {

//    abstract protected static function Schema();
//
//    abstract protected static function Propiedades();

    public function construirResult($propiedades) {
        //$claseHija = get_called_class();
        //$propiedades = get_class_vars($claseHija);
        foreach ($propiedades as $propiedad => $valor) {
            $this->set($propiedad, $valor);
        }
    }

    /*
     * Funcion que determina cual es la clase "hija" 
     * que extendio esta clase abstracta
     */

    function get_called_class() {
        $objects = array();
        $traces = debug_backtrace();
        foreach ($traces as $trace) {
            if (isset($trace['object'])) {
                if (is_object($trace['object'])) {
                    $objects[] = $trace['object'];
                }
            }
        }
        if (count($objects)) {
            return get_class($objects[0]);
        }
    }

    public function get($fieldName) {
        $field = self::__getterCaller($fieldName);
        return($field);
    }

    public function set($fieldName, $value) {
        $propiedades = call_user_func(array(get_class($this), 'Propiedades'));
        foreach ($propiedades as $clave => $propiedad) {
            if ($propiedad['field'] === $fieldName) {
                self::__setterCaller($clave, $value);
                break;
            }
        }
    }

    public function __getterCaller($key) {
        $propiedades = call_user_func(array(get_class($this), 'Propiedades'));
        //        $propiedades = static::Propiedades();
        if (!is_null($propiedades[$key])) {
            $data = call_user_func_array(array($this, $propiedades[$key]["getter"]), array());
        } else {
            $data = null;
        }
        return($data);
    }

    public function __setterCaller($key, $value) {
        $propiedades = call_user_func(array(get_class($this), 'Propiedades'));

//$propiedades = static::Propiedades();
        if (!is_null($propiedades[$key])) {
            $data = call_user_func_array(array($this, $propiedades[$key]["setter"]), array($value));
        } else {
            $data = null;
        }
        return($data);
    }

    public function getFieldSelect($schema, $propiedades, $fieldList) {
        $query = array();

        if (count($fieldList) > 0) {

            foreach ($fieldList as $key) {
                if (!is_null($propiedades[$key])) {
                    if (strlen($propiedades[$key]["cast"]) > 0) {
                        $query[] = implode("", array($schema["alias"], ". ", $propiedades[$key]["field"], "::", $propiedades[$key]["cast"], " as ", $key));
                    } else {
                        $query[] = implode("", array($schema["alias"], ". ", $propiedades[$key]["field"], " as ", $key));
                    }
                } else {
                    $query[] = $key;
                }
            }
        } else {
            foreach (array_keys($propiedades) as $key) {
                if (strlen($propiedades[$key]["cast"]) > 0) {
                    $query[] = implode(" ", array($schema["alias"], ". ", $propiedades[$key]["field"], "::", $propiedades[$key]["cast"], " as ", $key));
                } else {
                    $query[] = implode(" ", array($schema["alias"], ". ", $propiedades[$key]["field"], " as ", $key));
                }
            }
        }
        return(implode(", \n", $query));
    }

    /* ! 
     *  \brief     Documentacion para metodo getFieldWhere.
     *  \details   Contruye las condiciones de la consulta usando los campos de propiedads
     *  \author    Gustavo Fernandez
     *  \date      28/07/2014
     *  \copyright GNU Public License.
     */

    public static function getFieldWhere($schema, $propiedades, $field) {

        $conditions = array();
        $key = key($field);
        if (!is_null($propiedades[$key])) {
            if ($field[$key]["nexo"]) {
                $sqlWhere = " " . $field[$key]["nexo"] . " ";
            }

            $sqlWhere.= $schema["alias"] . "." . $propiedades[$key]["field"];
            //$sqlWhere = implode(" ", array($field));

            if (count($field[$key]["cast"]) > 0) {
                $sqlWhere .= implode(" ", array("::", $field[$key]["cast"], " "));
            }

            $sqlWhere .=" " . implode(" ", array($field[$key]["condition"])) . " ";

            if ($field[$key]["comillas"]) {
                $sqlWhere .="'";
            }

            $sqlWhere .= implode(" ", array($field[$key]["value"]));

            if ($field[$key]["comillas"]) {
                $sqlWhere .="'";
            }


            if (count($field[$key]["optValue"]) > 1) {
                $sqlWhere .= implode(" ", array(" ", "AND", $field[$key]["optValue"]));
            }
        }
        return($sqlWhere);
    }

    public static function getFieldOrder($orden) {
        $sizeOrden = sizeof($orden);
        if ($sizeOrden) {
            $sql.=" ORDER BY ";
            $ordercatch = new CachingIterator(new ArrayIterator($orden));
            foreach ($ordercatch as $unordenamiento) {
                $sql.=$unordenamiento['campo'] . " " . $unordenamiento['criterio'];
                if ($ordercatch->hasNext()) {
                    $sql.=", ";
                }
            }
        }
        return($sql);
    }

    /* ! 
     *  \brief     Documentacion para metodo getSQLSelect.
     *  \details   Contruye la consulta select
     *  \author    Gustavo Fernandez
     *  \date      28/07/2014
     *  \copyright GNU Public License.
     */

    public static function getSQLSelect($schema, $propiedades, $fieldList, $condiciones = null, $order = null, $limit = null) {

        $sql = "SELECT " . self::getFieldSelect($schema, $propiedades, $fieldList);
        $sql .= " FROM " . $schema["schema"] . "." . $schema["tabla"] . " " . $schema["alias"];

        //discrimina si son condiciones de atributo, o normales
        if (!is_null($condiciones)) {
            foreach ($condiciones as $condicion) {
                if (is_array($condicion)) {
                    $where[] = self::getFieldWhere($schema, $propiedades, $condicion);
                } else {
                    $where[] = $condicion;
                }
            }
        }

        if (count($where) > 0) {
            $sql .=" WHERE " . implode(" ", $where);
        }

        $sql .= self::getFieldOrder($order);

        if ($limit != "") {
            if (is_array($limit)) {
                if ($limit['limit'] != "") {
                    $sql.= " LIMIT " . $limit['limit'];
                    if ($limit['offset'] != "") {
                        $sql.= " OFFSET " . $limit['offset'];
                    }
                }
            } else {
                $sql.= " LIMIT " . $limit;
            }
        }

        return($sql);
    }

    function getJsonData() {
        $propiedades = call_user_func(array(get_class($this), 'Propiedades'));
        $data = array();
        foreach ($propiedades as $key => $valor) {
            $dato = call_user_func_array(array($this, $valor["getter"]), array());
            if (!is_null($dato)) {
                if (!is_object($dato)) {
                    $data[$key] = utf8_encode($dato);
                } else {
                    $data[$key] = $dato->getJsonData();
                }
            }
        }
        return $data;
    }

}
