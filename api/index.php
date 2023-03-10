<?php

$db = new SQLite3('/tmp/db.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

$db->query('CREATE TABLE IF NOT EXISTS "visits" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "url" VARCHAR,
    "time" DATETIME
)');

$db->query('CREATE TABLE IF NOT EXISTS "users" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" VARCHAR,
    "email" VARCHAR,
    "password" VARCHAR,
    "created_at" DATETIME,
    "updated_at" DATETIME
)');

$statement = $db->prepare('INSERT INTO "visits" ("url", "time") VALUES (:url, :time)');
$statement->bindValue(':url', ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
$statement->bindValue(':time', date('Y-m-d H:i:s'));
$statement->execute();

$visits = $db->querySingle('SELECT COUNT(id) FROM "visits"');

echo("Nombre total de visiteurs : $visits");

$db->close();
