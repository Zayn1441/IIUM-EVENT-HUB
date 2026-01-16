<?php
define('API_REQUEST', true);
require_once __DIR__ . '/../../bootstrap.php';

if (!Auth::check()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? null;

if (!$id) {
    jsonResponse(['error' => 'ID required'], 400);
}

$event = Event::find($id);
if (!$event) {
    jsonResponse(['error' => 'Event not found'], 404);
}

// Check authorization (only organizer or admin)
$user = Auth::user();
if ($event['user_id'] != $user['id'] && !$user['is_admin']) {
    jsonResponse(['error' => 'Forbidden'], 403);
}

$db = Database::connect();
$stmt = $db->prepare("DELETE FROM events WHERE id = ?");
$stmt->execute([$id]);

jsonResponse(['message' => 'Event deleted successfully']);
