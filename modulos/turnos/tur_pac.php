<?php
require_once("../../config.php");
require_once("agenda_funciones.php");

$users=$_ses_user['name'];
$usuario=new user($_ses_user['login']);
$id_user=$usuario->get_id_usuario();

$id_efector = $_POST['id_efector'];
$id_especialidad = $_POST['id_especialidad'];
$id_medico = $_POST['id_medico'];
$fecha_inicio = $_POST['fecha_inicio'];

$q_user_ef="";


cargar_calendario();



echo $html_header;?>


<script type="text/javascript">
  $(document).ready(function() {
    // permitir solo el ingreso de numeros en los campos con la clase "numeric"
    $('input.numeric').keyup(function() {     
        this.value = this.value.replace(/[^0-9]/g,'');
    });
    $('#id_efector').change(function(e) {
      var selectvalue = $(this).val();

      if (selectvalue == '') {
        $('#id_especialidad').html('<option value="">Seleccione una Especialidad...</option>');
        // resetear los valores de la sesion
        $.ajax({
          url: '<?php echo encode_link("sala_espera_datos.php", array("accion" => "cargar_especialidades")); ?>',
          type: 'POST',
          data: 'id_efector=0'
        });
      }
      else{
        $('#id_especialidad').html('<option value="">Cargando...</option>');
        $.ajax({
          url: '<?php echo encode_link("sala_espera_datos.php", array("accion" => "cargar_especialidades")); ?>',
          type: 'POST',
          data: 'id_efector='+selectvalue,
          success: function(opciones) {
            $('#id_especialidad').html(opciones);
          },
          error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + " "+ thrownError);
          }
        });
      }
    });

    $('#id_especialidad').change(function(e) {
      var selectvalue = $(this).val();
      var selectvalue_efec = $('#id_efector').val();

      if (selectvalue == '') {
        $('#id_medico').html('<option value="">Seleccione un Profesional...</option>');
        quitar_referencias()
        // resetear los valores de la sesion
        $.ajax({
          url: '<?php echo encode_link("sala_espera_datos.php", array("accion" => "cargar_medicos_sala_esp")); ?>',
          type: 'POST',
          data: 'id_especialidad=0'
        });
      }
      else{
        $('#id_medico').html('<option value="">Cargando...</option>');
        $.ajax({
          url: '<?php echo encode_link("sala_espera_datos.php", array("accion" => "cargar_medicos_sala_esp")); ?>',
          type: 'POST',
          data: "id_efector="+selectvalue_efec+'&id_especialidad='+selectvalue,
          success: function(opciones) {
            $('#id_medico').html(opciones);
          },
          error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + " "+ thrownError);
          }
        });
      }
    });

  });

  function control_nuevos(){
    if ($('#id_efector').val()==''){
      BootstrapDialog.alert('Debe ingresar Efector!');
      return false;
    }
    if ($('#id_especialidad').val()==''){
      BootstrapDialog.alert('Debe ingresar Especialidades!');
      return false;
    }
    if ($('#id_medico').val()==''){
      BootstrapDialog.alert('Debe ingresar Medicos!');
      return false;
    }
    if ($('#fecha_inicio').val()==''){
      BootstrapDialog.alert('Debe ingresar fecha!');
      return false;
    }
  }
  </script>
      
<form name='form1' action='tur_pac.php' method='POST'>
<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
<?php

 //if(permisos_check('ver','visualizar_todo')){ 

$query = "SELECT 
        nacer.efe_conv.id_efe_conv,
        nacer.efe_conv.nombre,
        sistema.usu_efec.cuie
      FROM
        sistema.usu_efec
        INNER JOIN nacer.efe_conv ON (sistema.usu_efec.cuie = nacer.efe_conv.cuie)
      WHERE
        sistema.usu_efec.id_usuario = $id_user
      ORDER BY nacer.efe_conv.nombre";

$res_efectores = sql($query, "al obtener los datos de los Efectores") or fin_pagina();

if ($id_efector) {
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
  
  $res_especialidades = sql($query, "al obtener los datos de las Especialidades") or fin_pagina();
}

if (($id_efector)&&($id_especialidad)) {
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
  
  $res_medico = sql($query, "al obtener los datos de las Especialidades") or fin_pagina();
}

?>

<div class="container" style="width:100%; height:100%;">
  <br/>
   <div class="row">
    <div class="col-sm-5 col-sm-offset-1">
      <label for="id_efector">Efector</label>
      <select class="form-control" name="id_efector" id="id_efector">
        <option value="">Todos</option>
        <?php
        $res_efectores->MoveFirst();
        while (!$res_efectores->EOF) {
          $selected = "";
          if (!empty($id_efector) && $id_efector == $res_efectores->fields['id_efe_conv']) {
            $selected = " selected";
          }
          echo '<option value="', $res_efectores->fields['id_efe_conv'], '"';
          echo $selected, '>', $res_efectores->fields['nombre'], '</option>';
          $res_efectores->MoveNext();
        }
        ?>
      </select>
    </div>
    <div class="col-sm-5">
      <label for="especialidad-nombre">Especialidad</label>
      <select class="form-control" name="id_especialidad" id="id_especialidad">
        <?php 
        if ($id_efector) {
            echo '<option value="">Todas</option>';
          $res_especialidades->MoveFirst();
          $color_index = 0;
          while (!$res_especialidades->EOF) {
            $selected = "";
            if (!empty($id_especialidad) && $id_especialidad == $res_especialidades->fields['id_especialidad']) {
              $selected = " selected";
            }
            echo '<option value="', $res_especialidades->fields['id_especialidad'], '" ';
            echo 'data-color="', $agenda_colores[$color_index], '"';
            echo $selected, '>', $res_especialidades->fields['nom_titulo'], '</option>';
            $color_index++;
            if ($color_index == count($agenda_colores)) {
              $color_index = 0;
            }
            $res_especialidades->MoveNext();
          }
        } else { 
          echo '<option value="">Seleccione un Efector...</option>';
        }
        ?>
      </select>
    </div>
  </div>
  <br/>
  <div class="row">
    <div class="col-sm-5 col-sm-offset-1">
      <label for="id_medico">Profesional</label>
      <select class="form-control" name="id_medico" id="id_medico">
        <?php
        if (($id_efector)&&($id_especialidad)) {
          echo '<option value="">Todos</option>';
          $res_medico->MoveFirst();
          while (!$res_medico->EOF) {
            $selected = "";
            if (!empty($id_medico) && $id_medico == $res_medico->fields['id_medico']) {
              $selected = " selected";
            }
            echo '<option value="',$res_medico->fields['id_medico'], '"';
            echo $selected, '>', $res_medico->fields['nombre'].", ".$res_medico->fields['apellido'], '</option>';
            $res_medico->MoveNext();
          }
        } 
        else { 
          echo '<option value="">Seleccione un Profesional...</option>';
        }?>
      </select>
    </div>

    <div class="col-sm-5">
      <label for="id_medico">Fecha de Atencion</label>
      <input type=text id=fecha_inicio name=fecha_inicio value='<?=$fecha_inicio?>' size=15 title="Fecha de atencion" >
        <?=link_calendario("fecha_inicio");?> 
    </div>
  </div>

<tr><td><div><table width=100%  align="center">
    <tr id=ma>
        <td>
            <input type="submit" name="muestra" value='Siguiente' onclick="return control_nuevos()" >
        </td>
    <
</table></div></td></tr>


<tr><td><div><table width=100%  align="center">
    <tr id=mo>
      <td>
          <font size=+1><b>Planilla de atencion <?=$fecha_inicio?></b></font>           
      </td>
    </tr>
</table></div></td></tr>

<tr><td><div><table width=95%  align="center">
   <table border=0 width=90% cellspacing=2 cellpadding=2 align=center>

<link rel="stylesheet" type="text/css" href="<?php echo $html_root?>/lib/jquery/jquery.dataTables.min.css" />
<script src="<?php echo $html_root?>/lib/jquery/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function() {
    $('#prestacion').DataTable( {
        
        "columnDefs": [
          { "width": "20%" },
          { "width": "20%" },
          { "width": "10%" },
          { "width": "20%" },
          { "width": "10%" },
          { "width": "10%" }
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

  <tr><td><table id="prestacion" width=95% align="center" class="table table-striped" style="border:thin groove">
        <thead>
            <tr>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Cobertura</th>
                <th>Estado</th>
                <th>Hora</th>
                <th>Diagnostico</th>
                <th>Registrar</th>
                <th>CABASS</th>

            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Cobertura</th>
                <th>Estado</th>
                <th>Hora</th>
                <th>Diagnostico</th>
                <th>Registrar</th>
                <th>CABASS</th>

            </tr>
        </tfoot>
 
        <tbody>
          <? 
          if ($_POST['muestra']=="Siguiente"){
            
            $fecha_inicio_db=fecha_db($fecha_inicio);
            $sql_tmp="SELECT nacer.agendas_eventos.id AS id_ag_evento,
                      nacer.agendas_eventos.titulo,
                      nacer.agendas_eventos.inicio,
                      nacer.agendas_eventos.id_agenda,
                      nacer.agendas_eventos.id_paciente,
                      nacer.agendas_eventos.estado,
                      nacer.agendas_eventos.motivo,
                      nacer.agendas_eventos.sobreturno,
                      uad.beneficiarios.apellido_benef AS paciente_apellido,
                      uad.beneficiarios.nombre_benef AS paciente_nombre,
                      uad.beneficiarios.numero_doc,
                      nacer.agendas_eventos.id_efector,
                      nacer.efe_conv.nombre AS efector_nombre,
                      nacer.especialidades.id_especialidad,
                      nacer.especialidades.nom_titulo AS especialidad_nombre,
                      nacer.medicos.id_medico,
                      nacer.medicos.apellido AS medico_apellido,
                      nacer.medicos.nombre AS medico_nombre,
                      nacer.obras_sociales.id_obra_social,
                      nacer.obras_sociales.nom_obra_social AS obra_social_nombre,
                      nacer.cie10.dec10,
                      nacer.cie10.grp10,
                      nacer.cepsap_items.codigo as cesapcod1,
                      nacer.cepsap_items.descripcion as cesapdesc2,
                      nacer.cepsap_items_2016.codigo as cesapcod2,
                      nacer.cepsap_items_2016.descripcion as cesapdesc2,
                      uad.beneficiarios.sexo,
                      uad.beneficiarios.fecha_nacimiento_benef,
                      uad.beneficiarios.tipo_documento,
                      nacer.diagnosticos.id_cepsap,
                      nacer.diagnosticos.id_cie10,
                      nacer.diagnosticos.id_cesap2,
                      nacer.agendas_eventos.estado 
                      FROM
                         nacer.agendas_eventos
                        LEFT OUTER JOIN nacer.efe_conv ON (nacer.agendas_eventos.id_efector = nacer.efe_conv.id_efe_conv)
                        LEFT OUTER JOIN uad.beneficiarios ON (nacer.agendas_eventos.id_paciente = uad.beneficiarios.id_beneficiarios)
                        LEFT OUTER JOIN nacer.agendas ON (nacer.agendas_eventos.id_agenda = nacer.agendas.id)
                        LEFT OUTER JOIN nacer.especialidades_medicos ON (nacer.agendas.id_especialidad_medico = nacer.especialidades_medicos.id)
                        LEFT OUTER JOIN nacer.medicos ON (nacer.especialidades_medicos.id_medico = nacer.medicos.id_medico)
                        LEFT OUTER JOIN nacer.obras_sociales ON (nacer.agendas_eventos.id_obra_social = nacer.obras_sociales.id_obra_social)
                        LEFT OUTER JOIN nacer.especialidades ON (nacer.especialidades.id_especialidad = nacer.especialidades_medicos.id_especialidad)
                        LEFT OUTER JOIN nacer.diagnosticos ON nacer.agendas_eventos.id = nacer.diagnosticos.id_turno
                        LEFT OUTER JOIN nacer.cie10 ON nacer.cie10.id10 = nacer.diagnosticos.id_cie10
                        LEFT OUTER JOIN nacer.cepsap_items ON nacer.cepsap_items.id = nacer.diagnosticos.id_cepsap
                        LEFT OUTER JOIN nacer.cepsap_items_2016 ON nacer.cepsap_items_2016.id = nacer.diagnosticos.id_cesap2
                      WHERE (date (nacer.agendas_eventos.inicio) = '{$fecha_inicio_db}' AND 
                          nacer.agendas_eventos.id_efector = {$id_efector} AND 
                          nacer.agendas_eventos.estado != 'cancelado' AND
                          nacer.especialidades.id_especialidad = {$id_especialidad} AND 
                          nacer.medicos.id_medico = {$id_medico})
                  ORDER BY nacer.agendas_eventos.inicio ASC";
              if (!empty($id_efector) and !empty($id_especialidad) and !empty($id_medico) and !empty($fecha_inicio)) {
                $sql_pres=sql($sql_tmp,"No se pueden mostrar los registros");

                $sql_pres->MoveFirst;
                   while (!$sql_pres->EOF){ 
                   
                    $estado=$sql_pres->fields['estado'];
                    if($estado=='ausente')$c_linea='#F38D8D';
                    ?>
                    <tr btn-danger>
                      <td align="left"><?=$sql_pres->fields['paciente_apellido'];?></td>
                      <td align="left"> <?=$sql_pres->fields['paciente_nombre']?></td>
                      <td align="left" > <?=$sql_pres->fields['tipo_documento'].'-'.$sql_pres->fields['numero_doc']?></td>
                      <td align="left"> <?=$sql_pres->fields['obra_social_nombre']?></td>
                      <td align="left" clase='col-sm-offset-1'> <?=$estado?></td>
                      <td align="left"> <? $inicio=$sql_pres->fields['inicio'];
                                            $hora_i=substr($inicio,11,15);
                                            echo  $hora_i;
                      ?></td>
                      
                   
                     <td><a><?if($sql_pres->fields['id_cepsap']!=0)
                            echo  $sql_pres->fields['cesapcod1'].'-'.$sql_pres->fields['cesapdesc1'].'-'.$sql_pres->fields['dec10'];// MUESTRA cepsap1?>
                            <?if($sql_pres->fields['id_cesap2']!=0)
                            echo  $sql_pres->fields['cesapcod2'].'-'.$sql_pres->fields['cesapdesc2'];// MUESTRA  CIE10
                            elseif($sql_pres->fields['id_cie10']!=0)
                        echo  $sql_pres->fields['codigo'].'-'.$sql_pres->fields['descripcion'];//muestra por CESAP
                       
                        ?></a>
                    <td>
                        <? 
                     $link3= encode_link("c_diagnostico.php",array("id_turno"=>$sql_pres->fields['id_ag_evento'],"id_efector"=>$id_efector, "fecha_inicio_db"=>$fecha_inicio_db,"id_especialidad"=>$id_especialidad,"id_medico"=>$id_medico,"pagina"=>"tur_pac.phps"));
                     $link2="window.open('$link3','','top=300, left=300, width=880, height=420, scrollbars=1, status=1,directories=1');";
                    ?>
                        <input type="button" name=nuevo value='Cargar' onclick="<?=$link2?>" class="btn btn-primary" data-dismiss="modal">
                  </td>     
                      <?$ref_leche = encode_link("cabass_pdf.php",array("id_agevent"=>$sql_pres->fields['id_ag_evento'],"origen"=>"NUEVO","pagina"=>"tur_pac.php"));?>
                  <td align="center"><?if($sql_pres->fields['id_obra_social'] >0)  { ?><a href="<?=$ref_leche?>" title=" CABASS"><IMG src='../../imagenes/pdf_logo.gif' height='20' width='20' border='0'></a>
                  </td>
                  <?}?>
                  </tr>
                   
                  <?$sql_pres->MoveNext();
                    }
              }
      }?>
 </tbody>
    </table>
 </td></tr></table>
</table></div></td></tr>

<tr><td><div><table width=100%  align="center"  id=ma>
<tr><td><div><table width=20%  align="center">

    <tr width=30%  align="center">
        <!--td>
          <?  $ref = encode_link("planillat_pdf.php",array("id_efector"=>$id_efector, "fecha_inicio_db"=>$fecha_inicio_db,"id_especialidad"=>$id_especialidad,"id_medico"=>$id_medico));
          $onclick_elegir_aux="location.href='$ref'";?>
           <!--img src='../../imagenes/pdf_logo.gif' style='cursor:hand;' onclick="<?=$onclick_elegir_aux?>"> 
        </td!-->
          <?$ref_planilla = encode_link("planilla_exc.php",array("id_efector"=>$id_efector, "fecha_inicio_db"=>$fecha_inicio_db,"id_especialidad"=>$id_especialidad,"id_medico"=>$id_medico));?>
        <td align="center">  
              <a href="<?=$ref_planilla?>" title="Planilla de atencion medica"><IMG src='../../imagenes/excel.gif' ></a>
        </td>
</table></div></td></tr>  
</table></div></td></tr>

</div>
 </form>

 <?=fin_pagina();// aca termino ?>