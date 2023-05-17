<?php
    require_once 'vendor/autoload.php';

    $loader = new \Twig\Loader\FilesystemLoader('/caminho/para/os/templates');
    $twig = new \Twig\Environment($loader, [
        'debug' => true, // opcional: ativar modo de depuração
        'cache' => '/caminho/para/o/cache', // opcional: definir pasta de cache
        'auto_reload' => true, // opcional: recarregar automaticamente os templates quando forem alterados
        'strict_variables' => true, // opcional: forçar a declaração de todas as variáveis
        'autoescape' => 'html', // opcional: ativar a autoescape para escapar automaticamente o conteúdo HTML
        'assets' => '/caminho/para/os/assets' // defina a pasta de ativos (assets)
    ]);
