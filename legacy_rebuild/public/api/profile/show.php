<?php
define('API_REQUEST', true);
require_once __DIR__ . '/../../bootstrap.php';

if (!Auth::check()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

// Return current authenticated user
$user = Auth::user();
jsonResponse(['data' => $user]);
