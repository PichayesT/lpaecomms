// Get cart from cookies
function getCartFromCookie() {
    const cartCookie = document.cookie.replace(/(?:(?:^|.*;\s*)cart\s*=\s*([^;]*).*$)|^.*$/, "$1");
    return cartCookie ? JSON.parse(cartCookie) : [];
}

// Set cart cookie
function setCartCookie(cart) {
    const cartJSON = JSON.stringify(cart);
    document.cookie = `cart=${cartJSON}; path=/; max-age=${365 * 24 * 60 * 60}`;
}

// Search function to filter products
function filterProducts() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const products = document.querySelectorAll('.product');

    products.forEach(product => {
        const productName = product.querySelector('.product-name').textContent.toLowerCase();
        if (productName.includes(searchInput)) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

// JavaScript to handle Add to Cart functionality
function addToCart(productCode,productName, productPrice, quantity) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    let productIndex = cart.findIndex(item => item.code === productCode);
    if (productIndex !== -1) {
        cart[productIndex].quantity += quantity;
    } else {
        cart.push({
            code: productCode,
            name: productName,
            price: productPrice,
            quantity: quantity
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();  // Update cart count after adding an item
    alert('Product added to cart!');
}


// Function to update the cart count display
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartCount = cart.reduce((total, item) => total + item.quantity, 0); // Calculate total quantity of products
    
    // Find the cart link element and update its content
    const cartLink = document.querySelector('.cart');
    if (cartLink) {
        //cartLink.textContent = `${cartCount}`; // Update the cart text with the count
        cartLink.innerHTML = `${'&nbsp;'.repeat(3)}${cartCount}`;
    }
}

 // Update cart count when the page loads
 document.addEventListener('DOMContentLoaded', function() {
    updateCartCount(); // Ensure the cart count is updated when the page loads
});



// Function to load and display the cart items
function loadCart() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartItems = document.getElementById('cart-items');
    let totalPrice = 0;

    if (cartItems) {  // Check if cartItems exists
        cartItems.innerHTML = ''; // Clear the cart items
        
        if (cart.length === 0) {
            let row = document.createElement('tr');
            row.innerHTML = `<td colspan="6">Your cart is empty.</td>`; // Inform the user the cart is empty
            cartItems.appendChild(row);
        } else {
            // Loop through cart items and add them to the table
            cart.forEach((item, index) => {
                let amount = item.price * item.quantity;
                totalPrice += amount;

                let row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.code}</td>
                    <td>${item.name}</td>
                    <td>$${item.price}</td>
                    <td><input type="number" value="${item.quantity}" min="1" onchange="updateQuantity(${index}, this.value)"></td>
                    <td>$${amount}</td>
                    <td><button onclick="removeItem(${index})">Remove</button></td>
                `;
                cartItems.appendChild(row);
            });
        }

        // Update the total price display
        document.getElementById('total-price').textContent = `$${totalPrice.toFixed(2)}`;
    } else {
        console.error("Element with id 'cart-items' not found.");
    }
}



// Function to update the quantity of an item
function updateQuantity(index, newQuantity) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart[index].quantity = parseInt(newQuantity);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart(); // Reload the cart to reflect the changes
}

// Function to remove an item from the cart
function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1); // Remove the item at the given index
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart(); // Reload the cart to reflect the changes
    updateCartCount(); //try
}

// Function to proceed to checkout
function proceedToCheckout() {
    // Get cart data from localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Check if the cart is empty
    if (cart.length === 0) {
        alert("Your cart is empty! Please add items to your cart before proceeding to checkout.");
        return; 
    }

    // Convert cart data into a JSON string and store it in a cookie
    document.cookie = "cart=" + JSON.stringify(cart) + "; path=/; max-age=" + (365 * 24 * 60 * 60);  // Set cookie for 1 year

    // Redirect to the checkout page
    window.location.href = "../view/checkout.php";
}

// Load the cart when the page is loaded
window.onload = loadCart;



//Mashup
const apiUrl = 'http://localhost/Project/e-commerce/view/css/our-mission.json'; // Correct API URL

// Function to fetch mission data
async function fetchMissionData() {
    try {
        // Fetch data from the API
        const response = await fetch(apiUrl);

        // Check if the response is successful
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        // Parse the JSON response
        const data = await response.json();

        // Access the correct field from the JSON
        const missionElement = document.getElementById('mission-content');
        missionElement.innerHTML = data['mission-content'] || 'Mission data not available';  // Updated key

    } catch (error) {
        console.error('Error fetching mission data:', error);
        const missionElement = document.getElementById('mission-content');
        missionElement.innerHTML = 'Failed to load mission statement';
    }
}

// Call the function to fetch and display the mission statement
fetchMissionData();


//<!-- Google Maps API Script -->
    // Initialize and add the map
    function initMap() {
        // The location of the Head Office
        var officeLocation = { lat: -27.465360476322125, lng: 153.0265859762565 };

        // Create a map centered at the office location
        var map = new google.maps.Map(document.getElementById("map"), {
            zoom: 14, // Adjust zoom level
            center: officeLocation, // Set center
        });

        // Add a marker at the office location
        var marker = new google.maps.Marker({
            position: officeLocation,
            map: map,
            title: "Our Head Office", // Title on hover
        });
    }

//theme//
// Grab the body and theme icons
const lightIcon = document.getElementById("light-icon");
const darkIcon = document.getElementById("dark-icon");
const body = document.body;

// Set the default theme based on localStorage (if available)
const currentTheme = localStorage.getItem("theme") || "light";
body.className = "theme-" + currentTheme;

// Function to change the theme
function changeTheme(theme) {
    // Change the body's class based on the selected theme
    body.className = "theme-" + theme;
    // Save the selected theme in localStorage for persistence
    localStorage.setItem("theme", theme);
}

// Event listener for light theme
lightIcon.addEventListener("click", () => {
    changeTheme("light");
});

// Event listener for dark theme
darkIcon.addEventListener("click", () => {
    changeTheme("dark");
});



var player;

function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        height: '390',  // Set the height of the player
        width: '640',   // Set the width of the player
        videoId: 'M0F4cc2dkV8',  // YouTube Video ID (replace with your video ID)
        events: {
            // Set up event listeners (optional)
            'onStateChange': onPlayerStateChange
        }
    });
}

// This function will be called when the player's state changes
function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.PLAYING) {
        console.log("Video is playing");
    }
}

// Optionally, you can use JavaScript to control the video
function playVideo() {
    player.playVideo();
}

function pauseVideo() {
    player.pauseVideo();
}

function stopVideo() {
    player.stopVideo();
        }