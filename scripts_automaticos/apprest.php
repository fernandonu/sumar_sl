<?php
define(LIB_DIR, dirname(__FILE__)."/../lib");                          // Librerias del sistema
require_once(LIB_DIR."/adodb/adodb.inc.php");
require_once(LIB_DIR."/adodb/adodb-pager.inc.php");
/*
  listar todos los posts o solo uno
 */
//echo "ok";
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['id']))
    {
        $db_type = 'postgres8'; // Tipo de base de datos.
        $db_host = 'localhost'; // Host para desarrollo.
        $db_user = 'postgres'; // Usuario.
        $db_password = ''; // Contrase?a.
        $db_name = 'sumar_produccion';
        $db_schemas = array(
          "nacer"
        );
       
        $db = &ADONewConnection($db_type) or die("Error al conectar a la base de datos");
        $db->Connect($db_host, $db_user, $db_password, $db_name);
        $db->cacheSecs = 3600;
      //Mostrar un post
        $id=$_GET['id'];
//        $sql_tmp="SELECT * FROM matriculacion.persona WHERE id_persona='$id'";
      $sql_tmp="SELECT * FROM nacer.efe_conv where cuie='$id'";
      $sql=$db->Execute($sql_tmp) or die("Error borrando las sesiones\n");
//      header("HTTP/1.1 200 OK");
      while ($fila=$sql->fetchRow()) {
        $array=array("cuie"=>$fila['cuie'],
                      "nombre"=>$fila['nombre']);
        echo json_encode ($array);
        
      };
      
//      echo json_encode(  $sql->fields["cuie"]  );
      unset($sql);
      exit();
    }
}


//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
?>