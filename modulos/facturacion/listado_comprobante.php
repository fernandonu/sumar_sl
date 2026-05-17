<?php

require_once("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();


//variables_form_busqueda("listado_comprobante");


function bisiesto_local($anio_actual){ 
    $bisiesto=false; 
    //probamos si el mes de febrero del año actual tiene 29 días 
      if (checkdate(2,29,$anio_actual)) 
      { 
        $bisiesto=true; 
    } 
    return $bisiesto; 
} 


function edad_con_meses_sin_fecha_actual($fecha_de_nacimiento,$fecha_actual){ 
	// separamos en partes las fechas 
	$array_nacimiento = explode ( "-", $fecha_de_nacimiento ); 
	$array_actual = explode ( "-", $fecha_actual ); 

	$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
	$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
	$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días 

	//ajuste de posible negativo en $días 
	if ($dias < 0) 
	{ 
		--$meses; 

		//ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
		switch ($array_actual[1]) { 
			   case 1:     $dias_mes_anterior=31; break; 
			   case 2:     $dias_mes_anterior=31; break; 
			   case 3:  
					if (bisiesto_local($array_actual[0])) 
					{ 
						$dias_mes_anterior=29; break; 
					} else { 
						$dias_mes_anterior=28; break; 
					} 
			   case 4:     $dias_mes_anterior=31; break; 
			   case 5:     $dias_mes_anterior=30; break; 
			   case 6:     $dias_mes_anterior=31; break; 
			   case 7:     $dias_mes_anterior=30; break; 
			   case 8:     $dias_mes_anterior=31; break; 
			   case 9:     $dias_mes_anterior=31; break; 
			   case 10:     $dias_mes_anterior=30; break; 
			   case 11:     $dias_mes_anterior=31; break; 
			   case 12:     $dias_mes_anterior=30; break; 
		} 

		$dias=$dias + $dias_mes_anterior; 
	} 

	//ajuste de posible negativo en $meses 
	if ($meses < 0) 
	{ 
		--$anos; 
		$meses=$meses + 12; 
	} 
	$edad_con_meses_result= array("anos"=>$anos,"meses"=>$meses,"dias"=>$dias);
	return  $edad_con_meses_result;
}

if ($cmd == "")  $cmd="F";


$orden = array(
        "default" => "8",
        "default_up" => "0",
        "1" => "afiapellido",
        "2" => "afinombre",
        "3" => "afidni",        
        "8" => "id_comprobante",
        "9" => "id_factura",
        "11" => "comprobante.fecha_comprobante"
       );
$filtro = array(
		"afidni" => "DNI",
        "afiapellido" => "Apellido",
        "afinombre" => "Nombre",        
        "to_char(id_comprobante,'999999')"=>"Nro. Comprobante",
        "to_char(id_factura,'999999')"=>"Nro. Factura"                
       );
       
$datos_barra = array(
     array(
        "descripcion"=> "Facturados",
        "cmd"        => "F"
     ),
     array(
        "descripcion"=> "No Facturados",
        "cmd"        => "NF"
     ),
     array(
        "descripcion"=> "Todos",
        "cmd"        => "todos"
     )
);

generar_barra_nav($datos_barra);

$codigos_embarazadas=array ("CTC005W78","CTC006W78","CTC007O24.4","CTC022O24.4","CTC007O10","CTC007O10.4",
                            "CTC022O10","CTC022O10.4","CTC007O16","CTC022O16","CTC017P05");
                            
$codigo_ninios_adolesc=array("CTC001A97","CTC009A97");

$codigo_obes_sobrep=array("CTC001T79","CTC001T82","CTC001T83","CTC002T79","CTC002T82","CTC002T83");



$sql_tmp="SELECT afiapellido,afinombre,afidni,id_comprobante,id_factura,comprobante.fecha_comprobante 
	 from facturacion.comprobante	 
	 left join nacer.smiafiliados using (id_smiafiliados)
	 left join facturacion.factura using (id_factura)";
	 
if ($cmd=="F")
    $where_tmp=" (comprobante.id_factura is not null)";
    

if ($cmd=="NF")
    $where_tmp=" (comprobante.id_factura is null)";
    
if ($_POST['muestra']=="Muestra"){
	
	$fecha_desde=$_POST['fecha_desde'];
	$fecha_hasta=$_POST['fecha_hasta'];
	$link=encode_link("comprobante_excel.php",array("fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));?>
	<script>
	window.open('<?=$link?>')
	</script>	
<?}

if ($_POST['importar']=="Importar"){	
	$fecha_desde=fecha_db($_POST['fecha_desde']);
	$fecha_hasta=fecha_db($_POST['fecha_hasta']);
	

//------------------------------------------------------------------------------------------------------------------------
//							PRESTACIONES PARA SISTEMA SIGEP (MAYOR NUMERO DE REGISTRO)
//------------------------------------------------------------------------------------------------------------------------
    	$filename = 'SUMAR-CEB-12-sistema-padrones-' . date('Y-m-d') . '.txt';	
	  	if (!$handle = fopen($filename, 'w+')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
    	$sql1="SELECT 'A' as operacion,
				'L' as estado,
				facturacion.comprobante.id_factura as numero_comprobante,
				facturacion.anexo.numero as subcodigo_prestacion, --CUANDO DEVUELVE '0' EN EL ARCHIVO VA VACIO
				facturacion.prestacion.precio_prestacion as precio_unitario,
				facturacion.comprobante.fecha_comprobante::date as fecha_prestacion,
				nacer.smiafiliados.clavebeneficiario as clave_beneficiario,
				nacer.smiafiliados.afitipodoc as tipo_documento,
				nacer.smiafiliados.aficlasedoc as clase_documento,
				nacer.smiafiliados.afidni as numero_documento,
				' ' as id_dato_reportable,
				' ' as dato_reportable,
				'1' as orden, --VER ESTE CAMPO LOS VALORES POSIBLES Y PONER VALOR CORRECTO
				comprobante.cuie as efector,			  
				CASE WHEN facturacion.nomenclador_detalle.modo_facturacion='1' THEN facturacion.nomenclador.codigo
					 WHEN facturacion.nomenclador_detalle.modo_facturacion<>'1' THEN facturacion.nomenclador.grupo||facturacion.nomenclador.codigo||facturacion.prestacion.diagnostico
					END as codigo_prestacion
					FROM
					facturacion.prestacion
					left JOIN facturacion.comprobante ON (facturacion.comprobante.id_comprobante = facturacion.prestacion.id_comprobante)				  
					left JOIN facturacion.nomenclador ON (facturacion.prestacion.id_nomenclador = facturacion.nomenclador.id_nomenclador)
					left JOIN facturacion.nomenclador_detalle ON (facturacion.nomenclador.id_nomenclador_detalle=facturacion.nomenclador_detalle.id_nomenclador_detalle)
					left JOIN nacer.smiafiliados ON (facturacion.comprobante.id_smiafiliados = nacer.smiafiliados.id_smiafiliados)
					left JOIN facturacion.factura ON (comprobante.id_factura = factura.id_factura)
					left join facturacion.anexo on (prestacion.id_anexo = anexo.id_anexo)
				Where
					(fecha_comprobante between '$fecha_desde' and '$fecha_hasta') and factura.estado='C'
				order by comprobante.id_comprobante";

		$result1=sql($sql1) or die;
    	$result1->movefirst();  
    	 			
    	$contenido="operacion;estado;numero_comprobante;codigo_prestacion;subcodigo_prestacion;precio_unitario;fecha_prestacion;clave_beneficiario;tipo_documento;clase_documento;numero_documento;id_dato_reportable_1;dato_reportable_1;id_dato_reportable_2;dato_reportable_2;id_dato_reportable_3;dato_reportable_3;id_dato_reportable_4;dato_reportable_4;orden;cuie\r\n";			
    	if (fwrite($handle, $contenido) === FALSE) {
        	echo "No se Puede escribir  ($filename)";
        	exit;
    	}
    	while (!$result1->EOF) {    		
			$contenido=$result1->fields['operacion'];
			$contenido.=";";			
    		$contenido.=$result1->fields['estado'];
			$contenido.=";"; 
			$contenido.=$result1->fields['numero_comprobante'];
			$contenido.=";"; 
			$contenido.=str_replace(" ", "",$result1->fields['codigo_prestacion']);
			$contenido.=";"; 
			if ($result1->fields['subcodigo_prestacion']<>0) $contenido.=$result1->fields['subcodigo_prestacion'];
			$contenido.=";"; 
			$contenido.=$result1->fields['precio_unitario'];
			$contenido.=";"; 
			$contenido.=$result1->fields['fecha_prestacion'];
			$contenido.=";"; 
			$contenido.=$result1->fields['clave_beneficiario'];
			$contenido.=";"; 
			$contenido.=$result1->fields['tipo_documento'];
			$contenido.=";"; 
			$contenido.=trim($result1->fields['clase_documento']);
			$contenido.=";"; 
			$contenido.=$result1->fields['numero_documento'];
			$contenido.=";"; 
			$contenido.=$result1->fields['id_dato_reportable'];
			$contenido.=";"; 
			$contenido.=$result1->fields['dato_reportable'];
			$contenido.=";"; 
			$contenido.=$result1->fields['id_dato_reportable'];
			$contenido.=";"; 
			$contenido.=$result1->fields['dato_reportable'];
			$contenido.=";";
			$contenido.=$result1->fields['id_dato_reportable'];
			$contenido.=";"; 
			$contenido.=$result1->fields['dato_reportable'];
			$contenido.=";";
			$contenido.=$result1->fields['id_dato_reportable'];
			$contenido.=";"; 
			$contenido.=$result1->fields['dato_reportable'];
			$contenido.=";";
			$contenido.=$result1->fields['orden'];
			$contenido.=";"; 
    		$contenido.=$result1->fields['efector'];			
			$contenido.="\r\n";			
    		if (fwrite($handle, $contenido) === FALSE) {
        		echo "No se Puede escribir  ($filename)";
        		exit;
    		}
			
    		$result1->MoveNext();			
    	}
    	echo "El Archivo ($filename) se genero con exito <br>";    
    	fclose($handle);   	

 }
   
    
echo $html_header;
?>
<form name=form1 action="listado_comprobante.php" method=POST>
<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
	    
	    <?if (permisos_check('inicio','importa_rendicion_cuentas')){?>
	    <b>
	    &nbsp;&nbsp;&nbsp; || &nbsp;&nbsp;&nbsp;
	    Desde: <input type=text id="fecha_desde" name="fecha_desde" value='<?=$fecha_desde?>' size=15>
		<?=link_calendario("fecha_desde");?>
		
		Hasta: <input type=text id="fecha_hasta" name="fecha_hasta" value='<?=$fecha_hasta?>' size=15>
		<?=link_calendario("fecha_hasta");?> 
	    <?if ($_ses_user['login'] == 'fer') {?>
	    <input type="submit" name="importar" value='Importar'>
	    <?}?>
	    &nbsp;&nbsp;&nbsp;
	    <input type="submit" name="muestra" value='Muestra'>
	    </b>
	    <?}?>
	  </td>
     </tr>
</table>
<?$result = sql($sql) or die;?>
<table border=0 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
  <tr>
  	<td colspan=15 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  
  <tr>
    <td id=mo width=1%>&nbsp;</td>
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_comprobante.php",array("sort"=>"8","up"=>$up))?>' >Nro Comp</a></td>      	
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_comprobante.php",array("sort"=>"11","up"=>$up))?>' >Fecha Comp</a></td>      	
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_comprobante.php",array("sort"=>"9","up"=>$up))?>' >Nro Factura</a></td>      	
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_comprobante.php",array("sort"=>"1","up"=>$up))?>' >Apellido</a></td>      	
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_comprobante.php",array("sort"=>"2","up"=>$up))?>'>Nombre</a></td>
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_comprobante.php",array("sort"=>"3","up"=>$up))?>'>DNI</a></td>   
  </tr>
 <?
   while (!$result->EOF) {   	
    $id_tabla="tabla_".$result->fields['id_comprobante'];	
	$onclick_check=" javascript:(this.checked)?Mostrar('$id_tabla'):Ocultar('$id_tabla')";?>
  
    <tr <?=atrib_tr()?>>
     <td>
	  <input type=checkbox name=check_prestacion value="" onclick="<?=$onclick_check?>" class="estilos_check">
	 </td>     
     <td><?=$result->fields['id_comprobante']?></td>
     <td><?=Fecha($result->fields['fecha_comprobante'])?></td>
     <td ><?=$result->fields['id_factura']?></td>
     <td ><?=$result->fields['afiapellido']?></td>
     <td ><?=$result->fields['afinombre']?></td>
     <td ><?=$result->fields['afidni']?></td> 
    </tr>    
    <tr>
	          <td colspan=10>
	
	                  <?
	                  $sql=" select *
								from facturacion.prestacion 
								left join facturacion.nomenclador using (id_nomenclador)							
								where id_comprobante=". $result->fields['id_comprobante']." order by id_prestacion DESC";
	                  $result_items=sql($sql) or fin_pagina();
	                  ?>
	                  <div id=<?=$id_tabla?> style='display:none'>
	                  <table width=90% align=center class=bordes>
	                  			<?
	                  			$cantidad_items=$result_items->recordcount();
	                  			if ($cantidad_items==0){?>
		                            <tr>
		                            	<td colspan="10" align="center">
		                            		<b><font color="Red" size="+1">NO HAY PRESTACIONES PARA ESTE COMPROBANTE</font></b>
		                            	</td>	                                
			                        </tr>	                               
								<?}
								else{?>
		                           <tr id=ma>		                               
		                               <td>Cantidad</td>
		                               <td>Codigo</td>
		                               <td>Descripción</td>
		                               <td>Precio</td>
		                               <td>Total</td>	                               
		                            </tr>
		                            <?while (!$result_items->EOF){?>
			                            <tr>
			                            	 <td class="bordes"><?=$result_items->fields["cantidad"]?></td>			                                 
			                                 <td class="bordes"><?=$result_items->fields["codigo"]?></td>
			                                 <td class="bordes"><?=$result_items->fields["descripcion"]?></td>
			                                 <td class="bordes"><?=number_format($result_items->fields["precio_prestacion"],2,',','.')?></td>
			                                 <td class="bordes"><?=number_format($result_items->fields["cantidad"]*$result_items->fields["precio_prestacion"],2,',','.')?></td>
			                            </tr>
		                            	<?$result_items->movenext();
		                            }//del while
								}//del else?>
	                            	                            
	               </table>
	               </div>
	
	         </td>
	      </tr>  	
	<?$result->MoveNext();
    }?>
    
</table>
<br>
	<table align='center' border=1 bordercolor='#000000' bgcolor='#FFFFFF' width='80%' cellspacing=0 cellpadding=0>
     <tr>
      <td colspan=10 bordercolor='#FFFFFF'><b>Colores de Referencia para la Columna Número de Comprobante:</b></td>
     <tr>
     <td width=30% bordercolor='#FFFFFF'>
      <table border=1 bordercolor='#FFFFFF' cellspacing=0 cellpadding=0 width=100%>
       
       <tr>        
        <td width=30 bgcolor='AA888' bordercolor='#000000' height=30>&nbsp;</td>
        <td bordercolor='#FFFFFF'>Anulado</td>
       </tr>
      </table>
     </td>
    </table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
