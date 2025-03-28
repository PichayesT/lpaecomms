<?php
    include('../model/config.php');

    // Get the search query from the request
    $query = isset($_GET['query']) ? $_GET['query'] : '';

    // SQL query to fetch names based on search query
    $sql = "SELECT lpa_stock_ID, lpa_stock_name, lpa_stock_onhand, lpa_stock_price
            FROM lpa_stock
            WHERE lpa_stock_name LIKE ? 
            LIMIT 10";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $searchQuery = '%' . $query . '%';
    $stmt->bind_param("s", $searchQuery); // "s" stands for string

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all names matching the query
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'productID' => $row['lpa_stock_ID'],                // Include ID
            'productName' => $row['lpa_stock_name'],            // Product name
            'productOnHand' => $row['lpa_stock_onhand'],        // Stock on hand
            'productPrice' => $row['lpa_stock_price'],          // Product price
        ];
    }

    // Return the names as a JSON response
    echo json_encode($products);

    // Close the database connection
    $stmt->close();
    $conn->close();
?>
