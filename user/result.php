<?php
include '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");

$uid = $_SESSION['user_id'];
$score = 0;
$answers = $_POST; 

$feedback = [];

foreach ($answers as $qid => $userAnswer) {
    $qid = intval(str_replace("q", "", $qid));
    $qData = $conn->query("SELECT * FROM questions WHERE id = $qid")->fetch_assoc();
    $correctAnswer = $qData['answer'];

    if ($userAnswer == $correctAnswer) {
        $score++;
    }

    $feedback[] = [
        'question' => $qData['question'],
        'user_answer' => $userAnswer,
        'correct_answer' => $correctAnswer,
        'is_correct' => $userAnswer == $correctAnswer
    ];
}

// Save result
$conn->query("INSERT INTO results (user_id, score) VALUES ($uid, $score)");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Result</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .result-box {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px gray;
            border-radius: 10px;
        }
        .correct { color: green; font-weight: bold; }
        .wrong { color: red; font-weight: bold; }
        .question-feedback {
            margin-bottom: 20px;
        }
        .history-btn {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            padding: 10px;
            text-align: center;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .history-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="result-box">
    <h2>🎉 Thank You for Completing the Test!</h2>
    <p><strong>Your Score:</strong> <?= $score ?>/<?= count($answers) ?></p>
    <hr>

    <h3> Question Feedback:</h3>
    <?php foreach ($feedback as $index => $f): ?>
        <div class="question-feedback">
            <p><strong>Q<?= $index + 1 ?>: <?= $f['question'] ?></strong></p>

            <?php if ($f['is_correct']): ?>
                <p class="correct"> Correct! Your answer: <?= $f['user_answer'] ?></p>
            <?php else: ?>
                <p class="wrong"> Wrong. Your answer: <?= $f['user_answer'] ?></p>
                <p class="correct"> Correct answer: <?= $f['correct_answer'] ?></p>
            <?php endif; ?>
        </div>
        <hr>
    <?php endforeach; ?>

    <a href="history.php" class="history-btn">📊 View My History</a>
</div>

</body>
</html>
