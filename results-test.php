<?php
require('config.php');
define('NUM_ITEMS_BY_PAGE', 5);
$dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";
$pdo = new PDO($dsn, $username, $password);
$result = $pdo->query('SELECT COUNT(*) as total_products FROM jobs WHERE hyvaksytty = 1');
$row = $result->fetch(PDO::FETCH_ASSOC); // Usar PDO::FETCH_ASSOC para obtener un arreglo asociativo
$num_total_rows = $row['total_products'];
?>

<head>
    <title>Avoinmet työpaikat</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php
if ($num_total_rows > 0) {
    $page = false;
 
    //examinar la página a mostrar y el inicio del registro a mostrar
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    }
 
    if (!$page) {
        $start = 0;
        $page = 1;
    } else {
        $start = ($page - 1) * NUM_ITEMS_BY_PAGE;
    }
    //calcular el total de páginas
    $total_pages = ceil($num_total_rows / NUM_ITEMS_BY_PAGE);
 
    //poner el número de registros total, el tamaño de página y la página que se muestra
    echo '<h3>Numero de articulos: '.$num_total_rows.'</h3>';
    echo '<h3>En cada pagina se muestra '.NUM_ITEMS_BY_PAGE.' articulos ordenados por fecha en formato descendente.</h3>';
    echo '<h3>Mostrando la pagina '.$page.' de ' .$total_pages.' paginas.</h3>';
 
    $stmt = $pdo->prepare(
        'SELECT * FROM jobs WHERE hyvaksytty = 1 
        ORDER BY julkaistu DESC
        LIMIT :start, :items_per_page'
    );
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':items_per_page', NUM_ITEMS_BY_PAGE, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo '<ul class="list-group">';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            echo '<div class="item">';
            echo '<h3>'.$row['otsikko'].'</h3>';
            echo '<p>'.$row['kuvaus'].'</p>';
            echo '<p>'.$row['tyokieli'].'</p>';
            echo '<p>'.$row['sijainti'].'</p>';
            echo '<p>'.$row['tyoaika'].'</p>';
            echo '<p>'.$row['palvelusuhde'].'</p>';
            echo '</div>';
            echo '</li>';
        }
        echo '</ul>';
    }
 
    echo '<nav>';
    echo '<ul class="pagination">';
 
    if ($total_pages > 1) {
        if ($page != 1) {
            echo '<li class="page-item"><a class="page-link" href="index.php?page='.($page-1).'"><span aria-hidden="true">&laquo;</span></a></li>';
        }
 
        for ($i=1;$i<=$total_pages;$i++) {
            if ($page == $i) {
                echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="results-test.php?page='.$i.'">'.$i.'</a></li>';
            }
        }
 
        if ($page != $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="results-test.php?page='.($page+1).'"><span aria-hidden="true">&raquo;</span></a></li>';
        }
    }
    echo '</ul>';
    echo '</nav>';
}
?>
