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
        $result = mysqli_query($mysqli, "SELECT * FROM (table_name)");

        while($res = $result->fetch_object()){
            $data[] = $res;
        }

        foreach($data as $item){
            echo "<br>";
            echo $item->(table_field) . "+" . $item->(table_field);
            echo "<br>";
        }
?>

</body>
</html>
