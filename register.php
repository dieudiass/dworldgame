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
    <title>D Game World: Register Account</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        .space{
            margin: 25px 0;
        }
        #sign-in{
            margin-top: 35px;
            padding-top: 20px;
        }
        .reg-form-header{
            padding-bottom: 10px;
            font-weight: bold;
            text-align: center;
        }

        div#OR {
            height: 30px;
            width: 30px;
            border: 1px solid #C2C2C2;
            border-radius: 50%;
            font-weight: bold;
            line-height: 28px;
            text-align: center;
            font-size: 12px;
            position: absolute;
            right: -16px;
            top: 40%;
            z-index: 1;
            background: #DFDFDF;
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
        <div class="col-md-7 col-md-offset-1" style="border-right: 1px dotted #C2C2C2;padding-right: 30px;">
            <h3 class="reg-form-header">Register an account</h3>
            <?php
            //Check if the user was redirect from the checkout
            if(isset($_GET["checkout"])){
                $_SESSION["user"]["checkout"] = 1;
            }
            //Check if the request is valid
            if(isset($_POST["reg"])){
                //Check if the session has been started
                if(isset($_SESSION["user"])){
                    //Check if the session token is equal to the token passed
                    if($_POST["reg"] == $_SESSION["user"]["token"]){
                        //Check if the password and confirm password match
                        if($_POST["password"] == $_POST["confirm"]) {
                            //Setup the database
                            $db = mysqli_connect("localhost", "root", "", "dee");
                            //Clean form data
                            $username = htmlentities($_POST["username"]);
                            $firstname = htmlentities($_POST["firstname"]);
                            $lastname = htmlentities($_POST["lastname"]);
                            $email = htmlentities($_POST["email"]);
                            $gender = htmlentities($_POST["gender"]);
                            $contact = htmlentities($_POST["contact"]);
                            $password = htmlentities($_POST["password"]);
                            $confirm = htmlentities($_POST["confirm"]);
                            //Create user
                            $user = new User($db);
                            $user->setUsername($username);
                            $user->setFirstname($firstname);
                            $user->setLastname($lastname);
                            $user->setEmail($email);
                            $user->setGender($gender);
                            $user->setPassword($password);
                            $user->setContact($contact);
                            $user->setAccountType(2);
                            if($user->create()){
                                $user->fetchLastID();

                                //Update the user session variable
                                $_SESSION["user"]["logged"] = base64_encode(1);
                                $_SESSION["user"]["username"] = base64_encode($username);

                                if($_SESSION["user"]["checkout"] == 1){
                                    header("Location: checkout.php");
                                }

                                ?>
                                <div class="alert alert-success">Your account was created successfully</div>
                                <?php
                            }else{
                                ?>
                                <div class="alert alert-danger">Dear user, there was a problem creating your account, please try again later</div>
                                <?php
                            }
                        }else{
                            ?>
                            <div class="alert alert-danger">Dear user user passwords do not match, please fix that</div>
                            <?php
                        }
                    }else{
                        ?>
                        <div class="alert">Dear user, this is an illegal session, please report it to admin</div>
                        <?php
                    }
                }else{
                    ?>
                    <div class="alert alert-danger">Dear user, the session has not started as yet</div>
                    <?php
                }
            }
            ?>
            <form action="" method="post" role="form" class="form-horizontal" id="reg-form">
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input name="username" type="text" class="form-control" id="username" placeholder="Username" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstname" class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-md-6">
                                <input name="firstname" type="text" class="form-control" id="firstname" placeholder="Firstname" />
                            </div>
                            <div class="col-md-6">
                                <input name="lastname" type="text" class="form-control" placeholder="Lastname" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input name="email" type="email" class="form-control" id="email" placeholder="Email" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Gender</label>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="gender" class="form-control">
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>
                            <div class="col-md-9">
                                <input name="contact" type="text" class="form-control" placeholder="Mobile" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input name="password" type="password" class="form-control" id="password" placeholder="Password" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm-password" class="col-sm-2 control-label">Confirm</label>
                    <div class="col-sm-10">
                        <input name="confirm" type="password" class="form-control" id="confirm-password" placeholder="Confirm Password" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-10">
                        <input type="hidden" name="reg" value="<?php if(isset($_SESSION["user"]["token"])){ echo $_SESSION["user"]["token"]; } ?>">
                        <input type="submit" name="register" class="btn btn-primary form-control" value="Register" />
                    </div>
                </div>
            </form>

            <div id="OR" class="hidden-xs">
                OR</div>
        </div>
        <div class="col-md-3">
            <div class="row text-center" id="sign-in">
                <div class="col-md-12">
                    <h3 class="reg-form-header">Already a member?</h3>
                </div>
                <div class="col-md-8 col-md-offset-2">
                    <p>Click below to sign in</p>
                    <div class="space"></div>
                    <div class="btn-group btn-group-justified">
                        <a href="login.php" class="btn btn-primary">Sign in</a>
                    </div>
                </div>
            </div>
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
<script>
</script>
</html>