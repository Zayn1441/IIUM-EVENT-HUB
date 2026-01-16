<?php

class Event
{
    public static function all()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT events.*, users.name as organizer_name FROM events LEFT JOIN users ON events.user_id = users.id ORDER BY date ASC");
        return $stmt->fetchAll();
    }

    public static function upcoming($limit = 6)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT events.*, users.name as organizer_name FROM events LEFT JOIN users ON events.user_id = users.id WHERE date >= CURDATE() ORDER BY date ASC LIMIT ?");
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT events.*, users.name as organizer_name FROM events LEFT JOIN users ON events.user_id = users.id WHERE events.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO events (title, description, category, date, time, location, capacity, organizer, is_starpoints, participation_link, image_path, user_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

        $stmt->execute([
            $data['title'],
            $data['description'],
            $data['category'],
            $data['date'],
            $data['time'],
            $data['location'],
            $data['capacity'] ?? null,
            $data['organizer'],
            $data['is_starpoints'] ? 1 : 0,
            $data['participation_link'] ?? null,
            $data['image_path'] ?? null,
            $data['user_id']
        ]);

        return $db->lastInsertId();
    }

    // Additional methods for update/delete...
}
