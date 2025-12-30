$(document).ready(function(){
  
    $("#cuie").change(function () {
      cuie=$(this).val();
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
      
      op='grupo';
      
      $.ajax({
          data: {op: op, cuie: cuie, fecha_desde: fecha_desde, fecha_hasta: fecha_hasta},
          type: "POST",
          dataType: "text",
          url: "datos_auditoria.php",
        })
    .done(function( data, textStatus, jqXHR ) {
        if ( console && console.log ) {
             console.log( "La solicitud se ha completado correctamente.");
             $("#practicas-div").html(data);
             //console.log(data);
        }
    })//done
    .fail(function( jqXHR, textStatus, errorThrown ) {
        if ( console && console.log ) {
            console.log( "La solicitud a fallado: " +  textStatus);
        }
    });//fail
  });//change
});
    
function control_entrada()
  {        
         if(document.all.fecha_desde.value==""){
          alert('Debe Ingresar una Fecha de comienzo del periodo');
          document.all.fecha_desde.focus();
          return false;
         }
         
         if(document.all.fecha_hasta.value==""){
          alert('Debe Ingresar una Fecha de final del periodo');
          document.all.fecha_hasta.focus();
          return false;
       }
  };
  

  