<?php
require_once("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$users=$_ses_user['name'];
$usuario=new user($_ses_user['login']);
$id=$usuario->get_id_usuario();
$id_user=$usuario->get_id_usuario();

echo $html_header;

?>
<form name=form1 action="listado_pacientes.php" method=POST>
<tr><td><div><table width=100%  align="center">

<div class="row-fluid" align="center">
    <div class="span12">
       <input type=text id=buescar name=buescar value='<?=$buescar?>' size=15 title="Criterio de Busqueda">
       &nbsp;&nbsp;<input class="btn" type=submit name="buscar" value='Buscar'>         
            <?  $link_nuevo_paciente = encode_link("$html_root/modulos/inscripcion/ins_admin_old.php",array("pagina_viene_2"=>"$html_root/modulos/entrega_leche/listado_pacientes.php"));?>
       &nbsp;&nbsp;<input type='button' name="nuevo" value='Nuevo Paciente' class="btn btn-info btn-sm" onclick="document.location='<?php echo $link_nuevo_paciente; ?>';">         
    </div>
</div>

<tr><td><div><table width=100% class="btn btn-success" align="center">
    <tr align="center">
      <td>
          <font size=+1><b>Listado de Beneficiario</b></font>           
      </td>
    </tr>
</table></div></td></tr>

      <link rel="stylesheet" type="text/css" href="<?php echo $html_root?>/lib/jquery/jquery.dataTables.min.css" />
      <script src="<?php echo $html_root?>/lib/jquery/jquery.dataTables.min.js"></script>
      <script>

      $(document).ready(function() {
          $('#prestacion').DataTable( {
              
              "columnDefs": [
                { "width": "10%" },
                { "width": "10%" },
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
            <th> <i class="glyphicon glyphicon-user"></i> Efector de entrega</th>    
            <th> <i class="glyphicon glyphicon-user"></i> Apellido</th>       
            <th> <i class="glyphicon glyphicon-user"></i> Nombre</th>      
            <th> <i class="glyphicon glyphicon-user"></i> DNI</th> 
            <th> <i class="glyphicon glyphicon-user"></i> Edad</th>   
            <th> <i class="glyphicon glyphicon-user"></i> Domicilio</th> 
            <th> <i class="glyphicon glyphicon-user"></i> Editar Persona</th>
            <th> <i class="glyphicon glyphicon-user"></i> Nuevo</th>  
            <th> <i class="glyphicon glyphicon-user"></i> Entrega</th>  
          </tr>
            </tr>
        </thead>
        <tfoot>
            <tr>
              <th> <i class="glyphicon glyphicon-user"></i> Efector de entrega</th>   
              <th> <i class="glyphicon glyphicon-user"></i> Apellido</th>       
              <th> <i class="glyphicon glyphicon-user"></i> Nombre</th>      
              <th> <i class="glyphicon glyphicon-user"></i> DNI</th> 
              <th> <i class="glyphicon glyphicon-user"></i> Edad</th>   
              <th> <i class="glyphicon glyphicon-user"></i> Domicilio</th> 
              <th> <i class="glyphicon glyphicon-user"></i> Editar Persona</th>
              <th> <i class="glyphicon glyphicon-user"></i> Nuevo</th>  
              <th> <i class="glyphicon glyphicon-user"></i> Entrega</th>
            </tr>
        </tfoot>
 
        <tbody>
          <? 
if ($buescar!=''){
  $fecha_desde=fecha_db($fecha_desde);
  $fecha_hasta=fecha_db($fecha_hasta);
     $sql_tmp="SELECT DISTINCT
                          uad.beneficiarios.id_beneficiarios, 
                          uad.beneficiarios.clave_beneficiario, 
                          uad.beneficiarios.apellido_benef, 
                          uad.beneficiarios.nombre_benef, 
                          uad.beneficiarios.fecha_nacimiento_benef, 
                          uad.beneficiarios.fecha_inscripcion, 
                          uad.beneficiarios.numero_doc, 
                          uad.beneficiarios.calle,
                          uad.beneficiarios.numero_calle,
                          uad.beneficiarios.piso,
                          uad.beneficiarios.dpto,
                          uad.beneficiarios.manzana,
                          uad.beneficiarios.entre_calle_1,
                          uad.beneficiarios.entre_calle_2,
                          uad.beneficiarios.telefono,
                          uad.beneficiarios.barrio, 
                          nacer.efe_conv.nombre 
                          FROM
                          uad.beneficiarios
                          LEFT OUTER JOIN leche.formula_inicio ON leche.formula_inicio.id_benuad = uad.beneficiarios.id_beneficiarios 
                          LEFT OUTER JOIN leche.detalle_leche ON  leche.detalle_leche.id_benuad = uad.beneficiarios.id_beneficiarios
                          LEFT OUTER JOIN nacer.efe_conv ON nacer.efe_conv.cuie = leche.detalle_leche.cuie AND leche.detalle_leche.cuie = nacer.efe_conv.cuie AND nacer.efe_conv.cuie = leche.formula_inicio.cuie
                        where (uad.beneficiarios.numero_doc ILIKE ('%$buescar%') OR uad.beneficiarios.apellido_benef ILIKE ('%$buescar%') OR uad.beneficiarios.nombre_benef ILIKE ('%$buescar%')) ";

 
      $result=sql($sql_tmp,"No se pueden mostrar los registros");
   while (!$result->EOF) {
       $id_benfuad=$result->fields['id_beneficiarios'];
    ?>
    <tr>   
         <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre']?></td>   
         <td onclick="<?=$onclick_elegir?>"><?=$result->fields['apellido_benef']?></td>
         <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre_benef']?></td>    
         <td onclick="<?=$onclick_elegir?>"><?=$result->fields['numero_doc']?></td>    
         <td onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['fecha_nacimiento_benef'])?></td> 
         <td onclick="<?=$onclick_elegir?>"><?=$result->fields['barrio'].', calle:'.$result->fields['calle'].', Nro:'.$result->fields['numero_calle'].', Piso:'.$result->fields['piso'].', Dpt: '.$result->fields['dpto'].', Mza:'.$result->fields['manzana']?>  
         </td> 
          
          <td><? 
              $link3= encode_link("$html_root/modulos/inscripcion/ins_admin_old.php",array("pagina_viene_2"=>encode_link("$html_root/modulos/entrega_leche/listado_pacientes.php", array("pagina" => $listado_pacientes)), "id_planilla"=>$result->fields['id_beneficiarios']));?>
              <a href="<?=$link3?>" title="Editar Paciente"><IMG src='<?=$html_root?>/imagenes/iso.jpg' height='30' width='30' border='0'></IMG></a>
          </td>
          
            <?  $link02 = encode_link("solicitar_entrega.php",array("id_benuad"=>$result->fields['id_beneficiarios']));?>
              
          <td>&nbsp;&nbsp;<input type='button' name="alta" value='Alta' class="btn btn-info btn-sm" onclick="document.location='<?php echo $link02; ?>';">
          </td>

      
            <? $link01= encode_link("registrar_entrega.php",array("id_benuad"=>$result->fields['id_beneficiarios'],"pagina"=>"listado_pacientes.phps"));?>
            <td><button type="button" class="btn btn-info btn-sm"  name="entrega" onclick="<?=$link01?>">Registrar Entrega</button>
            </td>
    </tr>    
  <?$result->MoveNext();
    }
    }?>
</tbody>
</tr></tbody></table></td></tr></table></div></td></tr>
</form>

<?echo fin_pagina();// aca termino ?>