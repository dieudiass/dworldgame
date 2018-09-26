<?php
include_once("Classes/Cart.php");
include_once("Classes/Game.php");
include_once("Classes/Session.php");
//Start the session
session_start();
//Create the database
$db = mysqli_connect("localhost", "root", "", "dee");
//Create the session class
$session = new Session();
//Create the cart class
$cart = new Cart($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>D Game World: Home</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        .back{
            background-color: #1B2631;
            color: #FFF;
        }
        .nm{
            margin-top: -20px;
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
                <li class="active">
                    <a href="#">Home</a>
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
<div id="img-slider" class="carousel slide nm" data-ride="carousel">
    <!-- Carousel indicators-->
    <ol class="carousel-indicators">
        <li data-target="#img-slider" data-slide-to="0" class="active"></li>
        <li data-target="#img-slider" data-slide-to="1"></li>
        <li data-target="#img-slider" data-slide-to="2"></li>
    </ol>

    <!-- Slide items -->
    <div class="carousel-inner">
        <div class="item active">
            <img alt="" src="/imag/war.jpg"/>
            <div class="carousel-caption"></div>
        </div>
        <div class="item">
            <img alt="" src="/imag/great.jpg"/>
            <div class="carousel-caption"></div>
        </div>
    </div>

    <!-- Carousel controls-->
    <a class="left carousel-control" href="#img-slider" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#img-slider" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<div class="navbar navbar-inverse navbar-fixed-bottom">
    <div class="container">
        <p class="navbar-text pull-left">&copy; 2016 - Built By D Game World
        </p>
        <p class="navbar-text pull-right">
            <a href="contact.php">Contact Us</a>
        </p>
    </div>
</div>
</body>
</html>