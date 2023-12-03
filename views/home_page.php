<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/new_lesson_widgets.php';
include_once '../views/view_widgets.php';

session_start();
$token = decode_token_or_quit($_SESSION['token']);

generate_before_content('Nuova lezione', $token);
?>

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

<?php
generate_after_content();
?>