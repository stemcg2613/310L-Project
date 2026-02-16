<?php /* app/views/products/index.php */ ?>
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
  <a href="index.php?controller=cart&action=index">View Cart</a>
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
        <form method="POST" action="index.php?controller=product&action=add" style="margin:0;">
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
