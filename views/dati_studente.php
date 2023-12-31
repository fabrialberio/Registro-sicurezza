<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/view_widgets.php';
include_once '../views/dati_widgets.php';

session_start();
$token = decode_token_or_quit($_SESSION['token']);

if (!is_amministratore_by_username($token['username'])) {
    go_to_login();
}
?>

<?php
generate_before_content('Dati studente', $token);
?>

<div class='card'>
  <form action="../src/edit_studente.php" method="post">
    <div class='card-body'>
      <?php
      $new = isset($_GET['new']);
      
      if (!$new) {
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $studente = get_studente($id);
        $nome = $studente['nome'];
        $cognome = $studente['cognome'];
        $id_classe = $studente['id_classe'];
      } else {
        $nome = '';
        $cognome = '';
        $id_classe = null;
      }

      generate_input_dati('Nome', 'nome', $nome);
      generate_input_dati('Cognome', 'cognome', $cognome);
      generate_select_classe($id_classe);
      ?>
    </div>
    <div class='card-footer'>
      <a id='btn-cancel' href='studenti.php' class="btn btn-default">Annulla</a>
      <?php if (!$new): ?>
        <input type='hidden' name='id' value='<?php echo $id ?>'>
        <button type='submit' name='edit' class='btn btn-primary float-right'>Salva</button>
        <button type='submit' name='delete' class='btn btn-danger float-right mr-2'>Elimina</button>
      <?php else: ?>
        <button type='submit' name='add' class='btn btn-primary float-right'>Aggiungi</button>
      <?php endif; ?>
    </div>
  </form>
</div>

<?php
generate_after_content();
?>