<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/view_widgets.php';
include_once '../views/list_widgets.php';

session_start();
$token = decode_token_or_quit($_SESSION['token']);

if (!is_amministratore_by_username($token['username'])) {
    go_to_login();
}

generate_before_content('Docenti', $token);
?>

<div class='card'>
  <div class="card-header">
    <button class="btn btn-primary" onclick="window.location.href='dati_docente.php'">
      <i class="fas fa-plus"></i>
      Aggiungi docente
    </button>
  </div>
  <div class="card-body">
    <?php
        $docenti = get_docenti();

        $on_row_click = array_map(function ($d) {
          return 'dati_docente.php?id=' . $d[0];
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