<?php
//Include classes
require_once("../Classes/AccountType.php");
require_once("../Classes/Admin.php");
require_once("../Classes/Session.php");
session_start();

$db = mysqli_connect("localhost", "root", "", "dee");

$session = new Session();
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
//Create the account type class
$type = new AccountType($db);
$types = $type->getAll();
//Create an array of genders
$genders = array();
$genders[] = array("M", "Male");
$genders[] = array("F", "Female");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: Profile</title>
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
                        <li class="active">
                            <a href="#">
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
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-center">User Profile</h3>
                        <?php
                        //Check if the form was submitted
                        if (isset($_POST["edit-user"])) {
                            //Check if the session has been started
                            if (isset($_SESSION["user"])) {
                                //Check if the form submission is valid
                                if ($_POST["edit-user"] == $_SESSION["user"]["token"]) {
                                    //Check if the user exists
                                    $admin->setUsername("Dido");
                                    if ($admin->get()) {
                                        //Check if we are deleting the user
                                        if (isset($_POST["submit-del"])) {
                                            //Remove the user
                                            if ($admin->del()) {
                                                //User has been removed
                                                ?>
                                                <div class="alert alert-success"><?php echo $admin->getUsername(); ?> has been removed successfully</div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="alert alert-danger">Oops! There was a problem removing <?php echo $admin->getUsername(); ?>. Please try again
                                                </div>
                                                <?php
                                            }
                                        }
                                        //Check if we are editing the user
                                        if (isset($_POST["submit-edit"])) {
                                            //Prepare the errors
                                            $errors = array();
                                            //Check if the gender is valid
                                            //Get the keys of from the gender arrays to find out if the passed on gender is valid
                                            $arr_genders = array_keys($genders);
                                            $gender = htmlentities($_POST["gender"]);
                                            $found_gender = false;

                                            if(in_array($gender, $arr_genders)){
                                                $found_gender = true;
                                            }else{
                                                $errors[] = "<div class='alert alert-danger'>Ooops! There was a problem, please re-select the gender</div>";
                                            }
                                            //Check if the account type is valid
                                            $account = (int) $_POST["account"];
                                            $found_type = false;
                                            $type->setType($account);

                                            if($type->get()){
                                                $found_type = true;
                                            }else{
                                                $errors[] = "<div class='alert alert-danger'>Ooops! There was a problem, please re-select  the account type</div>";
                                            }
                                            //Check if the firstname is in a valid format
                                            $firstname = htmlentities($_POST["firstname"]);
                                            //Check if the lasttname is in a valid format
                                            $lastname = htmlentities($_POST["lastname"]);
                                            //Check if the email is in a valid format
                                            $email = htmlentities($_POST["email"]);
                                            $valid_email = false;
                                            $email_regex = "@^[a-zA-Z0-9]{2,50}[\\@][a-zA-Z0-9]{2,40}[\\.]([a-z]{3})?([a-z]{2,3}[\\.][a-z]{2,3})?$@";
                                            if(preg_match($email_regex, $email) == true){
                                                //The email is in a valid format
                                                $valid_email = true;
                                            }else{
                                                //The email is in an invalid format
                                                $errors[] = "<div class='alert alert-danger'>Ooops! Your email was not in a valid format</div>";
                                            }
                                            //Check if the contact number is in a valid format
                                            $contact = htmlentities($_POST["contact"]);
                                            //Check if there were no errors
                                            if($found_gender == true && $found_type == true && $valid_email == true){
                                                //Update the user
                                                $admin->setAccountType($account);
                                                $admin->setGender($gender);
                                                $admin->setContact($contact);
                                                $admin->setEmail($email);
                                                $admin->setFirstname($firstname);
                                                $admin->setLastname($lastname);
                                                //Check if the admin was updated
                                                if($admin->update()){
                                                    ?>
                                                    <div class="alert alert-success"><?php echo $admin->getUsername(); ?> was updated successfully</div>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <div class="alert alert-danger">Oops! There was a error updating <?php echo $admin->getUsername() ?>. Please try again</div>
                                                    <?php
                                                }
                                            }else{
                                                //Either the category or the supplier is invalid
                                                //Check of the errors were set
                                                if(empty($errors)){
                                                    //Something went wrong somewhere
                                                    ?>
                                                    <div class="alert alert-danger">Oops! There was an error updating the user, please try agin</div>
                                                    <?php
                                                }else{
                                                    foreach($errors as $error){
                                                        echo $error;
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        //The requested user does not exist
                                        ?>
                                        <div class="alert alert-warning">Ooops! We could not find the user you requested. Please try another one.</div>
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
                        //Set the user id
                        $admin->setUsername("Dido");
                        //Check if the user is valid
                        if ($admin->get()) {
                            ?>
                            <form method="post" action="" role="form">
                                <div class="form-group">
                                    <label class="control-label" for="username">Username</label>
                                    <input name="username" type="text" class="form-control form-control-static" id="username" value="<?php echo $admin->getUsername(); ?>" disabled/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="email">E-mail</label>
                                    <input name="email" type="text" class="form-control" id="email" value="<?php echo $admin->getEmail(); ?>" required/>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="firstname">Firstname</label>
                                            <input name="firstname" type="text" class="form-control" id="firstname" value="<?php echo $admin->getFirstname(); ?>" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="stock">Lastname</label>
                                            <input name="lastname" type="text" class="form-control" id="lastname" value="<?php echo $admin->getLastname(); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="gender">Gender</label>
                                            <select name="gender" class="form-control input-sm" id="category">
                                                <?php
                                                foreach($genders as $gender){
                                                    ?>
                                                    <option value="<?php echo $gender[0]; ?>" <?php if($gender[0] == $admin->getGender()){ echo "selected"; } ?>><?php echo $gender[1]; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="account">Account</label>
                                            <select name="account" class="form-control input-sm" id="supplier">
                                                <?php
                                                foreach($types as $type){
                                                    ?>
                                                    <option value="<?php echo $type["Type_id"]; ?>" <?php if($type["Type_id"] == $admin->getAccountType()){ echo "selected"; } ?>><?php echo $type["Name"]; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="contact">Contact</label>
                                    <input name="contact" type="text" class="form-control" id="contact" value="<?php echo $admin->getContact(); ?>" required/>
                                </div>
                                <div class="form-group">
                                    <input name="edit-user" type="hidden" value="<?php if (isset($_SESSION["user"]["token"])) { echo $_SESSION["user"]["token"]; } ?>">
                                    <button name="submit-edit" type="submit" class="btn btn-info btn-block" id="submit">Update</button>
                                </div>
                            </form>
                            <?php
                        } else {
                            ?>
                            <div class="alert alert-danger">Ooops! The requested user could not be found. Please try another one.
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>