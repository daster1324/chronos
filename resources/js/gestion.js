$(document).ready(function(){
	var uri = window.location.toString();
	if (uri.indexOf("?") > 0) {
	    var clean_uri = uri.substring(0, uri.indexOf("&"));
	    window.history.replaceState({}, document.title, clean_uri);
	}
});

function borrar(){
    let r = confirm("Para confirmar el borrado, haz clic en 'Aceptar'");
    if (r == true) {
       $('#listado form').submit();
    }
}

function editar_facultad(id){
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
                $('#submit-facultad').text('Editar');
                $('#formulario form').append('<button type="button" id="cancelar-facultad" class="btn btn-warning w-100 mt-2" onclick="cancelar_editar_facultad()">Cancelar</button>')
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
        }
    });
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

