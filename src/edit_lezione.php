<?php
include_once '../database/interface.php';
include_once '../views/boilerplate.php';

session_start();
check_token_amministratore($_SESSION['token']);


$id_lezione = intval(filter_var($_POST['id_lezione'], FILTER_SANITIZE_NUMBER_INT));

if (isset($_POST['delete'])) {
    set_lezione_eliminata($id_lezione, TRUE);
    go_to_lezioni('Lezione eliminata con successo');
} elseif (isset($_POST['restore'])) {
    set_lezione_eliminata($id_lezione, FALSE);
    go_to_lezioni('Lezione ripristinata con successo');
}