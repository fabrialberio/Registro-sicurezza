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

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
$lezione = get_lezione($id);
$ora_inizio = $lezione['ora_inizio'];
$ora_fine = $lezione['ora_fine'];
$data = $lezione['data'];
$aggiunta = $lezione['aggiunta'];
$eliminata = $lezione['eliminata'];

$lezione_exp = get_lezione_expanded($id);
$docente = $lezione_exp['docente'];
$classe = $lezione_exp['classe'];
?>

<div class='card'>
  <form action="../src/edit_lezione.php" method="post">
    <div class='card-body'>
      <input type='hidden' name='id_lezione' value='<?php echo $id ?>'>
      <input type='hidden' name='id_docente' value='<?php echo $lezione['id_docente'] ?>'>
      <input type='hidden' name='id_classe' value='<?php echo $lezione['id_classe'] ?>'>
      <dl>
        <dt>Docente</dt>
        <dd><?php echo $docente ?></dd>
        <dt>Data e ora di aggiunta</dt>
        <dd><?php echo $aggiunta ?></dd>
        <dt>Classe</dt>
        <dd><?php echo $classe ?></dd>
        <dt>Argomenti</dt>
        <dd style='max-widht: 100%'><?php generate_table_argomenti_svolti($id); ?></dd>
      </dl>
      <?php
      generate_input_data(gmdate("Y-m-d", $data+(3600*24))); // Aggiunge un giorno che viene sottratto dalla conversione
      generate_input_ora_inizio_fine($ora_inizio, $ora_fine);
      generate_table_select_presenze($id_classe, $id);
      ?>
    </div>
    <div class='card-footer'>
      <a id='btn-cancel' href='lezioni.php' class="btn btn-default">Annulla</a>
      <button type='submit' name='edit' class='btn btn-primary float-right'>Salva</button>
      <button type='submit' name='delete' class='btn btn-danger float-right mr-2'>Elimina</button>
    </div>
  </form>
</div>


<?php
generate_after_content();