<table id="section10">
    <!-- RECIEN NACIDO - 715/765  -->   
    <tr>
        <td class="border2" id="recien_nacido">
            <table class="inside">
                <tr> <td valign="top" colspan="2"> <h6 class="aligncenter">RECI&Eacute;N NACIDO</h6> </td> </tr>    
                <tr>
                    <td class="borderright"><label class="title2 block">SEXO</label></td>
                    <td><label class="title2 block">PESO AL NACER</label></td>
                </tr>
                <tr>
                    <td class="borderright">
                        <label  class="title3 top-5" for="rn_sexo_femenino">F</label>
                        <input class="black" type="radio" tabindex="715" name="var_0310" value="A" title="variable: <RN sexo>, valor:<femenino>, id:<0310_A>"
                               id="rn_sexo_femenino" <?=($ficha->getVar0310()=='A')?$checked:'' ?> >
                    </td>
                    <td>
                        <input type="text" class="number" maxlength="4" name="var_0311" id="rn_peso" title="variable: <Peso al nacer>, valor:<gramos>, id:<0311>"
                               value="<?=$ficha->getVar0311() ?>" tabindex="718" style="width:56px"  min="10" max="6000"> g
                    </td>
                </tr>
                <tr>
                    <td class="borderright">
                        <label  class="title3 top-5" for="rn_sexo_masculino">M</label>
                        <input class="black" type="radio" tabindex="716" name="var_0310" value="B" title="variable: <RN sexo>, valor:<masculino>, id:<0310_B>"
                               id="rn_sexo_masculino" <?=($ficha->getVar0310()=='B')?$checked:'' ?> >                        
                    </td>
                    <td>
                        <label  class="title3 top-5" for="rn_peso_men2500"><2500g</label>
                        <input class="yellow" type="radio" tabindex="719" name="var_0312" value="A" title="variable: <Peso al nacer <2500 o >=4000>, valor:<<2500>, id:<0312_A>"
                               id="rn_peso_men2500" <?=($ficha->getVar0312()=='A')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td class="borderright">
                        <span  class="title3" for="rn_sexo_nodefinido">no definido</span>
                        <input class="yellow" type="radio" tabindex="717" name="var_0310" value="C" title="variable: <RN sexo>, valor:<no definido>, id:<0310_C>"
                           id="rn_sexo_nodefinido" <?=($ficha->getVar0310()=='C')?$checked:'' ?> >
                    </td>
                    <td>
                        <label class="title3 top-5" for="rn_peso_may4000">&GreaterEqual;4000g</label>
                        <input class="yellow" type="radio" tabindex="720" name="var_0312" value="B" title="variable: <Peso al nacer <2500 o >=4000>, valor:<>=4000>, id:<0312_B>"
                               id="rn_peso_may4000" <?=($ficha->getVar0312()=='B')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2" id="rn_medidas">
            <table class="inside">
                <tr>
                    <td class="borderB1"><span class="title2 block">P. CEF&Aacute;LICO<br>cm</span>
                        <input type="text" class="number width3 comatemp" maxlength="3" value="<?=$ficha->getVar0313() ?>" title="variable: <Per&iacute;metro cef&aacute;lico>, valor:<mm>, id:<0313>"
                               name="var_0313" id="rn_perim_cefalico" tabindex="721" min="15" max="500" >
                    </td>
                </tr>
                <tr>
                    <td><span class="title2 block">LONGITUD<br>cm</span>
                        <input type="text" class="number width3 comatemp" maxlength="3" value="<?=$ficha->getVar0314() ?>" title="variable: <Longitud>, valor:<mm>, id:<0314>"
                               name="var_0314" id="rn_longitud" tabindex="722" min="15" max="700">
                    </td>
                </tr>
            </table>    
        </td>
        <td class="border2" id="rn_edad_gestacional">
            <table class="inside">
                <tr>
                    <td colspan="2"> <span class="title3 block">EDAD GESTACIONAL</span> </td> 
                </tr>
                <tr>
                    <td colspan="2"><span class="title3 block">semana - d&iacute;as</span>
                        <input type="text" class="number width2" name="var_0315" id="rn_edad_gestacional_semana" title="variable: <Edad gestacional RN>, valor:<semana>, id:<0315>"
                               value="<?=$ficha->getVar0315() ?>" maxlength="2" tabindex="723" min="1" max="44" />
                        <input class="number width1" name="var_0316" id="rn_edad_gestacional_dias" title="variable: <Edad gestacional RN - d&iacute;as>, valor:<d&iacute;as>, id:<0316>"
                               value="<?=$ficha->getVar0316() ?>" maxlength="1" tabindex="724" min="0" max="6" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label class="title3" style="top:-8px;" for="rn_edad_gestacional_estimada">estimada</label>
                        <input class="yellow" type="radio" tabindex="725" name="var_0319" value="X" title="variable: <Edad gestacional RN - estimada>, valor:<estimada>, id:<0319_X>"
                               id="rn_edad_gestacional_estimada" <?=($ficha->getVar0319()=='X')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3" for=rn_edad_gestacional_fum"">por FUM</label>
                        <input class="black" type="radio" tabindex="726" name="var_0317" value="X" title="variable: <Edad gestacional RN - FUM>, valor:<FUM>, id:<0317_X>"
                               id="rn_edad_gestacional_fum" <?=($ficha->getVar0317()=='X')?$checked:'' ?> >
                    </td>
                    <td>
                        <label class="title3" for="rn_edad_gestacional_eco">por ECO</label>
                        <input class="black" type="radio" tabindex="727" name="var_0318" value="X" title="variable: <Edad gestacional RN - ECO>, valor:<ECO>, id:<0318_X>"
                               id="rn_edad_gestacional_eco" <?=($ficha->getVar0318()=='X')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2" id="rn_peso_edad_gestacional">
            <table class="inside">
                <tr>
                    <td colspan="2"> <span class="title3 block">PESO E.G.</span> </td> 
                </tr>
                <tr>
                    <td><label class="title3 block" for="rn_peso_edad_gestacional_adecuado">adecuado</label>
                        <input class="black" type="radio" tabindex="728" name="var_0320" value="A" title="variable: <RN Peso para Edad Gestacional>, valor:<adecuado>, id:<0320_A>"
                               id="rn_peso_edad_gestacional_adecuado" <?=($ficha->getVar0320()=='A')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3 block" for="rn_peso_edad_gestacional_pequeno">peque&ntilde;o</label>
                        <input class="yellow" type="radio" tabindex="729" name="var_0320" value="B" title="variable: <RN Peso para Edad Gestacional>, valor:<peque&ntilde;o>, id:<0320_B>"
                               id="rn_peso_edad_gestacional_pequeno" <?=($ficha->getVar0320()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3 block" for="rn_peso_edad_gestacional_grande">grande</label>
                        <input class="yellow" type="radio" tabindex="730" name="var_0320" value="C" title="variable: <RN Peso para Edad Gestacional>, valor:<grande>, id:<0320_C>"
                               id="rn_peso_edad_gestacional_grande" <?=($ficha->getVar0320()=='C')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2" id="rn_apgar">
            <table class="inside">
                <tr>
                    <td colspan="2"> <span class="title3 block">APGAR</span> </td> 
                </tr>
                <tr>
                    <td><label class="title3 block">1&deg; minuto</label>
                        <input type="text" class="number width2" maxlength="2" value="<?=$ficha->getVar0321() ?>" title="variable: <Apgar 1er. Minuto>, valor:<1er. minuto>, id:<0321>"
                               name="var_0321" id="rn_apgar_minuto1" tabindex="731" min="0" max="10"  >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3 block">5&deg; minuto</label>
                        <input type="text" class="number width2" maxlength="2" value="<?=$ficha->getVar0322() ?>" name="var_0322" title="variable: <Apgar 5to. Minuto>, valor:<5to. minuto>, id:<0321>"
                               id="rn_apgar_minuto5" tabindex="732" min="0" max="10" >
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2" id="rn_reanimacion">
            <table class="inside">
                <tr>
                    <td> <span class="title2">REANIMA CI&Oacute;N</span> </td> 
                    <td> <span class="title3">no</span> </td> 
                    <td> <span class="title3">si</span> </td> 
                </tr>
                <tr>
                    <td><label class="title3">estimulaci&oacute;n</label></td>
                    <td><input class="black" type="radio" tabindex="733" name="var_0323" value="A" title="variable: <RN Reanimaci&oacute;n - Estimulaci&oacute;n>, valor:<no>, id:<0323_A>"
                               id="rn_reanimacion_estimulacion_no" <?=($ficha->getVar0323()=='A')?$checked:'' ?> >
                    </td>
                    <td><input class="yellow" type="radio" tabindex="734" name="var_0323" value="B" title="variable: <RN Reanimaci&oacute;n - Estimulaci&oacute;n>, valor:<si>, id:<0323_B>"
                               id="rn_reanimacion_estimulacion_si" <?=($ficha->getVar0323()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3">aspiraci&oacute;n</label></td>
                    <td><input class="black" type="radio" tabindex="735" name="var_0324" value="A" title="variable: <RN Reanimaci&oacute;n - Aspiraci&oacute;n>, valor:<no>, id:<0324_A>"
                               id="rn_reanimacion_aspiracion_no" <?=($ficha->getVar0324()=='A')?$checked:'' ?> >
                    </td>
                    <td><input class="yellow" type="radio" tabindex="736" name="var_0324" value="B" title="variable: <RN Reanimaci&oacute;n - Aspiraci&oacute;n>, valor:<si>, id:<0324_B>"
                               id="rn_reanimacion_aspiracion_si" <?=($ficha->getVar0324()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3">m&aacute;scara</label></td>
                    <td><input class="black" type="radio" tabindex="737" name="var_0325" value="A" title="variable: <RN Reanimaci&oacute;n - M&aacute;scara>, valor:<no>, id:<0325_A>"
                               id="rn_reanimacion_mascara_no" <?=($ficha->getVar0325()=='A')?$checked:'' ?> >
                    </td>
                    <td><input class="yellow" type="radio" tabindex="738" name="var_0325" value="B" title="variable: <RN Reanimaci&oacute;n - M&aacute;scara>, valor:<si>, id:<0325_B>"
                               id="rn_reanimacion_mascara_si" <?=($ficha->getVar0325()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3">ox&iacute;geno</label></td>
                    <td><input class="black" type="radio" tabindex="739" name="var_0326" value="A" title="variable: <RN Reanimaci&oacute;n - Ox&iacute;geno>, valor:<no>, id:<0326_A>"
                               id="rn_reanimacion_oxigeno_no" <?=($ficha->getVar0326()=='A')?$checked:'' ?> >
                    </td>
                    <td><input class="yellow" type="radio" tabindex="740" name="var_0326" value="B" title="variable: <RN Reanimaci&oacute;n - Ox&iacute;geno>, valor:<si>, id:<0326_B>"
                               id="rn_reanimacion_oxigeno_si" <?=($ficha->getVar0326()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3">masaje</label></td>
                    <td><input class="black" type="radio" tabindex="741" name="var_0327" value="A" title="variable: <RN Reanimaci&oacute;n - Masaje>, valor:<no>, id:<0327_A>"
                               id="rn_reanimacion_masaje_no" <?=($ficha->getVar0327()=='A')?$checked:'' ?> >
                    </td>
                    <td><input class="yellow" type="radio" tabindex="742" name="var_0327" value="B" title="variable: <RN Reanimaci&oacute;n - Masaje>, valor:<si>, id:<0327_B>"
                               id="rn_reanimacion_masaje_si" <?=($ficha->getVar0327()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3">tubo</label></td>
                    <td><input class="black" type="radio" tabindex="743" name="var_0328" value="A" title="variable: <RN Reanimaci&oacute;n - Tubo>, valor:<no>, id:<0328_A>"
                               id="rn_reanimacion_tubo_no" <?=($ficha->getVar0328()=='A')?$checked:'' ?> >
                    </td>
                    <td><input class="yellow" type="radio" tabindex="744" name="var_0328" value="B" title="variable: <RN Reanimaci&oacute;n - Tubo>, valor:<si>, id:<0328_B>"
                               id="rn_reanimacion_tubo_si" <?=($ficha->getVar0328()=='B')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2" id="rn_atendio">
            <table class="inside">
                <tr> <td colspan="2" class="borderright"><span class="title2">FALLECE EN LUGAR DE PARTO</span></td> 
                    <td colspan="3" class="fleft"><span class="title2" style="margin-left: 5px;">REFERIDO</span></td>
                </tr>
                <tr class="borderB2">
                    <td><label class="title2 top-5" for="rn_fallece_lugar_no">no</label> 
                        <input class="black" type="radio" tabindex="745" name="var_0329" value="A" title="variable: <Fallece lugar de parto>, valor:<no>, id:<0329_A>"
                               id="rn_fallece_lugar_no" <?=($ficha->getVar0329()=='A')?$checked:'' ?> >
                    </td>
                    <td  class="borderright"><label class="title2 top-5" for="rn_fallece_lugar_si">si</label> 
                        <input class="yellow" type="radio" tabindex="746" name="var_0329" value="B" title="variable: <Fallece lugar de parto>, valor:<si>, id:<0329_B>"
                               id="rn_fallece_lugar_si" <?=($ficha->getVar0329()=='B')?$checked:'' ?> >                        
                    </td>    
                    <td><input class="black" type="radio" tabindex="747" name="var_0330" value="A" title="variable: <Referido>, valor:<alojamiento conjunto>, id:<0330_A>"
                               id="rn_referido_alojconjunto" <?=($ficha->getVar0330()=='A')?$checked:'' ?> >
                        <label class="title2 block" for="rn_referido_alojconjunto">alojamiento conjunto</label> 
                    </td>
                    <td><input class="yellow" type="radio" tabindex="748" name="var_0330" value="B" title="variable: <Referido>, valor:<neonato>, id:<0330_B>"
                               id="rn_referido_neonatologia" <?=($ficha->getVar0330()=='B')?$checked:'' ?> >
                        <label class="title2 block" for="rn_referido_neonatologia">neonatolog&iacute;a</label> 
                    </td>
                    <td><input class="yellow" type="radio" tabindex="749" name="var_0330" value="C" title="variable: <Referido>, valor:<otro hospit.>, id:<0330_C>"
                               id="rn_referido_otrohospital" <?=($ficha->getVar0330()=='C')?$checked:'' ?> >
                        <label class="title2 block" for="rn_referido_otrohospital">otro hospital</label> 
                    </td>
                </tr>
                <tr>
                    <td colspan="7">
                        <table class="inside_atendio">
                            <tr class="borderB1">
                                <td><span class="title2 block" style="margin:5px;">ATENDIO</span></td> 
                                <td><span class="title3 block">m&eacute;dico</span></td> 
                                <td><span class="title3 block">obstetra</span></td> 
                                <td><span class="title3 block">enfermera</span></td> 
                                <td><span class="title3 block">auxiliar</span></td> 
                                <td><span class="title3 block">estudiante</span></td> 
                                <td><span class="title3 block">emp&iacute;rica</span></td> 
                                <td><span class="title3 block">otro</span></td> 
                                <td class="borderL1"><span class="title3 block">Nombre</span></td> 
                            </tr>
                            <tr class="borderB1">
                                <td> <span class="title2 block">PARTO</span></td> 
                                <td><input class="black" type="radio" tabindex="750" name="var_0331" value="A" title="variable: <Atendi&oacute; parto>, valor:<m&eacute;dico>, id:<0331_A>"
                                           id="rn_atendio_parto_medico" <?=($ficha->getVar0331()=='A')?$checked:'' ?> >
                                </td> 
                                <td><input class="black" type="radio" tabindex="751" name="var_0331" value="B" title="variable: <Atendi&oacute; parto>, valor:<obstetra>, id:<0331_B>"
                                           id="rn_atendio_parto_obstetra" <?=($ficha->getVar0331()=='B')?$checked:'' ?> >
                                </td> 
                                <td><input class="black" type="radio" tabindex="752" name="var_0331" value="C" title="variable: <Atendi&oacute; parto>, valor:<enfermera>, id:<0331_C>"
                                           id="rn_atendio_parto_enfermera" <?=($ficha->getVar0331()=='C')?$checked:'' ?> >
                                </td> 
                                <td><input class="yellow" type="radio" tabindex="753" name="var_0331" value="D" title="variable: <Atendi&oacute; parto>, valor:<auxiliar>, id:<0331_D>"
                                           id="rn_atendio_parto_auxiliar" <?=($ficha->getVar0331()=='D')?$checked:'' ?> >
                                </td> 
                                <td><input class="yellow" type="radio" tabindex="754" name="var_0331" value="E" title="variable: <Atendi&oacute; parto>, valor:<estudiante>, id:<0331_E>"
                                           id="rn_atendio_parto_estudiante" <?=($ficha->getVar0331()=='E')?$checked:'' ?> >
                                </td> 
                                <td><input class="yellow" type="radio" tabindex="755" name="var_0331" value="F" title="variable: <Atendi&oacute; parto>, valor:<emp&iacute;rica>, id:<0331_F>"
                                           id="rn_atendio_parto_empirica" <?=($ficha->getVar0331()=='F')?$checked:'' ?> >
                                </td> 
                                <td><input class="yellow" type="radio" tabindex="756" name="var_0331" value="G" title="variable: <Atendi&oacute; parto>, valor:<otro>, id:<0331_G>"
                                           id="rn_atendio_parto_otro" <?=($ficha->getVar0331()=='G')?$checked:'' ?> >
                                </td> 
                                <td  class="borderL1"> 
                                    <input type="text" style="width:100%" maxlength="50" value="<?=$ficha->getVar0332() ?>" name="var_0332" title="variable: <Atendi&oacute; parto - Nombre>, valor:<nombre>, id:<0332>"
                                           id="rn_atendio_parto_nombre" tabindex="757">
                                </td> 
                            </tr>
                            <tr>
                                <td> <span class="title2 block">NEONATO</span></td> 
                                <td> <input class="black" type="radio" tabindex="758" name="var_0333" value="A" title="variable: <Atendi&oacute; neonato>, valor:<m&eacute;dico>, id:<0333_A>"
                                            id="rn_atendio_neonato_medico" <?=($ficha->getVar0333()=='A')?$checked:'' ?> >
                                </td> 
                                <td> <input class="black" type="radio" tabindex="759" name="var_0333" value="B" title="variable: <Atendi&oacute; neonato>, valor:<obstetra>, id:<0333_B>"
                                            id="rn_atendio_neonato_obstetra" <?=($ficha->getVar0333()=='B')?$checked:'' ?> >
                                </td> 
                                <td> <input class="black" type="radio" tabindex="760" name="var_0333" value="C" title="variable: <Atendi&oacute; neonato>, valor:<enfermera>, id:<0333_C>"
                                            id="rn_atendio_neonato_enfermera" <?=($ficha->getVar0333()=='C')?$checked:'' ?> >
                                </td> 
                                <td> <input class="yellow" type="radio" tabindex="761" name="var_0333" value="D" title="variable: <Atendi&oacute; neonato>, valor:<auxiliar>, id:<0333_D>"
                                            id="rn_atendio_neonato_auxiliar" <?=($ficha->getVar0333()=='D')?$checked:'' ?> >
                                </td> 
                                <td> <input class="yellow" type="radio" tabindex="762" name="var_0333" value="E" title="variable: <Atendi&oacute; neonato>, valor:<estudiante>, id:<0333_E>"
                                            id="rn_atendio_neonato_estudiante" <?=($ficha->getVar0333()=='E')?$checked:'' ?> >
                                </td> 
                                <td> <input class="yellow" type="radio" tabindex="763" name="var_0333" value="F" title="variable: <Atendi&oacute; neonato>, valor:<emp&iacute;rica>, id:<0333_F>"
                                            id="rn_atendio_neonato_empirica" <?=($ficha->getVar0333()=='F')?$checked:'' ?> >
                                </td> 
                                <td> <input class="yellow" type="radio" tabindex="764" name="var_0333" value="G" title="variable: <Atendi&oacute; neonato>, valor:<otro>, id:<0333_G>"
                                            id="rn_atendio_neonato_otro" <?=($ficha->getVar0333()=='G')?$checked:'' ?> >
                                </td> 
                                <td  class="borderL1"> 
                                    <input type="text" style="width:100%" maxlength="50" value="<?=$ficha->getVar0334() ?>" name="var_0334" title="variable: <Atendi&oacute; neonato - Nombre>, valor:<nombre>, id:<0334>"
                                            id="rn_atendio_neonato_nombre" tabindex="765"> 
                                </td> 
                            </tr>
                        </table>  
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>