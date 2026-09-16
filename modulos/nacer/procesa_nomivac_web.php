<?php
require_once("../../config.php");
require_once("funciones_generales.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
$usuario1=$_ses_user['login'];
cargar_calendario();

function get_token_nomivac(&$error) {

	$usuario = 'prod_user_sumar';
	$pass = '5A(4X0(}b<!ZmIlB4)0F';
	$url_token = 'http://busgpsl.sanluis.gob.ar:8080/healthConnectorServer/hcd/login';

	$json_data = json_encode(array('userName' => $usuario, 'password' => $pass));

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url_token);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);

	$resultado = curl_exec($ch);
	$curl_error = curl_error($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($resultado === false) {
		$error = "Error de conexión al obtener el token: ".$curl_error;
		return null;
	}
	if ($http_code < 200 || $http_code >= 300) {
		$error = "El servicio de login respondió con código HTTP $http_code.";
		return null;
	}

	$token_data = json_decode($resultado, true);
	$token = null;
	if (is_array($token_data)) {
		if (isset($token_data['authToken']['token'])) $token = $token_data['authToken']['token'];
		elseif (isset($token_data['token'])) $token = $token_data['token'];
	}
	if (!$token) {
		$error = "No se pudo obtener el token de autenticación.";
		return null;
	}
	return $token;
}

function get_vacunas_nomivac($token, $fecha_desde, $fecha_hasta, &$error) {

	$url = 'http://busgpsl.sanluis.gob.ar:8080/healthConnectorServer/sumar/vaccines?dateFrom='.urlencode($fecha_desde).'&dateTo='.urlencode($fecha_hasta);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
	curl_setopt($ch, CURLOPT_TIMEOUT, 300);

	$resultado = curl_exec($ch);
	$curl_error = curl_error($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($resultado === false) {
		$error = "Error de conexión al obtener las vacunas: ".$curl_error;
		return null;
	}
	if ($http_code < 200 || $http_code >= 300) {
		$error = "El servicio de vacunas respondió con código HTTP $http_code.";
		return null;
	}

	// "sourceCode" puede exceder PHP_INT_MAX en PHP de 32 bits (json_decode lo pasaria a float
	// perdiendo precision), por lo que se preserva como string antes de decodificar
	$resultado = preg_replace('/"sourceCode"\s*:\s*(\d+)/', '"sourceCode":"$1"', $resultado);

	$datos = json_decode($resultado, true);
	if (!is_array($datos)) {
		$error = "La respuesta del servicio de vacunas no es válida.";
		return null;
	}
	// si el servicio devuelve un unico registro como objeto (no array), se normaliza a array
	if (isset($datos['sniVaccineId'])) $datos = array($datos);

	return $datos;
}

if ($_POST['importar_web_nomivac']){

	ob_start();

	global $db;
	$fecha_desde = trim($_POST['fecha_desde_web']);
	$fecha_hasta = trim($_POST['fecha_hasta_web']);

	$detalle = array();
	$registros_recibidos = 0;
	$importados = 0;
	$duplicados = 0;
	$errores = 0;

	if (!$fecha_desde || !$fecha_hasta) {
		echo "<b>Debe seleccionar el rango de fechas (desde y hasta).</b>";
	}
	else if ($fecha_desde > $fecha_hasta) {
		echo "<b>La fecha desde no puede ser mayor a la fecha hasta.</b>";
	}
	else {

		$error_ws = null;
		$token = get_token_nomivac($error_ws);

		if (!$token) {
			echo "<b>".htmlspecialchars($error_ws)."</b>";
		}
		else {

			$error_ws = null;
			$vacunas = get_vacunas_nomivac($token, $fecha_desde, $fecha_hasta, $error_ws);

			if ($vacunas === null) {
				echo "<b>".htmlspecialchars($error_ws)."</b>";
			}
			else if (count($vacunas) == 0) {
				$registros_recibidos = 0;
				echo "<b>El servicio no devolvió registros para el rango de fechas seleccionado.</b>";
			}
			else {

				$registros_recibidos = count($vacunas);

				// campos que conforman la clave para evitar duplicados (misma logica que la importacion via Excel)
				$campos_clave = array("Código de establecimiento", "codigo Vacuna", "Fecha de aplicación", "Nro. de documento");
				$claves_procesadas = array(); // duplicados dentro de la misma respuesta del servicio
				$fecha_hoy = date("Y-m-d");

				foreach ($vacunas as $item) {

					$cod_establecimiento = isset($item['sourceCode']) ? strval($item['sourceCode']) : '';
					$cod_vacuna = isset($item['sniVaccineId']) ? strval($item['sniVaccineId']) : '';
					$nombre_vacuna = isset($item['sniVaccineName']) ? $item['sniVaccineName'] : '';
					$fecha_aplicacion_raw = isset($item['vaccineApplicationDate']) ? trim($item['vaccineApplicationDate']) : '';
					$fecha_aplicacion = $fecha_aplicacion_raw ? substr($fecha_aplicacion_raw, 0, 10) : '';
					$documento = isset($item['patientDocumentNumber']) ? trim($item['patientDocumentNumber']) : '';
					$fecha_nacimiento = isset($item['patientBirthDate']) ? trim($item['patientBirthDate']) : '';

					// edad de aplicacion = edad del paciente (en años) a la fecha de aplicacion de la vacuna
					$edad = '';
					if ($fecha_nacimiento && $fecha_aplicacion) {
						$datos_edad = edad_con_meses($fecha_nacimiento, $fecha_aplicacion);
						$edad = $datos_edad['anos'];
					}

					$fila = array(
						"establecimiento" => $cod_establecimiento,
						"vacuna" => trim($cod_vacuna." - ".$nombre_vacuna),
						"fecha" => $fecha_aplicacion,
						"documento" => $documento,
						"edad" => $edad,
						"estado" => '',
					);

					if ($cod_establecimiento === '' || $cod_vacuna === '' || $fecha_aplicacion === '' || $documento === '') {
						$errores++;
						$fila["estado"] = "Error: datos incompletos";
						$detalle[] = $fila;
						continue;
					}

					// "Sexo" no tiene mapeo desde el servicio, se guarda vacio (NULL)
					$valores = array(
						"Código de establecimiento" => $cod_establecimiento,
						"codigo Vacuna" => $cod_vacuna,
						"Fecha de aplicación" => $fecha_aplicacion,
						"Nro. de documento" => $documento,
						"Edad de aplicación" => $edad,
						"Sexo" => '',
					);

					// duplicado dentro de la propia respuesta del servicio
					$clave = implode("|", array($cod_establecimiento, $cod_vacuna, $fecha_aplicacion, $documento));
					if (isset($claves_procesadas[$clave])) {
						$duplicados++;
						$fila["estado"] = "Duplicado";
						$detalle[] = $fila;
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
						$fila["estado"] = "Duplicado";
						$detalle[] = $fila;
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

					if ($res === false) {
						$errores++;
						$fila["estado"] = "Error al insertar";
					}
					else {
						$importados++;
						$claves_procesadas[$clave] = true;
						$fila["estado"] = "Importado";
					}
					$detalle[] = $fila;
				}

				echo "<b>Importación WEB finalizada.<br>";
				echo "Rango de fechas: ".htmlspecialchars($fecha_desde)." a ".htmlspecialchars($fecha_hasta).".<br>";
				echo "Registros recibidos del servicio: $registros_recibidos<br>";
				echo "</b>";

				echo "<table class='bordes' cellspacing=0 border=1 bordercolor=#E0E0E0 align='center' style='margin-top:8px;'>";
				echo "<tr bgcolor='#DDDDDD'><td align='center'>&nbsp;<b>Estado</b>&nbsp;</td><td align='center'>&nbsp;<b>Cantidad</b>&nbsp;</td></tr>";
				echo "<tr><td>Registros importados</td><td align='center'>$importados</td></tr>";
				echo "<tr><td>Registros no importados por estar duplicados</td><td align='center'>$duplicados</td></tr>";
				echo "<tr><td>Registros no importados por error</td><td align='center'>$errores</td></tr>";
				echo "</table>";

				echo "<br><table class='bordes' cellspacing=0 border=1 bordercolor=#E0E0E0 align='center' style='font-size:11px;'>";
				echo "<tr bgcolor='#DDDDDD'>
					<td align='center'>&nbsp;<b>Cód. Establecimiento</b>&nbsp;</td>
					<td align='center'>&nbsp;<b>Vacuna</b>&nbsp;</td>
					<td align='center'>&nbsp;<b>Fecha Aplicación</b>&nbsp;</td>
					<td align='center'>&nbsp;<b>Documento</b>&nbsp;</td>
					<td align='center'>&nbsp;<b>Edad</b>&nbsp;</td>
					<td align='center'>&nbsp;<b>Estado</b>&nbsp;</td>
					</tr>";
				foreach ($detalle as $fila) {
					$color = '';
					if ($fila["estado"] == "Importado") $color = "color:green";
					else if ($fila["estado"] == "Duplicado") $color = "color:#B8860B";
					else if (strpos($fila["estado"], "Error") === 0) $color = "color:red";
					echo "<tr>
						<td align='center'>".htmlspecialchars($fila["establecimiento"])."</td>
						<td align='left'>".htmlspecialchars($fila["vacuna"])."</td>
						<td align='center'>".htmlspecialchars($fila["fecha"])."</td>
						<td align='center'>".htmlspecialchars($fila["documento"])."</td>
						<td align='center'>".htmlspecialchars($fila["edad"])."</td>
						<td align='center' style='$color'><b>".htmlspecialchars($fila["estado"])."</b></td>
						</tr>";
				}
				echo "</table>";
			}
		}
	}

	$resultado_texto = ob_get_clean();
	echo $resultado_texto;

	$resultado_log = "Rango: $fecha_desde a $fecha_hasta. Importados: $importados. Duplicados: $duplicados. Errores: $errores.";
	$sql_log = "INSERT INTO facturacion.log_proceso_nomivac (fecha, accion, resultado, usuario)
		VALUES (now(), ".$db->qstr('Importar WEB Nomivac').", ".$db->qstr($resultado_log).", ".$db->qstr($usuario1).")";
	sql($sql_log, "Error al registrar el log del proceso");

	if ($fecha_desde && $fecha_hasta) {
		$sql_log_web = "INSERT INTO facturacion.log_importacion_web_nomivac
			(fecha_desde, fecha_hasta, registros_recibidos, registros_importados, registros_duplicados, registros_error, usuario)
			VALUES (".$db->qstr($fecha_desde).", ".$db->qstr($fecha_hasta).", ".intval($registros_recibidos).", ".intval($importados).", ".intval($duplicados).", ".intval($errores).", ".$db->qstr($usuario1).")";
		sql($sql_log_web, "Error al registrar el historial de la importación WEB");
	}
}


// historial de importaciones WEB (busqueda + paginado)
global $db;

$hist_fecha_desde = isset($_GET['hist_fecha_desde']) ? trim($_GET['hist_fecha_desde']) : '';
$hist_fecha_hasta = isset($_GET['hist_fecha_hasta']) ? trim($_GET['hist_fecha_hasta']) : '';
$hist_pagina = isset($_GET['hist_pagina']) ? intval($_GET['hist_pagina']) : 1;
if ($hist_pagina < 1) $hist_pagina = 1;
$hist_por_pagina = 20;

$hist_condiciones = array();
if ($hist_fecha_desde) $hist_condiciones[] = "fecha_proceso::date >= ".$db->qstr($hist_fecha_desde);
if ($hist_fecha_hasta) $hist_condiciones[] = "fecha_proceso::date <= ".$db->qstr($hist_fecha_hasta);
$hist_where = count($hist_condiciones) ? " WHERE ".implode(" AND ", $hist_condiciones) : "";

$hist_total = 0;
$res_hist_total = sql("SELECT count(*) as total FROM facturacion.log_importacion_web_nomivac".$hist_where);
if ($res_hist_total) {
	$res_hist_total->movefirst();
	$hist_total = intval($res_hist_total->fields['total']);
}

$hist_total_paginas = max(1, ceil($hist_total / $hist_por_pagina));
if ($hist_pagina > $hist_total_paginas) $hist_pagina = $hist_total_paginas;
$hist_offset = ($hist_pagina - 1) * $hist_por_pagina;

$hist_registros = array();
$res_hist = sql("SELECT * FROM facturacion.log_importacion_web_nomivac".$hist_where."
	ORDER BY fecha_proceso DESC LIMIT $hist_por_pagina OFFSET $hist_offset");
if ($res_hist) {
	$res_hist->movefirst();
	while (!$res_hist->EOF) {
		$hist_registros[] = $res_hist->fields;
		$res_hist->MoveNext();
	}
}

function hist_link_pagina($pagina, $hist_fecha_desde, $hist_fecha_hasta) {
	$qs = array("hist_pagina" => $pagina);
	if ($hist_fecha_desde) $qs["hist_fecha_desde"] = $hist_fecha_desde;
	if ($hist_fecha_hasta) $qs["hist_fecha_hasta"] = $hist_fecha_hasta;
	return "procesa_nomivac_web.php?".http_build_query($qs);
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
	setTimeout(function(){
		var botones = document.getElementsByTagName('input');
		for (var i = 0; i < botones.length; i++){
			if (botones[i].type == 'submit') botones[i].disabled = true;
		}
	}, 0);
}
</script>
<form name=form1 action="procesa_nomivac_web.php" method=POST onsubmit="mostrarProcesando();">
<table width="80%" class="bordes" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
<tr>
	<td>
		<table width=100% align="center" class="bordes">

			<tr id="mo" align="center">
				<td colspan="3" align="center">
					<font size=+1><b>Importar WEB</b></font>
				</td>
			</tr>

			<tr>
				<td align="center" colspan="3" id="ma">
					<b> NOMIVAC </b>
				</td>
			</tr>

			<tr>
				<td align="right" colspan="1">
					<b>Fecha desde:</b>
					<input type="date" name="fecha_desde_web" id="fecha_desde_web" value="<?=htmlspecialchars($fecha_desde_web)?>" required>
					&nbsp;
					<b>Fecha hasta:</b>
					<input type="date" name="fecha_hasta_web" id="fecha_hasta_web" value="<?=htmlspecialchars($fecha_hasta_web)?>" required>
					&nbsp;
				</td>
				<td align="left" colspan="2">
					<input type="submit" name="importar_web_nomivac" value='Importar WEB Nomivac' class="btn btn-info" onclick="try{var d=this.form.elements['fecha_desde_web'].value, h=this.form.elements['fecha_hasta_web'].value; if(!d || !h){alert('Debe seleccionar el rango de fechas.'); return false;} return confirm('¿Confirma la importación vía WEB (Nomivac) de las vacunas aplicadas entre '+d+' y '+h+'?');}catch(e){alert('Error de validación: '+e.message); return false;}">
					&nbsp;&nbsp;
					<font color="Red">Importa a facturacion.importar_nomivac las vacunas del servicio web SUMAR para el rango de fechas seleccionado.</font>
				</td>
			</tr>

		</table>
	</td>
</tr>
</table>
</form>

<br>
<form name=form_hist action="procesa_nomivac_web.php" method=GET>
<table width="80%" class="bordes" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
<tr>
	<td>
		<table width=100% align="center" class="bordes">

			<tr id="mo" align="center">
				<td colspan="3" align="center">
					<font size=+1><b>Historial de Importaciones WEB Nomivac</b></font>
				</td>
			</tr>

			<tr>
				<td align="right" colspan="1">
					<b>Fecha proceso desde:</b>
					<input type="date" name="hist_fecha_desde" value="<?=htmlspecialchars($hist_fecha_desde)?>">
					&nbsp;
					<b>Fecha proceso hasta:</b>
					<input type="date" name="hist_fecha_hasta" value="<?=htmlspecialchars($hist_fecha_hasta)?>">
					&nbsp;
				</td>
				<td align="left" colspan="2">
					<input type="submit" value='Buscar' class="btn btn-info">
					&nbsp;&nbsp;
					<a href="procesa_nomivac_web.php">Limpiar filtro</a>
				</td>
			</tr>

			<tr>
				<td colspan="3">
					<table width="100%" class="bordes" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" style="font-size:11px;">
						<tr bgcolor="#DDDDDD">
							<td align="center">&nbsp;<b>ID</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Fecha proceso</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Fecha desde</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Fecha hasta</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Recibidos</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Importados</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Duplicados</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Errores</b>&nbsp;</td>
							<td align="center">&nbsp;<b>Usuario</b>&nbsp;</td>
						</tr>
						<?
						if (count($hist_registros) == 0) {
						?>
						<tr><td colspan="9" align="center">No hay registros de importaciones WEB para el filtro seleccionado.</td></tr>
						<?
						}
						foreach ($hist_registros as $reg) {
						?>
						<tr>
							<td align="center"><?=htmlspecialchars($reg['id_log_importacion_web_nomivac'])?></td>
							<td align="center"><?=htmlspecialchars(substr($reg['fecha_proceso'],0,16))?></td>
							<td align="center"><?=htmlspecialchars($reg['fecha_desde'])?></td>
							<td align="center"><?=htmlspecialchars($reg['fecha_hasta'])?></td>
							<td align="center"><?=htmlspecialchars($reg['registros_recibidos'])?></td>
							<td align="center" style="color:green"><?=htmlspecialchars($reg['registros_importados'])?></td>
							<td align="center" style="color:#B8860B"><?=htmlspecialchars($reg['registros_duplicados'])?></td>
							<td align="center" style="color:red"><?=htmlspecialchars($reg['registros_error'])?></td>
							<td align="center"><?=htmlspecialchars($reg['usuario'])?></td>
						</tr>
						<?
						}
						?>
					</table>
				</td>
			</tr>

			<tr>
				<td colspan="3" align="center">
					<?
					if ($hist_pagina > 1) {
					?>
					<a href="<?=htmlspecialchars(hist_link_pagina($hist_pagina-1, $hist_fecha_desde, $hist_fecha_hasta))?>">&laquo; Anterior</a>
					&nbsp;&nbsp;
					<?
					}
					?>
					Página <?=$hist_pagina?> de <?=$hist_total_paginas?> (<?=$hist_total?> registros)
					<?
					if ($hist_pagina < $hist_total_paginas) {
					?>
					&nbsp;&nbsp;
					<a href="<?=htmlspecialchars(hist_link_pagina($hist_pagina+1, $hist_fecha_desde, $hist_fecha_hasta))?>">Siguiente &raquo;</a>
					<?
					}
					?>
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
