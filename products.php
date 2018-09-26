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
//Create the game class
$game = new Game($db);
//Get all the games
$games = $game->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>D Game World: Products</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        .nbm{
            margin-bottom: -20px;
        }
    </style>
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
                    <li class="active">
                        <a href="#">Products</a>
                    </li>
                    <li>
                        <a href="contact.php">Contact</a>
                    </li>
                    <li>
                        <a href="login.php">Sign-in</a>
                    </li>
                    <li>
                        <a href="cart.php">
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
            <?php
            foreach($games as $product)
            {
				$image = "img/800x500.png";
				if(empty($product["Image"]) == false)
					$image = "img/" . $product["Image"];
            ?>
            <div class="col-md-4">
                <div class="thumbnail">
                    <a href="#">
                        <img src="<?php echo $image; ?>" alt=""/>
                    </a>
                    <div class="caption">
                        <h4>
                            <a href="#"><?php echo $product["Name"]?></a>
                        </h4>
                        <p>Product Description</p>
                        <div class="row">
                            <div class="col-md-7">
                                <h3 style="margin:5px auto;"><label>R<?php echo $product["Price"]?></label></h3>
                            </div>
                            <div class="col-md-5">
                                <a class="btn btn-success btn-product add-to-cart" data-id="<?php echo $product["Game_ref_no"]; ?>"><span class="glyphicon glyphicon-shopping-cart"></span> Add To Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>

        </div>
    </div>

    <div class="navbar navbar-default nbm">
        <div class="container">
            <p class="navbar-text pull-left">&copy; 2016 - Built By D Game World
            </p>
            <p class="navbar-text pull-right">
                <a href="contact.php">Contact Us</a>
            </p>
        </div>
    </div>
</body>
<script>
    $(document).ready(function(){
        $(".add-to-cart").on("click", function(){
            var game = $(this).attr("data-id");
            var data = {};
            data["id"] = game;
            data["action"] = "add";
            data["source"] = "products";
            console.log(data);
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
                    alert(result.msg);
                    $(".cart").html(result.items);
                    console.log(result);
                }
            });
        });
        return false;
    });
</script>
</html>