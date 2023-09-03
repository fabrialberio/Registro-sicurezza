<?php
include_once '../database/interface.php';

// TODO: L'ora non è proprio proprio corretta perché è l'ora del server e non dell'utente
date_default_timezone_set('Europe/Rome');

function generate_select_classe(?int $id_classe = null, bool $disabled = FALSE) {
    $classi = get_classi();

    if (!is_null($id_classe)) {
        $classe_selezionata = get_classe($id_classe);
        $classi = [$classe_selezionata];
    }

    echo "<select name='classe' class='custom-select' " . ($disabled ? "disabled" : "") . ">";

    foreach ($classi as $classe) {
        echo "<option value='" . strval($classe[0]) . "'>" . $classe[1] . "</option>";
    }

    echo "</select>";
    
    // Input nascosto per inoltrare il valore di 'classe' al form anche se il select è disabilitato
    if ($disabled) {
        echo "<input type='hidden' name='classe' value='" . strval($id_classe) . "'>";
    }
}

function generate_input_data(?string $data = null) {
    if (is_null($data)) {
        $data = date('Y-m-d');
    }

    echo "<input type='date' name='data' class='form-control' value='$data'/>";
}

function generate_input_ora(?string $ora_inizio = null, ?string $ora_fine = null) {
    if (is_null($ora_inizio)) {
        $ora_inizio = date('H:00');
    }
    if (is_null($ora_fine)) {
        $ora_fine =  date('H:00', strtotime('+1 hour', strtotime($ora_inizio)));
    }

    echo "<input id='inputOraInizio' type='time' step='3600' name='ora_inizio' class='form-control mb-1' value='$ora_inizio' min='08:00', max='15:00'/>";
    echo "<input id='inputOraFine' type='time' step='3600' name='ora_fine' class='form-control' value='$ora_fine' min='09:00', max='16:00'/>";
}

function generate_datalist_argomenti() {
    $argomenti = get_argomenti();

    echo "<datalist id='argomenti'>";

    foreach ($argomenti as $a) {
        $titolo = $a['titolo'];
        echo "<option value='$titolo'/>";
    }

    echo "</datalist>";
}

function generate_table_select_presenze($id_classe) {
    $studenti = get_studenti_by_classe($id_classe);

    echo "<table class='table table-hover table-sm'>";

    foreach ($studenti as $s) {
        $id = $s[0];
        $nome = strval($s[1]);

        $checkbox_id = "presenza_studente_$id";

        echo "<tr onclick='document.getElementById(\"$checkbox_id\").click();'>
            <td>$nome</td>
            <td style='width: 1%; white-space: nowrap;'>
                <input id='$checkbox_id' type='checkbox' name='presenze[]' value='$id' checked=1 onclick='event.stopPropagation();'/>
            </td>
        </tr>";
    }

    echo "</table>";
}