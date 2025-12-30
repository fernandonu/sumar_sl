<?/*
$Author: Seba $
$Revision: 3.0 $
$Date: 2016/12/28 $
*/

require_once ("../../config.php");?>
<script>
            $("#grupo").change(function () {
            grupo=$(this).val();
            cuie=$("#cuie").val();
            
            console.log(cuie);
            console.log(grupo);
            
            fecha_desde=$("#fecha_desde").val();
            fecha_hasta=$("#fecha_hasta").val();
            dia = fecha_desde[0]+fecha_desde[1];
            mes = fecha_desde[3]+fecha_desde[4];
            anio = fecha_desde[6]+fecha_desde[7]+fecha_desde[8]+fecha_desde[9];
            fecha_desde = anio+'-'+mes+'-'+dia;
            
            dia = fecha_hasta[0]+fecha_hasta[1];
            mes = fecha_hasta[3]+fecha_hasta[4];
            anio = fecha_hasta[6]+fecha_hasta[7]+fecha_hasta[8]+fecha_hasta[9];
            fecha_hasta = anio+'-'+mes+'-'+dia;
            
            op='practica';
            
            $.ajax({
                data: {op: op, cuie: cuie, fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, grupo: grupo},
                type: "POST",
                dataType: "text",
                url: "datos_auditoria.php",
              })
          .done(function( data, textStatus, jqXHR ) {
              if ( console && console.log ) {
                   console.log( "La solicitud se ha completado correctamente.");
                   $("#practicas_pres-div").html(data);
                   //console.log(data);
              }
          })//done
          .fail(function( jqXHR, textStatus, errorThrown ) {
              if ( console && console.log ) {
                  console.log( "La solicitud a fallado: " +  textStatus);
              }
          });//fail
        });//change
    
</script>
<? 

$op=$_POST['op'];
$cuie=$_POST['cuie'];
$fecha_desde=$_POST['fecha_desde'];
$fecha_hasta=$_POST['fecha_hasta'];

$sql_nom_detalle="SELECT * from facturacion.nomenclador_detalle where '$fecha_desde' between fecha_desde and fecha_hasta";
$res_nom_detalle=sql($sql_nom_detalle) or fin_pagina();
$id_nomenclador_detalle=$res_nom_detalle->fields['id_nomenclador_detalle'];

if ($op=='grupo') {
    if ($cuie=='todos') {
      $query="SELECT grupo
                from facturacion.prestacion 
                inner join facturacion.comprobante using (id_comprobante)
                inner join facturacion.nomenclador using (id_nomenclador)
                where fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
                and comprobante.marca=0
                and id_nomenclador_detalle=$id_nomenclador_detalle
                group by 1
                order by 1";
                }

      else {
        $query="SELECT grupo
                from facturacion.prestacion 
                inner join facturacion.comprobante using (id_comprobante)
                inner join facturacion.nomenclador using (id_nomenclador)
                where fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
                and comprobante.marca=0
                and comprobante.cuie='$cuie'
                and id_nomenclador_detalle=$id_nomenclador_detalle
                group by 1
                order by 1";
      };
        
$res_query = sql($query) or fin_pagina();?>

            
            <label for="grupo">Grupo Prest.: </label>
            <select name="grupo" id="grupo">
              <option value="-1">seleccione</option>
              <?while (!$res_query->EOF){?>
                <option value="<?=$res_query->fields['grupo']?>"><?=$res_query->fields['grupo']?></option>
                <?$res_query->Movenext();
              }?>
            </select>
    <?};//del if
    


if ($op=='practica') {
    $grupo=$_POST['grupo'];

    /*$sql_nom_detalle="SELECT * from facturacion.nomenclador_detalle where '$fecha_desde' between fecha_desde and fecha_hasta";
    $res_nom_detalle=sql($sql_nom_detalle) or fin_pagina();
    $id_nomenclador_detalle=$res_nom_detalle->fields['id_nomenclador_detalle'];*/

    if ($cuie=='todos') {
      $query="SELECT id_nomenclador,codigo,descripcion
                from facturacion.prestacion 
                inner join facturacion.comprobante using (id_comprobante)
                inner join facturacion.nomenclador using (id_nomenclador)
                where fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
                and grupo='$grupo'
                and comprobante.marca=0
                and id_nomenclador_detalle=$id_nomenclador_detalle
                group by 1,2,3
                order by 2,3";
                }

      else {
        $query="SELECT id_nomenclador,codigo,descripcion
                from facturacion.prestacion 
                inner join facturacion.comprobante using (id_comprobante)
                inner join facturacion.nomenclador using (id_nomenclador)
                where fecha_comprobante between '$fecha_desde' and '$fecha_hasta'
                and grupo='$grupo'
                and comprobante.cuie='$cuie'
                and comprobante.marca=0
                and id_nomenclador_detalle=$id_nomenclador_detalle
                group by 1,2,3
                order by 2,3";
      };
        
$res_query_2 = sql($query) or fin_pagina();?>
            <label for="practica">Practica: </label>
            <select name="practica" id="practica">
              <option value="-1">seleccione</option>
              <?while (!$res_query_2->EOF){?>
                <option value="<?=$res_query_2->fields['id_nomenclador']?>"><?=$res_query_2->fields['codigo']." - ".$res_query_2->fields['descripcion']?></option>
                <?$res_query_2->Movenext();
              }?>
            </select>
    <?};//del if

?>