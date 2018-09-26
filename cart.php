<?php
include_once("Classes/Session.php");
include_once("Classes/Cart.php");
include_once("Classes/Game.php");
//Start the session
session_start();
//Create the database
$db = mysqli_connect("localhost", "root", "", "dee");
//Create the session class
$session = new Session();
//Create the cart class
$cart = new Cart($db);
//Get all the games
$games = $cart->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>D Game World: Shopping Cart</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div id="header">
        </div>
    </div>
    <nav class="navbar navbar-inverse navbar-static-top">

        <div class="container">

            <a href="#" class="navbar-brand">D Game World</a>
            <button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse"></button>

            <div class="collapse navbar-collapse navHeaderCollapse">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="index.php">Home</a>
                    </li>
                    <li>
                        <a href="products.php">Products</a>
                    </li>
                    <li>
                        <a href="contact.php">Contact</a>
                    </li>
                    <li>
                        <a href="login.php">Sign-in</a>
                    </li>
                    <li class="active">
                        <a href="#">
                            <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Cart
                            <span class="label label-info cart"><?php echo $cart->numberOfItems(); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="well">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        //Check if there are no products in the shopping cart
                        if(empty($games)){
                            ?>
                            <tr>
                                <td>There are currently no products in your cart. <a href="products.php">Start shopping.</a></td>
                            </tr>
                            <?php
                        }else{
                        ?>
                            <form action="" method="post" class="form-inline" id="form-cart">
                        <?php
                            foreach($games as $game){
                                ?>
                                <tr>
                                    <td class="col-md-4"><?php echo $game["Name"]; ?></td>
                                    <td class="col-md-2">
                                        <input name="qty" type="number" class="form-control input-sm qty" value="<?php echo $game["Qty"]; ?>" min="1" max="<?php echo $game["Stock"]; ?>">
                                    </td>
                                    <td class="col-md-2 text-center">R<?php echo $game["Price"]; ?></td>
                                    <td class="col-md-4">
                                        <input name="id" type="hidden" class="game" value="<?php echo $game["Game_ref_no"]; ?>" />
                                        <input name="remove" type="submit" class="btn btn-danger btn-sm remove" value="Remove">
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            </form>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
            //Check if there are items in the cart
            if($cart->numberOfItems() > 0) {
                ?>
                <div class="col-md-4">
                    <div class="well">
                        <h4>Shopping Cart Details</h4>
                        <hr>
                        <table class="table">
                            <tr>
                                <td>Sub total</td>
                                <td id="sub-total">R<?php echo number_format($cart->subTotal(), 2); ?></td>
                            </tr>
                            <tr>
                                <td>Delivery</td>
                                <td>R25.00</td>
                            </tr>
                            <tr>
                                <td class="success">Total</td>
                                <td class="success" id="total">R<?php echo number_format($cart->subTotal() + 25, 2); ?></td>
                            </tr>
                        </table>
                        <a href="checkout.php" class="btn btn-success">Checkout</a>
                        <a href="products.php" class="btn btn-default">Continue Shopping</a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
<script>
    $(document).ready(function(){
        $('.qty').on('change', function(){
            var row = $(this).parent().parent();
            var game = row.find('.game').val();
            var data = {};
            data["id"] = game;
            data["qty"] = $(this).val();
            data["action"] = "edit";
            console.log(game);
            $.ajax({
                url: 'parser.php',
                dataType: 'json',
                type: 'post',
                data: data,
                error: function(jqXHR, txtStatus, errThrown){
                    console.log(jqXHR.responseText);
                    console.log(txtStatus);
                },
                success: function(result){
                    console.log(result);
                    $(".cart").html(result.items);
                    $("#sub-total").html("R" + result.sub.toFixed(2));
                    $("#total").html("R" + (result.sub + 25).toFixed(2));
                }
            });
        });

        $('.remove').on('click', function(){
            if(confirm("Are you sure you want to remove this game?")){
                var row = $(this).parent().parent();
                var game = row.find('.game').val();
                var data = {};
                data["id"] = game;
                data["action"] = "remove";
                console.log(data);
                $.ajax({
                    url: 'parser.php',
                    dataType: 'json',
                    type: 'post',
                    data: data,
                    error: function(jqXHR, txtStatus, errThrown){
                        console.log(jqXHR.responseText);
                        console.log(txtStatus);
                        console.log(errThrown);
                    },
                    success: function(result){
                        console.log(result);
                        row.remove();
                    }
                });
            }
            return false;
        });
    });
</script>
</body>
</html>