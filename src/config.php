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

mysqli_query($mysqli, "create database if not exists tododb");
mysqli_query($mysqli, "create table if not exists tododb.Category (
    id int(11) not null auto_increment, title varchar(255) default null, 
    date datetime not null default current_timestamp,
    complete_until datetime default null,
    unique(title),
    primary key (id)
)");
mysqli_query($mysqli, "create table if not exists tododb.Todo (
    id int(11) not null auto_increment, title varchar(255) default null, description varchar(255), 
    completed tinyint(1) not null default 0, date datetime not null default current_timestamp,
    cid int(11) not null,
    constraint fk_category foreign key (cid) references Category(id),
    constraint unique_title_category unique(title,cid),
    primary key (id)
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