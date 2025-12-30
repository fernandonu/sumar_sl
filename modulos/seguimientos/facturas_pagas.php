<?
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

//facturas pagadas
$sql_fac="SELECT id_factura,fecha_ing,periodo,monto from expediente.expediente where estado='C' 
and id_efe_conv=(select id_efe_conv from nacer.efe_conv where cuie='$cuie' limit 1) order by fecha_ing";
$sql_fact=sql($sql_fac,"No se Puede abrir la base de datos de facturas");
//end facturas pagadas

echo $html_header;
?>




<link rel="stylesheet" type="text/css" href="<?php echo $html_root?>/modulos/seguimientos/css/jquery.dataTables.css" />
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function() {
    $('#prestacion').DataTable( {
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    } );
} );
</script>

<form name='form1' action='facturas_pagas.php' method='POST'>
<table width="100%" border="1px"><tr align="center"><td>

<table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">    
      <tr align="center" id="sub_tabla">
      <td colspan=10><font size=3 color= red> <b>Facturas Pagas</b></font>
      </td>
       
  <tr><td><table id="prestacion" width=90% align="center" class="table table-striped" style="border:thin groove">

        <thead>
            <tr>
                <th>N° Factura</th>
                <th>Fecha de Ingreso</th>
                <th>Periodo</th>
                <th>Saldo</th>
                            
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>N° Factura</th>
                <th>Fecha de Ingreso</th>
                <th>Periodo</th>
                <th>Saldo</th>
                
            </tr>
        </tfoot>
 
        <tbody>
            <? $sql_fact->MoveFirst();        
          while (!$sql_fact->EOF)
          {?>
          <tr>
          <td align="center"> <?=$sql_fact->fields['id_factura']?></td>
          <td align="center"> <?=$sql_fact->fields['fecha_ing']?></td>
          <td align="center"> <?=$sql_fact->fields['periodo']?></td>
          <td align="center"> <?=$sql_fact->fields['monto']?></td>
          </tr>
          <?$sql_fact->MoveNext();
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