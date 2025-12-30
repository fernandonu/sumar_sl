<?

require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

function extrae_anio($fecha) {
        list($d,$m,$a) = explode("/",$fecha);
        //$a=$a+2000;
        return $a;
		}

$user=$_ses_user['login'];

if ($id_efe_conv) {
	$query ="SELECT 
	efe_conv.*,dpto.nombre as dpto_nombre
	FROM
	nacer.efe_conv 
	left join nacer.dpto on dpto.codigo=efe_conv.departamento   
	where id_efe_conv=$id_efe_conv";
	
	$res_factura=sql($query, "Error al traer el Efector") or fin_pagina();
	
	$cuie=$res_factura->fields['cuie'];
	$nombre=$res_factura->fields['nombre'];
	$domicilio=$res_factura->fields['domicilio'];
	$departamento=$res_factura->fields['dpto_nombre'];
	$localidad=$res_factura->fields['localidad'];
	$cod_pos=$res_factura->fields['cod_pos'];
	$cuidad=$res_factura->fields['cuidad'];
	$referente=$res_factura->fields['referente'];
	$tel=$res_factura->fields['tel'];
	}
else {

	$cuie=$_ses_user['login'];
	$sql_cuie="SELECT * from nacer.efe_conv where cuie='$cuie'";
	$res_cuie= sql($sql_cuie, "Error al traer el Efector") or fin_pagina();
	$id_efe_conv=$res_cuie->fields['id_efe_conv'];
	}

if ($_POST['muestra']=="Muestra"){	
	
	$fecha_desde=fecha_db($_POST['fecha_desde']);
	$fecha_hasta=fecha_db($_POST['fecha_hasta']);
	
	$fecha_hoy=Date("d/m/Y");
	  
  $sql_id_nom="SELECT * from nacer.efe_conv where cuie='$cuie'";
  $res_id_nom=sql ($sql_id_nom) or fin_pagina();
  $id_nomenclador_detalle=$res_id_nom->fields['id_nomenclador_detalle'];

//contabilidad, saldos,ingresos,egresos,comprometidos
$sql="SELECT ingre,
      case when (egre is NULL) then 0
      else egre end as egre ,
      case when (monto_preventivo is NULL) then 0
      else monto_preventivo end as monto_preventivo,
      case when (egre_comp is NULL) then 0
      else egre_comp end as egre_comp
      from
        (select sum (monto_recurso)as ingre from proteger.ingreso
        where cuie='$cuie' and fecha_mod between '$fecha_desde' and '$fecha_hasta') as ingreso,
        (select sum (monto_egreso)as egre from proteger.egreso
        where cuie='$cuie' and pagado='si' and fecha_mod between '$fecha_desde' and '$fecha_hasta') as egreso,
        (select sum (monto_preventivo) as monto_preventivo from proteger.egreso
        where cuie='$cuie' and pagado='no' and fecha_mod between '$fecha_desde' and '$fecha_hasta') as preventivo,
        (select sum (monto_comprometido) as egre_comp from proteger.egreso
        where cuie='$cuie' and pagado='no' 
        and fecha_mod between '$fecha_desde' and '$fecha_hasta') as egre_comp";

$res_saldo=sql($sql,"no puede calcular el saldo");

$saldo=($res_saldo->fields['ingre']-$res_saldo->fields['egre']);
$total_egre_comp=$res_saldo->fields['egre_comp'];
$saldo_real=($saldo)-$total_egre_comp;
$uso_de_fondos=($res_saldo->fields['egre']/$res_saldo->fields['ingre'])*100;
$saldo_i=(100-$uso_de_fondos); //porcentual
$saldo_virtual=$saldo_real-$res_saldo->fields['monto_preventivo'];
$monto_preventivo=number_format($res_saldo->fields['monto_preventivo'],2,',','.');

$ingreso=number_format($res_saldo->fields['ingre'],2,',','.');
$egreso=number_format($res_saldo->fields['egre'],2,',','.');
$saldo = number_format($saldo,2,',','.');
$saldo_real = number_format($saldo_real,2,',','.');
$total_egre_comp=number_format($total_egre_comp,2,',','.');
$uso_de_fondos=number_format($uso_de_fondos,2,',','.');
$saldo_inmovilizado=number_format($saldo_i,2,',','.');
$saldo_virtual=number_format($saldo_virtual,2,',','.');

//end contabilidad, saldos,ingresos,egresos,comprometidos


}

if ($id_efe_conv) {
$query="SELECT 
  efe_conv.*,dpto.nombre as dpto_nombre
FROM
  nacer.efe_conv 
  left join nacer.dpto on dpto.codigo=efe_conv.departamento   
  where id_efe_conv=$id_efe_conv";

$res_factura=sql($query, "Error al traer el Efector") or fin_pagina();

$cuie=$res_factura->fields['cuie'];
$nombre=$res_factura->fields['nombre'];
$domicilio=$res_factura->fields['domicilio'];
$departamento=$res_factura->fields['dpto_nombre'];
$localidad=$res_factura->fields['localidad'];
$cod_pos=$res_factura->fields['cod_pos'];
$cuidad=$res_factura->fields['cuidad'];
$referente=$res_factura->fields['referente'];
$tel=$res_factura->fields['tel'];

}

echo $html_header;
?>

<script>

function control_muestra()
{ 
 if(document.all.fecha_desde.value==""){
  alert('Debe Ingresar una Fecha DESDE');
  return false;
 } 
 if(document.all.fecha_hasta.value==""){
  alert('Debe Ingresar una Fecha HASTA');
  return false;
 } 
 if(document.all.fecha_hasta.value<document.all.fecha_desde.value){
  alert('La Fecha HASTA debe ser MAYOR 0 IGUAL a la Fecha DESDE');
  return false;
 }
 if(document.all.fecha_desde.value.indexOf("-")!=-1){
	  alert('Debe ingresar un fecha en el campo DESDE');
	  return false;
	 }
if(document.all.fecha_hasta.value.indexOf("-")!=-1){
	  alert('Debe ingresar una fecha en el campo HASTA');
	  return false;
	 }
return true;
}

var img_ext='<?=$img_ext='f_right1.png' ?>';//imagen extendido
var img_cont='<?=$img_cont='f_down1.png' ?>';//imagen contraido

function muestra_tabla(obj_tabla,nro){
	 oimg=eval("document.all.imagen_"+nro);//objeto tipo IMG
	 if (obj_tabla.style.display=='none'){
	 	obj_tabla.style.display='inline';
	    oimg.show=0;
	    oimg.src=img_ext;
	 }
	 else{
	 	obj_tabla.style.display='none';
	    oimg.show=1;
		oimg.src=img_cont;
	 }
	}
</script>

<form name='form1' action='detalle_gestion2.php' method='POST'>
<input type="hidden" value="<?=$id_efe_conv?>" name="id_efe_conv">
<input type="hidden" value="<?=$cuie?>" name="cuie">

<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<b>	
		<?if ($fecha_desde=='') $fecha_desde=DATE ('01/01/2019');
		if ($fecha_hasta=='') $fecha_hasta=DATE ('31/12/2019');?>		
		Desde: <input type=text id=fecha_desde name=fecha_desde value='<?=$fecha_desde?>' size=15 readonly>
		<?=link_calendario("fecha_desde");?>
		
		Hasta: <input type=text id=fecha_hasta name=fecha_hasta value='<?=$fecha_hasta?>' size=15 readonly>
		<?=link_calendario("fecha_hasta");?> 
		
		   
	    
	    &nbsp;&nbsp;&nbsp;
	    <input type="submit" class="btn btn-success" name="muestra" value='Muestra' onclick="return control_muestra()" >
	    </b>
	    
	    &nbsp;&nbsp;&nbsp;	    
        <?if ($_POST['muestra']){
         	
        $link=encode_link("efec_cumplimiento_pdf.php",array("id_efe_conv"=>$id_efe_conv,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));?>
        <!--<img src="../../imagenes/pdf_logo.gif" style='cursor:hand;'  onclick="window.open('<?=$link?>')">-->
        <?}?>
	  </td>
       
     </tr>
     
    
     
</table>
<table width="90%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
    	<font size=+1><b>Efector: <?echo $cuie.". Desde: ".fecha($fecha_desde)." Hasta: ".fecha($fecha_hasta)?> </b></font>        
    </td>
 </tr>
 <tr><td>
  <table width=100% align="center" class="bordes">
     <tr>
      <td id=mo colspan="5">
       <b><font size=2> Descripcion del Efector</font></b>
      </td>
     </tr>
     <tr>
       <td>
        <table align="center">
                
         <td align="right">
				<b>Nombre:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$nombre?>" name="nombre" readonly>
            </td>
         </tr>
         
         <tr>	           
           
         <tr>
         <td align="right">
				<b>Domicilio:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$domicilio?>" name="domicilio" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Departamento:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$departamento?>" name="departamento" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Localidad:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$localidad?>" name="localidad" readonly>
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
              <input type="text" size="40" value="<?=$cod_pos?>" name="cod_pos" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Cuidad:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$cuidad?>" name="cuidad" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Referente:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$referente?>" name="referente" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Telefono:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?=$tel?>" name="tel" readonly>
            </td>
         </tr>          
        </table>
      </td>  
       
     </tr> 
           
 </table>           

<?if ($_POST['muestra']){?>
<table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
		<tr align="center" id="sub_tabla">
		 	
</table>
<table>
	<table width=100% align="center" class="bordes">
  <tr><td id="mo" colspan="5">
  <b><font size=2>Evaluacion de Gestion</font></b>
  </td></tr>
	
  <tr><td><table width=95% align="center" class="bordes">	    
      <tr>
			<td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
			<font size=3 color= red><b>Uso de Fondos</b> </font>
			</td>   
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
			<font size=2>Ingreso en Pesos: </br><b><?=($ingreso)?$ingreso:0?> </b></font>
			</td>
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
			<font size=2>Egreso en Pesos: </br><b><?=($egreso)?$egreso:0?></b></font>
			</td>
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
			<font size=2>Uso de Fondos (%): </br><b><?=($uso_de_fondos)?$uso_de_fondos:0?></b></font>
			</td>
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
			<font size=2>Saldo Inmovilizado (%): </br><b><?=($saldo_inmovilizado)?$saldo_inmovilizado:0?></b></font>
			</td>
			</tr>
      </table></td></tr>
   
	
  <tr><td><table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
			
			<tr>
			<td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
			<font size=3 color= red> <b>Dinero Disponible  </b></font>
			</td>   
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
			<font size=2>Saldo en Pesos (Ingreso - Egreso): <br/><b><?=($saldo)?$saldo:0?> </b> </font>
			</td>
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
			<font size=2>Total Montos Preventivos: <br/><b><?=($monto_preventivo)?$monto_preventivo:0?> </b> </font>
			</td>


			<td align="center"  border=1 bordercolor=#2C1701 <?=atrib_tr7()?>>
			<font size=2>Total Expedientes Comprometidos (No Pagos): <br/><b><?=($total_egre_comp)?$total_egre_comp:0?> </b> </font>
			</td>
				  
			<?if ($saldo_real<0) $atrib=atrib_tr5();
			else $atrib=atrib_tr8();?>
			<td align="center"  border=1 bordercolor=#2C1701 <?=$atrib?>>
			<font size=2>Saldo Real (Saldo - Monto Comprom.): <br/><b><?=($saldo_real)?$saldo_real:0?> </b> </font>
			</td>
			</tr>

			<?if ($saldo_virtual<0) $atrib=atrib_tr5();
			else $atrib=atrib_tr8();?>
			<td align="center"  border=1 bordercolor=#2C1701 <?=$atrib?>>
			<font color="blue" size=2>Saldo Virtual (Saldo - Monto Prevent.): <br/><b><?=($saldo_virtual)?$saldo_virtual:0?> </b> </font>
			</td>
			</tr>
	</table></td></tr>
			
			<br/>
<?}?>			
  	
<BR>
 <tr><td><table width=90% align="center" class="bordes">
 	<tr>
 		<table>
	<h3 align="center" style="font-family: 'Helvetica', Times, serif;  font-size:2em; color: #727BF7">Ante cualquier consulta, dirigirse por mail: <b>programaprotegersanluis@gmail.com</b></h3> 	
 	</table>
 	</tr>
  <tr align="center">
  		<td>
     	<input type="button" class="btn btn-primary" name="volver" value="Volver" onclick="document.location='detalle_gestion2.php'"title="Volver al Listado" style="width=150px">     
   	</td>
   
  </tr>

 </table></td></tr>
 
  </table>

 </table>
 	
 </form>
 
 <?=fin_pagina();// aca termino ?>
