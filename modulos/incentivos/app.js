$(document).ready(function() {
 
 function control_entrada()
{
 if(document.all.dni.value==""){
  alert('Debe Ingresar un DNI valido, sin puntos ni separaciones');
  document.all.dni.focus();
  return false;
 } 

 if(document.all.dni.value.length<7 || document.all.dni.value.length>11){
  alert('Debe Ingresar un DNI valido, debe contener entre 7 y 8 caracteres');
  document.all.dni.focus();
  return false;
 }
 
 if(document.all.nombre.value==""){
  alert('Debe Ingresar al menos un Nombre');
  document.all.nombre.focus();
  return false;
 }
 
 if(document.all.apellido.value==""){
  alert('Debe Ingresar al menos un Apellido');
  document.all.apellido.focus();
  return false;
 }
 
 if(document.all.regimen.value==-1){
  alert('Debe Ingresar Regimen Laboral');
  document.all.regimen.focus();
  return false;
 }
 
 if(document.all.funcion.value==-1){
  alert('Debe Ingresar el tipo de Funcion');
  document.all.funcion.focus();
  return false;
 }
 
 if(document.all.funcion.value=="Especialidad"){
  if(document.all.especialidad.value==-1){
    alert('Debe Ingresar la Especialidad');
    document.all.especialidad.focus();
    return false;
  }
}

if(document.all.funcion.value=="Mantenimiento"){
  if(document.all.mantenimiento.value==-1){
    alert('Debe Ingresar la Funcion No-Profesional');
    document.all.mantenimiento.focus();
    return false;
  }
}
 
 if (confirm('Esta Seguro que Desea Agregar?'))return true;
 else return false; 
}//de function control_entrada()
 
 
 $("#id_efector").change(function(){
   
   var id_efector = $(this).val();
   var op = "id_efector";
      
   //console.log(id_efector);
   $.ajax({
            data: { op: op,
                    id_efector:id_efector },
            type: "POST",
            dataType: "json",
            url: "efector.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
               //$("#id_efector").val(data[0].id_efector);
               console.log(data);
               $("#cuie").val(data['cuie']);
               $("#nombre_efector").val(data['nombre']);
               $("#domicilio").val(data['domicilio']);
               $("#departamento").val(data['departamento']);
               $("#localidad").val(data['localidad']);
               $("#cod_pos").val(data['cod_pos']);
               $("#cuidad").val(data['cuidad']);
               $("#referente").val(data['referente']);
               $("#tel").val(data['tel']);
               $("#ver_listado").click();
               $("#ver_listado").removeAttr('disabled');
               $("#nuevo_dato").removeAttr('disabled');
               $("#boton_excel").html(data['link_excel']);
               
               //location.reload();
               
          }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado: " +  textStatus);
          }
      });//fail 
    
 })
  
  $("button[name='ver_listado']").click( function () {
        var op = "ver_listado";
        var cuie=$('#cuie').val();
        $.ajax({
            data: { op: op,
                    cuie:cuie },
            type: "POST",
            dataType: "text",
            url: "datos_personal.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
               $("#personal").html(data);
               //$('[data-toggle="tooltip"]').tooltip();
               //console.log(data);
          }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado: " +  textStatus);
          }
      });//fail
    
})//click
  
  $("button[name='imprimir_listado']").click( function () {
        var op = "imprimir_listado";
        var cuie=$('#cuie').val();
        $.ajax({
            data: { op: op,
                    cuie:cuie },
            type: "POST",
            dataType: "text",
            url: "datos_personal.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
               //$("#personal").html(data);
               //$('[data-toggle="tooltip"]').tooltip();
               console.log(data);
          }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado: " +  textStatus);
          }
      });//fail
    
})//click
   
  $("button[name='guardar_cambios']").click( function () {
        
        var cuie=$('#cuie').val();
        var dni=$('#dni').val();
        var nombre=$('#nombre').val();
        var apellido=$('#apellido').val();
        var estado=$('#estado').val();
        var reg_lab=$('#regimen').val();
        var funcion=$('#funcion').val();
        var especialidad=$('#especialidad').val();
        var mantenimiento=$('#mantenimiento').val();
        var id=$('#id_inc').val();
        var op = 'alta';
        
        //console.log(dni);
        if (control_entrada()){        
          $.ajax({
            data: {op:op,
                  id:id,
                  cuie:cuie,
                  dni:dni,
                  nombre: nombre,
                  apellido: apellido,
                  estado: estado,
                  reg_lab: reg_lab,
                  funcion: funcion,
                  especialidad: especialidad,
                  mantenimiento: mantenimiento},
            type: "POST",
            dataType: "json",
            url: "alta_modif.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
               //console.log(data);
               alert(data['mensaje']);
               $(myModal).modal('hide');
               location.reload();
               //console.log(html(data));
               
          }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado X ALTA: " +  textStatus);
          }
      });//fail
    }
  });
  
  $('#funcion').change( function () {
      
      var funcion = $('#funcion').val();
      
      $.ajax({
            data: {op:funcion
                  },
            type: "POST",
            dataType: "text",
            url: "datos_personal.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
               $("#especialidad-div").html(data);               
          }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado: " +  textStatus);
          }
      });//fail
    })

})


