<?php
session_start();

// Set default values for session variables if not set
if (!isset($_SESSION['loggedIn'])) {
    $_SESSION['loggedIn'] = false; // Default to not logged in
}

if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = ''; // Default to empty username
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = ''; // Default to empty user ID
}

// Simulate login process (you can replace this with real authentication logic)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    // Assume successful login (replace with actual authentication logic)
    $_SESSION['loggedIn'] = true;
    $_SESSION['username'] = htmlspecialchars($_POST['username']);  // Sanitize input
    $_SESSION['user_id'] = 1;  // Example user ID (replace with actual user ID)
}

?>

<?php
    require 'header.php';
?>
        
    <!-- Display session messages (if any) -->
    <?php
    // Check and display any session messages
    if (isset($_SESSION['error_message'])) {
        echo "<div class='alert error'>{$_SESSION['error_message']}</div>";
        unset($_SESSION['error_message']);
    }

    if (isset($_SESSION['success_message'])) {
        echo "<div class='alert success'>{$_SESSION['success_message']}</div>";
        unset($_SESSION['success_message']);
    }
    ?>
    <!-- Main Content Area -->
    <main>
        <h2>Items in Your Cart</h2>

        <table id="cart-table">
            <thead>
                <tr>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody id="cart-items">
                <!-- Cart items will be dynamically populated here -->
            </tbody>
        </table>

        <div>
            <p>Total: $<span id="total-price">0</span></p>
            <button onclick="proceedToCheckout()">Proceed to Checkout</button>
            <!--<button onclick="window.location.href='checkout.php';">Proceed to Checkout</button>-->
        </div>
    </main>
    <script src="js/script.js"></script>
    <!-- Footer -->
</body>
</html>

<?php
 require 'footer.php';
?>