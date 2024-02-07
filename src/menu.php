<?php
echo "<ul class='mobile-dropdown-menu'>";
echo "<div class='filter-header'>";
echo "<p>Filters</p>";
echo "<img src='assets/filter.svg' alt='filter'/>";
echo "</div>";
$filters = ["All", "Pending", "Completed", "Date Range"];
$images = ["inbox", "pending", "completed", "calendar"];
for ($i = 0; $i < count($filters); $i++) {
    $filter = $filters[$i];
    $image = $images[$i];
    $checked = $i == 0 ? "checked" : "";
    echo "<li onclick='handleFilterTitleClick(event)' class='filter-item' data-filter='$filter'>";
    echo "<img src='assets/$image.svg' alt='$image'/>";
    echo "<p>$filter</p>";
    echo "<input onchange='handleFilterChange(event)' data-filter='$filter' type='checkbox' $checked data-filter='$i'/>";
    echo "</li>";
}
echo "</ul>";
