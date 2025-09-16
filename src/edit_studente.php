<?php
include_once '../database/interface.php';
include_once '../views/boilerplate.php';
include_once 'navigation.php';

session_start();
check_token_amministratore($_SESSION['token']);


$id_studente = intval(filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT));
$nome = mysqli_real_escape_string($connection, $_POST['nome']);
$cognome = mysqli_real_escape_string($connection, $_POST['cognome']);
$id_classe = intval(filter_var($_POST['classe'], FILTER_SANITIZE_NUMBER_INT));
$nascosto = $_POST['nascosto'] == '1' ? 1 : 0;


if (isset($_POST['add'])) {
    add_studente($nome, $cognome, $id_classe, $nascosto);
    go_to_studenti('Studente aggiunto con successo');
} elseif (isset($_POST['edit'])) {
    edit_studente($id_studente, $nome, $cognome, $id_classe, $nascosto);
    go_back('Studente modificato con successo');
} elseif (isset($_POST['delete'])) {
    try {
        delete_studente($id_studente);
        go_to_studenti('Studente eliminato con successo');
    } catch (\Throwable $th) {
        go_back(error: 'Impossibile eliminare studente: studente presente in una lezione');
    }
}