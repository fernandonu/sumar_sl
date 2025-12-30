<?
 
require_once ("../../config.php");

$op=$_POST['op'];
$cuie=$_POST['cuie'];

if ($op=='Especialidad'){?>
  
  <label for="especialidad">Especialidad</label>
  <select class='form-control' id='especialidad'>
  <option value=-1>Seleccione</option>
  <?$sql_esp='select * from nacer.especialidades order by 2';
   $res_esp=sql($sql_esp) or fin_pagina();
   while (!$res_esp->EOF){?>
  <option value='<?=$res_esp->fields['nom_titulo']?>'><?=$res_esp->fields['nom_titulo']?></option>
  <?$res_esp->MoveNext();
  }?>
  </select>
<?}

if ($op=='Mantenimiento'){?>
  
  <label for="mantenimiento">Mantenimiento</label>
  <select class='form-control' id='mantenimiento'>
  <option value=-1>Seleccione</option>
  <option value='Mucama'>Mucama</option>
  <option value='Portero'>Portero</option>
  <option value='Chofer'>Chofer</option>
  <option value='Asistente Social'>Asistente Social</option>
  <option value='Otros No-Profesional'>Otros No-Profesional</option>
  </select>
<?}

if ($op=='Administrativo'){?>
  
  <!--<label for="especialidad">No hay especiliadades para la funcion Administrativa</label>-->
  
<?}

if ($op=='imprimir_listado'){
  $cuie=$_POST['cuie'];
  
  $ref=encode_link("incentivos_excel.php",array("cuie"=>$cuie));
  //echo "<SCRIPT>window.location='$ref';</SCRIPT>";?>
  
  <script>window.open('<?=$ref?>')</script>
  
<?}

if ($op=='ver_listado'){
  
    $sql = "SELECT * from personal.incentivos where cuie='$cuie'";
    $res_sql= sql($sql) or fin_pagina();
    ?>

    <script>
    $("button[name='guardar_']").click( function () {
      
        var id=$(this).val();
        console.log(id);
        
        var nombre=$("#"+id+"nombre").val();
        var apellido=$("#"+id+"apellido").val();
        var estado=$("#"+id+"estado").val();
        var reg_lab=$("#"+id+"regimen").val();
        var funcion=$("#"+id+"funcion").val();
        var dni=$("#"+id+"dni").val();
        var op = 'modif';
        
        /*console.log(reg_lab);
        console.log(funcion);*/
                
          $.ajax({
            data: {op:op,
                  id:id,
                  dni:dni,
                  nombre: nombre,
                  apellido: apellido,
                  estado: estado,
                  reg_lab: reg_lab,
                  funcion: funcion},
            type: "POST",
            dataType: "json",
            url: "alta_modif.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
               alert(data['mensaje']);
               $(myModal).modal('hide');
               location.reload();
                           
          }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado: " +  textStatus);
          }
      });//fail
  });


  $("button[name='borrar']").click( function () {
      
        if(confirm("Se ELIMINARA el registro, esta seguro?")) {

                var id=$(this).val();
                var op = 'borrar';
                               
                  $.ajax({
                    data: {op:op,
                          id:id},
                    type: "POST",
                    dataType: "json",
                    url: "alta_modif.php",
                  })
              .done(function( data, textStatus, jqXHR ) {
                  if ( console && console.log ) {
                       console.log( "La solicitud se ha completado correctamente.");
                       alert(data['mensaje']);
                       $(myModal).modal('hide');
                       location.reload();
                                   
                  }
              })//done
              .fail(function( jqXHR, textStatus, errorThrown ) {
                  if ( console && console.log ) {
                      console.log( "La solicitud a fallado: " +  textStatus);
                  }
              });//fail
        }      
  });  
  </script>
    
  <table>
  <?$link=encode_link("incentivos_excel.php",array("cuie"=>$cuie));?>
  <tr>
  <td align="center" class="bordes">
  <button type="button" class="btn btn-success btn-sm" name="reporte_excel" onclick="window.open('<?=$link?>')">
      Imprimir Listado
  </button>
  </td>
  </tr> 
  </table>
    
    <td class="bordes">
    <table class="table table-striped"  width="100%" cellspacing="0" cellpadding="0" >
    <tr>
    <td align="center" width="10%" id="mo">CUIL</td>          
    <td align="center" width="30%" id="mo">Nombre</td>            
    <td align="center" width="30%" id="mo">Apellido</td>           
    <td align="center" width="5%" id="mo">Estado</td>
    <td align="center" width="20%" id="mo">Regimen Laboral</td>
    <td align="center" width="20%" id="mo">Funcion</td>
    <td align="center" width="20%" id="mo">Especialidad</td>
    <td align="center" width="20%" id="mo">Funcion No-Profesional</td>
    <td align="center" width="5%" id="mo">Editar Datos</td>
    </tr>
  
  <?while(!$res_sql->EOF){
    $id_inc=$res_sql->fields['id_incentivos'];
    $activo=$res_sql->fields['activo'];
    $reg_lab=$res_sql->fields['regimen_laboral'];
    $funcion=$res_sql->fields['funcion'];
    ?>
  
  <!-- Modal -->
<div class="modal fade" id="myModal<?=$id_inc?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Editar Datos</h4>
        <hidden id="id_inc" value="<?=$id_inc?>"></hidden>
  <!--  <h4>Personal: <input type="text" id="id_inc" value="<?=$id_inc?>" readonly></h4>-->
        
      </div>
      <div class="modal-body">
       <form>
          <div >
          <label for="dni">Cuil (Sin guion ni puntos separadores)</label>
          <input type="text" class="form-control" id="<?=$id_inc?>dni" value='<?=$res_sql->fields['dni']?>'
          placeholder="Ingrese CUIL sin guion ni puntos ni separaciones">
          </div>
          
          
          <div >
          <label for="nombre">Nombre</label>
          <input type="text" class="form-control" id="<?=$id_inc?>nombre" value='<?=$res_sql->fields['nombre']?>' readonly>
          </div>
          
          <div >
          <label for="apellido">Apellido</label>
          <input type="text" class="form-control" id="<?=$id_inc?>apellido"value='<?=$res_sql->fields['apellido']?>' readonly>
          </div>
          
          <div >
          <label for="estado">Estado</label>
          <select class="form-control" id="<?=$id_inc?>estado">
          <?if ($activo=='S') {?>
            <option value="activo" selected>Activo</option>
            <option value="inactivo">Inactivo</option>
          <?}
          else {?>
            <option value="activo">Activo</option>
            <option value="inactivo" selected>Inactivo</option>
          <?}?>
          </select>
          </div>
          
          <div >
          <label for="regimen">Regimen Laboral</label>
          <select class="form-control" id="<?=$id_inc?>regimen">
          <?
          switch ($reg_lab) {
            case 'Convenio Colectivo 122/75':{?>
              <option value="Convenio Colectivo 122/75" selected>Convenio Colectivo 122/75</option>
              <option value="Carrera Sanitaria">Carrera Sanitaria</option>
              <option value="Escalafon General">Escalafon General</option>
              <option value="Plan Inclusion">Plan Inclusion</option>
              <option value="Pasante">Pasante</option>
              <option value="Practica Laboral Rentada">Practica Laboral Rentada</option>
              <option value="Categoria F">Categoria F</option>
              <?};break;
            case 'Carrera Sanitaria':{?>
              <option value="Convenio Colectivo 122/75" >Convenio Colectivo 122/75</option>
              <option value="Carrera Sanitaria" selected>Carrera Sanitaria</option>
              <option value="Escalafon General">Escalafon General</option>
              <option value="Plan Inclusion">Plan Inclusion</option>
              <option value="Pasante">Pasante</option>
              <option value="Practica Laboral Rentada">Practica Laboral Rentada</option>
              <option value="Categoria F">Categoria F</option>
              <?};break;
            case 'Escalafon General':{?>
              <option value="Convenio Colectivo 122/75" >Convenio Colectivo 122/75</option>
              <option value="Carrera Sanitaria">Carrera Sanitaria</option>
              <option value="Escalafon General" selected>Escalafon General</option>
              <option value="Plan Inclusion">Plan Inclusion</option>
              <option value="Pasante">Pasante</option>
              <option value="Practica Laboral Rentada">Practica Laboral Rentada</option>
              <option value="Categoria F">Categoria F</option>
              <?};break;
            case 'Plan Inclusion':{?>
              <option value="Convenio Colectivo 122/75">Convenio Colectivo 122/75</option>
              <option value="Carrera Sanitaria">Carrera Sanitaria</option>
              <option value="Escalafon General">Escalafon General</option>
              <option value="Plan Inclusion" selected>Plan Inclusion</option>
              <option value="Pasante">Pasante</option>
              <option value="Practica Laboral Rentada">Practica Laboral Rentada</option>
              <option value="Categoria F">Categoria F</option>
              <?};break;
            case 'Pasante':{?>
              <option value="Convenio Colectivo 122/75">Convenio Colectivo 122/75</option>
              <option value="Carrera Sanitaria">Carrera Sanitaria</option>
              <option value="Escalafon General">Escalafon General</option>
              <option value="Plan Inclusion">Plan Inclusion</option>
              <option value="Pasante" selected>Pasante</option>
              <option value="Practica Laboral Rentada">Practica Laboral Rentada</option>
              <option value="Categoria F">Categoria F</option>
              <?};break;
            case 'Practica Laboral Rentada':{?>
              <option value="Convenio Colectivo 122/75">Convenio Colectivo 122/75</option>
              <option value="Carrera Sanitaria">Carrera Sanitaria</option>
              <option value="Escalafon General">Escalafon General</option>
              <option value="Plan Inclusion">Plan Inclusion</option>
              <option value="Pasante">Pasante</option>
              <option value="Practica Laboral Rentada" selected>Practica Laboral Rentada</option>
              <option value="Categoria F">Categoria F</option>
              <?};break;
              
          }?>
          
          
          </select>
          </div>
          
          <div >
          <label for="funcion">Funcion</label>
          <input type="text" class="form-control" id="<?=$funcion?>" value='<?=$res_sql->fields['funcion']?>' readonly>
          </div>
          
          <!--<select class="form-control" id="<?=$id_inc?>funcion">
          <?if ($funcion=='Administrativo'){?>
            <option value="Administrativo" selected>Administrativo</option>
            <option value="Especialidad">Especialidad</option>
            <option value="Mantenimiento">Mantenimiento</option>
          <?} elseif ($funcion=='Especialidad') {?>
            <option value="Administrativo">Administrativo</option>
            <option value="Especialidad" selected>Especialidad</option>
            <option value="Mantenimiento">Mantenimiento</option>
          <?} else {?>
            <option value="Administrativo">Administrativo</option>
            <option value="Especialidad">Especialidad</option>
            <option value="Mantenimiento" selected>Mantenimiento</option>
            <?}?>
          </select>
          </div>
          
          <div id="especialidad-div"></div>-->
          
</form>
      </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
        <button type="button" name="guardar_" value="<?=$id_inc?>" class="btn btn-primary">Guardar Cambios
        <button type="button" name="borrar" value="<?=$id_inc?>" class="btn btn-danger">Borrar Registro
        </button>
      </div>
    </div>
  </div>
</div>
  
  
  <tr>
  
  <?if (strlen($res_sql->fields['dni'])==11) 
        $cuil=substr($res_sql->fields['dni'],0,2).'-'.substr($res_sql->fields['dni'],2,8).'-'.substr($res_sql->fields['dni'],10,10);
    
    elseif (strlen($res_sql->fields['dni'])==10) 
        $cuil=substr($res_sql->fields['dni'],0,2).'-'.substr($res_sql->fields['dni'],2,7).'-'.substr($res_sql->fields['dni'],9,9);

    else $cuil=$res_sql->fields['dni'];?>

  <td align="center" width="10%" ><?=$cuil?></td>
  <td align="left" width="25%" ><?=$res_sql->fields['nombre']?></td>
  <td align="left" width="25%" ><?=$res_sql->fields['apellido']?></td>
  <td align="center" width="5%" ><?=$res_sql->fields['activo']?></td>
  <td align="center" width="20%" ><?=$res_sql->fields['regimen_laboral']?></td>
  <td align="center" width="20%" ><?=$res_sql->fields['funcion']?></td>
  <td align="center" width="20%" ><?=$res_sql->fields['especialidad']?></td>
  <td align="center" width="20%" ><?=$res_sql->fields['mantenimiento']?></td>
  <td align="center"><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal<?=$id_inc?>" name="boton_modal" value="<?=$id_inc?>" > 
      Modf.</button></td>
   </tr>
  <?$res_sql->MoveNext(); 
    
  }?>
  </table>
 
  
  
<?}
?>