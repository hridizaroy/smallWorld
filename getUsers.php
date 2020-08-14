<?php

//Done: Add new field in table for local/tourist
//Done: Add toggle switch for the same
//Done: append to array opposite of user
//Done: e.g. if user is local, show tourists, else show locals

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

    $lat = $_POST['lat'];
    $long = $_POST['long'];

    $tourist = $_POST['tourist'];

    if ($tourist == 0) {
        $opp = 1;
    }
    else {
        $opp = 0;
    }



    $setLocation = $mysqli->query("UPDATE users SET latitude = $lat, longitude = $long WHERE username = '$username'");

    $getUsers = $mysqli->query("SELECT username FROM users WHERE (latitude - $lat)*(latitude - $lat)*110947.2 + (longitude - $long)*(longitude - $long)*87843.36 <= 25 AND tourist = $opp AND username != '$username'");

    $users = array();

    while ($row = mysqli_fetch_array($getUsers, MYSQLI_NUM)) {
        $text = $row[0];
        array_push($users, "'$text'");       
    }

    $arr = '('.implode($users, ",").')';

    $today = date("Y-m-d");

    if ($opp == 1) {
        $prods = $mysqli->query("SELECT * FROM reqs WHERE username in $arr AND expiry >= '$today'");

        if ($prods) {
            while ($row = mysqli_fetch_array($prods, MYSQLI_NUM)) {
                if ($row[1] == 0) {
                    $type = "Product";
                }
                else {
                    $type = "Service";
                }
                echo "<div class = 'prod'>";
                echo "<p>Type: ".$type."</p>";
                echo "<p>Title: ".$row[2]."</p>";
                echo "<p>Description: ".$row[3]."</p>";
                echo "<p>Max Price: Rs. ".$row[4]."</p>";
                echo "<p>Max Deposit: Rs. ".$row[5]."</p>";
                echo "<p>Required By: ".$row[6]."</p>";
                echo "<p class = 'user'>".$row[7]."</p>";
                echo "</div><br>";
            }
        }
    }
    else {
        $prods = $mysqli->query("SELECT * FROM prods WHERE username in $arr AND expiry >= '$today'");

        while ($row = mysqli_fetch_array($prods, MYSQLI_NUM)) {
            if ($row[1] == 0) {
                $type = "Product";
            }
            else {
                $type = "Service";
            }
            echo "<div class = 'prod'>";
            echo "<p>Type: ".$type."</p>";
            echo "<p>Title: ".$row[2]."</p>";
            echo "<p>Description: ".$row[3]."</p>";
            echo "<p>Price: Rs. ".$row[4]."</p>";
            echo "<p>Deposit: Rs. ".$row[5]."</p>";
            echo "<p>Expires on: ".$row[6]."</p>";
            echo "<p class = 'user'>".$row[7]."</p>";
            echo "</div><br>";
        }
    }

    //echo $arr;
    //echo implode($users, ";");

?>