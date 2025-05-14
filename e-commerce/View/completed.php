<?php
session_start();
include('../model/config.php');

// Check if the customer is logged in
if (!isset($_SESSION['loggedIn']) || !isset($_SESSION['client_id'])) {
    header("Location: ..controller/log_in.php");
    exit();
}

date_default_timezone_set('Australia/Brisbane');
$invoice_date = date("Y-m-d H:i:s");
$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];
$client_address = $_SESSION['client_address'];
$status = '1';  // Assuming 1 means 'successful'

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize total amount and cart
    $total_amount = 0;
    $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

    // Start a transaction to ensure atomic operations
    $conn->begin_transaction();

    try {
        // Step 1: Check stock availability and calculate total amount
        foreach ($cart as $item) {

            $total_quantity += $item['quantity'];
            // Get the current stock from the database for this product
            $sql_check_stock = "SELECT lpa_stock_onhand FROM lpa_stock WHERE lpa_stock_ID = ?";
            $stmt = $conn->prepare($sql_check_stock);
            $stmt->bind_param("s", $item['code']);
            $stmt->execute();
            $stmt->bind_result($current_stock);
            $stmt->fetch();
            $stmt->close();

            // Check if the quantity in the cart exceeds available stock
            if ($item['quantity'] > $current_stock) {
                // Store error message in session and redirect back to cart page
                $_SESSION['error_message'] = "Insufficient stock for product: " . $item['name'] . ". Only " . $current_stock . " items are available.";
                header("Location: cart.php");
                exit();
            }

            // Calculate the total amount
            $total_amount += $item['price'] * $item['quantity'];
        }

        // Step 2: Save the invoice in the database
        $sql_invoice = "INSERT INTO lpa_invoices (lpa_inv_date, lpa_inv_client_ID, lpa_inv_client_name, lpa_inv_client_address, lpa_inv_amount, lpa_inv_status) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_invoice);
        $stmt->bind_param("ssssds", $invoice_date, $client_id, $client_name, $client_address, $total_amount, $status);
        $stmt->execute();
        $invoice_id = $stmt->insert_id;  // Get the generated invoice ID
        $stmt->close();

        // Step 3: Insert each item in the invoice_items table
        foreach ($cart as $item) {
            // Calculate the total amount for the item
            $item_total_amount = $item['quantity'] * $item['price'];

            // Insert the invoice item into the database
            $sql_invoice_item = "INSERT INTO lpa_invoice_items (lpa_invitem_inv_no, lpa_invitem_stock_ID, lpa_invitem_stock_name, lpa_invitem_qty, lpa_invitem_stock_price, lpa_invitem_stock_amount, lpa_inv_status) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_item = $conn->prepare($sql_invoice_item);
            $stmt_item->bind_param("isssdds", $invoice_id, $item['code'], $item['name'], $item['quantity'], $item['price'], $item_total_amount, $status);
            $stmt_item->execute();
            $stmt_item->close();

            // Step 4: Update the stock in the database (reduce the stock)
            $sql_update_stock = "UPDATE lpa_stock SET lpa_stock_onhand = lpa_stock_onhand - ? WHERE lpa_stock_ID = ?";
            $stmt_update_stock = $conn->prepare($sql_update_stock);
            $stmt_update_stock->bind_param("ds", $item['quantity'], $item['code']);
            $stmt_update_stock->execute();
            $stmt_update_stock->close();
        }

        // Commit the transaction if all queries are successful
        $conn->commit();

        // Clear the cart cookie after the order is completed
        setcookie('cart', '', time() - 3600, '/');  // Expire the cookie

        $_SESSION['invoice_id'] = $invoice_id;  // Store the invoice ID in session

        // Redirect to the success page
        header("Location: completed.php");  
        exit();
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        $_SESSION['error_message'] = 'An error occurred. Please try again later.';
        error_log($e->getMessage());
        header("Location: cart.php");
        exit();
    }
}
?>

<script>localStorage.removeItem('cart');  // Remove the 'cart' item from localStorage</script>
<script src="js/script.js"></script>

<?php
    require 'header.php';
?>
    
    <h2>Payment Successful!</h2>
    <main>
        <p>Your order has been successfully processed. Your order ID is: <?php echo $_SESSION['invoice_id']; ?>.</p>
        <p>Thank you for shopping with us!</p>
        <form action="product.php" method="get">
            <button type="submit">Close</button>
        </form>
    </main>
    
</body>
</html>

<?php
    require 'footer.php';
?>
