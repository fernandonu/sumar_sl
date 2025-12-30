<?php 
function es_hospital($cuie){
    $hospitales = array ('D05118','D05117','D05111','D05050','D05128','D05154','D00009','D12007','D12001','D05127','D3307','D05142','D00001','D05114','D05148','D05158','D00008','D05083','D05061','D03306','D12009','D05071','D05080','D05026','D05054','D05015','D05126','D05098','D05104','D05093','D05120','D00011','D03307');
    
    if (in_array($cuie, $hospitales)) return true;
        else return false;
}

function getSQLCountPrestacionesBeneficiario($rolAud,$nroDoc,$fechaDesde="",$fechaHasta=""){
    $sql  = " SELECT COUNT(*) AS total 
              FROM ( ";
    $sql .= getSQLPrestacionesBeneficiario($rolAud,$nroDoc,$fechaDesde,$fechaHasta,99999,0);
    $sql .= " ) pr_tr ";
    return $sql;
}

// $fechaDesde y $fechaHasta deben tener formato dd/mm/yyyy
// $rolAud => boolean que indica si se debe levantar adicionalmente info de la factura
function getSQLPrestacionesBeneficiario($rolAud,$nroDoc,$fechaDesde="",$fechaHasta="",$limit=9999,$offset=0){
    if($rolAud){
        // $selectP = " ,fact.nro_exp, fact.nro_fact_offline,nom.tipo_nomenclador as tipo_nomenclador ";
        $selectP = " ,fact.nro_exp, nom.tipo_nomenclador as tipo_nomenclador ";
        // $selectT = " ,p.nro_exp, p.nro_fact_offline, null as tipo_nomenclador  ";
        $selectT = " ,p.nro_exp, null as tipo_nomenclador  ";
        $joinP = $joinT = " LEFT JOIN facturacion.factura fact ON comp.id_factura=fact.id_factura ";
    }
    if(isset($fechaDesde) && $fechaDesde!=""){
        $arr_fecha_desde = explode("/", $fechaDesde);
        $fechaDesde = $arr_fecha_desde[2]."-".$arr_fecha_desde[1]."-".$arr_fecha_desde[0];
    }
    if(isset($fechaHasta) && $fechaHasta!=""){
        $arr_fecha_hasta = explode("/", $fechaHasta);
        $fechaHasta = $arr_fecha_hasta[2]."-".$arr_fecha_hasta[1]."-".$arr_fecha_hasta[0];
    }
    if($fechaDesde!="" && $fechaHasta==""){
        $cond_fechaP .= " AND comp.fecha_comprobante >= '$fechaDesde' ";
        $cond_fechaT .= " AND t.fecha_comprobante >= '$fechaDesde' ";
    }
    if($fechaDesde=="" && $fechaHasta!=""){
        $cond_fechaP .= " AND comp.fecha_comprobante <= '$fechaHasta' ";
        $cond_fechaT .= " AND t.fecha_comprobante <= '$fechaHasta' ";
    }
    if($fechaDesde!="" && $fechaHasta!=""){
        $cond_fechaP .= " AND comp.fecha_comprobante BETWEEN '$fechaDesde' AND '$fechaHasta' ";
        $cond_fechaT .= " AND t.fecha_comprobante BETWEEN '$fechaDesde' AND '$fechaHasta' ";
    }
    $limit = " LIMIT $limit OFFSET $offset ";
    $sql = "SELECT DISTINCT(prest.id_prestacion), comp.fecha_comprobante, 
                    efec.nombreefector, efec.cuie, 
        CAST(benef.numero_doc AS INTEGER), benef.fecha_nacimiento_benef, 
                    nom.codigo, 
                    nom.codigo AS cod_nomenclador,
                    nom.descripcion, nom.id_nomenclador_detalle, 
                    NULL AS desc_descripcion, NULL AS trz
                    ".$selectP."  
             FROM (  select afidni as numero_doc, aficlasedoc as clase_documento_benef, 
                        afifechanac as fecha_nacimiento_benef, clavebeneficiario as clave_beneficiario 
                     from nacer.smiafiliados 
                     where afidni='".$nroDoc."' 
                   union
                     select numero_doc, clase_documento_benef, fecha_nacimiento_benef, clave_beneficiario 
                     from uad.beneficiarios 
                     where numero_doc='".$nroDoc."' 
                     limit 1
                   ) benef
             JOIN facturacion.comprobante comp ON comp.clavebeneficiario=benef.clave_beneficiario AND comp.marca=0  
             JOIN facturacion.prestacion prest ON comp.id_comprobante=prest.id_comprobante 
             JOIN facturacion.nomenclador nom ON prest.id_nomenclador=nom.id_nomenclador 
             JOIN facturacion.smiefectores efec ON comp.cuie=efec.cuie 
             ".$joinP." 
             WHERE benef.numero_doc='".$nroDoc."' AND benef.clase_documento_benef='P' 
                 ".$cond_fechaP."
    UNION

            SELECT NULL AS id_prestacion, t.fecha_comprobante,  
                t.nombreefector, t.cuie, t.numero_doc, NULL AS fecha_nacimiento_benef, 
                NULL AS codigo, t.cod_nomenclador, NULL AS descripcion, NULL AS id_nomenclador_detalle, 
                NULL AS desc_descripcion, t.trz_nombre AS trz 
                ".$selectT."  
            FROM 
            ( 
                SELECT 'EMB' AS trz_nombre, trz.num_doc AS numero_doc, trz.fecha_control AS fecha_comprobante, trz.cuie, NULL AS cod_nomenclador, ef.nombreefector
                FROM trazadoras.embarazadas trz 
                JOIN facturacion.smiefectores ef ON trz.cuie=ef.cuie 
            UNION ALL
                SELECT 'NINO' AS trz_nombre, trz.num_doc AS numero_doc, trz.fecha_control AS fecha_comprobante, trz.cuie, NULL AS codnomenclador, ef.nombreefector
                FROM trazadoras.nino_new trz 
                JOIN facturacion.smiefectores ef ON trz.cuie=ef.cuie 
            UNION ALL
                SELECT 'PARTO' AS trz_nombre, trz.num_doc AS numero_doc, trz.fecha_parto AS fecha_comprobante, trz.cuie, NULL AS cod_nomenclador, ef.nombreefector
                FROM trazadoras.partos trz 
                JOIN facturacion.smiefectores ef ON trz.cuie=ef.cuie 
            ) t 
            LEFT JOIN (
                    SELECT DISTINCT(prest.id_prestacion), comp.fecha_comprobante, NULL AS grupo_etario, 
                                efec.nombreefector, efec.cuie, benef.numero_doc, benef.fecha_nacimiento_benef, 
                                nom.codigo, NULL AS diagnostico, nom.descripcion, nom.id_nomenclador_detalle
                                ".$selectP."  
                        FROM (  select afidni as numero_doc, aficlasedoc as clase_documento_benef, 
                                    afifechanac as fecha_nacimiento_benef, clavebeneficiario as clave_beneficiario 
                                from nacer.smiafiliados 
                                where afidni='".$nroDoc."' 
                            union
                                select numero_doc, clase_documento_benef, fecha_nacimiento_benef, clave_beneficiario 
                                from uad.beneficiarios 
                                where numero_doc='".$nroDoc."' 
                            ) benef
                        JOIN facturacion.comprobante comp ON comp.clavebeneficiario=benef.clave_beneficiario AND comp.marca=0  
                        JOIN facturacion.prestacion prest ON comp.id_comprobante=prest.id_comprobante 
                        JOIN facturacion.nomenclador nom ON prest.id_nomenclador=nom.id_nomenclador 
                        JOIN facturacion.smiefectores efec ON comp.cuie=efec.cuie 
                        ".$joinT." 
            ) p ON t.numero_doc=CAST(p.numero_doc AS INTEGER) AND t.fecha_comprobante=p.fecha_comprobante AND t.cuie=p.cuie
                    

            WHERE t.numero_doc='".$nroDoc."' and p.id_prestacion IS NULL  
                ".$cond_fechaT."


    ORDER BY fecha_comprobante DESC 
        ".$limit."
    ";
    // $sql = "SELECT DISTINCT(prest.id_prestacion), comp.fecha_comprobante, comp.grupo_etario, 
    //                 efec.nombreefector, efec.cuie, 
    //     CAST(benef.numero_doc AS INTEGER), benef.fecha_nacimiento_benef, 
    //                 nom.codigo, nom.diagnostico, 
    //                 nom.codigo || CASE WHEN nom.diagnostico <> '' 
    //                                    THEN ' ' || nom.diagnostico 
    //                                    ELSE '' 
    //                               END AS cod_nomenclador,
    //                 nom.descripcion, nom.id_nomenclador_detalle, 
    //                 t_desc.descripcion AS desc_descripcion, t_desc.trz 
    //                 ".$selectP."  
    //          FROM (  select afidni as numero_doc, aficlasedoc as clase_documento_benef, 
    //                     afifechanac as fecha_nacimiento_benef, clavebeneficiario as clave_beneficiario 
    //                  from nacer.smiafiliados 
    //                  where afidni='".$nroDoc."' 
    //                union
    //                  select numero_doc, clase_documento_benef, fecha_nacimiento_benef, clave_beneficiario 
    //                  from uad.beneficiarios 
    //                  where numero_doc='".$nroDoc."' 
    //                  limit 1
    //                ) benef
    //          JOIN facturacion.comprobante comp ON comp.clavebeneficiario=benef.clave_beneficiario AND comp.marca=0  
    //          JOIN facturacion.prestacion prest ON comp.id_comprobante=prest.id_comprobante 
    //          JOIN facturacion.nomenclador nom ON prest.id_nomenclador=nom.id_nomenclador 
    //          JOIN facturacion.smiefectores efec ON comp.cuie=efec.cuie 
    //          LEFT JOIN nomenclador.descripciones t_desc ON comp.grupo_etario=t_desc.grupo_etareo 
    //                                                AND nom.codigo=t_desc.codigo
    //                                                AND nom.diagnostico=t_desc.diagnostico 
    //          ".$joinP." 
    //          WHERE benef.numero_doc='".$nroDoc."' AND benef.clase_documento_benef='P' 
    //              ".$cond_fechaP."
    // UNION

    //         SELECT NULL AS id_prestacion, t.fecha_comprobante, NULL AS grupo_etario, 
    //             t.nombreefector, t.cuie, t.numero_doc, NULL AS fecha_nacimiento_benef, 
    //             NULL AS codigo, NULL ASdiagnostico, t.cod_nomenclador, NULL AS descripcion, NULL id_nomenclador_detalle, 
    //             NULL AS desc_descripcion, t.trz_nombre AS trz 
    //             ".$selectT."  
    //         FROM 
    //         ( 
    //             SELECT 'ADOLESCENTE' AS trz_nombre, trz.num_doc AS numero_doc, trz.fecha_control AS fecha_comprobante, trz.cuie, trz.codnomenclador AS cod_nomenclador, ef.nombreefector
    //             FROM trazadoras.adolecentes trz 
    //             JOIN facturacion.smiefectores ef ON trz.cuie=ef.cuie 
    //         UNION  ALL
    //             SELECT 'ADULTO' AS trz_nombre, trz.num_doc AS numero_doc, trz.fecha_control AS fecha_comprobante, trz.cuie, trz.codnomenclador AS cod_nomenclador, ef.nombreefector
    //             FROM trazadoras.adultos trz 
    //             JOIN facturacion.smiefectores ef ON trz.cuie=ef.cuie 
    //         UNION ALL
    //             SELECT 'EMB' AS trz_nombre, trz.num_doc AS numero_doc, trz.fecha_control AS fecha_comprobante, trz.cuie, NULL AS cod_nomenclador, ef.nombreefector
    //             FROM trazadoras.embarazadas trz 
    //             JOIN facturacion.smiefectores ef ON trz.cuie=ef.cuie 
    //         UNION ALL
    //             SELECT 'NINO' AS trz_nombre, trz.num_doc AS numero_doc, trz.fecha_control AS fecha_comprobante, trz.cuie, codnomenclador, ef.nombreefector
    //             FROM trazadoras.nino_new trz 
    //             JOIN facturacion.smiefectores ef ON trz.cuie=ef.cuie 
    //         UNION ALL
    //             SELECT 'PARTO' AS trz_nombre, trz.num_doc AS numero_doc, trz.fecha_parto AS fecha_comprobante, trz.cuie, NULL AS cod_nomenclador, ef.nombreefector
    //             FROM trazadoras.partos trz 
    //             JOIN facturacion.smiefectores ef ON trz.cuie=ef.cuie 
    //         UNION ALL
    //             SELECT 'TAL' AS trz_nombre, trz.num_doc AS numero_doc, trz.fecha_control AS fecha_comprobante, trz.cuie, codnomenclador AS cod_nomenclador, ef.nombreefector
    //             FROM trazadoras.tal trz 
    //             JOIN facturacion.smiefectores ef ON trz.cuie=ef.cuie 
    //         ) t 
    //         LEFT JOIN (
    //                 SELECT DISTINCT(prest.id_prestacion), comp.fecha_comprobante, comp.grupo_etario, 
    //                             efec.nombreefector, efec.cuie, benef.numero_doc, benef.fecha_nacimiento_benef, 
    //                             nom.codigo, nom.diagnostico, nom.descripcion, nom.id_nomenclador_detalle, 
    //                             t_desc.descripcion AS desc_descripcion, t_desc.trz  
    //                             ".$selectP."  
    //                     FROM (  select afidni as numero_doc, aficlasedoc as clase_documento_benef, 
    //                                 afifechanac as fecha_nacimiento_benef, clavebeneficiario as clave_beneficiario 
    //                             from nacer.smiafiliados 
    //                             where afidni='".$nroDoc."' 
    //                         union
    //                             select numero_doc, clase_documento_benef, fecha_nacimiento_benef, clave_beneficiario 
    //                             from uad.beneficiarios 
    //                             where numero_doc='".$nroDoc."' 
    //                         ) benef
    //                     JOIN facturacion.comprobante comp ON comp.clavebeneficiario=benef.clave_beneficiario AND comp.marca=0  
    //                     JOIN facturacion.prestacion prest ON comp.id_comprobante=prest.id_comprobante 
    //                     JOIN facturacion.nomenclador nom ON prest.id_nomenclador=nom.id_nomenclador 
    //                     JOIN facturacion.smiefectores efec ON comp.cuie=efec.cuie 
    //                     LEFT JOIN nomenclador.descripciones t_desc ON comp.grupo_etario=t_desc.grupo_etareo 
    //                                                         AND nom.codigo=t_desc.codigo
    //                                                         AND nom.diagnostico=t_desc.diagnostico 
    //                     ".$joinT." 
    //         ) p ON t.numero_doc=CAST(p.numero_doc AS INTEGER) AND t.fecha_comprobante=p.fecha_comprobante AND t.cuie=p.cuie
                    

    //         WHERE t.numero_doc='".$nroDoc."' and p.id_prestacion IS NULL  
    //             ".$cond_fechaT."


    // ORDER BY fecha_comprobante DESC 
    //     ".$limit."
    // ";
  /*
 AVISO: 
   * En la union entre  nacer.smiafiliados  y uad.beneficiarios  se agrego limit 1,
  *  ya que si existin dos personas con DNI igual, duplicaba las prestaciones

      */
    return $sql;
}

/*******************************************************************************
 Valida las prestaciones de acuerdo a las reglas especificadas
 
 @id_comprobante 
 @nomenclador    Id del nomenclador (despues tengo que sacar codigo)   
 
*******************************************************************************/
function valida_prestacion($id_comprobante,$nomenclador){
	 
	//asigno variables para usar la validacion
	$query="select codigo from facturacion.nomenclador 
			where id_nomenclador='$nomenclador'";	             
	$res_codigo_nomenclador=sql($query, "Error 1") or fin_pagina();	
	$codigo=$res_codigo_nomenclador->fields['codigo'];
	
	//traigo el codigo de nomenclador y si hay validaciones traigo los datos de la validacion
	$query="select * from facturacion.validacion_prestacion
			where codigo='$codigo'";	             
	$res=sql($query, "Error 1") or fin_pagina();
	
	if ($res->RecordCount()>0){//me fijo si hay que validar (si tiene regla)
		//recupero el id_smiafiliados para mas adelante
		$query="SELECT id_smiafiliados,fecha_comprobante
				FROM facturacion.comprobante
  				INNER JOIN nacer.smiafiliados using (id_smiafiliados)
				where id_comprobante='$id_comprobante'";	             
		$id_smiafiliados_res=sql($query, "Error 2") or fin_pagina();
		$id_smiafiliados=$id_smiafiliados_res->fields['id_smiafiliados'];
		$fecha_comprobante=$id_smiafiliados_res->fields['fecha_comprobante'];
		
		//cantidad de prestaciones limites
		$cant_pres_lim=$res->fields['cant_pres_lim'];
		$per_pres_limite=$res->fields['per_pres_limite'];
		
		//cuenta la cantidad de prestaciones de un determinado filiado, de un determinado codigo y 
		//en un periodo de tiempo parametrizado.
	    $query="SELECT id_prestacion, codigo, fecha_comprobante
				FROM nacer.smiafiliados
  				INNER JOIN facturacion.comprobante ON (nacer.smiafiliados.id_smiafiliados = facturacion.comprobante.id_smiafiliados)
  				INNER JOIN facturacion.prestacion ON (facturacion.comprobante.id_comprobante = facturacion.prestacion.id_comprobante)
  				INNER JOIN facturacion.nomenclador ON (facturacion.prestacion.id_nomenclador = facturacion.nomenclador.id_nomenclador)
  				where smiafiliados.id_smiafiliados=$id_smiafiliados and 
  					  codigo='$codigo' and
  					   facturacion.comprobante.marca !=1 and
  					  fecha_comprobante between (CAST('$fecha_comprobante' AS date) - $per_pres_limite) and CAST('$fecha_comprobante' AS date) ";
  		$cant_pres=sql($query, "Error 3") or fin_pagina();
  
  		
  		if ($cant_pres->RecordCount()>=$cant_pres_lim){
  			$msg_error=$res->fields['msg_error'];
  			$accion = $msg_error." - Cantidad de Prestaciones: ".$cant_pres->RecordCount()." - Limite: ".$cant_pres_lim." en ".$per_pres_limite." dias" . " - Codigo: ".$codigo;
  			echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
  			return 0;
  		}
  		else return 1;
	}
	else return 1;
}

function valida_prestacion1($id_comprobante,$nomenclador){
   
	$query="select codigo from facturacion.nomenclador 
			where id_nomenclador='$nomenclador'";	             
	$res=sql($query, "Error 1") or fin_pagina();	
	$codigo_nomenclador = $res->fields['codigo'];
	
	$query="SELECT afifechanac,fecha_comprobante
				FROM facturacion.comprobante
  				INNER JOIN nacer.smiafiliados using (id_smiafiliados)
				where id_comprobante='$id_comprobante'";	             
	$res1=sql($query, "Error 2") or fin_pagina();
	$fecha_nac=$res1->fields['afifechanac'];
	$fecha_comprobante=$res1->fields['fecha_comprobante'];
	
	list($aa,$mm,$dd) = explode("-",$fecha_comprobante);
    $fecha1 = mktime(0,0,0,$mm,$dd,$aa);
    list($aa,$mm,$dd) = explode("-",$fecha_nac);
    $fecha2 = mktime(0,0,0,$mm,$dd,$aa);
    $Dias=($fecha1 - $fecha2) / 86400;
	
	if (($codigo_nomenclador=='NPE 32')&&($Dias>365)){
		$accion = "No se Puede facturar un 'NPE 32' a un niño mayor de 1 año - Por favor Verifique o Facture un 'NPE 33'";
  		echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
  		return 0;
	}
	else{
		if (($codigo_nomenclador=='NPE 33')&&($Dias<=365)){
			$accion = "No se Puede facturar un 'NPE 33' a un niño menor de 1 año - Por favor Verifique o Facture un 'NPE 32'";
	  		echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
	  		return 0;
		}
		else return 1;
	}
	echo $codigo_nomenclador.$edad;
}

function valida_prestacion3($id_comprobante,$nomenclador){
	 
	//asigno variables para usar la validacion
	$query="select codigo from nomenclador.grupo_prestacion
			where id_categoria_prestacion='$nomenclador'";	             
	$res_codigo_nomenclador=sql($query, "Error 1") or fin_pagina();	
	$codigo=$res_codigo_nomenclador->fields['codigo'];
	
	//traigo el codigo de nomenclador y si hay validaciones traigo los datos de la validacion
	$query="select * from facturacion.validacion_prestacion
			where codigo='$codigo'";	             
	$res=sql($query, "Error 1") or fin_pagina();
	
	if ($res->RecordCount()>0){//me fijo si hay que validar (si tiene regla)
		//recupero el id_smiafiliados para mas adelante
		$query="SELECT id_smiafiliados,fecha_comprobante
				FROM facturacion.comprobante
  				INNER JOIN nacer.smiafiliados using (id_smiafiliados)
				where id_comprobante='$id_comprobante'";	             
		$id_smiafiliados_res=sql($query, "Error 2") or fin_pagina();
		$id_smiafiliados=$id_smiafiliados_res->fields['id_smiafiliados'];
		$fecha_comprobante=$id_smiafiliados_res->fields['fecha_comprobante'];
		
		//cantidad de prestaciones limites
		$cant_pres_lim=$res->fields['cant_pres_lim'];
		$per_pres_limite=$res->fields['per_pres_limite'];
		
		//cuenta la cantidad de prestaciones de un determinado filiado, de un determinado codigo y 
		//en un periodo de tiempo parametrizado.
		$query="SELECT id_prestaciones_n_op, codigo, comprobante.fecha_comprobante
				FROM nacer.smiafiliados
  				INNER JOIN facturacion.comprobante ON (nacer.smiafiliados.id_smiafiliados = facturacion.comprobante.id_smiafiliados)
  				inner join nomenclador.prestaciones_n_op using (id_comprobante)
  				where smiafiliados.id_smiafiliados=$id_smiafiliados and 
  					  tema='$codigo' and
  					  prestaciones_n_op.fecha_comprobante between (CAST('$fecha_comprobante' AS date) - $per_pres_limite) and CAST('$fecha_comprobante' AS date and facturacion.comprobante.marca !=1)";
  		$cant_pres=sql($query, "Error 3") or fin_pagina();
  		
  		if ($cant_pres->RecordCount()>=$cant_pres_lim){
  			$msg_error=$res->fields['msg_error'];
  			$accion = $msg_error." - Cantidad de Prestaciones: ".$cant_pres->RecordCount()." - Limite: ".$cant_pres_lim." en ".$per_pres_limite." dias" . " - Codigo: ".$codigo;
  			echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
  			return 0;
  		}
  		else return 1;
	}
	else return 1;
}

function valida_prestacion_nuevo_nomenclador_old($id_comprobante,$nomenclador){
	 
	//nuevo calculo para poder detectar las practicas facturadas mas de las permitidas
    //el calculo anterior toma periodos correlativos, el orden inverso de entradas de comprobantes no detecta la cantidad real
    $dia_hoy=date("Y-m-d");


    //asigno variables para usar la validacion
	$query="select codigo from facturacion.nomenclador 
			where id_nomenclador='$nomenclador'";	             
	$res_codigo_nomenclador=sql($query, "Error 1") or fin_pagina();	
	$codigo=$res_codigo_nomenclador->fields['codigo'];
	
	//traigo el codigo de nomenclador y si hay validaciones traigo los datos de la validacion
	$query="select * from facturacion.validacion_prestacion
			where id_nomenclador='$nomenclador'";	             
	$res=sql($query, "Error 1") or fin_pagina();
	
	if ($res->RecordCount()>0){//me fijo si hay que validar (si tiene regla)
		//recupero el id_smiafiliados para mas adelante
		$query="SELECT id_smiafiliados,fecha_comprobante
				FROM facturacion.comprobante
  				INNER JOIN nacer.smiafiliados using (id_smiafiliados)
				where id_comprobante='$id_comprobante'";	             
		$id_smiafiliados_res=sql($query, "Error 2") or fin_pagina();
		$id_smiafiliados=$id_smiafiliados_res->fields['id_smiafiliados'];
		$fecha_comprobante=$id_smiafiliados_res->fields['fecha_comprobante'];
		
		//cantidad de prestaciones limites
		$cant_pres_lim=$res->fields['cant_pres_lim'];
		$per_pres_limite=$res->fields['per_pres_limite'];
		
		//cuenta la cantidad de prestaciones de un determinado filiado, de un determinado codigo y 
		//en un periodo de tiempo parametrizado.
	$query="SELECT id_prestacion, codigo, fecha_comprobante
				FROM nacer.smiafiliados
  				INNER JOIN facturacion.comprobante ON (nacer.smiafiliados.id_smiafiliados = facturacion.comprobante.id_smiafiliados)
  				INNER JOIN facturacion.prestacion ON (facturacion.comprobante.id_comprobante = facturacion.prestacion.id_comprobante)
  				INNER JOIN facturacion.nomenclador ON (facturacion.prestacion.id_nomenclador = facturacion.nomenclador.id_nomenclador)
  				where smiafiliados.id_smiafiliados=$id_smiafiliados and 
  					  nomenclador.id_nomenclador='$nomenclador' and
  					   facturacion.comprobante.marca !=1 and
  					  fecha_comprobante between ('$dia_hoy'::date - $per_pres_limite) and '$dia_hoy'::date";
  		$cant_pres=sql($query, "Error 3") or fin_pagina();
  
  		
  		if ($cant_pres->RecordCount()>=$cant_pres_lim){
  			$msg_error=$res->fields['msg_error'];
  			$accion = $msg_error." - Cantidad de Prestaciones: ".$cant_pres->RecordCount()." - Limite: ".$cant_pres_lim." en ".$per_pres_limite." dias" . " - Codigo: ".$codigo;
  			echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
  			return 0;
  		}
  		else return 1;
	}
	else return 1;
}


function comprobante_duplicado($id_comprobante,$nomenclador){

    $query="SELECT id_smiafiliados,fecha_comprobante
				FROM facturacion.comprobante
  				INNER JOIN nacer.smiafiliados using (id_smiafiliados)
				where id_comprobante='$id_comprobante'";

    $id_smiafiliados_res=sql($query) or fin_pagina();
    $id_smiafiliados=$id_smiafiliados_res->fields['id_smiafiliados'];
    $fecha_comprobante=$id_smiafiliados_res->fields['fecha_comprobante'];

    $query="SELECT * 
            from facturacion.prestacion a,
            facturacion.comprobante b
            where a.id_comprobante = b.id_comprobante
            and b.id_smiafiliados = $id_smiafiliados
            and a.id_nomenclador = $nomenclador
            and b.fecha_comprobante = '$fecha_comprobante'";
    $rs_query = sql ($query) or fin_pagina();

    if ($rs_query->RecordCount()>0) return 1;
        else return 0;
}

//la vamos a utilizar como nuevas validaciones 2022
function valida_prestacion_nuevo_nomenclador($id_comprobante,$nomenclador,&$accion){
	
    if (comprobante_duplicado($id_comprobante,$nomenclador)) {
            return 0;
            $accion = "Comprobante Duplicado";
            echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
        }
        else {
            if (valida_prestacion_nuevo_nomenclador_old($id_comprobante,$nomenclador))
                {return 1; 
                    $accion = "Supera la Tasa de Uso";
                    echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";}
            else 
                return 0;
        }
}
