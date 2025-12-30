<?
require_once ("../../config.php");

$query="SELECT count (DISTINCT uad.beneficiarios.numero_doc)  as cantidad
    FROM
    sip_clap.hcperinatal
    LEFT JOIN uad.beneficiarios ON (sip_clap.hcperinatal.clave_beneficiario = uad.beneficiarios.clave_beneficiario)
    LEFT JOIN nacer.efe_conv ON (sip_clap.hcperinatal.var_0017 = nacer.efe_conv.cuie)
    LEFT JOIN sip_clap.hcgestacion_actual ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcgestacion_actual.id_hcperinatal)
    LEFT JOIN sip_clap.hcparto_aborto ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcparto_aborto.id_hcperinatal)
    LEFT JOIN sip_clap.control_parto ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.control_parto.id_hcperinatal)
    LEFT JOIN sip_clap.control_puerperio ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.control_puerperio.id_hcperinatal)
    LEFT JOIN sip_clap.hcespeciales ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcespeciales.id_hcperinatal)
    LEFT JOIN sip_clap.hclibres ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hclibres.id_hcperinatal)
    LEFT JOIN sip_clap.hcrecien_nacido ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcrecien_nacido.id_hcperinatal)
    LEFT JOIN sip_clap.hcantecedentes ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcantecedentes.id_hcperinatal)
    LEFT JOIN sistema.usuarios ON (hcperinatal.usuario_carga=usuarios.id_usuario) ";


function hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$var){

    $var_array=array();
    $q=$proyeccion
                .$query
                .$str
                .$str_efector
                .$where_var
                .$group_by;

    $res_denominador=sql($q) or fin_pagina();
    $coute=(stripos($var,'integer')==true) ? "" :"'";
    while (!$res_denominador->EOF){
        
        $where_var_str=" and ".$var."=".$coute.$res_denominador->fields['id'].$coute;
        $consulta=$query
            .$str
            .$str_efector
            .$where_var_str;
        $array_temp = array ($res_denominador->fields['descripcion'],$res_denominador->fields['cantidad'],$consulta);
        array_push($var_array,$array_temp);
        $res_denominador->MoveNext();
    };

return $var_array;
}

function get_datos ($var_periodo,$fecha_desde,$fecha_hasta,$cuie,$filtros,$efe_depto)
{
  
 $var_array=array();

 $str='where char_length('.$var_periodo.')=10'.' and '.$var_periodo.' ::date between '."'".$fecha_desde."'".' and '."'".$fecha_hasta."'";
 
 if ($efe_depto<>'' or $efe_depto<>NULL) {
    $str_efector=($efe_depto=='todos') ? '' : ' and nacer.efe_conv.departamento='."'".$efe_depto."'";
 }
    else $str_efector=($cuie=='todos') ? '' : ' and (sip_clap.hcperinatal.var_0017='."'".$cuie."' or sip_clap.hcperinatal.var_0018="."'".$cuie."')";

 $proyeccion="SELECT count (DISTINCT (numero_doc))  as cantidad ";
 $query="FROM
    sip_clap.hcperinatal
    LEFT JOIN uad.beneficiarios ON (sip_clap.hcperinatal.clave_beneficiario = uad.beneficiarios.clave_beneficiario)
    LEFT JOIN nacer.efe_conv ON (sip_clap.hcperinatal.var_0017 = nacer.efe_conv.cuie)
    LEFT JOIN sip_clap.hcgestacion_actual ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcgestacion_actual.id_hcperinatal)
    LEFT JOIN sip_clap.hcparto_aborto ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcparto_aborto.id_hcperinatal)
    LEFT JOIN sip_clap.control_parto ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.control_parto.id_hcperinatal)
    LEFT JOIN sip_clap.control_puerperio ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.control_puerperio.id_hcperinatal)
    LEFT JOIN sip_clap.hcespeciales ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcespeciales.id_hcperinatal)
    LEFT JOIN sip_clap.hclibres ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hclibres.id_hcperinatal)
    LEFT JOIN sip_clap.hcrecien_nacido ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcrecien_nacido.id_hcperinatal)
    LEFT JOIN sip_clap.hcantecedentes ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcantecedentes.id_hcperinatal)
    LEFT JOIN sistema.usuarios ON (hcperinatal.usuario_carga=usuarios.id_usuario) ";


 $q=$proyeccion
    .$query
    .$str
    .$str_efector;

$consulta=$query
        .$str
        .$str_efector
        .$where_var;

$res_denominador=sql($q) or fin_pagina();
$array_temp = array ('Cantidad de Embarazadas segun el Periodo Filtrado',$res_denominador->fields['cantidad'],$consulta);

array_push($var_array,$array_temp);

//nuevo
$where_var='';
$group_by='';
for ($i=0;$i<=sizeof($filtros)-1;$i++) {
    if ($filtros[$i][1]=='' or $filtros[$i][1]=='seleccione') { //siempre que pase por aca es para agrupar
        
        switch ($filtros[$i][0]) {
             
            case 'sip_clap.hcperinatal.var_0009::integer' : {
            $where_var.=' and sip_clap.hcperinatal.var_0009::integer <= 15';
            $q=$proyeccion
                .$query
                .$str
                .$str_efector
                .$where_var;
            
            $consulta=$query
                .$str
                .$str_efector
                .$where_var;


            $res_denominador=sql($q) or fin_pagina();
            $array_temp = array ('Cantidad de Embarazadas Menores de 15 Años',$res_denominador->fields['cantidad'],$consulta);
            array_push($var_array,$array_temp);
            $where_var='';
            
            $where_var.=' and sip_clap.hcperinatal.var_0009::integer < 20';
            $q=$proyeccion
                .$query
                .$str
                .$str_efector
                .$where_var;

            $consulta=$query
                .$str
                .$str_efector
                .$where_var;

            $res_denominador=sql($q) or fin_pagina();
            $array_temp = array ('Cantidad de Embarazadas Menores de 20 Años',$res_denominador->fields['cantidad'],$consulta);
            array_push($var_array,$array_temp);
            $where_var='';

            $where_var.=' and sip_clap.hcperinatal.var_0009::integer between 20 and 35';
            $q=$proyeccion
                .$query
                .$str
                .$str_efector
                .$where_var;

            $consulta=$query
                .$str
                .$str_efector
                .$where_var;
            $res_denominador=sql($q) or fin_pagina();
            $array_temp = array ('Cantidad de Embarazadas Entre 20 y 35 Años',$res_denominador->fields['cantidad'],$consulta);
            array_push($var_array,$array_temp);
            $where_var='';

            $where_var.=' and sip_clap.hcperinatal.var_0009::integer > 35';
            $q=$proyeccion
                .$query
                .$str
                .$str_efector
                .$where_var;

            $consulta=$query
                .$str
                .$str_efector
                .$where_var;
            $res_denominador=sql($q) or fin_pagina();
            $array_temp = array ('Cantidad de Embarazadas Mayores de 35 Años',$res_denominador->fields['cantidad'],$consulta);
            array_push($var_array,$array_temp);
            $where_var='';
            };break;

           case 'sip_clap.hcperinatal.var_0011' : {
            $proyeccion="SELECT ccc.var_0011 as id,
                        case when ccc.var_0011='A' then 'Etnia - BLANCA' 
                         when ccc.var_0011='B' then 'Etnia - INDIGENA'
                         when ccc.var_0011='C' then 'Etnia - MESTIZA'
                         when ccc.var_0011='D' then 'Etnia - NEGRA'
                         when ccc.var_0011='E' then 'Etnia - OTRA'
                        else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                        FROM (select distinct on (numero_doc) * ";
            
            $group_by=') as ccc group by ccc.var_0011 order by 1';
            
            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;


            case 'sip_clap.hcperinatal.var_0013' : {
            $proyeccion="SELECT ccc.var_0013 as id,
                    case when ccc.var_0013='A' then 'Educacion - Ninguna' 
                     when ccc.var_0013='B' then 'Educacion - Primario'
                     when ccc.var_0013='C' then 'Educacion - Secundario'
                     when ccc.var_0013='D' then 'Educacion - Universitario'
                     else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                     FROM (select distinct on (numero_doc) * ";
            
            $group_by=') as ccc group by ccc.var_0013 order by 1';
            
            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            
            };break;
        
            case 'sip_clap.hcperinatal.var_0015' : {
            $proyeccion="SELECT ccc.var_0015 as id,
                         case when ccc.var_0015='A' then 'Estado Civil - Casada' 
                         when ccc.var_0015='B' then 'Estado Civil - Union Estable'
                         when ccc.var_0015='C' then 'Estado Civil - Soltero'
                         when ccc.var_0015='D' then 'Estado Civil - Otro'
                         else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) * ";
            
            $group_by=') as ccc group by ccc.var_0015 order by 1';
            
            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

         
            case 'sip_clap.hcantecedentes.var_0020' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'TBC (Familiares) - NO' 
                        when d='B' then 'TBC (Familiares) - SI'
                        else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                        FROM (select distinct on (numero_doc) *,sip_clap.hcantecedentes.var_0020 d ";
            
           $group_by=') as ccc group by d order by 1';

           $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($i=0;$i<=sizeof($array_temp);$i++) {
                array_push($var_array,$array_temp[$i]);
                    }
                };break;

            case 'sip_clap.hcantecedentes.var_0022' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Diabetes (Familiares) - NO' 
                        when d='B' then 'Diabetes (Familiares) - SI'
                        else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                        FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0022 d ";
            
           $group_by=') as ccc group by d order by 1';

           $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($i=0;$i<=sizeof($array_temp);$i++) {
                array_push($var_array,$array_temp[$i]);
                    }
                };break;

            case 'sip_clap.hcantecedentes.var_0024' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Hipertension (Familiares) - NO' 
                        when d='B' then 'Hipertension (Familiares) - SI'
                        else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                        FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0024 d ";
            
           $group_by=') as ccc group by d order by 1';

           $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($i=0;$i<=sizeof($array_temp);$i++) {
                array_push($var_array,$array_temp[$i]);
                    }
                };break;

            case 'sip_clap.hcantecedentes.var_0026' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Antecedentes Preclampsia (Familiares) - NO' 
                        when d='B' then 'Antecedentes Preclampsia (Familiares) - SI'
                        else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                        FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0026 d ";
            
           $group_by=') as ccc group by d order by 1';

           $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($i=0;$i<=sizeof($array_temp);$i++) {
                array_push($var_array,$array_temp[$i]);
                    }
                };break;


            /*case 'sip_clap.hcantecedentes.var_0028' : {
            $proyeccion="SELECT sip_clap.hcantecedentes.var_0028 as id,
                        case when sip_clap.hcantecedentes.var_0028='A' then 'Eclampsia (Familiares) - NO' 
                        when sip_clap.hcantecedentes.var_0028='B' then 'Eclampsia (Familiares) - SI'
                        else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad ";
            
           $group_by=' group by sip_clap.hcantecedentes.var_0028 order by 1';

           $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($i=0;$i<=sizeof($array_temp);$i++) {
                array_push($var_array,$array_temp[$i]);
                    }
                };break;


            case 'sip_clap.hcantecedentes.var_0030' : {
            $proyeccion="SELECT sip_clap.hcantecedentes.var_0030 as id,
                        case when sip_clap.hcantecedentes.var_0030='A' then 'Otros Antec.Familiares (Familiares) - NO' 
                        when sip_clap.hcantecedentes.var_0030='B' then 'Otros Antec.Familiares (Familiares) - SI'
                        else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad ";
            
           $group_by=' group by sip_clap.hcantecedentes.var_0030 order by 1';

           $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($i=0;$i<=sizeof($array_temp);$i++) {
                array_push($var_array,$array_temp[$i]);
                    }
                };break;*/
            
            
            case 'sip_clap.hcantecedentes.var_0021' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'TBC (Personales) - NO' 
                         when d='B' then 'TBC (Personales) - SI'
                         else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0021 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcantecedentes.var_0023' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Diabetes (Personales) - NO' 
                         when d='B' then 'Diabetes (Personales) - Tipo I'
                         when d='C' then 'Diabetes (Personales) - Tipo II'
                         when d='D' then 'Diabetes (Personales) - Tipo G'
                         else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0023 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcantecedentes.var_0025' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Hipertension (Personales) - NO' 
                         when d='B' then 'Hipertension (Personales) - SI'
                         else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0025 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcantecedentes.var_0027' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Antec. Preclampsia (Personales) - NO' 
                         when d='B' then 'Antec. Preclampsia (Personales) - SI'
                         else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0027 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcantecedentes.var_0029' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Eclampsia (Personales) - NO' 
                         when d='B' then 'Eclampsia (Personales) - SI'
                         else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0029 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcantecedentes.var_0032' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Otros Anteced. (Personales) - NO' 
                         when d='B' then 'Otros Anteced. (Personales) - SI'
                         else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0032 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcantecedentes.var_0032' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Cirugia (Personales) - NO' 
                         when d='B' then 'Cirugia (Personales) - SI'
                         else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0032 d ";
            
            $group_by=' group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcantecedentes.var_0033' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Infertilidad (Personales) - NO' 
                         when d='B' then 'Infertilidad (Personales) - SI'
                         else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0033 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcantecedentes.var_0034' : {
            $proyeccion="SELECT d as id,
                    case when d='A' then 'Anteced. Cardiopatia (Personales) - NO' 
                     when d='B' then 'Anteced. Cardiopatia (Personales) - SI'
                     else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                     FROM (select distinct on (numero_doc) *,sip_clap.hcantecedentes.var_0034 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcantecedentes.var_0035' : {
            $proyeccion="SELECT d as id,
                    case when d='A' then 'Anteced. Nefropatia (Personales) - NO' 
                     when d='B' then 'Anteced. Nefropatia (Personales) - SI'
                     else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                     FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0035 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcantecedentes.var_0040::integer' : {
            $proyeccion="SELECT round(sip_clap.hcantecedentes.var_0040::integer) as id,
                        "."'Gestas Previas - '||"."round(sip_clap.hcantecedentes.var_0040::integer) as descripcion, count (DISTINCT (numero_doc))  as cantidad ";
            
            $group_by=' group by round(sip_clap.hcantecedentes.var_0040::integer) order by 1';
            $var="round(sip_clap.hcantecedentes.var_0040::integer)=";
            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcantecedentes.var_0046::integer' : {
            $proyeccion="SELECT round(sip_clap.hcantecedentes.var_0046::integer) as id,
                            "."'Partos Previos - '||"."round(sip_clap.hcantecedentes.var_0046::integer) as descripcion, count (DISTINCT (numero_doc))  as cantidad ";
            
            $group_by=' group by round(sip_clap.hcantecedentes.var_0046::integer) order by 1';
            $var="round(sip_clap.hcantecedentes.var_0046::integer)=";
            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;


            case 'sip_clap.hcantecedentes.var_0047::integer' : {
            $proyeccion="SELECT round(sip_clap.hcantecedentes.var_0047::integer) as id,
                            "."'Antec. Cesareas - '||"."round(sip_clap.hcantecedentes.var_0047::integer) as descripcion, count (DISTINCT (numero_doc))  as cantidad ";
            
            $group_by=' group by round(sip_clap.hcantecedentes.var_0047::integer) order by 1';
            $var="round(sip_clap.hcantecedentes.var_0047::integer)=";
            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcantecedentes.var_0043::integer' : {
            $proyeccion="SELECT round(sip_clap.hcantecedentes.var_0043::integer) as id,
                        "."'Antec. Nac. Vivo - '||"."round(sip_clap.hcantecedentes.var_0043::integer) as descripcion, count (DISTINCT (numero_doc))  as cantidad ";
            
            $group_by=' group by round(sip_clap.hcantecedentes.var_0043::integer) order by 1';
            $var="round(sip_clap.hcantecedentes.var_0043::integer)=";
            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;


            case 'sip_clap.hcantecedentes.var_0039' : {
            $proyeccion="SELECT d as id, 
                            case when d='A' then 'Antec. Gemelares - NO' 
                            when d='B' then 'Antec. Gemelares - SI'
                            else 'SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                            FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0039 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;
           
           case 'sip_clap.hcgestacion_actual.var_0059' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'EG Confiable por FUM - NO' 
                         when d='B' then 'EG Confiable por FUM - SI'
                         else 'EG Confiable por FUM - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0059 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;


            case 'sip_clap.hcgestacion_actual.var_0060' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'EG Confiable por ECO men.20 Sem.- NO' 
                         when d='B' then 'EG Confiable por ECO men.20 Sem.- SI'
                         else 'EG Confiable por ECO men.20 Sem. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0060 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            //tabaco
            case 'sip_clap.hcespeciales.var_0061' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Fumadora Activa 1er. - NO' 
                         when d='B' then 'Fumadora Activa 1er. - SI'
                         else 'Fumadora Activa 1er. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0061 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0062' : {
            $proyeccion="SELECT d as id,
                         case when d='A' then 'Fumadora Pasiva 1er. - NO' 
                         when d='B' then 'Fumadora Pasiva 1er. - SI'
                         else 'Fumadora Pasiva 1er. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0062 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0063' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Drogas 1er. - NO' 
                         when d='B' then 'Drogas 1er. - SI'
                         else 'Drogas 1er. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0063 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0064' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Alcohol 1er. - NO' 
                         when d='B' then 'Alcohol 1er. - SI'
                         else 'Alcohol 1er. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0064 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0066' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Fumadora Activa 2do. - NO' 
                         when d='B' then 'Fumadora Activa 2do. - SI'
                         else 'Fumadora Activa 2do. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0066 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0067' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Fumadora Pasiva 2do. - NO' 
                         when d='B' then 'Fumadora Pasiva 2do. - SI'
                         else 'Fumadora Pasiva 2do. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0067 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0068' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Drogas 2do. - NO' 
                         when d='B' then 'Drogas 2do. - SI'
                         else 'Drogas 2do. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0068 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0069' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Alcohol 2do. - NO' 
                         when d='B' then 'Alcohol 2do. - SI'
                         else 'Alcohol 2do. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0069 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0071' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Fumadora Activa 3er. - NO' 
                         when d='B' then 'Fumadora Activa 3er. - SI'
                         else 'Fumadora Activa 3er. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0071 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0072' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Fumadora Pasiva 3er. - NO' 
                         when d='B' then 'Fumadora Pasiva 3er. - SI'
                         else 'Fumadora Pasiva 3er. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0072 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0073' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Drogas 3er. - NO' 
                         when d='B' then 'Drogas 3er. - SI'
                         else 'Drogas 3er. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0073 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0074' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Alcohol 3er. - NO' 
                         when d='B' then 'Alcohol 3er. - SI'
                         else 'Alcohol 3er. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0074 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0065' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Violencia 1er. - NO' 
                         when d='B' then 'Violencia 1er. - SI'
                         else 'Violencia 1er. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0065 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0070' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Violencia 2do. - NO' 
                         when d='B' then 'Violencia 2do. - SI'
                         else 'Violencia 2do. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0070 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcespeciales.var_0075' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Violencia 3er. - NO' 
                         when d='B' then 'Violencia 3er. - SI'
                         else 'Violencia 3er. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0075 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0076' : {
            $proyeccion="SELECT d as  id,
                        case when d='A' then 'Vacuna Antirubeola - Previa' 
                         when d='B' then 'Vacuna Antirubeola - No Sabe'
                         when d='C' then 'Vacuna Antirubeola - Embarazo'
                         when d='D' then 'Vacuna Antirubeola - NO'
                         else 'Vacuna Antirubeola - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0076 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0077' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Vacuna Antitetanica - NO' 
                         when d='B' then 'Vacuna Antitetanica - SI'
                         else 'Vacuna Antitetanica - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0077 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0080' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Examen Odontologico - NO' 
                         when d='B' then 'Examen Odontologico - SI'
                         else 'Examen Odontologico - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0080 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0081' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Examen de Mamas - NO' 
                         when d='B' then 'Examen de Mamas - SI'
                         else 'Examen de Mamas - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0081 d ";

            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0082' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Cervix Insp. Visual - NORMAL' 
                         when d='B' then 'Cervix Insp. Visual - ANORMAL'
                         when d='C' then 'Cervix Insp. Visual - NO SE HIZO'
                         else 'Cervix Insp. Visual - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0082 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0083' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Cervix PAP - NORMAL' 
                         when d='B' then 'Cervix PAP - ANORMAL'
                         when d='C' then 'Cervix PAP - NO SE HIZO'
                         else 'Cervix PAP - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0083 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0084' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Cervix COLP - NORMAL' 
                         when d='B' then 'Cervix COLP - ANORMAL'
                         when d='C' then 'Cervix COLP - NO SE HIZO'
                         else 'Cervix COLP - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0084 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0086' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'POSITIVO' 
                         when d='B' then 'NEGATIVO'
                         else 'Factor - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0086 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0088' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Tamiz.Anten.-Toxoplasmosis menor 20 Sem. - Negativo' 
                         when d='B' then 'Tamiz.Anten.-Toxoplasmosis menor 20 Sem. - positivo'
                         else 'Tamiz.Anten.-Toxoplasmosis menor 20 Sem. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0088 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0089' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Tamiz.Anten.-Toxoplasmosis mayor Igual 20 Sem. - Negativo' 
                         when d='B' then 'Tamiz.Anten.-Toxoplasmosis mayor Igual 20 Sem. - Positivo'
                         else 'Tamiz.Anten.-Toxoplasmosis mayor Igual 20 Sem.-SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0089 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0090' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Tamiz.Anten.-Toxopl.1º Consulta - Negativo' 
                         when d='B' then 'Tamiz.Anten.-Toxopl.1º Consulta - Positivo'
                         else 'Tamiz.Anten.-Toxopl.1º Consulta SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0090 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcperinatal.var_0432' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'VIH + (Personales) - NO' 
                         when d='B' then 'VIH + (Personales) - SI'
                         else 'VIH + (Personales) - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcperinatal.var_0432 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcperinatal.var_0433' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Tam. Antenat. prueba VIH Menor 20 Sem. - Positivo' 
                         when d='B' then 'Tam. Antenat. prueba VIH Menor 20 Sem. - Negativo'
                         when d='C' then 'Tam. Antenat. prueba VIH Menor 20 Sem. - No se hizo'
                         else 'Tam. Antenat. prueba VIH Menor 20 Sem. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcperinatal.var_0433 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcperinatal.var_0434' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Tam. Antenat. TARV VIH Menor 20 Sem. - NO' 
                         when d='B' then 'Tam. Antenat. TARV VIH Menor 20 Sem. - SI'
                         when d='C' then 'Tam. Antenat. TARV VIH Menor 20 Sem. - No se hizo'
                         else 'Tam. Antenat. TARV VIH Menor 20 Sem. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcperinatal.var_0434 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcperinatal.var_0435' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Tam. Antenat. prueba VIH Mayor Igual 20 Sem. - Positivo' 
                         when d='B' then 'Tam. Antenat. prueba VIH Mayor Igual 20 Sem. - Negativo'
                         when d='C' then 'Tam. Antenat. prueba VIH Mayor Igual 20 Sem. - No se hizo'
                         else 'Tam. Antenat. prueba VIH Mayor Igual 20 Sem. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcperinatal.var_0435 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcperinatal.var_0436' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Tam. Antenat. TARV VIH Mayor Igual 20 Sem. - NO' 
                         when d='B' then 'Tam. Antenat. TARV VIH Mayor Igual 20 Sem. - SI'
                         when d='C' then 'Tam. Antenat. TARV VIH Mayor Igual 20 Sem. - No se hizo'
                         else 'Tam. Antenat. TARV VIH Mayor Igual 20 Sem. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcperinatal.var_0436 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0101' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Tam. Antenatal Chagas - Negativo' 
                         when d='B' then 'Tam. Antenatal Chagas - Positivo'
                         else 'Tam. Antenatal Chagas - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0101 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0102' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Hepatitis B - Negativo' 
                         when d='B' then 'Hepatitis B - Positivo'
                         else 'Hepatitis B - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0102 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0103' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Bacteriuria Menor 20 Sem. - Normal' 
                         when d='B' then 'Bacteriuria Menor 20 Sem. - Anormal'
                          when d='C' then 'Bacteriuria Menor 20 Sem. - No se hizo'
                         else 'Bacteriuria Menor 20 Sem. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0103 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0104' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Bacteriuria May.Igual 20 Sem. - Normal' 
                         when d='B' then 'Bacteriuria May.Igual 20 Sem. - Anormal'
                          when d='C' then 'Bacteriuria May.Igual 20 Sem. - No se hizo'
                         else 'Bacteriuria May.Igual 20 Sem. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0104 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0109' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Tam. Antenatal Estreptococo B - Negativo' 
                         when d='B' then 'Tam. Antenatal Estreptococo B - Positivo'
                          when d='C' then 'Tam. Antenatal Estreptococo B - No se hizo'
                         else 'Bacteriuria May.Igual 20 Sem. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0109 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0112' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Prueba Sifilis No Treponemica menor 20 Sem. - Negativo' 
                         when d='B' then 'Prueba Sifilis No Treponemica menor 20 Sem. - Positivo'
                          when d='C' then 'Prueba Sifilis No Treponemica menor 20 Sem. - Se desconoce'
                         else 'Prueba Sifilis No Treponemica menor 20 Sem. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0112 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcgestacion_actual.var_0114' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Prueba Sifilis No Treponemica mayor Igual 20 Sem. - Negativo' 
                         when d='B' then 'Prueba Sifilis No Treponemica mayor Igual 20 Sem. - Positivo'
                          when d='C' then 'Prueba Sifilis No Treponemica menor 20 Sem. - Se desconoce'
                         else 'Prueba Sifilis No Treponemica mayor Igual 20 Sem. - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                         FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0114 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;

            case 'sip_clap.hcparto_aborto.var_0271' : {
            $proyeccion="SELECT d as id,
                        case when d='A' then 'Anemia - NO' 
                        when d='B' then 'Anemia - SI'
                        else 'Anemia - SIN DATOS' end as descripcion, count (DISTINCT (numero_doc))  as cantidad 
                        FROM (select distinct on (numero_doc) *, sip_clap.hcparto_aborto.var_0271 d ";
            
            $group_by=') as ccc group by d order by 1';

            $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,$filtros[$i][0]);
            for ($j=0;$j<=sizeof($array_temp);$j++) {
                array_push($var_array,$array_temp[$j]);
                }
            };break;


        default : break;           
        }// del swith
    }
    else {
        $filtros[$i][1]=str_replace('apostrofe', "'", $filtros[$i][1]);
        $filtros[$i][1]=str_replace('menor', "<", $filtros[$i][1]);
        $filtros[$i][1]=str_replace('mayor', ">", $filtros[$i][1]);

        $where_var.=' and '.$filtros[$i][0].' '.$filtros[$i][1];
        $q=$proyeccion
            .$query
            .$str
            .$str_efector
            .$where_var;

        $consulta=$query
                .$str
                .$str_efector
                .$where_var;
        $res_denominador=sql($q) or fin_pagina();
        $array_temp = array ('Cantidad de Embarazadas '.$filtros[$i][1],$res_denominador->fields['cantidad'],$consulta);
        array_push($var_array,$array_temp);
    }
}

return $var_array;


}


function get_datos_global ($var_periodo,$fecha_desde,$fecha_hasta,$cuie,$filtro,$efe_depto)
{
  
 $var_array=array();

 $str='where char_length('.$var_periodo.')=10'.' and '.$var_periodo.' ::date between '."'".$fecha_desde."'".' and '."'".$fecha_hasta."'";
 if ($efe_depto<>'' or $efe_depto=NULL) {
    $str_efector=($efe_depto=='todos') ? '' : ' and nacer.efe_conv.departamento='."'".$efe_depto."'";
 }
    else $str_efector=($cuie=='todos') ? '' : ' and (sip_clap.hcperinatal.var_0017='."'".$cuie."' or sip_clap.hcperinatal.var_0018="."'".$cuie."')";

 $proyeccion="SELECT count (distinct (numero_doc))  as cantidad";
 $query=" FROM
    sip_clap.hcperinatal
    LEFT JOIN uad.beneficiarios ON (sip_clap.hcperinatal.clave_beneficiario = uad.beneficiarios.clave_beneficiario)
    LEFT JOIN nacer.efe_conv ON (sip_clap.hcperinatal.var_0017 = nacer.efe_conv.cuie)
    LEFT JOIN sip_clap.hcgestacion_actual ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcgestacion_actual.id_hcperinatal)
    LEFT JOIN sip_clap.hcparto_aborto ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcparto_aborto.id_hcperinatal)
    LEFT JOIN sip_clap.control_parto ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.control_parto.id_hcperinatal)
    LEFT JOIN sip_clap.control_puerperio ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.control_puerperio.id_hcperinatal)
    LEFT JOIN sip_clap.hcespeciales ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcespeciales.id_hcperinatal)
    LEFT JOIN sip_clap.hclibres ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hclibres.id_hcperinatal)
    LEFT JOIN sip_clap.hcrecien_nacido ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcrecien_nacido.id_hcperinatal)
    LEFT JOIN sip_clap.hcantecedentes ON (sip_clap.hcperinatal.id_hcperinatal = sip_clap.hcantecedentes.id_hcperinatal)
    LEFT JOIN sistema.usuarios ON (hcperinatal.usuario_carga=usuarios.id_usuario) ";



$q=$proyeccion
    .$query 
    .$str
    .$str_efector;

$consulta=$query
        .$str
        .$str_efector;

$res_denominador=sql($q) or fin_pagina();
$array_temp = array ('Cantidad de Embarazadas segun el Periodo Filtrado',$res_denominador->fields['cantidad'],$consulta);
array_push($var_array,$array_temp);

if ($filtro=='edad') {
    $where_var=' and sip_clap.hcperinatal.var_0009::integer <= 15';

    $q=$proyeccion
    .$query 
    .$str
    .$str_efector
    .$where_var;

    $consulta=$query
        .$str
        .$str_efector
        .$where_var;

    $res_denominador=sql($q) or fin_pagina();
    $array_temp = array ('Cantidad de Embarazadas Menores de 15 Años',$res_denominador->fields['cantidad'],$consulta);
    array_push($var_array,$array_temp);

     $where_var=' and sip_clap.hcperinatal.var_0009::integer < 20';

     $q=$proyeccion
    .$query 
    .$str
    .$str_efector
    .$where_var;

    $consulta=$query
        .$str
        .$str_efector
        .$where_var;

    $res_denominador=sql($q) or fin_pagina();
    $array_temp = array ('Cantidad de Embarazadas Menores de 20 Años',$res_denominador->fields['cantidad'],$consulta);
    array_push($var_array,$array_temp);

    $where_var=' and sip_clap.hcperinatal.var_0009::integer between 20 and 35';
    $q=$proyeccion
    .$query 
    .$str
    .$str_efector
    .$where_var;

    $consulta=$query
        .$str
        .$str_efector
        .$where_var;

    $res_denominador=sql($q) or fin_pagina();
    $array_temp = array ('Cantidad de Embarazadas Entre 20 y 35 Años',$res_denominador->fields['cantidad'],$consulta);
    array_push($var_array,$array_temp);

     $where_var=' and sip_clap.hcperinatal.var_0009::integer > 35';
     $q=$proyeccion
        .$query 
        .$str
        .$str_efector
        .$where_var;

    $consulta=$query
        .$str
        .$str_efector
        .$where_var;

    $res_denominador=sql($q) or fin_pagina();
    $array_temp = array ('Cantidad de Embarazadas Mayores 35 Años',$res_denominador->fields['cantidad'],$consulta);
}

if ($filtro=='etnia') {
    $proyeccion="SELECT ccc.var_0011 as id,
            case when ccc.var_0011='A' then 'Etnia - BLANCA' 
             when ccc.var_0011='B' then 'Etnia - INDIGENA'
             when ccc.var_0011='C' then 'Etnia - MESTIZA'
             when ccc.var_0011='D' then 'Etnia - NEGRA'
             when ccc.var_0011='E' then 'Etnia - OTRA'
            else 'SIN DATOS' end as descripcion, count (*)  as cantidad 
            FROM (select distinct on (numero_doc) * ";

    $group_by=') as ccc group by ccc.var_0011 order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcperinatal.var_0011');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}


if ($filtro=='educacion') {
    $proyeccion="SELECT ccc.var_0013 as id,
            case when ccc.var_0013='A' then 'Educacion - Ninguna' 
             when ccc.var_0013='B' then 'Educacion - Primario'
             when ccc.var_0013='C' then 'Educacion - Secundario'
             when ccc.var_0013='D' then 'Educacion - Universitario'
             else 'SIN DATOS' end as descripcion, count (*)  as cantidad 
             FROM (select distinct on (numero_doc) * ";

    $group_by=') as ccc group by ccc.var_0013 order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcperinatal.var_0013');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}

if ($filtro=='civil') {
    $proyeccion="SELECT ccc.var_0015 as id,
            case when ccc.var_0015='A' then 'Estado Civil - Casada' 
             when ccc.var_0015='B' then 'Estado Civil - Union Estable'
             when ccc.var_0015='C' then 'Estado Civil - Soltero'
             when ccc.var_0015='D' then 'Estado Civil - Otro'
             else 'SIN DATOS' end as descripcion, count (*)  as cantidad
             FROM (select distinct on (numero_doc) * ";

    $group_by=') as ccc group by ccc.var_0015 order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcperinatal.var_0015');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}

if ($filtro=='familiar') {
    $proyeccion="SELECT d as id,
            case when d='A' then 'TBC (Familiares) - NO' 
             when d='B' then 'TBC (Familiares) - SI'
             else 'SIN DATOS' end as descripcion, count (*)  as cantidad 
             FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0020 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcantecedentes.var_0020');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}

if ($filtro=='personal') {
    $proyeccion="SELECT d as id,
            case when d='A' then 'TBC (Personales) - NO' 
             when d='B' then 'TBC (Personales) - NO'
             else 'SIN DATOS' end as descripcion, count (*)  as cantidad
             FROM (select distinct on (numero_doc) *, sip_clap.hcantecedentes.var_0021 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcantecedentes.var_0021');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}

if ($filtro=='gestasprevias') {
    $proyeccion="SELECT round(sip_clap.hcantecedentes.var_0040::integer) as id,
        "."'Gestas Previas - '||"."round(sip_clap.hcantecedentes.var_0040::integer) as descripcion, count (*)  as cantidad";

    $group_by=' group by round(sip_clap.hcantecedentes.var_0040::integer) order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'round(sip_clap.hcantecedentes.var_0040::integer)');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}


if ($filtro=='partosprevios') {
    $proyeccion="SELECT round(sip_clap.hcantecedentes.var_0046::integer) as id,
        "."'Partos Previos - '||"."round(sip_clap.hcantecedentes.var_0046::integer) as descripcion, count (*)  as cantidad";

    $group_by=' group by round(sip_clap.hcantecedentes.var_0046::integer) order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'round(sip_clap.hcantecedentes.var_0046::integer)');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}

if ($filtro=='cesareas') {
    $proyeccion="SELECT round(sip_clap.hcantecedentes.var_0047::integer) as id,
            "."'Antec. Cesareas - '||"."round(sip_clap.hcantecedentes.var_0047::integer) as descripcion, count (*)  as cantidad";

   $group_by=' group by round(sip_clap.hcantecedentes.var_0047::integer) order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'round(sip_clap.hcantecedentes.var_0047::integer)');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}

if ($filtro=='tabaco') {
    $proyeccion="SELECT d as id,
                case when (d='A') then 'Fumadora Activa 1er. - NO'
                when (d='B') then 'Fumadora Activa 1er. - SI'
                else 'Fumadora Activa 1er. - Sin Datos' end as descripcion, count (*)  as cantidad 
                FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0061 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcespeciales.var_0061');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }

    $proyeccion="SELECT d as id,
                case when (d='A') then 'Fumadora Activa 2do. - NO'
                when (d='B') then 'Fumadora Activa 2do. - SI'
                else 'Fumadora Activa 2do. - Sin Datos' end as descripcion, count (*)  as cantidad 
                FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0066 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcespeciales.var_0066');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }

    $proyeccion="SELECT d as id,
                case when (d='A') then 'Fumadora Activa 3er. - NO'
                when (d='B') then 'Fumadora Activa 3er. - SI'
                else 'Fumadora Activa 3er. - Sin Datos' end as descripcion, count (*)  as cantidad 
                FROM (select distinct on (numero_doc) *, sip_clap.hcespeciales.var_0071 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcespeciales.var_0071');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }

}


if ($filtro=='antitetanica') {
    $proyeccion="SELECT d as id,
                case when (d='A') then 'Antitetanica Vigente - NO'
                when (d='B') then 'Antitetanica Vigente - SI'
                else 'Sin Datos' end as descripcion, count (*)  as cantidad
                FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0077 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcgestacion_actual.var_0077');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}


if ($filtro=='exa_mama') {
    $proyeccion="SELECT d as id,
                case when (d='A') then 'Examen de Mama - NO'
                when (d='B') then 'Examen de Mama - SI'
                else 'Sin Datos' end as descripcion, count (*)  as cantidad 
                FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0081 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcgestacion_actual.var_0081');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}

if ($filtro=='exa_cervi') {
    $proyeccion="SELECT d as id,
                case when (d='A') then 'Cervix Insp. Visual - Normal'
                when (d='B') then 'Cervix Insp. Visual - Anormal'
                when (d='C') then 'Cervix Insp. Visual - No se hizo'
                
                else 'Cervix Insp. Visual - Sin Datos' end as descripcion, count (*)  as cantidad 
                FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0082 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcgestacion_actual.var_0082');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }

    $proyeccion="SELECT d as id,
                case when (d='A') then 'Cervix PAP - Normal'
                when (d='B') then 'Cervix PAP - Anormal'
                when (d='C') then 'Cervix PAP - No se hizo'

                else 'Cervix PAP - Sin Datos' end as descripcion, count (*)  as cantidad 
                FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0083 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcgestacion_actual.var_0083');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }

    $proyeccion="SELECT d as id,
                case when (d='A') then 'Cervix COLP - Normal'
                when (d='B') then 'Cervix COLP - Anormal'
                when (d='C') then 'Cervix COLP - No se hizo'
                else 'Cervix COLP - Sin Datos' end as descripcion, count (*)  as cantidad 
                FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0084 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcgestacion_actual.var_0084');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }

}

if ($filtro=='bacteriuria') {
    $proyeccion="SELECT d as id,
                case when (d='A') then 'Bacteriuria Menor 20 Sem.- Normal'
                when (d='B') then 'Bacteriuria Menor 20 Sem.- Anormal'
                when (d='C') then 'Bacteriuria Menor 20 Sem.- No se hizo'
                else 'Bacteriuria Menor 20 Sem. - Sin Datos' end as descripcion, count (*)  as cantidad 
                FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0103 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcgestacion_actual.var_0103');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }

    $proyeccion="SELECT d as id,
                case when (d='A') then 'Bacteriuria Mayor o Igual 20 Sem.- Normal'
                when (d='B') then 'Bacteriuria Mayor o Igual 20 Sem.- Anormal'
                when (d='C') then 'Bacteriuria Mayor o Igual 20 Sem.- No se hizo'
                else 'Bacteriuria Mayor o Igual 20 Sem. - Sin Datos' end as descripcion, count (*)  as cantidad
                FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0104 d ";

   $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcgestacion_actual.var_0104');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}

if ($filtro=='sifilis') {
    $proyeccion="SELECT d as id,
                case when (d='A') then 'Prueba Sifilis No Treponemica menor 20 Sem. - Negativo'
                when (d='B') then 'Prueba Sifilis No Treponemica menor 20 Sem. - Positivo'
                when (d='C') then 'Prueba Sifilis No Treponemica menor 20 Sem. - Se desconoce'
                else 'Prueba Sifilis No Treponemica menor 20 Sem. - Sin Datos' end as descripcion, count (*)  as cantidad 
                FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0112 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcgestacion_actual.var_0112');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }

    $proyeccion="SELECT d as id,
                case when (d='A') then 'Prueba Sifilis No Treponemica mayor igual 20 Sem. - Negativo'
                when (d='B') then 'Prueba Sifilis No Treponemica mayor igual 20 Sem. - Positivo'
                when (d='C') then 'Prueba Sifilis No Treponemica mayor igual 20 Sem. - Se desconoce'
                else 'Prueba Sifilis No Treponemica mayor igual 20 Sem. - Sin Datos' end as descripcion, count (*)  as cantidad 
                FROM (select distinct on (numero_doc) *, sip_clap.hcgestacion_actual.var_0114 d ";

    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcgestacion_actual.var_0114');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}

if ($filtro=='anemia') {
    $proyeccion="SELECT d as id,
                case when (d='A') then 'Anemia - SI'
                when (d='B') then 'Anemia - No'
                else 'Anemia - Sin Datos' end as descripcion, count (*)  as cantidad 
                FROM (select distinct on (numero_doc) *, sip_clap.hcparto_aborto.var_0271 d ";
    
    $group_by=') as ccc group by d order by 1';

    $array_temp=hacer_consulta($proyeccion,$query,$str,$str_efector,$where_var,$group_by,'sip_clap.hcparto_aborto.var_0271');
    for ($j=0;$j<=sizeof($array_temp);$j++) {
            array_push($var_array,$array_temp[$j]);
            }
}

array_push($var_array,$array_temp);

return $var_array;
}

?> 

