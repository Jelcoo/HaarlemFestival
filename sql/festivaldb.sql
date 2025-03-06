CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'user',
    address VARCHAR(255),
    city VARCHAR(255),
    postal_code VARCHAR(255),
    stripe_customer_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO users (firstname, lastname, email, password, role, address, city, postal_code, stripe_customer_id)
VALUES ('John', 'Doe', 'john.doe@example.com', 'hashedpassword123', 'user', '123 Main St', 'New York', '10001', 'cus_123abc');


CREATE TABLE invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    status VARCHAR(255) DEFAULT 'started',
    stripe_payment_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
INSERT INTO invoices (user_id, status, stripe_payment_id)
VALUES (1, 'started', 'pi_123xyz');

CREATE TABLE locations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    coordinates VARCHAR(255),
    adress TEXT,
    preview_description TEXT,
    main_description TEXT
);

INSERT INTO locations (
    name, coordinates, adress, preview_description, main_description
) VALUES 
('Lichtfabriek', '3,3', 'Minckelersweg 2, 2031 EM Haarlem', 'leeg', 'leeg'),
('Slachthuis', '3,3', 'Rockplein 6, 2033 KK Haarlem', 'leeg', 'leeg'),
('Jopenkerk', '3,3', 'Gedempte Voldersgracht 2, 2011 WD Haarlem', 'leeg', 'leeg'),
('XO the Club', '3,3', 'Grote Markt 8, 2011 RD Haarlem', 'leeg', 'leeg'),
('Puncher Comedy Club', '3,3', 'Grote Markt 10, 2011 RD Haarlem','leeg', 'leeg'),
('Caprera Openluchttheater', '3,3', 'Hoge Duin en Daalseweg 2, 2061 AG Bloemendaal', 'leeg', 'leeg'),
('Café de Roemer', '3,3', 'Botermarkt 17, 2011 XL Haarlem', 'leeg', 'leeg'),
('Ratatouille', '3,3', 'Spaarne 96, 2011 CL Haarlem, Nederland', 'leeg', 'leeg'),
('Restaurant ML', '3,3', 'Kleine Houtstraat 70, 2011 DR Haarlem, Nederland', 'leeg', 'leeg'),
('Restaurant Fris', '3,3', 'Twijnderslaan 7, 2012 BG Haarlem, Nederland', 'leeg', 'leeg'),
('New Vegas', '3,3', 'Koningstraat 5, 2011 TB Haarlem','leeg', 'leeg'),
('Grand Cafe Brinkman', '3,3', 'Grote Markt 13, 2011 RC Haarlem, Nederland', 'leeg', 'leeg'),
('Urban Frenchy Bistro Toujours', '3,3', 'Oude Groenmarkt 10-12, 2011 HL Haarlem, Nederland','leeg', 'leeg');


CREATE TABLE artists (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    preview_description TEXT,
    main_description TEXT,
    iconic_albums TEXT
);
INSERT INTO artists (
    name, preview_description, main_description, iconic_albums
) VALUES 
('Nicky Romero', 'leeg', 'leeg', 'leeg'),
('Afrojack', 'leeg', 'leeg', 'leeg'),
('Tiësto', 'leeg', 'leeg', 'leeg'),
('Hardwell', 'leeg', 'leeg', 'leeg'),
('Armin van Buuren', 'leeg', 'leeg', 'leeg'),
('Martin Garrix', 'leeg', 'leeg', 'leeg');


CREATE TABLE restaurants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    location_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    restaurant_type VARCHAR(255),
    rating INT,
    preview_description TEXT,
    main_description TEXT,
    menu TEXT,
    FOREIGN KEY (location_id) REFERENCES locations(id)
);
INSERT INTO restaurants (
    location_id, name, restaurant_type, rating, preview_description, main_description, menu
) VALUES 
(7, 'Café de Roemer', 'Dutch, fish and seafood, European', 4, 
 'leeg', 
 'leeg', 
 'leeg'),

(8, 'Ratatouille', 'French, fish and seafood, European', 4, 
  'leeg', 
 'leeg', 
 'leeg'),

(9, 'Restaurant ML', 'Dutch, fish and seafood, European', 4, 
  'leeg', 
 'leeg', 
 'leeg'),

(10, 'Restaurant Fris', 'Dutch, French, European', 4, 
  'leeg', 
 'leeg', 
 'leeg'),

(11, 'New Vegas', 'Veganistisch ', 3, 
 'leeg', 
 'leeg', 
 'leeg'),

(12, 'Grand Cafe Brinkman', 'Dutch, European, Modern', 3, 
 'leeg', 
 'leeg', 
 'leeg'),

(13, 'Urban Frenchy Bistro Toujours', 'Dutch, fish and seafood, European', 3, 
  'leeg', 
 'leeg', 
 'leeg');


CREATE TABLE dance_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    artist_id INT NOT NULL,
    location_id INT NOT NULL,
    total_tickets INT NOT NULL,
    session VARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    vat DECIMAL(10,2) NOT NULL,
    start_time TIME NOT NULL,
    start_date DATE NOT NULL,
    end_time TIME NOT NULL,
    end_date DATE NOT NULL,
    FOREIGN KEY (artist_id) REFERENCES artists(id),
    FOREIGN KEY (location_id) REFERENCES locations(id)
);
INSERT INTO dance_events (
    artist_id, location_id, total_tickets, session, price, vat, 
    start_time, start_date, end_time, end_date
) VALUES 
(1, 1, 1500, 'Back2Back', 75.00, 1.21, '20:00:00', '2025-07-25', '04:00:00', '2025-07-26'),
(1, 2, 200, 'Club', 60.00, 1.21, '22:00:00', '2025-07-25', '23:30:00', '2025-07-25'),
(1, 3, 300, 'Club', 60.00, 1.21, '23:00:00', '2025-07-25', '00:30:00', '2025-07-26'),
(1, 4, 200, 'Club', 60.00, 1.21, '22:00:00', '2025-07-25', '23:30:00', '2025-07-25'),
(1, 5, 1500, 'Club', 60.00, 1.21, '22:00:00', '2025-07-25', '23:30:00', '2025-07-25'),
(1, 6, 200, 'Back2Back', 110.00, 1.21, '14:00:00', '2025-07-26', '23:00:00', '2025-07-26'),
(1, 3, 300, 'Club', 60.00, 1.21, '22:00:00', '2025-07-26', '23:30:00', '2025-07-26'),
(1, 1, 200, 'TiëstoWorld', 75.00, 1.21, '21:00:00', '2025-07-26', '01:00:00', '2025-07-27'),
(1, 2, 1500, 'Club', 60.00, 7.50, '23:00:00', '2025-07-26', '00:30:00', '2025-07-27'),
(1, 6, 200, 'Back2Back', 110.00, 6.00, '14:00:00', '2025-07-27', '23:00:00', '2025-07-27'),
(1, 3, 300, 'Club', 60.00, 6.00, '19:00:00', '2025-07-27', '20:30:00', '2025-07-27'),
(1, 4, 200, 'Club', 90.00, 6.00, '21:00:00', '2025-07-27', '22:30:00', '2025-07-27'),
(1, 2, 200, 'Club', 60.00, 6.00, '18:00:00', '2025-07-27', '19:30:00', '2025-07-27');



CREATE TABLE yummy_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    restaurant_id INT NOT NULL,
    total_seats INT NOT NULL,
    kids_price DECIMAL(10,2) NOT NULL,
    adult_price DECIMAL(10,2) NOT NULL,
    vat DECIMAL(10,2) NOT NULL,
    start_time TIME NOT NULL,
    start_date DATE NOT NULL,
    end_time TIME NOT NULL,
    end_date DATE NOT NULL,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

INSERT INTO yummy_events (
    restaurant_id, total_seats, kids_price, adult_price, vat, 
    start_time, start_date, end_time, end_date
) VALUES 
(1, 35, 17.50, 35.00, 1.21, '18:00:00', '2025-07-25', '21:00:00', '2025-07-25'),
(2, 52, 22.50, 45.00, 1.21, '17:00:00', '2025-07-25', '22:00:00', '2025-07-25'),
(3, 60, 22.50, 45.00, 1.21, '17:00:00', '2025-07-25', '20:30:00', '2025-07-25'),
(4, 45, 22.50, 45.00, 1.21, '17:30:00', '2025-07-25', '21:00:00', '2025-07-25'),
(5, 36, 22.50, 35.00, 1.21, '17:00:00', '2025-07-25', '22:00:00', '2025-07-25'),
(6, 100, 17.50, 35.00, 1.21, '16:30:00','2025-07-25', '20:30:00', '2025-07-25'),
(7, 48, 17.50, 35.00, 1.21, '17:30:00', '2025-07-25', '21:45:00', '2025-07-25');

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
INSERT INTO history_events (
    seats_per_tour, language, guide, family_price, single_price, vat, 
    start_location, start_time, start_date, end_time, end_date
) VALUES 
(12, 'English', 'Frederic', 60.00, 17.50, 1.21, 'Bavo Church', '10:00:00', '2025-07-25', '11:30:00', '2025-07-25'),
(12, 'Dutch', 'Jan-Willem', 60.00, 17.50, 1.21, 'Bavo Church', '13:00:00', '2025-07-25', '14:30:00', '2025-07-25'),
(12, 'English', 'Frederic', 60.00, 17.50, 1.21, 'Bavo Church', '16:00:00', '2025-07-25', '17:30:00', '2025-07-25'),

(12, 'Dutch', 'Annet', 60.00, 17.50, 1.21, 'Bavo Church', '10:00:00', '2025-07-26', '11:30:00', '2025-07-26'),
(12, 'English', 'Williams', 60.00, 17.50, 1.21, 'Bavo Church', '13:00:00', '2025-07-26', '14:30:00', '2025-07-26'),
(12, 'Chinese', 'Kim', 60.00, 17.50, 1.21, 'Bavo Church', '16:00:00', '2025-07-26', '17:30:00', '2025-07-26'),

(12, 'Dutch', 'Annet', 60.00, 17.50, 1.21, 'Bavo Church', '10:00:00', '2025-07-27', '11:30:00', '2025-07-27'),
(12, 'Dutch', 'Jan-Willem', 60.00, 17.50, 1.21, 'Bavo Church', '10:15:00', '2025-07-27', '11:45:00', '2025-07-27'),

(12, 'English', 'Frederic', 60.00, 17.50, 1.21, 'Bavo Church', '13:00:00', '2025-07-27', '14:30:00', '2025-07-27'),
(12, 'English', 'William', 60.00, 17.50, 1.21, 'Bavo Church', '13:15:00', '2025-07-27', '14:45:00', '2025-07-27'),

(12, 'Chinese', 'Kim', 60.00, 17.50, 1.21, 'Bavo Church', '16:00:00', '2025-07-27', '17:30:00', '2025-07-27'),

(12, 'Dutch', 'Lisa', 60.00, 17.50, 1.21, 'Bavo Church', '10:00:00', '2025-07-28', '11:30:00', '2025-07-28'),
(12, 'Dutch', 'Annet', 60.00, 17.50, 1.21, 'Bavo Church', '10:15:00', '2025-07-28', '11:45:00', '2025-07-28'),
(12, 'Dutch', 'Jan-Willem', 60.00, 17.50, 1.21, 'Bavo Church', '10:30:00', '2025-07-28', '12:00:00', '2025-07-28'),

(12, 'English', 'Deirdre', 60.00, 17.50, 1.21, 'Bavo Church', '13:00:00', '2025-07-28', '14:30:00', '2025-07-28'),
(12, 'English', 'Frederic', 60.00, 17.50, 1.21, 'Bavo Church', '13:15:00', '2025-07-28', '14:45:00', '2025-07-28'),
(12, 'English', 'William', 60.00, 17.50, 1.21, 'Bavo Church', '13:30:00', '2025-07-28', '15:00:00', '2025-07-28'),

(12, 'Chinese', 'Kim', 60.00, 17.50, 1.21, 'Bavo Church', '16:00:00', '2025-07-28', '17:30:00', '2025-07-28'),
(12, 'Chinese', 'Susan', 60.00, 17.50, 1.21, 'Bavo Church', '16:15:00', '2025-07-28', '17:45:00', '2025-07-28');


CREATE TABLE history_event_locations (
    history_id INT,
    location_id INT,
    PRIMARY KEY (history_id, location_id),
    FOREIGN KEY (history_id) REFERENCES history_events(id),
    FOREIGN KEY (location_id) REFERENCES locations(id)
);

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
INSERT INTO dance_tickets (dance_event_id, invoice_id, all_access, qrcode, ticket_used)
VALUES 
(1, 1, TRUE, 'QR123DANCE', FALSE),
(2, 1, FALSE, 'QR456DANCE', TRUE),
(3, 1, TRUE, 'QR789DANCE', FALSE);


CREATE TABLE yummy_tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    yummy_event_id INT,
    invoice_id INT,
    kids_count INT DEFAULT 0,
    adult_count INT DEFAULT 0,
    qrcode VARCHAR(255),
    ticket_used BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (yummy_event_id) REFERENCES yummy_events(id),
    FOREIGN KEY (invoice_id) REFERENCES invoices(id)
);
INSERT INTO yummy_tickets (yummy_event_id, invoice_id, kids_count, adult_count, qrcode, ticket_used)
VALUES 
(1, 1, 2, 2, 'QR123YUMMY', FALSE),
(2, 1, 1, 3, 'QR456YUMMY', TRUE),
(3, 1, 0, 4, 'QR789YUMMY', FALSE);

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
INSERT INTO history_tickets (invoice_id, history_event_id, total_seats, family_ticket, qrcode, ticket_used)
VALUES (1, 1, 4, TRUE, 'QR123HISTORY', FALSE),
(1, 2, 2, FALSE, 'QR456HISTORY', TRUE),
(1, 3, 5, TRUE, 'QR789HISTORY', FALSE);



CREATE TABLE assets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    collection VARCHAR(255) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    mimetype VARCHAR(255) NOT NULL,
    size INT NOT NULL,
    model VARCHAR(255) NOT NULL,
    model_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
