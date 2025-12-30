<?php
require_once("../../config.php");

$sql = "SELECT codigo,descripcion
        FROM sip_clap.auxiliares 
        WHERE tabla = '".$_POST['tabla']."' 
        ORDER BY orden";
$result = sql($sql);

$output = '<table id="auxiliaresList">';
$output .= '<thead>
            <tr>
                <th>CODIGO</th>
                <th>NOMBRE</th>
            </tr>
        </thead>';
while (!$result->EOF) {
    $output .= '<tr>';
    $output .= '<td class="title3"><a href="javascript:void(0);" class="select_efector" onclick="setValue('."'".$result->fields['codigo']."'".')">'.$result->fields['codigo'].'</a></td>';
    $output .= '<td class="title3">'.  $result->fields['descripcion'] .'</td>';
    $output .= '</tr>';
    $result->MoveNext();
}
$output .= '</table>';
$output .= '<input type="hidden" id="inputId" value=""/>';
echo $output;
?>
<script type="text/javascript">
function setValue(codigo){
    $("#inputId").val(codigo);
    $("#dialog_auxiliar").dialog('close');
};    
$(document).ready( function () { 
    $('#auxiliaresList').dataTable({
        "bSort": false,
        "bAutoWidth": false,
        "lengthChange": false,
        "pageLength": 15,
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "oPaginate": {
                "sFirst": "<<",
                "sNext": ">",
                "sLast": ">>",
                "sPrevious": "<"
            },
            "sLengthMenu": "Mostrar _MENU_",
            "sZeroRecords": "Sin datos",
            "sInfo": " _START_ / _END_  -  <strong>Total: _TOTAL_ </strong>",
            "sInfoEmpty": "Sin coincidencias",
            "sInfoFiltered": "(filtrado de _MAX_ registros)",
            "sSearch": "Buscar:"
        }
    });    
});
</script>