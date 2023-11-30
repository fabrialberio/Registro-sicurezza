<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/docenti_widgets.php';
include_once '../views/filter_widgets.php';

session_start();
$token = decode_token_or_quit($_SESSION['token']);

if (!is_amministratore_by_username($token['username'])) {
    go_to_login();
}
?>

<!DOCTYPE html>
<html style="height: auto;" lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro sicurezza | Docenti</title>
  
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../vendor/fortawesome/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="../vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css">
</head>

<body style="height: auto;" class="sidebar-mini layout-fixed">
  <?php include 'sidebar.php' ?>

  <div class="content-wrapper">
    <div class="content-header">
      <h1 class="ml-2">Docenti</h1>
    </div>
    
    <section class="content pb-5">
      <?php
        generate_before_filters_bar();

        $classi_options = array_map(
            function($c) {
                return [
                    0 => $c['id'],
                    1 => $c['classe'],
                ];
            },
            get_classi()
        );
        generate_filter_select('id_classe', 'Classe', $classi_options, $_GET['id_classe'] ?? null);

        generate_after_filters_bar();

        generate_table_docenti();
      ?>
    </section>
  </div>

  <footer class="main-footer">
      <div class="float-right d-none d-sm-inline">v3.2</div>
      <strong>Copyright © 2014-2022 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
    </footer>
</body>

</html>