<?php
include_once '../database/interface.php';
include_once 'navigation.php';
include_once 'token.php';

session_start();
$token = decode_token_or_quit($_SESSION['token']);

if (!is_amministratore_by_username($token['username'])) {
    go_to_login();
}

$id_studente = intval(filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT));
$nome = mysqli_real_escape_string($connection, $_POST['nome']);
$cognome = mysqli_real_escape_string($connection, $_POST['cognome']);
$id_classe = intval(filter_var($_POST['classe'], FILTER_SANITIZE_NUMBER_INT));

if (isset($_POST['add'])) {
    add_studente($nome, $cognome, $id_classe);
} elseif (isset($_POST['edit'])) {
    edit_studente($id_studente, $nome, $cognome, $id_classe);
} elseif (isset($_POST['delete'])) {
    try {
        delete_studente($id_studente);
    } catch (\Throwable $th) {
        echo "<h1>Impossibile eliminare studente: studente presente in una lezione</h1>";
        exit();
    }
}

go_to_studenti();