<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "user_system";

$conn = new mysqli($host, $user, $pass, $db);
$message = "";

// Ensure the user is logged in
if (!isset($_SESSION['id_number'])) {
    header("Location: login.php");
    exit();
}

$id_number = $_SESSION['id_number'];

// Fetch the current balance from the users table
$balance_query = "SELECT balance FROM users WHERE id_number = '$id_number'";
$balance_result = $conn->query($balance_query);

if ($balance_result->num_rows > 0) {
    $row = $balance_result->fetch_assoc();
    $balance = $row['balance'];
} else {
    $balance = 0;
}

// Handle withdrawal
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];

    if ($amount <= 0) {
        $message = "Please enter a valid amount.";
    } elseif ($amount > $balance) {
        $message = "Insufficient balance.";
    } else {
        $conn->begin_transaction();

        try {
            // Insert withdrawal as 'pending' (optional)
            $withdrawal_sql = "INSERT INTO transactions (id_number, amount, type, status) 
                               VALUES ('$id_number', '$amount', 'withdrawal', 'pending')";
            if ($conn->query($withdrawal_sql) === TRUE) {
                // Deduct from balance immediately
                $new_balance = $balance - $amount;
                $balance_update_sql = "UPDATE users SET balance = $new_balance WHERE id_number = '$id_number'";

                if ($conn->query($balance_update_sql) === TRUE) {
                    $conn->commit();
                    $message = "Withdrawal request received. Please wait as our team processes your request.";
                    $_SESSION['balance'] = $new_balance;
                    $balance = $new_balance;
                } else {
                    $conn->rollback();
                    $message = "Error updating balance.";
                }
            } else {
                $conn->rollback();
                $message = "Error logging withdrawal.";
            }
        } catch (Exception $e) {
            $conn->rollback();
            $message = "Error: " . $e->getMessage();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Withdraw Funds</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto mt-8 p-4">
    <h1 class="text-3xl font-semibold text-gray-800 text-center mb-6">Withdraw Funds</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Enter amount to withdraw</h2>
        <form method="POST" action="">
            <div class="flex items-center mb-4">
                <input type="number" name="amount" placeholder="Enter amount"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <button type="submit"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                WITHDRAW
            </button>
        </form>
        <div class="mt-4 text-yellow-600 font-semibold"><?php echo $message; ?></div>
    </div>
</div>
</body>
</html>
