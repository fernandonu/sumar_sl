<?
require_once ("../../config.php");

$op=$_POST['op'];
$dni=$_POST['dni'];
$nombre=$_POST['nombre'];
$apellido=$_POST['apellido'];
$reg_lab=$_POST['reg_lab'];
$funcion=$_POST['funcion'];
$especialidad=$_POST['especialidad'];
$mantenimiento=$_POST['mantenimiento'];
$cuie=$_POST['cuie'];
$activo=($_POST['estado']=='activo')?'S':'N';
$id=$_POST['id'];
$dia_hoy=date("Y-m-d H:i:s");

if ($op=='alta'){
    
    $busq="SELECT * from personal.incentivos where dni='$dni' and cuie='$cuie'";
    $res_busq=sql($busq,"No se pudo ejecutar la consulta");
    if ($res_busq->RecordCount()>0) {$mensaje="Persona Empadronada en el Centro, revisar solo CONDICION";}
        else {
            $sql="INSERT into personal.incentivos (cuie,
                        dni,nombre,apellido,activo,regimen_laboral,
                        funcion,especialidad,fecha_carga,fecha_modificacion,mantenimiento) values
                        ('$cuie','$dni','$nombre','$apellido','$activo','$reg_lab','$funcion','$especialidad','$dia_hoy','$dia_hoy','$mantenimiento')";
            sql($sql,"Error en dar el Alta");
            $mensaje="Persona Ingresada Correctamente en el Centro de Salud";
        };
 };
 
if ($op=='modif'){
    $nombre=$_POST['nombre'];
    $apellido=$_POST['apellido'];
    $reg_lab=$_POST['reg_lab'];
    //$funcion=$_POST['funcion'];
    //$especialidad=$_POST['especialidad'];
    //$mantenimiento=$_POST['mantenimiento'];
    $activo=($_POST['estado']=='activo')?'S':'N';
    $id=$_POST['id'];
    $dni=$_POST['dni'];
    
    $sql="UPDATE personal.incentivos SET 
                dni='$dni',
                nombre='$nombre',
                apellido='$apellido',
                activo='$activo',
                regimen_laboral='$reg_lab',
                fecha_modificacion='$dia_hoy'
                where id_incentivos=$id";
                
    sql($sql,"Error en Modificar el registro");
    $mensaje="Persona Modificada Correctamente";

    
 };

 if ($op=='borrar'){
    
    $sql_del="DELETE from personal.incentivos where id_incentivos=$id";
    sql($sql_del,"Error al Borrar el Registro") or fin_pagina();
    $mensaje="Registro eliminado correctamente";
    
 };

 $mensaje_json= array ("mensaje" => $mensaje);
 echo json_encode($mensaje_json);
?>



 