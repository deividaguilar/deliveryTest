/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    $('#controles').accordion({
        heightStyle: "content"
    });

    $("#respuesta").dialog({
        autoOpen: false,
        height: 350,
        width: 350,
        show: {
            effect: "blind",
            duration: 1000
        },
        hide: {
            effect: "explode",
            duration: 1000
        }
    });

    $("#fileuploader").uploadFile({
        url: "/web/app.php/admin/importar/",
        method: 'POST',
        allowedTypes: "xlsx",
        fileName: "archivoSubido",
        multiple: false,
        returnType: "json",
        onSubmit: function () {

        },
        onSuccess: function (files, data, xhr) {

            if (data.error != '') {
                $("#respuesta").html(data.error);
            } else {
                $("#respuesta").html(data.proceso);
            }
            $("#respuesta").dialog("open");
        },
        onError: function (jqXHR, textStatus, errorThrown) {
            var message = "Se presenta el siguiente incidente al intentar ejectuar esta consulta. <br /> Codigo: " + jqXHR.status + " <br />" + textStatus + "<br />" + errorThrown;
            $("#respuesta").html(message);
            $("#respuesta").dialog("open");

        }
    });

});