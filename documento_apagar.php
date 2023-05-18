<?php
    require('carregar_twig.php');
    require('pdo.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_GET['id'] ?? false;
        if ($id) {
            $sql = $pdo->prepare('DELETE FROM documentos WHERE id = ?');
            $sql->execute([$id]);
        }
        header('location: meus_documentos.php');
        die;
    }

    echo $twig->render('documento_apagar.html');
?>
