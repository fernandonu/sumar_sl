<?php
require_once("../../config.php");

if (isset($_POST['grupo']) && !empty($_POST['grupo'])) {
 	$grupo = trim($_POST['grupo']);
 	if (!empty($grupo)) {
 		$_ses_vademecum["grupo"] = $grupo;
  		phpss_svars_set("_ses_vademecum", $_ses_vademecum);
 	}
}

if (isset($_POST['subgrupo']) && !empty($_POST['subgrupo'])) {
 	$subgrupo = trim($_POST['subgrupo']);
 	if (!empty($subgrupo)) {
 		$_ses_vademecum["subgrupo"] = $subgrupo;
  		phpss_svars_set("_ses_vademecum", $_ses_vademecum);
 	}
}

if (isset($_POST['nombre_generico']) && !empty($_POST['nombre_generico'])) {
 	$nombre_generico = trim($_POST['nombre_generico']);
 	if (!empty($nombre_generico)) {
 		$_ses_vademecum["nombre_generico"] = $nombre_generico;
  		phpss_svars_set("_ses_vademecum", $_ses_vademecum);
 	}
}

?>
<script type="text/javascript">
$(document).ready(function() {
	$('#grupo, #subgrupo, #nombre_generico').change(function() {
        this.form.submit();
    });
});
</script>
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<form method="POST" action="vademecum.php">
					<?php if (!empty($pagina)) { ?>
					  <input type="hidden" name="pagina" value="<?php echo $pagina; ?>" />
					<?php } ?>
					<div class="row">
						<div class="col-md-2"><b>Grupo:</b></div>
						<div class="col-md-10">
							<select id="grupo" name="grupo" class="col-md-12">
								<option></option>
								<?php
								$query_grupo = "SELECT grupo FROM nacer.vademecum GROUP BY grupo ORDER BY grupo";
								$res_grupo = sql($query_grupo, "Error al traer los datos de los grupos") or fin_pagina();
								$grupo_encontrado = false;

								while (!$res_grupo->EOF) {
									$selected = "";
									if ($res_grupo->fields['grupo'] == $grupo) {
										$selected = " selected";
										$grupo_encontrado = true;
									}
									echo '<option value="', $res_grupo->fields['grupo'], '"', $selected, '>', $res_grupo->fields['grupo'], '</option>';
									$res_grupo->MoveNext();
								}
								if (!$grupo_encontrado) {
									$grupo = $subgrupo = $nombre_generico = "";
								}
								?>
							</select>
						</div>
					</div>
					<?php if (!empty($grupo)) { ?>
					<div class="row">
						<div class="col-md-2"><b>Sub Grupo:</b></div>
						<div class="col-md-10">
							<select id="subgrupo" name="subgrupo" class="col-md-12">
								<option></option>
								<?php
								$query_subgrupo = "SELECT subgrupo FROM nacer.vademecum WHERE grupo = '{$grupo}' GROUP BY subgrupo ORDER BY subgrupo";
								$res_subgrupo = sql($query_subgrupo, "Error al traer los datos de los subgrupos") or fin_pagina();
								$subgrupo_encontrado = false;

								while (!$res_subgrupo->EOF) {
									$selected = "";
									if ($res_subgrupo->fields['subgrupo'] == $subgrupo) {
										$selected = " selected";
										$subgrupo_encontrado = true;
									}
									echo '<option value="', $res_subgrupo->fields['subgrupo'], '"', $selected, '>', $res_subgrupo->fields['subgrupo'], '</option>';
									$res_subgrupo->MoveNext();
								}
								if (!$subgrupo_encontrado) {
									$subgrupo = "";
								}
								?>
							</select>
						</div>
					</div>
					<?php } ?>
				</form>
			</div>
			<?php 
			if (!empty($subgrupo)) {
				$sql_tmp = "SELECT * FROM nacer.vademecum WHERE subgrupo = '{$subgrupo}'";
				$mostrar_form_busqueda = false;
				list($sql,$total_vademecum,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,false);
				$res = sql($sql, "Error al traer los datos") or fin_pagina();

				echo '<table class="table table-condensed table-hover">';
				echo '<thead>
						<tr>
							<th width="20%">
								<a href="'.encode_link("vademecum.php",array("sort"=>"1","up"=>$up)).'">
									Nombre Gen&eacute;rico '.icono_sort(1).'
								</a>
							</th>
							<th width="20%">
								<a href="'.encode_link("vademecum.php",array("sort"=>"2","up"=>$up)).'">
									Nombre Comercial '.icono_sort(2).'
								</a>
							</th>
							<th width="30%">
								<a href="'.encode_link("vademecum.php",array("sort"=>"3","up"=>$up)).'">
									Acci&oacute;n Terap&eacute;utica '.icono_sort(3).'
								</a>
							</th>
							<th width="30%">
								<a href="'.encode_link("vademecum.php",array("sort"=>"4","up"=>$up)).'">
									Presentaci&oacute;n '.icono_sort(4).'
								</a>
							</th>
						</tr>
					  </thead>';
				echo '<tbody>';

				if ($res->recordCount() > 0) {
					while (!$res->EOF) {
						echo '<tr>';
						echo '<td>', $res->fields['nombre_generico'], '</td>';
						echo '<td>', $res->fields['nombre_comercial'], '</td>';
						echo '<td>', $res->fields['accion_terapeutica'], '</td>';
						echo '<td>', $res->fields['presentacion'], '</td>';
						echo '<tr>';
						$res->MoveNext();
					}
				}
				else {
					echo '<td colspan="4" align="center" class="danger"><strong>No hay datos</strong></td>';
				}
				echo '</tbody>';
				echo '</table>';
			}
			?>
		</div>
	</div>
	<div class="col-md-2"></div>
</div>