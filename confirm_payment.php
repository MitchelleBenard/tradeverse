<?php
// Retrieve the data sent from the form
$coin = $_POST['coin'];
$amount = $_POST['amount'];
$phone = $_POST['phone'];
$entered_amount = $_POST['entered_amount'];

// Save the details to a file
file_put_contents("payments.txt", "Coin: $coin | Expected: $amount | Sent: $entered_amount | Phone: $phone\n", FILE_APPEND);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1c1c1c;
            color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .confirmation-box {
            background-color: #2c2c2c;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        h2 {
            color: #66bb6a;
            margin-bottom: 1rem;
        }
        p {
            color: #e0e0e0;
            line-height: 1.6;
        }
        strong {
            color: #81c784;
        }
    </style>
</head>
<body>
    <div class="confirmation-box">
        <h2>Thank you!</h2>
        <p>We have received your confirmation for <strong><?= htmlspecialchars($coin) ?></strong> investment.</p>
        <p>Expected: <strong>KSh <?= htmlspecialchars($amount) ?></strong></p>
        <p>You sent: <strong>KSh <?= htmlspecialchars($entered_amount) ?></strong></p>
        <p>Phone: <strong><?= htmlspecialchars($phone) ?></strong></p>
        <p>We will verify and update you shortly.</p>
    </div>
</body>
</html>
