<?php
require_once "db.php";
$cartId = get_cart_id($conn);

// Handle Add to Cart
if (isset($_POST["add_to_cart"])) {
  $productId = (int)($_POST["product_id"] ?? 0);
  $qty = (int)($_POST["qty"] ?? 1);
  if ($qty < 1) $qty = 1;

  // If product already in cart, increase quantity; else insert
  $checkSql = "SELECT cart_item_id, quantity FROM cart_items WHERE cart_id = $cartId AND product_id = $productId";
  $checkRes = mysqli_query($conn, $checkSql);
  $existing = mysqli_fetch_assoc($checkRes);

  if ($existing) {
    $newQty = (int)$existing["quantity"] + $qty;
    mysqli_query($conn, "UPDATE cart_items SET quantity = $newQty WHERE cart_item_id = " . (int)$existing["cart_item_id"]);
  } else {
    mysqli_query($conn, "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ($cartId, $productId, $qty)");
  }

  header("Location: index.php");
  exit;
}

// Load products
$products = mysqli_query($conn, "SELECT product_id, product_name, product_description, product_cost FROM products WHERE is_active = 1 ORDER BY product_id");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Online Store - Products</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #000; padding: 10px; text-align: left; }
    th { background: #bfe3ff; }
    tr:nth-child(even) { background: #f2f2f2; }
    .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .btn { padding: 6px 10px; cursor: pointer; }
    input[type="number"]{ width: 70px; }
  </style>
</head>
<body>

<div class="topbar">
  <h2>Products</h2>
  <a href="cart.php">View Cart</a>
</div>

<table>
  <tr>
    <th>ID</th>
    <th>Product</th>
    <th>Description</th>
    <th>Cost</th>
    <th>Add</th>
  </tr>

  <?php while ($p = mysqli_fetch_assoc($products)) : ?>
    <tr>
      <td><?= (int)$p["product_id"] ?></td>
      <td><?= htmlspecialchars($p["product_name"]) ?></td>
      <td><?= htmlspecialchars($p["product_description"]) ?></td>
      <td>$<?= number_format((float)$p["product_cost"], 2) ?></td>
      <td>
        <form method="POST" style="margin:0;">
          <input type="hidden" name="product_id" value="<?= (int)$p["product_id"] ?>">
          <input type="number" name="qty" min="1" value="1">
          <button class="btn" type="submit" name="add_to_cart">Add to Cart</button>
        </form>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
