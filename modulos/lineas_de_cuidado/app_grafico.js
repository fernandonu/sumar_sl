var chart;
var chartData = [];
var madre = $("#madre").val();
var minima = $("#minima").val();
var adecuada = $("#adecuada").val();

var data = {madre:madre, minima:minima, adecuada:adecuada};


transformar = function () {
   chart.transform(this.id); 
}

requestData = function(data){
  if (data) {console.log(data);
                //$('#spinner').addClass('fa-refresh');
                arr = ["madre",data['madre']];
                chartData.push(arr);
                arr = ["minima",data['minima']];
                chartData.push(arr);
                arr = ["adecuada",data['adecuada']];
                chartData.push(arr);
                addChart();
                $('#spinner').removeClass('fa-refresh');
            }
    else console.log("no hay data");
}

addChart = function (){
  chart = c3.generate ({
    bindto : "#chart",
    data : {
      type : 'pie',
      columns : chartData,
      names : {
        madre: 'Prestaciones Madres',
        minima: 'Minima' ,
        adecuada: 'Adecuada'
      }
    },
    bar:{
      width : {
        ratio : 1
      }
    },
    tooltip : {
      format : {
        title : function (x) {
          return 'Estado de la linea de Cuidado';
        }
      }
    },
    axis : {
      rotated : false,
      y : {
        //label : 'Cantidad de Beneficiarios'
      },
      x : {
        show : true,
        label : 'Linea de Cuidado'
      }
    }
    
  })
}

$('#bar, #pie, #donut').click(transformar);
  $('#spinner').addClass('fa-refresh');
  //solicita_datos();   
  requestData(data);
  //addChart();

$('#guardar').click( function () {
    if (confirm("Desea guardar los nuevos Parametros?")) {
      var op = "cambio_parametros";
      var long=$('#indice').val();
      for (i=0;i<long;i++) {
        st_parametro="#id_parametro"+i;
        st_minima="#minima"+i;
        st_adecuada="#adecuada"+i;
        
        id_parametro=parseInt($(st_parametro).val());
        minima=parseInt($(st_minima).val());
        adecuada=parseInt($(st_adecuada).val());
        
        $.ajax({
            data: { op:op, id_parametro:id_parametro, minima:minima, adecuada:adecuada},
            type: "POST",
            url: "datos_csi.php",
          })
      .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
               }
      })//done
      .fail(function( jqXHR, textStatus, errorThrown ) {
          if ( console && console.log ) {
              console.log( "La solicitud a fallado: " +  textStatus);
          }
      });//fail
    }
  alert("Parametros Guardados");
  }
})//click




     

      
  


