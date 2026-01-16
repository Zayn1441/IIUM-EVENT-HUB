<?php
session_start();

// Simple Autoloader
spl_autoload_register(function ($class) {
    // Check if class exists in src
    if (file_exists(__DIR__ . '/src/' . $class . '.php')) {
        require_once __DIR__ . '/src/' . $class . '.php';
    }
});

// Helper functions
function jsonResponse($data, $status = 200)
{
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

// Global Constant to prevent direct access to included files if needed
define('APP_INIT', true);
