<?php

    session_start();
    if(!isset($_SESSION["usuario"])){
        header("Location: ../Front-End/HTML/informacion.html");
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    

    <script>
        window.addEventListener("beforeunload", function (e) {
            navigator.sendBeacon("../../../Back-End/PHP/logout.php");
        });
    </script>

</body>
</html>