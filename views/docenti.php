<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/boilerplate.php';
include_once '../views/dati_widgets.php';

session_start();
check_token_amministratore($_SESSION['token']);
generate_before_content('Docenti', $_SESSION['token']);

$id_docente_token = token_get_id_docente($_SESSION['token']);
?>

<div class='card'>
  <div class="card-header">
    <button class="btn btn-primary" onclick="window.location.href='dati_docente.php?new'">
      <i class="fas fa-plus"></i>
      Aggiungi docente
    </button>
  </div>
  <div class="card-body">
    <?php
        $docenti = get_docenti();

        $on_row_click = array_map(function ($d) use ($id_docente_token) {
          if ($d[0] == $id_docente_token) {
            return 'dati_docente.php?profile';
          } else {
            return 'dati_docente.php?id=' . $d[0];
          }
        }, $docenti);
        
        $docenti = array_map(function ($d) {
          return [$d[1], $d[2]];
        }, $docenti);

        generate_table(
            ['Cognome e nome', 'Username'],
            $docenti,
            $on_row_click
        );
    ?>
  </div>
</div>

<?php
generate_after_content();
?>