<?php
session_start(); 
include '../model/config.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // insert
    if (isset($_POST['stockData'])) {
        // Get the raw POST data (which is the stock data in JSON format)
        $stockData = $_POST['stockData'];
    
        // If stockData is not provided or is empty
        if (empty($_POST['stockData'])) {
            echo json_encode(['error' => 'No data received!']);
            exit;
        }
    
        // Decode the JSON string into a PHP array
        $users = json_decode($stockData, true);
    
        // If decoding fails, handle the error
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['error' => 'Invalid JSON data!']);
            exit;
        }
    
        // Start the transaction
        $conn->begin_transaction();
    
        // Insert invoice into the database
        date_default_timezone_set('Australia/Brisbane');
        $invoice_date = date("Y-m-d H:i:s");
    
        try {
            // Insert the invoice into the lpa_invoices table
            $stmt_invoice = $conn->prepare("INSERT INTO lpa_invoices (lpa_inv_date, lpa_inv_client_ID, lpa_inv_client_name, lpa_inv_client_address, lpa_inv_amount, lpa_inv_status) VALUES (?, ?, ?, ?, ?, ?)");
        
            // Total amount to be calculated for all products in the stock data
            $totalAmount = 0;
            $customerID = null;
            $customerName = null;
            $customerAddress = null;
        
            // Loop through each product to calculate total amount and extract customer details
            foreach ($users as $product) {
                // Accumulate the total amount for the invoice
                $totalAmount += $product['productAmount'] * $product['productPrice'];
        
                // Get the customer information (assuming the same for all products in the invoice)
                $customerID = $product['customerID'];
                $customerName = $product['customerfullName'];
                $customerAddress = $product['customerAddress'];
            }
        
            // Set values for the invoice
            $lpa_inv_date = $invoice_date; // The date when the invoice is created
            $lpa_inv_status = "1";  // Default status
            $lpa_inv_amount = $totalAmount;  // The total amount for the invoice
        
            // Insert into the lpa_invoices table (only once for the invoice)
            $stmt_invoice->bind_param("sissds", $lpa_inv_date, $customerID, $customerName, $customerAddress, $lpa_inv_amount, $lpa_inv_status);
            $stmt_invoice->execute();
        
            // Get the last inserted invoice ID
            $lpa_inv_no = $conn->insert_id;
        
            // Now, insert the invoice items into the lpa_invoice_items table for each product
            $stmt_product = $conn->prepare("INSERT INTO lpa_invoice_items (lpa_invitem_inv_no, lpa_invitem_stock_ID, lpa_invitem_stock_name, lpa_invitem_qty, lpa_invitem_stock_price, lpa_invitem_stock_amount, lpa_inv_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
            foreach ($users as $product) {
                $lpa_invitem_stock_ID = $product['productID'];
                $lpa_invitem_stock_name = $product['productName'];
                $lpa_invitem_qty = $product['productAmount'];
                $lpa_invitem_stock_price = $product['productPrice'];
                $lpa_invitem_stock_amount = $product['productAmount'] * $product['productPrice'];  // Total amount for the product
                $lpa_invitem_status = "Pending";  // Default status
        
                // Execute insert into the lpa_invoice_items table for each product
                $stmt_product->bind_param("iisddds", $lpa_inv_no, $lpa_invitem_stock_ID, $lpa_invitem_stock_name, $lpa_invitem_qty, $lpa_invitem_stock_price, $lpa_invitem_stock_amount, $lpa_invitem_status);
                $stmt_product->execute();
            }
        
            // If everything is okay, commit the transaction
            $conn->commit();
        
            // Send a success response
            echo json_encode(['success' => 'Invoice and products uploaded successfully.']);
            exit;
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
        
        // Close the prepared statements and the database connection
        $stmt_invoice->close();
        $stmt_product->close();
        $conn->close();
    }

    // Delete
    if (isset($_POST['id'])) {
        $id = $_POST['id']; // Get the ID from POST request

        // Check if ID is valid (numeric and non-empty)
        if (empty($id) || !is_numeric($id)) {
            echo json_encode(["error" => "Invalid ID provided."]);
            exit;
        }

        // Start the transaction
        $conn->begin_transaction();

        try {
            // Delete from lpa_invoices
            $stmtInvoice = $conn->prepare("DELETE FROM lpa_invoices WHERE lpa_inv_no = ?");
            $stmtInvoice->bind_param("i", $id);
            $stmtInvoice->execute();

            // Delete from lpa_invoice_items
            $stmtItems = $conn->prepare("DELETE FROM lpa_invoice_items WHERE lpa_invitem_inv_no = ?");
            $stmtItems->bind_param("i", $id);
            $stmtItems->execute();

            // Commit the transaction
            $conn->commit();

            echo json_encode(["success" => "Invoice deleted successfully."]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(["error" => $e->getMessage()]);
        }

        $stmtInvoice->close();
        $stmtItems->close();
        $conn->close();
        exit;
    }
}

?>

<?php
    require 'header.php';
?>


<h1>Invoice System</h1>
<!-- Table1 -->
<div class="container">
   <!--<h4>Add New Invoice Name</h4>-->
<form id="addProductForm">
    <!-- Customer Section -->
    <fieldset>
        <legend>Customer Information</legend>
        <div class="dropdown-container">
            <!-- Searchable input for customer -->
            <input type="text" id="nameSearchInput" class="dropdown-input" placeholder="Search for a name...">
            
            <!-- List of options, dynamically populated -->
            <div id="dropdownList" class="dropdown-list"></div>
        </div>

        <input type="text" id="customerID" name="customerID" placeholder="ID" readonly>
        <input type="text" id="firstName" name="firstName" placeholder="First Name" readonly>
        <input type="text" id="lastName" name="lastName" placeholder="Last Name" readonly>
        <input type="text" id="address" name="address" placeholder="Address" readonly>
    </fieldset>

    <br>

    <!-- Product Section -->
    <!--<h4>Add New Invoice Products</h4>-->
    <fieldset>
        <legend>Product Information</legend>
        <div class="dropdown-container">
            <!-- Searchable input for product -->
            <input type="text" id="nameSearchInput1" class="dropdown-input1" placeholder="Search for a product...">
            
            <!-- List of options, dynamically populated -->
            <div id="dropdownList1" class="dropdown-list1"></div>
        </div>

        <input type="text" id="productID" name="productID" placeholder="Product ID" readonly>
        <input type="text" id="productName" name="productName" placeholder="Product Name" readonly>
        <input type="number" id="productOnHand" name="productOnHand" placeholder="Product OnHand" readonly>
        <input type="text" id="productPrice" name="productPrice" placeholder="Product Price" readonly>
        <input type="number" id="productAmount" name="productAmount" placeholder="Product Amount" required>

        <button type="button" id="addProductButton">Add Product</button>
        <button type="button" id="submitButton" onclick="uploadDataInStock()">Upload Data</button>
    </fieldset>
</form>



    <table id="stockTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Customer Address</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Amount</th>
                <th>Price ($)</th>
                <th>Total ($)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Stock items will appear here -->
        </tbody>
    </table>
    <br></br>
    <tfoot>
        <tr>
           <!--<td id="totalAmount">$0.00</td>--> <!-- This is where the total amount will appear -->
           <p>Total: $<span id="totalAmount">0</span></p>
       </tr>
   </tfoot>
    </tfoot>
</div>


<!--Table2-->
<div class="container_1">
    <h4>Fix The Product</h4>
    <form id="UpdateProductForm">
        <input type="text" id="search-input" name="invoiceName" placeholder="Invoice number, name, client ID..." required>
        <button type="button" onclick="filterInvoices()">Find Invoices</button>
    </form>

    <table id="stockTable_1">
        <thead>
            <tr>

                <th>Invoice No</th>
                <th>Date</th>
                <th>Client ID</th>
                <th>Client Name</th>
                <th>Client Address</th>
                <th>Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
            include '../model/config.php';
            $sql = "SELECT * FROM lpa_invoices";  // Adjust this query to match your table and columns
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
                echo "<tr class='invoice' data-id='" . $item["lpa_inv_no"] . "'>";  // Added class 'product' to each row
                echo "<td>" . number_format($item["lpa_inv_no"]) . "</td>";
                echo "<td class='invpice-date'>" . date("F j, Y g:i A", strtotime($item["lpa_inv_date"])) . "</td>";  // Added missing semicolon here
                echo "<td class='invoice-clientID'>" . number_format($item["lpa_inv_client_ID"]) . "</td>";
                echo "<td class='invoice-name'>" . htmlspecialchars($item["lpa_inv_client_name"]) . "</td>";
                echo "<td class='invoice-address'>" . htmlspecialchars($item["lpa_inv_client_address"]) . "</td>";
                echo "<td class='invoice-amount'>" . number_format($item["lpa_inv_amount"], 2) . "</td>";
                echo "<td>
                        <button class='update-btn' onclick='openInvoiceDetails(" . $item["lpa_inv_no"] . ")'>Check Detail</button>
                        <button class='delete-btn' data-id='" . $item["lpa_inv_no"] . "' onclick='deleteProductInUI(this)'>Delete</button>
                      </td>";
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>

</div>

<!-- JavaScript for actions -->
<script src="script_invoice.js"></script>

</body>
</html>

<?php
    require 'footer.php';
?>