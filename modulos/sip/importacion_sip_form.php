<?php
echo $html_header;
?>
<!--<link rel='stylesheet' href='../../lib/jquery/ui/jquery-ui.css'/>-->
<script src='../../lib/jquery.min.js' type='text/javascript'></script>
<!--<script src='../../lib/jquery/jquery-ui.js' type='text/javascript'></script>-->

<link rel="stylesheet" type="text/css" href="../../lib/css/sprites.css">
<link rel="stylesheet" type="text/css" href="../../lib/css/general.css">
<style>
    .contenido{
        border: 1px solid #ebebeb;
    }
</style>
<form name='importacion_archivo' action='importacion_sip.php' method="post" accept-charset=utf-8 enctype='multipart/form-data' id="recepcion_archivo">
    <div class="contenido" id="contenido0" >
        <h3 align="center" class="titulo_pagina">Importar Base Sip-Clap</h3>
        <div style="width: 100% ; margin-bottom: 2%; margin-top: 2%" align="center">
            <input align="center" size="59" type="file" name="archivo"/>
            <input class="enviar" type="submit" name="enviar" value="Enviar" size="160px"/>
        </div>
    </div>
</form>