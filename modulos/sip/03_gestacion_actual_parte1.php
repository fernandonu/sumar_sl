<table id="section3">
    <tr>
<!-- GESTACION ACTUAL - PARTE I - 94/95  -->
        <td class="border2" id="gestacion_actual">
            <table class="inside">
                <tr>
                    <td valign="top" colspan="2"> <h6>&nbsp; GESTACI&Oacute;N ACTUAL</h6> </td>
                </tr>    
                <tr>
                    <td class="borderright">
                        <label style="margin: 8px 5px;" class="title2 block">PESO ANTERIOR</label>
                        <input style="margin: 0 5px 0 10px;" class="number width3" maxlength="3" name="var_0055" id="peso_anterior" min="25" max="199"
                               value="<?=$ficha->getVar0055() ?>" tabindex="94" type="text" title="variable: <Peso Anterior>, valor:<Kg.>, id:<0055>"/>kg. 
                    </td>
                    <td>
                        <label style="margin: 8px 5px;" class="title2 block">TALLA (cm)</label>
                        <label style="font-size: 1.4em; font-weight: bold; margin: 0 2px 0 4px;">1</label>
                        <input type="text" class="number width2" name="var_0056" id="talla_madre" maxlength="2" min="10" max="99"
                               value="<?=$ficha->getVar0056() ?>" tabindex="95" title="variable: <Talla madre>, valor:<cm.>, id:<0056>"/> 
                    </td>
                </tr>
            </table>
        </td>
        <!-- FECHAS FUM Y FPP 96/97  --> 
        <td class="border2" id="fum_fpp">
            <table class="inside">
                <tr class="aligncenter">
                    <td rowspan="2" style="width: 14px;"><label>&nbsp;F&nbsp; &nbsp;U&nbsp; &nbsp;M&nbsp;</label></td>
                    <td><label class="title3">d&iacute;a &nbsp; - &nbsp; mes &nbsp; - &nbsp; a&ntilde;o</label></td>
                </tr>
                <tr class="aligncenter">
                    <td>   <input type="text" class="datepicker" value="<?=FechaView($ficha->getVar0057()) ?>" name="var_0057" 
                                  id="fecha_fum" tabindex="96" title="variable: <Fecha &uacute;ltima menstruaci&oacute;n>, valor:<fecha>, id:<0057>" readonly/>
                    </td>
                </tr>
                <tr class="aligncenter">
                    <td rowspan="2" style="width: 14px;"><label>&nbsp;F&nbsp; &nbsp;P&nbsp; &nbsp;P&nbsp;</label></td>
                    <td><label class="title3">d&iacute;a &nbsp; - &nbsp; mes &nbsp; - &nbsp; a&ntilde;o</label></td>
                </tr>
                <tr class="aligncenter">
                    <td>   <input type="text" class="datepicker" value="<?=FechaView($ficha->getVar0058()) ?>" name="var_0058" 
                                  id="fecha_fpp" tabindex="97" title="variable: <Fecha probable de parto>, valor:<fecha>, id:<0058>" readonly/>
                    </td>
                </tr>
            </table>
        </td>
        <!-- EG CONFIABLE 98/101  --> 
        <td class="border2" id="eg_confiable_por">
            <table class="inside">
                <tr class="aligncenter">
                    <td class="titulo" colspan="2"><span class="title2">EG CONFIABLE por</span></td>
                </tr>
                <tr class="aligncenter">
                    <td><span class="title2">FUM</span></td>
                    <td><span class="title2">Eco <20s</span></td>
                </tr>
                <tr class="aligncenter">
                    <td><label class="title2" for="eg_confiable_por_fum_no">no</label>
                        <input class="yellow" type="radio" tabindex="98" name="var_0059" value="A" title="variable: <EG confiable por FUM>, valor:<no>, id:<0059_A>"
                               id="eg_confiable_por_fum_no" <?=($ficha->getVar0059()=='A')?$checked:'' ?> >
                    </td>
                    <td><label class="title2" for="eg_confiable_por_eco_no">no</label>
                        <input class="yellow" type="radio" tabindex="100" name="var_0060" value="A" title="variable: <EG confiable por Eco>, valor:<no>, id:<0060_A>"
                               id="eg_confiable_por_eco_no" <?=($ficha->getVar0060()=='A')?$checked:'' ?> >
                    </td>
                </tr>
                <tr class="aligncenter">
                    <td><label class="title2" for="eg_confiable_por_fum_si">si</label>
                        <input class="black" type="radio" tabindex="99" name="var_0059" value="B" title="variable: <EG confiable por FUM>, valor:<si>, id:<0059_B>"
                               id="eg_confiable_por_fum_si" <?=($ficha->getVar0059()=='B')?$checked:'' ?> >
                    </td>
                    <td><label class="title2" for="eg_confiable_por_eco_si">si</label>
                        <input class="black" type="radio" tabindex="101" name="var_0060" value="B" title="variable: <EG confiable por Eco>, valor:<si>, id:<0060_B>"
                               id="eg_confiable_por_eco_si" <?=($ficha->getVar0060()=='B')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td> 
        <!-- FUMADORA - DROGAS - ALCOHOL - VIOLENCIA 102/131  -->
        <td class="border2" id="fuma_droga_etc">
            <table class="inside">
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="2"><span class="title3" >FUMA ACT.</span> </td>
                    <td colspan="2"><span class="title3" >FUMA PAS.</span> </td>
                    <td colspan="2"><span class="title3" >DROGAS</span> </td>
                    <td colspan="2"><span class="title3" >ALCOHOL</span> </td>
                    <td colspan="2"><span class="title3" >VIOLENCIA</span> </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><span class="title2" >no</span> </td>
                    <td><span class="title2" >si</span> </td>
                    <td><span class="title2" >no</span> </td>
                    <td><span class="title2" >si</span> </td>
                    <td><span class="title2" >no</span> </td>
                    <td><span class="title2" >si</span> </td>
                    <td><span class="title2" >no</span> </td>
                    <td><span class="title2" >si</span> </td>
                    <td><span class="title2" >no</span> </td>
                    <td><span class="title2" >si</span> </td>
                </tr>
                <tr>
                    <td><label class="title3 tarvlbl">1&deg; trim.</label></td>
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="102" name="var_0061" value="A" title="variable: <Fumadora activa 1er.>, valor:<no>, id:<0061_A>"
                               id="fuma_act_1_trim_no" <?=($ficha->getVar0061()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="103" name="var_0061" value="B" title="variable: <Fumadora activa 1er.>, valor:<si>, id:<0061_B>"
                               id="fuma_act_1_trim_si" <?=($ficha->getVar0061()=='B')?$checked:'' ?> >
                    </td>
                    
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="104" name="var_0062" value="A" title="variable: <Fumadora pasiva 1er.>, valor:<no>, id:<0062_A>"
                               id="fuma_pas_1_trim_no" <?=($ficha->getVar0062()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="105" name="var_0062" value="B" title="variable: <Fumadora pasiva 1er.>, valor:<si>, id:<0062_B>"
                               id="fuma_pas_1_trim_si" <?=($ficha->getVar0062()=='B')?$checked:'' ?> >
                    </td>
                    
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="106" name="var_0063" value="A" title="variable: <Drogas 1er.>, valor:<no>, id:<0063_A>"
                               id="drogas_1_trim_no" <?=($ficha->getVar0063()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="107" name="var_0063" value="B" title="variable: <Drogas 1er.>, valor:<si>, id:<0063_B>"
                               id="drogas_1_trim_si" <?=($ficha->getVar0063()=='B')?$checked:'' ?> >
                    </td>
                    
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="108" name="var_0064" value="A" title="variable: <Alcohol 1er.>, valor:<no>, id:<0064_A>"
                               id="alcohol_1_trim_no" <?=($ficha->getVar0064()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="109" name="var_0064" value="B" title="variable: <Alcohol 1er.>, valor:<si>, id:<0064_B>"
                               id="alcohol_1_trim_si" <?=($ficha->getVar0064()=='B')?$checked:'' ?> >
                    </td>

                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="110" name="var_0065" value="A" title="variable: <Violencia 1er.>, valor:<no>, id:<0065_A>"
                               id="violencia_1_trim_no" <?=($ficha->getVar0065()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="111" name="var_0065" value="B" title="variable: <Violencia 1er.>, valor:<si>, id:<0065_B>"
                               id="violencia_1_trim_si" <?=($ficha->getVar0065()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3 tarvlbl">2&deg; trim.</label></td>
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="112" name="var_0066" value="A" title="variable: <Fumadora activa 2do.>, valor:<no>, id:<0066_A>"
                               id="fuma_act_2_trim_no" <?=($ficha->getVar0066()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="113" name="var_0066" value="B" title="variable: <Fumadora activa 2do.>, valor:<si>, id:<0066_B>"
                               id="fuma_act_2_trim_si" <?=($ficha->getVar0066()=='B')?$checked:'' ?> >
                    </td>
                    
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="114" name="var_0067" value="A" title="variable: <Fumadora pasiva 2do.>, valor:<no>, id:<0067_A>"
                               id="fuma_pas_2_trim_no" <?=($ficha->getVar0067()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="115" name="var_0067" value="B" title="variable: <Fumadora pasiva 2do.>, valor:<si>, id:<0067_B>"
                               id="fuma_pas_2_trim_si" <?=($ficha->getVar0067()=='B')?$checked:'' ?> >
                    </td>
                    
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="116" name="var_0068" value="A" title="variable: <Drogas 2do.>, valor:<no>, id:<0068_A>"
                               id="drogas_2_trim_no" <?=($ficha->getVar0068()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="117" name="var_0068" value="B" title="variable: <Drogas 2do.>, valor:<si>, id:<0068_B>"
                               id="drogas_2_trim_si" <?=($ficha->getVar0068()=='B')?$checked:'' ?> >
                    </td>
                    
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="118" name="var_0069" value="A" title="variable: <Alcohol 2do.>, valor:<no>, id:<0069_A>"
                               id="alcohol_2_trim_no" <?=($ficha->getVar0069()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="119" name="var_0069" value="B" title="variable: <Alcohol 2do.>, valor:<si>, id:<0069_B>"
                               id="alcohol_2_trim_si" <?=($ficha->getVar0069()=='B')?$checked:'' ?> >
                    </td>

                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="120" name="var_0070" value="A" title="variable: <Violencia 2do.>, valor:<no>, id:<0070_A>"
                               id="violencia_2_trim_no" <?=($ficha->getVar0070()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="121" name="var_0070" value="B" title="variable: <Violencia 2do.>, valor:<si>, id:<0070_B>"
                               id="violencia_2_trim_si" <?=($ficha->getVar0070()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3 tarvlbl">3&deg; trim.</label></td>
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="122" name="var_0071" value="A" title="variable: <Fumadora activa 3er.>, valor:<no>, id:<0071_A>"
                               id="fuma_act_3_trim_no" <?=($ficha->getVar0071()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="123" name="var_0071" value="B" title="variable: <Fumadora activa 3er.>, valor:<si>, id:<0071_B>"
                               id="fuma_act_3_trim_si" <?=($ficha->getVar0071()=='B')?$checked:'' ?> >
                    </td>  
                    
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="124" name="var_0072" value="A" title="variable: <Fumadora pasiva 3er.>, valor:<no>, id:<0072_A>"
                               id="fuma_pas_3_trim_no" <?=($ficha->getVar0072()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="125" name="var_0072" value="B" title="variable: <Fumadora pasiva 3er.>, valor:<si>, id:<0072_B>"
                               id="fuma_pas_3_trim_si" <?=($ficha->getVar0072()=='B')?$checked:'' ?> >
                    </td>   
                    
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="126" name="var_0073" value="A" title="variable: <Drogas 3er.>, valor:<no>, id:<0073_A>"
                               id="drogas_3_trim_no" <?=($ficha->getVar0073()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="127" name="var_0073" value="B" title="variable: <Drogas 3er.>, valor:<si>, id:<0073_B>"
                               id="drogas_3_trim_si" <?=($ficha->getVar0073()=='B')?$checked:'' ?> >
                    </td>  
                    
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="128" name="var_0074" value="A" title="variable: <Alcohol 3er.>, valor:<no>, id:<0074_A>"
                               id="alcohol_3_trim_no" <?=($ficha->getVar0074()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="129" name="var_0074" value="B" title="variable: <Alcohol 3er.>, valor:<si>, id:<0074_B>"
                               id="alcohol_3_trim_si" <?=($ficha->getVar0074()=='B')?$checked:'' ?> >
                    </td>
                    
                    <td class="alignright chktd">
                        <input class="black" type="radio" tabindex="130" name="var_0075" value="A" title="variable: <Violencia 3er.>, valor:<no>, id:<0075_A>"
                               id="violencia_3_trim_no" <?=($ficha->getVar0075()=='A')?$checked:'' ?> >
                    </td>
                    <td class="alignleft chktd">
                        <input class="yellow" type="radio" tabindex="131" name="var_0075" value="B" title="variable: <Violencia 3er.>, valor:<si>, id:<0075_B>"
                               id="violencia_3_trim_si" <?=($ficha->getVar0075()=='B')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>    
        <!-- ANTIRUBEOLA 132/135  -->
        <td class="border2" id="antirubeola">
            <table class="inside">
                <tr><td class="titulo" colspan="2"><span class="title3">ANTIRUBEOLA</span></td></tr>
                <tr>
                    <td><label class="title2" for="antirubeola_previa">previa</label></td>
                    <td><label class="title2" for="antirubeola_nosabe">no sabe</label></td>
                </tr>
                <tr>
                    <td>
                        <input class="black" type="radio" tabindex="132" name="var_0076" value="A" title="variable: <Antirub&eacute;ola>, valor:<previa>, id:<0076_A>"
                               id="antirubeola_previa" <?=($ficha->getVar0076()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="133" name="var_0076" value="B" title="variable: <Antirub&eacute;ola>, valor:<no sabe>, id:<0076_B>"
                               id="antirubeola_nosabe" <?=($ficha->getVar0076()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title2" for="antirubeola_embarazo">embarazo</label></td>
                    <td><label class="title2" for="antirubeola_no">no</label></td>
                </tr>
                <tr>
                    <td>
                        <input class="yellow" type="radio" tabindex="134" name="var_0076" value="C" title="variable: <Antirub&eacute;ola>, valor:<embarazo>, id:<0076_C>"
                               id="antirubeola_embarazo" <?=($ficha->getVar0076()=='C')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="135" name="var_0076" value="D" title="variable: <Antirub&eacute;ola>, valor:<no>, id:<0076_D>"
                               id="antirubeola_no" <?=($ficha->getVar0076()=='D')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
        <!-- ANTITETANICA 136/139  -->
        <td class="border2" id="antitetanica">
            <table class="inside">
                <tr><td class="titulo" colspan="3"><span class="title3">ANTITETANICA</span></td></tr>
                <tr>
                    <td><span class="title3">Vigente</span></td>
                    <td><label class="title2" for="antitetanica_no">no</label>
                        <input class="yellow" type="radio" tabindex="136" name="var_0077" value="A" title="variable: <Antitet&aacute;nica>, valor:<no>, id:<0077_A>"
                               id="antitetanica_no" <?=($ficha->getVar0077()=='A')?$checked:'' ?> >
                    </td>
                    <td><label class="title2" for="antitetanica_si">si</label>
                        <input class="black" type="radio" tabindex="137" name="var_0077" value="B" title="variable: <Antitet&aacute;nica>, valor:<si>, id:<0077_B>"
                               id="antitetanica_si"  <?=($ficha->getVar0077()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><span class="title3 block alignright">DOSIS</span>
                        <span class="title3 block alignleft" style="margin-left: 2px">mes gestaci&oacute;n</span></td>
                    <td><label class="title2" for="antitetanica_dosis_1">1&deg;</label>
                        <input tabindex="138" maxlength="1" class="number width1" name="var_0078" title="variable: <Antitet&aacute;nica dosis 1>, valor:<mes>, id:<0078>"
                               id="antitetanica_dosis_1" value="<?=$ficha->getVar0078() ?>" />
                    </td>
                    <td><label class="title2" for="antitetanica_dosis_2">2&deg;</label>
                        <input tabindex="139" maxlength="1" class="number width1" name="var_0079" title="variable: <Antitet&aacute;nica dosis 2>, valor:<mes>, id:<0079>"
                               id="antitetanica_dosis_2" value="<?=$ficha->getVar0079() ?>" />
                    </td>
                </tr>
            </table>
        </td>
        <!-- EXAMENES NORMALES 140/143  -->
        <td class="border2" id="ex_normal">
            <table class="inside">
                <tr><td class="titulo" colspan="3"><span class="title3">EX. NORMAL</span></td></tr>
                </tr>
                <tr>
                    <td valign="top"><span class="title3">ODONT.</span></td>
                    <td><label class="title2" for="ex_odontologico_normal_no">no</label>
                        <input class="yellow" type="radio" tabindex="140" name="var_0080" value="A" title="variable: <Examen odontol&oacute;gico normal>, valor:<no>, id:<0080_A>"
                               id="ex_odontologico_normal_no" <?=($ficha->getVar0080()=='A')?$checked:'' ?> >
                    </td>
                    <td><label class="title2" for="ex_odontologico_normal_si">si</label>
                        <input class="black" type="radio" tabindex="141" name="var_0080" value="B" title="variable: <Examen odontol&oacute;gico normal>, valor:<si>, id:<0080_B>"
                               id="ex_odontologico_normal_si" <?=($ficha->getVar0080()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td valign="top"><span class="title3">MAMAS</span></td>
                    <td><label class="title2" for="ex_mamas_normal_no">no</label>
                        <input class="yellow" type="radio" tabindex="142" name="var_0081" value="A" title="variable: <Examen mamas normal>, valor:<no>, id:<0081_A>"
                               id="ex_mamas_normal_no" <?=($ficha->getVar0081()=='A')?$checked:'' ?> >
                    </td>
                    <td><label class="title2" for="ex_mamas_normal_si">si</label>
                        <input class="black" type="radio" tabindex="143" name="var_0081" value="B" title="variable: <Examen mamas normal>, valor:<si>, id:<0081_B>"
                               id="ex_mamas_normal_si" <?=($ficha->getVar0081()=='B')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>