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
?>
<body>
    <header id="header">
        <p><?php echo Category::fetchName($_REQUEST['id']) ?></p>
        <img src="assets/menu.svg" alt="menu" id="menu"/>
    </header>
    <ul id="tasks">
        <p>Tasks</p>
        <?php
            include 'tasks.php';
        ?>
    </ul>
</body>
</html>
