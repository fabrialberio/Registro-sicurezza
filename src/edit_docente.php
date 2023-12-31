<?php
include_once '../database/interface.php';
include_once '../views/boilerplate.php';
include_once 'navigation.php';

session_start();
check_token_amministratore($_SESSION['token']);


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
        go_to_docenti('Docente aggiunto con successo');
    } catch (\Throwable $th) {
        go_back(error: 'Impossibile aggiungere docente: username già esistente');
    }
} elseif (isset($_POST['edit'])) {
    edit_docente($id_docente, $nome, $cognome, $username, $password_hash);
    go_back('Docente modificato con successo');
} elseif (isset($_POST['delete'])) {
    try {
        delete_docente($id_docente);
        go_to_docenti('Docente eliminato con successo');
    } catch (\Throwable $th) {
        go_back(error: 'Impossibile eliminare docente: docente presente in una lezione');
    }
}
