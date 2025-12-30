<?php

require_once("../../config.php");
$fecha_hasta=$parametros["fecha_hasta"];

if ($fecha_hasta=='aaaa-mm-dd') {$sql_ingre=''; 
                                  $sql_egre='';}
  else {$sql_egre=" and egreso.fecha_deposito <= '$fecha_hasta'";
        $sql_ingre=" and ingreso.fecha_deposito <= '$fecha_hasta'";
    }

$sql_tmp="SELECT 
  nacer.efe_conv.id_efe_conv,
  nacer.efe_conv.nombre,
  nacer.efe_conv.domicilio,
  nacer.efe_conv.cuidad,
  nacer.efe_conv.cuie
FROM
  nacer.efe_conv WHERE efe_conv.conv_sumar='t'";
 
 $result=sql($sql_tmp) or fin_pagina(); 
 $total_muletos=$result->recordCount();

excel_header("ingre_egre_listado_excel.xls");
?>

<table border=0 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
  <tr>
    <td colspan=11 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       </tr>
    </table>
   </td>
  </tr>
  

  <tr>
    <td align=right>CUIE</td>       
    <td align=right>Nombre</td>
    <td align=right>Domicilio</td>    
    <td align=right>Cuidad</td>        
    <td align=right>Ingreso</td>        
    <td align=right>Egreso</td>        
    <td align=right>Saldo</td> 
    <td align=right>Egreso Comprometido</td>
    <td align=right>Saldo Real</td> 
        
  </tr>
 <?
   while (!$result->EOF) {
    $cuie=$result->fields['cuie'];
      $sql="select monto_egreso from contabilidad.egreso
    where cuie='$cuie'";
    $res_egreso=sql($sql,"no puede calcular el saldo");
    
  if ($res_egreso->recordCount()==0){
    $sql="select ingre as total, ingre,egre,deve,egre_comp from
      (select case when (sum (monto_deposito) is null) then 0 else sum (monto_deposito) end as ingre from contabilidad.ingreso
      where cuie='$cuie'".$sql_ingre.") as ingreso,
      (select case when (sum (monto_egreso) is null) then 0 else sum (monto_egreso) end as egre from contabilidad.egreso
      where cuie='$cuie' and monto_egre_comp <> 0".$sql_egre.") as egreso,
      (select case when (sum (monto_factura) is null) then 0 else sum (monto_factura) end as deve from contabilidad.ingreso
      where cuie='$cuie'".$sql_ingre.") as devengado,
      (select case when (sum (monto_egre_comp) is null) then 0 else sum (monto_egre_comp) end as egre_comp from contabilidad.egreso
      where cuie='$cuie'".$sql_egre.") as egre_comp";

    }
  else{
    $sql="select ingre-egre as total, ingre,egre,deve,egre_comp from
        (select case when (sum (monto_deposito) is null) then 0 else sum (monto_deposito) end as ingre from contabilidad.ingreso
        where cuie='$cuie'".$sql_ingre.") as ingreso,
        (select case when (sum (monto_egreso) is null) then 0 else sum (monto_egreso) end as egre from contabilidad.egreso
        where cuie='$cuie' and monto_egre_comp <> 0".$sql_egre.") as egreso,
        (select case when (sum (monto_factura) is null) then 0 else sum (monto_factura) end as deve from contabilidad.ingreso
        where cuie='$cuie'".$sql_ingre.") as devengado,
        (select case when (sum (monto_egre_comp) is null) then 0 else sum (monto_egre_comp) end as egre_comp from contabilidad.egreso
        where cuie='$cuie'".$sql_egre.") as egre_comp";
    }
    $res_saldo=sql($sql,"no puede calcular el saldo");

    $total_depositado=$res_saldo->fields['ingre'];//lo uso en ecuacion mas adelante
    
    if ($res_color->fields['monto_factura']==$res_color->fields['monto_deposito'])
      $color_fondo="";
    else if (($res_color->fields['monto_factura']>$res_color->fields['monto_deposito'])and (($res_color->fields['dias_demora'])>30))
      $color_fondo="#FF9999";
    else
      $color_fondo="#FFFFCC";     
    ?>
    
    
    
  <tr <?=atrib_tr()?>>        
   <td bgcolor='<?=$color_fondo?>'><?=$result->fields['cuie']?></td>
   <td bgcolor='<?=$color_fondo?>'><?=$result->fields['nombre']?></td>
   <td bgcolor='<?=$color_fondo?>'><?=$result->fields['domicilio']?></td>     
   <td bgcolor='<?=$color_fondo?>'><?=$result->fields['cuidad']?></td> 
   <td bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['ingre'],2,',','.')?></td>
   <td bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['egre'],2,',','.')?></td> 
   <td bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['total'],2,',','.')?></td>
   <td align='center' bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['egre_comp'],2,',','.')?></td>
   <?if ((($total_depositado-$res_saldo->fields['egre']-($res_saldo->fields['egre_comp']-$res_saldo->fields['egre'])))<0)$color_fondo1="#BE81F7";
    else $color_fondo1="";?>
   <td bgcolor='<?=$color_fondo1?>'><?=number_format($total_depositado-$res_saldo->fields['egre']-($res_saldo->fields['egre_comp']-$res_saldo->fields['egre']),2,',','.')?></td>          
    </tr>
  <?$result->MoveNext();
    }?>
 
</table>
</td>
</body>
</html>
<?echo fin_pagina();// aca termino ?>