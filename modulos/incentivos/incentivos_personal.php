<?
require_once ("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);


  if (es_cuie($_ses_user['login'])) {
    require_once("efector.php");
    $disabled="disabled";
    $disabled_1='';
    
  }
  else {$cuie='';$disabled_1='disabled';}

  


echo $html_header;?>

<script src="app.js" type="text/javascript" charset="utf-8"></script>

<style type="text/css"> 
.modal-body {max-height: 900px;}
</style>

<body>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Nueva Entrada de Datos</h4>
        <h4>Efector: <input type="text" id="cuie" value="<?=$cuie?>" readonly></h4>
      </div>
      <div class="modal-body">
       <form>
          <div >
          <label for="dni">CUIL</label>
          <input type="text" class="form-control" id="dni" name="dni" placeholder="Ingrese CUIL sin guion ni puntos ni separaciones">
          </div>
          
          
          <div >
          <label for="nombre">Nombre</label>
          <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese Nombre">
          </div>
          
          <div >
          <label for="apellido">Apellido</label>
          <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese Apellido">
          </div>
          
          <div >
          <label for="estado">Estado</label>
          <select class="form-control" id="estado">
          <option value="activo">Activo</option>
          <option value="inactivo">Inactivo</option>
          </select>
          </div>
          
          <div >
          <label for="regimen">Regimen Laboral</label>
          <select class="form-control" id="regimen" name="regimen">
          <option value=-1>Seleccione</option>
          <option value="Convenio Colectivo 122/75">Convenio Colectivo 122/75</option>
          <option value="Carrera Sanitaria">Carrera Sanitaria</option>
          <option value="Escalafon General">Escalafon General</option>
          <option value="Plan Inclusion">Plan Inclusion</option>
          <option value="Pasante">Pasante</option>
          <option value="Practica Laboral Rentada">Practica Laboral Rentada</option>
          <option value="Categoria F">Categoria F</option>
          </select>
          </div>
          
          <div >
          <label for="funcion">Funcion</label>
          <select class="form-control" id="funcion" name="funcion">
          <option value=-1>Seleccione</option>
          <option value="Administrativo">Administrativo</option>
          <option value="Especialidad">Especialidad</option>
          <option value="Mantenimiento">Mantenimiento</option>
          </select>
          </div>
          
          <div id="especialidad-div"></div>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" name="guardar_cambios" class="btn btn-primary">Guardar Cambios
        </button>
      </div>
    </div>
  </div>
</div>


<div>
<div>
<div class="center">

<table width="98%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">

<tr>
<div id="efector">

<td align="center">
 <b>Efector:</b>
    <select id="id_efector" <?=$disabled?>>
    <option value=-1>Seleccione un EFECTOR</option>
              
<?$sql= "SELECT * from nacer.efe_conv order by cuie";
  $res_efectores=sql($sql) or fin_pagina();
  while (!$res_efectores->EOF){ 
    $id_efector=$res_efectores->fields['id_efe_conv'];
    $nombre_efector=$res_efectores->fields['nombre'];
    //$cuie=$res_efectores->fields['cuie'];
    ?>
    <option value="<?=$id_efector?>"><?=$res_efectores->fields['cuie']." - ".$nombre_efector?></option>
    <?
    $res_efectores->movenext();
    };
    $id_efector=0;?>
  </select>
</td>
</div>
</tr>

<tr><td>
<table width=100% align="center" class="bordes">
<tr>
<td id=mo colspan="5">
<b> Descripcion del Efector</b>
</td>
</tr>
<tr>
<td>
<table align="center">
  
<td align="right">
<b>Nombre:</b>
</td>
<td align="left">    
<input type="text" size="40" value="<?=$nombre?>" id="nombre_efector" name="nombre_efector" readonly>
</td>
</tr>

<tr>            

<tr>
<td align="right">
<b>Domicilio:</b>
</td>
<td align="left">    
<input type="text" size="40" value="<?=$domicilio?>" id="domicilio" name="domicilio" readonly>
</td>
</tr>

<tr>
<td align="right">
<b>Departamento:</b>
</td>
<td align="left">    
<input type="text" size="40" value="<?=$departamento?>" id="departamento" name="departamento" readonly>
</td>
</tr>

<tr>
<td align="right">
<b>Localidad:</b>
</td>
<td align="left">    
<input type="text" size="40" value="<?=$localidad?>" id="localidad" name="localidad" readonly>
</td>
</tr>
</table>
</td>      
<td>
<table align="center">        
<tr>
<td align="right">
<b>Codigo Postal:</b>
</td>
<td align="left">    
<input type="text" size="40" value="<?=$cod_pos?>" id="cod_pos" name="cod_pos" readonly>
</td>
</tr>

<tr>
<td align="right">
<b>Cuidad:</b>
</td>
<td align="left">    
<input type="text" size="40" value="<?=$cuidad?>" id="cuidad" name="cuidad" readonly>
</td>
</tr>

<tr>
<td align="right">
<b>Referente:</b>
</td>
<td align="left">    
<input type="text" size="40" value="<?=$referente?>" id="referente" name="referente" readonly>
</td>
</tr>

<tr>
<td align="right">
<b>Telefono:</b>
</td>
<td align="left">    
<input type="text" size="40" value="<?=$tel?>" id="tel" name="tel" readonly>
</td>
</tr>          
</table>
</td>  

</tr> 
           
 </table>        
 <table border="1">     
    <div class="container" align="center">
      <button type="button" class="btn btn-primary btn-sm" id="ver_listado" name="ver_listado" <?=$disabled_1?>>
      Ver Listado
      </button>
      <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal" id="nuevo_dato" <?=$disabled_1?>>
      Nuevo Dato
      </button>
      
        
    </div>
  </div>
 </div>
</div>
</table>

<table class="bordes" align="center" width="86%" bordercolor=#E0E0E0 border="solid 1px ">
     <tr align="center" id="sub_tabla">
     <td colspan="2"> 
        Personal
     </td>
     </tr>
     <tr id="personal">
     <td align="center"></td>
     </tr> 
</table>

</body>
</html>
<?echo fin_pagina();?>