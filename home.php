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

    $API_KEY = "<YOUR_API_KEY>";

?>
<html>
    <head>
    </head>
    <body>

    <p></p>
    <p></p>

    <input type = 'checkbox' name = 'tourist' id = "tourist">
    <label for = "tourist">Tourist</label>

    <div class = "prods">
        
    </div>

    <button id = "req"><a href = "addRequest.php">Request Product or Service</a></button>
    <button id = "add"><a href = "addProdOrServ.php">Add Product or Service</a></button>


    <form action = "deal.php" method = "POST" id = "deal" hidden>
        <input type = "text" name = "to" id = "to" hidden>
    </form>


    <script
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo $API_KEY; ?>&callback=initMap"
        async defer></script>
    <script type="text/javascript">

        var lat;
        var long;

        function locate(){
            if ("geolocation" in navigator){
                navigator.geolocation.getCurrentPosition(function(position){ 
                    var currentLatitude = position.coords.latitude;
                    var currentLongitude = position.coords.longitude;

                    lat = currentLatitude;
                    long = currentLongitude;

                    var p = document.querySelectorAll("p");
                    p[0].innerHTML = "Latitude: " + currentLatitude;
                    p[1].innerHTML = "Longitude: " + currentLongitude;

                    setActive();
                });
            }
        }

        function getUsers() {

            var tourist;
            
            if (document.getElementById('tourist').checked == true) {
                tourist = 1;
            }
            else {
                tourist = 0;
            }

            const params = "lat=" + lat + "&long=" + long + "&tourist=" + tourist;

            var xhttp = new XMLHttpRequest();
            var url = "getUsers.php";
            xhttp.open("POST", url, true);

            xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if (xhttp.response != document.querySelector('.prods').innerHTML) {
                        document.querySelector('.prods').innerHTML = xhttp.response;
                        userListeners();
                    }
                }
            };
            xhttp.send(params);
        }

        function setTourist() {

            var xhttp = new XMLHttpRequest();
            var url = "tourist.php";
            xhttp.open("POST", url, true);

            xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if (xhttp.responseText == 0) {
                        var ans = false;
                    }
                    else {
                        var ans = true;
                    }
                    document.getElementById('tourist').checked = ans;

                    if (ans) {
                        document.getElementById('add').style.display = "none";
                        document.getElementById('req').style.display = "block";
                    }
                    else {
                        document.getElementById('add').style.display = "block";
                        document.getElementById('req').style.display = "none";
                    }

                    setInterval(locate, 1000);
                }
            };
            xhttp.send(); 
        }

        function sendTourist() {

            var tourist;
            
            if (document.getElementById('tourist').checked) {
                tourist = 1;
                document.getElementById('add').style.display = "none";
                document.getElementById('req').style.display = "block";
            }
            else {
                tourist = 0;
                document.getElementById('add').style.display = "block";
                document.getElementById('req').style.display = "none";
            }

            const params = "tourist=" + tourist;

            var xhttp = new XMLHttpRequest();
            var url = "tourist.php";
            xhttp.open("POST", url, true);

            xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    locate();
                }
            };
            xhttp.send(params); 
        }

        //Find a way to get location in background
        //Email user about deals

        function setActive() {

            var xhttp = new XMLHttpRequest();
            var url = "activeMembers.php";
            xhttp.open("POST", url, true);
            xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    getUsers();
                }
            };
            xhttp.send();
        }

        function userListeners() {

            var persons = document.querySelectorAll(".prods .user");

            for ( var i = 0; i < persons.length; i++) {
                persons[i].addEventListener('click', open);
            }
        }

        function open() {
            document.getElementById('to').value = this.innerText;
            document.getElementById('deal').submit();
        }

        document.getElementById('tourist').addEventListener('input', sendTourist);

        setTourist();
    </script>

    </body>

</html>