<?php
$dashboardCards = [
    [
        'title' => 'User Management',
        'description' => 'Manage all user accounts on your platform. Add, edit, or delete users and assign roles for different levels of access.',
        'url' => '/dashboard/users',
    ],
    [
        'title' => 'Restaurant Management',
        'description' => 'Organize and manage your restaurant listings. Add, edit, or remove restaurant information, including menus and hours.',
        'url' => '/dashboard/restaurants',
    ],
    [
        'title' => 'Location Management',
        'description' => 'Manage the locations where your services are available. Add new locations, edit details, or remove locations.',
        'url' => '/dashboard/locations',
    ],
    [
        'title' => 'Artist Dashboard',
        'description' => 'Manage and view all your artists in one place. Easily update artist information, add new artists, or delete existing ones.',
        'url' => '/dashboard/artists',
    ],
];
?>

<div class="container-fluid">
    <h2>Dashboard Overview</h2>

    <!-- Dynamic Cards -->
    <div class="row">
        <?php foreach ($dashboardCards as $card) { ?>
            <?php include __DIR__ . '/../../../components/dashboard/dashboard_card.php'; ?>
        <?php } ?>
    </div>
</div>
