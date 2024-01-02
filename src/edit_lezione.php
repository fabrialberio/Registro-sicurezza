<?php
include_once '../database/interface.php';
include_once '../views/boilerplate.php';
include_once 'navigation.php';

session_start();
check_token_amministratore($_SESSION['token']);


$id_lezione = intval(filter_var($_POST['id_lezione'], FILTER_SANITIZE_NUMBER_INT));

if (isset($_POST['delete'])) {
    set_lezione_eliminata($id_lezione, TRUE);
    go_back('Lezione eliminata con successo');
} elseif (isset($_POST['restore'])) {
    set_lezione_eliminata($id_lezione, FALSE);
    go_back('Lezione ripristinata con successo');
}