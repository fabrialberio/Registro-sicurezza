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
    header(_append_toast_headers('Location: ../views/nuova_lezione.php', $success, $error));
}

function go_back(?string $success = null, ?string $error = null) {
    if (isset($_SERVER['HTTP_REFERER'])) {
        header(_append_toast_headers('Location: ' . $_SERVER['HTTP_REFERER'], $success, $error));
    } else {
        go_to_home_page($success, $error);
    }
}

function go_to_lezioni(?string $success = null, ?string $error = null) {
    header(_append_toast_headers('Location: ../views/lezioni.php', $success, $error));
}

function go_to_docenti(?string $success = null, ?string $error = null) {
    header(_append_toast_headers('Location: ../views/docenti.php', $success, $error));
}

function go_to_studenti(?string $success = null, ?string $error = null) {
    header(_append_toast_headers('Location: ../views/studenti.php', $success, $error));
}

function _append_toast_headers(string $url, ?string $success = null, ?string $error = null) {
    $success_string = !is_null($success) ? "success=" . htmlspecialchars($success) : '';
    $error_string = !is_null($error) ? "error=" . htmlspecialchars($error) : '';

    if (!empty($success_string) && !empty($error_string)) {
        $query_string = "$success_string&$error_string";
    } else if (!empty($success_string)) {
        $query_string = $success_string;
    } else if (!empty($error_string)) {
        $query_string = $error_string;
    } else {
        return $url;
    }

    if (strpos($url, '?') !== false) {
        return $url . '&' . $query_string;
    } else {
        return $url . '?' . $query_string;
    }
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