<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Welcome to iDiscuss - Coding Forums</title>
    <style>
    #ques {
        min-height: 433px;
    }
    </style>
</head>

<body>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php include 'partials/_header.php'; ?>

    <?php
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `categories` WHERE category_id=$id;";
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)){
        $catname = $row['category_name'];
        $catdesc = $row['category_description'];
    };
    ?>
    <?php 
    $showAlert = false;
    $method = $_SERVER['REQUEST_METHOD'];
    // echo $method;
     if($method == 'POST'){ 
        $th_title = $_POST['title'];
        $th_desc = $_POST['desc'];

        $th_title = str_replace("<","&lt",$th_title);
        $th_title = str_replace(">","&gt",$th_title);
        
        $th_desc = str_replace("<","&lt",$th_desc);
        $th_desc = str_replace(">","&gt",$th_desc);
        $sno = $_POST['sno'];
        $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) 
        VALUES ('$th_title', '$th_desc', '$id', '$sno', current_timestamp());";
        $result = mysqli_query($conn,$sql);
        $showAlert = true;
        if($showAlert){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your Thread has been addeded successfully and after that someone give you answer .
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>          </div>';
        }

    }
    

    ?>

    <!-- Category container starts here -->
    <div class="container my-4">

        <div class="jumbotron">
            <h1 class="display-4">Welcome To <?php echo $catname ?> Forum</h1>
            <p class="lead"><?php echo $catdesc ?></p>
            <hr class="my-4">
            <p>This is a peer to peer forum. No Spam / Advertising / Self-promote in the forums is not allowed. Do not
                post copyright-infringing material. Do not post ???offensive??? posts, links or images. Do not cross post
                questions. Remain respectful of other members at all times.</p>
        </div>

    </div>


<?php
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
    echo '<div class="container">
    <h1 class="py-2">Start a Discussion</h1>
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Problem Title</label>
            <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            <small id="emailHelp" class="form-text text-muted">Your Title should short & crisp
            </small>
        </div>
        <input type="hidden" name="sno" value="'. $_SESSION["sno"] .'">
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Ellaborate your Concern</label>
            <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>';
}
else{
    echo '<div class="container">
    <h1 class="py-2">Start a Discussion</h1>
    <p class="lead">You are not logged in. Please login to be able to start a Discussion</p>
    </div>';
}
?>
    
    <div class="container mb-5" id="ques">
        <h1 class="py-2">Browse Question</h1>
        <?php
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `threads` WHERE thread_cat_id = $id;";
    $result = mysqli_query($conn,$sql);
    $noResult = true;
    while($row = mysqli_fetch_assoc($result)){
        $noResult = false;
        $id = $row['thread_id'];    
        $title = $row['thread_title'];
        $desc = $row['thread_desc'];
        $thread_time = $row['timestamp'];
        $thread_user_id = $row['thread_user_id'];
        $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);

        echo '<div class="media my-3">
        <img src="img/user.png" width="54px" class="mr-3" alt="...">
        <div class="media-body">'.
         '<h5 class="mt-0"> <a class="text-dark" href="thread.php?threadid=' . $id. '">'. $title . ' </a></h5>
            '. $desc . ' </div>'.'<div class="font-weight-bold my-0"> Asked by:'. $row2['user_email'] .'
             at '. $thread_time. '</div>'.
    '</div>';
    };
    // echo var_dump($noResult);
    if($noResult){
        echo '<div class="jumbotron jumbotron-fluid">
                <div class="container">
                    <p class="display-4">No Threads Found</p>
                    <p class="lead"> Be the first person to ask a question</p>
                </div>
             </div> ';
    }
    ?>
    </div>

    <?php include 'partials/_footer.php'; ?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
</body>

</html>