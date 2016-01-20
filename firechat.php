<?php

include_once "vendor/autoload.php";

use Firebase\Token\TokenException;
use Firebase\Token\TokenGenerator;

$usern = isset($_GET["user"]) ? $_GET["user"] : "lalit";

try {
    $generator = new TokenGenerator('YeKaaJU14OtA9Q8BK5v1NzTHC1pTTmwpnbA3O1lv');
    $token = $generator
        ->setData(array(
            'uid' => $usern,
            'displayName' => $usern))
        ->create();
} catch (TokenException $e) {
    echo "Error: " . $e->getMessage();
}

//echo $token;

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
    <!-- jQuery -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js'></script>

    <!-- Firebase -->
    <script src='bower_components/firebase/firebase.js'></script>

    <!-- Firechat -->
    <link rel='stylesheet' href='bower_components/firechat/dist/firechat.min.css'/>
    <script src='bower_components/firechat/dist/firechat.min.js'></script>
</head>
<body>
<div id='firechat-wrapper'></div>

<script>


    // Create a new Firebase reference, and a new instance of the Login client
    var chatRef = new Firebase('https://resplendent-inferno-9558.firebaseio.com/chat');

    function initChat(authData) {
        var chat = new FirechatUI(chatRef, document.getElementById('firechat-wrapper'));
        console.error("====",authData.uid, authData.auth.displayName);
        chat.setUser(authData.uid, authData.auth.displayName);
    }

    function login(){
        chatRef.authWithCustomToken("<?= $token; ?>", function (error, authData) {
            if (error) {
                console.log("Login Failed!", error);
            } else {
                console.log("Login Succeeded!", authData);
            }
        });
    }

    jQuery(document).ready(function(){


        chatRef.onAuth(function (authData) {
            // Once authenticated, instantiate Firechat with our user id and user name
            console.error("authData",authData)
            if (authData) {
                initChat(authData);
            } else {
                login();
            }
        });

    });

</script>

</body>
</html>