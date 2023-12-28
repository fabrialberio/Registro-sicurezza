<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/view_lesson_widgets.php';
include_once '../views/filter_widgets.php';
include_once '../views/view_widgets.php';

session_start();
$token = decode_token_or_quit($_SESSION['token']);
$amministratore = is_amministratore_by_username($token['username']);

generate_before_content('Lezioni', $token);


$id_docente_get = strlen($_GET['id_docente']) ? intval($_GET['id_docente']) : null;
$eliminate_get = strlen($_GET['eliminate']) ? boolval($_GET['eliminate']) : FALSE;
$id_classe_get = strlen($_GET['id_classe']) ? intval($_GET['id_classe']) : null;

$id_docente = $amministratore ? $id_docente_get : get_id_docente_by_username($token['username']);
$eliminate = $amministratore ? $eliminate_get : FALSE;
$id_classe = $id_classe_get;


$lezioni = get_lezioni_filter(
  id_docente: $id_docente,
  id_classe: $id_classe,
  eliminata: $eliminate,
);

generate_before_filters_bar('view_lessons.php');

if ($amministratore) {
  $eliminata_options = [
    0 => [TRUE, 'Eliminate'],
  ];
  generate_filter_select('eliminate', 'Non eliminate', $eliminata_options, $_GET['eliminate'] ?? null);

  $docenti_options = array_map(
      function($d) {
          return [
              0 => $d['id'],
              1 => $d['cognome_nome'],
          ];
      },
      get_docenti()
  );
  generate_filter_select('id_docente', 'Tutti i docenti', $docenti_options, $_GET['id_docente'] ?? null);
}

$classi_options = array_map(
    function($c) {
        return [
            0 => $c['id'],
            1 => $c['classe'],
        ];
    },
    get_classi()
);
generate_filter_select('id_classe', 'Tutte le classi', $classi_options, $_GET['id_classe'] ?? null);

generate_after_filters_bar();

if (count($lezioni) == 0) {
  echo "
  <div class='alert alert-info'>
    <h5><i class='icon fas fa-info'></i>Nessuna lezione trovata</h5>
    <p>
      Non ci sono lezioni da visualizzare con i filtri selezionati.
    </p>
  </div>";
} else {
  foreach ($lezioni as $lezione) {
    generate_card_lezione($lezione['id'], $amministratore);
  }
}


generate_after_content();