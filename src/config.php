<?php
// Database configuration
//* Host name is db because we are using docker-compose and we have mapped the database host to 'db'
$databaseHost = 'db';
$databaseUsername = 'root';
$databasePassword = 'root';
$databaseName = 'tododb';

// Connect to the database
$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 
global $mysqli;

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