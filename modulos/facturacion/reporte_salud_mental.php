<?php
/*
$Author: Seba $
$Revision: 2.0 $
$Date: 2016/08/08 $
*/
require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

if ($_POST['generar_excel']=="Generar Excel"){
  $periodo=$_POST['periodo'];
  $link=encode_link("reporte_salud_mental_excel.php",array("fecha_desde"=>$_POST['fecha_desde'],"fecha_hasta"=>$_POST['fecha_hasta']));
  ?>
  <script>
  window.open('<?=$link?>')
  </script> 
<?}

if ($_POST['muestra']=="Muestra"){
  $cuie=$_POST['cuie'];
  $fecha_desde=Fecha_db($_POST['fecha_desde']);
  $fecha_hasta=Fecha_db($_POST['fecha_hasta']);
    if($cuie=='Todos'){
      $sql_tmp="SELECT 
                  prestacion.*,nomenclador.*, t1.codigo as cod_diag, t1.descripcion as desc_diag, comprobante.cuie, smiafiliados.*
                FROM facturacion.prestacion 
                LEFT JOIN facturacion.comprobante USING (id_comprobante)
                LEFT JOIN nacer.smiafiliados USING (id_smiafiliados)
                LEFT JOIN facturacion.nomenclador using (id_nomenclador) 
                LEFT JOIN (select distinct codigo,descripcion from nomenclador.patologias_frecuentes) as t1 ON (prestacion.diagnostico=t1.codigo)
                WHERE
                  (prestacion.fecha_prestacion BETWEEN '$fecha_desde' and '$fecha_hasta') AND
                  (nomenclador.codigo = 'C073' OR nomenclador.codigo = 'C098')
                ORDER BY fecha_prestacion DESC";
}else {
        $sql_tmp="SELECT 
                  prestacion.*,nomenclador.*, t1.codigo as cod_diag, t1.descripcion as desc_diag, comprobante.cuie, smiafiliados.*
                FROM facturacion.prestacion 
                LEFT JOIN facturacion.comprobante USING (id_comprobante)
                LEFT JOIN nacer.smiafiliados USING (id_smiafiliados)
                LEFT JOIN facturacion.nomenclador using (id_nomenclador) 
                LEFT JOIN (select distinct codigo,descripcion from nomenclador.patologias_frecuentes) as t1 ON (prestacion.diagnostico=t1.codigo)
                WHERE
                  (prestacion.fecha_prestacion BETWEEN '$fecha_desde' and '$fecha_hasta') AND
                  (nomenclador.codigo = 'C073' OR nomenclador.codigo = 'C098') AND
                  comprobante.cuie ='$cuie'
                ORDER BY fecha_prestacion DESC";
              
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


<form name=form1 action="reporte_salud_mental.php" method=POST>
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
               
                ?>
                <option value='Todos' <?if ("Todos"==$cuie)echo "selected"?>>Todos</option>
               <?while (!$res_efectores->EOF){ 
                $cuie1=$res_efectores->fields['cuie'];
                $nombre_efector=$res_efectores->fields['nombre'];
                ?>
                <option value='<?=$cuie1;?>' <?if ($cuie1==$cuie)echo "selected"?>><?=$cuie1." - ".$nombre_efector?></option>
                <?
                $res_efectores->movenext();
                }?>
       
      </select>
      
      <input type="submit" class="btn btn-primary" name="muestra" value='Muestra' onclick="return control_muestra()" >
      <input type="submit" class="btn btn-success" value="Generar Excel" name="generar_excel" >
        </b>     
    </td>
     </tr>
</table>

<?if ($_POST['muestra']){?>

<tr><td><table id="prestacion" width=95% align="center" class="table table-striped" style="border:thin groove">
  <thead>
    <tr>
    <th align="center">CUIE</th>
    <th align="center">DNI</th>
    <th align="center">Nombre</th>
    <th align="center">Apellido</th>
    <th align="center">Fecha Nac</th>
    <th align="center">Sexo</th>
    <th align="center">Fecha Prestacion</th>
    <th align="center">Cod Prestacion</th>
    <th align="center">Prestacion</th>    
    <th align="center">Cod Diagnostico</th>
    <th align="center">Diagnostino</th>
    <th align="center">Grupo</th>
    <th align="center">Subgrupo</th>
    </tr>
  </thead>
  
  <tfoot>
  <tr>
    <th align="center">CUIE</th>
    <th align="center">DNI</th>
    <th align="center">Nombre</th>
    <th align="center">Apellido</th>
    <th align="center">Fecha Nac</th>
    <th align="center">Sexo</th>
    <th align="center">Fecha Prestacion</th>
    <th align="center">Cod Prestacion</th>
    <th align="center">Prestacion</th>    
    <th align="center">Cod Diagnostico</th>
    <th align="center">Diagnostino</th>
    <th align="center">Grupo</th>
    <th align="center">Subgrupo</th>
    </tr>
  </tfoot>         
            
  <tbody>          
 <?while (!$res_comprobante->EOF) {?>   
  
         
  <td ><?=$res_comprobante->fields['cuie']?></td>    
  <td ><?=$res_comprobante->fields['afidni']?></td>    
  <td ><?=$res_comprobante->fields['afinombre']?></td>    
  <td ><?=$res_comprobante->fields['afiapellido']?></td>  
  <td ><?=fecha($res_comprobante->fields['afifechanac'])?></td>  
  <td><?=$res_comprobante->fields['afisexo']?></td>
  <td ><?=fecha($res_comprobante->fields['fecha_prestacion'])?></td>  
  <td><?=$res_comprobante->fields['codigo']?></td>
  <td><?=$res_comprobante->fields['descripcion']?></td>
  <td><?=$res_comprobante->fields['cod_diag']?></td>
  <td><?=$res_comprobante->fields['desc_diag']?></td>
  <td><?=$res_comprobante->fields['grupo_descriptivo']?></td>
  <td><?=$res_comprobante->fields['subgrupo']?></td>

  
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
