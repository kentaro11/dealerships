<?php
session_start();
require '../db.php'; // Include your database connection file

// Check if user is logged in and is staff from branch 1
if (!isset($_SESSION['staff_user_id']) || $_SESSION['staff_role'] !== 'staff' || $_SESSION['staff_branch_id'] !== 1) {

}




// Function to log activities
function log_activity($db, $activity_type, $details) {
    $query = $db->prepare("INSERT INTO activity_log (activity_type, details) VALUES (?, ?)");
    $query->execute([$activity_type, $details]);
}

// Fetch all receipts
$receipts = $db->query("SELECT r.id, r.receipt_details, r.created_at, s.total_price, p.product_name 
                        FROM receipts r
                        JOIN sales s ON r.sale_id = s.id
                        JOIN products p ON s.product_id = p.id
                        ORDER BY r.created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

    <title>Receipts</title>
    <style>
        body {
            background-color: white; /* Light grey background */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: black;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }
        th {
            background-color: #e0e0e0; /* Slightly darker grey for header */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Zebra striping for better readability */
        }
        .print-button, .delete-button {
            margin-left: 10px;
            padding: 5px 10px;
            cursor: pointer;
            border: none;
            color: white;
            border-radius: 4px; /* Rounded corners */
        }
        .print-button {
            background-color: #4CAF50; /* Green */
        }
        .delete-button {
            background-color: #f44336; /* Red */
        }
        .go-back-button {
            padding: 10px 15px;
            background-color: #007BFF; /* Blue */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none; /* Remove underline from link */
        }
        .go-back-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .go-back-button {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            background-color: #5cb85c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .go-back-button i {
            margin-right: 8px; /* Space between the icon and text */
        }

        .go-back-button:hover {
            background-color: #4cae4c;
        }
    </style>
    <script>
        function printReceipt(receiptId) {
            var printWindow = window.open('print_receipt.php?id=' + receiptId, '_blank');
            printWindow.onload = function() {
                printWindow.print();
            };
        }
    </script>
</head>
<body>
    <div><br><br>

    <a href="dashboard.php" class="go-back-button">
        <i class="fas fa-arrow-left"></i> Go Back
    </a></div>

    <h1>Receipts</h1>
    <table>
        <tr>
            <th>Receipt ID</th>
            <th>Product</th>
            <th>Receipt Details</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php foreach ($receipts as $receipt): ?>
        <tr>
            <td><?php echo htmlspecialchars($receipt['id']); ?></td>
            <td><?php echo htmlspecialchars($receipt['product_name']); ?></td>
            <td><?php echo htmlspecialchars($receipt['receipt_details']); ?></td>
            <td><?php echo htmlspecialchars($receipt['created_at']); ?></td>
            <td>
                <button class="print-button" onclick="printReceipt(<?php echo $receipt['id']; ?>)">Print</button>
               
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- <br>------------------------------------------<br> -->

</body>
</html>
