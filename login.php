<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "user_system";

$conn = new mysqli($host, $user, $pass, $db);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Store user details in session
            $_SESSION['user'] = $user['first_name'];   // Store first name (if needed)
            $_SESSION['id_number'] = $user['id_number'];  // Store id_number in session
            header("Location: index.php");
            exit;
        } else {
            $message = "<div class='message error'>❌ Incorrect password.</div>";
        }
    } else {
        $message = "<div class='message error'>❌ No account found with that email.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            background-color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .message {
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 5px;
            font-weight: bold;
            width: 360px;
            text-align: center;
        }

        .error {
            background-color: #ffe6e6;
            color: red;
            border: 1px solid red;
        }

        form {
            background-color: #000;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            width: 360px;
            text-align: center;
            color: #fff;
        }

        h2 {
            margin-bottom: 20px;
            color: #fff;
        }

        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            background-color: #fff;
            color: #000;
        }

        button {
            background-color: red;
            color: #fff;
            padding: 12px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: darkred;
        }

        p {
            margin-top: 15px;
            font-size: 14px;
            color: #fff;
        }

        a {
            color: red;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php if (!empty($message)) echo $message; ?>
    <form method="POST" action="">
        <h2>Login</h2>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
        <p><a href="forgotpassword.php">Forgot Password?</a></p>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
</body>
</html>
