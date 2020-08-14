<?php

    require 'db.php';
    session_start();

    //Checking if logged in
    if ( isset($_SESSION['logged_in']) ) {
        if($_SESSION['logged_in'] != true){
            $_SESSION['message'] = "Sorry. You're not logged in.";
            header("location: error.php");
        }
    }
    else {
        $_SESSION['message'] = "Sorry. You're not logged in.";
        header("location: error.php");
    }

    $username = $_SESSION['username'];

    if (isset($_POST['tourist'])) {
        $tourist = $_POST['tourist'];

        $setTourist = $mysqli->query("UPDATE users SET tourist = $tourist WHERE username = '$username'");
    }
    else {
        $setTourist = $mysqli->query("SELECT tourist FROM users WHERE username = '$username'");
        $ans = mysqli_fetch_array($setTourist, MYSQLI_NUM)[0];
        
        echo $ans;
    }

?>