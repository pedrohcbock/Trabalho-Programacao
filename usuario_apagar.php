<?php
require('carregar_twig.php');
require('pdo.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? false;
    if ($id) {
        try {
            $sql = $pdo->prepare('DELETE FROM usuarios WHERE id = ?');
            $sql->execute([$id]);
        } catch (PDOException $e) {
            if ($e->getCode() == '23000' && strpos($e->getMessage(), 'compartilhamentos') !== false) {
                echo 'O usuário não pode ser apagado, pois existem documentos importantes associados a ele. <a href="usuarios.php">Voltar</a>';
                die;
            } else {
                echo 'Ocorreu um erro ao apagar o usuário: ' . $e->getMessage();
                die;
            }
        }
        header('location:usuarios.php');
        die;
    }
}

$id = $_GET['id'] ?? false;
$sql = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
$sql->execute([$id]);
$usuario = $sql->fetch(PDO::FETCH_ASSOC);

echo $twig->render('/usuario_apagar.html', [
    'usuario' => $usuario,
]);
?>
