<?php
function generateJobListItem($jobData) {
    $item = '<li>';
    $item .= '<h3>' . $jobData['Otsikko'] . '</h3>';
    $item .= '<p><strong>Sijainti:</strong> ' . $jobData['Sijainti'] . ', ' . $jobData['Kunta'] . '</p>';
    $item .= '<p><strong>Yrityksen Nimi:</strong> ' . $jobData['YrityksenNimi'] . '</p>';
    $item .= '<p><strong>Julkaistu:</strong> ' . date('d.m.Y', strtotime($jobData['Julkaisupaiva'])) . '</p>';
    $item .= '<p><strong>Kuvaus:</strong></p>';
    $item .= '<p>' . nl2br($jobData['Kuvaus']) . '</p>';
    $item .= '<p><strong>Vaatimukset:</strong></p>';
    $item .= '<p>' . nl2br($jobData['Vaatimukset']) . '</p>';
    if (!empty($jobData['YrityksenLinkki'])) {
        $item .= '<p><strong>Yrityksen Linkki:</strong> <a href="' . $jobData['YrityksenLinkki'] . '" target="_blank">' . $jobData['YrityksenLinkki'] . '</a></p>';
    }
    $item .= '</li>';

    return $item;
}
?>
