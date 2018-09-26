<?php
//Start the session
session_start();
//require the needed files to start
require_once("Classes/Session.php");
require_once("Classes/Cart.php");
require_once("Classes/User.php");
//Create the database
$db = mysqli_connect("localhost", "root", "", "dee");
//Create the session class
$session = new Session();
//Create the cart class
$cart = new Cart($db);
//check if the user is logged in already
if(isset($_SESSION["user"]["logged"])){
    $username = base64_decode($_SESSION["user"]["username"]);

    $user = new User($db);
    $user->setUsername($username);

    if($user->get() == true){
        //Redirect user
        $url = "";
        //Check whether the user is a user or admin
        if($user->getAccountType() == 1)
            $url = "admin/dashboard.php";
        elseif($user->getAccountType() == 2)
            $url = "customer/dashboard.php";

        header("Location: " . $url);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>D Game World: Login</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        .login {
            text-align: center;
            width: 30rem;
            border-radius: 0.5rem;
            bottom: 0;
            left: 0;
            margin: 4rem auto;
            background-color: #fff;
            padding: 2rem;
        }

        .container .glyphicon-lock {
            font-size: 8rem;
            margin-top: 2rem;
            color: #f96145;
        }

        input {
            width: 100%;
            margin-bottom: 1.4rem;
            padding: 1rem;
            background-color: #ecf2f4;
            border-radius: 0.2rem;
            border: none;
        }
        h2 {
            margin-bottom: 3rem;
            font-weight: bold;
            color: #ababab;
        }
        .btn {
            border-radius: 0.2rem;
        }
        .btn .glyphicon {
            font-size: 3rem;
            color: #fff;
        }
        .full-width {
            background-color: #8eb5e2;
            width: 100%;
            -webkit-border-top-right-radius: 0;
            -webkit-border-bottom-right-radius: 0;
            -moz-border-radius-topright: 0;
            -moz-border-radius-bottomright: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .gap{
            margin-top: 100px;
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
                <li>
                    <a href="contact.php">Contact</a>
                </li>
                <li class="active">
                    <a href="#">Sign-in</a>
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
        <div class="col-md-4 col-md-offset-4">
            <h2 class="text-center">Login</h2>
            <?php
            if(isset($_GET["checkout"])){
                $_SESSION["user"]["checkout"] = 1;
            }

            if(isset($_POST["login"])){
                if(isset($_SESSION["user"])){
                    if($_POST["login"] == $_SESSION["user"]["token"]){
                        $username = htmlentities($_POST["username"]);
                        $password = htmlentities($_POST["password"]);
                        $db = mysqli_connect("localhost", "root", "", "dee");
                        $user = new User($db);
                        $user->setUsername($username);
                        $user->setPassword($password);
                        if($user->login() != true){
                            ?>
                            <div class="alert-dismissable alert-danger">Dear user, incorrect username and password combination. Please try again</div>
                            <?php
                        }else{
                            //Update the user session variable
                            $_SESSION["user"]["logged"] = base64_encode(1);
                            $_SESSION["user"]["username"] = base64_encode($user->getUsername());

                            //Check the user type and redirect them to the appropriate page
                            if($_SESSION["user"]["checkout"] == 1){
                                header("Location: checkout.php");
                            }elseif($user->getAccountType() == 1){
                                $url = "Location: admin/dashboard.php";
                                header($url);
                            }elseif($user->getAccountType() == 2){
                                $url = "Location: customer/dashboard.php";
                                header($url);
                            }else{
                                ?>
                                <div class="alert alert-warning">Dear user, something went wrong, please report this error</div>
                                <?php
                            }
                        }
                    }else{
                        ?>
                        <div class="alert alert-danger">Dear user, in illegal login was attempted</div>
                        <?php
                    }
                }else{
                    ?>
                    <div class="alert alert-danger">Dear user, the session has not started yet</div>
                    <?php
                }
            }
            ?>
            <form action="" method="post">
                <input type="text" name="username" placeholder="username" required>
                <input type="password" name="password" placeholder="password" required>
                <input type="hidden" name="login" value="<?php if(isset($_SESSION["user"])){ echo $_SESSION["user"]["token"];} ?>"/>
                <button class="btn btn-default full-width">
                    <span class="glyphicon glyphicon-off"></span>
                </button>
                <p><small class="gap"><a href="#">Forgot your password?</a></small></p>
                <p><small class="gap">Don't have an account? <a href="register.php">Register</a></small></p>
            </form>
        </div>
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
</html>