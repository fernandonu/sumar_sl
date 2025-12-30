<?php
require_once("../../config.php");

/*function cargar_prescripciones($id_turno) {
  $ret = '';
  $query = "SELECT 
            nacer.prescripciones.id,
            nacer.prescripciones.dosis,
            nacer.prescripciones.presentacion,
            nacer.prescripciones.frecuencia,
            nacer.prescripciones.observaciones,
            nacer.vademecum.nombre_generico,
            nacer.vademecum.accion_terapeutica
          FROM
            nacer.prescripciones
            LEFT OUTER JOIN nacer.vademecum ON (nacer.prescripciones.id_vademecum = nacer.vademecum.id)
          WHERE  nacer.prescripciones.id_turno = {$id_turno} 
          ORDER BY nacer.prescripciones.fecha
          ";

  $res_prescripciones = sql($query, "al obtener los datos de las Prescripciones") or die();

  if ($res_prescripciones !== false && $res_prescripciones->recordCount() > 0) {
    while (!$res_prescripciones->EOF) {
      $link_borrar = encode_link("cargar_diagnostico_datos.php", array("accion" => "eliminar_prescripcion", "id_turno" => $id_turno, "id_prescripcion" => $res_prescripciones->fields['id']));
      echo '<tr>';
      echo '<td>', $res_prescripciones->fields['nombre_generico'], '</td>';
      echo '<td>', $res_prescripciones->fields['dosis'], '</td>';
      echo '<td>', $res_prescripciones->fields['presentacion'], '</td>';
      echo '<td>', $res_prescripciones->fields['frecuencia'], '</td>';
      echo '<td>', $res_prescripciones->fields['observaciones'], '</td>';
      echo '<td class="text-center"><a data-href="',$link_borrar,'" data-toggle="modal" data-target="#confirm-delete" href="#"><span class="glyphicon glyphicon-minus-sign text-danger" aria-hidden="true" title="Eliminar"></span></a></td>';
      echo '<tr>';
      $res_prescripciones->MoveNext();
    }
  }
  else {
    $ret .= '<tr><td colspan="6" align="center" class="danger"><strong>No hay datos</strong></td></tr>';
  }
  return $ret;
}
*/

if (isset($parametros["accion"])) {
  switch ($parametros["accion"]) {
    case 'cie10_autocomplete':
      $buscar = $_GET["term"];
      $res_json = array();
      $query = "SELECT 
                  *
                FROM
                  nacer.cie10
                WHERE
                  id10 ILIKE ".$db->Quote($buscar)." OR
                  dec10 ILIKE ".$db->Quote("%".$buscar."%")."
                  ";
      // echo $query;
      $res_cie10 = sql($query, "al obtener los datos del nomenclador") or die();
      while (!$res_cie10->EOF) {
        $res_json[] = array(
          "id"            => $res_cie10->fields["id10"],
          "codigo"        => $res_cie10->fields["id10"],
          "value"         => $res_cie10->fields["id10"]." ".$res_cie10->fields["dec10"],
          "label"         => $res_cie10->fields["id10"]." ".$res_cie10->fields["dec10"]
        );
        $res_cie10->MoveNext();
      }
      echo json_encode($res_json);
      break;

    case 'cepsap_autocomplete':
      $buscar = $_GET["term"];
      $res_json = array();
      $query = "SELECT 
                  *
                FROM
                  nacer.cepsap_items_2016
                WHERE
                  codigo ILIKE ".$db->Quote($buscar)." OR
                  descripcion ILIKE ".$db->Quote("%".$buscar."%")."
                  ";
      // echo $query;
      $res_cepsap = sql($query, "al obtener los datos del nomenclador") or die();
      while (!$res_cepsap->EOF) {
        $res_json[] = array(
          "id"            => $res_cepsap->fields["id"],
          "codigo"        => $res_cepsap->fields["codigo"],
          "value"         => $res_cepsap->fields["codigo"]." ".$res_cepsap->fields["descripcion"],
          "label"         => $res_cepsap->fields["codigo"]." ".$res_cepsap->fields["descripcion"]
        );
        $res_cepsap->MoveNext();
      }
      echo json_encode($res_json);
      break;

    case 'nombre_generico_autocomplete':
      $buscar = $_GET["term"];
      $res_json = array();
      $query = "SELECT 
                  *
                FROM
                  nacer.vademecum
                WHERE
                  nombre_generico ILIKE ".$db->Quote("%".$buscar."%")."
                  ";
      // echo $query;
      $res_vademecum = sql($query, "al obtener los datos del vademecum") or die();
      while (!$res_vademecum->EOF) {
        $res_json[] = array(
          "id"            => $res_vademecum->fields["id"],
          "value"         => $res_vademecum->fields["nombre_generico"],
          "label"         => $res_vademecum->fields["nombre_generico"]
        );
        $res_vademecum->MoveNext();
      }
      echo json_encode($res_json);
      break;
/*    case 'guardar_prescripcion':
      $ret = '';
      $id_turno = intval($_POST["id_turno"]);
      $id_vademecum = intval($_POST["id_vademecum"]);
      $dosis = $_POST["dosis"];
      $presentacion = $_POST["presentacion"];
      $frecuencia = $_POST["frecuencia"];
      $observaciones = $_POST["observaciones"];

      if ($id_turno > 0 && $id_vademecum > 0) {
        $query = "INSERT INTO nacer.prescripciones (id_turno, id_vademecum, dosis, presentacion, frecuencia, observaciones) VALUES (";
        $query .= $id_turno.", ".$id_vademecum.", ".$db->Quote($dosis).", ".$db->Quote($presentacion).", ".$db->Quote($frecuencia).", ".$db->Quote($observaciones).")";
        $res_insert = sql($query);
        if ($res_insert === false) {
          $ret .= '<tr><td colspan="6" align="center" class="danger"><strong>Ocurrio un error al guardar los datos de la Prescripci&oacute;n</strong></td></tr>';
        }
        $ret .= cargar_prescripciones($id_turno);
      }
      echo $ret;
      break;
    case 'eliminar_prescripcion':
      $ret = '';
      $id_turno = intval($parametros["id_turno"]);
      $id_prescripcion = intval($parametros["id_prescripcion"]);
      if ($id_turno > 0 && $id_prescripcion > 0) {
        $query = "DELETE FROM nacer.prescripciones WHERE id = {$id_prescripcion}";
        $res_delete = sql($query);
        if ($res_delete === false) {
          $ret .= '<tr><td colspan="6" align="center" class="danger"><strong>Ocurrio un error al eliminar los datos de la Prescripci&oacute;n</strong></td></tr>';
        }
        $ret .= cargar_prescripciones($id_turno);
      }
      echo $ret;
      break;
*/    case 'info_vademecum':
        $ret = '<table class="table table-condensed table-striped">';
        $id_vademecum = intval($_POST["id_vademecum"]);

        if ($id_vademecum > 0) {
          $query = "SELECT * FROM nacer.vademecum WHERE id = {$id_vademecum}";
          $res_vademecum = sql($query) or die();
          if ($res_vademecum !== false && $res_vademecum->recordCount() > 0) {
            while (!$res_vademecum->EOF) {
              $ret .= '<tr>';
              $ret .= '<td width="25%"><b>Nombre Gen&eacute;rico:</b></td>';
              $ret .= '<td width="75%">' . $res_vademecum->fields["nombre_generico"] . '</td>';
              $ret .= '</tr><tr>';
              $ret .= '<td><b>Nombre Comerial:</b></td>';
              $ret .= '<td>' . $res_vademecum->fields["nombre_comercial"] . '</td>';
              $ret .= '</tr><tr>';
              $ret .= '<td><b>Grupo:</b></td>';
              $ret .= '<td>' . $res_vademecum->fields["grupo"] . '</td>';
              $ret .= '</tr><tr>';
              $ret .= '<td><b>Subgrupo:</b></td>';
              $ret .= '<td>' . $res_vademecum->fields["subgrupo"] . '</td>';
              $ret .= '</tr><tr>';
              $ret .= '<td><b>Acci&oacute;n Terap&eacute;utica:</b></td>';
              $ret .= '<td>' . $res_vademecum->fields["accion_terapeutica"] . '</td>';
              $ret .= '</tr><tr>';
              $ret .= '<td><b>Dosis:</b></td>';
              $ret .= '<td>' . $res_vademecum->fields["dosis"] . '</td>';
              $ret .= '</tr><tr>';
              $ret .= '<td><b>V&iacute;as de Aplicaci&oacute;n:</b></td>';
              $ret .= '<td>' . $res_vademecum->fields["vias_aplicacion"] . '</td>';
              $ret .= '</tr><tr>';
              $ret .= '<td><b>Efectos Adversos:</b></td>';
              $ret .= '<td>' . $res_vademecum->fields["efectos_adversos"] . '</td>';
              $ret .= '</tr><tr>';
              $ret .= '<td><b>Presentaci&oacute;n:</b></td>';
              $ret .= '<td>' . $res_vademecum->fields["presentacion"] . '</td>';
              $ret .= '</tr><tr>';
              $ret .= '<td><b>Observaciones:</b></td>';
              $ret .= '<td>' . $res_vademecum->fields["observaciones"] . '</td>';
              $ret .= '</tr>';
              $res_vademecum->MoveNext();
            }
          } else {

          }
        }
        $ret .= '</table>';
        echo $ret;
        break;
      case 'libreta_salud':
        include_once("libreta_funciones.php");
        $id_paciente = intval($_POST["id_paciente"]);
        $libreta_edad = intval($_POST["libreta_edad"]);
        $ret = '';
        if ($id_paciente > 0) {
          $libreta = new LibretaSalud($id_paciente);
          $ret .= '<div class="form-horizontal">';
          $ret .= '<div class="form-group">';
          $ret .= '<label for="libreta_edad" class="col-xs-2 control-label">Edad</label>';
          $ret .= '<div class="col-xs-4">';
          $ret .= '<select name="libreta_edad" id="libreta_edad" class="form-control" onchange="mostrar_libreta_salud();">';
          $ret .= $libreta->get_options($libreta_edad);
          $ret .= '</select>';
          $ret .= '</div>';
          $ret .= '<label for="libreta_edad" class="col-xs-2 control-label">Fecha</label>';
          $ret .= '<div class="col-xs-4">';
          $ret .= '<p class="form-control-static">'.date("d/m/Y").'</p>';
          $ret .= '</div>';
          $ret .= '</div>';
          // $ret .= '<div class="form-group">';
          $ret .= $libreta->get_all_fields($libreta_edad);
          // $ret .= '</div>';
          $ret .= '</div>';
        }
        echo $ret;
        break;
  }
}
?>