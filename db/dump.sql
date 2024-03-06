USE `rc`;

CREATE TABLE `test` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(100) DEFAULT NULL,
    `body` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO rc.test(title, body)
VALUES('This is a test','This is placeholder text!');

CREATE TABLE Users (
    username VARCHAR(50) PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    UNIQUE (username)
);

INSERT INTO Users (username, name, password) VALUES ('admin', 'admin', '$2y$10$54/Xfan9DY9UK0CPH60d6uuYkHkK10gERf6hI9dyIpM25.CPd/rcq');
