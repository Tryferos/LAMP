<?php 
require_once("config.php");
require_once("types.php");
$cid = $_REQUEST['id'];
$todos = Todo::fetchAll($cid);
foreach($todos as $todo){
    $completed = $todo->completed ? "completed" : "";
    echo "<li  class='task' id='".$todo->id."'>";
        echo "<div class='todo-info'>";
            echo "<div data-completed='$completed' class='checkbox-input' onclick='handleToggle(event)'></div>";
            echo "<input type='text' onkeypress='onChange(event)' placeholder='Enter a title' class='todo-title-input' value='$todo->title' />";
            echo "<img data-toggled='false' src='assets/arrow.svg' id='arrow-$todo->id' alt='open' class='arrow-todo' onclick='handleDescription(event, $todo->id)' />";
        echo "</div>";
        echo "<textarea onkeypress='onChange(event)' data-toggled='false' id='description-$todo->id' class='description-input' placeholder='Enter a description'>$todo->description</textarea>";
    echo "</li>";
}
?>