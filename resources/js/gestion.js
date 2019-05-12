// Válido en todas las páginas

$(document).ready(function(){
	var uri = window.location.toString();
	if (uri.indexOf("?") > 0) {
	    var clean_uri = uri.substring(0, uri.indexOf("&"));
	    window.history.replaceState({}, document.title, clean_uri);
    }
});

function borrar(){
    var seleccionados = $('#listado input[type="checkbox"]').filter(':checked').length
   
    if(seleccionados > 0){
        let r = confirm("Para confirmar el borrado, haz clic en 'Aceptar'");
        if (r == true) {
            $('#listado form').submit();
        }
    }
    else{
        alert('No hay ningún elemento seleccionado. No se borrará nada.');
    }    
}


// Facultades

function editar_facultad(id){
    if($('#accion-facultad').val() != "edit"){  
        let data = "op=6";
        data += "&id="+id;
        $.ajax({
            url: '/async',
            dataType: 'json',
            type: 'post',
            data: data,
            success: function( data, textStatus, jQxhr ){
                if(jQxhr.responseText.indexOf("Error")>=0){
                    alert(data);
                }
                else{
                    $('#accion-title').text('Editar facultad');
                    $('#nombre-facultad').val(data.nombre);
                    $('#campus-facultad').val(data.campus);
                    $('#id-facultad').val(data.id);
                    $('#accion-facultad').val('edit');
                    $('#submit-facultad').text('Guardar cambios');
                    $('#formulario form').append('<button type="button" id="cancelar-facultad" class="btn btn-warning w-100 mt-2" onclick="cancelar_editar_facultad()">Cancelar</button>')
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
            }
        });
    }
    else
        alert("Cancela la edición actual antes de editar otro elemento.")
}

function cancelar_editar_facultad(){
    $('#accion-title').text('Añadir facultad');
    $('#nombre-facultad').val('');
    $('#campus-facultad').val('');
    $('#id-facultad').val(0);
    $('#accion-facultad').val('add');
    $('#submit-facultad').text('Añadir');
    $('#cancelar-facultad').remove();
}


// Carreras

$('#form-carreras select').change(function(){ 
    let name = $(this).attr("name");
    let value = $(this).val();

    if(name == "facultad" && value != "none"){
        facultad_seleccionada(value);
    }
    else if(name != "facultad-dg"){
        $("#selector-facultad-dg").prop( "disabled", true );
        $('#selector-facultad-dg').children('option:not(:first)').remove();
    }
}); 

function vaciarDG(){
    let $it_selector = $('#selector-facultad-dg');
    $it_selector.children('option:not(:first)').remove();
    $it_selector.prop( "disabled", true );
}

function facultad_seleccionada(id, facultad_dg = null){
    let data = "op=7";
    data += "&id="+id;
    $.ajax({
        url: '/async',
        dataType: 'json',
        type: 'post',
        data: data,
        success: function( data, textStatus, jQxhr ){
            if(jQxhr.responseText.indexOf("Error")>=0){
                alert(data);
            }
            else{ 
                vaciarDG();
                
                let $it_selector = $('#selector-facultad-dg');
                $it_selector.children('option:not(:first)').remove();

                $.each(data, function (i, item) {
                    last_id = item.id;
                    $it_selector.append($('<option>', { 
                        value: item.id,
                        text : item.nombre 
                    }));
                });
                $it_selector.prop( "disabled", false );

                if(facultad_dg != null){
                    $("#selector-facultad-dg").val(facultad_dg);
                }
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
        }
    });
}

function editar_carrera(id){
    if($('#accion-carrera').val() != "edit"){ 
        let data = "op=8";
        data += "&id="+id;
        $.ajax({
            url: '/async',
            dataType: 'json',
            type: 'post',
            data: data,
            success: function( data, textStatus, jQxhr ){
                if(jQxhr.responseText.indexOf("Error")>=0){
                    alert(data);
                }
                else{
                    $('#accion-title').text('Editar carrera');
                    $('#nombre-carrera').val(data.nombre);

                    //$('#campus-facultad').val(data.campus);
                    // Capturar
                    $("#selector-facultad").val(data.id_facultad);
                    facultad_seleccionada(data.id_facultad, data.id_facultad_dg);

                    $('#id-carrera').val(data.id);
                    $('#accion-carrera').val('edit');
                    $('#submit-carrera').text('Guardar cambios');
                    $('#formulario form').append('<button type="button" id="cancelar-carrera" class="btn btn-warning w-100 mt-2" onclick="cancelar_editar_carrera()">Cancelar</button>')
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
            }
        });
    }
    else
        alert("Cancela la edición actual antes de editar otro elemento.")
}

function cancelar_editar_carrera(){
    $('#accion-title').text('Añadir carrera');
    $("#selector-facultad").val('');
    $('#nombre-carrera').val('');
    $('#campus-carrera').val('');
    $('#id-carrera').val(0);
    $('#accion-carrera').val('add');
    $('#submit-carrera').text('Añadir');
    $('#cancelar-carrera').remove();
    vaciarDG();
}


// Itinerario

function editar_itinerario(id){
    if($('#accion-itinerario').val() != "edit"){
        let data = "op=9";
        data += "&id="+id;
        $.ajax({
            url: '/async',
            dataType: 'json',
            type: 'post',
            data: data,
            success: function( data, textStatus, jQxhr ){
                if(jQxhr.responseText.indexOf("Error")>=0){
                    alert(data);
                }
                else{
                    $('#accion-title').text('Editar itinerario');
                    $("#selector-carrera").val(data.id_carrera);
                    $('#nombre-itinerario').val(data.nombre);
                    $('#id-itinerario').val(data.id);
                    $('#accion-itinerario').val('edit');
                    $('#submit-itinerario').text('Guardar cambios');
                    $('#formulario form').append('<button type="button" id="cancelar-itinerario" class="btn btn-warning w-100 mt-2" onclick="cancelar_editar_itinerario()">Cancelar</button>')
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
            }
        });
    }
    else
        alert("Cancela la edición actual antes de editar otro elemento.")

}

function cancelar_editar_itinerario(){
    $('#accion-title').text('Añadir itinerario');
    $('#selector-carrera').val('');
    $('#nombre-itinerario').val('');
    $('#id-itinerario').val(0);
    $('#accion-itinerario').val('add');
    $('#submit-itinerario').text('Añadir');
    $('#cancelar-itinerario').remove();
}


// Departamento

function editar_departamento(id){
    if($('#accion-departamento').val() != "edit"){
        let data = "op=10";
        data += "&id="+id;
        $.ajax({
            url: '/async',
            dataType: 'json',
            type: 'post',
            data: data,
            success: function( data, textStatus, jQxhr ){
                if(jQxhr.responseText.indexOf("Error")>=0){
                    alert(data);
                }
                else{
                    $('#accion-title').text('Editar departamento');
                    $("#selector-facultad").val(data.id_facultad);
                    $('#nombre-departamento').val(data.nombre);
                    $('#id-departamento').val(data.id);
                    $('#accion-departamento').val('edit');
                    $('#submit-departamento').text('Guardar cambios');
                    $('#formulario form').append('<button type="button" id="cancelar-departamento" class="btn btn-warning w-100 mt-2" onclick="cancelar_editar_departamento()">Cancelar</button>')
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
            }
        });
    }
    else
        alert("Cancela la edición actual antes de editar otro elemento.")

}

function cancelar_editar_departamento(){
    $('#accion-title').text('Añadir departamento');
    $('#selector-facultad').val('');
    $('#nombre-departamento').val('');
    $('#id-departamento').val(0);
    $('#accion-departamento').val('add');
    $('#submit-departamento').text('Añadir');
    $('#cancelar-departamento').remove();
}


// Asignaturas

$('#form-asignaturas select').change(function(){ 
    let name = $(this).attr("name");
    let value = $(this).val();

    if(name == "carrera" && value != ""){
        $("#selector-itinerario").prop( "disabled", true );
        let $it_selector = $('#selector-itinerario');
        $it_selector.children('option:not(:first)').remove();
        busca_itinerarios(value);
    }
    else if(name == "departamento-1" && value != ""){
        $("#selector-departamento-2").prop( "disabled", true );
        let $it_selector = $('#selector-departamento-2');
        $it_selector.children('option:not(:first)').remove();
        busca_departamentos(value);
    }

});

$('#filtro-asignaturas').change(function(){ 
    let carrera = $(this).val();

    let $selector = $('#listado form fieldset');
    $selector.children().remove();

    // poner solo las asignaturas que cumplan el filtro
    mostrar_listado_filtrado(carrera);
});

function mostrar_listado_filtrado(carrera){
    let data = "op=13";
        data += "&carrera="+carrera;
    $.ajax({
        url: '/async',
        dataType: 'json',
        type: 'post',
        data: data,
        success: function( data, textStatus, jQxhr ){
            if(jQxhr.responseText.indexOf("Error")>=0){
                alert(jQxhr.responseText);
            }
            else{
                let $selector = $('#listado form fieldset');

                $.each(data, function (i, item) {
                    let $box = $('<div>',{ class : 'gestion-list-element p-2 mb-2 border'})
                    $box.append($('<input>', { 
                        type  : 'checkbox',
                        name  : 'asignatura[]',
                        value : item.id,
                        id : 'asignatura-' + item.id
                    }));
    
                    $box.append($('<label>', { 
                        for  : 'asignatura-' + item.id,
                        text : '[' + item.id + '] ' + item.nombre + ' (' + item.carrera + ') (' + item.itinerario + ')'
                    }));
    
                    $box.append($('<span>', { 
                        class   : 'editar-button',
                        onclick : 'editar_asignatura(' + item.id + ')',
                        text    : 'Editar'
                    }));
    
                    $selector.append($box);
                });
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
        }
    });
}

function busca_itinerarios(id_carrera, seleccion = null){
    let data = "op=1";
        data += "&idcarrera="+id_carrera;
    $.ajax({
        url: '/async',
        dataType: 'json',
        type: 'post',
        data: data,
        success: function( data, textStatus, jQxhr ){
            if(jQxhr.responseText.indexOf("Error")>=0){
                alert(jQxhr.responseText);
            }
            else{
                let $it_selector = $('#selector-itinerario');
                $it_selector.children('option:not(:first)').remove();

                $.each(data, function (i, item) {
                    last_id = item.id;
                    $it_selector.append($('<option>', { 
                        value: item.id,
                        text : item.nombre 
                    }));
                });

                if(data.length > 0){
                    $("#selector-itinerario").prop( "disabled", false );
                }

                if(seleccion != null){
                    $("#selector-itinerario").val(seleccion);
                }
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
        }
    });
}

function busca_departamentos(id_departamento, seleccion = null){
    let data = "op=11";
    data += "&id="+id_departamento;
    $.ajax({
        url: '/async',
        dataType: 'json',
        type: 'post',
        data: data,
        success: function( data, textStatus, jQxhr ){
            if(jQxhr.responseText.indexOf("Error")>=0){
                alert(data);
            }
            else{ 
                let $dep_selector = $('#selector-departamento-2');
                $dep_selector.children('option:not(:first)').remove();

                $.each(data, function (i, item) {
                    last_id = item.id;
                    $dep_selector.append($('<option>', { 
                        value: item.id,
                        text : item.nombre 
                    }));
                });

                if(Object.keys(data).length > 1){
                    $dep_selector.prop( "disabled", false );
                }

                if(seleccion != null){
                    $dep_selector.val(seleccion);
                }
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
        }
    });
}

function editar_asignatura(id){
    if($('#accion-asignatura').val() != "edit"){
        let data = "op=12";
        data += "&id="+id;
        $.ajax({
            url: '/async',
            dataType: 'json',
            type: 'post',
            data: data,
            success: function( data, textStatus, jQxhr ){
                if(jQxhr.responseText.indexOf("Error")>=0){
                    alert(data);
                }
                else{
                    $('#accion-title').text('Editar asignatura');

                    $("#selector-carrera").val(data.id_carrera);

                    let itinerario = (data.itinerario == null) ? 0 : data.itinerario;
                    busca_itinerarios(data.id_carrera, itinerario);

                    $("#nombre-asignatura").val(data.nombre);

                    $("#abreviatura").val(data.abreviatura);

                    $("#selector-curso").val(data.curso);

                    $('#selector-departamento-1').val(data.id_departamento);

                    let dep2 = (data.id_departamento_dos == null) ? "" : data.id_departamento_dos;
                    busca_departamentos(data.id_departamento, dep2);

                    $("#gea").val(data.id);
                    $("#gea").prop( "disabled", true );
                    $("#id-asignatura").val(data.id);

                    $("#creditos").val(data.creditos);

                    $("#docentes").val(data.docentes);

                    $("#accion-asignatura").val('edit');

                    $('#submit-asignatura').text('Guardar cambios');

                    $('#formulario form').append('<button type="button" id="cancelar-asignatura" class="btn btn-warning w-100 mt-2" onclick="cancelar_editar_asignatura()">Cancelar</button>');
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
            }
        });
    }
    else
        alert("Cancela la edición actual antes de editar otro elemento.")
}

function cancelar_editar_asignatura(){
    $('#accion-title').text('Añadir asignatura');

    $("#selector-carrera").val(0);
    $('#selector-itinerario').val(0);

    $("#nombre-asignatura").val('');

    $("#abreviatura").val('');

    $("#selector-curso").val(0);

    $('#selector-departamento-1').val('');
    $('#selector-departamento-2').val('');

    $("#gea").val(0);
    $("#gea").prop( "disabled", false );
    $("#id").val(0);

    $("#creditos").val('');

    $("#docentes").val('');

    $("#accion-asignatura").val('add');

    $('#submit-asignatura').text('Añadir');
    $('#cancelar-asignatura').remove();
}


// Clases

class Clase{
    constructor(inicio, duracion){
        this.inicio   = inicio;
        this.duracion = duracion;
    }
}

var semana = {
    lunes : [],
    martes : [],
    miercoles : [],
    jueves : [],
    viernes:  [],
    sabado : [],
    add: function(dia, clase){
        switch (dia) {
            case "lunes":
                if(this.lunes[clase.inicio] != undefined){
                    $('#item-' + dia + '-' + clase.inicio).remove();
                }
                this.lunes[clase.inicio] = clase.duracion; 
            break;

            case "martes":
                if(this.martes[clase.inicio] != undefined){
                    $('#item-' + dia + '-' + clase.inicio).remove();
                }
                this.martes[clase.inicio] = clase.duracion; 
            break;

            case "miercoles":
                if(this.miercoles[clase.inicio] != undefined){
                    $('#item-' + dia + '-' + clase.inicio).remove();
                }
                this.miercoles[clase.inicio] = clase.duracion; 
            break;

            case "jueves":
                if(this.jueves[clase.inicio] != undefined){
                    $('#item-' + dia + '-' + clase.inicio).remove();
                }
                this.jueves[clase.inicio] = clase.duracion; 
            break;

            case "viernes":
                if(this.viernes[clase.inicio] != undefined){
                    $('#item-' + dia + '-' + clase.inicio).remove();
                }
                this.viernes[clase.inicio] = clase.duracion; 
            break;

            case "sabado":
                if(this.sabado[clase.inicio] != undefined){
                    $('#item-' + dia + '-' + clase.inicio).remove();
                }
                this.sabado[clase.inicio] = clase.duracion; 
            break;


            default: return null;
        }
    },
    rem: function(dia, inicio){
        switch (dia) {
            case "lunes":
                delete this.lunes[inicio];
            break;

            case "martes":
                delete this.martes[inicio];
            break;

            case "miercoles":
                delete this.miercoles[inicio];
            break;

            case "jueves":
                delete this.jueves[inicio];
            break;

            case "viernes":
                delete this.viernes[inicio];
            break;

            case "sabado":
                delete this.sabado[inicio];
            break;


            default: return null;
        }
        $('#item-' + dia + '-' + inicio).remove();
    }
};

function addClase(dia){
    let inicio_val = $('#hora-inicio-'+dia).val(); 
    let inicio_txt = $('#hora-inicio-' + dia + ' option:selected').text();

    let dura_val = $('#duracion-'+dia).val();
    let dura_txt = $('#duracion-' + dia + ' option:selected').text();

    if(inicio_val == "" || dura_val == "" || inicio_val == null || dura_val == null || inicio_val == undefined || dura_val == undefined){
        alert("Selecciones 'Hora de Inicio' y 'Duración'")
        return;
    }

    let clase = new Clase(inicio_val, dura_val);
    semana.add(dia, clase);

    $('#clases-added-'+dia).append('<span id="item-'+dia+'-'+inicio_val+'" class="clases-added-item">' + inicio_txt + ' - ' + dura_txt + '<i onclick="semana.rem(\'' + dia + '\', ' + clase.inicio + ')" class="fas fa-times ml-2"></i></span>');

    $('#hora-inicio-'+dia).val('');
    $('#duracion-'+dia).val('');
}

$('#form-add-clase select').change(function(){
    let name = $(this).attr("name");
    let value = $(this).val();

    let filtrar = false;
    
    if(name == "facultad" && value != ""){
        $("#selector-carrera").prop( "disabled", true );
        $("#selector-itinerario").prop( "disabled", true );
        
        add_clase_filtro_carreras(value);
        filtrar = true;

        $('#selector-carrera').val("");
        $('#selector-itinerario').val("");
        $('#selector-asignatura').val("");
    }
    else if(name == "carrera" && value != ""){
        $("#selector-itinerario").prop( "disabled", true );

        filtrar = true;
        add_clase_filtro_itinerarios(value);

        $('#selector-itinerario').val("");
        $('#selector-asignatura').val("");
    }
    else if(name == "itinerario" && value != ""){
        filtrar = true;

        $('#selector-asignatura').val("");
    }
    else if(name == "asignatura" && value != ""){
        $("#btn-add-clase").prop( "disabled", false );
    }

    if(filtrar){
        filtrar_asignaturas();
    }
})

function add_clase_filtro_carreras(facultad){
    let data = "op=17";
    data += "&facultad=" + facultad;

    $.ajax({
        url: '/async',
        dataType: 'json',
        type: 'post',
        data: data,
        success: function( data, textStatus, jQxhr ){
            let $dep_selector = $('#selector-carrera');
            $dep_selector.children('option:not(:first)').remove();

            $.each(data, function (i, item) {
                last_id = item.id;
                $dep_selector.append($('<option>', { 
                    value: item.id,
                    text : item.nombre
                }));
            });

            if(Object.keys(data).length > 0){
                $dep_selector.prop( "disabled", false );
            }
            else{
                $dep_selector.prop( "disabled", true );
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            $('body').text(jqXhr.responseText);
        }
    });
}

function add_clase_filtro_itinerarios(carrera){
    let data = "op=1";
        data += "&idcarrera=" + carrera;
    $.ajax({
        url: '/async',
        dataType: 'json',
        type: 'post',
        data: data,
        success: function( data, textStatus, jQxhr ){
            if(jQxhr.responseText.indexOf("Error")>=0){
                alert(jQxhr.responseText);
            }
            else{
                let $it_selector = $('#selector-itinerario');
                $it_selector.children('option:not(:first)').remove();

                $it_selector.append($('<option>', { 
                    value: 'c',
                    text : 'Común' 
                }));

                $.each(data, function (i, item) {
                    last_id = item.id;
                    $it_selector.append($('<option>', { 
                        value: item.id,
                        text : item.nombre 
                    }));
                });
                $("#selector-itinerario").prop( "disabled", false );
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
        }
    });
}

function filtrar_asignaturas(){
    let data = "op=16";

    data += ($('#selector-facultad').val() != null) ? "&facultad=" + $('#selector-facultad').val()  : "";
    data += ($('#selector-carrera').val() != null) ? "&carrera=" + $('#selector-carrera').val() : "";
    data += ($('#selector-itinerario').val()  != null) ? "&itinerario=" + $('#selector-itinerario').val()  : "";

    $.ajax({
        url: '/async',
        dataType: 'json',
        type: 'post',
        data: data,
        success: function( data, textStatus, jQxhr ){
            let $dep_selector = $('#selector-asignatura');
            $dep_selector.children('option:not(:first)').remove();

            $.each(data, function (i, item) {
                last_id = item.id;
                $dep_selector.append($('<option>', { 
                    value: item.id,
                    text : item.nombre + ' (' + item.id + ')'
                }));
            });

            if(Object.keys(data).length > 0){
                $dep_selector.prop( "disabled", false );
            }
            else{
                $dep_selector.prop( "disabled", true );
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            $('body').text(jqXhr.responseText);
        }
    });
}

function commit(){
    let count = semana.lunes.length + semana.martes.length + semana.miercoles.length + semana.jueves.length + semana.viernes.length + semana.sabado.length;

    if(count > 0){
        $('#list-elements').text("HAY CLASES!!! Pero aun no las he podido rescatar");

        let text = "";

        if(semana.lunes.length > 0){
            text += "L: ";

            for (let i = 0; i < semana.lunes.length; i++) {
                if(semana.lunes[i] != undefined){
                    text += parseHoras(i, semana.lunes[i]);

                    if(i < semana.lunes.length - 1)
                        text += ", ";
                }
            }
        }

        if(semana.martes.length > 0){
            text += "<br />";
            text += "M: ";

            for (let i = 0; i < semana.martes.length; i++) {
                if(semana.martes[i] != undefined){
                    text += parseHoras(i, semana.martes[i]);

                    if(i < semana.martes.length - 1)
                        text += ", ";
                }
            }
        }

        if(semana.miercoles.length > 0){
            text += "<br />";
            text += "X: ";

            for (let i = 0; i < semana.miercoles.length; i++) {
                if(semana.miercoles[i] != undefined){
                    text += parseHoras(i, semana.miercoles[i]);

                    if(i < semana.miercoles.length - 1)
                        text += ", ";
                }
            }
        }

        if(semana.jueves.length > 0){
            text += "<br />";
            text += "J: ";

            for (let i = 0; i < semana.jueves.length; i++) {
                if(semana.jueves[i] != undefined){
                    text += parseHoras(i, semana.jueves[i]);

                    if(i < semana.jueves.length - 1)
                        text += ", ";
                }
            }
        }

        if(semana.viernes.length > 0){
            text += "<br />";
            text += "V: ";

            for (let i = 0; i < semana.viernes.length; i++) {
                if(semana.viernes[i] != undefined){
                    text += parseHoras(i, semana.viernes[i]);

                    if(i < semana.viernes.length - 1)
                        text += ", ";
                }
            }
        }

        if(semana.sabado.length > 0){
            text += "<br />";
            text += "S: ";

            for (let i = 0; i < semana.sabado.length; i++) {
                if(semana.sabado[i] != undefined){
                    text += parseHoras(i, semana.sabado[i]);

                    if(i < semana.sabado.length - 1)
                        text += ", ";
                }
            }
        }

        $('#list-elements').html(text);

        $('#submit-clase').prop( "disabled", false );
    }

    $('#horario-clase').val(JSON.stringify(semana));
}

function parseHoras(inicio, duracion){
    let inicio_real = 8 + parseInt(inicio/2);

    let text = "";
    text += inicio_real +":00 ~ ";

    switch (duracion) {
        case "1":
            text += inicio_real + ":50"
        break;
        case "2":
            inicio_real++;
            text += inicio_real + ":40"
        break;
        case "3":
            inicio_real += 2;
            text += inicio_real + ":30"
        break;
        case "4":
            inicio_real += 3;
            text += inicio_real + ":20"
        break;
        
        default: break;
    }

    return text;
}

// Docentes

$('#selector-facultad-docente').change(function(){ 
    let facultad = $(this).val();
    docente_editar_departamento(facultad);
});

function docente_editar_departamento(facultad, seleccion = null){
    if(facultad != ""){
        let data = "op=14";
        data += "&idfacultad="+facultad;
        $.ajax({
            url: '/async',
            dataType: 'json',
            type: 'post',
            data: data,
            success: function( data, textStatus, jQxhr ){
                if(jQxhr.responseText.indexOf("Error")>=0){
                    alert(data);
                }
                else{ 
                    let $dep_selector = $('#selector-departamento-docente');
                    $dep_selector.children('option:not(:first)').remove();
    
                    $.each(data, function (i, item) {
                        last_id = item.id;
                        $dep_selector.append($('<option>', { 
                            value: item.id,
                            text : item.nombre 
                        }));
                    });
    
                    if(Object.keys(data).length > 1){
                        $dep_selector.prop( "disabled", false );
                    }

                    if(seleccion != null){
                        $dep_selector.val(seleccion);
                    }
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
                alert('');
            }
        });
    }
}

function editar_docente(id){
    if($('#accion-docente').val() != "edit"){
        $('#accion-docente').val('edit');

        let data = "op=15";
        data += "&id="+id;
        $.ajax({
            url: '/async',
            dataType: 'json',
            type: 'post',
            data: data,
            success: function( data, textStatus, jQxhr ){
                if(jQxhr.responseText.indexOf("Error")>=0){
                    alert(data);
                }
                else{
                    //$docentes[] = array('id' => $r['id'], 'nombre' => $r['nombre'], 'departamento' => $r['departamento']);
                    $('#accion-title').text('Editar Docente');

                    $('#selector-facultad-docente').val(data.facultad);
                    
                    docente_editar_departamento(data.facultad, data.departamento);

                    $('#id-docente').val(data.id);

                    $('#nombre-docente').val(data.nombre);

                    $('#orden-docente').val(data.orden);

                    $('#usuario-docente').val(data.usuario);
                    $('#user-docente').val(data.usuario);
                    $('#usuario-docente').prop( "disabled", true );
                    
                    $('#password-docente').val("¿Te crees hacker o qué?");

                    $('#submit-docente').text('Guardar cambios');

                    $('#formulario form').append('<button type="button" id="cancelar-docente" class="btn btn-warning w-100 mt-2" onclick="cancelar_editar_docente()">Cancelar</button>');
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
            }
        });
    }
    else
        alert("Cancela la edición actual antes de editar otro elemento.")
}

function cancelar_editar_docente(){
    $('#accion-title').text('Añadir Docente');
    $('#accion-docente').val('add');

    $('#selector-facultad-docente').val('');

    $('#selector-departamento-docente').val('');
    $('#selector-departamento-docente').children('option:not(:first)').remove();
    $('#selector-departamento-docente').prop( "disabled", true );

    $('#id-docente').val(0);

    $('#nombre-docente').val('');

    $('#usuario-docente').val('');
    $('#user-docente').val('');
    
    $('#password-docente').val('');

    $('#password-docente').prop( "disabled", false );

    $('#submit-docente').text('Añadir');

    $('#cancelar-docente').remove();
}
