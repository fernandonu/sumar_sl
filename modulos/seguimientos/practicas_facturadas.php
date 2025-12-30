<?
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

//codigos de prestaciones facturados por el centro
$sql_nom_detalle="SELECT * from facturacion.nomenclador_detalle 
          where modo_facturacion='4'
          and '$fecha_desde' between fecha_desde and fecha_hasta ";
$res_nom_detalle=sql($sql_nom_detalle) or fin_pagina();
$id_nomenclador_detalle=$res_nom_detalle->fields['id_nomenclador_detalle'];


$sql_codigos="SELECT prestacion.id_nomenclador,
    nomenclador.codigo,
    nomenclador.descripcion as descripcion_2,
    nomenclador.grupo,
    nomenclador.subgrupo,
    nomenclador.color,
    count (prestacion.id_nomenclador) as cantidad 

  from facturacion.prestacion
  inner join facturacion.comprobante using (id_comprobante)
  inner join facturacion.nomenclador using (id_nomenclador)
  where comprobante.cuie='$cuie' 
  and prestacion.fecha_prestacion between '$fecha_desde' and '$fecha_hasta'
  and nomenclador.id_nomenclador_detalle=$id_nomenclador_detalle
  group by prestacion.id_nomenclador,nomenclador.codigo,nomenclador.descripcion,nomenclador.grupo,nomenclador.subgrupo,nomenclador.color
  order by nomenclador.codigo";
$sql_pres=sql($sql_codigos,"No se puede abrir la base de datos de Codigos Facturados");

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

<form name='form1' action='practicas_facturadas.php' method='POST'>
<table width="100%" border="1px"><tr align="center"><td>

<table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">    
      <tr align="center" id="sub_tabla">
      <td colspan=10><font size=3 color= red> <b>Practicas Facturadas</b></font>
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
                <th>Subgrupo</th>
                <th>Descripcion</th>
                <th>Cantidad</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>Codigo</th>
                <th>Grupo</th>
                <th>Subgrupo</th>
                <th>Descripcion</th>
                <th>Cantidad</th>
            </tr>
        </tfoot>
 
        <tbody>
            <? $sql_pres->MoveFirst();
          while (!$sql_pres->EOF)
          {$color=$sql_pres->fields['color'];?>
          <tr <?echo $color?>>
          <td align="center"> <?=$sql_pres->fields['codigo']?></td>
          <td align="left"> <?=$sql_pres->fields['grupo']?></td>
          <td align="left"> <?=$sql_pres->fields['subgrupo']?></td>
          <td align="left"> <?=$sql_pres->fields['descripcion_2']?></td>
          <td align="left"> <?=$sql_pres->fields['cantidad']?></td>
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