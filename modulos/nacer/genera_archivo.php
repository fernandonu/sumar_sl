<?php

require_once("../../config.php");
//require_once("funciones_generales.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
$usuario1=$_ses_user['login'];
cargar_calendario();

if ($_POST['importa_agentes_sanitarios']){
  include 'importa_agentes_sanitarios.php';
}

if ($_POST['importa_sip_web']){
  include 'importa_sip_web.php';
}

if ($_POST['importa_cronicos']){
  include 'importa_cronicos.php';
}

if ($_POST['generar']){
	$sql_tmp="select * from public.dosep";
	$result1=sql($sql_tmp);
	$filename = 'DOSEP.txt';	

	  	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}

    	$result1->movefirst();
    	while (!$result1->EOF) {
    		$contenido="DNI";
    		$contenido.=str_repeat('0',8-strlen($result1->fields['num'])).$result1->fields['num'];
    		$contenido.=$result1->fields['ape']." ";
    		$contenido.=$result1->fields['nom'];
    		$contenido1=$contenido;
    		$contenido.=str_repeat(' ',61-strlen($contenido1));
    		$contenido.=substr($result1->fields['sexo'],0,1);
    		$contenido.="3225515700";
    		$contenido.="    SL";    		
    		if ($result1->fields['plan']=="PLG") $contenido.="T";
    		else $contenido.="A";
    		$contenido.="\n";
    		if (fwrite($handle, $contenido) === FALSE) {
        		echo "No se Puede escribir  ($filename)";
        		exit;
    		}
    		$result1->MoveNext();
    	}
    	echo "El Archivo ($filename) se genero con exito";
    
    	fclose($handle);
	
}

if ($_POST['generar_n']){
	$sql_tmp="select * from public.dosep";
	$result1=sql($sql_tmp);
	$filename = 'DOSEP_mod.txt';	

	  	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}

    	$result1->movefirst();
    	while (!$result1->EOF) {
        $fecha_nac=explode('/',$result1->fields['fecha_nac']);
        $dia=(strlen($fecha_nac[0])==1)?'0'.$fecha_nac[0]:$fecha_nac[0];        
        $mes=(strlen($fecha_nac[1])==1)?'0'.$fecha_nac[1]:$fecha_nac[1];;
        $anio=$fecha_nac[2];
        $fecha_nac=$anio.'-'.$mes.'-'.$dia;
        
        $contenido="DNI"."||";
    		$contenido.=str_repeat('0',8-strlen($result1->fields['num'])).$result1->fields['num'];
    		$contenido.="||";
    		$contenido.=$result1->fields['ape']." ".$result1->fields['nom'].str_repeat(' ',50-strlen($result1->fields['ape']." ".$result1->fields['nom']));
    		$contenido.="||";
    		$contenido.=substr($result1->fields['sexo'],0,1);
    		$contenido.="||";
    		$contenido.="912001";
    		$contenido.="||";
    		$contenido.="    5700";
    		$contenido.="||";
    		$contenido.="12";    		
    		$contenido.="||";
    		if ($result1->fields['plan']=="PLG") $contenido.="T";
        else $contenido.="A";
        $contenido.="||";
        $contenido.=$fecha_nac;
    		$contenido.="\r";
    		$contenido.="\n";
    		if (fwrite($handle, $contenido) === FALSE) {
        		echo "No se Puede escribir  ($filename)";
        		exit;
    		}
    		$result1->MoveNext();
    	}
    	echo "El Archivo ($filename) se genero con exito";
    
    	fclose($handle);
	
}


if ($_POST['nomivac']){
	
	
	$fecha_desde=fecha_db($_POST['fecha_desde']);
	$fecha_hasta=fecha_db($_POST['fecha_hasta']);

  $sql_function="CREATE or REPLACE function sinacentos (text) returns text AS $$
      select translate($1,'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ','aeiouAEIOUaeiouAEIOU');
      $$ language sql;";
  sql($sql_function);

	
	//EXPORTACION PARA NOMIVAC DESDE NACER.SMIAFILIADOS
  
  $sql_tmp="SELECT *,--codigo esquema
    CASE WHEN tabla_vacunas_completo.id_vacuna = 143 then 78 --actualizacion 2018
    when tabla_vacunas_completo.id_vacuna = 143 
          and (tabla_vacunas_completo.afitipocategoria=6) then 79 --embarazadas
          
    when tabla_vacunas_completo.id_vacuna = 134 --triple viral para la campaña oct-nov 2018
          and tabla_vacunas_completo.id_dosis = 7
          then 356

    when tabla_vacunas_completo.id_vacuna = 175 
          and tabla_vacunas_completo.edad_meses >=228 then 285 --mayores de 25 años
    when tabla_vacunas_completo.id_vacuna = 175 
          and tabla_vacunas_completo.edad_meses <120 then 196 --menores de 10 años
          
    when tabla_vacunas_completo.id_vacuna = 162
          and tabla_vacunas_completo.edad_meses >=3 
          and tabla_vacunas_completo.edad_meses <=15 then 304 --lactantes 
    when tabla_vacunas_completo.id_vacuna = 162 
          and tabla_vacunas_completo.edad_meses >=108 
          and tabla_vacunas_completo.edad_meses <228 then 305 --adolescentes
    
    when tabla_vacunas_completo.id_vacuna = 186 
          and tabla_vacunas_completo.edad_meses <=1 then 269
    when tabla_vacunas_completo.id_vacuna = 186 then 277
    
    when tabla_vacunas_completo.id_vacuna = 169 
          and tabla_vacunas_completo.edad_meses >=300 then 169
    when tabla_vacunas_completo.id_vacuna = 169 then 170
          
    when tabla_vacunas_completo.id_vacuna = 127 and tabla_vacunas_completo.edad_meses <=1 then 63
    when tabla_vacunas_completo.id_vacuna = 127 and tabla_vacunas_completo.edad_meses >1 then 109
    
    when tabla_vacunas_completo.id_vacuna = 188 and tabla_vacunas_completo.edad_meses <1 then 273 --neonatos
    when tabla_vacunas_completo.id_vacuna = 188 and (tabla_vacunas_completo.afitipocategoria=6) then 271 --embarazadas
    when tabla_vacunas_completo.id_vacuna = 188 then 272

    when tabla_vacunas_completo.id_vacuna = 190 and (tabla_vacunas_completo.afitipocategoria=6) then 276 --embarazadas
    when tabla_vacunas_completo.id_vacuna = 190 then 275
    
    when tabla_vacunas_completo.id_vacuna = 141 and (tabla_vacunas_completo.afitipocategoria=6) then 87
   
    WHEN tabla_vacunas_completo.id_vacuna = 191 
          and tabla_vacunas_completo.edad_meses < 6 then 321

    WHEN tabla_vacunas_completo.id_vacuna = 191 
          and tabla_vacunas_completo.edad_meses >=6 then 322
    
    ELSE tabla_vacunas_completo.codigo_esquema end as ID_VACUNA_ESQUEMA,
    
    --codigo_aplicacion
    CASE 
      when tabla_vacunas_completo.id_vacuna = 143 and (tabla_vacunas_completo.afitipocategoria=6) then '5' 
      when tabla_vacunas_completo.id_vacuna = 143 then '3' 
      
      when tabla_vacunas_completo.id_vacuna = 134 --triple viral para la campaña oct-nov 2018
          and tabla_vacunas_completo.id_dosis = 7
          then '37'

      when tabla_vacunas_completo.id_vacuna = 141 and (tabla_vacunas_completo.afitipocategoria=6) then '5'--embarazadas
      when tabla_vacunas_completo.id_vacuna = 141 then '3'
      when tabla_vacunas_completo.id_vacuna = 188 and tabla_vacunas_completo.afitipocategoria=6 then '5'
      when tabla_vacunas_completo.id_vacuna = 188 and tabla_vacunas_completo.edad_meses <1 then '7' 
      when tabla_vacunas_completo.id_vacuna = 188 then '16'
      when tabla_vacunas_completo.id_vacuna = 190 and tabla_vacunas_completo.afitipocategoria=6 then '5'
      when tabla_vacunas_completo.id_vacuna = 190 then '16'
     
      when tabla_vacunas_completo.codigo_esquema is NULL
        then 'IMPORTACION'
      else tabla_vacunas_completo.codigo_aplicacion::text end as ID_APLICACION_CONDICION

from (
SELECT DISTINCT ON (trazadoras.vacunas.id_smiafiliados,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac,trazadoras.vacunas.id_dosis_apli)
      trazadoras.vacunas.id_vacunas as ID, 
      trazadoras.vacunas.id_vac_apli,
      trazadoras.vacunas.id_dosis_apli,
      trazadoras.vacunas.cuie,
      trazadoras.vacunas.id_smiafiliados,
      smi.afidni,
      smi.afinombre,
      smi.afiapellido,
      smi.afitipodoc as ID_TIPODOC,
      --case when nacer.smiafiliados.afitipodoc='DNI' then 1
      --  when nacer.smiafiliados.afitipodoc='LC' then 2
        --when nacer.smiafiliados.afitipodoc='LE' then 3
        --when nacer.smiafiliados.afitipodoc='CI' then 4
        --when nacer.smiafiliados.afitipodoc='DE' then 5
        --when nacer.smiafiliados.afitipodoc='DNIF' then 6
        --when nacer.smiafiliados.afitipodoc='DNIM' then 7
        --when nacer.smiafiliados.afitipodoc='CM' then 8
        --when nacer.smiafiliados.afitipodoc='IND' then 9
        --else 1 end::integer as ID_TIPODOC,
      smi.afisexo::character(1) as SEXO,
      smi.afitipocategoria,
      to_char (smi.afifechanac,'dd/MM/yyyy') as FECHA_NACIMIENTO,
      smi.".'"maTipoDocumento"'." as ID_TIPODOC_MADRE,
        --case when nacer.smiafiliados.".'"maTipoDocumento"'."='DNI' then 1
        --when nacer.smiafiliados.".'"maTipoDocumento"'."='LC' then 2
        --when nacer.smiafiliados.".'"maTipoDocumento"'."='LE' then 3
        --when nacer.smiafiliados.".'"maTipoDocumento"'."='CI' then 4
        --when nacer.smiafiliados.".'"maTipoDocumento"'."='DE' then 5
        --when nacer.smiafiliados.".'"maTipoDocumento"'."='DNIF' then 6
        --when nacer.smiafiliados.".'"maTipoDocumento"'."='DNIM' then 7
        --when nacer.smiafiliados.".'"maTipoDocumento"'."='CM' then 8
        --when nacer.smiafiliados.".'"maTipoDocumento"'."='IND' then 9
        --else 1 end::integer as ID_TIPODOC_MADRE,
      smi.manrodocumento as NRODOC_MADRE,
      200 as ID_PAIS,
      200 as ID_NACIONALIDAD,
      19 as ID_PROVINCIA_NACIMIENTO,
      --'' as ID_LOCALIDAD_NACIMIENTO, DATO NO OBLIGATORIO
      'NO'::text as INDOCUMENTADO, --dato no obligatorio
      'NO' as SE_DECLARA_PUEBLO_INDIGENA,
      19 as ID_PROVINCIA_DOMICILIO,
      uad.localidades.cod_sissa as ID_LOCALIDAD_DOMICILIO,
      
      --nuevo para departamentos
        case when upper(afidomdepartamento)=' SELECCIONE DEPARTAMENTO' then 'LA CAPITAL'
          when afidomdepartamento is null then 'LA CAPITAL'
          when afidomdepartamento='' then 'LA CAPITAL'
          when upper(afidomdepartamento)='<FONT><FONT>LA CAPITAL</FONT></FONT>' then 'LA CAPITAL'
          when upper(afidomdepartamento)='SELECCIONE DEPARTAMENTO' then 'LA CAPITAL'
          when upper(afidomdepartamento)='JUNíN' then 'JUNIN'
          when upper(afidomdepartamento)='JUNÃ­N' then 'JUNIN'
          when upper(afidomdepartamento)='PRINGLES' then 'CORONEL PRINGLES'
          when upper(afidomdepartamento)='SAN MARTIN' then 'LIBERTADOR GENERAL SAN MARTIN'
          else upper(afidomdepartamento) end as afidomdepartamento_fix,
      --end nuevos para departamentos 
      
      uad.departamentos.codigodepartamento as ID_DEPARTAMENTO_DOMICILIO,
      smi.afidomlocalidad,
      smi.".'"afiDomCP"'." as CP_DOMICILIO,
      smi.".'"afiDomCalle"'." as CALLE,
      smi.".'"afiDomNro"' ."as CALLE_NRO,
      smi.".'"afiDomPiso"' ."as CALLE_PISO,
      null as ID_TIPO_NF, 
      'IMPORTACION' as ID_TIPO_VACUNA,
      trazadoras.vac_apli.nomivac as  ID_VACUNA,
      
      CASE WHEN ((trazadoras.vacunas.id_vac_apli=18 or trazadoras.vacunas.id_vac_apli=19) and (trazadoras.vacunas.id_dosis_apli=6 or trazadoras.vacunas.id_dosis_apli=5 or trazadoras.vacunas.id_dosis_apli=4))  THEN 1
        WHEN trazadoras.vacunas.id_vac_apli=28 THEN 1
        WHEN trazadoras.vacunas.id_dosis_apli=6 THEN 1
        ELSE trazadoras.vacunas.id_dosis_apli end::integer as ID_DOSIS,
      
      nacer.efe_conv.cod_siisa as ID_ORIGEN,
      to_char (trazadoras.vacunas.fecha_vac,'dd/MM/yyyy') as FECHA_APLICACION,
      case when (trazadoras.vacunas.lote='' or trazadoras.vacunas.lote is null) then '00' else upper (trazadoras.vacunas.lote) end as LOTE,
      
      trazadoras.vac_apli.*,
      (extract (year from age (trazadoras.vacunas.fecha_vac,smi.afifechanac))*12) + 
      extract (month from age (trazadoras.vacunas.fecha_vac,smi.afifechanac)) as edad_meses,

      --nacer.smiafiliados.afidomlocalidad as ID_LOCALIDAD_DOMICILIO,
      uad.localidades.nombre as ID_LOCALIDAD_DOMICILIO_
   from trazadoras.vacunas 

inner join (select *,
  --nuevo para departamentos
  case when upper(afidomdepartamento)=' SELECCIONE DEPARTAMENTO' then 'LA CAPITAL'
          when afidomdepartamento is null then 'LA CAPITAL'
          when afidomdepartamento='' then 'LA CAPITAL'
          when upper(afidomdepartamento)='<FONT><FONT>LA CAPITAL</FONT></FONT>' then 'LA CAPITAL'
          when upper(afidomdepartamento)='SELECCIONE DEPARTAMENTO' then 'LA CAPITAL'
          when upper(afidomdepartamento)='JUNíN' then 'JUNIN'
          when upper(afidomdepartamento)='JUNÃ­N' then 'JUNIN'
          when upper(afidomdepartamento)='PRINGLES' then 'CORONEL PRINGLES'
          when upper(afidomdepartamento)='SAN MARTIN' then 'LIBERTADOR GENERAL SAN MARTIN'
          else upper(afidomdepartamento) end as afidomdepartamento_fix,
      --end nuevos para departamentos 
  
  --nuevo para localidades
  case when afidomlocalidad='' then 'SAN LUIS'
when afidomlocalidad is NULL then 'SAN LUIS'
when upper(afidomlocalidad)='CAPITAL FEDERAL' then 'SAN LUIS'
when upper(afidomlocalidad)='SELECCIONE LOCALIDAD' then 'SAN LUIS'
when upper(afidomlocalidad)='LA CAPITAL' then 'SAN LUIS'
when upper(afidomlocalidad)='ALEM' then 'LEANDRO N ALEM'
when upper(afidomlocalidad)='BALDE - CAPITAL' then 'BALDE'
when upper(afidomlocalidad)='BALDE DE ESCUDERO - JUNIN' then 'BALDE DE ESCUDERO'
when upper(afidomlocalidad)='BALDE DE ESCUDERO - JUNÃ­N' then 'BALDE DE ESCUDERO'
when upper(afidomlocalidad)='BALDE ESCUDERO' then 'BALDE DE ESCUDERO'
when upper(afidomlocalidad)='CALERAS CAÃ±ADA GRANDE' then 'CALERAS CAÑADA GRANDE'
when upper(afidomlocalidad)='CALERAS CAñADA GRANDE' then 'CALERAS CAÑADA GRANDE'
when upper(afidomlocalidad)='AYACUCHO' then 'CANDELARIA'
when upper(afidomlocalidad)='BELGRANO' then 'LA CALERA'
when upper(afidomlocalidad)='JUNIN' then 'CARPINTERIA'
when upper(afidomlocalidad)='JUNÃ­N' then 'CARPINTERIA'
when upper(afidomlocalidad)='LA AGUADA - AYACUCHO' then 'LAS AGUADAS'
when upper(afidomlocalidad)='CASA DE PIEDRA - SAN MARTÃ­N' then 'SAN MARTIN'
when upper(afidomlocalidad)='GENERAL SAN MARTIN' then 'SAN MARTIN'
when upper(afidomlocalidad)='CAÃ±ADA HONDA' then 'CAÑADA HONDA'
when upper(afidomlocalidad)='CAÃ±ADA HONDA DE GUZMÃ¡N' then 'CAÑADA HONDA'
when upper(afidomlocalidad)='CAñADA HONDA' then 'CAÑADA HONDA'
when upper(afidomlocalidad)='CAñADA HONDA DE GUZMAN' then 'CAÑADA HONDA'
when upper(afidomlocalidad)='CHACABUCO' then 'CONCARAN'
when upper(afidomlocalidad)='CONCARÃ¡N' then 'CONCARAN'
when upper(afidomlocalidad)='CONLARA' then 'SANTA ROSA DEL CONLARA'
when upper(afidomlocalidad)='CORTADERA' then 'CORTADERAS'
when upper(afidomlocalidad)='CORTADERAS - CAPITAL' then 'CORTADERAS'
when upper(afidomlocalidad)='CORTADERAS - CHACABUCO' then 'CORTADERAS'
when upper(afidomlocalidad)='CARPINTERÃ­A' then 'CARPINTERIA'
when upper(afidomlocalidad)='LA CAÃ±ADA' then 'CALERAS CAÑADA GRANDE'
when upper(afidomlocalidad)='LA CAñADA' then 'CALERAS CAÑADA GRANDE'
when upper(afidomlocalidad)='GOBERNADOR DUPUY' then 'ANCHORENA'
when upper(afidomlocalidad)='LA FLORIDA - AYACUCHO' then 'LA FLORIDA'
when upper(afidomlocalidad)='LA FLORIDA - BELGRANO' then 'LA FLORIDA'
when upper(afidomlocalidad)='LA FLORIDA - PRINGLES' then 'LA FLORIDA'
when upper(afidomlocalidad)='EMBALSE LA FLORIDA' then 'LA FLORIDA'
when upper(afidomlocalidad)='EL CALDEN - AYACUCHO' then 'EL CALDÉN'
when upper(afidomlocalidad)='EL CALDÃ©N - AYACUCHO' then 'EL CALDÉN'
when upper(afidomlocalidad)='FORTÃ­N EL PATRIA' then 'FORTIN DEL PATRIA'
when upper(afidomlocalidad)='LA VERTIENTE - SAN MARTIN' then 'LA VERTIENTE'
when upper(afidomlocalidad)='LA VERTIENTE - SAN MARTÃ­N' then 'LA VERTIENTE'
when upper(afidomlocalidad)='EL CAZADOR' then 'CAZADOR'
when upper(afidomlocalidad)='EL DESAGUADERO' then 'DESAGUADERO'
when upper(afidomlocalidad)='EL RINCON - AYACUCHO' then 'EL RINCÓN'
when upper(afidomlocalidad)='EL RINCÃ³N - AYACUCHO' then 'EL RINCÓN'
when upper(afidomlocalidad)='EL RINCÃ³N - JUNÃ­N' then 'EL RINCÓN'
when upper(afidomlocalidad)='EL SUYUQUE NUEVO' then 'SUYUQUE'
when upper(afidomlocalidad)='EL VOLCÃ¡N' then 'EL VOLCAN'
when upper(afidomlocalidad)='GENERAL PEDERNERA' then 'VILLA MERCEDES'
when upper(afidomlocalidad)='ISLA - JUNIN' then 'BALDE DE LA ISLA'
when upper(afidomlocalidad)='ISLA - JUNÃ­N' then 'BALDE DE LA ISLA'
when upper(afidomlocalidad)='LA BOTIJA' then 'BOTIJAS'
when upper(afidomlocalidad)='CUATRO ESQUINAS - PRINGLES' then 'CUATRO ESQUINAS'
when upper(afidomlocalidad)='EL DESAGÃ¼ADERO' then 'DESAGUADERO'
when upper(afidomlocalidad)='EL RAMBLON' then 'EL RAMBLÓN'
when upper(afidomlocalidad)='LA BAJADA - PRINGLES' then 'LA BAJADA'
when upper(afidomlocalidad)='LA ESPERANZA - DUPUY' then 'BUENA ESPERANZA'
when upper(afidomlocalidad)='LA PLATA - SAN LUIS' then 'LA PUNTA'
when upper(afidomlocalidad)='SAN FRANCISO DEL MONTE DE ORO' then 'SAN FRANCISCO DEL MONTE DE ORO'
when upper(afidomlocalidad)='SAN FRANCISCO' then 'SAN FRANCISCO DEL MONTE DE ORO'
when upper(afidomlocalidad)='PROTERO DE LOS FUNES' then 'POTRERO DE LOS FUNES'
when upper(afidomlocalidad)='TRAPICHE' then 'EL TRAPICHE'
when upper(afidomlocalidad)='PAPAGAYOS' then 'PAPAGALLO'
when upper(afidomlocalidad)='SANTA ROSA' then 'SANTA ROSA DEL CONLARA'
when upper(afidomlocalidad)='SUYUQUE NUEVO' then 'SUYUQUE'
when upper(afidomlocalidad)='UNIÃ³N' then 'UNION'
when upper(afidomlocalidad)='STA. ROSA DEL CANTANTAL' then 'SANTA ROSA DE CANTANTAL'
when upper(afidomlocalidad)='RÃ­O GRANDE' then 'RIO GRANDE'

else upper(afidomlocalidad) end as afidomlocalidad_fix
--end nuevo localidades  


    from nacer.smiafiliados) as smi on trazadoras.vacunas.id_smiafiliados=smi.id_smiafiliados
inner join trazadoras.vac_apli on trazadoras.vacunas.id_vac_apli=trazadoras.vac_apli.id_vac_apli
inner join nacer.efe_conv on trazadoras.vacunas.cuie=nacer.efe_conv.cuie
--inner join uad.provincias on uad.provincias.id_provincia=12
inner join uad.departamentos on afidomdepartamento_fix=uad.departamentos.nombre
--inner join uad.departamentos on uad.provincias.id_provincia=uad.departamentos.nombre
inner join uad.localidades on afidomlocalidad_fix=uad.localidades.nombre

where fecha_vac between '$fecha_desde' and '$fecha_hasta' and trazadoras.vacunas.id_smiafiliados is not null and trazadoras.vacunas.id_beneficiarios=0 and eliminada<>1
    and (estado_envio is null or estado_envio='n')
) as tabla_vacunas_completo";


	$result1=sql($sql_tmp) or fin_pagina();
	$filename = 'nomivac_smiafiliados_'.$fecha_hasta.'.txt';	//cambiar nombre

	  	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
		//encabezado
$contenido="id_tipodoc;nrodoc;apellido;nombre;sexo;fecha_nacimiento;id_tipodoc_madre;nrodoc_madre;id_pais;id_nacionalidad;id_provincia_nacimiento;indocumentado;se_declara_pueblo_indigena;id_provincia_domicilio;id_localidad_domicilio;id_departamento_domicilio;cp_domicilio;calle;calle_nro;id_tipo_nf;id_tipo_vacuna;id_vacuna;id_dosis;id_origen;fecha_aplicacion;lote;id_vacuna_esquema;id_aplicacion_condicion;id_vinculo_a_cargo";		
$contenido=strtoupper($contenido);
$contenido.="\r";
$contenido.="\n";
if (fwrite($handle, $contenido) === FALSE) {
        		echo "No se Puede escribir  ($filename)";
        		exit;
    		}
		
$result1->movefirst();
  while (!$result1->EOF) {
    		
    	$contenido=$result1->fields['id_tipodoc'].";";
			$contenido.=$result1->fields['afidni'].";";
			$contenido.=trim(utf8_decode($result1->fields['afiapellido'])).";";
			$contenido.=trim(utf8_decode($result1->fields['afinombre'])).";";
			$contenido.=$result1->fields['sexo'].";";
			$contenido.=$result1->fields['fecha_nacimiento'].";";
			$contenido.=$result1->fields['id_tipodoc_madre'].";";
			$contenido.=$result1->fields['nrodoc_madre'].";";
      $contenido.=$result1->fields['id_pais'].";";
			$contenido.=$result1->fields['id_nacionalidad'].";";
			$contenido.=$result1->fields['id_provincia_nacimiento'].";";
      $contenido.=$result1->fields['indocumentado'].";";
      $contenido.=$result1->fields['se_declara_pueblo_indigena'].";";
      $contenido.=$result1->fields['id_provincia_domicilio'].";";
      $contenido.=$result1->fields['id_localidad_domicilio'].";";
      $contenido.=$result1->fields['id_departamento_domicilio'].";";
      $contenido.=$result1->fields['cp_domicilio'].";";
      $contenido.=utf8_decode($result1->fields['calle']).";";
      $contenido.=$result1->fields['calle_nro'].";";
      $contenido.=$result1->fields['id_tipo_nf'].";";
      $contenido.=$result1->fields['id_tipo_vacuna'].";";
			$contenido.=$result1->fields['id_vacuna'].";";
			$contenido.=$result1->fields['id_dosis'].";";
			$contenido.=$result1->fields['id_origen'].";";
			$contenido.=$result1->fields['fecha_aplicacion'].";";
			$contenido.=trim($result1->fields['lote']).";";
			if ($result1->fields['id_aplicacion_condicion']!='IMPORTACION') {
            if ($result1->fields['id_vacuna_esquema']!=NULL) {
               $contenido.=$result1->fields['id_vacuna_esquema'].";";
			         $contenido.=$result1->fields['id_aplicacion_condicion'];}
            else {
              $contenido.="IMPORTACION;";
              $contenido.="IMPORTACION;"; 
            };
          }
      else {
              $contenido.="IMPORTACION;";
              $contenido.="IMPORTACION;";};
			if ($result1->fields['nrodoc_madre']) $contenido.=";2";
      $contenido.=";\r";
    	$contenido.="\n";
    		if (fwrite($handle, $contenido) === FALSE) {
        		echo "No se Puede escribir  ($filename)";
        		exit;
    		}
			$id_vacunas=$result1->fields['id'];
			$sql_update="update trazadoras.vacunas set estado_envio='e' where id_vacunas='$id_vacunas'";
			$res_sql_update=sql($sql_update,"No se pudieron actualizar los datos trazadoras.vacunas");
    		$result1->MoveNext();
    	}
    	echo "El Archivo ($filename) se genero con exito";
    
    	fclose($handle);
      
  //ARCHIVO EXPORTADO PARA NOMIVAC DESDE LECHE.BENEFICIARIOS (no va mas)
  //SE EXPORTA DESDE UAD.BENEFICIAIRIOS
      
  $sql_tmp="SELECT *,'DNI' as id_tipodoc,

  --codigo esquema
   CASE WHEN tabla_vacunas_completo.id_vacuna = 143 
          and tabla_vacunas_completo.edad_meses >=6 
          and tabla_vacunas_completo.edad_meses <=24 
          and (tabla_vacunas_completo.id_dosis_apli=1 or tabla_vacunas_completo.id_dosis_apli=2) then 78 --anual 6 a 24 meses REGULAR
    when tabla_vacunas_completo.id_vacuna = 134 --triple viral para la campaña oct-nov 2018
          and tabla_vacunas_completo.id_dosis = 7
          then 356


    when tabla_vacunas_completo.id_vacuna = 143 
          and tabla_vacunas_completo.edad_meses >=780 then 181 --mayores de 65 años
              
    when tabla_vacunas_completo.id_vacuna = 175 
          and tabla_vacunas_completo.edad_meses >=228 then 285 --mayores de 25 años
    when tabla_vacunas_completo.id_vacuna = 175 
          and tabla_vacunas_completo.edad_meses <120 then 196 --menores de 10 años
          
    when tabla_vacunas_completo.id_vacuna = 162 
          and tabla_vacunas_completo.edad_meses >=108 
          and tabla_vacunas_completo.edad_meses <228 then 305 --adolescentes
    
    when tabla_vacunas_completo.id_vacuna = 186 
          and tabla_vacunas_completo.edad_meses <=1 then 269
    when tabla_vacunas_completo.id_vacuna = 186 then 277
    
    when tabla_vacunas_completo.id_vacuna = 169 
          and tabla_vacunas_completo.edad_meses >=300 then 169
    when tabla_vacunas_completo.id_vacuna = 169 then 170
          
    when tabla_vacunas_completo.id_vacuna = 127 and tabla_vacunas_completo.edad_meses <=1 then 63
    when tabla_vacunas_completo.id_vacuna = 127 and tabla_vacunas_completo.edad_meses >1 then 109
    
    when tabla_vacunas_completo.id_vacuna = 188 and tabla_vacunas_completo.edad_meses <1 then 273 --neonatos
    when tabla_vacunas_completo.id_vacuna = 188 then 272

    when tabla_vacunas_completo.id_vacuna = 190 then 275
    
    when tabla_vacunas_completo.id_vacuna = 141 then 293 

    WHEN tabla_vacunas_completo.id_vacuna = 191 
            and tabla_vacunas_completo.edad_meses < 6 then 321

    WHEN tabla_vacunas_completo.id_vacuna = 191 
            and tabla_vacunas_completo.edad_meses >=6 then 322
    
    ELSE tabla_vacunas_completo.codigo_esquema end as ID_VACUNA_ESQUEMA,
    
    --codigo_aplicacion
    CASE 
      when tabla_vacunas_completo.id_vacuna = 143 then '3' 

      when tabla_vacunas_completo.id_vacuna = 134 --triple viral para la campaña oct-nov 2018
          and tabla_vacunas_completo.id_dosis = 7
          then '37'

      when tabla_vacunas_completo.id_vacuna = 141 then '3'
      when tabla_vacunas_completo.id_vacuna = 188 and tabla_vacunas_completo.edad_meses <1 then '7' 
      when tabla_vacunas_completo.id_vacuna = 188 then '16'
      when tabla_vacunas_completo.id_vacuna = 190 then '16'
     
      when tabla_vacunas_completo.codigo_esquema is NULL
        then 'IMPORTACION'
      else tabla_vacunas_completo.codigo_aplicacion::text end as ID_APLICACION_CONDICION

from (
SELECT distinct on (trazadoras.vacunas.id_beneficiarios,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac,trazadoras.vacunas.id_dosis_apli) 
  trazadoras.vacunas.id_vacunas as ID,
  trazadoras.vacunas.id_vac_apli,
  trazadoras.vacunas.id_dosis_apli,
  trazadoras.vacunas.fecha_vac,
  trazadoras.vacunas.cuie,
  trazadoras.vacunas.id_beneficiarios,
  nacer.efe_conv.cod_siisa,
  uad.beneficiarios.numero_doc as nrodoc,
  upper (uad.beneficiarios.nombre_benef) as afinombre,
  upper (uad.beneficiarios.apellido_benef) as apellido,
  'DNI' as afitipodoc,
  uad.beneficiarios.sexo,
  to_char (uad.beneficiarios.fecha_nacimiento_benef,'dd/MM/yyyy') as FECHA_NACIMIENTO,
  '' as id_tipodoc_madre,
  '' as nrodoc_madre,
  200 as ID_PAIS,
  200 as ID_NACIONALIDAD,
  19 as ID_PROVINCIA_NACIMIENTO,
  19 as ID_PROVINCIA_DOMICILIO,
  74056150 as ID_LOCALIDAD_DOMICILIO, --codigo sissa de San Luis
  438 as id_departamento_domicilio,
  'NO' as indocumentado,
  'NO' as se_declara_pueblo_indigena,
  'San Luis' as afidomlocalidad,
  '5700' as cp_domicilio,
  uad.beneficiarios.calle as calle,
  '' as calle_nro,
  null as ID_TIPO_NF, 
  'IMPORTACION' as ID_TIPO_VACUNA,
  trazadoras.vac_apli.nomivac as  ID_VACUNA,
  
  CASE WHEN ((trazadoras.vacunas.id_vac_apli=18 or trazadoras.vacunas.id_vac_apli=19) and (trazadoras.vacunas.id_dosis_apli=6 or trazadoras.vacunas.id_dosis_apli=5 or trazadoras.vacunas.id_dosis_apli=4))  THEN 1
       WHEN trazadoras.vacunas.id_vac_apli=28 THEN 1
       WHEN trazadoras.vacunas.id_dosis_apli=6 THEN 1
       ELSE trazadoras.vacunas.id_dosis_apli end::integer as ID_DOSIS,
  
  nacer.efe_conv.cod_siisa as ID_ORIGEN,
  to_char (trazadoras.vacunas.fecha_vac,'dd/MM/yyyy') as FECHA_APLICACION,
  trazadoras.vac_apli.*,
  (extract (year from age (trazadoras.vacunas.fecha_vac,uad.beneficiarios.fecha_nacimiento_benef))*12) + 
  extract (month from age (trazadoras.vacunas.fecha_vac,uad.beneficiarios.fecha_nacimiento_benef)) as edad_meses,
  case when (trazadoras.vacunas.lote='' or trazadoras.vacunas.lote is null) then '00' else upper (trazadoras.vacunas.lote) end as LOTE
  
from trazadoras.vacunas 

--inner join leche.beneficiarios on trazadoras.vacunas.id_beneficiarios=beneficiarios.id_beneficiarios
inner join uad.beneficiarios on trazadoras.vacunas.id_beneficiarios=beneficiarios.id_beneficiarios
inner join trazadoras.vac_apli on trazadoras.vacunas.id_vac_apli=trazadoras.vac_apli.id_vac_apli
inner join nacer.efe_conv on trazadoras.vacunas.cuie=nacer.efe_conv.cuie

inner join uad.provincias on uad.provincias.id_provincia=12
inner join uad.departamentos on uad.provincias.id_provincia=uad.departamentos.id_provincia
inner join uad.localidades on uad.departamentos.id_departamento=uad.localidades.id_departamento



where fecha_vac between '$fecha_desde' and '$fecha_hasta' and trazadoras.vacunas.id_beneficiarios is not null and trazadoras.vacunas.id_smiafiliados=0 and eliminada<>1
and (vacunas.estado_envio is null or vacunas.estado_envio='n')

) as tabla_vacunas_completo";

$result1=sql($sql_tmp) or fin_pagina();
$filename = 'nomivac_beneficiarios_'.$fecha_hasta.'.txt';  //cambiar nombre

if (!$handle = fopen($filename, 'a')) {
    echo "No se Puede abrir ($filename)";
   exit;
    }
    //encabezado
$contenido="id_tipodoc;nrodoc;apellido;nombre;sexo;fecha_nacimiento;id_tipodoc_madre;nrodoc_madre;id_pais;id_nacionalidad;id_provincia_nacimiento;indocumentado;se_declara_pueblo_indigena;id_provincia_domicilio;id_localidad_domicilio;id_departamento_domicilio;cp_domicilio;calle;calle_nro;id_tipo_nf;id_tipo_vacuna;id_vacuna;id_dosis;id_origen;fecha_aplicacion;lote;id_vacuna_esquema;id_aplicacion_condicion;id_vinculo_a_cargo";
$contenido=strtoupper($contenido);
$contenido.="\r";
$contenido.="\n";
if (fwrite($handle, $contenido) === FALSE) {
       echo "No se Puede escribir  ($filename)";
       exit;
        }
    
  $result1->movefirst();
  while (!$result1->EOF) {
        
  $contenido=$result1->fields['id_tipodoc'].";";
  $contenido.=$result1->fields['nrodoc'].";";
  $contenido.=trim(strtoupper(utf8_decode($result1->fields['apellido']))).";";
  $contenido.=trim(strtoupper(utf8_decode($result1->fields['afinombre']))).";";
  $contenido.=$result1->fields['sexo'].";";
  $contenido.=$result1->fields['fecha_nacimiento'].";";
  $contenido.=$result1->fields['id_tipodoc_madre'].";";
  $contenido.=$result1->fields['nrodoc_madre'].";";
  $contenido.=$result1->fields['id_pais'].";";
  $contenido.=$result1->fields['id_nacionalidad'].";";
  $contenido.=$result1->fields['id_provincia_nacimiento'].";";
  $contenido.=$result1->fields['indocumentado'].";";
  $contenido.=$result1->fields['se_declara_pueblo_indigena'].";";
  $contenido.=$result1->fields['id_provincia_domicilio'].";";
  $contenido.=$result1->fields['id_localidad_domicilio'].";";
  $contenido.=$result1->fields['id_departamento_domicilio'].";";
  $contenido.=$result1->fields['cp_domicilio'].";";
  $contenido.=trim(strtoupper(utf8_decode(str_replace(':','.',$result1->fields['calle'])))).";";
  $contenido.=str_replace(':','.',$result1->fields['calle_nro']).";";
  $contenido.=$result1->fields['id_tipo_nf'].";";
  $contenido.=$result1->fields['id_tipo_vacuna'].";";
  $contenido.=$result1->fields['id_vacuna'].";";
  $contenido.=$result1->fields['id_dosis'].";";
  $contenido.=$result1->fields['id_origen'].";";
  $contenido.=$result1->fields['fecha_aplicacion'].";";
  $contenido.=trim($result1->fields['lote']).";";
  if ($result1->fields['id_aplicacion_condicion']!='IMPORTACION') {
            if ($result1->fields['id_vacuna_esquema']!=NULL) {
               $contenido.=$result1->fields['id_vacuna_esquema'].";";
               $contenido.=$result1->fields['id_aplicacion_condicion'];}
            else {
              $contenido.="IMPORTACION;";
              $contenido.="IMPORTACION;"; 
            };
          }
      else {
              $contenido.="IMPORTACION;";
              $contenido.="IMPORTACION;";};
  $contenido.=";";//id_vinculo_a_cargo
  $contenido.="\r";
  $contenido.="\n";
  
  if (fwrite($handle, $contenido) === FALSE) {
         echo "No se Puede escribir  ($filename)";
         exit;
        }
  $id_vacunas=$result1->fields['id'];
  $sql_update="update trazadoras.vacunas set estado_envio='e' where id_vacunas='$id_vacunas'";
  $res_sql_update=sql($sql_update,"No se pudieron actualizar los datos trazadoras.vacunas");
  $result1->MoveNext();
      }
  echo "El Archivo ($filename) se genero con exito";
    
  fclose($handle);
	
}

if ($_POST['generarupdate']){
	
	//para generar script de exportacion de datos desde postgres a SQLserver
	//desde el uad.beneficiarios
	
	$sql_tmp="select * from uad.beneficiarios where tipo_transaccion='M' and estado_envio='e'";
	//$sql_tmp="select * from uad.beneficiarios where id_categoria=1 and fecha_diagnostico_embarazo between '2011-01-01' and '2011-12-31'";
	$result1=sql($sql_tmp);
	$filename = 'update.txt';	

	  	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}

    	$result1->movefirst();
    	while (!$result1->EOF) {
    		$contenido="UPDATE SMIAfiliados SET ";
    		$contenido.="afiApellido="."'".$result1->fields['apellido_benef']."'".", ";
    		$contenido.="afiNombre="."'".$result1->fields['nombre_benef']."'".", ";
    		$contenido.="afiTipoDoc="."'".$result1->fields['tipo_documento']."'".", ";
    		$contenido.="afiClaseDoc="."'".$result1->fields['clase_documento_benef']."'".", ";
    		$contenido.="afiDNI="."'".$result1->fields['numero_doc']."'".", ";
    		$contenido.="afiSexo="."'".$result1->fields['sexo']."'".", ";
    		$contenido.="afiProvincia="."'".$result1->fields['provincia_nac']."'".", ";
    		$contenido.="afiLocalidad="."'".$result1->fields['localidad_nac']."'".", ";
    		$contenido.="afiTipoCategoria="."'".$result1->fields['id_categoria']."'".", ";
    		$contenido.="afiFechaNac="."'".Fecha($result1->fields['fecha_nacimiento_benef'])."'".", ";
    		$contenido.="afiDeclaraIndigena="."'".$result1->fields['indigena']."'".", ";
    		$contenido.="afiId_Lengua="."'".$result1->fields['id_lengua']."'".", ";
    		$contenido.="afiId_Tribu="."'".$result1->fields['id_tribu']."'".", ";
    		$contenido.="maTipoDocumento="."'".$result1->fields['tipo_doc_madre']."'".", ";
    		$contenido.="maNroDocumento="."'".$result1->fields['nro_doc_madre']."'".", ";
    		$contenido.="maApellido="."'".$result1->fields['apellido_madre']."'".", ";
    		$contenido.="maNombre="."'".$result1->fields['nombre_madre']."'".", ";
    		$contenido.="paTipoDocumento="."'".$result1->fields['tipo_doc_padre']."'".", ";
    		$contenido.="paNroDocumento="."'".$result1->fields['nro_doc_padre']."'".", ";
    		$contenido.="paApellido="."'".$result1->fields['apellido_padre']."'".", ";
    		$contenido.="paNombre="."'".$result1->fields['nombre_padre']."'".", ";
    		$contenido.="OtroTipoDocumento="."'".$result1->fields['tipo_doc_tutor']."'".", ";
    		$contenido.="OtroNroDocumento="."'".$result1->fields['nro_doc_tutor']."'".", ";
    		$contenido.="OtroApellido="."'".$result1->fields['apellido_tutor']."'".", ";
    		$contenido.="OtroNombre="."'".$result1->fields['nombre_tutor']."'".", ";
    		$contenido.="FechaInscripcion="."'".Fecha($result1->fields['fecha_inscripcion'])."'".", ";
    		$contenido.="FechaDiagnosticoEmbarazo="."'".Fecha($result1->fields['fecha_diagnostico_embarazo'])."'".", ";
    		$contenido.="SemanasEmbarazo="."'".$result1->fields['semanas_embarazo']."'".", ";
    		$contenido.="FechaProbableParto="."'".Fecha($result1->fields['fecha_probable_parto'])."'".", ";
    		$contenido.="FechaEfectivaParto="."'".Fecha($result1->fields['fecha_efectiva_parto'])."'".", ";
    		$contenido.="Activo="."'".$result1->fields['activo']."'".", ";
    		$contenido.="afiDomCalle="."'".$result1->fields['calle']."'".", ";
    		$contenido.="afiDomNro="."'".$result1->fields['numero_calle']."'".", ";
    		$contenido.="afiDomManzana="."'".$result1->fields['manzana']."'".", ";
    		$contenido.="afiDomPiso="."'".$result1->fields['piso']."'".", ";
    		$contenido.="afiDomDepto="."'".$result1->fields['dpto']."'".", ";
    		$contenido.="afiDomEntreCalle1="."'".$result1->fields['entre_calle_1']."'".", ";
    		$contenido.="afiDomEntreCalle2="."'".$result1->fields['entre_calle_2']."'".", ";
    		$contenido.="afiDomBarrioParaje="."'".$result1->fields['barrio']."'".", ";
    		$contenido.="afiDomMunicipio="."'".$result1->fields['municipio']."'".", ";
    		$contenido.="afiDomDepartamento="."'".$result1->fields['departamento']."'".", ";
    		$contenido.="afiDomLocalidad="."'".$result1->fields['localidad']."'".", ";
    		$contenido.="afiDomProvincia="."'12'".", ";
    		$contenido.="afiDomCP="."'".$result1->fields['cod_pos']."'".", ";
    		$contenido.="afiTelefono="."'".$result1->fields['telefono']."'".", ";
    		$contenido.="FechaCarga="."'".Fecha($result1->fields['fecha_carga'])."'".", ";
    		$contenido.="UsuarioCreacion="."'".$result1->fields['usuario_carga']."'".", ";
    		$contenido.="MenorConviveConTutor="."'".$result1->fields['menor_convive_con_adulto']."'".", ";
    		$contenido.="CUIEEfectorAsignado="."'".$result1->fields['cuie_ea']."'".", ";
    		$contenido.="CUIELugarAtencionHabitual="."'".$result1->fields['cuie_ah']."'";
    		
    		$contenido.="WHERE ClaveBeneficiario=".$result1->fields['clave_beneficiario']."; ";
    		
    		$contenido.="\n";
    		if (fwrite($handle, $contenido) === FALSE) {
        		echo "No se Puede escribir  ($filename)";
        		exit;
    		}   		
    		$result1->MoveNext();
    	}
    	echo "El Archivo ($filename) se genero con exito";
    
    	fclose($handle);
	
}

/*if ($_POST['importarc']){
	//...........................para subir el archivo c................
	    $path = MOD_DIR."/nacer/archivos/";
		$name = $_FILES["archivo"]["name"];		
		$temp = $_FILES["archivo"]["tmp_name"];
		$size = $_FILES["archivo"]["size"];
		$type = $_FILES["archivo"]["type"];
		$extensiones = array("txt");
		if ($name) {
			$name = strtolower($name);
			$ext = substr($name,-3);
			if ($ext != "txt") {
				Error("El formato del archivo debe ser TXT");
			}
			$name = "$name";
			$ret = FileUpload($temp,$size,$name,$type,$max_file_size,$path,"",$extensiones,"",1,0);
			if ($ret["error"] != 0) {
				Error("No se pudo subir el archivo");
			}
		}
  
		$filename = MOD_DIR."/nacer/archivos/".$name;	

	  	if (!$handle = fopen($filename, 'r')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
    	$fecha_mig=date("Y-m-d");
    	$buffer = stream_get_line($handle, 1024, "\n"); 
    	$periodo_c=substr($buffer,-6,-1);//funciona bien no tocar
    	$periodo_c='2'.$periodo_c;
    	$cont=0;
    
    	while (!feof($handle)) {
    		$buffer = stream_get_line($handle, 1024, "\n");
    		$buffer=ereg_replace("'",null,$buffer);
    		$buffer=ereg_replace('"',null,$buffer);
	        $buffer_array=explode(chr(9),$buffer);
	      	list($a,$b,$c,$d,$f,$g,$h,$i,$j,$k,$l)=$buffer_array;
	      	

	        $q="select nextval('nacer.historico_c_id_historico_c_seq') as id_historico_c";
		    $id_historico_c=sql($q) or fin_pagina();
		    $id_historico_c=$id_historico_c->fields['id_historico_c'];    
      
   			$sql_tmp="INSERT INTO nacer.historico_c
        			(id_historico_c,clave_beneficiario,ape_nom,activo,motivo_baja,fecha_migra,periodo,nom_archivo,cod_baja)
        			VALUES
        			('$id_historico_c', '$b','$f','$h','$j','$fecha_mig','$periodo_c','$filename','$i')";
			sql($sql_tmp);        
	        $cont++;
    	}
		 	
    	echo "Se exportaron $cont Registros correspondientes al Periodo $periodo_c";
    	fclose($handle);
	
} */   



if ($_POST['importapuco']){
	$filename = 'puco.txt';	

	  	if (!$handle = fopen($filename, 'r')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
    
    $filename1 = 'C:\\puco_ok.txt';
    	if (!$handle1 = fopen($filename1, 'w+')) {
        	 echo "No se Puede abrir ($filename1)";
         	exit;
    	}
    	
    	while (!feof($handle)) {
        $buffer = fgets($handle, 61);
        $a=substr($buffer,3,8);
        $b=substr($buffer,0,3);
        $c=substr($buffer,15,6);
        $d=substr($buffer,22,40);       
        
       $contenido="";
       $contenido.=trim($b);
       $contenido.=chr(9);
       $contenido.=ereg_replace('[^ A-Za-z0-9_-]','',trim($d));
       $contenido.=chr(9);
       $contenido.=ereg_replace('[^ A-Za-z0-9_-]','',trim($c));
	   $contenido.=chr(9);
	   $contenido.=trim($a);     
       $contenido.="\n";
    		if (fwrite($handle1, $contenido) === FALSE) {
        		echo "No se Puede escribir  ($filename1)";
        		exit;
    		}
          
       }
		 	
    	echo "Se Genero c:\poco_ok.txt";
    	fclose($handle);
    	fclose($handle1);
	
}

if ($_POST['importapucocompleto']){
    $filename = 'puco.txt'; 

        if (!$handle = fopen($filename, 'r')) {
             echo "No se Puede abrir ($filename)";
            exit;
        }
        
        $sql_tmp="truncate table puco.puco;";
        sql($sql_tmp); 

        $sql_tmp="DROP INDEX puco.doc_i;";
        sql($sql_tmp);

               
       while (!feof($handle)) {
        $buffer = fgets($handle, 61);
        $a=substr($buffer,3,8);
        $b=substr($buffer,0,3);
        $c=substr($buffer,15,6);
        $d=substr($buffer,22,40);       
        
       
        $b=trim($b);
        $d=ereg_replace('[^ A-Za-z0-9_-]','',trim($d));
        $c=ereg_replace('[^ A-Za-z0-9_-]','',trim($c));
        $a=trim($a);  

        $sql_tmp="INSERT INTO puco.puco
                    (tipo_doc,nombre,cod_os,documento)
                    VALUES
                    ('$b', '$d','$c','$a')";
        sql($sql_tmp); 
       }

        $sql_tmp="CREATE INDEX doc_i
                  ON puco.puco
                  USING btree
                  (documento)";
        sql($sql_tmp);

            
        echo "Se importo puco";
        fclose($handle);          
}

if ($_POST['generarsmiafiliados']){
	$sql_tmp="delete from nacer.smiafiliados";
	$result1=sql($sql_tmp);
	echo "Se elimino datos de la tabla smiafiliados <br>";
	$filename = 'smiafiliados.csv';	

	  	if (!$handle = fopen($filename, 'r')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
		
    	$cont=0;
    	while (!feof($handle)) {
        $buffer = fgets($handle, 8192);
        $buffer=ereg_replace(chr(9),null,$buffer);
        $buffer=ereg_replace("'",null,$buffer);
        $buffer=explode('"',$buffer);
        //print_r($buffer);
        list($a,$b,$c,$d,$f,$g,$h,$i,$j,$k,$l,$m,$n,$o,$p,$q,$r,$s,$t,$u,$v,$w,$x,$y,$z,$ab,$ac,$ad,$ae,$af,$ag,$ah,$ai,
        	 $aj,$ak,$al,$am,$an,$ao,$ap,$aq,$ar,$as,$at,$au,$av,$aw,$ax)=$buffer;
        $b= ereg_replace(',000000',null,$b);
        $w= ereg_replace(',000000',null,$w);
        $y= Fecha_db(ereg_replace(' 12:00 a.m.',null,$y)); if ($y=='') $y='1980-01-01';
        $ah= ereg_replace(',000000',null,$ah); 
        $al= Fecha_db(ereg_replace(' 12:00 a.m.',null,$al));if ($al=='') $al='1980-01-01';
        $an= Fecha_db(ereg_replace(' 12:00 a.m.',null,$an));if ($an=='') $an='1980-01-01';
		$ax= Fecha_db(ereg_replace(' 12:00 a.m.',null,$ax));if ($ax=='') $ax='1980-01-01';		
		$sql_tmp="INSERT INTO nacer.smiafiliados
        			(id_smiafiliados,clavebeneficiario,afiapellido,afinombre,afitipodoc,aficlasedoc,afidni,afisexo,afidomdepartamento,
  						afidomlocalidad,afitipocategoria,afifechanac,activo,cuieefectorasignado,cuielugaratencionhabitual,
  						motivobaja,mensajebaja,fechainscripcion,fechacarga,usuariocarga,manrodocumento,maapellido,manombre,fechadiagnosticoembarazo)
        			VALUES
        			($b,'$d','$g','$i','$k','$m','$o','$q','$s','$u',$w,'$y','$ab','$ad','$af',$ah,'$aj','$al',
        			'$an','$ap','$ar','$at','$av','$ax')";
		sql($sql_tmp);        
        $cont++;
    	}
		 	
    	echo "Se exportaron $cont Registros";
    	fclose($handle);
	
}

if ($_POST['generarsmiafiliadosaux']){
	$sql_tmp="delete from nacer.smiafiliadosaux";
	$result1=sql($sql_tmp);
	echo "Se elimino datos de la tabla smiafiliadosaux <br>";
	$filename = 'smiafiliadosaux.csv';	

	  	if (!$handle = fopen($filename, 'r')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
		
    	$cont=0;
    	while (!feof($handle)) {
        $buffer = fgets($handle, 8192);
        $buffer=ereg_replace(chr(9),null,$buffer);
        $buffer=ereg_replace("'",null,$buffer);
        $buffer=explode('"',$buffer);
        //print_r($buffer);
        list($a,$b,$c,$d)=$buffer;
        $d= ereg_replace(',000000',null,$d);
        $sql_tmp="INSERT INTO nacer.smiafiliadosaux
        			(clavebeneficiario,id_procesoingresoafiliados)
        			VALUES
        			('$b',$d)";
		sql($sql_tmp);        
        $cont++;
    	}
		 	
    	echo "Se exportaron $cont Registros";
    	fclose($handle);
	
}

if ($_POST['generarsmiefectores']){
	$sql_tmp="delete from facturacion.smiefectores";
	$result1=sql($sql_tmp);
	echo "Se elimino datos de la tabla smiefectores <br>";
	$filename = 'smiefectores.csv';	

	  	if (!$handle = fopen($filename, 'r')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
		
    	$cont=0;
    	while (!feof($handle)) {
        $buffer = fgets($handle, 8192);
        $buffer=ereg_replace(chr(9),null,$buffer);
        $buffer=ereg_replace("'",null,$buffer);
        $buffer=explode('"',$buffer);
        //print_r($buffer);
        list($a,$b,$c,$d,$f,$g,$h,$i,$j,$k)=$buffer;
        $sql_tmp="INSERT INTO facturacion.smiefectores
        			(cuie,tipoefector,nombreefector,direccion,localidadmunicipiopartido)
        			VALUES
        			('$b','$d','$g','$i','$k')";
		sql($sql_tmp);        
        $cont++;
    	}
		 	
    	echo "Se exportaron $cont Registros";
    	fclose($handle);
	
}

if ($_POST['generarsmiprocesoafiliados']){
	$sql_tmp="delete from nacer.smiprocesoafiliados";
	$result1=sql($sql_tmp);
	echo "Se elimino datos de la tabla smiprocesoafiliados <br>";
	$filename = 'smiprocesoafiliados.csv';	

	  	if (!$handle = fopen($filename, 'r')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
		
    	$cont=0;
    	while (!feof($handle)) {
        $buffer = fgets($handle, 8192);
        $buffer=ereg_replace(chr(9),null,$buffer);
        $buffer=ereg_replace("'",null,$buffer);
        $buffer=explode('"',$buffer);
        //print_r($buffer);
        list($a,$b,$c,$d,$f,$g)=$buffer;
        $b= ereg_replace(',000000',null,$b);
        
        $sql_tmp="INSERT INTO nacer.smiprocesoafiliados
        			(id_procafiliado,periodo,codigocialtadatos)
        			VALUES
        			($b,'$d','$g')";
		sql($sql_tmp);        
        $cont++;
    	}
		 	
    	echo "Se exportaron $cont Registros";
    	fclose($handle);
	
}

if ($_POST['generaexclusionduplicados']){
	$sql_tmp="select * from uad.beneficiarios left join nacer.smiafiliados on (uad.beneficiarios.numero_doc=nacer.smiafiliados.afidni)
			 where (estado_envio='n' and afidni IS NOT NULL and tipo_transaccion='A'and nacer.smiafiliados.activo='S')";
	$result1=sql($sql_tmp)or die;
	$cont=0;
	while (!$result1->EOF){
			$dni=$result1->fields['numero_doc'];
		    $update="UPDATE uad.beneficiarios SET estado_envio='e' where numero_doc='$dni'";	
		    sql($update) or die;
		    $cont++;
			$result1->MoveNext();
	};			
	echo "Se cambio el estado de $cont Registros Duplicados";
}

if ($_POST['migraciondeestadoactivo']){
	$sql_tmp="select clavebeneficiario,activo from nacer.smiafiliados";
	$result1=sql($sql_tmp)or die;
	$cont=0;
	while (!$result1->EOF){
			$clavebeneficiario=$result1->fields['clavebeneficiario'];
			$activo=$result1->fields['activo'];
			$update="UPDATE uad.beneficiarios SET activo='$activo' where clave_beneficiario='$clavebeneficiario'";	
		    sql($update) or die;
		    $cont++;
			$result1->MoveNext();
	};			
	echo "Se cambio el estado de activo de $cont Registros";
}


//codigo para corregir los beneficiarios con estado de envio que no estan en el A

if ($_POST['corregir']){
	$sql_tmp="select distinct clave_beneficiarios from uad.beneficiarios where
	beneficiarios.clave_beneficiarios not in (select clavebeneficiarios from nacer.smiafiliados)";
	$result1=sql($sql_tmp)or die;
	$cont=0;
	while (!$result1->EOF){
			$clavebeneficiario=$result1->fields['clave_beneficiario'];
			$estado_envio="n";
			$update="UPDATE uad.beneficiarios SET estado_envio='$estado_envio' where clave_beneficiario='$clavebeneficiario'";	
		    sql($update) or die;
		    $cont++;
			$result1->MoveNext();
	};			
	echo "Se cambio el estado de envio de $cont Registros";
}

echo $html_header;
?>
<form name=form1 action="genera_archivo.php" method=POST enctype="multipart/form-data">
<table width="80%" class="bordes" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
<tr><td>
  <table width=100% align="center" class="bordes">
  
	<tr id="mo" align="center">
    <td colspan="3" align="center">
    	<font size=+1><b>Importar Archivo</b></font>      	      
    </td>
   </tr>
   		<tr>	           
           <td align="center" colspan="3" id="ma">
            <b> Obra Social Provincial (DOSEP) </b>
           </td>
         </tr>
     <tr>
      <td align="right">		
	    <input type=submit name="generar" value='Genera Archivo OSP' style="width=250px" disabled>
	  </td>
	  <td align="right">		
	    <input type=submit name="generar_n" value='Genera Archivo OSP Nuevo' class="btn btn-success" style="width=250px">
	  </td>
	  
	  <td align="left">		
	    <font color="Red">Debe tener preparada con los datos correspondiente la tabla "public.dosep".</font>
	  </td>
     </tr> 
     
	 <?if ($usuario1!='sebastian') {$disable='disabled';}
      else {$disable='';};?>
	 
   <tr>	           
    <td align="center" colspan="3" id="ma">
    <b> NOMIVAC </b>
    </td>
    </tr>
  <tr>
       <td>
		<b>	
		Desde: <input type=text id=fecha_desde name=fecha_desde value='<?=$fecha_desde?>' size=15 readonly>
		<?=link_calendario("fecha_desde");?>
		</b>
		</td>
		
		<td>
		<b>
		Hasta: <input type=text id=fecha_hasta name=fecha_hasta value='<?=$fecha_hasta?>' size=15 readonly>
		<?=link_calendario("fecha_hasta");?> 
		</b>
		</td>
		   
	    <td>
	    &nbsp;&nbsp;&nbsp;
		<input type="submit" name="nomivac" value='Genera Archivo Nomivac' class="btn btn-warning" <?=$disable?>>
	    </td>
	 </tr>
	 
	 
	 <tr>	           
           <td align="center" colspan="3" id="ma">
            <b> Importacion Archivos Sistema</b>
           </td>
         </tr>
     <tr>
     
     
     <!-- <tr>
      <tr><td align="center">		
		<font size=1><b> Importacion del archivo C para su Historial</b></font>
	  </td></tr>
	   <td align="right">		
		<input name="archivo" type="file" style="width=250px" id="archivo">
	  </td>
	  <td align="left">		
	    <input type=submit name="importarc" value='Importar' style="width=100px" disabled>
	  </td>
     </tr>
     </tr>--> 

     <tr>
      <td align="right">		
	    <input type=submit name="importapuco" value='Importar PUCO' style="width=250px" disabled>
	  </td>
	   <td align="left">		
	    <font color="Red">Debe copiar archivo puco.txt a la carpeta "sistema\modulos\nacer". Genera Archivo puco_ok.txt para hacer proceso de importacion manual a la tabla puco.puco</font>
	  </td>
     </tr>
     <tr>
      <td align="right">        
        <input type=submit name="importapucocompleto" value='Importar PUCO Completo' style="width=250px" disabled>
      </td>
       <td align="left">        
        <font color="Red">Debe copiar archivo puco.txt a la carpeta "sistema\modulos\nacer". Este proceso hace la importacion directamente en la tabla puco.puco.</font>
      </td>
     </tr>
     <tr>
      <td align="right">		
	    <input type=submit name="generarsmiafiliados" value='Importar Smiafiliados' style="width=250px" disabled>
	  </td>
	   <td align="left">		
	    <font color="Red">Debe copiar archivo smiafiliados.csv a la carpeta "sistema\modulos\nacer"</font>
	  </td>
     </tr>
     <tr>
      <td align="right">		
	    <input type=submit name="generarsmiafiliadosaux" value='Importar Smiafiliadosaux' style="width=250px" disabled>
	  </td>
	 <td align="left">		
	    <font color="Red">Debe copiar archivo smiafiliadosaux.csv a la carpeta "sistema\modulos\nacer"</font>
	  </td>
     </tr>
     <tr>
      <td align="right">		
	    <input type=submit name="generarsmiefectores" value='Importar Smiefectores' style="width=250px" disabled>
	  </td>
	  <td align="left">		
	    <font color="Red">Debe copiar archivo smiefectores.csv a la carpeta "sistema\modulos\nacer"</font>
	  </td>
     </tr>
     <tr>
      <td align="right">	
	    <input type=submit name="generarsmiprocesoafiliados" value='Importar Smiprocesoafiliados' style="width=250px" disabled>
	  </td>
	  <td align="left">		
	    <font color="Red">Debe copiar archivo smiprocesoafiliados.csv a la carpeta "sistema\modulos\nacer"</font>
	  </td>
     </tr>
     <tr>
      <td align="right">	
	    <input type=submit name="generaexclusionduplicados" value='Depuracion Beneficiarios Duplicados' style="width=250px" disabled>
	  </td>
	  <td align="left">		
	    <font color="Red">Se generara una depuracion de los beneficiarios duplicados (uad.beneficiarios)"</font>
	  </td>
	  </tr>
    
    <tr>
      <td align="right">	
	    <input type=submit name="migraciondeestadoactivo" value='Cambia el estado de Activo en uad.beneficiarios' style="width=250px" disabled>
	  </td>
	  <td align="left">		
	    <font color="Red">Se cambia los estado de Activo dentro de la tabla uad.beneficiarios"</font>
	  </td>

    <tr>     
        <td align="right">  
          <input type="submit" name="importa_sip_web" value='Importar SIP WEB' title='Importa controles del sip web a facturacion, trazadoras y fichero GENERAR CADA 10 DIAS' style="width=350px" onclick="if (confirm('Va a Realizar Migracion ¿esta seguro? GENERAR CADA 10 DIAS'))return true; else return false;" class="btn btn-info" <?=$disable?>>
        </td>
        <td align="left">   
          <font color="Red">Importa controles del sip web a facturacion, trazadoras y fichero.  GENERAR CADA 30 DIAS</font>
        </td>    
     </tr>

     <tr>     
        <td align="right">  
          <input type="submit" name="importa_cronicos" value='Importar SEGUIMIENTOS-CRONICOS' title='Importa controles del seguimiento a facturacion, trazadoras y fichero GENERAR CADA 10 DIAS' style="width=350px" onclick="if (confirm('Va a Realizar Migracion ¿esta seguro? GENERAR CADA 10 DIAS'))return true; else return false;"  class="btn btn-info"<?=$disable?>>
        </td>
        <td align="left">   
          <font color="Red">Importa controles de Seguimientos a facturacion, trazadoras y fichero.  GENERAR CADA 30 DIAS</font>
        </td>    
     </tr>

     <tr>     
        <td align="right">  
          <input type="submit" name="importa_agentes_sanitarios" value='Importar F1 - Agentes Sanitarios' title='Importa Empadronamiento desde planilla de Agentes Sanitarios' style="width=350px" onclick="if (confirm('Va a Realizar Migracion ¿esta seguro? GENERAR CADA 10 DIAS'))return true; else return false;" class="btn btn-danger" <?=$disable?>>
        </td>
        <td align="left">   
          <font color="Red">Importa Empadronamiento desde Agentes Sanitarios.  GENERAR CADA 30 DIAS</font>
        </td>    
     </tr>
</table>

</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>