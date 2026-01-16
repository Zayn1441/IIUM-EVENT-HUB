<?php
define('API_REQUEST', true);
require_once __DIR__ . '/../../bootstrap.php';

if (!Auth::check()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$eventId = $input['event_id'] ?? null;
$reason = sanitize($input['reason'] ?? '');

if (!$eventId || empty($reason)) {
    jsonResponse(['error' => 'Event ID and Reason are required'], 400);
}

$db = Database::connect();
$stmt = $db->prepare("INSERT INTO reports (user_id, event_id, reason, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
$stmt->execute([Auth::id(), $eventId, $reason]);

jsonResponse(['message' => 'Report submitted successfully']);
