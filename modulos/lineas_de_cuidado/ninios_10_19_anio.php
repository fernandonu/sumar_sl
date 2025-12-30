<?
require_once ("../../config.php");
require_once ("funciones_csi.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

limpiar_tablas('adolescente_10_19_anio','hist_adolescente_10_19_anio');


function busqueda_pres_madre($descripcion,$st_codigo,$st_fecha,$st_cuie,$tipo_linea,$fecha_desde,$fecha_hasta,$cuie){
 
 $grupo=strtoupper(substr($st_codigo,0,2));
 $codigo=strtoupper(substr($st_codigo,2,4));
 $diagnostico=strtoupper(substr($st_codigo,6,3));
 
 if ($tipo_linea=='Obesidad') $filtro="and (prestacion.diagnostico='$diagnostico' or prestacion.diagnostico='T82')";
 elseif ($tipo_linea=='Consumo excesivo de Alcohol') $filtro="and (prestacion.diagnostico='$diagnostico' or prestacion.diagnostico='P023' or prestacion.diagnostico='P024')";
 else $filtro="and prestacion.diagnostico='$diagnostico'";
 
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
      $filtro
      --tomo la edad que tiene el benefic. al momento de la prestacion.
      --del grupo de 10 a 19 año 
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))>=10 
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))<=19
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
      $filtro
      --tomo la edad que tiene el benefic. al momento de la prestacion.
      --del grupo de 10 a 19 año 
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))>=10 
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))<=19
      and comprobante.id_smiafiliados is not null
      and comprobante.cuie='$cuie' 
      order by comprobante.id_smiafiliados,prestacion.fecha_prestacion DESC";
  }
  
$res_sql=sql($sql_1,"No se pudo ejecutar la consulta de busqueda de prestaciones madres") or fin_pagina();
$res_sql->movefirst();

if ($res_sql->recordcount() > 0){
  
  while (!$res_sql->EOF) {
               
        $id_smiafiliados=$res_sql->fields['id_smiafiliados'];
        $fecha=$res_sql->fields['fecha_prestacion'];
        $cuie=$res_sql->fields['cuie'];
        $id_prestacion=$res_sql->fields['id_prestacion'];
        
        $grupo=$res_sql->fields['grupo'];
        $codigo=$res_sql->fields['codigo'];
        $diagnostico=$res_sql->fields['diagnostico'];
        $descripcion=$res_sql->fields['descripcion'];
        $id_factura=$res_sql->fields['id_factura'];
              
        if (!se_encuentra($id_smiafiliados,$tipo_linea,'adolescente_10_19_anio')) {
        $sql_2="SELECT nextval ('cobertura_efectiva.adolescente_10_19_anio_id_seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla de ninios menores de 1 anio");
        $id=$res_sql_2->fields['id'];
        
        $sql_insert="INSERT into cobertura_efectiva.adolescente_10_19_anio
                    (id,tipo_linea,id_smiafiliados,$st_codigo,$st_fecha,$st_cuie)
                    VALUES
                    ($id,'$tipo_linea',$id_smiafiliados,1,'$fecha','$cuie')";
        $res_insert=sql($sql_insert,"Error al insertar el registro") or fin_pagina();
        
        $sql_2="SELECT nextval ('cobertura_efectiva.hist_adolescente_10_19_anio_id_seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla historica de ninios menores de 1 anio");
        $id_hist=$res_sql_2->fields['id'];
        
        $sql_insert="INSERT into cobertura_efectiva.hist_adolescente_10_19_anio
                    (id,id_hist,id_prestacion,grupo,codigo,descripcion,diagnostico,fecha_prestacion,cuie,id_factura)
                    VALUES
                    ($id_hist,$id,$id_prestacion,'$grupo','$codigo','$descripcion','$diagnostico','$fecha','$cuie',$id_factura)";
        $res_insert=sql($sql_insert,"Error al insertar el registro en el historial") or fin_pagina();
        } 
        else {
        $sql_update="UPDATE cobertura_efectiva.adolescente_10_19_anio
                     SET $st_codigo=$st_codigo+1,
                     $st_fecha='$fecha',
                     $st_cuie='$cuie'
                      WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
        $res_update=sql($sql_update,"No se pudo actualizar el registro") or fin_pagina();
        
        $sql_consulta="SELECT id from cobertura_efectiva.adolescente_10_19_anio 
                        WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
        $res_consulta=sql($sql_consulta) or fin_pagina();
        $id=$res_consulta->fields['id'];
        
        $sql_2="SELECT nextval ('cobertura_efectiva.hist_adolescente_10_19_anio_id_seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla historica de ninios menores de 1 anio");
        $id_hist=$res_sql_2->fields['id'];
        
        $sql_insert="INSERT into cobertura_efectiva.hist_adolescente_10_19_anio
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
if ($linea_cuidado=='Embarazo Adolescente'){
  //BUSQUEDA DE PRESTACIONES MADRES - LINEA DE CUIDADO: EMBARAZO ADOLESCENTE
  busqueda_pres_madre('Control prenatal  de 1ra.vez  (Grupo Embarazo/parto/puerperio de Bajo Riesgo)','ctc005w78','fecha_p1','cuie_p1','Embarazo Adolescente',$fecha_desde,$fecha_hasta,$cuie);
  //BUSQUEDA DE PRACTICAS DERIVADAS
  busqueda_pres_compl('Ulterior de control  prenatal.   (Grupo Embarazo/parto/puerperio de Bajo Riesgo)','ctc006w78','fecha_p2','cuie_p2','Embarazo Adolescente',$fecha_desde,$fecha_hasta,'adolescente_10_19_anio','hist_adolescente_10_19_anio','fecha_p1');

  set_valores('adolescente_10_19_anio','Embarazo Adolescente',1);
};

if ($linea_cuidado=='Obesidad'){

  //BUSQUEDA DE PRESTACIONES MADRES - LINEA DE CUIDADO: OBESIDAD
  busqueda_pres_madre('Obesidad (inicial) (Grupo Adolecentes 10 a 19 años)','ctc001t79','fecha_p3','cuie_p3','Obesidad',$fecha_desde,$fecha_hasta,$cuie);
  busqueda_pres_madre('Obesidad (inicial) (Grupo Adolecentes 10 a 19 años)','ctc001t82','fecha_p4','cuie_p4','Obesidad',$fecha_desde,$fecha_hasta,$cuie);
  
  //BUSQUEDA DE PRACTICAS DERIVADAS
  busqueda_pres_compl_2('Obesidad (ulterior) (Grupo Adolecentes 10 a 19 años)','ctc002t79','fecha_p5','cuie_p5','Obesidad',$fecha_desde,$fecha_hasta,'adolescente_10_19_anio','hist_adolescente_10_19_anio','fecha_p3','fecha_p4');
  busqueda_pres_compl_2('Obesidad (ulterior) (Grupo Adolecentes 10 a 19 años)','ctc002t82','fecha_p6','cuie_p6','Obesidad',$fecha_desde,$fecha_hasta,'adolescente_10_19_anio','hist_adolescente_10_19_anio','fecha_p3','fecha_p4');
  busqueda_pres_compl_2('Promoción de hábitos saludables: salud bucal, educación alimentaria, pautas de higiene, trastornos de la alimentación. (Grupo Adolecentes 10 a 19 años)','tat011a98','fecha_p7','cuie_p7','Obesidad',$fecha_desde,$fecha_hasta,'adolescente_10_19_anio','hist_adolescente_10_19_anio','fecha_p3','fecha_p4');

  set_valores('adolescente_10_19_anio','Obesidad',1);
};

if ($linea_cuidado=='Sobrepeso'){

  //BUSQUEDA DE PRESTACIONES MADRES - LINEA DE CUIDADO: SOBREPESO
  busqueda_pres_madre('Sobrepeso (inicial) (Grupo Adolecentes 10 a 19 años)','ctc001t83','fecha_p8','cuie_p8','Sobrepeso',$fecha_desde,$fecha_hasta,$cuie);
  //BUSQUEDA DE PRACTICAS DERIVADAS
  busqueda_pres_compl('Sobrepeso (ulterior) (Grupo Adolecentes 10 a 19 años)','ctc002t83','fecha_p9','cuie_p9','Sobrepeso',$fecha_desde,$fecha_hasta,'adolescente_10_19_anio','hist_adolescente_10_19_anio','fecha_p8');
  busqueda_pres_compl('Promoción de hábitos saludables: salud bucal, educación alimentaria, pautas de higiene, trastornos de la alimentación. (Grupo Adolecentes 10 a 19 años)','tat011a98_1','fecha_p10','cuie_p10','Sobrepeso',$fecha_desde,$fecha_hasta,'adolescente_10_19_anio','hist_adolescente_10_19_anio','fecha_p8');

  set_valores('adolescente_10_19_anio','Sobrepeso',1);
};

if ($linea_cuidado=='Comunidad'){
  //BUSQUEDA DE PRESTACIONES MADRES - LINEA DE CUIDADO: COMUNIDAD 
  busqueda_pres_madre('Búsqueda activa de embarazadas adolescentes por agente sanitario y/o personal de Salud (Grupo Adolecentes 10 a 19 años)','caw004a98','fecha_p11','cuie_p11','Comunidad',$fecha_desde,$fecha_hasta,$cuie);
  //BUSQUEDA DE PRACTICAS DERIVADAS
  busqueda_pres_compl('Examen Periódico de Salud del adolescente (Grupo Adolecentes 10 a 19 años)','ctc001a97','fecha_p12','cuie_p12','Comunidad',$fecha_desde,$fecha_hasta,'adolescente_10_19_anio','hist_adolescente_10_19_anio','fecha_p11');

  set_valores('adolescente_10_19_anio','Comunidad',1);
};

if ($linea_cuidado=='Consumo excesivo de Alcohol'){
  //BUSQUEDA DE PRESTACIONES MADRES - LINEA DE CUIDADO: CONSUMO EXCESIVO DE ALCOHOL 
  busqueda_pres_madre('Seguimiento por consumo episódico excesivo de alcohol y/o otras sustancias psicoactivas (inicial) (Grupo Adolecentes 10 a 19 años)','ctc001p20','fecha_p13','cuie_p13','Consumo excesivo de Alcohol',$fecha_desde,$fecha_hasta,$cuie);
  busqueda_pres_madre('Seguimiento por consumo episódico excesivo de alcohol y/o otras sustancias psicoactivas (inicial) (Grupo Adolecentes 10 a 19 años)','ctc001p23','fecha_p14','cuie_p14','Consumo excesivo de Alcohol',$fecha_desde,$fecha_hasta,$cuie);
  busqueda_pres_madre('Seguimiento por consumo episódico excesivo de alcohol y/o otras sustancias psicoactivas (inicial) (Grupo Adolecentes 10 a 19 años)','ctc001p24','fecha_p15','cuie_p15','Consumo excesivo de Alcohol',$fecha_desde,$fecha_hasta,$cuie);
  
  //BUSQUEDA DE PRACTICAS DERIVADAS
  busqueda_pres_compl_3('Seguimiento por consumo episódico excesivo de alcohol y/o otras sustancias psicoactivas (ulterior) (Grupo Adolecentes 10 a 19 años)','ctc002p20','fecha_p16','cuie_p16','Consumo excesivo de Alcohol',$fecha_desde,$fecha_hasta,'adolescente_10_19_anio','hist_adolescente_10_19_anio','fecha_p13','fecha_p14','fecha_p15');
  busqueda_pres_compl_3('Seguimiento por consumo episódico excesivo de alcohol y/o otras sustancias psicoactivas (ulterior) (Grupo Adolecentes 10 a 19 años)','ctc002p23','fecha_p17','cuie_p17','Consumo excesivo de Alcohol',$fecha_desde,$fecha_hasta,'adolescente_10_19_anio','hist_adolescente_10_19_anio','fecha_p13','fecha_p14','fecha_p15');
  busqueda_pres_compl_3('Seguimiento por consumo episódico excesivo de alcohol y/o otras sustancias psicoactivas (ulterior) (Grupo Adolecentes 10 a 19 años)','ctc002p24','fecha_p18','cuie_p18','Consumo excesivo de Alcohol',$fecha_desde,$fecha_hasta,'adolescente_10_19_anio','hist_adolescente_10_19_anio','fecha_p13','fecha_p14','fecha_p15');

  set_valores('adolescente_10_19_anio','Consumo excesivo de Alcohol',1);
};


$accion="Se Creo la tabla cobertura_efectiva";
$link=encode_link("crear_bases_datos.php", array("accion"=>$accion,"tabla_base_1"=>$grupoetareo,"linea_cuidado_1"=>$linea_cuidado,"parametros_csi"=>$parametros_csi));?>
<script>location.href='<?=$link?>' </script>

