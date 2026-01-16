<?php

return [
    'host' => '127.0.0.1',
    'dbname' => 'iium_event_hub', // Using the same DB name or a new one? Assuming we might want to use the same one, but careful about overwriting. 
    // Actually, XAMPP usually uses 'root' and empty password by default.
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    // We can allow override via ENV if we were fancy, but hardcoding for XAMPP simplicity as requested.
];
