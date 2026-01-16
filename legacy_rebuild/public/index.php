<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IIUM Event Hub</title>
    <!-- Tailwind CSS (CDN for simplicity) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa',
                            500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 antialiased">

    <div id="app">
        <!-- Views will be loaded here -->
    </div>

    <!-- Application Scripts -->
    <script src="js/app.js"></script>
    <script src="js/router.js"></script>
    <script src="js/auth.js"></script>
    <script src="js/dashboard.js"></script>
    <script src="js/events.js"></script>
    <script src="js/profile.js"></script>
    <script src="js/notices.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Check Auth Status on Load (Optional, implemented in auth.js)
            Auth.init();
        });
    </script>
</body>

</html>