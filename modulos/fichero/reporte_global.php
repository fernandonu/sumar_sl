<?php
/*
$Author: Seba $
$Revision: 2.0 $
$Date: 2016/08/08 $
*/
require_once("../../config.php");
require_once("../contabilidad/funciones_edad.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

if ($_POST['generar_excel']=="Generar Excel"){
  $periodo=$_POST['periodo'];
  $link=encode_link("report_global.php",array("fecha_desde"=>$_POST['fecha_desde'],"fecha_hasta"=>$_POST['fecha_hasta']));
  ?>
  <script>
  window.open('<?=$link?>')
  </script> 
<?}

if ($_POST['muestra']=="Muestra"){
  $cuie=$_POST['cuie'];
  $fecha_desde=Fecha_db($_POST['fecha_desde']);
  $fecha_hasta=Fecha_db($_POST['fecha_hasta']);
    if($cuie!='Todos'){
      $sql_tmp="SELECT 
              leche.beneficiarios.*,
              nacer.smiafiliados.*,
              fichero.fichero.*,
              nacer.efe_conv.nombre as nom_efe
              FROM
              fichero.fichero
              LEFT OUTER JOIN nacer.smiafiliados ON fichero.fichero.id_smiafiliados = nacer.smiafiliados.id_smiafiliados
              LEFT OUTER JOIN leche.beneficiarios ON leche.beneficiarios.id_beneficiarios = fichero.fichero.id_beneficiarios
              INNER JOIN nacer.efe_conv ON nacer.efe_conv.cuie = fichero.fichero.cuie
            where (fichero.fichero.fecha_control BETWEEN '$fecha_desde' and '$fecha_hasta') 
            and (nacer.efe_conv.cuie='$cuie')
            and (embarazo='NO') 
            and (taller='NO')
            and (embarazo_riesgo='NO' or embarazo_riesgo='')
            ORDER BY fecha_control";
}else {
        $sql_tmp="SELECT 
              leche.beneficiarios.*,
              nacer.smiafiliados.*,
              fichero.fichero.*,
              nacer.efe_conv.nombre as nom_efe
                FROM
                fichero.fichero
                LEFT OUTER JOIN nacer.smiafiliados ON fichero.fichero.id_smiafiliados = nacer.smiafiliados.id_smiafiliados
                LEFT OUTER JOIN leche.beneficiarios ON leche.beneficiarios.id_beneficiarios = fichero.fichero.id_beneficiarios
                INNER JOIN nacer.efe_conv ON nacer.efe_conv.cuie = fichero.fichero.cuie
              where (fichero.fichero.fecha_control BETWEEN '$fecha_desde' and '$fecha_hasta') 
              and (embarazo='NO')
              and (taller='NO')
              and (embarazo_riesgo='NO' or embarazo_riesgo='')
              ORDER BY fecha_control";
              
      }
      
      $res_comprobante=sql($sql_tmp,"<br>Error al traer los datos<br>") or fin_pagina();


}
echo $html_header;?>
<link rel="stylesheet" type="text/css" href="<?php echo $html_root?>/modulos/contabilidad/css/jquery.dataTables.css" />
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function() {
    $('#prestacion').DataTable( {
        
        "columnDefs": [
          { "width": "2%" },
          { "width": "2%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" },
          { "width": "5%" }
        ],
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select style="width:100%"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j )      {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    } );
} );
</script>

<script>
function control_muestra()
{ 
 if(document.all.fecha_desde.value==""){
  alert('Debe Ingresar una Fecha DESDE');
  return false;
 } 
 if(document.all.fecha_hasta.value==""){
  alert('Debe Ingresar una Fecha HASTA');
  return false;
 } 
return true;
}
</script>


<form name=form1 action="reporte_global.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
    <b><?if ($fecha_desde=='')$fecha_desde=date("Y-m-d",time()-(30*24*60*60));
        if ($fecha_hasta=='')$fecha_hasta=date("Y-m-d");?>
        Desde: <input type=text id=fecha_desde name=fecha_desde value='<?=fecha($fecha_desde)?>' size=15 readonly>
        <?=link_calendario("fecha_desde");?>
    
        Hasta: <input type=text id=fecha_hasta name=fecha_hasta value='<?=fecha($fecha_hasta)?>' size=15 readonly>
        <?=link_calendario("fecha_hasta");?> 
        
        Efector: 
       <select name=cuie Style="width=257px" 
            onKeypress="buscar_combo(this);"
        onblur="borrar_buffer();"
        onchange="borrar_buffer();">
       <?$user_login1=substr($_ses_user['login'],0,6);
                  if (es_cuie($_ses_user['login'])){
                  $sql1= "select cuie, nombre, com_gestion from nacer.efe_conv where cuie='$user_login1' order by nombre";
                   }                  
                  else{
                  $usuario1=$_ses_user['id'];
                  $sql1= "select nacer.efe_conv.nombre, nacer.efe_conv.cuie, com_gestion 
                      from nacer.efe_conv 
                      join sistema.usu_efec on (nacer.efe_conv.cuie = sistema.usu_efec.cuie) 
                      join sistema.usuarios on (sistema.usu_efec.id_usuario = sistema.usuarios.id_usuario) 
                      where sistema.usuarios.id_usuario = '$usuario1'
                     order by nombre";
                   }               
                 $res_efectores=sql($sql1) or fin_pagina();
               
               while (!$res_efectores->EOF){ 
                $com_gestion=$res_efectores->fields['com_gestion'];
                $cuie1=$res_efectores->fields['cuie'];
                $nombre_efector=$res_efectores->fields['nombre'];
                if($com_gestion=='FALSO')$color_style='#F78181'; else $color_style='';
                ?>
                <option value='<?=$cuie1;?>' Style="background-color: <?=$color_style;?>" <?if ($cuie1==$cuie)echo "selected"?>><?=$cuie1." - ".$nombre_efector?></option>
                <?
                $res_efectores->movenext();
                }?>
        <option value='Todos' <?if ("Todos"==$cuie)echo "selected"?>>Todos</option>
      </select>
      
      <input type="submit" class="btn btn-primary" name="muestra" value='Muestra' onclick="return control_muestra()" >
      <input type="submit" class="btn btn-success" value="Generar Excel" name="generar_excel" disabled>
        </b>     
    </td>
     </tr>
</table>

<?if ($_POST['muestra']){?>

<tr><td><table id="prestacion" width=95% align="center" class="table table-striped" style="border:thin groove">
  <thead>
    <tr>
    <th align="center">Efector</th>
    <th align="center">DNI</th>
    <th align="center">Nombre</th>
    <th align="center">Apellido</th>
    <th align="center">Fecha Nac</th>
    <th align="center">Sexo</th>
    <th align="center">Edad</th>
    <th align="center">Domicilio</th>
    <th align="center">Fecha Control</th>
    <th align="center">Peso</th>
    <th align="center">Talla</th>
    <th align="center">IMC</th>
    <th align="center">Per. Cef.</th>
    <th align="center">Perc Peso/Edad</th>
    <th align="center">Perc Talla/Edad</th>
    <th align="center">Perc Perim. Cefalico/Edad</th>
    <th align="center">Perc IMC/Edad</th>
    </tr>
  </thead>
  
  <tfoot>
  <tr>
  <th align="center">Efector</th>
  <th align="center">DNI</th>
  <th align="center">Nombre</th>
  <th align="center">Apellido</th>
  <th align="center">Fecha Nac</th>
  <th align="center">Sexo</th>
  <th align="center">Edad</th>
  <th align="center">Domicilio</th>
  <th align="center">Fecha Control</th>
  <th align="center">Peso</th>
  <th align="center">Talla</th>
  <th align="center">IMC</th>
  <th align="center">Per. Cef.</th>
  <th align="center">Perc Peso/Edad</th>
  <th align="center">Perc Talla/Edad</th>
  <th align="center">Perc Perim. Cefalico/Edad</th>
  <th align="center">Perc IMC/Edad</th> 
  </tr>
  </tfoot>         
            
  <tbody>          
 <?while (!$res_comprobante->EOF) {
  $fecha_nac=($res_comprobante->fields['afifechanac']!='')?$res_comprobante->fields['afifechanac']:$res_comprobante->fields['fecha_nac'];
  $fecha_control=$res_comprobante->fields['fecha_control'];
  $edad=edad_con_meses($fecha_nac,$fecha_control);
  $anios=$edad['anos'];
  $sexo=($res_comprobante->fields['afisexo']!='')?trim($res_comprobante->fields['afisexo']):trim($res_comprobante->fields['sexo']);
  ?>   
  
         
  <td ><?=$res_comprobante->fields['nom_efe']?></td>    
  <td ><?=($res_comprobante->fields['afidni']!='')?$res_comprobante->fields['afidni']:$res_comprobante->fields['documento'];?></td>    
  <td ><?=($res_comprobante->fields['afinombre']!='')?$res_comprobante->fields['afinombre']:$res_comprobante->fields['nombre'];?></td>    
  <td ><?=($res_comprobante->fields['afiapellido']!='')?$res_comprobante->fields['afiapellido']:$res_comprobante->fields['apellido'];?></td>  
  <td ><?=($res_comprobante->fields['afifechanac']!='')?fecha($res_comprobante->fields['afifechanac']):fecha($res_comprobante->fields['fecha_nac']);?></td>  
  <td><?=$sexo?></td>
  <td><?=$anios?></td>
  <td ><?=($res_comprobante->fields['afidomlocalidad']!='')?$res_comprobante->fields['afidomlocalidad']:$res_comprobante->fields['domicilio'];?></td>  
    <td ><?=fecha($res_comprobante->fields['fecha_control'])?></td>  
    <td ><?=number_format($res_comprobante->fields['peso'],2,',',0)?></td>    
    <td ><?=number_format($res_comprobante->fields['talla'],2,',',0)?></td>    
    <td ><?if ($res_comprobante->fields['imc']!='NaN') echo number_format($res_comprobante->fields['imc'],2,',',0);
    else echo '0,00';?></td>    
  <td ><?=number_format($res_comprobante->fields['perim_cefalico'],2,',',0)?></td>
     
  <?switch ($res_comprobante->fields['percen_peso_edad']) {
              case 1: $percentilo_peso_edad="<3";break;
              case 2: $percentilo_peso_edad="3-10";break;
              case 3: $percentilo_peso_edad=">10-90";break;
              case 4: $percentilo_peso_edad=">90-97";break;
              case 5: $percentilo_peso_edad=">97";break;
              default: $percentilo_peso_edad="Datos sin Ingresar";
            };
        
        switch ($res_comprobante->fields['percen_talla_edad']) {
        case 1:$percentilo_talla_edad=">-3";break;
        case 2:$percentilo_talla_edad=">3-97";break;
        case 3:$percentilo_talla_edad=">+97";break;
        default: $percentilo_talla_edad="Datos sin Ingresar";break;
        };

      switch ($res_comprobante->fields['percen_perim_cefali_edad']) {
        case 1:$percentilo_perim_cefalico_edad=">-3";break;
        case 2:$percentilo_perim_cefalico_edad=">3-97";break;
        case 3:$percentilo_perim_cefalico_edad=">+97";break;
        default: $percentilo_perim_cefalico_edad="Datos sin Ingresar";break;
      };

      switch ($res_comprobante->fields['percen_peso_talla']) {
        case 1:$percentilo_peso_talla="<3";break;
        case 2:$percentilo_peso_talla="(3-10)";break;
        case 3:$percentilo_peso_talla=">10-85";break;
        case 4:$percentilo_peso_talla=">85-97";break;
        case 5:$percentilo_peso_talla=">97";break;
        default: $percentilo_peso_talla="Datos sin Ingresar";break;
      };

      switch ($res_comprobante->fields['percen_imc_edad']) {
        case 1:$percentilo_imc_edad="<3";break;
        case 2:$percentilo_imc_edad="(3-10)";break;
        case 3:$percentilo_imc_edad=" >10-85";break;
        case 4:$percentilo_imc_edad=">85-97";break;
        case 5:$percentilo_imc_edad=" >97";break;
        default: $percentilo_imc_edad="Dato Sin Ingresar";break;
      }

      ?> 

     <td><?=$percentilo_peso_edad;?></td>
       <td><?=$percentilo_talla_edad;?></td>  
       <td><?=$percentilo_perim_cefalico_edad;?></td>      
         <td ><?=$percentilo_imc_edad?></td>
   </tr>
  <?$res_comprobante->MoveNext();
   }?>
    
 </tbody>   
</table>
<?}?>
<br>
  
</td>
</table>

</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
