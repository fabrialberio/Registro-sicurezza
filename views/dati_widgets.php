<?php
include_once '../database/interface.php';

// TODO: L'ora non è proprio proprio corretta perché è l'ora del server e non dell'utente
date_default_timezone_set('Europe/Rome');

function generate_select_classe(?int $id_classe = null, bool $disabled = FALSE) {
    echo "
    <div class='form-group'>
        <label>Classe</label>
            <select name='classe' class='custom-select' " . ($disabled ? "disabled" : "") . ">";
        
    $classi = get_classi();
    foreach ($classi as $classe) {
        $questo_id = $classe[0];
        $selected_string = $id_classe == $questo_id ? "selected" : "";

        echo "<option value='$questo_id' $selected_string>" . $classe[1] . "</option>";
    }

    echo "</select>";
    
    if ($disabled) {
        echo "<input type='hidden' name='classe' value='" . strval($id_classe) . "'>";
    }

    echo "</div>";
}

function generate_input_data(?string $data = null) {
    echo "
    <div class='form-group'>
        <label>Data</label>";

    if (is_null($data)) {
        $data = date('Y-m-d');
    }

    echo "
        <input type='date' name='data' class='form-control' value='$data'/>
    </div>";
}

function generate_input_ora_inizio_fine(?string $ora_inizio = null, ?string $ora_fine = null) {
    // Genera due dropdown, con le ore dalle 8:00 alle 16:00
    
    echo "
    <div class='form-group'>
        <label>Inizio e fine</label>";

    if (is_null($ora_inizio)) {
        $ora_inizio = date('H:00');
    }
    if (is_null($ora_fine)) {
        $ora_fine =  date('H:00', strtotime('+1 hour', strtotime($ora_inizio)));
    }

    echo "<select name='ora_inizio' class='custom-select mb-1'>";
    for ($i = 8; $i <= 15; $i++) {
        $ora = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        echo "<option value='$ora'" . ($ora == $ora_inizio ? ' selected' : '') . ">$ora</option>";
    }
    echo "</select>";

    echo "<select name='ora_fine' class='custom-select'>";
    for ($i = 9; $i <= 16; $i++) {
        $ora = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        echo "<option value='$ora'" . ($ora == $ora_fine ? ' selected' : '') . ">$ora</option>";
    }
    echo "
        </select>
    </div>";
}

function generate_datalist_argomenti() {
    echo "
    <script src='../src/inputArgomenti.js'></script>
    <script>
        window.onload = function () {
            const divArgomenti = document.getElementById('divArgomenti');
            addInputArgomento(divArgomenti);
        }
    </script>";

    echo "
    <div class='form-group'>
        <label>Argomenti</label>
        <div id='divArgomenti'>";

    $argomenti = get_argomenti();

    echo "<datalist id='argomenti'>";

    foreach ($argomenti as $a) {
        $titolo = $a['titolo'];
        echo "<option value='$titolo'/>";
    }

    echo "
            </datalist>
        </div>
    </div>";
}

function generate_table_select_presenze($id_classe) {
    echo "
    <div class='form-group'>
        <label>Presenze</label>
        <div class='card'>
            <div class='card-body table-responsive p-0'>
                <table class='table table-hover table-sm'>";

    $studenti = get_studenti_by_classe($id_classe);

    foreach ($studenti as $s) {
        $id = $s[0];
        $nome = strval($s[1]);

        $checkbox_id = "presenza_studente_$id";

        echo "
        <tr onclick='document.getElementById(\"$checkbox_id\").click();'>
            <td style='width: 1%; white-space: nowrap;'>
                <input id='$checkbox_id' class='mr-3' type='checkbox' name='presenze[]' value='$id' checked=1 onclick='event.stopPropagation();'/>
            </td>
            <td>$nome</td>
        </tr>";
    }

    echo "
                </table>
            </div>
        </div>
    </div>";
}

function generate_table(array $headers, array $row_data, array $on_row_click = null) {
    $clickable_row = $on_row_click != null;
    $hover_class = $clickable_row ? 'table-hover' : '';
    
    echo "<table class='table table-sm table-bordered $hover_class'>";
    
    echo "<thead><tr>";
    foreach ($headers as $header) {
        echo "<th>$header</th>";
    }
    echo "</tr></thead>";

    echo "<tbody>";
    foreach ($row_data as $i => $row) {
        $row = $row_data[$i];

        $on_click = $clickable_row ?
            "onclick=\"window.location.href='" . $on_row_click[$i] . "'\"" :
            '';

        echo "<tr $on_click>";
        foreach ($row as $cell) {
            echo "<td>$cell</td>";
        }
        echo "</tr>";
    }
    
    echo "</tbody></table>";
}

function generate_input_dati(string $label, string $name, string $value = null) {
    $value_string = $value != null ? "value='$value'" : '';
    
    echo "
    <div class='form-group'>
        <label>$label</label>
        <input type='text' class='form-control' name='$name' placeholder='$label' $value_string required>
    </div>";
}

function generate_input_password(string $name, string|null $password_hash, bool $new = false) {
    echo "<script src='../src/inputPassword.js'></script>";

    if ($new) {
        echo "
        <script>
            window.onload = function() {
                showEditPassword(true);
            }
        </script>";
    }

    $cancel_button_hidden = $new ? 'hidden' : '';

    echo "
    <div id='group-password-placeholder' class='form-group'>
        <label>Password</label>
        <button class='btn btn-flat float-right' type='button' onclick='showEditPassword(true)'>
            <i class='fas fa-pen'></i>
            Modifica
        </button>
        <div class='input-group'>
            <input type='text' class='form-control' name='password-placeholder' value='●●●●●●●●' disabled>
            <div class='input-group-append'>
            </div>            
        </div>
    </div>
    <div id='group-password-edit' class='form-group' hidden>
        <label>Nuova password</label>
        <button class='btn btn-flat float-right' type='button' onclick='showEditPassword(false)' $cancel_button_hidden>
            <i class='fas fa-close'></i>
            Annulla
        </button>
        <input type='password' class='form-control mb-1' name='password-plain' placeholder='Password' onkeyup='checkPasswordConfirm()'>
        <input type='password' class='form-control' name='password-confirm' placeholder='Conferma password' onkeyup='checkPasswordConfirm()'>
        <p id='text-password-confirm-error' class='text-danger' hidden>Le password non corrispondono</p>
    </div>
    <input type='hidden' name='$name' value='$password_hash'>";
}