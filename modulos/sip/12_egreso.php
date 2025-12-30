<table id="section12">
    <!-- EGRESOS RN Y MATERNO - ANTICONCEPCION - 936/  -->   
    <tr>
        <td class="border2" id="rn_egreso">
            <table class="inside">
                <tr> 
                    <td valign="top" colspan="2" >
                        <h6>EGRESO RN</h6>  
                    </td> 
                    <td rowspan="2" style="width: 45px;" class="bottom">
                        <label class="title3 block" for="rn_egreso_traslado">traslado</label>
                        <input class="yellow" type="checkbox" tabindex="941" name="var_0371" value="C" title="variable: <Egreso RN>, valor:<traslado>, id:<0371_C>"
                               id="rn_egreso_traslado" <?=($ficha->getVar0371()=='C')?$checked:'' ?> >
                    </td>
                    <td rowspan="2" colspan="2"><span class="title3">fallece<br>durante o<br>en lugar<br>de traslado</span></td>
                    <td rowspan="2" colspan="2" class="bottom">
                        <span class="title3 block">EDAD AL EGRESO</span>
                        <span class="title3">d&iacute;as completos</span>
                    </td>
                </tr> 
                <tr>
                    <td> <label class="title3 top-5" for="rn_egreso_vivo">vivo</label> 
                         <input class="black" type="radio" tabindex="939" name="var_0371" value="A" title="variable: <Egreso RN>, valor:<vivo>, id:<0371_A>"
                                id="rn_egreso_vivo" <?=($ficha->getVar0371()=='A')?$checked:'' ?> >
                    </td>     
                    <td> <label class="title3 top-5" for="rn_egreso_fallece">fallece</label> 
                          <input class="yellow" type="radio" tabindex="940" name="var_0371" value="B" title="variable: <Egreso RN>, valor:<fallece>, id:<0371_B>"
                                 id="rn_egreso_fallece" <?=($ficha->getVar0371()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr>
                    <td><label class="title3 block">d&iacute;a &nbsp; - &nbsp; mes &nbsp; - &nbsp; a&ntilde;o</label>
                        <input type="text" class="datepicker" value="<?=  FechaView($ficha->getVar0425()) ?>" name="var_0425" title="variable: <Egreso RN - fecha>, valor:<fecha>, id:<0425>"
                               id="rn_egreso_fecha" tabindex="936"/>
                    </td>   
                    <td><label class="title3 block">hora &nbsp; - &nbsp; min</label>
                        <input name="var_0370H" type="text" value="<?=$ficha->getVar0370H() ?>" id="rn_egreso_hora" title="variable: <Egreso RN - hora>, valor:<hora>, id:<0370_A>"
                               min="00" max="23" tabindex="937" class="number width2"/> 
                        <input name="var_0370M" type="text" value="<?=$ficha->getVar0370M() ?>" id="rn_egreso_minuto" title="variable: <Egreso RN - hora>, valor:<minutos>, id:<0370_B>"
                               min="00" max="59" tabindex="938" class="number width2"/>
                    </td> 
                    <td>|<br>|<br>|<br>|</td>
                    <td style="width: 30px;"><label class="title3 block" for="rn_fallece_traslado_no">no</label> 
                         <input class="black" type="radio" tabindex="943" name="var_0373" value="A" title="variable: <Fallece lugar de traslado>, valor:<no>, id:<0373_A>"
                                id="rn_fallece_traslado_no" <?=($ficha->getVar0373()=='A')?$checked:'' ?> >
                    </td>     
                    <td style="width: 30px;">     
                        <label class="title3 block" for="rn_fallece_traslado_si">si</label> 
                         <input class="yellow" type="radio" tabindex="944" name="var_0373" value="B" title="variable: <Fallece lugar de traslado>, valor:<si>, id:<0373_B>"
                                id="rn_fallece_traslado_si" <?=($ficha->getVar0373()=='B')?$checked:'' ?> >
                    </td>                    
                    <td style="width:50px;">
                        <input class="number width2" type="text" tabindex="945" name="var_0374" id="rn_edad_egreso" maxlength="2" title="variable: <Edad egreso>, valor:<d&iacute;as completos>, id:<0374>"
                               value="<?=$ficha->getVar0374() ?>" >
                    </td>     
                    <td style="width: 35px;">     
                        <label class="title3 block" for=""><1 d&iacute;a</label> 
                        <input class="black" type="radio" tabindex="946" name="var_0375" value="X" title="variable: <Edad < 1 d&iacute;a>, valor:<si>, id:<0375_X>"
                                id="rn_edad_menor_1dia" <?=($ficha->getVar0375()=='X')?$checked:'' ?> >
                    </td>                    
                </tr>
                <tr>
                    <td colspan="2" style="border-top:1px solid black; border-right: 1px solid black;">
                        <label class="title2 block alignleft" for="">Id RN</label>
                        <input class="width100" type="text" value="<?=$ficha->getVar0388() ?>" maxlength="30" name="var_0388" title="variable: <Nro. Historia RN>, valor:<n&uacute;mero>, id:<0388>"
                               id="rn_historia_id" tabindex="973"/>
                    </td>
                    <td colspan="5">
                        <label class="title3 block alignleft" style="margin-left: 5px;">lugar de traslado</label>
                        <input class="width100" type="text" value="<?=$ficha->getVar0372() ?>" maxlength="10" name="var_0372" title="variable: <Lugar traslado RN>, valor:<lugar>, id:<0372>"
                               id="rn_lugar_traslado" tabindex="942" class="efectorselect"/>
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2" id="rn_alimentacion_alta">
            <table class="inside">
                <tr><td colspan="2"><span class="title3">ALIMENTO<br>AL ALTA</span></td> </tr>
                <tr><td><label class="title2 top-5" for="rn_alimento_alta_lactexclusiva">lact.<br>excl.</label> </td>
                    <td>
                        <input class="black" type="radio" tabindex="947" name="var_0376" value="A" title="variable: <Alimento al alta>, valor:<lactancia exclusiva>, id:<0376_A>"
                               id="rn_alimento_alta_lactexclusiva" <?=($ficha->getVar0376()=='A')?$checked:'' ?> >
                    </td>
                </tr>
                <tr><td><label class="title2 top-5" for="rn_alimento_alta_parcial">parcial</label> </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="948" name="var_0376" value="B" title="variable: <Alimento al alta>, valor:<parcial>, id:<0376_B>"
                               id="rn_alimento_alta_parcial" <?=($ficha->getVar0376()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr><td><label class="title2 top-5" for="rn_alimento_alta_artificial">artificial</label> </td>
                    <td>
                        <input class="yellow" type="radio" tabindex="949" name="var_0376" value="C" title="variable: <Alimento al alta>, valor:<artificial>, id:<0376_C>"
                               id="rn_alimento_alta_artificial" <?=($ficha->getVar0376()=='C')?$checked:'' ?> >
                    </td>
                </tr>
            </table>            
        </td>
        <td class="border2" id="rn_egreso_vs">
            <table class="inside">
                <tr><td colspan="2"><span class="title3">Boca arriba</span></td> </tr>
                <tr class="borderB2"><td><label class="title2 top-5" for="rn_boca_arriba_no">no</label> 
                        <input class="yellow" type="radio" tabindex="950" name="var_0377" value="A" title="variable: <Boca arriba>, valor:<no>, id:<0377_A>"
                               id="rn_boca_arriba_no" <?=($ficha->getVar0377()=='A')?$checked:'' ?> >
                    </td>
                    <td><label class="title2 top-5" for="rn_boca_arriba_si">si</label> 
                        <input class="black" type="radio" tabindex="951" name="var_0377" value="B" title="variable: <Boca arriba>, valor:<si>, id:<0377_B>"
                               id="rn_boca_arriba_si" <?=($ficha->getVar0377()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr><td colspan="2"><span class="title3">BCG</span></td> </tr>
                <tr class="borderB2"><td><label class="title2 top-5" for="rn_bcg_no">no</label> 
                        <input class="yellow" type="radio" tabindex="952" name="var_0378" value="A" title="variable: <BCG>, valor:<no>, id:<0378_A>"
                               id="rn_bcg_no" <?=($ficha->getVar0378()=='A')?$checked:'' ?> >
                    </td>
                    <td><label class="title2 top-5" for="rn_bcg_si">si</label> 
                        <input class="black" type="radio" tabindex="953" name="var_0378" value="B" title="variable: <BCG>, valor:<si>, id:<0378_B>"
                               id="rn_bcg_si" <?=($ficha->getVar0378()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr><td colspan="3">
                        <span class="title3">PESO AL EGRESO</span>
                        <input class="number width3" type="text" value="<?=$ficha->getVar0395() ?>" maxlength="4" title="variable: <Peso RN al egreso>, valor:<gramos>, id:<0395>"
                               name="var_0395" id="rn_peso_egreso" tabindex="954" min="10" max="6000"/> g
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2" id="rn_egreso_materno">
            <table class="inside">
                <tr> 
                    <td valign="top" colspan="6" >
                        <h6>EGRESO MATERNO</h6>  
                    </td>
                </tr>    
                <tr>
                    <td colspan="2"><label class="title3 block">d&iacute;a &nbsp; - &nbsp; mes &nbsp; - &nbsp; a&ntilde;o</label>
                        <input type="text" class="datepicker" value="<?=FechaView($ficha->getVar0379()) ?>" name="var_0379" title="variable: <Fecha de egreso materno>, valor:<fecha>, id:<0379>"
                               id="egreso_materno_fecha" tabindex="955"/>
                    </td>
                    <td style="width: 45px;"><label class="title3 block" for="traslado_materno">traslado</label>
                        <input class="yellow" type="checkbox" tabindex="956" name="var_0380" value="X" title="variable: <Traslado>, valor:<si>, id:<0380_X>"
                               id="traslado_materno" <?=($ficha->getVar0380()=='X')?$checked:'' ?> >
                    </td>
                    <td colspan="3"><label class="title3 block alignleft" style="margin-left: 5px;">lugar de traslado</label>
                        <input class="width100 efectorselect" type="text" value="<?=$ficha->getVar0381() ?>" name="var_0381" tabindex="957" title="variable: <Lugar traslado materno>, valor:<c&oacute;digo>, id:<0381>"
                               id="lugar_traslado_materno" maxlength="10"/>
                    </td>
                </tr>
                <tr>
                    <td> <label class="title3 top-5" for="egreso_materno_viva">viva</label> 
                         <input class="black" type="radio" tabindex="958" name="var_0382" value="A" title="variable: <Egreso materno>, valor:<viva>, id:<0382_A>"
                                id="egreso_materno_viva" <?=($ficha->getVar0382()=='A')?$checked:'' ?> >
                    </td>     
                    <td> <label class="title3 top-5" for="egreso_materno_fallece">fallece</label> 
                          <input class="yellow" type="radio" tabindex="959" name="var_0382" value="B" title="variable: <Egreso materno>, valor:<fallece>, id:<0382_B>"
                                 id="egreso_materno_fallece" <?=($ficha->getVar0382()=='B')?$checked:'' ?> >
                    </td>
                    <td><span class="title3">fallece durante o en lugar de traslado</span></td>
                    <td style="width: 25px;"> <label class="title3 top-5" for="egreso_materno_fallece_traslado_no">no</label> 
                         <input class="black" type="radio" tabindex="960" name="var_0383" value="A" title="variable: <Fallece durante traslado>, valor:<no>, id:<0383_A>"
                                id="egreso_materno_fallece_traslado_no" <?=($ficha->getVar0383()=='A')?$checked:'' ?> >
                    </td>     
                    <td style="width: 25px;"> <label class="title3 top-5" for="egreso_materno_fallece_traslado_si">si</label> 
                          <input class="yellow" type="radio" tabindex="961" name="var_0383" value="B" title="variable: <Fallece durante traslado>, valor:<si>, id:<0383_B>"
                                 id="egreso_materno_fallece_traslado_si" <?=($ficha->getVar0383()=='B')?$checked:'' ?> >
                    </td>
                    <td style="width: 80px;"><label class="title3 block alignleft" style="margin-left: 5px;">d&iacute;as completos desde el parto</label>
                        <input class="number width2" type="text" value="<?=$ficha->getVar0384() ?>" name="var_0384" title="variable: <Fallece durante traslado - d&iacute;as>, valor:<d&iacute;as completos desde parto>, id:<0384>"
                               id="egreso_materno_fallece_traslado_dias" tabindex="962" maxlength="3"/>
                    </td>
                </tr>
            </table>
        </td>
        <td class="border2" id="anticoncepcion">
            <table class="inside">
                <tr> <td colspan="4"> <h6>ANTICONCEPCION</h6> </td> </tr>
                <tr class="borderB1"> 
                    <td colspan="2"> <span class="title3">CONSERJER&Iacute;A</span> </td>
                    <td><label class="title2 top-5" for="conserjeria_no">no</label> 
                        <input class="yellow" type="radio" tabindex="963" name="var_0385" value="A" title="variable: <Anticoncepci&oacute;n conserjer&iacute;a>, valor:<no>, id:<0385_A>"
                               id="conserjeria_no" <?=($ficha->getVar0385()=='A')?$checked:'' ?> >
                    </td>
                    <td><label class="title2 top-5" for="conserjeria_si">si</label> 
                        <input class="black" type="radio" tabindex="964" name="var_0385" value="B" title="variable: <Anticoncepci&oacute;n conserjer&iacute;a>, valor:<si>, id:<0385_B>"
                               id="conserjeria_si" <?=($ficha->getVar0385()=='B')?$checked:'' ?> >
                    </td>
                </tr>
                <tr><td colspan="4"><span class="title3">M&Eacute;TODO ELEGIDO</span></td> </tr>
                <tr><td style="width: 45px;"> <label class="title3" for="metodo_anticoncepcion_diupost">DIU post- evento</label> </td>
                    <td> 
                        <input class="black" type="radio" tabindex="965" name="var_0386" value="A" title="variable: <M&eacute;todo antic. elegido>, valor:<DIU post evento>, id:<0386_A>"
                               id="metodo_anticoncepcion_diupost" <?=($ficha->getVar0386()=='A')?$checked:'' ?> >
                    </td>
                    <td style="width: 45px;"> <label class="title3" for="metodo_anticoncepcion_ligadura">ligadura tubaria</label> </td>
                    <td> 
                        <input class="black" type="radio" tabindex="969" name="var_0386" value="E" title="variable: <M&eacute;todo antic. elegido>, valor:<ligadura tubaria>, id:<0386_E>"
                               id="metodo_anticoncepcion_ligadura" <?=($ficha->getVar0386()=='E')?$checked:'' ?> > 
                    </td>
                </tr>
                <tr><td> <label class="title3" for="metodo_anticoncepcion_diu">DIU</label> </td>
                    <td> 
                        <input class="black" type="radio" tabindex="966" name="var_0386" value="B" title="variable: <M&eacute;todo antic. elegido>, valor:<DIU>, id:<0386_B>"
                               id="metodo_anticoncepcion_diu" <?=($ficha->getVar0386()=='B')?$checked:'' ?> >
                    </td>
                    <td> <label class="title3" for="metodo_anticoncepcion_natural">natural</label> </td>
                    <td> 
                        <input class="black" type="radio" tabindex="970" name="var_0386" value="F" title="variable: <M&eacute;todo antic. elegido>, valor:<natural>, id:<0386_F>"
                               id="metodo_anticoncepcion_natural" <?=($ficha->getVar0386()=='F')?$checked:'' ?> > 
                    </td>
                </tr>
                <tr><td> <label class="title3" for="metodo_anticoncepcion_barrera">barrera</label> </td>
                    <td> 
                        <input class="black" type="radio" tabindex="967" name="var_0386" value="C" title="variable: <M&eacute;todo antic. elegido>, valor:<barrera>, id:<0386_C>"
                               id="metodo_anticoncepcion_barrera" <?=($ficha->getVar0386()=='C')?$checked:'' ?> >
                    </td>
                    <td> <label class="title3" for="metodo_anticoncepcion_otro">otro</label> </td>
                    <td> 
                        <input class="black" type="radio" tabindex="971" name="var_0386" value="G" title="variable: <M&eacute;todo antic. elegido>, valor:<otro>, id:<0386_G>"
                               id="metodo_anticoncepcion_otro" <?=($ficha->getVar0386()=='G')?$checked:'' ?> > 
                    </td>
                </tr>
                <tr><td> <label class="title3" for="metodo_anticoncepcion_hormonal">hormonal</label> </td>
                    <td> 
                        <input class="black" type="radio" tabindex="968" name="var_0386" value="D" title="variable: <M&eacute;todo antic. elegido>, valor:<hormonal>, id:<0386_D>"
                               id="metodo_anticoncepcion_hormonal" <?=($ficha->getVar0386()=='D')?$checked:'' ?> >
                    </td>
                    <td> <label class="title3" for="metodo_anticoncepcion_ninguno">ninguno</label> </td>
                    <td> 
                        <input class="yellow" type="radio" tabindex="972" name="var_0386" value="H" title="variable: <M&eacute;todo antic. elegido>, valor:<ninguno>, id:<0386_H>"
                               id="metodo_anticoncepcion_ninguno" <?=($ficha->getVar0386()=='H')?$checked:'' ?> > 
                    </td>
                </tr>
            </table> 
        </td>    
    </tr>
</table>
<table id="section13">
    <tr>
        <td class="border2" style="border-top: none;"><label class="title2">Nombre Reci&eacute;n Nacido</label>
            <input class="width100" type="text" value="<?=$ficha->getVar0389() ?>" maxlength="50" name="var_0389" title="variable: <Nombre RN>, valor:<nombre>, id:<0389>"
                   id="nombre_recien_nacido" tabindex="974"/>
        </td>
        <td class="border2" style="border-top: none;"><label class="title2">Responsable</label>
            <input class="width100" type="text" value="<?=$ficha->getVar0390() ?>" maxlength="50" name="var_0390" title="variable: <Responsable egreso RN>, valor:<nombre>, id:<0390>"
                   id="rn_responsable_egreso" tabindex="975"/>
        </td>
        <td class="border2" style="border-top: none;"><label class="title2">Responsable</label>
            <input class="width100" type="text" value="<?=$ficha->getVar0391() ?>" maxlength="50" name="var_0391" title="variable: <Responsable egreso materno>, valor:<nombre>, id:<0391>"
                   id="responsable_egreso_materno" tabindex="976"/>
        </td>
        <td><button type="button" id="agregar_varlibres" title="Agregar variables libres" tabindex="977">Variables Libres</button>
        </td>
    </tr>
</table>