-- ============================
-- SDC310L Online Store Database
-- Week 2: Database + Foundation
-- ============================

CREATE DATABASE IF NOT EXISTS online_store
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE online_store;

-- ----------------------------
-- Products
-- ----------------------------
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS carts;
DROP TABLE IF EXISTS products;

CREATE TABLE products (
  product_id INT AUTO_INCREMENT PRIMARY KEY,
  product_name VARCHAR(120) NOT NULL,
  product_description TEXT NOT NULL,
  product_cost DECIMAL(10,2) NOT NULL CHECK (product_cost >= 0),
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE carts (
  cart_id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------
-- Cart Items
-- ----------------------------
CREATE TABLE cart_items (
  cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
  cart_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 0 CHECK (quantity >= 0),
  added_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_cartitems_cart
    FOREIGN KEY (cart_id) REFERENCES carts(cart_id)
    ON DELETE CASCADE,

  CONSTRAINT fk_cartitems_product
    FOREIGN KEY (product_id) REFERENCES products(product_id)
    ON DELETE RESTRICT,

  -- prevents duplicate rows for same product in same cart
  UNIQUE KEY uq_cart_product (cart_id, product_id)
) ENGINE=InnoDB;


INSERT INTO products (product_name, product_description, product_cost) VALUES
('Wireless Mouse', 'Ergonomic wireless mouse with adjustable DPI.', 19.99),
('Mechanical Keyboard', 'Backlit mechanical keyboard with blue switches.', 79.99),
('USB-C Charger', 'Lightening USB-C wall charger.', 24.99),
('Laptop Stand', 'Aluminum laptop stand with adjustable height.', 29.99),
('Noise-Canceling Headphones', 'Headphones with active noise canceling.', 129.99);

INSERT INTO carts () VALUES ();


