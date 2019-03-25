/***************************/
/*          INDEX          */
/***************************/


// Trigger de los dropdowns
var handler = $('#form-inicial select').change(function(){ 

    // 1º Almacena nombre, id css e id del option
    let name = $(this).attr("name");
    let value = $(this).val();

    if(name == "carrera" && value != "none"){
        carrera_seleccionada(value);
    }

    checkStatus();
});


/**
 * Limpia  el dropdown de los itinerarios
 * Deshabilita dicho dropdown y el botón de envío
 */
function reset_form() {
    $("#selector-itinerario").prop( "disabled", true );
    $('#selector-itinerario').children('option:not(:first)').remove();

    disableSend();
}

/**
 * Habilita el dropdown de los itinerarios
 */
function enableItinerarios() { $("#selector-itinerario").prop( "disabled", false ); }

/**
 * Deshabilita botón de envío
 */
function disableSend(){ $("#boton-enviar").prop( "disabled", true ); }

/**
 * Habilita el botón de envío
 */
function enableSend() { $("#boton-enviar").prop( "disabled", false ); }

/**
 * Busca los itinerarios asociados a la carrera seleccionada
 * @param {Number} id - ID de la carrera seleccionada
 */
function carrera_seleccionada(id){
    $.ajax({
        url: '/async.php',
        dataType: 'json',
        type: 'post',
        data: "idcarrera="+id,
        success: function( data, textStatus, jQxhr ){
            response = data;
            if(data.indexOf("Error")>=0){
                reset_form();
                alert(data);
            }
            else{
                enableItinerarios();
                set_itinerarios(data);
            }
            console.log(data);
        },
        error: function( jqXhr, textStatus, errorThrown ){
            console.log("error: " +  errorThrown );
        }
    });
}

function set_itinerarios(itinerarios){
    $('#selector-itinerario').children('option:not(:first)').remove();
    $.each(itinerarios, function (i, item) {
        $('#selector-itinerario').append($('<option>', { 
            value: item.id,
            text : item.nombre 
        }));
    });
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