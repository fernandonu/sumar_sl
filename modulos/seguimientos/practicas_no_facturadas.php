<?
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

//codigos de prestaciones NO facturados por el centro
$sql_id_nom="SELECT * from nacer.efe_conv where cuie='$cuie'";
$res_id_nom=sql ($sql_id_nom) or fin_pagina();
$id_nomenclador_detalle=$res_id_nom->fields['id_nomenclador_detalle'];


$sql_prestaciones="SELECT codigo,nomenclador.grupo,
especialidades.descripcion,
especialidad,especialidades.grupo as grupo_nomenclador,color from (
select * from (
select id_nomenclador from facturacion.nomenclador where id_nomenclador_detalle=$id_nomenclador_detalle order by id_nomenclador
) as nomenclador
except 

select id_nomenclador from (
select * from facturacion.comprobante where cuie='$cuie'  and fecha_comprobante between '$fecha_desde' and '$fecha_hasta') as comprobantes
inner join facturacion.prestacion using (id_comprobante) 
group by (id_nomenclador) order by id_nomenclador
) as codigos_no_facturados
inner join facturacion.nomenclador using (id_nomenclador) 
inner join nomenclador.especialidades on especialidades.descripcion=nomenclador.descripcion
order by 5,4,2,1";

$sql_pres=sql($sql_prestaciones,"No se pudo abrir la base de datos de prestaciones no facturadas por el centro");

echo $html_header;
?>




<link rel="stylesheet" type="text/css" href="<?php echo $html_root?>/modulos/seguimientos/css/jquery.dataTables.css" />
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function() {
    $('#prestacion').DataTable( {
        
        "columnDefs": [
          { "width": "5%" },
          { "width": "5%" },
          { "width": "50%" },
          { "width": "20%" },
          { "width": "20%" }
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

<form name='form1' action='practicas_no_facturadas.php' method='POST'>
<table width="100%" border="1px"><tr align="center"><td>

<table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">    
      <tr align="center" id="sub_tabla">
      <td colspan=10><font size=3 color= red> <b>Practicas NO Facturadas</b></font>
      </td>

  <td><tr><table width="70%" cellspacing=0 border=2 bordercolor=#E0E0E0 align="center" class="bordes">    
      <tr align="center" id="sub_tabla">
      <td colspan=10><font size=2 color="blue"> <b>Colores de Referencia</b></font></td></tr>
      <tr>
      <td width=30 bgcolor='#81F893' bordercolor='#000000' height=30>&nbsp;</td>
        <td bordercolor='#FFFFFF'>Prestaciones de Cobertura Efectiva Basica (CEB)</td>
               
      <td width=30 bgcolor='#F7FA7E' bordercolor='#000000' height=30>&nbsp;</td>
        <td bordercolor='#FFFFFF'>Prestaciones con CEB y Cobertura Sanitaria Integral</td>
        </tr>
        <tr>

    </table></tr></td>
       
  <tr><td><table id="prestacion" width=95% align="center" class="table table-striped" style="border:thin groove">
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Grupo</th>
                <th>Descripcion</th>
                <th>Especialidad</th>
                <th>Grupo Etareo</th>
                
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>Codigo</th>
                <th>Grupo</th>
                <th>Descripcion</th>
                <th>Especialidad</th>
                <th>Grupo Etareo</th>
                
            </tr>
        </tfoot>
 
        <tbody>
            <? $sql_pres->MoveFirst();
          while (!$sql_pres->EOF)
          {$color=$sql_pres->fields['color'];?>
          <tr <?echo $color?>>
          <td align="center"> <?=$sql_pres->fields['codigo']?></td>
          <td align="left"> <?=$sql_pres->fields['grupo']?></td>
          <td align="left"> <?=$sql_pres->fields['descripcion']?></td>
          <td align="left"> <?=$sql_pres->fields['especialidad']?></td>
          <td align="left"> <?=$sql_pres->fields['grupo_nomenclador']?></td>
          </tr>
          <?$sql_pres->MoveNext();
          }?>
            
        </tbody>
    </table>
 </td></tr></table>

 <tr><td><table width=90% align="center" class="bordes">
  <tr align="center"><td>
  <button type="button" id="cerrar" class="btn btn-default btn" onclick="window.close();">Cerrar</button>    
  </td></tr>
 </table></td></tr>


</td></tr></table>
</form>


 <?=fin_pagina();// aca termino ?>