<?php
    require_once 'carregar_twig.php';
    require_once 'verifica_sessao.php';

    echo $twig->render('compartilhar.html');