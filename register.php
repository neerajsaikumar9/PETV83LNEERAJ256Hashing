<?php
session_start();
// Open (and create if not exists) the SQLite database
$db = new SQLite3('users.db');
$db->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
)');

if (isset($_SESSION['username'])) {
    header('Location: home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']); // SHA-256 hash
    $stmt = $db->prepare('INSERT INTO users (first_name, last_name, username, password) VALUES (?, ?, ?, ?)');
    $stmt->bindValue(1, $first_name, SQLITE3_TEXT);
    $stmt->bindValue(2, $last_name, SQLITE3_TEXT);
    $stmt->bindValue(3, $username, SQLITE3_TEXT);
    $stmt->bindValue(4, $password, SQLITE3_TEXT);
    $result = @$stmt->execute(); // Suppress warning
    if ($result) {
        header('Location: login.html?registered=1');
        exit();
    } else {
        echo "Username already exists. <a href='register.html'>Try again</a>";
    }
} else {
    header('Location: register.html');
    exit();
} 