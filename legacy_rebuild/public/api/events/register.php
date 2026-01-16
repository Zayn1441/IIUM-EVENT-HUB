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
$type = $input['type'] ?? 'registered'; // 'registered' or 'saved'

if (!$eventId) {
    jsonResponse(['error' => 'Event ID required'], 400);
}

$db = Database::connect();

// Check if already registered/saved
$stmt = $db->prepare("SELECT id, type FROM registrations WHERE user_id = ? AND event_id = ?");
$stmt->execute([Auth::id(), $eventId]);
$existing = $stmt->fetch();

if ($existing) {
    if ($existing['type'] === $type) {
        // Toggle off (Unregister/Unsave)
        $db->prepare("DELETE FROM registrations WHERE id = ?")->execute([$existing['id']]);
        jsonResponse(['message' => ucfirst($type) . ' removed', 'status' => 'removed']);
    } else {
        // Switch type (e.g., save -> register) OR error?
        // Usually you can be both? Or one? Schema unique key?
        // Migration didn't show unique key on (user_id, event_id), but usually logic implies one record per pair or separate tables.
        // Let's assume one record per user-event pair for simplicity unless verified otherwise.
        // Update type
        $db->prepare("UPDATE registrations SET type = ?, updated_at = NOW() WHERE id = ?")->execute([$type, $existing['id']]);
        jsonResponse(['message' => 'Updated to ' . $type, 'status' => 'updated']);
    }
} else {
    // Create new
    $stmt = $db->prepare("INSERT INTO registrations (user_id, event_id, type, status, created_at, updated_at) VALUES (?, ?, ?, 'upcoming', NOW(), NOW())");
    $stmt->execute([Auth::id(), $eventId, $type]);
    jsonResponse(['message' => 'Successfully ' . $type, 'status' => 'added']);
}
