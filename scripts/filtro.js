// Esta función realiza la solicitud AJAX con los valores actuales de búsqueda y filtros.
function fetchJobs() {
    var keyword = $("#jobSearchText").val();
    var sijainti = $("#sijainti").val();
    var julkaistu = $("#julkaistu").val();
    var palvelusuhde = $("#palvelusuhde").val();
    var tyonkieli = $("#tyonkieli").val();
    var tyoaika = $("#tyoaika").val();
    var ala = $("#ala").val();

    // Realizar solicitud Ajax para procesar la búsqueda y los filtros
    $.ajax({
        type: "POST",  // Cambiado a POST
        url: "procesar_busqueda.php",
        data: {
            jobSearchText: keyword,
            Sijainti: sijainti,
            Julkaistu: julkaistu,
            PalveluSuhde: palvelusuhde,
            TyoKieli: tyonkieli,
            TyoAika: tyoaika,
            Ala: ala
        },
        success: function (response) {
            // Actualizar los resultados de la búsqueda en la página
            $("#searchResults").html(response);
        }
    });
}

$("#searchButton").on("click", fetchJobs);
$("#sijainti, #julkaistu, #palvelusuhde, #tyonkieli, #tyoaika, #vaatimukset, #ala").on("input", fetchJobs);

// Función para limpiar todos los filtros
function clearFilters() {
    $("#jobSearchText").val(''); // limpia el campo de búsqueda
    $("#sijainti").val(''); // limpia el filtro sijainti
    $("#julkaistu").val(''); // limpia el filtro julkaistu
    $("#palvelusuhde").val(''); // limpia el filtro palvelusuhde
    $("#tyonkieli").val(''); // limpia el filtro tyonkieli
    $("#tyoaika").val(''); // limpia el filtro tyoaika
    $("#ala").val(''); // limpia el filtro ala
    // ... (Haz lo mismo para cualquier otro filtro que tengas)

    fetchJobs(); // Vuelve a cargar todos los trabajos sin filtros
}

// Agrega un listener para el botón de limpiar filtros
$("#clearFilters").on("click", clearFilters);