
<?php 
    session_start(); 
    require 'header.php'
?>
    <!-- Display session messages (if any) -->

    
    <!-- Display session messages (if any) -->
    <div class="welcome-container">
        <h1>Welcome to the Stock Management System</h1>
        <p>Manage your inventory, sales, clients, and more with ease. This system is designed to streamline your business operations and improve efficiency.</p>
        
        <div class="quick-links">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="stockmanagement.php">Manage Stock</a></li>
                <li><a href="salesandinvoicing.php">Sales and Invoicing</a></li>
                <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="clientsmanagement.php">Clients Management</a></li> 
                <?php endif; ?>
                <li><a href="usersguide.php">User Guide</a></li>
                <li><a href="about.php">About the System</a></li>
            </ul>
        </div>
    </div>
</body>
</html>

<?php
    require 'footer.php';
?>