CREATE DATABASE IF NOT EXISTS penyelenggaraan;
USE penyelenggaraan;

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    role ENUM('adminA','adminB')
);

INSERT INTO admin (username, password, role) VALUES 
('admina', 'admin123', 'adminA'),
('adminb', 'admin123', 'adminB');

CREATE TABLE penyelenggaraan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    juruteknik VARCHAR(100),
    tarikh DATE,
    peralatan TEXT,
    status VARCHAR(50) DEFAULT 'Belum Disahkan'
);
