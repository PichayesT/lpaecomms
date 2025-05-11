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
        $_SESSION['user_id'] = ''; // Default to empty user ID if not set
    }

    // Simulate login process (you can replace this with real authentication logic)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Assume successful login
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $user['lpa_user_username'];  // Assuming $user is the authenticated user data
        $_SESSION['user_id'] = $user['lpa_user_id'];  // Assuming you set the user ID here during login
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
    
    <!-- Search Section -->
    <section id="search-section">
        <input type="text" id="search-input" placeholder="Search products..." onkeyup="filterProducts()">
    </section>

    <!-- Page Body (Product Listings) -->
    <!--<section id="product-listing">-->
    <!-- Sample Products -->
    <main>
        <div class="include_product">
            <?php
                // Include the database connection
                include('../model/config.php');

                // Fetch the products from the database
                $sql = "SELECT * FROM lpa_stock WHERE lpa_stock_status = '1'";  // Adjust this query to match your table and columns
                
                $start_time = microtime(true); // Add
                $result = $conn->query($sql);
                $end_time = microtime(true); // Add
                $execution_time = $end_time - $start_time; //Add
                error_log("Product fetch execution time: $execution_time seconds"); // Add

                if ($result->num_rows > 0) {
                    // Output each product
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="product" data-product-id="' . $row["lpa_stock_ID"] . '">
                                <h2 class="product-name">' . $row["lpa_stock_name"] . '</h2>
                                <img src="../images/' . $row['lpa_stock_picture'] . '" width="200" height="200">
                                <p class="product-description">' . $row["lpa_stock_desc"] . '</p>
                                <p class="product-quantity">Quantity: ' . $row["lpa_stock_onhand"] . '</p>
                                <p class="product-price">$' . $row["lpa_stock_price"] . '</p>
                                <button class="add-to-cart" onclick="addToCart(\'' . $row["lpa_stock_ID"] . '\', \'' . $row["lpa_stock_name"] . '\', ' . $row["lpa_stock_price"] . ', ' . $row["lpa_stock_onhand"] . ')">Add to Cart</button>
                            </div>';
                    }
                } else {
                    echo "0 results found";
                }
                $conn->close();
            ?>       
        </div>
    </main>
    
    <!--</section>-->
    <!-- Page Footer -->

    <script src="js/script.js"></script>
</body>
</html>

<?php
    require 'footer.php';
?>