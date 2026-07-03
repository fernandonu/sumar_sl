<?php

require_once("../../config.php");
require_once("funciones_generales.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
$usuario1=$_ses_user['login'];
cargar_calendario();

$result_nomenclador = sql("SELECT id_nomenclador_detalle, descripcion FROM facturacion.nomenclador_detalle ORDER BY fecha_desde DESC");

if ($_POST['importar_excel_nomivac']){

	ob_start();

	$archivo = $_FILES['archivo_excel_nomivac'];

	if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK || $archivo['size'] == 0) {
		echo "<b>Debe seleccionar un archivo Excel válido.</b>";
	}
	else {
		$ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
		if ($ext != 'xls' && $ext != 'xlsx') {
			echo "<b>El archivo debe tener extensión .xls o .xlsx</b>";
		}
		else {
			ini_set('memory_limit', '512M');
			set_time_limit(600);

			require_once(LIB_DIR."/PHPExcel/IOFactory.php");
			PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_to_discISAM);

			try {
				$objPHPExcel = PHPExcel_IOFactory::load($archivo['tmp_name']);
			}
			catch (Exception $e) {
				$objPHPExcel = null;
				echo "<b>No se pudo leer el archivo Excel: ".$e->getMessage()."</b>";
			}

			if ($objPHPExcel) {

				// mapeo encabezado de excel -> columna de facturacion.importar_nomivac
				$mapeo_campos = array(
					"Código de establecimiento" => "Código de establecimiento",
					"Código de vacuna" => "codigo Vacuna",
					"Fecha de aplicación" => "Fecha de aplicación",
					"Nro. de documento" => "Nro. de documento",
					"Edad de aplicación" => "Edad de aplicación",
					"Sexo" => "Sexo",
				);

				$hoja = $objPHPExcel->getSheet(0);
				$highestRow = $hoja->getHighestRow();
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($hoja->getHighestColumn());

				// leer encabezados de la primer fila y ubicar las columnas necesarias
				$columnas_encontradas = array(); // indice de columna del excel => campo de la tabla
				for ($col = 0; $col < $highestColumnIndex; $col++) {
					$titulo = trim($hoja->getCellByColumnAndRow($col, 1)->getValue());
					foreach ($mapeo_campos as $titulo_excel => $campo_tabla) {
						if (mb_strtolower($titulo, 'UTF-8') == mb_strtolower($titulo_excel, 'UTF-8')) {
							$columnas_encontradas[$col] = $campo_tabla;
							break;
						}
					}
				}

				if (count($columnas_encontradas) < count($mapeo_campos)) {
					echo "<b>El archivo Excel no contiene todas las columnas esperadas: ".implode(", ", array_keys($mapeo_campos))."</b>";
				}
				else {
					global $db;
					// campos que conforman la clave para evitar duplicados
					$campos_clave = array("Código de establecimiento", "codigo Vacuna", "Fecha de aplicación", "Nro. de documento");

					$importados = 0;
					$duplicados = 0;
					$errores = 0;
					$claves_procesadas = array(); // duplicados dentro del mismo archivo
					$fecha_hoy = date("Y-m-d");

					for ($row = 2; $row <= $highestRow; $row++) {

						$valores = array();
						$fila_vacia = true;

						foreach ($columnas_encontradas as $col => $campo_tabla) {
							$celda = $hoja->getCellByColumnAndRow($col, $row);
							$valor = $celda->getCalculatedValue();

							if ($campo_tabla == "Fecha de aplicación") {
								if ($valor !== null && $valor !== '' && PHPExcel_Shared_Date::isDateTime($celda)) {
									$valor = PHPExcel_Shared_Date::ExcelToPHPObject($valor)->format("Y-m-d");
								}
								else {
									$valor = trim($valor);
									if ($valor != '') $valor = fecha_db($valor);
								}
							}
							else {
								$valor = trim($valor);
							}

							if ($valor !== '') $fila_vacia = false;
							$valores[$campo_tabla] = $valor;
						}

						if ($fila_vacia) continue;

						// duplicado dentro del propio archivo
						$clave = implode("|", array($valores["Código de establecimiento"], $valores["codigo Vacuna"], $valores["Fecha de aplicación"], $valores["Nro. de documento"]));
						if (isset($claves_procesadas[$clave])) {
							$duplicados++;
							continue;
						}

						// duplicado ya existente en la base
						$condiciones = array();
						foreach ($campos_clave as $campo) {
							$valor = $valores[$campo];
							$condiciones[] = ($valor === '') ? '"'.$campo.'" IS NULL' : '"'.$campo.'" = '.$db->qstr($valor);
						}
						$existe_sql = "SELECT 1 FROM facturacion.importar_nomivac WHERE ".implode(" AND ", $condiciones)." LIMIT 1";
						$res_existe = sql($existe_sql);
						if ($res_existe && !$res_existe->EOF) {
							$duplicados++;
							$claves_procesadas[$clave] = true;
							continue;
						}

						$campos_sql = array();
						$valores_sql = array();
						foreach ($valores as $campo => $valor) {
							$campos_sql[] = '"'.$campo.'"';
							$valores_sql[] = ($valor === '') ? "NULL" : $db->qstr($valor);
						}
						$campos_sql[] = "importado";
						$valores_sql[] = "true";
						$campos_sql[] = "fecha_importacion";
						$valores_sql[] = $db->qstr($fecha_hoy);

						$insert = "INSERT INTO facturacion.importar_nomivac (".implode(",", $campos_sql).") VALUES (".implode(",", $valores_sql).")";
						$res = sql($insert);
						if ($res === false) { $errores++; } else { $importados++; $claves_procesadas[$clave] = true; }
					}

					echo "<b>Importación finalizada.<br>";
					echo "Registros importados: $importados.<br>";
					echo "Registros no importados por estar duplicados: $duplicados.<br>";
					echo "Registros no importados por error: $errores.";
					echo "</b>";
				}
			}
		}
	}

	$resultado_texto = ob_get_clean();
	echo $resultado_texto;
	$sql_log = "INSERT INTO facturacion.log_proceso_nomivac (fecha, accion, resultado, usuario)
		VALUES (now(), ".$db->qstr('Importar Excel Nomivac').", ".$db->qstr(strip_tags($resultado_texto)).", ".$db->qstr($usuario1).")";
	sql($sql_log, "Error al registrar el log del proceso");
}


if ($_POST['completar_datos']){

	global $db;
	ob_start();
	$id_nomenclador_detalle = intval($_POST['id_nomenclador_detalle']);

	if (!$id_nomenclador_detalle) {
		echo "<b>Debe seleccionar un nomenclador antes de completar los datos.</b>";
	}
	else {
		$fecha_hoy = date("Y-m-d");

		$res_pend = sql("SELECT count(*) as total FROM facturacion.importar_nomivac WHERE completado_de_datos IS NOT TRUE");
		$res_pend->movefirst();
		$total_pendientes = $res_pend->fields['total'];

		$res_match = sql("SELECT count(*) as total FROM facturacion.importar_nomivac inv
			WHERE inv.completado_de_datos IS NOT TRUE
			AND EXISTS (SELECT 1 FROM facturacion.pr_brinda_ceb pbc WHERE trim(pbc.codsisa) = trim(inv.\"codigo Vacuna\"))");
		$res_match->movefirst();
		$con_cod_sumar = $res_match->fields['total'];

		// completa cod_sumar segun la equivalencia de facturacion.pr_brinda_ceb (codsisa <-> codigo Vacuna)
		$update_cod_sumar = "UPDATE facturacion.importar_nomivac inv
			SET cod_sumar = pbc.codsumar
			FROM facturacion.pr_brinda_ceb pbc
			WHERE trim(pbc.codsisa) = trim(inv.\"codigo Vacuna\")
			AND inv.completado_de_datos IS NOT TRUE";
		sql($update_cod_sumar, "Error al completar cod_sumar") or fin_pagina();

		$res_nom = sql("SELECT count(*) as total FROM facturacion.importar_nomivac inv
			WHERE inv.completado_de_datos IS NOT TRUE
			AND EXISTS (SELECT 1 FROM facturacion.nomenclador nom
				WHERE nom.id_nomenclador_detalle = $id_nomenclador_detalle
				AND (trim(nom.grupo) || trim(nom.codigo)) = substr(inv.cod_sumar,1,6))");
		$res_nom->movefirst();
		$con_id_nomenclador = $res_nom->fields['total'];

		// completa id_nomenclador buscando en facturacion.nomenclador (del nomenclador elegido)
		// el registro cuyo grupo+codigo coincide con los primeros 6 digitos del cod_sumar ya completado
		$update_id_nomenclador = "UPDATE facturacion.importar_nomivac inv
			SET id_nomenclador = nom.id_nomenclador
			FROM facturacion.nomenclador nom
			WHERE nom.id_nomenclador_detalle = $id_nomenclador_detalle
			AND (trim(nom.grupo) || trim(nom.codigo)) = substr(inv.cod_sumar,1,6)
			AND inv.completado_de_datos IS NOT TRUE";
		sql($update_id_nomenclador, "Error al completar id_nomenclador") or fin_pagina();

		$res_smi = sql("SELECT count(*) as total FROM facturacion.importar_nomivac inv
			WHERE inv.completado_de_datos IS NOT TRUE
			AND EXISTS (SELECT 1 FROM nacer.smiafiliados smi WHERE trim(smi.afidni) = trim(inv.\"Nro. de documento\"))");
		$res_smi->movefirst();
		$con_id_smiafiliados = $res_smi->fields['total'];

		// completa id_smiafiliados buscando en nacer.smiafiliados por nro. de documento (afidni)
		// si hay mas de un beneficiario con el mismo documento, prioriza el activo y luego el mas reciente
		$update_id_smiafiliados = "UPDATE facturacion.importar_nomivac inv
			SET id_smiafiliados = (
				SELECT smi.id_smiafiliados FROM nacer.smiafiliados smi
				WHERE trim(smi.afidni) = trim(inv.\"Nro. de documento\")
				ORDER BY (smi.activo = 'S') DESC, smi.id_smiafiliados DESC
				LIMIT 1
			)
			WHERE inv.completado_de_datos IS NOT TRUE
			AND EXISTS (SELECT 1 FROM nacer.smiafiliados smi WHERE trim(smi.afidni) = trim(inv.\"Nro. de documento\"))";
		sql($update_id_smiafiliados, "Error al completar id_smiafiliados") or fin_pagina();

		$res_cuie = sql("SELECT count(*) as total FROM facturacion.importar_nomivac inv
			WHERE inv.completado_de_datos IS NOT TRUE
			AND EXISTS (SELECT 1 FROM nacer.efe_conv efe WHERE trim(efe.cod_siisa) = trim(inv.\"Código de establecimiento\"))");
		$res_cuie->movefirst();
		$con_cuie = $res_cuie->fields['total'];

		// completa cuie buscando en nacer.efe_conv por codigo de establecimiento (cod_siisa)
		// si hay mas de un efector con el mismo cod_siisa, toma el mas reciente
		$update_cuie = "UPDATE facturacion.importar_nomivac inv
			SET cuie = (
				SELECT efe.cuie FROM nacer.efe_conv efe
				WHERE trim(efe.cod_siisa) = trim(inv.\"Código de establecimiento\")
				ORDER BY efe.id_efe_conv DESC
				LIMIT 1
			)
			WHERE inv.completado_de_datos IS NOT TRUE
			AND EXISTS (SELECT 1 FROM nacer.efe_conv efe WHERE trim(efe.cod_siisa) = trim(inv.\"Código de establecimiento\"))";
		sql($update_cuie, "Error al completar cuie") or fin_pagina();

		// marca como completados todos los pendientes (tengan o no equivalencia encontrada)
		$update_completado = "UPDATE facturacion.importar_nomivac
			SET completado_de_datos = true,
				fecha_completado_datos = ".$db->qstr($fecha_hoy).",
				generado_comprobante = false
			WHERE completado_de_datos IS NOT TRUE";
		sql($update_completado, "Error al marcar los registros como completados") or fin_pagina();

		$sin_cod_sumar = $total_pendientes - $con_cod_sumar;
		$sin_id_nomenclador = $total_pendientes - $con_id_nomenclador;
		$sin_id_smiafiliados = $total_pendientes - $con_id_smiafiliados;
		$sin_cuie = $total_pendientes - $con_cuie;

		echo "<b>Completado de datos finalizado.<br>";
		echo "Registros procesados: $total_pendientes.<br>";
		echo "Cod_sumar encontrado: $con_cod_sumar. Sin equivalencia en facturacion.pr_brinda_ceb: $sin_cod_sumar.<br>";
		echo "Id_nomenclador encontrado: $con_id_nomenclador. Sin equivalencia en facturacion.nomenclador: $sin_id_nomenclador.<br>";
		echo "Id_smiafiliados encontrado: $con_id_smiafiliados. Sin equivalencia en nacer.smiafiliados: $sin_id_smiafiliados.<br>";
		echo "Cuie encontrado: $con_cuie. Sin equivalencia en nacer.efe_conv: $sin_cuie.";
		echo "</b>";
	}

	$resultado_texto = ob_get_clean();
	echo $resultado_texto;
	$sql_log = "INSERT INTO facturacion.log_proceso_nomivac (fecha, accion, resultado, usuario)
		VALUES (now(), ".$db->qstr('Completar Datos Nomivac').", ".$db->qstr(strip_tags($resultado_texto)).", ".$db->qstr($usuario1).")";
	sql($sql_log, "Error al registrar el log del proceso");
}


if ($_POST['generar_comprobantes_nomivac']){

	global $db;
	ob_start();
	$fecha_hoy = date("Y-m-d");
	$usuario_migracion = "migracion";

	$res_pend = sql("SELECT * FROM facturacion.importar_nomivac
		WHERE generado_comprobante IS NOT TRUE
		AND id_nomenclador IS NOT NULL
		AND id_smiafiliados IS NOT NULL
		AND cuie IS NOT NULL");

	$procesados = 0;
	$comprobantes_generados = 0;
	$sin_beneficiario_activo = 0;
	$duplicados = 0;

	if ($res_pend) {
		$res_pend->movefirst();
		while (!$res_pend->EOF) {

			$id_importacion_nomivac = $res_pend->fields['id_importacion_nomivac'];
			$id_nomenclador = $res_pend->fields['id_nomenclador'];
			$id_smiafiliados = $res_pend->fields['id_smiafiliados'];
			$cuie = $res_pend->fields['cuie'];
			$fecha_aplicacion = substr($res_pend->fields['Fecha de aplicación'], 0, 10);
			$edad = $res_pend->fields['Edad de aplicación'];
			$sexo = $res_pend->fields['Sexo'];

			$procesados++;
			$id_comprobante = null;

			$res_smi = sql("SELECT clavebeneficiario, afifechanac, activo FROM nacer.smiafiliados WHERE id_smiafiliados=".intval($id_smiafiliados));
			$res_smi->movefirst();

			if (!$res_smi->EOF && trim($res_smi->fields['activo']) == 'S') {

				$clavebeneficiario = $res_smi->fields['clavebeneficiario'];
				$fecha_nacimiento = $res_smi->fields['afifechanac'];
				$periodo = str_replace("-", "/", substr($fecha_aplicacion, 0, 7));

				// duplicado: misma persona, misma fecha, mismo establecimiento y misma prestacion
				$sql_dup = "SELECT p.id_prestacion FROM facturacion.comprobante c
					JOIN facturacion.prestacion p ON c.id_comprobante = p.id_comprobante
					WHERE c.id_smiafiliados = ".intval($id_smiafiliados)."
					AND c.fecha_comprobante = ".$db->qstr($fecha_aplicacion)."
					AND c.cuie = ".$db->qstr($cuie)."
					AND p.id_nomenclador = ".intval($id_nomenclador)."
					LIMIT 1";
				$res_dup = sql($sql_dup);

				if ($res_dup && !$res_dup->EOF) {

					$duplicados++;
					$obs_dup = "Duplicado: ya existe comprobante/prestacion para la misma persona, fecha, establecimiento y prestacion (id_prestacion=".$res_dup->fields['id_prestacion'].")";
					$sql_obs = "UPDATE facturacion.importar_nomivac SET \"OBS\" = ".$db->qstr($obs_dup)." WHERE id_importacion_nomivac = $id_importacion_nomivac";
					sql($sql_obs, "Error al marcar observacion de duplicado") or fin_pagina();

				}
				else {

				$db->StartTrans();

				$sql_comp = "INSERT INTO facturacion.comprobante
					(cuie, clavebeneficiario, id_smiafiliados, fecha_comprobante, fecha_carga, periodo, id_servicio, activo, id_importacion_nomivac)
					VALUES
					(".$db->qstr($cuie).", ".$db->qstr($clavebeneficiario).", $id_smiafiliados, ".$db->qstr($fecha_aplicacion).", ".$db->qstr($fecha_aplicacion).", ".$db->qstr($periodo).", 1, 'S', $id_importacion_nomivac)
					RETURNING id_comprobante";
				$res_comp = sql($sql_comp, "Error al insertar el comprobante") or fin_pagina();
				$id_comprobante = $res_comp->fields['id_comprobante'];

				$log_comp = "INSERT INTO facturacion.log_comprobante
					(id_comprobante, fecha, tipo, descripcion, usuario)
					VALUES
					($id_comprobante, ".$db->qstr($fecha_aplicacion).", 'Nuevo Comprobante', 'Nro. Comprobante $id_comprobante', ".$db->qstr($usuario_migracion).")";
				sql($log_comp, "Error al insertar el log del comprobante") or fin_pagina();

				$res_nom = sql("SELECT precio FROM facturacion.nomenclador WHERE id_nomenclador=".intval($id_nomenclador));
				$res_nom->movefirst();
				$precio = $res_nom->fields['precio'];

				$arr_fecha = explode("-", $fecha_aplicacion);
				$anio = $arr_fecha[0];
				$mes = $arr_fecha[1];
				$dia = $arr_fecha[2];

				$sql_prest = "INSERT INTO facturacion.prestacion
					(id_comprobante, id_nomenclador, cantidad, precio_prestacion, id_anexo, diagnostico, edad, sexo, fecha_nacimiento, fecha_prestacion, anio, mes, dia, migrada)
					VALUES
					($id_comprobante, $id_nomenclador, 1, ".$db->qstr($precio).", 346, 'A98', ".$db->qstr($edad).", ".$db->qstr($sexo).", ".$db->qstr($fecha_nacimiento).", ".$db->qstr($fecha_aplicacion).", $anio, $mes, $dia, 1)
					RETURNING id_prestacion";
				$res_prest = sql($sql_prest, "Error al insertar la prestacion") or fin_pagina();
				$id_prestacion = $res_prest->fields['id_prestacion'];

				$log_prest = "INSERT INTO facturacion.log_prestacion
					(id_prestacion, fecha, tipo, descripcion, usuario)
					VALUES
					($id_prestacion, ".$db->qstr($fecha_aplicacion).", 'Nueva PRESTACION', 'Nro. prestacion $id_prestacion', ".$db->qstr($usuario_migracion).")";
				sql($log_prest, "Error al insertar el log de la prestacion") or fin_pagina();

				$db->CompleteTrans();

				$comprobantes_generados++;

				}
			}
			else {
				$sin_beneficiario_activo++;
			}

			$sql_update = "UPDATE facturacion.importar_nomivac
				SET generado_comprobante = true,
					fecha_generado_comprobante = ".$db->qstr($fecha_hoy).",
					id_comprobante = ".(is_null($id_comprobante) ? "NULL" : intval($id_comprobante))."
				WHERE id_importacion_nomivac = $id_importacion_nomivac";
			sql($sql_update, "Error al marcar el registro como generado") or fin_pagina();

			$res_pend->MoveNext();
		}
	}

	echo "<b>Generación de comprobantes finalizada.<br>";
	echo "Registros procesados: $procesados.<br>";
	echo "Comprobantes generados: $comprobantes_generados.<br>";
	echo "Registros omitidos por beneficiario inactivo: $sin_beneficiario_activo.<br>";
	echo "Registros omitidos por duplicado (misma persona/fecha/establecimiento/prestación): $duplicados.";
	echo "</b>";

	$resultado_texto = ob_get_clean();
	echo $resultado_texto;
	$sql_log = "INSERT INTO facturacion.log_proceso_nomivac (fecha, accion, resultado, usuario)
		VALUES (now(), ".$db->qstr('Generar Comprobantes y Prestaciones Nomivac').", ".$db->qstr(strip_tags($resultado_texto)).", ".$db->qstr($usuario1).")";
	sql($sql_log, "Error al registrar el log del proceso");
}


echo $html_header;
?>
<style>
#overlay_procesando {
	display: none;
	position: fixed;
	top: 0; left: 0; width: 100%; height: 100%;
	background: rgba(0,0,0,0.5);
	z-index: 9999;
	text-align: center;
}
#overlay_procesando .msg_procesando {
	background: #fff;
	border-radius: 6px;
	padding: 20px 40px;
	margin-top: 20%;
	display: inline-block;
	font-size: 16px;
	font-weight: bold;
	box-shadow: 0 0 10px rgba(0,0,0,0.5);
}
</style>
<div id="overlay_procesando">
	<div class="msg_procesando">Procesando, por favor espere...</div>
</div>
<script>
function mostrarProcesando(){
	document.getElementById('overlay_procesando').style.display = 'block';
	// se deshabilita en el siguiente ciclo para no interferir con el envio del formulario:
	// si se deshabilita el boton dentro del propio onsubmit, el navegador lo excluye del POST
	setTimeout(function(){
		var botones = document.getElementsByTagName('input');
		for (var i = 0; i < botones.length; i++){
			if (botones[i].type == 'submit') botones[i].disabled = true;
		}
	}, 0);
}
</script>
<form name=form1 action="procesa_nomivac.php" method=POST enctype="multipart/form-data" onsubmit="mostrarProcesando();">
<table width="80%" class="bordes" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
<tr>
	<td>
		<table width=100% align="center" class="bordes">
  
			<tr id="mo" align="center">
				<td colspan="3" align="center">
					<font size=+1><b>Importar Archivo</b></font>      	      
				</td>
			</tr>

 
		    <tr>	           
				<td align="center" colspan="3" id="ma">
					<b> NOMIVAC </b>
				</td>
		    </tr>

			<tr>
				<td align="right" colspan="1">
					<input type="file" name="archivo_excel_nomivac" id="archivo_excel_nomivac" accept=".xls,.xlsx">
					&nbsp;
        </td>
				<td align="left" colspan="2">
          <input type="submit" name="importar_excel_nomivac" value='Importar Excel Nomivac' class="btn btn-info" onclick="return confirm('¿Confirma la importación del archivo Excel seleccionado a facturacion.importar_nomivac?');">
					&nbsp;&nbsp;
					<font color="Red">Importa a Nomivac.primer hoja del Excel, fila 1 encabezado).</font>
				</td>
			</tr>

			<tr>
				<td colspan="3" align="center">
					<font color="Red">Formato esperado del Excel (primer hoja, fila 1 = encabezado con estos 6 nombres de columna exactos):</font>
					<table class="bordes" cellspacing="0" border="1" bordercolor="#E0E0E0" align="center" style="font-size:11px; margin-top:4px;">
						<tr bgcolor="#DDDDDD">
							<td align="center">&nbsp;<b>Código de establecimiento</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Código de vacuna</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Fecha de aplicación</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Nro. de documento</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Edad de aplicación</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Sexo</b>&nbsp;</td>
						</tr>
						<tr>
							<td align="center">50940072195013</td>
							<td align="center">127</td>
							<td align="center">01-05-2026</td>
							<td align="center">46666877</td>
							<td align="center">20</td>
							<td align="center">F</td>
						</tr>
						<tr>
							<td align="center">50940072195018</td>
							<td align="center">193</td>
							<td align="center">01-05-2026</td>
							<td align="center">59547003</td>
							<td align="center">3</td>
							<td align="center">M</td>
						</tr>
						<tr>
							<td align="center">50940072195018</td>
							<td align="center">216</td>
							<td align="center">01-05-2026</td>
							<td align="center">18471221</td>
							<td align="center">59</td>
							<td align="center">F</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td align="right" colspan="1">
					<select name="id_nomenclador_detalle">
						<option value="">Seleccione Nomenclador</option>
						<?
						if ($result_nomenclador) {
							$result_nomenclador->movefirst();
							while (!$result_nomenclador->EOF) {
						?>
						<option value="<?=$result_nomenclador->fields['id_nomenclador_detalle']?>"><?=htmlspecialchars($result_nomenclador->fields['descripcion'])?></option>
						<?
								$result_nomenclador->MoveNext();
							}
						}
						?>
					</select>
					&nbsp;
				</td>
				<td align="left" colspan="2">
					<input type="submit" name="completar_datos" value='Completar Datos Nomivac' class="btn btn-info" onclick="try{var sel=this.form.elements['id_nomenclador_detalle']; if(!sel || !sel.value){alert('Debe seleccionar un nomenclador antes de completar los datos.'); return false;} return confirm('Va a completar los datos usando el nomenclador seleccionado:\n\n' + sel.options[sel.selectedIndex].text + '\n\nVerifique con MUCHO CUIDADO que sea el nomenclador correcto, ya que una selección incorrecta afectará los datos generados.\n\n¿Confirma continuar?');}catch(e){alert('Error de validación: '+e.message); return false;}">
					&nbsp;&nbsp;
					<font color="Red">Completa los registros con completado_de_datos=false (cod_sumar, id_nomenclador y fecha_completado_datos).</font>
				</td>
			</tr>


			<tr>
				<td align="right" colspan="1">
					&nbsp;
				</td>
				<td align="left" colspan="2">
					<input type="submit" name="generar_comprobantes_nomivac" value='Generar Comprobantes y Prestaciones Nomivac' class="btn btn-info" onclick="return confirm('¿Confirma la generación de comprobantes y prestaciones para los registros pendientes de facturacion.importar_nomivac?');">
					&nbsp;&nbsp;
					<font color="Red">Genera comprobante/prestación para los registros con datos completos (generado_comprobante=false, id_nomenclador, id_smiafiliados y cuie no nulos).</font>
				</td>
			</tr>

		</table>
		</td>
	</tr>
</table>	
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
