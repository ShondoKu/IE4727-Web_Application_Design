<?php
// --- Database Connection ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "javajam cs5";

try {
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // --- Fetch All Products ---
  $stmt = $pdo->query("SELECT name, price_single, price_double FROM products");

  // THE FIX IS HERE:
  // 1. Fetch as a standard associative array.
  $db_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // 2. Loop through the results to create an array keyed by the product name.
  $products_by_name = [];
  foreach ($db_products as $product) {
    $products_by_name[$product['name']] = $product;
  }

  // --- Prepare a simplified array for JavaScript using the new organized array ---
  $js_prices = [
    'java' => (float) $products_by_name['Just Java']['price_single'],
    'cafe' => [
      'single' => (float) $products_by_name['Cafe au Lait']['price_single'],
      'double' => (float) $products_by_name['Cafe au Lait']['price_double'],
    ],
    'capp' => [
      'single' => (float) $products_by_name['Iced Cappuccino']['price_single'],
      'double' => (float) $products_by_name['Iced Cappuccino']['price_double'],
    ]
  ];

} catch (PDOException $e) {
  die("Error: Could not connect to the database. " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>JavaJam - Menu</title>
  <link rel="stylesheet" href="javajam.css">
  <style>
    table {
      border: 1px solid black;
      margin-top: 20px;
      text-align: left;
      width: 100%;
      border-collapse: collapse;
    }

    tr:nth-of-type(even) {
      background-color: #cdab61;
    }

    td,
    th {
      padding: 8px;
      vertical-align: top;
    }

    input[type="number"] {
      width: 60px;
    }

    input[readonly] {
      background: #f0f0f0;
      border: 1px solid #ccc;
      padding: 3px 6px;
      width: 80px;
      text-align: right;
    }

    .shot-options label {
      margin-right: 10px;
      font-weight: normal;
    }

    .checkout-btn {
      padding: 10px 20px;
      background-color: #422312;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 16px;
      margin-top: 10px;
    }

    .checkout-btn:hover {
      background-color: #6a4a3a;
    }
  </style>
  <script>
    const PRICES = <?php echo json_encode($js_prices); ?>;
  </script>
  <script src="menu_Update.js" defer></script>
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
        <hr>
        <b>Admin</b>
        <a href="update_price.php">Update Prices</a>
        <a href="sales_report.php">Sales Report</a>
      </nav>
    </div>
    <div id="rightcolumn">
      <div class="content">
        <h2>Coffee at JavaJam</h2>
        <form action="checkout.php" method="POST">
          <table>
            <tr>
              <th>Coffee</th>
              <th>Description and Price</th>
              <th style="width: 160px">Quantity</th>
              <th style="width: 120px">Sub-Total</th>
            </tr>
            <tr>
              <td>Just Java</td>
              <td>Regular house blend, decaffeinated coffee, or flavor of the day.<br><strong>Price:</strong>
                $<?php echo number_format($js_prices['java'], 2); ?></td>
              <td><input type="number" name="qty_java" min="0" value="0"></td>
              <td><input type="text" name="subtotal_java" value="$0.00" readonly></td>
            </tr>
            <tr>
              <td>Cafe au Lait</td>
              <td>House blend coffee with steamed milk.<br><strong>Price:</strong> Single
                $<?php echo number_format($js_prices['cafe']['single'], 2); ?>; Double
                $<?php echo number_format($js_prices['cafe']['double'], 2); ?>
                <div class="shot-options">
                  <label><input type="radio" name="shot_cafe" value="single" checked> Single</label>
                  <label><input type="radio" name="shot_cafe" value="double"> Double</label>
                </div>
              </td>
              <td><input type="number" name="qty_cafe" min="0" value="0"></td>
              <td><input type="text" name="subtotal_cafe" value="$0.00" readonly></td>
            </tr>
            <tr>
              <td>Iced Cappuccino</td>
              <td>Sweetened espresso blended with icy-cold milk.<br><strong>Price:</strong> Single
                $<?php echo number_format($js_prices['capp']['single'], 2); ?>; Double
                $<?php echo number_format($js_prices['capp']['double'], 2); ?>
                <div class="shot-options">
                  <label><input type="radio" name="shot_capp" value="single" checked> Single</label>
                  <label><input type="radio" name="shot_capp" value="double"> Double</label>
                </div>
              </td>
              <td><input type="number" name="qty_capp" min="0" value="0"></td>
              <td><input type="text" name="subtotal_capp" value="$0.00" readonly></td>
            </tr>
          </table>
          <div style="text-align: right; margin-top: 20px">
            <strong>Total:</strong> $<span id="total">0.00</span><br>
            <button type="submit" class="checkout-btn">Check Out</button>
          </div>
        </form>
      </div>
    </div>
    <footer>
      <i>
        <p>Copyright &copy; 2014 JavaJam Coffee House<br><u><a
              href="mailto:your_email@domain.com">your_email@domain.com</a></u></p>
      </i>
    </footer>
  </div>
</body>

</html>