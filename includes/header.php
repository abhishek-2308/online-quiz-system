<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

$userId = $_SESSION['user_id'] ?? 0;
$roleText = "";

if ($userId) {
    $res = $conn->query("SELECT username, role FROM users WHERE id = $userId");
    $user = $res->fetch_assoc();
    $username = $user['username'];
    $role = $user['role'];
    $roleText = ucfirst($role); // Admin / Student
}
?>

<div style="text-align:right; padding: 10px; background: #f0f0f0; font-family:sans-serif;">
    <?php if ($userId): ?>
        👋 Welcome, <strong><?= $username ?></strong> (<?= $roleText ?>) |
        <a href="/online_quiz/logout.php">Logout</a>
    <?php else: ?>
        <a href="/online_quiz/login.php">Login</a> |
        <a href="/online_quiz/register.php">Register</a>
    <?php endif; ?>
</div>
