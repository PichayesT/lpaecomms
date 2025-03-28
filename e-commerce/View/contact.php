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
        <h2>Contact us</h2>
    </div>

    <main>
            <!-- Main Contact Section -->
        <section class="contact-container">
            <!-- Contact Details -->
            <div class="contact-details">
                <h2>Our Contact Information</h2>
                <p><strong>Phone:</strong> +61 (07) 3123 4055</p>
                <p><strong>Email:</strong> info@cti.qld.edu.au</p>
                <p><strong>Address:</strong> Level 1, 333 Adelaide St, Brisbane, QLD 4000</p>

                <div>
                    <h2>Follow Us</h2>
                    <a href="https://www.facebook.com/CanterburyTechnicalInstitute" target="_blank">Facebook</a> | 
                    <a href="https://www.instagram.com/studycti/#" target="_blank">Instagram</a> | 
                    <a href="https://www.linkedin.com/company/canterbury-technical-institute" target="_blank">Linkedin</a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <form action="contact.php" method="POST">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required placeholder="Enter your name">
                    
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">

                    <label for="message">Your Message:</label>
                    <textarea id="message" name="message" required placeholder="Write your message here..."></textarea>

                    <button type="button" onclick="window.location.href='contact.php';">Send Message</button>
                </form>
            </div>
        </section>

        <!-- Google Map -->
        <div class="map-container">
            <div id="map" style="width: 100%; height: 400px;"></div>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUNXbWenqIshMQ58NSVy9i-H0Z4LIzwwA&callback=initMap" async defer></script>

                
</body>
</html>

<?php
    require 'footer.php';
?>