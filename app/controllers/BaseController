<?php
// app/controllers/BaseController.php

class BaseController {
  protected mysqli $conn;

  public function __construct(mysqli $conn) {
    $this->conn = $conn;
  }

  protected function redirect(string $url): void {
    header("Location: " . $url);
    exit;
  }
}
