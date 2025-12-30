<?php

require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE); 

$sql_tmp="SELECT distinct on (afidni) * from (
        SELECT * from (
        select distinct on (smiafiliados.afidni) fichero.cuie,fichero.fecha_control,
        smiafiliados.afidni, smiafiliados.afinombre,
        smiafiliados.afiapellido, fichero.codigo_riesgo,
        nomenclador.descripcion from fichero.fichero 
        inner join nacer.smiafiliados on smiafiliados.id_smiafiliados=fichero.id_smiafiliados
        inner join  facturacion.nomenclador on fichero.codigo_riesgo=nomenclador.codigo
        where fichero.embarazo_riesgo='SI' and fichero.cuie='$cuie' and
        fichero.fecha_control between '$fecha_desde' and '$fecha_hasta'
        union
        select distinct on (beneficiarios.numero_doc) fichero.cuie,
        fichero.fecha_control,beneficiarios.numero_doc as afidni, beneficiarios.nombre_benef,
        beneficiarios.apellido_benef, fichero.codigo_riesgo,nomenclador.descripcion from fichero.fichero 
        inner join uad.beneficiarios using (id_beneficiarios)
        inner join  facturacion.nomenclador on fichero.codigo_riesgo=nomenclador.codigo
        where fichero.embarazo_riesgo='SI'  and fichero.cuie='$cuie' and
        fichero.fecha_control between '$fecha_desde' and '$fecha_hasta') as ccc
        union
        select distinct on (smiafiliados.afidni) comprobante.cuie,prestacion.fecha_prestacion,
        smiafiliados.afidni,smiafiliados.afinombre,
        smiafiliados.afiapellido,nomenclador.codigo,
        nomenclador.descripcion from facturacion.prestacion
        inner join facturacion.nomenclador using (id_nomenclador)
        inner join facturacion.comprobante using (id_comprobante)
        inner join nacer.smiafiliados using (id_smiafiliados)
        where nomenclador.grupo='NT'
        and comprobante.cuie='$cuie'
        and prestacion.fecha_prestacion between '$fecha_desde' and '$fecha_hasta'
        and (nomenclador.codigo='N004' or nomenclador.codigo='N006')
        ) as ttt";
$result=sql($sql_tmp) or fin_pagina();

echo $html_header;
?>
<form name=form1 method=post action="detalle_emb_riesgo.php">
<table width="100%">
  <tr>
   <td>
    <table width="100%">
     <tr>
      <td align=left>
       <b>Total de Inscriptos: <?=$result->RecordCount();?></b>
       </td>       
      </tr>      
    </table>  
   </td>
  </tr>  
 </table> 
 <br>
 <table width="100%" align=center border=1 bordercolor=#585858 cellspacing="0" cellpadding="5"> 
  <tr bgcolor=#C0C0FF>
    <td align="right" id="mo">DNI</td>      	
    <td align="right" id="mo">Apellido</td>      	
    <td align="right" id="mo">Nombre</td>      	
    <td align="right" id="mo">Fecha Control</td>
    <td align="right" id="mo">Codigo</td>
    <td align="right" id="mo">Descripcion</td>

  </tr>
  <?   
  while (!$result->EOF) {?>   
    <tr>          
	  <td><?=$result->fields['afidni']?></td>
    <td><?=$result->fields['afiapellido']?></td>
    <td><?=$result->fields['afinombre']?></td>
	  <td><?=fecha($result->fields['fecha_control'])?></td>
    <td><?=$result->fields['codigo_riesgo']?></td> 
    <td><?=$result->fields['descripcion']?></td>  
    </tr>	
	<?$result->MoveNext();
    }?>
 </table>
 </form>
 <?=fin_pagina();// aca termino ?>