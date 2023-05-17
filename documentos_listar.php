<?php
require_once 'pdo.php';
require_once 'carregar_twig.php';

$pasta_documentos = 'documents/';

$filtro_tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$filtro_usuario = isset($_POST['usuario_id']) ? $_POST['usuario_id'] : '';
$filtro_data = isset($_POST['data']) ? $_POST['data'] : '';

$sql = 'SELECT * FROM documentos WHERE 1=1';
$parametros = array();

if (!empty($filtro_tipo)) {
    $sql .= ' AND tipo = ?';
    $parametros[] = $filtro_tipo;
}

if (!empty($filtro_usuario)) {
    $sql .= ' AND usuario_id = ?';
    $parametros[] = $filtro_usuario;
}

if (!empty($filtro_data)) {
    $sql .= ' AND data = ?';
    $parametros[] = $filtro_data;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);

$arquivos_na_pasta = glob($pasta_documentos . '*');

if (count($arquivos_na_pasta) === 0) {
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