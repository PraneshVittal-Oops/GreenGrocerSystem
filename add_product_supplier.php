<?php
session_start();
include('connect.php'); // Include the database connection file

// Enable error reporting for debugging
ini_set('display_errors', 1); 
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = htmlspecialchars($_POST['productName']);
    $category = htmlspecialchars($_POST['category']);
    $price = $_POST['price'];
    $stockLevel = $_POST['stockLevel'];
    $imagePath = htmlspecialchars($_POST['imagePath']);
    $supplierName = htmlspecialchars($_POST['supplierName']);
    $contactPerson = htmlspecialchars($_POST['contactPerson']);
    $contactEmail = htmlspecialchars($_POST['contactEmail']);
    $contactPhone = htmlspecialchars($_POST['contactPhone']);
    $address = htmlspecialchars($_POST['address']);

    // Validate the image path
    if (!file_exists($imagePath)) {
        $_SESSION['cart_message'] = "Error: The specified image does not exist.";
        header('Location: product_management.php');
        exit();
    }

    $conn->begin_transaction();

    try {
        // Insert supplier data
        $sqlSupplier = "INSERT INTO Suppliers (SupplierName, ContactPerson, ContactEmail, ContactPhone, Address) 
                        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sqlSupplier);
        $stmt->bind_param("sssss", $supplierName, $contactPerson, $contactEmail, $contactPhone, $address);
        $stmt->execute();
        $supplierID = $stmt->insert_id;
        $stmt->close();

        // Insert product data
        $sqlProduct = "INSERT INTO Products (ProductName, Category, Price, StockLevel, ImagePath, SupplierID) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sqlProduct);
        $stmt->bind_param("ssdiis", $productName, $category, $price, $stockLevel, $imagePath, $supplierID);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        $_SESSION['cart_message'] = "Product and Supplier added successfully!";
        header('Location: product_management.php');
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['cart_message'] = "Error: Could not add product and supplier.";
        header('Location: product_management.php');
    }
} else {
    header('Location: product_management.php');
    exit();
}
$conn->close();
?>
