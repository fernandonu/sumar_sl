<?php
echo $html_header;
require_once("../../config.php");
require_once ("clases/LotesProceso.php"); 
require_once("../../clases/Smiefectores.php");
//Lista de efectores tipo Hospital
$efectoresSmi = new SmiefectoresColeccion();
$efectores = $efectoresSmi->traeCuiesPorUsuario($_ses_user["id"]);
$codHosp = array("HOS", "HOS1", "HOS2", "HOS3");
foreach ($efectores as $key => $value) {
    $efector = $efectoresSmi->buscarPorCUIE($value);
    if (in_array($efector->getTipoEfector(), $codHosp)) {
        $listEfectores[] = array(
            "cuie" => $efector->getCuie(),
            "nombre" => utf8_encode($efector->getNombreefector()),
        );
    }
}

$lotesProceso = new LotesProceso();
$lotes = $lotesProceso->getLotes();
?>
<script type='text/javascript' src='../../lib/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='../../lib/jquery/jquery-ui.js'></script>
<script type='text/javascript' src='../../lib/jquery/ui/jquery.ui.datepicker-es.js'></script>
<link rel='stylesheet' href='../../lib/jquery/ui/jquery-ui.css'/>
<link rel="stylesheet" type="text/css" href="../../lib/css/sprites.css">
<link rel="stylesheet" type="text/css" href="../../lib/css/general.css">
<style>
    td{
        text-align: center !important; 
        font-size: 10px!important; 
    }
    #btnProcesar {
        border: 1px solid #ebebeb;
        border-radius: 10px;
        cursor: pointer;
        margin: 10px;
        padding: 8px;
      }
    #btnProcesar:hover{
        box-shadow: 0 0 5px powderblue;
    }
    .datepicker{
        width: 110px;
    }
    .ui-datepicker-calendar {
    display: none;
    }
</style>
<div class="contenido" id="contenido0" >
    <h3 align="center" class="titulo_pagina">Exportaci&oacute;n de Fichas SIP</h3>
    <h3 align="center">Periodo de proceso:</h3>
    <div align="center">
        <label>Desde:</label>
        <input class="datepicker" name="desde" id="desde" value="">
        <br>
        <br>
        <label>Hasta:</label>
        <input class="datepicker" name="hasta" id="hasta" value="">
    </div>
    <br>
    <div align="center">
        <label>Efectores:</label>
        <select name="efector" id="efector" >
            <option val="" >- Todos -</option>
            <?php foreach ($listEfectores as $ef) {
                echo '<option val="'.$ef['cuie'].'" > '.$ef['cuie'].' - '.$ef['nombre'].' </option> ';
            } ?>
        </select>
    </div>
    <div style="width: 100% ; margin-bottom: 2%; margin-top: 2%" align="center">
        <a id="btnProcesar" style=""> PROCESAR LOTE</a>
    </div>
    <div id="loading" style="width: 100% ; margin-bottom: 2%; margin-top: 2%" align="center"></div>
    <h3 align="center">Lotes Procesados</h3>
    <div id="procesados">
        <table id="lista_fichas" class="tablagenerica" style="width:60%">
            <thead>
                <tr id="encabezados">
                    <th>#</th>
                    <th>Cuie</th>
                    <th>Periodo</th>
                    <th>Nuevas</th>
                    <th>Reprocesadas</th>
                    <th>Total</th>
                    <th>Fecha de Proceso</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if( count($lotes) >0){
                    $cantLotes=1;
                    foreach ($lotes as $key => $lote) {
                        ?>
                <tr class="fila_con">
                    <td><?=$cantLotes?></td>
                    <td><b><?= $lote->getCuieTxt() ?></b></td>
                    <td><b><?=$lote->getPeriodoDesdeTxt().' &nbsp;-&nbsp; '.$lote->getPeriodoHastaTxt() ?></b></td>
                    <td><?=$lote->getFichasNuevas() ?></td>
                    <td><?=$lote->getFichasReprocesadas() ?></td>
                    <td><b><?=$lote->getFichasTotal() ?></b></td>
                    <td><?=Fecha($lote->getFechaProceso()) ?></td>
                    <td>
                        <!--<a class="sprite-gral icon-download" href="encode_link(../../lib/ver_archivo.php", $path_envio_zip).'" target="_blank" title="Descargar"></a>-->
                    </td>
                </tr>
                <?php 
                    $cantLotes++;
                    } 
                    }?>
            </tbody>
        </table>
    </div>
    
</div>

<script>
$(document).ready(function() {
    $("#desde,#hasta").datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'MM yy',
    maxDate: "+0m +0w", 
    onClose: function() {
        var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
    },
    beforeShow: function() {
        if ((selDate = $(this).val()).length > 0)
        {
            iYear = selDate.substring(selDate.length - 4, selDate.length);   
            iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5),
            $(this).datepicker('option', 'monthNames'));
            $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
            $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
        }
    }
    });

    $("#desde").datepicker().datepicker("setDate", new Date(new Date().getFullYear(), 0, 1));
    $("#hasta").datepicker().datepicker("setDate", new Date());
    $('#btnProcesar').on('click',function(){
        if(confirm('Confirma el proceso del lote indicado?')){
            desde = $('#desde').val();
            hasta = $('#hasta').val();
            efector = $('#efector option:selected').attr('val');
            if( desde=='' || hasta=='' ){
                alert('Debe ingresar el periodo de exportacion');
                return false;
            }
            $('#loading').empty().append('<img src="imagenes/loading.gif" width="25px" height="25px"/>');
            var dataString='desde=' + desde + '&hasta=' + hasta + '&efector=' + efector;
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "procesar_lote.php",
                data: dataString,

                success: function(data){
                    
                    $('#loading').empty();
                    var dat = data.toString();
                    cod = dat.slice(0,1);
                    id = dat.slice(1);
                    switch(cod){
                        case '0':
                            alert('No hay fichas para procesar con el criterio seleccionado.');
                        break;
                        case '1':
                            alert('Error en el Proceso: INSERT INTO nivel_01 HCPerinatal ' + dat);
                        break;
                        case '2':
                            alert('Error en el Proceso: INSERT INTO nivel_05 HCPerinatal ' + dat);
                        break;
                        case '3':
                            alert('Error en el Proceso: INSERT INTO nivel_02 consultasPrenatales ' + dat);
                        break;
                        case '4':
                            alert('Error en el Proceso: INSERT INTO nivel_03 controlesParto ' + dat);
                        break;
                        case '5':
                            alert('Error en el Proceso: INSERT INTO nivel_04 controlesPuerperio ' + dat);
                        break;
                        case '6':
                            alert('Error en el Proceso: INSERT INTO nivel_06 variablesLibres ' + dat);
                        break;
                        default:
                            window.open(data.url, '_blank');
                            $("#lista_fichas tbody tr:first td:last").html('');
                            $("#lista_fichas").prepend(data.tr);
                    }        
                }
            });

        }
    });   
});

</script>