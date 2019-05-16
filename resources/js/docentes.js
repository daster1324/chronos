var preferencias = [];

$(document).ready(function(){
	var uri = window.location.toString();
	if (uri.indexOf("?") > 0) {
	    var clean_uri = uri.substring(0, uri.indexOf("&"));
	    window.history.replaceState({}, document.title, clean_uri);
    }
});

$('.preferencia-asignatura').change(function (e) { 
    let name = $(this).attr("name");
    let value = $(this).val();

    if(name == "preferencia-1" && value != "none"){
       limpia_bloquea(2);
       preferencias[0] = value;
       update_preferencias();
       busca_filtrado(2);
    }
    else if(name == "preferencia-2" && value != "none"){
       limpia_bloquea(3);
       preferencias[1] = value;
       update_preferencias();
       busca_filtrado(3);
    }
    else if(name == "preferencia-3" && value != "none"){
       limpia_bloquea(4);
       preferencias[2] = value;
       update_preferencias();
       busca_filtrado(4);
    }
    else if(name == "preferencia-4" && value != "none"){
       limpia_bloquea(5);
       preferencias[3] = value;
       update_preferencias();
       busca_filtrado(5);
    }
    else if(name == "preferencia-5" && value != "none"){
       limpia_bloquea(6);
       preferencias[4] = value;
       update_preferencias();
       busca_filtrado(6);
    }
    else if(name == "preferencia-6" && value != "none"){
        preferencias[5] = value;
        update_preferencias();
    }
});

function limpia_bloquea(start){
    for (let index = start; index < 7; index++) {
        $("#selector-preferencia-"+index).prop( "disabled", true );
        $('#selector-preferencia-'+index).children('option:not(:first)').remove();
        $('#selector-preferencia-'+index).val('');
    }
    
    preferencias.splice(start-1, 7-start);
}

function update_preferencias(){
    $("#preferencias").val(JSON.stringify(preferencias));
    console.log(JSON.stringify(preferencias));
}

function busca_filtrado(next){
    let data = "op=18";
    data += "&seleccion="+ $("#preferencias").val();
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
                let $it_selector = $('#selector-preferencia-' + next);
                $it_selector.children('option:not(:first)').remove();

                $.each(data, function (i, item) {
                    last_id = item.id;
                    $it_selector.append($('<option>', { 
                        value: item.id,
                        text : '(' + item.carrera + ') [' + item.id + '] ' + item.nombre 
                    }));
                });
                $it_selector.prop( "disabled", false );

            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            alert('Error');
        }
    });
}

