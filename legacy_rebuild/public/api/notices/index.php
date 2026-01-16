<?php
define('API_REQUEST', true);
require_once __DIR__ . '/../../bootstrap.php';

if (!Auth::check()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

$db = Database::connect();
$stmt = $db->prepare("SELECT * FROM notices WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([Auth::id()]);
$notices = $stmt->fetchAll();

// Mark all as read when fetched (simple approach) or have separate mark-read API
// Let's mark them read here for simplicity as "Viewing Notice Board clears badges" logic often works.
$db->prepare("UPDATE notices SET is_read = 1 WHERE user_id = ?")->execute([Auth::id()]);

jsonResponse(['data' => $notices]);
