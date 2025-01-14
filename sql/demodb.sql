-- Create the testing database
CREATE DATABASE IF NOT EXISTS testing_db;
USE testing_db;

-- Create the users table
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the products table
CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the orders table
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Create the order_items table
CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Create the categories table
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
);

-- Create the product_categories table
CREATE TABLE IF NOT EXISTS product_categories (
    product_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (product_id, category_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Create the reviews table
CREATE TABLE IF NOT EXISTS reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Insert sample data for testing
INSERT INTO users (username, email) VALUES 
    ('testuser1', 'test1@example.com'), 
    ('testuser2', 'test2@example.com'), 
    ('testuser3', 'test3@example.com'), 
    ('testuser4', 'test4@example.com');

INSERT INTO products (product_name, price) VALUES 
    ('Product A', 10.00), 
    ('Product B', 20.00), 
    ('Product C', 30.00), 
    ('Product D', 40.00), 
    ('Product E', 50.00);

INSERT INTO categories (category_name) VALUES 
    ('Electronics'), 
    ('Books'), 
    ('Clothing'), 
    ('Furniture'), 
    ('Toys');

INSERT INTO product_categories (product_id, category_id) VALUES 
    (1, 1), 
    (2, 2), 
    (3, 3), 
    (4, 4), 
    (5, 5);

-- Example orders
INSERT INTO orders (user_id, total) VALUES 
    (1, 60.00), 
    (2, 90.00), 
    (3, 120.00);

INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
    (1, 1, 2, 10.00),
    (1, 2, 1, 20.00),
    (2, 3, 3, 30.00),
    (2, 4, 1, 40.00),
    (3, 5, 2, 50.00);

-- Insert sample reviews
INSERT INTO reviews (user_id, product_id, rating, comment) VALUES
    (1, 1, 5, 'Excellent product!'),
    (2, 2, 4, 'Good quality but a bit expensive.'),
    (3, 3, 3, 'Average product.'),
    (4, 4, 2, 'Not as expected.'),
    (1, 5, 5, 'Highly recommend!');
