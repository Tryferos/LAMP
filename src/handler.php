<?php
require_once("config.php");
require_once("types.php");
$requestBody = json_decode(file_get_contents('php://input'), true);
$action = $requestBody["action"];
header('Content-Type: application/json');
if ($action == "toggleTodo") {
    $id = $requestBody["id"];
    Todo::toggleTodo($id);
    return;
}
if ($action == "save-category") {
    $todos = $requestBody["todos"];
    $cTitle = $requestBody["title"];
    $complete_until = $requestBody["complete_until"];
    $cid = $requestBody["id"];
    $todosArray = [];
    foreach ($todos as $todo) {
        $id = $todo["id"];
        $title = $todo["title"];
        $description = $todo["description"];
        $completed = $todo["completed"];
        echo json_encode($completed);
        $todosArray[] = Todo::updateTodo($id, $title, $description, $completed);
    }
    Category::updateCategory($cid, $cTitle, $complete_until);
    echo json_encode($todosArray);
    return;
}
if ($action == "delete-todo") {
    $id = $requestBody["id"];
    echo json_encode(Todo::deleteTodo($id));
    return;
}
if ($action == "create-todo") {
    $cid = $requestBody["cid"];
    try {
        echo json_encode(Todo::createEmptyTodo($cid));
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
    return;
}

echo json_encode(["error" => "Invalid action"]);
