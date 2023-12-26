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

<section class="content">
  <div class="container-fluid">
    <div class='card mt-4' style='max-width: 720px; margin: auto'>
      <div class="card-header">
        <button class="btn btn-primary" onclick="window.location.href='add_studente.php'">
          <i class="fas fa-plus"></i>
          Aggiungi studente
        </button>
      </div>
      <div class="card-body">
        <?php
            $studenti = get_studenti();

            $studenti = array_map(function ($s) {
                $studente_expanded = get_studente_expanded($s[0]);

                return [
                    $studente_expanded['classe'],
                    $studente_expanded['cognome_nome'],
                    $studente_expanded['ore']
                ];
            }, $studenti);

            generate_table(
                ['Classe', 'Cognome e nome', 'Ore di lezione'],
                $studenti,
                'prompt(\'Cognome e nome\', this.innerHTML);'
            );
        ?>
      </div>
    </div>
  </div>
</section>

<?php
generate_after_content();
?>