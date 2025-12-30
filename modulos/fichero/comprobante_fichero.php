<?
require_once ("../../config.php");
include_once("../facturacion/funciones.php");
include_once("funciones_fichero.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

cargar_calendario();
$usuario1=$_ses_user['id'];

if($anular=="anular"){
   $db->StartTrans();

  $query="UPDATE fichero.fichero set
             anular='SI'
             where id_fichero=$id_fichero";

    sql($query, "Error al Anular la Ficha de Atencion") or fin_pagina();
    $accion="Se Anulado la Ficha de Atencion";   
    if ($entidad_alta=='nu'){
        $id_beneficiarios=$id; 
          $id_smiafiliados=0;
          $update_f="UPDATE fichero.fichero set fecha_pcontrol_flag='1' 
          where id_fichero=(select max(id_fichero) from fichero.fichero  where id_beneficiarios='$id'  AND (anular='' or anular IS NULL))";
        }//carga de prestacion a paciente NO PLAN NACER
    
    if ($entidad_alta=='na'){
        $id_beneficiarios=0; 
          $id_smiafiliados=$id;
          $update_f="update fichero.fichero set fecha_pcontrol_flag='1' where id_fichero=(select max(id_fichero) from fichero.fichero  where id_smiafiliados='$id'  AND (anular='' or anular IS NULL))";
        }//carga de prestacion a paciente PLAN NACER
    sql($update_f, "No se puede actualizar los registros") or fin_pagina();
         
     /*cargo los log*/ 
    $usuario=$_ses_user['name'];
    $fecha_carga=date("Y-m-d H:i:s");
    $log="insert into fichero.log_fichero 
           (id_fichero, fecha, tipo, descripcion, usuario) 
      values ($id_fichero, '$fecha_carga','Anular Ficha de Atencion','Nro. fichero $id_fichero', '$usuario')";
    sql($log) or fin_pagina();
   
    $db->CompleteTrans();   
}

if ($_POST['guardar']=="Guardar"){
	 
  $db->StartTrans();
			//if($ta=='')$ta=0;
			$tension_arterial_M=$_POST['tension_arterial_M'];
			$tension_arterial_m=$_POST['tension_arterial_m'];
      $perim_cefalico = (isset($_POST['perim_cefalico'])) ? $_POST['perim_cefalico'] : 0;
			$maxima=str_pad($tension_arterial_M,3,"0",STR_PAD_LEFT);
			$minima=str_pad($tension_arterial_m,3,"0",STR_PAD_LEFT);
			$tension_arterial="$maxima"."/"."$minima";

      		
			$fecha_carga=date("Y-m-d H:i:s");
			$cuie=$_POST['cuie'];
			$nom_medico=$_POST['nom_medico'];
			$fecha_comprobante=$_POST['fecha_comprobante'];
			$comentario=$_POST['comentario'];
			$fecha_comprobante=Fecha_db($fecha_comprobante);
      $fecha_control=$fecha_comprobante;

 if ($sexo=='Femenino') $sexo='F';
 if ($sexo=='Masculino') $sexo='M';



			$tipo_lactancia=$_POST['tipo_lactancia'];
      
      //enfermedades epidemiologicas
      if ($_POST['epi']=='ninguno') $enf_epidem=FALSE;
      if ($_POST['epi']=='bolutismo') $enf_epidem="BOTULISMO";
      if ($_POST['epi']=='hanta_virus') $enf_epidem="HANTA VIRUS";
      if ($_POST['epi']=='marea_roja') $enf_epidem="MAREA ROJA";
      if ($_POST['epi']=='psitacosis') $enf_epidem="PSITACOSIS";
      if ($_POST['epi']=='tetanos') $enf_epidem="TETANOS";
      if ($_POST['epi']=='encefal_espong') $enf_epidem="ENCEFAL.ESPONGIFORME";
      if ($_POST['epi']=='carbunco') $enf_epidem="CARBUNCO";
      if ($_POST['epi']=='fiebre_rec') $enf_epidem="FIEBRE REC(piojos)";
      if ($_POST['epi']=='otros_toxicos') $enf_epidem="OTROS TOXICOS";
      if ($_POST['epi']=='rabia_humana') $enf_epidem="RABIA HUMANA";
      if ($_POST['epi']=='tetanos_neo') $enf_epidem="TETANOS NEONATAL";
      if ($_POST['epi']=='encefalitis') $enf_epidem="ENCEFALITIS";
      if ($_POST['epi']=='colera') $enf_epidem="COLERA";
      if ($_POST['epi']=='tifus') $enf_epidem="TIFUS EXANTEM.(piojos)";
      if ($_POST['epi']=='paralasis') $enf_epidem="PARALISIS FLACCIDAS";
      if ($_POST['epi']=='sarampion') $enf_epidem="SARAMPION";
      if ($_POST['epi']=='mening') $enf_epidem="MENING.MENINGOCOCCIDAS";
      if ($_POST['epi']=='fiebre_hem_arg') $enf_epidem="FIEBRE HEMORR.ARG";
      if ($_POST['epi']=='dengue') $enf_epidem="DENGUE";
      if ($_POST['epi']=='fiebre_amarilla') $enf_epidem="FIEBRE AMARILLA";
      if ($_POST['epi']=='peste_humana') $enf_epidem="PESTE HUMANA";
      if ($_POST['epi']=='rubeola') $enf_epidem="RUBEOLA";
      if ($_POST['epi']=='influenzae') $enf_epidem="MENING.HAEMOP.INFLUENZAE";
      if ($_POST['epi']=='paludismo') $enf_epidem="PALUDISMO";
      if ($_POST['epi']=='difteria') $enf_epidem="DIFTERIA";
      if ($_POST['epi']=='intox_alim') $enf_epidem="INTOXICACION ALIMENTARIA";
      if ($_POST['epi']=='sika') $enf_epidem="SIKA";
      if ($_POST['epi']=='rubeola_cong') $enf_epidem="RUBEOLA CONGENITA";
      
      if ($_POST['epi']=='alacranismo') $enf_epidem="ALACRANISMO";
      if ($_POST['epi']=='tifoidea') $enf_epidem="FIEBRE TIFOIDEA";
      if ($_POST['epi']=='intox_plag') $enf_epidem="INTOX.POR PLAG.AGRIC.";
      if ($_POST['epi']=='mening_tuber') $enf_epidem="MENINGITIS TUBERCULOSA";
      if ($_POST['epi']=='parotiditis') $enf_epidem="PAROTIDITIS";
      if ($_POST['epi']=='chagas_agudo') $enf_epidem="CHAGAS AGUDO";
      if ($_POST['epi']=='aracnoidismo') $enf_epidem="ARACNOIDISMO";
      if ($_POST['epi']=='paratifoidea') $enf_epidem="FIEBRE PARATIFOIDEA";
      if ($_POST['epi']=='intox_carb') $enf_epidem="NTOX.POR MON.CARB.";
      if ($_POST['epi']=='mening_germen') $enf_epidem="MENINGITIS OTRO GERMEN";
      if ($_POST['epi']=='fiebre_paratifoidea') $enf_epidem="FIEBRE PARATIFOIDEA";

      if ($_POST['epi']=='sepsis') $enf_epidem="SEPSIS CONNATAL";
      if ($_POST['epi']=='hiv_sida') $enf_epidem="COND.ACUMINADOS-HIV-SIDA";
      if ($_POST['epi']=='brucelosis') $enf_epidem="BRUCELOSIS";
      if ($_POST['epi']=='hep') $enf_epidem="HEP.A.B.C.D.E.";
      if ($_POST['epi']=='lepra') $enf_epidem="LEPRA";
      if ($_POST['epi']=='mening_viral') $enf_epidem="MENINGITIS VIRAL";
      if ($_POST['epi']=='toxoplasmosis') $enf_epidem="TOXOPLASMOSIS";
      if ($_POST['epi']=='sifilis') $enf_epidem="SIFILIS CONGENITA";
      if ($_POST['epi']=='coqueluche') $enf_epidem="COQUELUCHE";
      if ($_POST['epi']=='hep_s_esp') $enf_epidem="HEPATITIS S/ESPECIFICAR";
      if ($_POST['epi']=='leptos') $enf_epidem="LEPTOSPIROSIS";
      if ($_POST['epi']=='mening_viral_ent') $enf_epidem="MENINGITIS VIRAL X ENT.";
      if ($_POST['epi']=='triquinosis') $enf_epidem="TRIQUINOSIS";
      if ($_POST['epi']=='meningitis_otro_germen') $enf_epidem="MENINGITIS OTRO GERMEN";
      
      if ($_POST['epi']=='sifilis_s_especificar') $enf_epidem="SIFILIS S/ESPEC.";
      if ($_POST['epi']=='hidatidosis') $enf_epidem="HIDATIDOSIS";
      if ($_POST['epi']=='mening_s_e') $enf_epidem="MENINGITIS S/ESP.";
      if ($_POST['epi']=='mening_para') $enf_epidem="MENING.VIR.P/PAROTIDITIS";
      if ($_POST['epi']=='tuberculosis') $enf_epidem="TUBERCULOSIS";
      if ($_POST['epi']=='supur') $enf_epidem="SUPUR.GONOC.Y NO GONOC.Y S/ESP.";
      if ($_POST['epi']=='chagas_cong') $enf_epidem="CHAGAS CONGENITO";
      if ($_POST['epi']=='inf_hosp') $enf_epidem="INFEC.HOSPITALARIAS";
      if ($_POST['epi']=='mening_bact') $enf_epidem="MENINGITIS BACT.S/G";
      if ($_POST['epi']=='mor_perro') $enf_epidem="MORDEDURA DE PERRO";
      if ($_POST['epi']=='sars') $enf_epidem="SARS";
      
      if ($_POST['epi']=='leish') $enf_epidem="LEISHMANIASIS";
      if ($_POST['epi']=='mening_neumoc') $enf_epidem="MENINGITIS NEUMOCOCO";
      if ($_POST['epi']=='ofidismo') $enf_epidem="OFIDISMO";
      if ($_POST['epi']=='suh') $enf_epidem="SUH";
      
      if ($_POST['epi']=='acci_trans') $enf_epidem="ACCIDENTE DE TRANSITO";
      if ($_POST['epi']=='acci_hogar') $enf_epidem="ACCIDENTE DE HOGAR";
      if ($_POST['epi']=='acci_s_esp') $enf_epidem="ACCIDENTE S/ESPECIFICAR";
      if ($_POST['epi']=='cancer') $enf_epidem="CANCER";
      if ($_POST['epi']=='chancro_blando') $enf_epidem="CHANCRO BLANDO";
      if ($_POST['epi']=='diabetes') $enf_epidem="DIABETES";
      if ($_POST['epi']=='diarrea') $enf_epidem="DIARREA";
      if ($_POST['epi']=='glanuloma') $enf_epidem="GLANULOMA INGUINAL";
      if ($_POST['epi']=='enf_tipo_influenza') $enf_epidem="ENF.TIPO INFLUENZA";
      
      if ($_POST['epi']=='bronquitis_menor') $enf_epidem="BRONQUILITIS MENOR 2 AÑOS";
      if ($_POST['epi']=='neumonia') $enf_epidem="NEUMONIA";
      if ($_POST['epi']=='intox_medicamento') $enf_epidem="INTOX.MEDICAMENTOSA";
      if ($_POST['epi']=='intox_plaguicidas') $enf_epidem="INTOX. POR PLAGUICIDAS";
      if ($_POST['epi']=='intox_plag_s_esp') $enf_epidem="INTOX. POR PLAGUISIDAS SIN ESPECIFICAR";
      if ($_POST['epi']=='varicela') $enf_epidem="VARICELA";
      if ($_POST['epi']=='sindrome_febril') $enf_epidem="SINDROME FEBRIL INESPECIFICO";
      
      //fin enfermedades epidemiologicas
      

			//percentilos CTTE
			$percen_peso_edad=-1;
			$percen_talla_edad=-1;
			$percen_perim_cefali_edad=-1;
			$percen_peso_talla=-1;
			$percen_imc_edad=-1;

      
    $taller=$_POST['taller'];

    $edad_con_meses=edad_con_meses(fecha_db($afifechanac),$fecha_control);
    $anio=$edad_con_meses['anos'];
	  $meses=$edad_con_meses['meses'];
	  $dias=$edad_con_meses['dias'];
    
    if ($anio==0) {
		$dias=$dias+($meses*30);
		$percen_peso_edad=calculo_percentilo_peso($dias,$sexo,$peso);
		}

    if ($anio>=1 and $anio<=5) {
		$meses=$meses+($anio*12);
		$dias=$dias+($meses*30);
		$percen_imc_edad=calculo_percentilo_imc($meses,$sexo,$imc);
		$percen_peso_edad=calculo_percentilo_peso($dias,$sexo,$peso);
		$percen_talla_edad=calculo_percentilo_talla($dias,$sexo,($talla*100));

		}

	if ($anio>5 and $anio<=19) {
		$meses=$meses+($anio*12);
		$percen_imc_edad=calculo_percentilo_imc($meses,$sexo,$imc);
		$percen_talla_edad=calculo_percentilo_talla($meses,$sexo,($talla*100));

		}
    
    $q="SELECT id_nomenclador_detalle from facturacion.nomenclador_detalle
        where '$fecha_control'>=fecha_desde and '$fecha_control'<=fecha_hasta and modo_facturacion='4'";
    $res_efector=sql($q,"Error en traer el precio del nomenclador") or fin_pagina();
    $id_nomenclador_detalle=$res_efector->fields['id_nomenclador_detalle'];     

	if ($entidad_alta=='nu'){
	    $id_beneficiarios=$id; 
		$id_smiafiliados=0;
		$update_f="update fichero.fichero set fecha_pcontrol_flag='0' where id_beneficiarios='$id' ";
		}
        
  //carga de prestacion a paciente NO PLAN SUMAR
		    
      if ($entidad_alta=='na'){
				$id_beneficiarios=0; 
	    	$id_smiafiliados=$id;
	    	$update_f="update fichero.fichero set fecha_pcontrol_flag='0' where id_smiafiliados='$id'";
		    }
		    
      sql($update_f, "No se puede actualizar los registros") or fin_pagina();
		 
		$q="select nextval('fichero.fichero_id_fichero_seq') as id_fichero";
		$id_fichero=sql($q) or fin_pagina();
		$id_fichero=$id_fichero->fields['id_fichero'];	
		        
		$periodo= str_replace("-","/",substr($fecha_comprobante,0,7));
		//
		    
		if($fecha_pcontrol=='')$fecha_pcontrol='1000-01-01';
		else $fecha_pcontrol=Fecha_db($fecha_pcontrol);
		    
		if($tunner=='')$tunner=0;	
		if(($edad >19)){
		    	if($peso=='')$peso=0;
		    	if($talla=='')$talla=0;
		    	if($imc=='')$imc=0;
		    	//if($ta=='')$ta=0;	    	
		    }

		  if($tasa_materna=='' or $tasa_materna==-1){
		    	$tasa_materna=0;
		    }	

		   
		if($peso_embarazada=='')$peso_embarazada=0;
		if($altura_uterina=='')$altura_uterina=0;
		if($imc_uterina=='')$imc_uterina=0;
		if($semana_gestacional=='')$semana_gestacional=0;
		if($perim_cefalico=='')$perim_cefalico=0;
		if($diabetico=='')$diabetico='NO';
		if($hipertenso=='')$hipertenso='NO';
			
		if($embarazo=='embarazo'){
				 $embarazo='SI';
				 $fpp=Fecha_db($fpp);
			     $fum=Fecha_db($fum);
		    	 $f_diagnostico=Fecha_db($f_diagnostico);
        if ($emb_riesgo=='SI') {
          $embarazo_riesgo='SI';
          $codigo_riesgo=$_POST['codigo_riesgo'];
        };
		}
		else {
		      $embarazo='NO';
          
		      $fpp='1000-01-01'; 
			  $fum='1000-01-01'; 
			  $f_diagnostico='1000-01-01';	 
		}
		    
      if($taller){
        $taller='SI';
        $codigo_taller=$_POST['codigo_taller'];
        } else {
        $taller='NO';
        $codigo_taller='';
      }
      
      if($enf_epidem){
        $peso=0;
        $talla=0;
        $imc=0;
      }
      
      
      $query="INSERT into fichero.fichero
			             (id_fichero,  cuie, nom_medico, fecha_control, comentario, periodo, peso, talla, 
			             imc ,ta, tunner, c_vacuna,  ex_clinico_gral, ex_trauma, ex_cardio, ex_odontologico, ex_ecg, hemograma, vsg, 
			             glucemia, uremia, ca_total, orina_cto, chagas ,obs_laboratorio, ergometria, obs_adolesc, id_smiafiliados, id_beneficiarios, 
			             conclusion,tasa_materna,salud_rep,metodo_anti,fecha_pcontrol,fpp, fum, f_diagnostico, peso_embarazada,altura_uterina,imc_uterina, semana_gestacional, 
			             rx_torax,rx_col_vertebral,otros,rx_observaciones,otros_obs,fecha_pcontrol_flag,percen_peso_edad,percen_talla_edad,perim_cefalico,
                   percen_perim_cefali_edad,percen_peso_talla,percen_imc_edad,diabetico,hipertenso,embarazo,publico,ag_visual, obs_ecg,tipo_lactancia,
                   embarazo_riesgo,codigo_riesgo,taller,codigo_taller,enfer_epidemeologica)
		        values
		        ($id_fichero, '$cuie', '$nom_medico', '$fecha_control', '$comentario', '$periodo', '$peso', 
			    '$talla', '$imc', '$tension_arterial', '$tunner', '$c_vacuna',  '$ex_clinico_gral', '$ex_trauma',
			    '$ex_cardio', '$ex_odontologico', '$ex_ecg', '$hemograma', 
			    '$vsg', '$glucemia', '$uremia', '$ca_total', '$orina_cto', '$chagas', '$obs_laboratorio', '$ergometria',
			    '$obs_adolesc', '$id_smiafiliados', '$id_beneficiarios', 
			    '$conclusion','$tasa_materna','$salud_rep','$metodo_anti' ,'$fecha_pcontrol', '$fpp', '$fum',
			    '$f_diagnostico', '$peso_embarazada', '$altura_uterina','$imc_uterina','$semana_gestacional',
			    '$rx_torax','$rx_col_vertebral','$otros','$rx_observaciones','$otros_obs','1','$percen_peso_edad',
			    '$percen_talla_edad','$perim_cefalico',
                '$percen_perim_cefali_edad','$percen_peso_talla', '$percen_imc_edad','$diabetico','$hipertenso',
                '$embarazo','$publico', '$ag_visual', '$obs_ecg','$tipo_lactancia',
                '$emb_riesgo','$codigo_riesgo','$taller','$codigo_taller','$enf_epidem')";	

		  sql($query, "Error al insertar el comprobante") or fin_pagina();	    
		  $accion="Registro Grabado.";	    
		    
		  /*cargo los log*/ 
		  $usuario=$_ses_user['name'];
			$log="insert into fichero.log_fichero 
				   (id_fichero, fecha, tipo, descripcion, usuario) 
			     values ($id_fichero, '$fecha_carga','Nuevo Registro','Nro. fichero $id_fichero', '$usuario')";
			sql($log) or fin_pagina();		 
		  $db->CompleteTrans(); 

		//alta en el sistema de facturacion ----------------------------------------------------------------------
		$q_2="SELECT *,trim (afisexo) as sexo from nacer.smiafiliados where id_smiafiliados='$id_smiafiliados'";
		$res_2=sql($q_2,"no puedo ejecutar consulta");
		$activo=trim($res_2->fields['activo']);
		$clavebeneficiario=$res_2->fields['clavebeneficiario'];
    $sexo=$res_2->fields['sexo'];
    $grupopoblacional=$res_2->fields['grupopoblacional'];


    $dias_de_vida=GetCountDaysBetweenTwoDates(fecha_db($afifechanac), $fecha_comprobante);
    if (($dias_de_vida>=0) and ($dias_de_vida<=28)) $grupo_etareo='Neonato';
    if (($dias_de_vida>28) and ($dias_de_vida<=2190)) $grupo_etareo='Cero a Cinco Años';
    if (($dias_de_vida>2190)and ($dias_de_vida<=3650)) $grupo_etareo='Seis a Nueve Años';
    if (($dias_de_vida>3650) and ($dias_de_vida<=7300)) $grupo_etareo='Adolecente';
    if (($dias_de_vida>7300) and ($dias_de_vida<=23725))$grupo_etareo='Adulto';
    if ($dias_de_vida>23725) $grupo_etareo='Mayor de 65 años';
    
    /*if (($dias_de_vida>6935) and ($dias_de_vida<=8760) and ($sexo=='F')) $grupo_etareo='Mujeres_taller';*/


//facturo a los niños,adolescentes,mujeres y hombres		
if (($activo=='S') and ($embarazo=='NO') and ($taller=='NO') and (!$enf_epidem)){
			$db->StartTrans();
			
//COMPROBANTE
$q="SELECT nextval('comprobante_id_comprobante_seq') as id_comprobante";
$id_comprobante=sql($q) or fin_pagina();
$id_comprobante=$id_comprobante->fields['id_comprobante'];	
		    
$periodo= str_replace("-","/",substr($fecha_control,0,7));
		    		    
$query="INSERT into facturacion.comprobante
		(id_comprobante, cuie, nombre_medico, fecha_comprobante, clavebeneficiario, id_smiafiliados, fecha_carga,periodo,comentario,id_servicio,activo,grupo_etareo)
		values
		($id_comprobante,'$cuie','$nom_medico','$fecha_control','$clavebeneficiario', $id_smiafiliados,'$fecha_carga',
		'$periodo','Desde fichero','1','$activo','$grupopoblacional')";	

sql($query, "Error al insertar el comprobante") or fin_pagina();	    
$usuario=$_ses_user['name'];
$log="insert into facturacion.log_comprobante 
	   (id_comprobante, fecha, tipo, descripcion, usuario) 
     values ($id_comprobante, '$fecha_carga','Nuevo Comprobante','Nro. Comprobante $id_comprobante', '$usuario')";
sql($log) or fin_pagina();	
			
//saco los codigos NOMENCLADOR 2020 y 2021
if ($id_nomenclador_detalle==17){
					
        //sacar codigo segun prestacion
                
				if (($grupo_etareo=='Neonato') or ($grupo_etareo=='Cero a Cinco Años')){
					if ($dias_de_vida <= 365) {
						$codigo= "C001";
						$desc="Examen periódico de salud de niño menor de 1 año";
            
					} elseif ($dias_de_vida > 365) {
						$codigo= "C001";
						$desc="Examen periódico de salud de niño de 1 a 5 años";
					}
				}
				if ($grupo_etareo=='Seis a Nueve Años'){					
						$codigo= "C001";
						$desc="Examen periódico de salud del niño de 6 a 9 años";					
				}
				if ($grupo_etareo=='Adolecente'){					
						$codigo= "C001";
						$desc="Examen Periódico de Salud del adolescente";				
				}
				
        if ($grupo_etareo=='Adulto') {					
						$codigo= "C001";
						$desc="Examen periódico de salud en adulto";					
				}	
       	
				
        //tengo que sacar el id_nomenclado2020
			$q="SELECT * from facturacion.nomenclador
					where id_nomenclador_detalle=$id_nomenclador_detalle and codigo='$codigo' 
          and descripcion='$desc'";
			$res_nom=sql($q,"Error en traer el id_nomenclador 2020".$grupo_etareo) or fin_pagina();
			$nomenclador=$res_nom->fields['id_nomenclador'];
		
    } else {

        if ($grupo_etareo=='Neonato' or $grupo_etareo=='Cero a Cinco Años'){
            $codigo= "C001";
            $desc="Examen periódico de salud";
            $grupoe='ceroacinco';            
          }
          
          if ($grupo_etareo=='Seis a Nueve Años'){          
              $codigo= "C001";
              $desc="Examen periódico de salud"; 
              $grupoe='seisanueve';        
          }
          
          if ($grupo_etareo=='Adolecente'){         
              $codigo= "C001";
              $desc="Examen periódico de salud"; 
              $grupoe='adol';       
          }
          
          if ($grupo_etareo=='Adulto') {          
              $codigo= "C001";
              $desc="Examen periódico de salud"; 
              $grupoe='adulto';         
          } 

          if ($grupo_etareo=='Mayor de 65 años') {          
            $codigo= "C001";
            $desc="Examen periódico de salud"; 
            $grupoe='mayores_65';         
        } 


          $q="SELECT * from facturacion.nomenclador
          where id_nomenclador_detalle=$id_nomenclador_detalle and codigo='$codigo' 
          and descripcion='$desc' and $grupoe ='1' ";
          $res_nom=sql($q,"Error en traer el id_nomenclador 2021".$grupo_etareo) or fin_pagina();
          $nomenclador=$res_nom->fields['id_nomenclador'];

    }


      //-------------------------------------------------------------------------------------
			
			//tengo que sacar el id_anexo
			$q="SELECT * from facturacion.anexo
				where id_nomenclador_detalle=$id_nomenclador_detalle and id_nomenclador=$nomenclador";
		  $res_nom=sql($q,"Error en traer el id_anexo") or fin_pagina();
						
			if ($res_nom->Recordcount()==0){
				$q="SELECT * from facturacion.anexo
					where prueba='No Corresponde' and id_nomenclador_detalle=$id_nomenclador_detalle";
			  $res_nom=sql($q,"Error en traer el id_anexo") or fin_pagina();
				$anexo=$res_nom->fields['id_anexo'];
			}
			
			//saco id_prestacion
			$q="SELECT nextval('facturacion.prestacion_id_prestacion_seq') as id_prestacion";
		  $id_prestacion=sql($q) or fin_pagina();
		  $id_prestacion=$id_prestacion->fields['id_prestacion'];
		
		    //traigo el precio de la prestacion del nomencladorpara guardarla en la 
		    //tabla de prestacion por que si se cambia el precio en el nomenclador
		    //cambia el precio de todas las prestaciones y las facturas
		  $q="SELECT precio from facturacion.nomenclador where id_nomenclador=$nomenclador";
		  $precio_prestacion=sql($q,"Error en traer el precio del nomenclador") or fin_pagina();
		  $precio_prestacion=$precio_prestacion->fields['precio'];
		  $precio_prestacion=$precio_prestacion;
		   
     		    
			if (valida_prestacion_nuevo_nomenclador($id_comprobante,$nomenclador)){
					
				$query="insert into facturacion.prestacion
             (id_prestacion,id_comprobante, id_nomenclador,cantidad,precio_prestacion,id_anexo,peso,tension_arterial,talla,diagnostico,estado_envio,perim_cefalico)
             values
             ($id_prestacion,$id_comprobante,$nomenclador,1,$precio_prestacion,$anexo,$peso,'$tension_arterial',$talla,'A97','n',$perim_cefalico)";
             
        sql($query, "Error al insertar la prestacion") or fin_pagina();
				
				/*cargo los log*/ 
				$usuario=$_ses_user['name'];
				$log="insert into facturacion.log_prestacion
					   (id_prestacion, fecha, tipo, descripcion, usuario) 
				values ($id_prestacion, '$fecha_carga','Nueva PRESTACION','Nro. prestacion $id_prestacion', '$usuario')";
				sql($log) or fin_pagina();
				$accion.=" Se Genero el Comprobante Nro  $id_comprobante.";
			} else $accion.=" Supero tasa de Uso";
		}
		
//cargo trazadoras de niño-------------------------------------------------------------------------------------------------

if ($embarazo=='NO' and $taller=='NO' and (!$enf_epidem)){ 
			$fecha_carga=date("Y-m-d H:m:s");
			$usuario=$_ses_user['name'];
			$db->StartTrans();         
		    
			
		   
			$tension_arterial_M=$_POST['tension_arterial_M'];
			$tension_arterial_m=$_POST['tension_arterial_m'];
			$maxima=str_pad($tension_arterial_M,3,"0",STR_PAD_LEFT);
			$minima=str_pad($tension_arterial_m,3,"0",STR_PAD_LEFT);
			$tension_arterial="$maxima"."/"."$minima";
		   
		  $fecha_nac=fecha_db($afifechanac);
		  $triple_viral="1980-01-01";  
		      
		  ($talla!=0)?$imc=($peso/($talla*$talla)):$imc=0;
		  $talla=$talla*100; //paso talla en metro a centimetro
			$num_doc=$afidni;
			$apellido=$afiapellido;
			$nombre=$afinombre;
			(date("Y-m-d")-fecha_db($afifechanac) <= '1')?$nino_edad=0:$nino_edad=1;
			
		  if ($grupo_etareo==''){
          $sql="SELECT * from uad.beneficiarios
                where id_beneficiarios=$id";
          $res_sql=sql($sql, "Error al traer el beneficiario") or fin_pagina();
          $afifechanac=$res_sql->fields['fecha_nacimiento_benef'];
          $sexo=$res_sql->fields['sexo'];
          $dias_de_vida=GetCountDaysBetweenTwoDates(fecha_db($afifechanac), $fecha_control);
          if (($dias_de_vida>=0) and ($dias_de_vida<=28)) $grupo_etareo='Neonato';
          if (($dias_de_vida>28) and ($dias_de_vida<=2190)) $grupo_etareo='Cero a Cinco Años';
          if (($dias_de_vida>2190)and ($dias_de_vida<=3650)) $grupo_etareo='Seis a Nueve Años';
          if (($dias_de_vida>3650) and ($dias_de_vida<=7300)) $grupo_etareo='Adolecente';
          if (($dias_de_vida>7300) and ($sexo=='F')) $grupo_etareo='Mujeres';
          if (($dias_de_vida>7300) and ($sexo=='M')) $grupo_etareo='Hombres';
          };

      if ($grupo_etareo=='Mujeres' or $grupo_etareo=='Hombres') {
          $q="select nextval('trazadoras.adultos_id_adulto_seq') as id_planilla";
          $id_planilla=sql($q) or fin_pagina();
          $id_planilla=$id_planilla->fields['id_planilla'];
          
          $query="INSERT into trazadoras.adultos
    		          (id_adulto,cuie,clave,clase_doc,tipo_doc,num_doc,apellido,nombre,fecha_nac,fecha_control,peso,talla,imc,observaciones,fecha_carga,usuario,ta)
    		          values
    		          ('$id_planilla','$cuie','','P','DNI','$num_doc','$apellido','$nombre','$fecha_nac',
    		         	'$fecha_control',$peso,$talla,$imc,'Desde el Fichero','$fecha_carga','$usuario','$tension_arterial')";
              }
          else {
            $q="select nextval('trazadoras.nino_new_id_nino_new_seq') as id_planilla";
            $id_planilla=sql($q) or fin_pagina();
            $id_planilla=$id_planilla->fields['id_planilla'];
            $query="INSERT into trazadoras.nino_new
                  (id_nino_new,cuie,clave,clase_doc,tipo_doc,num_doc,apellido,nombre,fecha_nac,fecha_control,peso,talla,
                  percen_peso_edad,percen_talla_edad,perim_cefalico,percen_perim_cefali_edad,imc,percen_imc_edad,percen_peso_talla,
                  triple_viral,nino_edad,observaciones,fecha_carga,usuario,ta)
                  values
                  ('$id_planilla','$cuie','','R','DNI','$num_doc','$apellido','$nombre','$fecha_nac',
                  '$fecha_control','$peso','$talla','$percen_peso_edad','$percen_talla_edad','$perim_cefalico',
                  '$percen_perim_cefali_edad','$imc','$percen_imc_edad','$percen_peso_talla','$triple_viral',
                  '$nino_edad','Desde el Fichero','$fecha_carga','$usuario','$tension_arterial')";
          }

		  sql($query, "Error al insertar la Planilla") or fin_pagina();		    
		  $db->CompleteTrans(); 
			$accion.=" Grabo TRZ.";
		}
		
//facturo embarazadas ---------------------------------------------------------------------------------------------------
if (($activo=='S') and ($embarazo=='SI') and $taller=='NO' and (!$enf_epidem)){
			$db->StartTrans();
			//comprobante
			$q="select nextval('comprobante_id_comprobante_seq') as id_comprobante";
		  $id_comprobante=sql($q) or fin_pagina();
		  $id_comprobante=$id_comprobante->fields['id_comprobante'];	
		  
		  $periodo= str_replace("-","/",substr($fecha_control,0,7));
		    		    
		  $query="INSERT into facturacion.comprobante
		             (id_comprobante, cuie, nombre_medico, fecha_comprobante, clavebeneficiario, id_smiafiliados, fecha_carga,periodo,comentario,id_servicio,activo,grupo_etareo)
		             values
		             ($id_comprobante,'$cuie','$nom_medico','$fecha_control','$clavebeneficiario', $id_smiafiliados,'$fecha_carga','$periodo','Desde fichero','1','$activo','$grupopoblacional')";	
		  sql($query, "Error al insertar el comprobante") or fin_pagina();	    
		  
      $usuario=$_ses_user['name'];
			$log="INSERT into facturacion.log_comprobante 
				   (id_comprobante, fecha, tipo, descripcion, usuario) 
			values ($id_comprobante, '$fecha_carga','Nuevo Comprobante','Nro. Comprobante $id_comprobante', '$usuario')";
			sql($log) or fin_pagina();	
						

    //desde SIPWEB no se cargan mas embarazadas por fichero asi mismo arreglo codigo
    if ($id_nomenclador_detalle==17 or $id_nomenclador_detalle==18){
				$q1="SELECT comprobante.id_comprobante
					FROM
					facturacion.comprobante
					INNER JOIN facturacion.prestacion ON facturacion.comprobante.id_comprobante = facturacion.prestacion.id_comprobante
					INNER JOIN facturacion.nomenclador ON facturacion.prestacion.id_nomenclador = facturacion.nomenclador.id_nomenclador
					WHERE
					facturacion.comprobante.id_smiafiliados = '$id_smiafiliados' AND
					facturacion.nomenclador.codigo = 'C005' AND
					(facturacion.nomenclador.descripcion = 'Control prenatal  de 1ra.vez (< a 13 semanas de Edad Gestacional)' OR (facturacion.nomenclador.descripcion = 'Control de embarazo < a 13 semanas')
          AND
					facturacion.comprobante.fecha_comprobante::DATE BETWEEN CURRENT_DATE-360 AND CURRENT_DATE";
				$res_mem=sql($q1,"Error al buscar") or fin_pagina();
		
		//sacar codigo segun prestacion
				//if ($res_mem->recordcount()==0){
				if ($fecha_control==$f_diagnostico){
					$codigo= "C005";
					$desc="Control de embarazo < a 13 semanas";
          }
				else{
					 $codigo= "C006";
					 $desc="Control de embarazo (desde semana 13)";
           }
				
				//tengo que sacar el id_nomenclado (global)
              
				$q="SELECT * from facturacion.nomenclador
					where id_nomenclador_detalle='$id_nomenclador_detalle' and codigo='$codigo' and descripcion='$desc'";
				$res_nom=sql($q,"Error en traer el id_nomenclador") or fin_pagina();
				$nomenclador=$res_nom->fields['id_nomenclador'];
			}//------------------------------------------
			
		//tengo que sacar el id_anexo
		$q="SELECT * from facturacion.anexo
				where id_nomenclador_detalle=$id_nomenclador_detalle and id_nomenclador=$nomenclador";
		$res_nom=sql($q,"Error en traer el id_anexo") or fin_pagina();
		$anexo=$res_nom->fields['id_anexo'];
			
		if ($anexo==''){
		$q="SELECT * from facturacion.anexo
					where prueba='No Corresponde' and id_nomenclador_detalle='$id_nomenclador_detalle'";
		$res_nom=sql($q,"Error en traer el id_anexo") or fin_pagina();
		$anexo=$res_nom->fields['id_anexo'];
		}
			
		//saco id_prestacion
		$q="SELECT nextval('facturacion.prestacion_id_prestacion_seq') as id_prestacion";
		$id_prestacion=sql($q) or fin_pagina();
		$id_prestacion=$id_prestacion->fields['id_prestacion'];
		
		//traigo el precio de la prestacion del nomencladorpara guardarla en la 
		//tabla de prestacion por que si se cambia el precio en el nomenclador
		//cambia el precio de todas las prestaciones y las facturas
		$q="SELECT precio from facturacion.nomenclador where id_nomenclador=$nomenclador";
		$precio_prestacion=sql($q,"Error en traer el precio del nomenclador") or fin_pagina();
		$precio_prestacion=$precio_prestacion->fields['precio'];
		$precio_prestacion=$precio_prestacion;
		    
		if (valida_prestacion_nuevo_nomenclador($id_comprobante,$nomenclador)){
				
		$query="INSERT into facturacion.prestacion
                 (id_prestacion,id_comprobante, id_nomenclador,cantidad,precio_prestacion,id_anexo,peso,tension_arterial,talla,diagnostico,estado_envio)
                 values
                 ($id_prestacion,$id_comprobante,$nomenclador,'1',$precio_prestacion,$anexo,$peso,'$tension_arterial',$talla,'W78','n')";
    
    
        sql($query, "Error al insertar la prestacion") or fin_pagina();
        
    /*cargo los log*/ 
    $usuario=$_ses_user['name'];
    $log="INSERT into facturacion.log_prestacion
           (id_prestacion, fecha, tipo, descripcion, usuario) 
      values ($id_prestacion, '$fecha_carga','Nueva PRESTACION','Nro. prestacion $id_prestacion', '$usuario')";
    sql($log) or fin_pagina();
    
    //En el nomenclador 2018 se salio de facturacion Normal para entrar en PPAC
    //Por eso se saca de Fichero

    /*if ($emb_riesgo=='SI'){
          $desc_alta='Alta Complejidad';
          $sql_riesgo="SELECT * from nomenclador_detalle  
                      where '$fecha_control'>=fecha_desde and '$fecha_control'<=fecha_hasta 
                      and descripcion='$desc_alta'";
          $res_sql_riesgo=sql ($sql_riesgo) or fin_pagina();
          $id_nomenclador_detalle=$res_sql_riesgo->fields['id_nomenclador_detalle'];

          $sql_riesgo="SELECT * from facturacion.nomenclador
                       where codigo='$codigo_riesgo' and id_nomenclador_detalle=$id_nomenclador_detalle";
          $res_riesgo=sql($sql_riesgo) or fin_pagina();
          $id_nomenclador_riesgo=$res_riesgo->fields['id_nomenclador'];
          $precio_prestacion=$res_riesgo->fields['precio'];
          $id_anexo=346;//No Corresponde del id_nomenclador_detall=14
                       
          if (valida_prestacion_nuevo_nomenclador($id_comprobante,$id_nomenclador_riesgo)){
            $q="select nextval('facturacion.prestacion_id_prestacion_seq') as id_prestacion";
            $id_prestacion=sql($q) or fin_pagina();
            $id_prestacion=$id_prestacion->fields['id_prestacion'];
        
            $query="insert into facturacion.prestacion
                 (id_prestacion,id_comprobante, id_nomenclador,cantidad,precio_prestacion,id_anexo,peso,tension_arterial,talla,diagnostico,estado_envio)
                 values
                 ($id_prestacion,$id_comprobante,$id_nomenclador_riesgo,1,$precio_prestacion,$id_anexo,$peso,'$tension_arterial',$talla,'Z35.9','n')";
            sql($query, "Error al insertar la prestacion") or fin_pagina();
            
            //log de prestaciones
            $log="insert into facturacion.log_prestacion
                (id_prestacion, fecha, tipo, descripcion, usuario) 
                values ($id_prestacion, '$fecha_carga','Nueva PRESTACION','Nro. prestacion $id_prestacion', '$usuario')";
            sql($log) or fin_pagina();
        }
     };*/
    	
		$accion.=" Se Genero el Comprobante Nro  $id_comprobante.";
			} else $accion.=" Supero tasa de Uso";
		}
		
    if ($embarazo=='SI'){//cargo trazadoras embarazada------------------------------------------
			$fecha_carga=date("Y-m-d H:m:s");
			$usuario=$_ses_user['name'];
			$db->StartTrans();         
			    
			$q="select nextval('trazadoras.embarazadas_id_emb_seq') as id_planilla";
			$id_planilla=sql($q) or fin_pagina();
			$id_planilla=$id_planilla->fields['id_planilla'];
			   
			$sem_gestacion=$semana_gestacional;
			
			$maxima=str_pad($tension_arterial_M,3,"0",STR_PAD_LEFT);
			$minima=str_pad($tension_arterial_m,3,"0",STR_PAD_LEFT);
			$tension_arterial="$maxima"."/"."$minima";
			
			$fpcp=$fecha_control;			
			$num_doc=$afidni;
			$apellido=$afiapellido;
			$nombre=$afinombre;
			      
			$query="INSERT into trazadoras.embarazadas
			             (id_emb,cuie,clave,tipo_doc,num_doc,apellido,nombre,fecha_control,
			             sem_gestacion,fum,fpp,fpcp,observaciones,fecha_carga,usuario,vdrl,antitetanica,ta,peso)
			             values
			             ('$id_planilla','$cuie','','DNI','$num_doc','$apellido',
			             '$nombre','$fecha_control','$sem_gestacion','$fum',
			             '$fpp','$fpcp','Desde el Fichero','$fecha_carga','$usuario','','','$tension_arterial',$peso)";

			sql($query, "Error al insertar la Planilla") or fin_pagina();
			
      $db->CompleteTrans(); 
			$accion.=" Grabo TRZ. de Embarazos";
		}
    
    if ($activo=='S' and $taller=='SI' and ($grupo_etareo=='Adolecente')) { //or $grupo_etareo=='Mujeres_taller'
      $fecha_carga=date("Y-m-d H:m:s");
      $usuario=$_ses_user['name'];
      $fecha_taller=fecha_db($_POST['fecha_taller']);
      $db->StartTrans();         
          
      if ($entidad_alta=='nu'){//carga de prestacion a paciente NO PLAN NACER
          $sql="select * from uad.beneficiarios
                where id_beneficiarios=$id";
          $res_comprobante=sql($sql, "Error al traer los Comprobantes") or fin_pagina();
          $afifechanac=$res_comprobante->fields['fecha_nacimiento_benef'];
          $id_beneficiarios=$id;
          $id_smiafiliados=0;
          }
    
      if ($entidad_alta=='na'){//carga de prestacion a paciente PLAN NACER
        $sql="select * from nacer.smiafiliados where id_smiafiliados=$id";
        $res_comprobante=sql($sql, "Error al traer los Comprobantes") or fin_pagina();
        $afifechanac=$res_comprobante->fields['afifechanac'];
        $id_beneficiarios=0;
        $id_smiafiliados=$id;
        
        $sql_taller="SELECT * from facturacion.nomenclador
                       where codigo='$codigo_taller' 
                       and id_nomenclador_detalle=$id_nomenclador_detalle
                       and adol='1'";
        
                 
        $res_taller=sql($sql_taller) or fin_pagina();
                   
        $id_nomenclador_taller=$res_taller->fields['id_nomenclador'];
        $precio_prestacion=$res_taller->fields['precio'];
        $anexo="SELECT * from facturacion.anexo where id_nomenclador_detalle=$id_nomenclador_detalle";
        $res_anexo=sql($anexo) or fin_pagina();
        $id_anexo=$res_anexo->fields['id_anexo'];

        //if (valida_prestacion_nuevo_nomenclador($id_comprobante,$id_nomenclador_taller)){
        $q="select nextval('facturacion.prestacion_id_prestacion_seq') as id_prestacion";
        $id_prestacion=sql($q) or fin_pagina();
        $id_prestacion=$id_prestacion->fields['id_prestacion'];
         
        $talla=$talla/100; //para trazadora me cambio el formato
        
        //COMPROBANTE
        $q="select nextval('comprobante_id_comprobante_seq') as id_comprobante";
        $id_comprobante=sql($q) or fin_pagina();
        $id_comprobante=$id_comprobante->fields['id_comprobante'];  
        
        $periodo= str_replace("-","/",substr($fecha_control,0,7));
              
        $query="INSERT into facturacion.comprobante
                 (id_comprobante, cuie, nombre_medico, fecha_comprobante, clavebeneficiario, id_smiafiliados, fecha_carga,periodo,comentario,id_servicio,activo,grupo_etareo)
                 values
                 ($id_comprobante,'$cuie','$nom_medico','$fecha_control','$clavebeneficiario', $id_smiafiliados,'$fecha_carga','$periodo','Desde fichero','1','$activo','$grupopoblacional')";  
        sql($query, "Error al insertar el comprobante") or fin_pagina();      
        $usuario=$_ses_user['name'];
        $log="insert into facturacion.log_comprobante 
                (id_comprobante, fecha, tipo, descripcion, usuario) 
                values ($id_comprobante, '$fecha_carga','Nuevo Comprobante','Nro. Comprobante $id_comprobante', '$usuario')";
        sql($log) or fin_pagina();
           
        //PRESTACION
        $query="INSERT into facturacion.prestacion
              (id_prestacion,id_comprobante, id_nomenclador,cantidad,precio_prestacion,id_anexo,peso,tension_arterial,talla,diagnostico,estado_envio)
               values
               ($id_prestacion,$id_comprobante,$id_nomenclador_taller,1,$precio_prestacion,$id_anexo,$peso,'$tension_arterial',$talla,'A98','n')";
        sql($query, "Error al insertar la prestacion") or fin_pagina();
            
        //log de prestaciones
        $log="INSERT into facturacion.log_prestacion
            (id_prestacion, fecha, tipo, descripcion, usuario) 
            values ($id_prestacion, '$fecha_carga','Nueva PRESTACION','Nro. prestacion $id_prestacion', '$usuario')";
        sql($log) or fin_pagina();
        
        $accion.=" Se Genero el Comprobante Nro  $id_comprobante.";
        }
     
      $q="select nextval('trazadorassps.seq_id_trz11') as id_trz11";
      $id_planilla=sql($q) or fin_pagina();
      $id_trz11=$id_planilla->fields['id_trz11'];
         
      $query="INSERT into trazadorassps.trazadora_11
              (cuie,id_trz11,fecha_nac,fecha_asis_taller,tema_taller,fecha_carga,usuario,
              comentario,id_smiafiliados,id_beneficiarios)
              values
              ('$cuie',$id_trz11,'$afifechanac','$fecha_taller','$codigo_taller','$fecha_carga','$usuario',
               '',$id_smiafiliados,$id_beneficiarios)";

      sql($query, "Error al insertar la Planilla de Trazadoras XI") or fin_pagina();
         
      $db->CompleteTrans(); 
      $accion.=" Se grabo TRZ. de taller";
    }
  	        
  	$db->CompleteTrans();
  
    			
}//de if ($_POST['guardar']=="Guardar nuevo Muleto")

if ($entidad_alta=='nu'){//carga de prestacion a paciente NO PLAN NACER 
	$sql="select * from uad.beneficiarios
	   where id_beneficiarios=$id";
  $res_comprobante=sql($sql, "Error al traer los Comprobantes") or fin_pagina();
    
  $afiapellido=trim($res_comprobante->fields['apellido_benef']);
	$afinombre=trim($res_comprobante->fields['nombre_benef']);
	$afidni=trim($res_comprobante->fields['numero_doc']);
	$nombre=$res_comprobante->fields['calle'];
	$afifechanac=$res_comprobante->fields['fecha_nacimiento_benef'];
	$sexo=$res_comprobante->fields['sexo'];

	$sql_leche="SELECT * from fichero.fichero where id_beneficiarios=$id order by 1 DESC";
	$res_leche=sql($sql_leche,"no se pudieron traer los datos de fichero") or fin_pagina();
	if($res_leche->recordcount()!=0) $tipo_lactancia=$res_leche->fields['tipo_lactancia'];
		else $tipo_lactancia="";		
 }

if ($entidad_alta=='na'){//carga de prestacion a paciente PLAN NACER
	$sql="SELECT *,trim (afisexo) as sexo from nacer.smiafiliados
	 left join nacer.efe_conv on (cuieefectorasignado=cuie)
	 where id_smiafiliados=$id";
    $res_comprobante=sql($sql, "Error al traer los Comprobantes") or fin_pagina();
    
  $afiapellido=trim($res_comprobante->fields['afiapellido']);
	$afinombre=trim($res_comprobante->fields['afinombre']);
	$afidni=$res_comprobante->fields['afidni'];
	$nombre=$res_comprobante->fields['nombre'];
	$afifechanac=$res_comprobante->fields['afifechanac'];
	$sexo=$res_comprobante->fields['sexo'];

	$sql_leche="SELECT * from fichero.fichero where id_smiafiliados=$id order by 1 DESC";
	$res_leche=sql($sql_leche,"no se pudieron traer los datos de fichero") or fin_pagina();
	if($res_leche->recordcount()!=0) $tipo_lactancia=$res_leche->fields['tipo_lactancia'];
		else $tipo_lactancia="";

}

echo $html_header;
?>
<script>

function control_nuevos(){
 if(document.all.cuie.value==-1){
  alert('Debe Seleccionar un EFECTOR');
  document.all.cuie.focus();
  return false;
  } 
 
  var epidem=$("input[type='radio'][name='epi']:checked").val();
  if (!(document.all.taller.checked) && (epidem=='ninguno')) {
   
    if (document.all.peso.value==""){
    alert('Debe ingresar el Peso');
    document.all.peso.focus();
    return false;}  

    if (document.all.peso.value >190){
    alert('El peso de la persona va desde 0.1 a 190 Kg');
    document.all.peso.focus();
      return false;} 
    
    if (document.all.peso.value==0){
      alert('El peso de la persona va desde 0.1 a 190 Kg');
      document.all.peso.focus();
      return false;}
    
    if (isNaN(document.all.peso.value)){
      alert('El peso de la persona debe ser un numero real');
      document.all.peso.focus();
      return false;}

    if(document.all.talla.value==""){
    alert('Debe ingresar la talla');
    document.all.talla.focus();
    return false;}
    
    if(document.all.talla.value >= 2.50  ) {
    alert('la talla no puede superar los 2.50 metros');
    document.all.talla.focus();
    return false;}
    
    if(document.all.talla.value==0  ) {
    alert('la talla no puede ser 0 o superar los 2.50 metros');
    document.all.talla.focus();
    return false;}
    
    if(isNaN(document.all.talla.value)) {
    alert('la talla debe ser un numero real');
    document.all.talla.focus();
    return false;}

    <?if (GetCountDaysBetweenTwoDates($afifechanac,date("Y-m-d"))<=365){?>
        if(document.all.perim_cefalico.value==""||
          document.all.perim_cefalico.value==0){
          alert('Debe ingresar valor valido de Perimetro Cefalico');
          document.all.perim_cefalico.focus();
          return false;
       }
  
    if(document.all.percen_perim_cefali_edad.value=="-1"){
      alert('Debe ingresar el Percentilo Perimetro Cefalico');
      document.all.percen_perim_cefali_edad.focus();
      return false;
     }
  <?}?>
  
    
  <?if (GetCountDaysBetweenTwoDates($afifechanac,date("Y-m-d"))>1825){?>
  
     if(document.all.tension_arterial_M.value==""){
       alert("Debe completar el campo Tension Arterial MAXIMA");
       document.all.tension_arterial_M.focus();
       return false;
       }
      else{
         var tension_arterial_M=document.all.tension_arterial_M.value;
         if(isNaN(tension_arterial_M)){
           alert('El dato de la tension Arterial MAXIMA debe ser un Numero Entero');
           document.all.tension_arterial_M.focus();
           return false;
          }
        }
  
      if(document.all.tension_arterial_m.value==""){
       alert("Debe completar el campo de Tension Arterial MINIMA");
       document.all.tension_arterial_m.focus();
       return false;
       }
       else{
        var tension_arterial_m=document.all.tension_arterial_m.value;
        if(isNaN(tension_arterial_m)){
          alert('El dato de la tension Arterial MINIMA debe ser un Numero Entero');
          document.all.tension_arterial_m.focus();
          return false;
          }
         }
  <?}?>
   
  
    if (document.all.imc.value==""){
      alert('Ingreso automatico del IMC');
      document.all.imc.focus();
      return false;}

    if (document.all.embarazo.checked==true){
    if(document.all.f_diagnostico.value==""){
      alert('Debe ingresar la fecha de diagnostico');
      document.all.f_diagnostico.focus();
      return false;
    }
    if(document.all.fum.value==""){
      alert('Debe ingresar la FUM');
      document.all.fum.focus();
      return false;
    }
    
    
    if(diff_fecha()){
      alert('La fecha de FUM debe ser anterior a la fecha de Diagnostico');
      document.all.fum.focus();
      return false;
    } 
    
    if(document.all.fpp.value==""){
      alert('Debe ingresar la FPP');
      document.all.fpp.focus();
      return false;
    }
    
    if(document.all.semana_gestacional.value==""){
      alert('Debe ingresar las semanas gestacional');
      document.all.semana_gestacional.focus();
      return false;
    }

    if(document.all.semana_gestacional.value>45){
      alert('La cantidad de Semanas Gestacionales Exede un Embarazo Normal, Revise Fecha de Diagnostico y FUM');
      document.all.f_diagnostico.focus();
      return false;
    }
    if(document.all.tension_arterial_M.value==""){
    alert("Debe completar el campo Tension Arterial MAXIMA");
    document.all.tension_arterial_M.focus();
    return false;
    }else{
    var tension_arterial_M=document.all.tension_arterial_M.value;
    if(isNaN(tension_arterial_M)){
      alert('El dato de la tension Arterial MAXIMA debe ser un Numero Entero');
      document.all.tension_arterial_M.focus();
      return false;
    }
  }
  
  if(document.all.tension_arterial_m.value==""){
   alert("Debe completar el campo de Tension Arterial MINIMA");
   document.all.tension_arterial_m.focus();
   return false;
   }else{
    var tension_arterial_m=document.all.tension_arterial_m.value;
    if(isNaN(tension_arterial_m)){
      alert('El dato de la tension Arterial MINIMA debe ser un Numero Entero');
      document.all.tension_arterial_m.focus();
      return false;
    }
  }
  if (document.all.emb_riesgo.checked==true){
    if (document.all.codigo_riesgo.value==-1) {
      alert('Debe ingresar al menos un codigo de Notificacion');
      document.all.codigo_riesgo.focus();
      return false;
    }
  }
}

var peso=document.all.peso.value;
var talla=document.all.talla.value;
if (confirm('Esta Seguro que Desea Ingresar el Registro?')){
  document.all.peso.value = peso.replace(',','.');
  document.all.talla.value = talla.replace(',','.');
  return true;
 }
 else return false; 
}

if (document.all.taller.checked==true){
    if (document.all.codigo_taller.value==-1) {
      alert('Debe ingresar al menos un codigo de Taller');
      document.all.codigo_taller.focus();
      return false;
    }
    
    if (confirm('Esta Seguro que Desea Ingresar el Registro?')){
      document.all.peso.value = 0;
      document.all.talla.value = 0;
      document.all.imc.value = 0;
      return true;
    }
    else return false; 
  }
  
}//de function control_nuevos()

var img_ext='<?php echo $img_ext='../../imagenes/rigth2.gif' ?>';//imagen extendido
var img_cont='<?php echo $img_cont='../../imagenes/down2.gif' ?>';//imagen contraido
function muestra_tabla(obj_tabla,nro){
 oimg=eval("document.all.imagen_"+nro);//objeto tipo IMG
 if (obj_tabla.style.display=='none'){
  obj_tabla.style.display='inline';
    oimg.show=0;
    oimg.src=img_ext;
 }
 else{
  obj_tabla.style.display='none';
    oimg.show=1;
  oimg.src=img_cont;
 }
}
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


function calculo_fpp_sem(){
  
  if (document.all.f_diagnostico.value!="" && document.all.fum.value!="") {
    //var f_c=document.all.fecha_comprobante.value;
    var f_c=document.all.f_diagnostico.value;
    var f_f=document.all.fum.value;
    
    var array_c = f_c.split("/");
    var array_f = f_f.split("/");
    
    var ch_c=array_c[2] + "/" + array_c[1] + "/" +array_c[0];
    var ch_f=array_f[2] + "/" + array_f[1] + "/" +array_f[0];
        
    var fecha_control= new Date (ch_c);
    /*var fecha_control_i = new Date ();
    fecha_control=fecha_control_i.getDate() + "/" + (fecha_control_i.getMonth() +1) + "/" + fecha_control_i.getFullYear();*/
    
    var fum=new Date (ch_f);
    var fpp= new Date (ch_f);
    var dias= 10;
    var dias_fpp = 282;
            
  var tiempo=fecha_control.getTime();
  
  
   
    //Calculamos los milisegundos sobre la fecha que hay que sumar o restar...
    milisegundos=parseInt(dias*24*60*60*1000);
  
    //Modificamos la fecha actual
    total=fecha_control.setTime(tiempo+milisegundos);
  
  
  var fin = fecha_control.getTime() - fum.getTime();
  //var dias = Math.floor(fin / (1000 * 60 * 60 * 24))
  //var semanas = Math.floor(dias/7) - 2;
  
  var dias = Math.round(fin / (1000 * 60 * 60 * 24))
  var semanas = Math.round(dias/7) - 2;
  
  document.all.semana_gestacional.readonly = true;
  document.all.semana_gestacional.value=semanas;
  document.all.semana_gestacional.readonly = false;
  
  var tiempo_fpp=fpp.getTime();
  miii= parseInt(dias_fpp*24*60*60*1000);
  total_1=fpp.setTime(tiempo_fpp+miii);
  
  var anio=fpp.getFullYear();
  var mes= ("0" + (fpp.getMonth()+1)).slice (-2);
  var dia= ("0" + fpp.getDate()).slice (-2);
  var fecha_fpp= dia + "/" + mes + "/" + anio;
  
  document.all.fpp.readonly = true;
  document.all.fpp.value=fecha_fpp;
  document.all.fpp.readonly = false;
  }
       
}


function diff_fecha(){
  
  if (document.all.f_diagnostico.value!="" && document.all.fum.value!="") {
    var f_c=document.all.f_diagnostico.value;
    var f_f=document.all.fum.value;
    
    var array_c = f_c.split("/");
    var array_f = f_f.split("/");
    
    var ch_c=array_c[2] + "/" + array_c[1] + "/" +array_c[0];
    var ch_f=array_f[2] + "/" + array_f[1] + "/" +array_f[0];
        
    var f_diagnostico_1= new Date (ch_c);
    var fum_1=new Date (ch_f);
    
    if (f_diagnostico_1>fum_1) { return false }
    else {return true}
  }
else {alert ('los valores de fecha de diagnostico y fum estan vacios, revisar');
return false;}  
}   

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
function calculo_imc_ult(){
  var t=document.all.altura_uterina.value;
  var p=document.all.peso_embarazada.value;
  var i=0;
  var peso=document.all.peso.value;
  var talla=document.all.talla.value;
  document.all.peso.value = peso.replace(',','.');
  document.all.talla.value = talla.replace(',','.');
  
   if(t!=0) i=(p/(t * t));
     var original=i;
   var result=Math.round(i*100)/100 ;
    document.all.imc_uterina.focus();
    document.all.imc_uterina.value=result; 
}

function habilita_hemg(){
  if(document.all.hemograma_selec.checked == true){   
      document.all.hemograma[0].disabled = false;
      document.all.hemograma[1].disabled = false;
      document.all.hemograma[2].disabled = false;
  }else{ 
       document.all.hemograma[0].checked = false;
       document.all.hemograma[1].checked = false;
       document.all.hemograma[2].checked = false;

      document.all.hemograma[0].disabled = true;
      document.all.hemograma[1].disabled = true;
      document.all.hemograma[2].disabled = true;
  
  }
}
function habilita_vsg(){
if(document.all.vsg_selec.checked == true){   
    document.all.vsg[0].disabled = false;
    document.all.vsg[1].disabled = false;
    document.all.vsg[2].disabled = false;
}else{ 
  document.all.vsg[0].checked = false;
  document.all.vsg[1].checked = false;
  document.all.vsg[2].checked = false;
    document.all.vsg[0].disabled = true;
    document.all.vsg[1].disabled = true;
    document.all.vsg[2].disabled = true;

}
    }
function habilita_glucemia(){
  if(document.all.glucemia_selec.checked == true){   
      document.all.glucemia[0].disabled = false;
      document.all.glucemia[1].disabled = false;
      document.all.glucemia[2].disabled = false;
  }else{ 
    document.all.glucemia[0].checked = false;
    document.all.glucemia[1].checked = false;
    document.all.glucemia[2].checked = false;
      document.all.glucemia[0].disabled = true;
      document.all.glucemia[1].disabled = true;
      document.all.glucemia[2].disabled = true;
  
  }
    }
function habilita_uremia(){
  if(document.all.uremia_selec.checked == true){   
      document.all.uremia[0].disabled = false;
      document.all.uremia[1].disabled = false;
      document.all.uremia[2].disabled = false;
  }else{ 
    document.all.uremia[0].checked = false;
    document.all.uremia[1].checked = false;
    document.all.uremia[2].checked = false;
      document.all.uremia[0].disabled = true;
      document.all.uremia[1].disabled = true;
      document.all.uremia[2].disabled = true;
  
  }
}

function habilita_ca(){
  if(document.all.ca_total_selec.checked == true){   
      document.all.ca_total[0].disabled = false;
      document.all.ca_total[1].disabled = false;
      document.all.ca_total[2].disabled = false;
  }else{ 
    document.all.ca_total[0].checked = false;
    document.all.ca_total[1].checked = false;
    document.all.ca_total[2].checked = false;
      document.all.ca_total[0].disabled = true;
      document.all.ca_total[1].disabled = true;
      document.all.ca_total[2].disabled = true;
  
  }
}

function habilita_orina(){
if(document.all.orina_cto_selec.checked == true){   
    document.all.orina_cto[0].disabled = false;
    document.all.orina_cto[1].disabled = false;
    document.all.orina_cto[2].disabled = false;
}else{ 
  document.all.orina_cto[0].checked = false;
  document.all.orina_cto[1].checked = false;
  document.all.orina_cto[2].checked = false;
    document.all.orina_cto[0].disabled = true;
    document.all.orina_cto[1].disabled = true;
    document.all.orina_cto[2].disabled = true;
  }
}   
function habilita_chagas(){
if(document.all.chagas_selec.checked == true){   
    document.all.chagas[0].disabled = false;
    document.all.chagas[1].disabled = false;
    document.all.chagas[2].disabled = false;
}else{ 
  document.all.hemograma[0].checked = false;
  document.all.chagas[1].checked = false;
  document.all.chagas[2].checked = false;
    document.all.chagas[0].disabled = true;
    document.all.chagas[1].disabled = true;
    document.all.chagas[2].disabled = true;
  }
} 

  
  
</script>

<form name='form1' action='comprobante_fichero.php' method='POST'>
<input type="hidden" value="<?php echo $usuario1?>" name="usuario1">
<input type="hidden" name="entidad_alta" value="<?php echo $entidad_alta?>">
<input type="hidden" name="id" value="<?php echo $id?>">
<input type="hidden" name="afifechanac" value="<?php echo $afifechanac?>">
<input type="hidden" name="afiapellido" value="<?php echo $afiapellido?>">
<input type="hidden" name="afinombre" value="<?php echo $afinombre?>">
<input type="hidden" name="afidni" value="<?php echo $afidni?>">
<input type="hidden" name="sexo" value="<?php echo $sexo?>">
<input type="hidden" name="anio_edad" value="<?php echo $anio_edad?>">
<?php
echo "<center><b><font size='+2' color='red'>$accion</font></b></center>";
echo "<center><b><font size='+2' color='blue'>$accion1</font></b></center>";

/*******Traemos y mostramos el Log **********/
if ($entidad_alta=='nu'){//carga de prestacion a paciente NO PLAN NACER
$q="SELECT 
	  *
	FROM
      fichero.log_fichero
    LEFT JOIN fichero.fichero using (id_fichero)           
	where fichero.id_beneficiarios=$id
	order by id_log_fichero";
$log=$db->Execute($q) or die ($db->ErrorMsg()."<br>$q");
}

if ($entidad_alta=='na'){//carga de prestacion a paciente PLAN NACER
$q="SELECT 
	  *
	FROM
      fichero.log_fichero
    LEFT JOIN fichero.fichero using (id_fichero)           
	where fichero.id_smiafiliados=$id 
	order by id_log_fichero";
$log=$db->Execute($q) or die ($db->ErrorMsg()."<br>$q");
}

?>
<div align="right">
	<input name="mostrar_ocultar_log" type="checkbox" value="1" onclick="if(!this.checked)
																	  document.all.tabla_logs.style.display='none'
																	 else 
																	  document.all.tabla_logs.style.display='block'
																	  "> Mostrar Logs
</div>	
<!-- tabla de Log de la OC -->
<div style="display:'none';width:98%;overflow:auto;<? if ($log->RowCount() > 3) echo 'height:60;' ?> " id="tabla_logs" >
<table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor=#cccccc>
<?while (!$log->EOF){?>
	<tr>
	      <td height="20" nowrap>Fecha <?php echo fecha($log->fields['fecha']). " " .Hora($log->fields['fecha']);?> </td>
	      <td nowrap > Usuario : <?php echo $log->fields['usuario']; ?> </td>
	      <td nowrap > Tipo : <?php echo $log->fields['tipo']; ?> </td>
	      <td nowrap > descipcion : <?php echo $log->fields['descripcion']; ?> </td>	      
	</tr>
	<?$log->MoveNext();
}?>
</table>
</div>
<hr>
<?/*******************  FIN  LOG  ****************************/?>
<table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
     <font size=+1><b>Beneficiario</b></font>    
    </td>
 </tr>
 <tr><td>
  <table width=90% align="center" class="bordes">
     <tr>
      <td id=mo colspan="2">
       <b> Descripción del Beneficiario</b>
      </td>
     </tr>
     <tr>
       <td>
        <table>
         <tr>
         	<td align="right">
         	  <b>Apellido:
         	</td>         	
            <td align='left'>
              <input type='text' name='afiapellido' value='<?php echo $afiapellido;?>' size=40 align='right' readonly></b>
             </td>          
             <td align="right">
              <b> Nombre:
         	</td>   
            <td  colspan="2">
             <input type='text' name='afinombre' value='<?php echo $afinombre;?>' size=40 align='right' readonly></b>
           </td>
          </tr>
          <tr>
           <td align="right">
         	  <b> Documento:
         	</td> 
           <td align='left'>
             <input type='text' name='afidni' value='<?php echo $afidni;?>' size=40 align='right' readonly></b>
           </td>          
           <td align="right">
         	  <b> Fecha de Nacimiento:
         	</td> 
           <td align='left'>
             <input type='text' name='afifechanac' value='<?php echo fecha($afifechanac);?>' size=40 align='right' readonly></b>
           </td>
          </tr>

         <tr>
           <td align="right" title="Edad a la Fecha actual">
         	 <b> Edad a la Fecha Actual:
           </td> 
           <td align='left'>
			 <?$fecha_actual=date("Y-m-d");
       $edad_con_meses=edad_con_meses($afifechanac,$fecha_actual);
			 $anio_edad=$edad_con_meses["anos"];
			 $meses_edad=$edad_con_meses["meses"];
			 $dias_edad=$edad_con_meses["dias"];
			 ?>
         	 <input type='text' name='edad' value='<?echo $anio_edad." Año/s, ".$meses_edad." Mes/es y ".$dias_edad." dia/s"?>' size=40 align='right' readonly></b>
           </td>
           <td align="right">
         	  <b> Efector Asignado:
         	</td> 
           <td align='left'>
             <input type='text' name='nombreefecto' value='<?php echo $nombre;?>' size=40 align='right' readonly></b>
           </td>
          </tr>
          <tr>
          <td align="right">
                      <b>Sexo:</b>
                  </td>           
                    <td align='left'>
                    <input type="text" name='sexo' value='<?php echo ($sexo=='F') ? 'Femenino' : 'Masculino'?>' readonly>
                    </td>
          </tr>
          
        </table>
      </td>     
     </tr>
   </table>  
   
	 <table class="bordes" align="center" width="90%">
		 <tr align="center" id="sub_tabla">
		 	<td colspan="2">	
		 		Registrar Atencion		 		
		 	</td>
		 </tr>
		 <tr><td class="bordes" align="center"><table>
			 <tr>
				 <td>
					 <tr><td align="center"><table>
						<tr>
						    <td align="right">
						    	<b>Lugar:</b>
						    </td>
						    <td align="left">		          			
					 			 <select name=cuie Style="width=257px" 
					        		onKeypress="buscar_combo(this);"
									onblur="borrar_buffer();"
									onchange="borrar_buffer();" >
									<?$user_login1=substr($_ses_user['login'],0,6);
									  if (es_cuie($_ses_user['login'])){
										$sql1= "select cuie, nombre, com_gestion from nacer.efe_conv where cuie='$user_login1' order by nombre";
									   }									
									  else{
										$usuario1=$_ses_user['id'];
										$sql1= "select nacer.efe_conv.nombre, nacer.efe_conv.cuie, com_gestion 
												from nacer.efe_conv 
												join sistema.usu_efec on (nacer.efe_conv.cuie = sistema.usu_efec.cuie) 
												join sistema.usuarios on (sistema.usu_efec.id_usuario = sistema.usuarios.id_usuario) 
												where sistema.usuarios.id_usuario = '$usuario1'
											 order by nombre";
									   }			 			   
									 $res_efectores=sql($sql1) or fin_pagina();
								 
								 while (!$res_efectores->EOF){ 
									$com_gestion=$res_efectores->fields['com_gestion'];
									$cuie1=$res_efectores->fields['cuie'];
									$nombre_efector=$res_efectores->fields['nombre'];
									if($com_gestion=='FALSO')$color_style='#F78181'; else $color_style='';
									?>
									<option value='<?php echo $cuie1;?>' Style="background-color: <?php echo $color_style;?>" <?if ($cuie1==$cuie)echo "selected"?>><?php echo $cuie1." - ".$nombre_efector?></option>
									<?
									$res_efectores->movenext();
									}?>
								</select>				 			
						    </td>
               					
						 	<td align="right">
						    	<b>Nombre Medico:</b>
						    </td>
						    <td align="left">
						    	 <input type="text" value="<?echo $nom_medico; ?>" name="nom_medico" Style="width=300px">
						    </td>		    
					    </tr>
					</table></td></tr>
					<tr><td align="center"><table>
						 <tr>
						 	<td align="right">
						    	<b>Fecha de Control:</b>
						    </td>
						    <td align="left">					    	
						    	<?if ($_POST['percentilo']!="Sugerir Percentilos")$fecha_comprobante=date("d/m/Y");?>
						    	 <input type=text id=fecha_comprobante name=fecha_comprobante value='<?php echo $fecha_comprobante;?>' size=12 readonly>
						    	 <?php echo link_calendario("fecha_comprobante");?>					    	 
						    </td>					 	
	            			<td align="right">
						    	<b>Fecha proximo Control:</b>
						    </td>
						    <td align="left">					    	
						    	<?if ($_POST['percentilo']!="Sugerir Percentilos")$fecha_pcontrol=date("d/m/Y"); ?>
						    	 <input type=text id=fecha_pcontrol name=fecha_pcontrol value='<?php echo $fecha_pcontrol;?>' size=12 readonly>
						    	 <?php echo link_calendario("fecha_pcontrol");?>					    	 
						    </td>	
							<td align="right">
	         	  				<b>Comentario:</b>
	         				</td>         	
	            			<td align='left'>
	              	 <!-- <textarea cols='40' rows='-2' name='comentario'><?if ($_POST['percentilo']=="Sugerir Percentilos")echo $comentario;else echo""; ?></textarea>-->
	            			<textarea cols='40' rows='-2' name='comentario'><?php echo $grupo_etareo?></textarea>
                    </td>

                    
						</tr>
					</table></td></tr> 
					<tr><td align="center"class="bordes">
		<!--			<table>
						<tr>
						  	<td>		      
						    	<input type="submit" name="guardar" value="Guardar" title="Guardar" Style="width=250px;height=30px;background:#CEF6CE" onclick="return control_nuevos()">
						   	</td>
						</tr> 
					</table></td></tr>  -->
					
				
         	</td></tr>
		</table></td></tr>	 
	</table>
		
		<table class="bordes" align="center" width="90%" >
				 <tr align="center" id="sub_tabla">
				 	<td colspan="2">	
				 		Informacion del Control		 		
				 	</td>
				 </tr>
 
				 <tr><td align="center" class="bordes"><table width="100%" >
						 <? $edad=date("Y-m-d")-$afifechanac; ?>
						 
						 <tr><td align="center"><table width="100%">
						 <tr>
							<td align="Center">
								<font color="Red">En los datos Numericos el separador de Decimales es "."</font>
							</td>
						</tr>
						</table></td></tr>				
						 
						 <tr><td align="center"><table width="100%">					
								 	<tr>
								 		<td align="right"><b>Peso:</b></td>
										<td>
										<input type="text" value="<?php echo $peso?>" name="peso" Style="width=50px" placeholder="Kg">
										</td>
										<td align="right"><b>Talla:</b></td>
										<td>
										<input type="text" value="<?php echo $talla?>" name="talla" Style="width=50px" onchange="calculo_imc()" placeholder="Mts">
										</td>
										<td align="right"><b>IMC:</b></td>
										<td>
										<input type="text" value="<?php echo $imc?>" name="imc" Style="width=50px" >
										</td>
										
                    <?if (GetCountDaysBetweenTwoDates($afifechanac,date("Y-m-d"))>1825){?>
					<td align="center"><table width="75%">
                    <tr><td colspan="2" bgcolor="#BEDFF1" align="center"><b> Tensión Arterial</td></tr>
                    <table FRAME="border" RULES="none" width="75%">

                    <td align="center">
					<tr><td align="right"><b><font color="Red">MAXIMA: </b></font></td>
                    <td align="left"><input type="text" value='<?php echo $tension_arterial_M;?>' name="tension_arterial_M" size=5></td></tr>
					
					<tr><td align="right"><b><font color="Red">MINIMA: </b></font></td> 
                    <td align="left"><input type='text' name='tension_arterial_m' value='<?php echo $tension_arterial_m;?>' size=5 align='right'></b></td></tr>
										  <tr>
                    <td colspan="2"><font align='right' color="Red">Tanto para Maxima y Minima los valores son Numeros Enteros</font></td>
                    </tr>
										</td>
                    </table>
                    </table></td>
                   
                    <?}
										
					if (GetCountDaysBetweenTwoDates($afifechanac,date("Y-m-d"))<=365){?>
					<td align="right">
					<b>Perim. Cefalico: </b>
					</td>         	
					<td align='left'>
					<input type="text" size=15 value="<?php echo $perim_cefalico?>" name="perim_cefalico" placeholder="Cm ">
					</td>
					<?}?>		
					</tr>
					</table></td></tr>
		
			<tr><td ><table width="85%">	   
			<tr>
			
			
			
			</table></td></tr>

						<tr><td align="center"><table width="100%">					
								 	<tr>
										<td align="right">
									    	<b>Público:</b>
									    </td>
										<td>
											<input type="radio" name="publico" value="NO">NO
											<input type="radio" name="publico" value="SI" checked>SI
										</td>

										<?if (GetCountDaysBetweenTwoDates($afifechanac,date("Y-m-d"))<730){?>

										<td align="right">
									    	<font size="2px" color="blue"><b>Tipo de Lactancia:</b></font>&nbsp;&nbsp;&nbsp;&nbsp;
									    </td>
										<td>
											<font size="2px" color="blue"><b>Exclusiva</b></font><input type="radio" name="tipo_lactancia" value="LME" <?if ($tipo_lactancia=="LME") echo "checked"?>>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<font size="2px" color="blue"><b>Predominante</b></font><input type="radio" name="tipo_lactancia" value="LMM" <?if ($tipo_lactancia=="LMM") echo "checked"?>>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<font size="2px" color="blue"><b>Artificial</b></font><input type="radio" name="tipo_lactancia" value="NLM" <?if ($tipo_lactancia=="NLM") echo "checked"?>>
										</td>
										<?}?>
									</tr>
						</table></td></tr>
						
	</table></td></tr>
	
	<?if($sexo=='F' and $edad >=11 ){?>	

	<tr><td><table class="bordes" border=1 align="center" width="90%">
	<tr align="center" id="sub_tabla">	    	
	<td colspan="2">Bajo programa Salud Sexual y Reproductivo</td>					    	
	</tr>
  <tr align="left">
  <td>
  <b>Bajo programa Salud Sexual y Reproductivo:</b> <input type="checkbox" name="salud_rep" value="SI" onclick="muestra_tabla(document.all.prueba_vida3,2);">	 
	</td>
	<td colspan=8><table id="prueba_vida3"  width="100%" style="display:none;">	 	
	<td >	
	      <b>Metodo Anticonceptivo:</b>	 		
	      <select name=metodo_anti Style="width=257px">
	      <option value=-1>Seleccione </option>
	      <option value="ACO-Orales" >ACO-Orales</option>
				<option value="ACI-Inyectables" >ACI-Inyectables</option> 
				<option value="ACOLAC-Orales de Lac" >ACOLAC-Orales de Lac</option> 
				<option value="AHE-Orales de Emerg" >AHE-Orales de Emerg</option> 
				<option value="DIU" >DIU</option> 
				<option value="PRESERVATIVO" >Preservativo</option> 
				<option value="PRESERVATIVO" >Naturales u Otros</option> 
				</select>
	</td>
	</table></td></tr>
	</table></td></tr>
  
  <!--<tr><td><table class="bordes" border=1 align="center" width="90%">
				 <tr align="center" id="sub_tabla">
				 	<td colspan="2">	
				 		Informacion Adicional de Embarazo	 		
				 	</td>
				 </tr>
				 <? if ($entidad_alta=='na'){//carga de prestacion a paciente PLAN NACER
					  $q_emb= "SELECT DISTINCT id_fichero,id_smiafiliados, 
										fpp, fum, f_diagnostico
										FROM fichero.fichero
										where fichero.fichero.id_smiafiliados=$id and (fichero.fichero.fpp >= CURRENT_DATE)  
										ORDER BY id_fichero DESC";
					    $res_emb=sql($q_emb, "Error al verificar embarazo existente") or fin_pagina();
					    if($res_emb->RecordCount!=EOF){
						    $fpp=$res_emb->fields["fpp"];
							$fum=$res_emb->fields["fum"];
							$f_diagnostico=$res_emb->fields["f_diagnostico"];
						}
				 } else {//PARA EL 'nu'
				 
					    $q_emb= "SELECT DISTINCT id_fichero,id_smiafiliados, 
										fpp, fum, f_diagnostico
					    				FROM fichero.fichero
										where fichero.fichero.id_beneficiarios=$id and (fichero.fichero.fpp >= CURRENT_DATE)  
										ORDER BY id_fichero DESC";
					    $res_emb=sql($q_emb, "Error al verificar embarazo existente") or fin_pagina();
					    if($res_emb->RecordCount!=EOF){
						    $fpp=$res_emb->fields["fpp"];
							$fum=$res_emb->fields["fum"];
							$f_diagnostico=$res_emb->fields["f_diagnostico"];
						}
		
				 }
				?>
				 
				<tr><td class="bordes" align="center"><table width="100%" >	
				<td align="left"><b>Embarazo: </b>
        <?//if ($sexo=='M') echo "disabled=true"; else echo "disabled=false";?>
				<input type="checkbox" name="embarazo" value="embarazo" onclick="muestra_tabla(document.all.prueba_vida2,2);"> 	 
				</td>
				
        <tr><td colspan=8><table id="prueba_vida2" border="1" width="100%" style="display:none;border:thin groove">
				<td><b>Fecha Diagnostico:</b></td>
				<td><input type=text id=f_diagnostico name=f_diagnostico value='<?php echo fecha($f_diagnostico);?>' size=15 readonly>
				<?php echo link_calendario("f_diagnostico");?></td>				    	 
				<td><b>FUM:</b></td>
				<td><input type=text id=fum name=fum value='<?php echo fecha($fum);?>' size=15 onblur="if (document.all.fum.value!='') calculo_fpp_sem()" readonly>
				<?php echo link_calendario("fum");?></td>										 
				<td><b>FPP:</b></td>
				<td><input type=text id=fpp name=fpp value='<?php echo fecha($fpp);?>' size=15 onfocus="if (document.all.fum.value!='') calculo_fpp_sem()" readonly>
				<?php echo link_calendario("fpp");?></td>
        <td><b>Semana de Gestacion:</b></td>
				<td><input type=text id=semana_gestacional name=semana_gestacional value='' size=5 ></td>
				
        <!--<tr id="sub_tabla"><td colspan="8">Embarazadas de Riesgo</td></tr>
        <tr align="left">
        <td>
        <b>Embarazo de Riesgo:</b> <input type="checkbox" name="emb_riesgo" value="SI" onclick="muestra_tabla(document.all.prueba_vida4,2);">  
        </td>
        <td colspan=8><table id="prueba_vida4"  width="100%" style="display:none;">   
        <td > 
        <b>Codigos:</b>     
            <select name="codigo_riesgo" Style="width=257px">
            <option value=-1>Seleccione </option>
            <option value="N004">N004 - Notificación de Factores de Riesgo</option>
            <option value="N006">N006 - Referencia por embarazo de alto riesgo de Nivel 2 ó 3 a niveles de complejidad superiores</option> 
            </select>
            </td>
          </tr>
        </table></td></tr>
        
      </table></td></tr>
    </table></td></tr>			
	</table></td></tr>-->    
	<?}?>
			
	<?if (GetCountDaysBetweenTwoDates($afifechanac,date("Y-m-d"))>=2190) $disabled1="";
      else $disabled1="disabled";?>
  <tr><td><table class="bordes" border=1 align="center" width="90%">
  <tr align="center" id="sub_tabla">        
  <td colspan="2">Talleres</td>                
  </tr>
  <tr align="left">
  <td>
  <b>Talleres:</b> <input type="checkbox" name="taller" value="SI" onclick="muestra_tabla(document.all.prueba_vida5,2);" <?php echo $disabled1?>>  
  </td>
  <td colspan=8><table id="prueba_vida5"  width="100%" style="display:none;">   
  <td > 
      <?$sql_taller="SELECT *  from facturacion.nomenclador where id_nomenclador_detalle=18 and grupo='TA'
          and adol='1' order by 2";
        $res_taller=sql($sql_taller) or fin_pagina();?>
      <select name="codigo_taller" Style="width=257px">
      <option value=-1>Seleccione </option>
      <?while (!$res_taller->EOF){
        $codigo_taller=$res_taller->fields['codigo'];
        $desc_taller=$res_taller->fields['descripcion'];?>

          <option value="<?php echo $codigo_taller?>"><?echo $codigo_taller." - ".$desc_taller?></option>
        <?$res_taller->movenext();
      }?>
          
      </select>
  </td>
  <td>
  <input type=text id="fecha_taller" name="fecha_taller" value='<?php echo $fecha_taller=date("d/m/Y");?>' size=12>
                   <?php echo link_calendario("fecha_taller");?></td>
  </table></td></tr>
  </table></td></tr>
  <?//}?>
  
  <tr><td><table width="90%" class="bordes" align="center">
	<tr align="center" id="mo">
	  <td align="center" width="3%">
	   <img id="imagen_2" src="<?php echo $img_ext?>" border=0 title="Mostrar Comprobantes" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida1,2);" >
	  </td>
	  <td align="center">
	   <b>Datos Adicionales</b>
	  </td>
	</tr>
	</table></td></tr>
	<tr><td><table id="prueba_vida1" border="1" width="100%" style="display:none;border:thin groove">
					
			<tr><td align="center"><table>	   
			<tr>
							<td align="right">
								 <b>Carnet de Vacunacion:</b>
							</td>
							<td>
								Completo <input type=radio name='c_vacuna' value='completo'<? if ($id_planilla) echo "readonly"?> checked >
								Incompleto <input type=radio name='c_vacuna' value='incompleto'<? if ($id_planilla) echo "readonly"?>>
							</td>
							<td align="right">
								<b>Agudeza Visual:</b>
							</td>
							<td>
								<input type="text" value="" name="ag_visual" Style="width=50px">
							</td>							
							<td align="right">
							   	<b>TUNNER:</b>
							</td>
							<td>
								<input type="text" value="" name="tunner" Style="width=50px">
							</td>
			</tr>
			</table></td></tr>
		
	<? if($edad <=1 ){?>
		<tr><td align="center"><table>	      
					
						 	<tr>	
								<td align="left">
							    	<b>Tasa Materna:</b>
							    </td>
								<td align="left">			 	
									 <select name=tasa_materna Style="width=100px" >
									  <option value=-1 selected>Seleccione</option>
										  <option value=SI>SI</option>
										  <option value=NO>NO</option>			  		  
									 </select>
								</td>							
							</tr>
		</table></td></tr>
	<?}?>
		
		<tr><td ><table>
						 	<tr>
								<td align="left">
							    	<b>Examen Clinico General:</b>
							    </td>
								<td>
									<textarea cols='40' rows='-2' value="" name='ex_clinico_gral'></textarea>
								</td>								
							
								<td align="left">
							    	<b>Examen Traumatologico:</b>
							    </td>
								<td >
									<textarea cols='40' rows='-2' value="" name='ex_trauma'></textarea>
								</td>								
							</tr>
							<tr>								
								<td align="left">
							    	<b>Examen Cardiologico:</b>
							    </td>
								<td >
									<textarea cols='40' rows='-2' value="" name='ex_cardio'></textarea>
								</td>
						
						
						 								
								<td align="right">
							    	<b>Examen Odontologico:</b>
							    </td>
								<td align="left">
									<textarea cols='40' rows='-2' value="" name='ex_odontologico'></textarea>
								</td>
							</tr>
		</table></td></tr>	
					
					<? 
					// --------------------------------------------- mayores de 12 ----------------------------------------
		if($edad >=12 ){?>
					<tr><td align="left"><table><tr>								
								<td align="left">
											 <b>ECG:</b>
									</td>
									<td>
											<textarea cols='40' rows='-2' value="" name='ex_ecg'></textarea>
									</td>
														
									<td align="left">
									<b>Observacion ECG:</b>  
								</td>
								<td >	
									<textarea cols='40' rows='-2' value="" name='obs_ecg'></textarea>
								</td>
					
					</tr></table></td></tr>
					
					<tr><td><table align="center" width="50%" border="1">
												<b>Laboratorio:</b>
													<tr><td>		
															    <tr>
																	<td>
																		<input type="checkbox" name="hemograma_selec" onClick="habilita_hemg();"> Hemograma 
																		<td>	
																			<input type="radio" name="hemograma" value="N" disabled> N
																			</td>
																		<td><input type="radio" name="hemograma" value="A" disabled> A
																		</td>
																		<td><input type="radio" name="hemograma" value="No Realizado" disabled> No Realizado
																		</td>
																	</td>
																</tr>
																<tr>
																	<td>
																		<input type="checkbox" name="vsg_selec" onClick="habilita_vsg();"> VSG 
																		<td>	
																			<input type="radio" name="vsg" value="N" disabled> N
																			</td>
																		<td><input type="radio" name="vsg" value="A" disabled> A
																		</td>
																		<td><input type="radio" name="vsg" value="No Realizado" disabled> No Realizado
																		</td>
																	</td>
																</tr>
																<tr>
																	<td>
																		<input type="checkbox" name="glucemia_selec" onClick="habilita_glucemia();"> Glucemia 
																		<td>	
																			<input type="radio" name="glucemia" value="N"  disabled> N
																		</td>
																		<td>	
																			<input type="radio" name="glucemia" value="A" disabled> A
																		</td>
																		<td>
																			<input type="radio" name="glucemia" value="No Realizado" disabled> No Realizado
																		</td>
																		
																	</td>
																</tr>
																<tr>
																	<td>
																		<input type="checkbox" name="uremia_selec" onClick="habilita_uremia();"> Uremia 
																		<td>	
																			<input type="radio" name="uremia" value="N" disabled> N 
																			</td>
																		<td><input type="radio" name="uremia" value="A" disabled> A
																		</td>
																		<td><input type="radio" name="uremia" value="No Realizado" disabled> No Realizado
																		</td>
																	</td>
																</tr>
																<tr>
																	<td>
																		<input type="checkbox" name="ca_total_selec" onClick="habilita_ca();"> Col. Total 
																		<td>	
																			<input type="radio" name="ca_total" value="N" disabled> N
																			</td>
																		<td> <input type="radio" name="ca_total" value="A" disabled> A 
																		</td>
																		<td><input type="radio" name="ca_total" value="No Realizado" disabled> No Realizado
																		</td>
																	</td>
																</tr>
																<tr>
																	<td>
																		<input type="checkbox" name="orina_cto_selec" onClick="habilita_orina();"> Orina 
																		<td>	
																			<input type="radio" name="orina_cto" value="N" disabled> N
																			</td>
																		<td><input type="radio" name="orina_cto" value="A" disabled> A 
																		</td>
																		<td><input type="radio" name="orina_cto" value="No Realizado" disabled> No Realizado
																		</td>
																	</td>
																</tr>
																<tr>
																	<td>
																		<input type="checkbox" name="chagas_selec" onClick="habilita_chagas();"> Chagas 
																		<td>	
																			<input type="radio" name="chagas" value="N" disabled> N
																			</td>
																		<td><input type="radio" name="chagas" value="A" disabled> A
																		</td>
																		<td><input type="radio" name="chagas" value="No Realizado" disabled> No Realizado
																		</td>
																	</td>
																</tr>
													</td></tr>
														
							</table></td></tr>
							
							<tr><td align="left"><table>
									<tr>
										<td>
											<b>Observacion Laboratorio:</b>  
										</td>
										<td>	
											<textarea cols='40' rows='-2' value="" name='obs_laboratorio'></textarea>
										</td>
							</tr></table></tr>
							
						<tr><td ><table width="95%">
									<tr>
										<td align="right">
											<b>RX de Torax:</b>  
										</td>
										<td align="left">
											<input type="checkbox" value='SI' name="rx_torax"> 
										<td>
										<td align="right">
											<b>RX de Columna Vertebral:</b>  
										</td>
										<td align="left">
											<input type="checkbox" value='SI' name="rx_col_vertebral">  
										<td>	
										<td align="right">
											<b>Observaciones:</b>  
										</td>
										<td align="left">	
											<textarea cols='40' rows='-2' value="" name='rx_observaciones'></textarea>
										</td>
									</tr>
						</table></td></tr>	
						
						<tr><td align="left"><table width="80%">
									<tr>
										<td align="right">
											<b>Otros:</b>  
										</td>
										<td align="left">	
											<textarea cols='40' rows='-2' value="" name='otros'></textarea>
										</td>
										<td align="right">
											<b>Observaciones:</b>  
										</td>
										<td align="left">	
											<textarea cols='40' rows='-2' value="" name='otros_obs'></textarea>
										</td>
									</tr>
						</table></td></tr>
									
				<?} // ---------------------------------------------Mayor de 16 años---------------------------------------- 
				if ($edad >=16){?>					
						<tr><td align="left"><table width="80%">
								<tr>	
									<td align="right">
								    	<b>Ergometria:</b>
								    </td>
									<td align="left">
										<textarea cols='40' rows='-2' value="" name='ergometria'></textarea>
									</td>								
									
									<td align="right">
								    	<b>Observaciones:</b>
								    </td>
									<td align="left">
										<textarea cols='40' rows='-2' value="" name='obs_adolesc'></textarea>
									</td>								
								</tr>
							</table></td></tr>
				<?}?>	
							
			<?if($edad > 6 ){?>
			<tr><td><table class="bordes" align="center" width="80%">
					 <tr align="center" id="sub_tabla">
					 	<td colspan="2">	
					 		Conclusion de Ficha Medica Intercolegial 		 		
					 	</td>
					 </tr>
					<tr><td align="center" colspan="2">
								<B><font size="+1">
								<input type="radio" name="conclusion" value="Apto" checked>Apto
								&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="conclusion" value="Parcial">Apto Parcial
								&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="conclusion" value="No Apto">No Apto
								</font></B>
					</td></tr>
			</table></td></tr>	
			<?}?>
			
	</table></td></tr>	
  
  
  <tr><td><table width="90%" class="bordes" align="center">
  <tr align="center" id="mo">
    <td align="center" width="3%">
     <img id="imagen_2" src="<?php echo $img_ext?>" border=0 title="Mostrar Epidemiologico" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida7,2);" >
    </td>
    <td align="center">
     <b>Datos Epidemiologicos</b>
    </td>
  </tr>
  </table></td></tr>
  <tr><td><table id="prueba_vida7" border="1" width="100%" style="display:none;border:thin groove">
          
      <tr><td align="center"><table>     
      <tr><td><table class="table table-striped" class="table table-bordered" align="center" width="90%" >
      <font style="background-color:yellow;font-size:14;"><b>Dada la gravedad de las patologias en este grupo,proceder <u>ante la menor sospecha</u>,denunciando los casos por <u>telefono/fax</u> ,con los datos personales solicitados en la planilla <u>C2</u> y adjuntar la ficha especial del caso cuando corresponda</b></font>
      <BR><BR>
      <tr><td>   
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="bolutismo"></td>
      </td><td width="300"><b>BOTULISMO</b></td>
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('bolutismo')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td>    
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="hanta_virus"></td><td width="300"><b>HANTA VIRUS</b></td>
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="marea_roja"></td><td width="300"><b>MAREA ROJA</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="psitacosis"></td><td width="300"><b>PSITACOSIS</b></td>
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('psitacosis')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="tetanos"></td><td width="300"><b>TETANOS</b></td>
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('tetanos')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="encefal_espong"></td><td width="300"><b>ENCEFAL.ESPONGIFORME</b></td>
      <td></td> 
      </tr>
      
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="carbunco"></td><td width="300"><b>CARBUNCO</b></td>
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('carbunco')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="fiebre_rec"></td><td width="300"><b>FIEBRE REC(piojos)</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="otros_toxicos"></td><td width="300"><b>OTROS TOXICOS</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="rabia_humana"></td><td width="300"><b>RABIA HUMANA</b></td>
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('rabia_humana')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="tetanos_neo"></td><td width="300"><b>TETANOS NEONATAL</b></td> 
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('tetanos_neo')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="encefalitis"></td><td width="300"><b>ENCEFALITIS</b></td>
      <td></td> 
      </tr>
      
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="colera"></td><td width="300"><b>COLERA</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="tifus"></td><td width="300"><b>TIFUS EXANTEM.(piojos)</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="paralasis"></td><td width="300"><b>PARALISIS FLACCIDAS</b></td>
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('paralasis')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="sarampion"></td><td width="300"><b>SARAMPION</b></td>
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('sarampion')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="mening"></td><td width="300"><b>MENING.MENINGOCOCCIDAS</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="fiebre_hem_arg"></td><td width="300"><b>FIEBRE HEMORR.ARG</b></td>
      <td></td> 
      </tr>
      
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="dengue"></td><td width="300"><b>DENGUE</b></td>
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('dengue')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="fiebre_amarilla"></td><td width="300"><b>FIEBRE AMARILLA</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="peste_humana"></td><td width="300"><b>PESTE HUMANA</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="rubeola"></td><td width="300"><b>RUBEOLA</b></td>
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('rubeola')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="influenzae"></td><td width="300"><b>MENING.HAEMOP.INFLUENZAE</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="paludismo"></td><td width="300"><b>PALUDISMO</b></td> 
      <td></td> 
      </tr>
      
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="difteria"></td><td width="300"><b>DIFTERIA</b></td> 
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('difteria')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="intox_alim"></td><td width="300"><b>INTOXICACION ALIMENTARIA</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="sika"></td><td width="300"><b>SIKA</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="rubeola_cong"></td><td width="300"><b>RUBEOLA CONGENITA</b></td>
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('rubeola_cong')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td> 
      </tr>
      </td></tr>
    </table></td></tr>
    <tr><td><BR></td></tr>
    <tr><td><BR></td></tr>
    <tr><td><table class="table table-striped" class="table table-bordered" align="center" width="90%">
      <font style="background-color:yellow;font-size:14;"><b>Para las enfermedades de este grupo debera en todos los casos consignarse los datos personales solicitados en la planilla <u>C2</u> y adjuntar la ficha especial cuando corresponda.</b></font>
      <BR><BR>
      <tr><td>   
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="alacranismo"></td><td width="300"><b>ALACRANISMO</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="tifoidea"></td><td width="300"><b>FIEBRE TIFOIDEA</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="intox_plag"></td><td width="300"><b>INTOX.POR PLAG.AGRIC.</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="mening_tuber"></td><td width="300"><b>MENINGITIS TUBERCULOSA</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="parotiditis"></td><td width="300"><b>PAROTIDITIS</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="chagas_agudo"></td><td width="300"><b>CHAGAS AGUDO</b></td>
      <td></td> 
      </tr>
      
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="aracnoidismo"></td><td width="300"><b>ARACNOIDISMO</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="paratifoidea"></td><td width="300"><b>FIEBRE PARATIFOIDEA</b></td>
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="intox_carb"></td><td width="300"><b>INTOX.POR MON.CARB.</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="fiebre_paratifoidea"></td><td width="300"><b>FIEBRE PARATIFOIDEA</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="sepsis"></td><td width="300"><b>SEPSIS CONNATAL</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="hiv_sida"></td><td width="300"><b>COND.ACUMINADOS-HIV-SIDA</b></td>
      <td></td> 
      </tr>
      
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="brucelosis"></td><td width="300"><b>BRUCELOSIS</b></td> 
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('brucelosis')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="hep"></td><td width="300"><b>HEP.A.B.C.D.E.</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="lepra"></td><td width="300"><b>LEPRA</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="mening_viral"></td><td width="300"><b>MENINGITIS VIRAL</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="toxoplasmosis"></td><td width="300"><b>TOXOPLASMOSIS</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="sifilis"></td><td width="300"><b>SIFILIS CONGENITA</b></td>
      <td></td> 
      </tr>
      
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="coqueluche"></td><td width="300"><b>COQUELUCHE</b></td>
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('coqueluche')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="hep_s_esp"></td><td width="300"><b>HEPATITIS S/ESPECIFICAR</b></td>
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('hep_s_esp')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="leptos"></td><td width="300"><b>LEPTOSPIROSIS</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="mening_viral_ent"></td><td width="300"><b>MENINGITIS VIRAL X ENT.</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="triquinosis"></td><td width="300"><b>TRIQUINOSIS</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="meningitis_otro_germen"></td><td width="300"><b>MENINGITIS OTRO GERMEN</b></td>
      <td></td> 
      </tr>
      
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="sifilis_s_especificar"></td><td width="300"><b>SIFILIS S/ESPEC.</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="hidatidosis"></td><td width="300"><b>HIDATIDOSIS</b></td> 
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('hidatidosis')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="mening_s_e"></td><td width="300"><b>MENINGITIS S/ESP.</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="mening_para"></td><td width="300"><b>MENING.VIR.P/PAROTIDITIS</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="tuberculosis"></td><td width="300"><b>TUBERCULOSIS</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="supur"></td><td width="300"><b>SUPUR.GONOC.Y NO GONOC.Y S/ESP.</b></td>
      <td></td> 
      </tr>
      
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="chagas_cong"></td><td width="300"><b>CHAGAS CONGENITO</b></td> 
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('chagas_cong')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="inf_hosp"></td><td width="300"><b>INFEC.HOSPITALARIAS</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="mening_bact"></td><td width="300"><b>MENINGITIS BACT.S/G</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="mor_perro"></td><td width="300"><b>MORDEDURA DE PERRO</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="sars"></td><td width="300"><b>SARS</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="leish"></td><td width="300"><b>LEISHMANIASIS</b></td> 
      <td align='center'>
      <a target='_blank' href='<?echo carga_planilla('leish')?>' title='Imprime Planilla' onclick="return confirm('¿Esta Seguro que Desea Imprimir la Planilla correspondiente? ')"><IMG src='<?php echo $html_root?>/imagenes/pdf_logo.gif' height='20' width='20' border='0'></a></IMG>
      </td>
      </tr>
      
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="mening_neumoc"></td><td width="300"><b>MENINGITIS NEUMOCOCO</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="ofidismo"></td><td width="300"><b>OFIDISMO</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="suh"></td><td width="300"><b>SUH</b></td>
      <td></td> 
      </tr>
      
    </table></td></tr>
    
    <tr><td><BR></td></tr>
    <tr><td><BR></td></tr>
    <tr><td><table class="table table-striped" class="table table-bordered" align="center" width="90%">
      <font style="background-color:yellow;font-size:14;"><b>Patologias tipo III.</b></font>
      <BR><BR>
      <tr><td>   
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="acci_trans"></td><td width="300"><b>ACCIDENTE DE TRANSITO</b></td>
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="acci_hogar"></td><td width="300"><b>ACCIDENTE DE HOGAR</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="acci_s_esp"></td><td width="300"><b>ACCIDENTE S/ESPECIFICAR</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="cancer"></td><td width="300"><b>
      CANCER</b></td>
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="chandro_blando"></td><td width="300"><b>CHANCRO BLANDO</b></td>
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="diabetes"></td><td width="300"><b>DIABETES</b></td>
      <td></td> 
      </tr>
      
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="diarrea"></td><td width="300"><b>DIARREAS</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="glanuloma"></td><td width="300"><b>GLANULOMA INGUINAL</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="enf_tipo_influenza"></td><td width="300"><b>INFLUENZA</b></td>
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="bronquitis_menor"></td><td width="300"><b>BRONQUITIS MENOR 2 AÑOS</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="neumonia"></td><td width="300"><b>NEUMONIA</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="intox_medicamento"></td><td width="300"><b>INTOX.MEDICAMENTOSA</b></td>
      <td></td> 
      </tr>
      
      <tr>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="intox_plaguicidas"></td><td width="300"><b>INTOX. POR PLAGUICIDAS USO DOMESTICO</b></td> 
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="intox_plag_s_esp"></td><td width="300"><b>INTOX. POR PLAGUISIDAS SIN ESPECIFICAR</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="sec_gen_mujer"></td><td width="300"><b>VARICELA</b></td>
      <td></td>
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="sindrome_febril"></td><td width="300"><b>SINDROME FEBRIL INESPECIFICO</b></td>
      <td></td> 
      <td style="padding:10px;"><input type="radio" name="epi" id="epi" value="ninguno" checked></td><td width="300"><b>NINGUNO</b></td>
      <td></td>
      </tr>
    </table></td></tr>
    
  </table></td></tr>
</table></td></tr>

		 
<?//tabla de comprobantes
if ($entidad_alta=='nu'){//carga de prestacion a paciente NO PLAN NACER
	$query="SELECT nacer.efe_conv.nombre,
				fichero.fichero.nom_medico,
				fichero.fichero.fecha_control,
				fichero.fichero.periodo, *
			FROM
	fichero.fichero
	INNER JOIN nacer.efe_conv ON fichero.fichero.cuie = nacer.efe_conv.cuie
	where id_beneficiarios='$id' AND (anular='' or anular IS NULL)
  and (enfer_epidemeologica='' or enfer_epidemeologica is null) 
  and (taller='NO' or taller is null)
  and (embarazo_riesgo='' or embarazo_riesgo is null)
	order by fichero.id_fichero DESC";
}elseif ($entidad_alta=='na'){//carga de prestacion a paciente PLAN NACER
			$query="SELECT nacer.efe_conv.nombre,
			nacer.efe_conv.cuie,
				fichero.fichero.nom_medico,
				fichero.fichero.fecha_control,
				fichero.fichero.periodo,*
			FROM
			fichero.fichero
			INNER JOIN nacer.efe_conv ON fichero.fichero.cuie = nacer.efe_conv.cuie
			where id_smiafiliados='$id' and (anular='' or anular IS NULL)
      and (enfer_epidemeologica='' or enfer_epidemeologica is null) 
      and (taller='NO' or taller is null)
      and (embarazo_riesgo='' or embarazo_riesgo is null)
			order by fichero.id_fichero DESC";
		}
$res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();?>

<tr><td><table width="100%" class="bordes" align="center">
	<tr align="center" id="mo">
	  <td align="center" width="3%">
	   <img id="imagen_2" src="<?php echo $img_ext?>" border=0 title="Mostrar Comprobantes" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida,2);" >
	  </td>
	  <td align="center">
	   <b>Prestaciones</b>
	  </td>
	</tr>
</table></td></tr>
<tr><td><table id="prueba_vida" border="1" width="100%" style="display:none;border:thin groove">
	<?
	
	if ($res_comprobante->recordcount()==0){?>
	 <tr>
	  <td align="center">
	   <font size="3" color="Red"><b>No existen Prestaciones</b></font>
	  </td>
	 </tr>
	 <?}
	 else{	 	
	 	?>
	 	<tr id="sub_tabla">	
	 	    <td width=1%>&nbsp;</td>
	 		<td width="30%">Efector</td>
	 		<td width="30%">Medico</td>
	 		<td width="30%">Comentario</td>
	 		<td width="10%">Fecha Prestación</td>	 		
	 		<td width="10%">Periodo</td>
	 		<td width="10%">Fecha Proximo Control</td>	
	 		<td width="10%">Asistio Proximo Control</td>
	 		<td >Anular</td>	 	
	 	</tr>
	 	<?//1=ultimo control cargado 0= controles anterioeres
	 	$res_comprobante->movefirst();
	 	while (!$res_comprobante->EOF) {
	 		
	 		$ref =encode_link("fichero_muestra.php",array("id_fichero"=>$res_comprobante->fields['id_fichero'],"id"=>$id,"entidad_alta"=>$entidad_alta,"pagina_viene"=>"fichero_muestra.php"));
           
            $id_tabla="tabla_".$res_comprobante->fields['id_fichero'];	
	 		$onclick_check=" javascript:(this.checked)?Mostrar('$id_tabla'):Ocultar('$id_tabla')";?>
	 		<tr <?php echo atrib_tr()?>>
	 			<td>
	              <input type=checkbox name=check_prestacion value="" onclick="<?php echo $onclick_check?>" class="estilos_check">
	            </td>	
		 		<td align="center" onclick="window.open('<?php echo $ref?>','','toolbar=0,location=0,directories=0,status=0, menubar=0,scrollbars=1');"><?php echo $res_comprobante->fields['cuie'].' - '.$res_comprobante->fields['nombre']?></td>
		 		<td align="center" onclick="window.open('<?php echo $ref?>','','toolbar=0,location=0,directories=0,status=0, menubar=0,scrollbars=1');"><?if ($res_comprobante->fields['nom_medico']!="") echo $res_comprobante->fields['nom_medico']; else echo "&nbsp"?></td>
		 		<td align="center" onclick="window.open('<?php echo $ref?>','','toolbar=0,location=0,directories=0,status=0, menubar=0,scrollbars=1');"><?if ($res_comprobante->fields['comentario']!="") echo $res_comprobante->fields['comentario']; else echo "&nbsp"?></td>
		 		<td align="center" onclick="window.open('<?php echo $ref?>','','toolbar=0,location=0,directories=0,status=0, menubar=0,scrollbars=1');"><?php echo fecha($res_comprobante->fields['fecha_control'])?></td>		 		
		 		<td align="center" onclick="window.open('<?php echo $ref?>','','toolbar=0,location=0,directories=0,status=0, menubar=0,scrollbars=1');"><?php echo $res_comprobante->fields['periodo']?></td>	
		 		<td align="center" onclick="window.open('<?php echo $ref?>','','toolbar=0,location=0,directories=0,status=0, menubar=0,scrollbars=1');"><?php echo fecha($res_comprobante->fields['fecha_pcontrol'])?></td>		 
		 		<td align="center" onclick="<?//=$onclick_elegir?>"><?if ($res_comprobante->fields['fecha_pcontrol_flag']!="0") echo "NO"; else echo "SI"?></td>			 		
		 		<?$ref1 = encode_link("comprobante_fichero.php",array("id_fichero"=>$res_comprobante->fields['id_fichero'], "anular"=>"anular", "entidad_alta"=>$entidad_alta,"id"=>$id ));
            		$onclick_anular="if (confirm('Esta Seguro que Desea ANULAR Comprobante $id_comprobante_aux?')) location.href='$ref1'
            						else return false;	";
					$onclick_no_anular="alert('Ud. no tiene permiso para anular el comprobante')";?>
		 		<?if ($res_comprobante->fields['cuie']==$_ses_user['login']
					  or $_ses_user['login']=='miguel'
					  or $_ses_user['login']=='mario'
					  or $_ses_user['login']=='glhuiller'
					  or $_ses_user['login']=='dquevedo') {?>
				<td align="center" onclick="<?php echo $onclick_anular?>"><img src='../../imagenes/sin_desc.gif' style='cursor:pointer;'></td>	
		 		<?} else {?>
				<td align="center" onclick="<?php echo $onclick_no_anular?>"><img src='../../imagenes/candado1.gif' style='cursor:pointer;'></td>
				<?}?>
				</tr>	
		 	<tr>
	          <td colspan=9>					  
	                  <div id=<?php echo $id_tabla?> style='display:none'>
	                  <table width=100% align=center class=bordes>
	                  			    <tr id=ma>		                               
		                               <td>Peso</td>
		                               <td>Talla</td>
		                               <td>IMC</td>
		                               <td>TA</td>
		                               <td>Perc. Peso/edad</td>	  
		                               <td>Perc. talla/edad</td>
		                               <td>Perc. IMC/edad</td>	  
		                               <td>Perc. Peso/Talla</td>	   
		                               <? if($edad <=1 ){?> 
		                               <td>Perimet.Cefarico</td>	  
		                               <td>Perc.Perimet.Cefarico/edad</td>	   
		                               <? }?>                         
		                            </tr>
		                         <tr>
			                            <td align="center" class="bordes"><?if ($res_comprobante->fields['peso']=="") echo "&nbsp"; else echo $res_comprobante->fields["peso"]?></td>			                                 
			                            <td align="center" class="bordes"><?if ($res_comprobante->fields['talla']=="") echo "&nbsp"; else echo $res_comprobante->fields["talla"]?></td>
			                            <td align="center" class="bordes"><?if ($res_comprobante->fields['imc']=="") echo "&nbsp"; else echo$res_comprobante->fields["imc"]?></td>
			                            <td align="center" class="bordes"><?if ($res_comprobante->fields['ta']=="") echo "&nbsp";else echo  $res_comprobante->fields["ta"]?></td>
			                            
			                <td align="center" class="bordes"><?
			                if($res_comprobante->fields['percen_peso_edad']=="1")echo "<3"; 
			                elseif ($res_comprobante->fields['percen_peso_edad']=="2")echo "3-10";  
			                elseif ($res_comprobante->fields['percen_peso_edad']=="3")echo ">10-90 ";  
			                elseif ($res_comprobante->fields['percen_peso_edad']=="4")echo ">90-97 ";  
			                elseif ($res_comprobante->fields['percen_peso_edad']=="5")echo ">97";
			                else echo"Dato Sin Ingresar";?></td>
			                
			                <td align="center" class="bordes"><?
			                if($res_comprobante->fields['percen_talla_edad']=="1")echo "<3"; 
			                elseif ($res_comprobante->fields['percen_talla_edad']=="2")echo "3-10";  
			                elseif ($res_comprobante->fields['percen_talla_edad']=="3")echo ">10-90 ";  
			                elseif ($res_comprobante->fields['percen_talla_edad']=="4")echo ">90-97 ";  
			                elseif ($res_comprobante->fields['percen_talla_edad']=="5")echo ">97";
			                else echo"Dato Sin Ingresar";?></td>	
			                
			                 <td align="center" class="bordes"><?
			                 if ($res_comprobante->fields['percen_imc_edad']=='1') echo "<3"; 
			                 elseif ($res_comprobante->fields['percen_imc_edad']=='2') echo "3-10"; 
			                 elseif ($res_comprobante->fields['percen_imc_edad']=='3') echo ">10-90"; 
			                 elseif ($res_comprobante->fields['percen_imc_edad']=='4') echo ">90-97";
			                 elseif ($res_comprobante->fields['percen_imc_edad']=='5') echo ">97"; 
			                 else echo "Dato Sin Ingresar";?></td>
			                    		
			                <td align="center" class="bordes"><?
			                if ($res_comprobante->fields['percen_peso_talla']=='1') echo "<3"; 
			                elseif ($res_comprobante->fields['percen_peso_talla']=='2') echo "3-10"; 
			                elseif ($res_comprobante->fields['percen_peso_talla']=='3') echo ">10-90"; 
			                elseif ($res_comprobante->fields['percen_peso_talla']=='4') echo ">90-97"; 
			                elseif ($res_comprobante->fields['percen_peso_talla']=='5') echo ">97"; 
			                else  echo "Dato Sin Ingresar"?></td>			                                 
			                
			                           <? if($edad <=1 ){?> 
		                    <td align="center" class="bordes"><?
		                    if ($res_comprobante->fields['perim_cefalico']=="") echo "&nbsp"; echo number_format($res_comprobante->fields["perim_cefalico"],2,',',0)?></td>
			                <td align="center" class="bordes"><?
			                if ($res_comprobante->fields['percen_perim_cefali_edad']=='1') echo "-3"; 
			                elseif ($res_comprobante->fields['percen_perim_cefali_edad']=='2') echo "3-97"; 
			                elseif ($res_comprobante->fields['percen_perim_cefali_edad']=='3') echo "+97"; 
			                else echo "Dato Sin Ingresar";?></td>		   
		                               <? }?>         
			                          
			                </tr>                            	                            
	               </table>
	               </div>
	
	         </td>
	      </tr>  	
	 		<?$res_comprobante->movenext();
	 	}
	 }?>
</table></td></tr>
					<tr><td align="center"class="bordes">
					<table>
						<tr>
						  	<td>		      
						    	<input type="submit" name="guardar" value="Guardar" title="Guardar" Style="width=250px;height=30px;background:#CEF6CE" onclick="return control_nuevos()">
						   	</td>
						</tr> 
					</table></td></tr> 
					
				
         	</td></tr> 
 <tr><td><table width=100% align="center" class="bordes">
  <tr align="center">
    <td> 	
   	 	<input type=button name="volver" value="Volver" onclick="document.location='../entrega_leche/listado_beneficiarios_leche.php'"title="Volver al Listado" style="width=150px">
    </td>
  </tr>
 </table></td></tr>
 
</td></tr></table>
</table>

</form>
<?php echo fin_pagina();// aca termino ?>
