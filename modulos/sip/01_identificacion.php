<table id="section1">
    <tr>
<!-- IDENTIFICACION -->
        <td class="border2" id="datos">
            <table class="inside">
                <tr>
                    <td valign="top" colspan="2"> <h6>&nbsp; HISTORIA CLINICA PERINATAL CLAP/SMR - OPS/OMS</h6> </td>
                </tr>    
                <tr>
                    <td>
                        <span class="title1">NOMBRE</span> 
                        <input name="var_0001" maxlength="20" value="<?=$ficha->getVar0001()?>" id="nombre" readonly="readonly" title="variable: <Nombre>, valor:<texto>, id:<0001>"/>                        
                    </td>
                    <td>
                        <span class="title1">APELLIDO</span>
                        <input name="var_0002" maxlength="20" value="<?=$ficha->getVar0002()?>" id="apellido" readonly="readonly" title="variable: <Apellido>, valor:<texto>, id:<0002>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="title1">DOMICILIO</span> 
                        <input name="var_0003" maxlength="50" value="<?=$ficha->getVar0003()?>" id="domicilio" readonly="readonly" title="variable: <Domicilio>, valor:<texto>, id:<0003>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="title1">LOCALIDAD</span> 
                        <input name="var_0004" maxlength="30" value="<?=$ficha->getVar0004()?>" id="localidad" readonly="readonly" title="variable: <Localidad>, valor:<texto>, id:<0004>"/>
                    </td>
                    <td>
                        <span class="title1">TELEFONO</span>
                        <input name="var_0005" maxlength="20" value="<?=$ficha->getVar0005()?>" id="telefono" readonly="readonly" title="variable: <Tel&eacute;fono>, valor:<n&uacute;mero>, id:<0003>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                      <span class="title1">SCORE RIESGO</span>
                      <input name="score_riesgo_tmp" value="<?php echo $score_riesgo_txt; ?>" readonly="readonly" style="<?php echo $score_riesgo_style; ?>"/>
                    </td>
                    <td>
                      <span class="title1">ACCI&Oacute;N A SEGUIR</span>
                      <input name="score_riesgo_tmp" value="<?php echo $score_riesgo_accion; ?>" readonly="readonly" style="<?php echo $score_riesgo_style; ?>"/>
                    </td>
                </tr>
            </table> 
        </td>
        <!-- NACIMIENTO - EDAD 1/4 -->                
        <td class="border2" id="nacimiento">
            <table class="inside">
                <tr>
                    <td colspan="3"><br/><br/></td>
                </tr>
                <tr>
                    <td class="titulo" colspan="3"> <span class="title1">FECHA DE NACIMIENTO</span> </td>
                </tr>
                <tr>
                    <td colspan="3" class="lblfechanac">
                        <span class="title1">d&iacute;a - mes - a&ntilde;o</span>
                    </td>
                </tr>
                <tr class="fechanac">
                    <td class="aligncenter" colspan="3">
                        <input id="fecha_nacimiento_madre" name="var_0006" value="<?=FechaView($ficha->getVar0006()) ?>" class="fecha" tabindex="3" readonly="readonly"  
                               title="variable: <Fecha de nacimiento madre>, valor:<fecha>, id:<0006>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                        <span class="title1 fleft lbledad">EDAD (a&ntilde;os)</span>
                    </td>
                </tr>
                <tr class="edad">
                    <td> 
                        <input type="text" class="number required" maxlength="2" id="edad_materna" name="var_0009" value="<?=$ficha->getVar0009() ?>" tabindex="4"  readonly="readonly" title="variable: <Edad materna>, valor:<a&ntilde;>, id:<0009>"/>
                    </td>
                    <td>
                        <input type="checkbox" class="yellow" id="edad_materna_rango" name="var_0010" value="X" <?=($ficha->getVar0010() == 'X') ? $checked : '' ?> tabindex="5" disabled="disabled" title="variable: <Edad materna <15 o >35>, valor:<si>, id:<0010_X>">
                    </td>
                    <td> <label for="edad_rango" class="title2">< de 15 <br>> de 35</label></td>
                </tr>
                <tr>
                    <td colspan="3"><br/><br/></td>
                </tr>
            </table>
        </td>
        <!-- ETNIA 6/10 -->                
        <td class="border2" id="etnia">
            <table class="inside">
                <tr> <td class="titulo" colspan="2"><span class="title1">ETNIA</span></td> </tr>
                <tr>
                    <td class="radio">
                        <input class="black" type="radio" tabindex="6" name="var_0011" value="A" id="etnia_blanca" 
                            <?=($ficha->getVar0011()=='A')?$checked:'' ?> title="variable: <Etnia>, valor:<blanca>, id:<0011_A>">
                    </td>
                    <td><label class="title2" for="etnia_blanca">Blanca</label></td>
                </tr>
                <tr>
                    <td class="radio">
                        <input class="black" type="radio" tabindex="7" name="var_0011" value="B" id="etnia_indigena" 
                            <?=($ficha->getVar0011()=='B')?$checked:'' ?> title="variable: <Etnia>, valor:<indigena>, id:<0011_B>" >
                    </td>
                    <td><label class="title2" for="etnia_indigena">Indigena</label></td>
                </tr>    
                <tr>
                    <td class="radio">
                        <input class="black" type="radio" tabindex="8" name="var_0011" value="C" id="etnia_mestiza" 
                            <?=($ficha->getVar0011()=='C')?$checked:'' ?> title="variable: <Etnia>, valor:<mestiza>, id:<0011_C>">
                    </td>
                    <td><label class="title2" for="etnia_mestiza">Mestiza</label></td>
                </tr>
                <tr>
                    <td class="radio">
                        <input class="black" type="radio" tabindex="9" name="var_0011" value="D" id="etnia_negra" 
                            <?=($ficha->getVar0011()=='D')?$checked:'' ?> title="variable: <Etnia>, valor:<negra>, id:<0011_D>">
                    </td>
                    <td><label class="title2" for="etnia_negra">Negra</label></td>
                </tr>
                <tr>
                    <td class="radio">
                        <input class="black" type="radio" tabindex="10" name="var_0011" value="E" id="etnia_otra" 
                            <?=($ficha->getVar0011()=='E')?$checked:'' ?> title="variable: <Etnia>, valor:<otra>, id:<0011_E>">
                    </td>
                    <td><label class="title2" for="etnia_otra">Otra</label></td>
                </tr>
            </table>                    

        </td>
        <!-- ALFABETA 11/12 -->                
        <td class="border2" id="alfabeta">
            <table class="inside">
                <tr> <td class="titulo"><span class="title1">ALFABETA</span></td> </tr>
                <tr>
                    <td>
                        <label class="title2" for="alfabeta_no">No</label>
                        <input class="yellow" type="radio" tabindex="11" name="var_0012" value="A" id="alfabeta_no"  
                               <?=($ficha->getVar0012()=='A')?$checked:'' ?> title="variable: <Alfabeta>, valor:<no>, id:<0012_A>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="title2" for="alfabeta_si">Si</label>
                        <input class="black" type="radio" tabindex="12" name="var_0012" value="B" id="alfabeta_si" 
                               <?=($ficha->getVar0012()=='B')?$checked:'' ?> title="variable: <Alfabeta>, valor:<si>, id:<0012_B>">                                
                    </td>
                </tr>
            </table>
        </td>
        <!-- ESTUDIOS 13/17 -->                
        <td class="border2" id="estudios">
            <table class="inside">
                <tr> <td class="titulo" colspan="2"><span class="title1">ESTUDIOS</span></td> </tr>
                <tr>
                    <td class="radio aligncenter">
                        <input class="yellow" tabindex="13" type="radio" name="var_0013" value="A" id="estudio_ninguno" <?=($ficha->getVar0013()=='A')?$checked:'' ?> title="variable: <Estudios>, valor:<ninguno>, id:<0013_A>">
                        <label class="title2" for="estudio_ninguno">Ninguno</label>
                        <input class="black" tabindex="14" type="radio" name="var_0013" value="C" id="estudio_secundario" <?=($ficha->getVar0013()=='C')?$checked:'' ?> title="variable: <Estudios>, valor:<secundario>, id:<0013_C>">
                        <label class="title2" for="estudio_secundario">Secund.</label>
                    </td>
                    <td class="radio aligncenter">
                        <input class="black" tabindex="15" type="radio" name="var_0013" value="B" id="estudio_primaria" <?=($ficha->getVar0013()=='B')?$checked:'' ?> title="variable: <Estudios>, valor:<primaria>, id:<0013_B>">
                        <label class="title2" for="estudio_primaria">Primaria</label>
                        <input class="black" tabindex="16" type="radio" name="var_0013" value="D" id="estudio_universitario" <?=($ficha->getVar0013()=='D')?$checked:'' ?> title="variable: <Estudios>, valor:<universidad>, id:<0013_D>">
                        <label class="title2" for="estudio_universitario">Univers.</label>
                    </td>
                </tr>    
                <tr class="mayor_nivel">
                    <td class="aligncenter"><span class="title2">A&ntilde;os en mayor nivel</span></td>
                    <td class="aligncenter"><input tabindex="17" maxlength="1" class="number width1" name="var_0014" value="<?=$ficha->getVar0014() ?>" id="anios_mayor_nivel" title="variable: <A&ntilde;os estudios mayor nivel>, valor:<a&ntilde;os en el mayor nivel>, id:<0014>"/></td>
                </tr>    
            </table>
        </td>
        <!-- ESTADO CIVIL 18/23 -->                
        <td class="border2" id="estado_civil">
            <table class="inside">
                <tr> <td class="titulo" colspan="2"><span class="title1">ESTADO CIVIL</span></td> </tr>
                <tr>
                    <td class="radio">
                        <input class="black" type="radio" tabindex="18" name="var_0015" value="A" id="estado_civil_casada"  
                            <?=($ficha->getVar0015()=='A')?$checked:'' ?> title="variable: <Estado civil>, valor:<casada>, id:<0015_A>">
                    </td>
                    <td><label class="title2" for="estado_civil_casada">Casada</label></td>
                </tr>
                <tr>
                    <td class="radio">
                        <input class="black" type="radio" tabindex="19" name="var_0015" value="B" id="estado_civil_unionestable" 
                            <?=($ficha->getVar0015()=='B')?$checked:'' ?> title="variable: <Estado civil>, valor:<uni&oacute;n estable>, id:<0015_B>">
                    </td>
                    <td><label class="title2" for="estado_civil_unionestable">Uni&oacute;n Estable</label></td>
                </tr>    
                <tr>
                    <td class="radio">
                        <input class="yellow" type="radio" tabindex="20" name="var_0015" value="C" id="estado_civil_soltera"  
                            <?=($ficha->getVar0015()=='C')?$checked:'' ?> title="variable: <Estado civil>, valor:<soltera>, id:<0015_C>">
                    </td>
                    <td><label class="title2" for="estado_civil_soltera">Soltera</label></td>
                </tr>
                <tr>
                    <td class="radio">
                        <input class="yellow" type="radio" tabindex="21" name="var_0015" value="D" id="estado_civil_otro"  
                            <?=($ficha->getVar0015()=='D')?$checked:'' ?> title="variable: <Estado civil>, valor:<otro>, id:<0015_D>">
                    </td>
                    <td><label class="title2" for="estado_civil_otro">Otro</label></td>
                </tr>
                <tr>
                    <td colspan="2" class="lblvivesola">
                        <hr class="dashed">
                        <span class="title1 aligncenter">Vive sola</span>
                    </td>
                </tr>
                <tr class="vivesola">
                    <td>
                        <label for="vive_sola_no" class="title2">No</label>
                        <input class="black" type="radio" tabindex="22" name="var_0016" value="A" id="vive_sola_no" 
                            <?=($ficha->getVar0016()=='A')?$checked:'' ?> title="variable: <Vive sola>, valor:<no>, id:<0016_A>">
                    </td>
                    <td>
                        <label for="vive_sola_no" class="title2">Si</label>
                        <input class="yellow" type="radio" tabindex="23" name="var_0016" value="B" id="vive_sola_no" 
                            <?=($ficha->getVar0016()=='B')?$checked:'' ?> title="variable: <Vive sola>, valor:<si>, id:<0016_B>">
                    </td>
                </tr>
            </table>
        </td>
        <!-- LUGAR ATENCION Y DNI 24/26 -->                
        <td class="border2" id="lugar_dni">
            <table class="inside">
                <tr>
                    <td style="position:relative">
                        <span class="title1 fleft" >Lugar del control prenatal</span>
                        <button type="button" class="efectorselectBtn" ><img src="<?php echo "../../imagenes/lupa.gif" ?>" ></button>
                        <input readonly="readonly" type="text" tabindex="24" name="var_0017" id="lugar_control_prenatal" value="<?=$ficha->getVar0017() ?>" maxlength="6" class="efectorselect <?=($permisoHospital)?'all':'' ?>" title="variable: <Lugar control prenatal>, valor:<c&oacute;digo>, id:<0017>"/>
                </tr>
                <tr>
                    <td style="position:relative">
                        <span class="title1 fleft" >Lugar del parto/aborto</span>
                        <button type="button" class="efectorselectBtn" ><img src="<?php echo "../../imagenes/lupa.gif" ?>" ></button>
                        <input readonly="readonly" type="text" tabindex="25" name="var_0018" id="lugar_parto_aborto" value="<?=$ficha->getVar0018() ?>" maxlength="6" class="efectorselect all" title="variable: <Lugar parto>, valor:<c&oacute;digo>, id:<0018>"/> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="title1 fleft" >Nro Identidad</span>
                        <input type="text" tabindex="26" name="var_0019" maxlength="20" value="<?=$ficha->getVar0019() ?>" class="number required" readonly="readonly" id="nro_identidad" title="variable: <N&uacute;mero de identidad>, valor:<n&uacute;mero>, id:<0019>"/>
                    </td>
                </tr>
            </table>
        </td>                
    </tr>
</table>