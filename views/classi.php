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

generate_before_content('Classi', $token);
?>

<section class="content">
  <div class="container-fluid">
    <div class='card mt-4' style='max-width: 720px; margin: auto'>
      <div class="card-body">
        <?php
            $classi = get_classi();

            $classi = array_map(function ($c) {
                return [$c[1]];
            }, $classi);

            generate_table(
                ['Anno e sezione'],
                $classi,
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