<?php
// public/index.php
// Front controller / 

require_once __DIR__ . "/../app/models/Database.php";

$conn = Database::connect();

$controller = strtolower($_GET["controller"] ?? "product");
$action = strtolower($_GET["action"] ?? "index");

$controllerClass = ucfirst($controller) . "Controller";
$controllerFile  = __DIR__ . "/../app/controllers/" . $controllerClass . ".php";

if (!file_exists($controllerFile)) {
  http_response_code(404);
  echo "Controller not found.";
  exit;
}

require_once $controllerFile;

if (!class_exists($controllerClass)) {
  http_response_code(500);
  echo "Controller class missing.";
  exit;
}

$instance = new $controllerClass($conn);

if (!method_exists($instance, $action)) {
  http_response_code(404);
  echo "Action not found.";
  exit;
}

// Run the controller action
$instance->$action();
