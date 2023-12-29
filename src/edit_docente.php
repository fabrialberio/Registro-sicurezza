<?php
include_once '../database/interface.php';
include_once 'navigation.php';
include_once 'token.php';

$mode = filter_var($_POST['mode'], FILTER_SANITIZE_STRING);

$id_docente = intval(filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT));
$nome = mysqli_real_escape_string($connection, $_POST['nome']);
$cognome = mysqli_real_escape_string($connection, $_POST['cognome']);
$username = mysqli_real_escape_string($connection, $_POST['username']);
$password = mysqli_real_escape_string($connection, $_POST['password']);

session_start();
$token = decode_token_or_quit($_SESSION['token']);

if (!is_amministratore_by_username($token['username'])) {
    go_to_login();
}


switch ($mode) {
case 'add':
    try {
        add_docente($nome, $cognome, $username, $password);
    } catch (\Throwable $th) {
        // TODO: mostrare errore in modo migliore
        echo "<h1>Impossibile aggiungere docente: username già esistente</h1>";
        print($th);
        break;
    }
    go_to_docenti();

    break;

case 'edit':
    edit_docente($id_docente, $nome, $cognome, $username, $password);
    go_to_docenti();
    break;

case 'delete':
    delete_docente($id_docente);
    go_to_docenti();
    break;

default:
    go_to_docenti();
    break;
}