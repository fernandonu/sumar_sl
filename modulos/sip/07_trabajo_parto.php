<table id="section7">
    <tr>
<!-- TRABAJO DE PARTO - 543/661  -->
        <td class="border2 top" id="trabajo_parto">
            <table class="inside">
                <tr class="top">
                    <td><span style='margin-top:10px;' class="title3 block">TRABAJO<br>DE<br>PARTO</span></td> 
                </tr>
                <tr>
                    <td><span class="title3 block">detalles en partograma</span></td> 
                </tr>
                <tr>
                    <td> <label class="title3 block" for="trabajo_parto_detalles_no">no</label> 
                        <input class="yellow" type="radio" tabindex="543" name="var_0206" value="A" title="variable: <Trabajo de parto, detalles>, valor:<no>, id:<0206_A>"
                               id="trabajo_parto_detalles_no" <?=($ficha->getVar0206()=='A')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td> <label style="margin-top: 5px;" class="title3 block" for="trabajo_parto_detalles_si">si</label> 
                         <input class="black" type="radio" tabindex="544" name="var_0206" value="B" title="variable: <Trabajo de parto, detalles>, valor:<si>, id:<0206_B>"
                                id="trabajo_parto_detalles_si" <?=($ficha->getVar0206()=='B')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
<!-- CONTROL TRABAJO DE PARTO - 545  -->
        <td class="border2" id="control_parto">
            <table class="inside" id="tablacontrolparto">
                <thead>
                    <tr>
                        <th style="width:30px;">hora</th>
                        <th style="width:30px;">min</th>
                        <th style="width:50px;">posici&oacute;n de la madre</th>
                        <th style="width:80px;">PA</th>
                        <th style="width:40px;">pulso</th>
                        <th style="width:50px;">contr./10'</th>
                        <th style="width:50px;">dilataci&oacute;n</th>
                        <th style="width:50px;">altura present.</th>
                        <th style="width:50px;">variedad posic.</th>
                        <th style="width:50px;">meconio</th>
                        <th style="width:50px;">FCF/dips.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $k = 0;
                    $tab = 545;
                if(count($controlesParto)>0){
                    $k = count($controlesParto)-1;
                    for ($i = 0; $i <= $k; $i++ ){
                        $controlParto->construirResult($controlesParto[$i]);                    
                        ?>
                        <tr>
                            <td><input type="hidden" name="control_parto[id_control_parto][]" value="<?=$controlParto->getIdControlParto() ?>" name="" />
                                <input type="text" name="control_parto[var_0207][]" maxlength="2" min="0" max="23" class="number" value="<?=$controlParto->getVar0207() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$i+1 ?>>, valor:<hora>, id:<0207>"/>
                            <td><input type="text" name="control_parto[var_0208][]" maxlength="2" min="0" max="59" class="number" value="<?=$controlParto->getVar0208() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$i+1 ?>>, valor:<minutos>, id:<0208>"/> </td>
                            <td><input type="text" name="control_parto[var_0209][]" maxlength="3" class="width2" value="<?=$controlParto->getVar0209() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$i+1 ?>>, valor:<posici&oacute;n>, id:<0209>"/> </td>
                            <td><input type="text" name="control_parto[var_0210][]" maxlength="3" class="aligncenter" value="<?=$controlParto->getVar0210() ?>" tabindex="<?php echo $tab++ ?>" style="width:50%;float:left;" title="variable: <Control en parto <?=$i+1 ?>>, valor:<PA sist&oacute;lica>, id:<0210>">
                                <input type="text" name="control_parto[var_0407][]" maxlength="3" class="aligncenter" value="<?=$controlParto->getVar0407() ?>" tabindex="<?php echo $tab++ ?>" style="width:50%; float:right;" title="variable: <Control en parto <?=$i+1 ?>>, valor:<PA diast&oacute;lica>, id:<0407>"> 
                            </td>
                            <td><input type="text" name="control_parto[var_0392][]" maxlength="3" class="width2 aligncenter" value="<?=$controlParto->getVar0392() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$i+1 ?>>, valor:<Pulso>, id:<0392>"> </td>
                            <td><input type="text" name="control_parto[var_0211][]" maxlength="1" class="width2 aligncenter" value="<?=$controlParto->getVar0211() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$i+1 ?>>, valor:<Contracciones 10'>, id:<0211>"> </td>
                            <td><input type="text" name="control_parto[var_0212][]" maxlength="2" class="width2 aligncenter" value="<?=$controlParto->getVar0212() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$i+1 ?>>, valor:<Dilataci&oacute;n>, id:<0212>"> </td>
                            <td><input type="text" name="control_parto[var_0213][]" maxlength="4" class="width2 aligncenter" value="<?=$controlParto->getVar0213() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$i+1 ?>>, valor:<Altura presentaci&oacute;n>, id:<0213>"> </td>
                            <td><input type="text" name="control_parto[var_0214][]" maxlength="4" class="width2 aligncenter" value="<?=$controlParto->getVar0214() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$i+1 ?>>, valor:<Variaci&oacute;n posici&oacute;n>, id:<0214>"> </td>
                            <td><input type="text" name="control_parto[var_0215][]" maxlength="1" class="width2 aligncenter" value="<?=$controlParto->getVar0215() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$i+1 ?>>, valor:<Meconio>, id:<0215>"> </td>
                            <td><input type="text" name="control_parto[var_0216][]" maxlength="3" class="width2 aligncenter" value="<?=$controlParto->getVar0216() ?>" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$i+1 ?>>, valor:<FCF//dips>, id:<0216>"> </td>
                        </tr>            
                        <?php
                        }
                       // $tab += count($controles);
                        $k = count($controlesParto);
                    }    
                    for ($j = $k; $j <= 4; $j++){
                    ?>
                        <tr>
                        <td> <input type="text" name="control_parto[var_0207][]" maxlength="2" min="0" max="23" class="number" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$j+1 ?>>, valor:<hora>, id:<0207>"/>  </td>
                        <td> <input type="text" name="control_parto[var_0208][]" maxlength="2" min="0" max="59" class="number" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$j+1 ?>>, valor:<minutos>, id:<0208>"/> </td>
                        <td> <input type="text" name="control_parto[var_0209][]" maxlength="3" class="width2" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$j+1 ?>>, valor:<posici&oacute;n>, id:<0209>"/> </td>
                        <td> <input type="text" name="control_parto[var_0210][]" maxlength="3" class="aligncenter" tabindex="<?php echo $tab++ ?>" style="width:50%;float:left;" title="variable: <Control en parto <?=$j+1 ?>>, valor:<PA sist&oacute;lica>, id:<0210>">
                             <input type="text" name="control_parto[var_0407][]" maxlength="3" class="aligncenter" tabindex="<?php echo $tab++ ?>" style="width:50%; float:right;" title="variable: <Control en parto <?=$j+1 ?>>, valor:<PA diast&oacute;lica>, id:<0407>"> </td>
                        <td> <input type="text" name="control_parto[var_0392][]" maxlength="3" class="width2 aligncenter" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$j+1 ?>>, valor:<Pulso>, id:<0392>"></td>
                        <td> <input type="text" name="control_parto[var_0211][]" maxlength="1" class="width2 aligncenter" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$j+1 ?>>, valor:<Contracciones 10'>, id:<0211>"></td>
                        <td> <input type="text" name="control_parto[var_0212][]" maxlength="2" class="width2 aligncenter" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$j+1 ?>>, valor:<Dilataci&oacute;n>, id:<0212>"></td>
                        <td> <input type="text" name="control_parto[var_0213][]" maxlength="4" class="width2 aligncenter" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$j+1 ?>>, valor:<Altura presentaci&oacute;n>, id:<0213>"></td>
                        <td> <input type="text" name="control_parto[var_0214][]" maxlength="4" class="width2 aligncenter" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$j+1 ?>>, valor:<Variaci&oacute;n posici&oacute;n>, id:<0214>"></td>
                        <td> <input type="text" name="control_parto[var_0215][]" maxlength="1" class="width2 aligncenter" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$j+1 ?>>, valor:<Meconio>, id:<0215>"></td>
                        <td> <input type="text" name="control_parto[var_0216][]" maxlength="3" class="width2 aligncenter" tabindex="<?php echo $tab++ ?>" title="variable: <Control en parto <?=$j+1 ?>>, valor:<FCF//dips>, id:<0216>"></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </td>
<!-- ENFERMEDADES - 605/  -->
        <td class="border2 top" id="enfermedades">
            <table class="inside">
                <tr>
                    <td class="alignleft ck_titulo" valign="top" colspan="14" style="padding:0">
                        <h6><span style="margin:0 10px">ENFERMEDADES</span> 
                            <input class="black" type="radio" tabindex="605" name="var_0257" value="B" title="variable: <Enfermedades>, valor:<1 o m&aacute;s>, id:<0257_B>"
                                   id="enfermedades_ninguna" <?=($ficha->getVar0257()=='B')?$checked:'' ?> >    
                            <label for="enfermedades_ninguna">ninguna</label>
                            <input class="yellow" type="radio" tabindex="606" name="var_0257" value="A" title="variable: <Enfermedades>, valor:<ninguna>, id:<0257_A>"
                                   id="enfermedades_1omas" <?=($ficha->getVar0257()=='A')?$checked:'' ?> >    
                            <label for="enfermedades_1omas">1 &oacute; m&aacute;s</label>
                        </h6> 
                    </td>
                </tr>
                <tr>
                    <td style="width: 15%;">&nbsp;</td>
                    <td><span class="title3">no</span></td>
                    <td><span class="title3">si</span></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
                    <td><span class="title3">no</span></td>
                    <td><span class="title3">si</span></td>
                    <td colspan="3"><span class="title3">HEMORRAGIA</span></td>
                    <td><span class="title3">no</span></td>
                    <td><span class="title3">si</span></td>
                </tr>
                <tr>
                    <td><label class="title3">HTA previa</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="607" name="var_0258" value="A" title="variable: <HTA previa>, valor:<no>, id:<0258_A>"
                               id="hta_previa_no" <?=($ficha->getVar0258()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="608" name="var_0258" value="B" title="variable: <HTA previa>, valor:<si>, id:<0258_B>"
                               id="hta_previa_si" <?=($ficha->getVar0258()=='B')?$checked:'' ?> >
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2"><label class="title3">infecc. ovular</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="623" name="var_0266" value="A" title="variable: <Infecci&oacute;n ovular>, valor:<no>, id:<0266_A>"
                               id="infeccion_ovular_no" <?=($ficha->getVar0266()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="624" name="var_0266" value="B" title="variable: <Infecci&oacute;n ovular>, valor:<si>, id:<0266_B>"
                               id="infeccion_ovular_si" <?=($ficha->getVar0266()=='B')?$checked:'' ?> >
                    </td>
                    <td colspan="3"><label class="title3">1&deg; trim.</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="637" name="var_0273" value="A" title="variable: <Hemorragia 1er. trim.>, valor:<no>, id:<0273_A>"
                               id="hemorragia_1trim_no" <?=($ficha->getVar0273()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="638" name="var_0273" value="B" title="variable: <Hemorragia 1er. trim.>, valor:<si>, id:<0273_B>"
                               id="hemorragia_1trim_si" <?=($ficha->getVar0273()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3">HTA inducida embarazo</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="609" name="var_0259" value="A" title="variable: <HTA inducida>, valor:<no>, id:<0259_A>"
                               id="hta_inducida_no" <?=($ficha->getVar0259()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="610" name="var_0259" value="B" title="variable: <HTA inducida>, valor:<si>, id:<0259_B>"
                               id="hta_inducida_si" <?=($ficha->getVar0259()=='B')?$checked:'' ?> >
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2"><label class="title3">infecc. urinaria</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="625" name="var_0267" value="A" title="variable: <Infecci&oacute;n urinaria>, valor:<no>, id:<0267_A>"
                               id="infeccion_urinaria_no" <?=($ficha->getVar0267()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="626" name="var_0267" value="B" title="variable: <Infecci&oacute;n urinaria>, valor:<si>, id:<0267_B>"
                               id="infeccion_urinaria_si" <?=($ficha->getVar0267()=='B')?$checked:'' ?> >
                    </td>
                    <td colspan="3"><label class="title3">2&deg; trim.</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="639" name="var_0274" value="A" title="variable: <Hemorragia 2do. trim.>, valor:<no>, id:<0274_A>"
                               id="hemorragia_2trim_no" <?=($ficha->getVar0274()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="640" name="var_0274" value="B" title="variable: <Hemorragia 2do. trim.>, valor:<si>, id:<0274_B>"
                               id="hemorragia_2trim_si" <?=($ficha->getVar0274()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3">preeclampsia</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="611" name="var_0260" value="A" title="variable: <Preeclampsia>, valor:<no>, id:<0260_A>"
                               id="preeclampsia_no" <?=($ficha->getVar0260()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="612" name="var_0260" value="B" title="variable: <Preeclampsia>, valor:<si>, id:<0260_B>"
                               id="preeclampsia_si" <?=($ficha->getVar0260()=='B')?$checked:'' ?> >
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2"><label class="title3">amenaza parto preter.</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="627" name="var_0268"  value="A" title="variable: <Amenaza parto pret&eacute;rmino>, valor:<no>, id:<0268_A>"
                               id="amenaza_parto_pretermino_no" <?=($ficha->getVar0268()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="628" name="var_0268"  value="B" title="variable: <Amenaza parto pret&eacute;rmino>, valor:<si>, id:<0268_B>"
                               id="amenaza_parto_pretermino_si" <?=($ficha->getVar0268()=='B')?$checked:'' ?> >
                    </td>
                    <td colspan="3"><label class="title3">3&deg; trim.</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="641" name="var_0275" value="A" title="variable: <Hemorragia 3er. trim.>, valor:<no>, id:<0275_A>"
                               id="hemorragia_3trim_no" <?=($ficha->getVar0275()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="642" name="var_0275" value="B" title="variable: <Hemorragia 3er. trim.>, valor:<si>, id:<0275_B>"
                               id="hemorragia_3trim_si" <?=($ficha->getVar0275()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3">eclampsia</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="613" name="var_0261" value="A" title="variable: <Eclampsia>, valor:<no>, id:<0261_A>"
                               id="eclampsia_no" <?=($ficha->getVar0261()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="614" name="var_0261" value="B" title="variable: <Eclampsia>, valor:<si>, id:<0261_B>"
                               id="eclampsia_si" <?=($ficha->getVar0261()=='B')?$checked:'' ?> >
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2"><label class="title3">R.C.I.U.</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="629" name="var_0269" value="A" title="variable: <RCIU>, valor:<no>, id:<0269_A>"
                               id="rciu_no" <?=($ficha->getVar0269()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="630" name="var_0269" value="B" title="variable: <RCIU>, valor:<si>, id:<0269_B>"
                               id="rciu_si" <?=($ficha->getVar0269()=='B')?$checked:'' ?> >
                    </td>
                    <td colspan="3"><label class="title3">postparto</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="643" name="var_0276" value="A" title="variable: <Hemorragia postparto>, valor:<no>, id:<0276_A>"
                               id="hemorragia_postparto_no" <?=($ficha->getVar0276()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="644" name="var_0276" value="B" title="variable: <Hemorragia postparto>, valor:<si>, id:<0276_B>"
                               id="hemorragia_postparto_si" <?=($ficha->getVar0276()=='B')?$checked:'' ?> >
                    </td>
                </tr>                  
                <tr>
                    <td><label class="title3">cardiopat&iacute;a</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="615" name="var_0262" value="A" title="variable: <Cardiopat&iacute;a>, valor:<no>, id:<0262_A>"
                               id="cardiopatia_no" <?=($ficha->getVar0262()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="616" name="var_0262" value="B" title="variable: <Cardiopat&iacute;a>, valor:<si>, id:<0262_B>"
                               id="cardiopatia_si" <?=($ficha->getVar0262()=='B')?$checked:'' ?> >
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2"><label class="title3">rotura prem. de membranas</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="631" name="var_0270" value="A" title="variable: <Ruptura prem. de membranas>, valor:<no>, id:<0270_A>"
                               id="rotura_prematura_membranas_no" <?=($ficha->getVar0270()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="632" name="var_0270" value="B" title="variable: <Ruptura prem. de membranas>, valor:<si>, id:<0270_B>"
                               id="rotura_prematura_membranas_si" <?=($ficha->getVar0270()=='B')?$checked:'' ?> >
                    </td>
                    <td colspan="3"><label class="title3">infecc. puerperal</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="645" name="var_0277" value="A" title="variable: <Infecci&oacute;n puerperal>, valor:<no>, id:<0277_A>"
                               id="infeccion_puerperal_no" <?=($ficha->getVar0277()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="646" name="var_0277" value="B" title="variable: <Infecci&oacute;n puerperal>, valor:<si>, id:<0277_B>"
                               id="infeccion_puerperal_si" <?=($ficha->getVar0277()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3">nefropat&iacute;a</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="617" name="var_0263" value="A" title="variable: <Nefropat&iacute;a>, valor:<no>, id:<0263_A>"
                               id="nefropatia_no" <?=($ficha->getVar0263()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="618" name="var_0263" value="B" title="variable: <Nefropat&iacute;a>, valor:<si>, id:<0263_B>"
                               id="nefropatia_si" <?=($ficha->getVar0263()=='B')?$checked:'' ?> >
                    </td>
                    <td class="bottom aligncenter"><label style="left:-3px;top:2px;" class="title4">I</label><label class="title4">II</label></td>
                    <td class="bottom aligncenter"><label class="title4">G</label></td>
                    <td colspan="2"><label class="title3">anemia</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="633" name="var_0271" value="A" title="variable: <Anemia>, valor:<no>, id:<0271_A>"
                               id="anemia_no" <?=($ficha->getVar0271()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="634" name="var_0271" value="B" title="variable: <Anemia>, valor:<si>, id:<0271_B>"
                               id="anemia_si" <?=($ficha->getVar0271()=='B')?$checked:'' ?> >
                    </td>
                    <td colspan="6" rowspan="2">
                        <span class="title2 block alignleft">&nbsp;&nbsp;C&oacute;digos</span>
                        <input type="text" class="width2 alerta auxiliar number" data-tabla="PATOLOGIAS MATERNAS" name="var_0278" title="variable: <C&oacute;digo enfermedad 1>, valor:<c&oacute;digo 1>, id:<0278>"
                               id="codigo_enfermedad_1" value="<?=$ficha->getVar0278() ?>" maxlength="3" tabindex="648"/>
                        <input type="text" class="width2 alerta auxiliar number" data-tabla="PATOLOGIAS MATERNAS" name="var_0279" title="variable: <C&oacute;digo enfermedad 2>, valor:<c&oacute;digo 2>, id:<0279>"
                               id="codigo_enfermedad_2" value="<?=$ficha->getVar0279() ?>" maxlength="3" tabindex="649"/>
                        <input type="text" class="width2 alerta auxiliar number" data-tabla="PATOLOGIAS MATERNAS" name="var_0280" title="variable: <C&oacute;digo enfermedad 3>, valor:<c&oacute;digo 3>, id:<0280>"
                               id="codigo_enfermedad_3" value="<?=$ficha->getVar0280() ?>" maxlength="3" tabindex="650"/>
                    </td>
                </tr>
                <tr class="top borderB1">
                    <td><label class="title3">diabetes</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="619" name="var_0264" value="A" title="variable: <Diabetes>, valor:<no>, id:<0264_A>"
                               id="diabetes_no" <?=($ficha->getVar0264()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="620" name="var_0264" value="B" title="variable: <Diabetes>, valor:<I>, id:<0264_B>"
                               id="diabetes_I" <?=($ficha->getVar0264()=='B')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="621" name="var_0264" value="C" title="variable: <Diabetes>, valor:<II>, id:<0264_C>"
                               id="diabetes_II" <?=($ficha->getVar0264()=='C')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="622" name="var_0264" value="D" title="variable: <Diabetes>, valor:<G>, id:<0264_D>"
                               id="diabetes_G" <?=($ficha->getVar0264()=='D')?$checked:'' ?> >
                    </td>
                    <td colspan="2"><label class="title3">otra cond. grave</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="635" name="var_0272" value="A" title="variable: <Otra condici&oacute;n grave>, valor:<no>, id:<0272_A>"
                               id="otra_condicion_grave_no" <?=($ficha->getVar0272()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="636" name="var_0272" value="B" title="variable: <Otra condici&oacute;n grave>, valor:<si>, id:<0272_B>"
                               id="otra_condicion_grave_si" <?=($ficha->getVar0272()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td rowspan="2"><span class="title3">TDP Prueba: Sifilis</span></td>
                    <td><label class="title3">-</label></td>
                    <td><label class="title4">+</label></td>
                    <td><label class="title4">n/r</label></td>
                    <td><label class="title4">n/c</label></td>
                    <td rowspan="2" style="width: 60px;"><span class="title3">TDP Prueba: VIH</span></td>
                    <td style="width: 25px;"><label class="title3">-</label></td>
                    <td><label class="title4">+</label></td>
                    <td><label class="title4">n/r</label></td>
                    <td><label class="title4">n/c</label></td>
                    <td rowspan="2" class="borderL1"><span class="title3">TARV</span></td>
                    <td><label class="title4">si</label></td>
                    <td><label class="title4">no</label></td>
                    <td><label class="title4">n/c</label></td>
                </tr>
                <tr>
                    <td>
                        <input class="black" type="radio" tabindex="651" name="var_0437" value="A" title="variable: <TDP - Prueba s&iacute;filis>, valor:<negativo>, id:<0437_A>"
                               id="tdp_prueba_sifilis_negativo" <?=($ficha->getVar0437()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="652" name="var_0437" value="B" title="variable: <TDP - Prueba s&iacute;filis>, valor:<positivo>, id:<0437_B>"
                               id="tdp_prueba_sifilis_positivo" <?=($ficha->getVar0437()=='B')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="653" name="var_0437" value="C" title="variable: <TDP - Prueba s&iacute;filis>, valor:<n/r>, id:<0437_C>"
                               id="tdp_prueba_sifilis_nr" <?=($ficha->getVar0437()=='C')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="black" type="radio" tabindex="654" name="var_0437" value="D" title="variable: <TDP - Prueba s&iacute;filis>, valor:<n/c>, id:<0437_D>"
                               id="tdp_prueba_sifilis_nc" <?=($ficha->getVar0437()=='D')?$checked:'' ?> >
                    </td>
                    
                    <td>
                        <input class="black" type="radio" tabindex="655" name="var_0438" value="A" title="variable: <TDP - Prueba VIH>, valor:<negativo>, id:<0438_A>"
                               id="tdp_prueba_vih_negativo" <?=($ficha->getVar0438()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="656" name="var_0438" value="B" title="variable: <TDP - Prueba VIH>, valor:<positivo>, id:<0438_B>"
                               id="tdp_prueba_vih_positivo" <?=($ficha->getVar0438()=='B')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="657" name="var_0438" value="C" title="variable: <TDP - Prueba VIH>, valor:<n/r>, id:<0438_C>"
                               id="tdp_prueba_vih_nr" <?=($ficha->getVar0438()=='C')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="black" type="radio" tabindex="658" name="var_0438" value="D" title="variable: <TDP - Prueba VIH>, valor:<n/c>, id:<0438_D>"
                               id="tdp_prueba_vih_nc" <?=($ficha->getVar0438()=='D')?$checked:'' ?> >
                    </td>
                    <td><input class="black" type="radio" tabindex="659" name="var_0439" value="B" title="variable: <TDP - ARV>, valor:<si>, id:<0439_B>"
                               id="tdp_arv_si" <?=($ficha->getVar0439()=='B')?$checked:'' ?> ></td>
                    <td><input class="black" type="radio" tabindex="660" name="var_0439" value="A" title="variable: <TDP - ARV>, valor:<no>, id:<0439_A>"
                               id="tdp_arv_no" <?=($ficha->getVar0439()=='A')?$checked:'' ?> ></td>
                    <td><input class="black" type="radio" tabindex="661" name="var_0439" value="C" title="variable: <TDP - ARV>, valor:<n/c>, id:<0439_C>"
                               id="tdp_arv_nc" <?=($ficha->getVar0439()=='C')?$checked:'' ?> ></td>
                </tr>
            </table>
        </td>    
    </tr>
</table>    