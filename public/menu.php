<?php
include $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/DBFunctions.php';
include $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/Menu.php';
include $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/MenuItem.php';

//echo $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/DBFunctions.php'."<br>";
//echo $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/Menu.php'."<br>";
//echo $_SERVER['DOCUMENT_ROOT'].'/ISAD251/btgage/src/model/MenuItem.php'."<br>";

?>
<html lang="en">
<head>
    <title>Menu</title>
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
        <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>admin.php">Admin</a>
        <a href="/ISAD251/btgage/index.php">Home</a>
        <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>menu.php" style="text-decoration: underline">Menu</a>
        <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>order.php">Order</a>
    </div>
</nav>
<div class="jumbotron">
    <h1 style="text-align: center">Our Menu</h1>
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

//            try{
                $menuItemsArray = getAllMenuItems();

                if(empty($menuItemsArray)){
                    echo '
                    No menu items available! Please try again later!
                    ';
                }



                for ($i = 0; $i < count($menuItemsArray); ++$i){

                    $itemId = $menuItemsArray[$i]->getItemId();
                    $itemTitle = $menuItemsArray[$i]->getTitle();
                    $itemDetails = $menuItemsArray[$i]->getDetails();
                    $itemIsFood =  $menuItemsArray[$i]->getIsFood();
                    $itemPrice =  $menuItemsArray[$i]->getPrice();
                    $itemWithdrawn = $menuItemsArray[$i]->getWithdrawn();

                    $quantityLeftInStock = getQuantityLeftInStock($itemId);
                        if(!empty($quantityLeftInStock)){
                            $quantityLeftInStock = $quantityLeftInStock[0]["Quantity"];
                        }else{
                            $quantityLeftInStock = 0;
                        }

                        if($itemWithdrawn == 0){
                        echo '        
                        <li class="list-group-item">
                            ' . $itemTitle . ' - ' . $itemDetails . ' - £' . $itemPrice . '
                            <span class="pull-right"> 
                                <form action="menu.php" method="post">
                                    <input type="number" name="' . $itemId . 'Quantity" min="0" max="'.$quantityLeftInStock.'">
                                    <input type="submit" name="' . $itemId . 'Add" value="Add to Order">
                                </form> 
                            </span>
                            <span class="pull-right"></span>
                        </li>
                        ';
                    }


                    if (isset($_POST[$itemId . "Add"])) {

                        $orderId = 1;
                        $quantity = $_POST[$itemId."Quantity"];
                        $date = date("Y-m-d");


                        createNewOrder($orderId, $date, 1);

                        addItemToOrder($orderId, $itemTitle, $quantity);
                        updatePage();

                    }

                }
//            }catch(Exception $e){
//                echo 'Error!';
//            }

            ?>

        </div>
        <div class="col-md-3"></div>
    </div>

</div>


</body>
</html>
