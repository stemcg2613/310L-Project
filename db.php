<?php
// db.php
$DB_HOST = "localhost";
$DB_USER = "ecpi_user";
$DB_PASS = "Password1";
$DB_NAME = "online_store";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
  die("DB connection failed: " . mysqli_connect_error());
}

// Get or create a cart for this browser session
function get_cart_id(mysqli $conn): int {
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  if (!empty($_SESSION["cart_id"])) {
    return (int)$_SESSION["cart_id"];
  }

  // Create a new cart
  mysqli_query($conn, "INSERT INTO carts () VALUES ()");
  $cartId = (int)mysqli_insert_id($conn);

  $_SESSION["cart_id"] = $cartId;
  return $cartId;
}
