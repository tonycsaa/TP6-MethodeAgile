CREATE DATABASE IF NOT EXISTS trombinoscope;
USE trombinoscope;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Exemple d'insertion d'un utilisateur
INSERT INTO users (email, password) VALUES ('test@test', 'test');
