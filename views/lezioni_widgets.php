<?php
include_once '../database/interface.php';
include_once 'filter_widgets.php';


function generate_card_lezione(int $lesson_id, bool $amministratore = FALSE) {
    global $connection;

    $lezione = get_lezione_expanded($lesson_id);
    
    $title = $lezione['titolo'];
    $docente = $lezione['docente'];
    $data = $lezione['data'];
    $ora = $lezione['ora'];
    $classe = $lezione['classe'];
    $argomenti_svolti = get_argomenti_svolti($lezione['id']);

    $presenze = get_presenze_expanded($lesson_id);
    $num_studenti = count($presenze);
    $num_presenti = count(array_filter(array_map(
        function($p) {
            return $p['presente'];
        },
        $presenze
    )));

    echo "
    <div class='card mb-2'>
        <div class='card-header'>
            <h class='card-title'>$title</h>
            <div class='card-tools btn-group'>
                <form action='../src/print_lezione.php' method='post'>
                    <input type='hidden' name='id_lezione' value='$lesson_id'>
                    <button class='btn btn-default btn-sm' type='submit'>
                        <i class='fas fa-print'></i>
                        Stampa
                    </button>
                </form>";

    if ($amministratore) {
        echo "
                    <a href=\"../views/dati_lezione.php?id=$lesson_id\" class='btn btn-default btn-sm ml-2'>
                        <i class='fas fa-edit'></i>
                        Modifica
                    </a>";
        if (!$lezione['eliminata']) {
            echo "
                    <form action='../src/edit_lezione.php' method='post'>
                        <input type='hidden' name='id_lezione' value='$lesson_id'>
                        <button class='btn btn-danger btn-sm ml-2' name='delete' type='submit'>
                            <i class='fas fa-trash'></i>
                            Elimina
                        </button>
                    </form>";
        } else {
            echo "
                    <form action='../src/edit_lezione.php' method='post'>
                        <input type='hidden' name='id_lezione' value='$lesson_id'>
                        <input type='hidden' name='restore' value='1'>
                        <button class='btn btn-default btn-sm ml-2' name='restore' type='submit'>
                            <i class='fas fa-arrow-rotate-left'></i>
                            Ripristina
                        </button>
                    </form>";
        }
    }

    echo "
            </div>
        </div>
        <div class='card-body'>
            <dl>"
                . ($amministratore ? "<dt>Docente</dt><dd>$docente</dd>" : "") . "
                <dt>Data</dt>
                <dd>$data</dd>
                <dt>Ora</dt>
                <dd>$ora</dd>
                <dt>Classe</dt>
                <dd>$classe</dd>
                <dt>Argomenti</dt>
                <dd style='max-widht: 100%'>";

    generate_table_argomenti_svolti($lezione['id']);

    echo "
                </dd>
                <dt>Presenze</dt>
                <dd>$num_presenti su $num_studenti presenti</dd>
            </dl>
        </div>
    </div>";
}

function generate_table_argomenti_svolti(int $id_lezione) {
    $argomenti_svolti = get_argomenti_svolti($id_lezione);

    if (count($argomenti_svolti) == 0) {
        echo "Nessun argomento";
        return;
    }

    echo "<table class='mt-1 table table-sm table-bordered'>";

    foreach($argomenti_svolti as $a) {
        $titolo = $a['argomento'];
        echo "<tr><td>$titolo</td></tr>";
    }

    echo "</table>";
}