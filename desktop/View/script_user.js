let stock = []; // Ensure stock array is defined

function addUser() {
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const address = document.getElementById('address').value.trim();
    const phoneNumber = document.getElementById('phoneNumber').value.trim();
    const userName = document.getElementById('userName').value.trim();
    const password = document.getElementById('password').value;

    if (firstName === '' || lastName === '' || address === '' || isNaN(phoneNumber) || userName === '' || password === '') {
        alert('Please enter valid user details.');
        return;
    }

    stock.push({ firstName, lastName, address, phoneNumber, userName, password });
    renderTable();
    clearInputs();
}

// Function to render the table (as previously provided)
function renderTable() {
    const tbody = document.getElementById('User').querySelector('tbody');
    tbody.innerHTML = '';
    stock.forEach((item, index) => {
        const row = tbody.insertRow();
        row.insertCell(0).textContent = index + 1;
        row.insertCell(1).textContent = item.firstName;
        row.insertCell(2).textContent = item.lastName;
        row.insertCell(3).textContent = item.address;
        row.insertCell(4).textContent = item.phoneNumber;
        row.insertCell(5).textContent = item.userName;
        row.insertCell(6).textContent = item.password;
        const actionCell = row.insertCell(7);
        const updateBtn = document.createElement('button');
        updateBtn.textContent = 'Update';
        updateBtn.className = 'update-btn';
        updateBtn.onclick = () => updateUserInStock(index);
        const deleteBtn = document.createElement('button');
        deleteBtn.textContent = 'Delete';
        deleteBtn.className = 'delete-btn';
        deleteBtn.onclick = () => deleteUserInStock(index);
        actionCell.appendChild(updateBtn);
        actionCell.appendChild(deleteBtn);
    });
    document.getElementById('submitButton').disabled = stock.length === 0;
}

// Clear inputs after submission (function should be defined)
function clearInputs() {
    document.getElementById('firstName').value = '';
    document.getElementById('lastName').value = '';
    document.getElementById('address').value = '';
    document.getElementById('phoneNumber').value = '';
    document.getElementById('userName').value = '';
    document.getElementById('password').value = '';
}


// Update a product in the stock array
function updateUserInStock(index) {
    const newfirstName = prompt('Enter new first name:', stock[index].firstName);
    const newlastName = prompt('Enter new last name:', stock[index].lastName);
    const newAddress = prompt('Enter new address:', stock[index].address);
    const newphoneNumber = prompt('Enter new phoneNumber:', stock[index].phoneNumber);
    const newuserName = prompt('Enter new userName:', stock[index].userName);
    const newpassword = prompt('Enter new password:', stock[index].password);

    // Check if all inputs are valid
    if (newfirstName && newlastName && newAddress && !isNaN(newphoneNumber) && newuserName && newpassword) {
        stock[index].firstName = sanitizeInput(newfirstName);
        stock[index].lastName = sanitizeInput(newlastName);
        stock[index].address = sanitizeInput(newAddress);
        stock[index].phoneNumber = parseInt(newphoneNumber);
        stock[index].userName = sanitizeInput(newuserName);
        stock[index].password = newpassword.trim();
        renderTable();
    } else {
        alert('Invalid input. Update cancelled.');
    }
}

// Delete a product from the stock array
function deleteUserInStock(index) {
    if (confirm('Are you sure you want to delete this user?')) {
        stock.splice(index, 1);
        renderTable();
    }
}

// Function to upload data (send stock data to the server)
function uploadDataInStock() {
    // Check if there's any product data in stock
    if (stock.length === 0) {
        alert("No users to upload.");
        return;
    }

    // Add the group as 'client' to each user in the stock array
    stock = stock.map(user => ({ ...user, group: 'user' }));

    // Convert stock array to a JSON string
    const stockData = JSON.stringify(stock);

    // Send the stock data to the server using an AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "usersmanagement.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        // Check if the request was successful
        if (xhr.status === 200) {

            // Parse the JSON response directly (without try-catch)
            const response = JSON.parse(xhr.responseText); // Parse the JSON response
        
            // Directly check for success or error
            if (response.success) {
                alert(response.success);  // If success, show the success message
                clearAllData();
            } else if (response.error) {
                alert(response.error);  // If error, show the error message
            }
        } else {
            alert("Error uploading data. Please try again.");  // Inform the user of upload failure
        }
        
    };

    console.log("Sending stock data:", stockData); // Debug log
    // Send the data in the format of a POST request
    xhr.send("stockData=" + encodeURIComponent(stockData));
}



// Function to filter user based on search input
function filterUsers() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();  // Get the value from the input field
    const users = document.querySelectorAll('.user');  // Select all rows with the class 'product'

    users.forEach(user => {
        const userfirstName = user.querySelector('.user-firstName').textContent.toLowerCase();  // Get the product name
        const userlastName = user.querySelector('.user-lastName').textContent.toLowerCase();
        const userAddress = user.querySelector('.user-address').textContent.toLowerCase();
        const userPhone = user.querySelector('.user-phone').textContent.trim();
        const useruserName = user.querySelector('.user-userName').textContent.toLowerCase();
        if (userfirstName.includes(searchInput) || userlastName.includes(searchInput) || userAddress.includes(searchInput) || userPhone.includes(searchInput) || useruserName.includes(searchInput)) {  // If the product name contains the search input
            user.style.display = 'table-row';  // Show the product row
        } else {
            user.style.display = 'none';  // Hide the product row
        }
    });
}


function sanitizeInput(data) {
    return data.trim(); // Simply trims whitespace from the beginning and end
}

// Update a product (UI update first)
function updateUserInUI(button) {
    const row = button.closest('tr');
    const id = row.dataset.id;

    // Get current product details from the row
    const firstName = row.querySelector('.user-firstName').textContent;
    const lastName = row.querySelector('.user-lastName').textContent;
    const address = row.querySelector('.user-address').textContent;
    const phoneNumber = row.querySelector('.user-phone').textContent;
    const userName = row.querySelector('.user-userName').textContent;
    const password = row.querySelector('.user-password').textContent;

    console.log("Current values:", firstName, lastName, address, phoneNumber, userName, password);

    // Prompt the user for new values
    const newfirstName = prompt("Enter new firstName:", firstName);
    const newlastName = prompt("Enter new lastName:", lastName);
    const newAddress = prompt("Enter new address:", address);
    const newphoneNumber = prompt("Enter new phoneNumber:", phoneNumber);
    const newuserName = prompt("Enter new userName:", userName);
    const newPassword = prompt("Enter new password:", '');

    console.log("New values:", newfirstName, newlastName, newAddress, newphoneNumber, newuserName, newPassword);

    // Only check for invalid input when the user has changed any of the values
    if ((newfirstName !== firstName || newlastName !== lastName || newAddress !== address || newphoneNumber !== phoneNumber || newuserName !== userName || newPassword !== password) && 
        (!newfirstName || !newlastName || !newAddress || !newphoneNumber || !newuserName || !newPassword)) {
        alert("Invalid input. Update canceled.");
        return; // Exit the function if invalid input
    }

    // Sanitize the inputs before updating
    const sanitizedFirstName = sanitizeInput(newfirstName);
    const sanitizedLastName = sanitizeInput(newlastName);
    const sanitizedAddress = sanitizeInput(newAddress);
    const sanitizedphoneNumber = sanitizeInput(newphoneNumber);
    const sanitizeduserName = sanitizeInput(newuserName);

    // Update the UI immediately with the sanitized values
    row.querySelector('.user-firstName').textContent = sanitizedFirstName;
    row.querySelector('.user-lastName').textContent = sanitizedLastName;
    row.querySelector('.user-address').textContent = sanitizedAddress;
    row.querySelector('.user-phone').textContent = sanitizedphoneNumber;// No need to sanitize phone number here
    row.querySelector('.user-userName').textContent = sanitizeduserName;
    row.querySelector('.user-password').textContent = newPassword;

    // Send AJAX request to update the database with the new values
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "usersmanagement.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert("Users updated successfully!");
        } else {
            alert("Error updating users.");
        }
    };

    xhr.send(`id=${id}&firstName=${sanitizedFirstName}&lastName=${sanitizedLastName}&address=${sanitizedAddress}&phoneNumber=${sanitizedphoneNumber}&userName=${sanitizeduserName}&password=${newPassword}`);
}


function deleteUserInUI(button) {
    const row = button.closest('tr');
    const id = row.dataset.id;

    // Confirm deletion
    const confirmDelete = confirm("Are you sure you want to delete this user?");
    if (!confirmDelete) return;

    // Send AJAX request to delete the user
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "usersmanagement.php", true);
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

function clearAllData() {
    // Clear all input fields
    document.getElementById('firstName').value = '';
    document.getElementById('lastName').value = '';
    document.getElementById('address').value = '';
    document.getElementById('phoneNumber').value = '';
    document.getElementById('userName').value = '';
    document.getElementById('password').value = '';

    // Clear the search input field
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.value = '';
    }

    // Clear the table
    const tbody = document.getElementById('User').querySelector('tbody');
    tbody.innerHTML = '';


    // Clear the stock array
    stock = [];

    // Disable the submit button if no data is present
    document.getElementById('submitButton').disabled = true;

}