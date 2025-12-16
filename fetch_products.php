<?php
// Include the database connection
include('connect.php');

// Prepare an array to hold the products
$products = [];

try {
    // Query to fetch products and their suppliers
    $sql = "SELECT 
                p.ProductID, 
                p.ProductName, 
                p.Category, 
                p.Price, 
                p.StockLevel, 
                p.ImagePath,
                s.SupplierName, 
                s.ContactPerson, 
                s.ContactEmail, 
                s.ContactPhone, 
                s.Address
            FROM Products p
            JOIN Suppliers s ON p.SupplierID = s.SupplierID";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are any products returned
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Store each product row in the products array
                $products[] = $row;
            }
        } else {
            $products = ['message' => 'No products available'];
        }

        // Close the statement
        $stmt->close();
    } else {
        throw new Exception("Failed to prepare the SQL statement.");
    }

    // Close the database connection
    $conn->close();

} catch (Exception $e) {
    // In case of error, return an error message
    $products = ['error' => 'Failed to fetch products: ' . $e->getMessage()];
    http_response_code(500); // Internal Server Error
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($products);
