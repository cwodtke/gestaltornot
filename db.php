<?php
// GestaltOrNot — Database Connection

function getDB() {
    $dbPath = __DIR__ . '/data/gestaltornot.db';
    $dbDir = dirname($dbPath);
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0755, true);
    }

    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        created_at TEXT DEFAULT (datetime('now')),
        reset_token TEXT DEFAULT NULL,
        reset_expires TEXT DEFAULT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS reviews (
        id TEXT PRIMARY KEY,
        user_id INTEGER DEFAULT NULL,
        feedback TEXT NOT NULL,
        screenshot TEXT DEFAULT NULL,
        url TEXT DEFAULT NULL,
        image_path TEXT DEFAULT NULL,
        created_at TEXT DEFAULT (datetime('now')),
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    // Add image_path column to existing tables that lack it
    $cols = $db->query("PRAGMA table_info(reviews)")->fetchAll();
    $colNames = array_column($cols, 'name');
    if (!in_array('image_path', $colNames)) {
        $db->exec("ALTER TABLE reviews ADD COLUMN image_path TEXT DEFAULT NULL");
    }

    return $db;
}
