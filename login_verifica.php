<?php
require('pdo.php');

$user = $_POST['nome'];
$pass = $_POST['senha'];

$sql = $pdo->prepare('SELECT * FROM usuarios WHERE nome = :usr');

$sql->bindParam(':usr', $user);

$sql->execute();

if ($sql->rowCount()) {

    $user = $sql->fetch(PDO::FETCH_OBJ);


    if (!password_verify($pass, $user->senha)) {

        header('location:login.php?erro=1');
        die;
    }

    session_start();
    $_SESSION['user'] = $user->nome;

    header('location:upload_arquivo.php');
    die;
} else {

    header('location:login.php?erro=1');
    die;
}