<?php 
require_once("config.php");
require_once("types.php");
$cid = $_REQUEST['id'];
$todos = Todo::fetchAll($cid);
foreach($todos as $todo){
    $completed = $todo->completed ? "checked" : "";
    echo "<li class='task' id='task-".$todo->id."'>";
        echo "<div class='todo-info'>";
            echo "<input type='checkbox' onclick='toggleComplete' $completed/>";
            echo "<p class='title'>".$todo->title."</p>";
        echo "</div>";
    echo "</li>";
}
?>