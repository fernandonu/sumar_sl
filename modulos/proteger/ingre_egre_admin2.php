<?
require_once ("../../config.php");


extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

if(($_POST['notificar']=="Notificar Via Mail") or ($notificar_mail=="True")){
  
  $color1="#5090C0";
  $color2="#D5D5D5";
  $ret = "";
  $fecha_hoy=date("Y-m-d");

  if ($id_ingreso!='') 
    { $sql_update="UPDATE proteger.ingreso set fecha_notificacion='$fecha_hoy'
                where id_ingreso=$id_ingreso";
      sql($sql_update,"No se pudo modificar la fecha de notificacion del regsitro") or fin_pagina();
    }

 $sql= "SELECT * 
        FROM
          proteger.ingreso  
        inner join nacer.efe_conv using (cuie)
        inner join proteger.egreso using (cuie)
        inner join proteger.inciso using (id_inciso)

        WHERE  cuie = '$cuie'";


 $sql_ingreso= "SELECT * 
                FROM proteger.ingreso  
                inner join nacer.efe_conv using (cuie)
                WHERE  cuie = '$cuie'
                AND id_ingreso='$id_ingreso'";

$sql_egreso= "SELECT * 
FROM
   proteger.egreso
inner join nacer.efe_conv using (cuie)
inner join proteger.inciso using (id_inciso)
WHERE  cuie = '$cuie'";

  
$res_ingreso=sql($sql_ingreso,"no se puede ejecutar la consulta de Ingreso");
$res_egreso=sql($sql_egreso,"no se puede ejecutar la consulta de Egreso");
$nombre_efector=$res_ingreso->fields['nombre'];


date_default_timezone_set('Europe/Madrid');
setlocale(LC_TIME, 'spanish');
$dia_hoy=strftime("%A %d de %B de %Y");
  
$ret .= "<table width='65%'  bgcolor='$color1' align='center' style='border: 2px solid #000000; font-size=14px;'>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='center'>\n";
$ret .= "<b>FORMULARIO I</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1' align='right'>\n";
$ret .= "<td align='rigth'>\n";
$ret .= "<b>Proteger, $dia_hoy</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1' align='left'>\n";
$ret .= "<td align='left'>\n";
$ret .= "<b>Efector: ".$res_ingreso->fields['nombre']." CUIE: $cuie. </b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1' align='left'>\n";
$ret .= "<td align='left'>\n";
$ret .= "<b>Referente: ".$res_ingreso->fields['referente']."</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "<b>Por medio de la presente le notifico que se encuentra a disposición del prestador que usted representa
la suma de $ ".number_format($res_ingreso->fields['monto_recurso'],2,',','.')." transferida por el Programa PROTEGER correspondiente al periodo ".$res_ingreso->fields['periodo'].".</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "<b>Asimismo informo a Usted que dicha transferencia se realizo a través del Expediente N°: ".$res_ingreso->fields['numero_expediente'].".</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "</table><br>\n";

$sql="SELECT ingre,
      case when (egre is NULL) then 0
      else egre end as egre ,
      case when (monto_preventivo is NULL) then 0
      else monto_preventivo end as monto_preventivo,
      case when (egre_comp is NULL) then 0
      else egre_comp end as egre_comp
      from
        (select sum (monto_recurso)as ingre from proteger.ingreso
        where cuie='$cuie') as ingreso,
        (select sum (monto_egreso)as egre from proteger.egreso
        where cuie='$cuie' and pagado='si') as egreso,
        (select sum (monto_preventivo) as monto_preventivo from proteger.egreso
        where cuie='$cuie' and pagado='no') as preventivo,
        (select sum (monto_comprometido) as egre_comp from proteger.egreso
        where cuie='$cuie' and pagado='no') as egre_comp";

$res_saldo=sql($sql,"no puede calcular el saldo");
$saldo_real=$res_saldo->fields['ingre']-$res_saldo->fields['egre']-$res_saldo->fields['egre_comp'];

$saldo_real=number_format($saldo_real,2,',','.');

$ret .= "<table width=95% align=center style='font-size=10px'>\n";
$ret .= "<tr>\n";
$ret .= "<td align=center>\n";
$ret .= "<b>INFORMACION ANEXA\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "</table>\n";
$ret .= "<table width='65%'  bgcolor='$color1' align='center' style='border: 2px solid #000000; font-size=14px;'>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "<b>Por medio de la presente le informo que: </b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "<b>Su Saldo Acumulado es de: $ ".number_format($res_saldo->fields['ingre'],2,',','.').".</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "<b>Su Saldo Comprometido es de: $ ".number_format($res_saldo->fields['egre_comp'],2,',','.').".</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "<b>Su Saldo Real es de: $ $saldo_real. </b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "<b>Para consultar sobre los saldos, dirigirse a Calidad->Reporte de Cumplimiento y Gestion y luego clikear en Detalle de Gestion</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "<font color=white><b>Se ruega contestar este mail al mail Oficial del Programa PROTEGER : protegersanluis@sanluis.gov.ar , para ser tenido en cuenta como acuse de recibo.</b></font>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "</table><br>\n";


$ret .= "<table width=95% align=center style='font-size=10px'>\n";
$ret .= "<tr>\n";
$ret .= "<td align=center>\n";
$ret .= "<b> NOTIFICACIONES\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "</table>\n"; 
$ret .= "<table width='65%'  bgcolor='$color1' align='center' style='border: 2px solid #000000; font-size=14px;'>\n";
$ret .= "<tr bgcolor='$color1' align='left'>\n";
$ret .= "<td align='rigth'>\n";
$ret .= "<b>Queda Notificado Equipo Programa PROTEGER a través del mail oficial.</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1' align='left'>\n";
$ret .= "<td align='left'>\n";
$ret .= "<b>Queda Notificado el Efector: $nombre_efector. CUIE: $cuie. A través de los mail declarados.</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$sql= "select * from nacer.mail_efe_conv where cuie='$cuie'";
$res_mail=sql($sql,"no se puede ejecutar");
$res_mail->movefirst();
while (!$res_mail->EOF) { 
  $para=$res_mail->fields['mail'];
  $ret .= "<tr bgcolor='$color1' align='left'>\n";
  $ret .= "<td align='left'>\n";
  $ret .= "<b>Mail: $para.</b>\n";
  $ret .= "</td>\n";
  $ret .= "</tr>\n";
  $res_mail->movenext();
}
$ret .= "</table>\n";

echo $ret;
  
  $res_mail->movefirst();
  while (!$res_mail->EOF) { 
  $para=$res_mail->fields['mail'];
  enviar_mail_html_proteger($para,'Notificacion de Fondos',$ret,0,0,0);
  $res_mail->movenext();
  }
  
  enviar_mail_html_proteger('seba1202@gmail.com','Notificacion de Fondos',$ret,0,0,0);
  /*enviar_mail_html_proteger('seba_cyb1202@hotmail.com','Notificacion de Fondos',$ret,0,0,0);
  enviar_mail_html_proteger('norcortes@hotmail.com','Notificacion de Fondos',$ret,0,0,0);
  enviar_mail_html_proteger('ericanataliarosales@gmail.com','Notificacion de Fondos',$ret,0,0,0);
  enviar_mail_html_proteger('camilaorquieda@hotmail.com','Notificacion de Fondos',$ret,0,0,0);
  enviar_mail_html_proteger('arceguido@gmail.com','Notificacion de Fondos',$ret,0,0,0);*/

  $ref = encode_link("notificacion_excel2.php",array("cuie"=>$cuie,"id_factura"=>$nro_factura,"saldo_real"=>$saldo_real));?>
  <script>
  window.open('<?=$ref?>')
  </script>
<?}


if($marcar1=="True"){
   $db->StartTrans();
  $query="DELETE from proteger.ingreso
             where id_ingreso=$id_ingreso";
    sql($query, "Error al eliminar 1") or fin_pagina();
  
   if ($id_egre_temp !=''){
    $query="DELETE from proteger.egreso
               where id_egreso=$id_egre_temp";
      sql($query, "Error al eliminar 3") or fin_pagina();
  }
    
    if ($id_egre_temp !='') $accion="Se elimino el Ingreso Numero: $id_ingreso. Se Elimino el Incentivo Vinculado en la Tabla Egreso y la Tabla Incentivo. NO SE ELIMINO LOS REGISTROS EN EL SISTEMA DE EXPEDIENTE"; 
  else $accion="Se elimino el Ingreso Numero: $id_ingreso. NO SE ELIMINO LOS REGISTROS EN EL SISTEMA DE EXPEDIENTE"; 
    $db->CompleteTrans();   
}

if($marcar2=="True"){
   $db->StartTrans();
  $query="DELETE from proteger.egreso
             where id_egreso=$id_egreso";
    sql($query, "Error al eliminar") or fin_pagina();
    
    $accion="Se elimino el Egreso Numero: $id_egreso."; 
    $db->CompleteTrans();   
}

if ($_POST['guardar']=="Guardar Ingreso"){
  
  $cuie=$_POST['cuie'];
  $numero_expediente=$_POST['numero_expediente_ingreso'];	
  $monto_recurso=$_POST['monto_recurso'];
  $fecha_deposito=Fecha_db($_POST['fecha_deposito']);
  $periodo=$_POST['periodo'];
  $fecha_expediente=($_POST['fecha_expediente'])?Fecha_db($_POST['fecha_expediente']):'1800-01-01';
  $comentario=$_POST['comentario'];
  $usuario=$_ses_user['name'];  
  $fecha=date("Y-m-d"); 
  
   
  $db->StartTrans();
  $q="SELECT nextval('proteger.ingreso_id_ingreso_seq') as id_ingreso";
  $res_q=sql($q) or fin_pagina();
  $id_ingreso=$res_q->fields['id_ingreso'];  
  $query="INSERT into proteger.ingreso
           (id_ingreso,cuie,monto_recurso,fecha_deposito,comentario,usuario,fecha_mod,numero_expediente,fecha_expediente,periodo)
           values
           ($id_ingreso,'$cuie',$monto_recurso,'$fecha_deposito','$comentario','$usuario','$fecha',$numero_expediente,'$fecha_expediente','$periodo')"; 
  
  sql($query, "Error al insertar los detalles de Ingresos ") or fin_pagina();
  $accion="Se guardo el Ingreso Numero: $id_ingreso";

  $db->CompleteTrans();
           
}//de if ($_POST['guardar']=="Guardar Ingreso")

if ($_POST['guardar']=="Guardar Egreso"){
  
  $cuie=$_POST['cuie'];
  $monto_preventivo=$_POST['monto_preventivo'];
  $fecha_preventivo=Fecha_db($_POST['fecha_preventivo']);
  $monto_comprometido=($_POST['monto_comprometido'])?$_POST['monto_comprometido']:0;
  $fecha_comprometido=($_POST['fecha_comprometido'])?Fecha_db(($_POST['fecha_comprometido'])):'1800-01-01';
  $monto_egreso=($_POST['monto_egreso'])?$_POST['monto_egreso']:0;
  $fecha_egreso=($_POST['fecha_egreso'])?Fecha_db($_POST['fecha_egreso']):'1800-01-01';
  $comentario=$_POST['comentario1'];
  $usuario=$_ses_user['name'];  
  $numero_factura=($_POST['numero_factura'])?$_POST['numero_factura']:0;
  $numero_expediente=$_POST['numero_expediente_egreso'];
  $pagado=($_POST['pagado']=='on')?'si':'no';
  $fecha_mod=date("Y-m-d"); 
  $id_inciso=$_POST['ins_nombre'];
    
   $db->StartTrans();
   $q="select nextval('proteger.egreso_id_egreso_seq') as id_egreso";
   $sql_query=sql($q) or fin_pagina();
   $id_egreso=$sql_query->fields['id_egreso'];  
   
   $query="INSERT into proteger.egreso
               (id_egreso,cuie,monto_preventivo,fecha_preventivo,comentario,usuario,fecha_comprometido,monto_egreso,fecha_egreso,fecha_mod,monto_comprometido,numero_factura,numero_expediente,pagado,id_inciso)
               values
               ($id_egreso,'$cuie','$monto_preventivo','$fecha_preventivo','$comentario','$usuario','$fecha_comprometido',$monto_egreso,'$fecha_egreso','$fecha_mod',$monto_comprometido,$numero_factura,'$numero_expediente','$pagado',$id_inciso)"; 
    sql($query, "Error al insertar el comprobante") or fin_pagina();      
      
   
      
      
      
    $accion="Se guardo el Ingreso Numero: $id_egreso";
    $db->CompleteTrans();   
          
}//de if ($_POST['guardar']=="Guardar nuevo Muleto")

$sql="SELECT * from nacer.efe_conv
   where cuie='$cuie'";
$res_comprobante=sql($sql, "Error al traer los Comprobantes") or fin_pagina();

$nombre=$res_comprobante->fields['nombre'];
$domicilio=$res_comprobante->fields['domicilio'];
$ciudad=$res_comprobante->fields['cuidad'];

echo $html_header;
?>
<script>
//controlan que ingresen todos los datos necesarios par el muleto
function control_nuevos_ingresos()
{
  if(document.all.servicio.value=="-1"){
  alert('Debe Seleccionar un Servicio');
  return false;
  }
 if(document.all.numero_factura.value=="-1"){
  alert('Debe Vincular una Factura')
  return false;
 }
  if(document.all.expediente_externo.value==""){
  alert('Debe Ingresar un Expediente Externo');
  return false;
 }
 if(document.all.fecha_exp_ext.value==""){
  alert('Debe Ingresar una Fecha de Expediente Externo');
  return false;
 }
 if (confirm('Esta Seguro que Desea Agregar Ingreso?'))return true;
 else return false; 
}


 function control_nuevos_egresos()
{
 if(document.all.servicio1.value=="-1"){
  alert('Debe Seleccionar un Servicio');
  return false;
  }
  
  if(document.all.ins_nombre.value=="-1"){
  alert('Debe Seleccionar un Inciso');
  return false;
  }
  
 if(document.all.monto_egre_comp.value==""){
  alert('Debe Ingresar un monto egreso COMPROMETIDO');
  return false;
 }
  if(document.all.monto_egreso.value==""){
  alert('Debe Ingresar un monto egreso (0 si no hay monto)');
  return false;
 } 
 if (confirm('Esta Seguro que Desea Agregar Egreso?'))return true;
 else return false; 
}//de function control_nuevos()

var img_ext='<?=$img_ext='../../imagenes/rigth2.gif' ?>';//imagen extendido
var img_cont='<?=$img_cont='../../imagenes/down2.gif' ?>';//imagen contraido
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
function destrabar_ingreso(){
  document.all.monto_egre_comp.readOnly=false;
  document.all.monto_egre_comp.focus();
}
</script>

<form name='form1' action='ingre_egre_admin2.php' method='POST' enctype='multipart/form-data'>

<?echo "<center><b><font size='+2' color='red'>$accion</font></b></center>";?>
<input type="hidden" name="cuie" value="<?=$cuie?>">
<?
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
$sql="SELECT ingre,
      case when (egre is NULL) then 0
      else egre end as egre ,
      case when (egre_comp is null) then 0
             else egre_comp end, 
      case when (monto_preventivo is NULL) then 0
      else monto_preventivo end as monto_preventivo,
      case when (egre_comp is NULL) then 0
      else egre_comp end as egre_comp
      from
        (select sum (monto_recurso)as ingre from proteger.ingreso
        where cuie='$cuie') as ingreso,
        (select sum (monto_egreso)as egre from proteger.egreso
        where cuie='$cuie' and pagado='si') as egreso,
        (select sum (monto_preventivo) as monto_preventivo from proteger.egreso
        where cuie='$cuie' and pagado='no') as preventivo,
        (select sum (monto_comprometido) as egre_comp from proteger.egreso
        where cuie='$cuie' and pagado='no') as egre_comp";
}
$res_saldo=sql($sql,"no puede calcular el saldo")

?>
<table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
     <font size=+1><b>Ingreso / Egreso</b></font>    
    </td>
 </tr>
 <tr><td>
  <table width=70% align="center" class="bordes">
     <tr>
      <td id=mo colspan="2">
       <b> Descripción del Efector</b>
      </td>
     </tr>
     <tr>
       <td>
        <table>
         <tr>            
           <td align="center" colspan="2">
            <b> CUIE: <font size="+1" color="Red"><?=$cuie?></font> </b>
           </td>
         </tr>
         <tr>
          <td align="right">
            <b>Nombre:
          </td>           
            <td align='left'>
              <input type='text' name='nombre' value='<?=$nombre;?>' size=60 align='right' readonly></b>
            </td>
         </tr>
         <tr>
            <td align="right">
            <b> Domicilio:
          </td>   
           <td  >
             <input type='text' name='domicilio' value='<?=$domicilio;?>' size=60 align='right' readonly></b>
           </td>
          </tr>
          <tr>
           <td align="right">
            <b> Ciudad:
          </td> 
           <td >
             <input type='text' name='ciudad' value='<?=$ciudad;?>' size=60 align='right' readonly></b>
           </td>
          </tr>
          <tr>
      <td align="right"><b>Saldo:</b></td>
      <td align="left">                   
        <b><font size="+1" color="Blue"><?=number_format($res_saldo->fields['total'],2,',','.')?></font></b>
      </td>
      </tr>  
          <tr>             
           <td align="center" colspan="2">
             <b><font size="2" color="Red">Nota: Los valores numericos se ingresan SIN separadores de miles, y con "." como separador DECIMAL</font> </b>
           </td>
         </tr> 
         <tr>            
           <td align="center" colspan="2">
            <?$ref = encode_link("detalle_servicio.php",array("cuie"=>$cuie, "nombre"=>$nombre));
          $onclick_elegir="window.open('$ref')";?>
             <input type="submit" name="notificar" value="Notificar Via Mail" onclick="return confirm('Se Notificara por Mail Movimiento Bancarios del CAPS. ¿Esta Seguro?');" style="width=250px">
           </td>
         </tr>          
        </table>
      </td>      
     </tr>
   </table>     
   <table class="bordes" align="center" width="70%">
     <tr align="center" id="sub_tabla">
      <td colspan="2">  
        Nuevo Ingreso
      </td>
     </tr>
     <tr><td class="bordes"><table>
       <tr>
         <td>

         	<tr>
              <td align="right">
                <b>Numero Expediente:</b>
              </td>
               <td align="left">                    
              <input type="numeric" name="numero_expediente_ingreso" Style="width=550px">                  
              </td>
              
           </tr>

           <tr>
              <td align="right">
                <b>Monto Recurso:</b>
              </td>
               <td align="left">                    
              <input type="numeric" name="monto_recurso" Style="width=550px">                  
              </td>
              
           </tr>
           <tr>
            <td align="right">
                <b>Fecha de Deposito:</b>
              </td>
              <td align="left">
              <input type=text id=fecha_deposito name=fecha_deposito value='<?//=$fecha_deposito;?>' size=18 readonly>
                 <?=link_calendario("fecha_deposito");?>                 
              </td>       
           </tr>
          
           
           
           <tr>
              <td align="right">
                 <b>Periodo:</b>
              </td>
                         
              <td align="left">
              <input type="text" name="periodo" value='<?//=$periodo;?>' size=18 align="right">
              </td>
           </tr>

      	<tr>
            <td align="right"><b>Fecha de Expediente:</b></td>
            <td align="left"> 
            <input type=text id=fecha_expediente name=fecha_expediente value='<?=fecha($fecha_expediente);?>' size=18 readonly>
              <?=link_calendario("fecha_expediente");?>  
            </td>       
        </tr>
           
	        <tr>
	        <td align="right">
	        <b>Detalle de Expediente:</b>
	        </td>           
	        <td align='left'>
	        <textarea cols='90' rows='5' name='comentario' ></textarea>
	        </td>
        	</tr>                          
        
        </td>
       </tr>
     </table></td></tr>  
     <tr>
        <td align="center" colspan="2" class="bordes">          
          <input type="submit" name="guardar" value="Guardar Ingreso" title="Guardar Ingreso" Style="width=300px" onclick="return control_nuevos_ingresos()">
        </td>
     </tr> 
   </table> 
 </td></tr>
 
<?//tabla de comprobantes
$query="SELECT * FROM proteger.ingreso where ingreso.cuie='$cuie' order by id_ingreso DESC";
$res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();
?>
<tr><td><table width="100%" class="bordes" align="center">
  <tr align="center" id="mo">
    <td align="center" width="3%">
     <img id="imagen_2" src="<?=$img_ext?>" border=0 title="Mostrar Ingresos" align="left" style="cursor:hand;" onclick="muestra_tabla(document.all.prueba_vida,2);" >
    </td>
    <td align="center">
     <h4><b>Ingresos</b>&nbsp; (Total Depositado: <?=number_format($res_saldo->fields['ingre'],2,',','.')?>)
              <?$total_depositado=$res_saldo->fields['ingre'] //lo uso en ecuacion mas adelante?>
    </h4></td>
  </tr>
</table></td></tr>
<tr><td><table id="prueba_vida" border="1" width="100%" style="display:none;border:thin groove">
  <?if ($res_comprobante->RecordCount()==0){?>
   <tr>
    <td align="center">
     <font size="3" color="Red"><b>No existen Ingresos para este Efector</b></font>
    </td>
   </tr>
   <?}
   else{    
    ?>
    <tr id="sub_tabla">         
      <td width="5%">ID</td>
      <td width="10%">Numero Expediente</td>
      <td width="10%">Monto Recurso</td>
      <td width="10%">Fecha Exped.</td>
      <td width="10%">Periodo</td>
      <td width="10%">Fecha Deposito</td>
      <td width="10%">Fecha Notificacion</td>
      <td width="10%">Usuario</td>
      <td width="10%">Fecha Mod.</td>
      <td width="10%">Det.Exped.</td>
      <td width="10%">Editar Reg.</td>
      <td width="10%">Notificar</td>
     </tr>
    <?
    $res_comprobante->movefirst();
    while (!$res_comprobante->EOF) {
                
             $id_ingreso=$res_comprobante->fields['id_ingreso'];
             if (permisos_check('inicio','contabilidad_exepciones')){ 
               $ref1 = encode_link("ingre_egre_admin2.php",array("id_ingreso"=>$res_comprobante->fields['id_ingreso'],"marcar1"=>"True","cuie"=>$cuie));              
               
               $onclick_eliminar="if (confirm('Esta Seguro que Desea Eliminar Ingreso $id_ingreso ?')) location.href='$ref1'
                          else return false;  ";
      }
             else $onclick_eliminar="alert ('Debe Tener Permisos Especiales para poder Eliminar.')";
       
       $saldo_real=$total_depositado-$res_saldo->fields['egre']-($res_saldo->fields['egre_comp']-$res_saldo->fields['egre']);
       
       $ref_notificar = encode_link("ingre_egre_admin2.php",array("notificar_mail"=>"True","cuie"=>$cuie,"id_ingreso"=>$id_ingreso));
       
       $ref_editar = encode_link("detalle_ingreso_admin2.php",array("id_ingreso"=>$res_comprobante->fields['id_ingreso'], "cuie"=>$res_comprobante->fields['cuie']));
       
       $id_factura_notificar=$res_comprobante->fields['numero_factura'];
       
       $onclick_notificar="if (confirm('Esta Seguro que Desea Notificar el Registro Nº  $id_ingreso?')) location.href='$ref_notificar'
                          else return false;  ";

       $onclick_editar="if (confirm('Esta Seguro que Desea Editar los datos del Registro Nº  $id_ingreso?')) location.href='$ref_editar'
                          else return false;  ";
      ?>
      <tr <?=atrib_tr()?>>        
        <td onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['id_ingreso']?></td>
        <td onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['numero_expediente']?></td>
		<td onclick="<?=$onclick_elegir?>"><?=number_format($res_comprobante->fields['monto_recurso'],2,',','.')?></td>
        <td onclick="<?=$onclick_elegir?>"><?=($res_comprobante->fields['fecha_expediente']=='1800-01-01')?'':fecha($res_comprobante->fields['fecha_expediente'])?></td>
        <td onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['periodo']?></td>
        <td onclick="<?=$onclick_elegir?>"><?=($res_comprobante->fields['fecha_deposito']='1800-01-01')?'':fecha($res_comprobante->fields['fecha_deposito'])?></td>
        <td onclick="<?=$onclick_elegir?>"><?=fecha($res_comprobante->fields['fecha_notificacion'])?></td>
        <td onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['usuario']?></td>        
        <td onclick="<?=$onclick_elegir?>"><?=fecha($res_comprobante->fields['fecha_mod'])?></td> 
        <td onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['comentario']?></td>      
         
        <td onclick="<?=$onclick_editar?>" align="center"><img src='../../imagenes/editar1.png' style='cursor:hand;'></td>
        <td onclick="<?=$onclick_notificar?>" align="center"><img src='../../imagenes/iconnote_resize.gif' style='cursor:hand;'></td>     
      </tr> 
      
      <?$res_comprobante->movenext();
    }
   }?>
</table></td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
<tr><td>
<table class="bordes" align="center" width="70%">
     <tr align="center" id="sub_tabla">
      <td colspan="2">  
        Nuevo Egreso
      </td>
     </tr>
     <tr><td class="bordes"><table>
       <tr>
         <td>        
           <tr>
              <td align="right">
                <b>Rubro:</b>
              </td>
              <td align="left">                   
              <select name=ins_nombre Style="width=450px">
                <? if ((!($ins_nombre)) or $ins_nombre==-1) {?>
                  <option value=-1>Seleccione</option>
                       <?
                       $sql= "select * from proteger.inciso order by id_inciso";
                       $res_efectores=sql($sql) or fin_pagina();
               		   while (!$res_efectores->EOF){ 
                        $id_servicio=$res_efectores->fields['id_inciso'];
                        $descripcion=$res_efectores->fields['ins_nombre'];
                       ?>
                         <option value='<?=$id_servicio;?>'><?=$id_servicio.' - '.$descripcion?></option>
                       <?
                       $res_efectores->movenext();
                       }
                       }
                       else {
                        $sql_inc= "select * from proteger.inciso where id_inciso='$ins_nombre'";
                        $rs_sql_inc=sql($sql_inc) or die;
                        $descripcion=$rs_sql_inc->fields['ins_nombre'];
                          ?>
                         <option value='<?=$ins_nombre;?>'><?=$descripcion?></option> 
                      <? }                   
                       ?>
                  
                  
                  </select>
              </td>
           </tr>
           <tr>
              <td align="right">
                <b>Monto Preventivo:</b>
              </td>
				<td align="left">         			
                <input type=text id=monto_preventivo name=monto_preventivo value='<?//=($result_sql_inciso->fields['monto_preventivo']*30)/100;?>' size=30 align=right>
	            </td>
              
           </tr>
           <tr>
            <td align="right">
                <b>Fecha del Preventivo:</b>
              </td>
              <td align="left">
              <input type=text id=fecha_preventivo name=fecha_preventivo value='<?=$fecha_preventivo;?>' size=18 readonly>
                 <?=link_calendario("fecha_preventivo");?>                
              </td>       
           </tr>
           
           
           <tr>
              <td align="right">
                <b>Monto Comprometido:</b>
              </td>
              <td align="left">                   
              <input type="text" name="monto_comprometido" value="" size=30 align="right">
              </td>
           </tr>
           
           <tr>
            <td align="right">
                <b>Fecha del Comprometido:</b>
              </td>
              <td align="left">
               <input type=text id=fecha_comprometido name=fecha_comprometido value='<?=$fecha_comprometido;?>' size=18 readonly>
                 <?=link_calendario("fecha_comprometido");?>                 
              </td>       
           </tr>

           <tr>
              <td align="right">
                <b>Monto Egreso:</b>
              </td>
              <td align="left">                   
              <input type="text" name="monto_egreso" value="" size=30 align="right">
              </td>
           </tr>
           <tr>
            <td align="right">
                <b>Fecha Egreso:</b>
              </td>
              <td align="left">
              <input type=text id=fecha_egreso name=fecha_egreso value='<?=$fecha_egreso;?>' size=18 readonly>
                 <?=link_calendario("fecha_egreso");?>                 
              </td>       
           </tr>

           <tr>
              <td align="right">
                <b>Numero de Factura:</b>
              </td>
              <td align="left">                   
              <input type="text" name="numero_factura" value="" size=30 align="right">
              </td>
           </tr>

           <tr>
              <td align="right">
                <b>Numero de Expediente:</b>
              </td>
              <td align="left">                   
              <input type="text" name="numero_expediente_egreso" value="" size=30 align="right">
              </td>
           </tr>

           <tr>
              <td align="right">
                <b>Pagado:</b>
              </td>
              <td align="left">                   
              <input type="checkbox" name="pagado">
              </td>
           </tr>
               
           <tr>
                <td align="right">
                    <b>Detalle de Expediente:</b>
                </td>           
                  <td align='left'>
                      <textarea cols='90' rows='5' name='comentario1' ></textarea>
                  </td>
              </tr>              
          </td>
       </tr>
     </table></td></tr>  
          <tr>
        <td align="center" colspan="2" class="bordes">          
       	<input type="submit" name="guardar" value="Guardar Egreso" title="Guardar Ingreso" Style="width=300px" onclick="return control_nuevos_egresos()">
        </td>
     </tr> 
   </table> 
 </td></tr>
 
<?//tabla de comprobantes
$query="SELECT 
  *
FROM
  proteger.egreso 
  inner join proteger.inciso using (id_inciso) 
  where cuie='$cuie'
  order by id_egreso DESC";
$res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();

$q2="SELECT sum(monto_comprometido) as monto_comprometido_no_pago
			from proteger.egreso where cuie='$cuie' and (pagado is null or pagado='')";

$res_q2=sql($q2) or fin_pagina();

?>
<tr><td><table width="100%" class="bordes" align="center">
  <tr align="center" id="mo">
    <td align="center" width="3%">
     <img id="imagen_2" src="<?=$img_ext?>" border=0 title="Mostrar egresos" align="left" style="cursor:hand;" onclick="muestra_tabla(document.all.prueba_vida1,2);" >
    </td>
    <td align="center"><h4>
     <b>Total de Egresos: <?=number_format($res_saldo->fields['egre'],2,',','.')?> 
     Compr. NO Pagado: <?=number_format($res_saldo->fields['egre_comp'],2,',','.')?> // 
	<font color=#F781F3>
	<?$saldo_real=$res_saldo->fields['ingre']-$res_saldo->fields['egre'];?>
	Saldo= <?=number_format($saldo_real,2,',','.')?> //</font>
  
  <font color=#F931A1>
	<?$saldo_real=$res_saldo->fields['ingre']-$res_saldo->fields['egre'];?>
	Saldo Real= <?=number_format($saldo_real-$res_saldo->fields['egre_comp'],2,',','.')?> //</font>
  
  <font color=#45F433>
	<?$saldo_virtual=$saldo_real-$res_saldo->fields['egre_comp']-$res_saldo->fields['monto_preventivo']?>
	Saldo Virtual= <?=number_format($saldo_virtual,2,',','.')?></font>
	</b>
    </h4>
     <input type="hidden" value="<?=$saldo_real?>" name="saldo_real">
    </td>
  </tr>
</table></td></tr>
<tr><td><table id="prueba_vida1" border="1" width="100%" style="display:none;border:thin groove">
  <?if ($res_comprobante->RecordCount()==0){?>
   <tr>
    <td align="center">
     <font size="3" color="Red"><b>No existen Egresos para este Efector</b></font>
    </td>
   </tr>
   <?}
   else{    
    ?>
    <tr id="sub_tabla">         
      <td width="3%">ID</td>
      <td width="5%">Rubro</td>
      <td width="5%">Monto Preventivo</td>      
      <td width="5%">Fecha Preventivo</td>
      <td width="5%">Monto Comprometido</td>     
      <td width="5%">Fecha Comprometido</td>
      <td width="5%">Monto Egreso</td>
      <td width="5%">Fecha Egreso</td>
      <td width="5%">Numero Fact.</td>
      <td width="5%">Numero Exped.</td>
      <td width="5%">Pagado</td>
      <td width="5%">Usuario</td>
      <td width="5%">Editar Reg..</td>
      <td width="5%">Modificado</td>
      <td width="5%">Fecha modif</td>
      <td width="5%">Det.Exp.</td>
    </tr>
    <?
    $res_comprobante->movefirst();
    while (!$res_comprobante->EOF) {
        $ref_e = encode_link("detalle_egreso_admin2.php",array("id_egreso"=>$res_comprobante->fields['id_egreso'],"cuie"=>$res_comprobante->fields['cuie']));             
            $onclick_editar_e="location.href='$ref_e'";
      
        if (permisos_check('inicio','contabilidad_exepciones')){
        $ref1 = encode_link("ingre_egre_admin2.php",array("id_egreso"=>$res_comprobante->fields['id_egreso'],"marcar2"=>"True","cuie"=>$res_comprobante->fields['cuie']));
              $id_egreso=$res_comprobante->fields['id_egreso'];
              $onclick_eliminar="if (confirm('Esta Seguro que Desea Eliminar Egreso $id_egreso ?')) location.href='$ref1'
                          else return false;  ";    
      } else $onclick_elegir1="alert ('Debe Tener Permisos Especiales para poder eliminar.')";
      
              ?>
          <? if ($res_comprobante->fields['id_inciso']==1) $tr=atrib_tr1();
             else $tr=atrib_tr()
          
          ?>    
             
      <tr <?=$tr?>>       
        <td><?=$res_comprobante->fields['id_egreso']?></td>
        <td><?=$res_comprobante->fields['ins_nombre']?></td>
        <td><?=number_format($res_comprobante->fields['monto_preventivo'],2,',','.')?></td>
        <td><?=fecha($res_comprobante->fields['fecha_preventivo'])?></td>
        <td><?=number_format($res_comprobante->fields['monto_comprometido'],2,',','.')?></td>
        <td><?=($res_comprobante->fields['fecha_comprometido']=='1800-01-01')?'':fecha($res_comprobante->fields['fecha_comprometido'])?></td>
        <td><?=number_format($res_comprobante->fields['monto_egreso'],2,',','.')?></td>
        <td><?=($res_comprobante->fields['fecha_egreso']=='1800-01-01')?'':fecha($res_comprobante->fields['fecha_egreso'])?></td>

        <td><?=$res_comprobante->fields['numero_factura']?></td>   
        <td><?=$res_comprobante->fields['numero_expediente']?></td>
        <td align="center"><?=($res_comprobante->fields['pagado']=='si')?'SI':'NO'?></td>
        <td><?=$res_comprobante->fields['usuario']?></td>
        <td onclick="<?=$onclick_editar_e?>" align="center"><img src='../../imagenes/editar1.png' style='cursor:hand;'></td>  
        <td><?=$res_comprobante->fields['usuario_mod']?></td>
        <td><?=fecha($res_comprobante->fields['fecha_mod'])?></td>  
        <td><?=$res_comprobante->fields['comentario']?></td> 
      </tr> 
      
      <?$res_comprobante->movenext();
    }
   }?>
</table></td></tr>

<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>

 <tr><td><table width=100% align="center" class="bordes">
  <tr align="center">
   <td>
     <input type=button name="volver" value="Volver" onclick="document.location='ing_egre_listado2.php'"title="Volver al Listado" style="width=150px">     
   </td>
  </tr>
 </table></td></tr>
 
</table>

</form>
<?=fin_pagina();// aca termino ?>
