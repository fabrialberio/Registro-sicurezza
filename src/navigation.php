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

function go_to_home_page(?string $success = null, ?string $error = null) {
    header('Location: ../views/nuova_lezione.php' . _generate_toast_headers($success, $error));
}

function go_to_lezioni(?string $success = null, ?string $error = null) {
    header('Location: ../views/lezioni.php' . _generate_toast_headers($success, $error));
}

function go_to_docenti(?string $success = null, ?string $error = null) {
    header('Location: ../views/docenti.php' . _generate_toast_headers($success, $error));
}

function go_to_studenti(?string $success = null, ?string $error = null) {
    header('Location: ../views/studenti.php' . _generate_toast_headers($success, $error));
}

function _generate_toast_headers(?string $success = null, ?string $error = null) {
    $success_string = !is_null($success) ? "success=" . htmlspecialchars($success) : '';
    $error_string = !is_null($error) ? "error=" . htmlspecialchars($error) : '';

    if (!empty($success_string) && !empty($error_string)) {
        $query_string = "$success_string&$error_string";
    } else if (!empty($success_string)) {
        $query_string = $success_string;
    } else if (!empty($error_string)) {
        $query_string = $error_string;
    }

    return !empty($query_string) ? "?$query_string" : '';
}

function show_toast(string $message, string $type = 'success') {
    echo "
    <script>
        toastr.options = {
            'closeButton': true,
        };
        toastr.$type('$message');
    </script>";
}