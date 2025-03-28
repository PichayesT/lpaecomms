<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Application</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <ul>
            <li><a href="#">Menu</a>
                <ul>
                    <li><a href="#">Stock Management</a></li>
                    <li><a href="#">Sales and Invoicing</a>
                        <ul>
                            <li><a href="#">Invoices</a></li>
                            <li><a href="#">Clients</a></li>
                        </ul>
                    </li>
                    <li><a href="#">User Management</a></li>
                    <li><a href="#">Exit</a></li>
                </ul>
            </li>
            <li><a href="#">Help</a>
                <ul>
                    <li><a href="#">User Guide</a></li>
                    <li><a href="#">About</a></li>
                </ul>
            </li>
        </ul>
    </div>
    
    <div class="content">
        <h1>Welcome to the Web Application</h1>
        <div id="stock-management" class="section">
            <h2>Stock Management</h2>
            <p>Content for stock management goes here...</p>
        </div>
        <div id="sales-invoicing" class="section">
            <h2>Sales and Invoicing</h2>
            <p>Content for sales and invoicing goes here...</p>
        </div>
        <div id="user-management" class="section">
            <h2>User Management</h2>
            <form>
                <label for="userId">User ID</label>
                <input type="text" id="userId" name="userId">
                
                <label for="userName">User Name</label>
                <input type="text" id="userName" name="userName">
                
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName">
                
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName">
                
                <label for="group">Group</label>
                <select id="group" name="group">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="enabled">Enabled</option>
                    <option value="disabled">Disabled</option>
                </select>
                
                <button type="submit">Save</button>
                <button type="button" onclick="findUser()">Find</button>
                <button type="button" onclick="closeForm()">Close</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
