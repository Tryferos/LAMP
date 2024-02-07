<!DOCTYPE html>
<html>

<head>
    <title>Big shif perimenw gia to colpo grosso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/global.css">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
</head>
<?php
require_once("config.php");
include_once("types.php");
echo "<script src='scripts/publicHandler.js'></script>";
echo "<script src='scripts/indexHandler.js'></script>";
?>

<body>
    <section>
        <?php
        $date = date("j F");
        echo "<h1>Today, $date</h1>";
        ?>
        <div id='projects'>
            <div id='header'>
                <p>Your Projects</p>
                <img src="assets/add.svg" onclick='handleAddProject()' alt="add-project">
            </div>
            <ul id='projects-list' class="scrollbar">
                <?php
                include "projects.php";
                ?>

            </ul>
        </div>
    </section>
</body>

</html>