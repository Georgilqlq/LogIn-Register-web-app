CREATE TABLE accounts(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL UNIQUE,
    email VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(400) NOT NULL,
    verified TINYINT(1) NOT NULL DEFAULT 0,
);