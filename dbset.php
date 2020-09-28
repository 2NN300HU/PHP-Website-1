<?php
$servername = "localhost";
$dbname = "test";
$user = "root";
$password = "0000";


// Create connection
$conn = new mysqli($servername, $user, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE test";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();

try {
    $connect = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);
    $sql = $connect->prepare("create table train
(
    id       int auto_increment
        primary key,
    name     varchar(20)                           not null,
    password varchar(20)                           not null,
    message  mediumtext                            not null,
    time     timestamp default current_timestamp() not null,
    title    varchar(40)                           not null
);
");
    $sql->execute();
    $sql = $connect->prepare("create table comment
(
    id        int auto_increment
        primary key,
    parent_id int                                   not null,
    message   tinytext                              not null,
    time      timestamp default current_timestamp() not null,
    name      varchar(20)                           not null,
    password  varchar(20)                           not null
);");
    $sql->execute();


    echo "<br>Table created successfully!";

} catch (PDOException $ex) {
    echo "<br>Error creating database! : " . $ex->getMessage() . "<br>";
}
$connect = null;
