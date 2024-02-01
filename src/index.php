<!DOCTYPE html>
<html>
<head>
    <title>Big shif perimenw gia to colpo grosso</title>
</head>
<body>
<?php

// This is a basic PHP file
        include_once("config.php");
        include_once("types.php");

        // //!SMALL DEMO
        // $result = mysqli_query($mysqli, "SELECT * FROM tododb.Todo");

        // while($res = $result->fetch_object()){
        //     $data[] = $res;
        // }

        // foreach($data as $item){
        //     echo "<br>";
        //     console_log($item);
        //     $todo = new Todo(
        //         $item->id,
        //         $item->title,
        //         $item->description,
        //         $item->completed,
        //         $item->date,
        //         $item->cid
        //     );
        //     echo $item->title;
        //     console_log($todo);
        //     echo "<br>";
        // }
        $result = Todo::fetchId(1);
        console_log($result);
?>

</body>
</html>
