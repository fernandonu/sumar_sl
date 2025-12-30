<?php
require_once("../../config.php");

variables_form_busqueda("ins_listado_old");
cargar_calendario();

$usuario1=$_ses_user['id'];
$user_name=$_ses_user['name'];

if ($_POST['generarnino']=='Generar'){
        $fechaemp=Fecha_db($_POST['fechaemp']);
		$fechakrga=Fecha_db($_POST['fechakrga']);
		$link=encode_link("ins_listado_remediar_xls.php",array("fechaemp"=>$fechaemp, "fechakrga"=>$fechakrga));?>
	<script>
	window.open('<?php echo $link?>')
	</script>	
<?}

$sql_aux = "SELECT * 
            FROM uad.beneficiarios ";


/*$sql_tmp="SELECT 
			beneficiarios.id_beneficiarios, 
			remediar_x_beneficiario.id_r_x_b,
			beneficiarios.clave_beneficiario,
			beneficiarios.numero_doc,
			beneficiarios.apellido_benef, 
			beneficiarios.nombre_benef, 
			beneficiarios.fecha_nacimiento_benef, 
			beneficiarios.sexo, 
			beneficiarios.estado_envio, 
			remediar_x_beneficiario.nroformulario,
			remediar_x_beneficiario.fechaempadronamiento,
			remediar_x_beneficiario.fecha_carga,
			remediar_x_beneficiario.enviado,
      formulario.puntaje_final,          
      formulario.hta2,        
      formulario.colesterol4,
      clasificacion_remediar2.clasif_rapida,
      clasificacion_remediar2.col_tot 					
			FROM uad.remediar_x_beneficiario
			inner join uad.beneficiarios ON (remediar_x_beneficiario.clavebeneficiario=beneficiarios.clave_beneficiario)
      inner join remediar.formulario ON (remediar_x_beneficiario.nroformulario=formulario.nroformulario) 
      left  join trazadoras.clasificacion_remediar2 ON (remediar_x_beneficiario.clavebeneficiario=clasificacion_remediar2.clave_beneficiario)";*/

    $sql_tmp = "SELECT 
    a.id_beneficiarios, 
    b.id_r_x_b,
    a.clave_beneficiario,
    a.numero_doc,
    a.apellido_benef, 
    a.nombre_benef, 
    a.fecha_nacimiento_benef, 
    a.sexo, 
    a.estado_envio, 
    b.nroformulario,
    b.fechaempadronamiento,
    b.fecha_carga,
    b.enviado,
    c.puntaje_final,          
    c.hta2,        
    c.colesterol4,
    d.clasif_rapida,
    d.col_tot 					
FROM uad.beneficiarios a ";


echo $html_header;?>
<script>
function control_busqueda() {
  
  if(document.all.tex_buscar.value==""){
  alert('Debe Ingresar un valor de busqueda');
  document.all.tex_buscar.focus();
  return false;
 } 
}

function control_muestra()
{ 
 if(document.all.fechaemp.value==""){
  alert('Debe Ingresar una Fecha DESDE');
  return false;
 } 
 if(document.all.fechakrga.value==""){
  alert('Debe Ingresar una Fecha HASTA');
  return false;
 } 

 alert('Se genera un Excel Completo. Toma como Filtro "Fecha de Empadronamiento" "Fecha de Clasificacion" y "Fecha de Seguimiento"');

return true;
}
</script>
<form name=form1 action="ins_listado_remediar.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		  &nbsp;&nbsp;<b>Buscar: </b><input type=text name="tex_buscar">
      &nbsp;&nbsp;<input type=submit class='btn btn-primary' name="buscar" value='Buscar' onclick="return control_busqueda();">
	    &nbsp;&nbsp;<input type='button' class='btn btn-secondary' name="Auditoriaemp" onclick="window.open('../remediar/auditoriaemp.php','AuditoriaEmpadronamiento','dependent:yes,width=900,height=700,top=1,left=60,scrollbars=yes');" value="Auditoria de Empadronamiento" disabled/>
	    &nbsp;&nbsp;<input type='button' class='btn btn-warning' name="auditoria" onclick="window.open('../remediar/auditoria.php','Auditoria','dependent:yes,width=900,height=700,top=1,left=60,scrollbars=yes');" value="Auditoria"/>
	</td>	
	</tr>
	<tr><td>
  &nbsp;&nbsp;
  &nbsp;&nbsp;
  &nbsp;&nbsp;
  </td></tr>

  <tr>
  <td align=center>       
	   &nbsp;&nbsp;<b>Fecha desde: </b>
		<input type=text id="fechaemp" name="fechaemp" size=11 maxlength="11" onchange="esFechaValida(this);"> <?php echo link_calendario('fechaemp');?>
		&nbsp;&nbsp;<b>Fecha hasta: </b>
		<input type=text id="fechakrga" name="fechakrga"  size=11 maxlength="11" onchange="esFechaValida(this);"> <?php echo link_calendario('fechakrga');?>		
		<? if(1==1){
				$permiso_genera_archivo_remediar="";
			}
			else {
				$permiso_genera_archivo_remediar="disabled";
			}?>
	    &nbsp;&nbsp;<input type=submit name="generarnino" class='btn btn-success' value='Generar' <?php echo $permiso_genera_archivo_remediar?> onclick="return control_muestra();">
	  </td>
     </tr>     
    <tr>
      &nbsp;&nbsp;
</table>

<table border=0 width=100% cellspacing=2 cellpadding=2 bgcolor='<?php echo $bgcolor3?>' align=center>
  <tr>
  	<td colspan=11 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left><b>Resultado:</b>       
      </tr>
    </table>
   </td>
  </tr>
  

  <tr>
  	<td align=right id=mo>Clave Beneficiario</td>    	    
    <td align=right id=mo><a id=mo href='<?php echo encode_link("ins_listado_remediar.php",array("sort"=>"1","up"=>$up))?>'>Documento</a></td>      	    
    <td align=right id=mo>Apellido</a></td>      	    
    <td align=right id=mo>Nombre</a></td>
    <td align=right id=mo>F NAC</td>
    <td align=right id=mo>Formulario</td>
    <td align=right id=mo>F Emp R+R</td>
    <td align=right id=mo>F Carga R+R</td>
    <td align=right id=mo>Score</td>
    <td align="right" id="mo">Clasif.</td>     	    
    <td align="right" id="mo">Seg.</td>     	    

  </tr>
 <?
  if ($_POST['buscar']) {
      $where_var = $_POST['tex_buscar'];
      $where = "WHERE numero_doc ILIKE '%".$where_var."%'"." OR apellido_benef ILIKE '%".$where_var."%'";
      $sql0=$sql_aux.$where;
      $result = sql($sql0) or fin_pagina();
      $clave_beneficiario = $result->fields['clave_beneficiario'];
      
      $where2 = "INNER JOIN (select * from uad.remediar_x_beneficiario where id_r_x_b =  (select max(id_r_x_b) from uad.remediar_x_beneficiario where clavebeneficiario = '".$clave_beneficiario."') ) b ON a.clave_beneficiario = b.clavebeneficiario
            INNER JOIN (select * from remediar.formulario where id_formulario=(select max(id_formulario) from remediar.formulario where nroformulario='".$clave_beneficiario."')) c ON (b.nroformulario=c.nroformulario) 
            LEFT JOIN trazadoras.clasificacion_remediar2 d ON (b.clavebeneficiario=d.clave_beneficiario)";
            
      $sql1=$sql_tmp.$where2;
      $result = sql($sql1) or fin_pagina();
      if ($result->RecordCount() == 0) {
        echo "<tr><td align=center colspan=11><b><h4>No se encontraron registros</h4></b></td></tr>";
      } else {
      while (!$result->EOF) {
        $estado_envio=$result->fields['estado_envio'];
        $clave_beneficiario=$result->fields['clave_beneficiario'];
        $sexo=$result->fields['sexo'];
        $fecha_nac=$result->fields['fecha_nacimiento_benef'];
        $clasif_rapida=$result->fields['clasif_rapida'];
        $col_tot=$result->fields['col_tot'];
        $ref = encode_link("../remediar/remediar_admin.php",array("estado_envio"=>$estado_envio,"clave_beneficiario"=>$clave_beneficiario,"sexo"=>$sexo,"fecha_nac"=>$fecha_nac,"vremediar"=>"s","pagina_viene_1"=>"ins_listado_remediar.php"));  	
        $onclick_elegir="location.href='$ref'";?>
      
        <tr <?php echo atrib_tr()?>>     
         <td onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['clave_beneficiario']?></td>
         <td onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['numero_doc']?></td>        
         <td onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['apellido_benef']?></td>     
         <td onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['nombre_benef']?></td>     
         <td onclick="<?php echo $onclick_elegir?>"><?php echo fecha($result->fields['fecha_nacimiento_benef'])?></td>
         <td onclick="<?php echo $onclick_elegir?>"><?php echo $result->fields['nroformulario']?></td>     
         <td onclick="<?php echo $onclick_elegir?>"><?php echo fecha($result->fields['fechaempadronamiento'])?></td>
         <td onclick="<?php echo $onclick_elegir?>"><?php echo fecha($result->fields['fecha_carga'])?></td>

         <?php if ($result->fields['puntaje_final']==0 and $result->fields['hta2']=='-1' and $result->fields['colesterol4']=='-1') $puntaje_final="";
         else $puntaje_final=$result->fields['puntaje_final']?>
         <td onclick="<?php echo $onclick_elegir?>"><?php echo $puntaje_final?></td>

         <td align="center">
           	 <?$ref = encode_link("../trazadoras/remediar_carga.php",array("clave_beneficiario"=>$result->fields['clave_beneficiario'],"pagina"=>'ins_listado_remediar.php'));
           	 
           	   echo "<a href='#' title='Clasificacion' onclick=window.open('".$ref."','Clasificacion','menubar=1,resizable=1,scrollbars=1,width=1200,height=750')><IMG src='$html_root/imagenes/flech.png' height='20' width='20' border='0'></a> ";?>
         </td>  
        
          <td align="center"> 
          <?php $ref = encode_link("../trazadoras/seguimiento_admin.php",array("clave_beneficiario"=>$result->fields['clave_beneficiario'],"pagina"=>'ins_listado_remediar.php'));
          if ($clasif_rapida!='s' and $col_tot!='') {
            echo "<a href='#' title='Seguimiento' onclick=window.open('".$ref."','Seguimiento','menubar=1,resizable=1,scrollbars=1,width=1390,height=750')><IMG src='$html_root/imagenes/flech.png' height='20' width='20' border='0'></a>";
          };?> 
          </td>
        
       </tr>
    	<?$result->MoveNext();
        }
      }
    }?>
    
</table>
</form>
</body>
</html>
<?php echo fin_pagina();// aca termino ?>
