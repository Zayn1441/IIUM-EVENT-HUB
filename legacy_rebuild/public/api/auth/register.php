<?php
define('API_REQUEST', true);
require_once __DIR__ . '/../../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

$name = sanitize($input['name'] ?? '');
$email = sanitize($input['email'] ?? '');
$password = $input['password'] ?? '';
$password_confirmation = $input['password_confirmation'] ?? '';
$student_id = sanitize($input['student_id'] ?? '');

// Validation
$errors = [];
if (empty($name))
    $errors['name'] = 'Name is required';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors['email'] = 'Valid email is required';
if (empty($password) || strlen($password) < 8)
    $errors['password'] = 'Password must be at least 8 characters';
if ($password !== $password_confirmation)
    $errors['password_confirmation'] = 'Passwords do not match';

if (!empty($errors)) {
    jsonResponse(['errors' => $errors], 422);
}

$db = Database::connect();

// Check if email exists
$stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    jsonResponse(['errors' => ['email' => 'Email already registered']], 422);
}

// Check if student_id exists (if provided)
if (!empty($student_id)) {
    $stmt = $db->prepare("SELECT id FROM users WHERE student_id = ?");
    $stmt->execute([$student_id]);
    if ($stmt->fetch()) {
        jsonResponse(['errors' => ['student_id' => 'Student ID already registered']], 422);
    }
} else {
    $student_id = null;
}

// Create User
try {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (name, email, password, student_id, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$name, $email, $hashedPassword, $student_id]);

    // Auto login
    Auth::attempt($email, $password);

    jsonResponse(['message' => 'Registration successful', 'user' => Auth::user()], 201);
} catch (Exception $e) {
    jsonResponse(['error' => 'Registration failed: ' . $e->getMessage()], 500);
}
