<?php
require_once 'pdo.php';
require_once 'carregar_twig.php';
require_once 'verifica_sessao.php';

$pasta_documentos = 'documents/';

$usuario_id = $_SESSION['user'];

$filtro_tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$filtro_data = isset($_POST['data']) ? $_POST['data'] : '';
$filtro_nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$filtro_compartilhador = isset($_POST['compartilhador']) ? $_POST['compartilhador'] : '';

// Consultar o banco de dados para obter os documentos compartilhados com o usuário atual
$sql = 'SELECT d.id, d.usuario_id, d.nome, d.tipo, d.data, u.nome AS nome_compartilhador
        FROM compartilhamentos c
        INNER JOIN documentos d ON c.documento_id = d.id
        INNER JOIN usuarios u ON d.usuario_id = u.id
        WHERE c.usuario_id = ?';

$parametros = [$usuario_id];

if (!empty($filtro_tipo)) {
    $sql .= ' AND d.tipo = ?';
    $parametros[] = $filtro_tipo;
}

if (!empty($filtro_data)) {
    $sql .= ' AND d.data = ?';
    $parametros[] = $filtro_data;
}

if (!empty($filtro_nome)) {
    $sql .= ' AND d.nome LIKE ?';
    $parametros[] = '%' . $filtro_nome . '%';
}

if (!empty($filtro_compartilhador)) {
    $sql .= ' AND u.nome LIKE ?';
    $parametros[] = '%' . $filtro_compartilhador . '%';
}

$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);

$arquivos_na_pasta = glob($pasta_documentos . '*');

if ($stmt->rowCount() === 0) {
    echo 'Não foram encontrados documentos compartilhados. <a href="documentos_compartilhados.php">Voltar</a>';
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
        'compartilhador' => $doc['nome_compartilhador'],
        'caminho' => $caminho_do_arquivo,
    );
}

echo $twig->render('documentos_compartilhados.html', array('documentos' => $documentos));
?>