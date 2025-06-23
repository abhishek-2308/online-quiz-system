<?php
include '../includes/db.php';
session_start();

$userId = $_SESSION['user_id'] ?? 0;
$res = $conn->query("SELECT role FROM users WHERE id = $userId");
$data = $res->fetch_assoc();

if (!$userId || $data['role'] !== 'admin') {
    echo "<h2 style='text-align:center; color:red;'> Only admin can view student history.</h2>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Quiz History</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<h2>All Students' Quiz History</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Score</th>
        <th>Date</th>
    </tr>

    <?php
    $results = $conn->query("SELECT r.*, u.username, u.email 
                             FROM results r 
                             JOIN users u ON r.user_id = u.id 
                             ORDER BY r.date_taken DESC");

    while ($row = $results->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['username'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['score'] ?></td>
        <td><?= $row['date_taken'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
