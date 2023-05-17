<?php
require_once 'pdo.php';
require_once 'carregar_twig.php';
require_once 'verifica_sessao.php';

$pasta_documentos = 'documents/';

$usuario_id = $_SESSION['user'];

$filtro_tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$filtro_data = isset($_POST['data']) ? $_POST['data'] : '';
$filtro_nome = isset($_POST['nome']) ? $_POST['nome'] : '';

$sql = 'SELECT * FROM documentos WHERE usuario_id = ?';
$parametros = [$usuario_id];

if (!empty($filtro_tipo)) {
    $sql .= ' AND tipo = ?';
    $parametros[] = $filtro_tipo;
}

if (!empty($filtro_data)) {
    $sql .= ' AND data = ?';
    $parametros[] = $filtro_data;
}

if (!empty($filtro_nome)) {
    $sql .= ' AND nome LIKE ?';
    $parametros[] = '%' . $filtro_nome . '%';
}

$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);

$arquivos_na_pasta = glob($pasta_documentos . '*');

if ($stmt->rowCount() === 0) {
    echo 'Não foram encontrados documentos. <a href="meus_documentos.php">Voltar</a>';
    exit;
}

$documentos = array();
foreach ($stmt as $doc) {

    $caminho_do_arquivo = $pasta_documentos . $doc['nome'];
    if (!in_array($caminho_do_arquivo, $arquivos_na_pasta)) {
        continue;
    }

    $documentos[] = array(
        'id' => $doc['id'],
        'usuario_id' => $doc['usuario_id'],
        'nome' => $doc['nome'],
        'tipo' => $doc['tipo'],
        'data' => $doc['data'],
        'caminho' => $caminho_do_arquivo,
    );
}

echo $twig->render('meus_documentos.html', array('documentos' => $documentos));
?>