USE `rc`;

-- Allow for "fancy" characters
SET NAMES 'utf8mb4';

-- Create tables

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    UNIQUE (username)
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
    kitchen_id INT,
    name VARCHAR(50) NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES users(user_id),
    FOREIGN KEY (kitchen_id) REFERENCES kitchens(kitchen_id)
);

CREATE TABLE recipes (
    recipe_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
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
    cookbook_id INT,
    FOREIGN KEY (cookbook_id) REFERENCES cookbooks(cookbook_id),
    FOREIGN KEY (creator_id) REFERENCES users(user_id)
);

CREATE TABLE favorites (
    favorite_id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    recipe_id INT NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES users(user_id),
    FOREIGN KEY (recipe_id) REFERENCES recipes(recipe_id)
);

-- Example/Testing Data entry

-- Example add admin user.
INSERT INTO users (username, name, password) VALUES ('admin', 'admin', '$2y$10$54/Xfan9DY9UK0CPH60d6uuYkHkK10gERf6hI9dyIpM25.CPd/rcq');
-- Test user for Mia
INSERT INTO users (username, name, password) VALUES ('miazine', 'Mia', '$2y$10$L9UsivvQlLjsKzUrrHplEevdcDNiN.aDZ05Yt.DbqcFkbJKyn/wMa');
-- Test user for Phil
INSERT INTO users (username, name, password) VALUES ('philbert', 'Phil', '$2y$10$X/1njNRoPduetVO4ZS4WMeG2pNDIQgNYYoDnXgrhRL9SxnkJofPiO');

-- Kitchens testing
INSERT INTO kitchens (owner_id, name) VALUES (2, "Personal");
INSERT INTO kitchens (owner_id, name) VALUES (2, "For blog");

-- Cookbooks testing
INSERT INTO cookbooks (owner_id, kitchen_id, name) VALUES (2, 1, "Breakfast");
INSERT INTO cookbooks (owner_id, kitchen_id, name) VALUES (2, 2, "Lunch");
INSERT INTO cookbooks (owner_id, kitchen_id, name) VALUES (2, 1, "Dinner");
INSERT INTO cookbooks (owner_id, name) VALUES (2, "Desert");

-- Example recipe.
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Classic Tomato Basil Pasta',
    'A light and refreshing pasta dish with ripe tomatoes, fresh basil, and garlic.',
    'Main Course',
    'Italian',
    '200g spaghetti|4 ripe tomatoes (diced)|3 cloves garlic (minced)|1/4 cup extra-virgin olive oil|Fresh basil leaves (chopped)|Salt and pepper (to taste)',
    '1. Cook the spaghetti in salted boiling water until al dente. Reserve some pasta water.|2. While the pasta cooks, heat olive oil in a skillet over medium heat.|3. Add minced garlic and sauté until fragrant (about 1-2 minutes).|4. Add the diced tomatoes and cook until they release their juices (about 5 minutes).|5. Season with salt and pepper.|6. Toss the cooked spaghetti into the tomato mixture, adding a splash of reserved pasta water if needed.|7. Remove from heat and stir in the chopped fresh basil.|8. Serve hot, garnished with grated Parmesan cheese if desired.',
    10, 20, 30, 2, 1, 2
);

-- Example recipe 2 (contains "tomato" in title to display multiple results)
-- Source: https://cooking.nytimes.com/recipes/1020272-tomato-bruschetta
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Tomato Bruschetta',
    'A light Italian appetizer.',
    'Appetizer',
    'Italian',
    '1 lbs tomatoes|1 teaspon kosher salt|5 tablespoons extra-virgin olive oil|2 large garlic cloves (minced)|8 large basil leaves|grilled/toasted bread (for serving)',
    '1. Core and chop the tomatoes, then transfer to a colander over a bowl or in the sink. Add the salt and gently stir. Let drain for up to 2 hours.|2. Meanwhile, make the garlic oil: In a small saucepan, warm the olive oil and garlic over low heat until the garlic is softened and fragrant, about 5 minutes, making sure the garlic doesn’t brown. Set aside to cool.|3. Roll the basil leaves up and thinly slice crosswise.|4. When the oil is cool and the tomatoes are well drained, combine the tomatoes, garlic oil and basil in a medium bowl. Season with additional salt, to taste. Spoon over toasted bread.',
    130, 20, 150, 4, 3, 2
);

-- Example recipe 3 (just another recipe)
-- Source: https://www.twopeasandtheirpod.com/pomegranate-white-chocolate-chunk-cookies/
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Pomegranate White Chocolate Chunk Cookies',
    'Delicious oatmeal cookies with pomegranate and white chocolate.',
    'Dessert',
    'Unknown',
    '1/2 cup unsalted butter (room temp)|1/2 cup light brown sugar|1/2 cup granulated sugar|1 large egg|1 teaspoon vanilla extract|1 1/4 cup all purpose flour|1/2 teaspoon baking powder|1/2 teaspoon baking soda|1/4 teaspoon salt|1 cup oats|1 cup white chocolate chunks|1 cup pomegranate arils',
    '1. Preheat the oven to 375 degrees F. Line a large baking sheet with parchment paper or a silicone baking mat and set aside.|2. In the bowl of a stand mixer, cream butter and sugars together until smooth. Add the egg and vanilla extract and mix until well combined.|3. In a separate bowl whisk together flour, baking powder, baking soda, and salt. Slowly add flour mixture to the wet ingredients. Mix until just incorporated.|4. Stir in the oats and white chocolate chunks. Make dough balls-about 1 tablespoon of dough per cookie. Tuck about 6-8 pomegranate arils in each cookie dough ball. Bake cookies for 10-12 minutes, until the cookies are golden brown. Remove from oven and let cool on baking sheet for two minutes. Transfer to a wire rack to finish cooling.',
    30, 10, 40, 30, 2, 3
);

-- Test recipe
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id)
Values('Test', "Test recipe", "Testing", "QA", "1 Test data|2 Test code|3 Test users", "1 Test prep|2 Test work|3 Test consume", 0, 0, 0, 0, 1);

-- Favorites testing
INSERT INTO favorites (owner_id, recipe_id) VALUES (2, 1);
INSERT INTO favorites (owner_id, recipe_id) VALUES (2, 2);

