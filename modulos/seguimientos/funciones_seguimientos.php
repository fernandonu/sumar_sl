<?php
require_once('consultas_bd_metas_2022.php');

function embarazo_riesgo($fecha_desde,$fecha_hasta,$cuie){

	//consultas para embarazos de riesgo
	$sql_riesgo="SELECT distinct on (afidni) * from (
				SELECT * from (
				select distinct on (smiafiliados.afidni) fichero.cuie,fichero.fecha_control,
				smiafiliados.afidni, smiafiliados.afinombre,
				smiafiliados.afiapellido, fichero.codigo_riesgo,
				nomenclador.descripcion from fichero.fichero 
				inner join nacer.smiafiliados on smiafiliados.id_smiafiliados=fichero.id_smiafiliados
				inner join  facturacion.nomenclador on fichero.codigo_riesgo=nomenclador.codigo
				where fichero.embarazo_riesgo='SI' and fichero.cuie='$cuie' and
				fichero.fecha_control between '$fecha_desde' and '$fecha_hasta'
				union
				select distinct on (beneficiarios.numero_doc) fichero.cuie,
				fichero.fecha_control,beneficiarios.numero_doc as afidni, beneficiarios.nombre_benef,
				beneficiarios.apellido_benef, fichero.codigo_riesgo,nomenclador.descripcion from fichero.fichero 
				inner join uad.beneficiarios using (id_beneficiarios)
				inner join  facturacion.nomenclador on fichero.codigo_riesgo=nomenclador.codigo
				where fichero.embarazo_riesgo='SI'  and fichero.cuie='$cuie' and
				fichero.fecha_control between '$fecha_desde' and '$fecha_hasta') as ccc
				union
				select distinct on (smiafiliados.afidni) comprobante.cuie,prestacion.fecha_prestacion,
				smiafiliados.afidni,smiafiliados.afinombre,
				smiafiliados.afiapellido,nomenclador.codigo,
				nomenclador.descripcion from facturacion.prestacion
				inner join facturacion.nomenclador using (id_nomenclador)
				inner join facturacion.comprobante using (id_comprobante)
				inner join nacer.smiafiliados using (id_smiafiliados)
				where nomenclador.grupo='NT'
				and comprobante.cuie='$cuie'
				and prestacion.fecha_prestacion between '$fecha_desde' and '$fecha_hasta'
				and (nomenclador.codigo='N004' or nomenclador.codigo='N006')
				) as ttt";
		$res_riesgo=sql($sql_riesgo) or fin_pagina();
		return $res_riesgo;

}

function talleres ($fecha_desde,$fecha_hasta,$cuie){
	//consutas para talleres

	$sql_taller="SELECT distinct on (afidni,codigo_taller) * from (
		        select distinct on (smiafiliados.afidni,codigo_taller) fichero.cuie,fichero.fecha_control,
		        smiafiliados.afidni, smiafiliados.afinombre,
		        smiafiliados.afiapellido, fichero.codigo_taller,
		        nomenclador.descripcion from fichero.fichero 
		        inner join nacer.smiafiliados on smiafiliados.id_smiafiliados=fichero.id_smiafiliados
		        inner join  facturacion.nomenclador on fichero.codigo_taller=nomenclador.codigo
		        where fichero.taller='SI' and fichero.cuie='$cuie'
		        and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta'
		        union
		        select distinct on (beneficiarios.numero_doc,codigo_taller) fichero.cuie,
		        fichero.fecha_control,beneficiarios.numero_doc as afidni, beneficiarios.nombre_benef,
		        beneficiarios.apellido_benef, fichero.codigo_taller,nomenclador.descripcion from fichero.fichero 
		        inner join uad.beneficiarios using (id_beneficiarios)
		        inner join  facturacion.nomenclador on fichero.codigo_taller=nomenclador.codigo
		        where fichero.taller='SI' and fichero.cuie='$cuie'
		        and fichero.fecha_control between '$fecha_desde' and '$fecha_hasta'
		        union
		        select distinct on (smiafiliados.afidni,nomenclador.codigo) comprobante.cuie,prestacion.fecha_prestacion,
		        smiafiliados.afidni,smiafiliados.afinombre,
		        smiafiliados.afiapellido,nomenclador.codigo as codigo_taller,
		        nomenclador.descripcion from facturacion.prestacion
		        inner join facturacion.nomenclador using (id_nomenclador)
		        inner join facturacion.comprobante using (id_comprobante)
		        inner join nacer.smiafiliados using (id_smiafiliados)
		        where nomenclador.grupo='TA'  and comprobante.cuie='$cuie' and
		        prestacion.fecha_prestacion between '$fecha_desde' and '$fecha_hasta'
		        ) as ttt";
	$res_taller=sql($sql_taller) or fin_pagina();
	return $res_taller;
}


function embarazadas($fecha_desde,$fecha_hasta,$cuie){
	//embarazadas
    		
		 /*$sql_embarazadas="SELECT distinct on(afidni) *  from (
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

			order by 1,5 DESC) as zzz";*/

		$sql_embarazadas = "SELECT distinct on (c.afidni) 
							c.afidni,
							c.afinombre,
							c.afiapellido,
							c.afifechanac as fecha_nac							
							from facturacion.comprobante a
							, facturacion.prestacion b
							, nacer.smiafiliados c
							, facturacion.nomenclador d
							where a.id_comprobante = b.id_comprobante 
							and a.id_smiafiliados = c.id_smiafiliados
							and b.id_nomenclador = d.id_nomenclador
							and a.cuie = '$cuie'
							and a.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
							and d.grupo||d.codigo in ('CTC005', 'CTC006')";

		$res_sql_emb= sql($sql_embarazadas) or fin_pagina();
		return $res_sql_emb;		
		    
}

function emb_sem_12($fecha_desde,$fecha_hasta,$cuie){
	//antes de la semana 12
		$sql_embarazadas_12_pers = "SELECT distinct on (dni) * from (
			SELECT distinct on (nacer.smiafiliados.afidni)
					nacer.smiafiliados.afidni as dni,
					nacer.smiafiliados.afinombre,
					nacer.smiafiliados.afiapellido,
					nacer.smiafiliados.afifechanac as fecha_nac,
					trazadorassps.trazadora_1.fum,
					trazadorassps.trazadora_1.fpp,
					trazadorassps.trazadora_1.edad_gestacional,
					trazadorassps.trazadora_1.fecha_control_prenatal,
					'Desde trazadora I - NACER' as comentario
					from trazadorassps.trazadora_1 
					inner join nacer.smiafiliados on trazadorassps.trazadora_1.id_smiafiliados=nacer.smiafiliados.id_smiafiliados
					where cuie = '$cuie' and 
					trazadorassps.trazadora_1.edad_gestacional<=12 and 
					(fecha_control_prenatal between '$fecha_desde' and '$fecha_hasta')
					
					union --trazadorassps.trazadora_1 con beneficiarios en uad.beneficiarios
					
					select distinct on (uad.beneficiarios.numero_doc)
					uad.beneficiarios.numero_doc as dni,
					uad.beneficiarios.nombre_benef,
					uad.beneficiarios.apellido_benef,
					uad.beneficiarios.fecha_nacimiento_benef as fecha_nac,
					trazadorassps.trazadora_1.fum,
					trazadorassps.trazadora_1.fpp,
					trazadorassps.trazadora_1.edad_gestacional,		
					trazadorassps.trazadora_1.fecha_control_prenatal,
					'Desde trazadora I - EXTERNO' as comentario
					from trazadorassps.trazadora_1 
					inner join uad.beneficiarios on trazadorassps.trazadora_1.id_beneficiarios=uad.beneficiarios.id_beneficiarios
					where cuie = '$cuie' and 
					trazadorassps.trazadora_1.edad_gestacional<=12 and 
					(trazadora_1.fecha_control_prenatal between '$fecha_desde' and '$fecha_hasta')

					union

					select (num_doc::numeric(10,0))::text as dni,nombre,apellido,'1800-01-01' as fecha_nac,fum,fpp,sem_gestacion,fecha_control,
						'Desde trazadora.embarazadas' as comentario
						from trazadoras.embarazadas 
						where 
						cuie = '$cuie' and 
						fecha_control between '$fecha_desde' and '$fecha_hasta' and
						trazadoras.embarazadas.sem_gestacion<=12

					union
					--desde SIPWEB
					SELECT * from (
								SELECT
								uad.beneficiarios.numero_doc::text as dni,
								uad.beneficiarios.nombre_benef::text as afinombre,
								uad.beneficiarios.apellido_benef::text as afiapellido,
								uad.beneficiarios.fecha_nacimiento_benef as fecha_nac,
								sip_clap.hcgestacion_actual.var_0057 ::date AS fum,
								sip_clap.hcgestacion_actual.var_0058 ::date AS fpp,
								sip_clap.control_prenatal.var_0119 ::real AS edad_gestacional,
								date ('20'||(sip_clap.control_prenatal.var_0118::character(2)) ||'-'|| sip_clap.control_prenatal.var_0117 ||'-'|| sip_clap.control_prenatal.var_0116) AS fecha_control_prenatal,
								'Desde SIPWEB' as comentario					
								FROM
								sip_clap.hcperinatal
								INNER JOIN sip_clap.hcgestacion_actual ON sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcgestacion_actual.id_hcperinatal
								INNER JOIN sip_clap.control_prenatal ON sip_clap.hcperinatal.id_hcperinatal = sip_clap.control_prenatal.id_hcperinatal
								INNER JOIN uad.beneficiarios ON uad.beneficiarios.clave_beneficiario = sip_clap.hcperinatal.clave_beneficiario
								where upper(id_efector)='$cuie'
								) as eee
							where fecha_control_prenatal between '$fecha_desde' and '$fecha_hasta'
							and edad_gestacional<=12

					
			order by 1,7 
		) as zzz";

		$res_sql_emb_12_pers= sql($sql_embarazadas_12_pers) or fin_pagina();
		return $res_sql_emb_12_pers;

}

function ninios_menores_1_anio($fecha_desde,$fecha_hasta,$cuie){
	//ninio menores de 1 anio
		$sql_ninio="SELECT distinct on (afidni) * from (
			SELECT distinct on (nacer.smiafiliados.afidni,trazadorassps.trazadora_4.fecha_control)
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
			and (fecha_control between '$fecha_desde' and '$fecha_hasta')
			) as z";

		$res_sql_ninio_trzsps= sql($sql_ninio) or fin_pagina();
		return $res_sql_ninio_trzsps;
		
}

function ninios_entre_1_y_9_anios($fecha_desde,$fecha_hasta,$cuie){
	//calculo de los controles de niños entre 1 y 9 años
		$cons_1_a_9="SELECT distinct on (afidni) * from (
			SELECT distinct on (nacer.smiafiliados.afidni,trazadorassps.trazadora_7.fecha_control)
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
			and (fecha_control between '$fecha_desde' and '$fecha_hasta')
			) as z";

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
		    
		$sql_adol="SELECT distinct on (afidni) * from (
			SELECT distinct on (nacer.smiafiliados.afidni,trazadorassps.trazadora_10.fecha_control)
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
						(fecha_control between '$fecha_desde' and '$fecha_hasta')
			) as z";

		$res_sql_adol_trz10= sql($sql_adol) or fin_pagina();
		    
		//return $res_sql_adol->RecordCount()+$res_sql_adol_trz10->RecordCount();
		return $res_sql_adol_trz10;
		    
	// fin de adolescentes

}

function cuidado_sexual($fecha_desde,$fecha_hasta,$cuie){
	//cuidado sexual
		    
		$sql_cuidado_sexual="SELECT distinct on (dni) * from ( 
			SELECT distinct on (nacer.smiafiliados.afidni,fichero.fichero.fecha_control)
							nacer.smiafiliados.afidni as dni,
							nacer.smiafiliados.afinombre,
							nacer.smiafiliados.afiapellido,
							nacer.smiafiliados.afifechanac,
							fichero.fichero.fecha_control,
							fichero.fichero.peso,
							fichero.fichero.talla,
							fichero.fichero.ta
							from fichero.fichero
							inner join nacer.smiafiliados on fichero.fichero.id_smiafiliados=nacer.smiafiliados.id_smiafiliados
							where cuie = '$cuie' and 
							(fecha_control between '$fecha_desde' and '$fecha_hasta' and fichero.fichero.salud_rep = 'SI')

							union 

			select distinct on (uad.beneficiarios.numero_doc,fichero.fichero.fecha_control)
							uad.beneficiarios.numero_doc as dni,
							uad.beneficiarios.nombre_benef,
							uad.beneficiarios.apellido_benef,
							uad.beneficiarios.fecha_nacimiento_benef,
							fichero.fichero.fecha_control,
							fichero.fichero.peso,
							fichero.fichero.talla,
							fichero.fichero.ta
							from fichero.fichero 
							inner join uad.beneficiarios on fichero.fichero.id_beneficiarios=uad.beneficiarios.id_beneficiarios
							where cuie = '$cuie' and			
							(fecha_control between '$fecha_desde' and '$fecha_hasta') and fichero.salud_rep = 'SI'
			) as tabla1";
			
			$result_ssr1=sql($sql_cuidado_sexual) or fin_pagina();
			return $result_ssr1;
}

function diabeticos($fecha_desde,$fecha_hasta,$cuie){
	//dia e hip
		$sql_dia="SELECT distinct on (afidni)
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
		
				SELECT distinct on (clasificacion_remediar2.num_doc,clasificacion_remediar2.fecha_control)
				clasificacion_remediar2.num_doc,
				clasificacion_remediar2.nombre,
				clasificacion_remediar2.apellido,
				clasificacion_remediar2.fecha_nac,
				clasificacion_remediar2.fecha_control,
				'desde clasificacion' as estado
				from trazadoras.clasificacion_remediar2,
				nacer.smiafiliados 
				where smiafiliados.clavebeneficiario =  clasificacion_remediar2.clave_beneficiario
				and cuie = '$cuie' and fecha_control between '$fecha_desde' and '$fecha_hasta' and diabetico = 'SI'
		
				union
				--consulta desde seguimientos 
		
				select distinct on (nacer.smiafiliados.afidni,trazadoras.seguimiento_remediar.fecha_comprobante)
				--uad.beneficiarios.numero_doc,
				nacer.smiafiliados.afidni,
				--uad.beneficiarios.nombre_benef,
				nacer.smiafiliados.afinombre,
				--uad.beneficiarios.apellido_benef,
				nacer.smiafiliados.afiapellido,
				--uad.beneficiarios.fecha_nacimiento_benef,
				nacer.smiafiliados.afifechanac,
				trazadoras.seguimiento_remediar.fecha_comprobante as fecha_control,
				'desde seguimiento' as estado 
				from trazadoras.seguimiento_remediar , nacer.smiafiliados 
				--inner join uad.beneficiarios on uad.beneficiarios.clave_beneficiario=trim (' ' from trazadoras.seguimiento_remediar.clave_beneficiario)
				--inner join trazadoras.clasificacion_remediar2 on trazadoras.clasificacion_remediar2.num_doc=uad.beneficiarios.numero_doc
				where seguimiento_remediar.clave_beneficiario = nacer.smiafiliados.clavebeneficiario
				and trazadoras.seguimiento_remediar.efector='$cuie' 
				--and trazadoras.clasificacion_remediar2.diabetico is not null
				and trazadoras.seguimiento_remediar.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
				and trazadoras.seguimiento_remediar.dtm2<>'sin dmt'
		
				) as ccc order by 1,5";

	$result_dia=sql($sql_dia) or fin_pagina();
	return $result_dia;
}

function hipertensos($fecha_desde,$fecha_hasta,$cuie){
	$sql_hip="SELECT distinct on (afidni)
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
			from trazadoras.clasificacion_remediar2,
			nacer.smiafiliados 
			where trazadoras.clasificacion_remediar2.clave_beneficiario = nacer.smiafiliados.clavebeneficiario 
			and cuie = '$cuie' and fecha_control between '$fecha_desde' and '$fecha_hasta' and hipertenso = 'SI'

			union
			--consulta desde seguimientos 

			select distinct on (nacer.smiafiliados.afidni,trazadoras.seguimiento_remediar.fecha_comprobante)
			--uad.beneficiarios.numero_doc,
			nacer.smiafiliados.afidni,
			--uad.beneficiarios.nombre_benef,
			nacer.smiafiliados.afinombre,
			--uad.beneficiarios.apellido_benef,
			nacer.smiafiliados.afiapellido,
			--uad.beneficiarios.fecha_nacimiento_benef,
			nacer.smiafiliados.afifechanac,
			trazadoras.seguimiento_remediar.fecha_comprobante as fecha_control,
			'desde seguimiento' as estado
			from trazadoras.seguimiento_remediar , nacer.smiafiliados 
			--inner join uad.beneficiarios on uad.beneficiarios.clave_beneficiario=trim (' ' from trazadoras.seguimiento_remediar.clave_beneficiario)
			where trazadoras.seguimiento_remediar.clave_beneficiario = nacer.smiafiliados.clavebeneficiario 
			and trazadoras.seguimiento_remediar.efector='$cuie' 
			and trazadoras.seguimiento_remediar.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
			and (trazadoras.seguimiento_remediar.hta='SI' or trazadoras.seguimiento_remediar.hta='on')

			) as ccc order by 1,5";
				
	$res_hip= sql($sql_hip) or fin_pagina();
	return $res_hip;
}

function vacunas($fecha_desde,$fecha_hasta,$cuie){
	$sql_vac="SELECT id_vac_apli,count (*) as cant from (
select distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
	trazadoras.vacunas.id_vac_apli,
	trazadoras.vacunas.fecha_vac,
	trazadoras.vac_apli.nombre as nom_vacum,
	trazadoras.dosis_apli.nombre as dosis,
	nacer.smiafiliados.afidni,
	nacer.smiafiliados.afinombre,
	nacer.smiafiliados.afiapellido,
	nacer.smiafiliados.afifechanac
from trazadoras.vacunas 
inner join nacer.smiafiliados on vacunas.id_smiafiliados=smiafiliados.id_smiafiliados
INNER JOIN trazadoras.vac_apli ON trazadoras.vacunas.id_vac_apli = trazadoras.vac_apli.id_vac_apli
INNER JOIN trazadoras.dosis_apli ON trazadoras.vacunas.id_dosis_apli = trazadoras.dosis_apli.id_dosis_apli
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' 
and (trazadoras.vacunas.eliminada=0)

union

select distinct on (uad.beneficiarios.numero_doc,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac)
	trazadoras.vacunas.id_vac_apli,
	trazadoras.vacunas.fecha_vac,
	trazadoras.vac_apli.nombre as nom_vacum,
	trazadoras.dosis_apli.nombre as dosis,
	uad.beneficiarios.numero_doc,
	uad.beneficiarios.nombre_benef,
	uad.beneficiarios.apellido_benef,
	uad.beneficiarios.fecha_nacimiento_benef	
from trazadoras.vacunas 
inner join uad.beneficiarios on vacunas.id_beneficiarios=beneficiarios.id_beneficiarios
INNER JOIN trazadoras.vac_apli ON trazadoras.vacunas.id_vac_apli = trazadoras.vac_apli.id_vac_apli
INNER JOIN trazadoras.dosis_apli ON trazadoras.vacunas.id_dosis_apli = trazadoras.dosis_apli.id_dosis_apli
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' 
and (trazadoras.vacunas.eliminada=0)
) as ccc group by id_vac_apli
order by 1";
			
			$res_vacunas=sql($sql_vac) or fin_pagina();
			while (!$res_vacunas->EOF){
			switch ($res_vacunas->fields['id_vac_apli']){
				case 2 : $efe_hep_b=$res_vacunas->fields['cant'];break;
				case 17 : $efe_neumococo_1=$res_vacunas->fields['cant'];break;
				case 16 : $efe_neumococo_2=$res_vacunas->fields['cant'];break;
				case 3: $efe_pentavalente=$res_vacunas->fields['cant'];break;
				case 5 : $efe_sabin=$res_vacunas->fields['cant'];break;
				case 20 : $efe_salk=$res_vacunas->fields['cant'];break;
				case 6 : $efe_triple_viral=$res_vacunas->fields['cant'];break;
				case 18 : $efe_gripe_1=$res_vacunas->fields['cant'];break;
				case 19 : $efe_gripe_2=$res_vacunas->fields['cant'];break;
				case 52 : $efe_gripe_3=$res_vacunas->fields['cant'];break;
				case 53 : $efe_gripe_4=$res_vacunas->fields['cant'];break;
				case 54 : $efe_gripe_5=$res_vacunas->fields['cant'];break;
				case 7 : $efe_hep_a=$res_vacunas->fields['cant'];break;
				case 8 : $efe_triple_bacteriana_celular=$res_vacunas->fields['cant'];break;
				case 9: $efe_triple_bacteriana_acelular=$res_vacunas->fields['cant'];break;
				case 10 : $efe_doble_bacteriana=$res_vacunas->fields['cant'];break;
				case 14: $efe_hpv=($res_vacunas->fields['cant'])?$res_vacunas->fields['cant']:0;break;
				case 11 : $efe_doble_viral=$res_vacunas->fields['cant'];break;
				case 13 : $efe_fiebre_amarilla=$res_vacunas->fields['cant'];break;
				case 48 : {$efe_hpv2=($res_vacunas->fields['cant'])?$res_vacunas->fields['cant']:0;
							$efe_hpv=$efe_hpv+$efe_hpv2;}break;
				default: break;
				}
			$res_vacunas->MoveNext();
			}
	$efe_neumococo=$efe_neumococo_1+$efe_neumococo_2;	
    $efe_gripe=$efe_gripe_1+$efe_gripe_2+$efe_gripe_3+$efe_gripe_4+$efe_gripe_5;
    $efe_sabin=$efe_sabin+$efe_salk;

    $vacunas=array (
    		"efe_hep_b"=>$efe_hep_b,
    		"efe_neumococo"=>$efe_neumococo,
    		"efe_pentavalente"=>$efe_pentavalente,
    		"efe_sabin"=>$efe_sabin,
    		"efe_triple_viral"=>$efe_triple_viral,
    		"efe_gripe"=>$efe_gripe,
    		"efe_hep_a"=>$efe_hep_a,
    		"efe_triple_bacteriana_celular"=>$efe_triple_bacteriana_celular,
    		"efe_triple_bacteriana_acelular"=>$efe_triple_bacteriana_acelular,
    		"efe_doble_bacteriana"=>$efe_doble_bacteriana,
    		"efe_hpv"=>$efe_hpv,
    		"efe_doble_viral"=>$efe_doble_viral,
    		"efe_fiebre_amarilla"=>$efe_fiebre_amarilla
    	);
    return $vacunas;
}

function embarazadas_2022($fecha_desde,$fecha_hasta,$cuie){
	
	$cantidad_e = embarazadas_e($fecha_desde,$fecha_hasta,$cuie);
	$cantidad_p = embarazadas_p($fecha_desde,$fecha_hasta,$cuie);
	$cantidad_r = embarazadas_p($fecha_desde,$fecha_hasta,$cuie);
	$meta_2022 = embarazadas_metas($cuie);
		
	return $resultado = array (
		"cantidad_e" => $cantidad_e, //estructura
		"cantidad_p" => $cantidad_p, //proceso
		"cantidad_r" => $cantidad_r, //resultado
		"meta" => $meta_2022 //meta desde la tabla
	);
}

function ninios_1_9_2022($fecha_desde,$fecha_hasta,$cuie){
	
	$cantidad_e = ninios_1_9_e($fecha_desde,$fecha_hasta,$cuie);
	$cantidad_p = ninios_1_9_p($fecha_desde,$fecha_hasta,$cuie);
	$cantidad_r = ninios_1_9_p($fecha_desde,$fecha_hasta,$cuie);
	$meta_2022 = ninios_1_9_metas($cuie);
	
	
	return $resultado = array (
		"cantidad_e" => $cantidad_e, //estructura
		"cantidad_p" => $cantidad_p, //proceso
		"cantidad_r" => $cantidad_r, //resultado
		"meta" => $meta_2022 //meta desde la tabla
	);
}

function ninios_menor_1_2022($fecha_desde,$fecha_hasta,$cuie){
	
	$cantidad_e = ninios_1_e($fecha_desde,$fecha_hasta,$cuie);
	$cantidad_p = ninios_1_p($fecha_desde,$fecha_hasta,$cuie);
	$cantidad_r = ninios_1_r($fecha_desde,$fecha_hasta,$cuie);
	$meta_2022 = ninios_1_metas($cuie);
	
	return $resultado = array (
		"cantidad_e" => $cantidad_e, //estructura
		"cantidad_p" => $cantidad_p, //proceso
		"cantidad_r" => $cantidad_r, //resultado
		"meta" => $meta_2022 //meta desde la tabla
	);
}

function adolecentes_2022($fecha_desde,$fecha_hasta,$cuie){
	$cantidad_e = adolecentes_e($fecha_desde,$fecha_hasta,$cuie);
	$cantidad_r = adolecentes_r($fecha_desde,$fecha_hasta,$cuie);
	$meta_2022 = adolecentes_metas($cuie);
	
	return $resultado = array (
		"cantidad_e" => $cantidad_e, //estructura
		"cantidad_r" => $cantidad_r, //resultado
		"meta" => $meta_2022 //meta desde la tabla
	);
}

function hta_2022($fecha_desde,$fecha_hasta,$cuie){
	$cantidad_e = hta_e($fecha_desde,$fecha_hasta,$cuie);
	$cantidad_p = hta_p($fecha_desde,$fecha_hasta,$cuie);
	$meta_2022 = hta_metas($cuie);
	
	return $resultado = array (
		"cantidad_e" => $cantidad_e, //estructura
		"cantidad_p" => $cantidad_p, //proceso
		"meta" => $meta_2022 //meta desde la tabla
	);
}

function dbt_2022($fecha_desde,$fecha_hasta,$cuie){
	$cantidad_e = dbt_e($fecha_desde,$fecha_hasta,$cuie);
	$cantidad_p = dbt_p($fecha_desde,$fecha_hasta,$cuie);
	$meta_2022 = dbt_metas($cuie);
	
	return $resultado = array (
		"cantidad_e" => $cantidad_e, //estructura
		"cantidad_p" => $cantidad_p, //proceso
		"meta" => $meta_2022 //meta desde la tabla
	);
}


function control_adultos ($fecha_desde,$fecha_hasta,$cuie) {
	$consulta = "SELECT distinct on (c.afidni) c.*
				from facturacion.comprobante a
				, facturacion.prestacion b
				, nacer.smiafiliados  c
				where a.id_comprobante =  b.id_comprobante
				and a.id_smiafiliados = c.id_smiafiliados
				and a.cuie =  '$cuie'
				and a.fecha_comprobante between  '$fecha_desde' and '$fecha_hasta'
				and extract ('Year' from  age(a.fecha_comprobante, c.afifechanac)) >=20";

	$result = sql($consulta) or fin_pagina();
	return $result;	
}
?>