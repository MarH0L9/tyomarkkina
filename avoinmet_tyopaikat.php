<!DOCTYPE html>
<html lang="fi">
<head>
    <title>Avoinmet työpaikat</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            <h1 class="display-4">Avoin Met Työpaikat</h1>
            <form action="#" method="GET" class="input-group mb-3">
                <div class="input-group">
                    <input type="search" class="form-control rounded" placeholder="Kirjoita Ammatti tai työtehtävä" aria-label="Search" aria-describedby="search-addon" />
                    <button type="button" class="btn btn-primary custom-button">Näytä työpaikat</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-6">
            <form>
                <div class="mb-3">
                    <label for="sijainti" class="form-label" style="font-weight:bold;">Valitse sijainti:</label>
                    <select class="form-select" id="sijainti" name="sijainti">
                        <option value="">Valitse sijainti</option>
                        <?php
                            foreach ($maakunnat as $maakunta) {
                                $maakuntaName = $maakunta['classificationItemNames'][0]['name'];
                                echo '<option value="maakunta-' . $maakuntaName . '">' . $maakuntaName . '</option>';
                            }
                        ?>
                    </select>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form>
                <div class="mb-3">
                    <label for="julkaistu" class="form-label"  style="font-weight:bold;">Julkaistu:</label>
                    <select class="form-select" id="julkaistu" name="julkaistu">
                        <option value="">Valitse aika</option>
                        <option value="24h">24 tuntia</option>
                        <option value="3d">3 päivää</option>
                        <option value="1w">Viikko</option>
                    </select>
                </div>
                <button type="button" class="btn btn-primary">Hae</button>
            </form>
        </div>
    </div>

    <!-- Botón para mostrar/ocultar los filtros adicionales -->
    <div class="row mt-3">
        <div class="col-md-12">
            <span> Lisää hakuehtoja</span>
            <button type="button" class="btn btn-light" id="toggleFiltersButton"><i class="fa-solid fa-square-plus fa-lg" style="color: #050505;"></i></button>
        </div>
    </div>

    <!-- Filtros adicionales ocultos por defecto -->
    <div class="row mt-3" id="additionalFilters" style="display: none;">
        <div class="col-md-6">
            <label for="continuity">Työn jatkuvuus:</label>
            <select class="form-select" id="continuity" name="continuity">
                <option value="">Valitse työn jatkuvuus</option>
                <option value="continuous">Jatkuva</option>
                <option value="temporary">Määräaikainen</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="language">Työkieli:</label>
            <select class="form-select" id="language" name="language">
                <option value="">Valitse työkieli</option>
                <option value="finnish">Suomi</option>
                <option value="english">Englanti</option>
            </select>
        </div>
    </div>
    <div class="row mt-3" id="hiddenFilters" style="display: none;">
        <div class="col-md-6">
            <label for="workTime">Työaika:</label>
            <select class="form-select" id="workTime" name="workTime">
                <option value="">Valitse työaika</option>
                <option value="fullTime">Kokopäivätyö</option>
                <option value="partTime">Osapäivätyö</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="employment">Palvelussuhde:</label>
            <select class="form-select" id="employment" name="employment">
                <option value="">Valitse palvelussuhde</option>
                <option value="permanent">Vakituinen</option>
                <option value="temporary">Määräaikainen</option>
            </select>
        </div>
    </div>
    <div class="row mt-3" id="additionalFilters2" style="display: none;">
        <div class="col-md-6">
            <label for="workingTime">Työskentelyaika:</label>
            <select class="form-select" id="workingTime" name="workingTime">
                <option value="">Valitse työskentelyaika</option>
                <option value="dayShift">Päivävuoro</option>
                <option value="nightShift">Yövuoro</option>
            </select>
        </div>
    </div>
</div>
</div>
</div>
<?php include 'footer.html'; ?>

<script src="scripts/haku_filter.js"></script>

</body>
</html>