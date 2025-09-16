<?php
include_once '../database/interface.php';
include_once '../views/boilerplate.php';
include_once '../views/dati_widgets.php';

session_start();
check_token_amministratore($_SESSION['token']);
generate_before_content('Classi', $_SESSION['token']);
?>

<div class='card'>
  <div class="card-body">
    <?php
        $classi = get_classi();

        $classi = array_map(function ($c) {
            return [$c['sezione'], $c['anno_inizio'], $c['anno_inizio'] + 1];
        }, $classi);

        generate_table(
            ['Sezione', 'Anno di inizio', 'Anno di fine'],
            $classi,
        );
    ?>
  </div>
</div>

<?php
generate_after_content();
?>
