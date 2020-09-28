<?php

$servername = "localhost";
$dbname = "test";
$user = "root";
$password = "0000";
$id = $_GET['id'];
$parentID = $_GET['parentID'];
try {
    $connect = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);
    $sql = $connect->prepare("DELETE FROM comment WHERE id = :num");
    $sql->bindParam(":num",$id,PDO::PARAM_INT);
    $sql->execute();
    echo "<script>document.location.href='content.php?id=" .$parentID. "';</script>";
} catch (PDOException $ex) {
    echo "<script>document.location.href='content.php?id=" .$parentID. "';</script>";
}
$connect = null;
