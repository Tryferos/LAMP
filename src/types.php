<?php

// todo - categories
// todo -> task, category -> project
// fetch todos completed by category id     done
// fetch todos pending by category id       done
// fetch todos within date range by category id (date1, date2) done
// fetch description by id      done
// delete todo by id            done
// delete category by id        done
// update todo (title, description) by id - 2 functions done 
// create empty todo    done 
// create empty category done
// update category (title, complete until) by id - 2 functions done
// check expired category by id done
// category tasks statistics stored in variables (completed, total, percentage)
// 
class Todo
{
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

    public static function fetchId($id)
    {
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

    public static function fetchDescription($id)
    {

        $result = mysqli_query($GLOBALS['mysqli'], "SELECT description FROM tododb.Todo WHERE id = $id");
        $todo = $result->fetch_object();

        return $todo->description;
    }


    public static function fetchCompleted($cid)
    {
        $result = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM tododb.Todo WHERE cid = $cid AND completed = 1");

        $todos = [];
        while ($row = $result->fetch_object()) {
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


    public static function fetchPending($cid)
    {
        $result = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM tododb.Todo WHERE cid = $cid AND completed = 0");

        $todos = [];
        while ($row = $result->fetch_object()) {
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

    public static function fetchTodosByDateRange($cid, $startDate, $endDate)
    {
        $mysqli = $GLOBALS['mysqli'];

        $query = "SELECT * FROM tododb.Todo 
                  WHERE cid = $cid 
                  AND date BETWEEN '$startDate' AND '$endDate'";

        $result = mysqli_query($mysqli, $query);

        $todos = [];
        while ($row = $result->fetch_object()) {
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



    public static function toggleTodo($id)
    {

        $result = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM tododb.Todo WHERE id = $id");
        $todo = $result->fetch_object();

        // Toggle the completed status
        $newCompletedStatus = ($todo->completed == 1) ? 0 : 1;

        // Update the database with the new completed status
        mysqli_query($GLOBALS['mysqli'], "UPDATE tododb.Todo SET completed = $newCompletedStatus WHERE id = $id");

        // Fetch and return the updated todo
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

    public static function fetchAll($cid)
    {
        $result = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM tododb.Todo WHERE cid = $cid");
        $todos = [];
        while ($row = $result->fetch_object()) {
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

    public static function fetchName($id)
    {
        return Todo::fetchId($id)->title;
    }


    public static function createEmptyTodo($cid)
    {
        $mysqli = $GLOBALS['mysqli'];

        //Insert a new empty todo
        $insertResult = mysqli_query($mysqli, "INSERT INTO tododb.Todo (title, description, completed, cid) VALUES ('', '', 0, $cid)");

        if (!$insertResult) {
            // Handle the error if the insert query fails
            die("Error creating empty todo: " . mysqli_error($mysqli));
        }

        // Get the ID of the newly created todo
        $newTodoId = mysqli_insert_id($mysqli);

        // Fetch and return the newly created todo
        $result = mysqli_query($mysqli, "SELECT * FROM tododb.Todo WHERE id = $newTodoId");
        if (!$result) {
            // Handle the error if the select query fails
            die("Error fetching newly created todo: " . mysqli_error($mysqli));
        }
        $todoData = $result->fetch_object();

        if (!$todoData) {
            // Handle the case where no todo is found
            die("Error: No todo found with ID $newTodoId");
        }

        return new Todo(
            $todoData->id,
            $todoData->title,
            $todoData->description,
            $todoData->completed,
            $todoData->date,
            $todoData->cid
        );
    }


    public static function deleteTodo($id)
    {
        // Delete the todo from the database
        mysqli_query($GLOBALS['mysqli'], "DELETE FROM tododb.Todo WHERE id = $id");

        if (mysqli_affected_rows($$GLOBALS['mysqli']) > 0) {
            // Deletion successful
            return true;
        } else {
            // Deletion failed or todo with given ID doesn't exist
            return false;
        }
    }

    public static function updateTodo($id, $newTitle, $newDescription, $completed)
    {
        $mysqli = $GLOBALS['mysqli'];

        // Fetch the current todo
        $result = mysqli_query($mysqli, "SELECT * FROM tododb.Todo WHERE id = $id");
        $todo = $result->fetch_object();

        // Check if new title or description is empty, if not, update
        $updatedTitle = (!empty($newTitle)) ? $newTitle : $todo->title;
        $updatedDescription = (!empty($newDescription)) ? $newDescription : $todo->description;
        $updatedCompleted = (!empty($completed)) ? $completed : $todo->completed;

        // Update the database with the new title and description
        mysqli_query($mysqli, "UPDATE tododb.Todo 
                               SET title = '$updatedTitle', description = '$updatedDescription', date = current_timestamp, completed = $updatedCompleted 
                               WHERE id = $id");

        // Fetch and return the updated todo
        $result = mysqli_query($mysqli, "SELECT * FROM tododb.Todo WHERE id = $id");
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

class Category
{
    public $id;
    public $title;
    public $date;
    public $complete_until;
    public $todos;

    public function __construct($id, $title, $date, $complete_until, $todos = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->date = $date;
        $this->complete_until = $complete_until;
        $this->todos = $todos;
    }

    public static function fetchId($id)
    {
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

    public static function fetchName($id)
    {
        return Category::fetchId($id)->title;
    }

    public static function fetchCompleteUntil($id)
    {
        return Category::fetchId($id)->complete_until;
    }


    public static function isCategoryExpired($categoryId)
    {
        $mysqli = $GLOBALS['mysqli'];

        // Fetch the category information
        $result = mysqli_query($mysqli, "SELECT * FROM tododb.Category WHERE id = $categoryId");
        $category = $result->fetch_object();

        // Check if the category has an expiration date
        if ($category->complete_until) {
            // Convert the complete_until date to a timestamp
            $expirationTimestamp = strtotime($category->complete_until);

            // Get the current timestamp
            $currentTimestamp = time();

            // Check if the category has expired
            return $currentTimestamp > $expirationTimestamp;
        }

        // If the category doesn't have an expiration date, it's not expired
        return false;
    }


    public static function getCompletionPercentage($categoryId)
    {
        $mysqli = $GLOBALS['mysqli'];

        // Count the total number of todos in the category
        $totalQuery = "SELECT COUNT(*) as total FROM tododb.Todo WHERE cid = $categoryId";
        $totalResult = mysqli_query($mysqli, $totalQuery);
        $total = $totalResult->fetch_assoc()['total'];

        // Count the number of completed todos in the category
        $completedQuery = "SELECT COUNT(*) as completed FROM tododb.Todo WHERE cid = $categoryId AND completed = 1";
        $completedResult = mysqli_query($mysqli, $completedQuery);
        $completed = $completedResult->fetch_assoc()['completed'];

        // Calculate the percentage
        $percentage = ($total > 0) ? (($completed / $total) * 100) : 0;

        return $percentage;
    }



    public static function createEmptyCategory()
    {
        $mysqli = $GLOBALS['mysqli'];

        // Insert a new empty category into the database
        mysqli_query($mysqli, "INSERT INTO tododb.Category (title) VALUES ('')");
        $categoryId = mysqli_insert_id($mysqli);

        // Fetch and return the newly created category
        $result = mysqli_query($mysqli, "SELECT * FROM tododb.Category WHERE id = $categoryId");
        $result = $result->fetch_object();

        return new Category(
            $result->id,
            $result->title,
            $result->date,
            $result->complete_until
        );
    }

    public static function updateCategory($id, $newTitle, $newCompleteUntil)
    {
        $mysqli = $GLOBALS['mysqli'];

        // Fetch the current category
        $result = mysqli_query($mysqli, "SELECT * FROM tododb.Category WHERE id = $id");
        $category = $result->fetch_object();

        // Check if new title or new Complete_until date is empty, if not, update
        $updatedTitle = (!empty($newTitle)) ? $newTitle : $category->title;
        $updatedNewCompleteUntilDate = (!empty($newCompleteUntil)) ? $newCompleteUntil : $category->complete_until;

        // Update the database with the new title and complete_until date
        mysqli_query($mysqli, "UPDATE tododb.Category
                               SET title = '$updatedTitle', complete_until = '$updatedNewCompleteUntilDate' 
                               WHERE id = $id");


        // Fetch and return the updated category
        $result = mysqli_query($mysqli, "SELECT * FROM tododb.Category WHERE id = $id");
        $result = $result->fetch_object();

        return new Category(
            $result->id,
            $result->title,
            $result->date,
            $result->complete_until
        );
    }

    public static function deleteCategory($id)
    {
        // Delete the category from the database
        mysqli_query($GLOBALS['mysqli'], "DELETE FROM tododb.Category WHERE id = $id");

        if (mysqli_affected_rows($$GLOBALS['mysqli']) > 0) {
            // Deletion successful
            return true;
        } else {
            // Deletion failed or category with given ID doesn't exist
            return false;
        }
    }
}
