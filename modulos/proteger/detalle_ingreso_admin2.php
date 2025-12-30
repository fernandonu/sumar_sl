<?
require_once ("../../config.php");


extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

if ($_POST['borrar']=="Borrar Ingreso"){

  $q="DELETE from proteger.ingreso where id_ingreso=$id_ingreso";
  sql($q,"No se puede eliminar el registro") or fin_pagina();

}


if ($_POST['guardar']=="Guardar Ingreso"){
  
  $monto_recurso=$_POST['monto_recurso'];
  $fecha_deposito=fecha_db($_POST['fecha_deposito']);
  $comentario=$_POST['comentario'];
  $usuario=$_ses_user['name']; 
  $fecha=date("Y-m-d");
  $periodo=$_POST['periodo'];
  $fecha_expediente=fecha_db($_POST['fecha_expediente']);
  $numero_expediente=$_POST['numero_expediente'];

  
  $sql_update="UPDATE proteger.ingreso set 
              monto_recurso=$monto_recurso,
              fecha_deposito='$fecha_deposito',
              comentario='$comentario',
              usuario='$usuario',
              fecha_mod='$fecha',
              periodo='$periodo',
              fecha_expediente='$fecha_expediente',
              numero_expediente=$numero_expediente 
              where id_ingreso=$id_ingreso";

  $res_update=sql($sql_update,"No se pudo actualizar la tabla de ingresos");
    
}
echo $html_header;
?>
<script>
function eliminar_registro()
  {
    if (confirm('Esta Seguro que Desea ELIMINAR el Registro de Ingreso?'))return true;
    else return false; 
  }



//controlan que ingresen todos los datos necesarios par el muleto
function control_nuevos_ingresos()
{
 if (confirm('Esta Seguro que Desea MODIFICAR el registro del Ingreso?'))return true;
 else return false; 
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

<form name='form1' action='detalle_ingreso_admin2.php' method='POST' enctype='multipart/form-data'>
<input type="hidden" value="<?=$id_ingreso?>" name="id_ingreso">


<?echo "<center><b><font size='+2' color='red'>$accion</font></b></center>";?>
<input type="hidden" name="cuie" value="<?=$cuie?>">
<?
$sql_efec="SELECT * from nacer. efe_conv where cuie='$cuie'";
$res_efec=sql($sql_efec,"No se pudieron traer los datos del efector");
$nombre=$res_efec->fields['nombre'];
$domicilio=$res_efec->fields['domicilio'];
$ciudad=$res_efec->fields['ciudad'];

$sql="select monto_egreso from proteger.egreso
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
$sql="select ingre-egre as total, ingre,egre,deve,egre_comp from
        (select sum (monto_recurso)as ingre from proteger.ingreso
        where cuie='$cuie') as ingreso,
        (select sum (monto_egreso)as egre from proteger.egreso
        where cuie='$cuie' and monto_comprometido <> 0) as egreso,
        (select sum (monto_recurso)as deve from proteger.ingreso
        where cuie='$cuie') as devengado,
        (select sum (monto_comprometido)as egre_comp from proteger.egreso
        where cuie='$cuie') as egre_comp";
}
$res_saldo=sql($sql,"no puede calcular el saldo");

$q="SELECT * from proteger.ingreso where id_ingreso=$id_ingreso";
$res_q=sql($q,"No se pudo ejecutar la consulta sobre el ingreso") or fin_pagina();

$monto_recurso=$res_q->fields['monto_recurso'];
$fecha_deposito=$res_q->fields['fecha_deposito']; 
$comentario=$res_q->fields['comentario']; 
$periodo=$res_q->fields['periodo']; 
$fecha_expediente=$res_q->fields['fecha_expediente']; 
$numero_expediente=$res_q->fields['numero_expediente']; 

?>
<table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
     <font size=+1><b>Ingreso Numero: <?=$id_ingreso;?></b></font>    
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
        &nbsp;&nbsp;&nbsp;&nbsp;
      </tr>  
            
        </table>
      </td>      
     </tr>
   </table>     
   <table class="bordes" align="center" width="70%">
     <tr align="center" id="sub_tabla">
      <td colspan="2">  
        Editar Ingreso
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
              <input type="numeric" name="numero_expediente" value="<?=$numero_expediente;?>" Style="width=550px">                  
              </td>
              
           </tr>

           <tr>
              <td align="right">
                <b>Monto Recurso:</b>
              </td>
               <td align="left">                    
              <input type="numeric" name="monto_recurso" value="<?=number_format($monto_recurso,2,',','.');?>" Style="width=550px">                  
              </td>
              
           </tr>
           <tr>
            <td align="right">
                <b>Fecha de Deposito:</b>
              </td>
              <td align="left">
              <input type=text id=fecha_deposito name=fecha_deposito value='<?=Fecha($fecha_deposito);?>' size=18 readonly>
                 <?=link_calendario("fecha_deposito");?>                 
              </td>       
           </tr>
          
           
           
           <tr>
              <td align="right">
                 <b>Periodo:</b>
              </td>
                         
              <td align="left">
              <input type="text" name="periodo" value='<?=$periodo;?>' size=18 align="right">
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
          <b>Comentario:</b>
          </td>           
          <td align='left'>
          <textarea cols='70' rows='3' name='comentario'><?=$comentario;?></textarea>
          </td>
          </tr>                          
        
        </td>
       </tr>
     </table></td></tr> 
     <tr>
       <table class="bordes" align="center" width="70%">
      <tr align="center" id="sub_tabla"></tr>
      <td align="center" colspan="2" class="bordes">          
        <input type="submit" name="guardar" value="Guardar Ingreso" title="Guardar Ingreso" Style="width=300px" onclick="return control_nuevos_ingresos()">
      </td>

       <td align="center" colspan="2" class="bordes">          
        <input type="submit" name="borrar" value="Borrar Ingreso" title="Borrar Ingreso" Style="width=300px" onclick="return eliminar_registro()">
      </td>
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
