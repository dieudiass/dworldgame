<?php
//Include classes
require_once("../Classes/Session.php");
require_once("../Classes/AccountType.php");
require_once("../Classes/Admin.php");
require_once("../Classes/Category.php");

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
//Create category type class
$category = new Category($db);
//Check if the id was set
if(isset($_GET["id"])){
    //Clean the id
    $id = (int) $_GET["id"];
}
$categories = $category->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: Edit Category</title>
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
                <h4 class="text-center">Edit Category</h4>
                <?php
                //Check if the form was submitted
                if (isset($_POST["edit-category"])) {
                    //Check if the session has been started
                    if (isset($_SESSION["user"])) {
                        //Check if the form submission is valid
                        if ($_POST["edit-category"] == $_SESSION["user"]["token"]) {
                            //Check if the category exists
                            $category->setCategory($id);
                            if ($category->get()) {
                                //Check if we are deleting the category
                                if (isset($_POST["submit-del"])) {
                                    //Remove the category
                                    if ($category->del()) {
                                        //Category has been removed
                                        ?>
                                        <div class="alert alert-success"><?php echo $category->getName(); ?> category has been removed successfully
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="alert alert-danger">Oops! There was a problem removing the requested category. Please try again
                                        </div>
                                        <?php
                                    }
                                }
                                //Check if we are editing the category
                                if (isset($_POST["submit-edit"])) {
                                    //Check if the country is valid
                                    $name = htmlentities($_POST["name"]);
                                    $desc = htmlentities($_POST["desc"]);
                                    //Update the category
                                    $category->setName($name);
                                    $category->setDescription($desc);
                                    //Check if the category was updated
                                    if ($category->update()) {
                                        ?>
                                        <div class="alert alert-success"><?php echo $category->getName(); ?> was
                                            updated successfully
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="alert alert-danger">Oops! There was a error
                                            updating <?php echo $category->getName() ?>. Please try again
                                        </div>
                                        <?php
                                    }
                                }
                            } else {
                                //The requested category does not exist
                                ?>
                                <div class="alert alert-warning">Ooops! We could not find the category you
                                    requested. Please try another one.
                                </div>
                                <?php
                            }
                        } else {
                            //Invalid request
                            ?>
                            <div class="alert alert-danger">There was a problem with your request, please submit it
                                again
                            </div>
                            <?php
                        }
                    } else {
                        //Session not started
                        ?>
                        <div class="alert alert-warning">Dear user, the session has not been started yet. Please
                            refresh the page
                        </div>
                        <?php
                    }
                }
                //Set the category id
                $category->setCategory($id);
                //Check if the category is valid
                if ($category->get()) {
                    ?>
                    <form method="post" action="" role="form">
                        <div class="form-group">
                            <label class="control-label" for="name">Name</label>
                            <input name="name" type="text" class="form-control" id="name"
                                   value="<?php echo $category->getName(); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="desc">Description</label>
                            <input name="desc" type="text" class="form-control" id="desc"
                                   value="<?php echo $category->getDescription(); ?>" required>
                        </div>
                        <div class="form-group">
                            <input name="edit-category" type="hidden"
                                   value="<?php if (isset($_SESSION["user"]["token"])) {
                                       echo $_SESSION["user"]["token"];
                                   } ?>">
                            <button name="submit-edit" type="submit" class="btn btn-info btn-block" id="submit">
                                Update
                            </button>
                            <button name="submit-del" type="submit" class="btn btn-danger btn-block" id="submit">
                                Remove
                            </button>
                        </div>
                    </form>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-danger">Ooops! The requested category could not be found. Please try
                        another one.
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>