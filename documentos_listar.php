<?php
require_once 'pdo.php';
require_once 'carregar_twig.php';

$filtro_tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$filtro_usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
$filtro_data = isset($_POST['data']) ? $_POST['data'] : '';

$sql = 'SELECT * FROM arquivos WHERE 1=1';
$parametros = array();

if (!empty($filtro_tipo)) {
    $sql .= ' AND tipo = ?';
    $parametros[] = $filtro_tipo;
}

if (!empty($filtro_usuario)) {
    $sql .= ' AND usuario = ?';
    $parametros[] = $filtro_usuario;
}

if (!empty($filtro_data)) {
    $sql .= ' AND data = ?';
    $parametros[] = $filtro_data;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);


$table_rows = array();
foreach ($stmt as $row) {

    $conteudo = base64_decode($row['conteudo']);

    $table_rows[] = array(
        'usuario' => $row['usuario'],
        'nome' => $row['nome'],
        'tipo' => $row['tipo'],
        'data_envio' => $row['data_envio'],
        'conteudo' => $conteudo,
    );
}

echo $twig->render('documentos_listar.html', array('table_rows' => $table_rows));
