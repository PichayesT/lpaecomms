<?php
    require 'header.php';
?>

<h1>Sales and Invoicing System</h1>
<!--Table1-->
<div class="container">
    <h4>Add New Sales and Invoicing</h4>
    <!--<form id="addProductForm">-->
    <form action="salesandinvoicing.php" method="POST">
        <label for="items">Client name:</label>
        <select name="item" id="items">
            <?PHP
                include 'config.php';
                $query = "SELECT lpa_client_firstname, lpa_client_lastname FROM lpa_clients";  // Assuming you have an 'items' table
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("i", $selected_item_id);  // Bind the ID to the query
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if there are any items in the database
                if ($result->num_rows > 0) {
                    // Loop through each item and create an option for the select dropdown
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['lpa_client_firstname'] . "'>" . $row['lpa_client_lastname'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No items available</option>";
                }

                // Close the database connection
                $mysqli->close();
            ?>
        <!--<label for="productName">Name:</label>
        <input type="text" id="productName" name="productName" placeholder="Product Name" required>

        <label for="productPicture">URL:</label>
        <input type="text" id="productPicture" name="productPicture" placeholder="Product URL" required>

        <label for="productDescription">Description:</label>
        <input type="text" id="productDescription" name="productDescription" placeholder="Product Description" required>

        <label for="productQuantity">Quantity:</label>
        <input type="number" id="productQuantity" name="productQuantity" placeholder="Quantity" required>

        <label for="productPrice">Price:</label>
        <input type="number" id="productPrice" name="productPrice" placeholder="Price" required>

        <button type="button" onclick="addProductInStock()">Add Product</button>
        <button type="button" id="submitButton" onclick="uploadDataInStock()">Upload Data</button>-->
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