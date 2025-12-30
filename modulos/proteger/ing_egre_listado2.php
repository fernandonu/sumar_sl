<?php

require_once("../../config.php");

variables_form_busqueda("ing_egre_listado");

if ($_POST['generar']=="Generar"){
	$fecha_hasta=$_POST['fecha_hasta'];
	$ref = encode_link("ing_egre_excel2.php",array("fecha_hasta"=>$fecha_hasta));?>
	<script>
	window.open('<?=$ref?>')
	</script>
	
<?}


if ($_POST['listado_excel']=="Listado EXCEL"){
  $fecha_hasta=$_POST['fecha_hasta'];
  $ref = encode_link("ingre_egre_listado_excel2.php",array("fecha_hasta"=>$fecha_hasta));?>
  <script>
  window.open('<?=$ref?>')
  </script>
  
<?}

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

if ($cmd == "")  $cmd="VERDADERO";

$orden = array(
        "default" => "1",
        "1" => "cuie",
        "2" => "nombre",
        "3" => "domicilio",
        "4" => "cuidad",         
       );
$filtro = array(
		"cuie" => "CUIE",
        "nombre" => "Nombre",                
       );


$sql_tmp="SELECT 
  nacer.efe_conv.id_efe_conv,
  nacer.efe_conv.nombre,
  nacer.efe_conv.domicilio,
  nacer.efe_conv.departamento,
  nacer.efe_conv.localidad,
  nacer.efe_conv.cod_pos,
  nacer.efe_conv.cuidad,
  nacer.efe_conv.referente,
  nacer.efe_conv.tel,
  nacer.efe_conv.mail,
  nacer.efe_conv.com_gestion,
  nacer.efe_conv.com_gestion_firmante,
  nacer.efe_conv.fecha_comp_ges,
  nacer.efe_conv.fecha_fin_comp_ges,
  nacer.efe_conv.com_gestion_pago_indirecto,
  nacer.efe_conv.tercero_admin,
  nacer.efe_conv.tercero_admin_firmante,
  nacer.efe_conv.fecha_tercero_admin,
  nacer.efe_conv.fecha_fin_tercero_admin,
  nacer.efe_conv.cuie
FROM
  nacer.efe_conv";

$where_tmp=" (nacer.efe_conv.proteger = 'S')";
    



echo $html_header;
?>
<form name=form1 action="ing_egre_listado2.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;
	    <input type=submit name="buscar" value='Buscar'>
	    &nbsp;&nbsp;
      Hasta: <input type="text" name="fecha_hasta" value="aaaa-mm-dd" maxlength="10" size="12">
	    <input type="submit" name="generar" value='Generar'> 
      <input type="submit" name="listado_excel" value='Listado EXCEL'>

	  </td>
     </tr>
</table>

<?$result = sql($sql) or die;?>

<table border=0 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
  <tr>
  	<td colspan=11 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  
<table width=100% align="center">
  <tr>
    <td align=right id=mo><a id=mo href='<?=encode_link("ing_egre_listado2.php",array("sort"=>"1","up"=>$up))?>'>CUIE</a></td>      	
    <td align=right id=mo><a id=mo href='<?=encode_link("ing_egre_listado2.php",array("sort"=>"2","up"=>$up))?>'>Nombre</a></td>
    <!--<td align=right id=mo><a id=mo href='<?=encode_link("ing_egre_listado2.php",array("sort"=>"3","up"=>$up))?>' >Domicilio</a></td>    -->
    <td align=right id=mo><a id=mo href='<?=encode_link("ing_egre_listado2.php",array("sort"=>"4","up"=>$up))?>'>Cuidad</a></td>        
    <td align=right id=mo title="Todas las facturas Depositadas">Ingreso</td>        
    <td align=right id=mo title="Todos los exp de insumos o servicios Pagados">Egreso</td>        
    <td align=right id=mo title="Depositado(ingreso) - Exp Pagados(egreso)">Saldo</td> 
    <td align=right id=mo title="Todos los exp de insumos o servicios Pagados">Egreso Compr.</td>
    <td align=right id=mo title="Ingreso - Egreso - Saldo Comprometido">Saldo Real</td> 
    <!--<td align=right id=mo title="Preventivo">Preventivo</td>
    <td align=right id=mo title="Saldo Real - Preventivo">Saldo Virtual</td>-->
    
    <td align=right id=mo title="ingreso_excel">Det.Ingreso</td>
    <td align=right id=mo title="egreso_excel"Det.>Det.Egreso</td>
    
  </tr>
 <?
   while (!$result->EOF) {
  	$ref = encode_link("ingre_egre_admin2.php",array("cuie"=>$result->fields['cuie']));
    $onclick_elegir="location.href='$ref'";
    
   
    
    $cuie=$result->fields['cuie'];
  		$sql="SELECT monto_egreso from proteger.egreso
		where cuie='$cuie'";
		$res_egreso=sql($sql,"no puede calcular el saldo");
    
	if ($res_egreso->recordCount()==0){
		$sql="SELECT ingre as total, ingre,egre,deve,egre_comp from
			(select sum (monto_recurso)as ingre from proteger.ingreso
			where cuie='$cuie') as ingreso,
			(select sum (monto_egreso)as egre from proteger.egreso
			where cuie='$cuie' and monto_comprometido <> 0) as egreso,
			(select sum (monto_recurso)as deve from proteger.ingreso
			where cuie='$cuie') as devengado,
			(select sum (monto_comprometido)as egre_comp from proteger.egreso
			where cuie='$cuie') as egre_comp";

		}
	else{
		$sql="SELECT case when (egre is NULL) then ingre-0
      else ingre-egre end as total_real, ingre,egre,
        case when (egre_comp is null) then 0
          else egre_comp end, 
        case when (egre_comp<>0) then ingre-egre-egre_comp
          when (egre_comp=0 or egre_comp is NULL) then ingre-egre
             end as total_virtual,
        case when (monto_preventivo is NULL) then 0
		      else monto_preventivo end as monto_preventivo
              from
        (select sum (monto_recurso)as ingre from proteger.ingreso
        where cuie='$cuie') as ingreso,
        (select sum (monto_egreso)as egre from proteger.egreso
        where cuie='$cuie' and pagado='si') as egreso,
        (select sum (monto_comprometido) as egre_comp from proteger.egreso
        where cuie='$cuie' and monto_egreso=0 and pagado='no') as egre_comp,
        (select sum (monto_preventivo) as monto_preventivo from proteger.egreso
        where cuie='$cuie' and pagado='no') as preventivo";
		}
		$res_saldo=sql($sql,"no puede calcular el saldo");

 		$total_depositado=$res_saldo->fields['ingre'];//lo uso en ecuacion mas adelante
		
		if ($res_color->fields['monto_recurso']==$res_color->fields['monto_deposito'])
			$color_fondo="";
		else if (($res_color->fields['monto_recurso']>$res_color->fields['monto_deposito'])and (($res_color->fields['dias_demora'])>30))
			$color_fondo="#FF9999";
		else
			$color_fondo="#FFFFCC";			
		?>
    
		
		
    <tr <?=atrib_tr()?>>        
     <td onclick="<?=$onclick_elegir?>" bgcolor='<?=$color_fondo?>'><?=$result->fields['cuie']?></td>
     <td align='center 'onclick="<?=$onclick_elegir?>"bgcolor='<?=$color_fondo?>'><?=$result->fields['nombre']?></td>
     <!--<td onclick="<?=$onclick_elegir?>"bgcolor='<?=$color_fondo?>'><?=$result->fields['domicilio']?></td>     -->
     <td onclick="<?=$onclick_elegir?>" bgcolor='<?=$color_fondo?>'><?=$result->fields['cuidad']?></td> 
     <td onclick="<?=$onclick_elegir?>" align='center' bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['ingre'],2,',','.')?></td>         
     <td onclick="<?=$onclick_elegir?>" align='center' bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['egre'],2,',','.')?></td>         
     <td onclick="<?=$onclick_elegir?>" align='center' bgcolor='<?=$color_fondo?>'><b><?=number_format($res_saldo->fields['ingre']-$res_saldo->fields['egre'],2,',','.')?></b></td>
     <!--<td bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['total'],2,',','.')?></td>          -->
     <td onclick="<?=$onclick_elegir?>" align='center' bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['egre_comp'],2,',','.')?></td>
     <?if ((($total_depositado-$res_saldo->fields['egre']-($res_saldo->fields['egre_comp']-$res_saldo->fields['egre'])))<0)$color_fondo1="#BE81F7";
      else $color_fondo1="";?>
     <!--<td onclick="<?=$onclick_elegir?>" bgcolor='<?=$color_fondo1?>'><b><?=number_format($total_depositado-$res_saldo->fields['egre']-($res_saldo->fields['egre_comp']-$res_saldo->fields['egre']),2,',','.')?></b></td>-->          
     <td onclick="<?=$onclick_elegir?>" bgcolor='<?=$color_fondo1?>'><b><?=number_format($res_saldo->fields['ingre']-$res_saldo->fields['egre']-$res_saldo->fields['egre_comp'],2,',','.')?></b></td>
     <!--<td onclick="<?=$onclick_elegir?>" align='center' bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['monto_preventivo'],2,',','.')?></td>
     <td onclick="<?=$onclick_elegir?>" align='center' bgcolor='<?=$color_fondo?>'><b><?=number_format($res_saldo->fields['ingre']-$res_saldo->fields['egre']-$res_saldo->fields['egre_comp']-$res_saldo->fields['monto_preventivo'],2,',','.')?></b></td>-->
    
    
       <td align="center" bgcolor='<?=$color_fondo?>'>
         <?$link=encode_link("ingreso_excel2.php", array("cuie"=>$result->fields['cuie']));  
       echo "<a target='_blank' href='".$link."' title='Listado de Ingresos Excel'><IMG src='$html_root/imagenes/excel.gif' height='20' width='20' border='0'></a>";?>
       </td>
       
       <td align="center" bgcolor='<?=$color_fondo?>'>
         <?$link=encode_link("egreso_excel2.php", array("cuie"=>$result->fields['cuie']));  
       echo "<a target='_blank' href='".$link."' title='Listado de Egresos Excel'><IMG src='$html_root/imagenes/excel.gif' height='20' width='20' border='0'></a>";?>
       </td>
    </tr>
  
	<?$result->MoveNext();
    }?>
  </table>   
</table>
<br>
	<table align='center' border=1 bordercolor='#000000' bgcolor='#FFFFFF' width='80%' cellspacing=0 cellpadding=0>
     <tr>
      <td colspan=10 bordercolor='#FFFFFF'><b>Colores de Referencia para el Listado:</b></td>
     <tr>
     <td width=30% bordercolor='#FFFFFF'>
      <table border=1 bordercolor='#FFFFFF' cellspacing=0 cellpadding=0 width=100%>
       <tr>
        <td width=30 bgcolor='#FFFFCC' bordercolor='#000000' height=30>&nbsp;</td>
        <td bordercolor='#FFFFFF'>Efector con saldo Devengado no Depositado</td>
       </tr>
       <tr>
       	<td>
       	 &nbsp;
       	</td>
       </tr>
       <tr>        
        <td width=30 bgcolor='FF9999' bordercolor='#000000' height=30>&nbsp;</td>
        <td bordercolor='#FFFFFF'>Efector con saldo Devengado no Depositado > 30 dias!!</td>
       </tr> 
       <tr>
       	<td>
       	 &nbsp;
       	</td>
       </tr>
       <tr>        
        <td width=30 bgcolor='BE81F7' bordercolor='#000000' height=30>&nbsp;</td>
        <td bordercolor='#FFFFFF'>Saldo Real Negativo</td>
       </tr>       
      </table>
     </td>
    </table>
    
    <table align='center' border=1 bordercolor='#000000' bgcolor='#FFFFFF' width='80%' cellspacing=0 cellpadding=0>
     <tr>
      <td colspan=10 bordercolor='#FFFFFF'><b>Leyenda de referencia para las formulas:</b></td>
     <tr>
     <td width=30% bordercolor='#FFFFFF'>
      <table border=1 bordercolor='#FFFFFF' cellspacing=0 cellpadding=0 width=100%>
       <tr>
        <td width=30 bgcolor='#A5DF00' bordercolor='#000000' height=30>&nbsp;</td>
        <td bordercolor='#FFFFFF'>INGRESO - EGRESO = SALDO</td>
       </tr>
       <tr>
       	<td>
       	 &nbsp;
       	</td>
       </tr>
       <tr>        
        <td width=30 bgcolor='#A5DF00' bordercolor='#000000' height=30>&nbsp;</td>
        <td bordercolor='#FFFFFF'>INGRESO - EGRESO COMPROMETIDO = SALDO REAL</td>
       </tr> 
       <tr>
       	<td>
       	 &nbsp;
       	</td>
       </tr>
          
      </table>
     </td>
    </table>
    
    
    
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>