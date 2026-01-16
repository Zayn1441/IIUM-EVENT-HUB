<?php
define('API_REQUEST', true);
require_once __DIR__ . '/../../bootstrap.php';

Auth::logout();
jsonResponse(['message' => 'Logged out successfully']);
