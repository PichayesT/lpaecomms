<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chatbot</title>
        <!--Favicon-->
        <link rel="icon" type="image/x-icon" href="747954-Product-1-I-638567925047048852.webp">
        <!-- Link to your CSS file -->
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <!-- Header -->
        <header>
            <h1>Logic Peripherals Australia (LPA)</h1>
        </header>
        <!-- Link the external JavaScript file -->
        <script src="script.js"></script>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>  
                <li><a href="product.php">Product</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="cart.php" class="cart">(0)</a></li>
                <li><a href="<?php echo isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] ? 'log_out.php' : 'log_in.php'; ?>">
                    <?php echo isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] ? 'Hi ' . htmlspecialchars($_SESSION['username']) . ', ' . 'Log Out' : 'Log In'; ?>
                </a></li>
            </ul>
        </nav>
    <!-- Main Content Area -->
    <main>
        
    </main>
    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Logic Peripherals Australia.</p>
    </footer>
</body>
</html>