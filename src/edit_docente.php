<?php
include_once '../database/interface.php';
include_once 'navigation.php';

$mode = filter_var($_POST['mode'], FILTER_SANITIZE_STRING);

$id_docente = filter_var($_POST['id_studente'], FILTER_SANITIZE_NUMBER_INT);
$nome = mysqli_real_escape_string($connection, $_POST['nome']);
$cognome = mysqli_real_escape_string($connection, $_POST['cognome']);
$username = mysqli_real_escape_string($connection, $_POST['username']);
$password = mysqli_real_escape_string($connection, $_POST['password']);

if ($mode == 'add') {
    add_docente($nome, $cognome, $username, $password);
    go_to_docenti();
} else if ($mode == 'edit') {

} else if ($mode == 'delete') {


}