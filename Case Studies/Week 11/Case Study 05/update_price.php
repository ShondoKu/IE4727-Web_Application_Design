<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "javajam cs5";
$update_message = '';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_prices'])) {
        if (!empty($_POST['selected_products'])) {
            $stmt = $pdo->prepare("UPDATE products SET price_single = :price_single, price_double = :price_double WHERE id = :id");

            foreach ($_POST['selected_products'] as $id) {
                $price_single = $_POST['price_single'][$id];
                $price_double = isset($_POST['price_double'][$id]) ? $_POST['price_double'][$id] : null;

                $stmt->execute([
                    ':price_single' => $price_single,
                    ':price_double' => $price_double,
                    ':id' => $id
                ]);
            }
            $update_message = "Prices updated successfully!";
        } else {
            $update_message = "Please select a product to update.";
        }
    }

    $products = $pdo->query("SELECT id, name, price_single, price_double FROM products ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Price Update</title>
    <link rel="stylesheet" href="javajam.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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

        input[type="number"] {
            width: 80px;
            padding: 5px;
        }

        input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
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
                <h2>Product Price Update</h2>
                <p>Select products and enter new prices. The updated prices will be shown for confirmation.</p>
                <?php if ($update_message): ?>
                    <p style="font-weight: bold;"><?php echo $update_message; ?></p>
                <?php endif; ?>
                <form action="update_price.php" method="POST">
                    <table>
                        <tr>
                            <th>Update?</th>
                            <th>Product</th>
                            <th>New Single Price ($)</th>
                            <th>New Double Price ($)</th>
                        </tr>
                        <?php foreach ($products as $row): ?>
                            <tr>
                                <td><input type="checkbox" name="selected_products[]" value="<?php echo $row['id']; ?>">
                                </td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><input type="number" name="price_single[<?php echo $row['id']; ?>]"
                                        value="<?php echo number_format($row['price_single'], 2); ?>" step="0.01" min="0">
                                </td>
                                <td>
                                    <?php if ($row['price_double'] !== null): ?>
                                        <input type="number" name="price_double[<?php echo $row['id']; ?>]"
                                            value="<?php echo number_format($row['price_double'], 2); ?>" step="0.01" min="0">
                                    <?php else:
                                        echo 'N/A';
                                    endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <br>
                    <input type="submit" name="update_prices" value="Update Selected Prices">
                </form>
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