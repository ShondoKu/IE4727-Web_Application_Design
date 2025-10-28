<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "javajam cs5";
$message = '';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $prices = [];
    $stmt = $pdo->query("SELECT id, name, price_single, price_double FROM products");
    foreach ($stmt as $row) {
        $prices[$row['name']] = ['id' => $row['id'], 'single' => $row['price_single'], 'double' => $row['price_double']];
    }

    $pdo->beginTransaction();

    $pdo->exec("INSERT INTO orders (order_date) VALUES (NOW())");
    $order_id = $pdo->lastInsertId();

    $insert_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, shot_type, price_at_sale) VALUES (?, ?, ?, ?, ?)");
    $items_in_order = 0;

    if (isset($_POST['qty_java']) && $_POST['qty_java'] > 0) {
        $insert_stmt->execute([$order_id, $prices['Just Java']['id'], $_POST['qty_java'], null, $prices['Just Java']['single']]);
        $items_in_order++;
    }
    
    if (isset($_POST['qty_cafe']) && $_POST['qty_cafe'] > 0) {
        $shot = $_POST['shot_cafe'];
        $price = $prices['Cafe au Lait'][$shot];
        $insert_stmt->execute([$order_id, $prices['Cafe au Lait']['id'], $_POST['qty_cafe'], $shot, $price]);
        $items_in_order++;
    }

    if (isset($_POST['qty_capp']) && $_POST['qty_capp'] > 0) {
        $shot = $_POST['shot_capp'];
        $price = $prices['Iced Cappuccino'][$shot];
        $insert_stmt->execute([$order_id, $prices['Iced Cappuccino']['id'], $_POST['qty_capp'], $shot, $price]);
        $items_in_order++;
    }

    if ($items_in_order > 0) {
        $pdo->commit();
        $message = "Your order has been placed successfully!";
    } else {
        $pdo->rollBack();
        $message = "Your cart was empty. No order was placed.";
    }

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $message = "Error placing order: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="javajam.css">
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
                <h2>Order Confirmation</h2>
                <p><?php echo $message; ?></p>
                <a href="menu.php"><b>&larr; Back to Menu</b></a>
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