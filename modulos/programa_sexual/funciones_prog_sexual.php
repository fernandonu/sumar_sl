<?

function valida_producto($datos){
   
  $entidad_alta=$datos['entidad_alta'];
  $id=$datos['ID'];
  $fecha_comprobante=$datos['fecha_control'];
  $id_remedio=$datos['id_remedio'];
  
  //nuevo calculo para poder detectar las practicas facturadas mas de las permitidas
  //el calculo anterior toma periodos correlativos, el orden inverso de entradas de comprobantes no detecta la cantidad real
    $dia_hoy=date("Y-m-d");


  //asigno variables para usar la validacion
  $query="SELECT * from programa_sexual.validacion_producto
      where id_nomenclador=$id_remedio";              
  $res=sql($query, "Error 1") or fin_pagina(); 
    
  if ($res->RecordCount()>0){//me fijo si hay que validar (si tiene regla)
      
    //cantidad de prestaciones limites
    $cant_pres_lim=$res->fields['cant_pres_lim'];
    $per_pres_limite=$res->fields['per_pres_limite'];
    
    //cuenta la cantidad de prestaciones de un determinado filiado, de un determinado codigo y 
    //en un periodo de tiempo parametrizado.
  if ($entidad_alta=='na')
  
          $query="SELECT id_prestacion, codigo, fecha_entrega
                FROM nacer.smiafiliados
                INNER JOIN programa_sexual.comprobantes using (id_smiafiliados)
                INNER JOIN programa_sexual.prestaciones using (id_comprobante)
                INNER JOIN programa_sexual.remedio using (id_remedio)
                where smiafiliados.id_smiafiliados=$id and 
                programa_sexual.remedio.id_remedio=$id_remedio and
                fecha_entrega between ('$fecha_comprobante'::date - $per_pres_limite) and '$fecha_comprobante'::date";
    
    else  $query="SELECT id_prestacion, codigo, fecha_entrega
                FROM leche.beneficiarios
                INNER JOIN programa_sexual.comprobantes using (id_beneficiarios)
                INNER JOIN programa_sexual.prestaciones using (id_comprobante)
                INNER JOIN programa_sexual.remedio using (id_remedio)
                where leche.beneficiarios.id_beneficiarios=$id and 
                programa_sexual.remedio.id_remedio=$id_remedio and
                fecha_entrega between ('$fecha_comprobante'::date - $per_pres_limite) and '$fecha_comprobante'::date";
  
  
  $cant_pres=sql($query, "Error 3") or fin_pagina();
  
      
      if ($cant_pres->RecordCount()>=$cant_pres_lim){
        $accion=$res->fields['msg_error'];
       
        echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
        return 0;
      }
      else return 1;
  }
  else return 1;
}


?>