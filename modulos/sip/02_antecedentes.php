<table id="section2">
    <tr>
<!-- ANTECEDENTES 27/65  -->                    
        <td class="border2" id="antecedentes">
            <table class="inside" style="text-align: center">
                <tr>
                    <td rowspan="9"><img src="css/antecedentes.jpg" /></td>                            
                </tr>
                <tr>
                    <td colspan="3"><span class="title2" >FAMILIARES</span></td>
                    <td colspan="4"><span class="title2" >PERSONALES</span></td>
                    <td> &nbsp; </td>
                    <td><label class="title2">No</label></td>
                    <td><label class="title2">Si</label></td>
                </tr>
                <tr>
                    <td><label class="title2">No</label></td>
                    <td><label class="title2">Si</label></td>
                    <td> &nbsp; </td>
                    <td><label class="title2">No</label></td>
                    <td><label class="title2">Si</label></td>
                    <td colspan="2"> &nbsp; </td>
                    <td><label class="title3">Cirug&iacute;a<br>genito-urinaria</label></td>
                    <td>
                        <input class="black" type="radio" name="var_0032" value="A" id="personales_cirugia_no" tabindex="53"
                               <?=($ficha->getVar0032()=='A')?$checked:'' ?> title="variable: <Cirug&iacute;a>, valor:<no>, id:<0032_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0032" value="B" id="personales_cirugia_si" tabindex="54" 
                               <?=($ficha->getVar0032()=='B')?$checked:'' ?> title="variable: <Cirug&iacute;a>, valor:<si>, id:<0032_B>">
                    </td>

                </tr>
                <tr>
                    <td>
                        <input class="black" type="radio" name="var_0020" value="A" id="familiares_tbc_no" 
                               tabindex="27" <?=($ficha->getVar0020()=='A')?$checked:'' ?> title="variable: <TBC (familiares)>, valor:<no>, id:<0020_A>"> 
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0020" value="B" id="familiares_tbc_si" 
                               tabindex="28" <?=($ficha->getVar0020()=='B')?$checked:'' ?> title="variable: <TBC (familiares)>, valor:<si>, id:<0020_B>">
                    </td>
                    <td> <label class="title3">TBC</label> </td>
                    <td>
                        <input class="black" type="radio" name="var_0021" value="A" id="personales_tbc_no" 
                               tabindex="39" <?=($ficha->getVar0021()=='A')?$checked:'' ?> title="variable: <TBC (personales)>, valor:<no>, id:<0021_A>">
                    </td>
                    <td id="personales_diabetes">
                        <input class="yellow" type="radio" name="var_0021" value="B" id="personales_tbc_si" 
                               tabindex="40" <?=($ficha->getVar0021()=='B')?$checked:'' ?> title="variable: <TBC (personales)>, valor:<si>, id:<0021_B>"> 
                        <label class="title4">I</label> 
                    </td>
                    <td> <label class="title4">II</label> </td>
                    <td> <label class="title4">G</label> </td>
                    <td><label class="title3">infertilidad</label></td>
                    <td>
                        <input class="black" type="radio" name="var_0033" value="A" id="personales_infertilidad_no" 
                               tabindex="55" <?=($ficha->getVar0033()=='A')?$checked:'' ?> title="variable: <Infertilidad>, valor:<no>, id:<0033_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0033" value="B" id="personales_infertilidad_si" 
                               tabindex="56" <?=($ficha->getVar0033()=='B')?$checked:'' ?> title="variable: <Infertilidad>, valor:<si>, id:<0033_B>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="black" type="radio" name="var_0022" value="A" id="familiares_diabetes_no" 
                               tabindex="29" <?=($ficha->getVar0022()=='A')?$checked:'' ?> title="variable: <Diabetes (familiares)>, valor:<no>, id:<0022_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0022" value="B" id="familiares_diabetes_si" 
                               tabindex="30" <?=($ficha->getVar0022()=='B')?$checked:'' ?> title="variable: <Diabetes (familiares)>, valor:<si>, id:<0022_B>">
                    </td>
                    <td> <label class="title3">Diabetes</label> </td>
                    <td>
                        <input class="black" type="radio" name="var_0023" value="A" id="personales_diabetes_no" 
                               tabindex="41" <?=($ficha->getVar0023()=='A')?$checked:'' ?> title="variable: <Diabetes (personales)>, valor:<no>, id:<0023_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0023" value="B" id="personales_diabetes_I" 
                               tabindex="42" <?=($ficha->getVar0023()=='B')?$checked:'' ?> title="variable: <Diabetes (personales)>, valor:<tipo I>, id:<0023_B>">
                    </td>
                    <td class="diabetes2">
                        <input class="yellow" type="radio" name="var_0023" value="C" id="personales_diabetes_II" 
                               tabindex="43" <?=($ficha->getVar0023()=='C')?$checked:'' ?> title="variable: <Diabetes (personales)>, valor:<tipo II>, id:<0023_C>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0023" value="D" id="personales_diabetes_G"  
                               tabindex="44" <?=($ficha->getVar0023()=='D')?$checked:'' ?> title="variable: <Diabetes (personales)>, valor:<tipo G>, id:<0023_D>">
                    </td>
                    <td> <label class="title3">Cardiopat.</label> </td>
                    <td>
                        <input class="black" type="radio" name="var_0034" value="A" id="personales_cardiopatia_no" 
                               tabindex="57" <?=($ficha->getVar0034()=='A')?$checked:'' ?> title="variable: <Cardiopat&iacute;a>, valor:<no>, id:<0034_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0034" value="B" id="personales_cardiopatia_si" 
                               tabindex="58" <?=($ficha->getVar0034()=='B')?$checked:'' ?> title="variable: <Cardiopat&iacute;a>, valor:<si>, id:<0034_B>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="black" type="radio" name="var_0024" value="A" id="familiares_hipertension_no" 
                               tabindex="31" <?=($ficha->getVar0024()=='A')?$checked:'' ?> title="variable: <Hipertensi&oacute;n (familiares)>, valor:<no>, id:<0024_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0024" value="B" id="familiares_hipertension_si" 
                               tabindex="32" <?=($ficha->getVar0024()=='B')?$checked:'' ?> title="variable: <Hipertensi&oacute;n (familiares)>, valor:<si>, id:<0024_B>">
                    </td>
                    <td> <label class="title3">Hipertensi&oacute;n</label> </td>
                    <td>
                        <input class="black" type="radio" name="var_0025" value="A" id="personales_hipertension_no" 
                               tabindex="45" <?=($ficha->getVar0025()=='A')?$checked:'' ?> title="variable: <Hipertensi&oacute;n (personales)>, valor:<no>, id:<0025_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0025" value="B" id="personales_hipertension_si" 
                               tabindex="46" <?=($ficha->getVar0025()=='B')?$checked:'' ?> title="variable: <Hipertensi&oacute;n (personales)>, valor:<si>, id:<0025_B>" >
                    </td>
                    <td colspan="2"> &nbsp; </td>
                    <td> <label class="title3">Nefropat&iacute;a</label> </td>
                    <td>
                        <input class="black" type="radio" name="var_0035" value="A" id="personales_nefropatia_no"
                               tabindex="59" <?=($ficha->getVar0035()=='A')?$checked:'' ?> title="variable: <Nefropat&iacute;a>, valor:<no>, id:<0035_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0035" value="B" id="personales_nefropatia_si" 
                               tabindex="60" <?=($ficha->getVar0035()=='B')?$checked:'' ?> title="variable: <Nefropat&iacute;a>, valor:<si>, id:<0035_B>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="black" type="radio" name="var_0026" value="A" id="familiares_preclampsia_no" 
                               tabindex="33" <?=($ficha->getVar0026()=='A')?$checked:'' ?> title="variable: <Preclampsia (familiares)>, valor:<no>, id:<0026_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0026" value="B" id="familiares_preclampsia_si"  
                               tabindex="34" <?=($ficha->getVar0026()=='B')?$checked:'' ?> title="variable: <Preclampsia (familiares)>, valor:<si>, id:<0026_B>">
                    </td>
                    <td> <label class="title3">Preclampsia</label> </td>
                    <td>
                        <input class="black" type="radio" name="var_0027" value="A" id="personales_preclampsia_no" 
                               tabindex="47" <?=($ficha->getVar0027()=='A')?$checked:'' ?> title="variable: <Preclampsia (personales)>, valor:<no>, id:<0027_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0027" value="B" id="personales_preclampsia_si" 
                               tabindex="48" <?=($ficha->getVar0027()=='B')?$checked:'' ?> title="variable: <Preclampsia (personales)>, valor:<si>, id:<0027_B>">
                    </td>
                    <td colspan="2"> &nbsp; </td>
                    <td> <label class="title3">Violencia</label> </td>
                    <td>
                        <input class="black" type="radio" name="var_0036" value="A" id="personales_violencia_no" 
                               tabindex="61" <?=($ficha->getVar0036()=='A')?$checked:'' ?> title="variable: <Violencia>, valor:<no>, id:<0036_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0036" value="B" id="personales_violencia_si" 
                               tabindex="62" <?=($ficha->getVar0036()=='B')?$checked:'' ?> title="variable: <Violencia>, valor:<si>, id:<0036_B>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="black" type="radio" name="var_0028" value="A" id="familiares_eclampsia_no" 
                               tabindex="35" <?=($ficha->getVar0028()=='A')?$checked:'' ?> title="variable: <Eclampsia (familiares)>, valor:<no>, id:<0028_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0028" value="B" id="familiares_eclampsia_si" 
                               tabindex="36" <?=($ficha->getVar0028()=='B')?$checked:'' ?> title="variable: <Eclampsia (familiares)>, valor:<si>, id:<0028_B>">
                    </td>
                    <td> <label class="title3">Eclampsia</label> </td>
                    <td>
                        <input class="black" type="radio" name="var_0029" value="A" id="personales_eclampsia_no" 
                               tabindex="49" <?=($ficha->getVar0029()=='A')?$checked:'' ?> title="variable: <Eclampsia (personales)>, valor:<no>, id:<0029_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0029" value="B" id="personales_eclampsia_si" 
                               tabindex="50" <?=($ficha->getVar0029()=='B')?$checked:'' ?> title="variable: <Eclampsia (personales)>, valor:<si>, id:<0029_B>">
                    </td>
                    <td colspan="2"> <label class="title3">Otro</label> </td>
                    <td> <label class="title3">VIH+</label> </td>
                    <td>
                        <input class="black" type="radio" name="var_0432" value="A" id="personales_vih_no" 
                               tabindex="63" <?=($ficha->getVar0432()=='A')?$checked:'' ?> title="variable: <VIH +>, valor:<no>, id:<0432_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0432" value="B" id="personales_vih_si" 
                               tabindex="64" <?=($ficha->getVar0432()=='B')?$checked:'' ?> title="variable: <VIH +>, valor:<si>, id:<0432_B>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="black" type="radio" name="var_0030" value="A" id="familiares_otro_grave_no" 
                               tabindex="37" <?=($ficha->getVar0030()=='A')?$checked:'' ?> title="variable: <Otro antecedente familiar>, valor:<no>, id:<0030_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0030" value="B" id="familiares_otro_grave_si" 
                               tabindex="38" <?=($ficha->getVar0030()=='B')?$checked:'' ?> title="variable: <Otro antecedente familiar>, valor:<si>, id:<0030_B>">
                    </td>
                    <td> <label class="title3">Otra cond. <br>m&eacute;dica grave</label> </td>
                    <td>
                        <input class="black" type="radio" name="var_0031" value="A" id="personales_otro_grave_no" 
                               tabindex="51" <?=($ficha->getVar0031()=='A')?$checked:'' ?> title="variable: <Otro antecedente personal>, valor:<no>, id:<0031_A>">
                    </td>
                    <td>
                        <input class="yellow" type="radio" name="var_0031" value="B" id="personales_otro_grave_si" 
                               tabindex="52" <?=($ficha->getVar0031()=='B')?$checked:'' ?> title="variable: <Otro antecedente personal>, valor:<si>, id:<0031_B>">
                    </td>
                    <td colspan="5">
                        <input type="text" name="var_0037" value="<?=$ficha->getVar0037() ?>" tabindex="65" maxlength="20" id="otro_antecedente" title="variable: <Otros antecedentes>, valor:<texto>, id:<0037>"/>
                    </td>
                </tr>
            </table>
        </td>
<!-- ANTECEDENTES OBSTETRICOS  66/83 -->
        <td id="obstetricos" class="border2">
            <!-- PARTE 1 -->
            <table class="inside" id="obstetricos_1">
                <tr> <td colspan="4"><span class="title1" >OBSTETRICOS</span></td> </tr>
                <tr> <td colspan="4">&nbsp;</td> </tr>
                <tr> <td colspan="4"><span class="title3">ULTIMO PREVIO</span></td> </tr>
                <tr class="ultimoprevio1">
                    <td><label class="title3">n/c</label></td>
                    <td>
                        <input class="black" type="radio" name="var_0038" value="A" id="peso_rn_previo_nc" 
                               tabindex="71" <?=($ficha->getVar0038()=='A')?$checked:'' ?> title="variable: <Ultimo RN previo>, valor:<no sabe>, id:<0038_A>">  
                    </td>
                    <td><label class="title3"><2500g</label></td>
                    <td>
                        <input class="yellow" type="radio" name="var_0038" value="B" id="peso_rn_previo_2500" 
                               tabindex="73" <?=($ficha->getVar0038()=='B')?$checked:'' ?> title="variable: <Ultimo RN previo>, valor:<<2500g>, id:<0038_B>">
                    </td>
                </tr>
                <tr class="ultimoprevio2">
                    <td><label class="title3">normal</label></td>
                    <td>
                        <input class="black" type="radio" name="var_0038" value="C" id="peso_rn_previo_normal" 
                               tabindex="72" <?=($ficha->getVar0038()=='C')?$checked:'' ?> title="variable: <Ultimo RN previo>, valor:<normal>, id:<0038_C>">  
                    </td>
                    <td><label class="title3">>4000g</label></td>
                    <td>
                        <input class="yellow" type="radio" name="var_0038" value="D" id="peso_rn_previo_4000" 
                               tabindex="74" <?=($ficha->getVar0038()=='D')?$checked:'' ?> title="variable: <Ultimo RN previo>, valor:<<4000g>, id:<0038_D>">
                    </td>
                </tr>
                <tr><td class="obstrelleno">&nbsp;</td></tr>
                <tr class="gemelares">
                    <td colspan="2"><span class="title3">Antecedente de Gemelares</span></td>
                    <td><label class="title2" for="antecedentes_gemelares_no">No</label>
                        <input class="black" type="radio" name="var_0039"  value="A" id="antecedentes_gemelares_no" 
                               tabindex="75" <?=($ficha->getVar0039()=='A')?$checked:'' ?> title="variable: <Antecedentes gemelares>, valor:<no>, id:<0039_A>">
                    </td>
                    <td><label class="title2" for="antecedentes_gemelares_si">Si</label>
                        <input class="yellow" type="radio" name="var_0039"  value="B" id="antecedentes_gemelares_si" 
                               tabindex="76" <?=($ficha->getVar0039()=='B')?$checked:'' ?> title="variable: <Antecedentes gemelares>, valor:<si>, id:<0039_B>">
                    </td>
                </tr>
                <tr><td class="obstrelleno">&nbsp;</td></tr>
            </table>         
            <!-- PARTE 2 -->
            <table class="inside" id="obstetricos_2">
                <tr>
                    <td style="width: 80px;"><span class="title2">Gestas Previas</span></td>
                    <td style="width: 60px;"><span class="title2">Abortos</span></td>
                    <td style="width: 60px;"><span class="title2">Vaginales</span></td>
                    <td><span class="title2">Nacidos Vivos</span></td>
                    <td><span class="title2">Viven</span></td>
                </tr>
                <tr>
                    <td>
                        <input type="text" class="number required width2" maxlength="2" tabindex="66" name="var_0040" id="gestas_previas" required="required"
                               value="<?=$ficha->getVar0040() ?>" min="0" max="20" title="variable: <Gestas previas>, valor:<cantidad>, id:<0040>"/>
                    </td>
                    <td>
                        <input type="text" class="number width2" maxlength="2" tabindex="68" name="var_0041" id="abortos" 
                               value="<?=$ficha->getVar0041() ?>" min="0" max="20" title="variable: <Abortos>, valor:<cantidad>, id:<0041>"/>
                    </td>
                    <td>
                        <input type="text" class="number width2" maxlength="2" tabindex="77" name="var_0042" id="partos_vaginales" 
                               value="<?=$ficha->getVar0042() ?>" min="0" max="20" title="variable: <Partos vaginales>, valor:<cantidad>, id:<0042>"/>
                    </td>
                    <td>
                        <input type="text" class="number width2" maxlength="2" tabindex="79" name="var_0043" id="nacidos_vivos" 
                               value="<?=$ficha->getVar0043() ?>" min="0" max="20" title="variable: <Ant. nacidos vivos>, valor:<cantidad>, id:<0043>"/>
                    </td>
                    <td>
                        <input type="text" class="number width2" maxlength="2" tabindex="80" name="var_0044" id="rn_que_viven" 
                               value="<?=$ficha->getVar0044() ?>" min="0" max="20" title="variable: <RN que viven>, valor:<cantidad>, id:<0044>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input tabindex="67" maxlength="1" class="number width1" name="var_0409" id="embarazo_ectopico" min="0" max="9"
                               value="<?=$ficha->getVar0409() ?>" title="variable: <Embarazo ect&oacute;pico>, valor:<si>, id:<0409>"/>
                        <label style="display: block" class="title3">emb. ect&oacute;pico</label>
                    </td>
                    <td><label class="title3 block">3 espont.<br>consecutivos</label>
                        <input class="yellow" type="checkbox" name="var_0045" value="X" id="tres_espontaneo_cons" 
                               tabindex="69" <?=($ficha->getVar0045()=='X')?$checked:'' ?> title="variable: <Tres abortos espont&aacute;neos consecutivos>, valor:<si>, id:<0045>">
                    </td>
                    <td colspan="2">&nbsp;</td>
                    <td style="text-align:left"><label style="margin-right: 3px;" class="title3">Muertos<br>1&deg; sem.</label>
                        <input tabindex="82" maxlength="1" class="number width1 alerta" name="var_0049" min="0" max="9"
                               id="muerto_1_sem" value="<?=$ficha->getVar0049() ?>" title="variable: <Ant. muertos 1a. sem.>, valor:<cantidad>, id:<0049>" />
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><label style="display: block;" class="title2">Partos</label>
                        <input type="text" class="number width2" maxlength="2" tabindex="70" name="var_0046" 
                               id="partos_previos" value="<?=$ficha->getVar0046() ?>" min="0" max="20" title="variable: <Partos previos>, valor:<cantidad>, id:<0046>" />
                    </td>
                    <td><label style="display: block;" class="title3">Ces&aacute;reas</label>
                        <input tabindex="78" maxlength="1" class="number width1 alerta" name="var_0047" min="0" max="9"
                               id="cesareas" value="<?=$ficha->getVar0047() ?>" title="variable: <Ces&aacute;reas>, valor:<cantidad>, id:<0047>"/>
                    </td>
                    <td><label style="display: block;" class="title3">Nacidos<br>muertos</label>
                        <input tabindex="81" maxlength="1" class="number width1 alerta" name="var_0048" min="0" max="9"
                               id="nacido_muerto" value="<?=$ficha->getVar0048() ?>" title="variable: <Ant. nacidos muertos>, valor:<cantidad>, id:<0048>"/>
                    </td>
                    <td style="text-align:left"><label style="margin-right: 3px;" class="title3">Despu&eacute;s<br>1&deg; sem.</label>
                        <input tabindex="83" maxlength="1" class="number width1 alerta" name="var_0050"  min="0" max="9"
                               id="muerto_despues_1_sem" value="<?=$ficha->getVar0050() ?>" title="variable: <Ant. muertos despu&eacute; 1a. sem.>, valor:<cantidad>, id:<0050>"/>
                    </td>
                </tr>
            </table>         
            <!-- PARTE 3 84/93 -->
            <table class="inside" id="obstetricos_3">
                <tr>
                    <td colspan="5"><span class="title1" >FIN EMBARAZO ANTERIOR</span></td>
                </tr>
                <tr class="lblfinembarazo">
                    <td><label class="title3">d&iacute;a</label></td>
                    <td><label class="title3">mes</label></td>
                    <td><label class="title3">a&ntilde;o</label></td>
                    <td colspan="2"><label class="title3" for="emb_anterior_menor_1_anio"><1 o >5 a&ntilde;os</label></td>
                </tr>
                <tr class="embarazo_anterior">
                    <td colspan="3"> 
                        <input type="text" class="datepicker" name="var_0051" id="fecha_embarazo_anterior" 
                               tabindex="84" value="<?=FechaView($ficha->getVar0051()) ?>" title="variable: <Fecha embarazo anterior>, valor:<fecha>, id:<0051>"/>
                    </td>
                    <td  colspan="2">
                        <input class="yellow" tabindex="85" type="checkbox" name="var_0052" value="X" title="variable: <Emb. anterior <1 o > 5 a&ntilde;os>, valor:<si>, id:<0052_X>"
                               id="emb_anterior_menor_1_anio" <?=($ficha->getVar0052()=='X')?$checked:'' ?> disabled="disabled" >
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><span class="title3">EMBARAZO PLANEADO</span></td>
                    <td><label class="title2" for="embarazo_planeado_no">no</label>
                        <input class="yellow" type="radio" name="var_0053" value="A" id="embarazo_planeado_no" 
                                tabindex="86" <?=($ficha->getVar0053()=='A')?$checked:'' ?> title="variable: <Embarazo planeado>, valor:<no>, id:<0053_A>">
                    </td>
                    <td ><label class="title2" for="embarazo_planeado_si">si</label>
                        <input class="black" type="radio" name="var_0053"  value="B" id="embarazo_planeado_si" 
                               tabindex="87" <?=($ficha->getVar0053()=='B')?$checked:'' ?> title="variable: <Embarazo planeado>, valor:<si>, id:<0053_B>">
                    </td>
                </tr>
                <tr>
                    <td colspan="5"><span class="title3" >FRACASO METODO ANTICONCEP.</span></td>
                </tr>
                <tr class="fracaso_anticoncep">
                    <td colspan="5">
                        <input class="black"  type="radio" tabindex="88" name="var_0054" value="A" title="variable: <Fracaso m&eacute;todo anticonceptivo>, valor:<no usaba>, id:<0054_A>"
                               id="fracaso_anticonceptivo_a" <?=($ficha->getVar0054()=='A')?$checked:'' ?> >
                        <input class="yellow" type="radio" tabindex="89" name="var_0054" value="B" title="variable: <Fracaso m&eacute;todo anticonceptivo>, valor:<barrera>, id:<0054_B>"
                               id="fracaso_anticonceptivo_b" <?=($ficha->getVar0054()=='B')?$checked:'' ?> >    
                        <input class="yellow" type="radio" tabindex="90" name="var_0054" value="C" title="variable: <Fracaso m&eacute;todo anticonceptivo>, valor:<DIU>, id:<0054_C>"
                               id="fracaso_anticonceptivo_c" <?=($ficha->getVar0054()=='C')?$checked:'' ?> >
                        <input class="yellow" type="radio" tabindex="91" name="var_0054" value="D" title="variable: <Fracaso m&eacute;todo anticonceptivo>, valor:<hormonal>, id:<0054_D>"
                               id="fracaso_anticonceptivo_d" <?=($ficha->getVar0054()=='D')?$checked:'' ?> >
                        <input class="yellow" type="radio" tabindex="92" name="var_0054" value="E" title="variable: <Fracaso m&eacute;todo anticonceptivo>, valor:<emergencia>, id:<0054_E>"
                               id="fracaso_anticonceptivo_e" <?=($ficha->getVar0054()=='E')?$checked:'' ?> >
                        <input class="yellow" type="radio" tabindex="93" name="var_0054" value="F" title="variable: <Fracaso m&eacute;todo anticonceptivo>, valor:<natural>, id:<0054_F>"
                               id="fracaso_anticonceptivo_f" <?=($ficha->getVar0054()=='F')?$checked:'' ?> >
                    </td>
                </tr>
                <tr class="fracaso_anticoncep">
                    <td colspan="5">
                        <label class="title2" for="fracaso_anticonceptivo_a">no<br>usaba</label>
                        <label class="title2" for="fracaso_anticonceptivo_b">barrera</label>
                        <label class="title2" for="fracaso_anticonceptivo_c">DIU</label>
                        <label class="title2" for="fracaso_anticonceptivo_d">hormo<br>nal</label>
                        <label class="title2" for="fracaso_anticonceptivo_e">emer<br>gencia</label>
                        <label class="title2" for="fracaso_anticonceptivo_f">natural</label>
                    </td>
                </tr>
            </table>         
        </td>
    </tr>
</table>