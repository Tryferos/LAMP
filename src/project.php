<!DOCTYPE html>
<html>
<?php
require_once("config.php");
include_once("types.php");
echo "<script src='scripts/publicHandler.js'></script>";
echo "<script src='scripts/projectHandler.js'></script>";
?>

<head>
    <?php
    if (array_key_exists('id', $_REQUEST)) {
        $cid = $_REQUEST['id'];
        $name = Category::fetchName($cid);
        echo "<title>Project: $name</title>";
    } else {
        echo "<title>Project does not exist</title>";
    }
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <?php
    echo '<link rel="stylesheet" type="text/css" href="styles/global.css">';
    echo '<link rel="stylesheet" type="text/css" href="styles/project.css">';
    echo '<link rel="stylesheet" type="text/css" href="styles/menu.css">';
    ?>
</head>

<body>
    <header id="header">
        <img src="assets/back.svg" alt="back" id="back" onclick="handleBack()" />
        <?php
        if (!array_key_exists('id', $_REQUEST)) {
            echo "<p id='not-exist'>This Project does not exist</p>";
            return;
        }
        $cid = $_REQUEST['id'];
        $exists = Category::projectExists($cid);
        if (!$exists) {
            echo "<p id='not-exist'>This Project does not exist</p>";
            return;
        }
        $name = Category::fetchName($cid);
        echo "<input type='text' placeholder='Creating a new Project...' onkeypress='onChange(event)' value='$name' name='title' id='c$cid' class='title-input'/>" ?>
        <img src="assets/menu.svg" alt="menu" id="menu-mobile" />
        <?php
        include "menu.php";
        ?>
    </header>
    <ul id="tasks">
        <div id='tasks-title'>
            <p>Tasks</p>
            <?php
            $date = date('Y-m-d\TH:i');
            $cid = $_REQUEST['id'];
            $complete_until = Category::fetchCompleteUntil($cid);
            echo "<input min='$date' onchange='onChange(event)' value='$complete_until' type='datetime-local' id='calendar-input'/>";
            ?>
        </div>
        <?php
        //Render tasks from server
        echo "<div id='tasks-container' class='scrollbar'>";
        include 'tasks.php';
        echo "</div>";
        ?>
        <div id="task-footer">
            <?php
            $cid = $_REQUEST['id'];
            $tasks = count(Todo::fetchAll($cid));
            $completed = count(Todo::fetchCompleted($cid));
            echo "<p id='task-count'>$completed/$tasks <span>completed</span></p>";
            echo "<button onclick='handleCreateTodo($cid)' id='add-btn'>";
            echo "<p>Add Task</p>";
            echo "<img src='assets/add.svg' alt='add' id='add-task'/>";
            echo "</button>";
            $cid = $_REQUEST['id'];
            echo "<button class='save-changes' hidden id='save-btn'>";
            echo "<img src='assets/confirm.svg' onclick='handleSaveChanges($cid)' alt='save' id='save-changes'/>";
            echo "<p>Save Changes</p>";
            echo "<img src='assets/cancel.svg' onclick='handleCancelChanges()' alt='cancel' id='cancel-changes'/>";
            echo "</div>";
            ?>
        </div>
    </ul>
</body>

</html>