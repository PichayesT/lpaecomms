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
    
    <!--picture-->>
    <div class="header">
        <h2>Contact</h2>
    </div>
    <!-- Main Content Area -->
    <main>
        
    </main>

    <!-- Footer -->
</body>
</html>

<?php
    require 'footer.php';
?>