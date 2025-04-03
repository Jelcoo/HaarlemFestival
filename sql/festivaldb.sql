CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone_number VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'user',
    address VARCHAR(255),
    city VARCHAR(255),
    postal_code VARCHAR(255),
    stripe_customer_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `phone_number`, `password`, `role`, `address`, `city`, `postal_code`, `stripe_customer_id`) VALUES
(1, 'John', 'Doe', 'johndoe@example.com', NULL, '$2a$12$I03L/LUh1SntONPFOVwz3eivdVa1O.hna9GFmfDbbGO/22imeOoR.', 'admin', NULL, NULL, NULL, NULL);

CREATE TABLE invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    status VARCHAR(255) DEFAULT 'started',
    stripe_payment_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
INSERT INTO `invoices` (`id`, `user_id`, `status`, `stripe_payment_id`, `created_at`, `completed_at`) VALUES
(1, 1, 'started', 'pi_123xyz', '2025-03-06 10:52:27', NULL);

CREATE TABLE locations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    event_type VARCHAR(255) NOT NULL,
    coordinates VARCHAR(255),
    address TEXT,
    preview_description TEXT,
    main_description TEXT
);
INSERT INTO `locations` (`id`, `name`, `event_type`, `coordinates`, `address`, `preview_description`, `main_description`) VALUES
(1, 'Café de Roemer', 'yummy', '52.379876689846675,4.631872280758655', 'Botermarkt 17, 2011 XL Haarlem', 'A Haarlem favorite for over 30 years, Café de Roemer offers a menu with both classic and innovative dishes Relax on the sunny terrace or in the cozy glass conservatory, perfect for any weather Whether for lunch, dinner, or drinks, enjoy great food and warm hospitality!', 'Indulge in a festival menu designed to meet your preferences, delivering exceptional flavor and quality. Discover the creativity of Café de Roemer with this carefully crafted dining experience, featuring fresh, seasonal ingredients and a celebration of Dutch and European cuisine. Perfect for food lovers seeking something special during the festival.'),
(2, 'Ratatouille', 'yummy', '52.37868858869996,4.63750189518844', 'Spaarne 96, 2011 CL Haarlem', 'Ratatouille Food and Wine in Haarlem, led by chef Jozua Jaring, offers a refined dining experience with dishes like Holstein tartar and Langoustine, paired with exclusive wines Perfect for any occasion, the restaurant combines innovative flavors and exceptional hospitality for a memorable culinary journey.', 'Indulge in a festival menu designed to meet your preferences, delivering exceptional flavor and quality. Discover the creativity of Café de Roemer with this carefully crafted dining experience, featuring fresh, seasonal ingredients and a celebration of Dutch and European cuisine. Perfect for food lovers seeking something special during the festival.'),
(3, 'Restaurant ML', 'yummy', '52.380961905388105,4.638467714257977', 'Kleine Houtstraat 70, 2011 DR Haarlem', 'Restaurant ML in Haarlem, awarded a Michelin star, offers bold dishes by chef Mark Gratama in a modern setting with an open kitchen The menu blends French and international flavors, complemented by a curated wine list from sommelier Tim Jesse.', NULL),
(4, 'Restaurant Fris', 'yummy', '52.37224930133032,4.634197557490366', 'Twijnderslaan 7, 2012 BG Haarlem', 'Restaurant Fris in Haarlem offers a relaxed fine-dining experience, blending French and Asian cuisines The menu is a playful exploration of bold flavors, with a team dedicated to surprising guests with fresh, innovative dishes Enjoy a high-quality dining experience in a welcoming atmosphere.', NULL),
(5, 'New Vegas', 'yummy', '52.3811093974921,4.634921260543867', 'Koningstraat 5, 2011 TB Haarlem', 'New Vegas, Haarlem\'s first vegan restaurant, offers creative twists on familiar dishes using seasonal, plant-based ingredients. Known for its 3D-printed steak and innovative menu, it provides a unique dining experience with vegan sides and bites in a welcoming atmosphere for all to enjoy sustainable, delicious food.', NULL),
(6, 'Grand Cafe Brinkmann', 'yummy', '52.381650756067195,4.636149155178292', 'Grote Markt 13, 2011 RC Haarlem', 'Grand Café Brinkmann offers a cozy and welcoming atmosphere in Haarlem, where guests can enjoy delicious dishes and a relaxed ambiance With a varied menu and the option to rent rooms for special events, it is the perfect place for both a casual meal and a special occasion.', NULL),
(7, 'Urban Frenchy Bistro Toujours', 'yummy', '52.380669602787556,4.637055187351411', 'Oude Groenmarkt 10-12, 2011 HL Haarlem, Nederland', 'Toujours in Haarlem offers a luxurious private dining experience with a menu featuring truffle, Wagyu, caviar, and sushi. Enjoy cocktails, wine, and beer on the cozy terrace, perfect for an unforgettable meal. Open daily, Toujours is the ideal spot for refined dining with family or friends.', NULL),
(8, 'Lichtfabriek', 'dance', '52.38635032209866,4.651753794055413', 'Minckelersweg 2, 2031 EM Haarlem', 'Located in a historic power station, Lichtfabriek exudes industrial charm and creative energy. Its spacious interiors and captivating ambiance make it an ideal venue for large-scale performances and immersive musical experiences.', NULL),
(9, 'Teylers Museum', 'teylers', '52.380350236629766,4.640344133818465', 'Spaarne 16, 2011 CH Haarlem', 'Discover the Magic@Teylers! Dive into an interactive adventure at Teylers Museum, where kids solve science puzzles and riddles to uncover The Secret of Professor Teyler. From cracking \\\"The Egg Problem\\\" to fixing circuits, this hands-on experience combines fun with learning, making science magical for the whole family!', NULL),
(10, 'Kerk van St Bavo', 'history', '52.38107259154299,4.637333412667616', 'Grote Markt 22, 2011 RD Haarlem', 'A true icon of Haarlem, the Church of St. Bavo is a masterpiece of Gothic architecture and a treasure trove of history. Its towering spire dominates the skyline, while the interior houses the world-famous Müller organ, once played by Mozart. Step inside to admire stunning stained glass, intricate woodwork, and centuries-old gravestones. A must-visit for history buffs and architecture lovers alike!', 'The Church of St. Bavo, also known as the Grote Kerk, has stood at the heart of Haarlem for over 600 years. Construction began in the late 14th century, and the church was completed in the early 16th century, making it a fine example of late Gothic architecture in the Netherlands. Originally built as a Catholic cathedral, the church was dedicated to Saint Bavo, the patron saint of Haarlem and the protector of the city. Its construction symbolized Haarlem’s growing importance during the Middle Ages as a center of trade and religion. During the Protestant Reformation in the 16th century, the Church of St. Bavo transitioned from Catholicism to Protestantism, reflecting the significant religious and political changes sweeping across Europe. This shift led to the removal of Catholic altars, statues, and other decorations, leaving behind a more austere yet equally impressive interior. Despite this transformation, the church retained its grandeur, becoming a cornerstone of Protestant worship and a civic landmark.
The church played a significant role during Haarlem’s Golden Age in the 17th century when the city flourished as a hub of art, culture, and commerce. It witnessed countless historic moments, including royal visits and civic ceremonies. Over the centuries, the church has survived wars, fires, and restorations, emerging as a symbol of resilience and continuity for Haarlem and its people. Today, the Church of St. Bavo is not only a place of worship but also a cultural monument and a tourist destination. Its magnificent Müller Organ, completed in 1738, has attracted world-famous musicians, including Wolfgang Amadeus Mozart, who played the organ at the tender age of 10. The church remains a living testament to Haarlem’s rich history, serving as a gathering place for locals and visitors alike.'),
(11, 'Slachthuis Haarlem', 'dance', '52.373484207668476,4.650625813936793', 'Rockplein 6, 2033 KK Haarlem', 'Once an industrial slaughterhouse, Slachthuis has been transformed into a dynamic cultural hotspot Known for its edgy and raw atmosphere, this venue is a favorite for high-energy performances and underground vibes Its unique architecture creates an unforgettable experience for music lovers.', NULL),
(12, 'Caprera Openluchttheater', 'dance', '52.41115015210989,4.608279150557901', 'Hoge Duin en Daalseweg 2, 2061 AG Bloemendaal', 'Nestled amidst lush greenery, Caprera Openluchttheater is an enchanting open-air venue perfect for unforgettable performances under the stars Its natural acoustics and scenic beauty make it an iconic spot for electronic music and cultural events alike.', NULL),
(13, 'Jopenkerk', 'dance', '52.38121361696195,4.629730994681502', 'Gedempte Voldersgracht 2, 2011 WD Haarlem', 'A stunning fusion of history and modernity, Jopenkerk is a former church turned brewery and event space With its vibrant atmosphere and excellent acoustics, this venue offers a unique blend of sacred architecture and pulsating beats.', NULL),
(14, 'Puncher Comedy Club', 'dance', '52.38147256162246,4.635383633505596', 'Grote Markt 10, 2011 RD Haarlem', 'Situated in the heart of Haarlem, Puncher Comedy Club combines a cozy setting with electric energy While known for its comedy, it transforms into an intimate and vibrant space for special performances during the festival.', NULL),
(15, 'XO the Club', 'dance', '52.38121402679863,4.635255348119989', 'Grote Markt 8, 2011 RD Haarlem', 'XO the Club is a chic and modern nightlife destination where style meets sound Its sleek interiors and state-of-the-art lighting set the stage for a night of high-energy dance and unforgettable moments.', NULL),
(16, 'Grote Markt', 'history', '52.381330914584794,4.636316340973041', '2011 RD Haarlem', 'At the heart of Haarlem lies the Grote Markt, a bustling square steeped in history and charm. For centuries, it has been the city’s beating heart—a central hub where trade, governance, culture, and community come together. Surrounded by stunning historic buildings, the square offers visitors a glimpse into Haarlem’s rich past and vibrant present.', 'The Grote Markt dates back to the Middle Ages when it served as Haarlem’s central market square. During this time, it was the center of commerce, where merchants traded goods like cloth, fish, and produce. Its strategic location helped Haarlem grow into a prosperous trade city during the Dutch Golden Age. Throughout its history, the Grote Markt has witnessed many significant events, from royal processions to public executions. It was a place where news was shared, justice was served, and celebrations took place. The square has always been a reflection of Haarlem’s dynamic spirit, adapting to the needs of its citizens while retaining its historic charm. Today, the Grote Markt is a lively gathering spot for locals and tourists alike. Weekly markets, seasonal festivals, and cultural events bring the square to life, continuing its centuries-old tradition as a place of connection and community.'),
(17, 'De Hallen', 'history', '52.3811512364665,4.63603884377743', 'Grote Markt 16, 2011 RD Haarlem', NULL, NULL),
(18, 'Proveniershof', 'history', '52.37738423023927,4.631011950297312', 'Grote Houtstraat 142D, 2011 SV Haarlem', NULL, NULL),
(19, 'Jopenkerk', 'history', '52.381345450512335,4.630589805003887', 'Gedempte Voldersgracht 2, 2011 WD Haarlem', NULL, NULL),
(20, 'Waalse Kerk Haarlem', 'history', '52.38248697950012,4.639153299903443', 'Begijnhof 28, 2011 HE Haarlem', NULL, NULL),
(21, 'Molen de Adriaan', 'history', '52.38377593049064,4.642761929374879', 'Papentorenvest 1A, 2011 AV Haarlem', NULL, NULL),
(22, 'Amsterdamse Poort', 'history', '52.38051738005276,4.646598531624926', '2011 BZ Haarlem', NULL, NULL),
(23, 'Hof van Bakenes', 'history', '52.38248911248071,4.640292785411666', '2011 JN Haarlem', NULL, NULL);

CREATE TABLE artists (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    preview_description TEXT,
    main_description TEXT,
    iconic_albums TEXT
);
INSERT INTO `artists` (`id`, `name`, `preview_description`, `main_description`, `iconic_albums`) VALUES
(1, 'Hardwell', 'A powerhouse in the electronic dance music world, Hardwell is known for his explosive live performances and chart-topping tracks. Hailing from Breda, Netherlands, this superstar DJ and producer has dominated global stages with hits like \"Spaceman\" and \"Apollo\". Hardwell\'s blend of big-room house and progressive beats makes him a fan favorite.', NULL, NULL),
(2, 'Armin van Buuren', 'A legend in trance music, Armin van Buuren has been at the forefront of the EDM scene for decades. With five-time DJ Mag\'s \"World’s No. 1 DJ\" titles and iconic tracks like \"This Is What It Feels Like\", Armin has captivated audiences worldwide. His A State of Trance radio show is a lifeline for trance enthusiasts everywhere.', 'Armin van Buuren is a name synonymous with electronic dance music, particularly trance, where he has reigned as a global icon for over two decades. Hailing from Leiden, Netherlands, Armin began producing music in the mid-90s and quickly became a leading force in the genre. Known for his euphoric melodies, emotional depth, and exceptional DJ skills, he has set the standard for trance music worldwide.\r\n\r\nCareer Highlights:\r\nA State of Trance (ASOT): In 2001, Armin launched A State of Trance, a weekly radio show that reaches over 40 million listeners across more than 80 countries. The show has become a cornerstone for trance enthusiasts, showcasing the best in the genre and celebrating milestones with global festivals.\r\nDJ Mag Accolades: Armin has been named the world’s No. 1 DJ five times by DJ Mag’s Top 100 DJs poll (2007-2010, 2012), solidifying his position as a titan in electronic music.\r\nGrammy Nomination: In 2014, he became one of the few electronic artists to earn a Grammy nomination for \"This Is What It Feels Like\" featuring Trevor Guthrie, a track that marked his crossover success.\r\nNotable Albums: His discography includes standout albums like \"76\" (2003), \"Intense\" (2013), and \"Balance\" (2019), showcasing his evolution as an artist while staying true to his trance roots.\r\nMajor Festivals: Armin has headlined some of the world\'s largest festivals, including Tomorrowland, Ultra Music Festival, and EDC, captivating millions with his spellbinding performances.\r\n\r\nIconic Tracks:\r\nArmin’s catalog features legendary tracks that have defined his career and the trance genre. \"Communication\", his early breakthrough hit, became a staple in dance music. \"Shivers\", an emotional anthem, showcased his melodic mastery, while \"In and Out of Love\" (featuring Sharon den Adel) captivated audiences with its heartfelt vocals. Tracks like \"Blah Blah Blah\" and \"This Is What It Feels Like\" highlight his ability to cross genres and reach global audiences without losing his signature sound.\r\nArmin van Buuren is not just a DJ; he’s a storyteller, innovator, and ambassador for electronic music, continually pushing boundaries while uniting fans through the universal language of trance.', '76 (2003) – Armin’s debut album, a landmark release with tracks like “Blue Fear” and “Burned With Desire”, laying the foundation for his career.\r\nShivers (2005) – Featuring a mix of vocal trance and instrumental tracks, this album solidified Armin’s place in the EDM world.\r\nImagine (2008) – His first album to debut at No. 1 in the Netherlands, featuring the hit “In and Out of Love”.\r\nMirage (2010) – A rich, cinematic album with standout tracks like “Not Giving Up on Love” (with Sophie Ellis-Bextor).\r\nIntense (2013) – A Grammy-nominated album that blended orchestral elements with electronic beats, featuring hits like “This Is What It Feels Like”.\r\nBalance (2019) – A double album showcasing Armin’s exploration of diverse electronic styles while staying true to his trance roots.'),
(3, 'Martin Garrix', 'Known for his breakout hit \"Animals\", Martin Garrix became a global sensation as a teenager. Now a staple in the EDM world, the Dutch producer is celebrated for his infectious melodies and collaborations with artists like Dua Lipa, Bebe Rexha, and Khalid. Martin’s energy and passion light up every stage he touches.', NULL, NULL),
(4, 'Tiësto', 'The \"Godfather of EDM,\" Tiësto has redefined the electronic music landscape. From trance beginnings to becoming a global pop-crossover sensation with hits like \"Red Lights\" and \"The Business\", Tiësto’s evolution is legendary. His ability to stay at the forefront of the scene makes him a timeless icon.', 'Tiësto is a trailblazer in electronic dance music, whose career has spanned over two decades and transformed the global EDM scene. Born Tijs Michiel Verwest in Breda, Netherlands, Tiësto began as a trance DJ and producer but has continually reinvented himself, embracing new styles while maintaining his status as a leading figure in dance music. His innovative sound and electrifying performances have earned him fans worldwide and cemented his legacy as one of the greatest DJs of all time.\r\n\r\nCareer Highlights:\r\nTrance Icon to Crossover King: Starting as a trance artist, Tiësto became a household name with legendary sets like his performance at the Athens 2004 Olympics, where he became the first DJ to play at the opening ceremony. He later evolved into a multi-genre producer, pushing boundaries in EDM.\r\nDJ Mag No. 1 DJ: Tiësto topped DJ Mag’s Top 100 DJs poll three times (2002-2004), solidifying his dominance during the early 2000s.\r\nGrammy Recognition: In 2015, Tiësto won a Grammy for his remix of John Legend\'s \"All of Me\", showcasing his versatility and mainstream appeal.\r\nMajor Festivals: A headline act at festivals like Tomorrowland, Ultra Music Festival, and EDC, Tiësto has consistently delivered iconic sets that define the festival experience.\r\nRecord Label Founder: As the founder of Black Hole Recordings and later Musical Freedom, Tiësto has supported the growth of EDM and launched the careers of many rising stars.\r\n\r\nIconic Tracks and Albums:\r\nAlbums: His early albums like \"In My Memory\" (2001) and \"Just Be\" (2004) became trance classics. Later works such as \"Kaleidoscope\" (2009) and \"A Town Called Paradise\" (2014) marked his shift to a more mainstream EDM sound.\r\nTracks: His legendary tracks include \"Adagio for Strings\", a trance anthem; \"Red Lights\", which marked his move into progressive house; \"The Business\", a global hit that introduced him to a new generation of fans; and \"Jackie Chan\", a vibrant collaboration with Post Malone, Dzeko, and Preme.', 'In My Memory (2001) – Tiësto’s debut album, featuring tracks like \"Lethal Industry\" and \"Flight 643\", laid the groundwork for his rise as a trance icon.\r\nJust Be (2004) – Home to anthems like \"Love Comes Again\" and \"Adagio for Strings\", this album solidified his dominance in the trance genre.\r\nElements of Life (2007) – A Grammy-nominated album that combined trance with orchestral influences, including hits like \"Break My Fall\" and \"Carpe Noctum\".\r\nKaleidoscope (2009) – Marking a significant shift in style, this album explored electro and pop influences, featuring collaborations with artists like Nelly Furtado (\"Who Wants to Be Alone\") and Jónsi (\"Kaleidoscope\").\r\nA Town Called Paradise (2014) – With tracks like \"Wasted\" and \"Red Lights\", this album firmly established Tiësto as a crossover EDM artist.\r\nDrive (2023) – Featuring collaborations with artists like Black Eyed Peas and Tate McRae, this album showcases Tiësto’s continued relevance and adaptability in the ever-evolving EDM landscape.'),
(5, 'Nicky Romero', 'A master of progressive house, Nicky Romero burst onto the scene with hits like \"Toulouse\" and \"I Could Be the One\" with Avicii. As a DJ, producer, and label head of Protocol Recordings, he’s recognized for his dynamic sound and mentorship of upcoming artists. His sets are a journey through emotion and rhythm.', NULL, NULL),
(6, 'Afrojack', 'Afrojack is a Grammy-winning DJ and producer renowned for his signature Dutch house sound. Known for tracks like \"Take Over Control\" and \"Ten Feet Tall\", he’s a regular at major festivals worldwide. Afrojack’s collaborations with artists such as Beyoncé and David Guetta underscore his versatility and influence.', NULL, NULL);


CREATE TABLE restaurants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    location_id INT NOT NULL,
    restaurant_type VARCHAR(255),
    rating INT,
    menu TEXT,
    FOREIGN KEY (location_id) REFERENCES locations(id)
);
INSERT INTO `restaurants` (`id`, `location_id`, `restaurant_type`, `rating`, `menu`) VALUES
(1, 1, 'Dutch, Fish and Seafood, European', 4, 'Starter Tomaten Quiche (Vega) Main Course Geroosterde Aubergine (Vega, Allergen-Friendly) Dessert Sinaasappel Bavarois'),
(2, 2, 'French, Fish and Seafood, European', 4, 'Starter Cauliflower Seaweed · Grapefruit · Smoked Almond Main Course Ratatouille Molasses · Gnocchi · Parmesan Dessert Pumpkin Ravioli Cheesecake · Chocolate · Orange' ),
(3, 3, 'Dutch, Fish and Seafood, European', 4, NULL),
(4, 4, 'Dutch, French, European', 4, NULL),
(5, 5, 'Vegan', 3, NULL),
(6, 6, 'Dutch, European, Modern', 3, NULL),
(7, 7, 'Dutch, Fish and Seafood, European', 3, NULL);


CREATE TABLE dance_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    location_id INT NOT NULL,
    total_tickets INT NOT NULL,
    session VARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    vat DECIMAL(10,2) NOT NULL,
    start_time TIME NOT NULL,
    start_date DATE NOT NULL,
    end_time TIME NOT NULL,
    end_date DATE NOT NULL,
    FOREIGN KEY (location_id) REFERENCES locations(id)
);
INSERT INTO `dance_events` (`id`, `location_id`, `total_tickets`, `session`, `price`, `vat`, `start_time`, `start_date`, `end_time`, `end_date`) VALUES
(1, 8, 1500, 'b2b', 61.98, 0.21, '20:00:00', '2025-07-25', '02:00:00', '2025-07-26'),
(2, 11, 200, 'club', 49.59, 0.21, '22:00:00', '2025-07-25', '23:30:00', '2025-07-25'),
(3, 13, 300, 'club', 49.59, 0.21, '20:00:00', '2025-07-25', '21:30:00', '2025-07-25'),
(4, 15, 200, 'club', 49.59, 0.21, '20:00:00', '2025-07-25', '21:30:00', '2025-07-25'),
(5, 14, 200, 'club', 49.59, 0.21, '20:00:00', '2025-07-25', '21:30:00', '2025-07-25'),
(6, 12, 2000, 'b2b', 90.91, 0.21, '14:00:00', '2025-07-26', '23:00:00', '2025-07-26'),
(7, 13, 300, 'club', 49.59, 0.21, '22:00:00', '2025-07-26', '23:30:00', '2025-07-26'),
(8, 8, 1500, 'tiesto_world', 61.98, 0.21, '21:00:00', '2025-07-26', '01:00:00', '2025-07-27'),
(9, 11, 200, 'club', 49.59, 0.21, '23:00:00', '2025-07-26', '00:30:00', '2025-07-27'),
(10, 12, 2000, 'b2b', 90.91, 0.21, '14:00:00', '2025-07-27', '23:00:00', '2025-07-27'),
(11, 13, 300, 'club', 49.59, 0.21, '19:00:00', '2025-07-27', '20:30:00', '2025-07-27'),
(12, 15, 1500, 'club', 74.38, 0.21, '21:00:00', '2025-07-27', '22:30:00', '2025-07-27'),
(13, 11, 200, 'club', 49.59, 0.21, '18:00:00', '2025-07-27', '19:30:00', '2025-07-27');

CREATE TABLE dance_event_artists (
    event_id INT,
    artist_id INT,
    PRIMARY KEY (event_id, artist_id),
    FOREIGN KEY (event_id) REFERENCES dance_events(id),
    FOREIGN KEY (artist_id) REFERENCES artists(id)
);
INSERT INTO `dance_event_artists` (`event_id`, `artist_id`) VALUES
(3, 1),
(6, 1),
(12, 1),
(4, 2),
(6, 2),
(11, 2),
(5, 3),
(6, 3),
(13, 3),
(2, 4),
(8, 4),
(10, 4),
(1, 5),
(9, 5),
(10, 5),
(1, 6),
(7, 6),
(10, 6);

CREATE TABLE yummy_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    restaurant_id INT NOT NULL,
    total_seats INT NOT NULL,
    kids_price DECIMAL(10,2) NOT NULL,
    adult_price DECIMAL(10,2) NOT NULL,
    reservation_cost DECIMAL(10,2) NOT NULL,
    vat DECIMAL(10,2) NOT NULL,
    start_time TIME NOT NULL,
    start_date DATE NOT NULL,
    end_time TIME NOT NULL,
    end_date DATE NOT NULL,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);
INSERT INTO `yummy_events` (`id`, `restaurant_id`, `total_seats`, `kids_price`, `adult_price`, `reservation_cost`, `vat`, `start_time`, `start_date`, `end_time`, `end_date`) VALUES
(1, 1, 35, 17.50, 35.00, 8.26, 0.21, '18:00:00', '2025-07-25', '19:30:00', '2025-07-25'),
(2, 1, 35, 17.50, 35.00, 8.26, 0.21, '19:30:00', '2025-07-25', '21:00:00', '2025-07-25'),
(3, 1, 35, 17.50, 35.00, 8.26, 0.21, '21:00:00', '2025-07-25', '22:30:00', '2025-07-25'),
(4, 1, 35, 17.50, 35.00, 8.26, 0.21, '18:00:00', '2025-07-26', '19:30:00', '2025-07-26'),
(5, 1, 35, 17.50, 35.00, 8.26, 0.21, '19:30:00', '2025-07-26', '21:00:00', '2025-07-26'),
(6, 1, 35, 17.50, 35.00, 8.26, 0.21, '21:00:00', '2025-07-26', '22:30:00', '2025-07-26'),
(7, 1, 35, 17.50, 35.00, 8.26, 0.21, '18:00:00', '2025-07-27', '19:30:00', '2025-07-27'),
(8, 1, 35, 17.50, 35.00, 8.26, 0.21, '19:30:00', '2025-07-27', '21:00:00', '2025-07-27'),
(9, 1, 35, 17.50, 35.00, 8.26, 0.21, '21:00:00', '2025-07-27', '22:30:00', '2025-07-27'),
(10, 2, 52, 22.50, 45.00, 8.26, 0.21, '17:00:00', '2025-07-25', '19:00:00', '2025-07-25'),
(11, 2, 52, 22.50, 45.00, 8.26, 0.21, '19:00:00', '2025-07-25', '21:00:00', '2025-07-25'),
(12, 2, 52, 22.50, 45.00, 8.26, 0.21, '21:00:00', '2025-07-25', '23:00:00', '2025-07-25'),
(13, 2, 52, 22.50, 45.00, 8.26, 0.21, '17:00:00', '2025-07-26', '19:00:00', '2025-07-26'),
(14, 2, 52, 22.50, 45.00, 8.26, 0.21, '19:00:00', '2025-07-26', '21:00:00', '2025-07-26'),
(15, 2, 52, 22.50, 45.00, 8.26, 0.21, '21:00:00', '2025-07-26', '23:00:00', '2025-07-26'),
(16, 2, 52, 22.50, 45.00, 8.26, 0.21, '17:00:00', '2025-07-27', '19:00:00', '2025-07-27'),
(17, 2, 52, 22.50, 45.00, 8.26, 0.21, '19:00:00', '2025-07-27', '21:00:00', '2025-07-27'),
(18, 2, 52, 22.50, 45.00, 8.26, 0.21, '21:00:00', '2025-07-27', '23:00:00', '2025-07-27'),
(19, 3, 60, 22.50, 45.00, 8.26, 0.21, '17:00:00', '2025-07-25', '19:00:00', '2025-07-25'),
(20, 3, 60, 22.50, 45.00, 8.26, 0.21, '19:00:00', '2025-07-25', '21:00:00', '2025-07-25'),
(21, 3, 60, 22.50, 45.00, 8.26, 0.21, '17:00:00', '2025-07-26', '19:00:00', '2025-07-26'),
(22, 3, 60, 22.50, 45.00, 8.26, 0.21, '19:00:00', '2025-07-26', '21:00:00', '2025-07-26'),
(23, 3, 60, 22.50, 45.00, 8.26, 0.21, '17:00:00', '2025-07-27', '19:00:00', '2025-07-27'),
(24, 3, 60, 22.50, 45.00, 8.26, 0.21, '19:00:00', '2025-07-27', '21:00:00', '2025-07-27'),
(25, 3, 45, 22.50, 45.00, 8.26, 0.21, '17:30:00', '2025-07-25', '19:00:00', '2025-07-25'),
(26, 3, 45, 22.50, 45.00, 8.26, 0.21, '19:00:00', '2025-07-25', '20:30:00', '2025-07-25'),
(27, 3, 45, 22.50, 45.00, 8.26, 0.21, '20:30:00', '2025-07-25', '22:00:00', '2025-07-25'),
(28, 3, 45, 22.50, 45.00, 8.26, 0.21, '17:30:00', '2025-07-26', '19:00:00', '2025-07-26'),
(29, 3, 45, 22.50, 45.00, 8.26, 0.21, '19:00:00', '2025-07-26', '20:30:00', '2025-07-26'),
(30, 3, 45, 22.50, 45.00, 8.26, 0.21, '20:30:00', '2025-07-26', '22:00:00', '2025-07-26'),
(31, 3, 45, 22.50, 45.00, 8.26, 0.21, '17:30:00', '2025-07-27', '19:00:00', '2025-07-27'),
(32, 3, 45, 22.50, 45.00, 8.26, 0.21, '19:00:00', '2025-07-27', '20:30:00', '2025-07-27'),
(33, 3, 45, 22.50, 45.00, 8.26, 0.21, '20:30:00', '2025-07-27', '22:00:00', '2025-07-27'),
(34, 4, 36, 17.50, 35.00, 8.26, 0.21, '17:00:00', '2025-07-25', '18:30:00', '2025-07-25'),
(35, 4, 36, 17.50, 35.00, 8.26, 0.21, '18:30:00', '2025-07-25', '20:00:00', '2025-07-25'),
(36, 4, 36, 17.50, 35.00, 8.26, 0.21, '20:00:00', '2025-07-25', '21:30:00', '2025-07-25'),
(37, 4, 36, 17.50, 35.00, 8.26, 0.21, '17:00:00', '2025-07-26', '18:30:00', '2025-07-26'),
(38, 4, 36, 17.50, 35.00, 8.26, 0.21, '18:30:00', '2025-07-26', '20:00:00', '2025-07-26'),
(39, 4, 36, 17.50, 35.00, 8.26, 0.21, '20:00:00', '2025-07-26', '21:30:00', '2025-07-26'),
(40, 4, 36, 17.50, 35.00, 8.26, 0.21, '17:00:00', '2025-07-27', '18:30:00', '2025-07-27'),
(41, 4, 36, 17.50, 35.00, 8.26, 0.21, '18:30:00', '2025-07-27', '20:00:00', '2025-07-27'),
(42, 4, 36, 17.50, 35.00, 8.26, 0.21, '20:00:00', '2025-07-27', '21:30:00', '2025-07-27'),
(43, 5, 100, 17.50, 35.00, 8.26, 0.21, '16:30:00', '2025-07-25', '18:00:00', '2025-07-25'),
(44, 5, 100, 17.50, 35.00, 8.26, 0.21, '18:00:00', '2025-07-25', '19:30:00', '2025-07-25'),
(45, 5, 100, 17.50, 35.00, 8.26, 0.21, '19:30:00', '2025-07-25', '21:00:00', '2025-07-25'),
(46, 5, 100, 17.50, 35.00, 8.26, 0.21, '16:30:00', '2025-07-26', '18:00:00', '2025-07-26'),
(47, 5, 100, 17.50, 35.00, 8.26, 0.21, '18:00:00', '2025-07-26', '19:30:00', '2025-07-26'),
(48, 5, 100, 17.50, 35.00, 8.26, 0.21, '19:30:00', '2025-07-26', '21:00:00', '2025-07-26'),
(49, 5, 100, 17.50, 35.00, 8.26, 0.21, '16:30:00', '2025-07-27', '18:00:00', '2025-07-27'),
(50, 5, 100, 17.50, 35.00, 8.26, 0.21, '18:00:00', '2025-07-27', '19:30:00', '2025-07-27'),
(51, 5, 100, 17.50, 35.00, 8.26, 0.21, '19:30:00', '2025-07-27', '21:00:00', '2025-07-27'),
(52, 6, 48, 17.50, 35.00, 8.26, 0.21, '17:30:00', '2025-07-25', '19:00:00', '2025-07-25'),
(53, 6, 48, 17.50, 35.00, 8.26, 0.21, '19:00:00', '2025-07-25', '20:30:00', '2025-07-25'),
(54, 6, 48, 17.50, 35.00, 8.26, 0.21, '20:30:00', '2025-07-25', '22:00:00', '2025-07-25'),
(55, 6, 48, 17.50, 35.00, 8.26, 0.21, '17:30:00', '2025-07-26', '19:00:00', '2025-07-26'),
(56, 6, 48, 17.50, 35.00, 8.26, 0.21, '19:00:00', '2025-07-26', '20:30:00', '2025-07-26'),
(57, 6, 48, 17.50, 35.00, 8.26, 0.21, '20:30:00', '2025-07-26', '22:00:00', '2025-07-26'),
(58, 6, 48, 17.50, 35.00, 8.26, 0.21, '17:30:00', '2025-07-27', '19:00:00', '2025-07-27'),
(59, 6, 48, 17.50, 35.00, 8.26, 0.21, '19:00:00', '2025-07-27', '20:30:00', '2025-07-27'),
(60, 6, 48, 17.50, 35.00, 8.26, 0.21, '20:30:00', '2025-07-27', '22:00:00', '2025-07-27');

CREATE TABLE history_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    seats_per_tour INT NOT NULL,
    language VARCHAR(255) NOT NULL,
    guide VARCHAR(255) NOT NULL,
    family_price DECIMAL(10,2) NOT NULL,
    single_price DECIMAL(10,2) NOT NULL,
    vat DECIMAL(10,2) NOT NULL,
    start_location VARCHAR(255),
    start_time TIME NOT NULL,
    start_date DATE NOT NULL,
    end_time TIME NOT NULL,
    end_date DATE NOT NULL
);
INSERT INTO `history_events` (`id`, `seats_per_tour`, `language`, `guide`, `family_price`, `single_price`, `vat`, `start_location`, `start_time`, `start_date`, `end_time`, `end_date`) VALUES
(1, 12, 'Dutch', 'Jan-Willem', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-24', '12:00:00', '2025-07-24'),
(2, 12, 'English', 'Frederic', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-24', '12:00:00', '2025-07-24'),
(3, 12, 'Dutch', 'Jan-Willem', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-24', '15:00:00', '2025-07-24'),
(4, 12, 'English', 'Frederic', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-24', '15:00:00', '2025-07-24'),
(5, 12, 'English', 'Frederic', 49.59, 14.46, 0.21, 'Bavo Church', '16:00:00', '2025-07-24', '18:00:00', '2025-07-24'),
(6, 12, 'Dutch', 'Jan-Willem', 49.59, 14.46, 0.21, 'Bavo Church', '16:00:00', '2025-07-24', '18:00:00', '2025-07-24'),
(7, 12, 'Dutch', 'Annet', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-25', '12:00:00', '2025-07-25'),
(8, 12, 'Dutch', 'Annet', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-25', '15:00:00', '2025-07-25'),
(9, 12, 'Dutch', 'Annet', 49.59, 14.46, 0.21, 'Bavo Church', '16:00:00', '2025-07-25', '18:00:00', '2025-07-25'),
(10, 12, 'English', 'William', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-25', '12:00:00', '2025-07-25'),
(11, 12, 'English', 'William', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-25', '15:00:00', '2025-07-25'),
(12, 12, 'English', 'William', 49.59, 14.46, 0.21, 'Bavo Church', '16:00:00', '2025-07-25', '18:00:00', '2025-07-25'),
(13, 12, 'Chinese', 'Kim', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-25', '15:00:00', '2025-07-25'),
(14, 12, 'Chinese', 'Kim', 49.59, 14.46, 0.21, 'Bavo Church', '16:00:00', '2025-07-25', '18:00:00', '2025-07-25'),
(15, 12, 'Dutch', 'Annet', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-26', '12:00:00', '2025-07-26'),
(16, 12, 'Dutch', 'Jan Willem', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-26', '12:00:00', '2025-07-26'),
(17, 12, 'Dutch', 'Annet', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-26', '15:00:00', '2025-07-26'),
(18, 12, 'Dutch', 'Jan Willem', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-26', '15:00:00', '2025-07-26'),
(19, 12, 'Dutch', 'Annet', 49.59, 14.46, 0.21, 'Bavo Church', '16:00:00', '2025-07-26', '18:00:00', '2025-07-26'),
(20, 12, 'English', 'Frederic', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-26', '12:00:00', '2025-07-26'),
(21, 12, 'English', 'William', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-26', '12:00:00', '2025-07-26'),
(22, 12, 'English', 'Frederic', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-26', '15:00:00', '2025-07-26'),
(23, 12, 'English', 'William', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-26', '15:00:00', '2025-07-26'),
(24, 12, 'English', 'Frederic', 49.59, 14.46, 0.21, 'Bavo Church', '16:00:00', '2025-07-26', '18:00:00', '2025-07-26'),
(25, 12, 'Chinese', 'Kim', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-26', '15:00:00', '2025-07-26'),
(26, 12, 'Chinese', 'Kim', 49.59, 14.46, 0.21, 'Bavo Church', '16:00:00', '2025-07-26', '18:00:00', '2025-07-26'),
(27, 12, 'Dutch', 'Annet', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-27', '12:00:00', '2025-07-27'),
(28, 12, 'Dutch', 'Jan Willem', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-27', '12:00:00', '2025-07-27'),
(29, 12, 'Dutch', 'Annet', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-27', '15:00:00', '2025-07-27'),
(30, 12, 'Dutch', 'Jan Willem', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-27', '15:00:00', '2025-07-27'),
(31, 12, 'Dutch', 'Lisa', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-27', '15:00:00', '2025-07-27'),
(32, 12, 'Dutch', 'Annet', 49.59, 14.46, 0.21, 'Bavo Church', '16:00:00', '2025-07-27', '18:00:00', '2025-07-27'),
(33, 12, 'English', 'Deirdre', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-27', '12:00:00', '2025-07-27'),
(34, 12, 'English', 'Frederic', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-27', '12:00:00', '2025-07-27'),
(35, 12, 'English', 'Deirdrem', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-27', '15:00:00', '2025-07-27'),
(36, 12, 'English', 'Frederic', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-27', '15:00:00', '2025-07-27'),
(37, 12, 'English', 'William', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-27', '15:00:00', '2025-07-27'),
(38, 12, 'English', 'Deirdre', 49.59, 14.46, 0.21, 'Bavo Church', '16:00:00', '2025-07-27', '18:00:00', '2025-07-27'),
(39, 12, 'Chinese', 'Kim', 49.59, 14.46, 0.21, 'Bavo Church', '10:00:00', '2025-07-27', '12:00:00', '2025-07-27'),
(40, 12, 'Chinese', 'Kim', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-27', '15:00:00', '2025-07-27'),
(41, 12, 'Chinese', 'Susan', 49.59, 14.46, 0.21, 'Bavo Church', '13:00:00', '2025-07-27', '15:00:00', '2025-07-27');

CREATE TABLE dance_tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dance_event_id INT,
    invoice_id INT,
    all_access BOOLEAN DEFAULT false,
    qrcode VARCHAR(255),
    ticket_used BOOLEAN DEFAULT false,
    FOREIGN KEY (dance_event_id) REFERENCES dance_events(id),
    FOREIGN KEY (invoice_id) REFERENCES invoices(id)
);
INSERT INTO `dance_tickets` (`id`, `dance_event_id`, `invoice_id`, `all_access`, `qrcode`, `ticket_used`) VALUES
(1, 1, 1, 1, 'QR123DANCE', 0),
(2, 2, 1, 0, 'QR456DANCE', 1),
(3, 3, 1, 1, 'QR789DANCE', 0);

CREATE TABLE yummy_tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    yummy_event_id INT,
    invoice_id INT,
    kids_count INT DEFAULT 0,
    adult_count INT DEFAULT 0,
    note TEXT,
    qrcode VARCHAR(255),
    ticket_used BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (yummy_event_id) REFERENCES yummy_events(id),
    FOREIGN KEY (invoice_id) REFERENCES invoices(id)
);
INSERT INTO `yummy_tickets` (`id`, `yummy_event_id`, `invoice_id`, `kids_count`, `adult_count`, `note`, `qrcode`, `ticket_used`) VALUES
(1, 1, 1, 2, 2, NULL, 'QR123YUMMY', 0),
(2, 2, 1, 1, 3, NULL, 'QR456YUMMY', 1),
(3, 3, 1, 0, 4, NULL, 'QR789YUMMY', 0);

CREATE TABLE history_tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id INT,
    history_event_id INT,
    total_seats INT NOT NULL,
    family_ticket BOOLEAN DEFAULT FALSE,
    qrcode VARCHAR(255),
    ticket_used BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (history_event_id) REFERENCES history_events(id),
    FOREIGN KEY (invoice_id) REFERENCES invoices(id)
);
INSERT INTO `history_tickets` (`id`, `invoice_id`, `history_event_id`, `total_seats`, `family_ticket`, `qrcode`, `ticket_used`) VALUES
(1, 1, 1, 4, 1, 'QR123HISTORY', 0),
(2, 1, 2, 2, 0, 'QR456HISTORY', 1),
(3, 1, 3, 5, 1, 'QR789HISTORY', 0);

CREATE TABLE assets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    collection VARCHAR(255) NOT NULL,
    filepath VARCHAR(255) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    mimetype VARCHAR(255) NOT NULL,
    size INT NOT NULL,
    model VARCHAR(255),
    model_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO assets (`id`, `collection`, `filepath`, `filename`, `mimetype`, `size`, `model`, `model_id`) VALUES
(1, 'cover', 'assets/img/artists/cover', 'hardwell.png', 'image/png', 311704, 'App\\Models\\Artist', 1),
(2, 'cover', 'assets/img/artists/cover', 'armin.png', 'image/png', 331035, 'App\\Models\\Artist', 2),
(3, 'cover', 'assets/img/artists/cover', 'martin.png', 'image/png', 269175, 'App\\Models\\Artist', 3),
(4, 'cover', 'assets/img/artists/cover', 'tiesto.png', 'image/png', 332987, 'App\\Models\\Artist', 4),
(5, 'cover', 'assets/img/artists/cover', 'nicky.png', 'image/png', 270514, 'App\\Models\\Artist', 5),
(6, 'cover', 'assets/img/artists/cover', 'afrojack.png', 'image/png', 278148, 'App\\Models\\Artist', 6),
(7, 'cover', 'assets/img/events', 'dance.png', 'image/png', 1166415, NULL, NULL),
(8, 'cover', 'assets/img/events', 'history.png', 'image/png', 827269, NULL, NULL),
(9, 'cover', 'assets/img/events/slider', 'dance.png', 'image/png', 686420, NULL, NULL),
(10, 'cover', 'assets/img/events/slider', 'history.png', 'image/png', 707075, NULL, NULL),
(11, 'cover', 'assets/img/events/slider', 'teylers.png', 'image/png', 686674, NULL, NULL),
(12, 'cover', 'assets/img/events/slider', 'yummy.png', 'image/png', 805206, NULL, NULL),
(13, 'cover', 'assets/img/events', 'teylers.png', 'image/png', 256627, NULL, NULL),
(14, 'cover', 'assets/img/events', 'yummy.png', 'image/png', 937557, NULL, NULL),
(15, 'cover', 'assets/img/locations/cover', 'amsterdamsepoort.png', 'image/png', 374472, 'App\\Models\\Location', 22),
(16, 'cover', 'assets/img/locations/cover', 'caprera.jpg', 'image/jpeg', 422800, 'App\\Models\\Location', 12),
(17, 'cover', 'assets/img/locations/cover', 'dehallen.png', 'image/png', 406354, 'App\\Models\\Location', 17),
(18, 'cover', 'assets/img/locations/cover', 'grotemarkt.png', 'image/png', 335336, 'App\\Models\\Location', 16),
(19, 'cover', 'assets/img/locations/cover', 'hofvanbakenes.png', 'image/png', 437900, 'App\\Models\\Location', 23),
(20, 'cover', 'assets/img/locations/cover', 'jopenkerk-2.png', 'image/png', 390841, 'App\\Models\\Location', 19),
(21, 'cover', 'assets/img/locations/cover', 'jopenkerk.jpg', 'image/jpeg', 407051, 'App\\Models\\Location', 13),
(22, 'cover', 'assets/img/locations/cover', 'lichtfabriek.jpg', 'image/jpeg', 174155, 'App\\Models\\Location', 8),
(23, 'cover', 'assets/img/locations/cover', 'molendeadriaan.png', 'image/png', 346793, 'App\\Models\\Location', 21),
(24, 'cover', 'assets/img/locations/cover', 'proveniershof.png', 'image/png', 422859, 'App\\Models\\Location', 18),
(25, 'cover', 'assets/img/locations/cover', 'puncher.jpg', 'image/jpeg', 110529, 'App\\Models\\Location', 14),
(26, 'cover', 'assets/img/locations/cover', 'slachthuis.jpg', 'image/jpeg', 100646, 'App\\Models\\Location', 11),
(27, 'cover', 'assets/img/locations/cover', 'stbavo.png', 'image/png', 412680, 'App\\Models\\Location', 10),
(28, 'cover', 'assets/img/locations/cover', 'waalsekerk.png', 'image/png', 322239, 'App\\Models\\Location', 20),
(29, 'cover', 'assets/img/locations/cover', 'xo.jpg', 'image/jpeg', 2196793, 'App\\Models\\Location', 15),
(30, 'cover', 'assets/img/restaurants/cover', 'roemer.png', 'image/png', 11118, 'App\\Models\\Restaurant', 1),
(31, 'cover', 'assets/img/restaurants/cover', 'ratatouille.png', 'image/png', 12743, 'App\\Models\\Restaurant', 2),
(32, 'cover', 'assets/img/restaurants/cover', 'ml.png', 'image/png', 18393, 'App\\Models\\Restaurant', 3),
(33, 'cover', 'assets/img/restaurants/cover', 'fris.png', 'image/png', 58155, 'App\\Models\\Restaurant', 4),
(34, 'cover', 'assets/img/restaurants/cover', 'newvegas.png', 'image/png', 26943, 'App\\Models\\Restaurant', 5),
(35, 'cover', 'assets/img/restaurants/cover', 'brinkmann.png', 'image/png', 10472, 'App\\Models\\Restaurant', 6),
(36, 'cover', 'assets/img/restaurants/cover', 'toujours.png', 'image/png', 15977, 'App\\Models\\Restaurant', 7),
(37, 'icon', 'assets/img/icons', 'icon1.png', 'image/png', 8863, 'App\\Models\\Restaurant', 1),
(38, 'icon', 'assets/img/icons', 'icon2.png', 'image/png', 7347, 'App\\Models\\Restaurant', 2),
(39, 'icon', 'assets/img/icons', 'icon3.png', 'image/png', 12765, 'App\\Models\\Restaurant', 3),
(40, 'icon', 'assets/img/icons', 'icon4.png', 'image/png', 10794, 'App\\Models\\Restaurant', 4),
(41, 'icon', 'assets/img/icons', 'icon5.png', 'image/png', 6945, 'App\\Models\\Restaurant', 5),
(42, 'icon', 'assets/img/icons', 'icon6.png', 'image/png', 16982, 'App\\Models\\Restaurant', 6),
(43, 'icon', 'assets/img/icons', 'icon7.png', 'image/png', 7486, 'App\\Models\\Restaurant', 7),
(44, 'extra', 'assets/img/artists/extra', 'tiesto_live_1.png', 'image/png', 483624, 'App\\Models\\Artist', 4),
(45, 'extra', 'assets/img/artists/extra', 'tiesto_live_2.png', 'image/png', 501421, 'App\\Models\\Artist', 4),
(46, 'album', 'assets/img/artists/album', 'tiesto_in_my_memory.png', 'image/png', 353293, 'App\\Models\\Artist', 4),
(47, 'album', 'assets/img/artists/album', 'tiesto_just_be.png', 'image/png', 217603, 'App\\Models\\Artist', 4),
(48, 'album', 'assets/img/artists/album', 'tiesto_elements_of_life.png', 'image/png', 320117, 'App\\Models\\Artist', 4),
(49, 'album', 'assets/img/artists/album', 'tiesto_kaleidoscope.png', 'image/png', 346395, 'App\\Models\\Artist', 4),
(50, 'album', 'assets/img/artists/album', 'tiesto_town_called_paradise.png', 'image/png', 294538, 'App\\Models\\Artist', 4),
(51, 'album', 'assets/img/artists/album', 'tiesto_drive.png', 'image/png', 307915, 'App\\Models\\Artist', 4),
(52, 'extra', 'assets/img/artists/extra', 'armin_live_1.png', 'image/png', 570017, 'App\\Models\\Artist', 2),
(53, 'extra', 'assets/img/artists/extra', 'armin_live_2.png', 'image/png', 425362, 'App\\Models\\Artist', 2),
(54, 'album', 'assets/img/artists/album', 'armin_76.png', 'image/png', 132262, 'App\\Models\\Artist', 2),
(55, 'album', 'assets/img/artists/album', 'armin_shivers.png', 'image/png', 101604, 'App\\Models\\Artist', 2),
(56, 'album', 'assets/img/artists/album', 'armin_imagine.png', 'image/png', 32076, 'App\\Models\\Artist', 2),
(57, 'album', 'assets/img/artists/album', 'armin_mirage.png', 'image/png', 229527, 'App\\Models\\Artist', 2),
(58, 'album', 'assets/img/artists/album', 'armin_intense.png', 'image/png', 86617, 'App\\Models\\Artist', 2),
(59, 'album', 'assets/img/artists/album', 'armin_balance.png', 'image/png', 84392, 'App\\Models\\Artist', 2),
(60, 'header', 'assets/img/artists/header', 'armin_header.png', 'image/png', 84392, 'App\\Models\\Artist', 2),
(61, 'header', 'assets/img/artists/header', 'tiesto_header.png', 'image/png', 84392, 'App\\Models\\Artist', 4),
(62, 'extra', 'assets/img/restaurants/extra', 'roemer_extra1.png', 'image/png', 11118, 'App\\Models\\Restaurant', 1),
(63, 'extra', 'assets/img/restaurants/extra', 'roemer_extra2.png', 'image/png', 11118, 'App\\Models\\Restaurant', 1),
(64, 'logo', 'assets/img/restaurants/logo', 'roemer.png', 'image/png', 11118, 'App\\Models\\Restaurant', 1),
(65, 'header', 'assets/img/restaurants/header', 'roemer_header.png', 'image/png', 11118, 'App\\Models\\Restaurant', 1),
(66, 'header', 'assets/img/restaurants/header', 'ratatouille_header.png', 'image/png', 11118, 'App\\Models\\Restaurant', 2),
(67, 'extra', 'assets/img/restaurants/extra', 'ratatouille_extra1.png', 'image/png', 11118, 'App\\Models\\Restaurant', 2),
(68, 'extra', 'assets/img/restaurants/extra', 'ratatouille_extra2.png', 'image/png', 11118, 'App\\Models\\Restaurant', 2),
(69, 'logo', 'assets/img/restaurants/logo', 'ratatouille.png', 'image/png', 11118, 'App\\Models\\Restaurant', 2);


CREATE TABLE carts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cart_id INT NOT NULL,
    event_model VARCHAR(255) NOT NULL,
    event_id INT NOT NULL,
    note TEXT,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE
);

CREATE TABLE cart_item_quantities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cart_item_id INT NOT NULL,
    type VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (cart_item_id) REFERENCES cart_items(id) ON DELETE CASCADE
);
