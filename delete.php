<?php
$servername = "localhost";
$dbname = "test";
$user = "root";
$password = "0000";
$num = $_POST['id'];
$pass = $_POST['password'];
try {
    $connect = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);
    $sql = $connect->prepare("SELECT password FROM train WHERE id = :num");
    $sql->bindParam(':num', $num);
    $sql->execute();
    $result = $sql->fetch();
    if ($result['password'] == $pass){
        $sql = $connect->prepare("DELETE FROM train WHERE id = :num");
        $sql->bindParam(':num', $num);
        $sql->execute();
        $sql = $connect->prepare("DELETE FROM comment WHERE parent_id = :num");
        $sql->bindParam(":num",$num,PDO::PARAM_INT);
        $sql->execute();
        echo "<script>document.location.href='list.php';</script>";
    } else{
        echo '<script>alert("비밀번호가 잘못되었습니다");</script>';
        echo "<script>document.location.href='content.php?id=".$num."';</script>";
    }
} catch (PDOException $ex) {
    echo "<script>document.location.href='list.php';</script>";
}
$connect = null;
