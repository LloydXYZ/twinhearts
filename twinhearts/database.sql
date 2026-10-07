CREATE DATABASE IF NOT EXISTS twinhearts CHARACTER SET utf8mb4;
USE twinhearts;
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100),email VARCHAR(100) UNIQUE,phone
VARCHAR(30),password VARCHAR(255),role ENUM('customer','admin') DEFAULT 'customer',created_at TIMESTAMP DEFAULT
CURRENT_TIMESTAMP);
CREATE TABLE packages(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100),description TEXT,price DECIMAL(10,2),active
TINYINT DEFAULT 1);
CREATE TABLE decorations(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100),description TEXT,price
DECIMAL(10,2),quantity INT DEFAULT 1,image VARCHAR(255),available TINYINT DEFAULT 1);
CREATE TABLE reservations(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT,package_id INT,event_type VARCHAR(60),event_date
DATE,venue VARCHAR(150),notes TEXT,status ENUM('pending','approved','declined','completed') DEFAULT 'pending',created_at
TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(user_id) REFERENCES users(id),FOREIGN KEY(package_id) REFERENCES
packages(id));
CREATE TABLE rentals(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT,decoration_id INT,qty INT,start_date DATE,end_date
DATE,status ENUM('pending','approved','declined','returned') DEFAULT 'pending',created_at TIMESTAMP DEFAULT
CURRENT_TIMESTAMP,FOREIGN KEY(user_id) REFERENCES users(id),FOREIGN KEY(decoration_id) REFERENCES decorations(id));
CREATE TABLE payments(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT,type ENUM('reservation','rental'),ref_id INT,amount
DECIMAL(10,2),method VARCHAR(40),reference_no VARCHAR(60),status ENUM('pending','verified','rejected') DEFAULT
'pending',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(user_id) REFERENCES users(id));
CREATE TABLE notifications(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT,message VARCHAR(255),created_at TIMESTAMP
DEFAULT CURRENT_TIMESTAMP);
INSERT INTO packages(name,description,price) VALUES
('Garden Wedding','Full styling, floral arch, aisle decor, head table setup',45000),
('Intimate Celebration','Backdrop, 10 tables styled, centerpieces, lighting',22000),
('Debut Classic','Themed stage, balloon installation, cake table, lighting',28000);
INSERT INTO decorations(name,description,price,quantity) VALUES
('Floral Backdrop','Arch backdrop with fresh-look florals',5000,3),
('Round Table','60-inch round banquet table',250,40),
('Chiavari Chair','Gold chiavari chair',60,200),
('Table Centerpiece','Glass vase with florals and candles',350,40),
('String Lighting','10-meter warm white lights',800,15);