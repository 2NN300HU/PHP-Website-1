<?php
$servername = "localhost";
$dbname = "test";
$user = "root";
$password = "0000";
$name = $_POST["name"];
$pass = $_POST["password"];
$msg = $_POST["message"];
$title = $_POST["title"];
try {
    $connect = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);
    $sql = $connect->prepare("INSERT INTO train(name,password,message,title)VALUES(:name,:pass, :msg,:title);");
    $sql->bindParam(':name', $name, PDO::PARAM_STR);
    $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
    $sql->bindParam(':msg', $msg, PDO::PARAM_STR);
    $sql->bindParam(':title', $title, PDO::PARAM_STR);
    $sql->execute();
    $result = $sql->fetch();
    $id=$connect->lastInsertId();
    echo "<script>document.location.href='content.php?id=".$id."';</script>";
} catch (PDOException $ex) {
    echo "<script>document.location.href='list.php';</script>";
}
$connect = null;
