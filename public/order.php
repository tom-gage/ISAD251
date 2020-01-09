<?php
include $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/DBFunctions.php';
include $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/MenuItem.php';
include $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/Order.php';
include $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/OrderItem.php'
?>
<html lang="en">
<head>
    <title>Your Order</title>
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
        text-align: left;

    }

    h1 {
        font-family: "Inconsolata", sans-serif;
        text-align: left;
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


</style>

<body>


<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <a href="<?php $_SERVER['HTTP_HOST'] ?>admin.php">Admin</a>
        <a href="/ISAD251/btgage/index.php">Home</a>
        <a href="<?php $_SERVER['HTTP_HOST'] ?>menu.php">Menu</a>
        <a href="<?php $_SERVER['HTTP_HOST'] ?>order.php" style="text-decoration: underline">Order</a>
    </div>
</nav>

<div class="jumbotron">
    <strong><h1 style="text-align: center">Your Order</h1></strong>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3"></div>
            <div class="col-md-6">

                <?php

                set_error_handler('exceptions_error_handler');

                function exceptions_error_handler($severity, $message, $filename, $lineno) {
                    if (error_reporting() == 0) {
                        return;
                    }
                    if (error_reporting() & $severity) {
                        throw new ErrorException($message, 0, $severity, $filename, $lineno);
                    }
                }

                try{
                    $allOrders = getAllOrders();

                    $filteredOrders = array();
                    $filteredOrders = filterOrdersByCustomerId($allOrders, 1);
                    $filteredOrders = filterOrdersByOrderId($filteredOrders, 1);

                    $orderItems = array();
                    $orderItems = $filteredOrders[0]->getOrderItems();

                    $itemTitlesArray = array();

                    $total = 0;

                    echo '
                <ul class="list-group">
                ';

                    for ($i = 0; $i < count($orderItems); ++$i){
                        $itemTitlesArray = getMenuItemTitle($orderItems[$i]->getItemId());
                        $itemTitle = $itemTitlesArray[0]["Title"];

                        $itemPriceArray = getMenuItemPrice($orderItems[$i]->getItemId());
                        $itemPrice = $itemPriceArray[0]["Price"];

                        $itemQuantity = $orderItems[$i]->getQuantity();

                        $quantityLeftInStock = getQuantityLeftInStock($orderItems[$i]->getItemId());

                        $total = $total + ($itemQuantity * $itemPrice);

                        echo '
                    <li class="list-group-item">
                        <span>'.$itemTitle.'(£'.$itemPrice.') x '.$itemQuantity.'</span>
                    </li>
                    ';
                    }

                    echo '
                </ul>


                <h2>Total: £'.$total.'</h2>
                <div></div>
                ';


                    echo '
                <form action="order.php" method="post">
                                <input type="submit" name="CheckOut" value="Check Out">
                </form>

                <br>

                <form action="order.php" method="post">
                                <input type="submit" name="CancelOrder" value="Cancel Order">
                </form>
                ';

                    if (isset($_POST['CheckOut'])) {
                        checkOutOrder(1);
                        updatePage();
                    }

                    if (isset($_POST['CancelOrder'])) {
                        $canceledOrder = $filteredOrders[0];
                        cancelOrder($canceledOrder);
                        updatePage();
                    }


                }catch(Exception $e){
                    echo "No active orders!";
                }




                ?>
            </div>
        <div class="col-md-3"></div>
    </div>

</div>


</body>
</html>
