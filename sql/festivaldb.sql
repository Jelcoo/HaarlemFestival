CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL,
    address VARCHAR(255),
    city VARCHAR(100),
    postal_code VARCHAR(20),
    profile_picture VARCHAR(255)
);

CREATE TABLE invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    paid_status BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    qrcode VARCHAR(255) UNIQUE NOT NULL,
    session DATETIME NOT NULL,
    invoice_id INT NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id)
);

CREATE TABLE pages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    header_text TEXT,
    slug VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_type ENUM('DANCE', 'YUMMY', 'HISTORY') NOT NULL,
    session DATETIME NOT NULL,
    start_time TIME NOT NULL,
    end_date DATE NOT NULL,
    page_id INT NOT NULL,
    FOREIGN KEY (page_id) REFERENCES pages(id)
);

CREATE TABLE events_dance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    artist VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    total_tickets INT NOT NULL,
    tickets_left INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    event_id INT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE events_yummy (
    id INT PRIMARY KEY AUTO_INCREMENT,
    restaurant VARCHAR(100) NOT NULL,
    restaurant_type VARCHAR(50) NOT NULL,
    rating DECIMAL(3,1),
    kids_price DECIMAL(10,2) NOT NULL,
    adult_price DECIMAL(10,2) NOT NULL,
    event_id INT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE events_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    seats_per_tour INT NOT NULL,
    language VARCHAR(50) NOT NULL,
    guide VARCHAR(100) NOT NULL,
    family_price DECIMAL(10,2) NOT NULL,
    single_price DECIMAL(10,2) NOT NULL,
    start_location VARCHAR(255) NOT NULL,
    event_id INT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE tickets_dance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    all_access BOOLEAN DEFAULT FALSE,
    ticket_id INT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events_dance(id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id)
);

CREATE TABLE tickets_yummy (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    kids_count INT NOT NULL DEFAULT 0,
    adult_count INT NOT NULL DEFAULT 0,
    ticket_id INT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events_yummy(id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id)
);

CREATE TABLE tickets_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    total_seats INT NOT NULL,
    family_ticket BOOLEAN DEFAULT FALSE,
    ticket_id INT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events_history(id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id)
);
