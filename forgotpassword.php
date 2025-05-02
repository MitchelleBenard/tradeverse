<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "user_system";

$conn = new mysqli($host, $user, $pass, $db);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $newpass = $_POST['new_password'];
    $confirmpass = $_POST['confirm_password'];

    if ($newpass !== $confirmpass) {
        $message = "<div class='message error'>❌ Passwords do not match.</div>";
    } else {
        $hashed_pass = password_hash($newpass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
        $stmt->bind_param("ss", $hashed_pass, $email);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $message = "<div class='message success'>✅ Password reset successful. <a href='login.php'>Login now</a></div>";
        } else {
            $message = "<div class='message error'>❌ Email not found or update failed.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
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

        .success {
            background-color: #e6ffea;
            color: green;
            border: 1px solid green;
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
        <h2>Reset Password</h2>
        <input type="email" name="email" placeholder="Enter your email" required><br>
        <input type="password" name="new_password" placeholder="New Password" required><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
