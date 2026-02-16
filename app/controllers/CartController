<?php
// app/controllers/CartController.php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../models/Cart.php";

class CartController extends BaseController {

  public function index(): void {
    $cart = new Cart($this->conn);
    $data = $cart->getItems();
    $items = $data["items"];
    $total = $data["total"];

    // View expects: $items, $total
    require __DIR__ . "/../views/cart/index.php";
  }

  public function update(): void {
    $cartItemId = (int)($_POST["cart_item_id"] ?? 0);
    $qty = (int)($_POST["qty"] ?? 0);

    $cart = new Cart($this->conn);
    if ($cartItemId > 0) {
      $cart->updateQty($cartItemId, $qty);
    }

    $this->redirect("index.php?controller=cart&action=index");
  }

  public function remove(): void {
    $cartItemId = (int)($_POST["cart_item_id"] ?? 0);

    $cart = new Cart($this->conn);
    if ($cartItemId > 0) {
      $cart->removeItem($cartItemId);
    }

    $this->redirect("index.php?controller=cart&action=index");
  }
}
