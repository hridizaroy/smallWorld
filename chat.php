<?php

    $user = "User: ".$_POST['user']."\n";

    $msg = "Msg: ".$_POST['chatContent']."\n";
    $timeOfMsg = "Time: ".$_POST['time']."\n";

    $dateOfMsg = "Date: ".$_POST['fullDate']."\n";

    $username = $_POST['user'];
    $to = $_POST['to'];

    $fileDir = "./$username/chats/";

    $chat = $to.".txt";

    $file = fopen($fileDir.$chat, "a");

    fwrite($file, $msg.$user.$timeOfMsg.$dateOfMsg);

    fclose($file);

?>
