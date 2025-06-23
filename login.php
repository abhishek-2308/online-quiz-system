<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: user/quiz.php");
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
        }
        .nav-buttons {
            text-align: center;
            margin-bottom: 20px;
        }
        .nav-buttons a {
            text-decoration: none;
        }
        .nav-buttons button {
            margin: 5px;
        }
    </style>
</head>
<body>


<div class="nav-buttons">
    <a href="register.php"><button>Register</button></a>
    <a href="login.php"><button>Login</button></a>
    <a href="user/quiz.php"><button>Take Quiz</button></a>
    <a href="admin/add_question.php"><button>Add Question</button></a>
    <a href="logout.php"><button>Logout</button></a>
</div>

<h2 style="text-align:center;">Login</h2>

<form method="POST">
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <button type="submit">Login</button>
</form>

</body>
</html>
