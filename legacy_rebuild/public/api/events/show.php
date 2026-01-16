<?php
define('API_REQUEST', true);
require_once __DIR__ . '/../../bootstrap.php';

if (!Auth::check()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

$id = $_GET['id'] ?? null;
if (!$id) {
    jsonResponse(['error' => 'ID required'], 400);
}

$event = Event::find($id);
if (!$event) {
    jsonResponse(['error' => 'Event not found'], 404);
}

jsonResponse(['data' => $event]);
