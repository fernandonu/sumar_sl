<?
require_once ("../../config.php");
include_once("../facturacion/funciones.php");
require_once ("../nacer/funciones_cronicos.php");

extract($_POST, EXTR_SKIP);
if ($parametros)
extract($parametros, EXTR_OVERWRITE);

cargar_calendario();

$usuario = $_ses_user['login'];
$es_cuie=(es_cuie($usuario))?'si':'no'; 

//trae los datos para cargar el formulario
if ($pagina=='listado_beneficiarios_leche.php'){
	$sql="select clavebeneficiario from nacer.smiafiliados where id_smiafiliados='$id_smiafiliados'";
	$res_clave = sql($sql, "Error al traer la clave del beneficiario") or fin_pagina();
	$clave_beneficiario=$res_clave->fields['clavebeneficiario'];
}
$sql = "select 	clave_beneficiario,
				tipo_documento,
				clase_documento_benef,
				numero_doc,
				apellido_benef,
				nombre_benef,
				fecha_nacimiento_benef,
				sexo,telefono,
				provincia_nac,
				departamento,
				municipio,
				localidad,
				calle,
				barrio,
				numero_calle,
				manzana,
				piso,
				dpto,
				formulario.centro_inscriptor,
				fechaempadronamiento,
				os,
				cual_os 
				from uad.beneficiarios	  
				inner join uad.remediar_x_beneficiario on beneficiarios.clave_beneficiario=remediar_x_beneficiario.clavebeneficiario
				inner join remediar.formulario on remediar_x_beneficiario.nroformulario=formulario.nroformulario
				where beneficiarios.clave_beneficiario='$clave_beneficiario' 
				group by clave_beneficiario,tipo_documento,clase_documento_benef,numero_doc,apellido_benef,nombre_benef,
					     fecha_nacimiento_benef,sexo,telefono, provincia_nac,departamento,municipio,localidad,calle,barrio,numero_calle,manzana,piso,
						 dpto,formulario.centro_inscriptor,fechaempadronamiento,os,cual_os";
$res_extra = sql($sql, "Error al traer el beneficiario") or fin_pagina();
    
if ($res_extra->RecordCount() > 0) {
        $clave = $res_extra->fields['clave_beneficiario'];
        $tipo_doc = $res_extra->fields['tipo_documento'];
        $clase_doc = $res_extra->fields['clase_documento_benef'];
        $num_doc = number_format($res_extra->fields['numero_doc'], 0, '.', '');
        $apellido = $res_extra->fields['apellido_benef'] . ' ' . $res_extra->fields['apellido_benef_otro'];
        $nombre = $res_extra->fields['nombre_benef'] . ' ' . $res_extra->fields['nombre_benef_otro'];
        $fecha_nac = fecha($res_extra->fields['fecha_nacimiento_benef']);
        $sexo = $res_extra->fields['sexo'];
        $telefono = $res_extra->fields['telefono'];
        $provincia_nac = $res_extra->fields['provincia_nac'];
        $departamento = $res_extra->fields['departamento'];
        $municipio = $res_extra->fields['municipio'];
        $localidad = $res_extra->fields['localidad'];
        $calle = $res_extra->fields['calle'];
        $barrio = $res_extra->fields['barrio'];
        $numero_calle = $res_extra->fields['numero_calle'];
        $manzana = $res_extra->fields['manzana'];
        $piso = $res_extra->fields['piso'];
        $dpto = $res_extra->fields['dpto'];
        $cuie_centro_inscriptor = $res_extra->fields['centro_inscriptor'];
        $fechaempadronamiento = fecha($res_extra->fields['fechaempadronamiento']);
        $os = $res_extra->fields['os'];
        $cual_os = $res_extra->fields['cual_os'];
        
        //verifica si tiene cargada clasificacion
        $query = "SELECT  id_clasificacion,nro_clasificacion,clasif_rapida
					FROM trazadoras.clasificacion_remediar2  
					where clave_beneficiario='$clave'";
        $res_factura = sql($query, "Error al traer el Comprobantes") or fin_pagina();
        if ($res_factura->recordcount() > 0) {
            $id_planilla = $res_factura->fields['id_clasificacion'];
            $nro_clasificacion = $res_factura->fields['nro_clasificacion']; //echo rtrim(substr($accion,3,9));
            if (rtrim(substr($accion2, 3, 6)) != "guardo" && rtrim(substr($accion2, 3, 9)) != "actualizo" && $accion != 'Imposible guardar el medico.' && $accion != 'Falta el medico.') {
                $accion = 'Beneficiario ya posee Clasificacion con Nro. ' . $nro_clasificacion;
            }
        }
} 
else {
        echo '<script>
				alert("Esta persona no posee Remediar NO TIENEN EL CALCULO del SCORE de RIESGO");
				window.close();
			  </script>';
}
    
 
if ($_POST['guardar'] == "Guardar Planilla") {
	
	if ($nro_clasificacion=='') $nro_clasificacion=$clave_beneficiario;
    $fecha_carga = date("Y-m-d H:m:s");
    $usuario = $_ses_user['id'];
    $p_abdominal = $_POST['p_abdominal'];
    $talla = $_POST['talla'];
    $imc = round($_POST['imc'],2);
    $db->StartTrans();

    $fecha_nac = Fecha_db($fecha_nac);
    $fecha_clasificacion = Fecha_db($fecha_clasificacion);

    $sql_benef="SELECT * from nacer.smiafiliados where clavebeneficiario='$clave_beneficiario'";
    $res_benef=sql($sql_benef,"No se pudo ejecutar la consulta sobre los beneficiarios") or fin_pagina();
    if ($res_benef->RecordCount()!=0) {
        $activo=trim($res_benef->fields['activo']);
        
        if ($dmt == '') $dmt = 0;    
        if ($acv == 'on') $acv = 1;
        else $acv = 0;
        
        if ($vas_per == 'on') $vas_per = 1;
        else $vas_per = 0;
        
        if ($car_isq == 'on') $car_isq = 1;
        else $car_isq = 0;
        
        if ($col310 == 'on') $col310 = 1;
        else $col310 = 0;
        
        if ($col_ldl == 'on') $col_ldl = 1;
        else $col_ldl = 0;
        
        if ($ct_hdl == 'on') $ct_hdl = 1;
        else $ct_hdl = 0;
        
        if ($pres_art == 'on') $pres_art = 1;
        else $pres_art = 0;
        
        if ($dmt2 == 'on') $dmt2 = 1;
        else $dmt2 = 0;
        
        if ($insu_renal == 'on') $insu_renal = 1;
        else $insu_renal = 0;
            
        if ($dmt_menor == 'on') $dmt_menor = 1;
        else $dmt_menor = 0;
        
        if ($hta_menor == 'on') $hta_menor = 1;
        else $hta_menor = 0;
            
        if ($hta == 'on') $hta = 1;
        else $hta = 0;
        
        if ($tabaquismo == 'SI') $tabaquismo = 1;
        else $tabaquismo = 0;
        
        if ($meno_prema == 'on') $meno_prema = 1;
        else $meno_prema = 0;
        
        if ($antihiper == 'on') $antihiper = 1;
        else $antihiper = 0;
        
        if ($obesi == 'on') $obesi = 1;
        else $obesi = 0;
        
        if ($acv_prema == 'on') $acv_prema = 1;
        else $acv_prema = 0;
        
        if ($trigli == 'on') $trigli = 1;
        else $trigli = 0;
        
        if ($hdl_col == 'on') $hdl_col = 1;
        else $hdl_col = 0;
        
        if ($hiperglu == 'on') $hiperglu = 1;
        else $hiperglu = 0;
        
        if ($microalbu == 'on') $microalbu = 1;
        else $microalbu = 0;
            
        if ($bajo_prog == 'on') $bajo_prog = 1;
        else $bajo_prog = 0;              

        $ta_sist = str_replace(',', '.', $ta_sist);
        if ($ta_sist == '') $ta_sist = 0;
        
        $ta_diast = str_replace(',', '.', $ta_diast);
        if ($ta_diast == '') $ta_diast = 0;
        
        $col_tot = str_replace(',', '.', $col_tot);
        if ($col_tot == '') $col_tot = 0;
        
        if ($sedentarismo == 'on') $sedentarismo = 'SI';
        else $sedentarismo = 'NO';
        
        if ($dmt!=0) $diabetico='SI';
        else $diabetico='NO';

        if ($hta!=0) $hipertenso='SI';
        else $hipertenso='NO';
        
        /*if ($id_medico == '') $accion = 'Falta el medico.';
        elseif ($id_medico == 'new') {
            $queryx = "SELECT  id_medico
                        FROM planillas.medicos 
                    where apellido_medico=upper('$apellido_medico') and nombre_medico=upper('$nombre_medico') and dni_medico='$dni_medico'";
            $res_comp_num_comp = sql($queryx, "Error al traer el Comprobantes") or fin_pagina();
            if ($res_comp_num_comp->recordcount() == 0) {

                $query1 = "insert into planillas.medicos  (id_medico,apellido_medico,nombre_medico,dni_medico) 
                        values (nextval('planillas.medicos_id_medico_seq'),upper('$apellido_medico'),upper('$nombre_medico'),'$dni_medico') RETURNING id_medico";
                $res_extras1 = sql($query1, "Error al insertar la Planilla") or fin_pagina();
                $id_medico = $res_extras1->fields['id_medico'];
            }
            if ($res_comp_num_comp->recordcount() > 0) 
                $accion = 'Imposible guardar el medico.';
            
        }*/   

        //if (!$id_planilla) {
            $queryx = "SELECT  clave_beneficiario,num_doc,apellido,nombre
                        FROM trazadoras.clasificacion_remediar2  
                        WHERE nro_clasificacion='$nro_clasificacion'";
            $res_comp_num_comp = sql($queryx, "Error al traer la Clasificacion") or fin_pagina();
            
            if ($res_comp_num_comp->recordcount() == 0) {
                
                if ($fecha_prox_seguimiento=='')$fecha_prox_seguimiento='NULL';
                    else $fecha_prox_seguimiento=fecha_db($fecha_prox_seguimiento);

                $query = "INSERT into trazadoras.clasificacion_remediar2 
                    (id_clasificacion,nro_clasificacion,cuie,clave_beneficiario,tipo_doc,num_doc,apellido,nombre,
                    fecha_nac,fecha_carga,usuario,fecha_control,id_medico,dmt,acv,vas_per,car_isq,col310,col_ldl,ct_hdl,pres_art
                    ,dmt2,insu_renal,dmt_menor,hta_menor,hta,tabaquismo,ta_sist,ta_diast,col_tot,menopausia,antihiper,
                    obesi,acv_prema,trigli,hdl_col,hiperglu,microalbu,bajo_prog,rcvg,fecha_prox_seguimiento,diabetico,hipertenso,sedentarismo,peso,talla,p_abd,imc)
                VALUES 
                    (nextval('trazadoras.clasificacion_remediar_id_clasificacion_seq'),'$nro_clasificacion','$cuie','$clave','$tipo_doc','$num_doc','$apellido',
                    '$nombre','$fecha_nac','$fecha_carga','$usuario','$fecha_clasificacion','$id_medico','$dmt','$acv','$vas_per','$car_isq','$col310','$col_ldl','$ct_hdl','$pres_art','$dmt2',
                    '$insu_renal','$dmt_menor','$hta_menor','$hta','$tabaquismo','$ta_sist','$ta_diast','$col_tot','$menopausia','$antihiper','$obesi','$acv_prema',
                    '$trigli','$hdl_col','$hiperglu','$microalbu','$bajo_prog','$riesgo_global','$fecha_prox_seguimiento','$diabetico','$hipertenso','$sedentarismo',$peso,$talla,$p_abdominal,$imc) 
                    RETURNING id_clasificacion";
                $res_extras = sql($query, "Error al insertar la Planilla") or fin_pagina();
                $id_planilla = $res_extras->fields['id_clasificacion'];
                
                $accion = '';
                $accion2 .= "Se guardo la Planilla Clasificacion Nro: " . $nro_clasificacion ."</br>";
                
                //facturo y lleno fichero y trazadoras
                if ($activo=='S'){
                    $id_comprobante=facturar_clasificacion($clave_beneficiario,$fecha_clasificacion);
                    $accion2 .= "Se guardo comprobantes N° ".$id_comprobante." para facturacion."."</br>";
                    }
                    else $accion2 .= "Beneficiario no activo para facturacion"."</br>";


            } else {
                if ($fecha_prox_seguimiento=='')$fecha_prox_seguimiento='NULL';
                    else $fecha_prox_seguimiento=fecha_db($fecha_prox_seguimiento);
                $query = "UPDATE trazadoras.clasificacion_remediar2 SET
                    nro_clasificacion='$nro_clasificacion',
                    id_medico='$id_medico',
                    fecha_carga='$fecha_carga',
                    dmt='$dmt',
                    acv='$acv',
                    vas_per='$vas_per',
                    car_isq='$car_isq',
                    col310='$col310',
                    col_ldl='$col_ldl',
                    ct_hdl='$ct_hdl',
                    pres_art='$pres_art',
                    dmt2='$dmt2',
                    insu_renal='$insu_renal',
                    dmt_menor='$dmt_menor',
                    hta_menor='$hta_menor',
                    hta='$hta',
                    tabaquismo='$tabaquismo',
                    ta_sist='$ta_sist',
                    ta_diast='$ta_diast',
                    col_tot='$col_tot',
                    menopausia='$menopausia',
                    antihiper='$antihiper',
                    obesi='$obesi',
                    acv_prema='$acv_prema',
                    trigli='$trigli',
                    hdl_col='$hdl_col',
                    hiperglu='$hiperglu',
                    microalbu='$microalbu',
                    bajo_prog='$bajo_prog',
                    fecha_control='$fecha_clasificacion',
                    rcvg='$riesgo_global',
                    fecha_prox_seguimiento='$fecha_prox_seguimiento', 
                    diabetico='$diabetico', 
                    hipertenso='$hipertenso', 
                    cuie='$cuie',
                    sedentarismo='$sedentarismo',
                    clasif_rapida='',
                    peso=$peso,
                    talla=$talla,
                    p_abd=$p_abdominal,
                    imc=$imc
                    WHERE id_clasificacion=$id_planilla";    
                
                $res_extras = sql($query, "Error al insertar la Planilla") or fin_pagina();
                $accion2 .= "Se actualizo la Planilla";
                //facturo y lleno fichero y trazadoras
                if ($activo=='S'){
                    $id_comprobante=facturar_clasificacion($clave_beneficiario,$fecha_clasificacion);
                    $accion2 .= "Se guardo comprobantes N° ".$id_comprobante." para facturacion."."</br>";
                    }
                    else $accion2 .= "Beneficiario no activo para facturacion"."</br>";
            }        
        //}//del if (!$id_planilla)
    $db->CompleteTrans();
    }
    else {
        if ($dmt == '') $dmt = 0;    
        if ($acv == 'on') $acv = 1;
        else $acv = 0;
        
        if ($vas_per == 'on') $vas_per = 1;
        else $vas_per = 0;
        
        if ($car_isq == 'on') $car_isq = 1;
        else $car_isq = 0;
        
        if ($col310 == 'on') $col310 = 1;
        else $col310 = 0;
        
        if ($col_ldl == 'on') $col_ldl = 1;
        else $col_ldl = 0;
        
        if ($ct_hdl == 'on') $ct_hdl = 1;
        else $ct_hdl = 0;
        
        if ($pres_art == 'on') $pres_art = 1;
        else $pres_art = 0;
        
        if ($dmt2 == 'on') $dmt2 = 1;
        else $dmt2 = 0;
        
        if ($insu_renal == 'on') $insu_renal = 1;
        else $insu_renal = 0;
            
        if ($dmt_menor == 'on') $dmt_menor = 1;
        else $dmt_menor = 0;
        
        if ($hta_menor == 'on') $hta_menor = 1;
        else $hta_menor = 0;
            
        if ($hta == 'on') $hta = 1;
        else $hta = 0;
        
        if ($tabaquismo == 'SI') $tabaquismo = 1;
        else $tabaquismo = 0;
        
        if ($meno_prema == 'on') $meno_prema = 1;
        else $meno_prema = 0;
        
        if ($antihiper == 'on') $antihiper = 1;
        else $antihiper = 0;
        
        if ($obesi == 'on') $obesi = 1;
        else $obesi = 0;
        
        if ($acv_prema == 'on') $acv_prema = 1;
        else $acv_prema = 0;
        
        if ($trigli == 'on') $trigli = 1;
        else $trigli = 0;
        
        if ($hdl_col == 'on') $hdl_col = 1;
        else $hdl_col = 0;
        
        if ($hiperglu == 'on') $hiperglu = 1;
        else $hiperglu = 0;
        
        if ($microalbu == 'on') $microalbu = 1;
        else $microalbu = 0;
            
        if ($bajo_prog == 'on') $bajo_prog = 1;
        else $bajo_prog = 0;              

        $ta_sist = str_replace(',', '.', $ta_sist);
        if ($ta_sist == '') $ta_sist = 0;
        
        $ta_diast = str_replace(',', '.', $ta_diast);
        if ($ta_diast == '') $ta_diast = 0;
        
        $col_tot = str_replace(',', '.', $col_tot);
        if ($col_tot == '') $col_tot = 0;
        
        if ($sedentarismo == 'on') $sedentarismo = 'SI';
        else $sedentarismo = 'NO';
        
        if ($dmt!=0) $diabetico='SI';
        else $diabetico='NO';

        if ($hta!=0) $hipertenso='SI';
        else $hipertenso='NO';
        
        $queryx = "SELECT  clave_beneficiario,num_doc,apellido,nombre
                        FROM trazadoras.clasificacion_remediar2  
                        WHERE nro_clasificacion='$nro_clasificacion'";
        $res_comp_num_comp = sql($queryx, "Error al traer la Clasificacion") or fin_pagina();
        
        if ($res_comp_num_comp->recordcount() == 0) {
            
            if ($fecha_prox_seguimiento=='')$fecha_prox_seguimiento='NULL';
                else $fecha_prox_seguimiento=fecha_db($fecha_prox_seguimiento);

            $query = "INSERT into trazadoras.clasificacion_remediar2 
                (id_clasificacion,nro_clasificacion,cuie,clave_beneficiario,tipo_doc,num_doc,apellido,nombre,
                fecha_nac,fecha_carga,usuario,fecha_control,id_medico,dmt,acv,vas_per,car_isq,col310,col_ldl,ct_hdl,pres_art
                ,dmt2,insu_renal,dmt_menor,hta_menor,hta,tabaquismo,ta_sist,ta_diast,col_tot,menopausia,antihiper,
                obesi,acv_prema,trigli,hdl_col,hiperglu,microalbu,bajo_prog,rcvg,fecha_prox_seguimiento,diabetico,hipertenso,sedentarismo,peso,talla,p_abd,imc)
            VALUES 
                (nextval('trazadoras.clasificacion_remediar_id_clasificacion_seq'),'$nro_clasificacion','$cuie','$clave','$tipo_doc','$num_doc','$apellido',
                '$nombre','$fecha_nac','$fecha_carga','$usuario','$fecha_clasificacion','$id_medico','$dmt','$acv','$vas_per','$car_isq','$col310','$col_ldl','$ct_hdl','$pres_art','$dmt2',
                '$insu_renal','$dmt_menor','$hta_menor','$hta','$tabaquismo','$ta_sist','$ta_diast','$col_tot','$menopausia','$antihiper','$obesi','$acv_prema',
                '$trigli','$hdl_col','$hiperglu','$microalbu','$bajo_prog','$riesgo_global','$fecha_prox_seguimiento','$diabetico','$hipertenso','$sedentarismo',$peso,$talla,$p_abdominal,$imc) 
                RETURNING id_clasificacion";
            $res_extras = sql($query, "Error al insertar la Planilla") or fin_pagina();
            $id_planilla = $res_extras->fields['id_clasificacion'];
            
            $accion = '';
            $accion2 .= "Se guardo la Planilla Clasificacion Nro: " . $nro_clasificacion ."</br>";
            
            

        } else {
            if ($fecha_prox_seguimiento=='')$fecha_prox_seguimiento='NULL';
                else $fecha_prox_seguimiento=fecha_db($fecha_prox_seguimiento);
            $query = "UPDATE trazadoras.clasificacion_remediar2 SET
                nro_clasificacion='$nro_clasificacion',
                id_medico='$id_medico',
                fecha_carga='$fecha_carga',
                dmt='$dmt',
                acv='$acv',
                vas_per='$vas_per',
                car_isq='$car_isq',
                col310='$col310',
                col_ldl='$col_ldl',
                ct_hdl='$ct_hdl',
                pres_art='$pres_art',
                dmt2='$dmt2',
                insu_renal='$insu_renal',
                dmt_menor='$dmt_menor',
                hta_menor='$hta_menor',
                hta='$hta',
                tabaquismo='$tabaquismo',
                ta_sist='$ta_sist',
                ta_diast='$ta_diast',
                col_tot='$col_tot',
                menopausia='$menopausia',
                antihiper='$antihiper',
                obesi='$obesi',
                acv_prema='$acv_prema',
                trigli='$trigli',
                hdl_col='$hdl_col',
                hiperglu='$hiperglu',
                microalbu='$microalbu',
                bajo_prog='$bajo_prog',
                fecha_control='$fecha_clasificacion',
                rcvg='$riesgo_global',
                fecha_prox_seguimiento='$fecha_prox_seguimiento', 
                diabetico='$diabetico', 
                hipertenso='$hipertenso', 
                cuie='$cuie',
                sedentarismo='$sedentarismo',
                clasif_rapida='',
                peso=$peso,
                talla=$talla,
                p_abd=$p_abdominal,
                imc=$imc
                WHERE id_clasificacion=$id_planilla";    
            
            $res_extras = sql($query, "Error al insertar la Planilla") or fin_pagina();
            $accion2 .= "Se actualizo la Planilla"."</br>"; 
            $accion2 .= "Beneficiario no activo para facturacion"."</br>";               
            }        
        
    $db->CompleteTrans();
    } 
	
} //de if ($_POST['guardar']=="Guardar nuevo Muleto")

if ($_POST['borrar'] == "Borrar") {
    $query = "delete from trazadoras.clasificacion_remediar2
			where id_clasificacion=$id_planilla";
    sql($query, "Error al insertar la Planilla") or fin_pagina();
    $accion = "Se elimino la planilla $id_planilla de Niños";
}


if ($_POST['buscar_clasificacion'] == "b") {
    if ($_POST['nro_clasificacion'] != '') {
        $query = "SELECT id_clasificacion,nro_clasificacion,clasif_rapida
				FROM trazadoras.clasificacion_remediar2  
			  where nro_clasificacion='$nro_clasificacion' and clave_beneficiario='$clave'";
        $res_factura = sql($query, "Error al traer el Comprobantes") or fin_pagina();
        if ($res_factura->recordcount() > 0) {
            $id_planilla = $res_factura->fields['id_clasificacion'];
            $nro_clasificacion = $res_factura->fields['nro_clasificacion'];
            $accion = 'Clasificacion con Nro.' . $nro_clasificacion . ' encontrada.';
        }
        if ($res_factura->recordcount() == 0) {
            $accion2 = 'No se encuentra Clasificacion con Nro.' . $nro_clasificacion . ' para este beneficiario';
            //$id_planilla=0;                               
            $fecha_carga = '';
            $acv = '';
            $vas_per = '';
            $car_isq = '';
            $col310 = '';
            $col_ldl = '';
            $ct_hdl = '';
            $pres_art = '';
            $dmt2 = '';
            $insu_renal = '';
            $dmt_menor = '';
            $hta_menor = '';

            $hta = '';
            $tabaquismo = '';
            $ta_sist = '';
            $ta_diast = '';
            $col_tot = '';
            $meno_prema = '';
            $antihiper = '';
            $obesi = '';
            $acv_prema = '';
            $trigli = '';
            $hdl_col = '';
            $hiperglu = '';
            $microalbu = '';
            $bajo_prog = '';
            $riesgo_global = '';

            $apellido_medico = '';
            $nombre_medico = '';
            $matricula_medico = '';
            //$id_smiafiliados=0;
        }
    } else {
        echo "<SCRIPT Language='Javascript'> alert('Debe Cargar el Nro. de Clasificacion'); </SCRIPT>";
        
    }
}


if ($id_planilla) { 
    $query = "SELECT  *
	FROM
  trazadoras.clasificacion_remediar2  a
  left join planillas.medicos b on a.id_medico=b.id_medico
  where id_clasificacion=$id_planilla";
// VER AQUI TAMBIEN
    $res_factura = sql($query, "Error al traer el Comprobantes") or fin_pagina();

    $cuie = $res_factura->fields['cuie'];
    $clave = $res_factura->fields['clave_beneficiario'];
    $tipo_doc = $res_factura->fields['tipo_doc'];
    $num_doc = number_format($res_factura->fields['num_doc'], 0, '.', '');
    $apellido = $res_factura->fields['apellido'];
    $nombre = $res_factura->fields['nombre'];
    $fecha_nac = fecha($res_factura->fields['fecha_nac']);
    $fecha_carga = fecha($res_factura->fields['fecha_carga']);
    $bajo_prog = $res_factura->fields['bajo_prog'];
    
    $clasif_rapida= $res_factura->fields['clasif_rapida'];

    if ($clasif_rapida=='s') {$riesgo_global = '';
             $acv = '';
             $vas_per = '';
             $car_isq = '';
             $col310 = '';
             $col_ldl = '';
             $ct_hdl = '';
             $pres_art = '';
             $dmt2 = '';
             $insu_renal = '';
             $dmt_menor = '';
             $hta_menor = '';
             $dmt='';
             $hta = '';
             $tabaquismo = '';
             $ta_sist = '';
             $ta_diast = '';
             $col_tot = '';
             $menopausia = '';
             $antihiper = '';
             $obesi = '';
             $acv_prema = '';
             $trigli = '';
             $hdl_col = '';
             $hiperglu = '';
             $microalbu ='';
         }





        else {$riesgo_global = $res_factura->fields['rcvg'];
         $acv = $res_factura->fields['acv'];
         $vas_per = $res_factura->fields['vas_per'];
         $car_isq = $res_factura->fields['car_isq'];
         $col310 = $res_factura->fields['col310'];
         $col_ldl = $res_factura->fields['col_ldl'];
         $ct_hdl = $res_factura->fields['ct_hdl'];
         $pres_art = $res_factura->fields['pres_art'];
         $dmt2 = $res_factura->fields['dmt2'];
         $insu_renal = $res_factura->fields['insu_renal'];
         $dmt_menor = $res_factura->fields['dmt_menor'];
         $hta_menor = $res_factura->fields['hta_menor'];
         $dmt=$res_factura->fields['dmt'];
         $hta = $res_factura->fields['hta'];
         $tabaquismo = $res_factura->fields['tabaquismo'];
         $ta_sist = $res_factura->fields['ta_sist'];
         $ta_diast = $res_factura->fields['ta_diast'];
         $col_tot = $res_factura->fields['col_tot'];
         $menopausia = $res_factura->fields['menopausia'];
         $antihiper = $res_factura->fields['antihiper'];
         $obesi = $res_factura->fields['obesi'];
         $acv_prema = $res_factura->fields['acv_prema'];
         $trigli = $res_factura->fields['trigli'];
         $hdl_col = $res_factura->fields['hdl_col'];
         $hiperglu = $res_factura->fields['hiperglu'];
         $microalbu = $res_factura->fields['microalbu'];

         $peso = $res_factura->fields['peso'];
         $talla = $res_factura->fields['talla'];
         $imc = $res_factura->fields['imc'];
         $p_abdominal = $res_factura->fields['p_abd'];
     };
    

    $fecha_clasificacion=$res_factura->fields['fecha_control'];

    $apellido_medico = $res_factura->fields['apellido_medico'];
    $nombre_medico = $res_factura->fields['nombre_medico'];
    $dni_medico = $res_factura->fields['dni_medico'];
    $id_medico = $res_factura->fields['id_medico'];
	$fecha_prox_seguimiento = $res_factura->fields['fecha_prox_seguimiento'];
	$diabetico = trim($res_factura->fields['diabetico']);
	$hipertenso = trim($res_factura->fields['hipertenso']);
    $sedentarismo = $res_factura->fields['sedentarismo'];
}

echo $html_header;
?>
<script>
    $(document).ready(function() {
    
        $('body').on('click', 'input[name=acv]', function (e){
          if ($(this).is(':checked')) $('input:radio[name=riesgo_global]')[4].checked = true; 
             else $("input[name=riesgo_global]").attr('disabled', false);
        });

        $('body').on('click', 'input[name=vas_per]', function (e){
          if ($(this).is(':checked')) $('input:radio[name=riesgo_global]')[4].checked = true; 
             else $("input[name=riesgo_global]").attr('disabled', false);
        });

        $('body').on('click', 'input[name=car_isq]', function (e){
          if ($(this).is(':checked')) $('input:radio[name=riesgo_global]')[4].checked = true; 
             else $("input[name=riesgo_global]").attr('disabled', false);
        });

        $('body').on('click', 'input[name=col310]', function (e){
          if ($(this).is(':checked')) $('input:radio[name=riesgo_global]')[4].checked = true; 
             else $("input[name=riesgo_global]").attr('disabled', false);
        });

        $('body').on('click', 'input[name=col_ldl]', function (e){
          if ($(this).is(':checked')) $('input:radio[name=riesgo_global]')[4].checked = true; 
             else $("input[name=riesgo_global]").attr('disabled', false);
        });

        $('body').on('click', 'input[name=ct_hdl]', function (e){
          if ($(this).is(':checked')) $('input:radio[name=riesgo_global]')[4].checked = true; 
             else $("input[name=riesgo_global]").attr('disabled', false);
        });

        $('body').on('click', 'input[name=pres_art]', function (e){
          if ($(this).is(':checked')) $('input:radio[name=riesgo_global]')[4].checked = true; 
             else $("input[name=riesgo_global]").attr('disabled', false);
        });

        $('body').on('click', 'input[name=dmt2]', function (e){
          if ($(this).is(':checked')) $('input:radio[name=riesgo_global]')[4].checked = true; 
             else $("input[name=riesgo_global]").attr('disabled', false);
        });

        $('body').on('click', 'input[name=insu_renal]', function (e){
          if ($(this).is(':checked')) $('input:radio[name=riesgo_global]')[4].checked = true; 
             else $("input[name=riesgo_global]").attr('disabled', false);
        });

        $('body').on('click', 'input[name=dmt_menor]', function (e){
          if ($(this).is(':checked')) $('input:radio[name=riesgo_global]')[4].checked = true; 
             else $("input[name=riesgo_global]").attr('disabled', false);
        });

        $('body').on('click', 'input[name=hta_menor]', function (e){
          if ($(this).is(':checked')) $('input:radio[name=riesgo_global]')[4].checked = true; 
             else $("input[name=riesgo_global]").attr('disabled', false);
        });
    });

    $('body').on('click', 'input[name=riesgo_global]', function (e){
          if (($('input[name=acv]').is(':checked')
                || $('input[name=vas_per]').is(':checked')
                || $('input[name=car_isq]').is(':checked')
                || $('input[name=col310]').is(':checked')
                || $('input[name=col_ldl]').is(':checked')
                || $('input[name=ct_hdl]').is(':checked')
                || $('input[name=pres_art]').is(':checked')
                || $('input[name=dmt2]').is(':checked')
                || $('input[name=insu_renal]').is(':checked')
                || $('input[name=dmt_menor]').is(':checked')
                || $('input[name=hta_menor]').is(':checked')
                ) 
            && ($('input[name=riesgo_global]')[0].checked 
            || $('input[name=riesgo_global]')[1].checked
            || $('input[name=riesgo_global]')[2].checked)) {
                $('input[name=riesgo_global]')[4].checked = true
            }

    });
    


//controlan que ingresen todos los datos necesarios par el muleto
function calculo_imc(){
	var t=document.all.talla.value;
	var p=document.all.peso.value;
	var i=0;
	var peso=document.all.peso.value;
	var talla=document.all.talla.value;
	document.all.peso.value = peso.replace(',','.');
	document.all.talla.value = talla.replace(',','.');
	
   if(t!=0) i=(p/(t * t));
  	 var original=i;
	 var result=Math.round(i*100)/100 ;
	 document.all.imc.readonly = true;
	 document.all.imc.value=result; 
	 document.all.imc.readonly = false;	 
}

function control_nuevos()
    {
    if (document.all.peso.value=='') {
        alert("Debe Colocar un Valor PESO");
        document.all.peso.focus();
        return false;
    } 
    
    if (isNaN(document.all.peso.value)) {
        alert("El Valor de PESO debe ser un número real");
        document.all.peso.focus();
        return false;
    } 
    
    if (document.all.peso.value<=10 || document.all.peso.value>=250) {
        alert("El Valor de PESO debe estar entre 10 y 250");
        document.all.peso.focus();
        return false;
    }   

    if (document.all.talla.value=='') {
        alert("Debe Colocar un Valor TALLA");
        document.all.talla.focus();
        return false;
    }    
    
    if (isNaN(document.all.talla.value)) {
        alert("El valor de TALLA debe ser un número entero");
        document.all.talla.focus();
        return false;
    }
            
    if (document.all.talla.value<=0.30 || document.all.talla.value>=2.50) {
        alert("El valor de TALLA debe estar comprendido entre 0.30 y 2.50");
        document.all.talla.focus();
        return false;
    }
    
    
    if (document.all.p_abdominal.value=='') {
        alert("Debe Colocar un Valor Perimetro Abdominal");
        document.all.p_abdominal.focus();
        return false;
    }

    if (isNaN(document.all.p_abdominal.value)) {
        alert("El Valor de Perimetro Abdominal debe ser un número real");
        document.all.p_abdominal.focus();
        return false;
    } 

    if (document.all.p_abdominal.value<50) {
        alert("El Valor de Perimetro Abdominal debe estar comprendido entre 50 y 200 cm");
        document.all.p_abdominal.focus();
        return false;
    } 

    if (document.all.p_abdominal.value>200) {
        alert("El Valor de Perimetro Abdominal debe estar comprendido entre 50 y 200 cm");
        document.all.p_abdominal.focus();
        return false;
    } 

    if (document.all.ta_diast.value=='') {
        alert("Debe Colocar un Valor TA Diástole");
        document.all.ta_diast.focus();
        return false;
    }

    if (isNaN(document.all.ta_diast.value)) {
        alert("El Valor de TA Diástole debe ser un número Entero");
        document.all.ta_diast.focus();
        return false;
    } 

    if (document.all.ta_sist.value=='') {
        alert("Debe Colocar un Valor TA Sístole");
        document.all.ta_sist.focus();
        return false;
    }

    if (isNaN(document.all.ta_sist.value)) {
        alert("El Valor de TA Sístole debe ser un número Entero ");
        document.all.ta_sist.focus();
        return false;
    } 
    
    
    if (document.all.tabaquismo.value=='-1') {
        alert("Debe Colocar un Valor dentro del campo TABAQUISMO");
            document.all.tabaquismo.focus();
            return false;
        }

    if(document.all.col_tot.value=='' || document.all.col_tot.value=='0'){
            alert("Debe Colocar un Valor de Coleserol Total Mayor a 100");
            document.all.col_tot.focus();
            return false;
        }

        if(document.all.col_tot.value<100){
            alert("Debe Colocar un Valor de Coleserol Total Mayor a 100");
            document.all.col_tot.focus();
            return false;
        }


        if (document.all.es_cuie.value=='si') {
            if(document.all.col_tot.value=='' || document.all.col_tot.value=='0'){
                alert("Debe Colocar un Valor de Coleserol Total Mayor a 100");
                document.all.col_tot.focus();
                return false;
            }

            if(document.all.col_tot.value<100){
                alert("Debe Colocar un Valor de Coleserol Total Mayor a 100");
                document.all.col_tot.focus();
                return false;
            }

            if(document.all.riesgo_global[0].checked==false && document.all.riesgo_global[1].checked==false && document.all.riesgo_global[2].checked==false && document.all.riesgo_global[3].checked==false
                 && document.all.riesgo_global[4].checked==false){
                alert("Debe seleccionar al menos una opcion en RCVG");
                //document.all.nro_clasificacion.focus();
                return false;
            }

        }
        else {

            if (document.all.bajo_prog.checked==false){
                if(document.all.col_tot.value<100 || document.all.col_tot.value=='0'){
                    alert("Debe Colocar un Valor de Coleserol Total Mayor a 100");
                    document.all.col_tot.focus();
                    return false;
                }
                if(document.all.riesgo_global[0].checked==false && document.all.riesgo_global[1].checked==false && document.all.riesgo_global[2].checked==false && document.all.riesgo_global[3].checked==false && document.all.riesgo_global[4].checked==false){
                    alert("Debe seleccionar al menos una opcion en RCVG");
                    //document.all.nro_clasificacion.focus();
                    return false;
                }
            }
        }

         if(document.all.fecha_clasificacion.value==""){
    		alert("Debe completar la Fecha de la Clasificacion");
    		document.all.fecha_clasificacion.focus();
    		return false;
    	 }
    	 if(document.all.fecha_prox_seguimiento.value==""){
    		alert("Debe completar la Fecha de Proximo Seguimiento");
    		document.all.fecha_prox_seguimiento.focus();
    		return false;
    	 }
         
        //ta_sist
		/*
		alert(document.all.ta_sist.value);
        if((document.all.ta_sist.value!='0')&&(document.all.ta_sist.value!='')&&(document.all.ta_sist.value<80 || document.all.ta_sist.value>220)){ 
            alert('Debe completar la presion arterial con datos validos');
            return false;
        }

        //ta_diast
        if((document.all.ta_diast.value!='')&&(document.all.ta_diast.value<50 || document.all.ta_diast.value>130)){ 
            alert('Debe completar la presion con datos validos');
            return false;
        }

        //col_tot
        if((document.all.col_tot.value!='')&&(document.all.col_tot.value<80)){ 
            alert('Debe completar el colesterol con datos validos');
            return false;
        }*/
        /* RCVG*/
        
        
        
        var dni_medico=document.all.dni_medico.value;
        if(dni_medico.replace(/^\s+|\s+$/g,"")==""){
            alert("Debe completar el campo Num. Doc. Medico");
            document.all.dni_medico.focus();
            return false;
        }else{
            var dni_medico=document.all.dni_medico.value;
            if(isNaN(dni_medico)){
                alert('El dato ingresado en numero de formulario debe ser entero');
                document.all.dni_medico.focus();
                return false;
            }
        }
				 
        var apellido_medico=document.all.apellido_medico.value;
        if(apellido_medico.replace(/^\s+|\s+$/g,"")==""){
            alert("Debe completar el campo Apellido Medico");
            document.all.apellido_medico.focus();
            return false;
        }else{
            var charpos = document.all.apellido_medico.value.search("/[^A-Za-z\s]/");
            if( charpos >= 0)
            {
                alert( "El campo Apellido Medico solo permite letras ");
                document.all.apellido_medico.focus();
                return false;
            }
        }	
        var nombre_medico=document.all.nombre_medico.value;
        if(nombre_medico.replace(/^\s+|\s+$/g,"")==""){
            alert("Debe completar el campo nombre Medico");
            document.all.nombre_medico.focus();
            return false;
        }else{
            var charpos = document.all.nombre_medico.value.search("/[^A-Za-z\s]/");
            if( charpos >= 0)
            {
                alert( "El campo Nombre Medico solo permite letras ");
                document.all.nombre_medico.focus();
                return false;
            }
        }

        
    }//de function control_nuevos()


    /**********************************************************/
    //funciones para busqueda abreviada utilizando teclas en la lista que muestra los clientes.
    var digitos=10; //cantidad de digitos buscados
    var puntero=0;
    var buffer=new Array(digitos); //declaración del array Buffer
    var cadena="";

    function buscar_combo(obj)
    {
        var letra = String.fromCharCode(event.keyCode)
        if(puntero >= digitos)
        {
            cadena="";
            puntero=0;
        }   
        //sino busco la cadena tipeada dentro del combo...
        else
        {
            buffer[puntero]=letra;
            //guardo en la posicion puntero la letra tipeada
            cadena=cadena+buffer[puntero]; //armo una cadena con los datos que van ingresando al array
            puntero++;

            //barro todas las opciones que contiene el combo y las comparo la cadena...
            //en el indice cero la opcion no es valida
            for (var opcombo=1;opcombo < obj.length;opcombo++){
                if(obj[opcombo].text.substr(0,puntero).toLowerCase()==cadena.toLowerCase()){
                    obj.selectedIndex=opcombo;break;
                }
            }
        }//del else de if (event.keyCode == 13)
        event.returnValue = false; //invalida la acción de pulsado de tecla para evitar busqueda del primer caracter
    }//de function buscar_op_submit(obj)
    /**********************************************************/
    //Validar Fechas
    function esFechaValida(fecha){
        if (fecha != undefined && fecha.value != "" ){
            if (!/^\d{2}\/\d{2}\/\d{4}$/.test(fecha.value)){
                alert("formato de fecha no valido (dd/mm/aaaa)");
                return false;
            }
            var dia  =  parseInt(fecha.value.substring(0,2),10);
            var mes  =  parseInt(fecha.value.substring(3,5),10);
            var anio =  parseInt(fecha.value.substring(6),10);
 
            switch(mes){
                case 1:
                case 3:
                case 5:
                case 7:
                case 8:
                case 10:
                case 12:
                    numDias=31;
                    break;
                case 4: case 6: case 9: case 11:
                                numDias=30;
                                break;
                            case 2:
                                if (comprobarSiBisisesto(anio)){ numDias=29 }else{ numDias=28};
                                break;
                            default:
                                alert("Fecha introducida err&oacute;nea");
                                return false;
                        }
 
                        if (dia>numDias || dia==0){
                            alert("Fecha introducida err&oacute;nea");
                            return false;
                        }
                        return true;
                    }
                }
 
                function comprobarSiBisisesto(anio){
                    if ( ( anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0))) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                /**********************************************************/
                var patron = new Array(2,2,4)
                var patron2 = new Array(5,16)
                function mascara(d,sep,pat,nums){
                    if(d.valant != d.value){
                        val = d.value
                        largo = val.length
                        val = val.split(sep)
                        val2 = ''
                        for(r=0;r<val.length;r++){
                            val2 += val[r]
                        }
                        if(nums){
                            for(z=0;z<val2.length;z++){
                                if(isNaN(val2.charAt(z))){
                                    letra = new RegExp(val2.charAt(z),"g")
                                    val2 = val2.replace(letra,"")
                                }
                            }
                        }
                        val = ''
                        val3 = new Array()
                        for(s=0; s<pat.length; s++){
                            val3[s] = val2.substring(0,pat[s])
                            val2 = val2.substr(pat[s])
                        }
                        for(q=0;q<val3.length; q++){
                            if(q ==0){
                                val = val3[q]

                            }
                            else{
                                if(val3[q] != ""){
                                    val += sep + val3[q]
                                }
                            }
                        }
                        d.value = val
                        d.valant = val
                    }
                }

</script>
<style type="text/css">
    <!--
    .Estilo1 {
        font-size: large;
        color: #FF6633;
    }
    -->
</style>
<? // echo $tema.'*'.$id_nomenclador_detalle.'*'.$cuie.'*'.$fecha_comprobante.'*'.$clave_beneficiario.'*'.$fecha_nacimiento.'*'.$sexo_codigo.'*'.$edad.'*'.$prestacion.'*'.$tema.'*'.$patologia.'*'.$profesional.'*'.$pagina_viene.'*'.$id_comprobante; ?>

<form name='form1' action='remediar_carga.php' method='POST'>
    <input type="hidden" value="<?php echo  $id_planilla ?>" name="id_planilla">
    <input type="hidden" value="<?php echo  $pagina ?>" name="pagina">
    <input type="hidden" value="<?php echo  $id_smiafiliados ?>" name="id_smiafiliados">
    <input type="hidden" value="<?php echo  $pagina_viene ?>" name="pagina_viene">
    <input type="hidden" value="<?php echo  $tema ?>" name="tema">
    <input type="hidden" value="<?php echo  $id_nomenclador_detalle ?>" name="id_nomenclador_detalle">
    <input type="hidden" value="<?php echo  $fecha_comprobante ?>" name="fecha_comprobante">
    <input type="hidden" value="<?php echo  $clave_beneficiario ?>" name="clave_beneficiario">
    <input type="hidden" value="<?php echo  $fecha_nacimiento ?>" name="fecha_nacimiento">
    <input type="hidden" value="<?php echo  $sexo_codigo ?>" name="sexo_codigo">
    <input type="hidden" value="<?php echo  $edad ?>" name="edad">
    <input type="hidden" value="<?php echo  $prestacion ?>" name="prestacion">
    <input type="hidden" value="<?php echo  $tema ?>" name="tema">
    <input type="hidden" value="<?php echo  $patologia ?>" name="patologia">
    <input type="hidden" value="<?php echo  $profesional ?>" name="profesional">
    <input type="hidden" value="<?php echo  $id_comprobante ?>" name="id_comprobante">
    <input type="hidden" value="<?php echo  $es_cuie ?>" name="es_cuie">
<?php echo "<center><b><font size='+1' color='Blue'>".$accion2."</font></b></center>"; ?>
<?php echo "<center><b><font size='+1' color='red'>".$accion.' '.$accion_fichero."</font></b></center>"; ?> 
    
    <table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo  $bgcolor_out ?>' class="bordes">
        <tr>
        <tr id="mo">
            <td align="center" colspan="4" >
                <b> N&uacute;mero de Clasificacion </b><input type="text" maxlength="10" name="nro_clasificacion" value="<?php echo  $nro_clasificacion ?>" disabled> 
        </tr>
        <tr id="mo">
            <td>
                <?
                if (!$id_planilla) {
                    ?>  
                    <font size=+1><b>Nuevo Dato</b></font>   
                <?
                } else {
                    ?>
                    <font size=+1><b>Dato</b></font>   
                <? } ?>
            </td>
        </tr>

        <!--********************
        Comienzo del formulario
        ************************-->  

        <tr >
            <td>
                <table width=95% align="center" class="bordes">
                    <tr>
                        <td id=mo colspan="2">
                            <b> Descripci&oacute;n de la PLANILLA</b>
                        </td>
                    </tr>
                </table>



                <table width=95% align="center" class="bordes" >
                    <tr>	           
                        <td align="center" colspan="2">
                            <b> N&uacute;mero del Dato: <font size="+1" color="Red"><?php echo ($id_planilla) ? $id_planilla : "Nuevo Dato" ?></font> </b><label></label>
                        </td>
                    </tr>
                    <tr>	           
                        <td align="center" colspan="2">
                            <b><font size="2" color="Red">Nota: Los valores numericos se ingresan SIN separadores de miles, y con "." como separador DECIMAL</font> </b>           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left:10px">
                            <b>Clave Beneficiario:</b><?php echo  $clave ?><input type="hidden" value="<?php echo  $clave ?>" name="clave" />
                        </td>
                    </tr> 
                    <tr >
                        <td style="padding-left:10px">
                            <b>Efector de Clasificacion:</b> <?
                            $sql = "select * from facturacion.smiefectores where cuie='$cuie'";
                            $res_efectores = sql($sql) or fin_pagina();
                            if ($res_efectores->fields['cuie']!='') echo $res_efectores->fields['cuie'] . '-' . $res_efectores->fields['nombreefector'];
                            else echo 'Esperando Clasificacion';?>
                        </td>
                        <td style="padding-left:10px">

                            <b>Fecha de Emp.:</b><?php echo  $fechaempadronamiento ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left:10px">
                            <b>Apellido:</b><?php echo  $apellido ?><input type="hidden" value="<?php echo  $apellido ?>" name="apellido" readonly />
                            &nbsp;&nbsp;
                            <b>Nombre:</b><?php echo  $nombre ?><input type="hidden" value="<?php echo  $nombre ?>" name="nombre" readonly/>
                            &nbsp;&nbsp;
                            <b>Clase de Doc.:</b><? if ($clase_doc == 'P')
                                echo "Propio";
                            if ($clase_doc == 'A')
                                echo "Ajeno" ?><input type="hidden" value="<?php echo  $clase_doc ?>" name="clase_doc" />
                            &nbsp;&nbsp;
                            <b>Tipo de Doc.:</b> <?php echo  $tipo_doc ?><input type="hidden" value="<?php echo  $tipo_doc ?>" name="tipo_doc" readonly/>


                        </td>
                    </tr> 
                    <tr>
                        <td colspan="2" style="padding-left:10px">

                            <b>Nro. de Doc.:</b> <?php echo  $num_doc ?><input type="hidden" value="<?php echo  $num_doc ?>" name="num_doc" />
                            &nbsp;&nbsp;
                            <b>Fecha de Nacimiento:</b>	<?php echo  $fecha_nac ?><input type="hidden" value="<?php echo  $fecha_nac ?>" name="fecha_nac" />
                            &nbsp;&nbsp;
                            <b>Edad-A&ntilde;os:</b><?php echo  substr($fechaempadronamiento, 6, 10) - substr($fecha_nac, 6, 10) ?><input type="hidden" value="<?php echo  substr($fechaempadronamiento, 6, 10) - substr($fecha_nac, 6, 10) ?>" name="nino_edad" />
                            &nbsp;&nbsp;
                            <b>Sexo:</b> <? if ($sexo == 'F')
                                echo "Femenino";
                            if ($sexo == 'M')
                                echo "Masculino"; ?>

                        </td>                  
                    </tr>     
                    <tr>
                        <td colspan="2" style="padding-left:10px">

                            <b> Datos Cobertura: </b><?php echo  $os ?>  <?php echo  $cual_os ?>	
                            &nbsp;&nbsp;
                            <b>Telefono:</b>	<?php echo  $telefono ?>
                            &nbsp;&nbsp;
                            <b>Provincia:</b><?php echo  $provincia_nac ?>
                            &nbsp;&nbsp;
                            <b>Departamento:</b> <?php echo  $departamento ?>

                        </td>                  
                    </tr>  
                    <tr>
                        <td colspan="2" style="padding-left:10px">					  
                            <b> Municipio: </b><?php echo  $municipio ?>
                            &nbsp;&nbsp;
                            <b> Localidad: </b><?php echo  $localidad ?>
                            &nbsp;&nbsp;
                            <b> Calle-Ruta: </b><?php echo  $calle ?>
                            &nbsp;&nbsp;
                            <b> Nro. de Puerta: </b><?php echo  $numero_calle ?>

                        </td>                  
                    </tr> 
                    <tr>
                        <td colspan="2" style="padding-left:10px">
                            <b> Barrio: </b><?php echo  $barrio ?>
                            &nbsp;&nbsp;
                            <b> Mza.: </b><?php echo  $manzana ?>
                            &nbsp;&nbsp;
                            <b> Piso: </b><?php echo  $piso ?>
                            &nbsp;&nbsp;
                            <b> Casa-Depto: </b><?php echo  $dpto ?>
                            </td>                  
                    </tr>      
                </table>   
            </td>
        </tr>       


        </td>
        </tr>

		<tr>
            <td colspan="2">
                <table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo  $bgcolor_out ?>' class="bordes">
                    <tr>
                        <td align="center" id='ma'>
                            <button style="font-size:9px;" onclick="window.open('busca_medico.php','Buscar','dependent:yes,width=900,height=700,top=1,left=60,scrollbars=yes');" <?php echo  $desabil ?>>Buscar</button>
                            <b>Datos del Medico</b> 
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding-left:10px">
                            <input type="hidden" size="30" value="<?php echo  $id_medico ?>" name="id_medico" maxlength="50" >
                            <b>Apellido:</b>         	
                            <input type="text" size="30" value="<?php echo  $apellido_medico ?>" name="apellido_medico" maxlength="50" readonly >          
                            &nbsp;
                            <b>Nombre:</b>     
                            <input type="text" size="30" value="<?php echo  $nombre_medico ?>" name="nombre_medico" maxlength="50" readonly>
                            &nbsp;
                            <b>Doc. Medico:</b>         
                            <input type="text" size="16" value="<?php echo  $dni_medico ?>" name="dni_medico" maxlength="12" readonly> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
		
        <!--********************
        Comienzo del 1body del formulario
        ************************-->  
        <td>
            <table width=95% align="center" class="bordes" style="margin-top:5px">
                <table width=95% align="center" class="bordes" >
                    <tr>
                        <td id=mo colspan="2">
                            <b> Evaluaci&oacute;n y Clasificaci&oacute;n Cardiovascular</b>
                        </td>
                    </tr>
                    <tr><td colspan="2" style="padding-left:10px"><b>En los siguientes casos NO es necesario la UTILIZACION DE LA TABLA DE PREDICCION DE RCVG</b></td></tr>
                    <tr><td colspan="2" style="padding-left:10px">MENOR DE 39 A&NtildeOS - Evaluar presencia de factores de riesgo y estilos de vida</td></tr>
                    <td><table class="bordes">
                            <tr><td colspan="2" style="padding-top:5px;padding-left:10px"><input type="checkbox" name="acv"<?php if (strtoupper($acv) == 1)
                                echo "checked"; ?>  <?php echo  $desabil ?>/> Accidente Cerebrovascular (ACV)
                                    &nbsp;</td></tr>
                            <tr><td colspan="2" style="padding-left:10px"><input type="checkbox" name="vas_per"<?php if (strtoupper($vas_per) == 1)
                                echo "checked";?>  <?php echo  $desabil ?>/> Vasculopat&iacute;a perif&eacute;rica
                                    &nbsp;</td></tr>
                            <tr><td colspan="2" style="padding-left:10px"><input type="checkbox" name="car_isq"<?php if (strtoupper($car_isq) == 1)
                                echo "checked";?>  <?php echo  $desabil ?>/> Cardiopat&iacute;a isqu&eacute;mica
                                    &nbsp;</td></tr>
                            <tr><td colspan="2" style="padding-left:10px"><input type="checkbox" name="col310"<?php if (strtoupper($col310) == 1)
                                echo "checked";?>  <?php echo  $desabil ?>/> Colesterol total >= 310 mg/dl
                                    &nbsp;</td></tr>
                            <tr><td colspan="2" style="padding-left:10px"><input type="checkbox" name="col_ldl"<?php if (strtoupper($col_ldl) == 1)
                                echo "checked"; ?>  <?php echo  $desabil ?>/> Colesterol LDL >= 230 mg/dl
                                    &nbsp;</td></tr>
                            <tr><td colspan="2" style="padding-left:10px"><input type="checkbox" name="ct_hdl"<?php if (strtoupper($ct_hdl) == 1)
                                echo "checked";?>  <?php echo  $desabil ?>/> Relaci&oacute;n CT/HDL > 8
                                    &nbsp;</td></tr>
                            <tr><td colspan="2" style="padding-left:10px"><input type="checkbox" name="pres_art"<?php if (strtoupper($pres_art) == 1)
                                echo "checked";?>  <?php echo  $desabil ?>/> Cifras de Presi&oacute;n Arterial permanente elevadas > 160-170 nm Hg de sist&oacute;lica y 100-105 nm Hg de diast&oacute;lica.
                                    &nbsp;</td></tr>
                            <tr><td colspan="2" style="padding-left:10px"><input type="checkbox" name="dmt2"<?php if (strtoupper($dmt2) == 1)
                                echo "checked";?>  <?php echo  $desabil ?>/> DMT tipo 2 con nefropat&iacute;a manifiesta, deterioro o insuficiencia de la funci&oacute;n renal.
                                    &nbsp;</td></tr>
                            <tr><td colspan="2" style="padding-left:10px"><input type="checkbox" name="insu_renal"<?php if (strtoupper($insu_renal) == 1)
                                echo "checked"; ?>  <?php echo  $desabil ?>/> Con insuficiencia renal o deterioro de la funci&oacute;n renal
                                    &nbsp;</td></tr>
                            <tr><td colspan="2" style="padding-left:10px"><input type="checkbox" name="dmt_menor"<?php if (strtoupper($dmt_menor) == 1)
                                echo "checked";?>  <?php echo  $desabil ?>/> Menor de 39 a&ntilde;os con DMT tipo 2 
                                    &nbsp;</td></tr>
                            <tr><td colspan="2" style="padding-left:10px"><input type="checkbox" name="hta_menor"<?php if (strtoupper($hta_menor) == 1)
                                echo "checked";?>  <?php echo  $desabil ?>/> Menor de 39 a&ntilde;os con HTA que requiere tratamiento farmacol&oacute;gico 
                                    &nbsp;</td></tr>
                            <tr><td style="padding-top:5px;padding-left:10px"><b>Quedan excluidas Diabetes gestacional e Hipertensi&oacute;n del embarazo.</b></td></tr>
                        </table></td>
                </table>

                <!--********************
                    Comienzo del 2body del formulario
                    ************************-->  

                <tr>
                    <td>                       
                        <table width=95% align="center" class="bordes" style="margin-top:5px">
                            <tr>
                                <td colspan="2">
                            <tr>
                                <td align="center" id='mo' colspan="2"><b>Clasificaci&oacute;n con utilizaci&oacute;n de tabla de predicci&oacute;n RCVG</b></td>
                            </tr>
                            <tr>
                                <td align="left" style="padding-left:10px">
                                    <div style="display:inline;">
                                        <b title="Sin DMT">Sin DMT</b> 
                                        <input type="radio" value="0" name="dmt" <?php if (strtoupper($dmt) == "0")
                                echo "checked"; ?> title="Sin DMT"  <?php echo  $desabil ?>/>
                                        &nbsp;
                                        &nbsp;
                                        <b title="DMT 1">DMT1</b> 
                                        <input type="radio" value="1" name="dmt" <?php if (strtoupper($dmt) == "1")
                                echo "checked"; ?> title="DMT 1"  <?php echo  $desabil ?>/>
                                        &nbsp;
                                        &nbsp;
                                        <b title="DMT 2">DMT2</b>
                                        <input style="margin-right:5px" type="radio" value="2" name="dmt" <?php if (strtoupper($dmt) == "2")
                                echo "checked"; ?>  title="DMT 2" <?php echo  $desabil ?>/>
                                        &nbsp;
                                        &nbsp;
                                        <b title="Hipertension Arterial">HTA</b>
                                        <input type="checkbox" name="hta" <?php if (strtoupper($hta) == 1)
                                echo "checked"; ?>  title="Hipertension Arterial"  <?php echo  $desabil ?>/>
                                        </br>
                                        </br> 
                                        <b title="Peso">Peso</b>
                                        <input type="text" value="<?php echo $peso ?>" name="peso" size="6" style="font-size:9px;" maxlength="6" title="Peso" <?php echo  $desabil ?> placeholder="Kg"/>
                                        &nbsp;
                                        <b title="Talla">Talla</b>
                                        <input type="text" value="<?php echo $talla ?>" name="talla" size="4" style="font-size:9px;" maxlength="4" title="Talla" <?php echo  $desabil ?> placeholder="Mts" onchange="calculo_imc()"/>
                                        &nbsp;
                                        <b title="IMC">IMC</b>
									    <input type="text" value="" name="imc" size="6" style="font-size:9px;" maxlength="5" title="IMC" onfocus="calculo_imc()" readonly/>
                                        &nbsp;
                                        <b title="Perimetro Abdominal">P. ABD</b>
									    <input type="text" value="<?php echo  $p_abdominal ?>" name="p_abdominal" size="6" style="font-size:9px;" maxlength="5" title="Perimetro Abdominal" placeholder="Cm"/>
									    &nbsp;
                                        <b title="Control de la Presion Arterial Sistolica">TA Sist</b>
                                        <input type="text" value="<?php echo  $ta_sist ?>" name="ta_sist" size="3" style="font-size:9px;" maxlength="3" title="Control de la Presion Arterial Sistolica" <?php echo  $desabil ?>/>
                                        &nbsp;
                                        <b title="Control de la Presion Arterial Diastolica">TA Diast</b>
                                        <input type="text" value="<?php echo  $ta_diast ?>" name="ta_diast" size="3" style="font-size:9px;" maxlength="3" title="Control de la Presion Arterial Diastolica"<?php echo  $desabil ?>/>
                                        </br>
                                        </br>
                                        <b>Tabaquismo</b>
                                        <!--<input type="checkbox" name="tabaquismo"<?php if (strtoupper($tabaquismo) == 1)
                                echo "checked"; ?>  <?php echo  $desabil ?>/>-->
                                        <select name="tabaquismo">
                                          <option value='-1' <?php if ($tabaquismo <> 1 or $tabaquismo <> 2 )
                                echo "selected"; ?> disabled>Seleccione</option>
                                          <option value='SI' <?php if (strtoupper($tabaquismo) == 1)
                                echo "selected"; ?>>SI</option>
                                          <option value='NO' <?php if (strtoupper($tabaquismo) == 0)
                                echo "selected"; ?>>NO</option>
                                        </select>

                                        &nbsp;

                                        <b title="Control del colesterol Total">Col. Tot.</b>
                                        <input type="text" value="<?php echo  $col_tot ?>" name="col_tot" size="3" style="font-size:9px;" maxlength="3" title="Control del colesterol Total" <?php echo  $desabil ?>/>mg/dl
                                        &nbsp;
                                    </div>
							
							<tr><td><table class="bordes">
                                        <tr><td colspan="2" style="padding-left:10px"><input type="checkbox" name="sedentarismo"<?php echo  $desabil ?>/>Realiza habitualmente al menos 30 min. de actividad fisica en el trabajo y/o en el tiempo libre.&nbsp;</td></tr>
                                        <tr><td align="left" style="padding-left:10px"><input type="checkbox" name="meno_prema"<?php if (strtoupper($meno_prema) == 1)
                                echo "checked"; ?>  <?php echo  $desabil ?>/> Menopausia prematura ( < 35 a&ntilde;os )
                                                &nbsp;</td></tr>
                                        <tr><td align="left" style="padding-left:10px"><input type="checkbox" name="antihiper"<?php if (strtoupper($antihiper) == 1)
                                echo "checked"; ?>  <?php echo  $desabil ?>/> Pacientes en tratamiento antihipertensivo
                                                &nbsp;</td></tr>
                                        <tr><td align="left" style="padding-left:10px"><input type="checkbox" name="obesi"<?php if (strtoupper($obesi) == 1)
                                echo "checked"; ?>  <?php echo  $desabil ?>/> Obesidad ( IMC >= 30 )
                                                &nbsp;</td></tr>
                                        <tr><td align="left" style="padding-left:10px"><input type="checkbox" name="acv_prema"<?php if (strtoupper($acv_prema) == 1)
                                            echo "checked"; ?>  <?php echo  $desabil ?>/> Antecedentes familiares de cardiopat&iacute;a isqu&eacute;mica o de ACV prematuro ( Hombre < 55 a&ntilde;os; mujer < 65 a&ntilde;os )
                                                &nbsp;</td></tr>
                                        <tr><td align="left" style="padding-left:10px"><input type="checkbox" name="trigli"<?php if (strtoupper($trigli) == 1)
                                echo "checked"; ?>  <?php echo  $desabil ?>/> Concentraci&oacute;n elevada de triglic&eacute;ridos ( > 180 mg/dl)
                                                &nbsp;</td></tr>
                                        <tr><td align="left" style="padding-left:10px"><input type="checkbox" name="hdl_col"<?php if (strtoupper($hdl_col) == 1)
                                echo "checked"; ?>  <?php echo  $desabil ?>/> Concentraci&oacute;n baja de colesterol HDL (Hombres < 40 mg/dl; Mujeres < 50 mg/dl)
                                                &nbsp;</td></tr>
                                        <tr><td align="left" style="padding-left:10px"><input type="checkbox" name="hiperglu"<?php if (strtoupper($hiperglu) == 1)
                                echo "checked"; ?>  <?php echo  $desabil ?>/> Hiperglucemia en ayunas o intolerancia a la glucosa
                                                &nbsp;</td></tr>
                                        <tr><td align="left" style="padding-left:10px"><input type="checkbox" name="microalbu"<?php if (strtoupper($microalbu) == 1)
                                echo "checked"; ?>  <?php echo  $desabil ?>/> Microalbuminuria
                                                &nbsp;</td></tr>
                                    </table></td></tr>
                </tr>
            </table>
        </td>
        </tr>

        <!--********************
          Comienzo del footer del formulario
          ************************-->  
        <tr>
                <td><table width=95% align="center" class="bordes" style="margin-top:5px">
                        <tr><td><div style="background-color:#A2A2A2"><b>Bajo Programa</b>                                
                                    <input type="checkbox" name="bajo_prog"<?php if (strtoupper($bajo_prog) == 1) echo "checked"; ?>
                                </div>
                            </td>
                        </tr>                    
                </table></td>
         </tr>
        

        <tr>
            <td><table width=95% align="center" class="bordes" style="margin-top:5px">
                    <tr><td><div style="background-color:#A2A2A2"><b>Riesgo Cardiovascular Global</b>                                
                                <input style="margin-left:10px" type="radio" value="bajo" name="riesgo_global" <?php if (strtoupper($riesgo_global) == "BAJO")                                    
                                echo "checked"; ?> title="Riesgo Bajo"  <?php echo  $desabil ?>/> Bajo < 10%
                                <input style="margin-left:10px" type="radio" value="mode" name="riesgo_global" <?php if (strtoupper($riesgo_global) == "MODE")
                                echo "checked"; ?> title="Riesgo Moderado"  <?php echo  $desabil ?>/> Moderado 10% a < 20% 
                                <input style="margin-left:10px" type="radio" value="alto" name="riesgo_global" <?php if (strtoupper($riesgo_global) == "ALTO")
                                echo "checked"; ?> title="Riesgo Alto"  <?php echo  $desabil ?>/> Alto 20% a < 30% 
                                <input style="margin-left:10px" type="radio" value="malto" name="riesgo_global" <?php if (strtoupper($riesgo_global) == "MALTO")
                                echo "checked"; ?> title="Riesgo Muy Alto"  <?php echo  $desabil ?>/> Muy Alto > 30% 
                                <input style="margin-left:10px" type="radio" value="per se" name="riesgo_global" <?php if (strtoupper($riesgo_global) == "PER SE")
                                echo "checked"; ?> title="Riesgo PER SE"  <?php echo  $desabil ?>/> PER SE 
                            </div>
                        </td>
                    </tr>
                    <tr>
                    	<td style="padding-top:5px;padding-left:15px" align="left"><? if($fecha_clasificacion=='') $fecha_clasificacion = date("d/m/Y"); ?>
                            <b>Fecha de clasificaci&oacute;n: </b><input type='text' id='fecha_clasificacion' name='fecha_clasificacion' value='<?php //echo  Fecha($fecha_clasificacion); ?>' size=15 onKeyUp="mascara(this,'/',patron,true);" onblur="esFechaValida(this);" <?php echo  $desabil ?>/>
        					<?php echo link_calendario("fecha_clasificacion"); ?>
                            &nbsp;&nbsp;                            
                            &nbsp;&nbsp;
                            <? if($fecha_prox_seguimiento=='') $fecha_prox_seguimiento = ''; ?>
                            <b>Fecha de Proximo Seguimiento: </b><input type='text' id='fecha_prox_seguimiento' name='fecha_prox_seguimiento' value='<?php //echo  Fecha($fecha_prox_seguimiento); ?>' size=15 onKeyUp="mascara(this,'/',patron,true);" onblur="esFechaValida(this);" <?php echo  $desabil ?>/>
        					<?php echo link_calendario("fecha_prox_seguimiento"); ?>
                            &nbsp;&nbsp; 
                        </td>
                            
                   </tr>
                </table></td>
        </tr>
		
		<tr>
        <td><table width=95% align="center" class="bordes" style="margin-top:5px">
        <tr>               
           <td align="center" id="mo" colspan="2">
            <b> Efector Clasificacion </b>
           </td>
         </tr>
         
         <tr>
            <td align="right" width="20%" >
                <b>Efector:</b>
            </td>
            <td align="left" width="30%" colspan="2">               
             <select name=cuie Style="width=300px" 
                onKeypress="buscar_combo(this);"
                onblur="borrar_buffer();"
                onchange="borrar_buffer();" 
                 
             <option value=-1>Seleccione</option>
             <?
             if ($cuie=='') $cuie=$cuie_centro_inscriptor; //lo uso en caso que este vacio
             $sql= "select * from nacer.efe_conv order by nombre";
             $res_efectores=sql($sql) or fin_pagina();
             while (!$res_efectores->EOF){ 
                $cuiel=$res_efectores->fields['cuie'];
                $nombre_efector=$res_efectores->fields['nombre'];
                
                ?>
                <option value='<?php echo $cuiel?>' <?if ($cuie==$cuiel) echo "selected"?> ><?php echo $cuiel." - ".$nombre_efector?></option>
                <?
                $res_efectores->movenext();
                }?>
            </select>
            </td>                               
        </tr>
        </table>
        </td>
        </tr>

        <!--
		<tr>
            <td><table width=95% align="center" class="bordes" style="margin-top:5px">
				<tr><td><div id="mo" style="background-color:#4864DB"><b>Registro Para META</b>
									
				<tr><td><table>
					<td align="right">
                           <b title="Diabetico">Diabetico:</b>
                        <select name="diabetico" title="diabetico">
                            <option value='-1' <?php echo ($diabetico=='')?'selected':'';?>>Seleccione</option>
                            <option value='SI' <?php echo ($diabetico=='SI')?'selected':'';?>>SI</option>
                            <option value='NO' <?php echo ($diabetico!='SI')?'selected':'';?>>NO</option>
                        </select>
                        
                        <b title="Hipertension Arterial">Hipertenso:</b>
                        <select name="hipertenso" title="Hipertension Arterial">
                          <option value='-1' <?php echo ($hipertenso=='')?'selected':'';?>>Seleccione</option>
                          <option value='SI' <?php echo ($hipertenso=='SI')?'selected':'';?>>SI</option>
                          <option value='NO' <?php echo ($hipertenso!='SI')?'selected':'';?>>NO</option>
                        </select>										    					    
				</table></td></tr>
				</div></td></tr>
		</table></td>
        </tr>
        -->
        

        <? //if (!($id_planilla) && $clave!=''){?>
        <table class="bordes" align="center" width="100%">
            <tr align="center" id="sub_tabla">
                <td>	
                    <b>Guarda Planilla</b>
                </td>
            </tr>


            <tr align="center">
            <td>
            <?php if ($id_planilla and $clasif_rapida<>'s')  $disabled = 'disabled';
                else $disabled = '';?>
            <input type='submit' class="btn btn-info" name='guardar' value='Guardar Planilla' onclick="return control_nuevos()" style='width:200px; height:35px'
            title="Guardar datos de la Planilla" <?php //echo  $desabil;
                                                       echo $disabled; ?>/>
            </td>
            </tr>
            
             <tr><td colspan="2"><table width=100% align="center" class="bordes">
	  			<tr align="center">
	  			 <td >
	     		<input type=button class="btn btn-warning" name="cerrar" value="Cerrar" onclick="window.close()">     
	   			</td>
	 			 </tr>
  			</table></td></tr>   
  
        </table>           
        <br>

        </form>

            <?
if(($accion2=="Se actualizo la Planilla")||($accion2=="Se guardo la Planilla Clasificacion Nro. " . $nro_clasificacion ." y la Prestacion: " . $id_prestacion)){
    sleep(2);
    echo('<script>window.close();</script>');
    } ?> 
<?php echo  fin_pagina(); // aca termino ?>





       
