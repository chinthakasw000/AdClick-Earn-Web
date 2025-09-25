CREATE DATABASE adclick;
USE adclick;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    coins INT DEFAULT 0,
    blocked TINYINT(1) DEFAULT 0,
    is_admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE secret_codes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE payment_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    amount INT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    details TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE chat_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE ads (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    coins INT NOT NULL,
    link VARCHAR(255) NOT NULL,
    new_tab_link VARCHAR(255) NOT NULL
);

CREATE TABLE gifts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT NOT NULL,
    recipient_email VARCHAR(100) NOT NULL,
    amount INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id)
);

