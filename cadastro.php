<?php
    require_once 'models/Model.php';
    require_once 'models/Usuario.php';

    $nome = $_POST['nome'] ?? false;
    $email =$_POST['email'] ?? false;
    $senha = $_POST['senha'] ?? false;

    if (!$nome || !$senha) {
        header('location:cadastro_usuario.php');
        die;
    }

    $senha = password_hash($senha, PASSWORD_BCRYPT);

    $usr = new Usuario();
    $usr->create([
        'nome' => $nome,
        'email' => $email,
        'senha' => $senha,
    ]);

    header('location:login.php');
?>

