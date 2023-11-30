<?php
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'logout') {
        go_to_login();
    }
}

function go_to_login(?string $error = 'Sessione scaduta') {
    $location = '../index.php';

    if (!is_null($error)) {
        $location = $location . '?errore=' . htmlspecialchars($error);
    }

    session_abort();
    session_destroy();
    header('Location: ' . $location);
}

function go_to_home_page() {
    header('Location: ../views/home_page.php');
}

function go_to_view_lessons() {
    header('Location: ../views/view_lessons.php');
}