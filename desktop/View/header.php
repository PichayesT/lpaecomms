<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management System</title>
    <link rel="stylesheet" href="../view/styles.css">
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
    <script src="../view/script_header.js"></script>
<header>
        <div class="menu-bar">
            <ul class="menu">
                <li class="menu-item">Menu
                    <ul class="submenu">
                        <li><a href="#">Stock Management
                        <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && in_array($_SESSION['role'], ['user', 'admin'])): ?>
                            <ul class="sub"> 
                                <li><a href="../view/stockmanagement.php">Stocking</a></li>
                            </ul>
                        <?php endif; ?>
                        </a></li>   
                        <li><hr></li>

                        <li><a href="#">Sales and Invoicing
                        <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && in_array($_SESSION['role'], ['user', 'admin'])): ?>
                            <ul class="sub">
                                <li><a href="../view/salesandinvoicing.php">Invoices management</a></li>
                            </ul>
                        <?php endif; ?>
                        </a></li>
                        <li><hr></li>

                        <li><a href="#">System Administration
                        <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && $_SESSION['role'] === 'admin'): ?>
                            <ul class="sub">
                                <li><a href="../view/clientsmanagement.php">Clients management</a></li>
                                <li><a href="../view/usersmanagement.php">Users management</a></li>
                                <li><a href="../view/adminmanagement.php">Admin management</a></li>
                            </ul>
                        <?php endif; ?>
                        </a></li>
                        <li><hr></li>
                        
                        <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && in_array($_SESSION['role'], ['user', 'admin'])): ?>
                            <li><a href="../view/index.php">Exit</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="menu-item">Help
                    <ul class="submenu">
                        <li><a href="../view/usersguide">User Guide</a></li>
                        <li><a href="../view/about.php">About</a></li>
                    </ul>
                </li>
                <!--<li class="menu-item"><a href="../controller/login.php">Log In</a></li>-->
                <li class="menu-item"><a href="<?php echo isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] ? '../controller/logout.php' : '../controller/login.php'; ?>">
                    <?php echo isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] ? 'Hi ' . htmlspecialchars($_SESSION['username']) . ', ' . 'Log Out' : 'Log In'; ?>
                </a></li>
            </ul>
        </div>
</header>
