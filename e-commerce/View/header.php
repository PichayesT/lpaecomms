<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LPA</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../images/Logo.PNG">
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="../view/css/styles.css">
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
        <img src="../images/Logo.PNG" alt="My Brand Logo" width="150" height="auto"> <h1>Logic Peripherals Australia (LPA)</h1>
    </header>

    <!-- Link the external JavaScript file -->
    <script src="../view/js/script.js"></script>

    <nav>
        <ul>
            <li><a href="../view/index.php">Home</a></li>
            <li><a href="../view/product.php">Product</a></li>       
            <li><a href="../view/about.php">About</a></li>
            <li><a href="../view/contact.php">Contact</a></li>
            <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && in_array($_SESSION['role'], ['client', 'user', 'admin'])): ?>
                <li><a href="../view/cart.php" class="cart">(0)</a></li>
            <?php endif; ?>
            <li><a href="<?php echo isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] ? '../controller/log_out.php' : '../controller/log_in.php'; ?>">
                <?php echo isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] ? 'Hi ' . htmlspecialchars($_SESSION['username']) . ', ' . 'Log Out' : 'Log In'; ?>
            </a></li>
        </ul>
    </nav>