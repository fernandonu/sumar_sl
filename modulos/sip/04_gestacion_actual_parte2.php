<table id="section4">
    <tr>
<!-- GESTACION ACTUAL - PARTE II  -->                            
        <!-- CERVIX 144/152  -->                    
        <td colspan="2" class="border2" id="cervix">
            <table class="inside">
                <tr><td colspan="3" class="alignleft"><span class="title2">CERVIX</span></td>
                    <td><span class="title3">no se</span></td>
                </tr>
                <tr>
                    <td><span class="title3" style="padding: 0px 3px;">Insp. Visual</span></td>
                    <td><label class="title3" for="cervix_insp_visual_normal">normal</label>
                        <input class="black" type="radio" tabindex="144" name="var_0082" value="A" title="variable: <Cervix insp. Visual>, valor:<normal>, id:<0082_A>"
                               id="cervix_insp_visual_normal" <?=($ficha->getVar0082()=='A')?$checked:'' ?> >
                    </td>
                    <td><label class="title3" for="cervix_insp_visual_anormal">anormal</label>
                        <input class="yellow" type="radio" tabindex="145" name="var_0082" value="B" title="variable: <Cervix insp. Visual>, valor:<anormal>, id:<0082_B>"
                               id="cervix_insp_visual_anormal" <?=($ficha->getVar0082()=='B')?$checked:'' ?> >
                    </td>
                    <td><label class="title3" for="cervix_insp_visual_nohizo">hizo</label>
                        <input class="yellow" type="radio" tabindex="146" name="var_0082" value="C" title="variable: <Cervix insp. Visual>, valor:<no se hizo>, id:<0082_C>"
                               id="cervix_insp_visual_nohizo" <?=($ficha->getVar0082()=='C')?$checked:'' ?> >
                    </td>
                </tr>                
                <tr>
                    <td><span class="title3" style="padding: 0px 3px;">PAP</span></td>
                    <td>
                        <input class="black" type="radio" tabindex="147" name="var_0083" value="A" title="variable: <Cervix PAP>, valor:<normal>, id:<0083_A>"
                               id="cervix_pap_normal" <?=($ficha->getVar0083()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="148" name="var_0083" value="B" title="variable: <Cervix PAP>, valor:<anormal>, id:<0083_B>"
                               id="cervix_pap_anormal" <?=($ficha->getVar0083()=='B')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="149" name="var_0083" value="C" title="variable: <Cervix PAP>, valor:<no se hizo>, id:<0083_C>"
                               id="cervix_pap_nohizo" <?=($ficha->getVar0083()=='C')?$checked:'' ?> >
                    </td>
                </tr>                
                <tr>
                    <td><span class="title3" style="padding: 0px 3px;">COLP</span></td>
                    <td>
                        <input class="black" type="radio" tabindex="150" name="var_0084" value="A" title="variable: <Cervix COLP>, valor:<normal>, id:<0084_A>"
                               id="cervix_colp_normal" <?=($ficha->getVar0084()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="151" name="var_0084" value="B" title="variable: <Cervix COLP>, valor:<anormal>, id:<0084_B>"
                               id="cervix_colp_anormal" <?=($ficha->getVar0084()=='B')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="152" name="var_0084" value="C" title="variable: <Cervix COLP>, valor:<no se hizo>, id:<0084_C>"
                               id="cervix_colp_nohizo" <?=($ficha->getVar0084()=='C')?$checked:'' ?> >
                    </td>
                </tr>                
            </table>
        </td>
        <!-- GRUPO SANGUINEO 153/160  -->                    
        <td class="border2" id="grupo_sanguineo">
            <table class="inside">
                <tr><td> <span class="title2">GRUPO</span>  </td>  
                    <td class="alignright"> <span class="title2">Rh Inmuniz.</span> </td>  
                </tr>
                    <tr>
                        <td><select name="var_0085" id="grupo_sanguineo" class="grupo" tabindex="153" title="variable: <Grupo Sangu&iacute;neo>, valor:<grupo>, id:<0085>">
                                <option value="">&nbsp;</option>
                                <option value="O" <?=($ficha->getVar0085()=='O')?$selected:'' ?> >0</option>
                                <option value="A" <?=($ficha->getVar0085()=='A')?$selected:'' ?> >A</option>
                                <option value="B" <?=($ficha->getVar0085()=='B')?$selected:'' ?> >B</option>
                                <option value="AB" <?=($ficha->getVar0085()=='AB')?$selected:'' ?> >AB</option>
                            </select>
                        </td>
                    <td> 
                        <label class="title1" for="rh_negativo">-</label>
                        <input class="yellow" type="radio" tabindex="154" name="var_0086" value="B" title="variable: <RH>, valor:<->, id:<0086_B>"
                               id="factor_rh_negativo" <?=($ficha->getVar0086()=='B')?$checked:'' ?> >
                        
                        <label class="title2" for="inmunizacion_no">no</label>
                        <input class="black" type="radio" tabindex="156" name="var_0087" value="A" title="variable: <Inmunizaci&oacute;n>, valor:<no>, id:<0087_A>"
                               id="inmunizacion_no" <?=($ficha->getVar0087()=='A')?$checked:'' ?> >
                        <br>
                        <label class="title1" for="rh_positivo" >+</label>
                        <input class="black" type="radio" tabindex="155" name="var_0086" value="A" title="variable: <RH>, valor:<+>, id:<0086_A>"
                               id="factor_rh_positivo" <?=($ficha->getVar0086()=='A')?$checked:'' ?> >

                        <label class="title2" for="inmunizacion_si">si</label>
                        <input class="yellow" type="radio" tabindex="157" name="var_0087" value="B" title="variable: <Inmunizaci&oacute;n>, valor:<si>, id:<0087_B>"
                               id="inmunizacion_si" <?=($ficha->getVar0087()=='B')?$checked:'' ?> > 
                    </td>
                </tr>  
                <tr><td colspan="2"> <span class="title2 aligncenter block"> &gamma;globulina anti D</span> </td></tr>
                <tr><td colspan="2"> 
                        <label class="title2" for="gammaglobulina_no">no</label>
                        <input class="yellow" type="radio" tabindex="158" name="var_0410" value="A" title="variable: <Gamma globulina anti D>, valor:<no>, id:<0410_A>"
                               id="gammaglobulina_no" <?=($ficha->getVar0410()=='A')?$checked:'' ?> >
                        <label class="title2" for="gammaglobulina_si">si</label>
                        <input class="black" type="radio" tabindex="159" name="var_0410" value="B" title="variable: <Gamma globulina anti D>, valor:<si>, id:<0410_B>" 
                               id="gammaglobulina_si" <?=($ficha->getVar0410()=='B')?$checked:'' ?> >
                        <label class="title2" for="gammaglobulina_nc">n/c</label>
                        <input class="black" type="radio" tabindex="160" name="var_0410" value="C" title="variable: <Gamma globulina anti D>, valor:<n/c>, id:<0410_C>"
                               id="gammaglobulina_nc" <?=($ficha->getVar0410()=='C')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
        <!-- TOXOPLASMOSIS 161/169  -->                    
        <td class="border2" id="toxoplasmosis">
            <table class="inside">
                <tr><td colspan="3" class="alignleft titulo"><span class="title3">TOXOPLASMOSIS</span></td>
                    <td><span class="title3">no se</span></td>
                </tr>
                <tr>
                    <td><span class="title3" style="padding: 0px 3px;"><20sem IgG</span></td>
                    <td><label class="title3" for="toxoplasmosis_menor20sem_negativo">-</label>
                        <input class="yellow" type="radio" tabindex="161" name="var_0088" value="A" title="variable: <Tamizaje Antenatal - Toxoplasmosis < 20 sem.>, valor:<->, id:<0088_A>"
                               id="toxoplasmosis_menor20sem_negativo" <?=($ficha->getVar0088()=='A')?$checked:'' ?> >
                    </td>
                    <td><label class="title3" for="toxoplasmosis_menor20sem_positivo">+</label>
                        <input class="black" type="radio" tabindex="162" name="var_0088" value="B" title="variable: <Tamizaje Antenatal - Toxoplasmosis < 20 sem.>, valor:<+>, id:<0088_B>"
                               id="toxoplasmosis_menor20sem_positivo" <?=($ficha->getVar0088()=='B')?$checked:'' ?> >
                    </td>
                    <td><label class="title3" for="toxoplasmosis_menor20sem_nohizo">hizo</label>
                        <input class="yellow" type="radio" tabindex="163" name="var_0088" value="C" title="variable: <Tamizaje Antenatal - Toxoplasmosis < 20 sem.>, valor:<no se hizo>, id:<0088_C>"
                               id="toxoplasmosis_menor20sem_nohizo" <?=($ficha->getVar0088()=='C')?$checked:'' ?> >
                    </td>
                </tr>                
                <tr>
                    <td><span class="title3" style="padding: 0px 3px;">&GreaterEqual;20sem IgG</span></td>
                    <td>
                        <input class="yellow" type="radio" tabindex="164" name="var_0089" value="A" title="variable: <Tamizaje Antenatal - Toxoplasmosis >= 20 sem.>, valor:<->, id:<0089_A>"
                               id="toxoplasmosis_mayor20sem_negativo" <?=($ficha->getVar0089()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="165" name="var_0089" value="B" title="variable: <Tamizaje Antenatal - Toxoplasmosis >= 20 sem.>, valor:<+>, id:<0089_B>"
                               id="toxoplasmosis_mayor20sem_positivo" <?=($ficha->getVar0089()=='B')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="166" name="var_0089" value="C" title="variable: <Tamizaje Antenatal - Toxoplasmosis >= 20 sem.>, valor:<no se hizo>, id:<0089_C>"
                               id="toxoplasmosis_mayor20sem_nohizo" <?=($ficha->getVar0089()=='C')?$checked:'' ?> >
                    </td>
                </tr>                
                <tr style="border-top: 1px dashed black;">
                    <td><span class="title3" style="padding: 0px 3px;">1&deg; consulta IgG</span></td>
                    <td>
                        <input class="yellow" type="radio" tabindex="167" name="var_0090" value="A" title="variable: <Tamizaje Antenatal - Toxoplasmosis 1er. Consulta>, valor:<->, id:<0090_A>"
                               id="toxoplasmosis_1consulta_negativo" <?=($ficha->getVar0090()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="168" name="var_0090" value="B" title="variable: <Tamizaje Antenatal - Toxoplasmosis 1er. Consulta>, valor:<+>, id:<0090_B>"
                               id="toxoplasmosis_1consulta_positivo" <?=($ficha->getVar0090()=='B')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="169" name="var_0090" value="C" title="variable: <Tamizaje Antenatal - Toxoplasmosis 1er. Consulta>, valor:<no se hizo>, id:<0090_C>"
                               id="toxoplasmosis_1consulta_nohizo" <?=($ficha->getVar0090()=='C')?$checked:'' ?> >
                    </td>
                </tr>                
            </table>
        </td>
        <!-- HEMOGLOBINA HB - HIERRO Y FOLATOS 170/177  -->                    
        <td colspan="3" class="border2" id="hemoglobina_fe_folatos">
            <table class="inside">
                <tr>
                    <td class="titulo bottom"><span class="title2">HB <20 sem</span></td>
                    <td class="titulo width1" colspan="2"><span class="title2">Fe/FOLATOS indicados</span></td>
                    <td class="titulo bottom"><span class="title2">HB &GreaterEqual;20 sem</span></td>
                </tr>
                <tr>
                    <td>
                        <input type="text" class="number width50 coma" id="hb_menor20sem" name="var_0095" title="variable: <HB < 20 sem.>, valor:<gramos>, id:<0095>"
                               value="<?=$ficha->getVar0095() ?>" tabindex="170" maxlength="3" min="40" max="250"/>
                    </td>
                    <td><span class="title3 block">Fe</span>
                        <input class="yellow" type="radio" tabindex="172" name="var_0097" value="A" title="variable: <Hierro>, valor:<no>, id:<0097_A>"
                               id="hierro_no" <?=($ficha->getVar0097()=='A')?$checked:'' ?> >
                        <label class="title2 block" for="hierro_no">no</label>
                    </td>
                    <td><span class="title3 block">Folatos</span>
                        <input class="yellow" type="radio" tabindex="174" name="var_0098" value="A" title="variable: <Folatos>, valor:<no>, id:<0098_A>"
                               id="folatos_no" <?=($ficha->getVar0098()=='A')?$checked:'' ?> >
                        <label class="title2 block" for="folatos_no">no</label>
                    </td>
                    <td>
                        <input type="text" class="number width50 coma" id="hb_mayor20sem" name="var_0099" title="variable: <HB >= 20 sem.>, valor:<gramos>, id:<0099>"
                               value="<?=$ficha->getVar0099() ?>" tabindex="176" maxlength="3" min="40" max="250"/>
                    </td>
                </tr>
                <tr>
                    <td><span class="title3" style="vertical-align:super;">< 11,0 g/dl</span>
                        <input class="yellow" tabindex="171" type="checkbox" name="var_0096" value="X" title="variable: <HB < 20 sem, < 11g.>, valor:<si>, id:<0096>"
                               id="hb_menor20sem_menor11g" disabled="disabled" <?=($ficha->getVar0096()=='X')?$checked:'' ?> >
                    </td>
                    <td><input class="black" type="radio" tabindex="172" name="var_0097" value="B" title="variable: <Hierro>, valor:<si>, id:<0097_B>"
                               id="hierro_si" <?=($ficha->getVar0097()=='B')?$checked:'' ?> >
                        <label class="title2 block" for="hierro_si">si</label>
                    </td>
                    <td><input class="black" type="radio" tabindex="175" name="var_0098" value="B" title="variable: <Folatos>, valor:<si>, id:<0098_B>"
                               id="folatos_si" <?=($ficha->getVar0098()=='B')?$checked:'' ?> >
                        <label class="title2 block" for="folatos_si">si</label>
                    </td>
                    <td><span class="title3" style="vertical-align:super;">< 11,0 g/dl</span>
                        <input class="yellow" tabindex="177" type="checkbox" name="var_0100" value="X" title="variable: <HB >= 20 sem, < 11g.>, valor:<si>, id:<0100>"
                               id="hb_mayor20sem_menor11g" disabled="disabled" <?=($ficha->getVar0100()=='X')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
        <!-- VIH DIAGNOSTICO Y TRATAMIENTO < 20 SEM - 201/203 - 207/210 - 215/217  -->                     
        <td class="border2" id="vih_menor20sem">
            <table class="inside">
                <tr class="borderB1"><td class="titulo" colspan="3" ><span class="title2">VIH - Diag.-Tratamiento </span></td></tr> 
                <tr>
                    <td><span class="title2 block"><strong>< 20 sem</strong></span><span class="title3">solicitada</span></td>
                    <td><span class="title2 block">Prueba</span><span class="title3">result.</span></td>
                    <td style="border-left:1px dashed black;"><span class="title2 block">TARV</span><span class="title3">en emb</span></td>
                </tr>
                <tr>
                    <td><label class="title3" for="vih_menor20sem_solicitado_si">si</label>
                        <input class="black" tabindex="201" type="radio" name="var_0091" value="B" title="variable: <Tamizaje Antenatal - VIH < 20 sem. solicitado>, valor:<si>, id:<0091_B>"
                               id="vih_menor20sem_solicitado_si" <?=($ficha->getVar0091()=='B')?$checked:'' ?> >
                    </td>
                    <td class="left-5"><label class="title2" for="prueba_vih_menor20sem_positivo">+</label>
                        <input class="yellow" tabindex="207" type="radio" name="var_0433" value="A" title="variable: <Tamizaje Antenatal - Prueba VIH < 20 sem.>, valor:<positivo>, id:<0433_A>"
                               id="prueba_vih_menor20sem_positivo" <?=($ficha->getVar0433()=='A')?$checked:'' ?> >
                    </td>
                    <td  style="border-left:1px dashed black;"><label class="title3 tarvlbl" for="tarv_hb_menor20sem_si">si&nbsp;</label>
                        <input class="black" tabindex="215" type="radio" name="var_0434" value="B" title="variable: <Tamizaje Antenatal - TARV VIH < 20 sem.>, valor:<si>, id:<0434_B>"
                               id="tarv_hb_menor20sem_si" <?=($ficha->getVar0434()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3" for="vih_menor20sem_solicitado_no">no</label>
                        <input class="yellow" tabindex="202" type="radio" name="var_0091" value="A" title="variable: <Tamizaje Antenatal - VIH < 20 sem. solicitado>, valor:<no>, id:<0091_A>"
                               id="vih_menor20sem_solicitado_no" <?=($ficha->getVar0091()=='A')?$checked:'' ?> >
                    </td>
                    <td><label class="title2" for="prueba_vih_menor20sem_negativo">-</label>
                        <input class="black" tabindex="208" type="radio" name="var_0433" value="B" title="variable: <Tamizaje Antenatal - Prueba VIH < 20 sem.>, valor:<negativo>, id:<0433_B>"
                               id="prueba_vih_menor20sem_negativo" <?=($ficha->getVar0433()=='B')?$checked:'' ?> >
                        <label class="title3" for="prueba_vih_menor20sem_sd">s/d</label>
                    </td>
                    <td  style="border-left:1px dashed black;"><label class="title3 tarvlbl" for="tarv_hb_menor20sem_no">no</label>
                        <input class="black" tabindex="216" type="radio" name="var_0434" value="A" title="variable: <Tamizaje Antenatal - TARV VIH < 20 sem.>, valor:<no>, id:<0434_A>"
                               id="tarv_hb_menor20sem_no" <?=($ficha->getVar0434()=='A')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3" for="vih_menor20sem_solicitado_nc">n/c</label>
                        <input class="black" tabindex="203" type="radio" name="var_0091" value="C" title="variable: <Tamizaje Antenatal - VIH < 20 sem. solicitado>, valor:<n/c>, id:<0091_C>"
                               id="vih_menor20sem_solicitado_nc" <?=($ficha->getVar0091()=='C')?$checked:'' ?> >
                    </td>
                    <td><label class="title2" for="prueba_vih_menor20sem_nc">n/c</label>
                        <input class="black" tabindex="209" type="radio" name="var_0433" value="C" title="variable: <Tamizaje Antenatal - Prueba VIH < 20 sem.>, valor:<n/c>, id:<0433_C>"
                               id="prueba_vih_menor20sem_nc" <?=($ficha->getVar0433()=='C')?$checked:'' ?> >
                        <input class="yellow" tabindex="210" type="radio" name="var_0433" value="D" title="variable: <Tamizaje Antenatal - Prueba VIH < 20 sem.>, valor:<s/d>, id:<0433_D>"
                               id="prueba_vih_menor20sem_sd" <?=($ficha->getVar0433()=='D')?$checked:'' ?> >
                    </td>
                    <td  style="border-left:1px dashed black;"><label class="title3 tarvlbl" for="tarv_hb_menor20sem_nc">n/c</label>
                        <input class="black" tabindex="217" type="radio" name="var_0434" value="C" title="variable: <Tamizaje Antenatal - TARV VIH < 20 sem.>, valor:<n/c>, id:<0434_C>"
                               id="tarv_hb_menor20sem_nc" <?=($ficha->getVar0434()=='C')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
        <!-- SIFILIS 221/256  --> 
        <td rowspan="2" class="border2 top" id="sifilis">
            <table class="inside">
                <tr class="borderB1"><td class="titulo" colspan="4" ><span class="title2">SIFILIS - Diagn&oacute;stico y Tratamiento</span></td></tr>   
                <tr>
                    <td colspan="2"><span class="title2">Prueba</span></td>
                    <td class="borderL1"><span class="title2">Tratamiento</span></td>
                    <td class="borderL1" style="width:46px;"><span class="title3">Tto de la pareja</span></td>
                </tr>
                <tr>
                    <td><span class="title3">no trepon&eacute;mica</span></td>
                    <td class="borderL1"><span class="title3">trepon&eacute;mica</span></td>
                    <td class="borderL1"><span class="title2">&nbsp;</span></td>
                    <td class="borderL1"><span class="title3">&nbsp;</span></td>
                </tr>
                <tr>
                    <td> <label class="title2 width16 inlblock" for="sifilis_menor20sem_notreponemica_negativo">-</label> 
                         <label class="title2 width16 inlblock" for="sifilis_menor20sem_notreponemica_positivo">+</label> 
                         <label class="title3 width16 inlblock" for="sifilis_menor20sem_notreponemica_sd">s/d</label> 
                    </td>
                    <td class="borderL1"> <label class="title2 width16 inlblock" for="sifilis_menor20sem_treponemica_negativo">-</label> 
                         <label class="title2 width16 inlblock" for="sifilis_menor20sem_treponemica_positivo">+</label> 
                         <label class="title3 width16 inlblock" for="sifilis_menor20sem_treponemica_sd">s/d</label> 
                         <label class="title3 width16 inlblock" for="sifilis_menor20sem_treponemica_nc">n/c</label> 
                    </td>
                    <td class="borderL1"> <label class="title2 width16 inlblock" for="sifilis_menor20sem_tratamiento_negativo">-</label> 
                         <label class="title2 width16 inlblock" for="sifilis_menor20sem_tratamiento_positivo">+</label> 
                         <label class="title3 width16 inlblock" for="sifilis_menor20sem_tratamiento_sd">s/d</label> 
                         <label class="title3 width16 inlblock" for="sifilis_menor20sem_tratamiento_nc">n/c</label> 
                    </td>
                    <td class="borderL1"><label class="title3 width16 inlblock" for="sifilis_menor20sem_tratamiento_pareja_no">no</label> 
                         <label class="title3 width16 inlblock" for="sifilis_menor20sem_tratamiento_pareja_si">si</label> 
                    </td>
                </tr>
                <tr>
                    <td><input class="black" tabindex="221" type="radio" name="var_0112" value="A" title="variable: <Prueba S&iacute;filis No Trepon&eacute;mica < 20 sem.>, valor:<negativo>, id:<0112_A>"
                               id="sifilis_menor20sem_notreponemica_negativo" <?=($ficha->getVar0112()=='A')?$checked:'' ?> >
                        <input class="yellow" tabindex="222" type="radio" name="var_0112" value="B" title="variable: <Prueba S&iacute;filis No Trepon&eacute;mica < 20 sem.>, valor:<positivo>, id:<0112_B>"
                               id="sifilis_menor20sem_notreponemica_positivo" <?=($ficha->getVar0112()=='B')?$checked:'' ?> >
                        <input class="yellow" tabindex="223" type="radio" name="var_0112" value="C" title="variable: <Prueba S&iacute;filis No Trepon&eacute;mica < 20 sem.>, valor:<se desconoce>, id:<0112_C>"
                               id="sifilis_menor20sem_notreponemica_sd" <?=($ficha->getVar0112()=='C')?$checked:'' ?> >
                    </td>
                    <td class="borderL1">
                        <input class="black" tabindex="229" type="radio" name="var_0415" value="A" title="variable: <Prueba S&iacute;filis Trepon&eacute;mica < 20 sem.>, valor:<negativo>, id:<0415_A>"
                               id="sifilis_menor20sem_treponemica_negativo" <?=($ficha->getVar0415()=='A')?$checked:'' ?> >
                        <input class="yellow" tabindex="230" type="radio" name="var_0415" value="B" title="variable: <Prueba S&iacute;filis Trepon&eacute;mica < 20 sem.>, valor:<positivo>, id:<0415_B>"
                               id="sifilis_menor20sem_treponemica_positivo" <?=($ficha->getVar0415()=='B')?$checked:'' ?> >
                        <input class="yellow" tabindex="231" type="radio" name="var_0415" value="C" title="variable: <Prueba S&iacute;filis Trepon&eacute;mica < 20 sem.>, valor:<se desconoce>, id:<0415_C>"
                               id="sifilis_menor20sem_treponemica_sd" <?=($ficha->getVar0415()=='C')?$checked:'' ?> >
                        <input class="black" tabindex="232" type="radio" name="var_0415" value="D" title="variable: <Prueba S&iacute;filis Trepon&eacute;mica < 20 sem.>, valor:<no corresponde>, id:<0415_D>"
                               id="sifilis_menor20sem_treponemica_nc" <?=($ficha->getVar0415()=='D')?$checked:'' ?> >
                    </td>
                    <td class="borderL1">
                        <input class="yellow" tabindex="239" type="radio" name="var_0115" value="A" title="variable: <Tratamiento S&iacute;filis < 20 sem.>, valor:<negativo>, id:<0115_A>"
                               id="sifilis_menor20sem_tratamiento_negativo" <?=($ficha->getVar0115()=='A')?$checked:'' ?> >
                        <input class="black" tabindex="240" type="radio" name="var_0115" value="B" title="variable: <Tratamiento S&iacute;filis < 20 sem.>, valor:<positivo>, id:<0115_B>"
                               id="sifilis_menor20sem_tratamiento_positivo" <?=($ficha->getVar0115()=='B')?$checked:'' ?> >
                        <input class="yellow" tabindex="241" type="radio" name="var_0115" value="C" title="variable: <Tratamiento S&iacute;filis < 20 sem.>, valor:<se desconoce>, id:<0115_C>"
                               id="sifilis_menor20sem_tratamiento_sd" <?=($ficha->getVar0115()=='C')?$checked:'' ?> >
                        <input class="black" tabindex="242" type="radio" name="var_0115" value="D" title="variable: <Tratamiento S&iacute;filis < 20 sem.>, valor:<no corresponde>, id:<0115_D>"
                               id="sifilis_menor20sem_tratamiento_nc" <?=($ficha->getVar0115()=='D')?$checked:'' ?> >
                    </td>
                    <td class="borderL1">
                        <input class="yellow" tabindex="249" type="radio" name="var_0418" value="A" title="variable: <Tratamiento s&iacute;filis pareja < 20 sem.>, valor:<no>, id:<0418_A>"
                               id="sifilis_menor20sem_tratamiento_pareja_no" <?=($ficha->getVar0418()=='A')?$checked:'' ?> >
                        <input class="black" tabindex="250" type="radio" name="var_0418" value="B" title="variable: <Tratamiento s&iacute;filis pareja < 20 sem.>, valor:<si>, id:<0418_B>"
                               id="sifilis_menor20sem_tratamiento_pareja_si" <?=($ficha->getVar0418()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px dashed black;"><label class="title3"> <20 sem </label>
                        <input type="text" style="margin: 6px 0;" class="number width2 men20sem" name="var_0413" title="variable: <Semana prueba s&iacute;filis No trepon. < 20 sem.>, valor:<semana>, id:<0413>"
                               id="sifilis_menor20sem_notreponemica_semana" value="<?=$ficha->getVar0413() ?>" tabindex="224" maxlength="2" min="1" max="19"/>
                    </td>
                    <td style="border-bottom: 1px dashed black;" class="borderL1">
                        <input class="number width2 men20sem" name="var_0414" title="variable: <Semana prueba s&iacute;filis Trepon&eacute;mica < 20 sem.>, valor:<semana>, id:<0414>"
                                id="sifilis_menor20sem_treponemica_semana" value="<?=$ficha->getVar0414() ?>" tabindex="233" maxlength="2" min="1" max="19"/>
                    </td>
                    <td style="border-bottom: 1px dashed black;" class="borderL1">
                        <input class="number width2 men20sem" name="var_0416" title="variable: <Semana tratamiento s&iacute;filis < 20 sem.>, valor:<semana>, id:<0416>"
                                id="sifilis_menor20sem_tratamiento_semana" value="<?=$ficha->getVar0416() ?>" tabindex="243" maxlength="2" min="1" max="19"/>
                    </td>
                    <td style="border-bottom: 1px dashed black;" class="borderL1">
                        <div><label class="title3 width16 inlblock" for="sifilis_menor20sem_tratamiento_pareja_sd">s/d</label>
                            <label class="title3 width16 inlblock" for="sifilis_menor20sem_tratamiento_pareja_nc">n/c</label> 
                        </div>
                        <input class="yellow" tabindex="251" type="radio" name="var_0418" value="C" title="variable: <Tratamiento s&iacute;filis pareja < 20 sem.>, valor:<se desconoce>, id:<0418_C>"
                               id="sifilis_menor20sem_tratamiento_pareja_sd" <?=($ficha->getVar0418()=='C')?$checked:'' ?> >
                        <input class="black" tabindex="252" type="radio" name="var_0418" value="D" title="variable: <Tratamiento s&iacute;filis pareja < 20 sem.>, valor:<no corresponde>, id:<0418_D>"
                               id="sifilis_menor20sem_tratamiento_pareja_nc" <?=($ficha->getVar0418()=='D')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3"> &GreaterEqual;20 sem </label>
                        <input type="text" style="margin: 6px 0;" class="number width2 may20sem" name="var_0419" title="variable: <Semana prueba s&iacute;filis No trepon. >= 20 sem.>, valor:<semana>, id:<0419>"
                               id="sifilis_mayor20sem_notreponemica_semana" value="<?=$ficha->getVar0419() ?>" tabindex="225" maxlength="2" min="20" max="40" />
                    </td>
                    <td class="borderL1"><input type="text" class="number width2 may20sem" name="var_0420" title="variable: <Semana prueba s&iacute;filis Trepon&eacute;mica >= 20 sem.>, valor:<semana>, id:<0420>"
                                                id="sifilis_mayor20sem_treponemica_semana" value="<?=$ficha->getVar0420() ?>" tabindex="234" maxlength="2" min="20" max="40"/></td>
                    <td class="borderL1"><input type="text" class="number width2 may20sem" name="var_0422" title="variable: <Semana tratamiento s&iacute;filis >= 20 sem.>, valor:<semana>, id:<0422>"
                                                id="sifilis_mayor20sem_tratamiento_semana" value="<?=$ficha->getVar0422() ?>" tabindex="244" maxlength="2" min="20" max="40"/></td>
                    <td class="borderL1">
                        <div><label class="title3 width1 inlblock" for="sifilis_mayor20sem_tratamiento_pareja_no">no</label>
                            <label class="title3 width1 inlblock" for="sifilis_menor20sem_ttopareja_si">si</label> 
                        </div>
                        <input class="yellow" tabindex="253" type="radio" name="var_0424" value="A" title="variable: <Tratamiento s&iacute;filis pareja >= 20 sem.>, valor:<no>, id:<0424_A>"
                               id="sifilis_mayor20sem_tratamiento_pareja_no" <?=($ficha->getVar0424()=='A')?$checked:'' ?> >
                        <input class="black" tabindex="254" type="radio" name="var_0424" value="B" title="variable: <Tratamiento s&iacute;filis pareja >= 20 sem.>, valor:<si>, id:<0424_B>"
                               id="sifilis_mayor20sem_tratamiento_pareja_si" <?=($ficha->getVar0424()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td> <label class="title2 width16 inlblock" for="sifilis_mayor20sem_notreponemica_negativo">-</label> 
                         <label class="title2 width16 inlblock" for="sifilis_mayor20sem_notreponemica_positivo">+</label> 
                         <label class="title3 width16 inlblock" for="sifilis_mayor20sem_notreponemica_sd">s/d</label> 
                    </td>
                    <td class="borderL1"> <label class="title2 width16 inlblock" for="sifilis_mayor20sem_treponemica_negativo">-</label> 
                         <label class="title2 width16 inlblock" for="sifilis_mayor20sem_treponemica_positivo">+</label> 
                         <label class="title3 width16 inlblock" for="sifilis_mayor20sem_treponemica_sd">s/d</label> 
                         <label class="title3 width16 inlblock" for="sifilis_mayor20sem_treponemica_nc">n/c</label> 
                    </td>
                    <td class="borderL1"> <label class="title2" for="sifilis_mayor20sem_tratamiento_negativo">-</label> 
                         <label class="title2 width16 inlblock" for="sifilis_mayor20sem_tratamiento_positivo">+</label> 
                         <label class="title3 width16 inlblock" for="sifilis_mayor20sem_tratamiento_sd">s/d</label> 
                         <label class="title3 width16 inlblock" for="sifilis_mayor20sem_tratamiento_nc">n/c</label> 
                    </td>
                    <td class="borderL1"><label class="title3 width16 inlblock" for="sifilis_mayor20sem_tratamiento_pareja_sd">s/d</label> 
                         <label class="title3 width16 inlblock" for="sifilis_mayor20sem_tratamiento_pareja_nc">n/c</label> 
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 8px;">
                        <input class="black" tabindex="226" type="radio" name="var_0114" value="A" title="variable: <Prueba S&iacute;filis No Trepon&eacute;mica >= 20 sem.>, valor:<negativo>, id:<0114_A>"
                               id="sifilis_mayor20sem_notreponemica_negativo" <?=($ficha->getVar0114()=='A')?$checked:'' ?> >
                        <input class="yellow" tabindex="227" type="radio" name="var_0114" value="B" title="variable: <Prueba S&iacute;filis No Trepon&eacute;mica >= 20 sem.>, valor:<positivo>, id:<0114_B>"
                               id="sifilis_mayor20sem_notreponemica_positivo" <?=($ficha->getVar0114()=='B')?$checked:'' ?> >
                        <input class="yellow" tabindex="228" type="radio" name="var_0114" value="C" title="variable: <Prueba S&iacute;filis No Trepon&eacute;mica >= 20 sem.>, valor:<se desconoce>, id:<0114_C>"
                               id="sifilis_mayor20sem_notreponemica_sd" <?=($ficha->getVar0114()=='C')?$checked:'' ?> >
                    </td>
                    <td style="padding-bottom: 8px;" class="borderL1">
                        <input class="black" tabindex="235" type="radio" name="var_0421" value="A" title="variable: <Prueba S&iacute;filis Trepon&eacute;mica >= 20 sem.>, valor:<negativo>, id:<0421_A>"
                               id="sifilis_mayor20sem_treponemica_negativo" <?=($ficha->getVar0421()=='A')?$checked:'' ?> >
                        <input class="yellow" tabindex="236" type="radio" name="var_0421" value="B" title="variable: <Prueba S&iacute;filis Trepon&eacute;mica >= 20 sem.>, valor:<positivo>, id:<0421_B>"
                               id="sifilis_mayor20sem_treponemica_positivo" <?=($ficha->getVar0421()=='B')?$checked:'' ?> >
                        <input class="yellow" tabindex="237" type="radio" name="var_0421" value="C" title="variable: <Prueba S&iacute;filis Trepon&eacute;mica >= 20 sem.>, valor:<se desconoce>, id:<0421_C>"
                               id="sifilis_mayor20sem_treponemica_sd" <?=($ficha->getVar0421()=='C')?$checked:'' ?> >
                        <input class="black" tabindex="238" type="radio" name="var_0421" value="D" title="variable: <Prueba S&iacute;filis Trepon&eacute;mica >= 20 sem.>, valor:<no corresponde>, id:<0421_D>"
                               id="sifilis_mayor20sem_treponemica_nc" <?=($ficha->getVar0421()=='D')?$checked:'' ?> >
                    </td>
                    <td style="padding-bottom: 8px;" class="borderL1">
                        <input class="yellow" tabindex="245" type="radio" name="var_0423" value="A" title="variable: <Tratamiento S&iacute;filis >= 20 sem.>, valor:<negativo>, id:<0423_A>"
                               id="sifilis_mayor20sem_tratamiento_negativo" <?=($ficha->getVar0423()=='A')?$checked:'' ?> >
                        <input class="black" tabindex="246" type="radio" name="var_0423" value="B" title="variable: <Tratamiento S&iacute;filis >= 20 sem.>, valor:<positivo>, id:<0423_B>"
                               id="sifilis_mayor20sem_tratamiento_positivo" <?=($ficha->getVar0423()=='B')?$checked:'' ?> >
                        <input class="yellow" tabindex="247" type="radio" name="var_0423" value="C" title="variable: <Tratamiento S&iacute;filis >= 20 sem.>, valor:<se desconoce>, id:<0423_C>"
                               id="sifilis_mayor20sem_tratamiento_sd" <?=($ficha->getVar0423()=='C')?$checked:'' ?> >
                        <input class="black" tabindex="248" type="radio" name="var_0423" value="D" title="variable: <Tratamiento S&iacute;filis >= 20 sem.>, valor:<no corresponde>, id:<0423_D>"
                               id="sifilis_mayor20sem_tratamiento_nc" <?=($ficha->getVar0423()=='D')?$checked:'' ?> >
                    </td>
                    <td style="padding-bottom: 8px;" class="borderL1">
                        <input class="yellow" tabindex="255" type="radio" name="var_0424" value="C" title="variable: <Tratamiento s&iacute;filis pareja >= 20 sem.>, valor:<se desconoce>, id:<0424_C>"
                               id="sifilis_mayor20sem_tratamiento_pareja_sd" <?=($ficha->getVar0424()=='C')?$checked:'' ?> >
                        <input class="black" tabindex="256" type="radio" name="var_0424" value="D" title="variable: <Tratamiento s&iacute;filis pareja >= 20 sem.>, valor:<no corresponde>, id:<0424_D>"
                               id="sifilis_mayor20sem_tratamiento_pareja_nc" <?=($ficha->getVar0424()=='D')?$checked:'' ?> >
                    </td>
                </tr>                
            </table>
        </td>
        
    </tr>
    <tr>
        <!-- CHAGAS 178/180  -->
        <td class="border2" id="chagas">
            <table class="inside">
                <tr>
                    <td><span class="title2">C<br>H<br>A<br>G<br>A<br>S</span><label class="title3" for="chagas_nohizo">no se hizo</label></td>
                    <td>
                        <input class="black" type="radio" tabindex="178" name="var_0101"  value="A" title="variable: <Tamizaje Antenatal - Chagas>, valor:<->, id:<0101_A>"
                               id="chagas_negativo" <?=($ficha->getVar0101()=='A')?$checked:'' ?> >
                        <label class="title1" for="chagas_negativo" style="margin-right: 5px;">-</label>
                        <input class="yellow" type="radio" tabindex="179" name="var_0101"  value="B" title="variable: <Tamizaje Antenatal - Chagas>, valor:<+>, id:<0101_B>"
                               id="chagas_positivo" <?=($ficha->getVar0101()=='B')?$checked:'' ?> >
                        <label class="title1" for="chagas_positivo" style="margin-right: 5px;">+</label>
                        <input class="yellow" type="radio" tabindex="180" name="var_0101"  value="C" title="variable: <Tamizaje Antenatal - Chagas>, valor:<no se hizo>, id:<0101_C>"
                               id="chagas_nohizo" <?=($ficha->getVar0101()=='C')?$checked:'' ?> >
                    </td>
                </tr>    
            </table>
        </td>
        <!-- PALUDISMO - MALARIA 181/183  -->                    
       <td class="border2" id="paludismo_malaria">
            <table class="inside">
                <tr>
                    <td colspan="2"><span class="title3">HEPATITIS B</span></td>
                </tr>
                <tr>
                    <td>
                        <input class="black" type="radio" tabindex="181" name="var_0102" value="A" title="variable: <Tamizaje Antenatal - Malaria>, valor:<->, id:<0102_A>"
                               id="paludismo_malaria_negativo" <?=($ficha->getVar0102()=='A')?$checked:'' ?> >
                        <label class="title1" for="paludismo_malaria_negativo">-</label>
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="182" name="var_0102" value="B" title="variable: <Tamizaje Antenatal - Malaria>, valor:<+>, id:<0102_B>"
                               id="paludismo_malaria_positivo" <?=($ficha->getVar0102()=='B')?$checked:'' ?> >
                        <label class="title1" for="paludismo_malaria_positivo">+</label>
                    </td>
                </tr>    
                <tr>
                    <td colspan="2">
                        <input class="yellow" type="radio" tabindex="183" name="var_0102" value="C" title="variable: <Tamizaje Antenatal - Malaria>, valor:<no se hizo>, id:<0102_C>"
                               id="paludismo_malaria_nohizo" <?=($ficha->getVar0102()=='C')?$checked:'' ?> >
                        <label class="title1" for="paludismo_malaria_nohizo">no se hizo</label>
                    </td>
                </tr>
            </table>
        </td>
        <!-- BACTERIURIA 184/189  -->                    
        <td class="border2" id="bacteriuria">
            <table class="inside">
                <tr><td colspan="4" class="titulo"><span class="title2">BACTERIURIA</span></td></tr>
                <tr>
                    <td><span class="title3" style="padding: 0px 3px;">Sem <20</span></td>
                    <td style="padding-left: 4px;"><label class="title3" for="bacteriuria_menor20sem_normal">normal</label>
                        <input class="black" type="radio" tabindex="184" name="var_0103" value="A" title="variable: <Bacteriuria < 20 sem.>, valor:<normal>, id:<0103_A>"
                               id="bacteriuria_menor20sem_normal" <?=($ficha->getVar0103()=='A')?$checked:'' ?> >
                    </td>
                    <td style="padding-left: 4px;"><label class="title3" for="bacteriuria_menor20sem_anormal">anormal</label>
                        <input class="yellow" type="radio" tabindex="185" name="var_0103" value="B" title="variable: <Bacteriuria < 20 sem.>, valor:<anormal>, id:<0103_B>"
                               id="bacteriuria_menor20sem_anormal" <?=($ficha->getVar0103()=='B')?$checked:'' ?> >
                    </td>
                    <td valign="top" style="padding-left: 4px;"><label class="title3" for="bacteriuria_menor20sem_nohizo">no se hizo</label>
                        <input class="yellow" type="radio" tabindex="186" name="var_0103" value="C" title="variable: <Bacteriuria < 20 sem.>, valor:<no se hizo>, id:<0103_C>"
                               id="bacteriuria_menor20sem_nohizo" <?=($ficha->getVar0103()=='C')?$checked:'' ?> >
                    </td>
                </tr>                
                <tr>
                    <td><span class="title3" style="padding: 0px 3px;">&GreaterEqual;20</span></td>
                    <td style="padding-left: 4px;">
                        <input class="black" type="radio" tabindex="187" name="var_0104" value="A" title="variable: <Bacteriuria >= 20 sem.>, valor:<normal>, id:<0104_A>"
                               id="bacteriuria_mayor20sem_normal" <?=($ficha->getVar0104()=='A')?$checked:'' ?> >
                    </td>
                    <td style="padding-left: 4px;">
                        <input class="yellow" type="radio" tabindex="188" name="var_0104" value="B" title="variable: <Bacteriuria >= 20 sem.>, valor:<anormal>, id:<0104_B>"
                               id="bacteriuria_mayor20sem_anormal" <?=($ficha->getVar0104()=='B')?$checked:'' ?> >
                    </td>
                    <td style="padding-left: 4px;">
                        <input class="yellow" type="radio" tabindex="189" name="var_0104" value="C" title="variable: <Bacteriuria >= 20 sem.>, valor:<no se hizo>, id:<0104_C>"
                               id="bacteriuria_mayor20sem_nohizo" <?=($ficha->getVar0104()=='C')?$checked:'' ?> >
                    </td>
                </tr>                
            </table>
        </td>
        <!-- GLUCEMIA EN AYUNAS 190/193  -->                    
        <td class="border2" id="glucemia">
            <table class="inside">
                <tr><td class="titulo" colspan="2"><span class="title3">GLUCEMIA EN AYUNAS</span></td></tr>   
                <tr>
                    <td><label style="margin-left:3px;" class="title3" for="glucemia_menor20sem"><20 sem</label>
                        <input type="text" class="number width50" id="glucemia_menor20sem" name="var_0105" title="variable: <Glucemia < 20 sem.>, valor:<gramos>, id:<0105>"
                               value="<?=$ficha->getVar0105() ?>" tabindex="190" maxlength="3" min="10" max="500" />
                    </td>
                    <td valign="bottom">
                        <input class="yellow" tabindex="191" type="checkbox" name="var_0106" value="X" disabled="disabled" title="variable: <Glucemia < 20 sem. > 1.05>, valor:<si>, id:<0106>"
                                id="glucemia_menor20sem_mayor105" <?=($ficha->getVar0106()=='X')?$checked:'' ?> >
                        <span class="title3 block">&GreaterEqual;105</span></td>
                </tr>
                <tr>
                    <td><label style="margin-left:3px;" class="title3" for="glucemia_mayor30sem">&GreaterEqual;30 sem</label>
                        <input type="text" class="number width50" id="glucemia_mayor30sem" name="var_0108" title="variable: <Glucemia >= 30 sem.>, valor:<gramos>, id:<0108>"
                               value="<?=$ficha->getVar0108() ?>" tabindex="192" maxlength="3" min="10" max="500" />
                    </td>
                    <td valign="top"><span class="title3 block">mg/dl</span>
                        <input class="yellow" tabindex="193" type="checkbox" name="var_0107" value="X" disabled="disabled" title="variable: <Glucemia >= 30 sem. > 1.05>, valor:<si>, id:<0107>"
                               id="glucemia_mayor30sem_mayor105" <?=($ficha->getVar0107()=='X')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
        <!-- ESTREPTOCOCO B 194/196  -->
        <td class="border2" id="estreptococo_b">
            <table class="inside">
                <tr><td class="titulo" colspan="3">
                        <span class="title3">ESTREPTO- COCO B</span>
                        <span class="title3" style="padding-top: 6px;">35-37 semanas</span>
                    </td>
                </tr>  
                <tr>
                    <td>
                        <input class="black" type="radio" tabindex="194" name="var_0109" value="A" title="variable: <Tamizaje Antenatal - Estreptococo B>, valor:<->, id:<0109_A>"
                               id="estreptococo_b_negativo" <?=($ficha->getVar0109()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="195" name="var_0109" value="B" title="variable: <Tamizaje Antenatal - Estreptococo B>, valor:<+>, id:<0109_B>"
                               id="estreptococo_b_positivo" <?=($ficha->getVar0109()=='B')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="196" name="var_0109" value="C" title="variable: <Tamizaje Antenatal - Estreptococo B>, valor:<no se hizo>, id:<0109_C>"
                               id="estreptococo_b_nohizo" <?=($ficha->getVar0109()=='C')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title2" for="estreptococo_b_negativo">-</label></td>
                    <td><label class="title2" for="estreptococo_b_positivo">+</label></td>
                    <td><label class="title3" for="estreptococo_b_nohizo">no se hizo</label></td>
                </tr>
            </table>
        </td>
        <!-- PREPARACION PARA EL PARTO 197/198  -->                    
        <td class="border2" id="preparacion_parto">
            <table class="inside">
                <tr><td class="titulo" colspan="2"><span class="title3">PREPARA- CI&Oacute;N<br>PARA EL PARTO</span></td>
                </tr>  
                <tr>
                    <td>
                        <input class="yellow" type="radio" tabindex="197" name="var_0110" value="A" title="variable: <Preparaci&oacute;n Parto>, valor:<no>, id:<0110_A>"
                               id="preparacion_parto_no" <?=($ficha->getVar0110()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="black" type="radio" tabindex="198" name="var_0110" value="B" title="variable: <Preparaci&oacute;n Parto>, valor:<si>, id:<0110_B>"
                               id="preparacion_parto_si" <?=($ficha->getVar0110()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title2" for="preparacion_parto_no">no</label></td>
                    <td><label class="title2" for="preparacion_parto_si">si</label></td>
                </tr>
            </table>
        </td>
        <!-- CONSERJERIA LACTANCIA MATERNA 199/200  -->                    
        <td class="border2" id="conserjeria_lactancia">
            <table class="inside">
                <tr><td class="titulo" colspan="2"><span class="title3">CONSERJERIA LACTANCIA MATERNA</span></td>
                </tr>  
                <tr>
                    <td>
                        <input class="yellow" type="radio" tabindex="199" name="var_0111" value="A" title="variable: <Conserjer&iacute; lactancia>, valor:<no>, id:<0111_A>"
                               id="conserjeria_lactancia_no" <?=($ficha->getVar0111()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input class="black" type="radio" tabindex="200" name="var_0111" value="B" title="variable: <Conserjer&iacute; lactancia>, valor:<si>, id:<0111_B>"
                               id="conserjeria_lactancia_si" <?=($ficha->getVar0111()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title2" for="conserjeria_lactancia_no">no</label></td>
                    <td><label class="title2" for="conserjeria_lactancia_si">si</label></td>
                </tr>
            </table>
        </td>
        <!-- VIH DIAGNOSTICO Y TRATAMIENTO >= 20 SEM - 204/206 - 211/214 - 218/220  -->                  
        <td class="border2" id="vih_mayor20sem">
            <table class="inside">
                <tr>
                    <td><span class="title2 block"><strong>&GreaterEqual; 20 sem</strong></span><span class="title3">solicitada</span></td>
                    <td><span class="title2 block">Prueba</span><span class="title3">result.</span></td>
                    <td style="border-left:1px dashed black;"><span class="title2 block">TARV</span><span class="title3">en emb</span></td>
                </tr>
                <tr>
                    <td><label class="title3" for="vih_mayor20sem_solicitado_si">si</label>
                        <input class="black" tabindex="204" type="radio" name="var_0093" value="B" title="variable: <Tamizaje Antenatal - VIH >= 20 sem. solicitado>, valor:<si>, id:<0093_B>"
                               id="vih_mayor20sem_solicitado_si" <?=($ficha->getVar0093()=='B')?$checked:'' ?> >
                    </td>
                    <td  class="left-5"><label class="title2" for="hb_mayor20sem_mayor11g_positivo">+</label>
                        <input class="yellow" tabindex="211" type="radio" name="var_0435" value="A" title="variable: <Tamizaje Antenatal - Prueba VIH >= 20 sem.>, valor:<positivo>, id:<0435_A>"
                               id="hb_mayor20sem_mayor11g_positivo" <?=($ficha->getVar0435()=='A')?$checked:'' ?> >
                    </td>
                    <td  style="border-left:1px dashed black;"><label class="title3 tarvlbl" for="tarv_hb_mayor20sem_si">si&nbsp;</label>
                        <input class="black" tabindex="218" type="radio" name="var_0436" value="B" title="variable: <Tamizaje Antenatal - TARV VIH >= 20 sem.>, valor:<si>, id:<0436_B>"
                               id="tarv_hb_mayor20sem_si" <?=($ficha->getVar0436()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3" for="vih_mayor20sem_solicitado_no">no</label>
                        <input class="yellow" tabindex="205" type="radio" name="var_0093" value="A" title="variable: <Tamizaje Antenatal - VIH >= 20 sem. solicitado>, valor:<no>, id:<0093_A>"
                               id="vih_mayor20sem_solicitado_no" <?=($ficha->getVar0093()=='A')?$checked:'' ?> >
                    </td>
                    <td><label class="title2" for="prueba_vih_mayor20sem_negativo">-</label>
                        <input class="black" tabindex="212" type="radio" name="var_0435" value="B" title="variable: <Tamizaje Antenatal - Prueba VIH >= 20 sem.>, valor:<negativo>, id:<0435_B>"
                               id="prueba_vih_mayor20sem_negativo" <?=($ficha->getVar0435()=='B')?$checked:'' ?> >
                        <label class="title3" for="prueba_vih_mayor20sem_sd">s/d</label>
                    </td>
                    <td  style="border-left:1px dashed black;"><label class="title3 tarvlbl" for="tarv_hb_mayor20sem_no">no</label>
                        <input class="black" tabindex="219" type="radio" name="var_0436" value="A" title="variable: <Tamizaje Antenatal - TARV VIH >= 20 sem.>, valor:<no>, id:<0436_A>"
                               id="tarv_hb_mayor20sem_no" <?=($ficha->getVar0436()=='A')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3" for="vih_mayor20sem_solicitado_nc">n/c</label>
                        <input class="black" tabindex="206" type="radio" name="var_0093" value="C" title="variable: <Tamizaje Antenatal - VIH >= 20 sem. solicitado>, valor:<n/c>, id:<0093_C>"
                               id="vih_mayor20sem_solicitado_nc" <?=($ficha->getVar0093()=='C')?$checked:'' ?> >
                    </td>
                    <td><label class="title2" for="prueba_vih_mayor20sem_nc">n/c</label>
                        <input class="black" tabindex="213" type="radio" name="var_0435" value="C" title="variable: <Tamizaje Antenatal - Prueba VIH >= 20 sem.>, valor:<n/c>, id:<0435_C>"
                               id="prueba_vih_mayor20sem_nc" <?=($ficha->getVar0435()=='C')?$checked:'' ?> >
                        <input class="yellow" tabindex="214" type="radio" name="var_0435" value="D" title="variable: <Tamizaje Antenatal - Prueba VIH >= 20 sem.>, valor:<s/d>, id:<0435_D>"
                               id="prueba_vih_mayor20sem_sd" <?=($ficha->getVar0435()=='D')?$checked:'' ?> >
                    </td>
                    <td  style="border-left:1px dashed black;"><label class="title3 tarvlbl" for="tarv_hb_mayor20sem_nc">n/c</label>
                        <input class="black" tabindex="220" type="radio" name="var_0436" value="C" title="variable: <Tamizaje Antenatal - TARV VIH >= 20 sem.>, valor:<n/c>, id:<0436_C>"
                               id="tarv_hb_mayor20sem_nc" <?=($ficha->getVar0436()=='C')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>