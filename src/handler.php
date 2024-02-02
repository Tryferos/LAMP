<?php
require_once("config.php");
require_once("types.php");
$requestBody = json_decode(file_get_contents('php://input'), true);
$action = $requestBody["action"];
if($action == "toggleTodo"){
    $id = $requestBody["id"];
    return;
}
if($action == "toggleDescription"){
    $id = $requestBody["id"];
    $todo = Todo::fetchId($id);
    echo $todo->description;
    return;
}
?>