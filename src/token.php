<?php
include_once '../vendor/autoload.php';
include_once 'navigation.php';

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

function check_token(string|null $jwt): bool {
    if (is_null($jwt)) {
        return false;
    }

    $expires = decode_token($jwt)['exp'];
    
    if ($expires < time()) {
        return false;
    }

    return true;
}

function decode_token(string $jwt): array {
    global $public_key;

    return (array) JWT::decode($jwt, new Key($public_key, 'RS256'));
}

function decode_token_or_quit(string|null $jwt): array|bool {
    if (is_null($jwt)) {
        // Token nullo
        go_to_login('Sessione scaduta');
        return false;
    }

    try {
        $token = decode_token($jwt);
    } catch (Exception $e) {
        // Errore di decodifica (token invalido)
        go_to_login('Sessione scaduta');
    }

    if ($token['exp'] < time()) {
        // Token scaduto
        go_to_login('Sessione scaduta');
    }

    return $token;
}

function token_get_username(string $jwt) {
    return decode_token($jwt)['username'];
}