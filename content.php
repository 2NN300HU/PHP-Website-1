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

    $sql = $connect->prepare("SELECT name,message,time,title FROM train WHERE id = :num");
    $sql->bindParam(':num', $num);
    $sql->execute();
    $result = $sql->fetch();
    $name = $result['name'];
    $msg = $result["message"];
    $date = $result["time"];
    $title = $result["title"];

} catch (PDOException $ex) {
    echo "페이지 불러오기 실패! :" . $ex->getMessage() . "<br>";

}
?>
<!DOCTYPE html>
<html lang="kor">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title><?=$title?> - Train site</title>

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

    <div class="px-2">
    <h3><?= $title ?><br>

        <small class="text-muted"><?= $name ?> <span style="color:lightgrey">|</span> <?= $date ?></small></h3>
    </div>
    <hr>
    <div class="my-4 px-2"><p style="white-space: pre-wrap"><?= $msg ?></p></div>
    <hr>
    <div class="row">
        <div class="col-3">

            <button class="btn btn-outline-secondary btn-block" type="button" onclick="location.href='list.php'">목록
            </button>
        </div>
        <div class="col-3">

            <button class="btn btn-outline-primary btn-block" type="button" onclick="location.href='writer.php'">글쓰기
            </button>
        </div>
        <div class="col-3">

            <button type="button" class="btn btn-outline-secondary btn-block"
                    onclick="location.href='editor.php?id=<?= $num ?>'">수정
            </button>
        </div>
        <div class="col-3">
            <button type="button" class="btn btn-outline-danger btn-block"
                    data-toggle="modal" data-target="#deleteModal">삭제
            </button>
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">비밀번호를 입력해 주세요</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="delete.php" method="post" role="form">
                            <input type="hidden" name="id" value="<?= $num ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Password" aria-label="password">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
                                <button type="submit" class="btn btn-danger">삭제</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <table class="table">
                <?php
                $commentSql = $connect->prepare("SELECT name,time,message,id FROM comment WHERE parent_id = :id ORDER BY id");
                $commentSql->bindParam(':id', $num, PDO::PARAM_INT);
                $commentSql->execute();
                $commentResult = $commentSql->fetchALL();
                $commentCount = count($commentResult);
                echo '<thead><tr><th colspan="6">댓글 <span style="color:red">[' . $commentCount . ']</span></th></tr></thead>';
                foreach ($commentResult as $row) {
                    $name = $row['name'];
                    $date = $row["time"];
                    $message = $row["message"];
                    $commentID = $row['id'];
                    $strTok = explode(' ', $date);

                    if ($strTok[0] == date("Y-m-d")) {

                        $date2 = $strTok[1];
                    } else {
                        $date2 = substr($strTok[0], 5);
                    }
                    echo '<tbody><tr><th style="width: 20%">' . $name . '</th><td class="text-break" style="width: 50%">' . $message .
                        '</td><td style="width: 10%">' . $date2 . '</td><td style="width: 5%">
                        <button type="button" style="border-color: white" class="btn btn-outline-danger" onclick="location.href=\'commentdelete.php?id=' . $commentID . '&parentID=' . $num . '\'"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg></button></td></tr></tbody>';
                }
                ?>
            </table>
        </div>
    </div>
    <div class="border p-2 mb-2">
        <form action="commentwrite.php" method="post" role="form">
            <input type="hidden" name="id" value="<?= $num ?>">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="userNameInput">
                        사용자
                    </label>
                    <input class="form-control" type="text" name="name" id="userNameInput" placeholder="Name">
                </div>
                <div class="form-group col-md-6">
                    <label for="passwordInput">
                        비밀번호
                    </label>
                    <input class="form-control" type="password" name="password" id="passwordInput"
                           placeholder="Password">
                </div>
            </div>
            <textarea class="form-control" rows="3" id="message" name="message" aria-label="textarea"></textarea>
            <div class="col text-right mt-2">
                <button type="submit" class="btn btn-primary btn-lg  ">등록</button>
            </div>
        </form>
    </div>
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
<?php
$connect = null;