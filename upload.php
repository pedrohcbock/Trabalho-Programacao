<?php
require_once 'sessao_verifica.php';

$formatos_permitidos = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
  echo 'Erro ao enviar o arquivo';
  exit;
}

$arquivo = $_FILES['arquivo'];

if (!in_array($arquivo['type'], $formatos_permitidos)) {
  echo 'Formato do arquivo não é permitido';
  exit;
}

$conteudo = file_get_contents($arquivo['tmp_name']);

$base64 = base64_encode($conteudo);

require_once 'pdo.php';

$stmt = $pdo->prepare('INSERT INTO arquivos (nome, tipo, conteudo, usuario) VALUES (?, ?, ?, ?)');
$stmt->execute([$arquivo['name'], $arquivo['type'], $base64, $usuario]);

echo 'Arquivo enviado com sucesso';
?>