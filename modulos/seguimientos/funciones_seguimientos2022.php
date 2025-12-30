<?php 

function embarazadas($fecha_desde,$fecha_hasta,$cuie){
	    		
	$sql_embarazadas="SELECT distinct on(afidni,fecha_control) *  from (
		SELECT distinct on (nacer.smiafiliados.afidni,fecha_control)
					nacer.smiafiliados.afidni,
					nacer.smiafiliados.afinombre,
					nacer.smiafiliados.afiapellido,
					nacer.smiafiliados.afifechanac as fecha_nac,
					trazadorassps.trazadora_2.edad_gestacional,
					trazadorassps.trazadora_2.fecha_control,
					trazadorassps.trazadora_2.tension_arterial,
					'Desde Trazadora II - NACER' as comentario
					from trazadorassps.trazadora_2 
					inner join nacer.smiafiliados on trazadorassps.trazadora_2.id_smiafiliados=nacer.smiafiliados.id_smiafiliados
					where cuie = '$cuie' and (fecha_control between '$fecha_desde' and '$fecha_hasta')

					union --trazadorassps.trazadora_2 con beneficiarios en uad.beneficiarios
					
					select distinct on (uad.beneficiarios.numero_doc,fecha_control)
					uad.beneficiarios.numero_doc as afidni,
					uad.beneficiarios.nombre_benef,
					uad.beneficiarios.apellido_benef,
					uad.beneficiarios.fecha_nacimiento_benef as fecha_nac,
					trazadorassps.trazadora_2.edad_gestacional,
					trazadorassps.trazadora_2.fecha_control,
					trazadorassps.trazadora_2.tension_arterial,
					'Desde Trazadora II - EXTERNO' as comentario
					from trazadorassps.trazadora_2 
					inner join uad.beneficiarios on trazadorassps.trazadora_2.id_beneficiarios=uad.beneficiarios.id_beneficiarios
					where cuie = '$cuie' and (fecha_control between '$fecha_desde' and '$fecha_hasta')

					union

					SELECT distinct on (num_doc,fecha_control)
					num_doc::numeric (10,0)::text as afidni, nombre,apellido,'1800-01-01' as fecha_nac,sem_gestacion,fecha_control,ta,
					'Desde Trazadora.embarazadas' as comentario
					from trazadoras.embarazadas where cuie = '$cuie' and (fecha_control between '$fecha_desde' and '$fecha_hasta')

					union
					--desde SIPWEB
					SELECT * from (
						SELECT
						uad.beneficiarios.numero_doc::text,
						uad.beneficiarios.nombre_benef::text,
						uad.beneficiarios.apellido_benef::text,
						uad.beneficiarios.fecha_nacimiento_benef as fecha_nac,
						sip_clap.control_prenatal.var_0119 ::real AS edad_ges_var_0119,
						date ('20'||(sip_clap.control_prenatal.var_0118::character(2)) ||'-'|| sip_clap.control_prenatal.var_0117 ||'-'|| sip_clap.control_prenatal.var_0116) AS fecha_control,
						sip_clap.control_prenatal.var_0121 || '/0' || sip_clap.control_prenatal.var_0394 as tension_arterial,
						'Desde SIPWEB' as comentario
						FROM
						sip_clap.hcperinatal
						INNER JOIN sip_clap.control_prenatal ON sip_clap.hcperinatal.id_hcperinatal = sip_clap.control_prenatal.id_hcperinatal
						INNER JOIN uad.beneficiarios ON uad.beneficiarios.clave_beneficiario = sip_clap.hcperinatal.clave_beneficiario
						where upper(id_efector)='$cuie'
						) as eee
					where fecha_control between '$fecha_desde' and '$fecha_hasta'

	order by 1,5 DESC) as zzz";

$res_sql_emb= sql($sql_embarazadas) or fin_pagina();
return $res_sql_emb;		
		    
}

function ninios_menores_1_anio($fecha_desde,$fecha_hasta,$cuie){
	//ninio menores de 1 anio
		$sql_ninio="SELECT distinct on (nacer.smiafiliados.afidni,trazadorassps.trazadora_4.fecha_control)
		nacer.smiafiliados.afidni,
		nacer.smiafiliados.afinombre,
		nacer.smiafiliados.afiapellido,
		nacer.smiafiliados.afifechanac,
		trazadorassps.trazadora_4.fecha_control,
		trazadorassps.trazadora_4.peso,
  		trazadorassps.trazadora_4.talla,
  		trazadorassps.trazadora_4.perimetro_cefalico,
  		trazadorassps.trazadora_4.percentilo_peso_edad,
  		trazadorassps.trazadora_4.percentilo_talla_edad,
  		trazadorassps.trazadora_4.percentilo_perim_cefalico_edad,
  		trazadorassps.trazadora_4.percentilo_peso_talla
		from trazadorassps.trazadora_4 
		inner join nacer.smiafiliados on trazadorassps.trazadora_4.id_smiafiliados=nacer.smiafiliados.id_smiafiliados
		where cuie = '$cuie' and 
		(fecha_control - fecha_nac >= 0 and fecha_control - fecha_nac < 365) and
		(fecha_control between '$fecha_desde' and '$fecha_hasta')

		union --trazadorassps.trazadora_4 con beneficiarios en uad.beneficiarios
		
		select distinct on (uad.beneficiarios.numero_doc,trazadorassps.trazadora_4.fecha_control)
		uad.beneficiarios.numero_doc,
		uad.beneficiarios.nombre_benef,
		uad.beneficiarios.apellido_benef,
		uad.beneficiarios.fecha_nacimiento_benef,
		trazadorassps.trazadora_4.fecha_control,
		trazadorassps.trazadora_4.peso,
  		trazadorassps.trazadora_4.talla,
  		trazadorassps.trazadora_4.perimetro_cefalico,
  		trazadorassps.trazadora_4.percentilo_peso_edad,
  		trazadorassps.trazadora_4.percentilo_talla_edad,
  		trazadorassps.trazadora_4.percentilo_perim_cefalico_edad,
  		trazadorassps.trazadora_4.percentilo_peso_talla
		from trazadorassps.trazadora_4 
		inner join uad.beneficiarios on trazadorassps.trazadora_4.id_beneficiarios=uad.beneficiarios.id_beneficiarios
		where cuie = '$cuie' and 
		(trazadora_4.fecha_control - trazadora_4.fecha_nac >= 0 and trazadora_4.fecha_control - trazadora_4.fecha_nac < 365) and
		(trazadora_4.fecha_control between '$fecha_desde' and '$fecha_hasta')
		
		union --beneficiarios en trazadoras.nino_new

		SELECT distinct on (num_doc,fecha_control)
		num_doc::numeric(10,0)::text as dni,nombre,apellido,fecha_nac,fecha_control,
		peso,talla,perim_cefalico,percen_peso_edad,percen_talla_edad,percen_perim_cefali_edad,percen_peso_talla
		from trazadoras.nino_new 
		where 
		cuie = '$cuie' and 
		(fecha_control - fecha_nac >= 0 and fecha_control - fecha_nac < 365) 
		and (fecha_control between '$fecha_desde' and '$fecha_hasta')";

		$res_sql_ninio_trzsps= sql($sql_ninio) or fin_pagina();
		return $res_sql_ninio_trzsps;
		
}

function ninios_entre_1_y_9_anios($fecha_desde,$fecha_hasta,$cuie){
	//calculo de los controles de niños entre 1 y 9 años
		$cons_1_a_9="SELECT distinct on (nacer.smiafiliados.afidni,trazadorassps.trazadora_7.fecha_control)
		nacer.smiafiliados.afidni,
		nacer.smiafiliados.afinombre,
		nacer.smiafiliados.afiapellido,
		nacer.smiafiliados.afifechanac,
		trazadorassps.trazadora_7.fecha_control,
		trazadorassps.trazadora_7.peso,
		trazadorassps.trazadora_7.talla,
		trazadorassps.trazadora_7.percentilo_peso_edad,
		trazadorassps.trazadora_7.percentilo_talla_edad,
		trazadorassps.trazadora_7.percentilo_peso_talla,
		trazadorassps.trazadora_7.tension_arterial::text
		from trazadorassps.trazadora_7 
		inner join nacer.smiafiliados on trazadorassps.trazadora_7.id_smiafiliados=nacer.smiafiliados.id_smiafiliados
		where cuie = '$cuie' and 
		(fecha_control - fecha_nac >= 366 and fecha_control - fecha_nac < 3649) and
		(fecha_control between '$fecha_desde' and '$fecha_hasta')

		union --trazadorassps.trazadora_7 con beneficiarios en uad.beneficiarios
		
		select distinct on (uad.beneficiarios.numero_doc,trazadorassps.trazadora_7.fecha_control)
		uad.beneficiarios.numero_doc,
		uad.beneficiarios.nombre_benef,
		uad.beneficiarios.apellido_benef,
		uad.beneficiarios.fecha_nacimiento_benef,
		trazadorassps.trazadora_7.fecha_control,
		trazadorassps.trazadora_7.peso,
		trazadorassps.trazadora_7.talla,
		trazadorassps.trazadora_7.percentilo_peso_edad,
		trazadorassps.trazadora_7.percentilo_talla_edad,
		trazadorassps.trazadora_7.percentilo_peso_talla,
		trazadorassps.trazadora_7.tension_arterial::text
		from trazadorassps.trazadora_7 
		inner join uad.beneficiarios on trazadorassps.trazadora_7.id_beneficiarios=uad.beneficiarios.id_beneficiarios
		where cuie = '$cuie' and 
		(trazadora_7.fecha_control - trazadora_7.fecha_nac >= 366 and trazadora_7.fecha_control - trazadora_7.fecha_nac < 3649) and
		(trazadora_7.fecha_control between '$fecha_desde' and '$fecha_hasta')
		
		union --beneficiarios en trazadoras.nino_new

		SELECT distinct on (num_doc,fecha_control)
		num_doc::numeric(10,0)::text as dni,nombre,apellido,fecha_nac,fecha_control,
		peso,talla,percen_peso_edad,percen_talla_edad,percen_peso_talla,ta
		from trazadoras.nino_new 
		where 
		cuie = '$cuie' and 
		(fecha_control - fecha_nac >= 366 and fecha_control - fecha_nac < 3649) 
		and (fecha_control between '$fecha_desde' and '$fecha_hasta')";

		$res_cons_1_a_9=sql($cons_1_a_9) or fin_pagina();
		return $res_cons_1_a_9;
}

function adolescentes($fecha_desde,$fecha_hasta,$cuie){
		//adolescentes
		/*$sql_adol="SELECT distinct on (num_doc,fecha_control) num_doc,fecha_control from trazadoras.nino_new 
						where 
						cuie = '$cuie' and 
						(fecha_control - fecha_nac >= 3651 and fecha_control - fecha_nac < 7299) and
						(fecha_control between '$fecha_desde' and '$fecha_hasta')";

		$res_sql_adol= sql($sql_adol) or fin_pagina();*/
		    
		$sql_adol="SELECT distinct on (nacer.smiafiliados.afidni,trazadorassps.trazadora_10.fecha_control)
			nacer.smiafiliados.afidni,
			nacer.smiafiliados.afinombre,
			nacer.smiafiliados.afiapellido,
			trazadorassps.trazadora_10.fecha_control,
			trazadorassps.trazadora_10.talla,
			trazadorassps.trazadora_10.peso,
			trazadorassps.trazadora_10.tension_arterial
			from trazadorassps.trazadora_10
			inner join nacer.smiafiliados on trazadorassps.trazadora_10.id_smiafiliados=nacer.smiafiliados.id_smiafiliados
			where cuie = '$cuie' and 
			(fecha_control - fecha_nac >= 3650 and fecha_control - fecha_nac < 7299) and
			(fecha_control between '$fecha_desde' and '$fecha_hasta')
			union 
			select distinct on (uad.beneficiarios.numero_doc,trazadorassps.trazadora_10.fecha_control)
			uad.beneficiarios.numero_doc,
			uad.beneficiarios.nombre_benef,
			uad.beneficiarios.apellido_benef,
			trazadorassps.trazadora_10.fecha_control,
			trazadorassps.trazadora_10.talla,
			trazadorassps.trazadora_10.peso,
			trazadorassps.trazadora_10.tension_arterial
			from trazadorassps.trazadora_10 
			inner join uad.beneficiarios on trazadorassps.trazadora_10.id_beneficiarios=uad.beneficiarios.id_beneficiarios
			where cuie = '$cuie' and 
			(trazadora_10.fecha_control - trazadora_10.fecha_nac >= 3650 and trazadora_10.fecha_control - trazadora_10.fecha_nac < 7299) and
			(trazadora_10.fecha_control between '$fecha_desde' and '$fecha_hasta')

			union 

			SELECT distinct on (num_doc,fecha_control) 
						num_doc::numeric(10,0)::text as dni,nombre,apellido,fecha_control,
						talla,peso,ta
						from trazadoras.nino_new 
						where cuie = '$cuie' and 
						(fecha_control - fecha_nac >= 3650 and fecha_control - fecha_nac < 7299) and
						(fecha_control between '$fecha_desde' and '$fecha_hasta')";

		$res_sql_adol_trz10= sql($sql_adol) or fin_pagina();
		    
		//return $res_sql_adol->RecordCount()+$res_sql_adol_trz10->RecordCount();
		return $res_sql_adol_trz10;
		    
	// fin de adolescentes

}


function diabeticos($fecha_desde,$fecha_hasta,$cuie){

	$sql_dia="SELECT distinct on (afidni,fecha_control)
		afidni,afinombre,afiapellido,afifechanac,fecha_control,estado from (
		SELECT distinct on (nacer.smiafiliados.afidni,fichero.fichero.fecha_control)
		nacer.smiafiliados.afidni,
		nacer.smiafiliados.afinombre,
		nacer.smiafiliados.afiapellido,
		nacer.smiafiliados.afifechanac,
		fichero.fichero.fecha_control,
		'desde fichero (nacer)' as estado
		from fichero.fichero
		inner join nacer.smiafiliados on fichero.fichero.id_smiafiliados=nacer.smiafiliados.id_smiafiliados
		where cuie='$cuie' and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and diabetico='SI'
	union
		select distinct on (uad.beneficiarios.numero_doc,fichero.fichero.fecha_control)
		uad.beneficiarios.numero_doc,
		uad.beneficiarios.nombre_benef,
		uad.beneficiarios.apellido_benef,
		uad.beneficiarios.fecha_nacimiento_benef,
		fichero.fichero.fecha_control,
		'desde fichero (emp.rapido)' as estado
		from fichero.fichero
		inner join uad.beneficiarios on fichero.fichero.id_beneficiarios=uad.beneficiarios.id_beneficiarios
		where cuie='$cuie' and 
		fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and diabetico='SI'
	union

	SELECT distinct on (clasificacion_remediar2.num_doc,clasificacion_remediar2.fecha_control)
	clasificacion_remediar2.num_doc,
	clasificacion_remediar2.nombre,
	clasificacion_remediar2.apellido,
	clasificacion_remediar2.fecha_nac,
	clasificacion_remediar2.fecha_control,
	'desde clasificacion' as estado
	from trazadoras.clasificacion_remediar2
	where cuie = '$cuie' and fecha_control between '$fecha_desde' and '$fecha_hasta' and diabetico = 'SI'

	union
--consulta desde seguimientos
	
	select distinct on (uad.beneficiarios.numero_doc,trazadoras.seguimiento_remediar.fecha_comprobante)
  uad.beneficiarios.numero_doc,
  uad.beneficiarios.nombre_benef,
  uad.beneficiarios.apellido_benef,
  uad.beneficiarios.fecha_nacimiento_benef,
  trazadoras.seguimiento_remediar.fecha_comprobante as fecha_control,
  'desde seguimiento' as estado 
  from trazadoras.seguimiento_remediar
  inner join uad.beneficiarios on uad.beneficiarios.clave_beneficiario=trim (' ' from trazadoras.seguimiento_remediar.clave_beneficiario)
  --inner join trazadoras.clasificacion_remediar2 on trazadoras.clasificacion_remediar2.num_doc=uad.beneficiarios.numero_doc
  where trazadoras.seguimiento_remediar.efector='$cuie' 
  --and trazadoras.clasificacion_remediar2.diabetico is not null
  and trazadoras.seguimiento_remediar.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
  and trazadoras.seguimiento_remediar.dtm2<>'sin dmt'

    ) as ccc order by 1,5";

	$result_dia=sql($sql_dia) or fin_pagina();
	return $result_dia;
}

function hipertensos($fecha_desde,$fecha_hasta,$cuie){
	$sql_hip="SELECT distinct on (afidni,fecha_control)
          afidni,afinombre,afiapellido,afifechanac,fecha_control,estado from (
          SELECT distinct on (nacer.smiafiliados.afidni,fichero.fichero.fecha_control)
          nacer.smiafiliados.afidni,
          nacer.smiafiliados.afinombre,
          nacer.smiafiliados.afiapellido,
          nacer.smiafiliados.afifechanac,
          fichero.fichero.fecha_control,
          'desde fichero (nacer)' as estado
          from fichero.fichero
          inner join nacer.smiafiliados on fichero.fichero.id_smiafiliados=nacer.smiafiliados.id_smiafiliados
          where cuie='$cuie' and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and hipertenso='SI'
union
          select distinct on (uad.beneficiarios.numero_doc,fichero.fichero.fecha_control)
          uad.beneficiarios.numero_doc,
          uad.beneficiarios.nombre_benef,
          uad.beneficiarios.apellido_benef,
          uad.beneficiarios.fecha_nacimiento_benef,
          fichero.fichero.fecha_control,
          'desde fichero (emp.rapido)' as estado
          from fichero.fichero
          inner join uad.beneficiarios on fichero.fichero.id_beneficiarios=uad.beneficiarios.id_beneficiarios
          where cuie='$cuie' and 
          fichero.fecha_control between '$fecha_desde' and '$fecha_hasta' and hipertenso='SI'
union

    SELECT distinct on (clasificacion_remediar2.num_doc,clasificacion_remediar2.fecha_control)
    clasificacion_remediar2.num_doc,
    clasificacion_remediar2.nombre,
    clasificacion_remediar2.apellido,
    clasificacion_remediar2.fecha_nac,
    clasificacion_remediar2.fecha_control,
    'desde clasificacion' as estado
    from trazadoras.clasificacion_remediar2
    where cuie = '$cuie' and fecha_control between '$fecha_desde' and '$fecha_hasta' and hipertenso = 'SI'

union
--consulta desde seguimientos
	
	select distinct on (uad.beneficiarios.numero_doc,trazadoras.seguimiento_remediar.fecha_comprobante)
  uad.beneficiarios.numero_doc,
  uad.beneficiarios.nombre_benef,
  uad.beneficiarios.apellido_benef,
  uad.beneficiarios.fecha_nacimiento_benef,
  trazadoras.seguimiento_remediar.fecha_comprobante as fecha_control,
  'desde seguimiento' as estado
  from trazadoras.seguimiento_remediar
  inner join uad.beneficiarios on uad.beneficiarios.clave_beneficiario=trim (' ' from trazadoras.seguimiento_remediar.clave_beneficiario)
  where trazadoras.seguimiento_remediar.efector='$cuie' 
  and trazadoras.seguimiento_remediar.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
  and (trazadoras.seguimiento_remediar.hta='SI' or trazadoras.seguimiento_remediar.hta='on')

    ) as ccc order by 1,5";
				
	$res_hip= sql($sql_hip) or fin_pagina();
	return $res_hip;
}
?>