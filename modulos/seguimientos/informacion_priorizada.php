<?
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);



echo $html_header;
?>


<form name='form1' action='informacion_priorizada.php' method='POST'>
<table width="100%" border="1px"><tr align="center"><td>

<table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">    
      <tr align="center" id="sub_tabla">
      <td colspan=10><font size=3 color= red> <b>Informacion Priorizada </b></font>
      </td>
       
  <tr><td><table id="priorizado" width=90% align="center" class="bordes" style="border:thin groove">
      
      <tr>
        <td align="center"  border=1 bordercolor=#2C1701>
        <font size=2> <b>Concepto</b></font>
          </td>
          
        <td align="center"  border=1 bordercolor=#2C1701>
        <font size=2> <b>Numerador</b></font>
          </td>

        <td align="center"  border=1 bordercolor=#2C1701>
        <font size=2> <b>Denominador</b></font>
        </td>

        <td align="center"  border=1 bordercolor=#2C1701>
        <font size=2> <b>Valor</b></font>
          </td>       
      </tr>
      
      
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?> title="El indicador mide el porcentaje de beneficiarios a cargo del efector con Cobertura Efectiva Básica (CEB)">
        <font size=2> <b>1.1 - TASA DE COBERTURA EFECTIVA B&Aacute;SICA </b></font>
          </td>
        <?
        $numerador=$ceb_a+$ceb_b+$ceb_c+$ceb_d;
          
        $sql_ex="SELECT count(*) as cantidad from 
          (SELECT distinct id_smiafiliados 
          from nacer.smiafiliados 
          where  cuieefectorasignado='$cuie' or cuielugaratencionhabitual='$cuie'
          ) as cantidad";
        $res_sql_ex=sql($sql_ex,"No se puede calcular el denominador de CEB");
        $denominador=($res_sql_ex->fields['cantidad'])?$res_sql_ex->fields['cantidad']:0;


        //$denominador=$sql_res_metas->fields['ceb_ceroacinco']+$sql_res_metas->fields['ceb_seisanueve']+$sql_res_metas->fields['ceb_diezadiecinueve']+$sql_res_metas->fields['ceb_veinteasesentaycuatro'];
        if ($denominador)
          {$ceb_total=($numerador/$denominador)*100;}
          else $ceb_total=0?> 
        
        <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=$numerador?> </b></font>
           </td>
         
         <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=$denominador?> </b></font>
           </td>        
          
          <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=number_format($ceb_total,2,',','.')?> %</b></font>
           </td>
      </tr>

      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?> title="El indicador mide el porcentaje de niños de 6 a 9 años de edad beneficiarios a cargo del efector con Cobertura Efectiva Básica (CEB)">
        <font size=2> <b>1.2 - TASA DE COBERTURA EFECTIVA B&Aacute;SICA EN NI&Ntilde;OS DE 6-9 A&Ntilde;OS </b></font>
            </td>
        <?
        $numerador=$ceb_b;
        
        $sql_ex="SELECT count(*) as cantidad from  (
          select distinct id_smiafiliados 
          from nacer.smiafiliados 
          where (cuieefectorasignado='$cuie' or cuielugaratencionhabitual='$cuie' 
            or cuie_ceb='$cuie') and grupopoblacional='B'
          ) as cantidad";
        $res_sql_ex=sql($sql_ex,"No se puede calcular el denominador de la tasa de cobertura");
        $denominador=($res_sql_ex->fields['cantidad'])?$res_sql_ex->fields['cantidad']:0;

        if ($denominador) {$ceb_seis_a_nueve=($numerador/$denominador)*100;
          } else $ceb_seis_a_nueve=0;?>  
        
        <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=$numerador?> </b></font>
           </td>
         
         <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=$denominador?> </b></font>
           </td>
          
          <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=number_format($ceb_seis_a_nueve,2,',','.')?>% </b></font>
           </td>
      </tr>
      
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?> title="El indicador mide el cumplimiento de las actividades extramuro esperadas por cada efector">
        <font size=2> <b>1.3 - NIVEL ACTIVIDADES EXTRAMURO </b></font>
            </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b>-----</b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b>-----</b></font>
      </td>
        
      
      
      <?$sql_ex="SELECT count (*) as cantidad from (
            select id_comprobante from facturacion.prestacion 
            where id_nomenclador=1671 or id_nomenclador=2195 or id_nomenclador=1338 or id_nomenclador=1337) as t1
            left join facturacion.comprobante using (id_comprobante)
            where cuie='$cuie'";
      $res_sql_ex=sql($sql_ex,"No se puede calcula Nivel actividad extramuro");?>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=($res_sql_ex->fields['cantidad'])?$res_sql_ex->fields['cantidad']:0?></b></font>
      </td>
      </tr>
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?> title="El indicador mide el cumplimiento de las búsquedas activas de adolescentes para valoración integral y de embarazadas adolescentes por agente sanitario y/o personal de salud esperadas por cada efector">
        <font size=2> <b>1.4 - NIVEL DE BUSQUEDA ACTIVA DEL ADOLESCENTE </b></font>
            </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b>-----</b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b>-----</b></font>
      </td>
      
      <?$sql_ex="SELECT count (*) as cantidad from (
            select id_comprobante from facturacion.prestacion 
            where id_nomenclador=1988 or id_nomenclador=2081 or id_nomenclador=1257 or
            id_nomenclador=1989 or id_nomenclador=2080 or id_nomenclador=1260) as t1
            left join facturacion.comprobante using (id_comprobante)
            where cuie='$cuie'";
      $res_sql_ex=sql($sql_ex,"No se puede calculas busqueda activa en adolescentes");?>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=($res_sql_ex->fields['cantidad'])?$res_sql_ex->fields['cantidad']:0?></b></font>
      </td>
      
      </tr>
      
      <br/>
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?> title="El indicador mide el nivel de notificación de embarazos de alto riesgo a cargo del efector">
        <font size=2> <b>2.1 - TASA DE DETECCION DE FACTORES DE RIESGO EN EMBARAZO </b></font>
            </td>
      
      <?$sql_ex="SELECT count (*) as cantidad from (
            select distinct id_smiafiliados from facturacion.comprobante 
            inner join facturacion.prestacion on comprobante.id_comprobante=prestacion.id_comprobante
            where (prestacion.id_nomenclador=1658 or prestacion.id_nomenclador=2418 or prestacion.id_nomenclador=1647)
            and comprobante.cuie='$cuie'
            ) as cantidad";
      $res_sql_ex=sql($sql_ex,"No se puede calcular factores de riesgo en embarazadas");
      $numerador=$res_sql_ex->fields['cantidad'];
      
      $sql_emb="SELECT count (*) as cantidad from (
            select distinct afidni from nacer.smiafiliados 
            where (cuieefectorasignado='$cuie' or cuielugaratencionhabitual='$cuie' or cuie_ceb='$cuie')
              and afitipocategoria=1
            ) as cantidad";
      $sql_res_emb=sql($sql_emb,"no se pueden traer el denominador de embarazadas");
      
      
      $denominador=$sql_res_emb->fields['cantidad'];
      if ($denominador) {$res_emb=($numerador/$denominador)*100;}
      else $res_emb=0;
      ?>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=$numerador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=$denominador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=number_format($res_emb,2,',','.')?></b></font>
      </td>
      
      </tr>
      
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?> title="El indicador mide el nivel de Búsqueda activa de embarazadas en el primer trimestre por agente sanitario  y/o personal de salud">
        <font size=2> <b>2.2 - NIVEL DE B&Uacute;SQUEDA ACTIVA DE EMBARAZADAS </b></font>
            </td>
      <?$sql_ex="SELECT count (*) as cantidad from (
            select distinct comprobante.id_smiafiliados
            from facturacion.comprobante
            inner join facturacion.prestacion on comprobante.id_comprobante=prestacion.id_comprobante
            where (prestacion.id_nomenclador=1983 or prestacion.id_nomenclador=2076 or prestacion.id_nomenclador=1261)
            and comprobante.cuie='$cuie'
            ) as cantidad";
      $res_sql_ex=sql($sql_ex,"No se puede calcular busqueda activa en embarazadas");?>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b>-----</b></font>
      </td>
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b>-----</b></font>
      </td>
            
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=($res_sql_ex->fields['cantidad'])?$res_sql_ex->fields['cantidad']:0?></b></font>
      </td>
      
      </tr>
      
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?> title="El indicador mide el nivel de embarazadas de alto riesgo a cargo del efector que son trasladadas en forma oportuna a un mayor nivel de complejidad para realización de parto/cesárea">
        <font size=2> <b>2.3 - TASA DE TRASLADO INTRA-&Uacute;TERO </b></font>
            </td>
      <!-- NO FIGURAN LAS PRACTICAS EN EL NOMENCLADOR-->
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b>-----</b></font>
      </td>
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b>-----</b></font>
      </td>
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b>0</b></font>
      </td>
      
      </tr>
    
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?> title="El indicador mide el nivel de Referencia oportuna por embarazo de alto riesgo a cargo del efector">
        <font size=2> <b>2.4 - NIVEL DE REFERENCIA OPORTUNA </b></font>
            </td>
      
      <? $sql_ex="SELECT count (*) as cantidad from (
            select distinct comprobante.id_smiafiliados
            from facturacion.comprobante
            inner join facturacion.prestacion on comprobante.id_comprobante=prestacion.id_comprobante
            where prestacion.id_nomenclador=2419 
            and comprobante.cuie='$cuie'
            ) as cantidad";
        $res_sql_ex=sql($sql_ex,"No se encuntran los datos para refer.Oportuna");
        $dato=$res_sql_ex->fields['cantidad'];
        ?>

      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b>-----</b></font>
      </td>
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b>-----</b></font>
      </td>
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=$dato?></b></font>
      </td>
      
      </tr>
      
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?> title="El indicador mide la cobertura del seguimiento de salud de los adolescentes beneficiarios a cargo del efector (trazadora X post-auditoría)">
        <font size=2> <b>3.1 - SEGUIMIENTO DE SALUD DEL ADOLESCENTE </b></font>
            </td>
      <?/*$sql_ex="SELECT count (*) as cantidad from nacer.smiafiliados where cuieefectorasignado='$cuie' 
            and (fechainscripcion::date-afifechanac) between 3650 and 6935";*/

      
      //Adolescentes desde trazadoras.nino_new
      $sql_ex="SELECT count(*) as cantidad from (
      SELECT distinct on (nacer.smiafiliados.afiapellido, 
    nacer.smiafiliados.afinombre,ccc.num_doc)

  nacer.smiafiliados.clavebeneficiario, 
  nacer.smiafiliados.afiapellido, 
  nacer.smiafiliados.afinombre, 
  nacer.smiafiliados.afitipodoc, 
  nacer.smiafiliados.aficlasedoc::character(1), 
  --nacer.smiafiliados.afidni, 
  nacer.smiafiliados.afisexo::character(1),
  nacer.smiafiliados.afifechanac,
  ccc.cuie,
  (ccc.num_doc::numeric(30,0))::text as afidni,
  ccc.fecha_control,
  trim (both '0000' from ccc.peso::text) as peso ,
  round (ccc.talla) as talla,
  ccc.percen_peso_edad,
  ccc.percen_talla_edad,
  ccc.percen_perim_cefali_edad,
  ccc.percen_peso_talla,
  ccc.ta
  from nacer.smiafiliados
  inner join (select *,(trazadoras.nino_new.num_doc::numeric(30,0))::text as afidni from trazadoras.nino_new) as ccc
  on nacer.smiafiliados.afidni=ccc.afidni
  --filtro para adolescentes entre 10 y 19
  where  (nacer.smiafiliados.fechainscripcion between '$fecha_desde' and '$fecha_hasta') and 
  ((nacer.smiafiliados.fechainscripcion::date-nacer.smiafiliados.afifechanac) between 3650 and 6935) 
  and nacer.smiafiliados.cuie_ceb='$cuie') as cantidad";


  $res_sql_ex=sql($sql_ex,"No se puede calcular seguimiento del adolescente");
  $numerador=$res_sql_ex->fields['cantidad'];

  //adolescentes desde trazadorassps.trazadora_10
  $sql_ex="SELECT count(*) as cantidad from (
    SELECT distinct on (nacer.smiafiliados.afiapellido, 
    nacer.smiafiliados.afinombre,nacer.smiafiliados.afidni)

  nacer.smiafiliados.clavebeneficiario, 
  nacer.smiafiliados.afiapellido, 
  nacer.smiafiliados.afinombre, 
  nacer.smiafiliados.afitipodoc, 
  nacer.smiafiliados.aficlasedoc::character(1), 
  nacer.smiafiliados.afidni, 
  nacer.smiafiliados.afisexo::character(1),
  nacer.smiafiliados.afifechanac,
  trazadorassps.trazadora_10.cuie, 
  trazadorassps.trazadora_10.fecha_nac, 
  trazadorassps.trazadora_10.fecha_control, 
  trazadorassps.trazadora_10.peso, 
  case when trazadorassps.trazadora_10.talla<=2 then round (trazadorassps.trazadora_10.talla*100) 
  else round(trazadorassps.trazadora_10.talla) end as talla,
  trazadorassps.trazadora_10.cuie
  from nacer.smiafiliados
  inner join trazadorassps.trazadora_10 on nacer.smiafiliados.id_smiafiliados=trazadorassps.trazadora_10.id_smiafiliados
  --filtro para adolescentes entre 10 y 19
  where  (nacer.smiafiliados.fechainscripcion between '$fecha_desde' and '$fecha_hasta') and 
  ((nacer.smiafiliados.fechainscripcion::date-nacer.smiafiliados.afifechanac) between 3650 and 6935) 
  and nacer.smiafiliados.cuie_ceb='$cuie') as cantidad";


  $res_sql_ex=sql($sql_ex,"No se puede calcular seguimiento del adolescente");
  $numerador+=$res_sql_ex->fields['cantidad'];

  //Adolescentes desde fichero.fichero
  $sql_ex="SELECT count (*) as cantidad from (
  SELECT distinct on (nacer.smiafiliados.afiapellido,nacer.smiafiliados.afinombre,nacer.smiafiliados.afidni)

  nacer.smiafiliados.clavebeneficiario, 
  nacer.smiafiliados.afiapellido, 
  nacer.smiafiliados.afinombre, 
  nacer.smiafiliados.afitipodoc, 
  nacer.smiafiliados.aficlasedoc::character(1), 
  nacer.smiafiliados.afidni, 
  nacer.smiafiliados.afisexo::character(1),
  nacer.smiafiliados.afifechanac,
  fichero.fichero.cuie,
  fichero.fichero.fecha_control,
  trim (both '0000' from (fichero.fichero.peso::text)) as peso ,
  round (fichero.fichero.talla) as talla,
  fichero.fichero.percen_peso_talla
  from nacer.smiafiliados
  inner join fichero.fichero on nacer.smiafiliados.id_smiafiliados=fichero.fichero.id_smiafiliados
  --filtro para adolescentes entre 10 y 19
  where  (nacer.smiafiliados.fechainscripcion between '$fecha_desde' and '$fecha_hasta') and 
  ((nacer.smiafiliados.fechainscripcion::date-nacer.smiafiliados.afifechanac) between 3650 and 6935) 
  and nacer.smiafiliados.cuie_ceb='$cuie') as cantidad";


  $res_sql_ex=sql($sql_ex,"No se puede calcular seguimiento del adolescente");
  $numerador+=$res_sql_ex->fields['cantidad'];

  /*$sql_ex="SELECT count (*) as cantidad from nacer.smiafiliados where cuie_ceb='$cuie' 
            and fechainscripcion between '$fecha_desde' and '$fecha_hasta'
            and (fechainscripcion::date-afifechanac) between 3650 and 6935";
  $res_sql_ex=sql($sql_ex,"No se puede calcular DEenominador de seguimiento del adolescente");
  $denominador=$res_sql_ex->fields['cantidad'];*/

  //$denominador=$sql_res_metas->fields['ceb_diezadiecinueve'];
  $denominador=$ceb_c;
  if ($denominador) {$res_adol=($numerador/$denominador)*100;}
  else $res_adol=0;?>
      
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=$numerador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=$denominador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=number_format($res_adol,2,',','.')?></b></font>
      </td>
      
      </tr>
      
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?> title="El indicador mide la cobertura de la promoción de derechos y cuidados en salud sexual y reproductiva de los beneficiarios a cargo del efector (trazadora XI post-auditoría)">
        <font size=2> <b>3.2 - PROMOCION DE DERECHOS Y CUIDADOS EN SALUD SEXUAL Y REPRODUCTIVA </b></font>
            </td>
      
      <?/*$sql_ex="SELECT count (*) as cantidad from nacer.smiafiliados where cuieefectorasignado='$cuie' 
            and (((fechainscripcion::date-afifechanac) between 3650 and 6935)
            or (afisexo='F' and (fechainscripcion::date-afifechanac) between 7300 and 23360))";*/

      $sql_ex="SELECT count (*) as cantidad from (
                    select * from 
            (select distinct id_smiafiliados from nacer.smiafiliados
          where --(nacer.smiafiliados.fechainscripcion between '2014-01-01' and '2014-12-31') and 
                --filtro adolescentes entre 10 y 19 años y mujeres de 20 a 64 años
          (((nacer.smiafiliados.".'"FechaUtimaPrestacion"'."-nacer.smiafiliados.afifechanac) between 3650 and 6935) or
          --filtro mujeres entre 20 y 24 años
          (((nacer.smiafiliados.".'"FechaUtimaPrestacion"'."-nacer.smiafiliados.afifechanac) between 7300 and 8760) and 
                     nacer.smiafiliados.afisexo='F'))
                     and (cuie_ceb='$cuie' or cuieefectorasignado='$cuie' or cuielugaratencionhabitual='$cuie')) as smiafiliados
                      
                inner join (
                  
                SELECT distinct comprobante.id_smiafiliados from facturacion.prestacion
                inner join facturacion.comprobante on comprobante.id_comprobante=prestacion.id_comprobante
                where (prestacion.id_nomenclador=2040 or prestacion.id_nomenclador=1821 or prestacion.id_nomenclador=2469 or prestacion.id_nomenclador=2470 --TA T007
                    or prestacion.id_nomenclador=2011 or prestacion.id_nomenclador=2004 or prestacion.id_nomenclador=2471 or prestacion.id_nomenclador=2472 --TA T008
                    or prestacion.id_nomenclador=1667 or prestacion.id_nomenclador=1980 or prestacion.id_nomenclador=2458 --TA T001
                    or prestacion.id_nomenclador=2020 or prestacion.id_nomenclador=2046 or prestacion.id_nomenclador=2477 or prestacion.id_nomenclador=2479 or prestacion.id_nomenclador=2478 --TA T011
                    or prestacion.id_nomenclador=2021 or prestacion.id_nomenclador=2043 or prestacion.id_nomenclador=2482 or prestacion.id_nomenclador=2483 --TA T013
                    or prestacion.id_nomenclador=2006 or prestacion.id_nomenclador=2484) --TA T014
                    and comprobante.cuie='$cuie'
                ) as prestaciones on smiafiliados.id_smiafiliados=prestaciones.id_smiafiliados
                ) as cantidad";

  $res_sql_ex=sql($sql_ex,"No se puede calcular numerador cuidado sexual y reproductivo");
  $numerador=$res_sql_ex->fields['cantidad'];
      
  $sql_ex="SELECT count (*) as cantidad from (
    select distinct id_smiafiliados from nacer.smiafiliados
    where --(nacer.smiafiliados.fechainscripcion between '$fecha_desde' and '$fecha_hasta') and 
    --filtro adolescentes entre 10 y 19 años y mujeres de 20 a 24 años
    (((nacer.smiafiliados.".'"FechaUtimaPrestacion"'."-nacer.smiafiliados.afifechanac) between 3650 and 6935) or
    --filtro mujeres entre 20 y 24 años
    (((nacer.smiafiliados.".'"FechaUtimaPrestacion"'."-nacer.smiafiliados.afifechanac) between 7300 and 8760) and 
     nacer.smiafiliados.afisexo='F'))
     and (cuie_ceb='$cuie' or cuieefectorasignado='$cuie' or cuielugaratencionhabitual='$cuie')
    ) as cantidad";

    $res_sql_ex=sql($sql_ex,"No se puede calcular denominador cuidado sexual y reproductivo");
    $denominador=$res_sql_ex->fields['cantidad'];
    //$denominador=$sql_res_metas->fields['ceb_diezadiecinueve']+$sql_res_metas->fields['ceb_veinteasesentaycuatro'];
    
      if ($denominador) {$res_ssr=($numerador/$denominador)*100;}
      else $res_ssr=0;
      ?>
            
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=$numerador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=$denominador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=number_format ($res_ssr,2,',','.')?></b></font>
      </td>
      
      </tr>
      
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?> title="El indicador mide la tasa de cobertura del control odontologico en adolescentes beneficiarios a cargo del efector ">
        <font size=2> <b>3.3 - TASA DE COBERTURA DE CONTROL ODONTOL&Oacute;GICO </b></font>
            </td>
      <?$sql_ex="SELECT count (*) as cantidad from (
            select distinct comprobante.id_smiafiliados
          from facturacion.prestacion 
          inner join facturacion.comprobante on comprobante.id_comprobante=prestacion.id_comprobante
          where (prestacion.id_nomenclador=2044 or prestacion.id_nomenclador=2163) and comprobante.cuie='$cuie'
          and comprobante.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
        ) as cantidad";

      $res_sql_ex=sql($sql_ex,"No se puede calcular cobertura de control odontologico");
      $numerador=$res_sql_ex->fields['cantidad'];
      
      /*$sql_ex="SELECT * from nacer.smiafiliados where cuieefectorasignado='$cuie' 
          and ((fechainscripcion::date-afifechanac) between 3650 and 6650)";

      $res_sql_ex=sql($sql_ex,"No se puede calcular la cantidad de inscripto de 11 años");*/
      
      //$denominador=$sql_res_metas->fields['ceb_diezadiecinueve'];
      $denominador=$ceb_c;
      if($denominador) {$res_odon=($numerador/$denominador)*100;}
      else $res_odon=0;
      
      ?>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=$numerador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=$denominador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=number_format($res_odon,2,',','.')?></b></font>
      </td>
      
      </tr>
      
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?> title="El indicador mide la tasa de cobertura de inmunizaciones de VPH con 3 dosis aplicadas a adolescentes beneficiarias de 11 años a cargo del efector">
        <font size=2> <b>3.4 - TASA DE COBERTURA DE INMUNIZACIONES DE VPH </b></font>
            </td>
      
      <?$sql_ex="SELECT count (*) as cantidad from (
        select * from (
SELECT id_smiafiliados from nacer.smiafiliados where cuieefectorasignado='$cuie' 
          and ((fechainscripcion::date-afifechanac) between 4015 and 4380)) as smiafiliados
inner join (
  select comprobante.id_comprobante,
    comprobante.id_smiafiliados 
    from facturacion.prestacion 

    inner join facturacion.comprobante on comprobante.id_comprobante=prestacion.id_comprobante
    where (prestacion.id_nomenclador=1819 or prestacion.id_nomenclador=2260) and comprobante.cuie='$cuie'
  ) as prestaciones on smiafiliados.id_smiafiliados=prestaciones.id_smiafiliados
) as cantidad";

      $res_sql_ex=sql($sql_ex,"No se puede calcular cobertura de inmunizaciones de vph");
      $numerador=($res_sql_ex->fields['cantidad'])?$res_sql_ex->fields['cantidad']:0;
      //hay que analizar la consulta del numerador
      
      $sql_ex="SELECT count(*) as cantidad from (
      SELECT * from nacer.smiafiliados where cuieefectorasignado='$cuie' 
          and ((fechainscripcion::date-afifechanac) between 4015 and 4380)) as cantidad";

      $res_sql_ex=sql($sql_ex,"No se puede calcular la cantidad de inscripto de 11 años");
      
      $denominador=($res_sql_ex->fields['cantidad'])?$res_sql_ex->fields['cantidad']:0;
      
      if ($denominador) {$res_vph=($numerador/$denominador)*100;}
      else $res_vph=0;
      ?>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=$numerador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=$denominador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
          <font size=2><b><?=number_format($res_vph,2,',','.')?></b></font>
      </td>
      
      </tr>
      
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?> title="El indicador mide la tasa de cobertura de mamografías en mujeres beneficiarias a cargo del efector en su zona de influencia">
        <font size=2> <b>4.1 - TASA DE COBERTURA DE MAMOGRAF&Iacute;AS</b></font>
            </td>
      
      <?$sql_ex="SELECT count (*) as cantidad from (
            select distinct comprobante.id_smiafiliados
            from facturacion.comprobante
            inner join facturacion.prestacion on comprobante.id_comprobante=prestacion.id_comprobante
            where (prestacion.id_nomenclador=1345 or prestacion.id_nomenclador=1770 or prestacion.id_nomenclador=2209)
            and comprobante.cuie='$cuie'
            ) as cantidad";

      $res_sql_ex=sql($sql_ex,"No se puede calcular mamografias");
      $numerador=$res_sql_ex->fields['cantidad'];
      
      $sql_ex1="SELECT count (*) as cantidad from (
          select distinct id_smiafiliados from nacer.smiafiliados 
          where (cuieefectorasignado='$cuie' or cuielugaratencionhabitual='$cuie') and 
          afisexo='F' and (fechainscripcion::date-afifechanac) between 17885 and 23360
          ) as cantidad";

      $res_sql_ex1=sql($sql_ex1,"No se puede calcular cantidad de mujeres entre 49 y 64");
      
      $denominador=($res_sql_ex1->fields['cantidad'])?$res_sql_ex1->fields['cantidad']:0;
      
      if ($denominador<>0) {$result_ex=$res_sql_ex->fields['cantidad']/$denominador;}
      else $result_ex=0;
      ?>
      
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=$numerador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=$denominador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=$result_ex?>%</b></font>
      </td>
      </tr>
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?> title="El indicador mide la tasa de cobertura de lecturas de PAP en mujeres beneficiarias a cargo del efector en su zona de influencia">
        <font size=2> <b>4.2 - TASA DE COBERTURA DE TAMIZAJE C&Aacute;NCER C&Eacute;RVICOUTERINO </b></font>
            </td>
      <?$sql_ex="SELECT count (*) as cantidad from (
          (select distinct id_smiafiliados from nacer.smiafiliados 
          where (cuieefectorasignado='$cuie' or cuielugaratencionhabitual='$cuie') and 
          afisexo='F' and (fechainscripcion::date-afifechanac) between 17885 and 23360
          ) as smiafiliados

          inner join (
            select distinct comprobante.id_smiafiliados
            from facturacion.comprobante
            inner join facturacion.prestacion on comprobante.id_comprobante=prestacion.id_comprobante
            where (prestacion.id_nomenclador=1247 or prestacion.id_nomenclador=1248 or prestacion.id_nomenclador=1985
            or prestacion.id_nomenclador=2070 or prestacion.id_nomenclador=1760 or prestacion.id_nomenclador=2069)
            and (prestacion.diagnostico='A98' or prestacion.diagnostico='X86' or prestacion.diagnostico='X75')
            and comprobante.cuie='$cuie' and (prestacion.fecha_prestacion between '$fecha_hasta'::date -730 and '$fecha_desde')
          ) as prestaciones on smiafiliados.id_smiafiliados=prestaciones.id_smiafiliados
        ) as cantidad";

      $res_sql_ex=sql($sql_ex,"No se puede calcular numerador del Tamizaje");
      $numerador=$res_sql_ex->fields['cantidad'];
      
      $sql_ex1="SELECT count (*) as cantidad from (
          select distinct id_smiafiliados from nacer.smiafiliados 
          where (cuieefectorasignado='$cuie' or cuielugaratencionhabitual='$cuie') and 
          afisexo='F' and (fechainscripcion::date-afifechanac) between 17885 and 23360
          ) as cantidad";

      $res_sql_ex1=sql($sql_ex1,"No se puede calcular cantidad de mujeres entre 49 y 64");
      
      $denominador=($res_sql_ex1->fields['cantidad'])?$res_sql_ex1->fields['cantidad']:0;
      
      if ($denominador<>0) {$result_ex=($numerador/$denominador)*100;}
      else $result_ex=0;
      ?>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=$numerador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=$denominador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=number_format($result_ex,2,',','.')?></b></font>
      </td>
      </tr>
      
      <tr>
        <td align="left"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?> title="El indicador mide la tasa de cobertura de lecturas de biopsias en mujeres beneficiarias a cargo del efector en su zona de influencia">
        <font size=2> <b>4.3 - TASA DE LECTURA POR BIOPSIA </b></font>
            </td>
      
      <?$sql_ex="SELECT count (*) as cantidad from (
            select distinct comprobante.id_smiafiliados
            from facturacion.comprobante
            inner join facturacion.prestacion on comprobante.id_comprobante=prestacion.id_comprobante
            where (prestacion.id_nomenclador=1992 or prestacion.id_nomenclador=1249 or prestacion.id_nomenclador=1250
            or prestacion.id_nomenclador=1993 or prestacion.id_nomenclador=2071 or prestacion.id_nomenclador=2072)
            and comprobante.cuie='$cuie' 
            ) as cantidad";

      $res_sql_ex=sql($sql_ex,"No se puede calcular laboratorio");
      $numerador=$res_sql_ex->fields['cantidad'];
      
      $sql_ex="SELECT count (*) as cantidad from (
      select distinct id_smiafiliados from trazadorassps.trazadora_12 where cuie = '$cuie' and id_smiafiliados<>0
      ) as cantidad";
      
      $res_sql_ex=sql($sql_ex,"No se pueden traer los datos de trazadora 12");
      $denominador=$res_sql_ex->fields['cantidad'];
      
      if ($denominador<>0) {$result_ex=($numerador/$denominador)*100;}
      else $result_ex=0;
      ?>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=$numerador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=$denominador?></b></font>
      </td>
      
      <td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr6()?>>
          <font size=2><b><?=number_format($result_ex,2,',','.')?></b></font>
      </td>
      </tr>
      
      <tr>
      </td></tr></table>
  </table>
 
<tr><td><table width=90% align="center" class="bordes">
  <tr align="center"><td>
  <button type="button" id="cerrar" class="btn btn-default btn" onclick="window.close();">Cerrar</button>    
  </td></tr>
 </table></td></tr>

 </td></tr></table>
</form>


 <?=fin_pagina();// aca termino ?>