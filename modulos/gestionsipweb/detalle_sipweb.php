<?php
/*
$Author: Seba $
$Revision: 1.0 $
$Date: 2018/01/23 $
*/

require_once ("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$proyeccion="SELECT DISTINCT ON (numero_doc)
  uad.beneficiarios.apellido_benef,
  uad.beneficiarios.nombre_benef,
  --uad.beneficiarios.clase_documento_benef,
  --uad.beneficiarios.tipo_documento,
  uad.beneficiarios.sexo,
  uad.beneficiarios.numero_doc,
  nacer.efe_conv.cuie,
  nacer.efe_conv.nombre as nombre_efector,
  nacer.efe_conv.domicilio,
  --uad.departamentos.nombre as departamento,
  nacer.efe_conv.referente,
  sip_clap.hcperinatal.id_hcperinatal,
  sip_clap.hcperinatal.clave_beneficiario,
  sip_clap.hcperinatal.var_0005 as Telefono,
  sip_clap.hcperinatal.var_0006 as Fecha_de_nacimiento_madre,
  sip_clap.hcperinatal.var_0009 as Edad_materna,
  sip_clap.hcperinatal.var_0010 as Edad_materna_menor15_o_mayor35,
  sip_clap.hcperinatal.var_0011 as Etnia,
  sip_clap.hcperinatal.var_0012 as Alfabeta,
  sip_clap.hcperinatal.var_0013 as Estudios,
  sip_clap.hcperinatal.var_0014 as Años_estudios_mayor_nivel,
  sip_clap.hcperinatal.var_0015 as Estado_civil,
  sip_clap.hcperinatal.var_0016 as Vive_sola,
  sip_clap.hcperinatal.var_0017 as Lugar_control_prenatal,
  sip_clap.hcperinatal.var_0018 as Lugar_de_parto,
  sip_clap.hcperinatal.var_0019 as Numero_de_identidad,
   
  sip_clap.hcantecedentes.var_0020 as TBC_familiares,
  sip_clap.hcantecedentes.var_0022 as Diabetes_familiares,
  sip_clap.hcantecedentes.var_0024 as Hipertension_familiares,
  sip_clap.hcantecedentes.var_0026 as Antecedentes_preclampsia_familiares,
  sip_clap.hcantecedentes.var_0028 as Eclampsia_familiares,
  sip_clap.hcantecedentes.var_0030 as Otro_antecedente_familiar,
  sip_clap.hcantecedentes.var_0021 as TBC_personales,
  sip_clap.hcantecedentes.var_0023 as Diabetes_personales,
  sip_clap.hcantecedentes.var_0025 as Hipertension_personales,
  sip_clap.hcantecedentes.var_0027 as Antecedentes_preclampsia_personales,
  sip_clap.hcantecedentes.var_0029 as Eclampsia_personales,
  sip_clap.hcantecedentes.var_0031 as Otro_antecedente_personal,
  sip_clap.hcantecedentes.var_0032 as Cirugia_personales,
  sip_clap.hcantecedentes.var_0033 as Infertilidad_personales,
  sip_clap.hcantecedentes.var_0034 as Cardiopatia_antecedentes,
  sip_clap.hcantecedentes.var_0035 as Antecedentes_Nefropatia,
  sip_clap.hcantecedentes.var_0036 as Violencia_personales,
  sip_clap.hcantecedentes.var_0037 as Otros_antecedentes,
  sip_clap.hcantecedentes.var_0040 as Gestas_previas,
  sip_clap.hcantecedentes.var_0041 as Abortos_previos,
  sip_clap.hcantecedentes.var_0045 as Tres_abortos_espontaneos_consecutivos,
  sip_clap.hcantecedentes.var_0046 as Partos_previos,
  sip_clap.hcantecedentes.var_0038 as Peso_RN_previo,
  sip_clap.hcantecedentes.var_0039 as Antecedentes_gemelares,
  sip_clap.hcantecedentes.var_0042 as Partos_vaginales,
  sip_clap.hcantecedentes.var_0047 as Cesareas_previas,
  sip_clap.hcantecedentes.var_0043 as Ant_nacidos_vivos,
  sip_clap.hcantecedentes.var_0044 as RN_que_viven,
  sip_clap.hcantecedentes.var_0048 as Ant_nacidos_muertos,
  sip_clap.hcantecedentes.var_0049 as Ant_muertos_1a_sem,
  sip_clap.hcantecedentes.var_0050 as Ant_muertos_despues_1a_sem,
  sip_clap.hcantecedentes.var_0051 as Fecha_embarazo_anterior,
  sip_clap.hcantecedentes.var_0052 as Embarazo_anterior_menor_1,
  sip_clap.hcantecedentes.var_0053 as Embarazo_planeado,
  sip_clap.hcantecedentes.var_0054 as Fracaso_metodo_anticonceptivo,

   /*sip_clap.hcperinatal.var_0020 as TBC_familiares,
    sip_clap.hcperinatal.var_0022 as Diabetes_familiares,
    sip_clap.hcperinatal.var_0024 as Hipertension_familiares,
    sip_clap.hcperinatal.var_0026 as Antecedentes_preclampsia_familiares,
    sip_clap.hcperinatal.var_0028 as Eclampsia_familiares,
    sip_clap.hcperinatal.var_0030 as Otro_antecedente_familiar,
    sip_clap.hcperinatal.var_0021 as TBC_personales,
    sip_clap.hcperinatal.var_0023 as Diabetes_personales,
    sip_clap.hcperinatal.var_0025 as Hipertension_personales,
    sip_clap.hcperinatal.var_0027 as Antecedentes_preclampsia_personales,
    sip_clap.hcperinatal.var_0029 as Eclampsia_personales,
    sip_clap.hcperinatal.var_0031 as Otro_antecedente_personal,
    sip_clap.hcperinatal.var_0032 as Cirugía_personales,
    sip_clap.hcperinatal.var_0033 as Infertilidad_personales,
    sip_clap.hcperinatal.var_0034 as Cardiopatia_antecedentes,
    sip_clap.hcperinatal.var_0035 as Antecedentes_Nefropatia,
    sip_clap.hcperinatal.var_0036 as Violencia_personales,
    sip_clap.hcperinatal.var_0037 as Otros_antecedentes,
    sip_clap.hcperinatal.var_0040 as Gestas_previas,
    sip_clap.hcperinatal.var_0041 as Abortos_previos,
    sip_clap.hcperinatal.var_0045 as Tres_abortos_espontaneos_consecutivos,
    sip_clap.hcperinatal.var_0046 as Partos_previos,
    sip_clap.hcperinatal.var_0038 as Peso_RN_previo,
    sip_clap.hcperinatal.var_0039 as Antecedentes_gemelares,
    sip_clap.hcperinatal.var_0042 as Partos_vaginales,
    sip_clap.hcperinatal.var_0047 as Cesareas_previas,
    sip_clap.hcperinatal.var_0043 as Ant_nacidos_vivos,
    sip_clap.hcperinatal.var_0044 as RN_que_viven,
    sip_clap.hcperinatal.var_0048 as Ant_nacidos_muertos,
    sip_clap.hcperinatal.var_0049 as Ant_muertos_1a_sem,
    sip_clap.hcperinatal.var_0050 as Ant_muertos_despues_1a_sem,
    sip_clap.hcperinatal.var_0051 as Fecha_embarazo_anterior,
    sip_clap.hcperinatal.var_0052 as Embarazo_anterior_menor1,
    sip_clap.hcperinatal.var_0053 as Embarazo_planeado,
    sip_clap.hcperinatal.var_0054 as Fracaso_metodo_anticonceptivo,*/

    /*sip_clap.hcperinatal.var_0055 as Peso_anterior,
    sip_clap.hcperinatal.var_0056 as Talla_madre,
    sip_clap.hcperinatal.var_0057 as Fecha_ultima_menstruacion,
    sip_clap.hcperinatal.var_0058 as Fecha_probable_de_parto,
    sip_clap.hcperinatal.var_0059 as EG_confiable_por_FUM,
    sip_clap.hcperinatal.var_0060 as EG_confiable_por_Eco_menor20s,
    sip_clap.hcperinatal.var_0076 as Antirubeola,
    sip_clap.hcperinatal.var_0077 as Antitetanica_vigente,
    sip_clap.hcperinatal.var_0078 as Antitetanica_dosis_1,
    sip_clap.hcperinatal.var_0079 as Antitetanica_dosis_2,
    sip_clap.hcperinatal.var_0080 as Examen_odontologico_normal,
    sip_clap.hcperinatal.var_0081 as Examen_mamas_normal,
    sip_clap.hcperinatal.var_0082 as Cervix_insp_Visual,
    sip_clap.hcperinatal.var_0083 as Cervix_PAP,
    sip_clap.hcperinatal.var_0084 as Cervix_COLP,
    sip_clap.hcperinatal.var_0085 as Grupo_sanguineo,
    sip_clap.hcperinatal.var_0086 as Rh,
    sip_clap.hcperinatal.var_0087 as Inmunizacion,
    sip_clap.hcperinatal.var_0088 as Tamizaje_Antenatal_Toxoplasmosis_menor_20_sem,
    sip_clap.hcperinatal.var_0089 as Tamizaje_Antenatal_Toxoplasmosis_mayorIgual_20_sem,
    sip_clap.hcperinatal.var_0090 as Tamizaje_Antenatal_Toxoplasmosis_1er_Consulta,
    sip_clap.hcperinatal.var_0095 as Hb_menor20_sem,
    sip_clap.hcperinatal.var_0096 as Hb_menor20_sem_menor11g,
    sip_clap.hcperinatal.var_0097 as Hierro_indicado,
    sip_clap.hcperinatal.var_0098 as Folatos_indicados,
    sip_clap.hcperinatal.var_0099 as Hb_mayorIgual20_sem,
    sip_clap.hcperinatal.var_0100 as Hb_mayorIgual20_sem_menor11g,
    sip_clap.hcperinatal.var_0101 as Tamizaje_Antenatal_Chagas,
    sip_clap.hcperinatal.var_0102 as Tamizaje_Antenatal_Malaria,
    sip_clap.hcperinatal.var_0103 as Bacteriuria_menor20_sem,
    sip_clap.hcperinatal.var_0104 as Bacteriuria_mayorIgual20_sem,
    sip_clap.hcperinatal.var_0105 as Glucemia_menor20sem,
    sip_clap.hcperinatal.var_0106 as Glu_menor20sem_mayor105,
    sip_clap.hcperinatal.var_0107 as Glu_mayorIgual30sem_menor105,
    sip_clap.hcperinatal.var_0108 as Glucemia_mayorIgual30sem,
    sip_clap.hcperinatal.var_0109 as Tamizaje_Antenatal_Estreptococo_B,
    sip_clap.hcperinatal.var_0110 as Preparacion_parto,
    sip_clap.hcperinatal.var_0111 as Consejeria_lactancia,
    sip_clap.hcperinatal.var_0091 as Tamizaje_Antenatal_VIH_menor20sem_solicitado,
    sip_clap.hcperinatal.var_0093 as Tamizaje_Antenatal_VIH_mayorIgual20sem_solicitado,
    sip_clap.hcperinatal.var_0112 as Prueba_Sifilis_No_treponemica_menor20s,
    sip_clap.hcperinatal.var_0114 as Prueba_Sifilis_No_treponemica_mayorIgual20s,
    sip_clap.hcperinatal.var_0115 as Tratamiento_Sifilis_menor20s,*/
    
    
  sip_clap.hcgestacion_actual.var_0055 as Peso_anterior,
  sip_clap.hcgestacion_actual.var_0056 as Talla_madre,
  sip_clap.hcgestacion_actual.var_0057 as Fecha_ultima_menstruacion,
  sip_clap.hcgestacion_actual.var_0058 as Fecha_probable_de_parto,
  sip_clap.hcgestacion_actual.var_0059 as EG_confiable_por_FUM,
  sip_clap.hcgestacion_actual.var_0060 as EG_confiable_por_Eco_menor20s,
  sip_clap.hcgestacion_actual.var_0076 as Antirubeola,
  sip_clap.hcgestacion_actual.var_0077 as Antitetanica_vigente,
  sip_clap.hcgestacion_actual.var_0078 as Antitetanica_dosis_1,
  sip_clap.hcgestacion_actual.var_0079 as Antitetanica_dosis_2,
  sip_clap.hcgestacion_actual.var_0080 as Examen_odontologico_normal,
  sip_clap.hcgestacion_actual.var_0081 as Examen_mamas_normal,
  sip_clap.hcgestacion_actual.var_0082 as Cervix_insp_Visual,
  sip_clap.hcgestacion_actual.var_0083 as Cervix_PAP,
  sip_clap.hcgestacion_actual.var_0084 as Cervix_COLP,
  sip_clap.hcgestacion_actual.var_0085 as Grupo_sanguineo,
  sip_clap.hcgestacion_actual.var_0086 as Rh,
  sip_clap.hcgestacion_actual.var_0087 as Inmunizacion,
  sip_clap.hcgestacion_actual.var_0088 as Tamizaje_Antenatal_Toxoplasmosis_menor20_sem,
  sip_clap.hcgestacion_actual.var_0089 as Tamizaje_Antenatal_Toxoplasmosis_mayoIgual20_sem,
  sip_clap.hcgestacion_actual.var_0090 as Tamizaje_Antenatal_Toxoplasmosis_1er_Consulta,
  sip_clap.hcgestacion_actual.var_0095 as Hb_menor_20_sem,
  sip_clap.hcgestacion_actual.var_0096 as Hb_menor_20_sem_menor_11g,
  sip_clap.hcgestacion_actual.var_0097 as Hierro_indicado,
  sip_clap.hcgestacion_actual.var_0098 as Folatos_indicados,
  sip_clap.hcgestacion_actual.var_0099 as Hb_mayorIgual20_sem,
  sip_clap.hcgestacion_actual.var_0100 as Hb_mayorIgual20sem_menor_11g,
  sip_clap.hcgestacion_actual.var_0101 as Tamizaje_Antenatal_Chagas,
  sip_clap.hcgestacion_actual.var_0102 as Tamizaje_Antenatal_Hepatitis_B,
  sip_clap.hcgestacion_actual.var_0103 as Bacteriuria_menor20sem,
  sip_clap.hcgestacion_actual.var_0104 as Bacteriuria_mayorIgual20sem,
  sip_clap.hcgestacion_actual.var_0105 as Glucemia_menor20sem,
  sip_clap.hcgestacion_actual.var_0106 as GluMenor20sem_mayor_105,
  sip_clap.hcgestacion_actual.var_0107 as Glu_mayorIgual30sem_mayor_105,
  sip_clap.hcgestacion_actual.var_0108 as Glucemia_mayorIgual30sem,
  sip_clap.hcgestacion_actual.var_0109 as Tamizaje_Antenatal_Estreptococo_B,
  sip_clap.hcgestacion_actual.var_0110 as Preparacion_parto,
  sip_clap.hcgestacion_actual.var_0111 as Consejeria_lactancia,
  sip_clap.hcgestacion_actual.var_0091 as Tamizaje_Antenatal_VIH_menor20sem_solicitado,
  sip_clap.hcgestacion_actual.var_0093 as Tamizaje_Antenatal_VIH_moyorIgual20sem_solicitado,
  sip_clap.hcgestacion_actual.var_0112 as Prueba_Sifilis_No_treponemica_menor20s,
  sip_clap.hcgestacion_actual.var_0114 as Prueba_Sifilis_No_treponemica_mayorIgual20s,
  sip_clap.hcgestacion_actual.var_0115 as Tratamiento_Sifilis_menor20s,
    
  /*sip_clap.hcperinatal.var_0182 as PartoAborto,
    sip_clap.hcperinatal.var_0183 as Fecha_de_ingreso,
    sip_clap.hcperinatal.var_0184 as Carnet,
    sip_clap.hcperinatal.var_0185 as Consultas_prenatales,
    sip_clap.hcperinatal.var_0186 as Hospitalizacion,
    sip_clap.hcperinatal.var_0187 as Hospitalizacion_dias,
    sip_clap.hcperinatal.var_0188 as Corticoides,
    sip_clap.hcperinatal.var_0189 as Corticoides2,
    sip_clap.hcperinatal.var_0190 as Inicio_parto,
    sip_clap.hcperinatal.var_0191 as Ruptura_de_membranas,
    sip_clap.hcperinatal.var_0192 as Fecha_ruptura_de_membranas,
    sip_clap.hcperinatal.var_0193 as Hora_rupt_membranas,
    sip_clap.hcperinatal.var_0194 as Rupt_membranas_menor37sem,
    sip_clap.hcperinatal.var_0195 as Rupt_membranas_mayorIgual18hs,
    sip_clap.hcperinatal.var_0196 as Rupt_membranas_temperatura_mayorIgual38,
    sip_clap.hcperinatal.var_0197 as Rupt_membranas_temperatura,
    sip_clap.hcperinatal.var_0198 as Edad_gestacional_al_parto,
    sip_clap.hcperinatal.var_0199 as Edad_gestacional_dias,
    sip_clap.hcperinatal.var_0200 as Edad_gestacional_al_parto_por_FUM,
    sip_clap.hcperinatal.var_0201 as Edad_gestacional_al_parto_por_ECO,
    sip_clap.hcperinatal.var_0202 as Presentacion_situación,
    sip_clap.hcperinatal.var_0203 as Tamaño_fetal_acorde,
    sip_clap.hcperinatal.var_0204 as Acompañante_TDP,
    sip_clap.hcperinatal.var_0205 as Acompañante_Parto,
    sip_clap.hcperinatal.var_0206 as Trabajo_de_parto_detalles,
    sip_clap.hcperinatal.var_0257 as Enfermedades,
    sip_clap.hcperinatal.var_0258 as HTA_previa,
    sip_clap.hcperinatal.var_0259 as HTA_inducida,
    sip_clap.hcperinatal.var_0260 as Preeclampsia,
    sip_clap.hcperinatal.var_0261 as Eclampsia,
    sip_clap.hcperinatal.var_0262 as Cardiopatia,
    sip_clap.hcperinatal.var_0263 as Nefropatia,
    sip_clap.hcperinatal.var_0264 as Diabetes,
    sip_clap.hcperinatal.var_0266 as Infeccion_ovular,
    sip_clap.hcperinatal.var_0267 as Infeccion_urinaria,
    sip_clap.hcperinatal.var_0268 as Amenaza_parto_pretermino,
    sip_clap.hcperinatal.var_0269 as RCIU,
    sip_clap.hcperinatal.var_0270 as Ruptura_prem_de_membranas,
    sip_clap.hcperinatal.var_0271 as Anemia,
    sip_clap.hcperinatal.var_0272 as Otra_condicion_grave,
    sip_clap.hcperinatal.var_0273 as Hemorragia_1er,
    sip_clap.hcperinatal.var_0274 as Hemorragia_2do,
    sip_clap.hcperinatal.var_0275 as Hemorragia_3er,
    sip_clap.hcperinatal.var_0276 as Hemorragia_posparto,
    sip_clap.hcperinatal.var_0277 as Infeccion_purperal,
    sip_clap.hcperinatal.var_0278 as Codigo_enfermedad_1,
    sip_clap.hcperinatal.var_0279 as Codigo_enfermedad_2,
    sip_clap.hcperinatal.var_0280 as Codigo_enfermedad_3,
    sip_clap.hcperinatal.var_0282 as Nacimiento,
    sip_clap.hcperinatal.var_0283 as Hora_nacimiento,
    sip_clap.hcperinatal.var_0284 as Fecha_de_nacimiento,
    sip_clap.hcperinatal.var_0285 as Embarazo_multiple,
    sip_clap.hcperinatal.var_0286 as Embarazo_multiple_orden,
    sip_clap.hcperinatal.var_0287 as Terminacion,
    sip_clap.hcperinatal.var_0288 as Motivo_principal_de_induccion_o_cirugia,
    sip_clap.hcperinatal.var_0289 as Codigo_induccion,
    sip_clap.hcperinatal.var_0290 as Codigo_operatorio,
    sip_clap.hcperinatal.var_0291 as Posicion_parto,
    sip_clap.hcperinatal.var_0292 as Episiotomia,
    sip_clap.hcperinatal.var_0293 as Desgarros_no,
    sip_clap.hcperinatal.var_0294 as Desgarros_grado,
    sip_clap.hcperinatal.var_0295 as Ocitocicos_prealumbramiento,
    sip_clap.hcperinatal.var_0296 as VIT_K,
    sip_clap.hcperinatal.var_0297 as Placenta_completa,
    sip_clap.hcperinatal.var_0298 as Placenta_retenida,
    sip_clap.hcperinatal.var_0299 as Ligadura_cordon_precoz,
    sip_clap.hcperinatal.var_0300 as Ocitocicos_en_TDP,
    sip_clap.hcperinatal.var_0301 as Antibioticos,
    sip_clap.hcperinatal.var_0302 as Analgesia,
    sip_clap.hcperinatal.var_0303 as Anestesia_local,
    sip_clap.hcperinatal.var_0304 as Anestesia_regional,
    sip_clap.hcperinatal.var_0305 as Anestesia_general,
    sip_clap.hcperinatal.var_0306 as Transfusion,
    sip_clap.hcperinatal.var_0307 as Otros,
    sip_clap.hcperinatal.var_0308 as Codigo_Medicacion_1,
    sip_clap.hcperinatal.var_0309 as Codigo_Medicacion_2,*/
    
    sip_clap.hcperinatal.var_0310 as RN_Sexo,
    sip_clap.hcperinatal.var_0311 as Peso_al_nacer,
    sip_clap.hcperinatal.var_0312 as Peso_al_nacer_menor2500o_mayorIgual4000,
    sip_clap.hcperinatal.var_0313 as Perimetro_cefalico,
    sip_clap.hcperinatal.var_0314 as Longitud,
    sip_clap.hcperinatal.var_0315 as Edad_gestacional_RN,
    sip_clap.hcperinatal.var_0316 as Edad_gestacional_RN_dias,
    sip_clap.hcperinatal.var_0319 as Edad_gestacional_RN_estimada,
    sip_clap.hcperinatal.var_0317 as Edad_gestacional_RN_FUM,
    sip_clap.hcperinatal.var_0318 as Edad_gestacional_RN_ECO,
    sip_clap.hcperinatal.var_0320 as RN_Peso_para_Edad_Gestacional,
    sip_clap.hcperinatal.var_0321 as Apgar_1erMinuto,
    sip_clap.hcperinatal.var_0322 as Apgar_5toMinuto,
    sip_clap.hcperinatal.var_0323 as RN_Reanimacion_Estimulacion,
    sip_clap.hcperinatal.var_0324 as RN_Reanimacion_Aspiracion,
    sip_clap.hcperinatal.var_0325 as RN_Reanimacion_Mascara,
    sip_clap.hcperinatal.var_0326 as RN_Reanimacion_Oxigeno,
    sip_clap.hcperinatal.var_0327 as RN_Reanimacion_Masaje,
    sip_clap.hcperinatal.var_0328 as RN_Reanimacion_Tubo,
    sip_clap.hcperinatal.var_0329 as Fallece_lugar_de_parto,
    sip_clap.hcperinatal.var_0330 as Referido,
    sip_clap.hcperinatal.var_0331 as Atendio_Parto,
    sip_clap.hcperinatal.var_0332 as Atendio_Parto_nombre,
    sip_clap.hcperinatal.var_0333 as Atendio_Neonato,
    sip_clap.hcperinatal.var_0334 as Atendio_Neonato_nombre,
    sip_clap.hcperinatal.var_0335 as Defectos_congenitos,
    sip_clap.hcperinatal.var_0368 as Defectos_congenitos_codigo,
    sip_clap.hcperinatal.var_0336 as Enfermedades_RN,
    sip_clap.hcperinatal.var_0337 as Codigo_enfermedad_RN1,
    sip_clap.hcperinatal.var_0338 as Notas_enfermedades_RN1,
    sip_clap.hcperinatal.var_0339 as Codigo_enfermedad_RN2,
    sip_clap.hcperinatal.var_0340 as Notas_enfermedades_RN2,
    sip_clap.hcperinatal.var_0341 as Codigo_enfermedad_RN3,
    sip_clap.hcperinatal.var_0342 as Notas_enfermedades_RN3,
    sip_clap.hcperinatal.var_0343 as RN_Tamizaje_VDRL,
    sip_clap.hcperinatal.var_0344 as RN_Tamizaje_TSH,
    sip_clap.hcperinatal.var_0345 as RN_Tamizaje_Hbpatia,
    sip_clap.hcperinatal.var_0346 as RN_Tamizaje_Bilirrubina,
    sip_clap.hcperinatal.var_0347 as RN_Tamizaje_Toxo_IgM,
    sip_clap.hcperinatal.var_0348 as RN_Tamizaje_Meconio_1erdia,
    sip_clap.hcperinatal.var_0367 as Antirubeola_postparto,
    sip_clap.hcperinatal.var_0370 as Egreso_RN_hora,
    sip_clap.hcperinatal.var_0371 as Egreso_RN,
    sip_clap.hcperinatal.var_0372 as Lugar_traslado_RN,
    sip_clap.hcperinatal.var_0373 as Fallece_lugar_de_traslado,
    sip_clap.hcperinatal.var_0374 as Edad_egreso,
    sip_clap.hcperinatal.var_0375 as Edad_menor1dia,
    sip_clap.hcperinatal.var_0376 as Alimento_al_alta,
    sip_clap.hcperinatal.var_0377 as Boca_arriba,
    sip_clap.hcperinatal.var_0378 as BCG,
    sip_clap.hcperinatal.var_0395 as Peso_RN_al_egreso,
    sip_clap.hcperinatal.var_0379 as Fecha_de_egreso_materno,
    sip_clap.hcperinatal.var_0380 as Traslado,
    sip_clap.hcperinatal.var_0381 as Lugar_traslado_materno,
    sip_clap.hcperinatal.var_0382 as Egreso_materno,
    sip_clap.hcperinatal.var_0383 as Fallece_durante_traslado,
    sip_clap.hcperinatal.var_0384 as Fallece_durante_traslado_dias,
    sip_clap.hcperinatal.var_0385 as Anticoncepcion_consejeria,
    sip_clap.hcperinatal.var_0386 as Metodo_antic_elegido,
    sip_clap.hcperinatal.var_0388 as Nro_Historia_RN,
    sip_clap.hcperinatal.var_0389 as Nombre_RN,
    sip_clap.hcperinatal.var_0390 as Responsable_egreso_RN,
    sip_clap.hcperinatal.var_0391 as Responsable_egreso_materno,
    sip_clap.hcperinatal.var_0432 as VIH_personales,
    sip_clap.hcperinatal.var_0409 as Embarazo_ectopico,
    sip_clap.hcperinatal.var_0061 as Fumadora_activa_1er,
    sip_clap.hcperinatal.var_0062 as Fumadora_pasiva_1er,
    sip_clap.hcperinatal.var_0063 as Drogas_1er,
    sip_clap.hcperinatal.var_0064 as Alcohol_1er,
    sip_clap.hcperinatal.var_0065 as Violencia_1er,
    sip_clap.hcperinatal.var_0066 as Fumadora_activa_2do,
    sip_clap.hcperinatal.var_0067 as Fumadora_pasiva_2do,
    sip_clap.hcperinatal.var_0068 as Drogas_2do,
    sip_clap.hcperinatal.var_0069 as Alcohol_2do,
    sip_clap.hcperinatal.var_0070 as Violencia_2do,
    sip_clap.hcperinatal.var_0071 as Fumadora_activa_3er,
    sip_clap.hcperinatal.var_0072 as Fumadora_pasiva_3er,
    sip_clap.hcperinatal.var_0073 as Drogas_3er,
    sip_clap.hcperinatal.var_0074 as Alcohol_3er,
    sip_clap.hcperinatal.var_0075 as Violencia_3er,
    sip_clap.hcperinatal.var_0410 as Gamma_globulina_anti_D,
    sip_clap.hcperinatal.var_0433 as Tamizaje_Antenatal_Prueba_VIH_menor20sem,
    sip_clap.hcperinatal.var_0434 as Tamizaje_Antenatal_TARV_VIH_menor20sem,
    sip_clap.hcperinatal.var_0435 as Tamizaje_Antenatal_Prueba_VIH_mayorIgual20sem,
    sip_clap.hcperinatal.var_0436 as Tamizaje_Antenatal_TARV_VIH_mayorIgual20sem,
    sip_clap.hcperinatal.var_0413 as Semana_prueba_sifilis_No_trepon_menor20s,
    sip_clap.hcperinatal.var_0419 as Semana_prueba_sifilis_No_trepon_mayorIgual20s,
    sip_clap.hcperinatal.var_0415 as Prueba_sifilis_treponemica_menor20s,
    sip_clap.hcperinatal.var_0414 as Semana_prueba_sifilis_Treponemica_menor20s,
    sip_clap.hcperinatal.var_0420 as Semana_prueba_sifilis_Treponemica_mayorIgual20s,
    sip_clap.hcperinatal.var_0421 as Prueba_sifilis_treponemica_mayorIgual20s,
    sip_clap.hcperinatal.var_0416 as Semana_tratamiento_sifilis_menor20s,
    sip_clap.hcperinatal.var_0422 as Semana_tratamiento_sifilis_mayorIgual20s,
    sip_clap.hcperinatal.var_0423 as Tratamiento_sifilis_mayorIgual20s,
    sip_clap.hcperinatal.var_0418 as Tratamiento_sifilis_pareja_menor20s,
    sip_clap.hcperinatal.var_0425 as Egreso_RN_fecha,
    sip_clap.hcperinatal.var_0437 as TDP_Prueba_Sifilis,
    sip_clap.hcperinatal.var_0438 as TDP_Prueba_VIH,
    sip_clap.hcperinatal.var_0439 as TDP_ARV,
    sip_clap.hcperinatal.var_0440 as VIH_en_RN_Expuesto,
    sip_clap.hcperinatal.var_0441 as VIH_en_RN_Tratamiento,
    sip_clap.hcperinatal.var_0412 as TTO_VDRL,
    sip_clap.hcperinatal.var_0411 as Gamma_globulina_anti_D_egreso,
    
  sip_clap.hcperinatal.usuario_carga,
  sip_clap.hcperinatal.fecha_hora_carga,
  sip_clap.hcperinatal.fecha_hora_proceso,
  sip_clap.hcperinatal.finalizado,
  sip_clap.hcperinatal.estado,

  sip_clap.hcparto_aborto.var_0182 as Parto_Aborto,
  sip_clap.hcparto_aborto.var_0183 as Fecha_de_ingreso,
  sip_clap.hcparto_aborto.var_0184 as Carne,
  sip_clap.hcparto_aborto.var_0185 as Consultas_prenatales,
  sip_clap.hcparto_aborto.var_0186 as Hospitalizacion,
  sip_clap.hcparto_aborto.var_0187 as Hospitalizacion_dias,
  sip_clap.hcparto_aborto.var_0188 as Corticoides,
  sip_clap.hcparto_aborto.var_0189 as Corticoides2,
  sip_clap.hcparto_aborto.var_0190 as Inicio_parto,
  sip_clap.hcparto_aborto.var_0191 as Ruptura_de_membranas,
  sip_clap.hcparto_aborto.var_0192 as Fecha_ruptura_de_membranas,
  sip_clap.hcparto_aborto.var_0193 as Hora_rupt_membranas,
  sip_clap.hcparto_aborto.var_0194 as Rupt_membranas_menor_37sem,
  sip_clap.hcparto_aborto.var_0195 as Rupt_membranas_mayorIgual_18hs,
  sip_clap.hcparto_aborto.var_0196 as Rupt_membranas_temperatura_mayorIgual_38,
  sip_clap.hcparto_aborto.var_0197 as Rupt_membranas_temperatura,
  sip_clap.hcparto_aborto.var_0198 as Edad_gestacional_al_parto,
  sip_clap.hcparto_aborto.var_0199 as Edad_gestacional_dias,
  sip_clap.hcparto_aborto.var_0200 as Edad_gestacional_al_parto_por_FUM,
  sip_clap.hcparto_aborto.var_0201 as Edad_gestacional_al_parto_por_ECO,
  sip_clap.hcparto_aborto.var_0202 as Presentacion_situacion,
  sip_clap.hcparto_aborto.var_0203 as Tamaño_fetal_acorde,
  sip_clap.hcparto_aborto.var_0204 as Acompañante_TDP,
  sip_clap.hcparto_aborto.var_0205 as Acompañante_Parto,
  sip_clap.hcparto_aborto.var_0206 as Trabajo_de_parto_detalles,
  sip_clap.hcparto_aborto.var_0257 as Enfermedades,
  sip_clap.hcparto_aborto.var_0258 as HTA_previa,
  sip_clap.hcparto_aborto.var_0259 as HTA_inducida,
  sip_clap.hcparto_aborto.var_0260 as Preeclampsia,
  sip_clap.hcparto_aborto.var_0261 as Eclampsia,
  sip_clap.hcparto_aborto.var_0262 as Cardiopatia,
  sip_clap.hcparto_aborto.var_0263 as Nefropatia,
  sip_clap.hcparto_aborto.var_0264 as Diabetes,
  sip_clap.hcparto_aborto.var_0266 as Infeccion_ovular,
  sip_clap.hcparto_aborto.var_0267 as Infeccion_urinaria,
  sip_clap.hcparto_aborto.var_0268 as Amenaza_parto_pretermino,
  sip_clap.hcparto_aborto.var_0269 as RCIU,
  sip_clap.hcparto_aborto.var_0270 as Ruptura_prem_de_membranas,
  sip_clap.hcparto_aborto.var_0271 as Anemia,
  sip_clap.hcparto_aborto.var_0272 as Otra_condicion_grave,
  sip_clap.hcparto_aborto.var_0273 as Hemorragia_1er_trim,
  sip_clap.hcparto_aborto.var_0274 as Hemorragia_2do_trim,
  sip_clap.hcparto_aborto.var_0275 as Hemorragia_3er_trim,
  sip_clap.hcparto_aborto.var_0276 as Hemorragia_posparto,
  sip_clap.hcparto_aborto.var_0277 as Infeccion_purperal,
  sip_clap.hcparto_aborto.var_0278 as Codigo_enfermedad_1,
  sip_clap.hcparto_aborto.var_0279 as Codigo_enfermedad_2,
  sip_clap.hcparto_aborto.var_0280 as Codigo_enfermedad_3,
  sip_clap.hcparto_aborto.var_0282 as Nacimiento,
  sip_clap.hcparto_aborto.var_0283 as Hora_nacimiento,
  sip_clap.hcparto_aborto.var_0284 as Fecha_de_nacimiento,
  sip_clap.hcparto_aborto.var_0285 as Embarazo_multiple,
  sip_clap.hcparto_aborto.var_0286 as Embarazo_multiple_orden,
  sip_clap.hcparto_aborto.var_0287 as Terminacion,
  sip_clap.hcparto_aborto.var_0288 as Motivo_principal_de_induccion_o_cirugia,
  sip_clap.hcparto_aborto.var_0289 as Codigo_inducción,
  sip_clap.hcparto_aborto.var_0290 as Codigo_operatorio,
  sip_clap.hcparto_aborto.var_0291 as Posicion_parto,
  sip_clap.hcparto_aborto.var_0292 as Episiotomia,
  sip_clap.hcparto_aborto.var_0293 as Desgarros_no,
  sip_clap.hcparto_aborto.var_0294 as Desgarros_grado,
  sip_clap.hcparto_aborto.var_0295 as Ocitocicos_prealumbramiento,
  sip_clap.hcparto_aborto.var_0296 as VIT_K,
  sip_clap.hcparto_aborto.var_0297 as Placenta_completa,
  sip_clap.hcparto_aborto.var_0298 as Placenta_retenida,
  sip_clap.hcparto_aborto.var_0299 as Ligadura_cordon_precoz,
  sip_clap.hcparto_aborto.var_0300 as Ocitócicos_en_TDP,
  sip_clap.hcparto_aborto.var_0301 as Antibióticos,
  sip_clap.hcparto_aborto.var_0302 as Analgesia,
  sip_clap.hcparto_aborto.var_0303 as Anestesia_local,
  sip_clap.hcparto_aborto.var_0304 as Anestesia_regional,
  sip_clap.hcparto_aborto.var_0305 as Anestesia_general,
  sip_clap.hcparto_aborto.var_0306 as Transfusion,
  sip_clap.hcparto_aborto.var_0307 as Otros,
  sip_clap.hcparto_aborto.var_0308 as Codigo_Medicacion1,
  sip_clap.hcparto_aborto.var_0309 as Codigo_Medicacion2,

  sip_clap.hcperinatal.procesado,
  sip_clap.hcperinatal.usuario_ultact,
  sip_clap.hcperinatal.fecha_hora_ultact,
  sip_clap.hcperinatal.score_riesgo,

  sip_clap.control_parto.var_0207 as Control_en_parto_1_hora,
  sip_clap.control_parto.var_0208 as Control_en_parto_1_minutos,
  sip_clap.control_parto.var_0209 as Control_en_parto_1_posicion,
  sip_clap.control_parto.var_0210 as Control_en_parto_1_Pasist,
  sip_clap.control_parto.var_0407 as Control_en_parto_1_Padiast,
  sip_clap.control_parto.var_0392 as Control_en_parto_1_pulso,
  sip_clap.control_parto.var_0211 as Control_en_parto_1_contracciones10,
  sip_clap.control_parto.var_0212 as Control_en_parto_1_dilatacion,
  sip_clap.control_parto.var_0213 as Control_en_parto_1_altura_pres,
  sip_clap.control_parto.var_0214 as Control_en_parto_1_variacion_posicion,
  sip_clap.control_parto.var_0215 as Control_en_parto_1_meconio,
  sip_clap.control_parto.var_0216 as Control_en_parto_1_FCF_dips,

   sip_clap.control_puerperio.var_0349 as Control_post_parto_1_dia,
   sip_clap.control_puerperio.var_0350 as Control_post_parto_1_hora,
   sip_clap.control_puerperio.var_0351 as Control_post_parto_1_temp_grados,
   sip_clap.control_puerperio.var_0352 as Control_post_parto_1_PA_sist,
   sip_clap.control_puerperio.var_0406 as Control_post_parto_1_diast,
   sip_clap.control_puerperio.var_0353 as Control_post_parto_1_pulso,
   sip_clap.control_puerperio.var_0354 as Control_post_parto_1_invol_uter,
   sip_clap.control_puerperio.var_0355 as Control_post_parto_1_loquios,

  sip_clap.hcespeciales.var_0432 as VIH_personales,
  sip_clap.hcespeciales.var_0409 as Embarazo_ectopico,
  sip_clap.hcespeciales.var_0061 as Fumadora_activa_1er,
  sip_clap.hcespeciales.var_0062 as Fumadora_pasiva_1er,
  sip_clap.hcespeciales.var_0063 as Drogas_1er,
  sip_clap.hcespeciales.var_0064 as Alcohol_1er,
  sip_clap.hcespeciales.var_0065 as Violencia_1er,
  sip_clap.hcespeciales.var_0066 as Fumadora_activa_2do,
  sip_clap.hcespeciales.var_0067 as Fumadora_pasiva_2do,
  sip_clap.hcespeciales.var_0068 as Drogas_2do,
  sip_clap.hcespeciales.var_0069 as Alcohol_2do,
  sip_clap.hcespeciales.var_0070 as Violencia_2do,
  sip_clap.hcespeciales.var_0071 as Fumadora_activa_3er,
  sip_clap.hcespeciales.var_0072 as Fumadora_pasiva_3er,
  sip_clap.hcespeciales.var_0073 as Drogas_3er,
  sip_clap.hcespeciales.var_0074 as Alcohol_3er,
  sip_clap.hcespeciales.var_0075 as Violencia_3er,
  sip_clap.hcespeciales.var_0410 as Gamma_globulina_anti_D,
  sip_clap.hcespeciales.var_0433 as Tamizaje_Antenatal_Prueba_VIH_menor_20sem,
  sip_clap.hcespeciales.var_0434 as Tamizaje_Antenatal_TARV_VIH_menor_20sem,
  sip_clap.hcespeciales.var_0435 as Tamizaje_Antenatal_Prueba_VIH_mayor_igual_20sem,
  sip_clap.hcespeciales.var_0436 as Tamizaje_Antenatal_Prueba_VIH_mayor_igual_20sem2,
  sip_clap.hcespeciales.var_0413 as Semana_prueba_sifilis_No_trepon_menor_20s,
  sip_clap.hcespeciales.var_0419 as Semana_prueba_sifilis_No_trepon_mayor_igual_20s,
  sip_clap.hcespeciales.var_0415 as Prueba_sifilis_treponemica_menor_20s,
  sip_clap.hcespeciales.var_0414 as Semana_prueba_sifilis_Treponemica_menor_20s,
  sip_clap.hcespeciales.var_0420 as Semana_prueba_sifilis_Treponemica_mayor_igual_20s,
  sip_clap.hcespeciales.var_0421 as Prueba_sifilis_treponemica_mayor_igual_20s,
  sip_clap.hcespeciales.var_0416 as Semana_tratamiento_sifilis_menor_20s,
  sip_clap.hcespeciales.var_0422 as Semana_tratamiento_sifilis_mayor_igual_20s,
  sip_clap.hcespeciales.var_0423 as Tratamiento_sifilis_mayor_igual_20s,
  sip_clap.hcespeciales.var_0418 as Tratamiento_sifilis_pareja_menor_20s,
  sip_clap.hcespeciales.var_0424 as Tratamiento_sifilis_pareja_mayor_igual_20s,
  sip_clap.hcespeciales.var_0437 as TDP_Prueba_Sifilis,
  sip_clap.hcespeciales.var_0438 as TDP_Prueba_VIH,
  sip_clap.hcespeciales.var_0439 as TDP_ARV,
  sip_clap.hcespeciales.var_0440 as VIH_en_RN_Expuesto,
  sip_clap.hcespeciales.var_0441 as VIH_en_RN_Tratamiento,
  sip_clap.hcespeciales.var_0412 as TTO_VDRL,
  sip_clap.hcespeciales.var_0411 as Gamma_globulina_anti_D_egreso,


  sip_clap.hclibres.var_0396 as VIT_K,
  sip_clap.hclibres.var_0397 as imc,
  sip_clap.hclibres.var_0398 as Libre_2,
  sip_clap.hclibres.var_0399 as Libre_3,
  sip_clap.hclibres.var_0400 as Libre_4,
  sip_clap.hclibres.var_0401 as Libre_5,
  sip_clap.hclibres.var_0402 as Libre_6,
  sip_clap.hclibres.var_0403 as Libre_7,
  sip_clap.hclibres.var_0404 as Libre_8,
  sip_clap.hclibres.var_0405 as Libre_9,
  sip_clap.hclibres.var_0426 as Libre_10,
  sip_clap.hclibres.var_0427 as Libre_11,
  sip_clap.hclibres.var_0428 as Libre_12,
  sip_clap.hclibres.var_0429 as Libre_13,
  sip_clap.hclibres.var_0430 as Libre_14,
  sip_clap.hclibres.var_0431 as Libre_15,

  sip_clap.hcrecien_nacido.var_0310 as RN_Sexo,
  sip_clap.hcrecien_nacido.var_0311 as Peso_al_nacer,
  sip_clap.hcrecien_nacido.var_0312 as Peso_al_nacer_menor2500o_mayorIgual4000,
  sip_clap.hcrecien_nacido.var_0313 as Perimetro_cefalico,
  sip_clap.hcrecien_nacido.var_0314 as Longitud,
  sip_clap.hcrecien_nacido.var_0315 as Edad_gestacional_RN,
  sip_clap.hcrecien_nacido.var_0316 as Edad_gestacional_RN_dias,
  sip_clap.hcrecien_nacido.var_0319 as Edad_gestacional_RN_estimada,
  sip_clap.hcrecien_nacido.var_0317 as Edad_gestacional_RN_FUM,
  sip_clap.hcrecien_nacido.var_0318 as Edad_gestacional_RN_ECO,
  sip_clap.hcrecien_nacido.var_0320 as RN_Peso_para_Edad_Gestacional,
  sip_clap.hcrecien_nacido.var_0321 as Apgar_1er_Minuto,
  sip_clap.hcrecien_nacido.var_0322 as Apgar_5to_Minuto,
  sip_clap.hcrecien_nacido.var_0323 as RN_Reanimacion_Estimulacion,
  sip_clap.hcrecien_nacido.var_0324 as RN_Reanimacion_Aspiracion,
  sip_clap.hcrecien_nacido.var_0325 as RN_Reanimacion_Mascara,
  sip_clap.hcrecien_nacido.var_0326 as RN_Reanimacion_Oxigeno,
  sip_clap.hcrecien_nacido.var_0327 as RN_Reanimacion_Masaje,
  sip_clap.hcrecien_nacido.var_0328 as RN_Reanimacion_Tubo,
  sip_clap.hcrecien_nacido.var_0329 as Fallece_lugar_de_parto,
  sip_clap.hcrecien_nacido.var_0330 as Referido,
  sip_clap.hcrecien_nacido.var_0331 as Atendio_Parto,
  sip_clap.hcrecien_nacido.var_0332 as Atendio_Parto_nombre,
  sip_clap.hcrecien_nacido.var_0333 as Atendio_Neonato,
  sip_clap.hcrecien_nacido.var_0334 as Atendio_Neonato_nombre,
  sip_clap.hcrecien_nacido.var_0335 as Defectos_congenitos,
  sip_clap.hcrecien_nacido.var_0368 as Defectos_congenitos_codigo,
  sip_clap.hcrecien_nacido.var_0336 as Enfermedades_RN,
  sip_clap.hcrecien_nacido.var_0337 as Codigo_enfermedad_RN_1,
  sip_clap.hcrecien_nacido.var_0338 as Notas_enfermedades_RN_1,
  sip_clap.hcrecien_nacido.var_0339 as Codigo_enfermedad_RN_2,
  sip_clap.hcrecien_nacido.var_0340 as Notas_enfermedades_RN_2,
  sip_clap.hcrecien_nacido.var_0341 as Codigo_enfermedad_RN_3,
  sip_clap.hcrecien_nacido.var_0342 as Notas_enfermedades_RN_3,
  sip_clap.hcrecien_nacido.var_0343 as RN_Tamizaje_VDRL,
  sip_clap.hcrecien_nacido.var_0344 as RN_Tamizaje_TSH,
  sip_clap.hcrecien_nacido.var_0345 as RN_Tamizaje_Hbpatia,
  sip_clap.hcrecien_nacido.var_0346 as RN_Tamizaje_Bilirrubina,
  sip_clap.hcrecien_nacido.var_0347 as RN_Tamizaje_Toxo_IgM,
  sip_clap.hcrecien_nacido.var_0348 as RN_Tamizaje_Meconio_1er_dia,
  sip_clap.hcrecien_nacido.var_0367 as Antirubeola_postparto,
  sip_clap.hcrecien_nacido.var_0425 as Egreso_RN_fecha,
  sip_clap.hcrecien_nacido.var_0370 as Egreso_RN_hora,
  sip_clap.hcrecien_nacido.var_0371 as Egreso_RN,
  sip_clap.hcrecien_nacido.var_0372 as Lugar_traslado_RN,
  sip_clap.hcrecien_nacido.var_0373 as Fallece_lugar_de_traslado,
  sip_clap.hcrecien_nacido.var_0374 as Edad_egreso,
  sip_clap.hcrecien_nacido.var_0375 as Edad_menor1dia,
  sip_clap.hcrecien_nacido.var_0376 as Alimento_al_alta,
  sip_clap.hcrecien_nacido.var_0377 as Boca_arriba,
  sip_clap.hcrecien_nacido.var_0378 as BCG,
  sip_clap.hcrecien_nacido.var_0395 as Peso_RN_al_egreso,
  sip_clap.hcrecien_nacido.var_0379 as Fecha_de_egreso_materno,
  sip_clap.hcrecien_nacido.var_0380 as Traslado,
  sip_clap.hcrecien_nacido.var_0381 as Lugar_traslado_materno,
  sip_clap.hcrecien_nacido.var_0382 as Egreso_materno,
  sip_clap.hcrecien_nacido.var_0383 as Fallece_durante_traslado,
  sip_clap.hcrecien_nacido.var_0384 as Fallece_durante_traslado_dias,
  sip_clap.hcrecien_nacido.var_0385 as Anticoncepcion_consejeria,
  sip_clap.hcrecien_nacido.var_0386 as Metodo_antic_elegido,
  sip_clap.hcrecien_nacido.var_0388 as Nro_Historia_RN,
  sip_clap.hcrecien_nacido.var_0389 as Nombre_RN,
  sip_clap.hcrecien_nacido.var_0390 as Responsable_egreso_RN,
  sip_clap.hcrecien_nacido.var_0391 as Responsable_egreso_materno,

  usuarios.login,
  usuarios.apellido,
  usuarios.nombre ";
   
  $query="FROM
      sip_clap.hcperinatal
      LEFT JOIN uad.beneficiarios ON (sip_clap.hcperinatal.clave_beneficiario = uad.beneficiarios.clave_beneficiario)
      LEFT JOIN nacer.efe_conv ON (sip_clap.hcperinatal.var_0017 = nacer.efe_conv.cuie)
      --LEFT JOIN uad.departamentos ON (efe_conv.departamento = departamentos.id_codigo_depto)
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
    .$consulta
    .' AND uad.beneficiarios.numero_doc is not null';
$result=sql($q) or fin_pagina();


echo $html_header;
?>

<form name=form1 method=post action="detalle_sipweb.php">
<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align="left">
       <b>Total de Beneficiarios: <?=$result->RecordCount();?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="90%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
  <tr bgcolor=#C0C0FF>
    <td align="center" id=mo>Apellido</td> 
        <td align="center" id=mo>Nombre</td>
        <td align="center" id=mo>Sexo</td>
        <td align="center" id=mo>Numero Doc.</td>
        <td align="center" id=mo>Domicilio</td>
        <td align="center" id=mo>Telefono</td>
        <td align="center" id=mo>Cuie</td>
        <td align="center" id=mo>Efector</td>        
        <td align="center" id=mo>referente</td>        
        <td align="center" id=mo>Fecha_de_nacimiento_madre</td>
        <td align="center" id=mo>Edad_materna</td>
        <td align="center" id=mo>Edad_materna_menor15_o_mayor35</td>
        <td align="center" id=mo>Etnia</td>
        <td align="center" id=mo>Alfabeta</td>
        <td align="center" id=mo>Estudios</td>
        <td align="center" id=mo>Años_estudios_mayor_nivel</td>
        <td align="center" id=mo>Estado_civil</td>
        <td align="center" id=mo>Vive_sola</td>
        <td align="center" id=mo>Lugar_control_prenatal</td>
        <td align="center" id=mo>Lugar_de_parto</td>
        <td align="center" id=mo>Numero_de_identidad</td>
        
        <td align="center" id=mo>TBC_familiares</td>
        <td align="center" id=mo>Diabetes_familiares</td>
        <td align="center" id=mo>Hipertension_familiares</td>
        <td align="center" id=mo>Antecedentes_preclampsia_familiares</td>
        <td align="center" id=mo>Eclampsia_familiares</td>
        <td align="center" id=mo>Otro_antecedente_familiar</td>
        <td align="center" id=mo>TBC_personales</td>
        <td align="center" id=mo>Diabetes_personales</td>
        <td align="center" id=mo>Hipertension_personales</td>
        <td align="center" id=mo>Antecedentes_preclampsia_personales</td>
        <td align="center" id=mo>Eclampsia_personales</td>
        <td align="center" id=mo>Otro_antecedente_personal</td>
        <td align="center" id=mo>Cirugia_personales</td>
        <td align="center" id=mo>Infertilidad_personales</td>
        <td align="center" id=mo>Cardiopatia_antecedentes</td>
        <td align="center" id=mo>Antecedentes_Nefropatia</td>
        <td align="center" id=mo>Violencia_personales</td>
        <td align="center" id=mo>Otros_antecedentes</td>
        <td align="center" id=mo>Gestas_previas</td>
        <td align="center" id=mo>Abortos_previos</td>
        <td align="center" id=mo>Tres_abortos_espontaneos_consecutivos</td>
        <td align="center" id=mo>Partos_previos</td>
        <td align="center" id=mo>Peso_RN_previo</td>
        <td align="center" id=mo>Antecedentes_gemelares</td>
        <td align="center" id=mo>Partos_vaginales</td>
        <td align="center" id=mo>Cesareas_previas</td>
        <td align="center" id=mo>Ant_nacidos_vivos</td>
        <td align="center" id=mo>RN_que_viven</td>
        <td align="center" id=mo>Ant_nacidos_muertos</td>
        <td align="center" id=mo>Ant_muertos_1a_sem</td>
        <td align="center" id=mo>Ant_muertos_despues_1a_sem</td>
        <td align="center" id=mo>Fecha_embarazo_anterior</td>
        <td align="center" id=mo>Embarazo_anterior_menor_1</td>
        <td align="center" id=mo>Embarazo_planeado</td>
        <td align="center" id=mo>Fracaso_metodo_anticonceptivo</td>
        
        <td align="center" id=mo>Peso_anterior</td>
        <td align="center" id=mo>Talla_madre</td>
        <td align="center" id=mo>Fecha_ultima_menstruacion</td>
        <td align="center" id=mo>Fecha_probable_de_parto</td>
        <td align="center" id=mo>EG_confiable_por_FUM</td>
        <td align="center" id=mo>EG_confiable_por_Eco_menor20s</td>
        <td align="center" id=mo>Antirubeola</td>
        <td align="center" id=mo>Antitetanica_vigente</td>
        <td align="center" id=mo>Antitetanica_dosis_1</td>
        <td align="center" id=mo>Antitetanica_dosis_2</td>
        <td align="center" id=mo>Examen_odontologico_normal</td>
        <td align="center" id=mo>Examen_mamas_normal</td>
        <td align="center" id=mo>Cervix_insp_Visual</td>
        <td align="center" id=mo>Cervix_PAP</td>
        <td align="center" id=mo>Cervix_COLP</td>
        <td align="center" id=mo>Grupo_sanguineo</td>
        <td align="center" id=mo>Rh</td>
        <td align="center" id=mo>Inmunizacion</td>
        <td align="center" id=mo>Tamizaje_Antenatal_Toxoplasmosis_menor20_sem</td>
        <td align="center" id=mo>Tamizaje_Antenatal_Toxoplasmosis_mayoIgual20_sem</td>
        <td align="center" id=mo>Tamizaje_Antenatal_Toxoplasmosis_1er_Consulta</td>
        <td align="center" id=mo>Hb_menor_20_sem</td>
        <td align="center" id=mo>Hb_menor_20_sem_menor_11g</td>
        <td align="center" id=mo>Hierro_indicado</td>
        <td align="center" id=mo>Folatos_indicados</td>
        <td align="center" id=mo>Hb_mayorIgual20_sem</td>
        <td align="center" id=mo>Hb_mayorIgual20sem_menor_11g</td>
        <td align="center" id=mo>Tamizaje_Antenatal_Chagas</td>
        <td align="center" id=mo>Tamizaje_Antenatal_Hepatitis_B</td>
        <td align="center" id=mo>Bacteriuria_menor20sem</td>
        <td align="center" id=mo>Bacteriuria_mayorIgual20sem</td>
        <td align="center" id=mo>Glucemia_menor20sem</td>
        <td align="center" id=mo>GluMenor20sem_mayor_105</td>
        <td align="center" id=mo>Glu_mayorIgual30sem_mayor_105</td>
        <td align="center" id=mo>Glucemia_mayorIgual30sem</td>
        <td align="center" id=mo>Tamizaje_Antenatal_Estreptococo_B</td>
        <td align="center" id=mo>Preparacion_parto</td>
        <td align="center" id=mo>Consejeria_lactancia</td>
        <td align="center" id=mo>Tamizaje_Antenatal_VIH_menor20sem_solicitado</td>
        <td align="center" id=mo>Tamizaje_Antenatal_VIH_moyorIgual20sem_solicitado</td>
        <td align="center" id=mo>Prueba_Sifilis_No_treponemica_menor20s</td>
        <td align="center" id=mo>Prueba_Sifilis_No_treponemica_mayorIgual20s</td>
        <td align="center" id=mo>Tratamiento_Sifilis_menor20s</td>

        <td align="center" id=mo>RN_Sexo</td>
        <td align="center" id=mo>Peso_al_nacer</td>
        <td align="center" id=mo>Peso_al_nacer_menor2500o_mayorIgual4000</td>
        <td align="center" id=mo>Perimetro_cefalico</td>
        <td align="center" id=mo>Longitud</td>
        <td align="center" id=mo>Edad_gestacional_RN</td>
        <td align="center" id=mo>Edad_gestacional_RN_dias</td>
        <td align="center" id=mo>Edad_gestacional_RN_estimada</td>
        <td align="center" id=mo>Edad_gestacional_RN_FUM</td>
        <td align="center" id=mo>Edad_gestacional_RN_ECO</td>
        <td align="center" id=mo>RN_Peso_para_Edad_Gestacional</td>
        <td align="center" id=mo>Apgar_1erMinuto</td>
        <td align="center" id=mo>Apgar_5toMinuto</td>
        <td align="center" id=mo>RN_Reanimacion_Estimulacion</td>
        <td align="center" id=mo>RN_Reanimacion_Aspiracion</td>
        <td align="center" id=mo>RN_Reanimacion_Mascara</td>
        <td align="center" id=mo>RN_Reanimacion_Oxigeno</td>
        <td align="center" id=mo>RN_Reanimacion_Masaje</td>
        <td align="center" id=mo>RN_Reanimacion_Tubo</td>
        <td align="center" id=mo>Fallece_lugar_de_parto</td>
        <td align="center" id=mo>Referido</td>
        <td align="center" id=mo>Atendio_Parto</td>
        <td align="center" id=mo>Atendio_Parto_nombre</td>
        <td align="center" id=mo>Atendio_Neonato</td>
        <td align="center" id=mo>Atendio_Neonato_nombre</td>
        <td align="center" id=mo>Defectos_congenitos</td>
        <td align="center" id=mo>Defectos_congenitos_codigo</td>
        <td align="center" id=mo>Enfermedades_RN</td>
        <td align="center" id=mo>Codigo_enfermedad_RN1</td>
        <td align="center" id=mo>Notas_enfermedades_RN1</td>
        <td align="center" id=mo>Codigo_enfermedad_RN2</td>
        <td align="center" id=mo>Notas_enfermedades_RN2</td>
        <td align="center" id=mo>Codigo_enfermedad_RN3</td>
        <td align="center" id=mo>Notas_enfermedades_RN3</td>
        <td align="center" id=mo>RN_Tamizaje_VDRL</td>
        <td align="center" id=mo>RN_Tamizaje_TSH</td>
        <td align="center" id=mo>RN_Tamizaje_Hbpatia</td>
        <td align="center" id=mo>RN_Tamizaje_Bilirrubina</td>
        <td align="center" id=mo>RN_Tamizaje_Toxo_IgM</td>
        <td align="center" id=mo>RN_Tamizaje_Meconio_1erdia</td>
        <td align="center" id=mo>Antirubeola_postparto</td>
        <td align="center" id=mo>Egreso_RN_hora</td>
        <td align="center" id=mo>Egreso_RN</td>
        <td align="center" id=mo>Lugar_traslado_RN</td>
        <td align="center" id=mo>Fallece_lugar_de_traslado</td>
        <td align="center" id=mo>Edad_egreso</td>
        <td align="center" id=mo>Edad_menor1dia</td>
        <td align="center" id=mo>Alimento_al_alta</td>
        <td align="center" id=mo>Boca_arriba</td>
        <td align="center" id=mo>BCG</td>
        <td align="center" id=mo>Peso_RN_al_egreso</td>
        <td align="center" id=mo>Fecha_de_egreso_materno</td>
        <td align="center" id=mo>Traslado</td>
        <td align="center" id=mo>Lugar_traslado_materno</td>
        <td align="center" id=mo>Egreso_materno</td>
        <td align="center" id=mo>Fallece_durante_traslado</td>
        <td align="center" id=mo>Fallece_durante_traslado_dias</td>
        <td align="center" id=mo>Anticoncepcion_consejeria</td>
        <td align="center" id=mo>Metodo_antic_elegido</td>
        <td align="center" id=mo>Nro_Historia_RN</td>
        <td align="center" id=mo>Nombre_RN</td>
        <td align="center" id=mo>Responsable_egreso_RN</td>
        <td align="center" id=mo>Responsable_egreso_materno</td>
        <td align="center" id=mo>VIH_personales</td>
        <td align="center" id=mo>Embarazo_ectopico</td>
        <td align="center" id=mo>Fumadora_activa_1er</td>
        <td align="center" id=mo>Fumadora_pasiva_1er</td>
        <td align="center" id=mo>Drogas_1er</td>
        <td align="center" id=mo>Alcohol_1er</td>
        <td align="center" id=mo>Violencia_1er</td>
        <td align="center" id=mo>Fumadora_activa_2do</td>
        <td align="center" id=mo>Fumadora_pasiva_2do</td>
        <td align="center" id=mo>Drogas_2do</td>
        <td align="center" id=mo>Alcohol_2do</td>
        <td align="center" id=mo>Violencia_2do</td>
        <td align="center" id=mo>Fumadora_activa_3er</td>
        <td align="center" id=mo>Fumadora_pasiva_3er</td>
        <td align="center" id=mo>Drogas_3er</td>
        <td align="center" id=mo>Alcohol_3er</td>
        <td align="center" id=mo>Violencia_3er</td>
        <td align="center" id=mo>Gamma_globulina_anti_D</td>
        <td align="center" id=mo>Tamizaje_Antenatal_Prueba_VIH_menor20sem</td>
        <td align="center" id=mo>Tamizaje_Antenatal_TARV_VIH_menor20sem</td>
        <td align="center" id=mo>Tamizaje_Antenatal_Prueba_VIH_mayorIgual20sem</td>
        <td align="center" id=mo>Tamizaje_Antenatal_TARV_VIH_mayorIgual20sem</td>
        <td align="center" id=mo>Semana_prueba_sifilis_No_trepon_menor20s</td>
        <td align="center" id=mo>Semana_prueba_sifilis_No_trepon_mayorIgual20s</td>
        <td align="center" id=mo>Prueba_sifilis_treponemica_menor20s</td>
        <td align="center" id=mo>Semana_prueba_sifilis_Treponemica_menor20s</td>
        <td align="center" id=mo>Semana_prueba_sifilis_Treponemica_mayorIgual20s</td>
        <td align="center" id=mo>Prueba_sifilis_treponemica_mayorIgual20s</td>
        <td align="center" id=mo>Semana_tratamiento_sifilis_menor20s</td>
        <td align="center" id=mo>Semana_tratamiento_sifilis_mayorIgual20s</td>
        <td align="center" id=mo>Tratamiento_sifilis_mayorIgual20s</td>
        <td align="center" id=mo>Tratamiento_sifilis_pareja_menor20s</td>
        <td align="center" id=mo>Egreso_RN_fecha</td>
        <td align="center" id=mo>TDP_Prueba_Sifilis</td>
        <td align="center" id=mo>TDP_Prueba_VIH</td>
        <td align="center" id=mo>VIH_en_RN_Expuesto</td>
        <td align="center" id=mo>VIH_en_RN_Tratamiento</td>
        <td align="center" id=mo>TTO_VDRL</td>
        <td align="center" id=mo>Gamma_globulina_anti_D_egreso</td>

        <td align="center" id=mo>usuario_carga</td>
        <td align="center" id=mo>fecha_hora_carga</td>
        <td align="center" id=mo>fecha_hora_proceso</td>
        <td align="center" id=mo>finalizado</td>
        <td align="center" id=mo>estado</td>

        <td align="center" id=mo>Parto_Aborto</td>
        <td align="center" id=mo>Fecha_de_ingreso</td>
        <td align="center" id=mo>Carne</td>
        <td align="center" id=mo>Consultas_prenatales</td>
        <td align="center" id=mo>Hospitalizacion</td>
        <td align="center" id=mo>Hospitalizacion_dias</td>
        <td align="center" id=mo>Corticoides</td>
        <td align="center" id=mo>Corticoides2</td>
        <td align="center" id=mo>Inicio_parto</td>
        <td align="center" id=mo>Ruptura_de_membranas</td>
        <td align="center" id=mo>Fecha_ruptura_de_membranas</td>
        <td align="center" id=mo>Hora_rupt_membranas</td>
        <td align="center" id=mo>Rupt_membranas_menor_37sem</td>
        <td align="center" id=mo>Rupt_membranas_mayorIgual_18hs</td>
        <td align="center" id=mo>Rupt_membranas_temperatura_mayorIgual_38</td>
        <td align="center" id=mo>Rupt_membranas_temperatura</td>
        <td align="center" id=mo>Edad_gestacional_al_parto</td>
        <td align="center" id=mo>Edad_gestacional_dias</td>
        <td align="center" id=mo>Edad_gestacional_al_parto_por_FUM</td>
        <td align="center" id=mo>Edad_gestacional_al_parto_por_ECO</td>
        <td align="center" id=mo>Presentacion_situacion</td>
        <td align="center" id=mo>Tamaño_fetal_acorde</td>
        <td align="center" id=mo>Acompañante_TDP</td>
        <td align="center" id=mo>Acompañante_Parto</td>
        <td align="center" id=mo>Trabajo_de_parto_detalles</td>
        <td align="center" id=mo>Enfermedades</td>
        <td align="center" id=mo>HTA_previa</td>
        <td align="center" id=mo>HTA_inducida</td>
        <td align="center" id=mo>Preeclampsia</td>
        <td align="center" id=mo>Eclampsia</td>
        <td align="center" id=mo>Cardiopatia</td>
        <td align="center" id=mo>Nefropatia</td>
        <td align="center" id=mo>Diabetes</td>
        <td align="center" id=mo>Infeccion_ovular</td>
        <td align="center" id=mo>Infeccion_urinaria</td>
        <td align="center" id=mo>Amenaza_parto_pretermino</td>
        <td align="center" id=mo>RCIU</td>
        <td align="center" id=mo>Ruptura_prem_de_membranas</td>
        <td align="center" id=mo>Anemia</td>
        <td align="center" id=mo>Otra_condicion_grave</td>
        <td align="center" id=mo>Hemorragia_1er_trim</td>
        <td align="center" id=mo>Hemorragia_2do_trim</td>
        <td align="center" id=mo>Hemorragia_3er_trim</td>
        <td align="center" id=mo>Hemorragia_posparto</td>
        <td align="center" id=mo>Infeccion_purperal</td>
        <td align="center" id=mo>Codigo_enfermedad_1</td>
        <td align="center" id=mo>Codigo_enfermedad_2</td>
        <td align="center" id=mo>Codigo_enfermedad_3</td>
        <td align="center" id=mo>Nacimiento</td>
        <td align="center" id=mo>Hora_nacimiento</td>
        <td align="center" id=mo>Fecha_de_nacimiento</td>
        <td align="center" id=mo>Embarazo_multiple</td>
        <td align="center" id=mo>Embarazo_multiple_orden</td>
        <td align="center" id=mo>Terminacion</td>
        <td align="center" id=mo>Motivo_principal_de_induccion_o_cirugia</td>
        <td align="center" id=mo>Codigo_inducción</td>
        <td align="center" id=mo>Codigo_operatorio</td>
        <td align="center" id=mo>Posicion_parto</td>
        <td align="center" id=mo>Episiotomia</td>
        <td align="center" id=mo>Desgarros_no</td>
        <td align="center" id=mo>Desgarros_grado</td>
        <td align="center" id=mo>Ocitocicos_prealumbramiento</td>
        <td align="center" id=mo>VIT_K</td>
        <td align="center" id=mo>Placenta_completa</td>
        <td align="center" id=mo>Placenta_retenida</td>
        <td align="center" id=mo>Ligadura_cordon_precoz</td>
        <td align="center" id=mo>Ocitócicos_en_TDP</td>
        <td align="center" id=mo>Antibióticos</td>
        <td align="center" id=mo>Analgesia</td>
        <td align="center" id=mo>Anestesia_local</td>
        <td align="center" id=mo>Anestesia_regional</td>
        <td align="center" id=mo>Anestesia_general</td>
        <td align="center" id=mo>Transfusion</td>
        <td align="center" id=mo>Otros</td>
        <td align="center" id=mo>Codigo_Medicacion1</td>
        <td align="center" id=mo>Codigo_Medicacion2</td>

        <td align="center" id=mo>procesado</td>
        <td align="center" id=mo>usuario_ultact</td>
        <td align="center" id=mo>fecha_hora_ultact</td>
        <td align="center" id=mo>score_riesgo</td>

        <td align="center" id=mo>Control_en_parto_1_hora</td>
        <td align="center" id=mo>Control_en_parto_1_minutos</td>
        <td align="center" id=mo>Control_en_parto_1_posicion</td>
        <td align="center" id=mo>Control_en_parto_1_Pasist</td>
        <td align="center" id=mo>Control_en_parto_1_Padiast</td>
        <td align="center" id=mo>Control_en_parto_1_pulso</td>
        <td align="center" id=mo>Control_en_parto_1_contracciones10</td>
        <td align="center" id=mo>Control_en_parto_1_dilatacion</td>
        <td align="center" id=mo>Control_en_parto_1_altura_pres</td>
        <td align="center" id=mo>Control_en_parto_1_variacion_posicion</td>
        <td align="center" id=mo>Control_en_parto_1_meconio</td>
        <td align="center" id=mo>Control_en_parto_1_FCF_dips</td>

        <td align="center" id=mo>Control_post_parto_1_dia</td>
        <td align="center" id=mo>Control_post_parto_1_hora</td>
        <td align="center" id=mo>Control_post_parto_1_temp_grados</td>
        <td align="center" id=mo>Control_post_parto_1_PA_sist</td>
        <td align="center" id=mo>Control_post_parto_1_diast</td>
        <td align="center" id=mo>Control_post_parto_1_pulso</td>
        <td align="center" id=mo>Control_post_parto_1_invol_uter</td>
        <td align="center" id=mo>Control_post_parto_1_loquios</td>

        <td align="center" id=mo>VIH_personales</td>
        <td align="center" id=mo>Embarazo_ectopico</td>
        <td align="center" id=mo>Fumadora_activa_1er</td>
        <td align="center" id=mo>Fumadora_pasiva_1er</td>
        <td align="center" id=mo>Drogas_1er</td>
        <td align="center" id=mo>Alcohol_1er</td>
        <td align="center" id=mo>Violencia_1er</td>
        <td align="center" id=mo>Fumadora_activa_2do</td>
        <td align="center" id=mo>Fumadora_pasiva_2do</td>
        <td align="center" id=mo>Drogas_2do</td>
        <td align="center" id=mo>Alcohol_2do</td>
        <td align="center" id=mo>Violencia_2do</td>
        <td align="center" id=mo>Fumadora_activa_3er</td>
        <td align="center" id=mo>Fumadora_pasiva_3er</td>
        <td align="center" id=mo>Drogas_3er</td>
        <td align="center" id=mo>Alcohol_3er</td>
        <td align="center" id=mo>Violencia_3er</td>
        <td align="center" id=mo>Gamma_globulina_anti_D</td>
        <td align="center" id=mo>Tamizaje_Antenatal_Prueba_VIH_menor_20sem</td>
        <td align="center" id=mo>Tamizaje_Antenatal_TARV_VIH_menor_20sem</td>
        <td align="center" id=mo>Tamizaje_Antenatal_Prueba_VIH_mayor_igual_20sem</td>
        <td align="center" id=mo>Semana_prueba_sifilis_No_trepon_menor_20s</td>
        <td align="center" id=mo>Semana_prueba_sifilis_No_trepon_mayor_igual_20s</td>
        <td align="center" id=mo>Prueba_sifilis_treponemica_menor_20s</td>
        <td align="center" id=mo>Semana_prueba_sifilis_Treponemica_menor_20s</td>
        <td align="center" id=mo>Semana_prueba_sifilis_Treponemica_mayor_igual_20s</td>
        <td align="center" id=mo>Prueba_sifilis_treponemica_mayor_igual_20s</td>
        <td align="center" id=mo>Semana_tratamiento_sifilis_menor_20s</td>
        <td align="center" id=mo>Semana_tratamiento_sifilis_mayor_igual_20s</td>
        <td align="center" id=mo>Tratamiento_sifilis_mayor_igual_20s</td>
        <td align="center" id=mo>Tratamiento_sifilis_pareja_menor_20s</td>
        <td align="center" id=mo>Tratamiento_sifilis_pareja_mayor_igual_20s</td>
        <td align="center" id=mo>TDP_Prueba_Sifilis</td>
        <td align="center" id=mo>TDP_Prueba_VIH</td>
        <td align="center" id=mo>TDP_ARV</td>
        <td align="center" id=mo>VIH_en_RN_Expuesto</td>
        <td align="center" id=mo>VIH_en_RN_Tratamiento</td>
        <td align="center" id=mo>TTO_VDRL</td>
        <td align="center" id=mo>Gamma_globulina_anti_D_egreso</td>

        <td align="center" id=mo>VIT_K</td>
        <td align="center" id=mo>imc</td>
        <td align="center" id=mo>Libre_2</td>
        <td align="center" id=mo>Libre_3</td>
        <td align="center" id=mo>Libre_4</td>
        <td align="center" id=mo>Libre_5</td>
        <td align="center" id=mo>Libre_6</td>
        <td align="center" id=mo>Libre_7</td>
        <td align="center" id=mo>Libre_8</td>
        <td align="center" id=mo>Libre_9</td>
        <td align="center" id=mo>Libre_10</td>
        <td align="center" id=mo>Libre_11</td>
        <td align="center" id=mo>Libre_12</td>
        <td align="center" id=mo>Libre_13</td>
        <td align="center" id=mo>Libre_14</td>
        <td align="center" id=mo>Libre_15</td>

        <td align="center" id=mo>RN_Sexo</td>
        <td align="center" id=mo>Peso_al_nacer</td>
        <td align="center" id=mo>Peso_al_nacer_menor2500o_mayorIgual4000</td>
        <td align="center" id=mo>Perimetro_cefalico</td>
        <td align="center" id=mo>Longitud</td>
        <td align="center" id=mo>Edad_gestacional_RN</td>
        <td align="center" id=mo>Edad_gestacional_RN_dias</td>
        <td align="center" id=mo>Edad_gestacional_RN_estimada</td>
        <td align="center" id=mo>Edad_gestacional_RN_FUM</td>
        <td align="center" id=mo>Edad_gestacional_RN_ECO</td>
        <td align="center" id=mo>RN_Peso_para_Edad_Gestacional</td>
        <td align="center" id=mo>Apgar_1er_Minuto</td>
        <td align="center" id=mo>Apgar_5to_Minuto</td>
        <td align="center" id=mo>RN_Reanimacion_Estimulacion</td>
        <td align="center" id=mo>RN_Reanimacion_Aspiracion</td>
        <td align="center" id=mo>RN_Reanimacion_Mascara</td>
        <td align="center" id=mo>RN_Reanimacion_Oxigeno</td>
        <td align="center" id=mo>RN_Reanimacion_Masaje</td>
        <td align="center" id=mo>RN_Reanimacion_Tubo</td>
        <td align="center" id=mo>Fallece_lugar_de_parto</td>
        <td align="center" id=mo>Referido</td>
        <td align="center" id=mo>Atendio_Parto</td>
        <td align="center" id=mo>Atendio_Parto_nombre</td>
        <td align="center" id=mo>Atendio_Neonato</td>
        <td align="center" id=mo>Atendio_Neonato_nombre</td>
        <td align="center" id=mo>Defectos_congenitos</td>
        <td align="center" id=mo>Defectos_congenitos_codigo</td>
        <td align="center" id=mo>Enfermedades_RN</td>
        <td align="center" id=mo>Codigo_enfermedad_RN_1</td>
        <td align="center" id=mo>Notas_enfermedades_RN_1</td>
        <td align="center" id=mo>Codigo_enfermedad_RN_2</td>
        <td align="center" id=mo>Notas_enfermedades_RN_2</td>
        <td align="center" id=mo>Codigo_enfermedad_RN_3</td>
        <td align="center" id=mo>Notas_enfermedades_RN_3</td>
        <td align="center" id=mo>RN_Tamizaje_VDRL</td>
        <td align="center" id=mo>RN_Tamizaje_TSH</td>
        <td align="center" id=mo>RN_Tamizaje_Hbpatia</td>
        <td align="center" id=mo>RN_Tamizaje_Bilirrubina</td>
        <td align="center" id=mo>RN_Tamizaje_Toxo_IgM</td>
        <td align="center" id=mo>RN_Tamizaje_Meconio_1er_dia</td>
        <td align="center" id=mo>Antirubeola_postparto</td>
        <td align="center" id=mo>Egreso_RN_fecha</td>
        <td align="center" id=mo>Egreso_RN_hora</td>
        <td align="center" id=mo>Egreso_RN</td>
        <td align="center" id=mo>Lugar_traslado_RN</td>
        <td align="center" id=mo>Fallece_lugar_de_traslado</td>
        <td align="center" id=mo>Edad_egreso</td>
        <td align="center" id=mo>Edad_menor1dia</td>
        <td align="center" id=mo>Alimento_al_alta</td>
        <td align="center" id=mo>Boca_arriba</td>
        <td align="center" id=mo>BCG</td>
        <td align="center" id=mo>Peso_RN_al_egreso</td>
        <td align="center" id=mo>Fecha_de_egreso_materno</td>
        <td align="center" id=mo>Traslado</td>
        <td align="center" id=mo>Lugar_traslado_materno</td>
        <td align="center" id=mo>Egreso_materno</td>
        <td align="center" id=mo>Fallece_durante_traslado</td>
        <td align="center" id=mo>Fallece_durante_traslado_dias</td>
        <td align="center" id=mo>Anticoncepcion_consejeria</td>
        <td align="center" id=mo>Metodo_antic_elegido</td>
        <td align="center" id=mo>Nro_Historia_RN</td>
        <td align="center" id=mo>Nombre_RN</td>
        <td align="center" id=mo>Responsable_egreso_RN</td>
        <td align="center" id=mo>Responsable_egreso_materno</td>

        <td align="center" id=mo>login</td>
        <td align="center" id=mo>apellido</td>
        <td align="center" id=mo>nombre</td>
  </tr>
  <?   
  while (!$result->EOF) {?>
        
  <tr>          
    <td><?=$result->fields['apellido_benef']?></td> 
        <td><?=$result->fields['nombre_benef']?></td>
        <td><?=$result->fields['sexo']?></td>
        <td><?=$result->fields['numero_doc']?></td>
        <td><?=$result->fields['domicilio']?></td>
        <td><?=$result->fields['telefono']?></td>
        <td><?=$result->fields['cuie']?></td>
        <td><?=$result->fields['nombre_efector']?></td>        
        <td><?=$result->fields['referente']?></td>        
        <td><?=$result->fields['fecha_de_nacimiento_madre']?></td>
        <td><?=$result->fields['edad_materna']?></td>
        <td><?=$result->fields['edad_materna_menor15_o_mayor35']?></td>
        <td><?=$result->fields['etnia']?></td>
        <td><?=$result->fields['alfabeta']?></td>
        <td><?=$result->fields['estudios']?></td>
        <td><?=$result->fields['años_estudios_mayor_nivel']?></td>
        <td><?=$result->fields['estado_civil']?></td>
        <td><?=$result->fields['vive_sola']?></td>
        <td><?=$result->fields['lugar_control_prenatal']?></td>
        <td><?=$result->fields['lugar_de_parto']?></td>
        <td><?=$result->fields['numero_de_identidad']?></td>
        
        <td align="center"><?=$result->fields['tbc_familiares']?></td>
        <td align="center"><?=$result->fields['diabetes_familiares']?></td>
        <td align="center"><?=$result->fields['hipertension_familiares']?></td>
        <td align="center"><?=$result->fields['antecedentes_preclampsia_familiares']?></td>
        <td align="center"><?=$result->fields['eclampsia_familiares']?></td>
        <td align="center"><?=$result->fields['otro_antecedente_familiar']?></td>
        <td align="center"><?=$result->fields['tbc_personales']?></td>
        <td align="center"><?=$result->fields['diabetes_personales']?></td>
        <td align="center"><?=$result->fields['hipertension_personales']?></td>
        <td align="center"><?=$result->fields['antecedentes_preclampsia_personales']?></td>
        <td align="center"><?=$result->fields['eclampsia_personales']?></td>
        <td align="center"><?=$result->fields['otro_antecedente_personal']?></td>
        <td align="center"><?=$result->fields['cirugia_personales']?></td>
        <td align="center"><?=$result->fields['infertilidad_personales']?></td>
        <td align="center"><?=$result->fields['cardiopatia_antecedentes']?></td>
        <td align="center"><?=$result->fields['antecedentes_Nefropatia']?></td>
        <td align="center"><?=$result->fields['violencia_personales']?></td>
        <td align="center"><?=$result->fields['otros_antecedentes']?></td>
        <td align="center"><?=$result->fields['gestas_previas']?></td>
        <td align="center"><?=$result->fields['abortos_previos']?></td>
        <td align="center"><?=$result->fields['tres_abortos_espontaneos_consecutivos']?></td>
        <td align="center"><?=$result->fields['partos_previos']?></td>
        <td align="center"><?=$result->fields['peso_RN_previo']?></td>
        <td align="center"><?=$result->fields['antecedentes_gemelares']?></td>
        <td align="center"><?=$result->fields['partos_vaginales']?></td>
        <td align="center"><?=$result->fields['cesareas_previas']?></td>
        <td align="center"><?=$result->fields['ant_nacidos_vivos']?></td>
        <td align="center"><?=$result->fields['rn_que_viven']?></td>
        <td align="center"><?=$result->fields['ant_nacidos_muertos']?></td>
        <td align="center"><?=$result->fields['ant_muertos_1a_sem']?></td>
        <td align="center"><?=$result->fields['ant_muertos_despues_1a_sem']?></td>
        <td align="center"><?=$result->fields['fecha_embarazo_anterior']?></td>
        <td align="center"><?=$result->fields['embarazo_anterior_menor_1']?></td>
        <td align="center"><?=$result->fields['embarazo_planeado']?></td>
        <td align="center"><?=$result->fields['fracaso_metodo_anticonceptivo']?></td>
        
        <td align="center"><?=$result->fields['peso_anterior']?></td>
        <td align="center"><?=$result->fields['talla_madre']?></td>
        <td align="center"><?=$result->fields['fecha_ultima_menstruacion']?></td>
        <td align="center"><?=$result->fields['fecha_probable_de_parto']?></td>
        <td align="center"><?=$result->fields['eg_confiable_por_fum']?></td>
        <td align="center"><?=$result->fields['eg_confiable_por_Eco_menor20s']?></td>
        <td align="center"><?=$result->fields['antirubeola']?></td>
        <td align="center"><?=$result->fields['antitetanica_vigente']?></td>
        <td align="center"><?=$result->fields['antitetanica_dosis_1']?></td>
        <td align="center"><?=$result->fields['antitetanica_dosis_2']?></td>
        <td align="center"><?=$result->fields['examen_odontologico_normal']?></td>
        <td align="center"><?=$result->fields['examen_mamas_normal']?></td>
        <td align="center"><?=$result->fields['cervix_insp_Visual']?></td>
        <td align="center"><?=$result->fields['cervix_PAP']?></td>
        <td align="center"><?=$result->fields['cervix_COLP']?></td>
        <td align="center"><?=$result->fields['grupo_sanguineo']?></td>
        <td align="center"><?=$result->fields['rh']?></td>
        <td align="center"><?=$result->fields['inmunizacion']?></td>
        <td align="center"><?=$result->fields['tamizaje_Antenatal_Toxoplasmosis_menor20_sem']?></td>
        <td align="center"><?=$result->fields['tamizaje_Antenatal_Toxoplasmosis_mayoIgual20_sem']?></td>
        <td align="center"><?=$result->fields['tamizaje_Antenatal_Toxoplasmosis_1er_Consulta']?></td>
        <td align="center"><?=$result->fields['hb_menor_20_sem']?></td>
        <td align="center"><?=$result->fields['hb_menor_20_sem_menor_11g']?></td>
        <td align="center"><?=$result->fields['hierro_indicado']?></td>
        <td align="center"><?=$result->fields['folatos_indicados']?></td>
        <td align="center"><?=$result->fields['hb_mayorigual20_sem']?></td>
        <td align="center"><?=$result->fields['hb_mayorigual20sem_menor_11g']?></td>
        <td align="center"><?=$result->fields['tamizaje_antenatal_chagas']?></td>
        <td align="center"><?=$result->fields['Tamizaje_Antenatal_Hepatitis_B']?></td>
        <td align="center"><?=$result->fields['bacteriuria_menor20sem']?></td>
        <td align="center"><?=$result->fields['bacteriuria_mayorigual20sem']?></td>
        <td align="center"><?=$result->fields['glucemia_menor20sem']?></td>
        <td align="center"><?=$result->fields['glumenor20sem_mayor_105']?></td>
        <td align="center"><?=$result->fields['glu_mayorigual30sem_mayor_105']?></td>
        <td align="center"><?=$result->fields['glucemia_mayorigual30sem']?></td>
        <td align="center"><?=$result->fields['tamizaje_antenatal_estreptococo_b']?></td>
        <td align="center"><?=$result->fields['preparacion_parto']?></td>
        <td align="center"><?=$result->fields['consejeria_lactancia']?></td>
        <td align="center"><?=$result->fields['tamizaje_antenatal_vih_menor20sem_solicitado']?></td>
        <td align="center"><?=$result->fields['tamizaje_antenatal_vih_moyorigual20sem_solicitado']?></td>
        <td align="center"><?=$result->fields['prueba_sifilis_no_treponemica_menor20s']?></td>
        <td align="center"><?=$result->fields['prueba_sifilis_no_treponemica_mayorigual20s']?></td>
        <td align="center"><?=$result->fields['tratamiento_sifilis_menor20s']?></td>

        <td align="center"><?=$result->fields['rn_sexo']?></td>
        <td align="center"><?=$result->fields['peso_al_nacer']?></td>
        <td align="center"><?=$result->fields['peso_al_nacer_menor2500o_mayorigual4000']?></td>
        <td align="center"><?=$result->fields['perimetro_cefalico']?></td>
        <td align="center"><?=$result->fields['longitud']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_rn']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_rn_dias']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_rn_estimada']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_rn_fum']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_rn_eco']?></td>
        <td align="center"><?=$result->fields['rn_peso_para_edad_gestacional']?></td>
        <td align="center"><?=$result->fields['apgar_1erminuto']?></td>
        <td align="center"><?=$result->fields['apgar_5tominuto']?></td>
        <td align="center"><?=$result->fields['rn_reanimacion_estimulacion']?></td>
        <td align="center"><?=$result->fields['rn_reanimacion_aspiracion']?></td>
        <td align="center"><?=$result->fields['rn_reanimacion_mascara']?></td>
        <td align="center"><?=$result->fields['rn_reanimacion_oxigeno']?></td>
        <td align="center"><?=$result->fields['rn_reanimacion_masaje']?></td>
        <td align="center"><?=$result->fields['rn_reanimacion_tubo']?></td>
        <td align="center"><?=$result->fields['fallece_lugar_de_parto']?></td>
        <td align="center"><?=$result->fields['referido']?></td>
        <td align="center"><?=$result->fields['atendio_parto']?></td>
        <td align="center"><?=$result->fields['atendio_parto_nombre']?></td>
        <td align="center"><?=$result->fields['atendio_neonato']?></td>
        <td align="center"><?=$result->fields['atendio_neonato_nombre']?></td>
        <td align="center"><?=$result->fields['defectos_congenitos']?></td>
        <td align="center"><?=$result->fields['defectos_congenitos_codigo']?></td>
        <td align="center"><?=$result->fields['enfermedades_rn']?></td>
        <td align="center"><?=$result->fields['codigo_enfermedad_rn1']?></td>
        <td align="center"><?=$result->fields['notas_enfermedades_rn1']?></td>
        <td align="center"><?=$result->fields['codigo_enfermedad_rn2']?></td>
        <td align="center"><?=$result->fields['notas_enfermedades_rn2']?></td>
        <td align="center"><?=$result->fields['codigo_enfermedad_rn3']?></td>
        <td align="center"><?=$result->fields['notas_enfermedades_rn3']?></td>
        <td align="center"><?=$result->fields['rn_tamizaje_vdrl']?></td>
        <td align="center"><?=$result->fields['rn_tamizaje_tsh']?></td>
        <td align="center"><?=$result->fields['rn_tamizaje_hbpatia']?></td>
        <td align="center"><?=$result->fields['rn_tamizaje_bilirrubina']?></td>
        <td align="center"><?=$result->fields['rn_tamizaje_toxo_igm']?></td>
        <td align="center"><?=$result->fields['rn_tamizaje_meconio_1erdia']?></td>
        <td align="center"><?=$result->fields['antirubeola_postparto']?></td>
        <td align="center"><?=$result->fields['egreso_rn_hora']?></td>
        <td align="center"><?=$result->fields['egreso_rn']?></td>
        <td align="center"><?=$result->fields['lugar_traslado_rn']?></td>
        <td align="center"><?=$result->fields['fallece_lugar_de_traslado']?></td>
        <td align="center"><?=$result->fields['edad_egreso']?></td>
        <td align="center"><?=$result->fields['edad_menor1dia']?></td>
        <td align="center"><?=$result->fields['alimento_al_alta']?></td>
        <td align="center"><?=$result->fields['boca_arriba']?></td>
        <td align="center"><?=$result->fields['bcg']?></td>
        <td align="center"><?=$result->fields['peso_rn_al_egreso']?></td>
        <td align="center"><?=$result->fields['fecha_de_egreso_materno']?></td>
        <td align="center"><?=$result->fields['traslado']?></td>
        <td align="center"><?=$result->fields['lugar_traslado_materno']?></td>
        <td align="center"><?=$result->fields['egreso_materno']?></td>
        <td align="center"><?=$result->fields['fallece_durante_traslado']?></td>
        <td align="center"><?=$result->fields['fallece_durante_traslado_dias']?></td>
        <td align="center"><?=$result->fields['anticoncepcion_consejeria']?></td>
        <td align="center"><?=$result->fields['metodo_antic_elegido']?></td>
        <td align="center"><?=$result->fields['nro_historia_rn']?></td>
        <td align="center"><?=$result->fields['nombre_rn']?></td>
        <td align="center"><?=$result->fields['responsable_egreso_rn']?></td>
        <td align="center"><?=$result->fields['responsable_egreso_materno']?></td>
        <td align="center"><?=$result->fields['vih_personales']?></td>
        <td align="center"><?=$result->fields['embarazo_ectopico']?></td>
        <td align="center"><?=$result->fields['fumadora_activa_1er']?></td>
        <td align="center"><?=$result->fields['fumadora_pasiva_1er']?></td>
        <td align="center"><?=$result->fields['drogas_1er']?></td>
        <td align="center"><?=$result->fields['alcohol_1er']?></td>
        <td align="center"><?=$result->fields['violencia_1er']?></td>
        <td align="center"><?=$result->fields['fumadora_activa_2do']?></td>
        <td align="center"><?=$result->fields['fumadora_pasiva_2do']?></td>
        <td align="center"><?=$result->fields['drogas_2do']?></td>
        <td align="center"><?=$result->fields['alcohol_2do']?></td>
        <td align="center"><?=$result->fields['violencia_2do']?></td>
        <td align="center"><?=$result->fields['fumadora_activa_3er']?></td>
        <td align="center"><?=$result->fields['fumadora_pasiva_3er']?></td>
        <td align="center"><?=$result->fields['drogas_3er']?></td>
        <td align="center"><?=$result->fields['alcohol_3er']?></td>
        <td align="center"><?=$result->fields['violencia_3er']?></td>
        <td align="center"><?=$result->fields['gamma_globulina_anti_d']?></td>
        <td align="center"><?=$result->fields['tamizaje_antenatal_prueba_vih_menor20sem']?></td>
        <td align="center"><?=$result->fields['tamizaje_antenatal_tarv_vih_menor20sem']?></td>
        <td align="center"><?=$result->fields['tamizaje_antenatal_prueba_vih_mayorigual20sem']?></td>
        <td align="center"><?=$result->fields['tamizaje_antenatal_tarv_vih_mayorigual20sem']?></td>
        <td align="center"><?=$result->fields['semana_prueba_sifilis_no_trepon_menor20s']?></td>
        <td align="center"><?=$result->fields['semana_prueba_sifilis_no_trepon_mayorigual20s']?></td>
        <td align="center"><?=$result->fields['prueba_sifilis_treponemica_menor20s']?></td>
        <td align="center"><?=$result->fields['semana_prueba_sifilis_treponemica_menor20s']?></td>
        <td align="center"><?=$result->fields['semana_prueba_sifilis_treponemica_mayorigual20s']?></td>
        <td align="center"><?=$result->fields['prueba_sifilis_treponemica_mayorigual20s']?></td>
        <td align="center"><?=$result->fields['semana_tratamiento_sifilis_menor20s']?></td>
        <td align="center"><?=$result->fields['semana_tratamiento_sifilis_mayorigual20s']?></td>
        <td align="center"><?=$result->fields['tratamiento_sifilis_mayorigual20s']?></td>
        <td align="center"><?=$result->fields['tratamiento_sifilis_pareja_menor20s']?></td>
        <td align="center"><?=$result->fields['egreso_rn_fecha']?></td>
        <td align="center"><?=$result->fields['tdp_prueba_sifilis']?></td>
        <td align="center"><?=$result->fields['tdp_prueba_vih']?></td>
        <td align="center"><?=$result->fields['vih_en_rn_expuesto']?></td>
        <td align="center"><?=$result->fields['vih_en_rn_tratamiento']?></td>
        <td align="center"><?=$result->fields['tto_vdrl']?></td>
        <td align="center"><?=$result->fields['gamma_globulina_anti_d_egreso']?></td>

        <td align="center"><?=$result->fields['usuario_carga']?></td>
        <td align="center"><?=$result->fields['fecha_hora_carga']?></td>
        <td align="center"><?=$result->fields['fecha_hora_proceso']?></td>
        <td align="center"><?=$result->fields['finalizado']?></td>
        <td align="center"><?=$result->fields['estado']?></td>

        <td align="center"><?=$result->fields['parto_aborto']?></td>
        <td align="center"><?=$result->fields['fecha_de_ingreso']?></td>
        <td align="center"><?=$result->fields['carne']?></td>
        <td align="center"><?=$result->fields['consultas_prenatales']?></td>
        <td align="center"><?=$result->fields['hospitalizacion']?></td>
        <td align="center"><?=$result->fields['hospitalizacion_dias']?></td>
        <td align="center"><?=$result->fields['corticoides']?></td>
        <td align="center"><?=$result->fields['corticoides2']?></td>
        <td align="center"><?=$result->fields['inicio_parto']?></td>
        <td align="center"><?=$result->fields['ruptura_de_membranas']?></td>
        <td align="center"><?=$result->fields['fecha_ruptura_de_membranas']?></td>
        <td align="center"><?=$result->fields['hora_rupt_membranas']?></td>
        <td align="center"><?=$result->fields['rupt_membranas_menor_37sem']?></td>
        <td align="center"><?=$result->fields['rupt_membranas_mayorigual_18hs']?></td>
        <td align="center"><?=$result->fields['rupt_membranas_temperatura_mayorigual_38']?></td>
        <td align="center"><?=$result->fields['rupt_membranas_temperatura']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_al_parto']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_dias']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_al_parto_por_fum']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_al_parto_por_eco']?></td>
        <td align="center"><?=$result->fields['presentacion_situacion']?></td>
        <td align="center"><?=$result->fields['tamaño_fetal_acorde']?></td>
        <td align="center"><?=$result->fields['acompañante_tdp']?></td>
        <td align="center"><?=$result->fields['acompañante_parto']?></td>
        <td align="center"><?=$result->fields['trabajo_de_parto_detalles']?></td>
        <td align="center"><?=$result->fields['enfermedades']?></td>
        <td align="center"><?=$result->fields['hta_previa']?></td>
        <td align="center"><?=$result->fields['hta_inducida']?></td>
        <td align="center"><?=$result->fields['preeclampsia']?></td>
        <td align="center"><?=$result->fields['eclampsia']?></td>
        <td align="center"><?=$result->fields['cardiopatia']?></td>
        <td align="center"><?=$result->fields['nefropatia']?></td>
        <td align="center"><?=$result->fields['diabetes']?></td>
        <td align="center"><?=$result->fields['infeccion_ovular']?></td>
        <td align="center"><?=$result->fields['infeccion_urinaria']?></td>
        <td align="center"><?=$result->fields['amenaza_parto_pretermino']?></td>
        <td align="center"><?=$result->fields['rciu']?></td>
        <td align="center"><?=$result->fields['ruptura_prem_de_membranas']?></td>
        <td align="center"><?=$result->fields['anemia']?></td>
        <td align="center"><?=$result->fields['otra_condicion_grave']?></td>
        <td align="center"><?=$result->fields['hemorragia_1er_trim']?></td>
        <td align="center"><?=$result->fields['hemorragia_2do_trim']?></td>
        <td align="center"><?=$result->fields['hemorragia_3er_trim']?></td>
        <td align="center"><?=$result->fields['hemorragia_posparto']?></td>
        <td align="center"><?=$result->fields['infeccion_purperal']?></td>
        <td align="center"><?=$result->fields['codigo_enfermedad_1']?></td>
        <td align="center"><?=$result->fields['codigo_enfermedad_2']?></td>
        <td align="center"><?=$result->fields['codigo_enfermedad_3']?></td>
        <td align="center"><?=$result->fields['nacimiento']?></td>
        <td align="center"><?=$result->fields['hora_nacimiento']?></td>
        <td align="center"><?=$result->fields['fecha_de_nacimiento']?></td>
        <td align="center"><?=$result->fields['embarazo_multiple']?></td>
        <td align="center"><?=$result->fields['embarazo_multiple_orden']?></td>
        <td align="center"><?=$result->fields['terminacion']?></td>
        <td align="center"><?=$result->fields['motivo_principal_de_induccion_o_cirugia']?></td>
        <td align="center"><?=$result->fields['codigo_inducción']?></td>
        <td align="center"><?=$result->fields['codigo_operatorio']?></td>
        <td align="center"><?=$result->fields['posicion_parto']?></td>
        <td align="center"><?=$result->fields['episiotomia']?></td>
        <td align="center"><?=$result->fields['desgarros_no']?></td>
        <td align="center"><?=$result->fields['desgarros_grado']?></td>
        <td align="center"><?=$result->fields['ocitocicos_prealumbramiento']?></td>
        <td align="center"><?=$result->fields['vit_k']?></td>
        <td align="center"><?=$result->fields['placenta_completa']?></td>
        <td align="center"><?=$result->fields['placenta_retenida']?></td>
        <td align="center"><?=$result->fields['ligadura_cordon_precoz']?></td>
        <td align="center"><?=$result->fields['ocitócicos_en_tdp']?></td>
        <td align="center"><?=$result->fields['antibióticos']?></td>
        <td align="center"><?=$result->fields['analgesia']?></td>
        <td align="center"><?=$result->fields['anestesia_local']?></td>
        <td align="center"><?=$result->fields['anestesia_regional']?></td>
        <td align="center"><?=$result->fields['anestesia_general']?></td>
        <td align="center"><?=$result->fields['transfusion']?></td>
        <td align="center"><?=$result->fields['otros']?></td>
        <td align="center"><?=$result->fields['codigo_medicacion1']?></td>
        <td align="center"><?=$result->fields['codigo_medicacion2']?></td>

        <td align="center"><?=$result->fields['procesado']?></td>
        <td align="center"><?=$result->fields['usuario_ultact']?></td>
        <td align="center"><?=$result->fields['fecha_hora_ultact']?></td>
        <td align="center"><?=$result->fields['score_riesgo']?></td>

        <td align="center"><?=$result->fields['control_en_parto_1_hora']?></td>
        <td align="center"><?=$result->fields['control_en_parto_1_minutos']?></td>
        <td align="center"><?=$result->fields['control_en_parto_1_posicion']?></td>
        <td align="center"><?=$result->fields['control_en_parto_1_pasist']?></td>
        <td align="center"><?=$result->fields['control_en_parto_1_padiast']?></td>
        <td align="center"><?=$result->fields['control_en_parto_1_pulso']?></td>
        <td align="center"><?=$result->fields['control_en_parto_1_contracciones10']?></td>
        <td align="center"><?=$result->fields['control_en_parto_1_dilatacion']?></td>
        <td align="center"><?=$result->fields['control_en_parto_1_altura_pres']?></td>
        <td align="center"><?=$result->fields['control_en_parto_1_variacion_posicion']?></td>
        <td align="center"><?=$result->fields['control_en_parto_1_meconio']?></td>
        <td align="center"><?=$result->fields['control_en_parto_1_fcf_dips']?></td>

        <td align="center"><?=$result->fields['control_post_parto_1_dia']?></td>
        <td align="center"><?=$result->fields['control_post_parto_1_hora']?></td>
        <td align="center"><?=$result->fields['control_post_parto_1_temp_grados']?></td>
        <td align="center"><?=$result->fields['control_post_parto_1_pa_sist']?></td>
        <td align="center"><?=$result->fields['control_post_parto_1_diast']?></td>
        <td align="center"><?=$result->fields['control_post_parto_1_pulso']?></td>
        <td align="center"><?=$result->fields['control_post_parto_1_invol_uter']?></td>
        <td align="center"><?=$result->fields['control_post_parto_1_loquios']?></td>

        <td align="center"><?=$result->fields['vih_personales']?></td>
        <td align="center"><?=$result->fields['embarazo_ectopico']?></td>
        <td align="center"><?=$result->fields['fumadora_activa_1er']?></td>
        <td align="center"><?=$result->fields['fumadora_pasiva_1er']?></td>
        <td align="center"><?=$result->fields['drogas_1er']?></td>
        <td align="center"><?=$result->fields['alcohol_1er']?></td>
        <td align="center"><?=$result->fields['violencia_1er']?></td>
        <td align="center"><?=$result->fields['fumadora_activa_2do']?></td>
        <td align="center"><?=$result->fields['fumadora_pasiva_2do']?></td>
        <td align="center"><?=$result->fields['drogas_2do']?></td>
        <td align="center"><?=$result->fields['alcohol_2do']?></td>
        <td align="center"><?=$result->fields['violencia_2do']?></td>
        <td align="center"><?=$result->fields['fumadora_activa_3er']?></td>
        <td align="center"><?=$result->fields['fumadora_pasiva_3er']?></td>
        <td align="center"><?=$result->fields['drogas_3er']?></td>
        <td align="center"><?=$result->fields['alcohol_3er']?></td>
        <td align="center"><?=$result->fields['violencia_3er']?></td>
        <td align="center"><?=$result->fields['gamma_globulina_anti_d']?></td>
        <td align="center"><?=$result->fields['tamizaje_antenatal_prueba_vih_menor_20sem']?></td>
        <td align="center"><?=$result->fields['tamizaje_antenatal_tarv_vih_menor_20sem']?></td>
        <td align="center"><?=$result->fields['tamizaje_antenatal_prueba_vih_mayor_igual_20sem']?></td>
        <td align="center"><?=$result->fields['semana_prueba_sifilis_no_trepon_menor_20s']?></td>
        <td align="center"><?=$result->fields['semana_prueba_sifilis_no_trepon_mayor_igual_20s']?></td>
        <td align="center"><?=$result->fields['prueba_sifilis_treponemica_menor_20s']?></td>
        <td align="center"><?=$result->fields['semana_prueba_sifilis_treponemica_menor_20s']?></td>
        <td align="center"><?=$result->fields['semana_prueba_sifilis_treponemica_mayor_igual_20s']?></td>
        <td align="center"><?=$result->fields['prueba_sifilis_treponemica_mayor_igual_20s']?></td>
        <td align="center"><?=$result->fields['semana_tratamiento_sifilis_menor_20s']?></td>
        <td align="center"><?=$result->fields['semana_tratamiento_sifilis_mayor_igual_20s']?></td>
        <td align="center"><?=$result->fields['tratamiento_sifilis_mayor_igual_20s']?></td>
        <td align="center"><?=$result->fields['tratamiento_sifilis_pareja_menor_20s']?></td>
        <td align="center"><?=$result->fields['tratamiento_sifilis_pareja_mayor_igual_20s']?></td>
        <td align="center"><?=$result->fields['tdp_prueba_sifilis']?></td>
        <td align="center"><?=$result->fields['tdp_prueba_vih']?></td>
        <td align="center"><?=$result->fields['tdp_arv']?></td>
        <td align="center"><?=$result->fields['vih_en_rn_expuesto']?></td>
        <td align="center"><?=$result->fields['vih_en_rn_tratamiento']?></td>
        <td align="center"><?=$result->fields['tto_vdrl']?></td>
        <td align="center"><?=$result->fields['gamma_globulina_anti_d_egreso']?></td>

        <td align="center"><?=$result->fields['vit_k']?></td>
        <td align="center"><?=$result->fields['imc']?></td>
        <td align="center"><?=$result->fields['libre_2']?></td>
        <td align="center"><?=$result->fields['libre_3']?></td>
        <td align="center"><?=$result->fields['libre_4']?></td>
        <td align="center"><?=$result->fields['libre_5']?></td>
        <td align="center"><?=$result->fields['libre_6']?></td>
        <td align="center"><?=$result->fields['libre_7']?></td>
        <td align="center"><?=$result->fields['libre_8']?></td>
        <td align="center"><?=$result->fields['libre_9']?></td>
        <td align="center"><?=$result->fields['libre_10']?></td>
        <td align="center"><?=$result->fields['libre_11']?></td>
        <td align="center"><?=$result->fields['libre_12']?></td>
        <td align="center"><?=$result->fields['libre_13']?></td>
        <td align="center"><?=$result->fields['libre_14']?></td>
        <td align="center"><?=$result->fields['libre_15']?></td>

        <td align="center"><?=$result->fields['rn_sexo']?></td>
        <td align="center"><?=$result->fields['peso_al_nacer']?></td>
        <td align="center"><?=$result->fields['peso_al_nacer_menor2500o_mayorigual4000']?></td>
        <td align="center"><?=$result->fields['perimetro_cefalico']?></td>
        <td align="center"><?=$result->fields['longitud']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_rn']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_rn_dias']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_rn_estimada']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_rn_fum']?></td>
        <td align="center"><?=$result->fields['edad_gestacional_rn_eco']?></td>
        <td align="center"><?=$result->fields['rn_peso_para_edad_gestacional']?></td>
        <td align="center"><?=$result->fields['apgar_1er_minuto']?></td>
        <td align="center"><?=$result->fields['apgar_5to_minuto']?></td>
        <td align="center"><?=$result->fields['rn_reanimacion_estimulacion']?></td>
        <td align="center"><?=$result->fields['rn_reanimacion_aspiracion']?></td>
        <td align="center"><?=$result->fields['rn_reanimacion_mascara']?></td>
        <td align="center"><?=$result->fields['rn_reanimacion_oxigeno']?></td>
        <td align="center"><?=$result->fields['rn_reanimacion_masaje']?></td>
        <td align="center"><?=$result->fields['rn_reanimacion_tubo']?></td>
        <td align="center"><?=$result->fields['fallece_lugar_de_parto']?></td>
        <td align="center"><?=$result->fields['referido']?></td>
        <td align="center"><?=$result->fields['atendio_parto']?></td>
        <td align="center"><?=$result->fields['atendio_parto_nombre']?></td>
        <td align="center"><?=$result->fields['atendio_neonato']?></td>
        <td align="center"><?=$result->fields['atendio_neonato_nombre']?></td>
        <td align="center"><?=$result->fields['defectos_congenitos']?></td>
        <td align="center"><?=$result->fields['defectos_congenitos_codigo']?></td>
        <td align="center"><?=$result->fields['enfermedades_rn']?></td>
        <td align="center"><?=$result->fields['codigo_enfermedad_rn_1']?></td>
        <td align="center"><?=$result->fields['notas_enfermedades_rn_1']?></td>
        <td align="center"><?=$result->fields['codigo_enfermedad_rn_2']?></td>
        <td align="center"><?=$result->fields['notas_enfermedades_rn_2']?></td>
        <td align="center"><?=$result->fields['codigo_enfermedad_rn_3']?></td>
        <td align="center"><?=$result->fields['notas_enfermedades_rn_3']?></td>
        <td align="center"><?=$result->fields['rn_tamizaje_vdrl']?></td>
        <td align="center"><?=$result->fields['rn_tamizaje_tsh']?></td>
        <td align="center"><?=$result->fields['rn_tamizaje_hbpatia']?></td>
        <td align="center"><?=$result->fields['rn_tamizaje_bilirrubina']?></td>
        <td align="center"><?=$result->fields['rn_tamizaje_toxo_igm']?></td>
        <td align="center"><?=$result->fields['rn_tamizaje_meconio_1er_dia']?></td>
        <td align="center"><?=$result->fields['antirubeola_postparto']?></td>
        <td align="center"><?=$result->fields['egreso_rn_fecha']?></td>
        <td align="center"><?=$result->fields['egreso_rn_hora']?></td>
        <td align="center"><?=$result->fields['egreso_rn']?></td>
        <td align="center"><?=$result->fields['lugar_traslado_rn']?></td>
        <td align="center"><?=$result->fields['fallece_lugar_de_traslado']?></td>
        <td align="center"><?=$result->fields['edad_egreso']?></td>
        <td align="center"><?=$result->fields['edad_menor1dia']?></td>
        <td align="center"><?=$result->fields['alimento_al_alta']?></td>
        <td align="center"><?=$result->fields['boca_arriba']?></td>
        <td align="center"><?=$result->fields['bcg']?></td>
        <td align="center"><?=$result->fields['peso_rn_al_egreso']?></td>
        <td align="center"><?=$result->fields['fecha_de_egreso_materno']?></td>
        <td align="center"><?=$result->fields['traslado']?></td>
        <td align="center"><?=$result->fields['lugar_traslado_materno']?></td>
        <td align="center"><?=$result->fields['egreso_materno']?></td>
        <td align="center"><?=$result->fields['fallece_durante_traslado']?></td>
        <td align="center"><?=$result->fields['fallece_durante_traslado_dias']?></td>
        <td align="center"><?=$result->fields['anticoncepcion_consejeria']?></td>
        <td align="center"><?=$result->fields['metodo_antic_elegido']?></td>
        <td align="center"><?=$result->fields['nro_historia_rn']?></td>
        <td align="center"><?=$result->fields['nombre_rn']?></td>
        <td align="center"><?=$result->fields['responsable_egreso_rn']?></td>
        <td align="center"><?=$result->fields['responsable_egreso_materno']?></td>

        <td><?=$result->fields['login']?></td>
        <td><?=$result->fields['apellido']?></td>
        <td><?=$result->fields['nombre']?></td>
  </tr>
  
  <?$result->MoveNext();
    }?>
 </table>
 </form>
 <?=fin_pagina();// aca termino ?>