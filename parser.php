<?php
include_once("Classes/Cart.php");

//echo "<pre>";
session_start();
$msg = array("msg" => "Nothing so far!");

if(isset($_POST)){
    $db = mysqli_connect("localhost", "root", "", "dee");
    $cart = new Cart($db);

    if($_POST["action"] == "add"){
        //Clean the post variable for XSS and SQL injection
        $game = (int) $_POST["id"];
        //Check for the source of the action
        if($_POST["source"] == "products"){
            $qty = 1;
        }else{
            //Type cast the quantity in order to ensure it's an integer
            $qty = (int) $_POST["qty"];
        }
        //Add the game to the cart
        $cart->add($game, $qty);
        //Give feedback to the user
        $msg = array("msg" => "Product was added successfully", "items" => $cart->numberOfItems());
    }
    if($_POST["action"] == "edit"){
        //Clean the post variable for XSS and SQL injection
        $game = (int) $_POST["id"];
        //Type cast the quantity in order to ensure it's an integer
        $qty = (int) $_POST["qty"];
        //Add the game to the cart
        $cart->update($game, $qty);
        //Give feedback to the user
        $msg = array("msg" => "Product was updated successfully", "items" => $cart->numberOfItems(), "sub" => $cart->subTotal());
    }
    if($_POST["action"] == "remove"){
        //Clean the post variable for XSS
        $game = (int) $_POST["id"];
        //Remove the game from the cart
        $cart->remove($game);
        //Give feedback to the user
        $msg = array("msg" => "Product was removed successfully", "items" => $cart->numberOfItems(), "sub" => $cart->subTotal());
    }
}

echo json_encode($msg);
?>