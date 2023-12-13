<?php
include_once '../database/interface.php';
include_once 'navigation.php';

$mode = filter_var($_POST['mode'], FILTER_SANITIZE_STRING);

$id_studente = filter_var($_POST['id_studente'], FILTER_SANITIZE_NUMBER_INT);
$nome = mysqli_real_escape_string($connection, $_POST['nome']);
$cognome = mysqli_real_escape_string($connection, $_POST['cognome']);
$id_classe = filter_var($_POST['classe'], FILTER_SANITIZE_NUMBER_INT);

if ($mode == 'add') {
    add_studente($nome, $cognome, $id_classe);
    go_to_studenti();
} else if ($mode == 'edit') {

} else if ($mode == 'delete') {


}