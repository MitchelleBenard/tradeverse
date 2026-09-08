<?php
session_start();

if (!isset($_SESSION["id_number"])) {
    header("Location: login.php");
    exit();
}

require_once "db_config.php";

$id_number = $_SESSION["id_number"];

$stmt = $conn->prepare("SELECT first_name, last_name, email, id_number FROM users WHERE id_number = ?");
$stmt->bind_param("s", $id_number);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$full_name = trim($user["first_name"] . " " . $user["last_name"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tradeverse | Account</title>

    <link rel="stylesheet" href="index.css">

    <style>
        .account-page {
            max-width: 900px;
            margin: auto;
        }

        .profile-card {
            padding: 30px;
            border-radius: 18px;
            background: linear-gradient(135deg, #123524, #0d1b2a);
            border: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 25px;
        }

        .profile-top {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .avatar {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #22c55e;
            color: #04120a;
            font-size: 25px;
            font-weight: 800;
        }

        .profile-card h1 {
            margin: 0;
            font-size: 28px;
        }

        .profile-card p {
            margin: 6px 0 0;
            color: #94a3b8;
        }

        .account-section-title {
            margin: 28px 0 14px;
            font-size: 18px;
        }

        .account-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .account-option {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            border-radius: 14px;
            background: #0d1b2a;
            border: 1px solid rgba(255,255,255,0.08);
            color: white;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .account-option:hover {
            transform: translateY(-2px);
            background: #13263a;
            border-color: rgba(34,197,94,0.25);
        }

        .option-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: rgba(34,197,94,0.10);
            font-size: 20px;
        }

        .option-content strong {
            display: block;
            font-size: 14px;
        }

        .option-content span {
            display: block;
            margin-top: 4px;
            color: #64748b;
            font-size: 12px;
        }

        .logout-option {
            color: #ef4444;
        }

        .logout-option .option-icon {
            background: rgba(239,68,68,0.10);
        }

        @media (max-width: 650px) {
            .account-options {
                grid-template-columns: 1fr;
            }

            .profile-card {
                padding: 22px;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>Tradeverse</h1>

    <a href="index.php" class="profile-button"
       style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
        ←
    </a>
</header>

<main>

    <div class="account-page">

        <section class="profile-card">

            <div class="profile-top">

                <div class="avatar">
                    <?php echo strtoupper(substr($user["first_name"], 0, 1)); ?>
                </div>

                <div>
                    <h1><?php echo htmlspecialchars($full_name); ?></h1>
                    <p><?php echo htmlspecialchars($user["email"]); ?></p>
                </div>

            </div>

        </section>


        <h3 class="account-section-title">
            Account
        </h3>

        <div class="account-options">

            <a href="profile.php" class="account-option">
                <div class="option-icon">👤</div>

                <div class="option-content">
                    <strong>My Profile</strong>
                    <span>View your account information</span>
                </div>
            </a>


            <a href="login.php" class="account-option">
                <div class="option-icon">⇄</div>

                <div class="option-content">
                    <strong>Switch Account</strong>
                    <span>Sign in with another account</span>
                </div>
            </a>


            <a href="withdraw.php" class="account-option">
                <div class="option-icon">💳</div>

                <div class="option-content">
                    <strong>Withdraw</strong>
                    <span>Manage your withdrawals</span>
                </div>
            </a>


            <a href="logout.php" class="account-option logout-option">
                <div class="option-icon">↪</div>

                <div class="option-content">
                    <strong>Log Out</strong>
                    <span>Securely sign out of Tradeverse</span>
                </div>
            </a>

        </div>


        <h3 class="account-section-title">
            Support
        </h3>

        <div class="account-options">

            <a href="#" class="account-option"
               onclick="alert('Customer support will be available here.'); return false;">
                <div class="option-icon">🎧</div>

                <div class="option-content">
                    <strong>Customer Service</strong>
                    <span>Get help with your account</span>
                </div>
            </a>


            <a href="#" class="account-option"
               onclick="alert('FAQ section coming soon.'); return false;">
                <div class="option-icon">❓</div>

                <div class="option-content">
                    <strong>FAQ Guide</strong>
                    <span>Find answers to common questions</span>
                </div>
            </a>

        </div>

    </div>

</main>


<nav class="bottom-nav">

    <a href="index.php" class="nav-item">
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

    <a href="account.php" class="nav-item active">
        Account
    </a>

</nav>

</body>
</html>
