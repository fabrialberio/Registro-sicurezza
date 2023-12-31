<?php
include_once '../database/interface.php';
include_once 'navigation.php';
include_once 'token.php';

session_start();
$token = decode_token_or_quit($_SESSION['token']);

if (!is_amministratore_by_username($token['username'])) {
    go_to_login();
}

$id_docente = intval(filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT));
$nome = mysqli_real_escape_string($connection, $_POST['nome']);
$cognome = mysqli_real_escape_string($connection, $_POST['cognome']);
$username = mysqli_real_escape_string($connection, $_POST['username']);
$password_hash = mysqli_real_escape_string($connection, $_POST['password']);
$password_plain = mysqli_real_escape_string($connection, $_POST['password-plain']);
$password_confirm = mysqli_real_escape_string($connection, $_POST['password-confirm']);

if (!empty($password_plain) && $password_plain == $password_confirm) {
    $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);
}

if (isset($_POST['add'])) {
    try {
        add_docente($nome, $cognome, $username, $password_hash);
    } catch (\Throwable $th) {
        // TODO: mostrare errore in modo migliore
        echo "<h1>Impossibile aggiungere docente: username già esistente</h1>";
        exit();
    }
} elseif (isset($_POST['edit'])) {
    edit_docente($id_docente, $nome, $cognome, $username, $password_hash);
} elseif (isset($_POST['delete'])) {
    try {
        delete_docente($id_docente);
    } catch (\Throwable $th) {
        echo "<h1>Impossibile eliminare docente: docente presente in una lezione</h1>";
        exit();
    }
}

go_to_docenti();