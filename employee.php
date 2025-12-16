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
        <h2 class="mb-4 text-center"> The Following Are Our Employees</h2>

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
                                </tr>
                            <?php endwhile;
                        } else {
                            echo '<tr><td colspan="9" class="text-center text-muted">No employees found.</td></tr>';
                        }
                        $conn->close();
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
