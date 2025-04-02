let stock = [];

    // Add a new product to the stock array
    function addProductInStock() {
        const name = document.getElementById('productName').value.trim();
        const url = document.getElementById('productPicture').value.trim();
        const description = document.getElementById('productDescription').value.trim();
        const quantity = parseInt(document.getElementById('productQuantity').value);
        const price = parseFloat(document.getElementById('productPrice').value);

        if (name === '' || url === '' || description === '' || isNaN(quantity) || isNaN(price) || quantity <= 0 || price <= 0) {
            alert('Please enter valid product details.');
            return;
        }

        stock.push({ name, url, description, quantity, price });
        renderTable();
        clearInputs();
    }

    // Render the stock table
    function renderTable() {
        const tbody = document.getElementById('stockTable').querySelector('tbody');
        tbody.innerHTML = '';

        stock.forEach((item, index) => {
            const row = tbody.insertRow();

            row.insertCell(0).textContent = index + 1;
            row.insertCell(1).textContent = item.name;
            row.insertCell(2).textContent = item.url;
            row.insertCell(3).textContent = item.description;
            row.insertCell(4).textContent = item.quantity;
            row.insertCell(5).textContent = item.price.toFixed(2);
            row.insertCell(6).textContent = (item.quantity * item.price).toFixed(2);

            const actionCell = row.insertCell(7);

            const updateBtn = document.createElement('button');
            updateBtn.textContent = 'Update';
            updateBtn.className = 'update-btn';
            updateBtn.onclick = () => updateProductInStock(index);

            const deleteBtn = document.createElement('button');
            deleteBtn.textContent = 'Delete';
            deleteBtn.className = 'delete-btn';
            deleteBtn.onclick = () => deleteProductInStock(index);

            actionCell.appendChild(updateBtn);
            actionCell.appendChild(deleteBtn);
        });

        // Enable or disable the upload button based on stock availability
        document.getElementById('submitButton').disabled = stock.length === 0;
    }

    // Update a product in the stock array
    function updateProductInStock(index) {
        const newName = prompt('Enter new product name:', stock[index].name);
        const newURL = prompt('Enter new URL:', stock[index].url);
        const newDescription = prompt('Enter new Description:', stock[index].description);
        const newQuantity = prompt('Enter new quantity:', stock[index].quantity);
        const newPrice = prompt('Enter new price:', stock[index].price);

        // Check if all inputs are valid
        if (newName && newURL && newDescription && !isNaN(newQuantity) && !isNaN(newPrice) && newQuantity.trim() !== "" && newPrice.trim() !== "") {
            stock[index].name = newName.trim();
            stock[index].url = newURL.trim();
            stock[index].description = newDescription.trim();
            stock[index].quantity = parseInt(newQuantity);
            stock[index].price = parseFloat(newPrice);
            renderTable();
        } else {
            alert('Invalid input. Update cancelled.');
        }
    }

    // Delete a product from the stock array
    function deleteProductInStock(index) {
        if (confirm('Are you sure you want to delete this product?')) {
            stock.splice(index, 1);
            renderTable();
        }
    }

    // Clear input fields
    function clearInputs() {
        document.getElementById('productName').value = '';
        document.getElementById('productPicture').value = '';
        document.getElementById('productDescription').value = '';
        document.getElementById('productQuantity').value = '';
        document.getElementById('productPrice').value = '';
    }

// Function to upload data (send stock data to the server)
function uploadDataInStock() {
    // Check if there's any product data in stock
    if (stock.length === 0) {
        alert("No products to upload.");
        return;
    }

    // Convert stock array to a JSON string
    const stockData = JSON.stringify(stock);

    // Send the stock data to the server using an AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "stockmanagement.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert("Data uploaded successfully!");
            // Clear the tbody content
            const tbody = document.getElementById('stockTable').getElementsByTagName('tbody')[0];
            tbody.innerHTML = '';
            // Optionally, disable the submit button if needed
            document.getElementById('submitButton').disabled = true;
        } else {
            alert("Error uploading data.");
        }
    };

    console.log("Sending stock data:", stockData); // Debug log
    // Send the data in the format of a POST request
    xhr.send("stockData=" + encodeURIComponent(stockData));
}


// Function to filter products based on search input
function filterProducts() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();  // Get the value from the input field
    const products = document.querySelectorAll('.product');  // Select all rows with the class 'product'

    products.forEach(product => {
        const productName = product.querySelector('.product-name').textContent.toLowerCase();  // Get the product name
        const productDes = product.querySelector('.product-desc').textContent.toLowerCase();
        if (productName.includes(searchInput) || productDes.includes(searchInput) )  {  // If the product name contains the search input
            product.style.display = 'table-row';  // Show the product row
        } else {
            product.style.display = 'none';  // Hide the product row
        }
    });
}

// Update a product (UI update first)
function updateProductInUI(button) {
    const row = button.closest('tr');
    const id = row.dataset.id;

    // Get current product details from the row
    const name = row.querySelector('.product-name').textContent;
    const url = row.querySelector('.product-url').textContent;
    const description = row.querySelector('.product-desc').textContent;
    const quantity = row.querySelector('.product-quantity').textContent;
    const price = row.querySelector('.product-price').textContent;
    const status = row.querySelector('.product-status').textContent;

    console.log("Current values:", name, url, description, quantity, price, status);

    // Prompt the user for new values
    const newName = prompt("Enter new product name:", name);
    const newURL = prompt("Enter new picture URL:", url);
    const newDescription = prompt("Enter new description:", description);
    const newQuantity = prompt("Enter new quantity:", quantity);
    const newPrice = prompt("Enter new price:", price);
    const newStatus = prompt("Enter new status (Enabled/Disabled):", status);

    console.log("New values:", newName, newURL, newDescription, newQuantity, newPrice, newStatus);

    // Validate inputs
    if (
        !newName.trim() || // Check if the name is empty
        !newURL.trim() || // Check if the URL is empty
        !newDescription.trim() || // Check if the description is empty
        isNaN(newQuantity) || newQuantity.trim() === "" || // Check if quantity is not a number or empty
        isNaN(newPrice) || newPrice.trim() === "" || // Check if price is not a number or empty
        (newStatus !== "Enabled" && newStatus !== "Disabled") // Check if status is not "Enabled" or "Disabled"
    ) {
        alert("Invalid input. Update canceled.");
        return; // Exit the function if invalid input
    }

    // Convert status to database format (1 for Enabled, 0 for Disabled)
    const statusValue = newStatus === "Enabled" ? 1 : 0;

    // Update the UI immediately with the new values
    row.querySelector('.product-name').textContent = newName;
    row.querySelector('.product-url').textContent = newURL;
    row.querySelector('.product-desc').textContent = newDescription;
    row.querySelector('.product-quantity').textContent = newQuantity;
    row.querySelector('.product-price').textContent = newPrice;
    row.querySelector('.product-status').textContent = newStatus;

    // Send AJAX request to update the database with the new values
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "stockmanagement.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert("Product updated successfully!");
        } else {
            alert("Error updating product.");
        }
    };
    xhr.send(
        `id=${id}&name=${newName}&url=${newURL}&description=${newDescription}&quantity=${newQuantity}&price=${newPrice}&status=${statusValue}`
    );
}


function deleteProductInUI(button) {
    const row = button.closest('tr');
    const id = row.dataset.id;

    // Confirm deletion
    const confirmDelete = confirm("Are you sure you want to delete this product?");
    if (!confirmDelete) return;

    // Send AJAX request to delete the product
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "stockmanagement.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert("Product deleted successfully!");
            row.remove(); // Remove the row from the UI
        } else {
            alert("Error deleting product.");
        }
    };
    xhr.send(`id=${id}`);
}


