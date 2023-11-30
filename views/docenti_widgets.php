<?php
include_once '../database/interface.php';

function generate_table_docenti() {
    $docenti = get_docenti();

    echo "<table class='table table-sm'>";
    
    foreach ($docenti as $d) {
        $cognome_nome = $d['cognome_nome'];

        echo "<tr>
            <td>$cognome_nome</td>
        <tr>";
    }

    echo "</table>";
}