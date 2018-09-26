<?php
//Include classes
require_once("../Classes/Session.php");
require_once("../Classes/AccountType.php");
require_once("../Classes/Sale.php");
require_once("../Classes/SaleItem.php");
require_once("../Classes/User.php");
session_start();

$db = mysqli_connect("localhost", "root", "", "dee");

$session = new Session();

$user = new User($db);
//Check if the user is logged
if(isset($_SESSION["user"])){
    if(isset($_SESSION["user"]["logged"])){
        if(base64_decode($_SESSION["user"]["logged"]) != 1){
            //User is not logged in, redirect
        }else{
            //User is logged, check if user is valid
            if(isset($_SESSION["user"]["username"])){
                $username = base64_decode($_SESSION["user"]["username"]);
                $user->setUsername($username);
                //Check if the user does not exist
                if($user->get() == false){
                    //User is not valid, redirect
                }
            }else{
                //User session is not set, redirect
            }
        }
    }else{
        //The session login is not set, redirect
    }
}else{
    //Session is not set, redirect
}
//Create the account type class
$type = new AccountType($db);
$type->setType($user->getAccountType());
$type->get();
//Check if the game reference was not passed
if(isset($_GET["id"]) == false){
    //Redirect the user
    header("Location: dashboard.php");
}
$sale_ref_no = (int) $_GET["id"];
//Create sale type class
$sale = new SaleItem($db);
$sale->setSaleReference($sale_ref_no);
$sales = $sale->fetchAllUsingSale();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer: Orders</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin-user.css">
    <link rel="stylesheet" href="../css/customer.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div id="header">
    </div>
</div>

<div class="container">
    <div class="row profile">
        <div class="col-md-3">
            <div class="profile-sidebar">
                <!-- SIDEBAR USER TITLE -->
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name"><?php echo $user->getFirstname() . " " . $user->getLastname(); ?></div>
                    <div class="profile-usertitle-job"><?php echo $type->getName(); ?></div>
                </div>
                <!-- END SIDEBAR USER TITLE -->
                <!-- SIDEBAR BUTTONS -->
                <div class="profile-userbuttons">
                    <div class="row margin">
                        <a href="../index.php" class="btn btn-success btn-sm">Website</a>
                    </div>
                    <div class="row margin">
                        <a href="dashboard.php?logout=1" class="btn btn-danger btn-sm">Logout</a>
                    </div>
                </div>
                <!-- END SIDEBAR BUTTONS -->
                <!-- SIDEBAR MENU -->
                <div class="profile-usermenu">
                    <ul class="nav">
                        <li>
                            <a href="dashboard.php">
                                <i class="glyphicon glyphicon-home"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="profile.php">
                                <i class="glyphicon glyphicon-wrench"></i>
                                Profile
                            </a>
                        </li>
                        <li class="active">
                            <a href="#">
                                <i class="glyphicon glyphicon-shopping-cart"></i>
                                Orders
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- END MENU -->
            </div>
        </div>
        <div class="col-md-9">
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-center">Sales</h3>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Game</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(empty($sales)){
                                ?>
                                <tr>
                                    <td colspan="5">
                                        <p class="lead">There are currently no sale order items on this sale</p>
                                    </td>
                                </tr>
                                <?php
                            }else{
                                foreach($sales as $s){
                                    ?>
                                    <tr>
                                        <td><?php echo $s["Game"]; ?></td>
                                        <td><?php echo $s["Quantity"]; ?></td>
                                        <td>R<?php echo number_format($s["Amount"], 2); ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>