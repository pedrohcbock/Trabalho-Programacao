<?php
require_once 'verifica_sessao.php';
require_once 'pdo.php';

$usuario_id = $_SESSION['user'];

// Consulta SQL para obter a lista de usuários, excluindo o usuário logado
$sqlUsuarios = $pdo->prepare('SELECT id, nome FROM usuarios WHERE id <> ?');
$sqlUsuarios->execute([$usuario_id]);
$usuarios = $sqlUsuarios->fetchAll(PDO::FETCH_ASSOC);

// Consulta SQL para obter a lista de documentos
$sqlDocumentos = $pdo->prepare('SELECT id, nome FROM documentos');
$sqlDocumentos->execute();
$documentos = $sqlDocumentos->fetchAll(PDO::FETCH_ASSOC);

$sqlUsuarioAtual = $pdo->prepare('SELECT nome FROM usuarios WHERE id = ?');
$sqlUsuarioAtual->execute([$usuario_id]);
$usuarioAtual = $sqlUsuarioAtual->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['usuario']) && isset($_POST['documento'])) {
        $idUsuarioDestino = $_POST['usuario'];
        $idDocumento = $_POST['documento'];

        // Verificar se o usuário de destino é diferente do usuário logado
        if ($idUsuarioDestino != $usuario_id) {
            // Verificar se o documento existe
            $sqlVerificarDocumento = $pdo->prepare('SELECT COUNT(*) FROM documentos WHERE id = ?');
            $sqlVerificarDocumento->execute([$idDocumento]);
            $documentoExists = $sqlVerificarDocumento->fetchColumn();

            if ($documentoExists) {
                // Inserir o compartilhamento na tabela de compartilhamentos
                $sqlInserirCompartilhamento = $pdo->prepare('INSERT INTO compartilhamentos (documento_id, usuario_id) VALUES (?, ?)');
                $sqlInserirCompartilhamento->execute([$idDocumento, $idUsuarioDestino]);

                // Redirecionar para a página de compartilhamentos
                header('Location: arquivoscompartilhados.php');
                exit();
            } else {
                echo "Documento inválido. Por favor, selecione um documento válido.";
            }
        } else {
            echo "Você não pode compartilhar um documento com você mesmo.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Compartilhar Documento</title>
</head>

<body>
    <h1>O que irá compartilhar hoje
        <?php echo $usuarioAtual; ?>
    </h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Selecione o documento que você irá compartilhar:</label>
        <select name="documento">
            <?php foreach ($documentos as $documento): ?>
                <option value="<?php echo $documento['id']; ?>"><?php echo $documento['nome']; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <label>Selecione o usuário para compartilhar:</label>
        <select name="usuario">
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nome']; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <input type="submit" value="Compartilhar">
        <p>Não encontrou o documento desejado? Faça upload dele primeiro <a href="upload_documento.php">aqui</a></p>
    </form>
</body>

</html>