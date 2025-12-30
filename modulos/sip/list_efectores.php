<?php
$tipo = $_POST['id'];
// efectores    
require_once("../../config.php");
require_once("../../clases/Smiefectores.php");
$efectoresSmi = new SmiefectoresColeccion();
if( isset( $_POST['all'] ) ){
    $efectores = $efectoresSmi->buscarTodos();
}else{
    $efectores = $efectoresSmi->traeCuiesPorUsuario($_ses_user["id"]);
}
$efector = Null;
// $codHosp = array("HOS","HOS1","HOS2","HOS3");
foreach ($efectores as $key => $value) {
    if( isset( $_POST['all'] ) ){
        $efector = $efectoresSmi->buscarPorCUIE($value['cuie']);
    }else{
        $efector = $efectoresSmi->buscarPorCUIE($value);
    }
    if ($efector != false) {
        if( $tipo=='lugar_parto_aborto' ){
            // if( in_array($efector->getTipoEfector(), $codHosp) ){
              $listEfectores[] = array(
                "cuie" => $efector->getCuie(),
                "nombre" => $efector->getNombreefector(),
                );  
            // }      
        }else{
            $listEfectores[] = array(
                "cuie" => $efector->getCuie(),
                "nombre" => $efector->getNombreefector(),
            );
        }        
    }
}
$output = '<table id="efectoresList">';
$output .= '<thead>
            <tr>
                <th>CUIE</th>
                <th>NOMBRE</th>
            </tr>
        </thead>';
foreach ($listEfectores as $ef) {
    $output .= '<tr>';
    $output .= '<td class="title3"><a href="javascript:void(0);" class="select_efector" onclick="setValue('."'".$ef['cuie']."'".')">'.$ef['cuie'].'</a></td>';
    $output .= '<td class="title3">'.  strtolower( $ef['nombre'] ) .'</td>';
    $output .= '</tr>';
}
$output .= '</table>';
$output .= '<input type="hidden" id="inputId" value=""/>';
echo $output;
?>
<script type="text/javascript">
function setValue(codigo){
    $("#inputId").val(codigo);
    $("#dialog_efectores").dialog('close');
};    
$(document).ready( function () { 
    $('#efectoresList').dataTable({
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