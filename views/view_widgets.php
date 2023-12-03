<?php
// Funzioni per generare automaticamente il boilerplate a inizio e fine pagina

function generate_before_content(string $page_title, $token) {
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
</head>

<body style='height: auto;' class='sidebar-mini layout-fixed'>
    <div class='wrapper'>";
    
    include 'sidebar.php';

    echo "
        <div class='content-wrapper'>
            <div class='content-header'>
                <h1 class='ml-2'>$page_title</h1>
            </div>";
}

function generate_after_content() {
    echo "
        </div>
    </div>

    <footer class='main-footer'>
        <div class='float-right d-none d-sm-inline'>v3.2</div>
        <strong>Copyright © 2014-2022 <a href='https://adminlte.io'>AdminLTE.io</a>.</strong> All rights reserved.
    </footer>
</body>";
}