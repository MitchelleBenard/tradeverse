<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

require_once "db_config.php";

$message = "";
$message_type = "success";

/*
|--------------------------------------------------------------------------
| Handle transaction actions
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $transaction_id = (int)($_POST["transaction_id"] ?? 0);
    $action = $_POST["action"] ?? "";

    if ($transaction_id > 0 && in_array($action, ["approve", "decline"])) {

        $conn->begin_transaction();

        try {

            $stmt = $conn->prepare(
                "SELECT id, id_number, amount, type, status
                 FROM transactions
                 WHERE id = ?
                 FOR UPDATE"
            );

            $stmt->bind_param("i", $transaction_id);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows !== 1) {
                throw new Exception("Transaction not found.");
            }

            $transaction = $result->fetch_assoc();

            if ($transaction["status"] !== "pending") {
                throw new Exception("This transaction has already been processed.");
            }

            $id_number = $transaction["id_number"];
            $amount = (float)$transaction["amount"];
            $type = $transaction["type"];


            /*
            |--------------------------------------------------------------------------
            | APPROVE
            |--------------------------------------------------------------------------
            */

            if ($action === "approve") {

                /*
                Deposit:
                Add money to user's balance only when admin approves it.
                */

                if ($type === "deposit") {

                    $stmt = $conn->prepare(
                        "UPDATE users
                         SET balance = balance + ?
                         WHERE id_number = ?"
                    );

                    $stmt->bind_param("ds", $amount, $id_number);
                    $stmt->execute();

                    if ($stmt->affected_rows !== 1) {
                        throw new Exception("User account could not be updated.");
                    }
                }

                /*
                Withdrawal:
                The withdrawal amount was already reserved/deducted
                when the user submitted the request.
                Therefore approval DOES NOT deduct again.
                */

                $new_status = "approved";

                $stmt = $conn->prepare(
                    "UPDATE transactions
                     SET status = ?
                     WHERE id = ?"
                );

                $stmt->bind_param("si", $new_status, $transaction_id);
                $stmt->execute();

                $message = ucfirst($type) . " approved successfully.";

            }


            /*
            |--------------------------------------------------------------------------
            | DECLINE
            |--------------------------------------------------------------------------
            */

            else {

                /*
                If a withdrawal is declined, return the reserved
                money back to the user's available balance.
                */

                if ($type === "withdrawal") {

                    $stmt = $conn->prepare(
                        "UPDATE users
                         SET balance = balance + ?
                         WHERE id_number = ?"
                    );

                    $stmt->bind_param("ds", $amount, $id_number);
                    $stmt->execute();

                    if ($stmt->affected_rows !== 1) {
                        throw new Exception("Unable to refund withdrawal amount.");
                    }
                }

                $new_status = "declined";

                $stmt = $conn->prepare(
                    "UPDATE transactions
                     SET status = ?
                     WHERE id = ?"
                );

                $stmt->bind_param("si", $new_status, $transaction_id);

                if (!$stmt->execute()) {
                    throw new Exception("Unable to update transaction.");
                }

                $message = ucfirst($type) . " declined.";

            }

            $conn->commit();

        } catch (Exception $e) {

            $conn->rollback();

            $message = $e->getMessage();
            $message_type = "error";
        }
    }
}


/*
|--------------------------------------------------------------------------
| Fetch pending transactions
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT
        t.id,
        t.id_number,
        u.first_name,
        u.last_name,
        u.email,
        t.amount,
        t.type,
        t.status,
        t.created_at
     FROM transactions t
     JOIN users u ON t.id_number = u.id_number
     WHERE t.status IN ('pending', 'payment_submitted')
     ORDER BY t.created_at DESC"
);

$stmt->execute();

$pending_transactions = $stmt->get_result();


/*
|--------------------------------------------------------------------------
| Account statistics
|--------------------------------------------------------------------------
*/

$total_users = 0;
$total_balance = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total_users,
            COALESCE(SUM(balance), 0) AS total_balance
     FROM users"
);

if ($result) {
    $stats = $result->fetch_assoc();
    $total_users = $stats["total_users"];
    $total_balance = $stats["total_balance"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tradeverse | Admin Dashboard</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Inter, Arial, sans-serif;
    background: #07111f;
    color: #f8fafc;
}

header {
    padding: 22px 35px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}

.logo {
    font-size: 22px;
    font-weight: 800;
}

.admin-label {
    color: #94a3b8;
    font-size: 13px;
}

.logout {
    color: #ef4444;
    text-decoration: none;
    font-size: 13px;
    margin-left: 20px;
}

.container {
    width: min(1200px, calc(100% - 40px));
    margin: auto;
    padding: 35px 0 60px;
}

h1 {
    margin-bottom: 8px;
}

.subtitle {
    color: #94a3b8;
    margin-top: 0;
}

.stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin: 30px 0;
}

.stat {
    background: #0d1b2a;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    padding: 24px;
}

.stat-label {
    color: #94a3b8;
    font-size: 13px;
}

.stat-value {
    font-size: 28px;
    font-weight: 800;
    margin-top: 8px;
}

.message {
    padding: 15px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.success {
    background: rgba(34,197,94,0.12);
    color: #4ade80;
}

.error {
    background: rgba(239,68,68,0.12);
    color: #f87171;
}

.panel {
    background: #0d1b2a;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    overflow: hidden;
}

.panel-header {
    padding: 22px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

.panel-header h2 {
    margin: 0;
    font-size: 18px;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 16px;
    text-align: left;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    white-space: nowrap;
}

th {
    color: #94a3b8;
    font-size: 12px;
    text-transform: uppercase;
}

td {
    font-size: 13px;
}

.amount-deposit {
    color: #22c55e;
    font-weight: 700;
}

.amount-withdrawal {
    color: #ef4444;
    font-weight: 700;
}

.type {
    display: inline-block;
    padding: 6px 9px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
}

.type.deposit {
    background: rgba(34,197,94,0.10);
    color: #4ade80;
}

.type.withdrawal {
    background: rgba(239,68,68,0.10);
    color: #f87171;
}

.actions {
    display: flex;
    gap: 8px;
}

.actions button {
    border: 0;
    border-radius: 8px;
    padding: 8px 12px;
    color: white;
    cursor: pointer;
    font-weight: 600;
}

.approve {
    background: #16a34a;
}

.decline {
    background: #dc2626;
}

.empty {
    padding: 40px;
    text-align: center;
    color: #64748b;
}

@media(max-width: 700px) {

    header {
        padding: 18px;
    }

    .container {
        width: calc(100% - 24px);
    }

    .stats {
        grid-template-columns: 1fr;
    }

    th,
    td {
        padding: 12px;
    }
}

</style>

</head>

<body>

<header>

    <div class="logo">
        Tradeverse
    </div>

    <div>
        <span class="admin-label">
            Admin
        </span>

        <a href="admin_logout.php" class="logout">
            Log out
        </a>
    </div>

</header>


<div class="container">

    <h1>Admin Dashboard</h1>

    <p class="subtitle">
        Manage users, deposits and withdrawal requests.
    </p>


    <?php if ($message): ?>

        <div class="message <?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>


    <div class="stats">

        <div class="stat">

            <div class="stat-label">
                Registered Users
            </div>

            <div class="stat-value">
                <?php echo number_format($total_users); ?>
            </div>

        </div>


        <div class="stat">

            <div class="stat-label">
                Total User Balances
            </div>

            <div class="stat-value">
                KES <?php echo number_format($total_balance, 2); ?>
            </div>

        </div>

    </div>


    <div class="panel">

        <div class="panel-header">

            <h2>
                Pending Transactions
            </h2>

        </div>


        <?php if ($pending_transactions->num_rows > 0): ?>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php while ($row = $pending_transactions->fetch_assoc()): ?>

                        <tr>

                            <td>
                                #<?php echo $row["id"]; ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["first_name"] . " " . $row["last_name"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["email"]); ?>
                            </td>

                            <td>

                                <span class="type <?php echo $row["type"]; ?>">
                                    <?php echo ucfirst($row["type"]); ?>
                                </span>

                            </td>

                            <td class="<?php echo $row["type"] === "deposit"
                                ? "amount-deposit"
                                : "amount-withdrawal"; ?>">

                                KES <?php echo number_format($row["amount"], 2); ?>

                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["created_at"]); ?>
                            </td>

                            <td>

                                <div class="actions">

                                    <form method="POST">

                                        <input
                                            type="hidden"
                                            name="transaction_id"
                                            value="<?php echo $row["id"]; ?>"
                                        >

                                        <button
                                            type="submit"
                                            name="action"
                                            value="approve"
                                            class="approve"
                                        >
                                            Approve
                                        </button>

                                    </form>


                                    <form method="POST">

                                        <input
                                            type="hidden"
                                            name="transaction_id"
                                            value="<?php echo $row["id"]; ?>"
                                        >

                                        <button
                                            type="submit"
                                            name="action"
                                            value="decline"
                                            class="decline"
                                        >
                                            Decline
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="empty">
                No pending transactions.
            </div>

        <?php endif; ?>

    </div>

</div>

</body>

</html>
