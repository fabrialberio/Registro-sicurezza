<?php
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
    echo "
    <script>
        function showEditPassword(value) {
            var placeholder = document.getElementById('group-password-placeholder');
            var edit = document.getElementById('group-password-edit');
            
            if (value) {
                placeholder.hidden = true;
                edit.hidden = false;
            } else {
                placeholder.hidden = false;
                edit.hidden = true;
            }
        }

        function checkPasswordConfirm() {
            var password = document.getElementsByName('password-plain')[0];
            var passwordConfirm = document.getElementsByName('password-confirm')[0];
            var textError = document.getElementById('text-password-confirm-error');
            
            if (password.value != passwordConfirm.value && passwordConfirm.value != '') {
                textError.hidden = false;
            } else {
                textError.hidden = true;
            }
        }
    </script>";

    if ($new) {
        echo "
        <script>
            window.onload = function() {
                showEditPassword(true);
            }
        </script>";
    }

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
        <button class='btn btn-flat float-right' type='button' onclick='showEditPassword(false)'>
            <i class='fas fa-close'></i>
            Annulla
        </button>
        <input type='password' class='form-control mb-1' name='password-plain' placeholder='Password' onkeyup='checkPasswordConfirm()'>
        <input type='password' class='form-control' name='password-confirm' placeholder='Conferma password' onkeyup='checkPasswordConfirm()'>
        <p id='text-password-confirm-error' class='text-danger' hidden>Le password non corrispondono</p>
    </div>
    <input type='hidden' name='$name' value='$password_hash'>";
}