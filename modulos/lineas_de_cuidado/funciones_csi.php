<?
function limpiar_tablas($tabla_base,$tabla_historica){

$sql_clean="DELETE from cobertura_efectiva.$tabla_base";
sql($sql_clean) or fin_pagina();
$sql_clean="DELETE from cobertura_efectiva.$tabla_historica";
sql($sql_clean) or fin_pagina();
}


function se_encuentra ($id1,$tipo_linea,$tabla_base) {
  
  $sql_busq="SELECT * from cobertura_efectiva.$tabla_base 
             WHERE id_smiafiliados=$id1 and tipo_linea='$tipo_linea'";
  $res_busq=sql($sql_busq,"Fallo la busqueda") or fin_pagina();
  
  if($res_busq->recordcount()>0) return TRUE;
  else return FALSE;
  
}

function busqueda_pres_compl($descripcion,$st_codigo,$st_fecha,$st_cuie,$tipo_linea,$fecha_desde,$fecha_hasta,$tabla_base,$tabla_historica,$st_fecha_madre){
 
 $grupo=strtoupper(substr($st_codigo,0,2));
 $codigo=strtoupper(substr($st_codigo,2,4));
 

 /*$sql_1="SELECT comprobante.id_smiafiliados,prestacion.fecha_prestacion,comprobante.cuie,
  prestacion.id_prestacion,nomenclador.codigo,nomenclador.grupo,nomenclador.descripcion,prestacion.diagnostico,factura.id_factura
  from facturacion.prestacion 
  inner join facturacion.comprobante on prestacion.id_comprobante=comprobante.id_comprobante
  inner join facturacion.nomenclador on nomenclador.id_nomenclador=prestacion.id_nomenclador
  inner join facturacion.factura on factura.id_factura=comprobante.id_factura 
  where nomenclador.codigo='$codigo' 
  and nomenclador.grupo='$grupo' 
  and nomenclador.descripcion='$descripcion'
  and prestacion.fecha_prestacion between '$fecha_desde' and '$fecha_hasta'
  and comprobante.id_smiafiliados in (select id_smiafiliados from cobertura_efectiva.$tabla_base 
    where tipo_linea='$tipo_linea')
  order by comprobante.id_smiafiliados,prestacion.fecha_prestacion ASC";*/
  
  $sql_temp="SELECT id_smiafiliados,$st_fecha_madre as fecha_madre
      from cobertura_efectiva.$tabla_base
      where $tabla_base.tipo_linea='$tipo_linea'";
  $res_temp=sql($sql_temp) or fin_pagina();
  
  while (!$res_temp->EOF){
    $id_smiafiliados=$res_temp->fields['id_smiafiliados'];
    $fecha_madre=$res_temp->fields['fecha_madre'];
    
    $sql_1="SELECT comprobante.id_smiafiliados,prestacion.fecha_prestacion,comprobante.cuie,
  prestacion.id_prestacion,nomenclador.codigo,nomenclador.grupo,nomenclador.descripcion,prestacion.diagnostico,factura.id_factura
  from facturacion.prestacion 
  inner join facturacion.comprobante on prestacion.id_comprobante=comprobante.id_comprobante
  inner join facturacion.nomenclador on nomenclador.id_nomenclador=prestacion.id_nomenclador
  inner join facturacion.factura on factura.id_factura=comprobante.id_factura 
  where comprobante.id_smiafiliados=$id_smiafiliados
  and nomenclador.codigo='$codigo' 
  and nomenclador.grupo='$grupo' 
  and nomenclador.descripcion='$descripcion'
  and prestacion.fecha_prestacion between '$fecha_madre' and '$fecha_hasta'";
  
$res_sql=sql($sql_1,"No se pudo ejecutar la consulta de prestaciones complementarias") or fin_pagina();
$res_sql->movefirst();

if ($res_sql->recordcount() > 0){
  
  while (!$res_sql->EOF) {
        
        //$id_smiafiliados=$res_sql->fields['id_smiafiliados'];
        $fecha=$res_sql->fields['fecha_prestacion'];
        $cuie=$res_sql->fields['cuie'];
        $id_prestacion=$res_sql->fields['id_prestacion'];
        
        $grupo=$res_sql->fields['grupo'];
        $codigo=$res_sql->fields['codigo'];
        $diagnostico=$res_sql->fields['diagnostico'];
        $descripcion=$res_sql->fields['descripcion'];
        $id_factura=$res_sql->fields['id_factura'];
               
        $sql_update="UPDATE cobertura_efectiva.$tabla_base
                     SET $st_codigo=$st_codigo+1,
                     $st_fecha='$fecha',
                     $st_cuie='$cuie'
                     WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
        $res_update=sql($sql_update,"No se pudo actualizar el registro") or fin_pagina();
        
        $sql_consulta="SELECT id from cobertura_efectiva.$tabla_base 
                        WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
        $res_consulta=sql($sql_consulta) or fin_pagina();
        $id=$res_consulta->fields['id'];
        
        $seq=$tabla_historica."_id_seq";
        $sql_2="SELECT nextval ('cobertura_efectiva.$seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla historica de ninios menores de 1 anio");
        $id_hist=$res_sql_2->fields['id'];
        
        $sql_insert="INSERT into cobertura_efectiva.$tabla_historica
                    (id,id_hist,id_prestacion,grupo,codigo,descripcion,diagnostico,fecha_prestacion,cuie,id_factura)
                    VALUES
                    ($id_hist,$id,$id_prestacion,'$grupo','$codigo','$descripcion','$diagnostico','$fecha','$cuie',$id_factura)";
        $res_insert=sql($sql_insert,"Error al insertar el registro en el historial") or fin_pagina();
          
  $res_sql->Movenext();
    }
  }
 $res_temp->MoveNext();
 }   
}//function

function busqueda_pres_compl_2($descripcion,$st_codigo,$st_fecha,$st_cuie,$tipo_linea,$fecha_desde,$fecha_hasta,$tabla_base,$tabla_historica,$st_fecha_madre1,$st_fecha_madre2){
 
 $grupo=strtoupper(substr($st_codigo,0,2));
 $codigo=strtoupper(substr($st_codigo,2,4));
   
  $sql_temp="SELECT id_smiafiliados,$st_fecha_madre1 as fecha_madre1,
      $st_fecha_madre2 as fecha_madre2
      from cobertura_efectiva.$tabla_base
      where $tabla_base.tipo_linea='$tipo_linea'";
  $res_temp=sql($sql_temp) or fin_pagina();
  
  while (!$res_temp->EOF){
    $id_smiafiliados=$res_temp->fields['id_smiafiliados'];
    $fecha_madre1=$res_temp->fields['fecha_madre1'];
    $fecha_madre2=$res_temp->fields['fecha_madre2'];
    
    $sql_1="SELECT comprobante.id_smiafiliados,prestacion.fecha_prestacion,comprobante.cuie,
  prestacion.id_prestacion,nomenclador.codigo,nomenclador.grupo,nomenclador.descripcion,prestacion.diagnostico,factura.id_factura
  from facturacion.prestacion 
  inner join facturacion.comprobante on prestacion.id_comprobante=comprobante.id_comprobante
  inner join facturacion.nomenclador on nomenclador.id_nomenclador=prestacion.id_nomenclador
  inner join facturacion.factura on factura.id_factura=comprobante.id_factura 
  where comprobante.id_smiafiliados=$id_smiafiliados
  and nomenclador.codigo='$codigo' 
  and nomenclador.grupo='$grupo' 
  and nomenclador.descripcion='$descripcion'
  and (prestacion.fecha_prestacion between '$fecha_madre1' and '$fecha_hasta'
      or prestacion.fecha_prestacion between '$fecha_madre2' and '$fecha_hasta')";
  
$res_sql=sql($sql_1,"No se pudo ejecutar la consulta de prestaciones complementarias") or fin_pagina();

$res_sql->movefirst();

if ($res_sql->recordcount() > 0){
  
  while (!$res_sql->EOF) {
        //$id_smiafiliados=$res_sql->fields['id_smiafiliados'];
        $fecha=$res_sql->fields['fecha_prestacion'];
        $cuie=$res_sql->fields['cuie'];
        $id_prestacion=$res_sql->fields['id_prestacion'];
        
        $grupo=$res_sql->fields['grupo'];
        $codigo=$res_sql->fields['codigo'];
        $diagnostico=$res_sql->fields['diagnostico'];
        $descripcion=$res_sql->fields['descripcion'];
        $id_factura=$res_sql->fields['id_factura'];
               
        $sql_update="UPDATE cobertura_efectiva.$tabla_base
                     SET $st_codigo=$st_codigo+1,
                     $st_fecha='$fecha',
                     $st_cuie='$cuie'
                     WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
        $res_update=sql($sql_update,"No se pudo actualizar el registro") or fin_pagina();
        
        $sql_consulta="SELECT id from cobertura_efectiva.$tabla_base 
                        WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
        $res_consulta=sql($sql_consulta) or fin_pagina();
        $id=$res_consulta->fields['id'];
        
        $seq=$tabla_historica."_id_seq";
        $sql_2="SELECT nextval ('cobertura_efectiva.$seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla historica de ninios menores de 1 anio");
        $id_hist=$res_sql_2->fields['id'];
        
        $sql_insert="INSERT into cobertura_efectiva.$tabla_historica
                    (id,id_hist,id_prestacion,grupo,codigo,descripcion,diagnostico,fecha_prestacion,cuie,id_factura)
                    VALUES
                    ($id_hist,$id,$id_prestacion,'$grupo','$codigo','$descripcion','$diagnostico','$fecha','$cuie',$id_factura)";
        $res_insert=sql($sql_insert,"Error al insertar el registro en el historial") or fin_pagina();
      
    $res_sql->Movenext();
    }
  }
 $res_temp->MoveNext();
 }   
}//function

function busqueda_pres_compl_3($descripcion,$st_codigo,$st_fecha,$st_cuie,$tipo_linea,$fecha_desde,$fecha_hasta,$tabla_base,$tabla_historica,$st_fecha_madre1,$st_fecha_madre2,$st_fecha_madre3){
 
 $grupo=strtoupper(substr($st_codigo,0,2));
 $codigo=strtoupper(substr($st_codigo,2,4));
   
  $sql_temp="SELECT id_smiafiliados,$st_fecha_madre1 as fecha_madre1,
      $st_fecha_madre2 as fecha_madre2,$st_fecha_madre3 as fecha_madre3
      from cobertura_efectiva.$tabla_base
      where $tabla_base.tipo_linea='$tipo_linea'";
  $res_temp=sql($sql_temp) or fin_pagina();
  
  while (!$res_temp->EOF){
    $id_smiafiliados=$res_temp->fields['id_smiafiliados'];
    $fecha_madre1=$res_temp->fields['fecha_madre1'];
    $fecha_madre2=$res_temp->fields['fecha_madre2'];
    $fecha_madre3=$res_temp->fields['fecha_madre3'];
    
    $sql_1="SELECT comprobante.id_smiafiliados,prestacion.fecha_prestacion,comprobante.cuie,
  prestacion.id_prestacion,nomenclador.codigo,nomenclador.grupo,nomenclador.descripcion,prestacion.diagnostico,factura.id_factura
  from facturacion.prestacion 
  inner join facturacion.comprobante on prestacion.id_comprobante=comprobante.id_comprobante
  inner join facturacion.nomenclador on nomenclador.id_nomenclador=prestacion.id_nomenclador
  inner join facturacion.factura on factura.id_factura=comprobante.id_factura 
  where comprobante.id_smiafiliados=$id_smiafiliados
  and nomenclador.codigo='$codigo' 
  and nomenclador.grupo='$grupo' 
  and nomenclador.descripcion='$descripcion'
  and (prestacion.fecha_prestacion between '$fecha_madre1' and '$fecha_hasta'
      or prestacion.fecha_prestacion between '$fecha_madre2' and '$fecha_hasta')
      or prestacion.fecha_prestacion between '$fecha_madre3' and '$fecha_hasta')";
  
$res_sql=sql($sql_1,"No se pudo ejecutar la consulta de prestaciones complementarias") or fin_pagina();
$res_sql->movefirst();

if ($res_sql->recordcount() > 0){
  
  while (!$res_sql->EOF) {
        
        //$id_smiafiliados=$res_sql->fields['id_smiafiliados'];
        $fecha=$res_sql->fields['fecha_prestacion'];
        $cuie=$res_sql->fields['cuie'];
        $id_prestacion=$res_sql->fields['id_prestacion'];
        
        $grupo=$res_sql->fields['grupo'];
        $codigo=$res_sql->fields['codigo'];
        $diagnostico=$res_sql->fields['diagnostico'];
        $descripcion=$res_sql->fields['descripcion'];
        $id_factura=$res_sql->fields['id_factura'];
               
        $sql_update="UPDATE cobertura_efectiva.$tabla_base
                     SET $st_codigo=$st_codigo+1,
                     $st_fecha='$fecha',
                     $st_cuie='$cuie'
                     WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
        $res_update=sql($sql_update,"No se pudo actualizar el registro") or fin_pagina();
        
        $sql_consulta="SELECT id from cobertura_efectiva.$tabla_base 
                        WHERE id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'";
        $res_consulta=sql($sql_consulta) or fin_pagina();
        $id=$res_consulta->fields['id'];
        
        $seq=$tabla_historica."_id_seq";
        $sql_2="SELECT nextval ('cobertura_efectiva.$seq') as id";
        $res_sql_2=sql($sql_2,"error al traer el id de la tabla historica de ninios menores de 1 anio");
        $id_hist=$res_sql_2->fields['id'];
        
        $sql_insert="INSERT into cobertura_efectiva.$tabla_historica
                    (id,id_hist,id_prestacion,grupo,codigo,descripcion,diagnostico,fecha_prestacion,cuie,id_factura)
                    VALUES
                    ($id_hist,$id,$id_prestacion,'$grupo','$codigo','$descripcion','$diagnostico','$fecha','$cuie',$id_factura)";
        $res_insert=sql($sql_insert,"Error al insertar el registro en el historial") or fin_pagina();
          
  $res_sql->Movenext();
    }
  }
 $res_temp->MoveNext();
 }   
}//function


function set_valores($tabla_base,$linea_cuidado,$categoria){
 
    $m = array();
    $a = array(); 
    $q="SELECT * from cobertura_efectiva.parametros 
        where linea_de_cuidado='$linea_cuidado' 
        and poblacion='$tabla_base'
        and categoria=$categoria";
    $res_q=sql($q) or fin_pagina();
    
    if ($tabla_base=='ninios_0_1_anio' and $linea_cuidado=='Control de Salud') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc001a97>=$m[0] and tat002a98>=$m[1] and tat003a98>=$m[2]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          ctc001a97>=$a[0] and tat002a98>=$a[1] and tat003a98>=$a[2]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='ninios_0_1_anio' and $linea_cuidado=='Comunidad') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          caw003a98>=$m[0] and ctc001a97_1>=$m[1]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          caw003a98>=$a[0] and ctc001a97_1>=$a[1]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='ninios_1_2_anio' and $linea_cuidado=='Control de Salud') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc001a97>=$m[0] and ctc010a97>=$m[1] 
          and imv001a98>=$m[2] and imv015a98>=$m[3]
          and imv005a98>=$m[4]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          ctc001a97>=$a[0] and ctc010a97>=$a[1] 
          and imv001a98>=$a[2] and imv015a98>=$a[3]
          and imv005a98>=$a[4]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='ninios_1_2_anio' and $linea_cuidado=='Infeccion respiratoria Aguda') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ite001r78>=$m[0] and ctc001r78>=$m[1] 
          and ctc002r78>=$m[2] ";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ite001r78>=$a[0] and ctc001r78>=$a[1] 
          and ctc002r78>=$a[2] ";
       sql($sql_rec) or fin_pagina();
              
    };
    
    if ($tabla_base=='ninios_1_2_anio' and $linea_cuidado=='Comunidad') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          caw003a98>=$m[0] and ctc001a97>=$m[1]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          caw003a98>=$a[0] and ctc001a97>=$a[1]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='ninios_2_5_anio' and $linea_cuidado=='Control de Salud') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc001a97>=$m[0] and ctc010a97>=$m[1] 
          and tat002a98>=$m[2] and tat003a98>=$m[3]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc001a97>=$a[0] and ctc010a97>=$a[1] 
          and tat002a98>=$a[2] and tat003a98>=$a[3]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='ninios_2_5_anio' and $linea_cuidado=='Infeccion Respiratoria Aguda') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ite001r78>=$m[0] and (ctc001r74>=$m[1] 
          or ctc001r78>=$m[2] or ctc001r81>=$m[3])
          and (ctc002r74>=$m[4] or ctc002r81>=$m[5] or ctc002r78>=$m[6])";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where
          ite001r78>=$a[0] and (ctc001r74>=$a[1] 
          or ctc001r78>=$a[2] or ctc001r81>=$a[3])
          and (ctc002r74>=$a[4] or ctc002r81>=$a[5] or ctc002r78>=$a[6])";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='ninios_6_anio' and $linea_cuidado=='Control de Salud') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc001a97>=$m[0] and ctc010a97>=$m[1] 
          and imv001a98>=$m[2] and imv011a98>=$m[3]
          and imv002a98>=$m[4] and imv008a98>=$m[5]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where
          ctc001a97>=$a[0] and ctc010a97>=$a[1] 
          and imv001a98>=$a[2] and imv011a98>=$a[3]
          and imv002a98>=$a[4] and imv008a98>=$a[5]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='ninios_6_9_anio' and $linea_cuidado=='Asma') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc001r96>=$m[0] and ctc002r96>=$m[1]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          ctc001r96>=$a[0] and ctc002r96>=$a[1]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='ninios_6_9_anio' and $linea_cuidado=='Sobrepeso') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc001t83>=$m[0] and ctc002t83>=$m[1] 
          and tat011a98>=$m[2]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          ctc001t83>=$a[0] and ctc002t83>=$a[1] 
          and tat011a98>=$a[2]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='adolescente_11_anio' and $linea_cuidado=='Control de Salud') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc001a97>=$m[0] and ctc010a97>=$m[1] 
          and imv008a98>=$m[2] and imv014a98>=$m[3]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          ctc001a97>=$a[0] and ctc010a97>=$a[1] 
          and imv008a98>=$a[2] and imv014a98>=$a[3]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='adolescente_10_19_anio' and $linea_cuidado=='Embarazo Adolescente') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc005w78>=$m[0] and ctc006w78>=$m[1]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          ctc005w78>=$a[0] and ctc006w78>=$a[1]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='adolescente_10_19_anio' and $linea_cuidado=='Obesidad') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          (ctc001t79>=$m[0] or ctc001t82>=$m[1]) 
          and (ctc002t79>=$m[2] or ctc002t82>=$m[3])
          and tat011a98>=$m[4]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          (ctc001t79>=$a[0] or ctc001t82>=$a[1]) 
          and (ctc002t79>=$a[2] or ctc002t82>=$a[3])
          and tat011a98>=$a[4]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='adolescente_10_19_anio' and $linea_cuidado=='Sobrepeso') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc001t83>=$m[0] and ctc002t83>=$m[1] 
          and tat011a98_1>=$m[2]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          ctc001t83>=$a[0] and ctc002t83>=$a[1] 
          and tat011a98_1>=$a[2]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='adolescente_10_19_anio' and $linea_cuidado=='Comunidad') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          caw004a98>=$m[0] and ctc001a97>=$m[1]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          caw004a98>=$a[0] and ctc001a97>=$a[1]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='adolescente_10_19_anio' and $linea_cuidado=='Consumo excesivo de Alcohol') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          (ctc001p20>=$m[0] or 
          ctc001p23>=$m[1] or 
          ctc001p24>=$m[2]) 
          and 
          (ctc002p20>=$m[3] or 
          ctc002p23>=$m[4] or 
          ctc002p24>=$m[5])";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          (ctc001p20>=$a[0] or ctc001p23>=$a[1] 
          or ctc001p24>=$a[2]) and (ctc002p20>=$a[3]
          or ctc002p23>=$a[4]
          or ctc002p24>=$a[5])";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='mujeres_25_64_anio' and $linea_cuidado=='Cancer de Cuello') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          (ctc001a97>=$m[0] or ctc008a97>=$m[1]) 
          and prp018a98>=$m[2]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          (ctc001a97>=$a[0] or ctc008a97>=$a[1]) 
          and prp018a98>=$a[2]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='mujeres_49_64_anio' and $linea_cuidado=='Cancer de Mama') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          (ctc001a97>=$m[0] or ctc008a97>=$m[1]) 
          and (apa001x86>=$m[2] or apa001x75>=$m[3]
          or apa001a98>=$m[4])";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          (ctc001a97>=$a[0] or ctc008a97>=$a[1]) 
          and (apa001x86>=$a[2] or apa001x75>=$a[3]
          or apa001a98>=$a[4])";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='mujeres_20_64_anio' and $linea_cuidado=='Embarazo Bajo Riesgo') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc005w78>=$m[0] and ctc006w78>=$m[1] 
          and tat003a98>=$m[2] and tat002a98>=$m[3]
          and tat001a98>=$m[4]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          ctc005w78>=$a[0] and ctc006w78>=$a[1] 
          and tat003a98>=$a[2] and tat002a98>=$a[3]
          and tat001a98>=$a[4]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='mujeres_20_64_anio' and $linea_cuidado=='Embarazo Alto Riesgo (HTA Inducida)') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ntn004>=$m[0]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          ntn004>=$a[0]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='hombres_20_64_anio' and $linea_cuidado=='Hombre Sano') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc001a97>=$m[0] and ctc010a97>=$m[1] 
          and ctc011a97>=$m[2]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          ctc001a97>=$a[0] and ctc010a97>=$a[1] 
          and ctc011a97>=$a[2]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='hombres_40_64_anio' and $linea_cuidado=='Deteccion de enfermedades cronicas no trasmisibles') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc001a97>=$m[0] and ctc048vmd>=$m[1] 
          and ntn007k22>=$m[2] and ntn008k22>=$m[3]
          and ntn009k22>=$m[4] and ntn010k22>=$m[5]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          ctc001a97>=$a[0] and ctc048vmd>=$a[1] 
          and ntn007k22>=$a[2] and ntn008k22>=$a[3]
          and ntn009k22>=$a[4] and ntn010k22>=$a[5]";
       sql($sql_rec) or fin_pagina();
       
    };
    
    if ($tabla_base=='hombres_50_64_anio' and $linea_cuidado=='"Deteccion de Riesgo y Diagnostico oportuno de Cancer Colorectal"') {
       $i=0;
       while (!$res_q->EOF) {
         $m[$i]=$res_q->fields['minima'];
         $a[$i]=$res_q->fields['adecuada'];
         $i++;
         $res_q->Movenext();
       };
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set minima='SI' where
          ctc001a97>=$m[0] and ctc001vmd>=$m[1]";
       sql($sql_rec) or fin_pagina();
       
       $sql_rec="UPDATE cobertura_efectiva.$tabla_base set adecuada='SI' where 
          ctc001a97>=$a[0] and ctc001vmd>=$a[1]";
       sql($sql_rec) or fin_pagina();
       
    };
}


function valores_csi($tabla_base,$linea_cuidado){
  
  $q="SELECT * from cobertura_efectiva.$tabla_base
              where tipo_linea='$linea_cuidado'";
          $res_q=sql($q) or fin_pagina();
          $madre=$res_q->Recordcount();
          $minima=0;
          while (!$res_q->EOF){
            if ($res_q->fields['minima']=='SI') $minima++;
            $res_q->Movenext();
          };
          $adecuada=0;
          $res_q->Movefirst();
          while (!$res_q->EOF){
            if ($res_q->fields['adecuada']=='SI') $adecuada++;
            $res_q->Movenext();
          };
    $valores = array ("madre" => $madre,
                    "minima" => $minima,
                    "adecuada" => $adecuada) ;
    return $valores;
}

function crear_bases_de_datos($grupoetareo,$linea_cuidado,$fecha_desde,$fecha_hasta,$cuie) {
    
  $link=encode_link("$grupoetareo".".php", array("grupoetareo"=>$grupoetareo,"linea_cuidado"=>$linea_cuidado,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta,"cuie" =>$cuie));?>
    <script>location.href='<?=$link?>' </script>
<?}?>


