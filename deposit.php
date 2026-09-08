<?php
session_start();
require_once "db_config.php";

if (!isset($_SESSION['id_number'])) {
    header("Location: login.php");
    exit();
}

$id_number = $_SESSION['id_number'];

$stmt = $conn->prepare("SELECT first_name, balance FROM users WHERE id_number = ?");
$stmt->bind_param("s", $id_number);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    $mpesa_code = trim($_POST['mpesa_transaction_code'] ?? '');
    $payment_phone = trim($_POST['payment_phone'] ?? '');

    if ($amount === false || $amount <= 0) {
        $message = "Please enter a valid deposit amount.";
        $message_type = "error";

    } elseif ($mpesa_code === '' || $payment_phone === '') {
        $message = "Please enter your M-PESA transaction code and phone number.";
        $message_type = "error";

    } else {

        $reference = "DEP-" . strtoupper(bin2hex(random_bytes(4)));

        $stmt = $conn->prepare(
            "INSERT INTO transactions
            (id_number, amount, mpesa_transaction_code, payment_phone, payment_amount, payment_reference, type, status)
            VALUES (?, ?, ?, ?, ?, ?, 'deposit', 'payment_submitted')"
        );

        $stmt->bind_param(
            "sdssds",
            $id_number,
            $amount,
            $mpesa_code,
            $payment_phone,
            $amount,
            $reference
        );

        if ($stmt->execute()) {
            header("Location: deposit.php?submitted=1&ref=" . urlencode($reference));
            exit();
        } else {
            $message = "We could not submit your deposit. Please try again.";
            $message_type = "error";
        }

        $stmt->close();
    }
}

if (isset($_GET['submitted']) && $_GET['submitted'] === '1') {
    $reference = htmlspecialchars($_GET['ref'] ?? '');

    $message = "Payment submitted successfully. Reference: " . $reference .
               ". We're confirming your payment and your balance will update once the payment is verified.";
    $message_type = "success";
}

$stmt = $conn->prepare(
    "SELECT id, amount, status, payment_reference, created_at
     FROM transactions
     WHERE id_number = ? AND type = 'deposit'
     ORDER BY created_at DESC
     LIMIT 5"
);

$stmt->bind_param("s", $id_number);
$stmt->execute();
$deposits = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Deposit | Tradeverse</title>

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Inter, Arial, sans-serif;
    background: #080b12;
    color: #f5f7fb;
    min-height: 100vh;
}

.container {
    width: min(1100px, 92%);
    margin: auto;
    padding: 30px 0 100px;
}

.top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    gap: 20px;
}

.back {
    display: inline-block;
    color: #9ca6ba;
    text-decoration: none;
    margin-bottom: 20px;
}

.back:hover {
    color: white;
}

h1 {
    font-size: 32px;
    margin-bottom: 8px;
}

.subtitle {
    color: #8d96aa;
}

.balance {
    background: #111722;
    border: 1px solid #202838;
    padding: 18px 22px;
    border-radius: 16px;
    min-width: 210px;
}

.balance small {
    color: #8d96aa;
    display: block;
    margin-bottom: 6px;
}

.balance strong {
    font-size: 23px;
}

.message {
    padding: 17px;
    border-radius: 14px;
    margin-bottom: 22px;
    line-height: 1.5;
}

.success {
    background: #10261d;
    color: #7ee2a8;
    border: 1px solid #214f38;
}

.error {
    background: #2a1518;
    color: #ff9c9c;
    border: 1px solid #5a292f;
}

.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
}

.card {
    background: #111722;
    border: 1px solid #202838;
    border-radius: 20px;
    padding: 28px;
}

.card h2 {
    font-size: 20px;
    margin-bottom: 20px;
}

.payment {
    background: #0b101a;
    border: 1px solid #263149;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 22px;
}

.payment-title {
    font-size: 14px;
    color: #8d96aa;
    margin-bottom: 8px;
}

.mpesa-number {
    font-size: 25px;
    font-weight: 800;
    margin-bottom: 12px;
}

.instructions {
    color: #aeb7c5;
    font-size: 14px;
    line-height: 1.6;
}

label {
    display: block;
    color: #aab2c5;
    margin: 17px 0 8px;
    font-size: 14px;
}

input {
    width: 100%;
    padding: 15px;
    border-radius: 12px;
    border: 1px solid #2b3548;
    background: #090d15;
    color: white;
    font-size: 16px;
    outline: none;
}

input:focus {
    border-color: #5f8cff;
}

button {
    width: 100%;
    margin-top: 20px;
    padding: 15px;
    border: 0;
    border-radius: 12px;
    background: #5f8cff;
    color: white;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
}

button:hover {
    opacity: .9;
}

.helper {
    color: #778196;
    font-size: 13px;
    line-height: 1.5;
    margin-top: 15px;
}

.transaction {
    padding: 17px 0;
    border-bottom: 1px solid #202838;
}

.transaction:last-child {
    border-bottom: 0;
}

.transaction-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.amount {
    font-size: 17px;
    font-weight: 700;
}

.reference {
    color: #7f899c;
    font-size: 12px;
    margin-top: 6px;
}

.date {
    color: #737d90;
    font-size: 12px;
    margin-top: 4px;
}

.status {
    font-size: 12px;
    padding: 6px 10px;
    border-radius: 20px;
    white-space: nowrap;
}

.status-submitted {
    background: #292511;
    color: #e8cf70;
}

.status-approved {
    background: #10261d;
    color: #7ee2a8;
}

.status-declined {
    background: #2a1518;
    color: #ff9c9c;
}

.status-other {
    background: #202838;
    color: #aeb7c5;
}

.empty {
    color: #7f889c;
    padding: 15px 0;
}

@media (max-width: 750px) {

    .grid {
        grid-template-columns: 1fr;
    }

    .top {
        flex-direction: column;
    }

    .balance {
        width: 100%;
    }
}

</style>
</head>

<body>

<div class="container">

    <div class="top">

        <div>

            <a href="index.php" class="back">
                ← Back to Dashboard
            </a>

            <h1>Deposit Funds</h1>

            <p class="subtitle">
                Add funds to your Tradeverse investment account securely.
            </p>

        </div>

        <div class="balance">

            <small>Available Balance</small>

            <strong>
                KSh <?= number_format((float)$user['balance'], 2) ?>
            </strong>

        </div>

    </div>

    <?php if ($message): ?>

        <div class="message <?= htmlspecialchars($message_type) ?>">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>

    <div class="grid">

        <div class="card">

            <h2>Make a Deposit</h2>

            <div class="payment">

                <div class="payment-title">
                    Pay via M-PESA
                </div>

                <div class="mpesa-number">
                    0752159279
                </div>

                <p class="instructions">
                    Send the amount you want to deposit to the
                    Tradeverse M-PESA number above.
                </p>

            </div>

            <form method="POST">

                <label for="amount">
                    Deposit Amount (KSh)
                </label>

                <input
                    type="number"
                    id="amount"
                    name="amount"
                    min="1"
                    step="0.01"
                    placeholder="Enter amount"
                    required
                >

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

                <button type="submit">
                    Submit Payment
                </button>

            </form>

            <p class="helper">
                After submitting your payment details, we'll confirm
                the transaction before adding the funds to your balance.
            </p>

        </div>

        <div class="card">

            <h2>Recent Deposits</h2>

            <?php if ($deposits->num_rows > 0): ?>

                <?php while ($deposit = $deposits->fetch_assoc()): ?>

                    <?php
                    $status = strtolower($deposit['status']);

                    if ($status === 'payment_submitted' || $status === 'pending') {
                        $status_class = 'status-submitted';
                        $status_text = 'Payment Submitted';
                    } elseif ($status === 'approved' || $status === 'completed') {
                        $status_class = 'status-approved';
                        $status_text = 'Deposit Confirmed';
                    } elseif ($status === 'declined' || $status === 'failed') {
                        $status_class = 'status-declined';
                        $status_text = 'Not Confirmed';
                    } else {
                        $status_class = 'status-other';
                        $status_text = 'Processing';
                    }
                    ?>

                    <div class="transaction">

                        <div class="transaction-top">

                            <div>

                                <div class="amount">
                                    + KSh <?= number_format((float)$deposit['amount'], 2) ?>
                                </div>

                                <?php if (!empty($deposit['payment_reference'])): ?>

                                    <div class="reference">
                                        <?= htmlspecialchars($deposit['payment_reference']) ?>
                                    </div>

                                <?php endif; ?>

                                <div class="date">
                                    <?= date("d M Y, H:i", strtotime($deposit['created_at'])) ?>
                                </div>

                            </div>

                            <span class="status <?= $status_class ?>">
                                <?= $status_text ?>
                            </span>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <p class="empty">
                    No deposit transactions yet.
                </p>

            <?php endif; ?>

        </div>

    </div>

</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
