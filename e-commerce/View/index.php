<?php
    session_start();
    // Set default values for session variables if not set
    if (!isset($_SESSION['loggedIn'])) {
        $_SESSION['loggedIn'] = false; // Default to not logged in
    }

    if (!isset($_SESSION['username'])) {
        $_SESSION['username'] = ''; // Default to empty username
    }

    if (!isset($_SESSION['user_id'])) {
        $_SESSION['user_id'] = ''; // Default to empty user ID if not set
    }

    // Simulate login process (you can replace this with real authentication logic)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Assume successful login
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $user['lpa_user_username'];  // Assuming $user is the authenticated user data
        $_SESSION['user_id'] = $user['lpa_user_id'];  // Assuming you set the user ID here during login
    }
?>

<?php
    require 'header.php';
?>

<?php
    // Check and display any session messages
    if (isset($_SESSION['error_message'])) {
        echo "<div class='alert error'>{$_SESSION['error_message']}</div>";
        unset($_SESSION['error_message']);
    }

    if (isset($_SESSION['success_message'])) {
        echo "<div class='alert success'>{$_SESSION['success_message']}</div>";
        unset($_SESSION['success_message']);
    }
?>    
    <!-- Main Content Area -->

    <main>
        <div class="home">
            <h2>Welcome to Logic Peripherals Australia (LPA)</h2>
        </div>
        <div class="view">
            <img src="../images/750583-Product-0-I-638518254628316387.webp" alt="Description of the image" width="300" height="300">
            <img src="../images/747954-Product-1-I-638567925047048852.webp" alt="Description of the image" width="300" height="300">
            <img src="../images/621648-Product-0-I-638175490217635087_04ba7953-51ff-4bb7-8ba8-a04a9ae502d2_600x600.webp" alt="Description of the image" width="300" height="300">
            <img src="../images/659599-Product-0-I-638321436007672697_6581a147-b324-4695-a2e5-fd10fd483753_600x600.webp" alt="Description of the image" width="300" height="300">
            <img src="../images/627009-Product-0-I-638175583208622675_b3cdd2c8-8d97-48ae-81e1-6e0b40fedee0_600x600.webp" alt="Description of the image" width="300" height="300">
            <img src="../images/669333-Product-1-I-638435636418752590.webp" alt="Description of the image" width="300" height="300">
        </div>

        <div class="description">
            <h2>What are the best Windows laptops?</h2>
            <p>
                The good news is you don’t need to wait until Windows 11 is released to buy a Windows laptop that will run the new software. So, now’s the perfect time to take advantage of some big brand Windows laptops at Market Laptops. When you buy a big brand, you know you’re getting quality, reliability, and a heap of the latest features. We’ve got all the big brand Windows laptops instore and online, including:
            </p>
            <ul>
                <li>Microsoft</li>
                <li>Asus</li>
                <li>Dell</li>
                <li>HP</li>
                <li>Lenovo</li>
                <li>MSI</li>
            </ul>
            <p>
                Our Microsoft range of Windows laptops is one of the biggest going around. That means great deals on Windows 11-ready Microsoft Surface laptops such as:
            </p>
            <ul>
                <li>Surface</li>
                <li>Surface Pro X</li>
                <li>Surface Pro</li>
                <li>Surface Studio</li>
                <li>Surface Go</li>
                <li>Surface Book</li>
            </ul>
            <h2>When is Windows 11 coming out?</h2>
            <p>
                Windows 11 is expected to be released from about October 2021, but not every Windows laptop and PC will be updated at the same time. The rollout is tipped to go into 2022, with timing likely to vary depending on the device you have. But that doesn’t mean you have to wait until then to get your hands on a Windows laptop. If you buy a compatible Windows laptop today, with Windows 10 installed, Windows 11 will be updated when it’s released. Details on compatibility are below.
            </p>
            <h2>Can I download Windows 11 for free?</h2>
            <p>If your Windows laptop has the latest version of Windows 10 on it, or if you buy one that has, then Windows 11 will be updated for free when its released. But that’s assuming your laptop or PC is eligible for the new operating system. While most Windows laptops available at Market Loptops will be eligible, it’s always good to check. Your laptop, Xbox, or PC will need:</p>
            <ul>
                <li>1GHz or faster processor</li>
                <li>4GB of RAM</li>
                <li>64GB or more storage</li>
                <li>Internet connectivity</li>
                <li>Graphics cars compatible with DirectX 12 or later</li>
                <li>TPM version 2.0</li>
            </ul>
            <p>If you’re buying online, check the Description and Details of the Windows laptop. And if you’re instore, our team members will be able to check for you. You can also download the PC Health Check app to find out more. If you’re buying a Windows laptop with the latest Windows 10 at Market Loptops, you shouldn’t have any dramas when Windows 11 is released.</p>
            <h2>How do you update from Windows 10 to Windows 11?</h2>
            <p>If you have a Windows 10 operating system or Windows 365 subscription, and your Windows laptop or PC is eligible, it should all happen automatically, like any other update. Check to see if you’ve got the latest Windows 10 operating system by clicking on Settings/Windows Update.</p>
            <h2>What are the main features of Windows 11 compared to Windows 10?</h2>
            <p>Windows 11 will have a heap of features, improvements, tools, and apps for your Windows laptop, PC, or Xbox. They include:</p>
            <ul>
                <li>New ways of connecting</li>
                <li>Design focused on creativity</li>
                <li>Productivity tools including Snap Assist and Desktop Groups</li>
                <li>Improved Xbox graphics</li>
            </ul>
            <h2>Can I get the right software for my laptop?</h2>
            <p>The right software is one that works hand-in-hand with Windows 10 and Windows 11 to help you get the most out of your Windows laptop. And you can find it all at Market Loptops. We’ve got the latest Microsoft 365 downloads to help you create quality content and be the master of your day. Choose the right Office 365 deal for you, including:</p>
            <ul>
                <li>Microsoft 365 Personal</li>
                <li>Microsoft 365 Family</li>
                <li>Microsoft Office Home and Student</li>
                <li>Microsoft 365 Business</li>
                <li>Microsoft Office Home and Business</li>
            </ul>
        </div>
    </main>
    <!-- Footer -->
</body>
</html>

<?php
    require 'footer.php';
?>