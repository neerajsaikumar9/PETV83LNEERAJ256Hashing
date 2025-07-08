<?php
session_start();
$db = new SQLite3('users.db');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
    $stmt->bindValue(1, $username, SQLITE3_TEXT);
    $stmt->bindValue(2, $password, SQLITE3_TEXT);
    $result = $stmt->execute();
    if ($user = $result->fetchArray(SQLITE3_ASSOC)) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        header('Location: home.php');
        exit();
    } else {
        echo "Invalid username or password. <a href='login.html'>Try again</a>";
    }
} else {
    header('Location: login.html');
    exit();
} 