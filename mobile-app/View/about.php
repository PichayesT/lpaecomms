<?php
    session_start(); 
    require 'header.php';
?>

 <!-- Main Content Area -->
 <main>
    <div class="about-container">
        <!-- Mission Statement Section -->
        <section id="mission-statement">
            <h2>Our Mission</h2>
            <p>At Logical Peripherals Australia, our mission is to provide cutting-edge technology solutions that enhance the everyday lives of our customers. We strive for excellence in innovation, customer service, and product quality.</p>
        </section>

        <!-- YouTube Video Embed Section -->
        <section id="youtube-video">
            <h2>Watch Our Introduction</h2>
            <div class="video-container">
                <!-- YouTube Embed Code -->
                <iframe width="500" height="315" src="https://www.youtube.com/embed/M0F4cc2dkV8?si=p-P2RxOt4GfeJIWf" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </section>

        <!-- Facebook Feed Section -->
        <section id="facebook-feed">
            <h2>Follow Us on Facebook</h2>
            <div class="fb-page" data-href="https://www.facebook.com/CanterburyTechnicalInstitute" data-tabs="timeline" data-width="500" data-height="600" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/CanterburyTechnicalInstitute" class="fb-xfbml-parse-ignore">
                    <a href="https://www.facebook.com/CanterburyTechnicalInstitute">Logical Peripherals Australia</a>
                </blockquote>
            </div>
        </section>

        <!-- Google Map Section -->
        <section id="google-map">
            <h2>Our Head Office Location</h2>
            <div class="map-container">
                <!-- Google Map Embed Code -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3540.1252277328813!2d153.0265859762565!3d-27.465360476322125!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b915a1d772edb2d%3A0xa10d73f1dee93c97!2sCanterbury%20Technical%20Institute!5e0!3m2!1sen!2sau!4v1731040597896!5m2!1sen!2sau" width="500" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
    </div>
</main>

</body>
</html>



<?php
    require 'footer.php';
?>