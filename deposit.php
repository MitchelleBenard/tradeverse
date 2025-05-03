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

$id_number = $_SESSION['id_number'];  // Use the id_number from session

// Handle deposit form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];

    // Validate amount
    if ($amount <= 0) {
        $message = "Please enter a valid amount.";
    } else {
        // Insert deposit into transactions
        $sql = "INSERT INTO transactions (id_number, amount, type) VALUES ('$id_number', '$amount', 'deposit')";
        if ($conn->query($sql) === TRUE) {
            $message = "Deposit request submitted. Please send KSh $amount to 0752159279.";
        } else {
            $message = "Error recording transaction: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deposit Funds</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-4">
        <h1 class="text-3xl font-semibold text-gray-800 text-center mb-6">Deposit Funds</h1>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-md mx-auto">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Enter Amount to Deposit</h2>

            <form action="deposit.php" method="POST">
                <input type="number" name="amount" placeholder="e.g. 100" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-4 focus:outline-none focus:shadow-outline">

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded w-full">
                    Submit
                </button>
            </form>

            <p class="text-sm text-gray-600 mt-4">
                After submitting, please send the same amount via M-PESA to: <br>
                <span class="text-black font-bold">0752159279</span>
            </p>

            <div class="mt-4 text-green-700 font-semibold">
                <?php echo $message; ?>
            </div>
        </div>
    </div>
</body>
</html>
