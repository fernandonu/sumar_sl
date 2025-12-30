<table id="section6">
    <tr>
<!-- PARTO ABORTO - 500/505  -->
        <td class="border2" id="parto_aborto">
            <table class="inside">
                <tr>
                    <td valign="top" colspan="2" class="ck_titulo">
                        <h6><label style="margin-left:10px" for="parto">PARTO</label> 
                            <input class="black" type="radio" tabindex="500" name="var_0182" value="A" title="variable: <Parto / Aborto>, valor:<parto>, id:<0182_A>"
                                   id="parto" <?=($ficha->getVar0182()=='A')?$checked:'' ?> >    
                            <label for="aborto">ABORTO</label>
                            <input class="yellow" type="radio" tabindex="501" name="var_0182" value="B" title="variable: <Parto / Aborto>, valor:<aborto>, id:<0182_B>"
                                   id="aborto" <?=($ficha->getVar0182()=='B')?$checked:'' ?> >    
                        </h6> 
                    </td>
                </tr>    
                <tr>
                    <td class="borderright" style="border-bottom: 2px solid black">
                        <label style="margin: 8px 5px;" class="title3 block">FECHA DE INGRESO</label>
                        <input type="text" class="datepicker" value="<?=FechaView($ficha->getVar0183()) ?>" name="var_0183" title="variable: <Fecha de Ingreso>, valor:<fecha>, id:<0183>"
                               id="fecha_ingreso" tabindex="502"/>
                    </td>
                    <td>
                        <span class="title2 block">CONSULTAS PRE-NATALES</span>
                        <span class="title3 block">total</span>
                    </td>
                </tr>
                <tr>
                    <td  class="borderright">
                        <span  class="title1">CARN&Eacute;</span>
                        <input class="yellow" type="radio" tabindex="503" name="var_0184" value="A" title="variable: <Carn&eacute;>, valor:<no>, id:<0184_A>"
                               id="carnet_no" <?=($ficha->getVar0184()=='A')?$checked:'' ?> >
                        <label class="title3" for="carnet_no">no</label>
                        <input class="black" type="radio" tabindex="504" name="var_0184" value="B" title="variable: <Carn&eacute;>, valor:<si>, id:<0184_B>"
                               id="carnet_si" <?=($ficha->getVar0184()=='B')?$checked:'' ?> >
                        <label class="title3" for="carnet_si">si</label>
                    </td>
                    <td> <input type="text" class="number width2 required" name="var_0185" id="consultas_prenatales_total" maxlength="2" title="variable: <Consultas prenatales>, valor:<n&uacute;mero>, id:<0185>"
                                value="<?=$ficha->getVar0185() ?>" tabindex="505" min="0" max="20" /> </td>
                </tr>
            </table>
        </td>
<!-- HOSPITALIZACION - 506/508  -->
        <td class="border2" id="hospitalizacion">
            <table class="inside">
                <tr>
                    <td><span class="title3 block">HOSPITALIZACI&Oacute;N</span> 
                        <span class="title3 block">EN</span>   
                        <span class="title3 block">EMBARAZO</span></td> 
                </tr>
                <tr>
                    <td><label class="title3 top-5" for="hospitalizacion_no">no</label>
                        <input class="black" type="radio" tabindex="506" name="var_0186" value="A" title="variable: <Hospitalizaci&oacute;n>, valor:<no>, id:<0186_A>"
                               id="hospitalizacion_no" <?=($ficha->getVar0186()=='A')?$checked:'' ?> >
                        <label class="title3 top-5" for="hospitalizacion_si">si</label>
                        <input class="yellow" type="radio" tabindex="507" name="var_0186" value="B" title="variable: <Hospitalizaci&oacute;n>, valor:<si>, id:<0186_B>"
                               id="hospitalizacion_si" <?=($ficha->getVar0186()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><span class="title3 block">d&iacute;as</span>
                        <input type="text" class="number width2 alerta" id="hospitalizacion_dias" name="var_0187" title="variable: <Hospitalizaci&oacute;n - d&iacute;as>, valor:<d&iacute;as>, id:<0187>"
                               value="<?=$ficha->getVar0187() ?>" maxlength="2" tabindex="508"/>
                    </td>
                </tr>
            </table>
        </td>
<!-- COTICOIDES - 509/513  -->        
        <td class="border2" id="corticoides">
            <table class="inside">
                <tr>
                    <td colspan="3"><span class="title3 block">CORTICOIDES ANTENATALES</span></td> 
                </tr>
                <tr>
                    <td> <label class="title3" for="coticoides_completo">completo</label> </td>
                    <td> 
                        <input class="black" type="radio" tabindex="509" name="var_0188" value="A" title="variable: <Corticoides>, valor:<completo>, id:<0188_A>"
                               id="coticoides_completo" <?=($ficha->getVar0188()=='A')?$checked:'' ?> > 
                    </td>
                    <td rowspan="2" class="bottom"> 
                        <input type="text" class="number width2" name="var_0189" id="corticoides_semana_inicio" title="variable: <Corticoides>, valor:<semana de inicio>, id:<0189>"
                               value="<?=$ficha->getVar0189() ?>" maxlength="2" tabindex="513" min="1" max="44"/>
                    </td>
                </tr>
                <tr>
                    <td> <label class="title3" for="coticoides_incompleto">incompl.</label> </td>
                    <td> 
                        <input class="yellow" type="radio" tabindex="510" name="var_0188" value="B" title="variable: <Corticoides>, valor:<incompleto>, id:<0188_B>"
                               id="coticoides_incompleto" <?=($ficha->getVar0188()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td> <label class="title3" for="coticoides_ninguna">ninguna</label>
                    <td> 
                        <input class="yellow" type="radio" tabindex="511" name="var_0188" value="C" title="variable: <Corticoides>, valor:<ninguna>, id:<0188_C>"
                               id="coticoides_ninguna" <?=($ficha->getVar0188()=='C')?$checked:'' ?> > 
                    </td>
                    <td class="bottom"> <label class="title3" for="corticoides_semana_inicio">semana</label> </td>
                </tr>
                <tr>
                    <td> <label class="title3" for="coticoides_nc">n/c</label>
                    <td> 
                        <input class="black" type="radio" tabindex="512" name="var_0188" value="D" title="variable: <Corticoides>, valor:<n/c>, id:<0188_D>"
                               id="coticoides_nc" <?=($ficha->getVar0188()=='D')?$checked:'' ?> >
                    </td>
                    <td class="top"> <label class="title3" for="corticoides_semana_inicio">inicio</label> </td>
                </tr>
            </table>
        </td>
<!-- INICIO DEL PARTO - 514/516  -->        
        <td class="border2" id="inicio_parto">
            <table class="inside">
                <tr>
                    <td><span class="title2 block">INICIO</span></td> 
                </tr>
                <tr>
                    <td> <label class="title3 block" for="inicio_parto_espontaneo">espont&aacute;neo</label> 
                        <input class="black" type="radio" tabindex="514" name="var_0190" value="A" title="variable: <Inicio parto>, valor:<espont&aacute;neo>, id:<0190_A>"
                               id="inicio_parto_espontaneo" <?=($ficha->getVar0190()=='A')?$checked:'' ?> >
                         <label class="title3 block" for="inicio_parto_inducido">inducido</label> 
                         <input class="yellow" type="radio" tabindex="515" name="var_0190" value="B" title="variable: <Inicio parto>, valor:<inducido>, id:<0190_B>"
                                id="inicio_parto_inducido" <?=($ficha->getVar0190()=='B')?$checked:'' ?> >
                         <label class="title3 block" for="inicio_parto_cesarea">cesar.elect.</label> 
                         <input class="yellow" type="radio" tabindex="516" name="var_0190" value="C" title="variable: <Inicio parto>, valor:<ces&aacute;rea electiva>, id:<0190_C>"
                                id="inicio_parto_cesarea" <?=($ficha->getVar0190()=='C')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
<!-- ROTURA DE MEMBRANAS - 517/525  -->        
        <td class="border2" id="ruptura_membranas">
            <table class="inside">
                <tr>
                    <td colspan="4"><span class="title2 block">ROTURA DE MEMBRANAS ANTEPARTO</span></td> 
                </tr>
                <tr>
                    <td rowspan="2"> <label style="margin: 0 10px;" class="title3" for="ruptura_membrana_no">no</label> 
                        <input class="black" type="radio" tabindex="517" name="var_0191" value="A" title="variable: <Ruptura de membrana>, valor:<no>, id:<0191_A>"
                               id="ruptura_membrana_no" <?=($ficha->getVar0191()=='A')?$checked:'' ?> >
                    </td>
                    <td rowspan="2"> <label class="title3">d&iacute;a &nbsp; - &nbsp; mes &nbsp; - &nbsp; a&ntilde;o</label>
                        <input type="text" class="datepicker" value="<?=FechaView($ficha->getVar0192()) ?>" name="var_0192" title="variable: <Fecha ruptura de membranas>, valor:<fecha>, id:<0192>"
                               id="ruptura_membrana_fecha" tabindex="519"/>
                    </td>
                    <td> <label class="title3" for="ruptura_membrana_menor37sem"><37 sem.</label> 
                    </td>
                    <td> 
                        <input class="yellow" type="checkbox" tabindex="522" name="var_0194" value="X" title="variable: <Rupt. membranas < 37 sem.>, valor:<si>, id:<0194_X>"
                               id="ruptura_membrana_menor37sem" <?=($ficha->getVar0194()=='X')?$checked:'' ?> >
                    </td> 
                </tr>
                <tr>
                    <td><label class="title3" for="ruptura_membrana_mayor18hs">&GreaterEqual; 18 hs</label> 
                    </td>
                    <td> <input class="yellow" type="checkbox" tabindex="523" name="var_0195" value="X" title="variable: <Rupt. membranas >= 18 hs.>, valor:<si>, id:<0195_X>"
                                id="ruptura_membrana_mayor18hs" <?=($ficha->getVar0195()=='X')?$checked:'' ?> >
                    </td>                    
                </tr>
                <tr>
                    <td rowspan="2"> <label style="margin: 0 10px;" class="title3" for="ruptura_membrana_si">si</label> 
                         <input class="yellow" type="radio" tabindex="518" name="var_0191" value="B" title="variable: <Ruptura de membrana>, valor:<si>, id:<0191_B>"
                                id="ruptura_membrana_si" <?=($ficha->getVar0191()=='B')?$checked:'' ?> >
                    </td>
                    <td rowspan="2"> <label class="title3 block">hora &nbsp; - &nbsp; min</label>
                         <input type="text" value="<?=$ficha->getVar0193H() ?>" min="00" max="23" name="var_0193H" title="variable: <Hora rupt.membranas>, valor:<hora>, id:<0193_A>"
                                id="ruptura_membrana_hora" tabindex="520" class="number width2" />
                         <input type="text" value="<?=$ficha->getVar0193M() ?>" min="00" max="59" name="var_0193M" title="variable: <Hora rupt.membranas>, valor:<minutos>, id:<0193_B>"
                                id="ruptura_membrana_minuto" tabindex="521" class="number width2"/>
                    </td>
                    <td> <label class="title3" for="ruptura_membrana_temperatura_mayor38">Temp. &GreaterEqual; 38&deg;</label> 
                    </td>
                    <td> <input class="yellow" type="checkbox" tabindex="524" name="var_0196" value="X" disabled="disabled" title="variable: <Rupt. membranas - temperatura >= 38>, valor:<si>, id:<0196_X>"
                                id="ruptura_membrana_temperatura_mayor38" <?=($ficha->getVar0196()=='X')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="text" class="number width3 comatemp" id="ruptura_membrana_temperatura" name="var_0197" title="variable: <Rupt. membranas - temperatura>, valor:<temperatura grados>, id:<0197>"
                               value="<?=$ficha->getVar0197() ?>" maxlength="4" tabindex="525" min="34" max="430"/>
                    </td>
                </tr>
            </table>
        </td>
<!-- EDAD GESTACIONAL AL PARTO - 526/529  -->        
        <td class="border2" id="edad_gestacional_parto">
            <table class="inside">
                <tr>
                    <td colspan="2"><span class="title3 block">EDAD GEST.</span> 
                        <span class="title3 block">al parto</span>   
                    </td> 
                </tr>
                <tr>
                    <td colspan="2"><span class="title3">semana - d&iacute;as</span>
                        <input type="text" class="number width2" name="var_0198" id="edad_gestacional_parto_semana" title="variable: <Edad gestacional al parto>, valor:<semanas>, id:<0198>"
                               value="<?=$ficha->getVar0198() ?>" maxlength="2" tabindex="526" min="1" max="44"/>
                        <input class="number width1" name="var_0199" id="edad_gestacional_parto_dias" title="variable: <Edad gestacional - d&iacute;as>, valor:<d&iacute;as>, id:<0199>"
                               value="<?=$ficha->getVar0199() ?>" maxlength="1" tabindex="527" min="0" max="6" />
                    </td>
                </tr>
                <tr>
                    <td><label class="title3" for="edad_gestacional_parto_fum">por FUM</label>
                        <input class="black" type="radio" tabindex="528" name="var_0200"  value="X" id="edad_gestacional_parto_fum" title="variable: <Edad gestacional al parto - por FUM>, valor:<por FUM>, id:<0200_X>"
                                <?=($ficha->getVar0200()=='X')?$checked:'' ?> >
                    </td>
                    <td>
                        <label class="title3" for="edad_gestacional_parto_eco">por ECO</label>
                        <input class="black" type="radio" tabindex="529" name="var_0201"  value="X" id="edad_gestacional_parto_eco" title="variable: <Edad gestacional al parto - por ECO>, valor:<por ECO>, id:<0201_X>"
                                 <?=($ficha->getVar0201()=='X')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
<!-- PRESENTACION SITUACION - 530 /532  -->          
        <td class="border2" id="presentacion">
            <table class="inside">
                <tr>
                    <td colspan="2"><span class="title3 block">PRESENTACI&Oacute;N SITUACI&Oacute;N</span> </td> 
                </tr>
                <tr>
                    <td> <label class="title3" for="presentacion_cefalica">cef&aacute;lica</label> </td>
                    <td> 
                        <input class="black" type="radio" tabindex="530" name="var_0202" value="A" title="variable: <Presentaci&oacute;n situaci&oacute;n>, valor:<cef&aacute;lica>, id:<0202_A>"
                               id="presentacion_cefalica" <?=($ficha->getVar0202()=='A')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td> <label class="title3" for="presentacion_pelviana">pelviana</label> </td>
                    <td> 
                        <input class="yellow" type="radio" tabindex="531" name="var_0202" value="B" title="variable: <Presentaci&oacute;n situaci&oacute;n>, valor:<pelviana>, id:<0202_B>"
                               id="presentacion_pelviana" <?=($ficha->getVar0202()=='B')?$checked:'' ?> > 
                    </td>
                </tr>
                <tr>
                    <td> <label class="title3" for="presentacion_transversa">transversa</label> </td>
                    <td> 
                        <input class="yellow" type="radio" tabindex="532" name="var_0202" value="C" title="variable: <Presentaci&oacute;n situaci&oacute;n>, valor:<transversa>, id:<0202_C>"
                               id="presentacion_transversa" <?=($ficha->getVar0202()=='C')?$checked:'' ?> > 
                    </td>
                </tr>
            </table>
        </td>
<!-- TAMAÑO FETAL ACORDE - 533/534  -->        
        <td class="border2" id="tamanio_fetal_acorde">
            <table class="inside">
                <tr>
                    <td><span class="title2 block">TAMA&Ntilde;O FETAL ACORDE</span></td> 
                </tr>
                <tr>
                    <td> <label class="title3 block" for="tamanio_fetal_acorde_no">no</label> 
                        <input class="yellow" type="radio" tabindex="533" name="var_0203" value="A" title="variable: <Tama&ntilde;o fetal acorde>, valor:<no>, id:<0203_A>"
                               id="tamanio_fetal_acorde_no" <?=($ficha->getVar0203()=='A')?$checked:'' ?> >
                         <label class="title3 block" for="tamanio_fetal_acorde_si">si</label> 
                         <input class="black" type="radio" tabindex="534" name="var_0203" value="B" title="variable: <Tama&ntilde;o fetal acorde>, valor:<si>, id:<0203_B>"
                                id="tamanio_fetal_acorde_si" <?=($ficha->getVar0203()=='B')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td> 
<!-- ACOMPANANTE - 535/542  -->        
        <td class="border2" id="acompaniante">
            <table class="inside">
                <tr>
                    <td colspan="3"><span class="title2 block">ACOMPA&Ntilde;ANTE</span></td> 
                </tr>
                <tr>
                    <td>&nbsp;</td> 
                    <td> <span class="title2 block">TDP</span></td> 
                    <td> <span class="title2 block">P</span></td> 
                </tr>
                <tr>
                    <td> <span class="title2 block">pareja</span></td> 
                    <td> 
                        <input class="black" type="radio" tabindex="535" name="var_0204" id="acompaniante_tdp_pareja" title="variable: <Acompa&ntilde;ante TDP>, valor:<pareja>, id:<0204_A>"
                               value="A" <?=($ficha->getVar0204()=='A')?$checked:'' ?> >
                    </td>
                    <td> 
                        <input class="black" type="radio" tabindex="539" name="var_0205" id="acompaniante_p_pareja" title="variable: <Acompa&ntilde;ante Parto>, valor:<pareja>, id:<0205_A>"
                               value="A" <?=($ficha->getVar0205()=='A')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td> <span class="title2 block">familiar</span></td> 
                    <td> 
                        <input class="black" type="radio" tabindex="536" name="var_0204" id="acompaniante_tdp_familiar" title="variable: <Acompa&ntilde;ante TDP>, valor:<familiar>, id:<0204_B>"
                               value="B" <?=($ficha->getVar0204()=='B')?$checked:'' ?> >
                    </td>
                    <td> 
                        <input class="black" type="radio" tabindex="540" name="var_0205" id="acompaniante_p_familiar" title="variable: <Acompa&ntilde;ante Parto>, valor:<familiar>, id:<0205_B>"
                               value="B" <?=($ficha->getVar0205()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td> <span class="title2 block">otro</span></td> 
                    <td> 
                        <input class="black" type="radio" tabindex="537" name="var_0204" id="acompaniante_tdp_otro" title="variable: <Acompa&ntilde;ante TDP>, valor:<otro>, id:<0204_C>"
                               value="C" <?=($ficha->getVar0204()=='C')?$checked:'' ?> >
                    </td>
                    <td> 
                        <input class="black" type="radio" tabindex="541" name="var_0205" id="acompaniante_p_otro" title="variable: <Acompa&ntilde;ante Parto>, valor:<otro>, id:<0205_C>"
                               value="C" <?=($ficha->getVar0205()=='C')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td> <span class="title2 block">ninguno</span></td> 
                    <td> 
                        <input class="yellow" type="radio" tabindex="538" name="var_0204" id="acompaniante_tdp_ninguno" title="variable: <Acompa&ntilde;ante TDP>, valor:<ninguno>, id:<0204_D>"
                               value="D" <?=($ficha->getVar0204()=='D')?$checked:'' ?> >
                    </td>
                    <td> 
                        <input class="yellow" type="radio" tabindex="542" name="var_0205" id="acompaniante_p_ninguno" title="variable: <Acompa&ntilde;ante Parto>, valor:<ninguno>, id:<0205_D>"
                               value="D" <?=($ficha->getVar0205()=='D')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td> 
    </tr>
</table>    