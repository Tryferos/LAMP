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

    
}
class Category{
    public $id;
    public $title;
    public $date;
    public $complete_until;

    public function __construct($id, $title, $date, $complete_until)
    {
        $this->id = $id;
        $this->title = $title;
        $this->date = $date;
        $this->complete_until = $complete_until;
    }
}
?>