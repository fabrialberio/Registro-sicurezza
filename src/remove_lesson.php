<?php
include_once '../database/interface.php';
include_once 'navigation.php';

$id_lezione = filter_var($_POST['id_lezione'], FILTER_SANITIZE_NUMBER_INT);

print_r("DELETE FROM lezione
        WHERE id=$id_lezione");

set_lezione_eliminata($id_lezione);

go_to_view_lessons();