
<?php
    session_start(); 
    include '../model/config.php';
    //Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['url']) && isset($_POST['description']) && isset($_POST['quantity']) && isset($_POST['price'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $url = $_POST['url'];
        $description = $_POST['description'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];

        // Update SQL query
        $sql = "UPDATE lpa_stock SET lpa_stock_name = ?, lpa_stock_picture = ?, lpa_stock_desc = ?, lpa_stock_onhand = ?, lpa_stock_price = ? WHERE lpa_stock_ID = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssidi", $name, $url, $description, $quantity, $price, $id);
            if ($stmt->execute()) {
                echo "Product updated successfully!";
            } else {
                echo "Error updating product.";
            }
            $stmt->close();
        }

        $conn->close();
    } else {
        echo "Invalid data!";
    }

    //Delette
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = $_POST['id']; // Get product ID from POST request

        // Prepare DELETE query
        $sql = "DELETE FROM lpa_stock WHERE lpa_stock_ID = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id); // Bind the ID as an integer
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Product deleted successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error deleting product"]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Error preparing SQL query"]);
        }

        $conn->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request"]);
    }

    //Insert
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data (which is the stock data in JSON format)
        $stockData = isset($_POST['stockData']) ? $_POST['stockData'] : null;

        // If stockData is not provided or is empty
        if (!$stockData) {
            echo "No data received!";
            exit;
        }
        
        // Decode the JSON string into a PHP array
        $products = json_decode($stockData, true);

        // If decoding fails, handle the error
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Invalid JSON data!";
            exit;
        }

        // Prepare the SQL insert statement
        $stmt = $conn->prepare("INSERT INTO lpa_stock (lpa_stock_name, lpa_stock_picture, lpa_stock_desc, lpa_stock_onhand, lpa_stock_price, lpa_stock_status) VALUES (?, ?, ?, ?, ?, ?)");
        
        // Loop through the products array and insert each product into the database
        foreach ($products as $product) {
            $name = $product['name'];
            $url = $product['url'];
            $description = $product['description'];
            $quantity = $product['quantity'];
            $price = $product['price'];
            $status = '1';

            // Bind parameters to the SQL statement
            $stmt->bind_param("sssidi", $name, $url, $description, $quantity, $price, $status);

            // Execute the statement
            if (!$stmt->execute()) {
                echo "Error inserting product: " . $stmt->error;
                exit;
            }
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();

        // Return a success message
        echo "Data uploaded successfully!";
    } else {
        echo "Invalid request method.";
    }
} 
?>

<?php
    require 'header.php';
?>

<h1>Stock Management System</h1>
<!--Table1-->
<div class="container">
    <h4>Add New Product</h4>
    <form id="addProductForm">
        <!--<label for="productName">Name:</label>-->
        <input type="text" id="productName" name="productName" placeholder="Product Name" required>

        <!--<label for="productPicture">URL:</label>-->
        <input type="text" id="productPicture" name="productPicture" placeholder="Product URL" required>

        <!--<label for="productDescription">Description:</label>-->
        <input type="text" id="productDescription" name="productDescription" placeholder="Product Description" required>

        <!--<label for="productQuantity">Quantity:</label>-->
        <input type="number" id="productQuantity" name="productQuantity" placeholder="Quantity" required>

        <!--<label for="productPrice">Price:</label>-->
        <input type="number" id="productPrice" name="productPrice" placeholder="Price" required>

        <button type="button" onclick="addProductInStock()">Add Product</button>
        <button type="button" id="submitButton" onclick="uploadDataInStock()">Upload Data</button>
    </form>

    <table id="stockTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Product Name</th>
                <th>Picture URL</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price ($)</th>
                <th>Total ($)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Stock items will appear here -->
        </tbody>
    </table>
</div>


<!--Table2-->
<div class="container_1">
    <h4>Fix The Product</h4>
    <form id="UpdateProductForm">
        <label for="productName">Name:</label>
        <input type="text" id="search-input" name="productName" placeholder="Product Name" required>

        <button type="button" onclick="filterProducts()">Find Product</button>
    </form>

    <table id="stockTable_1">
        <thead>
            <tr>
                <th>No</th>
                <th>Product Name</th>
                <th>Picture URL</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price ($)</th>
                <th>Total ($)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
            include '../model/config.php';
            $sql = "SELECT * FROM lpa_stock";  // Adjust this query to match your table and columns
            $result = $conn->query($sql);
            
            $stock_items = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $stock_items[] = $row;
                }
            }    
            $conn->close();

            // Loop through the stock items and display them
            foreach ($stock_items as $index => $item) {
                $total = $item['lpa_stock_onhand'] * $item['lpa_stock_price']; // Calculate the total
                echo "<tr class='product' data-id='" . $item["lpa_stock_ID"] . "'>";  // Added class 'product' to each row
                echo "<td>" . htmlspecialchars($item["lpa_stock_ID"]) . "</td>";
                echo "<td class='product-name'>" . htmlspecialchars($item["lpa_stock_name"]) . "</td>";
                echo "<td class='product-url'>" . htmlspecialchars($item["lpa_stock_picture"]) . "</td>";
                echo "<td class='product-desc'>" . htmlspecialchars($item["lpa_stock_desc"]) . "</td>";
                echo "<td class='product-quantity'>" . htmlspecialchars($item["lpa_stock_onhand"]) . "</td>";
                echo "<td class='product-price'>" . number_format($item["lpa_stock_price"], 2) . "</td>";
                echo "<td class='product-total'>" . number_format($total, 2) . "</td>";
                echo "<td>
                        <button class='update-btn' onclick='updateProductInUI(this)'>Update</button>
                        <button class='delete-btn' data-id='" . $item["lpa_stock_ID"] . "' onclick='deleteProductInUI(this)'>Delete</button>
                        </td>";
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>

</div>
<!-- JavaScript for actions -->
<script src="script_stock.js"></script>

</body>
</html>

<?php
    require 'footer.php';
?>