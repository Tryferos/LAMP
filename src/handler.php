<?php
require_once("config.php");
require_once("types.php");
$requestBody = json_decode(file_get_contents('php://input'), true);
$action = $requestBody["action"];
header('Content-Type: application/json');
if($action == "toggleTodo"){
    $id = $requestBody["id"];
    Todo::toggleTodo($id);
    return;
}
if($action == "save-category"){
    $todos = $requestBody["todos"];
    $cTitle = $requestBody["title"];
    $complete_until = $requestBody["complete_until"];
    $cid = $requestBody["id"];
    foreach($todos as $todo){
        $id = $todo["id"];
        $title = $todo["title"];
        $description = $todo["description"];
        $completed = $todo["completed"];
        Todo::updateTodo($id, $title, $description, $completed);
    }
    echo json_encode(Category::updateCategory($cid, $cTitle, $complete_until));
}

?>