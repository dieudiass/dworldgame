<?php
class SaleItem
{
    //Attributes
    /**
     * @var Mysqli Database connection
     */
    private $db;
    private $sale_ref;
    private $game_ref;
    private $qty;
    private $amount;

    /**
     * Supplier constructor.
     * @param Mysqli $db
     */
    public function __construct(Mysqli $db)
    {
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getSaleReference()
    {
        return $this->sale_ref;
    }

    /**
     * @return mixed
     */
    public function getGameReference()
    {
        return $this->game_ref;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->qty;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }
    //Setters
    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param mixed $sale_ref
     */
    public function setSaleReference($sale_ref)
    {
        $this->sale_ref = $sale_ref;
    }

    /**
     * @param mixed $game_ref
     */
    public function setGameReference($game_ref)
    {
        $this->game_ref = $game_ref;
    }

    /**
     * @param mixed $qty
     */
    public function setQuantity($qty)
    {
        $this->qty = $qty;
    }
    //Functions
    public function create()
    {
        $sql = "INSERT INTO SaleItem(Sale_ref_no, Game_ref_no, Quantity, Amount)";
        $sql .= " ";
        $sql .= "VALUES(?, ?, ?, ?)";
        //Prepare the sql query to be executed (prevention of sql injection)
        $stmt = mysqli_prepare($this->db, $sql);
        //Bind the sale data (prevention of sql injection)
        mysqli_stmt_bind_param($stmt, "iiid", $this->sale_ref, $this->game_ref, $this->qty, $this->amount);
        //Add the sale to the database
        //Check if the sale was added successfully
        if(mysqli_stmt_execute($stmt) == true){
            //Close the database connection
            mysqli_stmt_close($stmt);
            //The game was added successfully
            return true;
        }else{
            //Close the database connection
            mysqli_stmt_close($stmt);
            //There was an error adding the game
            return false;
        }
    }

    public function del()
    {
        $sql = "DELETE FROM SaleItem WHERE Sale_ref_no = ? AND Game_ref_no = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $this->sale_ref, $this->game_ref);
        //Check if the game was added successfully
        if(mysqli_stmt_execute($stmt) == true){
            //Close the database connection
            mysqli_stmt_close($stmt);
            //The game was added successfully
            return true;
        }else{
            //Close the database connection
            mysqli_stmt_close($stmt);
            //There was an error adding the game
            return false;
        }
    }

    public function fetch()
    {
        $sql = "SELECT * FROM SaleItem WHERE Sale_ref_no = ? AND Game_ref_no = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $this->sale_ref, $this->game_ref);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $this->sale_ref, $this->game_ref, $this->qty, $this->amount);
        if(mysqli_stmt_fetch($stmt) == true){
            mysqli_stmt_close($stmt);
            return true;
        }else{
            mysqli_stmt_close($stmt);
            return false;
        }
    }

    public function fetchAll()
    {
        $sql = "SELECT * FROM SaleItem";
        $res = mysqli_query($this->db, $sql);
        $suppliers = array();
        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
        {
            $suppliers[] = $row;
        }
        return $suppliers;
    }

    public function fetchAllUsingSale()
    {
        //The database query to execute
        $sql = "SELECT Game.Game_ref_no as GameNo, Game.Name as Game, Quantity, Amount FROM SaleItem, Game WHERE SaleItem.Game_ref_no = Game.Game_ref_no AND SaleItem.Sale_ref_no = ?";
        //Preparing the database query
        $stmt = mysqli_prepare($this->db, $sql);
        //Bind the query
        mysqli_stmt_bind_param($stmt, "i", $this->sale_ref);
        //Running the database query
        //Checking if it ran
        if(mysqli_stmt_execute($stmt))
        {
            $result_set = mysqli_stmt_get_result($stmt);
            $sales = array();
            //Fetch all sales
            while($db_row = mysqli_fetch_array($result_set, MYSQLI_ASSOC)){
                $sales[] = $db_row;
            }
            //Return sales
            return $sales;
        }else{
            //Return false
            return false;
        }
    }

    public function update()
    {
        $sql = "UPDATE SaleItem SET Quantity = ?, Amount = ? WHERE Sale_ref_no = ? AND Game_ref_no = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "idii", $this->qty, $this->amount, $this->sale_ref, $this->game_ref);
        //Check if the game was updated successfully
        if(mysqli_stmt_execute($stmt) == true){
            //Close the database connection
            mysqli_stmt_close($stmt);
            //The game was added successfully
            return true;
        }else{
            //Close the database connection
            mysqli_stmt_close($stmt);
            //There was an error adding the game
            return false;
        }
    }
}