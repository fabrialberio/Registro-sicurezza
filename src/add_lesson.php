<?php
include_once '../database/interface.php';
include_once 'token.php';
include_once 'navigation.php';

print_r($_POST);

session_start();
$token = decode_token_or_quit($_SESSION['token']);

// Inserisce la lezione nel database
$id_docente = get_id_docente_by_username($token['username']);

$id_classe = $_POST['classe'];
$ora_inizio = $_POST['ora_inizio'];
$ora_fine = $_POST['ora_fine'];
$data = $_POST['data'];

$id_lezione = add_lezione($id_docente, $id_classe, $ora_inizio, $ora_fine, $data);

// Inserisce gli argomenti svolti nel database
$argomenti = $_POST['argomenti'];
foreach ($argomenti as $argomento) {
    if (!empty($argomento)) {
        // Sanitizza l'input
        $argomento = mysqli_real_escape_string($connection, $argomento);
        add_argomento_svolto($id_lezione, $argomento);
    }
}

// Inserisce le presenze nel database
$studenti = get_studenti_by_classe($_POST['classe']);
foreach ($studenti as $studente) {
    $id_studente = $studente[0];

    if (isset($_POST['presenze'])) {
        $presente = in_array($id_studente, $_POST['presenze']) ? 1 : 0;
    } else {
        $presente = FALSE;
    }
    add_presenza($id_lezione, $id_studente, $presente);
}

go_to_home_page();
?>