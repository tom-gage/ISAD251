<?php
include $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/DBFunctions.php';
include $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/Menu.php';
include $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/MenuItem.php';
include $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/Order.php';
include $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/OrderItem.php';
?>
<html lang="en">
<head>
    <title>Admin</title>
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
        <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>admin.php" style="text-decoration: underline">Admin</a>
        <a href="/ISAD251/btgage/index.php">Home</a>
        <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>menu.php">Menu</a>
        <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>order.php">Order</a>
    </div>
</nav>


<div class="container-fluid">
    <h1>Admin Page</h1>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3"></div>

        <div class="col-md-6">
            <h3>Menu Item Editor</h3>
            <br>
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
                $menuItems = getMenuItems();

                $menuItemsArray = getAllMenuItems();

                for ($i = 0; $i < count($menuItemsArray); ++$i){

                    $itemId = $menuItemsArray[$i]->getItemId();
                    $itemTitle = $menuItemsArray[$i]->getTitle();
                    $itemDetails = $menuItemsArray[$i]->getDetails();
                    $itemIsFood =  $menuItemsArray[$i]->getIsFood();
                    $itemPrice =  $menuItemsArray[$i]->getPrice();
                    $itemWithdrawn = $menuItemsArray[$i]->getWithdrawn();
                    $amountInStock = $menuItemsArray[$i]->getAmountInStock();

                    $amountInStock = $amountInStock[0]["Quantity"];

//                    <span>Amount in stock:<input type="number" name="' . $itemId . 'amount" value="'.$amountInStock[0]["Quantity"].'" min="0" size="1"></span> <br>
                    echo '
                <form action="admin.php" method="post">
                <li class="list-group-item">
                        
                
                        <span>Menu Item ID: ' .$itemId.'</span><br>
                        <span>Title:<input type="text" name="' . $itemId . 'Title" value="'.$itemTitle.'" minlength="0" maxlength="100" size="20"></span> <br>
                        <span>Details:<input type="text" name="' . $itemId . 'Details" value="'.$itemDetails.'" minlength="0" maxlength="400" size="30"></span> <br>
                        <span>Is food:<input type="number" name="' . $itemId . 'IsFood" value="'.$itemIsFood.'" min="0" max="1" size="1"></span> <br>
                        <span>Price £<input type="number" name="' . $itemId . 'Price" value="'.$itemPrice.'" min="1" max="100"></span> <br>
                        <span>Is withdrawn:<input type="number" name="' . $itemId . 'Withdrawn" value="'.$itemWithdrawn.'" min="0" max="1" size="1"></span> <br>
                        <span>Amount in stock:<input type="number" name="' . $itemId . 'amount" value="'.$amountInStock.'" min="0" size="1"></span> <br>


                        
                        <input type="submit" name="' . $itemId . 'Edit" value="Commit edits to '.$itemTitle.'">
                </li>
                </form>
                ';

                    if (isset($_POST[$itemId.'Edit'])) {
                        $Id = $itemId;
                        $newTitle = $_POST[$itemId.'Title'];
                        $newDetails = $_POST[$itemId.'Details'];
                        $newIsFood = $_POST[$itemId.'IsFood'];
                        $newPrice = $_POST[$itemId.'Price'];
                        $newWithdrawn = $_POST[$itemId.'Withdrawn'];
                        $newAmount = $_POST[$itemId.'amount'];

                        editMenuItem($Id, $newTitle, $newDetails, $newIsFood, $newPrice, $newWithdrawn);
                        setQuantityInStock($Id, $newAmount);
                        updatePage();
                    }

                    if (isset($_POST[$itemId.'Delete'])) {
                        $Id = $itemId;

                        editMenuItem($Id, $newTitle, $newDetails, $newIsFood, $newPrice, $newWithdrawn);
                        updatePage();
                    }


                }
                echo '
                <br>
                <h3>Add New Menu Item</h3>
                ';


                echo '
                <form action="admin.php" method="post">
                <li class="list-group-item">
                        <span>New Menu Item</span>
                        <span>Title:<input type="text" name="newTitle" value="" minlength="0" maxlength="100" size="20"></span> <br>
                        <span>Details:<input type="text" name="newDetails" value="" minlength="0" maxlength="400" size="30"></span> <br>
                        <span>Is food:<input type="number" name="newIsFood" value="" min="0" max="1" size="1"></span> <br>
                        <span>Price £<input type="number" name="newPrice" value=""></span> <br>
                        <span>Is withdrawn:<input type="number" name="newWithdrawn" value="" min="0" max="1" size="1"></span> <br>
                        
                        <input type="submit" name="AddNewMenuItem" value="Add New Menu Item">
                        
                </li>
                </form>
                    ';

                if (isset($_POST['AddNewMenuItem'])) {
                    $newTitle = $_POST['newTitle'];
                    $newDetails = $_POST['newDetails'];
                    $newIsFood = $_POST['newIsFood'];
                    $newPrice = $_POST['newPrice'];
                    $newWithdrawn = $_POST['newWithdrawn'];

                    addMenuIem($newTitle, $newDetails, $newIsFood, $newPrice, $newWithdrawn);
                    updatePage();
                }




                echo '
            <br>
            <h3>View Open Orders</h3>
            ';

//                $orders = getOrders();

                $allOrders = getAllOrders();

                for ($i = 0; $i < count($allOrders); ++$i){
                    $orderId = $allOrders[$i]->getOrderId();
                    $orderDate = $allOrders[$i]->getOrderDate();
                    $customerId = $allOrders[$i]->getCustomerId();
                    $itemsArray = $allOrders[$i]->getOrderItems();





                    echo '
                <li class="list-group-item">
                    <span>Order ID: '.$orderId.', Customer ID: '.$customerId.', Date of Order: '.$orderDate.' <br></span>
                ';

                    for ($x = 0; $x < count($itemsArray); ++$x){
                        $itemTitlesArray = getMenuItemTitle($itemsArray[$x]->getItemId());
                        $itemTitle = $itemTitlesArray[0]["Title"];

                        $itemPriceArray = getMenuItemPrice($itemsArray[$x]->getItemId());
                        $itemPrice = $itemPriceArray[0]["Price"];

                        $quantity = $itemsArray[$x]->getQuantity();

                        echo '
                    <span>
                    Item '.($x + 1).': '.$itemTitle.', Price: '.$itemPrice.', Quantity: '.$quantity.'<br>
                    </span>
                    ';
                    }


                    echo '
                </li>
                ';
                }

            }catch(Exception $e){
                echo $e;
            }


            ?>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>


</body>
</html>