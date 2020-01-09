<?php
?>
<html lang="en">
<head>
    <title>ISAD251 Teahouse - Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

</head>

<style>
    body, html {
        height: 100%;
        font-family: "Inconsolata", sans-serif;
        text-align: center;

    }

    .navbar a {
        float: left;
        padding: 12px;
        color: white;
        text-decoration: none;
        font-size: 20px;
        width: 33.33%;
        text-align: center;
    }

    .jumbotron {
        text-align: center;

        background-image: url("/ISAD251/btgage/assets/img/cherryBlossom.jpg");

        background-repeat: repeat;
    }


    .highlighted {
        background-color: rgba(255, 160, 224, 0.5);
    }

</style>

<body>



    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <a href= "<?php $_SERVER['DOCUMENT_ROOT'] ?>/ISAD251/btgage/public/admin.php">Admin</a>
            <a href="/ISAD251/btgage/index.php" style="text-decoration: underline">Home</a>
            <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/ISAD251/btgage/public/menu.php">Menu</a>
            <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/ISAD251/btgage/public/order.php">Order</a>
        </div>
    </nav>

    <div class="jumbotron">
        <h1> Welcome to the ISAD251 Tea House  </h1>
    </div>


    <div class="container-fluid">
        <p>We serve tea, coffee and biscuits.</p>
    </div>

    <div id="orderNow">
        <a class="btn btn-success" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>public/menu.php">Order Now!</a>
    </div>

</body>
</html>
