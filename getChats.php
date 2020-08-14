<?php

    $username = $_POST['user'];
    $to = $_POST['to'];

    $fileDir = "./$username/chats/";

    $chat = "";

    if($handle = opendir($fileDir)) {
        global $chat;
        while ($entry = readdir($handle)) {
            if ( $entry != "."  && $entry != ".." ){
                $chat = $entry;
            }
        }
        closedir($handle);
    }

    if ($chat == $to.".txt") {
        $file = fopen($fileDir.$chat, "r");

        $allChats = fread($file, filesize($fileDir.$chat));

        fclose($file);

        echo $allChats;
    }

?>