<?php
// Create connection
$conn = new mysqli('localhost','root', '', 'lpa_ecomms');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
// Database connection settings
$host = 'localhost'; // or the host where your database is located
$dbname = 'lpa_ecomms'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

try {
    // Create a new PDO instance and set attributes
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error mode for exceptions
} catch (PDOException $e) {
    // Handle connection error
    die("Connection failed: " . $e->getMessage());
}
?>
