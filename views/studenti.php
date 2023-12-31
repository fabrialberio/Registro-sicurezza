<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/view_widgets.php';
include_once '../views/dati_widgets.php';
include_once '../views/filter_widgets.php';

session_start();
$token = decode_token_or_quit($_SESSION['token']);

if (!is_amministratore_by_username($token['username'])) {
    go_to_login();
}

generate_before_content('Studenti', $token);


$id_classe = !empty($_GET['id_classe']) ? intval($_GET['id_classe']) : null;
$min_ore = !empty($_GET['min_ore']) ? intval($_GET['min_ore']) : null;
$max_ore = !empty($_GET['max_ore']) ? intval($_GET['max_ore']) : null;

$studenti = get_studenti_filter(
  id_classe: $id_classe,
  min_ore: $min_ore,
  max_ore: $max_ore
);

generate_before_filters_bar('studenti.php');

$classi_options = array_map(
    function($c) {
        return [
            0 => $c['id'],
            1 => $c['classe'],
        ];
    },
    get_classi()
);

generate_filter_select('id_classe', 'Tutte le classi', $classi_options, $id_classe ?? null);

generate_filter_int_input('min_ore', 'Ore minime', $min_ore, 0, 50);
generate_filter_int_input('max_ore', 'Ore massime', $max_ore, 0, 50);

generate_after_filters_bar();

?>

<div class='card'>
  <div class="card-header">
    <button class="btn btn-primary" onclick="window.location.href='add_studente.php'">
      <i class="fas fa-plus"></i>
      Aggiungi studente
    </button>
  </div>
  <div class="card-body">
    <?php
        $studenti_map = array_map(function ($s) {
            $studente_expanded = get_studente_expanded($s[0]);

            return [
                $studente_expanded['classe'],
                $studente_expanded['cognome_nome'],
                $studente_expanded['ore']
            ];
        }, $studenti);

        if (empty($studenti_map)) {
            echo "
            <div class='alert alert-info'>
                <h5><i class='icon fas fa-info'></i>Nessuno studente trovato</h5>
                <p>
                    Non ci sono studenti da visualizzare con i filtri selezionati.
                </p>
            </div>";
        } else {
            generate_table(
                ['Classe', 'Cognome e nome', 'Ore di lezione'],
                $studenti_map
            );
        }
    ?>
  </div>
</div>

<?php
generate_after_content();
?>