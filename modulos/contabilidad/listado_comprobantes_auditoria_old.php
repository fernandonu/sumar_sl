<?
require_once ("../../config.php");
require_once ("funciones_edad.php");
require_once ("funciones_auditoria.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

$usuario=$_ses_user['login'];

function inserta_datos ($datos_compl,$datos,$msg) {
  
  $id_factura = ($datos_compl['id_factura']) ? $datos_compl['id_factura'] : 0;
  $id_comprobante = $datos_compl['id_comprobante'];
  $id_prestacion=$datos_compl['id_prestacion'];
  $cuie = $datos_compl['cuie'];
  $nombre_efector = $datos_compl['nombre_efector'];
  $clavebeneficiario = $datos_compl['clavebeneficiario'];
  $id_smiafiliados = $datos_compl['id_smiafiliados'];
  $dni= $datos_compl['dni'];
  $nombre = $datos_compl['nombre'];
  $apellido = $datos_compl['apellido'];
  $afifechanac = $datos_compl['afifechanac'];
  $anios= $datos_compl['edad'];
  $afisexo = $datos_compl['afisexo'];
  $fecha_comprobante = $datos_compl['fecha_comprobante'];
  $grupo = $datos_compl['grupo'];
  $codigo = $datos_compl['codigo'];
  $descripcion = $datos_compl['descripcion'];
  $diagnostico = $datos_compl['diagnostico'];
  $log=$msg;
    
  if ($datos) {
    $peso=($datos['peso']) ? $datos['peso'] : 0;
    $talla=($datos['talla']) ? $datos['talla'] : 0;
    $ta=$datos['ta'];
    $perim_cefalico=($datos['perimetro_cefalico']) ? $datos['perimetro_cefalico'] :0;
    $edad_gestacional=($datos['edad_gestacional']) ? $datos['edad_gestacional'] : 0;
    $res_oido_derecho=$datos['res_oido_derecho'];
    $res_oido_izquierdo=$datos['res_oido_izquierdo'];
    $retinopatia=$datos['retinopatia'];
    $inf_diag_biopsia=$datos['inf_diag_biopsia'];
    $inf_anat_patologica=$datos['inf_anat_patologica'];
    $inf_diag_anatomo=$datos['inf_diag_anatomo'];
    $inf_vdrl=$datos['inf_vdrl'];
    $tratamiento_instaurado=$datos['tratamiento_instaurado'];
    if ($edad<=12) $cpod_ceod=$datos['ceod'];
      else $cpod_ceod=$datos['cpod'];
    $birads=$datos['birads'];
    $tisomf=$datos['tisomf'];
    $hemo_glic=$datos['hemo_glic'];
    $vph=$datos['vph'];
    $tratamiento_instaurado_de_cm=$datos['tratamiento_instaurado_de_cm'];
    $financiador=$datos['financiador'];
    $porc_geo=$datos['porc_geo'];
    $porc_dbt=$datos['porc_dbt'];
    $porc_hta=$datos['porc_hta'];
      
  $query = "INSERT INTO facturacion.resumen_prestacion 
      (id_factura, id_comprobante, id_prestacion, cuie, nombre_efector, 
      clavebeneficiario, id_smiafiliados, dni, nombre, apellido, afifechanac,edad, 
      afisexo, fecha_comprobante, grupo, 
      codigo, descripcion, diagnostico, 
      peso, talla, ta, perim_cefalico, edad_gestacional, 
      res_oido_derecho, res_oido_izquierdo, retinopatia, inf_diag_biopsia, 
      inf_anat_patologica, inf_diag_anatomo, inf_vdrl, tratamiento_instaurado, 
      cpod_ceod, log_table,birads,tisomf,hemo_glic,vph,tratamiento_instaurado_de_cm,financiador,
      porc_geo,porc_dbt,porc_hta) 
      VALUES 
      ($id_factura, $id_comprobante, $id_prestacion, '$cuie', '$nombre_efector', 
      '$clavebeneficiario', $id_smiafiliados, '$dni', '$nombre', '$apellido', '$afifechanac',$anios, 
      '$afisexo', '$fecha_comprobante', '$grupo', 
      '$codigo', '$descripcion', '$diagnostico', 
      '$peso', '$talla', '$ta', '$perim_cefalico', '$edad_gestacional', 
      '$res_oido_derecho', '$res_oido_izquierdo', '$retinopatia', '$inf_diag_biopsia', 
      '$inf_anat_patologica', '$inf_diag_anatomo', '$inf_vdrl', '$tratamiento_instaurado', 
      '$cpod_ceod', '$log','$birads','$tisomf','$hemo_glic','$vph','$tratamiento_instaurado_de_cm',
      '$financiador','$porc_geo','$porc_dbt','$porc_hta')";
  
  $res_query = sql($query) or fin_pagina(); 
  }else {
    $query = "INSERT INTO facturacion.resumen_prestacion 
      (id_factura, id_comprobante, id_prestacion, cuie, nombre_efector, 
      clavebeneficiario, id_smiafiliados, dni, nombre, apellido, afifechanac,edad, 
      afisexo, fecha_comprobante, grupo, 
      codigo, descripcion, diagnostico, 
      log_table) 
      VALUES 
      ($id_factura, $id_comprobante, $id_prestacion, '$cuie', '$nombre_efector', 
      '$clavebeneficiario', $id_smiafiliados, '$dni', '$nombre', '$apellido', '$afifechanac',$anios, 
      '$afisexo', '$fecha_comprobante', '$grupo', 
      '$codigo', '$descripcion', '$diagnostico','$log')";
  
  $res_query = sql($query) or fin_pagina(); 
  } 
}

function extrae_anio($fecha) {
        list($d,$m,$a) = explode("/",$fecha);
        //$a=$a+2000;
        return $a;
		}

function extrae_periodo($fecha) {
        list($a,$m,$d) = explode("-",$fecha);
        //$a=$a+2000;
        return $a.$m;
    }


if ($_POST['muestra']=="Muestra"){	
	
	$fecha_desde=fecha_db($_POST['fecha_desde']);
	$fecha_hasta=fecha_db($_POST['fecha_hasta']);


  $periodo = extrae_periodo($fecha_desde);
	
	$sql_delete = "DELETE from facturacion.resumen_prestacion";
  sql($sql_delete) or fin_pagina();

  $sql_drop = 'DROP TABLE IF EXISTS facturacion.resumen_prestacion'.$periodo;
  sql($sql_drop) or fin_pagina();

  $sql_create = 'CREATE TABLE facturacion.resumen_prestacion'.$periodo." AS
                  (SELECT * from facturacion.resumen_prestacion)";
  sql($sql_create) or fin_pagina();
  
  $sql_fact="SELECT 
          a.id_factura,
          c.id_comprobante,
          g.cuie,
          g.nombre as nombre_efector,
          f.clavebeneficiario,
          f.id_smiafiliados,
          f.afidni as dni,
          f.afinombre as nombre,
          f.afiapellido as apellido,
          f.afifechanac,
          f.afisexo,
          d.id_prestacion,
          c.fecha_comprobante::date,
          c.grupo_etareo,
          d.id_nomenclador,
          e.grupo,
          e.codigo,
          e.descripcion,
          d.cantidad,
          d.precio_prestacion,
          d.diagnostico,
          h.fecha_deposito,
          d.birads,
          d.tisomf,
          d.hemo_glic,
          d.vph,
          d.tratamiento_instaurado_de_cm,
          d.financiador,
          d.porc_geo,
          d.porc_dbt,
          d.porc_hta
        --from expediente.transaccion 
        from expediente.expediente a, facturacion.factura b, 
        facturacion.comprobante c, facturacion.prestacion d, 
        facturacion.nomenclador e, nacer.smiafiliados f, nacer.efe_conv g,
        contabilidad.ingreso h

        where a.id_factura=b.id_factura
        and a.id_factura=c.id_factura
        and c.id_comprobante = d.id_comprobante
        and d.id_nomenclador = e.id_nomenclador  
        and c.id_smiafiliados = f.id_smiafiliados
        and a.id_efe_conv = g.id_efe_conv
        and h.numero_factura = b.id_factura
        and b.estado_envio <> 's'
        and  a.estado='C' and h.fecha_deposito between '$fecha_desde' and '$fecha_hasta' 
        order by 1,2";

$res_fact=sql($sql_fact) or fin_pagina();
$res_fact->MoveFirst();
while (!$res_fact->EOF) {
  $id_factura = $res_fact->fields['id_factura'];
  $id_comprobante = $res_fact->fields['id_comprobante']; 
  $id_prestacion = $res_fact->fields['id_prestacion'];
  $cuie =$res_fact->fields['cuie'];
  $nombre_efector =$res_fact->fields['nombre_efector'];
  $clavebeneficiario = $res_fact->fields['clavebeneficiario'];
  $id_smiafiliados=$res_fact->fields['id_smiafiliados'];
  $dni=$res_fact->fields['dni']; 
  $nombre = str_replace("'","",$res_fact->fields['nombre']);
  $apellido = str_replace("'","",$res_fact->fields['apellido']);
  $afifechanac=$res_fact->fields['afifechanac']; 
  $edad=edad_con_meses($afifechanac,$fecha_comprobante);
  $anios=$edad['anos'];
  $afisexo = trim($res_fact->fields['afisexo']);
  $fecha_comprobante=$res_fact->fields['fecha_comprobante'];
  $grupo_etareo = $res_fact->fields['grupo_etareo'];
  $id_nomenclador = $res_fact->fields['id_nomenclador'];
  $grupo = $res_fact->fields['grupo'];
  $codigo = $res_fact->fields['codigo'];
  $descripcion = $res_fact->fields['descripcion'];
  $cantidad = $res_fact->fields['cantidad'];
  $precio_prestacion = $res_fact->fields['precio_prestacion'];
  $diagnostico = $res_fact->fields['diagnostico'];
  $fecha_deposito =$res_fact->fields['fecha_deposito'];
  
  
  $codigo_comp=$res_fact->fields['grupo'].$res_fact->fields['codigo'].$res_fact->fields['diagnostico'];

  if (necesita_dr($codigo_comp)) {
    $datos = get_datos ($id_prestacion,$id_comprobante,$dni,$diagnostico, $id_smiafiliados, $fecha_comprobante,$codigo);
    $peso=($datos['peso']) ? $datos['peso'] : 0;
    $talla=($datos['talla']) ? $datos['talla'] : 0;
    $ta=$datos['ta'];
    $perim_cefalico=($datos['perimetro_cefalico']) ? $datos['perimetro_cefalico'] :0;
    $edad_gestacional=($datos['edad_gestacional']) ? $datos['edad_gestacional'] : 0;
    $res_oido_derecho=$datos['res_oido_derecho'];
    $res_oido_izquierdo=$datos['res_oido_izquierdo'];
    $retinopatia=$datos['retinopatia'];
    $inf_diag_biopsia=$datos['inf_diag_biopsia'];
    $inf_anat_patologica=$datos['inf_anat_patologica'];
    $inf_diag_anatomo=$datos['inf_diag_anatomo'];
    $inf_vdrl=$datos['inf_vdrl'];
    $tratamiento_instaurado=$datos['tratamiento_instaurado'];
    if ($edad<=12) $cpod_ceod=$datos['ceod'];
        else $cpod_ceod=$datos['cpod'];
    $birads=($datos['birads']<>null and $datos['birads']<>'')?$datos['birads']:-1;
    $tisomf=$datos['tisomf'];
    $hemo_glic=($datos['hemo_glic']<>null and $datos['hemo_glic']<>'')?$datos['hemo_glic']:-1;
    $vph=$datos['vph'];
    $tratamiento_instaurado_de_cm=($datos['tratamiento_$tratamiento_instaurado_de_cm']<>null and 
      $datos['tratamiento_$tratamiento_instaurado_de_cm']<>'')?$datos['tratamiento_$tratamiento_instaurado_de_cm']:-1;
    $financiador=($datos['financiador']<>null and $datos['financiador']<>'')?$datos['financiador']:-1;
    $porc_geo=($datos['porc_geo']<>null and $datos['porc_geo']<>'')?$datos['porc_geo']:-1;
    $porc_dbt=($datos['porc_dbt']<>null and $datos['porc_dbt']<>'')?$datos['porc_dbt']:-1;
    $porc_hta=($datos['porc_hta']<>null and $datos['porc_hta']<>'')?$datos['porc_hta']:-1;    
    $log=$datos['log'];
  } else {
    $peso=0;
    $talla=0;
    $ta=null;
    $perim_cefalico=0;
    $edad_gestacional=0;
    $res_oido_derecho=null;
    $res_oido_izquierdo=null;
    $retinopatia=null;
    $inf_diag_biopsia=null;
    $inf_anat_patologica=null;
    $inf_diag_anatomo=null;
    $inf_vdrl=null;
    $tratamiento_instaurado=null;
    $birads=-1;
    $tisomf=-1;
    $hemo_glic=-1;
    $vph=null;
    $tratamiento_instaurado_de_cm=-1;
    $financiador=-1;
    $porc_geo=-1;
    $porc_dbt=-1;
    $porc_hta=-1;

    $log='No necesita datos reportables';
  }
  
  $query = "INSERT INTO facturacion.resumen_prestacion".$periodo."  
      (id_factura, id_comprobante, id_prestacion, cuie, nombre_efector, 
      clavebeneficiario, id_smiafiliados, dni, nombre, apellido, afifechanac,edad, 
      afisexo, fecha_comprobante, grupo_etareo, id_nomenclador, grupo, 
      codigo, descripcion, cantidad, precio_prestacion, diagnostico, 
      fecha_deposito, peso, talla, ta, perim_cefalico, edad_gestacional, 
      res_oido_derecho, res_oido_izquierdo, retinopatia, inf_diag_biopsia, 
      inf_anat_patologica, inf_diag_anatomo, inf_vdrl, tratamiento_instaurado, 
      cpod_ceod, log_table,birads,tisomf,hemo_glic,vph,tratamiento_instaurado_de_cm,financiador,
      porc_geo,porc_dbt,porc_hta) 
      VALUES 
      ($id_factura, $id_comprobante, $id_prestacion, '$cuie', '$nombre_efector', 
      '$clavebeneficiario', $id_smiafiliados, '$dni', '$nombre', '$apellido', '$afifechanac',$anios, 
      '$afisexo', '$fecha_comprobante', '$grupo_etareo', $id_nomenclador, '$grupo', 
      '$codigo', '$descripcion', $cantidad, $precio_prestacion, '$diagnostico', 
      '$fecha_deposito', '$peso', '$talla', '$ta', '$perim_cefalico', '$edad_gestacional', 
      '$res_oido_derecho', '$res_oido_izquierdo', '$retinopatia', '$inf_diag_biopsia', 
      '$inf_anat_patologica', '$inf_diag_anatomo', '$inf_vdrl', '$tratamiento_instaurado', 
      '$cpod_ceod', '$log','$birads','$tisomf','$hemo_glic','$vph','$tratamiento_instaurado_de_cm',
      '$financiador','$porc_geo','$porc_dbt','$porc_hta')";
  
      $res_query = sql($query) or fin_pagina();
      $res_fact->MoveNext();
  }
 
  /*$query_prestacion = "SELECT * 
                      from facturacion.resumen_prestacion
                      --where fecha_deposito between '$fecha_desde'::date and '$fecha_hasta'::date
                      ";
  $res_prestacion = sql($query_prestacion) or fin_pagina();*/
}

if ($_POST['detalle_prestaciones']=="Detalle de Prestaciones para Control"){	
	
  //control diario de prestaciones sin datos reportables
  //despues de correr la funcion se debe pasar a control con
  //script CONTROLES_DETALLE_PRESTACION 
  
  $sql_delete = "DELETE from facturacion.resumen_prestacion_auditoria";
  sql($sql_delete) or fin_pagina();

  $sql_fact="SELECT 
          b.id_factura,
          c.id_comprobante,
          g.cuie,
          g.nombre as nombre_efector,
          f.clavebeneficiario,
          f.id_smiafiliados,
          f.afidni as dni,
          f.afinombre as nombre,
          f.afiapellido as apellido,
          f.afifechanac,
          f.afisexo,
          d.id_prestacion,
          c.fecha_comprobante::date,
          c.grupo_etareo,
          d.id_nomenclador,
          e.grupo,
          e.codigo,
          e.descripcion,
          d.cantidad,
          d.precio_prestacion,
          d.diagnostico,
          b.estado,
          d.birads,
          d.tisomf,
          d.hemo_glic,
          d.vph,
          d.tratamiento_instaurado_de_cm,
          d.financiador,
          d.porc_geo,
          d.porc_dbt,
          d.porc_hta         
        from facturacion.factura b, 
        facturacion.comprobante c, facturacion.prestacion d, 
        facturacion.nomenclador e, nacer.smiafiliados f, nacer.efe_conv g
        
        where b.id_factura=c.id_factura
        and c.id_comprobante = d.id_comprobante
        and d.id_nomenclador = e.id_nomenclador  
        and c.id_smiafiliados = f.id_smiafiliados
        and c.cuie = g.cuie
        and c.fecha_comprobante between (current_date::date - 30) and current_date
        and (b.estado <>'C' or b.estado <> 'X')
        order by 1,2";

$res_fact=sql($sql_fact) or fin_pagina();
$res_fact->MoveFirst();
while (!$res_fact->EOF) {
  $id_factura = $res_fact->fields['id_factura'];
  $id_comprobante = $res_fact->fields['id_comprobante']; 
  $id_prestacion = $res_fact->fields['id_prestacion'];
  $cuie =$res_fact->fields['cuie'];
  $nombre_efector =$res_fact->fields['nombre_efector'];
  $clavebeneficiario = $res_fact->fields['clavebeneficiario'];
  $id_smiafiliados=$res_fact->fields['id_smiafiliados'];
  $dni=$res_fact->fields['dni']; 
  $nombre = str_replace("'","",$res_fact->fields['nombre']);
  $apellido = str_replace("'","",$res_fact->fields['apellido']);
  $afifechanac=$res_fact->fields['afifechanac']; 
  $edad=edad_con_meses($afifechanac,$fecha_comprobante);
  $anios=$edad['anos'];
  $afisexo = trim($res_fact->fields['afisexo']);
  $fecha_comprobante=$res_fact->fields['fecha_comprobante'];
  $grupo_etareo = $res_fact->fields['grupo_etareo'];
  $id_nomenclador = $res_fact->fields['id_nomenclador'];
  $grupo = $res_fact->fields['grupo'];
  $codigo = $res_fact->fields['codigo'];
  $descripcion = $res_fact->fields['descripcion'];
  $cantidad = $res_fact->fields['cantidad'];
  $precio_prestacion = $res_fact->fields['precio_prestacion'];
  $diagnostico = $res_fact->fields['diagnostico'];
  $estado = $res_fact->fields['estado'];
  $birads = $res_fact->fields['birads'];
  $tisomf = $res_fact->fields['tisomf'];
  $hemo_glic = $res_fact->fields['hemo_glic'];
  $vph = $res_fact->fields['vph'];
  $tratamiento_instaurado_de_cm = $res_fact->fields['tratamien$tratamiento_instaurado_de_cm'];
  $financiador = $res_fact->fields['financiador'];
  $porc_geo = $res_fact->fields['porc_geo'];
  $porc_dbt = $res_fact->fields['porc_dbt'];
  $porc_hta = $res_fact->fields['porc_hta'];


  
  
  $codigo_comp=$res_fact->fields['grupo'].$res_fact->fields['codigo'].$res_fact->fields['diagnostico'];

  if (necesita_dr($codigo_comp)) {
    $datos = get_datos ($id_prestacion,$id_comprobante,$dni,$diagnostico, $id_smiafiliados, $fecha_comprobante,$codigo);
    $peso=($datos['peso']) ? $datos['peso'] : 0;
    $talla=($datos['talla']) ? $datos['talla'] : 0;
    $ta=$datos['ta'];
    $perim_cefalico=($datos['perimetro_cefalico']) ? $datos['perimetro_cefalico'] :0;
    $edad_gestacional=($datos['edad_gestacional']) ? $datos['edad_gestacional'] : 0;
    $res_oido_derecho=$datos['res_oido_derecho'];
    $res_oido_izquierdo=$datos['res_oido_izquierdo'];
    $retinopatia=$datos['retinopatia'];
    $inf_diag_biopsia=$datos['inf_diag_biopsia'];
    $inf_anat_patologica=$datos['inf_anat_patologica'];
    $inf_diag_anatomo=$datos['inf_diag_anatomo'];
    $inf_vdrl=$datos['inf_vdrl'];
    $tratamiento_instaurado=$datos['tratamiento_instaurado'];
    if ($edad<=12) $cpod_ceod=$datos['ceod'];
        else $cpod_ceod=$datos['cpod'];
    $birads = ($datos['birads'])?$datos['birads']:-1;
    $tisomf = $datos['tisomf'];
    $hemo_glic = ($datos['hemo_glic'])?$datos['hemo_glic']:0;
    $vph = $datos['vph'];
    $tratamiento_instaurado_de_cm = ($datos['tratamien$tratamiento_instaurado_de_cm'])?$datos['tratamien$tratamiento_instaurado_de_cm']:-1;
    $financiador = ($datos['financiador'])?$datos['financiador']:-1;
    $porc_geo = ($datos['porc_geo'])?$datos['porc_geo']:-1;
    $porc_dbt = ($datos['porc_dbt'])?$datos['porc_dbt']:-1;
    $porc_hta = ($datos['porc_hta'])?$datos['porc_hta']:-1;
    
    $log=$datos['log'];
  } else {
    $peso=0;
    $talla=0;
    $ta=null;
    $perim_cefalico=0;
    $edad_gestacional=0;
    $res_oido_derecho=null;
    $res_oido_izquierdo=null;
    $retinopatia=null;
    $inf_diag_biopsia=null;
    $inf_anat_patologica=null;
    $inf_diag_anatomo=null;
    $inf_vdrl=null;
    $tratamiento_instaurado=null;
    $cpod_ceod=null;
    $birads = -1;
    $tisomf = null;
    $hemo_glic = 0;
    $vph = null;
    $tratamiento_instaurado_de_cm = -1;
    $financiador = -1;
    $porc_geo = -1;
    $porc_dbt = -1;
    $porc_hta = -1;
    $log='No necesita datos reportables';
  }
  
  $query = "INSERT INTO facturacion.resumen_prestacion_auditoria  
      (id_factura, id_comprobante, id_prestacion, cuie, nombre_efector, 
      clavebeneficiario, id_smiafiliados, dni, nombre, apellido, afifechanac,edad, 
      afisexo, fecha_comprobante, grupo_etareo, id_nomenclador, grupo, 
      codigo, descripcion, cantidad, precio_prestacion, diagnostico, 
       peso, talla, ta, perim_cefalico, edad_gestacional, 
      res_oido_derecho, res_oido_izquierdo, retinopatia, inf_diag_biopsia, 
      inf_anat_patologica, inf_diag_anatomo, inf_vdrl, tratamiento_instaurado, 
      cpod_ceod, log_table,estado,birads,tisomf,hemo_glic,vph,tratamiento_instaurado_de_cm,financiador,
      porc_geo,porc_dbt,porc_hta) 
      VALUES 
      ($id_factura, $id_comprobante, $id_prestacion, '$cuie', '$nombre_efector', 
      '$clavebeneficiario', $id_smiafiliados, '$dni', '$nombre', '$apellido', '$afifechanac',$anios, 
      '$afisexo', '$fecha_comprobante', '$grupo_etareo', $id_nomenclador, '$grupo', 
      '$codigo', '$descripcion', $cantidad, $precio_prestacion, '$diagnostico' 
      ,'$peso', '$talla', '$ta', '$perim_cefalico', '$edad_gestacional', 
      '$res_oido_derecho', '$res_oido_izquierdo', '$retinopatia', '$inf_diag_biopsia', 
      '$inf_anat_patologica', '$inf_diag_anatomo', '$inf_vdrl', '$tratamiento_instaurado', 
      '$cpod_ceod', '$log','$estado','$birads','$tisomf','$hemo_glic','$vph','$tratamiento_instaurado_de_cm',
      '$financiador','$porc_geo','$porc_dbt','$porc_hta')";
  
      $res_query = sql($query) or fin_pagina();
      $res_fact->MoveNext();
  }
   
}

if ($_POST['muestra_fact']=="Ver Factura"){	
	
	$id_factura = $_POST['id_factura'];
	$query_prestacion = "SELECT * 
                      from facturacion.resumen_prestacion
                      where id_factura = $id_factura";
  $res_prestacion = sql($query_prestacion) or fin_pagina();
	if ($res_prestacion->RecordCount()==0) {  
    $sql_fact="SELECT 
          a.id_factura,
          c.id_comprobante,
          d.id_prestacion,
          g.cuie,
          g.nombre as nombre_efector,
          f.clavebeneficiario,
          f.id_smiafiliados,
          f.afidni as dni,
          f.afinombre as nombre,
          f.afiapellido as apellido,
          f.afifechanac,
          f.afisexo,
          d.id_prestacion,
          c.fecha_comprobante::date,
          c.grupo_etareo,
          d.id_nomenclador,
          e.grupo,
          e.codigo,
          e.descripcion,
          d.cantidad,
          d.precio_prestacion,
          d.diagnostico,
          h.fecha_deposito
          
        --from expediente.transaccion 
        from expediente.expediente a, facturacion.factura b, facturacion.comprobante c, facturacion.prestacion d, facturacion.nomenclador e, nacer.smiafiliados f, nacer.efe_conv g,
        contabilidad.ingreso h

        where a.id_factura=b.id_factura
        and a.id_factura=c.id_factura
        and c.id_comprobante = d.id_comprobante
        and d.id_nomenclador = e.id_nomenclador  
        and c.id_smiafiliados = f.id_smiafiliados
        and a.id_efe_conv = g.id_efe_conv
        and h.numero_factura = b.id_factura
        and b.estado_envio <> 's'
        and a.id_factura = $id_factura
        order by 1,2";

    $res_fact=sql($sql_fact) or fin_pagina();
    $res_fact->MoveFirst();
    while (!$res_fact->EOF) {
      $id_factura = $res_fact->fields['id_factura'];
      $id_comprobante = $res_fact->fields['id_comprobante']; 
      $id_prestacion = $res_fact->fields['id_prestacion'];
      $cuie =$res_fact->fields['cuie'];
      $nombre_efector =$res_fact->fields['nombre_efector'];
      $clavebeneficiario = $res_fact->fields['clavebeneficiario'];
      $id_smiafiliados=$res_fact->fields['id_smiafiliados'];
      $dni=$res_fact->fields['dni']; 
      $nombre = str_replace("'","",$res_fact->fields['nombre']);
      $apellido = str_replace("'","",$res_fact->fields['apellido']);
      $afifechanac=$res_fact->fields['afifechanac']; 
      $edad=edad_con_meses($afifechanac,$fecha_comprobante);
      $anios=$edad['anos'];
      $afisexo = trim($res_fact->fields['afisexo']);
      $fecha_comprobante=$res_fact->fields['fecha_comprobante'];
      $grupo_etareo = $res_fact->fields['grupo_etareo'];
      $id_nomenclador = $res_fact->fields['id_nomenclador'];
      $grupo = $res_fact->fields['grupo'];
      $codigo = $res_fact->fields['codigo'];
      $descripcion = $res_fact->fields['descripcion'];
      $cantidad = $res_fact->fields['cantidad'];
      $precio_prestacion = $res_fact->fields['precio_prestacion'];
      $diagnostico = $res_fact->fields['diagnostico'];
      $fecha_deposito =$res_fact->fields['fecha_deposito'];
  
  
    $codigo_comp=$res_fact->fields['grupo'].$res_fact->fields['codigo'].$res_fact->fields['diagnostico'];

    if (necesita_dr($codigo_comp)) {
      $datos = get_datos ($id_prestacion,$id_comprobante,$dni,$diagnostico, $id_smiafiliados, $fecha_comprobante,$codigo);
      $peso=($datos['peso']) ? $datos['peso'] : 0;
      $talla=($datos['talla']) ? $datos['talla'] : 0;
      $ta=$datos['ta'];
      $perim_cefalico=($datos['perimetro_cefalico']) ? $datos['perimetro_cefalico'] :0;
      $edad_gestacional=($datos['edad_gestacional']) ? $datos['edad_gestacional'] : 0;
      $res_oido_derecho=$datos['res_oido_derecho'];
      $res_oido_izquierdo=$datos['res_oido_izquierdo'];
      $retinopatia=$datos['retinopatia'];
      $inf_diag_biopsia=$datos['inf_diag_biopsia'];
      $inf_anat_patologica=$datos['inf_anat_patologica'];
      $inf_diag_anatomo=$datos['inf_diag_anatomo'];
      $inf_vdrl=$datos['inf_vdrl'];
      $tratamiento_instaurado=$datos['tratamiento_instaurado'];
      if ($edad<=12) $cpod_ceod=$datos['ceod'];
        else $cpod_ceod=$datos['cpod'];
      $birads = $datos['birads'];
      $tisomf = $datos['tisomf'];
      $hemo_glic = $datos['hemo_glic'];
      $vph = $datos['vph'];
      $tratamiento_instaurado_de_cm = $datos['tratamien$tratamiento_instaurado_de_cm'];
      $financiador = $datos['financiador'];
      $porc_geo = $datos['porc_geo'];
      $porc_dbt = $datos['porc_dbt'];
      $porc_hta = $datos['porc_hta'];
      $log=$datos['log'];
    } else {
      $peso=0;
      $talla=0;
      $ta=null;
      $perim_cefalico=0;
      $edad_gestacional=0;
      $res_oido_derecho=null;
      $res_oido_izquierdo=null;
      $retinopatia=null;
      $inf_diag_biopsia=null;
      $inf_anat_patologica=null;
      $inf_diag_anatomo=null;
      $inf_vdrl=null;
      $tratamiento_instaurado=null;
      $cpod_ceod=null;
      $birads = null;
      $tisomf = null;
      $hemo_glic = null;
      $vph = null;
      $tratamiento_instaurado_de_cm = null;
      $financiador = null;
      $porc_geo = null;
      $porc_dbt = null;
      $porc_hta = null;
      $log='No necesita datos reportables';
    }
  
    $query = "INSERT INTO facturacion.resumen_prestacion 
        (id_factura, id_comprobante, id_prestacion, cuie, nombre_efector, 
        clavebeneficiario, id_smiafiliados, dni, nombre, apellido, afifechanac,edad, 
        afisexo, fecha_comprobante, grupo_etareo, id_nomenclador, grupo, 
        codigo, descripcion, cantidad, precio_prestacion, diagnostico, 
        fecha_deposito, peso, talla, ta, perim_cefalico, edad_gestacional, 
        res_oido_derecho, res_oido_izquierdo, retinopatia, inf_diag_biopsia, 
        inf_anat_patologica, inf_diag_anatomo, inf_vdrl, tratamiento_instaurado, 
        cpod_ceod, log_table,birads,tisomf,hemo_glic,vph,tratamiento_instaurado_de_cm,financiador,
        porc_geo,porc_dbt,porc_hta) 
        VALUES 
        ($id_factura, $id_comprobante, $id_prestacion, '$cuie', '$nombre_efector', 
        '$clavebeneficiario', $id_smiafiliados, '$dni', '$nombre', '$apellido', '$afifechanac',$anios, 
        '$afisexo', '$fecha_comprobante', '$grupo_etareo', $id_nomenclador, '$grupo', 
        '$codigo', '$descripcion', $cantidad, $precio_prestacion, '$diagnostico', 
        '$fecha_deposito', '$peso', '$talla', '$ta', '$perim_cefalico', '$edad_gestacional', 
        '$res_oido_derecho', '$res_oido_izquierdo', '$retinopatia', '$inf_diag_biopsia', 
        '$inf_anat_patologica', '$inf_diag_anatomo', '$inf_vdrl', '$tratamiento_instaurado', 
        '$cpod_ceod', '$log','$birads','$tisomf','$hemo_glic,'$vph','$tratamiento_instaurado_de_cm',
        '$financiador','$porc_geo,'$porc_dbt,'$porc_hta')";
    
    $res_query = sql($query) or fin_pagina();
    $res_fact->MoveNext();
  }
 
  $query_prestacion = "SELECT * 
                      from facturacion.resumen_prestacion
                      where id_factura=$id_factura";
  $res_prestacion = sql($query_prestacion) or fin_pagina();
  }
}


if ($_POST['muestra_pres']=="Ver Prestacion"){	
	
	$sql_delete = "DELETE from facturacion.resumen_prestacion";
  sql($sql_delete) or fin_pagina();

  $id_prestacion = $_POST['id_prestacion'];
	$query_prestacion = "SELECT 
                      b.id_factura,
                      b.id_comprobante,
                      b.cuie,
                      e.nombre as nombre_efector,
                      d.clavebeneficiario,
                      d.id_smiafiliados,
                      d.afidni as dni,
                      d.afinombre as nombre,
                      d.afiapellido as apellido,
                      d.afifechanac,
                      d.afisexo,
                      a.id_prestacion,
                      b.fecha_comprobante::date,
                      
                      c.id_nomenclador,
                      c.grupo,
                      c.codigo,
                      c.descripcion,
                      a.cantidad,
                      a.precio_prestacion,
                      a.diagnostico 
                      from facturacion.prestacion a, facturacion.comprobante b, facturacion.nomenclador c, nacer.smiafiliados d, nacer.efe_conv e
                      where a.id_comprobante = b.id_comprobante
                      and a.id_nomenclador = c.id_nomenclador
                      and b.id_smiafiliados = d.id_smiafiliados
                      and b.cuie = e.cuie
                      and a.id_prestacion = $id_prestacion";

  $res_prestacion = sql($query_prestacion) or fin_pagina();
	
  $id_comprobante = $res_prestacion->fields['id_comprobante']; 
  $id_factura = $res_prestacion->fields['id_factura'];
  $cuie = $res_prestacion->fields['cuie'];
  $nombre_efector = $res_prestacion->fields['nombre_efector'];
  
  $clavebeneficiario = $res_prestacion->fields['clavebeneficiario'];
  $nombre = $res_prestacion->fields['nombre'];
  $apellido = $res_prestacion->fields['apellido'];
  $id_smiafiliados=$res_prestacion->fields['id_smiafiliados'];
  $dni=$res_prestacion->fields['dni'];  
  $afifechanac=$res_prestacion->fields['afifechanac']; 
  $edad=edad_con_meses($afifechanac,$fecha_comprobante);
  $anios=$edad['anos'];
  
  $fecha_comprobante=$res_prestacion->fields['fecha_comprobante'];
  $codigo_comp=$res_prestacion->fields['grupo'].$res_prestacion->fields['codigo'].$res_prestacion->fields['diagnostico'];

  $grupo = $res_prestacion->fields['grupo'];
  $codigo = $res_prestacion->fields['codigo'];
  $descripcion = $res_prestacion->fields['descripcion'];
  $diagnostico = $res_prestacion->fields['diagnostico'];
  
  $datos_compl = array (
    "id_factura" => $id_factura,
    "id_comprobante" => $id_comprobante,
    "id_prestacion" => $id_prestacion,
    "cuie" => $cuie,
    "nombre_efector" => $nombre_efector,
    "clavebeneficiario"=> $clavebeneficiario,
    "nombre"=>$nombre,
    "apellido"=>$apellido,
    "id_smiafiliados" => $id_smiafiliados,
    "dni"=>$dni,
    "afifechanac" => $afifechanac,
    "edad" => $anios,
    "fecha_comprobante" => $fecha_comprobante,
    "grupo"=>$grupo,
    "codigo" =>$codigo,
    "descripcion" =>$descripcion,
    "diagnostico" => $diagnostico
    );
  
  
  if (necesita_dr($codigo_comp)) {
    $datos = (get_datos_sipweb($dni,$fecha_comprobante)) ? get_datos_sipweb($dni,$fecha_comprobante): null;
    inserta_datos($datos_compl,$datos,'Desde Sipweb');
    $datos = (get_datos_trazadora_1($id_smiafiliados,$fecha_comprobante)) ? get_datos_trazadora_1($id_smiafiliados,$fecha_comprobante) : null;
    inserta_datos($datos_compl,$datos,'Desde Trazadora 1');
    $datos = (get_datos_trazadora_2($id_smiafiliados,$fecha_comprobante)) ? get_datos_trazadora_2($id_smiafiliados,$fecha_comprobante) : null;
    inserta_datos($datos_compl,$datos,'Desde Trazadora 2');
    $datos = (get_datos_prestacion($id_comprobante)) ? get_datos_prestacion($id_comprobante) : null; 
    inserta_datos($datos_compl,$datos,'Desde Prestacion');
    $datos = (get_datos_fichero($id_smiafiliados,$fecha_comprobante)) ? get_datos_fichero($id_smiafiliados,$fecha_comprobante) : null;
    inserta_datos($datos_compl,$datos,'Desde Fichero');
    $datos = (get_datos_trazadora_ninio_new($dni,$fecha_comprobante)) ? get_datos_trazadora_ninio_new($dni,$fecha_comprobante) : null;
    inserta_datos($datos_compl,$datos,'Desde Trazadora Ninios_new');
    $datos = (get_datos_trazadora_adulto($dni,$fecha_comprobante)) ? get_datos_trazadora_adulto($dni,$fecha_comprobante) : null;
    inserta_datos($datos_compl,$datos,'Desde Trazadora Adultos');
    $datos = (get_datos_trazadora_7($id_smiafiliados,$fecha_comprobante)) ? get_datos_trazadora_7($id_smiafiliados,$fecha_comprobante) : null;
    inserta_datos($datos_compl,$datos,'Desde Trazadora 7');
    $datos = (get_datos_trazadora_10($id_smiafiliados,$fecha_comprobante)) ? get_datos_trazadora_10($id_smiafiliados,$fecha_comprobante) : null; 
    inserta_datos($datos_compl,$datos,'Desde Trazadora 10');
  }
    else {
      $datos= null;
      inserta_datos($datos_compl,$datos,'No necesita datos reportables');
    };   
 
  $query_prestacion = "SELECT * 
                      from facturacion.resumen_prestacion
                      where id_prestacion=$id_prestacion";
  $res_prestacion = sql($query_prestacion) or fin_pagina();
  }


if ($_POST['genera']=="Genera TXT Con Datos Reportables"){  
  
$fecha_desde=fecha_db($_POST['fecha_desde']);
$periodo = extrae_periodo($fecha_desde);
  

$sql_fact="SELECT * from facturacion.resumen_prestacion".$periodo." 
            ORDER BY 1,2";

$res_fact=sql($sql_fact) or fin_pagina();
$filename = 'Prestaciones_pagas_'.$periodo.'.txt';  //cambiar nombre

      if (!$handle = fopen($filename, 'a')) {
           echo "No se Puede abrir ($filename)";
          exit;
      }
//encabezado
$contenido="N.Factura;N.Comprob.;CUIE;Efector;clavebeneficiario;DNI;Nombre;Apellido;Fecha Nac.;Sexo;Edad;Grupo Etareo;Fecha Comp.;Grupo;Codigo;Descripcion;Cantidad;Precio;Diagnostico;Peso;Talla;TA;Perimetro Cefalico;Edad_Gestacional;Indice CPO / ceo;Resultado de otoemisiones acusticas;Grado de retinopatia;Resultado de la biopsia de mama;Resultado de la biopsia de cuello uterino;Informe de pap;Resultado mamografia (BIRADS);Resultado VDRL;Tratamiento instaurado en cancer de cuello uterino / lesiones precancerosas;TISOMF;Resultado de Hemoglobina glicosilada;Lectura de Test VPH;IMC;Percentilo del IMC;Puntaje Z del IMC;% de personas a cargo georreferenciadas;% de personas con DBT 2 con una hemoglobina glicosilada;% de personas con HTA con al menos un control por HTA;Tratamiento instaurado en cáncer de mama;Financiador del medicamento dispensado;Fecha Deposito";   
$contenido=strtoupper($contenido);
$contenido.="\r";
$contenido.="\n";
if (fwrite($handle, $contenido) === FALSE) {
            echo "No se Puede escribir  ($filename)";
            exit;
        }
    
$res_fact->movefirst();
  while (!$res_fact->EOF) {
      $id_smiafiliados=$res_fact->fields['id_smiafiliados'];
      $dni=$res_fact->fields['dni'];  
      
      $codigo_comp=$res_fact->fields['grupo'].$res_fact->fields['codigo'].$res_fact->fields['diagnostico'];
      $fecha_nac=$res_fact->fields['afifechanac'];
      $fecha_comprobante=$res_fact->fields['fecha_comprobante'];
      $edad=edad_con_meses($fecha_nac,$fecha_comprobante);
      $anios=$edad['anos'];
      $diagnostico  = $res_fact->fields['diagnostico'];
      $id_comprobante = $res_fact->fields['id_comprobante'];

      //se escribe el archivo
      $contenido=$res_fact->fields['id_factura'].";";
      $contenido.=$res_fact->fields['id_comprobante'].";";
      $contenido.=$res_fact->fields['cuie'].";";
      $contenido.=utf8_decode($res_fact->fields['nombre_efector']).";";
      $contenido.=$res_fact->fields['clavebeneficiario'].";";
      $contenido.=$dni.";";
      $contenido.=utf8_decode($res_fact->fields['nombre']).";";
      $contenido.=utf8_decode($res_fact->fields['apellido']).";";
      $contenido.=fecha($res_fact->fields['afifechanac']).";";
      $contenido.=trim($res_fact->fields['afisexo']).";";
      $contenido.=$anios.";";
      $contenido.=$res_fact->fields['grupo_etareo'].";";
      $contenido.=fecha($res_fact->fields['fecha_comprobante']).";";
      $contenido.=$res_fact->fields['grupo'].";";
      $contenido.=$res_fact->fields['codigo'].";";
      $contenido.=utf8_decode($res_fact->fields['descripcion']).";";
      $contenido.=$res_fact->fields['cantidad'].";";
      $contenido.=number_format($res_fact->fields['precio_prestacion'],2,'.',',').";";
      $contenido.=$res_fact->fields['diagnostico'].";";
            
      $contenido.=$res_fact->fields['peso'].";";
      $contenido.=$res_fact->fields['talla'].";";
      $contenido.=$res_fact->fields['ta'].";";      
      $contenido.=$res_fact->fields['perim_cefalico'].";";
      $contenido.=$res_fact->fields['edad_gestacional'].",0;";
      $contenido.=$res_fact->fields['cpod_ceod'].";";
      $contenido.=$res_fact->fields['res_oido_derecho'].$res_fact->fields['res_oido_izquierdo'].";";
      $contenido.=$res_fact->fields['retinopatia'].";";
      $contenido.=$res_fact->fields['inf_diag_biopsia'].";";
      $contenido.=$res_fact->fields['inf_anat_patologica'].";";
      $contenido.=$res_fact->fields['inf_diag_anatomo'].";";
      $contenido.=$res_fact->fields['birads'].";";
      $contenido.=$res_fact->fields['inf_vdrl'].";";
      $contenido.=$res_fact->fields['tratamiento_instaurado'].";";      
      //$contenido.=$res_fact->fields['log_table'].";";
      $contenido.=$res_fact->fields['tisomf'].";";
      $contenido.=$res_fact->fields['hemo_glic'].";";
      $contenido.=$res_fact->fields['vph'].";";
      if ($res_fact->fields['peso']<>0 and $res_fact->fields['talla']<>0)
        $imc = $peso/(($talla*100)*($talla*100));
        else $imc = 0 ;      
      $contenido.=number_format($imc,2,'.',',').";";
      $contenido.=";";// Percentilo del IMC
      $contenido.=";";//Puntaje Z del IMC
      $contenido.=$res_fact->fields['porc_geo'].";";//% de personas a cargo georreferenciadas
      $contenido.=$res_fact->fields['porc_dbt'].";";//% de personas con DBT 2 con una hemoglobina glicosilada
      $contenido.=$res_fact->fields['porc_hta'].";";//% de personas con HTA con al menos un control por HTA
      $contenido.=$res_fact->fields['tratamiento_instaurado_de_cm'].";";//Tratamiento instaurado en cáncer de mama
      $contenido.=$res_fact->fields['financiador'].";";//Financiador del medicamento dispensado
      $contenido.=fecha($res_fact->fields['fecha_deposito']).";";
      $contenido.="\r";
      $contenido.="\n";
        if (fwrite($handle, $contenido) === FALSE) {
            echo "No se Puede escribir  ($filename)";
            exit;
        }
      
      $res_fact->MoveNext();
    }
      
  if (fwrite($handle, '----FIN DE ARCHIVOS-----') === FALSE) {
        echo "No se Puede escribir  ($filename)";
        exit;
  }

  echo "El Archivo ($filename) se genero con exito";

  fclose($handle);
}

/*
if ($_POST['genera']=="Genera TXT Consultas"){  
  
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);
  
  $sql_fact="SELECT a.id_factura,
  c.id_comprobante,
  g.cuie,
  g.nombre as nombre_efector,
  f.id_smiafiliados,
  f.afidni as dni,
  f.afinombre as nombre,
  f.afiapellido as apellido,
  f.afifechanac,
  f.afisexo,
  d.id_prestacion,
  c.fecha_comprobante,
  c.grupo_etareo,
  d.id_nomenclador,
  e.grupo,
  e.codigo,
  e.descripcion,
  d.cantidad,
  d.precio_prestacion,
  d.diagnostico,
  h.fecha_deposito
  
from expediente.expediente a, facturacion.factura b, facturacion.comprobante c, facturacion.prestacion d, facturacion.nomenclador e, nacer.smiafiliados f, nacer.efe_conv g,
contabilidad.ingreso h

where a.id_factura=b.id_factura
and a.id_factura=c.id_factura
and c.id_comprobante = d.id_comprobante
and d.id_nomenclador = e.id_nomenclador  
and c.id_smiafiliados = f.id_smiafiliados
and a.id_efe_conv = g.id_efe_conv
and h.numero_factura = b.id_factura

and  a.estado='C' and h.fecha_deposito between '$fecha_desde' and '$fecha_hasta' 
order by 1,2
";
$res_fact=sql($sql_fact) or fin_pagina();
$filename = 'Prestaciones_pagas_'.$fecha_desde.'_'.$fecha_hasta.'(sin DR).txt';  //cambiar nombre

      if (!$handle = fopen($filename, 'a')) {
           echo "No se Puede abrir ($filename)";
          exit;
      }
//encabezado
$contenido="N.Factura;N.Comprob.;CUIE;Efector;DNI;Nombre;Apellido;Fecha Nac.;Sexo;Edad;Grupo Etareo;Fecha Comp.;Grupo;Codigo;Descripcion;Cantidad;Precio;Diagnostico;Fecha Deposito";   
$contenido=strtoupper($contenido);
$contenido.="\r";
$contenido.="\n";
if (fwrite($handle, $contenido) === FALSE) {
            echo "No se Puede escribir  ($filename)";
            exit;
        }
    
$res_fact->movefirst();
  while (!$res_fact->EOF) {
      $id_smiafiliados=$res_fact->fields['id_smiafiliados'];
      $dni=$res_fact->fields['dni'];  
      
      $codigo_comp=$res_fact->fields['grupo'].$res_fact->fields['codigo'].$res_fact->fields['diagnostico'];
      $fecha_nac=$res_fact->fields['afifechanac'];
      $fecha_comp=$res_fact->fields['fecha_comprobante'];
      $edad=edad_con_meses($fecha_nac,$fecha_comp);
      $anios=$edad['anos'];


      $contenido=$res_fact->fields['id_factura'].";";
      $contenido.=$res_fact->fields['id_comprobante'].";";
      $contenido.=$res_fact->fields['cuie'].";";
      $contenido.=utf8_decode($res_fact->fields['nombre_efector']).";";
      $contenido.=$dni.";";
      $contenido.=utf8_decode($res_fact->fields['nombre']).";";
      $contenido.=utf8_decode($res_fact->fields['apellido']).";";
      $contenido.=fecha($res_fact->fields['afifechanac']).";";
      $contenido.=trim($res_fact->fields['afisexo']).";";
      $contenido.=$anios.";";
      $contenido.=$res_fact->fields['grupo_etareo'].";";
      $contenido.=fecha($res_fact->fields['fecha_comprobante']).";";
      $contenido.=$res_fact->fields['grupo'].";";
      $contenido.=$res_fact->fields['codigo'].";";
      $contenido.=utf8_decode($res_fact->fields['descripcion']).";";
      $contenido.=$res_fact->fields['cantidad'].";";
      $contenido.=number_format($res_fact->fields['precio_prestacion'],2,'.',',').";";
      $contenido.=$res_fact->fields['diagnostico'].";";
      $contenido.=fecha($res_fact->fields['fecha_deposito']).";";
      $contenido.="\r";
      $contenido.="\n";
        if (fwrite($handle, $contenido) === FALSE) {
            echo "No se Puede escribir  ($filename)";
            exit;
        }
      
      $res_fact->MoveNext();
      }
      echo "El Archivo ($filename) se genero con exito";
    
      fclose($handle);

}*/

if ($_POST['genera_excel']=="Genera EXCEL"){  
  
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $periodo = extrae_periodo($fecha_desde);
  //$link=encode_link("listado_comprobantes_auditoria_excel.php",array("fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta)); 
  $link=encode_link("listado_comprobantes_auditoria_excel.php",array("periodo"=>$periodo)); ?>

  <script>
  window.open('<?php echo $link?>')
  </script>
  <?
  

}

if ($_POST['genera']=='Genera TXT-FASE 1') {
  
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);

  fase_1($fecha_desde,$fecha_hasta);
}

if ($_POST['genera']=='Genera TXT-FASE 2') {
  
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);

  fase_2($fecha_desde,$fecha_hasta);
}

if ($_POST['genera']=='Genera TXT-FASE 3') {
  
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);

  fase_3($fecha_desde,$fecha_hasta);
}

if ($_POST['genera']=='Genera TXT-FASE 3 R') {
  
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);

  fase_3_reparado($fecha_desde,$fecha_hasta);
}

echo $html_header;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $html_root?>/modulos/contabilidad/css/jquery.dataTables.css" />
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function() {
    $('#prestacion').DataTable( {
        
        "columnDefs": [
          { "width": "2%" },
          { "width": "2%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "2%" },
          { "width": "2%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" }
        ],
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select style="width:100%"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j )      {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    } );
} );
</script>


<script>
function control_id_factura(){
  if ( document.all.id_factura.value=="" ){
    alert("Debe ingresar un numero de factura");
    document.all.id_factura.focus();
    return false;
  }
}

function control_id_prestacion(){
  if ( document.all.id_prestacion.value=="" ){
    alert("Debe ingresar un numero de Prestacion");
    document.all.id_prestacion.focus();
    return false;
  }
}


function control_muestra()
{ 
 if(document.all.fecha_desde.value==""){
  alert('Debe Ingresar una Fecha DESDE');
  return false;
 } 
 if(document.all.fecha_hasta.value==""){
  alert('Debe Ingresar una Fecha HASTA');
  return false;
 } 
 if(document.all.fecha_hasta.value<document.all.fecha_desde.value){
  alert('La Fecha HASTA debe ser MAYOR 0 IGUAL a la Fecha DESDE');
  return false;
 }
 if(document.all.fecha_desde.value.indexOf("-")!=-1){
	  alert('Debe ingresar un fecha en el campo DESDE');
	  return false;
	 }
if(document.all.fecha_hasta.value.indexOf("-")!=-1){
	  alert('Debe ingresar una fecha en el campo HASTA');
	  return false;
	 }
return true;
}
</script>

<form name='form1' action='listado_comprobantes_auditoria_old.php' method='POST'>

<?php echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<table cellspacing=2 border=0 width=100% align=center>
  <td align=left><tr>
  <table cellspacing=2 border=1 width=80% align=center>  
  <?php if ($usuario!='sebastian') $disable = 'disabled';
          else $disable = 'enabled';?>
    <tr>
    <br>
    <td align=center>
    <br>
    <b><font size="2">N° Factura</font></b>	
    <input type=text id="id_factura" name="id_factura" size=15>
    &nbsp;&nbsp;&nbsp;
    <input type="submit" class="btn btn-warning" name="muestra_fact" value='Ver Factura' onclick="return control_id_factura()" >
    
    <td align=center>
    <br>
    <b><font size="2">N° Prestacion</font></b>	
    <input type=text id="id_prestacion" name="id_prestacion" size=15>
    &nbsp;&nbsp;&nbsp;
    <input type="submit" class="btn btn-warning" name="muestra_pres" value='Ver Prestacion' onclick="return control_id_prestacion()" >
    </table>
    </td>
    </tr>
    
    <td align=right> 
    <table cellspacing=2 border=1 width=80% align=center>  
    <tr>
    <br>
    <td align=center>
    <b><font size="2">Fecha de Depositos	
    Desde:</font></b> <input type=text id="fecha_desde" name="fecha_desde" value='<?=$fecha_desde?>' size=15>
    <?php echo link_calendario("fecha_desde");?>
        
    <b><font size="2">Hasta: </font></b><input type=text id="fecha_hasta" name="fecha_hasta" value='<?=$fecha_hasta?>' size=15>
    <?php echo link_calendario("fecha_hasta");?>
    </br>
    </br>

    &nbsp;&nbsp;&nbsp;
    <input type="submit" class="btn btn-info" name="muestra" value='Muestra' onclick="return control_muestra()" <?php echo $disable;?> title="Esta funcion genera la tabla resumen_prestacion_periodo">
    &nbsp;&nbsp;&nbsp;
    <input type="submit" class="btn btn-info" name="genera" value='Genera TXT Con Datos Reportables' onclick="return control_muestra()" <?php echo $disable;?> title="Genera archivo plano para auditoria desde las tablas de facturacion.resumen_prestacion(periodo)">
    &nbsp;&nbsp;&nbsp;
    <!--<input type="submit" class="btn btn-warning" name="genera" value='Genera TXT Sin Datos Reportables' onclick="return control_muestra()">-->
    &nbsp;&nbsp;&nbsp;
    <input type="submit" class="btn btn-info" name="genera_excel" value='Genera EXCEL' onclick="return control_muestra()" title ="Genera archivo Excel para auditoria desde las tablas de facturacion.resumen_prestacion(periodo)" disabled>
    &nbsp;&nbsp;&nbsp;
    <input type="submit" class="btn btn-danger" name="detalle_prestaciones" value='Detalle de Prestaciones para Control'<?php echo $disable;?> 
    title="Esta funcion analiza por fecha de comprobante desde el dia actual, 30 dias para atras, depsues hay que correr el script CONTROL_DETALLE_PRESTACION">
     
    </br>
    </br>
    <input type="submit" class="btn btn-success" name="genera" value='Genera TXT-FASE 1' onclick="return control_muestra()" disabled>
    &nbsp;&nbsp;
    
    <input type="submit" class="btn btn-success" name="genera" value='Genera TXT-FASE 2' onclick="return control_muestra()" disabled>
    &nbsp;&nbsp;
    
    <input type="submit" class="btn btn-success" name="genera" value='Genera TXT-FASE 3' onclick="return control_muestra()" <?php echo $disable;?> title="Funcion para generar salida para SIRGE2">

    <input type="submit" class="btn btn-success" name="genera" value='Genera TXT-FASE 3 R' onclick="return control_muestra()" <?php echo $disable;?> title="Funcion para generar salida para SIRGE2 con prestaciones reparadas">    
    </table>
    </td>
  </tr>     
</table>

<table width="98%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <?php if ($_POST['muestra']=="Muestra") {?> 
    <td>
    	<font size=+1><b><?echo "Desde: ".fecha($fecha_desde)." Hasta: ".fecha($fecha_hasta)?> </b></font>        
    </td>
    <?php }
    if ($muestra_fact=="Ver Factura") {?>
    <td>
    	<font size=+1><b><?echo "Factura: ".$id_factura?> </b></font>
    </td>
    <?php }?>
 </tr>
 <tr><td>
 
<?php if ($_POST['muestra_fact']=='Ver Factura'){?>

<tr><td><table id="prestacion" width=95% align="center" class="table table-striped" style="border:thin groove">
        <thead>
            <tr>
            <th align="center">Nº Factura</th>
            <th align="center">Nº Comprob.</th>
            <th align="center">Nº Prest.</th>
            <th align="center">CUIE</th>
            <th align="center">Efector</th>
            <th align="center">DNI</th>
            <th align="center">Nombre</th>
            <!--<th align="center">Apellido</th>-->
            <th align="center">Fecha Nac.</th>
            <th align="center">Grupo Etareo</th>
            <th align="center">Sexo</th>
            <th align="center">Fecha Comp.</th>
            <th align="center">Codigo</th>
            <th align="center">Descripcion</th>
            <!--<th align="center">Cantidad</th>-->
            <th align="center">Precio</th>
            <th align="center">Diagnostico</th>
            <th align="center">Peso</th>
            <th align="center">Talla</th>
            <th align="center">TA</th>
            <th align="center">P.Cefalico</th>
            <th align="center">E.Gest.</th>
            <!--<th align="center">Oido Der.</th>
            <th align="center">Oido Izq.</th>
            <th align="center">Retinopat.</th>
            <th align="center">Diag.biopsia.</th>
            <th align="center">Anat.patologica</th>
            <th align="center">Diag.anatomo</th>
            <th align="center">Vdrl</th>
            <th align="center">Trat.instaurado</th>-->
            <th align="center">birads</th>
            <th align="center">tisomf</th>
            <th align="center">hemo_glic</th>
            <th align="center">vph</th>
            <th align="center">tratamiento_instaurado_de_cm</th>
            <th align="center">financiador</th>
            <th align="center">porc_geo</th>
            <th align="center">por_dbt</th>
            <th align="center">porc_hta</th>
            <th align="center">log</th>
            <th align="center">Fecha Deposito</th>
            </tr>
        </thead>
 
        <!--<tfoot>
            <tr>
            <th align="center">Nº Factura</th>
            <th align="center">Nº Comprob.</th>
            <th align="center">CUIE</th>
            <th align="center">Efector</th>
            <th align="center">DNI</th>
            <th align="center">Nombre</th>
            <th align="center">Apellido</th>
            <th align="center">Fecha Nac.</th>
            <th align="center">Grupo Etareo</th>
            <th align="center">Sexo</th>
            <th align="center">Fecha Comp.</th>
            <th align="center">Codigo</th>
            <th align="center">Descripcion</th>
            <th align="center">Cantidad</th>
            <th align="center">Precio</th>
            <th align="center">Diagnostico</th>
            <th align="center">Peso</th>
            <th align="center">Talla</th>
            <th align="center">TA</th>
            <th align="center">P.Cefalico</th>
            <th align="center">E.Gest.</th>
            <th align="center">Oido Der.</th>
            <th align="center">Oido Izq.</th>
            <th align="center">Retinopat.</th>
            <th align="center">Diag.biopsia.</th>
            <th align="center">Anat.patologica</th>
            <th align="center">Diag.anatomo</th>
            <th align="center">Vdrl</th>
            <th align="center">Trat.instaurado</th>
            <th align="center">cpod/ceod</th>
            <th align="center">log</th>
            <th align="center">Fecha Deposito</th>
            </tr>
        </tfoot>-->
 
        <tbody>
          <?php $res_prestacion->MoveFirst();
          while (!$res_prestacion->EOF)
          {?>
          <tr>
          <td><?php echo $res_prestacion->fields['id_factura']?></td>
          <td><?php echo $res_prestacion->fields['id_comprobante']?></td>
          <td><?php echo $res_prestacion->fields['id_prestacion']?></td>
          <td><?php echo $res_prestacion->fields['cuie']?></td>
          <td><?php echo $res_prestacion->fields['nombre_efector']?></td>
          <td><?php echo $res_prestacion->fields['dni']?></td>
          <!--<td><?php echo $res_prestacion->fields['nombre']?></td>-->
          <td><?php echo $res_prestacion->fields['apellido'].','.$res_prestacion->fields['nombre']?></td>
          <td><?php echo fecha($res_prestacion->fields['afifechanac'])?></td>
          <td><?php echo $res_prestacion->fields['grupo_etareo']?></td>
          <td><?php echo $res_prestacion->fields['afisexo']?></td>
          <td><?php echo fecha($res_prestacion->fields['fecha_comprobante'])?></td>
          <td><?php echo $res_prestacion->fields['codigo']?></td>
          <td><?php echo $res_prestacion->fields['descripcion']?></td>
          <!--<td><?php echo $res_prestacion->fields['cantidad']?></td>-->
          <td><?php echo number_format($res_prestacion->fields['precio_prestacion'],2,'.',',')?></td>
          <td><?php echo $res_prestacion->fields['diagnostico']?></td>
          <td><?php echo $res_prestacion->fields['peso']?></td>
          <td><?php echo $res_prestacion->fields['talla']?></td>
          <td><?php echo $res_prestacion->fields['ta']?></td>
          <td><?php echo $res_prestacion->fields['perim_cefalico']?></td>
          <td><?php echo $res_prestacion->fields['edad_gestacional']?></td>
          <!--<td><?php echo $res_prestacion->fields['res_oido_derecho']?></td>
          <td><?php echo $res_prestacion->fields['res_oido_izquierdo']?></td>
          <td><?php echo $res_prestacion->fields['retinopatia']?></td>
          <td><?php echo $res_prestacion->fields['inf_diag_biopsia']?></td>
          <td><?php echo $res_prestacion->fields['inf_anat_patologica']?></td>
          <td><?php echo $res_prestacion->fields['inf_diag_anatomo']?></td>
          <td><?php echo $res_prestacion->fields['inf_vdrl']?></td>
          <td><?php echo $res_prestacion->fields['tratamiento_instaurado']?></td>-->
          <td><?php echo $res_prestacion->fields['cpod_ceod']?></td>
          <td><?php echo $res_prestacion->fields['birads']?></td>
          <td><?php echo $res_prestacion->fields['tisomf']?></td>
          <td><?php echo $res_prestacion->fields['hemo_glic']?></td>
          <td><?php echo $res_prestacion->fields['vph']?></td>
          <td><?php echo $res_prestacion->fields['tratamiento_instaurado_de_cm']?></td>
          <td><?php echo $res_prestacion->fields['financiador']?></td>
          <td><?php echo $res_prestacion->fields['porc_geo']?></td>
          <td><?php echo $res_prestacion->fields['porc_dbt']?></td>
          <td><?php echo $res_prestacion->fields['porc_hta']?></td>
          <td><?php echo $res_prestacion->fields['log_table']?></td>
          <td><?php echo fecha($res_prestacion->fields['fecha_deposito'])?></td>
          </tr>
          <?php $res_prestacion->MoveNext();
          }?>
            
        </tbody>
    </table>
 </td></tr>
<?php } ?>

<?php if ($_POST['muestra_pres']=='Ver Prestacion'){?>

  <tr><td><table id="prestacion" width=95% align="center" class="table table-striped" style="border:thin groove">
          <thead>
              <tr>
              <th align="center">Nº Pres.</th>
              <th align="center">Nº Comprob.</th>
              <th align="center">CUIE</th>
              <th align="center">Efector</th>
              <th align="center">DNI</th>
              <th align="center">Nombre</th>
              <th align="center">Fecha Nac.</th>
              <th align="center">Fecha Comp.</th>
              <th align="center">Codigo</th>
              <th align="center">Descripcion</th>
              <th align="center">Diagnostico</th>
              <th align="center">Peso</th>
              <th align="center">Talla</th>
              <th align="center">TA</th>
              <th align="center">P.Cefalico</th>
              <th align="center">E.Gest.</th>
              <!--<th align="center">Oido Der.</th>
              <th align="center">Oido Izq.</th>
              <th align="center">Retinopat.</th>
              <th align="center">Diag.biopsia.</th>
              <th align="center">Anat.patologica</th>
              <th align="center">Diag.anatomo</th>
              <th align="center">Vdrl</th>
              <th align="center">Trat.instaurado</th>-->
              <th align="center">cpod/ceod</th>
              <th align="center">birads</th>
              <th align="center">tisomf</th>
              <th align="center">hemo_glic</th>
              <th align="center">vph</th>
              <th align="center">tratamiento_instaurado_de_cm</th>
              <th align="center">financiador</th>
              <th align="center">porc_geo</th>
              <th align="center">porc_dbt</th>
              <th align="center">porc_hta</th>
              <th align="center">log</th>
              </tr>
          </thead>
   
          <!--<tfoot>
              <tr>
              <th align="center">Nº Factura</th>
              <th align="center">Nº Comprob.</th>
              <th align="center">CUIE</th>
              <th align="center">Efector</th>
              <th align="center">DNI</th>
              <th align="center">Nombre</th>
              <th align="center">Apellido</th>
              <th align="center">Fecha Nac.</th>
              <th align="center">Grupo Etareo</th>
              <th align="center">Sexo</th>
              <th align="center">Fecha Comp.</th>
              <th align="center">Codigo</th>
              <th align="center">Descripcion</th>
              <th align="center">Cantidad</th>
              <th align="center">Precio</th>
              <th align="center">Diagnostico</th>
              <th align="center">Peso</th>
              <th align="center">Talla</th>
              <th align="center">TA</th>
              <th align="center">P.Cefalico</th>
              <th align="center">E.Gest.</th>
              <th align="center">Oido Der.</th>
              <th align="center">Oido Izq.</th>
              <th align="center">Retinopat.</th>
              <th align="center">Diag.biopsia.</th>
              <th align="center">Anat.patologica</th>
              <th align="center">Diag.anatomo</th>
              <th align="center">Vdrl</th>
              <th align="center">Trat.instaurado</th>
              <th align="center">cpod/ceod</th>
              <th align="center">log</th>
              <th align="center">Fecha Deposito</th>
              </tr>
          </tfoot>-->
   
          <tbody>
            <?php $res_prestacion->MoveFirst();
            while (!$res_prestacion->EOF)
            {?>
            <tr>
            <td><?php echo $res_prestacion->fields['id_prestacion']?></td>
            <td><?php echo $res_prestacion->fields['id_comprobante']?></td>
            <td><?php echo $res_prestacion->fields['cuie']?></td>
            <td><?php echo $res_prestacion->fields['nombre_efector']?></td>
            <td><?php echo $res_prestacion->fields['dni']?></td>
            <!--<td><?php echo $res_prestacion->fields['nombre']?></td>-->
            <td><?php echo $res_prestacion->fields['apellido'].','.$res_prestacion->fields['nombre']?></td>
            <td><?php echo fecha($res_prestacion->fields['afifechanac'])?></td>
            <td><?php echo fecha($res_prestacion->fields['fecha_comprobante'])?></td>
            <td><?php echo $res_prestacion->fields['codigo']?></td>
            <td><?php echo $res_prestacion->fields['descripcion']?></td>
            <td><?php echo $res_prestacion->fields['diagnostico']?></td>
            <td><?php echo $res_prestacion->fields['peso']?></td>
            <td><?php echo $res_prestacion->fields['talla']?></td>
            <td><?php echo $res_prestacion->fields['ta']?></td>
            <td><?php echo $res_prestacion->fields['perim_cefalico']?></td>
            <td><?php echo $res_prestacion->fields['edad_gestacional']?></td>
            <!--<td><?php echo $res_prestacion->fields['res_oido_derecho']?></td>
            <td><?php echo $res_prestacion->fields['res_oido_izquierdo']?></td>
            <td><?php echo $res_prestacion->fields['retinopatia']?></td>
            <td><?php echo $res_prestacion->fields['inf_diag_biopsia']?></td>
            <td><?php echo $res_prestacion->fields['inf_anat_patologica']?></td>
            <td><?php echo $res_prestacion->fields['inf_diag_anatomo']?></td>
            <td><?php echo $res_prestacion->fields['inf_vdrl']?></td>
            <td><?php echo $res_prestacion->fields['tratamiento_instaurado']?></td>-->
            <td><?php echo $res_prestacion->fields['cpod_ceod']?></td>
            <td><?php echo $res_prestacion->fields['birads']?></td>
            <td><?php echo $res_prestacion->fields['tisomf']?></td>
            <td><?php echo $res_prestacion->fields['vph']?></td>
            <td><?php echo $res_prestacion->fields['tratamiento_instaurado_de_cm']?></td>
            <td><?php echo $res_prestacion->fields['financiador']?></td>
            <td><?php echo $res_prestacion->fields['porc_geo']?></td>
            <td><?php echo $res_prestacion->fields['porc_dbt']?></td>
            <td><?php echo $res_prestacion->fields['por_hta']?></td>
            <td><?php echo fecha($res_prestacion->fields['fecha_deposito'])?></td>
            </tr>
            <?php $res_prestacion->MoveNext();
            }?>
              
          </tbody>
      </table>
   </td></tr>
  <?php } ?>


<?php if ($_POST['muestra']=="Muestra") {?> 
<tr><td align="center"><h4>Tabla <b>facturacion.resumen_prestacion</b> completa desde <?php echo $fecha_desde?> hasta <?php echo $fecha_hasta?> para revision con script CONTROLES_AUDITORIA_1(controles despues de llenar resumen_prestacion) </h4></td></tr>
<?php }?> 

<?php if ($_POST['detalle_prestacion']=="Detalle de Prestaciones para Control") {
      $fecha_hasta=date("Y-m-d");
      $fecha_desde=date("Y-m-d", strtotime("- 30 day"));?> 
<tr><td align="center"><h4>Tabla <b>facturacion.resumen_prestacion_auditoria</b> lista para control <?php echo $fecha_desde?> hasta <?php echo $fecha_hasta?></h4></td></tr>
<?php }?> 

<BR>
 <tr><td><table width=90% align="center" class="bordes">
  <tr align="center">
 	<td>
    <input type=button class="btn btn-primary" name="volver" value="Volver" onclick="document.location='listado_comprobantes_auditoria.php'"title="Volver al Listado" style="width=150px">     
  </td>
  </tr>
 </table></td></tr>
 
 
 </table>
 </form>
 
 <?php echo fin_pagina();?>
