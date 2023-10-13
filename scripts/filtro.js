// Esta función realiza la solicitud AJAX con los valores actuales de búsqueda y filtros.
function fetchJobs() {
    var keyword = $("#jobSearchText").val();
    var sijainti = $("#sijainti").val();
    var julkaistu = $("#julkaistu").val();
    var palvelusuhde = $("#palvelusuhde").val();
    var tyonkieli = $("#tyonkieli").val();
    var tyoaika = $("#tyoaika").val();
    var tehtava = $("#tehtava").val();
    var offersPerPage = $("#offersPerPage").val(); // Agregamos esta línea

    // Realizar solicitud Ajax para procesar la búsqueda y los filtros
    $.ajax({
        type: "POST",
        url: "procesar_busqueda.php",
        data: {
            jobSearchText: keyword,
            sijainti: sijainti,
            julkaistu: julkaistu,
            palvelusuhde: palvelusuhde,
            tyokieli: tyonkieli,
            tyoaika: tyoaika,
            tehtava: tehtava,
            OffersPerPage: offersPerPage // Agregamos esta línea
        },
        success: function (response) {
            $("#searchResults").html(response);
        }
    });
}

$("#offersPerPage").change(fetchJobs); // Agregamos un listener para el desplegable

$("#searchButton").on("click", fetchJobs);
$("#sijainti, #julkaistu, #palvelusuhde, #tyonkieli, #tyoaika, #vaatimukset, #tehtava").on("input", fetchJobs);

// Función para limpiar todos los filtros
function clearFilters() {
    $("#jobSearchText").val(''); // limpia el campo de búsqueda
    $("#sijainti").val(''); // limpia el filtro sijainti
    $("#julkaistu").val(''); // limpia el filtro julkaistu
    $("#palvelusuhde").val(''); // limpia el filtro palvelusuhde
    $("#tyonkieli").val(''); // limpia el filtro tyonkieli
    $("#tyoaika").val(''); // limpia el filtro tyoaika
    $("#tehtava").val(''); // limpia el filtro ala
    // ... (Haz lo mismo para cualquier otro filtro que tengas)

    fetchJobs(); // Vuelve a cargar todos los trabajos sin filtros
}

// Agrega un listener para el botón de limpiar filtros
$("#clearFilters").on("click", clearFilters);
