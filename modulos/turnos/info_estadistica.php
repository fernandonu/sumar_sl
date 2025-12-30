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
$fecha_hasta=$_POST['fecha_hasta'];

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
      
<form name='form1' action='info_estadistica.php' method='POST'>

<?php
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
}?>

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
      <label for="id_medico">Fecha desde</label>
      <input type=text id=fecha_inicio name=fecha_inicio value='<?=$fecha_inicio?>' size=15 title="Fecha de desde" >
        <?=link_calendario("fecha_inicio");?> 
    </div>
    <div class="col-sm-5">
      <label for="id_medico">Fecha hasta</label>
      <input type=text id=fecha_hasta name=fecha_hasta value='<?=$fecha_hasta?>' size=15 title="Fecha de Hasta" >
        <?=link_calendario("fecha_hasta");?> 
    </div>
  </div>

<tr><td><div><table width=100%  align="center">
    <tr id=ma>
        <td>
            <input type="submit" name="buscar" value='Buscar'  >
        </td>
    <
</table></div></td></tr>


<tr><td><div><table width=100%  align="center">
    <tr id=mo>
      <td>
          <font size=+1><b>Registro de atenciones</b></font>           
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
          { "width": "10%" },
          { "width": "10%" },
          { "width": "10%" },
          { "width": "10%" },
          { "width": "10%" },
          { "width": "10%" },
          { "width": "10%" },
          { "width": "10%" },
          { "width": "10%" },
          { "width": "10%" },
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
                <th>Efector</th>
                <th>Especialidad</th>
                <th>Profesional</th>
                <th>Apellido y Nombre</th>
                <th>Tipo y N°</th>
                <th>Sexo</th>
                <th>Edad</th>
                <th>Cobertura</th>                
                <th>Estado</th>
                <th>Fecha de Atencion</th>
                <th>tipo de atencion</th>
                <th>Diagnostico</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Efector</th>
                <th>Especialidad</th>
                <th>Profesional</th>
                <th>Apellido y Nombre</th>
                <th>Tipo y N°</th>
                <th>Sexo</th>
                <th>Edad</th>
                <th>Cobertura</th>                
                <th>Estado</th>
                <th>Fecha de Atencion</th>
                <th>Tipo de atencion</th>
                <th>Diagnostico</th>
            </tr>
        </tfoot>
 
        <tbody>
          <? 
          if ($_POST['buscar']=="Buscar"){
            
            $fecha_inicio_db=fecha_db($fecha_inicio);
            $fecha_hasta_db=fecha_db($fecha_hasta);
            $sql_tmp=  "SELECT nacer.agendas_eventos.id AS id_ag_evento,
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
                      uad.beneficiarios.sexo,
                      uad.beneficiarios.fecha_nacimiento_benef,
                      uad.beneficiarios.tipo_documento,
                      uad.beneficiarios.barrio,
                      uad.beneficiarios.numero_calle,
                      uad.beneficiarios.calle,
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
                      nacer.cepsap_items.codigo AS cesap1,
                      nacer.cepsap_items.descripcion as cesapdesc1,
                      nacer.cepsap_items_2016.codigo as cesapcod,
                      nacer.cepsap_items_2016.descripcion as cesapdesc,
                      nacer.diagnosticos.id_cesap2,
                      nacer.diagnosticos.prima_ves,
                      nacer.diagnosticos.ulterior
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
                    LEFT OUTER JOIN nacer.cepsap_items_2016 ON nacer.cepsap_items_2016.id = nacer.diagnosticos.id_cesap2";

                    if (!empty($id_efector) and !empty($id_especialidad) and !empty($id_medico) and !empty($fecha_inicio) and !empty($fecha_hasta)){
                         //echo "entro 1 ";
                        $where="  WHERE
                          (date (nacer.agendas_eventos.inicio) BETWEEN  '{$fecha_inicio_db}' AND '{$fecha_hasta_db}') and
                          nacer.agendas_eventos.id_efector = {$id_efector} AND 
                          nacer.agendas_eventos.estado != 'cancelado' AND
                          nacer.especialidades.id_especialidad = {$id_especialidad} AND 
                          nacer.medicos.id_medico = {$id_medico}
                          order by nacer.agendas_eventos.inicio ASC";
                    }elseif(!empty($id_efector) and !empty($id_especialidad) and !empty($fecha_inicio) and !empty($fecha_hasta)){
                         //echo "entro 2 ";
                        $where="  WHERE
                          (date (nacer.agendas_eventos.inicio) BETWEEN  '{$fecha_inicio_db}' AND '{$fecha_hasta_db}') and
                          nacer.agendas_eventos.id_efector = {$id_efector} AND 
                          nacer.agendas_eventos.estado != 'cancelado' AND
                          nacer.especialidades.id_especialidad = {$id_especialidad}  
                          order by nacer.agendas_eventos.inicio ASC";
                      }elseif(!empty($id_efector) and !empty($fecha_inicio) and !empty($fecha_hasta)){
                      //echo "entro 3 ";
                      $where="  WHERE
                        (date (nacer.agendas_eventos.inicio) BETWEEN  '{$fecha_inicio_db}' AND '{$fecha_hasta_db}') and
                        nacer.agendas_eventos.id_efector = {$id_efector} AND 
                        nacer.agendas_eventos.estado != 'cancelado' 
                        order by nacer.agendas_eventos.inicio ASC";

                      }elseif(!empty($fecha_inicio) and !empty($fecha_hasta)) {
                              //echo "entro 4 ";
                              $where="  WHERE
                                    (date (nacer.agendas_eventos.inicio) BETWEEN  '{$fecha_inicio_db}' AND '{$fecha_hasta_db}') and
                                    nacer.agendas_eventos.estado != 'cancelado' 
                                    order by nacer.agendas_eventos.inicio ASC";
                      }
                       //  echo "entro a el codigo  ";
                 
                $sql_tmp1=$sql_tmp.$where;
                $sql_pres=sql($sql_tmp1,"No se pueden mostrar los registros");

                $sql_pres->MoveFirst;
                   while (!$sql_pres->EOF){ ?>
                    <tr>
                        <td align="left"><?=$sql_pres->fields['efector_nombre'];?></td>
                        <td align="left"><?=$sql_pres->fields['especialidad_nombre'];?></td>
                        <td align="left"><?=$sql_pres->fields['medico_apellido'];?></td>
                        <td align="left"> <?=$sql_pres->fields['paciente_apellido'].', '.$sql_pres->fields['paciente_nombre'];?></td>
                        <td align="left" > <?=$sql_pres->fields['tipo_documento'].'-'.$sql_pres->fields['numero_doc']?></td>
                        <td align="left"> <?=$sql_pres->fields['sexo']?></td>
                        <td align="left"> <?=date("Y-m-d")-$sql_pres->fields['fecha_nacimiento_benef']?></td>
                        <td align="left"> <?=$sql_pres->fields['obra_social_nombre']?></td>
                     
                        <td align="left"> <?=$sql_pres->fields['estado']?></td>
                        <td align="left"> <?=fecha($sql_pres->fields['inicio']);?></td>
                        <td><a><?if($sql_pres->fields['prima_ves']==1)
                                    echo  "Primera vez";
                                  else
                                    echo  "Ulterrior";    
                        ?></a>
                         </td> 
                        <td><a><?if($sql_pres->fields['id_cepsap']!=0)
                            echo  $sql_pres->fields['id10'].'-'.$sql_pres->fields['grp10'].'-'.$sql_pres->fields['dec10'];// MUESTRA cepsap1?>
                            <?if($sql_pres->fields['id_cesap2']!=0)
                            echo  $sql_pres->fields['cesapcod'].'-'.$sql_pres->fields['cesapdesc'];// MUESTRA  CIE10
                            elseif($sql_pres->fields['id_cie10']!=0)
                        echo  $sql_pres->fields['cesap1'].'-'.$sql_pres->fields['cesapdesc1'];//muestra por CESAP
                        ?></a>
                         </td> 
                    </tr>
                  <?$sql_pres->MoveNext();
                }//while
      }?>
 </tbody>
    </table>
 </td></tr></table>
</table></div></td></tr>

<tr><td><div><table width=100%  align="center"  id=ma>
    <tr><td><div><table width=20%  align="center">

        <tr width=30%  align="center">
            <!--td>
              <?  //$ref = encode_link("planillat_pdf.php",array("id_efector"=>$id_efector, "fecha_inicio_db"=>$fecha_inicio_db,"id_especialidad"=>$id_especialidad,"id_medico"=>$id_medico));
              $onclick_elegir_aux//="location.href='$ref'";?>
               <!--img src='../../imagenes/pdf_logo.gif' style='cursor:hand;' onclick="<?=$onclick_elegir_aux?>"> 
            </td!-->
               <?$ref_planilla = encode_link("informe_estadistica_exc.php",array("sql_pres"=>$sql_pres));?>
            <td align="center">  
                  <a href="<?=$ref_planilla?>" title="Planilla de atencion medica"><IMG src='../../imagenes/excel.gif' ></a>
            </td>
    </table></div></td></tr>  
</table></div></td></tr>

</div>
 </form>

 <?=fin_pagina();// aca termino ?>