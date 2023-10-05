   <?php
        // Lee el contenido del archivo JSON de Maakunnat
        $jsonMaakunnatContent = file_get_contents('json/maakunnat.json');

        // Decodifica el contenido JSON de Maakunnat en un array asociativo
        $maakunnat = json_decode($jsonMaakunnatContent, true);

    ?>