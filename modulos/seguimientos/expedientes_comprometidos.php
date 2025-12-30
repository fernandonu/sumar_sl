<?
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

//codigos de prestaciones NO facturados por el centro

$sql_prestaciones="SELECT 
  id_egreso,monto_egre_comp,fecha_egre_comp,comentario
FROM
  contabilidad.egreso  
  left join facturacion.servicio using (id_servicio) 
  left join contabilidad.inciso using (id_inciso) 
  where cuie='$cuie' and monto_egre_comp <> 0 and monto_egreso = 0 --and fecha_egre_comp between '$fecha_desde' and '$fecha_hasta'
  order by id_egreso DESC";

$sql_pres=sql($sql_prestaciones,"No se pudo abrir la base de datos de prestaciones no facturadas por el centro");

echo $html_header;
?>




<link rel="stylesheet" type="text/css" href="<?php echo $html_root?>/modulos/seguimientos/css/jquery.dataTables.css" />
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function() {
    $('#prestacion').DataTable( {
        
        "columnDefs": [
          { "width": "10%" },
          { "width": "20%" },
          { "width": "20%" },
          { "width": "50%" }
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
      <td colspan=10><font size=3 color= red> <b>Expedientes Comprometidos</b></font>
      </td>
       
  <tr><td><table id="prestacion" width=95% align="center" class="table table-striped" style="border:thin groove">
        <thead>
            <tr>
                <th>N° Expediente</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Descripcion</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>N° Expediente</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Descripcion</th>
            </tr>
        </tfoot>
 
        <tbody>
            <? $sql_pres->MoveFirst();
          while (!$sql_pres->EOF)
          {$color=$sql_pres->fields['color'];?>
          <tr <?echo $color?>>
          <td align="left"> <?=$sql_pres->fields['id_egreso']?></td>
          <td align="left"> <?=number_format($sql_pres->fields['monto_egre_comp'],2,',','.')?></td>
          <td align="left"> <?=$sql_pres->fields['fecha_egre_comp']?></td>
          <td align="left"> <?=$sql_pres->fields['comentario']?></td>
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