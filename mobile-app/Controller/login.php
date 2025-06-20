<?php
    session_start();
    include('../model/config.php'); // Include your DB connection file

    if (isset($_POST['submit'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        //$group = ['user', 'admin'];
        $group = 'user';

        // Prepare SQL query to prevent SQL injection
        //$sql = "SELECT * FROM lpa_users WHERE lpa_user_username = ? AND lpa_user_group IN (?,?)";
        $sql = "SELECT * FROM lpa_users WHERE lpa_user_username = ? AND lpa_user_group IN (?)";
        $stmt = $conn->prepare($sql);
        //$stmt->bind_param("sss", $username, $group[0], $group[1]); 
        $stmt->bind_param("ss", $username, $group); 
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        // Check if user exists and verify password
        if ($user) {
            if (password_verify($password, $user['lpa_user_password'])) {
                // Password is correct, start session
                $_SESSION['loggedIn'] = true;
                $_SESSION['user_id'] = $user['lpa_user_ID'];
                $_SESSION['first_name'] = $user['lpa_user_firstname'];
                $_SESSION['last_name'] = $user['lpa_user_lastname'];
                $_SESSION['address'] = $user['lpa_user_group'];
                $_SESSION['username'] = $user['lpa_user_username'];
                $_SESSION['role'] = $user['lpa_user_group'];
                $_SESSION['success_message'] = 'Welcome to the system.';
                header("Location: login.php");
                exit(); // Ensure no further code is executed after redirection
            } else {
                $_SESSION['error_message'] = 'Invalid password.';
            }
        } else {
            $_SESSION['error_message'] = 'No user found with that username.';
        }

        // Close the statement
        $stmt->close();
    }

    // Close the database connection
    $conn->close();
    ?>


<?php
    require '../view/header.php';
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
    
    <div class="login">
        <div class="dialog">
            <h2>User Login</h2>
            <form action="login.php" method="POST"> <!-- Ensure this is the correct path -->
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <button type="submit" name="submit">Login</button>
                </div>
                <div class="input-group">
                    <button type="button" class="register-button" onclick="location.href='register.php'">Register</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

<?php
    require '../view/footer.php';
?>