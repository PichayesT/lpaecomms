<?php
    include('../model/config.php');

    // Get the search query from the request
    $query = isset($_GET['query']) ? $_GET['query'] : '';

    // SQL query to fetch names based on search query
    $sql = "SELECT lpa_client_ID, lpa_client_firstname, lpa_client_lastname, lpa_client_address 
            FROM lpa_clients 
            WHERE lpa_client_firstname LIKE ? 
            LIMIT 10";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $searchQuery = '%' . $query . '%';
    $stmt->bind_param("s", $searchQuery); // "s" stands for string

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all names matching the query
    $names = [];
    while ($row = $result->fetch_assoc()) {
        $names[] = [
            'firstName' => $row['lpa_client_firstname'], // Use first name
            'lastName' => $row['lpa_client_lastname'],   // Use last name
            'address' => $row['lpa_client_address'],     // Use address
            'id' => $row['lpa_client_ID']                // Include ID
        ];
    }

    // Return the names as a JSON response
    echo json_encode($names);

    // Close the database connection
    $stmt->close();
    $conn->close();
?>

