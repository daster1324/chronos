/*******************************/
/*          ASISTENTE          */
/*******************************/
class Asignatura {
    constructor(id, nombre, abreviatura, creditos) {
        this.id = id;
        this.nombre = nombre;
        this.abreviatura = abreviatura;
        this.creditos = creditos;
    }
}

class Clase {
    constructor(dia, hora, abreviatura) {
        this.dia = dia;
        this.hora = hora;
        this.abreviatura = abreviatura;
    }
}

var asignaturas = {};

var user= {
    asignaturas : {},
    clases      : {},
    creditos    : 0,
    cuatrimestre: 1,
    addAsig     : function(asignatura){

        if (typeof this.asignaturas[asignatura.id] === 'undefined') {
            this.asignaturas[asignatura.id] = asignatura;
            
            this.creditos += parseFloat(asignatura.creditos);
            $('#creditos').text(this.creditos);

            appendAsignatura(asignatura);
        }
        else{
            alert("La asignatura «" + asignatura.nombre + "» ya está insertada.")
        }
    },
    addClases   : function(clases){
        //TODO: recibe un array con clases de una asignatura y las almacena


    },
    remAsig     : function(id) {
        this.creditos -= this.asignaturas[id].creditos;
        $('#creditos').text(this.creditos);

        $(".asignatura-"+id).remove();
        delete this.asignaturas[id]
    },
    remAll      : function(){
        this.creditos = 0;
        $('#creditos').text(this.creditos);

        $("#asignaturas-container .asignatura").remove();
        this.asignaturas = [];
        this.clases = [];

        cuatrimestre = 1;
    }
};

$(function () {
    $('[data-toggle="popover"]').popover({
        trigger: 'hover',
        placement: 'right'
    });
  })

/////////////////////////////////////////////////////////////////////////////////////////////

// Detectar cambios en el formulario de Añadir Asignatura
var handler = $('#form-add-asignatura select').change(function(){ 

    // 1º Almacena nombre, id css e id del option
    let name = $(this).attr("name");
    let value = $(this).val();

    if(name == "curso" && value != "none"){
        cleanAsignaturas();
        disableAsignaturas();
        
        curso_seleccionado(value); 
    }

    if(name == "asignatura" && value != "none"){
        asignatura_seleccionada(value);
    }

    checkStatus();
});

function curso_seleccionado(curso) {
    let data = "op=4";
    data += "&curso="+curso;
    $.ajax({
        url: '/async',
        dataType: 'json',
        type: 'post',
        data: data,
        success: function( data, textStatus, jQxhr ){
            if(data.indexOf("Error")>=0){
                reset_form();
                alert(data);
            }
            else{
                enableAsignaturas();
                set_asignaturas(data);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
        }
    });
}

function set_asignaturas(asignaturas){
    let last_id;

    let $it_selector = $('#addasignatura-asignatura');
    $it_selector.children('option:not(:first)').remove();

    $.each(asignaturas, function (i, item) {
        last_id = item.id;
        $it_selector.append($('<option>', { 
            value: item.id,
            text : item.nombre 
        }));
        window.asignaturas[item.id] = new Asignatura(item.id, item.nombre, item.abreviatura, item.creditos);
    });

    if(asignaturas.length == 1){
        $("#addasignatura-asignatura").val(last_id);
        enableAdd();
    }
}

function asignatura_seleccionada(idasignatura){
    let cur = $('#addasignatura-curso').val();
    let asi = idasignatura;

    let data = "op=5"
    data += "&curso="+cur;
    data += "&idasignatura="+asi;

    $.ajax({
        url: '/async',
        dataType: 'json',
        type: 'post',
        data: data,
        success: function( data, textStatus, jQxhr ){
            if(data.indexOf("Error")>=0){
                alert(data);
            }
            else{
                enableAdd();
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
        }
    });
    return false;
}


/**
 * Comprueba el estado del formulario y actúa en consecuencia
 */
function checkStatus(){
    let cur = ($('#addasignatura-curso').val() != "none");
    let asi = ($('#addasignatura-asignatura').val() != "none");
    let estado = 0;

    if(!cur && !asi) estado = 0;
    else if (cur && !asi) estado = 1;
    else if (!cur && asi) estado = 0; //Estado imposible. Volvemos al principio
    else if (cur && asi)  estado = 2;

    switch (estado) {
        case 1 : 
            disableAdd()
            break;

        case 2 : 
            enableAdd();
            break;
    
        default: 
            reset_form();
            break;
    }
}

/**
 * Limpia  el dropdown de los itinerarios
 * Deshabilita dicho dropdown y el botón de envío
 */
function reset_form() {
    $("#addasignatura-curso").val('none');
    cleanAsignaturas();
    disableAsignaturas();
    disableAdd();
}

/**
 * Habilita el dropdown de los asignaturas
 */
function enableAsignaturas() { $("#addasignatura-asignatura").prop( "disabled", false ); }

/**
 * Deshabilita el dropdown de los asignaturas
 */
function disableAsignaturas(){ $("#addasignatura-asignatura").prop( "disabled", true ); }

/**
 * Elimina todos los elementos del dropown salvo el primero
 */
function cleanAsignaturas() {  $('#addasignatura-asignatura').children('option:not(:first)').remove(); }

/**
 * Habilita el botón de envío
 */
function enableAdd() { $("#addasignatura-add").prop( "disabled", false ); }

/**
 * Deshabilita botón de envío
 */
function disableAdd(){ $("#addasignatura-add").prop( "disabled", true ); }

function appendAsignatura(asignatura){
    let node = `<div data="`+asignatura.id+`" class="asignatura border border-light bg-dark asignatura-`+asignatura.id+`">
        <div class="sort-asignatura">
            <i class="fas fa-sort"></i>
        </div>
        <div class="info-asignatura">
            <div class="asignatura-data">
                <span class="asignatura-name" title="`+asignatura.nombre+`">`+asignatura.nombre+`</span>
                <span class="abreviatura">(`+asignatura.abreviatura+`)</span>
            </div> 
            <span>`+asignatura.creditos+` créditos</span>
        </div>
        <div class="rem-asignatura">
            <i class="fas fa-times" onclick="quitarAsignatura(`+asignatura.id+`)"></i>
        </div>                        
    </div>`;

    $('#asignaturas-container').append(node);
}

$( function() {
    $( "#asignaturas-container" ).sortable();
    $( "#asignaturas-container" ).disableSelection();
} );

function cambiarCuatrimestre(){
    // Vaciar horario
    limpiarSemana();

    // Mostrar datos del otro cuatrimestre.
    if(user.cuatrimestre == 1){
        user.cuatrimestre = 2;
        $('#cuatrimestre').text("Se está mostrando el segundo cuatrimestre");
    }
    else{
        user.cuatrimestre = 1;
        $('#cuatrimestre').text("Se está mostrando el primer cuatrimestre");
    }

    //TODO: ¿A qué estás esperando para hacer esta parte?
    

}

function addAsignatura(){    
    let cur = $('#addasignatura-curso').val();
    let asi = $('#addasignatura-asignatura').val();

    let data = "op=5"
    data += "&curso="+cur;
    data += "&idasignatura="+asi;

    $.ajax({
        url: '/async',
        dataType: 'json',
        type: 'post',
        data: data,
        success: function( data, textStatus, jQxhr ){
            if(data.indexOf("Error")>=0){
                alert(data);
            }
            else{
                reset_form();
                user.addAsig(asignaturas[asi]);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
        }
    });
    return false;
}

function quitarAsignatura(idasignatura){
    user.remAsig(idasignatura);
}

function vaciarHorario(){
    user.remAll();

    limpiarSemana();
}

function procesarHorario(){
    if(Object.keys(user.asignaturas) == 0){
        alert("Primero, añade algunas asignaturas");
        return;
    }
    let listado_ordenado = [];
    let i = 0;
    $('.asignatura ').each(function(){
        listado_ordenado[i] = user.asignaturas[$(this).attr('data')];
        i++;
    });

    let data = "op=19";
        data += "&asignaturas="+JSON.stringify(user.asignaturas);
        data += "&disponibilidad="+ $('#selector-disponibilidad').val();
        $.ajax({
            url: '/async',
            dataType: 'json',
            type: 'post',
            data: data,
            success: function( data, textStatus, jQxhr ){
                if(jQxhr.responseText.indexOf("Error")>=0){
                    alert("Devuelve algo 'correcto', pero error: " + data);
                }
                else{
                   alert('Todo va bien');
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
                alert('Error');
                $('body').append(jqXhr.responseText);
            }
        });

}

function exportar(){
    alert("Exportando");
    //TODO: ¿A qué estás esperando para hacer esta parte?
}

function limpiarSemana(){
    $(".casillero.bg-secondary").text("");
    $(".casillero.bg-secondary").toggleClass("hora-vacia")
    $(".casillero.bg-secondary").toggleClass("bg-secondary");
}

// Trigger de los dropdowns de addAsignatura
var handler = $('#form-add-asignatura select').change(function(){ 

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
