<?
	include("funciones_generales.php");

	$sql_fact="SELECT *
				,extract (year from age(comprobante.fecha_comprobante,smiafiliados.afifechanac)) as edad_anio
				from facturacion.comprobante
				inner join nacer.smiafiliados using (id_smiafiliados)
				inner join facturacion.factura using (id_factura)
				where factura.estado='A' 
				and (smiafiliados.grupopoblacional='E' 
				or  smiafiliados.grupopoblacional=' ')
				order by 1";
	//$res_fact=sql($sql_fact) or fin_pagina();
	$res_fact=$db->Execute($sql_fact) or die("Error Consulta de Comprobantes\n");
	
	while ($fila=$res_fact->fetchRow()){
		$id_comprobante=$fila["id_comprobante"];
		$id_factura=$fila["id_factura"];

		//$db->StartTrans();
		if (($fila['grupopoblacional']=='E') or ($fila['edad_anio']>=20 and trim($fila['afisexo'])=='M'))
			{
			$query="UPDATE facturacion.comprobante set
				 id_factura=NULL
				 where id_comprobante=$id_comprobante";

			$db->Execute($query) or die("Error update de comprobante\n");	   
			/*cargo los log*/ 
			$usuario='Script Automatico';
			$fecha_carga=date("Y-m-d H:i:s");
		
			$log="INSERT into facturacion.log_comprobante 
			   (id_comprobante, fecha, tipo, descripcion, usuario) 
			values ($id_comprobante, '$fecha_carga','Comprobante Desvinculado de Factura $id_factura (por Prestacion al grupo Hombre)','Nro. Comprobante $id_comprobante', '$usuario')";
			$db->Execute($log) or die("Error Log de comprobante\n");
		
			$log="INSERT into facturacion.log_factura
			   (id_factura, fecha, tipo, descripcion, usuario) 
			values ($id_factura, '$fecha_carga','Comprobante Desvinculado de Factura $id_factura (por Prestacion al grupo Hombre)','Nro. Comprobante $id_comprobante', '$usuario')";
			$db->Execute($log) or die("Error Consulta de factura\n");
		 }
		//$db->CompleteTrans(); 
	}	
?>