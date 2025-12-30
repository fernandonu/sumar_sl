<?php
require_once("../../config.php");

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
    case 'snomed_autocomplete':
      $buscar = $_GET["term"];
      $res_json = array();
      $query = "SELECT 
                  id_snomed,
                  term
                FROM
                  nacer.snomed
                WHERE
                  term ILIKE ".$db->Quote("%".$buscar."%")."
                  ";
      $res_snomed = sql($query, "al obtener los datos terminologias") or die();
      while (!$res_snomed->EOF) {
        $res_json[] = array(
          "id"            => $res_snomed->fields["id_snomed"],
          "value"         => $res_snomed->fields["term"],
        );
        $res_snomed->MoveNext();
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

    case 'info_vademecum':
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
      $id_benef_snomed = intval($_POST["id_benef_snomed"]);
      $ret = '';
      
      if ($id_paciente > 0) {

        $libreta = new LibretaSalud($id_paciente);
        if (isset($_POST["libreta_edad"])) {
          $libreta->edad_meses = intval($_POST["libreta_edad"]);
        }
        elseif (isset($parametros["libreta_edad"])) {
          $libreta->edad_meses = intval($parametros["libreta_edad"]);          
        }
        $libreta->cargar_libreta();
        //$libreta->obtener_percentilos();

        $ret .= '<input type="hidden" name="id_paciente" id="id_paciente" value="'.$id_paciente.'" />';
        $ret .= '<input type="hidden" name="id_benef_snomed" id="id_benef_snomed" value="'.$id_benef_snomed.'" />';
        $ret .= '<input type="hidden" name="libreta_id" id="libreta_id" value="'.$libreta->id_libreta.'" />';
        $ret .= '<input type="hidden" name="libreta_p_peso_tres" id="libreta_p_peso_tres" value="'.$libreta->p_peso["tres"].'" />';
        $ret .= '<input type="hidden" name="libreta_p_peso_diez" id="libreta_p_peso_diez" value="'.$libreta->p_peso["diez"].'" />';
        $ret .= '<input type="hidden" name="libreta_p_peso_noventa" id="libreta_p_peso_noventa" value="'.$libreta->p_peso["noventa"].'" />';
        $ret .= '<input type="hidden" name="libreta_p_peso_noventaysiete" id="libreta_p_peso_noventaysiete" value="'.$libreta->p_peso["noventaysiete"].'" />';
        $ret .= '<input type="hidden" name="libreta_p_talla_tres" id="libreta_p_talla_tres" value="'.$libreta->p_talla["tres"].'" />';
        $ret .= '<input type="hidden" name="libreta_p_talla_noventaysiete" id="libreta_p_talla_noventaysiete" value="'.$libreta->p_talla["noventaysiete"].'" />';
        $ret .= '<input type="hidden" name="libreta_p_perim_cefalico_tres" id="libreta_p_perim_cefalico_tres" value="'.$libreta->p_perim_cefalico["tres"].'" />';
        $ret .= '<input type="hidden" name="libreta_p_perim_cefalico_noventaysiete" id="libreta_p_perim_cefalico_noventaysiete" value="'.$libreta->p_perim_cefalico["noventaysiete"].'" />';
        $ret .= '<div class="form-horizontal">';
        $ret .= '<div class="form-group">';
        $ret .= '<label for="libreta_edad" class="col-xs-2 control-label">Edad</label>';
        $ret .= '<div class="col-xs-4">';
        $ret .= '<h6 id="libreta_edad">'.$libreta->get_mes_desc($libreta->edad_meses).'</h6>';
        $ret .= '</div>';
        $ret .= '<label for="libreta_edad" class="col-xs-3 control-label">Fecha Nacimiento</label>';
        $ret .= '<div class="col-xs-3">';
        $ret .= '<p class="form-control-static">'.$libreta->fecha_nacimiento->format("d/m/Y").'</p>';
        $ret .= '</div>';
        $ret .= '<div class="col-xs-12" align="left">';
        $ret .= '<label for="nutricion_form_peso" class="control-label text-danger">USAR "." como Separador de decimales</label>';
        $ret .= '</div>';
        $ret .= '</div>';
        $ret .= '<div class="form-group">';
        $ret .= '<label for="libreta_proximo_control" class="col-xs-3 control-label">Pr&oacute;ximo control</label>';
        $ret .= '<div class="col-xs-8 input-group date campo_fecha">';
        $ret .= '<input class="form-control" type="text" name="libreta_proximo_control" id="libreta_proximo_control" value="" />';
        $ret .= '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>';
        $ret .= '</div>';
        $ret .= '</div>';        
        $ret .= $libreta->get_fields_libreta();
        $ret .= '</div>';
      }
      echo $ret;
      break;

    case 'guardar_libreta':    
      $id_benef_snomed  = intval($_POST["id_benef_snomed"]);
      $id_paciente  = intval($_POST["id_paciente"]);      
      $edad_meses  = intval($_POST["edad_meses"]);      
      $mensaje_tipo = "success";
      $mensaje = "";
         
        $query = "INSERT INTO nacer.libreta_salud (id_paciente, edad_meses,id_benef_snomed) VALUES (".$id_paciente.", ".$edad_meses.", ".$id_benef_snomed.") RETURNING id";
        $res_insert = sql($query) or die();
        $id_libreta  = $res_insert->fields["id"];        

          if ($id_libreta > 0) {
            include_once("libreta_funciones.php");
            $libreta_edad = $edad_meses;
            $libreta_peso_percentilo = (empty($_POST["libreta_peso_percentilo"]) ? 'NULL' : intval($_POST["libreta_peso_percentilo"]));
            $libreta_talla_percentilo = (empty($_POST["libreta_talla_percentilo"]) ? 'NULL' : intval($_POST["libreta_talla_percentilo"]));
            $libreta_perim_cefalico_percentilo = (empty($_POST["libreta_perim_cefalico_percentilo"]) ? 'NULL' : intval($_POST["libreta_perim_cefalico_percentilo"]));
            $libreta_proximo_control = (empty($_POST["libreta_proximo_control"]) ? 'NULL' : "'".Fecha_db($_POST["libreta_proximo_control"])."'");

            $libreta = new LibretaSalud($id_paciente);
            $libreta_campos = $libreta->get_fields_libreta($libreta_edad, true);
            $desarrollo_edad = $libreta->get_mes_desarrollo($libreta_edad);

            $query_update = "UPDATE nacer.libreta_salud SET peso_percentilo = ".$libreta_peso_percentilo;
            $query_update .= ", talla_percentilo = ".$libreta_talla_percentilo.", perimetro_percentilo = ".$libreta_perim_cefalico_percentilo;
            $query_update .= ", proximo_control = ".$libreta_proximo_control;
            foreach ($libreta_campos as $campo => $valor) {
              if (strpos($campo, '|') !== false) {
                $campo_multiple = explode('|', $campo);
                // $tipo_multiple = explode('|', $valor["tipo"]);
                foreach ($campo_multiple as $campo_parcial) {
                  $query_update .= ", ".$campo_parcial." = ".(empty($_POST["libreta_".$campo_parcial]) ? 'NULL' : $_POST["libreta_".$campo_parcial]);
                }
              } 
              else {
                switch ($valor['tipo']) {
                  case 'num':
                  case 'dec1':
                    $query_update .= ", ".$campo." = ".(empty($_POST["libreta_".$campo]) ? 'NULL' : $_POST["libreta_".$campo]);
                    break;
                  case 'bool':
                    $query_update .= ", ".$campo." = ".((isset($_POST["libreta_".$campo]) && intval($_POST["libreta_".$campo]) == 1) ? "'t'" : "'f'");
                    break;
                  case 'date':
                  case 'text':
                  case 'select':
                    $query_update .= ", ".$campo." = ".(empty($_POST["libreta_".$campo]) ? 'NULL' : "'".$_POST["libreta_".$campo]."'");
                    break;
                  case 'masa':
                    if (empty($_POST["libreta_".$campo])) {
                      $libreta_masa = explode(' ', $_POST["libreta_".$campo]);
                      $masa = $libreta_masa[0];
                    }
                    else {
                      $masa = 'NULL';
                    }
                    $query_update .= ", ".$campo." = ".$masa;
                    break;
                  
                  default:
                    break;
                }
              }
            }
            $query_update .= ' WHERE id = '.$id_libreta;
            $res_libreta = sql($query_update) or die($query_update);

            if ($res_libreta === false) {              
              $mensaje_tipo = "danger";
              $mensaje = "No se pudo guardar los datos de la libreta de salud";
            }
            
            if (empty($mensaje) && isset($_POST["libreta"]) && isset($_POST["libreta"]["desarrollo"])) {
              $campos_des = $libreta->get_fields_desarrollo();
              $query_des = "INSERT INTO nacer.libreta_salud_desarrollo (id_libreta, edad_meses";
              foreach ($campos_des as $campo_desarrollo => $desc_desarrollo) {
                $query_des .= ','.$campo_desarrollo;
              }
              $query_des .= ') VALUES ('.$id_libreta.', '.$desarrollo_edad;
              foreach ($campos_des as $campo_desarrollo => $desc_desarrollo) {
                if (isset($_POST["libreta"]["desarrollo"][$campo_desarrollo])) {
                  $query_des .= ", '".$_POST["libreta"]["desarrollo"][$campo_desarrollo]."'";
                }
                else {                  
                  $mensaje_tipo = "danger";
                  $mensaje = "No se completaron los campos de la secci&oacute;n Desarrollo";
                }
              }
              $query_des .= ') RETURNING id';
              $res_des = sql($query_des) or die($query_des);
              $new_id_desarrollo = null;
              if ($res_des->recordCount() == 1) {
                $new_id_desarrollo = $res_des->fields["id"];
              } 
              else {
                $mensaje_tipo = "danger";
                $mensaje = "No se pudo guardar los datos de la secci&oacute;n Desarrollo";
              }
            }
            else{
              $mensaje = "No hay datos de seccion desarrollo";
            }
          }
      echo json_encode(array("mensaje" => $mensaje, "tipo" => $mensaje_tipo));
      break;
    case 'cancelar_turno':
      $id_turno = $_POST["id_turno"];
      $query="UPDATE nacer.agendas_eventos SET estado = 'ausente' WHERE id = ".$db->Quote($id_turno);
        
      $res_update = sql($query) or die(-1);
      echo ($db->Affected_Rows() == 1) ? 1 : 0;
      break;
    case 'turno_presente':
      $id_turno = $_POST["id_turno"];
      $hora_presente = date("Y-m-d H:i:s");
      $motivo = $_POST["motivo"];
      $sobreturno = $_POST["sobreturno"];
      if ($sobreturno=='sobreturno') $sobreturno=1;
      else $sobreturno=0;
      $query="UPDATE nacer.agendas_eventos SET 
                estado = 'presente',
                hora_presente = '".$hora_presente."',
                motivo = '".$motivo."',
                sobreturno = '".$sobreturno."'
                WHERE id = ".$db->Quote($id_turno);
        
      $res_update = sql($query) or die(-1);
      echo ($db->Affected_Rows() == 1) ? 1 : 0;
      break;
    case 'guardar_diagnostico':
      $id_turno  = intval($_POST["id_turno"]);      
      $id_benef_snomed  = $_POST["id_benef_snomed"];
      $new_prescripcion = $_POST["new_prescripcion"];
      $mensaje_tipo = "success";
      $mensaje = "";
      if (!empty($id_benef_snomed)) {

        $query_insert = "INSERT INTO nacer.diagnosticos (id_turno, subjetivo, objetivo, plan_d, plan_m, plan_e, tratamiento_no_farma,id_benef_snomed)";
        $query_insert .= " VALUES (".$db->Quote($id_turno).", ";
        $query_insert .= $db->Quote($_POST["subjetivo"]).", ";
        $query_insert .= $db->Quote($_POST["objetivo"]).", ";
        $query_insert .= $db->Quote($_POST["plan_d"]).", ";
        $query_insert .= $db->Quote($_POST["plan_m"]).", ";
        $query_insert .= $db->Quote($_POST["plan_e"]).", ";
        $query_insert .= $db->Quote($_POST["tratamiento_no_farma"]).", ";
        $query_insert .= $db->Quote($id_benef_snomed);
        $query_insert .= ") RETURNING id";
        $res = sql($query_insert) or die($query_insert);
        if ($res->recordCount() == 1) {
          $new_id_diagnostico = $res->fields["id"];
        } 
        else {
          $mensaje_tipo = "danger";
          $mensaje = "No se pudo guardar los datos del Diagn&oacute;stico";
        }

        if (empty($mensaje)) {
          $query_update = "UPDATE nacer.agendas_eventos 
                            SET estado = 'completo'
                            WHERE id = ".$db->Quote($id_turno);
          $res = sql($query_update) or fin_pagina();
          if ($res === false) {
            $mensaje_tipo = "danger";
            $mensaje = "No se pudo actualizar el estado del turno";
          }
        }

        if (empty($mensaje)) {
          if (!empty($new_prescripcion) && is_array($new_prescripcion)) {
            foreach ($new_prescripcion as $prescripcion) {
              $query_insert = "INSERT INTO nacer.prescripciones (id_diagnostico, id_vademecum, observaciones, dosis, presentacion, frecuencia, cantidad)";
              $query_insert .= " VALUES (" . $new_id_diagnostico . ", ";
              $query_insert .= $prescripcion["id_vademecum"] . ", ";
              $query_insert .= $db->Quote($prescripcion["observaciones"]) . ", ";
              $query_insert .= $db->Quote($prescripcion["dosis"]) . ", ";
              $query_insert .= $db->Quote($prescripcion["presentacion"]) . ", ";
              $query_insert .= $db->Quote($prescripcion["frecuencia"]) . ", ";
              $query_insert .= $db->Quote($prescripcion["cantidad"]) . ")";
              $res = sql($query_insert) or die($query_insert);
              if ($res === false) {
                $mensaje_tipo = "danger";
                $mensaje = "No se pudo guardar la prescripci&oacute;n";
                break;
              }
            }
          }
        }
        
      }
      echo json_encode(array("mensaje" => $mensaje, "tipo" => $mensaje_tipo));
      break;
  }
}
?>