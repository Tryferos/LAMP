<?php
// Database configuration
//* Host name is db because we are using docker-compose and we have mapped the database host to 'db'
$databaseHost = 'db';
$databaseUsername = 'root';
$databasePassword = 'big_shifu_123';
$databaseName = 'tododb';

// Connect to the database
$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 
global $mysqli;

mysqli_query($mysqli, "CREATE DATABASE IF NOT EXISTS tododb");
mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS tododb.Category (
    id int(11) NOT NULL AUTO_INCREMENT, 
    title varchar(255) DEFAULT NULL, 
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    complete_until DATETIME DEFAULT NULL,
    UNIQUE (title),
    PRIMARY KEY (id),
    CHECK ((title IS NULL) OR (CHAR_LENGTH(title) >= 4))
)");
mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS tododb.Todo (
    id int(11) NOT NULL AUTO_INCREMENT, 
    title varchar(255) DEFAULT NULL, 
    description varchar(255), 
    completed tinyint(1) NOT NULL DEFAULT 0, 
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    cid int(11) NOT NULL,
    CONSTRAINT fk_category FOREIGN KEY (cid) REFERENCES Category(id) ON DELETE CASCADE,
    CONSTRAINT unique_title_category UNIQUE(title,cid),
    PRIMARY KEY (id),
    CHECK ((title IS NULL) OR (CHAR_LENGTH(title) >= 4))
)");

function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
    if ($with_script_tags)
    {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
?>