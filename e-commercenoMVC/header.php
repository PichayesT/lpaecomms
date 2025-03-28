<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Page</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="747954-Product-1-I-638567925047048852.webp">
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="styles.css">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="theme-light"> <!-- Default theme is light -->
    <!-- Customization Panel with Theme Icons -->
    <div class="customization-panel">
        <!-- Light Theme Icon -->
        <i class="fas fa-sun theme-icon" id="light-icon"></i>
        <!-- Dark Theme Icon -->
        <i class="fas fa-moon theme-icon" id="dark-icon"></i>
    </div>

    <!-- Header -->
    <header>
        <img src="Logo.PNG" alt="My Brand Logo" width="150" height="auto"> <h1>Logic Peripherals Australia (LPA)</h1>
    </header>

    <!-- Link the external JavaScript file -->
    <script src="script.js"></script>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="product.php">Product</a></li>       
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && $_SESSION['role'] === 'user'): ?>
            <li><a href="cart.php" class="cart">(0)</a></li>
            <?php elseif (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && $_SESSION['role'] === 'admin'): ?>
                <li><a href="cart.php" class="cart">(0)</a></li> <!-- Change the link as needed -->
            <?php endif; ?>
            <li><a href="<?php echo isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] ? 'log_out.php' : 'log_in.php'; ?>">
                <?php echo isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] ? 'Hi ' . htmlspecialchars($_SESSION['username']) . ', ' . 'Log Out' : 'Log In'; ?>
            </a></li>
        </ul>
    </nav>