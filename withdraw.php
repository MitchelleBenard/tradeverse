<?php
session_start();
require_once "db_config.php";

if (!isset($_SESSION['id_number'])) {
    header("Location: login.php");
    exit();
}

$id_number = $_SESSION['id_number'];
$message = "";
$message_type = "";

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

$balance = (float) $user['balance'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    $phone = trim($_POST['phone'] ?? '');

    if ($amount === false || $amount <= 0) {
        $message = "Please enter a valid withdrawal amount.";
        $message_type = "error";

    } elseif ($amount > $balance) {
        $message = "The withdrawal amount is greater than your available balance.";
        $message_type = "error";

    } elseif ($phone === '') {
        $message = "Please enter the M-PESA phone number where you want to receive the funds.";
        $message_type = "error";

    } else {

        $reference = "WTH-" . strtoupper(bin2hex(random_bytes(4)));

        $conn->begin_transaction();

        try {

            $stmt = $conn->prepare(
                "INSERT INTO transactions
                (id_number, amount, payment_phone, payment_amount, payment_reference, type, status)
                VALUES (?, ?, ?, ?, ?, 'withdrawal', 'payment_submitted')"
            );

            $stmt->bind_param(
                "sdsds",
                $id_number,
                $amount,
                $phone,
                $amount,
                $reference
            );

            if (!$stmt->execute()) {
                throw new Exception("Unable to create withdrawal request.");
            }

            $stmt->close();

            $new_balance = $balance - $amount;

            $stmt = $conn->prepare(
                "UPDATE users SET balance = ? WHERE id_number = ?"
            );

            $stmt->bind_param("ds", $new_balance, $id_number);

            if (!$stmt->execute()) {
                throw new Exception("Unable to update account balance.");
            }

            $stmt->close();

            $conn->commit();

            header(
                "Location: withdraw.php?submitted=1&ref=" .
                urlencode($reference)
            );
            exit();

        } catch (Exception $e) {

            $conn->rollback();

            $message = "We could not submit your withdrawal request. Please try again.";
            $message_type = "error";
        }
    }
}

if (isset($_GET['submitted']) && $_GET['submitted'] === '1') {

    $reference = htmlspecialchars($_GET['ref'] ?? '');

    $message =
        "Withdrawal submitted successfully. Reference: " .
        $reference .
        ". Your request is being processed and you will receive the funds once it has been completed.";

    $message_type = "success";
}

$stmt = $conn->prepare(
    "SELECT id, amount, status, payment_phone, payment_reference, created_at
     FROM transactions
     WHERE id_number = ? AND type = 'withdrawal'
     ORDER BY created_at DESC
     LIMIT 5"
);

$stmt->bind_param("s", $id_number);
$stmt->execute();
$withdrawals = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Withdraw | Tradeverse</title>

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
    gap: 20px;
    margin-bottom: 30px;
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

.balance-card {
    background: #111722;
    border: 1px solid #202838;
    padding: 18px 22px;
    border-radius: 16px;
    min-width: 240px;
}

.balance-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #8d96aa;
    font-size: 13px;
    margin-bottom: 7px;
}

.balance-value {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: .3px;
}

.eye-button {
    background: none;
    border: none;
    color: #9ca6ba;
    cursor: pointer;
    font-size: 18px;
    padding: 2px 5px;
}

.eye-button:hover {
    color: white;
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

.available {
    background: #0b101a;
    border: 1px solid #263149;
    border-radius: 14px;
    padding: 17px;
    margin-bottom: 22px;
}

.available-label {
    color: #8d96aa;
    font-size: 13px;
    margin-bottom: 6px;
}

.available-value {
    font-size: 20px;
    font-weight: 700;
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

.helper {
    color: #778196;
    font-size: 13px;
    margin-top: 8px;
}

button.submit {
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

button.submit:hover {
    opacity: .9;
}

.info {
    background: #0b101a;
    border: 1px solid #263149;
    border-radius: 14px;
    padding: 18px;
    margin-top: 20px;
}

.info-title {
    font-weight: 700;
    margin-bottom: 8px;
}

.info-text {
    color: #9da6b8;
    font-size: 13px;
    line-height: 1.6;
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

.transaction {
    padding: 17px 0;
    border-bottom: 1px solid #202838;
}

.transaction:last-child {
    border-bottom: none;
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

    .balance-card {
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

            <h1>Withdraw Funds</h1>

            <p class="subtitle">
                Withdraw funds from your Tradeverse account securely.
            </p>

        </div>

        <div class="balance-card">

            <div class="balance-label">

                <span>Available Balance</span>

                <button
                    type="button"
                    class="eye-button"
                    onclick="toggleBalance()"
                    id="eyeButton"
                    aria-label="Hide balance">
                    👁
                </button>

            </div>

            <div
                class="balance-value"
                id="balanceValue"
                data-balance="<?= number_format($balance, 2, '.', '') ?>">
                KSh <?= number_format($balance, 2) ?>
            </div>

        </div>

    </div>

    <?php if ($message): ?>

        <div class="message <?= htmlspecialchars($message_type) ?>">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>

    <div class="grid">

        <div class="card">

            <h2>Request a Withdrawal</h2>

            <div class="available">

                <div class="available-label">
                    Available to withdraw
                </div>

                <div class="available-value">
                    KSh <?= number_format($balance, 2) ?>
                </div>

            </div>

            <form method="POST">

                <label for="amount">
                    Withdrawal Amount (KSh)
                </label>

                <input
                    type="number"
                    id="amount"
                    name="amount"
                    min="1"
                    max="<?= htmlspecialchars($balance) ?>"
                    step="0.01"
                    placeholder="Enter amount"
                    required
                >

                <div class="helper">
                    You can withdraw up to your available balance.
                </div>

                <label for="phone">
                    M-PESA Phone Number
                </label>

                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    maxlength="20"
                    placeholder="e.g. 0712345678"
                    required
                >

                <div class="helper">
                    Funds will be sent to this M-PESA number after your
                    withdrawal has been processed.
                </div>

                <button type="submit" class="submit">
                    Submit Withdrawal
                </button>

            </form>

            <div class="info">

                <div class="info-title">
                    Withdrawal processing
                </div>

                <div class="info-text">
                    Your available balance is reserved when you submit a
                    withdrawal request. Your funds will be released to your
                    M-PESA number after the request is successfully processed.
                </div>

            </div>

        </div>

        <div class="card">

            <h2>Recent Withdrawals</h2>

            <?php if ($withdrawals->num_rows > 0): ?>

                <?php while ($withdrawal = $withdrawals->fetch_assoc()): ?>

                    <?php

                    $status = strtolower($withdrawal['status']);

                    if ($status === 'payment_submitted' || $status === 'pending') {

                        $status_class = 'status-submitted';
                        $status_text = 'Withdrawal Submitted';

                    } elseif ($status === 'approved' || $status === 'completed') {

                        $status_class = 'status-approved';
                        $status_text = 'Completed';

                    } elseif ($status === 'declined' || $status === 'failed') {

                        $status_class = 'status-declined';
                        $status_text = 'Not Completed';

                    } else {

                        $status_class = 'status-other';
                        $status_text = 'Processing';

                    }

                    ?>

                    <div class="transaction">

                        <div class="transaction-top">

                            <div>

                                <div class="amount">
                                    - KSh <?= number_format((float)$withdrawal['amount'], 2) ?>
                                </div>

                                <?php if (!empty($withdrawal['payment_reference'])): ?>

                                    <div class="reference">
                                        <?= htmlspecialchars($withdrawal['payment_reference']) ?>
                                    </div>

                                <?php endif; ?>

                                <div class="date">
                                    <?= date("d M Y, H:i", strtotime($withdrawal['created_at'])) ?>
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
                    No withdrawal transactions yet.
                </p>

            <?php endif; ?>

        </div>

    </div>

</div>

<script>

let balanceVisible = true;

function toggleBalance() {

    const balanceElement = document.getElementById("balanceValue");
    const eyeButton = document.getElementById("eyeButton");

    const balance = balanceElement.dataset.balance;

    if (balanceVisible) {

        balanceElement.textContent = "KSh ••••••";
        eyeButton.textContent = "🙈";
        eyeButton.setAttribute("aria-label", "Show balance");

        balanceVisible = false;

    } else {

        balanceElement.textContent =
            "KSh " + Number(balance).toLocaleString("en-KE", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

        eyeButton.textContent = "👁";
        eyeButton.setAttribute("aria-label", "Hide balance");

        balanceVisible = true;
    }
}

</script>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
