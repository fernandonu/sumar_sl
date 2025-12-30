<table id="section11">
    <!-- ENFERMEDADES - 766/935  -->   
    <tr>
        <td class="border2" id="defectos_congenitos">
            <table class="inside">
                <tr> <td valign="top" colspan="2"> <span class="title2">DEFECTOS CONG&Eacute;NITOS</span> </td> </tr>    
                <tr>
                    <td colspan="2"><label class="title2 top-5" for="defectos_congenitos_no">no</label>
                        <input class="black" type="radio" tabindex="766" name="var_0335" value="A" title="variable: <Defectos cong&eacute;nitos>, valor:<no>, id:<0335_A>"
                               id="defectos_congenitos_no" <?=($ficha->getVar0335()=='A')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><input class="yellow" type="radio" tabindex="767" name="var_0335" value="B" title="variable: <Defectos cong&eacute;nitos>, valor:<menor>, id:<0335_B>"
                               id="defectos_congenitos_menor" <?=($ficha->getVar0335()=='B')?$checked:'' ?> >
                        <label class="title2 block" for="defectos_congenitos_menor">menor</label>
                    </td>
                    <td><input class="yellow" type="radio" tabindex="768" name="var_0335" value="C" title="variable: <Defectos cong&eacute;nitos>, valor:<mayor>, id:<0335_C>"
                               id="defectos_congenitos_mayor" <?=($ficha->getVar0335()=='C')?$checked:'' ?> >
                        <label class="title2 block" for="defectos_congenitos_mayor">mayor</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"> <label class="title3 block">C&oacute;digo</label>
                        <input type="text" class="number width3 alerta auxiliar" maxlength="3" value="<?=$ficha->getVar0368() ?>" name="var_0368" title="variable: <Defectos cong&eacute;nitos - C&oacute;digo>, valor:<c&oacute;digo>, id:<0368>"
                               tabindex="769" data-tabla="ANOMALIAS CONGENITAS" id="rn_defectos_congenitos_codigo">
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2" id="rn_enfermedades">
            <table class="inside">
                <tr> <td rowspan="5"><img src="css/enfermedades.jpg" /></td></tr>    
                <tr>
                    <td style="width:26%"><label class="title2 block" for="rn_enfermedades_ninguna" style="text-align:right">ninguna</label>
                    </td>
                    <td colspan="2" style="text-align: left; padding-left: 6px;">
                        <input class="black fleft" type="radio" tabindex="770" name="var_0336" value="A" title="variable: <Enfermedades RN>, valor:<ninguna>, id:<0336_A>"
                               id="rn_enfermedades_ninguna" <?=($ficha->getVar0336()=='A')?$checked:'' ?> >
                        <label class="title2 top-5" for="rn_enfermedades_1omas">1 o m&aacute;s</label>
                        <input class="yellow" type="radio" tabindex="771" name="var_0336" value="B" title="variable: <Enfermedades RN>, valor:<1 o m&aacute;s>, id:<0336_B>"
                               id="rn_enfermedades_1omas" <?=($ficha->getVar0336()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><span class="title3">c&oacute;digo</span>
                        <input class="number width2 alerta auxiliar" maxlength="2" value="<?=$ficha->getVar0337() ?>" name="var_0337" title="variable: <C&oacute;digo Enfermedad RN 1>, valor:<c&oacute;digo 1>, id:<0337>"
                               id="rn_enfermedades_codigo1" tabindex="772" type="text" data-tabla="PATOLOGIAS NEONATALES">
                    </td>
                    <td><input type="text" class="width3" style="width:100%; font-weight:normal; font-size:9px;" maxlength="50" value="<?=$ficha->getVar0338() ?>" name="var_0338" title="variable: <Notas Enfermedades RN 1>, valor:<notas 1>, id:<0338>"
                               id="rn_enfermedades_notas1" tabindex="773">
                    </td>
                </tr>
                <tr>
                    <td><input class="number width2 alerta auxiliar" maxlength="2" value="<?=$ficha->getVar0339() ?>" name="var_0339" title="variable: <C&oacute;digo Enfermedad RN 2>, valor:<c&oacute;digo 2>, id:<0339>"
                               id="rn_enfermedades_codigo2" tabindex="774"
                               type="text" data-tabla="PATOLOGIAS NEONATALES">
                    </td>
                    <td><input type="text" class="width3" style="width:100%; font-weight:normal; font-size:9px;" maxlength="50" value="<?=$ficha->getVar0340() ?>" name="var_0340" title="variable: <Notas Enfermedades RN 2>, valor:<notas 2>, id:<0340>"
                               id="rn_enfermedades_notas2" tabindex="775">
                    </td>
                </tr>
                <tr>
                    <td><input class="number width2 alerta auxiliar" maxlength="2" value="<?=$ficha->getVar0341() ?>" name="var_0341" title="variable: <C&oacute;digo Enfermedad RN 3>, valor:<c&oacute;digo 3>, id:<0341>"
                               id="rn_enfermedades_codigo3" tabindex="776"
                               type="text" data-tabla="PATOLOGIAS NEONATALES"></td>
                    <td><input type="text" class="width3" style="width:100%; font-weight:normal; font-size:9px;" maxlength="50" value="<?=$ficha->getVar0342() ?>" name="var_0342" title="variable: <Notas Enfermedades RN 3>, valor:<notas 3>, id:<0342>"
                               id="rn_enfermedades_notas3" tabindex="777">
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2" id="rn_vih">
            <table class="inside">
                <tr> <td valign="top" colspan="2"> <span class="title2">VIH en RN</span> </td> </tr>     
                <tr>
                    <td><span class="title3">Expuesto</span> </td>
                    <td><span class="title3">Tto.</span> </td>
                </tr>
                <tr>
                    <td><label class="title2 top-5" for="rn_vih_expuesto_no">no</label>
                        <input class="black" type="radio" tabindex="778" name="var_0440" value="A" title="variable: <VIH en RN - Expuesto>, valor:<no>, id:<0440_A>"
                               id="rn_vih_expuesto_no" <?=($ficha->getVar0440()=='A')?$checked:'' ?> >
                    </td>
                    <td><label class="title2 top-5" for="rn_vih_tratamiento_no">no</label>
                        <input class="black" type="radio" tabindex="781" name="var_0441" value="A" title="variable: <VIH en RN - Tratamiento>, valor:<no>, id:<0441_A>"
                               id="rn_vih_tratamiento_no" <?=($ficha->getVar0441()=='A')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title2 top-5" for="rn_vih_expuesto_si">si</label>
                        <input class="yellow" type="radio" tabindex="779" name="var_0440" value="B" title="variable: <VIH en RN - Expuesto>, valor:<si>, id:<0440_B>"
                               id="rn_vih_expuesto_si" <?=($ficha->getVar0440()=='B')?$checked:'' ?> >
                    </td>
                    <td><label class="title2 top-5" for="rn_vih_tratamiento_si">si</label>
                        <input class="black" type="radio" tabindex="782" name="var_0441" value="B" title="variable: <VIH en RN - Tratamiento>, valor:<si>, id:<0441_B>"
                               id="rn_vih_tratamiento_si" <?=($ficha->getVar0441()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title2 top-5" for="rn_vih_expuesto_sd">s/d</label>
                        <input class="yellow" type="radio" tabindex="780" name="var_0440" value="C" title="variable: <VIH en RN - Expuesto>, valor:<s/d>, id:<0440_C>"
                               id="rn_vih_expuesto_sd" <?=($ficha->getVar0440()=='C')?$checked:'' ?> >
                    </td>
                    <td><label class="title2 top-5" for="rn_vih_tratamiento_sd">s/d</label>
                        <input class="yellow" type="radio" tabindex="783" name="var_0441" value="D" title="variable: <VIH en RN - Tratamiento>, valor:<s/d>, id:<0441_D>"
                               id="rn_vih_tratamiento_sd" <?=($ficha->getVar0441()=='D')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><label class="title2 top-5" for="rn_vih_tratamiento_nc">n/c</label>
                        <input class="black" type="radio" tabindex="784" name="var_0441" value="C" title="variable: <VIH en RN - Tratamiento>, valor:<n/c>, id:<0441_C>"
                               id="rn_vih_tratamiento_nc" <?=($ficha->getVar0441()=='C')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2" id="rn_tamizaje_neonatal">
            <table class="inside">
                <tr> <td colspan="6" class="alignleft"> <span style="margin-left: 10px" class="title1">TAMIZAJE NEONATAL</span> </td> </tr>    
                <tr><td rowspan="5" > 
                        <table id="tablevdrl" >
                            <tr>
                                <td colspan="2" style="width: 40px;"><span class="title3">VDRL</span> </td>
                                <td><span class="title3">Tratamiento</span> </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                                <td><label class="title2 top-5" for="rn_vdrl_tratamiento_no">no</label>
                                    <input class="yellow" type="radio" tabindex="788" name="var_0412" value="A" title="variable: <TTO. VDRL>, valor:<no>, id:<0412_A>"
                                           id="rn_vdrl_tratamiento_no" <?=($ficha->getVar0412()=='A')?$checked:'' ?> >
                                </td>
                            </tr>                
                            <tr>
                                <td><label class="title1 top-5" for="rn_vdrl_negativo">-</label></td>
                                <td>
                                    <input class="black" type="radio" tabindex="785" name="var_0343" value="A" title="variable: <RN Tamizaje - VDRL>, valor:<->, id:<0343_A>"
                                           id="rn_vdrl_negativo" <?=($ficha->getVar0343()=='A')?$checked:'' ?> >
                                </td>
                                <td><label class="title2 top-5" for="rn_vdrl_tratamiento_si">si</label>
                                    <input class="black" type="radio" tabindex="789" name="var_0412" value="B" title="variable: <TTO. VDRL>, valor:<si>, id:<0412_B>"
                                           id="rn_vdrl_tratamiento_si" <?=($ficha->getVar0412()=='B')?$checked:'' ?> >
                                </td>
                            </tr>
                            <tr>
                                <td><label class="title1 top-5" for="rn_vdrl_positivo">+</label></td>
                                <td>
                                    <input class="yellow" type="radio" tabindex="786" name="var_0343" value="B" title="variable: <RN Tamizaje - VDRL>, valor:<+>, id:<0343_B>"
                                           id="rn_vdrl_positivo" <?=($ficha->getVar0343()=='B')?$checked:'' ?> >
                                </td>
                                <td><label class="title2 top-5" for="rn_vdrl_tratamiento_nc">n/c</label>
                                    <input class="black" type="radio" tabindex="790" name="var_0412" value="C" title="variable: <TTO. VDRL>, valor:<n/c>, id:<0412_C>"
                                           id="rn_vdrl_tratamiento_nc" <?=($ficha->getVar0412()=='C')?$checked:'' ?> >
                                </td>
                            </tr>
                            <tr>
                                <td style="width:23px;padding-left:3px;"><label class="title3 block" for="rn_vdrl_nohizo">no se hizo</label></td>
                                <td>
                                    <input class="yellow" type="radio" tabindex="787" name="var_0343" value="C" title="variable: <RN Tamizaje - VDRL>, valor:<no se hizo>, id:<0343_C>"
                                           id="rn_vdrl_nohizo" <?=($ficha->getVar0343()=='C')?$checked:'' ?> >
                                </td>
                                <td><label class="title2 top-5" for="rn_vdrl_tratamiento_sd">s/d</label>
                                    <input class="yellow" type="radio" tabindex="791" name="var_0412" value="D" title="variable: <TTO. VDRL>, valor:<s/d>, id:<0412_D>"
                                           id="rn_vdrl_tratamiento_sd" <?=($ficha->getVar0412()=='D')?$checked:'' ?> >
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>&nbsp;</td>
                    <td style="width: 32px;"><span class="title3">TSH</span></td>
                    <td style="width: 32px;"><span class="title3">Hbpat&iacute;a</span></td>
                    <td style="width: 32px;"><span class="title3">Bilirrub</span></td>
                    <td style="width: 32px;"><span class="title3">Toxo lgM</span></td>
                </tr>
                <tr><td><label class="title1 top-5">-</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="792" name="var_0344" value="A" title="variable: <RN Tamizaje - TSH>, valor:<->, id:<0344_A>"
                               id="rn_tsh_negativo" <?=($ficha->getVar0344()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="black" type="radio" tabindex="795" name="var_0345" value="A" title="variable: <RN Tamizaje - Hbpat&iacute;a>, valor:<->, id:<0345_A>"
                               id="rn_hbpatia_negativo" <?=($ficha->getVar0345()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="black" type="radio" tabindex="798" name="var_0346" value="A" title="variable: <RN Tamizaje - Bilirrubina>, valor:<->, id:<0346_A>"
                               id="rn_bilirrubina_negativo" <?=($ficha->getVar0346()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="black" type="radio" tabindex="801" name="var_0347" value="A" title="variable: <RN Tamizaje - Toxo IgM>, valor:<->, id:<0347_A>"
                               id="rn_toxo_negativo" <?=($ficha->getVar0347()=='A')?$checked:'' ?> >
                    </td>
                </tr>
                <tr><td><label class="title1 top-5">+</label></td>
                    <td>
                        <input class="yellow" type="radio" tabindex="793" name="var_0344" value="B" title="variable: <RN Tamizaje - TSH>, valor:<+>, id:<0344_B>"
                               id="rn_tsh_positivo" <?=($ficha->getVar0344()=='B')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="796" name="var_0345" value="B" title="variable: <RN Tamizaje - Hbpat&iacute;a>, valor:<+>, id:<0345_B>"
                               id="rn_hbpatia_positivo" <?=($ficha->getVar0345()=='B')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="799" name="var_0346" value="B" title="variable: <RN Tamizaje - Bilirrubina>, valor:<+>, id:<0346_B>"
                               id="rn_bilirrubina_positivo" <?=($ficha->getVar0346()=='B')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="802" name="var_0347" value="B" title="variable: <RN Tamizaje - Toxo IgM>, valor:<+>, id:<0347_B>"
                               id="rn_toxo_positivo" <?=($ficha->getVar0347()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr><td><label class="title3">no se hizo</label></td>
                    <td>
                        <input class="yellow" type="radio" tabindex="794" name="var_0344" value="C" title="variable: <RN Tamizaje - TSH>, valor:<no se hizo>, id:<0344_C>"
                               id="rn_tsh_nohizo" <?=($ficha->getVar0344()=='C')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="black" type="radio" tabindex="797" name="var_0345" value="C" title="variable: <RN Tamizaje - Hbpat&iacute;a>, valor:<no se hizo>, id:<0345_C>"
                               id="rn_hbpatia_nohizo" <?=($ficha->getVar0345()=='C')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="black" type="radio" tabindex="800" name="var_0346" value="C" title="variable: <RN Tamizaje - Bilirrubina>, valor:<no se hizo>, id:<0346_C>"
                               id="rn_bilirrubina_nohizo" <?=($ficha->getVar0346()=='C')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="803" name="var_0347" value="C" title="variable: <RN Tamizaje - Toxo IgM>, valor:<no se hizo>, id:<0347_C>"
                               id="rn_toxo_nohizo" <?=($ficha->getVar0347()=='C')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2" id="rn_meconio">
            <table class="inside">
                <tr><td colspan="2"><span class="title3">MECONIO <br> 1&deg; d&iacute;a</span></td> </tr>
                <tr><td>
                        <label class="title2 top-5" for="rn_meconio_no">no</label> 
                        <input class="yellow" type="radio" tabindex="804" name="var_0348" value="A" title="variable: <RN Tamizaje - Meconio 1er. d&iacute;a>, valor:<no>, id:<0348_A>"
                               id="rn_meconio_no" <?=($ficha->getVar0348()=='A')?$checked:'' ?> >                        
                    </td>    
                </tr>
                <tr>
                    <td>
                        <label class="title2 top-5" for="rn_meconio_si">si</label> 
                        <input class="black" type="radio" tabindex="805" name="var_0348" value="B" title="variable: <RN Tamizaje - Meconio 1er. d&iacute;a>, valor:<si>, id:<0348_B>"
                               id="rn_meconio_si" <?=($ficha->getVar0348()=='B')?$checked:'' ?> >                        
                    </td>    
                </tr>
            </table>            
        </td>
        <td class="border2" id="rn_puerperio">
            <table class="inside">
                <tr class="borderB2"><td colspan="8">
                        <table id="tablapuerperio">
                            <thead>
                                <tr><td colspan="7"><h6>PUERPERIO</h6></td></tr>
                                <tr>
                                    <th style="width:30px;">d&iacute;a</th>
                                    <th style="width:30px;">hora</th>
                                    <th style="width:40px;">T&deg;C</th>
                                    <th style="width:80px;">PA</th>
                                    <th style="width:40px;">pulso</th>
                                    <th style="width:50px;">invol.uter.</th>
                                    <th style="width:40px;">loquios</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                <?php
                                $k = 0;
                                $tab = 806;
                                if(count($controlesPuerperio)>0){
                                    $k = count($controlesPuerperio)-1;
                                    for ($i = 0; $i <= $k; $i++ ){
                                        $puerperio->construirResult($controlesPuerperio[$i]);                    
/*                                if($ficha->getIdHcPerinatal()){
                                    $controlesPuerperio = new ControlPuerperio;
                                    $controles = $controlesPuerperio->getPuerperioByHcPerinatal($ficha->getIdHcPerinatal());
                                    $k = count($controles)-1;
                                    for ($i = 0; $i <= $k; $i++ ){
                                        $puerperio = new ControlPuerperio();
                                        $puerperio->construirResult($controles[$i]);*/
                                    ?>
                                    <tr>
                                        <td><input type="hidden" name="puerperio[id_control_puerperio][]" value="<?=$puerperio->getIdControlPuerperio() ?>" /> 
                                            <input type="text" name="puerperio[var_0349][]" maxlength="2" class="number" value="<?=$puerperio->getVar0349() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$i+1 ?>>, valor:<d&iacute;a>, id:<0349>"/>
                                        </td>
                                        <td><input type="text" name="puerperio[var_0350][]" maxlength="2" min="00" max="23" class="number" value="<?=$puerperio->getVar0350() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$i+1 ?>>, valor:<hora>, id:<0350>"/> </td>
                                        <td><input type="text" name="puerperio[var_0351][]" maxlength="3" class="width2 comappr number" value="<?=$puerperio->getVar0351() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$i+1 ?>>, valor:<temp grados>, id:<0351>"/> </td>
                                        <td><input type="text" name="puerperio[var_0352][]" maxlength="3" class="number" style="width:50%;float:left;" value="<?=$puerperio->getVar0352() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$i+1 ?>>, valor:<PA sist&oacute;lica>, id:<0352>">
                                             <input type="text" name="puerperio[var_0406][]" maxlength="3" class="number" style="width:50%; float:right;" value="<?=$puerperio->getVar0406() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$i+1 ?>>, valor:<PA diast&oacute;lica>, id:<0406>">                                             
                                        </td>
                                        <td><input type="text" name="puerperio[var_0353][]" maxlength="3" class="width2 number" value="<?=$puerperio->getVar0353() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$i+1 ?>>, valor:<pulso>, id:<0353>"> </td>
                                        <td><input type="text" name="puerperio[var_0354][]" maxlength="4" class="width2 aligncenter" value="<?=$puerperio->getVar0354() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$i+1 ?>>, valor:<Invol. Uter.>, id:<0354>"> </td>
                                        <td><input type="text" name="puerperio[var_0355][]" maxlength="4" class="width2 aligncenter" value="<?=$puerperio->getVar0355() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$i+1 ?>>, valor:<loquios>, id:<0355>"> </td>
                                    </tr>            
                                    <?php
                                    }
                                    //$tab += count($controles);
                                    $k = count($controlesPuerperio);
                                }  
                                for ($j = $k ; $j <= 2; $j++){
                                ?>
                                    <tr>
                                        <td> <input type="text" name="puerperio[var_0349][]" maxlength="2" class="number" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$j+1 ?>>, valor:<d&iacute;a>, id:<0349>"/>  </td>
                                        <td> <input type="text" name="puerperio[var_0350][]" maxlength="2" min="00" max="23" class="number" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$j+1 ?>>, valor:<hora>, id:<0350>"/> </td>
                                        <td> <input type="text" name="puerperio[var_0351][]" maxlength="3" class="width2 comappr number" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$j+1 ?>>, valor:<temp grados>, id:<0351>"/> </td>
                                        <td> <input type="text" name="puerperio[var_0352][]" maxlength="3" class="number" style="width:50%;float:left;" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$j+1 ?>>, valor:<PA sist&oacute;lica>, id:<0352>">
                                             <input type="text" name="puerperio[var_0406][]" maxlength="3" class="number" style="width:50%; float:right;" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$j+1 ?>>, valor:<PA diast&oacute;lica>, id:<0406>"> 
                                        </td>
                                        <td> <input type="text" name="puerperio[var_0353][]" maxlength="3" class="width2 number" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$j+1 ?>>, valor:<pulso>, id:<0353>"></td>
                                        <td> <input type="text" name="puerperio[var_0354][]" maxlength="4" class="width2 aligncenter" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$j+1 ?>>, valor:<Invol. Uter.>, id:<0354>"></td>
                                        <td> <input type="text" name="puerperio[var_0355][]" maxlength="4" class="width2 aligncenter" tabindex="<?php echo $tab++ ?>" title="variable: <Control post parto <?=$j+1 ?>>, valor:<loquios>, id:<0355>"></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        
                    </td>
                </tr>
                <tr>
                    <td style="width: 70px;"><span class="title2">Antirubeola post parto</span></td>
                    <td><input class="yellow" type="radio" tabindex="830" name="var_0367" value="A" title="variable: <Antirub&eacute;ola postparto>, valor:<no>, id:<0367_A>"
                               id="antirubeola_postparto_no" <?=($ficha->getVar0367()=='A')?$checked:'' ?> >
                        <label class="title3 block" for="antirubeola_postparto_no">no</label>
                    </td>
                    <td><input class="black" type="radio" tabindex="831" name="var_0367" value="B" title="variable: <Antirub&eacute;ola postparto>, valor:<si>, id:<0367_B>"
                               id="antirubeola_postparto_si" <?=($ficha->getVar0367()=='B')?$checked:'' ?> >
                        <label class="title3 block" for="antirubeola_postparto_si">si</label>
                    </td>
                    <td class="borderright">
                        <input class="black" type="radio" tabindex="832" name="var_0367" value="C" title="variable: <Antirub&eacute;ola postparto>, valor:<vigente>, id:<0367_C>"
                               id="antirubeola_postparto_nc" <?=($ficha->getVar0367()=='C')?$checked:'' ?> >
                        <label class="title3 block" for="antirubeola_postparto_nc">n/c</label>
                    </td>
                    <td style="width: 70px;"><span class="title2">&gamma;globulina anti D</span></td>
                    <td><input class="yellow" type="radio" tabindex="833" name="var_0411" value="A" title="variable: <Gamma globulina anti D - egreso>, valor:<no>, id:<0411_A>"
                               id="gammaglobulina_egreso_no" <?=($ficha->getVar0411()=='A')?$checked:'' ?> >
                        <label class="title3 block" for="gammaglobulina_egreso_no">no</label>
                    </td>
                    <td><input class="black" type="radio" tabindex="834" name="var_0411" value="B" title="variable: <Gamma globulina anti D - egreso>, valor:<si>, id:<0411_B>"
                               id="gammaglobulina_egreso_si" <?=($ficha->getVar0411()=='B')?$checked:'' ?> >
                        <label class="title3 block" for="gammaglobulina_egreso_si">si</label>
                    </td>
                    <td><input class="black" type="radio" tabindex="835" name="var_0411" value="C" title="variable: <Gamma globulina anti D - egreso>, valor:<n/c>, id:<0411_C>"
                               id="gammaglobulina_egreso_nc" <?=($ficha->getVar0411()=='C')?$checked:'' ?> >
                        <label class="title3 block" for="gammaglobulina_egreso_nc">n/c</label>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>