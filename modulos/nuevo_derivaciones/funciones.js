function autocomplet() {
  var min_length = 3; // min caracters to display the autocomplete
  var keyword = $('#country_id').val();
  var dataString = 'keyword='+ keyword;

  if (keyword.length >= min_length) {
    $.ajax({
      url: 'buscar_cie10.php',
      type: 'POST',
      data: dataString,
      success:function(data){
        $('#country_list_id').show();
        $('#country_list_id').html(data);
      }
    });
  } else {
    $('#country_list_id').hide();
  }
}

// set_item : this function will be executed when we select an item
function set_item(item,id) {
  var itemcomp=id+'-'+item;
  // change input value
  $('#country_id').val(itemcomp);
  $('#id_cie').val(id);
  // hide proposition list
  $('#country_list_id').hide();
}

function autocomplet_practica() {
  var min_length = 3; // min caracters to display the autocomplete
  var keyword = $('#practica').val();
  var dataString = 'keyword='+ keyword;

  if (keyword.length >= min_length) {
    $.ajax({
      url: 'buscar_practica.php',
      type: 'POST',
      data: dataString,
      success:function(data){
        $('#practicas_list').show();
        $('#practicas_list').html(data);
      }
    });
  } else {
    $('#practicas_list').hide();
  }
}
       
function set_item_practica(item,id) {
  var itemcomp=id+'-'+item;
  // change input value
  $('#practica').val(itemcomp);
  $('#id_practica').val(id);
  // hide proposition list
  $('#practicas_list').hide();
}

function autocomplet_fin() {
var min_length = 0; // min caracters to display the autocomplete
var keyword = $('#financiador_id').val();
var dataString = 'keyword='+ keyword;

if (keyword.length >= min_length) {
  $.ajax({
    url: 'buscar_efe.php',
    type: 'POST',
    data: dataString,
    success:function(data){
      $('#financiador_list_id').show();
      $('#financiador_list_id').html(data);
    }
  });
} else {
  $('#financiador_list_id').hide();
}
}

// set_item : this function will be executed when we select an item
function set_item_fin(item,id) {
  // change input value
  $('#financiador_id').val(item);
  $('#cuie_efe_deriv').val(id);
  // hide proposition list
  $('#financiador_list_id').hide();
}
//-------------------------------------------------------------------------------***
function autocomplet_efec() {
    var min_length = 0; // min caracters to display the autocomplete
    var keyword = $('#financiador_id1').val();
    var dataString = 'keyword='+ keyword;

    if (keyword.length >= min_length) {
      $.ajax({
        url: 'buscar_efe1.php',
        type: 'POST',
        data: dataString,
        success:function(data){
          $('#financiador_list_id1').show();
          $('#financiador_list_id1').html(data);
        }
      });
    } else {
      $('#financiador_list_id1').hide();
    }
}
       
// set_item : this function will be executed when we select an item
function set_item_efec(item,id) {
  // change input value
  $('#financiador_id1').val(item);
  $('#cuie_solic').val(id);
  // hide proposition list
  $('#financiador_list_id1').hide();
}


    