CREATE TABLE Productos (
    productid INT PRIMARY KEY AUTO_INCREMENT,
    productname VARCHAR(100),
    categoryid INT,
    price DECIMAL(18, 2),
    stockquantity INT
);