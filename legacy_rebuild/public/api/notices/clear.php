<?php
define('API_REQUEST', true);
require_once __DIR__ . '/../../bootstrap.php';

if (!Auth::check()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$db = Database::connect();
$stmt = $db->prepare("DELETE FROM notices WHERE user_id = ?");
$stmt->execute([Auth::id()]);

jsonResponse(['message' => 'Notices cleared']);
