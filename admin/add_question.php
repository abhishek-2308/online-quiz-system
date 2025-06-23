<?php
include '../includes/db.php';
session_start();

$userId = $_SESSION['user_id'] ?? 0;


$res = $conn->query("SELECT role FROM users WHERE id = $userId");
$data = $res->fetch_assoc();

if (!$userId || $data['role'] !== 'admin') {
    echo "<h2 style='color:red; text-align:center;'>Only admin is allowed to add questions.</h2>";
    echo "<div style='text-align:center; margin-top:20px;'>
            <a href='../user/quiz.php'><button style='padding:10px 20px;'>Go to Quiz</button></a>
          </div>";
    exit();
}

$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $q = mysqli_real_escape_string($conn, $_POST['question']);
    $o1 = mysqli_real_escape_string($conn, $_POST['option1']);
    $o2 = mysqli_real_escape_string($conn, $_POST['option2']);
    $o3 = mysqli_real_escape_string($conn, $_POST['option3']);
    $o4 = mysqli_real_escape_string($conn, $_POST['option4']);
    $ans = mysqli_real_escape_string($conn, $_POST['answer']);

    $conn->query("INSERT INTO questions (question, option1, option2, option3, option4, answer) 
                  VALUES ('$q', '$o1', '$o2', '$o3', '$o4', '$ans')");
    $success = "Question added successfully!";
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM questions WHERE id=$id");
    header("Location: add_question.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Question</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2, h3 {
            text-align: center;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }

        input[type="text"] {
            margin: 5px;
            padding: 8px;
            width: 50%;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            margin-top: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background: #0056b3;
            cursor: pointer;
        }

        .question-list {
            margin-top: 30px;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .delete-btn {
            color: red;
            font-weight: bold;
            text-decoration: none;
        }

        .delete-btn:hover {
            text-decoration: underline;
        }

        .success {
            color: green;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include '../includes/nav.php'; ?>
<?php include '../includes/header.php'; ?>

<h2> Add New Question</h2>

<?php if ($success): ?>
    <p class="success"><?= $success ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="question" required placeholder="Enter Question"><br>
    <input type="text" name="option1" required placeholder="Option 1"><br>
    <input type="text" name="option2" required placeholder="Option 2"><br>
    <input type="text" name="option3" required placeholder="Option 3"><br>
    <input type="text" name="option4" required placeholder="Option 4"><br>
    <input type="text" name="answer" required placeholder="Correct Answer"><br>
    <button type="submit">Add Question</button>
</form>

<div style="text-align:center; margin-top:20px;">
    <a href="view_results.php"><button>View Students' History</button></a>
</div>

<div class="question-list">
    <h3>Existing Questions</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Correct Answer</th>
            <th>Action</th>
        </tr>

        <?php
        $res = $conn->query("SELECT * FROM questions ORDER BY id DESC");
        while ($row = $res->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['question'] ?></td>
            <td><?= $row['answer'] ?></td>
            <td>
                <a href="?delete=<?= $row['id'] ?>" class="delete-btn"
                   onclick="return confirm('Are you sure you want to delete this question?')">
                   Delete
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
