<?php
include_once '../database/interface.php';



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
    <div class='card mt-4' style='max-width: 720px; margin: auto'>
        <div class='card-header'>
            <h4 class='card-title'>$title</h4>
            <div class='card-tools btn-group'>
                <form action='../src/print_lesson.php' method='post'>
                    <input type='hidden' name='id_lezione' value='$lesson_id'>
                    <button class='btn btn-sm btn-default mr-1' type='submit'>
                        <i class='fas fa-print'></i>
                        Stampa
                    </button>
                </form>";

    if ($amministratore) {
        echo "
                <form action='../src/remove_lesson.php' method='post'>
                    <input type='hidden' name='id_lezione' value='$lesson_id'>
                    <button class='btn btn-sm btn-danger' type='submit'>
                        <i class='fas fa-trash'></i>
                        Elimina
                    </button>
                </form>";
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
        echo "<tr><td>$titolo</tr></td>";
    }

    echo "</table>";
}

function generate_filters_bar(bool $amministratore) {
    echo "
    <div class='mb-2'>
        <form action='view_lessons.php' method='get'>
            <div class='input-group'>";
    
    if ($amministratore) {
        $docenti_options = array_map(
            function($d) {
                return [
                    'value' => $d['id'],
                    'text' => $d['cognome_nome'],
                ];
            },
            get_docenti()
        );
        generate_filter_select('id_docente', 'Tutti i docenti', $docenti_options, $_GET['id_docente'] ?? null);
    }

    $classi_options = array_map(
        function($c) {
            return [
                'value' => $c['id'],
                'text' => $c['classe'],
            ];
        },
        get_classi()
    );
    generate_filter_select('id_classe', 'Tutte le classi', $classi_options, $_GET['id_classe'] ?? null);

    echo "
                <div class='input-group-append'>
                    <button class='btn btn-primary' type='submit'>
                        <i class='fas fa-filter'></i>
                        Filtra
                    </button>
                </div>
            </div>
        </form>
    </div>";
}

function generate_filter_select(string $name, string $default_string, array $options, string $selected = null) {
    echo "<select class='form-control' name='$name'>
        <option value=''>$default_string</option>";

    foreach ($options as $option) {
        $value = $option['value'];
        $text = $option['text'];
        $selected_attr = $selected == $value ? 'selected' : '';
        echo "<option value='$value' $selected_attr>$text</option>";
    }

    echo "</select>";
}