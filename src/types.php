<?php
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
        $result = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM tododb.Todo WHERE cid = $cid AND completed = 1 ORDER BY date DESC");

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
        $result = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM tododb.Todo WHERE cid = $cid AND completed = 0 ORDER BY date DESC");

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
                  AND date BETWEEN '$startDate' AND '$endDate' 
                  ORDER BY date DESC";

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
        $result = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM tododb.Todo WHERE cid = $cid ORDER BY date DESC");
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

        //Check if a record with NULL title exists
        $query = "SELECT * FROM tododb.Todo WHERE title IS NULL";
        $result = mysqli_query($mysqli, $query);
        if (mysqli_num_rows($result) > 0) return;

        //Insert a new empty todo
        $insertResult = mysqli_query($mysqli, "INSERT INTO tododb.Todo (title, description, completed, cid) VALUES (NULL, '', 0, $cid)");

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
        $updatedCompleted = ($completed == true ? 1 : 0);

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
    public $todoCount;
    public $completedCount;

    public function __construct($id, $title, $date, $complete_until, $todos = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->date = $date;
        $this->complete_until = $complete_until;
        $this->todos = $todos;
    }

    public static function fetchAll($exceptId = -1)
    {
        $result = mysqli_query($GLOBALS['mysqli'], "SELECT c.id, c.title, c.date, c.complete_until, count(t.id) as todoCount, 
        count(case t.completed when '1' then 1 else null end) as completedCount 
        from Category c left join Todo t on c.id = t.cid where c.id != $exceptId group by c.id,c.title,c.date,c.complete_until order by completedCount desc, todoCount desc,c.date desc");
        $categories = [];
        while ($row = $result->fetch_object()) {
            $cat = new Category(
                $row->id,
                $row->title,
                $row->date,
                $row->complete_until,
                Todo::fetchAll($row->id)
            );
            $cat->todoCount = $row->todoCount;
            $cat->completedCount = $row->completedCount;
            $categories[] = $cat;
        }
        return $categories;
    }

    public static function fetchId($id)
    {
        $result = mysqli_query($GLOBALS['mysqli'], "SELECT * FROM tododb.Category WHERE id = $id");
        $result = $result->fetch_object();
        if ($result == null) {
            return null;
        }
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
        $cat = Category::fetchId($id);
        if ($cat == null) return null;
        return $cat->title;
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

    public static function calculateCompletionStatistics($category)
    {
        $completed = 0;
        $total = 0;
        foreach ($category->todos as $todo) {
            if ($todo->completed) {
                $completed++;
            }
            $total++;
        }
        $percentage = ($total > 0) ? (($completed / $total) * 100) : 0;
        return array($completed, $total, number_format($percentage, 1));
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

        //Check if a record with NULL title exists
        $query = "SELECT * FROM tododb.Category WHERE title IS NULL";
        $result = mysqli_query($mysqli, $query);
        if (mysqli_num_rows($result) > 0) return;

        // Insert a new empty category into the database
        mysqli_query($mysqli, "INSERT INTO tododb.Category (title) VALUES (NULL)");
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
        $result = mysqli_query($mysqli, "SELECT * FROM tododb.Category WHERE id = $id ");
        $category = $result->fetch_object();
        if ($category->title != $newTitle) {
            $titleExistsRes = mysqli_query($mysqli, "SELECT count(*) as total FROM tododb.Category c WHERE c.title = '$newTitle' ");
            $titleExists = $titleExistsRes->fetch_assoc()['total'];
            if ($titleExists > 0) {
                throw new Exception("Category with title $newTitle already exists");
            }
        }

        // Check if new title or new Complete_until date is empty, if not, update
        $updatedTitle = (!empty($newTitle)) ? $newTitle : $category->title;
        $updatedNewCompleteUntilDate = (!empty($newCompleteUntil)) ? $newCompleteUntil : $category->complete_until;

        if ($updatedNewCompleteUntilDate == null) {
            mysqli_query($mysqli, "UPDATE tododb.Category
            SET title = '$updatedTitle' 
             WHERE id = $id");
        } else {
            mysqli_query($mysqli, "UPDATE tododb.Category
            SET title = '$updatedTitle', complete_until = '$updatedNewCompleteUntilDate' 
             WHERE id = $id");
        }



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
