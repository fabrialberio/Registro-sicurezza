<?php
include_once '../database/interface.php';
include_once '../src/token.php';
include_once '../src/navigation.php';
include_once '../views/view_widgets.php';

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
        <button class="btn btn-primary" onclick="window.location.href='add_docente.php'">
          <i class="fas fa-plus"></i>
          Aggiungi docente
        </button>
      </div>
      <div class="card-body">
        <?php
            $docenti = get_docenti();

            echo "<table class='table table-sm table-bordered'>";
            
            foreach ($docenti as $d) {
                $cognome_nome = $d['cognome_nome'];
        
                echo "<tr><td>$cognome_nome</td><tr>";
            }

            echo "</table>";
        ?>
      </div>
    </div>
  </div>
</section>

<?php
generate_after_content();
?>