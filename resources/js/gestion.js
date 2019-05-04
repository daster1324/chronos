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
            alert("Esto ha petao muy ricamente");
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
                alert(jqXhr.textResponse);
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

$('#container-selector-carrera select').change(function(){ 
    let name = $(this).attr("name");
    let value = $(this).val();

    if(name == "carrera" && value != ""){
        $('#container-selector-itinerario').hide();
        $("#container-selector-itinerario selec").prop( "disabled", true );
        let $it_selector = $('#selector-itinerario');
        $it_selector.children('option:not(:first)').remove();
        busca_itinerarios(value);
        // Consultar itinerarios y decidir si mostrar o no
        // Si no hay itinerarios, mostrar "Itinerario Único" y ya
        // Dicho "Itinerario Único" no existe, en la BD es un NULL
    }
    else if(name != "facultad-dg"){
        $("#selector-facultad-dg").prop( "disabled", true );
        $('#selector-facultad-dg').children('option:not(:first)').remove();
    }
});

function busca_itinerarios(id_carrera){
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
                let last_id;

                let $it_selector = $('#selector-itinerario');
                $it_selector.children('option:not(:first)').remove();

                $.each(data, function (i, item) {
                    last_id = item.id;
                    $it_selector.append($('<option>', { 
                        value: item.id,
                        text : item.nombre 
                    }));
                });

                if(data.length > 1){
                    $('#container-selector-itinerario').show();
                    $("#selector-itinerario").prop( "disabled", false );
                }
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            alert("ESTO HA PETAO MUY FUERTEMENTE")
        }
    });
}

//TODO: NO HAY NADA CAMBIADO. ADAPTAR
function editar_asignaturas(id){
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

function cancelar_editar_asignaturas(){
    $('#accion-title').text('Añadir departamento');
    $('#selector-facultad').val('');
    $('#nombre-departamento').val('');
    $('#id-departamento').val(0);
    $('#accion-departamento').val('add');
    $('#submit-departamento').text('Añadir');
    $('#cancelar-departamento').remove();
}