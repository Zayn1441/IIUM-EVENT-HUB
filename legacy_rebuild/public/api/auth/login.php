<?php
define('API_REQUEST', true);
require_once __DIR__ . '/../../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$email = sanitize($input['email'] ?? '');
$password = $input['password'] ?? '';

if (Auth::attempt($email, $password)) {
    jsonResponse(['message' => 'Login successful', 'user' => Auth::user()]);
} else {
    jsonResponse(['errors' => ['email' => 'The provided credentials do not match our records.']], 422);
}
