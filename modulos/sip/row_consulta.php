<?php 
$idx = $_POST['idx'];
$i = $_POST['row'];
?>
<tr class="trconsulta">
    <td  style="border-left:1px solid #666;width:76px;position:relative;">
        <button type="button" class="efectorselectBtn"  style="top:0 !important"><img src="<?php echo "../../imagenes/lupa.gif" ?>"></button>
        <input name="consulta[id_efector][]" required="required" id="cons_efector_<?=$i ?>" tabindex="<?=$idx++ ?>" class="efectorselect all" >
    </td>
    <td  style="width:125px;">
        <select id="codigo_<?=$i ?>" class="codigoNomenclador" name="consulta[codigo][]" style="text-transform:none;width:70px;float:left;" tabindex="<?=$idx++ ?>" >
            <option value="">S/D</option>
        </select>
        <input type="hidden" id="diagnosticovalue_<?=$i ?>" value=""/>
        <select id="diagnostico_<?=$i ?>" name="consulta[diagnostico][]" class="diagnosticoNomenclador" style="text-transform:none;width:55px;float:right;" tabindex="<?=$idx++ ?>" >
            <option value="">S/D</option>
        </select>
        <input type="hidden" name="consulta[nuevo][]" value="1"/>

        <input type="hidden" name="consulta[trazadora_id_emb][]" value=""/>
        <input type="hidden" name="consulta[facturacion_id_prestacion][]" value=""/>
        <input type="hidden" name="consulta[facturado][]" value=""/>
    </td>
    <td style="width:80px">
        <input name="consulta[fecha_consulta][]" required="required" id="cons_fecha_<?=$i ?>" class="datepicker" tabindex="<?=$idx++ ?>" value="" title="variable: <Consulta <?=$i ?>>, valor:<fecha>, id:<0116-0117-0118>" >
    </td>
    <td style="width:40px">
        <input name="consulta[var_0119][]" required="required" id="cons_edad_gestacional_<?=$i ?>" min="1" max="40" class="number edadgestacional" type="text" maxlength="2" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<edad gestacional>, id:<0119>">
    </td>
    <td style="width:40px">
        <input name="consulta[var_0120][]" id="cons_peso_<?=$i ?>" type="text" maxlength="4" tabindex="<?=$idx++ ?>" class="number comacons checkvalidacion pesoembarazo" data-tabla="PESOFET" title="variable: <Consulta <?=$i ?>>, valor:<peso>, id:<0120>" style="letter-spacing:2px;">
    </td>
    <td style="width:70px" >
        <input name="consulta[var_0121][]" id="cons_pa_sistolica_<?=$i ?>" min="90" max="135" type="text" maxlength="3" class="number noreq presionarterial" title="variable: <Consulta <?=$i ?>>, valor:<PA Sist&oacute;lica>, id:<0121>"
               tabindex="<?=$idx++ ?>" style="width:50%;float:left;">
        <input name="consulta[var_0394][]" id="cons_pa_diastolica_<?=$i ?>" min="55" max="95" type="text" maxlength="3" class="number noreq presionarterial" title="variable: <Consulta <?=$i ?>>, valor:<PA Diast&oacute;lica>, id:<0394>"
               tabindex="<?=$idx++ ?>" style="width:50%; float:right;">
    </td>
    <td style="width:45px">
        <input name="consulta[var_0122][]" id="cons_alturauterina_<?=$i ?>" type="text" maxlength="2" class="number checkvalidacion" data-tabla="ALTUTER" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<altura uterina>, id:<0122>" >
    </td>
    <td style="width:50px"><select name="consulta[var_0123][]" id="cons_presentacion_<?=$i ?>" style="text-transform:none; " tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<presentaci&oacute;n>, id:<0123>" >
            <option value="">&nbsp;</option>
            <option value="CEF">CEF - Cefálica</option>
            <option value="POD">POD - Podálica</option>
            <option value="TRA">TRA - Transversa</option>
            <option value="OBL">OBL - Oblicua</option>
            <option value="IND">IND - Indiferente</option>
        </select>
    <td style="width:40px">
        <input name="consulta[var_0124][]" id="cons_fcf" class="aligncenter number" type="text" maxlength="3" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<FCF>, id:<0124>" >
    </td>
    <td style="width:40px">
        <input name="consulta[var_0125][]" id="cons_movimientos_fetales_<?=$i ?>" class="aligncenter" type="text" maxlength="1" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<movimientos fetales>, id:<0125>" >
    </td>
    <td style="width:35px">
        <input name="consulta[var_0393][]" id="cons_proteinuria_<?=$i ?>" class="aligncenter" type="text" maxlength="1" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<proteinuria>, id:<0393>" >
    </td>
    <td style="width:200px">
        <input name="consulta[var_0126][]" id="cons_signos_examenes_tratamientos_<?=$i ?>" type="text" maxlength="100" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<signos de alarma>, id:<0126>" >
    </td>
    <td style="width:50px">
        <input name="consulta[var_0127][]" id="cons_iniciales_tecnicos_<?=$i ?>" type="text" maxlength="6" class="aligncenter" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<t&eacute;cnico>, id:<0127>" >
    </td>
    <td style="width:80px"> 
        <input name="consulta[fecha_proxima][]" id="cons_proxima_cita_<?=$i ?>" class="datepicker" tabindex="<?=$idx++ ?>" title="variable: <Consulta <?=$i ?>>, valor:<fecha>, id:<0128-0129>">
    </td>
    <td style="border-right: 1px solid #666; width:20px"> 
        <button type="button" class="delconsulta" title="Eliminar consulta" ><img src="<?php echo "../../imagenes/salir2.gif" ?>" ></button>
    </td>
</tr>   