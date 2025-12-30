<?php
require_once("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

function get_token() {
  // Datos de autenticación
  $usuario = 'prod_user_sumar';
  $pass = 'Sum_$r6iW5W84%(TD-';

  // URL de la API para obtener el token (ajusta según la API)
  //$url_token = 'http://201.222.57.88:8080/ERMConnectorServer/hcd/login';
  $url_token = 'http://192.168.10.37:8080/ERMConnectorServer/hcd/login';

  // Datos para enviar en la solicitud de token (ajusta según la API)
  $data_token = array(
    'userName' => $usuario,
    'password' => $pass
  );

  // Convertir los datos a formato JSON
  $json_data = json_encode($data_token);

  // Inicializar cURL para obtener el token
  $ch = curl_init();
  
  // Verificar si ocurrió algún error
  curl_setopt($ch, CURLOPT_URL, $url_token);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json'
  ));

  // Ejecutar la solicitud y obtener el token
  $resultado_token = curl_exec($ch);
  curl_close($ch);

  // Decodificar la respuesta JSON para obtener el token
  $token_data = json_decode($resultado_token, true);
  $token = $token_data['authToken']['token']; 
  return $token;
}


function get_benef($token, $benef_dni, $genero) {

  // URL de la API para obtener los datos con el token
  //$url_datos = 'http://201.222.57.88:8080/ERMConnectorServer/sumar/person/dni/'.$benef_dni.'/gender/'.$genero;
  $url_datos = 'http://192.168.10.37:8080/ERMConnectorServer/sumar/person/dni/'.$benef_dni.'/gender/'.$genero;
  
  // Inicializar cURL para obtener los datos
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url_datos);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Authorization: Bearer ' . $token
  ));

  // Ejecutar la solicitud y obtener los datos
  $resultado_datos = curl_exec($ch);
  curl_close($ch);
  
  // Decodificar la respuesta JSON para convertirlo en diccionario
  $datos = json_decode($resultado_datos, true);
  return $datos;

}

if ($_POST['importar_hc']=='Importar HC'){

  $hoy = date ("Y-m-d"); 
  $periodo=$_POST['periodo'];  
  $sql_benef = "SELECT a.dni
                from facturacion.resumen_hc a
                where a.tipo_benef <> 'na'
                and a.dni not ilike '%M%'
                and a.dni not ilike '%F%'
                and a.periodo = ".$periodo.
                " except
                select b.numero_doc
                from uad.beneficiarios b";
  
  $res_benef = sql ($sql_benef) or fin_pagina(); 
  
  $token = get_token();

  //preparamos para la creacion de clave de beneficiarios
  $sql_parametros="select * from uad.parametros";
  $result_parametros=sql($sql_parametros) or fin_pagina();
  $codigo_provincia=$result_parametros->fields['codigo_provincia'];
  $codigo_ci=$result_parametros->fields['codigo_ci'];   
  $codigo_uad=$result_parametros->fields['codigo_uad']; 
    
  ?>
  
  <br>
  <br>
  <br>
  <table width="97%" class="bordes" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
  <tr><td>
  
  <tr id="mo" align="center">
  <td colspan="6" align="center">
  <font size=+1><b>Migracion de Beneficiarios Hc-Sigep</b></font>           
  </td>
  </tr>
  
  <table class="table table-striped" border=1 bordercolor=#E0E0E0>
  <thead>
    <tr>
      <th scope="col">Clave Benef.</th>
      <th scope="col">DNI</th>
      <th scope="col">Nombre</th>
      <th scope="col">Apellido</th>
      <th scope="col">Genero</th>
      <th scope="col">F.Nac.</th>
      <th scope="col">Cuie</th>
      <th scope="col">Efector</th>
      <th scope="col">Calle</th>
      <th scope="col">N° Calle</th>
      <th scope="col">Piso</th>
      <th scope="col">Departamento</th>
      <th scope="col">Cod_postal</th>
      <th scope="col">Barrio</th>
      <th scope="col">Monoblock</th>
      <th scope="col">Ciudad</th>
      <th scope="col">Municipio</th>
      <th scope="col">Provicia</th>
      <th scope="col">Pais</th>      
    </tr>
  </thead>

  <?
  $total_registros=$res_benef->recordcount();
    
  while (!$res_benef->EOF) {

    $dni = trim($res_benef->fields['dni']);
    $datos = get_benef ($token, $dni, 'M');
        
    if (array_key_exists('status',$datos)
      and $datos['status']=='409')
      $datos = get_benef ($token, $dni, 'F');
    
    $apellido = str_replace("'",'',$datos['apellido']);
    $nombres = str_replace("'",'',$datos['nombres']);
    $fecha_nacimiento = $datos['fechaNacimiento'];
    $calle = str_replace("'",'',$datos['calle']);
    $numero = $datos['numero'];
    $piso = $datos['piso'];
    $departamento = $datos['departamento'];
    $cpostal = ($datos['cpostal'] and is_numeric($datos['cpostal']))?$datos['cpostal']:0;
    $barrio = $datos['barrio'];
    $monoblock = $datos['monoblock'];
    $ciudad = $datos['ciudad'];
    $municipio = $datos['municipio'];
    $provincia = $datos['provincia'];
    $pais = $datos['pais'];
    $genero = $datos['genero'];
    $numero_documento = $datos['numeroDocumento'];

    if ($numero_documento!='') {
      
      //creamos la clave de beneficiarios
      $q="select nextval('uad.beneficiarios_id_beneficiarios_seq') as id_planilla";
      $id_planilla=sql($q) or fin_pagina();
      $id_planilla=$id_planilla->fields['id_planilla'];    
      $id_planilla_clave= str_pad($id_planilla, 6, '0', STR_PAD_LEFT);    
      $clave_beneficiario=$codigo_provincia.$codigo_uad.$codigo_ci.$id_planilla_clave;

      //buscamos el cuie para la inscripcion
      $sql_cuie = "SELECT * FROM  facturacion.resumen_hc a
                  WHERE a.periodo = $periodo
                  AND a.dni = '$numero_documento'";

      $res_cuie = sql ($sql_cuie) or fin_pagina();
      if ($res_cuie->recordCount()>0) $cuie = $res_cuie->fields['cuie'];
        else $cuie = 'S/C';

      //sacamos la categoria
      if ($res_cuie->recordCount()>0) {
        switch ($res_cuie->fields['tipo_control']) {
          case 'Adultos de 20 a 64' : $categoria = 6; break;
          case 'Adolescentes' : $categoria = 7; break;
          case 'Niños de 6 a 9' : $categoria = 4; break;
          case 'Niños de 0 a 5' : $categoria = 4; break;
          default : $categoria = 0; break;
        };
      };
      
      
      
      $insert_benef = "INSERT INTO 
                uad.beneficiarios_hc
                ( clave_beneficiario,
                  apellido,
                  nombres,
                  fecha_nac,
                  calle,
                  numero,
                  piso,
                  departamento,
                  codigo_postal,
                  barrio,
                  monoblock,
                  ciudad,
                  municipio,
                  provincia,
                  pais,
                  genero,
                  numero_doc,
                  procesado,
                  fecha_mod,
                  periodo,
                  cuie,
                  categoria
                )
                VALUES (
                  '$clave_beneficiario',
                  '$apellido',
                  '$nombres',
                  '$fecha_nacimiento',
                  '$calle',
                  '$numero',
                  '$piso',
                  '$departamento',
                   $cpostal,
                  '$barrio',
                  '$monoblock',
                  '$ciudad',
                  '$municipio',
                  '$provincia',
                  '$pais',
                  '$genero',
                  '$numero_documento',
                  null,
                  '$hoy',
                  $periodo,
                  '$cuie',
                  $categoria)";

          sql($insert_benef) or fin_pagina(); 

      ?>
    
      <tbody>
      <tr>
        <td><?php echo $clave_beneficiario?></td>
        <td><?php echo $datos['numeroDocumento']?></td>
        <td><?php echo $datos['nombres']?></td>
        <td><?php echo $datos['apellido']?></td>
        <td><?php echo $datos['genero']?></td>
        <td><?php echo $datos['fechaNacimiento']?></td>
        <td><?php echo $cuie?></td>
        <td><?php echo $datos['efector']?></td>
        <td><?php echo $datos['calle']?></td>
        <td><?php echo $datos['numero']?></td>
        <td><?php echo $datos['piso']?></td>
        <td><?php echo $datos['departamento']?></td>
        <td><?php echo $datos['cpostal']?></td>
        <td><?php echo $datos['barrio']?></td>
        <td><?php echo $datos['monoblock']?></td>
        <td><?php echo $datos['ciudad']?></td>
        <td><?php echo $datos['municipio']?></td>
        <td><?php echo $datos['provincia']?></td>
        <td><?php echo $datos['pais']?></td>
      </tr>
    <?php }//del IF
  
     $res_benef->MoveNext();
  }
  //del while ?>
    
 </tbody>
 </table>   
  <br>
  <br>
  <table class="table table-bordered">
  <tr id="mo" align="center">
  <td colspan="6" align="center">
  <b>Resumen</b>           
  </td>
  </tr>

  <tr><td>Cantidad de Reg. a Procesar: </td><td><?php echo '<b>'.$total_registros.'</b>'?></td></tr>
  </table>
</table>

<?} //del POST 

echo $html_header;
?>

<form name=form1 action="migracion_hc_beneficiarios.php" method=POST enctype="multipart/form-data">
<table width="80%" class="bordes" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?php echo $bgcolor_out?>' class="bordes">
<tr><td>
  <?php if (!$_POST['importar_hc']){?>
  <table width=100% align="center" class="bordes">
  
  <tr id="mo" align="center">
    <td colspan="6" align="center">
      <font size=+1><b>Migracion de Historia Clinica</b></font>             
    </td>
   </tr>
    
     
  <tr>             
  <td align="center" colspan="6" id="ma">
  <b> Migracion de HC a Sumar</b>
  </td>
  </tr>
  
  <tr>
  <td>
  <b>Periodo: </b>
  <input name="periodo" type="numeric" id='periodo' placeholder='AAAAMM'>
  </td>
  <td>
  <input type=submit name="importar_hc" value='Importar HC' class="btn btn-primary"  <?php echo $disable;?>>
  </td>
  </table>
  <?php }?>    
</table>
</form>
</body>
</html>
<?php echo fin_pagina();// aca termino ?>
