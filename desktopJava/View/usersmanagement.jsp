<h1>Users Management System</h1>
<!--Table1-->
<div class="container">
    <h4>Add New User</h4>
    <form id="addUserForm">
        <!--<label for="firstName">First Name:</label>-->
        <input type="text" id="firstName" name="firstName" placeholder="firstName" required>

        <!--<label for="lastName">Last Name:</label>-->
        <input type="text" id="lastName" name="lastName" placeholder="lastName" required>

        <!--<label for="address">Address:</label>-->
        <input type="text" id="address" name="address" placeholder="address" required>

        <!--<label for="phoneNumber">Phone Number</label>-->
        <input type="number" id="phoneNumber" name="phoneNumber" placeholder="phoneNumber" required>

        <!--<label for="userName">Username</label>-->
        <input type="text" id="userName" name="userName" placeholder="userName" required>

        <!--<label for="password">Password</label>-->
        <input type="password" id="password" name="password" placeholder="password" required>

        <!--<label for="confirmPassword">Confirm Password</label>-->
        <!--<input type="password" id="confirmPassword" name="confirmPassword" placeholder="confirmPassword" required>-->

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
            <!-- Stock items will appear here -->
        </tbody>
    </table>
</div>