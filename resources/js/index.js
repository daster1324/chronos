/***************************/
/*          INDEX          */
/***************************/

// Trigger de los dropdowns
var handler = $('#form-inicial select').change(function(){ 

    // 1º Almacena nombre, id css e id del option
    let name = $(this).attr("name");
    let value = $(this).val();

    if(name == "carrera" && value != "none"){
        cleanItinerarios();
        disableItinerarios();
        
        carrera_seleccionada(value);
    }

    if(name == "itinerario" && value != "none"){
        itinerario_seleccionado($('#selector-carrera').val(), value);
    }

    checkStatus();
}); 



/**
 * Limpia  el dropdown de los itinerarios
 * Deshabilita dicho dropdown y el botón de envío
 */
function reset_form() {
    $("#selector-carrera").val('none');
    cleanItinerarios();
    disableItinerarios();
    disableSend();
}

/**
 * Habilita el dropdown de los itinerarios
 */
function enableItinerarios() { $("#selector-itinerario").prop( "disabled", false ); }

/**
 * Deshabilita el dropdown de los itinerarios
 */
function disableItinerarios(){ $("#selector-itinerario").prop( "disabled", true ); }

/**
 * Elimina todos los elementos del dropown salvo el primero
 */
function cleanItinerarios() {  $('#selector-itinerario').children('option:not(:first)').remove(); }

/**
 * Habilita el botón de envío
 */
function enableSend() { $("#boton-enviar").prop( "disabled", false ); }

/**
 * Deshabilita botón de envío
 */
function disableSend(){ $("#boton-enviar").prop( "disabled", true ); }

/**
 * Busca los itinerarios asociados a la carrera seleccionada
 * @param {Number} id - ID de la carrera seleccionada
 */
function carrera_seleccionada(id){
    $.ajax({
        url: '/async.php',
        dataType: 'json',
        type: 'post',
        data: "op=1&idcarrera="+id,
        success: function( data, textStatus, jQxhr ){
            if(data.indexOf("Error")>=0){
                reset_form();
                alert(data);
            }
            else{
                enableItinerarios();
                set_itinerarios(data);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
        }
    });
}

/**
 * Comprueba si el itinerario existe para evitar manipulación en el formulario
 * @param {Number} id 
 */
function itinerario_seleccionado(idcarrera, iditinerario){
    $.ajax({
        url: '/async.php',
        dataType: 'json',
        type: 'post',
        data: "op=2&idcarrera="+idcarrera+"&iditinerario="+iditinerario,
        success: function( data, textStatus, jQxhr ){
            if(data.indexOf("Error")>=0){
                reset_form();
                alert(data);
            }
            else{
                enableSend();
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
        }
    });
}

/**
 * Elimina todos los elementos, salvo el primero, del selector de itinerarios
 * y añade los que se proporciona.
 * 
 * @param {*} itinerarios 
 */
function set_itinerarios(itinerarios){
    let last_id;

    let $it_selector = $('#selector-itinerario');
    $it_selector.children('option:not(:first)').remove();

    $.each(itinerarios, function (i, item) {
        last_id = item.id;
        $it_selector.append($('<option>', { 
            value: item.id,
            text : item.nombre 
        }));
    });

    if(itinerarios.length == 1){
        $("#selector-itinerario").val(last_id);
        enableSend();
    }
}

/**
 * Comprueba el estado del formulario y actúa en consecuencia
 */
function checkStatus(){
    let car = ($('#selector-carrera').val() != "none");
    let iti = ($('#selector-itinerario').val() != "none");
    let estado = 0;

    if(!car && !iti) estado = 0;
    else if (car && !iti) estado = 1;
    else if (!car && iti) estado = 0; //Estado imposible. Volvemos al principio
    else if (car && iti)  estado = 2;

    switch (estado) {
        case 1 : 
            disableSend()
            break;

        case 2 : 
            enableSend();
            break;
    
        default: 
            reset_form();
            break;
    }
}

/**
 * Comprueba que los datos son correctos y, en caso de serlo, envía al usuario al asistente.
 * En caso de no serlo, refresca la página para eliminar posibles manipulaciones de datos
 */
function submit(){
    let car = $('#selector-carrera').val();
    let iti = $('#selector-itinerario').val();
    $.ajax({
        url: '/async.php',
        dataType: 'json',
        type: 'post',
        data: "op=3&idcarrera="+car+"&iditinerario="+iti,
        success: function( data, textStatus, jQxhr ){
            if(data.indexOf("Error")>=0){
                reset_form();
                alert(data);
                window.location.href = "/";
            }
            else{
                reset_form();
                window.location.href = "/asistente.php";
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
        }
    });
    return false;
}

/**
 * Ejecuta submit y se queda esperando su respuesta
 */
function submitForm() {
    $.when(submit()).done(function(a1){
        if(a1 == "OK")
            return true;
        
        return false;
    });   
}