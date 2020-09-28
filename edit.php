<?php
$servername = "localhost";
$dbname = "test";
$user = "root";
$password = "0000";
$msg = $_POST["message"];
$title = $_POST["title"];
$num = $_POST['id'];
try {
    $connect = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);
    $sql = $connect->prepare("UPDATE train SET title= :title, message= :msg WHERE id = $num");
    $sql->bindParam(':msg', $msg, PDO::PARAM_STR);
    $sql->bindParam(':title', $title, PDO::PARAM_STR);
    $sql->execute();
    echo "<script>alert(\"수정 완료\");</script>";
    echo "<script>document.location.href='content.php?id=".$num."';</script>";
} catch (PDOException $ex) {
    echo "<script>alert(\"수정 실패\");</script>";
    echo "<script>document.location.href='content.php?id=".$num."';</script>";
}
$connect = null;
