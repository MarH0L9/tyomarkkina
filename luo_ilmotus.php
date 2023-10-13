<?php
 if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luo Työilmoitus</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/07bb6b2702.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js"></script>
    <script src="scripts/haku_filter.js"></script>
    <?php include 'config.php'; ?>
    <?php include 'maakunnat.php'; ?>


</head>
<body>
<?php include 'header.php'; ?>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Luo Uusi Työilmoitus</h2><hr>
    <form action="process_uusi_ilmotus.php" method="post">
    
        <h5 class="title">Ilmoituksen tiedot</h5>
        <div class="mb-3">
            <label for="otsikko" class="form-label">Otsikko</label>
            <input type="text" class="form-control" id="otsikko" name="otsikko" required>
        </div>

        <div class="mb-3">
            <label for="kuvaus" class="form-label">Kuvaus</label>
            <textarea class="form-control"  id="kuvaus" name="kuvaus" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="tarkkakuvaus" class="form-label">Tarkka Kuvaus</label>
            <textarea class="form-control" id="tarkkakuvaus" name="tarkkakuvaus" required></textarea>
        </div>
        

        <div class="mb-3">
            <label for="yrityksennimi" class="form-label">Yrityksen Nimi</label>
            <input type="text" class="form-control" id="yrityksennimi" name="yrityksennimi" required>
        </div>

        <div class="mb-3">
        <label for="sijainti" class="form-label" style="font-weight:bold;">Valitse sijainti:</label>
            <select class="form-select" id="sijainti" name="sijainti">
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

        <div class="mb-3">
            <label for="kunta" class="form-label">Kunta</label>
            <input type="text" class="form-control" placeholder="esim: Espoo" id="kunta" name="kunta" required>
        </div>
        <div class="mb-3">
            <label for="tehtava" class="form-label">Tehtävä</label>
            <select class="form-select" id="tehtava" name="tehtava">:
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

        <div class="mb-3">
            <label for="ala" class="form-label">Ala</label>
            <input type="text" placeholder="esim: IT" class="form-control" id="ala" name="ala" required>
        </div>

        <div class="mb-3">
            <label for="tyoaika" class="form-label">Työaika</label>
            <select class="form-select" id="tyoaika" name="tyoaika">:
                <option value="Kokoaikainen">Kokoaikainen</option>
                <option value="Osa-aikainen">Osa-aikainen</option>
            </select>
        </div>

        <div class="mb-3">
        <label for="palvelusuhde" class="form-label"  style="font-weight:bold;" >Palvelusuhde:</label>
            <select class="form-select" id="palvelusuhde" name="palvelusuhde" required>
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

        <div class="mb-3">
            <label for="palkka" class="form-label">Palkka</label>
            <input type="number" placeholder="esim: 2500" class="form-control" id="palkka" name="palkka" step="0.01" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Työn kieli</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tyokieli[]" id="Suomi" value="Suomi">
                <label class="form-check-label" for="Suomi">Suomi</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tyokieli[]" id="Englanti" value="Englanti">
                <label class="form-check-label" for="Englanti">Englanti</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tyokieli[]" id="Ruotsi" value="Ruotsi">
                <label class="form-check-label" for="Ruotsi">Ruotsi</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tyokieli[]" id="Venaja" value="Venäjä">
                <label class="form-check-label" for="Venaja">Venäjä</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tyokieli[]" id="Eesti" value="Eesti">
                <label class="form-check-label" for="Eesti">Eesti</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tyokieli[]" id="Espanja" value="Espanja">
                <label class="form-check-label" for="Espanja">Espanja</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tyokieli[]" id="Muu" value="Muu">
                <label class="form-check-label" for="Muu">Muu</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="voimassaolopaiva" class="form-label">Viimeinen Hakupäivä</label>
            <input type="date" class="form-control" id="voimassaolopaiva" name="voimassaolopaiva" required>
        </div>

        <div class="mb-3">
            <label for="vaatimukset" class="form-label">Vaatimukset</label>
            <textarea class="form-control" id="vaatimukset" name="vaatimukset" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="yrityksenlinkki" class="form-label">Yrityksen Linkki</label>
            <input type="text" class="form-control" id="yrityksenlinkki" name="yrityksenlinkki">
        </div>
       <div class="mb-3">         
        <button type="submit" class="btn btn-primary">Lähetä Työilmoitus</button>
        <button type="reset" class="btn btn-secondary">Tyhjennä Lomake</button>
        </div>
    </form>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.html'; ?>
</body>
</html>