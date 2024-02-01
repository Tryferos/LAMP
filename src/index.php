<!DOCTYPE html>
<html>
<head>
    <title>Big shif perimenw gia to colpo grosso</title>
</head>
<body>
<?php

// This is a basic PHP file
        include_once("config.php");

        //!SMALL DEMO
        $result = mysqli_query($mysqli, "SELECT * FROM tododb.Todo");

        while($res = $result->fetch_object()){
            $data[] = $res;
        }

        foreach($data as $item){
            echo "<br>";
            console_log($item);
            echo $item->title;
            echo "<br>";
        }
?>

</body>
</html>
