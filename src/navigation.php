<?php
if (isset($_GET['logout'])) {
    go_to_login('');
}

function go_to_login(?string $error = 'Sessione scaduta') {
    $location = '../index.php';

    if (!is_null($error) && !empty($error)) {
        $location = $location . '?errore=' . htmlspecialchars($error);
    }

    session_abort();
    session_destroy();
    header('Location: ' . $location);
}

function go_to_home_page() {
    header('Location: ../views/nuova_lezione.php');
}

function go_to_lezioni() {
    header('Location: ../views/lezioni.php');
}

function go_to_docenti() {
    header('Location: ../views/docenti.php');
}

function go_to_studenti() {
    header('Location: ../views/studenti.php');
}