<?php
include_once("Classes/Session.php");
include_once("Classes/Cart.php");
include_once("Classes/Game.php");
include_once("Classes/User.php");
//Start the session
session_start();
//Create the database
$db = mysqli_connect("localhost", "root", "", "dee");
//Create the session class
$session = new Session();
//Create the cart class
$cart = new Cart($db);
//Create the user class
$user = new User($db);
//Get all the games
$games = $cart->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>D Game World: Order Form</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        .fix-mt{
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
        <form method="post" action="invoice.php">
            <?php
            //Check if there are items in the cart
            if($cart->numberOfItems() > 0) {
                //Validate user
                $logged = false;
                //Check if user session is set
                if(isset($_SESSION["user"])){
                    if(isset($_SESSION["user"]["logged"])){
                        if(base64_decode($_SESSION["user"]["logged"]) == 1){
                            //User is logged in
                            //User is logged, check if user is valid
                            if(isset($_SESSION["user"]["username"])){
                                $username = base64_decode($_SESSION["user"]["username"]);
                                $user->setUsername($username);
                                //Check if the user does not exist
                                if($user->get() == true){
                                    //Check if user is a customer
                                    if($user->getAccountType() == 2){
                                        //User is logged in
                                        $logged = true;
                                    }
                                }
                            }
                        }
                    }
                }
                //Check if user validation was successful
                if($logged == false){
                    //User is not logged in
                    ?>
                    <div class="jumbotron fix-mt">
                        <p>Dear user, you need to be a member in order to complete your purchase</p>
                        <p><small>Already a member? <a href="login.php?checkout=1"> Sign-in</a></small></p>
                        <p><small>New to us? <a href="register.php?checkout=1">Sign up now</a></small></p>
                    </div>
                    <?php
                }else{
                    //Validation was successful
                    ?>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Your Details</h3>
                                <hr>
                                <div class="form-group">
                                    <label for="first">Firstname</label>
                                    <input type="text" name="firstname" class="form-control" id="first" value="<?php echo $user->getFirstname(); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="last">Lastname</label>
                                    <input type="text" name="lastname" class="form-control" id="last" value="<?php echo $user->getLastname(); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" class="form-control" id="email" value="<?php echo $user->getEmail(); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="contact">Contact</label>
                                    <input type="text" name="contact" class="form-control" id="contact" value="<?php echo $user->getContact(); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3>Payment Details</h3>
                                <hr>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="">Credit Card</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="ccard" class="form-control" id="card" value="" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="ccard" class="form-control" id="card" value="" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="ccard" class="form-control" id="card" value="" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="ccard" class="form-control" id="card" value="" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="well">
                            <h4>Your Order</h4>
                            <hr>
                            <table class="table">
                                <?php
                                foreach($games as $game){
                                    ?>
                                    <tr>
                                        <td><?php echo $game["Name"]; ?></td>
                                        <td><?php echo $game["Qty"]; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr class="lead">
                                    <td>Sub total</td>
                                    <td id="sub-total">R<?php echo number_format($cart->subTotal(), 2); ?></td>
                                </tr>
                                <tr class="lead">
                                    <td>Delivery</td>
                                    <td>R25.00</td>
                                </tr>
                                <tr class="lead">
                                    <td class="success">Total</td>
                                    <td class="success" id="total">R<?php echo number_format($cart->subTotal() + 25, 2); ?></td>
                                </tr>
                            </table>
                            <input type="hidden" name="order" value="<?php if(isset($_SESSION["user"])){ echo $_SESSION["user"]["token"]; } ?>">
                            <input type="submit" class="btn btn-success" value="Make Order">
                            <a href="products.php" class="btn btn-default">Continue Shopping</a>
                        </div>
                    </div>
                    <?php
                }
            }else{
                //There are no items in the cart
                ?>
                <div class="jumbotron fix-mt">
                    <p>Dear user, there are no items in your cart</p>
                    <a href="products.php" class="btn btn-success">Start Shopping</a>
                </div>
            <?php
            }
            ?>
        </form>
    </div>
</div>
<script>
</script>
</body>
</html>