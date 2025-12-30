	<?
//version 2.3 - Mayo 2022
require_once ("../../config.php");
require_once ("funciones_edad.php");
require_once ("funciones_auditoria.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

function extrae_anio($fecha) {
        list($d,$m,$a) = explode("/",$fecha);
        //$a=$a+2000;
        return $a;
		}

if ($_POST['genera']=="Genera TXT-FASE 1"){  
  
  //control de caracteres
  $sql_ctrol = "UPDATE nacer.smiafiliados set afiapellido = ccc.fix_afiapellido
        from (
          select *, replace(afiapellido,'Ã‘','Ñ') as fix_afiapellido 
          from nacer.smiafiliados
          where  afiapellido ilike '%Ã‘%'
        ) as ccc
        where smiafiliados.id_smiafiliados = ccc.id_smiafiliados";
  sql($sql_ctrol);

  $sql_ctrol = "UPDATE nacer.smiafiliados set afinombre = ccc.fix_afinombre
        from (
          select *,replace(afinombre,'Ã‘','Ñ') as fix_afinombre
          from nacer.smiafiliados
          where  afinombre ilike '%Ã‘%'
        ) as ccc
        where smiafiliados.id_smiafiliados = ccc.id_smiafiliados";
  sql($sql_ctrol);

  $sql_ctrol = "UPDATE nacer.smiafiliados
      set afinombre = ccc.nombre_fix
      from (
      select id_smiafiliados, afinombre, replace(afinombre,'Ã“','O') as nombre_fix
      from nacer.smiafiliados
      where afinombre ilike '%Ã“%' 
      ) as ccc
      where smiafiliados.id_smiafiliados = ccc.id_smiafiliados";
  sql($sql_ctrol);

  $sql_ctrol = "UPDATE nacer.smiafiliados
      set afiapellido = ccc.apellido_fix
      from (
      select id_smiafiliados, afiapellido, replace(afiapellido,'Ã“','O') as apellido_fix
      from nacer.smiafiliados
      where afiapellido ilike '%Ã“%'
      ) as ccc
      where smiafiliados.id_smiafiliados = ccc.id_smiafiliados";
  sql($sql_ctrol);
  //fin control

  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);
  
  $sql_fact="SELECT --expediente.transaccion.id_expediente,
  d.id_prestacion, --VAR_01
  c.id_comprobante , --VAR_01
  d.id_nomenclador, --VAR_EXTRA
  e.grupo, --VAR_02
  e.codigo,--VAR_02
  d.diagnostico,--VAR_02
  e.descripcion,--VAR_EXTRA
  g.cuie, --VAR_03
  g.nombre as nombre_efector, --VAR_EXTRA
  --facturacion.prestacion.fecha_prestacion,
  c.fecha_comprobante::date, --VAR_04
  f.id_smiafiliados, --VAR_EXTRA
  c.grupo_etareo, --VAR_EXTRA
  f.afiapellido as apellido, --VAR_05
  f.afinombre as nombre, --VAR_06
  f.clavebeneficiario, --VAR_07
  f.afitipodoc, --VAR_08
  f.aficlasedoc, --VAR_09  
  f.afidni as dni, --VAR_10
  f.afisexo, --VAR_11
  f.afifechanac, --VAR_12
  d.precio_prestacion, --VAR_13
  d.cantidad, --VAR_14
  d.cantidad*d.precio_prestacion as importe_subtotal, --VAR_15
  a.id_factura, --VAR_16
  --VAR_17->numero de factura = Id_factura
  b.fecha_factura::date, --VAR_18
  b.monto_prefactura, --VAR_19
  case when b.fecha_control is null then a.fecha_ing else b.fecha_control end as fecha_control, --consultar VAR_20
  b.alta_comp, --VAR_21
  a.id_expediente, --VAR_22
  a.fecha_ing, --VAR_23
  --VAR_24 -> VAR_13
  --VAR_25 -> VAR_14
  --VAR_26 -> VAR_15
  a.monto, --VAR_26 (CONSULTAR)
  --VAR_27 -> numero de comprobante de extracto bancario

  --VAR_38-> los digitos intermedios del id_op
  b.nro_exp_ext as id_op,  --VAR_39
  --VAR_40-> los 4 primeros digitos junto con los dos ultimos digitos para formar la fecha
  i.total_pagar, --VAR_41
  a.nro_exp, --VAR_42 -- realmente viene de expediente.transaccion estado=A y id_area=1 para fase 1 y fase 2 viene null y no hay problema
  
  h.fecha_deposito, --VAR_43
  --VAR_44 -> Suma de todas las facturas del mismo expediente   
  i.fecha_inf as fecha_inf_efector --VAR_45    

from expediente.expediente a, facturacion.factura b, facturacion.comprobante c,facturacion.prestacion d,
	facturacion.nomenclador e, nacer.smiafiliados f, nacer.efe_conv g, contabilidad.ingreso h, expediente.transaccion i


--FASE 1: facturas en circuito de pago no pagas
where a.id_factura = b.id_factura
and b.id_factura=c.id_factura
and c.id_comprobante=d.id_comprobante
and d.id_nomenclador=e.id_nomenclador
and c.id_smiafiliados=f.id_smiafiliados
and a.id_efe_conv=g.id_efe_conv
and b.id_factura=h.numero_factura

--FASE 1 : para expediente con facturas cerras en circuito de pago
and b.id_factura=i.id_factura and i.estado='V'
and b.estado='C' and i.fecha_mov between '$fecha_desde' and '$fecha_hasta'
order by 1,2";
$res_fact=sql($sql_fact) or fin_pagina();
$filename = 'Prestaciones_fase1_'.$fecha_desde.'_'.$fecha_hasta.'.txt';  //cambiar nombre

      if (!$handle = fopen($filename, 'a')) {
           echo "No se Puede abrir ($filename)";
          exit;
      }

//encabezado NUEVO
/*$contenido="Id_Prestación;Codigo_Prestación;CUIE_Efector;Fecha_Prestacion;Apellido_Beneficiario;NombreBeneficiario;Clave_Beneficiario;Benef_Tipo_Documento;Benef_Clase_Documento;Benef_Nro_Documento;Sexo;Feha de Nacimiento;Valor_Unitario_facturado;Cantidad_facturada;Importe_Prestacion_Facturado;id_factura;nmero_fact;fecha_fact;Importe_Total_Factura;fecha_recepcion_fact;Alta complejidad;id_liquidacion;fecha iquidacion;Valor_Unitario_aprobado;Cantidad_aprobada;importe_Prestación_Aprobado;Numero de omprobante Extracto Bcario;id_dato_reportable_1;dato_reportable_1;id_dato_reportable_2;dato_reportable_2;id_dato_reportable_3;dato_reportable_3;id_dato_reportable_4;dato_reportable_4;id_dato_reportable_;dato_reportable_5;id_op;numero_op;fecha_op;importe_total_op;numero_expte;fecha_debito_bancario;importe_debito_bancario;fecha_notificacion_efector";
$contenido.="\r";
$contenido.="\n";  */ 
$contenido=''; 

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


    $contenido=$res_fact->fields['id_prestacion'].";";
    $contenido.=$codigo_comp.";";
    $contenido.=$res_fact->fields['cuie'].";";
    $contenido.=$res_fact->fields['fecha_comprobante'].";";
    $contenido.=$res_fact->fields['apellido'].";";
    $contenido.=$res_fact->fields['nombre'].";";
    $contenido.=$res_fact->fields['clavebeneficiario'].";";
    $contenido.=$res_fact->fields['afitipodoc'].";";
    $contenido.=$res_fact->fields['aficlasedoc'].";";
    $contenido.=$res_fact->fields['dni'].";";
    $contenido.=trim($res_fact->fields['afisexo']).";";
    $contenido.=$res_fact->fields['afifechanac'].";";
    $contenido.=number_format($res_fact->fields['precio_prestacion'],2,'.','').";";
    $contenido.=$res_fact->fields['cantidad'].";";
    $contenido.=number_format($res_fact->fields['importe_subtotal'],2,'.','').";";
    $contenido.=$res_fact->fields['id_factura'].";";
    $contenido.=$res_fact->fields['id_factura'].";";//este es el numero de factura-REVISAR
    $contenido.=$res_fact->fields['fecha_factura'].";";
    $contenido.=number_format($res_fact->fields['monto_prefactura'],2,'.','').";";
    $contenido.=$res_fact->fields['fecha_control'].";";
    $contenido.=($res_fact->fields['alta_comp']=='SI')?'S;':'N;';
    $contenido.=$res_fact->fields['id_expediente'].";";
    $contenido.=$res_fact->fields['fecha_ing'].";";
    $contenido.=number_format($res_fact->fields['precio_prestacion'],2,'.','').";";
    $contenido.=$res_fact->fields['cantidad'].";";
    $contenido.=number_format($res_fact->fields['monto'],2,'.','').";";
    //$contenido.=$res_fact->fields['VAR_027-numero_compro_Extr_banc'].";";
    
    //datos reportables
    
    //fin datos reportables
    
    $numero_op=$res_fact->fields['id_op'];
  
    if ($numero_op) {
      if (strlen($numero_op)==9) {
          $substr_anio='20'.substr($numero_op, -2);
          $substr_mes='0'.substr($numero_op,0,1);
          $substr_dia=substr($numero_op,1,2);
          $var_40_fecha_op=$substr_anio.'-'.$substr_mes.'-'.$substr_dia;
          $id_op_str=substr($numero_op,3,-2);
      } else {
          $substr_anio='20'.substr($numero_op, -2);
          $substr_mes=substr($numero_op,0,2);
          $substr_dia=substr($numero_op,2,2);
          $var_40_fecha_op=$substr_anio.'-'.$substr_mes.'-'.$substr_dia;
          $id_op_str=substr($numero_op,4,-2);
        }
      
      $contenido.=$id_op_str.';';
      $contenido.=$numero_op.';';
      $contenido.=$var_40_fecha_op.';';
      } else {
        $contenido.=';;;';
      }
    $contenido.=$res_fact->fields['total_pagar'].";";
    $contenido.=$res_fact->fields['nro_exp'].";";
    $contenido.=$res_fact->fields['fecha_deposito'].";";
    $sql_total_exp="SELECT sum(monto_prefactura) as monto_exp from facturacion.factura where nro_exp_ext='$id_exp_ext'";
    $res_sql_total=sql($sql_total_exp) or fin_pagina();

    $contenido.=$res_sql_total->fields['monto_exp'].";";
    $contenido.=$res_fact->fields['fecha_inf_efector']."\r";
    //$contenido.='A'."\r"; //Tipo no va
    $contenido.="\n";
      if (fwrite($handle, $contenido) === FALSE) {
          echo "No se Puede escribir  ($filename)";
          exit;
      }
    
    $res_fact->MoveNext();
    }
    
    if (fwrite($handle, '----FIN DE ARCHIVO-----') === FALSE) {
          echo "No se Puede escribir  ($filename)";
          exit;
    }

    echo "El Archivo ($filename) se genero con exito";
  
    fclose($handle);

}

if ($_POST['genera']=="Genera TXT-FASE 2"){  
  
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);
  
$sql_fact="SELECT --expediente.transaccion.id_expediente,
  d.id_prestacion, --VAR_01
  c.id_comprobante , --VAR_01
  d.id_nomenclador, --VAR_EXTRA
  e.grupo, --VAR_02
  e.codigo,--VAR_02
  d.diagnostico,--VAR_02
  e.descripcion,--VAR_EXTRA
  g.cuie, --VAR_03
  g.nombre as nombre_efector, --VAR_EXTRA
  --facturacion.prestacion.fecha_prestacion,
  c.fecha_comprobante::date, --VAR_04
  f.id_smiafiliados, --VAR_EXTRA
  c.grupo_etareo, --VAR_EXTRA
  f.afiapellido as apellido, --VAR_05
  f.afinombre as nombre, --VAR_06
  f.clavebeneficiario, --VAR_07
  f.afitipodoc, --VAR_08
  f.aficlasedoc, --VAR_09  
  f.afidni as dni, --VAR_10
  f.afisexo, --VAR_11
  f.afifechanac, --VAR_12
  d.precio_prestacion, --VAR_13
  d.cantidad, --VAR_14
  d.cantidad*d.precio_prestacion as importe_subtotal, --VAR_15
  a.id_factura, --VAR_16
  --VAR_17->numero de factura = Id_factura
  b.fecha_factura::date, --VAR_18
  b.monto_prefactura, --VAR_19
  case when b.fecha_control is null then a.fecha_ing else b.fecha_control end as fecha_control, --consultar VAR_20
  b.alta_comp, --VAR_21
  a.id_expediente, --VAR_22
  a.fecha_ing, --VAR_23
  --VAR_24 -> VAR_13
  --VAR_25 -> VAR_14
  --VAR_26 -> VAR_15
  a.monto, --VAR_26 (CONSULTAR)
  --VAR_27 -> numero de comprobante de extracto bancario
  --VAR_38-> los digitos intermedios del id_op
  b.nro_exp_ext as id_op,  --VAR_39
  --VAR_40-> los 4 primeros digitos junto con los dos ultimos digitos para formar la fecha
  i.total_pagar, --VAR_41
  a.nro_exp, --VAR_42 -- realmente viene de expediente.transaccion estado=A y id_area=1 para fase 1 y fase 2 viene null y no hay problema
  
  h.fecha_deposito, --VAR_43
  --VAR_44 -> Suma de todas las facturas del mismo expediente   
  i.fecha_inf as fecha_inf_efector --VAR_45    

from expediente.expediente a, facturacion.factura b, facturacion.comprobante c,facturacion.prestacion d,
	facturacion.nomenclador e, nacer.smiafiliados f, nacer.efe_conv g, contabilidad.ingreso h, expediente.transaccion i

where a.id_factura = b.id_factura
and b.id_factura=c.id_factura
and c.id_comprobante=d.id_comprobante
and d.id_nomenclador=e.id_nomenclador
and c.id_smiafiliados=f.id_smiafiliados
and a.id_efe_conv=g.id_efe_conv
and b.id_factura=h.numero_factura


--FASE 2 : expedientes en situacion de aceptacion para circuito de pago 
and b.id_factura=i.id_factura and i.estado='D'

 --FASE 2: facturas en calidad de aceptacion (pos credito y debito) para el sistema de expediente
 and b.estado='C' and i.fecha_mov between '$fecha_desde' and '$fecha_hasta'

order by 1,2";
$res_fact=sql($sql_fact) or fin_pagina();
$filename = 'Prestaciones_fase2_'.$fecha_desde.'_'.$fecha_hasta.'.txt';  //cambiar nombre

      if (!$handle = fopen($filename, 'a')) {
           echo "No se Puede abrir ($filename)";
          exit;
      }

//encabezado NUEVO
/*$contenido="Id_Prestación;Codigo_Prestación;CUIE_Efector;Fecha_Prestacion;Apellido_Beneficiario;NombreBeneficiario;Clave_Beneficiario;Benef_Tipo_Documento;Benef_Clase_Documento;Benef_Nro_Documento;Sexo;Feha de Nacimiento;Valor_Unitario_facturado;Cantidad_facturada;Importe_Prestacion_Facturado;id_factura;nmero_fact;fecha_fact;Importe_Total_Factura;fecha_recepcion_fact;Alta complejidad;id_liquidacion;fecha iquidacion;Valor_Unitario_aprobado;Cantidad_aprobada;importe_Prestación_Aprobado;Numero de omprobante Extracto Bcario;id_dato_reportable_1;dato_reportable_1;id_dato_reportable_2;dato_reportable_2;id_dato_reportable_3;dato_reportable_3;id_dato_reportable_4;dato_reportable_4;id_dato_reportable_;dato_reportable_5;id_op;numero_op;fecha_op;importe_total_op;numero_expte;fecha_debito_bancario;importe_debito_bancario;fecha_notificacion_efector";
$contenido.="\r";
$contenido.="\n";  */  
$contenido='';

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


    $contenido=$res_fact->fields['id_prestacion'].";";
    $contenido.=$codigo_comp.";";
    $contenido.=$res_fact->fields['cuie'].";";
    $contenido.=$res_fact->fields['fecha_comprobante'].";";
    $contenido.=$res_fact->fields['apellido'].";";
    $contenido.=$res_fact->fields['nombre'].";";
    $contenido.=$res_fact->fields['clavebeneficiario'].";";
    $contenido.=$res_fact->fields['afitipodoc'].";";
    $contenido.=$res_fact->fields['aficlasedoc'].";";
    $contenido.=$res_fact->fields['dni'].";";
    $contenido.=trim($res_fact->fields['afisexo']).";";
    $contenido.=$res_fact->fields['afifechanac'].";";
    $precio_prestacion_ = ($res_fact->fields['precio_prestacion'])?$res_fact->fields['precio_prestacion']:0;
    $contenido.=number_format($precio_prestacion_,2,'.','').";";
    $contenido.=$res_fact->fields['cantidad'].";";
    $importe_subtotal_=($res_fact->fields['importe_subtotal'])?$res_fact->fields['importe_subtotal']:0;
    $contenido.=number_format($importe_subtotal_,2,'.','').";";
    $contenido.=$res_fact->fields['id_factura'].";";
    $contenido.=$res_fact->fields['id_factura'].";";//este es el numero de factura-REVISAR
    $contenido.=$res_fact->fields['fecha_factura'].";";
    $monto_prefactura_=($res_fact->fields['monto_prefactura'])?$res_fact->fields['monto_prefactura']:0;
    $contenido.=number_format($monto_prefactura_,2,'.','').";";
    $contenido.=$res_fact->fields['fecha_control'].";";
    $contenido.=($res_fact->fields['alta_comp']=='SI')?'S;':'N;';
    $contenido.=$res_fact->fields['id_expediente'].";";
    $contenido.=$res_fact->fields['fecha_ing'].";";
    $contenido.=number_format($precio_prestacion_,2,'.','').";";
    $contenido.=$res_fact->fields['cantidad'].";";
    $monto_=($res_fact->fields['monto'])?$res_fact->fields['monto']:0;
    $contenido.=number_format($monto_,2,'.','').";";
    //$contenido.=$res_fact->fields['VAR_027-numero_compro_Extr_banc'].";";

          
    //datos reportables
    
    //fin datos reportables
    
    $numero_op=$res_fact->fields['id_op'];
  
    if ($numero_op) {
      if (strlen($numero_op)==9) {
          $substr_anio='20'.substr($numero_op, -2);
          $substr_mes='0'.substr($numero_op,0,1);
          $substr_dia=substr($numero_op,1,2);
          $var_40_fecha_op=$substr_anio.'-'.$substr_mes.'-'.$substr_dia;
          $id_op_str=substr($numero_op,3,-2);
      } else {
          $substr_anio='20'.substr($numero_op, -2);
          $substr_mes=substr($numero_op,0,2);
          $substr_dia=substr($numero_op,2,2);
          $var_40_fecha_op=$substr_anio.'-'.$substr_mes.'-'.$substr_dia;
          $id_op_str=substr($numero_op,4,-2);
        }
      
      $contenido.=$id_op_str.';';
      $contenido.=$numero_op.';';
      $contenido.=$var_40_fecha_op.';';
      } else {
        $contenido.=';;;';
      }
    $contenido.=$res_fact->fields['total_pagar'].";";
    $contenido.=$res_fact->fields['nro_exp'].";";
    $contenido.=$res_fact->fields['fecha_deposito'].";";
    $sql_total_exp="SELECT sum(monto_prefactura) as monto_exp from facturacion.factura where nro_exp_ext='$id_exp_ext'";
    $res_sql_total=sql($sql_total_exp) or fin_pagina();

    $contenido.=$res_sql_total->fields['monto_exp'].";";
    $contenido.=$res_fact->fields['fecha_inf_efector']."\r";
    //$contenido.='A'."\r"; //Tipo No va
    $contenido.="\n";
      if (fwrite($handle, $contenido) === FALSE) {
          echo "No se Puede escribir  ($filename)";
          exit;
      }
    
    $res_fact->MoveNext();
    }
    
    if (fwrite($handle, '----FIN DE ARCHIVO-----') === FALSE) {
          echo "No se Puede escribir  ($filename)";
          exit;
    }

    echo "El Archivo ($filename) se genero con exito";
  
    fclose($handle);

}

if ($_POST['genera']=="Genera TXT-FASE 3"){  
  
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);
  
  $sql_fact="SELECT --expediente.transaccion.id_expediente,
  d.id_prestacion, --VAR_01
  c.id_comprobante , --VAR_01
  d.id_nomenclador, --VAR_EXTRA
  e.grupo, --VAR_02
  e.codigo,--VAR_02
  d.diagnostico,--VAR_02
  e.descripcion,--VAR_EXTRA
  g.cuie, --VAR_03
  g.nombre as nombre_efector, --VAR_EXTRA
  --facturacion.prestacion.fecha_prestacion,
  c.fecha_comprobante::date, --VAR_04
  f.id_smiafiliados, --VAR_EXTRA
  c.grupo_etareo, --VAR_EXTRA
  f.afiapellido as apellido, --VAR_05
  f.afinombre as nombre, --VAR_06
  f.clavebeneficiario, --VAR_07
  f.afitipodoc, --VAR_08
  f.aficlasedoc, --VAR_09  
  f.afidni as dni, --VAR_10
  f.afisexo, --VAR_11
  f.afifechanac, --VAR_12
  d.precio_prestacion, --VAR_13
  d.cantidad, --VAR_14
  d.cantidad*d.precio_prestacion as importe_subtotal, --VAR_15
  a.id_factura, --VAR_16
  --VAR_17->numero de factura = Id_factura
  b.fecha_factura::date, --VAR_18
  b.monto_prefactura, --VAR_19
  case when b.fecha_control is null then a.fecha_ing else b.fecha_control end as fecha_control, --consultar VAR_20
  b.alta_comp, --VAR_21
  a.id_expediente, --VAR_22
  a.fecha_ing, --VAR_23
  --VAR_24 -> VAR_13
  --VAR_25 -> VAR_14
  --VAR_26 -> VAR_15
  a.monto, --VAR_26 (CONSULTAR)
  --VAR_27 -> numero de comprobante de extracto bancario
  --VAR_38-> los digitos intermedios del id_op
  b.nro_exp_ext as id_op,  --VAR_39
  --VAR_40-> los 4 primeros digitos junto con los dos ultimos digitos para formar la fecha
  i.total_pagar, --VAR_41
  a.nro_exp, --VAR_42 -- realmente viene de expediente.transaccion estado=A y id_area=1 para fase 1 y fase 2 viene null y no hay problema
  
  h.fecha_deposito, --VAR_43
  --VAR_44 -> Suma de todas las facturas del mismo expediente   
  i.fecha_inf as fecha_inf_efector --VAR_45    

from expediente.expediente a, facturacion.factura b, facturacion.comprobante c,facturacion.prestacion d,
	facturacion.nomenclador e, nacer.smiafiliados f, nacer.efe_conv g, contabilidad.ingreso h, expediente.transaccion i

where a.id_factura = b.id_factura
and b.id_factura=c.id_factura
and c.id_comprobante=d.id_comprobante
and d.id_nomenclador=e.id_nomenclador
and c.id_smiafiliados=f.id_smiafiliados
and a.id_efe_conv=g.id_efe_conv
and b.id_factura=h.numero_factura


--FASE 2 : expedientes en situacion de aceptacion para circuito de pago 
and b.id_factura=i.id_factura and i.estado='C'

 --FASE 2: facturas en calidad de aceptacion (pos credito y debito) para el sistema de expediente
and b.estado='C' and i.fecha_mov = (select max (fecha_mov) from expediente.transaccion
                                    where transaccion.id_factura=b.id_factura
                                    and fecha_mov between '$fecha_desde' and '$fecha_hasta')


--FASE 3: fecha de deposito no nulo
and h.fecha_deposito is not null

order by 1,2";
$res_fact=sql($sql_fact) or fin_pagina();
$filename = 'Prestaciones_fase3_'.$fecha_desde.'_'.$fecha_hasta.'.txt';  //cambiar nombre

      if (!$handle = fopen($filename, 'a')) {
           echo "No se Puede abrir ($filename)";
          exit;
      }

//encabezado NUEVO
/*$contenido="Id_Prestación;Codigo_Prestación;CUIE_Efector;Fecha_Prestacion;Apellido_Beneficiario;NombreBeneficiario;Clave_Beneficiario;Benef_Tipo_Documento;Benef_Clase_Documento;Benef_Nro_Documento;Sexo;Feha de Nacimiento;Valor_Unitario_facturado;Cantidad_facturada;Importe_Prestacion_Facturado;id_factura;nmero_fact;fecha_fact;Importe_Total_Factura;fecha_recepcion_fact;Alta complejidad;id_liquidacion;fecha iquidacion;Valor_Unitario_aprobado;Cantidad_aprobada;importe_Prestación_Aprobado;Numero de omprobante Extracto Bcario;id_dato_reportable_1;dato_reportable_1;id_dato_reportable_2;dato_reportable_2;id_dato_reportable_3;dato_reportable_3;id_dato_reportable_4;dato_reportable_4;id_dato_reportable_;dato_reportable_5;id_op;numero_op;fecha_op;importe_total_op;numero_expte;fecha_debito_bancario;importe_debito_bancario;fecha_notificacion_efector";
$contenido.="\r";
$contenido.="\n";*/
$contenido='';    

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


    $contenido=$res_fact->fields['id_prestacion'].";";
    $contenido.=$codigo_comp.";";
    $contenido.=$res_fact->fields['cuie'].";";
    $contenido.=$res_fact->fields['fecha_comprobante'].";";
    $contenido.=$res_fact->fields['apellido'].";";
    $contenido.=$res_fact->fields['nombre'].";";
    $contenido.=$res_fact->fields['clavebeneficiario'].";";
    $contenido.=trim($res_fact->fields['afitipodoc']).";";
    $contenido.=trim($res_fact->fields['aficlasedoc']).";";
    $contenido.=$res_fact->fields['dni'].";";
    $contenido.=trim($res_fact->fields['afisexo']).";";
    $contenido.=$res_fact->fields['afifechanac'].";";
    $precio_prestacion_ = ($res_fact->fields['precio_prestacion'])?$res_fact->fields['precio_prestacion']:0;
    $contenido.=number_format($precio_prestacion_,2,'.','').";";
    $contenido.=$res_fact->fields['cantidad'].";";
    $importe_subtotal_=($res_fact->fields['importe_subtotal'])?$res_fact->fields['importe_subtotal']:0;
    $contenido.=number_format($importe_subtotal_,2,'.','').";";
    $contenido.=$res_fact->fields['id_factura'].";";
    $contenido.=$res_fact->fields['id_factura'].";";//este es el numero de factura-REVISAR
    $contenido.=$res_fact->fields['fecha_factura'].";";
    $monto_prefactura_=($res_fact->fields['monto_prefactura'])?$res_fact->fields['monto_prefactura']:0;
    $contenido.=number_format($monto_prefactura_,2,'.','').";";
    $contenido.=$res_fact->fields['fecha_control'].";";
    $contenido.=($res_fact->fields['alta_comp']=='SI')?'S;':'N;';
    $contenido.=$res_fact->fields['id_expediente'].";";
    $contenido.=$res_fact->fields['fecha_ing'].";";
    $contenido.=number_format($precio_prestacion_,2,'.','').";";
    $contenido.=$res_fact->fields['cantidad'].";";
    $monto_=($res_fact->fields['monto'])?$res_fact->fields['monto']:0;
    $contenido.=number_format($monto_,2,'.','').";";
    //$contenido.=$res_fact->fields['VAR_027-numero_compro_Extr_banc'].";";

          
    //datos reportables
    if (necesita_dr($codigo_comp)) {
          $datos = get_datos ($id_comprobante,$dni,$diagnostico, $id_smiafiliados, $fecha_comprobante);
          $contenido.=";1;".$datos['peso']; //peso
          $contenido.=";2;".$datos['talla'];//talla
          $contenido.=";3;".$datos['ta'];//ta
          $contenido.=";4;".$datos['perim_cefalico'];//perimetro cefalico
          $contenido.=";5;".$datos['edad_gestacional'];
          $contenido.=";6;".$datos['cpod_ceod'];
          $contenido.=";9;".$datos['inf_diag_biopsia'];
          $contenido.=";10;".$datos['inf_anat_patologica'];
          $contenido.=";11;".$datos['inf_diag_anatomo'];
          $contenido.=";13;".$datos['inf_vdrl'].";";
          $contenido.=";14;".$datos['tratamiento_instaurado'];
          $contenido.=";;;;;";//Completamos los lugares 
    }  
       


  /*elseif ($codigo_comp=="PRP021A97" or $codigo_comp=="PRP021H86") {
    $contenido.=";;;;;";
    $contenido.=$res_fact->fields['res_oido_derecho'].";";
    $contenido.=$res_fact->fields['res_oido_izquierdo'].";";
    $contenido.=";;;;;;;";
  }
  
  elseif ($codigo_comp=="PRP017A46" or $codigo_comp=="PRP017A97") {
    $contenido.=";;;;;;;";
    $contenido.=$res_fact->fields['retinopatia'].";";
    $contenido.=";;;;;;";
  }
  */
  
  
  
  else $contenido.=";;;;;;;;;;;";
    //fin datos reportables
    
    $numero_op=$res_fact->fields['id_op'];
  
    if ($numero_op) {
      if (strlen($numero_op)==9) {
          $substr_anio='20'.substr($numero_op, -2);
          $substr_mes='0'.substr($numero_op,0,1);
          $substr_dia=substr($numero_op,1,2);
          $var_40_fecha_op=$substr_anio.'-'.$substr_mes.'-'.$substr_dia;
          $id_op_str=substr($numero_op,3,-2);
      } else {
          $substr_anio='20'.substr($numero_op, -2);
          $substr_mes=substr($numero_op,0,2);
          $substr_dia=substr($numero_op,2,2);
          $var_40_fecha_op=$substr_anio.'-'.$substr_mes.'-'.$substr_dia;
          $id_op_str=substr($numero_op,4,-2);
        }
      
      $contenido.=$id_op_str.';';
      $contenido.=$numero_op.';';
      $contenido.=$var_40_fecha_op.';';
      } else {
        $contenido.=';;;';
      }
    $total_pagar_ = ($res_fact->fields['total_pagar'])?$res_fact->fields['total_pagar']:0;
    $contenido.=number_format($total_pagar_,2,'.','').";";
    $contenido.=$res_fact->fields['nro_exp'].";";
    $contenido.=$res_fact->fields['fecha_deposito'].";";
    $sql_total_exp="SELECT sum(monto_prefactura) as monto_exp from facturacion.factura where nro_exp_ext='$id_exp_ext'";
    $res_sql_total=sql($sql_total_exp) or fin_pagina();
    $monto_exp_ = ($res_sql_total->fields['monto_exp'])?$res_sql_total->fields['monto_exp']:0;

    $contenido.=number_format($monto_exp_,2,'.','').";";
    $contenido.=$res_fact->fields['fecha_inf_efector']."\r";
    //$contenido.='A'."\r"; //Tipo no va
    $contenido.="\n";
      if (fwrite($handle, $contenido) === FALSE) {
          echo "No se Puede escribir  ($filename)";
          exit;
      }
    
    $res_fact->MoveNext();
    }
    
    if (fwrite($handle, '----FIN DE ARCHIVO-----') === FALSE) {
          echo "No se Puede escribir  ($filename)";
          exit;
    }

    echo "El Archivo ($filename) se genero con exito";
  
    fclose($handle);

}

if ($_POST['genera_excel']=="Genera EXCEL"){  
  
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);
  $link=encode_link("listado_comprobantes_auditoria_excel.php",array("fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta)); ?>
  <script>
  window.open('<?=$link?>')
  </script>
  <?
  

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

<form name='form1' action='listado_comprobantes_auditoria.php' method='POST'>

<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<b><font size="2">Fechas del Periodo</font></b>	
		Desde: <input type=text id="fecha_desde" name="fecha_desde" value='<?=$fecha_desde?>' size=15>
		<?=link_calendario("fecha_desde");?>
		
		Hasta: <input type=text id="fecha_hasta" name="fecha_hasta" value='<?=$fecha_hasta?>' size=15>
		<?=link_calendario("fecha_hasta");?> 
		
		  </td>
    </tr>
    <tr><td>&nbsp;&nbsp;&nbsp;</td></tr>

    <tr>
    <td align="center"> 
	  <!--
    <input type="submit" class="btn btn-success" name="muestra" value='Muestra' onclick="return control_muestra()" >
    <input type="submit" class="btn btn-danger" name="genera" value='Genera TXT Con Datos Reportables' onclick="return control_muestra()">
    <input type="submit" class="btn btn-warning" name="genera" value='Genera TXT Sin Datos Reportables' onclick="return control_muestra()">
    <input type="submit" class="btn btn-info" name="genera_excel" value='Genera EXCEL' onclick="return control_muestra()">-->
	  <input type="submit" class="btn btn-danger" name="genera" value='Genera TXT-FASE 1' onclick="return control_muestra()" disabled>
    <input type="submit" class="btn btn-warning" name="genera" value='Genera TXT-FASE 2' onclick="return control_muestra()" disabled>
    <input type="submit" class="btn btn-success" name="genera" value='Genera TXT-FASE 3' onclick="return control_muestra()">
    </td>
    </tr>
     
    
     
</table>
<table width="98%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
    	<font size=+1><b><?echo "Desde: ".fecha($fecha_desde)." Hasta: ".fecha($fecha_hasta)?> </b></font>        
    </td>
 </tr>
 <tr><td>
 
<?if ($_POST['muestra']){?>

<tr><td><table id="prestacion" width=95% align="center" class="table table-striped" style="border:thin groove">
        <thead>
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
            <th align="center">Fecha Deposito</th>
            </tr>
        </thead>
 
        <tfoot>
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
            <th align="center">Fecha Deposito</th>
            </tr>
        </tfoot>
 
        <tbody>
            <? $res_fact->MoveFirst();
          while (!$res_fact->EOF)
          {?>
          <tr>
          <td><?=$res_fact->fields['id_factura']?></td>
          <td><?=$res_fact->fields['id_comprobante']?></td>
          <td><?=$res_fact->fields['cuie']?></td>
          <td><?=$res_fact->fields['nombre_efector']?></td>
          <td><?=$res_fact->fields['dni']?></td>
          <td><?=$res_fact->fields['nombre']?></td>
          <td><?=$res_fact->fields['apellido']?></td>
          <td><?=fecha($res_fact->fields['afifechanac'])?></td>
          <td><?=$res_fact->fields['grupo_etareo']?></td>
          <td><?=$res_fact->fields['afisexo']?></td>
          <td><?=fecha($res_fact->fields['fecha_comprobante'])?></td>
          <td><?=$res_fact->fields['codigo']?></td>
          <td><?=$res_fact->fields['descripcion']?></td>
          <td><?=$res_fact->fields['cantidad']?></td>
          <td><?=number_format($res_fact->fields['precio_prestacion'],2,'.',',')?></td>
          <td><?=$res_fact->fields['diagnostico']?></td>
          <td><?=fecha($res_fact->fields['fecha_deposito'])?></td>
          </tr>
          <?$res_fact->MoveNext();
          }?>
            
        </tbody>
    </table>
 </td></tr>
<?}?>

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
 
 <?=fin_pagina();// aca termino ?>
