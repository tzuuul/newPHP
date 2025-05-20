<?php
session_start();

// Prevent caching of this page
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Block access if not logged in
if (!isset($_SESSION["admin"])) {
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin-dashboard.css" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="manage-customers.php">Manage Customers</a></li>
            <li><a href="subscription.php">Subscription</a></li>
            <li><a href="equipments.php">Equipments</a></li>
            <li><a href="site-settings.php">Settings</a></li>
            <li><a href="reports.php">Reports</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION["admin"]); ?>!</h1>
            <a class="logout-btn" href="logout.php">Logout</a>
        </div>
    </div>

    <script>
        // Force reload if page is accessed via back/forward navigation (to avoid cached content)
        window.addEventListener("pageshow", function(event) {
            if (event.persisted || (performance.getEntriesByType("navigation")[0].type === "back_forward")) {
                window.location.reload();
            }
        });

        // Prevent navigating back to login page using back button
        history.pushState(null, "", location.href);
        window.addEventListener("popstate", function () {
            history.pushState(null, "", location.href);
        });
    </script>
</body>
</html>
