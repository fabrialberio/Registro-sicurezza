<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/dati_widgets.php';
include_once '../views/boilerplate.php';

session_start();
check_token($_SESSION['token']);
generate_before_content('Nuova lezione', $_SESSION['token']);

if (!isset($_POST['id_classe'])) {
  go_back();
}
?>

<div class="card">
  <form action="../src/edit_lezione.php" method="post">
    <div class="card-body">
      <?php
      generate_select_classe($_POST['id_classe'], TRUE);
      generate_input_data($_POST['data']);
      generate_input_ora_inizio_fine($_POST['ora_inizio'], $_POST['ora_fine']);
      generate_datalist_argomenti();
      generate_table_select_presenze($_POST['id_classe']);
      ?>
    </div>

    <div class="card-footer">
      <button type="submit" name='add' class="btn btn-primary float-right">Aggiungi lezione</button>
      <a href='nuova_lezione.php' class="btn btn-default">Annulla</a>
    </div>
  </form>
</div>

<?php
generate_after_content();
?>