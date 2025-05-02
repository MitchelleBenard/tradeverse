<?php
include 'db_connection.php'; // Include the database connection

// Get user_id and new balance from request (make sure you're passing these via POST)
$user_id = $_POST['user_id']; // Assuming user_id is sent via POST
$new_balance = $_POST['new_balance']; // The new balance to be set

// Query to update the balance
$sql = "UPDATE balance SET balance = '$new_balance' WHERE user_id = '$user_id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "Balance updated successfully."]);
} else {
    echo json_encode(["error" => "Error updating balance: " . $conn->error]);
}

// Close connection
$conn->close();
?>
