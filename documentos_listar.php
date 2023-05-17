<?php
require_once 'pdo.php';
require_once 'carregar_twig.php';
require_once 'verifica_sessao.php'; // Inclua o arquivo de verificação de sessão

$pasta_documentos = 'documents/';

// Obtém o ID do usuário logado
$usuario_id = $_SESSION['user'];

// Consulta SQL para obter os documentos do usuário logado
$sql = 'SELECT * FROM documentos WHERE usuario_id = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario_id]);

$arquivos_na_pasta = glob($pasta_documentos . '*');

if ($stmt->rowCount() === 0) {
    echo 'Não foram encontrados documentos.';
    exit;
}

$table_rows = array();
foreach ($stmt as $row) {

    $caminho_do_arquivo = $pasta_documentos . $row['nome'];
    if (!in_array($caminho_do_arquivo, $arquivos_na_pasta)) {
        continue;
    }

    $table_rows[] = array(
        'usuario_id' => $row['usuario_id'],
        'nome' => $row['nome'],
        'tipo' => $row['tipo'],
        'data' => $row['data'],
        'caminho' => $caminho_do_arquivo,
    );
}

echo $twig->render('documentos_listar.html', array('table_rows' => $table_rows));
?>
