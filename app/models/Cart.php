<?php
// app/models/Cart.php

class Cart {
  private mysqli $conn;

  public function __construct(mysqli $conn) {
    $this->conn = $conn;
    $this->ensureSession();
  }

  private function ensureSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  public function getCartId(): int {
    if (!empty($_SESSION["cart_id"])) {
      return (int)$_SESSION["cart_id"];
    }

    // Create a new cart row
    mysqli_query($this->conn, "INSERT INTO carts () VALUES ()");
    $cartId = (int)mysqli_insert_id($this->conn);

    $_SESSION["cart_id"] = $cartId;
    return $cartId;
  }

  public function addItem(int $productId, int $qty): void {
    $cartId = $this->getCartId();
    if ($qty < 1) $qty = 1;

    $checkSql = "SELECT cart_item_id, quantity
                 FROM cart_items
                 WHERE cart_id = $cartId AND product_id = $productId";
    $checkRes = mysqli_query($this->conn, $checkSql);
    $existing = mysqli_fetch_assoc($checkRes);

    if ($existing) {
      $newQty = (int)$existing["quantity"] + $qty;
      $cartItemId = (int)$existing["cart_item_id"];
      mysqli_query($this->conn, "UPDATE cart_items SET quantity = $newQty WHERE cart_item_id = $cartItemId");
    } else {
      mysqli_query($this->conn, "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ($cartId, $productId, $qty)");
    }
  }

  public function updateQty(int $cartItemId, int $qty): void {
    $cartId = $this->getCartId();
    if ($qty <= 0) {
      mysqli_query($this->conn, "DELETE FROM cart_items WHERE cart_item_id = $cartItemId AND cart_id = $cartId");
    } else {
      mysqli_query($this->conn, "UPDATE cart_items SET quantity = $qty WHERE cart_item_id = $cartItemId AND cart_id = $cartId");
    }
  }

  public function removeItem(int $cartItemId): void {
    $cartId = $this->getCartId();
    mysqli_query($this->conn, "DELETE FROM cart_items WHERE cart_item_id = $cartItemId AND cart_id = $cartId");
  }

  public function getItems(): array {
    $cartId = $this->getCartId();
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
    $itemsRes = mysqli_query($this->conn, $sql);

    $items = [];
    $total = 0.0;
    while ($row = mysqli_fetch_assoc($itemsRes)) {
      $rowTotal = (float)$row["product_cost"] * (int)$row["quantity"];
      $total += $rowTotal;
      $row["row_total"] = $rowTotal;
      $items[] = $row;
    }

    return ["items" => $items, "total" => $total];
  }
}
