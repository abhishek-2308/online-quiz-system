<?php
$host = "localhost";
$user = "root";
$pass = ""; // default XAMPP passwod khali hi rahega

$db = "quiz_system";

$conn = new mysqli($host, $user, $pass, $db);




if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
