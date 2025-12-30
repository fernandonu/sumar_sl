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
	$anio_corr=extrae_anio($fecha_hoy);
	$fecha_desde_sem_1="$anio_corr"."-01-01";
	$fecha_hasta_sem_1="$anio_corr"."-06-30";
	
	$fecha_desde_sem_2="$anio_corr"."-07-01";
	$fecha_hasta_sem_2="$anio_corr"."-12-31";
  
  $sql_id_nom="SELECT * from nacer.efe_conv where cuie='$cuie'";
  $res_id_nom=sql ($sql_id_nom) or fin_pagina();
  $id_nomenclador_detalle=$res_id_nom->fields['id_nomenclador_detalle'];

//sumatoria de los expedientes comprometidos

/*$sql_comp_sum="SELECT sum(monto_egre_comp) as total from (
SELECT 
  id_egreso,monto_egre_comp,fecha_egre_comp,comentario
FROM
  contabilidad.egreso  
  left join facturacion.servicio using (id_servicio) 
  left join contabilidad.inciso using (id_inciso) 
  where cuie='$cuie' and monto_egre_comp <> 0 and monto_egreso = 0 --and fecha_egre_comp between '$fecha_desde' and '$fecha_hasta'
  order by id_egreso DESC
  ) as sumas";*/

$sql_comp_sum="SELECT sum (monto_egre_comp) as total from contabilidad.egreso
			where cuie='$cuie'";
$sql_res_comp_sum=sql($sql_comp_sum,"No se puede traer los datos para la suma de los expedientes comprometidos");//
	
	
//contabilidad, saldos,ingresos,egresos,comprometidos
$sql="SELECT ingre-egre as total, ingre,egre,deve,egre_comp from
		(select sum (monto_deposito)as ingre from contabilidad.ingreso
		where cuie='$cuie') as ingreso,
		(select sum (monto_egreso)as egre from contabilidad.egreso
		where cuie='$cuie' and monto_egre_comp <> 0) as egreso,
		(select sum (monto_factura)as deve from contabilidad.ingreso
		where cuie='$cuie') as devengado,
		(select sum (monto_egre_comp)as egre_comp from contabilidad.egreso
		where cuie='$cuie') as egre_comp";

$res_saldo=sql($sql,"no puede calcular el saldo");
$total_depositado=number_format($res_saldo->fields['ingre'],2,',','.');
//$total_egre_comp=number_format($res_saldo->fields['egre_comp'],2,',','.');
$total_egre_comp=number_format($sql_res_comp_sum->fields['total'],2,',','.');
$total=number_format($res_saldo->fields['total'],2,',','.');
$ingreso=number_format($res_saldo->fields['ingre'],2,',','.');
$egreso=number_format($res_saldo->fields['egre'],2,',','.');
$egreso_compr_no_pago=$sql_res_comp_sum->fields['total']-$res_saldo->fields['egre'];
//$saldo_real=$total_depositado-$egreso-($total_egre_comp-$egreso);
//$saldo_real = $res_saldo->fields['total']-$sql_res_comp_sum->fields['total'];
//$saldo_real = $res_saldo->fields['total']-$egreso_compr_no_pago;
$saldo_real=($res_saldo->fields['ingre']-$res_saldo->fields['egre'])-$egreso_compr_no_pago;
$saldo_real = number_format($saldo_real,2,',','.');
$egreso_compr_no_pago=number_format($egreso_compr_no_pago,2,',','.');
$saldo_p=$total_egre_comp-$egreso;
$saldo_p=number_format($saldo_p,2,',','.');

//$uso_f=($egreso/$ingreso)*100;
$uso_f=($res_saldo->fields['ingre']<>0)?($res_saldo->fields['egre']/$res_saldo->fields['ingre'])*100:0;
$uso_de_fondos=number_format($uso_f,2,',','.');
$saldo_i=(100-$uso_f);
$saldo_inmovilizado=number_format($saldo_i,2,',','.');

//end contabilidad, saldos,ingresos,egresos,comprometidos

//facturas pagadas
$sql_fac="SELECT id_factura,fecha_ing,periodo,monto from expediente.expediente where estado='C' 
and id_efe_conv=(select id_efe_conv from nacer.efe_conv where cuie='$cuie' limit 1) order by fecha_ing";
$sql_fact=sql($sql_fac,"No se Puede abrir la base de datos de facturas");
//end facturas pagadas

//detalles incentivos
$no_cumle="SELECT id_factura,monto_factura,monto_incentivo from contabilidad.incentivo where cuie='$cuie' and cumple='0'";

$cumple="SELECT id_factura,monto_factura,monto_incentivo from contabilidad.incentivo where cuie='$cuie' and cumple='1'";

$pendientes="SELECT id_factura,monto_factura,monto_incentivo from contabilidad.incentivo where cuie='$cuie' and cumple='2'";

$parciales="SELECT id_factura,monto_factura,monto_incentivo from contabilidad.incentivo where cuie='$cuie' and cumple='3'";

$sql_incetivos_totales="SELECT cumple,sum(monto_factura),sum(monto_incentivo) from contabilidad.incentivo where cuie='$cuie'
group by cumple";


//codigos facturados por periodos
$sql_codigos="SELECT * from (
select id_nomenclador,count(id_nomenclador) as cantidad from (
select * from facturacion.comprobante where cuie='$cuie'  and fecha_comprobante between '$fecha_desde' and '$fecha_hasta') as comprobantes
inner join facturacion.prestacion using (id_comprobante) 
group by (id_nomenclador)
) as codigos_nomenclador
inner join (select id_nomenclador,codigo,grupo,subgrupo,descripcion from facturacion.nomenclador) as nomenclador using (id_nomenclador)
order by codigo";
$sql_cod=sql($sql_codigos,"No se puede abrir la base de datos de Codigos Facturados");



//codigos de prestaciones NO facturados por el centro

$sql_prestaciones="SELECT codigo,nomenclador.grupo,case when (char_length (nomenclador.descripcion) >70) then substring(nomenclador.descripcion from 0 for 70)||'...' else nomenclador.descripcion end as descripcion,especialidad,especialidades.grupo as grupo_nomenclador,color from (
select * from (
select id_nomenclador from facturacion.nomenclador where id_nomenclador_detalle=$id_nomenclador_detalle
order by id_nomenclador
) as nomenclador
except 

select id_nomenclador from (
select * from facturacion.comprobante where cuie='$cuie'  and fecha_comprobante between '$fecha_desde' and '$fecha_hasta') as comprobantes
inner join facturacion.prestacion using (id_comprobante) 
group by (id_nomenclador) order by id_nomenclador
) as codigos_no_facturados
inner join facturacion.nomenclador using (id_nomenclador) 
inner join nomenclador.especialidades on especialidades.descripcion=nomenclador.descripcion
order by 5,4,2,1";

$sql_pres=sql($sql_prestaciones,"No se pudo abrir la base de datos de prestaciones no facturadas por el centro");

//end codigos de prestaciones ...

$sql_1="SELECT sum (monto_egre_comp)as egre_incentivo
		from contabilidad.egreso
		where cuie='$cuie' and id_inciso=1";
$res_incentivo=sql($sql_1,"no puede calcular el saldo");
$total_incentivo=number_format($res_incentivo->fields['egre_incentivo'],2,',','.');	   			
//incentivos
$sql_inc="SELECT monto_egreso from (
select * from contabilidad.egreso where id_servicio=1 and id_inciso=1 
and comentario ilike '%Suma de Incentivo correspondiente en semestre%' and cuie='$cuie' ) as t1

where fecha_egreso = (select max (fecha_egreso) from (

select * from contabilidad.egreso where id_servicio=1 and id_inciso=1 
and comentario ilike '%Suma de Incentivo correspondiente en semestre%' and cuie='$cuie' ) as t2)";

$sql_res_inc=sql($sql_inc,"No se Puede calcular los montos de Incentivos");

$sql_acum_1="SELECT sum(monto_incentivo) as total from contabilidad.incentivo where cuie='$cuie' 
			and cumple='2' and fecha_prefactura between 
			'$fecha_desde_sem_1' and '$fecha_hasta_sem_1'";
$sql_res_acum_1=sql($sql_acum_1,"No se puede calcular el acumulado de incentivo");

$sql_acum_2="SELECT sum(monto_incentivo) as total from contabilidad.incentivo where cuie='$cuie' 
			and cumple='2' and fecha_prefactura between 
			'$fecha_desde_sem_2' and '$fecha_hasta_sem_2'";
$sql_res_acum_2=sql($sql_acum_2,"No se puede calcular el acumulado de incentivo");



//expedientes comprometidos

$sql_comp_sum="SELECT sum(monto_egre_comp) as total from (
SELECT 
  id_egreso,monto_egre_comp,fecha_egre_comp,comentario
FROM
  contabilidad.egreso  
  left join facturacion.servicio using (id_servicio) 
  left join contabilidad.inciso using (id_inciso) 
  where cuie='$cuie' and monto_egre_comp <> 0 and monto_egreso = 0 --and fecha_egre_comp between '$fecha_desde' and '$fecha_hasta'
  order by id_egreso DESC
  ) as sumas";
$sql_res_comp_sum=sql($sql_comp_sum,"No se puede traer los datos para la suma de los expedientes comprometidos");

$sql_comp="SELECT 
  id_egreso,monto_egre_comp,fecha_egre_comp,comentario
FROM
  contabilidad.egreso  
  left join facturacion.servicio using (id_servicio) 
  left join contabilidad.inciso using (id_inciso) 
  where cuie='$cuie' and monto_egre_comp <> 0 and monto_egreso = 0 --and fecha_egre_comp between '$fecha_desde' and '$fecha_hasta'
  order by id_egreso DESC";
$sql_res_comp=sql($sql_comp,"No se puede traer los datos de los expedientes comprometidos");

//Consultas redundantes - para poder medir la ceb nesecito los datos desde facturacion.
//afiliados con ceb por el centro
$sql_ceb="SELECT afiapellido,afinombre,afidni,afifechanac,activo,cuieefectorasignado,cuielugaratencionhabitual,fechainscripcion,ceb,cuie_ceb,GrupoPoblacional
from nacer.smiafiliados where cuie_ceb='$cuie' and fechainscripcion between '$fecha_desde' and '$fecha_hasta' and ceb='S'";
//end consultas redundantes


//Beneficiarios inscriptos por el efector (acumulado)
$sql_insc_total="SELECT grupopoblacional, count (*) as cantidad
			from nacer.smiafiliados 
			where (cuieefectorasignado='$cuie' 
			or cuielugaratencionhabitual='$cuie' 
			or cuie_efector_a_cargo='$cuie)')
			and grupopoblacional is not null
			group by grupopoblacional
			order by 1";

$sql_ins_total=sql($sql_insc_total) or fin_pagina();

$sql_ins_total->movefirst();
$ins_tot_a=$ins_tot_b=$ins_tot_c=$ins_tot_d=$ins_tot_e=$ins_tot_f=0;
while (!$sql_ins_total->EOF) {
	$grupo=$sql_ins_total->fields['grupopoblacional'];
	switch ($grupo) {
		case 'A' : $ins_tot_a=$sql_ins_total->fields['cantidad'];break;//de 0 a 5
		case 'B' : $ins_tot_b=$sql_ins_total->fields['cantidad'];break;//de 6 a 9
		case 'C' : $ins_tot_c=$sql_ins_total->fields['cantidad'];break;//adolescentes
		case 'D' : $ins_tot_d=$sql_ins_total->fields['cantidad'];break;//mujeres
		case 'E' : $ins_tot_e=$sql_ins_total->fields['cantidad'];break;//Hombres
		case 'F' : $ins_tot_f=$sql_ins_total->fields['cantidad'];break;//Adultos Mayores
		default : break;
	}
	$sql_ins_total->movenext();
}


//Beneficiarios inscriptos por el efector
$sql_insc="SELECT grupopoblacional, count (*) as cantidad
			from nacer.smiafiliados 
			where (cuieefectorasignado='$cuie' 
			or cuielugaratencionhabitual='$cuie' 
			or cuie_efector_a_cargo='$cuie)')
			and fechainscripcion between '$fecha_desde' and '$fecha_hasta' 
			and grupopoblacional is not null
			group by grupopoblacional
			order by 1";

$sql_res_ins=sql($sql_insc) or fin_pagina();

$sql_res_ins->movefirst();
$ins_a=$ins_b=$ins_c=$ins_d=$ins_e=$ins_f=0;
while (!$sql_res_ins->EOF) {
	$grupo=$sql_res_ins->fields['grupopoblacional'];
	switch ($grupo) {
		case 'A' : $ins_a=$sql_res_ins->fields['cantidad'];break;//de 0 a 5
		case 'B' : $ins_b=$sql_res_ins->fields['cantidad'];break;//de 6 a 9
		case 'C' : $ins_c=$sql_res_ins->fields['cantidad'];break;//adolescentes
		case 'D' : $ins_d=$sql_res_ins->fields['cantidad'];break;//mujeres
		case 'E' : $ins_e=$sql_res_ins->fields['cantidad'];break;//Hombres
		case 'F' : $ins_f=$sql_res_ins->fields['cantidad'];break;//Adultos Mayores
		default : break;
	}
	$sql_res_ins->movenext();
}

//consulta para sacar el ceb
$sql_ceb="SELECT e.grupopoblacional as grupo, count  (*) as cantidad
		from facturacion.prestacion a,
		facturacion.comprobante b,
		facturacion.nomenclador d,
		nacer.smiafiliados e
		where a.id_comprobante = b.id_comprobante
		and a.id_nomenclador = d.id_nomenclador
		and b.id_smiafiliados = e.id_smiafiliados
		and b.cuie = '$cuie'
		and b.fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
		and d.ceb = 's'
		and e.grupopoblacional is not null
		group by e.grupopoblacional
		order by 1";

$sql_res_ceb=sql($sql_ceb,"No se pudo calcular el ceb del efector");

$sql_res_ceb->movefirst();
$ceb_a=$ceb_b=$ceb_c=$ceb_d=$ceb_e=$ceb_f=0;
while (!$sql_res_ceb->EOF) {
	$grupo=$sql_res_ceb->fields['grupo'];
	switch ($grupo) {
		case 'A' : $ceb_a=$sql_res_ceb->fields['cantidad'];break;//de 0 a 5
		case 'B' : $ceb_b=$sql_res_ceb->fields['cantidad'];break;//de 6 a 9
		case 'C' : $ceb_c=$sql_res_ceb->fields['cantidad'];break;//adolescentes
		case 'D' : $ceb_d=$sql_res_ceb->fields['cantidad'];break;//mujeres
		case 'E' : $ceb_e=$sql_res_ceb->fields['cantidad'];break;//Hombres
		case 'F' : $ceb_f=$sql_res_ceb->fields['cantidad'];break;//Adultos Mayores
		default : break;
	}
	$sql_res_ceb->movenext();
}

//metas CEB del efector
$sql_metas="SELECT * from nacer.metas where cuie='$cuie'";
//$sql_res_metas=sql($sql_metas,"No se pudo traer los datos de metas del efector");

//metas segun RRHH
$sql_metasrrhh="SELECT * from nacer.metas_ceb where cuie='$cuie'";
//$sql_res_metasrrhh=sql($sql_metasrrhh,"No se pudo traer los datos de metas del CEB del efector");

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

<type="text/css" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css" />
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function() {
   $('#imagen_pres_no_pagas').click(function(){ 
    $('#prestaciones').DataTable( {
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    } );
} );
 });
</script>

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

var img_ext='<?php echo $img_ext='f_right1.png' ?>';//imagen extendido
var img_cont='<?php echo $img_cont='f_down1.png' ?>';//imagen contraido

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

<form name='form1' action='detalle_gestion.php' method='POST'>
<input type="hidden" value="<?php echo $id_efe_conv?>" name="id_efe_conv">
<input type="hidden" value="<?php echo $cuie?>" name="cuie">

<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<b>	
		<?if ($fecha_desde=='') $fecha_desde=DATE ('d/m/Y');
		if ($fecha_hasta=='') $fecha_hasta=DATE ('d/m/Y');?>		
		Desde: <input type=text id=fecha_desde name=fecha_desde value='<?php echo $fecha_desde?>' size=15 readonly>
		<?php echo link_calendario("fecha_desde");?>
		
		Hasta: <input type=text id=fecha_hasta name=fecha_hasta value='<?php echo $fecha_hasta?>' size=15 readonly>
		<?php echo link_calendario("fecha_hasta");?> 
		
		   
	    
	    &nbsp;&nbsp;&nbsp;
	    <input type="submit" class="btn btn-success" name="muestra" value='Muestra' onclick="return control_muestra()" >
	    </b>
	    
	    &nbsp;&nbsp;&nbsp;	    
        <?if ($_POST['muestra']){
         	
        $link=encode_link("efec_cumplimiento_pdf.php",array("id_efe_conv"=>$id_efe_conv,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));?>
        <!--<img src="../../imagenes/pdf_logo.gif" style='cursor:hand;'  onclick="window.open('<?php echo $link?>')">-->
        <?}?>
	  </td>
       
     </tr>
     
    
     
</table>
<table width="90%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
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
              <input type="text" size="40" value="<?php echo $nombre?>" name="nombre" readonly>
            </td>
         </tr>
         
         <tr>	           
           
         <tr>
         <td align="right">
				<b>Domicilio:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?php echo $domicilio?>" name="domicilio" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Departamento:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?php echo $departamento?>" name="departamento" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Localidad:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?php echo $localidad?>" name="localidad" readonly>
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
              <input type="text" size="40" value="<?php echo $cod_pos?>" name="cod_pos" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Cuidad:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?php echo $cuidad?>" name="cuidad" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Referente:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?php echo $referente?>" name="referente" readonly>
            </td>
         </tr>
         
         <tr>
         <td align="right">
				<b>Telefono:</b>
			</td>
			<td align="left">		 
              <input type="text" size="40" value="<?php echo $tel?>" name="tel" readonly>
            </td>
         </tr>          
        </table>
      </td>  
       
     </tr> 
           
 </table>           

<?if ($_POST['muestra']){?>
<table width="100%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
		<tr align="center" id="sub_tabla">
		 	
</table>
<table>
	<table width=100% align="center" class="bordes">
  <tr><td id="mo" colspan="5">
  <b><font size=2>Evaluacion de Gestion</font></b>
  </td></tr>
	
  <tr><td><table width=95% align="center" class="bordes">	    
      <tr>
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=3 color= red><b>Uso de Fondos</b> </font>
			</td>   
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2>Ingreso en Pesos: </br><b><?php echo ($ingreso)?$ingreso:0?> </b></font>
			</td>
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2>Egreso en Pesos: </br><b><?php echo ($egreso)?$egreso:0?></b></font>
			</td>
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2>Uso de Fondos (%): </br><b><?php echo ($uso_de_fondos)?$uso_de_fondos:0?></b></font>
			</td>
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2>Saldo Inmovilizado (%): </br><b><?php echo ($saldo_inmovilizado)?$saldo_inmovilizado:0?></b></font>
			</td>
			</tr>
      </table></td></tr>
   
	
  <tr><td><table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
			
			<tr>
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=3 color= red> <b>Dinero Disponible  </b></font>
			</td>   
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2>Saldo en Pesos (Ingreso - Egreso): <br/><b><?php echo ($total)?$total:0?> </b> </font>
			</td>
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2>Total Expedientes Comprometidos (No Pagos): <br/><b><?php echo ($egreso_compr_no_pago)?$egreso_compr_no_pago:0?> </b> </font>
			</td>
				  
			<?if ($saldo_real<0) $atrib=atrib_tr5();
			else $atrib=atrib_tr8();?>
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo $atrib?>>
			<font size=2>Saldo Real: <br/><b><?php echo ($saldo_real)?$saldo_real:0?> </b> </font>
			</td>
			</tr>
	</table></td></tr>
			
			<br/>
			
  <tr><td><table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
			<tr>	  
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
				<font size=3 color= red><b>Pago de Incentivos</b> </font>
			     </td>   
				  
				 <td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
					<font size=2>Ultimo Pago: </br><b><?php echo ($sql_res_inc->fields['monto_egreso'])?number_format($sql_res_inc->fields['monto_egreso'],2,',','.'):0?> </b></font>
			      </td>
				  
				  <td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
					<font size=2>Acumulado 1° Semeste (Sujeto a cumpl. de Metas): </br><b><?php echo ($sql_res_acum_1->fields['total'])?number_format($sql_res_acum_1->fields['total'],2,',','.'):0?> </b></font>
			      </td>
				  
				  <td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
					<font size=2>Acumulado 2° Semeste (Sujeto a cumpl. de Metas): </br><b><?php echo ($sql_res_acum_2->fields['total'])?number_format($sql_res_acum_2->fields['total'],2,',','.'):0?></b></font>
			      </td>
			</tr>
		</table></td></tr>
			
			<br/>
			<tr>
		<table width=100% align="center" class="bordes">
			<br>
			<tr>
			<td id="mo" colspan="5">
			<b> <font size=2 >Evaluacion de Inscripcion</font></b>
			</td>
			</tr>
		</table>
		</tr>
	<tr><td><table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
		
		<tr>
		<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=3 color= red><b>Poblacion Inscripta al Programa </b> </font>
			</td>   
				 	  
			<!--<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2>Poblacio Objetivo x RRHH</br></font>
			</td>-->

			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2>Poblacio Inscripta Acumulada</br></font>
			</td>
			
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2>Poblacio Inscripta en el Periodo</br></font>
			</td>
				  
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2>CEB</br></font>
			</td>
			 
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2>Porcentaje</br></font>
			</td>
		</tr>
			
		<tr>
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2 color= red><b>Ni&ntilde;os de 0 a 5 A&ntilde;o</b> </font>
			</td>
				   
			<!--<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo ($sql_res_metasrrhh->fields['ceb_ceroacinco'])?$sql_res_metasrrhh->fields['ceb_ceroacinco']:0?> </b></font>
			</td>-->
				
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ins_tot_a?> </b></font>
			</td>
			
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ins_a?> </b></font>
			</td>			
			
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ceb_a?> </b></font>
			</td>
				  
			<!--<td align="center"  border=1 <?if ($sql_res_metasrrhh->fields['ceb_ceroacinco']) {
			$met1=($ceb_a*100)/$sql_res_metasrrhh->fields['ceb_ceroacinco'];}
			else $met1=0?>bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo number_format($met1,2,',','.');?>% </b></font>
			</td>-->
			
			<td align="center"  border=1 <?php $met1=($ins_tot_a<>0)?($ceb_a*100)/$ins_tot_a:0;?>
			bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo number_format($met1,2,',','.');?>% </b></font>
			</td>
		</tr>
			
      <tr>
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2 color= red><b>Ni&ntilde;os de 6 a 9 A&ntilde;o</b> </font>
			</td>
			
			<!--<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo ($sql_res_metasrrhh->fields['ceb_seisanueve'])?$sql_res_metasrrhh->fields['ceb_seisanueve']:0?> </b></font>
			</td>-->
				 
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ins_tot_b?> </b></font>
			</td>
			
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ins_b?> </b></font>
			</td>
			
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ceb_b?> </b></font>
			</td>			 
				 
			<!--<?if ($sql_res_metasrrhh->fields['ceb_seisanueve']) {$met1=($ceb_b*100)/$sql_res_metasrrhh->fields['ceb_seisanueve'];}
			else $met1=0?>
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo number_format($met1,2,',','.')?>% </b></font>
			</td>-->
			<td align="center"  border=1 <?php $met1=($ins_tot_b<>0)?($ceb_b*100)/$ins_tot_b:0;?>
			bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo number_format($met1,2,',','.');?>% </b></font>
			</td>
		</tr>
			
      <tr>
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2 color= red><b>Adolescentes (10 a 19 a&ntilde;os)</b> </font>
			</td>
				  
			<!--<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo ($sql_res_metasrrhh->fields['ceb_diezadiecinueve'])?$sql_res_metasrrhh->fields['ceb_diezadiecinueve']:0?> </b></font>
			</td>-->					
			
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ins_tot_c?> </b></font>
			</td>

			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ins_c?> </b></font>
			</td>

			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ceb_c?> </b></font>
			</td>
					
			<!--<?if ($sql_res_metasrrhh->fields['ceb_diezadiecinueve']) {$met1=($ceb_c*100)/$sql_res_metasrrhh->fields['ceb_diezadiecinueve'];}
			else $met1=0?>
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo number_format($met1,2,',','.')?>% </b></font>
			</td>-->
			<td align="center"  border=1 <?php $met1=($ins_tot_c<>0)?($ceb_c*100)/$ins_tot_c:0;?>
			bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo number_format($met1,2,',','.');?>% </b></font>
			</td>
	 </tr>
			
      <tr>
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2 color= red><b>Mujeres (20 a 64 a&ntilde;os)</b> </font>
			</td>
								
			<!--<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo ($sql_res_metasrrhh->fields['ceb_veinteasesentaycuatro'])?$sql_res_metasrrhh->fields['ceb_veinteasesentaycuatro']:0?> </b></font>
			</td>-->
			
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ins_tot_d?> </b></font>
			</td>
			
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ins_d?> </b></font>
			</td>

			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ceb_d?> </b></font>
			</td>
					
			<?if ($sql_res_metasrrhh->fields['ceb_veinteasesentaycuatro']) {
			$met1=($ceb_d*100)/$sql_res_metasrrhh->fields['ceb_veinteasesentaycuatro'];}
			else $met1=0?>
			<!--<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo number_format($met1,2,',','.')?>% </b></font>
			</td>-->
			<td align="center"  border=1 <?php $met1=($ins_tot_d<>0)?($ceb_d*100)/$ins_tot_d:0;?>
			bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo number_format($met1,2,',','.');?>% </b></font>
			</td>
	  </tr>

	  <tr>
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2 color= red><b>Hombres (20 a 64 a&ntilde;os)</b> </font>
			</td>
								
			<!--<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo ($sql_res_metasrrhh->fields['ceb_veinteasesentaycuatro'])?$sql_res_metasrrhh->fields['ceb_veinteasesentaycuatro']:0?> </b></font>
			</td>-->
			
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ins_tot_e?> </b></font>
			</td>
			
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ins_e?> </b></font>
			</td>

			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ceb_e?> </b></font>
			</td>
					
			<?if ($sql_res_metasrrhh->fields['ceb_veinteasesentaycuatro']) {
			$met1=($ceb_d*100)/$sql_res_metasrrhh->fields['ceb_veinteasesentaycuatro'];}
			else $met1=0?>
			<!--<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo number_format($met1,2,',','.')?>% </b></font>
			</td>-->
			<td align="center"  border=1 <?php $met1=($ins_tot_e<>0)?($ceb_e*100)/$ins_tot_e:0;?>
			bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo number_format($met1,2,',','.');?>% </b></font>
			</td>
	  </tr>

	  <tr>
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2 color= red><b>Adultos Mayores (64 a&ntilde;os)</b> </font>
			</td>
								
			<!--<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo ($sql_res_metasrrhh->fields['ceb_veinteasesentaycuatro'])?$sql_res_metasrrhh->fields['ceb_veinteasesentaycuatro']:0?> </b></font>
			</td>-->
			
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ins_tot_f?> </b></font>
			</td>
			
			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ins_f?> </b></font>
			</td>

			<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo $ceb_f?> </b></font>
			</td>
					
			<?if ($sql_res_metasrrhh->fields['ceb_veinteasesentaycuatro']) {
			$met1=($ceb_d*100)/$sql_res_metasrrhh->fields['ceb_veinteasesentaycuatro'];}
			else $met1=0?>
			<!--<td align="center"  border=1 bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo number_format($met1,2,',','.')?>% </b></font>
			</td>-->
			<td align="center"  border=1 <?php $met1=($ins_tot_f<>0)?($ceb_f*100)/$ins_tot_f:0;?>
			bordercolor=#2C1701 <?php echo atrib_tr7()?>>
			<font size=2><b><?php echo number_format($met1,2,',','.');?>% </b></font>
			</td>
	  </tr>
	</table></td></tr>
	
	<br/>

<tr><td><table width=90% align="center" class="bordes">
<tr align="center">

    <table width=100% align="center" class="bordes">
    <br/> 
	<tr>
      <td id="mo" colspan="5">
       <b> <font size=2 >Evaluacion de Facturacion</font></b>
      </td>
     </tr>
    </table>

    <tr align="center">
    <table width=100% border=1 align="center" class="bordes">
    
   <?php $ref_det_1=encode_link("practicas_no_facturadas.php",array("cuie"=>$cuie,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
    $ref_det_2=encode_link("practicas_facturadas.php",array("cuie"=>$cuie,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
    $ref_det_3=encode_link("facturas_pagas.php",array("cuie"=>$cuie,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
    $ref_det_4=encode_link("expedientes_comprometidos.php",array("cuie"=>$cuie,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
    $ref_det_5=encode_link("informacion_priorizada.php",array("cuie"=>$cuie,"fecha_desde"=>$fecha_desde,"fecha_hasta"=>$fecha_hasta));
   ?>
  
    <tr>
	<td><button align="center" type="button" id="boton_1" class="btn btn-primary"
	onclick="window.open('<?php echo $ref_det_3?>','f','dependent:yes,width=800,height=700,top=2,left=60,scrollbars=yes');">
	Facturas pagas en el Periodo Fijado</button></td>
   
   <td><button align="center" type="button" id="boton_2" class="btn btn-info"
   onclick="window.open('<?php echo $ref_det_2?>','f','dependent:yes,width=1000,height=700,top=2,left=60,scrollbars=yes');">
   Codigos Facturados por Periodo Fijado</button></td>
    
   <td><button align="center" type="button" id="boton_3" class="btn btn-warning"
   onclick="window.open('<?php echo $ref_det_1?>','f','dependent:yes,width=1000,height=700,top=2,left=60,scrollbars=yes');">
   Codigos NO Facturados en el Periodo Fijado</button></td>
   
   <td><button align="center" type="button" id="boton_4" class="btn btn-success"
   onclick="window.open('<?php echo $ref_det_4?>','f','dependent:yes,width=1000,height=700,top=2,left=60,scrollbars=yes');">
   Expedientes Comprometidos</button></td>
   
   <td><button align="center" type="button" id="boton_5" class="btn btn-info"
   onclick="window.open('<?php echo $ref_det_5?>','f','dependent:yes,width=1000,height=700,top=2,left=60,scrollbars=yes');">
   Informacion Priorizada</button></td>
   	</tr>
   	
	</table>
	</tr>
</tr>
</table></td></tr>
<?}?>
	
<BR>
 <tr><td><table width=90% align="center" class="bordes">
  <tr align="center">
  <?
   if (!es_cuie($user)){ ?>
		<td>
     	<input type="button" class="btn btn-primary" name="volver" value="Volver" onclick="document.location='seguimiento.php'"title="Volver al Listado" style="width=150px">     
   		</td>
   <?} 
   else {?>

   		<td>
     	<input type="button" class="btn btn-primary" name="volver" value="Volver" onclick="document.location='efectores_detalle.php'"title="Volver al Listado" style="width=150px">     
   		</td>
  <?}?>

  </tr>
 </table></td></tr>
 
  </table>
 </table>
 </form>
 
 <?php echo fin_pagina();// aca termino ?>
