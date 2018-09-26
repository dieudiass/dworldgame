<?php
if(isset($_GET["export"]))
{
    $file = $_GET["export"];
    header('Content-Length: ' . ob_get_length());
    header('Content-Type: text/plain');
    header("Content-Disposition: attachment; filename=\"$file\"");
    header('Pragma: no-cache');
    header('Expires: 0');
    $key = file_get_contents($file);
    echo $key;
    exit();
}
include_once("Classes/Session.php");
include_once("Classes/Cart.php");
include_once("Classes/Game.php");
include_once("Classes/GameKey.php");
include_once("Classes/Sale.php");
include_once("Classes/SaleItem.php");
include_once("Classes/User.php");
//Start the session
session_start();
//Create the database
$db = mysqli_connect("localhost", "root", "", "dee");
//Create the session class
$session = new Session();
//Create the cart class
$cart = new Cart($db);
//Create user class
$user = new User($db);
//Get all the games
$games = $cart->getAll();
//Get the items total
$total = $cart->subTotal();
//Clear the cart
$cart->clear();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>D Game World: Invoice</title>
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
        <div class="col-md-12">
            <div class="row">
                <?php
                //Check if the form was submitted
                if(isset($_POST["order"])){
                    //Check if the session has been set
                    if(isset($_SESSION["user"])){
                        //Check if the passed token and the session token match
                        if($_POST["order"] == $_SESSION["user"]["token"]){
                            //Get the user
                            $username = base64_decode($_SESSION["user"]["username"]);
                            $user->setUsername($username);
                            $user->get();
                            //Clean the data
                            $firstname = htmlspecialchars($_POST["firstname"]);
                            $lastname = htmlspecialchars($_POST["lastname"]);
                            $email = htmlspecialchars($_POST["email"]);
                            $contact = htmlspecialchars($_POST["contact"]);
                            //$address1 = htmlspecialchars($_POST["address1"]);
                            //$address2 = htmlspecialchars($_POST["address2"]);
                            //$city = htmlspecialchars($_POST["city"]);
                            //$postal = htmlspecialchars($_POST["pcode"]);
                            //Check if the firstname is valid
                            //Check if the lastname is valid
                            //Check if the email is valid
                            //Check if the contact is valid
                            //Check if the first address is valid
                            //Check if the second address is valid
                            //Check if the city is valid
                            //Check if the postal is valid
                            $date = date("Y-m-d");
                            $time = date("H:i:s");
                            $sale = new Sale($db);
                            $sale->setUsername($user->getUsername());
                            $sale->setDate($date);
                            $sale->setTime($time);
                            $sale->setTotalAmount($total + 25);
                            //Check if the sale could be created
                            if($sale->create()){
                                //Get the last sale id created
                                $sale->fetchLastSaleUsingUsername();
                                //Create the sale items
                                foreach($games as $game){
                                    $item = new SaleItem($db);
                                    $item->setSaleReference($sale->getSaleReference());
                                    $item->setGameReference($game["Game_ref_no"]);
                                    $item->setQuantity($game["Qty"]);
                                    $item->setAmount($game["Price"] * $game["Qty"]);
                                    $item->create();
									$item->updateStockQuantity($game["Qty"]);
                                }
                                //Show the user the invoice
                                ?>
                                <h3>Order #<?php echo $sale->getSaleReference(); ?></h3>

                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Customer</h4>
                                        <p>Firstname: <?php echo $user->getFirstname(); ?></p>
                                        <p>Lastname: <?php echo $user->getLastname(); ?></p>
                                        <p>Contact: <?php echo $user->getContact(); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h4>Items</h4>
                                        <?php
                                        //Show the games purchased
                                        foreach($games as $game){
                                            ?>
                                            <p><?php echo $game["Name"]; ?> X <?php echo $game["Qty"]; ?></p>
                                            <?php
                                        }
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Game Keys</h4>

                                                <?php
                                                foreach($games as $g){
                                                    $key = new GameKey($db);
                                                    $key->setGameReferenceNumber($g["Game_ref_no"]);
                                                    $key->setStatus(0);
                                                    $key->create();
                                                    $pk = $key->getGameKeySerialNumber();
                                                    $file = "keys/";
                                                    $file .= $sale->getSaleReference();
                                                    $file .= "_";
                                                    $file .= $g["Game_ref_no"];
                                                    $file .= ".txt";
                                                    file_put_contents($file, $pk, FILE_APPEND|LOCK_EX);
                                                    ?>
                                                    <p>
                                                        <a href="?export=<?php echo $file; ?>"><?php echo $g["Name"]; ?></a>
                                                    </p>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <p>Delivery: R25.00</p>

                                <p><strong>Order Total: R<?php echo ($total + 25); ?></strong></p>
                                <?php
                            }else{
                                //The sale could not be made
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script>
</script>
</body>
</html>