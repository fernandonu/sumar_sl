<?
require_once ("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);


if ($anular=='Anular') {
      $db->StartTrans();
      $sql_cons="SELECT * from remedios.prestaciones 
          inner join remedios.comprobantes on comprobantes.id_comprobante=prestaciones.id_comprobante
          inner join remedios.efectores on efectores.cuie=comprobantes.cuie
          where prestaciones.id_comprobante=$id_comprobante";
          
      $res_cons=sql($sql_cons,"No se pudieron traer los datos de prestaciones/comprobantes") or fin_pagina();
      
      while (!$res_cons->EOF){
        $id_efector=$res_cons->fields['id_efector'];
        $id_remedio=$res_cons->fields['id_remedio'];
        $cantidad=$res_cons->fields['cantidad'];
        $sql_stock="SELECT * from remedios.stock_producto WHERE id_efector=$id_efector and id_remedio=$id_remedio";
        $res_stock=sql($sql_stock) or fin_pagina();
        $u_entregadas=$res_stock->fields['u_entregadas']-$cantidad;
        $total_2=$u_entregadas+$res_stock->fields['salida_clearing']+$res_stock->fields['salida_no_apto']+$res_stock->fields['salida_robo'];
        $final=$res_stock->fields['total_1']-$total_2;

        $update="UPDATE remedios.stock_producto 
                  SET u_entregadas=$u_entregadas,
                      total_2=$total_2,
                      final=$final
                      WHERE id_efector=$id_efector and id_remedio=$id_remedio";
        sql($update,"no se pudo actualizar stock_producto") or fin_pagina();
        
        $res_cons->Movenext();      
      };
      
      $sql_anular="DELETE from remedios.prestaciones where id_comprobante=$id_comprobante";
      $res_anular=sql($sql_anular,"No se pudo eliminar las prestaciones") or fin_pagina();
      $sql_anular="DELETE from remedios.comprobantes where id_comprobante=$id_comprobante";
      $res_anular=sql($sql_anular,"No se pudo eliminar el comprobante") or fin_pagina();
      $db->CompleteTrans();

      };

$sql="SELECT distinct on (beneficiarios.numero_doc,comprobantes.fecha_entrega,comprobantes.cuie)
  beneficiarios.id_beneficiarios,
  beneficiarios.numero_doc,
  beneficiarios.apellido_benef,
  beneficiarios.nombre_benef,
  beneficiarios.sexo,
  beneficiarios.fecha_nacimiento_benef,
  comprobantes.cuie,
  efectores.nombre as efector
  
 from remedios.comprobantes 
inner join uad.beneficiarios on comprobantes.id_beneficiarios=beneficiarios.id_beneficiarios
inner join remedios.efectores on efectores.cuie=comprobantes.cuie
where comprobantes.id_beneficiarios='$id_beneficiarios'";
$res_comprobante=sql($sql, "Error al traer los datos del beneficiario") or fin_pagina();

$afiapellido=$res_comprobante->fields['apellido_benef'];
$afinombre=$res_comprobante->fields['nombre_benef'];
$afidni=$res_comprobante->fields['numero_doc'];
$afifechanac=$res_comprobante->fields['fecha_nacimiento_benef'];
$afisexo=$res_comprobante->fields['sexo'];
$efector=$res_comprobante->fields['cuie']." - ".$res_comprobante->fields['efector'];

echo $html_header;
?>
<script >
	
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
<form name='form1' action='listado_benef_prestacion_detalle.php' method='POST'>
<input type="hidden" value="<?=$usuario1?>" name="usuario1">
<input type="hidden" name="id_beneficiarios" value="<?=$id_beneficiarios?>">


<table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    
 </tr>
 <tr><td>
  <table width="70%" align="center" class="bordes">
     <tr>
      <td id="mo" colspan="2">
       <b> Descripci&oacute;n del Beneficiario</b>
      </td>
     </tr>
     <tr>
       <td>
        <table>
         
         <tr>
         	<td align="right">
         	  <b>Apellido:
         	</td>         	
            <td align='left'>
              <input type='text' name='afiapellido' value='<?=$afiapellido;?>' size=60 align='right' readonly></b>
            </td>
         </tr>
         <tr>
            <td align="right">
         	  <b> Nombre:
         	</td>   
           <td  colspan="2">
             <input type='text' name='afinombre' value='<?=$afinombre;?>' size=60 align='right' readonly></b>
           </td>
          </tr>
          <tr>
           <td align="right">
         	  <b> Documento:
         	</td> 
           <td colspan="2">
             <input type='text' name='afidni' value='<?=$afidni;?>' size=60 align='right' readonly></b>
           </td>
          </tr>
           <tr>
           <td align="right">
         	  <b> Fecha de Nacimiento:
         	</td> 
           <td colspan="2">
             <input type='text' name='afidni' value='<?=fecha($afifechanac);?>' size=60 align='right' readonly></b>
           </td>
          </tr>
          
          <tr>
           <td align="right">
         	  <b> Efector Asignado:
         	</td> 
           <td colspan="2">
             <input type='text' name='nombreefecto' value='<?=$efector;?>' size=60 align='right' readonly></b>
           </td>
          </tr>
        </table>
      </td>      
     </tr>
   </table>     
	 
  
<?//tabla de comprobantes
$query="SELECT distinct on (prestaciones.id_comprobante)
	prestaciones.id_comprobante,
	comprobantes.cuie,
	efectores.nombre as efector,
	comprobantes.usuario,
	comprobantes.fecha_entrega
	from remedios.prestaciones
	inner join remedios.comprobantes on prestaciones.id_comprobante=comprobantes.id_comprobante
	inner join remedios.efectores on efectores.cuie=comprobantes.cuie
	where comprobantes.id_beneficiarios='$id_beneficiarios'";

$res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();?>

<tr><td><table width="100%" class="bordes" align="center">
	<tr align="center" id="mo">
	  <td align="center" width="3%">
	   <img id="imagen_2" src="<?=$img_ext?>" border=0 title="Mostrar Comprobantes" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida,2);" >
	  </td>
	  <td align="center">
	   <b>Comprobantes</b>
	  </td>
	</tr>
</table></td></tr>
<tr><td><table id="prueba_vida" border="1" width="100%" style="display:none;border:thin groove">
	
	 	<tr id="sub_tabla">	
	 	  <td width="1%">&nbsp;</td>
	 		<td width="10%">N&uacute;mero de Comprobante</td>
	 		<td width="20%">Cuie</td>
	 		<td width="30%">Efector</td>
	 		<td width="30%">Usuario</td>
	 		<td width="20%">Fecha Comprobante</td>
      <td width="20%">Anular</td>	 		
	 		</tr>
	 	
			<?
	 		$res_comprobante->movefirst();
	 		while (!$res_comprobante->EOF) {
	 		$id_tabla="tabla_".$res_comprobante->fields['id_comprobante'];	
	 		$onclick_check=" javascript:(this.checked)?Mostrar('$id_tabla'):Ocultar('$id_tabla')";
	 		
	 		//consulta para saber si tiene pretaciones el comprobante
	 		$sql="SELECT COUNT (*)  as cant_prestaciones from remedios.prestaciones WHERE id_comprobante='". $res_comprobante->fields['id_comprobante']."'";
	 		$cant_prestaciones=sql($sql,"no se puede traer la cantidad de prestaciones") or die();
	 		$cant_prestaciones=$cant_prestaciones->fields['cant_prestaciones'];
	 		?>
	 		<tr <?=atrib_tr()?>>
	 			<td>
	      <input type=checkbox name="check_prestacion" value="" onclick="<?=$onclick_check?>" class="estilos_check">
	      </td>	
		 		<td align ="center" ><font size="3"><b><?=$res_comprobante->fields['id_comprobante']?></b></font></td>
		 		<td align ="center" ><font size="3"><b><?=$res_comprobante->fields['cuie']?></b></font></td>
		 		<td ><font size="3"><b><?=$res_comprobante->fields['efector']?></b></font></td>
		 		<td align="center" ><font size="3"><b><?=$res_comprobante->fields['usuario']?></b></font></td>
		 		<td ><font size="3"><b><?=fecha($res_comprobante->fields['fecha_entrega'])?></b></font></td>		 		
		 	  
        <?$id_comprobante=$res_comprobante->fields['id_comprobante'];
          $ref = encode_link("listado_benef_prestacion_detalle.php",array("id_beneficiarios"=>$id_beneficiarios,"id_comprobante"=>$id_comprobante,"anular"=>"Anular"));
          $onclick_anular="if (confirm('Esta Seguro que Desea ANULAR Comprobante $id_comprobante?')) location.href='$ref'
                        else return false;";?> 
                        
        <td onclick="<?=$onclick_anular?>" align="center">
        <?echo "<img src='../../imagenes/sin_desc.gif' style='cursor:pointer;'>"?>
        </td>
      </tr>	
		 	<tr>
	          <td colspan=9>
	
	                  <?
	                  $sql="SELECT * from remedios.prestaciones 
	                  		inner join remedios.remedio on remedio.id_remedio=prestaciones.id_remedio
							where prestaciones.id_comprobante=". $res_comprobante->fields['id_comprobante']." order by id_prestacion DESC";
	                  $result_items=sql($sql) or fin_pagina();
	                  ?>
	                  <div id=<?=$id_tabla?> style='display:none'>
	                  <table width="90%" align="center" class="bordes" border=1>
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
		                           <tr id="ma">		                               
		                               <td>Cantidad</td>
		                               <td>Codigo</td>
		                               <td>Descripci&oacute;n</td>
		                               <td>Producto</td>
		                                            
		                            </tr>
		                            <?while (!$result_items->EOF){?>
			                            <tr>
			                            	 <td align ="center" class="bordes"><?=$result_items->fields['cantidad']?></td>			                                 
			                                 <td align ="center" class="bordes"><?=$result_items->fields['codigo']?></td>
			                                 <td class="bordes"><?=$result_items->fields['descripcion']?></td>
			                                 <td class="bordes"><?=$result_items->fields['producto']?></td>
			                                 </tr>
		                            	<?$result_items->movenext();
		                            }//del while?>
								               
	               </table>
	               </div>
	
	         </td>
	      </tr>  	
	 		<?$res_comprobante->movenext();
	 }
	}?>
</table></td></tr>
 
 <tr><td><table width=100% align="center" class="bordes">
  <tr align="center">
   <td>
   	  	<input type=button name="volver" value="Volver" onclick="document.location='listado_benef_prestacion.php'"title="Volver al Listado" style="width=150px">     
   	</td>
  </tr>
 </table></td></tr>
 
</table>

   
</form>
<?=fin_pagina();// aca termino ?>
