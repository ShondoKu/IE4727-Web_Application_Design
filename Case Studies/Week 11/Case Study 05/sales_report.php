<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "javajam cs5";
$report_by_product = null;
$report_by_category = null;
$best_seller_info = '';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_report'])) {
        if (!empty($_POST['report_type']) && in_array('by_product', $_POST['report_type'])) {
            $sql = "SELECT p.name, SUM(oi.quantity) AS total_quantity, SUM(oi.price_at_sale * oi.quantity) AS total_sales
                    FROM order_items oi JOIN products p ON oi.product_id = p.id
                    GROUP BY p.name ORDER BY p.name";
            $report_by_product = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }

        if (!empty($_POST['report_type']) && in_array('by_category', $_POST['report_type'])) {
            $sql = "SELECT COALESCE(shot_type, 'Null') AS category, SUM(quantity) AS total_quantity, SUM(price_at_sale * quantity) AS total_sales
                    FROM order_items GROUP BY category ORDER BY category";
            $report_by_category = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }

        $best_seller_sql = "SELECT p.id, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id
                            GROUP BY p.id, p.name ORDER BY SUM(oi.price_at_sale * oi.quantity) DESC LIMIT 1";
        $best_seller = $pdo->query($best_seller_sql)->fetch(PDO::FETCH_ASSOC);

        if ($best_seller) {
            $popular_option_sql = "SELECT COALESCE(shot_type, 'N/A') as shot FROM order_items WHERE product_id = ?
                                   GROUP BY shot ORDER BY SUM(quantity) DESC LIMIT 1";
            $stmt = $pdo->prepare($popular_option_sql);
            $stmt->execute([$best_seller['id']]);
            $popular_option = $stmt->fetch(PDO::FETCH_ASSOC);

            $best_seller_info = "Popular option of best selling product: <strong>" . htmlspecialchars($best_seller['name']) . " (" . htmlspecialchars($popular_option['shot']) . ")</strong>";
        } else {
            $best_seller_info = "No sales data available to determine the best selling product.";
        }
    }
} catch (PDOException $e) {
    die("Error generating report: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Sales Report</title>
    <link rel="stylesheet" href="javajam.css">
    <style>
        h2,
        h3 {
            color: #422312;
            font-family: arial, sans-serif;
            margin: 15px 0 10px 0;
        }

        table {
            width: 70%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 30px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #422312;
            text-align: left;
        }

        th {
            background-color: #CDAB61;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #422312;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <header></header>
        <div id="leftcolumn">
            <nav>
                <a href="index.html">Home</a>
                <a href="menu.php">Menu</a>
                <a href="music.html">Music</a>
                <a href="jobs.html">Jobs</a>
                <hr style="width:80%;">
                <b>Admin</b>
                <a href="update_price.php">Update Prices</a>
                <a href="sales_report.php">Sales Report</a>
            </nav>
        </div>
        <div id="rightcolumn">
            <div class="content">
                <h2>Daily Sales Report</h2>
                <form action="sales_report.php" method="POST">
                    <p>Select reports to generate:</p>
                    <label><input type="checkbox" name="report_type[]" value="by_product"> Total sales by
                        products</label><br>
                    <label><input type="checkbox" name="report_type[]" value="by_category"> Total sales by
                        categories</label><br><br>
                    <input type="submit" name="generate_report" value="Generate Report">
                </form>
                <hr style="margin: 20px 0;">
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                    <h3>Report Results</h3>
                    <?php if ($report_by_product): ?>
                        <h4>Sales By Product</h4>
                        <table>
                            <tr>
                                <th>Product</th>
                                <th>Total Dollar Sales</th>
                                <th>Quantity Sold</th>
                            </tr>
                            <?php foreach ($report_by_product as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td>$<?php echo number_format($row['total_sales'], 2); ?></td>
                                    <td><?php echo $row['total_quantity']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                    <?php if ($report_by_category): ?>
                        <h4>Sales By Category</h4>
                        <table>
                            <tr>
                                <th>Category</th>
                                <th>Total Dollar Sales</th>
                                <th>Quantity Sold</th>
                            </tr>
                            <?php foreach ($report_by_category as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                    <td>$<?php echo number_format($row['total_sales'], 2); ?></td>
                                    <td><?php echo $row['total_quantity']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                    <?php if ($best_seller_info): ?>
                        <p><?php echo $best_seller_info; ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <footer>
            <i>
                <p>Copyright &copy; 2014 JavaJam Coffee House<br><u><a
                            href="mailto:azfarnasri@binazman.com">azfarnasri@binazman.com</a></u></p>
            </i>
        </footer>
    </div>
</body>

</html>