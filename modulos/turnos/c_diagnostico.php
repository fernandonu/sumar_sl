<?php
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
echo $id_user=$_ses_user['id'];
$usuario=$_ses_user['name'];
	
if ($_POST['guardar']=='Guardar'){
	$db->StartTrans();
 if ($nomenclador_id_val== "CIE10") {
        $id_cie10=$nomenclador_id; 
        $id_cepsap=0;
      }
      else{ 
        $id_cepsap=$nomenclador_id; 
        $id_cie10=0;
      }
      if($consulta== 1) {
          $prima_ves=1; $ulterior=0;
        }else{ 
          $ulterior=1; $prima_ves=0;
        }
  //verifico si ya posee algun diagnostico cargado
  $query_verif="SELECT * from nacer.diagnosticos
                WHERE id_turno=$id_turno";
  $res_queryverif=sql($query_verif, 'generado al traer valores de diagnostico')or die();
  if($res_queryverif->EOF){
    		    $q="select nextval('nacer.diagnosticos_id_seq') as id_serie";
    		    $id_serie=sql($q) or fin_pagina();
    		    $id_serie=$id_serie->fields['id_serie']; 
    		
    		    $query="insert into nacer.diagnosticos
    				   	(id, id_turno,id_cie10, id_cepsap, fecha_atencion, prima_ves, ulterior)
    				   	values
    				   	($id_serie,$id_turno,'$id_cie10', '$id_cepsap', '$fecha_inicio_db', '$prima_ves', '$ulterior')";
    			
    		   sql($query, "Error consulta 03") or fin_pagina();
           $query2="UPDATE nacer.agendas_eventos SET estado = 'presente' WHERE id =$id_turno";
           $res_update = sql($query2) or die();
           $accion="Los datos se han guardado correctamente"; 
}else {
        $query="update nacer.diagnosticos set
                id_cie10='$id_cie10',
                id_cepsap='$id_cepsap', 
                prima_ves='$prima_ves', 
                ulterior='$ulterior'
                Where id_turno='$id_turno'";
          
           sql($query, "Error consulta 03") or fin_pagina();
        $query2="UPDATE nacer.agendas_eventos SET estado = 'presente' WHERE id =$id_turno";       
      }      
 $db->CompleteTrans();  
  
}

if ($_POST['ausente']=='Ausente'){
    $verif="SELECT
              id_cie10,
              id_cepsap,
                * FROM nacer.diagnosticos
              where id_turno=$id_turno";
    $res_v=sql($verif,"al verificar codificacion") or die();

    if($res_v->EOF){

              $query="UPDATE nacer.agendas_eventos SET estado = 'ausente' WHERE id =$id_turno";
                
              $res_update = sql($query) or die(-1);
              echo "Se registro como ausente";
              $db->CompleteTrans();  
   ?> 

   <script type="text/javascript">
       window.opener.location.reload();
      window.close();
   </script>

<?php    
    }else echo "El paciente posee un diagnostico, no puede quedar ausente";
}
   
if ($id_turno) {
            $query="SELECT DISTINCT 
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
                    nacer.diagnosticos.id as id_serie,
                    nacer.diagnosticos.id_cie10,
                    nacer.diagnosticos.id_cepsap,
                    nacer.diagnosticos.prima_ves,
                    nacer.diagnosticos.ulterior
                  FROM
                    nacer.agendas_eventos
                    LEFT OUTER JOIN nacer.efe_conv ON (nacer.agendas_eventos.id_efector = nacer.efe_conv.id_efe_conv)
                    LEFT OUTER JOIN uad.beneficiarios ON (nacer.agendas_eventos.id_paciente = uad.beneficiarios.id_beneficiarios)
                    LEFT OUTER JOIN nacer.diagnosticos ON (nacer.diagnosticos.id_turno = nacer.agendas_eventos.id)
                    LEFT OUTER JOIN nacer.agendas ON (nacer.agendas_eventos.id_agenda = nacer.agendas.id)
                    LEFT OUTER JOIN nacer.especialidades_medicos ON (nacer.agendas.id_especialidad_medico = nacer.especialidades_medicos.id)
                    LEFT OUTER JOIN nacer.medicos ON (nacer.especialidades_medicos.id_medico = nacer.medicos.id_medico)
                    LEFT OUTER JOIN nacer.obras_sociales ON (nacer.agendas_eventos.id_obra_social = nacer.obras_sociales.id_obra_social)
                    LEFT OUTER JOIN nacer.especialidades ON (nacer.especialidades.id_especialidad = nacer.especialidades_medicos.id_especialidad)
                    LEFT OUTER JOIN nacer.cie10 ON nacer.cie10.id10 = nacer.diagnosticos.id_cie10
                    LEFT OUTER JOIN nacer.cepsap_items ON nacer.cepsap_items.id = nacer.diagnosticos.id_cepsap
                  WHERE
                    nacer.agendas_eventos.id=$id_turno and nacer.agendas_eventos.estado!='cancelado'";
                           

$res_hosp=sql($query, "Error al traer el Comprobantes") or fin_pagina();
$fecha_inicio=$res_hosp->fields['inicio'];
$paciente_apellido=$res_hosp->fields['paciente_apellido'];
$paciente_nombre=$res_hosp->fields['paciente_nombre'];

if ($res_hosp->fields['id_cie10']==0){
  echo ($nomenclador == "CEPSAP") ? ' active"' : ''; 
  $nomenclador_id=$res_hosp->fields['id_cepsap'];
}elseif($res_hosp->fields['id_cepsap']==0){
  echo ($nomenclador == "CIE10") ? ' active"' : ''; 
  $nomenclador_id=$res_hosp->fields['id_cie10'];
}
//$cesap=$res_hosp->fields['cesap'];
$id_efector=$res_hosp->fields['id_efector'];
$id_especialidad=$res_hosp->fields['id_especialidad'];
$id_medico=$res_hosp->fields['id_medico'];
$medico_user=$res_hosp->fields['medico_user'];
$numero_doc=$res_hosp->fields['numero_doc'];
$ulterior=$res_hosp->fields['ulterior'];
$prima_ves=$res_hosp->fields['prima_ves'];
if ($prima_ves==1) $consulta=1;
else $consulta=2;
}
$id_serie=$res_hosp->fields['numero_doc'];
echo $html_header;
?>

<script type="text/javascript">
  $(document).ready(function() {
    $("#nomenclador_cie10, #nomenclador_cepsap").on('change', function() {
      $('#nomenclador_label').html($(this).val());
      $('#nomenclador_codigo').prop('placeholder', 'Ingrese el código o descripción de '+$(this).val());
      $('#nomenclador_id').val('');
      $('#nomenclador_codigo').val('');
      $('#nomenclador_id_val').val($(this).val());
      nomenclador_autocomplete();
    });
  });

  var url_nomenclador_cie10 = '<?php echo encode_link("cargar_diagnostico_datos.php", array("accion" => "cie10_autocomplete")); ?>';
  var url_nomenclador_cepsap = '<?php echo encode_link("cargar_diagnostico_datos.php", array("accion" => "cepsap_autocomplete")); ?>';

  function nomenclador_autocomplete() {
   var url = '';
   if ($("#nomenclador_cie10").is(":checked")) {
      url = url_nomenclador_cie10;
    }
    else if ($("#nomenclador_cepsap").is(":checked")) {
      url = url_nomenclador_cepsap;
    }
    $( "#nomenclador_codigo" ).autocomplete({
      source: url,
      minLength: 2,
      select: function( event, ui ) {
        if (ui.item) {
          $('#nomenclador_id').val(ui.item.id);
                            
        }
        else {
          $('#nomenclador_codigo').val("");
        }
      }
    });
  }

  function control_nuevos() {
    if(!$('#nomenclador_id').val()){
      BootstrapDialog.alert('Debe seleccionar un nomenclador e ingresar un diagnostico');
      return false;
    } 
    return true;
  }

    $('#btn-cancelar').on('click', function() {
      $.ajax({
        url: '<?php echo encode_link("cargar_diagnostico_datos.php", array("accion" => "cancelar_turno")); ?>',
        type: 'POST',
        data: 'id_turno='+$('#id_turno').val(),
        success: function(resultado) {
          if (resultado == 1) {
            BootstrapDialog.alert("El turno fu&eacute; cancelado correctamente");
            $('#calendar').fullCalendar( 'refetchEvents' );
          }
          else {
            BootstrapDialog.alert("El turno no se pudo cancelar");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(xhr.status + " "+ thrownError);
        }
      });
    });

</script>

<form name='form1' action='c_diagnostico.php' method='POST'>
<input type="hidden" value="<?=$id_turno?>" name="id_turno">
<input type="hidden" value="<?=$fecha_inicio_db?>" name="fecha_inicio_db">
<table width="95%" cellspacing=0   align="center"  >
    <table width="100%" cellspacing=0   align="center" class="btn btn-primary" >
     <tr  >
        <td>
        	<?
        	if ($id_turno) {
        	?>  
        	  <font size=+1><b><?  echo $paciente_apellido.', '.$paciente_nombre;?></b></font>   
            <? } ?>
           
        </td>
     </tr>
    </tr></table></div></td></tr>

<div class="container" style="width:100%; height:100%;">
  <br/>
    <?php //if(!isset($nomenclador)) $nomenclador = "CEPSAP"; ?>
    <div class="col-sm-12">
      <b>Diagnostico</b>
      <div class="row">
        <div class="col-sm-3">
          <div class="btn-group btn-group-justified btn-group-sm" role="group" data-toggle="buttons">
             <label class="btn btn-primary<?php echo ($nomenclador == "CEPSAP") ? ' active"' : ''; ?>">
                <input type="radio" id="nomenclador_cepsap" name="nomenclador" autocomplete="off" value="CEPSAP"<?php echo ($nomenclador == "CEPSAP") ? ' checked="checked"' : ''; ?> /> CEPSAP
             </label> 
             <? if(permisos_check('hospital','hospital_primernivel')){ ?>
             <label class="btn btn-primary<?php echo ($nomenclador == "CIE10") ? ' active"' : ''; ?>">
                <input type="radio" id="nomenclador_cie10" name="nomenclador" autocomplete="off" value="CIE10"<?php echo ($nomenclador == "CIE10") ? ' checked="checked"' : ''; ?> /> CIE10
             </label> 
             
             <?}else{  $nomenclador = "CEPSAP"; } ?>
          </div>
        </div>
        <div class="col-sm-9">
          <input type="hidden" name="nomenclador_id" id="nomenclador_id" value="<?php echo $nomenclador_id; ?>" />
          <input type="hidden" name="nomenclador_id_val" id="nomenclador_id_val" value="" />
          <input type="text" name="nomenclador_codigo" class="form-control" id="nomenclador_codigo" placeholder="Ingrese el c&oacute;digo o descripci&oacute;n" value="<?php echo $nomenclador_codigo; ?>">
        </div>
      </div>
    </div>
  <br/>
 
        
          <tr><td><div><table width=100%  >
              <td align="right">
    	         	<b>Tipo de atencion:</b>
    	        </td> 
    	        <td align="left">			 
                  <input  name="consulta"  type="radio" value="1" checked>Primera vez
                  <input  name="consulta"  type="radio" value="2" >Ulterior
    	        </td>
             </tr>   
      </tr></table></div></td></tr>

    <br>
   	
    	<tr><td><table width=100% align="center" class="bordes">
    	 	 	<tr>
    		    <td align="center">
              <input type="submit" name="ausente" value="Ausente" title="Ausente"class="btn btn-danger" data-dismiss="modal" >
              <? if($id_user==$medico_user or permisos_check('dignosticar','dignosticar_paciente')){ ?>
      		      <input type="submit" name="guardar" value="Guardar" title="Guardar" class="btn btn-primary" data-dismiss="modal" onclick="return control_nuevos()">&nbsp;&nbsp;
              <?} ?>
             </td>
    	  </tr>
    	  <tr>
            &nbsp;&nbsp;
            &nbsp;&nbsp;
              
             </td>
        </tr>
<?
 if($id_serie){   ?> 	
          <tr>
                <?$ref_leche = encode_link("../fichero/comprobante_fichero.php",array("id"=>$result->fields['id'],"entidad_alta"=>$result->fields['f']));?>
                <td align="center">  <a href="<?=$ref_leche?>" title="Fichero Cronologico"><IMG src='<?=$html_root?>/imagenes/iso.jpg' height='20' width='20' border='0'></a></td>
            <? if($id_user==$medico_user or permisos_check('sip_wev','carga_sipweb')){ 
                  if( $result->fields['sexo']=='F' && $edad>=10 && $edad<=55 ) {?>
                  <a title="Ir a Carga del SIP"
                     href="<?php echo encode_link("$html_root/modulos/sip/ficha_sip.php",array("clavebeneficiario"=>$result->fields['clave_beneficiario'])); ?>" >
                    <img src="<?php echo $html_root; ?>/imagenes/logosip.gif" width="20" height="20"/>
                  </a>
                <? } 
              } ?>
                
        </tr>
      
 <?} ?> 
 </table></td></tr>         
</table></form>
<?=fin_pagina();// aca termino ?>


   <--script type="text/javascript">
      window.opener.location.reload();
      window.close();
   <--/script-->

