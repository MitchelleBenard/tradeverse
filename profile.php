<?php
session_start();

if (!isset($_SESSION['id_number'])) {
    header("Location: login.php");
    exit();
}

require_once "db_config.php";

$id_number = $_SESSION['id_number'];

$stmt = $conn->prepare("
    SELECT first_name, last_name, email, id_number, balance
    FROM users
    WHERE id_number = ?
    LIMIT 1
");

$stmt->bind_param("s", $id_number);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$full_name = $user['first_name'] . ' ' . $user['last_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Tradeverse</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #07111f;
            color: white;
        }

        header {
            height: 70px;
            padding: 0 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            background: #081525;
        }

        header h1 {
            margin: 0;
            font-size: 22px;
        }

        .back-button {
            color: white;
            text-decoration: none;
            font-size: 24px;
        }

        main {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-title {
            margin-bottom: 25px;
        }

        .page-title h2 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .page-title p {
            margin: 0;
            color: #94a3b8;
        }

        .profile-card {
            background: #0d1b2a;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 18px;
            padding: 28px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 18px;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .avatar {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: #22c55e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            font-weight: bold;
            color: #04110a;
        }

        .profile-header h3 {
            margin: 0 0 5px;
            font-size: 21px;
        }

        .profile-header p {
            margin: 0;
            color: #94a3b8;
        }

        .details {
            margin-top: 10px;
        }

        .detail {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 18px 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .detail:last-child {
            border-bottom: none;
        }

        .label {
            color: #94a3b8;
        }

        .value {
            font-weight: 600;
            text-align: right;
        }

        .balance {
            color: #22c55e;
            font-size: 18px;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .button {
            flex: 1;
            padding: 14px;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
        }

        .primary {
            background: #22c55e;
            color: #04110a;
        }

        .secondary {
            background: #13263a;
            color: white;
            border: 1px solid rgba(255,255,255,0.08);
        }

        @media (max-width: 600px) {
            main {
                margin: 25px auto;
            }

            .profile-card {
                padding: 20px;
            }

            .detail {
                flex-direction: column;
                gap: 6px;
            }

            .value {
                text-align: left;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>Tradeverse</h1>

    <a href="account.php" class="back-button">←</a>
</header>

<main>

    <div class="page-title">
        <h2>My Profile</h2>
        <p>View your Tradeverse account information.</p>
    </div>

    <section class="profile-card">

        <div class="profile-header">

            <div class="avatar">
                <?php echo strtoupper(substr($user['first_name'], 0, 1)); ?>
            </div>

            <div>
                <h3><?php echo htmlspecialchars($full_name); ?></h3>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>

        </div>

        <div class="details">

            <div class="detail">
                <span class="label">Full Name</span>
                <span class="value">
                    <?php echo htmlspecialchars($full_name); ?>
                </span>
            </div>

            <div class="detail">
                <span class="label">Email Address</span>
                <span class="value">
                    <?php echo htmlspecialchars($user['email']); ?>
                </span>
            </div>

            <div class="detail">
                <span class="label">ID Number</span>
                <span class="value">
                    <?php echo htmlspecialchars($user['id_number']); ?>
                </span>
            </div>

            <div class="detail">
                <span class="label">Available Balance</span>
                <span class="value balance">
                    KSh <?php echo number_format($user['balance'], 2); ?>
                </span>
            </div>

            <div class="detail">
                <span class="label">Account Status</span>
                <span class="value">Active</span>
            </div>

        </div>

        <div class="actions">
            <a href="withdraw.php" class="button primary">Withdraw Funds</a>
            <a href="account.php" class="button secondary">Back to Account</a>
        </div>

    </section>

</main>

</body>
</html>
