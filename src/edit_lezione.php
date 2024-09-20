<?php
include_once '../database/interface.php';
include_once '../views/boilerplate.php';
include_once 'navigation.php';

session_start();
check_token_amministratore($_SESSION['token']);


$id_lezione = intval(filter_var($_POST['id_lezione'], FILTER_SANITIZE_NUMBER_INT));
$id_docente = intval(filter_var($_POST['id_docente'], FILTER_SANITIZE_NUMBER_INT));
$id_classe = intval(filter_var($_POST['id_classe'], FILTER_SANITIZE_NUMBER_INT));
$ora_inizio = mysqli_real_escape_string($connection, $_POST['ora_inizio']);
$ora_fine = mysqli_real_escape_string($connection, $_POST['ora_fine']);
$data = mysqli_real_escape_string($connection, $_POST['data']);


if (isset($_POST['delete'])) {
    set_lezione_eliminata($id_lezione, TRUE);
    go_back('Lezione eliminata con successo');
} elseif (isset($_POST['restore'])) {
    set_lezione_eliminata($id_lezione, FALSE);
    go_back('Lezione ripristinata con successo');
} elseif (isset($_POST['edit'])) {
    edit_lezione($id_lezione, $id_docente, $id_classe, $ora_inizio, $ora_fine, $data);
    go_back('Lezione modificata con successo');
}