<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/view_widgets.php';

session_start();
$token = decode_token_or_quit($_SESSION['token']);

if (!is_amministratore_by_username($token['username'])) {
    go_to_login();
}
?>

<?php
generate_before_content('Aggiungi docente', $token);
?>

<section class="content">
  <div class="container-fluid">
    <div class='card mt-4' style='max-width: 720px; margin: auto'>
      <form action="../src/edit_docente.php" method="post">
        <div class="card-header">
          <h4 class="card-title">Dati docente</h4>
        </div>
        <div class="card-body">
          <input type="hidden" name="mode" value="add">
          <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="nome" placeholder="Nome" required>
          </div>
          <div class="form-group">
            <label>Cognome</label>
            <input type="text" class="form-control" name="cognome" placeholder="Cognome" required>
          </div>
          <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username" required>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="text" class="form-control" name="password" placeholder="Password" required>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary float-right">Aggiungi</button>
        </div>
      </form>
    </div>
  </div>
</section>



<?php
generate_after_content();
?>