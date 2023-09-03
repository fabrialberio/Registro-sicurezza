<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/new_lesson_widgets.php';

session_start();
$token = decode_token_or_quit($_SESSION['token']);
?>

<!DOCTYPE html>
<html style="height: auto;" lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro sicurezza | Home</title>

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../vendor/fortawesome/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="../vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css">
</head>

<body style="height: auto;" class="sidebar-mini layout-fixed">
  <div class="wrapper">
    <?php include 'sidebar.php' ?>

    <div class="content-wrapper">
      <div class="content-header">
        <h1 class="ml-2">Nuova lezione</h1>
      </div>

      <section class="content">
        <div class="container-fluid">
          <div class="card mt-4 mb-4" style="max-width: 720px; margin: auto">
            <form action="new_lesson.php" method="post">
              <div class="card-header">
                <h4 class="card-title">Dati lezione</h4>
              </div>

              <div class="card-body">
                <div class="form-group">
                  <label>Classe</label>
                  <?php generate_select_classe(); ?>
                </div>
                <div class="form-group">
                  <label>Data</label>
                  <?php generate_input_data(); ?>
                </div>
                <div class="form-group">
                  <label>Inizio e fine</label>
                  <?php generate_input_ora(); ?>
                </div>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right">Avanti</button>
              </div>
            </form>
          </div>
        </div>
      </section>
    </div>

    <footer class="main-footer">
      <div class="float-right d-none d-sm-inline">v3.2</div>
      <strong>Copyright © 2014-2022 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
    </footer>
  </div>
</body>

</html>