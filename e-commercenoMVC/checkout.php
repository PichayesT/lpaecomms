<?php
session_start();

// Set default values for session variables if not set
if (!isset($_SESSION['loggedIn'])) {
    $_SESSION['loggedIn'] = false;
}

if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = '';
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulate login process (replace with real login logic)
    $_SESSION['loggedIn'] = true;
    $_SESSION['username'] = $user['lpa_user_username'];
    $_SESSION['user_id'] = $user['lpa_user_id'];
}

// Include your DB connection file
include('config.php');  // Ensure this is correct

// Check if the PDO connection is established
if (!$pdo) {
    die('Database connection failed.');
}

// Function to redirect to login page
function redirectToLogin() {
    header("Location: log_in.php");
    exit();
}

// Check if the customer is logged in
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== '') {
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT c.lpa_client_address, c.lpa_client_phone, c.lpa_client_firstname, c.lpa_client_lastname, c.lpa_client_ID
            FROM lpa_clients c
            INNER JOIN lpa_users u 
            ON c.lpa_client_firstname = u.lpa_user_firstname 
            AND c.lpa_client_lastname = u.lpa_user_lastname
            AND c.lpa_client_ID = u.lpa_user_ID
            WHERE u.lpa_user_id = :user_id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $customer_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer_data) {
            $address = $customer_data['lpa_client_address'];
            $phone = $customer_data['lpa_client_phone'];
            $first_name = $customer_data['lpa_client_firstname'];
            $last_name = $customer_data['lpa_client_lastname'];
            $ID = $customer_data['lpa_client_ID'];
            $_SESSION['client_id'] = $ID;
            $_SESSION['client_name'] = $first_name . ' ' . $last_name;
            $_SESSION['client_address'] = $address;
        } else {
            redirectToLogin();
        }
    } catch (PDOException $e) {
        echo 'Query Error: ' . $e->getMessage();
    }
} else {
    redirectToLogin();
}
?>



<?php
    require 'header.php';
?>
    
    <!-- Main Content Area -->
        <h2>Enter Your Payment Details</h2>
    <main>
        <!--<form id="payment-form">-->
        <form action="completed.php" method="POST">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($first_name)?>" required>
            

            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($last_name)?>" required>

            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address)?>" required>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value= "<?php echo htmlspecialchars($phone)?>" required>

            <label for="payment-option">Payment Option</label>
            <select id="payment-option">
                <option value="PayPal">PayPal</option>
                <option value="VISA">VISA</option>
                <option value="MasterCard">MasterCard</option>
                <option value="Direct Deposit">Direct Deposit</option>
            </select>

            <button type="submit" onclick="payNow()">Pay Now</button>
            <button type="button" onclick="window.location.href='cart.php';">Cancel</button>
        </form>
    </main>

    <!-- Footer -->
</body>
</html>

<?php
    require 'footer.php';
?>