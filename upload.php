<?php
// Inclui o arquivo que verifica se o usuário está logado
require_once 'verifica_sessao.php';

// Verifica se um arquivo foi enviado
if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
  echo 'Erro ao enviar o arquivo';
  exit;
}

// Obtém as informações do arquivo
$arquivo = $_FILES['arquivo'];

// Lê o conteúdo do arquivo em formato binário
$conteudo = file_get_contents($arquivo['tmp_name']);

// Codifica o conteúdo do arquivo em base64
$base64 = base64_encode($conteudo);

// Inclui o arquivo PDO para fazer a conexão com o banco de dados
require_once 'pdo.php';

// Salva o arquivo no banco de dados, incluindo o valor do usuário
$stmt = $pdo->prepare('INSERT INTO arquivos (nome, tipo, conteudo, usuario) VALUES (?, ?, ?, ?)');
$stmt->execute([$arquivo['name'], $arquivo['type'], $base64, $usuario]);

echo 'Arquivo enviado com sucesso';
?>