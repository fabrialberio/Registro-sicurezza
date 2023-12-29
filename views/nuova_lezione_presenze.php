<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/nuova_lezione_widgets.php';
include_once '../views/view_widgets.php';

session_start();
$token = decode_token_or_quit($_SESSION['token']);

if (!isset($_POST['classe'])) {
  go_to_home_page();
}

generate_before_content('Nuova lezione', $token);
?>

<script src="../src/inputArgomenti.js"></script>
<script>
  window.onload = function () {
    const divArgomenti = document.getElementById("divArgomenti");

    addInputArgomento(divArgomenti);
  }
</script>

<div class="card">
  <form action="../src/add_lezione.php" method="post">
    <div class="card-body">
      <div class="form-group">
        <label>Classe</label>
        <?php generate_select_classe($_POST['classe'], TRUE); ?>
      </div>
      <div class="form-group">
        <label>Data</label>
        <?php generate_input_data($_POST['data']); ?>
      </div>
      <div class="form-group">
        <label>Inizio e fine</label>
        <?php generate_input_ora($_POST['ora_inizio'], $_POST['ora_fine']); ?>
      </div>
      <div class="form-group">
        <label>Argomenti</label>
        <div id='divArgomenti'>
        </div>
        <?php generate_datalist_argomenti(); ?>
      </div>
      <div class="form-group">
        <label>Presenze</label>
        <div class="card">
          <div class="card-body table-responsive p-0">
            <?php generate_table_select_presenze($_POST['classe']); ?>
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer">
      <button type="submit" class="btn btn-primary float-right">Aggiungi lezione</button>
      <a href='nuova_lezione.php' class="btn btn-default">Annulla</a>
    </div>
  </form>
</div>

<?php
generate_after_content();
?>