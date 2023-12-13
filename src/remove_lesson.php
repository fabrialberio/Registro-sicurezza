<?php
include_once '../database/interface.php';
include_once 'navigation.php';

$id_lezione = filter_var($_POST['id_lezione'], FILTER_SANITIZE_NUMBER_INT);
$restore = filter_var($_POST['restore'], FILTER_SANITIZE_NUMBER_INT);

$elimina = ($restore == 1) ? FALSE : TRUE;

set_lezione_eliminata($id_lezione, $elimina);

go_to_view_lessons();