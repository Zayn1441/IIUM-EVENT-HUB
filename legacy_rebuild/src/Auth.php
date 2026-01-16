<?php

class Auth
{
    public static function user()
    {
        if (isset($_SESSION['user_id'])) {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            if ($user) {
                unset($user['password']); // Never return password
                return $user;
            }
        }
        return null;
    }

    public static function id()
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    public static function attempt($email, $password)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['is_admin'] = $user['is_admin'];
            return true;
        }
        return false;
    }

    public static function logout()
    {
        session_destroy();
    }
}
