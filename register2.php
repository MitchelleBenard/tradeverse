<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "user_system";

$conn = new mysqli($host, $user, $pass, $db);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $id_no = $_POST['id_number'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "<div class='message error'>❌ This email is already registered. <a href='login.php'>Login</a></div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, id_number, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first, $last, $id_no, $email, $password);

        if ($stmt->execute()) {
            $message = "<div class='message success'>✅ Registration successful. <a href='login.php'>Login</a></div>";
        } else {
            $message = "<div class='message error'>❌ Error: " . $stmt->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
            background-color: #ffe6e6;
            color: red;
            border: 1px solid red;
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

        input[type="text"],
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
            margin-top: 20px;
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
        <h2>Register</h2>
        <input type="text" name="first_name" placeholder="First Name" required><br>
        <input type="text" name="last_name" placeholder="Last Name" required><br>
        <input type="text" name="id_number" placeholder="ID Number" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
</body>
</html>
