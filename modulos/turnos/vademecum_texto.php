<?php
require_once("../../config.php");

$sql_tmp = "SELECT * FROM nacer.vademecum";
$itemspp = 20;
?>
<form method="POST" action="vademecum.php">
<?php if (!empty($pagina)) { ?>
  <input type="hidden" name="pagina" value="<?php echo $pagina; ?>" />
<?php } ?>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
    	<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-12 text-center">
						<?php 
						$link_tmp = array("pagina" => $pagina);
						list($sql,$total_vademecum,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
						&nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
						<?php $result = sql($sql,"No se ejecuto en la consulta principal") or fin_pagina();?>
					</div>
				</div>
			</div>
			<table class="table table-condensed table-hover">
				<thead>
					<tr>
						<th colspan="4">
							<div class="row">
								<div class="col-sm-8 text-left">
									<h6><b>Total:</b> <?php echo $total_vademecum; ?></h6>
								</div>
								<div class="col-sm-4 text-right">
									<h6><?php echo $link_pagina; ?></h6>
								</div>
							</div>
						</th>
					</tr>
					<tr>
						<th width="20%">
							<a href="<?php echo encode_link("vademecum.php",array("sort"=>"1","up"=>$up)); ?>">
								Nombre Gen&eacute;rico <?php echo icono_sort(1); ?>
							</a>
						</th>
						<th width="20%">
							<a href="<?php echo encode_link("vademecum.php",array("sort"=>"2","up"=>$up)); ?>">
								Nombre Comercial <?php echo icono_sort(2); ?>
							</a>
						</th>
						<th width="30%">
							<a href="<?php echo encode_link("vademecum.php",array("sort"=>"3","up"=>$up)); ?>">
								Acci&oacute;n Terap&eacute;utica <?php echo icono_sort(3); ?>
							</a>
						</th>
						<th width="30%">
							<a href="<?php echo encode_link("vademecum.php",array("sort"=>"4","up"=>$up)); ?>">
								Presentaci&oacute;n <?php echo icono_sort(4); ?>
							</a>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if ($result->recordCount() > 0) {
		      		while (!$result->EOF) {
      			    $link_fila = "";
						    if (!empty($pagina)) { // si no se debe retornar a alguna pagina en especial se va a la pagina de detalles
						      $link_fila = ' onclick="location.href=\''.encode_link($pagina, array("nuevo_id_vademecum" => $result->fields['id'])).'\';"';
						    } 
								echo '<tr>';
								echo '<td'.$link_fila.'>', $result->fields['nombre_generico'], '</td>';
								echo '<td'.$link_fila.'>', $result->fields['nombre_comercial'], '</td>';
								echo '<td'.$link_fila.'>', $result->fields['accion_terapeutica'], '</td>';
								echo '<td'.$link_fila.'>', $result->fields['presentacion'], '</td>';
								echo '<tr>';
								$result->MoveNext();
							}
						}
						else {
							echo '<td colspan="4" align="center" class="danger"><strong>No hay datos</strong></td>';
						}
					?>
				</tbody>
			</table>
		</div>
    </div>
    <div class="col-md-2"></div>
</div>
</form>
