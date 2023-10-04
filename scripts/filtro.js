$(document).ready(function () {
    $("#jobSearchText, #sijainti, #julkaistu, #continuity, #language, #workTime, #employment").on("change keyup", function () {
        // Obtener valores de búsqueda y filtros
        var keyword = $("#jobSearchText").val();
        var sijainti = $("#sijainti").val();
        var julkaistu = $("#julkaistu").val();
        var continuity = $("#continuity").val();
        var language = $("#language").val();
        var workTime = $("#workTime").val();
        var employment = $("#employment").val();

        // Realizar solicitud Ajax para procesar la búsqueda y los filtros
        $.ajax({
            type: "GET",
            url: "procesar_busqueda.php",
            data: {
                jobSearchText: keyword,
                sijainti: sijainti,
                julkaistu: julkaistu,
                continuity: continuity,
                language: language,
                workTime: workTime,
                employment: employment
            },
            success: function (response) {
                // Actualizar los resultados de la búsqueda en la página
                $("#searchResults").html(response);
            }
        });
    });
});