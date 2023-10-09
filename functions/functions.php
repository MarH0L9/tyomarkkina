<?php
function generateJobCard($jobData) {
    $card = '<div class="col-md-12">';
    $card .= '<a href="detalle_oferta.php?id=' . $jobData['ID'] . '" style="text-decoration: none; color: inherit;" target="_blank">'; // Enlace que envuelve toda la tarjeta
    $card .= '<div class="card-body my-custom-card">';
    
    // Título en negrita sin márgenes y fondo lila claro
    $card .= '<h3 class="card-title"><strong>' . $jobData['Otsikko'] . '</strong></h3>';
    
    // Fila para Sijainti, kunta a la izquierda, tyosuhde a la derecha
    $card .= '<div class="row">';
    $card .= '<div class="col-md-6">';
    $card .= '<p><i class="fas fa-map-marker-alt" style="color: #0f0f10;"></i></i><strong> Sijainti:</strong> ' . $jobData['Sijainti'] . ', ' . $jobData['Kunta'] . '</p>';
    $card .= '</div>';
    $card .= '<div class="col-md-6">';
    $card .= '<p><strong>Palvelusuhde:</strong> ' . $jobData['palvelusuhde'] . '</p>';
    $card .= '</div>';
    $card .= '</div>';
    
    // Fila para Kuvaus
    $card .= '<div class="row">';
    $card .= '<div class="col-md-12">';
    $card .= '<p><strong>Kuvaus:</strong> ' . $jobData['Kuvaus'] . '</p>';
    $card .= '</div>';
    $card .= '</div>';
    
    // Cierre de la tarjeta
    $card .= '</div>';
    $card .= '</a>'; // Cierra el enlace que envuelve toda la tarjeta
    $card .= '</div>';

    return $card;
}

?>
