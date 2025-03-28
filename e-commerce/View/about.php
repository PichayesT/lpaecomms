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
        // Assuming the login is successful (replace this with actual authentication logic)
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = htmlspecialchars($_POST['username']);  // Sanitize input
        $_SESSION['user_id'] = 1;  // Example user ID
    }
?>


<?php
    require 'header.php';
?>

        <!-- Picture -->
    <div class="header">
        <h2>About us</h2>
    </div>
    <!-- Main Content Area -->
    <main>
        <!-- Mission Statement Section -->
        <div class="about-container">
            <section id="mission-statement">
                <h2>Our Mission</h2>
                <p id="mission-content">Loading mission statement...</p> <!-- Placeholder for API data -->
                <!--<p>At Logical Peripherals Australia, our mission is to provide cutting-edge technology solutions that enhance the everyday lives of our customers. We strive for excellence in innovation, customer service, and product quality.</p>-->
            </section>

            <section id="google-map">
                <h2>Our Head Office Location</h2>
                <div class="map-container">
                    <!-- This is where the map will be rendered -->
                    <div id="map" style="width: 100%; height: 350px;"></div>
                </div>
                <!-- Include the Google Maps API script with the callback to initMap -->
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUNXbWenqIshMQ58NSVy9i-H0Z4LIzwwA&callback=initMap" async defer></script>
            </section>

            <!-- YouTube Video Embed Section -->
            <section id="youtube-video">
                <h2>Watch us</h2>
                <div id="player" style="width: 100%; height: 350px;"></div>
            </section>

                <!--<h2>Watch Our Introduction</h2>
                <div class="video-container">
                    <iframe width="560" height="350" src="https://www.youtube.com/embed/M0F4cc2dkV8?si=p-P2RxOt4GfeJIWf" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>-->

            <!-- Facebook Feed Section -->
            <section id="facebook-feed">
                <h2>Follow Us on Facebook</h2>
                <img src="../images/facebook.PNG" alt="Facebook Feed" style="width:450px; height:350px;">
                <div class="fb-page" data-href="https://www.facebook.com/CanterburyTechnicalInstitute" data-tabs="timeline" data-width="500" data-height="600" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                    <blockquote cite="https://www.facebook.com/CanterburyTechnicalInstitute" class="fb-xfbml-parse-ignore">
                        <a href="https://www.facebook.com/CanterburyTechnicalInstitute">Logical Peripherals Australia</a>
                    </blockquote>
                </div>
            </section>
        </div>
    </main>
    <script src="https://www.youtube.com/iframe_api"></script>
    <!-- Footer -->
    <script src="js/script.js"></script>
</body>
</html>

<?php
    require 'footer.php';
?>