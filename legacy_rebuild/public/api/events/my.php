<?php
define('API_REQUEST', true);
require_once __DIR__ . '/../../bootstrap.php';

if (!Auth::check()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

$db = Database::connect();
// Get events registered by user OR organized by user? "My Events" usually means registered.
// Let's return both or just registered. The sidebar usually separates "Managed Events" vs "Attending".
// Let's limit to "Attending" (registered/saved) for this endpoint.

$stmt = $db->prepare("
    SELECT events.*, registrations.type as registration_type, users.name as organizer_name 
    FROM events 
    JOIN registrations ON events.id = registrations.event_id 
    LEFT JOIN users ON events.user_id = users.id
    WHERE registrations.user_id = ? 
    ORDER BY events.date ASC
");
$stmt->execute([Auth::id()]);
$events = $stmt->fetchAll();

jsonResponse(['data' => $events]);
