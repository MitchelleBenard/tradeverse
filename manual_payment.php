<?php
$coin = $_GET['coin'];
$amount = $_GET['amount'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complete Payment</title>
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
        .container {
            background-color: #2c2c2c;
            border-radius: 8px;
            padding: 2rem;
            width: 100%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        h2, h3 {
            color: #81c784;
        }
        p {
            color: #e0e0e0;
            margin-bottom: 1.5rem;
        }
        input[type="text"], input[type="number"], button {
            width: 100%;
            padding: 1rem;
            margin: 0.5rem 0;
            border-radius: 5px;
            border: 1px solid #81c784;
            background-color: #424242;
            color: #ffffff;
            font-size: 1rem;
        }
        input[type="text"]:focus, input[type="number"]:focus, button:focus {
            outline: none;
            border-color: #66bb6a;
            box-shadow: 0 0 5px rgba(102, 187, 106, 0.8);
        }
        button {
            background-color: #66bb6a;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #81c784;
        }
        input[type="text"].mpesa-number {
            color: #66bb6a;
            font-weight: bold;
            font-size: 1.1rem;
            text-align: center;
            background-color: #333333;
            border: 1px solid #66bb6a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Invest in <?= htmlspecialchars($coin) ?></h2>
        <p>Please send <strong>KSh <?= htmlspecialchars($amount) ?></strong> to the number below:</p>
        
        <!-- Editable M-PESA number -->
        <input type="text" name="mpesa_number" value="0792721804" class="mpesa-number"><br><br>

        <p>After sending, enter your payment details to confirm:</p>

        <form action="confirm_payment.php" method="POST">
            <input type="hidden" name="coin" value="<?= htmlspecialchars($coin) ?>">
            <input type="hidden" name="amount" value="<?= htmlspecialchars($amount) ?>">

            <!-- Input for entered amount -->
            <input type="number" name="entered_amount" placeholder="Amount Sent (KSh)" required><br>

            <!-- M-PESA phone number -->
            <input type="text" name="phone" placeholder="Your M-PESA Phone Number" required><br><br>

            <button type="submit">Confirm Payment</button>
        </form>
    </div>
</body>
</html>
