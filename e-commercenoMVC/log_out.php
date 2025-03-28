<?php
session_start();
session_unset();  // Remove session variables
session_destroy();  // Destroy the session to log out
//header('Location: log_in.php');  // Redirect to the homepage after logging out
// Clear the cart from localStorage
echo "<script>
    localStorage.removeItem('cart');  // Remove the 'cart' item from localStorage
    window.location.href = 'log_in.php';  // Redirect to the homepage after logout
</script>";
?>
