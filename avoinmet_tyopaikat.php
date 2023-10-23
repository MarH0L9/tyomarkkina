<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();

}

/*data from index.php search bar*/
$searchTerm = isset($_GET['jobSearchText']) ? $_GET['jobSearchText'] : '';    
?>


<!DOCTYPE html>
<html lang="fi">
<head>
    <title>Avoimmet työpaikat</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="resources/images/logo/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://kit.fontawesome.com/07bb6b2702.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js"></script>
    <?php include 'config.php'; ?>
    <?php include 'maakunnat.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>

<main>
<div class="container mx-auto mt-5">
    <div class="row justify-content-center mt-5">
    <div class="col-md-6">
    <h1 class="display-4">Avoinmet Työpaikat</h1>            
    <div class="input-group">
        <input type="search" class="form-control  custom-border form-rounded-3" placeholder="Kirjoita Ammatti tai työtehtävä" aria-label="Search" aria-describedby="search-addon" id="jobSearchText" value="<?php echo $searchTerm;?>">
        <button type="button" id="searchButton" class="btn btn-primary custom-button">Hae <i class="fas fa-search"></i></button>
    </div>

</div>
    <!-- Filtros debajo de la barra de búsqueda en filas -->
    <div class="row mt-4 ">
        <div class="col-md-2">
        
            <label for="sijainti" class="form-label" style="font-weight:bold;">Valitse maakunta:</label>
            <select class="form-select" id="sijainti" name="sijainti">
                <option value="">Kaikki</option>
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
                <option value="">Kaikki</option>
                <option value="24h">24 tuntia</option>
                <option value="3d">3 päivää</option>
                <option value="1w">Viikko</option>
            </select>
        </div>
        <div class="col-md-2">
             <label for="tehtava" class="form-label">Tehtävä:</label>
                 <select class="form-select" id="tehtava" name="tehtava">:
                    <option value="">Kaikki</option>
                    <option value="Asennus, huolto ja kunnossapito">Asennus, huolto ja kunnossapito</option>
                    <option value="Asiakaspalvelu">Asiakaspalvelu</option>
                    <option value="Asiantuntijatyö ja konsultointi">Asiantuntijatyö ja konsultointi</option>
                    <option value="Hallinto ja yleiset toimistotyöt">Hallinto ja yleiset toimistotyöt</option>
                    <option value="Henkilöstöala">Henkilöstöala</option>
                    <option value="Hyvinvointi- ja henkilöpalvelut">Hyvinvointi- ja henkilöpalvelut</option>
                    <option value="Johtotehtävät">Johtotehtävät</option>
                    <option value="Julkinen sektori ja järjestöt">Julkinen sektori ja järjestöt</option>
                    <option value="Kiinteistöala">Kiinteistöala</option>
                    <option value="Kuljetus, logistiikka ja liikenne">Kuljetus, logistiikka ja liikenne</option>
                    <option value="Kulttuuri-, viihde- ja taidealat">Kulttuuri-, viihde- ja taidealat</option>
                    <option value="Lakiala">Lakiala</option>
                    <option value="Markkinointi">Markkinointi</option>
                    <option value="Markkinointi, mainonta, media ja viestintä">Markkinointi, mainonta, media ja viestintä</option>
                    <option value="Myynti- ja kaupan ala">Myynti- ja kaupan ala</option>
                    <option value="Opetusala">Opetusala</option>
                    <option value="Opiskelijoiden työpaikat">Opiskelijoiden työpaikat</option>
                    <option value="Rakennusala">Rakennusala</option>
                    <option value="Ravintola- ja matkailuala">Ravintola- ja matkailuala</option>
                    <option value="Siivous, puhtaanapito ja jätehuolto">Siivous, puhtaanapito ja jätehuolto</option>
                    <option value="Sosiaali- ja hoiva-ala">Sosiaali- ja hoiva-ala</option>
                    <option value="Taloushallinto ja pankkiala">Taloushallinto ja pankkiala</option>
                    <option value="Teollisuus ja teknologia">Teollisuus ja teknologia</option>
                    <option value="Terveys- ja sosiaalipalvelut">Terveys- ja sosiaalipalvelut</option>
                 </select>
        </div>
        <div class="col-md-2">
            <label for="tyoaika" class="form-label"  style="font-weight:bold;">Työaika:</label>
            <select class="form-select" id="tyoaika" name="tyoaika">
                <option value="">Kaikki</option>
                <option value="Kokoaikainen">Kokoainainen</option>
                <option value="Osa-aikainen">Osa-aikainen</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="tyokieli" class="form-label"  style="font-weight:bold;">Työn kieli:</label>
            <select class="form-select" id="tyokieli" name="tyokieli">
                <option value="">Kaikki kielet</option>
                <option value="Suomi">Suomi</option>
                <option value="Ruotsi">Ruotsi</option>
                <option value="Englanti">Englanti</option>
                <option value="Eesti">Eesti</option>
                <option value="Espanja">Espanja</option>
                <option value="Muu">Muu</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="palvelusuhde" class="form-label"  style="font-weight:bold;">Palvelusuhde:</label>
            <select class="form-select" id="palvelusuhde" name="palvelusuhde">
                <option value="">Kaikki</option>
                <option value="tyosuhde">Työsuhde</option>
                <option value="virkasuhde">Virkasuhde</option>
                <option value="vuokratyo">Vuokratyö</option>
                <option value="tyoharjottelu">Työharjottelu</option>
                <option value="oppisopimus">Oppisopimus</option>
                <option value="franchasing">Franchasing</option>
                <option value="Vakituinen">Vakituinen</option>
                <option value="Projektityo">Projektityö</option>
                <option value="Muu">Muu</option>
            </select>
        </div>
        </div>        
        
        <div class="row mt-5">
        <div class="col-md-12 text-center">              
        <button id="clearFilters" type="button" class="btn btn-danger" style="margin-bottom: 20px;">Poista Suodattimet</button>
        </div>        
            <div class="col-md-12" id="active-filters">
            </div>
        </div>

    <!-- Resultados de la búsqueda debajo de los filtros -->
    
    <div class="row mt-5" >
        <div class="row  justify-content-center " id="searchResults" style="margin-bottom: 20px;">     
        </div>
    </div>
    
</div>

</main>

<div class="footer">
<?php include 'footer.html'; ?>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="scripts/haku_filter.js"></script>
<script src="scripts/individual-filters.js"></script>
<script src="scripts/filtro.js"></script>

<script>

//script for search bar from index.php
let searchTerm = "<?php echo $searchTerm; ?>";
if (searchTerm !== "") {
    $(window).on('load', function() {
        $("#searchButton").click();
    });
}

$(document).ready(function () {
function fetchResults() {
    var keyword = $('input[name="jobSearchText"]').val();
    $.ajax({
        type: "POST",
        url: "procesar_busqueda.php",
        data: { 
            jobSearchText: keyword,
            
        },
        success: function (response) {
            $("#searchResultsContainer").html(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $("#searchResultsContainer").html("Error fetching results. Please try again later.");
        }
    });
}


});
</script>
</body>
</html>
