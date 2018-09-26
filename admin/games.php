<?php
//Include classes
require_once("../Classes/Session.php");
require_once("../Classes/Admin.php");
require_once("../Classes/Category.php");
require_once("../Classes/Game.php");
require_once("../Classes/Supplier.php");
//Start the session
session_start();
//Create the database
$db = mysqli_connect("localhost", "root", "", "dee");
//Create the session class
$session = new Session();
//Create the admin class
$admin = new Admin($db);
//Set the redirect page
$url = "../index.php";
//Check if the user is logged
if(isset($_SESSION["user"])){
    if(isset($_SESSION["user"]["logged"])){
        if(base64_decode($_SESSION["user"]["logged"]) != 1){
            //User is not logged in, redirect
        }else{
            //User is logged, check if user is valid
            if(isset($_SESSION["user"]["username"])){
                $username = base64_decode($_SESSION["user"]["username"]);
                $admin->setUsername($username);
                //Check if the user does not exist
                if($admin->get() == false){
                    //User is not valid, redirect
                    header("Location: " . $url);
                }
            }else{
                //User session is not set, redirect
                header("Location: " . $url);
            }
        }
    }else{
        //The session login is not set, redirect
        header("Location: " . $url);
    }
}else{
    //Session is not set, redirect
    header("Location: " . $url);
}
//Create the game category class
$category = new Category($db);
//Create the supplier class
$supplier = new Supplier($db);
//Create the game class
$game = new Game($db);
$games = $game->getAllUsingLimit("0, 20");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: View Games</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin-user.css">
    <link rel="stylesheet" href="../css/customer.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <style></style>
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
                    <div class="profile-usertitle-name"><?php echo $admin->getFirstname() . " " . $admin->getLastname(); ?></div>
                    <div class="profile-usertitle-job"><?php echo $admin->getAccountType(); ?></div>
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
                        <li>
                            <a href="types.php">
                                <i class="glyphicon glyphicon-user"></i>
                                Account Types
                            </a>
                        </li>
                        <li>
                            <a href="categories.php">
                                <i class="glyphicon glyphicon-piggy-bank"></i>
                                Categories
                            </a>
                        </li>
                        <li class="active">
                            <a href="#">
                                <i class="glyphicon glyphicon-piggy-bank"></i>
                                Games
                            </a>
                        </li>
                        <li>
                            <a href="orders.php">
                                <i class="glyphicon glyphicon-shopping-cart"></i>
                                Orders
                            </a>
                        </li>
                        <li>
                            <a href="suppliers.php">
                                <i class="glyphicon glyphicon-asterisk"></i>
                                Suppliers
                            </a>
                        </li>
                        <li>
                            <a href="users.php">
                                <i class="glyphicon glyphicon-user"></i>
                                Users
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- END MENU -->
            </div>
        </div>
        <div class="col-md-9">
            <div class="profile-content">
                <h4 class="text-center">List of All Games</h4>
                <hr>
                <table class="table table-bordered">
                    <thead>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Supplier</th>
                    </thead>
                    <tbody>
                        <?php
                        if(empty($games)){
                            ?>
                            <tr>
                                <td colspan="5">
                                    <p class="lead">There are currently no games in collection</p>
                                </td>
                            </tr>
                        <?php
                        }else{
                            foreach($games as $g){
                            ?>
                            <tr>
                                <td><a href="game-view-edit.php?game_ref=<?php echo $g["Game_ref_no"]; ?>"><?php echo $g["Name"]; ?></a></td>
                                <td>R<?php echo $g["Price"]; ?></td>
                                <td><?php echo $g["Stock"]; ?></td>
                                <td><?php echo $g["CategoryName"]; ?></td>
                                <td><?php echo $g["SupplierName"]; ?></td>
                            </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                    <th colspan="5">
                        <ul class="pager">
                            <li class="previous">
                                <a href="#">&larr; Prev</a>
                            </li>
                            <li class="next">
                                <a href="#">Next &rarr;</a>
                            </li>
                        </ul>
                    </th>
                    </tfoot>
                </table>
                <a class="btn btn-primary" href="game-add.php">Add Game</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>