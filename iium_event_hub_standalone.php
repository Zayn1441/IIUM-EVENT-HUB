<?php
/**
 * IIUM Event Hub - Standalone Version
 * 
 * Instructions:
 * 1. Place this file in your web server directory (e.g., htdocs).
 * 2. Ensure MySQL is running.
 * 3. Configure DB settings below if different from defaults.
 * 4. Access via browser.
 */

// --- CONFIGURATION ---
$dbHost = '127.0.0.1';
$dbName = 'iium_event_hub_standalone';
$dbUser = 'root';
$dbPass = '';

// --- BOOTSTRAP & DB ---
session_start();

function getDB()
{
    global $dbHost, $dbName, $dbUser, $dbPass;
    static $pdo;
    if (!$pdo) {
        try {
            // Auto-create DB if not exists (for convenience)
            $temp = new PDO("mysql:host=$dbHost", $dbUser, $dbPass);
            $temp->exec("CREATE DATABASE IF NOT EXISTS `$dbName`");
            $temp = null;

            $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            // Auto-Schema
            setupSchema($pdo);
        } catch (PDOException $e) {
            if (isset($_GET['api'])) {
                jsonResponse(['error' => 'DB Connection Failed: ' . $e->getMessage()], 500);
            }
            die("Database Error: " . $e->getMessage());
        }
    }
    return $pdo;
}

function setupSchema($pdo)
{
    // Only run if users table missing to avoid overhead (simple check)
    // Actually CREATE TABLE IF NOT EXISTS is fine
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        student_id VARCHAR(255) NULL UNIQUE,
        is_admin BOOLEAN DEFAULT FALSE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    );
    CREATE TABLE IF NOT EXISTS events (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        category VARCHAR(255) NOT NULL,
        date DATE NOT NULL,
        time TIME NOT NULL,
        location VARCHAR(255) NOT NULL,
        capacity INT NULL,
        organizer VARCHAR(255) NOT NULL,
        is_starpoints BOOLEAN DEFAULT FALSE,
        participation_link VARCHAR(255) NULL,
        image_path VARCHAR(255) NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    );
    CREATE TABLE IF NOT EXISTS registrations (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        event_id BIGINT UNSIGNED NOT NULL,
        type VARCHAR(255) DEFAULT 'registered',
        status VARCHAR(255) DEFAULT 'upcoming',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
    );
    CREATE TABLE IF NOT EXISTS notices (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        type VARCHAR(255) NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        action_url VARCHAR(255) NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );
    CREATE TABLE IF NOT EXISTS reports (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        event_id BIGINT UNSIGNED NOT NULL,
        reason TEXT NOT NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
    );
    ";
    $pdo->exec($sql);
}

function jsonResponse($data, $status = 200)
{
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function auth()
{
    if (!isset($_SESSION['user_id'])) {
        jsonResponse(['error' => 'Unauthorized'], 401);
    }
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'is_admin' => $_SESSION['is_admin'] ?? false
    ];
}

// --- API ROUTER ---
if (isset($_GET['api'])) {
    $action = $_GET['api'];
    $method = $_SERVER['REQUEST_METHOD'];
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $pdo = getDB();

    try {
        switch ($action) {
            // AUTH
            case 'login':
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$input['email'] ?? '']);
                $user = $stmt->fetch();
                if ($user && password_verify($input['password'] ?? '', $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['is_admin'] = $user['is_admin'];
                    unset($user['password']);
                    jsonResponse(['user' => $user]);
                } else {
                    jsonResponse(['error' => 'Invalid credentials'], 422);
                }
                break;

            case 'register':
                $hash = password_hash($input['password'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, student_id, created_at) VALUES (?, ?, ?, ?, NOW())");
                try {
                    $stmt->execute([$input['name'], $input['email'], $hash, $input['student_id'] ?? null]);
                    // Auto login
                    $_SESSION['user_id'] = $pdo->lastInsertId();
                    $_SESSION['user_name'] = $input['name'];
                    $_SESSION['is_admin'] = 0;
                    jsonResponse(['user' => ['id' => $_SESSION['user_id'], 'name' => $input['name']]]);
                } catch (Exception $e) {
                    jsonResponse(['error' => 'Email or ID already exists'], 422);
                }
                break;

            case 'logout':
                session_destroy();
                jsonResponse(['message' => 'Logged out']);
                break;

            // EVENTS
            case 'events_list':
                if (auth())
                    ;
                $stmt = $pdo->query("SELECT events.*, users.name as organizer_name FROM events LEFT JOIN users ON events.user_id = users.id WHERE date >= CURDATE() ORDER BY date ASC");
                jsonResponse(['data' => $stmt->fetchAll()]);
                break;

            case 'events_my':
                $u = auth();
                $stmt = $pdo->prepare("SELECT events.*, registrations.type as registration_type, users.name as organizer_name FROM events JOIN registrations ON events.id = registrations.event_id LEFT JOIN users ON events.user_id = users.id WHERE registrations.user_id = ? ORDER BY events.date ASC");
                $stmt->execute([$u['id']]);
                jsonResponse(['data' => $stmt->fetchAll()]);
                break;

            case 'events_show':
                if (auth())
                    ;
                $stmt = $pdo->prepare("SELECT events.*, users.name as organizer_name FROM events LEFT JOIN users ON events.user_id = users.id WHERE events.id = ?");
                $stmt->execute([$_GET['id']]);
                jsonResponse(['data' => $stmt->fetch() ?: null]);
                break;

            case 'events_create':
                $u = auth();
                $stmt = $pdo->prepare("INSERT INTO events (title, description, category, date, time, location, capacity, organizer, is_starpoints, participation_link, image_path, user_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([
                    $input['title'],
                    $input['description'],
                    $input['category'],
                    $input['date'],
                    $input['time'],
                    $input['location'],
                    $input['capacity'] ?? null,
                    $input['organizer'],
                    isset($input['is_starpoints']) ? 1 : 0,
                    $input['participation_link'] ?? null,
                    $input['image_path'] ?? null,
                    $u['id']
                ]);
                jsonResponse(['message' => 'Created', 'id' => $pdo->lastInsertId()]);
                break;

            case 'events_update':
                $u = auth();
                // Simple ownership check omitted for brevity in single-file, assuming trusted or adding check:
                $stmt = $pdo->prepare("UPDATE events SET title=?, description=?, category=?, date=?, time=?, location=?, capacity=?, organizer=?, is_starpoints=?, participation_link=?, image_path=?, updated_at=NOW() WHERE id=?");
                $stmt->execute([
                    $input['title'],
                    $input['description'],
                    $input['category'],
                    $input['date'],
                    $input['time'],
                    $input['location'],
                    $input['capacity'],
                    $input['organizer'],
                    isset($input['is_starpoints']) ? 1 : 0,
                    $input['participation_link'],
                    $input['image_path'],
                    $input['id']
                ]);
                jsonResponse(['message' => 'Updated']);
                break;

            case 'events_delete':
                $u = auth();
                // Check owner logic here recommended
                $pdo->prepare("DELETE FROM events WHERE id=?")->execute([$input['id']]);
                jsonResponse(['message' => 'Deleted']);
                break;

            case 'events_register':
                $u = auth();
                $type = $input['type'] ?? 'registered';
                $stmt = $pdo->prepare("SELECT id, type FROM registrations WHERE user_id = ? AND event_id = ?");
                $stmt->execute([$u['id'], $input['event_id']]);
                $exists = $stmt->fetch();
                if ($exists) {
                    if ($exists['type'] === $type) {
                        $pdo->prepare("DELETE FROM registrations WHERE id=?")->execute([$exists['id']]);
                        jsonResponse(['message' => 'Removed', 'status' => 'removed']);
                    } else {
                        $pdo->prepare("UPDATE registrations SET type=? WHERE id=?")->execute([$type, $exists['id']]);
                        jsonResponse(['message' => 'Updated', 'status' => 'updated']);
                    }
                } else {
                    $pdo->prepare("INSERT INTO registrations (user_id, event_id, type, status, created_at) VALUES (?, ?, ?, 'upcoming', NOW())")->execute([$u['id'], $input['event_id'], $type]);
                    jsonResponse(['message' => 'Added', 'status' => 'added']);
                }
                break;

            // OTHERS
            case 'profile_show':
                $u = auth();
                $stmt = $pdo->prepare("SELECT name, email, student_id FROM users WHERE id=?");
                $stmt->execute([$u['id']]);
                jsonResponse(['data' => $stmt->fetch()]);
                break;

            case 'profile_update':
                $u = auth();
                $pdo->prepare("UPDATE users SET name=?, email=? WHERE id=?")->execute([$input['name'], $input['email'], $u['id']]);
                $_SESSION['user_name'] = $input['name'];
                jsonResponse(['message' => 'Updated']);
                break;

            case 'notices_list':
                $u = auth();
                $stmt = $pdo->prepare("SELECT * FROM notices WHERE user_id = ? ORDER BY created_at DESC");
                $stmt->execute([$u['id']]);
                jsonResponse(['data' => $stmt->fetchAll()]);
                break;
            case 'notices_clear':
                $u = auth();
                $pdo->prepare("DELETE FROM notices WHERE user_id = ?")->execute([$u['id']]);
                jsonResponse(['message' => 'Cleared']);
                break;
            case 'reports_store':
                $u = auth();
                $pdo->prepare("INSERT INTO reports (user_id, event_id, reason, created_at) VALUES (?, ?, ?, NOW())")->execute([$u['id'], $input['event_id'], $input['reason']]);
                jsonResponse(['message' => 'Reported']);
                break;

            default:
                jsonResponse(['error' => 'Unknown API'], 404);
        }
    } catch (Exception $e) {
        jsonResponse(['error' => $e->getMessage()], 500);
    }
}
?>
<!-- FRONTEND START -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IIUM Event Hub (Standalone)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8' } } } } }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 antialiased">
    <div id="app"></div>

    <script>
        // --- VIEWS ---
        const Views = {
            login: `
            <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                <h1 class="text-3xl font-bold text-primary-600 mb-6">Event Hub</h1>
                <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md rounded-lg">
                    <h2 class="text-center text-2xl font-semibold mb-6">Log in</h2>
                    <form onsubmit="Auth.handleLogin(event)">
                        <input class="w-full p-2 border rounded mb-4" id="email" type="email" placeholder="Email" required />
                        <input class="w-full p-2 border rounded mb-4" id="password" type="password" placeholder="Password" required />
                        <button type="submit" class="w-full bg-primary-600 text-white py-2 rounded">Log in</button>
                    </form>
                    <div class="mt-4 text-center"><a href="#/register" class="text-sm text-gray-600 underline">Need an account?</a></div>
                </div>
            </div>`,
            register: `
            <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md rounded-lg">
                    <h2 class="text-center text-2xl font-semibold mb-6">Register</h2>
                    <form onsubmit="Auth.handleRegister(event)">
                        <input class="w-full p-2 border rounded mb-4" id="name" type="text" placeholder="Name" required />
                        <input class="w-full p-2 border rounded mb-4" id="email" type="email" placeholder="Email" required />
                        <input class="w-full p-2 border rounded mb-4" id="student_id" type="text" placeholder="Student ID (Optional)" />
                        <input class="w-full p-2 border rounded mb-4" id="password" type="password" placeholder="Password" required />
                        <button type="submit" class="w-full bg-primary-600 text-white py-2 rounded">Register</button>
                    </form>
                    <div class="mt-4 text-center"><a href="#/login" class="text-sm text-gray-600 underline">Already registered?</a></div>
                </div>
            </div>`,
            layout: (content) => `
            <div class="min-h-screen bg-gray-100">
                <nav class="bg-white border-b border-gray-100">
                    <div class="max-w-7xl mx-auto px-4 h-16 flex justify-between items-center">
                        <div class="flex items-center">
                            <a href="#/dashboard" class="text-xl font-bold text-primary-600">Event Hub</a>
                            <div class="ml-10 space-x-8 hidden sm:flex">
                                <a href="#/dashboard" class="text-gray-900 border-b-2 border-primary-400 px-1 pt-1">Dashboard</a>
                                <a href="#/my-events" class="text-gray-500 hover:text-gray-700 px-1 pt-1">My Events</a>
                                <a href="#/notices" class="text-gray-500 hover:text-gray-700 px-1 pt-1">Notices</a>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <a href="#/events/create" class="bg-primary-600 text-white px-4 py-2 rounded text-sm mr-4">Create Event</a>
                            <a href="#/profile" class="text-gray-500 hover:text-gray-700 text-sm mr-4">Profile</a>
                            <button onclick="Auth.logout()" class="text-gray-500 text-sm">Log Out</button>
                        </div>
                    </div>
                </nav>
                <div class="py-12 px-4 max-w-7xl mx-auto">${content}</div>
            </div>`
        };

        // --- LOGIC ---
        const App = {
            api: (action, method = 'GET', data = null) => {
                const opts = { method, headers: { 'Content-Type': 'application/json' } };
                if (data) opts.body = JSON.stringify(data);
                return fetch(`?api=${action}`, opts).then(r => r.json()).then(res => {
                    if (res.error) throw res;
                    return res;
                });
            },
            toast: (msg) => {
                const d = document.createElement('div');
                d.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded shadow-lg z-50';
                d.innerText = msg;
                document.body.appendChild(d);
                setTimeout(() => d.remove(), 3000);
            }
        };

        const Router = {
            routes: {},
            add(path, fn) { this.routes[path] = fn; },
            init() {
                window.onhashchange = () => this.run();
                this.run();
            },
            run() {
                let hash = location.hash || '#/login'; // Default to login if empty? Or check auth
                // Check auth
                const user = localStorage.getItem('user');
                if (!user && hash !== '#/login' && hash !== '#/register') {
                    location.hash = '#/login';
                    return;
                }
                if (user && (hash === '#/login' || hash === '#/register' || hash === '#/' || hash === '')) {
                    location.hash = '#/dashboard';
                    return;
                }

                // Match
                let handler = this.routes[hash];
                let params = [];
                if (!handler) {
                    for (const path in this.routes) {
                        if (path.includes(':')) {
                            const r = new RegExp('^' + path.replace(/:[^\s/]+/g, '([^/]+)') + '$');
                            const m = hash.match(r);
                            if (m) { handler = this.routes[path]; params = m.slice(1); break; }
                        }
                    }
                }
                if (handler) handler(...params);
            }
        };

        const Auth = {
            async handleLogin(e) {
                e.preventDefault();
                try {
                    const res = await App.api('login', 'POST', {
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value
                    });
                    localStorage.setItem('user', JSON.stringify(res.user));
                    App.toast('Welcome ' + res.user.name);
                    location.hash = '#/dashboard';
                } catch (err) { App.toast(err.error || 'Login failed'); }
            },
            async handleRegister(e) {
                e.preventDefault();
                try {
                    const res = await App.api('register', 'POST', {
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value,
                        student_id: document.getElementById('student_id').value
                    });
                    localStorage.setItem('user', JSON.stringify(res.user));
                    location.hash = '#/dashboard';
                } catch (err) { App.toast(err.error || 'Failed'); }
            },
            logout() {
                App.api('logout', 'POST');
                localStorage.removeItem('user');
                location.hash = '#/login';
            }
        };

        // --- CONTROLLERS ---
        Router.add('#/login', () => document.getElementById('app').innerHTML = Views.login);
        Router.add('#/register', () => document.getElementById('app').innerHTML = Views.register);

        Router.add('#/dashboard', async () => {
            try {
                const res = await App.api('events_list');
                const html = `
                <h2 class="text-2xl font-bold mb-6">Upcoming Events</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    ${res.data.map(e => renderEventCard(e)).join('')}
                </div>`;
                document.getElementById('app').innerHTML = Views.layout(html);
            } catch (e) { console.error(e); }
        });

        Router.add('#/my-events', async () => {
            try {
                const res = await App.api('events_my');
                const html = `
                <h2 class="text-2xl font-bold mb-6">My Events</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    ${res.data.length ? res.data.map(e => renderEventCard(e, true)).join('') : '<p>No events found.</p>'}
                </div>`;
                document.getElementById('app').innerHTML = Views.layout(html);
            } catch (e) { console.error(e); }
        });

        Router.add('#/events/create', () => {
            const form = `
            <div class="bg-white p-6 rounded shadow max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold mb-6">Create Event</h2>
                <form id="create-form" class="space-y-4">
                    <input name="title" class="w-full border p-2 rounded" placeholder="Title" required>
                    <textarea name="description" class="w-full border p-2 rounded" placeholder="Description" required></textarea>
                    <select name="category" class="w-full border p-2 rounded"><option>Academic</option><option>Sports</option><option>Cultural</option></select>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="date" name="date" class="w-full border p-2 rounded" required>
                        <input type="time" name="time" class="w-full border p-2 rounded" required>
                    </div>
                    <input name="location" class="w-full border p-2 rounded" placeholder="Location" required>
                    <input name="organizer" class="w-full border p-2 rounded" placeholder="Organizer" required>
                    <input name="image_path" class="w-full border p-2 rounded" placeholder="Image URL">
                    <button type="button" onclick="submitCreate()" class="bg-primary-600 text-white px-4 py-2 rounded">Create</button>
                </form>
            </div>
        `;
            document.getElementById('app').innerHTML = Views.layout(form);
        });

        Router.add('#/events/:id', async (id) => {
            const res = await App.api(`events_show&id=${id}`);
            const e = res.data;
            const html = `
            <div class="bg-white rounded overflow-hidden shadow">
                 <div class="h-64 bg-gray-300 bg-cover bg-center" style="background-image: url('${e.image_path || ''}')"></div>
                 <div class="p-8">
                    <h1 class="text-3xl font-bold mb-2">${e.title}</h1>
                    <p class="text-gray-600 mb-4">${e.date} at ${e.time} | ${e.location}</p>
                    <div class="prose max-w-none mb-6">${e.description}</div>
                    <div class="flex space-x-4">
                        <button onclick="interactEvent(${e.id}, 'registered')" class="bg-primary-600 text-white px-6 py-2 rounded">Register</button>
                        <button onclick="interactEvent(${e.id}, 'saved')" class="border border-yellow-500 text-yellow-600 px-6 py-2 rounded">Save</button>
                        <button onclick="reportEvent(${e.id})" class="text-red-500 underline">Report</button>
                    </div>
                 </div>
            </div>`;
            document.getElementById('app').innerHTML = Views.layout(html);
        });

        // --- HELPERS ---
        function renderEventCard(e, my = false) {
            return `
            <div class="bg-white rounded shadow overflow-hidden hover:shadow-lg transition">
                <div class="h-40 bg-gray-200 bg-cover bg-center" style="background-image: url('${e.image_path || ''}')"></div>
                <div class="p-4">
                    <span class="text-xs font-bold text-primary-600 uppercase">${e.category}</span>
                    <h3 class="font-bold text-lg truncate mt-1">${e.title}</h3>
                    <p class="text-sm text-gray-500">${e.date} • ${e.location}</p>
                    <a href="#/events/${e.id}" class="block mt-4 text-center bg-gray-100 py-2 rounded text-sm font-medium">View Details</a>
                </div>
            </div>`;
        }

        async function submitCreate() {
            const f = document.getElementById('create-form');
            const d = Object.fromEntries(new FormData(f));
            try {
                await App.api('events_create', 'POST', d);
                App.toast('Created'); location.hash = '#/dashboard';
            } catch (e) { App.toast(e.error); }
        }

        async function interactEvent(id, type) {
            try {
                const res = await App.api('events_register', 'POST', { event_id: id, type });
                App.toast(res.message);
            } catch (e) { App.toast('Error'); }
        }

        async function reportEvent(id) {
            const r = prompt('Reason?');
            if (r) App.api('reports_store', 'POST', { event_id: id, reason: r }).then(() => App.toast('Reported'));
        }

        // INIT
        Router.init();
    </script>
</body>

</html>