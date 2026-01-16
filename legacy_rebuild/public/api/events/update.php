<?php
define('API_REQUEST', true);
require_once __DIR__ . '/../../bootstrap.php';

if (!Auth::check()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Supporting generic POST for forms, but maybe PUT? using POST with _method or just POST is easier for vanilla form data
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

// Check authorization
$user = Auth::user();
if ($event['user_id'] != $user['id'] && !$user['is_admin']) {
    jsonResponse(['error' => 'Forbidden'], 403);
}

// Update fields (simplified)
$fields = ['title', 'description', 'category', 'date', 'time', 'location', 'capacity', 'organizer', 'is_starpoints', 'participation_link', 'image_path'];
$data = [];
foreach ($fields as $field) {
    $data[$field] = $input[$field] ?? $event[$field];
}

$db = Database::connect();
$stmt = $db->prepare("UPDATE events SET title=?, description=?, category=?, date=?, time=?, location=?, capacity=?, organizer=?, is_starpoints=?, participation_link=?, image_path=?, updated_at=NOW() WHERE id=?");

$stmt->execute([
    $data['title'],
    $data['description'],
    $data['category'],
    $data['date'],
    $data['time'],
    $data['location'],
    $data['capacity'],
    $data['organizer'],
    $data['is_starpoints'] ? 1 : 0,
    $data['participation_link'],
    $data['image_path'],
    $id
]);

jsonResponse(['message' => 'Event updated successfully']);
