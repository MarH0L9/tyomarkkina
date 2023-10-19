function handleSearchAndFilters() {
    // Obtén el término de búsqueda
    var keyword = $('#jobSearchText').val();

    // Obtén los valores de los filtros
    var sijainti = $('#sijainti').val();
    var julkaistu = $('#julkaistu').val();
    var tehtava = $('#tehtava').val();
    var tyoaika = $('#tyoaika').val();
    var tyokieli = $('#tyokieli').val();
    var palvelusuhde = $('#palvelusuhde').val();


    console.log('Sijainti:', sijainti);
    console.log('Julkaistu:', julkaistu);
    console.log('Tehtävä:', tehtava);
    console.log('Työaika:', tyoaika);
    console.log('Tyokieli:', tyokieli);
    console.log('Palvelusuhde:', palvelusuhde);

    // Realiza una solicitud AJAX al servidor para obtener resultados
    $.ajax({
        type: 'POST',
        url: 'procesar_busqueda.php',
        data: {
            jobSearchText: keyword,
            sijainti: sijainti,
            julkaistu: julkaistu,
            tehtava: tehtava,
            tyoaika: tyoaika,
            tyokieli: tyokieli,
            palvelusuhde: palvelusuhde
        },
        success: function(response) {
            // Actualiza el contenedor de resultados con la nueva información
            $('#searchResults').html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Maneja errores aquí
        }
    });
}

// Asocia el evento 'click' del botón de búsqueda al manejo de búsqueda y filtros
$('#searchButton').click(function() {
    handleSearchAndFilters();
});

// Asocia el evento 'change' de los filtros al manejo de búsqueda y filtros
$('.filter-select').change(function() {
    handleSearchAndFilters();
});

$("#sijainti, #julkaistu, #palvelusuhde, #tyokieli, #tyoaika, #vaatimukset, #tehtava").on("input", handleSearchAndFilters);


