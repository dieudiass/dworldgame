<?php
//Include classes
require_once("../Classes/Session.php");
require_once("../Classes/AccountType.php");
require_once("../Classes/Admin.php");
require_once("../Classes/User.php");
//Start the session
session_start();
//Create database
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
//Create account type class
$type = new AccountType($db);
//Check if the account type was passed
if(isset($_GET["type"]) == false){
    //Redirect the user
    header("Location: " . $url);
}
//Check if the type exists
$type_id = (int) $_GET["type"];
$type->setType($type_id);
if($type->get() == false){
    //Redirect the user
    header("Location: " . $url);
}

$type->setType($admin->getAccountType());
$type->get();
$types = $type->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: Edit Account Type</title>
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
                    <div class="profile-usertitle-name"><?php echo $admin->getFirstname() . " " . $admin->getLastname(); ?></div>
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
                        <li>
                            <a href="games.php">
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
                <h4 class="text-center">Edit Account Type</h4>
                <hr>
                <?php
                //Check if the form was submitted
                if (isset($_POST["edit-type"])) {
                    //Check if the session has been started
                    if (isset($_SESSION["user"])) {
                        //Check if the form submission is valid
                        if ($_POST["edit-type"] == $_SESSION["user"]["token"]) {
                            //Check if the type exists
                            $type_id = (int) $_POST["type_id"];
                            $type->setType($type_id);
                            if ($type->get()) {
                                //Check if we are deleting the type
                                if (isset($_POST["submit-del"])) {
                                    //Remove the type
                                    if ($type->del()) {
                                        //AccountType has been removed
                                        ?>
                                        <div class="alert alert-success"><?php echo $type->getName(); ?> type has been removed successfully</div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="alert alert-danger">Oops! There was a problem removing <?php echo $type->getName(); ?>. Please try again</div>
                                        <?php
                                    }
                                }
                                //Check if we are editing the type
                                if (isset($_POST["submit-edit"])) {
                                    //Check if the country is valid
                                    $name = htmlentities($_POST["name"]);
                                    $desc = htmlentities($_POST["desc"]);
                                    //Update the type
                                    $type->setName($name);
                                    $type->setDescription($desc);
                                    //Check if the type was updated
                                    if ($type->update()) {
                                        ?>
                                        <div class="alert alert-success"><?php echo $type->getName(); ?> was updated successfully</div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="alert alert-danger">Oops! There was a error updating <?php echo $type->getName() ?>. Please try again</div>
                                        <?php
                                    }
                                }
                            } else {
                                //The requested type does not exist
                                ?>
                                <div class="alert alert-warning">Ooops! We could not find the type you requested. Please try another one.</div>
                                <?php
                            }
                        } else {
                            //Invalid request
                            ?>
                            <div class="alert alert-danger">There was a problem with your request, please submit it again</div>
                            <?php
                        }
                    } else {
                        //Session not started
                        ?>
                        <div class="alert alert-warning">Dear user, the session has not been started yet. Please refresh the page</div>
                        <?php
                    }
                }
                //Set the type id
                $type->setType($type_id);
                //Check if the type is valid
                if ($type->get()) {
                    ?>
                    <form method="post" action="" role="form">
                        <div class="form-group">
                            <label class="control-label" for="name">Name</label>
                            <input name="name" type="text" class="form-control" id="name" value="<?php echo $type->getName(); ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="desc">Description</label>
                            <input name="desc" type="text" class="form-control" id="desc" value="<?php echo $type->getDescription(); ?>">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="edit-type" value="<?php if (isset($_SESSION["user"]["token"])) { echo $_SESSION["user"]["token"]; } ?>">
                            <input type="hidden" name="type_id" value="<?php echo $type_id; ?>" />
                            <button name="submit-edit" type="submit" class="btn btn-info btn-block" id="submit">Update</button>
                            <button name="submit-del" type="submit" class="btn btn-danger btn-block" id="submit">Remove</button>
                        </div>
                    </form>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-danger">Ooops! The requested type could not be found. Please try another one.</div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>