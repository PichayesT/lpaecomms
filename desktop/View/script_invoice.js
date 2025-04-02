let stock = [];

// Add a new product to the stock array
function addProductInStock() {
    const customerID = parseInt(document.getElementById('customerID').value);
    const customerfirstName = document.getElementById('firstName').value.trim();
    const customerlastName = document.getElementById('lastName').value.trim();
    const customerAddress = document.getElementById('address').value.trim();
    const customerfullName = customerfirstName + ' ' + customerlastName; 

    // Correct the productID field to use the correct input
    const productID = parseInt(document.getElementById('productID').value); // Use the correct input field for productID
    const productName = document.getElementById('productName').value.trim();
    const productAmount = parseFloat(document.getElementById('productAmount').value);
    const productPrice = parseFloat(document.getElementById('productPrice').value);
    const productOnHand = parseFloat(document.getElementById('productOnHand').value);

    // Validation check for empty or invalid inputs
    if (isNaN(customerID) || customerfirstName === '' || customerlastName === '' || customerAddress === '' || customerfullName === '' || isNaN(productID) || productName === '' || isNaN(productPrice) || isNaN(productAmount) || productAmount <= 0 || isNaN(productOnHand) || productOnHand <= 0) {
        alert('Please enter valid product details or check product on-hand.');
        return;
    }

    // Check if product amount exceeds product on hand
    if (productAmount > productOnHand) {
        alert('Product amount cannot exceed the quantity on hand.');
        return;  // Stop the process
    }

    // Ensure no duplicate productID is added to stock
    const existingProduct = stock.find(item => item.productID === productID);
    if (existingProduct) {
        alert('A product with this ID already exists.');
        return;  // Stop the process if product ID is already in stock
    }

    // Add the new product to the stock array
    stock.push({ customerID, customerfullName, customerAddress, productID, productName, productAmount, productPrice, productOnHand });

    // Render the table with updated data and update the total
    renderTable();
    clearInputs();
}

// Add event listener for the "Add Product" button
document.getElementById('addProductButton').addEventListener('click', addProductInStock);

// Render the stock table
function renderTable() {
    const tbody = document.getElementById('stockTable').querySelector('tbody');
    tbody.innerHTML = '';  // Clear existing table rows

    let totalPrice = 0;  // Initialize total price

    // Add rows to the table for each item in the stock
    stock.forEach((item, index) => {
        const row = tbody.insertRow();

        row.insertCell(0).textContent = index + 1;  // Row number

        // Columns for customer information
        row.insertCell(1).textContent = item.customerID;  // Customer ID
        row.insertCell(2).textContent = item.customerfullName;  // Full Name
        row.insertCell(3).textContent = item.customerAddress;  // Customer Address

        // Columns for product information
        row.insertCell(4).textContent = item.productID;  // Product ID
        row.insertCell(5).textContent = item.productName;  // Product Name
        row.insertCell(6).textContent = item.productAmount;  // Product Amount
        row.insertCell(7).textContent = item.productPrice.toFixed(2);  // Product Price
        const productTotal = (item.productAmount * item.productPrice).toFixed(2);
        row.insertCell(8).textContent = productTotal;  // Product Total (Amount * Price)

        // Update totalPrice by adding the current row's total price
        totalPrice += parseFloat(productTotal);

        // Add a delete and update button for each row
        const actionCell = row.insertCell(9);

        // Update button
        const updateBtn = document.createElement('button');
        updateBtn.textContent = 'Update';
        updateBtn.className = 'update-btn';
        updateBtn.onclick = () => updateProductInStock(index);
        actionCell.appendChild(updateBtn);

        // Delete button
        const deleteBtn = document.createElement('button');
        deleteBtn.textContent = 'Delete';
        deleteBtn.className = 'delete-btn';
        deleteBtn.onclick = () => deleteProductInStock(index);
        actionCell.appendChild(deleteBtn);
    });

        // Add a new row at the bottom of the table for Total Price
        const totalRow = tbody.insertRow();
        totalRow.insertCell(0).textContent = '';  // Empty cell for row number
        totalRow.insertCell(1).textContent = '';  // Empty cell for customer ID
        totalRow.insertCell(2).textContent = '';  // Empty cell for customer full name
        totalRow.insertCell(3).textContent = '';  // Empty cell for customer address
        totalRow.insertCell(4).textContent = '';  // Empty cell for product ID
        totalRow.insertCell(5).textContent = '';  // Empty cell for product name
        totalRow.insertCell(6).textContent = '';  // Empty cell for product amount
        totalRow.insertCell(7).textContent = '';  // Empty cell for product price
        totalRow.insertCell(8).textContent = '';  // Empty cell for product total
        totalRow.insertCell(9).textContent = `Total: $${parseFloat(totalPrice.toFixed(2)).toLocaleString()}`;  // Display the total price in the last column
    
        // Optional: Style the last row to make it look different
        totalRow.style.fontWeight = 'bold';
        totalRow.style.color = 'red';
        totalRow.style.backgroundColor = '#f8f8f8';  // Light gray background
    
        // Disable the submit button if stock is empty
        document.getElementById('submitButton').disabled = stock.length === 0;
    }


// Update a product in the stock array
function updateProductInStock(index) {
    const newproductAmount = prompt('Enter new quantity:', stock[index].productAmount);

    // Check if all inputs are valid
    if (!isNaN(newproductAmount)) {
        stock[index].productAmount = parseInt(newproductAmount);
        renderTable();
        updateTotal();
    } else {
        alert('Invalid input. Update cancelled.');
    }
}


// Delete a product from the stock array
function deleteProductInStock(index) {
    if (confirm('Are you sure you want to delete this product?')) {
        stock.splice(index, 1);  // Remove the product from the stock array
        renderTable();  // Re-render the table
        updateTotal();  // Recalculate the total
    }
}

// Clear input fields
function clearInputs() {
    document.getElementById('productID').value = '';
    document.getElementById('productName').value = '';
    document.getElementById('productAmount').value = '';
    document.getElementById('productPrice').value = '';
    document.getElementById('productOnHand').value = '';
    document.getElementById('nameSearchInput1').value = '';
}


function uploadDataInStock() {
    // Check if there's any product data in stock
    if (!stock || stock.length === 0) {
        alert("No data to upload.");
        return;
    }

    // Convert stock array to a JSON string
    const stockData = JSON.stringify(stock);

    // Debug log to ensure stockData is properly initialized
    console.log("Sending stock data:", stockData);

    // Send the stock data to the server using an AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "salesandinvoicing.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        // Check if the request was successful
        if (xhr.status === 200) {
            // Parse the JSON response directly
            let response;
            try {
                response = JSON.parse(xhr.responseText); // Parse the JSON response
            } catch (e) {
                alert("Upload successful! Error: Invalid JSON response");
                clearFormAndTable();
                renderTable();
                return;
            }

            // Directly check for success or error
            if (response.success) {
                alert(response.success); // If success, show the success message
                clearFormAndTable();
                renderTable();
            } else if (response.error) {
                alert(response.error); // If error, show the error message
            }
        } else {
            alert("Error uploading data. Please try again."); // Inform the user of upload failure
        }
    };

    // Send the data in the format of a POST request
    xhr.send("stockData=" + encodeURIComponent(stockData));
}


function clearFormAndTable() {
    // Clear the stock table's tbody content
    const tableBody = document.querySelector('#stockTable tbody');
    tableBody.innerHTML = '';

    // Clear all input fields
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        if (input.type === 'text' || input.type === 'number') {
            input.value = '';
        }
    });

    // Clear the dropdowns
    const dropdowns = document.querySelectorAll('.dropdown-list, .dropdown-list1');
    dropdowns.forEach(dropdown => {
        dropdown.innerHTML = ''; // Clear the dropdown content
    });

    const dropdownInputs = document.querySelectorAll('.dropdown-input, .dropdown-input1');
    dropdownInputs.forEach(input => {
        input.value = ''; // Clear the searchable input
    });

    // Reset the total amount display
    document.getElementById('totalAmount').textContent = '0';
}


// User search dropdown
// Get references to the input and the dropdown list
const nameSearchInput = document.getElementById('nameSearchInput');
const dropdownList = document.getElementById('dropdownList');
const firstNameInput = document.getElementById('firstName'); // Reference to firstName input
const lastNameInput = document.getElementById('lastName'); // Reference to lastName input
const addressInput = document.getElementById('address'); // Reference to address input
const idInput = document.getElementById('customerID'); // Reference to address input

// Event listener for the search input field
nameSearchInput.addEventListener('input', function() {
    const query = nameSearchInput.value;

    if (query.length > 0) {
        fetchUserNames(query);  // Fetch matching names from the server
    } else {
        // Clear all inputs when the search box is cleared
        dropdownList.style.display = 'none'; // Hide the dropdown
        clearUserInputs(); // Clear all input fields
    }
});

// Function to clear user input fields
function clearUserInputs() {
    // Reset all input fields to be empty
    nameSearchInput.value = '';
    dropdownList.value = '';
    firstNameInput.value = '';
    lastNameInput.value = '';
    addressInput.value = '';
    idInput.value = '';
}

// Function to fetch user names from the server based on search query
function fetchUserNames(query) {
    fetch('../controller/fetch_name.php?query=' + query)
        .then(response => response.json())
        .then(data => {
            dropdownList.innerHTML = ''; // Clear previous results

            if (data.length > 0) {
                // Populate the dropdown with matching names
                data.forEach(item => {
                    const listItem = document.createElement('div');
                    listItem.classList.add('dropdown-item');
                    listItem.textContent = item.firstName + ' ' + item.lastName; // Display full name
                    listItem.dataset.nameId = item.id; // Store the ID
                    listItem.dataset.firstName = item.firstName; // Store first name
                    listItem.dataset.lastName = item.lastName; // Store last name
                    listItem.dataset.address = item.address; // Store address

                    // Event listener to handle item selection
                    listItem.addEventListener('click', function() {
                        selectUser(item);
                    });

                    dropdownList.appendChild(listItem);
                });

                dropdownList.style.display = 'block'; // Show the dropdown
            } else {
                dropdownList.style.display = 'none'; // Hide if no matches
            }
        })
        .catch(error => console.error('Error fetching names:', error));
}

// Function to handle user name selection
function selectUser(item) {
    nameSearchInput.value = item.firstName + ' ' + item.lastName; // Display full name in input
    dropdownList.style.display = 'none'; // Hide the dropdown

    // Set the selected name's details in the appropriate input fields
    firstNameInput.value = item.firstName;  // Set first name
    lastNameInput.value = item.lastName;    // Set last name
    addressInput.value = item.address;      // Set address
    idInput.value = item.id; 
}

// Close the user dropdown if the user clicks outside
document.addEventListener('click', function(event) {
    if (!dropdownList.contains(event.target) && event.target !== nameSearchInput) {
        dropdownList.style.display = 'none';
    }
});




// Product search dropdown
// Get references to the input and the dropdown list for the product search
const nameSearchInput1 = document.getElementById('nameSearchInput1');
const dropdownList1 = document.getElementById('dropdownList1');
const productIDInput = document.getElementById('productID'); // Reference to Product ID input
const productNameInput = document.getElementById('productName'); // Reference to Product Name input
const productOnHandInput = document.getElementById('productOnHand'); // Reference to Product OnHand input
const productPriceInput = document.getElementById('productPrice'); // Reference to Product Price input
const productAmountInput = document.getElementById('amount'); // Reference to Product Price input

// Event listener for the search input field
nameSearchInput1.addEventListener('input', function() {
    const query = nameSearchInput1.value;

    if (query.length > 0) {
        fetchProducts(query);  // Fetch matching products from the server
    } else {
        // Clear all inputs when the search box is cleared
        dropdownList1.style.display = 'none'; // Hide the dropdown
        clearProductInputs(); // Clear all input fields
    }
});

// Function to clear the product input fields
function clearProductInputs() {
    productIDInput.value = '';
    productNameInput.value = '';
    productOnHandInput.value = '';
    productPriceInput.value = '';
    productAmountInput.value = '';
}

// Function to fetch products from the server based on the search query
function fetchProducts(query) {
    fetch('../controller/fetch_product.php?query=' + query)
        .then(response => response.json())
        .then(data => {
            dropdownList1.innerHTML = ''; // Clear previous results

            if (data.length > 0) {
                // Populate the dropdown with matching products
                data.forEach(item => {
                    const listItem = document.createElement('div');
                    listItem.classList.add('dropdown-item');
                    listItem.dataset.productID = item.productID; // Store the ID
                    listItem.dataset.productName = item.productName; // Store product name
                    listItem.dataset.productOnHand = item.productOnHand; // Store stock on hand
                    listItem.dataset.productPrice = item.productPrice; // Store price
                    listItem.textContent = item.productName; // Display product name in the dropdown

                    // Event listener to handle item selection
                    listItem.addEventListener('click', function() {
                        selectProduct(item);
                    });

                    dropdownList1.appendChild(listItem);
                });

                dropdownList1.style.display = 'block'; // Show the dropdown
            } else {
                dropdownList1.style.display = 'none'; // Hide if no matches
            }
        })
        .catch(error => console.error('Error fetching products:', error));
}

// Function to handle product selection
function selectProduct(item) {
    nameSearchInput1.value = item.productName; // Display product name in input
    dropdownList1.style.display = 'none'; // Hide the dropdown

    // Set the selected product's details in the appropriate input fields
    productIDInput.value = item.productID;
    productNameInput.value = item.productName;
    productOnHandInput.value = item.productOnHand;
    productPriceInput.value = item.productPrice;
}

// Close the dropdown if the user clicks outside
document.addEventListener('click', function(event) {
    if (!dropdownList1.contains(event.target) && event.target !== nameSearchInput1) {
        dropdownList1.style.display = 'none';
    }
});



// Search function to filter products
function filterInvoices() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const invoices = document.querySelectorAll('.invoice');

    invoices.forEach(invoice => {
        const invoiceName = invoice.querySelector('.invoice-name')?.textContent.toLowerCase() || '';
        const invoiceID = invoice.dataset.id.toLowerCase(); // Access the data-id attribute
        const invoiceClientID = invoice.querySelector('.invoice-clientID')?.textContent.toLowerCase() || '';

        // Check if the search input matches any of the columns
        if (invoiceName.includes(searchInput) || invoiceID.includes(searchInput) || invoiceClientID.includes(searchInput)) {
            invoice.style.display = 'table-row'; // Show the row if there's a match
        } else {
            invoice.style.display = 'none'; // Hide the row if there's no match
        }
    });
}

//delete
function deleteProductInUI(button) {
    const row = button.closest('tr');
    const id = row.dataset.id;

    // Confirm deletion
    const confirmDelete = confirm("Are you sure you want to delete this client?");
    if (!confirmDelete) return;

    // Send AJAX request to delete the user
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "salesandinvoicing.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);

                if (response.success) {
                    alert(response.success);  // Show success message
                    row.remove();  // Remove the row from the UI
                } else if (response.error) {
                    alert(response.error);  // Show error message
                }
            } catch (e) {
                console.error('Error parsing response:', e);
                alert('Unexpected error occurred.');
            }
        }
    };

    xhr.send(`id=${id}`);  // Send ID to PHP for deletion
}

function openInvoiceDetails(invoiceId) {
    const width = 600;
    const height = 400;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;

    // Open a new popup window
    window.open(
        `invoice_details.php?id=${invoiceId}`,
        'InvoiceDetails',
        `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`
    );
}