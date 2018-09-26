<?php
include_once("Game.php");
class Cart
{
    //Attributes
    /**
     * @var Mysqli
     */
    private $db;

    /**
     * Cart constructor.
     * @param Mysqli $db
     */
    public function __construct(Mysqli $db)
    {
        $this->db = $db;
        //Check if the session has not been started and start it
        if(!isset($_SESSION["cart"])){
            //Create the session with empty details
            $_SESSION["cart"] = array();
        }
    }
    //Functions
    public function add($game, $qty)
    {
        //Check if the game is already in the cart
        if($this->has($game))
        {
            //Add one more copy of this game to the shopping cart
            $qty = $this->get($game)["qty"] + $qty;
        }
        //Update the shopping cart
        $this->update($game, $qty);
    }

    public function clear()
    {
        unset($_SESSION["cart"]);
    }

    /**
     * Checks whether a game ID is valid or not
     * @param int $game
     */
    public function exists($game)
    {
        $sql = "SELECT Game_ref_no FROM Game WHERE Game_ref_no = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $game);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id);
        if(mysqli_stmt_fetch($stmt) == true){
            mysqli_stmt_close($stmt);
            return true;
        }else{
            mysqli_stmt_close($stmt);
            return false;
        }
    }

    public function get($game)
    {
        return $_SESSION["cart"][$game];
    }

    public function getAll()
    {
        //Check if the session is not set
        if(!isset($_SESSION["cart"])){
            //Throw an exception
            throw new Exception("Please start the session first");
        }
        //Get a list of all the keys stored in the cart (game IDs)
        $game_ids = array_keys($_SESSION["cart"]);
        //Check if there are no games in the shopping cart
        if(count($game_ids) < 1){
            //Return null
            return null;
        }
        //Create an empty games array
        $games = array();
        //Create a game object
        $game = new Game($this->db);
        //Fetch the games in the shopping cart
        $games = $game->getAllUsingID($game_ids);
        //Add the requested quantity to each game
        //Create a counter variable
        for($i = 0; $i < count($games); $i++){
            $games[$i]["Qty"] = $_SESSION["cart"][$games[$i]["Game_ref_no"]]["qty"];
        }
        //Return games
        return $games;
    }

    public function has($game)
    {
        return isset($_SESSION["cart"][$game]);
    }

    public function numberOfItems()
    {
        if(isset($_SESSION["cart"])){
            return count($_SESSION["cart"]);
        }else{
            return 0;
        }
    }

    public function remove($game)
    {
        unset($_SESSION["cart"][$game]);
    }

    public function subTotal()
    {
        $games = $this->getAll();
        $total = 0;

        foreach($games as $game){
            $total += $game["Price"] * $game["Qty"];
        }

        return $total;
    }

    public function update($game, $qty)
    {
        //Check if the product does not exist
        if(!$this->exists($game)){
            throw new Exception("The requested game could not be found, please pick another one");
        }
        //Check if the quantity is 0, remove the product from the shopping cart
        if($qty == 0){
            //Remove the game
            $this->remove($game);
            return;
        }
        //Create a game object in order to check if there is enough stock for the request
        $gameObj = new Game($this->db);
        $gameObj->setGameRefNo($game);
        //Check if there isn't enough stock for the user's request
        if(!$gameObj->hasStockFor($qty)){
            throw new Exception("We have ran out of copies, please check with us again later");
        }
        //Update the shopping cart
        $_SESSION["cart"][$game] = array("id" => $game, "qty" => $qty);
    }
}