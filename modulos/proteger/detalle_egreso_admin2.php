<?
require_once ("../../config.php");


extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

if ($_POST['guardar']=="Guardar Egreso"){
    
  $monto_preventivo=$_POST['monto_preventivo'];
  $fecha_preventivo=Fecha_db($_POST['fecha_preventivo']);
  $monto_comprometido=$_POST['monto_comprometido'];
  $fecha_comprometido=($_POST['fecha_comprometido'])?Fecha_db($_POST['fecha_comprometido']):'1800-01-01';
  $monto_egreso=$_POST['monto_egreso'];
  $fecha_egreso=($_POST['fecha_egreso'])?Fecha_db($_POST['fecha_egreso']):'1800-01-01';
  $comentario=$_POST['comentario'];
  $usuario=$_ses_user['name'];  
  $numero_factura=($_POST['numero_factura'])?$_POST['numero_factura']:0;
  $numero_expediente=$_POST['numero_expediente'];
  $pagado=($_POST['pagado']=='on')?'si':'no';
  $fecha_mod=date("Y-m-d");
  $id_inciso=$_POST['ins_nombre'];

  
  $db->StartTrans();
  $query="UPDATE proteger.egreso set 
            monto_preventivo=$monto_preventivo,
            fecha_preventivo='$fecha_preventivo',
            monto_comprometido=$monto_comprometido,
            fecha_comprometido='$fecha_comprometido',
            monto_egreso=$monto_egreso,
            fecha_egreso='$fecha_egreso',
            comentario='$comentario',
            usuario_mod='$usuario',
            numero_factura=$numero_factura,
            numero_expediente='$numero_expediente',
            pagado='$pagado',
            fecha_mod='$fecha_mod',
            id_inciso=$id_inciso
          where id_egreso=$id_egreso";
                
   sql($query, "Error al Modificar el Egreso") or fin_pagina();      
      
   $accion="Se Modifico el Egreso Numero: $id_egreso";
   $db->CompleteTrans(); 
    
}

if ($_POST['borrar']=="Borrar Egreso"){
    
  $usuario=$_ses_user['name'];  
  $fecha_hoy=date("Y-m-d");
  $db->StartTrans();
  
  
  //borra los montos de los egresos en la tabla de proteger.egreso
    
  /*$sql_id_egreso="UPDATE proteger.egreso set 
  monto_egreso=0,
  usuario='$usuario',
  monto_comprometido=0 
  where id_egreso='$id_egreso'";*/

  $sql_id_egreso="DELETE from proteger.egreso where id_egreso=$id_egreso";

  $res_id_egreso=sql($sql_id_egreso,"no se pudo modificar el registro de egreso") or die;
  
      
   $accion="Se Elimino el Egreso Numero: $id_egreso";
   $db->CompleteTrans(); 
  
}

echo $html_header;
?>
<script>
//controlan que ingresen todos los datos necesarios par el muleto

 function control_borrar_egresos()
{
  if(document.all.borrar.value=="Borrar Egreso"){
    if (confirm('Esta seguro que desea ELIMINAR el Egreso'))return true;
    else return false;
   }
  
 } 
  
 function control_modificar_egresos()
 {
   
   if(document.all.guardar.value=="Guardar Egreso"){
    if (confirm('Esta seguro que desea MODIFICAR el Egreso'))return true;
    else return false;
   }
  } 

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

<form name='form1' action='detalle_egreso_admin2.php' method='POST' enctype='multipart/form-data'>
<input type="hidden" value="<?=$id_egreso?>" name="id_egreso">

<?
$sql_egreso="SELECT * from proteger.egreso where id_egreso=$id_egreso";
$res_egreso=sql($sql_egreso,"no se pudo ejecutar la consulta sobre el egreso");

$cuie=$res_egreso->fields['cuie'];
$monto_preventivo= $res_egreso->fields['monto_preventivo'];
$fecha_preventivo= $res_egreso->fields['fecha_preventivo'];
$comentario= $res_egreso->fields['comentario'];
$fecha_comprometido= ($res_egreso->fields['fecha_comprometido']=='1800-01-01')?'':fecha($res_egreso->fields['fecha_comprometido']);
$monto_egreso= $res_egreso->fields['monto_egreso'];
$fecha_egreso= ($res_egreso->fields['fecha_egreso']=='1800-01-01')?'':fecha($res_egreso->fields['fecha_egreso']);
$fecha_mod=date("Y-m-d"); 
$monto_comprometido= $res_egreso->fields['monto_comprometido'];
$numero_factura= $res_egreso->fields['numero_factura'];
$numero_expediente= $res_egreso->fields['numero_expediente'];
$pagado= $res_egreso->fields['pagado'];
$ins_nombre= $res_egreso->fields['id_inciso'];

?>

<?echo "<center><b><font size='+2' color='red'>$accion</font></b></center>";?>
<input type="hidden" name="cuie" value="<?=$cuie?>">

<?
$sql_efec="SELECT * from nacer. efe_conv where cuie='$cuie'";
$res_efec=sql($sql_efec,"No se pudieron traer los datos del efector");
$nombre=$res_efec->fields['nombre'];
$domicilio=$res_efec->fields['domicilio'];
$ciudad=$res_efec->fields['ciudad'];

$sql="SELECT monto_egreso from proteger.egreso
    where cuie='$cuie'";
$res_egreso=sql($sql,"no puede calcular el saldo");

if ($res_egreso->recordCount()==0){
  $sql="select ingre as total, ingre,egre,deve,egre_comp from
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
     <font size=+1><b>Egreso Numero <?=$id_egreso?></b></font>    
    </td>
 </tr>
 <tr><td>
  <table width=70% align="center" class="bordes">
     <tr>
      <td id=mo colspan="2">
       <b> Descripción del Efector</b>
      </td>
     </tr>
     <br>
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
      <td align="right"><b><font size="+1" color="Blue">Saldo Real:</b></font></td>
      <td align="left"> 
      <?$saldo_real=$res_saldo->fields['ingre']-$res_saldo->fields['egre'];?>                  
      <b><font size="+1" color="Blue"><?=number_format($saldo_real,2,',','.')?></font></b>
      </td>

      <td align="right"><b><font size="+1" color="green">Saldo Virual:</b></font></td>
      <td align="left"> 
      <?$saldo_virtual=$saldo_real-$res_saldo->fields['monto_preventivo']?>                  
        <b><font size="+1" color="green"><?=number_format($saldo_virtual,2,',','.')?></font></b>
      
     <font >
    </b></td>
      </tr>  
            
        </table>
      </td>      
     </tr>
   </table>     
   <table class="bordes" align="center" width="70%">
     <tr align="center" id="sub_tabla">
      <td colspan="2">  
        Datos del Egreso
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
                <input type=text id=monto_preventivo name=monto_preventivo value='<?=$monto_preventivo;?>' size=30 align=right>
              </td>
              
           </tr>
           <tr>
            <td align="right">
                <b>Fecha del Preventivo:</b>
              </td>
              <td align="left">
              <input type=text id=fecha_preventivo name=fecha_preventivo value='<?=Fecha($fecha_preventivo);?>' size=18 readonly>
                 <?=link_calendario("fecha_preventivo");?>                
              </td>       
           </tr>
           
           
           <tr>
              <td align="right">
                <b>Monto Comprometido:</b>
              </td>
              <td align="left">                   
              <input type="text" name="monto_comprometido" value="<?=$monto_comprometido;?>" size=30 align="right">
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
              <input type="text" name="monto_egreso" value="<?=$monto_egreso;?>" size=30 align="right">
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
              <input type="text" name="numero_factura" value="<?=$numero_factura;?>" size=30 align="right">
              </td>
           </tr>

           <tr>
              <td align="right">
                <b>Numero de Expediente:</b>
              </td>
              <td align="left">                   
              <input type="text" name="numero_expediente" value="<?=$numero_expediente;?>" size=30 align="right">
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
                  <textarea name='comentario' rows="10" cols="80" style="font-size: 15px;"><?=$comentario?></textarea>
                  </td>
              </tr>              
          </td>
       </tr>
     </table></td></tr> 
     <tr>
        <table class="bordes" align="center" width="70%">
     <tr align="center" id="sub_tabla"></tr>
      <td colspan="2"></td>
        <td align="center" colspan="2" class="bordes">          
          <input type="submit" name="guardar" value="Guardar Egreso" title="Guardar Egreso" Style="width=300px" onclick="return control_modificar_egresos()">
        </td>
     <td align="center" colspan="2" class="bordes">         
          <input type="submit" name="borrar" value="Borrar Egreso" title="Borrar Egreso" Style="width=300px" onclick="return control_borrar_egresos()">
        </td>
     </table>
     </tr> 
   </table> 
 </td></tr>
 

 
 <tr><td><table width=100% align="center" class="bordes">
  <tr align="center">
   <td>
     <? $ref = encode_link("ingre_egre_admin2.php",array("cuie"=>$cuie));
     ?>
     <input type=button name="volver" value="Volver" onclick="document.location='<?=$ref?>'" title="Volver al Listado" style="width=150px">     
   </td>
  </tr>
 </table></td></tr>
 
</table>

</form>
<?=fin_pagina();// aca termino ?>
