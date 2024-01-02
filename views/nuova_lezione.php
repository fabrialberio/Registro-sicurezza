<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/dati_widgets.php';
include_once '../views/boilerplate.php';

session_start();
check_token($_SESSION['token']);
generate_before_content('Nuova lezione', $_SESSION['token']);
?>

<div class="card">
  <form action="nuova_lezione_presenze.php" method="post">
    <div class="card-body">
      <?php
      generate_select_classe();
      generate_input_data();
      generate_input_ora_inizio_fine();
      ?>
    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-primary float-right">Avanti</button>
    </div>
  </form>
</div>

<?php
generate_after_content();
?>