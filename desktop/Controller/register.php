<?php
    session_start(); // Start the session at the beginning of the script
    include('../model/config.php'); // Include the database connection configuration file

    function sanitizeInput($data) {
        return htmlspecialchars(trim($data)); // Sanitize by trimming and converting special characters
    }

    // Handle the form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize and retrieve the form inputs
        $firstName = sanitizeInput($_POST['firstName']);
        $lastName = sanitizeInput($_POST['lastName']);
        $address = sanitizeInput($_POST['address']);
        $phoneNumber = sanitizeInput($_POST['phoneNumber']);
        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $group = 'user';
        echo "<script>alert('Sanitized Data:\nFirst Name: " . htmlspecialchars($firstName) . "\nLast Name: " . htmlspecialchars($lastName) . "\nAddress: " . htmlspecialchars($address) . "\nPhone Number: " . htmlspecialchars($phoneNumber) . "\nUsername: " . htmlspecialchars($username) . "\nPassword: " . htmlspecialchars($password) . "\nConfirm Password: " . htmlspecialchars($confirmPassword) . "');</script>";

        // Validate input fields
        if (empty($firstName) || empty($lastName) || empty($address) || empty($phoneNumber) || empty($username) || empty($password) || empty($confirmPassword)) {
            $_SESSION['error_message'] = 'All fields are required.';
        }
        elseif ($password !== $confirmPassword) {
            $_SESSION['error_message'] = 'Passwords do not match. Please try again.';
        } else {
            // Check if the username already exists
            $query = "SELECT COUNT(*) AS count FROM lpa_users WHERE lpa_user_username = ? AND lpa_user_group = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $username, $group);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            // Handle username availability
            if ($count > 0) {
                $_SESSION['error_message'] = 'This username is already taken. Please choose another one.';
            } else {
                try {
                    // Hash the password before saving it
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $defaultGroup = 'admin';  // Default group for the user
                    $defaultStatus = '1';    // Default status for the user

                    // Begin transaction to insert into both tables
                    $conn->begin_transaction();

                    // Insert into the users table
                    $stmt = $conn->prepare("INSERT INTO lpa_users (lpa_user_username, lpa_user_password, lpa_user_firstname, lpa_user_lastname, lpa_user_group, lpa_inv_status) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $username, $hashed_password, $firstName, $lastName, $defaultGroup, $defaultStatus);

                    // Execute and check if successful
                    if (!$stmt->execute()) {
                        throw new Exception("Error inserting into lpa_users: " . $stmt->error);
                    }

                    // Get the last inserted user ID
                    $user_id = $stmt->insert_id;
                    $stmt->close();

                    // Insert into the clients table
                    $stmt = $conn->prepare("INSERT INTO lpa_clients (lpa_client_ID, lpa_client_firstname, lpa_client_lastname, lpa_client_address, lpa_client_phone, lpa_client_status) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $user_id, $firstName, $lastName, $address, $phoneNumber, $defaultStatus);

                    // Execute and check if successful
                    if (!$stmt->execute()) {
                        throw new Exception("Error inserting into lpa_clients: " . $stmt->error);
                    }

                    // Commit transaction if both inserts are successful
                    $conn->commit();

                    // Regenerate session ID to prevent session fixation
                    session_regenerate_id(true);

                    // Set success message and redirect
                    $_SESSION['success_message'] = 'User created successfully.';
                    header('Location: register.php');
                    exit();
                } catch (Exception $e) {
                    // Rollback if there's an error
                    $conn->rollback();
                    $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
                }
            }
        }
    }

    // Close the database connection
    $conn->close();
?>

<?php
    require '../view/header.php';
?>

    <!-- Display session messages (if any) -->
    <?php
    if (isset($_SESSION['error_message'])) {
        echo "<div class='alert error'>{$_SESSION['error_message']}</div>";
        unset($_SESSION['error_message']);
    }

    if (isset($_SESSION['success_message'])) {
        echo "<div class='alert success'>{$_SESSION['success_message']}</div>";
        unset($_SESSION['success_message']);
    }
    ?>


    <div class="register">
        <h2>Create Your Account</h2>
        <form action="register.php" method="POST">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="firstName" required><br><br>

            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName" required><br><br>

            <label for="address">Address</label>
            <input type="text" id="address" name="address" required><br><br>

            <label for="phoneNumber">Phone Number</label>
            <input type="number" id="phoneNumber" name="phoneNumber" required><br><br>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required><br><br>

            <button type="submit">Register</button>
            <button class="cancel" type="button" onclick="location.href='login.php'">Cancel</button>
        </form>
    </div>
</body>
</html>

<?php
    require '../view/footer.php';
?>