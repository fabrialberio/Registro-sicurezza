<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/boilerplate.php';
include_once '../views/dati_widgets.php';
include_once '../views/lezioni_widgets.php';

session_start();
check_token_amministratore($_SESSION['token']);
generate_before_content('Dati lezione', $_SESSION['token']);
?>

<div class='card'>
  <form action="../src/edit_lezione.php" method="post">
    <div class='card-body'>
      <?php
      
      ?>
    </div>
    <div class='card-footer'>
      <a id='btn-cancel' href='lezioni.php' class="btn btn-default">Annulla</a>
      <input type='hidden' name='id' value='<?php echo $id ?>'>
      <button type='submit' name='edit' class='btn btn-primary float-right'>Salva</button>
      <button type='submit' name='delete' class='btn btn-danger float-right mr-2'>Elimina</button>
    </div>
  </form>
</div>


<?php
generate_after_content();