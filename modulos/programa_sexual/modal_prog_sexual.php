<!-- Modal linea temporal-->
<div class="modal fade" id="myModal_timeline" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <?
 $string_temp=explode('/', $dato);
 $id=$string_temp[0];
 $entidad_alta=$string_temp[1];
 
 if ($entidad_alta=='na') 
    $q="SELECT afinombre as nombre, afiapellido as apellido, afidni as documento from nacer.smiafiliados where id_smiafiliados=$id";
  else $q="SELECT * from leche.beneficiarios where id_beneficiarios=$id";

$ex_q=sql($q) or fin_pagina();

$nombre=$ex_q->fields['nombre'];
$apellido=$ex_q->fields['apellido'];
$dni=$ex_q->fields['documento'];

 ?>
  
  
  
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Historial de Prestaciones</h4>
         <h3 style="font-weight: bold; color:#3258FA"><?echo $apellido.', '.$nombre.'  ('.$dni.')';?></h3> 
           
        <?if ($entidad_alta=='na')
            $q="SELECT *,fecha_entrega+per_pres_limite as proxima_entrega from programa_sexual.comprobantes
                inner join programa_sexual.prestaciones using (id_comprobante)
                inner join programa_sexual.remedio using (id_remedio)
                left join programa_sexual.validacion_producto using (codigo)
                inner join nacer.efe_conv using (cuie)
                where comprobantes.id_smiafiliados=$id
                order by comprobantes.fecha_entrega DESC";
            
            else $q="SELECT *,fecha_entrega+per_pres_limite as proxima_entrega from programa_sexual.comprobantes
                      inner join programa_sexual.prestaciones using (id_comprobante)
                      inner join programa_sexual.remedio using (id_remedio)
                      left join programa_sexual.validacion_producto using (codigo)
                      inner join nacer.efe_conv using (cuie)
                      where id_beneficiarios=$id
                      order by comprobantes.fecha_entrega DESC";
          $res_q=sql($q) or fin_pagina();
          ?>
        
      </div>
      <div class="modal-body">
       
       <div id="timeline"><div class="row timeline-movement timeline-movement-top"></div>
         
<!--due -->

<div class="row timeline-movement">

    <?while (!$res_q->EOF) {
        $fecha_control=$res_q->fields['fecha_entrega'];
        $fecha_c=explode("-",$fecha_control);
        $dia=$fecha_c['2'];
        $mes=$fecha_c['1'];
             
      ?>
    <!--<div class="timeline-badge">
        <span class="timeline-balloon-date-day"><?=$dia?></span>
        <span class="timeline-balloon-date-month"><? echo "/".$mes?></span>
    </div>-->

    <div class="col-sm-6  timeline-item">
        <div class="row">
            <div class="col-sm-offset-1 col-sm-11">
                <div class="timeline-panel credits">
                    <ul class="timeline-panel-ul">
                        <li><span class="importo"><?echo 'N.Comprobante: '.$res_q->fields['id_comprobante'].' (N.Prest: '.$res_q->fields['id_prestacion'].')';?></span></li>
                        <li><span class="causale" style="font-weight: bold;"><?echo $res_q->fields['cuie'].' - '.$res_q->fields['nombre'];?></span> </li>
                        <li><span class="causale"><a href="#" data-toggle="tooltip" data-placement="right" ><?echo 'Producto:'.$res_q->fields['tipo'].'.'.$res_q->fields['producto'].'.'.$res_q->fields['descripcion']?></a></span> </li>
                        <li><span class="causale"><a href="#" data-toggle="tooltip" data-placement="right" ><?echo 'Cantidad:'.$res_q->fields['cantidad']?></a></span> </li>
                        
                        <li><p><small class="text-muted" style="font-weight: bold;"><i class="glyphicon glyphicon-time"></i> <? echo 'Fecha de Entrega:'.fecha($res_q->fields['fecha_entrega']);?></small></p> </li>
                        
                        <li><p><small class="text-muted" style="font-weight: bold;"><i class="glyphicon glyphicon-time"></i> <? echo 'Fecha Prox. Entrega:'.fecha($res_q->fields['proxima_entrega']);?></small></p> </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    
    <?$res_q->MoveNext();
      if (!$res_q->EOF) {?>
    
    <div class="col-sm-offset-6 col-sm-6  timeline-item">
        <div class="row">
            <div class="col-sm-11">
                <div class="timeline-panel debits">
                    <ul class="timeline-panel-ul">
                      <li><span class="importo"><?echo 'N.Comprobante: '.$res_q->fields['id_comprobante'].' (N.Prest: '.$res_q->fields['id_prestacion'].')';?></span></li>
                        <li><span class="causale" style="font-weight: bold;"><?echo $res_q->fields['cuie'].' - '.$res_q->fields['nombre'];?></span> </li>
                        <li><span class="causale"><a href="#" data-toggle="tooltip" data-placement="right" ><?echo 'Producto:'.$res_q->fields['tipo'].'.'.$res_q->fields['producto'].'.'.$res_q->fields['descripcion']?></a></span> </li>
                        <li><span class="causale"><a href="#" data-toggle="tooltip" data-placement="right" ><?echo 'Cantidad:'.$res_q->fields['cantidad']?></a></span> </li>
                        
                        <li><p><small class="text-muted" style="font-weight: bold;"><i class="glyphicon glyphicon-time"></i> <? echo 'Fecha_comprobante:'.fecha($res_q->fields['fecha_entrega']);?></small></p> </li>
                        
                        <li><p><small class="text-muted" style="font-weight: bold;"><i class="glyphicon glyphicon-time"></i> <? echo 'Fecha Prox. Entrega:'.fecha($res_q->fields['proxima_entrega']);?></small></p> </li> 
                    </ul>
                </div>

            </div>
        </div>
    </div>
  <?}
  $res_q->MoveNext();
    }?>
  </div>

</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
  </div>
</div>