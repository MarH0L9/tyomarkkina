// Esta función realiza la solicitud AJAX con los valores actuales de búsqueda y filtros.
function fetchJobs() {
    var keyword = $("#jobSearchText").val();
    var sijainti = $("#sijainti").val();
    var julkaistu = $("#julkaistu").val();
    var palvelusuhde = $("#palvelusuhde").val();
    var tyonkieli = $("#tyonkieli").val();
    var tyoaika = $("#tyoaika").val();
    var tehtava = $("#tehtava").val();
    
    

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
            
        },
        success: function (response) {
            $("#searchResults").html(response);
    }
});
}

$("#offersPerPage").change(fetchJobs); // Agregamos un listener para el desplegable

$("#searchButton").on("click", fetchJobs);
$("#sijainti, #julkaistu, #palvelusuhde, #tyonkieli, #tyoaika, #vaatimukset, #tehtava").on("input", fetchJobs);
