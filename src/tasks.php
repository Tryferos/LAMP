<?php
require_once("config.php");
require_once("types.php");
$cid = $_REQUEST['id'];
$todos = Todo::fetchAll($cid);
foreach ($todos as $todo) {
    $completed = $todo->completed ? "completed" : "";
    echo "<li data-completed='$completed' class='task' id='" . $todo->id . "'>";
    echo "<div class='todo-info'>";
    echo "<div data-completed='$completed' class='checkbox-input' onclick='handleToggle(event)'></div>";
    echo "<input min='4' type='text' onkeypress='onChange(event)' placeholder='Creating a new Task...' class='todo-title-input' value='$todo->title' />";
    echo "<img data-toggled='false' src='assets/arrow.svg' id='arrow-$todo->id' alt='open' class='arrow-todo' onclick='handleDescription(event, $todo->id)' />";
    echo "</div>";
    echo "<textarea onkeypress='onChange(event)' data-toggled='false' id='description-$todo->id' class='description-input scrollbar' placeholder='Provide a description about your task'>$todo->description</textarea>";
    echo "<img src='assets/delete.svg' id='delete-$todo->id' alt='delete' class='delete-todo' onclick='handleDeleteTodo(event)' />";
    echo "</li>";
}
if (count($todos) == 0) {
    echo "<li><p id='no-todos'>No tasks yet.</p></li>";
}
