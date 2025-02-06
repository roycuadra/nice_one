<?php
// Include the query execution file
$data = include 'query.php';

// Ensure $data is an array or initialize it as an empty array
if (!is_array($data)) {
    $data = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Orders Report</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
</head>
<body>
    <h1>Client Orders Report</h1>

    <!-- Summary div list for order, total revenue, client -->
    <div class="summary-container">
        <div class="summary-item">
            <h2>Orders</h2>
            <p><?php echo count($data); ?></p> <!-- Example dynamic value for number of orders -->
        </div>
        <div class="summary-item">
            <h2>Total Revenue</h2>
            <p>
                <?php 
                $totalRevenue = 0;
                foreach ($data as $row) {
                    $totalRevenue += (float)$row["total"];
                }
                echo '₱' . number_format($totalRevenue, 2);
                ?>
            </p>
        </div>
        <div class="summary-item">
            <h2>Clients</h2>
            <p>
                <?php 
                $clients = array_unique(array_column($data, 'client_id'));
                echo count($clients);
                ?>
            </p>
        </div>
    </div>

    <!-- Table for data display -->
    <?php
    if (count($data) > 0) {
        echo '<table>
                <thead>
                    <tr>
                        <th>Client ID</th>
                        <th>Full Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Cash</th>
                        <th>Change</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($data as $row) {
            $total = (float)$row["total"];
            $cash = (float)$row["cash"];
            $change = $cash - $total;
            
            echo '<tr>
                    <td>' . htmlspecialchars($row["client_id"]) . '</td>
                    <td>' . htmlspecialchars($row["full_name"]) . '</td>
                    <td>' . htmlspecialchars($row["contact"]) . '</td>
                    <td>' . htmlspecialchars($row["address"]) . '</td>
                    <td>' . htmlspecialchars($row["order_id"]) . '</td>
                    <td>' . htmlspecialchars($row["product_name"]) . '</td>
                    <td>' . htmlspecialchars('₱' . number_format((float)$row["price"], 2)) . '</td>
                    <td>' . htmlspecialchars($row["quantity"]) . '</td>
                    <td>' . htmlspecialchars('₱' . number_format($total, 2)) . '</td>
                    <td>' . htmlspecialchars('₱' . number_format($cash, 2)) . '</td>
                    <td>' . htmlspecialchars('₱' . number_format($change, 2)) . '</td>
                  </tr>';
        }
        
        echo '</tbody>
            </table>';
    } else {
        echo '<p class="no-results">No results found.</p>';
    }
    ?>

    <!-- Canvas element for the chart -->
    <div class="chart-container" style="width: 80%; margin: auto;">
        <canvas id="revenueChart"></canvas>
    </div>

    <script>
        // Prepare data for the chart
        const labels = <?php echo json_encode(array_column($data, 'product_name')); ?>;
        const totalAmounts = <?php echo json_encode(array_column($data, 'total')); ?>;

        // Chart.js script to display the graph
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'bar', // Type of chart (e.g., bar, line, pie)
            data: {
                labels: labels, // Product names
                datasets: [{
                    label: 'Total Revenue per Product',
                    data: totalAmounts, // Total values for each product
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Bar color
                    borderColor: 'rgba(75, 192, 192, 1)', // Border color
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
        