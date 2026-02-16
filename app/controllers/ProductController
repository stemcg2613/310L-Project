<?php
// app/controllers/ProductController.php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../models/Product.php";
require_once __DIR__ . "/../models/Cart.php";

class ProductController extends BaseController {

  public function index(): void {
    $productModel = new Product($this->conn);
    $products = $productModel->getActiveProducts();

    // View expects: $products
    require __DIR__ . "/../views/products/index.php";
  }

  public function add(): void {
    $productId = (int)($_POST["product_id"] ?? 0);
    $qty = (int)($_POST["qty"] ?? 1);
    if ($qty < 1) $qty = 1;

    $cart = new Cart($this->conn);
    if ($productId > 0) {
      $cart->addItem($productId, $qty);
    }

    $this->redirect("index.php?controller=product&action=index");
  }
}
