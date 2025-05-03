<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "user_system";

$conn = new mysqli($host, $user, $pass, $db);

// Handle approval or rejection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'], $_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        // Approve and deduct balance
        $result = $conn->query("SELECT id_number, amount FROM transactions WHERE id = $transaction_id AND status = 'pending'");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_number = $row['id_number'];
            $amount = $row['amount'];

            // Begin transaction
            $conn->begin_transaction();
            try {
                $conn->query("UPDATE transactions SET status = 'approved' WHERE id = $transaction_id");
                $conn->query("UPDATE users SET balance = balance - $amount WHERE id_number = '$id_number'");
                $conn->commit();
                $message = "Withdrawal approved.";
            } catch (Exception $e) {
                $conn->rollback();
                $message = "Error approving withdrawal.";
            }
        }
    } elseif ($action === 'decline') {
        $conn->query("UPDATE transactions SET status = 'declined' WHERE id = $transaction_id");
        $message = "Withdrawal declined.";
    }
}

// Fetch all pending withdrawals
$withdrawals = $conn->query("SELECT t.id, u.id_number, u.email, t.amount, t.created_at 
                             FROM transactions t 
                             JOIN users u ON t.id_number = u.id_number 
                             WHERE t.type = 'withdrawal' AND t.status = 'pending'
                             ORDER BY t.created_at DESC");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Admin Dashboard - Withdrawal Requests</h1>

        <?php if (isset($message)) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <!-- Add Logout button -->
            <div class="flex justify-end mb-4">
                <form method="POST" action="admin_logout.php">
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
                </form>
            </div>

            <?php if ($withdrawals->num_rows > 0): ?>
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="px-4 py-2">Transaction ID</th>
                            <th class="px-4 py-2">User ID</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Requested At</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $withdrawals->fetch_assoc()): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?php echo $row['id']; ?></td>
                            <td class="px-4 py-2"><?php echo $row['id_number']; ?></td>
                            <td class="px-4 py-2"><?php echo $row['email']; ?></td>
                            <td class="px-4 py-2 text-red-600 font-bold">KSh <?php echo number_format($row['amount'], 2); ?></td>
                            <td class="px-4 py-2"><?php echo $row['created_at']; ?></td>
                            <td class="px-4 py-2">
                                <form method="post" class="flex gap-2">
                                    <input type="hidden" name="transaction_id" value="<?php echo $row['id']; ?>">
                                    <button name="action" value="approve" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Approve</button>
                                    <button name="action" value="decline" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Decline</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-700">No pending withdrawal requests.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
