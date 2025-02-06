<?php
// Include the database configuration file
include 'db_config.php'; 

// SQL query to join client, orders, and payment tables
$sql = "SELECT client.client_id, client.full_name, client.contact, client.address, 
               orders.order_id, orders.product_name, orders.price, orders.quantity,
               payment.payment_id, payment.total, payment.cash, (payment.cash - payment.total) AS 'change'
        FROM client 
        INNER JOIN orders ON client.client_id = orders.client_id
        INNER JOIN payment ON orders.order_id = payment.order_id";

// Execute the query
$result = $conn->query($sql);

// Check if the query executed successfully
if (!$result) {
    die("Query failed: " . $conn->error); // Exit script and show error if query fails
}

// Initialize an array to hold the data
$data = [];

// Fetch rows and store them in the $data array
while ($row = $result->fetch_assoc()) {
    $data[] = $row;  // Store each row of the result as an associative array
}

// Free the result set after processing
$result->free();

// Close the database connection
$conn->close();

// Return the data (array) to be used elsewhere
return $data;
?>
