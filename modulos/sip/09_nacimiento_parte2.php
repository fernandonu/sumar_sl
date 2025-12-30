<table id="section9">
    <!-- NACIMIENTO - 680/714  -->   
    <tr>
        <td class="border2 top" id="posicion_parto">
            <table class="inside">
                <tr><td colspan="2" class="titulo"><span class="title1">POSICI&Oacute;N PARTO </td> </tr>
                <tr><td>
                        <label class="title2 block" for="posicion_parto_sentada">sentada</label> 
                        <input class="black" type="radio" tabindex="680" name="var_0291" value="A" title="variable: <Posici&oacute;n parto>, valor:<sentada>, id:<0291_A>"
                               id="posicion_parto_sentada" <?=($ficha->getVar0291()=='A')?$checked:'' ?> >
                    </td>    
                    <td rowspan="2">
                        <label class="title2 block" for="posicion_parto_acostada">acostada</label> 
                        <input class="black" type="radio" tabindex="682" name="var_0291" value="C" title="variable: <Posici&oacute;n parto>, valor:<acostada>, id:<0291_C>"
                               id="posicion_parto_acostada" <?=($ficha->getVar0291()=='C')?$checked:'' ?> >                        
                    </td>    
                </tr>
                <tr>
                    <td>
                        <label class="title2 block" for="posicion_parto_cuclillas">cuclillas</label> 
                        <input class="black" type="radio" tabindex="681" name="var_0291" value="B" title="variable: <Posici&oacute;n parto>, valor:<cuclillas>, id:<0291_B>"
                               id="posicion_parto_cuclillas" <?=($ficha->getVar0291()=='B')?$checked:'' ?> >                        
                    </td>    
                </tr>
            </table>
        </td>
        <td class="border2" id="episiotomia">
            <table class="inside">
                <tr><td colspan="2"><span class="title3">EPISIOTOM&Iacute;A</span></td> </tr>
                <tr><td>
                        <label class="title2 top-5" for="episiotomia_no">no</label> 
                        <input class="black" type="radio" tabindex="683" name="var_0292" value="A" title="variable: <Episiotom&iacute;a>, valor:<no>, id:<0292_A>"
                               id="episiotomia_no" <?=($ficha->getVar0292()=='A')?$checked:'' ?> >                        
                    </td>    
                </tr>
                <tr>
                    <td>
                        <label class="title2 top-5" for="episiotomia_si">si</label> 
                        <input class="black" type="radio" tabindex="684" name="var_0292" value="B" title="variable: <Episiotom&iacute;a>, valor:<si>, id:<0292_B>"
                               id="episiotomia_si" <?=($ficha->getVar0292()=='B')?$checked:'' ?> >                        
                    </td>    
                </tr>
            </table>
        </td>
        <td class="border2" id="desgarros">
            <table class="inside">
                <tr><td colspan="2"><span class="title3 block">DESGARROS</span></td></tr>
                <tr><td>
                        <label class="title2" for="desgarros_no">no</label> 
                        <input class="black" type="checkbox" tabindex="685" name="var_0293" value="X" title="variable: <Desgarros (no)>, valor:<no>, id:<0293_X>"
                               id="desgarros_no" <?=($ficha->getVar0293()=='X')?$checked:'' ?> >                        
                    </td> 
                    <td><span class="title3">Grado<br>(1 a 4)</span>
                        <input name="var_0294" type="text" id="desgarro_grado" class="alerta number width2" title="variable: <Desgarros - grado>, valor:<grado (1 a 4)>, id:<0294>"
                               value="<?=$ficha->getVar0294()?>" min="1" max="4" tabindex="686"/> 
                    </td> 
                </tr>
            </table>
        </td>
        <td class="border2" id="ocitocicos">
            <table class="inside">
                <tr><td colspan="4" ><span class="title3 block">OCITOCICOS</span></td> </tr>
                <tr>
                    <td colspan="2" ><span class="title3 block">prealumbr.</span></td> 
                    <td colspan="2" ><span class="title3 block">postalumbr.</span></td> 
                </tr>
                <tr>
                    <td>
                        <label class="title2" for="ocitocicos_prealumbramiento_no">no</label> 
                        <input class="black" type="radio" tabindex="687" name="var_0295" value="A" title="variable: <Ocit&oacute;cicos prealumbramiento>, valor:<no>, id:<0295_A>"
                               id="ocitocicos_prealumbramiento_no" <?=($ficha->getVar0295()=='A')?$checked:'' ?> > 
                    </td> 
                    <td>
                        <label class="title2" for="ocitocicos_prealumbramiento_si">si</label> 
                        <input class="black" type="radio" tabindex="688" name="var_0295" value="B" title="variable: <Ocit&oacute;cicos prealumbramiento>, valor:<si>, id:<0295_B>"
                               id="ocitocicos_prealumbramiento_si" <?=($ficha->getVar0295()=='B')?$checked:'' ?> >
                    </td> 
                    <td class="borderL1">
                        <label class="title2" for="ocitocicos_postalumbramiento_no">no</label> 
                        <input class="yellow" type="radio" tabindex="689" name="var_0296" value="A" title="variable: <Ocit&oacute;cicos postalumbramiento>, valor:<no>, id:<0296_A>"
                               <?=($ficha->getVar0296()=='A')?$checked:'' ?> id="ocitocicos_postalumbramiento_no">                        
                    </td> 
                    <td>
                        <label class="title2" for="ocitocicos_postalumbramiento_si">si</label> 
                        <input class="black" type="radio" tabindex="690" name="var_0296" value="B" title="variable: <Ocit&oacute;cicos postalumbramiento>, valor:<no>, id:<0296_B>"
                               <?=($ficha->getVar0296()=='B')?$checked:'' ?> id="ocitocicos_postalumbramiento_si">
                    </td> 
                </tr>
            </table>
        </td>
        <td class="border2" id="placenta">
            <table class="inside">
                <tr><td colspan="3" ><span class="title3 block">PLACENTA</span></td> </tr>
                <tr>
                    <td><span style="padding-left: 4px;" class="title3 block">completa</span></td> 
                    <td><label class="title2" for="placenta_completa_no">no</label> 
                        <input class="yellow" type="radio" tabindex="691" name="var_0297" value="A" title="variable: <Placenta completa>, valor:<no>, id:<0297_A>"
                               id="placenta_completa_no" <?=($ficha->getVar0297()=='A')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2" for="placenta_completa_si">si</label> 
                        <input class="black" type="radio" tabindex="692" name="var_0297" value="B" title="variable: <Placenta completa>, valor:<si>, id:<0297_B>"
                               id="placenta_completa_si" <?=($ficha->getVar0297()=='B')?$checked:'' ?> >
                    </td> 
                </tr>
                <tr>
                    <td><span style="padding-left: 4px;" class="title3 block">retenida</span></td> 
                    <td>
                        <input class="black" type="radio" tabindex="693" name="var_0298" value="A" title="variable: <Placenta retenida>, valor:<no>, id:<0298_A>"
                               id="placenta_retenida_si" <?=($ficha->getVar0298()=='A')?$checked:'' ?> >
                    </td> 
                    <td>
                        <input class="yellow" type="radio" tabindex="694" name="var_0298" value="B" title="variable: <Placenta retenida>, valor:<si>, id:<0298_B>"
                               id="placenta_retenida_no" <?=($ficha->getVar0298()=='B')?$checked:'' ?> >
                    </td> 
                </tr>
            </table>
        </td>
        <td class="border2" id="ligadura_cordon">
            <table class="inside">
                <tr><td colspan="2" >
                        <span class="title3 block">LIGADURA CORD&Oacute;N</span>
                        <span class="title3 block">precoz</span>
                    </td> 
                </tr>
                <tr>
                    <td><label class="title2 block" for="ligadura_cordon_precoz_no">no</label> 
                        <input class="black" type="radio" tabindex="695" name="var_0299" value="A" title="variable: <Ligadura cord&oacute;n precoz>, valor:<no>, id:<0299_A>"
                               id="ligadura_cordon_precoz_no" <?=($ficha->getVar0299()=='A')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 block" for="ligadura_cordon_precoz_si">si</label> 
                        <input class="yellow" type="radio" tabindex="696" name="var_0299" value="B" title="variable: <Ligadura cord&oacute;n precoz>, valor:<si>, id:<0299_B>"
                               id="ligadura_cordon_precoz_si" <?=($ficha->getVar0299()=='B')?$checked:'' ?> >
                    </td> 
                </tr>
            </table>
        </td>
        <td class="border2" id="medicacion_recibida">
            <table class="inside">
                <tr><td colspan="7"><span class="title2">MEDICACI&Oacute;N RECIBIDA</span></td></tr>
                <tr>
                    <td><span class="title3 block">ocitocicos<br>en TDP</span></td> 
                    <td><span class="title3 block">antibiotico</span></td> 
                    <td><span class="title3 block">analgesia</span></td> 
                    <td><span class="title3 block">anestesia<br>local</span></td> 
                    <td><span class="title3 block">anestesia<br>regional</span></td> 
                    <td><span class="title3 block">anestesia<br>general</span></td> 
                    <td><span class="title3 block">transfusi&oacute;n</span></td> 
                </tr>
                <tr>
                    <td><label class="title2 top-5" for="ocitocicos_tdp_no">no</label> 
                        <input class="black" type="radio" tabindex="697" name="var_0300" value="A" title="variable: <Ocit&oacute;cicos en TDP>, valor:<no>, id:<0300_A>"
                               id="ocitocicos_tdp_no" <?=($ficha->getVar0300()=='A')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 top-5" for="antibioticos_no">no</label> 
                        <input class="black" type="radio" tabindex="699" name="var_0301" value="A" title="variable: <Antibi&oacute;ticos>, valor:<no>, id:<0301_A>"
                               id="antibioticos_no" <?=($ficha->getVar0301()=='A')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 top-5" for="analgesia_no">no</label> 
                        <input class="black" type="radio" tabindex="701" name="var_0302" value="A" title="variable: <Analgesia>, valor:<no>, id:<0302_A>"
                               id="analgesia_no" <?=($ficha->getVar0302()=='A')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 top-5" for="anestesia_local_no">no</label> 
                        <input class="black" type="radio" tabindex="703" name="var_0303" value="A" title="variable: <Anestesia local>, valor:<no>, id:<0303_A>"
                               id="anestesia_local_no" <?=($ficha->getVar0303()=='A')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 top-5" for="anestesia_regional_no">no</label> 
                        <input class="black" type="radio" tabindex="705" name="var_0304" value="A" title="variable: <Anestesia regional>, valor:<no>, id:<0304_A>"
                               id="anestesia_regional_no" <?=($ficha->getVar0304()=='A')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 top-5" for="anestesia_general_no">no</label> 
                        <input class="black" type="radio" tabindex="707" name="var_0305" value="A" title="variable: <Anestesia general>, valor:<no>, id:<0305_A>"
                               id="anestesia_general_no" <?=($ficha->getVar0305()=='A')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 top-5" for="transfusion_no">no</label> 
                        <input class="black" type="radio" tabindex="709" name="var_0306" value="A" title="variable: <Transfusi&oacute;n>, valor:<no>, id:<0306_A>"
                               id="transfusion_no" <?=($ficha->getVar0306()=='A')?$checked:'' ?> >
                    </td> 
                </tr>
                <tr>
                    <td><label class="title2 top-5" for="ocitocicos_tdp_si">si</label> 
                        <input class="yellow" type="radio" tabindex="698" name="var_0300" value="B" title="variable: <Ocit&oacute;cicos en TDP>, valor:<si>, id:<0300_B>"
                               id="ocitocicos_tdp_si" <?=($ficha->getVar0300()=='B')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 top-5" for="antibioticos_si">si</label> 
                        <input class="yellow" type="radio" tabindex="700" name="var_0301" value="B" title="variable: <Antibi&oacute;ticos>, valor:<si>, id:<0301_B>"
                               id="antibioticos_si" <?=($ficha->getVar0301()=='B')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 top-5" for="analgesia_si">si</label> 
                        <input class="yellow" type="radio" tabindex="702" name="var_0302" value="B" title="variable: <Analgesia>, valor:<si>, id:<0302_B>" 
                               id="analgesia_si" <?=($ficha->getVar0302()=='B')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 top-5" for="anestesia_local_si">si</label> 
                        <input class="yellow" type="radio" tabindex="704" name="var_0303" value="B" title="variable: <Anestesia local>, valor:<si>, id:<0303_B>"
                               id="anestesia_local_si" <?=($ficha->getVar0303()=='B')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 top-5" for="anestesia_regional_si">si</label> 
                        <input class="yellow" type="radio" tabindex="706" name="var_0304" value="B" title="variable: <Anestesia regional>, valor:<si>, id:<0304_B>"
                               id="anestesia_regional_si" <?=($ficha->getVar0304()=='B')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 top-5" for="anestesia_general_si">si</label> 
                        <input class="yellow" type="radio" tabindex="708" name="var_0305" value="B" title="variable: <Anestesia general>, valor:<si>, id:<0305_B>"
                               id="anestesia_general_si" <?=($ficha->getVar0305()=='B')?$checked:'' ?> >
                    </td> 
                    <td><label class="title2 top-5" for="transfusion_si">si</label> 
                        <input class="yellow" type="radio" tabindex="710" name="var_0306" value="B" title="variable: <Transfusi&oacute;n>, valor:<si>, id:<0306_B>"
                               id="transfusion_si" <?=($ficha->getVar0306()=='B')?$checked:'' ?> >
                    </td> 
                </tr>

            </table>
        </td>
        <td class="border2" id="medicacion_recibida_otra">
            <table class="inside">
                <tr>
                    <td colspan="2" ><span class="title3 block">OTROS</span></td> 
                    <td colspan="2" ><span style="position:absolute;right:40px;top:15px;" class="title3 block">c&oacute;digo</span></td> 
                </tr>
                <tr>
                    <td class="top" style="width: 32px;"><label class="title2 block" for="medicacion_recibida_otros_no">no</label> 
                        <input class="black" type="radio" tabindex="711" name="var_0307" value="A" title="variable: <Otros>, valor:<no>, id:<0307_A>"
                               id="medicacion_recibida_otros_no" <?=($ficha->getVar0307()=='A')?$checked:'' ?> >
                    </td> 
                    <td class="top" style="width: 32px;"><label class="title2 block" for="medicacion_recibida_otros_si">si</label> 
                        <input class="yellow" type="radio" tabindex="712" name="var_0307" value="B" title="variable: <Otros>, valor:<si>, id:<0307_B>"
                               id="medicacion_recibida_otros_si" <?=($ficha->getVar0307()=='B')?$checked:'' ?> >
                    </td> 
                    <td rowspan="2" class="bottom"><label class="title2">medic 1</label> 
                        <input  style="margin-bottom: 10px;" name="var_0308" id="codigo_medicacion_1" type="text" title="variable: <C&oacute;digo medicaci&oacute;n 1>, valor:<c&oacute;digo>, id:<0308>"
                                value="<?=$ficha->getVar0308()?>" tabindex="713" maxlength="2"
                            class="width2 alerta auxiliar number" data-tabla="MEDICACION PARTO"/>
                    </td> 
                    <td rowspan="2" class="bottom"><label class="title2">medic 2</label> 
                        <input style="margin-bottom: 10px;" name="var_0309" id="codigo_medicacion_2" type="text" title="variable: <C&oacute;digo medicaci&oacute;n 2>, valor:<c&oacute;digo>, id:<0309>"
                               value="<?=$ficha->getVar0309()?>" tabindex="714" maxlength="2"
                            class="width2 alerta auxiliar number"  data-tabla="MEDICACION PARTO"/>
                    </td> 
                </tr>
                <tr> <td colspan="2"><label style="left:6px;position:absolute;top:50px;" class="title2" >especificar</label> </td> 
                    <td colspan="2">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>