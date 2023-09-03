<?php
include_once '../database/interface.php';
include_once 'token.php';
include_once 'navigation.php';

$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
$id_docente = get_id_docente_by_username($username);

if (!is_null($id_docente)) {
    $docente = get_docente($id_docente);
    
    if (password_verify($_POST['password'], $docente['password'])) {
        session_start();

        $_SESSION['token'] = get_token($docente['username']);

        go_to_home_page();
    } else {
        // Password errata
        go_to_login('Credenziali non corrette');
    };
} else {
    // Username non esistente
    go_to_login('Credenziali non corrette');
}