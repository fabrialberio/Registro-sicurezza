<?php
include_once '../database/interface.php';
include_once 'navigation.php';

$id_studente = filter_var($_POST['id_studente'], FILTER_SANITIZE_NUMBER_INT);
$nome = mysqli_real_escape_string($connection, $_POST['nome']);
$cognome = mysqli_real_escape_string($connection, $_POST['cognome']);
$id_classe = filter_var($_POST['classe'], FILTER_SANITIZE_NUMBER_INT);

session_start();
$token = decode_token_or_quit($_SESSION['token']);

if (!is_amministratore_by_username($token['username'])) {
    go_to_login();
}


if (isset($_POST['add'])) {
    add_studente($nome, $cognome, $id_classe);
} elseif (isset($_POST['edit'])) {
    edit_studente($id_studente, $nome, $cognome, $id_classe);
} elseif (isset($_POST['delete'])) {
    delete_studente($id_studente);
}

go_to_studenti();