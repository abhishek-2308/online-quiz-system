<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db.php';

$isAdmin = false;

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $res = $conn->query("SELECT role FROM users WHERE id = $id");
    $data = $res->fetch_assoc();
    if ($data && $data['role'] === 'admin') {
        $isAdmin = true;
    }
}
?>

<div class="nav-buttons" style="text-align: center; margin-bottom: 20px;">
    <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="../register.php"><button>Register</button></a>
        <a href="../login.php"><button>Login</button></a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="../user/quiz.php"><button>Take Quiz</button></a>

        <?php if ($isAdmin): ?>
            <a href="../admin/add_question.php"><button>Add Question</button></a>
            <a href="../admin/view_results.php"><button>View Results</button></a>
        <?php else: ?>
            <a href="../user/history.php"><button>View History</button></a>
        <?php endif; ?>

        <a href="../logout.php"><button>Logout</button></a>
    <?php endif; ?>
</div>
