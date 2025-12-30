<?
function variables_form_busqueda_remedios($prefijo,$extra=array()) {
	global $parametros;
	global $page,$keyword,$up,$filter,$sort,$cmd,$cmd1;
	global ${"_ses_".$prefijo};

	if ($_POST["form_busqueda_remedios"]) {
		$page = "0";
		$keyword = $_POST["keyword"];

	}
	else {
		if (isset($_GET["page"]) && (string)$_GET["page"] != "")
			$page = $_GET["page"] - 1;
		elseif (isset($parametros["page"]) && (string)$parametros["page"] != "")
			$page = (string)$parametros["page"];
		elseif ((string)${"_ses_".$prefijo}["page"] != "")
			$page = (string)${"_ses_".$prefijo}["page"];
		else
			$page = "0";
	}

	if (!isset($keyword)) {
		if (isset($parametros["keyword"]) && (string)$parametros["keyword"] != "")
			$keyword = (string)$parametros["keyword"];
		elseif ((string)${"_ses_".$prefijo}["keyword"] != "")
			$keyword = (string)${"_ses_".$prefijo}["keyword"];
		else
			$keyword = "";
	}


	if (isset($_POST["up"]) && (string)$_POST["up"] != "")
		$up = (string)$_POST["up"];
	elseif (isset($parametros["up"]) && (string)$parametros["up"] != "")
		$up = (string)$parametros["up"];
	elseif ((string)${"_ses_".$prefijo}["up"] != "")
		$up = (string)${"_ses_".$prefijo}["up"];
	else
		$up = "";

	if (isset($_POST["filter"]) && (string)$_POST["filter"] != "")
		$filter = (string)$_POST["filter"];
	elseif (isset($parametros["filter"]) && (string)$parametros["filter"] != "")
		$filter = (string)$parametros["filter"];
	elseif ((string)${"_ses_".$prefijo}["filter"] != "")
		$filter = (string)${"_ses_".$prefijo}["filter"];
	else
		$filter = "";

	if (isset($_POST["sort"]) && (string)$_POST["sort"] != "")
		$sort = (string)$_POST["sort"];
	elseif (isset($parametros["sort"]) && (string)$parametros["sort"] != "")
		$sort = (string)$parametros["sort"];
	elseif ((string)${"_ses_".$prefijo}["sort"] != "")
		$sort = (string)${"_ses_".$prefijo}["sort"];
	else
		$sort = "default";

	if (isset($_POST["cmd"]) && (string)$_POST["cmd"] != "")
		$cmd = (string)$_POST["cmd"];
	elseif (isset($parametros["cmd"]) && (string)$parametros["cmd"] != "")
		$cmd = (string)$parametros["cmd"];
	else
		$cmd = "";

	if (isset($_POST["cmd1"]) && (string)$_POST["cmd1"] != "")
		$cmd1 = (string)$_POST["cmd1"];
	elseif (isset($parametros["cmd1"]) && (string)$parametros["cmd1"] != "")
		$cmd1 = (string)$parametros["cmd1"];
	else
		$cmd1 = "";

	if ((string)$cmd != "") {
		if ((string)$cmd != (string)${"_ses_".$prefijo}["cmd"]) {


			$up = "";
			$page = "0";
			$filter = "";
			$keyword = "";
			$sort = "default";
			if (is_array($extra) and count($extra) > 0) {
				foreach ($extra as $key => $val) {
					global $$key;
					$$key = $val;
				}
			}
			//$flag_vaciar=1;
			$extra = array();
		}
	}
	else $cmd = (string)${"_ses_".$prefijo}["cmd"];

		//if (!$flag_vaciar && is_array($extra) and count($extra) > 0) {
		if (is_array($extra) and count($extra) > 0) {
		foreach ($extra as $key => $val) {
			if (isset($_POST[$key]) && (string)$_POST[$key] != "")
				$extra[$key] = (string)$_POST[$key];
			elseif (isset($parametros[$key]) && (string)$parametros[$key] != "")
				$extra[$key] = (string)$parametros[$key];
			elseif ((string)${"_ses_".$prefijo}[$key] != "")
				$extra[$key] = (string)${"_ses_".$prefijo}[$key];
			global $$key;
			$$key = $extra[$key];
		}
	}

	$variables = array("cmd"=>$cmd,"cmd1"=>$cmd1,"page"=>$page,"keyword"=>$keyword,"filter"=>$filter,"sort"=>$sort,"up"=>$up);
	$variables = array_merge($variables, $extra);
	if (serialize($variables) != serialize(${"_ses_".$prefijo})) {
		phpss_svars_set("_ses_".$prefijo, $variables);
	}

}




function form_busqueda_remedios($sql,$orden,$filtro,$link_pagina,$where_extra="",$contar=0,$sumas="",$ignorar="",$seleccion="") {

		global $bgcolor2,$page,$filter,$keyword,$sort,$up;
		global $itemspp,$parametros,$mostrar_form_busqueda;
		if ($_GET['page'])
			$page=($_GET['page'] > 0)?$_GET['page']-1:0;//controlo que no pongan valores negativos

		if ($up == "") {
			$up = $orden["default_up"];
		}
		if ($up == "") {
			$up = "1";
		}
		if ($up == "0") {
//				$up = $parametros["up"];
				$direction="DESC";
				$up2 = "1";
		}
		else {
				$up = "1";
				$direction = "ASC";
				$up2 = "0";
		}
		if ($sort == "") $sort = "default";
		if ($sort == "default") { $sort = $orden["default"]; }
		if ($orden[$sort] == "") { $sort = $orden["default"]; }
		if ($filtro[$filter] == "") { $filter = "all"; }
		//$tmp=es_numero($keyword);
		if (!isset($mostrar_form_busqueda) || $mostrar_form_busqueda !== false) {
			echo "<input type=hidden name=form_busqueda_remedios value=1>";
			echo "<b>Buscar:&nbsp;</b><input type='text' name='keyword' value='$keyword' size=20 maxlength=150>\n";
			echo "<b>&nbsp;en:&nbsp;</b><select name='filter'>&nbsp;\n";
			echo "<option value='all'";
			if (!$filter or $filtro[$filter] == "") echo " selected";
			echo ">Todos los campos\n";
			while (list($key, $val) = each($filtro)) {
					echo "<option value='$key'";
					if ($filter == "$key") echo " selected";
					echo ">$val\n";
			}
			echo "</select>\n";
		}
		//print_r($ignore);


		if ($keyword) {

				$where = "\nWHERE ";
				if ($filter == "all" or !$filter) {
						$where_arr = array();
						if (is_array($ignorar)) $where .= "((";
						else $where .= "(";
						reset($filtro);
						while (list($key, $val) = each($filtro)) {
							    if (is_array($ignorar) && !in_array($key,$ignorar))
							     $where_arr[] = "$key ILIKE '%$keyword%'";
							    if (!is_array($ignorar)) $where_arr[] = "$key ILIKE '%$keyword%'";

						}

						$where .= implode(" OR ", $where_arr);
						$where .= ")";

						if (is_array($seleccion)){
						while (list($key, $val) = each($seleccion)) {
						$where .= " OR ($val)";
						}
						$where .= ")";
						}
				}
				else {if (!is_array($ignorar)) $where .= "$filter ILIKE '%$keyword%'";
					  elseif (is_array($ignorar) && !in_array($filter,$ignorar))
						$where .= "$filter ILIKE '%$keyword%'";
						else $where .= " (".$seleccion[$filter].")";
				}
		}

		$sql .= " $where";
		if ($where_extra != "") {
				if ($where != "")
				{
					 //si no tiene un group by al principio
					 if (!eregi("^group by.*|^ group by.*",$where_extra))
						 $sql .= "\nAND";

				}
				else
				{
					 //si no tiene un group by al principio
					 if (!eregi("^group by.*|^ group by.*",$where_extra))
						 $sql .= "\nWHERE";

				}
				$sql .= " $where_extra";
		}
        //echo $sumas." AAAAAAAAAAAAAAAA<br>";
		if ("$contar"=="buscar") {
			$tipo_res = db_tipo_res("a");
//			$result = sql($sql,"CONTAR") or reportar_error($sql,__FILE__,__LINE__);
			$result = sql($sql,"CONTAR") or fin_pagina();
			$tipo_res = db_tipo_res();
			$total = $result->RecordCount();

			//Sumas de campos de montos caso en que usa la consulta general
			$res_sumas='';

			if (	$sumas!='' &&
					substr_count($sql,$sumas["campo"])>0 &&//si el campo esta definido
					is_array($sumas["mask"])//mascara para configurar el resultado
					) {
						$count_mask = count($sumas["mask"]);//tamaño de la mascara
						if ($count_mask==0) {//caso en que suma solo cantidades
							$acum=0;
							for($i=0;$i<$total;$i++){//for
								$acum+=$result->fields[$sumas["campo"]];
								$result->MoveNext();
							}	//fin de for
							$res_sumas ="$acum";
						}//fin de caso suma cantidades solam.
						elseif(substr_count($sql,$sumas["moneda"])>0) {//otro caso //si la moneda esta definida
							$sql_moneda="Select simbolo,id_moneda from moneda";
							$res_moneda=sql($sql_moneda,"Imposible obtener el listado de moneda") or fin_pagina();
							for($i;$i<$res_moneda->RecordCount();$i++){
								$moneda[$res_moneda->fields["id_moneda"]]=$res_moneda->fields["simbolo"];
								$res_moneda->MoveNext();
							}
								//print_r($moneda);
							for($i=0;$i<$count_mask;$i++) {//preparando el acumulador
								$acum[$i]=0;
							}//fin del for

							for($i=0;$i<$total;$i++){//for
								$pos = array_search($moneda[$result->fields[$sumas["moneda"]]],$sumas["mask"]);
								if (is_int($pos))
									$acum[$pos]+=$result->fields[$sumas["campo"]];
								$result->MoveNext();
							}	//fin de for
							$res_sumas = "";
							for($i=0;$i<$count_mask;$i++) { //preparando el resultado
								$res_sumas.=$sumas["mask"][$i].formato_money($acum[$i])." ";
							}//fin del for

						}//fin otro caso

					}
		}
		elseif($contar)
		{
//		$sql_cont = eregi_replace("^SELECT(.*)FROM", "SELECT COUNT(*) AS total FROM", $sql);
//		$sql_cont = eregi_replace("GROUP BY .*", "", $sql_cont);
			$tipo_res = db_tipo_res("n");
//		$result = $db->Execute($sql_cont) or die($db->ErrorMsg());
			$result = sql($contar,"CONTAR") or fin_pagina();
//		$total = $result->fields[0];
			$tipo_res = db_tipo_res();
			$total = $result->fields[0];


			if (is_string($sumas) && $sumas!="") {
				$tipo_res = db_tipo_res("n");
				$result = sql($sumas,"SUMAS") or fin_pagina();
				$tipo_res = db_tipo_res();
				$res_sumas="";
				for ($i=0;$i<$result->RecordCount();$i++){
					$res_sumas.=$result->fields[0]." ".formato_money($result->fields[1])." ";
					$result->MoveNext();
				}

			}
		}
		else {
			$total = 0;
			$res_sumas="";
		}

// $total=99;
		if ($sort != "" && isset($orden[$sort])) {
		    $sql .= "\nORDER BY ".$orden[$sort]." $direction";
		}

		//$sql .= "\nLIMIT $itemspp OFFSET ".($page * $itemspp);

		$page_n = $page + 1;
		$page_p = $page - 1;
		$link_pagina_p = "";
		$link_pagina_n = "";
		if (!is_array($link_pagina)) $link_pagina = array();
//		$link_pagina["sort"] = $sort;
//		$link_pagina["up"] = $up;
//		$link_pagina["keyword"] = $keyword;
//		$link_pagina["filter"] = $filter;
		if ($page > 0) {
			$link_pagina["page"] = $page_p;
			$link_pagina_p = "<a title='Página anterior' href='".encode_link($_SERVER["SCRIPT_NAME"],$link_pagina)."'><<</a>";
		}
		$sum=0;
		if (($total % $itemspp)>0) $sum=1;

		$last_page=(intval($total/$itemspp)+$sum);
		$link_pagina_num = "&nbsp;&nbsp;Página&nbsp;<input type='text' value=".($page+1)." name='page' size=2 style='text-align:right;border:none' onkeypress=\" if ((show_alert=(window.event.keyCode==13)) && parseInt(this.value)>0 && parseInt(this.value)<= $last_page ) {location.href='".encode_link($_SERVER["SCRIPT_NAME"],$link_pagina). "&page='+parseInt(this.value);return false;} else if (show_alert) {alert('Por favor ingrese un número válido'); return false;} \" />&nbsp;de&nbsp;$last_page&nbsp;&nbsp;";
		if ($total > $page_n*$itemspp) {
			$link_pagina["page"] = $page_n;
			$link_pagina_n = "<a title='Página siguiente' href='".encode_link($_SERVER["SCRIPT_NAME"],$link_pagina)."'>>></a>";
		}
		if ($total > 0 and $total > $itemspp) {
			$link_pagina_ret = $link_pagina_p.$link_pagina_num.$link_pagina_n;
		}
		else {
			$link_pagina_ret = "";
		}

		return array($sql,$total,$link_pagina_ret,$up2,$res_sumas);
}
?>