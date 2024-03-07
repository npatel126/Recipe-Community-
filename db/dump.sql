USE `rc`;

CREATE TABLE Users (
    username VARCHAR(50) PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    UNIQUE (username)
);

INSERT INTO Users (username, name, password) VALUES ('admin', 'admin', '$2y$10$54/Xfan9DY9UK0CPH60d6uuYkHkK10gERf6hI9dyIpM25.CPd/rcq');
