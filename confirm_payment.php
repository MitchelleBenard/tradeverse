<?php
session_start();

if (!isset($_SESSION['id_number'])) {
    header("Location: login.php");
    exit;
}

require "db_config.php";

$id_number = $_SESSION['id_number'];

$coin = trim($_POST['coin'] ?? '');
$amount = (float) ($_POST['amount'] ?? 0);
$mpesa_code = trim($_POST['mpesa_transaction_code'] ?? '');
$payment_phone = trim($_POST['payment_phone'] ?? '');
$payment_amount = (float) ($_POST['payment_amount'] ?? 0);

if ($coin === '' || $amount <= 0 || $mpesa_code === '' || $payment_phone === '' || $payment_amount <= 0) {
    die("Invalid payment details. Please go back and try again.");
}

if (abs($payment_amount - $amount) > 0.01) {
    die("The amount paid must match the investment amount.");
}

$reference = 'TV-' . strtoupper(bin2hex(random_bytes(5)));

$stmt = $conn->prepare("
    INSERT INTO investments
    (id_number, asset, amount, mpesa_transaction_code, payment_phone, payment_amount, payment_reference, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, 'payment_submitted')
");

$stmt->bind_param(
    "ssdssds",
    $id_number,
    $coin,
    $amount,
    $mpesa_code,
    $payment_phone,
    $payment_amount,
    $reference
);

if (!$stmt->execute()) {
    die("We could not submit your investment. Please try again.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Submitted</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #0b0f19;
            color: white;
            min-height: 100vh;
            padding: 30px 16px;
        }

        .container {
            max-width: 520px;
            margin: auto;
        }

        .card {
            background: #141a26;
            border: 1px solid #252d3d;
            border-radius: 20px;
            padding: 30px 24px;
            text-align: center;
        }

        .success-icon {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: #173c2a;
            color: #55d98b;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
        }

        h1 {
            font-size: 26px;
            margin-bottom: 12px;
        }

        .message {
            color: #aeb7c5;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .details {
            background: #0d131f;
            border-radius: 14px;
            padding: 18px;
            text-align: left;
            margin-bottom: 22px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #202838;
        }

        .row:last-child {
            border-bottom: none;
        }

        .label {
            color: #8f99a9;
        }

        .value {
            font-weight: bold;
            text-align: right;
            word-break: break-word;
        }

        .status {
            color: #f2c94c;
        }

        .button {
            display: block;
            width: 100%;
            padding: 15px;
            border-radius: 10px;
            background: #4f6df5;
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-top: 12px;
        }

        .button.secondary {
            background: #202838;
        }

        .note {
            color: #7f8999;
            font-size: 13px;
            line-height: 1.5;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">

        <div class="success-icon">✓</div>

        <h1>Payment Submitted</h1>

        <p class="message">
            Thank you. We've received your payment details and are confirming
            your payment. Your investment will be activated once the payment
            has been successfully confirmed.
        </p>

        <div class="details">

            <div class="row">
                <span class="label">Investment</span>
                <span class="value">
                    <?= htmlspecialchars($coin) ?>
                </span>
            </div>

            <div class="row">
                <span class="label">Amount</span>
                <span class="value">
                    KSh <?= number_format($amount, 2) ?>
                </span>
            </div>

            <div class="row">
                <span class="label">M-PESA Code</span>
                <span class="value">
                    <?= htmlspecialchars($mpesa_code) ?>
                </span>
            </div>

            <div class="row">
                <span class="label">Reference</span>
                <span class="value">
                    <?= htmlspecialchars($reference) ?>
                </span>
            </div>

            <div class="row">
                <span class="label">Status</span>
                <span class="value status">
                    Payment Submitted
                </span>
            </div>

        </div>

        <a href="invest.php" class="button">
            View My Investments
        </a>

        <a href="index.php" class="button secondary">
            Back to Dashboard
        </a>

        <p class="note">
            Keep your M-PESA transaction code and Tradeverse reference for
            your records.
        </p>

    </div>

</div>

</body>
</html>
