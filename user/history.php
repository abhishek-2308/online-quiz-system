<?php
include '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");

$uid = $_SESSION['user_id'];
$results = $conn->query("SELECT * FROM results WHERE user_id = $uid ORDER BY date_taken DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test History</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        table {
            margin: auto;
            width: 80%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px gray;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<?php include '../includes/header.php'; ?>

<h2 style="text-align:center;">Your Quiz History</h2>

<table>
    <tr>
        <th>#</th>
        <th>Score</th>
        <th>Date Taken</th>
    </tr>
    <?php
    $count = 1;
    while ($row = $results->fetch_assoc()) {
        echo "<tr>
                <td>{$count}</td>
                <td>{$row['score']}</td>
                <td>{$row['date_taken']}</td>
              </tr>";
        $count++;
    }
    ?>
</table>

</body>
</html>
