<?php
// delete.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'income_tracker';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Sanitize the ID
    $query = "DELETE FROM income WHERE id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
        // Redirect back to the income tracker page after deletion
        header("Location: index.php?message=Record deleted successfully");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
