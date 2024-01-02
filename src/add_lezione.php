<?php
include_once '../database/interface.php';
include_once '../views/boilerplate.php';

session_start();
check_token($_SESSION['token']);

// Inserisce la lezione nel database
$id_docente = token_get_id_docente($_SESSION['token']);

$id_classe = filter_var($_POST['classe'], FILTER_SANITIZE_NUMBER_INT);
$ora_inizio = mysqli_real_escape_string($connection, $_POST['ora_inizio']);
$ora_fine = mysqli_real_escape_string($connection, $_POST['ora_fine']);
$data = mysqli_real_escape_string($connection, $_POST['data']);

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
    $id_studente = filter_var($studente[0], FILTER_SANITIZE_NUMBER_INT);

    if (isset($_POST['presenze'])) {
        $presente = in_array($id_studente, $_POST['presenze']) ? 1 : 0;
    } else {
        $presente = FALSE;
    }
    add_presenza($id_lezione, $id_studente, $presente);
    
    go_to_home_page('Lezione aggiunta con successo');
}