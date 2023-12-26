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

// Classi
// -----------------------------------------------------------------------------
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

// Argomenti
// -----------------------------------------------------------------------------
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

// Studenti
// -----------------------------------------------------------------------------
function get_studente(int $id_studente): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, CONCAT(cognome, ' ', nome) as cognome_nome, id_classe
        FROM studente
        WHERE id=" . $id_studente
    );
    return mysqli_fetch_all($query, MYSQLI_BOTH)[0];
}

function get_studenti(): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, CONCAT(cognome, ' ', nome) AS cognome_nome, id_classe
        FROM studente
        ORDER BY id_classe, cognome"
    );
    return mysqli_fetch_all($query, MYSQLI_BOTH);
}

function get_studente_expanded(int $id_studente): array {
    global $connection;

    $studente = get_studente($id_studente);
    $classe = get_classe($studente['id_classe'])[1];

    # Trova le lezioni in cui lo studente era presente
    $query = mysqli_query(
        $connection,
        "SELECT DISTINCT lezione.id, lezione.data
        FROM lezione
        LEFT JOIN presenze ON lezione.id = presenze.id_lezione
        WHERE presenze.id_studente = $id_studente AND presenze.presente = TRUE
        ORDER BY data DESC"
    );

    $lezioni = array_map(
        function ($l) { return get_lezione($l[0]); },
        mysqli_fetch_all($query, MYSQLI_BOTH)
    );

    # Calcola il numero di ore di lezione
    $sec = array_sum(
        array_map(
            function ($l) {
                return strtotime($l['ora_fine']) - strtotime($l['ora_inizio']);
            },
            $lezioni
        )
    );

    return [
        'id' => $studente['id'],
        'cognome_nome' => $studente['cognome_nome'],
        'classe' => $classe,
        'lezioni' => $lezioni,
        'ore' => $sec / 3600,
    ];
}

function get_studenti_by_classe(int $id_classe): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, CONCAT(cognome, ' ', nome) AS cognome_nome
        FROM studente
        WHERE id_classe=" . $id_classe . "
        ORDER BY cognome"
    );
    return mysqli_fetch_all($query, MYSQLI_BOTH);
}

function add_studente(
    string $nome,
    string $cognome,
    int $id_classe
): int {
    global $connection;

    mysqli_query(
        $connection,
        "INSERT INTO studente(nome, cognome, id_classe) VALUES
        ('$nome', '$cognome', $id_classe)"
    );

    return mysqli_insert_id($connection);
}

function edit_studente(
    int $id_studente,
    string $nome,
    string $cognome,
    int $id_classe
) {
    global $connection;

    mysqli_query(
        $connection,
        "UPDATE studente
        SET nome='$nome', cognome='$cognome', id_classe=$id_classe
        WHERE id=$id_studente"
    );
}

function delete_studente(int $id_studente) {
    global $connection;

    mysqli_query(
        $connection,
        "DELETE FROM studente
        WHERE id=$id_studente"
    );
}

// Docenti
// -----------------------------------------------------------------------------
function get_docente(int $id_docente): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, CONCAT(cognome, ' ', nome) AS cognome_nome, username, password, cognome, nome
        FROM docente
        WHERE id=" . $id_docente
    );
    return mysqli_fetch_all($query, MYSQLI_BOTH)[0];
}

function get_docenti(): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, CONCAT(cognome, ' ', nome) AS cognome_nome, username, password, cognome, nome
        FROM docente
        ORDER BY cognome"
    );
    return mysqli_fetch_all($query, MYSQLI_BOTH);
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

function add_docente(
    string $nome,
    string $cognome,
    string $username,
    string $password_hash
): int {
    global $connection;

    mysqli_query(
        $connection,
        "INSERT INTO docente(nome, cognome, username, password) VALUES
        ('$nome', '$cognome', '$username', '$password_hash')"
    );

    return mysqli_insert_id($connection);
}

function edit_docente(
    int $id_docente,
    string $nome,
    string $cognome,
    string $username,
    string $password_hash
) {
    global $connection;

    mysqli_query(
        $connection,
        "UPDATE docente
        SET nome='$nome', cognome='$cognome', username='$username', password='$password_hash'
        WHERE id=$id_docente"
    );
}

function delete_docente(int $id_docente) {
    global $connection;

    mysqli_query(
        $connection,
        "DELETE FROM docente
        WHERE id=$id_docente"
    );
}

// Lezioni
// -----------------------------------------------------------------------------
function get_lezione(int $id_lezione): array {
    global $connection;

    $query = mysqli_query(
        $connection,
        "SELECT id, id_docente, id_classe, date_format(ora_inizio, '%H:%i') AS ora_inizio, date_format(ora_fine, '%H:%i') AS ora_fine, UNIX_TIMESTAMP(data) AS data, aggiunta, eliminata
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
        'eliminata' => $lezione['eliminata'],
    ];
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
        "INSERT INTO lezione(id_docente, id_classe, ora_inizio, ora_fine, data, aggiunta, eliminata) VALUES
        ($id_docente, $id_classe, '$ora_inizio', '$ora_fine', '$data', '$aggiunta', FALSE)"
    );

    return mysqli_insert_id($connection);
}

function set_lezione_eliminata(int $id_lezione, bool $eliminata = TRUE) {
    global $connection;
    
    $eliminata = $eliminata ? 1 : 0;

    mysqli_query(
        $connection,
        "UPDATE lezione
        SET eliminata=$eliminata
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
    ?bool $eliminata = null,
) {
    global $connection;

    $query = "SELECT DISTINCT lezione.id, id_docente, id_classe, date_format(ora_inizio, '%H:%i') AS ora_inizio, date_format(ora_fine, '%H:%i') AS ora_fine, UNIX_TIMESTAMP(data) AS data, aggiunta
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

    if ($eliminata === false) {
        $query .= " AND eliminata=FALSE";
    } else if ($eliminata === true) {
        $query .= " AND eliminata=TRUE";
    }

    $query .= " ORDER BY aggiunta DESC";

    $query = mysqli_query(
        $connection,
        $query
    );

    return mysqli_fetch_all($query, MYSQLI_BOTH);
}

// Presenze
// -----------------------------------------------------------------------------
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

// Permessi
// -----------------------------------------------------------------------------
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

// Argomenti svolti
// -----------------------------------------------------------------------------
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