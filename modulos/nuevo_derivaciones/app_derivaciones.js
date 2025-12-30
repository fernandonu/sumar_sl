//empieza funcion mostrar tabla
function muestra_tabla(obj_tabla,nro){
 oimg=eval("document.all.imagen_"+nro);//objeto tipo IMG
 if (obj_tabla.style.display=='none'){
  obj_tabla.style.display='inline';
    oimg.show=0;
    oimg.src=img_ext;
 }
 else{
  obj_tabla.style.display='none';
    oimg.show=1;
  oimg.src=img_cont;
 }
}//termina muestra tabla

// Validar Fechas
function esFechaValida(fecha){
    if (fecha != undefined && fecha.value != "" ){
        if (!/^\d{2}\/\d{2}\/\d{4}$/.test(fecha.value)){
            alert("formato de fecha no válido (dd/mm/aaaa)");
            return false;
        }
        var dia  =  parseInt(fecha.value.substring(0,2),10);
        var mes  =  parseInt(fecha.value.substring(3,5),10);
        var anio =  parseInt(fecha.value.substring(6),10);
 
    switch(mes){
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            numDias=31;
            break;
        case 4: case 6: case 9: case 11:
            numDias=30;
            break;
        case 2:
            if (comprobarSiBisisesto(anio)){ numDias=29 }else{ numDias=28};
            break;
        default:
            alert("Fecha introducida errónea");
            return false;
    }
 
        if (dia>numDias || dia==0){
            alert("Fecha introducida errónea");
            return false;
        }
        return true;
    }
}
 
function comprobarSiBisisesto(anio){
if ( ( anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0))) {
    return true;
    }
else {
    return false;
    }
}

//controlan que ingresen todos los datos necesarios par el muleto
function control_nuevos(){
    
    if(document.all.prioridad.telefono==""){
       alert("Debe completar el telefono de contacto");
       document.all.telefono.focus();
       return false;
       }

    if(document.all.prioridad.mail==""){
       alert("Debe completar el mail de contacto");
       document.all.mail.focus();
       return false;
       }


    if(document.all.fecha_der.value==""){
       alert("Debe completar la Fecha de solicitud de turno");
       document.all.fecha_der.focus();
       return false;
       }

    if(document.all.profesional.value==""){
       alert("Debe completar el profesional solicitante");
       document.all.profesional.focus();
       return false;
       }


    if(document.all.fecha_diag.value==""){
       alert("Debe completar la Fecha del diagnostico ");
       document.all.fecha_diag.focus();
       return false;
       }

    if(document.all.prioridad.value=="-1"){
       alert("Debe completar la prioridad del turno");
       document.all.prioridad.focus();
       return false;
       }
       
    if(document.all.id_cie.value==""){
       alert("Debe seleccionar un diagnostico");
       document.all.id_cie.focus();
       return false;
    }

    if(document.all.id_practica.value==""){
       alert("Debe seleccionar la practica solicitada");
       document.all.id_practica.focus();
       return false;
    }

    if(document.all.cuie_efe_deriv.value==""){
       alert("Debe seleccionar un Efector destino");
       document.all.cuie_efe_deriv.focus();
       return false;
    }  
   
   /*if(document.all.fecha_deriv.value==""){
       alert("Debe completar la Fecha de derivacion ");
       document.all.fecha_deriv.focus();
       return false;
       }*/

  
}
//de function control_nuevos()