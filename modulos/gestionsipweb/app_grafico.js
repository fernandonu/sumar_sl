var chart;
var chartData = [];
var data = [];

var i = $("#i").val();

for (j=0;j<=i-1;j++) {
    
  var str_desc = "#descripcion"+j;
  var str_cantidad = "#cantidad"+j;
  var descripcion = $(str_desc).val();
  var cantidad = parseInt($(str_cantidad).val());
  
   
  data.push([descripcion,cantidad]);
    
}

transformar = function () {
   chart.transform(this.id); 
}

requestData = function(data){
  if (data) {console.log(data);
                //$('#spinner').addClass('fa-refresh');
                for (i=0;i<data.length;i++) {
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
      type : 'pie',
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
          return 'Estado';
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
        label : 'Cantidad de Embarazadas'
      }
    }
    
  })
}

$('#bar, #pie, #donut').click(transformar);
  $('#spinner').addClass('fa-refresh');
  //solicita_datos();   
  requestData(data);
  //addChart();