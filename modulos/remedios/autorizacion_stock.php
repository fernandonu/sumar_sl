<?
/*
Author: seba
$Revision: 1.43 $
$Date: 2015/05/04 13:53:00 $
*/

require_once ("../../config.php");
extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

cargar_calendario();
//$usuario1=$_ses_user['id'];
$user_name=$_ses_user['name'];

$extras = array (
                 "id_remito" => $id_remito,
                 "id_efector" => $id_efector,
                 "cuie" => $cuie
                 );

variables_form_busqueda("autorizacion_stock",$extras);

$orden = array(
        "default" => "codigo",
        "codigo" => "Codigo",
        "descripcion" => "Descricion",
        "producto" => "Producto"
        );

$filtro = array(
		"codigo" => "Codigo",
    "descripcion"=> "Descripcion",
    "producto" => "Producto"
     );

function actualizar_stock($id_efector){

  //Funciona para producto que se repiten no mas de dos por entrada de remito
  $sql1="SELECT distinct id_remedio from remedios.stock_producto where id_efector=$id_efector order by 1";
  $res_sql1=sql($sql1) or fin_pagina();
  //sacar el cuie por id_efector
  $sql_efe="SELECT * from remedios.efectores where id_efector=$id_efector";
  $res_efe=sql($sql_efe) or fin_pagina();
  $cuie=strtolower($res_efe->fields['cuie']);
  
  while (!$res_sql1->EOF) {
    $id_remedio=$res_sql1->fields['id_remedio'];
    $sql2="SELECT id_stock_producto,remito from remedios.stock_producto 
            where id_efector=$id_efector and id_remedio=$id_remedio order by 1 DESC";
    
    $res_sql2=sql($sql2) or fin_pagina();
    
    if ($res_sql2->RecordCount()>1) {
        $res_sql2->Movefirst();
        $id_stock_producto=$res_sql2->fields['id_stock_producto'];
        $cantidad=$res_sql2->fields['remito'];
        $res_sql2->Movenext();
        $id_stock_producto_2=$res_sql2->fields['id_stock_producto'];
        $cantidad_2=$res_sql2->fields['remito'];
        
        $sql3="UPDATE remedios.stock_producto set remito=$cantidad+$cantidad_2,
              final=$cantidad+$cantidad_2,total_1=$cantidad+$cantidad_2 
              where id_stock_producto=$id_stock_producto_2";
        sql($sql3) or fin_pagina();
        
        $sql4="DELETE from remedios.stock_producto where id_stock_producto=$id_stock_producto";
        sql($sql4) or fin_pagina();
    }
    
    $sql2="SELECT id_stock,$cuie from remedios.stock 
            where id_remedio=$id_remedio and $cuie is not null order by 1 DESC";
    
    $res_sql2=sql($sql2) or fin_pagina();
    
    if ($res_sql2->RecordCount()>1) {
        $res_sql2->Movefirst();
        $id_stock=$res_sql2->fields['id_stock'];
        $cantidad=$res_sql2->fields[$cuie];
        $res_sql2->Movenext();
        $id_stock_2=$res_sql2->fields['id_stock'];
        $cantidad_2=$res_sql2->fields[$cuie];
        
        $sql3="UPDATE remedios.stock set $cuie=$cantidad+$cantidad_2
               where id_stock=$id_stock_2";
        sql($sql3) or fin_pagina();
        
        $sql4="DELETE from remedios.stock where id_stock=$id_stock";
        sql($sql4) or fin_pagina();
    }
    
    
  $res_sql1->Movenext();  
  }
} 

if ($_POST['autorizar']=="Autorizar") {
	
	$error = 0;
	$seleccionados = $_POST["chk_producto"] or Error("No se seleccionó ninguna solicitud para relacionar");
	
  if (!$error) {
		$sql_array = array();
    $sql_array_1 = array();
    $sql_array_2 = array();
    
    $cuie=strtolower($cuie);
		
			$fecha_aut=date("Y-m-d H:i:s");
			//$user=$_ses_user['login'];
			//$usuario_con="SELECT * from sistema.usuarios where login='$user'";
			//$resusu = sql($usuario_con,"Error al verificar usuario $user") or fin_pagina();
			//$user_id=$resusu->fields['id_usuario'];
			foreach ($seleccionados as $id_remito_remedio) {
			//actualizar estado de productos pendientes
      $sql_1="select * from remedios.remito_remedio where id_remito_remedio=$id_remito_remedio";
      $res_sql_1=sql($sql_1) or fin_pagina();
      $cantidad=$res_sql_1->fields['cantidad'];
      $id_remedio=$res_sql_1->fields['id_producto'];
      
      $sql_array[] = "UPDATE  remedios.remito_remedio
						        set condicion='a',
						            fecha_condicion='$fecha_aut',
                        id_user_aut='$user_name'
						        where id_remito_remedio=$id_remito_remedio";
                    
      $sql_temp="SELECT * from remedios.stock_producto where id_efector=$id_efector and id_remedio=$id_remedio";
      $res_temp=sql($sql_temp) or fin_pagina();
      if ($res_temp->RecordCount()==0) {
      
        $sql_array_1[] = "UPDATE remedios.stock SET $cuie=$cantidad WHERE id_remedio=$id_remedio";
                    
        $sql_array_2[] = "INSERT INTO  remedios.stock_producto
                    (id_efector,id_remedio,remito,total_1,final)
                    values ($id_efector,$id_remedio,$cantidad,$cantidad,$cantidad)";
		    }
      else {
        $sql_array_1[] = "UPDATE remedios.stock set $cuie=$cuie+$cantidad where id_remedio=$id_remedio";

        $sql_array_2[] = "UPDATE remedios.stock_producto set remito=remito+$cantidad,
                          total_1=total_1+$cantidad,final=final+$cantidad
                          where id_efector=$id_efector and id_remedio=$id_remedio";
      }
    
    //actualizar todos los valores
    
    } 
		$result = sql($sql_array) or fin_pagina();
    $result = sql($sql_array_1) or fin_pagina();
    $result = sql($sql_array_2) or fin_pagina();
		
    /*$sql_remito="SELECT count(*) as cantidad from remedios.remito_remedio where id_remito=$id_remito";
    $res_remito=sql($sql_remito) or fin_pagina();
    $cantidad_productos=$res_remito->fields['cantidad'];
    
    $sql_remito="SELECT count(*) as cantidad from remedios.remito_remedio 
                where id_remito=$id_remito and condicion='a'";
    $res_remito=sql($sql_remito) or fin_pagina();
    $cantidad_productos_aprobados=$res_remito->fields['cantidad'];*/

    $cantidad_modif="SELECT count(*) as cantidad from remedios.remito 
                      inner join remedios.remito_remedio using (id_remito)
                      where id_efector=$id_efector
                      and id_remito=$id_remito
                      and condicion='a'";
    $res_can_modif=sql($cantidad_modif) or fin_pagina();
    $total1=$res_can_modif->fields['cantidad'];

    $cantidad_modif="SELECT count(*) as cantidad from remedios.remito 
                      inner join remedios.remito_remedio using (id_remito)
                      where id_efector=$id_efector
                      and id_remito=$id_remito";
    $res_can_modif=sql($cantidad_modif) or fin_pagina();
    $total2=$res_can_modif->fields['cantidad'];

    if ($total1==$total2){
        $update_remito="UPDATE remedios.remito set estado='a' 
                        where id_remito=$id_remito
                        and id_efector=$id_efector";
        sql($update_remito) or fin_pagina();
    }; 
    
    actualizar_stock($id_efector);
    
    Aviso("El/los producto/s han sido Autorizados");
	}
}

if ($_POST['rechazar']=="Rechazar") {
	
	$error = 0;
	$seleccionados = $_POST["chk_producto"] or Error("No se seleccionó ninguna solicitud para relacionar");
	
  if (!$error) {
		$sql_array = array();
		
			$fecha_aut=date("Y-m-d H:i:s");
			//$user=$_ses_user['login'];
			//$usuario_con="SELECT * from sistema.usuarios where login='$user'";
			//$resusu = sql($usuario_con,"Error al verificar usuario $user") or fin_pagina();
			//$user_id=$resusu->fields['id_usuario'];
			
		foreach ($seleccionados as $id_remito_remedio) {
				$sql_array[] = "UPDATE  remedios.remito_remedio
                    set condicion='n',
                        fecha_condicion='$fecha_aut',
                        id_user_aut='$user_name'
                    where id_remito_remedio=$id_remito_remedio";
		}
		$result = sql($sql_array) or fin_pagina();

    $cantidad_modif="SELECT count(*) as cantidad from remedios.remito 
                      inner join remedios.remito_remedio using (id_remito)
                      where id_efector=$id_efector
                      and id_remito=$id_remito
                      and condicion='n'";
    $res_can_modif=sql($cantidad_modif) or fin_pagina();
    $total1=$res_can_modif->fields['cantidad'];

    $cantidad_modif="SELECT count(*) as cantidad from remedios.remito 
                      inner join remedios.remito_remedio using (id_remito)
                      where id_efector=$id_efector
                      and id_remito=$id_remito";
    $res_can_modif=sql($cantidad_modif) or fin_pagina();
    $total2=$res_can_modif->fields['cantidad'];

    if ($total1==$total2){
        $update_remito="UPDATE remedios.remito set estado='n' 
                        where id_remito=$id_remito
                        and id_efector=$id_efector";
        sql($update_remito) or fin_pagina();
    }

		Aviso("El/los Producto/s se modificaron a Rechazado");
	}
}
if ($_POST['pendiente']=="Pendiente") {
	
	$error = 0;
	$seleccionados = $_POST["chk_producto"] or Error("No se seleccionó ninguna solicitud para relacionar");
	
  $fecha_aut=date("Y-m-d H:i:s");
  
  if (!$error) {
		$sql_array = array();
			//$usuario_con="SELECT * from sistema.usuarios where login='$user'";
			//$resusu = sql($usuario_con,"Error al verificar usuario $user") or fin_pagina();
			//$user_id=$resusu->fields['id_usuario'];
			
		foreach ($seleccionados as $id_remito_remedio) {
				$sql_array[] = "UPDATE  remedios.remito_remedio
                    set condicion='p',
                        fecha_condicion='$fecha_aut',
                        id_user_aut='$user_name'
                    where id_remito_remedio=$id_remito_remedio";
		}
		$result = sql($sql_array);

    $cantidad_modif="SELECT count(*) as cantidad from remedios.remito 
                      inner join remedios.remito_remedio using (id_remito)
                      where id_efector=$id_efector
                      and id_remito=$id_remito
                      and condicion='p'";
    $res_can_modif=sql($cantidad_modif) or fin_pagina();
    $total1=$res_can_modif->fields['cantidad'];

    $cantidad_modif="SELECT count(*) as cantidad from remedios.remito 
                      inner join remedios.remito_remedio using (id_remito)
                      where id_efector=$id_efector
                      and id_remito=$id_remito";
    $res_can_modif=sql($cantidad_modif) or fin_pagina();
    $total2=$res_can_modif->fields['cantidad'];

    if ($total1==$total2){
        $update_remito="UPDATE remedios.remito set estado='p' 
                        where id_remito=$id_remito
                        and id_efector=$id_efector";
        sql($update_remito) or fin_pagina();
    };

		Aviso("El/los Producto/s se modificaron a PENDIENTES para su evaluacion");
	}
}


$sql_tmp="SELECT * from remedios.remito
      inner join remedios.remito_remedio on remito_remedio.id_remito=remito.id_remito
      inner join remedios.remedio on remedio.id_remedio=remito_remedio.id_producto";

if (!$id_remito) {

$id_remito=$_POST['id_remito'];
  if ($cmd==1)
    $where_tmp="remito.id_remito=$id_remito and (estado='p')";
  if ($cmd==2)
   $where_tmp="remito.id_remito=$id_remito and (estado='a')";  
  if ($cmd==3)
    $where_tmp="remito.id_remito=$id_remito and (estado='n')";
}
else {
  if ($cmd==1)
    $where_tmp="remito.id_remito=$id_remito and (estado='p')";
  if ($cmd==2)
   $where_tmp="remito.id_remito=$id_remito and (estado='a')";  
  if ($cmd==3)
    $where_tmp="remito.id_remito=$id_remito and (estado='n')";
}

$sql_num_remito="SELECT * from remedios.remito where id_remito=$id_remito";
$res_num_remito=sql($sql_num_remito) or fin_pagina();
$num_remito=$res_num_remito->fields['numero_remito'];

echo $html_header;
?>
<script>
//controlan que ingresen todos los datos necesarios par el muleto

function seleccionar(chkbox){
	for (var i=0;i < document.forms["form1"].elements.length;i++)
	{
		var elemento = document.forms[0].elements[i];
		if (elemento.type == "checkbox")
		{
		elemento.checked = chkbox.checked
		}
	}
} 
</script>

<form name='form1' action='autorizacion_stock.php' method='POST'>
<input type="hidden" value="<?=$cmd?>" name="cmd">
<input type="hidden" value="<?=$id_remito?>" name="id_remito">
<input type="hidden" value="<?=$id_efector?>" name="id_efector">
<input type="hidden" value="<?=$cuie?>" name="cuie">
<?echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";?>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
	    </td>
     </tr>
</table>
<?$result = sql($sql,"No se ejecuto en la consulta principal") or die;?>
<table width="98%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <tr id="mo">
    <td>
     <font size=+1><b>Autorizacion de Stock <?=$accion1?></b></font>    
    </td>
    <td width=40% align="center"><font size=+1><b>N&uacute;mero de Remito:&nbsp;&nbsp;<?echo $num_remito?></b></font></td>
    <td width=30% align="center"><font size=+1><b>Total: <?=$total_?>&nbsp;Productos</b></font></td>       
    <td width=40% align="right"><?=$link_pagina?></td>
    
 </tr>
			
<table width="98%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">	 
	</td></tr> 
	<?//tabla de comprobantes		
			
			if ($result->RecordCount()==0){?>
				 <tr>
				  <td align="center">
				   <font size="3" color="Red"><b>No existe documentacion para esta solicitud</b></font>
				  </td>
				</tr>
			<?}else{?>
				 	<tr id="sub_tabla">	
				 		<td align="right" id="mo"> <input type=checkbox name="seleccionar_todos" value="1" onclick="seleccionar(this)"> </td>
				 		<td align="right" id="mo"><b>Estado</b></td> 
				 	  <td align="right" id="mo">Codigo</td>
				 		<td align="right" id="mo">Descripcion</td>
				 		<td align="right" id="mo">Producto</td>
				 		<td align="right" id="mo">Cantidad</td>
				 		<td align="right" id="mo">Fecha Vencimiento</td>
            <td align="center" id="mo">Lote</td>
            <td align="right" id="mo">Codigo IR</td>
            <td align="right" id="mo">Fecha Aprobacion/Rechazo</td>
            <td align="right" id="mo">Usuario</td>
				 	</tr>
				 	<?while (!$result->EOF){
						   	$id_remito_remedio=$result->fields['id_remito_remedio'];
						   ?>
				 		<tr <?=atrib_tr()?> >				 
				 			<td align="center"> <input type=checkbox name="chk_producto[]" value="<?=$id_remito_remedio?>"> </td>		
				 			<td ><b><?if($result->fields['condicion']=='p')echo "PENDIENTE"; elseif(trim($result->fields['condicion'])=='a') echo "AUTORIZADO"; elseif(trim($result->fields['condicion'])=='n') echo "RECHAZADO"?></b></td>		
					 		<td ><?=$result->fields['codigo']?></td>
					 		<td ><?=$result->fields['descripcion']?></td>
					 		<td ><?=$result->fields['producto']?></td>
					 		<td align="center"><?=$result->fields['cantidad']?></td>
					 		<td align="center"><?=$result->fields['fecha_vencimiento']?></td>
              <td align="center"><?=$result->fields['lote']?></td>
              <td align="center"><?=$result->fields['codigo_ir']?></td>
              <td align="center"><?=$result->fields['fecha_condicion']?></td>
              <td align="center"><?=$result->fields['id_user_aut']?></td>
					 	</tr>	
					 <?$result->movenext();
				 	}// fin while
				} //fin del else?>	 	
	</td></tr>
 </table>
 
 <table width="98%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 
  <tr align="center">
    <td> 		                               
	<input type=submit name="autorizar" value="Autorizar" <?if ($cmd==2 or $cmd==3) echo "disabled"?> onclick="return confirm ('Autorizar Producto para Stock?')" title="Esta Seguro que desea Autorizar la entrada del Producto?" style="width=150px">     
	<input type=submit name="rechazar" value="Rechazar" <?if ($cmd==2 or $cmd==3) echo "disabled"?> onclick="return confirm ('Rechazar Producto?')" title="Desvincular" style="width=150px">     		
	<input type=submit name="pendiente" value="Pendiente" <?if ($cmd==2) echo "disabled"?> onclick="return confirm ('Colocar en Pendiente el Producto?')" title="Colocar en estado Pendiente para su revision" style="width=150px">  
	</td>
  </tr>
 
 </table>
 
 <table width="98%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 
  <tr align="center">
    <td> 	
		<input type=button name="volver" value="Volver" onclick="document.location='listado_remedio_remito.php'"title="Volver al Listado" style="width=150px">     
    </td>
  </tr>
 
 </table>
  
 </form>
 
 <?=fin_pagina();// aca termino ?>