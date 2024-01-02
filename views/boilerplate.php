<?php
include_once '../src/navigation.php';
include_once '../src/token.php';
// Funzioni per generare automaticamente il boilerplate a inizio e fine pagina

function check_token(string $jwt) {
    if (!token_is_valid($jwt)) {
        go_to_login();
    }
}

function check_token_amministratore(string $jwt) {
    check_token($jwt);

    if (!token_is_amministratore($jwt)) {
        go_to_login();
    }
}

function check_token_amministratore_or(string $jwt, bool $bypass) {
    if ($bypass) {
        check_token($jwt);
    } else {
        check_token_amministratore($jwt);
    }
}


function generate_before_content(string $page_title, string $jwt) {
    $amministratore = token_is_amministratore($jwt);
    $id_docente = token_get_id_docente($jwt);

    echo "
<!DOCTYPE html>
<html style='height: auto;' lang='it'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Registro sicurezza | $page_title</title>
    
    <link rel='stylesheet'
        href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback'>
    <link rel='stylesheet' href='../vendor/fortawesome/font-awesome/css/all.min.css'>
    <link rel='stylesheet' href='../vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css'>
    <script src='http://code.jquery.com/jquery-1.9.1.min.js'></script>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css' rel='stylesheet'/>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js'></script>
</head>

<body style='height: auto;' class='sidebar-mini layout-fixed'>
    <div class='wrapper'>";

    include 'sidebar.php';

    echo "
        <div class='content-wrapper pb-4'>
            <div style='max-width: 720px; margin: auto'>
                <div class='content-header mb-2'>
                    <h1>$page_title</h1>
                </div>
                <section class='content ml-2 mr-2'>";

    if (isset($_GET['error'])) {
        $error = htmlspecialchars($_GET['error']);
        show_toast($error, 'error');
    }

    if (isset($_GET['success'])) {
        $success = htmlspecialchars($_GET['success']);
        show_toast($success);
    }
}

function generate_after_content() {
    echo "
                </section>
            </div>
        </div>
    </div>

    <footer class='main-footer'>
        <div class='float-right d-none d-sm-inline'>v3.2</div>
        <strong>Copyright © 2014-2022 <a href='https://adminlte.io'>AdminLTE.io</a>.</strong> All rights reserved.
    </footer>
</body>";
}