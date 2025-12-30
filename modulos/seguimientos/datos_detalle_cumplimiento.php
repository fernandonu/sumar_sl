<?php

require_once ("../../config.php");
require_once("funciones_seguimientos.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

switch ($solicita_datos) {
	case 'control de adultos': {
 		
    $res_sql=control_adultos($fecha_desde,$fecha_hasta,$cuie);
    echo $html_header;
    ?>
    <form name=form1 method=post action="datos_detalle_cumplimiento.php">
    <table width="100%">
      <tr>
       <td>
        <table width="100%">
         <tr>
          <td align=left>
           <b>Total de Beneficiarios: <?php echo $res_sql->RecordCount();?></b>
           </td>       
          </tr>      
        </table>  
       </td>
      </tr>  
     </table> 
     <br>
     <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="12"> 
      <tr bgcolor=#C0C0FF>
        <td align=center width="10%" id="mo">DNI</td>      	
        <td align=right width="25%" id="mo">Nombre</td>   	
        <td align=right width="25%" id="mo">Apellido</td>      	
        <td align=right width="10%"id="mo">Fecha Nacimiento</td>       	
        <!--<td align=right id="mo">Fecha Control</td> 
        <td align=right id="mo">Peso</td> 
        <td align=right id="mo">Talla</td> 
        <td align=right id="mo">Perimetro Cefalico</td> 
        <td align=right id="mo">Prcent.Peso/Edad</td>  
        <td align=right id="mo">Prcent.Talla/Edad</td>	
        <td align=right id="mo">Prcent.Perm.Cefal./Edad</td>-->
        </tr>
      <?   
      while (!$res_sql->EOF) {?>
         <tr>     
         <td><?php echo $res_sql->fields['afidni']?></td>
       <td><?php echo $res_sql->fields['afinombre']?></td>
       <td><?php echo $res_sql->fields['afiapellido']?></td>
       <td><?php echo Fecha($res_sql->fields['afifechanac'])?></td>
       <!--<td><?php echo Fecha($res_sql->fields['fecha_control'])?></td>
       <td align="center"><?php echo $res_sql->fields['peso']?></td>
       <td align="center"><?php echo $res_sql->fields['talla']?></td>
       <td><?php echo $res_sql->fields['perimetro_cefalico']?></td>-->
       
       <?/*switch ($res_sql->fields['percentilo_peso_edad']) {
          case 1: $percentilo_peso_edad="<3";break;
          case 2: $percentilo_peso_edad="3-10";break;
          case 3: $percentilo_peso_edad=">10-90";break;
          case 4: $percentilo_peso_edad=">90-97";break;
          case 5: $percentilo_peso_edad=">97";break;
          case '': $percentilo_peso_edad="Datos sin Ingresar";break;
       } 
       
       switch ($res_sql->fields['percentilo_talla_edad']) {
          case 1:$percentilo_talla_edad=">-3";break;
          case 2:$percentilo_talla_edad=">3-97";break;
          case 3:$percentilo_talla_edad=">+97";break;
          case '': $percentilo_talla_edad="Datos sin Ingresar";break;
       }
       
       switch ($res_sql->fields['percentilo_perim_cefalico_edad']) {
          case 1:$percentilo_perim_cefalico_edad=">-3";break;
          case 2:$percentilo_perim_cefalico_edad=">3-97";break;
          case 3:$percentilo_perim_cefalico_edad=">+97";break;
          case '': $percentilo_perim_cefalico_edad="Datos sin Ingresar";break;
        }
       
       switch ($res_sql->fields['percentilo_peso_talla']) {
          case 1:$percentilo_peso_talla="<3";break;
          case 2:$percentilo_peso_talla="3-10";break;
          case 3:$percentilo_peso_talla=">10-85";break;
          case 4:$percentilo_peso_talla=">85-97";break;
          case 5:$percentilo_peso_talla=">97";break;
          case '': $percentilo_peso_talla="Datos sin Ingresar";break;
       }  
       
       */?>
       <!--<td align="center"><?php echo $percentilo_peso_edad?></td>
       <td align="center"><?php echo $percentilo_talla_edad?></td>
       <td align="center"><?php echo $percentilo_perim_cefalico_edad?></td>-->
       </tr>	
      <?$res_sql->MoveNext();
        }?>
       </table>
     </form>
     <?php echo fin_pagina();// aca termino ?>
     <?break;}//del case
  
  
  
  case 'controles de ninos menor de 1': {
 		
$res_sql=ninios_menores_1_anio($fecha_desde,$fecha_hasta,$cuie);
echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">
<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Beneficiarios: <?php echo $res_sql->RecordCount();?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="12"> 
  <tr bgcolor=#C0C0FF>
    <td align=center width="10%" id="mo">DNI</td>      	
    <td align=right width="25%" id="mo">Nombre</td>   	
    <td align=right width="25%" id="mo">Apellido</td>      	
    <td align=right width="10%"id="mo">Fecha Nacimiento</td>       	
    <!--<td align=right id="mo">Fecha Control</td> 
    <td align=right id="mo">Peso</td> 
    <td align=right id="mo">Talla</td> 
    <td align=right id="mo">Perimetro Cefalico</td> 
    <td align=right id="mo">Prcent.Peso/Edad</td>  
    <td align=right id="mo">Prcent.Talla/Edad</td>	
    <td align=right id="mo">Prcent.Perm.Cefal./Edad</td>-->
    </tr>
  <?   
  while (!$res_sql->EOF) {?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo Fecha($res_sql->fields['afifechanac'])?></td>
	 <!--<td><?php echo Fecha($res_sql->fields['fecha_control'])?></td>
	 <td align="center"><?php echo $res_sql->fields['peso']?></td>
	 <td align="center"><?php echo $res_sql->fields['talla']?></td>
	 <td><?php echo $res_sql->fields['perimetro_cefalico']?></td>-->
	 
   <?/*switch ($res_sql->fields['percentilo_peso_edad']) {
      case 1: $percentilo_peso_edad="<3";break;
      case 2: $percentilo_peso_edad="3-10";break;
      case 3: $percentilo_peso_edad=">10-90";break;
      case 4: $percentilo_peso_edad=">90-97";break;
      case 5: $percentilo_peso_edad=">97";break;
      case '': $percentilo_peso_edad="Datos sin Ingresar";break;
   } 
   
   switch ($res_sql->fields['percentilo_talla_edad']) {
      case 1:$percentilo_talla_edad=">-3";break;
      case 2:$percentilo_talla_edad=">3-97";break;
      case 3:$percentilo_talla_edad=">+97";break;
      case '': $percentilo_talla_edad="Datos sin Ingresar";break;
   }
   
   switch ($res_sql->fields['percentilo_perim_cefalico_edad']) {
      case 1:$percentilo_perim_cefalico_edad=">-3";break;
      case 2:$percentilo_perim_cefalico_edad=">3-97";break;
      case 3:$percentilo_perim_cefalico_edad=">+97";break;
      case '': $percentilo_perim_cefalico_edad="Datos sin Ingresar";break;
    }
   
   switch ($res_sql->fields['percentilo_peso_talla']) {
      case 1:$percentilo_peso_talla="<3";break;
      case 2:$percentilo_peso_talla="3-10";break;
      case 3:$percentilo_peso_talla=">10-85";break;
      case 4:$percentilo_peso_talla=">85-97";break;
      case 5:$percentilo_peso_talla=">97";break;
      case '': $percentilo_peso_talla="Datos sin Ingresar";break;
   }  
   
   */?>
   <!--<td align="center"><?php echo $percentilo_peso_edad?></td>
	 <td align="center"><?php echo $percentilo_talla_edad?></td>
	 <td align="center"><?php echo $percentilo_perim_cefalico_edad?></td>-->
	 </tr>	
	<?$res_sql->MoveNext();
    }?>
	 </table>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case
	
case 'controles_1_a_9': {
  $res_sql=ninios_entre_1_y_9_anios($fecha_desde,$fecha_hasta,$cuie);

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">
<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Beneficiarios: <?php echo $res_sql->RecordCount();?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="11"> 
  <tr bgcolor=#C0C0FF>
    <td align=center id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>       	
    <!--<td align=right id="mo">Fecha Control</td> 
    <td align=right id="mo">Peso</td>  
    <td align=right id="mo">Talla</td>
    <td align=right id="mo">Prcent.Peso/Edad</td>  
    <td align=right id="mo">Prcent.Talla/Edad</td>
    <td align=right id="mo">Prcent.IMC/Edad</td> 
    <td align=right id="mo">Tension Arterial</td> 	-->
     </tr>
  <?   
  while (!$res_sql->EOF) {?>
     <tr>     
   <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <!--<td><?php echo $res_sql->fields['fecha_control']?></td>
	 <td align="center"><?php echo $res_sql->fields['peso']?></td>
	 <td align="center"><?php echo $res_sql->fields['talla']?></td>-->
   
   <?/*switch ($res_sql->fields['percentilo_peso_edad']) {
      case 1: $percentilo_peso_edad="<3";break;
      case 2: $percentilo_peso_edad="(3-10)";break;
      case 3: $percentilo_peso_edad=">10-90";break;
      case 4: $percentilo_peso_edad=">90-97";break;
      case 5: $percentilo_peso_edad=">97";break;
      case '': $percentilo_peso_edad="Datos sin Ingresar";break;
   } 
   
   switch ($res_sql->fields['percentilo_talla_edad']) {
      case 1:$percentilo_talla_edad=">-3";break;
      case 2:$percentilo_talla_edad=">3-97";break;
      case 3:$percentilo_talla_edad=">+97";break;
      case '': $percentilo_talla_edad="Datos sin Ingresar";break;
   }
   
   switch ($res_sql->fields['percen_imc_edad']) {
      case 1:$percen_imc_edad="<3";break;
      case 2:$percen_imc_edad="(3-10)";break;
      case 3:$percen_imc_edad=">10-85";break;
      case 4:$percen_imc_edad=">85-97";break;
      case 5:$percen_imc_edad=">97";break;
      case '': $percen_imc_edad="Datos sin Ingresar";break;
   }  
   
   */?>
	 <!--<td align="center"><?php echo $percentilo_peso_edad?></td>
	 <td align="center"><?php echo $percentilo_talla_edad?></td>
   <td align="center"><?php echo $percen_imc_edad?></td>
	 <td><?php echo $res_sql->fields['tension_arterial']?></td>-->
	 </tr>	
	<?$res_sql->MoveNext();
    }?>
	 </table>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

 case 'embar_antes_sem_12': {

		$res_sql=emb_sem_12($fecha_desde,$fecha_hasta,$cuie);

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">
<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Beneficiarios: <?php echo $res_sql->RecordCount();?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td> 
    <td align=right id="mo">Fecha Nac.</td>   	
    <td align=right id="mo">FUM</td>      	
    <td align=right id="mo">FPP</td>      	
    <td align=right id="mo">Edad Gestacional</td>
    <td align=right id="mo">Fecha Control</td>
    <td align=right id="mo">Comentario</td>
  </tr>
  <?   
  while (!$res_sql->EOF) {?>
   <tr>     
   <td><?php echo $res_sql->fields['dni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
   <td><?php echo $res_sql->fields['fecha_nac']?></td>
	 <td><?php echo $res_sql->fields['fum']?></td>
	 <td><?php echo $res_sql->fields['fpp']?></td>
	 <td><?php echo $res_sql->fields['edad_gestacional']?></td>
	 <td><?php echo $res_sql->fields['fecha_control_prenatal']?></td>
   <td><?php echo $res_sql->fields['comentario']?></td>
    </tr>	
	<?$res_sql->MoveNext();
    }?>
	 </table>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

case 'total_controles_embar': {
  
  $res_sql=embarazadas($fecha_desde,$fecha_hasta,$cuie);

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">
<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $res_sql->RecordCount();?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="6"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>
    <td align=right id="mo">Fecha Nac.</td>      	
    <!--<td align=right id="mo">Edad Gestacional</td>      	
    <td align=right id="mo">Fecha Control</td>      	
    <td align=right id="mo">Tension Arterial</td>
    <td align=right id="mo">Comentario</td>-->
    </tr>
  <?   
  while (!$res_sql->EOF) {?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
   <td><?php echo $res_sql->fields['fecha_nac']?></td>
	 <td><?php echo $res_sql->fields['edad_gestacional']?></td>
	 <!--td><?php echo $res_sql->fields['fecha_control']?></td>
	 <td><?php echo $res_sql->fields['tension_arterial']?></td>
   <td><?php echo $res_sql->fields['comentario']?></td>-->
	 </tr>	
	<?$res_sql->MoveNext();
    }?>
	 </table>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case


 case 'adolescentes': {

		$res_sql=adolescentes($fecha_desde,$fecha_hasta,$cuie);

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">
<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Beneficiarios: <?php echo $res_sql->RecordCount();?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="8"> 
  <tr bgcolor=#C0C0FF>
    <td align=center width="10%" id="mo">DNI</td>      	
    <td align=right width="25%" id="mo">Nombre</td>   	
    <td align=right width="25%" id="mo">Apellido</td>      	
    <td align=right width="10%"id="mo">Fecha Nacimiento</td>       	
    <!--<td align=right id="mo">Fecha Control</td>      	
    <td align=right id="mo">Talla</td>
    <td align=right id="mo">Peso</td>
    <td align=right id="mo">Tension Arterial</td>-->
  </tr>
  <?   
  while (!$res_sql->EOF) {?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <!--<td><?php echo $res_sql->fields['fecha_control']?></td>
	 <td align="center"><?php echo $res_sql->fields['talla']?></td>
	 <td align="center"><?php echo $res_sql->fields['peso']?></td>
	 <td><?php echo $res_sql->fields['tension_arterial']?></td>-->
    </tr>	
	<?$res_sql->MoveNext();
    }?>
	 </table>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case


case 'cuidado_sexual': {

  $res_sql=cuidado_sexual($fecha_desde,$fecha_hasta,$cuie);

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">
<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $res_sql->RecordCount();?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="8"> 
  <tr bgcolor=#C0C0FF>
    <td align=center width="10%" id="mo">DNI</td>      	
    <td align=right width="25%" id="mo">Nombre</td>   	
    <td align=right width="25%" id="mo">Apellido</td>      	
    <td align=right width="10%"id="mo">Fecha Nacimiento</td>       	
    <td align=right id="mo">Fecha Control</td>      	
    <td align=right id="mo">Talla</td>
    <td align=right id="mo">Peso</td>
    <td align=right id="mo">Tensio Arterial</td>
  </tr>
  <?   
  while (!$res_sql->EOF) {?>
     <tr>     
     <td><?php echo $res_sql->fields['dni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_control']?></td>
	 <td align="center"><?php echo $res_sql->fields['peso']?></td>
	 <td align="center"><?php echo $res_sql->fields['talla']?></td>
	 <td><?php echo $res_sql->fields['ta']?></td>
    </tr>	
	<?$res_sql->MoveNext();
    }?>
	 </table>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

//////////////////////////vacunas////////////////////////////////////////////


 case 'doble_viral': {

		$sql="SELECT distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') 
and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)";

$res_sql=sql($sql) or die();

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">
 
 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>      	
    <td align=right id="mo">Fecha Vacuna</td>      	
    <td align=right id="mo">Nombre</td>
    <td align=right id="mo">Dosis</td>
  </tr>
  <? $count=0;  
  while (!$res_sql->EOF) {

  		if ($res_sql->fields['id_vac_apli']==11) { $count++;?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_vac']?></td>
	 <td><?php echo $res_sql->fields['nom_vacum']?></td>
	 <td align="center"><?php echo $res_sql->fields['dosis']?></td>
    </tr>	
	<?}
		$res_sql->MoveNext();
    
   }?>
	</table>
	<br>
	<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $count;?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table>
 <br>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

 case 'hep_b': {

		$sql="SELECT distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
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
and (trazadoras.vacunas.eliminada=0)";

$res_sql=sql($sql) or die();

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">
 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>      	
    <td align=right id="mo">Fecha Vacuna</td>      	
    <td align=right id="mo">Nombre</td>
    <td align=right id="mo">Dosis</td>
  </tr>
  <?  $count=0; 
  while (!$res_sql->EOF) {

  		if ($res_sql->fields['id_vac_apli']==2) { $count++;?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_vac']?></td>
	 <td><?php echo $res_sql->fields['nom_vacum']?></td>
	 <td align="center"><?php echo $res_sql->fields['dosis']?></td>
    </tr>	
	<?}
		$res_sql->MoveNext();
    
   }?>
	 </table>
	<br>
	<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $count;?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table>
 <br>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case


 case 'neumococo': {

		$sql="SELECT distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)";

$res_sql=sql($sql) or die();

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">
 
 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>      	
    <td align=right id="mo">Fecha Vacuna</td>      	
    <td align=right id="mo">Nombre</td>
    <td align=right id="mo">Dosis</td>
  </tr>
  <? $count=0;  
  while (!$res_sql->EOF) {

  		if ($res_sql->fields['id_vac_apli']==16 || $res_sql->fields['id_vac_apli']==17) { $count++;?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_vac']?></td>
	 <td><?php echo $res_sql->fields['nom_vacum']?></td>
	 <td align="center"><?php echo $res_sql->fields['dosis']?></td>
    </tr>	
	<?}
		$res_sql->MoveNext();
    
   }?>
	 </table>
	<br>
	<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $count;?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table>
 <br>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

 case 'pentavalente': {

		$sql="SELECT distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)

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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)";

$res_sql=sql($sql) or die();

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">

 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>      	
	<td align=right id="mo">Fecha Vacuna</td>      	
    <td align=right id="mo">Nombre</td>
    <td align=right id="mo">Dosis</td>
  </tr>
  <? $count=0;
  while (!$res_sql->EOF) {

  		if ($res_sql->fields['id_vac_apli']==3) { $count++;?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_vac']?></td>
	 <td><?php echo $res_sql->fields['nom_vacum']?></td>
	 <td align="center"><?php echo $res_sql->fields['dosis']?></td>
    </tr>	
	<?}
		$res_sql->MoveNext();
    
   }?>
	 </table>
	<br>
	<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $count;?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table>
 <br>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

 case 'sabin': {

		$sql="SELECT distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') 
and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)";

$res_sql=sql($sql) or die();

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">
 
 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>      	
    <td align=right id="mo">Fecha Vacuna</td>      	
    <td align=right id="mo">Nombre</td>
    <td align=right id="mo">Dosis</td>
  </tr>
  <? $count=0;  
  while (!$res_sql->EOF) {

  		if ($res_sql->fields['id_vac_apli']==5 or $res_sql->fields['id_vac_apli']==20) { $count++;?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_vac']?></td>
	 <td><?php echo $res_sql->fields['nom_vacum']?></td>
	 <td align="center"><?php echo $res_sql->fields['dosis']?></td>
    </tr>	
	<?}
		$res_sql->MoveNext();
    
   }?>
	 </table>
<br>
	<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $count;?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table>
 <br>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

 case 'triple_viral': {

		$sql="SELECT distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
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
and (trazadoras.vacunas.eliminada=0)";

$res_sql=sql($sql) or die();

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">

 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>      	
    <td align=right id="mo">Fecha Vacuna</td>      	
    <td align=right id="mo">Nombre</td>
    <td align=right id="mo">Dosis</td>
  </tr>
  <? $count=0;  
  while (!$res_sql->EOF) {

  		if ($res_sql->fields['id_vac_apli']==6) { $count++;?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_vac']?></td>
	 <td><?php echo $res_sql->fields['nom_vacum']?></td>
	 <td align="center"><?php echo $res_sql->fields['dosis']?></td>
    </tr>	
	<?}
		$res_sql->MoveNext();
    
   }?>
	 </table>
<br>
	<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $count;?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table>
 <br>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

 case 'gripe': {

		$sql="SELECT distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)

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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)";

$res_sql=sql($sql) or die();

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">

 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>      	
    <td align=right id="mo">Fecha Vacuna</td>      	
    <td align=right id="mo">Nombre</td>
    <td align=right id="mo">Dosis</td>
  </tr>
  <? $count=0;  
  while (!$res_sql->EOF) {

  		if ($res_sql->fields['id_vac_apli']==18 || $res_sql->fields['id_vac_apli']==19
        || $res_sql->fields['id_vac_apli']==52 || $res_sql->fields['id_vac_apli']==53
        || $res_sql->fields['id_vac_apli']==54) { $count++;?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_vac']?></td>
	 <td><?php echo $res_sql->fields['nom_vacum']?></td>
	 <td align="center"><?php echo $res_sql->fields['dosis']?></td>
    </tr>	
	<?}
		$res_sql->MoveNext();
    
   }?>
	 </table>
	<br>
	<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $count;?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table>
 <br>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

 case 'hep_a': {

		$sql="SELECT distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)

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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)";

$res_sql=sql($sql) or die();

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">

 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>      	
    <td align=right id="mo">Fecha Vacuna</td>      	
    <td align=right id="mo">Nombre</td>
    <td align=right id="mo">Dosis</td>
  </tr>
  <? $count=0;  
  while (!$res_sql->EOF) {

  		if ($res_sql->fields['id_vac_apli']==7) { $count++;?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_vac']?></td>
	 <td><?php echo $res_sql->fields['nom_vacum']?></td>
	 <td align="center"><?php echo $res_sql->fields['dosis']?></td>
    </tr>	
	<?}
		$res_sql->MoveNext();
    
   }?>
	 </table>
	<br>
	<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $count;?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table>
 <br>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

 case 'trip_celular': {

		$sql="SELECT distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)

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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)";

$res_sql=sql($sql) or die();

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">
 
 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>      	
    <td align=right id="mo">Fecha Vacuna</td>      	
    <td align=right id="mo">Nombre</td>
    <td align=right id="mo">Dosis</td>
  </tr>
  <? $count=0;  
  while (!$res_sql->EOF) {

  		if ($res_sql->fields['id_vac_apli']==8) { $count++;?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_vac']?></td>
	 <td><?php echo $res_sql->fields['nom_vacum']?></td>
	 <td align="center"><?php echo $res_sql->fields['dosis']?></td>
    </tr>	
	<?}
		$res_sql->MoveNext();
    
   }?>
	 </table>
	<br>
	<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $count;?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table>
 <br>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

 case 'trip_acelular': {

		$sql="SELECT distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)

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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)";

$res_sql=sql($sql) or die();

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">
 
 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>      	
    <td align=right id="mo">Fecha Vacuna</td>      	
    <td align=right id="mo">Nombre</td>
    <td align=right id="mo">Dosis</td>
  </tr>
  <? $count=0; 
  while (!$res_sql->EOF) {

  		if ($res_sql->fields['id_vac_apli']==9) { $count++;?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_vac']?></td>
	 <td><?php echo $res_sql->fields['nom_vacum']?></td>
	 <td align="center"><?php echo $res_sql->fields['dosis']?></td>
    </tr>	
	<?}
		$res_sql->MoveNext();
    
   }?>
	 </table>
	<br>
	<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $count;?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table>
 <br>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

 case 'doble_bacteriana': {

		$sql="SELECT distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)

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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)";

$res_sql=sql($sql) or die();

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">

 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>      	
    <td align=right id="mo">Fecha Vacuna</td>      	
    <td align=right id="mo">Nombre</td>
    <td align=right id="mo">Dosis</td>
  </tr>
  <? $count=0;
  while (!$res_sql->EOF) {

  		if ($res_sql->fields['id_vac_apli']==10) { $count++;?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_vac']?></td>
	 <td><?php echo $res_sql->fields['nom_vacum']?></td>
	 <td align="center"><?php echo $res_sql->fields['dosis']?></td>
    </tr>	
	<?}
		$res_sql->MoveNext();
    
   }?>
	 </table>
	<br>
	<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $count;?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table>
 <br>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case

 
 case 'vph': {

		$sql="SELECT distinct on (nacer.smiafiliados.afidni,trazadoras.vacunas.id_vac_apli,trazadoras.vacunas.fecha_vac) 
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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)

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
where (trazadoras.vacunas.fecha_vac BETWEEN '$fecha_desde' and '$fecha_hasta') and cuie='$cuie' and (trazadoras.vacunas.eliminada=0)";

$res_sql=sql($sql) or die();

echo $html_header;
?>
<form name=form1 method=post action="datos_detalle_cumplimiento.php">

 <br>
 <table width="95%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="7"> 
  <tr bgcolor=#C0C0FF>
    <td align=right id="mo">DNI</td>      	
    <td align=right id="mo">Nombre</td>   	
    <td align=right id="mo">Apellido</td>      	
    <td align=right id="mo">Fecha Nacimiento</td>      	
    <td align=right id="mo">Fecha Vacuna</td>      	
    <td align=right id="mo">Nombre</td>
    <td align=right id="mo">Dosis</td>
  </tr>
  <? $count=0; 
  while (!$res_sql->EOF) {

  		if ($res_sql->fields['id_vac_apli']==14 or $res_sql->fields['id_vac_apli']==48) { $count++;?>
     <tr>     
     <td><?php echo $res_sql->fields['afidni']?></td>
	 <td><?php echo $res_sql->fields['afinombre']?></td>
	 <td><?php echo $res_sql->fields['afiapellido']?></td>
	 <td><?php echo $res_sql->fields['afifechanac']?></td>
	 <td><?php echo $res_sql->fields['fecha_vac']?></td>
	 <td><?php echo $res_sql->fields['nom_vacum']?></td>
	 <td align="center"><?php echo $res_sql->fields['dosis']?></td>
    </tr>	
	<?}
		$res_sql->MoveNext();
    
   }?>
	 </table>
<br>
	<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?php echo $count;?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table>
 <br>
 </form>
 <?php echo fin_pagina();// aca termino ?>
 <?break;}//del case
}//del swith?>