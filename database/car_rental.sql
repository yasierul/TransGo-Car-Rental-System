CREATE DATABASE car_rental_db;
USE car_rental_db;

CREATE TABLE cars (
  car_id INT AUTO_INCREMENT PRIMARY KEY,
  car_name VARCHAR(100),
  brand VARCHAR(50),
  price_per_day DECIMAL(10,2),
  availability BOOLEAN DEFAULT TRUE,
  image VARCHAR(255)
);
