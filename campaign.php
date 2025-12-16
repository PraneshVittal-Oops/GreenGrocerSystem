<?php
session_start();
include('connect.php'); // Include database connection

// Fetch campaigns with linked customers
$sql = "
    SELECT 
        mc.CampaignID, mc.CampaignName, mc.StartDate, mc.EndDate, mc.Budget, mc.TargetAudience,
        GROUP_CONCAT(c.name SEPARATOR ', ') AS Customers
    FROM MarketingCampaign mc
    LEFT JOIN CampaignCustomer cc ON mc.CampaignID = cc.CampaignID
    LEFT JOIN Customer c ON cc.CustomerID = c.customerID
    GROUP BY mc.CampaignID
    ORDER BY mc.StartDate DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketing Campaigns</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <h2 class="text-center">Marketing Campaigns</h2>

    <!-- Campaign Cards -->
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <?= $row['CampaignName']; ?>
                        </div>
                        <div class="card-body">
                            <p><strong>Start Date:</strong> <?= $row['StartDate']; ?></p>
                            <p><strong>End Date:</strong> <?= $row['EndDate']; ?></p>
                            <p><strong>Budget:</strong> $<?= number_format($row['Budget'], 2); ?></p>
                            <p><strong>Target Audience:</strong> <?= $row['TargetAudience']; ?></p>
                            <p><strong>Customers:</strong> <?= $row['Customers'] ?: 'None'; ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">No campaigns found.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
