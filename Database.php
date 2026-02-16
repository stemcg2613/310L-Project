<?php
// app/models/Database.php
// Simple DB helper for this course project (mysqli)

class Database {
  public static function connect(): mysqli {
    $DB_HOST = "localhost";
    $DB_USER = "ecpi_user";
    $DB_PASS = "Password1";
    $DB_NAME = "online_store";

    $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    if (!$conn) {
      die("DB connection failed: " . mysqli_connect_error());
    }
    return $conn;
  }
}
