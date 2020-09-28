<!DOCTYPE html>
<html lang="kor">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>Train site</title>

</head>
<body>
<div class="container-fluid p-0 p-sm-4">
    <nav class="navbar sticky-top navbar-dark bg-dark mb-2 navbar-expand-sm">
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
    <div class="jumbotron jumbotron-fluid mx-2 mx-sm-0">
        <div class="container">
            <h1 class="display-4">This is train site!</h1>
            <p class="lead">Welcome to train site. site type : community</p>
        </div>
    </div>
    <button class="btn btn-dark float-right mr-2" onclick="location.href='list.php'">글목록</button>
    <h1 class="mx-2 mx-sm-0">최근 글 </h1>

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
                $password = "1234";
                $connect = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);
                try {
                    $sql = $connect->prepare("SELECT id,name,time,title FROM train ORDER BY id DESC LIMIT 5");
                    $sql->execute();
                    $result = $sql->fetchALL();

                    foreach ($result as $row) {
                        $id = $row['id'];
                        $name = $row['name'];
                        $date = $row["time"];
                        $title = $row["title"];
                        $strTok = explode(' ', $date);

                        if ($strTok[0] == date("Y-m-d", time())) {

                            $date3 = substr($strTok[1], 0, 5);
                        } else {
                            $date2 = substr($strTok[0], 5);
                            $date3 = str_replace("-", ".", $date2);
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

    <h1 class="mx-2 mx-sm-0">최근 댓글 </h1>
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
                $password = "1234";
                $connect = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);
                try {
                    $sql = $connect->prepare("SELECT parent_id, id,name,time,message FROM comment ORDER BY id DESC LIMIT 5");
                    $sql->execute();
                    $result = $sql->fetchALL();

                    foreach ($result as $row) {
                        $id = $row['parent_id'];
                        $name = $row['name'];
                        $date = $row["time"];
                        $title = $row["message"];
                        $strTok = explode(' ', $date);

                        if ($strTok[0] == date("Y-m-d", time())) {

                            $date3 = substr($strTok[1], 0, 5);
                        } else {
                            $date2 = substr($strTok[0], 5);
                            $date3 = str_replace("-", ".", $date2);
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