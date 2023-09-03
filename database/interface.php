<?php
include_once '../src/navigation.php';

if (file_exists(__DIR__ . '/credentials.json')) {
    $credentials = json_decode(file_get_contents(__DIR__ . '/credentials.json'), true);
} else {
    die('Credenziali del database non trovate in `database/credentials.json`');
}

$connection = mysqli_connect(
    $credentials['host'],
    $credentials['username'],
    $credentials['password'],
    $credentials['database'],
) or go_to_login('Connessione al database non riuscita');

// Implementazione manuale della funzione che non è supportata nativamente sul server
if (!function_exists('mysqli_fetch_all')) {
    function mysqli_fetch_all($result, $resulttype = MYSQLI_NUM) {
        $rows = [];
        while ($row = mysqli_fetch_array($result, $resulttype)) {
            $rows[] = $row;
        }
        return $rows;
    }
}


function get_classi(): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, CONCAT(anno, sezione) AS classe 
        FROM classe
        ORDER BY anno, sezione"
    );
    return mysqli_fetch_all($query, MYSQLI_BOTH);
}

function get_classe(int $id_classe): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, CONCAT(anno, sezione) 
        FROM classe
        WHERE id =" . strval($id_classe)
    );
    return mysqli_fetch_all($query, MYSQLI_BOTH)[0];
}

function get_argomenti(): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, titolo
        FROM argomento
        ORDER BY id"
    );
    return mysqli_fetch_all($query, MYSQLI_BOTH);
}

function get_argomento(int $id_argomento): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, titolo
        FROM argomento
        WHERE id=" . strval($id_argomento)
    );
    return mysqli_fetch_all($query, MYSQLI_BOTH)[0];
}

function get_studenti_by_classe(int $id_classe): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, CONCAT(cognome, ' ', nome) AS nome_cognome
        FROM studente
        WHERE id_classe=" . $id_classe . "
        ORDER BY cognome"
    );
    return mysqli_fetch_all($query, MYSQLI_BOTH);
}

function get_studente(int $id_studente): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, CONCAT(cognome, ' ', nome)
        FROM studente
        WHERE id=" . $id_studente
    );
    return mysqli_fetch_all($query, MYSQLI_BOTH)[0];
}

function get_docente(int $id_docente): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, CONCAT(cognome, ' ', nome) AS cognome_nome, username, password
        FROM docente
        WHERE id=" . $id_docente
    );
    return mysqli_fetch_all($query, MYSQLI_BOTH)[0];
}

function get_id_docente_by_username(string $username): int | null {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id
        FROM docente
        WHERE username='$username'"
    );
    return mysqli_fetch_array($query)['id'];
}

function get_lezione(int $id_lezione): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, id_docente, id_classe, date_format(ora_inizio, '%h:%i') AS ora_inizio, date_format(ora_fine, '%h:%i') AS ora_fine, UNIX_TIMESTAMP(data) AS data
        FROM lezione
        WHERE id=$id_lezione"
    );

    return mysqli_fetch_array($query, MYSQLI_BOTH);
}

function get_lezione_expanded(int $id_lezione): array {
    global $connection;

    $lezione = get_lezione($id_lezione);
    
    $classe = get_classe($lezione['id_classe'])[1];
    $formato_data_titolo = datefmt_create(
        'it_IT',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'GMT+2',
        null,
    );
    $title = "Lezione in $classe di " . datefmt_format($formato_data_titolo, $lezione['data']);

    $formato_data = datefmt_create(
        'it_IT',
        IntlDateFormatter::SHORT,
        IntlDateFormatter::NONE,
        'GMT+2',
        null,
    );
    $data = datefmt_format($formato_data, $lezione['data']);

    return [
        'id' => $lezione['id'],
        'titolo' => $title,
        'data' => $data,
        'classe' => $classe,
        'ora' => $lezione['ora_inizio'] . ' - ' . $lezione['ora_fine'],
        'docente' => get_docente($lezione['id_docente'])[1],
    ];
}

function get_presenze(int $id_lezione): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, id_studente, presente
        FROM presenze
        WHERE id_lezione=$id_lezione"
    );

    return mysqli_fetch_all($query, MYSQLI_BOTH);
}

function get_presenze_expanded(int $id_lezione): array {
    global $connection;

    $presenze = get_presenze($id_lezione);
    $presenze_expanded = [];

    foreach($presenze as $p) {
        $presenze_expanded[] = [
            'id' => $p[0],
            'studente' => get_studente($p[1])[1],
            'presente' => $p[2],
        ];
    }

    return $presenze_expanded;
}

function get_permessi(int $id_docente): array | null {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, id_docente, amministratore
        FROM permessi
        WHERE id_docente=$id_docente"
    );

    return mysqli_fetch_array($query, MYSQLI_BOTH);
}

function is_amministratore(int $id_docente): bool {
    $permessi = get_permessi($id_docente);

    if (is_null($permessi)) {
        return false;
    }

    return $permessi['amministratore'];
}

function is_amministratore_by_username(string $username): bool {
    $id_docente = get_id_docente_by_username($username);

    if (is_null($id_docente)) {
        return false;
    }

    return is_amministratore($id_docente);
}

function get_argomenti_svolti(int $id_lezione): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, id_lezione, argomento
        FROM argomento_svolto
        WHERE id_lezione=$id_lezione"
    );

    return mysqli_fetch_all($query, MYSQLI_BOTH);
}

function add_lezione(
    int $id_docente,
    int $id_classe,
    string $ora_inizio,
    string $ora_fine,
    string $data,
): int {
    global $connection;

    $aggiunta = date('Y-m-d H:i:s');

    mysqli_query(
        $connection,
        "INSERT INTO lezione(id_docente, id_classe, ora_inizio, ora_fine, data, aggiunta) VALUES
        ($id_docente, $id_classe, '$ora_inizio', '$ora_fine', '$data', '$aggiunta')"
    );

    return mysqli_insert_id($connection);
}

function add_argomento_svolto(
    int $id_lezione,
    string $argomento
): int {
    global $connection;

    mysqli_query(
        $connection,
        "INSERT INTO argomento_svolto(id_lezione, argomento) VALUES
        ($id_lezione, '$argomento')"
    );

    return mysqli_insert_id($connection);
}

function add_presenza(
    int $id_lezione,
    int $id_studente,
    bool $presente
): int {
    global $connection;

    $presente = $presente ? 1 : 0;

    print_r("INSERT INTO presenze(id_lezione, id_studente, presente) VALUES
        ($id_lezione, $id_studente, $presente)");

    mysqli_query(
        $connection,
        "INSERT INTO presenze(id_lezione, id_studente, presente) VALUES
        ($id_lezione, $id_studente, $presente)"
    );

    return mysqli_insert_id($connection);
}

function remove_lezione(int $id_lezione) {
    global $connection;

    mysqli_query(
        $connection,
        "DELETE FROM argomento_svolto
        WHERE id_lezione=$id_lezione"
    );

    mysqli_query(
        $connection,
        "DELETE FROM presenze
        WHERE id_lezione=$id_lezione"
    );

    mysqli_query(
        $connection,
        "DELETE FROM lezione
        WHERE id=$id_lezione"
    );
}

function get_lezioni_filter(
    ?int $id_docente = null,
    ?int $id_classe = null,
    ?string $data_inizio = null,
    ?string $data_fine = null,
    ?string $argomento = null,
    ?string $aggiunta_da = null,
    ?string $aggiunta_a = null,
) {
    global $connection;

    $query = "SELECT DISTINCT lezione.id, id_docente, id_classe, date_format(ora_inizio, '%h:%i') AS ora_inizio, date_format(ora_fine, '%h:%i') AS ora_fine, UNIX_TIMESTAMP(data) AS data, aggiunta
        FROM lezione
        LEFT JOIN argomento_svolto ON lezione.id = argomento_svolto.id_lezione
        WHERE 1=1";

    if (!is_null($id_docente)) {
        $query .= " AND id_docente=$id_docente";
    }

    if (!is_null($id_classe)) {
        $query .= " AND id_classe=$id_classe";
    }

    if (!is_null($data_inizio)) {
        $query .= " AND data >= '$data_inizio'";
    }

    if (!is_null($data_fine)) {
        $query .= " AND data <= '$data_fine'";
    }

    if (!is_null($argomento)) {
        $query .= " AND argomento_svolto.argomento LIKE '%$argomento%'";
    }

    if (!is_null($aggiunta_da)) {
        $query .= " AND aggiunta >= '$aggiunta_da'";
    }

    if (!is_null($aggiunta_a)) {
        $query .= " AND aggiunta <= '$aggiunta_a'";
    }

    $query .= " ORDER BY aggiunta DESC";

    $query = mysqli_query(
        $connection,
        $query
    );

    return mysqli_fetch_all($query, MYSQLI_BOTH);
}