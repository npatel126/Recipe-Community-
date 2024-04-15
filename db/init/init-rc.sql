-- Allow for "fancy" characters
SET NAMES 'utf8mb4';

DROP SCHEMA IF EXISTS rc;
CREATE SCHEMA rc;
USE rc;


-- Create tables

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    darkmode boolean,
    UNIQUE (username)
);

CREATE TABLE recipes (
    recipe_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    category VARCHAR(20),
    cuisine VARCHAR(20),
    ingredients TEXT,
    instructions TEXT,
    prep_time INT,
    cook_time INT,
    total_time INT,
    servings INT,
    creator_id INT,
    FOREIGN KEY (creator_id) REFERENCES users(user_id)
);

CREATE TABLE kitchens (
    kitchen_id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES users(user_id)
);

CREATE TABLE cookbooks (
    cookbook_id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES users(user_id)
);

CREATE TABLE favorites (
    favorite_id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES users(user_id)
);

-- Junction tables
CREATE TABLE kitchens_cookbooks (
    kitchen_id INT,
    cookbook_id INT,
    PRIMARY KEY (kitchen_id, cookbook_id),
    FOREIGN KEY(kitchen_id) REFERENCES kitchens(kitchen_id) ON DELETE CASCADE,
    FOREIGN KEY(cookbook_id) REFERENCES cookbooks(cookbook_id) ON DELETE CASCADE
);

CREATE TABLE cookbooks_recipes (
    cookbook_id INT,
    recipe_id INT,
    PRIMARY KEY(cookbook_id, recipe_id),
    FOREIGN KEY(cookbook_id) REFERENCES cookbooks(cookbook_id) ON DELETE CASCADE,
    FOREIGN KEY(recipe_id) REFERENCES recipes(recipe_id) ON DELETE CASCADE
);

CREATE TABLE favorites_recipes (
    favorite_id INT,
    recipe_id INT,
    PRIMARY KEY(favorite_id, recipe_id),
    FOREIGN KEY(favorite_id) REFERENCES favorites(favorite_id) ON DELETE CASCADE,
    FOREIGN KEY(recipe_id) REFERENCES recipes(recipe_id) ON DELETE CASCADE
);
