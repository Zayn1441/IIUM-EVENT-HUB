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

// Validation (Simplified)
$required = ['title', 'description', 'category', 'date', 'time', 'location', 'organizer'];
$errors = [];
foreach ($required as $field) {
    if (empty($input[$field])) {
        $errors[$field] = ucfirst($field) . ' is required';
    }
}

if (!empty($errors)) {
    jsonResponse(['errors' => $errors], 422);
}

// Add User ID
$input['user_id'] = Auth::id();

try {
    $id = Event::create($input);
    jsonResponse(['message' => 'Event created successfully', 'id' => $id], 201);
} catch (Exception $e) {
    jsonResponse(['error' => 'Failed to create event: ' . $e->getMessage()], 500);
}
