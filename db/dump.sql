USE `rc`;

CREATE TABLE `test` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(100) DEFAULT NULL,
    `body` text DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO rc.test(title, body)
VALUES('This is a test','This is placeholder text!');
