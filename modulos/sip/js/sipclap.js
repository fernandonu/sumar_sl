function init(editable){
    setTimeout(function() {
        $('.yellow').icheck({
            checkboxClass: 'icheckbox_square-yellow',
            radioClass: 'iradio_square-yellow',
            increaseArea: '10%',
            focusClass: 'disabled'
        });
        $('.black').icheck({
            checkboxClass: 'icheckbox_square',
            radioClass: 'iradio_square',
            increaseArea: '10%',
            focusClass: 'disabled'
        });
        $(':checkbox:disabled').parent().removeClass('disabled');
        // control de maximos y minimos
        $('input[min],input[max]').each(function(i) {
            var val = parseInt($(this).val());
            if( val > 0){
                if ($(this).attr('min') > val) {
                    $(this).addClass('error');
                }
                if ($(this).attr('max') < val) {
                    $(this).addClass('error');
                }                
            }
        });
        // control valores de consulta
        $('.checkvalidacion').each(function(i) {
            if($(this).val().length>0){
                checkvalidacion($(this),0);
            } 
        });
        $('.number').each(function() {
            if($(this).val()=='NULL'){
               $(this).val('');
            } 
        });

    //cambiar background a seccion activa
    $('input').on('focus ifChecked', function() {
        $('table').removeClass('active');
        $(this).closest('table').addClass('active');
    });
    $('input[type="text"]').click(function() {
        $(this).select();
    });
    //agregar funcion de uncheck a radio buttons
    $('input').on('ifClicked', function() {
        if ($(this).prop('checked')) {
            $(this).icheck('unchecked');
        } else $(this).focus();
    });        
        
    // Modal de VARIABLES LIBRES
    $("#dialog_varlibres").dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width : 600,
            beforeClose: function(event, ui) {
                $('#libres').val($('#form_libres').serialize() );
            }
        });
    $('#agregar_varlibres').on('click',function(){
        $("#dialog_varlibres").dialog( "option", "title", "Variables Libres" );
        $("#dialog_varlibres").dialog('open');
    });
    $('#variables_libres').show();

    // Una vez cargado todo lo basico mostrar el formulario
        $(".divloading").fadeOut();
        $('#sipclap').fadeIn();

    // si no es editable poner la ficha solo para vista
    if(!editable){
            $('#guardar, #ficha input,#variables_libres input, #ficha select,#table_consultas button').attr('disabled',true);               
    }else{

    
    $('.datepicker').datepicker({
        onSelect: function(date) {
            if ($(this).attr('id') == 'fecha_embarazo_anterior') {
                checkFinEmbarazoAnterior(date)
            }
            if($(this).attr('id') == 'fecha_fum') {
                calculaFPPconFUM(date);
            }            
        },
        onClose: function() { $(this).focus(); },
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true
    });
    
    calcular_edad_madre($('#fecha_nacimiento_madre').val());
    
    $('#fecha_embarazo_anterior, #fecha_fum, #ruptura_membrana_fecha, #nacimiento_dia,#rn_egreso_fecha, #egreso_materno_fecha').datepicker( "option", "maxDate", "+0m +0w" );
    $('#gestas_previas').blur();
    
    $('#sipclap').on('change', '.codigoNomenclador', function(e) {
        var idx = $(this).attr('id').split('_');
        var cuie = $('#cons_efector_'+idx[1]).val();
        $.ajax({
            type: "POST",
            dataType:"json",
            async:false,
            url: "sipclap_funciones.php",
            data: 'codigoNomenclador=' + $(this).val() + '&cuieEfector='+cuie
        }).done(function(data) {
            if(data){
                var selected ='';
                var sel = $('#diagnosticovalue_'+idx[1]).val();
                $('#diagnostico_'+idx[1]).empty();
                $('#diagnostico_'+idx[1]).append('<option value="">S/D</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    if(data[i].diagnostico == sel){
                        selected = 'selected="selected"';
                    }else{selected = "";}
                    
                    $('#diagnostico_'+idx[1]).append('<option value="' + data[i].diagnostico + '" '+selected+' >' + data[i].diagnostico+' - '+data[i].descripcion + '</option>');
                }
            }
        });
    });
    $('.codigoNomenclador').change();
    
 /*   $('#sipclap').on('keydown', function(e) {
        if (e.keyCode == 9) {
            e.preventDefault();
            obj = $(document.activeElement);
            tab = obj.attr('tabindex');
            val = parseInt(tab)+1;
            $('[tabindex='+ val +']').focus();
            $('[tabindex='+ val +']').removeClass('checked');
        }
    });*/
    
    
    // Controles de edicion de la ficha
    //*********************************

    // Validacion de campos numericos
    $('#sipclap').on('keyup','.number', function(e) {
        if (isNaN($(this).val())) {
            e.stopPropagation;
            alert('Debe ingresar un valor numerico');
            $(this).val('');
        }
    });

    $('#sipclap').on('blur','.pesoembarazo', function(e) {
        if (isNaN($(this).val())) {
            e.stopPropagation;
            alert('Debe ingresar un valor numerico');
            $(this).val('');
        }else{
            if($(this).val() > 2000 || $(this).val() < 390){
                e.stopPropagation;
                alert('Valor fuera de rango, debe estar entre 39 y 200 kg');
                $(this).val('');  
            }
        }
    });

    $('#sipclap').on('blur','.edadgestacional', function(e) {
        if (isNaN($(this).val())) {
            e.stopPropagation;
            alert('Debe ingresar un valor numerico');
            $(this).val('');
        }else{
            if($(this).val() > 42 || $(this).val() < 3){
                e.stopPropagation;
                alert('Valor fuera de rango, debe estar entre 3 y 42 semanas');
                $(this).val('');  
            }
        }
    });



    $('#sipclap').on('blur','.presionarterial', function(e) {
        if (isNaN($(this).val())) {
            e.stopPropagation;
            alert('Debe ingresar un valor numerico');
            $(this).val('');
        }else{
            if($(this).val() > 350 || $(this).val() < 0){
                e.stopPropagation;
                alert('Valor fuera de rango, debe estar entre 0 y 350');
                $(this).val('');  
            }
        }
    });

    $('#sipclap').on('blur','.talla', function(e) {
        if (isNaN($(this).val())) {
            e.stopPropagation;
            alert('Debe ingresar un valor numerico');
            $(this).val('');
        }else{
            if($(this).val() < 80){
                e.stopPropagation;
                alert('Valor fuera de rango, debe ser mayor a 80');
                $(this).val('');  
            }
        }
    });
    
    // Validacion gestas previas
    $('#gestas_previas').on('blur',function(){
        if($(this).val()=='0'){
                    //deshabilitar controles
                    var deshab = true;
                    $('#embarazo_ectopico,#abortos,#tres_espontaneo_cons,#partos_previos,#partos_vaginales').val('0');
                    $('#cesareas,#nacidos_vivos,#rn_que_viven,#nacido_muerto,#muerto_1_sem,#muerto_despues_1_sem').val('0');
                    
                    $('[id^="peso_rn_previo"]').attr('disabled',deshab);
                    $('[id^="peso_rn_previo"]').icheck('unchecked');
                }else{
                    //habilitar controles
                    var deshab = false;
                    $('[id^="peso_rn_previo"]').attr('disabled',deshab);
                }        
                $('#embarazo_ectopico,#abortos,#tres_espontaneo_cons,#partos_previos,#partos_vaginales').attr('readonly',deshab);
                $('#cesareas,#nacidos_vivos,#rn_que_viven,#nacido_muerto,#muerto_1_sem,#muerto_despues_1_sem').attr('readonly',deshab);  
    });
    
    // Validacion de minimos y maximos 
    $('#sipclap').on('blur','input[min],input[max]', function(e) {
            var val = parseInt($(this).val());
            if(val>0){
                var menor = $(this).attr('min') > val;
                var mayor = $(this).attr('max') < val;
                if( menor || mayor ){
                    $(this).addClass('error');
                }else{
                    $(this).removeClass('error');
                }
            }    
    });
    
    // Control de checks dependientes de valores 
    $('#hb_menor20sem, #hb_mayor20sem').on('blur', function(){
        var id = $(this).attr('id');
        var val = $(this).val();
        if( val.length > 0 && val < 110 )
            $('#'+id+'_menor11g').icheck('checked');
        else
            $('#'+id+'_menor11g').icheck('unchecked');
    });
    $('#glucemia_menor20sem, #glucemia_mayor30sem').on('blur', function(){
        var id = $(this).attr('id');
        var val = $(this).val();
        if( val.length > 0 && val >= 105 )
            $('#'+id+'_mayor105').icheck('checked');
        else
            $('#'+id+'_mayor105').icheck('unchecked');
    });
    $('#ruptura_membrana_temperatura').on('change',function(){
        if( $(this).val() >=380 )
            $('#ruptura_membrana_temperatura_mayor38').icheck('checked');
        else
            $('#ruptura_membrana_temperatura_mayor38').icheck('unchecked');
    });
    $('#rn_peso').on('blur', function(){
        id = $(this).attr('id');
        if( $(this).val() <2500 ){
            $('#'+id+'_men2500').icheck('checked');
        }
        if( $(this).val() >=4000 ){
            $('#'+id+'_may4000').icheck('checked');
        }
        $('#rn_perim_cefalico').focus();
    });
    
    // Deshabilitar controles segun el valor si o no
    $('#hospitalizacion_no').on('ifClicked', function() {
        $('#hospitalizacion_dias').val('');
        $('#hospitalizacion_dias').attr('readonly',true);
    });
    $('#hospitalizacion_si').on('ifClicked', function() {
        $('#hospitalizacion_dias').attr('readonly',false);
        $('#hospitalizacion_dias').focus();
    });
    $('#nacimiento_multiple_no').on('ifChecked', function() {
        $('#nacimiento_multiple_orden').val(0);
        $('#desgarro_grado').attr('readonly',true);
        $(this).parent().removeClass('disabled');
    });    
    
    $('#desgarros_no').on('ifChecked', function() {
        $('#desgarro_grado').val('');
        $('#desgarro_grado').attr('readonly',true);
        $(this).parent().removeClass('disabled');
    });    
    $('#desgarros_no').on('ifUnchecked', function() {
        $('#desgarro_grado').attr('readonly',false);
        $('#desgarro_grado').focus();
    });
    
    $('#medicacion_recibida_otros_no').on('ifClicked', function() {
        $('#codigo_medicacion_1, #codigo_medicacion_2').attr('readonly',true);
        $('#codigo_medicacion_1, #codigo_medicacion_2').val('');
    });
    $('#medicacion_recibida_otros_si').on('ifClicked', function() {
        $('#codigo_medicacion_1, #codigo_medicacion_2').attr('readonly',false);
        $('#codigo_medicacion_1').focus();
    });
    
    $('#defectos_congenitos_no').on('ifChecked', function() {
        $('#rn_defectos_congenitos_codigo').attr('disabled',true);
    });    
    $('#defectos_congenitos_no').on('ifUnchecked', function() {
        $('#rn_defectos_congenitos_codigo').attr('disabled',false);
    });
    
    // ENFERMEDADES
    /*$('#enfermedades_ninguna').on('ifChecked', function() {
        // check niguna
        $('#enfermedades_ninguna .black').icheck('checked');
        $('#enfermedades_ninguna .yellow').icheck('unchecked');
        $('#enfermedades_ninguna input[type="text"]').val('');       
    });    
    $('#enfermedades_ninguna, #enfermedades_1omas').on('ifUnchecked', function() {
        // uncheck cualquier opcion
        $('#enfermedades').icheck('unchecked');
        $('#enfermedades input[type="text"]').val('');   
    });
    $('#enfermedades .yellow').on('ifChecked', function() {
        // cambios en opcion si
        $('#enfermedades_1omas').icheck('checked');
    });    
    $('#enfermedades input').on('change', function() {
        // cambios en codigos
        if( $(this).val().length>0 )
            $('#enfermedades_1omas').icheck('checked');
    });    */
    // RECIEN NACIDO ENFERMEDADES
    $('#rn_enfermedades input[type="text"]').attr('readonly',true);

    $('#rn_enfermedades_ninguna').on('ifChecked', function() {
        // check niguna
        $('#rn_enfermedades_1omas').icheck('unchecked');
        $('#rn_enfermedades input[type="text"]').val('');    
        $('#rn_enfermedades input[type="text"]').attr('readonly',true);   
    });    
    
    $('#rn_enfermedades_1omas').on('ifChecked', function() {
        // uncheck cualquier opcion
        $('#rn_enfermedades_ninguna').icheck('unchecked');
        $('#rn_enfermedades input[type="text"]').val('');   
        $('#rn_enfermedades input[type="text"]').attr('readonly',false); 
    });

    /*$('#rn_enfermedades input').on('change', function() {
        // cambios en codigos
        if( $(this).val().length>0 )
            $('#rn_enfermedades_1omas').icheck('checked');
    });*/   
        
    // Agregar consultas prenatales
    $('#agregar_consultas,#consulta_otro_efector').on('click',function() {
            var id = $(this).attr('id');
            cant = $('#table_consultas tbody tr').length;
            lastindex = 260 + (cant*14);
            if( cant >= 25)
                 return false;  
            var jqxhr = $.ajax({
                type: "POST",
                url: "row_consulta.php",
                data: {idx:lastindex, row:cant}
            }).done(function(data) {
                $('#table_consultas').append(data);
                $('.datepicker').datepicker({
                    onClose: function() { $(this).focus(); },
                    dateFormat: "dd-mm-yy",
                    changeMonth: true,
                    changeYear: true
                });
                $('.checkvalidacion').blur(function(){ 
                    if($(this).val().length>0) checkvalidacion($(this),1);    
                });
                efectorTd = $('#table_consultas tbody tr').last().find('td').first();
                efector = efectorTd.find('input');
                efector.focus();
                if(id=='consulta_otro_efector'){
                    efector.addClass('all');
                    efector.attr('required',false);
                    var idx = efector.attr('id').split('_');
                    $('#codigo_'+idx['2']).attr('required',false);
                    $('#diagnostico_'+idx['2']).attr('required',false);
                }
            });
            
            /*$.get("sipclap_funciones.php", {efectorNomenclador: $('#user').val() },function(data2){
                alert(data2); 
            });*/
    });
    $('#table_consultas').on('blur', '.checkvalidacion', function(e) {    
        if($(this).val().length>0) checkvalidacion($(this),1);    
    });
    $('#sipclap').on('click', '.delconsulta', function(e) {
        e.preventDefault();
        if (confirm('Desea eliminar esta consulta?'))
            $(this).closest('tr').remove();
     });
    $('#sipclap').on('change','.trconsulta .efectorselect',function(){
        var idx = $(this).attr('id').split('_');
        var jqxhr = $.ajax({
                type: "POST",
                dataType:"json",
                url: "sipclap_funciones.php",
                data: {efectorNomenclador: $(this).val() }
            }).done(function(data) {
                $('#codigo_'+idx[2]).empty();
                $('#codigo_'+idx[2]).append('<option value="">S/D</option>');
                if(data){
                    for (var i = 0, total = data.length; i < total; i++) {
                        $('#codigo_'+idx[2]).append('<option value="' + data[i].codigo + '" >' + data[i].codigo+' - '+data[i].descripcion + '</option>');
                    }
                }
            });        
    });
    
    
    // Modal de EFECTORES Y AUXILIARES
    var inputId;
    // seleccion de efectores
    $('#sipclap').on('click', '.efectorselectBtn', function(e) {
        $(".divloading").show();
        inputId = $(this).parent().find('.efectorselect');
        if(inputId.hasClass('all')) otro = '&all=1';
        else otro ='';        
        inputId.blur();
        $.ajax({
            type: "POST",
            url: "list_efectores.php",
            data: "id=" + inputId.attr('id') + otro,
            success: function(data) {
                $(".divloading").hide();
                $("#dialog_efectores").html(data);
                $("#dialog_efectores").dialog('option', 'title', inputId.data('tabla'));
                $("#dialog_efectores").dialog('open');
            }
        });
    });
    
    $('#sipclap').on('keydown', '.efectorselect', function(e) {
        if (e.which == e.which114 && e.ctrlKey) {
            $(".divloading").show();
            e.preventDefault();
            inputId = $(this);
            if(inputId.hasClass('all')) otro = '&all=1';
            else otro ='';        
            inputId.blur();
            $.ajax({
                type: "POST",
                url: "list_efectores.php",
                data: "id=" + inputId.attr('id') + otro,
                success: function(data) {
                    $(".divloading").fadeOut();
                    $("#dialog_efectores").html(data);
                    $("#dialog_efectores").dialog('option', 'title', inputId.data('tabla'));
                    $("#dialog_efectores").dialog('open');
                }
            });
        }
    });

    $("#dialog_efectores").dialog({
        title: "Listado de Efectores",
        autoOpen: false,
        modal: true,
        width: 500,
        minHeight: 450,
        open: function(event, ui) {
            $('#inputId').val();
        },
        close: function(event, ui) {
            inputId.val($('#inputId').val());
            nextInput = inputId.parent('td').next().find('input');
            nextInput.focus();
            inputId.change();
        }
    });        

    // seleccion de auxiliares
    $('#sipclap').on('keydown', '.auxiliar', function(e) {
        if (e.which == 114 && e.ctrlKey) {
            e.preventDefault();
            inputId = $(this);
            inputId.blur();
            $.ajax({
                type: "POST",
                url: "list_auxiliar.php",
                data: "tabla=" + inputId.data('tabla'),
                success: function(data) {
                    $("#dialog_auxiliar").html(data);
                    $("#dialog_auxiliar").dialog('option', 'title', inputId.data('tabla'));
                    $("#dialog_auxiliar").dialog('open');
                }
            });
        }
    });
    $("#dialog_auxiliar").dialog({
        autoOpen: false,
        modal: true,
        width: 500,
        minHeight: 450,
        open: function(event, ui) {
            $('#inputId').val();
        },
        close: function(event, ui) {
            inputId.val($('#inputId').val());
        }
    });
    
    // FINALIZAR FICHA
    $('#finalizar').on('click',function(){
        if( $('#lugar_control_prenatal').val().trim()==='' ){
            alert('Debe indicar el lugar de Control Prenatal');
            $('#lugar_control_prenatal').focus();
            return false;
        }        
        if( $('#lugar_parto_aborto').val().trim()==='' ){
            alert('Debe indicar el lugar de Parto/Aborto');
            $('#lugar_parto_aborto').focus();
            return false;
        }        
        if( $('#gestas_previas').val().trim()==='' ){
            alert('Debe indicar cantidad de Gestas Previas');
            $('#gestas_previas').focus();
            return false;
        }
        if( !$('#parto').prop('checked') && !$('#aborto').prop('checked') ){
            alert('Debe indicar la terminacion en Parto o Aborto');            
            $('#parto').focus();
            window.scrollTo(0 , $('#parto').offset().top -60);
            return false;
        }
        if( $('#consultas_prenatales_total').val().trim()===''){
            // setConsultasPrenatalesTotal();
        }
        if( $('#nacimiento_multiple_orden').val().trim()==='' ){
            alert('Debe indicar Orden');
            $('#nacimiento_multiple_orden').focus();
            window.scrollTo(0 , $('#nacimiento_multiple_orden').offset().top -60);
            return false;
        }

        // validar fin de ficha
        if( confirm('CONFIRMA LA FINALIZACION DE LA FICHA PARA SER PROCESADA?') ){
            $('#finalizarFicha').val(1);
            $('#guardar').click();
        }
    });
    
    // FICHA NUEVA
    $('#nuevaFichaBtn').on('click',function(){
        if( $('#nacimiento_multiple_si').prop('checked') ){
            if( confirm('Esta ficha indica parto multiple.\n \n Desea cargar otro nacimiento del mismo parto?') )
                  $('#nuevaMultiple').val('S');
            else  $('#nuevaMultiple').val('N');
        }
        $('#nuevaFicha').val('S');
        $('#sipclap').submit();
    });
    
    
    // SUBMIT DEL FORMULARIO
    $('form').on('submit', function(e) {
        // e.preventDefault();
        // validacion de gestas
        var gestasPrevias = ($('#gestas_previas').val()==='') ? 0 : parseInt($('#gestas_previas').val());
        var partosPrevios = ($('#partos_previos').val()==='') ? 0 : parseInt($('#partos_previos').val());
        var abortos = ($('#abortos').val()==='') ? 0 : parseInt($('#abortos').val());
        if( abortos + partosPrevios !== gestasPrevias ){
            alert('Los valores de gestas previas, partos y abortos no coinciden');
            $('#gestas_previas').focus();
            return false;
        }
        // validacion de partos
        var partosVaginales = ($('#partos_vaginales').val()==='') ? 0 : parseInt($('#partos_vaginales').val());
        var cesareas = ($('#cesareas').val()==='') ? 0 : parseInt($('#cesareas').val());
        if( cesareas + partosVaginales !== partosPrevios ){
            alert('Los valores de partos, vaginales y cesareas no coinciden');
            $('#partos_previos').focus();
            return false;
        }
        // validacion de nacidos
        var nacidosVivos = ($('#nacidos_vivos').val()==='') ? 0 : parseInt($('#nacidos_vivos').val());
        var nacidosMuertos = ($('#nacido_muerto').val()==='') ? 0 : parseInt($('#nacido_muerto').val());
        if( (nacidosVivos + nacidosMuertos != partosPrevios) && !($("#antecedentes_gemelares_si").prop("checked")) ){
            alert('Los valores de nacidos vivos, nacidos muertos con los partos no coinciden');
            $('#nacidos_vivos').focus();
            return false;
        }

        // Validaciones para cantidad de partos 0 con vivos/muertos > 0
        if(partosPrevios < 1 && ( (nacidosVivos + nacidosMuertos) > 0)){
            alert('Los partos deben ser logicamente coherentes a la cantidad de nacimientos vivos/muertos');
            $('#partos_previos').focus();
            return false;   
        }
        // validacion de viven
        var rnViven = ($('#rn_que_viven').val()==='') ? 0 : parseInt($('#rn_que_viven').val());
        var muerto1sem = ($('#muerto_1_sem').val()==='') ? 0 : parseInt($('#muerto_1_sem').val());
        var muertoDespues1sem = ($('#muerto_despues_1_sem').val()==='') ? 0 : parseInt($('#muerto_despues_1_sem').val());
        if( rnViven + muerto1sem + muertoDespues1sem !== nacidosVivos ){
            alert('Los valores de viven, muertos antes 1a semana y despues 1a semana no coinciden');
            $('#rn_que_viven').focus();
            return false;
        }
            
        // validar enfermedades maternas
        if( $('#enfermedades_1omas').prop('checked') ){
            //verificar alguna marcada
            var ck = $('#enfermedades :radio:checked').not('#enfermedades_1omas');
            if ( ck.length==0 && $('#codigo_enfermedad_1').val()=='' && $('#codigo_enfermedad_2').val()=='' && $('#codigo_enfermedad_3').val()=='' ){
                alert('Si indica 1 o mas enfermedades debe marcar cuales son');
                $('#enfermedades_1omas').focus();
                window.scrollTo(0 , $('#enfermedades_1omas').offset().top );
                return false;
            }
        }
        // validar enfermedades RN
        if( $('#rn_enfermedades_1omas').prop('checked') ){
            //verificar alguna marcada
            if( $('#rn_enfermedades input[type="text"][value!=""]').length==0 ){
                alert('Si indica 1 o mas enfermedades debe marcar cuales son');
                $('#rn_enfermedades_1omas').focus();
                window.scrollTo(0 , $('#rn_enfermedades_1omas').offset().top );
                return false;
            }
        }
        
        // validacion de consultas - Si se guarda sin finalizar se exige la carga de una consulta
        // var valPartoAborto = $("#parto").prop('checked') || $("#aborto").prop('checked');
        // if( $('#finalizarFicha').val()==0 &&  ( $('#table_consultas .trconsulta').length===0 && !valPartoAborto) && $("#lugar_parto_aborto").val().length === 0 ){
        //     alert('Debe ingresar al menos una consulta prenatal');
        //     $('#agregar_consultas').click();
        //     window.scrollTo(0 , $('#table_consultas').offset().top - 30 );
        //     return false;
        // }

        // validacion de parto o aborto contra efector cargado
        var valPartoAborto = !( $("#parto").prop('checked') || $("#aborto").prop('checked') );
        if( valPartoAborto && !($("#lugar_parto_aborto").val().length === 0) ){
            alert('Debe cargar los datos del parto');
            window.scrollTo(0 , $('#parto').offset().top - 30 );
            return false;
        }

        // validacion de parto o aborto contra efector cargado II
        var valPartoAborto = ( $("#parto").prop('checked') || $("#aborto").prop('checked') );
        if( valPartoAborto && $("#lugar_parto_aborto").val().length === 0 ){
            alert('Debe cargar el lugar de parto o aborto');
            $('#lugar_parto_aborto').focus();
            return false;
        }


        $(".divloading").show();

        // Verificar que los efectores correspondan a cuie valido
        $('.efectorselect').not('.all').each(function(i) {
            if($(this).val().length>0){
                var tipo = 'E';
               // var invalid = false;
                if($(this).attr('id')=='lugar_parto_aborto')
                    tipo = 'H';
                $.ajax({
                    type: "POST",
                    async:false,
                    url: "sipclap_funciones.php",
                    data: 'tipo_cuie=' + tipo + '&cuie=' + $(this).val() + '&user=' + $('#user').val()
                }).done(function(data) {
                    $(".divloading").fadeOut();
                    if(data=='NO'){ 
                        // alert('El codigo ingresado no es valido.');
                        $(this).focus();
                        return false;
                    }else{
                        return true;
                    }
                });
            } 
        });
        
    });

    }
  }, 5);    
      //agregar title a checks
    $('input').on('ifCreated', function(e) {
        if (this.title) {
            $(this).parent().attr('title', this.title);
        }
    }); 
    //Cambiar ficha activa
    var prev_val;
    $('#fichasList').focus(function() {
        prev_val = $(this).val();
    }).change(function(){
        $(this).unbind('focus');
        var conf = true;
        if( $('#gestas_previas').prop('disabled')==false )
            var conf = confirm('Desea abandonar la carga de la ficha actual? \n \n Los datos no guardados se perderan.');
        if(conf == true){
            var link = $(this).find('option:selected').data('link');
            if(link){  window.location.href = link;   }
        }else{
            $(this).val(prev_val);
            $(this).bind('focus');
            return false;
        }
    });  
}
   
    
    // Eliminar ficha - NO SE UTILIZA
    /*$('#delete').on('click', function(){
        $msg = 'Esta seguro de eliminar esta ficha? \n \n LOS DATOS NO PODRAN SER RECUPERADOS';
        if( confirm($msg) ){
            $('#op').val('DELETE');
            var ficha = $('#sipclap').serialize();
            var jqxhr = $.ajax({
                type: "POST",
                url: "guardar_ficha_sip.php",
                data: '&'+ficha
            })
                .done(function(data) {
                    alert(data);
                    $('#goback').click();
                })
                .fail(function(data) {
                    alert(data);
                });
            
        }
    });*/
    
    
    
/*
 * Check fin embarazo anterior
 */
function checkFinEmbarazoAnterior(fecha){
    var anios = calcular_edad(fecha);
    if (anios <= 0 || anios >5 )
        $('[id="emb_anterior_menor_1_anio"]').icheck('checked');
    else
        $('[id="emb_anterior_menor_1_anio"]').icheck('unchecked');    
}
/*
 * Calcular FPP con la FUM
 */
function calculaFPPconFUM(fecha) {
    var jqxhr = $.ajax({
        type: "POST",
        url: "sipclap_funciones.php",
        data: 'fum=' + fecha
    }).done(function(data) {
        $('#fecha_fpp').val(data);
    });
}

/*
 * Calcular edad y marcar segun corresponda
 */
function calcular_edad_madre(date) {
    //Calcular edad de la madre y actualizar controles
    var edad = calcular_edad(date);
    $('#edad_materna').val(edad);
    if (edad < 15 || edad > 35)
        $('#edad_materna_rango').icheck('checked');
    else
        $('#edad_materna_rango').icheck('unchecked');
}

// Carga efector segun lo seleccionado en modal
function setEfectorCuie(cuie) {
    $("#inputId").val(cuie);
    $("#dialog_efectores").dialog('close');
};

/*
 * Controla los valores de peso y altura uterina segÃºn edad gestacional para consultas antenatales - Tabla Validaciones
  */
function checkvalidacion(obj,msg){
    id = obj.attr('id').split('_');
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "sipclap_funciones.php",
        data: "tabla=" + obj.data('tabla') + "&edadgest=" + $('#cons_edad_gestacional_'+id[2]).val() + "&valor=" + obj.val(),
        success: function(data) {
            if(data=='error')
                  obj.addClass('error');
            else  obj.removeClass('error');
                /*if(data.min!=null && data.max!=null){ 
                    //if(msg) alert('Para esta edad gestacional el valor debe estar entre '+data.min+' y '+data.max);
                    obj.addClass('error');
                }*/
        }
    });

}

/*
 deshabilita la tecla enter
 ej de uso: onkeypress="return deshabilitar_enter(event)"
 */
function deshabilitar_enter(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 13)
        return false;
    if(tecla == 115 && e.ctrlKey)
        return false;
}

function calcular_edad(date) {
    //Calculo de edad segun fecha de naciemiento
    var fechaActual = new Date();
    var ddActual = fechaActual.getDate();
    var mmActual = fechaActual.getMonth() + 1;
    var yyyyActual = fechaActual.getFullYear();
    var fechanac = date.split('-');
    var ddNac = fechanac[0];
    var mmNac = fechanac[1];
    var yyyyNac = fechanac[2];
   /* if (yyyyNac > yyyyActual)
        yyyyNac = '19' + fechanac[2];*/
//retiramos el primer cero de la izquierda
    if (mmNac.substr(0, 1) == 0) {
        mmNac = mmNac.substring(1, 2);
    }
//retiramos el primer cero de la izquierda
    if (ddNac.substr(0, 1) == 0) {
        ddNac = ddNac.substring(1, 2);
    }
    var edad = yyyyActual - yyyyNac;
//validamos si el mes de cumpleaÃ±os es menor al actual
//o si el mes de cumpleaÃ±os es igual al actual
//y el dia actual es menor al del nacimiento
//De ser asi, se resta un aÃ±o
    if ((mmActual < mmNac) || (mmActual == mmNac && ddActual < ddNac)) {
        edad--;
    }
    return edad;
}   

function imprFaltantes(){
 var ficha=document.getElementById('dialog_faltantes');
 var ventimp=window.open(' ','popimpr');
 ventimp.document.write(ficha.innerHTML);
 ventimp.document.close();
 ventimp.print();
 ventimp.close();
}