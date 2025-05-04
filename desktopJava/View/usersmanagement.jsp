<%@ include file="../view/header.jsp" %>

<h1>Users Management System</h1>
<div class="container">
    <h4>Add New User</h4>
    <form id="addUserForm">
        <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
        <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
        <input type="text" id="address" name="address" placeholder="Address" required>
        <input type="number" id="phoneNumber" name="phoneNumber" placeholder="Phone Number" required>
        <input type="text" id="userName" name="userName" placeholder="Username" required>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <button type="button" onclick="addUser()">Add in Table</button>
        <button type="button" id="submitButton" onclick="uploadDataInStock()">Upload Data</button>
    </form>

    <table id="User">
        <thead>
            <tr>
                <th>No</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Username</th>
                <th>Password</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- User data will appear here -->
        </tbody>
    </table>
</div>



