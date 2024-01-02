<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/boilerplate.php';
include_once '../views/dati_widgets.php';

session_start();

$new = isset($_GET['new']);
$profile = isset($_GET['profile']);

check_token_amministratore_or($_SESSION['token'], bypass: $profile);
generate_before_content('Dati docente', $_SESSION['token']);
?>

<div class='card'>
  <form action="../src/edit_docente.php" method="post">
    <div class='card-body'>
      <?php
      
      if ($new) {
        $nome = '';
        $cognome = '';
        $username = '';
        $password = null;
      } else {
        if ($profile) {
          $id = token_get_id_docente($_SESSION['token']);
        } else {
          $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        }
        
        $docente = get_docente($id);
        $nome = $docente['nome'];
        $cognome = $docente['cognome'];
        $username = $docente['username'];
        $password = $docente['password'];
      }

      generate_input_dati('Nome', 'nome', $nome);
      generate_input_dati('Cognome', 'cognome', $cognome);
      generate_input_dati('Username', 'username', $username, disabled: $profile);
      generate_input_password('password', $password, $new);
      ?>
    </div>
    <div class='card-footer'>
      <!-- TODO: In alcune situazioni, occorre premere il pulsante Annulla più volte per uscire -->
      <a id='btn-cancel' onclick='history.go(-1)' class="btn btn-default">Annulla</a>
      <?php if ($new): ?>
      <button type='submit' name='add' class='btn btn-primary float-right'>Aggiungi</button>
      <?php elseif ($profile): ?>
      <input type='hidden' name='id' value='<?php echo $id ?>'>
      <button type='submit' name='edit' class='btn btn-primary float-right'>Salva</button>
      <?php else: ?>
      <input type='hidden' name='id' value='<?php echo $id ?>'>
      <button type='submit' name='edit' class='btn btn-primary float-right'>Salva</button>
      <button type='submit' name='delete' class='btn btn-danger float-right mr-2'>Elimina</button>
      <?php endif; ?>
    </div>
  </form>
</div>

<?php
generate_after_content();
?>