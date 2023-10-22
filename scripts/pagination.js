function changePage(page) {
    $.ajax({
        type: "POST",
        url: "procesar_busqueda.php",
        data: {
            jobSearchText: "<?php echo $searchTerm; ?>",
            sijainti: $("#sijainti").val(),
            julkaistu: $("#julkaistu").val(),
            palvelusuhde: $("#palvelusuhde").val(),
            tyokieli: $("#tyokieli").val(),
            tyoaika: $("#tyoaika").val(),
            vaatimukset: $("#vaatimukset").val(),
            tehtava: $("#tehtava").val(),
            page: page // Envía el número de página actual
        },
        success: function (response) {
            $("#searchResults").html(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $("#searchResults").html("Error fetching results. Please try again later.");
        }
    });
}
