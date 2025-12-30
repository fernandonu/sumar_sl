<?php

require_once ("../../config.php");


extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);


$color1="#5090C0";
  $color2="#D5D5D5";
  $ret = "";
    

$sql_ingreso= "SELECT * 
FROM
  proteger.ingreso  
inner join nacer.efe_conv using (cuie)
WHERE  cuie = '$cuie'";
$res_ingreso=sql($sql_ingreso) or fin_pagina();

$sql_egreso= "SELECT * 
FROM
   proteger.egreso
inner join nacer.efe_conv using (cuie)
inner join proteger.inciso using (id_inciso)
WHERE  cuie = '$cuie'";
$res_egreso=sql($sql_egreso) or fin_pagina();


$sql= "SELECT ingre,
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
  
$res_factura=sql($sql,"no se puede ejecutar");
$res_factura->movefirst();

$nombre_efector=$res_ingreso->fields['nombre'];
$referente=$res_ingreso->fields['referente'];

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
$ret .= "<b>Plan Nacer, $dia_hoy</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1' align='left'>\n";
$ret .= "<td align='left'>\n";
$ret .= "<b>Efector: $nombre_efector. CUIE: $cuie.</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1' align='left'>\n";
$ret .= "<td align='left'>\n";
$ret .= "<b>Referente: $referente.</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "<b>Por medio de la presente le notifico que se encuentra a disposición del prestador que usted representa
la suma de $ ".number_format($res_factura->fields['ingre'],2,',','.').".</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "</table><br>\n";


$total_depositado=number_format($res_factura->fields['ingre'],2,',','.');
$total_egre_comp_no_pago=$res_factura->fields['egre_comp']-$res_saldo->fields['egre'];
$total_egre_comp=number_format($res_factura->fields['egre_comp'],2,',','.');
$total_egre_comp_no_pago=number_format($total_egre_comp_no_pago,2,',','.');

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
$ret .= "<b>Su Saldo Acumulado es de: $ $total_depositado. </b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "<b>Su Saldo Comprometido es de: $ $total_egre_comp_no_pago.</b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "<b>Su Saldo Real es de: $ $saldo_real. </b>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "</td>\n";
$ret .= "</tr>\n";
$ret .= "<tr bgcolor='$color1'>\n";
$ret .= "<td align='justify'>\n";
$ret .= "<font color=white><b>Se ruega contestar este mail al mail Oficial Progama PROTEGER, para ser tenido en cuenta como acuse de recibo.</b></font>\n";
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


excel_header("ingreso_egreso.xls");

?>
<form name=form1 method=post action="notificacion_excel2.php">
<?echo $ret;?>
 
 </form>