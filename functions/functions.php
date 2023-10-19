<?php

//Funktio joka luo työpaikka kortin listaan
function generateJobCard($jobData) {
    $formattedDate = date('d-m-Y', strtotime($jobData['julkaistu']));
    $formattedDate2 = date('d-m-Y', strtotime($jobData['voimassaolopaiva']));


    $card = '<div class="col-md-12 col-md-6 col-sm-12">';
    $card .= '<a href="detalle_oferta.php?id=' . $jobData['id'] . '" style="text-decoration: none; color: inherit;" target="_blank">'; // Enlace que envuelve toda la tarjeta
    $card .= '<div class="card-body">';
    
    // Title
    $card .= '<h3 class="card-title"><strong>' . $jobData['otsikko'] . ' - ' . $jobData['sijainti'] . '</strong></h3>';
    
    // Row for Sijainti, kunta left, tyosuhde right
    $card .= '<div class="row">';
    $card .= '<div class="col-sm-12 col-md-4">';
    $card .= '<p><i class="fas fa-map-marker-alt" style="color: #033f21;"></i></i><strong> Sijainti:</strong> ' . $jobData['sijainti'] . ', ' . $jobData['kunta'] . '</p>';
    $card .= '</div>';
    $card .= '<div class="col-sm-12 col-md-4">';
    $card .= '<p><i class="fa-solid fa-calendar-days fa-lg"></i><strong> Julkaistu:</strong> ' .  $formattedDate . '</p>';
    $card .= '</div>';
    $card .= '<div class="col-sm-12 col-md-4">';
    $card .= '<p><i class="fa-regular fa-clock fa-lg"></i><strong> Päättyy:</strong> ' . $formattedDate2 . '</p>';
    $card .= '</div>';
    $card .= '</div>';
    
    // row for kuvaus
    $card .= '<div class="row">';
    $card .= '<div class="col col-sm-12 col-md-4">'; // Ocupa las primeras 4 columnas (de un total de 12)
    $card .= '<p><strong>Yritys:</strong> ' . $jobData['yrityksennimi'] . '</p>';
    $card .= '</div>';
    $card .= '<div class="col col-sm-12 col-md-4">'; // Este espacio quedará vacío, creando una columna central sin contenido
    $card .= '</div>';
    $card .= '<div class="col col-sm-12 col-md-4">'; // Ocupa las últimas 4 columnas (de un total de 12)
    $card .= '<p><strong>Tehtävä:</strong> ' . $jobData['tehtava'] . '</p>';  
    $card .= '</div>';
    $card .= '</div>';

    $card .= '<div class="row">';
    $card .= '<div class="col-md-12 col-sm-12 col-md-4">';
    $card .= '<p><strong>Kuvaus:</strong> ' . $jobData['kuvaus'] . '</p>';    
    $card .= '</div>';
    $card .= '</div>';

    
    // Kortin sulkeminen
    $card .= '</div>';
    $card .= '</a>'; // Suljetaan linkki
    $card .= '</div>';

    return $card;
}



//Functio joka tallentaa viestin sessioniin kuin tallennetaan tietoja
function setSessionMessage($isSuccessful, $errorMessage = null) {
    if ($isSuccessful) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Tiedot tallennettu onnistuneesti.'
        ];
    } else {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Virhe, tiedot ei ole tallennetty: ' . $errorMessage
        ];
    }
}


//Funktio joka näyttää session viestin kuin tallennetaan tietoja
function displaySessionMessage() {
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message']['type'] . '">' . $_SESSION['message']['text'] . '</div>';
        // Borramos el mensaje después de mostrarlo:
        unset($_SESSION['message']);
    }
}


//Funktio joka kertoo onko käyttäjä kirjautunut ulos
function checkSessionClosed() {
    if (isset($_GET['session_closed']) && $_GET['session_closed'] == 'true') {
        return "Olet onnistuneesti kirjautunut ulos. Toivottavasti näemme sinut pian uudelleen!";
    }
    return null;
}

// Función para mostrar un mensaje de éxito
function showSuccessMessage($message) {
    echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
}

// Función para mostrar un mensaje de error
function showErrorMessage($message) {
    echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
}

?>
