<?php

require_once("../../config.php");
$fecha_hasta=$parametros["fecha_hasta"];

if ($fecha_hasta=='aaaa-mm-dd') {$sql_ingre=''; 
                                  $sql_egre='';}
  else {$sql_egre=" and egreso.fecha_egreso <= '$fecha_hasta'";
        $sql_ingre=" and ingreso.fecha_deposito <= '$fecha_hasta'";
    }

$sql_tmp="SELECT 
  nacer.efe_conv.id_efe_conv,
  nacer.efe_conv.nombre,
  nacer.efe_conv.domicilio,
  nacer.efe_conv.cuidad,
  nacer.efe_conv.cuie
FROM
  nacer.efe_conv WHERE efe_conv.proteger='S'";
 
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
    <td align="center">CUIE</td>       
    <td align="center">Nombre</td>
    <td align="center">Cuidad</td>        
    <td align="center">Ingreso</td>        
    <td align="center">Egreso</td>        
    <td align="center">Saldo</td> 
    <td align="center">Monto Comprometido</td>
    <td align="center">Saldo Real</td> 
    <!--<td align="center">Preventivo</td>
    <td align="center">Saldo Virtual</td>-->
        
  </tr>
 <?
   while (!$result->EOF) {
    $cuie=$result->fields['cuie'];
      $sql="SELECT monto_egreso from proteger.egreso
    where cuie='$cuie'";
    $res_egreso=sql($sql,"no puede calcular el saldo");
    
  if ($res_egreso->recordCount()==0){
    $sql="select ingre as total_real, ingre,egre,egre_comp from
      (select sum (monto_recurso) as ingre from proteger.ingreso
      where cuie='$cuie'".$sql_ingre.") as ingreso,
      (select sum (monto_egreso)as egre from proteger.egreso
      where cuie='$cuie' and monto_comprometido <> 0".$sql_egre.") as egreso,
      (select sum (monto_comprometido)as egre_comp from proteger.egreso
      where cuie='$cuie'".$sql_egre.") as egre_comp";

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
        where cuie='$cuie'".$sql_ingre.") as ingreso,
        (select sum (monto_egreso)as egre from proteger.egreso
        where cuie='$cuie' and pagado='si'".$sql_egre.") as egreso,
        (select sum (monto_comprometido) as egre_comp from proteger.egreso
        where cuie='$cuie' and monto_egreso=0 and pagado='no') as egre_comp,
        (select sum (monto_preventivo) as monto_preventivo from proteger.egreso
        where cuie='$cuie' and pagado='no') as preventivo";
    }
    $res_saldo=sql($sql,"no puede calcular el saldo");

    $total_depositado=$res_saldo->fields['ingre'];//lo uso en ecuacion mas adelante
        
    ?>
    
    
    
  <tr <?=atrib_tr()?>>        
   <td bgcolor='<?=$color_fondo?>'><?=$result->fields['cuie']?></td>
   <td bgcolor='<?=$color_fondo?>'><?=$result->fields['nombre']?></td>
   <td bgcolor='<?=$color_fondo?>'><?=$result->fields['cuidad']?></td> 
   <td bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['ingre'],2,',','.')?></td>
   <td bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['egre'],2,',','.')?></td> 
   <td bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['total_real'],2,',','.')?></td>
   <td align='center' bgcolor='<?=$color_fondo?>'><?=number_format($res_saldo->fields['egre_comp'],2,',','.')?></td>
   <?if ((($total_depositado-$res_saldo->fields['egre']-($res_saldo->fields['egre_comp']-$res_saldo->fields['egre'])))<0)$color_fondo1="#BE81F7";
    else $color_fondo1="";?>
   <td bgcolor='<?=$color_fondo1?>'><?=number_format(($res_saldo->fields['total_real']-$res_saldo->fields['egre_comp']),2,',','.')?></td>  
   <!--<td bgcolor='<?=$color_fondo1?>'><?=number_format($res_saldo->fields['monto_preventivo'],2,',','.')?></td> 
   <td bgcolor='<?=$color_fondo1?>'><?=number_format($res_saldo->fields['total_real']-$res_saldo->fields['egre_comp']-$res_saldo->fields['monto_preventivo'],2,',','.')?></td>         -->
    </tr>
  <?$result->MoveNext();
    }?>
 
</table>
</td>
</body>
</html>
<?echo fin_pagina();// aca termino ?>