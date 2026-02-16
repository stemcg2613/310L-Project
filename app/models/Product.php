<?php
// app/models/Product.php

class Product {
  private mysqli $conn;

  public function __construct(mysqli $conn) {
    $this->conn = $conn;
  }

  public function getActiveProducts() {
    $sql = "SELECT product_id, product_name, product_description, product_cost
            FROM products
            WHERE is_active = 1
            ORDER BY product_id";
    return mysqli_query($this->conn, $sql);
  }
}
