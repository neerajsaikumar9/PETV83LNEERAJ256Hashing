<?php
$db = new SQLite3('users.db');
$result = $db->query('SELECT username, password FROM users');
echo "<h2>Registered Users (for demo/teacher)</h2>";
echo "<table border='1'><tr><th>Username</th><th>Password Hash (SHA-256)</th></tr>";
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "<tr><td>" . htmlspecialchars($row['username']) . "</td><td>" . htmlspecialchars($row['password']) . "</td></tr>";
}
echo "</table>";
echo "<p><a href='login.html'>Back to Login</a></p>"; 