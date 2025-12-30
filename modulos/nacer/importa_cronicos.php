<?php

require_once("../../config.php");
require_once("funciones_cronicos.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

//tabla de clasificacion - cronicos
  $sql="SELECT clasificacion_remediar2.*,
    medicos.apellido_medico || ' ' ||medicos.nombre_medico as medico,
    seguimiento_remediar.peso as peso, 
    case when trim (seguimiento_remediar.talla) != '' 
      then seguimiento_remediar.talla::numeric(30,2)
		  else 0 end as talla,  
    seguimiento_remediar.imc as imc,
    smiafiliados.id_smiafiliados,
    smiafiliados.afinombre,
    smiafiliados.afiapellido,
    smiafiliados.afitipodoc,
    smiafiliados.aficlasedoc,
    smiafiliados.afifechanac,
    smiafiliados.afidni,
    smiafiliados.activo,
    smiafiliados.grupopoblacional,
    smiafiliados.afisexo
    from trazadoras.clasificacion_remediar2 
    inner join planillas.medicos using (id_medico)
    inner join trazadoras.seguimiento_remediar using (clave_beneficiario)
    INNER JOIN nacer.smiafiliados on (clasificacion_remediar2.clave_beneficiario=smiafiliados.clavebeneficiario)
    WHERE fecha_control between current_date::date-90 and current_date::date 
    and trim(seguimiento_remediar.peso) = ''
    and trim(seguimiento_remediar.talla)= ''
    order by fecha_control ASC";

$result1=sql($sql) or fin_pagina();

$cont_fac=0;
$cont_fac_r=0;
$cont_trz_adulto=0;
$cont_trz_adulto_r=0;
$cont_fich=0;
$cont_fich_r=0;
$comentario = 'desde CLASIFICACION - CRONICOS';


while (!$result1->EOF) {
 
  $usuario=$_ses_user['name'];
  $fecha_carga=date("Y-m-d H:i:s");
  $clavebeneficiario=trim($result1->fields['clave_beneficiario']);
  $fecha_control=$result1->fields['fecha_control'];
  $fecha_pcontrol=fecha_db($result1->fields['fecha_prox_seguimiento']);
  $cuie=trim($result1->fields['cuie']);
  $nom_medico=$result1->fields['medico'];

  $peso=($result1->fields['peso'])?(int)$result1->fields['peso']:0;
  $peso=($peso>=1000) ? $peso/1000 : $peso;
  $peso=($peso>300 and $peso<1000) ? $peso/100 : $peso;
  //$talla=($result1->fields['talla'])?(int)$result1->fields['talla']:0;
  $talla=$result1->fields['talla'];
  $imc=($result1->fields['imc'])?(int)$result1->fields['imc']:0;

  if ($imc==0 and $peso!=0 and $talla!=0) $imc=$peso/$talla*$talla;
  
  $tension_arterial_M=trim($result1->fields['ta_sist']);
  $tension_arterial_m=trim($result1->fields['ta_diast']);
  $maxima=str_pad($tension_arterial_M,3,"0",STR_PAD_LEFT);
  $minima=str_pad($tension_arterial_m,3,"0",STR_PAD_LEFT);
  $tension_arterial="$maxima"."/"."$minima";
  
  $id_smiafiliados=$result1->fields['id_smiafiliados'];
  $activo=trim($result1->fields['activo']);
  $afidni=trim($result1->fields['afidni']);
  $grupopoblacional=$result1->fields['grupopoblacional'];
  $afitipodoc=trim($result1->fields['afitipodoc']);
  $aficlasedoc=$result1->fields['aficlasedoc'];
  $afifechanac=$result1->fields['afifechanac'];
  $apellido=$result1->fields['afiapellido'];
  $nombre=$result1->fields['afinombre'];
  $sexo=trim($result1->fields['afisexo']);

  $comprobante_test=0;

  if ($result1->recordcount()>0 and $activo=='S'){
    $id_comprobante = insertar_comprobante ($cuie,$nom_medico,$fecha_control,$clavebeneficiario,$id_smiafiliados,$fecha_carga,$comentario,$grupopoblacional,$comentario);

    if ($fecha_control) {
    $q1="SELECT * from facturacion.nomenclador_detalle 
        where '$fecha_control' between fecha_desde and fecha_hasta 
        and modo_facturacion='4'";
    $res_q1=sql($q1) or fin_pagina();
    $id_nomenclador_detalle=$res_q1->fields['id_nomenclador_detalle'];
    
    //codigo redundante pero en el caso de ser diferentes las descriciones hay
    //que cambiar
    if ($id_nomenclador_detalle=='18') {
      $codigo='C001';
      $descripcion='Examen periódico de salud';
      $diagnostico='A97';
      $grupoe='adulto';
    } else {  
      $codigo='C001';
      $descripcion='Examen periódico de salud';
      $diagnostico='A97';
      $grupoe='adulto';
    }
  }

    $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
    $cont_fac+=$cont[0];
    $cont_fac_r+=$cont[1];
    $comprobante_test=$cont[1];
    
    $cont=insertar_trazadora($clavebeneficiario,$fecha_control,$cuie,$aficlasedoc,$afitipodoc
          ,$afidni,$apellido,$nombre,$afifechanac,$peso,$talla,$imc,$tension_arterial,$comentario);
    
    $cont_trz_adulto+=$cont[0];
    $cont_trz_adulto_r+=$cont[1];
    
    if ($result1->fields['dmt']=='1' or $result1->fields['dmt']=='2') {
      
      if ($id_nomenclador_detalle=='18') {
        $codigo='C050';
        $descripcion='"Consulta para diagnóstico de diabetes tipo 2 (a partir de 18 años)"';
        $diagnostico='T90';
        $grupoe='adulto';
        } else {
          $codigo='C050';
            $descripcion='Consulta para diagnóstico de diabetes tipo 2 (a partir de 18 años)';
            $diagnostico='T90';
            $grupoe='adulto';
        }

      $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
      $cont_fac+=$cont[0];
      $cont_fac_r+=$cont[1];
      $comprobante_test+=$cont[1];
    }
    
    if ($result1->fields['rcvg']!='') {
      
      if ($id_nomenclador_detalle=='18') {
        $codigo='C048';
        $descripcion='Consulta para la evaluación de riesgo cardiovascular';
        $diagnostico='K22';
        } else {
          $codigo='C048';
          $descripcion='Consulta para la evaluación de riesgo cardiovascular';
          $diagnostico='K22';
        }

      $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
      $cont_fac+=$cont[0];
      $cont_fac_r+=$cont[1];
      $comprobante_test+=$cont[1];
    }
  }

  $cont=insertar_fichero ($clavebeneficiario,$cuie,$nom_medico,$fecha_control,$peso,
        $talla,$imc,$tension_arterial,$tunner,$id_smiafiliados,$fecha_pcontrol,$diabetico,$hipertenso,$comentario);
      
  $cont_fich+=$cont[0];
  $cont_fich_r+=$cont[1];
  if ($comprobante_test==3) eliminar_comprobante($id_comprobante);
  $result1->movenext();
}//del while para clasificados



//tabla de seguimientos - cronicos
$sql="SELECT a.*,
b.id_smiafiliados,
b.afinombre,
b.afiapellido,
b.afitipodoc,
b.aficlasedoc,
b.afifechanac,
b.afidni,
b.activo,
b.grupopoblacional,
b.afisexo,
c.*
from trazadoras.seguimiento_remediar a, nacer.smiafiliados b, trazadoras.seguimiento_tratamiento c
WHERE b.clavebeneficiario = a.clave_beneficiario
and a.id_seguimiento_remediar = c.id_seguimiento
and fecha_comprobante between current_date::date-10 and current_date::date  order by fecha_comprobante ASC";

$result1=sql($sql) or fin_pagina();

$comentario="Desde SEGUIMIENTO - CRONICOS";

$result1->movefirst();
while (!$result1->EOF) {
  //saco los datos que necesito para cargar
  $usuario=$_ses_user['name'];
  $fecha_carga=date("Y-m-d H:i:s");
  $clavebeneficiario=trim($result1->fields['clave_beneficiario']);
  $fecha_control=$result1->fields['fecha_comprobante'];
  $fecha_pcontrol=fecha_db($result1->fields['fecha_comprobante_proximo']);
  $cuie=trim($result1->fields['efector']);
  $nom_medico=str_replace("'",'',$result1->fields['profesional']);
  
  $peso=($result1->fields['peso'])?(int)$result1->fields['peso']:0;
  $peso=($peso>=1000) ? $peso/1000 : $peso;
  $peso=($peso>300 and $peso<1000) ? $peso/100 : $peso;
  $talla=($result1->fields['talla'])?(int)$result1->fields['talla']:0;
  $imc=($result1->fields['imc'])?(int)$result1->fields['imc']:0;
  $tension_arterial_M=trim($result1->fields['ta_sist']);
  $tension_arterial_m=trim($result1->fields['ta_diast']);
  $maxima=str_pad($tension_arterial_M,3,"0",STR_PAD_LEFT);
  $minima=str_pad($tension_arterial_m,3,"0",STR_PAD_LEFT);
  $tension_arterial="$maxima"."/"."$minima";
  
  $id_smiafiliados=$result1->fields['id_smiafiliados'];
  $activo=trim($result1->fields['activo']);
  $afidni=trim($result1->fields['afidni']);
  $grupopoblacional=$result1->fields['grupopoblacional'];
  $afitipodoc=trim($result1->fields['afitipodoc']);
  $aficlasedoc=$result1->fields['aficlasedoc'];
  $afifechanac=$result1->fields['afifechanac'];
  $apellido=$result1->fields['afiapellido'];
  $nombre=$result1->fields['afinombre'];
  $sexo=trim($result1->fields['afisexo']);

  $res_dia_mes_anio=dia_mes_anio($fecha_nacimiento,$fecha_control);
  $anios_nac=(int)$res_dia_mes_anio['anios'];
  
  $comprobante_test=0;

  if ($result1->recordcount()>0 and $activo=='S'){
    
    $id_comprobante = insertar_comprobante ($cuie,$nom_medico,$fecha_control,$clavebeneficiario,$id_smiafiliados,$fecha_carga,$comentario,$grupopoblacional,$comentario);

    //no hay codigos C002 en los nomencladores 2020 y 2021 facturo C001
    if ($id_nomenclador_detalle=='18') {
        $codigo='C001';
        $descripcion='Examen periódico de salud';
        $diagnostico='A97';
        $grupoe = 'adulto';
    } else {
      $codigo='C001';
      $descripcion='Examen periódico de salud';
      $diagnostico='A97';
      $grupoe = 'adulto';
    }
    $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
    $cont_fac+=$cont[0];
    $cont_fac_r+=$cont[1];
    $comprobante_test=$cont[1];
    
    $cont=insertar_trazadora($clavebeneficiario,$fecha_control,$cuie,$aficlasedoc,$afitipodoc
          ,$afidni,$apellido,$nombre,$afifechanac,$peso,$talla,$imc,$tension_arterial,$comentario);
    
    $cont_trz_adulto+=$cont[0];
    $cont_trz_adulto_r+=$cont[1];
     
    
    if ($result1->fields['ieca_ara']=='si' || 
      $result1->fields['estatina']=='si' || 
      $result1->fields['aas']=='si' || 
      $result1->fields['beta_bloqueantes']=='si' || 
      $result1->fields['hipoglusemiante_oral']=='si' || 
      $result1->fields['insulina']=='si' || 
      $result1->fields['metformina']=='si' || 
      $result1->fields['enalapril']=='si' || 
      $result1->fields['losartan']=='si' || 
      $result1->fields['amlodipina']=='si' || 
      $result1->fields['atenolol']=='si' || 
      $result1->fields['hidroclorotiazida']=='si' 
      ) {
        $codigo='P053';
        $descripcion='Dispensa de medicamentos en efector';
        $diagnostico='T90';
        $grupoe='adulto';

        $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
        $cont_fac+=$cont[0];
        $cont_fac_r+=$cont[1];
        $comprobante_test+=$cont[1];
    };
    
    if ($result1->fields['dmt2']=='on') {
      
      if ($id_nomenclador_detalle=='18') {
        $codigo='C051';
        $descripcion='Consulta de seguimiento de diabetes tipo 2';
        $diagnostico='T90';
        $grupoe='adulto';
        } else {
          $codigo='C051';
          $descripcion='Consulta de seguimiento de diabetes tipo 2 (a partir de 18 años)';
          $diagnostico='T90';
          $grupoe='adulto';
        }

      $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
      $cont_fac+=$cont[0];
      $cont_fac_r+=$cont[1];
      $comprobante_test+=$cont[1];
    };
    
    if ($result1->fields['tabaquismo']=='on') {
      
      if ($id_nomenclador_detalle=='18') {
        $codigo='T023';
        $descripcion='Consejo conductual breve de cese de tabaquismo';
        $diagnostico='P22';
        $grupoe='adulto';
        } else {
          $codigo='T023';
          $descripcion='Consejo conductual breve de cese de tabaquismo';
          $diagnostico='P22';
          $grupoe='adulto';
        }
      
      $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
      $cont_fac+=$cont[0];
      $cont_fac_r+=$cont[1];
      $comprobante_test+=$cont[1];
    };
    
    if ($result1->fields['riesgo_global']) {
      switch ($result1->fields['riesgo_global']) {
        case 'bajo':{$codigo='N007';
                  if ($id_nomenclador_detalle==18) $descripcion='Notificación del nivel de riesgo cardiovascular < 10%';
                    else $descripcion = 'Notificación de riesgo cardiovascular < 10%  (a partir de 18 años)';
                  $diagnostico='K22';
                  $grupoe='adulto';
                  $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
                  $cont_fac+=$cont[0];
                  $cont_fac_r+=$cont[1];
                  $comprobante_test+=$cont[1];
                  break;}
        
        case 'mode':{
                $codigo='N008';
                if ($id_nomenclador_detalle==18) $descripcion='Notificación del nivel de riesgo cardiovascular  10%-< 20%';
                  else $descripcion='Notificación de riesgo cardiovascular  10%-< 20%  (a partir de 18 años)';
                $diagnostico='K22';
                $grupoe='adulto';
                $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
                $cont_fac+=$cont[0];
                $cont_fac_r+=$cont[1];
                $comprobante_test+=$cont[1];
                break;};
        
        case 'alto':{
                $codigo='N009';
                if ($id_nomenclador_detalle==18) $descripcion='Notificación del nivel de riesgo cardiovascular 20%-< 30%';
                  else $descripcion='Notificación de riesgo cardiovascular 20%-< 30%  (a partir de 18 años)';
                $diagnostico='K22';
                $grupoe='adulto';
                $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
                $cont_fac+=$cont[0];
                $cont_fac_r+=$cont[1];
                $comprobante_test+=$cont[1];
                break;};
                
        case 'malto':{
                $codigo='N010';
                if ($id_nomenclador_detalle==18) $descripcion='Notificación del nivel de riesgo cardiovascular  > 30%';
                  else $descripcion='Notificación de riesgo cardiovascular  ≥ 30%  (a partir de 18 años)';
                $diagnostico='K22';
                $grupoe='adulto';
                $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
                $cont_fac+=$cont[0];
                $cont_fac_r+=$cont[1];
                $comprobante_test+=$cont[1];
                break;};
                
        default:break;
        }
    };//riego global
    
    if ($result1->fields['creatininemia']) {
      $creat=(int)$result1->fields['creatininemia'];
      if ($sexo=='M') $formula=186 * pow($creat,-1.154) * pow($anios_nac,-0.203);
        else $formula=186 * pow($creat,-1.154) * pow($anios_nac,-0.203) * 0.742;

      if ($formula < 60) $diagnostico='U89'; else $diagnostico='A98';

        if ($id_nomenclador_detalle=='18') {
          $codigo='C047';
          $descripcion='Consulta preventiva o de diagnóstico precoz en personas con riesgo de ERC';
          $grupoe='adulto';
          } else {
            $codigo='C047';
            $descripcion='Consulta preventiva o de diagnóstico precoz en personas con riesgo de ERC';
            $grupoe='adulto';
          }
        
        $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
        $cont_fac+=$cont[0];
        $cont_fac_r+=$cont[1];
    };
    
    if ($result1->fields['riesgo_globala']) {
      
      if ($id_nomenclador_detalle=='18') {
          $codigo='C049';
          $descripcion='Consulta para seguimiento de persona con riesgo cardiovascular (a partir de 18 años)';
          $diagnostico='K22';
          } else {
            $codigo='C049';
            $descripcion='Consulta para seguimiento de persona con riesgo cardiovascular (a partir de 18 años)';
            $diagnostico='K22';
          }

      
      $cont=insertar_prestacion($id_comprobante,$fecha_control,$codigo,$descripcion,$grupoe,$peso,$tension_arterial,$talla,$id_smiafiliados,$afidni,$diagnostico,$sexo,$afifechanac,$cuie,$clavebeneficiario,$comentario);
      $cont_fac+=$cont[0];
      $cont_fac_r+=$cont[1];
      $comprobante_test+=$cont[1];
    }
  }
   $cont=insertar_fichero ($clavebeneficiario,$cuie,$nom_medico,$fecha_control,$peso,
      $talla,$imc,$tension_arterial,$tunner,$id_smiafiliados,$fecha_pcontrol,$diabetico,$hipertenso,$comentario);



    $cont_fich+=$cont[0];
    $cont_fich_r+=$cont[1];
   if ($comprobante_test==5) eliminar_comprobante($id_comprobante);
   $result1->movenext();
}//del while seguimiento


//nueva migracion para empadronamientos

/*$sql="tabla preparada para migracion";

$result1=sql($sql) or fin_pagina();

$comentario="Desde app - AGENTES SANITARIOS";

$sql_parametros="select * from uad.parametros ";
$result_parametros=sql($sql_parametros) or fin_pagina();
$codigo_provincia=$result_parametros->fields['codigo_provincia'];
$codigo_ci=$result_parametros->fields['codigo_ci'];   
$codigo_uad=$result_parametros->fields['codigo_uad'];   

$q="select nextval('uad.beneficiarios_id_beneficiarios_seq') as id_planilla";
$id_planilla=sql($q) or fin_pagina();

$id_planilla=$id_planilla->fields['id_planilla'];

$id_planilla_clave= str_pad($id_planilla, 6, '0', STR_PAD_LEFT);

$clave_beneficiario=$codigo_provincia.$codigo_uad.$codigo_ci.$id_planilla_clave;

$result1->movefirst();
while (!$result1->EOF) {
  //saco los datos que necesito para cargar
  $usuario=$_ses_user['name'];
  $fecha_carga=date("Y-m-d H:i:s");

  $apellido=$result1->fields['apellido'];
  $nombre=$result1->fields['nombre'];
  $num_doc=$result1->fields['dni'];
  $sexo=$result1->fields['sexo'];//revisar como viene
  $fecha_nac=$result1->fields['fecha_nac'];
  $indigena=//REVISAR;
  $cuie=$result1->fields['cuie'];
  $calle=$result1->fields['direccion'];

   //SCRIPT INSERCION
  $query="insert into uad.beneficiarios
             (id_beneficiarios,estado_envio,clave_beneficiario,tipo_transaccion,apellido_benef,nombre_benef,clase_documento_benef,
             tipo_documento,numero_doc,id_categoria,sexo,fecha_nacimiento_benef,provincia_nac,localidad_nac,pais_nac,
             indigena,id_tribu,id_lengua,fecha_diagnostico_embarazo,semanas_embarazo,
             fecha_probable_parto,fecha_efectiva_parto,cuie_ea,cuie_ah,calle,numero_calle,
             piso,dpto,manzana,entre_calle_1,entre_calle_2,telefono,departamento,localidad,municipio,barrio,cod_pos,observaciones,
       fecha_inscripcion,fecha_carga,usuario_carga,activo,tipo_ficha)
             values
             ($id_planilla,'n','$clave_beneficiario','A',upper('$apellido'),upper('$nombre'),'P',
             'DNI','$num_doc','8',upper('$sexo'),'$fecha_nac','SAN LUIS',upper('$localidad_procn'),'ARGENTINA',
             upper('$indigena'),0,0,'1899-12-30',null,
             '1899-12-30','1899-12-30',upper('$cuie'),upper('$cuie'),upper('$calle'),'',
             '','', '','','','0',upper('$departamenton'),
             upper('$localidadn'),upper('$municipion'),upper('$barrion'),'5700','$comentario', '$fecha_carga',
             '$fecha_carga',upper('$usuario'),'1','1')";

   $result1->movenext();
}//del while seguimiento
*/

limpieza_tablas();

Echo "<b><font size='+1' color='black'>";
echo "Cantidad Facturado: ".$cont_fac."<br>"; 
echo "Cantidad Facturado Rechazado: ".$cont_fac_r."<br>"; 
echo "Cantidad Trazadoras Adulto: ".$cont_trz_adulto."<br>"; 
echo "Cantidad Trazadoras Adulto Rechazado: ".$cont_trz_adulto_r."<br>";
echo "Cantidad Fichero: ".$cont_fich."<br>"; 
echo "Cantidad Fichero Rechazado: ".$cont_fich_r."<br>"; 
echo "Procesados: ".$result1->recordcount()."<br>"; 
echo "</font></b>";

?>

<?echo fin_pagina();// aca termino ?>