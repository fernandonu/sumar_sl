<?php

function calculo_percentilo_imc($meses,$sexo,$imc){

    $sql="SELECT * from nacer.percentilos_imc where meses=$meses and sexo='$sexo'";
    $res_sql=sql($sql) or fin_pagina();

    if ($res_sql->RecordCount()!=0) {
        switch ($imc){
            case $imc<=$res_sql->fields['p10'] : return '10';breck;
            case $imc>$res_sql->fields['p10'] and $imc<=$res_sql->fields['p85'] : return '85';breck;
            case $imc>$res_sql->fields['p85'] and $imc<$res_sql->fields['p97'] : return '85 - 97';breck;
            case $imc=$res_sql->fields['p97'] : return '97';breck;
            case $imc>$res_sql->fields['p97'] :return '>97';breck;
        }
    }
}


function control_padron () {
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
}


function necesita_dr ($codigo) {
    
$codigos_embarazadas=array ("CTC005W78","CTC006W78","CTC007O24.4","CTC022O24.4",
    "CTC007O10","CTC007O10.4","CTC022O10","CTC022O10.4","CTC007O16","CTC022O16","CTC017P05");
    
$codigo_controles=array("CTC001A97","CTC009A97","CTC074K86");

$codigo_obes_sobrep=array("CTC001T79","CTC001T82","CTC001T83","CTC002T79","CTC002T82","CTC002T83");

$codigo_otros = array ("PRP021A97","PRP021H86","PRP017A46","PRP017A97","APA002A98","APA002X75",
"APA002X80","APA002X76","APA002X79","APA001A98","APA001X86","APA001X75",
"NTN002X75","NTN002X76","CTC010A97","CTC010W78","IGR014A98","LBL098A97","LBL098A98","LBL098D75",
"IGR031W78","ITQ001W90","ITQ001W91","ITQ002W88","ITQ002W89","CTC022O10.0","CTC022OO10.4","CTC022OO16",
"APA004A97","APA004A77","ISI002A98");


if (in_array($codigo,$codigos_embarazadas)
    or in_array($codigo,$codigo_controles)
    or in_array($codigo,$codigo_obes_sobrep)
    or in_array($codigo,$codigo_otros)
    or substr($codigo,0,6) =='PRP053'
    or substr($codigo,0,6) =='LBL056'
    or substr($codigo,0,6) =='LBL119') return true;
        else return false;
    
}

function get_datos_prestacion($id_prestacion){
    /*$r="SELECT b.* ,
        (case when (length(split_part (tension_arterial,'/',1))<3) then '0'||split_part (tension_arterial,'/',1) else  split_part (tension_arterial,'/',1) end )
        ||'/'||
        (case when (length(split_part (tension_arterial,'/',2))<3) then '0'||split_part (tension_arterial,'/',2) else  split_part (tension_arterial,'/',2) end) as ta_fix
        from facturacion.prestacion a
        where a.id_prestacion=$id_comprobante";*/
	//en la misma consulta estamos haciend cast dependiendo de la mascara
    $r="SELECT a.*,
        case when (a.peso<> 0) then to_char(a.peso,'G999D99') 
        else null end as peso_fix,
        case when (a.edad_gestacional <> 0) then to_char(a.edad_gestacional,'G99D9') 
        else null end as eg_fix,
        case when (a.perim_cefalico<>0) then to_char(a.perim_cefalico,'G99D9') 
        else null end as perim_cefalico_fix 
		from facturacion.prestacion a
        where a.id_prestacion=$id_prestacion";
		
    $res_r=sql($r) or fin_pagina();
    if ($res_r->RecordCount()>0) {	
        $peso = (isset($res_r->fields['peso']) and $res_r->fields['peso']<>0) ? $res_r->fields['peso'] :null;
        //$edad_gestacional = (isset($res_r->fields['edad_gestacional'])) ? $res_r->fields['edad_gestacional'] :null;
        //$perimetro_cefalico = ($res_r->fields['perim_cefalico']);
        
        $edad_gestacional = (isset($res_r->fields['eg_fix'])) ? $res_r->fields['eg_fix'] :null;
        $ta = (isset($res_r->fields['tension_arterial'])) ? $res_r->fields['tension_arterial'] :null;
        $talla = (isset($res_r->fields['talla']) or $res_r->fields['talla']<>0 ) ? $res_r->fields['talla'] :null;
        $perimetro_cefalico = (isset($res_r->fields['perim_cefalico_fix'])) ? $res_r->fields['perim_cefalico_fix'] :null;

        $imc = ($talla<>null and $talla<>0 and $peso<>null and $peso<>0) ? round($peso/(($talla/100)*($talla/100)),2) : 0;

        $peso = (isset($res_r->fields['peso_fix'])) ? $res_r->fields['peso_fix'] :null;
        
        $res_oido_derecho=$res_r->fields['res_oido_derecho'];
        $res_oido_izquierdo=$res_r->fields['res_oido_izquierdo'];

        $retinopatia = ($res_r->fields['retinopatia']<>0) ?  $res_r->fields['retinopatia'] : null;

        $inf_anat_patologica = ($res_r->fields['inf_anat_patologica']<>0) ? $res_r->fields['inf_anat_patologica'] : null;

        $inf_diag_biopsia = ($res_r->fields['inf_diag_biopsia']<>0) ? $res_r->fields['inf_diag_biopsia'] : null;

        $inf_diag_anatomo = ($res_r->fields['inf_diag_anatomo']<>0) ? $res_r->fields['inf_diag_anatomo'] :null;

        $inf_vdrl = $res_r->fields['inf_vdrl'];
        /*if (trim($res_r->fields['inf_vdrl'])<>null) {
            if (trim($res_r->fields['inf_vdrl']=='+')) $inf_vdrl = 'Positivo';
            elseif (trim($res_r->fields['inf_vdrl']=='-')) $inf_vdrl = 'Negativo';            
        };*/
                
        $tratamiento_instaurado = ($res_r->fields['tratamiento_instaurado']<>0) ? $res_r->fields['tratamiento_instaurado'] : null;
        
        $ceod = ($res_r->fields['ceod']=='' or $res_r->fields['ceod']=='c:/ e:/ o:') ? 'c:00/e:00/o:00' : $res_r->fields['ceod'];
        $cpod = ($res_r->fields['cpod']=='' or $res_r->fields['cpod']=='C:/ P:/ O:') ? 'C:00/P:00/O:00' : $res_r->fields['cpod'];
        
        $birads = ($res_r->fields['birads'])?$res_r->fields['birads']:null;
        $tisomf = ($res_r->fields['tisomf'])?$res_r->fields['tisomf']:null;
        $hemo_glic = ($res_r->fields['hemo_glic'])?$res_r->fields['hemo_glic']:null;
        $vph = ($res_r->fields['vph'])?$res_r->fields['vph']:null;
        $tratamiento_instaurado_de_cm = ($res_r->fields['trata$tratamiento_instaurado_de_cm'])?$res_r->fields['trata$tratamiento_instaurado_de_cm']:null;
        $financiador = ($res_r->fields['financiador'])?$res_r->fields['financiador']:null;
        $porc_geo = ($res_r->fields['porc_geo'])?$res_r->fields['porc_geo']:null;
        $porc_dbt = ($res_r->fields['porc_dbt'])?$res_r->fields['porc_dbt']:null;
        $porc_hta = ($res_r->fields['porc_hta'])?$res_r->fields['porc_hta']:null;

        $comentario = $res_r->fields['comentario'];

        $datos = array ("peso" => $peso, 
                        "edad_gestacional" => $edad_gestacional, 
                        "ta" => $ta, 
                        "talla" => $talla,
                        "perimetro_cefalico" => $perimetro_cefalico,
                        "res_oido_derecho" => $res_oido_derecho,
                        "res_oido_izquierdo" => $res_oido_izquierdo,
                        "retinopatia" => $retinopatia,
                        "inf_anat_patologica" => $inf_anat_patologica,
                        "inf_diag_biopsia" => $inf_diag_biopsia,
                        "inf_diag_anatomo" => $inf_diag_anatomo,
                        "inf_vdrl" => $inf_vdrl,
                        "tratamiento_instaurado" => $tratamiento_instaurado,
                        "ceod" => $ceod,
                        "cpod" => $cpod, 
                        "birads" => $birads,
                        "tisomf" => $tisomf,
                        "hemo_glic" => $hemo_glic,
                        "vph" => $vph,
                        "tratamiento_instaurado_de_cm" => $tratamiento_instaurado_de_cm,
                        "financiador" => $financiador,
                        "porc_geo" => $porc_geo,
                        "porc_dbt" => $porc_dbt,
                        "porc_hta" => $porc_hta,
                        "imc" => $imc,
                        "log" => 'prestacion.('.$comentario.')');
        return $datos;
    }
    else return null;

}


function get_datos_sipweb($dni, $fecha_comp) {

    $r="SELECT 
        c.var_0119 as edad_gestacional,
        c.var_0120 as peso,
        replace(to_char (c.var_0121::integer,'000')||'/'||to_char (c.var_0394::integer,'000'),' ','') as ta	
        from sip_clap.hcperinatal a, nacer.smiafiliados b, sip_clap.control_prenatal c
        where a.id_hcperinatal = c.id_hcperinatal
        and a.clave_beneficiario = b.clavebeneficiario
        and b.afidni='$dni'
        and '$fecha_comp' = '20'||c.var_0118||'-'||c.var_0117||'-'||c.var_0116";
    $res_r=sql($r) or fin_pagina();
    
    if ($res_r->RecordCount()>0) {
        if (isset($res_r->fields['peso']) and $res_r->fields['peso']<>0) {
            if ($res_r->fields['peso']>250) $peso = number_format ($res_r->fields['peso']/10,3,'.','');
                else $peso = number_format($res_r->fields['peso'],3,'.','');
            }
            else $peso = null;
            
            $edad_gestacional = $res_r->fields['edad_gestacional'];
            $ta = $res_r->fields['ta'];
            $datos = array ("peso" => $peso, 
                            "edad_gestacional" => $edad_gestacional, 
                            "ta" => $ta, 
                            "log" =>'sipweb');
    }
    else $datos = null;
    
    return $datos;
}

function get_datos_trazadora_1 ($id_smiafiliados,$fecha_comp){
    $r="SELECT * from trazadorassps.trazadora_1 where id_smiafiliados=$id_smiafiliados and (fecha_control_prenatal='$fecha_comp' or fecha_carga='$fecha_comp')";
    $res_r=sql($r) or fin_pagina();
    if ($res_r->RecordCount()>0) {	
        if (isset($res_r->fields['peso']) and $res_r->fields['peso']<>0) $peso = $res_r->fields['peso'];
            else $peso = null;
        $edad_gestacional = $res_r->fields['edad_gestacional'];
        $ta = $res_r->fields['ta'];
        $datos = array ("peso" => $peso, 
                        "edad_gestacional" => $edad_gestacional, 
                        "ta" => $ta, 
                        "log" => 'trazadora_1');
        return $datos;
    }
    else return null;   
}

function get_datos_trazadora_2 ($id_smiafiliados,$fecha_comp){
    $r="SELECT * from trazadorassps.trazadora_2 where id_smiafiliados=$id_smiafiliados 
        and (fecha_control='$fecha_comp' or fecha_carga='$fecha_comp')";
    $res_r=sql($r) or fin_pagina();
    if ($res_r->RecordCount()>0) {	
        if (isset($res_r->fields['peso']) and $res_r->fields['peso']<>0) $peso = $res_r->fields['peso'];
            else $peso = null;
        $edad_gestacional = $res_r->fields['edad_gestacional'];
        $ta = $res_r->fields['tension_arterial'];
        $datos = array ("peso" => $peso, 
                        "edad_gestacional" => $edad_gestacional, 
                        "ta" => $ta, 
                        "log" => 'trazadora_2');
        return $datos;
    }
    else return null;   
}



function get_datos_fichero($id_smiafiliados,$fecha_comp) {
    $r="SELECT *,(case when (length(split_part (ta,'/',1))<3) then '0'||split_part (ta,'/',1) else  split_part (ta,'/',1) end )
       ||'/'||
       (case when (length(split_part (ta,'/',2))<3) then '0'||split_part (ta,'/',2) else  split_part (ta,'/',2) end) as ta_fix
        from 
        fichero.fichero where id_smiafiliados=$id_smiafiliados and fecha_control='$fecha_comp'";
    $res_r=sql($r) or fin_pagina();
    if ($res_r->RecordCount()>0) {    
        if (isset($res_r->fields['peso']) and $res_r->fields['peso']<>0) $peso = $res_r->fields['peso'];
            else $peso = null;
        $edad_gestacional = $res_r->fields['edad_gestacional'];
        $ta = $res_r->fields['ta_fix'];
        $perimetro_cefalico = $res_r->fields['perim_cefalico'];
        $log = 'fichero';
        $datos = array ("peso" => $peso, 
                        "edad_gestacional" => $edad_gestacional, 
                        "ta" => $ta,
                        "perimetro_cefalico" => $perimetro_cefalico,
                        "log" => $log);
        return $datos;                      
    
    } else return null;
}

function get_datos_trazadora_ninio_new($dni,$fecha_comp) {
    $r="SELECT *,(case when (length(split_part (ta,'/',1))<3) then '0'||split_part (ta,'/',1) else  split_part (ta,'/',1) end )
        ||'/'||
        (case when (length(split_part (ta,'/',2))<3) then '0'||split_part (ta,'/',2) else  split_part (ta,'/',2) end) as ta_fix
        from trazadoras.nino_new where round(num_doc)='$dni' and fecha_control='$fecha_comp'";
    $res_r=sql($r) or fin_pagina();
    if ($res_r->RecordCount()>0) {
        if (isset ($res_r->fields['peso']) and $res_r->fields['peso']<>0) $peso=$res_r->fields['peso'];
            else $peso=null;
        
        if (isset($res_r->fields['talla'])) { 
            if ($res_r->fields['talla']<>0 and $res_r->fields['talla']<=2.5) $talla=round($res_r->fields['talla']*100);
                else $talla=round($res_r->fields['talla']);
        }
        else $talla=null;  

        if ($res_r->fields['perim_cefalico']<=1) $res_r->fields['perim_cefalico']*100;
            else $res_r->fields['perim_cefalico'];

        $datos = array(
            "peso" => $peso,
            "talla" => $talla,
            "perim_cefalico" => $res_r->fields['perim_cefalico'],
            "ta" => $res_r->fields['ta_fix'],
            "log" => 'trazadora_ninio_new'
        );
        return $datos;
    } else return null;
}

function get_datos_trazadora_adulto ($dni,$fecha_comp){
    $r="SELECT *,(case when (length(split_part (ta,'/',1))<3) then '0'||split_part (ta,'/',1) else  split_part (ta,'/',1) end )
        ||'/'||
        (case when (length(split_part (ta,'/',2))<3) then '0'||split_part (ta,'/',2) else  split_part (ta,'/',2) end) as ta_fix
        from trazadoras.adultos where round(num_doc)='$dni' and fecha_control='$fecha_comp'";
    $res_r=sql($r) or fin_pagina();
    if ($res_r->RecordCount()>0) {
        if (isset($res_r->fields['peso']) and $res_r->fields['peso']<>0) $peso=$res_r->fields['peso'];   
            else $peso=null;
        if ($res_r->fields['talla']<=2.5) $talla=round($res_r->fields['talla']*100);
            else $talla=round($res_r->fields['talla']);
        $ta = $res_r->fields['ta_fix'];
        $datos = array ("peso" => $peso, 
                        "talla" => $talla, 
                        "ta" => $ta, 
                        "log" => 'trazadora_adulto');
        return $datos;
    } else return null;                     
}

function get_datos_trazadora_7 ($id_smiafiliados,$fecha_comp) {
    $r="SELECT *,(case when (length(split_part (tension_arterial,'/',1))<3) then '0'||split_part (tension_arterial,'/',1) else  split_part (tension_arterial,'/',1) end )
       ||'/'||
       (case when (length(split_part (tension_arterial,'/',2))<3) then '0'||split_part (tension_arterial,'/',2) else  split_part (tension_arterial,'/',2) end) as ta_fix
       from trazadorassps.trazadora_7 where id_smiafiliados=$id_smiafiliados and fecha_control='$fecha_comp'";
    $res_r=sql($r) or fin_pagina();
    if ($res_r->RecordCount()>0) {
        if (isset ($res_r->fields['peso']) and $res_r->fields['peso']<>0) $peso=$res_r->fields['peso'];
            else $peso=null;
        if ($res_r->fields['talla']<=2.5) $talla=round($res_r->fields['talla']*100);
            else $talla=round($res_r->fields['talla']);
        $ta = $res_r->fields['ta_fix'];
        $log = 'trazadora_7';
        $datos = array ("peso" => $peso, 
                        "talla" => $talla, 
                        "ta" => $ta, 
                        "log" => $log);
        return $datos;
    } else return null;
}

function get_datos_trazadora_10($id_smiafiliados,$fecha_comp) {
    $r="SELECT *,(case when (length(split_part (tension_arterial,'/',1))<3) then '0'||split_part (tension_arterial,'/',1) else  split_part (tension_arterial,'/',1) end )
        ||'/'||
        (case when (length(split_part (tension_arterial,'/',2))<3) then '0'||split_part (tension_arterial,'/',2) else  split_part (tension_arterial,'/',2) end) as ta_fix
        from trazadorassps.trazadora_10 where id_smiafiliados=$id_smiafiliados and fecha_control='$fecha_comp'";
    $res_r=sql($r) or fin_pagina();
    if ($res_r->RecordCount()>0) {
        if (isset($res_r->fields['peso']) and $res_r->fields['peso']<>0) $peso=$res_r->fields['peso'];
            else $peso=null;
        $talla=$res_r->fields['talla'];
        $ta = $res_r->fields['ta_fix'];
        $datos = array ("peso" => $peso, 
                        "talla" => $talla, 
                        "ta" => $ta, 
                        "log" => 'trazadora_10');
        return $datos;
    } else return null;
}

function datos_vacios () {
    $datos = array ("peso" => null, 
                    "talla" => null,
                    "ta" => null,
                    "perimetro_cefalico" => null,
                    "edad_gestacional" => null,                   
                    "res_oido_derecho" => null,
                    "res_oido_izquierdo" => null,
                    "retinopatia" => null,
                    "inf_diag_biopsia" => null,
                    "inf_anat_patologica" => null,                   
                    "inf_diag_anatomo" => null,
                    "inf_vdrl" => null,
                    "tratamiento_instaurado" => null,
                    "ceod" => null,
                    "cpod" => null,                    
                    "log" => 'Sin datos en todas las tablas');
    return $datos;
}

function fase_1 ($fecha_desde, $fecha_hasta){  
      
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

function fase_2($fecha_desde,$fecha_hasta){  
      
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


function fase_3($fecha_desde,$fecha_hasta){  
    
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
  $contenido.="\n";
  $contenido=''; */
  
  //encabezado NUEVO2024
  /*$contenido="doi3.01.id_Prestacion;doi3.02.Codigo_Prestacion;doi3.03.Cuie_Efector;doi3.04.Fecha_Prestacion;doi3.05.Apellido_Beneficiario;doi3.06.Nombre_Beneficiario;doi3.07.clave_Beneficiario;doi3.08.BENEF_TIPO_DOCUMENTO;doi3.09.BENEF_CLASE_DOCUMENTO;doi3.10.BENEF_NRO_DOCUMENTO;doi3.11.SEXO;doi3.12.FECHA_DE_NACIMIENTO;doi3.13.VALOR_UNITARIO_facturado;doi3.14.CANTIDAD_facturada;doi3.15.Importe_Prestacion_Facturado;doi3.16.id_factura;doi3.17.numero_fact;doi3.18.FECHA_FACT;doi3.19.importe_total_factura;doi3.20.fecha_recepcion_fact;doi3.21.Alta complejidad;doi3.22.id_liquidacion;doi3.23.FECHA_LIQUIDACION;doi3.24.Valor_Unitario_aprobado;doi3.25_cantidad_aprobada;doi3.26.importe_Prestaci󮟁probado;doi3.27.Numero de Comprobante Extracto Bcario;doi3.28.DR1;doi3.29.dato_DR1;doi3.30.DR2;doi3.31.dato_DR2;doi3.32.DR3;doi3.33.dato_DR3;doi3.34.DR4;doi3.35.dato_DR4;doi3.36.DR5;doi3.37.dato_DR5;doi3.38.'id_op;doi3.39.numero_op;DOI3.40.fecha_op;doi3.41.importe_total_op;doi3.42.numero_expte;doi3.43.fecha_debito_bancario;doi3.44.importe_debito_bancario;doi3.45.fecha_notificacion_efector";
  $contenido.="\r";
  $contenido.="\n";*/

  $contenido = '';
  
  if (fwrite($handle, $contenido) === FALSE) {
              echo "No se Puede escribir  ($filename)";
              exit;
          };
      
  $res_fact->movefirst();
  while (!$res_fact->EOF) {
      $id_smiafiliados=$res_fact->fields['id_smiafiliados'];
      $dni=$res_fact->fields['dni'];  
      $id_comprobante = $res_fact->fields['id_comprobante'];
	  $id_prestacion = $res_fact->fields['id_prestacion'];
      $diagnostico = $res_fact->fields['diagnostico'];
      $codigo_nomen = $res_fact->fields['codigo'];
      
      $codigo_comp=$res_fact->fields['grupo'].$res_fact->fields['codigo'].$res_fact->fields['diagnostico'];
      $fecha_nac=$res_fact->fields['afifechanac'];
      $fecha_comp=$res_fact->fields['fecha_comprobante'];
      $edad=edad_con_meses($fecha_nac,$fecha_comp);
      $anios=$edad['anos'];

      $edad_meses = ($anios*12) + $edad['meses'];
      $sexo = trim($res_fact->fields['afisexo']);
  
  
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
      /*$monto_=($res_fact->fields['monto'])?$res_fact->fields['monto']:0;
      $contenido.=number_format($monto_,2,'.','').';';*/
      $importe_subtotal_=($res_fact->fields['importe_subtotal'])?$res_fact->fields['importe_subtotal']:0;
      $contenido.=number_format($importe_subtotal_,2,'.','').";";
      //$contenido.=$res_fact->fields['VAR_027-numero_compro_Extr_banc'].";";
  
            
      //datos reportables
      
      if (necesita_dr($codigo_comp)) {
        
               
        $datos = get_datos ($id_prestacion,$id_comprobante,$dni,$diagnostico, $id_smiafiliados, $fecha_comp, $codigo_nomen);
        $peso = trim($datos['peso']);
        $talla = round($datos['talla'],0);
        $perim_cefalico = trim($datos['perimetro_cefalico']);
        $sem_gestacion = trim($datos['edad_gestacional']);

                     
        if ($codigo_comp == 'CTC001A97' and $anios<=1) $contenido.=';1;'.$peso.';2;'.$talla.';3;'.$datos['ta'].';4;'.$perim_cefalico.';;;';
        if ($codigo_comp == 'CTC001A97' and $anios>1) $contenido.=';1;'.$peso.';2;'.$talla.';3;'.$datos['ta'].';;;;;';
        if ($codigo_comp == 'ITQ001W90' or $codigo_comp == 'ITQ001W91') $contenido.=';5;'.$sem_gestacion.';;;;;;;;;';
        if ($codigo_comp == 'ITQ002W88' or $codigo_comp == 'ITQ002W89') $contenido.=';5;'.$sem_gestacion.';;;;;;;;;';
        if ($codigo_comp == 'CTC002T79' or $codigo_comp == 'CTC002T83' or $codigo_comp == 'CTC002T82') {
            $imc = $datos['imc'];
            $percentilo = calculo_percentilo_imc($edad_meses,$sexo,$imc);
            $contenido.=';1;'.$peso.';2;'.$talla.';18;'.$imc.';19;'.$percentilo.';;;';
            };
        if ($codigo_comp == 'CTC074K86') $contenido.=';3;'.$datos['ta'].';;;;;;;;;';
        if ($codigo_comp == 'CTC022O24.4') $contenido.=';1;'.$peso.';3;'.$datos['ta'].';5;'.$sem_gestacion.';;;;;';
        if ($codigo_comp == 'CTC022O10.0' or $codigo_comp == 'CTC022O10.4' or $codigo_comp == 'CTC022O16') $contenido.=';1;'.$peso.';3;'.$datos['ta'].';5;'.$sem_gestacion.';;;;;';
        if (($codigo_comp == 'CTC010A97' or $codigo_comp == 'CTC010W78') and $anios<12) $contenido.=';6;'.$datos['ceod'].';;;;;;;;;';
        if (($codigo_comp == 'CTC010A97' or $codigo_comp == 'CTC010W78') and $anios>=12) $contenido.=';6;'.$datos['cpod'].';;;;;;;;;';
        if ($codigo_comp == 'CTC005W78' or $codigo_comp == 'CTC006W78')  $contenido.=';1;'.$peso.';3;'.$datos['ta'].';5;'.$sem_gestacion.';;;;;';
        if ($codigo_comp == 'PRP021A97' or $codigo_comp == 'PRP021H86') $contenido.=';7;'.$datos['res_oido_derecho'].$datos['res_oido_izquierdo'].';;;;;;;;;';
        if ($codigo_comp == 'CTC009A97') $contenido.=';1;'.$peso.';2;'.$talla.';3;'.$datos['ta'].';;;;;;';
        if ($res_fact->fields['codigo'] == 'L056') {
            $contenido.=';16;';
            $contenido.=(isset($datos['hemo_glic'])) ? $datos['hemo_glic'] : '0';
            $contenido.=';;;;;;;;;';
            };
        if ($codigo_comp == 'APA002A98' or $codigo_comp == 'APA002X75' or $codigo_comp == 'APA002X80') $contenido.=';10;'.$datos['inf_anat_patologica'].';;;;;;;;;';
        if ($codigo_comp == 'APA002X76' or $codigo_comp == 'APA002X79') $contenido.=';9;'.$datos['inf_diag_biopsia'].';;;;;;;;;';
        if ($codigo_comp == 'ISI002A98') $contenido.=';21;'.$datos['porc_geo'].';22;'.$datos['porc_dbt'].';23;'.$datos['porc_hta'].';;;;;';
        if ($codigo_comp == 'APA004A97' or $codigo_comp == 'APA004A77') $contenido.=';17;'.$datos['vph'].';;;;;;;;;';
        if ($codigo_comp == 'APA001A98' or $codigo_comp == 'APA001X86' or $codigo_comp == 'APA001X75') $contenido.=';11;'.$datos['inf_diag_anatomo'].';;;;;;;;;';
        if ($codigo_comp == 'IGR014A98') $contenido.=';12;'.$datos['birads'].';;;;;;;;;';
        if ($codigo_comp == 'NTN002X75') $contenido.=';14;'.$datos['tratamiento_instaurado'].';;;;;;;;;';
        if ($codigo_comp == 'NTN002X76') $contenido.=';24;'.$datos['tratamiento_instaurado_de_cm'].';;;;;;;;;';
        if ($codigo_comp == 'PRP017A46 ' or $codigo_comp == 'PRP017A97') $contenido.=';8;'.$datos['retinopatia'].';;;;;;;;;';
        if ($codigo_comp == 'LBL098A97 ' or $codigo_comp == 'LBL098A98' or $codigo_comp == 'LBL098D75') {
            $contenido.=';15;';
            $contenido.=(isset($datos['tisomf'])) ? $datos['tisomf'] : 'Negativo';
            $contenido.= ';;;;;;;;;';
            };
        if ($res_fact->fields['codigo'] == 'L119') {
            $contenido.=';13;';
            $contenido.=(isset($datos['inf_vdrl'])) ? $datos['inf_vdrl'] : 'Negativo';
            $contenido.=';;;;;;;;;';
            };
        if ($codigo_comp == 'IGR031W78') $contenido.=';5;'.$sem_gestacion.';;;;;;;;;'; 
        if ($res_fact->fields['codigo'] == 'P053') $contenido.=';25;'.$datos['financiador'].';;;;;;;;;';    
    }      
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
          };
        
        $contenido.=$id_op_str.';';
        $contenido.=$numero_op.';';
        $contenido.=$var_40_fecha_op.';';
        } else {
          $contenido.=';;;';
        };
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
        };
      
      $res_fact->MoveNext();
      };
      
      if (fwrite($handle, '----FIN DE ARCHIVO-----') === FALSE) {
            echo "No se Puede escribir  ($filename)";
            exit;
      };
  
      echo "El Archivo ($filename) se genero con exito";
    
      fclose($handle);
  
  }

  function fase_3_reparado($fecha_desde,$fecha_hasta){  
    
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

  --tabla de prestaciones arregladas
  and d.id_prestacion in (select id_prestacion from facturacion.prestaciones_fix)
  
  order by 1,2";
  $res_fact=sql($sql_fact) or fin_pagina();
  $filename = 'Prestaciones_fase3_(reparado)'.$fecha_desde.'_'.$fecha_hasta.'.txt';  //cambiar nombre
  
        if (!$handle = fopen($filename, 'a')) {
             echo "No se Puede abrir ($filename)";
            exit;
        }
  
  //encabezado NUEVO2024
  /*$contenido="doi3.01.id_Prestacion;doi3.02.Codigo_Prestacion;doi3.03.Cuie_Efector;doi3.04.Fecha_Prestacion;doi3.05.Apellido_Beneficiario;doi3.06.Nombre_Beneficiario;doi3.07.clave_Beneficiario;doi3.08.BENEF_TIPO_DOCUMENTO;doi3.09.BENEF_CLASE_DOCUMENTO;doi3.10.BENEF_NRO_DOCUMENTO;doi3.11.SEXO;doi3.12.FECHA_DE_NACIMIENTO;doi3.13.VALOR_UNITARIO_facturado;doi3.14.CANTIDAD_facturada;doi3.15.Importe_Prestacion_Facturado;doi3.16.id_factura;doi3.17.numero_fact;doi3.18.FECHA_FACT;doi3.19.importe_total_factura;doi3.20.fecha_recepcion_fact;doi3.21.Alta complejidad;doi3.22.id_liquidacion;doi3.23.FECHA_LIQUIDACION;doi3.24.Valor_Unitario_aprobado;doi3.25_cantidad_aprobada;doi3.26.importe_Prestaci󮟁probado;doi3.27.Numero de Comprobante Extracto Bcario;doi3.28.DR1;doi3.29.dato_DR1;doi3.30.DR2;doi3.31.dato_DR2;doi3.32.DR3;doi3.33.dato_DR3;doi3.34.DR4;doi3.35.dato_DR4;doi3.36.DR5;doi3.37.dato_DR5;doi3.38.'id_op;doi3.39.numero_op;DOI3.40.fecha_op;doi3.41.importe_total_op;doi3.42.numero_expte;doi3.43.fecha_debito_bancario;doi3.44.importe_debito_bancario;doi3.45.fecha_notificacion_efector";
  $contenido.="\r";
  $contenido.="\n";*/

  $contenido = '';
  
  if (fwrite($handle, $contenido) === FALSE) {
              echo "No se Puede escribir  ($filename)";
              exit;
          };
      
  $res_fact->movefirst();
  while (!$res_fact->EOF) {
      $id_smiafiliados=$res_fact->fields['id_smiafiliados'];
      $dni=$res_fact->fields['dni'];  
      $id_comprobante = $res_fact->fields['id_comprobante'];
      $id_prestacion = $res_fact->fields['id_prestacion'];
      $diagnostico = $res_fact->fields['diagnostico'];
      $codigo_nomen = $res_fact->fields['codigo'];
      
      $codigo_comp=$res_fact->fields['grupo'].$res_fact->fields['codigo'].$res_fact->fields['diagnostico'];
      $fecha_nac=$res_fact->fields['afifechanac'];
      $fecha_comp=$res_fact->fields['fecha_comprobante'];
      $edad=edad_con_meses($fecha_nac,$fecha_comp);
      $anios=$edad['anos'];

      $edad_meses = ($anios*12) + $anios['meses'];
      $sexo = trim($res_fact->fields['afisexo']);
  
  
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
      $contenido.=number_format($monto_,2,'.','').';';
      //$contenido.=$res_fact->fields['VAR_027-numero_compro_Extr_banc'].";";
  
            
      //datos reportables
      
      if (necesita_dr($codigo_comp)) {
        
        $datos = get_datos ($id_prestacion,$id_comprobante,$dni,$diagnostico, $id_smiafiliados, $fecha_comp, $codigo_nomen);
        $peso = trim($datos['peso']);
        $talla = round($datos['talla'],0);
        $perim_cefalico = trim($datos['perimetro_cefalico']);
        $sem_gestacion = trim($datos['edad_gestacional']);

                
        if ($codigo_comp == 'CTC001A97' and $anios<=1) $contenido.=';1;'.$peso.';2;'.$talla.';3;'.$datos['ta'].';4;'.$perim_cefalico.';;;';
        if ($codigo_comp == 'CTC001A97' and $anios>1) $contenido.=';1;'.$peso.';2;'.$talla.';3;'.$datos['ta'].';;;;;';
        if ($codigo_comp == 'ITQ001W90' or $codigo_comp == 'ITQ001W91') $contenido.=';5;'.$sem_gestacion.';;;;;;;;;';
        if ($codigo_comp == 'ITQ002W88' or $codigo_comp == 'ITQ002W89') $contenido.=';5;'.$sem_gestacion.';;;;;;;;;';
        if ($codigo_comp == 'CTC002T79' or $codigo_comp == 'CTC002T83' or $codigo_comp == 'CTC002T82') {
            $imc = $datos['imc'];
            $percentilo = calculo_percentilo_imc($edad_meses,$sexo,$imc);
            $contenido.=';1;'.$peso.';2;'.$talla.';18;'.$imc.';19;'.$percentilo.';;;';
            };
        if ($codigo_comp == 'CTC074K86') $contenido.=';3;'.$datos['ta'].';;;;;;;;;';
        if ($codigo_comp == 'CTC022O24.4') $contenido.=';1;'.$peso.';3;'.$datos['ta'].';5;'.$sem_gestacion.';;;;;';
        if ($codigo_comp == 'CTC022O10.0' or $codigo_comp == 'CTC022O10.4' or $codigo_comp == 'CTC022O16') $contenido.=';1;'.$peso.';3;'.$datos['ta'].';5;'.$sem_gestacion.';;;;;';
        if (($codigo_comp == 'CTC010A97' or $codigo_comp == 'CTC010W78') and $anios<12) $contenido.=';6;'.$datos['ceod'].';;;;;;;;;';
        if (($codigo_comp == 'CTC010A97' or $codigo_comp == 'CTC010W78') and $anios>=12) $contenido.=';6;'.$datos['cpod'].';;;;;;;;;';
        if ($codigo_comp == 'CTC005W78' or $codigo_comp == 'CTC006W78')  $contenido.=';1;'.$peso.';3;'.$datos['ta'].';5;'.$sem_gestacion.';;;;;';
        if ($codigo_comp == 'PRP021A97' or $codigo_comp == 'PRP021H86') $contenido.=';7;'.$datos['res_oido_derecho'].$datos['res_oido_izquierdo'].';;;;;;;;;';
        if ($codigo_comp == 'CTC009A97') $contenido.=';1;'.$peso.';2;'.$talla.';3;'.$datos['ta'].';;;;;;';
        if ($res_fact->fields['codigo'] == 'L056') {
            $contenido.=';16;';
            $contenido.=(isset($datos['hemo_glic'])) ? $datos['hemo_glic'] : '0';
            $contenido.=';;;;;;;;;';
            };
        if ($codigo_comp == 'APA002A98' or $codigo_comp == 'APA002X75' or $codigo_comp == 'APA002X80') $contenido.=';10;'.$datos['inf_anat_patologica'].';;;;;;;;;';
        if ($codigo_comp == 'APA002X76' or $codigo_comp == 'APA002X79') $contenido.=';9;'.$datos['inf_diag_biopsia'].';;;;;;;;;';
        if ($codigo_comp == 'ISI002A98') $contenido.=';21;'.$datos['porc_geo'].';22;'.$datos['porc_dbt'].';23;'.$datos['porc_hta'].';;;;;';
        if ($codigo_comp == 'APA004A97' or $codigo_comp == 'APA004A77') $contenido.=';17;'.$datos['vph'].';;;;;;;;;';
        if ($codigo_comp == 'APA001A98' or $codigo_comp == 'APA001X86' or $codigo_comp == 'APA001X75') $contenido.=';11;'.$datos['inf_diag_anatomo'].';;;;;;;;;';
        if ($codigo_comp == 'IGR014A98') $contenido.=';12;'.$datos['birads'].';;;;;;;;;';
        if ($codigo_comp == 'NTN002X75') $contenido.=';14;'.$datos['tratamiento_instaurado'].';;;;;;;;;';
        if ($codigo_comp == 'NTN002X76') $contenido.=';24;'.$datos['tratamiento_instaurado_de_cm'].';;;;;;;;;';
        if ($codigo_comp == 'PRP017A46 ' or $codigo_comp == 'PRP017A97') $contenido.=';8;'.$datos['retinopatia'].';;;;;;;;;';
        if ($codigo_comp == 'LBL098A97 ' or $codigo_comp == 'LBL098A98' or $codigo_comp == 'LBL098D75') {
            $contenido.=';15;';
            $contenido.=(isset($datos['tisomf'])) ? $datos['tisomf'] : 'Negativo';
            $contenido.= ';;;;;;;;;';
            };
        if ($res_fact->fields['codigo'] == 'L119') {
            $contenido.=';13;';
            $contenido.=(isset($datos['inf_vdrl'])) ? $datos['inf_vdrl'] : 'Negativo';
            $contenido.=';;;;;;;;;';
            };   
        if ($codigo_comp == 'IGR031W78') $contenido.=';5;'.$sem_gestacion.';;;;;;;;;'; 
        if ($res_fact->fields['codigo'] == 'P053') $contenido.=';25;'.$datos['financiador'].';;;;;;;;;';    
    }      
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
          };
        
        $contenido.=$id_op_str.';';
        $contenido.=$numero_op.';';
        $contenido.=$var_40_fecha_op.';';
        } else {
          $contenido.=';;;';
        };
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
        };
      
      $res_fact->MoveNext();
      };
      
      if (fwrite($handle, '----FIN DE ARCHIVO-----') === FALSE) {
            echo "No se Puede escribir  ($filename)";
            exit;
      };
  
      echo "El Archivo ($filename) se genero con exito";
    
      fclose($handle);
  
  }


function get_datos ($id_prestacion,$id_comprobante,$dni,$diagnostico, $id_smiafiliados, $fecha_comprobante, $codigo_nomen){
    
    if ($diagnostico=='W78' and ($codigo_nomen == 'C005' or $codigo_nomen == 'C006')){//son embrazadas con controles prenatales
        if (get_datos_prestacion($id_prestacion)) $datos = get_datos_prestacion($id_prestacion); 
            else { if (get_datos_sipweb($dni,$fecha_comprobante)) $datos = get_datos_sipweb($dni,$fecha_comprobante);
                else {if (get_datos_trazadora_1($id_smiafiliados,$fecha_comprobante)) $datos = get_datos_trazadora_1($id_smiafiliados,$fecha_comprobante);
                    else {if (get_datos_trazadora_2($id_smiafiliados,$fecha_comprobante)) $datos = get_datos_trazadora_2($id_smiafiliados,$fecha_comprobante); 
                        else {if (get_datos_fichero($id_smiafiliados,$fecha_comprobante)) $datos = get_datos_fichero($id_smiafiliados,$fecha_comprobante);
                            else $datos = datos_vacios();
                            }
                    }
                }
            }
        }
    else { //no son embarazadas y pueden ser embarazadas con control odontologico
        if (get_datos_prestacion($id_prestacion)) $datos = get_datos_prestacion($id_prestacion); 
            else {if (get_datos_fichero($id_smiafiliados,$fecha_comprobante)) $datos = get_datos_fichero($id_smiafiliados,$fecha_comprobante);
                else {if (get_datos_trazadora_ninio_new($dni,$fecha_comprobante)) $datos = get_datos_trazadora_ninio_new($dni,$fecha_comprobante);
                    else {if (get_datos_trazadora_adulto($dni,$fecha_comprobante)) $datos = get_datos_trazadora_adulto($dni,$fecha_comprobante);
                        else {if (get_datos_trazadora_7($id_smiafiliados,$fecha_comprobante)) $datos = get_datos_trazadora_7($id_smiafiliados,$fecha_comprobante);
                            else {if (get_datos_trazadora_10($id_smiafiliados,$fecha_comprobante)) $datos = get_datos_trazadora_10($id_smiafiliados,$fecha_comprobante); 
                                else $datos = datos_vacios();
                            }
                        }
                    }
                }
            }
    }
return $datos;
}

?>