<?php
/*
$Author: Seba $
$Revision: 2.0 $
$Date: 2016/08/16 $
*/
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);


$sql_tmp="SELECT * from cobertura_efectiva.$tabla_base
      inner join nacer.smiafiliados using (id_smiafiliados)
      where tipo_linea='$tipo_linea'";
$result=sql($sql_tmp) or fin_pagina();


echo $html_header;
?>

<form name=form1 method=post action="detalle_linea_de_cuidado.php">
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
    <td align="right" id="mo">DNI</td>        
    <td align="right" id="mo">Apellido</td>       
    <td align="right" id="mo">Nombre</td>       
    <td align="center" id="mo">Fecha Nac.</td>
    <td align="center" id="mo">Sexo</td>
    <td align="center" id="mo">Linea Temporal</td>
  </tr>
  <?   
  while (!$result->EOF) {
    $id_smiafiliados=$result->fields['id_smiafiliados'];
    if ($result->fields['adecuada']=="SI") $att="style='background-color:#A7B8FC'"; 
    elseif ($result->fields['minima']=="SI") $att="style='background-color:#A5FAAD'";
    else $att="";?>   
  
  <!-- Modal -->
<div class="modal fade" id="myModal<?=$id_smiafiliados?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Linea Temporal</h4>
        <?$sql_modal="SELECT * from cobertura_efectiva.$tabla_base
                      INNER JOIN nacer.smiafiliados using (id_smiafiliados)
                      INNER JOIN cobertura_efectiva.$tabla_historial on $tabla_base.id=$tabla_historial.id_hist
                      WHERE $tabla_base.id_smiafiliados=$id_smiafiliados and tipo_linea='$tipo_linea'
                      ORDER by $tabla_historial.fecha_prestacion";;
          $res_modal=sql($sql_modal) or fin_pagina(); ?>      
      </div>
      <div class="modal-body">
      <?=$res_modal->fields['afinombre']?>&nbsp<b><?=$res_modal->fields['afiapellido']?></b>
        <div>&nbsp</div>
        <div class="row" style="background-color:#D8DEF5">
          <div class="col-md-2">Fecha Prest.</div>        
          <div class="col-md-2">Prestacion</div>       
          <div class="col-md-4">Descripcion</div>       
          <div class="col-md-2">Cuie</div>
          <div class="col-md-2">N.Fact</div>
        </div>
        <?while (!$res_modal->EOF) {?>
          <div class="row" style="border:0.5px solid; border-color:#EAEAEA">
            <div class="col-md-2"><?=Fecha($res_modal->fields['fecha_prestacion'])?></div>     
            <div class="col-md-2"><?=$res_modal->fields['grupo'].$res_modal->fields['codigo'].$res_modal->fields['diagnostico']?></div>       
            <div class="col-md-4"><?=$res_modal->fields['descripcion']?></div>
            <div class="col-md-2"><?=$res_modal->fields['cuie']?></div>
            <div class="col-md-2"><?=$res_modal->fields['id_factura']?></div>
          </div>
          <?$res_modal->MoveNext();
        }?>
      
    </div>
      
        <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
  </div>
</div>
<!--modal-->
  <tr <?=$att?>>          
    <td><?=$result->fields['afidni']?></td>
    <td><?=$result->fields['afiapellido']?></td>
    <td><?=$result->fields['afinombre']?></td>
    <td align="center"><?=fecha($result->fields['afifechanac'])?></td> 
    <td align="center"><?=$result->fields['afisexo']?></td>
    <td align="center">
    <botton data-toggle="modal" data-target="#myModal<?=$id_smiafiliados?>"><IMG src='../../imagenes/linea_tiempo.png' height='30' width='30' border='0'></botton>
    </td>
  </tr>
  
  <?$result->MoveNext();
    }?>
 </table>
 </form>
 <?=fin_pagina();// aca termino ?>