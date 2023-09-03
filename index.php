<?php
include_once 'src/common.php';

if (isset($_SESSION['token'])) {
  go_to_home_page();
}

session_start();
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro sicurezza | Accesso</title>

  <link rel="stylesheet"
  href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="vendor/fortawesome/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card card-outline card-primary">
      <p class="card-header text-center h1 text-bold">Registro sicurezza</p>
      <div class="card-body">
        <?php
          if(isset($_GET["errore"])){
            echo"<p class='login-box-msg text-danger'>" . $_GET["errore"] . "</p>"; 
          }
        ?>
        <form action="src/login.php" method="post">
          <div class="input-group mb-3">
            <input type="username" name="username" class="form-control" placeholder="Username" required>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->
</body>

</html>