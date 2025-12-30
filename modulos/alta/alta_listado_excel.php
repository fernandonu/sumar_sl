<?php

require_once ("../../config.php");

$sql="SELECT a.*, b.nombre as efector_cuie, c.nombre as efector_at_habitual, d.nombre as efector_derivado FROM alta.alta a
      left join nacer.efe_conv b using (CUIE)
      left join nacer.efe_conv c on (a.cuie_at_hab = c.cuie)
      left join nacer.efe_conv d on (a.cuie_ef_der = d.cuie)
      where a.fecha_alta >= '2025-01-01'";


$result=sql($sql) or fin_pagina();

excel_header("alta_listado.xls");

?>
<form name=form1 method=post action="alta_listado_excel.php">
<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total: </b><?php echo $result->RecordCount();?> 
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
  <tr bgcolor=#C0C0FF>   	
    <td>Efector de Alta</td>      	    
    <td>Efector Habitual</td>  
    <td>Efector Derivado</td>
    <td>Fecha de Turno</td>
    <td>Hora de Turno</td>    	    
    <td>Fecha Alta</td>      	    
    <td>Fecha Parto</td>      	    
    <td>Nombre Madre</td>      	    
    <td>Docuememto Madre</td>      	    
    <td>Nombre Bebe</td>      	    
    <td>Domicilio</td>      	    
    <td>Reponsable Obstetricia</td>      	    
    <td>Reponsable Neonatologia</td>      	    
    <td>Reponsable Enfermeria</td>      	    
    <td>Llena Epicrisis</td>      	    
    <td>Carnet Parenteral</td>      	    
    <td>Peso al Nacer</td>      	    
    <td>Riesgo Social</td>      	    
    <td>Sifilis</td>      	    
    <td>HIV</td>      	    
    <td>Hep B</td>      	    
    <td>Chagas</td>      	    
    <td>Toxoplasmosis</td>      	    
    <td>Pesquisa Neonatal</td>      	    
    <td>Vacuna Hep B</td>      	    
    <td>Vacuna BCG</td>      	    
    <td>Grupo y Factor de la Madre</td>      	    
    <td>Grupo y Factor del Bebe</td>      	    
    <td>Gamma Anti RH</td>      	    
    <td>Observaciones</td>
    <td>Puericultura</td>
    <td>Alarma Bebe</td>
    <td>Alarma Madre</td>
    <td>Lactancia Materna</td>
    <td>Salud Reproductiva</td>
    <td>Cuidados de Puerperio</td>  
  </tr>
  <?   
  while (!$result->EOF) {?>  
    <tr> 
     <td ><?php echo $result->fields['efector_cuie']?></td>         
     <td ><?php echo $result->fields['efector_at_habitual']?></td>
     <td ><?php echo $result->fields['efector_derivado']?></td>
     <td ><?php echo Fecha($result->fields['fecha_turno'])?></td> 
     <td ><?php echo $result->fields['hora_turno']?></td>         
     <td ><?php echo Fecha($result->fields['fecha_alta'])?></td>     
     <td ><?php echo Fecha($result->fields['fecha_parto'])?></td>     
     <td ><?php echo $result->fields['nom_madre']?></td>    
     <td ><?php echo $result->fields['doc_madre']?></td>    
     <td ><?php echo $result->fields['nom_bebe']?></td>    
     <td ><?php echo $result->fields['domicilio']?></td>    
     <td ><?php echo $result->fields['rep_obstetricia']?></td>    
     <td ><?php echo $result->fields['rep_neo']?></td>    
     <td ><?php echo $result->fields['rep_enf']?></td>    
     <td ><?php echo $result->fields['llena_epi']?></td>    
     <td ><?php echo $result->fields['carnet_parenteral']?></td>    
     <td ><?php echo $result->fields['peso_nacer']?></td>    
     <td ><?php echo $result->fields['riesgo_social']?></td>    
     <td ><?php echo $result->fields['sifilis']?></td>    
     <td ><?php echo $result->fields['hiv']?></td>    
     <td ><?php echo $result->fields['hep_b']?></td>    
     <td ><?php echo $result->fields['chagas']?></td>    
     <td ><?php echo $result->fields['toxo']?></td>    
     <td ><?php echo $result->fields['pes_neonatal']?></td>    
     <td ><?php echo $result->fields['vac_hep_b']?></td>    
     <td ><?php echo $result->fields['vac_bcg']?></td>    
     <td ><?php echo $result->fields['grupo_factor_mama']?></td>    
     <td ><?php echo $result->fields['grupo_factor_bebe']?></td>    
     <td ><?php echo $result->fields['gamma_anti_rh']?></td>    
     <td ><?php echo $result->fields['observaciones']?></td>
     <td ><?php echo $result->fields['pueri']?></td>
     <td ><?php echo $result->fields['alarma_bebe']?></td>
     <td ><?php echo $result->fields['alarma_madre']?></td>
     <td ><?php echo $result->fields['lac_materna']?></td>
     <td ><?php echo $result->fields['salud_repro']?></td>
     <td ><?php echo $result->fields['cuidados_puerpe']?></td>       
    </tr>
	<?$result->MoveNext();
    }?>
 </table>
 </form>