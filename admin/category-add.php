<?php
//Include classes
require_once("../Classes/AccountType.php");
require_once("../Classes/Admin.php");
require_once("../Classes/Category.php");
require_once("../Classes/Session.php");

session_start();

$session = new Session();
$db = mysqli_connect("localhost", "root", "", "dee");
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
$type->setType($admin->getAccountType());
$type->get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: Add A Category</title>
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
                        <li>
                            <a href="types.php">
                                <i class="glyphicon glyphicon-user"></i>
                                Account Types
                            </a>
                        </li>
                        <li class="active">
                            <a href="#">
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
                <h4 class="text-center">Add New Category</h4>
                <?php
                //Check if the form has been submitted
                if(isset($_POST["add-category"])){
                    //Check if the session has been set
                    if(isset($_SESSION["user"])){
                        //Check if the request is valid
                        if($_POST["add-category"] == $_SESSION["user"]["token"]){
                            //Create errors
                            $errors = array();
                            //Clean the request
                            $name = htmlentities($_POST["name"]);
                            $desc = htmlentities($_POST["desc"]);
                            //Check the length of the name
                            if(strlen($name) < 4){
                                $errors[] = "The name of the category is too short, it must be more than three letters.";
                            }
                            //Check the length of the description
                            if(strlen($desc) < 6){
                                $errors[] = "The description of the category is too short, it must be more than five letters.";
                            }
                            //Check if there are no errors
                            if(empty($errors)){
                                //Create the category
                                $category = new Category($db);
                                $category->setName($name);
                                $category->setDescription($desc);
                                //Check if the category was created successfully
                                if($category->create()){
                                    ?>
                                    <div class="alert alert-success"><?php echo $name; ?> category was added succesfully!</div>
                                    <?php
                                }else{
                                    ?>
                                    <div class="alert alert-success">There was a problem adding the category <?php echo $name; ?>, please try again later. If this problem persists, please report this error.</div>
                                    <?php
                                }
                            }else{
                                //There were errors
                                foreach($errors as $error){
                                    ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php
                                }
                            }
                        }else{
                            //Invalid request, tokens do not match, could be a hack attempt
                            ?>
                            <div class="alert alert-danger">Dear user, there was a problem, hack attempt</div>
                            <?php
                        }
                    }else{
                        //Invalid request, session not started as yet
                        ?>
                        <div class="alert alert-danger">Dear user, we are currently experiencing a problem</div>
                        <?php
                    }
                }
                ?>
                <form method="post" action="" id="add-category" role="form">
                    <div class="form-group">
                        <label class="control-label" for="name">Name</label>
                        <input name="name" type="text" class="form-control" id="name" value="<?php if(isset($_POST["name"])){ echo $_POST["name"];} ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="desc">Description</label>
                        <input name="desc" type="text" class="form-control" id="desc" value="<?php if(isset($_POST["desc"])){ echo $_POST["desc"];} ?>" required>
                    </div>
                    <div class="form-group">
                        <input name="add-category" type="hidden" value="<?php if(isset($_SESSION["user"]["token"])){ echo $_SESSION["user"]["token"]; } ?>">
                        <button name="submit" type="submit" class="btn btn-info btn-block" id="submit">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>