<!DOCTYPE html>
<html lang="kor">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>글목록 - Train site</title>

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
    <div class="row">
        <div class="col">
            <table class="table table-hover">
                <thead class="thead-dark">
                <tr>
                   <th scope="col" style="width: 20%">#</th>
                    <th scope="col" style="width: 50%">제목</th>
                    <th scope="col" style="width: 20%">글쓴이</th>
                    <th scope="col" style="width: 20%">시간</th>
                </tr>
                </thead>


                <?php
                $servername = "localhost";
                $dbname = "test";
                $user = "root";
                $password = "0000";
                $pageSize = 25;


                if (array_key_exists('amount', $_GET)) {
                    $amount = $pageSize * $_GET["amount"];
                } else {
                    $amount = 0;
                }
                if (array_key_exists("page", $_GET)) {
                    $page = $_GET["page"];
                } else {
                    $page = 1;
                }

                $connect = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);
                if (array_key_exists("key", $_GET) && $amount >= 0) {
                    $sql = $connect->prepare("SELECT id,name,time,title FROM train WHERE id < :key ORDER BY id DESC LIMIT :amount, :pageSize");
                    $sql->bindParam(':key', $key, PDO::PARAM_INT);
                    $key = $_GET['key'];
                } elseif (array_key_exists("key", $_GET) && $amount < 0) {
                    $sql = $connect->prepare("WITH this_set AS (SELECT id,name,time,title FROM train WHERE id > :key ORDER BY id LIMIT :amount, :pageSize) SELECT * FROM this_set order by id DESC");
                    $sql->bindParam(':key', $key, PDO::PARAM_INT);
                    $amount = -$amount - $pageSize;
                    $key = $_GET['key'];
                } else {
                    $sql = $connect->prepare("SELECT id,name,time,title FROM train ORDER BY id DESC LIMIT :amount, :pageSize");
                }

                try {
                    $sql->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
                    $sql->bindParam(':amount', $amount, PDO::PARAM_INT);
                    $sql->execute();
                    $result = $sql->fetchALL();
                    if (empty($result)) {
                        echo "</table><h3 class='mx-2 mx-sm-0'>글이 없습니다</h3><table>";
                        $id = 0;
                        $prevID = 0;
                    }else{

                        $prevID = $result[0]['id'];
                    }
                    foreach ($result as $row) {
                        $id = $row['id'];
                        $name = $row['name'];
                        $date = $row["time"];
                        $title = $row["title"];
                        $strTok =explode(' ' , $date);

                        if ($strTok[0] == date("Y-m-d", time())){

                            $date3 = substr($strTok[1],0,5);
                        }else{
                            $date2 = substr($strTok[0],5);
                            $date3 = str_replace("-",".",$date2);
                        }
                        echo '<tbody><tr><td>' . $id . '</td>
                           <td class="text-truncate" style="max-width: calc(50*1vw)"><a  href="content.php?id=' . $id . '">' . $title . '</a>
                            </td> <td class="text-truncate" style="max-width: calc(20*1vw)">' . $name . '</td><td>' . $date3 . '</td></tr></tbody>';
                    }
                } catch (PDOException $ex) {

                    echo "레코드 불러오기 실패! :" . $ex->getMessage() . "<br>";
                    $result = null;
                }

                ?>
            </table>
        </div>
    </div>
    <button class="btn btn-primary float-right mr-2" type="button" onclick="location.href='writer.php'">글쓰기</button>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php

            $div = intval(($page - 1) / 5);
            $rest = ($page - 1) % 5 + 1;

            $nextSize = $pageSize * (6 - $rest);
            $sql = $connect->prepare("SELECT id,name,time,title FROM train WHERE id < :key ORDER BY id DESC LIMIT :nextSize");
            $sql->bindParam(':key', $id, PDO::PARAM_INT);
            $sql->bindParam(':nextSize', $nextSize, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetchALL();
            if (empty($result)) {
                $nextPageCount = 0;
            } else {

                $nextPageCount = intval((count($result) - 1) / $pageSize) + 1;
            }
            if ($div > 0) {
                $a = $div * 5;
                $b = $prevID;
                $c = -$rest;
                echo '<li class="page-item"><a class="page-link" href="list.php?page=' . $a . '&key=' . $b . '&amount=' . $c . '">Previous</a></li>';
            }
            for ($i = 1; $i < $nextPageCount + $rest ; $i++) {
                $a = $div * 5 + $i;

                if ($i == $rest) {
                    echo '<li class="page-item active"><span class="page-link">' . $a . '<span class="sr-only">(current)</span></span></li>';
                } elseif ($i > $rest) {
                    $b = $id;
                    $c = $i - $rest - 1;
                    echo '<li class="page-item"><a class="page-link" href="list.php?page=' . $a . '&key=' . $b . '&amount=' . $c . '">' . $a . '</a></li>';
                } else {
                    $b = $prevID;
                    $c = $i - $rest;
                    echo '<li class="page-item"><a class="page-link" href="list.php?page=' . $a . '&key=' . $b . '&amount=' . $c . '">' . $a . '</a></li>';
                }
            }
            if ($nextPageCount + $rest == 6) {

                echo '<li class="page-item">';
                $a = $div * 5 + 6;
                $b = $id;
                $c = -$rest + 5;
                echo '<a class="page-link" href="list.php?page=' . $a . '&key=' . $b . '&amount=' . $c . '">Next</a>';
            echo '</li>';
        }else{
                $b = $id;
                $c = $nextPageCount - 1;
                $a = $div * 5 +$nextPageCount + $rest;
                echo '<li class="page-item"><a class="page-link" href="list.php?page=' . $a . '&key=' . $b . '&amount=' . $c . '">' . $a . '</a></li>';
            }
            ?>

        </ul>
    </nav>
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