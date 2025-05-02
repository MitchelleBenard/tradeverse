<?php
include 'db_connection.php'; // Include the database connection

// Get user_id from request (make sure you're passing this via GET or POST)
$user_id = $_GET['user_id']; // Assuming you pass the user_id via the query string, e.g., ?user_id=1

// Query to fetch balance from the balance table
$sql = "SELECT balance FROM balance WHERE user_id = '$user_id'"; 

$result = $conn->query($sql);

// Check if the query returned any result
if ($result->num_rows > 0) {
    // Fetch the balance
    $row = $result->fetch_assoc();
    echo json_encode(["balance" => $row['balance']]);
} else {
    echo json_encode(["error" => "No balance found for this user."]);
}

// Close connection
$conn->close();
?>
