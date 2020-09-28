<?php
$servername = "localhost";
$dbname = "test";
$user = "root";
$password = "0000";
$id = $_POST["id"];
$name = $_POST["name"];
$pass = $_POST["password"];
$msg = $_POST["message"];
try {
    $connect = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);
    $sql = $connect->prepare("INSERT INTO comment(name,password,message,parent_id)VALUES(:name,:pass, :msg,:id)");
    $sql->bindParam(':name', $name, PDO::PARAM_STR);
    $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
    $sql->bindParam(':msg', $msg, PDO::PARAM_STR);
    $sql->bindParam(':id', $id, PDO::PARAM_INT);
    $sql->execute();
    echo "<script>document.location.href='content.php?id=" .$id. "';</script>";
} catch (PDOException $ex) {
    echo "<script>document.location.href='content.php?id=" .$id. "';</script>";
}
$connect = null;