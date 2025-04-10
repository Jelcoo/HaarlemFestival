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
        'title' => 'Artist Management',
        'description' => 'Manage and view all your artists in one place. Easily update artist information, add new artists, or delete existing ones.',
        'url' => '/dashboard/artists',
    ],
    [
        'title' => 'Dance Events Management',
        'description' => 'Create and manage dance events. Update event details, manage participants, and track performances.',
        'url' => '/dashboard/events/dance',
    ],
    [
        'title' => 'Yummy Events Management',
        'description' => 'Oversee Yummy Events, manage food vendors, and update event information for an organized experience.',
        'url' => '/dashboard/events/yummy',
    ],
    [
        'title' => 'History Events Management',
        'description' => 'Manage historical tours and events. Set tour schedules, languages, guides, and ticket prices.',
        'url' => '/dashboard/events/history',
    ],
];
?>

<div class="container-fluid">
    <h2>Dashboard Overview</h2>

    <!-- Dynamic Cards -->
    <div class="row">
        <?php foreach ($dashboardCards as $card) { ?>
            <?php include __DIR__ . '/../../../components/dashboard/cards/dashboard_card.php'; ?>
        <?php } ?>
    </div>
</div>
