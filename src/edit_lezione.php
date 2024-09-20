<?php
include_once '../database/interface.php';
include_once '../views/boilerplate.php';
include_once 'navigation.php';

session_start();
check_token($_SESSION['token']);


$id_lezione = intval(filter_var($_POST['id_lezione'], FILTER_SANITIZE_NUMBER_INT));
$id_docente = intval(filter_var($_POST['id_docente'], FILTER_SANITIZE_NUMBER_INT));
$id_classe = intval(filter_var($_POST['id_classe'], FILTER_SANITIZE_NUMBER_INT));
$ora_inizio = mysqli_real_escape_string($connection, $_POST['ora_inizio']);
$ora_fine = mysqli_real_escape_string($connection, $_POST['ora_fine']);
$data = mysqli_real_escape_string($connection, $_POST['data']);
$argomenti = $_POST['argomenti'] ?? [];
$presenze = $_POST['presenze'] ?? [];

if (isset($_POST['add'])) {
    $id_docente = token_get_id_docente($_SESSION['token']);
    $id_lezione = add_lezione($id_docente, $id_classe, $ora_inizio, $ora_fine, $data);

    foreach ($argomenti as $argomento) {
        if (!empty($argomento)) {
            $argomento = mysqli_real_escape_string($connection, $argomento);
            add_argomento_svolto($id_lezione, $argomento);
        }
    }

    $studenti = get_studenti_by_classe($id_classe);
    foreach ($studenti as $studente) {
        $id_studente = filter_var($studente[0], FILTER_SANITIZE_NUMBER_INT);
        
        $presente = in_array($id_studente, $presenze) ? 1 : 0;
        
        add_presenza($id_lezione, $id_studente, $presente);
    }

    go_to_home_page('Lezione aggiunta con successo');
} elseif (isset($_POST['delete'])) {
    check_token_amministratore($_SESSION['token']);
    set_lezione_eliminata($id_lezione, TRUE);
    go_back('Lezione eliminata con successo');
} elseif (isset($_POST['restore'])) {
    check_token_amministratore($_SESSION['token']);
    set_lezione_eliminata($id_lezione, FALSE);
    go_back('Lezione ripristinata con successo');
} elseif (isset($_POST['edit'])) {
    check_token_amministratore($_SESSION['token'], $id_docente);
    edit_lezione($id_lezione, $id_docente, $id_classe, $ora_inizio, $ora_fine, $data);

    $studenti = get_studenti_by_classe($id_classe);
    foreach ($studenti as $studente) {
        $id_studente = $studente['id'];
        $presente = in_array($id_studente, $presenze) ? 1 : 0;

        edit_presenza_by_lezione_and_studente($id_lezione, $id_studente, $presente);
    }
    
    go_back('Lezione modificata con successo');
}