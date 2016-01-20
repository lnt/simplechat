<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>WebRTC Chat</title>
    <script type="text/javascript" src="js/peer.min.js"></script>
    <script src="vendor/jquery.min.js"></script>
    <style>
        .lfloat {
            float: left;
        }
    </style>
</head>
<body style="height: 100%; margin:0">

<div style="width: 100%; height: 100%">
    <div style="position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px">
        <div>
            My ID : <b id="myID"></b>; Peer ID : <b id="peerID"></b>

        </div>
        <div style="position: absolute; top:30px; bottom: 30px; overflow: scroll; width: 100%">
            <div style="height: 100%">
                <ul id="messages">
                </ul>
            </div>
        </div>
        <input id="message_input" style="position: absolute; bottom: 0px; width: 98%" />
    </div>

</div>


<script>

    var hash = window.location.hash.replace(/#/gi,"").split("|");

    var conn;
    var peerME,peerLink;

    function makeConneciton(){
         peerME = new Peer(hash[0],{key: 'gb47i8kw1ihbmx6r'});
         peerLink = new Peer(hash[1],{key: 'gb47i8kw1ihbmx6r'});
    };
    makeConneciton();

    peerME.on("open", function(id) {
        $("#myID").html(id);
    })

    peerME.on("connection", connect);
    peerLink.on("connection", connect);

    function connect(c) {
        conn = c;
        $("#peerID").val(conn.peer);
        conn.on('data',function(data) {
            console.error("ondatachange",data);
        });
        conn.on('disconnect', disconnect);
    }

    function disconnect(){
        alert('Disconnected from the server!');
        $('#peerID').val("");
        $('#progBar').val("0");
        $('#inputTxt').val("");
    }

    $(document).ready(function(){
        var $message  =  $("#message_input");

       var c1 = peerME.connect(hash[1]);
       c1.on('open', function() {
            connect(c1);
       });

       var c2 = peerLink.connect(hash[1]);
        c2.on('open', function() {
            connect(c2);
       });

        $message.change(function(){
            if(conn){
                try{
                    conn.send(JSON.stringify({ name : hash[1], data : $message.val() }));
                } catch (e){
                    makeConneciton();
                }
            }
            $message.val("");
        });

    });


</script>
</body>
</html>