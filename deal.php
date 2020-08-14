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
    $to = $_POST['to'];

?>


<html>
    <head>
        <title>Open File</title>
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
        <link rel = "stylesheet" href = "css/style.css">
        <style>
            .chatwindow {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                width: 30%;
                border: 1px solid #000;
            }

            .send {
                position: absolute;
                bottom: 0;
                width: 100%;
            }

            .chat {
                width: 100%;
                height: 5%;
                min-height: 35px;
                padding-top: 1%;
                padding-bottom: 1%;
                max-height: 150px;
            }

            .msgContainer {
                display: block;
            }

            .msg {
                padding: 2% 6%;
                margin: 10px 5px;
                border: 0.5px solid #000;
                display: inline-block;
            }

            .msgs {
                height: 77%;
                overflow: auto;
            }

            .date {
                margin: auto;
                text-align: center;
            }

        </style>
    </head>
    <body>

        <!--Chat Window-->
        <div class = "chatwindow">
            <p class = "mdl-color--primary mdl-shadow--2dp title">Chat</p>
            <div class = "msgs">
            </div>
            <!--Send content-->
            <div class = "send">
                <textarea type = "text" class = "chat"></textarea>
                <button class = "sendButton mdl-button mdl-js-button mdl-button--raised">Send</button>
                <input name = "user" value = '<?php echo $username ?>' class = "user" hidden>
            </div>
        </div>

        <script>

            function send() {

                if (document.querySelector('.chat').value != "") {

                    var date = new Date();
                    var day = date.getDate();
                    var month = date.getMonth();
                    var year = date.getFullYear();
                    var h = date.getHours();
                    var m = date.getMinutes();
                    if (m < 10) {
                        m = "0" + String(m);
                    }
                    var time = h + ":" + m;
                    var fullDate = String(day) + "/" + String(month) + "/" + String(year)

                    const params = "user=" + '<?php echo $username; ?>' + "&chatContent=" + document.querySelector('.chat').value + "&time=" + time + "&fullDate=" + fullDate + "&to=" + '<?php echo $to; ?>';

                    var xhttp = new XMLHttpRequest();
                    var url = "chat.php"; //tried to change page
                    xhttp.open("POST", url, true);

                    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            // Updated
                        }
                    };
                    xhttp.send(params);

                    document.querySelector(".chat").value = "";
                    document.querySelector(".chat").style.height = "35px";

                }
            }

            function getChats(){

                const params = "user=" + '<?php echo $username; ?>' + "&to=" + '<?php echo $to; ?>';

                var xhttp = new XMLHttpRequest();
                var url = "getChats.php";
                xhttp.open("POST", url, true);

                xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if(!(xhttp.responseText.startsWith("<") || xhttp.responseText === chats)) {

                            var msg, user, msgTime, msgDate;
                            var updateChats = xhttp.responseText;
                            chats = updateChats;

                            updateChats = updateChats.split("Msg: ");

                            var dates = document.body.getElementsByClassName('date');

                            document.querySelector(".msgs").innerHTML = "";

                            for (var i = 1; i < updateChats.length; i++){
                                msg = updateChats[i].split("User: ")[0];
                                user = updateChats[i].split("User: ")[1].split("Time: ")[0];
                                msgTime = updateChats[i].split("User: ")[1].split("Time: ")[1].split("Date: ")[0];
                                msgDate = updateChats[i].split("User: ")[1].split("Time: ")[1].split("Date: ")[1];

                                var container = document.createElement("div");
                                container.classList.add("msgContainer");

                                user = user.trim();

                                if (user == '<?php echo $username?>') {
                                    container.style.textAlign = "right";
                                }
                                else {
                                    container.style.textAlign = "left";
                                }

                                var dateP = document.createElement("p");
                                dateP.classList.add("date");

                                var dateRepeat = false;

                                for (var t = 0; t < dates.length; t++) {
                                    if (dates[t].innerHTML === msgDate) {
                                        dateRepeat = true;
                                        break;
                                    }
                                }

                                if (!(dateRepeat)) {
                                    dateP.innerHTML = msgDate;
                                    container.appendChild(dateP);
                                }

                                var newMsg = document.createElement("div");
                                newMsg.classList.add("msg");

                                var content = document.createElement("div");
                                content.classList.add("content");
                                content.innerHTML = msg;
                                var memberName = document.createElement("div");
                                memberName.classList.add("memberName");
                                memberName.innerHTML = user;
                                var timeOfMsg = document.createElement("div");
                                timeOfMsg.classList.add("time");
                                timeOfMsg.innerHTML = msgTime;

                                newMsg.appendChild(content);
                                newMsg.appendChild(memberName);
                                newMsg.appendChild(timeOfMsg);

                                container.appendChild(newMsg);

                                document.querySelector(".msgs").appendChild(container);

                                document.querySelector(".msgs").scrollTop = document.querySelector(".msgs").scrollHeight;
                            }
                        }
                    }
                };
                xhttp.send(params);
            }

            document.querySelector(".chat").addEventListener('input', print);

            function print() {
                var sh = document.querySelector(".chat").scrollHeight;
                var ch = document.querySelector(".chat").clientHeight;
                document.querySelector(".chat").style.height = sh + "px";
                if (ch < 148) {
                    document.querySelector(".chat").style.overflow = "hidden";
                }
                else {
                    document.querySelector(".chat").style.overflow = "auto";
                }
            }

            document.querySelector(".sendButton").addEventListener('click', send);
            var updateChatsInterval = setInterval(getChats, 500);
            var chats = "";
        
        </script>
    </body>
</html>