<?php
require_once("config.php");
require_once("types.php");
$projects = Category::fetchAll();
foreach ($projects as $project) {
    $percentage = Category::calculateCompletionStatistics($project)[2];
    $date = $project->complete_until != null ? date("l F, H:i", strtotime($project->complete_until)) : 'Indefinitely';
    $completion = $project->todoCount > 0 ? "$project->completedCount/$project->todoCount ($percentage%) of tasks are completed." : "No tasks yet.";
    echo "<li><p id='header'>$project->title</p>";
    echo "<p class='deadline'>Deadline: <span>$date</span></p>";
    echo "<p class='statistics'>";
    echo "$completion";
    echo "</p>";
    echo "</li>";
}
