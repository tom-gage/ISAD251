<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../../src/model/DBFunctions.php';


$menuItems = getMenuItems();

if($menuItems)
{
    $code = 200;
    echo returnJSON($menuItems, $code);
}
else{
    http_response_code(404);

    echo json_encode(
        array("message" => "Error 404, Menu Items not found")
    );
}

function returnJSON($response, $code)
{
    header_remove();
    http_response_code($code);
    header('Content-Type: application/json');
    header('Status: '.$code);
    return json_encode(array('status' => $code, 'message' => $response));
}