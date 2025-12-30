<?
require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
$dia_hoy = date("Y-m-d");

if ($id_efector) {
$sql_efector="SELECT * from nacer.efe_conv where id_efe_conv=$id_efector";
$res_efector=sql($sql_efector) or fin_pagina();
$cuie=$res_efector->fields['cuie'];
$nombre=utf8_decode($res_efector->fields['nombre']);
$domicilio=utf8_decode($res_efector->fields['domicilio']);
$departamento=$res_efector->fields['dpto_nombre'];
$localidad=$res_efector->fields['localidad'];
$cod_pos=$res_efector->fields['cod_pos'];
$cuidad=$res_efector->fields['cuidad'];
$referente=$res_efector->fields['referente'];
$tel=$res_efector->fields['tel'];

$nombre_archivo="Registro Diario_".$cuie."_".$dia_hoy.".xls";
excel_header($nombre_archivo);


$sql_comprobantes="SELECT comprobantes.*,
        prestaciones.*,
        remedio.*,
        beneficiarios.nombre_benef,
        beneficiarios.apellido_benef,
        beneficiarios.numero_doc,
        beneficiarios.sexo,
        beneficiarios.fecha_nacimiento_benef,
        beneficiarios.pais_nac
        from programa_sexual.comprobantes 
        inner join programa_sexual.prestaciones using (id_comprobante)
        inner join programa_sexual.remedio using (id_remedio)
        inner join uad.beneficiarios using (id_beneficiarios)
        where comprobantes.cuie='$cuie' and fecha_entrega='$dia_hoy'";
$result=sql($sql_comprobantes,"no se pudo ejecutar la consulta de comprobantes") or fin_pagina();
}
  else echo "No hay ningun efector seleccionado";?>

<form name=form1 method=post action="registro_diario.php">
<table width="95%" cellspacing=1 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
<td align="center">
<font size=+1><b>REGISTRO DIARIO</b></font> 
<BR><font color="red"><b>Fecha de corte: </b><?=$dia_hoy;?></font>
</td>
</tr>
<tr><td>
<table width=100% align="center" class="bordes">
<tr>
<td id=mo colspan="5" align="center">

</td>
</tr>
<tr>
<td>
<table align="center">
<td align="right"><b>Efector:</b></td>
<td align="left"><font color="blue"><?=$nombre?></font></td>
</tr>

<tr>
<td align="right"><b>Domicilio:</b></td>
<td align="left"><font color="blue"><?=$domicilio?></font></td>
</tr>

<tr>
<td align="right"><b>Departamento:</b></td>
<td align="left"><font color="blue"><?=$departamento?></font></td>
</tr>

<tr>
<td align="right">
<b>Localidad:</b>
</td>
<td align="left"><font color="blue"><?=$localidad?></font></td>
</tr>
</table>
</td>      

<td>
<table align="center">        
<tr>
<td align="right"><b>Codigo Postal:</b></td>
<td align="left"><font color="blue"><?=$cod_pos?></font></td>
</tr>

<tr>
<td align="right"><b>Cuidad:</b></td>
<td align="left"><font color="blue"><?=$cuidad?></font></td>
</tr>

<tr>
<td align="right"><b>Referente:</b></td>
<td align="left"><font color="blue"><?=utf8_decode($referente)?></font></td>
</tr>

<tr>
<td align="right"><b>Telefono:</b></td>
<td align="left"><font color="blue"><?=$tel?></font></td>
</tr>          
</table>
</td>  
</tr> 
</table>

   
<br>
&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;   
  <table  class="table table-striped" width=80% border="1">
  <tr>
               
    <td align="center" id="mo"><b>Fecha</b></td>            
    <td align="center" id="mo"><b>DNI</b></td>
    <td align="center" id="mo"><b>Nombre</b></td>
    <td align="center" id="mo"><b>Apellido</b></td>
    <td align="center" id="mo"><b>O.S.</b></td>
    <td align="center" id="mo"><b>Pais</b></td>
    <td align="center" id="mo"><b>Edad</b></td>
    <td align="center" id="mo"><b>Sexo</b></td>
    <td align="center" id="mo" colspan="14"><b>Entrega de M.A.C.</b></td>
    <td align="center" id="mo"><b>Test embarazo</b></td>
    <td align="center" id="mo"><b>Ingresa al Programa</b></td>
    </tr>

    <tr>
               
    <td align="center" id="mo"></td>            
    <td align="center" id="mo"></td>
    <td align="center" id="mo"></td>
    <td align="center" id="mo"></td>            
    <td align="center" id="mo"></td>
    <td align="center" id="mo"></td>
    <td align="center" id="mo"></td>            
    <td align="center" id="mo"></td>
    <td align="center" id="mo"><b>EE 0.03mg</b></td>
    <td align="center" id="mo"><b>Gestodeno</b></td>
    <td align="center" id="mo"><b>Levonorg x 35/28</b></td>
    <td align="center" id="mo"><b>Desogestrel</b></td>
    <td align="center" id="mo"><b>Norestist 50</b></td>
    <td align="center" id="mo"><b>Medroxi 150mg</b></td>
    <td align="center" id="mo"><b>Levonorg 0.75</b></td>
    <td align="center" id="mo"><b>Levonorg 1.5</b></td>
    <td align="center" id="mo"><b>DIU T</b></td>
    <td align="center" id="mo"><b>DIU M</b></td>
    <td align="center" id="mo"><b>DIU 7 mini</b></td>
    <td align="center" id="mo"><b>DIU H mini</b></td>
    <td align="center" id="mo"><b>SIU (mirena)</b></td>
    <td align="center" id="mo"><b>Implantes</b></td>
    <td align="center" id="mo"></td>
    <td align="center" id="mo"></td>
    </tr>

<?  while (!$result->EOF) {?>
    <tr>
     <td align="center"><?=$result->fields['fecha_entrega']?></td>
     <td align="center"><?=$result->fields['numero_doc']?></td>
     <td align="center"><?=$result->fields['nombre_benef']?></td>
     <td align="center"><?=$result->fields['apellido_benef']?></td>
     <td align="center"><?=$result->fields['consultar puco']?></td>
     <td align="center"><?=$result->fields['pais_nac']?></td>
     <td align="center"><?=$result->fields['calcular_edad_fecha_entrega']?></td>
     <td align="center"><?=$result->fields['sexo']?></td>
     <td align="center"><? if ($result->fields['codigo']=='001') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='002') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='003') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='004') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='005') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='006') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='007') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='008') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='009') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='010') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='011') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='012') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='013') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='017') echo $result->fields['cantidad']?></td>
     <td align="center"><? if ($result->fields['codigo']=='016') echo $result->fields['cantidad']?></td>
     <td align="center"><?=$result->fields['']?></td>
    </tr>

    <?$result->MoveNext();}?>
</table>
</form>