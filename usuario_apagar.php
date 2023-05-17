<?php
    # usuario_apagar.php
    require('carregar_twig.php');
    require('pdo.php'); // Conexão com o banco

    // Rotina de POST - Apagar o usuário
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Modifica o usuário para ativo = 0
        $id = $_POST['id'] ?? false;
        if ($id) {
            $sql = $pdo->prepare('DELETE FROM usuarios WHERE id = ?');
            $sql->execute([$id]);
        }
        header('location:usuarios.php');
        die;
    }

    // Rotina de GET - Mostrar informações na tela

    // Busca o usuário no banco para mostrar o nome dele na tela
    $id = $_GET['id'] ?? false;
    $sql = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
    $sql->execute([$id]);
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    echo $twig->render('/usuario_apagar.html',[
        'usuario' => $usuario,
    ]);