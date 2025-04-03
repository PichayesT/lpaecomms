
<?php
session_start(); 
include '../model/config.php';

function sanitizeInput($data) {
    return htmlspecialchars(trim($data)); // Sanitize by trimming and converting special characters
}

// Common check for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update Logic
    if (isset($_POST['id']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['address']) && isset($_POST['phoneNumber']) && isset($_POST['userName']) && isset($_POST['password'])) {
        $id = $_POST['id'];
        $firstName = sanitizeInput($_POST['firstName']);
        $lastName = sanitizeInput($_POST['lastName']);
        $address = sanitizeInput($_POST['address']);
        $phoneNumber = sanitizeInput($_POST['phoneNumber']);
        $userName = sanitizeInput($_POST['userName']);
        $password = $_POST['password'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $group = 'user'; // Default user group

        // Start transaction to ensure both updates are applied successfully
        $conn->begin_transaction();

        // Check if the user ID exists in lpa_users
        $queryUser = "SELECT COUNT(*) AS count FROM lpa_users WHERE lpa_user_ID = ?";
        $stmtCheckUser = $conn->prepare($queryUser);
        $stmtCheckUser->bind_param("i", $id);
        $stmtCheckUser->execute();
        $stmtCheckUser->bind_result($userCount);
        $stmtCheckUser->fetch();
        $stmtCheckUser->close();

        if ($userCount == 0) {
            echo json_encode(["error" => "User ID not found in lpa_users."]);
            $conn->rollback();
            exit;
        }

        // Check if the username already exists for a different user
        $queryUsername = "SELECT COUNT(*) AS count FROM lpa_users WHERE lpa_user_username = ? AND lpa_user_group = ? AND lpa_user_ID != ?";
        $stmtCheckUsername = $conn->prepare($queryUsername);
        $stmtCheckUsername->bind_param("ssi", $userName , $group, $id);
        $stmtCheckUsername->execute();
        $stmtCheckUsername->bind_result($usernameCount);
        $stmtCheckUsername->fetch();
        $stmtCheckUsername->close();

        if ($usernameCount > 0) {
            echo json_encode(["error" => "The username '$userName' is already taken by another user."]);
            $conn->rollback();
            exit;
        }

        // Update SQL query for lpa_users
        $sqlUser = "UPDATE lpa_users SET lpa_user_username = ?, lpa_user_password = ?, lpa_user_firstname = ?, lpa_user_lastname = ? WHERE lpa_user_ID = ?";
        
        // Prepare and execute the query for lpa_users
        if ($stmtUser = $conn->prepare($sqlUser)) {
            $stmtUser->bind_param("ssssi", $userName, $hashedPassword, $firstName, $lastName, $id);
            if (!$stmtUser->execute()) {
                echo json_encode(["error" => "Error updating user data: " . $stmtUser->error]);
                $conn->rollback(); // Rollback transaction if user update fails
                $stmtUser->close();
                $conn->close();
                exit;
            }
            $stmtUser->close();
        } else {
            echo json_encode(["error" => "Error preparing SQL query for lpa_users."]);
            $conn->rollback(); // Rollback transaction if user update preparation fails
            $conn->close();
            exit;
        }

        // Update SQL query for lpa_clients
        $sqlClient = "UPDATE lpa_clients SET lpa_client_firstname = ?, lpa_client_lastname = ?, lpa_client_address = ?, lpa_client_phone = ? WHERE lpa_client_ID = ?";

        // Prepare and execute the query for lpa_clients
        if ($stmtClient = $conn->prepare($sqlClient)) {
            $stmtClient->bind_param("ssssi", $firstName, $lastName, $address, $phoneNumber, $id);
            if (!$stmtClient->execute()) {
                echo json_encode(["error" => "Error updating client data: " . $stmtClient->error]);
                $conn->rollback(); // Rollback transaction if client update fails
                $stmtClient->close();
                $conn->close();
                exit;
            }
            $stmtClient->close();
        } else {
            echo json_encode(["error" => "Error preparing SQL query for lpa_clients."]);
            $conn->rollback(); // Rollback transaction if client update preparation fails
            $conn->close();
            exit;
        }

        // Commit the transaction if both updates succeed
        $conn->commit();
        $conn->close();
        echo json_encode(["success" => "User and client data updated successfully!"]);
        exit;
    }

    // Deletion Logic
    if (isset($_POST['id'])) {
        $id = $_POST['id']; // Get the ID from POST request

        // Check if ID is valid (numeric and non-empty)
        if (empty($id) || !is_numeric($id)) {
            echo json_encode(["error" => "Invalid ID provided."]);
            exit;
        }

        // Start the transaction
        $conn->begin_transaction();

        // Prepare DELETE query for lpa_clients
        $stmtClient = $conn->prepare("DELETE FROM lpa_clients WHERE lpa_client_ID = ?");
        if (!$stmtClient) {
            echo json_encode(["error" => "Error preparing SQL query for lpa_clients"]);
            $conn->rollback();
            exit;
        }

        // Bind the ID for client deletion
        $stmtClient->bind_param("i", $id);

        // Execute the query for lpa_clients
        if (!$stmtClient->execute()) {
            echo json_encode(["error" => "Error deleting client data: " . $stmtClient->error]);
            $conn->rollback();
            $stmtClient->close();
            exit;
        }

        // Prepare DELETE query for lpa_users
        $stmtUser = $conn->prepare("DELETE FROM lpa_users WHERE lpa_user_ID = ?");
        if (!$stmtUser) {
            echo json_encode(["error" => "Error preparing SQL query for lpa_users"]);
            $conn->rollback();
            $stmtClient->close();
            exit;
        }

        // Bind the ID for user deletion
        $stmtUser->bind_param("i", $id);

        // Execute the query for lpa_users
        if (!$stmtUser->execute()) {
            echo json_encode(["error" => "Error deleting user data: " . $stmtUser->error]);
            $conn->rollback();
            $stmtClient->close();
            $stmtUser->close();
            exit;
        }

        // Commit the transaction if both deletions succeed
        $conn->commit();

        // Close statements and connection
        $stmtClient->close();
        $stmtUser->close();
        $conn->close();

        echo json_encode(["success" => "User and associated client data deleted successfully"]);
        exit;
    }

    // Insert User and Client Data Logic
    if (isset($_POST['stockData'])) {
        // Get the raw POST data (which is the stock data in JSON format)
        $stockData = $_POST['stockData'];

        // If stockData is not provided or is empty
        if (!$stockData) {
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

        // Prepare the SQL insert statement for lpa_users
        $stmtUser = $conn->prepare("INSERT INTO lpa_users (lpa_user_username, lpa_user_password, lpa_user_firstname, lpa_user_lastname, lpa_user_group, lpa_inv_status) VALUES (?, ?, ?, ?, ?, ?)");

        // Prepare the SQL insert statement for lpa_clients
        $stmtClient = $conn->prepare("INSERT INTO lpa_clients (lpa_client_ID, lpa_client_firstname, lpa_client_lastname, lpa_client_address, lpa_client_phone, lpa_client_status) VALUES (?, ?, ?, ?, ?, ?)");

        // Loop through the users array to insert into lpa_users and lpa_clients
        foreach ($users as $user) {
            $userName = sanitizeInput($user['userName']);
            $password = $user['password'];
            $firstName = sanitizeInput($user['firstName']);
            $lastName = sanitizeInput($user['lastName']);
            $group = 'user'; // Default user group
            $status = '1'; // Default user status

            // Check if the username already exists
            $query = "SELECT COUNT(*) AS count FROM lpa_users WHERE lpa_user_username = ? AND lpa_user_group = ?";
            $stmtCheck = $conn->prepare($query);
            $stmtCheck->bind_param("ss", $userName, $group);
            $stmtCheck->execute();
            $stmtCheck->bind_result($count);
            $stmtCheck->fetch();
            $stmtCheck->close();

            // If username already exists, return the error message as JSON response
            if ($count > 0) {
                echo json_encode(['error' => "The username '$userName' is already taken. Please choose another one."]);
                $conn->rollback();
                exit; // Stop execution and return the error response
            }

            // Hash the password using PHP's password_hash function
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into lpa_users table
            $stmtUser->bind_param("sssssi", $userName, $hashedPassword, $firstName, $lastName, $group, $status);

            if (!$stmtUser->execute()) {
                echo json_encode(['error' => "Error inserting user into lpa_users: " . $stmtUser->error]);
                $conn->rollback();
                exit;
            }

            // Get the last inserted user ID for the current user
            $user_id = $stmtUser->insert_id;

            // Get the client data for the current user
            $address = sanitizeInput($user['address']);
            $phoneNumber = sanitizeInput($user['phoneNumber']);

            // Insert client data into lpa_clients table
            $stmtClient->bind_param("issssi", $user_id, $firstName, $lastName, $address, $phoneNumber, $status);

            if (!$stmtClient->execute()) {
                echo json_encode(['error' => "Error inserting client into lpa_clients: " . $stmtClient->error]);
                $conn->rollback();
                exit;
            }

            // Clear the client statement after executing
            $stmtClient->free_result();
        }

        // Commit the transaction if both insertions succeed
        $conn->commit();
        echo json_encode(['success' => 'Data uploaded successfully!']);

        // Close the statement and connection
        $stmtUser->close();
        $stmtClient->close();
        $conn->close();
        exit;
    }
} 
?>


<?php
    require 'header.php';
?>


<h1>Users Management System</h1>
<!--Table1-->
<div class="container">
    <h4>Add New User</h4>
    <form id="addUserForm">
        <!--<label for="firstName">First Name:</label>-->
        <input type="text" id="firstName" name="firstName" placeholder="firstName" required>

        <!--<label for="lastName">Last Name:</label>-->
        <input type="text" id="lastName" name="lastName" placeholder="lastName" required>

        <!--<label for="address">Address:</label>-->
        <input type="text" id="address" name="address" placeholder="address" required>

        <!--<label for="phoneNumber">Phone Number</label>-->
        <input type="number" id="phoneNumber" name="phoneNumber" placeholder="phoneNumber" required>

        <!--<label for="userName">Username</label>-->
        <input type="text" id="userName" name="userName" placeholder="userName" required>

        <!--<label for="password">Password</label>-->
        <input type="password" id="password" name="password" placeholder="password" required>

        <!--<label for="confirmPassword">Confirm Password</label>-->
        <!--<input type="password" id="confirmPassword" name="confirmPassword" placeholder="confirmPassword" required>-->

        <button type="button" onclick="addUser()">Add in Table</button>
        <button type="button" id="submitButton" onclick="uploadDataInStock()">Upload Data</button>

    </form>

    <table id="User">
        <thead>
            <tr>
                <th>No</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Username</th>
                <th>Password</th>
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
    <h4>Update User Information</h4>
    <form id="UpdateUserForm">
        <input type="text" id="search-input" name="firstName" placeholder="User details..." required>
        <button type="button" onclick="filterUsers()">Find User</button>
    </form>

    <table id="stockTable_1">
        <thead>
            <tr>
                <th>No</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Username</th>
                <th>Password</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
            include '../model/config.php';
            //$sql = "SELECT * FROM lpa_users";  // Adjust this query to match your table and columns
            //$result = $conn->query($sql);

            $sql = "SELECT c.lpa_client_address, c.lpa_client_phone, u.	lpa_user_firstname, u.lpa_user_lastname, u.lpa_user_password, u.lpa_user_username, u.lpa_user_ID
            FROM lpa_clients c
            INNER JOIN lpa_users u 
            ON c.lpa_client_ID = u.lpa_user_ID
            WHERE u.lpa_user_group = 'user'";

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
                echo "<tr class='user' data-id='" . $item["lpa_user_ID"] . "'>";  // Added class 'product' to each row
                echo "<td>" . htmlspecialchars($item["lpa_user_ID"]) . "</td>";
                echo "<td class='user-firstName'>" . htmlspecialchars($item["lpa_user_firstname"]) . "</td>";
                echo "<td class='user-lastName'>" . htmlspecialchars($item["lpa_user_lastname"]) . "</td>";
                echo "<td class='user-address'>" . htmlspecialchars($item["lpa_client_address"]) . "</td>";
                echo "<td class='user-phone'>" . htmlspecialchars($item["lpa_client_phone"]) . "</td>";
                echo "<td class='user-userName'>" . htmlspecialchars($item["lpa_user_username"]) . "</td>";
                echo "<td class='user-password'>" . htmlspecialchars($item["lpa_user_password"]) . "</td>";
                echo "<td>
                        <button class='update-btn' onclick='updateUserInUI(this)'>Update</button>
                        <button class='delete-btn' data-id='" . $item["lpa_user_ID"] . "' onclick='deleteUserInUI(this)'>Delete</button>
                        </td>";
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>

</div>
<!-- JavaScript for actions -->
<script src="script_user.js"></script>

</body>
</html>



<?php
    require 'footer.php';
?>