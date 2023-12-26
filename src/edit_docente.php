<?php
include_once '../database/interface.php';
include_once 'navigation.php';

$mode = filter_var($_POST['mode'], FILTER_SANITIZE_STRING);

$id_docente = filter_var($_POST['id_studente'], FILTER_SANITIZE_NUMBER_INT);
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
    add_docente($nome, $cognome, $username, $password);
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