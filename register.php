<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "This email is already registered. Please login.";
    } else {

        $conn->query("INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'student')");
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="nav-buttons" style="text-align: center; margin: 20px;">
    <a href="register.php"><button>Register</button></a>
    <a href="login.php"><button>Login</button></a>
    <a href="user/quiz.php"><button>Take Quiz</button></a>
    <a href="admin/add_question.php"><button>Add Question</button></a>
    <a href="logout.php"><button>Logout</button></a>
</div>

<h2 style="text-align:center;">Register</h2>

<form method="POST" style="text-align:center;">
    <?php if (isset($error)) echo "<p style='color:red; font-weight:bold;'>$error</p>"; ?>
    <input type="text" name="username" required placeholder="Username"><br>
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <button type="submit">Register</button>
</form>

</body>
</html>
