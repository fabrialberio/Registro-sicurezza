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
generate_before_content('Dati docente', $token);
?>

<div class='card'>
  <form action="../src/edit_docente.php" method="post">
    <?php
    $id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

    if ($id != null) {
      $docente = get_docente($id);
      $nome = $docente['nome'];
      $cognome = $docente['cognome'];
      $username = $docente['username'];
      $password = $docente['password'];
    } else {
      $nome = '';
      $cognome = '';
      $username = '';
      $password = '';
    }

    echo "
    <div class='card-body'>
      <input type='hidden' name='mode' value='add'>
      <div class='form-group'>
        <label>Nome</label>
        <input type='text' class='form-control' name='nome' placeholder='Nome' value='$nome' required>
      </div>
      <div class='form-group'>
        <label>Cognome</label>
        <input type='text' class='form-control' name='cognome' placeholder='Cognome' value='$cognome' required>
      </div>
      <div class='form-group'>
        <label>Username</label>
        <input type='text' class='form-control' name='username' placeholder='Username' value='$username' required>
      </div>
      <div class='form-group'>
        <label>Password</label>
        <input type='text' class='form-control' name='password' placeholder='Password' value='$password' required>
      </div>
    </div>
    ";
    ?>
    <div class='card-footer'>
      <a href='docenti.php' class="btn btn-default">Annulla</a>
      <?php if ($id != null): ?>
      <button type='submit' class='btn btn-primary float-right'>Salva</button>
      <button type='submit' class='btn btn-danger float-right mr-2'>Elimina</button>
      <?php else: ?>
      <button type='submit' class='btn btn-primary float-right'>Aggiungi</button>
      <?php endif; ?>
    </div>
  </form>
</div>

<?php
generate_after_content();
?>