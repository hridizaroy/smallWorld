<?php

$_SESSION['email'] = $_POST['email'];
$_SESSION['first_name'] = $_POST['firstname'];
$_SESSION['last_name'] = $_POST['lastname'];
$_SESSION['username'] = $_POST['username'];

$first_name = $mysqli->escape_string($_POST['firstname']);
$last_name = $mysqli->escape_string($_POST['lastname']);
$username = $mysqli->escape_string($_POST['username']);
$email = $mysqli->escape_string($_POST['email']);
$password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
$hash = $mysqli->escape_string( md5( rand(0,1000) ) );

$result = $mysqli->query("SELECT * FROM users WHERE email = '$email'") or die($mysqli->error());

if ($result->num_rows > 0) {
    $_SESSION['message'] = "User with email already exists.";
    header("location: error.php");
}

else {

    $sql = "INSERT INTO users (username, password, hash, first_name, last_name, email)" . "VALUES ('$username', '$password', '$hash', '$first_name', '$last_name', '$email')";

    if ( $mysqli->query($sql) ){
        $_SESSION['active'] = 0;
        $_SESSION['logged_in'] = true;
        
        $_SESSION['message'] = 
                    "Confirmation link has been sent to $email, please verify your account by clicking on the link in the message.";
    
        $to = $email;
        $subject = 'Account Verification (travel.co)';
        $message_body = '
        Hello '.$first_name.',
        Thank You for signing up!
        Please click this link to activate your account:

        http://www.travel.co/verify.php?email='.$email.'&hash='.$hash;

        //mail( $to, $subject, $message_body );

        if (mkdir("$username", 0777, true)) {
            if (mkdir("$username/chats", 0777, true)) {
                header("location: success.php"); 
            }
        }
    }

    else {
        $_SESSION['message'] = 'Registration Failed.';
        header("location: error.php");
    }
}

?>
