<?php
$servername = "localhost";
$dbname = "test";
$user = "root";
$password = "0000";

$num = $_GET['id'];
try {
    if (!$num) {
        header("Location: 404error.html");
    }
    $connect = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);
    $test = $connect->prepare("SELECT id FROM train WHERE id = :num");
    $test->bindParam(':num', $num);
    $test->execute();
    $testResult = $test->fetch();
    if (!$testResult) {
        header("Location: 404error.html");
    }

    $connect = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);
    $sql = $connect->prepare("SELECT title,message FROM train WHERE id = $num");
    $sql->execute();
    $result = $sql->fetch();
    $msg = $result["message"];
    $title = $result["title"];
} catch (PDOException $ex) {
    echo "페이지 불러오기 실패! :" . $ex->getMessage() . "<br>";
    $title = null;
    $msg = null;
}
?>
<!DOCTYPE html>
<html lang="kor">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>글 수정 - Train site</title>

</head>
<body>
<div class="container-fluid p-0 p-sm-4">
    <nav class="navbar navbar-dark bg-dark mb-2 navbar-expand-sm">
        <a class="navbar-brand" href="index.php">Train</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarDrop">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarDrop">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item mr-2">
                    <a class="nav-link" href="list.php">List</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-sm-0">
                <input class="form-control col-8 mr-2" type="search" placeholder="Search" aria-label="search">
                <button class="btn btn-success" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <form action="edit.php" method="post" role="form">
        <input type="hidden" name="id" value="<?= $num ?>">
        <div class="form-group row">
            <label class=" col-2 col-md-1 col-form-label text-center" for="title">제목</label>
            <div class="col-10 col-md-11">
                <input class="form-control" type="text" name="title" id="title" value="<?= $title ?>">
            </div>
        </div>
        <textarea class="form-control" rows="15" id="message" name="message"
                  aria-label="textarea"><?= $msg ?></textarea>

        <button type="button" class="btn btn-outline-danger btn-lg float-left my-2" onclick="location.href='list.php'">취소</button>
        <button type="submit" class="btn btn-outline-primary btn-lg my-2 float-right">등록</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous"></script>
</body>
</html>