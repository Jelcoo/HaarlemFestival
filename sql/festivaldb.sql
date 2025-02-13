CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL,
    address VARCHAR(255),
    city VARCHAR(255),
    postal_code VARCHAR(255),
    profile_picture VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    status VARCHAR(255) DEFAULT 'started',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    qrcode VARCHAR(255) UNIQUE NOT NULL,
    session VARCHAR(255) NOT NULL,
    invoice_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    FOREIGN KEY (invoice_id) REFERENCES invoices(id)
);

CREATE TABLE pages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    header_text TEXT,
    slug VARCHAR(255) UNIQUE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    page_id INT NOT NULL,
    type VARCHAR(255) NOT NULL,
    session VARCHAR(255) NOT NULL,
    start DATETIME NOT NULL,
    end DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (page_id) REFERENCES pages(id)
);

CREATE TABLE events_dance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    artist VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    total_tickets INT NOT NULL,
    tickets_left INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE events_yummy (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    restaurant VARCHAR(255) NOT NULL,
    restaurant_type VARCHAR(255) NOT NULL,
    rating DECIMAL(3,1),
    kids_price DECIMAL(10,2) NOT NULL,
    adult_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE events_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    seats_per_tour INT NOT NULL,
    language VARCHAR(255) NOT NULL,
    guide VARCHAR(255) NOT NULL,
    family_price DECIMAL(10,2) NOT NULL,
    single_price DECIMAL(10,2) NOT NULL,
    start_location VARCHAR(255) NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE tickets_dance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_id INT NOT NULL,
    event_id INT NOT NULL,
    all_access BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (event_id) REFERENCES events_dance(id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id)
);

CREATE TABLE tickets_yummy (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_id INT NOT NULL,
    event_id INT NOT NULL,
    kids_count INT NOT NULL DEFAULT 0,
    adult_count INT NOT NULL DEFAULT 0,
    FOREIGN KEY (event_id) REFERENCES events_yummy(id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id)
);

CREATE TABLE tickets_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_id INT NOT NULL,
    event_id INT NOT NULL,
    total_seats INT NOT NULL,
    family_ticket BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (event_id) REFERENCES events_history(id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id)
);
