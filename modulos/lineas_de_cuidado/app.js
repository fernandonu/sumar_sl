function control_entrada()
        {
         if(document.all.grupoetareo.value=="-1"){
          alert('Debe Ingresar un Grupo Etareo para la Medicion');
          document.all.grupoetareo.focus();
          return false;
         } 
         
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
         
         if(document.all.linea_cuidado.value=="-1"){
          alert('Debe Ingresar una Linea de Cuidado para la Medicion');
          document.all.linea_cuidado.focus();
          return false;
         } 
      
      }
      
$(document).ready(function(){
  
      $("#grupoetareo").change(function () {
        console.log($(this).val());
        elegido=$(this).val();
        $.ajax({
            data: { grupoetareo: elegido },
            type: "POST",
            dataType: "text",
            url: "datos_csi.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
               $("#linea_cuidado-div").html(data);
               //console.log(data);
          }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado: " +  textStatus);
          }
      });//fail
  });//change
})//ready
