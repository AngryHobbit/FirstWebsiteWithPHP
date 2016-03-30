DROP TABLE IF EXISTS bb_orderitems;
DROP TABLE IF EXISTS bb_order;
DROP TABLE IF EXISTS bb_product;
DROP TABLE IF EXISTS bb_customer;
DROP TABLE IF EXISTS bb_newsletter;

CREATE TABLE bb_newsletter (
    email VARCHAR(255) PRIMARY KEY
);

CREATE TABLE bb_customer (
    email VARCHAR(255) PRIMARY KEY, 
    fname VARCHAR(100), 
    sname VARCHAR(100), 
    postcode VARCHAR(7), 
    pass VARCHAR(41)
);

CREATE TABLE bb_product (
    pid INT AUTO_INCREMENT PRIMARY KEY , 
    name VARCHAR(100), 
    description TEXT,
    imagepath VARCHAR(100),
    price DECIMAL(10, 2)
);

CREATE TABLE bb_order (
    oid INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    FOREIGN KEY (email) REFERENCES bb_customer(email)
);

CREATE TABLE bb_orderitems (
    oid INT,
    pid INT,
    qty INT,
    PRIMARY KEY (oid, pid),
    FOREIGN KEY (oid) REFERENCES bb_order(oid),
    FOREIGN KEY (pid) REFERENCES bb_product(pid)
);

INSERT INTO bb_product VALUES
    (NULL, "10 inch Latex Balloons", "A pack of 14, 10 inch latex Balloons (Assorted colour)", "images/10inch_Latex_Balloons.jpg", 2.30),
    (NULL, "12 inch Latex Balloon", "A pack of 10, 12 inch Latex Balloon (Assorted colour)", "images/12inch_Latex_Balloons.jpg", 2.50),
    (NULL, "18 inch Foil Round Balloon", "One 18 inch Foil Round Balloon (pink)", "images/18inch_Foil_Round_Balloon.jpg", 3.00),
    (NULL, "18 inch Metallic Heart Foil Balloon", "One 18 inch Metallic Heart Foil Balloon (Red)", "images/18inch_Metallic_Heart_Foil_Balloon.jpg", 2.50),
    (NULL, "19 inch Metallic Star Foil Balloon", "One 19 inch Metallic Star Foil Balloon (Yellow)", "images/19inch_Metallic_Star_Foil_Balloon.jpg", 3.25);