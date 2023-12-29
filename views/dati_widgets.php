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
    for ($i = 0; $i < count($row_data); $i++) {
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