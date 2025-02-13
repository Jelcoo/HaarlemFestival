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
    profile_picture VARCHAR(255),
    stripe_customer_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    status VARCHAR(255) DEFAULT 'started',
    stripe_payment_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE locations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    coordinates VARCHAR(255),
    adress TEXT,
    description TEXT
);

CREATE TABLE artists (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    preview_description TEXT,
    main_description TEXT,
    iconic_albums TEXT
);

CREATE TABLE restaurants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    location_id INT,
    name VARCHAR(255) NOT NULL,
    restaurant_type VARCHAR(255),
    rating INT,
    description TEXT,
    menu TEXT,
    FOREIGN KEY (location_id) REFERENCES locations(id)
);

CREATE TABLE dance_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    artist_id INT,
    location_id INT,
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

CREATE TABLE yummy_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    restaurant_id INT,
    kids_price DECIMAL(10,2) NOT NULL,
    adult_price DECIMAL(10,2) NOT NULL,
    vat DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL,
    start_time TIME NOT NULL,
    start_date DATE NOT NULL,
    end_time TIME NOT NULL,
    end_date DATE NOT NULL,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

CREATE TABLE history_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    seats_per_tour INT NOT NULL,
    language VARCHAR(255) NOT NULL,
    guide VARCHAR(255),
    family_price DECIMAL(10,2) NOT NULL,
    single_price DECIMAL(10,2) NOT NULL,
    vat DECIMAL(10,2) NOT NULL,
    start_location VARCHAR(255),
    start_time TIME NOT NULL,
    start_date DATE NOT NULL,
    end_time TIME NOT NULL,
    end_date DATE NOT NULL
);

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

CREATE TABLE assets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    filename VARCHAR(255) NOT NULL,
    mimetype VARCHAR(100) NOT NULL,
    size INT NOT NULL,
    model VARCHAR(100) NOT NULL,
    model_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
