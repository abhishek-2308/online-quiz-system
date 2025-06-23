<?php
include '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");

$uid = $_SESSION['user_id'];

$userQuery = $conn->query("SELECT username, email FROM users WHERE id = $uid");
$userData = $userQuery->fetch_assoc();
$userName = $userData['username'];

$questions = $conn->query("SELECT * FROM questions");
$questionsArray = [];
while ($row = $questions->fetch_assoc()) {
    $questionsArray[] = $row;
}
$total = count($questionsArray);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Take Quiz</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
        }

        .question-container {
            margin: auto;
            width: 80%;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px gray;
            border-radius: 10px;
            display: none;
        }

        .option { margin-bottom: 10px; }

        #timer {
            font-size: 20px;
            color: red;
            text-align: center;
            margin-bottom: 20px;
            display: none;
        }

        .btn {
            padding: 10px 15px;
            margin: 5px;
            font-size: 16px;
        }

        #welcome-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        #popup-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        #start-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
        }

        #start-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<?php include '../includes/header.php'; ?>
<div id="welcome-popup">
    <div id="popup-box">
        <h2>Welcome, <?= htmlspecialchars($userName) ?></h2>
        <p>Ready to take your quiz?</p>
        <button id="start-btn" onclick="startQuiz()">Start Quiz</button>
    </div>
</div>

<h2 style="text-align:center; display:none;" id="quiz-title">Quiz</h2>
<div id="timer">Time Left: 2:00</div>

<div class="question-container" id="quiz-container">
<form method="POST" action="result.php" id="quizForm">
    <?php foreach ($questionsArray as $index => $q): ?>
        <div class="question" id="question-<?= $index ?>" style="display: <?= $index === 0 ? 'block' : 'none' ?>;">
            <p><strong>Q<?= $index + 1 ?>: <?= $q['question'] ?></strong></p>
            <div class="option"><input type="radio" name="q<?= $q['id'] ?>" value="<?= $q['option1'] ?>"> <?= $q['option1'] ?></div>
            <div class="option"><input type="radio" name="q<?= $q['id'] ?>" value="<?= $q['option2'] ?>"> <?= $q['option2'] ?></div>
            <div class="option"><input type="radio" name="q<?= $q['id'] ?>" value="<?= $q['option3'] ?>"> <?= $q['option3'] ?></div>
            <div class="option"><input type="radio" name="q<?= $q['id'] ?>" value="<?= $q['option4'] ?>"> <?= $q['option4'] ?></div>
        </div>
    <?php endforeach; ?>

    <div style="text-align:center;">
        <button type="button" class="btn" onclick="prevQuestion()">Previous</button>
        <button type="button" class="btn" onclick="nextQuestion()">Next</button>
        <button type="submit" class="btn" id="submitBtn" style="display:none;">Submit Quiz</button>
    </div>
</form>
</div>

<script>
    let current = 0;
    let total = <?= $total ?>;
    const questions = document.querySelectorAll('.question');

    function showQuestion(index) {
        questions.forEach((q, i) => {
            q.style.display = i === index ? 'block' : 'none';
        });

        document.querySelector('button[onclick="prevQuestion()"]').style.display = index === 0 ? 'none' : 'inline-block';
        document.querySelector('button[onclick="nextQuestion()"]').style.display = index === total - 1 ? 'none' : 'inline-block';
        document.getElementById('submitBtn').style.display = index === total - 1 ? 'inline-block' : 'none';
    }

    function nextQuestion() {
        if (current < total - 1) {
            current++;
            showQuestion(current);
        }
    }

    function prevQuestion() {
        if (current > 0) {
            current--;
            showQuestion(current);
        }
    }

//Quiz
    function startQuiz() {
        document.getElementById('welcome-popup').style.display = 'none';
        document.getElementById('quiz-container').style.display = 'block';
        document.getElementById('quiz-title').style.display = 'block';
        document.getElementById('timer').style.display = 'block';
        startTimer();
    }
//Timer
    let timeLeft = 120;
    const timerDisplay = document.getElementById("timer");
    const form = document.getElementById("quizForm");

    function startTimer() {
        const countdown = setInterval(() => {
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;

            timerDisplay.innerText = `Time Left: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

            if (timeLeft <= 0) {
                clearInterval(countdown);
                alert("Time's up! Submitting your quiz.");
                form.submit();
            }

            timeLeft--;
        }, 1000);
    }
</script>

</body>
</html>
