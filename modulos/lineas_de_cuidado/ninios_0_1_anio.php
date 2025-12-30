<?
require_once ("../../config.php");
require_once ("funciones_csi.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

limpiar_tablas('ninios_0_1_anio','hist_ninios_0_1_anio');


function busqueda_pres_madre($grupo_discriminado,$descripcion,$st_codigo,$st_fecha,$st_cuie,$tipo_linea,$fecha_desde,$fecha_hasta,$cuie){
 
 $grupo=strtoupper(substr($st_codigo,0,2));
 $codigo=strtoupper(substr($st_codigo,2,4));
 
 if($grupo_discriminado=='Excluir') 
  $filtro="and (extract (month from (age('$fecha_hasta',afifechanac)))>=10)";
 else $filtro='';
 
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
    --del grupo de 0 a 1 año me quedo con todo el espectro de edad no con la cota superior
    --en todos casos seria la cota inferior la que tiene mas chance de completar la linea
    and extract (year from (age(fecha_prestacion,fecha_nacimiento)))<2 
    and comprobante.id_smiafiliados is not null
    
    order by comprobante.id_smiafiliados,prestacion.fecha_prestacion DESC";
  }
  
  else {
    $sql_1="SELECT comprobante.id_smiafiliados,prestacion.fecha_prestacion,comprobante.cuie,prestacion.id_prestacion,nomenclador.codigo,nomenclador.grupo,nomenclador.descripcion,prestacion.diagnostico,factura.id_factura
      from facturacion.prestacion 
      inner join facturacion.comprobante on prestacion.id_comprobante=comprobante.id_comprobante
      inner join facturacion.nomenclador on nomenclador.id_nomenclador=prestacion.id_nomenclador
      inner join facturacion.factura on factura.id_factura=comprobante.id_factura  
      where nomenclador.codigo='$codigo' 
      and nomenclador.grupo='$grupo' 
      and nomenclador.descripcion='$descripcion'
      and prestacion.fecha_prestacion between '$fecha_desde' and '$fecha_hasta'
      
      --tomo la edad que tiene el benefic. al momento de ls pestacion.
      --del grupo de 0 a 1 año me quedo con todo el espectro de edad no con la cota superior
      --en todos casos seria la cota inferior la que tiene mas chance de completar la linea
      and extract (year from (age(fecha_prestacion,fecha_nacimiento)))<2 
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
              
        if (!se_encuentra($id_smiafiliados,$tipo_linea,'ninios_0_1_anio')) {
        $sql_2="SELECT nextval ('cobertura_efectiva.ninios_0_1_anio_id_seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla de ninios menores de 1 anio");
        $id=$res_sql_2->fields['id'];
        
        $sql_insert="INSERT into cobertura_efectiva.ninios_0_1_anio
                    (id,tipo_linea,id_smiafiliados,$st_codigo,$st_fecha,$st_cuie)
                    VALUES
                    ($id,'$tipo_linea',$id_smiafiliados,1,'$fecha','$cuie')";
        $res_insert=sql($sql_insert,"Error al insertar el registro") or fin_pagina();
        
        $sql_2="SELECT nextval ('cobertura_efectiva.hist_ninios_0_1_anio_id_seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla historica de ninios menores de 1 anio");
        $id_hist=$res_sql_2->fields['id'];
        
        $sql_insert="INSERT into cobertura_efectiva.hist_ninios_0_1_anio
                    (id,id_hist,id_prestacion,grupo,codigo,descripcion,diagnostico,fecha_prestacion,cuie,id_factura)
                    VALUES
                    ($id_hist,$id,$id_prestacion,'$grupo','$codigo','$descripcion','$diagnostico','$fecha','$cuie',$id_factura)";
        $res_insert=sql($sql_insert,"Error al insertar el registro en el historial") or fin_pagina();
        } 
        else {
        $sql_update="UPDATE cobertura_efectiva.ninios_0_1_anio
                     SET $st_codigo=$st_codigo+1,
                     $st_fecha='$fecha',
                     $st_cuie='$cuie'
                      WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
        $res_update=sql($sql_update,"No se pudo actualizar el registro") or fin_pagina();
        
        $sql_consulta="SELECT id from cobertura_efectiva.ninios_0_1_anio 
                        WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
        $res_consulta=sql($sql_consulta) or fin_pagina();
        $id=$res_consulta->fields['id'];
        
        $sql_2="SELECT nextval ('cobertura_efectiva.hist_ninios_0_1_anio_id_seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla historica de ninios menores de 1 anio");
        $id_hist=$res_sql_2->fields['id'];
        
        $sql_insert="INSERT into cobertura_efectiva.hist_ninios_0_1_anio
                    (id,id_hist,id_prestacion,grupo,codigo,descripcion,diagnostico,fecha_prestacion,cuie,id_factura)
                    VALUES
                    ($id_hist,$id,$id_prestacion,'$grupo','$codigo','$descripcion','$diagnostico','$fecha','$cuie',$id_factura)";
        $res_insert=sql($sql_insert,"Error al insertar el registro en el historial") or fin_pagina();
          
        }
  $res_sql->Movenext();
    }
  } 
}//funcion


if ($linea_cuidado=='Control de Salud'){
//CATEGORIA I Y II
//BUSQUEDA DE PRESTACIONES MADRES --------------------------------------
busqueda_pres_madre('Excluir','Pediátrica en menores de 1 año (Grupo Niño 0 a 5 años  Cuidado de la Salud)','ctc001a97','fecha_p1','cuie_p1','Control de Salud',$fecha_desde,$fecha_hasta,$cuie);
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (TA T002 A98)
busqueda_pres_compl('Encuentros para promoción de pautas alimentarias en embarazadas, puérperas y niños menores de 6 años. (Grupo Niño 0 a 5 años  Cuidado de la Salud)','tat002a98','fecha_p2','cuie_p2','Control de Salud',$fecha_desde,$fecha_hasta,'ninios_0_1_anio','hist_ninios_0_1_anio','fecha_p1');
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (TA T003 A98)
busqueda_pres_compl('Encuentros para promoción del desarrollo infantil, prevención de patolog. prevalentes en la infancia, conductas saludables, hábitos de higiene (Grupo Niño 0 a 5 años  Cuidado de la Salud)','tat003a98','fecha_p3','cuie_p3','Control de Salud',$fecha_desde,$fecha_hasta,'ninios_0_1_anio','hist_ninios_0_1_anio','fecha_p1');

set_valores('ninios_0_1_anio','Control de Salud',1);

};

if ($linea_cuidado=='Comunidad'){
//BUSQUEDA DE PRESTACIONES MADRES - comunidad (CA W003 A98) ----------------
busqueda_pres_madre('Incluir','Búsqueda activa de niños con abandono de controles, por agente sanitario y personal de salud.  (Grupo Niño 0 a 5 años  Cuidado de la Salud)','caw003a98','fecha_p17','cuie_p17','Comunidad',$fecha_desde,$fecha_hasta,$cuie);
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO - comunidad (CT C001 A97)
busqueda_pres_compl('Pediátrica en menores de 1 año (Grupo Niño 0 a 5 años  Cuidado de la Salud)','ctc001a97_1','fecha_p19','cuie_p19','Comunidad',$fecha_desde,$fecha_hasta,'ninios_0_1_anio','hist_ninios_0_1_anio','fecha_p17');

set_valores('ninios_0_1_anio','Comunidad',1);

};


/*
//CATEGORIA III
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (PR P021 A97)
busqueda_pres_compl('Otoemisiones acústicas para Detección temprana de hipoacusia en RN   (Grupo niños 0 a 5 años (Post Parto inmediato)) ','prp021a97','fecha_p4','cuie_p4','Control de Salud');
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (PR P017 A97/A46)
busqueda_pres_compl('Pesquisa de la retinopatía del prematuro: Oftalmoscopìa binocular indirecta (OBI) a todo niño de riesgo   (Grupo niños 0 a 5 años (Post Parto inmediato)) ','prp017a97','fecha_p5','cuie_p5','Control de Salud');
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (IG R005 L30/A98)
busqueda_pres_compl('Ecografía bilateral de caderas (menores de 2 meses) (Anexo)','igr005l30','fecha_p7','cuie_p7','Control de Salud');
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (IG R025 L30/A98)
busqueda_pres_compl('Rx hombro, humero, pelvis, cadera y femur (total o focalizada) (fte. y perf.) (Anexo)','igr025l30','fecha_p9','cuie_p9','Control de Salud');
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (LB L116 A98)
busqueda_pres_compl('TSH (Anexo)','lbl116a98','fecha_p11','cuie_p11','Control de Salud');
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (LB L001 A98)
busqueda_pres_compl('17 Hidroxiprogesterona (Anexo)','lbl001a98','fecha_p12','cuie_p12','Control de Salud');
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (LB L013 A98)
busqueda_pres_compl('Biotinidasa neonatal (Anexo)','lbl013a98','fecha_p13','cuie_p13','Control de Salud');
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (LB L014 A98)
busqueda_pres_compl('Tripsina catiónica inmunorreactiva (Anexo)','lbl115a98','fecha_p14','cuie_p14','Control de Salud');
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (LB L035 A98)
busqueda_pres_compl('Fenilalanina (Anexo)','lbl035a98','fecha_p15','cuie_p15','Control de Salud');
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (LB L016 A98)
busqueda_pres_compl('Galactosemia (Anexo)','lbl043a98','fecha_p16','cuie_p16','Control de Salud');


//BUSQUEDA DE PRESTACIONES MADRES - Deteccion Temprana de la Hipoacusia (PR P021 H86/A97) -----------
busqueda_pres_madre('Otoemisiones acústicas para Detección temprana de hipoacusia en RN   (Grupo niños 0 a 5 años (Post Parto inmediato)) ','prp021a97_1','fecha_p20','cuie_p20','Deteccion Temprana de la Hipoacusia');
//BUSQUEDA DE PRACTICAS DERIVADAS PARA LA LINEA DE CUIDADO (PR P021 H86)
busqueda_pres_compl('Rescreening de hipoacusia en lactante 
"No pasa" con Otoemisiones acústicas  (Grupo Niño 0 a 5 años  Cuidado de la Salud)','prp021h86','fecha_p21','cuie_p21','Deteccion Temprana de la Hipoacusia');
*/

$accion="Se Creo la tabla cobertura_efectiva";
$link=encode_link("crear_bases_datos.php", array("accion"=>$accion,"tabla_base_1"=>$grupoetareo,"linea_cuidado_1"=>$linea_cuidado,"parametros_csi"=>$parametros_csi));?>
<script>location.href='<?=$link?>' </script>