<?
require_once ("../../config.php");
require_once ("funciones_csi.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

limpiar_tablas('ninios_6_9_anio','hist_ninios_6_9_anio');

function busqueda_pres_madre($descripcion,$st_codigo,$st_fecha,$st_cuie,$tipo_linea,$fecha_desde,$fecha_hasta,$cuie){
 
 $grupo=strtoupper(substr($st_codigo,0,2));
 $codigo=strtoupper(substr($st_codigo,2,4));
 $diagnostico=strtoupper(substr($st_codigo,6,3));
 
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
      
      --tomo la edad que tiene el benefic. al momento de ls pestacion.
      --del grupo de 6 a 9 año 
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))>=6 
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))<=9
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
      and prestacion.diagnostico='$diagnostico'
      
      --tomo la edad que tiene el benefic. al momento de ls pestacion.
      --del grupo de 6 a 9 año 
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))>=6 
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))<=9
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
              
        if (!se_encuentra($id_smiafiliados,$tipo_linea,'ninios_6_9_anio')) {
        $sql_2="SELECT nextval ('cobertura_efectiva.ninios_6_9_anio_id_seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla de ninios menores de 1 anio");
        $id=$res_sql_2->fields['id'];
        
        $sql_insert="INSERT into cobertura_efectiva.ninios_6_9_anio
                    (id,tipo_linea,id_smiafiliados,$st_codigo,$st_fecha,$st_cuie)
                    VALUES
                    ($id,'$tipo_linea',$id_smiafiliados,1,'$fecha','$cuie')";
        $res_insert=sql($sql_insert,"Error al insertar el registro") or fin_pagina();
        
        $sql_2="SELECT nextval ('cobertura_efectiva.hist_ninios_6_9_anio_id_seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla historica de ninios menores de 1 anio");
        $id_hist=$res_sql_2->fields['id'];
        
        $sql_insert="INSERT into cobertura_efectiva.hist_ninios_6_9_anio
                    (id,id_hist,id_prestacion,grupo,codigo,descripcion,diagnostico,fecha_prestacion,cuie,id_factura)
                    VALUES
                    ($id_hist,$id,$id_prestacion,'$grupo','$codigo','$descripcion','$diagnostico','$fecha','$cuie',$id_factura)";
        $res_insert=sql($sql_insert,"Error al insertar el registro en el historial") or fin_pagina();
        } 
        else {
        $sql_update="UPDATE cobertura_efectiva.ninios_6_9_anio
                     SET $st_codigo=$st_codigo+1,
                     $st_fecha='$fecha',
                     $st_cuie='$cuie'
                      WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
        $res_update=sql($sql_update,"No se pudo actualizar el registro") or fin_pagina();
        
        $sql_consulta="SELECT id from cobertura_efectiva.ninios_6_9_anio 
                        WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
        $res_consulta=sql($sql_consulta) or fin_pagina();
        $id=$res_consulta->fields['id'];
        
        $sql_2="SELECT nextval ('cobertura_efectiva.hist_ninios_6_9_anio_id_seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla historica de ninios menores de 1 anio");
        $id_hist=$res_sql_2->fields['id'];
        
        $sql_insert="INSERT into cobertura_efectiva.hist_ninios_6_9_anio
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
if ($linea_cuidado=='Asma') {
//BUSQUEDA DE PRESTACIONES MADRES - LINEA DE CUIDADO: ASMA
busqueda_pres_madre('Asma bronquial ( inicial) (Grupo Niños de 6 a 9 Años)','ctc001r96','fecha_p1','cuie_p1','Asma',$fecha_desde,$fecha_hasta,$cuie);
//BUSQUEDA DE PRACTICAS DERIVADAS
busqueda_pres_compl('Asma bronquial (ulterior) (Grupo Niños de 6 a 9 Años)','ctc002r96','fecha_p2','cuie_p2','Asma',$fecha_desde,$fecha_hasta,'ninios_6_9_anio','hist_ninios_6_9_anio','fecha_p1');

set_valores('ninios_6_9_anio','Asma',1);
};

set_fechas('ninios_6_9_anio');
set_valores('ninios_6_9_anio','Asma',1);
};

if ($linea_cuidado=='Sobrepeso')
//BUSQUEDA DE PRESTACIONES MADRES - LINEA DE CUIDADO: SOBREPESO
busqueda_pres_madre('Sobrepeso ( inicial) (Grupo Niños de 6 a 9 Años)','ctc001t83','fecha_p3','cuie_p3','Sobrepeso',$fecha_desde,$fecha_hasta,$cuie);
//BUSQUEDA DE PRACTICAS DERIVADAS
busqueda_pres_compl('Sobrepeso ( ulterior) (Grupo Niños de 6 a 9 Años)','ctc002t83','fecha_p4','cuie_p4','Sobrepeso',$fecha_desde,$fecha_hasta,'ninios_6_9_anio','hist_ninios_6_9_anio','fecha_p3');

busqueda_pres_compl('Promoción de hábitos saludables: salud bucal, educación alimentaria, pautas de higiene. (Grupo Niños de 6 a 9 Años)','tat011a98','fecha_p5','cuie_p5','Sobrepeso',$fecha_desde,$fecha_hasta,'ninios_6_9_anio','hist_ninios_6_9_anio','fecha_p3');

set_valores('ninios_6_9_anio','Sobrepeso',1);
};

$accion="Se Creo la tabla cobertura_efectiva";
$link=encode_link("crear_bases_datos.php", array("accion"=>$accion,"tabla_base_1"=>$grupoetareo,"linea_cuidado_1"=>$linea_cuidado,"parametros_csi"=>$parametros_csi));?>
<script>location.href='<?=$link?>' </script>

