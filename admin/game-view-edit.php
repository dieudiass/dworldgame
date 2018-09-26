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
//Check if the game reference was not passed
if(isset($_GET["game_ref"]) == false){
    //Redirect the user
    header("Location: dashboard.php");
}
//Create the game class
$game = new Game($db);
//Check if the game exists
$game_ref = (int) $_GET["game_ref"];
$game->setGameRefNo($game_ref);

if($game->get() == false){
    //Redirect the user
    header("Location: " . $url);
}
//Create the category class
$category = new Category($db);
$categories = $category->getAll();
//Create the supplier class
$supplier = new Supplier($db);
$suppliers = $supplier->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: Edit Game</title>
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
                <h4 class="text-center">Edit Game</h4>
                <hr>
                <?php
                //Check if the form was submitted
                if (isset($_POST["edit-game"])) {
                    //Check if the session has been started
                    if (isset($_SESSION["user"])) {
                        //Check if the form submission is valid
                        if ($_POST["edit-game"] == $_SESSION["user"]["token"]) {
                            //Check if the game exists
                            $game_ref = (int) $_POST["game_ref"];
                            $game->setGameRefNo($game_ref);
                            if ($game->get()) {
                                //Check if we are deleting the game
                                if (isset($_POST["submit-del"])) {
                                    //Remove the game
                                    if ($game->del()) {
                                        //Game has been removed
                                        ?>
                                        <div class="alert alert-success"><?php echo $game->getName(); ?> has been removed successfully</div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="alert alert-danger">Oops! There was a problem removing <?php echo $game->getName(); ?>. Please try again</div>
                                        <?php
                                    }
                                }
                                //Check if we are editing the game
                                if (isset($_POST["submit-edit"])) {
                                    //Prepare the errors
                                    $errors = array();
                                    //Check if the category is valid
                                    $cat = (int) $_POST["category"];
                                    $found_category = false;
                                    $category->setCategory($cat);

                                    if($category->get()){
                                        $found_category = true;
                                    }else{
                                        $errors[] = "<div class='alert alert-danger'>Ooops! There was a problem, please re-select the category</div>";
                                    }
                                    //Check if the supplier is valid
                                    $supp = (int) $_POST["supplier"];
                                    $found_supplier = false;
                                    $supplier->setSupplier($supp);

                                    if($supplier->get()){
                                        $found_supplier = true;
                                    }else{
                                        $errors[] = "<div class='alert alert-danger'>Ooops! There was a problem, please re-select  the supplier</div>";
                                    }

                                    if($found_category == true && $found_supplier == true){
                                        //Check if the price is valid
                                        $price = (double) $_POST["price"];
                                        if($price < 1){
                                            $errors[] = "<div class='alert alert-danger'>Oops! Please re-enter a valid price</div>";
                                        }
                                        //Check if the stock is valid
                                        $stock = (int) $_POST["stock"];
                                        if($stock < 0){
                                            $errors[] = "<div class='alert alert-danger'>Oops! Please re-enter a valid stock quantity</div>";
                                        }
                                        //Check if there are no errors
                                        if(empty($errors)){
											$image = $_FILES["image"]["name"];
                                            $name = htmlentities($_POST["name"]);
                                            $desc = htmlentities($_POST["desc"]);
                                            //Update the game
                                            $game->setName($name);
                                            $game->setDescription($desc);
                                            $game->setPrice($price);
                                            $game->setStock($stock);
                                            $game->setCategory($cat);
                                            $game->setSupplier($supp);
											$game->setImage($image);
                                            //Check if the game was updated
                                            if($game->update()){
                                                ?>
                                                <div class="alert alert-success"><?php echo $game->getName(); ?> was updated successfully</div>
                                                <?php
                                            }else{
                                                ?>
                                                <div class="alert alert-danger">Oops! There was a error updating <?php echo $game->getName() ?>. Please try again</div>
                                                <?php
                                            }
                                        }else{
                                            //Display the error(s)
                                            foreach($errors as $error){
                                                echo $error;
                                            }
                                        }
                                    }else{
                                        //Either the category or the supplier is invalid
                                        //Check of the errors were set
                                        if(empty($errors)){
                                            //Something went wrong somewhere
                                            ?>
                                            <div class="alert alert-danger">Oops! There was an error updating the game, please try agin</div>
                                            <?php
                                        }else{
                                            foreach($errors as $error){
                                                echo $error;
                                            }
                                        }
                                    }
                                }
                            } else {
                                //The requested game does not exist
                                ?>
                                <div class="alert alert-warning">Ooops! We could not find the game you
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
                //Set the game reference number
                $game->setGameRefNo($game_ref);
                //Check if the game is valid
                if ($game->get()) {
                    ?>
                    <form method="post" action="" role="form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label" for="name">Name</label>
                            <input name="name" type="text" class="form-control" id="name"
                                   value="<?php echo $game->getName(); ?>" required/>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="desc">Description</label>
                            <textarea name="desc" class="form-control" id="desc"><?php echo $game->getDescription(); ?></textarea>
                        </div>
						<div class="form-group">
							<label class="control-label" for="qty">Image</label>
							<input name="image" type="file" id="image" placeholder="" value="">
						</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="price">Price</label>
                                    <input name="price" type="text" class="form-control" id="price" value="<?php echo $game->getPrice(); ?>" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="stock">Stock</label>
                                    <input name="stock" type="number" class="form-control" id="stock" value="<?php echo $game->getStock(); ?>" min="0" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="category">Category</label>
                                    <select name="category" class="form-control input-sm" id="category">
                                        <?php
                                        foreach($categories as $category){
                                            ?>
                                            <option value="<?php echo $category["Category_id"]; ?>" <?php if($category["Category_id"] == $game->getCategory()){ echo "selected";} ?>><?php echo $category["Name"]; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="supplier">Supplier</label>
                                    <select name="supplier" class="form-control input-sm" id="supplier">
                                        <?php
                                        foreach($suppliers as $supplier){
                                            ?>
                                            <option value="<?php echo $supplier["Supplier_id"]; ?>" <?php if($supplier["Supplier_id"] == $game->getSupplier()){ echo "selected";} ?>><?php echo $supplier["Name"]; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="edit-game" value="<?php if (isset($_SESSION["user"]["token"])) {echo $_SESSION["user"]["token"];} ?>">
                            <input type="hidden" name="game_ref" value="<?php echo $game_ref; ?>"/>
                            <button name="submit-edit" type="submit" class="btn btn-info btn-block" id="submit">Update</button>
                            <button name="submit-del" type="submit" class="btn btn-danger btn-block" id="submit">Remove</button>
                        </div>
                    </form>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-danger">Ooops! The requested game could not be found. Please try another one.</div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>