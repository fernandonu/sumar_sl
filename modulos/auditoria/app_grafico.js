var chart;
var chartData = [];
var data = [];

var i = $("#i").val();

for (j=1;j<=i;j++) {
  var str_codigo = "#codigo"+j;
  var str_cantidad = "#cantidad"+j;
  //var str_id_nomenclador = "#id_nomenclador"+j;
  var str_desc = "#descripcion"+j;
  
  var codigo = $(str_codigo).val();
  var cantidad = $(str_cantidad).val();
  //var id_nomenclador = $(str_id_nomenclador).val();
  var descripcion = $(str_desc).val();
   
  data.push([descripcion,cantidad]);
  
}

transformar = function () {
   chart.transform(this.id); 
}

requestData = function(data){
  if (data) {console.log(data);
                //$('#spinner').addClass('fa-refresh');
                for (i=0;i<data.length-1;i++) {
                  arr = [data[i][0],data[i][1]];
                  chartData.push(arr);
                  };
                  addChart();
                $('#spinner').removeClass('fa-refresh');
            }
    else console.log("no hay data");
}

addChart = function (){
  chart = c3.generate ({
    bindto : "#chart",
    data : {
      type : 'bar',
      columns : chartData,
      names : {
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
          return 'Estado de Facturacion';
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
        label : 'Cantidad de Prestaciones'
      }
    }
    
  })
}

$('#bar, #pie, #donut').click(transformar);
  $('#spinner').addClass('fa-refresh');
  //solicita_datos();   
  requestData(data);
  //addChart();