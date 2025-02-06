<?php
// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "";     // Replace with your database password
$dbname = "act_db"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to perform an inner join between client and orders tables
$sql = "SELECT client.client_id, client.full_name, client.contact, client.address, 
               orders.order_id, orders.product_name, orders.price, orders.quantity 
        FROM client 
        INNER JOIN orders ON client.client_id = orders.client_id";

$result = $conn->query($sql);

// Return result for use in other scripts   
return $result;
?>
