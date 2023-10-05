$(document).ready(function () {
    $("#jobSearchText, #sijainti, #julkaistu, #palvelusuhde, #tyonkieli, #tyoaika, #vaatimukset").on("change keyup", function () {
        // Obtener valores de búsqueda y filtros
        var keyword = $("#jobSearchText").val();
        var sijainti = $("#sijainti").val();
        var julkaistu = $("#julkaistu").val();
        var palvelusuhde = $("#palvelusuhde").val();
        var tyonkieli = $("#tyonkieli").val();
        var tyoaika = $("#tyoaika").val();
        var vaatimukset = $("#vaatimukset").val();

        // Realizar solicitud Ajax para procesar la búsqueda y los filtros
        $.ajax({
            type: "GET",
            url: "procesar_busqueda.php",
            data: {
                jobSearchText: keyword,
                Sijainti: sijainti,
                Julkaistu: julkaistu,
                PalveluSuhde: palvelusuhde,
                TyoKieli: tyonkieli,
                TyoAika: tyoaika,
                Vaatimukset: vaatimukset
            },
            success: function (response) {
                // Actualizar los resultados de la búsqueda en la página
                $("#searchResults").html(response);
            }
        });
    });
});
