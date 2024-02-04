<!DOCTYPE html>
<html>
<head>
    <title>Project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <?php
        echo '<link rel="stylesheet" type="text/css" href="styles/global.css">';
        echo '<link rel="stylesheet" type="text/css" href="styles/project.css">';
    ?>
</head>
<?php
    require_once("config.php");
    include_once("types.php");
    echo "<script src='scripts/handler.js'></script>";
?>
<body>
    <header id="header">
        <p><?php 
        $cid = $_REQUEST['id'];
        $name = Category::fetchName($cid);
        echo "<input type='text' onkeypress='onChange(event)' value='$name' name='title' id='c$cid' class='title-input'/>" ?></p>
        <img src="assets/menu.svg" alt="menu" id="menu"/>
    </header>
    <ul id="tasks">
        <div>
            <p>Tasks</p>
            <?php
                $date = date('Y-m-d\TH:i');
                $complete_until = Category::fetchCompleteUntil($cid);
                echo "<input min='$date' onchange='onChange(event)' value='$complete_until' type='datetime-local' id='calendar-input'/>";
            ?>
        </div>
        <?php
            include 'tasks.php';
        ?>
    </ul>
    <?php 
    $cid = $_REQUEST['id'];
    echo "<input type='button' hidden value='Save Changes' id='save-btn' class='save-changes' onclick='handleSave($cid)'/>"
    ?>
</body>
</html>
