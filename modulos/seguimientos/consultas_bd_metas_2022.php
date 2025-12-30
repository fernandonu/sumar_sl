<?php 
function embarazadas_e ($fecha_desde,$fecha_hasta,$cuie) {

    $q = "SELECT count (a.*) as cantidad from (
            SELECT distinct hcperinatal.clave_beneficiario FROM
                sip_clap.hcperinatal
                INNER JOIN sip_clap.control_prenatal ON sip_clap.hcperinatal.id_hcperinatal = sip_clap.control_prenatal.id_hcperinatal
                where date ('20'||(sip_clap.control_prenatal.var_0118::character(2)) ||'-'|| sip_clap.control_prenatal.var_0117 ||'-'|| sip_clap.control_prenatal.var_0116)
                between '$fecha_desde' and '$fecha_hasta'
                and control_prenatal.id_efector = '$cuie') a";
                
    $res_q = sql($q) or fin_pagina();        
    $result = $res_q->fields['cantidad'];
    return $result;      
}

function embarazadas_p ($fecha_desde,$fecha_hasta,$cuie) {

    $q = "SELECT count (a.*) as cantidad from (
            SELECT distinct hcperinatal.clave_beneficiario FROM
                sip_clap.hcperinatal
                INNER JOIN sip_clap.control_prenatal ON sip_clap.hcperinatal.id_hcperinatal = sip_clap.control_prenatal.id_hcperinatal
                where date ('20'||(sip_clap.control_prenatal.var_0118::character(2)) ||'-'|| sip_clap.control_prenatal.var_0117 ||'-'|| sip_clap.control_prenatal.var_0116)
                between '$fecha_desde' and '$fecha_hasta'
                and control_prenatal.id_efector = '$cuie'
                and sip_clap.control_prenatal.var_0119::integer <= 12
                ) a";
                
    $res_q = sql($q) or fin_pagina();        
    $result = $res_q->fields['cantidad'];
    return $result;     
}

function embarazadas_r ($fecha_desde,$fecha_hasta,$cuie) {

    $q = "SELECT count (a.*) as cantidad from (
            SELECT control_prenatal.id_hcperinatal, count (*) as cantidad FROM
                sip_clap.hcperinatal
                INNER JOIN sip_clap.control_prenatal ON sip_clap.hcperinatal.id_hcperinatal = sip_clap.control_prenatal.id_hcperinatal
                where date ('20'||(sip_clap.control_prenatal.var_0118::character(2)) ||'-'|| sip_clap.control_prenatal.var_0117 ||'-'|| sip_clap.control_prenatal.var_0116)
                between '$fecha_desde' and '$fecha_hasta'
                and control_prenatal.id_efector = '$cuie'
                group by control_prenatal.id_hcperinatal
                having count (*) >= 5
        --      and sip_clap.control_prenatal.var_0119::integer <= 12        
                ) a";
                
    $res_q = sql($q) or fin_pagina();        
    $result = $res_q->fields['cantidad'];
    return $result;     
}

function embarazadas_metas($cuie) {
    
    $q = "SELECT * 
        from nacer.metas_2024
        where cuie = '$cuie'";
    
    $res_q = sql($q) or fin_pagina();
    $result = $res_q->fields['embarazadas'];
    return $result;
}


function ninios_1_9_e($fecha_desde,$fecha_hasta,$cuie){
    
    $q = "SELECT count (distinct c.id_smiafiliados) as cantidad
        from facturacion.prestacion a, facturacion.comprobante b, 
        nacer.smiafiliados c, facturacion.nomenclador d
        where a.id_comprobante = b.id_comprobante
        and b.id_smiafiliados = c.id_smiafiliados
        and a.id_nomenclador = d.id_nomenclador
        and b.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
        and d.grupo||d.codigo||a.diagnostico = 'CTC001A97'
        and b.cuie = '$cuie'
        and extract ('year' from age(b.fecha_comprobante, c.afifechanac)) 
            between 3 and 9";

    $res_q = sql($q) or fin_pagina();
    $result = $result->fields['cantidad'];
    return $result;
}

function ninios_1_9_p($fecha_desde,$fecha_hasta,$cuie){
    
    $q1 = "SELECT ccc.id_smiafiliados, count (ccc.id_smiafiliados) from (
            select distinct on (c.id_smiafiliados, b.fecha_comprobante) c.id_smiafiliados, b.fecha_comprobante
            from facturacion.prestacion a, facturacion.comprobante b, 
            nacer.smiafiliados c, facturacion.nomenclador d
            where a.id_comprobante = b.id_comprobante
            and b.id_smiafiliados = c.id_smiafiliados
            and a.id_nomenclador = d.id_nomenclador
            and b.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
            and d.grupo||d.codigo||a.diagnostico = 'CTC001A97'
            and b.cuie = '$cuie'
            and extract ('year' from age(b.fecha_comprobante, c.afifechanac)) 
                between 1 and 2
        ) as ccc
        group by (ccc.id_smiafiliados)
        having (count (ccc.id_smiafiliados))>1";
    
    $res_q1 = sql($q1) or fin_pagina();
    $result = $recordCount($res_q1);

    $q2 = "SELECT ccc.id_smiafiliados, count (ccc.id_smiafiliados) from (
        select distinct on (c.id_smiafiliados, b.fecha_comprobante) c.id_smiafiliados, b.fecha_comprobante
        from facturacion.prestacion a, facturacion.comprobante b, 
        nacer.smiafiliados c, facturacion.nomenclador d
        where a.id_comprobante = b.id_comprobante
        and b.id_smiafiliados = c.id_smiafiliados
        and a.id_nomenclador = d.id_nomenclador
        and b.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
        and d.grupo||d.codigo||a.diagnostico = 'CTC001A97'
        and b.cuie = '$cuie'
        and extract ('year' from age(b.fecha_comprobante, c.afifechanac)) 
            between 3 and 9
        ) as ccc
        group by (ccc.id_smiafiliados)
        having (count (ccc.id_smiafiliados))>2";

    $res_q2 = sql($q2) or fin_pagina();
    $result += $recordCount($res_q2);

    return $result;


}

function ninios_1_9_r($fecha_desde,$fecha_hasta,$cuie){
    
    $denominador = "SELECT *
        from facturacion.prestacion a, facturacion.comprobante b, 
            nacer.smiafiliados c, facturacion.nomenclador d
            where a.id_comprobante = b.id_comprobante
            and b.id_smiafiliados = c.id_smiafiliados
            and a.id_nomenclador = d.id_nomenclador
            and b.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
            --and d.grupo||d.codigo||a.diagnostico = 'CTC002T82'
            and d.grupo||d.codigo = 'CTC002'
            and b.cuie = '$cuie'
            and extract ('year' from age(b.fecha_comprobante, c.afifechanac)) 
            between 1 and 9";
    $res_denominador = sql($denominador) or fin_pagina();
    $result = recordCount($res_denominador);

}

function ninios_1_9_metas($cuie){
    
    $q = "SELECT * 
        from nacer.metas_2024
        where cuie = '$cuie'";

    $res_q = sql($q) or fin_pagina();
    $result = $res_q->fields['ninio_1_9'];
    return $result;
}

function ninios_1_e($fecha_desde,$fecha_hasta,$cuie) {

    $q = "SELECT distinct on (c.id_smiafiliados, b.fecha_comprobante) c.id_smiafiliados, b.fecha_comprobante
        from facturacion.prestacion a, facturacion.comprobante b, 
        nacer.smiafiliados c, facturacion.nomenclador d
        where a.id_comprobante = b.id_comprobante
        and b.id_smiafiliados = c.id_smiafiliados
        and a.id_nomenclador = d.id_nomenclador
        and b.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
        and d.grupo||d.codigo||a.diagnostico = 'CTC001A97'
        and b.cuie = '$cuie'
        and extract ('year' from age(b.fecha_comprobante, c.afifechanac)) < 1";

    $res_q = sql($q) or fin_pagina();
    $result = recordCount($res_q);

    return $result;
}

function ninios_1_p($fecha_desde,$fecha_hasta,$cuie) {

    $q = "SELECT z.id_smiafiliados, count (*) controles
        from 	
            (select distinct on (c.id_smiafiliados, b.fecha_comprobante) c.id_smiafiliados,
            min(b.fecha_comprobante) min_fecha, max(b.fecha_comprobante) max_fecha
            from facturacion.prestacion a, facturacion.comprobante b, 
            nacer.smiafiliados c, facturacion.nomenclador d
            where a.id_comprobante = b.id_comprobante
            and b.id_smiafiliados = c.id_smiafiliados
            and a.id_nomenclador = d.id_nomenclador
            and b.fecha_comprobante between '2021-01-01' and '2022-12-31'
            and d.grupo||d.codigo||a.diagnostico = 'CTC001A97'
            and extract ('year' from age(b.fecha_comprobante, c.afifechanac)) < 1
            group by (c.id_smiafiliados, b.fecha_comprobante)
            order by 1) z,
            facturacion.comprobante y,
            facturacion.prestacion x,
            facturacion.nomenclador h
            
        where z.id_smiafiliados = y.id_smiafiliados
        and y.id_comprobante = x.id_comprobante
        and x.id_nomenclador = h.id_nomenclador
        and y.fecha_comprobante between z.min_fecha and z.max_fecha
        and h.grupo||h.codigo||x.diagnostico = 'CTC001A97'
        group by z.id_smiafiliados
        having count(z.id_smiafiliados) >=6";

    $res_q = sql($q) or fin_pagina();
    $result = recordCount($res_q);

    return $result;
}

function ninios_1_r($fecha_desde,$fecha_hasta,$cuie) {

    $q = "SELECT a.id_smiafiliados,b.afifechanac, a.fecha_control, a.tipo_lactancia,
        extract ('year' from age(a.fecha_control,b.afifechanac))||' anios,'
        ||extract ('month' from age(a.fecha_control,b.afifechanac))|| 'meses'
        from fichero.fichero a, nacer.smiafiliados b
        where  a.id_smiafiliados = b.id_smiafiliados
        and a.fecha_control between  '$fecha_desde' and '$fecha_hasta'
        and a.cuie = '$cuie'
        and (a.tipo_lactancia is not null and trim(a.tipo_lactancia)<>'')
        and a.id_smiafiliados is not null and a.id_smiafiliados<>0
        and extract ('year' from age(a.fecha_control,b.afifechanac))=0
        and extract ('month' from age(a.fecha_control,b.afifechanac))<=6";

    $res_q = sql($q) or fin_pagina();
    $result = recordCount($res_q);

    return $result;
}

function ninios_1_metas($cuie){
    
    $q = "SELECT * 
        from nacer.metas_2024
        where cuie = '$cuie'";

    $res_q = sql($q) or fin_pagina();
    $result = $res_q->fields['ninio_1'];
    return $result;
}

function adolecentes_e($fecha_desde,$fecha_hasta,$cuie) {

    $q="SELECT distinct on (c.id_smiafiliados, b.fecha_comprobante) 
        a.*
        from facturacion.prestacion a, facturacion.comprobante b, 
        nacer.smiafiliados c, facturacion.nomenclador d
        where a.id_comprobante = b.id_comprobante
        and b.id_smiafiliados = c.id_smiafiliados
        and a.id_nomenclador = d.id_nomenclador
        and b.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
        and d.grupo||d.codigo||a.diagnostico = 'CTC001A97'
        and b.cuie = '$cuie'
        and extract ('year' from age(b.fecha_comprobante, c.afifechanac)) between 10 and 19";

    $res_q = sql($q) or fin_pagina();
    $result = recordCount($res_q);

    return $result;

}


function adolecentes_r($fecha_desde,$fecha_hasta,$cuie) {
    
    $q = "SELECT count (distinct c.id_smiafiliados)
            from facturacion.prestacion a, facturacion.comprobante b, 
            nacer.smiafiliados c, facturacion.nomenclador d
            where a.id_comprobante = b.id_comprobante
            and b.id_smiafiliados = c.id_smiafiliados
            and a.id_nomenclador = d.id_nomenclador
            and b.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
            and d.grupo||d.codigo = 'CTC057'
            and c.id_smiafiliados in (            
                select distinct on (c.id_smiafiliados, b.fecha_comprobante) c.id_smiafiliados
                from facturacion.prestacion a, facturacion.comprobante b, 
                nacer.smiafiliados c, facturacion.nomenclador d
                where a.id_comprobante = b.id_comprobante
                and b.id_smiafiliados = c.id_smiafiliados
                and a.id_nomenclador = d.id_nomenclador
                and b.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
                and d.grupo||d.codigo||a.diagnostico = 'CTC001A97'
                and extract ('year' from age(b.fecha_comprobante, c.afifechanac)) between 10 and 19)";
    
    $res_q = sql($q) or fin_pagina();
    $result = recordCount($res_q);

    return $result;
}

function adolecentes_metas($cuie){
    
    $q = "SELECT * 
        from nacer.metas_2024
        where cuie = '$cuie'";

    $res_q = sql($q) or fin_pagina();
    $result = $res_q->fields['adolecentes'];
    return $result;
}

function hta_e($fecha_desde,$fecha_hasta,$cuie) {
    
    $q = "SELECT distinct on (a.num_doc,a.fecha_control) *
        from trazadoras.clasificacion_remediar2 a,
        trazadoras.seguimiento_remediar b,
        trazadoras.seguimiento_tratamiento c
        where a.clave_beneficiario = b.clave_beneficiario
        and b.id_seguimiento_remediar = c.id_seguimiento
        and a.fecha_control between '$fecha_desde' and '$fecha_hasta'
        and extract ('year' from age(a.fecha_control, a.fecha_nac)) >=18
        and a.hipertenso = 'SI'
        --and a.cuie = '$cuie' --esto es clasificacion
        and b.efector = '$cuie'--esto es seguimiento";

    $res_q = sql ($q) or fin_pagina();
    $result = recordCount($res_q);

    return $result;

}

function hta_p($fecha_desde,$fecha_hasta,$cuie) {
  
  $q ="SELECT distinct on (a.num_doc,a.fecha_control) *
        from trazadoras.clasificacion_remediar2 a,
        trazadoras.seguimiento_remediar b,
        trazadoras.seguimiento_tratamiento c,
        trazadoras.seguimiento_consejeria d
        where a.clave_beneficiario = b.clave_beneficiario
        and b.id_seguimiento_remediar = c.id_seguimiento
        and b.id_seguimiento_remediar = d.id_seguimiento
        and a.fecha_control between '$fecha_desde' and '$fecha_hasta'
        and extract ('year' from age(a.fecha_control, a.fecha_nac)) >=18
        and a.hipertenso = 'SI'
        --and a.cuie = '$cuie' --esto es clasificacion
        and b.efector = '$cuie'--esto es seguimiento
        and a.rcvg is not null
        and a.ta_diast::text||a.ta_sist::text <>''
        and (lpad(a.ta_diast::text,3,'0')||'/'||lpad(a.ta_sist::text,3,'0')) <>'000/000'
        and (c.ieca_ara='si' or  c.estatina='si' or c.aas='si' or c.beta_bloqueantes='si'
            or c.hipoglusemiante_oral='si' or c.insulina='si' or c.metformina='si'
            or c.enalapril='si' or c.losartan='si' or c.amlodipina='si' or c.atenolol='si'
            or c.hidroclorotiazida='si')
        and (d.alimentacion_saludable='si' or d.actividad_fisica='si'
            or d.rastreo_tabaquismo='si')
        and (b.ifg is not null and trim(b.ifg)<>'')
        and ((b.indice_ac is not null and trim(b.indice_ac)<>'')
            or (b.indice_pc is not null and trim(b.indice_pc)<>''))";   

    $res_q = sql($q) or fin_pagina();
    $result = recordCount($res_q);

    return $result;
}


function hta_metas($cuie){
    
    $q = "SELECT * 
        from nacer.metas_2024
        where cuie = '$cuie'";

    $res_q = sql($q) or fin_pagina();
    $result = $res_q->fields['hta'];
    return $result;
}

function dbt_e($fecha_desde,$fecha_hasta,$cuie) {
    
    $q = "SELECT distinct on (a.num_doc,a.fecha_control) *
            from trazadoras.clasificacion_remediar2 a,
            trazadoras.seguimiento_remediar b,
            trazadoras.seguimiento_tratamiento c
            where a.clave_beneficiario = b.clave_beneficiario
            and b.id_seguimiento_remediar = c.id_seguimiento
            and a.fecha_control between '$fecha_desde' and '$fecha_hasta'
            and extract ('year' from age(a.fecha_control, a.fecha_nac)) >=18
            and a.diabetico = 'SI' --revisar si este es el campo
            --and a.cuie = '$cuie' --esto es clasificacion
            and b.efector = '$cuie'--esto es seguimiento";
}

function dbt_p($fecha_desde,$fecha_hasta,$cuie) {
    
    $q = "SELECT distinct on (a.num_doc,a.fecha_control) *
        from trazadoras.clasificacion_remediar2 a,
        trazadoras.seguimiento_remediar b,
        trazadoras.seguimiento_tratamiento c,
        trazadoras.seguimiento_consejeria d
        where a.clave_beneficiario = b.clave_beneficiario
        and b.id_seguimiento_remediar = c.id_seguimiento
        and b.id_seguimiento_remediar = d.id_seguimiento
        and a.fecha_control between '$fecha_desde' and '$fecha_hasta'
        and extract ('year' from age(a.fecha_control, a.fecha_nac)) >=18
        and a.diabetico = 'SI'
        --and a.cuie = '$cuie' --esto es clasificacion
        and b.efector = '$cuie'--esto es seguimiento
        and a.rcvg is not null
        and a.ta_diast::text||a.ta_sist::text <>''
        and (lpad(a.ta_diast::text,3,'0')||'/'||lpad(a.ta_sist::text,3,'0')) <>'000/000'
        and (c.ieca_ara='si' or  c.estatina='si' or c.aas='si' or c.beta_bloqueantes='si'
            or c.hipoglusemiante_oral='si' or c.insulina='si' or c.metformina='si'
            or c.enalapril='si' or c.losartan='si' or c.amlodipina='si' or c.atenolol='si'
            or c.hidroclorotiazida='si')
        and (d.alimentacion_saludable='si' or d.actividad_fisica='si'
            or d.rastreo_tabaquismo='si')
        and (b.ifg is not null and trim(b.ifg)<>'')
        and ((b.indice_ac is not null and trim(b.indice_ac)<>'')
            or (b.indice_pc is not null and trim(b.indice_pc)<>''))
        and (b.hba1c is not null and trim(b.hba1c)<>'')
        and (b.fondodeojo is not null and trim (b.fondodeojo)<>'')
        and b.examendepie <>'NO'";

        $res_q = sql($q) or fin_pagina();
        $result = recordCount($res_q);

        return $result;
}

function dbt_metas($cuie){
    
    $q = "SELECT * 
        from nacer.metas_2024
        where cuie = '$cuie'";

    $res_q = sql($q) or fin_pagina();
    $result = $res_q->fields['dbt'];
    return $result;
}
?>