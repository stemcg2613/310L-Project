<?php
require_once "db.php";
$cartId = get_cart_id($conn);

// Update quantity
if (isset($_POST["update_qty"])) {
  $cartItemId = (int)($_POST["cart_item_id"] ?? 0);
  $qty = (int)($_POST["qty"] ?? 0);

  if ($qty <= 0) {
    mysqli_query($conn, "DELETE FROM cart_items WHERE cart_item_id = $cartItemId AND cart_id = $cartId");
  } else {
    mysqli_query($conn, "UPDATE cart_items SET quantity = $qty WHERE cart_item_id = $cartItemId AND cart_id = $cartId");
  }

  header("Location: cart.php");
  exit;
}

// Remove item
if (isset($_POST["remove_item"])) {
  $cartItemId = (int)($_POST["cart_item_id"] ?? 0);
  mysqli_query($conn, "DELETE FROM cart_items WHERE cart_item_id = $cartItemId AND cart_id = $cartId");
  header("Location: cart.php");
  exit;
}

// Load cart items
$sql = "
  SELECT
    ci.cart_item_id,
    ci.quantity,
    p.product_name,
    p.product_cost
  FROM cart_items ci
  JOIN products p ON p.product_id = ci.product_id
  WHERE ci.cart_id = $cartId
  ORDER BY ci.cart_item_id
";
$itemsRes = mysqli_query($conn, $sql);

// Calculate total
$items = [];
$total = 0.0;
while ($row = mysqli_fetch_assoc($itemsRes)) {
  $rowTotal = (float)$row["product_cost"] * (int)$row["quantity"];
  $total += $rowTotal;
  $row["row_total"] = $rowTotal;
  $items[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Online Store - Cart</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #000; padding: 10px; text-align: left; }
    th { background: #bfe3ff; }
    tr:nth-child(even) { background: #f2f2f2; }
    .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .btn { padding: 6px 10px; cursor: pointer; }
    input[type="number"]{ width: 80px; }
  </style>
</head>
<body>

<div class="topbar">
  <h2>Your Cart</h2>
  <a href="index.php">Back to Products</a>
</div>

<?php if (count($items) === 0): ?>
  <p>Your cart is empty.</p>
<?php else: ?>
  <table>
    <tr>
      <th>Item</th>
      <th>Cost</th>
      <th>Qty</th>
      <th>Row Total</th>
      <th>Update</th>
      <th>Remove</th>
    </tr>

    <?php foreach ($items as $it): ?>
      <tr>
        <td><?= htmlspecialchars($it["product_name"]) ?></td>
        <td>$<?= number_format((float)$it["product_cost"], 2) ?></td>
        <td>
          <form method="POST" style="margin:0; display:flex; gap:8px; align-items:center;">
            <input type="hidden" name="cart_item_id" value="<?= (int)$it["cart_item_id"] ?>">
            <input type="number" name="qty" min="0" value="<?= (int)$it["quantity"] ?>">
        </td>
        <td>$<?= number_format((float)$it["row_total"], 2) ?></td>
        <td>
            <button class="btn" type="submit" name="update_qty">Save</button>
          </form>
        </td>
        <td>
          <form method="POST" style="margin:0;">
            <input type="hidden" name="cart_item_id" value="<?= (int)$it["cart_item_id"] ?>">
            <button class="btn" type="submit" name="remove_item">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>

    <tr>
      <th colspan="3" style="text-align:right;">Total:</th>
      <th colspan="3">$<?= number_format($total, 2) ?></th>
    </tr>
  </table>
<?php endif; ?>

</body>
</html>
