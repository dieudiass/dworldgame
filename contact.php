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

        .nbm{
            margin-top: 20px;
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
                <li>
                    <a href="products.php">Products</a>
                </li>
                <li class="active">
                    <a href="#">Contact</a>
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
        <div class="col-sm-8 contact-form">
            <form action="" id="contact" method="post" class="form" role="form">
                <?php
                if(isset($_POST["contact"])){
                    $name = stripslashes($_POST["name"]);
                    $email = stripslashes($_POST["email"]);
                    $message = stripslashes($_POST["message"]);
                    $headers = "Reply-To: ". $name ."<". $email .">" . "\r\n" .
                    mail("info@dgameworld.com", "Contact Message", $message, $headers);
                }
                ?>
                <div class="row">
                    <h3>Contact Us</h3>

                    <hr>

                    <div class="col-xs-6 col-md-6 form-group">
                        <input class="form-control" id="name" name="name" placeholder="Name" type="text" required autofocus />
                    </div>
                    <div class="col-xs-6 col-md-6 form-group">
                        <input class="form-control" id="email" name="email" placeholder="Email" type="email" required />
                    </div>
                </div>
                <textarea class="form-control" id="message" name="message" placeholder="Message" rows="5"></textarea>
                <br />
                <input type="submit" name="contact" class="btn btn-primary" value="Send">
            </form>
        </div>

        <div class="col-sm-4">
            <h3>Contact Details</h3>
            <hr>
            <address>
                <strong>Email:</strong> <a href="mailto:info@dgameworld.com"> info@dgameworld.com</a>
                <br>
                <br>
                <strong>Phone:</strong> (011)123-4567
            </address>
        </div>
    </div>
    </div>
</body>
</html>