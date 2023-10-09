
<!DOCTYPE html>
<html lang="fi">
<head>
    <title>Avoinmet työpaikat</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/07bb6b2702.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js"></script>
    <?php include 'config.php'; ?>
    <?php include 'maakunnat.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container mx-auto mt-5">
    <div class="row justify-content-center mt-5">
    <div class="col-md-6">
    <h1 class="display-4">Avoinmet Työpaikat</h1>            
    <div class="input-group">
        <input type="search" class="form-control form-rounded-3" placeholder="Kirjoita Ammatti tai työtehtävä" aria-label="Search" aria-describedby="search-addon" id="jobSearchText">
        <button type="button" id="searchButton" class="btn btn-primary custom-button">Hae <i class="fas fa-search"></i></button>
    </div>
</div>
    </div>
    <!-- Filtros debajo de la barra de búsqueda en filas -->
    <div class="row mt-4">
        <div class="col-md-2">
            <label for="sijainti" class="form-label" style="font-weight:bold;">Valitse sijainti:</label>
            <select class="form-select" id="sijainti" name="sijainti">
                <option value="">Valitse sijainti</option>
                <?php
                // Decodificar el JSON de maakunnat y kunnat
                $jsonString = '...'; // Reemplaza con el contenido completo del JSON que creamos anteriormente
                $locations = json_decode($jsonString, true);

                if ($locations) {
                    // Iterar a través de las maakunnat y sus kunnat
                    foreach ($locations['Maakunnat'] as $maakunta => $kunnat) {
                        echo '<optgroup label="' . $maakunta . '">';
                        foreach ($kunnat as $kunta) {
                            echo '<option value="' . $maakunta . '-' . $kunta . '">' . $kunta . '</option>';
                        }
                        echo '</optgroup>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <label for="julkaistu" class="form-label"  style="font-weight:bold;">Julkaistu:</label>
            <select class="form-select" id="julkaistu" name="julkaistu">
                <option value="">Valitse aika</option>
                <option value="24h">24 tuntia</option>
                <option value="3d">3 päivää</option>
                <option value="1w">Viikko</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="ala" class="form-label"  style="font-weight:bold;">Ala:</label>
            <select class="form-select" id="ala" name="ala">
                <option value="">Valitse ala</option>
                <option value="IT">IT</option>
                <option value="Terveys">Terveys</option>
                <!-- Agrega opciones específicas del sector aquí -->
            </select>
        </div>
        <div class="col-md-2">
            <label for="tyoaika" class="form-label"  style="font-weight:bold;">Työaika:</label>
            <select class="form-select" id="tyoaika" name="tyoaika">
                <option value="">Valitse työaika</option>
                <option value="Kokoaikainen">Kokoainainen</option>
                <option value="Osa-aikainen">Osa-aikainen</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="tyonkieli" class="form-label"  style="font-weight:bold;">Työn kieli:</label>
            <select class="form-select" id="tyonkieli" name="tyonkieli">
                <option value="">Valitse työn kieli</option>
                <option value="Suomi">Suomi</option>
                <option value="Ruotsi">Ruotsi</option>
                <option value="Englanti">Englanti</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="palvelusuhde" class="form-label"  style="font-weight:bold;">Palvelusuhde:</label>
            <select class="form-select" id="palvelusuhde" name="palvelusuhde">
                <option value="">Valitse palvelusuhde</option>
                <option value="tyosuhde">Työsuhde</option>
                <option value="virkasuhde">Virkasuhde</option>
                <option value="vuokratyo">Vuokratyö</option>
                <option value="tyoharjottelu">Työharjottelu</option>
                <option value="oppisopimus">Oppisopimus</option>
                <option value="franchasing">Franchasing</option>
                <option value="Muu">Muu</option>
            </select>
        </div>
        <button id="clearFilters">Borrar Filtros</button>
    </div>

    <!-- Resultados de la búsqueda debajo de los filtros -->
    <div class="row mt-5" id="searchResults">
        <div class="col-md-12">
        </div>
    </div>
    <!-- Paginación para resultados -->
    <div class="row mt-3" id="pagination">
        <div class="col-md-12">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">Edellinen</a></li>
                    <!-- Genera dinámicamente los números de página aquí -->
                    <li class="page-item"><a class="page-link" href="#">Seuraava</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<?php include 'footer.html'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="scripts/haku_filter.js"></script>
<script src="scripts/filtro.js"></script>
<script>
$(document).ready(function () {
    // Captura el evento de envío del formulario
    $("#searchForm").submit(function (e) {
        e.preventDefault(); // Evita que la página se recargue

        // Obtiene el valor del campo de búsqueda
        var keyword = $(this).find('input[name="jobSearchText"]').val();

        // Envía el formulario a través de AJAX
        $.ajax({
            type: "GET",
            url: "procesar_busqueda.php",
            data: { jobSearchText: keyword },
            success: function (response) {
                // Muestra los resultados en el contenedor
                $("#searchResultsContainer").html(response);
            }
        });
    });
});
</script>
</body>
</html>
