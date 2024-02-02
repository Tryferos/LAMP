<!DOCTYPE html>
<html>
<head>
    <title>Project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/global.css">
    <link rel="stylesheet" type="text/css" href="styles/project.css">
</head>
<?php
    require_once("config.php");
    include_once("types.php");
?>
<script src='index.js'></script>
<body>
    <header>
        <h1>
            <?php echo Category::fetchName($_REQUEST['id']) ?>
            <img src="assets/menu.svg" alt="menu" id="menu"/>
        </h1>
    </header>
    

</body>
</html>
