<?php
 require_once ("../../config.php");
    $keyword = '%'.$_POST['keyword'].'%';
    $id = $_POST['id'];
//nacer.efe_conv.id_efe_conv= $id and
       $sql = "SELECT DISTINCT
                nacer.especialidades.nom_titulo,
                nacer.medicos.nombre AS nom_prof,
                nacer.medicos.apellido AS ape_prof,
                nacer.agendas.id as id_agenda
                 FROM
                  nacer.agendas_eventos
                  LEFT OUTER JOIN nacer.efe_conv ON (nacer.agendas_eventos.id_efector = nacer.efe_conv.id_efe_conv)
                  LEFT OUTER JOIN uad.beneficiarios ON (nacer.agendas_eventos.id_paciente = uad.beneficiarios.id_beneficiarios)
                  LEFT OUTER JOIN nacer.agendas ON (nacer.agendas_eventos.id_agenda = nacer.agendas.id)
                  LEFT OUTER JOIN nacer.especialidades_medicos ON (nacer.agendas.id_especialidad_medico = nacer.especialidades_medicos.id)
                  LEFT OUTER JOIN nacer.medicos ON (nacer.especialidades_medicos.id_medico = nacer.medicos.id_medico)
                  LEFT OUTER JOIN nacer.obras_sociales ON (nacer.agendas_eventos.id_obra_social = nacer.obras_sociales.id_obra_social)
                  LEFT OUTER JOIN nacer.especialidades ON (nacer.especialidades.id_especialidad = nacer.especialidades_medicos.id_especialidad)
                 where nacer.efe_conv.id_efe_conv=$id and  (upper(ape_prof) like upper('$keyword') or  upper(nom_titulo) like upper('$keyword')) 
                ORDER BY
                nacer.especialidades.nom_titulo ASC";
                echo $sql;
       $res=$db->Execute ($sql) or die ("Error: no puedo ejecutar 02");
      foreach ($res as $rs) {
            // put in bold the written text
        $country_name1 = $rs['nom_titulo'].' - '.$rs['ape_prof'].', '.$rs['nom_prof'];
     
            // add new option
          echo '<li onclick="set_item_fin(\''.str_replace("'", "\'", $rs['nom_titulo'].' - '.$rs['ape_prof']).', '.$rs['nom_prof'].'\',\''.$rs['id_agenda'].'\')">'.$country_name1.'</li>';

}
     
?>