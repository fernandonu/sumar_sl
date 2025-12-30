<?
require_once ("../../config.php");
require_once ("funciones_csi.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

limpiar_tablas('ninios_1_2_anio','hist_ninios_1_2_anio');

function busqueda_pres_madre($descripcion,$st_codigo,$st_fecha,$st_cuie,$tipo_linea,$fecha_desde,$fecha_hasta,$cuie){
 
 $grupo=strtoupper(substr($st_codigo,0,2));
 $codigo=strtoupper(substr($st_codigo,2,4));
 
 if ($cuie=="todos") {
     $sql_1="SELECT comprobante.id_smiafiliados,prestacion.fecha_prestacion,comprobante.cuie,
      prestacion.id_prestacion,nomenclador.codigo,nomenclador.grupo,nomenclador.descripcion,prestacion.diagnostico,factura.id_factura
      from facturacion.prestacion 
      inner join facturacion.comprobante on prestacion.id_comprobante=comprobante.id_comprobante
      inner join facturacion.nomenclador on nomenclador.id_nomenclador=prestacion.id_nomenclador
      inner join facturacion.factura on factura.id_factura=comprobante.id_factura
      where nomenclador.codigo='$codigo' 
      and nomenclador.grupo='$grupo' 
      and nomenclador.descripcion='$descripcion'
      and prestacion.fecha_prestacion between '$fecha_desde' and '$fecha_hasta'
      
      --tomo la edad que tiene el benefic. al momento de ls pestacion.
      --del grupo de 1 a 2 año 
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))>=1 
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))<=2
      and comprobante.id_smiafiliados is not null
      order by comprobante.id_smiafiliados,prestacion.fecha_prestacion DESC";
  }
  else {
    $sql_1="SELECT comprobante.id_smiafiliados,prestacion.fecha_prestacion,comprobante.cuie,
      prestacion.id_prestacion,nomenclador.codigo,nomenclador.grupo,nomenclador.descripcion,prestacion.diagnostico,factura.id_factura
      from facturacion.prestacion 
      inner join facturacion.comprobante on prestacion.id_comprobante=comprobante.id_comprobante
      inner join facturacion.nomenclador on nomenclador.id_nomenclador=prestacion.id_nomenclador
      inner join facturacion.factura on factura.id_factura=comprobante.id_factura
      where nomenclador.codigo='$codigo' 
      and nomenclador.grupo='$grupo' 
      and nomenclador.descripcion='$descripcion'
      and prestacion.fecha_prestacion between '$fecha_desde' and '$fecha_hasta'
      
      --tomo la edad que tiene el benefic. al momento de ls pestacion.
      --del grupo de 1 a 2 año 
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))>=1 
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))<=2
      and comprobante.id_smiafiliados is not null
      and comprobante.cuie='$cuie'
      order by comprobante.id_smiafiliados,prestacion.fecha_prestacion DESC";
  }
  
$res_sql=sql($sql_1,"No se pudo ejecutar la consulta de busqueda de prestaciones madres") or fin_pagina();
$res_sql->movefirst();

if ($res_sql->recordcount() > 0){
  
  while (!$res_sql->EOF) {
        $sql_2="select nextval ('cobertura_efectiva.ninios_1_2_anio_id_seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla de ninios entre 1 y 2 años");
        $id=$res_sql_2->fields['id'];
        
        $id_smiafiliados=$res_sql->fields['id_smiafiliados'];
        $fecha=$res_sql->fields['fecha_prestacion'];
        $cuie=$res_sql->fields['cuie'];
        $id_prestacion=$res_sql->fields['id_prestacion'];
        
        $grupo=$res_sql->fields['grupo'];
        $codigo=$res_sql->fields['codigo'];
        $diagnostico=$res_sql->fields['diagnostico'];
        $descripcion=$res_sql->fields['descripcion'];
        $id_factura=$res_sql->fields['id_factura'];
              
        if (!se_encuentra($id_smiafiliados,$tipo_linea,'ninios_1_2_anio')) {
          $sql_2="SELECT nextval ('cobertura_efectiva.ninios_1_2_anio_id_seq') as id";
          $res_sql_2=sql($sql_2,"error al traer el id de la tabla de ninios menores de 1 anio");
          $id=$res_sql_2->fields['id'];
          
          $sql_insert="INSERT into cobertura_efectiva.ninios_1_2_anio
                      (id,tipo_linea,id_smiafiliados,$st_codigo,$st_fecha,$st_cuie)
                      VALUES
                      ($id,'$tipo_linea',$id_smiafiliados,1,'$fecha','$cuie')";
          $res_insert=sql($sql_insert,"Error al insertar el registro") or fin_pagina();
          
          $sql_2="SELECT nextval ('cobertura_efectiva.hist_ninios_1_2_anio_id_seq') as id";
          $res_sql_2=sql($sql_2,"error al traer el id de la tabla historica de ninios menores de 1 anio");
          $id_hist=$res_sql_2->fields['id'];
          
          $sql_insert="INSERT into cobertura_efectiva.hist_ninios_1_2_anio
                      (id,id_hist,id_prestacion,grupo,codigo,descripcion,diagnostico,fecha_prestacion,cuie,id_factura)
                      VALUES
                      ($id_hist,$id,$id_prestacion,'$grupo','$codigo','$descripcion','$diagnostico','$fecha','$cuie',$id_factura)";
          $res_insert=sql($sql_insert,"Error al insertar el registro en el historial") or fin_pagina();
        } 
        else {
          $sql_update="UPDATE cobertura_efectiva.ninios_1_2_anio
                       SET $st_codigo=$st_codigo+1,
                       $st_fecha='$fecha',
                       $st_cuie='$cuie'
                        WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
          $res_update=sql($sql_update,"No se pudo actualizar el registro") or fin_pagina();
          
          $sql_consulta="SELECT id from cobertura_efectiva.ninios_1_2_anio 
                          WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
          $res_consulta=sql($sql_consulta) or fin_pagina();
          $id=$res_consulta->fields['id'];
          
          $sql_2="SELECT nextval ('cobertura_efectiva.hist_ninios_1_2_anio_id_seq') as id";
          $res_sql_2=sql($sql_2,"error al traer el id de la tabla historica de ninios menores de 1 anio");
          $id_hist=$res_sql_2->fields['id'];
          
          $sql_insert="INSERT into cobertura_efectiva.hist_ninios_1_2_anio
                      (id,id_hist,id_prestacion,grupo,codigo,descripcion,diagnostico,fecha_prestacion,cuie,id_factura)
                      VALUES
                      ($id_hist,$id,$id_prestacion,'$grupo','$codigo','$descripcion','$diagnostico','$fecha','$cuie',$id_factura)";
          $res_insert=sql($sql_insert,"Error al insertar el registro en el historial") or fin_pagina();
          
        }
  $res_sql->Movenext();
    }
  } 
}//funcion



//CATEGORIA I 
if ($linea_cuidado=='Control de Salud'){
//BUSQUEDA DE PRESTACIONES MADRES - LINEA DE CUIDADO: CONTROL DE SALUD
busqueda_pres_madre('Pediátrica de 1 a 6 años (Grupo Niño 0 a 5 años  Cuidado de la Salud)','ctc001a97','fecha_p1','cuie_p1','Control de Salud',$fecha_desde,$fecha_hasta,$cuie);

//BUSQUEDA DE PRACTICAS DERIVADAS
busqueda_pres_compl('Consulta  buco-dental en salud en niños menores de 6 años (Grupo Niño 0 a 5 años  Cuidado de la Salud)','ctc010a97','fecha_p2','cuie_p2','Control de Salud',$fecha_desde,$fecha_hasta,'ninios_1_2_anio','hist_ninios_1_2_anio','fecha_p1'); 

busqueda_pres_compl('Dosis aplicada de vacuna triple viral en niños menores de 6 años  (Grupo Niño 0 a 5 años  Cuidado de la Salud)','imv001a98','fecha_p3','cuie_p3','Control de Salud',$fecha_desde,$fecha_hasta,'ninios_1_2_anio','hist_ninios_1_2_anio','fecha_p1');

busqueda_pres_compl('Dosis aplicada de vacuna neumococo conjugada  (Grupo Niño 0 a 5 años  Cuidado de la Salud)','imv015a98','fecha_p4','cuie_p4','Control de Salud',$fecha_desde,$fecha_hasta,'ninios_1_2_anio','hist_ninios_1_2_anio','fecha_p1');

busqueda_pres_compl('Dosis aplicada de inmunización para Hepatitis A en niños de 12 meses o actualización de esquema (Grupo Niño 0 a 5 años  Cuidado de la Salud)','imv005a98','fecha_p5','cuie_p5','Control de Salud',$fecha_desde,$fecha_hasta,'ninios_1_2_anio','hist_ninios_1_2_anio','fecha_p1');

set_valores('ninios_1_2_anio','Control de Salud',1);
};

if ($linea_cuidado=='Infeccion respiratoria Aguda') {

//BUSQUEDA DE PRESTACIONES MADRES - LINEA DE CUIDADO: INFECCION RESPIRATORIA AGUDA
busqueda_pres_madre('Internación abreviada SBO (Prehospitalización en ambulatorio) (Grupo Niño 0 a 5 años  Cuidado de la Salud)','ite001r78','fecha_p6','cuie_p6','Infeccion respiratoria Aguda',$fecha_desde,$fecha_hasta,$cuie);
//BUSQUEDA DE PRACTICAS DERIVADAS
busqueda_pres_compl('Atención ambulatoria de infección respiratoria aguda en niños menores de 6 años (inicial) (Grupo Niño 0 a 5 años  Cuidado de la Salud)','ctc001r78','fecha_p7','cuie_p7','Infeccion respiratoria Aguda',$fecha_desde,$fecha_hasta,'ninios_1_2_anio','hist_ninios_1_2_anio','fecha_p6');
busqueda_pres_compl('Atención ambulatoria de infección respiratoria aguda en niños menores de 6 años (ulterior) (Grupo Niño 0 a 5 años  Cuidado de la Salud)','ctc002r78','fecha_p8','cuie_p8','Infeccion respiratoria Aguda',$fecha_desde,$fecha_hasta,'ninios_1_2_anio','hist_ninios_1_2_anio','fecha_p6');

set_valores('ninios_1_2_anio','Infeccion respiratoria Aguda',1);
};

if ($linea_cuidado=='Comunidad'){
//BUSQUEDA DE PRESTACIONES MADRES - LINEA DE CUIDADO: COMUNIDAD
busqueda_pres_madre('Búsqueda activa de niños con abandono de controles, por agente sanitario y personal de salud.  (Grupo Niño 0 a 5 años  Cuidado de la Salud)','caw003a98','fecha_p9','cuie_p9','Comunidad',$fecha_desde,$fecha_hasta,$cuie);
//BUSQUEDA DE PRACTICAS DERIVADAS
busqueda_pres_compl('Pediátrica en menores de 1 año (Grupo Niño 0 a 5 años  Cuidado de la Salud)','ctc001a97_1','fecha_p10','cuie_p10','Comunidad',$fecha_desde,$fecha_hasta,'ninios_1_2_anio','hist_ninios_1_2_anio','fecha_p9');

set_valores('ninios_1_2_anio','Comunidad',1);

};

$accion="Se Creo la tabla cobertura_efectiva";
$link=encode_link("crear_bases_datos.php", array("accion"=>$accion,"tabla_base_1"=>$grupoetareo,"linea_cuidado_1"=>$linea_cuidado,"parametros_csi"=>$parametros_csi));?>
<script>location.href='<?=$link?>' </script>

