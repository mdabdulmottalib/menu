<?php include '../includes/config.php'; checkLogin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="flex">
        <div class="w-1/4 h-screen bg-gray-800 text-white p-4">
            <h1 class="text-2xl">Admin Dashboard</h1>
            <ul class="mt-4">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="all_menus.php">All Menus</a></li>
                <li><a href="../views/logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="w-3/4 p-4">
            <h2 class="text-xl">Welcome to the Dashboard</h2>
        </div>
    </div>
</body>
</html>
