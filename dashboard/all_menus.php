<?php include '../includes/config.php'; checkLogin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Weekly Menus</title>
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
            <h2 class="text-xl mb-4">All Weekly Menus</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Week</th>
                        <th class="py-2 px-4 border-b">Content</th>
                        <th class="py-2 px-4 border-b">Created Date</th>
                        <th class="py-2 px-4 border-b">Image</th>
                        <th class="py-2 px-4 border-b">PDF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $menu = new Menu();
                    $menus = $menu->getAllMenus();
                    foreach ($menus as $menu) {
                        echo "<tr>
                            <td class='py-2 px-4 border-b'>{$menu['week']}</td>
                            <td class='py-2 px-4 border-b'>{$menu['content']}</td>
                            <td class='py-2 px-4 border-b'>{$menu['created_date']}</td>
                            <td class='py-2 px-4 border-b'><a href='" . BASE_URL . "{$menu['image_path']}'>View Image</a></td>
                            <td class='py-2 px-4 border-b'><a href='" . BASE_URL . "{$menu['pdf_path']}'>View PDF</a></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
