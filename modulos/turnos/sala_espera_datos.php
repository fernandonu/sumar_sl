<?php

require_once("../../config.php");
require_once("agenda_funciones.php");

$extras = array(
            "id_efector"      => "",
            "id_especialidad" => "",
            "id_medico"       => ""
          );
variables_form_busqueda("sala_espera", $extras);
// ini_set('display_errors',1);
// ini_set('display_startup_errors',1);
// error_reporting(E_ALL);

if (isset($parametros["accion"])) {
  switch ($parametros["accion"]) {
    case 'cargar_especialidades':
      $ret = '<option value="">Todas</option>';
      $id_efector = intval($_POST["id_efector"]);

      if ($id_efector > 0) {
        $query = "SELECT 
                nacer.especialidades.id_especialidad,
                nacer.especialidades.nom_titulo
              FROM
                nacer.especialidades_efectores
                LEFT OUTER JOIN nacer.especialidades ON (nacer.especialidades_efectores.id_especialidad = nacer.especialidades.id_especialidad)
              WHERE
                nacer.especialidades_efectores.id_efector = $id_efector
              ORDER BY
                nacer.especialidades.nom_titulo";
        
        $res_especialidades = sql($query) or die();
        $color_index = 0;
        while (!$res_especialidades->EOF) {
          $ret .= '<option value="' . $res_especialidades->fields['id_especialidad'] . '" data-color="' . $agenda_colores[$color_index] . '">';
          $ret .= $res_especialidades->fields['nom_titulo'] . '</option>';
          $res_especialidades->MoveNext();
          $color_index++;
          if ($color_index == count($agenda_colores)) {
            $color_index = 0;
          }
        }
        $_ses_sala_espera["id_especialidad"] = "";
        phpss_svars_set("_ses_sala_espera", $_ses_sala_espera);
      }
      else {
        $_ses_sala_espera["id_efector"] = "";
        $_ses_sala_espera["id_especialidad"] = "";
        phpss_svars_set("_ses_sala_espera", $_ses_sala_espera);
      }
      echo $ret;
      break;
    
    case 'cargar_medicos_sala_esp':
      $ret = '<option value="">Todos</option>';
      $id_efector = intval($_POST["id_efector"]);
      $id_especialidad = intval($_POST["id_especialidad"]);

      if ($id_especialidad > 0) {
        $query = "SELECT 
              nacer.medicos.nombre,
              nacer.medicos.apellido,
              nacer.medicos.id_medico,
              *
            FROM
              nacer.medicos_efectores
              INNER JOIN nacer.medicos ON (nacer.medicos_efectores.id_medico = nacer.medicos.id_medico)
              INNER JOIN nacer.especialidades_medicos ON (nacer.medicos.id_medico = nacer.especialidades_medicos.id_medico)
            WHERE
              nacer.medicos_efectores.id_efector = $id_efector AND 
              nacer.especialidades_medicos.id_especialidad = $id_especialidad";
 
        $res_medico = sql($query) or die();
        $color_index = 0;
        while (!$res_medico->EOF) {
          $ret .= '<option value="' . $res_medico->fields['id_medico'] . '" data-color="' . $agenda_colores[$color_index] . '">';
          $ret .= $res_medico->fields['nombre'].", ".$res_medico->fields['apellido']."</option>";
          $res_medico->MoveNext();
          $color_index++;
          if ($color_index == count($agenda_colores)) {
            $color_index = 0;
          }
        }
        $_ses_sala_espera["id_medico"] = "";
        phpss_svars_set("_ses_sala_espera", $_ses_sala_espera);
      }
      else {
        $_ses_sala_espera["id_efector"] = "";
        $_ses_sala_espera["id_especialidad"] = "";
        $_ses_sala_espera["id_medico"] = "";
        phpss_svars_set("_ses_sala_espera", $_ses_sala_espera);
      }
      echo $ret;
      break;

    case 'cargar_medicos':
      $id_especialidad = intval($_POST["id_especialidad"]);
      if ($id_especialidad > 0) {
        cargar_medicos($id_especialidad);
      }
      else {
        $_ses_sala_espera["id_especialidad"] = "";
        phpss_svars_set("_ses_sala_espera", $_ses_sala_espera);
      }
      break;

    case 'agenda_events':
      $res_json = array();
      $start = $_POST["start"];
      $end = $_POST["end"];
      // $agendas_ids = $_POST["agendas_ids"];
      $id_efector = $_POST["id_efector"];
      $id_especialidad = $_POST["id_especialidad"];
      $id_medico = $_POST["id_medico"];
      $modo_sala_espera = $_POST["modo_sala_espera"];
      $query = "SELECT 
                  nacer.agendas_eventos.id,
                  nacer.agendas_eventos.titulo,
                  nacer.agendas_eventos.inicio,
                  nacer.agendas_eventos.fin,
                  nacer.agendas_eventos.url,
                  nacer.agendas_eventos.id_agenda,
                  nacer.agendas_eventos.id_paciente,
                  nacer.agendas_eventos.estado,
                  nacer.agendas_eventos.hora_presente,
                  nacer.agendas_eventos.motivo,
                  nacer.agendas_eventos.sobreturno,
                  uad.beneficiarios.apellido_benef AS paciente_apellido,
                  uad.beneficiarios.nombre_benef AS paciente_nombre,
                  nacer.agendas_eventos.id_efector,
                  nacer.efe_conv.nombre AS efector_nombre,
                  nacer.especialidades.id_especialidad,
                  nacer.especialidades.nom_titulo AS especialidad_nombre,
                  nacer.medicos.id_medico,
                  nacer.medicos.apellido AS medico_apellido,
                  nacer.medicos.nombre AS medico_nombre,
                  nacer.obras_sociales.id_obra_social,
                  nacer.obras_sociales.nom_obra_social AS obra_social_nombre
                FROM
                  nacer.agendas_eventos
                  LEFT OUTER JOIN nacer.efe_conv ON (nacer.agendas_eventos.id_efector = nacer.efe_conv.id_efe_conv)
                  LEFT OUTER JOIN uad.beneficiarios ON (nacer.agendas_eventos.id_paciente = uad.beneficiarios.id_beneficiarios)
                  LEFT OUTER JOIN nacer.agendas ON (nacer.agendas_eventos.id_agenda = nacer.agendas.id)
                  LEFT OUTER JOIN nacer.especialidades_medicos ON (nacer.agendas.id_especialidad_medico = nacer.especialidades_medicos.id)
                  LEFT OUTER JOIN nacer.medicos ON (nacer.especialidades_medicos.id_medico = nacer.medicos.id_medico)
                  LEFT OUTER JOIN nacer.obras_sociales ON (nacer.agendas_eventos.id_obra_social = nacer.obras_sociales.id_obra_social)
                  LEFT OUTER JOIN nacer.especialidades ON (nacer.especialidades.id_especialidad = nacer.especialidades_medicos.id_especialidad)
                WHERE
                  nacer.agendas_eventos.estado IN ('presente', 'completo') AND 
                  nacer.agendas_eventos.inicio >= '{$start}' AND 
                  nacer.agendas_eventos.fin <= '{$end}'";

      if (!empty($id_efector)) {
        $query .= " AND nacer.agendas_eventos.id_efector = {$id_efector}";
      }
      if (!empty($id_especialidad)) {
        $query .= " AND nacer.especialidades.id_especialidad = {$id_especialidad}";
      }
      if (!empty($id_medico)) {
        $query .= " AND nacer.medicos.id_medico = {$id_medico}";
      }
      $res_eventos = sql($query) or die($query);
      while (!$res_eventos->EOF) {
        $res_json[] = array(
          "id"            => $res_eventos->fields["id"],
          "start"         => $res_eventos->fields["inicio"],
          "end"           => $res_eventos->fields["fin"],
          "title"         => $res_eventos->fields["paciente_apellido"].' '.$res_eventos->fields["paciente_nombre"],
          "url"           => $res_eventos->fields["url"],
          "estado"        => $res_eventos->fields["estado"],
          "id_agenda"     => $res_eventos->fields["id_agenda"],
          "id_paciente"   => $res_eventos->fields["id_paciente"],
          "efector"       => $res_eventos->fields["efector_nombre"],
          "especialidad"  => $res_eventos->fields["especialidad_nombre"],
          "medico"        => $res_eventos->fields["medico_apellido"].' '.$res_eventos->fields["medico_nombre"],
          "motivo"        => $res_eventos->fields["motivo"],
          "hora_presente" => substr($res_eventos->fields["hora_presente"],11,5),
          "sobreturno"    => $res_eventos->fields["sobreturno"],
          "obra_social"   => (empty($res_eventos->fields["obra_social_nombre"]) ? "Sin Obra Social" : $res_eventos->fields["obra_social_nombre"])
        );
        $res_eventos->MoveNext();
      }
      echo json_encode($res_json);
      break;

    case 'agenda_add':
      $id_agenda = $_POST["id_agenda"];
      $id_paciente = $_POST["id_paciente"];
      $id_efector = $_POST["id_efector"];
      $inicio = $_POST["inicio"];
      $fin = $_POST["fin"];
      $query="INSERT INTO nacer.agendas_eventos
                (titulo, inicio, fin, url, id_agenda, id_paciente, id_efector, estado)
              VALUES
                (
                '',
                ".$db->Quote($inicio).", 
                ".$db->Quote($fin).",
                '',
                ".$db->Quote($id_agenda).",
                ".$db->Quote($id_paciente).",
                ".$db->Quote($id_efector).",
                'activo'
                )
              RETURNING id
          ";
        
      $res_insert = sql($query) or die();
      
      $id_evento = $res_insert->fields['id'];

      echo (($id_evento > 0) ? $id_evento : 0);
      break;
    case 'cancelar_turno':
      $id_turno = $_POST["id_turno"];
      $query="UPDATE nacer.agendas_eventos SET estado = 'cancelado' WHERE id = ".$db->Quote($id_turno);
        
      $res_update = sql($query) or die(-1);
      echo ($db->Affected_Rows() == 1) ? 1 : 0;
      break;
    case 'datos_paciente':
      $dni = intval($_POST["dni"]);
      $res_json = array();
      if ($dni > 0) {
        $query = "SELECT *
              FROM 
                nacer.smiafiliados
              WHERE
                afidni = '$dni'";
        $res_paciente = sql($query) or die();
        // TODO: guardar los parametros del form_busqueda si hay mas de un resultado
        while (!$res_paciente->EOF) {
          $res_json[] = array(
            "id"                => $res_paciente->fields["id_smiafiliados"],
            "apellido"          => $res_paciente->fields["afiapellido"],
            "nombre"            => $res_paciente->fields["afinombre"],
            "documento"         => $res_paciente->fields["afitipodoc"].' '.$res_paciente->fields["afidni"],
            "sexo"              => ((trim($res_paciente->fields["afisexo"]) == 'M') ? 'Masculino' : 'Femenino'),
            "fechanac"          => Fecha($res_paciente->fields["afifechanac"]),
            "clavebeneficiario" => $res_paciente->fields["clavebeneficiario"],
            "cuieefector"       => $res_paciente->fields["cuieefectorasignado"],
            "cuielugaratencion" => $res_paciente->fields["cuielugaratencionhabitual"],
            "activo"            => htmlentities($sino[trim($res_paciente->fields["activo"])])
          );
          $res_paciente->MoveNext();
        }
      }
      echo json_encode($res_json);
      break;
    case 'hoja_sintesis':
      $id_paciente = intval($_POST["id_paciente"]);
      if ($id_paciente > 0) {
        include_once("ficha_consumo.php");
      } else {
        echo "Par&aacute;metros no v&aacute;lidos!";
      }
      break;
    default:
      # code...
      break;
  }
}
?>