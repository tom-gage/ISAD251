<?php

const DB_SERVER = "proj-mysql.uopnet.plymouth.ac.uk";
const DB_USER = "ISAD251_BGage";
const DB_PASSWORD = 'ISAD251_22211533';
const DB_DATABASE = "ISAD251_BGage";



function getConnection()
{
    $dataSourceName = 'mysql:dbname='.DB_DATABASE.';host='.DB_SERVER;
    $dbConnection = null;
    try{
        $dbConnection = new PDO($dataSourceName, DB_USER, DB_PASSWORD);

    }catch(PDOException $err)
    {
        echo 'Connection failed: ', $err->getMessage();
    }
    return $dbConnection;
}

function getAllMenuItems()//
{
    $statement = getConnection()->prepare("CALL GetAllMenuItems()");
    $statement->execute();
    $menuItems = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $constructedMenuItemsArray = constructMenuItemObjects($menuItems);

}

function constructMenuItemObjects($menuItems){
    $constructedMenuItemsArray = Array();


    for ($i = 0; $i < count($menuItems); ++$i){
        $itemId =  $menuItems[$i]["ItemId"];// convert this value to a string??
        $title = $menuItems[$i]["Title"];
        $details = $menuItems[$i]["Details"];
        $isFood = $menuItems[$i]["IsFood"];
        $price = $menuItems[$i]["Price"];
        $withdrawn = $menuItems[$i]["Withdrawn"];

        $amountInStock = getQuantityLeftInStock($itemId);

        $menuItem = new MenuItem($itemId, $title, $details, $isFood, $price, $withdrawn, $amountInStock);

        array_push($constructedMenuItemsArray, $menuItem);
    }

    return $constructedMenuItemsArray;
}




function getAllOrders(){
    $statement = getConnection()->prepare("SELECT * FROM ordersview");
    $statement->execute();
    $orders = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $constructedOrdersArray = constructOrderObjectsArray($orders);
}

function constructOrderObjectsArray($orders){
    $constructedOrderItemsArray = Array();

    for ($i = 0; $i <count($orders); ++$i){
        $orderId = $orders[$i]["OrderId"];
        $orderDate = $orders[$i]["OrderDate"];
        $customerId = $orders[$i]["CustomerId"];
        $orderItemsArray = Array();

        $orderItemsArray = getOrderItemsByOrderId($orderId);

        $order = new Order($orderId, $orderDate, $customerId, $orderItemsArray);

        array_push($constructedOrderItemsArray, $order);

    }

    return $constructedOrderItemsArray;
}

function getOrderItemsByOrderId($orderId){
    $statement = getConnection()->prepare("CALL GetOrderDetailsByOrderId(".$orderId.")");
    $statement->execute();
    $orderDetails = $statement ->fetchAll(PDO::FETCH_ASSOC);

    return $constructedOrderItemsArray = constructOrderItemsArray($orderDetails);
}


function constructOrderItemsArray($orderDetails){
    $constructedOrderItemsArray = Array();

    for ($i = 0; $i <count($orderDetails); ++$i){
        $orderId = $orderDetails[$i]["OrderId"];
        $itemId = $orderDetails[$i]["ItemId"];
        $quantity = $orderDetails[$i]["Quantity"];

        $orderItem = new OrderItem($orderId, $itemId, $quantity);

        array_push($constructedOrderItemsArray, $orderItem);
    }

    return $constructedOrderItemsArray;
}

function filterOrdersByCustomerId($orders, $customerId){
    $filteredOrders = array();

    for ($i = 0; $i <count($orders); ++$i){
        if($orders[$i]->getCustomerId() == $customerId) {
            array_push($filteredOrders, $orders[$i]);
        }
    }

    return $filteredOrders;
}

function filterOrdersByOrderId($orders, $orderId){
    $filteredOrders = array();

    for ($i = 0; $i <count($orders); ++$i){
        if($orders[$i]->getOrderId() == $orderId) {
            array_push($filteredOrders, $orders[$i]);
        }
    }

    return $filteredOrders;
}

function checkOutOrder($orderId){
    $statement = getConnection()->prepare("CALL CheckOutOrder(".$orderId.")");
    $statement->execute();
    $resultSet = $statement ->fetchAll(PDO::FETCH_ASSOC);
    return $resultSet;
}

function cancelOrder($canceledOrder){
    $canceledItems = $canceledOrder->getOrderItems();

    for ($i = 0; $i <count($canceledItems); ++$i){
        $itemId = $canceledItems[$i]->getItemId();
        $quantity = $canceledItems[$i]->getQuantity();
        echo $itemId."<br>";
        echo $quantity."<br>";

        $statement = getConnection()->prepare("CALL CancelOrderItem(".$itemId.",".$quantity.")");
        $statement->execute();
    }

    $orderId = $canceledOrder->getOrderId();
    $statement = getConnection()->prepare("CALL CancelOrder(".$orderId.")");
    $statement->execute();
}

function setQuantityInStock($itemId, $quantity){
    $statement = getConnection()->prepare("CALL SetStockQuantity(".$itemId.", ".$quantity.")");
    $statement->execute();
}

function updatePage(){
    echo "<meta http-equiv='refresh' content='0'>";
}




function getMenuItems(){
    $statement = getConnection()->prepare("CALL GetMenuItems()");
    $statement->execute();
    $resultSet = $statement ->fetchAll(PDO::FETCH_ASSOC);
    return $resultSet;
}

function getMenuItemTitle($itemId){
    $statement = getConnection()->prepare("CALL GetMenuItemTitle(".$itemId.")");
    $statement->execute();
    $resultSet = $statement ->fetchAll(PDO::FETCH_ASSOC);
    return $resultSet;
}

function getMenuItemPrice($itemId){
    $statement = getConnection()->prepare("CALL GetMenuItemPrice(".$itemId.")");
    $statement->execute();
    $resultSet = $statement ->fetchAll(PDO::FETCH_ASSOC);
    return $resultSet;
}

function addMenuIem($title, $details, $isFood, $price, $withdraw){
    $statement = getConnection()->prepare("CALL AddMenuItem('".$title."', '".$details."', ".$isFood.", ".$price.", ".$withdraw.")");
    $statement->execute();
    $resultSet = $statement ->fetchAll(PDO::FETCH_ASSOC);
    return $resultSet;
}


function editMenuItem($itemId, $title, $details, $isFood, $price, $withdraw){
    $statement = getConnection()->prepare("CALL EditMenuItem(".$itemId.", '".$title."', '".$details."', ".$isFood.", ".$price.", ".$withdraw.")");

    $statement->execute();
    $resultSet = $statement ->fetchAll(PDO::FETCH_ASSOC);
    return $resultSet;
}




function getQuantityLeftInStock($itemId){
    $statement = getConnection()->prepare("CALL GetAmountInStock(".$itemId.")");
    $statement->execute();
    $resultSet = $statement ->fetchAll(PDO::FETCH_ASSOC);
    return $resultSet;
}


function getOrderDetails($orderId){
    $statement = getConnection()->prepare("CALL GetOrderDetails(".$orderId.")");
    $statement->execute();
    $resultSet = $statement ->fetchAll(PDO::FETCH_ASSOC);
    return $resultSet;
}


function createNewOrder($OrderId, $OrderDate, $CustomerId){
    $statement = getConnection()->prepare("CALL CreateNewOrder(" . $OrderId . ", '" . $OrderDate . "', " . $CustomerId . ")");

    $statement->execute();
}

function addItemToOrder($OrderId, $menuItemTitle, $Quantity)
{
    $statement = getConnection()->prepare("CALL AddItemToOrder(" . $OrderId . ", '" . $menuItemTitle . "', " . $Quantity . ")");

    $statement->execute();

}

function editOrderItemQuantity($OrderId, $menuItemTitle, $Quantity)
{
    $statement = getConnection()->prepare("CALL EditOrderItemQuantity(" . $OrderId . ", '" . $menuItemTitle . "', " . $Quantity . ")");

    $statement->execute();

}

function checkOutItem($orderId){
    $statement = getConnection()->prepare("CALL CheckOutOrder(" . $orderId . ")");

    $statement->execute();
}
