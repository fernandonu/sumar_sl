$(document).ready(function(){
 
   // Parametros para el select con id_efector como id
  $("#id_efector").on("change", function () {
      
      $("#id_efector option:selected").each(function () {
      //alert($(this).val());
        elegido=$(this).val();
        op="control_stock";
        $.ajax({
            data: { elegido: elegido, op:op },
            type: "POST",
            dataType: "text",
            url: "carga_diagnostico.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
               $("#tabla_remedio").html(data);
               $('[data-toggle="tooltip"]').tooltip();
               console.log(elegido);
          }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado: " +  textStatus);
          }
      });//fail
    });//each
  })//change
})//document.ready
