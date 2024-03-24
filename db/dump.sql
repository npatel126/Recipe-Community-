USE `rc`;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    UNIQUE (username)
);


-- Example add admin user.
INSERT INTO users (username, name, password) VALUES ('admin', 'admin', '$2y$10$54/Xfan9DY9UK0CPH60d6uuYkHkK10gERf6hI9dyIpM25.CPd/rcq');
-- Test user for Mia
INSERT INTO users (username, name, password) VALUES ('miazine', 'Mia', '$2y$10$L9UsivvQlLjsKzUrrHplEevdcDNiN.aDZ05Yt.DbqcFkbJKyn/wMa');
-- Test user for Phil
INSERT INTO users (username, name, password) VALUES ('philbert', 'Phil', '$2y$10$X/1njNRoPduetVO4ZS4WMeG2pNDIQgNYYoDnXgrhRL9SxnkJofPiO');

CREATE TABLE recipes (
    recipe_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    description TEXT,
    category VARCHAR(20),
    ingredients TEXT,
    instructions TEXT,
    prep_time INT,
    cook_time INT,
    total_time INT,
    servings INT,
    creator_id INT,
    FOREIGN KEY (creator_id) REFERENCES users(user_id)
);
-- Example recipe.
INSERT INTO recipes (title, description, category, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id)
VALUES (
    'Classic Tomato Basil Pasta',
    'A light and refreshing pasta dish with ripe tomatoes, fresh basil, and garlic.',
    'Main Course',
    '200g spaghetti, 4 ripe tomatoes (diced), 3 cloves garlic (minced), 1/4 cup extra-virgin olive oil, Fresh basil leaves (chopped), Salt and pepper (to taste)',
    '1. Cook the spaghetti in salted boiling water until al dente. Reserve some pasta water.
        2. While the pasta cooks, heat olive oil in a skillet over medium heat.
        3. Add minced garlic and sauté until fragrant (about 1-2 minutes).
        4. Add the diced tomatoes and cook until they release their juices (about 5 minutes).
        5. Season with salt and pepper.
        6. Toss the cooked spaghetti into the tomato mixture, adding a splash of reserved pasta water if needed.
        7. Remove from heat and stir in the chopped fresh basil.
        8. Serve hot, garnished with grated Parmesan cheese if desired.',
    10, 20, 30, 2, 1
);

-- Example recipe 2 (contains "tomato" in title to display multiple results)
-- Example recipe 3 (just another recipe)
