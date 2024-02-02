<?php
class Todo{
    public $id;
    public $title;
    public $description;
    public $completed;
    public $date;
    public $category;

    public function __construct($id, $title, $description, $completed, $date, $cid)
    {
        
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->completed = $completed;
        $this->date = $date;
        $result = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM tododb.Category WHERE id = $cid");
        $this->category = $result->fetch_object();
    }

    public static function fetchId($id){
        $result = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM tododb.Todo WHERE id = $id");
        $result = $result->fetch_object();
        return new Todo(
            $result->id,
            $result->title,
            $result->description,
            $result->completed,
            $result->date,
            $result->cid
        );
    }

    public static function fetchAll($cid){
        $result = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM tododb.Todo where cid = $cid");
        $todos = [];
        while($row = $result->fetch_object()){
            $todos[] = new Todo(
                $row->id,
                $row->title,
                $row->description,
                $row->completed,
                $row->date,
                $row->cid
            );
        }
        return $todos;
    }

    public static function fetchName($id){
        return Todo::fetchId($id)->title;
    }


    
}
class Category{
    public $id;
    public $title;
    public $date;
    public $complete_until;
    public $todos;

    public function __construct($id, $title, $date, $complete_until,$todos = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->date = $date;
        $this->complete_until = $complete_until;
        $this->todos = $todos;
    }

    public static function fetchId($id){
        $result = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM tododb.Category WHERE id = $id");
        $result = $result->fetch_object();
        $todosResult = Todo::fetchAll($id);
        return new Category(
            $result->id,
            $result->title,
            $result->date,
            $result->complete_until,
            $todosResult
        );
    }

    public static function fetchName($id){
        return Category::fetchId($id)->title;
    }
}
?>