<?php
include_once '../database/interface.php';
include_once 'navigation.php';
include_once 'token.php';

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

if (isset($_POST['add'])) {
    try {
        add_docente($nome, $cognome, $username, $password);
    } catch (\Throwable $th) {
        // TODO: mostrare errore in modo migliore
        echo "<h1>Impossibile aggiungere docente: username già esistente</h1>";
        print($th);
        exit();
    }
} elseif (isset($_POST['edit'])) {
    edit_docente($id_docente, $nome, $cognome, $username, $password);
} elseif (isset($_POST['delete'])) {
    delete_docente($id_docente);
}

go_to_docenti();