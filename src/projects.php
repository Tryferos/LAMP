<?php
require_once("config.php");
require_once("types.php");
$projects = Category::fetchAll();
foreach ($projects as $project) {
    $percentage = Category::calculateCompletionStatistics($project)[2];
    $date = $project->complete_until != null ? date("l d F, H:i", strtotime($project->complete_until)) : 'Indefinitely';
    $completion = $project->todoCount > 0 ? "$project->completedCount/$project->todoCount ($percentage%) of tasks are completed." : "No tasks yet.";
    echo "<li><p id='header'>$project->title</p>";
    echo "<p class='deadline'>Deadline: <span>$date</span></p>";
    $todos = Todo::fetchAll($project->id);
    echo "<ul class='tasks'>";
    foreach (array_slice($todos, 1, 2) as $todo) {
        $completed = $todo->completed ? "completed" : "";
        echo "<li data-completed='$completed' onclick='window.location.href=`/project.php?id=$project->id`' class='task' id='" . $todo->id . "'>";
        echo "<div data-completed='$completed' class='checkbox-input'></div>";
        echo "<p>$todo->title</p>";
        echo "<img data-toggled='false' src='assets/arrow.svg' id='arrow-$todo->id' alt='open' class='arrow-todo' />";
        echo "</li>";
    }
    echo "</ul>";
    echo "<p class='statistics'>";
    echo "$completion";
    echo "</p>";
    echo "</li>";
}
