<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="income.css">
    <title>Income Tracker</title>
</head>
<body>
    <div class="container">
        <h1>Income Tracker</h1>
        <form action="" method="post" class="income-form">
            <input type="text" name="source" placeholder="Income Source" required>
            <input type="number" name="amount" placeholder="Amount" required>
            <input type="date" name="date" required>
            <button type="submit" name="add_income">Add Income</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Source</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Database connection
                include 'process.php'; // Include your process.php for database connection and operations

                // Handle form submission for adding income
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_income'])) {
                    $source = $conn->real_escape_string($_POST['source']);
                    $amount = $conn->real_escape_string($_POST['amount']);
                    $date = $conn->real_escape_string($_POST['date']);
                    
                    $sql = "INSERT INTO income (source, amount, date) VALUES ('$source', '$amount', '$date')";
                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Income added successfully!');</script>";
                    } else {
                        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
                    }
                }

                // Handle delete request
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_income'])) {
                    $id = $conn->real_escape_string($_POST['id']);
                    $delete_sql = "DELETE FROM income WHERE id = '$id'";
                    if ($conn->query($delete_sql) === TRUE) {
                        echo "<script>alert('Record deleted successfully!');</script>";
                    } else {
                        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
                    }
                }

                // Fetch income records
                $sql = "SELECT * FROM income ORDER BY id DESC";
                $result = $conn->query($sql);
                
                // Calculate total income
                $totalIncome = 0;

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $totalIncome += $row['amount'];
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['source']}</td>
                                <td>{$row['amount']}</td>
                                <td>{$row['date']}</td>
                                <td>
                                    <form action='' method='post' style='display:inline;'>
                                        <input type='hidden' name='id' value='{$row['id']}'>
                                        <button type='submit' name='delete_income' class='delete-button' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No income records found.</td></tr>";
                }

                // Display total income
                echo "<tr>
                        <td colspan='3' style='text-align:right;'><strong>Total Income:</strong></td>
                        <td><strong>" . number_format($totalIncome, 2) . "</strong></td>
                      </tr>";
                ?>
            </tbody>
        </table>
        <button onclick="window.location.href='../main/admin.php'" class="button">Go to Admin Dashboard</button>
    </div>
</body>
</html>

<?php
// Close the connection after all operations are complete
$conn->close();
?>
