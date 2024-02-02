<?php 
require_once("config.php");
require_once("types.php");
$cid = $_REQUEST['id'];
$todos = Todo::fetchAll($cid);
foreach($todos as $todo){
    $completed = $todo->completed ? "completed" : "";
    echo "<li onclick='handleDetails(this)' class='task' id='".$todo->id."'>";
        echo "<div class='todo-info'>";
            echo "<div data-completed='$completed' class='checkbox' onclick='handleToggle(this)'></div>";
            echo "<input type='text' placeholder='Enter a title' class='title' value='$todo->title'/>";
        echo "</div>";
    echo "</li>";
}
?>