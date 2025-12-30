<div id="consultas_antenatales">
    <h6><span style="margin-left:10px">CONSULTAS ANTENATALES</span></h6>
    <table id="table_consultas">
        <thead>
            <tr>
                <th style="width:76px">Efector</th>
                <th style="width:126px">Prestaci&oacute;n</th>
                <th style="width:80px">d&iacute;a-mes-a&ntilde;o</th>
                <th style="width:40px">Edad gest.</th>
                <th style="width:40px">Peso</th>
                <th style="width:70px">PA</th>
                <th style="width:45px">Altura uterina</th>
                <th style="width:50px">Presen taci&oacute;n</th>
                <th style="width:40px">FCF (lpm)</th>
                <th style="width:40px">Movim. Fetales</th>
                <th style="width:35px">Protei nuria</th>
                <th style="width:200px">Signos de alarma, ex&aacute;menes, tratamientos</th>
                <th style="width:50px">Iniciales T&eacute;cnico</th>
                <th style="width:80px">Pr&oacute;xima cita</th>
                <th style="width:20px"></th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $k = 0;
            $idx = 260;

                if(count($consultas)>0){
                    $k = count($consultas)-1;
                    for ($i = 0; $i <= $k; $i++ ){
                        // despues cambiar por facturado
                        $facturado = ($consultas[$i]['facturado']=='')?'':'disabled="disabled"';
                        $consultaPrenatal = new ConsultaPrenatal();
                        $consultaPrenatal->construirResult($consultas[$i]);
                        
                        //buscar codigos segun efector
                        // $codigos = getCodigosByEfector($consultaPrenatal->getIdEfector());
                        // $codigos = array(array('codigo'=>'', 'descripcion'=>'S/D'));

            ?>

                <?php if ($consultas[$i]['facturado']==''){ ?>
                    
                <tr class="trconsulta">
                    <td  style="border-left:1px solid #666;width:76px;position:relative">
                        <input type="hidden" name="consulta[id_control_prenatal][]" value="<?=$consultaPrenatal->getIdControlPrenatal() ?>"/>
                        <button type="button" class="efectorselectBtn" style="top:0 !important"><img src="<?php echo "../../imagenes/lupa.gif" ?>" ></button>
                        <input value="<?=$consultaPrenatal->getIdEfector() ?>" <?=$facturado ?> name="consulta[id_efector][]" id="cons_efector_<?=$i ?>" tabindex="<?=$idx++ ?>" class="efectorselect" >
                    </td>
                    <td  style="width:125px;">
                        <input type="hidden" name="consulta[id_nomenclador][]" value="<?=$consultaPrenatal->getIdNomenclador() ?>"/>
                       
                        <select id="codigo_<?=$i ?>" <?=$facturado ?> name="consulta[codigo][]" class="codigoNomenclador" style="text-transform:none;width:70px;float:left;" tabindex="<?=$idx++ ?>" >
                            <option value="">S/D</option>
                            <?php
                            if($codigos){
                                foreach ($codigos as $cod) {
                                    $sel = ($consultas[$i]['codigo']==$cod['codigo'])?'selected="selected"':'';
                                    echo '<option value="'.$cod['codigo'].'" '.$sel.' > '. $cod['codigo'].'- '.$cod['descripcion'] .'</option>';        
                                } 
                            }          
                            ?>
                        </select>

                        <input type="hidden" id="diagnosticovalue_<?=$i ?>" value="<?=$consultas[$i]['diagnostico'] ?>"/>
                        <select id="diagnostico_<?=$i ?>" <?=$facturado ?> name="consulta[diagnostico][]" class="diagnosticoNomenclador" style="text-transform:none;width:55px;float:right;" tabindex="<?=$idx++ ?>" >
                            <option value="">S/D</option>
                            <?php 
                                if ($consultas[$i]['facturado']!='') {
                                    $facturadoDiagnostico = $consultas[$i]['diagnostico'];
                                    $facturadoDiagnosticoTexto = $consultas[$i]['diagnostico'];
                                }
                            ?>
                            <option value="<?=$facturadoDiagnostico ?>" ><?=$facturadoDiagnosticoTexto?></option>
                        </select>
                        <input type="hidden" name="consulta[nuevo][]" value="0"/>
                        <input type="hidden" name="consulta[trazadora_id_emb][]" value="<?=$consultaPrenatal->getTrazadoraIdEmb() ?>"/>
                        <input type="hidden" name="consulta[facturacion_id_prestacion][]" value="<?=$consultaPrenatal->getFacturacionIdPrestacion() ?>"/>
                        <input type="hidden" name="consulta[facturado][]" value="<?=$consultas[$i]['facturado'] ?>"/>
                    </td>
                    <td style="width:80px">
                        <input value="<?=FechaView($consultaPrenatal->getFechaConsulta()) ?>" <?=$facturado ?> name="consulta[fecha_consulta][]" id="cons_fecha_<?=$i ?>" class="datepicker" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<fecha>, id:<0116-0117-0118>" >
                    </td>
                    <td style="width:40px">
                        <input value="<?=$consultaPrenatal->getVar0119() ?>" <?=$facturado ?> name="consulta[var_0119][]" id="cons_edad_gestacional_<?=$i ?>" min="1" max="40" class="number edadgestacional" type="text" maxlength="2" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<edad gestacional>, id:<0119>">
                    </td>
                    <td style="width:40px">
                        <input value="<?=$consultaPrenatal->getVar0120() ?>" <?=$facturado ?> name="consulta[var_0120][]" id="cons_peso_<?=$i ?>" type="text" maxlength="4" tabindex="<?=$idx++ ?>" class="number comacons checkvalidacion pesoembarazo" data-tabla="PESOFET" title="variable: <Consulta <?=$i ?>>, valor:<peso>, id:<0120>" style="letter-spacing:2px;" >
                    </td>
                    <td style="width:70px" >
                        <input value="<?=$consultaPrenatal->getVar0121() ?>" <?=$facturado ?> name="consulta[var_0121][]" id="cons_pa_sistolica_<?=$i ?>" min="90" max="135" type="text" maxlength="3" class="number noreq presionarterial" title="variable: <Consulta <?=$i ?>>, valor:<PA Sist&oacute;lica>, id:<0121>"
                               tabindex="<?=$idx++ ?>" style="width:50%;float:left;">
                        <input value="<?=$consultaPrenatal->getVar0394() ?>" <?=$facturado ?> name="consulta[var_0394][]" id="cons_pa_diastolica_<?=$i ?>" min="55" max="95" type="text" maxlength="3" class="number noreq presionarterial" title="variable: <Consulta <?=$i ?>>, valor:<PA Diast&oacute;lica>, id:<0394>"
                               tabindex="<?=$idx++ ?>" style="width:50%; float:right;">
                    </td>
                    <td style="width:45px">
                        <input value="<?=$consultaPrenatal->getVar0122() ?>" <?=$facturado ?> name="consulta[var_0122][]" id="cons_alturauterina_<?=$i ?>" type="text" maxlength="2" class="number checkvalidacion" data-tabla="ALTUTER" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<altura uterina>, id:<0122>" >
                    </td>
                    <td style="width:50px"><select name="consulta[var_0123][]" <?=$facturado ?> id="cons_presentacion_<?=$i ?>" style="text-transform:none; " tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<presentaci&oacute;n>, id:<0123>" >
                            <option value="">&nbsp;</option>
                            <option value="CEF" <?=($consultaPrenatal->getVar0123()=='CEF')?$selected:'' ?>>CEF - Cef&aacute;lica</option>
                            <option value="POD" <?=($consultaPrenatal->getVar0123()=='POD')?$selected:'' ?>>POD - Pod&aacute;lica</option>
                            <option value="TRA" <?=($consultaPrenatal->getVar0123()=='TRA')?$selected:'' ?>>TRA - Transversa</option>
                            <option value="OBL" <?=($consultaPrenatal->getVar0123()=='OBL')?$selected:'' ?>>OBL - Oblicua</option>
                            <option value="IND" <?=($consultaPrenatal->getVar0123()=='IND')?$selected:'' ?>>IND - Indiferente</option>
                        </select>
                    <td style="width:40px">
                        <input value="<?=$consultaPrenatal->getVar0124() ?>" <?=$facturado ?> name="consulta[var_0124][]" id="cons_fcf" class="aligncenter number" type="text" maxlength="3" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<FCF>, id:<0124>" >
                    </td>
                    <td style="width:40px">
                        <input value="<?=$consultaPrenatal->getVar0125() ?>" <?=$facturado ?> name="consulta[var_0125][]" id="cons_movimientos_fetales_<?=$i ?>" class="aligncenter" type="text" maxlength="1" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<movimientos fetales>, id:<0125>" >
                    </td>
                    <td style="width:35px">
                        <input value="<?=$consultaPrenatal->getVar0393() ?>" <?=$facturado ?> name="consulta[var_0393][]" id="cons_proteinuria_<?=$i ?>" class="aligncenter" type="text" maxlength="1" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<proteinuria>, id:<0393>" >                        
                    </td>
                    <td style="width:200px">
                        <input value="<?=$consultaPrenatal->getVar0126() ?>" <?=$facturado ?> name="consulta[var_0126][]" id="cons_signos_examenes_tratamientos_<?=$i ?>" type="text" maxlength="100" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<signos de alarma>, id:<0126>" >
                    </td>
                    <td style="width:50px">
                        <input value="<?=$consultaPrenatal->getVar0127() ?>" <?=$facturado ?> name="consulta[var_0127][]" id="cons_iniciales_tecnicos_<?=$i ?>" type="text" maxlength="6" class="aligncenter" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<t&eacute;cnico>, id:<0127>" >
                    </td>
                    <td style="width:80px"> 
                        <input value="<?=$consultaPrenatal->getFechaProximaConsulta() ?>" <?=$facturado ?> name="consulta[fecha_proxima][]" id="cons_proxima_cita_<?=$i ?>" class="datepicker" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<fecha>, id:<0128-0129>">
                    </td>
                    <td style="border-right: 1px solid #666; width:20px"> 
                       <!-- <button type="button" class="delconsulta" <?=$facturado ?> title="Eliminar consulta" ><img src="<?php echo "../../imagenes/salir2.gif" ?>" ></button>-->
                    </td>

                </tr>
                

                <!-- CONSULTAS FACTURADAS -->
                <?php }else{
                    ?>
                    
                    <tr class="trconsulta">
                    <td  style="border-left:1px solid #666;width:76px;position:relative">
                        <button type="button" class="efectorselectBtn" style="top:0 !important"><img src="<?php echo "../../imagenes/lupa.gif" ?>" ></button>
                        <input value="<?=$consultaPrenatal->getIdEfector() ?>" <?=$facturado ?> tabindex="<?=$idx++ ?>" class="efectorselect" >
                    </td>
                    <td  style="width:125px;">
                        <input type="hidden"  value="<?=$consultaPrenatal->getIdNomenclador() ?>"/>
                       
                        <select id="codigo_<?=$i ?>" <?=$facturado ?>  class="codigoNomenclador" style="text-transform:none;width:70px;float:left;" tabindex="<?=$idx++ ?>" >
                            <option value="">S/D</option>
                            <?php
                            if($codigos){
                                foreach ($codigos as $cod) {
                                    $sel = ($consultas[$i]['codigo']==$cod['codigo'])?'selected="selected"':'';
                                    echo '<option value="'.$cod['codigo'].'" '.$sel.' > '. $cod['codigo'].'- '.$cod['descripcion'] .'</option>';        
                                } 
                            }          
                            ?>
                        </select>

                        <input type="hidden" id="diagnosticovalue_<?=$i ?>" value="<?=$consultas[$i]['diagnostico'] ?>"/>
                        <select id="diagnostico_<?=$i ?>" <?=$facturado ?> class="diagnosticoNomenclador" style="text-transform:none;width:55px;float:right;" tabindex="<?=$idx++ ?>" >
                            <option value="">S/D</option>
                            <?php 
                                if ($consultas[$i]['facturado']!='') {
                                    $facturadoDiagnostico = $consultas[$i]['diagnostico'];
                                    $facturadoDiagnosticoTexto = $consultas[$i]['diagnostico'];
                                }
                            ?>
                            <option value="<?=$facturadoDiagnostico ?>" ><?=$facturadoDiagnosticoTexto?></option>
                        </select>
                        <input type="hidden"  value="0"/>
                        <input type="hidden"  value="<?=$consultaPrenatal->getTrazadoraIdEmb() ?>"/>
                        <input type="hidden"  value="<?=$consultaPrenatal->getFacturacionIdPrestacion() ?>"/>
                        <input type="hidden"  value="<?=$consultas[$i]['facturado'] ?>"/>
                    </td>
                    <td style="width:80px">
                        <input value="<?=FechaView($consultaPrenatal->getFechaConsulta()) ?>" <?=$facturado ?>  id="cons_fecha_<?=$i ?>" class="datepicker" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<fecha>, id:<0116-0117-0118>" >
                    </td>
                    <td style="width:40px">
                        <input value="<?=$consultaPrenatal->getVar0119() ?>" <?=$facturado ?>  id="cons_edad_gestacional_<?=$i ?>" min="1" max="40" class="number" type="text" maxlength="2" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<edad gestacional>, id:<0119>">
                    </td>
                    <td style="width:40px">
                        <input value="<?=$consultaPrenatal->getVar0120() ?>" <?=$facturado ?>  id="cons_peso_<?=$i ?>" type="text" maxlength="4" tabindex="<?=$idx++ ?>" class="number comacons checkvalidacion" data-tabla="PESOFET" title="variable: <Consulta <?=$i ?>>, valor:<peso>, id:<0120>" style="letter-spacing:2px;" >
                    </td>
                    <td style="width:70px" >
                        <input value="<?=$consultaPrenatal->getVar0121() ?>" <?=$facturado ?> id="cons_pa_sistolica_<?=$i ?>" min="90" max="135" type="text" maxlength="3" class="number noreq" title="variable: <Consulta <?=$i ?>>, valor:<PA Sist&oacute;lica>, id:<0121>"
                               tabindex="<?=$idx++ ?>" style="width:50%;float:left;">
                        <input value="<?=$consultaPrenatal->getVar0394() ?>" <?=$facturado ?>  id="cons_pa_diastolica_<?=$i ?>" min="55" max="95" type="text" maxlength="3" class="number noreq" title="variable: <Consulta <?=$i ?>>, valor:<PA Diast&oacute;lica>, id:<0394>"
                               tabindex="<?=$idx++ ?>" style="width:50%; float:right;">
                    </td>
                    <td style="width:45px">
                        <input value="<?=$consultaPrenatal->getVar0122() ?>" <?=$facturado ?> id="cons_alturauterina_<?=$i ?>" type="text" maxlength="2" class="number checkvalidacion" data-tabla="ALTUTER" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<altura uterina>, id:<0122>" >
                    </td>
                    <td style="width:50px"><select  <?=$facturado ?> id="cons_presentacion_<?=$i ?>" style="text-transform:none; " tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<presentaci&oacute;n>, id:<0123>" >
                            <option value="">&nbsp;</option>
                            <option value="CEF" <?=($consultaPrenatal->getVar0123()=='CEF')?$selected:'' ?>>CEF - Cef&aacute;lica</option>
                            <option value="POD" <?=($consultaPrenatal->getVar0123()=='POD')?$selected:'' ?>>POD - Pod&aacute;lica</option>
                            <option value="TRA" <?=($consultaPrenatal->getVar0123()=='TRA')?$selected:'' ?>>TRA - Transversa</option>
                            <option value="OBL" <?=($consultaPrenatal->getVar0123()=='OBL')?$selected:'' ?>>OBL - Oblicua</option>
                            <option value="IND" <?=($consultaPrenatal->getVar0123()=='IND')?$selected:'' ?>>IND - Indiferente</option>
                        </select>
                    <td style="width:40px">
                        <input value="<?=$consultaPrenatal->getVar0124() ?>" <?=$facturado ?>  id="cons_fcf" class="aligncenter number" type="text" maxlength="3" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<FCF>, id:<0124>" >
                    </td>
                    <td style="width:40px">
                        <input value="<?=$consultaPrenatal->getVar0125() ?>" <?=$facturado ?>  id="cons_movimientos_fetales_<?=$i ?>" class="aligncenter" type="text" maxlength="1" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<movimientos fetales>, id:<0125>" >
                    </td>
                    <td style="width:35px">
                        <input value="<?=$consultaPrenatal->getVar0393() ?>" <?=$facturado ?>  id="cons_proteinuria_<?=$i ?>" class="aligncenter" type="text" maxlength="1" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<proteinuria>, id:<0393>" >                        
                    </td>
                    <td style="width:200px">
                        <input value="<?=$consultaPrenatal->getVar0126() ?>" <?=$facturado ?>  id="cons_signos_examenes_tratamientos_<?=$i ?>" type="text" maxlength="100" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<signos de alarma>, id:<0126>" >
                    </td>
                    <td style="width:50px">
                        <input value="<?=$consultaPrenatal->getVar0127() ?>" <?=$facturado ?>  id="cons_iniciales_tecnicos_<?=$i ?>" type="text" maxlength="6" class="aligncenter" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<t&eacute;cnico>, id:<0127>" >
                    </td>
                    <td style="width:80px"> 
                        <input value="<?=$consultaPrenatal->getFechaProximaConsulta() ?>" <?=$facturado ?>  id="cons_proxima_cita_<?=$i ?>" class="datepicker" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<fecha>, id:<0128-0129>">
                    </td>
                    <td style="border-right: 1px solid #666; width:20px"> 
                       <!-- <button type="button" class="delconsulta" <?=$facturado ?> title="Eliminar consulta" ><img src="<?php echo "../../imagenes/salir2.gif" ?>" ></button>-->
                    </td>

                </tr>


                    <?php
                    } ?>                   
                <?php    
                    }
                }else echo '<tr></tr>';       
            
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="15">
                    <?php if ($ficha->getFinalizado() == 0) {?>
                        <button type="button" id="agregar_consultas" title="Agregar mas consultas" tabindex="500">Agregar Consulta</button>
                        <?php if($permisoHospital){ ?>
                            <button type="button" id="consulta_otro_efector" title="Agregar consulta de otro efector" tabindex="499">Consulta de otro efector</button>
                    &nbsp;&nbsp;
                        <?php } 
                    }?>

                </th>
            </tr>
        </tfoot>
    </table>
</div>