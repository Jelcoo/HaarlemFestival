<?php

$artists = [
    [
        'name' => 'Hardwell',
        'image' => '/assets/img/artists/hardwell.png',
        'description' => 'A powerhouse in the electronic dance music world, Hardwell is known for his explosive live performances and chart-topping tracks. Hailing from Breda, Netherlands, this superstar DJ and producer has dominated global stages with hits like "Spaceman" and "Apollo". Hardwell\'s blend of big-room house and progressive beats makes him a fan favorite.',
        'link' => '/dance/hardwell'
    ],
    [
        'name' => 'Armin van Buuren',
        'image' => '/assets/img/artists/armin.png',
        'description' => 'A legend in trance music, Armin van Buuren has been at the forefront of the EDM scene for decades. With five-time DJ Mag\'s "World’s No. 1 DJ" titles and iconic tracks like "This Is What It Feels Like", Armin has captivated audiences worldwide. His A State of Trance radio show is a lifeline for trance enthusiasts everywhere.',
        'link' => '/dance/armin'
    ],
    [
        'name' => 'Martin Garrix',
        'image' => '/assets/img/artists/martin.png',
        'description' => 'Known for his breakout hit "Animals", Martin Garrix became a global sensation as a teenager. Now a staple in the EDM world, the Dutch producer is celebrated for his infectious melodies and collaborations with artists like Dua Lipa, Bebe Rexha, and Khalid. Martin’s energy and passion light up every stage he touches.',
        'link' => '/dance/martin'
    ],
    [
        'name' => 'Tiësto',
        'image' => '/assets/img/artists/tiesto.png',
        'description' => 'The "Godfather of EDM," Tiësto has redefined the electronic music landscape. From trance beginnings to becoming a global pop-crossover sensation with hits like "Red Lights" and "The Business", Tiësto’s evolution is legendary. His ability to stay at the forefront of the scene makes him a timeless icon.',
        'link' => '/dance/tiesto'
    ],
    [
        'name' => 'Nicky Romero',
        'image' => '/assets/img/artists/nicky.png',
        'description' => 'A master of progressive house, Nicky Romero burst onto the scene with hits like "Toulouse" and "I Could Be the One" with Avicii. As a DJ, producer, and label head of Protocol Recordings, he’s recognized for his dynamic sound and mentorship of upcoming artists. His sets are a journey through emotion and rhythm.',
        'link' => '/dance/nicky'
    ],
    [
        'name' => 'Afrojack',
        'image' => '/assets/img/artists/afrojack.png',
        'description' => 'Afrojack is a Grammy-winning DJ and producer renowned for his signature Dutch house sound. Known for tracks like "Take Over Control" and "Ten Feet Tall", he’s a regular at major festivals worldwide. Afrojack’s collaborations with artists such as Beyoncé and David Guetta underscore his versatility and influence.',
        'link' => '/dance/afrojack'
    ]
];
?>

<div class="container-fluid">
    <div class="row header-section">
        <div class="col-md-6 header-content">
            <h1>DANCE! - OVERVIEW</h1>
            <p>Get ready to experience the electrifying pulse of Haarlem's dance scene! From world-renowned DJs in spectacular Back2Back sets to intimate experimental sessions in iconic venues, <strong>DANCE!</strong> is your ultimate destination for house, techno, and trance. This is more than music – it’s a celebration of rhythm, energy, and connection.</p>
            <p><strong>Dates:</strong> July 25 – 27, 2025</p>
        </div>
        <div class="col-md-6 header-image"></div>
    </div>
</div>

<div class="container artist-grid">
    <?php $count = 0; ?>
    <?php foreach ($artists as $artist) { ?>
        <?php if ($count % 3 == 0) { ?>
            <div class="row">
        <?php } ?>

        <div class="col-md-4 artist-card">
            <img src="<?php echo $artist['image']; ?>" alt="<?php echo $artist['name']; ?>">
            <h3><?php echo $artist['name']; ?></h3>
            <p><?php echo $artist['description']; ?></p>
            <a href="<?php echo $artist['link']; ?>" class="btn btn-custom-yellow">Visit website</a>
        </div>

        <?php $count++; ?>
        <?php if ($count % 3 == 0 || $count == count($artists)) { ?>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<style>
    .header-section {
        display: flex;
        align-items: center;
        background-color: #3D6F4D;
        color: white;
        padding: 50px;
    }
    .header-content {
        flex: 1;
        padding-right: 30px;
    }
    .header-image {
        flex: 1;
        background: url('/assets/img/events/slider/dance.png') no-repeat center center;
        background-size: cover;
        min-height: 400px;
    }
    .header-content h1 {
        font-weight: bold;
    }
    .header-content p {
        margin-bottom: 20px;
    }
    .header-content strong {
        font-weight: bold;
    }
    .artist-grid {
        padding: 50px 0;
    }
    .artist-card {
        text-align: center;
        margin-bottom: 30px;
    }
    .artist-card img {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }
    .artist-card button {
        margin-top: 10px;
        background-color: #D4A055;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
    }
</style>
