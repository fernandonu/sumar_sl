<?
/*
$Author: Seba $
$Revision: 1.0 $
$Date: 2017/11/14 $
*/
require_once ("../../config.php");
require_once ("add_libs.php");
require_once ("get_datos.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

$login=$_ses_user['login'];
if (es_cuie($login)) $cuie=$_ses_user['login'];
$id_user=intval($_ses_user['id']);
$query_user="SELECT * from sistema.usu_efec where id_usuario=$id_user";
$res_user=sql($query_user) or fin_pagina();
if ($res_user->RecordCount()>0) {
  $user_cuie='(';
  for ($i=0;$i<$res_user->RecordCount();$i++) {
    $user_cuie.="'".$res_user->fields['cuie']."'".',';
    $res_user->MoveNext();
  };
  $user_cuie.=')';
  $user_cuie=str_replace(',)',')',$user_cuie);
  $user_efector=1;

}
  else $user_efector=0;

if ($_POST['genera_excel']) {
  
  $varperiodo=$_POST['varperiodo'];
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);
  $cuie=$_POST['cuie'];
  $efe_depto=$_POST['efe_depto'];
  
  $link=encode_link("sipweb_excel.php",array("var_periodo"=>$varperiodo, "fecha_desde"=>$fecha_desde, "fecha_hasta"=>$fecha_hasta,"cuie"=>$cuie,"efe_depto"=>$efe_depto));?>
  
  <script>
  window.open('<?=$link?>');
  </script>
  <?
}

if ($_POST['traer_datos']=='Detalle Global'){
  
  $varperiodo=$_POST['varperiodo'];
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);
  $cuie=$_POST['cuie'];
  $efe_depto=$_POST['efe_depto'];

  $res_sql_edad=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'edad',$efe_depto);
  $res_sql_etnia=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'etnia',$efe_depto);
  $res_sql_educacion=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'educacion',$efe_depto);
  $res_sql_civil=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'civil',$efe_depto);

  $res_sql_familiar=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'familiar',$efe_depto);
  $res_sql_personal=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'personal',$efe_depto);

  $res_sql_gestasprevias=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'gestasprevias',$efe_depto);
  $res_sql_partosprevios=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'partosprevios',$efe_depto);
  $res_sql_cesareas=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'cesareas',$efe_depto);

  $res_sql_tabaco=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'tabaco',$efe_depto);
  $res_sql_antitetanica=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'antitetanica',$efe_depto);
  $res_sql_exa_mama=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'exa_mama',$efe_depto); 
  $res_sql_exa_cervi=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'exa_cervi',$efe_depto);
  $res_sql_bacteriuria=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'bacteriuria',$efe_depto);
  $res_sql_sifilis=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'sifilis',$efe_depto);
  $res_sql_anemia=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'anemia',$efe_depto);
  //$res_sql_glucemia=get_datos_global($varperiodo,$fecha_desde,$fecha_hasta,$cuie,'glucemia');

  $accion="Datos Obtenidos";

}


if ($_POST['traer_datos']=='Muestra'){
  
  $varperiodo=$_POST['varperiodo'];
  $fecha_desde=fecha_db($_POST['fecha_desde']);
  $fecha_hasta=fecha_db($_POST['fecha_hasta']);
  $cuie=$_POST['cuie'];
  $efe_depto=$_POST['efe_depto'];
  
  $filtros = array ();

  //nueva idea

  

  //conjunto de variables por check=>valor
  
  if ($_POST['chek_edad']) {$var=$_POST['edad'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcperinatal.var_0009::integer');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };

   if ($_POST['chek_etnia']) {$var=$_POST['etnia'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcperinatal.var_0011');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };


  if ($_POST['chek_niveleducativo']) {$var=$_POST['niveleducativo'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcperinatal.var_0013');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };

  if ($_POST['chek_estadocivil']) {$var=$_POST['estadocivil'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcperinatal.var_0015');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };


  if ($_POST['chek_antecedentesfamiliares']) /*{$var=$_POST['antecedentesfamiliares'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcantecedentes.var_0020');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };*/
                          {$st=$_POST['antecedentesfamiliares'];
                            if ($st=='todos' or $st==''){
                              $col_val=array ();
                              array_push($col_val,'sip_clap.hcantecedentes.var_0020');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0022');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0024');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0026');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0028');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0030');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);
                            }
                             else {
                            $st=explode('>',$st);
                            $col_val=array ();
                            array_push($col_val,$st[0]);
                            array_push($col_val,$st[1]);
                            array_push($filtros,$col_val);}
                          };

  if ($_POST['chek_antecedentespersonales']) /*{$var=$_POST['antecedentespersonales'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcantecedentes.var_0021');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };*/
                          {$st=$_POST['antecedentespersonales'];
                            if ($st=='todos' or $st==''){
                              $col_val=array ();
                              array_push($col_val,'sip_clap.hcantecedentes.var_0023');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0025');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0027');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0029');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0031');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0032');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0033');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0034');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcantecedentes.var_0035');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);
                            }
                             else {
                            $st=explode('>',$st);
                            $col_val=array ();
                            array_push($col_val,$st[0]);
                            array_push($col_val,$st[1]);
                            array_push($filtros,$col_val);}
                          };
  
  if ($_POST['chek_gestasprevias']) {
                        $var=($_POST['gestasprevias']<>'') ? '='.$_POST['gestasprevias'] : $_POST['gestasprevias'];
                        $col_val=array ();
                        array_push($col_val,'sip_clap.hcantecedentes.var_0040::integer');
                        array_push($col_val,$var);
                        array_push($filtros,$col_val);
                          };
  
  if ($_POST['chek_partosprevios']) {
                        $var=($_POST['partosprevios']<>'') ? '='.$_POST['partosprevios'] : $_POST['partosprevios'];
                        $col_val=array ();
                        array_push($col_val,'sip_clap.hcantecedentes.var_0046::integer');
                        array_push($col_val,$var);
                        array_push($filtros,$col_val);
                          };
  
  if ($_POST['chek_cesareas']) {
                        $var=($_POST['cesareas']<>'') ? '='.$_POST['cesareas'] : $_POST['cesareas'];
                        $col_val=array ();
                        array_push($col_val,'sip_clap.hcantecedentes.var_0047::integer');
                        array_push($col_val,$var);
                        array_push($filtros,$col_val);
                          };

  if ($_POST['chek_antecedentenacvivo']) {
                        $var=($_POST['antecedentenacvivo']<>'') ? '='.$_POST['antecedentenacvivo'] : $_POST['antecedentenacvivo'];
                        $col_val=array ();
                        array_push($col_val,'sip_clap.hcantecedentes.var_0043::integer');
                        array_push($col_val,$var);
                        array_push($filtros,$col_val);
                          };

  if ($_POST['chek_antecedenteultimonacido']) {$var=$_POST['antecedenteultimonacido'];
                            $st='antecedenteultimonacido'.'>'.'='."'".$var."'";
                            array_push($filtros,$st);};
  
  if ($_POST['chek_antecedentegemelares']) {$var=$_POST['antecedentegemelares'];
                        $col_val=array ();
                        array_push($col_val,'sip_clap.hcantecedentes.var_0039');
                        array_push($col_val,$var);
                        array_push($filtros,$col_val);
                          };
  
  if ($_POST['chek_interv_intergenesico']) {$var=$_POST['interv_intergenesico'];
                             $col_val=array ();
                            array_push($col_val,'sip_clap.hcantecedentes.var_0052');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };
  
  //vamos a tener que calular tambien con sip_clap.hcgestacion_actual.var_0060
  if ($_POST['chek_confia_edadgestacional']) {$st=$_POST['confia_edadgestacional'];
                            if ($st=='todos' or $st==''){
                              $col_val=array ();
                              array_push($col_val,'sip_clap.hcgestacion_actual.var_0059');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcgestacion_actual.var_0060');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);
                            }
                             else {
                            $st=explode('>',$st);
                            $col_val=array ();
                            array_push($col_val,$st[0]);
                            array_push($col_val,$st[1]);
                            array_push($filtros,$col_val);}
                          };
  
  if ($_POST['chek_tabacoalcholdrogas']) {$st=$_POST['tabacoalcholdrogas'];
                            if ($st=='todos' or $st==''){
                              $col_val=array ();
                              array_push($col_val,'sip_clap.hcespeciales.var_0061');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0062');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0063');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0064');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0066');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0067');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0068');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0069');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0071');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0072');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0073');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0074');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);
                            }
                             else {
                            $st=explode('>',$st);
                            $col_val=array ();
                            array_push($col_val,$st[0]);
                            array_push($col_val,$st[1]);
                            array_push($filtros,$col_val);}
                          };
  
  if ($_POST['chek_violencia']) {$st=$_POST['violencia'];
                            if ($st=='todos' or $st==''){
                              $col_val=array ();
                              array_push($col_val,'sip_clap.hcespeciales.var_0065');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0070');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcespeciales.var_0075');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);
                            }
                             else {
                            $st=explode('>',$st);
                            $col_val=array ();
                            array_push($col_val,$st[0]);
                            array_push($col_val,$st[1]);
                            array_push($filtros,$col_val);}
                          };

  if ($_POST['chek_antirubeola']) {$var=$_POST['antirubeola'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcgestacion_actual.var_0076');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };

  if ($_POST['chek_antitetanica']) {$var=$_POST['antitetanica'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcgestacion_actual.var_0077');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };
  
  if ($_POST['chek_examenodontol']) {$var=$_POST['examenodontol'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcgestacion_actual.var_0080');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };
  
  if ($_POST['chek_examenmamas']) {$var=$_POST['examenmamas'];
                             $col_val=array ();
                            array_push($col_val,'sip_clap.hcgestacion_actual.var_0081');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };
  
  if ($_POST['chek_examencervix']) {$st=$_POST['examencervix'];
                           if ($st=='todos' or $st==''){
                              $col_val=array ();
                              array_push($col_val,'sip_clap.hcgestacion_actual.var_0082');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcgestacion_actual.var_0083');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcgestacion_actual.var_0084');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);
                            }
                             else {
                            $st=explode('>',$st);
                            $col_val=array ();
                            array_push($col_val,$st[0]);
                            array_push($col_val,$st[1]);
                            array_push($filtros,$col_val);}
                          };

  
  if ($_POST['chek_gruporh']) {$var=$_POST['gruporh'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcgestacion_actual.var_0086');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };
  
  if ($_POST['chek_vih']) {$st=$_POST['vih'];
                            if ($st=='todos' or $st==''){
                              $col_val=array ();
                              array_push($col_val,'sip_clap.hcperinatal.var_0432');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcperinatal.var_0433');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcperinatal.var_0434');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcperinatal.var_0435');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcperinatal.var_0436');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);
                            }
                             else {
                            $st=explode('>',$st);
                            $col_val=array ();
                            array_push($col_val,$st[0]);
                            array_push($col_val,$st[1]);
                            array_push($filtros,$col_val);}
                          };

  if ($_POST['chek_chagas']) {$var=$_POST['chagas'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcgestacion_actual.var_0101');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };

  if ($_POST['chek_paludismo']) {$var=$_POST['paludismo'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcgestacion_actual.var_0102');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };
  
  if ($_POST['chek_bacteriuria']) {$st=$_POST['bacteriuria'];
                            if ($st=='todos' or $st==''){
                              $col_val=array ();
                              array_push($col_val,'sip_clap.hcgestacion_actual.var_0103');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcgestacion_actual.var_0104');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              }
                             else {
                            $st=explode('>',$st);
                            $col_val=array ();
                            array_push($col_val,$st[0]);
                            array_push($col_val,$st[1]);
                            array_push($filtros,$col_val);}
                          };
  
  if ($_POST['chek_glucemia']) {$var=$_POST['glucemia'];
                            $st='glucemia'.'>'.$var.'<';
                            array_push($filtros,$st);};
  
  if ($_POST['chek_strepto']) {$var=$_POST['strepto'];
                            $col_val=array ();
                            array_push($col_val,'sip_clap.hcgestacion_actual.var_0109');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };
  
  if ($_POST['chek_sifilis']) {$st=$_POST['sifilis'];
                           if ($st=='todos' or $st==''){
                              $col_val=array ();
                              array_push($col_val,'sip_clap.hcgestacion_actual.var_0112');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcgestacion_actual.var_0114');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              }
                             else {
                            $st=explode('>',$st);
                            $col_val=array ();
                            array_push($col_val,$st[0]);
                            array_push($col_val,$st[1]);
                            array_push($filtros,$col_val);}
                          };
  
  if ($_POST['chek_anemia']) {$var=$_POST['anemia'];
                             $col_val=array ();
                            array_push($col_val,'sip_clap.hcparto_aborto.var_0271');
                            array_push($col_val,$var);
                            array_push($filtros,$col_val);
                          };

  if ($_POST['chek_toxoplasmosis']) {$st=$_POST['toxoplasmosis'];
                           if ($st=='todos' or $st==''){
                              $col_val=array ();
                              array_push($col_val,'sip_clap.hcgestacion_actual.var_0088');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcgestacion_actual.var_0089');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              $col_val=array ();  
                              array_push($col_val,'sip_clap.hcgestacion_actual.var_0090');
                              array_push($col_val,$st);
                              array_push($filtros,$col_val);

                              }
                             else {
                            $st=explode('>',$st);
                            $col_val=array ();
                            array_push($col_val,$st[0]);
                            array_push($col_val,$st[1]);
                            array_push($filtros,$col_val);}
                          };

  
 
  
  $res_sql=get_datos($varperiodo,$fecha_desde,$fecha_hasta,$cuie,$filtros,$efe_depto);
  $accion="Datos Obtenidos";
  
};

echo $html_header;
?>
<div class="container">
  <form name='form1' action='sipweb_fact.php' method='POST' autocomplete="off">
  <input type="hidden" name="cuie" id="cuie" value='<?$cuie?>'> 
  <input type="hidden" name="efe_depto" id="efe_depto" value='<?$efe_depto?>'>

  <div class="row" style="border:0.5px solid; border-color:#F6F5F5">
    <div class="col-md-12">
      <h3 align="center"><b><font color="blue">Sistema de Gestion de SipWeb</font></b>
      <font size="1.5px" color="black">Ver.1.0</font>
      </h3>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-4">
      <label>Referencia del Periodo</label>
    </div>
    <div class="col-md-4">
      <label>Fecha Desde</label>
    </div>
    <div class="col-md-4">
      <label>Fecha Hasta</label>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      <select name="varperiodo" id="varperiodo">
        <option value="-1" disabled selected>Seleccione la Referencia del Periodo</option>
        <option value="sip_clap.hcrecien_nacido.var_0425">Egreso RN - fecha</option>
        <option value="sip_clap.hcrecien_nacido.var_0379">Fecha de Egreso Materno</option>
        <option value="sip_clap.hcparto_aborto.var_0183">Fecha de Ingreso</option>
        <option value="sip_clap.hcparto_aborto.var_0284">Fecha de Nacimiento</option>
        <option value="sip_clap.hcperinatal.var_0006">Fecha Nacimiento Madre</option>
        <option value="sip_clap.hcperinatal.fecha_hora_carga">Fecha de Registro</option>
        <option value="sip_clap.hcantecedentes.var_0051">Fecha Embarazo Anterior</option>
        <option value="sip_clap.hcgestacion_actual.var_0058">Fecha Probable de Parto</option>
        <option value="sip_clap.hcparto_aborto.var_0192">Fecha de Ruptura de Membranas</option>
        <option value="sip_clap.hcgestacion_actual.var_0057">Fecha Ultima Mestruacion</option>
      </select>
    </div>

    <div class="col-md-4">
    <input type="text" id="fecha_desde" name="fecha_desde" class="datetimepicker10" placeholder="Ingrese la fecha desde">
    </div>

    <div class="col-md-4">
    <input type="text" id="fecha_hasta" name="fecha_hasta" class="datetimepicker10" placeholder="Ingrese la fecha hasta">
    </div>
  </div>
</div> 

<? 
$html_option='<option data-divider="true"></option>
              <option value="todos">Todos</option>
              <option data-divider="true"></option>';
if (!es_cuie($login))  {?>
<div class="container">
  <div class="col-md-6">
    <label>Efector: </label>
    <select name="cuie" id="cuie" class="selectpicker" data-show-subtext="true" data-live-search="true">
    <option value="Seleccione" disabled selected>Seleccione el Efector</option>
    <?if (!$cuie){
            if (!$user_efector) echo $html_option;}
       else {
          if ($cuie=="todos"){
            if (!$user_efector) echo $html_option;}
          else {
            if (!$user_efector) echo $html_option;
        $sql= "SELECT * from nacer.efe_conv where cuie='$cuie'";
        $res_efectores=sql($sql) or fin_pagina();
        $id_efector=$res_efectores->fields['id_efe_conv'];
        $nombre_efector=$res_efectores->fields['nombre'];
        $cuie=$res_efectores->fields['cuie'];?>
        <option value="<?=$cuie?>"><?=$res_efectores->fields['cuie']." - ".$nombre_efector?></option>
        <?};
      };             
    if ($user_efector==1) $sql="SELECT * from nacer.efe_conv where conv_sumar=true and cuie in $user_cuie order by nombre";
    else $sql= "SELECT * from nacer.efe_conv where conv_sumar=true order by nombre";
    $res_efectores=sql($sql) or fin_pagina();
    while (!$res_efectores->EOF){ 
      $id_efector=$res_efectores->fields['id_efe_conv'];
      $nombre_efector=$res_efectores->fields['nombre'];
      $cuie=$res_efectores->fields['cuie'];
      ?>
      <option value="<?=$cuie?>"><?=$res_efectores->fields['cuie']." - ".$nombre_efector?></option>
      <?
      $res_efectores->movenext();
      };?>
    </select>
  </div>

    <div class="col-md-6">
      <?$disabled_efe=($user_efector) ? 'disabled' : '';?>
    <label>Efector x Departam.: </label>
    <select name="efe_depto" id="efe_depto" class="selectpicker" data-show-subtext="true" data-live-search="true" <?=$disabled_efe?>>
    <option value="Seleccione" disabled selected>Seleccione Departamento</option>
    <?if (!$efe_depto){?>
        
        <option data-divider="true"></option>
        <option value="todos">Todos</option>
        <option data-divider="true"></option>
     <?}
     else {
        if ($efe_depto=="todos"){?>
            <option data-divider="true"></option>
            <option value="todos">Todos</option>
            <option data-divider="true"></option>
            <?}
        else {?>
        <option data-divider="true"></option>
        <option value="todos">Todos</option>
        <option data-divider="true"></option>
        <?$sql= "SELECT * from nacer.efe_conv 
                inner join uad.departamentos on (efe_conv.departamento=id_codigo_depto::text)
                where efe_conv.departamento='$efe_depto'";
        $res_efectores_depto=sql($sql) or fin_pagina();
        $efe_depto=$res_efectores_depto->fields['id_depto'];?>
        <option value="<?=$efe_depto?>"><?=$res_efectores_depto->fields['id_depto'].' - '.$res_efectores_depto->fields['departamento']?></option>
        <?};
      };             
    $sql= "SELECT distinct (departamentos.nombre) as departamento,efe_conv.departamento as id_depto
            from nacer.efe_conv 
            inner join uad.departamentos on (efe_conv.departamento=id_codigo_depto::text)
            where conv_sumar=true order by 1";
    $res_efectores_depto=sql($sql) or fin_pagina();
    while (!$res_efectores_depto->EOF){
      $efe_depto=$res_efectores_depto->fields['id_depto'];?>
      <option value="<?=$efe_depto?>"><?=$res_efectores_depto->fields['id_depto'].' - '.$res_efectores_depto->fields['departamento']?></option>
      <?
      $res_efectores_depto->movenext();
      };?>
    </select>
  </div>

</div><!--del container de Efectores-->
<?}?>

<div class="container">
  <div class="row" style="border:0.5px solid; border-color:#F6F5F5">
    <div class="col-md-12">
      <font size="3.5px" color="blue"><b>Seleccion de Variables</b></font>
    </div>
</div>

<div>&nbsp</div>

  <div class="container" style="width:95%">
    <div class="row" style="border:0.5px solid; border-color:#F6F5F5">
      <div class="col-md-4">
        <font size="3.5px" color="green"><b>Identificacion</b></font>
        <hr style="color: '#056b2'; border:0; height: 2px; text-align: center; background-image: linear-gradient (left, #fff, #000, #fff);"/>
      </div>
    </div>
    
    <div>&nbsp</div>
    <div class="row">
      <div class="col-md-3">
        <div class="checkbox-inline">
          <label>
          <input type="checkbox" id="chek_edad" name="chek_edad" style="width:27px; top:0;left:0;right:0;bottom:0;"/>Edad</label>
          <select id="edad" name="edad">
            <option disabled selected value='todos'>Todos</option>
            <option value='menor15'><15 Años</option>
            <option value='menor20'><20 Años</option>
            <option value='between 20 and 35'>Entre 20-35 Años</option>
            <option value='mayor35'>>35 Años</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="checkbox-inline">
          <label>
          <input type="checkbox" id="chek_etnia" name="chek_etnia" style="width:27px; top:0;left:0;right:0;bottom:0;"/>Etnia</label>
          <select id="etnia" name="etnia">
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeAapostrofe'>Blanca</option>
            <option value='=apostrofeBapostrofe'>Indígena</option>
            <option value='=apostrofeCapostrofe'>Mestiza</option>
            <option value='=apostrofeDapostrofe'>Negra</option>
            <option value='=apostrofeEapostrofe'>Otra</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="checkbox-inline">
          <label>
          <input type="checkbox" id="chek_niveleducativo" name="chek_niveleducativo" style="width:27px; top:0;left:0;right:0;bottom:0;"/>Nivel Educativo</label>
          <select id="niveleducativo" name="niveleducativo">
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeAapostrofe'>Ninguno</option>
            <option value='=apostrofeBapostrofe'>Prim.</option>
            <option value='=apostrofeCapostrofe'>Secund.</option>
            <option value='=apostrofeDapostrofe'>Univer.</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="checkbox-inline">
          <label>
          <input type="checkbox" id="chek_estadocivil" name="chek_estadocivil" style="width:27px; top:0;left:0;right:0;bottom:0;"/>Estado Civil</label>
          <select id="estadocivil" name="estadocivil">
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeAapostrofe'>Casada</option>
            <option value='=apostrofeBapostrofe'>Union Estable</option>
            <option value='=apostrofeCapostrofe'>Soltera</option>
            <option value='=apostrofeDapostrofe'>Otro</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <div>&nbsp</div>
 
  <div class="container" style="width:95%">
    <div class="row" style="border:0.5px solid; border-color:#F6F5F5">
      <div class="col-md-4">
        <font size="3.5px" color="green"><b>Antecendentes Familiares y Personales</b></font>
      </div>
    </div>
    <div>&nbsp</div>
    <div class="row">
      <div class="col-md-6">
        <div class="checkbox-inline">
          <label>
          <input type="checkbox" id="chek_antecedentesfamiliares" name="chek_antecedentesfamiliares" style="width:27px; top:0;left:0;right:0;bottom:0;"/>Antecedentes Familiares</label>
          <select id="antecedentesfamiliares" name="antecedentesfamiliares">
            <option disabled selected value='todos'>Todos</option>
            <option style='background-color:#F6F9C5' value="sip_clap.hcantecedentes.var_0020>=apostrofeAapostrofe">TBC (Familiares) - NO</option>
            <option style='background-color:#F6F9C5' value="sip_clap.hcantecedentes.var_0020>=apostrofeBapostrofe">TBC (Familiares) - SI</option>
            <option style='background-color:#C5D4F9' value="sip_clap.hcantecedentes.var_0022>=apostrofeAapostrofe">Diabetes (Familiares) - NO</option>
            <option style='background-color:#C5D4F9' value="sip_clap.hcantecedentes.var_0022>=apostrofeBapostrofe">Diabetes (Familiares) - SI</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcantecedentes.var_0024>=apostrofeAapostrofe">Hipertension (Familiares) - NO</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcantecedentes.var_0024>=apostrofeBapostrofe">Hipertension (Familiares) - SI</option>
            <option style='background-color:#F6F9C5' value="sip_clap.hcantecedentes.var_0026>=apostrofeAapostrofe">Antecedente Preclampsia (Familiares) - NO</option>
            <option style='background-color:#F6F9C5' value="sip_clap.hcantecedentes.var_0026>=apostrofeBapostrofe">Antecedente Preclampsia (Familiares) - SI</option>
            <option style='background-color:#C5D4F9' value="sip_clap.hcantecedentes.var_0028>=apostrofeAapostrofe">Eclampsia (Familiares) - NO</option>
            <option style='background-color:#C5D4F9' value="sip_clap.hcantecedentes.var_0028>=apostrofeBapostrofe">Eclampsia (Familiares) - SI</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcantecedentes.var_0030>=apostrofeAapostrofe">Otros Antecedentes (Familiares) - NO</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcantecedentes.var_0030>=apostrofeBapostrofe">Otros Antecedentes (Familiares) - SI</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="checkbox-inline">
          <label>
          <input type="checkbox" id="chek_antecedentespersonales" name="chek_antecedentespersonales" style="width:27px; top:0;left:0;right:0;bottom:0;"/>Antecedentes Personales</label>
          <select id="antecedentespersonales" name="antecedentespersonales">
            <option disabled selected value='todos'>Todos</option>
            <option style='background-color:#F6F9C5' value="sip_clap.hcantecedentes.var_0021>=apostrofeAapostrofe">TBC (Personales) - NO</option>
            <option style='background-color:#F6F9C5' value="sip_clap.hcantecedentes.var_0021>=apostrofeBapostrofe">TBC (Personales) - SI</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcantecedentes.var_0023>=apostrofeAapostrofe">Diabetes (Personales) - NO</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcantecedentes.var_0023>=apostrofeBapostrofe">Diabetes (Personales) - Tipo I</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcantecedentes.var_0023>=apostrofeCapostrofe">Diabetes (Personales) - Tipo II</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcantecedentes.var_0023>=apostrofeDapostrofe">Diabetes (Personales) - Tipo G</option>
            <option style='background-color:#F6F9C5' value="sip_clap.hcantecedentes.var_0025>=apostrofeAapostrofe">Hipertension (Personales) - NO</option>
            <option style='background-color:#F6F9C5' value="sip_clap.hcantecedentes.var_0025>=apostrofeBapostrofe">Hipertension (Personales) - SI</option>
            <option style='background-color:#C5D4F9' value="sip_clap.hcantecedentes.var_0027>=apostrofeAapostrofe">Antecedente Preclampsia (Personales) - NO</option>
            <option style='background-color:#C5D4F9' value="sip_clap.hcantecedentes.var_0027>=apostrofeBapostrofe">Antecedente Preclampsia (Personales) - SI</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcantecedentes.var_0029>=apostrofeAapostrofe">Eclampsia (Personales) - NO</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcantecedentes.var_0029>=apostrofeBapostrofe">Eclampsia (Personales) - SI</option>
            <option style='background-color:#F6F9C5' value="sip_clap.hcantecedentes.var_0031>=apostrofeAapostrofe">Otros Antecedentes (Personales) - NO</option>
            <option style='background-color:#F6F9C5' value="sip_clap.hcantecedentes.var_0031>=apostrofeBapostrofe">Otros Antecedentes (Personales) - SI</option>
            <option style='background-color:#C5D4F9' value="sip_clap.hcantecedentes.var_0032>=apostrofeAapostrofe">Cirugia (Personales) - NO</option>
            <option style='background-color:#C5D4F9' value="sip_clap.hcantecedentes.var_0032>=apostrofeBapostrofe">Cirugia (Personales) - SI</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcantecedentes.var_0033>=apostrofeAapostrofe">Infertilidad (Personales) - NO</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcantecedentes.var_0033>=apostrofeBapostrofe">Infertilidad (Personales) - SI</option>
            <option style='background-color:#F6F9C5' value="sip_clap.hcantecedentes.var_0034>=apostrofeAapostrofe">Antecedentes Cardiopatia - NO</option>
            <option style='background-color:#F6F9C5' value="sip_clap.hcantecedentes.var_0034>=apostrofeBapostrofe">Antecedentes Cardiopatia - SI</option>
            <option style='background-color:#C5D4F9' value="sip_clap.hcantecedentes.var_0035>=apostrofeAapostrofe">Antecedentes Nefropatia - NO</option>
            <option style='background-color:#C5D4F9' value="sip_clap.hcantecedentes.var_0035>=apostrofeBapostrofe">Antecedentes Nefropatia - SI</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <div>&nbsp</div>
  
  <div class="container" style="width:95%">
    <div class="row" style="border:0.5px solid; border-color:#F6F5F5">
      <div class="col-md-4">
        <font size="3.5px" color="green"><b>Antecedentes Obstetricos</b></font>
      </div>
    </div>
    <div>&nbsp</div>
    
    <div class="row" id="titulo">
      <div class="col-md-3"><label>Gestas Previas</label></div>
      <div class="col-md-3"><label>Partos Previos</label></div>
      <div class="col-md-3"><label>Cesareas</label></div>
      <div class="col-md-3"><label></label></div>
    </div>
    
    <div class="row" id="datos">
      <div class="col-md-3">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_gestasprevias" name="chek_gestasprevias" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <input type="text" id="gestasprevias" name="gestasprevias" placeholder="Cantidad" style="width:85px">
        </div>
      </div>
      
      <div class="col-md-3">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_partosprevios" name="chek_partosprevios" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <input type="text" id="partosprevios" name="partosprevios" placeholder="Cantidad" style="width:85px">
        </div>
      </div>
      <div class="col-md-3">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_cesareas" name="chek_cesareas" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <input type="text" id="cesareas" name="cesareas" placeholder="Cantidad" style="width:85px">
        </div>
      </div>
    </div>
    
    <div>&nbsp</div>
    
    <div class="row" id="titulo">
      <div class="col-md-3"><label>Antecedente de nacido Vivo c/murte 1° semana</label></div>
      <div class="col-md-3"><label>Antecedente ultimo recien nacido previo</label></div>
      <div class="col-md-3"><label>Frecuencia de Madres c/antecedente de gemelares</label></div>
      <div class="col-md-3"><label>Intervalo Intergenesico</label></div>
    </div>
    
    <div class="row" id="datos">
      <div class="col-md-3">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_antecedentenacvivo" name="chek_antecedentenacvivo" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <input type="text" id="antecedentenacvivo" name="antecedentenacvivo" placeholder="Cantidad" style="width:85px">
        </div>
      </div>
      <div class="col-md-3">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_antecedenteultimonacido" name="chek_antecedenteultimonacido" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <input type="text" id="antecedenteultimonacido" name="antecedenteultimonacido" placeholder="Cantidad" style="width:85px">
        </div>
      </div>
      <div class="col-md-3">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_antecedentegemelares" name="chek_antecedentegemelares" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="antecedentegemelares" name="antecedentegemelares">
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeBapostrofe'>SI</option>
            <option value='=apostrofeAapostrofe'>NO</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_interv_intergenesico" name="chek_interv_intergenesico" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <input type="text" id="interv_intergenesico" name="interv_intergenesico" placeholder="Cantidad" style="width:85px">
          <select id="interv_intergenesico" name="interv_intergenesico">
            <option disabled selected value='todos'>Todos</option>
            <option value='B'>SI</option>
            <option value='A'>NO</option>
          </select>
        </div>
      </div>
    </div>
    
    
  </div>
  
  <div>&nbsp</div>

  <div class="container" style="width:95%">
    <div class="row" style="border:0.5px solid; border-color:#F6F5F5">
      <div class="col-md-4">
        <font size="3.5px" color="green"><b>Gestacion Actual</b></font>
      </div>
    </div>
    
    <div>&nbsp</div>
    
    <div class="row" id="titulo">
      <div class="col-md-6" style="align-items: left"><label>Confiabilidad de edad gestacional</label></div>
      <div class="col-md-6"><label>Tabaco, Alcohol o Drogas</label></div>
    </div>
    
    <div class="row" id="datos">
      <div class="col-md-6">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_confia_edadgestacional" name="chek_confia_edadgestacional" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="confia_edadgestacional" name="confia_edadgestacional">
            <option disabled selected value='todos'>Todos</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcgestacion_actual.var_0059>=apostrofeAapostrofe">EG Confiable por FUM - NO</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcgestacion_actual.var_0059>=apostrofeBapostrofe">EG Confiable por FUM - SI</option>
            <option style='background-color:#C5D4F9' value="sip_clap.hcgestacion_actual.var_0060>=apostrofeAapostrofe">EG Confiable por ECO men.20 Sem.- NO</option>
            <option style='background-color:#C5D4F9' value="sip_clap.hcgestacion_actual.var_0060>=apostrofeBapostrofe">EG Confiable por ECO men.20 Sem. - SI</option>
          </select>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="checkbox-inline">
          <label>
          <input type="checkbox" id="chek_tabacoalcholdrogas" name="chek_tabacoalcholdrogas" style="width:27px; top:0;left:0;right:0;bottom:0;"/></label>
          <select id="tabacoalcholdrogas" name="tabacoalcholdrogas">
            <option disabled selected value='seleccione'>Seleccione</option>
            <option style='background-color:#C5F9D4' value="sip_clap.hcespeciales.var_0061>=apostrofeBapostrofe">Fumadora Activa 1er. - SI</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0061>=apostrofeAapostrofe'>Fumadora Activa 1er. - NO</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0062>=apostrofeBapostrofe'>Fumadora Pasiva 1er. - SI</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0062>=apostrofeAapostrofe'>Fumadora Pasiva 1er. - NO</option>
            <option style='background-color:#C5D4F9' value='sip_clap.hcespeciales.var_0063>=apostrofeBapostrofe'>Drogas 1er. - SI</option>
            <option style='background-color:#C5D4F9' value='sip_clap.hcespeciales.var_0063>=apostrofeAapostrofe'>Drogas 1er. - NO</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0064>=apostrofeBapostrofe'>Alcohol 1er. - SI</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0064>=apostrofeAapostrofe'>Alcohol 1er. - NO</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0066>=apostrofeBapostrofe'>Fumadora Activa 2do. - SI</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0066>=apostrofeAapostrofe'>Fumadora Activa 2do. - NO</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0067>=apostrofeBapostrofe'>Fumadora Pasiva 2do. - SI</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0067>=apostrofeAapostrofe'>Fumadora Pasiva 2do. - NO</option>
            <option style='background-color:#C5D4F9' value='sip_clap.hcespeciales.var_0068>=apostrofeBapostrofe'>Drogas 2do. - SI</option>
            <option style='background-color:#C5D4F9' value='sip_clap.hcespeciales.var_0068>=apostrofeAapostrofe'>Drogas 2do. - NO</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0069>=apostrofeBapostrofe'>Alcohol 2do. - SI</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0069>=apostrofeAapostrofe'>Alcohol 2do. - NO</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0071>=apostrofeBapostrofe'>Fumadora Activa 3er. - SI</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0071>=apostrofeAapostrofe'>Fumadora Activa 3er. - NO</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0072>=apostrofeBapostrofe'>Fumadora Pasiva 3er. - SI</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0072>=apostrofeAapostrofe'>Fumadora Pasiva 3er. - NO</option>
            <option style='background-color:#C5D4F9' value='sip_clap.hcespeciales.var_0073>=apostrofeBapostrofe'>Drogas 3er. - SI</option>
            <option style='background-color:#C5D4F9' value='sip_clap.hcespeciales.var_0073>=apostrofeAapostrofe'>Drogas 3er. - NO</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0074>=apostrofeBapostrofe'>Alcohol 3er. - SI</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0074>=apostrofeAapostrofe'>Alcohol 3er. - NO</option>
            
          </select>
        </div>
      </div>
    </div>
    
    <div>&nbsp</div>
    
    <div class="row" id="titulo">
      <div class="col-md-6" ><label>Violencia</label></div>
      <div class="col-md-6"><label>Vacuna Antirubeola</label></div>
    </div>
    
    <div class="row" id="datos">
      <div class="col-md-6">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_violencia" name="chek_violencia" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="violencia" name="violencia">
            <option disabled selected value='todos'>Seleccione</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0065>=apostrofeBapostrofe'>Violencia 1er. - SI</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0065>=apostrofeAapostrofe'>Violencia 1er. - NO</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0070>=apostrofeBapostrofe'>Violencia 2do. - SI</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0070>=apostrofeAapostrofe'>Violencia 2do. - NO</option>
            <option style='background-color:#C5D4F9' value='sip_clap.hcespeciales.var_0075>=apostrofeBapostrofe'>Violencia 3er. - SI</option>
            <option style='background-color:#C5D4F9' value='sip_clap.hcespeciales.var_0075>=apostrofeAapostrofe'>Violencia 3er. - NO</option>
          </select>
        </div>
      </div>  
    
      <div class="col-md-6">
        <div class="checkbox-inline">
         <input type="checkbox" id="chek_antirubeola" name="chek_antirubeola" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="antirubeola" name="antirubeola">
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeAapostrofe'>Previa</option>
            <option value='=apostrofeBapostrofe'>No Sabe</option>
            <option value='=apostrofeCapostrofe'>Embarazo</option>
            <option value='=apostrofeDapostrofe'>NO</option>
          </select>
        </div>
      </div>
    </div>
    
    <div>&nbsp</div>

    <div class="row" id="titulo">
      <div class="col-md-6"><label>Vacuna Antitetanica</label></div>
      <div class="col-md-6"><label>Examen odontolog.</label></div>
    </div>
    
    <div class="row" id="datos">
      <div class="col-md-6">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_antitetanica" name="chek_antitetanica" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="antitetanica" name="antitetanica">
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeBapostrofe'>SI</option>
            <option value='=apostrofeAapostrofe'>NO</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_examenodontol" name="chek_examenodontol" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="examenodontol" name="examenodontol">
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeBapostrofe'>SI</option>
            <option value='=apostrofeAapostrofe'>NO</option>
          </select>
        </div>
      </div>
    </div>

  <div>&nbsp</div>

  <div class="row" id="titulo">
    <div class="col-md-6"><label>Examen de Mamas</label></div>
    <div class="col-md-6"><label>Examen cervix uterino</label></div>
  </div>
    
    <div class="row" id="datos">
      <div class="col-md-6">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_examenmamas" name="chek_examenmamas" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="examenmamas" name="examenmamas">
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeBapostrofe'>SI</option>
            <option value='=apostrofeAapostrofe'>NO</option>
          </select>
        </div>
      </div>

      <div class="col-md-6">
        <div class="checkbox-inline">
        <input type="checkbox" id="chek_examencervix" name="chek_examencervix" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
        <select id="examencervix" name="examencervix">
          <option disabled selected value='todos'>Todos</option>
          <option style='background-color:#C5F9D4' value='sip_clap.hcgestacion_actual.var_0082>=apostrofeAapostrofe'>Cervix Insp. Visual - NORMAL</option>
          <option style='background-color:#C5F9D4' value='sip_clap.hcgestacion_actual.var_0082>=apostrofeBapostrofe'>Cervix Insp. Visual - ANORMAL</option>
          <option style='background-color:#C5F9D4' value='sip_clap.hcgestacion_actual.var_0082>=apostrofeCapostrofe'>Cervix Insp. Visual - NO SE HIZO</option>
          <option style='background-color:#F6F9C5' value='sip_clap.hcgestacion_actual.var_0083>=apostrofeAapostrofe'>Cervix PAP - NORMAL</option>
          <option style='background-color:#F6F9C5' value='sip_clap.hcgestacion_actual.var_0083>=apostrofeBapostrofe'>Cervix PAP - ANORMAL</option>
          <option style='background-color:#F6F9C5' value='sip_clap.hcgestacion_actual.var_0083>=apostrofeCapostrofe'>Cervix PAP - NO SE HIZO</option>
          <option style='background-color:#C5D4F9' value='sip_clap.hcgestacion_actual.var_0084>=apostrofeAapostrofe'>Cervix COLP - NORMAL</option>
          <option style='background-color:#C5D4F9' value='sip_clap.hcgestacion_actual.var_0084>=apostrofeBapostrofe'>Cervix COLP - ANORMAL</option>
          <option style='background-color:#C5D4F9' value='sip_clap.hcgestacion_actual.var_0084>=apostrofeCapostrofe'>Cervix COLP - NO SE HIZO</option>
        </select>
        </div>
      </div>
  </div>
    
  <div>&nbsp</div>
    
  <div class="row" id="titulo">
    <div class="col-md-6"><label>Grupo y RH</label></div>
    <div class="col-md-6"><label>VIH</label></div>
  </div>
  
  <div class="row" id="datos">
     <div class="col-md-6">
       <div class="checkbox-inline">
       <input type="checkbox" id="chek_gruporh" name="chek_gruporh" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
       <select id="gruporh" name="gruporh">
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeAapostrofe'>POSITIVO</option>
            <option value='=apostrofeBapostrofe'>NEGATIVO</option>
        </select>
      </div>
    </div>


      <div class="col-md-6">
        <div class="checkbox-inline">
         <input type="checkbox" id="chek_vih" name="chek_vih" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="vih" name="vih">
            <option disabled selected value='todos'>Todos</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcperinatal.var_0432>=apostrofeAapostrofe'>VIH + (Personales) - NO</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcperinatal.var_0432>=apostrofeBapostrofe'>VIH + (Personales) - SI</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0433>=apostrofeAapostrofe'>Tam. Antenat. prueba VIH Menor 20 Sem. - Positivo</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0433>=apostrofeBapostrofe'>Tam. Antenat. prueba VIH Menor 20 Sem. - Negativo</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0433>=apostrofeCapostrofe'>Tam. Antenat. prueba VIH Menor 20 Sem. - No se Hizo</option>
            <option style='background-color:#C5D4F9' value='sip_clap.hcespeciales.var_0434>=apostrofeAapostrofe'>Tam. Antenat. TARV VIH Menor 20 Sem. - NO</option>
            <option style='background-color:#C5D4F9' value='sip_clap.hcespeciales.var_0434>=apostrofeBapostrofe'>Tam. Antenat. TARV VIH Menor 20 Sem. - SI</option>
            <option style='background-color:#C5D4F9' value='sip_clap.hcespeciales.var_0434>=apostrofeCapostrofe'>Tam. Antenat. TARV VIH Menor 20 Sem. - No se Hizo</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0435>=apostrofeAapostrofe'>Tam. Antenat. prueba VIH Mayor Igual 20 Sem. - Positivo</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0435>=apostrofeBapostrofe'>Tam. Antenat. prueba VIH Mayor Igual 20 Sem. - Negativo</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcespeciales.var_0435>=apostrofeCapostrofe'>Tam. Antenat. prueba VIH Mayor Igual 20 Sem. - No se Hizo</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0436>=apostrofeAapostrofe'>Tam. Antenat. TARV VIH Mayor Igual 20 Sem. - NO</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0436>=apostrofeBapostrofe'>Tam. Antenat. TARV VIH Mayor Igual 20 Sem. - SI</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcespeciales.var_0436>=apostrofeCapostrofe'>Tam. Antenat. TARV VIH Mayor Igual 20 Sem. - No se Hizo</option>
          </select>
        </div>
      </div>
   </div>

  <div>&nbsp</div>

  <div class="row" id="titulo">
    <div class="col-md-6"><label>Chagas</label></div>
    <div class="col-md-6"><label>Hepatitis B</label></div>
  </div>

  <div class="row" id="datos">
    <div class="col-md-6">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_chagas" name="chek_chagas" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="chagas" name="chagas">
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeBapostrofe'>POSITIVO</option>
            <option value='=apostrofeAapostrofe'>NEGATIVO</option>
            <option value='=apostrofeCapostrofe'>No Se Hizo</option>
          </select>
        </div>
      </div>

      <div class="col-md-6">
        <div class="checkbox-inline">
         <input type="checkbox"  id="chek_paludismo" name="chek_paludismo" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="paludismo" name="paludismo"><!--es hepatitis pero no cambiar los id ni names-->
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeBapostrofe'>SI</option>
            <option value='=apostrofeAapostrofe'>NO</option>
          </select>
        </div>
      </div>
  </div>

  <div>&nbsp</div>

  <div class="row" id="titulo">
      <div class="col-md-6"><label>Bacteriuria</label></div>
      <div class="col-md-6"><label>Glucemia</label></div>
  </div>


    <div class="row" id="datos">
      <div class="col-md-6">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_bacteriuria" name="chek_bacteriuria" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="bacteriuria" name="bacteriuria">
            <option disabled selected value='todos'>Todos</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcgestacion_actual.var_0103>=apostrofeAapostrofe'>Bacteriuria Menor 20 Sem. - Normal</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcgestacion_actual.var_0103>=apostrofeBapostrofe'>Bacteriuria Menor 20 Sem. - Anormal</option>
            <option style='background-color:#F6F9C5' value='sip_clap.hcgestacion_actual.var_0103>=apostrofeCapostrofe'>Bacteriuria Menor 20 Sem. - No se hizo</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcgestacion_actual.var_0104>=apostrofeAapostrofe'>Bacteriuria May.Igual 20 Sem. - Normal</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcgestacion_actual.var_0104>=apostrofeBapostrofe'>Bacteriuria May.Igual 20 Sem. - Anormal</option>
            <option style='background-color:#C5F9D4' value='sip_clap.hcgestacion_actual.var_0104>=apostrofeCapostrofe'>Bacteriuria May.Igual 20 Sem. - No se hizo</option>            
          </select>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_glucemia" name="chek_glucemia" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <input type="text" id="glucemia" name="glucemia" placeholder="C/gramos" style="width:85px">
        </div>
      </div>
      
    </div>
    
    <div>&nbsp</div>
    
    <div class="row" id="titulo">
      <div class="col-md-6"><label>Estrepto grupo B</label></div>
      <div class="col-md-6"><label>Diag.de sifilis y tratam.</label></div>
    </div>
    
    <div class="row" id="datos">
      <div class="col-md-6">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_strepto" name="chek_strepto" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="strepto" name="strepto">
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeBapostrofe'>POSITIVO</option>
            <option value='=apostrofeAapostrofe'>NEGATIVO</option>
            <option value='=apostrofeCapostrofe'>NO SE HIZO</option>
          </select>
        </div>
      </div>    
  

      <div class="col-md-6">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_sifilis" name="chek_sifilis" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
        <select id="sifilis" name="sifilis">
        <option disabled selected value='todos'>Seleccione</option>
        <option style='background-color:#F6F9C5' value='sip_clap.hcgestacion_actual.var_0112>=apostrofeAapostrofe'>Prueba Sifilis No Treponemica menor 20 Sem. - Negativo</option>
        <option style='background-color:#F6F9C5' value='sip_clap.hcgestacion_actual.var_0112>=apostrofeBapostrofe'>Prueba Sifilis No Treponemica menor 20 Sem. - Positivo</option>
        <option style='background-color:#F6F9C5' value='sip_clap.hcgestacion_actual.var_0112>=apostrofeCapostrofe'>Prueba Sifilis No Treponemica menor 20 Sem. - Se desconoce</option>
        <option style='background-color:#C5F9D4' value='sip_clap.hcgestacion_actual.var_0114>=apostrofeAapostrofe'>Prueba Sifilis No Treponemica mayor Igual 20 Sem. - Negativo</option>
        <option style='background-color:#C5F9D4' value='sip_clap.hcgestacion_actual.var_0114>=apostrofeBapostrofe'>Prueba Sifilis No Treponemica mayor Igual 20 Sem. - Positivo</option>
        <option style='background-color:#C5F9D4' value='sip_clap.hcgestacion_actual.var_0114>=apostrofeCapostrofe'>Prueba Sifilis No Treponemica mayor Igual 20 Sem. - Se desconoce</option>
        </select>
        </div>
      </div>
    </div>

  <div>&nbsp</div>
    
  <div class="row" id="titulo">
    <div class="col-md-6"><label>Anemia</label></div>
    <div class="col-md-6"><label>Toxoplasmosis</label></div>
  </div>

   <div class="row" id="datos">
    <div class="col-md-6">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_anemia" name="chek_anemia" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
          <select id="anemia" name="anemia">
            <option disabled selected value='todos'>Todos</option>
            <option value='=apostrofeBapostrofe'>SI</option>
            <option value='=apostrofeAapostrofe'>NO</option>
          </select>
        </div>
      </div>
    <div class="col-md-6">
        <div class="checkbox-inline">
          <input type="checkbox" id="chek_toxoplasmosis" name="chek_toxoplasmosis" style="width:27px; top:0;left:0;right:0;bottom:0;"/>
        <select id="toxoplasmosis" name="toxoplasmosis">
        <option disabled selected value='todos'>Seleccione</option>
        <option style='background-color:#F6F9C5' value='sip_clap.hcgestacion_actual.var_0088>=apostrofeAapostrofe'>Tamiz.Anten.-Toxoplasmosis menor 20 Sem. - Negativo</option>
        <option style='background-color:#F6F9C5' value='sip_clap.hcgestacion_actual.var_0088>=apostrofeBapostrofe'>Tamiz.Anten.-Toxoplasmosis menor 20 Sem. - Positivo</option>
        <option style='background-color:#F6F9C5' value='sip_clap.hcgestacion_actual.var_0088>=apostrofeCapostrofe'>Tamiz.Anten.-Toxoplasmosis menor 20 Sem. - No Se Hizo</option>
        <option style='background-color:#C5F9D4' value='sip_clap.hcgestacion_actual.var_0089>=apostrofeAapostrofe'>Tamiz.Anten.-Toxoplasmosis mayor Igual 20 Sem. - Negativo</option>
        <option style='background-color:#C5F9D4' value='sip_clap.hcgestacion_actual.var_0089>=apostrofeBapostrofe'>Tamiz.Anten.-Toxoplasmosis mayor Igual 20 Sem. - Positivo</option>
        <option style='background-color:#C5F9D4' value='sip_clap.hcgestacion_actual.var_0089>=apostrofeCapostrofe'>Tamiz.Anten.-Toxoplasmosis mayor Igual 20 Sem. - No Se Hizo</option>
        <option style='background-color:#C5D4F9' value='sip_clap.hcgestacion_actual.var_0090>=apostrofeAapostrofe'>Tamiz.Anten.-Toxopl.1º Consulta- Negativo</option>
        <option style='background-color:#C5D4F9' value='sip_clap.hcgestacion_actual.var_0090>=apostrofeBapostrofe'>Tamiz.Anten.-Toxopl.1º Consulta- Positivo</option>
        <option style='background-color:#C5D4F9' value='sip_clap.hcgestacion_actual.var_0090>=apostrofeCapostrofe'>Tamiz.Anten.-Toxopl.1º Consulta- No Se Hizo</option>
        </select>
        </div>
      </div>
  </div>

</div>
<div>&nbsp</div>
<div>&nbsp</div>

<div class="col-md-12" align="center">

<input type="submit" name="traer_datos" value='Detalle Global' class="btn btn-info" onclick="return control_entrada()">

<input type="submit" name="traer_datos" value='Muestra' class="btn btn-success" onclick="return control_entrada()">

<input type="submit" class="btn btn-warning" name="genera_excel" value="Genera Excel" onclick="return control_entrada()">
  <div>
  </BR>
  </div>
</div>
</form>

<?if ($_POST['traer_datos']=='Muestra'){?>
<div class="row">
  <div class="col-md-12" id="grafico" name="grafico">
    <?if ($accion=='Datos Obtenidos'){
      $cuie=$_POST['cuie'];?>
     
    <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
        <table class="table table-striped">
          <tr>
            <td align="center"><b>Descripcion</b></td>
            <td align="center"><b>Cantidad</b></b></td>
          </tr>
          
         
        <?
        ?>
        <input type="hidden" id="i" value="<?=sizeof($res_sql)?>">
        <?for ($i=0;$i<=sizeof($res_sql)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
           <tr onclick="<?=$onclick?>">
           <td><?=$descripcion=$res_sql[$i][0]?></td>
           <?
            $descripcion_id='descripcion'.$i;
            $descripcion=$res_sql[$i][0];
            $cantidad_id='cantidad'.$i;
            $cantidad=$res_sql[$i][1];?>
           <td align="center"><?=$cantidad?></td>
           </tr>
           <tr>
           <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
           <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
           </tr>
           <?}?>                            
       </table>
      </div>   
  </div>
</div>
    
&nbsp

<div class="row">  
  <!--<h1><font size=3 color= red><b>Grafico</b></font></h1>-->
  <div align="center">
    <div  class="col-md-12" id="grafica">
      <span id="spinner" class="fa fa-spin fa-3x"></span>
      <div id="chart"></div>
      <button id="bar" type="button" class="btn btn-success">
      <span class="fa fa-bar-chart"></span> Gráfica de barras
      </button>
      <button id="pie" class="btn btn-success">
      <span class="fa fa-pie-chart"></span> Gráfica circular
      </button>
      <button id="donut" class="btn btn-success">
      <span class="fa fa-circle-o-notch"></span> Gráfica de rosca
      </button>
      <script src="app_grafico.js" type="text/javascript" charset="utf-8"></script>
    </div>
  </div>
</div>
<?}?>
</div>
</div>
<?}
  
  else {?>
    <?if ($accion=='Datos Obtenidos'){
          $cuie=$_POST['cuie'];?>
      <div class="row">
        <div class="col-md-6">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_edad)?>">
          <?for ($i=0;$i<=sizeof($res_sql_edad)-1;$i++) {
             $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_edad[$i][2]));
             $onclick="window.open('$ref' , '_blank');";?>
          <tr onclick="<?=$onclick?>">
          <td><?=$descripcion=$res_sql_edad[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_edad[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_edad[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
      
        <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_etnia)?>">
          <?for ($i=0;$i<sizeof($res_sql_etnia)-1;$i++) {
             $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_etnia[$i][2]));
             $onclick="window.open('$ref' , '_blank');";?>
          <tr onclick="<?=$onclick?>">
          <td><?=$descripcion=$res_sql_etnia[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_etnia[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_etnia[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
     </div>
     &nbsp

    <div class="row">
      <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_educacion)?>">
          <?for ($i=0;$i<sizeof($res_sql_educacion)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_educacion[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr >
          <td><?=$descripcion=$res_sql_educacion[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_educacion[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_educacion[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
      
        <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_civil)?>">
          <?for ($i=0;$i<sizeof($res_sql_civil)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_civil[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr>
          <td><?=$descripcion=$res_sql_civil[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_civil[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_civil[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
    </div>

    &nbsp

    <div class="row">
      <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_familiar)?>">
          <?for ($i=0;$i<sizeof($res_sql_familiar)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_familiar[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr>
          <td><?=$descripcion=$res_sql_familiar[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_familiar[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_familiar[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
      
        <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_personal)?>">
          <?for ($i=0;$i<sizeof($res_sql_personal)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_personal[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr>
          <td><?=$descripcion=$res_sql_personal[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_personal[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_personal[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
    </div>  
  
  &nbsp

  <div class="row">
    <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_gestasprevias)?>">
          <?for ($i=0;$i<sizeof($res_sql_gestasprevias)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_gestasprevias[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr onclick="<?=$onclick?>">
          <td><?=$descripcion=$res_sql_gestasprevias[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_gestasprevias[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_gestasprevias[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
      
        <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_partosprevios)?>">
          <?for ($i=0;$i<sizeof($res_sql_partosprevios)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_partosprevios[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr onclick="<?=$onclick?>">
          <td><?=$descripcion=$res_sql_partosprevios[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_partosprevios[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_partosprevios[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>    
    </div>

  &nbsp

  <div class="row">
    <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_cesareas)?>">
          <?for ($i=0;$i<sizeof($res_sql_cesareas)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_cesareas[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr onclick="<?=$onclick?>">
          <td><?=$descripcion=$res_sql_cesareas[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_cesareas[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_cesareas[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
      
        <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_tabaco)?>">
          <?for ($i=0;$i<sizeof($res_sql_tabaco)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_tabaco[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr onclick="<?=$onclick?>">
          <td><?=$descripcion=$res_sql_tabaco[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_tabaco[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_tabaco[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>    
  </div>

  &nbsp

  <div class="row">
    <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_antitetanica)?>">
          <?for ($i=0;$i<sizeof($res_sql_antitetanica)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_antitetanica[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr onclick="<?=$onclick?>">
          <td><?=$descripcion=$res_sql_antitetanica[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_antitetanica[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_antitetanica[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
      
        <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_exa_mama)?>">
          <?for ($i=0;$i<sizeof($res_sql_exa_mama)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_exa_mama[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr onclick="<?=$onclick?>">
          <td><?=$descripcion=$res_sql_exa_mama[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_exa_mama[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_exa_mama[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
  </div>

   &nbsp

  <div class="row">
    <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_exa_cervi)?>">
          <?for ($i=0;$i<sizeof($res_sql_exa_cervi)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_exa_cervi[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr onclick="<?=$onclick?>">
          <td><?=$descripcion=$res_sql_exa_cervi[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_exa_cervi[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_exa_cervi[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
      
        <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_bacteriuria)?>">
          <?for ($i=0;$i<sizeof($res_sql_bacteriuria)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_bacteriuria[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr onclick="<?=$onclick?>">
          <td><?=$descripcion=$res_sql_bacteriuria[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_bacteriuria[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_bacteriuria[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
  </div>

   &nbsp

  <div class="row">
    <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_anemia)?>">
          <?for ($i=0;$i<sizeof($res_sql_anemia)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_anemia[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr onclick="<?=$onclick?>">
          <td><?=$descripcion=$res_sql_anemia[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_anemia[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_anemia[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
      
        <div class="col-md-6" id="grafico" name="grafico">
          <div align="center" style="border:1px solid; border-color:#EF9696; cursor: pointer">
          <table class="table table-striped">
          <tr>
          <td align="center"><b>Descripcion</b></td>
          <td align="center"><b>Cantidad</b></b></td>
          </tr>
          <input type="hidden" id="i" value="<?=sizeof($res_sql_sifilis)?>">
          <?for ($i=0;$i<sizeof($res_sql_sifilis)-1;$i++) {
            $ref = encode_link("detalle_sipweb.php",array("consulta"=>$res_sql_sifilis[$i][2]));
            $onclick="window.open('$ref' , '_blank');";?>
          <tr onclick="<?=$onclick?>">
          <td><?=$descripcion=$res_sql_sifilis[$i][0]?></td>
          <?$descripcion_id='descripcion'.$i;
          $descripcion=$res_sql_sifilis[$i][0];
          $cantidad_id='cantidad'.$i;
          $cantidad=$res_sql_sifilis[$i][1];?>
          <td align="center"><?=$cantidad?></td>
          </tr>
          <tr>
          <input type="hidden" id="<?=$descripcion_id?>" value="<?=$descripcion?>">
          <input type="hidden" id="<?=$cantidad_id?>" value="<?=$cantidad?>">
          </tr>
          <?}?>                            
          </table>
          </div>   
        </div>
  </div>

   &nbsp
  </div>
 <?}?>
 </div>
</div>
<?}?>


</div>
</body>
</html>
<?echo fin_pagina();// aca termino ?>