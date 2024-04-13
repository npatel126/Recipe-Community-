USE `rc`;

-- Allow for "fancy" characters
SET NAMES 'utf8mb4';

-- Create tables

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    darkmode boolean,
    UNIQUE (username)
);

CREATE TABLE kitchens (
    kitchen_id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    -- cookbook_id INT,
    -- FOREIGN KEY (cookbook_id) REFERENCES cookbooks(cookbook_id),
    FOREIGN KEY (owner_id) REFERENCES users(user_id)
);

CREATE TABLE cookbooks (
    cookbook_id INT AUTO_INCREMENT PRIMARY KEY,
    kitchen_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    -- recipe_id INT,
    -- FOREIGN KEY (recipe_id) REFERENCES recipe_id(recipe_id),
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

-- Example add admin user. - pwd: pwd
INSERT INTO users (username, name, password, darkmode) VALUES ('admin', 'admin', '$2y$10$54/Xfan9DY9UK0CPH60d6uuYkHkK10gERf6hI9dyIpM25.CPd/rcq', false);

-- Test user for Mia - pwd: webdev
INSERT INTO users (username, name, password, darkmode) VALUES ('miazine', 'Mia', '$2y$10$L9UsivvQlLjsKzUrrHplEevdcDNiN.aDZ05Yt.DbqcFkbJKyn/wMa', true);

-- Test user for Phil - pwd: procook
INSERT INTO users (username, name, password, darkmode) VALUES ('philbert', 'Phil', '$2y$10$X/1njNRoPduetVO4ZS4WMeG2pNDIQgNYYoDnXgrhRL9SxnkJofPiO', false);

-- Test user for Kevyn - pwd: 1234
INSERT INTO users (username, name, password, darkmode) VALUES ('kevynwithay', 'Kevyn', '$2y$10$PMj6ttPrMYCHb4DYmKrlxe4IvYUJedoHA3CjleMeggPNNYudsdyUq', true);

-- Test user for Susan - pwd: zzz
INSERT INTO users (username, name, password, darkmode) VALUES ('SnoozeinSusan', 'Susan', '$2y$10$aB7PWKV8SevspxbZQidwuu2lvsjsIM845HIpcWp.YxcQw/0rNRY6u', false);

-- Kitchens testing
INSERT INTO kitchens (owner_id, name) VALUES (2, "Personal");
INSERT INTO kitchens (owner_id, name) VALUES (2, "For blog");

-- Cookbooks testing
INSERT INTO cookbooks (kitchen_id, name) VALUES (1, "Breakfast");
INSERT INTO cookbooks (kitchen_id, name) VALUES (1, "Lunch");
INSERT INTO cookbooks (kitchen_id, name) VALUES (1, "Dinner");


-- Example recipe 1.
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Classic Tomato Basil Pasta',
    'A light and refreshing pasta dish with ripe tomatoes, fresh basil, and garlic.',
    'Main Course',
    'Italian',
    '200g spaghetti|4 ripe tomatoes (diced)|3 cloves garlic (minced)|1/4 cup extra-virgin olive oil|Fresh basil leaves (chopped)|Salt and pepper (to taste)',
    '1. Cook the spaghetti in salted boiling water until al dente. Reserve some pasta water.|2. While the pasta cooks, heat olive oil in a skillet over medium heat.|3. Add minced garlic and sauté until fragrant (about 1-2 minutes).|4. Add the diced tomatoes and cook until they release their juices (about 5 minutes).|5. Season with salt and pepper.|6. Toss the cooked spaghetti into the tomato mixture, adding a splash of reserved pasta water if needed.|7. Remove from heat and stir in the chopped fresh basil.|8. Serve hot, garnished with grated Parmesan cheese if desired.',
    10, 20, 30, 2, 1, null
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
    130, 20, 150, 4, 1, null
);

-- Example recipe 3 (just another recipe)
-- Source: https://www.twopeasandtheirpod.com/pomegranate-white-chocolate-chunk-cookies/
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Pomegranate White Chocolate Chunk Cookies',
    'Delicious oatmeal cookies with pomegranate and white chocolate.',
    'Dessert',
    'American',
    '1/2 cup unsalted butter (room temp)|1/2 cup light brown sugar|1/2 cup granulated sugar|1 large egg|1 teaspoon vanilla extract|1 1/4 cup all purpose flour|1/2 teaspoon baking powder|1/2 teaspoon baking soda|1/4 teaspoon salt|1 cup oats|1 cup white chocolate chunks|1 cup pomegranate arils',
    '1. Preheat the oven to 375 degrees F. Line a large baking sheet with parchment paper or a silicone baking mat and set aside.|2. In the bowl of a stand mixer, cream butter and sugars together until smooth. Add the egg and vanilla extract and mix until well combined.|3. In a separate bowl whisk together flour, baking powder, baking soda, and salt. Slowly add flour mixture to the wet ingredients. Mix until just incorporated.|4. Stir in the oats and white chocolate chunks. Make dough balls-about 1 tablespoon of dough per cookie. Tuck about 6-8 pomegranate arils in each cookie dough ball. Bake cookies for 10-12 minutes, until the cookies are golden brown. Remove from oven and let cool on baking sheet for two minutes. Transfer to a wire rack to finish cooling.',
    30, 10, 40, 30, 1, null
);

-- Example recipe 4
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Grilled Lemon Herb Chicken',
    'Juicy and flavorful grilled chicken marinated in lemon and herbs.',
    'Main Course',
    'American',
    '4 boneless, skinless chicken breasts|1/4 cup olive oil|2 cloves garlic (minced)|1 tablespoon fresh lemon juice|1 teaspoon lemon zest|1 teaspoon dried oregano|1 teaspoon dried thyme|Salt and pepper (to taste)',
    '1. In a small bowl, whisk together olive oil, minced garlic, lemon juice, lemon zest, oregano, thyme, salt, and pepper to make the marinade.|2. Place chicken breasts in a shallow dish and pour marinade over them, turning to coat evenly. Cover and refrigerate for at least 30 minutes, or up to 4 hours.|3. Preheat grill to medium-high heat.|4. Remove chicken from marinade and discard excess marinade.|5. Grill chicken for 6-8 minutes per side, or until internal temperature reaches 165°F (75°C).|6. Remove from grill and let rest for a few minutes before serving.',
    40, 15, 55, 4, 1, null
);

-- Example recipe 5
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Vegetable Stir-Fry',
    'A colorful and nutritious stir-fry featuring a variety of fresh vegetables.',
    'Main Course',
    'Asian',
    '2 tablespoons vegetable oil|1 onion (sliced)|2 cloves garlic (minced)|1 bell pepper (sliced)|1 carrot (julienned)|1 cup broccoli florets|1 cup snap peas|1 cup mushrooms (sliced)|1/4 cup soy sauce|2 tablespoons hoisin sauce|1 tablespoon rice vinegar|1 tablespoon sesame oil|1 teaspoon cornstarch (dissolved in 2 tablespoons water)|Cooked rice (for serving)',
    '1. Heat vegetable oil in a large skillet or wok over medium-high heat.|2. Add sliced onion and minced garlic, and sauté until fragrant and softened.|3. Add bell pepper, carrot, broccoli, snap peas, and mushrooms to the skillet, and stir-fry for 5-7 minutes, or until vegetables are tender-crisp.|4. In a small bowl, whisk together soy sauce, hoisin sauce, rice vinegar, sesame oil, and dissolved cornstarch.|5. Pour sauce over the vegetables in the skillet, and toss to coat evenly. Cook for an additional 2-3 minutes, or until sauce has thickened slightly.|6. Serve hot over cooked rice.',
    20, 15, 35, 4, 1, null
);

-- Example recipe 6
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Greek Salad',
    'A refreshing and vibrant salad featuring crisp vegetables, tangy feta cheese, and kalamata olives.',
    'Salad',
    'Greek',
    '2 cups cherry tomatoes (halved)|1 cucumber (diced)|1 bell pepper (diced)|1/2 red onion (thinly sliced)|1/2 cup kalamata olives|4 ounces feta cheese (crumbled)|2 tablespoons extra-virgin olive oil|1 tablespoon red wine vinegar|1 teaspoon dried oregano|Salt and pepper (to taste)',
    '1. In a large bowl, combine cherry tomatoes, cucumber, bell pepper, red onion, kalamata olives, and crumbled feta cheese.|2. In a small bowl, whisk together olive oil, red wine vinegar, dried oregano, salt, and pepper to make the dressing.|3. Pour dressing over the salad ingredients, and toss to coat evenly.|4. Serve immediately, or refrigerate until ready to serve.',
    15, 0, 15, 4, 2, null
);

-- Example recipe 7
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Homemade Pizza',
    'A classic homemade pizza with a crispy crust and your favorite toppings.',
    'Main Course',
    'Italian',
    '1 pre-made pizza dough|1/2 cup pizza sauce|1 cup shredded mozzarella cheese|Your choice of toppings (e.g., pepperoni, mushrooms, bell peppers, onions, olives)',
    '1. Preheat oven to 450°F (230°C).|2. Roll out pizza dough on a lightly floured surface to your desired thickness.|3. Transfer rolled-out dough to a pizza stone or baking sheet.|4. Spread pizza sauce evenly over the dough, leaving a small border around the edges.|5. Sprinkle shredded mozzarella cheese over the sauce, and add your desired toppings.|6. Bake pizza in preheated oven for 12-15 minutes, or until crust is golden brown and cheese is bubbly and melted.|7. Remove from oven and let cool for a few minutes before slicing and serving.',
    20, 15, 35, 4, 2, null
);

-- Example recipe 8
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Chocolate Chip Cookies',
    'Classic homemade chocolate chip cookies that are soft and chewy on the inside, with crispy edges.',
    'Dessert',
    'American',
    '1 cup unsalted butter (room temperature)|1 cup granulated sugar|1 cup packed light brown sugar|2 large eggs|1 teaspoon vanilla extract|3 cups all-purpose flour|1 teaspoon baking soda|1/2 teaspoon salt|2 cups semisweet chocolate chips',
    '1. Preheat oven to 350°F (175°C).|2. In a large mixing bowl, cream together butter, granulated sugar, and brown sugar until light and fluffy.|3. Beat in eggs, one at a time, then stir in vanilla extract.|4. In a separate bowl, whisk together flour, baking soda, and salt.|5. Gradually add dry ingredients to wet ingredients, mixing until well combined.|6. Stir in chocolate chips until evenly distributed throughout the dough.|7. Drop rounded tablespoons of dough onto ungreased baking sheets, spacing them about 2 inches apart.|8. Bake in preheated oven for 10-12 minutes, or until edges are lightly golden brown.|9. Remove from oven and let cool on baking sheets for a few minutes before transferring to wire racks to cool completely.',
    20, 10, 30, 36, 2, null
);
-- Example recipe 9
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Spaghetti Carbonara',
    'A classic Italian pasta dish made with pancetta, eggs, cheese, and black pepper.',
    'Main Course',
    'Italian',
    '12 ounces spaghetti|4 ounces pancetta or bacon (diced)|2 cloves garlic (minced)|2 large eggs|1 cup grated Parmesan cheese|1/4 cup grated Pecorino Romano cheese|Freshly ground black pepper (to taste)',
    '1. Cook spaghetti in a large pot of salted boiling water until al dente. Reserve 1/2 cup of pasta cooking water, then drain spaghetti.|2. While spaghetti cooks, heat a large skillet over medium heat. Add pancetta or bacon and cook until crispy and browned.|3. Add minced garlic to the skillet and cook for 1 minute, until fragrant.|4. In a small bowl, whisk together eggs, Parmesan cheese, Pecorino Romano cheese, and a generous amount of black pepper.|5. Remove skillet from heat and add cooked spaghetti to the pancetta/garlic mixture, tossing to combine.|6. Quickly pour egg and cheese mixture over hot pasta, stirring constantly to coat spaghetti evenly. If sauce is too thick, add reserved pasta water a little at a time until desired consistency is reached.|7. Serve immediately, garnished with additional grated Parmesan cheese and black pepper if desired.',
    10, 15, 25, 4, 2, null
);

-- Example recipe 10
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Chicken Tikka Masala',
    'A popular Indian curry dish featuring tender chicken in a creamy tomato-based sauce.',
    'Main Course',
    'Indian',
    '1 lb boneless, skinless chicken thighs (cut into bite-sized pieces)|1/2 cup plain yogurt|2 tablespoons lemon juice|2 teaspoons ground cumin|2 teaspoons ground coriander|1 teaspoon ground turmeric|1 teaspoon paprika|1 teaspoon garam masala|1/2 teaspoon cayenne pepper|Salt and pepper (to taste)|2 tablespoons vegetable oil|1 onion (finely chopped)|2 cloves garlic (minced)|1 tablespoon grated fresh ginger|1 can (14 oz) crushed tomatoes|1/2 cup heavy cream|Fresh cilantro (for garnish)|Cooked rice (for serving)',
    '1. In a bowl, combine yogurt, lemon juice, ground cumin, ground coriander, ground turmeric, paprika, garam masala, cayenne pepper, salt, and pepper. Add chicken pieces and toss to coat. Cover and refrigerate for at least 1 hour, or overnight for best flavor.|2. In a large skillet, heat vegetable oil over medium heat. Add chopped onion and cook until softened and translucent.|3. Add minced garlic and grated ginger to the skillet, and cook for 1 minute, until fragrant.|4. Add marinated chicken pieces to the skillet and cook until browned on all sides.|5. Stir in crushed tomatoes and simmer for 15-20 minutes, until chicken is cooked through and sauce has thickened slightly.|6. Stir in heavy cream and cook for an additional 5 minutes.|7. Serve hot over cooked rice, garnished with fresh cilantro leaves.',
    60, 25, 85, 4, 2, null
);

-- Example recipe 11
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Mushroom Risotto',
    'A creamy and comforting Italian rice dish cooked with mushrooms, onions, and Parmesan cheese.',
    'Main Course',
    'Italian',
    '6 cups chicken or vegetable broth|2 tablespoons olive oil|1 onion (finely chopped)|2 cloves garlic (minced)|1 lb mushrooms (sliced)|1 1/2 cups Arborio rice|1/2 cup dry white wine|1/2 cup grated Parmesan cheese|Salt and pepper (to taste)|Fresh parsley (for garnish)',
    '1. In a saucepan, heat chicken or vegetable broth over low heat.|2. In a large skillet or Dutch oven, heat olive oil over medium heat. Add chopped onion and cook until softened and translucent.|3. Add minced garlic and sliced mushrooms to the skillet, and cook until mushrooms release their juices and become tender.|4. Stir in Arborio rice and cook for 1-2 minutes, until rice is lightly toasted.|5. Pour in dry white wine and cook, stirring constantly, until wine is absorbed by the rice.|6. Add a ladleful of warm broth to the rice mixture and stir until liquid is absorbed. Continue adding broth, one ladleful at a time, stirring constantly and allowing each addition to be absorbed before adding more.|7. Cook risotto until rice is creamy and tender, about 20-25 minutes total.|8. Stir in grated Parmesan cheese, and season with salt and pepper to taste.|9. Serve hot, garnished with chopped fresh parsley.',
    20, 30, 50, 4, 3, null
);

-- Example recipe 12
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Vegetable Lasagna',
    'A hearty vegetarian lasagna loaded with layers of noodles, marinara sauce, vegetables, and cheese.',
    'Main Course',
    'Italian',
    '12 lasagna noodles|2 cups marinara sauce|2 tablespoons olive oil|1 onion (chopped)|2 cloves garlic (minced)|2 cups diced vegetables (e.g., zucchini, bell peppers, mushrooms)|2 cups spinach leaves|1 1/2 cups ricotta cheese|1 cup shredded mozzarella cheese|1/2 cup grated Parmesan cheese|Salt and pepper (to taste)',
    '1. Preheat oven to 375°F (190°C).|2. Cook lasagna noodles according to package instructions, then drain and set aside.|3. In a large skillet, heat olive oil over medium heat. Add chopped onion and minced garlic, and cook until softened and fragrant.|4. Add diced vegetables to the skillet and cook until tender. Stir in spinach leaves and cook until wilted. Season with salt and pepper to taste.|5. In a bowl, combine ricotta cheese, shredded mozzarella cheese, and grated Parmesan cheese.|6. Spread a thin layer of marinara sauce on the bottom of a 9x13-inch baking dish. Place a layer of cooked lasagna noodles on top of the sauce.|7. Spread half of the ricotta cheese mixture over the noodles, followed by half of the vegetable mixture. Repeat layers, ending with a layer of noodles on top.|8. Spread remaining marinara sauce over the top layer of noodles, and sprinkle with additional shredded mozzarella cheese if desired.|9. Cover baking dish with aluminum foil and bake in preheated oven for 30 minutes.|10. Remove foil and bake for an additional 10-15 minutes, until lasagna is hot and bubbly and cheese is melted and golden brown on top.|11. Let lasagna cool for a few minutes before slicing and serving.',
    30, 45, 75, 8, 3, null
);

-- Example recipe 13
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Caprese Salad',
    'A simple and elegant Italian salad featuring fresh tomatoes, mozzarella cheese, basil, and balsamic glaze.',
    'Salad',
    'Italian',
    '4 ripe tomatoes (sliced)|8 ounces fresh mozzarella cheese (sliced)|Fresh basil leaves|Extra-virgin olive oil|Balsamic glaze|Salt and pepper (to taste)',
    '1. Arrange alternating slices of tomatoes and mozzarella cheese on a serving platter.|2. Tuck fresh basil leaves in between tomato and cheese slices.|3. Drizzle extra-virgin olive oil over the salad, and season with salt and pepper to taste.|4. Drizzle balsamic glaze over the salad in a decorative pattern.|5. Serve immediately as a refreshing appetizer or side dish.',
    10, 0, 10, 4, 3, null
);

-- Example recipe 14
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Beef Stroganoff',
    'A comforting Russian dish featuring tender strips of beef in a creamy mushroom sauce, served over egg noodles.',
    'Main Course',
    'Russian',
    '1 lb beef sirloin or tenderloin (sliced thinly against the grain)|2 tablespoons vegetable oil|1 onion (sliced)|2 cloves garlic (minced)|8 ounces mushrooms (sliced)|1 tablespoon all-purpose flour|1 cup beef broth|1 cup sour cream|2 tablespoons Dijon mustard|1 tablespoon Worcestershire sauce|Salt and pepper (to taste)|Cooked egg noodles (for serving)|Chopped fresh parsley (for garnish)',
    '1. In a large skillet, heat vegetable oil over medium-high heat. Add sliced beef in batches and cook until browned on all sides. Remove beef from skillet and set aside.|2. In the same skillet, add sliced onion and cook until softened and translucent. Add minced garlic and sliced mushrooms, and cook until mushrooms release their juices and become tender.|3. Sprinkle flour over the mushroom mixture and cook, stirring constantly, for 1-2 minutes to create a roux.|4. Gradually pour in beef broth, stirring constantly, until mixture is smooth and thickened.|5. Return cooked beef to the skillet and stir to combine. Reduce heat to low and simmer for 10 minutes, stirring occasionally.|6. Stir in sour cream, Dijon mustard, and Worcestershire sauce. Season with salt and pepper to taste.|7. Serve hot over cooked egg noodles, garnished with chopped fresh parsley.',
    20, 25, 45, 4, 3, null
);

-- Example recipe 15
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Shrimp Scampi',
    'A classic Italian-American seafood dish featuring tender shrimp cooked in a garlic butter sauce, served over pasta.',
    'Main Course',
    'Italian',
    '12 ounces linguine pasta|1 lb large shrimp (peeled and deveined)|4 tablespoons unsalted butter|4 cloves garlic (minced)|1/4 teaspoon red pepper flakes|1/4 cup white wine|2 tablespoons fresh lemon juice|2 tablespoons chopped fresh parsley|Salt and pepper (to taste)',
    '1. Cook linguine pasta in a large pot of salted boiling water until al dente. Reserve 1/2 cup of pasta cooking water, then drain pasta and set aside.|2. In a large skillet, melt butter over medium heat. Add minced garlic and red pepper flakes, and cook for 1 minute, until fragrant.|3. Add shrimp to the skillet and cook until pink and opaque, about 2-3 minutes per side.|4. Remove shrimp from skillet and set aside. Deglaze skillet with white wine, scraping up any browned bits from the bottom of the pan.|5. Stir in fresh lemon juice and chopped parsley, and season with salt and pepper to taste.|6. Return cooked shrimp to the skillet and toss to coat in the sauce.|7. Add cooked linguine to the skillet and toss to combine, adding reserved pasta cooking water as needed to loosen the sauce and coat the pasta evenly.|8. Serve hot, garnished with additional chopped fresh parsley if desired.',
    15, 20, 35, 4, 3, null
);

-- Example recipe 16
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Vegetable Stir-Fry',
    'A quick and healthy Asian-inspired dish featuring a colorful medley of vegetables stir-fried in a savory sauce.',
    'Main Course',
    'Asian',
    '2 tablespoons soy sauce|2 tablespoons hoisin sauce|1 tablespoon rice vinegar|1 tablespoon sesame oil|1 tablespoon cornstarch|1/4 cup water|2 tablespoons vegetable oil|2 cloves garlic (minced)|1 tablespoon grated fresh ginger|1 bell pepper (sliced)|1 cup broccoli florets|1 cup snow peas|1 carrot (julienned)|2 green onions (sliced)|Cooked rice (for serving)',
    '1. In a small bowl, whisk together soy sauce, hoisin sauce, rice vinegar, sesame oil, cornstarch, and water to make the sauce. Set aside.|2. Heat vegetable oil in a large skillet or wok over high heat. Add minced garlic and grated ginger, and cook for 30 seconds, until fragrant.|3. Add sliced bell pepper, broccoli florets, snow peas, and julienned carrot to the skillet, and stir-fry for 3-4 minutes, until vegetables are crisp-tender.|4. Pour sauce over the vegetables in the skillet and toss to coat evenly. Cook for an additional 1-2 minutes, until sauce is thickened and vegetables are coated.|5. Stir in sliced green onions, then remove skillet from heat.|6. Serve hot over cooked rice, and enjoy this flavorful and nutritious stir-fry!',
    15, 10, 25, 4, 4, null
);

-- Example recipe 17
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Chicken Caesar Salad',
    'A classic salad featuring crisp romaine lettuce, grilled chicken, Parmesan cheese, and Caesar dressing.',
    'Salad',
    'Italian',
    '2 boneless, skinless chicken breasts|2 tablespoons olive oil|Salt and pepper (to taste)|1 head romaine lettuce (chopped)|1/2 cup Caesar dressing|1/4 cup grated Parmesan cheese|Croutons (for serving)',
    '1. Preheat grill or grill pan to medium-high heat.|2. Season chicken breasts with olive oil, salt, and pepper.|3. Grill chicken breasts for 6-7 minutes per side, or until cooked through and juices run clear. Remove from grill and let rest for a few minutes before slicing.|4. In a large bowl, toss chopped romaine lettuce with Caesar dressing until evenly coated.|5. Divide dressed lettuce among serving plates, and top with sliced grilled chicken.|6. Sprinkle grated Parmesan cheese over the salads, and garnish with croutons if desired.|7. Serve immediately as a satisfying and delicious main course or appetizer.',
    15, 15, 30, 2, 4, null
);

-- Example recipe 18
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Chocolate Chip Cookies',
    'Classic homemade cookies studded with chocolate chips, perfect for satisfying your sweet tooth.',
    'Dessert',
    'American',
    '1 cup unsalted butter (softened)|3/4 cup granulated sugar|3/4 cup packed brown sugar|2 large eggs|1 teaspoon vanilla extract|2 1/4 cups all-purpose flour|1 teaspoon baking soda|1/2 teaspoon salt|2 cups semisweet chocolate chips',
    '1. Preheat oven to 375°F (190°C) and line baking sheets with parchment paper or silicone baking mats.|2. In a large mixing bowl, cream together softened butter, granulated sugar, and brown sugar until light and fluffy.|3. Beat in eggs, one at a time, then stir in vanilla extract.|4. In a separate bowl, whisk together all-purpose flour, baking soda, and salt.|5. Gradually add dry ingredients to the butter mixture, mixing until just combined.|6. Fold in semisweet chocolate chips until evenly distributed throughout the dough.|7. Drop rounded tablespoons of dough onto prepared baking sheets, spacing them about 2 inches apart.|8. Bake in preheated oven for 9-11 minutes, or until cookies are golden brown around the edges and set in the center.|9. Remove from oven and let cookies cool on baking sheets for a few minutes before transferring to wire racks to cool completely.|10. Enjoy these classic chocolate chip cookies with a glass of cold milk for the ultimate treat!',
    15, 10, 25, 24, 4, null
);

-- Example recipe 19
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Tomato Basil Soup',
    'A comforting and flavorful soup made with ripe tomatoes, fresh basil, and a hint of cream.',
    'Soup',
    'American',
    '2 tablespoons olive oil|1 onion (chopped)|2 cloves garlic (minced)|2 lbs ripe tomatoes (cored and chopped)|4 cups vegetable broth|1/2 cup fresh basil leaves|1/4 cup heavy cream|Salt and pepper (to taste)|Grated Parmesan cheese (for serving)',
    '1. In a large pot, heat olive oil over medium heat. Add chopped onion and cook until softened and translucent.|2. Add minced garlic to the pot and cook for 1 minute, until fragrant.|3. Stir in chopped tomatoes and vegetable broth. Bring to a simmer and cook for 20-25 minutes, until tomatoes are soft and flavors have melded together.|4. Remove pot from heat and use an immersion blender to puree the soup until smooth. Alternatively, transfer soup to a blender and blend in batches until smooth, then return to pot.|5. Stir in fresh basil leaves and heavy cream. Season with salt and pepper to taste.|6. Return pot to low heat and simmer for an additional 5 minutes, until soup is heated through.|7. Serve hot, garnished with grated Parmesan cheese and additional fresh basil leaves if desired.|8. Enjoy this comforting tomato basil soup with crusty bread for a satisfying meal!',
    15, 30, 45, 6, 4, null
);
-- Example recipe 20.
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Chicken Alfredo Pasta',
    'A classic Italian pasta dish featuring tender chicken breast slices, creamy Alfredo sauce, and fettuccine noodles.',
    'Main Course',
    'Italian',
    '8 ounces fettuccine pasta|2 boneless, skinless chicken breasts (sliced)|2 tablespoons olive oil|2 cloves garlic (minced)|1 cup heavy cream|1 cup grated Parmesan cheese|Salt and pepper to taste|Fresh parsley (chopped, for garnish)',
    '1. Cook the fettuccine pasta according to package instructions until al dente. Drain and set aside.|2. Season the sliced chicken breasts with salt and pepper.|3. In a large skillet, heat olive oil over medium-high heat. Add minced garlic and cook until fragrant.|4. Add the seasoned chicken breast slices to the skillet and cook until golden brown and cooked through, about 5-6 minutes per side.|5. Remove the cooked chicken from the skillet and set aside.|6. In the same skillet, reduce heat to medium-low and pour in the heavy cream. Simmer gently for 2-3 minutes, stirring constantly.|7. Gradually add grated Parmesan cheese to the cream, stirring until the sauce is smooth and creamy.|8. Return the cooked chicken slices to the skillet and toss to coat in the Alfredo sauce.|9. Add the cooked fettuccine to the skillet and toss until evenly coated in the sauce.|10. Serve hot, garnished with chopped fresh parsley.',
    15, 20, 35, 4, 4, null
);

-- Example recipe 21.
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Vegetable Curry',
    'A flavorful and aromatic vegetarian curry made with a variety of fresh vegetables, coconut milk, and spices.',
    'Main Course',
    'Indian',
    '1 onion (chopped)|2 cloves garlic (minced)|1 tablespoon ginger (minced)|2 carrots (sliced)|1 potato (cubed)|1 cup cauliflower florets|1 cup green beans (cut into 1-inch pieces)|1 can (14 ounces) coconut milk|2 tablespoons curry powder|1 teaspoon ground turmeric|1 teaspoon ground cumin|1 teaspoon ground coriander|Salt and pepper to taste|Cooked rice (for serving)',
    '1. In a large pot or Dutch oven, heat olive oil over medium heat. Add chopped onion, minced garlic, and minced ginger. Cook until softened and fragrant.|2. Add sliced carrots, cubed potato, cauliflower florets, and green beans to the pot. Stir to coat the vegetables in the onion and spice mixture.|3. Pour in the coconut milk and stir to combine with the vegetables.|4. Season the curry with curry powder, ground turmeric, ground cumin, and ground coriander. Stir well to distribute the spices evenly.|5. Bring the curry to a simmer, then reduce heat to low and cover. Let the curry cook for 20-25 minutes, or until the vegetables are tender and the flavors have melded together.|6. Season with salt and pepper to taste.|7. Serve hot over cooked rice.',
    15, 25, 40, 6, 5, null
);

-- Example recipe 22.
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Grilled Salmon with Lemon-Dill Sauce',
    'A light and flavorful seafood dish featuring grilled salmon fillets topped with a tangy lemon-dill sauce.',
    'Main Course',
    'American',
    '4 salmon fillets (about 6 ounces each)|2 tablespoons olive oil|Salt and pepper to taste|1/4 cup fresh lemon juice|2 tablespoons Dijon mustard|2 tablespoons honey|2 tablespoons chopped fresh dill|1 clove garlic (minced)',
    '1. Preheat grill to medium-high heat.|2. Rub salmon fillets with olive oil and season with salt and pepper.|3. Place salmon fillets on the preheated grill and cook for 4-5 minutes per side, or until fish flakes easily with a fork.|4. While the salmon is grilling, prepare the lemon-dill sauce. In a small bowl, whisk together lemon juice, Dijon mustard, honey, chopped fresh dill, and minced garlic until well combined.|5. Remove grilled salmon from the grill and transfer to serving plates.|6. Drizzle salmon fillets with lemon-dill sauce, and serve immediately with your favorite side dishes.',
    10, 10, 20, 4, 5, null
);

-- Example recipe 23.
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Beef Stir-Fry with Vegetables',
    'A quick and easy Asian-inspired stir-fry dish featuring tender slices of beef, colorful vegetables, and a flavorful sauce.',
    'Main Course',
    'Asian',
    '1 lb flank steak (sliced thinly against the grain)|2 tablespoons soy sauce|1 tablespoon cornstarch|1 tablespoon vegetable oil|2 cloves garlic (minced)|1 bell pepper (sliced)|1 cup broccoli florets|1 carrot (julienned)|1/2 cup snap peas|1/4 cup sliced green onions|1/4 cup beef broth|2 tablespoons oyster sauce|1 tablespoon hoisin sauce|1 teaspoon sesame oil|Cooked rice (for serving)',
    '1. In a bowl, combine sliced flank steak with soy sauce and cornstarch. Toss to coat the beef evenly and let marinate for 15-20 minutes.|2. In a large skillet or wok, heat vegetable oil over high heat. Add minced garlic and stir-fry for 30 seconds, until fragrant.|3. Add marinated beef slices to the skillet and stir-fry for 2-3 minutes, until browned and cooked through. Remove beef from the skillet and set aside.|4. In the same skillet, add sliced bell pepper, broccoli florets, julienned carrot, and snap peas. Stir-fry for 3-4 minutes, until vegetables are crisp-tender.|5. Return cooked beef to the skillet with the vegetables.|6. In a small bowl, whisk together beef broth, oyster sauce, hoisin sauce, and sesame oil. Pour the sauce mixture over the beef and vegetables in the skillet.|7. Stir-fry everything together for another 1-2 minutes, until heated through and sauce has thickened slightly.|8. Serve hot over cooked rice, garnished with sliced green onions.',
    20, 15, 35, 4, 5, null
);

-- Example recipe 24.
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Chocolate Lava Cake',
    'A decadent dessert featuring rich chocolate cake with a gooey molten center, served warm and topped with a scoop of vanilla ice cream.',
    'Dessert',
    'International',
    '1/2 cup unsalted butter|4 ounces semi-sweet chocolate (chopped)|2 large eggs|2 large egg yolks|1/4 cup granulated sugar|1 teaspoon vanilla extract|2 tablespoons all-purpose flour|Pinch of salt|Vanilla ice cream (for serving)',
    '1. Preheat oven to 425°F (220°C). Grease four ramekins with butter and dust with cocoa powder.|2. In a microwave-safe bowl, melt butter and chopped chocolate together in 30-second intervals, stirring until smooth. Set aside to cool slightly.|3. In a separate bowl, whisk together eggs, egg yolks, granulated sugar, and vanilla extract until pale and creamy.|4. Slowly pour the melted chocolate mixture into the egg mixture, whisking constantly until well combined.|5. Sift in all-purpose flour and a pinch of salt, and gently fold into the batter until just combined.|6. Divide the batter evenly among the prepared ramekins.|7. Place ramekins on a baking sheet and transfer to the preheated oven.|8. Bake for 12-14 minutes, until the edges are set but the centers are still soft and slightly jiggly.|9. Remove from oven and let cool for 1-2 minutes before running a knife around the edges to loosen the cakes.|10. Carefully invert each cake onto a serving plate. Serve warm with a scoop of vanilla ice cream on top.',
    15, 12, 27, 4, 5, null
);


-- Example recipe 25.
INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id, cookbook_id)
VALUES (
    'Tiramisu',
    'A classic Italian dessert featuring layers of coffee-soaked ladyfingers and creamy mascarpone cheese, dusted with cocoa powder.',
    'Dessert',
    'Italian',
    '6 large egg yolks|3/4 cup granulated sugar|1 cup mascarpone cheese|1 1/2 cups heavy cream|2 cups strong brewed coffee (cooled)|1/4 cup coffee liqueur (e.g., Kahlua)|1 package (7 ounces) ladyfingers|2 tablespoons unsweetened cocoa powder',
    '1. In a heatproof bowl, whisk together egg yolks and granulated sugar until pale and creamy.|2. Place bowl over a pot of simmering water (double boiler) and continue whisking for 8-10 minutes, until mixture thickens and coats the back of a spoon. Remove from heat and let cool slightly.|3. In a separate bowl, beat mascarpone cheese until smooth and creamy. Gradually fold mascarpone into the egg mixture until well combined.|4. In another bowl, whip heavy cream until stiff peaks form. Gently fold whipped cream into the mascarpone mixture until smooth and creamy.|5. In a shallow dish, combine cooled brewed coffee and coffee liqueur.|6. Quickly dip each ladyfinger into the coffee mixture, making sure not to soak them too long. Arrange a layer of soaked ladyfingers in the bottom of a serving dish or 9x13-inch baking dish.|7. Spread half of the mascarpone mixture over the layer of ladyfingers.|8. Repeat with another layer of soaked ladyfingers and remaining mascarpone mixture.|9. Cover and refrigerate tiramisu for at least 4 hours, or overnight, to allow flavors to meld together and for the dessert to set.|10. Before serving, dust the top of the tiramisu with unsweetened cocoa powder.|11. Slice and serve this indulgent Italian dessert, and enjoy the rich flavors of coffee and mascarpone!',
    30, 0, 240, 8, 5, null
);

-- Favorites testing
INSERT INTO favorites (owner_id, recipe_id) VALUES (2, 1);
INSERT INTO favorites (owner_id, recipe_id) VALUES (2, 2);
