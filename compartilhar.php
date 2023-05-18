<?php
require_once 'verifica_sessao.php';
require_once 'pdo.php';

$usuario_id = $_SESSION['user'];

$sqlUsuarios = $pdo->prepare('SELECT id, nome FROM usuarios WHERE id <> ?');
$sqlUsuarios->execute([$usuario_id]);
$usuarios = $sqlUsuarios->fetchAll(PDO::FETCH_ASSOC);

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

        if ($idUsuarioDestino != $usuario_id) {
            $sqlVerificarDocumento = $pdo->prepare('SELECT COUNT(*) FROM documentos WHERE id = ?');
            $sqlVerificarDocumento->execute([$idDocumento]);
            $documentoExists = $sqlVerificarDocumento->fetchColumn();

            if ($documentoExists) {
                $sqlInserirCompartilhamento = $pdo->prepare('INSERT INTO compartilhamentos (documento_id, usuario_id) VALUES (?, ?)');
                $sqlInserirCompartilhamento->execute([$idDocumento, $idUsuarioDestino]);

                $_SESSION['success_message'] = 'Arquivo compartilhado com sucesso';

                header('Location: meus_documentos.php');
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cadastre-se</title>
</head>

<body>
    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
        <div class="collapse navbar-collapse justify-content-center" id="collapsibleNavbar">
            <a class="navbar-brand" href="menu.php">Site muito bom</a>
            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="upload_documento.php">Upload de Documentos</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="compartilhar.php">Compartilhar Documentos</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="meus_documentos.php">Meus Documentos</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="usuarios.php">Lista de Amigos</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="documentos_compartilhados.php">Compartilhados Comigo</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <h1 class="text-center">O que irá compartilhar hoje
                    <?php echo $usuarioAtual; ?>
                </h1>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Selecione o documento que você irá compartilhar:</label>
                        <select class="form-select" name="documento">
                            <?php foreach ($documentos as $documento): ?>
                                <option value="<?php echo $documento['id']; ?>"><?php echo $documento['nome']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Selecione o usuário para compartilhar:</label>
                        <select class="form-select" name="usuario">
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nome']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="container">
                        <div class="col-md-12 text-center p-2">
                            <div class="mb-3">
                                <input type="submit" class="btn btn-primary" value="Compartilhar">
                            </div>
                        </div>
                    </div>
                    <p class="text-center">Não encontrou o documento desejado? Faça upload dele primeiro <a
                            href="upload_documento.php">aqui</a></p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>