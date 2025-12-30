<table id="section8">
    <tr>
<!-- NACIMIENTO - 662/679  -->
        <td class="border2 top" id="nacimiento_a">
            <table class="inside">
                <tr>
                    <td colspan="3" class="top" ><span class="title1"><strong>NACIMIENTO</strong></span> </td> 
                    <td colspan="3" class="top">
                        <label class="title2 top-5" for="nacimiento_vivo">VIVO</label> 
                        <input class="black" type="radio" tabindex="662" name="var_0282" value="A" title="variable: <Nacimiento>, valor:<vivio>, id:<0282_A>"
                               id="nacimiento_vivo" <?=($ficha->getVar0282()=='A')?$checked:'' ?> >                        
                    </td>    
                </tr>
                <tr>
                    <td><span class="title2" style="bottom: 4px;position: relative;">MUERTO &nbsp;</span>
                        <label class="title3 fright" for="nacimiento_muerto_anteparto">anteparto</label>
                    </td> 
                    <td style="padding-right: 4px;">
                        <input class="yellow" type="radio" tabindex="663" name="var_0282" value="B" title="variable: <Nacimiento>, valor:<muerto ante parto>, id:<0282_B>"
                               id="nacimiento_muerto_anteparto" <?=($ficha->getVar0282()=='B')?$checked:'' ?> >
                    </td>   
                    <td class="alignright" style="padding-right: 4px;"><label class="title3" for="nacimiento_muerto_parto">parto</label></td>
                    <td>
                        <input class="yellow" type="radio" tabindex="664" name="var_0282" value="C" title="variable: <Nacimiento>, valor:<muerto parto>, id:<0282_C>"
                               id="nacimiento_muerto_parto" <?=($ficha->getVar0282()=='C')?$checked:'' ?> >
                    </td>
                    <td class="alignright" ><label class="title3" for="nacimiento_muerto_ignora">ignora momento</label></td>
                    <td style="padding-right: 4px;">
                        <input class="yellow" type="radio" tabindex="665" name="var_0282" value="D" title="variable: <Nacimiento>, valor:<muerto ignora momento>, id:<0282_D>"
                               id="nacimiento_muerto_ignora" <?=($ficha->getVar0282()=='D')?$checked:'' ?> >
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2 top" id="nacimiento_dia_hora">
            <table class="inside">
                <tr>
                    <td><label class="title3 block">hora &nbsp; - &nbsp; min</label></td> 
                    <td><label class="title3">d&iacute;a &nbsp; - &nbsp; mes &nbsp; - &nbsp; a&ntilde;o</label></td> 
                </tr>
                <tr>
                    <td><input name="var_0283H" type="text" value="<?=$ficha->getVar0283H() ?>" id="nacimiento_hora" title="variable: <Hora Nacimiento>, valor:<hora>, id:<0283_A>"
                               min="00" max="23" tabindex="666" class="number width2"/> 
                        <input name="var_0283M" type="text" value="<?=$ficha->getVar0283M() ?>" id="nacimiento_minuto" title="variable: <Hora Nacimiento>, valor:<minutos>, id:<0283_B>"
                               min="00" max="59" tabindex="667" class="number width2"/>
                    </td> 
                    <td>
                        <input type="text" class="datepicker" value="<?=FechaView($ficha->getVar0284()) ?>" name="var_0284" title="variable: <Fecha de Nacimiento>, valor:<fecha>, id:<0284>"
                               id="nacimiento_dia" tabindex="668"/>
                    </td>   
                </tr>
            </table>
        </td>
        <td class="border2 top" id="nacimiento_multiple">
            <table class="inside">
                <tr>
                    <td colspan="2"><label class="title3 block">M&Uacute;LTIPLE</label></td> 
                    <td><label class="title3">orden</label></td> 
                </tr>
                <tr>
                    <td><label class="title3 block" for="nacimiento_multiple_no">no</label> 
                        <input class="black" type="radio" tabindex="669" name="var_0285" value="A" title="variable: <Embarazo m&uacute;ltiple>, valor:<no>, id:<0285_A>"
                            id="nacimiento_multiple_no" <?=($ficha->getVar0285()=='A')?$checked:'' ?> >
                    </td> 
                    <td><label class="title3 block" for="nacimiento_multiple_si">si</label> 
                         <input class="yellow" type="radio" tabindex="670" name="var_0285" value="B" title="variable: <Embarazo m&uacute;ltiple>, valor:<si>, id:<0285_B>"
                            id="nacimiento_multiple_si" <?=($ficha->getVar0285()=='B')?$checked:'' ?> >
                    </td> 
                    <td>
                        <input type="text" class="width1 required number" value="<?=$ficha->getVar0286() ?>" name="var_0286" title="variable: <Embarazo m&uacute;ltiple - orden>, valor:<orden>, id:<0286>"
                               id="nacimiento_multiple_orden" maxlength="1" tabindex="671" min="0" max="9" readonly="readonly"/>
                    </td>   
                </tr>
            </table>
        </td>
        <td class="border2 top" id="nacimiento_terminacion">
            <table class="inside">
                <tr>
                    <td><label class="title3 block">TERMINACI&Oacute;N</label></td> 
                    <td><label class="title3 top-5" for="nacimiento_terminacion_espontaneo">espont.</label> 
                        <input class="black" type="radio" tabindex="672" name="var_0287" value="A" title="variable: <Terminaci&oacute;n>, valor:<vaginal espont&aacute;nea>, id:<0287_A>"
                               id="nacimiento_terminacion_espontaneo" <?=($ficha->getVar0287()=='A')?$checked:'' ?> >
                    </td> 
                    <td><label class="title3 top-5" for="nacimiento_terminacion_cesarea">ces&aacute;rea</label> 
                        <input class="yellow" type="radio" tabindex="673" name="var_0287" value="B" title="variable: <Terminaci&oacute;n>, valor:<ces&aacute;rea>, id:<0287_B>"
                               id="nacimiento_terminacion_cesarea" <?=($ficha->getVar0287()=='B')?$checked:'' ?> >
                    </td> 
                </tr>
                <tr>
                    <td><label class="title3 top-5" for="nacimiento_terminacion_forceps">forceps</label> 
                        <input class="yellow" type="radio" tabindex="674" name="var_0287" value="C" title="variable: <Terminaci&oacute;n>, valor:<f&oacute;rceps>, id:<0287_C>"
                               id="nacimiento_terminacion_forceps" <?=($ficha->getVar0287()=='C')?$checked:'' ?> >
                    </td> 
                    <td><label class="title3 top-5" for="nacimiento_terminacion_vacuum">vacuum</label> 
                        <input class="yellow" type="radio" tabindex="675" name="var_0287" value="D" title="variable: <Terminaci&oacute;n>, valor:<vacuum>, id:<0287_D>"
                               id="nacimiento_terminacion_vacuum" <?=($ficha->getVar0287()=='D')?$checked:'' ?> >
                    </td> 
                    <td><label class="title3 top-5" for="nacimiento_terminacion_otra">&nbsp;&nbsp;&nbsp;otra&nbsp;</label> 
                        <input class="yellow" type="radio" tabindex="676" name="var_0287" value="E" title="variable: <Terminaci&oacute;n>, valor:<otra>, id:<0287_E>"
                               id="nacimiento_terminacion_otra" <?=($ficha->getVar0287()=='E')?$checked:'' ?> >
                    </td> 
                </tr>
            </table>
        </td>
        <td class="border2 bottom" id="motivo_induccion">
            <table class="inside">
                <tr>
                    <td><label class="title1">INDICACI&Oacute;N PRINCIPAL DE</label></td>
                    <td class="aligncenter" colspan="2"><span class="title2 block">C&oacute;digo</span></td>
                </tr>
                <tr>
                    <td><label class="title1">INDUCCI&Oacute;N O PARTO OPERATORIO</label></td>
                    <td class="aligncenter"><label class="title3">INDUC.</label></td>
                    <td class="aligncenter"><label class="title3">OPER.</label></td>
                </tr>
                <tr>
                    <td><input type="text" style="width: 100%;font-size:9px;" maxlength="100" tabindex="677" name="var_0288" title="variable: <Motivo principal de inducci&oacute;n o cirug&iacute;a>, valor:<notas>, id:<0288>"
                               id="motivo_induccion_cirugia" value="<?=$ficha->getVar0288() ?>">
                    </td>
                    <td class="aligncenter">
                        <input type="text" class="width2 alerta auxiliar number" data-tabla="INDICACION PARTO-OPERATORIO" tabindex="678" maxlength="2" title="variable: <C&oacute;digo inducci&oacute;n>, valor:<c&oacute;digo>, id:<0289>"
                               value="<?=$ficha->getVar0289()?>" name="var_0289" id="codigo_induccion">
                    </td>
                    <td class="aligncenter">
                        <input type="text" class="width2 alerta auxiliar number" data-tabla="INDICACION PARTO-OPERATORIO" tabindex="679" maxlength="2" title="variable: <C&oacute;digo operatorio>, valor:<c&oacute;digo>, id:<0290>"
                               value="<?=$ficha->getVar0290()?>" name="var_0290" id="codigo_cirugia">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>    