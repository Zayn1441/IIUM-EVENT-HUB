<?php
define('API_REQUEST', true);
require_once __DIR__ . '/../../bootstrap.php';

// Check auth? Not strictly necessary for viewing events (usually public), but let's see existing logic. 
// Existing: Route::middleware('auth') -> /dashboard (which lists events). So yes, require auth.
if (!Auth::check()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

// Get filter params
// For now just return upcoming
$events = Event::upcoming();

// Add registration counts or status logic if needed later
jsonResponse(['data' => $events]);
