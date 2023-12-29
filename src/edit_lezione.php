<?php
include_once '../database/interface.php';
include_once 'navigation.php';

$id_lezione = intval(filter_var($_POST['id_lezione'], FILTER_SANITIZE_NUMBER_INT));

if (isset($_POST['delete'])) {
    set_lezione_eliminata($id_lezione, TRUE);
} elseif (isset($_POST['restore'])) {
    set_lezione_eliminata($id_lezione, FALSE);
}

go_to_lezioni();