<?php

//Functio joka luo työpaikka kortin
function generateJobCard($jobData) {
    $card = '<div class="col-md-12">';
    $card .= '<a href="detalle_oferta.php?id=' . $jobData['ID'] . '" style="text-decoration: none; color: inherit;" target="_blank">'; // Enlace que envuelve toda la tarjeta
    $card .= '<div class="card-body my-custom-card">';
    
    // Title
    $card .= '<h3 class="card-title"><strong>' . $jobData['Otsikko'] . '</strong></h3>';
    
    // Row for Sijainti, kunta left, tyosuhde right
    $card .= '<div class="row">';
    $card .= '<div class="col-md-6">';
    $card .= '<p><i class="fas fa-map-marker-alt" style="color: #0f0f10;"></i></i><strong> Sijainti:</strong> ' . $jobData['Sijainti'] . ', ' . $jobData['Kunta'] . '</p>';
    $card .= '</div>';
    $card .= '<div class="col-md-6">';
    $card .= '<p><strong>Palvelusuhde:</strong> ' . $jobData['palvelusuhde'] . '</p>';
    $card .= '</div>';
    $card .= '</div>';
    
    // row for kuvaus
    $card .= '<div class="row">';
    $card .= '<div class="col-md-12">';
    $card .= '<p><strong>Kuvaus:</strong> ' . $jobData['Kuvaus'] . '</p>';
    $card .= '</div>';
    $card .= '<p><strong>Julkaistu:</strong> ' . $jobData['julkaistu'] . '</p>';
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


//Functio joka näyttää session viestin kuin tallennetaan tietoja
function displaySessionMessage() {
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message']['type'] . '">' . $_SESSION['message']['text'] . '</div>';
        // Borramos el mensaje después de mostrarlo:
        unset($_SESSION['message']);
    }
}






?>
