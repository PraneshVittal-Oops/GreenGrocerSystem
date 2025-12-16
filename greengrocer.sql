

/*table creation for green grocer*/
CREATE TABLE IF NOT EXISTS Customer (
    customerID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Insert five customer records with names from the USA
INSERT INTO Customer (name, email, password) 
VALUES
    ('John Adams', 'john.adams@example.com', 'password123'),
    ('Emily Johnson', 'emily.johnson@example.com', 'securePass456'),
    ('Michael Smith', 'michael.smith@example.com', 'myPass789'),
    ('Sophia Williams', 'sophia.williams@example.com', 'helloWorld321'),
    ('James Brown', 'james.brown@example.com', 'brownie789');

CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for each user
    firstName VARCHAR(50) NOT NULL,    -- User's first name
    lastName VARCHAR(50) NOT NULL,     -- User's last name
    email VARCHAR(100) NOT NULL UNIQUE, -- User's email address, must be unique
    phoneNumber VARCHAR(20) NOT NULL,  -- User's phone number
    address VARCHAR(255) NOT NULL,     -- User's residential address
    dob DATE NOT NULL,                 -- User's date of birth
    dietaryPreferences TEXT DEFAULT NULL, -- Optional dietary preferences
    loyaltyPoints INT DEFAULT 0       -- Loyalty points, defaults to 0
);

INSERT INTO Users (firstName, lastName, email, phoneNumber, address, dob, dietaryPreferences, loyaltyPoints)
VALUES
    ('Michael', 'Scott', 'michael.scott@example.com', '123-456-7890', '1725 Slough Avenue, Scranton, PA', '1964-03-15', 'None', 50),
    ('Pamela', 'Beesly', 'pam.beesly@example.com', '987-654-3210', '123 Paper Street, Scranton, PA', '1979-03-25', 'Vegetarian', 120),
    ('Jim', 'Halpert', 'jim.halpert@example.com', '456-789-1230', '123 Office Lane, Scranton, PA', '1978-10-01', 'Pescatarian', 200),
    ('Dwight', 'Schrute', 'dwight.schrute@example.com', '789-123-4560', 'Beet Farm, Schrute Farms, PA', '1970-01-20', 'Vegan', 300),
    ('Angela', 'Martin', 'angela.martin@example.com', '321-654-9870', '456 Cat Lane, Scranton, PA', '1971-06-25', 'None', 150);



CREATE TABLE Suppliers (
SupplierID INT AUTO_INCREMENT PRIMARY KEY,
SupplierName VARCHAR(255) NOT NULL,
ContactPerson VARCHAR(255),
ContactEmail VARCHAR(255),
ContactPhone VARCHAR(20),
Address VARCHAR(255)
);
INSERT INTO Suppliers (SupplierName, ContactPerson, ContactEmail, ContactPhone, Address)
VALUES
('Fresh Farms', 'John Doe', 'john@freshfarms.com', '+1234567890', '123 Green St, Chicago, IL'),
('Organic Valley', 'Jane Smith', 'jane@organicvalley.com', '+0987654321', '456 Blue Ave, Denver, CO'),
('Nature\'s Best', 'Emily Johnson', 'emily.johnson@naturesbest.com', '+1123456789', '789 Oak Rd, Austin, TX'),
('Farm Fresh Co.', 'Michael Brown', 'michael.brown@farmfresh.com', '+1321654987', '321 Pine St, Portland, OR');

CREATE TABLE Categories (
    CategoryID INT AUTO_INCREMENT PRIMARY KEY,
    CategoryName VARCHAR(255) NOT NULL,
    Description TEXT
);
-- Insert example category records into Categories table
INSERT INTO Categories (CategoryName, Description)
VALUES
('Fruits', 'A variety of fresh and organic fruits.'),
('Dairy', 'Dairy products including milk, cheese, and eggs.'),
('Vegetables', 'Fresh vegetables of different varieties.');
CREATE TABLE Promotions (
    PromotionID INT AUTO_INCREMENT PRIMARY KEY,
    PromotionName VARCHAR(255) NOT NULL,
    DiscountPercentage DECIMAL(5, 2) NOT NULL,
    StartDate DATE NOT NULL,
    EndDate DATE NOT NULL
);
-- Insert sample promotions
INSERT INTO Promotions (PromotionName, DiscountPercentage, StartDate, EndDate)
VALUES
('Holiday Sale', 10.00, '2024-11-01', '2024-12-01'),
('New Year Discount', 15.00, '2025-01-01', '2025-01-15'),
('Clearance Sale', 20.00, '2024-12-15', '2024-12-31'),
('Spring Special', 5.00, '2024-03-01', '2024-03-31');

CREATE TABLE Products (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    ProductName VARCHAR(255) NOT NULL,
    Category VARCHAR(255) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    StockLevel INT NOT NULL,
    ImagePath VARCHAR(255) NOT NULL,
    SupplierID INT,
    CategoryID INT,
    PromotionID INT,
    FOREIGN KEY (SupplierID) REFERENCES Suppliers(SupplierID) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    FOREIGN KEY (CategoryID) REFERENCES Categories(CategoryID) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    FOREIGN KEY (PromotionID) REFERENCES Promotions(PromotionID) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE
);
-- Insert product records
INSERT INTO Products (ProductName, Category, Price, StockLevel, ImagePath, SupplierID, CategoryID, PromotionID) 
VALUES
('Organic Apple', 'Fruits', 2.00, 150, 'image/organic.webp', 1, 1, 1),
('Fresh Almond Milk', 'Dairy', 3.50, 80, 'image/almond_milk.jpeg', 2, 2, 2),
('Organic Spinach', 'Vegetables', 1.80, 200, 'image/spinach.webp', 3, 3, 3),
('Free-Range Eggs', 'Dairy', 4.20, 60, 'image/egg.jpeg', 4, 2, 4);


CREATE TABLE `cart` (
    `CartID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `CustomerID` INT NOT NULL,
    `TotalItems` INT DEFAULT 0,
    `TotalPrice` DECIMAL(10, 2) DEFAULT 0.00,
    `CartStatus` ENUM('active', 'completed', 'abandoned') DEFAULT 'active',
    `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`CustomerID`) REFERENCES `Customer`(`customerID`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `cart_items` ( 
    `CartItemID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `CartID` INT NOT NULL,
    `ProductID` INT NOT NULL,
    `Quantity` INT DEFAULT 1,
    FOREIGN KEY (`CartID`) REFERENCES `cart`(`CartID`) ON DELETE CASCADE,
    FOREIGN KEY (`ProductID`) REFERENCES `products`(`ProductID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT NOT NULL,
    OrderDate DATETIME NOT NULL,
    TotalAmount DECIMAL(10, 2) NOT NULL,
    PaymentStatus ENUM('Paid', 'Pending') DEFAULT 'Pending',
    DeliveryStatus ENUM('Delivered', 'Pending') DEFAULT 'Pending',
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID) 
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE OrderProduct (
    OrderProductID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT NOT NULL,
    ProductID INT NOT NULL,
    Quantity INT NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) ON DELETE CASCADE,
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Payment (
    PaymentID INT AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for each payment
    OrderID INT NOT NULL, -- Foreign key to link with the Orders table
    PaymentMethod VARCHAR(50) NOT NULL, -- Method used for payment (e.g., Credit Card, PayPal)
    AmountPaid DECIMAL(10, 2) NOT NULL, -- Amount paid in the transaction
    PaymentStatus ENUM('Pending', 'Paid') DEFAULT 'Pending', -- Status of the payment
    PaymentDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp when the payment was made
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) ON DELETE CASCADE -- Cascading delete on order removal
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Reviews (
    ReviewID INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each review
    CustomerID INT,                          -- ID of the customer writing the review
    ProductID INT,                           -- ID of the product being reviewed
    Rating INT CHECK (Rating BETWEEN 1 AND 5), -- Rating given (1-5)
    ReviewText TEXT,                         -- The actual review text
    ReviewDate DATE DEFAULT CURRENT_DATE,    -- The date of the review, defaults to current date
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID) ON DELETE CASCADE, -- Cascade delete on customer removal
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID) ON DELETE CASCADE    -- Cascade delete on product removal
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS Subscription (
    SubscriptionID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT NOT NULL,
    StartDate DATE NOT NULL,
    EndDate DATE NOT NULL,
    DeliveryFrequency ENUM('Weekly', 'Bi-weekly', 'Monthly', 'Custom') NOT NULL,
    SubscriptionStatus ENUM('Active', 'Paused', 'Cancelled') DEFAULT 'Active',
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (CustomerID) REFERENCES Customer(customerID) 
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE DeliveryPersonnel (
    DeliveryPersonID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    FullName VARCHAR(100) NOT NULL,
    Status ENUM('Active', 'Inactive') DEFAULT 'Active'
);

-- Insert four people into the table
INSERT INTO DeliveryPersonnel (FullName, Status) 
VALUES 
    ('John Doe', 'Active'),
    ('Jane Smith', 'Active'),
    ('Michael Brown', 'Inactive'),
    ('Emily Davis', 'Active');

CREATE TABLE Deliveries (
    DeliveryID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT NOT NULL,
    DeliveryAddress VARCHAR(255) NOT NULL,
    DeliveryDate DATE NOT NULL,
    DeliveryStatus ENUM('Pending', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
    DeliveryPerson INT DEFAULT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) ON DELETE CASCADE,
    FOREIGN KEY (DeliveryPerson) REFERENCES DeliveryPersonnel(DeliveryPersonID) ON DELETE SET NULL
);

CREATE TABLE Returns (
    ReturnID INT AUTO_INCREMENT PRIMARY KEY,      
    OrderID INT NOT NULL,                         
    ProductID INT NOT NULL,                       
    ReasonForReturn TEXT NOT NULL,                
    ReturnDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) ON DELETE CASCADE,
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Warehouse (
    WarehouseID INT AUTO_INCREMENT PRIMARY KEY,
    Location VARCHAR(255) NOT NULL,
    Capacity INT NOT NULL
);
-- Insert additional warehouses to total 10 records
INSERT INTO Warehouse (Location, Capacity)
VALUES
('Houston', 700),
('Phoenix', 600),
('Philadelphia', 400),
('San Antonio', 550),
('San Diego', 450),
('Dallas', 800),
('San Jose', 650),
('Austin', 500),
('Jacksonville', 300),
('Fort Worth', 350);


CREATE TABLE ProductWarehouse (
    ProductWarehouseID INT AUTO_INCREMENT PRIMARY KEY,
    ProductID INT NOT NULL,
    WarehouseID INT NOT NULL,
    Quantity INT NOT NULL,
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Link products to warehouses with sample quantities
INSERT INTO ProductWarehouse (ProductID, WarehouseID, Quantity)
VALUES
(1, 1, 150),  -- Organic Apple in New York
(2, 1, 100),  -- Fresh Almond Milk in New York
(3, 2, 200),  -- Organic Spinach in Los Angeles
(4, 2, 80),   -- Free-Range Eggs in Los Angeles
(1, 3, 120),  -- Organic Apple in Chicago
(2, 4, 90),   -- Fresh Almond Milk in Houston
(3, 5, 150),  -- Organic Spinach in Phoenix
(4, 6, 70),   -- Free-Range Eggs in Philadelphia
(1, 7, 200),  -- Organic Apple in San Antonio
(3, 8, 100);  -- Organic Spinach in San Diego

CREATE TABLE IF NOT EXISTS MarketingCampaign (
    CampaignID INT AUTO_INCREMENT PRIMARY KEY,
    CampaignName VARCHAR(150) NOT NULL,
    StartDate DATE NOT NULL,
    EndDate DATE NOT NULL,
    Budget DECIMAL(15, 2) NOT NULL,
    TargetAudience VARCHAR(255) NOT NULL
);
INSERT INTO MarketingCampaign (CampaignName, StartDate, EndDate, Budget, TargetAudience) VALUES
('Summer Sale', '2024-07-01', '2024-07-31', 5000.00, 'Young Adults'),
('Winter Deals', '2024-12-01', '2024-12-31', 8000.00, 'Families'),
('Back to School', '2024-08-01', '2024-08-15', 3000.00, 'Students'),
('Holiday Offers', '2024-11-20', '2024-11-30', 6000.00, 'Holiday Shoppers'),
('New Year Promo', '2025-01-01', '2025-01-10', 7000.00, 'General Audience');

CREATE TABLE IF NOT EXISTS CampaignCustomer (
    CampaignID INT NOT NULL,
    CustomerID INT NOT NULL,
    PRIMARY KEY (CampaignID, CustomerID),
    FOREIGN KEY (CampaignID) REFERENCES MarketingCampaign(CampaignID) ON DELETE CASCADE,
    FOREIGN KEY (CustomerID) REFERENCES Customer(customerID) ON DELETE CASCADE
);
INSERT INTO CampaignCustomer (CampaignID, CustomerID) VALUES
(1, 1), (1, 2), (2, 3), (3, 4), (3, 5),
(4, 1), (4, 3), (5, 2), (5, 4), (5, 5);

CREATE TABLE Employees (
    EmployeeID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Position VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Phone VARCHAR(15) NOT NULL,
    City VARCHAR(50) NOT NULL,
    Salary DECIMAL(10, 2) NOT NULL,
    HireDate DATE NOT NULL
);
INSERT INTO Employees (FirstName, LastName, Position, Email, Phone, City, Salary, HireDate)
VALUES 
('ken', 'derick', 'Manager', 'john.doe@greengrocer.com', '555-1234', 'New York', 60000, '2023-01-10'),
('Jane', 'morgan', 'Cashier', 'jane.smith@greengrocer.com', '555-5678', 'Chicago', 35000, '2023-03-15'),
('Carlos', 'Martinez', 'Stock Clerk', 'carlos.martinez@greengrocer.com', '555-8765', 'Los Angeles', 28000, '2023-06-20'),
('Emily', 'Johnson', 'HR Specialist', 'emily.johnson@greengrocer.com', '555-4321', 'Miami', 50000, '2023-08-05'),
('Ahmed', 'Ali', 'IT Technician', 'ahmed.ali@greengrocer.com', '555-3456', 'Dallas', 45000, '2023-11-01');
















