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

    if (isset($_POST['Add'])) {

        if ($_POST['type'] == 'product') {
            $type = 1;
        }
        else {
            $type = 0;
        }

        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $price = $_POST['price'];
        $deposit = $_POST['deposit'];
        $expiry = $_POST['exp'];
        
        $sql = "INSERT INTO prods (pType, title, descript, price, deposit, expiry, username)" . " VALUES ($type, '$title', '$desc', $price, $deposit, '$expiry', '$username')";

        if ( $mysqli->query($sql) ){
            header("location: home.php");
        }
        else {
            $_SESSION['message'] = "Product could not be registered.";
            header("location: error.php");
        }
    }

?>

<html>

<head>
    <title>Add Service</title>
    <!-- Add to homescreen for Chrome on Android -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="icon" sizes="192x192" href="images/android-desktop.png">

  <!-- Add to homescreen for Safari on iOS -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="The Dreamweavers Charitable Trust">
  <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

  <!-- Tile icon for Win8 (144x144 + tile color) -->
  <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
  <meta name="msapplication-TileColor" content="#3372DF">

  <link rel="shortcut icon" href="images/favicon.png">

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.teal-indigo.min.css" />
  <link rel="stylesheet" href="styles.css">

    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }
        label, input {
            display: block;
        }
        textarea {
            font-family: 'lato', helvetica;
            font-size: 1.3em;
            line-height: 1.5;
        }
        input[type=text] {
          font-family: 'lato', helvetica;
          font-size: 1.3em;
          line-height: 1.5;
        }
        .add {
          display: block;
          margin: 2% 0;
        }
        form {
          margin: 2%;
        }
    </style>
</head>

<body>

    <form method = "post" action = "addProdOrServ.php">

        <input type="radio" id="product" name="type" value="product">
        <label for="product">Product</label><br>
        <input type="radio" id="service" name="type" value="service">
        <label for="service">Service</label><br>

        <label for = "title">Title</label>
        <input type = "text" required name = "title" class = "title"><br>

        <label for = "desc">Description</label>
        <textarea type = "text" name = "desc" rows = "5" cols = "100" id = "desc" required></textarea>

    <!--<label for="category">Choose a category:</label>
        <select id="category" name="category">
            <option value="volvo">Volvo</option>
            <option value="saab">Saab</option>
            <option value="fiat">Fiat</option>
            <option value="audi">Audi</option>
        </select>-->
        
        <label for = "price">Price (Rs.)</label>
        <input type = "number" name = "price" required>

        <label for = "deposit">Deposit (Rs.)</label>
        <input type = "number" name = "deposit" required>

        <label for="exp">Expires on:</label>
        <input type="date" id="exp" name="exp" required>

        <input type = "submit" value = "Add" class = "add mdl-button mdl-js-button mdl-button--raised mdl-color--primary" name = "Add">
        <p class = "errMsg"></p>

    </form>

    <p><a href = "home.php">Home</a></p>
    <p><a href = "logout.php">Log out</a></p>

    <script>

        //Check to see that service/product name doesn't already exist

        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;

        document.getElementById('exp').min = today;
        document.getElementById('exp').value = today;
        
        //document.querySelector('.fileName').addEventListener('input', check);

        function check() {

            const params = "fileName=" + this.value;

            var xhttp = new XMLHttpRequest();
            var url = "checkFileName.php";
            xhttp.open("POST", url, true);

            xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if (xhttp.responseText == "0") {
                        document.querySelector('.errMsg').innerHTML = "File name already exists";
                        document.querySelector('.add').style.display = "none";
                    }
                    else if (xhttp.responseText == "1"){
                        document.querySelector('.errMsg').innerHTML = "";
                        document.querySelector('.add').style.display = "block";
                    }
                }
            };
            xhttp.send(params);
        }
    </script>

</body>

</html>
