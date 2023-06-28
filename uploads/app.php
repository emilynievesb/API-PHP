<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    die();
}
require_once "../vendor/autoload.php";

// Create Router instance
$router = new \Bramus\Router\Router();

//Routes
$router->get("/campus", function () {
    $instance = APP\campus\campus::getInstance(json_decode(file_get_contents("php://input"), true));
    $instance->get_tables();
});
$router->get("/{tabla}", function ($tabla) {
    $class = "APP\\" . $tabla . "\\" . $tabla;
    $method = "getAll_" . $tabla;
    $instance = $class::getInstance(json_decode(file_get_contents("php://input"), true));
    $instance->$method();
});
$router->post("/{tabla}/{id}", function ($tabla, $id) {
    $class = "APP\\" . $tabla . "\\" . $tabla;
    $method = "get_" . $tabla;
    $instance = $class::getInstance(json_decode(file_get_contents("php://input"), true));
    $instance->$method($id);
});
$router->post("/{tabla}", function ($tabla) {
    $class = "APP\\" . $tabla . "\\" . $tabla;
    $method = "post_" . $tabla;
    $instance = $class::getInstance(json_decode(file_get_contents("php://input"), true));
    $instance->$method();
});
$router->put("/{tabla}/{id}", function ($tabla, $id) {
    $class = "APP\\" . $tabla . "\\" . $tabla;
    $method = "update_" . $tabla;
    $instance = $class::getInstance(json_decode(file_get_contents("php://input"), true));
    $instance->$method($id);
});
$router->delete("/{tabla}/{id}", function ($tabla, $id) {
    $class = "APP\\" . $tabla . "\\" . $tabla;
    $method = "delete_" . $tabla;
    $instance = $class::getInstance(json_decode(file_get_contents("php://input"), true));
    $instance->$method($id);
});
$router->run();


?>