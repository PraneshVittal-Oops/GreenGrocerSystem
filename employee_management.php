<?php 
session_start();
include('connect.php'); // Include your database connection file

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenGrocer Employee Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">GreenGrocer Employee Management</h2>

        <!-- Display success/error messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <!-- Employee Form -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Add New Employee</h4>
                <form method="POST" action="add_delete_employee.php">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control" id="position" name="position" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" class="form-control" id="salary" name="salary" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hire_date" class="form-label">Hire Date</label>
                            <input type="date" class="form-control" id="hire_date" name="hire_date" required>
                        </div>
                    </div>
                    <button type="submit" name="add_employee" class="btn btn-primary w-100">Add Employee</button>
                </form>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead class="table-success">
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Position</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Salary</th>
                            <th>Hire Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query to fetch all employees
                        $sql = "SELECT * FROM Employees";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($employee = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($employee['EmployeeID']) ?></td>
                                    <td><?= htmlspecialchars($employee['FirstName']) ?></td>
                                    <td><?= htmlspecialchars($employee['LastName']) ?></td>
                                    <td><?= htmlspecialchars($employee['Position']) ?></td>
                                    <td><?= htmlspecialchars($employee['Email']) ?></td>
                                    <td><?= htmlspecialchars($employee['Phone']) ?></td>
                                    <td><?= htmlspecialchars($employee['City']) ?></td>
                                    <td>$<?= number_format($employee['Salary'], 2) ?></td>
                                    <td><?= htmlspecialchars($employee['HireDate']) ?></td>
                                    <td>
                                        <a href="add_delete_employee.php?delete_id=<?= $employee['EmployeeID'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile;
                        } else {
                            echo '<tr><td colspan="10" class="text-center text-muted">No employees found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
