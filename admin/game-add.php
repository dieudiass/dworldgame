<?php
//Include classes
require_once("../Classes/Session.php");
require_once("../Classes/AccountType.php");
require_once("../Classes/Admin.php");
require_once("../Classes/Category.php");
require_once("../Classes/Game.php");
require_once("../Classes/Supplier.php");

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
//Create game class
$game = new Game($db);
//Create category class
$category = new Category($db);
//Get a list of all suppliers
$categories = $category->getAll();
//Create supplier class
$sup = new Supplier($db);
//Get a list of all suppliers
$suppliers = $sup->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: Add A Game</title>
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
                <h4 class="text-center">Add New Game</h4>
                <?php
                //Check if the form has been submitted
                if(isset($_POST["add-game"])){
                    //Check if the session has been set
                    if(isset($_SESSION["user"])){
                        //Check if the request is valid
                        if($_POST["add-game"] == $_SESSION["user"]["token"]){
                            //Create errors
                            $errors = array();
                            //Clean the form data
							$image = $_FILES["image"]["name"];
                            $name = htmlentities($_POST["name"]);
                            $desc = htmlentities($_POST["desc"]);
                            $price = (double) htmlentities($_POST["price"]);
                            $qty = (int) htmlentities($_POST["stock"]);
                            //Set the category
                            $cat = (int) $_POST["category"];
                            $category->setCategory($cat);
                            //Set the supplier
                            $supplier = (int) $_POST["supplier"];
                            $sup->setSupplier($supplier);
                            //Check the length of name
                            if(strlen($name) < 4){
                                $errors[] = "The name of the game must be more than four letters";
                            }
                            //Check the length of name
                            if(strlen($desc) < 6){
                                $errors[] = "The description of the game must be more than 5 letters";
                            }
                            //Check if the price is positive
                            if($price < 1){
                                $errors[] = "The price of the game must be positive";
                            }
                            //Check if the price is positive
                            if($qty < 0){
                                $errors[] = "The game quantity must be positive";
                            }
                            //Check if the given category exists
                            if($category->get() != true){
                                //Invalid request, the supplier does not exist
                                $errors[] = "The selected category does not exist, please select another one";
                            }
                            //Check if the given supplier exists
                            if($sup->get() != true){
                                //Invalid request, the supplier does not exist
                                $errors[] = "The selected supplier does not exist, please select another one";
                            }
                            //Check if there are no errors
                            if(empty($errors)){
                                //Create game
                                $game->setName($name);
                                $game->setDescription($desc);
                                $game->setPrice($price);
                                $game->setStock($qty);
                                $game->setCategory($cat);
                                $game->setSupplier($supplier);
								$game->setImage($image);
                                if($game->create()){
                                    ?>
                                    <div class="alert alert-success"><?php echo $name; ?> was added succesfully!</div>
                                    <?php
                                }else{
                                    ?>
                                    <div class="alert alert-success">There was a problem adding <?php echo $name; ?>, please try again later. If this problem persists, please report this error.</div>
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
                <form method="post" action="" role="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="control-label" for="name">Name</label>
                        <input name="name" type="text" class="form-control" id="name" required value="<?php if(isset($_POST["name"])){ echo $_POST["name"];} ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="desc">Description</label>
                        <input name="desc" type="text" class="form-control" id="desc" value="<?php if(isset($_POST["desc"])){ echo $_POST["desc"];} ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="price">Price</label>
                        <input name="price" type="text" class="form-control" id="price" value="<?php if(isset($_POST["price"])){ echo $_POST["price"];} ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="qty">Stock</label>
                        <input name="stock" type="number" class="form-control" id="qty" placeholder="Quantity available in stock" value="<?php if(isset($_POST["stock"])){ echo $_POST["stock"];} ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="supplier">Category</label>
                        <select name="category" class="form-control" id="category" required>
                            <?php
                            foreach($categories as $category){
                                ?>
                                <option value="<?php echo $category["Category_id"]; ?>"><?php echo $category["Name"]; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="supplier">Supplier</label>
                        <select name="supplier" class="form-control" id="supplier" required>
                            <?php
                            foreach($suppliers as $supplier){
                                ?>
                                <option value="<?php echo $supplier["Supplier_id"]; ?>"><?php echo $supplier["Name"]; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div
					<div class="form-group">
                        <label class="control-label" for="qty">Image</label>
                        <input name="image" type="file" id="image" placeholder="" value="">
                    </div>
                    <div class="form-group">
                        <input name="add-game" type="hidden" value="<?php if(isset($_SESSION["user"]["token"])){ echo $_SESSION["user"]["token"]; } ?>">
                        <button name="submit" type="submit" class="btn btn-info btn-block" id="submit">Add Game</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>