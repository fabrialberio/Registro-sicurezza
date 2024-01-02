<?php
include_once '../vendor/autoload.php';
include_once '../database/interface.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


$private_key = file_get_contents(__DIR__ . '/../jwtRS256.key');
$public_key = file_get_contents(__DIR__ . '/../jwtRS256.key.pub');

if (!$private_key || is_null($private_key)) {
    die('Chiave privata non trovata in `../jwtRS256.key`');
}


function get_token(string $username): string {
    global $private_key;
    // TODO: usare parametri standard

    $payload = [
        'exp' => time() + (3600 * 6), // 6 ore
        'username' => $username
    ];

    $jwt = JWT::encode($payload, $private_key, 'RS256');

    return $jwt;
}

function decode_token(string $jwt): array {
    global $public_key;

    return (array) JWT::decode($jwt, new Key($public_key, 'RS256'));
}

function token_get_id_docente(string $jwt): int {
    $token = decode_token($jwt);
    $username = $token['username'];

    return get_id_docente_by_username($username);
}

function token_is_valid(?string $jwt): bool {
    if (is_null($jwt)) {
        return false;
    }

    try {
        $token = decode_token($jwt);
    } catch (Exception $e) {
        return false;
    }

    if ($token['exp'] < time()) {
        return false;
    }

    return true;
}

function token_is_amministratore(string $jwt) {
    $token = decode_token($jwt);
    $username = $token['username'];

    return is_amministratore_by_username($username);
}