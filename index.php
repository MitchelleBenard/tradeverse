<?php
session_start();

if (!isset($_SESSION["id_number"])) {
    header("Location: login.php");
    exit();
}

require_once "db_config.php";

$id_number = $_SESSION["id_number"];

/* Get logged-in user's details */
$stmt = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE id_number = ?");
$stmt->bind_param("s", $id_number);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

$first_name = $user["first_name"] ?? "Investor";

/* Get account balance */
$balance = 0;

$stmt = $conn->prepare("SELECT balance FROM users WHERE id_number = ?");
$stmt->bind_param("s", $id_number);
$stmt->execute();
$balance_result = $stmt->get_result();

if ($balance_result->num_rows === 1) {
    $balance_row = $balance_result->fetch_assoc();
    $balance = (float)($balance_row["balance"] ?? 0);
}

/* Get transaction history */
$transactions = [];

$stmt = $conn->prepare("
    SELECT *
    FROM transactions
    WHERE id_number = ?
    ORDER BY id DESC
    LIMIT 5
");
$stmt->bind_param("s", $id_number);
$stmt->execute();
$transaction_result = $stmt->get_result();

while ($row = $transaction_result->fetch_assoc()) {
    $transactions[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tradeverse | Dashboard</title>

    <link rel="stylesheet" href="index.css">

    <style>
        .welcome-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            border-radius: 20px;
            background: rgba(34,197,94,0.10);
            color: #22c55e;
            font-size: 12px;
            font-weight: 700;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
        }

        .balance-card {
            margin-top: 24px;
            padding: 28px;
            border-radius: 16px;
            background: linear-gradient(135deg, #123524, #0d1b2a);
            border: 1px solid rgba(34,197,94,0.20);
        }

        .balance-label {
            color: #94a3b8;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .balance-value {
            font-size: clamp(32px, 6vw, 48px);
            font-weight: 800;
            letter-spacing: -1px;
        }

        .balance-note {
            margin-top: 8px;
            color: #94a3b8;
            font-size: 13px;
        }

        .section-title {
            margin: 30px 0 14px;
            font-size: 18px;
            font-weight: 750;
        }

        .activity-card {
            background: #0d1b2a;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            overflow: hidden;
        }

        .activity-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 17px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .activity-row:last-child {
            border-bottom: none;
        }

        .activity-name {
            font-weight: 650;
            font-size: 14px;
        }

        .activity-date {
            margin-top: 4px;
            color: #64748b;
            font-size: 11px;
        }

        .activity-amount {
            font-weight: 700;
            font-size: 14px;
        }

        .empty-activity {
            padding: 30px 20px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        .profile-menu {
            position: relative;
        }

        .profile-button {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.08);
            background: #0d1b2a;
            color: white;
            cursor: pointer;
            font-weight: 700;
        }

        .profile-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 52px;
            width: 210px;
            padding: 8px;
            background: #0d1b2a;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.45);
            z-index: 300;
        }

        .profile-dropdown.show {
            display: block;
        }

        .profile-name {
            padding: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 5px;
        }

        .profile-name strong {
            display: block;
            font-size: 14px;
        }

        .profile-name span {
            display: block;
            margin-top: 3px;
            color: #64748b;
            font-size: 11px;
        }

        .profile-link {
            display: block;
            padding: 11px 12px;
            border-radius: 9px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 13px;
        }

        .profile-link:hover {
            background: #13263a;
            color: white;
        }

        .profile-link.logout {
            color: #ef4444;
        }
    </style>
</head>

<body>

<header>
    <h1>Tradeverse</h1>

    <div class="profile-menu">

        <button class="profile-button" onclick="toggleProfileMenu()">
            <?php echo strtoupper(substr($first_name, 0, 1)); ?>
        </button>

        <div class="profile-dropdown" id="profileDropdown">

            <div class="profile-name">
                <strong><?php echo htmlspecialchars($first_name); ?></strong>
                <span>Tradeverse Investor</span>
            </div>

            <a href="account.php" class="profile-link">
                My Profile
            </a>

            <a href="account.php" class="profile-link">
                Switch Account
            </a>

            <a href="logout.php" class="profile-link logout">
                Log Out
            </a>

        </div>

    </div>
</header>

<main>

    <!-- Welcome -->
    <section class="account-box">

        <div class="welcome-row">

            <div>
                <h2>
                    Welcome back, <?php echo htmlspecialchars($first_name); ?>
                </h2>

                <p>
                    Manage your investments, monitor your balance and
                    track your financial activity from one place.
                </p>
            </div>

            <div class="status">
                <span class="status-dot"></span>
                Account active
            </div>

        </div>

    </section>


    <!-- Balance -->
    <section class="balance-card">

        <div class="balance-label">
            Available Balance
        </div>

        <div class="balance-value">
            KES <?php echo number_format($balance, 2); ?>
        </div>

        <div class="balance-note">
            Available for investment or withdrawal
        </div>

    </section>


    <!-- Account overview -->
    <h3 class="section-title">
        Account Overview
    </h3>

    <div class="dashboard-grid">

        <div class="dashboard-card">
            <div class="label">
                Available Balance
            </div>

            <div class="value">
                KES <?php echo number_format($balance, 2); ?>
            </div>

            <div class="change">
                Ready to invest
            </div>
        </div>


        <div class="dashboard-card">
            <div class="label">
                Total Invested
            </div>

            <div class="value">
                KES 0.00
            </div>

            <div class="change">
                Start investing
            </div>
        </div>


        <div class="dashboard-card">
            <div class="label">
                Total Returns
            </div>

            <div class="value">
                KES 0.00
            </div>

            <div class="change">
                No returns yet
            </div>
        </div>

    </div>


    <!-- Quick actions -->
    <section class="action-section">

        <h3>
            Quick Actions
        </h3>

        <div class="action-buttons">

            <a href="deposit.php" class="action-button primary">
                Deposit Funds
            </a>

            <a href="invest.php" class="action-button">
                Start Investing
            </a>

            <a href="withdraw.php" class="action-button">
                Withdraw Funds
            </a>

        </div>

    </section>


    <!-- Recent activity -->
    <h3 class="section-title">
        Recent Activity
    </h3>

    <div class="activity-card">

        <?php if (count($transactions) > 0): ?>

            <?php foreach ($transactions as $transaction): ?>

                <div class="activity-row">

                    <div>
                        <div class="activity-name">
                            <?php
                            echo htmlspecialchars(
                                $transaction["type"]
                                ?? $transaction["transaction_type"]
                                ?? "Transaction"
                            );
                            ?>
                        </div>

                        <div class="activity-date">
                            <?php
                            echo htmlspecialchars(
                                $transaction["created_at"]
                                ?? $transaction["date"]
                                ?? ""
                            );
                            ?>
                        </div>
                    </div>

                    <div class="activity-amount">
                        KES
                        <?php
                        echo number_format(
                            (float)($transaction["amount"] ?? 0),
                            2
                        );
                        ?>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="empty-activity">
                No transactions yet.
                Your deposits, investments and withdrawals will appear here.
            </div>

        <?php endif; ?>

    </div>


    <!-- Market -->
    <h3 class="section-title">
        Market Overview
    </h3>

    <div class="tradingview-widget-container">

        <div class="tradingview-widget-container__widget"></div>

        <script
            type="text/javascript"
            src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-overview.js"
            async>
        {
            "symbols": [
                ["CRYPTOCAP:BTC", "Bitcoin"],
                ["CRYPTOCAP:ETH", "Ethereum"],
                ["NASDAQ:AAPL", "Apple"],
                ["NASDAQ:TSLA", "Tesla"]
            ],
            "chartOnly": false,
            "width": "100%",
            "height": "500",
            "locale": "en",
            "colorTheme": "dark",
            "autosize": true
        }
        </script>

    </div>

</main>


<!-- Navigation -->

<nav class="bottom-nav">

    <a href="index.php" class="nav-item active">
        Dashboard
    </a>

    <a href="invest.php" class="nav-item">
        Invest
    </a>

    <a href="deposit.php" class="nav-item">
        Deposit
    </a>

    <a href="withdraw.php" class="nav-item">
        Withdraw
    </a>

    <a href="account.php" class="nav-item">
        Account
    </a>

</nav>


<script>
function toggleProfileMenu() {
    const menu = document.getElementById("profileDropdown");
    menu.classList.toggle("show");
}

document.addEventListener("click", function(event) {

    const profileMenu = document.querySelector(".profile-menu");

    if (!profileMenu.contains(event.target)) {
        document
            .getElementById("profileDropdown")
            .classList.remove("show");
    }

});
</script>

</body>
</html>
