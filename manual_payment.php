<?php
session_start();

if (!isset($_SESSION['id_number'])) {
    header("Location: login.php");
    exit;
}

$coin = trim($_GET['coin'] ?? '');
$amount = (float) ($_GET['amount'] ?? 0);

if ($coin === '' || $amount <= 0) {
    header("Location: invest.php");
    exit;
}

$displayAmount = number_format($amount, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Investment Payment</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #0b0f19;
            color: #ffffff;
            min-height: 100vh;
            padding: 30px 16px;
        }

        .container {
            width: 100%;
            max-width: 520px;
            margin: auto;
        }

        .back {
            display: inline-block;
            color: #aaa;
            text-decoration: none;
            margin-bottom: 25px;
        }

        .card {
            background: #141a26;
            border: 1px solid #252d3d;
            border-radius: 18px;
            padding: 25px;
        }

        h1 {
            font-size: 25px;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #9da6b5;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .summary {
            background: #0d131f;
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 22px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 8px 0;
        }

        .label {
            color: #9da6b5;
        }

        .value {
            font-weight: bold;
            text-align: right;
        }

        .payment-box {
            background: #182131;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 22px;
        }

        .payment-box h3 {
            margin-bottom: 12px;
        }

        .mpesa-number {
            font-size: 24px;
            font-weight: bold;
            margin: 8px 0;
        }

        .instruction {
            color: #b7bfcc;
            line-height: 1.6;
            font-size: 14px;
        }

        label {
            display: block;
            margin: 16px 0 7px;
            color: #cbd2dc;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #30394b;
            border-radius: 10px;
            background: #0d131f;
            color: white;
            font-size: 16px;
            outline: none;
        }

        input:focus {
            border-color: #6c8cff;
        }

        button {
            width: 100%;
            margin-top: 22px;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: #4f6df5;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #405bd1;
        }

        .note {
            margin-top: 18px;
            color: #8993a3;
            font-size: 13px;
            line-height: 1.5;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container">

    <a href="invest.php" class="back">← Back to Investments</a>

    <div class="card">

        <h1>Complete Your Investment</h1>

        <p class="subtitle">
            You're almost there. Make your M-PESA payment and submit the
            transaction details so we can confirm your investment.
        </p>

        <div class="summary">
            <div class="row">
                <span class="label">Investment</span>
                <span class="value"><?= htmlspecialchars($coin) ?></span>
            </div>

            <div class="row">
                <span class="label">Amount</span>
                <span class="value">KSh <?= $displayAmount ?></span>
            </div>
        </div>

        <div class="payment-box">

            <h3>Make Payment via M-PESA</h3>

            <p class="instruction">
                Send <strong>KSh <?= $displayAmount ?></strong> to the
                Tradeverse payment number:
            </p>

            <div class="mpesa-number">0752159279</div>

            <p class="instruction">
                After completing the payment, enter the M-PESA transaction
                code and the phone number you used to make the payment below.
            </p>

        </div>

        <form action="confirm_payment.php" method="POST">

            <input type="hidden" name="coin"
                   value="<?= htmlspecialchars($coin) ?>">

            <input type="hidden" name="amount"
                   value="<?= htmlspecialchars($amount) ?>">

            <label for="mpesa_transaction_code">
                M-PESA Transaction Code
            </label>

            <input
                type="text"
                id="mpesa_transaction_code"
                name="mpesa_transaction_code"
                placeholder="e.g. QAB12CD34E"
                maxlength="100"
                required
            >

            <label for="payment_phone">
                M-PESA Phone Number
            </label>

            <input
                type="tel"
                id="payment_phone"
                name="payment_phone"
                placeholder="e.g. 0712345678"
                maxlength="20"
                required
            >

            <label for="payment_amount">
                Amount Paid
            </label>

            <input
                type="number"
                id="payment_amount"
                name="payment_amount"
                value="<?= htmlspecialchars($amount) ?>"
                min="<?= htmlspecialchars($amount) ?>"
                step="0.01"
                required
            >

            <button type="submit">
                Submit Payment
            </button>

        </form>

        <p class="note">
            Your investment will be activated once your payment has been
            successfully confirmed.
        </p>

    </div>

</div>

</body>
</html>
