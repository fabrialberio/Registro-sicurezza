<?php
function generate_table(array $headers, array $row_data, string $on_row_click = null) {
    $clickable_row = $on_row_click != null;
    $hover_class = $clickable_row ? 'table-hover' : '';
    
    echo "<table class='table table-sm table-bordered $hover_class'>";
    
    echo "<thead><tr>";
    foreach ($headers as $header) {
        echo "<th>$header</th>";
    }
    echo "</tr></thead>";
    
    echo "<tbody>";
    foreach ($row_data as $row) {
        $on_click = $clickable_row ? "onclick=\"window.location.href='$on_row_click'\"" : '';

        echo "<tr $on_click>";
        foreach ($row as $cell) {
            echo "<td>$cell</td>";
        }
        echo "</tr>";
    }
    
    echo "</tbody></table>";
}